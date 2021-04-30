<?php
session_start();

if(isset($_SESSION['loggedin'])){
if($_SESSION['ulevel'] == 3){
	if($_SESSION['ip'] == $_SERVER['REMOTE_ADDR']){
			header('Location: messagemanager.php');
		}
		else{
			session_destroy();
			echo"Please <a href='login.php'> click here </a> to login again. There has been a technical error";
			exit();
		}
}
if($_SESSION['ulevel'] == 2){
	if($_SESSION['ip'] == $_SERVER['REMOTE_ADDR']){
			header('Location: teacher.php');
		}
		else{
			session_destroy();
			echo"Please <a href='login.php'> click here </a> to login again. There has been a technical error";
			exit();
		}
}
if($_SESSION['ulevel'] == 1){
	if($_SESSION['ip'] == $_SERVER['REMOTE_ADDR']){
		header('Location: student.php');
		}
		else{
			session_destroy();
			echo"Please <a href='login.php'> click here </a> to login again. There has been a technical error";
			exit();
		}
}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login to Portal</title>
	<meta http-equiv="X-UA-Compatible" content="IE=7, IE=8, IE=9, IE=edge"/>
    <meta http-equiv="Pragma" content="no-cache">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/navigation.css">
	<style type="text/css">

		.simple-login-container{
		    width:300px;
		    max-width:100%;
		    margin:50px auto;
		}
		.simple-login-container h2{
		    text-align:center;
		    font-size:20px;
		}

		.simple-login-container .btn-login{
		    background-color:#FF5964;
		    color:#fff;
		}
		a{
		    color:#fff;
		}
	</style>
</head>
<body style="background-color:  rgb(223,233,190);">
<br>
<div style="text-align:center">
	<?php require 'navigation.html'; ?>
</div>

<div style="width:90%;margin-left:5%;background-color:white;text-align:center">
    <br>
    <div style="display:inline-block"> 
    <div class="simple-login-container">
			<h2>Resend Password</h2>
			<form method="post" action="authenticate.php">
			
				<div class="row">
					<div class="col-md-12 form-group">
						<input type="text" class="form-control" placeholder="Username/E-mail    " name="username" required="required">
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 form-group">
						<input type="submit" class="btn btn-block btn-login" value="Send reset link">
					</div>
				</div>
				<?php
					if(isset($_POST['loggin_error'])){
						echo"
						<div class='alert alert-warning'>
						".$_POST['loggin_error']."
						</div>";
					}
				?>
			</form>
		</div> 
    </div>
    <br>
    <br>
</div>
<div style="text-align:center">
    <?php require "footer.html"; ?>
</div>

</body>
</html>