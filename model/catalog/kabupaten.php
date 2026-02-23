<?php
class ModelCatalogKabupaten extends Model {
	public function addKabupaten($data) {
		$sql="INSERT INTO ".DB_PREFIX."zone(name,country_id,status) values('".$this->db->escape($data['name'])."','".$data['country_id']."',1)";
		$this->db->query($sql);

	}

	public function editKabupaten($id, $data) {
		$sql="UPDATE ".DB_PREFIX."zone SET name='".$this->db->escape($data['name'])."',country_id='".$data['country_id']."' WHERE zone_id='".$id."'";
		$this->db->query($sql);
	}

	public function deleteKabupaten($id) {
		$sql="DELETE FROM zone WHERE zone_id='".$id."'";
		$this->db->query($sql);
	}

	public function getKabupaten($id) {
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."zone WHERE zone_id = '" . (int)$id . "'");

		return $query->row;
	}

	public function getKabupatens($data=array()) {
		$sql = "SELECT z.*,c.name as provinsi FROM ".DB_PREFIX."zone z JOIN country c ON(z.country_id=c.country_id)  WHERE z.status=1 ";
		if(isset($data['filter_name'])){
			if (!empty($data['filter_name'])) {
		    $sql .= " AND lower(z.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		  }
		}
		if(isset($data['filter_country_id'])){
			if (!empty($data['filter_country_id'])) {
		    $sql .= " AND z.country_id='".$data['filter_country_id']."'";
		  }
		}
		$sql .=" ORDER BY z.country_id ASC, z.name ASC ";
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

	public function totalKabupatens($data=array()){
		$sql = "SELECT count(*) as total FROM ".DB_PREFIX."zone z JOIN country c ON(z.country_id=c.country_id)  WHERE z.status=1 ";
		if(isset($data['filter_name'])){
			if (!empty($data['filter_name'])) {
		    $sql .= " AND lower(z.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		  }
		}
		if(isset($data['filter_country_id'])){
			if (!empty($data['filter_country_id'])) {
		    $sql .= " AND z.country_id='".$data['filter_country_id']."'";
		  }
		}
		$query = $this->db->query($sql);

		return $query->row['total'];
	}




}
?>
