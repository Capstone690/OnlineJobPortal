<?php
require_once("include/config.php");
$browserTitle="Reset Password";
$error="";
$successMsg="";
if(isset($_POST["reset_password"])) {
    $newPassword = $_POST["newPassword"];
    $confrimPassword = $_POST["confirmPassword"];
    if($newPassword != $_POST["confirmPassword"]){
         $error = "Password and confirm password does not match!";
    }else{
           $id = $_GET['id'];
           $sql = "SELECT id,is_active,email FROM user_account WHERE id = '$id' AND user_type_id='1'";
           $result = mysqli_query($db,$sql);
           $user = mysqli_fetch_array($result,MYSQLI_ASSOC);
           $active = $user['is_active'];
           $count = mysqli_num_rows($result);
           // If result matched $myusername and $mypassword, table row must be 1 row
           if($count == 1) {
               mysqli_query($db,"UPDATE user_account set password='" .$newPassword. "' WHERE id='$id' AND user_type_id='1'");
               $successMsg ="Password changed successfully!<br><a href='login.php'>click here to login</a>";
               //$successMsg += "<a href='login.php'>login</a>";
               //header('Location: '.$_SERVER['PHP_SELF']);
               //die;
           }else {
              $error = "No record found!";
           }
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
                        <h3 class="panel-title">Reset Password</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" name="frmReset" method="POST"  >
                            <fieldset>
                                <div class="form-group">
                                            <label>New Password</label>
                                            <input class="form-control" type="password" name="newPassword" required >
                                    </div>
                                    <div class="form-group">
                                            <label>Re-enter new Password</label>
                                            <input class="form-control" type="password" name="confirmPassword" required >
                                    </div>
                                    
                                <!-- Change this to a button or input when using this as a form -->
                                <input type="submit" name="reset_password" id="reset-password" class="btn btn-lg btn-primary btn-block" value="Submit">
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