<?php
class ModelKeuanganCoa extends Model {
	public function addCategory($data) {
		$column='';
		$vals='';
		$i=1;

		foreach($data as $key => $value){
			if($key != 'keyword'){
				if($i != 1){
							 $column .=",";
							 $vals .=",";
				}
				if($key == 'column'){
					$column .= '"'.$key.'"';
				}else{
					$column .= $key;
				}
				if($key == 'name' | $key == 'description' | $key == 'meta_description' | $key == 'meta_keyword'){
					$vals .=  "'".$this->db->escape($value)."'";
					//$sql .=$key."= '".$this->db->escape($value)."'";
				}else if($key == 'image'){
					$vals .= "'".$this->db->escape(html_entity_decode($value, ENT_QUOTES, 'UTF-8'))."'";
					//$sql .=$key."= '".$this->db->escape(html_entity_decode($value, ENT_QUOTES, 'UTF-8'))."'";
				}
				else{
					$vals .= "'".$value."'";
					//$sql .=$key."= '".$value."'";
				}
			}
			$i++;
		}

		$sql="INSERT INTO ".DB_PREFIX."coamnb(".$column.") values(".$vals.")";

		$this->db->query($sql);
		$category_id = $this->db->getLastId();


		$this->cache->delete('category');
	}

	public function editCategory($category_id, $data) {
		$sql="UPDATE ".DB_PREFIX."coamnb SET ";
		$i=1;
		foreach($data as $key => $value){
			if($key != 'keyword'){
			if($i != 1){
		         $sql .=",";
			}

			if($key == 'name' | $key == 'description' | $key == 'meta_description' | $key == 'meta_keyword'){
				$sql .=$key."= '".$this->db->escape($value)."'";
			}else if($key == 'image'){
				$sql .=$key."= '".$this->db->escape(html_entity_decode($value, ENT_QUOTES, 'UTF-8'))."'";
			}else if($key == 'column'){
				$sql .= '"'.$key.'" = ';
				$sql .= "'".$value."'";
			}
			else{
				$sql .=$key."= '".$value."'";
			}
			$i++;
			}
		}
		$sql.=" WHERE category_id='".$category_id."'";
		$this->db->query($sql);


	}

	public function deleteCategory($category_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "coamnb WHERE category_id = '" . (int)$category_id . "'");
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "coamnb WHERE parent_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$this->deleteCategory($result['category_id']);
		}


	}

	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coamnb WHERE category_id = '" . (int)$category_id . "'");

		return $query->row;
	}
	public function getCategoryByKodeRek($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coamnb WHERE kode_rek = '" . $category_id . "'");

		return $query->row;
	}
	public function getChild($parent_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coamnb WHERE parent_id = '" . (int)$parent_id . "' AND status=1");

		return $query->rows;
	}
	public function getParentCategories($parent=0){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coamnb WHERE parent_id = ".$parent." AND status=1 ORDER BY sort_order ASC");
		$cat=$query->rows;
		$cats=array();
		foreach($cat as $c){
			$cats[]=array(
				'category_id'	=> $c['category_id'],
				'kode_rek'	=> $c['kode_rek'],
				'name'	=> $c['name'],
				'child'	=> $this->getParentCategories($c['category_id'])
			);

		}
		return $cats;
		//return $this->db->all('category',array('parent_id' => 0,'status'	=> 1));
	}
	public function getCategories($parent_id = 0,$data=array()) {
			$category_data = array();

			$sql = "SELECT * FROM " . DB_PREFIX . "coamnb c WHERE c.parent_id = '" . (int)$parent_id . "' ";
			if(isset($data['filter_name'])){
				if (!empty($data['filter_name'])) {
			    $sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			  }
			}
			$sql .= " ORDER BY sort_order ASC";
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

			foreach ($query->rows as $result) {
				$category_data[] = array(
					'category_id' => $result['category_id'],
					//'name'        => $this->getPath($result['category_id']),
					'name'	=> $result['name'],
					'status'  	  => $result['status'],
					'kode_rek'  	  => $result['kode_rek'],
					'sort_order'  => $result['sort_order']
				);

				$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
			}



		return $category_data;
	}

	public function getAllCategories($data=array()) {
			$category_data = array();

			$sql = "SELECT * FROM " . DB_PREFIX . "coamnb c WHERE c.category_id > 0 ";
			if(isset($data['filter_name'])){
				if (!empty($data['filter_name'])) {
			    $sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			  }
			}

			if(isset($data['filter_kode_rek'])){
				if (!empty($data['filter_kode_rek'])) {
			    $sql .= " AND lower(kode_rek) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_kode_rek'])) . "%'";
			  }
			}

			if(isset($data['filter_type'])){
				if (!empty($data['filter_type'])) {
					if($data['filter_type'] != 11){
			    	$sql .= " AND type='".$data['filter_type']."' ";
					}else{
						$sql .= " AND type IN(6,8)";
					}
			  }
			}
			if(isset($data['filter_parent'])){
				if (!empty($data['filter_parent'])) {
					if($data['filter_parent'] != 'x'){
			    	$sql .= " AND parent_id='".$data['filter_parent']."' ";
					}else{
						$sql .= " AND parent_id <> '0' ";
					}
			  }
			}
			$sql .= " ORDER BY sort_order, name ASC";
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

			foreach ($query->rows as $result) {
				if($result['type'] == 1){
					$type="Aset";
				}
				if($result['type'] == 2){
					$type="Hutang";
				}
				if($result['type'] == 3){
					$type="Modal";
				}
				if($result['type'] == 4){
					$type="Pendapatan";
				}
				if($result['type'] == 5){
					$type="Harga Pokok Penjualan";
				}
				if($result['type'] == 6){
					$type="Beban";
				}
				if($result['type'] == 7){
					$type="Pendapatan Lain-lain";
				}
				if($result['type'] == 8){
					$type="Beban Lain-lain";
				}
				$category_data[] = array(
					'category_id' => $result['category_id'],
					//'name'        => $this->getPath($result['category_id']),
					'name'	=> $result['name'],
					'parent_id'	=> $result['parent_id'],
					'sado'	=> $result['saldo'],
					'status'  	  => $result['status'],
					'type'	=> $type,
					'kode_rek'  	  => $result['kode_rek'],
					'sort_order'  => $result['sort_order']
				);

				//$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
			}



		return $category_data;
	}

	public function getPath($category_id) {
		$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "coamnb c WHERE c.category_id = '" . (int)$category_id . "' ORDER BY c.sort_order, name ASC");

		if ($query->row['parent_id']) {
			return $this->getPath($query->row['parent_id']) . $this->language->get('text_separator') . $query->row['name'];
		} else {
			return $query->row['name'];
		}
	}


	public function getTotalCategories($data=array()) {
		$sql="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coamnb WHERE category_id > 0 ";
		if(isset($data['filter_name'])){
			if (!empty($data['filter_name'])) {
				$sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			}
		}
		if(isset($data['filter_kode_rek'])){
			if (!empty($data['filter_kode_rek'])) {
				$sql .= " AND lower(kode_rek) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_kode_rek'])) . "%'";
			}
		}

		if(isset($data['filter_type'])){
			if (!empty($data['filter_type'])) {
				if($data['filter_type'] != 11){
					$sql .= " AND type='".$data['filter_type']."' ";
				}else{
					$sql .= " AND type IN(6,8)";
				}
			}
		}
    $query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function addSaldo($data){
		$this->load->model('keuangan/jurnal');
		$jurnal=array();
    $detail=array();
    if($data['nominal'] > 0){
      $detail[]=array(
        'ref_akun'  => $data['debet'],
        'keterangan'  => $this->db->escape($data['keterangan']),
        'debet' => $data['nominal'],
        'kredit'  => 0,
        'urutan'  => 1,
      );

      $detail[]=array(
        'ref_akun'  => $data['kredit'],
        'keterangan'  => $this->db->escape($data['keterangan']),
        'kredit' => $data['nominal'],
        'debet'  => 0,
        'urutan'  => 2,
      );

      $jurnal=array(
        'tanggal' => $data['tgl_bayar'],
        'keterangan'  => $data['keterangan'],
        'hapus' => 0,
        'ref' => 0,
        'type'  => 100,
        'details'  => $detail
      );
      $this->model_keuangan_jurnal->addJurnalUmum($jurnal);
    }
	}
	public function updateSaldo($kode_rek,$total,$jenis){
		$coa=$this->db->first('coamnb',array('kode_rek'=>$kode_rek));
		if(empty($coa['saldo'])){
			$coa['saldo']=0;
		}
		if($jenis == 1){
			$update=$coa['saldo']+$total;

		}
		if($jenis == 2){
			$update=$coa['saldo']-$total;

		}
		//$this->db->update('coamnb',array('saldo'=>$update),array('kode_rek'=>$kode_rek));
	}



}
?>
