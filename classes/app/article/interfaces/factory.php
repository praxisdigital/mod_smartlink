<?php

namespace mod_smartlink\app\article\interfaces;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

interface factory 
{
    public function extract(string $html): string;
}
