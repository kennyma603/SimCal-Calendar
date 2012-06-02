<?php

include_once "db_connect.php";
session_start(); 
include_once "check_session.php";
include_once "meta.php";
include_once "header.php";
include_once "menu.php"; ?>

<!-- Beginning of main frame -->
<div id="frame">

Please fill in the form:

<div>
<br />	
<form action="add_schedule.php?submit=true" method="post">
Event Title: <input type="text" name="schedule_title" /><br /><br />
Event Description: <input type="text" name="schedule_des" /><br /><br />
Event Location: <input type="text" name="schedule_location" /><br /><br />
Event Date: <input type="text" name="schedule_date" value="<?php echo date("Y-m-d"); ?>"/><br /><br />
Event Start Time: <input type="text" name="schedule_startTime" /><br /><br />
Event End Time: <input type="text" name="schedule_endTime" /><br /><br />
<input type="submit" value="Submit !" />
</form>
</div>

	
<!-- End of main frame -->


	<?php 

	if(isset($_GET['submit'])) {
	
		// That's nice, user wants to login. But lets check if user has filled in all information
		If(empty($_POST['schedule_title']) OR empty($_POST['schedule_date'])) {
		
			// User hasn't filled it all in!
			echo '<br />Please fill in all fields!';
		
		} else {
			
			$year = strtok(date("Y-m-d"), "-");
			$month = strtok("-");
			$day = strtok("-");
	
			// User filled it all in!
	
			// Make variables save with addslashes and md5
			$schedule_title = addslashes($_POST['schedule_title']);
			$schedule_des = addslashes($_POST['schedule_des']);
			$schedule_location = addslashes($_POST['schedule_location']);
			$schedule_date = addslashes($_POST['schedule_date']);
			$schedule_startTime = addslashes($_POST['schedule_startTime']);
			$schedule_endTime = addslashes($_POST['schedule_endTime']);
				
			// Search for a combination
			$query = mysql_query("INSERT INTO event (title, des, location, day, month, year, start_time, end_time, user_id) VALUES ('" . $schedule_title . "' , '" . $schedule_des . "', '" . $schedule_location . "', '" . $day . "', '" . $month . "', '" . $year . "', '" . $schedule_startTime . "', '" . $schedule_endTime . "', '" . getID($_SESSION['simcal_username']) . "')") or die(mysql_error());
			
		echo "Schedule has been saved.";	
		
		}	
	}
	
	?>


</div>
<?php include_once "footer.php" ?>