<?php
session_start(); 
/**
 * @author darius law
 */
 
// Open a connection to the DB
include_once "db_connect.php";

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
		<h1>Friend Requests</h1>
		<p>
			By allow your friends to view your calendar, you can easily 
			share all your events with them.  This is a great way to keep tabs on what your
			friends are up to!  

		</p>
		</div>
	</div><!--end left column-->
	
	<!-- Beginning of right column -->	
	<div id="rightcol2">


<?php

	//$countquery = mysql_query("SELECT count(sid) FROM share WHERE username = '" . $_SESSION['simcal_username'] . "' AND activation = '0'") or die(mysql_error());
	
	$query = mysql_query("SELECT * FROM share WHERE username = '" . $_SESSION['simcal_username'] . "' AND activation = '0'") or die(mysql_error());
	
	//$result = mysql_fetch_array($query);
	//list($count) = mysql_fetch_row($countquery);
		
	echo '<h1>Your Friend Requests</h1>';	
	
	
	if(isset($_GET['try']))
	{	
		echo "<table>";
		echo "<td>Your Friend / Requestor</td>\n";
		echo "<td>Reject</td>\n";
		echo "<td>Allow</td>\n";
		echo "</tr>\n";
		
		while($search_row = mysql_fetch_array($query))
		{
			echo '<tr align="center" class="row">';
			echo "\n";
			echo '<td width="60%">' . $search_row['grant_access'] . '</td>';
			echo "\n";
			
			echo '<form action="grant_access.php?try=true" method="post">';
			echo "\n";
			echo '<td width="15%"><input type="image" name="reject" value="' . $search_row['sid'] . '" src="images/error.png"></td>';
			echo "\n</form>";
			echo "\n";
					
			echo '<form action="grant_access.php?try=true" method="post">';
			echo "\n";
			echo '<td width="15%"><input type="image" name="accept" value="' . $search_row['sid'] . '" src="images/success.png"></td>';
			echo "\n</form>";
			echo "\n";		
		} 
	
		echo "</tr>\n";
		echo "\n</table>\n";
			
		//if user clicked delete
		if(!empty($_POST['reject']))
		{
			mysql_query("DELETE FROM share WHERE sid = '"  .  $_POST['reject'] . "'")
			or die (mysql_error());
			
			//refresh page
			echo '<script type="text/javascript">';
			echo "\n<!--\n";
			echo "\twindow.location = 'grant_access.php?try=true';\n";
			echo "\n--></script>\n";
		}
		if(!empty($_POST['accept']))
		{
			mysql_query("UPDATE share SET activation='1' WHERE 
				sid = '" . $_POST['accept'] . "'") or die(mysql_error());
			
			//refresh page
			echo '<script type="text/javascript">';
			echo "\n<!--\n";
			echo "\twindow.location = 'grant_access.php?try=true';\n";
			echo "\n--></script>\n";
		
		
		}	
	/*
			mysql_query("UPDATE login SET isadmin='1' WHERE 
				id = '" . $_POST['makeadmin'] . "'") or die(mysql_error());
			
			//refresh page
			echo '<script type="text/javascript">';
				echo "\n<!--\n";
				echo "\twindow.location = 'admin.php?manageUsers=true';\n";
				echo "\n--></script>\n";
				*/
		
			echo '<br/><input type="button" value="Back to My Calendar" onclick="window.location.href=\'userpanel.php\'"/>';
	}//end if try=true
		

	


/*
	$user = $_SESSION['simcal_username'];
	$query  = "SELECT * FROM share";
	$result = mysql_query($query);
	$exist = 0;
	
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

include "footer.php";*/
?>

</div><!--end right col-->
</div>
<?php include "footer.php"; ?>
