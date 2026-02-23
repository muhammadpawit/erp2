<?php
class ModelCatalogCity extends Model {
	public function addCity($data) {
		$sql="INSERT INTO ".DB_PREFIX."city(name,zone_id,status) values('".$this->db->escape($data['name'])."','".$data['zone_id']."',1)";
		$this->db->query($sql);

	}

	public function editCity($id, $data) {
		$sql="UPDATE ".DB_PREFIX."city SET name='".$this->db->escape($data['name'])."',zone_id='".$data['zone_id']."' WHERE city_id='".$id."'";
		$this->db->query($sql);
	}

	public function deleteCity($id) {
		$sql="DELETE FROM city WHERE city='".$id."'";
		$this->db->query($sql);
	}

	public function getCity($id) {
		$query = $this->db->query("SELECT z.*,c.country_id FROM ".DB_PREFIX."city z JOIN zone c ON(z.zone_id=c.zone_id) WHERE city_id = '" . (int)$id . "'");

		return $query->row;
	}

	public function getCitys($data=array()) {
		$sql = "SELECT z.*,c.name as kabupaten,p.name as provinsi FROM ".DB_PREFIX."city z JOIN zone c ON(z.zone_id=c.zone_id) JOIN country p ON(c.country_id=p.country_id)  WHERE z.status=1 ";
		if(isset($data['filter_name'])){
			if (!empty($data['filter_name'])) {
		    $sql .= " AND lower(z.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		  }
		}
		if(isset($data['filter_zone_id'])){
			if (!empty($data['filter_zone_id'])) {
		    $sql .= " AND z.zone_id='".$data['filter_zone_id']."'";
		  }
		}
		if(isset($data['filter_country_id'])){
			if (!empty($data['filter_country_id'])) {
		    $sql .= " AND c.country_id='".$data['filter_country_id']."'";
		  }
		}

		$sql .=" ORDER BY c.country_id ASC, c.name ASC, z.name ASC ";
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

	public function totalCitys($data=array()){
		$sql = "SELECT count(*) as total FROM ".DB_PREFIX."city z JOIN zone c ON(z.zone_id=c.zone_id) JOIN country p ON(c.country_id=p.country_id)  WHERE z.status=1 ";
		if(isset($data['filter_name'])){
			if (!empty($data['filter_name'])) {
		    $sql .= " AND lower(z.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		  }
		}
		if(isset($data['filter_zone_id'])){
			if (!empty($data['filter_zone_id'])) {
		    $sql .= " AND z.zone_id='".$data['filter_zone_id']."'";
		  }
		}
		if(isset($data['filter_country_id'])){
			if (!empty($data['filter_country_id'])) {
		    $sql .= " AND c.country_id='".$data['filter_country_id']."'";
		  }
		}
		$query = $this->db->query($sql);

		return $query->row['total'];
	}




}
?>
