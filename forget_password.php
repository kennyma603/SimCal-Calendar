<?php
include_once "db_connect.php";
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
				
		if(isset($_POST['username']) AND isset($_POST['secquestion']) AND isset($_POST['secanswer']))
		{	
			if(isset($_GET['try']))
			{
				$security = mysql_query("SELECT COUNT(ID) FROM login 
			   	WHERE username = '" . $_POST['username'] . "' AND security_question = '" . $_POST['secquestion'] . "' AND security_answer = '" . $_POST['secanswer'] . "'") 
				or die(mysql_error());
						
				list($count) = mysql_fetch_row($security);
				if($count == 1)
				{
					$email_query = mysql_query("SELECT email FROM login 
			   		WHERE username = '" . $_POST['username'] . "' AND security_question = '" . $_POST['secquestion'] . "' AND security_answer = '" . $_POST['secanswer'] . "'")
					or die(mysql_error());
						
					list($email) = mysql_fetch_row($email_query);
						
					$pass = generatePassword();
					
					$insert_pass = MD5($pass);
					
					mysql_query("UPDATE login 
					SET password = '" . $insert_pass . "'
			   		WHERE username = '" . $_POST['username'] . "' AND security_question = '" . $_POST['secquestion'] . "' AND security_answer = '" . $_POST['secanswer'] . "'");
					
					$completed = 1;
				}
			}
			if($completed == 1)
			{
				echo "<br /><br /><br />";
				echo "<div class=\"success\">You have successfully complete the form!</div>\n<br />";
				echo "Please check you e-mail to retrieve your new password..";
				
				$username = $_POST['username'];
				
				$subject = 'New Password Retrieval Email';
							
				$body = 'Hello, ' . $_POST['username'] . '! <br />
				<p>
				Your new password is: ' . $pass . '<br /> <br />
				You can change your password by clicking on your username after you have logged in. <br /><br />
				</p>';
							
				$alt_body = 'Hello, ' . $_POST['username'] . '! 
				Your new password is: ' . $pass . '
				You can change your password by clicking on your username after you have logged in.';
				include_once "email.php";
				
				echo '<script type="text/javascript">';
				echo "\n<!--\n";
				echo "setTimeout('Redirect()',2000);";
				echo "\nfunction Redirect()";
				echo "{\n";
				echo "\tlocation.href = 'index.php';\n";
				echo "}\n";
				echo "\n--></script>\n";
			}
		}
		
		if($completed == 0)
		{
		?>
		<h1>Retrieve Password Form</h1>
		

			<form action="forget_password.php?try=true" method="post" class="niceform">	
			
				<div class="time"><span>*</span><label for="textinput1">User Name:</label></div>
 				 <input type="text" id="textinput1" name="username" size="20"  />
		 		<?php
				if (empty($_POST['username']) AND isset($_GET['try']))
				{
					echo " Please fill in a username.";
				}				
				else if(isset($_POST['username']) AND isset($_GET['try']))
				{
					$query = mysql_query("SELECT COUNT(id) FROM login 
		   			WHERE username = '" . $_POST['username'] . "'") 
					   or die(mysql_error());
		
					list($count_user) = mysql_fetch_row($query);
					if($count_user == 0)
					{
						echo $_POST['username'] . " is not a member.";
					}	
				}
				?>								
				 <br /><br />	
				 <span>*</span><label for="mySelect1">Security Question: </label>
 				 <select name="secquestion" id="mySelect1" class="width_385">
  				  <option value="0" >- Select One -</option>
  				  <option value="1" >What is the last name of your favorite musician?</option>
  				  <option value="2" >What was the last name of your favorite teacher?</option>
  				  <option value="3" >What was the last name of your best childhood friend?</option>
  				  <option value="4" >What is the name of the hospital where you were born?</option>
  				  <option value="5" >What is your main frequent flier number?</option>
  				  <option value="6" >What is the name of the street on which you grew up?</option>
  				  <option value="7" >What is the name of your favorite book?</option>
  				  <option value="8" >Who is your favorite author?</option>
 				  <option value="9" >Where did you spend your childhood summers?</option>
 				 </select>
		 		<?php
				if (isset($_GET['try']))
				{
					if($_POST['secquestion'] == 0)
					{
						echo "Please select a Security Question.";
					}
				}
				
				?>
			
				
				<br /><br /><div class="time"><span>*</span><label for="textinput3">Your Answer:</label></div>
				  <input type="text" id="textinput3" name="secanswer" size="20"/>
				<?php
				if (empty($_POST['secanswer']) AND isset($_GET['try']))
				{
					echo "Please answer your security question.";
				}
				else if(isset($_POST['secanswer']) AND isset($_GET['try']))
				{
					$security = mysql_query("SELECT COUNT(ID) FROM login 
		   			WHERE username = '" . $_POST['username'] . "' AND security_question = '" . $_POST['secquestion'] . "' AND security_answer = '" . $_POST['secanswer'] . "'") 
					   or die(mysql_error());
					
					list($count) = mysql_fetch_row($security);
					if($count != 1)
					{
						echo "Your security question answer is incorrect.";
					}
				}
				echo "\n";
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