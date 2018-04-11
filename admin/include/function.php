<?php
//require_once('session.php');
//require_once("config.php");
//include('config.php');

define("TARGET_DIR", "uploads");
define("TARGET_DIR_FRONT", "../uploads");
$dir = $_SERVER['SERVER_NAME'];
define("CURRENT_URL", "http://".$dir."/OnlineJobPortal/");
$changeNavBar=false;

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
function letter_space($name){
    return preg_match("/^[a-zA-Z ]*$/",$name);
}
function get_business($businessStreamId){
    global $db;
    $sql = "SELECT business_stream_name FROM bussiness_stream WHERE is_removed='0' AND id=".$businessStreamId." ";
    $result = mysqli_query($db,$sql);
    $count = mysqli_num_rows($result);
    if($count>0){
        $content = mysqli_fetch_array($result,MYSQLI_ASSOC);
        return $content["business_stream_name"];
    }else{
        return "-";
    }
                                        
}
function get_business_options($businessStreamId){
    global $db;
    $option="<option value=''>Select</option>";
    $sql = "SELECT id, business_stream_name FROM bussiness_stream WHERE is_removed='0' AND is_active='1' ";
    $result = mysqli_query($db,$sql);
    $count = mysqli_num_rows($result);
    if($count>0){
        while($content = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $id=$content["id"];
                $business_stream_name=$content["business_stream_name"];
                if($businessStreamId==$id){
                    $select="selected";
                }else{
                    $select="";
                }
                $option.="<option $select value='$id'>$business_stream_name</option>";
        }
        return $option;
    }else{
        return $option;
    }
}

function get_company($companyId){
    global $db;
    $sql = "SELECT company_name FROM company WHERE is_removed='0' AND id=".$companyId." ";
    $result = mysqli_query($db,$sql);
    $count = mysqli_num_rows($result);
    if($count>0){
        $content = mysqli_fetch_array($result,MYSQLI_ASSOC);
        return $content["company_name"];
    }else{
        return "-";
    }

}

function get_company_options($companyId){
    global $db;
    $option="<option value=''>Select</option>";
    $sql = "SELECT id, company_name FROM company WHERE is_removed='0' AND is_active='1' ";
    $result = mysqli_query($db,$sql);
    $count = mysqli_num_rows($result);
    if($count>0){
        while($content = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $id=$content["id"];
                $company_name=$content["company_name"];
                if($companyId==$id){
                    $select="selected";
                }else{
                    $select="";
                }
                $option.="<option $select value='$id'>$company_name</option>";
        }
        return $option;
    }else{
        return $option;
    }
}

function get_company_search($companyId){
    global $db;
    $option="<option value=''>Company</option>";
    $sql = "SELECT id, company_name FROM company WHERE is_removed='0' AND is_active='1' ";
    $result = mysqli_query($db,$sql);
    $count = mysqli_num_rows($result);
    if($count>0){
        while($content = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $id=$content["id"];
                $company_name=$content["company_name"];
                if($companyId==$id){
                    $select="selected";
                }else{
                    $select="";
                }
                $option.="<option $select value='$id'>$company_name</option>";
        }
        return $option;
    }else{
        return $option;
    }
}

function get_job_type_search($jobTypeId){
    global $db;
    $option="<option value=''>Job Type</option>";
    $sql = "SELECT id, job_type FROM job_type";
    $result = mysqli_query($db,$sql);
    $count = mysqli_num_rows($result);
    if($count>0){
        while($content = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $id=$content["id"];
                $job_type=$content["job_type"];
                if($jobTypeId==$id){
                    $select="selected";
                }else{
                    $select="";
                }
                $option.="<option $select value='$id'>$job_type</option>";
        }
        return $option;
    }else{
        return $option;
    }
}
function get_city_search($locCitySel){
    global $db;
    $option="<option value=''>City</option>";
    $sql = "SELECT DISTINCT loc_city FROM job_post order By loc_city";
    $result = mysqli_query($db,$sql);
    $count = mysqli_num_rows($result);
    if($count>0){
        while($content = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $loc_city=$content["loc_city"];
                if($loc_city==$locCitySel){
                    $select="selected";
                }else{
                    $select="";
                }
                $option.="<option $select value='$loc_city'>$loc_city</option>";
        }
        return $option;
    }else{
        return $option;
    }
}
function get_job_type($jobTypeId){
    global $db;
    $sql = "SELECT job_type FROM job_type WHERE id=".$jobTypeId." ";
    $result = mysqli_query($db,$sql);
    $count = mysqli_num_rows($result);
    if($count>0){
        $content = mysqli_fetch_array($result,MYSQLI_ASSOC);
        return $content["job_type"];
    }else{
        return "-";
    }

}
function get_job_type_options($jobTypeId){
    global $db;
    $option="<option value=''>Select</option>";
    $sql = "SELECT id, job_type FROM job_type";
    $result = mysqli_query($db,$sql);
    $count = mysqli_num_rows($result);
    if($count>0){
        while($content = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $id=$content["id"];
                $job_type=$content["job_type"];
                if($jobTypeId==$id){
                    $select="selected";
                }else{
                    $select="";
                }
                $option.="<option $select value='$id'>$job_type</option>";
        }
        return $option;
    }else{
        return $option;
    }
}
function get_job_status($jobStatusId){
    global $db;
    $sql = "SELECT job_status FROM job_status WHERE job_status_id=".$jobStatusId." ";
    $result = mysqli_query($db,$sql);
    $count = mysqli_num_rows($result);
    if($count>0){
        $content = mysqli_fetch_array($result,MYSQLI_ASSOC);
        return $content["job_status"];
    }else{
        return "-";
    }

}
function get_job_status_options($jobStatusId){
    global $db;
    $option="<option value=''>Select</option>";
    $sql = "SELECT job_status_id, job_status FROM job_status";
    $result = mysqli_query($db,$sql);
    $count = mysqli_num_rows($result);
    if($count>0){
        while($content = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $id=$content["job_status_id"];
                $job_status=$content["job_status"];
                if($jobStatusId==$id){
                    $select="selected";
                }else{
                    $select="";
                }
                $option.="<option $select value='$id'>$job_status</option>";
        }
        return $option;
    }else{
        return $option;
    }
}
function no_of_vaccancies(){
    global $db;
    $sql = "SELECT id FROM job_post inner join job_status on job_post.job_status = job_status.job_status_id WHERE job_status.job_status='Open' ";
    $result = mysqli_query($db,$sql);
    $count = mysqli_num_rows($result);
    return  $count;
    
}
function isUserLoggedIn(){
    global $db;
    //global $loggedInUser;
    if(!isset($_SESSION['login_user_front'])){
      //header("location:sign-up");
        return 0;
   }else{
        $userEmail=$_SESSION['login_user_front'];
        $sql = "SELECT user_account.`id`, `first_name`, `last_name`, `email`,`user_image`,user_type.user_type_name
        FROM user_account INNER JOIN user_type ON user_account.user_type_id=user_type.id WHERE email='".$userEmail."' AND is_delete='0' AND is_approved='1' AND is_active='1' ";
        $result = mysqli_query($db,$sql);
        $count = mysqli_num_rows($result);
        if($count == 1) {
            $loggedInUser = mysqli_fetch_array($result,MYSQLI_ASSOC);
            return $loggedInUser;
        }else{
            return 0;
        }

   }
}
function timeago($date) {
	   $timestamp = strtotime($date);

	   $strTime = array("second", "minute", "hour", "day", "month", "year");
	   $length = array("60","60","24","30","12","10");

	   $currentTime = time();
	   if($currentTime >= $timestamp) {
			$diff     = time()- $timestamp;
			for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
			$diff = $diff / $length[$i];
			}

			$diff = round($diff);
			return $diff . " " . $strTime[$i] . "(s) ago ";
	   }
}
function get_number_of_application($jobId){
    global $db;
    $sql = "SELECT job_post_activity.id FROM job_post_activity INNER JOIN user_account ON job_post_activity.user_account_id=user_account.id WHERE user_account.is_delete='0' AND job_post_activity.job_post_id='".$jobId."'";
    $result = mysqli_query($db,$sql);
    $count = mysqli_num_rows($result);
    return $count;

}
function get_application_status($applStatusId){
    global $db;
    $sql = "SELECT appl_status FROM application_status WHERE appl_status_id=".$applStatusId." ";
    $result = mysqli_query($db,$sql);
    $count = mysqli_num_rows($result);
    if($count>0){
        $content = mysqli_fetch_array($result,MYSQLI_ASSOC);
        return $content["appl_status"];
    }else{
        return "-";
    }

}
function get_appl_status_options($applStatusId){
    global $db;
    $option="<option value=''>Select</option>";
    $sql = "SELECT appl_status_id, appl_status FROM application_status";
    $result = mysqli_query($db,$sql);
    $count = mysqli_num_rows($result);
    if($count>0){
        while($content = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $id=$content["appl_status_id"];
                $appl_status=$content["appl_status"];
                if($applStatusId==$id){
                    $select="selected";
                }else{
                    $select="";
                }
                $option.="<option $select value='$id'>$appl_status</option>";
        }
        return $option;
    }else{
        return $option;
    }
}
?>