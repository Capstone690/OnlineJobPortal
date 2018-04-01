<?php
 $isSession=0;

   include('include/session.php');
   require_once("include/config.php");
$browserTitle = "Dashboard";
$sideBarActive=1;
      
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
                        <h1 class="page-header">Welcome Admin</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
     <!--#wrapper -->
<?php include("partials/footer.inc.php");?>
</body>
</html>