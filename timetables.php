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
            //Code to delete stuff securely from the content Folder.
            if(isset($_POST['c_timetable']) && isset($_POST['c_delete'])){

                $id = $_POST['c_timetable'];
                $checker = 0;

                if(is_numeric($id)){

                    require_once 'app_config.php';
                    $connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");


                    $sql = "SELECT time_id, time_path FROM timetable WHERE time_id = '$id'";
                    $result = mysqli_query($connection, $sql);
                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            if($row['time_path'] == $_POST['c_name']){
                                $checker = 1;
                            }
                            else{
                                echo "<script>alert('Values are not equal.')</script>";
                            }
                        }
                    }
                    mysqli_close($connection);

                }

                if($checker == 1){

                    require_once 'app_config.php';
                    $connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

                    $sql_del = "DELETE FROM timetable WHERE time_id = '$id'";
                    $query = mysqli_query($connection, $sql_del);
                    if($query){
                        unlink($_POST['c_name']);
                    }
                    mysqli_close($connection);
                }
                echo " <script> document.location = 'timetables.php'; </script>";

            }
        	?>
			<?php 
				if(isset($_POST['c_timetable'])){
					echo "<div class='w3-container w3-cell' style='border-style: solid;border-left-color: black;'><span style='background-color: black; color: white'>Edit announcement </span><br>";
					if(is_numeric($_POST['c_timetable'])){

							require_once 'app_config.php';
							$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

							//This represent a potential sql injection. Fix it
							$sql = "SELECT time_id, time_title, time_type, time_grade, time_path FROM timetable WHERE time_id = '".$_POST['c_timetable']."'";
							$result = mysqli_query($connection, $sql);

							//Create a dynamic form and fill it with database values here
							if(mysqli_num_rows($result) > 0){
								while($row = mysqli_fetch_assoc($result)){
                                    echo "
                                    <span style='background-color: black; color: white'>Post timetable </span>
                                    <br><br>
									<form enctype='multipart/form-data' action='timetableedit.php' method='post'>
										<input type='text' value='".$row['time_path']."' hidden='hidden' name='c_name'>
										<input type='text' name='c_id' hidden='hidden' value='".$row['time_id']."'>
                                        Title: 
                                        <input type='text' name='title' placeholder='enter title' required='required' style='border-radius: 7px; border-color: lightblue;' value='".$row['time_title']."'>
                                        <br>

                                        Type:";

                                        if($row['time_type'] == 'class'){
                                            echo "
                                            <select style='border-radius: 7px; border-color: lightblue;' type='text' name='type'>
                                                <option value='class' selected='selected'>Class Timetable</option>
                                                <option value='exam'>Exam Timetable</option>
                                            </select><br>";
                                        }
                                        if($row['time_type'] == 'exam'){
                                            echo "
                                            <select style='border-radius: 7px; border-color: lightblue;' type='text' name='type'>
                                                <option value='class'>Class Timetable</option>
                                                <option value='exam' selected='selected'>Exam Timetable</option>
                                            </select><br>";  
										}
										
										if($row['time_grade'] == 8 ){
											echo "
											Grade: 
											<select style='border-radius: 7px; border-color: lightblue;' type='text' name='grade'
												<option value='8' selected='selected'>8</option>
												<option value='9'>9</option>
												<option value='10'>10</option>
												<option value='11'>11</option>
												<option value='12'>12</option>
											</select><br><br>";
										}

										if($row['time_grade'] == 9 ){
											echo "
											Grade: 
											<select style='border-radius: 7px; border-color: lightblue;' type='text' name='grade'
												<option value='8'>8</option>
												<option value='9' selected='selected'>9</option>
												<option value='10'>10</option>
												<option value='11'>11</option>
												<option value='12'>12</option>
											</select><br><br>";
										}

										if($row['time_grade'] == 10 ){
											echo "
											Grade: 
											<select style='border-radius: 7px; border-color: lightblue;' type='text' name='grade'
												<option value='8'>8</option>
												<option value='9'>9</option>
												<option value='10' selected='selected'>10</option>
												<option value='11'>11</option>
												<option value='12'>12</option>
											</select><br><br>";
										}

										if($row['time_grade'] == 11 ){
											echo "
											Grade: 
											<select style='border-radius: 7px; border-color: lightblue;' type='text' name='grade'
												<option value='8'>8</option>
												<option value='9'>9</option>
												<option value='10'>10</option>
												<option value='11' selected='selected'>11</option>
												<option value='12'>12</option>
											</select><br><br>";
										}

										if($row['time_grade'] == 12 ){
											echo "
											Grade: 
											<select style='border-radius: 7px; border-color: lightblue;' type='text' name='grade'
												<option value='8'>8</option>
												<option value='9'>9</option>
												<option value='10'>10</option>
												<option value='11'>11</option>
												<option value='12' selected='selected'>12</option>
											</select><br><br>";
										}

										
                                    
                                   
									
									echo "
                                        <input type='hidden' name='MAX_FILE_SIZE' value='8000000' /> <span style='color: red'>Select file</span>:
                                        <input type='file' name='data' /><br>
                                        <input type='submit' name='submit' value='Update Timetable'>
                                    </form>

                                    </form>
                                    <form action='timetables.php' method='post'>
                                        <input type='text' value='".$row['time_path']."' hidden='hidden' name='c_name'>
                                        <input type='text'  value='".$row['time_id']."' hidden='hidden' name='c_timetable'>
                                        <input type='submit' value='Delete' name='c_delete'>
                                    </form>";
                                }
						

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
	<h4><b>Timetables</b> | <a href="generalmanager.php" style="color: blue">Announcements</a></h4>
	<?php

	require_once 'app_config.php';
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	//Query to view all messages
	$sql = "SELECT time_id, time_title, time_type, time_grade, time_path FROM timetable ORDER BY time_id DESC";
	$result = mysqli_query($connection, $sql);

	if(mysqli_num_rows($result) > 0){
		echo "<table class='w3-table w3-bordered w3-hoverable' style='width:98%'>
			<thead style='background-color: rgb(74,74,74)'>
			<tr>
				<th style='color:rgb(235,238,196)'>#</th>
				<th style='color:rgb(235,238,196)'>Title</th>
				<th style='color:rgb(235,238,196)'>Type</th>
				<th style='color:rgb(235,238,196)'>Grade</th>
                <th style='color:rgb(235,238,196)'>File</th>
                <th style='color:rgb(235,238,196)'></th>
			</tr>
			</thead>
            <tbody>";
            $counter = 1;
			while($row = mysqli_fetch_assoc($result)){
                echo "<tr>
                        <td>".($counter)."</td>
						<td>".$row['time_title']."</td>
						<td>".$row['time_type']."</td>
						<td>".$row['time_grade']."</td>
						<td><a href='".$row['time_path']."' target='_blank' style='color:blue'>view_".$row['time_title']."</a></td>
						<td>
							<form method='POST' action='timetables.php'>
								<input type='text' name='c_timetable' value='".$row['time_id']."' hidden='hidden'>
                                <input type='text' name='c_title' value='".$row['time_title']."' hidden='hidden'>
                                <input type='text' name='c_type' value='".$row['time_type']."' hidden='hidden'>
                                <input type='text' name='c_grade' value='".$row['time_grade']."' hidden='hidden'>
								<button style='background-color: rgb(74,74,74); color: white;border-radius:5px;border-color:black;'>edit</button>
							</form>
						</td>
                     </tr>";
                $counter += 1;
			}
			echo "</tbody></table>";
		}
		else{
			echo "There are no posted Timetables.";
		}
		
		mysqli_close($connection);	
	?>
</div>
</div>
</div>
</body>
</html>