<?php
if(!$_SESSION['logged']){
    session_start();
    session_unset($_SESSION['logged']);
    session_destroy($_SESSION['logged']);
    $_SESSION['logged'] = FALSE;
    header("Location: admin_login.php");
    exit;
}
