<?php
/* CREATE TABLE outreachEvents ( */
/* 				      time TIMESTAMP default current_timestamp, */
/* 				      parent VARCHAR(30), */
/* 				      child VARCHAR(30), */
/* 				      phone1 VARCHAR(10), */
/* 				      phone2 VARCHAR(10), */
/* 				      email VARCHAR(40), */
/* 				      school VARCHAR(30), */
/* 				      grade INT(1), */
/* 				      deposit VARCHAR(6), */
/* 				      reference VARCHAR(200), */
/* 				      event VARCHAR(50), */
/* 				      hashCode VARCHAR(32)); */
function quitNow($msg){
  echo($msg);
  include("../../header_footer/footer.html");
  exit();
}
include("../../header_footer/header.html");
require_once('recaptchalib.php');
require('makeCSV.php');
$privatekey = "6Lc8MMISAAAAAIot6rEEV24dQ7zWTDegmzKWYBD9";
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
  quitNow("<p>The reCAPTCHA wasn't entered correctly. Go back and try it again.</p>");
  //"(reCAPTCHA said: " . $resp->error . ")");
}else{
  $server="mysql.ocf.berkeley.edu";
  $user="swe";
  $db = "swe";
  $table = "outreachEvents";
  $pwd="y5U3VGVvelM9QtH4v0S6";

  if (! mysql_connect($server, $user, $pwd))
    quitNow("<p>ERROR: Could not connect to MySQL server, $server. <br/>Please try again later.If this problem persists contact the webmaster. Please copy and paste this error message in your email.</p>");
  if (! mysql_select_db($db))
    quitNow("<p>ERROR: Could not connect to MySQL database, $db. <br/>Please try again later.If this problem persists contact the webmaster. Please copy and paste this error message in your email.</p>");

  $code = mysql_real_escape_string(trim($_POST["cancelCode"]));
  $emailEntered = mysql_real_escape_string(trim($_POST["email"]));
  if (strlen($code) != 32)
    quitNow("<p>The code should be 32 characters long.  Please try again.</p>"); 

  $row = mysql_query("SELECT * FROM $table WHERE hashCode='$code'");
  $event = mysql_query("SELECT event FROM $table WHERE hashCode='$code'");
  $event = mysql_result($event, 0, 0);
  $email = mysql_query("SELECT email FROM $table WHERE hashCode='$code'");
  $email = mysql_result($email, 0, 0);
  $child = mysql_query("SELECT child FROM $table WHERE hashCode='$code'");
  $child = mysql_result($child, 0, 0);

  if (strcmp(mysql_result($row, 0, 0), "") == 0 || strcmp($event, "") == 0 || strcmp($email, "") == 0)
    quitNow("<p>The code is invalid.  Please try again.</p>");
  if (strcmp($email, $emailEntered) != 0)
    quitNow("<p>The code you entered does not match the email address. Please try again.</p>");
  

  mysql_query("DELETE FROM $table WHERE hashCode='$code'");
  echo("<p>EVENT: $event<br/>CHILD: $child<br />EMAIL: $email</p>");
  makeCSV($event);

  $emailMessage = "You have successfully removed $child from the $event roster.";
  $cmd = "/usr/bin/mailx -s Outreach ". $email . " <<EOM\n" . $emailMessage . "\nEOM";
  shell_exec($cmd);
  quitNow("<p>" . $emailMessage . "</p>");
}

?>