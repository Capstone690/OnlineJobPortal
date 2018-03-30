<?php
/*
 * File Name: employer.php
 * By: Dipali
 * Date: 03/17/2018
 */
$isSession=1;
require_once('admin/include/session.php');
require_once("admin/include/config.php");
require_once("admin/include/function.php");
$browserTitle="Employer Profile";
$changeNavBar=True;
$error="";
$successMsg="";
$firstName   = "";
$lastName    = "";
$email       = "";
$contactNo = "";
$media     = "";
$companyId="";//only for Employer
$jobTitle="";//only for Employer
//check if user is logged in
if(isset($_SESSION['login_user_front'])){
     //get login email
    $userEmail=$_SESSION['login_user_front'];
    //check if record exist
    $sql = "SELECT user_account.`id`, `first_name`, `last_name`, `email`, `contact_number`,`user_image`,user_type.user_type_name
        FROM user_account INNER JOIN user_type ON user_account.user_type_id=user_type.id WHERE email='".$userEmail."' AND is_delete='0' AND is_approved='1' AND is_active='1' ";
$result = mysqli_query($db,$sql);
$count = mysqli_num_rows($result);
if($count == 1) {
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    if($row["user_type_name"]=='E'){
        $firstName   = $row["first_name"];
        $lastName    = $row["last_name"];
        $email       = $row["email"];
        $contactNo   = $row["contact_number"];
        $media       = $row["user_image"];
        $id          = $row["id"];
        $sqlEmployer = "SELECT `id`, `job_title`, `company_id`
        FROM employer_profile WHERE user_account_id='".$id."' ";
            $resultEmployer = mysqli_query($db,$sqlEmployer);
            $countEmployer = mysqli_num_rows($resultEmployer);
            if($countEmployer == 1) {
                $rowEmployer = mysqli_fetch_array($resultEmployer,MYSQLI_ASSOC);
                $jobTitle   = $rowEmployer["job_title"];
                $companyId   = $rowEmployer["company_id"];
                }
    }else{
        $error="Invalid User";
        header("location:error.php");
    }
    
}
   }else{
      header("location:login.php");
   }
//update profile
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit"])) {
$firstName      = isset($_POST["regFirstName"])      ? test_input($_POST["regFirstName"]) : "";
$lastName       = isset($_POST["regLastName"])       ? test_input($_POST["regLastName"]) : "";
$email          = isset($_POST["regEmail"])          ? test_input($_POST["regEmail"]) : "";
$contactNo      = isset($_POST["regContact"])      ? test_input($_POST["regContact"]) : "";
$imgName        = basename($_FILES["fileToUpload"]["name"]);

$date           = date('Y-m-d h:i:sa');
$jobTitle       = isset($_POST["jobTitle"])       ? test_input($_POST["jobTitle"]) : "";
$companyId      = isset($_POST["companyId"])      ? ($_POST["companyId"]) : "";//only for employer
    if(!empty($imgName)){
        $target_file = TARGET_DIR ."/".$imgName ;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check === false) {
            $error ="File is not image!";
        }else{
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $error=  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        }

    }
    if($error===""){
         $sql = "SELECT user_account.`id`,user_type.user_type_name
        FROM user_account INNER JOIN user_type ON user_account.user_type_id=user_type.id WHERE email='".$email."' AND is_delete='0' AND is_approved='1' AND is_active='1' ";
        $result = mysqli_query($db,$sql);
        $count = mysqli_num_rows($result);
        if($count == 1) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            if($row["user_type_name"]=='E'){
                $id=$row["id"];
                $quer = "UPDATE user_account set first_name='" .$firstName. "', last_name='" .$lastName. "',  contact_number='" .$contactNo. "'";
                if(!empty($imgName)){
                          $quer .= " ,  user_image='" .$imgName. "' ";
                          move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                        }
                        $quer .= " WHERE id='".$id."' ";
                        $res = mysqli_query($db,$quer);
                        if($res){

                            $querUser = "UPDATE employer_profile set job_title='" .$jobTitle. "', company_id='" .$companyId. "' WHERE user_account_id='".$id."'";
                            $resUser = mysqli_query($db,$querUser);
                             if($querUser){
                                   $successMsg ="Record updated successfully";
                                   $_SESSION['success_message'] = $successMsg;
                                   header("location:employer");die;

                            }
                        }

            }else{
                    $error = "Record not found";
                    $_SESSION['error_message'] = $error;
                    header("location:employer");die;
               }
        }
        
    }else{
        $_SESSION['error_message'] = $error;
        header("location:employer");die;
    }

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
                    <h2 class=" text-center ">Update Profile</h2><hr class=" text-center ">
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
                      <form class="login-form" role="form" data-toggle="validator" id="updateProfile" action="" enctype="multipart/form-data" method="POST">
                         <div class="form-group ">
                            <label for="inputFirstName">First Name <span style="color:red"> *</span></label>
                            <div class="help-block with-errors"></div>
                            <input value="<?php echo $firstName;?>" type="text" class="form-control" name="regFirstName" id="inputFirstName" placeholder="" required="required">
                        </div>
                        <div class="form-group ">
                            <label for="inputLastName">Last Name <span style="color:red"> *</span></label>
                            <div class="help-block with-errors"></div>
                            <input value="<?php echo $lastName;?>" type="text" class="form-control" name="regLastName" id="inputLastName" placeholder="" required="required">
                        </div>
                       
                          <div class="form-group ">
                            <label for="inputEmail">Email <span style="color:red"> *</span></label>
                            <div class="help-block with-errors"></div>
                            <input readonly="readonly" value="<?php echo $email;?>" type="email" class="form-control" name="regEmail" id="inputLoginEmail" placeholder="" required="required">
                        </div>
                           <div class="form-group ">
                            <label for="inputContactNo">Contact Number <span style="color:red"> *</span></label>
                            <div class="help-block with-errors"></div>
                            <input value="<?php echo $contactNo;?>" type="tel" pattern="\d{3}[\-]\d{3}[\-]\d{4}" title="Phone Number (Format: xxx-xxx-xxxx)" class="form-control" name="regContact" id="inputContactNo" placeholder="" required="required">
                        </div>
                       <div class="form-group" style="">
                        <label  for="jonTitle">Job Title<span style="color:red"> *</span></label>
                        <div class="help-block with-errors"></div>
                        <input type="text" class="form-control" id="jobTitle" name="jobTitle" value="<?php echo $jobTitle;?>" required="true">
                       </div>
                                   <!-- employer-->
                       <div class="form-group" style="">
                       <label  for="companyId">Company Name <span style="color:red"> *</span></label>
                       <div class="help-block with-errors"></div>
                        <select class="business form-control" id="companyId" name="companyId" required="true">
                                <?php echo get_company_options($companyId);?>
                        </select>
                       </div>
                       <div class="form-group">
                            <label>User Profile Picture </label>
                            <div class="help-block with-errors"></div>
                            <div id="success"></div>
                            <div id="delete_pic">
                            <?php if($media){?>
                                 <div id="edit_profile_pic">
                                    <img width="100" height="100" src="<?php echo CURRENT_URL.TARGET_DIR."/".$media?>" />
                                 </div>
                           <?php } ?>
                           </div>
                           <?php if($media){?>
                           <button type="button" name="delete" class="btn-danger delete" id="<?php echo $id ?>">Remove Image</button>
                           <?php }?>
                           <input style="display: inline" class=" upload-img form-control" type="file" name="fileToUpload" >

                       </div>
                       <div class="form-group">
                       <input type="submit" name="edit" class="btn btn-primary btn" value="UPDATE">

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
      //$("#login").validator();
        //image delete
     $(document).on('click', '.delete', function(){
           var user_id = $(this).attr("id");
             var action = "delete_profile_pic";

         if(confirm("Are you sure you want to remove this image from database?"))
          {
           // $( "#frm_add_update_news" ).submit();
              $.ajax({
                  url: 'admin/include/action.php',
                  type: 'post',
                  data:{user_id:user_id, action:action},
                  success: function(data, status) {
                   // alert(data);
                     $("#delete_pic").html('');
                     $(".delete").css("display","none");
                     $('#success').html("<div class='alert alert-success'><a href='#' class='close' data-dismiss='alert'>&times;</a>Image deleted Successfully</div>");

                  },
                  error: function(xhr, desc, err) {
                 //  alert(err);
                  }
                }); // end ajax call

          }else
          {
           return false;
          }
     });
     
});
</script>
<script type="text/javascript" src="<?php echo CURRENT_URL?>public/javascript/script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>

</html>