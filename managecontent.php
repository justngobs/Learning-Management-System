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
	<link rel="stylesheet" type="text/css" href="tooltip.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="javascriptFormsValidations.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="javascriptgoodies.js"></script>
</head>
<body style="background-color: rgb(249,247,243)">
<div class="w3-sidebar w3-bar-block" style="width: 20%; background-color: rgb(74,74,74);">
	
<?php
if(isset($_SESSION['current_subject']) && isset($_SESSION['subject_name']) && isset($_SESSION['subject_code'])){

	echo "<a href='teachsubject.php?subject_id=".$_SESSION['current_subject']."'><i class='fa fa-arrow-circle-left' style='font-size:48px;color:yellow'></i></a><a href='teacher.php'><i class='fa fa-home' style='font-size:48px;color:yellow;margin-left: 5%'></i></a>
	<br>";
	echo "<h4 style='color:white;margin-left:20px'><u>".htmlspecialchars(ucfirst($_SESSION['subject_name']))." ( ".htmlspecialchars($_SESSION['subject_code'])." </u>)</h4><br>";
	
	//The teacher navigation bar
	require 'teachermenu.php';
}
else{
	echo "<script> document.location = 'teacher.php'; </script>";
}
?>
</div>
<div style="margin-left: 25%">
	<div id = "content">
	<h4><b>Subject Notes</b></h4>
	<div class="w3-container w3-cell" style="border-style: solid;border-left-color: black;">
		<form enctype="multipart/form-data" action="managecontent.php" method="post">

			<!-- documentation -->
			<div class="tooltip" style="margin-left: 98%"><i class="fa fa-question-circle" style="color: blue;font-size: 30px" aria-hidden="true"></i>
			  <span class="tooltiptext">Required fields are marked with an asteric(*). Post content for the students that take this subject. You can only post pdf documents or images as they are viewable on most devices. <a href='https://smallpdf.com/pdf-converter' >Click here</a> to convert your files into pdf before uploading.</span>
			</div>
			<!-- End documentation -->

			<br>Title: <input style="border-radius: 7px; border-color: lightblue;" type="text" name="title" required="required" placeholder="Enter content title"> <span style="color: red"> * </span>
			<hr>
			<input type="hidden" name="MAX_FILE_SIZE" value="8000000" /> Select file:
			<input type="file" name="data" /><span style="color: red"> * </span><br>
			<hr>
			<i>allowed filetypes (Pdf, txt, jpeg, png, pjpeg)</i><br>
			<input type="submit" name="submit" value="Upload  File" /><br><br>
		</form>
	
	<?php
	require 'phpgoodies.php';
	$file_key = 'data'; 
	$errors = "";
	$cantcatchme = 0;

	if(isset($_POST['submit']) && !empty($_POST['title'])){

		$tit = $_POST['title'];
	    if(!preg_match('/^[a-zA-Z0-9 .,:()]+$/', $tit)) {
	      $errors .= "Title should contain letters, numbers and symbols :,.()<br>";
	    }
	    else{
	    	$cantcatchme += 1;
	    }
	}
	if($cantcatchme == 1){
		//  check  uploaded  file  size
	    if(array_key_exists($file_key, $_FILES)){
	        $file = $_FILES[$file_key];
	        if($file['size'] > 0){
	        	//Check if the file is allowed
	        	$allowedFileTypes  =  array("image/png",  "image/jpeg",  "image/pjpeg", "text/plain", "application/pdf");
				if  (!in_array($_FILES[$file_key]['type'],  $allowedFileTypes)) {
					echo "<a href='managecontent.php' style='color:blue'>Go back </a>";
					die("ERROR:  File  type  not  permitted.");
				}
	            $data_storage_path = '../content/';
	            $original_filename = $file['name'];
	            $file_basename     = substr($original_filename, 0, strripos($original_filename, '.')); // strip extention
	            $file_ext          = substr($original_filename, strripos($original_filename, '.'));
	            $stored_filename   = date('Ymd') . '_' . md5($original_filename . microtime());
	            $stored_filename  .= $file_ext;                        
	            if(! move_uploaded_file($file['tmp_name'], $data_storage_path.$stored_filename)){
	                 // unable to move,  check error_log for details
	                 echo "<script>alert('Sorry something went wrong.');</script>";
	            }
	            echo  "<h6 style='background-color:green; width:60'>File (<b style='color:white'>".htmlspecialchars($_POST['title'])."</b>)  successfully  uploaded  to  contents folder.</h6>";


				require_once "app_config.php";
				$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

				$current_subject = $_SESSION['current_subject'];
				$posted_by = $_SESSION['id'];
				$title = $_POST['title'];
				$path = $data_storage_path  .$stored_filename;

				if (!($stmt = $connection->prepare("INSERT INTO content (sub_id, tea_id, con_name, con_path) VALUES(?, ?, ?, ?)"))) {
					echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;}

					$stmt->bind_param("ssss",$a, $b, $c, $d); 
					$a = $current_subject;
					$b = $posted_by;
					$c = $tit;
					$d = $path;
					$stmt->execute();
					$stmt->close();
				mysqli_close($connection);
	        }
	        else{
	        	echo "<div style='background-color:red;color:white'>Upload a valid file</div>";
	        }
	    }
    }
    else{
    	echo "<div style='background-color:red;color:white'>".$errors."</div>";
    }    
	?>
	</div>

<?php
	//Code to delete stuff securely from the content Folder.
	if(isset($_POST['c_id']) && isset($_POST['c_delete'])){

		$id = $_POST['c_id'];
		$checker = 0;

		if(is_numeric($id)){

			require_once 'app_config.php';
			$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");


			$sql = "SELECT con_id, con_path FROM content WHERE con_id = '$id'";
			$result = mysqli_query($connection, $sql);
			if(mysqli_num_rows($result) > 0){
				while($row = mysqli_fetch_assoc($result)){
					if($row['con_path'] == $_POST['c_name']){
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

			$sql_del = "DELETE FROM content WHERE con_id = '$id'";
			$query = mysqli_query($connection, $sql_del);
			if($query){
				unlink($_POST['c_name']);
			}
			mysqli_close($connection);
		}


	}
?>
	<?php 
	//Code to update stuff
	if(isset($_POST['c_content'])){
		echo "<div class='w3-container w3-cell' style='border-style: solid;border-right-color: black;'>";

		require_once 'app_config.php';
		$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

		if(is_numeric($_POST['c_content'])){
			$id = $_POST['c_content'];
			$sql = "SELECT con_id, con_name, con_path FROM content WHERE con_id = '$id'";
			$result = mysqli_query($connection, $sql);
			if(mysqli_num_rows($result) > 0){
				while($row = mysqli_fetch_assoc($result)){
					echo "

					<form enctype='multipart/form-data' action='updatecontent.php' method='post'>
						<br>Title:<input style='border-radius: 7px; border-color: pink;' type='text' name='title' required='required' placeholder='Enter content title' value='".$row['con_name']."'>
						<hr>
						<input type='hidden' name='MAX_FILE_SIZE' value='8000000' /> Select file:
						<input type='file' name='data'/><br>
						<input type='text'  value='".$row['con_id']."' hidden='hidden' name='c_id'>
						<input type='text' name='content_name' value='".$row['con_path']."' hidden='hidden'>
						<hr>
						<i>allowed filetypes ( Pdf, txt, jpeg, png, pjpeg )</i><br>
						<input type='submit' name='submit' value='Update  File' />
					</form>

					<form action='managecontent.php' method='post'>
						<input type='text' value='".$row['con_path']."' hidden='hidden' name='c_name'>
						<input type='text'  value='".$row['con_id']."' hidden='hidden' name='c_id'>
						<input type='submit' value='Delete' name='c_delete'>
					</form>

					";
				}
			}
		}
		echo "</div>";
		mysqli_close($connection);
	}
	?>


<h4><b>Posted Notes</b></h4>

<?php

	require_once 'app_config.php';
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	//Query to view all students
	$sub_id = $_SESSION['current_subject'];
	$sql = "SELECT con_id, sub_id, tea_id, con_name, con_path, date_posted FROM content WHERE sub_id = '$sub_id' ORDER BY con_id DESC LIMIT 10";
	$result = mysqli_query($connection, $sql);

	if(mysqli_num_rows($result) > 0){
		echo "<table class='w3-table w3-striped w3-bordered w3-hoverable' style='width:98%'>
		<thead style='background-color: rgb(74,74,74);'>
		<tr>
			<th style='color:rgb(235,238,196)'>#</th>
			<th style='color:rgb(235,238,196)'>Posted by</th>
			<th style='color:rgb(235,238,196)'>Title</th>
			<th style='color:rgb(235,238,196)'>Date Posted</th>
			<th style='color:rgb(235,238,196)'></th>
		</tr>
		</thead>
		<tbody>";
		while($row = mysqli_fetch_assoc($result)){
			$download = "<a href='".$row['con_path']."' style='color:blue' target='_blank'>".htmlspecialchars(ucfirst($row['con_name']))."</a>";
			echo "
			<tr>
				<td>".htmlspecialchars($row['con_id'])."</td>
				<td>".htmlspecialchars($row['tea_id'])."</td>
				<td>".$download."</td>
				<td>".htmlspecialchars($row['date_posted'])."</td>
				<td>
					<form action='managecontent.php' method='post'>
					<input type='text' name='c_content' hidden='hidden' value='".$row['con_id']."'>
					<button style='background-color: rgb(74,74,74); color: white;border-radius:5px;border-color:orange;'>Edit</button>
					</form>
				</td>
			<tr>
			";
		}
		echo "</tbody>
			</table>";
	}
	else{
		echo "No content yet.";
	}
	mysqli_close($connection);

?>

</div>
</div>
</body>
</html>