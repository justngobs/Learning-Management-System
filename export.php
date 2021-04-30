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
	<title>Import Quiz</title>
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<link rel="stylesheet" type="text/css" href="tooltip.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="javascriptgoodies.js"></script>
	<script type="text/javascript" src="javascriptFormsValidations.js"></script>
</head>
<body style="background-color: rgb(249,247,243)">
	<br>
<div class="w3-sidebar w3-bar-block" style="width: 20%; background-color: rgb(74,74,74);">

	<?php
		if(isset($_SESSION['current_subject']) && isset($_SESSION['subject_name']) && isset($_SESSION['subject_code'])){

			echo "<a href='quiz.php'><i class='fa fa-arrow-circle-left' style='font-size:48px;color:yellow'></i></a><a href='teacher.php'><i class='fa fa-home' style='font-size:48px;color:yellow;margin-left: 5%'></i></a>
			<br>";
			echo "<h4 style='color:white;margin-left:20px'><u>".htmlspecialchars(ucfirst($_SESSION['subject_name']))." ( ".htmlspecialchars($_SESSION['subject_code'])." </u>)</h4><br>";

			//Teachers navigation menu
			require 'teachermenu.php';
		}
		else{
			echo "<script> document.location = 'teacher.php'; </script>";
		}
	?>
</div>
<div style="margin-left: 25%">
	<div id = "content">
		<hr>
		<a href="quiz.php" style="color: blue"> Manage Quizzes</a>| <a href="manageassignment.php" style="color: blue"> Manage Assignments</a> | Quiz Questions Review
		<br>
		<form action="quizzes.php" method="post">
			<b>Available Quizzes :</b>
			<select style="border-radius: 7px; border-color: lightblue;" type="text" name="c_quiz" required="required">
				<option value="default" selected="selected" disabled="disabled">Select quiz</option>
				<?php
				require_once 'app_config.php';
				$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
				$c_subject = $_SESSION['current_subject'];
				if(is_numeric($c_subject))
				{
					$sql = "SELECT quiz_id, sub_id, quiz_title FROM quiz WHERE sub_id = '$c_subject'";
					$result = mysqli_query($connection, $sql);
					if(mysqli_num_rows($result) > 0){
						while($row = mysqli_fetch_assoc($result)){
							echo "<option value='".$row['quiz_id']."'>".$row['quiz_title']."</option>";
						}
					}
				}
				mysqli_close($connection);
				?>
			</select>
			<input type="submit" name="submit" value="View Questions">
		</form>		
		<hr>
		<br>

		<?php

		if(isset($_POST['c_question_export']) && isset($_POST['selected_quiz']) && isset($_POST['c_question_des'])){
			$quiz = $_POST['selected_quiz'];
			$qid = $_POST['c_question_export'];
			$qd = $_POST['c_question_des'];

			echo "<form action='export.php' method='post'>
				<span style='color:red'>".$_POST['c_question_des']."</span><br><br>
				<b>Export to :</b>
				<input type='hidden' name='theQuestion' value='".$qid."'>
				<input type='hidden' name='theQuestionDescription' value='".$qd."'>
				<select style='border-radius: 7px; border-color: lightblue;' type='text' name='cc_quiz' required='required'>
					<option value='default' selected='selected' disabled='disabled'>Select quiz</option>";
					
					require_once 'app_config.php';
					$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
					$c_subject = $_SESSION['current_subject'];
					if(is_numeric($c_subject))
					{
						$sql = "SELECT quiz_id, sub_id, quiz_title FROM quiz WHERE sub_id = '$c_subject'";
						$result = mysqli_query($connection, $sql);
						if(mysqli_num_rows($result) > 0){
							while($row = mysqli_fetch_assoc($result)){
								if($row['quiz_id'] == $quiz){
									//Do not do anything
								}
								else{
									echo "<option value='".$row['quiz_id']."'>".$row['quiz_title']."</option>";
								}
							}
						}
					}
					mysqli_close($connection);
					
				echo "</select>
				<input type='submit' name='submit' value='Export Question'>
			</form>";
		}
		?>

		<?php
		if(isset($_POST['cc_quiz']) && isset($_POST['theQuestion']) && isset($_POST['theQuestionDescription'])){
			//Export the quiz into the other quiz

			$quiz = $_POST['cc_quiz'];
			$qi = $_POST['theQuestion'];
			$qd = $_POST['theQuestionDescription'];

			require_once 'app_config.php';
			$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

			$innerSQL = "INSERT INTO question (quiz_id, question_des) VALUES('$quiz','$qd')";
			mysqli_query($connection, $innerSQL);
			$last_id = mysqli_insert_id($connection);

			$sql2 = "SELECT choice_id, question_id, is_right_choice, choice FROM question_choice WHERE question_id = '$qi'";
			$res = mysqli_query($connection, $sql2);
			if(mysqli_num_rows($res) > 0){
				while($row1 = mysqli_fetch_assoc($res)){
					$is_right = $row1['is_right_choice'];
					$choi = $row1['choice'];
					$insertChoice = "INSERT INTO question_choice (question_id, is_right_choice, choice) VALUES ('$last_id','$is_right','$choi')";
					mysqli_query($connection, $insertChoice);
				}
			}

			mysqli_close($connection);
				
			echo "<form method='post' action='quizzes.php' id='reload'><input type='text' hidden='hidden' name='c_quiz' value='".$quiz."'><input type='submit' hidden='hidden'></form>";
			echo "<script> document.forms['reload'].submit();</script>";
		}
		?>

	</div>
</div>
</body>
</html>