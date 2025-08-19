<?php
// filepath: /workspaces/cuadrodemando/db/access.php
defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'local/cuadrodemando:view' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
        ),
    ),
    'local/cuadrodemando:manage' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
        ),
        'clonepermissionsfrom' => 'moodle/site:config',
    ),
);