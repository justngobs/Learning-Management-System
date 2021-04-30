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
	$errors .= "Title is empty<br>";
}
else{
	$announcement = test_input($_POST['announcement']);
	$cantcatchme += 1;
}

if($cantcatchme == 2){
	require "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

    if (!($stmt = $connection->prepare("INSERT INTO general_announcement (gen_ann_name, gen_ann_description) VALUES(?, ?)"))) {
    echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;}

    $stmt->bind_param("ss", $a, $b); 
    	$a = $title;
    	$b = $announcement ;
    	
    $stmt->execute();
    $stmt->close();
	$connection->close();
	
	echo "<script> document.location ='generalmanager.php'; </script>";
}

else{
	echo $errors;
}
?>