<?php
// filepath: c:\Users\tkonradsson\Downloads\adminlte_20250618\views\pages\courses\courses.php

global $DB, $CFG;
include_once 'views/getdata/getdata.php';
include_once 'views/services/CourseStatsService.php';

// --- Use the service in your view ---
$statsService = new CourseStatsService($DB);

$year = date('Y');
$courseid = $_GET['courseid'] ?? null;
$course_info = null;
if ($courseid) {
    $course_info = $DB->get_record('course', ['id' => $courseid]);
}

$countCreatedCourses = $statsService->getCreatedCoursesCount();
$countOpenCourses = $statsService->getOpenCoursesCount();
$countFinishedCourses = $statsService->getFinishedCoursesCount();
$avgCourseEnrolment = $statsService->getAverageCourseEnrolment();

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <?php if ($courseid && $course_info): ?>
            <h1>Detalles del curso: <b><?= htmlspecialchars($course_info->fullname) ?></b> ( <?= htmlspecialchars($course_info->shortname) ?> )</h1>
          <?php else: ?>
            <h1>Vista general de los cursos de <?= $year ?></h1>
          <?php endif ?>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $CFG->wwwroot ?>/adminlte/">Inicio</a></li>
            <li class="breadcrumb-item active"><a href="<?= $CFG->wwwroot ?>/adminlte/courses">Cursos</a></li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row<?= $courseid ? ' d-none' : '' ?>">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?= $countCreatedCourses ?></h3>
            </div>
            <div class="icon">
              <i class="fas fa-calendar-plus"></i>
            </div>
            <p class="small-box-footer">Cursos creados (<?= $year ?>)</p>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3><?= $countOpenCourses ?></h3>
            </div>
            <div class="icon">
              <i class="fas fa-calendar-check"></i>
            </div>
            <p class="small-box-footer">Cursos activos (<?= $year ?>)</p>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><?= $countFinishedCourses ?></h3>
            </div>
            <div class="icon">
              <i class="fas fa-calendar-xmark"></i>
            </div>
            <p class="small-box-footer">Cursos finalizados (<?= $year ?>)</p>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-primary">
            <div class="inner">
              <h3><?= $avgCourseEnrolment ?></h3>
            </div>
            <div class="icon">
              <i class="fas fa-users-line"></i>
            </div>
            <p class="small-box-footer">Media matriculados</p>
          </div>
        </div>
      </div>
      <!-- /.row -->
      <!-- ...rest of your code... -->
    </div>
  </section>
</div>
<!-- /.content -->