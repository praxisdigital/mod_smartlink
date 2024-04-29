<?php

namespace mod_smartlink\app\moodle;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

use mod_smartlink\app\factory as base_factory;
use curl;
use moodle_database;

global $CFG;
require_once($CFG->dirroot.'/lib/filelib.php');

class factory implements interfaces\factory
{
    private base_factory $base_factory;

    function __construct(base_factory $base_factory)
    {
        $this->base_factory = $base_factory;
    }

    public function db(): moodle_database {
        global $DB;
        return $DB;
    }

    public function config(string $component, ?string $name = null): mixed {
        return get_config($component, $name);
    }

    public function context(): context\interfaces\factory
    {
        return new context\factory($this->base_factory);
    }

    public function curl(): curl
    {
        return new curl();
    }
}
