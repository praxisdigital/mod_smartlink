<?php

namespace mod_smartlink\app;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

class factory implements interfaces\factory
{
    private static self $instance;

    public static function make(): self
    {
        return self::$instance ??= new static();
    }

    public function moodle(): moodle\interfaces\factory
    {
        return new moodle\factory($this);
    }

    public function smartlink(): smartlink\interfaces\factory
    {
        return new smartlink\factory($this);
    }
}
