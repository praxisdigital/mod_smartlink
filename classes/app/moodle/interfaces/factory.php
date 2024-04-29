<?php

namespace mod_smartlink\app\moodle\interfaces;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

use mod_smartlink\app\moodle\context\interfaces\factory as context_factory_interface;
use curl;
use moodle_database;

interface factory
{
    public function db(): moodle_database;
    public function config(string $component, ?string $name = null): mixed;
    public function context(): context_factory_interface;
    public function curl(): curl;
}
