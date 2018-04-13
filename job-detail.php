<?php
/*
 * File Name: job-detail.php
 * By: Dipali
 * Date: 03/27/2018
 * Description: job-detail details
 */
$isSession=1;
require_once('admin/include/session.php');
require_once("admin/include/config.php");
require_once("admin/include/function.php");
$browserTitle="Job Details";
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

    }
    }
}else{
   header("location:login.php");die;

}
if(isset($_GET['title']))
{
    // Do something
$jobId=$_GET['title'];

$sqlJob    = "SELECT  job_post.*,company.company_name,company.business_stream_id
                  FROM job_post INNER JOIN company ON job_post.company_id=company.id
                  WHERE job_post.id='".$jobId."' AND job_post.is_delete='0' AND job_post.is_active='1'";
    $resultJob = mysqli_query($db,$sqlJob);
    $countJob = mysqli_num_rows($resultJob);
    if($countJob>0){
        $row = mysqli_fetch_array($resultJob,MYSQLI_ASSOC);
        $jobId=$row["id"];
        $jobTitle   = $row["job_title"];
        $companyName = $row["company_name"];
        $businessStreamId = $row["business_stream_id"];
        $companyUrl = CURRENT_URL.'company/' . urlencode(strtolower($companyName));

        $postedDate =$row["posted_date"];
        $daysPassed=timeago($postedDate);
                            
        $jobTypeId    = $row["job_type_id"];
        $jobType = get_job_type($jobTypeId);
        $jobStatusId= $row["job_status"];
        $jobStatus = get_job_status($jobStatusId);

        $jobFunction=$row["job_function"]?$row["job_function"]:'-';
        $jobSkills=$row["job_skills"];
        $locStreetAddress1 =$row["loc_street_address1"];
        $locStreetAddress2 =$row["loc_street_address2"];
        $locCity=get_city($row["loc_city"]);
        $locState=get_state($row["loc_state"]);
        $locCountry	=$row["loc_country"];
        $locZip=$row["loc_zip"];
        $jobLocation = $locCity.", ".$locState;
        $address="";
        if($locStreetAddress1){$address .=$locStreetAddress1."<br>";}
        if($locStreetAddress2){$address .=$locStreetAddress2."<br>";}
        $address .=$jobLocation;
        $address .="<br>".$locCountry;
        $address .= "<br>".$locZip;
        
        $jobDescription=$row["job_description"];
        $jobPostedBy = $row["posted_by_id"];
        $jobApplicationUrl=CURRENT_URL.'apply/'.$jobId;

        }
    }else{
        //page not found
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
<div class="container home-heading  ">
    <div style="margin-bottom:20px;" class="card ">
	<h4 class="card-header">
            <?php echo $jobTitle;?><br>
            <span style="font-size:15px;" class="text-muted"><a href="<?php echo $companyUrl?>"  target="_blank"><?php echo $companyName?></a>&nbsp;<?php echo $jobLocation?><br>Posted: <?php echo $daysPassed?></span>
            <br><br>
            <?php
            $appliedDate= has_applied($userIdDb,$jobId);
            if($appliedDate){
                ?>
         <a class="btn btn-secondary  btn-sm  active" href="#" role="button">Applied <?php echo $appliedDate;?></a>
        
            <?php
            }else{
                ?>
                <a class="btn btn-primary  btn-sm  active" href="<?php echo $jobApplicationUrl;?>" role="button">Apply</a>
        
            <?php
            }?>
        </h4>
        <div class="card-body">
            <h5 class="card-title">Job Description</h5>
            <div class="row">
            <div class="col-10">
                <p class="card-text"><?php echo $jobDescription?></p>
                <p><strong>Skills: </strong><br><?php echo $jobSkills?></p>
            </div>
            <div class="col-2">
                <strong>Job Status</strong>
                <p><?php echo $jobStatus?></p>
                <strong>Employment Type</strong>
                <p><?php echo $jobType?></p>
                <strong>Job Function</strong>
                <p><?php echo $jobFunction?></p>
                <strong>Industry</strong>
                <p><?php echo get_business($businessStreamId)?></p>
                <strong>Address</strong>
                <p><?php echo $address?></p>
            </div>
                </div>
        </div>
    </div><!-- //.card-->
    
</div>

<!-- ================== FOOTER ======== -->
<?php include('partials/footer.inc.php')?>
</div> <!-- id:background -->
</body>
<script type="text/javascript" src="<?php echo CURRENT_URL?>public/javascript/script.js"></script>
</html>