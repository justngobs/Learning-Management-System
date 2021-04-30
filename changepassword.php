<?php
session_start();

if(!isset($_SESSION['loggedin'])){
	header('Location: login.php');
	exit();
}

$cantcatchme = 0;

if($_SESSION['ulevel'] == 1 || $_SESSION['ulevel'] == 2){
	$cantcatchme += 1;
}

if($cantcatchme == 1){

}

else{
	echo "You do not have permission to view this page on this server";
	exit();
}

?>

<html>
<head>
	<title></title>
</head>
<body>


<?php 
require "phpgoodies.php";
$cantcatchme = 0;
$errors = "";


//Validate the old password
if(empty($_POST['oldPassword'])){
	$errors .= "Password is empty. <br>";
}
else{
	$old_password = $_POST['oldPassword'];

	require_once "app_config.php";

	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	$result = mysqli_query($connection,"SELECT *from member WHERE member_id = '" . $_SESSION["id"] . "'");
	$row = mysqli_fetch_array($result);
	if(password_verify($old_password, $row["member_password"])) {
        $cantcatchme += 1;
    }
    else{
       $errors .= "Current Password is not correct. <br>";
       $cantcatchme -= 1;
	}
	mysqli_close($connection);
		
}


//Validate new password if it meet the minimum data used
if(empty($_POST['newPassword'])){
	$errors .= "Password cannot be empty. <br>";
}
else{
	$new_password = test_input($_POST['newPassword']);
	if (strlen($new_password) <= '8') {
        $errors .= "Your Password Must Contain At Least 8 Characters! <br>";
        $cantcatchme -= 1;
    }
    elseif(!preg_match("#[0-9]+#",$new_password)) {
        $errors .= "Your Password Must Contain At Least 1 Number! <br>";
        $cantcatchme -= 1;
    }
    elseif(!preg_match("#[A-Z]+#",$new_password)) {
        $errors .= "Your Password Must Contain At Least 1 Capital Letter! <br>";
        $cantcatchme -= 1;
    }
    elseif(!preg_match("#[a-z]+#",$new_password)) {
        $errors .= "Your Password Must Contain At Least 1 Lowercase Letter! <br>";
        $cantcatchme -= 1;
    }
}


//Validate confirmed password if it matches the password from above
if(empty($_POST['newPasswordConfirm'])){
	$errors .= "Password has not been confirmed. <br>";
}
else{
	$confirm_password = $_POST['newPasswordConfirm'];
	if($new_password == $confirm_password){
		$cantcatchme += 1;
	}
		
}

if($cantcatchme == 2){

	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
	mysqli_query($connection, "UPDATE member set member_password='" . password_hash($new_password, PASSWORD_DEFAULT) . "' WHERE member_id ='" . $_SESSION["id"] . "'");
    mysqli_close($connection);

    if($_SESSION['ulevel'] == 1){
	    echo "<form method='post' action='profile.php' id='change'><input type='text' hidden='hidden' name='changedPasswordPass' value='Password Successfully Changed'><input type='submit' hidden='hidden'></form>";
		echo "<script> document.forms['change'].submit();</script>";
	}
	if($_SESSION['ulevel'] == 2){
	    echo "<form method='post' action='teacherprofile.php' id='change'><input type='text' hidden='hidden' name='changedPasswordPass' value='Password Successfully Changed'><input type='submit' hidden='hidden'></form>";
		echo "<script> document.forms['change'].submit();</script>";
	}
}

else{

	if($_SESSION['ulevel'] == 1){
		echo "<form method='post' action='profile.php' id='change'><input type='text' hidden='hidden' name='changedPasswordFail' value='".$errors."'><input type='submit' hidden='hidden'></form>";
		echo "<script> document.forms['change'].submit();</script>";
	}
	if($_SESSION['ulevel'] == 2){
		echo "<form method='post' action='teacherprofile.php' id='change'><input type='text' hidden='hidden' name='changedPasswordFail' value='".$errors."'><input type='submit' hidden='hidden'></form>";
		echo "<script> document.forms['change'].submit();</script>";	
	}
}
?>
</body>
</html>