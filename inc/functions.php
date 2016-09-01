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
