<?php
/*
 * File Name: about-us.php
 * By: Dipali
 * Date: 03/15/2018
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
  <h1>About Us</h1>
  <h5>lorem ipsum lorem ipsum lorem ipsum</h5>
  </div>
<!-- =============================Who We Are ============================= -->
<div class="">

<div class="container home-heading latest-news">
	
	<div class="row">
		<div class="col-6">
                    <h2 class=" text-left ">Who We Are</h2><hr class=" text-left left-hr ">
                    <p>Job Board is a professional recruitment agency with a rich history. It started as a small business in Oakville and has grown enough to successfully occupy two large buildings in two different cities offering people hot vacancies.</p>
                    <p>The range of our services includes not only free access to the list of America's featured and popular jobs but also additional support in the job search. Start building your career with us today!</p>
                    <a href="#" class="btn btn-primary">LEARN MORE</a>
                </div>
                <div class="col-6"><img class="img-responsive center-block" src="public/img/about-01-570x380.jpg" width="570" height="380" alt=""></div>
        </div>
</div>
    
</div>

<!-- ==============================Our advantages ================== -->
<div class="grey">

<div class="container home-heading why-people-chose-us-outer">
	<h2 class=" text-center ">Our advantages</h2>
	<hr>
	
	<div class="row">
    <div class="col">

    <h4><i class="fa fa-user" aria-hidden="true"></i>&nbsp;EXTENSIVE DATABASE</h4>

     <p>We dispose a vast database of various vacancies available for applying all over the USA. Our consultants will take all your wishes concerning the job of your dream into consideration to find a proper vacancy.</p>
    </div>
    <div class="col">
     
         <h4><i class="fa fa-user" aria-hidden="true"></i>&nbsp; DEDICATED TEAM</h4>

     <p>Job Board team is known for dedication to its customers. We will not only give you a list of recommended vacancies based on your personal profile but support you with some professional advice as well.</p>


    </div>
    <div class="col">
    <h4><i class="fa fa-user" aria-hidden="true"></i>&nbsp; ADDITIONAL SUPPORT</h4>

     <p>Consultants of Job Board will be glad to offer you additional support and even pick suitable refresher courses for increasing your skill level. We also offer vocational guidance services for the unemployed.

</p>
    </div>
  </div>

</div>
	
</div>
<!-- ==============================OUr ADVANTAGES ================== -->

<!-- =============================OUT TEAM ================ -->
<div class="">

<div class="container home-heading our-team">
                <div class="row">
                <div class="col"><h2 class=" text-center ">OUR TEAM</h2> <hr>
                </div>
                </div>

                <div class="row">
                <div class="col">
                    <div><img class="img-responsive" src="public/img/user-john-doe-270x270.jpg" alt=""/>
                    <h5 class="text-bold text-center">John Doe<small class="text-primary">&nbsp;Founder</small></h5>
                    </div>
                    
                </div>

                <div class="col">
                    <div><img class="img-responsive" src="public/img/user-john-doe-270x270.jpg" alt=""/>
                    <h5 class="text-bold text-center">John Doe<small class="text-primary">&nbsp;Founder</small></h5>
                    </div>

                </div>

                <div class="col">
                    <div><img class="img-responsive" src="public/img/user-john-doe-270x270.jpg" alt=""/>
                    <h5 class="text-bold text-center">John Doe<small class="text-primary">&nbsp;Founder</small></h5>
                    </div>

                </div>


                 <div class="col">
                    <div><img class="img-responsive" src="public/img/user-john-doe-270x270.jpg" alt=""/>
                    <h5 class="text-bold text-center">John Doe<small class="text-primary">&nbsp;Founder</small></h5>
                    </div>

                </div>
                </div>
        
</div>

</div>

<!-- ================== FOOTER ======== -->
<?php include('partials/footer.inc.php')?>
</div> <!-- id:background -->
</body>
<script type="text/javascript" src="public/javascript/script.js"></script>
</html>