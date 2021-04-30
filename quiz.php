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

			echo "<a href='manageassignment.php'><i class='fa fa-arrow-circle-left' style='font-size:48px;color:yellow'></i></a><a href='teacher.php'><i class='fa fa-home' style='font-size:48px;color:yellow;margin-left: 5%'></i></a>
			<br>";
			echo "<h4 style='color:white;margin-left:20px'><u>".htmlspecialchars(ucfirst($_SESSION['subject_name']))." ( ".htmlspecialchars($_SESSION['subject_code'])." </u>)</h4><br>";

			//The teacher navigation bar
			require 'teachermenu.php';
		}
		else{
			echo "<script> document.location = 'teacher.php'; </script>";
		}
	?>
</div>
<div style="margin-left: 25%">
	<div id = "content">
		<h4><b>Quizzes</b> | <a href="manageassignment.php" style="color: blue">Assignments</a> | <a href="quizzes.php" style="color: blue">Advanced Quiz Manager</a></h4>
		<div class="w3-container w3-cell" style="border-style: solid;border-left-color: black;">
			<form method="post" action="postquiz.php">

				<!-- documentation -->
				<div class="tooltip" style="margin-left: 98%"><i class="fa fa-question-circle" style="color: blue;font-size: 25px" aria-hidden="true"></i>
				  <span class="tooltiptext">Required fields are marked with an asteric(*). Number of questions represents the number of questions that a student will answer in this quiz. You can add more questions than the number of questions later on quiz management. This will ensure that students do not get the same questions as the questions are random when taken by the student. Published quizzes can be taken by students whiles unpublished quizzes can only be seen by you.</span>
				</div>
				<!-- End documentation -->

				Title: <input type="text" name="title" placeholder="Quiz Title" required="required" style="border-radius: 5px;"><span style="color: red"> * </span><br><br>
				Number of Questions: <input type="number" name="n" min="1" max="100" style="width: 20%;border-radius: 5px" required="required"><span style="color: red"> * </span><br><br>
				Due Date: <input type="date" name="due_date" required="required" style="border-radius: 5px"><span style="color: red"> * </span><br><br>
				Description: <br>
				<textarea placeholder="Enter quiz description" required="required" name="description" style="border-radius: 5px"></textarea><span style="color: red"> * </span>
				<input type="submit" value="Add quiz"><br><br>
			</form>
			<?php
			if(isset($_POST['post_status'])){

				$data = $_POST['post_status'];
				$new_data = "";
				$str_arr = explode ("#", $data);

				for($i = 0; $i < count($str_arr); $i++){
					$new_data .= $str_arr[$i]."<br>";
				}

				echo "<div style='color:white;background-color:red'>".$new_data."</div>";

			}
			?>
		</div>

			<?php
			//This code will edit basic quiz questions but will mainly be for adding questions and options.
			if(isset($_POST['edit_quiz'])){

				echo "<div class='w3-container w3-cell' style='border-style: solid;border-left-color: black;'>";

				echo "<form method='post' action='updatequiz.php'>
						Title: <input type='text' name='title' placeholder='Quiz Title' required='required' value='".$_POST['title']."'><br>
						Number of Questions: <input type='number' name='n' min='1' max='100' style='width: 20%' required='required' value='".$_POST['n']."'><br>
						Due Date: <input type='date' name='due_date' required='required' value='".$_POST['due_date']."'><br>
						Description: <br>
						<textarea placeholder='Enter quiz description' required='required' name='description'>".$_POST['description']."</textarea>
						<input type='text' hidden='hidden' value='".$_POST['quiz']."' name='quiz'>
						<input type='submit' value='Update quiz'>
					</form>";
				echo "</div>";

			}
			?>
		
			<?php
			//code to publish and unpublish a quiz
			if(isset($_POST['update_quiz_status'])){
			
				$value = 0;
				$quiz = 0;
				if(isset($_POST['unpublish']) && !empty($_POST['unpublish'])){
					$value = 0;
					$quiz = $_POST['unpublish'];
				}
				else{
					$value = 1;
					$quiz = $_POST['publish'];
				}
				if(is_numeric($quiz)){

					require_once "app_config.php";
					$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

					$stmt = $connection->prepare("UPDATE quiz SET status = ? WHERE quiz_id = ? ");
					$stmt->bind_param("ii",$value, $quiz);
					$stmt->execute();
					$stmt->close();

					mysqli_close($connection);
				}
			}
			?>

			<?php
			if(isset($_POST['c_quiz_delete'])){
				if(is_numeric($_POST['c_quiz_delete'])){
			 		
					require_once 'app_config.php';
					$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

			 		$id   = $_POST['c_quiz_delete'];    
			 		//Delete an announcement from the teacher table
			 		$query1  = "DELETE FROM quiz WHERE quiz_id='$id'";    
			 		$result1 = $connection->query($query1);    
			 		if (!$result1){ 
			 			echo "DELETE failed: $query<br>" . $connection->error . "<br><br>";  
			 		}
			 		mysqli_close($connection);
			 	}
			}
			?>
		<h4><b>Posted Quizzes</b></h4>
		<?php

		require_once 'app_config.php';
		$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

		$c_subject = $_SESSION['current_subject'];

		if(is_numeric($c_subject)){
			$sql = "SELECT quiz_id, sub_id, publish_date, due_date, quiz_title, quiz_description, status, n_of_questions FROM quiz WHERE sub_id ='$c_subject'";

			$result = mysqli_query($connection, $sql);
			$n = 0;
			if(mysqli_num_rows($result) > 0){
				echo "<table class='w3-table w3-striped w3-bordered w3-hoverable' style='width:98%'>
					<thead style='background-color: rgb(74,74,74);'>
					<tr>
						<th style='color:rgb(235,238,196)'>#</th>
						<th style='color:rgb(235,238,196)'>Title</th>
						<th style='color:rgb(235,238,196)'>Description</th>
						<th style='color:rgb(235,238,196)'>Questions</th>
						<th style='color:rgb(235,238,196)'>Date published</th>
						<th style='color:rgb(235,238,196)'>Due date</th>
						<th style='color:rgb(235,238,196)'></th>
						<th style='color:rgb(235,238,196)'></th>
						<th style='color:rgb(235,238,196)'></th>
						<th style='color:rgb(235,238,196)'></th>
					</tr>
					</thead>
					<tbody>";
				while($row = mysqli_fetch_assoc($result)){
					$n += 1;
					if($row['status'] == 1){
						echo "<tr>
								<td>".($n)."</td>
								<td>".$row['quiz_title']."</td>
								<td>".$row['quiz_description']."</td>
								<td>".$row['n_of_questions']."</td>
								<td>".$row['publish_date']."</td>
								<td>".$row['due_date']."</td>
								<td>
									<form method='post' action='quiz.php'>
										<input type='text' hidden='hidden' value='".$row['quiz_id']."' name='unpublish'>
										<input type='submit' value='unpublish' name='update_quiz_status'>
									</form>
								</td>
								<td>
									<form method='post' action='quiz.php'>
										<input type='text' name='title' value='".$row['quiz_title']."' hidden='hidden'>
										<input type='text' name='description' value='".$row['quiz_description']."' hidden='hidden'>
										<input type='text' name='n' value='".$row['n_of_questions']."' hidden='hidden'>
										<input type='text' name='date_published' value='".$row['publish_date']."' hidden='hidden'>
										<input type='text' name='due_date' value='".$row['due_date']."' hidden='hidden'>
										<input type='text' hidden='hidden' value='".$row['quiz_id']."' name='quiz'>
										<input type='submit' value='edit' name='edit_quiz'>
									</form>
								</td>
								<td>
									<form method='post' action='quizzes.php'>
										<input type='text' hidden='hidden' value='".$row['quiz_id']."' name='c_quiz'>
										<input type='submit' value='view'>
									</form>
								</td>
								<td>
									<form method='post' action='quiz.php'>
										<input type='text' hidden='hidden' value='".$row['quiz_id']."' name='c_quiz_delete'>
										<input type='submit' value='delete'>
									</form>
								</td>
							 </tr>";

					}
					else{
						echo "<tr>
								<td>".($n)."</td>
								<td>".$row['quiz_title']."</td>
								<td>".$row['quiz_description']."</td>
								<td>".$row['n_of_questions']."</td>
								<td>".$row['publish_date']."</td>
								<td>".$row['due_date']."</td>
								<td>
									<form method='post' action='quiz.php'>
										<input type='text' hidden='hidden' value='".$row['quiz_id']."' name='publish'>
										<input type='submit' value='publish' name='update_quiz_status'>
									</form>
								</td>
								<td>
									<form method='post' action='quiz.php'>
										<input type='text' name='title' value='".$row['quiz_title']."' hidden='hidden'>
										<input type='text' name='description' value='".$row['quiz_description']."' hidden='hidden'>
										<input type='text' name='n' value='".$row['n_of_questions']."' hidden='hidden'>
										<input type='text' name='date_published' value='".$row['publish_date']."' hidden='hidden'>
										<input type='text' name='due_date' value='".$row['due_date']."' hidden='hidden'>
										<input type='text' hidden='hidden' value='".$row['quiz_id']."' name='quiz'>
										<input type='submit' value='edit' name='edit_quiz'>
									</form>
								</td>
								<td>
									<form method='post' action='quizzes.php'>
										<input type='text' hidden='hidden' value='".$row['quiz_id']."' name='c_quiz'>
										<input type='submit' value='view'>
									</form>
								</td>
								<td>
									<form method='post' action='quiz.php'>
										<input type='text' hidden='hidden' value='".$row['quiz_id']."' name='c_quiz_delete'>
										<input type='submit' value='delete'>
									</form>
								</td>
							 </tr>";
					}
				}
				echo "</tbody></table>";
			}
			else{
				echo "No Quizzes posted yet. Post a quiz.";
			}
		}
		else{
			echo "ngazo hlanya san";
		}

		mysqli_close($connection);
		?>
	</div>
</div>
</body>
</html>