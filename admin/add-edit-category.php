<?php
/*
 * File Name: add-edit-category.php
 * By: Dipali
 * Date: 02/15/2018
 * Modified By :Dipali
 * Modification date: 03/02/2018
 * Modification:Added font awsome icon input
 */
 $isSession=0;

require_once('include/session.php');
require_once("include/config.php");
require_once ("include/function.php");
$error="";
$successMsg="";
$sideBarActive=4;
//display content
$categoryTitle    = "";
$icon="";
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
//edit page
$browserTitle = "Edit Business Stream";
$btnName="edit_category";
$btn="Update";

// Get URL parameter

$id =  trim($_GET["id"]);
$sql = "SELECT `id`, `business_stream_name`,font_icon
        FROM bussiness_stream WHERE id='".$id."' ";
$result = mysqli_query($db,$sql);
$count = mysqli_num_rows($result);
if($count == 1) {
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $categoryId       = $row["id"];
      $categoryTitle    = $row["business_stream_name"];
      $icon            = $row["font_icon"];
 }

}else{
    $browserTitle = "Add Business Stream";
    $btnName="add_category";
    $btn="Add";
}

if($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST["add_category"]) || isset($_POST["edit_category"]))) {

    $categoryTitle       = test_input($_POST["categoryTitle"]);
    $icon                = test_input($_POST["fontIcon"]);
           //check if add/edit page
           if(isset($_POST["add_category"])){
               //check for duplicate record
                $sql = "SELECT id FROM bussiness_stream WHERE business_stream_name='$categoryTitle' AND is_removed='0'";
                $result = mysqli_query($db,$sql);
                $count = mysqli_num_rows($result);
                if($count == 1) {
                     $error="Duplicate record.";
                }else{
                        $quer = "INSERT INTO bussiness_stream (business_stream_name,font_icon, is_active,is_removed) VALUES ('$categoryTitle','$icon', '1', '0')";
                        $res = mysqli_query($db,$quer);
                        if($res){
                           $successMsg ="Record added successfully";
                           $_SESSION['success_message'] = $successMsg;
                           header("location:category.php");
                        }else{
                            $error="Error in adding record.";
                        }
                }

           }else if(isset($_POST["edit_category"])){
                    $sql = "SELECT id FROM bussiness_stream WHERE id='".$id."'";
                    $result = mysqli_query($db,$sql);
                    $count = mysqli_num_rows($result);
                   if($count == 1) {
                       $sqlDuplicate = "SELECT id FROM bussiness_stream WHERE business_stream_name='$categoryTitle' AND is_removed='0' AND id!='".$id."'";
                       $resultDuplicate = mysqli_query($db,$sqlDuplicate);
                       $countDupli = mysqli_num_rows($resultDuplicate);
                       if($countDupli == 1) {
                             $error="Duplicate record.";
                        }else{

                        $quer = "UPDATE bussiness_stream set business_stream_name='" .$categoryTitle. "', font_icon='" .$icon. "'";
                        $quer .= " WHERE id='".$id."' ";
                        $res = mysqli_query($db,$quer);
                        if($res){

                           $successMsg ="Record updated successfully";
                           $_SESSION['success_message'] = $successMsg;

                           header("location:category.php");
                        }else{
                             $error = "Error in update";
                        }
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
                            Business Stream
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-10">
                                    <form role="form"  data-toggle="validator" id="frm_add_update_category"  name="frm_add_update_category" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                            <label  class="control-label">Business Stream</label>
                                            <div class="help-block with-errors"></div>
                                            <input class="form-control" id="category_title" name="categoryTitle" value="<?php echo $categoryTitle;?>" required="true">
                                    </div>
                                    <div class="form-group">
                                        <label  class="control-label">Icon</label>&nbsp;<span>search for icon that suits your business from <a href="https://fontawesome.com/icons?d=gallery" target="_blank">font awesome</a> and paste classname </span>
                                            <div class="help-block with-errors"></div>
                                            <input class="form-control" id="font_icon" name="fontIcon" value="<?php echo $icon;?>" required="true">
                                            
                                    </div>
                                        <?php if($icon!=""){
                                            ?>
                                        <div style="margin-bottom:10px;"><i class="<?php echo $icon?>"></i></div>
                                        <?php
                                        }?>
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
       addRequiredMark('frm_add_update_category');
       $('#frm_add_update_category').validator()
});
  </script>

</body>
</html>