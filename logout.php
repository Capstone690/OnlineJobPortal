<?php
   session_start();

   if(isset($_SESSION['login_user_front'])) {
       $_SESSION['login_user_front']="";
      header("Location: sign-up");
   }
?>