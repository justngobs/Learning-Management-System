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
	<title>Create Quiz</title>
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<link rel="stylesheet" type="text/css" href="tooltip.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="javascriptgoodies.js"></script>
	<script type="text/javascript" src="javascriptFormsValidations.js"></script>
</head>
<body style="background-color: rgb(249,247,243)">
<?php
$cantcatchme = 0;
$errors = "";

if(isset($_POST['choice'])){
	$choice = $_POST['choice'];
	if(!preg_match('/^[a-zA-Z0-9 .,:()+-=]+$/', $choice)) {
      $errors .= "* Choice should contain letters, numbers and symbols :,.()+-=<br>";
    }
    else{
    	$cantcatchme += 1;
    }
}
if(is_numeric($_POST['q_id'])){
	$cantcatchme += 1;
	$id = $_POST['q_id'];
}
else{
	$errors .= "* Invalid question selected. <br>";
}

if(isset($_POST['choice']) && isset($_POST['choice_correct']) && $cantcatchme == 2){
	
	$is_correct = 1;

	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	$sql = "SELECT * FROM question_choice WHERE question_id = '$id' AND is_right_choice = '1'";
	$result = mysqli_query($connection, $sql);
	$checker = 0;
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
			$checker += 1;
		}
	}

	if($checker > 0){
		$n = 0;
		$stmt = $connection->prepare("UPDATE question_choice SET  is_right_choice = ? WHERE  question_id  = ?");
		$stmt->bind_param("ii",$n, $id);
		$stmt->execute();
		$stmt->close();
	}

	$stmt = $connection->prepare("INSERT INTO question_choice (question_id, is_right_choice, choice) VALUES (?,?,?)");
	$stmt->bind_param("iis",$id, $is_correct, $choice);
	$stmt->execute();
	$stmt->close();

	mysqli_close($connection);

	echo "<form method='post' action='quizzes.php' id='choi'><input type='text' hidden='hidden' name='c_question_choice' value='".$id."'><input type='text' hidden='hidden' name='c_quiz_selected' value='".$_POST['qz_id']."'><input type='text' hidden='hidden' name='c_question_des' value='".$_POST['question']."'><input type='submit' hidden='hidden'></form>";
	echo "<script> document.forms['choi'].submit();</script>";


}
if($cantcatchme == 2 && !isset($_POST['choice_correct'])){

	$is_correct = 0;

	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	$stmt = $connection->prepare("INSERT INTO question_choice (question_id, is_right_choice, choice) VALUES (?,?,?)");
	$stmt->bind_param("iis",$id, $is_correct, $choice);
	$stmt->execute();
	$stmt->close();

	mysqli_close($connection);

	echo "<form method='post' action='quizzes.php' id='choi'><input type='text' hidden='hidden' name='c_question_choice' value='".$id."'><input type='text' hidden='hidden' name='c_quiz_selected' value='".$_POST['qz_id']."'><input type='text' hidden='hidden' name='c_question_des' value='".$_POST['question']."'><input type='submit' hidden='hidden'></form>";
	echo "<script> document.forms['choi'].submit();</script>";
}
else{
	echo "<script>alert('".$errors."'); document.location = 'quizzes.php';</script>";
}
?>
</body>
</html>