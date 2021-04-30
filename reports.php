<?php
session_start();

if(!isset($_SESSION['loggedin'])){
	header('Location: login.php');
	exit();
}

$cantcatchme = 0;

if($_SESSION['ulevel'] == 3){
	$cantcatchme += 1;
}

if($cantcatchme == 1){

}

else{
	echo "You do not have permission to view this page on this server";
	exit();
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Report generator</title>
	<meta http-equiv="X-UA-Compatible" content="IE=7, IE=8, IE=9, IE=edge"/>
    <meta http-equiv="Pragma" content="no-cache">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="javascriptgoodies.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>	
<div class="w3-sidebar w3-bar-block" style="width: 20%; background-color: rgb(74,74,74);margin-left:1%; color: white">
	<a class="w3-bar-item w3-button" href="#"><i class="fa fa-user" style="font-size:48px;color:yellow"></i><i style="color: white"><b> Admin</b></i></a>
	<br>
	<strong>
		<a href="generalmanager.php" style="color: white"><i class="fa fa-bank" style="font-size: 30px;color: lightblue;"></i> General</a><br><br><br>
		<a href="studentmanager.php" style="color: white"><i class="fa fa-user" style="font-size: 30px;color: lightblue;"></i> Students</a><br><br><br>
		<a href="teachermanager.php" style="color: white"><i class="fa fa-user" style="font-size: 30px;color: lightblue;"></i> Teachers</a><br><br><br>
		<a href="subjectmanager.php" style="color: white"><i class="fa fa-book" style="font-size: 30px;color: lightblue;"></i> Subjects</a><br><br><br>
		<a href="messagemanager.php" style="color: white"><i class="fa fa-envelope" style="font-size: 30px;color: lightblue;"></i> Messages  </a><br><br><br>
		<a href="reports.php" style="color: white"><i class="fa fa-pie-chart" style="font-size: 30px;color: lightblue;"></i>         Reports</a><br><br><br><br>
		<a href="logout.php" style="color: white"><i class="fa fa-sign-out" style="font-size:30px;color:orange"></i> Sign Out</a>
	</strong>
</div>

<?php
require_once "app_config.php";
$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

$sql = "SELECT * FROM seed";
$result = mysqli_query($connection, $sql);

$male = 0;
$female = 0;

$grade8 = 0;
$grade9 = 0;
$grade10 = 0;
$grade11 = 0;
$grade12 = 0;


if(mysqli_num_rows($result) > 0){
	while($row = mysqli_fetch_assoc($result)){
		if($row['stu_grade'] == 8){
			$grade8 += 1;
		}
		if($row['stu_grade'] == 9){
			$grade9 += 1;
		}
		if($row['stu_grade'] == 10){
			$grade10 += 1;
		}
		if($row['stu_grade'] == 11){
			$grade11 += 1;
		}
		if($row['stu_grade'] == 12){
			$grade12 += 1;
		}
		if($row['stu_gender'] == "M"){
			$male += 1;
		}
		if($row['stu_gender'] == "F"){
			$female += 1;
		}
	}
}

$_SESSION['grade8'] = $grade8;
$_SESSION['grade9'] = $grade9;
$_SESSION['grade10'] = $grade10;
$_SESSION['grade11'] = $grade11;
$_SESSION['grade12'] = $grade12;

$_SESSION['male'] = $male;
$_SESSION['female'] = $female;

mysqli_close($connection);
?>

<?php
require_once "app_config.php";
$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

$grade8 = 0;
$grade9 = 0;
$grade10 = 0;
$grade11 = 0;
$grade12 = 0;
$subjects = 0;
$sql = "SELECT * FROM subjectss";
$result = mysqli_query($connection, $sql);
if(mysqli_num_rows($result) > 0){
	while($row = mysqli_fetch_assoc($result)){
		$subjects += 1;
		if($row['sub_grade'] == "8"){
			$grade8 += 1;
		}
		if($row['sub_grade'] == "9"){
			$grade9 += 1;
		}
		if($row['sub_grade'] == "10"){
			$grade10 += 1;
		}
		if($row['sub_grade'] == "11"){
			$grade11 += 1;
		}
		if($row['sub_grade'] == "12"){
			$grade12 += 1;
		}
	}
}
$_SESSION['s_grade8'] = $grade8;
$_SESSION['s_grade9'] = $grade9;
$_SESSION['s_grade10'] = $grade10;
$_SESSION['s_grade11'] = $grade11;
$_SESSION['s_grade12'] = $grade12;
$_SESSION['number_of_subjects'] = $subjects;

mysqli_close($connection); 

?>
<div id = "selection" style="margin-left: 25%">
	<div id = "content">
		<h3>Insights</h3>
		<br>
		<hr>
		<cite>There are <?php echo ($_SESSION['male'])+($_SESSION['female']);?> students erolled. Of the <?php echo ($_SESSION['male'])+($_SESSION['female']);?> , <?php echo ($_SESSION['male']);?> are males and <?php echo ($_SESSION['female']);?> are females.</cite>
		<hr>
		<div class="w3-cell">
			<div id="piechart"></div>
		</div>

		<div class="w3-cell">
			<div id="piechart2"></div>
		</div>
		<hr>
		<cite>There are <?php echo $_SESSION['number_of_subjects']?> subjects available from grade 8 through to grade 12.</cite>
		<hr>
		<div class="w3-cell">
			<div id="piechart3" style="width: 550px; height: 350px;"></div>
		</div>

		<div class="w3-cell">
			<div id="piechart4"></div>
		</div>
		<hr>
	</div>
</div>

<?php 

echo "<script type='text/javascript'>
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Task', 'Hours per Day'],
  ['Male', ".$_SESSION['male']."],
  ['Female', ".$_SESSION['female']."]
]);

  // Optional; add a title and set the width and height of the chart
  var options = {'title':'Male vs Female', 'width':550, 'height':400};


  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
}
</script>

<script type='text/javascript'>
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Task', 'Hours per Day'],
  ['Grade 8', ".$_SESSION['grade8']."],
  ['Grade 9', ".$_SESSION['grade9']."],
  ['Grade 10', ".$_SESSION['grade10']."],
  ['Grade 11', ".$_SESSION['grade11']."],
  ['Grade 12', ".$_SESSION['grade12']."]
]);

  // Optional; add a title and set the width and height of the chart
  var options = {'title':'Students per grade', 'width':550, 'height':400};


  var chart = new google.visualization.PieChart(document.getElementById('piechart2'));
  chart.draw(data, options);
}
</script>

<script type='text/javascript'>
      google.charts.load('current', {packages:['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Subjects per grade'],
          ['Grade 8',     11],
          ['Grade 9',      2],
          ['Grade 10',  2],
          ['Grade 11', 2],
          ['Grade 12',    7]
        ]);

        var options = {
          title: 'Subjects per grade',
          pieHole: 0.3,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart3'));
        chart.draw(data, options);
      }
    </script>

";

?>
</body>
</html>