<?php
class ModelUserUser extends Model {
	public function addUser($data) {
		$user=array(
			'username'=>$this->db->escape($data['username']),
			'salt'=>$this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)),
			'password'	=>$this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))),
			'firstname'	=>$this->db->escape($data['firstname']),
			'email'	=>$this->db->escape($data['email']),
			'user_group_id'=>$data['user_group_id'],
			'status'	=>$data['status'],
			'date_added'	=>date('Y-m-d H:i:s'),
			'npwp'	=> $this->db->escape($data['npwp']),
			'alamat'	=> $this->db->escape($data['alamat']),
			'telephone'	=> $this->db->escape($data['telephone']),
			'hp'	=> $this->db->escape($data['hp']),
			'tempatlahir'	=> $this->db->escape($data['tempatlahir']),
			'tgl_lahir'	=> empty($data['tgl_lahir'])?date('Y-m-d'):$data['tgl_lahir'],
			'agama'	=> $data['agama'],
			'ktp'	=> $data['ktp'],
			'gudang_id'	=> $data['gudang_id'],
			'jeniskelamin'	=> $data['jeniskelamin'],
			'divisi'	=> $data['divisi'],
			'jenispegawai'	=> $data['jenispegawai'],
			'tglmasuk'	=> empty($data['tglmasuk'])?date('Y-m-d'):$data['tglmasuk'],
			'tglakhir'	=> empty($data['tglakhir'])?date('Y-m-d'):$data['tglakhir'],
			'pendidikan'	=> $data['pendidikan'],
			'statusgaji'	=> $data['statusgaji'],
			'bank'	=> $data['bank'],
			'rekening'	=> $data['rekening'],
			'statuskawin'	=> $data['statuskawin'],
			'namakerabat'	=> $this->db->escape($data['namakerabat']),
			'telpkerabat'	=> $data['telpkerabat'],
			'alamatkerabat'	=> $this->db->escape($data['alamatkerabat']),
			'foto'	=> $this->db->escape($data['foto']),
			'hapus'	=> 0

		);
		$this->db->insert("users",$user);

		$user_id=$this->db->getLastId();

	}

	public function editUser($user_id, $data) {
		$user=array(
			'username'=>$this->db->escape($data['username']),
			'firstname'	=>$this->db->escape($data['firstname']),
			'email'	=>$this->db->escape($data['email']),
			'user_group_id'=>$data['user_group_id'],
			'status'	=>$data['status'],
			'date_added'	=>date('Y-m-d H:i:s'),
			'npwp'	=> $this->db->escape($data['npwp']),
			'alamat'	=> $this->db->escape($data['alamat']),
			'telephone'	=> $this->db->escape($data['telephone']),
			'hp'	=> $this->db->escape($data['hp']),
			'tempatlahir'	=> $this->db->escape($data['tempatlahir']),
			'tgl_lahir'	=> empty($data['tgl_lahir'])?date('Y-m-d'):$data['tgl_lahir'],
			'agama'	=> $data['agama'],
			'ktp'	=> $data['ktp'],
			'gudang_id'	=> $data['gudang_id'],
			'jeniskelamin'	=> $data['jeniskelamin'],
			'divisi'	=> $data['divisi'],
			'jenispegawai'	=> $data['jenispegawai'],
			'tglmasuk'	=> empty($data['tglmasuk'])?date('Y-m-d'):$data['tglmasuk'],
			'tglakhir'	=> empty($data['tglakhir'])?date('Y-m-d'):$data['tglakhir'],
			'pendidikan'	=> $data['pendidikan'],
			'statusgaji'	=> $data['statusgaji'],
			'bank'	=> $data['bank'],
			'rekening'	=> $data['rekening'],
			'statuskawin'	=> $data['statuskawin'],
			'namakerabat'	=> $this->db->escape($data['namakerabat']),
			'telpkerabat'	=> $data['telpkerabat'],
			'alamatkerabat'	=> $this->db->escape($data['alamatkerabat']),
			'foto'	=> $this->db->escape($data['foto'])

		);

		$where=array(
			'user_id'	=> $user_id
		);

		$this->db->update("users",$user,$where);

		if ($data['password']) {
			$user=array(
				'salt'=>$this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)),
				'password'	=>$this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))),

			);
			$this->db->update("users",$user,$where);
		}


	}

	public function editPassword($user_id, $password) {
		$user=array(
			'salt'=>$this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)),
			'password'	=>$this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))),
			'code'	=>null
		);
		$where=array(
			'user_id'	=> $user_id
		);
		$this->db->update("users",$user,$where);

	}

	public function editCode($email, $code) {
		$user=array(
			'code'	=>$code
		);
		$where=array(
			'email'	=> $email
		);
		$this->db->update("users",$user,$where);

	}

	public function editPermission($user_id, $code) {
		$user=array(
			'permission'	=>$permission
		);
		$where=array(
			'user_id'	=> $user_id
		);
		$this->db->update("users",$user,$where);

	}

	public function deleteUser($user_id) {
		//$this->db->query("DELETE FROM " . DB_PREFIX . "users WHERE user_id = '" . (int)$user_id . "'");
		$this->db->query("UPDATE users set hapus='1' WHERE user_id = '" . (int)$user_id . "'");
	}

	public function getUser($user_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "users WHERE user_id = '" . (int)$user_id . "'");

		return $query->row;
	}

	public function getUserByUsername($username) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "users WHERE username = '" . $this->db->escape($username) . "'");

		return $query->row;
	}

	public function getUserByCode($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "users WHERE code = '" . $this->db->escape($code) . "' AND code != ''");

		return $query->row;
	}

	public function getUsers($data = array()) {
		$sql = "SELECT u.*,g.name as namajabatan,d.name as namadivisi,gd.nama FROM " . DB_PREFIX . "users u LEFT JOIN ".DB_PREFIX."divisi d ON(u.divisi=d.id)  LEFT JOIN ".DB_PREFIX."gudang gd ON(u.gudang_id=gd.gudang_id) LEFT JOIN ".DB_PREFIX."user_group g ON(u.user_group_id=g.user_group_id) WHERE u.hapus=0 ";

		if(!empty($data['filter_name'])){
			$sql .= " AND lower(firstname) LIKE '%".utf8_strtolower($data['filter_name'])."%'";
		}
		if(!empty($data['filter_tglakhir'])){
			$sql .= " AND tglakhir='".$data['filter_tgllakhir']."'";
		}
		if(!empty($data['filter_gudang_id'])){
			$sql .= " AND u.gudang_id='".$data['filter_gudang_id']."'";
		}
		if(!empty($data['filter_statuspegawai'])){
			$sql .= " AND jenispegawai='".$data['filter_statuspegawai']."'";
		}
		if(!empty($data['filter_divisi'])){
			$sql .= " AND u.divisi='".$data['filter_divisi']."'";
		}
		if(!empty($data['filter_jabatan'])){
			if($data['filter_jabatan'] != 21){
				$sql .= " AND u.user_group_id='".$data['filter_jabatan']."'";
			}else{
				$sql .= " AND u.user_group_id IN(21,1,25)";
			}
		}
		if(isset($data['filter_status'])){
			if($data['filter_status'] != null){
				$sql .= " AND u.status='".$data['filter_status']."'";
			}
		}
		/*$sort_data = array(
			'username',
			'status',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY username";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}*/
		//$sql .=" ORDER BY firstname ASC";
		$sql .=" ORDER BY resign ASC,firstname ASC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalUsers($data=array()) {
  	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "users u LEFT JOIN ".DB_PREFIX."divisi d ON(u.divisi=d.id)  LEFT JOIN ".DB_PREFIX."gudang gd ON(u.gudang_id=gd.gudang_id) LEFT JOIN ".DB_PREFIX."user_group g ON(u.user_group_id=g.user_group_id) WHERE u.hapus=0";
		if(!empty($data['filter_name'])){
			$sql .= " AND lower(firstname) LIKE '%".utf8_strtolower($data['filter_name'])."%'";
		}
		if(!empty($data['filter_tglakhir'])){
			$sql .= " AND tglakhir='".$data['filter_tgllakhir']."'";
		}
		if(!empty($data['filter_gudang_id'])){
			$sql .= " AND u.gudang_id='".$data['filter_gudang_id']."'";
		}
		if(!empty($data['filter_statuspegawai'])){
			$sql .= " AND jenispegawai='".$data['filter_statuspegawai']."'";
		}
		if(!empty($data['filter_divisi'])){
			$sql .= " AND u.divisi='".$data['filter_divisi']."'";
		}
		if(!empty($data['filter_jabatan'])){
			$sql .= " AND u.user_group_id='".$data['filter_jabatan']."'";
		}
		if($data['filter_status'] != null){
			$sql .= " AND u.status='".$data['filter_status']."'";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function getTotalUsersByGroupId($user_group_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "users WHERE user_group_id = '" . (int)$user_group_id . "'");

		return $query->row['total'];
	}

	public function getTotalUsersByEmail($email) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "users WHERE email = '" . $this->db->escape($email) . "'");

		return $query->row['total'];
	}

	public function getUserGudang($user_id){
		$query=$this->db->query("SELECT gudang_id FROM ".DB_PREFIX."user_gudang WHERE user_id='".$user_id."' ");
		//return $query->rows;
		$gudang=array();
		foreach($query->rows as $g){
			$gudang[]=$g['gudang_id'];
		}
		return $gudang;
	}
	public function getAllUser(){
		$sql = "SELECT * FROM " . DB_PREFIX . "users";


		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function addGudang($user_id,$data){
		$this->db->delete('user_gudang',array('user_id'=>$user_id));
		foreach($data as $d){

			$ug=array(
				'user_id'=>$user_id,
				'gudang_id'=>$d
			);

			$this->db->insert('user_gudang',$ug);
		}
	}
	public function addKontraks($pegawai_id,$data){
		//$this->db->delete('customer_document',array('customer_id'=>$customer_id));
		$upd=array(
			'status'	=> 0
		);
		$this->db->update("kontrak_pegawai",$upd,array('pegawai_id'=>$pegawai_id));
		$im=array(
			'pegawai_id'	=> $pegawai_id,
			'keterangan'	=> $this->db->escape($data['keterangan']),
			'file'	=> $this->db->escape(html_entity_decode($data['file'], ENT_QUOTES, 'UTF-8')),
			'date_added'	=> date('Y-m-d H:i:s'),
			'date_modified'	=> date('Y-m-d H:i:s'),
			'no_kontrak'	=> !empty($data['no_kontrak'])?$data['no_kontrak']:"",
			'tglawal'	=> $data['tglawal'],
			'tglakhir'	=> $data['tglakhir'],
			'status'	=> 1,
			'hapus'	=> 0
		);
		$this->db->insert("kontrak_pegawai",$im);
		$id=$this->db->getLastId();

		$no_kontrak=$id."/SK"."/".date('m')."/".date('Y');

		if(empty($data['no_kontrak'])){
			$upd=array(
				'no_kontrak'	=> $no_kontrak
			);
			$this->db->update("kontrak_pegawai",$upd,array('id'=>$id));
		}

		$this->db->update("users",array('tglakhir'=>$data['tglakhir']),array('user_id'=>$pegawai_id));

	}
	public function getKontraks($pegawai_id,$data) {

			$sql="SELECT * FROM " . DB_PREFIX . "kontrak_pegawai WHERE pegawai_id = '".$pegawai_id."' AND (hapus IS null OR hapus=0) ORDER BY tglakhir DESC";
			if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
		}

			$query=$this->db->query($sql);

		return $query->rows;
	}
	public function getTotalkontraks($pegawai_id,$data) {

			$sql="SELECT COUNT(*) as total FROM " . DB_PREFIX . "kontrak_pegawai WHERE pegawai_id = '" . (int)$pegawai_id . "' AND (hapus IS null OR hapus=0) ";


			$query=$this->db->query($sql);

		return $query->row['total'];
	}
	public function editAkses($user_id,$data) {
		$this->db->query("DELETE FROM ".DB_PREFIX."user_menu WHERE user_id=" . (int)$user_id . " ");
		foreach ($data as $url) {
			$pc = array(
				'user_id'	=> $user_id,
				'menu_url'	=> $url
			);
			$this->db->insert('user_menu',$pc);
			}
	}

	public function getUserMenus($user_id) {
	  $product_category_data = array();

	  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user_menu WHERE user_id = '" . (int)$user_id . "'");

	  foreach ($query->rows as $result) {
	    $product_category_data[] = $result['menu_url'];
	  }

	  return $product_category_data;
	}

	public function editAksesData($user_id,$data) {
		$this->db->query("DELETE FROM ".DB_PREFIX."akses_data WHERE user_id=" . (int)$user_id . " ");
		foreach ($data as $d) {
			$pc = array(
				'user_id'	=> $user_id,
				'akses'	=> $d['akses'],
				'nilai'	=> $d['nilai']
			);
			$this->db->insert('akses_data',$pc);
			}
	}

	public function getUserAksesData($user_id) {
	  $product_category_data = array();

	  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "akses_data WHERE user_id = '" . (int)$user_id . "'");

	  foreach ($query->rows as $result) {
	    $product_category_data[$result['akses']] = $result['nilai'];
	  }

	  return $product_category_data;
	}

	public function getAksesData($user_id,$menu_id){
		$q="SELECT COALESCE(nilai,2) as nilai FROM akses_data WHERE user_id='".$user_id."' AND akses='".$menu_id."'";
		$query=$this->db->query($q);
		return $query->row['nilai'];
	}
}
?>
