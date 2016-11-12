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
  $dateofbirthError = null;

  // keep track post values
  $thaiID = $_POST['thaiID'];
  $nameprefix = $_POST['nameprefix'];
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $email = $_POST['email'];
  $mobile = $_POST['mobile'];
  $dob_day = $_POST['dob-day'];
  $dob_month = $_POST['dob-month'];
  $dob_year = $_POST['dob-year'];

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

  if (empty($dob_day) || empty($dob_month) || empty($dob_year)) {
    $dateofbirthError = 'Please enter Mobile Number';
    $valid = false;
  }

  // insert data
  if ($valid) {

    $created_date = date("Y-m-d H:i:s");
    $dateofbirth = $dob_year . "-" . $dob_month . "-" . $dob_day;
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO accounts (nameprefix, firstname,lastname,ThaiID,dateofbirth, email,mobile,created_at) values(?, ?, ?, ?, ?, ?, ?, ?)";
    $q = $pdo->prepare($sql);
    #$accountID = EncryptionID($thaiID);
    #$accountID = $thaiID;
    $q->execute(array($nameprefix,$firstname,$lastname,$thaiID,$dateofbirth,$email,$mobile,$created_date));


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
<div class="control-group">
  <label for="dob-day" class="control-label">วัน/เดือน/ปี เกิด</label>
  <div class="controls">
    <select name="dob-day" id="dob-day">
      <option value="">Day</option>
      <option value="">---</option>
      <option value="01">01</option>
      <option value="02">02</option>
      <option value="03">03</option>
      <option value="04">04</option>
      <option value="05">05</option>
      <option value="06">06</option>
      <option value="07">07</option>
      <option value="08">08</option>
      <option value="09">09</option>
      <option value="10">10</option>
      <option value="11">11</option>
      <option value="12">12</option>
      <option value="13">13</option>
      <option value="14">14</option>
      <option value="15">15</option>
      <option value="16">16</option>
      <option value="17">17</option>
      <option value="18">18</option>
      <option value="19">19</option>
      <option value="20">20</option>
      <option value="21">21</option>
      <option value="22">22</option>
      <option value="23">23</option>
      <option value="24">24</option>
      <option value="25">25</option>
      <option value="26">26</option>
      <option value="27">27</option>
      <option value="28">28</option>
      <option value="29">29</option>
      <option value="30">30</option>
      <option value="31">31</option>
    </select>
    <select name="dob-month" id="dob-month">
      <option value="">Month</option>
      <option value="">-----</option>
      <option value="01">January</option>
      <option value="02">February</option>
      <option value="03">March</option>
      <option value="04">April</option>
      <option value="05">May</option>
      <option value="06">June</option>
      <option value="07">July</option>
      <option value="08">August</option>
      <option value="09">September</option>
      <option value="10">October</option>
      <option value="11">November</option>
      <option value="12">December</option>
    </select>
    <select name="dob-year" id="dob-year">
      <option value="">Year</option>
      <option value="">----</option>
      <option value="2012">2012</option>
      <option value="2011">2011</option>
      <option value="2010">2010</option>
      <option value="2009">2009</option>
      <option value="2008">2008</option>
      <option value="2007">2007</option>
      <option value="2006">2006</option>
      <option value="2005">2005</option>
      <option value="2004">2004</option>
      <option value="2003">2003</option>
      <option value="2002">2002</option>
      <option value="2001">2001</option>
      <option value="2000">2000</option>
      <option value="1999">1999</option>
      <option value="1998">1998</option>
      <option value="1997">1997</option>
      <option value="1996">1996</option>
      <option value="1995">1995</option>
      <option value="1994">1994</option>
      <option value="1993">1993</option>
      <option value="1992">1992</option>
      <option value="1991">1991</option>
      <option value="1990">1990</option>
      <option value="1989">1989</option>
      <option value="1988">1988</option>
      <option value="1987">1987</option>
      <option value="1986">1986</option>
      <option value="1985">1985</option>
      <option value="1984">1984</option>
      <option value="1983">1983</option>
      <option value="1982">1982</option>
      <option value="1981">1981</option>
      <option value="1980">1980</option>
      <option value="1979">1979</option>
      <option value="1978">1978</option>
      <option value="1977">1977</option>
      <option value="1976">1976</option>
      <option value="1975">1975</option>
      <option value="1974">1974</option>
      <option value="1973">1973</option>
      <option value="1972">1972</option>
      <option value="1971">1971</option>
      <option value="1970">1970</option>
      <option value="1969">1969</option>
      <option value="1968">1968</option>
      <option value="1967">1967</option>
      <option value="1966">1966</option>
      <option value="1965">1965</option>
      <option value="1964">1964</option>
      <option value="1963">1963</option>
      <option value="1962">1962</option>
      <option value="1961">1961</option>
      <option value="1960">1960</option>
      <option value="1959">1959</option>
      <option value="1958">1958</option>
      <option value="1957">1957</option>
      <option value="1956">1956</option>
      <option value="1955">1955</option>
      <option value="1954">1954</option>
      <option value="1953">1953</option>
      <option value="1952">1952</option>
      <option value="1951">1951</option>
      <option value="1950">1950</option>
      <option value="1949">1949</option>
      <option value="1948">1948</option>
      <option value="1947">1947</option>
      <option value="1946">1946</option>
      <option value="1945">1945</option>
      <option value="1944">1944</option>
      <option value="1943">1943</option>
      <option value="1942">1942</option>
      <option value="1941">1941</option>
      <option value="1940">1940</option>
      <option value="1939">1939</option>
      <option value="1938">1938</option>
      <option value="1937">1937</option>
      <option value="1936">1936</option>
      <option value="1935">1935</option>
      <option value="1934">1934</option>
      <option value="1933">1933</option>
      <option value="1932">1932</option>
      <option value="1931">1931</option>
      <option value="1930">1930</option>
      <option value="1929">1929</option>
      <option value="1928">1928</option>
      <option value="1927">1927</option>
      <option value="1926">1926</option>
      <option value="1925">1925</option>
      <option value="1924">1924</option>
      <option value="1923">1923</option>
      <option value="1922">1922</option>
      <option value="1921">1921</option>
      <option value="1920">1920</option>
      <option value="1919">1919</option>
      <option value="1918">1918</option>
      <option value="1917">1917</option>
      <option value="1916">1916</option>
      <option value="1915">1915</option>
      <option value="1914">1914</option>
      <option value="1913">1913</option>
      <option value="1912">1912</option>
      <option value="1911">1911</option>
      <option value="1910">1910</option>
      <option value="1909">1909</option>
      <option value="1908">1908</option>
      <option value="1907">1907</option>
      <option value="1906">1906</option>
      <option value="1905">1905</option>
      <option value="1904">1904</option>
      <option value="1903">1903</option>
      <option value="1901">1901</option>
      <option value="1900">1900</option>
    </select>
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
