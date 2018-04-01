<?php

   require_once('config.php');
   $login_session="";
   
   session_start();

   $user_check = isset($_SESSION['login_user'])?$_SESSION['login_user']:'';
   $ses_sql = mysqli_query($db,"select email from user_account where email = '$user_check' ");

   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);

   $login_session = $row['email'];
   //check if url has admin word
   $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
   if(strpos($url,'admin') !== false && !isset($_SESSION['login_user'])){
      header("location:login.php");
   }
   if( strpos($url,'sign-up') == false  && !isset($_SESSION['login_user_front'])){
       if( $isSession==1)
            header("location:sign-up");
   }
?>