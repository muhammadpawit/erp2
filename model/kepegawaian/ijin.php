<?php
class ModelKepegawaianIjin extends Model {
	public function addIjin($data){
		//get day
		$vendor=array(
			'pegawai_id'	=> $data['pegawai_id'],
			'tgl_awal'	=> $data['tgl_awal'],
			'tgl_akhir'	=> $data['tgl_akhir'],
			'keperluan'	=> $data['keperluan'],
			'status'	=> 1,
			'date_added'	=> date('Y-m-d H:i:s'),
			'date_modified'	=> date('Y-m-d H:i:s'),
			'hapus'	=> 0
		);
		$this->db->insert('ijin_pegawai',$vendor);
	}
	public function updateIjin($data,$where=array()){
		//if($data['status']){}
		$this->db->update('ijin_pegawai',$data,$where);
	}
	public function getIjin($where){
		return $this->db->first('ijin_pegawai',$where);
	}
	public function getIjins($column,$join,$where,$order,$limit,$offset){
		return $this->db->alljoin('ijin_pegawai',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalIjins($where,$join){
		return $this->db->countAll('ijin_pegawai',$where,$join);
	}



}
?>
