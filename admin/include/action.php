<?php
//action.php
require_once('session.php');
require_once("config.php");
require_once("function.php");

if(isset($_POST["action"]))
{
    //delete image from news
    if($_POST["action"] == "delete")
     {
      $query = "UPDATE article set media='' WHERE article_id = '".$_POST["image_id"]."'";
      if(mysqli_query($db, $query))
      {
            echo 'Image Deleted from Database';
      }else{
          echo "error in image deleting";
      }
     }
    //delete news
    if($_POST["action"] == "delete_news")
     {
      $query = "DELETE FROM article WHERE article_id = '".$_POST["article_id"]."'";
      if(mysqli_query($db, $query))
      {
            echo 'success';
      }else{
          echo "error";
      }
     }
     //delete news
    if($_POST["action"] == "chagne_news_status")
     {
        $sql = "SELECT `is_active` FROM article WHERE article_id='".$_POST["article_id"]."' ";
        $result = mysqli_query($db,$sql);
        $count = mysqli_num_rows($result);
        if($count == 1) {
           $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
           $is_active = $row['is_active'];
           if($is_active=='1'){
            $change_status="0";
            $html ="<i class='fa fa-ban fa-2x red'></i>";

           }else{
            $change_status="1";
            $html="<i class='fa fa-check-circle fa-2x green'></i>";
          
           }
           $query = "UPDATE article set is_active='$change_status' WHERE article_id = '".$_POST["article_id"]."'";
            if(mysqli_query($db, $query))
            {
                echo $html;
            }else{
                echo "error";
            }
            }
     }
     //category delete
     if($_POST["action"] == "delete_category")
     {
      $query = "UPDATE bussiness_stream set is_removed='1' WHERE id = '".$_POST["category_id"]."'";
      if(mysqli_query($db, $query))
      {
            echo 'success';
      }else{
          echo "error";
      }
     }
     //change category status
    if($_POST["action"] == "change_category_status")
     {
        $sql = "SELECT `is_active` FROM bussiness_stream WHERE id='".$_POST["category_id"]."' ";
        $result = mysqli_query($db,$sql);
        $count = mysqli_num_rows($result);
        if($count == 1) {
           $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
           $is_active = $row['is_active'];
           if($is_active=='1'){
            $change_status="0";
            $html ="<i class='fa fa-ban fa-2x red'></i>";

           }else{
            $change_status="1";
            $html="<i class='fa fa-check-circle fa-2x green'></i>";

           }
           $query = "UPDATE bussiness_stream set is_active='$change_status' WHERE id = '".$_POST["category_id"]."'";
            if(mysqli_query($db, $query))
            {
                echo $html;
            }else{
                echo "error";
            }
            }
     }
      //company delete
     if($_POST["action"] == "delete_company")
     {
      $query = "UPDATE company set is_removed='1' WHERE id = '".$_POST["company_id"]."'";
      if(mysqli_query($db, $query))
      {
            echo 'success';
      }else{
          echo "error";
      }
     }
     //change category status
    if($_POST["action"] == "change_company_status")
     {
        $sql = "SELECT `is_active` FROM company WHERE id='".$_POST["company_id"]."' ";
        $result = mysqli_query($db,$sql);
        $count = mysqli_num_rows($result);
        if($count == 1) {
           $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
           $is_active = $row['is_active'];
           if($is_active=='1'){
            $change_status="0";
            $html ="<i class='fa fa-ban fa-2x red'></i>";

           }else{
            $change_status="1";
            $html="<i class='fa fa-check-circle fa-2x green'></i>";

           }
           $query = "UPDATE company set is_active='$change_status' WHERE id = '".$_POST["company_id"]."'";
            if(mysqli_query($db, $query))
            {
                echo $html;
            }else{
                echo "error";
            }
            }
     }
      
}
?>