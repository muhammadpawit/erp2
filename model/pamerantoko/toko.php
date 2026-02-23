<?php
class ModelPamerantokoToko extends Model {
	public function addPameran($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "toko SET lokasi='".$this->db->escape($data['lokasi'])."',keterangan='".$this->db->escape($data['keterangan'])."',kode='".$this->db->escape($data['kode'])."',status='1'");
		$id=$this->db->getLastId();
		if(!empty($data['users'])){
			foreach($data['users'] as $u){
				$this->db->query("INSERT INTO ".DB_PREFIX."user_toko(user_id,gudang_id) values('".$u."','".$id."')");
			}
		}
	}

	public function editStatus($pameran_id,$status){
		$this->db->query("UPDATE ".DB_PREFIX."toko set status='".$status."' WHERE pameran_id='".$pameran_id."'");
		/*if($status == 2){
			$det=$this->getPameran($pameran_id);

			$this->load->model('pameran/pegawai');


		}*/
	}

	public function editPameran($pameran_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "toko SET lokasi='".$this->db->escape($data['lokasi'])."',keterangan='".$this->db->escape($data['keterangan'])."',kode='".$this->db->escape($data['kode'])."',sewa='".$data['sewa']."',status='".$data['status']."' WHERE pameran_id = '" . (int)$pameran_id . "'");

		if(!empty($data['users'])){
			$this->db->query("DELETE FROM " . DB_PREFIX . "user_toko WHERE gudang_id = '" . (int)$pameran_id . "'");
			foreach($data['users'] as $u){

				$this->db->query("INSERT INTO ".DB_PREFIX."user_toko(user_id,gudang_id) values('".$u."','".$pameran_id."')");
			}
		}
	}

	public function deletePameran($pameran_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "toko WHERE pameran_id = '" . (int)$pameran_id . "'");

	}

	public function getPameran($pameran_id) {
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."toko WHERE pameran_id ='" . (int)$pameran_id . "'");

		return $query->row;
	}

	public function getPamerans($data=array()) {
		$category_data = array();

			$sql="SELECT * FROM " . DB_PREFIX . "toko WHERE pameran_id > 0 ";

			if (isset($data['filter_kode'])) {
				$sql .= " AND kode='".$data['filter_kode']."'";
			}
			if (isset($data['filter_lokasi'])) {
				if(!empty($data['filter_lokasi'])){
					$sql .= " AND lower(lokasi) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_lokasi'])) . "%'";
				}
			}

			if (isset($data['filter_status'])) {
				$sql .= " AND status='".$data['filter_status']."'";
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


		$query=$this->db->query($sql);
		return $query->rows;
	}

	public function getTotalPameran($data=array()){
		$sql="SELECT count(*) as total FROM " . DB_PREFIX . "toko WHERE pameran_id > 0 ";

			if (isset($data['filter_kode'])) {
				$sql .= " AND kode='".$data['filter_kode']."'";
			}

			if (isset($data['filter_status'])) {
				$sql .= " AND status='".$data['filter_status']."'";
			}

		$query=$this->db->query($sql);
		return $query->row['total'];
	}


}
?>
