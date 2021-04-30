<?php

session_start();

if(!isset($_SESSION['loggedin'])){
	header('Location: login.php');
	exit();
}

$cantcatchme = 0;

if($_SESSION['ulevel'] == 1){
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
	<title>subject</title>
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="background-color: rgb(249,247,243)">
<div class="w3-sidebar w3-bar-block" style="width: 20%; background-color: rgb(74,74,74);">
	<a href='student.php'><i class="fa fa-arrow-circle-left" style="font-size:48px;color:yellow"></i></a>
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
		 	//Try to see if the student is really taking the selected subject
		 	if($stmt = $connection->prepare('SELECT stu_id FROM enrol WHERE  sub_id = ? AND stu_id = ?')){
				$stmt->bind_param('ss', $subject_i, $user_i);
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
							mysqli_close($connection);
						}
						else{
							echo "<script> document.location = 'student.php';</script>";
						}
					}

				}
				else{
					echo "<script> document.location = 'student.php';</script>";
				}
			}
		 }

		 if($cantcatchme == 1){
			//Display this information onky if the information from the previous form is correct
			echo "<h4 style='color:white;margin-left:20px'><u>".htmlspecialchars(ucfirst($subject_n))." ( ".htmlspecialchars($subject_c)." </u>)</h4><br>";

			// Student navigation menu
			require 'studentmenu.php';

		 }
		 else{
		 	echo "<script> document.location = 'student.php';</script>";
		 }
	 ?>
</div>
<div style="margin-left: 25%">
	<div id = "content">
		<h3 style="text-align: center;"><b>Recent activity</b></h3>
		<div id="assignments" style="background-color: white">
			<h4><b>Assignments</b></h4>
			<br>
			<?php

				require_once 'app_config.php';
				$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

				$sub_id = $_SESSION['current_subject'];
				$sql = "SELECT ass_id, sub_id, tea_id, ass_name, ass_description, date_posted FROM assignment WHERE sub_id = '$sub_id' ORDER BY ass_id DESC LIMIT 3";
				$result = mysqli_query($connection, $sql);

				if(mysqli_num_rows($result) > 0){
					while($row = mysqli_fetch_assoc($result)){
						echo "<a href='submit.php?assessment=".$row['ass_id']."' style='width:90%;color:blue'><i class='fa fa-file' style='font-size:38px;color:rgb(200,220,240);margin-left:3%'></i><i style='color:white'>___</i>".htmlspecialchars(ucfirst($row['ass_name']))."<br></a><p style='margin-left:5%'>".htmlspecialchars($row['ass_description'])."</p><hr>";
					}
				}
				else{
					echo "No assignment posted yet.";
				}
				mysqli_close($connection);
			?>
		</div>
		<div id="announcements" style="background-color: white">
			<h4><b>Announcements</b></h4>
			<br>
			<?php

				require_once 'app_config.php';
				$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

				//Query to view all messages
				$c_subject = $_SESSION['current_subject'];
				$sql = "SELECT ann_id, ann_name, ann_description, date_posted, tea_id FROM announcement WHERE sub_id = '$c_subject' ORDER BY ann_id DESC LIMIT 3";
				$result = mysqli_query($connection, $sql);

				if(mysqli_num_rows($result) > 0){
					while($row = mysqli_fetch_assoc($result)){
						echo "<div style='margin-left:3%'>
							    <h6><b>".htmlspecialchars($row['ann_name'])."</b></h6>
							    <h6>Posted On: ".htmlspecialchars($row['date_posted'])."by ".htmlspecialchars($row['tea_id'])."(inner join on T_id)</h6>
							    <br>
							    <p>".htmlspecialchars($row['ann_description'])."</p>
							    <br>  
							  </div>
							  <hr>";
						}
				}
				else{
					echo "No announcement posted yet.";
				}

				mysqli_close($connection);
			?>
		</div>
	</div>
</div>
</body>
</html>