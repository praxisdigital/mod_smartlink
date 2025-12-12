<?php

// @codeCoverageIgnoreStart
defined('MOODLE_INTERNAL') || die();
// @codeCoverageIgnoreEnd

/* @global admin_root $ADMIN  */

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
}
