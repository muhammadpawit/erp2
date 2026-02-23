<?php
class User {
	private $user_id;
	private $user_group_id;
	private $username;
	private $name;
  	private $permission = array();

  	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

    	if (isset($this->session->data['user_id'])) {

			$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "users  WHERE user_id= '" . (int)$this->session->data['user_id'] . "' AND status = '1'");

			if ($user_query->num_rows) {
				$this->user_id = $user_query->row['user_id'];
				$this->user_group_id = $user_query->row['user_group_id'];
				$this->username = $user_query->row['username'];
				$this->name = $user_query->row['firstname'];

      			$this->db->query("UPDATE " . DB_PREFIX . "users  SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$this->session->data['user_id'] . "'");

      			$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

	  			$permissions = unserialize($user_group_query->row['permission']);

				if (is_array($permissions)) {
	  				foreach ($permissions as $key => $value) {
	    				$this->permission[$key] = $value;
	  				}
				}
				//update status option gudang temporary aja

			} else {
				$this->logout();
			}
    	}
  	}

		public function login($username, $password) {
			$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "users  WHERE username = '" . $this->db->escape($username) . "' AND status = '1'");
			if(!empty($user_query)){
				$encpassword= SHA1($user_query->row['salt'].SHA1($user_query->row['salt'].SHA1($this->db->escape($password))));
				if($encpassword == $user_query->row['password']){
					if ($user_query->num_rows) {
					$this->session->data['user_id'] = $user_query->row['user_id'];

					$this->user_id = $user_query->row['user_id'];
					$this->username = $user_query->row['username'];


		      		$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

			  		$permissions = unserialize($user_group_query->row['permission']);

					if (is_array($permissions)) {
						foreach ($permissions as $key => $value) {
							$this->permission[$key] = $value;
						}
					}



		      		return true;
		    	} else {
		      		return false;
		    	}
				}else{
					return false;
				}
			}else{
				return false;
			}
			//$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "users  WHERE username = '" . $this->db->escape($username) . "' AND (password = '".$encpassword."') OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'");

  	}

  	public function logout() {
		unset($this->session->data['user_id']);

		$this->user_id = '';
		$this->username = '';

		session_destroy();
  	}

  	public function hasPermission($key, $value) {
    	/*if (isset($this->permission[$key])) {
	  		return in_array($value, $this->permission[$key]);
		} else {
	  		return false;
		}*/
		return true;
  	}

  	public function isLogged() {
    	return $this->user_id;
  	}

  	public function getId() {
    	return $this->user_id;
  	}
		public function getGroupId() {
    	return $this->user_group_id;
  	}

  	public function getUserName() {
    	return $this->username;
  	}

	public function getName() {
    	return $this->name;
  	}
  	public function getGudang(){
  		$user_id=$this->user_id;
  		$query=$this->db->query("SELECT gudang_id FROM ".DB_PREFIX."user_gudang WHERE user_id='".$user_id."' ");
		//return $query->rows;
		$gudang=array();
		foreach($query->rows as $g){
			$gudang[]=$g['gudang_id'];
		}
		return $gudang;
  	}

public function getKantor(){
	$user_id=$this->user_id;
	$q=$this->db->query("SELECT * FROM users WHERE user_id='".$user_id."'");
	return $q->row['gudang_id'];
}

		public function getToko(){
  		$user_id=$this->user_id;
  		$query=$this->db->query("SELECT gudang_id FROM ".DB_PREFIX."user_toko WHERE user_id='".$user_id."' ");
		//return $query->rows;
		$gudang=array();
		foreach($query->rows as $g){
			$gudang[]=$g['gudang_id'];
		}
		return $gudang;
  	}

		public function getPameran(){
  		$user_id=$this->user_id;
  		$query=$this->db->query("SELECT gudang_id FROM ".DB_PREFIX."user_pameran WHERE user_id='".$user_id."' ");
		//return $query->rows;
		$gudang=array();
		foreach($query->rows as $g){
			$gudang[]=$g['gudang_id'];
		}
		return $gudang;
  	}

  	public function addUserActivity($data){
  		$act=array(
				'user_id'	=> $this->user_id,
				'activity'	=> $this->db->escape($data['activity']),
				'menu'	=> $this->db->escape($data['menu']),
				'date_added'	=> date('Y-m-d h:i:s',time())
			);
  		$this->db->insert('user_activity',$act);
  	}

	public function tokoPermission($user_id,$pameran_id){
		$sql="SELECT COUNT(*) as total FROM ".DB_PREFIX."user_toko WHERE user_id='".$user_id."' AND gudang_id='".$pameran_id."' ";

		$res=$this->db->query($sql);
		if($res->row['total'] > 0){
			return true;
		}
		else{
			return false;
		}
	}

	public function pameranPermission($user_id,$pameran_id){
		$sql="SELECT COUNT(*) as total FROM ".DB_PREFIX."user_pameran WHERE user_id='".$user_id."' AND gudang_id='".$pameran_id."' ";

		$res=$this->db->query($sql);
		if($res->row['total'] > 0){
			return true;
		}
		else{
			return false;
		}
	}
	public function getOs(){
		$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
		$os_platform    =   "Unknown OS Platform";

    $os_array       =   array(
                            '/windows nt 10/i'     =>  'Windows 10',
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );

    foreach ($os_array as $regex => $value) {

        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }

    }

    return $os_platform;

	}
	public static function getBrowser()
 {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $browser        =   "Unknown Browser";

    $browser_array  = array('/msie/i'       =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
                            '/mobile/i'     =>  'Handheld Browser');

    foreach ($browser_array as $regex => $value)
    {
        if($found)
         break;
        else if (preg_match($regex, $user_agent,$result))
        {
            $browser    =   $value;
        }
    }
    return $browser;
 }


}
?>
