<?php
/*
 * File Name: contact-us.php
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
  <h1>Contact Us</h1>
  <h5>lorem ipsum lorem ipsum lorem ipsum</h5>
  </div>
<!-- =============================Who We Are ============================= -->
<div class="">

<div class="container home-heading latest-news">
	
	<div class="row">
		<div class="col-8">
                    <h2 class=" text-left ">Get In Touch</h2><hr class=" text-left left-hr ">
                    <p>You can contact us any way that is convenient for you. We are available 24/7 via fax, email or telephone. You can also use a quick contact form located on this page to ask a question about our services and current offers. We would be happy to answer your questions or offer any help.</p>
                <!-- CONTACT FORM -->
                <form class="contact-form">
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputFirstName">First Name</label>
      <input type="text" class="form-control" id="inputFirstName" placeholder="First Name">
    </div>
    <div class="form-group col-md-6">
      <label for="inputLastName">Last Name</label>
      <input type="text" class="form-control" id="inputLastName" placeholder="Last Name">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail">Email</label>
    <input type="email" class="form-control" id="inputEmail" placeholder="Email">
  </div>
 <div class="form-group">
    <label for="inputTextArea">Message</label>
    <textarea class="form-control" id="inputTextArea" rows="3"></textarea>
  </div>
                    
  
  
  <button type="submit" class="btn btn-primary">Send</button>
</form>
                <!-- CONTACT FORM ENDS-->
                </div>
                <div class="col-4">
                    <div class="contact-right-side">
                        <h5 class="right-contact">Phone</h5>
                        <p class="right-contact-p">1800-123-33</p>
                    </div>
                    <div class="contact-right-side">
                        <h5 class="right-contact">Address</h5>
                        <p class="right-contact-p">4578 Marmora St, San Francisco D04 89GR</p>
                    </div>
                    <div class="contact-right-side">
                        <h5 class="right-contact">Opening Hours</h5>
                        <p class="right-contact-p">
                            <strong>Monday-Friday: </strong>9:00am-6:00pm<br>
                            <strong>Saturday: </strong>10:00am-4:00pm<br>
                            <strong>Sunday: </strong>10:00am-1:00pm<br>

                        </p>
                    </div>
                </div>
        </div>
</div>
    
</div>

<!-- ==============================Map ================== -->
<div class="">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3169.962843481304!2d-121.88686278539541!3d37.39071097983121!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x808fcc1986b3b1b3%3A0xb8e94e3853b01aa6!2sCostco+Wholesale!5e0!3m2!1sen!2sus!4v1521914504776" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
</div>
<!-- ==============================OUr ADVANTAGES ================== -->

<!-- ================== FOOTER ======== -->
<?php include('partials/footer.inc.php')?>
</div> <!-- id:background -->
</body>
<script type="text/javascript" src="public/javascript/script.js"></script>
</html>