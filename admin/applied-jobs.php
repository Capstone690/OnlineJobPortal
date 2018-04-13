<?php
/*
 * File Name: applied-jobs.php
 * By: Dipali
 * Date: 02/28/2018
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
$browserTitle = "Applied Job";
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
                         <!--
                        <div class="text-right" style="margin-bottom:10px;">
                            <a class="btn btn-primary " href="apply-for-job.php?userid=<?= $userIdDb;?>">Apply for Job</a>
                        </div>
                         -->
                             <form role="form"  id="frm_applied_jobs"  name="frm_applied_jobs" method="POST" enctype="multipart/form-data">
                                   
                       <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Company Name</th>
                                        <th>Job Title</th>
                                        <th>Location</th>
                                        <th>Date Applied</th>
                                        <th>Status</th>
                                        <th>Detail</th>
                                        <th>Delete Application</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        //display data
                                        $sql = " select temp.*,c.company_name from
(select user_account.id as user_id,job_post.job_title as job_title,job_post.loc_city as loc_city,job_post.loc_state as loc_state,a.apply_date as apply_date,a.status as status,a.id as id,job_post.company_id as company_id
from job_post_activity a
inner join user_account on a.user_account_id = user_account.id
inner join job_post on a.job_post_id = job_post.id WHERE user_account.id='".$userIdDb."' AND a.is_removed='0' AND job_post.is_delete='0') temp
inner join company c on temp.company_id = c.id";
                                        $result = mysqli_query($db,$sql);
                                        $count = mysqli_num_rows($result);
                                        $rowNo=1;
                                        if($count > 0) {
                                              while($content = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                                $recordId    = $content["id"];
                                                $userId  = $content["user_id"];
                                                $jobTitle  = $content["job_title"];
                                                $applyDate  = $content["apply_date"];
                                                $companyId= $content["company_id"];
                                                $companyName= $content["company_name"];
                                                $status = get_application_status($content["status"]);
                                                
                                                $locCity = get_city($content["loc_city"]);
                                                $locState=get_state($content["loc_state"]);
                                                $jobLocation = "<div class='location'>".$locCity.", ".$locState."</div>";

                                                $isExpired="";
                                                $class     = ($rowNo%2==0) ? "even" : "odd";
                                                ?>
                                    <tr class="<?php echo $class?> gradeX" id="record_<?php echo $recordId?>">
                                        <td><?php echo $companyName;?></td>
                                        <td><?php echo $jobTitle;?></td>
                                        <td><?php echo $jobLocation;?></td>
                                        <td><?php echo $applyDate;?></td>
                                        <td><?php echo $status?></td>
                                        <td class="center text-center"><a href="job-detail.php?id=<?= $recordId;?>&userid=<?php echo $userId;?>"><i class="fa fa-file fa-2x"></i></a></td>
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
            "aoColumns": [null,null,null,{ "bSortable": false },{ "bSortable": false },{ "bSortable": false },{ "bSortable": false }]
            
        });
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

     
    });
    </script>

</body>
</html>