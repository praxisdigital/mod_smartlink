<?php 

namespace mod_smartlink\unit\webservices\openai;

use basic_testcase;
use curl;
use mod_smartlink\app\factory as base_factory;
use mod_smartlink\app\moodle\factory as moodle_factory;
use mod_smartlink\app\smartlink\factory as smartlink_factory;
use mod_smartlink\app\article\factory as article_factory;
use mod_smartlink\app\webservices\openai\repository;

class repository_test extends basic_testcase
{
    private base_factory $base_factory;
    private repository $repository;

    protected function setUp(): void
    {
        // Mock curl class
        $curl = $this->getMockBuilder(curl::class)->onlyMethods(['get', 'post'])->getMock();
        $curl->method('get')->willReturn('Lorem ipsum dolor sit amet');
        $curl->method('post')->willReturn(json_encode([
            'choices' => [
                0 => [
                    'message' => [
                        'content' => 'This is the OpenAI result string',
                    ]
                ]
            ]
        ]));

        // Mock moodle factory
        $moodle = $this->getMockBuilder(moodle_factory::class)->disableOriginalConstructor()->onlyMethods(['config', 'curl'])->getMock();
        $moodle->method('curl')->willReturn($curl);
        $moodle->method('config')->willReturn((object)[
            'openai_endpoint' => 'https://api.openai.com/v1/chat/completions',
            'openai_token' => 'sk-AaBbCcDdEeFfGgHhIiJjKkLlMmNn1234567890',
            'openai_model' => 'gpt-3.5-turbo',
            'openai_temperature' => 0.7,
        ]);

        // Mock smartlink factory
        $smartlink = $this->getMockBuilder(smartlink_factory::class)->disableOriginalConstructor()->onlyMethods(['get_settings', 'get_prompt'])->getMock();
        $smartlink->method('get_prompt')->willReturn((object)[
            'prompt' => 'Create 5 question from this article:',
            'description' => 'Create questions',
        ]);
        $smartlink->method('get_settings')->willReturn((object)[
            'url' => 'https://google.com/',
        ]);

        // Mock article factory
        $article = $this->getMockBuilder(article_factory::class)->disableOriginalConstructor()->onlyMethods(['extract'])->getMock();
        $article->method('extract')->willReturn('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis ac ultrices magna.');

        // Mock base factory
        $this->base_factory = $this->getMockBuilder(base_factory::class)->onlyMethods(['moodle', 'smartlink', 'article'])->getMock();
        $this->base_factory->method('moodle')->willReturn($moodle);
        $this->base_factory->method('smartlink')->willReturn($smartlink);
        $this->base_factory->method('article')->willReturn($article);

        // Apply mock tree to repository factory
        $this->repository = new repository($this->base_factory);
    }

    public function test_prepare_request(): void
    {
        $expected = (object)[
            'url' => 'https://api.openai.com/v1/chat/completions',
            'headers' => [
                'Content-Type: application/json',
                'Authorization: Bearer sk-AaBbCcDdEeFfGgHhIiJjKkLlMmNn1234567890',
                'Accept: application/json',
            ],
            'payload' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [],
                'temperature' => 0.7,
            ],
        ];

        $request = $this->repository->prepare_request();

        self::assertEqualsCanonicalizing($expected, $request);
    }

    public function test_prompt(): void
    {
        $expected = [
            'prompt_text' => 'Create 5 question from this article: https://google.com/',
            'prompt_real' => 'Create 5 question from this article: ```Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis ac ultrices magna.```',
            'description' => 'Create questions',
            'result' => 'This is the OpenAI result string',
        ];

        $prompt = $this->repository->prompt(1, 1, '', 1);

        self::assertEqualsCanonicalizing($expected, $prompt);
    }
}
