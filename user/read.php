<?php
    require '../inc/database.php';
    require '../inc/functions.php';
    checkLogin();
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }

    if ( null==$id ) {
        header("Location: index.php");
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM accounts where id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">

                <div class="span10 offset1">
                    <div class="row">
                        <h3>Read a Customer</h3>
                    </div>

                    <div class="form-horizontal" >
                      <div class="control-group">
                        <label class="control-label">คำนำหน้า</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $data['nameprefix'];?>
                            </label>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">ชื่อ</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $data['firstname'];?>
                            </label>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">นามสกุล</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $data['lastname'];?>
                            </label>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">หมายเลขบัตรประชาชน</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $data['ThaiID'];?>
                            </label>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">วัน/เดือน/ปี เกิด</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php
					$dob = $data['dateofbirth'];
					$from = new DateTime($dob);
					$to = new DateTime('today');
					$age = $from->diff($to)->y;
					echo $dob . " (อายุ " . $age ." ปี)";
				?>
                            </label>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">อีเมลล์</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $data['email'];?>
                            </label>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">หมายเลขโทรศัพท์</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $data['mobile'];?>
                            </label>
                        </div>
                      </div>
                        <div class="form-actions">
                          <a class="btn" href="index.php">Back</a>
                       </div>


                    </div>
                </div>

    </div> <!-- /container -->
  </body>
</html>
