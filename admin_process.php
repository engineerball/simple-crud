<?php
if(isset($_POST['submit'])){
    require 'inc/database.php';
    // $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbUserPassword);
    $db = Database::connect();
    #$db         = new PDO("mysql:dbname=$dbDatabase;host=$dbHost;port=3306", $dbUser, $dbPass);

    $sql = $db->prepare("SELECT * FROM admin
        WHERE username = ? AND
        password = ?
        LIMIT 1");

    //Lets search the databse for the user name and password
    //Choose some sort of password encryption, I choose sha256
    //Password function (Not In all versions of MySQL).
    $username = $_POST['username'];
    $pas = hash('sha256', $_POST['password']);
    $sql->execute(array($username,$pas));

    // Row count is different for different databases
    // Mysql currently returns the number of rows found
    // this could change in the future.
    if($sql->rowCount() == 1){
        $row                  = $sql->fetch($sql);
        session_start();
        $_SESSION['username'] = $row['username'];
        // $_SESSION['fname']    = $row['first_name'];
        // $_SESSION['lname']    = $row['last_name'];
        $_SESSION['logged']   = TRUE;
        header("Location: index.php"); // Modify to go to the page you would like
        exit;
    }else{
        header("Location: admin_login.php");
        exit;
    }
}else{ //If the form button wasn't submitted go to the index page, or login page
    header("Location: admin_login.php");
    exit;
}
 ?>
