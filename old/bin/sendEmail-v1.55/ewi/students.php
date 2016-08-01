<?php 
function getFileExtension($fname){
  $fname_split = explode(".", $fname);
  return(trim($fname_split[sizeof($fname_split) - 1]));
}

include("header_footer/header.html");
echo('<div class="ewi">
  <p>
  <strong>Announcement:</strong><br />
  EWI resumes are due October 29th..
  </p>
  <p>
  UC Berkeley\'s Society of Women Engineers (SWE) is proud to host our 36th annual Evening with Industry (EWI) career fair/dinner event!
  All Berkeley students are welcome to attend.
  We will be selling tickets during the two weeks before the event.
  Dress code is business casual.
  </p>
  
  <h3>Information</h3>
  <table id="ewiInfoTable">
    <tr>
      <td>Date:</td>
      <td>
      Thursday, November 1, 2012 <br/>
      </td>
    </tr>
    <tr>
      <td>Time:</td><td>6-9 PM</td>
    </tr>
    <tr>
      <td>Location:</td><td><a href="http://www.hslordships.com/hslordships/" target="_blank">HS Lordships</a></td>
    </tr>
    <tr>
      <td>Cost:</td><td>From 10/15 - 10/21 $10 National SWE member, <br/>$15 non-member 
10/22 - whenever tickets sell out - $13/$18</td>
    </tr>
  </table>
  <p>
  Come join us for an opportunity you cannot miss:
  <ul>
    <li>
    Network with industry leaders in a personal setting without the stress of rushing to class
    </li>
    <li>
    Sit down with the very same professionals of the company your choice over a delicious three-course dinner
    </li>
    <li>
    Seek out exciting internships and full-time positions
    </li>
  </ul>
  </p>
  
  <p>Tickets will be sold beginning Oct. 15 in front of Evans Hall, facing the Hearst Mining Circle. Tabling times are:
  <ul>
	<li>October 15 through October 30 (or whenever tickets are sold out): 10AM - 5PM/li>
  </ul>	
  </p>	  

  
');
$PATH = "../sponsors/logos/";
$DIRS = array("golden/", "silver/", "bronze/");
foreach($DIRS as $d){
  $images = Array();
  foreach(scandir($PATH . $d) as $s){
    if (getFileExtension($s) === "gif"){
      $images[] = $s;
    }
  }
  sort($images, SORT_STRING);

  if ($d == "golden/")
    echo("<h3>Golden Bear Sponsors:</h3>");
  else if ($d == "silver/")
    echo("<h3>Silver Bear Sponsors:</h3>");
  else if ($d == "bronze/")
    echo("<h3>Bronze Bear Sponsors:</h3>");
    
  
  foreach($images as $i){
    echo("<img class=\"corporateLogos\" src=\"/" . $PATH . $d . $i . "\"/>");   
  }
  if ($d == "bronze/")
    echo("<p>Google and Salesforce will not be attending, but will each receive a resume CD.</p>");
}

  
echo('</div>');
include("header_footer/footer.html");
?>

