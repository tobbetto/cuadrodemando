<?php
/**
* El template gestiona la pare JS y dirige el usuarios a las páginas
* correspondientes. Hay que añadir el siguiente código al .conf del apache
* o del httpd:
*     <Directory "/moodle/www/local/cuadrodemando/">
*        Options All -Indexes
*        Options -MultiViews
*
*        RewriteEngine On
*        RewriteCond %{REQUEST_FILENAME} !-f
*        RewriteRule ^ index.php [QSA,L]
*    </Directory>
*
*/
$routesArray = explode('/', $_SERVER['REQUEST_URI']);
$routesArray = array_filter($routesArray);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CdM | Cuadro de Mando</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" type="text/bundle" href="fonts.googleapi.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="views/assets/scripts/fontawesome/css/all.min.css">
    <script  type="text/bundle" src="views/assets/scripts/fontawesome/js/all.min.js" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script type="text/javascript" src="views/assets/scripts/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4  -->
    <script type="text/javascript" src="views/assets/scripts/jquery/jquery-ui.min.js"></script>

    <!-- jQuery Knob Chart -->
    <script type="text/javascript" src="views/assets/scripts/jquery/jquery.knob.min.js"></script>

    <!-- FLOT CHARTS -->
    <script type="text/javascript" src="views/assets/scripts/jquery/jquery.flot.min.js"></script>
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
    <script type="text/bundle" src="views/assets/scripts/jquery/jquery.flot.resize.js"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
    <script type="text/bundle" src="views/assets/scripts/jquery/jquery.flot.pie.js"></script>
    
    <!-- Bootstrap 5 -->
    <script type="text/javascript" src="views/assets/scripts/bootstrap/bootstrap.bundle.min.js"></script>

    <!-- Map of Spain -->
    <script src="views/assets/scripts/map/mapa.js"></script>
    <link rel="stylesheet" href="views/assets/scripts/map/estilos.css"/>

    <!-- ChartJS -->
    <script src="views/assets/scripts/chart/chart.umd.js"></script>

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="views/assets/scripts/overlayscrollbars/overlayscrollbars.min.css">
    <script type="text/javascript" src="views/assets/scripts/overlayscrollbars/overlayscrollbars.browser.es6.min.js"></script>

    <!-- DataTables  & Plugins -->
    <link rel="stylesheet" href="views/assets/scripts/datatables/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="views/assets/scripts/datatables/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="views/assets/scripts/datatables/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="views/assets/scripts/datatables/datatables.min.css" >
    <script type="text/javascript" src="views/assets/scripts/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="views/assets/scripts/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="views/assets/scripts/datatables/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="views/assets/scripts/datatables/jszip.min.js"></script>
    <script type="text/javascript" src="views/assets/scripts/datatables/pdfmake.min.js"></script>
    <script type="text/javascript" src="views/assets/scripts/datatables/vfs_fonts.js"></script>
    <script type="text/javascript" src="views/assets/scripts/datatables/buttons.html5.min.js"></script>
    <script type="text/javascript" src="views/assets/scripts/datatables/buttons.print.min.js"></script>
    <script type="text/javascript" src="views/assets/scripts/datatables/buttons.bootstrap5.min.js"></script>
    <script type="text/javascript" src="views/assets/scripts/datatables/buttons.colVis.min.js"></script>

    <!-- Theme style -->
    <link rel="stylesheet" href="views/assets/scripts/adminlte/adminlte.min.css">  
    
    <!-- AdminLTE App -->
    <script src="views/assets/scripts/adminlte/adminlte.min.js"></script>


</head>
<body class="hold-transition sidebar-mini layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">

  <!-- Navbar -->
  <?php require 'views/modules/navbar.php'; ?>
  <!-- End Navbar -->

  <!-- Sidebar -->
    <?php require 'views/modules/sidebar.php'; ?>
  <!-- End Sidebar -->

  <!-- Content Wrapper. Contains page content -->

  <?php

if (strstr($routesArray[1], 'moodle')) {

  if (!empty($routesArray[3])) {
    if (strstr($routesArray[3], 'users')) {
      include "views/pages/users/users.php";
    } elseif (strstr($routesArray[3], 'courses')) {
      include "views/pages/courses/courses.php";
    } elseif (strstr($routesArray[3], 'home')) {
      include "views/pages/home/home.php";
    } elseif (strstr($routesArray[3], 'index.php')) {
      include "views/pages/home/home.php";
    } elseif (strstr($routesArray[3], 'geo')) {
      include "views/pages/geo/geo.php";
    } else {
        require "views/pages/404/404.php";
    }
  } else {
    include "views/pages/home/home.php";
  } 

} else {

    if (!empty($routesArray[2])) {
        if (strstr($routesArray[2], 'users')) {
          include "views/pages/users/users.php";
        } elseif (strstr($routesArray[2], 'courses')) {
          include "views/pages/courses/courses.php";
        } elseif (strstr($routesArray[2], 'home')) {
          include "views/pages/home/home.php";
        } elseif (strstr($routesArray[2], 'index.php')) {
          include "views/pages/home/home.php";
        } elseif (strstr($routesArray[2], 'geo')) {
          include "views/pages/geo/geo.php";
        } else {
            require "views/pages/404/404.php";
        }
      } else {
        include "views/pages/home/home.php";
    }


  } 

    ?>

  <!-- /.content-wrapper -->

  <!-- footer -->
    <?php require 'views/modules/footer.php'; ?>
  <!-- /.footer -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->



</body>
</html>
<script>
  $(document).ready(function() {
    $('.spinner-border').hide();
  });
</script>
