<?php
 $isSession=0;
$sideBarActive=0;

require_once('include/session.php');
require_once("include/config.php");
$browserTitle = "Chanage Password";
$error="";
$successMsg="";

if(isset($_POST["change_password"])) {
    $oldPassword = $_POST["oldPassword"];
    $newPassword = $_POST["newPassword"];
    $confrimPassword = $_POST["confirmPassword"];
    if($newPassword != $confrimPassword){
         $error = "Password and confirm password does not match!";
    }else{
           $sql = "SELECT id,is_active,email,password FROM user_account WHERE user_type_id='1' AND is_active=1 AND password='".$oldPassword."'";
           $result = mysqli_query($db,$sql);
           $count = mysqli_num_rows($result);
           if($count == 1) {
               $user = mysqli_fetch_array($result,MYSQLI_ASSOC);
               mysqli_query($db,"UPDATE user_account set password='" .$newPassword. "' WHERE user_type_id='1'");
               $successMsg ="Password changed successfully";
           }else {
              $error = "Old password is incorrect!";
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
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <?php include("partials/nav.inc.php");?>
        <?php include("partials/sidebar.inc.php");?>
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Change Password</h1>
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
                            Change Password
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" name="frm_change_password" id="frm_change_password" method="POST">
                                    <div class="form-group">
                                            <label>Old Password</label>
                                            <div class="help-block with-errors"></div>
                                            <input class="form-control" type="password" name="oldPassword" required>
                                    </div>
                                    <div class="form-group">
                                            <label>New Password</label>
                                            <div class="help-block with-errors"></div>
                                            <input class="form-control" type="password" name="newPassword" required>
                                    </div>
                                    <div class="form-group">
                                            <label>Re-enter new Password</label>
                                            <div class="help-block with-errors"></div>
                                            <input class="form-control" type="password" name="confirmPassword" required>
                                    </div>
                                    
                                    <button type="submit" name="change_password" class="btn btn-default btn-primary">Update</button>
                                        <button type="reset" class="btn btn-default btn-primary">Cancel</button>
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
        //add reqired field mark
       addRequiredMark('frm_change_password');
      //add validator
      $('#frm_change_password').validator();
});
</script>
</body>
</html>