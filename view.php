<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * smartlink module version information
 *
 * @package mod_smartlink
 * @copyright  2022 Autotech
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_smartlink\app\factory as base_factory;

require_once('../../config.php');
require_once($CFG->dirroot . '/mod/smartlink/lib.php');

global $PAGE, $USER, $CFG, $COURSE;

$base_factory = base_factory::make();

$id = required_param('id', PARAM_INT); // Course Module ID
$forceview = optional_param('forceview', 0, PARAM_BOOL);

$cm = get_coursemodule_from_id('smartlink', $id, 0, false, MUST_EXIST);
$instanceid = $cm->instance;
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
$smartlink = $DB->get_record('smartlink', array('id' => $cm->instance), '*', MUST_EXIST);
$courseid = $course->id;
$context = context_module::instance($cm->id);

require_login($course, false, $cm);

$pageurl = new moodle_url('/mod/smartlink/view.php', ['id' => $cm->id]);
$PAGE->set_url($pageurl, ['id' => $cm->id]);

$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'smartlink'));
$PAGE->set_heading(get_string('pluginname', 'smartlink'));

$smartlink = $base_factory->smartlink()->get_settings($courseid, $instanceid);
$url = $smartlink->url ?? '';
if ($url && !$forceview) { redirect($url); }

$prompts = get_available_prompts();

echo $OUTPUT->header();

$view = $OUTPUT->render_from_template('mod_smartlink/get_ai_button', ['hasprompts' => count($prompts) > 0, 'prompts' => array_values($prompts)]);
$view .= $OUTPUT->render_from_template('mod_smartlink/add_custom_prompt_modal', ['url' => $smartlink->url]);
$view .= $OUTPUT->render_from_template('mod_smartlink/ai_response_modal', []);

$PAGE->requires->js_call_amd('mod_smartlink/smartlink_actions', 'init', ['prompts' => $prompts, 'courseid' => $courseid, 'instanceid' => $instanceid]);

echo $view;

echo $OUTPUT->footer();
