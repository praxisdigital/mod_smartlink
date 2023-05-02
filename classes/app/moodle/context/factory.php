<?php

namespace mod_smartlink\app\moodle\context;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

use mod_smartlink\app\factory as base_factory;
use context;

class factory implements interfaces\factory 
{
    private base_factory $base_factory;

    function __construct(base_factory $base_factory)
    {
        $this->base_factory = $base_factory;
    }

    public function instance_by_id(int $id, int $strictness = MUST_EXIST): context
    {
        return context::instance_by_id($id, $strictness);
    }
}
