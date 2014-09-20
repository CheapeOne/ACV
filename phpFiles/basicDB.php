<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	mysql_error();
	$log_file = 'ACV_log_file.txt';
	
	function writeToLog($msg){
		put_file_contents($log_file,$msg,FILE_APPEND);
		echo '[{$log_file}] '.time().' $msg';
	}
	
	function connectToDB($db_user, $db_password, $db_host, $db_name) {
		try {
			$dbconn = new PDO('mysql:host=localhost;dbname={$db_name};charset=utf8, {$username}, {$password}');
			return $dbconn;
			
		} catch (PDOException $ex) {
			$msg = 'Connection failed:'.$ex->getMessage()';
			writeToLog($msg);
		}
	}

?>