<?php

// General
$string['modulename'] = 'Smart Link';
$string['pluginadministration'] = 'Smart Link modul administration';
$string['pluginname'] = 'Smart Link';
$string['modulenameplural'] = 'Smart Link';

// Capabilities
$string['smartlink:modifyprompts'] = 'Tillad brugere at oprette/ændre/slette prompts';
$string['smartlink:addinstance'] = 'Tillad brugere at tilføje Smartlink instanser';

// Settings
$string['settings_prompts_title'] = 'Administrer prompts';
$string['settings_prompts'] = 'Rediger dine prompts';
$string['settings_prompts_desc'] = '<a href="{$a}">Klik her for at administrere prompterne</a>';

$string['settings_openai_title'] = 'OpenAI oplysninger';
$string['settings_openai_endpoint'] = 'Endpoint URL';
$string['settings_openai_endpoint_desc'] = 'Eksempel: https://api.openai.com/v1/chat/completions';
$string['settings_openai_token'] = 'API Token';
$string['settings_openai_token_desc'] = 'Eksempel: sk-kU3R3SdqL4R8qM9F...';
$string['settings_openai_model'] = 'Model';
$string['settings_openai_model_desc'] = 'Indtast den AI model du ønsker at benytte';
$string['settings_openai_temperature'] = 'Temperatur';
$string['settings_openai_temperature_desc'] = 'Indtast temperatur fra 0.0 til 2.0. En lavere værdi vil give et mere fokuseret og deterministisk resultat';

// Other
$string['prompt_settings'] = 'Smart Link Prompts';
$string['prompt'] = 'Prompt';
$string['add_prompt_title'] = 'Tilføj en prompt';
$string['url'] = 'URL';
$string['custom_prompt_modal_title'] = 'Lav din egen AI-prompt';
$string['get_ai_button'] = 'Get AI Muligheder';
$string['no_prompt_msg'] = 'Ingen tilgængelige prompter på dit sprog';
$string['own_prompt_description'] = 'Din prompt sættes automisk foran følgende URL: {$a}. <br><br> Du kan f.eks. skrive "Lav et resumé af: " eller "Beskriv de fem vigtigste personer i: " ';
$string['ai_result_title'] = 'AI resultater';
$string['url_param_validation_msg'] = 'URL-parameteren skal inkluderes.';
$string['prompt_edit_warning'] = 'Bemærk! Hvis du redigerer denne, vil den blive ændret i alle aktiviteter.';
$string['prompt_modal_close_warning'] = "Du er ved at lukke denne formular. De ændringer du har foretaget bliver ikke gemt. Ønsker du at fortsætte?";
$string['response_modal_close_warning'] = "Du er ved at lukke oversigten. Ønsker du at fortsætte?";
$string['prompt_help_description'] = 'Denne indstilling indsætter den prompt, der bruges i AI-opslaget. Din prompt vil automatisk blive efterfulgt af det konfigurerede link';
$string['prompt_help_title'] = 'Hjælp til Smart Link-prompter';

?>