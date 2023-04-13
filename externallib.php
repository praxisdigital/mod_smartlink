<?php
require_once($CFG->libdir . "/externallib.php");
require_once($CFG->dirroot . "/webservice/externallib.php");
require_once($CFG->libdir . '/adminlib.php');

class mod_smartlink_external extends external_api
{

    /**
     * Calls the Open AI end point with the prompt that was passed by a student
     *
     * @param [type] $contextid
     * @param [type] $jsonformdata
     * @return void
     */
    public static function get_ai_version($contextid, $jsondata)
    {
        global $DB;
        try {

            $params = self::validate_parameters(self::get_ai_version_parameters(), ['contextid' => $contextid, 'jsondata' => $jsondata]);
            $context = context::instance_by_id($params['contextid'], MUST_EXIST);

            self::validate_context($context);

            $serialiseddata = json_decode($params['jsondata']);

            $courseid   = $serialiseddata->courseid;
            $instanceid = $serialiseddata->instanceid;

            $smartlink = $DB->get_record('smartlink', array('course' => $courseid , 'id' => $instanceid));
            $prompturl = $smartlink->url;

            $description = $prompt = $payload = $config = $url = "";
            self::getOpenAIRequestConfig($payload, $config, $url);

            if (isset($serialiseddata->promptid)) {
                $promptsetting = $DB->get_record('smartlink_prompts', array('id' => $serialiseddata->promptid));
                $prompt = $promptsetting->prompt;
                $description = $promptsetting->description;
            } 
            else {
                $prompt = $serialiseddata->prompt;
            }

            $payload['prompt'] = str_replace('{url}', $prompturl, $prompt);

            $result = self::sendCurlRequest($url, $payload, "POST", $config);

            if ($result === false) {
                throw new Exception("An error occured while calling the API.");
            }

            $result = self::jsonToObject($result);

            if ($result === false) {
                throw new Exception("An error occured while parsing the API request.");
            }

            $genText = "";

            if (count($result->choices)) {
                $genText = $result->choices[0]->text;
                $genText = rawurldecode($genText);
            }

            $data = new stdClass();
            $data->prompt_text = $payload['prompt'];;
            $data->description = $description;
            $data->result = $genText;

            return json_encode(
                array(
                    'success' => true,
                    'data' => $data,
                    'message' => "Successful",
                    'errors'  => []
                )
            );
        } catch (Exception $ex) {
            return json_encode(
                array(
                    'success' => false,
                    'message' => "Failure : " . $ex->getMessage(),
                    'errors'  => [$ex]
                )
            );
        }
    }

    /**
     * Retrieves the AI request attempt record by the current date, the assignment id and the user id
     *
     * @param [type] $assignmentid
     * @param [type] $userid
     * @return void
     */
    public static function getAIAttemptRecord($assignmentid, $userid)
    {
        global $DB;
        return $DB->get_record('pxaiwriter_api_attempts', array('assignment' => $assignmentid, 'userid' => $userid, 'api_attempt_date' => strtotime("today")));
    }

    public static function get_ai_version_parameters()
    {
        return new external_function_parameters(
            array(
                'contextid' => new external_value(PARAM_INT, 'The context id for the event'),
                'jsondata' => new external_value(PARAM_RAW, 'The data from form, encoded as a json array')
            )
        );
    }

    public static function get_ai_version_is_allowed_from_ajax()
    {
        return true;
    }

    public static function get_ai_version_returns()
    {
        return new external_value(PARAM_RAW, 'Update response');
    }

    /**
     * Helper function to get the Open AI API request  payload and header configuration formatted 
     *
     * @param [type] $payload
     * @param [type] $config
     * @param [type] $url
     * @return void
     */
    static function getOpenAIRequestConfig(&$payload, &$config, &$url)
    {

        $adminConfig = self::getPluginAdminSettings();

        $config = [
            'Content-Type: application/json',
            'Authorization:' . $adminConfig->authorization,
            'Accept: application/json',
        ];

        $payload = array(
            "model" => $adminConfig->model,
            "prompt" => "",
            "temperature" => (float)$adminConfig->temperature,
            "max_tokens" =>  (int)$adminConfig->max_tokens,
            "top_p" => (float)$adminConfig->top_p,
            "frequency_penalty" => (float)$adminConfig->frequency_penalty,
            "presence_penalty" => (float)$adminConfig->presence_penalty
        );

        $url = $adminConfig->url;
    }

    /**
     * Helper function to convert a json string to an object recursively 
     *
     * @param [type] $json
     * @return object
     */
    static function jsonToObject($json)
    {
        $i = 0;
        while (!is_object($json)) {
            if ($i > 3) {
                $json = false;
                break;
            }
            $json = json_decode($json);
            $i++;
        }
        return $json;
    }

    /**
     * Helper function to send a custom CURL
     *
     * @param [type] $endpoint
     * @param array $data
     * @param string $method
     * @param array $headerConfig
     * @return void
     */
    static function sendCurlRequest($endpoint, $data = [], $method = "GET", $headerConfig = array('Content-Type: application/json', 'Accept: application/json'))
    {

        try {

            $postdata = json_encode($data);

            $url = $endpoint;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerConfig);
            $result = curl_exec($ch);

            if ($result === false) {
                echo 'Curl error: ' . curl_error($ch);
            }

            curl_close($ch);
            return $result;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Summary : Gets the pxaiwriter admin settings from config_plugins table
     *              PLEASE RE-USE THIS FUNCTION!!!
     *
     * @param [type] $setting
     * @return object
     */
    static function getPluginAdminSettings($setting = "", $pluginName = 'assignsubmission_pxaiwriter')
    {

        // last_modified_by
        // api_key
        // presence_penalty
        // frequency_penalty
        // top_p
        // max_tokens
        // temperature
        // model
        // authorization
        // content_type
        // url
        // default
        // installrunning
        // version
        // granularity

        global $DB;
        if ($setting) {
            $dbparams = array(
                'plugin' => $pluginName,
                'name' => $setting
            );
            $result = $DB->get_record('config_plugins', $dbparams, '*', IGNORE_MISSING);

            if ($result) {
                return $result->value;
            }

            return false;
        }

        $dbparams = array(
            'plugin' => $pluginName,
        );
        $results = $DB->get_records('config_plugins', $dbparams);

        $config = new stdClass();
        if (is_array($results)) {
            foreach ($results as $setting) {
                $name = $setting->name;
                $config->$name = $setting->value;
            }
        }
        return $config;
    }
}
