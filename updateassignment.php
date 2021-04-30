<?php
session_start();

if(!isset($_SESSION['loggedin'])){
	header('Location: login.php');
	exit();
}

$cantcatchme = 0;

if($_SESSION['ulevel'] == 2){
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
	<title>Manage assignments</title>
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<link rel="stylesheet" type="text/css" href="tooltip.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="javascriptgoodies.js"></script>
	<script type="text/javascript" src="javascriptFormsValidations.js"></script>
</head>
<body style="background-color: rgb(249,247,243)">
<?php
require 'phpgoodies.php';
$cantcatchme = 0;
$errors = "";


if(isset($_POST['title']) && isset($_POST['description']) && isset($_POST['fileOption']) && isset($_POST['dueDate']) && isset($_POST['totalMarks']) && !empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['fileOption']) && !empty($_POST['dueDate']) && !empty($_POST['totalMarks'])){
	$cantcatchme = 0;
	$errors = "";

	//Validate the due date if is date or if greater than the current date
	$due_date = test_input($_POST['dueDate']);
	// make sure the user really entered a date
	if (isRealDate($due_date)) {
	    $cantcatchme += 1;
	}
	else{
		$errors .= "* Invalid due date.<br>";
	}

	//Validate the dropdown for the file enable and disable link
	$file_option = test_input($_POST['fileOption']);
	if($file_option == "enable" || $file_option == "disable"){
		$cantcatchme += 1;
	}
	else{
		$errors .= "* File option should be enable or disable.<br>";
	}

	//Validate the mark entered
	$mark = test_input($_POST['totalMarks']);
	if(is_numeric($mark)){
		if($mark > 0){
			$cantcatchme += 1;
		}
	}
	else{
		$errors .= "* Mark should be greater than zero.<br>";
	}

	//Validate the title for the assessment
	$assessment = test_input($_POST['title']);
	if(!preg_match('/^[a-zA-Z0-9 .,:()]+$/', $assessment)) {
      $errors .= "Title should contain letters, numbers and symbols :,.()<br>";
    }
    else{
    	$cantcatchme += 1;
    }

	//Validate assignment description
	$description = test_input($_POST['description']);
	if(!preg_match('/^[a-zA-Z0-9 .,:()]+$/', $description)) {
      $errors .= "Description should contain letters, numbers and symbols :,.()<br>";
    }
    else{
    	$cantcatchme += 1;
    }

    $assessment_id = test_input($_POST['c_assignment']);
    if(is_numeric($assessment_id)){
    	$cantcatchme += 1;
    }
    else{
    	$errors .= "Assessment ID is invalid";
    }
}

if($cantcatchme == 6){

	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	$stmt = $connection->prepare("UPDATE assignment SET ass_name = ?, ass_description = ?, due_date = ?, marks = ?,  upload_link = ? WHERE ass_id = ? ");

	$stmt->bind_param("sssisi",$assessment, $description, $due_date, $mark, $file_option, $assessment_id);
	$stmt->execute();
	$stmt->close();
	mysqli_close($connection);

	echo "<script> alert('Successfully updated.'); document.location = 'manageassignment.php';</script>";
}
else{
	echo "<script> alert('Error updating.'); document.location = 'manageassignment.php';</script>";
}


?>
</body>
</html>