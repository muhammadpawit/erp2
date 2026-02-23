<?php
class ModelKepegawaianKontrak extends Model {
	public function addKontrak($data){
		//get day
		$upd=array(
			'status'	=> 0
		);
		$this->db->update("kontrak_pegawai",$upd,array('pegawai_id'=>$data['pegawai_id']));
		$vendor=array(
			'pegawai_id'	=> $data['pegawai_id'],
			'tglawal'	=> $data['tglawal'],
			'tglakhir'	=> $data['tglakhir'],
			'keterangan'	=> $data['keterangan'],
			'file'	=> $data['file'],
			'status'	=> 1,
			'date_added'	=> date('Y-m-d H:i:s'),
			'date_modified'	=> date('Y-m-d H:i:s'),
			'no_kontrak'	=> !empty($data['no_kontrak'])?$data['no_kontrak']:"",
			'hapus'	=> 0
		);
		$this->db->insert("kontrak_pegawai",$vendor);
		$id=$this->db->getLastId();

		$no_kontrak=$id."/SK"."/".date('m')."/".date('Y');

		if(empty($data['no_kontrak'])){
			$upd=array(
				'no_kontrak'	=> $no_kontrak
			);
			$this->db->update("kontrak_pegawai",$upd,array('id'=>$id));
		}

		$this->db->update("users",array('tglakhir'=>$data['tglakhir']),array('user_id'=>$data['pegawai_id']));
	}
	public function updateKontrak($data,$where=array()){
		//if($data['status']){}
		$this->db->update('kontrak_pegawai',$data,$where);
	}
	public function getKontrak($where){
		return $this->db->first('kontrak_pegawai',$where);
	}
	public function getKontraks($column,$join,$where,$order,$limit,$offset){
		return $this->db->alljoin('kontrak_pegawai',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalKontraks($where,$join){
		return $this->db->countAll('kontrak_pegawai',$where,$join);
	}



}
?>
