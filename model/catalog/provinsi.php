<?php
class ModelCatalogProvinsi extends Model {
	public function addProvinsi($data) {
		$sql="INSERT INTO ".DB_PREFIX."country(name,status) values('".$this->db->escape($data['name'])."',1)";
		$this->db->query($sql);

	}

	public function editProvinsi($id, $data) {
		$sql="UPDATE ".DB_PREFIX."country SET name='".$this->db->escape($data['name'])."' WHERE country_id='".$id."'";
		$this->db->query($sql);
	}

	public function deleteProvinsi($id) {
		$sql="DELETE FROM country WHERE country_id='".$id."'";
		$this->db->query($sql);
	}

	public function getProvinsi($id) {
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."country WHERE country_id = '" . (int)$id . "'");

		return $query->row;
	}

	public function getProvinsis($data=array()) {
		$sql = "SELECT * FROM ".DB_PREFIX."country WHERE status=1 ";
		if(isset($data['filter_name'])){
			if (!empty($data['filter_name'])) {
		    $sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		  }
		}
		$sql .=" ORDER BY name ASC ";
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

	public function totalProvinsis($data=array()){
		$sql = "SELECT * FROM ".DB_PREFIX."country WHERE status=1";
		if (!empty($data['filter_name'])) {
	    $sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
	  }
		$query = $this->db->query($sql);

		return $query->num_rows;
	}




}
?>
