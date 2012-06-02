<?php

include_once "db_connect.php";
session_start(); 
include_once "check_session.php";
include_once "meta.php";
include_once "header.php";
include_once "menu.php"; ?>

<!-- Beginning of main frame -->
<div id="frame">
<!-- Beginning of left column -->


	<?php
	if(isset($_GET['new'])) {
	echo "<div id=\"leftcol\">";
	echo "Please fill in the form: <br/><br/>";

	echo "<form action=\"create_rss.php?submit=true\" method=\"post\">";
	echo "RSS Feed Title: <input type=\"text\" name=\"rsstitle\" /><br /><br />";
	echo "RSS Description: <input type=\"text\" name=\"rssdes\" /><br /><br />";
	/*<!--Event: 
	<select name="event">
	<option value="none">Create Empty RSS Feed/option>
	<option value="newEvent">Add New Event</option>
	<option value="existingEvent">Add Existing Event</option>
	</select>
	<script type="text/javascript"> 
	document.location.href = "create_rss.php?submit=true&event=" + $_POST['event'];
	</script>-->*/
	echo "<input type=\"submit\" value=\"Create Blank RSS\" />";
	echo "</form><br />";
	echo "<input type=\"button\" value=\"Add New Event to RSS\" onclick=\"window.location.href='create_rss.php?eventNew=true'\"/></p>";
	echo "<p><input type=\"button\" value=\"Add Existing Events to RSS\" onclick=\"window.location.href='create_rss.php?eventExist=true'\"/></p>";
	
	echo "</div>";
	}
	?>

	<!--end of left column-->
	
	
	<!-- beginning right column
	<div id="rightcol">-->
	<?php
	//user wants to add new event to this RSS before creating
	if(isset($_GET['eventNew'])) {
			
		//start left column refresh
		echo "<div id=\"leftcol\">";
		echo "	Please fill in the form: <br/><br />";	
		echo "RSS Feed Title: <input type=\"text\" name=\"rsstitle\" /><br /><br />";
		echo "RSS Description: <input type=\"text\" name=\"rssdes\" /><br /><br />";
			
		
		echo "</div>";
		
		
		
		echo "<div id=\"rightcol\">";
		echo "Please fill in the event details:";
		echo "<br /><br />";	
		//submit set to false, since event is added to RSS, hence try=true
		//submit=true only if its for create RSS blank with no events
		echo "<form action=\"create_rss.php?eventNew=true&try=true\" method=\"post\">";//&submit=false
		echo "Title: <input type=\"text\" name=\"eventTitle\" /><br /><br />";
		echo "Description: <input type=\"text\" name=\"eventDescription\" /><br /><br />";
		echo "Location: <input type=\"text\" name=\"eventLocation\" /><br /><br />";
		echo "Day: <input type=\"int\" name=\"day\" /> Month: <input type=\"int\" name=\"month\" /> Year: <input type=\"int\" name=\"year\" /><br /><br />";
		echo "Start Time: <input type=\"text\" name=\"startTime\" /><br /><br />";
		echo "End Time: <input type=\"text\" name=\"endTime\" /><br /><br />";
		
		
		echo "<input type=\"submit\" value=\"Submit New Event and RSS\" /><br/><br/>";
	
	
			
		// That's nice, user wants to create new feed with new event. But lets check if user has filled in all information
		If(empty($_POST['rsstitle']) OR empty($_POST['rssdes']) OR empty($_POST['eventTitle']) OR empty($_POST['day']) OR empty($_POST['month']) OR empty($_POST['year']) ){
			
			if(isset($_GET['try']))
			// User hasn't filled it all in!
			echo 'RSS Title RSS Description Event Title and Event Date is required!';
		
		}
		
		else {
	
			// User filled it all in!
	
			// Make variables save with addslashes 
			$eventTitle = addslashes($_POST['eventTitle']);
			$eventDescription = addslashes($_POST['eventDescription']);
			$eventLocation = addslashes($_POST['eventLocation']);
			$day = addslashes($_POST['day']);
			$month = addslashes($_POST['month']);
			$year = addslashes($_POST['year']);
			$startTime = addslashes($_POST['startTime']);
			$endTime = addslashes($_POST['endTime']);
			
			
			//get userID and store into array to avoid resource ID #7 error
			$query = mysql_query("SELECT id FROM login 
		   	WHERE username = '" . $_SESSION['simcal_username'] . "'") or die(mysql_error());
			$result = mysql_fetch_array($query);  
			//store userID of logged in user to event
			$userID = $result[0];	  
			
			
			//create RSS Feed***********************************
			
			// Make variables save with addslashes and md5
			$rsstitle = addslashes($_POST['rsstitle']);
			$rssdes = addslashes($_POST['rssdes']);
			$date = date("Y-m-d");
	
			// Search for a combination
			$query = mysql_query("INSERT INTO rssfeed (title, des, creator, creation_date) VALUES ('" . $rsstitle . "' , '" . $rssdes . "', '" . $_SESSION['simcal_username'] . "', '" . $date . "')") or die(mysql_error());
			
			createRss(mysql_insert_id(), $rsstitle, $rssdes, $_SESSION['simcal_username'], $date);
			//***************************************************
			
			
			//insert event
			mysql_query("INSERT INTO event (title, des, location, day, month, year, start_time, end_time, feed_id, user_id) VALUES ('" .		 $eventTitle . "' , '" . $eventDescription . "', '" . $eventLocation . "', '" . $day . "', '" . $month . "', '" . $year . "', '" . $startTime . "' , '" . $endTime . "' , '" . mysql_insert_id() . "' , '" . $userID . "')") or die(mysql_error());
			
			
				echo "<br/>New RSS Feed successfully created and new Event Published to RSS Feed. <br /><br />";
			}
					
	}
	?>
	</div>
	<!--end of right col-->
	
	
	</div>
	<!-- End of main frame -->
	


<!--
CREATE TABLE `event` (`ID` INT( 9 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`title` VARCHAR( 100 ) NOT NULL ,`des` VARCHAR( 100 ) NULL ,`location` VARCHAR( 3 ) NULL ,`day` INT( 3 ) NOT NULL ,`month` INT( 3 ) NOT NULL ,`year` INT( 3 ) NOT NULL ,`start_time` TIME NULL ,`end_time` TIME NULL ,`feed_id` INT( 9 ) NULL ,`user_id` INT( 9 ) NULL)


CREATE TABLE `rssfeed` ( `rss_id` int(9) NOT NULL auto_increment, `title` varchar(100) NOT NULL, `des` varchar(400) NOT NULL, `creator` varchar(100) NOT NULL, `creation_date` date NOT NULL, PRIMARY KEY (`rss_id`) ) ENGINE=MyISAM ;

-->

	<?php 
	//creating blank RSS feed
	if(isset($_GET['submit'])) {
	
		// That's nice, user wants to login. But lets check if user has filled in all information
		If(empty($_POST['rsstitle']) OR empty($_POST['rssdes'])) {
		
			// User hasn't filled it all in!
			echo '<br />Please fill in all fields!';
		
		} else {
	
			// User filled it all in!
	
			// Make variables save with addslashes and md5
			$rsstitle = addslashes($_POST['rsstitle']);
			$rssdes = addslashes($_POST['rssdes']);
			$date = date("Y-m-d");
	
			// Search for a combination
			$query = mysql_query("INSERT INTO rssfeed (title, des, creator, creation_date) VALUES ('" . $rsstitle . "' , '" . $rssdes . "', '" . $_SESSION['simcal_username'] . "', '" . $date . "')") or die(mysql_error());
			
			
			createRss(mysql_insert_id(), $rsstitle, $rssdes, $_SESSION['simcal_username'], $date);
			
				
		
		}	
	}
	
	?>


</div>
<?php include_once "footer.php" ?>