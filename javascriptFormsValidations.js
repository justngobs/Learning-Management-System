function isHTML(str) {
  var a = document.createElement('div');
  a.innerHTML = str;

  for (var c = a.childNodes, i = c.length; i--; ) {
    if (c[i].nodeType == 1) return true; 
  }

  return false;
}

function validateAnnouncement(){
	var title = document.forms["announcements"]["title"].value;
	var message = document.forms["announcements"]["announcement"].value;
	var errors = "";
	var cantcatchme = 0;
	if(title == "" || title == null){
		errors += "* Name must be filled.<br>";
		cantcatchme += 1;
	}
	if(isHTML(title)){
		errors += "* Title contains Invalid tags.<br>";
		cantcatchme += 1;
	}
	if(message == "" || title == null){
		errors += "* Announcement cannot be empty.<br>";
		cantcatchme += 1;
	}
	if(isHTML(message)){
		errors += "* Title contains Invalid tags.<br>";
		cantcatchme += 1;
	}
	if(cantcatchme > 0){
		document.getElementById("error").innerHTML = errors;
		return false;
	}
}