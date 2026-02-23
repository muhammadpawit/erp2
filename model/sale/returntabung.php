<?php
class ModelSaleReturntabung extends Model {
  /*
  Status
  1. disimpan
  2. diproses sebagian
  4. selesai diproses
  3. dibatalkan

  */
  public function addPenjualan($data){
    $this->load->model('sale/customer');
    if($data['pengiriman'] == 2){
      $data['address_id'] = 0;
    }
    if($data['address_id'] == -1){

      $add=array(
        'customer_id'	=> $data['customer_id'],
        'firstname'	=>$this->db->escape($data['firstname']),
        'lastname'	=>$this->db->escape($data['lastname']),
        'address_1'	=>$this->db->escape($data['address_1']),
        'address_2'	=>$this->db->escape($data['address_2']),
        'city_id'	=>(int)$data['city_id'],
        'postcode'	=>$this->db->escape($data['postcode']),
        'country_id'	=>(int)$data['country_id'],
        'zone_id'	=>(int)$data['zone_id'],
        'hapus'	=> 0
      );
      $data['address_id']=$this->model_sale_customer->addAddress($add,$data['customer_id']);
    }
    $penj=array(
      'date_added' => isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s'),
      'tttk_manual'  => '',
      'customer_id'  => $data['customer_id'],
      'quantity'  => $data['quantity'],
      'alasan_pengembalian' => $data['alasan_pengembalian'],
      'status' => 1,
      'pengiriman'  => $data['pengiriman'],
      'hapus' => 0,
      'user_id'  => $this->user->getId(),
      'address_id' =>0,
      'no_so'  => '',
      'no_pol'  => '',
      'sopir' => 0,
      //'tabungmp'  => $data['tabungmp'],
      //'tabungmr'  => $data['tabungmr']
    );
    $data['date_added']=$penj['date_added'];
    $this->db->insert('return_tabung',$penj);
    $id=$this->db->getlastId();
    $data['id']=$id;
    $no_so="RTTK-".$id."-".date('Y')."-".date("m")."-".$this->user->getId();
    $data['no_so']=$no_so;
    $this->db->update('return_tabung',array('no_so' => $no_so),array('id' => $id));
    $this->addPenjualanProduct($data);


    return $id;

  }
  public function addPenjualanProduct($data){
    $this->load->model('catalog/tabungmr');
    $this->load->model('catalog/kartustoktabungmr');
    foreach($data['product'] as $p){
        if(!empty($p['product_id'])){
          $penj=array(
            'tttk_id' => $data['id'],
            'product_id' => $p['product_id'],
            'tutup' => $p['tutup'],
            'quantity'  => $p['quantity']
          );

          $this->db->insert('return_tabung_detail',$penj);

           $tabung=$this->model_catalog_tabungmr->getTabungByProduct($p['product_id'],$data['customer_id']);

         //product
         $prod=$this->db->first('product',array('product_id' => $p['product_id']));
         if(!empty($tabung)){

           $qty=$tabung['quantity']-$p['quantity'];
           $this->db->update('tabung_mr',array('quantity'=>$qty,'date_modified'=>date('Y-m-d H:i:s',time())),array('id'=>$tabung['id']));
           $kartustok=array(
             'tabung_id'	=> $tabung['id'],
             'tgl'	=> isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s'),
             'stokmasuk'	=> 0,
             'stokkeluar'	=> $p['quantity'],
             'ket'	=> $this->db->escape("Pengembalian tabung MR kepada customer dengan nomor ".$data['no_so']),
             'saldo'	=> $qty,
             'quantityawal'	=> $tabung['quantity'],
             'invoice'	=> $data['no_so'],
             'tabel_ref' => 'sale/returntabung',
             'idref' => $data['id'],
             'jenistransaksi'  =>2,
             'type'	=> 2
           );
           $this->model_catalog_kartustoktabungmr->addKartuStok($kartustok);
         }



      }
    }

  }


  public function cancelPenjualan($id,$alasan){
    $penj=array(
      'status'  => 3,
      'alasan_batal'=>$this->db->escape($alasan)
    );

    $where=array(
      'id'  => $id
    );

    $tttk=$this->getPenjualan($where);
    $this->db->update('return_tabung',$penj,$where);
    //kembalikan stok produk
    $products=$this->getPenjualanProducts(array('tttk_id'=> $id));
    foreach($products as $p){
      $this->load->model('catalog/tabungmr');
      $this->load->model('catalog/kartustoktabungmr');
        if(!empty($p['product_id'])){

            //update tanggal pengembalian
            //untuk MR kalau no tabung ada  berarti update status ktersediaan, kalau tidak ada no tabung input dulu di master,
           $tabung=$this->model_catalog_tabungmr->getTabungByProduct($p['product_id'],$tttk['customer_id']);
           $qty=$tabung['quantity']+$p['quantity'];
           $this->db->update('tabung_mr',array('quantity'=>$qty,'date_modified'=>date('Y-m-d H:i:s',time())),array('id'=>$tabung['id']));
           $kartustok=array(
             'tabung_id'	=> $tabung['id'],
             'tgl'	=> date('Y-m-d H:i:s',time()),
             'stokkeluar'	=> 0,
             'stokmasuk'	=> $p['quantity'],
             'ket'	=> $this->db->escape("Pembatalan pengembalian tabung kepada customer dengan alasan ".$alasan),
             'saldo'	=> $qty,
             'quantityawal'	=> $tabung['quantity'],
             'invoice'	=> $id,
             //'gudang_id'	=> $data['gudang_id'],
             'type'	=> 1,
             'tabel_ref' => 'sale/returntabung',
             'idref' => $id,
             'jenistransaksi'  =>1,
           );
           $this->model_catalog_kartustoktabungmr->addKartuStok($kartustok);


        }

    }
  }



  public function updatePenjualan($data,$where=array()){
	$this->db->update('return_tabung',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('return_tabung',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order){
		return $this->db->firstdetail('return_tabung',$column,$join,$where,$order);
	}
  public function getPenjualanProducts($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('return_tabung_detail.product_id','return_tabung_detail.quantity','return_tabung_detail.id','return_tabung_detail.tutup','product.name');
    $join=array();
    $leftjoin=array();
    /*$join[]=array(
      'tablename' => 'tabung_mr',
      'firsttable'  => 'return_tabung_detail.product_id',
      'secondtable' => 'tabung_mr.product_id'
    );*/
    $join[]=array(
      'tablename' => 'product',
      'firsttable'  => 'return_tabung_detail.product_id',
      'secondtable' => 'product.product_id'
    );
    /*$leftjoin[]=array(
      'tablename' => 'product_options',
      'firsttable'  => 'tabung_mr.ukuran_tabung',
      'secondtable' => 'product_options.product_options_id'
    );*/
    //$where['tabung_mr.customer_id']
    return $this->db->alljoins('return_tabung_detail',$column,$join,$leftjoin,$where,array(),0,null);
	}

  public function getPenjualanKernets($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('tttk_kernet.id','tttk_kernet.pegawai_id','users.firstname');
    $join=array();
    $join[]=array(
      'tablename' => 'users',
      'firsttable'  => 'tttk_kernet.pegawai_id',
      'secondtable' => 'users.user_id'
    );

    return $this->db->alljoin('tttk_kernet',$column,$join,$where,array(),0,null);
	}
  public function getPenjualanProduct($where){
		return $this->db->first('return_tabung_detail',$where);
	}
	public function getPenjualans($column=array(),$join=array(),$where=array(),$order,$limit,$offset){
		return $this->db->alljoin('return_tabung',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where){
		return $this->db->count('return_tabung',$where);
	}


}
?>
