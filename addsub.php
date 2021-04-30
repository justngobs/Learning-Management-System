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
		$errors .= "Name is empty<br>";
	}
	else{
		$sub_name = test_input($_POST['name']);
		$cantcatchme += 1;
	    if (!preg_match("/^[a-zA-Z ]*$/",$sub_name)) {
	      $cantcatchme -= 1;
	      $errors .= "Name should contain letters only<br>";
	    }
	}

	if(empty($_POST['grade'])){
		$errors .= "Grade is required<br>";
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
if($cantcatchme == 2){
	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

    if (!($stmt = $connection->prepare("INSERT INTO subjectss ( sub_name, sub_grade, sub_code) VALUES(?, ?, ?)"))) {
    echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;}

    $stmt->bind_param("sis", $a, $b, $c); 
    $a = $sub_name ;
    $b = $sub_grade ;
    $c = $sub_name[0].$sub_name[1].$sub_name[2].$sub_name[3].$sub_name[4].$sub_grade;

    $stmt->execute();
    $stmt->close();
    $connection->close();
	header("Location: subjectmanager.php");
}

else{
	echo "Cant catch me fail with the following errors:<br>".$errors;
}