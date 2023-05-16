<?php

namespace mod_smartlink\app\webservices\interfaces;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

use mod_smartlink\app\webservices\openai\interfaces\factory as openai_factory_interface;

interface factory 
{
    public function openai(): openai_factory_interface;
}
