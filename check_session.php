<?php

/**
 * @author 3ddown.com
 * @copyright 2008
 */

if(!isset($_SESSION['simcal_username'])) {
	//redirect user to log inpage.
	header('location: login.php');
}

if(!empty($_SESSION['lastactivity']))
{
	include_once"timeout.php";
}
?>