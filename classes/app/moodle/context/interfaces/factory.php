<?php
namespace mod_smartlink\app\moodle\context\interfaces;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

use context;

interface factory 
{
    public function instance_by_id(int $id, int $strictness = MUST_EXIST): context;
}
