<?php

namespace mod_smartlink\app\webservices\openai\interfaces;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

interface repository 
{
    public function prompt(int $courseid, int $instanceid, string $prompt = '', int $promptid = 0): array;
    public function prepare_request(): object;
}
