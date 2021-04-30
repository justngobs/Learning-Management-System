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
<title>Diepdale Secondary School Home Page</title>
<meta charset="UTF-8">
<meta name="description" content="Diepdale secondary home page">
<meta name="keywords" content="diepdale, diepdale secondary, high school, matric, grade 8, grade 9, grade 10, grade 11, grade 12, school website">
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
if(empty($_POST['name'])){
	$errors .= "Name is empty<br>";
}
else{
	//sanitize input
	$stu_name1 = test_input($_POST['name']);
	$cantcatchme += 1;
	//check if name is letters only and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$stu_name1)) {
      $cantcatchme -= 1;
      $errors .= "Name should contain letters only<br>";
    }
}

if(empty($_POST['surname'])){
	$errors .= "Surname is empty<br>";
}
else{
	$stu_name2 = test_input($_POST['surname']);
	$cantcatchme += 1;
    if (!preg_match("/^[a-zA-Z ]*$/",$stu_name2)) {
      $cantcatchme -= 1;
      $errors .= "Surname should contain letters only<br>";
    }

}

if(empty($_POST['email'])){
	$errors .= "Email is empty<br>";
}
else{
	$stu_email = test_input($_POST['email']);
	$cantcatchme += 1;

	//Validate if user entered real email 
	if (!filter_var($stu_email, FILTER_VALIDATE_EMAIL)) {
    	$cantcatchme -= 1;
    	$errors .= "E-mail is invalid<br>";
    }
}

if(empty($_POST['grade'])){
	$errors .= "Grade is empty<br>";
}
else{
	$stu_grade = test_input($_POST['grade']);
	if(is_numeric($stu_grade)){
	$cantcatchme += 1;}
	// check if grade is within a high school range
	if($stu_grade < 8 || $stu_grade > 12) {
		$cantcatchme -= 1;
		$errors .= "Grade should be from 8 to 12<br>";
	}
}

// Validate id number
if(empty($_POST['idNumber'])){
	$errors .= "ID number is empty";
}
else{
	$stu_idNumber = test_input($_POST['idNumber']);
	if(is_numeric($stu_idNumber)){
		$cantcatchme += 1;
	}

}

// validate date of birth
if(empty($_POST['birth'])){
	$errors .= "Date of birth is empty<br>";
}
else{
	$stu_birth = test_input($_POST['birth']);
	// make sure the user really entered a date
	if (isRealDate($stu_birth)) {
	    $cantcatchme += 1;
	}
	else {
	    $errors .= "Date of birth is invalid<br>";
	}
}

// Validate gender selection
if(empty($_POST['gender'])){
	$errors .= "Select student gender<br>";
}
else{
	$stu_gender = test_input($_POST['gender']);
	if($stu_gender == "F" || $stu_gender == "M")
	{
		$cantcatchme += 1;
	}
}
//if the verification went well then connect to db
if ($cantcatchme == 7){
	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

 //check if there are subjects available for a certain grade and if not refuse fuarther action/ terminate
 //at this point its improbable to use paramatized queries on Variable $stu_grade but because of paranoia we will do that
//But no, actually we wont be doing that/
    $sql1 = "SELECT sub_name, sub_id FROM subjectss WHERE sub_grade = '$stu_grade'";
	$result1 = mysqli_query($connection, $sql1);
	if(mysqli_num_rows($result1) > 0){ 
		$sql2 = "SELECT member_id FROM member WHERE member_email = '$stu_email'";
		$result2 = mysqli_query($connection, $sql2);
		if(mysqli_num_rows($result2) > 0) exit("email ".$stu_email." already exist, <a href='studentmanager.php'> go back</a>");
		else {
		if (!($stmt = $connection->prepare("INSERT INTO member (member_email, member_password, member_level) VALUES (?, ?, ?)"))){
			echo "prepare failed: (" .$connection->errno. ") ". $connection->error;}

		//Create a default password
		$passwd = "password";

		//Hash password and save information to member table
		$sys_passw = password_hash($passwd, PASSWORD_DEFAULT);
		$stmt->bind_param("ssi",$a,$b, $c);
		$a = $stu_email;
		$b = $sys_passw;
		$c = 1;
		$stmt->execute();
		$stu = $connection->insert_id;
		$stmt->close();


		if (!($stmt = $connection->prepare("INSERT INTO seed (stu_id, stu_name, stu_surname, stu_email, stu_grade, stu_dob, stu_gender, stu_id_num ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"))) {
	    echo "prepare failed: (" . $connection->errno . ") " . $connection->error;}

		    $stmt->bind_param("isssisss", $z, $a, $b, $c, $d, $e, $f,$g); 
		    	$a = $stu_name1 ;
		    	$b = $stu_name2 ;
		    	$c = $stu_email ;
		    	$d = $stu_grade ;
		    	$e = $stu_birth ;
		    	$f = $stu_gender;
		    	$z = $stu;
		    	$g = $stu_idNumber;
		    	
		    $stmt->execute();
		    
		    

		    $sql = "SELECT sub_name, sub_id FROM subjectss WHERE sub_grade = '$stu_grade'";
			$result = mysqli_query($connection, $sql);
			echo"<div class='w3-modal' style='display:block'>
		  	<div class='w3-modal-content'>
		    <div class='w3-container'>
			<b style='color:orange'>select student subjects</b>:<br>";
			echo "<form method='POST' action='enrol.php'>";
			echo "<input type='number' name='s_student' value='".$stu."' hidden='hidden'>";
			if(mysqli_num_rows($result) > 0){
			$stmt->close();
				while($row = mysqli_fetch_assoc($result)){
					echo "<input type='checkbox' name='check_list[]' value='".$row['sub_id']."' >".$row['sub_name']."</input><br>";
				}

				echo "<br><input type='submit' value='Enrol student' name='enrolstudent'></form></div></div></div>";
			}
		}
		}
		else{
			echo "No subjects available yet for grade ".$stu_grade."<br><br><a href='studentmanager.php'>Go back</a>";
		}

	$connection->close();
	}


else {
	echo "cant catch me fail with the following erros<br>";
	echo "<strong style='color:red'>".$errors."</strong>";
	echo "<br><br>Page will automatically redirect in 6 seconds... or <a href='studentmanager.php'>correct form mistakes</a>";
}
?>
</body>
</html>