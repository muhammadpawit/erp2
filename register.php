<?php
require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	
if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
	
	

	$user_id 	= $_GET['user_id'];

	echo "$user_id;SecurityKey;15;".HTTP_SERVER."process_register.php;".HTTP_SERVER."getac.php";
	
}

?>