<?php

namespace mod_smartlink\external;

use mod_smartlink\app\factory as base_factory;
use external_function_parameters;
use external_value;

/* @codeCoverageIgnoreStart */
defined('MOODLE_INTERNAL') || die();
/* @codeCoverageIgnoreEnd */

global $CFG;
require_once($CFG->libdir.'/externallib.php');
require_once($CFG->libdir.'/moodlelib.php');
require_once(__DIR__.'/../../lib.php');

class get_available_prompts extends \external_api
{
    public static function execute() 
    {
        try {

            $prompts = get_available_prompts();

            return json_encode([
                'success' => true,
                'data' => $prompts,
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
        return new external_function_parameters([]);
    }

    public static function execute_returns()
    {
        return new external_value(PARAM_RAW);
    }
}
