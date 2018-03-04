<?php
/*
 * File Name: user-profile.php
 * By: Dipali
 * Date: 02/12/2018
 *
 */

require_once('include/session.php');
require_once("include/config.php");
$browserTitle = "Manage Content";
$error="";
$successMsg="";
$sideBarActive=2;
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
                        <h1 class="page-header">Manage Content</h1>
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
                        <div class="panel-heading">
                            Manage Content
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Page</th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //display data
                                        $sql = "SELECT menu_header_id,menu_header_text FROM menu_header ";
                                        $result = mysqli_query($db,$sql);
                                        $count = mysqli_num_rows($result);
                                        $rowNo=1;
                                        if($count > 0) {
                                              while($content = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                                $menuHeaderId = $content["menu_header_id"];
                                                $menuHeaderText = $content["menu_header_text"];
                                                ?>
                                         <tr>
                                            <td><?php echo $rowNo;?></td>
                                            <td><?php echo $menuHeaderText;?></td>
                                            <td><a href="edit-content.php?menu_id=<?php echo $menuHeaderId; ?>"><i class="fa fa-edit fa-2x"></i></a></td>
                                        </tr>
                                       
                                        <?php
                                                $rowNo++;
                                              }

                                         }else{
                                             ?>
                                        <tr>
                                            <td colspan="3">No records found</td>
                                        </tr>
                                       
                                             <?php
                                         }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
            
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
</body>
</html>