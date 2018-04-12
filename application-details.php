<?php
/*
 * File Name: view-application.php
 * By: Dipali
 * Date: 04/23/2018
 */
$isSession=1;
require_once('admin/include/session.php');
require_once("admin/include/config.php");
require_once("admin/include/function.php");
$changeNavBar=True;

$browserTitle = "View Application Details";
$error="";
$successMsg="";
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
    if($row["user_type_name"]=='E'){
       $userIdDb          = $row["id"];
        
    }
}
}else{
   header("location:login.php");die;
   
}

if(isset($_GET["applicationId"]) && !empty(trim($_GET["applicationId"]))){
    $applicationId=$_GET["applicationId"];//lower case
    //get jobid and user id from application id
    $sqlAppl = "SELECT job_post_activity.`user_account_id`, job_post_activity.`job_post_id`
        FROM job_post_activity WHERE id='".$applicationId."' AND is_removed='0'";
    $resultAppl = mysqli_query($db,$sqlAppl);
    $countAppl = mysqli_num_rows($resultAppl);
    if($countAppl == 1) {
        $rowAppl = mysqli_fetch_array($resultAppl,MYSQLI_ASSOC);
        $jobId=$rowAppl["job_post_id"];
        $userId=$rowAppl["user_account_id"];
    }
}else{
    //page not found

}
//update application status
if($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST["update_application_status"]))){
    $applStatusId      = isset($_POST["applStatusId"])      ? test_input($_POST["applStatusId"]) : "";
    $applicationId     =isset ($_POST["applicationId"])?test_input($_POST["applicationId"]):0;
//    $jobId     =isset ($_POST["jobId"])?test_input($_POST["jobId"]):0;

    $querUser = "UPDATE job_post_activity set status='" .$applStatusId. "' WHERE id='".$applicationId."'";
    $resUser = mysqli_query($db,$querUser);
     if($resUser){
                //if application is set as joingin for fomalities that means job is filled => update job status accordingly
              if($applStatusId==5){
                  //5=filled
                $querJobs = "UPDATE job_post set job_status='5' WHERE id='".$jobId."'";
                 $resJobs = mysqli_query($db,$querJobs);

              }
              $successMsg ="Status updated successfully";
              $_SESSION['success_message'] = $successMsg;
              header("location:".CURRENT_URL."view-application/".$jobId);

                            }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('partials/header.inc.php')?>
    <style>
        body { padding-top: 120px; }
        p {
    margin-top: 0;
    margin-bottom: 0.4rem;
}
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
</head>
<body>
<div>
<!--=====================================NAVIGATION : starts here ======================== -->
<?php include('partials/nav.inc.php')?>

<!--=====================================NAVIGATION : ends here ======================== -->
<div class="container home-heading ">
    <div class="row">
	<div class="col">
            <div class="container-fluid">
                <br>
  <?php
  //display data
                                         $sql = "SELECT job_post_activity.id,job_post_activity.apply_date,job_post_activity.status,job_post_activity.email as apply_email,job_post_activity.phone as apply_phone,
                                                       user_account.first_name,user_account.last_name,user_account.email as user_email,user_account.contact_number as user_phone,user_account.gender,user_account.user_image,user_account.is_active,
                                                       job_seeker_profile.*
                                                FROM job_post_activity
                                                INNER JOIN user_account ON job_post_activity.user_account_id=user_account.id
                                                INNER JOIN job_seeker_profile ON job_post_activity.user_account_id=job_seeker_profile.user_account_id
                                                WHERE job_post_activity.job_post_id='".$jobId."' AND
                                                      job_post_activity.is_removed='0' AND
                                                      user_account.is_delete='0'AND
                                                      user_account.is_approved='1' AND
                                                       job_post_activity.id='".$applicationId."'";
                                        $result = mysqli_query($db,$sql);
                                        $count = mysqli_num_rows($result);
                                        $rowNo=1;
                                        if($count > 0) {
                                               $content = mysqli_fetch_array($result,MYSQLI_ASSOC);
                                                $recordID  = $content["id"];
                                                $name      = $content["first_name"]." ".$content["last_name"];
                                                $email     = $content["apply_email"]?$content["apply_email"]:$content["user_email"];
                                                $phone     = $content["apply_phone"]?$content["apply_phone"]:$content["user_phone"];
                                                $media       = $content["user_image"];
                                                $gender     =$content["gender"]=='F'? "Female":($content["gender"]=='M'?"Male":"-");
                                                $applyDate  = $content["apply_date"];
                                                //$jobStatus  = get_application_status($content["status"]);
                                                $applStatusId = $content["status"];
                                                $headLine =$content["headline"];
                                                $skills =$content["skills"];
                                                $webSiteUrl =$content["website_url"];
                                                $streetAddress1 =$content["street_address1"];
                                                $streetAddress2 =$content["street_address2"];
                                                $city=$content["city"];
                                                $state=$content["state"];
                                                $country	=$content["country"];
                                                $zip=$content["zip"];
                                                $jobSeekerId= $content["user_account_id"];
                                                $userStatus=$content["is_active"];
                                                $class     = ($rowNo%2==0) ? "even" : "odd";
   ?>
     <?php if($userStatus==0){?>
    <div class="alert alert-danger fade in">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Error! User status is inactive.</strong>
                    </div>
     <?php }?>
    <div class="row">
        <div class="col-sm-10">
             <h1 class=""><?php echo $name?></h1>

          <h4><?php echo $headLine?></h4>
        <br>
        </div><!-- close col-sm-10-->
<?php if($media){?>

      <div class="col-sm-2">
          <a href="#" class="pull-right">
              <img title="profile image" class="img-circle img-responsive" src="<?php echo TARGET_DIR_FRONT."/".$media?>">
          </a>

        </div>
<?php }//close if(media)?>
    </div>
  <br>
    <div class="row">
        <div class="col-sm-3">
            <!--left col-->
            <ul class="list-group mb-3">
                <li class="list-group-item text-muted" contenteditable="false">Profile</li>
                <li class="list-group-item text-right"><span class="pull-left"><strong class="">Email</strong></span><a href="#"><?php echo $email?></a></li>
                <li class="list-group-item text-right"><span class="pull-left"><strong class="">Phone No</strong></span> <?php echo $phone?></li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong class="">Gender</strong></span> <?php echo $gender;?>
                        </li>
            </ul>
           <div class="card mb-3">
             <div class="card-header">Current Location

                </div>
                <div class="card-body">
                    <?php if($streetAddress1!=""){
                        echo $streetAddress1."<br>";
                    }?>
                    <?php if($streetAddress2!=""){
                        echo $streetAddress2."<br>";
                    }?>
                    <?php if($city!=""){
                        echo $city.",".$state." - ".$zip;
                    }?>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Website <i class="fa fa-link fa-1x"></i>

                </div>
                <div class="card-body">
                    <?php if($webSiteUrl!="") {?>
                    <a href="<?php echo $webSiteUrl?>" class=""><?php echo $webSiteUrl;?></a>
                    <?php }else{
                        echo "-";
                    }?>
                </div>
            </div>

        </div>
        <!--/col-3-->
        <div class="col-sm-9" style="" contenteditable="false">
            <div class="card mb-3">
                <div class="card-header">Skills</div>
                <div class="card-body"> <?php echo $skills?>

                </div>
            </div>
            <div class="card target mb-3">
                <div class="card-header" contenteditable="false">Experience</div>
                <div class="card-body">
                  <div class="row">
				<div class="col-md-12">
                                    <?php
                                    $sqlJobSeekerExperience = "SELECT `id`, `user_account_id`, `is_current_job`, `start_date`, `end_date`, `job_title`, `company_name`, `job_location`, `description`
                                        FROM experience_detail WHERE user_account_id='".$jobSeekerId."' ";
                                            $resultExperience = mysqli_query($db,$sqlJobSeekerExperience);
                                            $counter = mysqli_num_rows($resultExperience);
                                            if($counter<=0){
                                                echo "-";
                                            }else{
                                             while($rowJobSeekerExperience = mysqli_fetch_array($resultExperience,MYSQLI_ASSOC)){
                                                         $expid   = $rowJobSeekerExperience['id'];
                                                         $expjobTitle   = $rowJobSeekerExperience['job_title'];
                                                         $expcompanyName= $rowJobSeekerExperience['company_name'];
                                                         $expjobLocation= $rowJobSeekerExperience['job_location'];
                                                         $expstartDate=  date('F, Y', strtotime($rowJobSeekerExperience['start_date']));
                                                         $expendDate= date('F, Y', strtotime($rowJobSeekerExperience['end_date']));
                                                         $expDescription= $rowJobSeekerExperience['description'];
                                                         $expisCurrenJob= $rowJobSeekerExperience['is_current_job'];
                                                         $expendDate=($expisCurrenJob==1)?"Present":$expendDate;
                                    ?>
					<div class="img-thumbnail">
						<div class="caption">
							<h3>
								<?php echo $expjobTitle;?>
							</h3>
							<p>
								<?php echo $expcompanyName.", ".$expjobLocation;?>
							</p>
							<p class="small">
							    <?php echo $expstartDate;?> - <?php echo $expendDate;?>
							</p>
							<p>
							    <?php echo $expDescription;?>
							</p>
						</div>
					</div>
                                    <?php          } //close if
                                            }//close whiel
                                   ?>
				</div>


            </div>

        </div>

    </div>
           <div class="card mb-3">
                <div class="card-header">Education</div>
                <div class="card-body">
                <div class="row">
                    	<div class="col-md-12">
                            <?php
                                $sqlJobSeekerEducation = "SELECT `id`, `user_account_id`, `degree_name`, `major`, `institute_university_name`, `start_date`, `completion_date`, `grade`, `description`
                                        FROM education_detail WHERE user_account_id='".$jobSeekerId."' ";
                                            $resultEducation = mysqli_query($db,$sqlJobSeekerEducation);
                                            $eduCounter = mysqli_num_rows($resultEducation);
                                            if($eduCounter<=0){
                                                echo "-";
                                            }else{

                                            while($rowJobSeekerEducation = mysqli_fetch_array($resultEducation,MYSQLI_ASSOC)){
                                                         $eduId = $rowJobSeekerEducation["id"];
                                                         $eduSchoolName = $rowJobSeekerEducation["institute_university_name"];
                                                         $eduDegreeName =$rowJobSeekerEducation["degree_name"];
                                                         $eduMajor = $rowJobSeekerEducation["major"];
                                                         $eduGrade = $rowJobSeekerEducation["grade"];
                                                         $eduStartDate= date('F, Y', strtotime($rowJobSeekerEducation['start_date']));
                                                         $eduEndDate=  date('F, Y', strtotime($rowJobSeekerEducation['completion_date']));;
                                                         $eduDescription= $rowJobSeekerEducation['description'];

                            ?>
					<div class="img-thumbnail">
						<div class="caption">
							<h3>
								<?php echo $eduSchoolName?>
							</h3>
							<p>
                                                                <?php echo $eduDegreeName." in ".$eduMajor.", GPA:".$eduGrade?>
							</p>
							<p class="small">
							   <?php echo $eduStartDate." - ".$eduEndDate;?>
							</p>
							<p>
							   <?php echo $eduDescription?>
							</p>
						</div>
					</div>
                                        <?php }//close if
                                        }//close while
                                        ?>
				</div>
				<!-- //col-md-12-->

                </div>

                </div>
</div></div>


            <div id="push"></div>
        </div>
  <!-- update application stuas-->
  <div class="row">
                <div class="col-md-12">
                     <form role="form" class="form-inline" data-toggle="validator" id="frm_add_update_job"  name="update_application_status" method="POST" enctype="multipart/form-data">

                                   <div class="">
                                            <label><h3>Update Application Status</h3></label>
                                            <div class="help-block with-errors"></div>
                                            <select style="width:150px;" class=" form-control" id="applStatusId" name="applStatusId" required="true">
                                                <?php echo get_appl_status_options($applStatusId);?>
                                            </select>&nbsp;&nbsp;
                                            <input type="hidden" name="applicationId" value="<?php echo $recordID?>">
                                            <input type="submit" name="update_application_status" value="Update" class="btn btn-primary">
                                            <a href="<?php echo CURRENT_URL."view-application/".$jobId; ?>" class="btn btn-primary">Back</a>
                                    </div>

                    </form>
                    <br><br>
                </div>
            </div>
        <?php }//close if?>

            </div>
                
</div> <!-- close col-->
</div> <!-- //close  row-->
    </div> <!-- close container home-heading latest-news -->

</div>

    <?php include("partials/footer.inc.php");?>
    <!-- DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    
<script>
$(document).ready(function() {
    $('#datatables-example').DataTable(
    {
         responsive: true,
         stateSave: true,
         "aoColumns": [null,null,null,null,{ "bSortable": false },{ "bSortable": false }]
           
    }
    );
   $("#dataTables-example_filter").css("text-align","right");
   $("#dataTables-example_paginate").css("text-align","right");
   
} );
</script>
</body>
</html>