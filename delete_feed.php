<?php

////////////////////////////////////////////////////
//
//
////////////////////////////////////////////////////

include_once "db_connect.php";

// Start the session (DON'T FORGET!!)
session_start();

include_once "meta.php";
include_once "header.php";
include_once "menu.php";

?>


<div id="frame">
	<!-- Beginning of left column -->
	<div id="leftcol2">
	
		<div class="bluebox">
		<p>You must fill in the areas with a <span>*</span> beside it. </p>
		</div>	

	</div>
	<!-- End of left column -->

	<!-- Beginning of right column -->	
	<div id="rightcol2">
	
	<?php 

	if(isset($_GET['try'])) {

		if(($_POST['yesno']) == 'no') {
		
			echo '<script type="text/javascript">';
			echo "\n<!--\n";
			echo "\twindow.location = 'userpanel.php';\n";
			echo "\n--></script>\n";
		
		} else {
	
			$feed_id = $_POST['feed'];
	
			$query = mysql_query("SELECT * FROM rssfeed
						   WHERE rss_id = '" . $feed_id . "'") or die(mysql_error());
	
			list($ID) = mysql_fetch_row($query);
			
			if(!isset($ID)) 
			{
				echo '<div class="error">Rss Feed does not exist.</div>';
			}	
			else
			{
				mysql_query("DELETE FROM rssfeed WHERE rss_id = '" . $feed_id . "'") or die(mysql_error());  

				mysql_query("DELETE FROM subscribe WHERE feed_id = '" . $feed_id . "'") or die(mysql_error()); 

				mysql_query("DELETE FROM event WHERE feed_id = '" . $feed_id . "'") or die(mysql_error()); 

				echo '<div class="success">Rss Feed deleted.</div>';
				
			}	
			echo '<script type="text/javascript">';
			echo "\n<!--\n";
			echo "setTimeout('Redirect()',2000);";
			echo "\nfunction Redirect()";
			echo "{\n";
			echo "\tlocation.href = 'userpanel.php';\n";
			echo "}\n";
			echo "\n--></script>\n";	
		
		
		}	
	}
	
	else {
			if(!isset($_GET['feed'])) {
				echo "Oops, something wrong here. Missing feed_id.";
			}
			
			else {
				$feed_id = $_GET['feed'];
			
				$query = mysql_query("SELECT * FROM rssfeed
						   WHERE rss_id = '" . $feed_id . "'") or die(mysql_error());
				
				$info = mysql_fetch_array($query, MYSQL_ASSOC);
				
				echo '<h1>Are you sure you want to delete this Rss Feed?</h1><br />
				<p><div class="time">Title: ' . $info['title'] .' </div> <br /><br />
				<div class="time">Description: ' . $info['des'] .' </div> 
				</p><br /><br />
				<p>	
				  <form action="delete_feed.php?try=true" class="niceform" method="post">
			    	<input type="radio" name="yesno" id="yes" value="yes" /><label for="yes">Yes</label><br />
					<input type="radio" name="yesno" id="no" value="no" checked="checked" /><label for="no">No</label><br /><br />
					<input type="hidden" name="feed" value="'.$feed_id.'" />
					<input type="submit" value="Submit" />
				  </form>
				</p>
				<br />';
			}
	}
	
	?>
	</div>
	<!-- End of right column -->
</div>	
<!-- End of main frame -->

<?php include_once "footer.php" ?>