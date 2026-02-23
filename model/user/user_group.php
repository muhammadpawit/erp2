<?php
class ModelUserUserGroup extends Model {
	public function addUserGroup($data) {
		$ug=array(
			'name'	=>$this->db->escape($data['name']),
			'permission'	=>(isset($data['permission']) ? serialize($data['permission']) : '')
		);

		$this->db->insert('user_group',$ug);
		//$this->db->query("INSERT INTO " . DB_PREFIX . "user_group SET name = '" . $this->db->escape($data['name']) . "', permission = '" . (isset($data['permission']) ? serialize($data['permission']) : '') . "'");
	}

	public function editUserGroup($user_group_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "user_group SET name = '" . $this->db->escape($data['name']) . "', permission = '" . (isset($data['permission']) ? serialize($data['permission']) : '') . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
	}

	public function deleteUserGroup($user_group_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
	}

	public function addPermission($user_id, $type, $page) {
		$user_query = $this->db->query("SELECT DISTINCT user_group_id FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$user_id . "'");

		if ($user_query->num_rows) {
			$user_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

			if ($user_group_query->num_rows) {
				$data = unserialize($user_group_query->row['permission']);

				$data[$type][] = $page;

				$this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . serialize($data) . "' WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");
			}
		}
	}

	public function getUserGroup($user_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

		$user_group = array(
			'name'       => $query->row['name'],
			'permission' => unserialize($query->row['permission'])
		);

		return $user_group;
	}

	public function getUserGroups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "user_group";

		$sql .= " ORDER BY name";

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

	public function getTotalUserGroups() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user_group");

		return $query->row['total'];
	}

	public function getMenu(){
		$query=$this->db->query("SELECT * FROM ".DB_PREFIX."menu ORDER BY sort_order,grouping ");
		return $query->rows;
	}

	public function addMenu($data){
		//$this->db->query("DELETE * FROM ".DB_PREFIX."menu");

		$q="SELECT * FROM ".DB_PREFIX."menu WHERE url='".$data['url']."'";
		$res=$this->db->query($q);

		if($res->num_rows < 1){
			$sql="INSERT INTO ".DB_PREFIX."menu SET ";
				$i=1;
				foreach($data as $key => $value){
					if($i != 1){
						 $sql .=",";
					}
					$sql .=$key."= '".$value."'";
					$i++;
				}

				$this->db->query($sql);
		}
		else{
			if($res->row['group'] == ''){
				$this->db->query("UPDATE ".DB_PREFIX."menu SET grouping='".$data['grouping']."' WHERE url='".$data['url']."'");
			}
		}
	}
}
?>
