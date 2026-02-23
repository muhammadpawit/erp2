<?php
class ModelKepegawaianTunjangan extends Model {
	public function addtunjangan($data){
		$tunjangan=array(
			'nama'	=>$this->db->escape($data['nama']),
			'pegawai_id'	=>$data['pegawai_id'],
			'satuan'	=>$data['satuan'],
			'nilai'	=>$data['nilai'],
			'status'	=>1
		);
		$this->db->insert('tunjangan',$tunjangan);

  }

  public function edittunjangan($tunjangan_id,$data){
      $this->db->query("UPDATE ".DB_PREFIX."tunjangan SET nama='".$this->db->escape($data['nama'])."',pegawai_id='".$data['pegawai_id']."',satuan='".$data['satuan']."',nillai='".$data['nilai']."' WHERE tunjangan_id='".$tunjangan_id."'");
  }

    public function deletetunjangan($tunjangan_id){
       $this->db->query("UPDATE ".DB_PREFIX."tunjangan set status='0' where tunjangan_id='".$tunjangan_id."'");
    }

    public function gettunjangans($data=array()) {


        $sql="SELECT s.*,t.nama FROM " . DB_PREFIX . "tunjangan s JOIN ".DB_PREFIX."pegawai_toko t ON(s.pegawai_id = t.pegawai_id) WHERE s.status > 0 ";

        if (!empty($data['filter_name'])) {
            $sql .= " AND lower(s.nama) LIKE '%". $this->db->escape(utf8_strtolower($data['filter_name']))."%'";
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


		$query=$this->db->query($sql);
		return $query->rows;
	}

	public function getTotaltunjangan($data=array()){
		$sql="SELECT COUNT(*) as total FROM " . DB_PREFIX . "tunjangan WHERE status > 0 ";

        if (!empty($data['filter_name'])) {
            $sql .= " AND lower(nama) LIKE '%". $this->db->escape(utf8_strtolower($data['filter_name']))."%'";
        }

		$query=$this->db->query($sql);
		return $query->row['total'];
	}

	public function gettunjangan($tunjangan_id) {
		$query = $this->db->query("SELECT s.*,t.nama FROM " . DB_PREFIX . "tunjangan s JOIN ".DB_PREFIX."pegawai_toko t ON(s.pegawai_id = t.pegawai_id) WHERE s.status > 0 AND tunjangan_id ='" . (int)$tunjangan_id . "'");

		return $query->row;
	}

    public function gettunjanganbypegawai($pegawai_id) {
		$query = $this->db->query("SELECT s.*,t.nama as namapegawai FROM " . DB_PREFIX . "tunjangan s JOIN ".DB_PREFIX."pegawai_toko t ON(s.pegawai_id = t.pegawai_id) WHERE s.status > 0 AND s.pegawai_id ='" . (int)$pegawai_id . "'");

		return $query->rows;
	}




}
?>
