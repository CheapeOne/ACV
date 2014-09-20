<?php

	session_start();
	$userName = $_GET['email'];
	$geoLocation = 0;

	$_SESSION['email'] = $email;
	$_SESSION['geolocation'] = $geoLocation;

?>