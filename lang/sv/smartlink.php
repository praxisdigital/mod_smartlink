<?php

// General
$string['modulename'] = 'Smart Link';
$string['pluginadministration'] = 'Smart Link modul administration';
$string['pluginname'] = 'Smart Link';
$string['modulenameplural'] = 'Smart Link';

// Capabilities
$string['smartlink:modifyprompts'] = 'Tillåt användare att lägga till/redigera/ta bort instruktioner';
$string['smartlink:addinstance'] = 'Tillåt användare att lägga till Smartlink instanser';

// Settings
$string['settings_prompts_title'] = 'Administrer instruktioner';
$string['settings_prompts'] = 'Redigera dina instruktioner';
$string['settings_prompts_desc'] = '<a href="{$a}">Klicka här för att hantera instruktionerna</a>';

$string['settings_openai_title'] = 'OpenAI inställningar';
$string['settings_openai_endpoint'] = 'Endpoint URL';
$string['settings_openai_endpoint_desc'] = 'Exempel: https://api.openai.com/v1/chat/completions';
$string['settings_openai_token'] = 'API Token';
$string['settings_openai_token_desc'] = 'Exempel: sk-kU3R3SdqL4R8qM9F...';
$string['settings_openai_model'] = 'Modell';
$string['settings_openai_model_desc'] = 'Välj vilken AI modell du vill använda';
$string['settings_openai_temperature'] = 'Temperatur';
$string['settings_openai_temperature_desc'] = 'Välj önskad temperatur från 0.1 till 2.0. Lägre värden ger mer fokuserade och deterministiska resultat';

// Other
$string['prompt_settings'] = 'Smart Link Instruktioner';
$string['prompt'] = 'Instruktion';
$string['add_prompt_title'] = 'Lägg till instruktion ';
$string['url'] = 'URL';
$string['custom_prompt_modal_title'] = 'Gör din egen AI-instruktion';
$string['get_ai_button'] = 'Visa AI-version';
$string['no_prompt_msg'] = 'Inga tillgängliga instruktioner för ditt språk';
$string['own_prompt_description'] = 'Din instruktion sätts automatiskt före följande URL: {$a}. <br><br> Du kan exempelvis skriva “Summera:” eller “Beskriv de fem viktigaste personerna i: " ';
$string['ai_result_title'] = 'AI-resultat';
$string['url_param_validation_msg'] = 'URL-parametern måste inkluderas.';
$string['prompt_edit_warning'] = 'Notera! Om du redigerar denna kommer den att ändras i alla aktiviteter.';
$string['prompt_modal_close_warning'] = "Du håller på att stänga det här formuläret. Ändringarna du har gjort kommer inte att sparas. Vill du fortsätta?";
$string['response_modal_close_warning'] = "Du är på väg att stänga sammanfattningen. Vill du fortsätta?";
$string['prompt_help_description'] = 'Den här inställningen infogar instruktionen som används i AI-sökningen. Din instruktion kommer automatiskt att följas av den konfigurerade länken';
$string['prompt_help_title'] = 'Hjälp med Smart Link-instruktioner';

?>