<?php
/*
 * File Name: add-edit-user.php
 * By: Dipali
 * Date: 02/21/2018
 * Modified By: dipali
 * Modifed On 04/12/2018
 * Modificatition:Updated country as USA(non editable), city and state drop down
 */
 $isSession=0;

require_once('include/session.php');
require_once("include/config.php");
require_once ("include/function.php");
//check user type
if(isset($_GET["type"]) && !empty(trim($_GET["type"]))){
    $userTypeQuery=$_GET["type"];//lower case
    $userType=strtoUpper($userTypeQuery);//upper case as saved in database

}else{
    //page not found

}
if($userType==='E'){
    $userTypeName ="Employer";
    $sideBarActive=6;
    //hide date of birth and gender
   }else if($userType==='J'){
    $userTypeName ="Job Seeker";
    $sideBarActive=7;
   
}

$error="";
$successMsg="";
$browserTitle ="";
//display content
$firstName   = "";
$lastName    = "";
$email       = "";
$password = "";
//only for job seeker
$dateOfBirth = "";
$gender      = "";
$headLine="";
$skills="";
$webSiteUrl="";
$streetAddress1 ="";
$streetAddress2 ="";
$city="";
$state="";
$country	="";
$zip="";
//job seeker experience details
$expid="";
$expjobTitle   = "";
$expcompanyName= "";
$expjobLocation= "";
$expstartDate= "";
$expendDate= "";
$expDescription= "";
$expisCurrenJob= "";
$counter=0;
//job seeker education details
$eduId="";
$eduSchoolName="";
$eduDegreeName="";
$eduMajor="";
$eduGrade="";
$eduStartDate="";
$eduEndDate="";
$eduDescription="";
$eduCounter=0;

//ends only for job seeker
$contactNo = "";
$media     = "";
$companyId="";//only for Employer
$jobTitle="";//only for Employer
//
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
//edit page
$browserTitle = "Edit ".$userTypeName;
$btnName="edit";
$btn="Update";

// Get URL parameter

$id =  trim($_GET["id"]);
$sql = "SELECT `id`, `first_name`, `last_name`, `email`, `password`, `date_of_birth`,`gender`,`contact_number`,`user_image`
        FROM user_account WHERE id='".$id."' ";
$result = mysqli_query($db,$sql);
$count = mysqli_num_rows($result);
if($count == 1) {
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $firstName   = $row["first_name"];
      $lastName    = $row["last_name"];
      $email       = $row["email"];
      $password    = $row["password"];
      $dateOfBirth = $row["date_of_birth"];//only for job seeker
      $gender      = $row["gender"];//only for job seeker
      $contactNo   = $row["contact_number"];
      $media       = $row["user_image"];
      //get employer profile
      if($userType==='E'){
          $sqlEmployer = "SELECT `id`, `job_title`, `company_id`
        FROM employer_profile WHERE user_account_id='".$id."' ";
            $resultEmployer = mysqli_query($db,$sqlEmployer);
            $countEmployer = mysqli_num_rows($resultEmployer);
            if($countEmployer == 1) {
                $rowEmployer = mysqli_fetch_array($resultEmployer,MYSQLI_ASSOC);
                $jobTitle   = $rowEmployer["job_title"];
                $companyId   = $rowEmployer["company_id"];
                }
      }
      //get job seeker profile
      if($userType==='J'){
          $sqlJobSeeker = "SELECT `user_account_id`, `headline`,`skills`,`website_url`, `street_address1`, `street_address2`, `city`, `state`, `country`, `zip`
        FROM job_seeker_profile WHERE user_account_id='".$id."' ";
            $resultJobSeeker = mysqli_query($db,$sqlJobSeeker);
            $countJobSeeker = mysqli_num_rows($resultJobSeeker);
            if($countJobSeeker == 1) {
                $rowJobSeeker = mysqli_fetch_array($resultJobSeeker,MYSQLI_ASSOC);
                 $headLine =$rowJobSeeker["headline"];
                 $skills =$rowJobSeeker["skills"];
                 $webSiteUrl =$rowJobSeeker["website_url"];
                 $streetAddress1 =$rowJobSeeker["street_address1"];
                 $streetAddress2 =$rowJobSeeker["street_address2"];
                 $city=$rowJobSeeker["city"];
                 $state=$rowJobSeeker["state"];
                 $country	=$rowJobSeeker["country"];
                 $zip=$rowJobSeeker["zip"];
                }
           //add experience details if any
            //please find code below
                
      }

 }

}else{
    $browserTitle = "Add ".$userTypeName;
    $btnName="add";
    $btn="Add";

}

if($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST["add"]) || isset($_POST["edit"]) )) {

      $firstName      = isset($_POST["firstName"])      ? test_input($_POST["firstName"]) : "";
      $lastName       = isset($_POST["lastName"])       ? test_input($_POST["lastName"]) : "";
      $email          = isset($_POST["email"])          ? test_input($_POST["email"]) : "";
      $password       = isset($_POST["password"])       ? test_input($_POST["password"]) : "";
      $contactNo      = isset($_POST["contactNo"])      ? test_input($_POST["contactNo"]) : "";
      $imgName        = basename($_FILES["fileToUpload"]["name"]);
      $date           = date('Y-m-d h:i:sa');
      //only for employer
      $jobTitle       = isset($_POST["jobTitle"])       ? test_input($_POST["jobTitle"]) : "";
      $companyId      = isset($_POST["companyId"])      ? ($_POST["companyId"]) : "";//only for employer
      //only for job seeker
      $dateOfBirth    = isset($_POST["dateOfBirth"])    ? test_input($_POST["dateOfBirth"]) : "";
      $gender         = isset($_POST["gender"])         ? test_input($_POST["gender"]):'';//only for job seeker
      $headLine       = isset($_POST["headLine"])       ? test_input($_POST["headLine"]) : "";
      $skills         = isset($_POST["skills"])         ? test_input($_POST["skills"]) : "";
      $webSiteUrl     = isset($_POST["webSiteUrl"])     ? test_input($_POST["webSiteUrl"]) : "";
      $streetAddress1 = isset($_POST["streetAddress1"]) ? test_input($_POST["streetAddress1"]) : "";
      $streetAddress2 = isset($_POST["streetAddress2"]) ? test_input($_POST["streetAddress2"]) : "";
      $city           = isset($_POST["city"])           ? test_input($_POST["city"]) : "";
      $state          = isset($_POST["state"])          ? test_input($_POST["state"]) : "";
      $country	      = isset($_POST["country"])        ? test_input($_POST["country"]) : "";
      $zip            = isset($_POST["zip"])            ? test_input($_POST["zip"]) : "";
      //get experience details
      $counter        = isset($_POST["counter"])        ? $_POST["counter"] : "";
      //get deucation details
      $eduCounter     = isset($_POST["educounter"])     ? $_POST["educounter"] : "";

      //get user type
      //get user type id
      $sql = "SELECT `id`, `user_type_name` FROM user_type WHERE user_type_name='".$userType."'";
      $result = mysqli_query($db,$sql);
      $count = mysqli_num_rows($result);
      if($count > 0) {
          $userTypeArray = mysqli_fetch_array($result,MYSQLI_ASSOC);
          $usertypeId=$userTypeArray['id'];
      }      
    if(!empty($imgName)){
        $target_file = TARGET_DIR_FRONT ."/".$imgName ;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check === false) {
            $error ="File is not image!";
        }else{
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $error=  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        }

    }

    if($error===""){
           //check if add/edit page
           if(isset($_POST["add"])){
                        //check for duplicate record
                $sql = "SELECT id FROM user_account WHERE email='$email' AND is_delete='0'";
                $result = mysqli_query($db,$sql);
                $count = mysqli_num_rows($result);
                if($count == 1) {
                     $error="Duplicate record.";
                }else{
               
                        $quer = "INSERT INTO user_account (user_type_id, first_name,last_name,email,password,date_of_birth ,gender,contact_number,user_image,is_active,is_approved,is_delete) VALUES ('$usertypeId', '$firstName', '$lastName', '$email', '$password','$dateOfBirth','$gender','$contactNo','$imgName','1','0','0')";
                        $res = mysqli_query($db,$quer);
                        //get recent generated id
                        $recordId=mysqli_insert_id($db);
                        if($res){
                           if(!empty($imgName)){
                              move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                            }
                            //if employer
                            if($userType==='E'){
                            $querUser = "INSERT INTO employer_profile (user_account_id, job_title,company_id) VALUES ('$recordId', '$jobTitle', '$companyId')";
                            $resUser = mysqli_query($db,$querUser);
                            }
                            //if job seeker
                            if($userType==='J'){
                            $querUser = "INSERT INTO job_seeker_profile (user_account_id,`headline`,`skills`,`website_url`,`street_address1`, `street_address2`, `city`, `state`, `country`, `zip`) VALUES ('$recordId','$headLine', '$skills', '$webSiteUrl', '$streetAddress1','$streetAddress2','$city','$state','$country','$zip')";
                            $resUser = mysqli_query($db,$querUser);
                                //add experience details if any
                                if($counter>0){
                                    for($i=0;$i<$counter;$i++){
                                        //job title
                                        $expJobTitle =test_input($_POST["expJobTitle"][$i]);
                                        //companyName
                                        $companyName =test_input($_POST["companyName"][$i]);
                                        //location
                                        $location =test_input($_POST["location"][$i]);
                                        //currentlyWork
                                        $currentlyWork =isset($_POST["currentlyWork"][$i])? test_input($_POST["currentlyWork"][$i]):'0';
                                        //startDate
                                        $startDate =test_input($_POST["startDate"][$i]);
                                        //endDate
                                         if($currentlyWork==1){
                                        $endDate ="";

                                        }else{
                                         $endDate =test_input($_POST["endDate"][$i]);

                                        }
                                       
                                        //description
                                        $description =test_input($_POST["description"][$i]);
                                        $querExperience = "INSERT INTO experience_detail (user_account_id,`job_title`,`company_name`,`job_location`,`is_current_job`, `start_date`, `end_date`, `description`) VALUES ('$recordId','$expJobTitle', '$companyName', '$location', '$currentlyWork','$startDate','$endDate','$description')";
                                       $resExperience = mysqli_query($db,$querExperience);
                                        if(!$resExperience){
                                              $error="Error in adding experience details.";
                                        }
                                    }
                                }
                                //add education details if any
                                if($eduCounter>0){
                                    for($j=0;$j<$eduCounter;$j++){
                                        //school
                                        $eduSchoolName = test_input($_POST["eduSchoolName"][$j]);
                                        //degree
                                        $eduDegreeName = test_input($_POST["eduDegreeName"][$j]);
                                        //major
                                        $eduMajor = test_input($_POST["eduMajor"][$j]);
                                        //grade
                                        $eduGrade =test_input($_POST["eduGrade"][$j]);
                                        //
                                        $eduStartDate =test_input($_POST["eduStartDate"][$j]);
                                        //endDate
                                        $eduEndDate =test_input($_POST["eduEndDate"][$j]);
                                        //description
                                        $eduDescription =test_input($_POST["eduDescription"][$j]);
                                        
                                        $querEducation = "INSERT INTO education_detail (user_account_id,`institute_university_name`,`degree_name`,`major`,`grade`, `start_date`, `completion_date`, `description`) VALUES ('$recordId','$eduSchoolName', '$eduDegreeName', '$eduMajor', '$eduGrade','$eduStartDate','$eduEndDate','$eduDescription')";
                                        $resEducation = mysqli_query($db,$querEducation);
                                        if(!$resEducation){
                                              $error="Error in adding education details.";
                                        }
                                    }
                                }
                             }
                            if($resUser){

                                $successMsg ="Record added successfully";
                                $_SESSION['success_message'] = $successMsg;
                                header("location:user.php?type=".$userTypeQuery);
                            }
                        
                           
                        }else{
                            $error="Error in adding record.";
                        }
                      }

           }else if(isset($_POST["edit"])){
                    $sql = "SELECT id FROM user_account WHERE id='".$id."'";
                    $result = mysqli_query($db,$sql);
                    $count = mysqli_num_rows($result);
                   if($count == 1) {
                         //check for duplicate record
                $sql = "SELECT id FROM user_account WHERE email='$email' AND is_delete='0' AND id!='".$id."' ";
                $result = mysqli_query($db,$sql);
                $count = mysqli_num_rows($result);
                if($count == 1) {
                     $error="Duplicate record.";
                }else{
                        $quer = "UPDATE user_account set first_name='" .$firstName. "', last_name='" .$lastName. "', email='" .$email. "', password='" .$password. "', date_of_birth='" .$dateOfBirth. "', gender='" .$gender. "', contact_number='" .$contactNo. "'";
                        if(!empty($imgName)){
                          $quer .= " ,  user_image='" .$imgName. "' ";
                          move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                        }
                        $quer .= " WHERE id='".$id."' ";
                        $res = mysqli_query($db,$quer);
                        if($res){
                            //employer
                            if($userType==='E'){
                            
                            $querUser = "UPDATE employer_profile set job_title='" .$jobTitle. "', company_id='" .$companyId. "' WHERE user_account_id='".$id."'";
                            $resUser = mysqli_query($db,$querUser);
                            }
                            //employer
                            if($userType==='J'){

                            $querUser = "UPDATE job_seeker_profile set headline='" .$headLine. "', skills='" .$skills. "', website_url='" .$webSiteUrl. "', street_address1='" .$streetAddress1. "',
                                street_address2='" .$streetAddress2. "', city='" .$city. "', state='" .$state. "', country='" .$country. "', zip='" .$zip. "' WHERE user_account_id='".$id."'";
                            $resUser = mysqli_query($db,$querUser);
                            //add experience details if any
                                if($counter>0){
                                    //delete preious records
                                    $query = "DELETE FROM experience_detail WHERE user_account_id = '".$id."'";
                                    mysqli_query($db, $query);
                                    for($i=0;$i<$counter;$i++){
                                        //job title
                                        $expJobTitle =test_input($_POST["expJobTitle"][$i]);
                                        //companyName
                                        $companyName =test_input($_POST["companyName"][$i]);
                                        //location
                                        $location =test_input($_POST["location"][$i]);
                                        //currentlyWork
                                        $currentlyWork =isset($_POST["currentlyWork"][$i])? test_input($_POST["currentlyWork"][$i]):'0';
                                        //startDate
                                        $startDate =test_input($_POST["startDate"][$i]);
                                        //endDate
                                        if($currentlyWork==1){
                                        $endDate ="";

                                        }else{
                                         $endDate =test_input($_POST["endDate"][$i]);

                                        }
                                        //description
                                        $description =test_input($_POST["description"][$i]);
                                        $querExperience = "INSERT INTO experience_detail (user_account_id,`job_title`,`company_name`,`job_location`,`is_current_job`, `start_date`, `end_date`, `description`) VALUES ('$id','$expJobTitle', '$companyName', '$location', '$currentlyWork','$startDate','$endDate','$description')";
                                       $resExperience = mysqli_query($db,$querExperience);
                                        if(!$resExperience){
                                              $error="Error in adding experience details.";
                                        }
                                    }
                                }
                                //add education details if any
                                if($eduCounter>0){
                                    //delete preious records
                                    $queryEdu = "DELETE FROM education_detail WHERE user_account_id = '".$id."'";
                                    mysqli_query($db, $queryEdu);
                                    for($j=0;$j<$eduCounter;$j++){
                                         //school
                                        $eduSchoolName = test_input($_POST["eduSchoolName"][$j]);
                                        //degree
                                        $eduDegreeName = test_input($_POST["eduDegreeName"][$j]);
                                        //major
                                        $eduMajor = test_input($_POST["eduMajor"][$j]);
                                        //grade
                                        $eduGrade =test_input($_POST["eduGrade"][$j]);
                                        //
                                        $eduStartDate =test_input($_POST["eduStartDate"][$j]);
                                        //endDate
                                        $eduEndDate =test_input($_POST["eduEndDate"][$j]);
                                        //description
                                        $eduDescription =test_input($_POST["eduDescription"][$j]);

                                        $querEducation = "INSERT INTO education_detail (user_account_id,`institute_university_name`,`degree_name`,`major`,`grade`, `start_date`, `completion_date`, `description`) VALUES ('$id','$eduSchoolName', '$eduDegreeName', '$eduMajor', '$eduGrade','$eduStartDate','$eduEndDate','$eduDescription')";
                                        $resEducation = mysqli_query($db,$querEducation);
                                        if(!$resEducation){
                                              $error="Error in adding education details.";
                                        }
                                    }
                                }
                            }
                            if($querUser){
                                   $successMsg ="Record updated successfully";
                                   $_SESSION['success_message'] = $successMsg;
                                   header("location:user.php?type=".$userTypeQuery);

                            }
                        }else{
                             $error = "Error in update";
                        }
                }
               }else{
                    $error = "Record not found";
               }
               
           }else{
               $error ="Error in submitting form";
           }
           

           }

}
?>
<!DOCTYPE html>
<html>
<head>
  <?php include("partials/header.inc.php"); ?>
    <style>
  hr.style-seven {
    overflow: visible; /* For IE */
    padding: 0;
    border: none;
    border-top: medium double #333;
    color: #333;
    text-align: center;
}
hr.style-seven:after {
    content: "*";
    display: inline-block;
    position: relative;
    top: -0.7em;
    font-size: 1.5em;
    padding: 0 0.25em;
    background: white;
}
    </style>
</head>
<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav>
        <?php include("partials/nav.inc.php");?>
        <?php include("partials/sidebar.inc.php");?>
        
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"><?php echo $browserTitle;?></h1>
                    </div>
                    <!-- /.col-lg-12 -->

                </div>
                <!-- /.row -->
                <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        

                        <?php if($error){?>
                    <div class="alert alert-danger fade in">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <strong>Error! </strong><?php echo $error?>
                    </div>
                    <?php } ?>
                        <div class="panel-heading">
                            <?php echo $browserTitle;?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-10">
                                    <form role="form"  data-toggle="validator" id="frm_add_update_user"  name="frm_add_update_user" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                            <label  class="control-label">First Name</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $firstName;?>" required="true">
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Last Name</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $lastName;?>" required="true">
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Email</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email;?>" required="true">
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Password</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="password" class="form-control" id="password" name="password" value="<?php echo $password;?>" required="true">
                                    </div>
                                        <?php if($userType==='J'){?>
                                        <!-- jobseeker-->
                                    <div class="form-group">
                                            <label  class="control-label">Date Of Birth</label>
                                            <div class="help-block with-errors"></div>
                                            <input class="form-control" style="width:160px;" type="date"  id="dateOfBirth" name="dateOfBirth" value="<?php echo $dateOfBirth;?>" required="true">
                                    </div>
                                        <!-- jobseeker-->
                                    <div  class="form-group">
                                            <label  class="control-label">Gender</label>
                                            <div class="help-block with-errors"></div>
                                            <input id="female" type="radio" name="gender"
                                              <?php if (isset($gender) && $gender=="f") echo "checked";?>
                                              value="f">Female
                                              <input id="male" type="radio" name="gender"
                                              <?php if (isset($gender) && $gender=="m") echo "checked";?>
                                              value="m">Male
                                           
                                    </div>
                                   <!-- job seeker-->
                                     <fieldset class="form-group" >
                                    <legend>Current Location:</legend>
                                    <div class="form-group">
                                            <label  class="control-label">Address 1</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="text" class="form-control" id="streetAddress1" name="streetAddress1" value="<?php echo $streetAddress1;?>" >
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Address 2</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="text" class="form-control" id="streetAddress2" name="streetAddress2" value="<?php echo $streetAddress2;?>" >
                                    </div>
                                    <?php
                                    //Get all states from USA
                                    $query = $db->query("SELECT * FROM states WHERE status = 1 AND country_id = 224 ORDER BY state_name ASC");

                                    //Count total number of rows
                                    $rowCount = $query->num_rows;
                                    ?>
                                    <div class="form-group">
                                        <label  class="control-label">State</label>
                                        <div class="help-block with-errors"></div>
                                        <select class="form-control" name="state" id="state" required="required">
                                            <option value="">Select State</option>
<?php
                                    if ($rowCount > 0) {
                                        while ($row = $query->fetch_assoc()) {
                                            if ($row["state_id"] == $state) {
                                                $sel = "selected";
                                            } else {
                                                $sel = "";
                                            }
                                            echo '<option ' . $sel . ' value="' . $row['state_id'] . '">' . $row['state_name'] . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">State not available</option>';
                                    }
?>
                                        </select>

                                        <!--<input type="text" class="form-control" id="state" name="state" value="<?php echo $state; ?>" required="true">-->
                                    </div>
                                    <div class="form-group">
                                        <label  class="control-label">City</label>
                                        <div class="help-block with-errors"></div>

<?php
                                            if ($city != "") {
                                                $queryCity = $db->query("SELECT * FROM cities WHERE state_id = " . $state . " AND status = 1 ORDER BY city_name ASC");

                                                //Count total number of rows
                                                $rowCountCity = $queryCity->num_rows;

                                                //Display cities list
                                                echo " <select class='form-control' name='city' id='city'  required='required'>";
                                                if ($rowCountCity > 0) {
                                                    echo '<option value="">Select city</option>';

                                                    while ($rowCity = $queryCity->fetch_assoc()) {
                                                        if ($city == $rowCity["city_id"]) {
                                                            $citysel = "selected";
                                                        } else {
                                                            $citysel = "";
                                                        }

                                                        echo '<option ' . $citysel . ' value="' . $rowCity['city_id'] . '">' . $rowCity['city_name'] . '</option>';
                                                    }
                                                } else {
                                                    echo '<option value="">City not available</option>';
                                                }
                                                echo "</select>";
                                            } else {
 ?>
                                                <select class="form-control" name="city" id="city"  required="required">
                                                    <option value="">Select state first</option>
                                                </select>
<?php } ?>
                                            <!--<input type="text" class="form-control" id="city" name="city" value="<?php echo $city; ?>" required="true">-->
                                        </div>

                                    <div class="form-group">
                                            <label  class="control-label">Country</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="text" class="form-control" id="country" name="country" value="<?php echo $country?$country:"United States";?>" readonly="readonly">
                                    </div>
                                    <div class="form-group">
                                            <label  class="control-label">Zip Code</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="text" class="form-control" id="zip" name="zip" value="<?php echo $zip;?>"  pattern="[0-9]{5}" title="Five digit zip code"  required="true">
                                    </div>
                                    </fieldset>
                                   <?php } //close if($userType==='E'){?>
                                    <div class="form-group">
                                            <label  class="control-label">Contact Number</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="tel" pattern='\d{3}[\-]\d{3}[\-]\d{4}' title='Phone Number (Format: xxx-xxx-xxxx)' class="form-control" id="contactNo" name="contactNo" value="<?php echo $contactNo;?>" required="true">
                                    </div>
                                        <?php if($userType==='E'){?>
                                   <!-- employer-->
                                     <div class="form-group" style="">
                                            <label  class="control-label">Job Title</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="text" class="form-control" id="jobTitle" name="jobTitle" value="<?php echo $jobTitle;?>" required="true">
                                    </div>
                                   <!-- employer-->
                                   <div class="form-group" style="">
                                            <label  class="control-label">Company Name</label>
                                            <div class="help-block with-errors"></div>
                                            <select class="business form-control" id="companyId" name="companyId" required="true">
                                                <?php echo get_company_options($companyId);?>
                                            </select>
                                    </div>
                                     <?php }?>
                                     <div class="form-group">
                                            <label>User Profile Picture </label>
                                            <div class="help-block with-errors"></div>
                                            <div id="success"></div>
                                            <div id="delete_pic">

                                            <?php if($media){?>

                                             <div id="edit_profile_pic">
                                                <img src="<?php echo TARGET_DIR_FRONT."/".$media?>" />
                                            </div>
                                            <?php } ?>
                                            </div>
                                            <?php if($media){?>
                                            <button type="button" name="delete" class="btn-danger delete" id="<?php echo $id ?>">Remove Image</button>
                                            <?php }?>
                                            <input style="display: inline" class=" upload-img form-control" type="file" name="fileToUpload" >
                                            
                                    </div>
                                        <?php if($userType==='J'){?>
                                   <!-- job seeker-->
                                    <div  style=""  class="form-group">
                                            <label  class="control-label">Headline</label>
                                            <div class="help-block with-errors"></div>
                                            <textarea class="form-control" id="headLine" name="headLine" required="required"><?php echo $headLine?></textarea>
                                    </div>
                                        <div  style=""  class="form-group">
                                            <label  class="control-label">Skills</label>
                                            <div class="help-block with-errors"></div>
                                            <textarea class="form-control" id="skills" name="skills" required="required"><?php echo $skills?></textarea>
                                    </div>
                                        
                                        <div  style=""  class="form-group">
                                            <label  class="control-label">Website</label>
                                            <div class="help-block with-errors"></div>
                                            <input type="url" class="form-control" id="webSiteUrl" name="webSiteUrl" value="<?php echo $webSiteUrl?>">
                                    </div>
                                    <!-- experience section starts here-->
                                   <div  style=""  class="form-group">
                                            <h2><span>Experience</span><a class="addExperience" id="addExperienceBtn"><i class="fa fa-plus-circle"></i></a></h2>
                                            <hr>
                                    </div>
                                    <?php
                                    if($btn=='Add'){
                                        $id=0;
                                       }//if($btn=='update)
                                    $sqlJobSeekerExperience="";
                                    $resultExperience="";
                                      $sqlJobSeekerExperience = "SELECT `id`, `user_account_id`, `is_current_job`, `start_date`, `end_date`, `job_title`, `company_name`, `job_location`, `description`
                                        FROM experience_detail WHERE user_account_id='".$id."' ";
                                            $resultExperience = mysqli_query($db,$sqlJobSeekerExperience);
                                            $counter = mysqli_num_rows($resultExperience);
                                        
                                    ?>
                                    <input type="hidden" id="counter" name="counter" value="<?php echo $counter;?>">
                                    <div id="experience">
                                         <?php
                                            if($counter > 0) {
                                                while($rowJobSeekerExperience = mysqli_fetch_array($resultExperience,MYSQLI_ASSOC)){
                                                         $expid   = $rowJobSeekerExperience['id'];
                                                         $expjobTitle   = $rowJobSeekerExperience['job_title'];
                                                         $expcompanyName= $rowJobSeekerExperience['company_name'];
                                                         $expjobLocation= $rowJobSeekerExperience['job_location'];
                                                         $expstartDate= $rowJobSeekerExperience['start_date'];
                                                         $expendDate= $rowJobSeekerExperience['end_date'];
                                                         $expDescription= $rowJobSeekerExperience['description'];
                                                         $expisCurrenJob= $rowJobSeekerExperience['is_current_job'];
                                                       ?>
                                        <div class='form-group' >
                                            <label  class='control-label'>Title</label>
                                            <div class='help-block with-errors'></div>
                                            <input type='text' class='form-control' name="expJobTitle[]" value="<?php echo $expjobTitle;?>" required="true">
                                         </div>
                                        <div class='form-group' ><label  class='control-label'>Company Name</label><div class='help-block with-errors'></div>
                                                        <input type='text' class='form-control' name="companyName[]" value="<?php echo $expcompanyName;?>" required="true">
                                                        </div>
                                        <div class='form-group' >
                                            <input type="checkbox" class="currentlywork" name="currentlyWork[]" value="1" <?php if($expisCurrenJob==1)?> checked >&nbsp;&nbsp;I currently work here
                                         </div>
                                        <div class="outer-date">
                                        <div class='form-group start-date' >
                                            <label  class="control-label">Start Date</label> <div class='help-block with-errors'></div>
                                            <input type="month" class='form-control'  name="startDate[]" value="<?php echo $expstartDate?>" required="true">
                                         </div>

                                            <div class="form-group end-date" id="" <?php if($expisCurrenJob==1)?> style="display:none"  >
                                            <label  class="control-label">End Date</label> <div class='help-block with-errors'></div>
                                            <input type="month" class='form-control' name="endDate[]" value="<?php echo $expendDate?>" >
                                         </div>
                                            </div>
                                        <div class='form-group' >
                                            <label  class="control-label">Description</label> <div class='help-block with-errors'></div>
                                            <textarea  class='form-control'  name="description[]" required="true"><?php echo $expDescription?></textarea>
                                         </div>
                                        <div class='form-group'><a id='<?php echo $expid?>' class='btn btn-sm btn-danger remove-exp'>Delete</a></div>
                                        <hr class='style-seven'>
                                        <?php
                                                    }

                                                }
                                         ?>
                                    </div>

                                    <!-- education section starts here-->
                                   <div  style=""  class="form-group">
                                            <h2><span>Education</span><a class="addEducation" id="addEducationBtn"><i class="fa fa-plus-circle"></i></a></h2>
                                            <hr>
                                    </div>
                                    <?php
                                    if($btn=='Add'){
                                        $id=0;
                                       }//if($btn=='update)
                                    $sqlJobSeekerEducation="";
                                    $resultEducation="";
                                       $sqlJobSeekerEducation = "SELECT `id`, `user_account_id`, `degree_name`, `major`, `institute_university_name`, `start_date`, `completion_date`, `grade`, `description`
                                        FROM education_detail WHERE user_account_id='".$id."' ";
                                            $resultEducation = mysqli_query($db,$sqlJobSeekerEducation);
                                            $eduCounter = mysqli_num_rows($resultEducation);

                                    ?>
                                    <input type="hidden" id="educounter" name="educounter" value="<?php echo $eduCounter;?>">
                                    <div id="education">
                                         <?php
                                            if($eduCounter > 0) {
                                                while($rowJobSeekerEducation = mysqli_fetch_array($resultEducation,MYSQLI_ASSOC)){
                                                         $eduId = $rowJobSeekerEducation["id"];
                                                         $eduSchoolName = $rowJobSeekerEducation["institute_university_name"];
                                                         $eduDegreeName =$rowJobSeekerEducation["degree_name"];
                                                         $eduMajor = $rowJobSeekerEducation["major"];
                                                         $eduGrade = $rowJobSeekerEducation["grade"];
                                                         $eduStartDate= $rowJobSeekerEducation['start_date'];
                                                         $eduEndDate= $rowJobSeekerEducation['completion_date'];
                                                         $eduDescription= $rowJobSeekerEducation['description'];
                                                       ?>
                                        <div class='form-group' >
                                            <label  class='control-label'>School</label>
                                            <div class='help-block with-errors'></div>
                                            <input type='text' class='form-control' name="eduSchoolName[]" value="<?php echo $eduSchoolName;?>" required="true">
                                         </div>
                                        <div class='form-group' >
                                            <label  class='control-label'>Degree</label><div class='help-block with-errors'></div>
                                            <input type='text' class='form-control' name="eduDegreeName[]" value="<?php echo $eduDegreeName;?>" required="true">
                                        </div>
                                        <div class='form-group' >
                                            <label  class='control-label'>Field of study</label><div class='help-block with-errors'></div>
                                            <input type='text' class='form-control' name="eduMajor[]" value="<?php echo $eduMajor;?>" required="true">
                                        </div>
                                        <div class='form-group' >
                                            <label  class='control-label'>Grade</label><div class='help-block with-errors'></div>
                                            <input type='text' class='form-control' name="eduGrade[]" value="<?php echo $eduGrade;?>" required="true">
                                        </div>
                                        
                                        <div class="outer-date">
                                        <div class='form-group start-date' >
                                            <label  class="control-label">Start Date</label> <div class='help-block with-errors'></div>
                                            <input type="month" class='form-control'  name="eduStartDate[]" value="<?php echo $eduStartDate?>" required="true">
                                         </div>

                                            <div class="form-group end-date" id=""  >
                                            <label  class="control-label">End Date</label> <div class='help-block with-errors'></div>
                                            <input type="month" class='form-control' name="eduEndDate[]" value="<?php echo $eduEndDate?>" required="true">
                                         </div>
                                            </div>
                                        <div class='form-group' >
                                            <label  class="control-label">Description</label> <div class='help-block with-errors'></div>
                                            <textarea  class='form-control'  name="eduDescription[]" required="true"><?php echo $eduDescription?></textarea>
                                         </div>
                                        <div class='form-group'><a id='<?php echo $eduId?>' class='btn btn-sm btn-danger remove-edu'>Delete</a></div>
                                        <hr class='style-seven'>
                                        <?php
                                                    }

                                                }
                                         ?>
                                    </div>
                                    <?php } //close if($userType==='E'){
                                    ?>
                                        
                                     <!-- experience section ends here-->

                                        <button type="submit" name="<?php echo $btnName?>" class="btn btn-default btn-primary"><?php echo $btn?></button>
                                        <button type="reset"  id="back" class="btn btn-default btn-primary">Cancel</button>
                                    </form>
                                </div> <!-- ./col-lg-6 -->
                            </div> <!-- ./row -->
                        </div> <!-- ."panel-body-->
                    </div> <!-- /.l panel-default-->
                </div> <!-- .col-lg-12 -->
            </div> <!-- .row -->


            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
<?php include("partials/footer.inc.php");?>
    <script>

    </script>
    <script>
    $(document).ready(function() {
        
        //restrict date to future date
        var dtToday = new Date();
        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();

        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();

        var maxDate = year + '-' + month + '-' + day;
            $('#dateOfBirth').attr('max', maxDate);
     //image delete
     $(document).on('click', '.delete', function(){
           var user_id = $(this).attr("id");
             var action = "delete_profile_pic";

         if(confirm("Are you sure you want to remove this image from database?"))
          {
           // $( "#frm_add_update_news" ).submit();
              $.ajax({
                  url: 'include/action.php',
                  type: 'post',
                  data:{user_id:user_id, action:action},
                  success: function(data, status) {
                   // alert(data);
                     $("#delete_pic").html('');
                     $(".delete").css("display","none");
                     $('#success').html("<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>Image deleted Successfully</div>");

                  },
                  error: function(xhr, desc, err) {
                 //  alert(err);
                  }
                }); // end ajax call

          }else
          {
           return false;
          }
     });
     
     //esperience delete
     $(document).on('click', '.remove-exp', function(){
           var exp_id = $(this).attr("id");
           var action = "delete_experience";

         if(confirm("Are you sure you want to remove this record from database?"))
          {
           // $( "#frm_add_update_news" ).submit();
              $.ajax({
                  url: 'include/action.php',
                  type: 'post',
                  data:{exp_id:exp_id, action:action},
                  success: function(data, status) {
                       $.notify("Record deleted Successfully!","success");
                         setTimeout(function(){
                             location.reload();
                     }, 1000);

                  
                  },
                  error: function(xhr, desc, err) {
                 //  alert(err);
                  }
                }); // end ajax call

          }else
          {
           return false;
          }
     });
     //education delete
     $(document).on('click', '.remove-edu', function(){
           var edu_id = $(this).attr("id");
           var action = "delete_education";

         if(confirm("Are you sure you want to remove this record from database?"))
          {
           // $( "#frm_add_update_news" ).submit();
              $.ajax({
                  url: 'include/action.php',
                  type: 'post',
                  data:{edu_id:edu_id, action:action},
                  success: function(data, status) {
                       $.notify("Record deleted Successfully!","success");
                         setTimeout(function(){
                             location.reload();
                     }, 1000);


                  },
                  error: function(xhr, desc, err) {
                 //  alert(err);
                  }
                }); // end ajax call

          }else
          {
           return false;
          }
     });
     //add expeience
     var i=0;
     $("#addExperienceBtn").on('click',function(){ 
      var experienceHTML = "<div id='exp"+i+"'><div class='form-group' ><label class='control-label'>Title</label><span style='color:red'> *</span><div class='help-block with-errors'></div><input type='text' class='jobTitle form-control' name='expJobTitle[]' value='' required='true'></div>";
          experienceHTML +=" <div class='form-group' ><label  class='control-label'>Company Name</label><span style='color:red'> *</span><div class='help-block with-errors'></div><input type='text' class='form-control' name='companyName[]' value='' required='true'></div>";
          experienceHTML +="<div class='form-group' ><label  class='control-label'>Location</label><span style='color:red'> *</span> <div class='help-block with-errors'></div><input type='text' class='form-control' name='location[]' value='' required='true'></div>";
          experienceHTML +="<div class='form-group' ><input type='checkbox'  name='currentlyWork[]' class='currentlywork' value='1' >&nbsp;&nbsp;I currently work here</div>";
          experienceHTML +="<div class='outer-date'>";
          experienceHTML +="<div class='form-group start-date' ><label  class='control-label'>Start Date</label><span style='color:red'> *</span> <div class='help-block with-errors'></div><input type='month' class='form-control'  name='startDate[]' value='' required='true'></div>";
          experienceHTML +="<div class='form-group end-date' ><label  class='control-label'>End Date</label><span style='color:red'> *</span> <div class='help-block with-errors'></div><input type='month' class='form-control' name='endDate[]' value='' required='true'></div>";
          experienceHTML +="</div>";
          experienceHTML +="<div class='form-group' ><label class='control-label'>Description</label><span style='color:red'> *</span> <div class='help-block with-errors'></div><textarea  class='form-control'  name='description[]' required='true'></textarea></div>";
          experienceHTML +="<div class='form-group'><a id='' data-id='exp"+i+"' class='btn btn-sm btn-primary remove'>Cancel</a></div>";
          experienceHTML +="<hr class='style-seven'></div>"
      $("#experience").append(experienceHTML);
      //alert();
       var counter = parseInt($("#counter").val());
           counter=counter+1;
       $("#counter").val(counter);

      i++;

     $("#frm_add_update_user").validator('update');
     //currntly work here click: hide end date
     $('.currentlywork').click(function() {
        // var endDate=$(this).parent().closest("div").next("div").children("div.end-date");
        // var month = endDate.children("input")
         if($(this).is(':checked')){
            $(this).parent().closest("div").next("div").children("div.end-date").css('display','none');
            $(this).parent().closest("div").next("div").children("div.end-date").children("input").attr('data-validate','false');
            $("#frm_add_update_user").validator('update');
        }else{
           $(this).parent().closest("div").next("div").children("div.end-date").css('display','block');
           $(this).parent().closest("div").next("div").children("div.end-date").children("input").attr('data-validate','true');
           $("#frm_add_update_user").validator('update');
       }
    });
     });
     //currntly work here click: hide end date
     $('.currentlywork').click(function() {
       //  var endDate=$(this).parent().closest("div").next("div").children("div.end-date");
        // var month = endDate.children("input")
         if($(this).is(':checked')){
            $(this).parent().closest("div").next("div").children("div.end-date").css('display','none');
            $(this).parent().closest("div").next("div").children("div.end-date").children("input").attr('data-validate','false');
            $("#frm_add_update_user").validator('update');
        }else{
           $(this).parent().closest("div").next("div").children("div.end-date").css('display','block');
           $(this).parent().closest("div").next("div").children("div.end-date").children("input").attr('data-validate','true');
           $("#frm_add_update_user").validator('update');
       }
    });

    //add education
     var j=0;
     $("#addEducationBtn").on('click',function(){
      var educationHTML = "<div id='edu"+j+"'><div class='form-group' ><label class='control-label'>School</label><span style='color:red'> *</span><div class='help-block with-errors'></div><input type='text' class='eduSchoolName form-control' name='eduSchoolName[]' value='' required='true'></div>";
          educationHTML +=" <div class='form-group' ><label  class='control-label'>Degree</label><span style='color:red'> *</span><div class='help-block with-errors'></div><input type='text' class='form-control' name='eduDegreeName[]' value='' required='true'></div>";
          educationHTML +="<div class='form-group' ><label  class='control-label'>Field of Study</label><span style='color:red'> *</span> <div class='help-block with-errors'></div><input type='text' class='form-control' name='eduMajor[]' value='' required='true'></div>";
          educationHTML +="<div class='form-group' ><label  class='control-label'>Grade</label><div class='help-block with-errors'></div><input type='text' class='form-control' name='eduGrade[]' value='' required='true'></div>";
          educationHTML +="<div class='outer-date'>";
          educationHTML +="<div class='form-group start-date' ><label  class='control-label'>Start Date</label> <div class='help-block with-errors'></div><input type='month' class='form-control'  name='eduStartDate[]' value='' required='true'></div>";
          educationHTML +="<div class='form-group end-date' id=''><label  class='control-label'>End Date</label> <div class='help-block with-errors'></div><input type='month' class='form-control' name='eduEndDate[]' value='' required='true'></div>";
          educationHTML +="</div>";
          educationHTML +="<div class='form-group' ><label  class='control-label'>Description</label> <div class='help-block with-errors'></div><textarea  class='form-control'  name='eduDescription[]' required='true'></textarea></div>";
          educationHTML +="<div class='form-group'><a id='' data-id='edu"+j+"' class='btn btn-sm btn-primary removeEdu'>Cancel</a></div>";
          educationHTML +="<hr class='style-seven'></div>"
      $("#education").append(educationHTML);
      //alert();
       var educounter = parseInt($("#educounter").val());
           educounter=educounter+1;
       $("#educounter").val(educounter);

      j++;

     $("#frm_add_update_user").validator('update');
    
     });

     
      $(document).on("click", "a.remove" , function() {
           var record_no = $(this).attr("data-id");
           $( "#"+record_no ).remove();

            var counter = parseInt($("#counter").val());
                counter=counter-1;
            $("#counter").val(counter);

      $("#frm_add_update_user").validator('update');
    
        });
        
 $(document).on("click", "a.removeEdu" , function() {
           var record_no = $(this).attr("data-id");
           $( "#"+record_no ).remove();

            var educounter = parseInt($("#educounter").val());
                educounter=educounter-1;
            $("#educounter").val(educounter);

      $("#frm_add_update_user").validator('update');

        });



    $('#state').on('change',function(){
        var stateID = $(this).val();
        if(stateID){
            $.ajax({
                type:'POST',
                url:'include/action.php',
                data:'state_id='+stateID,
                success:function(html){
                    $('#city').html(html);
                }
            });
        }else{
            $('#city').html('<option value="">Select state first</option>');
        }
    });
    
       addRequiredMark('frm_add_update_user');
       // $('#frm_add_update_user').validator()
});
  </script>

</body>
</html>