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
$cantcatchme = 0;
$errors = "";

// Validate title entered entered
if(empty($_POST['title'])){
	$errors .= "Title is empty<br>";
}
else{
	$title = test_input($_POST['title']);
	//Do validation of announcement title
	if(!preg_match('/^[a-zA-Z0-9 .,:()]+$/', $title)) {
     $errors .= "Title should contain letters, numbers and symbols :,.()<br>";
    }
    else{
    	$cantcatchme += 1;
    }
}

// Validate announcement entered
if(empty($_POST['announcement'])){
	$errors .= "Title is empty<br>";
}
else{
	$announcement = test_input($_POST['announcement']);
	//Do validation of announcement
	if(!preg_match('/^[a-zA-Z0-9 .,:()]+$/', $announcement)) {
    $errors .= "Description should contain letters, numbers and symbols :,.()<br>";
    }
    else{
    	$cantcatchme += 1;
    }
	
}

if($cantcatchme == 2){
//Everything is ok now update some data
	$id = $_POST['c_announcement'];

	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
	
	$stmt = $connection->prepare("UPDATE announcement SET ann_name = ?, ann_description = ?, last_modified = ? WHERE ann_id = ? ");
	$d = date("Y-m-d");
	$editor_name = $_SESSION['teacher_name'];
	$editor_surname = $_SESSION['teacher_surname'];
	$poster = $d."(".$editor_name." ".$editor_surname.")";
	$stmt->bind_param("sssi", $title, $announcement, $poster, $id);
	$stmt->execute();
	$stmt->close();
	mysqli_close($connection);

	//I am not sure if this is a good way to do things but were are just giving the previous some data that it is expecting
	//Send the sent announcement id back to the previous page
	echo "<form method='post' action='manageannouncement.php' id='ann'><input type='text' hidden='hidden' name='c_announcement' value='".$id."'><input type='submit' hidden='hidden'></form>";
	echo "<script>alert('Updated Successfully');document.forms['ann'].submit();</script>";
	

}

else{
	echo "<script> alert('sorry, something went wrong.');document.location = 'manageannouncement.php';</script>";
}