<?php

/**
 * @author 3ddown.com
 * @copyright 2008
 */

include_once "db_connect.php";
session_start(); 
include_once "check_session.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>


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
		var c = $("<span style='color:red'>Time Entered: " + time + "</span>");
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
	<?php
	if(!isset($_GET['rss_id']) AND !isset($_GET['event_id']) AND !isset($_GET['editrss']))
	{
	?>
		<div id="leftcol2">
	
		<div class="pinkbox">
		<h1>Edit RSS and Events</h1>
		Your RSS Feeds are shown on the right side.  Please select an option
		<br /><br />
		<div id="cal1Container"></div>
		</div>
		<br />
		</div>
	<?php
	}
	else if(isset($_GET['rss_id']) AND !isset($_GET['event_id']) AND !isset($_GET['editrss']))
	{
	?>
		<div id="leftcol2">
	
		<div class="pinkbox">
		<h1>Select Event to Edit</h1>
		<div id="cal1Container"></div>
		</div>
		<br />
		</div>
	<?php
	}
	else if(isset($_GET['editrss']) AND isset($_GET['rss_id']) AND !isset($_GET['event_id']))
	{
	?>
		<div id="leftcol2">
	
		<div class="pinkbox">
		<h1>Edit Rss Name and Description</h1>
		<div id="cal1Container"></div>
		</div>
		<br />
		<div class="bluebox">
		<h1>Note</h1>
		<p>You must fill in the areas with a <span>*</span> beside it. </p>
		</div>
		
		</div>
	<?php
	}
	else if(!isset($_GET['rss_id']) AND isset($_GET['event_id']) AND !isset($_GET['editrss']))
	{
	?>
		<div id="leftcol2">
	
		<div class="pinkbox">
		<h1>Select event date</h1>
		<div id="cal1Container"></div>
		</div>
		<br />
		<div class="bluebox">
		<h1>Note</h1>
		<p>You must fill in the areas with a <span>*</span> beside it. </p>
		<p>You Must either have both start and end time filled out or have start and end time blank!</p>
		</div>
		
		</div>
	<?php
	}
	?>
	<!-- End of left column -->

	<!-- Beginning of right column -->	
	
<?php
$ID = getID($_SESSION['simcal_username']);

if(!isset($_GET['rss_id']) AND !isset($_GET['event_id']) AND !isset($_GET['editrss']))
{
?>
	<div id="rightcol2">
	<h1>List of RSS Feeds and their Color Scheme </h1>
	<div class="LabelList">
	<div class="LabelList" style="display:inline;">
	<div class="scheduleLabel">Personal Schedule	</div>
	<a href="edit_event.php?rss_id=0">View Events</a>
	<br /><br />

<?php

	$UserFeedIDs = getUserRss();
	foreach ($UserFeedIDs as $feedID)
	{
		$rss_style = getStyle(array_search($feedID, $UserFeedIDs));
		echo '<div class="scheduleLabel" style="' . $rss_style . '">';
		echo getFeedTitle($feedID) . '</div>';
		
		echo '<a href="edit_event.php?rss_id=' . $feedID . '">View Events</a>  |  ';
		echo '<a href="edit_event.php?editrss=true&rss_id='. $feedID . '"> Edit RSS</a>  |  ';
		echo '<a href="delete_feed.php?feed='. $feedID . '"> Delete RSS</a>';
		echo "<br /><br />";
	}
	echo "</div></div>";
	
	
}


else if(isset($_GET['rss_id']) AND !isset($_GET['event_id']) AND !isset($_GET['editrss']))
{
?>
	<div id="rightcol2">
	<h1>List of Events and their Color Scheme             
	<a href="edit_event.php"><img src="images/BackButton.jpg" height="40px" width="60px" /></a></h1>
	<div class="LabelList">
	<div class="LabelList" style="display:inline;">

<?php
	$EventIDs = getEventRss($_GET['rss_id'], $ID);
	foreach ($EventIDs as $eventID)
	{
		$event_style = getStyle(array_search($eventID, $EventIDs));
		echo '<div class="scheduleLabel" style="' . $event_style . '">';
		echo getEventTitle($eventID) . "</div>";
		echo '<a href="edit_event.php?event_id=' . $eventID . '"> Edit event</a>  |  ';
		echo '<a href="delete_event.php?event='. $eventID . '"> Delete event</a>';
		echo "<br /><br />";
	}
	if(empty($EventIDs))
	{
		echo "<br />\n";
		echo "There are no events in this feed.";
	}
	echo "</div></div>";
}	


	
//IF USER EDITING FROM ADMIN CONSOLE, REDIRECT IT BACK TO ADMIN WINDOW	
else if(isset($_GET['editrss']) AND isset($_GET['rss_id']) AND !isset($_GET['event_id']) AND isset($_GET['admin']))
{
		
	$search_result = mysql_query("SELECT * FROM rssfeed WHERE rss_id ='" . $_GET['rss_id'] . "'") or die(mysql_error());
	$rss_info = mysql_fetch_array($search_result);
	
		echo '<div id="rightcol2">';

		echo '<label for="heading">';
		echo 'Please edit the Rss details:</label><br /><br />';
		
		if(empty($_POST['submit']))
		{
			echo '<form action="edit_event.php?editrss=true&rss_id=' . $rss_info['rss_id'] . '&admin=true" class="niceform" method="post">';
		echo '<span>*</span><label for="title">RSS Title:</label> <input type="text" name="rssTitle" id="title"';
		echo ' value="' . $rss_info['title'] . '" size="20"/><br /><br />';
		echo '<span>*</span><label for="description">Description:</label> <textarea id="description" name="rssDescription" rows="10" cols="30">' . $rss_info['des'] . '</textarea><br />';
	
		echo '<input type="hidden" name="rss_submit" value="1" />';
		
		echo '<input type="submit" value="Submit" />';
		}
	
		if(isset($_POST['rss_submit']))
		{
			if(empty($_POST['rssTitle']) OR empty($_POST['rssDescription']))
			{
				// User hasn't filled it all in!
				echo '<br /><div class="error">RSS Title and Description are required!  Please try again.</div><br/><br/>';
			}
			else
			{
				$rssTitle = addslashes($_POST['rssTitle']);
				$rssDes = addslashes($_POST['rssDescription']);
			
				mysql_query("UPDATE rssfeed 
							SET title = '" . $rssTitle . "', des = '" . $rssDes . "'
							WHERE rss_id = '" . $_GET['rss_id'] . "'") or die(mysql_error());
							
				echo "<br/><div class=\"success\">RSS successfully update</div> <br /><br />";
				
				echo '<script type="text/javascript">';
				echo "\n<!--\n";
				echo "setTimeout('Redirect()',1000);";
				echo "\nfunction Redirect()";
				echo "{\n";
				echo "\tlocation.href = 'admin.php?manageFeeds=true';\n";
				echo "}\n";
				echo "\n--></script>\n";
			}
		}
}

//IF USER WANTS TO EDIT RSS from EDIT PAGGE
else if(isset($_GET['editrss']) AND isset($_GET['rss_id']) AND !isset($_GET['event_id']) AND !isset($_GET['admin']) )
{
		
	$search_result = mysql_query("SELECT * FROM rssfeed WHERE rss_id ='" . $_GET['rss_id'] . "'") or die(mysql_error());
	$rss_info = mysql_fetch_array($search_result);
	
		echo '<div id="rightcol2">';
	
		echo '<label for="heading">';
		echo 'Please edit the Rss details:</label><br /><br />';
		
		if(empty($_POST['submit']))
		{
			echo '<form action="edit_event.php?editrss=true&rss_id=' . $rss_info['rss_id'] . '" class="niceform" method="post">';
		echo '<span>*</span><label for="title">RSS Title:</label> <input type="text" name="rssTitle" id="title"';
		echo ' value="' . $rss_info['title'] . '" size="20"/><br /><br />';
		echo '<span>*</span><label for="description">Description:</label> <textarea id="description" name="rssDescription" rows="10" cols="30">' . $rss_info['des'] . '</textarea><br />';
	
		echo '<input type="hidden" name="rss_submit" value="1" />';
		
		echo '<input type="submit" value="Submit" />';
		}
	
		if(isset($_POST['rss_submit']))
		{
			if(empty($_POST['rssTitle']) OR empty($_POST['rssDescription']))
			{
				// User hasn't filled it all in!
				echo '<br /><div class="error">RSS Title and Description are required!  Please try again.</div><br/><br/>';
			}
			else
			{
				$rssTitle = addslashes($_POST['rssTitle']);
				$rssDes = addslashes($_POST['rssDescription']);
			
				mysql_query("UPDATE rssfeed 
							SET title = '" . $rssTitle . "', des = '" . $rssDes . "'
							WHERE rss_id = '" . $_GET['rss_id'] . "'") or die(mysql_error());
							
				echo "<br/><div class=\"success\">RSS successfully update</div> <br /><br />";
				
				echo '<script type="text/javascript">';
				echo "\n<!--\n";
				echo "setTimeout('Redirect()',1000);";
				echo "\nfunction Redirect()";
				echo "{\n";
				echo "\tlocation.href = 'edit_event.php';\n";
				echo "}\n";
				echo "\n--></script>\n";
			}
		}
}
	

//IF USER WANTS TO...Edit Event
else if(!isset($_GET['rss_id']) AND isset($_GET['event_id']) AND !isset($_GET['editrss']))
{	
	$search_result = mysql_query("SELECT * FROM event WHERE ID ='" . $_GET['event_id'] . "'");
	$event_info = mysql_fetch_array($search_result);
	
	

?>	
	<div id="rightcol2">
	
	<label for="heading">
	Please edit the event details:</label>
	<br />
	<br />	
	<?php

	if(empty($_POST['submit']))
	{
		echo '<form action="edit_event.php?event_id=' . $event_info['ID'] . '" class="niceform" method="post">';
		echo '<span>*</span><label for="title">Event Title:</label> <input type="text" name="eventTitle" id="title"';
		echo ' value="' . $event_info['title'] . '" size="20"/><br /><br />';
		echo '<label for="description">Description:</label> <textarea id="description" name="eventDescription" rows="10" cols="30">' . $event_info['des'] . '</textarea><br />';
		echo '<label for="location">Location:</label> <input type="text" name="eventLocation" id="location" ';
		echo 'value="' . $event_info['location'] . '" size="20"/><br /><br />';
	?>
	<!--
Day: <input type="int" name="day" />Month: <input type="int" name="month" />Year: <input type="int" name="year" /><br /><br />
-->
	
	<span>*</span><label for="day">Day</label>
		<input type="text" name="day" id="day" size="2" <?php echo 'value="' . $event_info['day'] . '"'; ?>/>
		
		
	<span>*</span><label for="month">Month</label>
		<input type="text" name="month" id="month" size="2" <?php echo 'value="' . $event_info['month'] . '"'; ?>/>	
		
	<span>*</span><label for="year">Year</label>
		<input type="text" name="year" id="year" size="4" <?php echo 'value="' . $event_info['year'] . '"'; ?>/>	(pick a date from the calendar on the left)	
	
		
	<br/><br />


	<label for="start_time">Start Time</label>
		 <input type="text" id="startTime" name="startTime" size="6" <?php echo 'value="' . $event_info['start_time'] . '"'; ?>/><br /><br />
		 
	<label for="end_time">End Time</label>
		 <input type="text" id="endTime" name="endTime" size="6" <?php echo 'value="' . $event_info['end_time'] . '"'; ?>/><br /><br />

	<br />
	<input type="hidden" name="submit" value="1" />
	<input type="submit" value="Submit" />
	
	</form>
	<?php
	}
	else
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
		
		$todays_date = date( "Ymd" );
		$year = substr($todays_date, 0, 4);
		$month = substr($todays_date, 4, 2);
		$date = substr($todays_date, 6, 2);
				// That's nice, user wants to add event. But lets check if user has filled in all information
		if(!isset($_POST['eventTitle']))
		{
			// User hasn't filled it all in!
			echo '<br /><div class="error">Event Title and Date are required!  Please try again.</div><br/><br/>';
			
			echo '<script type="text/javascript">';
			echo "\n<!--\n";
			echo "setTimeout('Redirect()',1000);";
			echo "\nfunction Redirect()";
			echo "{\n";
			echo "\tlocation.href = 'edit_event.php?event_id=" . $_GET['event_id'] . "';\n";
			echo "}\n";
			echo "\n--></script>\n";
		}
		else if(isset($_POST['day']) AND isset($_POST['month']) AND isset($_POST['year']) AND (check_date($_POST['day'], $_POST['month'], $_POST['year']) == 1))
		{
			// User hasn't filled it all in!
			echo '<br /><div class="error">Invalid Date!  Please try again.</div><br/><br/>';
				
			echo '<script type="text/javascript">';
			echo "\n<!--\n";
			echo "setTimeout('Redirect()',2000);";
			echo "\nfunction Redirect()";
			echo "{\n";
			echo "\tlocation.href = 'edit_event.php?event_id=" . $_GET['event_id'] . "';\n";
			echo "}\n";
			echo "\n--></script>\n";
		}
		else if((empty($_POST['startTime']) AND !empty($_POST['endTime'])) OR (!empty($_POST['startTime']) AND empty($_POST['endTime'])))
		{
			// User hasn't filled it all in!
			echo '<br /><div class="error">You Must either have both start and end time filled out or have start and end time blank!  Please try again.</div><br/><br/>';
			
			echo '<script type="text/javascript">';
			echo "\n<!--\n";
			echo "setTimeout('Redirect()',2000);";
			echo "\nfunction Redirect()";
			echo "{\n";
			echo "\tlocation.href = 'edit_event.php?event_id=" . $_GET['event_id'] . "';\n";
			echo "}\n";
			echo "\n--></script>\n";
		}
		else if(($end_Time < $start_Time) OR (($end_Time == $start_Time) AND ($end_Min < $start_Min)))
		{
			// Time conflict...
			echo '<br /><div class="error">Your Start Time must be earlier than your End Time.</div><br/><br/>';
			
			echo '<script type="text/javascript">';
			echo "\n<!--\n";
			echo "setTimeout('Redirect()',4000);";
			echo "\nfunction Redirect()";
			echo "{\n";
			echo "\tlocation.href = 'edit_event.php?event_id=" . $_GET['event_id'] . "';\n";
			echo "}\n";
			echo "\n--></script>\n";
		}
		else
		{
			$eventTitle = addslashes($_POST['eventTitle']);
			$eventDescription = addslashes($_POST['eventDescription']);
			$eventLocation = addslashes($_POST['eventLocation']);
			$day = addslashes($_POST['day']);
			$month = addslashes($_POST['month']);
			$year = addslashes($_POST['year']);
			$startTime = addslashes($_POST['startTime']);
			$endTime = addslashes($_POST['endTime']);
			
			mysql_query("UPDATE event 
						SET title = '" . $eventTitle . "', des = '" . $eventDescription . "', location = '" . $eventLocation . "', day = '" . $day . "', month = '" . $month . "', year = '" . $year . "', start_time = '" . $startTime . "', end_time = '" . $endTime . "'
						WHERE ID = '" . $_GET['event_id'] . "'") or die(mysql_error());
						
			echo "<br/><div class=\"success\">Event successfully update.</div> <br /><br />";
			
			echo '<script type="text/javascript">';
			echo "\n<!--\n";
			echo "setTimeout('Redirect()',1000);";
			echo "\nfunction Redirect()";
			echo "{\n";
			echo "\tlocation.href = 'userpanel.php';\n";
			echo "}\n";
			echo "\n--></script>\n";
		}
	}
	echo "</div>\n";
	echo "<br />\n";
}
include_once "footer.php" ?>