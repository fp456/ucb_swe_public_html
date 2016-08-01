<?php
function varCheck($var, $errMsg){ 
  if (empty($var)){
    donationAndFooter($errMsg . "<br />"); 
  }
}

function donationAndFooter($msg){
  echo($msg);
  include("../../header_footer/footer.html");
  exit();
}


function sanitizeInput($var){
  return(mysql_real_escape_string(trim($_POST[$var])));
}

include("../../header_footer/header.html");
require_once('recaptchalib.php');
require('makeCSV.php');
$privatekey = "6Lc8MMISAAAAAIot6rEEV24dQ7zWTDegmzKWYBD9";
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
$server="mysql.ocf.berkeley.edu";
$user="swe";
$db = "swe";
$table = "outreachEvents";
$pwd="y5U3VGVvelM9QtH4v0S6";

if (! mysql_connect($server, $user, $pwd)){
  donationAndFooter("<p>ERROR: Could not connect to MySQL server, $server. <br/>Please try again later.If this problem persists contact the webmaster. Please copy and paste this error message in your email.</p>");
}
if (! mysql_select_db($db)){
  donationAndFooter("<p>ERROR: Could not connect to MySQL database, $db. <br/>Please try again later.If this problem persists contact the webmaster. Please copy and paste this error message in your email.</p>");
}

$parent = sanitizeInput('parent');
$phone1 = sanitizeInput('phone1');
$phone2 = sanitizeInput('phone2');
$email = sanitizeInput('email');
$confirm_email = sanitizeInput('confirm_email');
$school = sanitizeInput('school');
//$deposit = sanitizeInput('deposit');
$deposit = 'none';
$reference = sanitizeInput('reference');
$child = $_POST['child'];
$grade = $_POST['grade'];
$event = $_POST['event'];
$donation = 5;
$maxEvents = 3;
$rosterLen = 25;
$rosterWithWaitlist = 35;

//if (!$resp->is_valid) {
if (0) {
  echo ("<p>The reCAPTCHA wasn't entered correctly. Go back and try it again.</p>");
  //"(reCAPTCHA said: " . $resp->error . ")");
}else{
  for ($i = 0; $i < sizeof($child); ++$i){
    $child[$i] = mysql_real_escape_string(trim($child[$i]));
    varCheck($child[$i], "You must include every child's name.  Please go back and resubmit the form.");
  }
  if (count($event) > $maxEvents){
    donationAndFooter("You may only sign up for up to 3 events.  Please go back and resubmit the form.");
  }
  if (count($event) == 0){
    donationAndFooter("You must select an event.  Please go back and resubmit the form.");
  }

  varCheck($parent, "You must include a parent's name.  Please go back and resubmit the form.");
  varCheck($phone1, "You must include a primary phone contact.  Please go back and resubmit the form.");
  varCheck($phone2, "You must include a secondary phone contact.  Please go back and resubmit the form.");
  varCheck($email, "You must include an email contact.  Please go back and resubmit the form.");
  varCheck($school, "You must include the name of the child's school.  Please go back and resubmit the form.");

  if (strcmp($email, $confirm_email) != 0){
    donationAndFooter("<p>You confirmed your email incorrectly.</p>");
  }
  if ( (strlen($phone1) != 10) || (strlen($phone2) != 10)){
    donationAndFooter("<p>Please enter 10 digit phone numbers.</p>");
  }
  if (! (is_numeric($phone1) && is_numeric($phone2))){
    donationAndFooter("<p>Please enter both phone numbers without spaces, dashes, or dots (just numbers).</p>");
  }

// /* CREATE TABLE outreachEvents ( */
// /* 				      time TIMESTAMP default current_timestamp, */
// /* 				      parent VARCHAR(30), */
// /* 				      child VARCHAR(30), */
// /* 				      phone1 VARCHAR(10), */
// /* 				      phone2 VARCHAR(10), */
// /* 				      email VARCHAR(40), */
// /* 				      school VARCHAR(30), */
// /* 				      grade INT(1), */
// /* 				      deposit VARCHAR(6), */
// /* 				      reference VARCHAR(200), */
// /* 				      event VARCHAR(50), */
// /* 				      hashCode VARCHAR(32)); */

  echo("<p>COPY THE INFORMATION BELOW. If you decide to cancel your registration, you will be asked to enter the code.</p>");
  $emailMessage = "You may need this information if you want to cancel your registration.";
  $counter = 0;
  foreach ($event as $e){
    echo("<p>" . $e . ":<br />");
    $emailMessage = $emailMessage . "\n\n" . $e . "\n";
    $gradeCounter = 0;
    $notifyCommittee = FALSE;
    foreach ($child as $c){
      //prepending/appending tags should keep the hash method secret...
      $code = md5("ucBerkeley" . $email . $e . $c . "SWE2011");
      $msg = "";
      $duplicate = mysql_query("SELECT * FROM $table WHERE hashCode='$code'");
      $t = mysql_result($duplicate, 0);
      if (strcmp($t, "") != 0){
	$msg = "N/A. Apparently you've already signed up.";
      }else{
	$currentRosterLen = mysql_num_rows(mysql_query("SELECT * FROM $table WHERE event='$e'"));
	$doInsert = TRUE;
	$rosterWithWaitlist_extended = $rosterWithWaitlist;
	if (strcmp($e, "Science Saturday Mar 10 2012") == 0){
	$rosterWithWaitlist_extended = $rosterWithWaitlist_extended + 10;
	}
	if ($currentRosterLen >= $rosterWithWaitlist_extended){
	  $msg = "N/A. Sorry, we are already past the limit of the waitlist for this event.";
	  $doInsert = FALSE;
	}elseif ($currentRosterLen >= $rosterLen){
	  $msg = $code . ", NOTE: You've been placed on a waitlist for this event.  You'll be notified by email.";
	}else{
	  if ($currentRosterLen == ($rosterLen - 1))
	    $notifyCommittee = TRUE;
	  $msg = $code;
	}
	
	if ($doInsert){
	  $counter += 1;
	  $insert= "INSERT INTO ". $table ." VALUES(CURRENT_TIMESTAMP, '" . $parent . "', '" . $c . "', '" . $phone1 . "', '" . $phone2 . "', '" . $email . "', '" . $school . "', '" . $grade[$gradeCounter] . "', '" . $deposit . "', '" . $reference . "', '" . $e . "', '" . $code . "')";
	  if (! mysql_query($insert) )
	    $msg = "N/A. Signup failed.  Try again later.";
	}
      }
      $tmp = "Child: " . $c . ", Code: " . $msg;
      $emailMessage = $emailMessage . $tmp . "\n";
      echo( $tmp . "<br />");
      $gradeCounter += 1;
    }
    echo("</p>");
    makeCSV($e);
    
//     if ($notifyCommittee){
//       $outreachCommitteeNotification = "\n$e has reached capacity\n";
//       $cmd = "/usr/bin/mailx -s Outreach ". "swe.berkeley.outreach@gmail.com" . " <<EOM\n" .  $outreachCommitteeNotification . "\nEOM";
//       shell_exec($cmd);
//     }
//   }
//   $cmd = "/usr/bin/mailx -s Outreach ". $email . " <<EOM\n" . $emailMessage . "\nEOM";
//   shell_exec($cmd);
  echo("<p>Thank you!</p>");
}
}
donationAndFooter("");
?>