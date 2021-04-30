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
	<title>student manager</title>
	<meta http-equiv="X-UA-Compatible" content="IE=7, IE=8, IE=9, IE=edge"/>
    <meta http-equiv="Pragma" content="no-cache">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="javascriptgoodies.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="background-color: rgb(253,253,250);">	
<div class="w3-sidebar w3-bar-block" style="width: 20%; background-color: rgb(74,74,74);margin-left:1%">
	<a class="w3-bar-item w3-button" href="#"><i class="fa fa-user" style="font-size:48px;color:yellow"></i><i style="color: white"><b> Admin</b></i></a>
	<br>
	<strong>
		<a href="generalmanager.php" style="color: white"><i class="fa fa-bank" style="font-size: 30px;color: lightblue;"></i> General</a><br><br><br>
		<a href="#" style="color: white"><i class="fa fa-user" style="font-size: 30px;color: lightblue;"></i> Students</a><br><br><br>
		<a href="teachermanager.php" style="color: white"><i class="fa fa-user" style="font-size: 30px;color: lightblue;"></i> Teachers</a><br><br><br>
		<a href="subjectmanager.php" style="color: white"><i class="fa fa-book" style="font-size: 30px;color: lightblue;"></i> Subjects</a><br><br><br>
		<a href="messagemanager.php" style="color: white"><i class="fa fa-envelope" style="font-size: 30px;color: lightblue;"></i> Messages  </a><br><br><br>
		<a href="reports.php" style="color: white"><i class="fa fa-pie-chart" style="font-size: 30px;color: lightblue;"></i>         Reports</a><br><br><br><br>
		<a href="logout.php" style="color: white"><i class="fa fa-sign-out" style="font-size:30px;color:orange"></i> logout</a>
	</strong>
</div>
<div id = "selection" style="margin-left: 25%">
	<h6><b>Student manager</b></h6>
	<div id ="content" style="background-color: rgb(255,255,255);width: 95%">
		<div class="w3-accordion">
		<button id = "btnText" onclick="ourFunction('forms')" class="w3-button w3-border w3-border-cyan w3-left">Hide</button><br><br>
		<!-- Form to add a new student to the system-->
		<div id="forms" class="w3-accordion-content w3-container" style="margin-right: 50%">
			<div class="w3-container w3-cell" style="border-style: solid;border-left-color: black;">
				<span style="background-color: black;color: white">Enter a new student below</span>
				<br>
				<form method="post" action="adstu.php">
					<b>ID number:</b> <br><input style="border-radius: 7px; border-color: lightblue;" type="text" name="idNumber" required="required" placeholder="Enter ID number"><br> 
					<b>Name:</b><br><input style="border-radius: 7px; border-color: lightblue;" type="text" name="name" required="required" placeholder="Enter student name"><br> 
					<b>Surname:</b><br><input style="border-radius: 7px; border-color: lightblue;" type="text" name="surname" required="required" placeholder="Enter student surname"><br>
					<b>Grade:</b><br><input style="border-radius: 7px; border-color: lightblue;" type="number" name="grade" required="required" placeholder="Grade" min="8" max="12" style="width: 30%"><br>
					<b>E-mail:</b><br><input style="border-radius: 7px; border-color: lightblue;" type="text" name="email" required="required" placeholder="user@example.com"><br> 
					<b>Date of Birth:</b><br><input style="border-radius: 7px; border-color: lightblue;" type="date" name="birth" required="required"><br><br>
					<b>Gender:</b><select style="border-radius: 7px; border-color: lightblue;" type="text" name="gender"><option value="M">Male</option><option value="F">Female</option></select><br>
					<br>
					<button>Add student</button>
				</form>

				<br>
			</div>
					<?php
					//Code to delete the current selected student from all the tables
					 if (isset($_POST['deletestu']) && isset($_POST["c_student"]))  
					 	{    
							require_once 'app_config.php';
							$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

					 		$id   = $_POST['c_student'];  
					 		//Delete student from student table 
					 		if(is_numeric($id)){ 
					 		$query1  = "DELETE FROM seed WHERE stu_id='$id'";    
					 		$result1 = $connection->query($query1);    
					 		if (!$result1){ 
					 			echo "DELETE failed: $query<br>" . $connection->error . "<br><br>";  
					 		}
					 		//Delete student from member table
					 		$query2  = "DELETE FROM member WHERE member_id='$id'";    
					 		$result2 = $connection->query($query2);    
					 		if (!$result2){ 
					 			echo "DELETE failed: $query<br>" . $connection->error . "<br><br>";  
					 		}
					 		//Delete student from enrol table
					 		$query3  = "DELETE FROM enrol WHERE stu_id='$id'";    
					 		$result3 = $connection->query($query3);    
					 		if (!$result3){ 
					 			echo "DELETE failed: $query<br>" . $connection->error . "<br><br>";  
					 		}
					 		mysqli_close($connection);
					 		echo "<script>document.location = 'studentmanager.php'</script>";
					 	}
					 	}

					?>
					<?php 
					//Code for updating student information
						if(isset($_POST['c_student'])){
							echo "<div class='w3-container w3-cell' style='border-style: solid;border-right-color: white;'>";

							require_once 'app_config.php';
							$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

							echo "<span style='color:white;background-color:black'>student number</span>: ".htmlspecialchars($_POST['c_student'])."8888";
					// Potential sql injection attack, fix it 
							if(is_numeric($_POST['c_student'])){
							$sql = "SELECT stu_name, stu_surname,stu_email, stu_grade, stu_dob, stu_gender, stu_id_num FROM seed WHERE stu_id = '".$_POST['c_student']."'";
							$result = mysqli_query($connection, $sql);

							//Create a dynamic form and fill it with database values here
							echo "";
							if(mysqli_num_rows($result) > 0){
								while($row = mysqli_fetch_assoc($result)){
									echo "<form method='post' action='updatestu.php'>
									<input type='text' name='c_student' value = '".htmlspecialchars($_POST['c_student'])."' hidden='hidden'>
									<b style='color:black;'>E-mail:</b><br><input style='border-radius: 7px; border-color: rgb(171,181,181);' type='text' name='email' required='required' value='".htmlspecialchars($row['stu_email'])."' disabled='disabled'><br>
									<b style='color:black;'>ID number:</b><br><input style='border-radius: 7px; border-color: rgb(171,181,181);' type='text' name='stu_idNumber' required='required' value='".htmlspecialchars($row['stu_id_num'])."'><br>			 
									<b style='color:black;'>Name:</b><br><input style='border-radius: 7px; border-color: rgb(171,181,181);' type='text' name='name' required='required' placeholder='Enter student name' value='".htmlspecialchars($row['stu_name'])."'><br> 
									<b style='color:black;'>Surname:</b><br><input style='border-radius: 7px; border-color: rgb(171,181,181);' type='text' name='surname' required='required' placeholder='Enter student surname' value='".htmlspecialchars($row['stu_surname'])."'><br>
									<b style='color:black;'>Grade:</b><br><input style='border-radius: 7px; border-color: rgb(171,181,181);' type='number' name='grade' required='required' placeholder='Grade' min='8' max='12' style='width: 30%' value='".htmlspecialchars($row['stu_grade'])."'><br> 
									<b style='color:black;'>Date of birth:</b><br><input style='border-radius: 7px; border-color: rgb(171,181,181);' type='date' name='birth' required='required' value='".htmlspecialchars($row['stu_dob'])."'><br><br><b style='color:black;'>Gender:</b><select style='border-radius: 7px; border-color: rgb(171,181,181);' name='gender'>";

									if ($row['stu_gender'] == 'M'){
									
									echo "<option  value='M' selected>Male</option>";
									echo "<option value='F'>Female</option>";
								}
									else {
									echo "<option value='F' selected>Female</option>";
									echo "<option  value='M'>Male</option>";
								}


									echo "</select><br>";}}	
							//Create a post form for deleting the current selected student		
							echo "<br>
							<button>Update student info</button>
						</form><form method='POST' action='studentmanager.php'><input style='width:5%;'id='c_student' type='text' name='c_student' hidden='hidden' value='".htmlspecialchars($_POST["c_student"])."'><button name='deletestu'>Delete this student</button></form>";
						mysqli_close($connection);	
							
					}
					echo "</div>";
				}
					?>
					<?php
					//Code to show student enrollments and initiate an update of the enrollments
					 if (isset($_POST["c_student"])){ 
					 	echo "<div class='w3-container w3-cell' style='border-style: solid;border-left-color: white;'>";
					 	
						require_once 'app_config.php';
						$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

					//Another potential sql injection that needs to be fixed
					 	$student_s = $_POST['c_student'];
					 	if(is_numeric($student_s)){
							 	$sql1 = "SELECT stu_gender, stu_name, stu_surname FROM seed WHERE stu_id = '$student_s'";
							 	$result1 = mysqli_query($connection, $sql1);
							 	if(mysqli_num_rows($result1) > 0){
									while($row = mysqli_fetch_assoc($result1)){
										if($row['stu_gender'] == "M"){
											echo "<strong>Full name: </strong>Mr ".$row['stu_name']." ".$row['stu_surname'];
										}
										else{
											echo "<strong>Full name: </strong>Miss ".$row['stu_name']." ".$row['stu_surname'];
										}
								 	}
								 }
								 //put a user picture here bro
								 echo "<br><i class='fa fa-user' style='font-size:96px;color:black'></i>";
								 echo "<br><br><strong><u>Student enrollments: </u></strong><br>";
								 
							//Another potential sql injection that needs to be fixed
								 $sql = "SELECT subjectss.sub_id, subjectss.sub_name , enrol.stu_id, enrol.sub_id FROM subjectss, enrol WHERE subjectss.sub_id = enrol.sub_id AND enrol.stu_id = '$student_s' ORDER BY subjectss.sub_name LIMIT 9";
								$result = mysqli_query($connection, $sql);
								echo "<ul>";
								if(mysqli_num_rows($result) > 0){

									while($row = mysqli_fetch_assoc($result)){
										echo "<li>".ucfirst($row['sub_name'])."</li>";
									}
									echo "</ul>";
								}
								else{
										echo "<li>Not enrolled yet</li>";
								}
								
								echo "";	
								mysqli_close($connection);
								echo "<form method='POST' action='studentmanager.php'><input style='width:5%;'id='c_student' type='text' name='ccc_student' hidden='hidden' value='".htmlspecialchars($_POST["c_student"])."'><button name='updatestudentsubject'>Update student subjects</button></form>";
							}
							echo "</div>";
					 }
					 ?>

					 <?php 
						//Code to update the subjects of the student					 
						if(isset($_POST['ccc_student'])){

							$cantcatchme = 0;//authentication mechanism
							$errors = "";//error handling variable

							//check if name is empty
							if(empty($_POST['ccc_student'])){
								$errors .= "blank input<br>";
							}
							else{
								//sanitize input
								$student = $_POST['ccc_student'];
								//check if name data is expected data
							    if(is_numeric($student)){
							    	$cantcatchme += 1;
							    }
							    else{
							    	$errors .= "bad input<br>";
							    }
							}

							if($cantcatchme == 1){

								require_once "app_config.php";
								$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

								// Get student grade from updsated database records
								$sql0 = "SELECT stu_grade FROM seed WHERE stu_id = '$student'";
								$result0 = mysqli_query($connection, $sql0);
								$grade = 0;
								if(mysqli_num_rows($result0) > 0){
									while($row = mysqli_fetch_assoc($result0)){
										$grade = $row['stu_grade'];
									}
								}

								//Get previous student enrollments if they exist
								$sql_middle = "SELECT sub_id FROM enrol WHERE stu_id = '$student'";
								$result_middle = mysqli_query($connection, $sql_middle);
								$subjects = array();
								$counter = 0;
								if(mysqli_num_rows($result_middle) > 0){
									while($row = mysqli_fetch_assoc($result_middle)){
										$subjects[$counter] = $row['sub_id'];
										$counter += 1;
									}
								}


								// Link a subject with a student
								$sql = "SELECT sub_name, sub_id FROM subjectss WHERE sub_grade = '$grade'";
								$result = mysqli_query($connection, $sql);
								echo"<div class='w3-modal' style='display:block;'>
									<div class='w3-modal-content' style='background-color:rgb(34,34,34)'>
								<div class='w3-container'>
								<b style='color:orange'>select student subjects</b>:<br><br>";
								echo "<div style='text-align:left;'><form method='POST' action=enrol.php>";
								echo "<input type='number' name='s_student' value='".$student."' hidden='hidden'>";
								if(mysqli_num_rows($result) > 0){
									while($row = mysqli_fetch_assoc($result)){

										if(in_array($row['sub_id'], $subjects)){
												echo "<input type='checkbox' name='check_list[]' value='".$row['sub_id']."' checked><span style='color:white;margin-left:2%'>".htmlspecialchars(ucfirst($row['sub_name']))."</span></input><br>";
										}
										else{
											echo "<input type='checkbox' name='check_list[]' value='".$row['sub_id']."' ><span style='color:white;margin-left:2%'>".htmlspecialchars(ucfirst($row['sub_name']))."</span></input><br>";
										}
									}
									echo "<br><input type='submit' value='Update student subjects'></form><br>";

									echo "<form method='post' action='studentmanager.php'><input type='text' hidden='hidden' name='c_student' value='".$student."'><input type='submit' value='Cancel'></form>;
									<br><br></div></div></div></div>";
								}
								else{
									echo "There are no subjects yet available <a href='subjectmanager.php'>Add a new subject here</a>";
								}

								mysqli_close($connection);
							}

							else{
								echo "something went wrong and we belive its either ".$errors;
							}
						}
						?>
			</div>

		</div>
	</div>
	<!-- Display student information -->
<h3><b>student information</b></h3>
<?php
require_once 'app_config.php';
$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
//Query to view all students
$sql = "SELECT stu_id, stu_name, stu_surname, stu_grade FROM seed ORDER BY stu_id DESC";
$result = mysqli_query($connection, $sql);
//This code will get students from the database, a selected student will be appended to an edit form using 
// post method
if(mysqli_num_rows($result) > 0){
	echo "<table class='w3-table w3-bordered w3-hoverable' style='width:95%'>
	<thead style='background-color:rgb(74,74,74);color:white'>
	<tr>
		<th>#</th>
		<th style='color:rgb(235,238,196)'>Name</th>
		<th style='color:rgb(235,238,196)'>Surname</th>
		<th style='color:rgb(235,238,196)'>Grade</th>
		<th style='color:rgb(235,238,196)'>Action</th>
	</tr>
	</thead>
	<tbody>";
	$counter = 1;
	while($row = mysqli_fetch_assoc($result)){
		echo "
		<tr>
			<td>".$counter."</td>
			<td>".htmlspecialchars($row["stu_name"])."</td>
			<td>".htmlspecialchars($row["stu_surname"])."</td>
			<td>".htmlspecialchars($row["stu_grade"])."</td>
			<td>
				<form method='POST' action='studentmanager.php'>
					<input style='width:5%;' id='c_student' type='text' name='c_student' hidden='hidden' value='".$row["stu_id"]."'>
					<button style='background-color: rgb(74,74,74); color: white;border-radius:5px;border-color:black;'>select</button>
				</form>
			</td>
		</tr>";
		$counter += 1;
	}
	echo "</tbody></table>";
}
else{
	echo "There are no students yet. ";
}
mysqli_close($connection);	
?>
</div>
</body>
</html>