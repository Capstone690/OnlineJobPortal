<?php
 $isSession=0;

   include('include/session.php');
   require_once("include/config.php");
$browserTitle = "Dashboard";
$sideBarActive=1;
//get no of  employer and job seeker
$sqlEmp = "SELECT id FROM user_account WHERE is_delete='0' AND is_approved='1' AND is_active='1' AND user_type_id='3'";
$resultEmp = mysqli_query($db,$sqlEmp);
$noOfEmp = mysqli_num_rows($resultEmp);

$sqlJsk = "SELECT id FROM user_account WHERE is_delete='0' AND is_approved='1' AND is_active='1' AND user_type_id='2'";
$resultJsk = mysqli_query($db,$sqlJsk);
$noOfJsk = mysqli_num_rows($resultJsk);
//get no of jobs according to job status
$sqlJobs = "SELECT job_status_id,job_status.job_status,status_description,count(job_post.id) AS no_of_jobs FROM `job_status` LEFT JOIN job_post ON job_status.job_status_id=job_post.job_status AND job_post.is_active='1' AND job_post.is_delete='0' GROUP BY job_status.job_status_id ORDER BY job_status_id";
$resultJobs = mysqli_query($db,$sqlJobs);
$noOfJobs = mysqli_num_rows($resultJobs);
//GET NUMBER OF JOBS POSTED (OPEN AND FILLED)
$jobType="";
$jobsPosted="";
$applRecived="";
$jobsFilled="";
$jobTypeArray=array();
        $jobPostedArray=array();
        $jobFilledArray=array();
$sqlpostedJobs="SELECT job_type.id,job_type.job_type ,count(job_post.id) AS jobs_posted
FROM `job_type`
LEFT JOIN job_post
ON job_type.id=job_post.job_type_id AND job_post.is_active='1' AND job_post.is_delete='0' AND (job_status='2' OR job_status='5') GROUP BY job_type.job_type ORDER BY job_type.id";
$resultPostedJobs = mysqli_query($db,$sqlpostedJobs);
$noOfPostedJobs = mysqli_num_rows($resultPostedJobs);
while($jobsArray = mysqli_fetch_array($resultPostedJobs,MYSQLI_ASSOC)){
 $jobPostedArray[$jobsArray["job_type"]]=$jobsArray["jobs_posted"];
}
$sqlJobsApplied="SELECT temp.id,temp.job_type, count(job_post_activity.id) AS jobs_applied from (SELECT job_type.id,job_type.job_type ,job_post.id AS jobs_posted_id
FROM `job_type`
LEFT JOIN job_post
ON job_type.id=job_post.job_type_id AND job_post.is_active='1' AND job_post.is_delete='0'  ) temp left join job_post_activity  on temp.jobs_posted_id = job_post_activity.job_post_id AND job_post_activity.is_removed='0' GROUP BY temp.job_type ORDER BY temp.id";
$resultJobsApplied = mysqli_query($db,$sqlJobsApplied);
$noOfJobsApplied = mysqli_num_rows($resultJobsApplied);

while($jobsApplied = mysqli_fetch_array($resultJobsApplied,MYSQLI_ASSOC)){
  $jobsAppliedArray[$jobsApplied["job_type"]]=$jobsApplied["jobs_applied"];
}

$sqlJobsFilled="SELECT job_type.id,job_type.job_type ,count(job_post.id) AS jobs_filled
FROM `job_type`
LEFT JOIN job_post
ON job_type.id=job_post.job_type_id AND job_post.is_active='1' AND job_post.is_delete='0' AND job_status='5' GROUP BY job_type.job_type ORDER BY job_type.id";
$resultJobsFilled = mysqli_query($db,$sqlJobsFilled);
$noOfJobsFilled = mysqli_num_rows($resultJobsFilled);

while($jobsFilled = mysqli_fetch_array($resultJobsFilled,MYSQLI_ASSOC)){
  $jobFilledArray[$jobsFilled["job_type"]]=$jobsFilled["jobs_filled"];
}
/*
foreach($jobPostedArray AS $jobType => $jobsPostedCnt){
    echo $jobType."-".$jobsPostedCnt."-".$jobsAppliedArray[$jobType]."-".$jobFilledArray[$jobType]."<br>";
}*/
/*
echo '<pre>';

var_dump($jobPostedArray["Full-time"]);

var_dump($jobFilledArray["Full-time"]);
var_dump($jobsAppliedArray["Full-time"]);

echo '</pre>';*/
?>

<!DOCTYPE html>
<html>
<head>
    <?php include("partials/header.inc.php"); ?>
 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Load Charts and the corechart package.
      google.charts.load('current', {'packages':['corechart','bar']});
      // Draw the pie chart for users distribution   when Charts is loaded.
      google.charts.setOnLoadCallback(drawUserChart);
      // Draw the bar chart for the jobs posted when Charts is loaded.
      google.charts.setOnLoadCallback(drawJobsChart);
      //Draw column chart for jobs by type
      google.charts.setOnLoadCallback(drawJobsByType);

      function drawUserChart() {

        var data = google.visualization.arrayToDataTable([
          ['Users', '-'],
          ['Employer',   <?php echo $noOfEmp;?>],
          ['JobSeeker',  <?php echo $noOfJsk?>],
        ]);

        var options = {
          title: 'Users Registered'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
       // Callback that draws the pie chart for jobsBarChart.
      function drawJobsChart() {

      var data = google.visualization.arrayToDataTable([
        ['Job Status', 'No of jobs',],
        <?php while($jobsArray = mysqli_fetch_array($resultJobs,MYSQLI_ASSOC)){ ?>
        [<?php echo "'".$jobsArray["job_status"]."'";?>, <?php echo intval($jobsArray["no_of_jobs"]);?>],
        
        <?php }?>
      
      ]);

      var options = {
        title: 'Jobs Distribution by Job Status',
        chartArea: {width: '50%'},
        hAxis: {
          title: 'Total Jobs',
          minValue: 0
        },
        vAxis: {
          title: 'Job Status'
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('jobsBarChart'));

      chart.draw(data, options);
    }

function drawJobsByType() {
      var data = google.visualization.arrayToDataTable([
        ['Job Type', 'No of Jobs Posted', 'No of applications','No of jobs successfully filled'],
        <?php foreach($jobPostedArray AS $jobType => $jobsPostedCnt){ ?>
        [<?php echo "'".$jobType."'"; ?>, <?php echo $jobsPostedCnt;?>, <?php echo $jobsAppliedArray[$jobType];?>, <?php echo $jobFilledArray[$jobType]?>],
        <?php }?>
        
      ]);

      var options = {
        title: 'Distibution of jobs by category',
        chartArea: {width: '50%'},
        hAxis: {
          title: 'Job Types',
          minValue: 0,
          slantedText:true,
          slantedTextAngle:45
        },
        vAxis: {
          title: 'Jobs'
        }
      };

      var chart = new google.visualization.ColumnChart(
        document.getElementById('jobsByType'));

      chart.draw(data, options);
    }
    </script>
</head>
<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <?php include("partials/nav.inc.php");?>
        <?php include("partials/sidebar.inc.php");?>
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Welcome Admin</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <h3>Job Portal Statistics</h3>
                    <div class="col-lg-6">
                    <div class="thumbnail alert alert-warning">
                         <span class="glyphicon glyphicon-user" aria-hidden="true"></span> REGISTERED CANDIDATES<br>
                         <?php
                         $sql = "SELECT id FROM user_account WHERE is_delete='0' AND user_type_id!='1'";
                         $result = mysqli_query($db,$sql);
                         $noOfCadidates = mysqli_num_rows($result);

                         echo "<strong>".$noOfCadidates."</strong>";?>
                    </div>
                        </div>
                    <div class="col-lg-6">
                    <div class="thumbnail alert alert-warning">
                         <span class="glyphicon glyphicon-user" aria-hidden="true"></span> PENDING CANDIDATES APPROVAL<br>
                         <?php
                          $sql = "SELECT id FROM user_account WHERE is_delete='0' AND user_type_id!='1' AND is_approved='0'";
                         $result = mysqli_query($db,$sql);
                         $noOfCadidatesNotApporoved = mysqli_num_rows($result);

                         echo "<strong>".$noOfCadidatesNotApporoved."</strong>";?>
                    </div>
                        </div   >
                </div>
                <div class="row">
                    <div class="col-lg-6">
                    <div class="thumbnail alert alert-warning">
                         <span class="glyphicon glyphicon-lock" aria-hidden="true"></span> TOTAL JOBS POSTED<br>
                         <?php
                         $sql = "SELECT id FROM job_post WHERE is_delete='0' AND job_status='2'";
                         $result = mysqli_query($db,$sql);
                         $noOfJobs = mysqli_num_rows($result);

                         echo "<strong>".$noOfJobs."</strong>";?>
                    </div>
                        </div>
                    <div class="col-lg-6">
                    <div class="thumbnail alert alert-warning">
                         <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> TOTAL APPLICATIONS<br>
                         <?php
                          $sql = "SELECT id FROM job_post_activity WHERE is_removed='0' AND status !='5'";
                         $result = mysqli_query($db,$sql);
                         $noOfApplication = mysqli_num_rows($result);

                         echo "<strong>".$noOfApplication."</strong>";?>
                    </div>
                        </div   >
                </div>
                <div class="row">
                    <div class="col-lg-6 ">

                    <div class="thumbnail" id="piechart" style="width: 100%;height:300px"></div>
                    </div>
                    <div class="col-lg-6 ">

                    <div class="thumbnail" id="jobsBarChart" style="width: 100%;height: 300px "></div>
                    </div>
                </div>
                 <div class="row">
                    <div class="col-lg-12 ">

                    <div class="thumbnail" id="jobsByType" style="height:400px;width:100%" ></div>
                    </div>
                    
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
     <!--#wrapper -->
<?php include("partials/footer.inc.php");?>
</body>
</html>