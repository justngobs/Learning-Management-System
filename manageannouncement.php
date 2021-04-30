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
	<title>Manage announcement</title>
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
			require 'teachermenu.php';
		}
		else{
			echo "<script> document.location = 'teacher.php'; </script>";
		}
	?>
</div>
<div style="margin-left: 25%">
	<div id = "content">
		<h4><b>Announcements</b></h4>
		<div class="w3-accordion" style="width: 93%">
			<!-- Form to add a new student to the system-->
			<div id="forms" class="w3-accordion-content w3-container" style="margin-right: 50%">
				<!-- Form to post an announcemengt to the students taking the particular subject-->
				<div class="w3-container w3-cell" style="border-style: solid;border-left-color: black;">
						<form method="POST" action="manageannouncement.php">

							<!-- documentation -->
							<div class="tooltip" style="margin-left: 98%"><i class="fa fa-question-circle" style="color: blue;font-size: 25px" aria-hidden="true"></i>
							  <span class="tooltiptext">Required fields are marked with an asteric(*). Announcements will be posted to all the students that take this subject.</span>
							</div>
							<!-- End documentation -->

							Title:<br><input style="border-radius: 7px; border-color: lightblue;" type="text" name="title" required="required" placeholder="Enter title"><span style="color: red"> * </span><br>
							Announcement:<br>
							<textarea style="border-radius: 7px; border-color: lightblue;" name="announcement" required="required" placeholder="type announcement..."></textarea><span style="color: red"> * </span><br><br>
							<button name="postAnnouncement">Post Announcement</button>
						</form>
						<?php
						if(isset($_POST['postAnnouncement'])){
							require "phpgoodies.php";
							$cantcatchme = 0;
							$errors = "";

							// Validate title entered entered
							if(empty($_POST['title'])){
								$errors .= "Title is empty<br>";
							}
							else{
								$title = test_input($_POST['title']);
								//Do validation of announcement title
								if(!preg_match('/^[a-zA-Z0-9 .,:()]+$/', $title)) {
							     $errors .= "Title should contain letters, numbers and symbols :,.()<br>";
							    }
							    else{
							    	$cantcatchme += 1;
							    }
							}

							// Validate announcement entered
							if(empty($_POST['announcement'])){
								$errors .= "Title is empty<br>";
							}
							else{
								$announcement = test_input($_POST['announcement']);
								//Do validation of announcement
								if(!preg_match('/^[a-zA-Z0-9 .,:()]+$/', $announcement)) {
							    $errors .= "Description should contain letters, numbers and symbols :,.()<br>";
							    }
							    else{
							    	$cantcatchme += 1;
							    }
								
							}

							if($cantcatchme == 2){

								require_once "app_config.php";
								$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

							    if (!($stmt = $connection->prepare("INSERT INTO announcement (sub_id, tea_id, ann_name, ann_description) VALUES(?, ?, ?, ?)"))) {
							    echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;}

							    $stmt->bind_param("iiss", $a, $b, $c, $d); 
								$a = $_SESSION['current_subject'];
								$b = $_SESSION['id'];
								$c = $title;
								$d = $announcement ;
							    	
							    $stmt->execute();
							    $stmt->close();
							    $connection->close();
							    echo "<div style='background-color:green;color:white'>Successfully added</div>";
							}

							else{
								echo "<div style='background-color:red;color:white'>".$errors."</div>";
							}
						}

						?>
						<br>
				</div>

					<?php
					//Code to delete the current selected announcement from all the tables
					 if (isset($_POST['deleteannouncement']) && isset($_POST["c_announcement"]))  
					 	{    
					 		if(is_numeric($_POST['c_announcement'])){
						 		
								require_once 'app_config.php';
								$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

						 		$id   = $_POST['c_announcement'];    
						 		//Delete an announcement from the teacher table
						 		$query1  = "DELETE FROM announcement WHERE ann_id='$id'";    
						 		$result1 = $connection->query($query1);    
						 		if (!$result1){ 
						 			echo "DELETE failed: $query<br>" . $connection->error . "<br><br>";  
						 		}
						 		mysqli_close($connection);
						 		echo "<script>document.location = 'manageannouncement.php';</script>";
						 	}
					 	}

					?>
					<?php 
						if(isset($_POST['c_announcement'])){
							if(is_numeric($_POST['c_announcement'])){
									echo "<div class='w3-container w3-cell' style='border-style: solid;border-right-color: black;'>";

									require_once 'app_config.php';
									$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

									echo "<span style='color:white;background-color:black'>Announcement Number</span> : ".htmlspecialchars($_POST['c_announcement'])."<br>";
									//This represent a potential sql injection. Fix it
									$sql = "SELECT ann_name, ann_description,date_posted FROM announcement WHERE ann_id = '".$_POST['c_announcement']."'";
									$result = mysqli_query($connection, $sql);

									//Create a dynamic form and fill it with database values here
									if(mysqli_num_rows($result) > 0){
										while($row = mysqli_fetch_assoc($result)){
											echo "<form method='post' action='updatesubjectannouncement.php'>
													<input type='text' name='c_announcement' value = '".$_POST['c_announcement']."' hidden='hidden'>			 
												  <b>Title:</b><br><input style='border-radius: 7px; border-color: black;' type='text' name='title' required='required' placeholder='Enter subject name' value='".$row['ann_name']."'><br>

											      <b>Announcement:</b><br><textarea style='border-radius: 7px; border-color: black' type='text'  name='announcement' required='required' >".$row['ann_description']."</textarea><br>
											      <br>
												  <button>Update announcement</button><br>
										          </form>";
										}
								
										//Create a post form for deleting the current selected subject		
										echo "<form method='POST' action='manageannouncement.php'>
												<input style='width:5%;'id='c_announcement' type='text' name='c_announcement' hidden='hidden' value='".$_POST["c_announcement"]."'>
												<button name='deleteannouncement'>Delete announcement</button>
											  </form>";
									mysqli_close($connection);	
						            }
						        echo "</div>";			            
						    }
						}

					?>
			</div>
		</div>
		<h4><b>Posted Announcements</b></h4>
		<?php

			require_once 'app_config.php';
			$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
			
			//Query to view all messages
			$c_subject = $_SESSION['current_subject'];


			$sql = "SELECT ann_id, sub_id, tea_id, ann_name, ann_description, date_posted, last_modified FROM announcement WHERE sub_id = '$c_subject' ORDER BY ann_id DESC";


			$result = mysqli_query($connection, $sql);
			if(mysqli_num_rows($result) > 0){
				echo "<table class='w3-table w3-striped w3-bordered w3-hoverable' style='width:98%'>
					<thead style='background-color: rgb(74,74,74);'>
					<tr>
						<th style='color:rgb(235,238,196)'>#</th>
						<th style='color:rgb(235,238,196)'>Date Posted</th>
						<th style='color:rgb(235,238,196)'>Posted by</th>
						<th style='color:rgb(235,238,196)'>Title</th>
						<th style='color:rgb(235,238,196)'>Description</th>
						<th style='color:rgb(235,238,196)'>Last modified</th>
						<th style='color:rgb(235,238,196)'>Action</th>
					</tr>
					</thead>
					<tbody>";
				while($row = mysqli_fetch_assoc($result)){
					echo "
					<tr>
						<td>".htmlspecialchars($row['ann_id'])."</td>
						<td>".htmlspecialchars($row['date_posted'])."</td>

						<td>".htmlspecialchars($row['tea_id'])."</td>

						<td>".htmlspecialchars($row['ann_name'])."</td>
						<td>".htmlspecialchars($row['ann_description'])."</td>
						<td>".htmlspecialchars($row['last_modified'])."</td>
						<td>
							<form method='POST' action='manageannouncement.php'>
								<input type='text' name='c_announcement' value='".$row['ann_id']."' hidden='hidden'>
								<input type='text' name='c_date' value='".$row['date_posted']."' hidden='hidden'>
								<button style='background-color: rgb(74,74,74); color: white;border-radius:5px;border-color:black;'>edit</button>
							</form>
						</td>
					</tr>";
				}
				echo "</tbody></table>";
			}
			else{
				echo "No announcements yet.";
			}
			mysqli_close($connection);
		?>
	</div>
</div>
</body>
</html>