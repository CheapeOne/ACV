<?php

	session_start();
	$userName = $_GET['user'];
	$geoLocation = 0;

	$_SESSION['user'] = $userName;
	$_SESSION['geolocation'] = $geoLocation;

?>