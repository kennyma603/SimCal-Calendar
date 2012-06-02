<?php

////////////////////////////////////////////////////
//
// 
// 
//
// 
//
////////////////////////////////////////////////////

include_once "db_connect.php";
session_start(); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">


<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="G7" />
	<title>simCal</title>
	
	<link rel="stylesheet" type="text/css" href="yui/build/menu/assets/skins/sam/menu.css" />
	<script type="text/javascript" src="yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
	<script type="text/javascript" src="yui/build/container/container_core-min.js"></script>
	<script type="text/javascript" src="yui/build/menu/menu-min.js"></script>

	<script language="javascript" type="text/javascript" src="niceforms.js"></script>
	<style type="text/css" media="screen">@import url(niceforms-default.css);</style>
	<link href="style.css" rel="stylesheet" type="text/css" />	
	<script language="javascript" type="text/javascript" src="home.js"></script>	
	
<style type="text/css">
/* Reset style */
ul, li { list-style:none; }
fieldset, img { border:none; }

/* iFocus style */
#ifocus { width:690px; height:245px; border:1px solid #DEDEDE; background:#F8F8F8; }
	#ifocus_pic { display:inline; position:relative; float:left; width:580px; height:235px; overflow:hidden; margin:5px 0 0 5px; }
		#ifocus_piclist { position:absolute; }
		#ifocus_piclist li { width:580px; height:235px; overflow:hidden; }
		#ifocus_piclist img { width:580px; height:235px; }
	#ifocus_btn { display:inline; float:right; width:91px; margin:9px 9px 0 0; }
		#ifocus_btn li { width:91px; height:57px; cursor:pointer; opacity:0.5; -moz-opacity:0.5; filter:alpha(opacity=50); }
		#ifocus_btn img { width:75px; height:45px; margin:7px 0 0 11px; }
		#ifocus_btn .current { background: url(images/ifocus_btn_bg.gif) no-repeat; opacity:1; -moz-opacity:1; filter:alpha(opacity=100); }
	#ifocus_opdiv { position:absolute; left:0; bottom:0; width:580px; height:35px; background:#000; opacity:0.5; -moz-opacity:0.5; filter:alpha(opacity=50); }
	#ifocus_tx { position:absolute; left:8px; bottom:8px; color:#FFF; }
		#ifocus_tx .normal { display:none; }
</style>
		
</head>

<body class="yui-skin-sam">

<?php
include_once "header.php";
include_once "menu.php"; 
?>

<!-- Beginning of main frame -->
<div id="frame">
	<!-- Beginning of left column -->
	<div id="leftcol2">
	<div class="pinkbox">
		<div class="left"><img src="images/login.png" alt="Login" /></div>
		<div class="right">
			<form action="index.php?try=true" class="niceform" method="post">
	
			<label for="username">Username: </label><br />
			<input id="username" size="13" type="text" name="username" /><br />
			<br />
			<label for="pass">Password: </label><br />
			<input id="pass" size="13" type="password" name="password" /><br />
			<br />
			<input type="submit" value="Login!" />	
		     
			<span id="passproblem"><a href="forget_password.php">Forget Password?</a> 
             		</span> 	
			</form>
		</div>
		
		<div style="clear:both;"></div>
		<br/><br/>
		<p>-- TEST ACCOUNT --</p>
		<p>User name: test<br/>
		Password: testtest</p>

<?php

if(!empty($_POST['timeout']))
{
	echo "You have been logged out due to inactivity.
	
	<br><br>
	You will now be returned to the login page.";
}


// Check if user wants to login (GET info)
if(isset($_GET['try'])) {
	
	// That's nice, user wants to login. But lets check if user has filled in all information
	if(empty($_POST['username']) OR empty($_POST['password'])) {
		
		// User hasn't filled it all in!
		echo '<div class="error">Please fill in all fields!</div>';
		
	} else {
	
	// User filled it all in!
	
	// Make variables save with addslashes and md5
	$username = addslashes($_POST['username']);
	$password = md5($_POST['password']);
	
	// Search for a combination
	$query = mysql_query("SELECT username FROM login
				   WHERE username = '" . $username . "' 
				   AND password = '" . $password . "'
				  ") or die(mysql_error());
	
	// Save result		
	list($username) = mysql_fetch_row($query);
	
	// If the user_id is empty no combination was found
	$true = true;
	$query = mysql_query("SELECT COUNT(id) FROM login 
   				WHERE username = '" . $username . "' AND activated = '" . $true . "'") 
				   or die(mysql_error());
					
	list($count) = mysql_fetch_row($query);
		
	if(empty($username)) 
	{
		echo '<div class="error">No combination of username and password found.</div>';
	}	
	else if($count != 1)
	{
		echo '<div class="error">Your account has not been activated yet.<br />
		 Please check your e-mail for the activation link.</div>';

		echo '<script type="text/javascript">';
		echo "\n<!--\n";
		echo "setTimeout('Redirect()',5000);";
		echo "\nfunction Redirect()";
		echo "{\n";
		echo "\tlocation.href = 'index.php';\n";
		echo "}\n";
		echo "\n--></script>\n";
	}		
	else 
	{
		// the user_id variable doesn't seem to be empty, so a combination was found!
	
		// Create new session, store the user id
		$_SESSION['simcal_username'] = $username;
	
		$_SESSION['lastactivity'] = gettimeofday(true);
	
		// Redirect to userpanel.php
		//header('location: userpanel.php');
		//echo '<h4> You have sucessfully logged in. </h4>';
		//echo '<h4> Please click <a href="userpanel.php"> * Here * </a>to continue.</h4>';
		//exit();
		echo '<script type="text/javascript">';
		echo "\n<!--\n";
		echo "\twindow.location = 'userpanel.php';\n";
		echo "\n--></script>\n";

		}		
		
	}	
}


?>

	
		<br />
		
	</div>
	</div>
	<!-- End of left column -->

	<!-- Beginning of right column -->	
	<div id="rightcol2">
<div>

<div id="ifocus">
	<div id="ifocus_pic">
		<div id="ifocus_piclist" style="left:0; top:0;">
			<ul>
				<li><img src="images/01.jpg" alt="" /></li>
				<li><img src="images/02.jpg" alt="" /></li>
				<li><img src="images/03.jpg" alt="" /></li>
				<li><img src="images/04.jpg" alt="" /></li>
			</ul>
		</div>
		<div id="ifocus_opdiv"></div>
		<div id="ifocus_tx">
			<ul>
				<li class="current">simCal is a new, innovative online calendar system.</li>
				<li class="normal">You can add events and view events conveniently.</li>
				<li class="normal">You can subscribe to any existing feeds.</li>
				<li class="normal">You can create feeds so that your friends can subscribe.</li>
			</ul>
		</div>
	</div>
	<div id="ifocus_btn">
		<ul>
			<li class="current"><img src="images/btn_01.jpg" alt="" /></li>
			<li class="normal"><img src="images/btn_02.jpg" alt="" /></li>
			<li class="normal"><img src="images/btn_03.jpg" alt="" /></li>
			<li class="normal"><img src="images/btn_04.jpg" alt="" /></li>
		</ul>
	</div>
</div><!--ifocus end-->

</div>
	</div>
	<!-- End of right column -->
</div>	
<!-- End of main frame -->

<?php include_once "footer.php" ?>

