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
	<title>Submit assignment</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="javascriptgoodies.js"></script>
</head>
<body style="background-color: rgb(249,247,243)">
<div class="w3-sidebar w3-bar-block" style="width: 20%; background-color: rgb(74,74,74);">
	<a href='assignment.php'><i class="fa fa-arrow-circle-left" style="font-size:48px;color:yellow"></i></a><a href="student.php"><i class="fa fa-home" style="font-size:48px;color:yellow;margin-left: 5%"></i></a>
	<br>
	<?php
	if(isset($_SESSION['subject_name']) && isset($_SESSION['subject_code'])){
		echo "<h4 style='color:white;margin-left:20px'><u>".htmlspecialchars($_SESSION['subject_name'])." ( ".htmlspecialchars($_SESSION['subject_code'])." </u>)</h4><br>";

		//Student navigation menu
		require 'studentmenu.php';
	}
	else{
		echo "<script> document.location = 'student.php';</script>";
	}
	?>
</div>
<div style="margin-left: 25%">
<?php
$cantcatchme = 0;

if(isset($_SESSION['id']) && isset($_SESSION['assignment_id'])){

	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
	$id = $_SESSION['id'];
	$a_id = $_SESSION['assignment_id'];
	$sql = "SELECT ass_sub_id, ass_sub_path, ass_sub_date, ass_sub_grade, feedback, ass_sub_status FROM assignment_submission WHERE stu_id = '$id' AND ass_id = '$a_id'";
	$result = mysqli_query($connection, $sql);
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){	
			$_SESSION['asd'] = $row['ass_sub_date'];
			$_SESSION['asp'] = $row['ass_sub_path'];
			$_SESSION['asg'] = $row['ass_sub_grade'];
			$_SESSION['feed'] = $row['feedback'];
			$_SESSION['stat'] = $row['ass_sub_status'];
		}
		$cantcatchme = 1;
		$sql2 = "SELECT marks FROM assignment WHERE ass_id = '$a_id'";
		$result2 = mysqli_query($connection, $sql2);
		if(mysqli_num_rows($result2) > 0){
			while ($row2 = mysqli_fetch_assoc($result2)) {
				$_SESSION['marks'] = $row2['marks'];
			}
		}
	}
	else{
		$cantcatchme = 0;

	}
	mysqli_close($connection);

}
else{
	echo "<script> document.location = 'student.php'</script>";
}

if($cantcatchme == 0){
		$file_key = 'data';

		if(array_key_exists($file_key, $_FILES)){
		    $file = $_FILES[$file_key];
		    if($file['size'] > 0){
		    	//Check if the file is allowed
		    	$allowedFileTypes  =  array("image/png",  "image/jpeg",  "image/pjpeg", "application/pdf");
				if  (!in_array($_FILES[$file_key]['type'],  $allowedFileTypes)) {
					echo "<a href='submit.php?assessment=".$a_id."' style='color:blue'>Go back </a>";
					die("ERROR:  File  type  not  permitted.");
				}
				$posted_by = $_SESSION['id'];
				$comment = $_POST['comment'];
		        $data_storage_path = '../submission/';
		        $original_filename = $file['name'];
		        $file_basename     = substr($original_filename, 0, strripos($original_filename, '.')); // strip extention
		        $file_ext          = substr($original_filename, strripos($original_filename, '.'));
		        $stored_filename   = date('Ymd') . '_' . md5($original_filename . microtime() . $posted_by);
		        $reference = $stored_filename;
		        $stored_filename  .= $file_ext;                        
		        if(! move_uploaded_file($file['tmp_name'], $data_storage_path.$stored_filename)){
		             // unable to move,  check error_log for details
		             echo "<script>alert('Sorry something went wrong.');</script>";
		        }
		    
				require_once "app_config.php";
				$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

				$current_assignment = $_SESSION['assignment_id'];
				$path = $data_storage_path  .$stored_filename;

				if (!($stmt = $connection->prepare("INSERT INTO assignment_submission (ass_id, ass_sub_date, ass_sub_path, stu_id, comment) VALUES(?, ?, ?, ?, ?)"))) {
					echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;}

					$stmt->bind_param("issis",$a, $b, $c, $d, $e); 
					$a = $current_assignment;
					$b = date('Ymd');
					$c = $path;
					$d = $posted_by;
					$e = $comment;
					$stmt->execute();
					$stmt->close();
				mysqli_close($connection);

				echo  "<center><div class='w3-panel w3-green' style='width:90%'><h6>Successfully  uploaded  assignment. Your reference number is <b>".$reference."</b></h6><div></center>";

				require_once "app_config.php";
				$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");
				
				$id = $_SESSION['id'];
				$a_id = $_SESSION['assignment_id'];
				$sql = "SELECT ass_sub_id, ass_sub_path, ass_sub_date, ass_sub_grade, feedback, ass_sub_status FROM assignment_submission WHERE stu_id = '$id' AND ass_id = '$a_id'";
				$result = mysqli_query($connection, $sql);
				if(mysqli_num_rows($result) > 0){
					while($row = mysqli_fetch_assoc($result)){	
						$_SESSION['asd'] = $row['ass_sub_date'];
						$_SESSION['asp'] = $row['ass_sub_path'];
						$_SESSION['asg'] = $row['ass_sub_grade'];
						$_SESSION['feed'] = $row['feedback'];
						$_SESSION['stat'] = $row['ass_sub_status'];
					}
					$cantcatchme = 1;
				$sql2 = "SELECT marks FROM assignment WHERE ass_id = '$a_id'";
				$result2 = mysqli_query($connection, $sql2);
				if(mysqli_num_rows($result2) > 0){
					while ($row2 = mysqli_fetch_assoc($result2)) {
						$_SESSION['marks'] = $row2['marks'];
					}
				}
				}
				mysqli_close($connection);



			}
		}
	}

if($cantcatchme == 1){
	$mark_stat = "";
	$status = "";
	$subD = strtotime($_SESSION['asd']);
	$dueD = strtotime($_SESSION['assignments_duedate']);
	if($dueD > $subD){
		$status .= "";
	}
	else{
		$status .= "(LATE)";
	}

	$mark = "";
	if($_SESSION['asg'] > 0){
		$mark .= $_SESSION['asg'];
		$mark_stat = "Marked";
	}
	if($_SESSION['asg'] == 0 && $_SESSION['stat'] == 1){
		$mark .= "0";
		$mark_stat = "Marked";
	}
	if($_SESSION['asg'] == 0 && $_SESSION['stat'] == 0){
		$mark .= "-";
		$mark_stat = "Pending";
	}
	echo "
		<aside style='float: right;width: 30%; background-color: rgb(249,247,243); margin-right:5px'>
			<div style='background-color: rgb(74,74,74);'>
				<br>
				<span style='color: white'>Assignment Details</span>
				<br>
				<br>
			</div>
			<div style='background-color: lightgrey'>
				<br>
				<br>
				<b>Name</b>
				<br>
				".htmlspecialchars($_SESSION['assignments_name'])."
				<hr>
				<b>Due Date</b>
				<br>
				".htmlspecialchars($_SESSION['assignments_duedate'])."
				<hr>
				<br>
				<br>
			</div> 
			<div style='background-color: rgb(74,74,74);'>
			<br>
			<span style='color: white'>".$mark_stat."</span><br>
			<div class='w3-left'>
				<span style='color: white'>Mark</span>
			</div>
			<div class='w3-right'>
				<span style='color: white'>".$mark."/".$_SESSION['marks']."</span>
			</div>
			<br>
			<span style='color: white'>Attempt: ".$_SESSION['asd']." <b style='color:red;'>".$status."</b></span>
			<br>
			</div>
			<div style='background-color: lightgrey'>
				<br>
				Submissions<br>
				<br>
				<a href='".$_SESSION['asp']."' style='color:blue' target='_blank'>View Submitted File</a>
				<br>
				".$_SESSION['feed']."
				<br>
				<a style='float: right; background-color: black;color: white' href='assignment.php'>Ok</a>
				<br>
				<br>
			</div>
			<br>
			<br>
		</aside>

		<section >
			<h3 style='margin-left: 5%'>Review Submission History: ".htmlspecialchars(ucfirst($_SESSION['assignments_name']))."</h3>
			<br>
			<br> 
			<iframe src='".$_SESSION['asp']."' width='700' height='500'></iframe>
		</section>";
	}

	else{
		echo "<script> document.location = 'student.php'; </script>";
	}
?>

</div>
</body>
</html>