# Name: Praxis Smart Link

## Repository
mod_smartlink

## Type
mod

## Dependencies
- Composer package: andreskrey/readability.php (^2.1)
## Description
AI text submission plugin that allows admins to define submission scope of steps, and students to generate AI assisted content based on assignment title.

## Global settings
Smartlink requires some setup to be completed before used, forgetting this step will cause the plugin not to work as supposed to be. Here, its mandatory that you complete all the sections Open API request settings and prompt settings. These Open API request settings are used as it is in PXAI writer pluign (Changes will effect to both plugins).
You can access these set of settings here : https://<site>/admin/settings.php?section=smartlink.

- URL **(url)**
- Authorization **(authorization)**
- Model **(model)**
- Temperature **(temperature)**
- Max tokens **(max_tokens)**
- Top p **(top_p)**
- Frequency Penalty **(frequency_penalty)**
- Presence penalty **(presence_penalty)**
- API key **(api_key)**
- Prompt Settings
    - See here : https://<site>/mod/smartlink/index.php.

## Setup
- Install plugin
- Go to https://<site>/admin/settings.php?section=smartlink and complete the settings and save.
- Use the link in https://<site>/admin/settings.php?section=smartlink to add/modify prompts (https://<site>/mod/smartlink/index.php)
- Go to the Course view and add smart link activity module and add a URL.
- Click on Get AI Version button to select a prompt to get AI generated results from the added URL in the smart link course module.

**Database tables:**
- smartlink - This records the smart link data such as the name of the module, URL etc.
- smartlink_prompts - This records the prompt details which are added by the admin.

## Release notes
- **1.0.0** (2022121400)
  - First version of the plugin

## GDPR
- All AJAX files protected by login.
- Privacy API implemented
