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
	<title>Manage assignments</title>
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<link rel="stylesheet" type="text/css" href="tooltip.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="javascriptgoodies.js"></script>
	<script type="text/javascript" src="javascriptFormsValidations.js"></script>
</head>
<body style="background-color: rgb(249,247,243)">
<div class="w3-sidebar w3-bar-block" style="width: 20%; background-color: rgb(74,74,74);">

	<?php
		if(isset($_SESSION['current_subject']) && isset($_SESSION['subject_name']) && isset($_SESSION['subject_code'])){

			echo "<a href='teachsubject.php?subject_id=".$_SESSION['current_subject']."'><i class='fa fa-arrow-circle-left' style='font-size:48px;color:yellow'></i></a><a href='teacher.php'><i class='fa fa-home' style='font-size:48px;color:yellow;margin-left: 5%'></i></a>
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
		<h4><b>Assignments</b> | <a href="quiz.php" style="color: blue">Create Quiz</a> |<a href="gradeassignment.php" style="color: blue">Grade Assignments</a></h4>
		<div class="w3-container w3-cell" style="border-style: solid;border-left-color: black;">
			<form  action="manageassignment.php" method="post">

				<!-- documentation -->
				<div class="tooltip" style="margin-left: 98%"><i class="fa fa-question-circle" style="color: blue;font-size: 30px" aria-hidden="true"></i>
				  <span class="tooltiptext">Required fields are marked with an asteric(*). The file upload enables you to decide whether students can submit this assignment online or not. Assignments that require attachments can be uploaded under contents on subject notes and then an upload link can be created from this page.</span>
				</div>
				<!-- End documentation -->

				<br>Title:<input style="border-radius: 7px; border-color: lightblue;" type="text" name="title" required="required" placeholder="Enter content title"><span style="color: red"> * </span>
				<span style="margin-left: 3%">File Upload: </span><select style="border-radius: 7px; border-color: lightblue;" type="text" name="fileOption"><option value="enable">Enable</option><option value="disable">Disable</option></select><span style="color: red"> * </span><br><br>
				Due Date: <input style="border-radius: 7px; border-color: lightblue;" type="date" name="dueDate" required="required"> <span style="color: red"> * </span>
				<span style="margin-left: 3%">Total Marks: </span><input style="border-radius: 7px; border-color: lightblue;width: 20%" type="number" min="1" name="totalMarks" required="required"><span style="color: red"> * </span><br><br>
				Description:<br>
				<textarea style="border-radius: 7px; border-color: lightblue;" name="description" required="required" placeholder="type announcement..."></textarea><span style="color: red"> * </span>
				<button style="margin-left: 3%" >Post Assignment</button>
				<br>
				<br>
			</form>

			<?php

			require "phpgoodies.php";

			if(isset($_POST['title']) && isset($_POST['description']) && isset($_POST['fileOption']) && isset($_POST['dueDate']) && isset($_POST['totalMarks']) && !empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['fileOption']) && !empty($_POST['dueDate']) && !empty($_POST['totalMarks'])){
				$cantcatchme = 0;
				$errors = "";

				//Validate the due date if is date or if greater than the current date
				$due_date = test_input($_POST['dueDate']);
				// make sure the user really entered a date
				if (isRealDate($due_date)) {
				    $cantcatchme += 1;
				}
				else{
					$errors .= "* Invalid due date.<br>";
				}

				//Validate the dropdown for the file enable and disable link
				$file_option = test_input($_POST['fileOption']);
				if($file_option == "enable" || $file_option == "disable"){
					$cantcatchme += 1;
				}
				else{
					$errors .= "* File option should be enable or disable.<br>";
				}

				//Validate the mark entered
				$mark = test_input($_POST['totalMarks']);
				if(is_numeric($mark)){
					if($mark > 0){
						$cantcatchme += 1;
					}
				}
				else{
					$errors .= "* Mark should be greater than zero.<br>";
				}

				//Validate the title for the assessment
				$assessment = test_input($_POST['title']);
				if(!preg_match('/^[a-zA-Z0-9 .,:()]+$/', $assessment)) {
			      $errors .= "Title should contain letters, numbers and symbols :,.()<br>";
			    }
			    else{
			    	$cantcatchme += 1;
			    }

				//Validate assignment description
				$description = test_input($_POST['description']);
				if(!preg_match('/^[a-zA-Z0-9 .,:()]+$/', $description)) {
			      $errors .= "Description should contain letters, numbers and symbols :,.()<br>";
			    }
			    else{
			    	$cantcatchme += 1;
			    }


				if($cantcatchme == 5){

					require_once "app_config.php";
					$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

					if (!($stmt = $connection->prepare("INSERT INTO assignment (sub_id, tea_id, ass_name, ass_description, date_posted, due_date, marks, upload_link) VALUES(?, ?, ?, ?, ?, ?, ?, ?)"))) {
					echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;}

					$subje = $_SESSION['current_subject'];
					$useri = $_SESSION['id'];

					$stmt->bind_param("iissssis",$a, $b, $c, $d, $e, $f, $g, $h); 
					$a = $subje;
					$b = $useri;
					$c = $assessment;
					$d = $description;
					$e = date('Ymd');
					$f = $due_date;
					$g = $mark;
					$h = $file_option;
		
					$stmt->execute();
					$stmt->close();
					mysqli_close($connection);
					echo "<br><span style='background-color:green;color:white'>Assignment successfully added</span><br><br>";
				}

				else{
					echo "<br><div style='background-color:red'><span style='color:white'>".$errors."</span></div>";
				}
			}
			?>
		</div>
		<div class="w3-container w3-cell" style="border-radius: 5px;background-color: rgb(249,247,243);">
		<?php
		//Code to delete the current selected announcement from all the tables
		 if (isset($_POST['deleteassignment']) && isset($_POST["c_assignment"]))  
		 	{    
		 		if(is_numeric($_POST['c_assignment'])){
			 		
					require_once 'app_config.php';
					$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

			 		$id   = $_POST['c_assignment'];    
			 		//Delete an announcement from the teacher table
			 		$query1  = "DELETE FROM assignment WHERE ass_id='$id'";    
			 		$result1 = $connection->query($query1);    
			 		if (!$result1){ 
			 			echo "DELETE failed: $query<br>" . $connection->error . "<br><br>";  
			 		}
			 		mysqli_close($connection);
			 		echo "<script>document.location = 'manageassignment.php';</script>";
			 	}
		 	}

		?>

		<?php
		if(isset($_POST['c_assignment'])){

			if(is_numeric($_POST['c_assignment'])){

				$id = $_POST['c_assignment'];

				require_once 'app_config.php';
				$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
				
				$sql = "SELECT ass_id, sub_id, tea_id, ass_name, ass_description, due_date, marks, upload_link FROM assignment WHERE ass_id = '$id'";
				$result = mysqli_query($connection, $sql);

				if(mysqli_num_rows($result) > 0){
					while($row = mysqli_fetch_assoc($result)){

						if($row['upload_link'] == "enable"){
							echo"

							<form  action='updateassignment.php' method='post'>
								<br>Title:<input style='border-radius: 7px; border-color: pink;' type='text' name='title' required='required' placeholder='Enter content title' value='".$row['ass_name']."'><span style='color: red'> * </span>
								<span style='margin-left: 3%'>File Upload: </span><select style='border-radius: 7px; border-color: lightblue;' type='text' name='fileOption'><option value='enable' selected='selected'>Enable</option><option value='disable'>Disable</option></select><span style='color: red'> * </span><br><br>
								Due Date: <input style='border-radius: 7px; border-color: pink;' type='date' name='dueDate' required='required' value='".$row['due_date']."'> <span style='color: red'> * </span>
								<span style='margin-left: 3%'>Total Marks: </span><input style='border-radius: 7px; border-color: pink;width: 20%' type='number' min='1' name='totalMarks' required='required' value='".$row['marks']."'><span style='color: red'> * </span><br><br>
								Description:<br>
								<textarea style='border-radius: 7px; border-color: pink;' name='description' required='required' placeholder='type announcement...' >".$row['ass_description']."</textarea><span style='color: red'> * </span>
								<input type='text' name='c_assignment' value='".$row['ass_id']."' hidden='hidden'>
								<input style='margin-left: 3%' type='submit' name='submit' value='Update Assignment' />

								<br>
								<br>
							</form>
							<form action='manageassignment.php' method='post'>
								<input type='text' name='c_assignment' value='".$row['ass_id']."' hidden='hidden'>
								<button name='deleteassignment'>Delete Assignment</button>
							</form>

							";
					}
					else{
						echo"

							<form  action='updateassignment.php' method='post'>
								<br>Title:<input style='border-radius: 7px; border-color: pink;' type='text' name='title' required='required' placeholder='Enter content title' value='".$row['ass_name']."'><span style='color: red'> * </span>
								<span style='margin-left: 3%'>File Upload: </span><select style='border-radius: 7px; border-color: lightblue;' type='text' name='fileOption'><option value='enable'>Enable</option><option value='disable' selected='selected'>Disable</option></select><span style='color: red'> * </span><br><br>
								Due Date: <input style='border-radius: 7px; border-color: pink;' type='date' name='dueDate' required='required' value='".$row['due_date']."'> <span style='color: red'> * </span>
								<span style='margin-left: 3%'>Total Marks: </span><input style='border-radius: 7px; border-color: pink;width: 20%' type='number' min='1' name='totalMarks' required='required' value='".$row['marks']."'><span style='color: red'> * </span><br><br>
								Description:<br>
								<textarea style='border-radius: 7px; border-color: pink;' name='description' required='required' placeholder='type announcement...' >".$row['ass_description']."</textarea><span style='color: red'> * </span>
								<input type='text' name='c_assignment' value='".$row['ass_id']."' hidden='hidden'>
								<input style='margin-left: 3%' type='submit' name='submit' value='Update Assignment' />
								<br>
								<br>
							</form>
							<form action='manageassignment.php' method='post'>
								<input type='text' name='c_assignment' value='".$row['ass_id']."' hidden='hidden'>
								<button name='deleteassignment'>Delete Assignment</button>
							</form>

							";
					}

					}
				}

				mysqli_close($connection);

			}

		}
		?>
		</div>

		<h4><b>Posted Assignments</b></h4>
		<?php

			require_once 'app_config.php';
			$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

			//Query to view all messages
			$c_subject = $_SESSION['current_subject'];

			if(is_numeric($c_subject))
			{
				$sql = "SELECT ass_id, sub_id, tea_id, ass_name, ass_description, date_posted FROM assignment WHERE sub_id = '$c_subject' ORDER BY ass_id DESC";


				$result = mysqli_query($connection, $sql);
				if(mysqli_num_rows($result) > 0)
				{
					echo "<table class='w3-table w3-striped w3-bordered w3-hoverable' style='width:98%'>
						<thead style='background-color: rgb(74,74,74);'>
						<tr>
							<th style='color:rgb(235,238,196)'>#</th>
							<th style='color:rgb(235,238,196)'>Date Posted</th>
							<th style='color:rgb(235,238,196)'>Posted by</th>
							<th style='color:rgb(235,238,196)'>Title</th>
							<th style='color:rgb(235,238,196)'>Description</th>
							<th style='color:rgb(235,238,196)'></th>
							<th style='color:rgb(235,238,196)'></th>
						</tr>
						</thead>
						<tbody>";
					while($row = mysqli_fetch_assoc($result))
					{
						echo "<tr>
									<td>".htmlspecialchars($row['ass_id'])."</td>
									<td>".htmlspecialchars($row['date_posted'])."</td>
									<td>".htmlspecialchars($row['tea_id'])."</td>
									<td>".htmlspecialchars($row['ass_name'])."</td>
									<td>".htmlspecialchars($row['ass_description'])."</td>
									<td>
										<form method='post' action='manageassignment.php'>
											<input type='text' name='c_assignment' value='".$row['ass_id']."' hidden='hidden'>
											<input type='submit' value='Edit'>
										</form>
									</td>
									<td>
										<form method='post' action='gradeassignment.php'>
										<input type='text' name='c_assignment' value='".$row['ass_id']."' hidden='hidden'>
										<input type='submit' value='View'>
										</form>
									</td>
							  </tr>";
					}

					echo "</tbody></table>";

				}
				else
				{
					echo "No assignments yet. Post an assignment.";
				}
			}

			mysqli_close($connection);
			?>
</div>
</div>
</body>
</html>