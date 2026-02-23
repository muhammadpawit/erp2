<?php
class ModelKepegawaianLemburjam extends Model {
	public function addIjin($data){
		//get day
		$vendor=array(
			'pegawai_id'	=> $data['pegawai_id'],
			'tanggal'	=> $data['tanggal'],
			'jumlahjam'	=> $data['jumlahjam'],
			'keperluan'	=> $data['keperluan'],
			'date_added'	=> date('Y-m-d H:i:s'),
			'date_modified'	=> date('Y-m-d H:i:s'),
			'hapus'	=> 0
		);
		$this->db->insert('lemburjam',$vendor);
	}
	public function updateIjin($data,$where=array()){
		//if($data['status']){}
		$this->db->update('lemburjam',$data,$where);
	}
	public function getIjin($where){
		return $this->db->first('lemburjam',$where);
	}
	public function getIjins($column,$join,$where,$order,$limit,$offset){
		return $this->db->alljoin('lemburjam',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalIjins($where,$join){
		return $this->db->countAll('lemburjam',$where,$join);
	}



}
?>
