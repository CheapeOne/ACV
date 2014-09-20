<?php

	session_start();

	if (!(isset($_SESSION['email']) && isset($_SESSION['geolocation']))) {// if these aren't set, we know user isn't logged in, so tell them to do so!

		$arr = array (

		"email" => null,
		"geolocation" => null

		);

		echo json_encode($arr);
	}

	else {

		$arr = array (

		"email" => $_SESSION['email'],
		"geolocation" => $_SESSION['geolocation']

		);

		echo json_encode($arr);
	}


?>