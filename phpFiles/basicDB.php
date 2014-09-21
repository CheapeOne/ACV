<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	mysql_error();
	$log_file = 'ACV_log_file.txt';
	
	function writeToLog($msg){
	//assuming this file has been initialized in a main function
		global $log_file;
		file_put_contents($log_file,$msg,FILE_APPEND);
		echo "[{$log_file}] ".time()." $msg\n";
	}
	
	function connectToDB($db_user, $db_password, $db_host, $db_name) {
		try {
			$dbconn = new PDO("mysql:host=$db_host;dbname=$db_name", "$db_user", "$db_password");
			
			return $dbconn;
			
		}
		 catch (PDOException $ex) {
			$msg = 'Connection failed:'.$ex->getMessage();
			writeToLog($msg);
			return false;
		}
	}

?>