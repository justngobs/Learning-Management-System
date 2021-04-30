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
	<title>Grade assignments</title>
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<link rel="stylesheet" type="text/css" href="tooltip.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="javascriptgoodies.js"></script>
	<script type="text/javascript" src="javascriptFormsValidations.js"></script>
</head>
<body style="background-color: rgb(249,247,243)">
<div class="w3-sidebar w3-bar-block" style="width: 20%; background-color: rgb(74,74,74);">
	<a href='manageassignment.php'><i class="fa fa-arrow-circle-left" style="font-size:48px;color:yellow"></i></a><a href="teacher.php"><i class="fa fa-home" style="font-size:48px;color:yellow;margin-left: 5%"></i></a>
	<br>
	<?php

	if(isset($_SESSION['subject_name']) && isset($_SESSION['subject_code']) && isset($_SESSION['current_subject'])){

		echo "<h4 style='color:white;margin-left:20px'><u>".htmlspecialchars(ucfirst($_SESSION['subject_name']))." ( ".htmlspecialchars($_SESSION['subject_code'])." </u>)</h4><br>";
		//Teacher navigation menu
		require 'teachermenu.php';
	}
	else{
		echo "<script> document.location = 'teacher.php';</script>";
	}
	?>
</div>
<div style="margin-left: 25%">
	<div id = "content">
		<hr>
		<a href="manageassignment.php" style="color: blue"> Manage Assignments</a> | Submitted Assignments Review
		<br>
		<form action="gradeassignment.php" method="post">
			<b>Submitted Assignments :</b>
			<select style="border-radius: 7px; border-color: lightblue;" type="text" name="c_assignment" required="required">
				<option value="default" selected="selected" disabled="disabled">Select assignment</option>
				<?php

				require_once 'app_config.php';
				$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

				$c_subject = $_SESSION['current_subject'];
				if(is_numeric($c_subject))
				{
					$sql = "SELECT ass_id, sub_id, ass_name, upload_link FROM assignment WHERE sub_id = '$c_subject' AND upload_link = 'enable' ORDER BY ass_id DESC";
					$result = mysqli_query($connection, $sql);
					if(mysqli_num_rows($result) > 0){
						while($row = mysqli_fetch_assoc($result)){
							echo "<option value='".$row['ass_id']."'>".$row['ass_name']."</option>";
						}
					}
				}
				mysqli_close($connection);
				?>
			</select>
			<input type="submit" name="submit" value="View/Mark">
		</form>		
		<hr>

		<?php
		if(isset($_POST['grade'])){

		require_once 'app_config.php';
		$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

		$id = $_POST['assignment_id'];
		$sql = "SELECT ass_id, marks FROM assignment WHERE ass_id = '$id'";
		$result = mysqli_query($connection, $sql);
		$total_mark = 0;
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				$total_mark = $row['marks'];
				$_SESSION['total_mark'] = $total_mark;
			}
		}
		mysqli_close($connection);

		echo "
			  <div id='id01' class='w3-modal'>
			    <div class='w3-modal-content w3-animate-right w3-card-4'>
			      <header class='w3-container' style='background-color:rgb(74,74,74)'> 
			      	<div class='w3-left'>
			        	<b style='color:white'>Grading Assignment for : </b><b style='color:yellow'>".ucfirst($_POST['student_name'])." ".ucfirst($_POST['student_surname'])."</b>
			        </div>
			        <div class='w3-right'>
			        	<a href='".$_POST['assignment']."' target='blank' style='color:lightblue'><b>View Assignment</b></a>
			        </div>
			      </header>
			      <div class='w3-container'>
			        <div class='w3-container w3-cell'>
			        	<br>
			        	<form method='post' action='grade_assignment.php'>
			        		Grade:
			        		<input type='number' min='0' max='".$total_mark."' name='mark' value='".$_POST['student_grade']."' style='width:25%'> / ".$total_mark."<br><br>
			        		Feedback(<i>optional</i>)<br>
			        		<textarea name='feedback'>".$_POST['teacher_feedback']."</textarea><br>
			        		<input type='text' name='totalM' value='".$total_mark."' hidden='hidden'>
			        		<input type='text' name='update_mark_id' value='".$_POST['assignment_sub_id']."' hidden='hidden'>
			        		<input type='text' name='assi_id' value='".$_POST['assignment_id']."' hidden='hidden'>
			        		<input type='submit' value='Update Grade' name='update_grade' style='background-color:orange;border-radius:5px'>
			        		<br>
			        		<br>
			        	</form>
			        	<br>
			        </div>
			        <div class='w3-container w3-cell'>
			        	Submission History
			        </div>
			      </div>
			      <footer class='w3-container' style='background-color:rgb(74,74,74)'>
			      <br>
			        <div class='w3-right'>
			        	<form method='post' action='gradeassignment.php'>
			        		<input type='text' hidden='hidden' value='".$_POST['assignment_id']."' name='c_assignment'>
			        		<button style='background-color:red;border-radius:5px'>Cancel</button>
			        	</form>	
			        </div>
			      <br>
			      <br>
			      </footer>
			    </div>
			  </div>
			  <script>document.getElementById('id01').style.display='block';</script>
			";

		}
		?>


		<?php
		$cantcatchme = 0;
		if(isset($_POST['c_assignment']) && !empty($_POST['c_assignment'])){
			$id = $_POST['c_assignment'];
			if(is_numeric($id)){
				$cantcatchme += 1;
			}
		}

		if($cantcatchme == 1){
			
			require_once 'app_config.php';
			$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
			
			$sql = "SELECT assignment_submission.ass_sub_id, assignment_submission.ass_id, assignment_submission.ass_sub_status, assignment_submission.ass_sub_grade, assignment_submission.ass_sub_date, assignment_submission.ass_sub_path, assignment_submission.stu_id, assignment_submission.comment, assignment_submission.feedback, seed.stu_id, seed.stu_name, seed.stu_surname FROM assignment_submission, seed WHERE assignment_submission.ass_id = '$id' AND seed.stu_id = assignment_submission.stu_id";

			$result = mysqli_query($connection, $sql);

			if(mysqli_num_rows($result) > 0){
				echo "<table class='w3-table w3-bordered w3-hoverable' style='width:98%'>
						<thead style='background-color: rgb(74,74,74);'>
						<tr>
							<th style='color:rgb(235,238,196)'>#</th>
							<th style='color:rgb(235,238,196)'>Name</th>
							<th style='color:rgb(235,238,196)'>Mark</th>
							<th style='color:rgb(235,238,196)'>Comment</th>
							<th style='color:rgb(235,238,196)'>Feedback</th>
							<th style='color:rgb(235,238,196)'></th>
						</tr>
						</thead>
						<tbody>";

				while($row = mysqli_fetch_assoc($result)){
					if($row['ass_sub_status'] == 0){

						echo "<tr>
								<td>".$row['stu_id']."</td>
								<td>".ucfirst($row['stu_name'])." ".ucfirst($row['stu_surname'])."</td>
								<td> - </td>
								<td>".$row['comment']."</td>
								<td>".$row['feedback']."</td>
								<td>
									<form method='post' action='gradeassignment.php'>
										<input type='hidden' name='assignment_id' value='".$row['ass_id']."'>
										<input type='hidden' name='assignment_sub_id' value='".$row['ass_sub_id']."'>
										<input type='hidden' name='student_id' value='".$row['stu_id']."'>
										<input type='hidden' name='student_name' value='".$row['stu_name']."'>
										<input type='hidden' name='student_surname' value='".$row['stu_surname']."'>
										<input type='hidden' name='student_grade' value='".$row['ass_sub_grade']."'>
										<input type='hidden' name='student_comment' value='".$row['comment']."'>
										<input type='hidden' name='teacher_feedback' value='".$row['feedback']."'>
										<input type='text' name='assignment' value='".$row['ass_sub_path']."' hidden='hidden'>
										<input type='submit' value='View' name='grade'>
									</form>
								</td>
							</tr>";

					}
					else{

						echo "<tr>
								<td>".$row['stu_id']."</td>
								<td>".ucfirst($row['stu_name'])." ".ucfirst($row['stu_surname'])."</td>
								<td>".$row['ass_sub_grade']."</td>
								<td>".$row['comment']."</td>
								<td>".$row['feedback']."</td>
								<td>
									<form method='post' action='gradeassignment.php'>
										<input type='hidden' name='assignment_id' value='".$row['ass_id']."'>
										<input type='hidden' name='assignment_sub_id' value='".$row['ass_sub_id']."'>
										<input type='hidden' name='student_id' value='".$row['stu_id']."'>
										<input type='hidden' name='student_name' value='".$row['stu_name']."'>
										<input type='hidden' name='student_surname' value='".$row['stu_surname']."'>
										<input type='hidden' name='student_grade' value='".$row['ass_sub_grade']."'>
										<input type='hidden' name='student_comment' value='".$row['comment']."'>
										<input type='hidden' name='teacher_feedback' value='".$row['feedback']."'>
										<input type='text' name='assignment' value='".$row['ass_sub_path']."' hidden='hidden'>
										<input type='submit' value='View' name='grade'>
									</form>
								</td>
							</tr>";
					}
				}

				echo "</tbody></table>";

				mysqli_close($connection);
			}
			else{
				echo "No Assignments submitted yet";
			}
		}
		else{
			echo "Select an assignment";
		}
		
		?>
	</div>
</div>

</body>
</html>