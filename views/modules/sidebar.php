<?php global $USER, $OUTPUT, $PAGE, $CFG; $context = context_system::instance(); $PAGE->set_context($context);?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-success elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo $CFG->wwwroot ?>/local/cuadrodemando/" class="brand-link bg-success">
    <span class="brand-text font-weight-light">Cuadro de Mando</span>
      <!-- <img src="../../moodle/local/cuadrodemando/views/assets/img/logo_fos.svg" alt="SEPE Logo" class="brand-image" style="opacity: .8"> -->
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info" style="padding: 5px;">
        <?php echo $OUTPUT->user_picture($USER, ['size' => 45, 'includefullname' => 'true', 'class' => 'userpicture']); ?>
        </div>
        <div class="info">
          <a href="#" class="d-block"></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <li class="nav-item">
            <a href="<?php echo $CFG->wwwroot ?>/local/cuadrodemando" class="nav-link <?php if (empty($routesArray[2]) || strstr($routesArray[2], 'index.php')) : ?>active<?php endif ?>">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Inicio
              </p>
            </a>
          </li>
          <li class="nav-item ml-1">
            <a href="<?php echo $CFG->wwwroot ?>/local/cuadrodemando/users" class="nav-link <?php if (strstr($routesArray[2], 'users')) : ?>active<?php endif ?>">
                <i class="fas fa-users nav-icon"></i>
                <p>Usuarios</p>
            </a>
          </li>
          <li class="nav-item ml-1">
            <a href="<?php echo $CFG->wwwroot ?>/local/cuadrodemando/courses" class="nav-link <?php if (strstr($routesArray[2], 'courses')) : ?>active<?php endif ?>">
                <i class="fas fa-graduation-cap nav-icon"></i>
                <p>Cursos</p>
            </a>
          </li>
          <li class="nav-item ml-1">
            <a href="<?php echo $CFG->wwwroot ?>/local/cuadrodemando/geo" class="nav-link <?php if (strstr($routesArray[2], 'geo')) : ?>active<?php endif ?>">
                <i class="fas fa-globe-europe nav-icon"></i>
                <p>Geografia</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
