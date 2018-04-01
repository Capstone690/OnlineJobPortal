<?php
/*
 * File Name: add-edit-testimonial.php
 * By: Dipali
 * Date: 03/10/2018
 */
 $isSession=0;

require_once('include/session.php');
require_once("include/config.php");
require_once ("include/function.php");
$error="";
$successMsg="";
$sideBarActive=8;
//display content
$text = "";
$name="";
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
//edit page
$browserTitle = "Edit Testimonial";
$btnName="edit_testimonial";
$btn="Update";

// Get URL parameter

$id =  trim($_GET["id"]);
$sql = "SELECT `id`, `name`,text
        FROM testimonial WHERE id='".$id."' ";
$result = mysqli_query($db,$sql);
$count = mysqli_num_rows($result);
if($count == 1) {
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $id      = $row["id"];
      $name    = $row["name"];
      $text    = $row["text"];
 }

}else{
    $browserTitle = "Add Testimonial";
    $btnName="add_testimonial";
    $btn="Add";
}

if($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST["add_testimonial"]) || isset($_POST["edit_testimonial"]))) {

    $name       = test_input($_POST["name"]);
    $text       = test_input($_POST["text"]);
           //check if add/edit page
           if(isset($_POST["add_testimonial"])){
              $quer = "INSERT INTO testimonial (name,text, is_active) VALUES ('$name','$text', '1')";
              $res = mysqli_query($db,$quer);
              if($res){
                  $successMsg ="Record added successfully";
                  $_SESSION['success_message'] = $successMsg;
                  header("location:testimonial.php");
              }else{
                  $error="Error in adding record.";
              }
              
           }else if(isset($_POST["edit_testimonial"])){
                    $sql = "SELECT id FROM testimonial WHERE id='".$id."'";
                    $result = mysqli_query($db,$sql);
                    $count = mysqli_num_rows($result);
                   if($count == 1) {
                        $quer = "UPDATE testimonial set name='" .$name. "', text='" .$text. "'";
                        $quer .= " WHERE id='".$id."' ";
                        $res = mysqli_query($db,$quer);
                        if($res){

                           $successMsg ="Record updated successfully";
                           $_SESSION['success_message'] = $successMsg;

                           header("location:testimonial.php");
                        }else{
                             $error = "Error in update";
                        }
                    
               }else{
                    $error = "Record not found";
               }
               
           }
           else{
               $error ="Error in submitting form";
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
        <nav>
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
                <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        

                        <?php if($error){?>
                    <div class="alert alert-danger fade in">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Error! </strong><?php echo $error?>
                    </div>
                    <?php } ?>
                        <div class="panel-heading">
                            Testimonial
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-10">
                                    <form role="form"  data-toggle="validator" id="frm_add_update_testimonial"  name="frm_add_update_testimonial" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label  class="control-label">Text</label>
                                            <div class="help-block with-errors"></div>
                                            <textarea  class="form-control" id="text" name="text" required="true"><?php echo $text;?></textarea>
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Name</label>
                                            <div class="help-block with-errors"></div>
                                            <input class="form-control" id="name" name="name" value="<?php echo $name;?>" required="true">
                                    </div>
                                    
                                        <button type="submit"name="<?php echo $btnName?>" class="btn btn-default btn-primary"><?php echo $btn?></button>
                                        <button type="reset"  id="back" class="btn btn-default btn-primary">Cancel</button>
                                    </form>
                                </div> <!-- ./col-lg-6 -->
                            </div> <!-- ./row -->
                        </div> <!-- ."panel-body-->
                    </div> <!-- /.l panel-default-->
                </div> <!-- .col-lg-12 -->
            </div> <!-- .row -->


            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
<?php include("partials/footer.inc.php");?>
    
    <script>
    $(document).ready(function() {
       //validation
       addRequiredMark('frm_add_update_testimonial');
       $('#frm_add_update_testimonial').validator()
});
  </script>

</body>
</html>