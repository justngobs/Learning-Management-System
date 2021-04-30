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
	<title>teacher manager</title>
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
		<a href="generalmanager.php" style="color: white"><i class="fa fa-bank" style="font-size: 30px;color: lightblue;"></i> General</a><br><br><br>
		<a href="studentmanager.php" style="color: white"><i class="fa fa-graduation-cap" style="font-size: 30px;color: lightblue;"></i> Students</a><br><br><br>
		<a href="#" style="color: white"><i class="fa fa-user" style="font-size: 30px;color: lightblue;"></i> Teachers</a><br><br><br>
		<a href="subjectmanager.php" style="color: white"><i class="fa fa-book" style="font-size: 30px;color: lightblue;"></i> Subjects</a><br><br><br>
		<a href="messagemanager.php" style="color: white"><i class="fa fa-envelope" style="font-size: 30px;color: lightblue;"></i> Messages  </a><br><br><br>
		<a href="reports.php" style="color: white"><i class="fa fa-pie-chart" style="font-size: 30px;color: lightblue;"></i>         Reports</a><br><br><br><br>
		<a href="logout.php" style="color: white"><i class="fa fa-sign-out" style="font-size:30px;color:orange"></i> logout</a>
	</strong>
</div>
<div id = "selection" style="margin-left: 25%">
	<h6><b>Teacher manager</b></h6>
	<div id ="content" style="background-color: rgb(255,255,255);width: 95%">
		<div class="w3-accordion">
		<button id = "btnText" onclick="ourFunction('forms')" class="w3-button w3-border w3-border-cyan w3-left">Hide</button><br><br>
		<!-- Form to add a new teacher to the system-->
		<div id="forms" class="w3-accordion-content w3-container" style="margin-right: 50%">
			<div class="w3-container w3-cell" style="border-style: solid;border-left-color: black;">
				<span style="background-color: black;color: white">Enter a new teacher below</span>
				<br>
				<form method="post" action="adtea.php">
					ID number:<br><input id = "idN" style="border-radius: 7px; border-color: pink;" type="text" name="idNumber" required="required" placeholder="Enter teacher ID number">  <br>
					Name:<br><input style="border-radius: 7px; border-color: pink;" type="text" name="name" required="required" placeholder="Enter teacher name">  <br> 
					Surname:<br><input style="border-radius: 7px; border-color: pink;" type="text" name="surname" required="required" placeholder="Enter teacher surname">  <br>
					Email:<br><input style="border-radius: 7px; border-color: pink;" type="text" name="email" required="required" placeholder="user@example.com">  <br> 
					Date of Birth:<br><input style="border-radius: 7px; border-color: pink;" type="date" name="birth" required="required">   <br><br>
					Gender:<select name="gender" style="border-radius: 7px; border-color: pink;"><option value="M">Male</option><option value="F">Female</option></select><br>
					<!-- This code is suppossed to fetch all the classes and returns them for selection .-->
					<br>
					<button>Add teacher</button>
				</form>
			</div>
			<div class="w3-container w3-cell">
				<?php
				//Code to delete the current selected teacher from all the tables
				 if (isset($_POST['deletetea']) && isset($_POST["c_teacher"]))  
				 	{    

						require_once 'app_config.php';
						$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

				 		$id   = $_POST['c_teacher'];    
				 		//Delete a teacher from the teacher table
				 		$query1  = "DELETE FROM farmer WHERE tea_id='$id'";    
				 		$result1 = $connection->query($query1);    
				 		if (!$result1){ 
				 			echo "DELETE failed: $query<br>" . $connection->error . "<br><br>";  
				 		}
				 		//Delete teacher from member table
				 		$query2  = "DELETE FROM member WHERE member_id='$id'";    
				 		$result2 = $connection->query($query2);    
				 		if (!$result2){ 
				 			echo "DELETE failed: $query<br>" . $connection->error . "<br><br>";  
				 		}
				 		//Delete teacher from class table
				 		$query3  = "DELETE FROM class WHERE tea_id='$id'";    
				 		$result3 = $connection->query($query3);    
				 		if (!$result3){ 
				 			echo "DELETE failed: $query<br>" . $connection->error . "<br><br>";  
				 		}
				 		mysqli_close($connection);
				 		echo "<script>document.location = 'teachermanager.php';</script>";
				 	}

				?>
				<?php 
				//Code for updating teacher information
					if(isset($_POST['c_teacher'])){

						require_once 'app_config.php';
						$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

						echo "<strong style='color:white;background-color:black'>Selected teacher id</strong> : ".htmlspecialchars($_POST['c_teacher']);
				//This also represent a sql injection point. fix it too
						$sql = "SELECT tea_name, tea_surname,tea_email, tea_dob, tea_gender, tea_id_num FROM farmer WHERE tea_id = '".$_POST['c_teacher']."'";
						$result = mysqli_query($connection, $sql);
						//Create a dynamic form and fill it with database values here
						if(mysqli_num_rows($result) > 0){
							while($row = mysqli_fetch_assoc($result)){
								
								echo "<form method='post' action='updatetea.php'>
								<input style='border-radius: 7px; border-color: black;' type='text' name='c_teacher' value = '".htmlspecialchars($_POST['c_teacher'])."' hidden='hidden'><b>E-mail:</b><br><input style='border-radius: 7px; border-color: black;' type='text' name='email' required='required' value='".htmlspecialchars($row['tea_email'])."' disabled='disabled'><br>			 
								<b>ID num:</b><br><input style='border-radius: 7px; border-color: black;' type='text' name='idNum' required='required' value='".htmlspecialchars($row['tea_id_num'])."'><br><b>Name:</b><input style='border-radius: 7px; border-color: black;' type='text' name='name' required='required' placeholder='Enter teacher name' value='".htmlspecialchars($row['tea_name'])."'><br> 
								<b>Surname:</b><br><input style='border-radius: 7px; border-color: black;' type='text' name='surname' required='required' placeholder='Enter teacher surname' value='".htmlspecialchars($row['tea_surname'])."'><br>
								<b>Date of birth:</b><br><input style='border-radius: 7px; border-color: black;' type='date' name='birth' required='required' value='".htmlspecialchars($row['tea_dob'])."'><br><br><b>Gender:</b><select name='gender' style='border-radius: 7px; border-color: black;'>";

							if($row['tea_gender'] == 'M'){
								echo("<option value='M' selected>Male</option><option value='F'>Female</option>");
							}

							else{
								echo("<option value='M'>Male</option><option value='F' selected>Female</option>");
							}

							echo ";</select><br>";}}
						//Create a post form for deleting the current selected student		
						echo "<br>
						<button>Update teacher info</button></form><form method='POST' action='teachermanager.php'><input style='width:5%;'id='c_teacher' type='text' name='c_teacher' hidden='hidden' value='".htmlspecialchars($_POST["c_teacher"])."'><button name='deletetea'>Delete this teacher</button></form>";
					mysqli_close($connection);	
					}

				?>
			</div>

		<div class="w3-container w3-cell">
				<?php
				//Code to delete the current selected teacher from all the tables
				 if (isset($_POST["c_teacher"])){ 
					 
				 	require_once 'app_config.php';
					$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

					if(is_numeric($_POST['c_teacher'])){
					 	$teacher_s = $_POST['c_teacher'];
					 	$sql1 = "SELECT tea_gender, tea_name, tea_surname FROM farmer WHERE tea_id = '$teacher_s'";
					 	$result1 = mysqli_query($connection, $sql1);
					 	if(mysqli_num_rows($result1) > 0){
							while($row = mysqli_fetch_assoc($result1)){
								if($row['tea_gender'] == "M"){
									echo "<strong>Full name: </strong>Mr ".htmlspecialchars($row['tea_name'])." ".htmlspecialchars($row['tea_surname']);
								}
								else{
									echo "<strong>Full name: </strong>Miss ".htmlspecialchars($row['tea_name'])." ".htmlspecialchars($row['tea_surname']);
								}
						 	}
						 }
						 //put a user picture here bro
						 echo "<br><i class='fa fa-user' style='font-size:96px;color:black'></i>";
						 echo "<br><br><strong><u>Teacher subjects: </u></strong><br>";
						 $sql = "SELECT subjectss.sub_id, subjectss.sub_name , subjectss.sub_grade, class.tea_id, class.sub_id FROM subjectss, class WHERE subjectss.sub_id = class.sub_id AND class.tea_id = '$teacher_s' ORDER BY subjectss.sub_name";
						$result = mysqli_query($connection, $sql);
						echo "<ul>";
						if(mysqli_num_rows($result) > 0){

							while($row = mysqli_fetch_assoc($result)){
								echo "<li>".$row['sub_name']."_Grade_".$row['sub_grade']."</li>";
							}
						}
						else{
								echo "<li>Not enrolled yet</li>";
						}
						echo "</ul>";	
						 mysqli_close($connection);	
						  echo "<form method='POST' action='updateteaclass.php'><input style='width:5%;'id='c_teacher' type='number' name='c_teacher' hidden='hidden' value='".$_POST["c_teacher"]."'><button>Update teacher subjects</button></form>";	
						}
						else{
							echo "<script> document.location = 'teachermanager.php';</script>";
						}	
																									
				 }
				 ?>
			</div>
		</div>
	</div>
<h3><b>Teacher information</b></h3>
<?php

	require_once 'app_config.php';
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	//Query to view all students
	$sql = "SELECT tea_id, tea_name, tea_surname, tea_email FROM farmer ORDER BY tea_id DESC";
	$result = mysqli_query($connection, $sql);
	//This code will get teachers from the database, a selected teacher will be appended to an edit form using 
	// post method
	if(mysqli_num_rows($result) > 0){
		echo "<table class='w3-table w3-bordered w3-hoverable' style='width:95%'>
		<thead  style='background-color:rgb(74,74,74);color:white'>
		<tr>
			<th>#</th>
			<th>Name</th>
			<th>Surname</th>
			<th>E-mail</th>
			<th>Action</th>
		</tr>
		</thead>
		<tbody>";
		$counter = 1;
		while($row = mysqli_fetch_assoc($result)){
			echo "
			<tr>
				<td>".$counter."</td>
				<td>".htmlspecialchars($row["tea_name"])."</td>
				<td>".htmlspecialchars($row["tea_surname"])."</td>
				<td>".htmlspecialchars($row["tea_email"])."</td>
				<td>
					<form method='POST' action='teachermanager.php'>
						<input style='width:5%;' id='c_teacher' type='text' name='c_teacher' hidden='hidden' value='".$row["tea_id"]."'>
						<button style='background-color: rgb(74,74,74); color: white;border-radius:5px;border-color:black;'>select</button>
					</form>
				</td>
			</tr>";
			$counter += 1;
		}
	echo "</tbody></table>";
	}
	else{
		echo "No students yet.";
	}
	mysqli_close($connection);	
?>
</div>
</div>
</body>
</html>