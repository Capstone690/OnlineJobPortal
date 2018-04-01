<?php
/*
 * File Name: add-edit-news.php
 * By: Dipali
 * Date: 02/14/2018
 * Modifited BY: Dipali
 * Modification:Removed restriction on published date
 */
 $isSession=0;

require_once('include/session.php');
require_once("include/config.php");
require_once ("include/function.php");
$error="";
$successMsg="";
$sideBarActive=3;
//display content
$newsTitle        = "";
$brsrTitle        = "";
$briefDescription ="";
$media            = "";
$description      = "";
$publishedDate    = "";
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
//edit page
$browserTitle = "Edit News";
$btnName="edit_news";
$btn="Update";

// Get URL parameter

$id =  trim($_GET["id"]);
$sql = "SELECT `article_id`, `title`, `browser_title`, `description`, `media`, `brief_desc`,`published_date`
        FROM article WHERE article_id='".$id."' ";
$result = mysqli_query($db,$sql);
$count = mysqli_num_rows($result);
if($count == 1) {
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $articleId       = $row["article_id"];
      $newsTitle       = $row["title"];
      $brsrTitle       = $row["browser_title"];
      $briefDescription    = $row["brief_desc"];
      $publishedDate = $row["published_date"];
      $media           = $row["media"];
      $description     = $row["description"];

 }

}else{
    $browserTitle = "Add News";
    $btnName="add_news";
    $btn="Add";
}

if($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST["add_news"]) || isset($_POST["edit_news"]) || isset($_POST["delete"]))) {

    $newsTitle       = test_input($_POST["newsTitle"]);
    $brsrTitle       = test_input($_POST["browserTitle"]);
    $briefDescription    = test_input($_POST["briefDescription"]);
    $publishedDate = test_input($_POST["publishedDate"]);
    $imgName         = basename($_FILES["fileToUpload"]["name"]);
    $description     = mysqli_real_escape_string($db,$_POST["description"]);
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
           //check if add/edit page
           if(isset($_POST["add_news"])){
                        $sql = "SELECT `id`
                                FROM user_account WHERE user_type_id='1' ";
                        $result = mysqli_query($db,$sql);
                        $count = mysqli_num_rows($result);
                        if($count == 1) {
                              $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                              $userId = $row['id'];
                        }
                        $quer = "INSERT INTO article (title, browser_title,brief_desc,description,media,published_date,user_id,is_active) VALUES ('$newsTitle', '$brsrTitle', '$briefDescription', '$description', '$imgName','$publishedDate','$userId','1')";
                        $res = mysqli_query($db,$quer);
                        if($res){
                           if(!empty($imgName)){
                              move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                            }
                           $successMsg ="Record added successfully";
                           $_SESSION['success_message'] = $successMsg;

                           header("location:news.php");
                        }else{
                            $error="Error in adding record.";
                        }

           }else if(isset($_POST["edit_news"])){
                    $sql = "SELECT article_id FROM article WHERE article_id='".$id."'";
                    $result = mysqli_query($db,$sql);
                    $count = mysqli_num_rows($result);
                   if($count == 1) {
                        $quer = "UPDATE article set title='" .$newsTitle. "', browser_title='" .$brsrTitle. "', published_date='" .$publishedDate. "', brief_desc='" .$briefDescription. "', description='" .$description. "', modified_date='" .$date. "'";
                        if(!empty($imgName)){
                          $quer .= " ,  media='" .$imgName. "' ";
                          move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                        }
                        $quer .= " WHERE article_id='".$id."' ";
                        $res = mysqli_query($db,$quer);
                        if($res){

                           $successMsg ="Record updated successfully";
                           $_SESSION['success_message'] = $successMsg;

                           header("location:news.php");
                        }else{
                             $error = "Error in update";
                        }
               }else{
                    $error = "Record not found";
               }
               
           }
           elseif(isset($_POST["delete"])){
                 /*   $sql = "SELECT article_id FROM article WHERE article_id='".$id."'";
                    $result = mysqli_query($db,$sql);
                    $count = mysqli_num_rows($result);
                   if($count == 1) {
                        $quer = "UPDATE article set media='' WHERE article_id='".$id."'";
                        $res = mysqli_query($db,$quer);
                        if($res){

                         //  $successMsg ="Image deleted successfully";
                           // $_SESSION['success_message'] =$successMsg;
                            header('Location: add-edit-news.php?id='.$id);

                        }else{
                             $error = "Error in update";
                        }
               }else{
                    $error = "Record not found";
               }*/
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
                            News content
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-10">
                                    <form role="form"  data-toggle="validator" id="frm_add_update_news"  name="frm_add_update_news" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                            <label  class="control-label">News Title</label>
                                            <div class="help-block with-errors"></div>
                                            <input class="form-control" id="news_title" name="newsTitle" value="<?php echo $newsTitle;?>" required="true">
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Browser Title</label>
                                            <div class="help-block with-errors"></div>
                                            <input class="form-control" id="browser_title" name="browserTitle" value="<?php echo $brsrTitle;?>" required="true">
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Brief Description</label>
                                            <div class="help-block with-errors"></div>
                                            <textarea class="form-control" id="brief_description" name="briefDescription" required="true" ><?php echo $briefDescription;?></textarea>
                                    </div>
                                     <div class="form-group">
                                            <label>Media Image </label>
                                            <div class="help-block with-errors"></div>
                                            <div id="success"></div>
                                            <div id="delete_pic">

                                            <?php if($media){?>

                                             <div id="edit_profile_pic">
                                                <img src="<?php echo TARGET_DIR_FRONT."/".$media?>" />
                                            </div>
                                            <?php } ?>
                                            </div>
                                            <?php if($media){?>
                                            <button type="button" name="delete" class="btn-danger delete" id="<?php echo $id ?>">Remove Image</button>
                                            <?php }?>
                                            <input style="display: inline" class=" upload-img form-control" type="file" name="fileToUpload" >
                                            
                                    </div> 
                                     <div class="form-group">
                                            <label  class="control-label">Publish Date</label>
                                            <div class="help-block with-errors"></div>
                                            <input class="form-control" style="width:160px;" type="date"  id="publishedDate" name="publishedDate" value="<?php echo $publishedDate;?>" required="true">
                                    </div>   
                                    <div class="form-group">
                                            <label  class="control-label">Description</label>
                                            <div class="help-block with-errors"></div>
                                            <textarea id="summernote"  class="form-control" name="description" required ><?php echo $description;?></textarea>
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
    <!-- include summernote css/js -->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
    
    <script>
    $(document).ready(function() {
        //restrict date to future date
        /*var dtToday = new Date();
        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();

        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();

        var maxDate = year + '-' + month + '-' + day;
            $('#publishedDate').attr('min', maxDate);*/
     //image delete
     $(document).on('click', '.delete', function(){
           var image_id = $(this).attr("id");
             var action = "delete";

         if(confirm("Are you sure you want to remove this image from database?"))
          {
           // $( "#frm_add_update_news" ).submit();
              $.ajax({
                  url: 'include/action.php',
                  type: 'post',
                  data:{image_id:image_id, action:action},
                  success: function(data, status) {
                   // alert(data);
                     $("#delete_pic").html('');
                     $(".delete").css("display","none");
                     $('#success').html("<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>Image deleted Successfully</div>");

                  },
                  error: function(xhr, desc, err) {
                 //  alert(err);
                  }
                }); // end ajax call

          }else
          {
           return false;
          }
     });
    //validation
       addRequiredMark('frm_add_update_news');
       $('#frm_add_update_news')
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
                $('#frm_add_update_news').validator('revalidateField', 'description');
            })
            .end();
});
  </script>

</body>
</html>