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
	<title>teacher account</title>
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="javascriptgoodies.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="background-color: rgb(249,247,243)">
<div class="w3-sidebar w3-bar-block" style="width: 20%; background-color: rgb(74,74,74);">

	<?php
	
	require_once 'app_config.php';
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	$tea_id = $_SESSION['id'];

	$sql = "SELECT tea_name, tea_surname, tea_email FROM farmer WHERE tea_id = '$tea_id'";
	$result = mysqli_query($connection, $sql);

	$name = "";
	$surname = "";

	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
			$name = $row['tea_name'];
			$surname = $row['tea_surname'];
			$email = $row['tea_email'];
		}
	}

	$_SESSION['teacher_name'] = $name;
	$_SESSION['teacher_surname'] = $surname;
	$_SESSION['teacher_email'] = $email;

	echo "<a class='w3-bar-item w3-button' href='teacherprofile.php' ><i class='fa fa-user' style='font-size:48px;color:yellow'></i><b style='color: silver'>".strtoupper($name[0])." ".strtoupper($surname)."</b></a>";

	mysqli_close($connection);
	?>
	<br>
	<strong>
		<?php

		require_once 'app_config.php';
		$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
		
		//select all subjects from the enrol table of the student session variable declared above
		$sql = "SELECT subjectss.sub_id, subjectss.sub_name , subjectss.sub_code, subjectss.sub_grade, class.tea_id, class.sub_id FROM subjectss, class WHERE subjectss.sub_id= class.sub_id AND class.tea_id = '$tea_id' ORDER BY subjectss.sub_name";
		$result = mysqli_query($connection, $sql);
		
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				echo "<form method='get' action='teachsubject.php'>
						<input type='hidden' name='subject_id' value='".$row['sub_id']."'>
						<button style='border:none; background-color: rgb(74,74,74); color:white;'> <i class='fa fa-circle' style='font-size:15px;color:lightblue'></i> ".htmlspecialchars(ucfirst($row['sub_code']))."</button>
					  </form><br>";

			}
			echo "<br><br><a href='logout.php' style='color:lightblue'> Logout</a>";
			
		}
		else{
				echo "Not enrolled yet<br><br><a href='logout.php'> Logout</a>";
		}
		mysqli_close($connection);
		?>
	</strong>
</div>
<div style="margin-left: 25%">
	<div id = "content">
		<br>
		<h4><b>Announcements</b></h4>
		<br>
		<div style="background-color: white; width: 95%">
			<?php

			require_once 'app_config.php';
			$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
			
			//Query to view all messages
			$sql = "SELECT gen_ann_id, gen_ann_name, gen_ann_description, gen_ann_date_posted FROM general_announcement ORDER BY gen_ann_id DESC";
			$result = mysqli_query($connection, $sql);

			if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				echo "<div style='margin-left:3%'>
					    <h6><b>".htmlspecialchars($row['gen_ann_name'])."</b></h6>
					    <h6>Posted On: ".htmlspecialchars($row['gen_ann_date_posted'])."</h6>
					    <br>
					    <p>".htmlspecialchars($row['gen_ann_description'])."</p>
					    <br>  
					  </div>
					  <hr>";
				}
			}
			else{
				echo "<h5>No announcements yet. Posted announcements will appear here.</h5>";
			}
			mysqli_close($connection);
			?>
	</div>
	</div>
</div>
</body>
</html>