<?php

include_once "db_connect.php";
session_start();
include_once "check_session.php";
include_once "meta.php";
include_once "header.php";
include_once "menu.php"; 
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

<!-- Beginning of main frame -->
<div id="frame">
	<!-- Beginning of left column -->
	<div id="leftcol2">
	
		<div class="pinkbox">
<!--		<h1>Friend Request</h1>
		<p>To request to view a friend's calendar simply send them a <a href ="require_access.php">request!</a></p>  -->
		
		<h1>Friends List</h1>
		<p>View your Friend's calendar by selecting one of your friends below</p>
		
	
		<?php 
		//query friends to list
		$query = mysql_query("SELECT username FROM share
		WHERE grant_access = '" . $_SESSION['simcal_username'] . "' AND activation='1'") 
		or die(mysql_error());
		
		echo '<p><br/><br/>  ';
		while($search_row = mysql_fetch_array($query))
		{
			echo '<a href="require_access.php?username=' . $search_row['username'] . '"><h4>   ' . $search_row['username'] . '   </h4></a>';
		}
		echo '</p>';
		?>
		
		</div>
	
	
	</div>
	<!-- End of left column -->


	<!-- Beginning of right column -->	
	<div id="rightcol2">

<h1>Friend Request</h1>
<p>Send a message to your friend to gain a permission to access your friend's calendar.  
Please enter their username:</p>
<p>&nbsp;</p>
<form action="require_access.php?try=true" method="post">
  <p>
  <span class="textFormat">Friend Name:
  <input name="friend_name" type="text"/>
  <input type="submit" name="friend" value="Send Request"/>
  </span>
  </p> 
</form>

<?php
//if a friend is clicked, show their calendar
if(isset($_GET['username']))
{
	
	echo '<br/>';
	echo '<h1>' .  $_GET['username'] . '\'s Calendar for this Month</h1>';
	//echo '<iframe id="frame1" width=700 height=500 src="monthlyview.php"></iframe>';
	?>
	<!-- Beginning of main frame -->

	
		<script type="text/javascript">
			YAHOO.namespace("example.calendar");
		
			YAHOO.example.calendar.init = function() {
		
				function handleSelect(type,args,obj) { 
				    var dates = args[0]; 
				    var date = dates[0]; 
				    var year = date[0], month = date[1], day = date[2]; 
				    document.location.href = "monthlyview.php?day=" + day + "&month=" + month + "	&year=" + year + "&mode=dayview";
				};
		
				YAHOO.example.calendar.cal1 = new YAHOO.widget.Calendar("cal1","cal1Container");
		
				YAHOO.example.calendar.cal1.selectEvent.subscribe(handleSelect, YAHOO.example	.calendar.cal1, true); 
		
				YAHOO.example.calendar.cal1.render();
			}
		
			YAHOO.util.Event.onDOMReady(YAHOO.example.calendar.init);
		</script>
		
		
		<script>
			YAHOO.namespace("example.calcontainer");
	
			function init() {
				// Instantiate a Panel from markup
				YAHOO.example.calcontainer.panelCal = new YAHOO.widget.Panel("panelCal", { width:	"200px", visible:false, constraintoviewport:true } );
				YAHOO.example.calcontainer.panelCal.render();
	
	
				YAHOO.util.Event.addListener("showCal", "click", YAHOO.example.calcontainer	.panelCal.show, YAHOO.example.calcontainer.panelCal, true);
			}
	
			YAHOO.util.Event.addListener(window, "load", init);
	</script>	
	
	
		<div style="clear:both;">
		<div id="colorScheme">Color scheme: </div>
		<div class="LabelList" style="display:inline;">
		<div class="scheduleLabel">Personal Schedule</div>
	
	<?php
	
	$ID = getID($_GET['username']);
	$FeedIDs = getSubscribedFeedID($ID);
	
	foreach ($FeedIDs as $feedID)
	{
		$style = getStyle(array_search($feedID, $FeedIDs));
		echo '<div class="scheduleLabel" style="' . $style . '">' . getFeedTitle($feedID) . "	 Feed </div>";
	}
	
	?>
		</div></div>
		<br /><br />
	<?php 

	if(!isset($_GET["day"])) {
	$year = strtok(date("Y-m-d"), "-");
	$month = strtok("-");
	$day = strtok("-");
	}

	else{
		$day = $_GET["day"];
		$month = $_GET["month"];
		$year = $_GET["year"];
	
	}
	
	echo '<br />';
	
	
	echo  	'<div id="calcontainer" style="margin-left:300px;">
				<div>' .
				'<div class="bigtitle" style="display:inline;">' . "Calendar for $month/$year "
					. '</div>
				</div>
			
				<div id="panelCal">
					<div id="cal1Container"></div>
				</div>
			</div><br /><br />';
	
	include_once "getdayevent.php";
	Friendcalendar($ID,$year,$month,0,$day,$FeedIDs);

	?>

	<!-- End of main frame -->
	
	<?php
}//end if click username




//try to submit request to be friend
if (isset($_GET['try'])) 
{
			// That's nice, user wants to login. But lets check if user has filled in all information
		if(empty($_POST['friend_name'])) 
		{
		
			// User hasn't filled it all in!
			echo '<div class="error">Please enter a username!</div>';
		
		} 
		else {
	
			// User filled it all in!
	
			// Make variables save with addslashes and md5
			$username = addslashes($_POST['friend_name']);
			
				// Search for a combination
			$query = mysql_query("SELECT username FROM login
						   WHERE username = '" . $username . "'") or die(mysql_error());
	
			// If the user_id is empty no combination was found
			$query = mysql_query("SELECT COUNT(id) FROM login 
					   			WHERE username = '" . $username . "'") 
								   or die(mysql_error());
					
			list($count) = mysql_fetch_row($query);
			
			if($count == 0) 
			{
				echo '<div class="error">No such username found.</div>';
			}
			else
			{
				//first check if request has been made
				$query = mysql_query("SELECT COUNT(username) FROM share WHERE username = '" . $username . "' AND grant_access = '" . $_SESSION['simcal_username'] . "'") or die(mysql_error());
				list($count) = mysql_fetch_row($query);
				
				if($count != 0)
				{	//request has been made already
					echo '<div class="error">You have already requested to be friends</div>';
				}
				
				else //request has not been made, make a new request
				{
				$query = mysql_query("INSERT INTO share (username, grant_access, activation) VALUES ('" . $username . "','" . $_SESSION['simcal_username'] . "', '0')") or die(mysql_error());
				echo '<div class="success">Message Sent to ' . $username . '</div><br/><br/>';
				}
			}
		}
			
	
	
	/*
	$fname = $_POST['friend_name'];
	$user = $_SESSION['simcal_username'];
	$test_exist = 0;
	$query  = "SELECT * FROM share";
	$result = mysql_query($query);

	while($row = mysql_fetch_array($result, MYSQL_ASSOC))
   		 if (($row['username'] == $fname) && ($row['grant_access'] == $user))
			$test_exist = 1;
	
	if(empty($fname))
		$test_exist = 1;
		
	if($fname == $user)
		$test_exist = 1;
		
	if ($test_exist == 0)
	{
		mysql_select_db('testdb');
		$query = "INSERT INTO share (username, grant_access, activation) 
		VALUES ('$fname','$user', 0)";
		mysql_query($query) or die('Error, insert share table failed');
		$query = "FLUSH PRIVILEGES";
		mysql_query($query) or die('Error, insert share table failed');
		echo '<h4>Message Sent!</h4>';
	}
	*/
}
?>
</div><!--end right col-->
</div>
</body>
<?php
include "footer.php";
?>
