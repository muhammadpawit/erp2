<?php
/*
Purchase Order Controller
Created by morebit   | http://morebit.co   |  info@morebit.co
*/
class ModelSettingUpdate extends Model {

	public function getUpdate($data){
		 $sql = "SELECT * FROM " . DB_PREFIX . "update_pos WHERE status='1' ORDER BY update_pos_id DESC ";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['offset'];
		}



		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getUpdateDetail($update_pos_id){
		 $sql = "SELECT * FROM " . DB_PREFIX . "update_pos WHERE status='1' AND update_pos_id='".$update_pos_id."'";



		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getTotalUpdate(){
		 $sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "update_pos WHERE status='1' ";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}




}
