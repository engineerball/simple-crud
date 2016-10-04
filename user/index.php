<?php
include '../inc/database.php';
include '../inc/functions.php';
checkLogin();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link   href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link   href="../assets/css/simple-sidebar.css" rel="stylesheet">
  <script src="../assets/js/bootstrap.min.js"></script>
</head>

<body>
  <div id="wrapper">
    <!-- Sidebar -->
    <?php
      printSidebar();
     ?>
    <!-- /#sidebar-wrapper -->

    <div id="page-content-wrapper">
      <div class="container">
        <div class="row">
          <h3>User account management</h3>
        </div>
        <div class="row">
          <p>
            <a href="add.php" class="btn btn-success">Create</a>
          </p>
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>ชื่อ</th>
                <th>นามสกุล</th>
                <th>อีเมลล์</th>
                <th>account</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $pdo = Database::connect();
              $sql = 'SELECT
              accounts.id AS id,
              accounts.firstname,
              accounts.lastname,
              accounts.ThaiID,
              accounts.email,
              radcheck.username AS accountID
              FROM
              accounts
              INNER JOIN radcheck ON radcheck.accounts_id = accounts.id
              ';
              foreach ($pdo->query($sql) as $row) {
                echo '<tr>';
                echo '<td>'. $row['firstname'] . '</td>';
                echo '<td>'. $row['lastname'] . '</td>';
                echo '<td>'. $row['email'] . '</td>';
                echo '<td>'. $row['ThaiID'] . '</td>';
                echo '<td width=250>';
                echo '<a class="btn" href="read.php?id='.$row['id'].'">Read</a>';
                echo ' ';
                echo '<a class="btn btn-success" href="edit.php?id='.$row['id'].'">Update</a>';
                echo ' ';
                echo '<a class="btn btn-danger" href="delete.php?id='.$row['id'].'">Delete</a>';
                echo '</td>';
                echo '</tr>';
              }
              Database::disconnect();
              ?>
            </tbody>
          </table>
        </div>
      </div> <!-- /container -->
    </div>
  </div>
</body>
</html>
