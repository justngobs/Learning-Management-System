<?php

// function to sanitize input data
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data; 
}



 // function to test date data
 function isRealDate($date) { 
    if (false === strtotime($date)) { 
        return false;
    } 
    list($year, $month, $day) = explode('-', $date); 
    return checkdate($month, $day, $year);
}



 //function to generate random passwords/tokens
function generateRandomToken($n){
 $array = ["A","a","B","b","C","c","D","d","E","e","F","f","G","g","H","h","I","i","J","j","K","k","L","l","M","m","N","n","O","o","P","p","Q","q","R","r", "S","s","T","t","U","u","V","v","W","w","X","x","Y","y","Z","z","1","2","3","4","5","6","7","8","9","0","!","@","#","$","%","&","(",")",",","[","]","{","}","^","?"];
$passw = "";
for($i = 1; $i <= $n; $i++){
	$passw .= $array[array_rand($array)];
}
return $passw;
}

?>
