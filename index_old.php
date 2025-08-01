<?php

/**
 * Dashboard/Cuadro de mando para Moodle
 * Basado en AdminLTE (https://adminlte.io/)
 * Let's see if this works
 * 
 * @package    local_cuadrodemando
 * @author     Thorvaldur Konradsson
 * @version    1.0
 */

// Configuración de errores - mostrar solo en desarrollo
if (defined('DEBUG') && DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/php_error_log');
}

require_once "../config.php";

// Verificar que estamos en el contexto de Moodle
defined('MOODLE_INTERNAL') || die();

// Configurar la URL de la página
$PAGE->set_url('/local/cuadrodemando/index.php');

// Requerir autenticación
require_login();

// Verificar que las variables globales necesarias existen
if (!isset($USER) || !isset($CFG)) {
    throw new moodle_exception('missingglobalvars', 'local_cuadrodemando');
}

$context = context_user::instance($USER->id);

// No queremos que entren estudiantes o visitantes aquí
if (has_capability('moodle/course:create', $context)) {
    // Cargar el controlador solo si el usuario tiene permisos
    require_once "controllers/template.controller.php";
    
    $index = new TemplateController;
    $index->index();
} else {

    redirect($CFG->wwwroot);

}
