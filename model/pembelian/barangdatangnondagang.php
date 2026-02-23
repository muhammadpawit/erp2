<?php
class ModelPembelianBarangdatangnondagang extends Model {

  public function updatePermintaan($data,$where,$id){
    $this->db->update('suratjalan_pembeliannondagang',$data,$where);

  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('pembelian_kreditnondagang',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('pembelian_kreditnondagang',$where);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('suratjalan_pembeliannondagang',$column,$join,$where,array());
  }
  public function getPermintaanPembelianFull($column=array(),$join=array(),$where=array(),$leftjoin=array()){
    return $this->db->alljoins('suratjalan_produknondagang',$column,$join,$leftjoin,$where,array(),0,null);
  }

  public function getPermintaanPembelianProduct($where){
    $join=array();
    $join[]=array(
      'tablename' => 'invoice_pembeliannondagang_product',
      'firsttable'  => 'suratjalan_produknondagang.pembelian_product_id',
      'secondtable' => 'invoice_pembeliannondagang_product.id'
    );
    return $this->db->alljoin('suratjalan_produknondagang',array('suratjalan_produknondagang.quantity as qtyterima','invoice_pembeliannondagang_product.*'),$join,$where,array(),0,null);
  }



}
?>
