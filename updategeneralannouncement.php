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

// Validate title entered entered
if(empty($_POST['title'])){
	$errors .= "Title is empty<br>";
}
else{
	$title = test_input($_POST['title']);
	$cantcatchme += 1;
}

// Validate announcement entered
if(empty($_POST['announcement'])){
	$errors .= "announcement is empty<br>";
}
else{
	$announcement = test_input($_POST['announcement']);
	$cantcatchme += 1;
}

if($cantcatchme == 2){
//Everything is ok now update some data
	$id = $_POST['c_announcement'];
	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
	$stmt = $connection->prepare("UPDATE general_announcement SET gen_ann_name = ?, gen_ann_description = ? WHERE gen_ann_id = ? ");

	$stmt->bind_param("ssi", $title, $announcement, $id);
	$stmt->execute();
	$stmt->close();
	mysqli_close($connection);

	echo "<script> document.location = 'generalmanager.php'; </script>";


}

else{
	echo "Something is not right<br>".$errors;
}