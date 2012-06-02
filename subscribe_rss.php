<?php

include_once "db_connect.php";
session_start(); 
include_once "check_session.php";
include_once "meta.php";
include_once "header.php";
include_once "menu.php"; ?>

<!-- Beginning of main frame -->
<div id="frame">

Please fill in the search form:

<div>
<br />	
<form action="subscribe_rss.php" method="post">
<input type="text" size="70" name="search_rss" />
<input type="hidden" name="searching" value="1" />
<input type="submit" value="Search RSS" />
</form>
</div>

	
<!-- End of main frame -->


<?php 
$search_num = 0;
$searching = 0;
echo "<table>";
echo "<td>Rss ID</td>\n";
echo "<td>Title</td>\n";
echo "<td>Description</td>\n";
echo "<td>Add/Remove Rss</td>\n";
echo "</tr>\n";

$search_num = 0;
$searching = 0;

$query = mysql_query("SELECT id FROM login
				   WHERE username = '" . $_SESSION['simcal_username'] . "' LIMIT 1")
				   or die(mysql_error());
	
list($user_id) = mysql_fetch_row($query);


if(!empty($_POST['searching']))
{
	$searching = $_POST['searching'];
}

if(!empty($_POST['search_rss']))
{
	$search_result = mysql_query("SELECT * FROM rssfeed WHERE title LIKE '%" . $_POST['search_rss'] . "%' AND NOT(creator = '" . $_SESSION['simcal_username'] . "')");
}
else
{
	$searching = -1;
	$search_result = mysql_query("SELECT * FROM rssfeed WHERE NOT(creator = '" . $_SESSION['simcal_username'] . "')");
	
	$search_user = mysql_query("SELECT * FROM rssfeed WHERE creator = '" . $_SESSION['simcal_username'] . "'");
}

if(empty($_POST['search_rss']))
{
	while($search_user_row = mysql_fetch_array($search_user))
	{
		echo '<tr align="center" class="row">';
		echo "\n";
		echo '<td width="5%">' . $search_user_row['rss_id'] . '</td>';
		echo "\n";
		echo '<td width="10%">' . $search_user_row['title'] . '</td>';
		echo "\n";
		echo '<td width="70%">' . $search_user_row['des'] . '</td>';
		echo "\n";
	
		echo '<td width="5%">Owner of Feed</td>';
			echo "\n";		
		echo "</tr>\n";
	}
}
while($search_row = mysql_fetch_array($search_result))
{
	$search_num++;
	echo '<tr align="center" class="row">';
	echo "\n";
	echo '<td width="5%">' . $search_row['rss_id'] . '</td>';
	echo "\n";
	echo '<td width="10%">' . $search_row['title'] . '</td>';
	echo "\n";
	echo '<td width="70%">' . $search_row['des'] . '</td>';
	echo "\n";

	$subscribe = mysql_query("SELECT feed_id FROM subscribe WHERE user_id = '" . $user_id . "' AND feed_id = '" . $search_row['rss_id'] . "'");
	list($subscribe_id) = mysql_fetch_row($subscribe);
	if($subscribe_id == $search_row['rss_id'])
	{
		echo '<form action="subscribe_rss.php?try=true" method="post">';
		echo "\n";
		echo '<td width="5%"><input type="image" name="delete" value="' . $search_row['rss_id'] . '" src="images/delete.png"> Remove Subscription</td>';
		echo "\n</form>";
		echo "\n";
	}
	else
	{
		echo '<form action="subscribe_rss.php?try=true" method="post">';
		echo "\n";
		echo '<td width="5%"><input type="image" name="add" value="' . $search_row['rss_id'] . '" src="images/rss.png"> Add Subscription</td>';
		echo "\n</form>";
		echo "\n";		
	}
	echo "</tr>\n";
}

echo "\n</table>\n";

if(($search_num == 0) OR (empty($_POST['search_rss'])))
{
	if($searching == 0)
	{
		echo '<table align="center">';
		echo "\n<tr>\n<td>There are no rss to subscribe.\n</td>\n</tr>\n</table>";		
	}
	else if ($searching == 1)
	{
		echo '<table align="center">';
		echo "\n<tr>\n<td>No Results Found!\n</td>\n</tr>\n</table>";
	}
}

echo "\n</div>\n";	
//<!-- End of main frame -->
if (isset($_GET['try'])) 
{	
	$query = mysql_query("SELECT id FROM login
				   WHERE username = '" . $_SESSION['simcal_username'] . "' LIMIT 1")
				   or die(mysql_error());
	
	list($user_id) = mysql_fetch_row($query);
	if(!empty($_POST['delete']))
	{
		mysql_query("DELETE FROM subscribe WHERE user_id = '" . $user_id . "' AND feed_id = '" . $_POST['delete'] . "'");
	}
	if(!empty($_POST['add']))
	{
		echo $_POST['add'];
		echo $user_id;
		mysql_query("INSERT INTO subscribe(user_id, feed_id) VALUES(" . $user_id . ", " . $_POST['add'] . ")");
	}
	echo '<script type="text/javascript">';
	echo "\n<!--\n";
	echo "\twindow.location = 'subscribe_rss.php';\n";
	echo "\n--></script>\n";
}
?>


</div>
<?php include_once "footer.php" ?>