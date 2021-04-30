<?php
session_start();
require "app_config.php";
$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

if(!isset($_POST['username'], $_POST['password'])){
	die ('Please fill both the username and password fields');
}

//Prepare the sql
if($stmt = $connection->prepare('SELECT member_id, member_password, member_level FROM member WHERE member_email = ?')){
$stmt->bind_param('s', $_POST['username']);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows > 0){
	$stmt->bind_result($id, $password, $level);
	$stmt->fetch();
	//Account exist now we verify the password./replace with/ if ($_POST['password'] === $password)
	if(password_verify($_POST['password'], $password)){
		//Verification success, user has logged in
		session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $_POST['username'];
		$_SESSION['id'] = $id;
		$_SESSION['ulevel'] = $level;
		$_SESSION['ip'] = $_SERVER['REMOTE_ADDR']; 
		//if ($_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) different_user();

		
		if($_SESSION['ulevel'] == 1 ){

			header('Location: student.php');
		}

		if($_SESSION['ulevel'] == 2 ){
			header('Location: teacher.php');
		}

		if($_SESSION['ulevel'] == 3 ){
			header('Location: messagemanager.php');
		}


	} 

	else{
		echo "<form method='post' action='login.php' id='loginFeedback'>
				<input type='text' hidden='hidden' name='loggin_error' value='Incorrect password or username.'>
				<input type='submit' hidden='hidden'>
			</form>";
		echo "<script> document.forms['loginFeedback'].submit();</script>";
	}
} 

else{
	echo "<form method='post' action='login.php' id='loginFeedback'>
			<input type='text' hidden='hidden' name='loggin_error' value='Incorrect password or username.'>
			<input type='submit' hidden='hidden'>
		</form>";
	echo "<script> document.forms['loginFeedback'].submit();</script>";	
}

$stmt->close();
$connection->close();
}
?>