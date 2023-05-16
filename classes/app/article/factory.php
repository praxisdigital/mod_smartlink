<?php

namespace mod_smartlink\app\article;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

use mod_smartlink\app\factory as base_factory;
use andreskrey\Readability\Readability;
use andreskrey\Readability\Configuration;

class factory implements interfaces\factory 
{
    private base_factory $base_factory;

    function __construct(base_factory $base_factory)
    {
        $this->base_factory = $base_factory;
    }

    public function extract(string $html): string
    {
        $configuration = new Configuration();
        $readability = new Readability($configuration);
        $readability->parse($html);

        return $readability->getTitle().'. '.strip_tags($readability->getContent());
    }
}
