<?php
    $functions = array(
        'mod_smartlink_get_ai_version' => array(
            'classname'   => 'mod_smartlink_external',
            'methodname'  => 'get_ai_version', 
            'classpath'   => 'mod/smartlink/externallib.php',
            'description' => 'App prompts for smartlink',
            'type'        => 'write', 
            'ajax'        => true 
        )
    );

    $services = array(
        'Smart Link API' => array(
            'functions' => array(
                'mod_smartlink_get_ai_version'
            ),
            'restrictedusers' => 0, // if 1, the administrator must manually select which user can use this service. 
            // (Administration > Plugins > Web services > Manage services > Authorised users)
            'enabled' => 1, // if 0, then token linked to this service won't work
            'shortname' => 'smartlinkapi' //the short name used to refer to this service from elsewhere including when fetching a token
        )
    );
?>