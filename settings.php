<?php

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

if ($ADMIN->fulltree) {

    // ------------- //
    //    Prompts    //
    // ------------- //

    $settings->add(
        new admin_setting_heading(
            'mod_smartlink/prompts',
            get_string('settings_prompts_title', 'mod_smartlink'),
            ''
        )
    );

    $promptsettingspageurl = new moodle_url('/mod/smartlink/index.php');
    $settings->add(
        new admin_setting_description(
            'mod_smartlink/manage_prompts', 
            get_string('settings_prompts', 'mod_smartlink'), 
            get_string('settings_prompts_desc', 'mod_smartlink', $promptsettingspageurl->out(false))
        )
    );

    // ------------------------ //
    //    OpenAI credentials    //
    // ------------------------ //

    $settings->add(
        new admin_setting_heading(
            'mod_smartlink/openai',
            get_string('settings_openai_title', 'mod_smartlink'),
            ''
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'mod_smartlink/openai_endpoint',
            get_string('settings_openai_endpoint', 'mod_smartlink'),
            get_string('settings_openai_endpoint_desc', 'mod_smartlink'),
            '',
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'mod_smartlink/openai_token',
            get_string('settings_openai_token', 'mod_smartlink'),
            get_string('settings_openai_token_desc', 'mod_smartlink'),
            '',
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'mod_smartlink/openai_model',
            get_string('settings_openai_model', 'mod_smartlink'),
            get_string('settings_openai_model_desc', 'mod_smartlink'),
            'gpt-3.5-turbo',
            '/^[a-z0-9.-]+$/'
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'mod_smartlink/openai_temperature',
            get_string('settings_openai_temperature', 'mod_smartlink'),
            get_string('settings_openai_temperature_desc', 'mod_smartlink'),
            '0.7',
            '/^(([0-1][.][0-9])|(2.0))$/'
        )
    );

}

?>