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

require "phpgoodies.php";
$cantcatchme = 0;
$errors = "";

if(empty($_POST['name'])){
	$errors .= "Subject name is required";
}
else{
	$sub_name = test_input($_POST['name']);
	$cantcatchme += 1;
    if (!preg_match("/^[a-zA-Z ]*$/",$sub_name)) {
      $cantcatchme -= 1;
      $errors .= "Name should only contain letters<br>";
    }
}

if(empty($_POST['grade'])){
	$errors .= "Subject grade is required<br>";
}
else{
	$sub_grade = test_input($_POST['grade']);
	if(is_numeric($sub_grade)){
	$cantcatchme += 1;}
	if($sub_grade < 8 || $sub_grade > 12) {
		$cantcatchme -= 1;
		$errors .= "Grade should between 8 and 12<br>";
	}
}

if($cantcatchme == 2)
{
	$id = test_input($_POST['c_subject']);
	$sub_code = $sub_name[0].$sub_name[1].$sub_name[2].$sub_name[3].$sub_name[4].$sub_grade;

	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	$stmt = $connection->prepare("UPDATE subjectss SET sub_name = ?, sub_grade = ?, sub_code = ? WHERE sub_id = ? ");

	$stmt->bind_param("sisi",$sub_name, $sub_grade, $sub_code, $id);
	$stmt->execute();
	$stmt->close();
	mysqli_close($connection);

	echo "<script> document.location = 'subjectmanager.php'; </script>";

}

else{
	echo $errors;
}
?>