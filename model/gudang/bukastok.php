<?php
class ModelGudangBukastok extends Model {
  public function bukaStok($data){
    //cek data

    $p=array(

      'tanggal' => $data['tanggal'],
      'gudang_id' => $data['gudang_id'],
      'user_id' => $this->user->getId(),
      'keterangan'  => $this->db->escape($data['keterangan']),
      'status'  => 1
      //'hapus' => 0
    );
    $this->db->insert('bukastok',$p);
    $id=$this->db->getLastId();

    $data['id'] = $id;
    //$this->bukaProduksiBahan($data);
    $this->load->model('gudang/product');
    $fil=array(
      'filter_gudang_id'  => $data['gudang_id'],
      'filter_jenisproduk'  => 2
    );
    $productgudang=$this->model_gudang_product->getProducts($fil,false);
    foreach($productgudang as $pp){
      $pd=array(
        'bukastok_id'=>$id,
        'product_id'  => $pp['product_id'],
        'gudang_id' => $data['gudang_id'],
        'quantity'  => $pp['quantity'],
        'net_cost'  => $pp['net_cost']
      );

      $this->db->insert('bukastok_product',$pd);
    }
    return $id;
  }



  public function getPermintaanPembelians($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('bukastok',$column,$join,$leftjoin,$where,array('id'=> 'DESC'),$limit,$offset);
  }

  public function totalPermintaans($where,$join,$leftjoin){
		return $this->db->countAll('bukastok',$where,$join,$leftjoin);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('bukastok',$column,$join,$where,array());
  }

  public function getPermintaanPembelianProduct($where){
    $column=array('bukastok_product.*','product.name');
    $join=array();
    $join[]=array(
			'tablename'	=> 'product',
			'firsttable'	=>'bukastok_product.product_id',
			'secondtable'	=> 'product.product_id'
		);
    $leftjoin=array();

    return $this->db->alljoins('bukastok_product',$column,$join,$leftjoin,$where,array(),0,null);
  }

  public function getBahanbaku($where){


    return $this->db->firstdetail('bukastok_product',array(),array(),$where);
  }

}
?>
