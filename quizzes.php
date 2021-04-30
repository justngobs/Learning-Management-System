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
	<br>
<div class="w3-sidebar w3-bar-block" style="width: 20%; background-color: rgb(74,74,74);">

	<?php
		if(isset($_SESSION['current_subject']) && isset($_SESSION['subject_name']) && isset($_SESSION['subject_code'])){

			echo "<a href='quiz.php'><i class='fa fa-arrow-circle-left' style='font-size:48px;color:yellow'></i></a><a href='teacher.php'><i class='fa fa-home' style='font-size:48px;color:yellow;margin-left: 5%'></i></a>
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

			<!-- documentation -->
			<div class="tooltip" style="margin-left: 3%"><i class="fa fa-question-circle" style="color: blue;font-size: 30px" aria-hidden="true"></i>
			  <span class="tooltiptext"> Select a quiz to add questions to the quiz. You can also export questions to another quiz or import questions into the current selected quiz. NOTE: Make sure that each question has more than one choice and that one of the choices is marked as the correct choice. </span>
			</div>
			<!-- End documentation -->

		</form>		
		<hr>


		<?php
		//Query to delete questions
			if(isset($_POST['c_question_delete'])){
				if(is_numeric($_POST['c_question_delete']) && is_numeric($_POST['selected_quiz'])){
			 		
					require_once 'app_config.php';
					$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

			 		$id   = $_POST['c_question_delete'];    
			 		//Delete an announcement from the teacher table
			 		$query1  = "DELETE FROM question WHERE question_id='$id'";    
			 		$result1 = $connection->query($query1);    
			 		if (!$result1){ 
			 			echo "DELETE failed: $query1<br>" . $connection->error . "<br><br>";  
			 		}
			 		mysqli_close($connection);
			 		echo "<form method='post' action='quizzes.php' id='del'>
			 				<input type='text' hidden='hidden' name='c_quiz' value='".$_POST['selected_quiz']."'>
			 				<input type='submit' hidden='hidden'>
			 			  </form>";
					echo "<script> document.forms['del'].submit();</script>";
			 	}
			}
		?>


		<?php
		if(isset($_POST['c_quiz'])){

		require_once 'app_config.php';
		$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

		$selected_quiz = $_POST['c_quiz'];
		$n = 0;
		$title = "";
		$description = "";


		$c = 0;
		$sql = "SELECT quiz.quiz_title, quiz.quiz_description, quiz.n_of_questions, question.question_id, question.quiz_id, question.question_des FROM quiz, question WHERE question.quiz_id = '$selected_quiz' AND quiz.quiz_id = '$selected_quiz'";
		$result = mysqli_query($connection, $sql);
		if(mysqli_num_rows($result) > 0){
			echo "<table class='w3-table w3-striped w3-bordered w3-hoverable' style='width:98%'>
				<thead style='background-color: rgb(74,74,74);'>
				<tr>
					<th style='color:rgb(235,238,196)'>#</th>
					<th style='color:rgb(235,238,196)'>Question</th>
					<th style='color:rgb(235,238,196)'></th>
					<th style='color:rgb(235,238,196)'></th>
					<th style='color:rgb(235,238,196)'></th>
					<th style='color:rgb(235,238,196)'></th>
				</tr>
				</thead>
				<tbody>";
			while($row = mysqli_fetch_assoc($result)){
				$c += 1;
				$n = $row['n_of_questions'];
				$title = $row['quiz_title'];
				$description = $row['quiz_description'];

				echo "<tr>
						<td>".$c."</td>
						<td>".$row['question_des']."</td>
						<td>
							<form method='post' action='quizzes.php'>
								<input type='text' name='c_question_choice' hidden='hidden' value='".$row['question_id']."'>
								<input type='text' name='c_quiz_selected' hidden='hidden' value='".$selected_quiz."'>
								<input type='text' name='c_question_des' hidden='hidden' value='".$row['question_des']."'>
								<input type='submit' value='+ choice'>
							</form>
						</td>
						<td>
							<form method='post' action='quizzes.php'>
								<input type='text' name='c_question_edit' hidden='hidden' value='".$row['question_id']."'>
								<input type='text' name='c_quiz_selected' hidden='hidden' value='".$selected_quiz."'>
								<input type='text' name='c_question_des' hidden='hidden' value='".$row['question_des']."'>
								<input type='submit' value='Edit'>
							</form>
						</td>
						<td>
							<form method='post' action='quizzes.php'>
								<input type='text' hidden='hidden' value='".$row['question_id']."' name='c_question_delete'>
								<input type='text' name='selected_quiz' hidden='hidden' value='".$selected_quiz."'>
								<input type='submit' value='Delete'>
							</form>
						</td>
						<td>
							<form method='post' action='export.php'>
								<input type='text' hidden='hidden' value='".$row['question_id']."' name='c_question_export'>
								<input type='text' name='selected_quiz' hidden='hidden' value='".$selected_quiz."'>
								<input type='text' name='c_question_des' hidden='hidden' value='".$row['question_des']."'>
								<input type='submit' value='Export'>
							</form>
						</td>
					  </tr>";
			}
			echo "</tbody></table>";
			echo "<br>
					<div class='w3-left'>
						<form method='post' action='viewQuizAsStudent.php'>
							<input type='text' name='quiz_selected' value='".$selected_quiz."' hidden='hidden'>
							<input type='text' name='number_of_questions' value='".$n."' hidden='hidden'>
							<input type='text' name='quiz_title' value='".$title."' hidden='hidden'>
							<input type='text' name='quiz_description' value='".$description."' hidden='hidden'>
							<button style='width:90%'>View Quiz as student</button>
						</form>
					</div>
					<div class='w3-right'>
						<form method='post' action='performanceReport.php'>
							<input type='text' name='quiz_selected' value='".$selected_quiz."' hidden='hidden'>
							<button style='width:90%'>View Performance Report</button>
						</form>
					</div>";
		}
		else{
			echo "<span style='color:red'>No questions yet. Add a question below</span>.<br>";
		}
		mysqli_close($connection);
		echo "<br><br><br>
		<form method='post' action='quizzes.php'>
			Question:<br>
			<input type='text' name='current_quiz' hidden='hidden' value='".$selected_quiz."'>
			<textarea name='question'></textarea><br>
			<input type='submit' value='Add Question' style='background-color:black;color:white;border-radius:5px'>
		</form> 
		<form method='post' action='import.php'>
			<input type='text' name='current_quiz_import' hidden='hidden' value='".$selected_quiz."'>
			<input type='submit' value='Import Question' style='background-color:black;color:white;border-radius:5px'>
		</form> ";
		}
		?>

		<?php
		if(isset($_POST['current_quiz']) && isset($_POST['question'])){

			require_once 'phpgoodies.php';

			$errors = "";
			$verifyer = 0;
			$cquiz = $_POST['current_quiz'];
			$question = test_input($_POST['question']);
			//Verify the question of the thing
			if(!preg_match('/^[a-zA-Z0-9 .,:()?]+$/', $question)) {
			  $errors .= "* Question should contain letters, numbers and symbols :,.()?<br>";
			}
			else{
				$verifyer += 1;
			}

			if(is_numeric($cquiz) && $cquiz >= 1){
				$verifyer += 1;
			}
			else{
				$errors .= "* Invalid quiz selected.";
			}

			if($verifyer == 2){

				require_once 'app_config.php';
				$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

			    if (!($stmt = $connection->prepare("INSERT INTO question ( quiz_id, question_des) VALUES(?, ?)"))) {
			    echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;}

			    $stmt->bind_param("is", $cquiz, $question); 
			    $stmt->execute();
			    $stmt->close();
			    mysqli_close($connection);

			    

				echo "<form method='post' action='quizzes.php' id='reload'>
							<input type='text' hidden='hidden' name='c_quiz' value='".$cquiz."'>
							<input type='submit' hidden='hidden'>
					  </form>";
				echo "<script> document.forms['reload'].submit();</script>";
			}
			else{
				echo "<div style='background-color:red; color:white; width:50%'>".$errors."</div>";
			}
		}
		?>

		<?php
		if(isset($_POST['c_question_choice']) && isset($_POST['c_quiz_selected']) && isset($_POST['c_question_des'])){
			$question_des = $_POST['c_question_des'];
			$quiz = $_POST['c_quiz_selected'];
			$question = $_POST['c_question_choice'];

			require_once 'app_config.php';
			$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

			$sql = "SELECT choice_id, question_id, is_right_choice, choice FROM question_choice WHERE question_id = '$question'";
			$result = mysqli_query($connection, $sql);
			//$choices = array();
			$current = 0;
			$data = "";
			if(mysqli_num_rows($result) > 0){
				while($row = mysqli_fetch_assoc($result)){
					//$choices[$current] = $row['choice'];
					$current += 1;
					if($row['is_right_choice'] == 1){
						$data .= "<li>".$row['choice']." (correct)<form method='post' action='quizzes.php'><input type='text' name='choice_id' value='".$row['choice_id']."' hidden='hidden'><input type='text' name='question_id' value='".$row['question_id']."' hidden='hidden'><input type='text' name='quiz_id' value='".$quiz."' hidden='hidden'><input type='text' name='question_des' value='".$question_des."' hidden='hidden'><button style='border-radius:10px;background-color:black;color:white'><i class='fa fa-trash'></i> remove</button></form></li>";
					}
					else{
						$data .= "<li>".$row['choice']."<form method='post' action='quizzes.php'><input type='text' name='choice_id' value='".$row['choice_id']."' hidden='hidden'><input type='text' name='question_id' value='".$row['question_id']."' hidden='hidden'><input type='text' name='quiz_id' value='".$quiz."' hidden='hidden'><input type='text' name='question_des' value='".$question_des."' hidden='hidden'><button style='border-radius:10px;background-color:black;color:white'><i class='fa fa-trash'></i> remove</button></form></li>";
					}
				}
			}
			else{
				$data .= "No choices yet for this question.";
			}

			$sql2 = "SELECT question_id, quiz_id, question_des FROM question WHERE quiz_id = '$quiz'";
			$result2 = mysqli_query($connection, $sql2);
			$current_question = 0;
			$information = "";
			if(mysqli_num_rows($result2) > 0){
				while($row = mysqli_fetch_assoc($result2)){

					if($row['question_id'] == $question){
						$information .= "<li><form method='post' action='quizzes.php'>
											<input type='text' name='c_question_des' value='".$row['question_des']."' hidden='hidden'>
											<input type='text' name='c_quiz_selected' value='".$row['quiz_id']."' hidden='hidden'>
											<input type='text' name='c_question_choice' value='".$row['question_id']."' hidden='hidden'>
											<button style='background-color:white;color:green;border:none;cursor: pointer'>".$row['question_des']."</button>
										</form></li>";
					}
					else{
						$information .= "<li><form method='post' action='quizzes.php'>
											<input type='text' name='c_question_des' value='".$row['question_des']."' hidden='hidden'>
											<input type='text' name='c_quiz_selected' value='".$row['quiz_id']."' hidden='hidden'>
											<input type='text' name='c_question_choice' value='".$row['question_id']."' hidden='hidden'>
											<button style='background-color:white;color:black;border:none;cursor: pointer'>".$row['question_des']."</button>
										</form></li>";
					}
				}
			}

     echo "<div id='choice_edit' class='w3-modal'>
			  <div class='w3-modal-content'>
			    <div class='w3-container'>
			    <br>
			    	<div class='w3-right'>
			      		<form action='quizzes.php' method='post'>
			      		<input type='text' name='c_quiz' hidden='hidden' value='".$quiz."'>
			      			<button style='background-color:black;color:yellow;cursor: pointer;'><i class='fa fa-close'></i></button>
			      		</form>
			      	</div>
			    	<div class='w3-cell'>
			    	  <hr>
			    	  <span style='color:white;background-color:black'>selected question</span><br>
				      <span style='color:red'>".$_POST['c_question_des']."</span>
				      <hr>
				      <br>	
				      <form method='post' action='add_choice.php'>
				      		choice:<br>
				      		<input type='hidden' name='q_id' value='".$question."'>
				      		<input type='hidden' name='qz_id' value='".$quiz."'>
				      		<input type='hidden' name='question' value='".$_POST['c_question_des']."'>
				      		<textarea name='choice'></textarea><br>
				      		is correct : <input type='checkbox' name='choice_correct' value='1'> (Tick if correct)<br>
				      		<br>
				      		<input type='submit' value='Add choice' style='background-color:black;color:white;border-radius:5px'>
				      </form>
			      	</div>
			      	<div class='w3-cell'>
			      		<p>Added Choice</p>
			      		<ol>
			      			".$data."                               
			      		</ol>
			      		<form method='post' action='quizzes.php'>
			      			<input type='text' name='c_question_edit' hidden='hidden' value='".$question."'>
			      			<input type='text' name='c_quiz_selected' hidden='hidden' value='".$quiz."'>
			      			<input type='text' name='c_question_des' hidden='hidden' value='".$question_des."'>
			      			<input type='submit' value='Edit Question'>	
			      		</form>
		      			<form method='post' action='quizzes.php'>
							<input type='text' hidden='hidden' value='".$question."' name='c_question_delete'>
							<input type='text' name='selected_quiz' hidden='hidden' value='".$quiz."'>
							<input type='submit' value='delete question'>
						</form>
			      	</div>
			      	<div class='w3-cell'>
			      	<p>Other questions in this quiz</p>
			      	<ol>
			      		".$information	."
			      	</ol>
			      	</div>
			      	<br>
			    </div>
			  </div>
			</div>";

			echo "<script>document.getElementById('choice_edit').style.display='block';</script>";

			mysqli_close($connection);
		}
		?>
		<?php
		if(isset($_POST['c_question_edit']) && isset($_POST['c_quiz_selected']) && isset($_POST['c_question_des'])){
			$question = $_POST['c_question_edit'];
			$quiz = $_POST['c_quiz_selected'];
			$description = $_POST['c_question_des'];

			echo "<div id='question_edit' class='w3-modal'>
			  		<div class='w3-modal-content'>
			    		<div class='w3-container'>
			    			<div class='w3-right'>
			    				<form action='quizzes.php' method='post'>
				      				<input type='text' name='c_quiz' hidden='hidden' value='".$quiz."'>
				      				<button style='background-color:black;color:yellow;cursor: pointer;'><i class='fa fa-close'></i></button>
				      			</form>
			      			</div>
		    				<br>
		    				<span style='color:white;background-color:black'>edit question below</span><br>
		    				<hr>
			    			<form method='post' action='quizzes.php'>
			    				<span style='color:red;'>Question:</span><br>
			    				<textarea name='update_question_description' required='required'>".$description."</textarea>
			    				<input type='text' name='update_question_id' value='".$question."' hidden='hidden'>
			    				<input type='text' name='update_question_quiz' value='".$quiz."' hidden='hidden'><br>
			    				<hr>
			    				<button style='background-color:black;color:white;border-radius:5px'>Edit question</button>
			    			</form>
			    			<br>
			    			<br>
			    		</div>
			    	</div>
			      </div>";

			echo "<script>document.getElementById('question_edit').style.display='block';</script>";
		}
		?>
		<?php
		if(isset($_POST['update_question_id']) && isset($_POST['update_question_description']) && isset($_POST['update_question_quiz'])){
			$question = $_POST['update_question_id'];
			$description = $_POST['update_question_description'];
			$quiz = $_POST['update_question_quiz'];

			require_once 'app_config.php';
			$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

			$stmt = $connection->prepare("UPDATE question SET  question_des = ? WHERE question_id = ?");
			$stmt->bind_param("si", $description, $question);
			$stmt->execute();
			$stmt->close();
			mysqli_close($connection);
		 	echo "<form method='post' action='quizzes.php' id='choi'><input type='text' hidden='hidden' name='c_quiz' value='".$quiz."'><input type='submit' hidden='hidden'></form>";
			echo "<script> document.forms['choi'].submit();</script>";
		}
		?>

		<?php
		if(isset($_POST['choice_id'])){

				if(is_numeric($_POST['choice_id'])){
			 		
					require_once 'app_config.php';
					$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
					
			 		$id   = $_POST['choice_id'];    
			 		//Delete an announcement from the teacher table
			 		$query1  = "DELETE FROM question_choice WHERE choice_id='$id'";    
			 		$result1 = $connection->query($query1);    
			 		if (!$result1){ 
			 			echo "DELETE failed: $query<br>" . $connection->error . "<br><br>";  
			 		}
			 		mysqli_close($connection);
			 		echo "<form method='post' action='quizzes.php' id='choi'><input type='text' hidden='hidden' name='c_question_choice' value='".$_POST['question_id']."'><input type='text' hidden='hidden' name='c_quiz_selected' value='".$_POST['quiz_id']."'><input type='text' hidden='hidden' name='c_question_des' value='".$_POST['question_des']."'><input type='submit' hidden='hidden'></form>";
					echo "<script> document.forms['choi'].submit();</script>";

			 	}
		}
		?>
	</div>
	</div>
</body>
</html>