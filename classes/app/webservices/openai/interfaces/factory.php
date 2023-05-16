<?php

namespace mod_smartlink\app\webservices\openai\interfaces;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

interface factory 
{
    public function repository(): repository;
}
