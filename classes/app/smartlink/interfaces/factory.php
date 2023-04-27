<?php
namespace mod_smartlink\app\smartlink\interfaces;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

interface factory 
{
    public function get_settings(int $courseid, int $instanceid): object;
    public function get_prompt(int $promptid): object;
}
