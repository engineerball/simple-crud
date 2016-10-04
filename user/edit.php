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
    }

    if ( !empty($_POST)) {
        // keep track validation errors
        $nameprefixError = null;
        $firstnameError = null;
        $lastnameError = null;
        $emailError = null;
        $mobileError = null;

        // keep track post values
        $nameprefix = $_POST['nameprefix'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];

        // validate input
        $valid = true;
        if (empty($nameprefix)) {
            $nameprefix = 'Please enter prefix';
            $valid = false;
        }

        if (empty($firstname)) {
            $firstname = 'Please enter First name';
            $valid = false;
        }

        if (empty($lastname)) {
            $lastname = 'Please enter Last name';
            $valid = false;
        }

        if (empty($email)) {
            $emailError = 'Please enter Email Address';
            $valid = false;
        } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
            $emailError = 'Please enter a valid Email Address';
            $valid = false;
        }

        if (empty($mobile)) {
            $mobileError = 'Please enter Mobile Number';
            $valid = false;
        }

        // update data
        if ($valid) {
            $updated_date = date("Y-m-d H:i:s");
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE accounts  set nameprefix = ?, firstname = ?, lastname = ?, email = ?, mobile =?, updated_at =? WHERE id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($nameprefix,$firstname,$lastname,$email,$mobile,$updated_date,$id));
            Database::disconnect();
            header("Location: index.php");
        }
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM accounts where id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $nameprefix = $data['nameprefix'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $email = $data['email'];
        $mobile = $data['mobile'];
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
                        <h3>Update a User</h3>
                    </div>

                    <form class="form-horizontal" action="edit.php?id=<?php echo $id?>" method="POST">
                      <div class="control-group <?php echo !empty($nameprefixError)?'error':'';?>">
                        <label class="control-label">คำนำหน้า</label>
                        <div class="controls">
                            <label class="radio-inline"><input type="radio" name="nameprefix" <?php if (isset($nameprefix) && $nameprefix=="เด็กชาย") echo "checked";?> value="เด็กชาย">เด็กชาย</label>
                            <label class="radio-inline"><input type="radio" name="nameprefix" <?php if (isset($nameprefix) && $nameprefix=="เด็กหญิง") echo "checked";?> value="เด็กหญิง">เด็กหญิง</label>
                            <label class="radio-inline"><input type="radio" name="nameprefix" <?php if (isset($nameprefix) && $nameprefix=="นาย") echo "checked";?> value="นาย">นาย</label>
                            <label class="radio-inline"><input type="radio" name="nameprefix" <?php if (isset($nameprefix) && $nameprefix=="นางสาว") echo "checked";?> value="นางสาว">นางสาว</label>
                            <label class="radio-inline"><input type="radio" name="nameprefix" <?php if (isset($nameprefix) && $nameprefix=="นาง") echo "checked";?> value="นาง">นาง</label>
                            <?php if (!empty($nameprefixError)): ?>
                                <span class="help-inline"><?php echo $nameprefixError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($firstnameError)?'error':'';?>">
                        <label class="control-label">First Name</label>
                        <div class="controls">
                            <input name="firstname" type="text"  placeholder="First Name" value="<?php echo !empty($firstname)?$firstname:'';?>">
                            <?php if (!empty($firstnameError)): ?>
                                <span class="help-inline"><?php echo $firstnameError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($lastnameError)?'error':'';?>">
                        <label class="control-label">Last Name</label>
                        <div class="controls">
                            <input name="lastname" type="text"  placeholder="First Name" value="<?php echo !empty($lastname)?$lastname:'';?>">
                            <?php if (!empty($lastnameError)): ?>
                                <span class="help-inline"><?php echo $lastnameError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($emailError)?'error':'';?>">
                        <label class="control-label">Email Address</label>
                        <div class="controls">
                            <input name="email" type="text" placeholder="Email Address" value="<?php echo !empty($email)?$email:'';?>">
                            <?php if (!empty($emailError)): ?>
                                <span class="help-inline"><?php echo $emailError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($mobileError)?'error':'';?>">
                        <label class="control-label">Mobile Number</label>
                        <div class="controls">
                            <input name="mobile" type="text"  placeholder="Mobile Number" value="<?php echo !empty($mobile)?$mobile:'';?>">
                            <?php if (!empty($mobileError)): ?>
                                <span class="help-inline"><?php echo $mobileError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="form-actions">
                          <button type="submit" class="btn btn-success">Update</button>
                          <a class="btn" href="index.php">Back</a>
                        </div>
                    </form>
                </div>

    </div> <!-- /container -->
  </body>
</html>
