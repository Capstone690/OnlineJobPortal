<?php
define("TARGET_DIR", "uploads");
define("TARGET_DIR_FRONT", "../uploads");

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
function letter_space($name){
    return preg_match("/^[a-zA-Z ]*$/",$name);
}
?>