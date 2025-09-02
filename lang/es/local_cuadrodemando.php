<?php
/**
 * Cadenas de idioma en español para el plugin Cuadro de Mando
 *
 * @package    local_cuadrodemando
 * @author     Thorvaldur Konradsson
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Cuadro de Mando';
$string['dashboard'] = 'Cuadro de Mando';
$string['dashboard:view'] = 'Ver cuadro de mando';
$string['dashboard:manage'] = 'Gestionar cuadro de mando';

// Cadenas de navegación
$string['home'] = 'Inicio';
$string['users'] = 'Usuarios';
$string['courses'] = 'Cursos';
$string['geography'] = 'Geografía';

// Títulos de página
$string['pagetitle'] = 'Cuadro de Mando - {$a}';
$string['welcometodashboard'] = 'Bienvenido al Cuadro de Mando';

// Cadenas de estadísticas
$string['totalusers'] = 'Total de Usuarios';
$string['totalcourses'] = 'Total de Cursos';
$string['totalenrollments'] = 'Total de Inscripciones';
$string['activeusers'] = 'Usuarios Activos';
$string['activeenrolments'] = 'Matriculaciones activas';
$string['registeredusers'] = 'Usuarios registrados';
$string['uniqueaccesses'] = 'Accesos únicos';

// Mensajes de error
$string['nopermission'] = 'No tienes permisos para ver el cuadro de mando';
$string['missingglobalvars'] = 'Faltan variables globales requeridas';

// Configuración
$string['dashboardsettings'] = 'Configuración del Cuadro de Mando';
$string['enablecharts'] = 'Habilitar Gráficos';
$string['enablecharts_desc'] = 'Habilitar la visualización de gráficos en el cuadro de mando';
$string['refreshinterval'] = 'Intervalo de Actualización de Datos';
$string['refreshinterval_desc'] = 'Con qué frecuencia actualizar los datos del cuadro de mando (en minutos)';

// Breadcrumbs (Migas de pan)
$string['breadcrumb:home'] = 'Inicio';
$string['breadcrumb:users'] = 'Usuarios';
$string['breadcrumb:courses'] = 'Cursos';
$string['breadcrumb:geography'] = 'Geografía';

// Selector de idioma
$string['language_selector'] = 'Idioma:';
$string['lang_english'] = 'English';
$string['lang_spanish'] = 'Español';
$string['lang_icelandic'] = 'Íslenska';
$string['lang_catalan'] = 'Català';
$string['selectlanguage'] = 'Selecciona idioma';

// Páginas de error
$string['pagenotfound'] = 'Página No Encontrada';
$string['pagenotfound_desc'] = 'La página solicitada no se pudo encontrar.';
$string['returntodashboard'] = 'Volver al Cuadro de Mando';

// Página de geografía
$string['geo'] = 'Geografía';
$string['geo_instructions'] = 'Pasa el cursor sobre cada provincia para ver sus datos y pulsa para más detalles';
$string['provinces_total'] = 'Total Provincias';
$string['province_last_30_days'] = 'Datos de la provincia durante los últimos 30 días';
$string['sessions_last_hour'] = 'Sesiones abiertas última hora';
$string['active_users_last_hour'] = 'Usuarios activos última hora';
$string['completions_last_month'] = 'Finalizaciones el último mes';
$string['enrollments_last_month'] = 'Matriculaciones el último mes';
$string['registrations_last_month'] = 'Altas el último mes';
$string['deletions_last_month'] = 'Bajas el último mes';
$string['geo_data_loading'] = 'Cargando datos geográficos...';
$string['map_loading'] = 'Cargando mapa interactivo...';
$string['visiblecourses'] = 'Cursos visibles';
$string['userdetails_student'] = 'Detalles del alumno: {$a}';
$string['userdetails_teacher'] = 'Detalles del docente: {$a}';
$string['userdetails_user'] = 'Detalles del usuario: {$a}';
$string['users_overview'] = 'Vista general de los usuarios';
$string['totalusers'] = 'Total usuarios';
$string['activeusers_month'] = 'Usuarios activos (este mes)';
$string['newusers_month'] = 'Nuevos usuarios (este mes)';
$string['onlineusers'] = 'Usuarios en línea';
$string['login_statistics'] = 'Estadísticas de acceso';
$string['user_changes'] = 'Cambios de usuarios';

// Detalles del curso
$string['coursedetails'] = 'Detalles del curso: {$a}';
$string['coursesoverview'] = 'Vista general de los cursos de {$a}';
$string['coursescreated'] = 'Cursos creados ({$a})';
$string['coursesactive'] = 'Cursos activos ({$a})';
$string['coursesfinished'] = 'Cursos finalizados ({$a})';
$string['averageenrollment'] = 'Media matriculados';

// Enlaces y acciones
$string['viewindashboard'] = 'Ver en el Cuadro de Mando';
$string['viewinmoodle'] = 'Ver en Moodle';
$string['viewcoursedetail'] = 'Ver detalle del curso';
$string['viewteacherdetail'] = 'Ver detalle del ponente';
$string['viewstudentdetail'] = 'Ver detalle del estudiante';
$string['viewuserdetail'] = 'Ver su información detallada';
$string['viewenrolledstudents'] = 'Ver los estudiantes matriculados';
$string['clickhere'] = 'Haz clic aquí para';
$string['clickherefor'] = 'Haz click aquí para';
$string['sendemailtoperson'] = 'Enviar email a la persona';
$string['sendemail'] = 'Enviar email';
$string['managecourse'] = 'Gestionar curso';
$string['backtolist'] = 'Atrás a listado completo de usuarios';

// Tablas y listados
$string['courselist'] = 'Listado de cursos ({$a})';
$string['enrolledinacourse'] = 'Matriculados en el curso: <strong>{$a}</strong>';
$string['platformusers'] = 'Usuarios de la plataforma';
$string['courselistwhereisrole'] = 'Listado de cursos dónde <b>{$a->fullname}</b> es {$a->role}. En total: <b>{$a->count}</b>';
$string['teacher'] = 'docente o gestor';
$string['student'] = 'estudiante';
$string['intotal'] = 'En total';

// Estados de cursos
$string['notstarted'] = 'No empezado';
$string['finished'] = 'Finalizado';
$string['active'] = 'Activo';
$string['noenddate'] = 'Sin fecha fin';
$string['notfinished'] = 'No finalizado';

// Encabezados de tablas
$string['id'] = 'ID';
$string['identification'] = 'Identificación';
$string['fullname'] = 'Nombre largo';
$string['shortname'] = 'Nombre corto';
$string['name'] = 'Nombre';
$string['email'] = 'Email';
$string['city'] = 'Ciudad';
$string['department'] = 'Departamento';
$string['province'] = 'Provincia';
$string['address'] = 'Dirección';
$string['teachers'] = 'Formador(es)';
$string['students_count'] = '# de alumnos';
$string['completed_count'] = '# Finalizados';
$string['completed_percent'] = '% Finalizados';
$string['status'] = 'Estatus';
$string['manageinmoodle'] = 'Gestionar en Moodle';
$string['coursestartdate'] = 'Fecha inicio curso';
$string['courseenddate'] = 'Fecha fin del curso';
$string['completiondate'] = 'Fecha finalización';
$string['enrollmentdate'] = 'Fecha matriculación';
$string['coursefinished'] = 'Curso finalizado';
$string['user'] = 'Usuario';

// Mensajes
$string['noactiveusers'] = 'No hay usuarios activos';
$string['noenrolled'] = '0 matriculados';

// Navegación y títulos
$string['navigateyeardata'] = 'Navega por los datos del último año';
$string['back'] = 'Atrás';
$string['viewdetail'] = 'Ver detalle';
$string['collapse'] = 'Colapsar';
$string['remove'] = 'Eliminar';

// Fechas
$string['startdate'] = 'Fecha inicio';
$string['enddate'] = 'Fecha fin';
$string['enrolldate'] = 'Fecha matriculación';
$string['completiondate'] = 'Fecha finalización';

// Acciones de botones
$string['viewcourse'] = 'Ver curso';
$string['configurecourse'] = 'Configurar curso';
$string['viewenrolled'] = 'Ver matriculados';
