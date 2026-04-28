<?php
class ModelKepegawaianSales extends Model {
	public function addIjin($data){
		//get day
		$vendor=array(
			'namasales'=>$data['namasales'],
			'hapus'	=> 0
		);
		$this->db->insert('namasales',$vendor);
	}
	public function updateIjin($data,$where=array()){
		//if($data['status']){}
		$this->db->update('namasales',$data,$where);
	}
	public function getIjin($table){
		$sql="SELECT * FROM $table WHERE hapus=0 ";
        $sql.=" ORDER BY namasales ASC ";
        return $this->db->query($sql)->rows;
	}
	public function getIjins($column,$join,$where,$order,$limit,$offset){
		return $this->db->alljoin('cuti',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalIjins($where,$join){
		return $this->db->countAll('cuti',$where,$join);
	}



}
?>
