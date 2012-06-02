<div class="bluebox">
<h3>Today's To do List</h3>

<?php
//if return 1, means the current user($ui) can access the rss feed with feed_id($fi).
//if return 0, means the current user($ui) can't access the rss feed with feed_id($fi).
function testRss($ui, $fi){
$ex = 0;
$query2 = "SELECT * FROM subscribe";
		$result2 = mysql_query($query2);
		while($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)){
			if (($row2['user_id'] == $ui) && ($row2['feed_id'] == $fi))
				{$ex = 1;
				break;}
		}
		
return $ex;
}



$i = 0;
$j = 0;
$Ccount = 0; 
$temp = 0;
$Cuserid = 0;
$current_ord = date("Ymd") . "." .date("His"); 
$save_time = array();
$save_time1 = array();
$save_title = array();
$save_take = array();
$save_startTime = array();
$save_endTime = array();
$save_day = array();

//get Cuserid
$query = "SELECT * FROM login";
$result = mysql_query($query);
$user = $_SESSION['simcal_username'];
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
	if ($row['username'] == $user)
	{
		$Cuserid = $row['ID'];
		break;
	}
}

	if ($Cuserid != 0)
		{
		$query1 = "SELECT * FROM event";
		$result1 = mysql_query($query1);
		$user1 = $_SESSION['simcal_username'];
		while($row1 = mysql_fetch_array($result1, MYSQL_ASSOC)){
			if (($row1['user_id'] == $Cuserid) && ($row1['feed_id'] == 0) || (testRss($Cuserid, $row1['feed_id']) == 1))
				{
					$Ccount++;
					$opt_title[$Ccount] = $row1['title'];
					$opt_stime[$Ccount] = $row1['start_time'];
					$opt_etime[$Ccount] = $row1['end_time'];
					$opt_day[$Ccount] = $row1['day'];
					$opt_ordday[$Ccount] = ((($row1['year'] * 100) + $row1['month'])*100)+ $row1['day'];
					$opt_ordetime[$Ccount] = str_replace(":","", $opt_etime[$Ccount]);
					$opt_ord[$Ccount] = $opt_ordday[$Ccount]. "." . $opt_ordetime[$Ccount];

					if($opt_ord[$Ccount] > $current_ord)
					{
					$temp++;
					//echo "<p>". $opt_ord[Ccount] . " > " .   $current_ord . "</p>";
					$save_time[] = $opt_ord[$Ccount];
					$save_time1[] = $opt_ord[$Ccount];
					$save_title[] = $opt_title[$Ccount];
					$save_startTime[] = $opt_stime[$Ccount];
					$save_endTime[] = $opt_etime[$Ccount];
					$save_day[] = $opt_day[$Ccount];
					$save_take[] = 1;					
					}
						
				}
			}
		}
		
sort($save_time);

$printed = 0;
$cc = 0;
for ($i = 0; $i < $temp; $i++)
{
	for ($j = 0; $j < $temp; $j++)
	{
	if( ($save_time1[$j] == $save_time[$i]) && ($save_take[$j] == 1))
		{
		if($save_day[$j] == date("d"))
		{	
  		echo "<p>(" . ($i + 1). ") ". $save_title[$j]. "<br />";
  		echo "" . substr($save_startTime[$j], 0, 5) . " - " . substr($save_endTime[$j], 0, 5) . "</p>";
		}	
		

  		
  		
  		
		$save_take[$j] = 2;
		break;
		}
	}
	$cc++;
	if ($cc == 9) //at most print 9 events on the todo list.
		break;
}

?>
</div>
