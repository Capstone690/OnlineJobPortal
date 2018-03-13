<?php
/*
 * File Name: company-gallery.php
 * By: Dipali
 * Date: 02/20/2018
 *
 */

require_once('include/session.php');
require_once("include/config.php");
require_once ("include/function.php");
$browserTitle = "Manage Company Gallery";
$error="";
$successMsg="";
$sideBarActive=5;
$id="";
$media   = "";

if(isset($_GET["company_id"]) && !empty(trim($_GET["company_id"]))){
    $companyId = $_GET['company_id'];
}
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $btnName="edit_gallery";
    $btn="Update";
    // Get URL parameter
    $id =  trim($_GET["id"]);
    $sql = "SELECT `id`, `company_id`, `company_image`
            FROM company_image WHERE id='".$id."' ";
    $result = mysqli_query($db,$sql);
    $count = mysqli_num_rows($result);
    if($count == 1) {
          $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
          $media           = $row["company_image"];

          }

}else{
    $btnName="add_gallery";
    $btn="Insert";
}
if($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST["add_gallery"]) || isset($_POST["edit_gallery"]))) {
$imgName         = basename($_FILES["fileToUpload"]["name"]);
if(!empty($imgName)){
        $target_file = TARGET_DIR_FRONT ."/".$imgName ;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check === false) {
            $error ="File is not image!";
        }else{
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $error=  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        }

    }
   if($error===""){
       if(isset($_POST["add_gallery"])){
            $quer = "INSERT INTO company_image (company_id, company_image,is_active) VALUES ('$companyId', '$imgName','1')";
                        $res = mysqli_query($db,$quer);
                        if($res){
                           if(!empty($imgName)){
                              move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                            }
                           $successMsg ="Record added successfully";
                           $_SESSION['success_message'] = $successMsg;

                           //header("location:company-gallery.php?company_id=".$companyId);
                        }else{
                            $error="Error in adding record.";
                        }
       }else if(isset($_POST["edit_gallery"])){
           $sql = "SELECT id FROM company_image WHERE id='".$id."'";
           $result = mysqli_query($db,$sql);
           $count = mysqli_num_rows($result);
           if($count == 1) {
               $quer = "UPDATE company_image set  company_image='" .$imgName. "'";
                        if(!empty($imgName)){
                          move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                        }
                        $quer .= " WHERE id='".$id."' ";
                        $res = mysqli_query($db,$quer);
                        if($res){

                           $successMsg ="Record updated successfully";
                           $_SESSION['success_message'] = $successMsg;

                        //  header("location:company-gallery.php?company_id=".$companyId);
                        }else{
                             $error = "Error in update";
                        }
           }else{
                $error = "Record not found";
           }
           
       }
   }
    
}
?>
<!DOCTYPE html>
<html>
<head>
<?php include("partials/header.inc.php"); ?>
    <style>
    
    </style>
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
                        <?php if ($successMsg): ?>
                            <div class="alert alert-success fade in">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <?php echo $successMsg;?>
                            </div>
                           <?php endif ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger fade in">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <?php echo $error;?>
                            </div>
                           <?php endif ?>
                        <div id="messages"></div>
                        <div class="panel-heading">
                            <?php echo $browserTitle?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <?php if($id){?>
                            <form role="form" id="frm_edit_gallery" name="frm_edit_gallery" method="POST" enctype="multipart/form-data" >
                                <div class="form-group">
                                    <label>Upload Image <?php echo $media?> </label>
                                    <div class="help-block with-errors"></div>
                                    <?php 

                                    if($media!=""){?>
                                    <div id="edit_profile_pic">
                                    <img src="<?php echo TARGET_DIR_FRONT."/".$media?>" />
                                    </div>
                                    <?php } ?>
                                    <input style="display: inline" class=" upload-img form-control" type="file" name="fileToUpload" >
                                </div>
                                <div class="form-group">
                                    <button type="submit"name="<?php echo $btnName?>" class="btn btn-default btn-primary"><?php echo $btn?></button>
                                    <button type="button" id="back" class="btn btn-default btn-primary">Cancel</button>
                                </div>
                            </form>
                            <?php }else{
                              ?>

                            <form role="form" id="frm_add_gallery" name="frm_add_gallery" method="POST" enctype="multipart/form-data" >
                                <div class="form-group">
                                    <label>Upload Image <?php echo $media?> </label>
                                    <div class="help-block with-errors"></div>
                                    <input style="display: inline" class=" upload-img form-control" type="file" name="fileToUpload" >
                                </div>
                                <div class="form-group">
                                    <button type="submit"name="<?php echo $btnName?>" class="btn btn-default btn-primary"><?php echo $btn?></button>
                                    <button type="button" id="cancel" class="btn btn-default btn-primary">Cancel</button>
                                </div>
                            </form>
                            <?php
                            }?>

                        <div class="text-right" style="margin-bottom:10px;">
                            <a class="btn btn-primary " id="add_gallery">Add Image</a>
                        </div>
                             <form role="form"  id="frm_gallery"  name="frm_gallery" method="POST" enctype="multipart/form-data">
                                   
                       <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        //display data
                                        $sql = "SELECT `id`,`company_id`,`company_image`,`is_active` FROM company_image WHERE company_id='".$companyId."' ";
                                        $result = mysqli_query($db,$sql);
                                        $count = mysqli_num_rows($result);
                                        $rowNo=1;
                                        if($count > 0) {
                                              while($content = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                                $galleryId = $content["id"];
                                                $galleryImage = $content["company_image"];
                                                $isActive = $content["is_active"];
                                                $class = ($rowNo%2==0)?"even":"odd";
                                                
                                                ?>
                                    <tr class="<?php echo $class?> gradeX" id="record_<?php echo $galleryId?>">
                                        <td><img width="100" height="100" src="<?php echo TARGET_DIR_FRONT."/".$galleryImage?>" ></td>
                                        <td class="text-center">
                                            <button type="button" name="status" id="status_<?php echo $galleryId?>" class=" status no-border" data-id="<?php echo $galleryId ?>">
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
                                        <td class="center text-center"><a href="company-gallery.php?company_id=<?php echo $companyId;?>&id=<?php echo $galleryId ?>"><i class="fa fa-edit fa-2x"></i></a></td>
                                        <td class="center text-center"><button type="button" name="delete" class=" btn-danger delete no-border" id="<?php echo $galleryId ?>"><i class="fa fa-trash fa-2x"></i></button></td>
                                    </tr>
                                    <?php
                                                $rowNo++;
                                              }

                                         }else{
                                             ?>
                                     <tr class="even gradeX">
                                        <td colspan="5">No records  found</td>
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
        //add imageclick
        $( "#add_gallery" ).click(function() {
            $("#frm_add_gallery").slideDown(1000);
        });
        //edit image
        $( "#add_gallery" ).click(function() {
            $("#frm_add_gallery").slideDown(1000);
        });

        $("#cancel").click(function(){
            $("#frm_add_gallery").slideUp(1000);
        });
        $('#dataTables-example').DataTable({
            responsive: true,
            stateSave: true,
            "bFilter": false,
            "aoColumns": [{ "bSortable": false },{ "bSortable": false },{ "bSortable": false },{ "bSortable": false }]
        });
        $("#dataTables-example_paginate").css("text-align","right");
        
        //delete record
     $(document).on('click', '.delete', function(){
             var gallery_id = $(this).attr("id");
             var action = "delete_gallery";

         if(confirm("Are you sure you want to remove this record from database?"))
          {
              $.ajax({
                  url: 'include/action.php',
                  type: 'post',
                  data:{gallery_id:gallery_id, action:action},
                  success: function(data, status) {
                     $("#record_"+gallery_id).html("");
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
             var gallery_id = $(this).attr("data-id");
             var action = "change_gallery_status";

         if(confirm("Are you sure you want to change status?"))
          {
              $.ajax({
                  url: 'include/action.php',
                  type: 'post',
                  data:{gallery_id:gallery_id, action:action},
                  success: function(data, status) {
                     $("#status_"+gallery_id).html(data);
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