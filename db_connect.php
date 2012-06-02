<?php

/**
 * @author Kenny Ma
 * @copyright 2008
 */

// Open a connection to the DB


//$conn = mysql_connect('localhost', 'root', 'hello') or die(mysql_error());
$conn = mysql_connect('localhost', 'user', '') or die(mysql_error());

//mysql_select_db('simcaldb', $conn);
mysql_select_db('simcal', $conn);

?>