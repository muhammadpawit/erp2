<?php
class ModelCatalogJenisgas extends Model {
	public function addOptions($data) {
		$sql="INSERT INTO ".DB_PREFIX."jenisgas(name,date_added,date_modified,hapus) values('".$this->db->escape($data['name'])."',NOW(),NOW(),0)";
		$this->db->query($sql);

	}

	public function editOptions($id, $data) {
		$sql="UPDATE ".DB_PREFIX."jenisgas SET name='".$this->db->escape($data['name'])."',date_modified=NOW() WHERE id='".$id."'";
		$this->db->query($sql);
	}

	public function deleteOptions($id) {
		$sql="UPDATE ".DB_PREFIX."jenisgas SET hapus=0 WHERE id='".$id."'";
		$this->db->query($sql);
	}

	public function getOption($id) {
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."jenisgas WHERE id = '" . (int)$id . "' AND hapus=0");

		return $query->row;
	}

	public function getOptions($data=array()) {
		$sql = "SELECT * FROM ".DB_PREFIX."jenisgas WHERE hapus=0";
		if(isset($data['filter_name'])){
			if (!empty($data['filter_name'])) {
		    $sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
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

	public function totalOptions($data=array()){
		$sql = "SELECT * FROM ".DB_PREFIX."jenisgas WHERE hapus=0";
		if (!empty($data['filter_name'])) {
	    $sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
	  }
		$query = $this->db->query($sql);

		return $query->num_rows;
	}



}
?>
