<?php
//This will display all *.csv files in the current directory.
//Just remove the old files each semester.
//The data will remain in the MySQL database.
include("../../header_footer/header.html");
echo("<h2 class=\"title\">Outreach Rosters</h2>");
//echo("<p>Click on each link to download a spreadsheet of the roster.</p>");
$stuff = scandir('.');
$none = True;
foreach($stuff as $f){
  $fname_split = explode(".", $f);
  if (strcmp("csv", $fname_split[sizeof($fname_split) - 1]) == 0){
    $none = False;
    echo("<p><a href=\"" . $f . "\">" . $f . "</a>");
    echo("<br />Last Modified: " .  date ("F d Y H:i:s", filemtime($f)));
    echo("<br />Length of Roster: " . (count(file($f)) - 1));
    echo("</p>");
  }
}
if ($none)
   echo("<p>There are no rosters yet. Come back when people have signed up, or contact the webmaster if you think this is a mistake.</p>");
include("../../header_footer/footer.html");
?>