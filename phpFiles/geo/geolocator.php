<?php

require "dbip.class.php";

$db_host = "localhost";
$db_user = "root";
$db_password = "yuhclickyuh";
$db_name = "acvdatabase";
$ip_table = "dbip_lookup";

$ip_address = $_SERVER['REMOTE_ADDR'];//$_GET['ip_address'];

try {

	$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
	$dbip = new DBIP($db);

	$inf = $dbip->Lookup($ip_address);

	echo $inf->city.', '.$inf -> stateprov.', '.$inf -> country;

} catch (DBIP_Exception $e) {

	echo "error: {$e->getMessage()}\n";

}

?>