<?php

namespace mod_smartlink\app\webservices\openai;

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

use \Exception;
use mod_smartlink\app\factory as base_factory;

class repository implements interfaces\repository 
{
    private base_factory $base_factory;

    public function __construct(base_factory $base_factory)
    {
        $this->base_factory = $base_factory;
    }

    public function prompt(int $courseid, int $instanceid, string $prompt = '', int $promptid = 0): array
    {
        $smartlink = $this->base_factory->smartlink()->get_settings($courseid, $instanceid);
        $description = '';

        if ($promptid) {
            $record = $this->base_factory->smartlink()->get_prompt($promptid);
            $prompt = $record->prompt;
            $description = $record->description;
        }

        $prompt_text = $prompt.' '.$smartlink->url;

        $curl = $this->base_factory->moodle()->curl();
        $html = $curl->get($smartlink->url);
        
        if ($curl->error) {
            throw new Exception($curl->error);
        }

        $article = $this->base_factory->article()->extract($html);
        $article = ' ```'.mb_substr($article, 0, 4000).'```';
        $prompt .= $article;

        $messages = [
            new \local_mxaimanager\app\ai\provider\message(
                'user',
                $prompt
            )
        ];

        $feature = $this->base_factory->local_mxaimanager()->ai()->feature()->repository()->get_by_component_and_name_identifier('mod_smartlink', 'ai:feature:prompt_link');

        return [
            'description' => $description,
            'prompt_text' => $prompt_text,
            'prompt_real' => $prompt,
            'result' => $this->base_factory->local_mxaimanager()->ai()->feature()->handler($feature)->chat_completion($messages)
        ];
    }
}
