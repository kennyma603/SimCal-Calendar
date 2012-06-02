<?php

/**
 * @author darius law
 * @copyright 2008
 */
?>
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="G7" />
	<title>simCal</title>

	<link rel="stylesheet" type="text/css" href="yui/build/menu/assets/skins/sam/menu.css" />
	<script type="text/javascript" src="yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="yui/build/container/container_core-min.js"></script>
	<script type="text/javascript" src="yui/build/menu/menu-min.js"></script>
	
	<!-- files for YUI Calendar -->
	<link rel="stylesheet" type="text/css" href="yui/build/fonts/fonts-min.css" />
	<link rel="stylesheet" type="text/css" href="yui/build/calendar/assets/skins/sam/calendar.css" />
	<script type="text/javascript" src="yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="yui/build/calendar/calendar-min.js"></script>
	
	<!-- files for YUI Panel -->
	<link rel="stylesheet" type="text/css" href="yui/build/container/assets/skins/sam/container.css" />
	<script type="text/javascript" src="yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="yui/build/dragdrop/dragdrop-min.js"></script>
	<script type="text/javascript" src="yui/build/container/container-min.js"></script>
	
	<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body class="yui-skin-sam">

<?php

include_once "db_connect.php";
session_start();
include_once "check_session.php";
include_once "meta.php";
include_once "header.php";
include_once "menu.php"; 
?>

<!-- Beginning of main frame -->
<div id="frame">
	<!-- Beginning of left column -->
	<div id="leftcol2">
	
		<div class="pinkbox">

		<h1>Add Event to Existing RSS</h1>
		<p>Please select a RSS Feed to add your selected event to</p>
		
	
		</div>
	
	</div>
	<!-- End of left column -->


	<!-- Beginning of right column -->	
	<div id="rightcol2">

	<?php
	//get event ID info
	if(isset($_GET['event_id']))
	{	
		
		$query = mysql_query("SELECT * FROM event
		  WHERE ID = '" . $_GET['event_id'] . "'") or die(mysql_error());
		$result = mysql_fetch_array($query);
		
		echo '<br/>';
		echo '<h1>Add "' .  $result['title'] . '" to a RSS Feed</h1>';
	
	echo
	'<label for="heading">
	Please select one of your existing RSS feeds to add your event to</label>
	<br />
	<br />	
	<form action="addtorss.php?event_id=' .  $_GET['event_id'] . '&try=true" class="niceform" method="post">
	
	<label for="broadcast">Your created Feeds:</label><br /> ';

	
	//Generate list of RSS feeds out there to display drop down list to add event to an existing RSS
	$query = mysql_query("SELECT rss_id, title FROM rssfeed WHERE creator= '" . $_SESSION['simcal_username'] . "' ORDER BY title ASC");
/*	echo "<input type=\"radio\" name=\"rssFeed\" id=\"option1\" value=\"none\" checked=\"checked\" /><label for=\"option1\">None - Do not broadcast</label><br />";*/
	
	//MULTIPLE SELETION 	//echo "<select multiple=\"yes\" name=rssFeed>";
//	echo "<select name=rssFeed id=\"mySelect2\" class=\"width_160\">";
	// printing the list box select command
	$i=1;
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
}//end if click username




//try to submit request to be friend
if (isset($_GET['try']) AND isset($_GET['event_id'])) 
{

			// That's nice, user wants to login. But lets check if user has filled in all information
		if(empty($_POST['rssFeed'])) 
		{
		
			// User hasn't filled it all in!
			echo '<div class="error">Please select a RSS Feed!</div>';
		
		} 
		else 
		{
			
			//get event details
			$query = mysql_query("SELECT ID FROM event
		  	WHERE ID = '" . $_GET['event_id'] . "'") or die(mysql_error());
			
			$eventresult = mysql_fetch_array($query); 
			
		
			//check if we need to post to an existing RSS FEED, if so we store feedID as the ID of rss feed selected, else stored as 0

		//insert event
		mysql_query("UPDATE event SET feed_id = '" . $_POST['rssFeed'] . "' WHERE ID = '" .  $_GET['event_id'] . "'") or die(mysql_error()); 
		echo "<br/><div class=\"success\">Event was added to the selected RSS Feed </div><br /><br />";
		
		
			echo '<script type="text/javascript">';
			echo "setTimeout('Redirect()',1000);";
			echo "\nfunction Redirect()";
			echo "{\n";
			echo "\tlocation.href = 'monthlyview.php';\n";
			echo "}\n";
			echo "</script>\n";
		}
			
			
	}//end try	  
	
			
			

?>
</div><!--end right col-->
</div>
</body>
<?php
include "footer.php";
?>
