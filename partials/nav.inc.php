<?php 
if($changeNavBar){
$classNav="navbar fixed-top navbar-expand-lg navbar-dark bg-dark";
}else{
$classNav="navbar fixed-top navbar-expand-lg navbar-dark ";
}?>
<nav class="<?php echo $classNav;?>">
    <div class="container">

        <a class="navbar-brand" href="#">Job Board</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php
                 //display menus
                 $sqlMenu = "SELECT `menu_header_id`,menu_header_text FROM menu_header ";
                 $resultMenu = mysqli_query($db,$sqlMenu);
                 $countMenu = mysqli_num_rows($resultMenu);
                 $rowNoMenu=1;
                 $active="";
                 if($countMenu > 0) {
                   while($contentMenu = mysqli_fetch_array($resultMenu,MYSQLI_ASSOC)){
                       $menuText = $contentMenu["menu_header_text"];
                       $menuTextUrl = CURRENT_URL. urlencode(strtolower($menuText));

                       ?>
                <li class="nav-item <?php echo $active?>">
                    <a class="nav-link" href="<?php echo $menuTextUrl?>"><?php echo $menuText?> <?php if($rowNoMenu==1) {?><span class="sr-only">(current)</span><?php } ?></a>
                </li>
                       <?php
                   $rowNoMenu++;
                   }
                   }
                ?>
                <li class="nav-item ">
                    <a class="nav-link" href="<?php echo CURRENT_URL?>news-listing/">News</a>
                </li>
                <?php
                //check if user is logged in
                $userLoggedIn = isUserLoggedIn();
                //print_r( $userLoggedIn);
                if($userLoggedIn!=0){
                    if($userLoggedIn["user_type_name"]=="J"){
                            $jobLink='jobs';
                        }else{
                            $jobLink='manage-jobs';
                        }
                ?>
               <li class="nav-item ">
                    <a class="nav-link" href="<?php echo CURRENT_URL.$jobLink?>">Jobs</a>
                </li>

                 <li class="nav-item dropdown ">
                    <a class="navbar-brand nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php if($userLoggedIn["user_image"]!=""){
                         ?>
                        <img src="<?php echo CURRENT_URL.TARGET_DIR."/".$userLoggedIn["user_image"]?>" width="30" height="30" class="d-inline-block align-top" alt="">
                        <?php }else{
                        ?>
                            <img src="<?php echo CURRENT_URL."/public/img/user.png"?>" width="30" height="30" class="d-inline-block align-top" alt="">
                        <?php
                        }?>

                        <?php //echo $userLoggedIn["first_name"];?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php
                        if($userLoggedIn["user_type_name"]=="J"){
                            $updateProfileLink='job-seeker';
                        }else{
                            $updateProfileLink='employer';
                        }
                        ?>
                        <a class="dropdown-item" href="<?php echo $updateProfileLink?>">Update Profile</a>
                        <a class="dropdown-item" href="<?php echo CURRENT_URL?>change-password">Change Password</a>
                        <a class="dropdown-item" href="<?php echo CURRENT_URL?>logout.php">Logout</a>
                        </div>
                </li>

                <?php
                }else{
               ?>
                 <li class="nav-item ">
                    <a class="nav-link" href="<?php echo CURRENT_URL?>sign-up">Sign In</a>
                </li>

                <?php
                }
                ?>

            </ul>

        </div>

    </div>
</nav>