<?php
/*
 * File Name: news.php
 * By: Dipali
 * Date: 02/13/2018
 *
 */

require_once('include/session.php');
require_once("include/config.php");
$browserTitle = "Manage News";
$error="";
$successMsg="";
$sideBarActive=3;
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
                        <h1 class="page-header">Manage News</h1>
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
                        <div id="messages"></div>
                        <div class="panel-heading">
                            Manage News
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        <div class="text-right" style="margin-bottom:10px;">
                            <a class="btn btn-primary " href="add-edit-news.php">Add News</a>
                        </div>
                             <form role="form"  id="frm_news"  name="frm_news" method="POST" enctype="multipart/form-data">
                                   
                       <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Published date</th>
                                        <th>Status</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        //display data
                                        $sql = "SELECT `article_id`, `title`, `published_date`,is_active FROM article ";
                                        $result = mysqli_query($db,$sql);
                                        $count = mysqli_num_rows($result);
                                        $rowNo=1;
                                        if($count > 0) {
                                              while($content = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                                                $articleId = $content["article_id"];
                                                $title = $content["title"];
                                                $publishedDate = $content["published_date"];
                                                $isActive = $content["is_active"];
                                                $class = ($rowNo%2==0)?"even":"odd";
                                                
                                                ?>
                                    <tr class="<?php echo $class?> gradeX" id="record_<?php echo $articleId?>">
                                        <td><?php echo $title;?></td>
                                        <td><?php echo $publishedDate;?></td>
                                        <td class="text-center">
                                            <button type="button" name="status" id="status_<?php echo $articleId?>" class=" status no-border" data-id="<?php echo $articleId ?>">
                                            <?php if($isActive==='1'){
                                                ?>
                                            <i class="fa fa-check-circle fa-2x green"></i>
                                            <?php
                                            }else{
                                                ?>
                                            <i class="fa fa-ban fa-2x red"></i>
                                            <?php
                                            }?>
                                            </button>
                                        </td>
                                        <td class="center text-center"><a href="add-edit-news.php?id=<?php echo $articleId;?>"><i class="fa fa-edit fa-2x"></i></a></td>
                                        <td class="center text-center"><button type="button" name="delete" class=" btn-danger delete no-border" id="<?php echo $articleId ?>"><i class="fa fa-trash fa-2x"></button></a></td>
                                    </tr>
                                    <?php
                                                $rowNo++;
                                              }

                                         }else{
                                             ?>
                                     <tr class="even gradeX">
                                        <td colspan="5">No records  found</td>
                                    </tr>
                                      <?php
                                         }
                                        ?>
                                     
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                            <!-- /.table-responsive -->
                             </form>
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
    <!-- DataTables JavaScript -->
    <script src="public/javascript/datatables/js/jquery.dataTables.min.js"></script>
    <script src="public/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="public/datatables-responsive/dataTables.responsive.js"></script>
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true,
            stateSave: true,
            "aoColumns": [null,null,{ "bSortable": false },{ "bSortable": false },{ "bSortable": false }]
        });
        $("#dataTables-example_filter").css("text-align","right");
        $("#dataTables-example_paginate").css("text-align","right");
        //delete record
     $(document).on('click', '.delete', function(){
             var article_id = $(this).attr("id");
             var action = "delete_news";

         if(confirm("Are you sure you want to remove this record from database?"))
          {
           // $( "#frm_add_update_news" ).submit();
              $.ajax({
                  url: 'include/action.php',
                  type: 'post',
                  data:{article_id:article_id, action:action},
                  success: function(data, status) {
                     $("#status"+article_id).html("");
                     $('#messages').html("<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>Record deleted Successfully</div>");
                      //location.reload();
                  },
                  error: function(xhr, desc, err) {
                     $('#messages').html("<div class='alert alert-error fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>Error in record deletion.</div>");

                  }
                }); // end ajax call

          }else
          {
           return false;
          }
         });

         //change status
     $(document).on('click', '.status', function(){
             var article_id = $(this).attr("data-id");
             var action = "chagne_news_status";

         if(confirm("Are you sure you want to change status?"))
          {
           // $( "#frm_add_update_news" ).submit();
              $.ajax({
                  url: 'include/action.php',
                  type: 'post',
                  data:{article_id:article_id, action:action},
                  success: function(data, status) {
                     $("#status_"+article_id).html(data);
                     $('#messages').html("<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>Status changed Successfully</div>");
                      //location.reload();
                  },
                  error: function(xhr, desc, err) {
                     $('#messages').html("<div class='alert alert-error fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>Error in status updation.</div>");

                  }
                }); // end ajax call

          }else
          {
           return false;
          }
         });
    });
    </script>

</body>
</html>