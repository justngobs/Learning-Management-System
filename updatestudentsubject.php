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
<html lang="en">
<head>
<title></title>
<meta charset="UTF-8">
<meta name="author" content="Master Q">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="w3.css">
<script src='https://www.google.com/recaptcha/api.js'></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="css/style1.css">
</head>
<body>

<?php 

require "phpgoodies.php";
$cantcatchme = 0;//authentication mechanism
$errors = "";//error handling variable

//check if name is empty
if(empty($_POST['c_student'])){
	$errors .= "blank input<br>";
}
else{
	//sanitize input
	$student = test_input($_POST['c_student']);
	//check if name data is expected data
    if(is_numeric($student)){
    	$cantcatchme += 1;
    }
    else{
    	$errors .= "bad input<br>";
    }
}

if($cantcatchme == 1){
	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	// Get student grade from updated database records
	$sql0 = "SELECT stu_grade FROM seed WHERE stu_id = '$student'";
	$result0 = mysqli_query($connection, $sql0);
	$grade = 0;
	if(mysqli_num_rows($result0) > 0){
		while($row = mysqli_fetch_assoc($result0)){
			$grade = $row['stu_grade'];
		}
	}

	//Get previous student enrollments if they exist
	$sql_middle = "SELECT sub_id FROM enrol WHERE stu_id = '$student'";
	$result_middle = mysqli_query($connection, $sql_middle);
	$subjects = array();
	$counter = 0;
	if(mysqli_num_rows($result_middle) > 0){
		while($row = mysqli_fetch_assoc($result_middle)){
			$subjects[$counter] = $row['sub_id'];
			$counter += 1;
		}
	}


	// Link a subject with a student
	$sql = "SELECT sub_name, sub_id FROM subjectss WHERE sub_grade = '$grade'";
	$result = mysqli_query($connection, $sql);
	echo"<div class='w3-modal' style='display:block'>
		<div class='w3-modal-content'>
	<div class='w3-container'>
	<b style='color:orange'>select student subjects</b>:<br><br>";
	echo "<div style='text-align:left;'><form method='POST' action=enrol.php>";
	echo "<input type='number' name='s_student' value='".$student."' hidden='hidden'>";
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){

			if(in_array($row['sub_id'], $subjects)){
					echo "<input type='checkbox' name='check_list[]' value='".$row['sub_id']."' checked>".htmlspecialchars($row['sub_name'])."</input><br>";
			}
			else{
				echo "<input type='checkbox' name='check_list[]' value='".$row['sub_id']."' >".htmlspecialchars($row['sub_name'])."</input><br>";
			}
		}
		echo "<br><input type='submit' value='Update student subjects'></form><br><a href='studentmanager.php'>Cancel</a></div></div></div></div>";
	}
	else{
		echo "There are no subjects yet available <a href='subjectmanager.php'>Add a new subject here</a>";
	}

	mysqli_close($connection);
}

else{
	echo "something went wrong and we belive its either ".$errors;
}

?>
</body>
</html>