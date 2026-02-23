<?php
class ModelPembelianBarangdatangimport extends Model {
  public function addPembelian($data){
    /*
    jenis barang
    1. bahan baku
    2. produk dagang
    3. ATK
    4. Perlengkapan/aktiva tetap
    5. Tabung MP
    */
		$p=array(
      'no_nota' => $data['no_nota'],
      'no_faktur' => $data['no_faktur'],
      'vendor_id' => $data['vendor_id'],
      'sub_total' => $data['sub_total'],
      'diskon'  => $data['diskon'],
      'pajak' => $data['pajak'],
      'total_pembelian' => $data['total_pembelian'],
      'tgl_surat' => empty($data['tgl_surat'])?date('Y-m-d'):$data['tgl_surat'],
      'date_added'  => date('Y-m-d H:i:s',time()),
      'date_modified'  => date('Y-m-d H:i:s',time()),
      'hapus' => 0,
      'status'  => 1,
      'jenis_barang'  => $data['jenis_barang'],
			'no_po'	=> $data['no_po']
    );

    $this->db->insert('pembelian_invoice_import',$p);
    $id=$this->db->getLastId();
    $data['id'] = $id;

    $this->addProduct($data);
    return $no_po;


  }

  public function addProduct($data){
    foreach($data['products'] as $d){
      $prod=array(
        'pembelian_id'  => $data['id'],
        'product_id'  => $d['product_id'],
        'product_name'  => $this->db->escape($d['name']),
        'quantity'  => $d['quantity'],
        'status'  => 1,
        'date_added'  => date('Y-m-d H:i:s',time()),
        'date_modified'  => date('Y-m-d H:i:s',time()),
        'quantity_terima' => $d['quantity'],
        'kategori'  => 0,
        'ukuran_tabung' => 0,
        'harga' => $d['harga'],
        'ppn' => $d['harga'] * 0.1,
        'hapus' => 0
      );
      $this->db->insert('pembelian_produk_import',$prod);
    }
  }
  public function updatePermintaan($data,$where){
    $this->db->update('pembelian_import',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('pembelian_import',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('pembelian_import',$where);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('suratjalan_pembelianimport',$column,$join,$where,array());
  }

  public function getPermintaanPembelianProduct($where){
    $join=array();
    $join[]=array(
      'tablename' => 'pembelian_produk_import',
      'firsttable'  => 'suratjalan_produkimport.pembelian_product_id',
      'secondtable' => 'pembelian_produk_import.id'
    );
    return $this->db->alljoin('suratjalan_produkimport',array('suratjalan_produkimport.quantity as qtyterima','pembelian_produk_import.*'),$join,$where,array(),0,null);
  }

  public function getPermintaanPembelianFull($column=array(),$join=array(),$where=array(),$leftjoin=array()){
    return $this->db->alljoins('suratjalan_pembelianimport',$column,$join,$leftjoin,$where,array('tgl_terima'=>'ASC'),0,null);
  }
}
?>
