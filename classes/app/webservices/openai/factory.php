<?php

namespace mod_smartlink\app\webservices\openai;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

use mod_smartlink\app\factory as base_factory;

class factory implements interfaces\factory 
{
    private base_factory $base_factory;

    function __construct(base_factory $base_factory)
    {
        $this->base_factory = $base_factory;
    }

    public function repository(): interfaces\repository
    {
        return new repository($this->base_factory);
    }
}
