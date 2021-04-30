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
	<title></title>
</head>
<body>
<?php
$cantcatchme = 0;

if(isset($_POST['update_mark_id']) && isset($_POST['mark']) && isset($_POST['feedback']) && isset($_POST['assi_id']) && isset($_SESSION['total_mark'])){
	$id   = $_POST['update_mark_id'];
	$g = $_POST['mark'];
	$f = $_POST['feedback'];
	$aid = $_POST['assi_id'];

	if(is_numeric($id) && is_numeric($g) & is_numeric($aid) && ($g <= $_SESSION['total_mark']) && ($g >= 0)){
		$cantcatchme += 1;
	}
	else{
		$cantcatchme -= 1;
	}

	if(!empty($f)){
		if(!preg_match('/^[a-zA-Z0-9 .,:()]+$/', $f)) {
	      $cantcatchme -= 1;
	      
	      echo "<form method='post' action='gradeassignment.php' id='gras'><input type='text' hidden='hidden' name='c_assignment' value='".$aid."'><input type='submit' hidden='hidden'></form>";
		  echo "<script>document.forms['gras'].submit();</script>";
	    }
	    else{
	    	$cantcatchme += 1;
	    }
	}
	else{
		$cantcatchme += 1;
	}

}
if($cantcatchme == 2){
	
	require_once "app_config.php";
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");


	$query = "UPDATE assignment_submission SET ass_sub_grade = '$g', ass_sub_status = '1', feedback = '$f' WHERE ass_sub_id = '$id'";
	if (mysqli_query($connection, $query)) {
	    echo "<form method='post' action='gradeassignment.php' id='gra'><input type='text' hidden='hidden' name='c_assignment' value='".$aid."'><input type='submit' hidden='hidden'></form>";
		echo "<script>document.forms['gra'].submit();</script>";
	} 
	else {
	    echo "Error updating record: " . mysqli_error($connection);
	}

	mysqli_close($connection);
}

else{
	echo "<script> document.location = 'gradeassignment.php';</script>";
}

?>
</body>
</html>