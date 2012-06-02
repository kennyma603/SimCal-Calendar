<?php
include("phpmailer/class.phpmailer.php");
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();

$mail->IsSMTP();
$mail->SMTPAuth   = true;                  // enable SMTP authentication

$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port for the GMAIL server

$mail->Username   = "simcalendar@gmail.com";  // GMAIL username
$mail->Password   = "simcal470";            // GMAIL password

$mail->AddReplyTo("simcalendar@gmail.com","simCal Team");

$mail->From       = "simcalendar@gmail.com";
$mail->FromName   = "simCal Team";

$mail->Subject    = $subject;

//$mail->Body       = "Hi,<br>This is the HTML BODY<br>";                      //HTML Body
$mail->AltBody    = $alt_body; // optional, comment out and test
$mail->WordWrap   = 50; // set word wrap

$mail->MsgHTML($body);

$mail->AddAddress($email, $username);

//$mail->AddAttachment("phpmailer/examples/images/phpmailer.gif");             // attachment

$mail->IsHTML(true); // send as HTML

if(!$mail->Send()) {
  echo "<br />Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "";
}
?>