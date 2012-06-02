<?php

/**
 * @author Esmond Man
 * @copyright 2008
 */

include_once "db_connect.php";
include_once "meta.php";
include_once "header.php";
include_once "menu.php";
?>




<!-- Beginning of main frame -->
<div id="frame">
	<!-- Beginning of left column -->
	<div id="leftcol">
		<h1>NOTE</h1>
		<p>
			You must fill in the areas with a '*' beside it.
		</p>
		<p>
			Also, you must enter a valid e-mail address since there will be an e-mail sent to you for your account activation.
		</p>
	</div>	
	<!-- End of left column -->

	<!-- Beginning of right column -->	
	<div id="rightcol">
	<h1>Activation Page</h1>
<?php

$true = true;
$false = false;

$activate_user = '';

$user_query = mysql_query("SELECT username FROM login") 
					   or die(mysql_error());
					   
while($user_row = mysql_fetch_array($user_query))
{
	if($_GET['uid'] == MD5($user_row['username']))
	{
		$activate_user = $user_row['username'];
	}
}

$query = mysql_query("SELECT COUNT(id) FROM login 
		   			WHERE username = '" . $activate_user . "' AND activated = '" . $false . "'") 
					   or die(mysql_error());
		
list($count) = mysql_fetch_row($query);

if($count == 1)
{
	
	mysql_query("UPDATE login
			SET activated = '" . $true . "'
			WHERE username = '" . $activate_user . "'") 
			or die(mysql_error());
	echo "<br />Your account has been activated. <br />";
	echo "You will now be redirected to the main page in 5 seconds.";
}
else
{
	$new_query = mysql_query("SELECT COUNT(id) FROM login 
		   			WHERE username = '" . $activate_user . "' AND activated = '" . $true . "'") 
					   or die(mysql_error());
		
	list($new_count) = mysql_fetch_row($new_query);
	if($new_count == 1)
	{
		echo "<div class=\"success\"><br/>Your account has already been activated. <br />";
		echo "You will now be redirected to the main page in 5 seconds.</div> <br /><br />";
	}
	else
	{
		echo "<br /><div class=\"error\"><br />Activation unsuccessful.<br />";
		echo "Please make sure you have copied the whole link. <br />";
		echo "You will now be redirected to the main page in 5 seconds.</div><br/><br/>";
	}
}
echo '<script type="text/javascript">';
echo "\n<!--\n";

echo "setTimeout('Redirect()',5000);";
echo "\nfunction Redirect()";
echo "{\n";
echo "\tlocation.href = 'index.php';\n";
echo "}\n";

echo "\n--></script>\n";
?>
	</div>
	<!-- End of right column -->
</div>	
<!-- End of main frame -->

<?php include_once "footer.php" ?>