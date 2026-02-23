<?php
class ModelProduksiPermintaanproduksi extends Model {
  public function addPermintaanPembelian($data){
    /*
    jenis produksi:
    1. MR
    2. Stok
    3. MP
    */


    $p=array(
      'no_surat'  => null,
      'jenis_produksi' => $data['jenis_produksi'],
      'no_so' => isset($data['no_so'])?$data['no_so']:0,
      'asal'  => $data['divisi_asal'],
      'keterangan'  => $this->db->escape($data['tujuan_pembelian']),
      'status'  => 1,
      'gudang_id' => $data['gudang_id'],
      'date_added'  => date('Y-m-d H:i:s',time()),
      'date_modified' => date('Y-m-d H:i:s',time()),
      //'hapus' => 0
    );
    $this->db->insert('permintaan_produksi',$p);
    $id=$this->db->getLastId();

    $no_surat='SP-PP-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;

    $this->db->update('permintaan_produksi',array('no_surat' => $no_surat),array('id'  => $id));
    $data['id'] = $id;
    $this->addPermintaanProduct($data);
    return $id;
  }

  public function addPermintaanProduct($data){
    foreach($data['product'] as $d){
      if(!empty($d['product_id'])){
      $prod=array(
        'surat_id'  => $data['id'],
        'product_id'  => $d['product_id'],
        'tabung_id'  => empty($d['tabung_id'])?0:$d['tabung_id'],
        'quantity'  => $d['quantity'],
        'keterangan'  => $this->db->escape($d['keterangan']),
        'hapus' => 0
      );
      $this->db->insert('permintaan_produksi_product',$prod);
    }
    }
  }
  public function updatePermintaan($data,$where){
    $this->db->update('permintaan_produksi',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('permintaan_produksi',$column,$join,$leftjoin,$where,array('id'=> 'DESC'),$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('permintaan_produksi',$where);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('permintaan_produksi',$column,$join,$where,array());
  }

  public function getPermintaanPembelianProduct($where){
    $column=array('permintaan_produksi_product.*','product.name','product.ukuran_tabung','product_options.name as namaukuran');
    $join=array();
    $join[]=array(
			'tablename'	=> 'product',
			'firsttable'	=>'permintaan_produksi_product.product_id',
			'secondtable'	=> 'product.product_id'
		);
    $leftjoin=array();
    $leftjoin[]=array(
      'tablename'	=> 'product_options',
			'firsttable'	=>'product.ukuran_tabung',
			'secondtable'	=> 'product_options.product_options_id'
    );
    return $this->db->alljoins('permintaan_produksi_product',$column,$join,$leftjoin,$where,array(),0,null);
  }
  public function addProsesProduksi($data){
    $pros=array(
      'permintaan'  => $data['permintaan'],
      'tanggal' => $data['tanggal'],
      'waktumulai' => $data['waktumulai'],
      
    );
  }
}
?>
