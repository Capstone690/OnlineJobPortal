<?php
/*
 * File Name: applied-jobs.php
 * By: Dipali
 * Date: 03/25/2018
 */
$isSession=1;
require_once('admin/include/session.php');
require_once("admin/include/config.php");
require_once("admin/include/function.php");
$changeNavBar=True;

$browserTitle = "Applied Job";
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
    if($row["user_type_name"]=='J'){
       $userIdDb          = $row["id"];
        
    }
}
}else{
   header("location:login.php");die;
   
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
            <h2 class=" text-center ">Applied Jobs</h2><hr class=" text-center ">
                <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']);?>
                            </div>
                <?php endif ?>
             <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-error">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']);?>
                            </div>
                <?php endif ?>
               
                <div id="messages"></div>
                
                        
     <form role="form"  id="frm_jobs"  name="frm_jobs" method="POST" enctype="multipart/form-data">

<table id="datatables-example" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
        <th>Job Title</th>
        <th>Company Name</th>
        <th>Location</th>
        <th>Applied</th>
        <th>Status</th>
        <th>Contact Information</th>
        <th>Delete</th>
        </tr>
       </thead>
       <tbody>
       <?php
       //display data
                                        $sql = " select temp.*,c.company_name from
(select user_account.id as user_id,job_post.job_title as job_title,job_post.id as job_id,job_post.loc_city as loc_city,job_post.loc_state as loc_state,a.apply_date as apply_date,a.status as status,a.id as id,a.email as email, a.phone as phone, job_post.company_id as company_id
from job_post_activity a
inner join user_account on a.user_account_id = user_account.id
inner join job_post on a.job_post_id = job_post.id WHERE user_account.id='".$userIdDb."' AND a.is_removed='0') temp
inner join company c on temp.company_id = c.id";
                                        $result = mysqli_query($db,$sql);
                                        $count = mysqli_num_rows($result);
                                        $rowNo=1;
                                        if($count > 0) {
                                              while($content = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                                $recordId    = $content["id"];
                                                $userId  = $content["user_id"];
                                                $jobTitle  = $content["job_title"];
                                                $jobId     = $content["job_id"];
                                                $applyDate  = $content["apply_date"];
                                                $daysPassed=timeago($applyDate);

                                                $companyId= $content["company_id"];
                                                $companyName= $content["company_name"];

                                                $jobUrl = CURRENT_URL.'job-detail/' . $jobId;
                                                $companyUrl = CURRENT_URL.'company/' . urlencode(strtolower($companyName));


                                                $status = $content["status"];
                                                if($status=="P"){
                                                    $status="Pending";
                                                }
                                                $locCity = $content["loc_city"];
                                                $locState=$content["loc_state"];
                                                $jobLocation = "<div class='location'>".$locCity.", ".$locState."</div>";
                                                $email=$content["email"];
                                                $phone= $content["phone"];
                                                $contactInfo="Email: ".$email."<br> Phone".$phone;
                                                $isExpired="";
                                                $class     = ($rowNo%2==0) ? "even" : "odd";
                                                ?>
                                    <tr class="<?php echo $class?> gradeX" id="record_<?php echo $recordId?>">
                                        <td><a href="<?php echo $jobUrl?>" target="_blank"><?php echo $jobTitle;?></a></td>
                                        <td><a href="<?php echo $companyUrl?>" target="_blank"><?php echo $companyName;?></a></td>
                                        <td><?php echo $jobLocation;?></td>
                                        <td><?php echo $daysPassed;?></td>
                                        <td><?php echo $status?></td>
                                        <td><?php echo $contactInfo;?></td>
                                        <!--<td class="center text-center"><a href="job-detail.php?id=<?= $recordId;?>&userid=<?php echo $userId;?>"><i class="fa fa-file fa-2x"></i></a></td>-->
                                        <td class="center text-center"><button type="button" name="delete" class=" btn-danger delete no-border" id="<?php echo $recordId ?>"><i class="fa fa-trash fa-2x"></i></button></td>
                                    </tr>
                                    <?php
                                                $rowNo++;
                                              }

                                         }else{
                                             ?>
                                     <tr class="even gradeX">
                                        <td colspan="8">No records  found</td>
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
         "aoColumns": [null,null,null,null,{ "bSortable": false },{ "bSortable": false },{ "bSortable": false },{ "bSortable": false }]
           
    }
    );
   $("#dataTables-example_filter").css("text-align","right");
   $("#dataTables-example_paginate").css("text-align","right");
    //delete record
     $(document).on('click', '.delete', function(){
             var job_id = $(this).attr("id");
             var action = "delete_applied_job";

         if(confirm("Are you sure you want to remove this record from database?"))
          {
           // $( "#frm_add_update_news" ).submit();
              $.ajax({
                  url: 'admin/include/action.php',
                  type: 'post',
                  data:{job_id:job_id, action:action},
                  success: function(data, status) {
                     $("#record_"+job_id).html("");
                     $('#messages').html("<div class='alert alert-success '><a href='#' class='close' data-dismiss='alert'>&times;</a>Record deleted Successfully</div>");
                      //location.reload();
                  },
                  error: function(xhr, desc, err) {
                     $('#messages').html("<div class='alert alert-error '><a href='#' class='close' data-dismiss='alert'>&times;</a>Error in record deletion.</div>");

                  }
                }); // end ajax call

          }else
          {
           return false;
          }
         });

} );
</script>
</body>
</html>