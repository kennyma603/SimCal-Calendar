<?php
// Globlal functions


function createRss ($rssID, $title, $des, $creator, $date)
	{
	
	$content = "<simCal>\n";
	$content .= "<id>$rssID</id>";
	$content .= "<title>$title</title>\n";
	$content .= "<description>$des</description>\n";
	$content .= "<author>$creator</author>\n";
	$content .= "<creationDate>$date</creationDate>\n";	
	$content .= "</simCal>";
	
	$Handle = fopen("xml/" . $rssID . ".xml", 'w') or die("can't open file");
	
	fwrite($Handle, $content); 
	echo "<div class=\"success\">The Rss Feed is created.</div>"	;
	
	fclose($Handle);	
	}


function saveRss ($rssID)
	{

	$rssID = $rssID . "xml";
	
	$content = getRssHeader($rssID) . getRssFooter();
	
	$ourFileHandle = fopen($content, 'w') or die("can't open file");
	fclose($ourFileHandle);
		
	}
	
function getRssHeader($rssID)
	{
	$query = mysql_query("SELECT * FROM rssfeed
			WHERE rss_id = $rssID");
		
	$row = mysql_fetch_array($query, MYSQL_ASSOC);
		
	$header = "<simCal>\n";
	$header .= "<id>$rssID</id>\n";	
	$header .= "<title>" . $row['title'] . "</title>\n";
	$header .= "<description>" . $row['des'] . "</description>\n";
	$header .= "<author>" . $row['author'] . "</author>\n";
	$header .= "<creationDate>" . $row['creation_date'] . "</creationDate>\n";
	return $header;
	}
	
function getRssFooter()
	{
	return "</simCal>";
	}
	
function addEvent($rssID,$eventTitle,$eventDescription,$eventLocation ,$day,$month,$year,$startTime,$endTime,$userID)
	{
	$content = file_get_contents ("xml/" . $rssID . ".xml");
	
	$handling = fopen("xml/" . $rssID . ".xml", 'w');
	$content = str_replace("</simCal>", "", $content);
	
	$content .= "<event>\n";
	$content .= "<title>$eventTitle</title>\n";	
	if($eventDescription != "")
		$content .= "<description>$eventDescription</description>\n";
	if($eventLocation != "")
		$content .= "<location>$eventLocation</location>\n";	
	$content .= "<day>$day</day>\n";
	$content .= "<month>$month</month>\n";
	$content .= "<year>$year</year>\n";
	if($startTime != "")
		$content .= "<starttime>$startTime</starttime>\n";
	if($endTime != "")	
		$content .= "<endtime>$endTime</endtime>\n";
	if($userID != "")
		$content .= "<user_id>$userID</user_id>\n";
	$content .= "<feed_id>$rssID</feed_id>\n";
	$content .= "</event>\n";
	
	$content .= "</simCal>";
	fwrite($handling, $content); 
	fclose($handling);
	//echo $content; 
	}
		
// find the name of the user.
function getID($name){

	$query = mysql_query("SELECT ID FROM login 
	   WHERE username = '" . $name . " '")  or die(mysql_error());
	
	$info = mysql_fetch_array($query, MYSQL_ASSOC);
	return $info['ID'];
}

function getEventList($ID, $day, $month, $year){
	
//	$result = mysql_query("SELECT * FROM event 
//	   WHERE (user_id = '" . $ID . " ' OR event.feed_id = ANY (SELECT subscribe.feed_id FROM subscribe WHERE subscribe.user_id = event.user_id)) AND day = '" . $day . " ' AND month = '" . $month . " ' AND year = '" . $year . " ' ORDER BY start_time ASC")  or die(mysql_error());

$result = mysql_query("SELECT *
FROM event
WHERE (
(
user_id = '" . $ID . " '
AND feed_id = '0'
)
OR (
event.feed_id = ANY(

SELECT subscribe.feed_id
FROM subscribe
WHERE subscribe.user_id = '" . $ID . " '
)
)
)
AND DAY = '" . $day . " '
AND MONTH = '" . $month . " '
AND year = '" . $year . " '
ORDER BY start_time ASC")  or die(mysql_error());
	
	$table = array();

    $i = 0;
    while($table[$i] = mysql_fetch_assoc($result)) 
        $i++;
    unset($table[$i]);                                                                                                                                                                                     
    mysql_free_result($result);
    return $table;	
}

function getSubscribedFeedID($ID){
	$result = mysql_query("SELECT feed_id FROM subscribe 
	   WHERE user_id = '" . $ID . " ' ")  or die(mysql_error());
	  
	$FeedList = array(); 
	   
	while($Feed = mysql_fetch_array($result))
		$FeedList[] = $Feed['feed_id'];
        
    return $FeedList;	
}

function getStyle($index){
	if ($index == 0) return "background-color: #F2FDDB; border: 1px solid #A4C63E;";
	elseif ($index == 1) return "background-color: #E8F5FE; border: 1px solid #ACC6E9;";
	elseif ($index == 2) return "background-color: #FFFFDD; border: 1px solid #E8DB97;";
	elseif ($index == 3) return "background-color: #F7F7F7; border: 1px solid #D5D5D5;";
	elseif ($index == 4) return "background-color: #F8B3D0; border: 1px solid #FFF5FA;";
	elseif ($index == 5) return "background-color: #FFDD99; border: 1px solid #FFF9ED;";
	elseif ($index == 6) return "background-color: #5C9CC0; border: 1px solid #F2FAFF;";
	elseif ($index == 7) return "background-color: #FFCC00; border: 1px solid #FFFFF7;";
	elseif ($index == 8) return "background-color: #9BDF70; border: 1px solid #F0FBEB;";
	elseif ($index == 9) return "background-color: #A5B6C8; border: 1px solid #EEF3F7;";
	else return "background-color: #FFFFDD; border: 1px solid #DEDFB7;";
}

function getFeedTitle($feedID){
	$result = mysql_query("SELECT title FROM rssfeed 
	   WHERE rss_id = '" . $feedID . " ' ")  or die(mysql_error());
	   
	$info = mysql_fetch_array($result, MYSQL_ASSOC);
	return $info['title'];
}

function getUserRss(){
	$user_rss = mysql_query("SELECT rss_id FROM rssfeed
		WHERE creator = '" . $_SESSION['simcal_username'] . "'") or die(mysql_error());
		
	$RssFeedList = array(); 
	   
	while($Rss = mysql_fetch_array($user_rss))
		$RssFeedList[] = $Rss['rss_id'];
        
    return $RssFeedList;
}

function getEventRss($feedID, $user){
	$event_rss = mysql_query("SELECT ID FROM event
		WHERE feed_id = '" . $feedID. "' AND user_id = '" . $user . "'") or die(mysql_error());
		
	$EventFeedList = array(); 
	   
	while($Events = mysql_fetch_array($event_rss))
		$EventFeedList[] = $Events['ID'];
        
    return $EventFeedList;
}

function getEventTitle($feedID){
	$result = mysql_query("SELECT title FROM event 
	   WHERE ID = '" . $feedID . "'")  or die(mysql_error());
	   
	$info = mysql_fetch_array($result, MYSQL_ASSOC);
	return $info['title'];
}

function generatePassword($length = 8)
{

  // start with a blank password
  $password = "";

  // define possible characters
  $possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
    
  // set up a counter
  $i = 0; 
    
  // add random characters to $password until $length is reached
  while ($i < $length) { 

    // pick a random character from the possible ones
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
        
    // we don't want this character if it's already in the password
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }

  }

  // done!
  return $password;
}

function check_date($day, $month, $year)
{
	if($day < 1 OR $month < 1 OR $month > 12 OR $year < 2008)
	{
		return 1;
	}
	else if(($month == 1) OR ($month == 3) OR ($month == 1) OR ($month == 5) OR ($month == 7) OR ($month == 8) OR ($month == 10) OR ($month == 12))
	{
		if($day > 31)
		{
			return 1;
		}
	}
	else if(($month == 4) OR ($month == 6) OR ($month == 9) OR ($month == 11))
	{
		if($day > 30)
		{
			return 1;
		}
	}
	else if($month == 0)
	{
		if($day > 28)
		{
			return 1;
		}
	}
	return 0;
}
?>


<!-- Beginning of header -->
<div id="head">
	<div id="headleft"><img src="images/logo.jpg" alt="Rss Calendar" width="420" height="70" /></div>
	<div id="headright"> 
	
	<?php
		// Check if user is logged in
		if(isset($_SESSION['simcal_username'])) {
		
			echo '<span><a href="userpanel.php">Home</a></span> <span><a href="logout.php">Logout</a></span>';
		
		} else {
			
			// User not logged in
			echo '<span><a href="index.php">Home</a></span> <span><a href="login.php">Login</a></span> <span><a href="register.php">Register</a></span>';
		
		}
		
	?>

	</div>
</div>
<!-- End of header -->