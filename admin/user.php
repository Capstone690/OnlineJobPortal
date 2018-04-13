<?php
/*
 * File Name: user.php
 * By: Dipali
 * Date: 02/20/2018
 * Modifed By: Dipali
 * Modified Date: 02/21/2018
 */
 $isSession=0;

require_once('include/session.php');
require_once("include/config.php");
require_once("include/function.php");
//check user type
if(isset($_GET["type"]) && !empty(trim($_GET["type"]))){
    $userTypeQuery=$_GET["type"];//lower case
    $userType=strtoUpper($userTypeQuery);//upper case as saved in database
    
}else{
    //page not found

}
if($userType==='E'){
    $browserTitle = "Manage Employer";
    $userTypeName ="Employer";
    $sideBarActive=6;
    $hideEmployer="display:none";
    $hideJobSeeker="";

}else if($userType==='J'){
    $browserTitle = "Manage Job Seeker";
    $userTypeName ="Job Seeker";
    $sideBarActive=7;
    $hideEmployer="";
    $hideJobSeeker="display:none";
}

$error="";
$successMsg="";
//get user type id
$sql = "SELECT `id`, `user_type_name` FROM user_type WHERE user_type_name='".$userType."'";
$result = mysqli_query($db,$sql);
$count = mysqli_num_rows($result);
if($count > 0) {
    $userTypeArray = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $usertypeId=$userTypeArray['id'];
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
                            <a class="btn btn-primary " href="add-edit-user.php?type=<?= $userTypeQuery;?>">Add <?php echo $userTypeName;?></a>
                        </div>
                             <form role="form"  id="frm_user"  name="frm_user" method="POST" enctype="multipart/form-data">
                                   
                       <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th style="<?php echo $hideJobSeeker?>">Company</th>
                                        <th style="<?php echo $hideJobSeeker?>">Jobs</th>
                                        <th style="<?php echo $hideEmployer?>">Address</th>
                                        <th style="<?php echo $hideEmployer?>">Applied Jobs</th>
                                        <th>Status</th>
                                        <th>Approve</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        //display data
                                        $sql = "SELECT `id`, `user_type_id`, `first_name`, `last_name`, `email`, is_active, is_approved FROM user_account WHERE is_delete='0' AND user_type_id=".$usertypeId." ";
                                        $result = mysqli_query($db,$sql);
                                        $count = mysqli_num_rows($result);
                                        $rowNo=1;
                                        if($count > 0) {
                                              while($content = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                                $userId    = $content["id"];
                                                $name      = $content["first_name"]." ".$content["last_name"];
                                                $email     = $content["email"];
                                                $isActive  = $content["is_active"];
                                                $isApprove = $content["is_approved"];
                                                $class     = ($rowNo%2==0) ? "even" : "odd";
                                                //get employer profile
                                                $companyId =0;
                                                if($userType==='E'){

                                                      $sqlEmployer = "SELECT `company_id`
                                                    FROM employer_profile WHERE user_account_id='".$userId."' ";
                                                        $resultEmployer = mysqli_query($db,$sqlEmployer);
                                                        $countEmployer = mysqli_num_rows($resultEmployer);
                                                        if($countEmployer == 1) {
                                                            $rowEmployer = mysqli_fetch_array($resultEmployer,MYSQLI_ASSOC);
                                                            $companyId   = $rowEmployer["company_id"];
                                                            }
                                                  }
                                                $companyName = get_company($companyId);
                                                 if($userType==='J'){

                                                      $sqlJobSeeker = "SELECT `city`,state
                                                    FROM job_seeker_profile WHERE user_account_id='".$userId."' ";
                                                        $resultJobSeeker = mysqli_query($db,$sqlJobSeeker);
                                                        $countJobSeeker = mysqli_num_rows($resultJobSeeker);
                                                        if($countJobSeeker == 1) {
                                                            $rowJobSeeker = mysqli_fetch_array($resultJobSeeker,MYSQLI_ASSOC);
                                                            $location   = get_city($rowJobSeeker["city"]).", ".get_state($rowJobSeeker["state"]);
                                                            }else{
                                                                $location="-";
                                                            }
                                                  }
                                                ?>
                                    <tr class="<?php echo $class?> gradeX" id="record_<?php echo $userId?>">
                                        <td><?php echo $name;?></td>
                                        <td><?php echo $email;?></td>
                                        <td  style="<?php echo $hideJobSeeker?>"><?php echo $companyName;?></td>
                                        <td  style="<?php echo $hideEmployer?>"><?php echo $location;?></td>
                                        
                                        <td  style="<?php echo $hideJobSeeker?>" class="center text-center"><a href="manage-jobs.php?userid=<?php echo $userId;?>"><i class="fa fa-book fa-2x"></i></a></td>
                                        <td  style="<?php echo $hideEmployer?>" class="center text-center"><a href="applied-jobs.php?userid=<?php echo $userId;?>"><i class="fa fa-book fa-2x"></i></a></td>

                                        <td class="text-center">
                                            <button type="button" name="status" id="status_<?php echo $userId?>" class=" status no-border" data-id="<?php echo $userId ?>">
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
                                        <td class="text-center">
                                            <?php if($isApprove==='0'){
                                                ?>
                                            <button type="button" name="approve" id="approve_<?php echo $userId?>" class=" approve no-border" data-id="<?php echo $userId ?>">
                                             <i class="fa fa-ban fa-2x red"></i>
                                            </button>
                                            <?php
                                            }else{
                                                ?>
                                            <button onclick="alert('You can not change status once approved.'); return false;">
                                            <i class="fa fa-check-circle fa-2x green"></i>
                                            </button>
                                            <?php
                                            }?>
                                            
                                        </td>
                                        <td class="center text-center"><a href="add-edit-user.php?type=<?= $userTypeQuery;?>&id=<?php echo $userId;?>"><i class="fa fa-edit fa-2x"></i></a></td>
                                        <td class="center text-center"><button type="button" name="delete" class=" btn-danger delete no-border" id="<?php echo $userId ?>"><i class="fa fa-trash fa-2x"></i></button></td>
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
            "aoColumns": [null,null,null,null,null,{ "bSortable": false },{ "bSortable": false },{ "bSortable": false },{ "bSortable": false },{ "bSortable": false }]
            
        });
        $("#dataTables-example_filter").css("text-align","right");
        $("#dataTables-example_paginate").css("text-align","right");
       
        //delete record
     $(document).on('click', '.delete', function(){
             var user_id = $(this).attr("id");
             var action = "delete_user";

         if(confirm("Are you sure you want to remove this record from database?"))
          {
           // $( "#frm_add_update_news" ).submit();
              $.ajax({
                  url: 'include/action.php',
                  type: 'post',
                  data:{user_id:user_id, action:action},
                  success: function(data, status) {
                     $("#record_"+user_id).html("");
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
             var user_id = $(this).attr("data-id");
             var action = "change_user_status";

         if(confirm("Are you sure you want to change status?"))
          {
           // $( "#frm_add_update_news" ).submit();
              $.ajax({
                  url: 'include/action.php',
                  type: 'post',
                  data:{user_id:user_id, action:action},
                  success: function(data, status) {
                     $("#status_"+user_id).html(data);
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
       //approve user
     $(document).on('click', '.approve', function(){
             var user_id = $(this).attr("data-id");
             var action = "approve_user";

         if(confirm("Are you sure you want to approve user?"))
          {
           // $( "#frm_add_update_news" ).submit();
              $.ajax({
                  url: 'include/action.php',
                  type: 'post',
                  data:{user_id:user_id, action:action},
                  success: function(data, status) {
                     $("#approve_"+user_id).html(data);
                     $('#messages').html("<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>User Approved Successfully</div>");
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