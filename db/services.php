<?php 

    $functions = [
        'mod_smartlink_prompt_openai' => [
            'classname'   => \mod_smartlink\external\prompt_openai::class,
            'methodname'  => 'execute', 
            'description' => 'App prompts for smartlink',
            'type'        => 'write', 
            'ajax'        => true ,
        ],
        'mod_smartlink_get_available_prompts' => [
            'classname'   => \mod_smartlink\external\get_available_prompts::class,
            'methodname'  => 'execute', 
            'description' => 'Get configured Smartlink prompts',
            'type'        => 'write', 
            'ajax'        => true ,
        ],
    ];

    $services = [
        'Smart Link API' => [
            'shortname' => 'smartlinkapi',
            'functions' => [
                'mod_smartlink_prompt_openai',
                'mod_smartlink_get_available_prompts',
            ],
            'restrictedusers' => 0,
            'enabled' => 1,
        ],
    ];

?>