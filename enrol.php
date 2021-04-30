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
$checker = 0; //variable to check if its updating or adding to be used in if statement later
$errors = "";


if(empty($_POST['s_student'])){
	//No teacher selected error
	$errors .= "You have to select an already existing student<br>";
}
else{
	//Validate some more because we dont trust users
	$student = test_input($_POST['s_student']);
	if(is_numeric($student)){
		$cantcatchme += 1;
	}
}


if(empty($_POST['check_list'])){
	$errors .= "You have to select student subjects<br>";
}
else{
	$cantcatchme += 1;
}


if($cantcatchme == 2){
	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	// Check if the current submitted data is from update or add user page
	$stmt = $connection->prepare("SELECT sub_id FROM enrol WHERE stu_id = ?");
	$stmt->bind_param("i",$student);
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows === 0){
		$checker += 1;//This means its new data
	}
	else{
		$query1  = "DELETE FROM enrol WHERE stu_id='$student'";    
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
				//Now add a subject and link it to the outer student variable
				if (!($stmt = $connection->prepare("INSERT INTO enrol ( stu_id, sub_id) VALUES(?, ?)"))) {
			    echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;}

			    $stmt->bind_param("ii", $a, $b); 
			    	$a = $student;
			    	$b = $subject;
			    $stmt->execute();
			    $stmt->close();
			}
		}
	}
	mysqli_close($connection);
	if($checker == 0){
		echo "<form method='post' action='studentmanager.php' id='stu'><input type='text' hidden='hidden' name='c_student' value='".$student."'><input type='submit' hidden='hidden'></form>";
		echo "<script>document.forms['stu'].submit();</script>";
	}
	else{
		echo "<form method='post' action='studentmanager.php' id='stu'><input type='text' hidden='hidden' name='c_student' value='".$student."'><input type='submit' hidden='hidden'></form>";
		echo "<script>document.forms['stu'].submit();</script>";
	}
}

else{
	echo "Cant catch me fail with the following errors ".$errors;
}

?>