<?php
/*
 * File Name: index.php
 * By: Dipali
 * Date: 03/01/2018
 */
$isSession=0;
require_once('admin/include/session.php');
require_once("admin/include/config.php");
require_once("admin/include/function.php");
$browserTitle="";
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
  <h1>Build Your Future</h1> 
  <h4>We offer <span><?php echo no_of_vaccancies();?></span> job vacancies right now!</h4>
  <!-- search form -->
  <form class="form-inline justify-content-center ">
  <div class="form-row">
     <div class="col-12">

      <input class="form-control form-control-lg mr-sm-2 min-width" type="search" placeholder="Job title, skills, or company" aria-label="Search">
      <select class="form-control form-control-lg mr-sm-2">
      	<option>City or State</option>
      	<option>New York</option>
      	<option>Los Angeles</option>
      	<option>Missouri</option>
      </select>
      <button class="btn btn-primary btn-lg my-sm-0" type="submit">FIND JOB</button>
      </div>
      </div>
    </form>

</div>
<!--=====================================FRONT SEARCH : ends here ======================== -->

<!--=====================================JOB CATEGORY : ends here ======================== -->
<div class="container-fluid job-category">
<div class="row align-items-center">
    <?php
    $sqlBusiness = "SELECT id, business_stream_name,font_icon FROM bussiness_stream WHERE is_removed='0' AND is_active='1' ";
    $resultBusiness = mysqli_query($db,$sqlBusiness);
    $countBusiness = mysqli_num_rows($resultBusiness);
    if($countBusiness>0){
        while($contentBusiness = mysqli_fetch_array($resultBusiness,MYSQLI_ASSOC)){
        
    ?>
    <div class="col text-center">
	    <i class="<?php echo $contentBusiness["font_icon"]?>" aria-hidden="true"></i>
	   <h4><?php echo $contentBusiness["business_stream_name"]?></h4>
	   <hr>
    </div>
    <?php } //close while($contentBusiness)
    } //close if($countBusiness)
    ?>
    
  </div>
</div>
<!--=====================================JOB CATEGORY : ends here ======================== -->

<!--=====================================LATEST JOB : starts here ======================== -->
<?php
 $sqlLatestJob = "SELECT job_post.`id`,job_post.`posted_date`, company.company_name,company.id as company_id, job_post.job_title, job_post.`loc_city`, job_post.`loc_state`,job_type.job_type
    FROM job_post INNER JOIN company ON job_post.company_id=company.id
    INNER JOIN job_type ON job_post.job_type_id=job_type.id
    WHERE job_post.is_active	='1' AND job_post.is_delete='0' AND job_post.job_status='2' AND company.is_removed='0' AND company.is_active='1' AND posted_date <= CURDATE() ORDER BY posted_date limit 10 ";
$resultLatestJob = mysqli_query($db,$sqlLatestJob);
$countLatestJob = mysqli_num_rows($resultLatestJob);
if($countLatestJob > 0) {
    $rowLatestjob=1;
    ?>
<div class="container latest-job text-center home-heading">
<h2>Latest Jobs</h2>
<hr>

<table class="table table-hover ">
  <thead>
    <tr class="tbl-latest-job-heading">
      <th >#</th>
      <th>Date</th>
      <th>Company</th>
      <th>Job Vacancy</th>
      <th>Location</th>
      <th>Employment</th>
    </tr>
  </thead>
  <tbody>
<?php
 while($contentLatestJob = mysqli_fetch_array($resultLatestJob,MYSQLI_ASSOC)){
?>
      <tr>
	<th scope="row"><?php echo $rowLatestjob;?></th>
	<td><?php echo $contentLatestJob["posted_date"]?></td>
	<td><a href="company-detail.php?id=<?php echo $contentLatestJob["company_id"]?>"><?php echo $contentLatestJob["company_name"]?></a></td>
	<td class="text-bold text-primary p"><a href="job-detail.php?id=<?php echo $contentLatestJob["id"]?>"><?php echo $contentLatestJob["job_title"]?></a></td>
	<td><?php echo get_city($contentLatestJob["loc_city"]).", ".get_state($contentLatestJob["loc_state"])?></td>
	<td><?php echo $contentLatestJob["job_type"]?></td>
	</tr>
    
      <?php
      $rowLatestjob++;
     }//close while
     ?>
      </tbody>
</table>

	<button class="btn btn-primary view-all">VIEW ALL</button>
</div>
<?php
}//close if
?>


	
    
  
<!--=====================================LATEST JOB : ends here ======================== -->

<!-- ==============================WHY PEOPLE CHOOSE US ================== -->
<div class="grey">

<div class="container home-heading why-people-chose-us-outer">
	<h2 class=" text-center ">Why People Choose Us</h2>
	<hr>
	
	<div class="row">
    <div class="col">

    <h4><i class="fa fa-user" aria-hidden="true"></i>&nbsp; VERIFIED EMPLOYERS</h4>

     <p>We pay a lot of attention to the employers we cooperate with and vacancies they submit to our job board.</p>
    </div>
    <div class="col">
     
         <h4><i class="fa fa-user" aria-hidden="true"></i>&nbsp; VERIFIED EMPLOYERS</h4>

     <p>We pay a lot of attention to the employers we cooperate with and vacancies they submit to our job board.</p>


    </div>
    <div class="col">
    <h4><i class="fa fa-user" aria-hidden="true"></i>&nbsp; VERIFIED EMPLOYERS</h4>

     <p>We pay a lot of attention to the employers we cooperate with and vacancies they submit to our job board.</p>
    </div>
  </div>

</div>
	
</div>
<!-- ==============================WHY PEOPLE CHOOSE US ================== -->

<!-- =============================TESTIMONIAL ================ -->
<?php
    $sqlTestimonial    = "SELECT id, name,text FROM testimonial WHERE is_active='1' ";
    $resultTestimonial = mysqli_query($db,$sqlTestimonial);
    $countTestimonial = mysqli_num_rows($resultTestimonial);
    if($countTestimonial>0){
    $rowTestimonial=1;
    ?>
<div class="container testimonial text-center home-heading">
<h2>Testimonials</h2>
<hr>


<div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="carousel slide" data-ride="carousel" id="quote-carousel">

                    <!-- Carousel Slides / Quotes -->
                    <div class="carousel-inner text-center">
                        <?php
                      while($contentTestimonial = mysqli_fetch_array($resultTestimonial,MYSQLI_ASSOC)){
                            $name=$contentTestimonial["name"];
                            $text = $contentTestimonial["text"];
    
                        ?>
                        <!-- Quote 1 -->
                        <div class="carousel-item <?php if($rowTestimonial==1){?> active <?php }?>">
                            <blockquote>
                                <div class="row justify-content-md-center">
                                    <div class="col-md-6 offset-md-3 rt-margin">
                                        <p><?php echo $text?></p>
                                        <small><?php echo $name;?></small>
                                    </div>
                                </div>
                            </blockquote>
                        </div>
                        <?php
                        $rowTestimonial++;
                      }//close while
                        ?>
                    </div>

                    <!-- Bottom Carousel Indicators -->
                     <!-- Carousel Buttons Next/Prev -->
                    <!-- Carousel Buttons Next/Prev -->
                    <a data-slide="prev" href="#quote-carousel" class="carousel-control-prev left carousel-control"><i class="fa fa-chevron-left"></i></a>
                    <a data-slide="next" href="#quote-carousel" class="carousel-control-next right carousel-control"><i class="fa fa-chevron-right"></i></a>
   

                </div>
            </div>
        </div>
        
    </div>

</div>
<?php } //close if($count)?>
<!-- ===========LATEST BLOG =============== -->
<?php
    $sqlLatestNews    = "SELECT article_id, title,brief_desc,media FROM article WHERE is_active='1' AND published_date <= CURDATE() ORDER BY published_date DESC limit 2  ";
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
                            $articleImg= TARGET_DIR."/".$contentLatestNews["media"];

                            $query_string = CURRENT_URL.'news/' . urlencode(strtolower($articleTitle));
                        ?>

		<div class="col-6">
			<div class="card" >
                            <?php if($contentLatestNews["media"]!=""){?>
  <img class="card-img-top" src="<?php echo $articleImg;?>" alt="<?php echo $articleTitle?>">
  <?php }?>
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

		

</div>
	
	<div class="text-center">
		<a href="news-listing/" class="btn btn-primary view-all">VIEW ALL</a>
	</div>
</div>
<?php
}//close if($count)?>
<!-- ================== FOOTER ======== -->
<?php include('partials/footer.inc.php')?>
</div> <!-- id:background -->
</body>
<script type="text/javascript" src="public/javascript/script.js"></script>
</html>