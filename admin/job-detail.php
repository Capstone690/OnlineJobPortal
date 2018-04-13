<?php
/*
 * File Name: job-details.php
 * By: Dipali
 * Date: 04/10/2018
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

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $applicationId=$_GET["id"];//lower case
}else{
    //page not found

}
$browserTitle = "View Job Details";
$sideBarActive=7;
$error="";
$successMsg="";
//get user type id
$sql = "SELECT user_account.`id`, user_account.`first_name`,user_account.last_name, user_type.user_type_name FROM user_account INNER JOIN user_type ON user_account.user_type_id = user_type.id WHERE user_account.id='".$userId."' AND user_type.user_type_name='J' ";
$result = mysqli_query($db,$sql);
$count = mysqli_num_rows($result);
if($count > 0) {
    $userArray = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $userIdDb=$userArray['id'];
}else{
    //page not found
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
                $sqlJob    = "SELECT  job_post.*,company.company_name,company.business_stream_id
                  FROM job_post INNER JOIN company ON job_post.company_id=company.id INNER JOIN job_post_activity ON job_post_activity.job_post_id=job_post.id
                  WHERE job_post_activity.id='".$applicationId."' AND job_post.is_delete='0' AND job_post.is_active='1'";
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
?>
  
    <div class="row">
        <div class="col-sm-12">
        <div class="panel panel-default target ">
	<div class="panel-heading">
            <div class="row">
            <div class="col-lg-6">
            <?php echo $jobTitle;?><br>
            <span style="font-size:15px;" class="text-muted">
                <a href="<?php echo $companyUrl?>"  target="_blank"><?php echo $companyName?></a>&nbsp;<br>
                Posted: <?php echo $daysPassed?></span>
            <br>
            <strong>Job Location:</strong>
                <p><?php echo $address?></p>

            </div>

                <div class="col-lg-6">
                <strong>Job Status:</strong>
                <p><?php echo $jobStatus?></p>
                <strong>Employment Type</strong>
                <p><?php echo $jobType?></p>
                <strong>Industry</strong>
                <p><?php echo get_business($businessStreamId)?></p>
                
            </div>
                
            </div>
            </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="thumbnail">
                        <div class="caption">
                            <h3>Job Description</h3>
                        <div style="float:none"><?php  echo $jobDescription?></div>
                        </div>
                    </div><!-- close thumbnail-->
                 </div> <!-- close col-10-->
           </div> <!-- close  col-10-->
            <div class="row">
                <div class="col-md-12">
                    <div class="thumbnail">
                        <div class="caption">
                         <div><strong style="font-size:1.2em">Skills:</strong> <?php echo $jobSkills?></div>
                        </div>
                    </div><!-- close thumbnail-->
                 </div> <!-- close col-10-->
           </div> <!-- close  col-10-->
           <div class="row">
                <div class="col-md-12">
                    <div class="thumbnail">
                        <div class="caption">
                         <div><strong style="font-size:1.2em">Job Function:</strong> <?php echo $jobFunction?></div>
                        </div>
                    </div><!-- close thumbnail-->
                 </div> <!-- close col-10-->
           </div> <!-- close  col-10-->
           
       </div><!-- pnale-bdy-->
            
        </div>
        </div>
    </div><!-- //.card-->
    
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