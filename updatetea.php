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

// Validate name entered
	if(empty($_POST['name'])){
		$errors .= "Name is empty<br>";
	}
	else{
		$tea_name = test_input($_POST['name']);
		$cantcatchme += 1;
	    if (!preg_match("/^[a-zA-Z ]*$/",$tea_name)) {
	      $cantcatchme -= 1;
	      $errors .= "Name should contain letters only<br>";
	    }
	}

// Validate surname entered
	if(empty($_POST['surname'])){
		$errors .= "Surname is empty<br>";
	}
	else{
		$tea_surname = test_input($_POST['surname']);
		$cantcatchme += 1;
	    if (!preg_match("/^[a-zA-Z ]*$/",$tea_surname)) {
	      $cantcatchme -= 1;
	      $errors .= "Surname should contain letters only<br>";
	    }

	}


// Validate birth date selection
	if(empty($_POST['birth'])){
		$errors .= "Date of birth is empty<br>";
	}
	else{
		$tea_birth = test_input($_POST['birth']);
		if (isRealDate($tea_birth)) {
		    $cantcatchme += 1;
		}
		else {
		    $errors .= "Date of birth is invalid<br>";
		}
	}

// Validate gender selection
	if(empty($_POST['gender'])){
		$errors .= "Select teacher gender<br>";
	}
	else{
		$tea_gender = test_input($_POST['gender']);
		if($tea_gender == "F" || $tea_gender == "M")
		{
			$cantcatchme += 1;
		}
	}

// Validate id number
if(empty($_POST['idNum'])){
	$errors .= "ID number is empty";
}
else{
	$tea_idNumber = test_input($_POST['idNum']);
	if(is_numeric($tea_idNumber)){
		$cantcatchme += 1;
	}

}

// if passed validation do the following
if ($cantcatchme == 5){
	
	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

    $id   = $_POST['c_teacher'];
	$query = "UPDATE farmer SET tea_name = '$tea_name', tea_surname = '$tea_surname', tea_dob = '$tea_birth', tea_gender = '$tea_gender', tea_id_num = '$tea_idNumber' WHERE tea_id = '$id' ";
	if (mysqli_query($connection, $query)) {

		echo "<script> document.location = 'teachermanager.php'; </script>";
	} 
	else {
	    echo "Error updating record: " . mysqli_error($connection);
	}

	mysqli_close($connection);
}

// if failed validation do these
else {
	echo "Cant catch me fail with the following errors<br>";
	echo "<strong style='color:red'>".$errors."</strong>";
	echo "<br><br><a href='teachermanager.php'>Go back and correct form mistakes</a>";
}

?>