<?php
class ModelProduksiProsesproduksi extends Model {
  public function addProsesProduksi($id,$data){
    /*
    jenis produksi:
    1. MR
    2. Stok
    3. MP
    */
    /*
    Status Produksi:
    1. Produksi Dimulai
    2. Produksi Selesai
    3. Dibatalkan
    */
    //input history produksi

    //bukaproduksi
    $this->load->model('produksi/bukaproduksi');
    $buka=$this->model_produksi_bukaproduksi->getPermintaanPembelian(array(),array(),array('status'=>1));

    $this->load->model('gudang/kartustok');
    $this->load->model('gudang/product');
    $prod=array(
      'permintaan'  =>$data['permintaan'],
      'tanggal' =>date('Y-m-d',strtotime($buka['tanggalmulai'])),
    //  'waktumulai'  =>$data['tanggal'].' '.$data['waktumulai'],
    //  'waktuselesai'  =>$data['tanggal'].' '.$data['waktuselesai'],
      'quantityhasil'  =>empty($data['quantityhasil'])?0:$data['quantityhasil'],
      'quantityproses'  =>empty($data['quantityproses'])?0:$data['quantityproses'],
      'quantitypesan' => empty($data['quantitypesan'])?0:$data['quantitypesan'],
      'keterangan'  =>$this->db->escape($data['keterangan']),
      'jenis_produksi' =>$data['jenis_produksi'],
      'product_id'  =>$data['product_id'],
      'ukuran_tabung' =>$data['ukuran_tabung'],
      'customer_id' => empty($data['customer_id'])?0:$data['customer_id'],
      'no_so' =>empty($data['no_so'])?0:$data['no_so'],
      'gudang_id' =>empty($data['gudang_id'])?0:$data['gudang_id'],
      'bukaproduksi_id' => $buka['id']
    );
    $this->db->insert('proses_produksi',$prod);
    $hasil_id=$this->db->getLastId();

    //update permintaan produksi
    $permintaan=$this->db->firstdetail('permintaan_produksi',array(),array(),array('id'=>$id));
    $permproduct=$this->db->first('permintaan_produksi_product',array('surat_id'=> $id,'product_id'=>$data['product_id']));

    if(($permproduct['quantity_proses'] + $data['quantityhasil']) == $data['quantitypesan']){
      $status=5;
    }else{
      $status=4;
    }

    $this->db->update('permintaan_produksi',array('status' => $status),array('id'=>$id));
    $this->db->update('permintaan_produksi_product',array('quantity_proses' => $permproduct['quantity_proses'] + $data['quantityhasil']),array('surat_id'=> $id,'product_id'=>$data['product_id']));

    //stok
    $this->load->model('gudang/product');
    $curqty=$this->model_gudang_product->getProduct($data['product_id'],$data['gudang_id']);
    $update=$this->model_gudang_product->updateQty($data['product_id'],$data['gudang_id'],$data['quantityhasil'],1);

    $kartustok=array(
      'product_id'	=> $data['product_id'],
      'product_name'	=> $curqty['product_name'],
      'tgl'	=> $buka['tanggalmulai'],
      'stokkeluar'	=> 0,
      'stokmasuk'	=> $data['quantityhasil'],
      'ket'	=> 'Hasil Produksi',
      'saldo'	=> $update,
      'quantityawal'	=> $curqty['quantity'],
      'invoice'	=>$hasil_id,
      'gudang_id'	=> $data['gudang_id'],
      'type'	=> 7
    );

    $this->model_gudang_kartustok->addKartuStok($kartustok);



    //kartustok bahan baku
    $net_cost=0;
    foreach($data['bahan'] as $b){

        $bahanbakupakai=array(
          'bahanbaku_id'	=> $b['bahanbaku_id'],
          'produksi_id'	=> $hasil_id,
          'presentase'  => $b['presentase'],
          'bukaproduksi_id' => $buka['id']

        );

        $this->db->insert('proses_produksi_bahanbaku',$bahanbakupakai);
      }




    if(isset($data['tabung'])){
      if($data['jenis_produksi'] == 3){
        $this->load->model('catalog/kartustoktabungmp');
        foreach($data['tabung'] as $p){
          if(!empty($p['tabung_id'])){
          if($p['tabung_id'] > 0){
            $pt=array(
              'tabung_id' => $p['tabung_id'],
              'produksi_id' => $hasil_id,
              'quantity'  => 1
            );
            $this->db->insert('proses_produksi_tabung',$pt);
           $this->db->update('tabung_mp',array('status' => 1,'date_modified'=>date('Y-m-d H:i:s',time())),array('id'=>$p['tabung_id']));
          //  $penj=$this->getPenjualanDetail(array('id'=>$penj['id']));
            /*$kartustok=array(
        			'tabung_id'	=> $p['tabung_id'],
        			'tglpeminjaman'	=> $buka['tanggalmulai'],
        			'tglpengembalian'	=> $buka['tanggalmulai'],
        			'tglisiulang'	=>$buka['tanggalmulai'],
        			'customer_id'	=> 0,
              'invoice'	=> $hasil_id,
        			'ket'	=> 'Produksi Gas',
        			'biayasewa'	=> 0
        		);

            $this->model_catalog_kartustoktabungmp->addKartuStok($kartustok);*/
            $kartustok=array(
              'tabung_id'	=> $p['tabung_id'],
              'tglpeminjaman'	=> isset($buka['tanggalmulai'])?$buka['tanggalmulai']:date('Y-m-d H:i:s'),
              'tglpengembalian'	=> '1901-01-01',
              'tglisiulang'	=> $buka['tanggalmulai'],
              'customer_id'	=> 0,
              'invoice'	=> $hasil_id,
              'ket'	=> 'Pengisian Tabung no '.$hasil_id,
              'biayasewa'	=> 0,
              'tabel_ref' => 'produksi/hasilproduksi',
              'idref' => $hasil_id,
              'jenistransaksi'  =>3
            );

            $this->model_catalog_kartustoktabungmp->addKartuStok($kartustok);
          }
        }
        }
      }
    }

    $nilaitabung=0;

    if(isset($data['tabungms'])){
      if($data['jenis_produksi'] == 2){
      //  $this->load->model('catalog/kartustoktabungmp');
      $this->load->model('catalog/tabungms');
        foreach($data['tabungms'] as $p){
          if(!empty($p['product_id'])){
          if($p['product_id'] > 0){
            $pt=array(
              'tabung_id' => $p['product_id'],
              'produksi_id' => $hasil_id,
              'quantity'  => $p['quantity']
            );
            $this->db->insert('proses_produksi_tabung',$pt);

            $tbg=$this->model_gudang_product->getProduct($p['product_id'],$data['gudang_id']);

            //if($net_cost == 0){
            //  $net_cost +=$p['net_cost']*$p['quantity'];
              $nilaitabung += $p['net_cost'];

              /*
          		product_id
          		gudang_id
          		jenisgas
          		quantity
          		tanggal
          		ref
          		*/
              $tabungms=array(
                'product_id'  => $p['product_id'],
                'gudang_id' => $data['gudang_id'],
                'jenisgas'  => $data['product_id'],
                'quantity'  => $p['quantity'],
                'tanggal' =>$buka['tanggalmulai'],
                'ref' => $hasil_id
              );
              $this->model_catalog_tabungms->addTabung($tabungms);

          }
        }
        }
      }
    }
    /*$netcost=(($prod['quantity']*$prod['net_cost'])+$net_cost)/$prod['quantity']+$data['quantityhasil'];
    $this->model_gudang_product->updateNetCost($data['product_id'],$data['gudang_id'],$netcost);
    $this->load->model('keuangan/jurnal');


    $details=array();
    $details[]=array(
      'ref_akun'  => '11.05.02',
      'keterangan'  => "Persediaan barang jadi hasil produksi",
      'debet' => $net_cost,
      'kredit'  => 0,
      'urutan'  => 1,
      'hapus' => 0
    );


    $details[]=array(
      'ref_akun'  => '11.05.01',
      //'jenis_akun'  => 52,
      'keterangan'  => 'Persediaan bahan baku',
      'debet' => 0,
      'kredit'  => $net_cost,
      'urutan'  => 2,
      'hapus' => 0
    );

    $j=array(
      'tanggal' =>$data['tanggal'].' '.$data['waktuselesai'],
      'keterangan'  => 'Proses Produksi',
      'details' => $details,
      'hapus' =>0,
      'ref' => $hasil_id,
      'type'  => 21
    );
    $this->model_keuangan_jurnal->addJurnalUmum($j);

    return $id;*/
  }

  /*public function getProsesProduksi($id){
    return
  }*/

  public function updatePermintaan($data,$where){
    $this->db->update('proses_produksi',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('proses_produksi',$column,$join,$leftjoin,$where,array('id'=> 'DESC'),$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('proses_produksi',$where);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('proses_produksi',$column,$join,$where,array());
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

  public function getPermintaanPembelianTabung($where){

    return $this->db->alljoins('proses_produksi_tabung',array(),array(),array(),$where,array(),0,null);
  }
  public function getPermintaanPembelianBahanbaku($where){
    $join=array();
    $join[]=array(
      'tablename' => 'bahanbaku',
      'firsttable'  => 'proses_produksi_bahanbaku.bahanbaku_id',
      'secondtable' => 'bahanbaku.id'
    );

    return $this->db->alljoins('proses_produksi_bahanbaku',array('proses_produksi_bahanbaku.*','bahanbaku.name'),$join,array(),$where,array(),0,null);
  }

}
?>
