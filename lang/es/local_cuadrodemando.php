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
$string['coursedetails'] = 'Detalles del curso';
$string['coursesoverview'] = 'Vista general de los cursos';
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
$string['noopensessions'] = 'No hay sesiones abiertas';
$string['notcompleted'] = 'No finalizado';

// Navegación y títulos
$string['navigateyeardata'] = 'Navega por los datos del último año';
$string['viewstatisticsof'] = 'Ver estatísticas de {$a->month} de {$a->year}';
$string['back'] = 'Atrás';
$string['viewdetail'] = 'Ver detalle';
$string['collapse'] = 'Colapsar';
$string['remove'] = 'Eliminar';

// Fechas
$string['startdate'] = 'Fecha inicio';
$string['enddate'] = 'Fecha fin';
$string['enrolldate'] = 'Fecha matriculación';
$string['completiondate'] = 'Fecha finalización';
$string['coursestartdate'] = 'Fecha inicio curso';
$string['courseenddate'] = 'Fecha fin del curso';

// Acciones de botones
$string['viewcourse'] = 'Ver curso';
$string['configurecourse'] = 'Configurar curso';
$string['viewenrolled'] = 'Ver matriculados';

// Nuevas cadenas añadidas
$string['coursesmostenrollmentslastyear'] = 'Cursos con mayor # de matriculados último año';
$string['completed'] = 'Finalizados';
$string['notcompleted'] = 'No finalizados';
$string['totalenrolled'] = 'Total matriculados:';
$string['completedpercentage'] = 'Porcentaje finalizados:';
$string['viewcoursedetail'] = 'Ver detalle del curso';
$string['gotocourseinplatform'] = 'Ir al curso en la plataforma';
$string['uniqueaccessesplatformlastyearbyday'] = 'Accesos únicos a la plataforma el último año por día';
$string['accessesofuserlastyearbyday'] = 'Accesos de <b>{$a}</b> el último año por día';
$string['top10provincesmostusers'] = 'Las 10 provincias con más usuarios';
$string['person'] = 'Persona';
$string['addresslabel'] = 'Dirección:';
$string['citylabel'] = 'Ciudad:';
$string['provincelabel'] = 'Provincia:';
$string['phonelabel'] = 'Teléfono:';
$string['emaillabel'] = 'Email:';
$string['teamslabel'] = 'Teams:';
$string['talkonteams'] = 'Hablar por Teams';
$string['openchatinplatform'] = 'Abrir chat en la plataforma';
$string['viewprofileinplatform'] = 'Ver perfil en la plataforma';
$string['provincesmorepercentusers'] = 'Provincias con más % de usuarios';
$string['categories'] = 'Categorías';
$string['times'] = 'Tiempos';
$string['percenttotalplatformcourses'] = '% del total de los cursos de la plataforma';
$string['gotocategoryinplatform'] = 'Ir a la categoría en la plataforma';
$string['coursegeographyandtimes'] = 'Geografía y tiempos del curso';
$string['variouscourseinformation'] = 'Información varia del curso';
$string['numberstudentsneverentered'] = '# de alumnos que nunca entraron';
$string['numberstudentprovinces'] = '# de provincias de los alumnos';
$string['numberenrolledteachers'] = '# de profesores matriculados';
$string['numberusedresources'] = '# de recursos utilizados';
$string['completionstatus'] = 'Estado de finalización';
$string['categoriesandtimes'] = 'Categorías y tiempos';
$string['withoutinstitution'] = 'Sin institución';

// Cadenas añadidas para home.php
$string['opensessionsnow'] = 'Sesiones abiertas ahora:';
$string['completionsthismonth'] = 'Finalizaciones este mes:';
$string['nocompletionsthismonth'] = 'No hay finalizaciones este mes 😭';
$string['registrationsthismonth'] = 'Altas este mes:';
$string['noregistrationsthismonth'] = 'No hay altas este mes 😭';
$string['accessesthismonth'] = 'Accesos este mes:';
$string['noaccessesthismonth'] = 'No hay accesos este mes 😭';
$string['activeuserslasthour'] = 'Usuarios activos última hora:';
$string['noactiveusers'] = 'No hay usuarios activos 😭';
$string['enrollmentsthismonth'] = 'Matriculaciones este mes:';
$string['noenrollmentsthismonth'] = 'No hay matriculaciones este mes 😭';
$string['suspensionsthismonth'] = 'Bajas este mes:';
$string['nosuspensionsthismonth'] = 'No hay bajas este mes 😀';
$string['messagesthismonth'] = 'Mensajes este mes:';
$string['nomessagesthismonth'] = 'No hay mensajes este mes 😭';
$string['calendar'] = 'Calendario';

// DataTables strings
$string['copytable'] = 'Copiar tabla';
$string['exportcsv'] = 'Exportar CSV';
$string['exportexcel'] = 'Exportar Excel';
$string['exportpdf'] = 'Exportar PDF';
$string['printtable'] = 'Imprimir tabla';
$string['filtercolumns'] = 'Filtrar columnas';
$string['showingrecords'] = 'Mostrando _START_ a _END_ de _TOTAL_ registros';
$string['previous'] = 'Anterior';
$string['first'] = 'Primero';
$string['last'] = 'Último';
$string['next'] = 'Siguiente';
$string['copy'] = 'Copiar';
$string['hidecolumns'] = 'Ocultar columnas';
$string['collection'] = 'Colección';
$string['restorevisibility'] = 'Restaurar visibilidad';
$string['copykeys'] = 'Presione ctrl o ⌘ + C para copiar los datos de la tabla al portapapeles del sistema.<br /><br />Para cancelar, haga clic en este mensaje o presione escape.';
$string['copytitle'] = 'Copiar al portapapeles';
$string['csv'] = 'CSV';
$string['excel'] = 'Excel';
$string['showallrows'] = 'Mostrar todas las filas';
$string['showrows'] = 'Mostrar %d filas';
$string['pdf'] = 'PDF';
$string['print'] = 'Imprimir';
$string['processing'] = 'Procesando...';
$string['lengthmenu'] = 'Mostrar _MENU_ registros por página';
$string['zerorecords'] = 'No se encontraron resultados';
$string['emptytable'] = 'Ningún dato disponible en esta tabla';
$string['infoempty'] = 'Mostrando registros del 0 al 0 de un total de 0 registros';
$string['infofiltered'] = '(filtrado de un total de _MAX_ registros)';
$string['search'] = 'Buscar:';
$string['loadingrecords'] = 'Cargando...';
$string['loadmessage'] = 'Cargando paneles de búsqueda';
$string['showmessage'] = 'Mostrar Todo';
$string['emptypanes'] = 'Sin paneles de búsqueda';
$string['title'] = 'Filtros Activos - %d';
$string['collapsemessage'] = 'Colapsar Todo';
$string['clearmessage'] = 'Borrar todo';
$string['searchpanes'] = 'Paneles de búsqueda';
$string['searchpanesplural'] = 'Paneles de búsqueda (%d)';
$string['all'] = 'Todos';

// Month names for DataTables internationalization
$string['january'] = 'Enero';
$string['february'] = 'Febrero';
$string['march'] = 'Marzo';
$string['april'] = 'Abril';
$string['may'] = 'Mayo';
$string['june'] = 'Junio';
$string['july'] = 'Julio';
$string['august'] = 'Agosto';
$string['september'] = 'Septiembre';
$string['october'] = 'Octubre';
$string['november'] = 'Noviembre';
$string['december'] = 'Diciembre';

// Weekday abbreviations for DataTables internationalization
$string['sunday'] = 'Dom';
$string['monday'] = 'Lun';
$string['tuesday'] = 'Mar';
$string['wednesday'] = 'Mié';
$string['thursday'] = 'Jue';
$string['friday'] = 'Vie';
$string['saturday'] = 'Sáb';

// Additional missing strings
$string['copyrow'] = 'Copiar 1 fila al portapapeles';
$string['copyrows'] = 'Copiar %d filas al portapapeles';
$string['averagecompletionindays'] = 'Promedio de finalización en días';
$string['completionindays'] = 'Finalización en días';
$string['numbercoursesincategory'] = '# de cursos en categoría';
