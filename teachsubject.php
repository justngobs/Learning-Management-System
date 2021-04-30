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

?>
<!DOCTYPE html>
<html>
<head>
	<title>Teach subject</title>
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="background-color: rgb(249,247,243)">
<div class="w3-sidebar w3-bar-block" style="width: 20%; background-color: rgb(74,74,74);">
	<a href='teacher.php'><i class="fa fa-arrow-circle-left" style="font-size:48px;color:yellow"></i></a>
	<br>
	<?php
	require "phpgoodies.php";
	//Session variable assumed to be the user id, We will use it as the basis for authentication
	$user_i = $_SESSION['id'];

	//Variables from the form. Verify them and them make session variables to use in the next level of screen
	$cantcatchme = 0;
	 
	 if(empty($_GET['subject_id'])){
	 	// an error occured here it means
	 }
	 else{
	 	$cantcatchme += 1;
		 $subject_i = test_input($_GET['subject_id']);
		 
		require_once 'app_config.php';
		$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
	 	//Try to see if the teacher is really teaching the selected subject
	 	if($stmt = $connection->prepare('SELECT tea_id FROM class WHERE  sub_id = ?')){
			$stmt->bind_param('s', $subject_i);
			$stmt->execute();
			$stmt->store_result();

			if($stmt->num_rows > 0){
				$_SESSION['current_subject'] = $subject_i;
				$stmt->close();

				if($stmt = $connection->prepare('SELECT sub_name, sub_code FROM subjectss WHERE  sub_id = ?')){
					$stmt->bind_param('s', $subject_i);
					$stmt->execute();
					$stmt->store_result();

					if($stmt->num_rows > 0){
						$stmt->bind_result($subject_n, $subject_c);
						$stmt->fetch();
						$_SESSION['subject_name'] = $subject_n;
						$_SESSION['subject_code'] = $subject_c;
						$stmt->close();
					}
					else{
						header("Location: teacher.php");
					}
				}

			}
			else{
				header("Location: teacher.php");
			}
		}
	 }

	 if($cantcatchme == 1){
		//Display this information onky if the information from the previous form is correct
		echo "<h4 style='color:white;margin-left:20px'><u>".htmlspecialchars(ucfirst($subject_n))." ( ".htmlspecialchars($subject_c)." </u>)</h4><br>";
		require 'teachermenu.php';

	 }
	 else{
	 	header('Location: teacher.php');
	 }
	 ?>
</div>
<div style="margin-left: 25%">
	<div id = "content">
		<div id="Recent">
				<script type="text/javascript">
					document.location = 'manageannouncement.php';
				</script>
			</div>
		</div>
	</div>
</div>
</body>
</html>