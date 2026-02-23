<?php
class ModelMarketplaceMarketplace extends Model {
	public function simpan($data){
		$insert = array(
			'nama'=>$data['nama'],
			'hapus'=>0,
			'date_added'=>date('Y-m-d H:i:s'),
		);
		$this->db->insert('marketplace',$insert);
	}

	public function getall($data){
		$sql="SELECT * FROM marketplace WHERE hapus=0 ";
		if(!empty($data['filter_name'])){
			$sql .=" AND lower(nama) LIKE '%".strtolower($data['filter_name'])."%' ";
		}
		$sql.=" ORDER BY nama ASC ";
		$d =$this->db->query($sql);
		return $d->rows;
	}

	public function gettotal($data){
		$sql="SELECT count(*) as total FROM marketplace WHERE hapus=0 ";
		if(!empty($data['filter_name'])){
			$sql .=" AND lower(nama) LIKE '%".strtolower($data['filter_name'])."%' ";
		}
		$d =$this->db->query($sql);
		return $d->row['total'];
	}

	public function getdetail($id){
		$sql="SELECT * FROM marketplace WHERE hapus=0 and id='$id' ";
		$d =$this->db->query($sql);
		return $d->row;
	}

	public function edit($id,$data){
		$this->db->update('marketplace',array('nama'=>$data['nama']),array('id'=>$id));
	}

	public function hapus($id){
		$this->db->update('marketplace',array('hapus'=>1),array('id'=>$id));
	}
}

?>