<?php
/*
 * File Name: sign-up.php
 * By: Dipali
 * Date: 03/15/2018
 */
$isSession=1;
require_once('admin/include/session.php');
require_once("admin/include/config.php");
require_once("admin/include/function.php");
$browserTitle="Sign up";
$error="";
if($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST["login"]) )) {
     // username and password sent from form
      $loginEmail = mysqli_real_escape_string($db,$_POST['loginEmail']);
      $loginPassword = mysqli_real_escape_string($db,$_POST['loginPassword']);
      $sql = "SELECT id,user_type_id,is_approved FROM user_account WHERE email = '$loginEmail' and password = '$loginPassword' AND is_active='1' AND is_delete='0'";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $approved = $row['is_approved'];
      $userTypeId = $row['user_type_id'];
      $count = mysqli_num_rows($result);

      // If result matched $myusername and $mypassword, table row must be 1 row

      if($count == 1) {
            if($approved==0){
                $error = "Your account is not approved yet.";
                $_SESSION['error_message'] = $error;
              }else{
                  $_SESSION['login_user_front'] = $loginEmail;
                  if($userTypeId==2){
                   header("location: job-seeker");die;
                  }
                  if($userTypeId==3){
                   header("location: employer");die;
                  }
                
              }

      }else{
         
          $error = "Your Login Name or Password is invalid";
          $_SESSION['error_message'] = $error;

      }
      header("location:sign-up");die;
}
if($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST["reg"]) )) {
      $firstName      = isset($_POST["regFirstName"])      ? test_input($_POST["regFirstName"]) : "";
      $lastName       = isset($_POST["regLastName"])       ? test_input($_POST["regLastName"]) : "";
      $email          = isset($_POST["regEmail"])          ? test_input($_POST["regEmail"]) : "";
      $password       = isset($_POST["regPassword"])       ? test_input($_POST["regPassword"]) : "";
      $contactNo      = isset($_POST["regContact"])      ? test_input($_POST["regContact"]) : "";
      $imgName        = basename($_FILES["fileToUpload"]["name"]);
      $date           = date('Y-m-d h:i:sa');
      $userType      = isset($_POST["userType"])      ? test_input($_POST["userType"]) : "";
      //get user type id
      $sql = "SELECT `id`, `user_type_name` FROM user_type WHERE user_type_name='".$userType."'";
      $result = mysqli_query($db,$sql);
      $count = mysqli_num_rows($result);
      if($count > 0) {
          $userTypeArray = mysqli_fetch_array($result,MYSQLI_ASSOC);
          $usertypeId=$userTypeArray['id'];
      }
      if(!empty($imgName)){
        $target_file = TARGET_DIR ."/".$imgName ;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check === false) {
            $error ="File is not image!";
            $_SESSION['error_message'] = $error;
            header("location:sign-up");die;

        }else{
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $error=  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $_SESSION['error_message'] = $error;
                header("location:sign-up");die;

            }
        }

    }
    if($error===""){
          //check for duplicate record
                $sql = "SELECT id FROM user_account WHERE email='$email' AND is_delete='0'";
                $result = mysqli_query($db,$sql);
                $count = mysqli_num_rows($result);
                if($count == 1) {
                     $error="Email Address already exist.";
                     $_SESSION['error_message'] = $error;
                     header("location:sign-up");die;

                }else{
                        $quer = "INSERT INTO user_account (user_type_id, first_name,last_name,email,password,contact_number,user_image,is_active,is_approved,is_delete) VALUES ('$usertypeId', '$firstName', '$lastName', '$email', '$password','$contactNo','$imgName','1','0','0')";
                        $res = mysqli_query($db,$quer);
                        //get recent generated id
                        $recordId=mysqli_insert_id($db);
                        if($res){
                           if(!empty($imgName)){
                              move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                            }
                            //inssert record into job seeker and employer table
                            if($userType=='J'){//job seeker
                            $querUser = "INSERT INTO job_seeker_profile (user_account_id) VALUES ('$recordId')";
                            $resUser = mysqli_query($db,$querUser);
                            
                            }
                            if($userType=='E'){//wmployer
                            $querUser = "INSERT INTO employer_profile (user_account_id) VALUES ('$recordId')";
                            $resUser = mysqli_query($db,$querUser);
                            
                            }
                             header("location:thank-you"); die;
                            
                        }
                }
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('partials/header.inc.php')?>
</head>
<body>
<div>
<!--=====================================NAVIGATION : starts here ======================== -->
<?php include('partials/nav.inc.php')?>
	
<!--=====================================NAVIGATION : ends here ======================== -->

<!--=====================================FRONT SEARCH : starts here ======================== -->

<div class="jumbotron text-center paralsec">
  <h1>Login or Registration</h1>
  <h5>lorem ipsum lorem ipsum lorem ipsum</h5>
  </div>
<!-- =============================Who We Are ============================= -->
<div class="">

<div class="container home-heading latest-news">
	
	<div class="row">
		<div class="col">
                    <h2 class=" text-center ">Sign In</h2><hr class=" text-center ">
                    <div class=" text-center"><button type="button" class="btn btn-link login-reg btn-lg " id="loginBtn">LOGIN</button><button type="button" class="btn btn-link  btn-lg login-reg" id="regBtn">REGISTRATION</button>
                    </div>
                    
                <!-- LOGIN FORM -->
                <div class="row" id="login">
                    <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="col-md-6 offset-md-3 alert alert-danger ">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']);?>
                            </div>
                           <?php endif ?>
                  
                  <div class="col-md-6 offset-md-3">
                      <form class="login-form" role="form" data-toggle="validator" id="login" action="" method="POST">
                        <div class="form-group ">
                            <label for="inputEmail">Email <span style="color:red"> *</span></label>
                            <div class="help-block with-errors"></div>
                            <input type="email" class="form-control" name="loginEmail" id="inputLoginEmail" placeholder="" required="required">
                        </div>
                        <div class="form-group ">
                            <label for="inputPassword">Password <span style="color:red"> *</span></label>
                            <div class="help-block with-errors"></div>
                            <input type="password" class="form-control" name="loginPassword" id="inputLoginPassword" placeholder="" required="required">
                        </div>
                        <div class="form-group ">
                            <input type="submit" name="login" class="btn btn-primary btn" value="LOGIN">
                             <a href="forgot-password">Forgot password?</a>

                        </div>
                          </form>
                
                    </div>
                  </div>
                <!-- LOGIN FORM ENDS-->
                <!-- REGISTRATION FORM -->
                <div class="row" id="reg">
                  <div class="col-md-6 offset-md-3">
                      <form class="reg-form" role="form" data-toggle="validator" id="reg" action="" method="POST">
                        <div class="form-group ">
                            <label for="inputFirstName">First Name <span style="color:red"> *</span></label>
                            <div class="help-block with-errors"></div>
                            <input type="text" class="form-control" name="regFirstName" id="inputFirstName" placeholder="" required="required">
                        </div>
                        <div class="form-group ">
                            <label for="inputLastName">Last Name <span style="color:red"> *</span></label>
                            <div class="help-block with-errors"></div>
                            <input type="text" class="form-control" name="regLastName" id="inputLastName" placeholder="" required="required">
                        </div>
                        <div class="form-group ">
                            <label for="inputEmail">Email <span style="color:red"> *</span></label>
                            <div class="help-block with-errors"></div>
                            <input type="email" class="form-control" name="regEmail" id="inputEmail" placeholder="" required="required">
                        </div>
                        <div class="form-group ">
                            <label for="inputPassword">Password <span style="color:red"> *</span></label>
                            <div class="help-block with-errors"></div>
                            <input type="password" class="form-control" name="regPassword" id="inputPassword" placeholder="" required="required">
                        </div>
                          <div class="form-group ">
                            <label for="inputConfPassword">Confirm Password <span style="color:red"> *</span></label>
                            <div class="help-block with-errors"></div>
                            <input type="password" class="form-control" name="regConfPassword" id="inputConfPassword" data-match="#inputPassword" data-match-error="Password and confirm password dont match."  placeholder="" required="required">
                        </div>
                        <div class="form-group ">
                            <label for="inputContactNo">Contact Number <span style="color:red"> *</span></label>
                            <div class="help-block with-errors"></div>
                            <input type="tel" pattern="\d{3}[\-]\d{3}[\-]\d{4}" title="Phone Number (Format: xxx-xxx-xxxx)" class="form-control" name="regContact" id="inputContactNo" placeholder="" required="required">
                        </div>
                        <div class="form-group ">
                            <label for="inputProfilePic">User Profile Picture</label>
                            <div class="help-block with-errors"></div>
                            <input style="display: inline" class=" upload-img form-control" type="file" name="fileToUpload" >
                        </div>
                        <div class="form-group ">
                            <div class="help-block with-errors"></div>
                            <label for="inputUserType">I am </label>
                                <input type="radio" class="" id="inputJobSeeker" value="J" checked name="userType">Job Seeker
                                 <input type="radio" class="" id="inputEmployer" value="E" name="userType">Employer
                        </div>

                        <div class="form-group text-center">
                            <input type="submit" name="reg" class="btn btn-primary btn" value="CREATE ACCOUNT">
                           
                        </div>
                          </form>

                    </div>
                  </div>
                <!-- REGISTRATION FORM ENDS-->
                
                </div>
                
                <!-- LOGIN FORM ENDS-->
                </div>
                
        </div>
</div>
    
</div>

<!-- ================== FOOTER ======== -->
<?php include('partials/footer.inc.php')?>
</div> <!-- id:background -->
</body>
<script type="text/javascript">
    var login = document.getElementById("login");
    var reg   = document.getElementById("reg");
    var loginBtn= document.getElementById("loginBtn");
    var regBtn= document.getElementById("regBtn");
  
    //hide reg and show login
    function loginFun(){
        reg.style.display='none';
        login.style.display='block';
        loginBtn.classList.add('tab-active');
        regBtn.classList.remove('tab-active');
    }
    //hide reg and show reg
    function regFun(){
        login.style.display='none';
        reg.style.display='block';
        regBtn.classList.add('tab-active');
        loginBtn.classList.remove('tab-active');
    }
    
    //show login on page load
    loginFun();
    //form validotr3
    
    loginBtn.addEventListener("click", function(){
       loginFun();
    });
    regBtn.addEventListener("click",function(){
        regFun();
    });

    

</script>
<!--<script src="https://code.jquery.com/jquery-3.2.1.min.js" ></script>-->
<script>
$(document).ready(function() {
      //$("#login").validator();
        
});
</script>
<script type="text/javascript" src="<?php echo CURRENT_URL?>public/javascript/script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>

</html>