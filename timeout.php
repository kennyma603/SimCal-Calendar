<?php

/**
 * @author Esmond Man
 * @copyright 2008
 */

$timeout_min = 15; //minutes of inactivity to log out after
$timeout_length = $timeout_min * 60;


$current_time = gettimeofday(true); //get current time

//$_SESSION['loginTime']=$current_time; // set login time
//$_SESSION['lastactivity']=$current_time; // set last activity 


if (($current_time - $_SESSION['lastactivity']) > $timeout_length) {
	session_destroy();
	
	echo '<script type="text/javascript">';
	echo "\n<!--\n";
	echo "\twindow.location = 'index.php';\n";
	echo "\n--></script>\n";
}
else
{
$_SESSION['lastactivity'] = $current_time; 
}
?>