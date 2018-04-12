<?php
/*
 * File Name: view-application.php
 * By: Dipali
 * Date: 04/06/2018
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

$browserTitle = "View Applications";
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
                        <!--  <a class="btn btn-primary " href="add-edit-user.php?type=<?= $userTypeQuery;?>">Add <?php echo $userTypeName;?></a>-->
                        </div>
                             <form role="form"  id="frm_application"  name="frm_application" method="POST" enctype="multipart/form-data">
                                   
                       <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
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
                                       
                                        <td class="center text-center"><a href="application-details.php?userid=<?php echo $userId;?>&jobId=<?php echo $jobId;?>&applicationId=<?php echo $recordID;?>"><i class="fa fa-book fa-2x"></i></a></td>
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
            "aoColumns": [null,null,null,null,{ "bSortable": false },{ "bSortable": false }]
            
        });
        $("#dataTables-example_filter").css("text-align","right");
        $("#dataTables-example_paginate").css("text-align","right");
       
        

    });
    </script>

</body>
</html>