<?php

function EncryptionID($id) {
  #  return $accoutID;
  $bit = findChecksumBit($id);
  $encryptID = $id . $bit ;
  return $encryptID;
}

function findChecksumBit($text) {
  $len = strlen($text);
  $sum = 0;

  for ($i = 0; $i < 12; $i++) {
    $sum += (int)$text[$i] * (13 - $i);
  }

  $mod = $sum % 11;
  $bit = 11 - $mod;
  return $bit;
}

function checkLogin() {
  session_start();
  if(!$_SESSION['logged']){
      header("Location: ../admin_login.php");
      exit;
  }
  #echo 'Welcome, '.$_SESSION['username'];
}

function getBaseUrl()
{
    // output: /myproject/index.php
    $currentPath = $_SERVER['PHP_SELF'];

    // output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index )
    $pathInfo = pathinfo($currentPath);

    // output: localhost
    $hostName = $_SERVER['HTTP_HOST'];

    // output: http://
    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

    // return: http://localhost/myproject/
    return $protocol.$hostName.$pathInfo['dirname']."/";
}

function printSidebar(){
  echo "<div id=\"sidebar-wrapper\">
    <ul class=\"sidebar-nav\">
      <li class=\"sidebar-brand\">
        <a href=\"#\">
          Admin
        </a>
      </li>
      <li>
        <a href=\"user\">User</a>
      </li>
      <li>
        <a href=\"log_viewer\">Log</a>
      </li>
      <li>
        <a href=\"#\">Overview</a>
      </li>
      <li>
        <a href=\"#\">Events</a>
      </li>
      <li>
        <a href=\"#\">About</a>
      </li>
      <li>
        <a href=\"#\">Services</a>
      </li>
      <li>
        <a href=\"../admin_logout.php\">Log out</a>
      </li>
    </ul>
  </div>";
}

function printSidebarMainPage(){
  echo "<div id=\"sidebar-wrapper\">
    <ul class=\"sidebar-nav\">
      <li class=\"sidebar-brand\">
        <a href=\"#\">
          Admin
        </a>
      </li>
      <li>
        <a href=\"user\">User</a>
      </li>
      <li>
        <a href=\"#\">Shortcuts</a>
      </li>
      <li>
        <a href=\"#\">Overview</a>
      </li>
      <li>
        <a href=\"#\">Events</a>
      </li>
      <li>
        <a href=\"#\">About</a>
      </li>
      <li>
        <a href=\"#\">Services</a>
      </li>
      <li>
        <a href=\"admin_logout.php\">Log out</a>
      </li>
    </ul>
  </div>";
}
