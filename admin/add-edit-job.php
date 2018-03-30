<?php
/*
 * File Name: add-edit-job.php
 * By: Dipali
 * Date: 02/23/2018
 * Modified By: Dipali
 * Modified Date: 03/02/2018
 * Modification:Added job status (draft,open, on hold,closed, filled)
 *
 */
require_once('include/session.php');
require_once("include/config.php");
require_once ("include/function.php");
//check user 
if(isset($_GET["userid"]) && !empty(trim($_GET["userid"]))){
    $userId=$_GET["userid"];//lower case
}else{
    //page not found
}
$sideBarActive=6;
$error="";
$successMsg="";
$browserTitle ="";

//display content
$jobTitle   = "";
//get compnay from user
 $sqlGetCompany = "SELECT `id`, `company_id`
        FROM employer_profile WHERE user_account_id='".$userId."' ";
 $resultGetCompany = mysqli_query($db,$sqlGetCompany);
 $countCompany = mysqli_num_rows($resultGetCompany);
 if($countCompany == 1) {
    $rowCompany = mysqli_fetch_array($resultGetCompany,MYSQLI_ASSOC);
    $companyId   = $rowCompany["company_id"];
}
$companyName ="";

$postedDate ="";
$jobPostedBy=$userId;

$jobTypeId    = "";
$jobStatusId="";
$jobFunction="";
$jobSkills="";
$locStreetAddress1 ="";
$locStreetAddress2 ="";
$locCity="";
$locState="";
$locCountry	="";
$locZip="";
$jobDescription="";

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
//edit page
$browserTitle = "Edit Jobs";
$btnName="edit";
$btn="Update";

// Get URL parameter

$id =  trim($_GET["id"]);
$sql = "SELECT `id`, `job_title`, `posted_by_id`, `job_type_id`, `company_id`, `posted_date`, `job_description`, `job_function`, `job_skills`,
    `loc_street_address1`, `loc_street_address2`, `loc_city`, `loc_state`, `loc_country`, `loc_zip`, job_status
        FROM job_post WHERE id='".$id."' ";
$result = mysqli_query($db,$sql);
$count = mysqli_num_rows($result);
if($count == 1) {
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $jobTitle   = $row["job_title"];
        $companyId   = $row["company_id"];
        $companyName = get_company($row["company_id"]);
        
        $postedDate =$row["posted_date"];
        $jobTypeId    = $row["job_type_id"];
        $jobType = get_job_type($jobTypeId);
        $jobStatusId= $row["job_status"];
        $jobStatus = get_job_status($jobStatusId);
        
        $jobFunction=$row["job_function"];
        $jobSkills=$row["job_skills"];
        $locStreetAddress1 =$row["loc_street_address1"];
        $locStreetAddress2 =$row["loc_street_address2"];
        $locCity=$row["loc_city"];
        $locState=$row["loc_state"];
        $locCountry	=$row["loc_country"];
        $locZip=$row["loc_zip"];
        $jobDescription=$row["job_description"];
        $jobPostedBy = $row["posted_by_id"];

 }

}else{
    $browserTitle = "Add Job";
    $btnName="add";
    $btn="Add";
}

if($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST["add"]) || isset($_POST["edit"]) )) {

        $jobTitle   = test_input($_POST["jobTitle"]);
        //hidden
        $companyId   = test_input($_POST["companyId"]);
        $postedDate =test_input($_POST["postedDate"]);
        $jobTypeId    = test_input($_POST["jobTypeId"]);
        $jobStatusId    = test_input($_POST["jobStatusId"]);

        $jobFunction=test_input($_POST["jobFunction"]);
        $jobSkills=test_input($_POST["jobSkills"]);
        $locStreetAddress1 =test_input($_POST["locStreetAddress1"]);
        $locStreetAddress2 =test_input($_POST["locStreetAddress2"]);
        $locCity=test_input($_POST["locCity"]);
        $locState=test_input($_POST["locState"]);
        $locCountry	=test_input($_POST["locCountry"]);
        $locZip=test_input($_POST["locZip"]);
        $jobDescription=mysqli_real_escape_string($db,$_POST["jobDescription"]);

        //get from hidden field
        $jobPostedBy = test_input($_POST["jobPostedBy"]);
    
        if($error===""){
           //check if add/edit page
           if(isset($_POST["add"])){
                         $quer = "INSERT INTO job_post (`job_title`, `posted_by_id`, `job_type_id`, job_status, `company_id`, `posted_date`, `job_description`, `job_function`, `job_skills`, `loc_street_address1`, `loc_street_address2`, `loc_city`, `loc_state`, `loc_country`, `loc_zip`,is_active,is_delete) VALUES
                                ('$jobTitle', '$jobPostedBy','$jobTypeId','$jobStatusId', '$companyId', '$postedDate', '$jobDescription','$jobFunction','$jobSkills','$locStreetAddress1','$locStreetAddress2','$locCity','$locState','$locCountry','$locZip','1','0')";
                        $res = mysqli_query($db,$quer);
                        //get recent generated id
                        $recordId=mysqli_insert_id($db);
                        if($res){
                                $successMsg ="Record added successfully";
                                $_SESSION['success_message'] = $successMsg;
                                header("location:manage-jobs.php?userid=".$jobPostedBy);
                           
                           
                        }else{
                            $error="Error in adding record.";
                        }
                      

           }else if(isset($_POST["edit"])){
                    $sql = "SELECT id FROM job_post WHERE id='".$id."'";
                    $result = mysqli_query($db,$sql);
                    $count = mysqli_num_rows($result);
                   if($count == 1) {

                        $quer = "UPDATE job_post set job_title='" .$jobTitle. "', job_type_id='" .$jobTypeId. "', job_status='" .$jobStatusId. "', posted_date='" .$postedDate. "', job_description='" .$jobDescription. "', job_function='" .$jobFunction. "', job_skills='" .$jobSkills. "', loc_street_address1='" .$locStreetAddress1. "',
                                loc_street_address2='" .$locStreetAddress2. "', loc_city='" .$locCity. "', loc_state='" .$locState. "', loc_country='" .$locCountry. "', loc_zip='" .$locZip. "'";
                        
                        $quer .= " WHERE id='".$id."' ";
                        $res = mysqli_query($db,$quer);
                        if($res){
                           $successMsg ="Record updated successfully";
                           $_SESSION['success_message'] = $successMsg;

                          header("location:manage-jobs.php?userid=".$jobPostedBy);
                        }else{
                             $error = "Error in update";
                        }
               }else{
                    $error = "Record not found";
               }
               
           }else{
               $error ="Error in submitting form";
           }
           

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
                            <?php echo $browserTitle;?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-10">
                                    <form role="form"  data-toggle="validator" id="frm_add_update_job"  name="frm_add_update_job" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                            <label  class="control-label">Job Title</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="text" class="form-control" id="jobTitle" name="jobTitle" value="<?php echo $jobTitle;?>" required="true">
                                    </div>
                                    <!-- hidden -->
                                    
                                    <input type="hidden" class="form-control" id="jobPostedBy" name="jobPostedBy" value="<?php echo $jobPostedBy;?>" >
                                    <div class="form-group">
                                            <label  class="control-label">Company</label>
                                            <div class="help-block with-errors"></div>
                                            <!-- hidden-->
                                            <input type="hidden" class="form-control" id="companyId" name="companyId" value="<?php echo $companyId;?>" >
                                            <input type="text" class="form-control" readonly="readonly" value="<?php echo get_company($companyId);?>" >

                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Job Post Date</label>
                                            <div class="help-block with-errors"></div>
                                            <input class="form-control" style="width:160px;" type="date"  id="postedDate" name="postedDate" value="<?php echo $postedDate;?>" required="true">
                                    </div>
                                    
                                    <div class="form-group">
                                            <label  class="control-label">Job Type</label>
                                            <div class="help-block with-errors"></div>
                                            <select class=" form-control" id="jobTypeId" name="jobTypeId" required="true">
                                                <?php echo get_job_type_options($jobTypeId);?>
                                            </select>
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Job Status</label>
                                            <div class="help-block with-errors"></div>
                                            <select class=" form-control" id="jobStatusId" name="jobStatusId" required="true">
                                                <?php echo get_job_status_options($jobStatusId);?>
                                            </select>
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Job Function</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="text" class="form-control" id="jobFunction" name="jobFunction" value="<?php echo $jobFunction;?>" >
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Skills Required</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="text" class="form-control" id="jobSkills" name="jobSkills" value="<?php echo $jobSkills;?>" required="true">
                                    </div>
                                    <fieldset >
                                    <legend>Job Location:</legend>
                                    <div class="form-group">
                                            <label  class="control-label">Address 1</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="text" class="form-control" id="locStreetAddress1" name="locStreetAddress1" value="<?php echo $locStreetAddress1;?>" >
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Address 2</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="text" class="form-control" id="locStreetAddress2" name="locStreetAddress2" value="<?php echo $locStreetAddress2;?>" >
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">City</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="text" class="form-control" id="locCity" name="locCity" value="<?php echo $locCity;?>" required="true">
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">State</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="text" class="form-control" id="locState" name="locState" value="<?php echo $locState;?>" required="true">
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Country</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="text" class="form-control" id="locCountry" name="locCountry" value="<?php echo $locCountry;?>" required="true">
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Zip Code</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="text" class="form-control" id="locZip" name="locZip" value="<?php echo $locZip;?>"  pattern="[0-9]{5}" title="Five digit zip code"  required="true">
                                    </div>
                                    </fieldset>
                                    <div class="form-group">
                                            <label  class="control-label">Job Description</label>
                                            <div class="help-block with-errors"></div>
                                            <textarea id="summernote"  class="form-control" name="jobDescription" required ><?php echo $jobDescription;?></textarea>
                                    </div>
                                        <button type="submit" name="<?php echo $btnName?>" class="btn btn-default btn-primary"><?php echo $btn?></button>
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
    <!-- include summernote css/js -->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
    
    <script>
    $(document).ready(function() {
        //restrict date to future date
        var dtToday = new Date();
        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();

        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();

        var minDate = year + '-' + month + '-' + day;
            $('#postedDate').attr('min', minDate);
     
    //validation
     addRequiredMark('frm_add_update_job');
       $('#frm_add_update_job')
        .validator({
            framework: 'bootstrap',
            excluded: [':disabled'],
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                description: {
                    validators: {
                        callback: {
                            message: 'The content is required and cannot be empty',
                            callback: function(value, validator, $field) {
                                var code = $('[name="jobDescription"]').summernote('code');
                                // <p><br></p> is code generated by Summernote for empty content
                                return (code !== '' && code !== '<p><br></p>');
                            }
                        }
                    }
                }
            }
        })
        .find('[name="jobDescription"]')
            .summernote({
                height: 400
            })
            .on('summernote.change', function(customEvent, contents, $editable) {
                // Revalidate the content when its value is changed by Summernote
                $('#frm_add_update_job').validator('revalidateField', 'jobDescription');
            })
            .end();
});
  </script>

</body>
</html>