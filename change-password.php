<?php
/*
 * File Name: change-password.php
 * By: Dipali
 * Date: 03/20/2018
 */
$isSession=1;
require_once('admin/include/session.php');
require_once("admin/include/config.php");
require_once("admin/include/function.php");
$browserTitle="Change Password";
$changeNavBar=True;
$error="";
$successMsg="";

$oldPassword   = "";
$newPassword    = "";
$confirmPassword       = "";

//update profile
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["change_password"])) {
$oldPassword   =  isset($_POST["oldPassword"])      ? test_input($_POST["oldPassword"]) : "";
$newPassword    = isset($_POST["newPassword"])      ? test_input($_POST["newPassword"]) : "";
$confirmPassword  = isset($_POST["confPassword"])      ? test_input($_POST["confPassword"]) : "";
$userInfo = isUserLoggedIn();
$email =$userInfo["email"];

$sql = "SELECT id,is_active,email,password FROM user_account WHERE email='".$email."' AND password='".$oldPassword."'";
$result = mysqli_query($db,$sql);
$count = mysqli_num_rows($result);
if($count == 1) {
        $user = mysqli_fetch_array($result,MYSQLI_ASSOC);
        mysqli_query($db,"UPDATE user_account set password='" .$newPassword. "' WHERE id='".$user["id"]."'");
        $successMsg ="Password changed successfully";
        $_SESSION['success_message'] = $successMsg;

        
}else {
        $error = "Old password is incorrect!";
        $_SESSION['error_message'] = $error;
             
}
header("location:change-password");die;

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('partials/header.inc.php')?>
    <style>
        body { padding-top: 70px; }

    </style>
</head>
<body>
<div>
<!--=====================================NAVIGATION : starts here ======================== -->
<?php include('partials/nav.inc.php')?>
	
<!--=====================================NAVIGATION : ends here ======================== -->

<!--=====================================FRONT SEARCH : starts here ======================== -->

<!-- =============================Who We Are ============================= -->
    <main role="main" class="container-fluid">

<div class="container home-heading latest-news">
	
	<div class="row">
		<div class="col">
                    <h2 class=" text-center ">Change Password</h2><hr class=" text-center ">
                    <?php if (isset($_SESSION['success_message'])): ?>
                            
                            <div class="col-md-6 offset-md-3 alert success alert-success ">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']);?>
                            </div>
                           <?php endif ?>

                    <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="col-md-6 offset-md-3 alert alert-danger ">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']);?>
                            </div>
                           <?php endif ?>
                  
                   <div class="col-md-6 offset-md-3">
                      <form class="change-password-form" role="form" data-toggle="validator" id="changePassword" action="" enctype="multipart/form-data" method="POST">
                       <div class="form-group ">
                            <label for="inputPassword">Old Password <span style="color:red"> *</span></label>
                            <div class="help-block with-errors"></div>
                            <input type="password" class="form-control" name="oldPassword" id="inputOldPassword" placeholder="" required="required">
                        </div>
                       
                        <div class="form-group ">
                            <label for="inputPassword">New Password <span style="color:red"> *</span></label>
                            <div class="help-block with-errors"></div>
                            <input type="password" class="form-control" name="newPassword" id="inputPassword" placeholder="" required="required">
                        </div>
                          <div class="form-group ">
                            <label for="inputConfPassword">Confirm Password <span style="color:red"> *</span></label>
                            <div class="help-block with-errors"></div>
                            <input type="password" class="form-control" name="confPassword" id="inputConfPassword" data-match="#inputPassword" data-match-error="Password and confirm password dont match."  placeholder="" required="required">
                        </div>
                          
                          <div class="form-group">
                       <input type="submit" name="change_password" class="btn btn-primary btn" value="UPDATE">

                       </div>
                          </form>
                   </div>
                </div>
                
                <!-- LOGIN FORM ENDS-->
                </div>
                
        </div>
</div>
    
</main>

<!-- ================== FOOTER ======== -->
<?php include('partials/footer.inc.php')?>
</div> <!-- id:background -->
</body>
<script>
$(document).ready(function() {
      
     
});
</script>
<script type="text/javascript" src="<?php echo CURRENT_URL?>public/javascript/script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>

</html>