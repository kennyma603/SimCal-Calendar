<?php

	$user = $_SESSION['simcal_username'];
	$query  = "SELECT * FROM share";
	$result = mysql_query($query);
	$exist = 0;
	$act = 1;

	while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
   		 if (($row['activation'] == 0) && ($row['username'] == $user)){
		 	$fname = $row['grant_access'];
			$shareid = $row['sid'];
			$exist = 1;
			break;
	}}	

if($exist == 1) {
	//redirect user to grant_access page.
	//header('location: grant_access.php');
				
	echo '<script type="text/javascript">';
	echo "\n<!--\n";
	echo "\twindow.location = 'grant_access.php';\n";
	echo "\n--></script>\n";
}


?>