<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php

require "phpgoodies.php";
	$cantcatchme = 0;
	$errors = "";

	if(empty($_POST['email'])){
		$errors .= "E-mail is required<br>";
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

	if(empty($_POST['subject'])){
		$errors .= "Subject cannot be empty<br>";
	}
	else{
		$sub = test_input($_POST['subject']);
		$cantcatchme += 1;
	}		

if($cantcatchme == 3){
	
		require_once "app_config.php";
		$connection = mysqli_connect($host,$user,$password,$database) or die("<p>Error connecting to database: ".mysqli_error(). "</p>");

        if (!($stmt = $connection->prepare("INSERT INTO sentmessages (email, subject, message) VALUES(?, ?, ?)"))) {
        echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;}

        $stmt->bind_param("sss", $a, $b, $c); 
        	$a = $email ;
        	$b = $sub ;
        	$c = $msg ;
        	
        $stmt->execute();
        $stmt->close();
        $connection->close();

        $headers = "From: example@gmail.com" . "\r\n" ."CC: example@gmail.com";

		mail($email,$sub,$msg,$headers);

        echo "<h1 style='text-align:center'>Message sent</h1><br><br><a href='messagemanager.php'><strong style='text-align:center'>go Back</strong></a>";
}

else{
	echo $errors."<br><br>Go back to fix the mistakes";
}
?>
</body>
</html>