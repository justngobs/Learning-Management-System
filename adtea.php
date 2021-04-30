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
$cantcatchme = 0;
$errors = "";
$teacher_selected = 0;

// Validate the name field
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
// Validate the surname field
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
// Validate the email field
	if(empty($_POST['email'])){
		$errors .= "Email is empty<br>";
	}
	else{
		$tea_email = test_input($_POST['email']);
		$cantcatchme += 1;
		if (!filter_var($tea_email, FILTER_VALIDATE_EMAIL)) {
	    	$cantcatchme -= 1;
	    	$errors .= "E-mail is invalid<br>";
	    }
	}
// Validate the birth field
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
if(empty($_POST['idNumber'])){
	$errors .= "ID number is empty";
}
else{
	$tea_idNumber = test_input($_POST['idNumber']);
	if(is_numeric($tea_idNumber)){
		$cantcatchme += 1;
	}

}


// Validate subject selections
	if(empty($_POST['check_list'])){
		$errors .= "You have to select teacher subjects<br>";
	}
	else{
		//validate the subject input here
	}

if ($cantcatchme == 6){
	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	$sql1 = "SELECT sub_name, sub_id, sub_grade FROM subjectss";
	$result1 = mysqli_query($connection, $sql1);
	if(mysqli_num_rows($result1) > 0){ 

		$sql2 = "SELECT member_id FROM member WHERE member_email = '$tea_email'";
		$result2 = mysqli_query($connection, $sql2);
		if(mysqli_num_rows($result2) > 0) exit("email ".$tea_email." already exist, <a href='teachermanager.php'> go back</a>");
		else {

			if (!($stmt = $connection->prepare("INSERT INTO member (member_email, member_password, member_level) VALUES (?, ?, ?)"))){
				echo "prepare failed: (" .$connection->errno. ") ". $connection->error;}

			//Create a default password
			$passwd = "password";

			//Hash the password and save it into the database
			$sys_passw = password_hash($passwd, PASSWORD_DEFAULT);
			$stmt->bind_param("ssi",$a,$b, $c);
			$a = $tea_email;
			$b = $sys_passw;
			$c = 2;
			$stmt->execute();
			$tea = $connection->insert_id;
			$stmt->close();

			if (!($stmt = $connection->prepare("INSERT INTO farmer (tea_id, tea_name, tea_surname, tea_email, tea_dob, tea_gender, tea_id_num) VALUES(?, ?, ?, ?, ?, ?, ?)"))) {
			echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;}

			$stmt->bind_param("issssss",$z, $a, $b, $c, $d, $e, $f); 
			$a = $tea_name;
			$b = $tea_surname;
			$c = $tea_email;
			$d = $tea_birth;
			$e = $tea_gender;
			$z = $tea;
			$f = $tea_idNumber;
			$stmt->execute();
			$stmt->close();

			// Link a subject with a teacher
			$sql = "SELECT sub_name, sub_id, sub_grade FROM subjectss ORDER BY sub_id ASC";
			$result = mysqli_query($connection, $sql);

			echo"<div class='w3-modal' style='display:block'>
				<div class='w3-modal-content'>
			<div class='w3-container'>
			<b style='color:orange'>select teacher subjects</b>:<br>";
			echo "<div style='text-align:left;'><form method='POST' action=enrollclass.php>";
			echo "<input type='number' name='s_teacher' value='".$tea."' hidden='hidden'>";
			if(mysqli_num_rows($result) > 0){
				while($row = mysqli_fetch_assoc($result)){
					echo "<input type='checkbox' name='check_list[]' value='".$row['sub_id']."' >".htmlspecialchars($row['sub_name'])."_Grade: ".htmlspecialchars($row['sub_grade'])."</input><br>";
				}
			}
			echo "<input type='submit' value='Add teacher classes'></form></div></div></div></div>";		
		}
		}
	else{
		echo "There are no subjects yet available <a href='subjectmanager.php'>Add a new subject here</a>";
	}
mysqli_close($connection);	
					   
}
	
else {
	echo "cant catch me fail with the following erros<br>";
	echo "<strong style='color:red'>".$errors."</strong>";
	echo "<br><br>Page will automatically redirect in 6 seconds... or <a href='teachermanager.php'>correct form mistakes</a>";
}
?>
</body>
</html>