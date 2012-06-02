<?php

////////////////////////////////////////////////////
//
// Basic login system tutorial 
// By Combined Minds <info@combined-minds.net>
//
// www.Combined-Minds.net
//
////////////////////////////////////////////////////

// Open a connection to the DB
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
		<p>
			Also, you must enter a valid e-mail address since there will be an e-mail sent to you for your account activation.
		</p>
		</div>
	</div>	
	<!-- End of left column -->

	<!-- Beginning of right column -->	
	<div id="rightcol2">
		<?php
		if(!empty($_POST['submitted']))
		{			
				if((isset($_POST['username'])) AND (isset($_POST['email'])) AND !empty($_POST['password']) AND 
				!empty($_POST['retypepassword'])  AND  !empty($_POST['secquestion']) AND !empty($_POST['secanswer']) AND
				!empty($_POST['agree']))
				{
					$query = mysql_query("SELECT COUNT(id) FROM login 
		   			WHERE username = '" . $_POST['username'] . "' OR email = '" . $_POST['email'] . "'") 
					   or die(mysql_error());
		
					list($count_user) = mysql_fetch_row($query);
					
					if(($_POST['password']==$_POST['retypepassword']) AND $count_user==0 AND $_POST['secquestion']!=0)
					{
						$completed = 1;
						
						$username = addslashes($_POST['username']);
						$password = MD5($_POST['password']);
						$email = addslashes($_POST['email']);
						$secquestion = addslashes($_POST['secquestion']);
					    $secanswer = addslashes($_POST['secanswer']);
					    $false = false;
					    
						// Username and Email are free!
						mysql_query("INSERT INTO login
									(username, password, email, security_question, security_answer, activated)
									VALUES
					('" . $username . "', '" . $password . "', '" . $email . "' , '" . $secquestion . "' , '" . $secanswer . "' , '" . $false . "')") 
					or die(mysql_error());
						echo "<br /><br /><br />";
						echo "<br/><div class=\"success\">You have successfully registered!";
						echo "<br />Please check you e-mail for an activation link to activate your account.<br />";
						echo "You will now be redirected to the main page in 5 seconds.</div><br/><br/>";
						
						$subject = 'Account Activation Email';
						
						$body = 'Welcome, ' . $username . '! <br />
						<p>
						Your account has been created successfully and you can now activate you account by going to the link below. <br />
						Your username is: ' . $username . ' and <br />
						your password is: ' . $_POST['password'] . '<br /> <br />

						Please visit the link below to activate your account: <br />

						<a href="http://www.sumsy.com/demos/simcal/activation.php?uid=' . MD5($username) . '">
						http://www.sumsy.com/demos/simcal/activation.php?uid=' . MD5($username) . '</a>
						
						</p>';
						
						$alt_body = 'Welcome, ' . $username . '! 
						Your account has been created successfully and you can now activate you account by going to the link below.

						Please visit the link below to activate your account: 
						http://cmpt470.csil.sfu.ca:8007/activation.php?uid=' . MD5($username);
						include_once "email.php";
						
						echo '<script type="text/javascript">';
						echo "\n<!--\n";
						echo "setTimeout('Redirect()',5000);";
						echo "\nfunction Redirect()";
						echo "{\n";
						echo "\tlocation.href = 'index.php';\n";
						echo "}\n";
						echo "\n--></script>\n";
					}
				}
		}
		
		
		if ($completed == 0)
		{
		?>
		<h1>Registration Form</h1>
		

			<form action="register.php?try=true" method="post" class="niceform">	
			
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
					if($count_user != 0)
					{
						echo $_POST['username'] . " has already been taken, please choose another username.";
					}	
				}
				?>		
			
				
			<br /><br /><div class="time"><span>*</span><label for="textinput2">User Email:</label></div>
 				 <input type="text" id="textinput2" name="email" value="" size="20" />
		 		<?php
				if (empty($_POST['email']) AND isset($_GET['try']))
				{
					echo " Please fill in an e-mail address.";
				}				
				else if(isset($_POST['email']) AND isset($_GET['try']))
				{
					// SQL save variables
					$email = addslashes($_POST['email']);

					$query = mysql_query("SELECT COUNT(id) FROM login 
		   			WHERE email = '" . $email . "'") or die(mysql_error());
		
					list($count_user) = mysql_fetch_row($query);
					
					if($count_user != 0)
					{
						echo " " . $email . " has already been taken, please choose another email address.";
					}	
				}
				?>
			
				
		<br />	<br /><div class="time"><span>*</span><label for="passwordinput">User Password:</label></div>
 				 <input type="password" id="passwordinput" name="password" size="20"/>
		 		<?php
				if (((empty($_POST['password'])) OR (empty($_POST['retypepassword']))) AND isset($_GET['try']))
				{
					echo " Please fill in a password.";
				}
				else if(isset($_GET['try']))
				{
					if(!($_POST['password'] == $_POST['retypepassword']))
					{
						echo " Retyped password and password does not match, please make sure both matches.";
					}
				}
				?>
			
				
			<br /><br /><div class="time"><span>*</span><label for="passwordinput2">Retype Password:</label></div>
 				 <input type="password" id="passwordinput2" name="retypepassword" value="" size="20" />
				
				
			<br /><br />	<span>*</span><label for="mySelect1">Security Question: </label>
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
						echo " Please select a Security Question.";
					}
				}
				?>
			
				
				<br /><br /><div class="time"><span>*</span><label for="textinput3">Your Answer:</label></div>
				  <input type="text" id="textinput3" name="secanswer" size="20"/>
		 		<?php
				if (empty($_POST['secanswer']) AND isset($_GET['try']))
				{
					echo " Please write an answer for your security question.";
				}
				?>
			
				
				<br /><br /><input id="check1" name="agree" type="checkbox" value="checkbox" /><label for="check1">
				I have also read and agree to the <a href="agreement.php" class="term">Terms of Service</a></label><br />
		 		<?php
				if (empty($_POST['agree']) AND isset($_GET['try']))
				{
					echo "<br />You must agree to our Terms of Service before you may allow to be our member.";
				}
				?>
			
				
			
				<br /><br /><input name="submitted" type="hidden" value="true" />

				<input type="submit" name="Register" />
			
		
			   </form>
	
		<?php
		}
		?>
	</div>
	<!-- End of right column -->
</div>	
<!-- End of main frame -->

<?php include_once "footer.php" ?>
