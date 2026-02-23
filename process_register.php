<?php
require_once('config.php');
require_once(DIR_SYSTEM . 'startup.php');
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if (isset($_POST['RegTemp']) && !empty($_POST['RegTemp'])) {
		
		$data 		= explode(";",$_POST['RegTemp']);
		$vStamp 	= $data[0];
		$sn 		= $data[1];
		$user_id	= $data[2];
		$regTemp 	= $data[3];
		
		//$device = getDeviceBySn($sn);
		
		$db->query("UPDATE ".DB_PREFIX."pegawai_toko SET finger='".$regTemp."' WHERE pegawai_id='".$user_id."'");
		$res['result'] = true;		
			echo "empty";
			
		
		
}

?>