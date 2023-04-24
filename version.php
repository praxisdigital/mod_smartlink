<?php

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2023041902;
$plugin->requires   = 2018120302; // Moodle v3.6.2             
$plugin->release    = 'v1.0.0.6';
$plugin->component = 'mod_smartlink';
$plugin->maturity   = MATURITY_STABLE;
$plugin->dependencies   = array(
    'assignsubmission_pxaiwriter'    => 2023013100,
);

?>
