<?PHP
require_once("include/config.php");
session_start();
$error = "";
$browserTitle="Admin Login";
if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form
      $myemail = mysqli_real_escape_string($db,$_POST['email']);
      $mypassword = mysqli_real_escape_string($db,$_POST['password']);

      $sql = "SELECT id,is_active FROM user_account WHERE email = '$myemail' and password = '$mypassword'";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $active = $row['is_active'];

      $count = mysqli_num_rows($result);

      // If result matched $myusername and $mypassword, table row must be 1 row

      if($count == 1) {
          //remeber me 
          if(!empty($_POST["remember"])) {
                setcookie ("admin_login",$myemail,time()+ (10 * 365 * 24 * 60 * 60));
		setcookie ("admin_password",$mypassword,time()+ (10 * 365 * 24 * 60 * 60));
	} else {
		if(isset($_COOKIE["admin_login"])) {
                    setcookie ("admin_login","");
		}
                if(isset($_COOKIE["admin_password"])) {
                    setcookie ("admin_password","");
		}
	}
         //session_register("myusername");
         $_SESSION['login_user'] = $myemail;

         header("location: index.php");
      }else {
         $error = "Your Login Name or Password is invalid";
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
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" id="login" action="" method="POST">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" value="<?php if(isset($_COOKIE["admin_login"])) { echo $_COOKIE["admin_login"]; } ?>" required autofocus>
                                </div>
                                <div class="form-group last-div">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="<?php if(isset($_COOKIE["admin_password"])) { echo $_COOKIE["admin_password"]; } ?>" required value="">
                                </div>
                                    <div class="form-group"><a href="forgot-password.php">Forgot password?</a></div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me" name="remember" id="remember" <?php if(isset($_COOKIE["admin_login"])) { ?> checked <?php } ?> >Remember Me
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <input type="submit" class="btn btn-lg btn-primary btn-block" value="Login">
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