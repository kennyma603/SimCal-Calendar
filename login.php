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
	<br />
		<div class="pinkbox">
		<h1>Not a member?</h1>
		<p>If you don't have an account yet, please register. Registration is free.</p>
		</div>
	</div>
	<!-- End of left column -->

	<!-- Beginning of right column -->	
	<div id="rightcol2">
	<h1>Login Form</h1><br />
		
	  <form action="login.php?try=true" class="niceform" method="post">
	  <p>
		  <span>*</span><label for="username">Username: </label>
		    <input id="username" type="text" name="username" value="" size="20" maxlength="100" />
	        <br />
			<br />
			<span>*</span><label for="pass">Password: </label>
			<input id="pass" type="password" name="password" value="" size="20" maxlength="40" />
			<br />
			<br />
			<input type="submit" value="Login!" /> 
		     <span id="passproblem"><a href="forget_password.php">Forget Password?</a> 
			 <a href="change_password.php">Change Password?</a>
             </span>  
		</p>		     
	  </form>
	
	<br />

	<?php 

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
	</div>
	<!-- End of right column -->
</div>	
<!-- End of main frame -->

<?php include_once "footer.php" ?>