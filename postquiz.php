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
	<title>Create Quiz</title>
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<link rel="stylesheet" type="text/css" href="tooltip.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="javascriptgoodies.js"></script>
	<script type="text/javascript" src="javascriptFormsValidations.js"></script>
</head>
<body style="background-color: rgb(249,247,243)">

<?php

$errors = "";
$cantcatchme = 0;

require "phpgoodies.php";

$DueDate = test_input($_POST['due_date']);
// make sure the user really entered a date
if (isRealDate($DueDate)) {
    $cantcatchme += 1;
}
else{
	$errors .= "* Invalid due date.#";
}

$n = test_input($_POST['n']);
//verify the number of questions entered
if(is_numeric($n)){
	if($n > 0 && $n <= 100){
		$cantcatchme += 1;
	}
}
else{
	$errors .= "* Invalid number. #";
}


$title = test_input($_POST['title']);
//Verify the title of the thing
if(!preg_match('/^[a-zA-Z0-9 .,:()]+$/', $title)) {
  $errors .= "* Title should contain letters, numbers and symbols :,.()#";
}
else{
	$cantcatchme += 1;
}

$description = test_input($_POST['description']);
if(!preg_match('/^[a-zA-Z0-9 .,:()]+$/', $description)) {
  $errors .= "* Description should contain letters, numbers and symbols :,.()#";
}
else{
	$cantcatchme += 1;
}

$today = date('Ymd');
$status = 0;
$subject = $_SESSION['current_subject'];

if($cantcatchme == 4){
	
	require "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	$stmt = $connection->prepare("INSERT INTO quiz ( due_date, quiz_title, quiz_description, n_of_questions, status, sub_id, publish_date) VALUES (?,?,?,?,?,?,?)");
	$stmt->bind_param("sssiiis",$DueDate, $title, $description, $n, $status, $subject, $today);
	$stmt->execute();
	$stmt->close();

	mysqli_close($connection);

	echo "<script> document.location = 'quiz.php';</script>";
}
else{
	echo "<form method='post' action='quiz.php' id='qui'><input type='text' hidden='hidden' name='post_status' value='".$errors."'><input type='submit' hidden='hidden'></form>";
	echo "<script> document.forms['qui'].submit();</script>";
}

?>
</body>
</html>