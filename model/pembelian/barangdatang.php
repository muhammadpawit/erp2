<?php
class ModelPembelianBarangdatang extends Model {

  public function updatePermintaan($data,$where){
    $this->db->update('pembelian_kredit',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('pembelian_kredit',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('pembelian_kredit',$where);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('suratjalan_pembelian',$column,$join,$where,array());
  }
  public function getPermintaanPembelianFull($column=array(),$join=array(),$where=array(),$leftjoin=array()){
    return $this->db->alljoins('suratjalan_pembelian',$column,$join,$leftjoin,$where,array('tgl_terima'=>'ASC'),0,null);
  }

  public function getPermintaanPembelianProduct($where){
    $join=array();
    $join[]=array(
      'tablename' => 'pembelian_produk_kredit',
      'firsttable'  => 'suratjalan_produk.pembelian_product_id',
      'secondtable' => 'pembelian_produk_kredit.id'
    );
    return $this->db->alljoin('suratjalan_produk',array('suratjalan_produk.quantity as qtyterima','pembelian_produk_kredit.*'),$join,$where,array(),0,null);
  }
}
?>
