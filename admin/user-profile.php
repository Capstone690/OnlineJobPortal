<?php
/*
 * File Name: user-profile.php
 * By: Dipali
 * Date: 02/09/2018
 *
 */
require_once('include/session.php');
require_once("include/config.php");
require_once ("include/function.php");
$browserTitle = "Update Profile";
$error="";
$successMsg="";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_profile"])) {

    $displayName = test_input($_POST["displayName"]);
    // check if name only contains letters and whitespace
    if (!letter_space($displayName)) {
      $error = "Only letters and white space allowed in first name";
    }
    /*$lastName = test_input($_POST["lastName"]);
    // check if name only contains letters and whitespace
    if (!letter_space($lastname)) {
      $error = "Only letters and white space allowed in last name";
    }*/
    $email = test_input($_POST["email"]);

    $imgName= basename($_FILES["fileToUpload"]["name"]);
    if(!empty($imgName)){
        $target_file = TARGET_DIR ."/".$imgName ;
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

                
           $sql = "SELECT id FROM user_account WHERE user_type_id='1' AND is_active=1 ";
           $result = mysqli_query($db,$sql);
           $count = mysqli_num_rows($result);
           if($count == 1) {
               $quer = "UPDATE user_account set displayName='" .$displayName. "', email='" .$email. "'";
               
                    if(!empty($imgName)){
                      $quer .= " ,  user_image='" .$imgName. "' ";
                      move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                    }
                    $quer .= " WHERE user_type_id='1' ";
                    $res = mysqli_query($db,$quer);
                    if($res){
               
                       $successMsg ="Record updated successfully";
                    }
               }else{
                    $error = "Error in update";
               }
               
           }else {
              $error = "Record not found!";
           }
  
}
//display data
$sql = "SELECT id,displayName,email,user_image FROM user_account WHERE user_type_id='1' AND is_active=1 ";
$result = mysqli_query($db,$sql);
$count = mysqli_num_rows($result);
if($count == 1) {
      $user = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $displayName = $user["displayName"];
      $email = $user["email"];
      $userImage = $user["user_image"];

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
                        <h1 class="page-header">User Profile</h1>
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
                            Update User Profile
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" id="frm_update_user_profile" name="frm_update_user_profile" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                            <label>Display Name</label>
                                            <div class="help-block with-errors"></div>
                                            <input class="form-control" name="displayName" value="<?php echo $displayName?>" required>
                                    </div>
                                    <div class="form-group">
                                            <label>Email Address</label>
                                            <div class="help-block with-errors"></div>
                                            <input class="form-control" type="email" name="email" value="<?php echo $email?>"  required>
                                    </div>
                                    <div class="form-group">
                                            <label>Profile Picture</label>
                                            <div id="edit_profile_pic">
                                                <img src="<?php echo TARGET_DIR."/".$userImage?>" />
                                            </div>
                                            <input type="file" name="fileToUpload" >
                                        </div>
                                    <button type="submit" name="update_profile" class="btn btn-default btn-primary">Update</button>
                                        <a href="index.php" class="btn btn-default btn-primary">Cancel</a>
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
      addRequiredMark("frm_update_user_profile");
      $('#frm_update_user_profile').validator()
});
</script>
</body>
</html>