<?php


$name = $_GET['input'];

session_destroy();
session_start();

	if (!isset($_SESSION['user'])) {

	$_SESSION['user'] = $name;
	$_SESSION['deathTime'] = time() + 90;
	} 

	else {

		if (time() > $_SESSION['deathTime']) {

			
		}
	}

?>