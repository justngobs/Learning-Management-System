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
	<title>Manage content</title>
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="javascriptFormsValidations.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="javascriptgoodies.js"></script>
</head>
<body style="background-color: rgb(249,247,243)">
<div class="w3-sidebar w3-bar-block" style="width: 20%; background-color: rgb(74,74,74);">
	<a href='teachsubject.php?subject_id=<?php echo $_SESSION['current_subject']?>'><i class="fa fa-arrow-circle-left" style="font-size:48px;color:yellow"></i></a><a href="teacher.php"><i class="fa fa-home" style="font-size:48px;color:yellow;margin-left: 5%"></i></a>
	<br>
<?php
echo "<h4 style='color:white;margin-left:20px'><u>".htmlspecialchars(ucfirst($_SESSION['subject_name']))." ( ".htmlspecialchars($_SESSION['subject_code'])." </u>)</h4><br>";

//Teacher navigation menu
require 'teachermenu.php';
?>
</div>
<div style="margin-left: 25%">
	<div id = "content">
		<h4><b>Class List</b></h4>
		<?php

			require_once 'app_config.php';
			$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

			$subject_s = $_SESSION['current_subject'];

			$sql = "SELECT seed.stu_id, seed.stu_name, seed.stu_surname, seed.stu_gender, seed.stu_dob, enrol.stu_id, enrol.sub_id FROM seed, enrol WHERE seed.stu_id = enrol.stu_id AND enrol.sub_id = '$subject_s' ORDER BY seed.stu_surname ASC";

			$result = mysqli_query($connection, $sql);

			$counter = 0;
			if(mysqli_num_rows($result) > 0){
				echo "<table class='w3-table w3-striped w3-bordered w3-hoverable' style='background-color: rgb(74,74,74); width:98%'>
				<thead>
				<tr>
					<th style='color:rgb(235,238,196)'>#</th>
					<th style='color:rgb(235,238,196)'>Student Number</th>
					<th style='color:rgb(235,238,196)'>Name</th>
					<th style='color:rgb(235,238,196)'>Surname</th>
					<th style='color:rgb(235,238,196)'>Birth Date</th>
					<th style='color:rgb(235,238,196)'>Gender</th>
					<th style='color:rgb(235,238,196)'></th>
				</tr>
				</thead>
				<tbody style='background-color:white'>";
				while($row = mysqli_fetch_assoc($result)){

					if($row['stu_gender'] == "M"){
						echo "<tr>
								<td>".++$counter."</td>
								<td>".$row['stu_id']."</td>
								<td>".$row['stu_name']."</td>
								<td>".$row['stu_surname']."</td>
								<td>".$row['stu_dob']."</td>

								<td>Male</td>
								<td>
									<form method='post' action='view_students.php'>
										<input type='text' name='current_student_id' value='".$row['stu_id']."' hidden='hidden'>
										<button>View</button>
									</form>
								</td>
							</tr>";
					}
					if($row['stu_gender'] == "F"){
						echo "<tr>
								<td>".++$counter."</td>
								<td>".$row['stu_id']."</td>
								<td>".$row['stu_name']."</td>
								<td>".$row['stu_surname']."</td>
								<td>".$row['stu_dob']."</td>

								<td>Female</td>
								<td>
									<form method='post' action='view_students.php'>
										<input type='text' name='current_student_id' value='".$row['stu_id']."' hidden='hidden'>
										<button>View</button>
									</form>
								</td>
							</tr>";
					}

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