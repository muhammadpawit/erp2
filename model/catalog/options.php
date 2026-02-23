<?php
class ModelCatalogOptions extends Model {
	public function addOptions($data) {
		$sql="INSERT INTO ".DB_PREFIX."product_options(name,date_added,date_modified) values('".$this->db->escape($data['name'])."',NOW(),NOW())";
		$this->db->query($sql);

	}

	public function editOptions($product_options_id, $data) {
		$sql="UPDATE ".DB_PREFIX."product_options SET name='".$this->db->escape($data['name'])."',date_modified=NOW() WHERE product_options_id='".$product_options_id."'";
		$this->db->query($sql);
	}

	public function deleteOptions($product_options_id) {
		$sql="UPDATE ".DB_PREFIX."product_options SET date_deleted=NOW() WHERE product_options_id='".$product_options_id."'";
		$this->db->query($sql);
	}

	public function getOption($product_options_id) {
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."product_options WHERE product_options_id = '" . (int)$product_options_id . "' AND date_deleted IS NULL");

		return $query->row;
	}

	public function getOptions($data=array()) {
		$sql = "SELECT * FROM ".DB_PREFIX."product_options WHERE date_deleted IS NULL";
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
		$sql = "SELECT * FROM ".DB_PREFIX."product_options WHERE date_deleted IS NULL";
		if (!empty($data['filter_name'])) {
	    $sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
	  }
		$query = $this->db->query($sql);

		return $query->num_rows;
	}

	

}
?>
