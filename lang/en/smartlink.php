<?php

// General
$string['modulename'] = 'Smart Link';
$string['pluginadministration'] = 'Smart Link module administration';
$string['pluginname'] = 'Smart Link';
$string['modulenameplural'] = 'Smart Links';

// Capabilities
$string['smartlink:modifyprompts'] = 'Allow users to add/edit/delete prompts';
$string['smartlink:addinstance'] = 'Allow users to add Smartlink instances';

// Settings
$string['settings_prompts_title'] = 'Manage prompts';
$string['settings_prompts'] = 'Manage your prompts';
$string['settings_prompts_desc'] = '<a href="{$a}">Click here to manage prompts</a>';

$string['settings_openai_title'] = 'OpenAI credentials';
$string['settings_openai_endpoint'] = 'Endpoint URL';
$string['settings_openai_endpoint_desc'] = 'Example: https://api.openai.com/v1/chat/completions';
$string['settings_openai_token'] = 'API Token';
$string['settings_openai_token_desc'] = 'Example: sk-kU3R3SdqL4R8qM9F...';
$string['settings_openai_model'] = 'Model';
$string['settings_openai_model_desc'] = 'Choose which AI model you want to use';
$string['settings_openai_temperature'] = 'Temperature';
$string['settings_openai_temperature_desc'] = 'Select request temperature from 0.0 to 2.0. Lower values will produce more focused and deterministic results';

// Other
$string['prompt_settings'] = 'Smart Link Prompts';
$string['prompt'] = 'Prompt';
$string['add_prompt_title'] = 'Add a prompt';
$string['url'] = 'URL';
$string['custom_prompt_modal_title'] = 'Make your own AI prompt';
$string['get_ai_button'] = 'Get AI Version';
$string['no_prompt_msg'] = 'No prompts available in your language';
$string['own_prompt_description'] = 'Your prompt will be added before the following URL: {$a}. <br><br> You can e.g. write "Write a short summary of the following: " or "Describe the five most important people in: " ';
$string['ai_result_title'] = 'AI Results';
$string['url_param_validation_msg'] = 'The URL-parameter is needed.';
$string['prompt_edit_warning'] = 'Note! If you edit this, it will be changed in all activities.';
$string['prompt_modal_close_warning'] = "You are about to close this form. The changes you've made will be lost. Do you wish to continue?";
$string['response_modal_close_warning'] = "You are about to close the summary. Do you wish to continue?";
$string['prompt_help_description'] = 'This setting inserts the prompt used in the AI lookup. Add your prompt followed by the {url} parameter.';
$string['prompt_help_title'] = 'Help with Smart Link Prompts';

?>