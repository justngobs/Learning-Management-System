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
$cantcatchme = 0;//validation variable
$errors = "";//error handling variable

// Validate name entered
if(empty($_POST['name'])){
	$errors .= "Name is required";
}
else{
	$stu_name1 = test_input($_POST['name']);
	$cantcatchme += 1;
    if (!preg_match("/^[a-zA-Z ]*$/",$stu_name1)) {
      $cantcatchme -= 1;
      $errors .= "Name should only contain letters<br>";
    }
}

// Validate surname entered
if(empty($_POST['surname'])){
	$errors .= "Surname is required<br>";
}
else{
	$stu_name2 = test_input($_POST['surname']);
	$cantcatchme += 1;
    if (!preg_match("/^[a-zA-Z ]*$/",$stu_name2)) {
      $cantcatchme -= 1;
      $errors .= "Surname should only contain letters<br>";
    }
}

// Validate grade entered
if(empty($_POST['grade'])){
	$errors .= "Grade is required<br>";
}
else{
	$stu_grade = test_input($_POST['grade']);
	if(is_numeric($stu_grade)){
	$cantcatchme += 1;}
	if($stu_grade < 8 || $stu_grade > 12) {
		$cantcatchme -= 1;
		$errors .= "Grade should between 8 and 12<br>";
	}
}

// Validate id number
if(empty($_POST['stu_idNumber'])){
	$errors .= "ID number is empty";
}
else{
	$stu_idNumber = test_input($_POST['stu_idNumber']);
	if(is_numeric($stu_idNumber)){
		$cantcatchme += 1;
	}

}

// Validate birth date
if(empty($_POST['birth'])){
	$errors .= "Date of birth is required<br>";
}
else{
	$stu_birth = test_input($_POST['birth']);
	if (isRealDate($stu_birth)) {
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
	$stu_gender = test_input($_POST['gender']);
	if($stu_gender == "F" || $stu_gender == "M")
	{
		$cantcatchme += 1;
	}
}


// if the validation went well do this
if ($cantcatchme == 6){

	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
	
	$id   = $_POST['c_student'];
	$query = "UPDATE seed SET stu_name = '$stu_name1', stu_surname = '$stu_name2', stu_grade = '$stu_grade' , stu_dob = '$stu_birth' ,stu_gender = '$stu_gender', stu_id_num = '$stu_idNumber' WHERE stu_id = '$id' ";
	if (mysqli_query($connection, $query)) {
	    echo "<form method='post' action='studentmanager.php' id='stu'><input type='text' hidden='hidden' name='c_student' value='".$id."'><input type='submit' hidden='hidden'></form>";
		echo "<script>alert('Updated Successfully');document.forms['stu'].submit();</script>";
	} 
	else {
	    echo "Error updating record: " . mysqli_error($connection);
	}

	mysqli_close($connection);
}

else {
	echo "Cant catch me fail with the following errors<br>";
	echo "<strong style='color:red'>".$errors."</strong>";
	echo "<br><br>Page will automatically redirect in 6 seconds... or <a href='studentmanager.php'>correct form mistakes</a>";
}
		
	

?>