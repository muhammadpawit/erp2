<?php
class ModelUserDeviceakses extends Model {
	public function addDevice($data) {
		$user=array(
			'token'	=> $data['token'],
			'location'	=> $data['location'],
			'status'	=> 2,
			'os'	=> $data['os'],
			'browser'	=> $data['browser'],
			'user_id'	=> $data['username'],
			'date_added'	=>date('Y-m-d H:i:s'),
			'date_modified'	=>date('Y-m-d H:i:s'),
			'approvedby'	=> 0,
			'hapus'	=> 0
		);
		$this->db->insert("deviceakses",$user);


	}

	public function updatedeviceakses($data,$where){
		$this->db->update("deviceakses",$data,$where);
	}

	public function approved($id,$data){
		$device=array(
			'namadevice'	=> $data['namadevice'],
			'status'	=> 1,
			'approvedby'	=> $this->user->getId(),
			'date_approved'	=> date('Y-m-d H:i:s'),
			'date_modified'	=>date('Y-m-d H:i:s'),
		);
		$this->db->update('deviceakses',$device,array('id'=>$id));
	}
	public function block($id){
		$device=array(
			'status'	=> 3,
			'date_modified'	=>date('Y-m-d H:i:s'),
		);
		$this->db->update('deviceakses',$device,array('id'=>$id));
	}

	public function openakses($id){
		$device=array(
			'status'	=> 1,
			'date_modified'	=>date('Y-m-d H:i:s'),
		);
		$this->db->update('deviceakses',$device,array('id'=>$id));
	}



	public function getDevice($user_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "deviceakses WHERE id = '" . (int)$user_id . "'");

		return $query->row;
	}

	public function getDeviceByToken($username) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "deviceakses WHERE token = '" . $this->db->escape($username) . "'");

		return $query->row;
	}

	public function getDevices($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "deviceakses";

		/*$sort_data = array(
			'username',
			'status',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY username";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		*/
		$sql .=" ORDER BY date_added DESC,status ASC ";
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

	public function getTotalDevices() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "deviceakses");

		return $query->row['total'];
	}




}
?>
