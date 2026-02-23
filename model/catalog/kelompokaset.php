<?php
class ModelCatalogKelompokaset extends Model {
	public function addKelompokaset($data){
		$nilai=explode(',',$data['nilai_depresiasi']);
		if(count($nilai) > 1){
			$data['nilai_depresiasi']=implode('.',$nilai);
		}
		$aset=array(
			'name'=>$this->db->escape($data['name']),
			'masa_manfaat'=> $data['masa_manfaat'],
			'jenis_depresiasi'=> $data['jenis_depresiasi'],
			'nilai_depresiasi'	=> $data['nilai_depresiasi'],
			'jenis_aset'	=> $data['jenis_aset'],
			'date_added'	=> date('Y-m-d H:i:s',time()),
			'date_modified'	=> date('Y-m-d H:i:s',time()),
			'hapus'	=> 0
		);
		$this->db->insert('kelompok_aset',$aset);
	}

	public function editKelompokaset($kelompok_aset_id,$data){
		$aset=array(
			'name'=>$this->db->escape($data['name']),
			'masa_manfaat'=> $data['masa_manfaat'],
			'jenis_depresiasi'=> $data['jenis_depresiasi'],
			'nilai_depresiasi'	=> $data['nilai_depresiasi'],
			'jenis_aset'	=> $data['jenis_aset'],
			'date_modified'	=> date('Y-m-d H:i:s',time()),
			'hapus'	=> 0
		);
		$where=array(
			'kelompok_aset_id'	=> $kelompok_aset_id
		);
		$this->db->update('kelompok_aset',$aset,$where);
	}

	public function deleteKelompokaset($kelompok_aset_id){
		$aset=array(
			'date_modified'	=> date('Y-m-d H:i:s',time()),
			'hapus'	=> 1
		);
		$where=array(
			'kelompok_aset_id'	=> $kelompok_aset_id
		);
		$this->db->update('kelompok_aset',$aset,$where);
	}


	public function getKelompokaset($product_options_id) {
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."kelompok_aset WHERE kelompok_aset_id = '" . (int)$product_options_id . "' AND hapus=0");

		return $query->row;
	}

	public function getKelompokasets($data=array()) {
		$sql = "SELECT * FROM ".DB_PREFIX."kelompok_aset WHERE hapus=0";
		if(isset($data['filter_name'])){
			if (!empty($data['filter_name'])) {
		    $sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		  }
		}
		if(isset($data['jenis_aset'])){
			if (!empty($data['jenis_aset'])) {
		    $sql .= " AND jenis_aset='".$data['jenis_aset']."'";
		  }
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

	public function totalKelompokasets($data=array()){
		$sql = "SELECT * FROM ".DB_PREFIX."kelompok_aset WHERE hapus=0";
		if(isset($data['filter_name'])){
			if (!empty($data['filter_name'])) {
		    $sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		  }
		}
		if(isset($data['jenis_aset'])){
			if (!empty($data['jenis_aset'])) {
		    $sql .= " AND jenis_aset='".$data['jenis_aset']."'";
		  }
		}
		$query = $this->db->query($sql);

		return $query->num_rows;
	}
	public function getAktivas($where=array(),$orderby=array(),$limit=0,$offset=null){
		return $this->db->all('jenis_aktiva',$where,$orderby,$limit,$offset);
	}
	public function getAktiva($where=array()){
    return $this->db->firstdetail('jenis_aktiva',array(),array(),$where,array());
  }


}
?>
