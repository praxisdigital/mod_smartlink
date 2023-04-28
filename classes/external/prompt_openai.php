<?php

namespace mod_smartlink\external;

use mod_smartlink\app\factory as base_factory;
use external_function_parameters;
use external_value;
use andreskrey\Readability\Readability;
use andreskrey\Readability\Configuration;

/* @codeCoverageIgnoreStart */
defined('MOODLE_INTERNAL') || die();
/* @codeCoverageIgnoreEnd */

global $CFG;
require_once($CFG->libdir.'/externallib.php');
require_once($CFG->libdir.'/moodlelib.php');
require_once(__DIR__.'/../../vendor/autoload.php');

class prompt_openai extends \external_api
{
    private static base_factory $base_factory;

    public static function execute(int $contextid, string $jsondata) 
    {
        try {

            self::$base_factory = base_factory::make();

            self::validate_parameters(self::execute_parameters(), [
                'contextid' => $contextid, 
                'jsondata' => $jsondata,
            ]);

            $context = self::$base_factory->moodle()->context()->instance_by_id($contextid);
            self::validate_context($context);
            
            $parameters = self::get_parameters($jsondata);
            $request = self::prepare_request();
            $smartlink = self::$base_factory->smartlink()->get_settings($parameters->courseid, $parameters->instanceid);

            $prompt = $parameters->prompt;
            $description = '';

            if ($parameters->promptid) {
                $record = self::$base_factory->smartlink()->get_prompt($parameters->promptid);
                $prompt = $record->prompt;
                $description = $record->description;
            }

            $prompt_text = $prompt;

            if (strpos($prompt, '{url}')) {

                $prompt_text = str_replace('{url}', $smartlink->url, $prompt);

                $curl = self::$base_factory->moodle()->curl();
                $html = $curl->get($smartlink->url);
                
                if ($curl->error) {
                    return self::error($curl->error);
                }

                $readability = new Readability(new Configuration());
                $readability->parse($html);
                $article = $readability->getTitle().'. '.strip_tags($readability->getContent());

                $prompt = str_replace('{url}', $article, $prompt);

            }

            $request->payload['messages'][] = [
                'role' => 'user',
                'content' => $prompt,
            ];

            $openai = self::$base_factory->moodle()->curl()->post($request->url, json_encode($request->payload), ['HTTPHEADER' => $request->headers]);
            $response = json_decode($openai, true);
            $content = $response['choices'][0]['message']['content'];

            return self::success([
                'description' => $description,
                'prompt_text' => $prompt_text,
                'prompt_real' => $prompt,
                'result' => $content,
            ]);

        } catch (\Exception $e) {
            return self::error($e->getMessage());
        }
    }

    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'contextid' => new external_value(PARAM_INT),
            'jsondata' => new external_value(PARAM_RAW),
        ]);
    }

    public static function execute_returns()
    {
        return new external_value(PARAM_RAW);
    }

    protected static function get_parameters(string $jsondata): object
    {
        $data = json_decode($jsondata);

        return (object)[
            'courseid' => $data->courseid,
            'instanceid' => $data->instanceid,
            'prompt' => $data->prompt ?? '',
            'promptid' => $data->promptid ?? false,
        ];
    }

    protected static function prepare_request(): object
    {
        $config = self::$base_factory->moodle()->config('mod_smartlink');

        return (object)[
            'url' => $config->openai_endpoint,
            'headers' => [
                'Content-Type: application/json',
                'Authorization: Bearer '.$config->openai_token,
                'Accept: application/json',
            ],
            'payload' => [
                'model' => $config->openai_model,
                'messages' => [],
                'temperature' => (float)$config->openai_temperature,
            ],
        ];
    }

    protected static function error(string $message): string
    {
        return json_encode([
            'success' => false,
            'message' => 'Error: '.$message,
        ]);
    }

    protected static function success(array $data, string $message = 'Success'): string
    {
        return json_encode([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ]);
    }
}
