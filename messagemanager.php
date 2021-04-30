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
	<title>Message inbox</title>
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
		<a href="subjectmanager.php" style="color: white"><i class="fa fa-book" style="font-size: 30px;color: lightblue;"></i> Subjects</a><br><br><br>
		<a href="#" style="color: white"><i class="fa fa-envelope" style="font-size: 30px;color: lightblue;"></i> Messages  </a><br><br><br>
		<a href="reports.php" style="color: white"><i class="fa fa-pie-chart" style="font-size: 30px;color: lightblue;"></i>         Reports</a><br><br><br><br>
		<a href="logout.php" style="color: white"><i class="fa fa-sign-out" style="font-size:30px;color:orange"></i> logout</a>
	</strong>
</div>
<div id = "selection" style="margin-left: 25%">
	<h6><b>Message manager</b></h6>
	<div id = "content" style="background-color: rgb(255,255,255);width: 95%">
		<div class="w3-accordion">
		<button id = "btnText" onclick="ourFunction('forms')" class="w3-button w3-border w3-border-cyan w3-left">Hide</button><br><br>
		<!-- Form to add a new student to the system-->
		<div id="forms" class="w3-accordion-content w3-container" style="margin-right: 50%">
		<!-- Form to send a message, reply and forward-->
		<div class="w3-container w3-cell" style="border-style: solid;border-left-color: black;"><br>
			<span style="background-color: black; color: white">Send a message </span>
			<br>
				<form method="POST" action="sendmail.php">
					To:<br><input style="border-radius: 7px; border-color: pink;" type="email" name="email" required="required" placeholder="user@example.com"><br>
					Subject:<br><input style="border-radius: 7px; border-color: pink;" type="text" name="subject" required="required" placeholder="Enter subject"><br>
					Message:<br>
					<textarea style="border-radius: 7px; border-color: pink;" type="text" name="message" required="required" placeholder="type message..."></textarea><br><br>
					<button>send message</button>
				</form>
				<br>
		</div>
		<!-- place to view messages -->
			<?php
				//Code to delete the current selected student from all the tables
				 if (isset($_POST['delete']) && isset($_POST["c_message"]))  
				 	{    

						require_once 'app_config.php';
						$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

				 		$id   = $_POST['c_message'];    
				 		$query  = "DELETE FROM visitormessages WHERE message_id='$id'";    
				 		$result = $connection->query($query);    
				 		if (!$result){ 
				 			echo "DELETE failed: $query<br>" . $connection->error . "<br><br>";  
				 		}
						 mysqli_close($connection);
						 echo "<script> document.location = 'messagemanager.php'; </script>";

				 	}

				?>

			<?php 
			if(isset($_POST['c_message'])){
				echo "<div class='w3-container w3-cell' style='border-style: solid;border-left-color: black;'><br>";

				require_once 'app_config.php';
				$connection = mysqli_connect($host,$user,$password,$database) or die ("<p>Error connecting to database: ".mysqli_error(). "</p>");

				$id = $_POST['c_message'];
				$query = "UPDATE visitormessages SET message_status = '2' WHERE message_id = '$id'";
				if (mysqli_query($connection, $query)) {
					
				} 
				echo "<b>From: </b>".htmlspecialchars($_POST['name']." ".$_POST['surname']);
				echo "<br><b>Date: </b>".htmlspecialchars($_POST['rdate']);
				echo "<br><b>Message: </b><br><hr>".htmlspecialchars($_POST['message']);
				echo "<hr>";
				echo "<form method='POST' action='messagemanager.php'>
						<input type='text' value='".$_POST['c_message']."' hidden='hidden' name='c_message'>
						<button  name='delete'>Delete</button>
					</form><br>	</div>";
				
				mysqli_close($connection);
			}
			?>
</div>
</div>
<br>
<h4><b>Message inbox</b></h4>
<?php

require_once 'app_config.php';
$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

//Query to view all messages
$sql = "SELECT message_id, sender_name, sender_surname, sender_message, date_received, message_status FROM visitormessages ORDER BY message_id DESC";
$result = mysqli_query($connection, $sql);

//This code will get messages from the database, a selected message will be appended to an edit form using 
// post method 
if(mysqli_num_rows($result) > 0){
	echo "<table class='w3-table w3-bordered w3-hoverable' style='width:95%'>
			<thead style='background-color: rgb(74,74,74); color:white'>
			<tr>
				<th>Received on</th>
				<th>From</th>
				<th>Message</th>
				<th>Action</th>
			</tr>
			</thead>
			<tbody>";
		while($row = mysqli_fetch_assoc($result)){
			echo "";

			// Printing messages that have been read
			if($row['message_status'] == 2) 
				{
					echo "<tr>
							<td>".htmlspecialchars($row["date_received"])."</td>
							<td>".htmlspecialchars($row["sender_name"])." ".htmlspecialchars($row["sender_surname"])."</td>
							<td>".substr(htmlspecialchars($row["sender_message"]), 0, -5)."...</td>

							<td>
								<form method='POST' action='messagemanager.php'>
								<input style='width:5%;' id='c_message' type='text' name='c_message' hidden='hidden' value='".$row["message_id"]."'>
								<input style='width:5%;' id='message' type='text' name='message' hidden='hidden' value='".$row["sender_message"]."'>
								<input style='width:5%;' id='name' type='text' name='name' hidden='hidden' value='".$row["sender_name"]."'>
								<input style='width:5%;' id='surname' type='text' name='surname' hidden='hidden' value='".$row["sender_surname"]."'>
								<input style='width:5%;' id='rdate' type='text' name='rdate' hidden='hidden' value='".$row["date_received"]."'>
								<button>view</button>
								</form>
							</td>
						</tr>";

				}
			// Printing messages that have not been read in bold text
			if($row['message_status'] == 1)
			{
				echo "<tr>
						<td><b>".htmlspecialchars($row["date_received"])."</b></td>
						<td><b>".htmlspecialchars($row["sender_name"])." ".htmlspecialchars($row["sender_surname"])."</b></td>
						<td><b>".substr(htmlspecialchars($row["sender_message"]), 0, -5)."...</b></td>

						<td>
							<form method='POST' action='messagemanager.php'>
							<input style='width:5%;' id='c_message' type='text' name='c_message' hidden='hidden' value='".$row["message_id"]."'>
							<input style='width:5%;' id='message' type='text' name='message' hidden='hidden' value='".$row["sender_message"]."'>
							<input style='width:5%;' id='name' type='text' name='name' hidden='hidden' value='".$row["sender_name"]."'>
							<input style='width:5%;' id='surname' type='text' name='surname' hidden='hidden' value='".$row["sender_surname"]."'>
							<input style='width:5%;' id='rdate' type='text' name='rdate' hidden='hidden' value='".$row["date_received"]."'>
							<button>view</button>
							</form>
						</td>
					</tr>";
			}
		}
		echo "</tbody></table>";
	}
	else{
		echo "There are no messages yet.";
	}
	
mysqli_close($connection);
?>
</div>
</div>
</body>
</html>