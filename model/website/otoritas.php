<?php
class ModelWebsiteOtoritas extends Model {
	public function addMenu($data) {

		$inf=array(
			'nama'	=>$this->db->escape($data['nama']),

		);
		$this->db->insert('otoritas',$inf);
		$information_id=$this->db->getLastId();

	}

	public function editMenu($menu_id, $data) {
		$inf=array(
			'nama'	=>$this->db->escape($data['nama'])
		);
		$where=array(
			'menu_id'	=> $menu_id
		);
		$this->db->update('otoritas',$inf,$where);


	}

	public function deleteMenu($menu_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "otoritas WHERE menu_id = '" . (int)$menu_id . "'");
	}

	public function getMenu($information_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "otoritas WHERE menu_id = '" . (int)$information_id . "'");

		return $query->row;
	}

	public function getMenus($data = array()) {


		$sql = "SELECT * FROM " . DB_PREFIX . "otoritas i WHERE i.menu_id > 0 ";


			$query = $this->db->query($sql);

			return $query->rows;

	}

	public function getTotalMenus() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "otoritas");

		return $query->row['total'];
	}
}
?>
