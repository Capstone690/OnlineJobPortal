<?php
/*
 * File Name: manage-jobs.php
 * By: Dipali
 * Date: 02/23/2018
 * Modified By:Dipali
 * Modification: Added job status
 */

require_once('include/session.php');
require_once("include/config.php");
require_once("include/function.php");
//check user type
if(isset($_GET["userid"]) && !empty(trim($_GET["userid"]))){
    $userId=$_GET["userid"];//lower case
}else{
    //page not found

}
$browserTitle = "Manage Job";
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
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"><?php echo $browserTitle;?></h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success fade in">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']);?>
                            </div>
                           <?php endif ?>
                        <div id="messages"></div>
                        <div class="panel-heading">
                            <?php echo $browserTitle?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        <div class="text-right" style="margin-bottom:10px;">
                            <a class="btn btn-primary " href="add-edit-job.php?userid=<?= $userIdDb;?>">Add Jobs</a>
                        </div>
                             <form role="form"  id="frm_jobs"  name="frm_jobs" method="POST" enctype="multipart/form-data">
                                   
                       <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
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
                            <!-- /.table-responsive -->
                            <!-- /.table-responsive -->
                             </form>
                        </div>      <!-- /.panel-body -->
                    </div> <!-- /.panel-default -->
                </div><!-- /.col-lg-12 -->
                </div> <!-- /.row -->

            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
<?php include("partials/footer.inc.php");?>
    <!-- DataTables JavaScript -->
    <script src="public/javascript/datatables/js/jquery.dataTables.min.js"></script>
    <script src="public/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="public/datatables-responsive/dataTables.responsive.js"></script>
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
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
                  url: 'include/action.php',
                  type: 'post',
                  data:{job_id:job_id, action:action},
                  success: function(data, status) {
                     $("#record_"+job_id).html("");
                     $('#messages').html("<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>Record deleted Successfully</div>");
                      //location.reload();
                  },
                  error: function(xhr, desc, err) {
                     $('#messages').html("<div class='alert alert-error fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>Error in record deletion.</div>");

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
                  url: 'include/action.php',
                  type: 'post',
                  data:{job_id:job_id, action:action},
                  success: function(data, status) {
                     $("#status_"+job_id).html(data);
                     $('#messages').html("<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>Status changed Successfully</div>");
                      //location.reload();
                  },
                  error: function(xhr, desc, err) {
                     $('#messages').html("<div class='alert alert-error fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>Error in status updation.</div>");

                  }
                }); // end ajax call

          }else
          {
           return false;
          }
         });
     
    });
    </script>

</body>
</html>