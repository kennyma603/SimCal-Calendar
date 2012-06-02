<?php

/**
 * @author Kenny Ma
 * @copyright 2008
 */

// Open a connection to the DB


//$conn = mysql_connect('localhost', 'root', 'hello') or die(mysql_error());
$conn = mysql_connect('localhost', 'km603co_km603', '3368807') or die(mysql_error());

//mysql_select_db('simcaldb', $conn);
mysql_select_db('km603co_demos_simcal', $conn);

?>