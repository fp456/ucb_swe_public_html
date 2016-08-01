<?php
include("../header_footer/header.html");
require_once('recaptchalib.php');
$privatekey = "6Lc8MMISAAAAAIot6rEEV24dQ7zWTDegmzKWYBD9";
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
  echo ("<p>The reCAPTCHA wasn't entered correctly. Go back and try it again.</p>");
}else{
  $msg=$_POST["email"];
  $subject="\"Email List: \"";
  $email="lianran@gmail.com";
  $sendmail = "/home/s/sw/swe/public_html/bin/sendEmail-v1.55/sendEmail";
  $cmd = $sendmail . " -f swe.berkeley@gmail.com  -t " . $email . " -u SWE Mailing List  -m " . $msg . " -s smtp.gmail.com -o tls=yes -xu swe.berkeley -xp 131Hesse";
  $cmd = "ssh tsunami.ocf.berkeley.edu \"" . $cmd . "\"";
  echo shell_exec($cmd);
    $connection = ssh2_connect('tsunami.ocf.berkeley.edu', 22);
    ssh2_aut_password($connection, 'swe', '131Hesse');
    $ssh2_exec($connection, $cmd);
	echo ("<p>You will now be added to our mailing list. You'll receive emails about upcoming events and career opportunities on campus.</p>");
} 
include("../header_footer/footer.html");
?>