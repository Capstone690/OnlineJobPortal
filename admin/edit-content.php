<?php
/*
 * File Name: edit-content.php
 * By: Dipali
 * Date: 02/12/2018
 *
 */
require_once('include/session.php');
require_once("include/config.php");
require_once ("include/function.php");
$browserTitle = "Edit Content";
$error="";
$successMsg="";
$sideBarActive=2;
//display content
$pageTitle       = "";
$brsrTitle     = "";
$mtKeywords    = "";
$metaDescription = "";
$media           = "";
$description     = "";

// Check existence of id parameter before processing further
if(isset($_GET["menu_id"]) && !empty(trim($_GET["menu_id"]))){
// Get URL parameter
$id =  trim($_GET["menu_id"]);
$sql = "SELECT `page_id`, `page_title`, `browser_title`, `keyword`, `meta_description`, `description`, `media`
        FROM pages WHERE menu_header_id='".$id."' ";
$result = mysqli_query($db,$sql);
$count = mysqli_num_rows($result);
if($count == 1) {
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $pageId       = $row["page_id"];
      $pageTitle       = $row["page_title"];
      $brsrTitle       = $row["browser_title"];
      $mtKeywords    = $row["keyword"];
      $metaDescription = $row["meta_description"];
      $media           = $row["media"];
      $description     = $row["description"];

 }

}else{
// URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();

}
 
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_content"])) {

    $pageTitle       = test_input($_POST["pageTitle"]);
    $brsrTitle       = test_input($_POST["browserTitle"]);
    $mtKeywords    = test_input($_POST["metaKeywords"]);
    $metaDescription = test_input($_POST["metaDescription"]);
    $imgName         = basename($_FILES["fileToUpload"]["name"]);
    $description     = test_input($_POST["description"]);
    $date            = date('Y-m-d h:i:sa');
    
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
           $sql = "SELECT page_id FROM pages WHERE menu_header_id='".$id."'";
           $result = mysqli_query($db,$sql);
           $count = mysqli_num_rows($result);
           if($count == 1) {
               $quer = "UPDATE pages set page_title='" .$pageTitle. "', browser_title='" .$brsrTitle. "', keyword='" .$mtKeywords. "', meta_description='" .$metaDescription. "', description='" .$description. "', modified_date='" .$date. "'";

                    if(!empty($imgName)){
                      $quer .= " ,  media='" .$imgName. "' ";
                      move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                    }
                    $quer .= " WHERE page_id='".$pageId."' ";
                    $res = mysqli_query($db,$quer);
                    if($res){

                       $successMsg ="Record updated successfully";
        	       $_SESSION['success_message'] = $successMsg;

                       header("location:content.php");
                    }
               }else{
                    $error = "Error in update";
               }

           }else {
              $error = "Record not found!";
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
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <?php include("partials/nav.inc.php");?>
        <?php include("partials/sidebar.inc.php");?>
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Update Content</h1>
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
                    <?php if($successMsg){?>
                    <div class="alert alert-success fade in">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <?php echo $successMsg?>
                    </div>
                    <?php } ?>
                        <div class="panel-heading">
                            Update page content
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-10">
                                    <form role="form"  data-toggle="validator" id="frm_update_content"  name="frm_update_content" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                            <label class="control-label" for="page_title">Page Title</label>
                                            <div class="help-block with-errors"></div>

                                            <input class="form-control" id="page_title" name="pageTitle" value="<?php echo $pageTitle;?>" required="true">
                                    </div>
                                      
                                    <div class="form-group">
                                            <label class="control-label">Browser Title</label>
                                            <div class="help-block with-errors"></div>

                                            <input class="form-control" name="browserTitle" value="<?php echo $brsrTitle;?>" required>
                                    </div>
                                    <div class="form-group">
                                            <label class="control-label">Meta Keywords</label>
                                            <div class="help-block with-errors"></div>

                                            <input class="form-control" name="metaKeywords" value="<?php echo $mtKeywords;?>" required>
                                    </div>
                                    <div class="form-group">
                                            <label class="control-label">Meta Description</label>
                                            <div class="help-block with-errors"></div>

                                            <textarea class="form-control" style="resize:none" rows="5" col="10" name="metaDescription" required><?php echo $metaDescription;?></textarea>
                                    </div>
                                     <div class="form-group">
                                            <label >Background Image</label>
                                            <div id="edit_profile_pic">
                                                <img src="<?php echo TARGET_DIR_FRONT."/".$media?>" />
                                            </div>
                                            <input class="form-control" type="file" name="fileToUpload" >
                                    </div>   
                                    <div class="form-group">
                                            <label class="control-label">Description</label>
                                            <div class="help-block with-errors"></div>

                                            <textarea id="summernote"  class="form-control" name="description" required ><?php echo $description;?></textarea>
                                    </div>
                                       <button type="submit" name="edit_content" class="btn btn-default btn-primary">Update</button>
                                        <button type="button" id="back" class="btn btn-default btn-primary">Cancel</button>
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
       addRequiredMark('frm_update_content');
       $('#frm_update_content')
        .validator({
            framework: 'bootstrap',
            excluded: [':disabled'],
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                page_title: {
                    validators: {
                        notEmpty: {
                            message: 'The title is required and cannot be empty'
                        }
                    }
                },
                description: {
                    validators: {
                        callback: {
                            message: 'The content is required and cannot be empty',
                            callback: function(value, validator, $field) {
                                var code = $('[name="description"]').summernote('code');
                                // <p><br></p> is code generated by Summernote for empty content
                                return (code !== '' && code !== '<p><br></p>');
                            }
                        }
                    }
                }
            }
        })
        .find('[name="description"]')
            .summernote({
                height: 400
            })
            .on('summernote.change', function(customEvent, contents, $editable) {
                // Revalidate the content when its value is changed by Summernote
                $('#frm_update_content').validator('revalidateField', 'description');
            })
            .end();
});
  </script>

</body>
</html>