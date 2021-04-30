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
	<title>update timetable</title>
</head>
<body>
<?php

require 'phpgoodies.php';
$file_key = 'data'; 
$cantcatchme = 0;
$errors = "";

if(isset($_POST['c_id'])){

	$id = $_POST['c_id'];
	if(is_numeric($id)){
		$cantcatchme += 1;
	}
	else{
		$errors .= "Id needs to be a number";
	}
}

if(isset($_POST['title'])){

	$tit = $_POST['title'];
    if(!preg_match('/^[a-zA-Z0-9 .,:()]+$/', $tit)) {
      $errors .= "Title should contain letters, numbers and symbols :,.()<br>";
    }
    else{
    	$cantcatchme += 1;
    }
}

if(isset($_POST['category'])){
    $type = $_POST['category'];
    if($type == 'sports' || $type == 'academic' || $type == 'event' || $type == 'school'){
        $cantcatchme += 1;
    }
    else{
        $error .= "Invalid gallery category selected";
    }
}

if(isset($_POST['description'])){
    $des = $_POST['description'];
    if(!empty($des)){
        $cantcatchme += 1;
    }
    else{
        $error .= "Description cannot be emppty";
    }
}

if($cantcatchme == 4){
    //Update the uploaded file title when no file has beed uploaded
	if ($_FILES['data']['size'] == 0){
    	
	    require_once "app_config.php";
		$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

		$stmt = $connection->prepare("UPDATE gallery SET gal_title = ? , gal_des = ?, gal_category = ? WHERE gal_id = ? ");
		$stmt->bind_param("sssi",$tit, $des, $type, $id);
		$stmt->execute();
		$stmt->close();

		mysqli_close($connection);
		echo "<script> document.location = 'gallerymanager.php';</script>";
    }
    //Update if a file has been uploaded 
	else{

        $file = $_FILES['data'];

       require_once "app_config.php";
       $connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

       $old_file = '';

       $sql = "SELECT gal_path FROM gallery WHERE gal_id = '$id'";
       $result = mysqli_query($connection, $sql);
       if(mysqli_num_rows($result) > 0){
           while($row = mysqli_fetch_assoc($result)){
               if($row['gal_path'] == $_POST['c_name']){
                   $old_file = $row['gal_path'];
               }
           }
       }

       $allowedFileTypes  =  array("image/gif",  "image/jpeg",  "image/pjpeg", "text/plain", "application/pdf");
       if  (!in_array($_FILES[$file_key]['type'],  $allowedFileTypes)) {
           echo "<a href='timetables.php' style='color:blue'>Go back </a>";
           die("ERROR:  File  type  not  permitted.");
       }

       $data_storage_path = './publicfiles/';
       $original_filename = $file['name'];
       $file_basename     = substr($original_filename, 0, strripos($original_filename, '.')); // strip extention
       $file_ext          = substr($original_filename, strripos($original_filename, '.'));
       $stored_filename   = date('Ymd') . '_' . md5($original_filename . microtime());
       $stored_filename  .= $file_ext;                        
       if(! move_uploaded_file($file['tmp_name'], $data_storage_path.$stored_filename)){
            // unable to move,  check error_log for details
            echo "<script>alert('Sorry something went wrong.');</script>";
       }

       $path = $data_storage_path  .$stored_filename;

       $stmt = $connection->prepare("UPDATE gallery SET gal_title = ?, gal_path = ? WHERE gal_id = ? ");
       $stmt->bind_param("ssi",$tit, $path, $id);
       $stmt->execute();
       $stmt->close();
       unlink($old_file);

       mysqli_close($connection);
       echo "<script> alert('Sucessfully updated');document.location = 'gallerymanager.php';</script>";


   }

}

else{
   echo $errors;
}

?>
</body>
</html>