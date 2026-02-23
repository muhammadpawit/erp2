<?php
class ModelProduksiBukaproduksi extends Model {
  public function bukaProduksi($data){
    //cek data

    $p=array(

      'tanggalmulai'  => $data['tanggalmulai'].' '.$data['waktumulai'],
      'gudang_id' => $data['gudang_id'],
      'user_id' => $this->user->getId(),
      'keterangan'  => $this->db->escape($data['keterangan']),
      'status'  => 1
      //'hapus' => 0
    );
    $this->db->insert('bukaproduksi',$p);
    $id=$this->db->getLastId();

    $data['id'] = $id;
    $this->bukaProduksiBahan($data);
    return $id;
  }

  public function bukaProduksiBahan($data){
    foreach($data['bahan'] as $d){
      if(!empty($d['bahanbaku_id'])){
      $prod=array(
        'bukaproduksi_id'  => $data['id'],
        'bahanbaku_id'  => $d['bahanbaku_id'],
        'levelawal'  => $d['levelawal'],
        'qtyawal'  => $d['qtyawal'],

      );
      $this->db->insert('bukaproduksi_bahanbaku',$prod);
    }
    }
  }

  public function tutupProduksi($data){
    $this->load->model('catalog/bahanbaku');
    $this->load->model('gudang/kartustok');
    $data['jamselesai']=$data['waktuselesai'];
    $produksi=$this->getPermintaanPembelian(array(),array(),array('id'=>$data['id']));
    $this->db->update('bukaproduksi',array('status'=>2,'tanggalselesai'=>$data['tanggalselesai'].' '.$data['jamselesai'],'user_tutup'=>$this->user->getId()),array('id'=>$data['id']));
    foreach($data['bahan'] as $b){
      $bahanproduksi=$this->getBahanbaku(array('id'=>$b['id']));
      //$this->load->model('catalog/bahanbaku');
      $curqty=$this->model_catalog_bahanbaku->getProduct($b['bahanbaku_id']);

      //level dipakai
      $level=$b['levelawal'] - $b['levelakhir'];

      //konversi level ke kg
      $konv=$this->db->first('konversi_bahanbaku',array('satuan'=>507,'product_id' => $b['bahanbaku_id']));
      //jumlah terpakai
      $quantitypakai=0;
      $qtyakhir=$b['levelakhir']*$konv['nilai'];
      if(!empty($konv)){
        $quantitypakai=$konv['nilai'] * $level;

        $update=$this->model_catalog_bahanbaku->updateQty($b['bahanbaku_id'],$quantitypakai,2);



        if(!empty($b['penggembosan'])){
          $akhir=$b['levelakhir'] - $b['penggembosan'];
          $this->db->update('bahanbaku',array('level'=>$akhir),array('id'  => $b['bahanbaku_id']));
          $kartustok=array(
            'product_id'	=> $b['bahanbaku_id'],
            'product_name'	=> $curqty['name'],
      			'tglawal'	=> $produksi['tanggalmulai'],
      			'tglakhir'	=> $data['tanggalselesai'].' '.$data['jamselesai'],
      			'levelawal'	=> $b['levelawal'],
      			'levelakhir'	=> $akhir,
      			'qtyawal'	=> $curqty['quantity'],
      			'qtyakhir'	=> $update,
      			'ket'	=> 'Produksi',
      			'perubahan'	=> $level - $b['penggembosan'],
      			'ref'	=> $data['id'],
      			//'gudang_id'	=> $data['gudang_id'],
      			'type'	=> 7
      		);
          $this->model_gudang_kartustok->addKartuStokBahanbaku($kartustok);

          $kartustok=array(
            'product_id'	=> $b['bahanbaku_id'],
            'product_name'	=> $curqty['name'],
      			'tglawal'	=> $produksi['tanggalmulai'],
      			'tglakhir'	=> $data['tanggalselesai'].' '.$data['jamselesai'],
      			'levelawal'	=> $level-$b['penggembosan'],
      			'levelakhir'	=> $akhir,
      			'qtyawal'	=> $curqty['quantity'],
      			'qtyakhir'	=> $update,
      			'ket'	=> 'Penggembosan',
      			'perubahan'	=> $level - $b['penggembosan'],
      			'ref'	=> $data['id'],
      			//'gudang_id'	=> $data['gudang_id'],
      			'type'	=> 8
      		);
          $this->model_gudang_kartustok->addKartuStokBahanbaku($kartustok);

        }else{
          $kartustok=array(
            'product_id'	=> $b['bahanbaku_id'],
            'product_name'	=> $curqty['name'],
      			'tglawal'	=> $produksi['tanggalmulai'],
      			'tglakhir'	=> $data['tanggalselesai'].' '.$data['jamselesai'],
      			'levelawal'	=> $b['levelawal'],
      			'levelakhir'	=> $b['levelakhir'],
      			'qtyawal'	=> $curqty['quantity'],
      			'qtyakhir'	=> $update,
      			'ket'	=> 'Produksi',
      			'perubahan'	=> 0,
      			'ref'	=> $data['id'],
      			//'gudang_id'	=> $data['gudang_id'],
      			'type'	=> 7
      		);
          $this->model_gudang_kartustok->addKartuStokBahanbaku($kartustok);
          $this->db->update('bahanbaku',array('level'=>$b['levelakhir']),array('id'  => $b['bahanbaku_id']));
        }


        //hitung net cost
        //harga beli bahan baku /kg
        $hargabeli=$curqty['hargabeli'];
        //konversi ke m3
        $konv2=$this->db->first('konversi_bahanbaku',array('satuan'=>506,'product_id' => $b['bahanbaku_id']));

        if(!empty($konv2)){
          $nilai=($b['presentase']/100)*(($quantitypakai/$konv2['nilai'])*$hargabeli);
          $net_cost += $nilai;
        }

        $bahanbakupakai=array(
          'levelakhir'  => $b['levelakhir'],
          'qtyakhir'  =>$update,
          'penggembosan'  => $b['penggembosan'],
          'pemakaian' => $b['levelawal'] - $b['levelakhir'],
          'quantitypakai' => $quantitypakai,
          'quantitypakaim'  => $quantitypakai/$konv2['nilai']
          /*'jammulai'	=> $data['tanggal'].' '.$b['jammulai'],
          'jamselesai'	=> $data['tanggal'].' '.$b['jamselesai'],
          'levelawal'	=> $b['levelawal'],
          'levelakhir'	=> $b['levelakhir'],
          'produksi_id'	=> $hasil_id,
          'penggembosan'  => $b['penggembosan'],
          'pemakaian' => $b['levelawal']-$b['levelakhir'],
          'quantitypakai' => $quantitypakai,
          'quantitypakaim'  => $quantitypakai/$konv2['nilai']*/
        );

        $this->db->update('bukaproduksi_bahanbaku',$bahanbakupakai,array('id'=>$b['id']));
      }
      }
  }

  public function getPermintaanPembelians($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('bukaproduksi',$column,$join,$leftjoin,$where,array('id'=> 'DESC'),$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('bukaproduksi',$where);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('bukaproduksi',$column,$join,$where,array());
  }

  public function getPermintaanPembelianProduct($where){
    $column=array('bukaproduksi_bahanbaku.*','bahanbaku.name');
    $join=array();
    $join[]=array(
			'tablename'	=> 'bahanbaku',
			'firsttable'	=>'bukaproduksi_bahanbaku.bahanbaku_id',
			'secondtable'	=> 'bahanbaku.id'
		);
    $leftjoin=array();

    return $this->db->alljoins('bukaproduksi_bahanbaku',$column,$join,$leftjoin,$where,array(),0,null);
  }

  public function getBahanbaku($where){


    return $this->db->firstdetail('bukaproduksi_bahanbaku',array(),array(),$where);
  }

}
?>
