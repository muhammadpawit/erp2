<?php
class ModelKepegawaianAbsensi extends Model {
	public function addAbsensi($data){
		//get day
		$this->load->model('kepegawaian/jamkerja');
		$day=date("w",strtotime($data['tanggal']));
		$jam=$this->model_kepegawaian_jamkerja->getOption($day);
		$vendor=array(
			'pegawai_id'	=> $data['pegawai_id'],
			'tanggal'	=> $data['tanggal'],
			'jam_datang'	=> $data['jam_datang'],
			'jam_pulang'	=> $data['jam_pulang'],
			'status'	=> 1,
			'jam_masuk'	=> $jam['jam_masuk'],
			'jam_selesai'	=> $jam['jam_selesai'],
			'hapus'	=> 0
		);
		$this->db->insert('absensi',$vendor);
	}
	public function updateAbsensi($data,$where=array()){
	$this->db->update('absensi',$data,$where);
	}
	public function getAbsensi($where){
		return $this->db->first('absensi',$where);
	}
	public function getAbsensis($column,$join,$where,$order,$limit,$offset){
		return $this->db->alljoin('absensi',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalAbsensis($where,$join){
		return $this->db->countAll('absensi',$where,$join);
	}



}
?>
