<?php
include_once "db_connect.php";
session_start(); 
include_once "check_session.php";
include_once "meta.php";
include_once "header.php";
include_once "menu.php";

$completed = 0;
?>




<!-- Beginning of main frame -->
<div id="frame">
	<!-- Beginning of left column -->
	<div id="leftcol2">	
		<div class="pinkbox">
		<h1>NOTE</h1>
		<p>
			You must fill in the areas with a <span>*</span> beside it.
		</p>
		</div>
	</div>	
	<!-- End of left column -->

	<!-- Beginning of right column -->	
	<div id="rightcol2">
		<?php
		if(isset($_GET['try']) AND isset($_POST['newpass']) AND isset($_POST['oldpass']) AND isset($_POST['reoldpass']))
		{
			if($_POST['oldpass'] == $_POST['reoldpass'])
			{
				$checkpass = MD5($_POST['oldpass']);
						
				$check = mysql_query("SELECT COUNT(ID) FROM login 
				WHERE password = '" . $checkpass . "' AND username = '" . $_SESSION['simcal_username'] . "'") 
				or die(mysql_error());
								
				list($count) = mysql_fetch_row($check);	

				if($count == 1)
				{
					$completed = 1;
					
					$newpass = MD5($_POST['newpass']);
					echo "hihi";
					echo $_SESSION['simcal_username'];
					mysql_query("UPDATE login 
					SET password = '" . $newpass . "'
				   	WHERE username = '" . $_SESSION['simcal_username'] . "'");
				   	
				   	echo "<br/><br /><div class=\"success\">Password successfully update.</div> <br /><br />";

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
		}
		if($completed == 0)
		{
		?>
		<h1>Change Password Form</h1>
		

			<form action="change_password.php?try=true" method="post" class="niceform">	
			
				<div class="time"><span>*</span><label for="textinput1">New Password:</label></div>
 				 <input type="text" id="textinput1" name="newpass" size="20"  />
		 		<?php
				if (!isset($_POST['newpass']) AND isset($_GET['try']))
				{
					echo " Please fill in a new password.";
				}			
				?>
												
				 <br /><br />	
				<div class="time"><span>*</span><label for="textinput1">Old Password:</label></div>
 				 <input type="text" id="textinput1" name="oldpass" size="20"  />
		 		<?php
				if (!isset($_POST['oldpass']) AND isset($_GET['try']))
				{
					echo "Please fill in your old password.";
				}		
				?>		
				
				<br /><br />
								
				<div class="time"><span>*</span><label for="textinput1">Retype Old Password:</label></div>
 				 <input type="text" id="textinput1" name="reoldpass" size="20"  />
		 		<?php
				if (!isset($_POST['reoldpass']) AND isset($_GET['try']))
				{
					echo "Please retype your old password.";
				}
				else if(isset($_POST['oldpass']) AND isset($_POST['reoldpass']) AND isset($_GET['try']))
				{
					if(!($_POST['oldpass'] == $_POST['reoldpass']))
					{
						echo "Your old and retype old password does not match.";
					}
					else
					{
						$checkpass = MD5($_POST['oldpass']);
								
						$check = mysql_query("SELECT COUNT(ID) FROM login 
						WHERE password = '" . $checkpass . "'") 
						or die(mysql_error());
										
						list($count) = mysql_fetch_row($check);	
						
						if($count != 1)
						{
							echo '<br /><br /><div class="error">Your old password is incorrect.</div><br/><br/>';
						}
					}
				}				
				?>
				<br />
				<input type="submit" name="Submit" />
			   </form>
	<?php
	}
	?>
	</div>
	<!-- End of right column -->
</div>	
<!-- End of main frame -->

<?php include_once "footer.php" ?>