<?php

namespace mod_smartlink\app\moodle;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

use local_pxsdk\app\v7\moodle\factory as sdk_moodle_factory;
use mod_smartlink\app\factory as base_factory;
use curl;

global $CFG;
require_once($CFG->dirroot.'/lib/filelib.php');

class factory extends sdk_moodle_factory implements interfaces\factory 
{
    private base_factory $base_factory;

    function __construct(base_factory $base_factory)
    {
        $this->base_factory = $base_factory;
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
