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

require "phpgoodies.php";
$cantcatchme = 0;//validation variable
$errors = "";//error handling variable

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

$id = $_POST['quiz'];
if(is_numeric($id)){
	$cantcatchme += 1;
}
else{
	$errors .= "* Invalid quiz selected. #";
}


if($cantcatchme == 5){
	
	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	$stmt = $connection->prepare("UPDATE quiz SET  due_date = ?, quiz_title = ?, quiz_description = ?, n_of_questions = ?  WHERE quiz_id = ?");
	$stmt->bind_param("sssii",$DueDate, $title, $description, $n, $id);
	$stmt->execute();
	$stmt->close();

	mysqli_close($connection);
	echo "<script> alert('Updated Successfully');document.location = 'quiz.php';</script>";
}
else{
	echo "<script>alert('Error! something went wrong. Try again!');document.location = 'quiz.php';</script>";

}