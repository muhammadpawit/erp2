<?php
class ModelLocalisationCountry extends Model {
	public function addCountry($data) {
		$coun=array(
			'name'	=> $this->db->escape($data['name']),
			'postcode_required'	=> 0,
			'status'	=> 1

		);
		$this->db->insert('country',$coun);

	}

	public function editCountry($country_id, $data) {
		$coun=array(
			'name'	=> $this->db->escape($data['name']),
			'postcode_required'	=> 0,
			'status'	=> 1

		);
		$this->db->update('country',$coun,array('country_id'=>$country_id));
	}

	public function deleteCountry($country_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "country SET status=0 WHERE country_id = '" . (int)$country_id . "'");

		$this->cache->delete('country');
	}

	public function getCountry($country_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");

		return $query->row;
	}

	public function getCountries($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "country WHERE status=1 ";

			$sort_data = array(
				'name',
				'iso_code_2',
				'iso_code_3'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY name";
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

	public function getTotalCountries() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "country WHERE status=1");

		return $query->row['total'];
	}

	public function getDisplayCountries(){
		return $this->db->all('country',array('status'	=> 1));
	}
	public function getCountryName($country_id){
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");

		$res=$query->row;
		if(!empty($res)){
			return $res['name'];
		}else{
			return "";
		}
	}
}
?>
