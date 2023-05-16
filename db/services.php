<?php 

    $functions = [
        'mod_smartlink_prompt_openai' => [
            'classname'   => \mod_smartlink\external\prompt_openai::class,
            'methodname'  => 'execute', 
            'description' => 'App prompts for smartlink',
            'type'        => 'write', 
            'ajax'        => true ,
        ],
    ];

    $services = [
        'Smart Link API' => [
            'shortname' => 'smartlinkapi',
            'functions' => ['mod_smartlink_prompt_openai'],
            'restrictedusers' => 0,
            'enabled' => 1,
        ],
    ];

?>