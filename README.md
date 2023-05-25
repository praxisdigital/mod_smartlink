# Name: Praxis Smart Link

## Repository
mod_smartlink

## Type
mod

## Dependencies
- Composer package: andreskrey/readability.php (^2.1)
## Description
AI text submission plugin that allows admins to define submission scope of steps, and students to generate AI assisted content based on assignment title.

## Settings
Smartlink requires some setup to be completed before used, forgetting this step will cause the plugin not to work.
You can access these set of settings here: https://<site>/admin/settings.php?section=modsettingsmartlink

- Endpoint URL **(openai_endpoint)**
- API Token **(openai_token)**
- Model **(openai_model)**
- Temperature **(openai_temperature)**
- Max tokens **(max_tokens)**
- Top p **(top_p)**
- Frequency Penalty **(frequency_penalty)**
- Presence penalty **(presence_penalty)**
- API key **(api_key)**

## Setup
- Install the plugin
- Ensure that all dependencies are also installed
- Configure the plugin at https://<site>/admin/settings.php?section=modsettingsmartlink
- Create some prompts at https://<site>/mod/smartlink/index.php
- Go to the Course view and add the Smartlink module
- Click on the "Get AI Version" button and select the prompt you wish to execute. After loading for a few seconds, a modal should appear with your result

**Database tables:**
- smartlink - This records the smartlink data such as the name of the module, URL etc.
- smartlink_prompts - This records the prompt details which are added by the admin.

## Release notes
- **1.1.1** (2023052502)
  - Fetch available prompts with AJAX to avoid js_call_amd error
  - Fix styling targets
  - Prevent JS errors when OpenAI response is null
  - Limit article length to 4000 characters to avoid empty responses
  - Automatically append URL to prompt, instead of relying on {url} variable
  - Remove unnecessary tests
- **1.1.0** (2023051500)
  - Fix OpenAI not being able to access URL content and hallucinating the content instead (We are now scraping content from the provided URL and including the text in the prompt)
  - Improve modal styling
  - Ensure correct module group
  - Redirect module view.php page to the configured URL
  - Remove pxaiwriter dependency by creating separate OpenAI settings for Smartlink
  - Write tests
  - Add missing capability strings
- **1.0.0** (2022121400)
  - First version of the plugin

## GDPR
- All AJAX files protected by login.
- Privacy API implemented
