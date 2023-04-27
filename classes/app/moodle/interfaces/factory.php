<?php

namespace mod_smartlink\app\moodle\interfaces;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

use local_pxsdk\app\v7\moodle\interfaces\factory as sdk_moodle_factory_interface;
use mod_smartlink\app\moodle\context\interfaces\factory as context_factory_interface;
use curl;

interface factory extends sdk_moodle_factory_interface 
{
    public function context(): context_factory_interface;
    public function curl(): curl;
}
