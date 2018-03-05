<?php
/*
 * File Name: add-edit-category.php
 * By: Dipali
 * Date: 02/17/2018
 *
 */
require_once('include/session.php');
//include("include/config.php");
require_once("include/function.php");
$error="";
$successMsg="";
$sideBarActive=5;
//display content
$companyTitle    = "";
$profileDescription = "";
$businessStreamId =0;
$establishmentDate="";
$companyWebsiteUrl="";

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
//edit page
$browserTitle = "Edit Company";
$btnName="edit_company";
$btn="Update";

// Get URL parameter

$id =  trim($_GET["id"]);
$sql = "SELECT `id`, `company_name`, `profile_description`, `business_stream_id`, `establishment_date`, `company_website_url`
        FROM company WHERE id='".$id."' ";
$result = mysqli_query($db,$sql);
$count = mysqli_num_rows($result);
if($count == 1) {
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $companyId          = $row["id"];
      $companyTitle       = $row["company_name"];
      $profileDescription = $row["profile_description"];
      $businessStreamId   = $row["business_stream_id"];
      $establishmentDate  = $row["establishment_date"];
      $companyWebsiteUrl  = $row["company_website_url"];

 }

}else{
    $browserTitle = "Add Company";
    $btnName="add_company";
    $btn="Add";
}

if($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST["add_company"]) || isset($_POST["edit_company"]))) {

    $companyTitle       = test_input($_POST["companyTitle"]);
    $profileDescription = test_input($_POST["profileDescription"]);
    $businessStreamId   = test_input($_POST["businessStreamId"]);
    $establishmentDate  = test_input($_POST["establishmentDate"]);
    $companyWebsiteUrl  = test_input($_POST["companyWebsiteUrl"]);

           //check if add/edit page
           if(isset($_POST["add_company"])){
               //check for duplicate record
                $sql = "SELECT id FROM company WHERE company_name='$companyTitle' AND is_removed='0'";
                $result = mysqli_query($db,$sql);
                $count = mysqli_num_rows($result);
                if($count == 1) {
                     $error="Duplicate record.";
                }else{
                        $quer = "INSERT INTO company (`company_name`, `profile_description`, `business_stream_id`, `establishment_date`, `company_website_url`, `is_active`, `is_removed`)
                                 VALUES ('$companyTitle','$profileDescription','$businessStreamId','$establishmentDate','$companyWebsiteUrl', '1', '0')";
                        $res = mysqli_query($db,$quer);
                        if($res){
                           $successMsg ="Record added successfully";
                           $_SESSION['success_message'] = $successMsg;
                           header("location:company.php");
                        }else{
                            $error="Error in adding record.";
                        }
                }

           }else if(isset($_POST["edit_company"])){
                    $sql = "SELECT id FROM company WHERE id='".$id."'";
                    $result = mysqli_query($db,$sql);
                    $count = mysqli_num_rows($result);
                   if($count == 1) {
                       $sqlDuplicate = "SELECT id FROM company WHERE company_name='$companyTitle' AND is_removed='0' AND id!='".$id."'";
                       $resultDuplicate = mysqli_query($db,$sqlDuplicate);
                       $countDupli = mysqli_num_rows($resultDuplicate);
                       if($countDupli == 1) {
                             $error="Duplicate record.";
                        }else{

                        $quer = "UPDATE company set company_name='" .$companyTitle. "', profile_description='" .$profileDescription. "', business_stream_id='" .$businessStreamId. "', establishment_date='" .$establishmentDate. "', company_website_url='" .$companyWebsiteUrl. "'";
                        $quer .= " WHERE id='".$id."' ";
                        $res = mysqli_query($db,$quer);
                        if($res){

                           $successMsg ="Record updated successfully";
                           $_SESSION['success_message'] = $successMsg;

                           header("location:company.php");
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
                            Company
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-10">
                                    <form role="form"  data-toggle="validator" id="frm_add_update_company"  name="frm_add_update_company" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                            <label  class="control-label">Company Name</label>
                                            <div class="help-block with-errors"></div>
                                            <input class="form-control" id="company_title" name="companyTitle" value="<?php echo $companyTitle;?>" required="true">
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Business Stream</label>
                                            <div class="help-block with-errors"></div>
                                            <select class="business form-control" id="business_stream_id" name="businessStreamId" required="true">
                                                <?php echo get_business_options($businessStreamId);?>
                                            </select>
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Profile Description</label>
                                            <div class="help-block with-errors"></div>
                                            <textarea class="form-control" id="profile_description" name="profileDescription" required="true"><?php echo $profileDescription;?></textarea>
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Establishment Date</label>
                                            <div class="help-block with-errors"></div>
                                            <input class="form-control" style="width:160px;" type="date"  id="establishment_date" name="establishmentDate" value="<?php echo $establishmentDate;?>" required="true">
                                    </div>
                                        <div class="form-group">
                                            <label  class="control-label">Website URL</label>
                                            <div class="help-block with-errors"></div>
                                            <input class="form-control" type="url"  id="company_website_url" name="companyWebsiteUrl" value="<?php echo $companyWebsiteUrl;?>" required="true">
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
       addRequiredMark('frm_add_update_company');
       $('#frm_add_update_company').validator()
});
  </script>

</body>
</html>