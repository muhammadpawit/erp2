<?php
class ModelKepegawaianKodepremi extends Model {
	public function addOptions($data) {
		$premi=array(
			'kelompok'	=> empty($data['kelompok'])?0:$data['kelompok'],
			'kelompok2'	=> empty($data['kelompok2'])?0:$data['kelompok2'],
			'kelompok3'	=> empty($data['kelompok3'])?0:$data['kelompok3'],
			'kelompok4'	=> empty($data['kelompok4'])?0:$data['kelompok4'],
			'hapus'	=> 0,
			'date_added'	=> date('Y-m-d H:i:s'),
			'date_modified'	=> date('Y-m-d H:i:s'),
		);
		$this->db->insert('kodepremi',$premi);

	}

	public function editOptions($id, $data) {
		//$sql="UPDATE ".DB_PREFIX."kodepremi SET kelompok='".$data['kelompok']."',date_modified=NOW(),nilai='".$data['nilai']."' WHERE id='".$id."'";

		$premi=array(
			'kelompok'	=> empty($data['kelompok'])?0:$data['kelompok'],
			'kelompok2'	=> empty($data['kelompok2'])?0:$data['kelompok2'],
			'kelompok3'	=> empty($data['kelompok3'])?0:$data['kelompok3'],
			'kelompok4'	=> empty($data['kelompok4'])?0:$data['kelompok4'],
			'date_modified'	=> date('Y-m-d H:i:s'),
		);
		$this->db->update('kodepremi',$premi,array('id'=>$id));
		$this->db->query($sql);
	}

	public function deleteOptions($id) {
		$sql="UPDATE ".DB_PREFIX."kodepremi SET hapus=1 WHERE id='".$id."'";
		$this->db->query($sql);
	}

	public function getOption($id) {
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."kodepremi WHERE id = '" . (int)$id . "' AND hapus=0");

		return $query->row;
	}

	public function getOptions($data=array()) {
		$sql = "SELECT * FROM ".DB_PREFIX."kodepremi WHERE hapus=0";
		if(isset($data['filter_name'])){
			if (!empty($data['filter_name'])) {
		    $sql .= " AND id='".$data['filter_name']."'";
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
		$sql = "SELECT * FROM ".DB_PREFIX."kodepremi WHERE hapus=0";
		if(isset($data['filter_name'])){
			if (!empty($data['filter_name'])) {
		    $sql .= " AND id='".$data['filter_name']."'";
		  }
		}
		$query = $this->db->query($sql);

		return $query->num_rows;
	}

	public function getkodepremi($id){
		$sql = "SELECT * FROM ".DB_PREFIX."kodepremi WHERE id='".$id."'";
		$query = $this->db->query($sql);
		if(!empty($query->row)){
			return $query->row['name'];
		}else{
			return false;
		}
	}

}
?>
