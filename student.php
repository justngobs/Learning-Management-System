<?php
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
	<title>student account</title>
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="javascriptgoodies.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="popupstyle.css">
</head>
<body style="background-color: rgb(249,247,243)">
<div class="w3-sidebar w3-bar-block" style="width: 20%; background-color: rgb(74,74,74);">
	<?php

	require_once 'app_config.php';
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	$stu_id = $_SESSION['id'];
	$sql = "SELECT stu_name, stu_surname, stu_email, stu_grade FROM seed WHERE stu_id = '$stu_id'";
	$result = mysqli_query($connection, $sql);

	$name = "";
	$surname = "";
	$grade = 0;
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
			$name = $row['stu_name'];
			$surname = $row['stu_surname'];
			$email = $row['stu_email'];
			$grade = $row['stu_grade'];
		}
	}

	$_SESSION['student_name'] = $name;
	$_SESSION['student_surname'] = $surname;
	$_SESSION['student_email'] = $email;
	$_SESSION['stu_grade'] = $grade;

	echo "<a class='w3-bar-item w3-button' href='profile.php'><i class='fa fa-user' style='font-size:48px;color:yellow'></i><b style='color: silver'>".htmlspecialchars(strtoupper($name[0]))." ".htmlspecialchars(strtoupper($surname))."</b></a>";
	mysqli_close($connection);
	?>
	<br>
	<strong>
		<?php

		require_once 'app_config.php';
		$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

		//set a student number as session variable
		
		//select all subjects from the enrol table of the student session variable declared above
		$sql = "SELECT subjectss.sub_id, subjectss.sub_name , subjectss.sub_code, enrol.stu_id, enrol.sub_id FROM subjectss, enrol WHERE subjectss.sub_id= enrol.sub_id AND enrol.stu_id = '$stu_id' ORDER BY subjectss.sub_name";
		$result = mysqli_query($connection, $sql);
		
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				echo "<form method='get' action='subject.php'>
						<input type='hidden' name='subject_id' value='".$row['sub_id']."'>
						<button style='border:none; background-color: rgb(74,74,74); color:white;'> <i class='fa fa-circle' style='font-size:15px;color:lightblue'></i> ".htmlspecialchars(ucfirst($row['sub_name']))."</button>
					  </form><br>";

			}
			echo "<br><br><a href='logout.php' style='color:lightblue'> Logout</a>";
			
		}
		else{
				echo "Not enrolled yet<br><br><a href='logout.php'> Logout</a>";
		}
		mysqli_close($connection);
		?>
	</strong>
</div>
<div style="margin-left: 25%">
	<div id = "content">
		<?php echo "<div class='w3-right'><u><b><h4 style='color:darkgreen;margin-right:5px'>Welcome ".htmlspecialchars(ucfirst($_SESSION['student_name']))." ".htmlspecialchars(ucfirst($_SESSION['student_surname']))."</h4></b></u></div><div class='w3-lefy'><b>Student Home Page</b></div>";?>
		<br>
		<br>
		<button style="border-radius:7px;width: 180px" onclick="openClassTimetable();">Class Timetable</button>
		<button style="border-radius:7px;width: 180px" onclick="openExamTimetable();">Test/Exam Timetable</button>
		<button style="border-radius:7px;width: 180px" onclick="openAssessments();">Assessment Results</button>
		<br>
		<br>
		<h4 style="color: brown"><b>Announcements</b></h4>
		<div style="background-color: white; width: 95%">
			<?php

			require_once 'app_config.php';
			$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
			
			//Query to view all messages
			$sql = "SELECT gen_ann_id, gen_ann_name, gen_ann_description, gen_ann_date_posted FROM general_announcement ORDER BY gen_ann_id DESC LIMIT 15";
			$result = mysqli_query($connection, $sql);

			if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				echo "<div style='margin-left:3%'>
						    <h6><b>".htmlspecialchars($row['gen_ann_name'])."</b></h6>
						    <h6>Posted On: ".htmlspecialchars($row['gen_ann_date_posted'])."</h6>
						    <br>
						    <p>".htmlspecialchars($row['gen_ann_description'])."</p>
						    <br>  
						  </div>
						  <hr>";
				}
			}
			else{
				echo "No announcements yet.";
			}
			mysqli_close($connection);
			?>
		</div>
	</div>
</div>

<!-- Code to view class timetable -->
<div id="myClassTimetable" class="overlay">

	<!-- Button to close the overlay navigation -->
  	<a href="javascript:void(0)" class="closebtn" onclick="closeClassTimetable()">&times;</a>

  	<!-- Overlay content -->
	<div class="overlay-content">
		<?php 
		//code to deal with class timetable
		require_once 'app_config.php';
		$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

		$g = $_SESSION['stu_grade'];

		$sql = "SELECT time_id, time_title, time_grade, time_path, time_type FROM timetable WHERE time_grade = '$g' AND time_type = 'class'";

		$result = mysqli_query($connection, $sql);
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				echo "<a href='".$row['time_path']."'>".$row['time_title']."</a><br>";
			}
		}
		mysqli_close($connection);
		?>
	</div>
</div>


<!-- Code to view exam and test timetable -->
<div id="myExamTimetable" class="overlay">

	<!-- Button to close the overlay navigation -->
  	<a href="javascript:void(0)" class="closebtn" onclick="closeExamTimetable()">&times;</a>

  	<!-- Overlay content -->
	<div class="overlay-content">
		<?php 
		//code to deal with exams timetable
		require_once 'app_config.php';
		$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

		$g = $_SESSION['stu_grade'];

		$sql = "SELECT time_id, time_title, time_grade, time_path, time_type FROM timetable WHERE time_grade = '$g' AND time_type = 'exam'";

		$result = mysqli_query($connection, $sql);
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				echo "<a href='".$row['time_path']."'>".$row['time_title']."</a><br>";
			}
		}
		mysqli_close($connection);
		?>
	</div>
</div>

<!-- Code to view exam and test timetable -->
<div id="myAssessments" class="overlay">

	<!-- Button to close the overlay navigation -->
  	<a href="javascript:void(0)" class="closebtn" onclick="closeAssessments()">&times;</a>

  	<!-- Overlay content -->
	<div class="overlay-content">
		<div style="background-color:white">
			<h2 style="color: red"><b>My Assessment Results</b></h2>
			<br>
			<p style="color: black"> for specific details regarding your marks please consult your teacher.</p>
			<?php

			require_once 'app_config.php';
			$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

			$student = $_SESSION['id'];

			$sql = "SELECT subjectss.sub_id, subjectss.sub_name, assignment.ass_id, assignment.sub_id, assignment.ass_name, assignment.date_posted, assignment.due_date, assignment.marks, assignment_submission.ass_sub_id, assignment_submission.ass_id, assignment_submission.stu_id, assignment_submission.ass_sub_status, assignment_submission.ass_sub_grade, assignment_submission.ass_sub_date, assignment_submission.feedback FROM subjectss, assignment, assignment_submission WHERE subjectss.sub_id = assignment.sub_id AND assignment.ass_id = assignment_submission.ass_id AND assignment_submission.stu_id = '$student' ORDER BY ass_sub_id DESC";

			$result = mysqli_query($connection, $sql);

			if(mysqli_num_rows($result) > 0){
				echo "<center><table style='width:80%'>
				<thead style='background-color: yellow;'>
				<tr style='color:red'>
					<th> # </th>
					<th> Subject </th>
					<th> Date submitted </th>
					<th> Assessment Name </th>
					<th> Assessment Mark (%) </th>
					<th> Feedback </th>
				</tr>
				</thead>
				<tbody style='background-color:white;color:black'>";
				$counter = 0;
				while($row = mysqli_fetch_assoc($result)){
					$counter += 1;

					if($row['ass_sub_status'] == 1){
						echo "<tr>
								<td>".$counter."</td>
								<td>".$row['sub_name']."</td>
								<td>".$row['ass_sub_date']."</td>
								<td>".$row['ass_name']."</td>
								<td>".round((($row['ass_sub_grade'])/($row['marks']))*100)."</td>
								<td>".$row['feedback']."</td>
							</tr>";
					}
					if($row['ass_sub_status'] == 0){
						echo "<tr>
								<td>".$counter."</td>
								<td>".$row['sub_name']."</td>
								<td>".$row['ass_sub_date']."</td>
								<td>".$row['ass_name']."</td>
								<td>Pending (Needs Marking)</td>
								<td>".$row['feedback']."</td>
							</tr>";		
					}
				}
				echo "</tbody></table></center>";
			}
			else{
				echo "<p style='color:black'>No assessments have been released yet.</p>";
			}
			mysqli_close($connection);
			?>
			<br>
			<br>
			<br>
		</div>
	</div>
</div>

<script type="text/javascript">

	function openClassTimetable() {
	  document.getElementById("myClassTimetable").style.width = "100%";
	}

	/* Close when someone clicks on the "x" symbol inside the overlay */
	function closeClassTimetable() {
	  document.getElementById("myClassTimetable").style.width = "0%";
	} 

	function openExamTimetable() {
	  document.getElementById("myExamTimetable").style.width = "100%";
	}

	/* Close when someone clicks on the "x" symbol inside the overlay */
	function closeExamTimetable() {
	  document.getElementById("myExamTimetable").style.width = "0%";
	} 

	function openAssessments() {
	  document.getElementById("myAssessments").style.width = "100%";
	}

	/* Close when someone clicks on the "x" symbol inside the overlay */
	function closeAssessments() {
	  document.getElementById("myAssessments").style.width = "0%";
	} 
</script>
</body>
</html>