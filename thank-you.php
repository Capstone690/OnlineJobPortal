<?php
/*
 * File Name: thank-you.php
 * By: Dipali
 * Date: 03/15/2018
 */
$isSession=0;

require_once('admin/include/session.php');
require_once("admin/include/config.php");
require_once("admin/include/function.php");
$browserTitle="Thank you";
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
  <h1>Success</h1>
  </div>
<!-- =============================Who We Are ============================= -->
<div class="">

<div class="container home-heading latest-news">
	
	<div class="row">
		<div class="col">
                    <h2 class=" text-center ">Thank you</h2><hr class=" text-center ">
                    <div class=" text-center">
                        <p>Thank you for registration.  Your account is currently pending approval by the site administrator.</p>
                        <a class="btn btn-primary btn-lg" href="<?php echo CURRENT_URL.'sign-up'?>">LOGIN</a>
                    </div>
                
                </div>
                
                <!-- LOGIN FORM ENDS-->
                </div>
                
        </div>
</div>
    
</div>

<!-- ================== FOOTER ======== -->
<?php include('partials/footer.inc.php')?>
</div> <!-- id:background -->
</body>
<script type="text/javascript">
    var login = document.getElementById("login");
    var reg   = document.getElementById("reg");
    var loginBtn= document.getElementById("loginBtn");
    var regBtn= document.getElementById("regBtn");
  
    //hide reg and show login
    function loginFun(){
        reg.style.display='none';
        login.style.display='block';
        loginBtn.classList.add('tab-active');
        regBtn.classList.remove('tab-active');
    }
    //hide reg and show reg
    function regFun(){
        login.style.display='none';
        reg.style.display='block';
        regBtn.classList.add('tab-active');
        loginBtn.classList.remove('tab-active');
    }
    
    //show login on page load
    loginFun();
    //form validotr3
    
    loginBtn.addEventListener("click", function(){
       loginFun();
    });
    regBtn.addEventListener("click",function(){
        regFun();
    });

    

</script>
<!--<script src="https://code.jquery.com/jquery-3.2.1.min.js" ></script>-->
<script>
$(document).ready(function() {
      //$("#login").validator();
        
});
</script>
<script type="text/javascript" src="<?php echo CURRENT_URL?>public/javascript/script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>

</html>