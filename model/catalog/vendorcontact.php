<?php
class ModelCatalogVendorcontact extends Model {
	public function addVendor($data){
		$vendor=array(
			'name'	=>$this->db->escape($data['name']),
			'vendor_id'	=> $data['vendor_id'],
			'email'	=> $this->db->escape($data['email']),
			'telephone'	=> $data['telephone'],
			'jenis'	=> $data['jenis'],
			'hapus'	=>0
		);
		$this->db->insert('vendorcontact',$vendor);
	}
	public function updateVendor($data,$where=array()){
	$this->db->update('vendorcontact',$data,$where);
	}
	public function getVendor($where){
		return $this->db->first('vendorcontact',$where);
	}
	public function getVendors($where,$order,$limit,$offset){
		return $this->db->all('vendorcontact',$where,$order,$limit,$offset);
	}
	public function totalVendors($where){
		return $this->db->count('vendorcontact',$where);
	}


}
?>
