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
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/navigation.css">

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
<hr>
<h2><b>Gallery Manager</b> | <a href="newsmanager.php" style="color: blue">News and Events Manager</a></h2>
<div class="row">
  <div class="col">
  <!-- Division for posting to gallery -->
    <hr>
    <h3>Post to gallery</h3>
    <form enctype="multipart/form-data" action="gallerymanager.php" method="post">
        Title: 
        <input type='text' name='title' placeholder='enter title' required='required' style="border-radius: 7px; border-color: lightblue;"><br>
        Description: 
        <br><textarea name="description"></textarea><br>
        Category: 
        <select style="border-radius: 7px; border-color: lightblue;" type="text" name="category"> 
            <option value="none" selected="selected" disabled="disabled">Select a category</option>
            <option value="sports">Sport</option>
            <option value="academic">Academic</option>
            <option value="school">School</option>
            <option value="events">Event</option>
        </select><br><br>
        <input type="hidden" name="MAX_FILE_SIZE" value="8000000" /> <span style="color: red">Select file</span>:
        <input type="file" name="data" /> <br> allowed file types (Png, Jpg, Jpeg)<br><br>
        <input type="submit" name="submit" value="Post Picture">
    </form>
    <?php
    if(isset($_POST['title']) && isset($_POST['description']) && isset($_POST['category'])){
      $file_key = "data";
      if(array_key_exists($file_key, $_FILES)){
          $file = $_FILES[$file_key];
          if($file['size'] > 0){
            //Check if the file is allowed
            $allowedFileTypes  =  array("image/png",  "image/jpeg",  "image/pjpeg");
          if  (!in_array($_FILES[$file_key]['type'],  $allowedFileTypes)) {
            echo "<a href='gallerymanager.php' style='color:blue'>Go back </a>";
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
          require_once "app_config.php";
          $connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

          $title = $_POST['title'];
          $des = $_POST['description'];
          $type = $_POST['category'];
          $today = date("ymd");
          $path = $data_storage_path  .$stored_filename;

          if (!($stmt = $connection->prepare("INSERT INTO gallery (gal_title, gal_des, gal_date, gal_category, gal_path) VALUES(?, ?, ?, ?, ?)"))) {
            echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;}

            $stmt->bind_param("sssss",$a, $b, $c, $d, $e); 
            $a = $title;
            $b = $des;
            $c = $today;
            $d = $type;
            $e = $path;
            $stmt->execute();
            $stmt->close();
          mysqli_close($connection);
          echo "<script>document.location = 'gallerymanager.php';</script>";
          }
          else{
            echo "<div style='background-color:red;color:white'>Upload a valid file</div>";
          }
      }
    }

    ?>
    <hr>
  </div>

  <div class="col">
  <!-- Division for posting Editing -->

  <?php
            //Code to delete stuff securely from the content Folder.
            if(isset($_POST['c_gallerydelete'])){

                $id = $_POST['c_gallerydelete'];
                $checker = 0;

                if(is_numeric($id)){

                    require_once 'app_config.php';
                    $connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");


                    $sql = "SELECT gal_id, gal_path FROM gallery WHERE gal_id = '$id'";
                    $result = mysqli_query($connection, $sql);
                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            if($row['gal_path'] == $_POST['c_name']){
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

                    $sql_del = "DELETE FROM gallery WHERE gal_id = '$id'";
                    $query = mysqli_query($connection, $sql_del);
                    if($query){
                        unlink($_POST['c_name']);
                    }
                    mysqli_close($connection);
                }
                echo " <script> document.location = 'gallerymanager.php'; </script>";

            }
        	?>
  <?php
  if(isset($_POST['editgallery'])){

    echo "
    <h3>Edit gallery item</h3>
    <form enctype='multipart/form-data' action='editgallery.php' method='post'>
      <input type='text' name='c_id' value='".$_POST['c_gallery']."' hidden='hidden'>
      <input type='text' name='c_name' value='".$_POST['c_name']."' hidden='hidden'>
      Title: 
      <input type='text' name='title' placeholder='enter title' required='required' style='border-radius: 7px; border-color: lightblue;' value='".$_POST['c_title']."'><br>
      Description: 
      <br><textarea name='description'>".$_POST['c_des']."</textarea><br>
      Category: 
      <select style='border-radius: 7px; border-color: lightblue;' type='text' name='category'> 
          <option value='none' disabled='disabled'>Select a category</option>";

          if($_POST['c_category'] == 'sports'){
            echo "<option value='sports' selected='selected'>Sport</option>
                  <option value='academic'>Academic</option>
                  <option value='school'>School</option>
                  <option value='events'>Event</option>";
          }
          if($_POST['c_category'] == 'academic'){
            echo "<option value='sports'>Sport</option>
                  <option value='academic' selected='selected'>Academic</option>
                  <option value='school'>School</option>
                  <option value='events'>Event</option>";
          }
          if($_POST['c_category'] == 'school'){
            echo "<option value='sports'>Sport</option>
                  <option value='academic'>Academic</option>
                  <option value='school' selected='selected'>School</option>
                  <option value='events'>Event</option>";
          }
          if($_POST['c_category'] == 'events'){
            echo "<option value='sports'>Sport</option>
                  <option value='academic'>Academic</option>
                  <option value='school'>School</option>
                  <option value='events' selected='selected'>Event</option>";
          }
      echo "
      </select><br><br>
      <input type='hidden' name='MAX_FILE_SIZE' value='8000000' /> <span style='color: red'>Select file</span>:
      <input type='file' name='data' /><br><br>
      <input type='submit' name='submit' value='Edit Picture'>
    </form>";
  }

  ?>

  </div>
</div>

<br>
<h2>Posted gallery items</h2>
<?php
  
  require_once 'app_config.php';
	$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

	//Query to view all messages
	$sql = "SELECT * FROM gallery ORDER BY gal_id DESC";
	$result = mysqli_query($connection, $sql);

	if(mysqli_num_rows($result) > 0){
		echo "<div class='table-responsive'><table class='table' style='width:98%'>
			<thead style='background-color: rgb(74,74,74)'>
			<tr>
				<th style='color:rgb(235,238,196)'>#</th>
				<th style='color:rgb(235,238,196)'>Title</th>
				<th style='color:rgb(235,238,196)'>Description</th>
				<th style='color:rgb(235,238,196)'>Category</th>
        <th style='color:rgb(235,238,196)'>Date Posted</th>
        <th style='color:rgb(235,238,196)'>Image</th>
        <th style='color:rgb(235,238,196)'></th>
        <th style='color:rgb(235,238,196)'></th>

			</tr>
			</thead>
            <tbody>";
            $counter = 1;
			while($row = mysqli_fetch_assoc($result)){
        echo "<tr>
                <td>".($counter)."</td>
                <td>".$row['gal_title']."</td>
                <td>".$row['gal_des']."</td>
                <td>".$row['gal_category']."</td>
                <td>".$row['gal_date']."</td>
                <td><a href='".$row['gal_path']."' target='_blank' style='color:blue'>view image</a></td>
                <td>
                  <form method='POST' action='gallerymanager.php'>
                    <input type='text' name='c_gallery' value='".$row['gal_id']."' hidden='hidden'>
                    <input type='text' name='c_title' value='".$row['gal_title']."' hidden='hidden'>
                    <input type='text' name='c_des' value='".$row['gal_des']."' hidden='hidden'>
                    <input type='text' name='c_category' value='".$row['gal_category']."' hidden='hidden'>
                    <input type='text' name='c_name' value='".$row['gal_path']."' hidden='hidden'>
                    <button style='background-color: rgb(74,74,74); color: white;border-radius:5px;border-color:black;' name='editgallery'>edit</button>
                  </form>
                </td>
                <td>
                  <form method='POST' action='gallerymanager.php'>
                    <input type='text' name='c_name' value='".$row['gal_path']."' hidden='hidden'>
                    <input type='text' name='c_gallerydelete' value='".$row['gal_id']."' hidden='hidden'>
                    <button style='background-color: rgb(74,74,74); color: white;border-radius:5px;border-color:black;'>delete</button>
                  </form>
               </td>
              </tr>";
           $counter += 1;
      }
      echo "</tbody></table></div>";
    }
    else{
      echo "There are no posted gallery images yet.";
    }
    
    mysqli_close($connection);	
  ?>	

</div>
</body>
</html>