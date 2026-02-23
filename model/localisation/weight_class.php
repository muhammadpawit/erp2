<?php
class ModelLocalisationWeightClass extends Model {
	public function addWeightClass($data) {
		$weight=array(
			'"value"'	=> $weight['value'],
			'title'	=>$this->db->escape($data['title']),
			'unit'	=>$this->db->escape($data['unit']),
		);
		$this->db->insert('weight_class',$weight);

	}

	public function editWeightClass($weight_class_id, $data) {
		$weight=array(
			'value'	=> $weight['value'],
			'name'	=>$this->db->escape($data['name']),
			'unit'	=>$this->db->escape($data['unit']),
		);
		$where=array(
			'weight_class_id'=> $weight_class_id
		);
		$this->db->update('weight_class',$weight,$where);

	}

	public function deleteWeightClass($weight_class_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "weight_class WHERE weight_class_id = '" . (int)$weight_class_id . "'");

	}

	public function getWeightClasses($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "weight_class ";
			$sql .= " ORDER BY title";

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

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}

			$query = $this->db->query($sql);

			return $query->rows;

	}

	public function getWeightClass($weight_class_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class wc WHERE wc.weight_class_id = '" . (int)$weight_class_id . "' ");

		return $query->row;
	}

	

	public function getTotalWeightClasses() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "weight_class");

		return $query->row['total'];
	}
}
?>
