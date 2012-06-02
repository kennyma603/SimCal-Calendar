<?php

////////////////////////////////////////////////////
//
// 
// 
//
// 
//
////////////////////////////////////////////////////

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
include_once "header.php";
include_once "menu.php"; 
?>

<!-- Beginning of main frame -->
<div id="frame">

<script>
		YAHOO.namespace("example.calcontainer");

		function init() {
			// Instantiate a Panel from markup
			YAHOO.example.calcontainer.panelCal = new YAHOO.widget.Panel("panelCal", { width:"200px", visible:false, constraintoviewport:true } );
			YAHOO.example.calcontainer.panelCal.render();


			YAHOO.util.Event.addListener("showCal", "click", YAHOO.example.calcontainer.panelCal.show, YAHOO.example.calcontainer.panelCal, true);
		}

		YAHOO.util.Event.addListener(window, "load", init);
</script>


	<script type="text/javascript">
		YAHOO.namespace("example.calendar");
	
		YAHOO.example.calendar.init = function() {
	
			function handleSelect(type,args,obj) { 
			    var dates = args[0]; 
			    var date = dates[0]; 
			    var year = date[0], month = date[1], day = date[2]; 
			    document.location.href = "weeklyview.php?day=" + day + "&month=" + month + "&year=" + year + "&mode=dayview";
			};
	
			YAHOO.example.calendar.cal1 = new YAHOO.widget.Calendar("cal1","cal1Container");
	
			YAHOO.example.calendar.cal1.selectEvent.subscribe(handleSelect, YAHOO.example.calendar.cal1, true); 
	
			YAHOO.example.calendar.cal1.render();
		}
	
		YAHOO.util.Event.onDOMReady(YAHOO.example.calendar.init);
	</script>
	
	
	
	<div style="clear:both;">
	<div id="colorScheme">Color scheme: </div>
	<div class="LabelList" style="display:inline;">
	<div class="scheduleLabel">Personal Schedule</div>

<?php

$ID = getID($_SESSION['simcal_username']);
$FeedIDs = getSubscribedFeedID($ID);

foreach ($FeedIDs as $feedID)
{
	$style = getStyle(array_search($feedID, $FeedIDs));
	echo '<div class="scheduleLabel" style="' . $style . '">' . getFeedTitle($feedID) . " Feed </div>";
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

echo  	'<br /><div id="calcontainer" style="margin-left:300px;">
			<div>' .
			'<div class="bigtitle" style="display:inline;">' . "Events for week of  $day/$month/$year "
				. '<img id="showCal"src="images/dateicon.png" alt="Change the date" /></div>
			</div>
		
			<div id="panelCal">
				<div id="cal1Container"></div>
			</div>
		</div><br /><br />';
	
include_once "getdayevent.php";
getWeeklyView($ID, $day, $month, $year,$FeedIDs);
?>

</div>	
<!-- End of main frame -->

<?php include_once "footer.php" ?>