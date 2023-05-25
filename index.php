<?php

require_once('../../config.php');
require_once $CFG->libdir.'/adminlib.php';
require_once(__DIR__.'/lib.php');

global $PAGE, $USER;

$context = context_system::instance();

if (!has_capability('mod/smartlink:modifyprompts', $context)) {
    throw new \moodle_exception('nopermissions', 'error', '', 'modify the smartlink prompts');
}

$pageurl = new moodle_url('/mod/smartlink/index.php');
$PAGE->set_url($pageurl);
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'smartlink'));
$PAGE->set_heading(get_string('prompt_settings', 'smartlink'));

$langs = [];
$installedlangs = get_string_manager()->get_list_of_translations(true);

foreach(array_keys($installedlangs) as $langCode) {
    $lang = new stdClass();
    $lang->code = $langCode;
    $lang->name = $installedlangs[$langCode];
    array_push($langs, $lang);
}

$page_content = "";
if (isset($_POST['action'])) {
    $data = (object)$_POST;
    update_prompts($data);
}

$prompt_settings = get_prompts();

$table = new html_table();
$table->size[0] = '5%';
$table->size[1] = '15%';
$table->size[2] = '25%';
$table->size[3] = '35%';
$table->size[4] = '10%';
$table->id = 'smart-link-prompts';

$table->head = array(
    '',
    get_string('language', 'core'),
    get_string('description', 'core'),
    get_string('prompt', 'smartlink'),
    get_string('status', 'core'),
);

$table->data = array();

foreach($prompt_settings as $prmpt) {
    $editIcon = html_writer::span("<i class='fa fa-pen'></i>");
    $editIcon = html_writer::end_span();

    $cell = new html_table_cell(html_writer::span("<i class='fa fa-pencil ml-5'></i>", 'prompt-edit-icon', ['data-id' => $prmpt->id, 'data-target' => '#newPromptModal']).html_writer::end_span());

    $prmpt->status_string = $prmpt->active == 1 ? get_string('active', 'core') : get_string('inactive', 'core'); 

    $table->data[] = array($cell, $prmpt->language, $prmpt->description, $prmpt->prompt, $prmpt->status_string);
}

$page_content .= html_writer::table($table);

$page_content .= html_writer::start_tag("div", ['class' => "d-flex flex-row-reverse mt-5"]);
$page_content .= html_writer::empty_tag('button', ['type' => 'button', 'class' => 'btn btn-secondary', 'id' => 'add-prompt-btn', 'data-target' => '#newPromptModal']);
$page_content .= get_string('add', 'core');
$page_content .= html_writer::end_tag('button');
$page_content .= html_writer::end_tag('div');

$page_content .= $OUTPUT->render_from_template('mod_smartlink/new_prompt_modal', ['lang_lsit' => $langs]);

$PAGE->requires->js_call_amd('mod_smartlink/admin_settings', 'init', ['prompts' => $prompt_settings, 'langs' => $langs]);

echo $OUTPUT->header();

echo($page_content);

echo $OUTPUT->footer();

?>


