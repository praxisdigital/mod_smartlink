<?php

namespace mod_smartlink\app\smartlink;

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

    public function get_settings(int $courseid, int $instanceid): object
    {
        return $this->base_factory->moodle()->db()->get_record('smartlink', ['course' => $courseid, 'id' => $instanceid]);
    }

    public function get_prompt(int $promptid): object
    {
        return $this->base_factory->moodle()->db()->get_record('smartlink_prompts', ['id' => $promptid]);
    }
}
