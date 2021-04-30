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
	<title>Profile</title>
	<meta http-equiv="X-UA-Compatible" content="IE=7, IE=8, IE=9, IE=edge"/>
    <meta http-equiv="Pragma" content="no-cache">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 
	<link rel="stylesheet" type="text/css" href="w3.css" />
	<script type="text/javascript" src="javascriptgoodies.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
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

	echo "<a href = '#'><i class='fa fa-user' style='font-size:48px;color:yellow'></i><b style='color: silver'>".strtoupper($name[0])." ".strtoupper($surname)."</b></a>";

	mysqli_close($connection);
	?>
	<br>
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
		<a href="teacher.php"><h4><u>Go Back To Home Page</u></h4></a>
		<br>
		<h4><b>Profile</b></h4>
		<div class="w3-right" style="margin-right: 100px"><a href="teacher.php"><i class="fa fa-window-close" aria-hidden="true" style="font-size: 48px;background-color: white;color:black"></i></a></div>
		<br>
		<br>
		<div style="background-image: url('images/backgroundForProfile.jpg'); margin-left: 2%; width: 90%">
			<center><i class="fa fa-user" style="font-size:128px;color:black"></i></center>
			<center><?php echo "<h2><b>".htmlspecialchars(ucfirst($_SESSION['teacher_name']))." ".htmlspecialchars(ucfirst($_SESSION['teacher_surname']))." (".$_SESSION['id'].")</b></h2>";?></center>
			<div style="background-color: white;color: black;">


				<br>
				  <div class="row">
				    <div class="col-sm-6" style="">
				    	<h4><b>Basic Information</b></h4>
				    	<hr>
				    	<div class="row">
				    		<div class="col-sm-6" style="">
				    			Full Name
				    		</div>
				    		<div class="col-sm-6" style="">
				    			<?php echo htmlspecialchars(ucfirst($_SESSION['teacher_name']))." ".htmlspecialchars(ucfirst($_SESSION['teacher_surname'])); ?>
				    		</div>
				    	</div>
				    	<hr>
				    	<div class="row">
				    		<div class="col-sm-6" style="">
				    			Email Address
				    		</div>
				    		<div class="col-sm-6" style="">
				    			<?php echo htmlspecialchars($_SESSION['teacher_email']); ?>
				    		</div>
				    	</div>
				    	<hr>
				    </div>
				    <div class="col-sm-6" style="">
				    	<h4><b>System Settings</b></h4>
				    	<hr>
				    	<form method="post" action="changepassword.php">
				    	<div class="row">
				    		<div class="col-sm-6" style="">
				    			Old Password
				    		</div>
				    		<div class="col-sm-6" style="">
				    			<input type="password" name="oldPassword" placeholder="Enter your old password">
				    		</div>
				    	</div>
				    	<br>
				    	<br>
				    	<div class="row">
				    		<div class="col-sm-6" style="">
				    			New Password
				    		</div>
				    		<div class="col-sm-6" style="">
				    			<input type="password" name="newPassword" placeholder="Enter your new password">
				    		</div>
				    	</div>
				    	<br>
				    	<br>
				    	<div class="row">
				    		<div class="col-sm-6" style="">
				    			Confirm Password
				    		</div>
				    		<div class="col-sm-6" style="">
				    			<input type="password" name="newPasswordConfirm" placeholder="Confirm your new password">
				    		</div>
				    	</div>
				    	<br>
				    	<div class="row">
				    		<div class="col-sm-6" style="">
				    			<?php
				    			if(isset($_POST['changedPasswordPass'])){
				    				echo "<p style='background-color: green; color: white'>".$_POST['changedPasswordPass']."</p>";
				    			}
				    			if(isset($_POST['changedPasswordFail'])){
				    				echo "<p style='background-color: red; color: white'>".$_POST['changedPasswordFail']."</p>";
				    			}				    			
				    			?>
				    		</div>
				    		<div class="col-sm-6" style="">
				    			<input type="submit" name="changePassword" value="Change Password">
				    		</div>
				    	</div>
				   		</form>
				    </div>
				  </div>
				<br>


			</div>
			<br>
		</div>
	</div>
</div>
</body>
</html>