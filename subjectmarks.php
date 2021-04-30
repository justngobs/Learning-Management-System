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
	<title>Assessment results</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="javascriptgoodies.js"></script>
</head>
<body style="background-color: rgb(249,247,243)">
<div class="w3-sidebar w3-bar-block" style="width: 20%; background-color: rgb(74,74,74);">
	<a href='assignment.php'><i class="fa fa-arrow-circle-left" style="font-size:48px;color:yellow"></i></a><a href="student.php"><i class="fa fa-home" style="font-size:48px;color:yellow;margin-left: 5%"></i></a>
	<br>
	<?php
	if(isset($_SESSION['subject_name']) && isset($_SESSION['subject_code'])){
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
	<h4><b>Assessment Results</b></h4>
	<br>
	<?php

	require_once 'app_config.php';
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	$student = $_SESSION['id'];
	$subject = $_SESSION['current_subject'];

	$sql = "SELECT assignment.ass_id, assignment.sub_id, assignment.ass_name, assignment.date_posted, assignment.due_date, assignment.marks, assignment_submission.ass_sub_id, assignment_submission.ass_id, assignment_submission.stu_id, assignment_submission.ass_sub_status, assignment_submission.ass_sub_grade, assignment_submission.ass_sub_date, assignment_submission.feedback FROM assignment, assignment_submission WHERE assignment.sub_id = '$subject' AND assignment.ass_id = assignment_submission.ass_id AND assignment_submission.stu_id = '$student' ORDER BY ass_sub_id DESC";

	$result = mysqli_query($connection, $sql);

	if(mysqli_num_rows($result) > 0){
		echo "<table class='w3-table w3-striped w3-bordered w3-hoverable' style='width:98%'>
		<thead style='background-color: rgb(74,74,74);'>
		<tr>
			<th style='color:rgb(235,238,196)'>#</th>
			<th style='color:rgb(235,238,196)'>Due Date</th>
			<th style='color:rgb(235,238,196)'>Date submitted</th>
			<th style='color:rgb(235,238,196)'>Assessment Name</th>
			<th style='color:rgb(235,238,196)'>Assessment Mark (%)</th>
			<th style='color:rgb(235,238,196)'>Feedback</th>
		</tr>
		</thead>
		<tbody>";
		$counter = 0;
		while($row = mysqli_fetch_assoc($result)){
			$counter += 1;

			if($row['ass_sub_status'] == 1){
				echo "<tr>
						<td>".$counter."</td>
						<td>".$row['due_date']."</td>
						<td>".$row['ass_sub_date']."</td>
						<td>".$row['ass_name']."</td>
						<td>".round((($row['ass_sub_grade'])/($row['marks']))*100)."</td>
						<td>".$row['feedback']."</td>
					 </tr>";
			}
			if($row['ass_sub_status'] == 0){
				echo "<tr>
						<td>".$counter."</td>
						<td>".$row['due_date']."</td>
						<td>".$row['ass_sub_date']."</td>
						<td>".$row['ass_name']."</td>
						<td>Pending (Needs Marking)</td>
						<td>".$row['feedback']."</td>
					 </tr>";		
			}
		}
		echo "</tbody></table>";
	}
	else{
		echo "No assessments have been released yet.";
	}

	mysqli_close($connection);
	?>
</div>
</body>
</html>