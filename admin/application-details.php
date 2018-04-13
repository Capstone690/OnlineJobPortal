<?php
/*
 * File Name: application-details.php
 * By: Dipali
 * Date: 04/09/2018
 */
 $isSession=0;

require_once('include/session.php');
require_once("include/config.php");
require_once("include/function.php");
//check user type
if(isset($_GET["userid"]) && !empty(trim($_GET["userid"]))){
    $userId=$_GET["userid"];//lower case
}else{
    //page not found

}
if(isset($_GET["jobId"]) && !empty(trim($_GET["jobId"]))){
    $jobId=$_GET["jobId"];//lower case
}else{
    //page not found

}
if(isset($_GET["applicationId"]) && !empty(trim($_GET["applicationId"]))){
    $applicationId=$_GET["applicationId"];//lower case
}else{
    //page not found

}
$browserTitle = "View Application";
$sideBarActive=6;
$error="";
$successMsg="";
//get user type id
$sql = "SELECT user_account.`id`, user_account.`first_name`,user_account.last_name, user_type.user_type_name FROM user_account INNER JOIN user_type ON user_account.user_type_id = user_type.id WHERE user_account.id='".$userId."' AND user_type.user_type_name='E' ";
$result = mysqli_query($db,$sql);
$count = mysqli_num_rows($result);
if($count > 0) {
    $userArray = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $userIdDb=$userArray['id'];
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
              header("location:view-application.php?userid=".$userIdDb."&jobId=".$jobId);

                            }
  
}
?>
<!DOCTYPE html>
<html>
<head>
<?php include("partials/header.inc.php"); ?>
</head>
<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <?php include("partials/nav.inc.php");?>
            <?php include("partials/sidebar.inc.php");?>
               </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
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
                                                $highestDegree=$content["highest_degree"];
                                                $major = $content["highest_major"];
                                                $yearOfExp = $content["year_of_exp"];
                 
                                                $streetAddress1 =$content["street_address1"];
                                                $streetAddress2 =$content["street_address2"];
                                                $city=get_city($content["city"]);
                                                $state=get_state($content["state"]);
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
            <ul class="list-group">
                <li class="list-group-item text-muted" contenteditable="false">Profile</li>
                <li class="list-group-item text-right"><span class="pull-left"><strong class="">Email</strong></span><a href="#"><?php echo $email?></a></li>
                <li class="list-group-item text-right"><span class="pull-left"><strong class="">Phone No</strong></span> <?php echo $phone?></li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong class="">Gender</strong></span> <?php echo $gender;?>
                        </li>
            </ul>
           <div class="panel panel-default">
             <div class="panel-heading">Current Location

                </div>
                <div class="panel-body">
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
            <div class="panel panel-default">
                <div class="panel-heading">Website <i class="fa fa-link fa-1x"></i>

                </div>
                <div class="panel-body">
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
            <div class="panel panel-default">
                <div class="panel-heading">Skills</div>
                <div class="panel-body"> <?php echo $skills?>

                </div>
            </div>
            <div class="panel panel-default target">
                <div class="panel-heading" contenteditable="false">Experience</div>
                <div class="panel-body">
                  <div class="row">
				<div class="col-md-12">
                                    <div style="margin:10px 0"><strong>
                                        <?php
                                        if($yearOfExp==0){
                                            echo "No Previous experience";
                                        }else if($yearOfExp==1){
                                            echo $yearOfExp." year of Experience<br>";
                                        }else if($yearOfExp>1){
                                            echo $yearOfExp." years of Experience<br>";
                                        }
                                        ?>
                                    </strong></div>
                                    <?php
                                    $sqlJobSeekerExperience = "SELECT `id`, `user_account_id`, `is_current_job`, `start_date`, `end_date`, `job_title`, `company_name`, `job_location`, `description`
                                        FROM experience_detail WHERE user_account_id='".$jobSeekerId."' ";
                                            $resultExperience = mysqli_query($db,$sqlJobSeekerExperience);
                                            $counter = mysqli_num_rows($resultExperience);
                                            if($counter<=0){
                                               // echo "-";
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
					<div class="thumbnail">
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
           <div class="panel panel-default">
                <div class="panel-heading">Education</div>
                <div class="panel-body">
                <div class="row">
                    	<div class="col-md-12">
                             <div style="margin:10px 0">
                                        <?php
                                        echo "<strong>Highest degree earned:</strong> ".$highestDegree." <strong>Major:</strong> ".$major;
                                        ?>
                                    </div>
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
					<div class="thumbnail">
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

                                   <div class="form-group">
                                            <label  class="control-label"><h1>Update Application Status</h1></label>
                                            <div class="help-block with-errors"></div>
                                            <select style="width:150px;" class=" form-control" id="applStatusId" name="applStatusId" required="true">
                                                <?php echo get_appl_status_options($applStatusId);?>
                                            </select>
                                            <input type="hidden" name="applicationId" value="<?php echo $recordID?>">
                                            <input type="submit" name="update_application_status" value="Update" class="btn btn-primary">
                                    </div>

                    </form>
                    <br><br>
                </div>
            </div>
        <?php }//close if?>
            
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
<?php include("partials/footer.inc.php");?>
    <!-- DataTables JavaScript -->
    <script>
    $(document).ready(function() {
        

    });
    </script>

</body>
</html>