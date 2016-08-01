<?php
function getFileExtension($fname){
  $fname_split = explode(".", $fname);
  return(trim($fname_split[sizeof($fname_split) - 1]));
}

include("./header_footer/header.html");
echo("
<h2 class=\"title\">Corporate Sponsors</h2>
<p>
For information about becoming a corporate sponsor, look at our
<a href=\"corporate.shtml\">Corporate Relations</a> page.
</p>");

$PATH = "sponsors/logos/";
$DIRS = array("golden/", "silver/", "bronze/", "other/");
$images = Array();
foreach($DIRS as $d){
  $stuff = scandir($PATH . $d);
  foreach($stuff as $s){
    if (getFileExtension($s) === "gif"){
      $images[] = $s;
    }
  }
}
sort($images, SORT_STRING);
foreach($images as $i){
  foreach($DIRS as $d){
    if (array_search($i, scandir($PATH . $d)))
      echo("<img class=\"corporateLogos\" src=\"/" . $PATH . $d . $i . "\"/>");   
  }
}
include("./header_footer/footer.html");
?>