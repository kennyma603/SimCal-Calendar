<div id="sepbar"></div>
<div id="submenu"><div class="left">Welcome...

<?php
// Check if user is logged in
if(isset($_SESSION['simcal_username'])) {

	echo $_SESSION['simcal_username'] . '! <span id="passproblem"><a href="change_password.php"> (change password?)</a></span>';

	$query = mysql_query("SELECT username, isadmin FROM login 
		WHERE username = '" . $_SESSION['simcal_username'] . "'") or die(mysql_error());
	$result = mysql_fetch_array($query);
		
	if($result['isadmin'] == 1)
	{
		echo "<br />You are an <a href=\"admin.php\"><u>Administrator</u></a>";
	

	} 
	
	$countquery = mysql_query("SELECT count(sid) FROM share WHERE username = '" . $_SESSION['simcal_username'] . "' AND activation = '0'") or die(mysql_error());
	$query = mysql_query("SELECT * FROM share WHERE username = '" . $_SESSION['simcal_username'] . "'") or die(mysql_error());
	$result = mysql_fetch_array($query);
	list($count) = mysql_fetch_row($countquery);
		
	if($count > 0)
	{
		echo "<br/><img src=images/blubounce.gif>You have friend <a href=\"grant_access.php?try=true\"><u>requests!</u></a>";
	} 
}

else {
	
	// User not logged in
	echo 'Guest';

	}

?>
</div>

<div class="right">
<?php
// Check if user is logged in
if(isset($_SESSION['simcal_username'])) {
	echo '
	
	<script type="text/javascript">

    YAHOO.util.Event.onContentReady("basicmenu", function () {
        var oMenu = new YAHOO.widget.Menu("basicmenu", { position: "dynamic", context: ["menutoggle", "tl", "bl"]});
		oMenu.render(); 
        YAHOO.util.Event.addListener("menutoggle", "mouseover", oMenu.show, null, oMenu);
          
    });
    
</script>

	
	<div id="menu2">

				<span id="menutoggle" style="cursor:pointer;">View Calendar</span>
				<span><a href="subscribe_rss.php">Subscribe Rss</a></span>
				<span><a href="create_rss.php?new=true">Create Rss</a></span>
                <span><a href="require_access.php">Friend Access</a></span>
				<span><a href="add_event.php">Add Event</a></span>
				<span><a href="edit_event.php">Edit Feed</a></span>

		  </div>
		  
<div class=" yui-skin-sam">
<div id="basicmenu" class="yuimenu">
    <div class="bd">
        <ul class="first-of-type">
            <li class="yuimenuitem"><a class="yuimenuitemlabel" href="userpanel.php">Agenda View</a></li>
            <li class="yuimenuitem"><a class="yuimenuitemlabel" href="weeklyview.php">Weekly View</a></li>
            <li class="yuimenuitem"><a class="yuimenuitemlabel" href="monthlyview.php">Monthly View</a></li>
        </ul>            
    </div>
</div>
</div>
 		  	
		 '

		 
		 ;
}
?>
</div>
</div> 