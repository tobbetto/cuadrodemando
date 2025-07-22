<?php

/**
 * Un pequeño php para gestionar lo del cuadro de mando
 * Basado en el adminlte (https://adminlte.io/)
 * php version: 7.4
 * author: Thorvaldur Konradsson
 */
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/var/www/html/moodle/adminlte/php_error_log');
require_once "../config.php";

// TK: Hay que encontrar un nombre más personalizado que adminlte
// Es dentro de un Moodle así que nos aseguramos que la persona tiene 
// sesión abierta en Moodle. Si no, al login del Moodle.
$PAGE->set_url('/adminlte/index.php');
defined('MOODLE_INTERNAL') || die();
require_once "controllers/template.controller.php";

require_login();
$context = context_user::instance($USER->id);

// No queremos que entren estudiantes o visitantes aquí
if (has_capability('moodle/course:create', $context)) {

    $index = new TemplateController;
    $index->index();
} else {

    redirect($CFG->wwwroot);

}
