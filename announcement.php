<?php
session_start();

if(!isset($_SESSION['loggedin'])){
	header('Location: login.php');
	exit();
}

$cantcatchme = 0;

if($_SESSION['ulevel'] == 1){
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
	<title>Announcements</title>
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="javascriptgoodies.js"></script>
</head>
<body style="background-color: rgb(249,247,243)">
	<?php

	if(isset($_SESSION['subject_name']) && isset($_SESSION['subject_code']) && isset($_SESSION['current_subject'])){
		echo "<div class='w3-sidebar w3-bar-block' style='width: 20%; background-color: rgb(74,74,74);'>
			<a href='subject.php?subject_id=".$_SESSION['current_subject']."'><i class='fa fa-arrow-circle-left' style='font-size:48px;color:yellow'></i></a><a href='student.php'><i class='fa fa-home' style='font-size:48px;color:yellow;margin-left: 5%'></i></a>
			<br>";
			
		echo "<h4 style='color:white;margin-left:20px'><u>".htmlspecialchars($_SESSION['subject_name'])." ( ".htmlspecialchars($_SESSION['subject_code'])." </u>)</h4><br>";

		// Student navigation menu
		require 'studentmenu.php';
	}
	else{
		echo "<script>document.location = 'student.php';</script>";
	}
	?>
</div>
<div style="margin-left: 25%">
	<div id = "announcements">
		<h4><b>Announcements</b></h4>
		<div style="background-color: white; width:95%">
			<?php

			if(isset($_SESSION['current_subject'])){
				require_once 'app_config.php';
				$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
				//Query to view all messages
				$c_subject = $_SESSION['current_subject'];
				$sql = "SELECT ann_id, ann_name, ann_description, date_posted, tea_id FROM announcement WHERE sub_id = '$c_subject' ORDER BY ann_id DESC LIMIT 10";
				$result = mysqli_query($connection, $sql);

				if(mysqli_num_rows($result) > 0){
				while($row = mysqli_fetch_assoc($result)){
					echo "<div style='margin-left:3%'>
						    <h6><b>".htmlspecialchars($row['ann_name'])."</b></h6>
						    <h6>Posted On: ".htmlspecialchars($row['date_posted'])."by ".htmlspecialchars($row['tea_id'])."(inner join on T_id)</h6>
						    <br>
						    <p>".htmlspecialchars($row['ann_description'])."</p>
						    <br>  
						  </div>
						  <hr>";
					}
				}
				else{
					echo "<br><p style='margin-left:3%'>No announcements yet. Posted announcements will appear here.</p><br>";
				}
				mysqli_close($connection);
			}
			else{
				echo "<script>document.location = 'student.php';</script>";
			}
			?>
		</div>
	</div>
</div>
</body>
</html>