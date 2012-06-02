<?php

function getDayEvent($ID, $day, $month, $year, $FeedIDs)
{

$event = getEventList($ID, $day, $month, $year); 

if(!empty($event)){
echo '<script type="text/javascript">
		YAHOO.namespace("example.container");

		function init() {';
			


foreach($event as $row)
{
		echo 'YAHOO.example.container.panel' . $row['ID'] . ' = new YAHOO.widget.Panel("panel' . $row['ID'] . '", { width:"320px", visible:false, constraintoviewport:true } );
			YAHOO.example.container.panel' . $row['ID'] . '.render();

			YAHOO.util.Event.addListener("show' . $row['ID'] . '", "click", YAHOO.example.container.panel' . $row['ID'] . '.show, YAHOO.example.container.panel' . $row['ID'] . ', true);';
}	

	echo'	}

		YAHOO.util.Event.addListener(window, "load", init);
</script>


<div id="container">';

	
foreach($event as $row)
{

	
	
    if ($row['start_time'] == "00:00:00" AND $row['end_time'] == "00:00:00") $timeRange = "All Day";
    else $timeRange = substr($row['start_time'], 0, 5). " - " . substr($row['end_time'],0,5);	
	
	if ($row['feed_id'] == 0 OR $row['feed_id'] == NULL) {
        echo '<div id="show' . $row['ID'] . '" class="schedule">'. '<div style="float:left;"><div class="time">' 
		. $timeRange . '</div>' . $row['title'] . '</div>
		<div style="float:right;">' . $row['location']. '</div></div>';
		}
		
	else
	{
		$style = getStyle(array_search($row['feed_id'], $FeedIDs));
		
		echo '<div id="show' . $row['ID'] . '" class="schedule" style="' . $style . '">'
		. '<div style="float:left;"><div class="time">' . $timeRange . '</div>' . $row['title'] . '</div>
		<div style="float:right;">' . $row['location']. '</div></div>';		
	}
echo '
	<div id="panel' . $row['ID'] . '">
		<div class="hd">Event Details</div>
		<div class="bd">
			<p>Title: ' . $row['title'] . '</p>
			<p>Description: ' . $row['des'] . '</p>
			<p>Location: ' . $row['location'] . '</p>
			<p>Time: ' . $timeRange . '</p>
			<p>Date: ' . $row['day'] . '/' . $row['month'] . '/' . $row['year'] . '</p>
		</div>';
	if (($row['feed_id'] == 0 OR $row['feed_id'] == NULL) AND $row['user_id'] == $ID ){	
		echo '<div class="ft"><a href="edit_event.php?event_id=' . $row['ID'] . '">Edit Event</a> | <a href="delete_event.php?event=' . $row['ID'] . '">Delete Event</a> | <a href="addtorss.php?event_id=' . $row['ID'] . '">Add to existing RSS Feed</a></div>';
		}
	//owner of the feed	
	elseif($row['user_id'] == $ID ){
		echo '<div class="ft"><a href="edit_event.php?event_id=' . $row['ID'] . '">Edit Event</a> | <a href="delete_event.php?event=' . $row['ID'] . '">Delete Event</a></div>';
	}	
		
	else{
		echo '<div class="ft">End of event info</div>';	
		}	
		
	echo '</div>';	
}

echo '</div>';
}
}

function getDayEvent2($ID, $day, $month, $year, $FeedIDs)
{
$html = "";
$event = getEventList($ID, $day, $month, $year); 

$html .= '<script>
		YAHOO.namespace("example.container");

		function init() {';
			


foreach($event as $row)
{
		$html .= 'YAHOO.example.container.panel' . $row['ID'] . ' = new YAHOO.widget.Panel("panel' . $row['ID'] . '", { width:"320px", visible:false, constraintoviewport:true } );
			YAHOO.example.container.panel' . $row['ID'] . '.render();

			YAHOO.util.Event.addListener("show' . $row['ID'] . '", "click", YAHOO.example.container.panel' . $row['ID'] . '.show, YAHOO.example.container.panel' . $row['ID'] . ', true);';
}	

	$html .= '	}

		YAHOO.util.Event.addListener(window, "load", init);
</script>


<div id="container">';

	
foreach($event as $row)
{

	
	
    if ($row['start_time'] == "00:00:00" AND $row['end_time'] == "00:00:00") $timeRange = "All Day";
    else $timeRange = substr($row['start_time'], 0, 5). " - " . substr($row['end_time'],0,5);	
	
	if ($row['feed_id'] == 0 OR $row['feed_id'] == NULL) {
        $html .= '<div id="show' . $row['ID'] . '" class="schedule">' 
		. $timeRange . ' - ' . $row['title'] . '</div>';
		}
		
	else
	{
		$style = getStyle(array_search($row['feed_id'], $FeedIDs));
		
		$html .= '<div id="show' . $row['ID'] . '" class="schedule" style="' . $style . '">'
		. $timeRange . ' - ' . $row['title'] . '</div>';		
	}
	$html .= '
	<div id="panel' . $row['ID'] . '">
		<div class="hd">Event Details</div>
		<div class="bd">
			<p>Title: ' . $row['title'] . '</p>
			<p>Description: ' . $row['des'] . '</p>
			<p>Location: ' . $row['location'] . '</p>
			<p>Time: ' . $timeRange . '</p>
			<p>Date: ' . $row['day'] . '/' . $row['month'] . '/' . $row['year'] . '</p>
		</div>';
		
	if (($row['feed_id'] == 0 OR $row['feed_id'] == NULL) AND $row['user_id'] == $ID) {	
		$html .= '<div class="ft"><a href="edit_event.php?event_id=' . $row['ID'] . '">Edit Event</a> | <a href="delete_event.php?event=' . $row['ID'] . '">Delete Event</a> | <a href="addtorss.php?event_id=' . $row['ID'] . '">Add to existing RSS Feed</a></div>';
		}
	//owner of the feed	
	elseif($row['user_id'] == $ID ){
		$html .= '<div class="ft"><a href="edit_event.php?event_id=' . $row['ID'] . '">Edit Event</a> | <a href="delete_event.php?event=' . $row['ID'] . '">Delete Event</a></div>';
	}	
		
	else{
		$html .= '<div class="ft">End of event info</div>';	
		}
	$html .= '</div>';	
}

$html .= '</div>';

return $html;
}

function calendar($ID, $year, $month, $day_offset = 0, $day, $FeedIDs){
    // get this month and this years as an int
    $thismonth = $month;
    $thisyear = $year; 
    // find out the number of days in the month
    $numdaysinmonth = cal_days_in_month( CAL_GREGORIAN, $thismonth, $thisyear ); 
    // create a calendar object
    $jd = cal_to_jd( CAL_GREGORIAN, $month, date( 1 ), $year );    
    // get the start day as an int (0 = Sunday, 1 = Monday, etc)
    $startday = jddayofweek( $jd , 0 );    
    // get the month as a name
    $monthname = jdmonthname( $month, 1 );
echo '
<table>

    <tr>
        <td><div class="tdheader">Sunday</div></td>
        <td><div class="tdheader">Monday</div></td>
        <td><div class="tdheader">Tuesday</div></td>
        <td><div class="tdheader">Wednsday</div></td>
        <td><div class="tdheader">Thursday</div></td>
        <td><div class="tdheader">Friday</div></td>
        <td><div class="tdheader">Saturday</div></td>
    </tr>
    <tr>';

    // put render empty cells

    $emptycells = 0;
    for( $counter = 0; $counter <  $startday; $counter ++ ) {   
        echo "\t\t<td>-</td>\n";
        $emptycells ++;  
    }
    
    // renders the days
    
    $rowcounter = $emptycells;
    $numinrow = 7;
    
    for( $counter = 1; $counter <= $numdaysinmonth; $counter ++ ) { 
        $rowcounter ++;        
        echo '<td valign="top">' . "$counter<br /><br /><div>". getDayEvent2($ID, $counter, $month, $year, $FeedIDs)."</div></td>\n";        
        if( $rowcounter % $numinrow == 0 ) {       
            echo "\t</tr>\n";            
            if( $counter < $numdaysinmonth ) {           
                echo "\t<tr>\n";           
            }      
            $rowcounter = 0;       
        }   
    }
    
    // clean up
    $numcellsleft = $numinrow - $rowcounter;
    
    if( $numcellsleft != $numinrow ) {  
        for( $counter = 0; $counter < $numcellsleft; $counter ++ ) {       
            echo "\t\t<td>-</td>\n";
            $emptycells ++;        
        }  
    }

echo"
    </tr>
</table>";
}


function getWeeklyView($ID, $day, $month, $year,$FeedIDs){
	
$mydate = mktime( 12, 0, 0, $month, $day, $year);
//or: $mydate = strtotime( 'some date string' );
//or get it from mysql_query( 'SELECT UNIXDATE(datefield) FROM table' )

//get weekday with monday==0, sunday==6
$weekday = ((int)date( 'w', $mydate ) + 6 ) % 7;

//get first monday on or before given date
$prevmonday = $mydate - $weekday * 24 * 3600;

$theDate = date( "Y/m/d", $prevmonday );
//format date
//echo $theDate;

$year = strtok($theDate, "/");
$month = strtok("/");
$day = strtok("/");

for ($i=0; $i<=6; $i++)
	{
		$tomorrow = mktime(0,0,0,$month,$day+$i,$year);
		$aDate = date("Y/m/d", $tomorrow);

		$temp_year = strtok($aDate, "/");
		$temp_month = strtok("/");
		$temp_day = strtok("/");
		echo "<div class=\"subtitle\">" . $aDate . " " .date("l", mktime(0, 0, 0, $temp_month, $temp_day, $temp_year)) . "</div>";
		getDayEvent($ID, $temp_day, $temp_month, $temp_year,$FeedIDs);
		echo "<br />";	
	}	
}

// ------------- for friend calendar

function getFriendDayEvent2($ID, $day, $month, $year, $FeedIDs)
{
$html = "";
$event = getEventList($ID, $day, $month, $year); 

$html .= '<script>
		YAHOO.namespace("example.container");

		function init() {';
			


foreach($event as $row)
{
		$html .= 'YAHOO.example.container.panel' . $row['ID'] . ' = new YAHOO.widget.Panel("panel' . $row['ID'] . '", { width:"320px", visible:false, constraintoviewport:true } );
			YAHOO.example.container.panel' . $row['ID'] . '.render();

			YAHOO.util.Event.addListener("show' . $row['ID'] . '", "click", YAHOO.example.container.panel' . $row['ID'] . '.show, YAHOO.example.container.panel' . $row['ID'] . ', true);';
}	

	$html .= '	}

		YAHOO.util.Event.addListener(window, "load", init);
</script>


<div id="container">';

	
foreach($event as $row)
{

	
	
    if ($row['start_time'] == "00:00:00" AND $row['end_time'] == "00:00:00") $timeRange = "All Day";
    else $timeRange = substr($row['start_time'], 0, 5). " - " . substr($row['end_time'],0,5);	
	
	if ($row['feed_id'] == 0 OR $row['feed_id'] == NULL) {
        $html .= '<div id="show' . $row['ID'] . '" class="schedule">' 
		. $timeRange . ' - ' . $row['title'] . '</div>';
		}
		
	else
	{
		$style = getStyle(array_search($row['feed_id'], $FeedIDs));
		
		$html .= '<div id="show' . $row['ID'] . '" class="schedule" style="' . $style . '">'
		. $timeRange . ' - ' . $row['title'] . '</div>';		
	}
	$html .= '
	<div id="panel' . $row['ID'] . '">
		<div class="hd">Event Details</div>
		<div class="bd">
			<p>Title: ' . $row['title'] . '</p>
			<p>Description: ' . $row['des'] . '</p>
			<p>Location: ' . $row['location'] . '</p>
			<p>Time: ' . $timeRange . '</p>
			<p>Date: ' . $row['day'] . '/' . $row['month'] . '/' . $row['year'] . '</p>
		</div><div class="ft">End of event info</div>';	
	
	$html .= '</div>';	
}

$html .= '</div>';

return $html;
}

function Friendcalendar($ID, $year, $month, $day_offset = 0, $day, $FeedIDs){
    // get this month and this years as an int
    $thismonth = $month;
    $thisyear = $year; 
    // find out the number of days in the month
    $numdaysinmonth = cal_days_in_month( CAL_GREGORIAN, $thismonth, $thisyear ); 
    // create a calendar object
    $jd = cal_to_jd( CAL_GREGORIAN, $month, date( 1 ), $year );    
    // get the start day as an int (0 = Sunday, 1 = Monday, etc)
    $startday = jddayofweek( $jd , 0 );    
    // get the month as a name
    $monthname = jdmonthname( $month, 1 );
echo '
<table>

    <tr>
        <td><div class="tdheader">Sunday</div></td>
        <td><div class="tdheader">Monday</div></td>
        <td><div class="tdheader">Tuesday</div></td>
        <td><div class="tdheader">Wednsday</div></td>
        <td><div class="tdheader">Thursday</div></td>
        <td><div class="tdheader">Friday</div></td>
        <td><div class="tdheader">Saturday</div></td>
    </tr>
    <tr>';

    // put render empty cells

    $emptycells = 0;
    for( $counter = 0; $counter <  $startday; $counter ++ ) {   
        echo "\t\t<td>-</td>\n";
        $emptycells ++;  
    }
    
    // renders the days
    
    $rowcounter = $emptycells;
    $numinrow = 7;
    
    for( $counter = 1; $counter <= $numdaysinmonth; $counter ++ ) { 
        $rowcounter ++;        
        echo '<td valign="top">' . "$counter<br /><br /><div>". getFriendDayEvent2($ID, $counter, $month, $year, $FeedIDs)."</div></td>\n";        
        if( $rowcounter % $numinrow == 0 ) {       
            echo "\t</tr>\n";            
            if( $counter < $numdaysinmonth ) {           
                echo "\t<tr>\n";           
            }      
            $rowcounter = 0;       
        }   
    }
    
    // clean up
    $numcellsleft = $numinrow - $rowcounter;
    
    if( $numcellsleft != $numinrow ) {  
        for( $counter = 0; $counter < $numcellsleft; $counter ++ ) {       
            echo "\t\t<td>-</td>\n";
            $emptycells ++;        
        }  
    }

echo"
    </tr>
</table>";
}


?>