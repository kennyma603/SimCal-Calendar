<?php

include_once "db_connect.php";
// Start the session (DON'T FORGET!!)
session_start();
include_once "check_session.php";
include_once "meta.php";
include_once "header.php";

?>



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
	

	if ($exist = 1)
	{
	//mysql_select_db('simcaldb')or die('Error, cannot select mysql database');
	$query = "UPDATE share SET activation = '$act'". "WHERE sid = '$shareid'"; 
	mysql_query($query) or die('Error, insert share table failed');
	$query = "FLUSH PRIVILEGES";
	mysql_query($query) or die('Error, insert share table failed');
	}
	
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
	}	 	
}

if ($exist == 1)
	{echo "<p>Do you allow ". $fname ." to see your personal calendar?</p>";


?>

<script>
function allow(){
document.form1.action="allow.php";
document.form1.submit();
}
function reject(){
document.form1.action="reject.php";
document.form1.submit();
}
</script>
<form name="form1">
<input type="button" name="btn1" value="Allow" onclick="allow();">
<input type="button" name="btn2" value="Reject" onclick="reject();">
</form> 

<?php
}
else
{
				echo '<script type="text/javascript">';
				echo "\n<!--\n";
				echo "\twindow.location = 'userpanel.php';\n";
				echo "\n--></script>\n";
}

include "footer.php";
?>

