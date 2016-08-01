function changeNewsletter(){
    var months = document.forms[0].getElementsByTagName('input');
    var month = "none";
    for (var i = 0; i < months.length; i++){
	if (months[i].checked)
	    month = months[i].value;
    }
    
    var years = document.forms[0].getElementsByTagName('select')[0];
    var year = years[years.selectedIndex].text;
    var fname= year + "_" + month + ".pdf";
    var newsletter="http://docs.google.com/viewer?url=swe.berkeley.edu%2Fnewsletter%2F"+fname+"&embedded=true";
    if (month == "none" || year == "Year")
	return;

    document.getElementById("newsletter_viewer").src=newsletter;
}