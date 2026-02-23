<?php
class ModelWebsiteMenu extends Model {
	// baru 1 juli 2020
	public function getnamasubmenu($grouping) {
		$sql="SELECT * FROM menu_sub ";
		$sql.=" WHERE id = '".$grouping."' ";
		$sql.=" ORDER BY nama asc ";
		$query = $this->db->query($sql);
		if(!empty($query->row)){
			return $query->row['nama'];
		}else{
			return 0;
		}
	}

	public function getsubmenu($grouping) {
		$sql="SELECT * FROM menu_sub ";
		$sql.=" WHERE grouping LIKE '%".$grouping."%' ";
		$sql.=" ORDER BY nama asc ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	// end baru

	// baru 25 Juni 2020
	public function cekuser($user_id) {
		$query = $this->db->query("SELECT semuamenu FROM users WHERE user_id='$user_id' ");
		return $query->rows;
	}
	public function supersupermenu($supersub_id,$IN) {
		if(!empty($IN)){
			$query = $this->db->query("SELECT * FROM menu WHERE hapus='0' AND supersub_id='".$supersub_id."' AND url IN(".$IN.") ORDER BY nama ASC ");
		}else{
			$query = $this->db->query("SELECT * FROM menu WHERE hapus='0' AND supersub_id='".$supersub_id."'  ORDER BY nama ASC ");
		}
		
		return $query->rows;
	}
	public function getsupersupersub($supersub_id) {
		$query = $this->db->query("SELECT * FROM menu_supersub WHERE menusub_id='".$supersub_id."' ORDER BY nama ASC ");
		return $query->rows;
	}

	public function getsupersub($supersub_id,$IN) {
		if(!empty($IN)){
			$query = $this->db->query("SELECT * FROM menu WHERE hapus='0' AND sub_id='".$supersub_id."' AND url IN(".$IN.") ORDER BY nama ASC ");
		}else{
			$query = $this->db->query("SELECT * FROM menu WHERE hapus='0' AND sub_id='".$supersub_id."' ORDER BY nama ASC ");
		}
		
		return $query->rows;
	}

	public function getsub($IN) {
		if(!empty($IN)){
			$query = $this->db->query("SELECT * FROM menu_sub WHERE grouping LIKE'%".$IN."%' ");
		}else{
			$query = $this->db->query("SELECT * FROM menu_sub ORDER BY nama asc");
		}
		return $query->rows;
	}

	public function getmastermenu($IN) {
		if(!empty($IN)){
			$query = $this->db->query("SELECT grouping,sort_order FROM menu WHERE hapus='0' AND url IN(".$IN.") Group BY grouping,sort_order ORDER BY sort_order ASC");
		}else{
			$query = $this->db->query("SELECT grouping,sort_order FROM menu WHERE hapus='0' Group BY grouping,sort_order ORDER BY sort_order ASC");
		}
		
		return $query->rows;
	}

	public function getin($user_id) {
		$hasil=array();
		$query = $this->db->query("SELECT menu_url FROM user_menu WHERE user_id='$user_id' ");
		$d = $query->rows;
		if(!empty($d)){
			foreach($d as $m){
				$hasil[]="'".$m['menu_url']."'";
			}
		}
		$ha=implode(",", $hasil);
		return $ha;
	}


	public function getmenuforuser($user_id,$sort_order,$IN) {
		if(!empty($IN)){
			$query = $this->db->query("SELECT um.menu_url,m.nama FROM user_menu um JOIN menu m ON(m.url=um.menu_url) WHERE m.hapus='0' AND  um.user_id='$user_id' AND m.sort_order='$sort_order' AND m.sub_id='0' AND m.rinciantunggal='1' Group by um.menu_url,m.nama ORDER BY m.nama ASC ");
		}else{
			//$query = $this->db->query("SELECT um.menu_url,m.nama FROM user_menu um JOIN menu m ON(m.url=um.menu_url) WHERE m.hapus='0' AND  m.sort_order='$sort_order' AND m.sub_id='0' AND m.rinciantunggal='1' Group by um.menu_url,m.nama ORDER BY m.nama ASC ");
			$query = $this->db->query("SELECT m.nama,m.url as menu_url FROM menu m WHERE m.hapus='0' AND  m.sort_order='$sort_order' AND m.rinciantunggal='1' ORDER BY m.nama ASC");
		}
		return $query->rows;
	}
	// end baru
	public function addMenu($data) {
		$rinciantunggal=1;
		if($data['sub_id']>0){
			$rinciantunggal=0;
		}
		$inf=array(
			//'sort_order'	=>(int)$data['sort_order'],
			'sort_order'=>(int)$data['sort_order'],
			'nama'	=>$this->db->escape($data['nama']),
			'url'	=>$this->db->escape($data['url']),
			'grouping'	=>$this->db->escape($data['grouping']),
			'sub_id'=>$data['sub_id'],
			'rinciantunggal'=>$rinciantunggal,
		);
		$this->db->insert('menu',$inf);
		$information_id=$this->db->getLastId();

		$this->db->query("INSERT INTO ".DB_PREFIX."user_menu(user_id,menu_url) values('1','".$this->db->escape($data['url'])."')");
		$this->db->query("INSERT INTO ".DB_PREFIX."user_menu(user_id,menu_url) values('12','".$this->db->escape($data['url'])."')");

	}

	public function editMenu($menu_id, $data) {
		$rinciantunggal=1;
		if($data['sub_id']>0){
			$rinciantunggal=0;
		}
		$inf=array(
			'sort_order'	=>(int)$data['sort_order'],
			'grouping'	=>$this->db->escape($data['grouping']),
			'url'	=>$this->db->escape($data['url']),
			'nama'	=>$this->db->escape($data['nama']),
			'sub_id'=>$data['sub_id'],
			'rinciantunggal'=>$rinciantunggal,
		);
		$where=array(
			'menu_id'	=> $menu_id
		);
		$this->db->update('menu',$inf,$where);


	}

	public function deleteMenu($menu_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "menu set hapus='1' WHERE menu_id = '" . (int)$menu_id . "'");
	}

	public function getMenu($information_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu WHERE menu_id = '" . (int)$information_id . "'");

		return $query->row;
	}

	public function getMenuByGroup($group){
		$sql="SELECT * FROM menu WHERE lower(grouping) = '".utf8_strtolower($group)."' ORDER BY nama ASC";
		$query = $this->db->query($sql);

		return $query->rows;



	}

	public function getMenus($data = array()) {


		$sql = "SELECT * FROM " . DB_PREFIX . "menu i WHERE i.menu_id > 0 and i.hapus=0 ";
		if(!empty($data['filter_name'])){
			$sql .=" AND lower(nama) LIKE '%".utf8_strtolower($data['filter_name'])."%' ";
		}
		if(!empty($data['filter_group'])){
			$sql .=" AND lower(grouping) LIKE '%".utf8_strtolower($data['filter_group'])."%' ";
		}
			$sort_data = array(
				'i.nama',
				'i.url',
				'i.grouping',
				'i.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY i.grouping";
			}

			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

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

	public function getTotalMenus($data) {

				$sql="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "menu WHERE menu_id > 0";
				if(!empty($data['filter_name'])){
					$sql .=" AND lower(nama) LIKE '%".utf8_strtolower($data['filter_name'])."%' ";
				}
				if(!empty($data['filter_group'])){
					$sql .=" AND lower(grouping) LIKE '%".utf8_strtolower($data['filter_group'])."%' ";
				}
				$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function addusermenu($users,$menu_url){
		$this->db->query("DELETE FROM ".DB_PREFIX."user_menu WHERE menu_url='".$this->db->escape($menu_url)."' ");
		//if(!empty($data['users'])){
			foreach($users as $u){
				//cek

				//$cek=$this->db->query("SELECT * FROM user_menu WHERE user_id='".$user_id."' AND menu_url='".$this->db->escape($menu_url)."'");
				$this->db->query("INSERT INTO ".DB_PREFIX."user_menu(user_id,menu_url) values('".$u."','".$this->db->escape($menu_url)."')");
			}
		//}
	}
	public function getUsermenus($menu_id) {
		$menu=$this->getMenu($menu_id);
	  $product_category_data = array();

	  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user_menu WHERE menu_url = '" . $this->db->escape($menu['url']) . "'");

	  foreach ($query->rows as $result) {
	    $product_category_data[] = $result['user_id'];
	  }

	  return $product_category_data;
	}
	public function cekakses($menu_url,$user_id){
		//$akses=false;
		$sql = "SELECT DISTINCT * FROM user_menu WHERE menu_url='".$this->db->escape($menu_url)."'";
		$q=$this->db->query($sql);
		if($user_id == 85 | $user_id == 12 | $user_id == 33 | $user_id == 32){
			return true;
		}else{
			if(empty($q->row)){
				return false;
			}else{
				$cekq="SELECT * FROM user_menu WHERE menu_url='".$this->db->escape($menu_url)."' AND user_id='".$user_id."'";
				$q2=$this->db->query($cekq);

				if(empty($q2->row)){
					return false;
				}else{
					return true;
				}
			}
		}
	}

	public function addhistoryakses($route,$user_id){
		$this->db->insert('history_akses',array('user_id'=>$user_id,'tanggal'=>date('Y-m-d H:i:s'),'menu_url'=>$route));
	}
}
?>
