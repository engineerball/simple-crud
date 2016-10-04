<?php

require '../inc/database.php';
require '../inc/functions.php';
checkLogin();
if ( !empty($_POST)) {
  // keep track validation errors
  $nameprefixError = null;
  $thaiIDError = null;
  $firstnameError = null;
  $lastnameError = null;
  $emailError = null;
  $mobileError = null;

  // keep track post values
  $thaiID = $_POST['thaiID'];
  $nameprefix = $_POST['nameprefix'];
  $firstname = $_POST['firstname'];
  $lastname = $_POST['firstname'];
  $email = $_POST['email'];
  $mobile = $_POST['mobile'];

  // validate input
  $valid = true;
  if (empty($nameprefix)) {
    $nameprefixError = 'Please enter name prefix';
    $valid = false;
  }

  if (empty($thaiID)) {
    $thaiIDError = 'Please enter Thai ID';
    $valid = false;
  }

  if (empty($firstname)) {
    $firstnameError = 'Please enter First name';
    $valid = false;
  }

  if (empty($lastname)) {
    $lastnameError = 'Please enter Last name';
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
    $sql = "INSERT INTO accounts (nameprefix, firstname,lastname,ThaiID,email,created_at) values(?, ?, ?, ?, ?,?)";
    $q = $pdo->prepare($sql);
    #$accountID = EncryptionID($thaiID);
    #$accountID = $thaiID;
    $q->execute(array($nameprefix,$firstname,$lastname,$thaiID,$email,$created_date));


    $sql = "SELECT id FROM accounts WHERE ThaiID = '$thaiID'";
    foreach ($pdo->query($sql) as $row) {
      $accounts_id = $row['id'];
    };


    $sql = "INSERT INTO radcheck (username,attribute,op,value,accounts_id) values(?, ?, ?, ?, ?)";
    $username = $thaiID;
    $value = $thaiID;
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
  <link   href="../assets/css/simple-sidebar.css" rel="stylesheet">
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
      <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
          <li class="sidebar-brand">
            <a href="#">
              Start Bootstrap
            </a>
          </li>
          <li>
            <a href="#">Dashboard</a>
          </li>
          <li>
            <a href="#">Shortcuts</a>
          </li>
          <li>
            <a href="#">Overview</a>
          </li>
          <li>
            <a href="#">Events</a>
          </li>
          <li>
            <a href="#">About</a>
          </li>
          <li>
            <a href="#">Services</a>
          </li>
          <li>
            <a href="#">Contact</a>
          </li>
        </ul>
      </div>
      <!-- /#sidebar-wrapper -->

      <div id="page-content-wrapper">
        <div class="container">

          <div class="span10 offset1">
            <div class="row">
              <h3>Create a account</h3>
            </div>

            <form class="form-horizontal" id="formAddUser" action="add.php" method="post">
              <div class="control-group <?php echo !empty($nameprefixError)?'error':'';?>">
                <label class="control-label">คำนำหน้า</label>
                <div class="controls">
                  <label class="radio-inline"><input type="radio" name="nameprefix" <?php if (isset($nameprefix) && $nameprefix=="เด็กชาย") echo "checked";?> value="เด็กชาย">เด็กชาย</label>
                  <label class="radio-inline"><input type="radio" name="nameprefix" <?php if (isset($nameprefix) && $nameprefix=="เด็กหญิง") echo "checked";?> value="เด็กหญิง">เด็กหญิง</label>
                  <label class="radio-inline"><input type="radio" name="nameprefix" <?php if (isset($nameprefix) && $nameprefix=="นาย") echo "checked";?> value="นาย">นาย</label>
                  <label class="radio-inline"><input type="radio" name="nameprefix" <?php if (isset($nameprefix) && $nameprefix=="นางสาว") echo "checked";?> value="นางสาว">นางสาว</label>
                  <label class="radio-inline"><input type="radio" name="nameprefix" <?php if (isset($nameprefix) && $nameprefix=="นาง") echo "checked";?> valuse="นาง">นาง</label>
                  <?php if (!empty($nameprefixError)): ?>
                    <span class="help-inline"><?php echo $nameprefixError;?></span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="control-group <?php echo !empty($thaiIDError)?'error':'';?>">
                <label class="control-label">เลขบัตรประชาชน</label>
                <div class="controls">
                  <input name="thaiID" id="thaiID" type="text"  placeholder="เลขบัตรประชาชน" onKeyPress="if (event.keyCode < 48 || event.keyCode > 57 ){event.returnValue = false;}" maxlength="13" value="<?php echo !empty($thaiID)?$thaiID:'';?>">
                  <?php if (!empty($thaiIDError)): ?>
                    <span class="help-inline"><?php echo $thaiIDError;?></span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="control-group <?php echo !empty($firstnameError)?'error':'';?>">
                <label class="control-label">ชื่อ</label>
                <div class="controls">
                  <input name="firstname" type="text"  placeholder="First Name" value="<?php echo !empty($firstname)?$firstname:'';?>">
                  <?php if (!empty($firstnameError)): ?>
                    <span class="help-inline"><?php echo $firstnameError;?></span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="control-group <?php echo !empty($lastnameError)?'error':'';?>">
                <label class="control-label">นามสกุล</label>
                <div class="controls">
                  <input name="lastname" type="text"  placeholder="Last Name" value="<?php echo !empty($lastname)?$lastname:'';?>">
                  <?php if (!empty($lastnameError)): ?>
                    <span class="help-inline"><?php echo $lastnameError;?></span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="control-group <?php echo !empty($emailError)?'error':'';?>">
                <label class="control-label">อีเมลล์</label>
                <div class="controls">
                  <input name="email" type="text" placeholder="Email Address" value="<?php echo !empty($email)?$email:'';?>">
                  <?php if (!empty($emailError)): ?>
                    <span class="help-inline"><?php echo $emailError;?></span>
                  <?php endif;?>
                </div>
              </div>
              <div class="control-group <?php echo !empty($mobileError)?'error':'';?>">
                <label class="control-label">โทรศัพท์</label>
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
      </div>
    </div>
  </body>
  </html>
