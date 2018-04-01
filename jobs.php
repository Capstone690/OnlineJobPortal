<?php
/*
 * File Name: jobs.php
 * By: Dipali
 * Date: 03/23/2018
 * Description: Job listing page
 */
$isSession=1;
require_once('admin/include/session.php');
require_once("admin/include/config.php");
require_once("admin/include/function.php");
$browserTitle="Jobs listing";
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
$currentpage=1;
// find out how many rows are in the table
$sqlLatestJobsTotal    = "SELECT job_post.`id`,job_post.`posted_date`, company.company_name,company.id as company_id, job_post.job_title, job_post.`loc_city`, job_post.`loc_state`,job_type.job_type
    FROM job_post INNER JOIN company ON job_post.company_id=company.id
    INNER JOIN job_type ON job_post.job_type_id=job_type.id
    WHERE job_post.is_active	='1' AND job_post.is_delete='0' AND job_post.job_status='2' AND company.is_removed='0' AND company.is_active='1' AND posted_date <= CURDATE() ORDER BY posted_date ";
$resultLatestJobsTotal = mysqli_query($db,$sqlLatestJobsTotal);
$numrows = mysqli_num_rows($resultLatestJobsTotal);
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
    <style>
        body { padding-top: 120px; }

    </style>
    
</head>
<body>
<div>
<!--=====================================NAVIGATION : starts here ======================== -->
<?php include('partials/nav.inc.php')?>
	
<!--=====================================NAVIGATION : ends here ======================== -->

<!--=====================================FRONT SEARCH : starts here ======================== -->

<!-- ===========LATEST BLOG =============== -->
<?php
    $sqlLatestJobs    = "SELECT job_post.`id`,job_post.`posted_date`, company.company_name,company.id as company_id, job_post.job_title, job_post.`loc_city`, job_post.`loc_state`,job_type.job_type
    FROM job_post INNER JOIN company ON job_post.company_id=company.id
    INNER JOIN job_type ON job_post.job_type_id=job_type.id
    WHERE job_post.is_active	='1' AND job_post.is_delete='0' AND job_post.job_status='2' AND company.is_removed='0' AND company.is_active='1' AND posted_date <= CURDATE() ORDER BY posted_date LIMIT $offset, $rowsperpage";
    $resultLatestJobs = mysqli_query($db,$sqlLatestJobs);
    $countLatestJobs = mysqli_num_rows($resultLatestJobs);
    if($countLatestJobs>0){
    $rowLatestJobs=1;
    ?>


<div class="container home-heading  ">
                  <div class="">
            <nav class="navbar navbar-light bg-light navbar-expand-lg ">
                <!--<span class="navbar-text">
                Search Jobs
                </span>-->
  <form class="form-inline row" id="search" name="frm_search" style="width:100%">
                
    <!-- search by company -->
      <div class="col">
     <select class="custom-select" id="company" name="company">
     <?php echo get_company_search($companyId);?>
    </select>
  </div>
  <!-- search by Type -->
  <div class="col">
     <select class="custom-select" id="job_type" name="job_type">
    <?php echo get_job_type_search();?>
    </select>
  </div>
  <!-- search by location -->
  <div class="col">
     <select class="custom-select" id="city" name="city">
   <?php echo get_city_search();?>
    </select>
  </div>
  <!-- Date postedd-->
  <div class="col">
     <select class="custom-select" id="posted_date" name="posted_date">
    <option selected>Job Posted</option>
    <option value="1">Last Day</option>
    <option value="2">Last Week</option>
    <option value="3">Last 2 Weeks</option>
    <option value="3">Last Month</option>
    <option value="3">Any Time</option>
    </select>
  </div>
  <!-- skills-->
  <div class="col">
    <input class="form-control" type="search" placeholder="Job Title or Skills" id="keyword" name="keyword" aria-label="Search">
  </div>
  <!-- seach button -->
 <div class="col-1">
    <button class="btn btn-outline-primary " type="submit" name="btn_search" id="btn_search">Search</button>
 </div>
  </form>
</nav>
                          </div>

        <div class="row">
            <?php
                      while($contentLatestJobs = mysqli_fetch_array($resultLatestJobs,MYSQLI_ASSOC)){
                            $jobId=$contentLatestJobs["id"];
                            $jobTitle = $contentLatestJobs["job_title"];
                            $companyId=$contentLatestJobs["company_id"];
                            $companyName = $contentLatestJobs["company_name"];
                           // $articleImg= CURRENT_URL.TARGET_DIR."/".$contentLatestNews["media"];
                            $jobUrl = CURRENT_URL.'job-detail/' . $jobId;
                            $companyUrl = CURRENT_URL.'company/' . urlencode(strtolower($companyName));
                            $jobLocation = $contentLatestJobs["loc_city"].", ".$contentLatestJobs["loc_state"];
                            $daysPassed=timeago($contentLatestJobs["posted_date"]);
                            $jobApplicationUrl=CURRENT_URL.'apply/'.$jobId;
                        ?>

		<div style="margin:1% 0" class="col-3 card-group">
			<div class="card" >
                            
  <div class="card-body">
    <h5 class="card-title"><a style="color:#212529" href="<?php echo $jobUrl?>"><?php echo $jobTitle?></a></h5>
     <h6 class="card-subtitle mb-2 "><a href="<?php echo $companyUrl?>" target="_blank" class="text-muted"><?php echo $companyName?></a></h6>
   
    <p class="card-text">
        <?php echo $jobLocation;?>
    </p>
   <p><small class="text-muted"><?php echo $daysPassed;?></small></p>
   <a class="btn btn-primary btn-block btn-sm  active" href="<?php echo $jobApplicationUrl;?>" role="button">Apply</a>
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
                <a class='page-link' href=".CURRENT_URL."jobs/$prevpage tabindex='-1'>Previous</a>
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
                <a class='page-link' href=".CURRENT_URL."jobs/$x >$x</a>
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
                <a class='page-link' href=".CURRENT_URL."jobs/$nextpage >Next</a>
            </li>";
  
?>
</ul>
      </nav>
<!--****** end build pagination links ******/ -->

</div>

<?php
}//close if($count)?>
<!-- ================== FOOTER ======== -->
<?php include('partials/footer.inc.php')?>
</div> <!-- id:background -->
</body>
<script type="text/javascript" src="<?php echo CURRENT_URL?>public/javascript/script.js"></script>
</html>