<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	mysql_error();
	
	function connectToDB($user, $password, $server_host, $db_name) {

		$conn = mysql_connect($server_host, $user, $password);

		if (!$conn) {

			die("Could not connect: " . mysql_error());
			//
		} 

		
		mysql_select_db($db_name, $conn);

	}


	/////////MAY NOT NEED THESE//////////
	function sendFunction($user, $password, $server_host, $db_name, $table, $query) {

		connectToDB($user, $password, $server_host, $db_name);
		mysql_query($query);
	}

	function retrieveFunction($user, $password, $server_host, $db_name, $table, $query) {

		connectToDB($user, $password, $server_host, $db_name);
		$retrieved = mysql_query($query);
		return mysql_fetch_array($retrieved);
	}

?>