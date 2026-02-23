<?php
class ModelSaleTttk extends Model {
  /*
  Status
  1. disimpan
  2. disetujui
  3. dibatalkan

  */
  public function addPenjualan($data){
    $penj=array(
      'date_added' => isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s'),
      'tttk_manual'  => $data['tttk_manual'],
      'customer_id'  => $data['customer_id'],
      'status' => 1,
      'pengiriman'  => $data['pengiriman'],
      'hapus' => 0,
      'user_id'  => $this->user->getId(),
      'address_id' =>isset($data['address_id'])?$data['address_id']:0,
      'no_so'  => '',
      'no_pol'  => $data['no_pol'],
      'sopir' => empty($data['sopir'])?0:$data['sopir'],
      //'tabungmp'  => $data['tabungmp'],
      //'tabungmr'  => $data['tabungmr']
    );
    $this->db->insert('tttk',$penj);
    $id=$this->db->getlastId();
    $data['id']=$id;
    $no_so="TTTK-".$id."-".date('Y')."-".date("m")."-".$this->user->getId();
    $this->db->update('tttk',array('no_so' => $no_so),array('id' => $id));
    $data['no_tttk']=$no_so;
    $this->addPenjualanProduct($data);
    $this->addPenjualanKernet($data);


    return $id;

  }
  public function addPenjualanProduct($data){
    $this->load->model('catalog/tabungmp');
    $this->load->model('catalog/kartustoktabungmp');
    foreach($data['product'] as $p){
        if(!empty($p['product_id'])){
          $penj=array(
            'tttk_id' => $data['id'],
            'tabung_id' => $p['product_id'],
            'tutup' => $p['tutup']
          );

          $this->db->insert('tttk_tabung',$penj);

          //update tanggal pengembalian 
          //untuk MR kalau no tabung ada  berarti update status ktersediaan, kalau tidak ada no tabung input dulu di master,
         $tabung=$this->model_catalog_tabungmp->getTabung($p['product_id']);
          $tab=array(
            'status'	=> 4,
            
            'date_modified'	=> date('Y-m-d H:i:s',time()),
            'customer_id' => 0
          );
          //$this->model_catalog_tabungmp->editTabung($p['product_id'],$tab);
          $this->db->update('tabung_mp',$tab,array('id'=>$p['product_id']));
          //input kartustok
          //if($tabung['pemilik'] == 1){

            $kartustok=array(
              'tabung_id'	=> $p['product_id'],
              'tglpeminjaman'	=> isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s'),
              'tglpengembalian'	=> '1901-01-01',
              'tglisiulang'	=> $data['date_added'],
              'customer_id'	=> $data['customer_id'],
              'invoice'	=> $data['no_tttk'],
              'ket'	=> 'Pengembalian Tabung tabung no '.$data['no_tttk'],
              'biayasewa'	=> 0,
              'tabel_ref' => 'sale/tttk',
              'idref' => $data['id'],
              'jenistransaksi'  =>2
            );

            $this->model_catalog_kartustoktabungmp->addKartuStok($kartustok);
            

      }
    }

  }

  public function addPenjualanKernet($data){

    foreach($data['kernet'] as $p){
        if(!empty($p)){
          $penj=array(
            'tttk_id' => $data['id'],
            'pegawai_id' => $p
          );


          $this->db->insert('tttk_kernet',$penj);



      }
    }

  }

  



  public function cancelPenjualan($id){
    $penj=array(
      'status'  => 3,
      'alasan_batal'  => $alasan_batal
    );

    $where=array(
      'id'  => $id
    );
    $this->db->update('tttk',$penj,$where);
    $tttk=$this->getPenjualan($where);

    $this->load->model('catalog/tabungmp');
    $this->load->model('catalog/kartustoktabungmp');

    $products=$this->getPenjualanProducts(array('tttk_id'=> $id));
    foreach($products as $p){
    $tabung=$this->model_catalog_tabungmp->getTabung($p['tabung_id']);
    $tab=array(
      'status'	=> 6,
      
      'date_modified'	=> date('Y-m-d H:i:s',time()),
      'customer_id' => $tttk['customer_id']
    );
    //$this->model_catalog_tabungmp->editTabung($p['product_id'],$tab);
    $this->db->update('tabung_mp',$tab,array('id'=>$p['tabung_id']));
    //input kartustok
    //if($tabung['pemilik'] == 1){
      
      $kartustok=array(
        'tabung_id'	=> $p['tabung_id'],
        'tglpeminjaman'	=> date('Y-m-d H:i:s'),
        'tglpengembalian'	=> '1901-01-01',
        'tglisiulang'	=> date('Y-m-d H:i:s'),
        'customer_id'	=> $tttk['customer_id'],
        'invoice'	=> $tttk['no_so'],
        'ket'	=> 'Pembatalan Pengembalian Tabung tabung no '.$tttk['no_so'],
        'biayasewa'	=> 0,
        'tabel_ref' => 'sale/tttk',
        'idref' => $tttk['id'],
        'jenistransaksi'  =>2
      );

      $this->model_catalog_kartustoktabungmp->addKartuStok($kartustok);
    }
  }
    

  public function setujuPenjualan($id){
    $penj=array(
      'status'  => 2
    );

    $where=array(
      'id'  => $id
    );
    $this->db->update('tttk',$penj,$where);
    $t=$this->getPenjualan(array('id'=>$id));
    $products=$this->getPenjualanProducts(array('tttk_id'=>$id));
    $this->load->model('catalog/tabungmp');
    $this->load->model('catalog/kartustoktabungmp');
    foreach($products as $p){
      $tabung=$this->model_catalog_tabungmp->getTabung($p['tabung_id']);
        $tab=array(
          'status'	=> 1,
          'ukuran_tabung'	=> $tabung['ukuran_tabung'],
          'tglpembelian'	=> $tabung['tglpembelian'],
          'hargabeli'	=> $tabung['hargabeli'],
          'kelompok_aset'	=> $tabung['kelompok_aset'],
          'date_added'	=> $tabung['date_added'],
          'date_modified'	=> date('Y-m-d H:i:s',time())
        );
        $this->model_catalog_tabungmp->editTabung($p['tabung_id'],$tab);

        //input kartustok
        if($tabung['pemilik'] == 1){

          $this->model_catalog_kartustoktabungmp->updateKartuStok($p['tabung_id'],$t['customer_id'],date('Y-m-d',time()));

        }
    }
    //kembalikan stok produk
    /*$products=$this->getPenjualanProducts(array('tttk_id'=> $id));
    foreach($products as $p){
      $this->deleteProduct($p['id']);
    }*/
  }

  public function updatePenjualan($data,$where=array()){
	$this->db->update('tttk',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('tttk',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order){
		return $this->db->firstdetail('tttk',$column,$join,$where,$order);
	}
  public function getPenjualanProducts($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('tttk_tabung.tabung_id','tttk_tabung.id','tttk_tabung.id','tabung_mp.no_tabung','tttk_tabung.tutup','product_options.name','tabung_mp.pemilik');
    $join=array();
    $join[]=array(
      'tablename' => 'tabung_mp',
      'firsttable'  => 'tttk_tabung.tabung_id',
      'secondtable' => 'tabung_mp.id'
    );
    $join[]=array(
      'tablename' => 'product_options',
      'firsttable'  => 'tabung_mp.ukuran_tabung',
      'secondtable' => 'product_options.product_options_id'
    );

    return $this->db->alljoin('tttk_tabung',$column,$join,$where,array(),0,null);
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
		return $this->db->first('tttk_tabung',$where);
	}
	public function getPenjualans($column=array(),$join=array(),$where=array(),$order,$limit,$offset){
		return $this->db->alljoin('tttk',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where){
		return $this->db->count('tttk',$where);
	}


}
?>
