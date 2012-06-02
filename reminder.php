#!/usr/bin/php -q
<?php
/**
 * @author Esmond Man
 * @copyright 2008
 */
include_once "db_connect.php";

$todays_date = date( "Ymd" );
$year = substr($todays_date, 0, 4);
$month = substr($todays_date, 4, 2);
$date = substr($todays_date, 6, 2);

$personal = array();

$reminder_query = mysql_query("SELECT * FROM event 
						WHERE year = '" . $year . "' AND month = '" . $month . "' AND day = '" . $date . "'") 
						or die(mysql_error());

while($reminder_row = mysql_fetch_array($reminder_query))
{	
	$event_id = $reminder_row['ID'];
	if($reminder_row['feed_id'] == 0)
	{
		$personal = $reminder_row['user_id'];
		$user_query = mysql_query("SELECT username FROM login 
							WHERE ID = '" . $reminder_row['user_id'] . "'") 
							or die(mysql_error());	
								
		list($username) = mysql_fetch_row($user_query);	
			
		$share_query = mysql_query("SELECT * FROM share 
							WHERE username = '" . $username . "'") 
							or die(mysql_error());		
	
		while($share_row = mysql_fetch_array($share_query))
		{
			if($share_row['activation'] == 1)
			{
				$user_id_query = mysql_query("SELECT ID FROM login 
								WHERE username = '" . $share_row['grant_access'] . "'") 
								or die(mysql_error());	
									
				list($user_id) = mysql_fetch_row($user_id_query);
				
				mysql_query("INSERT INTO reminder
							(title, start_time, end_time, user_id, event_id)
							VALUES
							('" . $reminder_row['title'] . "', '" . $reminder_row['start_time'] ."' , '" . $reminder_row['end_time'] ."' , '" . $user_id . "' , '" . $reminder_row['ID'] . "')") 
							or die(mysql_error());				
			}
		}
		mysql_query("INSERT INTO reminder
					(title, start_time, end_time, user_id, event_id)
					VALUES
					('" . $reminder_row['title'] . "', '" . $reminder_row['start_time'] ."' , '" . $reminder_row['end_time'] ."' , '" . $reminder_row['user_id'] . "' , '" . $reminder_row['ID'] . "')") 
					or die(mysql_error());	
	}
	else
	{
		$subscribe_query = mysql_query("SELECT * FROM subscribe 
							WHERE feed_id = '" . $reminder_row['feed_id'] . "'") 
							or die(mysql_error());
		
		while($subscribe_row = mysql_fetch_array($subscribe_query))
		{
			mysql_query("INSERT INTO reminder
						(title, start_time, end_time, user_id, event_id)
						VALUES
						('" . $reminder_row['title'] . "', '" . $reminder_row['start_time'] . "' , '" . $reminder_row['end_time'] . "' , '" . $subscribe_row['user_id'] . "' , '" . $reminder_row['ID'] . "')") 
						or die(mysql_error());
		}
	}
}
include("phpmailer/class.phpmailer.php");

$reminder_event_query = mysql_query("SELECT DISTINCT event_id FROM reminder WHERE event_id <>'0'") or die(mysql_error());

while($reminder_event_row = mysql_fetch_array($reminder_event_query))
{
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

	$reminder_query = mysql_query("SELECT * FROM reminder WHERE event_id = '" . $reminder_event_row['event_id'] . "'") or die(mysql_error());
	
	while($reminder_row = mysql_fetch_array($reminder_query))
	{	
		$user_query = mysql_query("SELECT email FROM login 
						WHERE ID = '" . $reminder_row['user_id'] . "'") 
						or die(mysql_error());	
						
		list($email) = mysql_fetch_row($user_query);
		
		$username_query = mysql_query("SELECT username FROM login 
					WHERE ID = '" . $reminder_row['user_id'] . "'") 
					or die(mysql_error());	
						
		list($username) = mysql_fetch_row($username_query);
	
		$mail->AddAddress($email, $username);
	
		if(($reminder_row['start_time'] == "00:00:00") AND ($reminder_row['end_time'] == "00:00:00"))
		{
			$time = 'for the whole day';
		}
		else
		{
			$time = 'from ' . $reminder_row['start_time'] . ' till ' . $reminder_row['end_time'];
		}
			
		$subject = $reminder_row['title'] . ' - Event Reminder Email';
							
		$body = '<p>Hello to everyone who have subscribed to this feed and event! <br />		
				This is a friendly reminder from your service calendar, simCal, who would like to remind you of the event happening today.
				<br />
				Please visit <a href="http://cmpt470.csil.sfu.ca:8007">simCal</a> for the location and description of the event. 
				<br />
				' . $reminder_row['title'] . ' will be going ' . $time . ', so stay on time.
				</p>';
							
		$alt_body = 'Hello to everyone who have subscribed to this feed and event! 
		This is a friendly reminder from your service calendar, simCal, who would like to remind you of the event happening today.
	
		Please visit <a href="http://cmpt470.csil.sfu.ca:8007">simCal</a> for the location and description of the event. ' . $reminder_row['title'] . 'will be going ' . $time . ', so stay on time.';
	
	}
	$mail->Subject    = $subject;
		
	//$mail->Body       = "Hi,<br>This is the HTML BODY<br>";                      //HTML Body
	$mail->AltBody    = $alt_body; // optional, comment out and test
	$mail->WordWrap   = 50; // set word wrap
		
	$mail->MsgHTML($body);
		
	//$mail->AddAttachment("phpmailer/examples/images/phpmailer.gif");             // attachment
		
	$mail->IsHTML(true); // send as HTML
		
	if(!$mail->Send())
	{
	  echo "<br />Mailer Error: " . $mail->ErrorInfo;
	} 
	else
	{
	  echo "Message Sent";
	}
	mysql_query("DELETE FROM reminder WHERE event_id = '" . $reminder_event_row['event_id'] . "'") or die(mysql_error());

	sleep(20);
}

foreach ($personal as $ID)
{
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

	
	$personal_username_query = mysql_query("SELECT username FROM login 
									WHERE ID = '" . $ID . "'") 
									or die(mysql_error());	
						
	list($personal_username) = mysql_fetch_row($personal_username_query);
	
	$personal_email_query = mysql_query("SELECT email FROM login 
									WHERE ID = '" . $ID . "'") 
									or die(mysql_error());	
						
	list($personal_email) = mysql_fetch_row($personal_email_query);
	
	$mail->AddAddress($personal_email, $personal_username);
	
	
	$share_personal_query = mysql_query("SELECT * FROM share 
							WHERE username = '" . $personal_username . "'") 
							or die(mysql_error());
	
	while($share_personal_row = mysql_fetch_array($share_personal_query))
	{
		if($share_personal_row['activation'] == 1)
		{
			$userid_query = mysql_query("SELECT ID FROM login 
							WHERE username = '" . $share_personal_row['grant_access'] . "'") 
							or die(mysql_error());	
									
			list($userid) = mysql_fetch_row($userid_query);			

			$user_query = mysql_query("SELECT email FROM login 
							WHERE ID = '" . $userid . "'") 
							or die(mysql_error());	
						
			list($email) = mysql_fetch_row($user_query);
		
			$username_query = mysql_query("SELECT username FROM login 
						WHERE ID = '" . $userid . "'") 
						or die(mysql_error());	
						
			list($username) = mysql_fetch_row($username_query);
	
			$mail->AddAddress($email, $username);
		}
	}
	
	$reminder_query = mysql_query("SELECT * FROM reminder WHERE event_id = '0' AND user_id = '" . $ID . "'") or die(mysql_error());
	
	$reminder_row = mysql_fetch_array($reminder_query);
	
	if(($reminder_row['start_time'] == "00:00:00") AND ($reminder_row['end_time'] == "00:00:00"))
	{
		$time = 'for the whole day';
	}
	else
	{
		$time = 'from ' . $reminder_row['start_time'] . ' till ' . $reminder_row['end_time'];
	}
			
	$subject = $reminder_row['title'] . ' - Event Reminder Email';
							
	$body = '<p>Hello to everyone who have subscribed to this feed and event! <br />		
			This is a friendly reminder from your service calendar, simCal, who would like to remind you of the event happening today.
			<br />
			Please visit <a href="http://cmpt470.csil.sfu.ca:8007">simCal</a> for the location and description of the event. 
			<br />
			' . $reminder_row['title'] . ' will be going ' . $time . ', so stay on time.
			</p>';
							
	$alt_body = 'Hello to everyone who have subscribed to this feed and event! 
	This is a friendly reminder from your service calendar, simCal, who would like to remind you of the event happening today.
	
	Please visit <a href="http://cmpt470.csil.sfu.ca:8007">simCal</a> for the location and description of the event. ' . $reminder_row['title'] . 'will be going ' . $time . ', so stay on time.';
	
	
	$mail->Subject    = $subject;
		
	//$mail->Body       = "Hi,<br>This is the HTML BODY<br>";                      //HTML Body
	$mail->AltBody    = $alt_body; // optional, comment out and test
	$mail->WordWrap   = 50; // set word wrap
		
	$mail->MsgHTML($body);
		
	//$mail->AddAttachment("phpmailer/examples/images/phpmailer.gif");             // attachment
		
	$mail->IsHTML(true); // send as HTML
		
	if(!$mail->Send())
	{
	  echo "<br />Mailer Error: " . $mail->ErrorInfo;
	} 
	else
	{
	  echo "Message Sent";
	}
	sleep(20);
}
mysql_query("DELETE FROM reminder WHERE event_id = '0'") or die(mysql_error());
?>