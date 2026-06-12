<?php

defined('MOODLE_INTERNAL') || die();

class behat_mod_smartlink_generator extends behat_generator_base {

    protected function get_creatable_entities(): array {
        return [
            'smartlink' => [
                'singular' => 'smartlink',
                'datagenerator' => 'smartlinks',
                'required' => ['course'],
                'switchids' => [
                    'course' => 'course_id',
                ],
            ]
        ];
    }
}