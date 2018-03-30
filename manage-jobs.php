<?php
/*
 * File Name: manage-jobs.php
 * By: Dipali
 * Date: 03/23/2018
 */
$isSession=1;
require_once('admin/include/session.php');
require_once("admin/include/config.php");
require_once("admin/include/function.php");
$browserTitle="Job Seeker Profile";
$changeNavBar=True;

$browserTitle = "Manage Job";
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
            <h2 class=" text-center ">Manage Jobs</h2><hr class=" text-center ">
                <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success fade in">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']);?>
                            </div>
                <?php endif ?>
                <div id="messages"></div>
                <div class="text-right" style="margin-bottom:10px;padding-right: 15px;">
                      <a class="btn btn-primary " href="add-edit-job.php?userid=<?= $userIdDb;?>">Add Jobs</a>
                </div>
                        
     <form role="form"  id="frm_jobs"  name="frm_jobs" method="POST" enctype="multipart/form-data">

<table id="datatables-example" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
        <th>Job Title</th>
        <th>Type</th>
        <th>Posted Date</th>
        <th>Location</th>
        <th>Job Status</th>
        <th>Status</th>
        <th>Edit</th>
        <th>Delete</th>
        </tr>
       </thead>
       <tbody>
       <?php
       //display data
       $sql = "SELECT `id`,posted_by_id, job_type_id,`job_title`, `posted_date`, `loc_street_address1`, `loc_street_address2`,`loc_city`, `loc_state`, `loc_country`, `loc_zip`, is_active, job_status FROM job_post WHERE is_delete='0' AND posted_by_id=".$userIdDb." ";
       $result = mysqli_query($db,$sql);
       $count = mysqli_num_rows($result);
       $rowNo=1;
       if($count > 0) {
        while($content = mysqli_fetch_array($result,MYSQLI_ASSOC)){
         $recordId    = $content["id"];
         $postedById  = $content["posted_by_id"];
         $jobTypeId  = $content["job_type_id"];
         $jobStatusId = $content["job_status"];
         $jobTitle  = $content["job_title"];
         $postedDate= $content["posted_date"];
         $locStreetAddress1= $content["loc_street_address1"];
         $locStreetAddress2= $content["loc_street_address2"];
         $locCity= $content["loc_city"];
         $locState= $content["loc_state"];
         $locCountry= $content["loc_country"];
         $locZip= $content["loc_zip"];
         $jobLocation = "<div class='location'>".$locCity.", ".$locState."</div>";
         //get type
         $jobType = get_job_type($jobTypeId);
         //get job status
         $jobStatus = get_job_status($jobStatusId);
         $isActive  = $content["is_active"];
         $class     = ($rowNo%2==0) ? "even" : "odd";
         ?>
         <tr class="<?php echo $class?> gradeX" id="record_<?php echo $recordId?>">
         <td><?php echo $jobTitle;?></td>
         <td><?php echo $jobType;?></td>
         <td><?php echo $postedDate;?></td>
         <td><?php echo $jobLocation;?></td>
         <td><?php echo $jobStatus;?></td>
         <td class="text-center">
         <button type="button" name="status" id="status_<?php echo $recordId?>" class=" status no-border" data-id="<?php echo $recordId ?>">
         <?php if($isActive==='1'){
         ?>
         <i class="fa fa-check-circle fa-2x green"></i>
         <?php
         }else{
         ?>
         <i class="fa fa-ban fa-2x red"></i>
         <?php
         }?>
         </button>
         </td>
         <td class="center text-center"><a href="add-edit-job.php?id=<?= $recordId;?>&userid=<?php echo $postedById;?>"><i class="fa fa-edit fa-2x"></i></a></td>
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
    <!--<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true,
            stateSave: true,
            "aoColumns": [null,null,null,null,{ "bSortable": false },{ "bSortable": false },{ "bSortable": false },{ "bSortable": false }]
            
        });
        $("#dataTables-example_filter").css("text-align","right");
        $("#dataTables-example_paginate").css("text-align","right");
       
        //delete record
     $(document).on('click', '.delete', function(){
             var job_id = $(this).attr("id");
             var action = "delete_job";

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

         //change status
     $(document).on('click', '.status', function(){
             var job_id = $(this).attr("data-id");
             var action = "change_job_status";

         if(confirm("Are you sure you want to change status?"))
          {
           // $( "#frm_add_update_news" ).submit();
              $.ajax({
                  url: 'admin/include/action.php',
                  type: 'post',
                  data:{job_id:job_id, action:action},
                  success: function(data, status) {
                     $("#status_"+job_id).html(data);
                     $('#messages').html("<div class='alert alert-success '><a href='#' class='close' data-dismiss='alert'>&times;</a>Status changed Successfully</div>");
                      //location.reload();
                  },
                  error: function(xhr, desc, err) {
                     $('#messages').html("<div class='alert alert-error '><a href='#' class='close' data-dismiss='alert'>&times;</a>Error in status updation.</div>");

                  }
                }); // end ajax call

          }else
          {
           return false;
          }
         });
     
    });
    </script>-->
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
             var action = "delete_job";

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

         //change status
     $(document).on('click', '.status', function(){
             var job_id = $(this).attr("data-id");
             var action = "change_job_status";

         if(confirm("Are you sure you want to change status?"))
          {
           // $( "#frm_add_update_news" ).submit();
              $.ajax({
                  url: 'admin/include/action.php',
                  type: 'post',
                  data:{job_id:job_id, action:action},
                  success: function(data, status) {
                     $("#status_"+job_id).html(data);
                     $('#messages').html("<div class='alert alert-success '><a href='#' class='close' data-dismiss='alert'>&times;</a>Status changed Successfully</div>");
                      //location.reload();
                  },
                  error: function(xhr, desc, err) {
                     $('#messages').html("<div class='alert alert-error '><a href='#' class='close' data-dismiss='alert'>&times;</a>Error in status updation.</div>");

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