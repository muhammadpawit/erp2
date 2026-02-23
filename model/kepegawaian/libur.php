<?php
class ModelKepegawaianLibur extends Model {
	public function addLibur($data){
			$libur=array(
				'tanggal'	=>$data['tanggal'],
				'periode_id'	=>$data['periode_id'],
				'keterangan'	=>$this->db->escape($data['keterangan'])
			);
        $this->db->insert('libur',$libur);
    }

    public function editLibur($libur_id,$data){
        $this->db->query("UPDATE ".DB_PREFIX."libur SET tanggal='".$data['tanggal']."',periode_id='".$data['periode_id']."',keterangan='".$this->db->escape($data['keterangan'])."' WHERE libur_id='".$libur_id."'");
    }

    public function deleteLibur($libur_id){
       $this->db->query("DELETE FROM ".DB_PREFIX."libur where libur_id='".$libur_id."'");
    }

    public function deleteLiburByPeriode($periode_id){
       $this->db->query("DELETE FROM ".DB_PREFIX."libur where periode_id='".$periode_id."'");
    }

    public function getLiburByPeriode($periode_id){
        $q=$this->db->query("SELECT * FROM ".DB_PREFIX."libur WHERE periode_id='".$periode_id."' ORDER BY tanggal ASC");
        return $q->rows;
    }

    public function getLiburByTanggal($tanggal){
        $q=$this->db->query("SELECT * FROM ".DB_PREFIX."libur WHERE tanggal='".$tanggal."' ");
        return $q->row;
    }
  

}
?>
