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

$browserTitle = "View Application";
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

if(isset($_GET["jobid"]) && !empty(trim($_GET["jobid"]))){
    $jobId=$_GET["jobid"];//lower case
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
            <h2 class=" text-center ">View Applications</h2><hr class=" text-center ">
                <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']);?>
                            </div>
                <?php endif ?>
                <div id="messages"></div>
                <div class="text-right" style="margin-bottom:10px;padding-right: 15px;">
                      <!--<a class="btn btn-primary " href="add-edit-job">Add Jobs</a>-->
                </div>
                        
     <form role="form"  id="frm_jobs"  name="frm_jobs" method="POST" enctype="multipart/form-data">

<table id="datatables-example" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Status</th>
        <th>View profile</th>
        </tr>
       </thead>
       <tbody>
       <?php
       //display data
       $sql = "SELECT job_post_activity.id,job_post_activity.apply_date,job_post_activity.status,job_post_activity.email as apply_email,job_post_activity.phone as apply_phone,
                                                       user_account.first_name,user_account.last_name,user_account.email as user_email,user_account.contact_number as user_phone,job_seeker_profile.city,job_seeker_profile.state
                                                FROM job_post_activity
                                                INNER JOIN user_account ON job_post_activity.user_account_id=user_account.id
                                                INNER JOIN job_seeker_profile ON job_post_activity.user_account_id=job_seeker_profile.user_account_id
                                                WHERE job_post_activity.job_post_id='".$jobId."' AND
                                                      job_post_activity.is_removed='0' AND
                                                      user_account.is_delete='0'AND
                                                      user_account.is_approved='1' ";
                                        $result = mysqli_query($db,$sql);
                                        $count = mysqli_num_rows($result);
                                        $rowNo=1;
                                        if($count > 0) {
                                              while($content = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                                $recordID  = $content["id"];
                                                $name      = $content["first_name"]." ".$content["last_name"];
                                                $email     = $content["apply_email"]?$content["apply_email"]:$content["user_email"];
                                                $phone     = $content["apply_phone"]?$content["apply_phone"]:$content["user_phone"];

                                                $applyDate  = $content["apply_date"];
                                                $jobStatus  = get_application_status($content["status"]);
                                                $location   = $content["city"].", ".$content["state"];

                                                $class     = ($rowNo%2==0) ? "even" : "odd";

                                                ?>
                                    <tr class="<?php echo $class?> gradeX" id="record_<?php echo $recordID?>">
                                        <td><?php echo $name;?></td>
                                        <td><?php echo $email;?></td>
                                        <td><?php echo $phone;?></td>
                                        <td><?php echo $location;?></td>
                                        <td><?php echo $jobStatus;?></td>

                                        <td class="center text-center"><a href="<?php echo CURRENT_URL?>application-details/<?php echo $recordID;?>"><i class="fa fa-book fa-2x"></i></a></td>
                                    </tr>
                                    <?php
                                                $rowNo++;
                                              }

                                         }else{
                                             ?>
                                     <tr class="even gradeX">
                                        <td colspan="6">No records  found</td>
                                    </tr>
                                      <?php
                                         }
                                        ?>
                                     
      </tbody>
    </table>
         </form>
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