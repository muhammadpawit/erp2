<?php
require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		
if (isset($_GET['vc']) && !empty($_GET['vc'])) {
	$dat=$db->query("SELECT * FROM ".DB_PREFIX." device WHERE vc='".$_GET['vc']."'");
    $data=$dat->row;
		
	//$data = getDeviceAcSn($_GET['vc']);
	
	echo $data['ac'].$data['sn'];
	
}

?>