<?php
session_start();

if(!isset($_SESSION['loggedin'])){
	header('Location: login.php');
	exit();
}

$cantcatchme = 0;

if($_SESSION['ulevel'] == 3){
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
	<title>General manager</title>
	<meta http-equiv="X-UA-Compatible" content="IE=7, IE=8, IE=9, IE=edge"/>
    <meta http-equiv="Pragma" content="no-cache">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="javascriptgoodies.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="background-color:rgb(249,247,243)">	
<div class="w3-sidebar w3-bar-block" style="width: 20%; background-color: rgb(74,74,74);margin-left:1%; color: white">
	<a class="w3-bar-item w3-button" href="#"><i class="fa fa-user" style="font-size:48px;color:yellow"></i><i style="color: white"><b> Admin</b></i></a>
	<br>
	<strong>
		<a href="#" style="color: white"><i class="fa fa-bank" style="font-size: 30px;color: lightblue;"></i> General</a><br><br><br>
		<a href="studentmanager.php" style="color: white"><i class="fa fa-user" style="font-size: 30px;color: lightblue;"></i> Students</a><br><br><br>
		<a href="teachermanager.php" style="color: white"><i class="fa fa-user" style="font-size: 30px;color: lightblue;"></i> Teachers</a><br><br><br>
		<a href="subjectmanager.php" style="color: white"><i class="fa fa-book" style="font-size: 30px;color: lightblue;"></i> Subjects</a><br><br><br>
		<a href="messagemanager.php" style="color: white"><i class="fa fa-envelope" style="font-size: 30px;color: lightblue;"></i> Messages  </a><br><br><br>
		<a href="reports.php" style="color: white"><i class="fa fa-pie-chart" style="font-size: 30px;color: lightblue;"></i>         Reports</a><br><br><br><br>
		<a href="logout.php" style="color: white"><i class="fa fa-sign-out" style="font-size:30px;color:orange"></i> logout</a>
	</strong>
</div>
<div id = "selection" style="margin-left: 25%">
	<h6><b>General manager</b></h6>
	<div id = "content" style="background-color: rgb(255,255,255);width:95%">
		<div class="w3-accordion">
		<button id = "btnText" onclick="ourFunction('forms')" class="w3-button w3-border w3-border-cyan w3-left">Hide</button><br><br>
		<!-- Form to add a new student to the system-->
		<div id="forms" class="w3-accordion-content w3-container" style="margin-right: 50%">
			<!-- Form to send a message, reply and forward-->
			<div class="w3-container w3-cell" style="border-style: solid;border-left-color: black;">		
				<span style="background-color: black; color: white">Post announcement </span>
				<br><br>
				<form method="POST" action="generalannouncement.php">
					Title:<br><input style="border-radius: 7px; border-color: pink;" type="text" name="title" required="required" placeholder="Enter title"><br>
					Announcement:<br>
					<textarea style="border-radius: 7px; border-color: pink;" name="announcement" required="required" placeholder="type announcement..."></textarea><br>
					<button>Post Announcement</button>
				</form>
			</div>
			<div class="w3-container w3-cell" style="border-style: solid;border-left-color: black;">
				<span style="background-color: black; color: white">Post timetable </span>
				<br><br>
				<form enctype="multipart/form-data" action="posttimetable.php" method="post">
					Title: <input type='text' name='title' placeholder='enter title' required='required' style="border-radius: 7px; border-color: lightblue;"><br>
					Type: <select style="border-radius: 7px; border-color: lightblue;" type="text" name="type"><option value="class">Class Timetable</option><option value="exam">Exam Timetable</option></select><br>
					Grade: <select style="border-radius: 7px; border-color: lightblue;" type="text" name="grade"> 
								<option value="8" selected="selected">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
						   </select><br><br>
					<input type="hidden" name="MAX_FILE_SIZE" value="8000000" /> <span style="color: red">Select file</span>:
					<input type="file" name="data" /><br>
					<input type="submit" name="submit" value="Post Timetable">
				</form>

			</div>
			<!-- place to view messages -->
			
			<!-- php code to update and delete goes here -->
			<?php
				//Code to delete the current selected teacher from all the tables
				 if (isset($_POST['deleteannouncement']) && isset($_POST["c_announcement"]))  
				 	{    
				 		if(is_numeric($_POST['c_announcement'])){

							require_once 'app_config.php';
							$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

					 		$id   = $_POST['c_announcement'];    
					 		//Delete a teacher from the teacher table
					 		$query1  = "DELETE FROM general_announcement WHERE gen_ann_id='$id'";    
					 		$result1 = $connection->query($query1);    
					 		if (!$result1){ 
					 			echo "DELETE failed: $query<br>" . $connection->error . "<br><br>";  
					 		}
					 		mysqli_close($connection);
					 		echo "<script>document.location = 'generalmanager.php';</script>";
					 	}
				 	}

				?>
			<?php 
				if(isset($_POST['c_announcement'])){
					echo "<div class='w3-container w3-cell' style='border-style: solid;border-left-color: black;'><span style='background-color: black; color: white'>Edit announcement </span><br>";
					if(is_numeric($_POST['c_announcement'])){

							require_once 'app_config.php';
							$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

							//This represent a potential sql injection. Fix it
							$sql = "SELECT gen_ann_name, gen_ann_description,gen_ann_date_posted FROM general_announcement WHERE gen_ann_id = '".$_POST['c_announcement']."'";
							$result = mysqli_query($connection, $sql);

							//Create a dynamic form and fill it with database values here
							if(mysqli_num_rows($result) > 0){
								while($row = mysqli_fetch_assoc($result)){
									echo "<form method='post' action='updategeneralannouncement.php'>
									<input type='text' name='c_announcement' value = '".htmlspecialchars($_POST['c_announcement'])."' hidden='hidden'>			 
									<b>Title:</b><br><input style='border-radius: 7px; border-color: black;' type='text' name='title' required='required' placeholder='Enter subject name' value='".htmlspecialchars($row['gen_ann_name'])."'><br>
									<b>Announcement:</b><br><textarea style='border-radius: 7px; border-color: black' type='text'  name='announcement' required='required' >".htmlspecialchars($row['gen_ann_description'])."</textarea><br>";
								}
						
								//Create a post form for deleting the current selected subject		
								echo "
								<button>Update announcement</button><br>
							</form><form method='POST' action='generalmanager.php'><input style='width:5%;'id='c_announcement' type='text' name='c_announcement' hidden='hidden' value='".htmlspecialchars($_POST["c_announcement"])."'><button name='deleteannouncement'>Delete announcement</button></form>";
							mysqli_close($connection);	
				}			}
				echo " </div>";
				}

			?>
			<!-- php code to update and delete ends above -->
		   
		</div>
</div>
<br>
<div id='selected_data'>
	<h4><b>Announcements</b> | <a href="timetables.php" style="color: blue">Timetables</a></h4>
	<?php

	require_once 'app_config.php';
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	//Query to view all messages
	$sql = "SELECT gen_ann_id, gen_ann_name, gen_ann_description, gen_ann_date_posted FROM general_announcement ORDER BY gen_ann_id DESC";
	$result = mysqli_query($connection, $sql);

	if(mysqli_num_rows($result) > 0){
		echo "<table class='w3-table w3-bordered w3-hoverable' style='width:98%'>
			<thead style='background-color: rgb(74,74,74)'>
			<tr>
				<th style='color:rgb(235,238,196)'>#</th>
				<th style='color:rgb(235,238,196)'>Date Posted</th>
				<th style='color:rgb(235,238,196)'>Title</th>
				<th style='color:rgb(235,238,196)'>Description</th>
				<th style='color:rgb(235,238,196)'>Action</th>
			</tr>
			</thead>
			<tbody>";
			while($row = mysqli_fetch_assoc($result)){
				echo "<tr>
						<td>".$row['gen_ann_id']."</td>
						<td>".$row['gen_ann_date_posted']."</td>
						<td>".$row['gen_ann_name']."</td>
						<td>".$row['gen_ann_description']."</td>
						<td>
							<form method='POST' action='generalmanager.php'>
								<input type='text' name='c_announcement' value='".$row['gen_ann_id']."' hidden='hidden'>
								<input type='text' name='c_date' value='".$row['gen_ann_date_posted']."' hidden='hidden'>
								<button style='background-color: rgb(74,74,74); color: white;border-radius:5px;border-color:black;'>edit</button>
							</form>
						</td>
					 </tr>";
			}
			echo "</tbody></table>";
		}
		else{
			echo "There are no posted Announcements.";
		}
		
		mysqli_close($connection);	
	?>
</div>
</div>
</div>
</body>
</html>