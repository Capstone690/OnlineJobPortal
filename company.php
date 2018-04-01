<?php
/*
 * File Name: company.php
 * By: Dipali
 * Date: 03/25/2018
 * Description: Company details
 */
$isSession=1;
require_once('admin/include/session.php');
require_once("admin/include/config.php");
require_once("admin/include/function.php");
$browserTitle="Compnay";
$changeNavBar=True;
//check if user is logged in
if(isset($_SESSION['login_user_front'])){
     $userEmail=$_SESSION['login_user_front'];
    //check if record exist
    $sql = "SELECT user_account.`id`, `first_name`, `last_name`, `email`, `contact_number`,`user_image`,user_type.user_type_name
        FROM user_account INNER JOIN user_type ON user_account.user_type_id=user_type.id WHERE email='".$userEmail."' AND is_delete='0' AND is_approved='1' AND is_active='1' ";
$result = mysqli_query($db,$sql);
$count = mysqli_num_rows($result);
if($count == 1) {
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    if($row["user_type_name"]=='J'){
       $userIdDb          = $row["id"];

    }
    }
}else{
   header("location:login.php");die;

}
if(isset($_GET['title']))
{
    // Do something
$url=$_GET['title'];
$title = urldecode($url);
 $sqlCompany    = "SELECT company.id as company_id,company.company_name,company.profile_description,company.establishment_date,company.company_website_url,bussiness_stream.business_stream_name
                     FROM company INNER JOIN bussiness_stream ON company.business_stream_id=bussiness_stream.id
                     WHERE company.is_removed=0 AND company.is_active=1 AND company.company_name='".$title."' AND bussiness_stream.is_active=1 AND bussiness_stream.is_removed=0";
    $resultCompany = mysqli_query($db,$sqlCompany);
    $countCompany = mysqli_num_rows($resultCompany);
    if($countCompany>0){
        $contentCompany = mysqli_fetch_array($resultCompany,MYSQLI_ASSOC);
        $companyId=$contentCompany["company_id"];
        $companyName=$contentCompany["company_name"];
        $companyprofileDes=$contentCompany["profile_description"];
        $companyEstablishedDate=$contentCompany["establishment_date"];
        $companyWebsiteUrl=$contentCompany["company_website_url"];
        $bussinessStreamName = $contentCompany["business_stream_name"];
        }
    }else{
        //page not found
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include('partials/header.inc.php')?>
    <style>
        body { padding-top: 120px; }

    </style>
     <!-- Custom styles for this template -->
    <link href="<?php echo CURRENT_URL?>public/css/thumbnail-gallery.css" rel="stylesheet">
</head>
<body>
<div>
<!--=====================================NAVIGATION : starts here ======================== -->
<?php include('partials/nav.inc.php')?>
<div class="container home-heading latest-news ">
    <div style="margin-bottom:20px;" class="card ">
	<h4 class="card-header">
            <?php echo $companyName;?><br>
            <span style="font-size:15px;" class="text-muted"><?php echo $bussinessStreamName?></span>
        </h4>
        <div class="card-body">
            <h5 class="card-title">About Us</h5>
            <p class="card-text"><?php echo $companyprofileDes?></p>
            <h5 class="card-title">Company details</h5>
            <p>
                <span>Website</span><br>
                <a href="<?php echo $companyWebsiteUrl?>" target="_blank"><?php echo $companyWebsiteUrl?></a><br><br>
                <span>Establishment Date</span><br>
                <span><?php $companyEstablishedDate?></span>

            </p>

        </div>
    </div><!-- //.card-->
    
</div>
<!-- image gallery-->
<?php
  $sql = "SELECT `id`,`company_id`,`company_image` FROM company_image WHERE company_id='".$companyId."' AND is_active='1' ";
  $result = mysqli_query($db,$sql);
  $count = mysqli_num_rows($result);
  if($count > 0) {
                                      
?>
<div class="container">

      <!--<h1 class="my-4 text-center text-lg-left">Thumbnail Gallery</h1>-->

      <div class="row text-center text-lg-left">
<?php
while($content = mysqli_fetch_array($result,MYSQLI_ASSOC)){
     $galleryId = $content["id"];
     $galleryImage = $content["company_image"];
?>
        <div class="col-lg-3 col-md-4 col-xs-6">
          <a href="#" class="d-block mb-4 h-100">
            <img class="img-fluid img-thumbnail" src="<?php echo CURRENT_URL.TARGET_DIR."/".$galleryImage;?>" alt="">
          </a>
        </div>
<?php }//close while?>
      </div>

    </div>
    <!-- /.container -->
<?php }//close if(count)?>
<!-- ================== FOOTER ======== -->
<?php include('partials/footer.inc.php')?>
</div> <!-- id:background -->
</body>
<script type="text/javascript" src="<?php echo CURRENT_URL?>public/javascript/script.js"></script>
</html>