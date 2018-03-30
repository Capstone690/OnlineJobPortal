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
$browserTitle="News";
$currentpage=1;
// find out how many rows are in the table
$sqlLatestNewsTotal    = "SELECT article_id FROM article WHERE is_active='1' AND published_date <= CURDATE() ORDER BY published_date ";
$resultLatestNewsTotal = mysqli_query($db,$sqlLatestNewsTotal);
$numrows = mysqli_num_rows($resultLatestNewsTotal);
// number of rows to show per page
$rowsperpage = 10;
// find out total pages
$totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
   // cast var as int
   $currentpage = (int) $_GET['page'];
} else {
   // default page num
   $currentpage = 1;
} // end if
// if current page is greater than total pages...
if ($currentpage > $totalpages) {
   // set current page to last page
   $currentpage = $totalpages;
} // end if
// if current page is less than first page...
if ($currentpage < 1) {
   // set current page to first page
   $currentpage = 1;
} // end if

// the offset of the list, based on current page
$offset = ($currentpage - 1) * $rowsperpage;

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
  <h1><?php echo $browserTitle?></h1>
  <h5>lorem ipsum lorem ipsum lorem ipsum</h5>
  </div>
<!-- ===========LATEST BLOG =============== -->
<?php
    $sqlLatestNews    = "SELECT article_id, title,brief_desc,media FROM article WHERE is_active='1' AND published_date <= CURDATE() ORDER BY published_date LIMIT $offset, $rowsperpage";
    $resultLatestNews = mysqli_query($db,$sqlLatestNews);
    $countLatestNews = mysqli_num_rows($resultLatestNews);
    if($countLatestNews>0){
    $rowLatestNews=1;
    ?>

<div class="grey">

<div class="container home-heading latest-news">
	<h2 class=" text-center ">Latest News</h2>
	<hr>

	<div class="row">
            <?php
                      while($contentLatestNews = mysqli_fetch_array($resultLatestNews,MYSQLI_ASSOC)){
                            $articleId=$contentLatestNews["article_id"];
                            $articleTitle = $contentLatestNews["title"];
                            $articleBriefDesc = $contentLatestNews["brief_desc"];
                            $articleImg= CURRENT_URL.TARGET_DIR."/".$contentLatestNews["media"];
                            $query_string = CURRENT_URL.'news/' . urlencode(strtolower($articleTitle));
                            //echo "ENCODE".$encode = urlencode($articleTitle);
                            //echo "DECODE".$decode = urldecode($encode);

                        ?>

		<div style="margin:1% 0" class="col-6">
			<div class="card" >
                            <?php if($contentLatestNews["media"]!=""){
                                ?>
                             <img class="card-img-top" src="<?php echo $articleImg;?>" alt="<?php echo $articleTitle?>">
 
                            <?php
                            }?>
  <div class="card-body">
    <h5 class="card-title"><?php echo $articleTitle?></h5>
    <p class="card-text"><?php echo $articleBriefDesc?></p>
   <div class="text-center">
       <a href="<?php echo $query_string;?>" class="btn btn-primary">view</a>

</div>
  </div>
</div>
		</div>
            <?php }//close while?>



	</div>
        <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
        <?php
        /******  build the pagination links ******/
        $range = $numrows;
        $classActive='';
        $prevDisable='';
        $prevpage=0;
// if not on page 1, don't show back links
if ($currentpage > 1) {
   // get previous page num
   $prevpage = $currentpage - 1;
 
}else{$prevDisable='disabled';} // end if
  // show < link to go back to 1 page
    echo "<li class='page-item $prevDisable'>
                <a class='page-link' href=".CURRENT_URL."news-listing/$prevpage tabindex='-1'>Previous</a>
            </li>";
// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
          $classActive='active';
        // echo " [<b>$x</b>] ";
      // if not current page...
      } else {
         // make it a link
         $classActive='';
      } // end else
          echo "<li class='page-item $classActive'>
                <a class='page-link' href=".CURRENT_URL."news-listing/$x >$x</a>
            </li>";
      
   } // end if
} // end for

// if not on last page, show forward and last page links
$nextDisable='';
$nextpage=0;
if ($currentpage != $totalpages) {
   // get next page
   $nextpage = $currentpage + 1;
    // echo forward link for next page
   
}else{$nextDisable='disabled'; } // end if
echo "<li class='page-item $nextDisable'>
                <a class='page-link' href=".CURRENT_URL."news-listing/$nextpage >Next</a>
            </li>";
  
?>
</ul>
</nav>
<!--****** end build pagination links ******/ -->

</div>

	
</div>
<?php
}//close if($count)?>
<!-- ================== FOOTER ======== -->
<?php include('partials/footer.inc.php')?>
</div> <!-- id:background -->
</body>
<script type="text/javascript" src="<?php echo CURRENT_URL?>public/javascript/script.js"></script>
</html>