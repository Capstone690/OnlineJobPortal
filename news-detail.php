<?php
/*
 * File Name: index.php
 * By: Dipali
 * Date: 03/10/2018
 */
$isSession=0;
require_once('admin/include/session.php');
require_once("admin/include/config.php");
require_once("admin/include/function.php");
$browserTitle="News Detail";
if(isset($_GET['title']))
{
    // Do something
$url=$_GET['title'];
$title = urldecode($url);

//echo $title;//
    $sqlNews    = "SELECT article_id, title,browser_title,brief_desc,media,description FROM article WHERE is_active='1' AND LOWER(title)='".$title."'";
    $resultNews = mysqli_query($db,$sqlNews);
    $countNews = mysqli_num_rows($resultNews);
    if($countNews>0){
    $contentNews = mysqli_fetch_array($resultNews,MYSQLI_ASSOC);
    $articleTitle = $contentNews["title"];
    $articleDesc = $contentNews["description"];
    $articleImg= CURRENT_URL.TARGET_DIR."/".$contentNews["media"];
    $browserTitle=$articleTitle;
    }

}else{
    //page not found
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('partials/header.inc.php')?>
</head>
<body>
<div>
<!--=====================================NAVIGATION : starts here ======================== -->
<?php include('partials/nav.inc.php')?>
	
<!--=====================================NAVIGATION : ends here ======================== -->

<!--=====================================FRONT SEARCH : starts here ======================== -->

<div class="jumbotron text-center paralsec">
  <h1>News</h1>
  <h5>lorem ipsum lorem ipsum lorem ipsum</h5>
  </div>
<!-- ===========LATEST BLOG =============== -->
<div class="grey">

<div class="container home-heading latest-news">
	<h2 class=" text-center "><?php echo $articleTitle;?></h2>
	<hr>

	<div class="row">
		<div class="col-12">
                    <?php if($contentNews["media"]!=""){
                                ?>

                     <img class="card-img-top" src="<?php echo $articleImg;?>" alt="<?php echo $articleTitle?>">
                     <?php }?>
  <div class="card-body">
    <p class="card-text"><?php echo $articleDesc;?>

</p>

  </div>
		</div>
		


         <a class="btn btn-primary" href="<?php echo CURRENT_URL?>news-listing/">Back</a>
	</div>
        



</div>

</div>

<!-- ================== FOOTER ======== -->
<?php include('partials/footer.inc.php')?>
</div> <!-- id:background -->
</body>
<script type="text/javascript" src="<?php echo CURRENT_URL?>public/javascript/script.js"></script>
</html>