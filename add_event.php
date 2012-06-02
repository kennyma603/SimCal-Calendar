<?php
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
	
	

	<!-- End of left column -->


	<!-- Beginning of right column -->	
	<div id="rightcol2">
	
	<label for="heading">
	Please fill in the event details:</label>
	<br />
	<br />	
	<form action="add_event.php?submit=true" class="niceform" method="post">
	<span>*</span><label for="title">Event Title:</label> <input type="text" name="eventTitle" id="title" size="20"/><br /><br />
	<label for="description">Description:</label> <textarea id="description" name="eventDescription" rows="10" cols="30"></textarea><br />
	<label for="location">Location:</label> <input type="text" name="eventLocation" id="location" size="20"/><br /><br />
	<!--
Day: <input type="int" name="day" />Month: <input type="int" name="month" />Year: <input type="int" name="year" /><br /><br />
-->
	
	<span>*</span><label for="day">Day</label>
		<input type="text" name="day" id="day" size="2" />
		
		
	<span>*</span><label for="month">Month</label>
		<input type="text" name="month" id="month" size="2" />	
		
	<span>*</span><label for="year">Year</label>
		<input type="text" name="year" id="year" size="4" />	(pick a date from the calendar on the left)	
	
		
	<br/><br />


	<label for="startTime">Start Time</label>
		 <input id="startTime" type="text" name="startTime" size="6"  /><br /><br />
		 
	<label for="endTime">End Time</label>
		 <input type="text" name="endTime" size="6" id="endTime" /><br /><br />
	
	<label for="broadcast">Broadcast to existing RSS feed?</label><br />  

	<?php
	//Generate list of RSS feeds out there to display drop down list to add event to an existing RSS
	$query = mysql_query("SELECT rss_id, title FROM rssfeed WHERE creator= '" . $_SESSION['simcal_username'] . "' ORDER BY title ASC");
	echo "<input type=\"radio\" name=\"rssFeed\" id=\"option1\" value=\"none\" checked=\"checked\" /><label for=\"option1\">None - Do not broadcast</label><br />";
	
	//MULTIPLE SELETION 	//echo "<select multiple=\"yes\" name=rssFeed>";
//	echo "<select name=rssFeed id=\"mySelect2\" class=\"width_160\">";
	// printing the list box select command
	$i=2;
	//echo "<option value= \"none\">         </option>";
	while($nt=mysql_fetch_array($query)){//Array or records stored in $nt
//	echo "<option value= " . $nt[rss_id] . " >" . $nt[title] . " </option>";
	echo "<input type=\"radio\" name=\"rssFeed\" id=\"option$i\" value=" . $nt['rss_id'] . " /><label for=\"option$i\">" . $nt['title'] . "</label><br />";
	$i++;
	/* Option values are added by looping through the array */
	}
	//echo "</select>";// Closing of list box
	echo "<br />";
	//*** NEED TO CHECK $_POST['rssFeed']; TO FIND WHICH FEED TO BROADCAST TO, if none, then dont, else it will store RSS_ID number**
	?> 

	<br />
	<input type="submit" value="Submit" />
	
		
	
	</form>
	
	
	
	<?php 
	if(isset($_GET['submit'])) {
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
		// That's nice, user wants to add event. But lets check if user has filled in all information
		if(!isset($_POST['eventTitle']))
		{
			// User hasn't filled it all in!
			echo '<br /><div class="error">Event Title are required!  Please try again.</div><br/><br/>';
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
		else 
		{
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
			
	
			//check if we need to post to an existing RSS FEED, if so we store feedID as the ID of rss feed selected, else stored as 0
			if($_POST['rssFeed'] == "none")
			{ 
				$feedID = 0;
				//insert event
				mysql_query("INSERT INTO event (title, des, location, day, month, year, start_time, end_time, feed_id, user_id) VALUES ('" .		 $eventTitle . "' , '" . $eventDescription . "', '" . $eventLocation . "', '" . $day . "', '" . $month . "', '" . $year . "', '" . $startTime . "' , '" . $endTime . "' , '" . $feedID . "' , '" . $userID . "')") or die(mysql_error());
			
			
				echo "<br/><div class=\"success\">New event successfully created.</div> <br /><br />";
				
			}
			
			//else event is added to rssFEED
			else
			{
					//insert event
				mysql_query("INSERT INTO event (title, des, location, day, month, year, start_time, end_time, feed_id, user_id) VALUES ('" . $eventTitle . "' , '" . $eventDescription . "', '" . $eventLocation . "', '" . $day . "', '" . $month . "', '" . $year . "', '" . $startTime . "' , '" . $endTime . "' , '" . $_POST['rssFeed'] . "' , '" . $userID . "')") or die(mysql_error());
			addEvent($_POST['rssFeed'],$eventTitle,$eventDescription,$eventLocation ,$day,$month,$year,$startTime,$endTime,$userID);	
				echo "<br/><div class=\"success\">New event successfully created successfully.  Event was added to the selected RSS Feed. </div><br /><br />";
			}
			
		}	
	}//end submit
	
	?>
	</div>
	<!-- End of right column -->

	
	
</div>	
<!-- End of main frame -->
<?php include_once "footer.php" ?>



