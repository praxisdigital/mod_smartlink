<?php

namespace mod_smartlink\external;

use mod_smartlink\app\factory as base_factory;
use core_external\external_function_parameters;
use core_external\external_value;

/* @codeCoverageIgnoreStart */
defined('MOODLE_INTERNAL') || die();
/* @codeCoverageIgnoreEnd */

global $CFG;
require_once($CFG->libdir.'/moodlelib.php');
require_once(__DIR__.'/../../vendor/autoload.php');

class prompt_openai extends \core_external\external_api
{
    public static function execute(int $contextid, string $jsondata) 
    {
        try {

            $base_factory = base_factory::make();
            $data = json_decode($jsondata);

            self::validate_parameters(self::execute_parameters(), [
                'contextid' => $contextid, 
                'jsondata' => $jsondata,
            ]);

            $context = $base_factory->moodle()->context()->instance_by_id($contextid);
            self::validate_context($context);

            $prompt = $base_factory->webservices()->openai()->repository()->prompt(
                $data->courseid, 
                $data->instanceid, 
                $data->prompt ?? '', 
                $data->promptid ?? 0
            );

            return json_encode([
                'success' => true,
                'data' => $prompt,
                'message' => 'Success',
            ]);

        } catch (\Exception $e) {
            return json_encode([
                'success' => false,
                'message' => 'Error: '.$e->getMessage(),
            ]);
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
}
