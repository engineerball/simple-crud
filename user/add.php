<?php

    require '../inc/database.php';
    require '../inc/functions.php';

    if ( !empty($_POST)) {
        // keep track validation errors
        $thaiIDError = null;
        $nameError = null;
        $emailError = null;
        $mobileError = null;

        // keep track post values
        $thaiID = $_POST['thaiID'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];

        // validate input
        $valid = true;
        if (empty($thaiID)) {
            $thaiIDError = 'Please enter Thai ID';
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
            #$accountID = EncryptionID($thaiID);
            $accountID = $thaiID;
            $q->execute(array($name,$thaiID,$email,$accountID,$created_date));


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
    <script>
    function check_idcard(idcard){
      if(idcard.value == ""){ return false;}
      if(idcard.length < 13){ return false;}

      var num = str_split(idcard); // function เพิ่มเติม
      var sum = 0;
      var total = 0;
      var digi = 13;

      for(i=0;i<12;i++){
        sum = sum + (num[i] * digi);
        digi--;
      }
      total = ((11 - (sum % 11)) % 10);

      if(total == num[12]){ //	alert('รหัสหมายเลขประจำตัวประชาชนถูกต้อง');
      return true;
      }else{ //	alert('รหัสหมายเลขประจำตัวประชาชนไม่ถูกต้อง');
        return false;
      }
    }

    function str_split ( f_string, f_split_length){
      f_string += '';
      if (f_split_length == undefined) {
        f_split_length = 1;
      }
      if(f_split_length > 0){
        var result = [];
        while(f_string.length > f_split_length) {
          result[result.length] = f_string.substring(0, f_split_length);
          f_string = f_string.substring(f_split_length);
        }
        result[result.length] = f_string;
        return result;
      }
      return false;
    }

    function id_card(id){
      if(check_idcard(id.value)){
        //alert("ID Card Completed.");
      }else{
        alert("ID Card Error ?\nPlease Tye Again");
        id.value = "";
        id.focus();
      }
    }
    </script>
</head>

<body>
    <div class="container">

                <div class="span10 offset1">
                    <div class="row">
                        <h3>Create a account</h3>
                    </div>

                    <form class="form-horizontal" id="formAddUser" action="add.php" method="post">
                      <div class="control-group <?php echo !empty($thaiIDError)?'error':'';?>">
                        <label class="control-label">เลขบัตรประชาชน</label>
                        <div class="controls">
                            <input name="thaiID" id="thaiID" type="text"  placeholder="เลขบัตรประชาชน" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57 ){event.returnValue = false;}" maxlength="13" value="<?php echo !empty($thaiID)?$thaiID:'';?>">
                            <?php if (!empty($thaiIDError)): ?>
                                <span class="help-inline"><?php echo $thaiIDError;?></span>
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
                          <button type="submit" class="btn btn-success" onClick="id_card(document.getElementById('thaiID'))">Create</button>
                          <a class="btn" href="index.php">Back</a>
                        </div>
                    </form>
                </div>

    </div> <!-- /container -->
  </body>
</html>
