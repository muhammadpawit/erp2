<?php
class ModelKepegawaianJabatan extends Model {
	public function addJabatan($data){
			$jabatan=array(
				'nama'=>$this->db->escape($data['nama']),
				'tunjangan'	=>$data['tunjangan'],

			);
			$this->db->insert('jabatan',$jabatan);

    }

    public function editJabatan($jabatan_id,$data){
        $this->db->query("UPDATE ".DB_PREFIX."jabatan SET nama='".$this->db->escape($data['nama'])."',tunjangan='".$data['tunjangan']."' WHERE jabatan_id='".$jabatan_id."'");
    }

    public function deleteJabatan($jabatan_id){
        $this->db->query("DELETE FROM ".DB_PREFIX."jabatan WHERE jabatan_id='".$jabatan_id."'");
    }

    public function getJabatans($data=array()) {


        $sql="SELECT * FROM " . DB_PREFIX . "jabatan WHERE jabatan_id > 0 ";

        if (isset($data['filter_name'])) {
            $sql .= " AND lower(nama) LIKE '%". $this->db->escape(utf8_strtolower($data['filter_name']))."%'";
        }

        $sql .= " ORDER BY nama";
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
        }


		$query=$this->db->query($sql);
		return $query->rows;
	}

	public function getTotalJabatan($data=array()){
		$sql="SELECT COUNT(*) as total FROM " . DB_PREFIX . "jabatan WHERE jabatan_id > 0 ";

        if (isset($data['filter_name'])) {
            $sql .= " AND lower(nama) LIKE '%". $this->db->escape(utf8_strtolower($data['filter_name']))."%'";
        }
		$query=$this->db->query($sql);
		return $query->row['total'];
	}

	public function getJabatan($jabatan_id) {
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."jabatan WHERE jabatan_id ='" . (int)$jabatan_id . "'");

		return $query->row;
	}




}
?>
