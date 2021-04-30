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
	<title>Submit assignment</title>
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="javascriptgoodies.js"></script>
</head>
<body style="background-color: rgb(249,247,243)">

	<?php

	if(isset($_SESSION['subject_name']) && isset($_SESSION['subject_code']) && isset($_SESSION['current_subject']) && isset($_GET['assessment'])){
		echo "<div class='w3-sidebar w3-bar-block' style='width: 20%; background-color: rgb(74,74,74);'>
			<a href='assignment.php'><i class='fa fa-arrow-circle-left' style='font-size:48px;color:yellow'></i></a><a href='student.php'><i class='fa fa-home' style='font-size:48px;color:yellow;margin-left: 5%'></i></a>
			<br>";

		echo "<h4 style='color:white;margin-left:20px'><u>".htmlspecialchars($_SESSION['subject_name'])." ( ".htmlspecialchars($_SESSION['subject_code'])." </u>)</h4><br>";

		//Student navigation menu
		require 'studentmenu.php';
	}
	else{
		echo "<script> document.location = 'student.php';</script>";
	}
	?>
</div>
<div style="margin-left: 25%">
	<div id = "assignmentSubmit" style="width: 95%">
		<h4><b>Submit Assignment</b></h4>
			<div style="background-color: white">
			<?php 
				require "phpgoodies.php";

				if(isset($_GET['assessment'])){
					$assessment_id = test_input($_GET['assessment']);
					
					$assessment_subject = $_SESSION['current_subject'];
					$student_id = $_SESSION['id'];
					$cantcatchme = 0;
					
					require_once 'app_config.php';
					$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
					
					//Verify the assignment details if they are correct
					if(is_numeric($assessment_id) && !empty($assessment_id)){
						$sql = "SELECT ass_id FROM assignment WHERE sub_id = '$assessment_subject' AND ass_id = '$assessment_id'";
						$result = mysqli_query($connection, $sql);

						if(mysqli_num_rows($result) > 0){
							while($row = mysqli_fetch_assoc($result)){
								$cantcatchme += 1;
								$_SESSION['assignment_id'] = $assessment_id;//Store the assessment in a session Id

								//see if there is already a submission
								$id = $_SESSION['id'];
								$sql1 = "SELECT ass_sub_id FROM assignment_submission WHERE stu_id = '$id' AND ass_id = '$assessment_id'";
								$result1 = mysqli_query($connection, $sql1);
								if(mysqli_num_rows($result1) > 0){
									while($row2 = mysqli_fetch_assoc($result1)){
										mysqli_close($connection);
										echo "<script>document.location = 'submitassignment.php';</script>";
									}
								}
								//End checking for submission
							}
						}
						else{
							echo "Error. Assignment Not Found. Go back and <a href='assignment.php' style='color:blue'>Try again.</a>";
						}
					}
					else{
						echo "Sorry. Something went wrong. Go back and <a href='assignment.php' style='color:blue'>Try again.</a>";
					}
					mysqli_close($connection);


					if($cantcatchme == 1){
						require_once 'app_config.php';
						$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

						$sql = "SELECT ass_id, ass_name, ass_description, due_date, marks FROM assignment WHERE sub_id = '$assessment_subject' AND ass_id = '$assessment_id' AND upload_link = 'enable'";
						$result = mysqli_query($connection, $sql);

						if(mysqli_num_rows($result) > 0){
							while($row = mysqli_fetch_assoc($result)){
									$_SESSION['assignments_name'] = $row['ass_name'];
									$_SESSION['assignments_duedate'] = $row['due_date'];
									$_SESSION['assignments_marks'] = $row['marks'];
									echo "<form enctype='multipart/form-data' method='post' action='submitassignment.php' style='margin-left:3%;'>
												<br>
												<h5>Upload Assignment: ".htmlspecialchars($row['ass_name'])."</h5>
												<hr>
												<h6>Assignment Information</h6>
												<div class='w3-half' style='background-color:rgb(241, 248, 248)'>
													<b>DUE DATE</b><br>".htmlspecialchars($row['due_date'])."
												</div>
												<div class='w3-half' style='background-color:rgb(241, 248, 248)'>
													<b>MARKS</b><br>".htmlspecialchars($row['marks'])."
												</div>
												<br>
												<hr>
												<p>".htmlspecialchars($row['ass_description'])."</p>
												<br>
												<hr>
												<h5>Assignment Submission</h5>
												<br>
												<input type='hidden' name='MAX_FILE_SIZE' value='8000000' />
												<input type='file' name='data'>
												<br>
												<br>
												<i>Allowed file types ( Pdf, txt, jpeg, png, pjpeg )</i>
												<br>
												<br>
												Add Comments (Optional)<br>
												<textarea name='comment'></textarea>
												<br>
												<div class='w3-left'>
													<input type='submit' value='Submit' style='border-color:black; border-radius:5px'>
												</div>
										</form>
												<div class='w3-right'>
													<a href='assignment.php' style='margin-left:3%;'><button style='border-color:red; border-radius:5px'>Cancel</button></a>
												</div>";

							}
						}
						else{
							echo "Upload link not enabled for this assignment <a href='assignment.php' style='color:blue'>GO BACK</a>";
						}
						mysqli_close($connection);
					}
				}
				else{
					echo "<script>document.location = 'assignment.php'</script>";
				}
			?>
			</div>
	</div>
</div>
</body>
</html>