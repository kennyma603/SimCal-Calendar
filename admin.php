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
		<h1>Welcome, Administrator</h1>
		<p>
			As an administrator, you may choose from the following menu options below.

		</p>
		<br />			<h1></h1><br />
		<p>
		<a href="admin.php?manageUsers=true">Manager Users and Make Admins</a>
		<br /><br />
		<a href="admin.php?manageFeeds=true">Manage RSS Feeds</a>
		<br /><br />
		<a href="admin.php?stats=true">View System Statistics</a>
		<br /><br />
		
		</p>
		
		</div>
	</div>	
	<!-- End of left column -->
	
	
	
		<!-- Beginning of right column -->	
	<div id="rightcol2">
	
	<br />
	<h1>Please choose from one of the Administrative options on the left menu</h1>
		
		<?php
		//MANAGE USERS
		//*************************************************************************
		if(isset($_GET['manageUsers']))
		{	
			
			echo "<table>";
			echo "<td>User ID</td>\n";
			echo "<td>Email</td>\n";
			echo "<td>Admin Access</td>\n";
			echo "<td>Delete User</td>\n";
			echo "</tr>\n";
			
			
			$search_result = mysql_query("SELECT * FROM login WHERE username !=  '" . $_SESSION['simcal_username'] . "' ") 
			or die(mysql_error());
			
			//user for add/delete later
			//list($userid) = mysql_fetch_row($search_result);
			
			//go through each user and broadcast
			while($search_row = mysql_fetch_array($search_result))
			{
				echo '<tr align="center" class="row">';
				echo "\n";
				echo '<td width="30%">' . $search_row['username'] . '</td>';
				echo "\n";
				echo '<td width="30%">' . $search_row['email'] . '</td>';
				echo "\n";
				//echo '<td width="15%">' . $search_row['isadmin'] . '</td>';
				//echo "\n";
				//echo '<td width="15%">' . $search_row['isadmin'] . '</td>';
				//echo "\n";

				if($search_row['isadmin'] == '1')
				{
					echo '<form action="admin.php?manageUsers=true" method="post">';
					echo "\n";
					echo '<td width="15%"><input type="image" name="removeadmin" value="' . $search_row['ID'] . '" src="images/removeadmin.jpg"> Remove Access</td>';
					echo "\n</form>";
					echo "\n";
				}
				else
				{
					echo '<form action="admin.php?manageUsers=true" method="post">';
					echo "\n";
					echo '<td width="15%"><input type="image" name="makeadmin" value="' . $search_row['ID'] . '" src="images/makeadmin.jpg"> Grant Access</td>';
					echo "\n</form>";
					echo "\n";	
				}
				
				
				echo '<form action="admin.php?manageUsers=true" method="post">';
				echo "\n";
				echo '<td width="5%"><input type="image" name="delete" value="' . $search_row['ID'] . '" src="images/delete.png"></td>';
				echo "\n</form>";
				echo "\n";
			
			echo "</tr>\n";
			}//end while

		echo "\n</table>\n";
		
		//if user clicked delete
		if(!empty($_POST['delete']))
		{
			mysql_query("DELETE FROM login WHERE ID = '"  .  $_POST['delete'] . "'")
			or die (mysql_error());
			
				//refresh page
				echo '<script type="text/javascript">';
				echo "\n<!--\n";
				echo "\twindow.location = 'admin.php?manageUsers=true';\n";
				echo "\n--></script>\n";
		}
		if(!empty($_POST['makeadmin']))
		{
			//echo"you chose to make admin " . $_POST['makeadmin'];
				mysql_query("UPDATE login SET isadmin='1' WHERE 
				ID = '" . $_POST['makeadmin'] . "'") or die(mysql_error());
				
				//refresh page
				echo '<script type="text/javascript">';
				echo "\n<!--\n";
				echo "\twindow.location = 'admin.php?manageUsers=true';\n";
				echo "\n--></script>\n";
		}
		if(!empty($_POST['removeadmin']))
		{
		//	echo"you chose to remove admin " . $_POST['removeadmin'];
				mysql_query("UPDATE login SET isadmin='0' WHERE 
				ID = '" . $_POST['removeadmin'] . "'") or die(mysql_error());
				
				//refresh page
				echo '<script type="text/javascript">';
				echo "\n<!--\n";
				echo "\twindow.location = 'admin.php?manageUsers=true';\n";
				echo "\n--></script>\n";
		}

	}
	//******************************************************//
	?>
	
	
	<?php
	//VIEW RSS INFO
	//**********************************************************
		if(isset($_GET['manageFeeds']))
		{	
			
			echo "<table>";
			echo "<td>RSS Feed Title</td>\n";
			echo "<td>RSS Feed Description</td>\n";
			echo "<td>Creator</td>\n";
			echo "<td>Delete Feed</td>\n";
			echo "<td>Edit Feed</td>\n";
			echo "</tr>\n";
			
			
			$search_result = mysql_query("SELECT * FROM rssfeed") or die(mysql_error());
			
			//user for add/delete later
			//list($userid) = mysql_fetch_row($search_result);
			
			//go through each user and broadcast
			while($search_row = mysql_fetch_array($search_result))
			{
				echo '<tr align="center" class="row">';
				echo "\n";
				echo '<td width="30%">' . $search_row['title'] . '</td>';
				echo "\n";
				echo '<td width="30%">' . $search_row['des'] . '</td>';
				echo "\n";
				echo '<td width="15%">' . $search_row['creator'] . '</td>';
				echo "\n";
				
				
				echo '<form action="admin.php?manageFeeds=true" method="post">';
				echo "\n";
				echo '<td width="5%"><input type="image" name="delete" value="' . $search_row['rss_id'] . '" src="images/delete.png"></td>';
				echo "\n</form>";
				echo "\n";
				
				echo '<form action="edit_event.php?editrss=true&rss_id=' . $search_row['rss_id'] . '&admin=true" method="post">';
				echo "\n";
				echo '<td width="5%"><input type="image" name="edit" value="' . $search_row['rss_id'] . '" src="images/edit.jpg"></td>';
				echo "\n</form>";
				echo "\n";
			
			echo "</tr>\n";
			}//end while

		echo "\n</table>\n";
		
		//if user clicked delete
		if(!empty($_POST['delete']))
		{
			mysql_query("DELETE FROM rssfeed WHERE rss_id = '"  .  $_POST['delete'] . "'")
			or die (mysql_error());
			
				//refresh page
				echo '<script type="text/javascript">';
				echo "\n<!--\n";
				echo "\twindow.location = 'admin.php?manageFeeds=true';\n";
				echo "\n--></script>\n";
		}
		if(!empty($_POST['edit']))
		{
			
				//do nothing, redirect to edit page
				
			/*
				mysql_query("UPDATE login SET isadmin='1' WHERE 
				id = '" . $_POST['makeadmin'] . "'") or die(mysql_error());
				
				//refresh page
				echo '<script type="text/javascript">';
				echo "\n<!--\n";
				echo "\twindow.location = 'admin.php?manageUsers=true';\n";
				echo "\n--></script>\n";
				*/
		}
		

	}
	//******************************************************//
	?>
	
		
		
		
		
	<?php
	//VIEW SYSTEM STATS
	//*****************************************************************
	if(isset($_GET['stats']))
	{
		//being query of stats
		$true = 1;
		$queryUsers = mysql_query("SELECT COUNT(ID) FROM login 
		WHERE activated = '" . $true . "'") 
		or die(mysql_error());
		list($userCount) = mysql_fetch_row($queryUsers);
		
		//being query of stats
		$queryFeeds = mysql_query("SELECT COUNT(rss_id) FROM rssfeed ") 
		or die(mysql_error());
		list($feedCount) = mysql_fetch_row($queryFeeds);
		
		//being query of stats
		$queryEvents = mysql_query("SELECT COUNT(ID) FROM event")  
		or die(mysql_error());
		list($eventCount) = mysql_fetch_row($queryEvents);
		
		echo '<h1>System Statistics</h1>';
		
		echo'<p>'; 
		echo 'Number of Registered and Activated Users: ';
		echo $userCount;
		echo '<br/><br/>';
	
		echo 'Number of RSS Feeds in System: ';
		echo $feedCount;
		echo '<br/><br/>';
	
		echo 'Number of Events in System: ';
		echo $eventCount;
		echo '<br/><br/>';
		echo '</p>';
	} 
	?>	
	</div>
	<!-- End of right column -->
</div>	
<!-- End of main frame -->

<?php include_once "footer.php" ?>
