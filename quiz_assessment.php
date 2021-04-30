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
	<title>Quiz Assessment</title>
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="javascriptgoodies.js"></script>
</head>
<body style="background-color: rgb(249,247,243)">

	<?php

	if(isset($_SESSION['subject_name']) && isset($_SESSION['subject_code']) && isset($_SESSION['current_subject'])){
		echo "<div class='w3-sidebar w3-bar-block' style='width: 20%; background-color: rgb(74,74,74);'>
			<a href='assignment.php'><i class='fa fa-arrow-circle-left' style='font-size:48px;color:yellow'></i></a><a href='student.php'><i class='fa fa-home' style='font-size:48px;color:yellow;margin-left: 5%'></i></a>
			<br>";
		echo "<h4 style='color:white;margin-left:20px'><u>".htmlspecialchars($_SESSION['subject_name'])." ( ".htmlspecialchars($_SESSION['subject_code'])." </u>)</h4><br>";

		//Student navigation menu
		require 'studentmenu.php';
	}
	else{
		echo "<script>document.location = 'student.php';</script>";
	}
	?>
</div>
<div style="margin-left: 25%">
	<div id = "Quiz questions">
		<?php
			if(isset($_SESSION['current_subject']) && isset($_POST['selected_quiz']) && isset($_POST['selected_quiz_description']) && is_numeric($_POST['selected_quiz'])){

				//Check if this student has submitted this qui yet or not
				$student_id = $_SESSION['id'];
				$selected_quiz = $_POST['selected_quiz'];
				$_SESSION['selected_quiz'] = $selected_quiz;
				$n = $_POST['selected_quiz_n'];
				$_SESSION['total_questions'] = $n;

				require_once 'app_config.php';
				$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

				$sql = "SELECT * FROM user_question_answer WHERE user_id ='$student_id' AND quiz_id = '$selected_quiz'";
				$result = mysqli_query($connection, $sql);
				if(mysqli_num_rows($result) > 0){

					mysqli_close($connection);
					echo "<script>document.location = 'submit_quiz.php';</script>";
				}
				echo "
				<div class='w3-panel w3-pale-blue w3-border' style='width:95%'>
				  <h3>".htmlspecialchars($_POST['selected_quiz_title'])."</h3>
				  <p>".htmlspecialchars($_POST['selected_quiz_description'])."</p>
				</div>";

				mysqli_close($connection);
				
				$_SESSION['quiz_name'] = $_POST['selected_quiz_title'];

					if(isset($_POST['selected_quiz']) && is_numeric($_POST['selected_quiz'])){
						require_once 'app_config.php';
						$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
						$_SESSION['selected_quiz'] = $selected_quiz;
						$n = $_POST['selected_quiz_n'];
						$_SESSION['total_questions'] = $n;

						$sql = "SELECT question_id, quiz_id, question_des FROM question WHERE quiz_id = '$selected_quiz' ORDER BY RAND() LIMIT $n";
						$result = mysqli_query($connection, $sql);
						$c = 1;
						if(mysqli_num_rows($result) > 0){
							echo "<form method='post' action='submit_quiz'>";

							while($row = mysqli_fetch_assoc($result)){
								$selected_question = $row['question_id'];
								echo "<input type='text' name='question".$c."' value='".$selected_question."' hidden='hidden'>";
								echo "<br><b>".$c." .".htmlspecialchars($row['question_des'])."</b><br>";

								$sql2 = "SELECT question_id, choice_id, choice FROM question_choice WHERE question_id = '$selected_question'";
								$result2 = mysqli_query($connection, $sql2);
								if(mysqli_num_rows($result2) > 0){
									while($row2 = mysqli_fetch_assoc($result2)){
										echo "<input style='margin-left:2%' type='radio' name='answer".$c."' value='".$row2['choice_id']."'> ".$row2['choice']."<br>";
									}
								}
								$c += 1;
							}
							$_SESSION['time_started_quiz'] = date('F j, Y \a\t g:ia');
							echo "<br><button style='background-color:green;color:white;border-radius:5px;' class='w3-right'>submit quiz</button></form><br><br>";
						}


						mysqli_close($connection);
					}
				else{
					echo "<script>document.location = 'assignment.php';</script>";
				}
			}
			else{
				echo "<script>document.location = 'assignment.php';</script>";
			}
		?>
	</div>
</div>
</body>
</html>