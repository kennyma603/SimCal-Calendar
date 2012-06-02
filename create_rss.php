<?php

include_once "db_connect.php";
session_start(); 
include_once "check_session.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="G7" />
	<title>simCal</title>
		<link rel="stylesheet" type="text/css" href="yui/build/menu/assets/skins/sam/menu.css" />
	<script type="text/javascript" src="yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="yui/build/container/container_core-min.js"></script>
	<script type="text/javascript" src="yui/build/menu/menu-min.js"></script>
	<link rel="stylesheet" type="text/css" href="yui/build/fonts/fonts-min.css" />
	<link rel="stylesheet" type="text/css" href="yui/build/calendar/assets/skins/sam/calendar.css" />
	<script type="text/javascript" src="yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="yui/build/calendar/calendar-min.js"></script>
<script language="javascript" type="text/javascript" src="niceforms.js"></script>
<style type="text/css" media="screen">@import url(niceforms-default.css);</style>
		<link href="style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="clock/clockpick.1.2.4.css" type="text/css" />

<script type="text/javascript" src="clock/jquery-1.2.6.pack.js"></script>
<script type="text/javascript" src="clock/jquery.clockpick.1.2.4.js"></script>
<script type="text/javascript" src="clock/jquery.bgiframe.pack.js"></script>
<script type="text/javascript">
$(document).ready(function() {

	function cback( time ) {
		var c = $("<span>Time Entered: " + time + "</span>");
		c
		.hide()
		.insertAfter( this )
		.fadeIn(1000)
		.fadeOut(1000);
	}
			
	$("select").not("#test").change(function() {
		binder();
	});
	
	function binder() {
		var opts = {starthour : 1,endhour : 23, military : true};
		opts.useBgiframe = true;
		$("select").not("#test").each(function() {
			opts[this.id] = this.id == 'event' || this.id == 'layout'
								? $(this).val()
								: eval( $(this).val() );
		});	
		$("#startTime").unbind().clockpick(opts, cback);
		$("#endTime").unbind().clockpick(opts, cback); 
	}
	
	binder();
});

</script>
	
	
</head>

<body class="yui-skin-sam">

<?php
include_once "header.php";
include_once "menu.php"; 
?>


<!-- Script for YUI Calendar -->
<script type="text/javascript">
	YAHOO.namespace("example.calendar");

	YAHOO.example.calendar.init = function() {

		function handleSelect(type,args,obj) {
			var dates = args[0]; 
			var date = dates[0];
			var year = date[0], month = date[1], day = date[2];
			
			var dayText = document.getElementById("day");
			dayText.value = day;
			
			var monthText = document.getElementById("month");
			monthText.value = month;
			
			var yearText = document.getElementById("year");
			yearText.value = year;
			
		}


		
		YAHOO.example.calendar.cal1 = new YAHOO.widget.Calendar("cal1","cal1Container");
		YAHOO.example.calendar.cal1.selectEvent.subscribe(handleSelect, YAHOO.example.calendar.cal1, true);
		YAHOO.example.calendar.cal1.render();

		YAHOO.util.Event.addListener("dates", "submit", handleSubmit);
	}

	YAHOO.util.Event.onDOMReady(YAHOO.example.calendar.init);
</script>	



<!-- Beginning of main frame -->
<div id="frame">
<!-- Beginning of left column -->

<div id="leftcol2">
	<div class="pinkbox">
	<h1>Select event date</h1>
	<div id="cal1Container"></div>
	</div>
	<br />
	<div class="bluebox">
	<h1>Note</h1>
	<p>You must fill in the areas with a <span>*</span> beside it. </p>
	</div>
</div>
<!--end of left column-->
<!-- beginning right column-->
<div id="rightcol2">

	<?php
	if(isset($_GET['new']) OR isset($_GET['submit'])) {

	echo "Please fill in the form: <br/><br/>";

	echo '<form action="create_rss.php?submit=true" class="niceform" method="post">';
	echo '<span>*</span><label for="textinput">RSS Feed Title:</label><br /> <input type="text" id="textinput" name="rsstitle" size="26"/><br /><br />';
	echo '<span>*</span><label for="textinput">RSS Description:</label><br /><textarea id="textareainput" name="rssdes" rows="10" cols="26"></textarea>';
	/*<!--Event: 
	<select name="event">
	<option value="none">Create Empty RSS Feed/option>
	<option value="newEvent">Add New Event</option>
	<option value="existingEvent">Add Existing Event</option>
	</select>
	<script type="text/javascript"> 
	document.location.href = "create_rss.php?submit=true&event=" + $_POST['event'];
	</script>-->*/
	echo "<input type=\"submit\" value=\"Create Blank RSS\" /><br /><br />";

	echo "<p>Or, you can</p><br /> <p><input type=\"button\" value=\"Add New Event to New RSS\" onclick=\"window.location.href='create_rss.php?eventNew=true'\"/></p>";
	echo "</form><br />";	
	}
	?>


	<?php
	//user wants to add new event to this RSS before creating
	if(isset($_GET['eventNew'])) {
			
		//submit set to false, since event is added to RSS, hence try=true
		//submit=true only if its for create RSS blank with no events
		echo "<form action=\"create_rss.php?eventNew=true&try=true\" class=\"niceform\" method=\"post\">";//&submit=false
		
		echo "	Please fill in the form: <br/><br />";	
		echo "<span>*</span><label for=\"rsstitle\">RSS Feed Title:</label> <input type=\"text\" name=\"rsstitle\" id=\"rsstitle\" size=\"20\" /><br /><br />";
		echo "<span>*</span><label for=\"rssdes\">RSS Description:</label> <input type=\"text\" name=\"rssdes\" id=\"rssdes\" size=\"20\" /><br /><br /><br /><br />";		
		echo "Please fill in the event details:";
		echo "<br /><br />";		
		echo "<span>*</span><label for=\"title\">Title:</label> <input type=\"text\" name=\"eventTitle\" id=\"title\" size=\"20\" /><br /><br />";
		echo "<label for=\"des\">Description:</label> <input type=\"text\" name=\"eventDescription\" id=\"des\" size=\"20\" /><br /><br />";
		echo "<label for=\"location\">Location:</label> <input type=\"text\" name=\"eventLocation\" id=\"location\" size=\"20\" /><br /><br />";
		echo "<span>*</span><label for=\"day\">Day:</label> <input type=\"int\" name=\"day\" id=\"day\" size=\"2\" />
		<span>*</span><label for=\"month\">Month:</label> <input type=\"int\" name=\"month\" id=\"month\" size=\"2\" /> 
		<span>*</span><label for=\"year\">Year:</label> <input type=\"int\" name=\"year\" id=\"year\" size=\"4\" /><br /><br />";
		echo "<label for=\"startTime\">Start Time:<label> <input type=\"text\" name=\"startTime\" id=\"startTime\" size=\"5\" /><br /><br />";
		echo "<label for=\"endTime\">End Time: <input type=\"text\" name=\"endTime\" id=\"endTime\" size=\"5\" /><br /><br />";
		
		
		echo "<input type=\"submit\" value=\"Submit New Event and RSS\" /><br/></form><br/>";
	
	
	if(isset($_GET['try']))
	{
		if(substr($_POST['endTime'],1,1)==":")
		{
			$end_Time = substr($_POST['endTime'],0,1);
			$end_Min = substr($_POST['endTime'],2,2);
		}
		else
		{
			$end_Time = substr($_POST['endTime'],0,2);
			$end_Min = substr($_POST['endTime'],3,2);
		}
		if(substr($_POST['startTime'],1,1)==":")
		{
			$start_Time = substr($_POST['startTime'],0,1);
			$start_Min = substr($_POST['startTime'],2,2);
		}
		else
		{
			$start_Time = substr($_POST['startTime'],0,2);
			$start_Min = substr($_POST['startTime'],3,2);
		}
			
		// That's nice, user wants to create new feed with new event. But lets check if user has filled in all information
		if(empty($_POST['rsstitle']) OR empty($_POST['rssdes']) OR empty($_POST['eventTitle']) OR empty($_POST['day']) OR empty($_POST['month']) OR empty($_POST['year']) ){
			
			if(isset($_GET['try']))
			// User hasn't filled it all in!
			
			echo '<div class="error">RSS Title RSS Description Event Title and Event Date are required!<div>';
		
		}
		else if(isset($_POST['day']) AND isset($_POST['month']) AND isset($_POST['year']) AND (check_date($_POST['day'], $_POST['month'], $_POST['year']) == 1))
		{
			// User hasn't filled it all in!
			echo '<br /><div class="error">Invalid Date!  Please try again.</div><br/><br/>';
		}
		else if((empty($_POST['startTime']) AND !empty($_POST['endTime'])) OR (!empty($_POST['startTime']) AND empty($_POST['endTime'])))
		{
			// User hasn't filled it all in!
			echo '<br /><div class="error">You Must either have both start and end time filled out or leave both start and end time blank!  Please try again.</div><br/><br/>';
		}
		else if(($end_Time < $start_Time) OR (($end_Time == $start_Time) AND ($end_Min < $start_Min)))
		{
			// Time conflict...
			echo '<br /><div class="error">Your Start Time must be earlier than your End Time.</div><br/><br/>';
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
			
			$theFeed = mysql_insert_id();
			//insert event
			mysql_query("INSERT INTO event (title, des, location, day, month, year, start_time, end_time, feed_id, user_id) VALUES ('" .		 $eventTitle . "' , '" . $eventDescription . "', '" . $eventLocation . "', '" . $day . "', '" . $month . "', '" . $year . "', '" . $startTime . "' , '" . $endTime . "' , '" . $theFeed . "' , '" . $userID . "')") or die(mysql_error());
			
			mysql_query("INSERT INTO subscribe(user_id, feed_id) VALUES(" . $userID . ", " . $theFeed . ")");
			
				echo "<br/><div class=\"success\">New RSS Feed successfully created and new Event Published to RSS Feed. </div><br /><br />";
			}
					
	}
	}
	?>

	


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
			echo '<br /><div class="error">Please fill in all fields!</div>';
		
		} else {
	
			// User filled it all in!
	
			// Make variables save with addslashes and md5
			$rsstitle = addslashes($_POST['rsstitle']);
			$rssdes = addslashes($_POST['rssdes']);
			$date = date("Y-m-d");
	
			// Search for a combination
			$query = mysql_query("INSERT INTO rssfeed (title, des, creator, creation_date) VALUES ('" . $rsstitle . "' , '" . $rssdes . "', '" . $_SESSION['simcal_username'] . "', '" . $date . "')") or die(mysql_error());
			
			
			createRss(mysql_insert_id(), $rsstitle, $rssdes, $_SESSION['simcal_username'], $date);
			
			mysql_query("INSERT INTO subscribe(user_id, feed_id) VALUES(" . getID($_SESSION['simcal_username']) . ", " . mysql_insert_id() . ")");
			
				
		
		}	
	}
	
	?>

	</div>
	<!--end of right col-->
	
	
	</div>
	<!-- End of main frame -->

<?php include_once "footer.php" ?>