<?php
include 'inc/database.php';
include 'inc/functions.php';
session_start();
if(!$_SESSION['logged']){
    header("Location: admin_login.php");
    exit;
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link   href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link   href="assets/css/simple-sidebar.css" rel="stylesheet">
  <script src="assets/js/bootstrap.min.js"></script>
</head>

<body>
  <div id="wrapper">
    <!-- Sidebar -->
  <?php printSidebarMainPage(); ?>
    <!-- /#sidebar-wrapper -->

    <div id="page-content-wrapper">
      <div class="container">
        
      </div> <!-- /container -->
    </div>
  </div>
</body>
</html>
