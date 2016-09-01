<?php

    require '../inc/database.php';
    require '../inc/functions.php';

    if ( !empty($_POST)) {
        // keep track validation errors
        $studentIDError = null;
        $nameError = null;
        $emailError = null;
        $mobileError = null;

        // keep track post values
        $studentID = $_POST['studentID'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];

        // validate input
        $valid = true;
        if (empty($studentID)) {
            $studentIDError = 'Please enter Student ID';
            $valid = false;
        }

        if (empty($name)) {
            $nameError = 'Please enter Name';
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

        // insert data
        if ($valid) {

            $created_date = date("Y-m-d H:i:s");
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO accounts (name,studentID,email,accountID,created_at) values(?, ?, ?, ?,?)";
            $q = $pdo->prepare($sql);
            $accountID = EncryptionID($studentID);
            $q->execute(array($name,$studentID,$email,$accountID,$created_date));


            $sql = "SELECT id FROM accounts WHERE accountID = '$accountID'";
            foreach ($pdo->query($sql) as $row) {
                $accounts_id = $row['id'];
            };


            $sql = "INSERT INTO radcheck (username,attribute,op,value,accounts_id) values(?, ?, ?, ?, ?)";
            $username = $accountID;
            $value = $accountID;
            $attribute = "User-Password";
            $op = ":=";
            $q = $pdo->prepare($sql);
            $q->execute(array($username,$attribute,$op,$value,$accounts_id));


            Database::disconnect();
            header("Location: index.php");
        }
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
                        <h3>Create a account</h3>
                    </div>

                    <form class="form-horizontal" action="add.php" method="post">
                      <div class="control-group <?php echo !empty($studentIDError)?'error':'';?>">
                        <label class="control-label">Student ID</label>
                        <div class="controls">
                            <input name="studentID" type="text"  placeholder="Student ID" value="<?php echo !empty($studentID)?$studentID:'';?>">
                            <?php if (!empty($studentIDError)): ?>
                                <span class="help-inline"><?php echo $studentIDError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($nameError)?'error':'';?>">
                        <label class="control-label">Name</label>
                        <div class="controls">
                            <input name="name" type="text"  placeholder="Name" value="<?php echo !empty($name)?$name:'';?>">
                            <?php if (!empty($nameError)): ?>
                                <span class="help-inline"><?php echo $nameError;?></span>
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
                          <button type="submit" class="btn btn-success">Create</button>
                          <a class="btn" href="index.php">Back</a>
                        </div>
                    </form>
                </div>

    </div> <!-- /container -->
  </body>
</html>
