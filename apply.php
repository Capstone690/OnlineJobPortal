<?php
/*
 * File Name: apply.php
 * By: Dipali
 * Date: 03/28/2018
 * Description: apply for job
 */
$isSession=1;
require_once('admin/include/session.php');
require_once("admin/include/config.php");
require_once("admin/include/function.php");
$browserTitle="Job Application";
$changeNavBar=True;
//check if user is logged in
if(isset($_SESSION['login_user_front'])){
     $userEmail=$_SESSION['login_user_front'];
    //check if record exist
    $sql = "SELECT user_account.`id`, `first_name`, `last_name`, `email`, `contact_number`,`user_image`,user_type.user_type_name
        FROM user_account INNER JOIN user_type ON user_account.user_type_id=user_type.id WHERE email='".$userEmail."' AND is_delete='0' AND is_approved='1' AND is_active='1' ";
$result = mysqli_query($db,$sql);
$count = mysqli_num_rows($result);
if($count == 1) {
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    if($row["user_type_name"]=='J'){
       $userIdDb          = $row["id"];
       $name = $row["first_name"]." ".$row["last_name"];
       $email       = $row["email"];
       $contactNo   = $row["contact_number"];
      
         $sqlJobSeeker = "SELECT `user_account_id`, `headline`, `city`, `state`
        FROM job_seeker_profile WHERE user_account_id='".$userIdDb."' ";
            $resultJobSeeker = mysqli_query($db,$sqlJobSeeker);
            $countJobSeeker = mysqli_num_rows($resultJobSeeker);
            if($countJobSeeker == 1) {
                $rowJobSeeker = mysqli_fetch_array($resultJobSeeker,MYSQLI_ASSOC);
                 $headLine =$rowJobSeeker["headline"];
                 $city=$rowJobSeeker["city"];
                 $state=$rowJobSeeker["state"];
                 $location = $city.", ".$state;
            }
    }
    }
}else{
   header("location:login.php");die;

}
if(isset($_GET['jobid']))
{
    // Do something
$jobId=$_GET['jobid'];

$sqlJob    = "SELECT  job_post.id,company.company_name
                  FROM job_post INNER JOIN company ON job_post.company_id=company.id
                  WHERE job_post.id='".$jobId."' AND job_post.is_delete='0' AND job_post.is_active='1'";
    $resultJob = mysqli_query($db,$sqlJob);
    $countJob = mysqli_num_rows($resultJob);
    if($countJob>0){
        $row = mysqli_fetch_array($resultJob,MYSQLI_ASSOC);
        $jobId=$row["id"];
        $companyName = $row["company_name"];
        
        }
    }else{
        //page not found
    }
//submit application
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["apply"])) {
$userAccountId  = $userIdDb;
$job_post_id    = $jobId;
$apply_date     = date('Y-m-d h:i:sa');
$status         = "P";
$email          = isset($_POST["email"])          ? test_input($_POST["email"]) : "";
$contactNo      = isset($_POST["contactNo"])      ? test_input($_POST["contactNo"]) : "";
//check if user has already applied for that job
$sql = "SELECT `id` FROM job_post_activity WHERE user_account_id='".$userAccountId."' AND job_post_id='".$job_post_id."' AND is_removed	='0'";
$result = mysqli_query($db,$sql);
$count = mysqli_num_rows($result);
if($count > 0) {
    $error="You have already applied for this job.";
    $_SESSION['error_message'] = $error;
    header("location:".CURRENT_URL."applied-jobs");die;

}else{
      $quer = "INSERT INTO  job_post_activity (user_account_id,job_post_id,apply_date,status,email,phone ,is_removed) VALUES ('$userAccountId', '$job_post_id', '$apply_date', '$status', '$email','$contactNo','0')";
      $res = mysqli_query($db,$quer);
      //get recent generated id
      $recordId=mysqli_insert_id($db);
      if($res){
          $_SESSION['success_message'] = "Application submitted successfully.";
          header("location:".CURRENT_URL."applied-jobs");die;

        }
}
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include('partials/header.inc.php')?>
    <style>
        body { padding-top: 120px; }

    </style>
 </head>
<body>
<div>
<!--=====================================NAVIGATION : starts here ======================== -->
<?php include('partials/nav.inc.php')?>
<form role="form"  data-toggle="validator" id="frm_application"  name="frm_application" method="POST" enctype="multipart/form-data">
                                    
<div class="container home-heading  ">
    <div style="margin-bottom:20px;" class="card ">
	<h4 class="card-header">
            Apply to <?php echo $companyName;?><br>
        </h4>
        <div class="card-body">
            <p class="card-text">
                <strong><?php echo $name;?></strong><br>
                <span><?php echo $headLine?></span><br>
                <span class="text-muted"><?php echo $location?></span><br>
                <a href="<?php echo CURRENT_URL?>job-seeker">Review Profile</a>
            </p>
            <div class="card-text">
                <div class="form-group">
                  <label  class="control-label">Email</label>
                  <div class="help-block with-errors"></div>
                  <input type="email" class="form-control" id="email" name="email" value="<?php echo $email?>" required="true">
                </div>
                <div class="form-group">
                <label  class="control-label">Phone</label>
                <div class="help-block with-errors"></div>
                 <input type="tel" pattern='\d{3}[\-]\d{3}[\-]\d{4}' title='Phone Number (Format: xxx-xxx-xxxx)' class="form-control" id="contactNo" name="contactNo" value="<?php echo $contactNo?>" required="true">
                 </div>
                                    
            </div>
            <div class="card-text">
                <button type="submit" name="apply" class="btn btn-default btn-primary">Submit Application</button>
            </div>

        </div>
    </div><!-- //.card-->
    
</div>
</form>
<!-- ================== FOOTER ======== -->
<?php include('partials/footer.inc.php')?>
</div> <!-- id:background -->
</body>
<script type="text/javascript" src="<?php echo CURRENT_URL?>public/javascript/script.js"></script>
</html>