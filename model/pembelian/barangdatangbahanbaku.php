<?php
class ModelPembelianBarangdatangbahanbaku extends Model {

  public function updatePermintaan($data,$where){
    $this->db->update('pembelian_kreditbahanbaku',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('pembelian_kreditbahanbaku',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('pembelian_kreditbahanbaku',$where);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('suratjalan_pembelianbahanbaku',$column,$join,$where,array());
  }
  public function getPermintaanPembelianFull($column=array(),$join=array(),$where=array(),$leftjoin=array()){
    return $this->db->alljoins('suratjalan_pembelianbahanbaku',$column,$join,$leftjoin,$where,array('tgl_terima'=>'ASC'),0,null);
  }

  public function getPermintaanPembelianProduct($where){
    $join=array();
    $join[]=array(
      'tablename' => 'pembelian_produk_kreditbahanbaku',
      'firsttable'  => 'suratjalan_produkbahanbaku.pembelian_product_id',
      'secondtable' => 'pembelian_produk_kreditbahanbaku.id'
    );
    return $this->db->alljoin('suratjalan_produkbahanbaku',array('suratjalan_produkbahanbaku.quantity as qtyterima','pembelian_produk_kreditbahanbaku.*'),$join,$where,array(),0,null);
  }
}
?>
