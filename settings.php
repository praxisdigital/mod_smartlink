<?php

defined('MOODLE_INTERNAL') || die;

$pluginname = get_string('pluginname', 'smartlink');

$settings->add(new admin_setting_heading(
    'smartlinkprompts',
    new lang_string('prompt_settings', 'smartlink'),
    new lang_string('prompt_settings_description', 'smartlink'),
));

$label = new lang_string('manage_prompts', 'smartlink');
$promptsettingspageurl = new moodle_url('/mod/smartlink/index.php');
$desc = new lang_string('prompt_settings_url', 'smartlink', $promptsettingspageurl->out(false));
$settings->add(new admin_setting_description('smart_link_prompts', $label, $desc));

$settings->add(new admin_setting_heading(
    'assignsubmissionheading',
    new lang_string('open_ai_request_settings', 'assignsubmission_pxaiwriter'),
    new lang_string('open_ai_request_settings_description', 'assignsubmission_pxaiwriter'),
));

$label = new lang_string('open_ai_request_settings', 'assignsubmission_pxaiwriter');
$openaisettingspageurl = new moodle_url('/admin/settings.php', ['section' => 'assignsubmission_pxaiwriter']);
$desc = new lang_string('open_ai_settings_details', 'smartlink', $openaisettingspageurl->out(false));
$settings->add(new admin_setting_description('open_ai_settings', $label, $desc));

?>