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
<body style="background-color: rgb(223,233,190)">

<?php 

require "phpgoodies.php";
$cantcatchme = 0;//authentication mechanism
$errors = "";//error handling variable

//check if name is empty
if(empty($_POST['c_teacher'])){
	$errors .= "blank<br>";
}
else{
	//sanitize input
	$teacher = test_input($_POST['c_teacher']);
	//check if name is letters only and whitespace
    if(is_numeric($teacher)){
    	$cantcatchme += 1;
    }
}

if($cantcatchme == 1){

	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	// Link a subject with a teacher
	$sql = "SELECT sub_name, sub_id, sub_grade FROM subjectss ORDER BY sub_id ASC";
	$result = mysqli_query($connection, $sql);

	echo"<div class='w3-modal' style='display:block'>
		<div class='w3-modal-content'>
	<div class='w3-container'>
	<b style='color:orange'>select teacher subjects</b>:<br>";
	echo "<div style='text-align:left;'><form method='POST' action=enrollclass.php>";
	echo "<input type='number' name='s_teacher' value='".$teacher."' hidden='hidden'>";
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
			echo "<input type='checkbox' name='check_list[]' value='".$row['sub_id']."' >".htmlspecialchars($row['sub_name'])."_Grade: ".htmlspecialchars($row['sub_grade'])."</input><br>";
		}
		echo "<input type='submit' value='Update teacher classes'></form></div></div></div></div>";
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