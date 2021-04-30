<!DOCTYPE html>
<html>
<head>
<title>Contact Us</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/navigation.css">
<style>
body {
  font-family: Arial, Helvetica, sans-serif;
}

* {
  box-sizing: border-box;
}

/* Style inputs */
input[type=text], select, textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  margin-top: 6px;
  margin-bottom: 16px;
  resize: vertical;
}

input[type=email], select, textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  margin-top: 6px;
  margin-bottom: 16px;
  resize: vertical;
}

input[type=submit] {
  background-color: #4CAF50;
  color: white;
  padding: 12px 20px;
  border: none;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

</style>
</head>
<body style="background-color:  rgb(223,233,190);">
<br>
<div style="text-align:center"> 
    <?php require 'navigation.html'; ?>
</div>
<br>
<br>

<?php

if(isset($_POST['send_message'])){

  require "phpgoodies.php";
  
	$cantcatchme = 0;
	$errors = "";

	if(empty($_POST['firstname'])){
		$errors .= "Name cannot be empty<br>";
	}
	else{
		$name1 = test_input($_POST['firstname']);
		$cantcatchme += 1;
		if (!preg_match("/^[a-zA-Z ]*$/",$name1)) {
			$cantcatchme -= 1;
			$errors .= "Name should contain only letters<br>";
		}
	}

	if(empty($_POST['lastname'])){
		$errors .= "Surname cannot be empty<br>";
	}
	else{
		$name2 = test_input($_POST['lastname']);
		$cantcatchme += 1;
		if (!preg_match("/^[a-zA-Z ]*$/",$name2)) {
			$cantcatchme -= 1;
			$errors .= "Surname should contain only letters<br>";
		}
	}

	if(empty($_POST['email'])){
		$errors .= "E-mail cannot be empty<br>";
	}
	else{
		$email = test_input($_POST['email']);
		$cantcatchme += 1;
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$cantcatchme -= 1;
			$errors .= "Invalid E-mail entered<br>";
		}
	}

	if(empty($_POST['message'])){
		$errors .= "Message cannot be empty<br>";
	}
	else{
		$msg = test_input($_POST['message']);
		$cantcatchme += 1;
	}

	if ($cantcatchme == 4) {
		require_once "app_config.php";
		$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

		if (!($stmt = $connection->prepare("INSERT INTO visitormessages ( sender_name, sender_surname, sender_email, sender_message, message_status, date_received) VALUES(?, ?, ?, ?, ?, ?)"))) {
		echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;}

		$stmt->bind_param("ssssss", $a, $b, $c, $d, $e, $f); 
			$a = $name1 ;
			$b = $name2 ;
			$c = $email ;
			$d = $msg ;
			$e = "1";
			$f = "2019/03/18";
			
		$stmt->execute();
		$stmt->close();
		$connection->close();

    //Return a success message
		echo "<form method='post' action='contact.php' id='formFeedback'><input type='text' hidden='hidden' name='message_sent' value='success'><input type='submit' hidden='hidden'></form>";
		echo "<script> document.forms['formFeedback'].submit();</script>";
		
	}

	else{
    //Return the error message
		echo "<form method='post' action='contact.php' id='formFeedback'><input type='text' hidden='hidden' name='message_unsent' value='".$errors."'><input type='submit' hidden='hidden'></form>";
		echo "<script> document.forms['formFeedback'].submit();</script>";
	}
}
?>


<div class="container">
  <div style="text-align:center">
    <h2>Contact Us</h2>
    <p>Come by at our school, or leave us a message:</p>
  </div>
  <div class="row">
    <div class="column">
      <img src="images/diepdaletop.png" style="width:100%">
    </div>
    <div class="column">

    <?php
      if(isset($_POST['message_unsent'])){
        echo"
        <div class='alert alert-warning'>
          ".$_POST['message_unsent']."
        </div>";
      }
    ?>
      <form method="post" action="contact.php">
        <label for="fname">First Name</label>
        <input type="text" id="fname" name="firstname" placeholder="Your name.." required="required">
        <label for="lname">Last Name</label>
        <input type="text" id="lname" name="lastname" placeholder="Your last name.." required="required">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" placeholder="Your email.." required="required">
        <label for="message">Message</label>
        <textarea id="message" name="message" placeholder="Write something.." style="height:170px" required="required"></textarea>

        <?php 
          if(isset($_POST['message_sent'])){

            echo"
            <div class='alert alert-success'>
              <strong> Message sent!</strong> Thanks for contacting us. We try to respond as fast as possible.
            </div>";

          }
        ?>

        <input type="submit" value="Send Message" name="send_message">
      </form>
    </div>
  </div>
</div>
<br>
<div style="text-align:center">
    <?php require "footer.html"; ?>
</div>

</body>
</html>
