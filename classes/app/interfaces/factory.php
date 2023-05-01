<?php

namespace mod_smartlink\app\interfaces;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

use mod_smartlink\app\moodle\interfaces\factory as moodle_factory_interface;
use mod_smartlink\app\smartlink\interfaces\factory as smartlink_factory_interface;
use mod_smartlink\app\webservices\interfaces\factory as webservices_factory_interface;
use mod_smartlink\app\article\interfaces\factory as article_factory_interface;

interface factory
{
    public static function make(): self;
    public function moodle(): moodle_factory_interface;
    public function smartlink(): smartlink_factory_interface;
    public function webservices(): webservices_factory_interface;
    public function article(): article_factory_interface;
}
