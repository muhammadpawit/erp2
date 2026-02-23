<?php
class ModelKepegawaianJamkerja extends Model {
	public function addOptions($data) {
		//$sql="INSERT INTO ".DB_PREFIX."title(name,date_added,date_modified,hapus) values('".$this->db->escape($data['name'])."',NOW(),NOW(),0)";
		$this->db->query($sql);

	}

	public function editOptions($id, $data) {
		//$sql="UPDATE ".DB_PREFIX."title SET name='".$this->db->escape($data['name'])."',date_modified=NOW() WHERE id='".$id."'";
		$jam=array(
			'jam_masuk'	=> $data['jam_masuk'],
			'jam_selesai'	=> $data['jam_selesai']
		);
		//$this->db->query($sql);
		$this->db->update('jamkerja',$jam,array('id'=>$id));
	}

	public function getOption($id) {
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."jamkerja WHERE id = '" . (int)$id . "'");

		return $query->row;
	}

	public function getOptions($data=array()) {
		$sql = "SELECT * FROM ".DB_PREFIX."jamkerja WHERE id > 0";
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
		$sql = "SELECT * FROM ".DB_PREFIX."jamkerja WHERE id > 0";
		if (!empty($data['filter_name'])) {
	    $sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
	  }
		$query = $this->db->query($sql);

		return $query->num_rows;
	}

	public function getTitle($id){
		$sql = "SELECT * FROM ".DB_PREFIX."jamkerja WHERE id='".$id."'";
		$query = $this->db->query($sql);
		if(!empty($query->row)){
			return $query->row['name'];
		}else{
			return false;
		}
	}

}
?>
