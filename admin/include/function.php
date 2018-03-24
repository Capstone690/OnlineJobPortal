<?php
//require_once('session.php');
//require_once("config.php");
//include('config.php');

define("TARGET_DIR", "uploads");
define("TARGET_DIR_FRONT", "../uploads");

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
?>