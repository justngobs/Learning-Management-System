function openLogin(userType){
	var i;
	var x = document.getElementsByClassName("user");

	for(i = 0; i < x.length; i++){
		x[i].style.display = "none";
	}

	document.getElementById(userType).style.display = "block";
}

function openFunction(functionType){
	var i;
	var x = document.getElementsByClassName("user_container");

	for(i = 0; i < x.length; i++){
		x[i].style.display = "none";
	}

	document.getElementById(functionType).style.display = "block";
}

function next(){
	var a = integer.Parse($("#answer").val());
	alert(a);
}

// function to hide and show forms for student and teachers
function ourFunction(id){

	if(document.getElementById(id).style.display == "none"){
		document.getElementById(id).style.display = "block";
		document.getElementById("btnText").innerHTML = "Hide";
	}
	else{
		document.getElementById(id).style.display = "none";
		document.getElementById("btnText").innerHTML = "Show";
	}
}


//function to show and hide arcodion
function myFunctionForChangingPassword() {
  var x = document.getElementById("changePassword");
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else { 
    x.className = x.className.replace(" w3-show", "");
  }
}
