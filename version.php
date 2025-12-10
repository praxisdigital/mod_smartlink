<?php

defined('MOODLE_INTERNAL') || die();

/** @var object $plugin */
$plugin->component = 'mod_smartlink';
$plugin->version  = 20251210000; // Someone added an extra zero...
$plugin->release = '2.1.5';
$plugin->requires = 2025041400; // Moodle 5.0
$plugin->maturity = MATURITY_STABLE;
$plugin->dependencies = [
    'local_mxaimanager' => 2025111900,
];
