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
<body>
<?php
$file_key = "data";
if(array_key_exists($file_key, $_FILES)){
    $file = $_FILES[$file_key];
    if($file['size'] > 0){
    	//Check if the file is allowed
    	$allowedFileTypes  =  array("image/png",  "image/jpeg",  "image/pjpeg", "text/plain", "application/pdf");
		if  (!in_array($_FILES[$file_key]['type'],  $allowedFileTypes)) {
			echo "<a href='timetables.php' style='color:blue'>Go back </a>";
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
		require_once "app_config.php";
		$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

		$title = $_POST['title'];
		$type = $_POST['type'];
		$grade = $_POST['grade'];
		$path = $data_storage_path  .$stored_filename;

		if (!($stmt = $connection->prepare("INSERT INTO timetable (time_title, time_type, time_grade, time_path) VALUES(?, ?, ?, ?)"))) {
			echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;}

			$stmt->bind_param("ssis",$a, $b, $c, $d); 
			$a = $title;
			$b = $type;
			$c = $grade;
			$d = $path;
			$stmt->execute();
			$stmt->close();
		mysqli_close($connection);
		echo "<script>document.location = 'timetables.php';</script>";
    }
    else{
    	echo "<div style='background-color:red;color:white'>Upload a valid file</div>";
    }
}


?>
</body>
</html>