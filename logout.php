<?php

/**
 * @author Kenny Ma
 * @copyright 2008
 */

// you have to open the session to be able to modify or remove it
session_start();

// or this would remove all the variable in the session
session_unset();

//destroy the session
session_destroy(); // you have to open the session to be able to modify or remove it

header("Location: index.php"); // Goes back to index


?>