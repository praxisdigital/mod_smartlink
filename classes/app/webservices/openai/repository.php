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

    function __construct(base_factory $base_factory)
    {
        $this->base_factory = $base_factory;
    }

    public function prompt(int $courseid, int $instanceid, string $prompt = '', int $promptid = 0): array
    {
        $request = $this->prepare_request();
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
        $article = ' ```'.mb_substr($article, 0, 5000).'```';
        $prompt .= $article;

        $request->payload['messages'][] = [
            'role' => 'user',
            'content' => $prompt,
        ];

        $openai = $this->base_factory->moodle()->curl()->post($request->url, json_encode($request->payload), ['HTTPHEADER' => $request->headers]);
        $response = json_decode($openai, true);
        $content = $response['choices'][0]['message']['content'];

        return [
            'description' => $description,
            'prompt_text' => $prompt_text,
            'prompt_real' => $prompt,
            'result' => $content,
        ];
    }

    public function prepare_request(): object
    {
        $config = $this->base_factory->moodle()->config('mod_smartlink');

        return (object)[
            'url' => $config->openai_endpoint,
            'headers' => [
                'Content-Type: application/json',
                'Authorization: Bearer '.$config->openai_token,
                'Accept: application/json',
            ],
            'payload' => [
                'model' => $config->openai_model,
                'messages' => [],
                'temperature' => (float)$config->openai_temperature,
            ],
        ];
    }
}
