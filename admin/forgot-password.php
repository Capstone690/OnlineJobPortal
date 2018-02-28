<?php
require_once("include/config.php");
session_start();
$error = "";
$successMsg = "";
$browserTitle = "Forgot password";
if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["forgot-password"])) {
   $myemail = mysqli_real_escape_string($db,$_POST['email']);
   $sql = "SELECT id,is_active,email FROM user_account WHERE email = '$myemail' ";
   $result = mysqli_query($db,$sql);
   $user = mysqli_fetch_array($result,MYSQLI_ASSOC);
   $active = $user['is_active'];
   $count = mysqli_num_rows($result);
   // If result matched $myusername and $mypassword, table row must be 1 row
   if($count == 1) {
       require_once("forgot-password-recovery-mail.php");
   }else {
      $error = "No record found!";
   }
}
?>
<!DOCTYPE html>
<html>
<head>
  <?php include("partials/header.inc.php"); ?>
</head>
<body>
<div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <?php if($error){?>
                    <div class="alert alert-danger fade in">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Error! </strong><?php echo $error?>
                    </div>
                    <?php } ?>
                    <?php if($successMsg){?>
                    <div class="alert alert-success fade in">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <?php echo $successMsg?>
                    </div>
                    <?php } ?>
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $browserTitle;?></h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" name="frmForgot" id="frmForgot" method="post" >
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" required autofocus>
                                </div>
                                <!-- Change this to a button or input when using this as a form 
                                -->
                                <input type="submit" name="forgot-password" id="forgot-password" value="Reset Password"  class="btn  btn-primary ">
                                <a href="login.php" class="btn btn-primary ">Cancel</a>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <?php include("partials/footer.inc.php");?>

</body>
</html>