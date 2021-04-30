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
	<title>Submit Quiz</title>
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="javascriptgoodies.js"></script>
</head>
<body style="background-color: rgb(249,247,243)">
<?php
$cantcatchme = 0;

if(isset($_SESSION['total_questions']) && isset($_SESSION['selected_quiz'])){
	$n = $_SESSION['total_questions'];
	$selected_quiz = $_SESSION['selected_quiz'];
	$student = $_SESSION['id'];
	$today = date('Ymd');
}
else{
	echo "<script> document.location = 'assignment.php';</script>";
}

require_once "app_config.php";
$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

if(is_numeric($n) && is_numeric($selected_quiz)){
	$cantcatchme += 1;

	$sql = "SELECT * FROM user_question_answer WHERE user_id ='$student' AND quiz_id = '$selected_quiz'";
	$result = mysqli_query($connection, $sql);
	if(mysqli_num_rows($result) > 0){
		$cantcatchme += 1;
	}
}

if($cantcatchme == 1){
	$sql = "INSERT INTO user_question_answer (user_id, question_id, choice_id, quiz_id, date_answered) VALUES";

	for($i = 1; $i <= $n; $i++){
		
		$q = "question".$i;
		$question = $_POST[$q];
		$a = "answer".$i;
		$answer = $_POST[$a];

		if($i == $n){
			$sql .= "('".$student."',";
			$sql .= "'".$question."',";
			$sql .= "'".$answer."',";
			$sql .= "'".$selected_quiz."',";
			$sql .= "'".$today."')";
		}
		else{
			$sql .= "('".$student."',";
			$sql .= "'".$question."',";
			$sql .= "'".$answer."',";
			$sql .= "'".$selected_quiz."',";
			$sql .= "'".$today."'),";
		}

	}

	mysqli_query($connection, $sql);

	//
	$sql2 = "SELECT question_choice.choice_id, question_choice.question_id, question_choice.is_right_choice, user_question_answer.user_q_answer_id, user_question_answer.user_id, user_question_answer.question_id, user_question_answer.choice_id, user_question_answer.quiz_id, user_question_answer.date_answered FROM question_choice, user_question_answer WHERE user_question_answer.quiz_id = '$selected_quiz' AND user_question_answer.user_id = '$student' AND question_choice.choice_id = user_question_answer.choice_id";
    $correct2 = 0;
    $result2 = mysqli_query($connection, $sql2);
	if(mysqli_num_rows($result2) > 0){
		while($row = mysqli_fetch_assoc($result2)){
			
			if($row['is_right_choice'] == 1){
				$correct2 += 1;
			}
			if($row['is_right_choice'] == 0){
				
			}
		}
	}

	$sql3 = "INSERT INTO quiz_mark (stu_id, quiz_id, stu_score) VALUES ('$student','$selected_quiz','$correct2')";
	mysqli_query($connection, $sql3);

	mysqli_close($connection);

	header("Location: submit_quiz.php");
}

if($cantcatchme == 2){

    $sql = "SELECT stu_score, stu_id FROM quiz_mark WHERE stu_id = '$student' AND quiz_id = '$selected_quiz'";
    $correct = 0;
    $result = mysqli_query($connection, $sql);
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
			$correct = $row['stu_score'];
		}
	}

	mysqli_close($connection);

    

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

	if(round(($correct/$n)*100) > 50){
		echo "<div style='margin-left: 25%'>
			<div id = 'announcements'>
			<br>
				<h3>Quiz Submitted: ".ucfirst($_SESSION['quiz_name'])."</h3>
				<br>
				<div style='background-color:white;width:90%'>
					<b>Student: </b>".ucfirst($_SESSION['student_name'])." ".ucfirst($_SESSION['student_surname'])."<br>
					<b>Quiz: </b>".ucfirst($_SESSION['quiz_name'])."<br>
					<b>Subject: </b>".$_SESSION['subject_code']." (".$_SESSION['subject_name'].") ".$_SESSION['subject_code']."<br>
					<b>Started: </b>".$_SESSION['time_started_quiz']."<br>
					<b>Submitted: </b>".date('F j, Y \a\t g:ia')."<br>
					<br>
					<br>
					
					<br>
					<div class='w3-panel w3-green'>
					  <h3>Congratulations. You passed!</h3>
					  <p>You scored ".$correct."/".$n.".</p>
					</div>
				</div>
			</div>
		</div>";
	}
	else{
		echo "<div style='margin-left: 25%'>
			<div id = 'announcements'>
			<br>
				<h3>Quiz Submitted: ".ucfirst($_SESSION['quiz_name'])."</h3>
				<br>
				<div style='background-color:white;width:90%'>
					<b>Student: </b>".ucfirst($_SESSION['student_name'])." ".ucfirst($_SESSION['student_surname'])."<br>
					<b>Quiz: </b>".ucfirst($_SESSION['quiz_name'])."<br>
					<b>Subject: </b>".$_SESSION['subject_code']." (".$_SESSION['subject_name'].") ".$_SESSION['subject_code']."<br>
					<b>Started: </b>".$_SESSION['time_started_quiz']."<br>
					<b>Submitted: </b>".date('F j, Y \a\t g:ia')."<br>
					<br>
					<br>
					
					<br>
					<div class='w3-panel w3-orange'>
					  <h3>You scored below 50%. You need to put in more effort!</h3>
					  <p>You scored ".$correct."/".$n.".</p>
					</div>
				</div>
			</div>
		</div>";
	}


	
}

?>
</body>
</html>