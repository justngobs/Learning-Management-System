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

	<title> View Quiz as Student</title>
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<link rel="stylesheet" type="text/css" href="tooltip.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="javascriptgoodies.js"></script>
	<script type="text/javascript" src="javascriptFormsValidations.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

</head>
<body style="background-color: rgb(249,247,243)">
	<br>
<div class="w3-sidebar w3-bar-block" style="width: 20%; background-color: rgb(74,74,74);">

	<?php
		if(isset($_SESSION['current_subject']) && isset($_SESSION['subject_name']) && isset($_SESSION['subject_code'])){

			echo "<a href='quizzes.php'><i class='fa fa-arrow-circle-left' style='font-size:48px;color:yellow'></i></a><a href='teacher.php'><i class='fa fa-home' style='font-size:48px;color:yellow;margin-left: 5%'></i></a>
			<br>";
			echo "<h4 style='color:white;margin-left:20px'><u>".htmlspecialchars(ucfirst($_SESSION['subject_name']))." ( ".htmlspecialchars($_SESSION['subject_code'])." </u>)</h4><br>";

			//Teacher navigation menu
			require 'teachermenu.php';
		}
		else{
			echo "<script> document.location = 'teacher.php'; </script>";
		}
	?>
</div>
<div style="margin-left: 25%">
	<div id = "content">
		<?php
		if(isset($_POST['quiz_selected']) && isset($_POST['quiz_title']) && isset($_POST['quiz_description']) && isset($_POST['number_of_questions'])){

			echo "
			<div class='w3-panel w3-pale-blue w3-border' style='width:95%'>
			  <h3>".htmlspecialchars($_POST['quiz_title'])."</h3>
			  <p>".htmlspecialchars($_POST['quiz_description'])."</p>
			</div>";

			require_once 'app_config.php';
			$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

			$selected_quiz = $_POST['quiz_selected'];
			$n = $_POST['number_of_questions'];

			$sql = "SELECT question_id, quiz_id, question_des FROM question WHERE quiz_id = '$selected_quiz' ORDER BY RAND() LIMIT $n";
			$result = mysqli_query($connection, $sql);
			$c = 1;
			if(mysqli_num_rows($result) > 0){
				echo "<form method='post' action='quizzes.php'>
						<input type='text' name='c_quiz' hidden='hidden' value='".$selected_quiz."'>";

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
				echo "<br><button style='background-color:green;color:white;border-radius:5px;' class='w3-right'>Go back</button></form><br><br>";
				mysqli_close($connection);
			}
		}

		?>
	</div>
</div>
</body>
</html>