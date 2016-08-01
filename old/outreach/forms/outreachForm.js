var child;
var grade;

function setCookie(c_name,value,exdays){    
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
    document.cookie=c_name + "=" + c_value;
}
function getCookie(c_name){
    var i,x,y,ARRcookies=document.cookie.split(";");
    for (i=0;i<ARRcookies.length;i++){
	x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
	y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
	x=x.replace(/^\s+|\s+$/g,"");
	if (x==c_name){
	    return unescape(y);
	}
    }
}

function saveInfo(){
    var childrenTextBoxes = document.getElementsByName('child[]');
    var gradeSelections = document.getElementsByName('grade[]');
    for (var i = 0; i < child.length; i++){
	child[i] = childrenTextBoxes[i].value;
	grade[i] = gradeSelections[i].value;
    }
    setCookie("child", child, 1);
    setCookie("grade", grade, 1);
}

function toggleEvents(eventId){
    if (eventId == "saturdayEvents"){
	document.getElementById("saturdayEvents").style.display = "block";
	document.getElementById("scholarEvents").style.display = "none";
	var tmp = document.getElementById("scholarEvents").children[0].children;
	for (var i = 0; i < tmp.length; i++){
	    if (tmp[i].type == "checkbox")
		tmp[i].checked = "";
	}
    }else if (eventId == "scholarEvents"){
	document.getElementById("scholarEvents").style.display = "block";
	document.getElementById("saturdayEvents").style.display = "none";
	var tmp = document.getElementById("saturdayEvents").children[0].children;
	for (var i = 0; i < tmp.length; i++){
	    if (tmp[i].type == "checkbox")
		tmp[i].checked = "";
	}
    }
}

function add() {    
    if (child.length >= 15){
	return;
    }
	
    var childrenTextBoxes = document.getElementsByName('child[]');
    var gradeSelections = document.getElementsByName('grade[]');
    for (var i = 0; i < child.length; i++){
	child[i] = childrenTextBoxes[i].value;
	grade[i] = gradeSelections[i].value;
    }

    child.push("");
    grade.push(4);
    setCookie("child", child, 1);
    setCookie("grade", grade, 1);
    display();
}

function display() {
    document.getElementById('children').innerHTML="";
    for (var i = 0; i < child.length; i++) {
	document.getElementById('children').innerHTML += createInput();
    }
    for (var i = 0; i < child.length; i++){
	document.getElementsByName('child[]')[i].value = child[i];
	document.getElementsByName('grade[]')[i].value = grade[i];
    }
    saveInfo();
}

function createInput() {
    var str = "Child (ex: Jane Smith), Grade:<br />"
	+"<input class=\"text\" type=\"text\" size=\"12\" maxlength=\"30\" name=\"child[]\">"
	+"<select name=\"grade[]\">"
	+"<option>4</option>"
	+"<option>5</option>"
	+"<option>6</option>"	
	+"<option>7</option>"
	+"<option>8</option>"
	+"</select>"
	+"<br />";    
    return str;
}

function remove() {
    if (child.length > 1) {
	child.pop(); 
	grade.pop();
    }
    display(); 
}

function initPage(){
    if (getCookie("child") != null && getCookie("grade") != null){
	child = getCookie("child").split(',');
	grade = getCookie("grade").split(',');    
    }else{
	child = [""];
	grade = ["4"];
    }
    if (child.length > 15 || grade.length > 15){
	child = [""];
	grade = ["4"];
    }
    display();    
}

window.onload = initPage;