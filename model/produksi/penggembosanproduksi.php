<?php
class ModelProduksiPenggembosanproduksi extends Model {
  public function addPermintaanPembelian($data){
    /*
    jenis produksi:
    1. MR
    2. Stok
    3. MP
    */


    $p=array(
      'no_surat'  => null,
      'asal'  => $data['divisi_asal'],
      'keterangan'  => $this->db->escape($data['tujuan_pembelian']),
      'status'  => 1,
      'gudang_id' => $data['gudang_id'],
      'jenis_produksi'  => $data['jenis_produksi'],
      'date_added'  => date('Y-m-d H:i:s',time()),
      'date_modified' => date('Y-m-d H:i:s',time()),
      //'hapus' => 0
    );
    $this->db->insert('penggembosan_produksi',$p);
    $id=$this->db->getLastId();

    $no_surat='SP-PgP-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;

    $this->db->update('penggembosan_produksi',array('no_surat' => $no_surat),array('id'  => $id));
    $data['id'] = $id;
    $this->addPermintaanProduct($data);
    return $id;
  }

  public function addPermintaanProduct($data){
    $this->load->model('catalog/kartustoktabungstok');
    $this->load->model('catalog/tabungms');
    $this->load->model('keuangan/jurnal');
    $this->load->model('gudang/product');
    $this->load->model('gudang/kartustok');
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
        $this->db->insert('penggembosan_produksi_product',$prod);

        //update stok produk
        $curqty=$this->model_gudang_product->getProduct($d['product_id'],$data['gudang_id']);
        $update=$this->model_gudang_product->updateQty($d['product_id'],$data['gudang_id'],$d['quantity'],2);

        $kartustok=array(
          'product_id'	=> $d['product_id'],
          'product_name'	=> $curqty['product_name'],
          'tgl'	=> date('Y-m-d h:i:s',time()),
          'stokkeluar'	=> $d['quantity'],
          'stokmasuk'	=> 0,
          'ket'	=> 'Penggembosan isi gas',
          'saldo'	=> $update,
          'quantityawal'	=> $curqty['quantity'],
          'invoice'	=>$data['id'],
          'gudang_id'	=> $data['gudang_id'],
          'type'	=> 7
        );

        $this->model_gudang_kartustok->addKartuStok($kartustok);


        //cek ms

        $prod=$this->model_gudang_product->getProduct($d['product_id'],$data['gudang_id']);

        $this->load->model('keuangan/jurnal');
       if(!empty($prod['net_cost'])){
          $details=array();
          $details[]=array(
            'ref_akun'  => '1202',
            //'jenis_akun'  => 52,
            'keterangan'  => 'Persediaan barang jadi',
            'kredit' => 0,
            'debet'  => $prod['net_cost'],
            'urutan'  => 1,
            'hapus' => 0
          );


          $details[]=array(
            'ref_akun'  => '6299',
            //'jenis_akun'  => 52,
            'keterangan'  => 'Biaya Lain-lain',
            'debet' => 0,
            'kredit'  => $prod['net_cost'],
            'urutan'  => 2,
            'hapus' => 0
          );


          $j=array(
            'tanggal' => date('Y-m-d'),
            'keterangan'  => 'Penggembosan hasil produksi '.$data['id'],
            'details' => $details,
            'hapus' =>0,
            'ref' => $data['id'],
            'type'  => 22
          );
          $this->model_keuangan_jurnal->addJurnalUmum($j);
        }
      }
    }
    if($data['jenis_produksi'] == 2){
      foreach($data['tabungms'] as $d){
        $tabung=array(
          'tabung_id' => $d['product_id'],
          'produksi_id' => $data['id'],
          'quantity'  => $d['quantity']
        );
        $this->db->insert('penggembosan_produksi_tabung',$tabung);
      $cek=$this->model_catalog_tabungms->getProductByGudang($d['product_id'],$data['gudang_id']);
      if(!empty($cek)){
        $curmsqty=$this->model_catalog_tabungms->getProductByGudang($d['product_id'],$data['gudang_id']);


        $updatems=$this->model_catalog_tabungms->updateQty($d['product_id'],$data['gudang_id'],$d['quantity'],2);

        $kartustok=array(
          'tabung_id'	=> $d['product_id'],
          'tgl'	=> date('Y-m-d h:i:s',time()),
          'stokkeluar'	=> $d['quantity'],
          'stokmasuk'	=> 0,
          'ket'	=> $this->db->escape("Penggembosan isi gas"),
          'saldo'	=> $updatems,
          'quantityawal'	=> $curmsqty['quantity'],
          'invoice'	=> $data['id'],
          'gudang_id'	=> $data['gudang_id'],
          'type'	=> 2
        );

          $this->model_catalog_kartustoktabungstok->addKartuStok($kartustok);


      }
      }
    }
    if($data['jenis_produksi'] == 3){
      $this->load->model('catalog/kartustoktabungmp');
      foreach($data['tabung'] as $d){
        if(!empty($d['tabung_id'])){
          $this->db->update('tabung_mp',array('status' => 4,'date_modified'=>date('Y-m-d H:i:s',time())),array('id'=>$d['tabung_id']));
        //  $penj=$this->getPenjualanDetail(array('id'=>$penj['id']));
          $kartustok=array(
            'tabung_id'	=> $d['tabung_id'],
            'tglpeminjaman'	=> date('Y-m-d H:i:s',time()),
            'tglpengembalian'	=> date('Y-m-d H:i:s',time()),
            'tglisiulang'	=> date('Y-m-d H:i:s',time()),
            'customer_id'	=> 0,
            'invoice'	=> $data['id'],
            'ket'	=> 'Penggembosan Produksi Gas',
            'biayasewa'	=> 0
          );

          $this->model_catalog_kartustoktabungmp->addKartuStok($kartustok);

          $tabung=array(
            'tabung_id' => $d['tabung_id'],
            'produksi_id' => $data['id'],
            'quantity'  => 1
          );
          $this->db->insert('penggembosan_produksi_tabung',$tabung);
        }

      }
    }
  }
  public function updatePermintaan($data,$where){
    $this->db->update('penggembosan_produksi',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('penggembosan_produksi',$column,$join,$leftjoin,$where,array('id'=> 'DESC'),$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('penggembosan_produksi',$where);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('penggembosan_produksi',$column,$join,$where,array());
  }

  public function getPermintaanPembelianProduct($where){
    $column=array('penggembosan_produksi_product.*','product.name','product.ukuran_tabung','product_options.name as namaukuran');
    $join=array();
    $join[]=array(
			'tablename'	=> 'product',
			'firsttable'	=>'penggembosan_produksi_product.product_id',
			'secondtable'	=> 'product.product_id'
		);
    $leftjoin=array();
    $leftjoin[]=array(
      'tablename'	=> 'product_options',
			'firsttable'	=>'product.ukuran_tabung',
			'secondtable'	=> 'product_options.product_options_id'
    );
    return $this->db->alljoins('penggembosan_produksi_product',$column,$join,$leftjoin,$where,array(),0,null);
  }
  public function getPermintaanPembelianTabung($where){

    return $this->db->alljoins('penggembosan_produksi_tabung',array(),array(),array(),$where,array(),0,null);
  }

}
?>
