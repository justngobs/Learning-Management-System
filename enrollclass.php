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

require 'phpgoodies.php';
$cantcatchme = 0;
$errors = "";


if(empty($_POST['s_teacher'])){
	//No teacher selected error
	$errors .= "You have to select an already existing teacher<br>";
}
else{
	//Validate some more because we dont trust users
	$teacher = test_input($_POST['s_teacher']);
	if(is_numeric($teacher)){
		$cantcatchme += 1;
	}
}


if(empty($_POST['check_list'])){
	$errors .= "You have to select teacher subjects<br>";
}
else{
	$cantcatchme += 1;
}


if($cantcatchme == 2){
	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	// Check if the current submitted data is from update of add user page
	$stmt = $connection->prepare("SELECT sub_id FROM class WHERE tea_id = ?");
	$stmt->bind_param("i",$teacher);
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows === 0){

	}
	else{
		$query1  = "DELETE FROM class WHERE tea_id='$teacher'";    
		$result1 = $connection->query($query1);    
		if (!$result1){ 
			echo "DELETE failed: $query<br>" . $connection->error . "<br><br>";  
		}
	}
	$stmt->close();

	foreach ($_POST['check_list'] as $subject) {
		//Do this only if the selected is numeric ... put
		if(is_numeric($subject)){
			//Do this if the selected subject appears in the subject table
			$stmt = $connection->prepare("SELECT sub_name FROM subjectss WHERE sub_id = ?");
			$stmt->bind_param("i",$subject);
			$stmt->execute();
			$result = $stmt->get_result();
			if($result->num_rows === 0) exit('No subjects found');
			else{
				$stmt->close();
				//Now add a subject and link it to the outer teacher variable
				if (!($stmt = $connection->prepare("INSERT INTO class ( tea_id, sub_id) VALUES(?, ?)"))) {
			    echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;}

			    $stmt->bind_param("ii", $a, $b); 
			    	$a = $teacher;
			    	$b = $subject;
			    $stmt->execute();
			    $stmt->close();
			}
		}
		else{
			echo $subject." is not numeric";
		}
	}
	mysqli_close($connection);

	echo "<script> document.location = 'teachermanager.php'; </script>";

}

else{
	echo "Cant catch me fail with the following errors ".$errors;
}

?>