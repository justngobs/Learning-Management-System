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
	<title>subject manager</title>
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
		<a href="studentmanager.php" style="color: white"><i class="fa fa-user" style="font-size: 30px;color: lightblue;"></i> Students</a><br><br><br>
		<a href="teachermanager.php" style="color: white"><i class="fa fa-user" style="font-size: 30px;color: lightblue;"></i> Teachers</a><br><br><br>
		<a href="#" style="color: white"><i class="fa fa-book" style="font-size: 30px;color: lightblue;"></i> Subjects</a><br><br><br>
		<a href="messagemanager.php" style="color: white"><i class="fa fa-envelope" style="font-size: 30px;color: lightblue;"></i> Messages  </a><br><br><br>
		<a href="reports.php" style="color: white"><i class="fa fa-pie-chart" style="font-size: 30px;color: lightblue;"></i>         Reports</a><br><br><br><br>
		<a href="logout.php" style="color: white"><i class="fa fa-sign-out" style="font-size:30px;color:orange"></i> logout</a>
	</strong>
	<!-- https://www.youtube.com/watch?v=dNVZ0ZPfE8s -->
</div>
<div id = "selection" style="margin-left: 25%">
	<h6><b>Subject manager</b></h6>
	<div id ="content" style="background-color: rgb(255,255,255);width: 95%">
		<div class="w3-accordion">
		<button id = "btnText" onclick="ourFunction('forms')" class="w3-button w3-border w3-border-cyan w3-left">Hide</button><br><br>
		<!-- Form to add a new teacher to the system-->
		<div id="forms" class="w3-accordion-content w3-container" style="margin-right: 50%">
			<div class="w3-container w3-cell" style="border-style: solid;border-left-color: black;">
				<span style="background-color: black;color: white">Enter a new subject below</span>
				<br>
				<form method="POST" action="addsub.php">
					Subject name<br>
					<input style="border-radius: 7px; border-color: pink;" type="text" name="name" placeholder="Enter subject name" required="required"><br><br>
					Subject grade<br>
					<input style="border-radius: 7px; border-color: pink;width: 50%" type="number" max="12" min="8" name="grade" placeholder="Grade" required="required"><br><br>
					<button>Add subject</button>
				</form>
				<br>
			</div>
			<?php
				//Code to delete the current selected subject from all the tables
				 if (isset($_POST['deletesub']) && isset($_POST["c_subject"]))  
				 	{    
						require_once 'app_config.php';
						$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

				 		$id   = $_POST['c_subject'];    
				 		$query  = "DELETE FROM subjectss WHERE sub_id='$id'";    
				 		$result = $connection->query($query);    
				 		if (!$result){ 
				 			echo "DELETE failed: $query<br>" . $connection->error . "<br><br>";  
				 		}

				 		$query  = "DELETE FROM class WHERE sub_id='$id'";    
				 		$result = $connection->query($query);    
				 		if (!$result){ 
				 			echo "DELETE failed: $query<br>" . $connection->error . "<br><br>";  
				 		}

				 		$query  = "DELETE FROM enrol WHERE sub_id='$id'";    
				 		$result = $connection->query($query);    
				 		if (!$result){ 
				 			echo "DELETE failed: $query<br>" . $connection->error . "<br><br>";  
				 		}

						 mysqli_close($connection);
						 echo "<script> document.location = 'subjectmanager.php'; </script>";

				 	}

				?>
				<?php 
				//Code for updating subject information
					if(isset($_POST['c_subject'])){
						echo "<div class='w3-container w3-cell' style='border-style: solid;border-right-color: white;'>";

						require_once 'app_config.php';
						$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

						echo "<span style='color:white;background-color:black'>selected subject id</span> : ".htmlspecialchars($_POST['c_subject'])."<br>";
						//This represent a potential sql injection. Fix it
						$sql = "SELECT sub_name, sub_code,sub_grade FROM subjectss WHERE sub_id = '".$_POST['c_subject']."'";
						$result = mysqli_query($connection, $sql);

						//Create a dynamic form and fill it with database values here
						if(mysqli_num_rows($result) > 0){
							while($row = mysqli_fetch_assoc($result)){
								echo "<form method='post' action='updatesub.php'>
								<input type='text' name='c_subject' value = '".htmlspecialchars($_POST['c_subject'])."' hidden='hidden'>				 
								<b>Subject name:</b><br><input style='border-radius: 7px; border-color: black;' type='text' name='name' required='required' placeholder='Enter subject name' value='".$row['sub_name']."'><br><br>
								<b>Subject grade:</b><br><input style='border-radius: 7px; border-color: black;' type='number' name='grade' required='required' placeholder='Grade' min='8' max='12' value='".$row['sub_grade']."'><br>";
							}
					
						//Create a post form for deleting the current selected subject		
							echo "<br>
							<button>Update subject info</button><br>
							</form><form method='POST' action='subjectmanager.php'><input style='width:5%;' id='c_subject' type='text' name='c_subject' hidden='hidden' value='".$_POST["c_subject"]."'><button name='deletesub'>Delete this subject</button></form>";
							mysqli_close($connection);	
						}
						echo "</div>";			
					}

				?>
			<?php 
				// Code for showing additional subject information
				if(isset($_POST['c_subject'])){
					echo "<div class='w3-container w3-cell' style='border-style: solid;border-left-color: white;'>";
					echo "<b><u>Subject teachers</u></b><br>";

					require_once 'app_config.php';
					$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

					$subject_s = $_POST['c_subject'];
					//Code to view teachers for a particular subject
					$sql = "SELECT farmer.tea_id, farmer.tea_name, farmer.tea_surname, class.tea_id, class.sub_id FROM farmer, class WHERE farmer.tea_id = class.tea_id AND class.sub_id = '$subject_s' ORDER BY farmer.tea_id";
					$result = mysqli_query($connection, $sql);
					if(mysqli_num_rows($result) > 0){
						echo "<ul>";
						while($row = mysqli_fetch_assoc($result)){
							echo "<li>".$row['tea_name']." ".$row['tea_surname']."</li>";
						}
						echo "</ul>";
					}
					else{
						echo "<br>No teachers yet..<br><br>";
					}

					echo "<br><b><u>Subject class list</u></b><br>";

					//Code to view class list for a given subject
					$sql = "SELECT seed.stu_id, seed.stu_name, seed.stu_surname, enrol.stu_id, enrol.sub_id FROM seed, enrol WHERE seed.stu_id = enrol.stu_id AND enrol.sub_id = '$subject_s' ORDER BY seed.stu_id LIMIT 3";
					$result = mysqli_query($connection, $sql);
					if(mysqli_num_rows($result) > 0){
						echo "<ul>";
						while($row = mysqli_fetch_assoc($result)){
							echo "<li>".$row['stu_name']." ".$row['stu_surname']."</li>";
						}
						echo "</ul>";
						echo "<a href='#'>view full class list</a>";
					}
					else{
							echo "<br>No students yet..<br>";
					}
					mysqli_close($connection);
				}
				echo "</div>";
			?>

	</div>
</div>
<h4><b>Subject information</b></h4>
<?php 

require_once 'app_config.php';
$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

$sql = "SELECT sub_id, sub_name, sub_code, sub_grade FROM subjectss ORDER BY sub_grade ASC";
$result = mysqli_query($connection, $sql);

//Fetch all subjects and append to a table for editing later
if(mysqli_num_rows($result) > 0){
	echo "<table class='w3-table w3-bordered w3-hoverable' style='width:95%'>
	<thead style='background-color: rgb(74,74,74);color:white'>
	<tr>
		<th>#</th>
		<th>Subject Code</th>
		<th>Subject Name</th>
		<th>Subject Grade</th>
		<th>Action</th>
	</tr>
	</thead>
	<tbody>";

	$counter = 1;
	while($row = mysqli_fetch_assoc($result)){
		echo "
		<tr>
			<td>".$counter."</td>
			<td>".htmlspecialchars($row["sub_code"])."</td>
			<td>".htmlspecialchars($row["sub_name"])."</td>
			<td>".htmlspecialchars($row["sub_grade"])."</td>
			<td>
				<form method='POST' action='subjectmanager.php'>
					<input style='width:5%;' id='c_subject' type='text' name='c_subject' hidden='hidden' value='".$row["sub_id"]."'>
					<button style='background-color: rgb(74,74,74); color: white;border-radius:5px;border-color:black;'>select</button>
				</form>
			</td>
		</tr>";
		$counter += 1;
	}
	echo "</tbody></table>";
}
			
mysqli_close($connection);	
?>
</div>
</div>
</body>
</html>