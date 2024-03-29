<?php

require_once($CFG->dirroot.'/lib/environmentlib.php');

/**
 * Add page instance
 * @param stdClass $data
 * @param mod_smartlink_mod_form $mform
 * @return int new page instance id
 */
function smartlink_add_instance($data, $mform = null)
{
    global $DB, $COURSE, $USER;

    // Add mod instance data to DB
    $data->course = $COURSE->id;
    $data->userid = $USER->id;
    $data->timemodified = time();
    $data->timecreated = time();

    $data->id = $DB->insert_record('smartlink', $data);

    return $data->id;
}

/**
 * Update page instance
 * @param object $data
 * @param object $mform
 * @return bool true
 */
function smartlink_update_instance($data, $mform)
{
    global $CFG, $DB;
    require_once($CFG->libdir.'/resourcelib.php');

    $data->id = $data->instance;
    $data->timemodified = time();

    $DB->update_record('smartlink', $data);

    return true;
}

/**
 * Delete page instance
 * @param int $id
 * @return bool true
 */
function smartlink_delete_instance($id)
{
    global $DB;

    if (!$page = $DB->get_record('smartlink', array('id' => $id))) {
        return false;
    }

    $cm = get_coursemodule_from_instance('smartlink', $id);
    \core_completion\api::update_completion_date_event($cm->id, 'smartlink', $id, null);

    // note: all context files are deleted automatically

    $DB->delete_records('smartlink', array('id' => $page->id));

    return true;
}

/**
 * Update prompts
 *
 * @package  mod_smartlink
 * @param stdClass $data data object of prompt
 * @return bool success status
 */
function update_prompts($data)
{
    global $DB;

    $record = null;

    if (isset($data->id) && $data->id > 0) {
        $record =  $DB->get_record('smartlink_prompts', array('id' => $data->id));
    }

    if (!$record) {
        $prompt_data = ['description' => $data->description, 'prompt' => $data->prompt, 'language' => $data->language, 'active' => (int)$data->active, 'timecreated' => time(), 'timemodified' => time()];
        return $DB->insert_record('smartlink_prompts', $prompt_data);
    } else {
        $record->description = $data->description;
        $record->prompt = $data->prompt;
        $record->language = $data->language;
        $record->active = (int)$data->active;
        $record->timemodified = time();
        return $DB->update_record('smartlink_prompts', $record);
    }
}

/**
 * Get all available prompts
 *
 * @package  mod_smartlink
 * @return array available all prompts
 */
function get_prompts()
{
    global $DB;
    $sql = "SELECT * FROM {smartlink_prompts} ORDER BY id";
    $prompts = $DB->get_records_sql($sql);
    return $prompts;
}

/**
 * Get available active and matching prompts
 *
 * @package  mod_smartlink
 * @return array available active and language-matching prompts
 */
function get_available_prompts()
{
    global $DB, $CFG;
    require_once($CFG->libdir.'/moodlelib.php');

    $language = current_language() ?: 'en';

    $prompts = $DB->get_records_sql('SELECT * FROM {smartlink_prompts} WHERE '.$DB->sql_compare_text('language').' = :language AND active = :active ORDER BY id', [
        'language' => $language,
        'active' => 1,
    ]);

    return $prompts;
}

/**
 * Serves the page files
 *
 * @package  mod_smartlink
 * @category files
 * @param stdClass $course course object
 * @param stdClass $cm course module object
 * @param stdClass $context context object
 * @param string $filearea file area
 * @param array $args extra arguments
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if file not found, does not return if found - just send the file
 */
function smartlink_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array())
{
    global $CFG;
    require_once($CFG->libdir.'/resourcelib.php');

    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    require_course_login($course, true, $cm);
    if (!has_capability('mod/smartlink:view', $context)) {
        return false;
    }

    if ($filearea !== 'content') {
        return false;
    }

    return false;
}

/**
 * Indicates API features that the forum supports
 *
 * @param string $feature
 * @return mixed True if yes (some features may use other values)
 */
function mod_smartlink_supports($feature)
{
    $supports = [
        FEATURE_MOD_ARCHETYPE => MOD_ARCHETYPE_RESOURCE,
        FEATURE_MOD_PURPOSE => MOD_PURPOSE_CONTENT,
    ];

    return $supports[$feature] ?? null;
}

function mod_smartlink_cm_info_view(cm_info $cm): void
{
    global $OUTPUT, $COURSE, $DB, $PAGE;

    $instanceid = $cm->instance;
    $smartlink = $DB->get_record('smartlink', ['id' => $instanceid], '*', MUST_EXIST);
    $prompts = get_available_prompts();

    $view = $OUTPUT->render_from_template('mod_smartlink/get_ai_button', ['hasprompts' => count($prompts) > 0, 'prompts' => array_values($prompts), 'moduleid' => $cm->id]);
    $view .= $OUTPUT->render_from_template('mod_smartlink/add_custom_prompt_modal', ['url' => $smartlink->url, 'moduleid' => $cm->id]);
    $view .= $OUTPUT->render_from_template('mod_smartlink/ai_response_modal', []);

    $PAGE->requires->js_call_amd('mod_smartlink/smartlink_actions', 'init', ['courseid' => $COURSE->id, 'instanceid' => $instanceid, 'moduleid' => $cm->id]);
    $PAGE->requires->js_call_amd('mod_smartlink/module_attributes', 'init', ['moduleid' => $cm->id]);

    $cm->set_content($view);
}
