<?php
class ModelGudangTransferaset extends Model {
  public function addTransferaset($data){
    $this->load->model('gudang/product');


    if($data['jenisaset'] == 1){
      $this->load->model('catalog/aset');
      $aset=$this->model_catalog_aset->getAset(array('aset_id'=>$data['aset_id']));
    }else{
      $this->load->model('catalog/tabungmp');
      $aset=$this->model_catalog_tabungmp->getTabung($data['aset_id']);
    }
    $product=$this->model_gudang_product->getProduct($data['product_id'],$data['gudang_id']);


    if(empty($product)){
      $kl=array(
  			'gudang_id'	=> $data['gudang_id'],
  			'product_id'	=> $data['product_id'],
  			'qty'	=> 0,
  			'net_cost'	=> 0,
  			'date_added'	=>date('Y-m-d H:i:s',time())
  		);
      $this->model_gudang_product->addStokAwal($kl);
    }



    $p=array(
      'tanggal' => $data['tanggal'],
      'status'  => 1,
      'hapus' => 0,
      'gudang_id' => $data['gudang_id'],
      'user_buat' => $this->user->getId(),
      'jenisaset' => $data['jenisaset'],/*1. aset 2. tabung mp*/
      'aset_id' => $data['aset_id'],
      'product_id'  => $data['product_id'],
      'keterangan'  => $this->db->escape($data['keterangan']),
      'nilaiaset' => $aset['nilaibuku'],
      'quantity'  => 1,
      'hargabeli' => $aset['hargabeli']

    );
    $this->db->insert('transfer_aset',$p);
    $id=$this->db->getLastId();

    $no_tukartabung='TA-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;

    $this->db->update('transfer_aset',array('no_transferaset' => $no_tukartabung),array('id'  => $id));
    return $no_tukartabung;
  }

  public function prosesTransferAset($id,$tgl_proses){
    $detail=$this->getTransferAset(array(),array(),array('id'=>$id));
    if($detail['status'] == 1){
      if($detail['jenisaset'] == 1){
        $this->load->model('catalog/aset');
        $aset=$this->model_catalog_aset->getAset(array('aset_id'=>$detail['aset_id']));



        $this->db->update('aset',array('status' => 4,'nilaibuku'=>0,'akumulasipenyusutan'=>$aset['hargabeli']),array('aset_id'=> $detail['aset_id']));
      }else{
        $this->load->model('catalog/tabungmp');
        $aset=$this->model_catalog_tabungmp->getTabung($detail['aset_id']);

        $this->db->update('tabung_mp',array('status' => 7,'nilaibuku'=>0,'akumulasipenyusutan'=>$aset['hargabeli']),array('id'=> $detail['aset_id']));
      }




      $this->load->model('gudang/product');
      $this->load->model('gudang/kartustok');

      $curqty=$this->model_gudang_product->getProduct($detail['product_id'],$detail['gudang_id']);
      $update=$this->model_gudang_product->updateQty($detail['product_id'],$detail['gudang_id'],$detail['quantity'],1);

      $kartustok=array(
        'product_id'	=> $detail['product_id'],
        'product_name'	=> "",
        'tgl'	=> $tgl_proses,
        'stokmasuk'	=> $detail['quantity'],
        'stokkeluar'	=> 0,
        'ket'	=> 'Proses Transfer Aset ke Produk Dagang',
        'saldo'	=> $update,
        'quantityawal'	=> $curqty['quantity'],
        'invoice'	=>$id,
        'gudang_id'	=> $detail['gudang_id'],
        'type'	=> 11001
      );

      $this->model_gudang_kartustok->addKartuStok($kartustok);

      if($curqty['quantity'] > 0){

          if($curqty['net_cost'] > 0){
            $netcost=(($detail['quantity'] * $detail['nilaiaset'])+($curqty['quantity']*$curqty['net_cost']))/($detail['quantity']+$curqty['quantity']);

          }else{
            $netcost=(($detail['quantity'] * $detail['nilaiaset']))/($detail['quantity']);
          }
        //}
      }else{
        $netcost=(($detail['quantity'] * $detail['nilaiaset']))/($detail['quantity']);
      }
        $this->model_gudang_product->updateNetCost($detail['product_id'],$detail['gudang_id'],$netcost);




          $this->db->update('transfer_aset',array('status'=>2,'tanggal_disetujui'=>$tgl_proses,'user_proses'=> $this->user->getId()),array('id'=>$id));

          $this->load->model('catalog/kelompokaset');
    			$aktiva=$this->model_catalog_kelompokaset->getAktiva(array('no_akun'  => $aset['jenis_aktiva']));


          //jurnal
          if($detail['hargabeli'] > 0){
            $this->load->model('keuangan/jurnal');
      			$detail=array();
      			/*$detail[]=array(
      				'ref_akun'  =>$aktiva['beban'],
      				'debet' => $aset['nilaibuku'],
      				'kredit'  => 0,
      				'urutan'  =>1,
      				'keterangan'  => 'Penyusutan Aset'
      			);*/
            if($aset['hargabeli'] - $aset['nilaibuku'] > 0){
      			$detail[]=array(
      				'ref_akun'  =>$aktiva['akumulasi'],
      				'kredit' => 0,
      				'debet'  => $aset['hargabeli'] - $aset['nilaibuku'],
      				'urutan'  =>1,
      				'keterangan'  => 'Akumulasi Penyusutan Aset'
      			);
            }

            $detail[]=array(
      				'ref_akun'  =>1202,
      				'kredit' => 0,
      				'debet'  => $aset['nilaibuku'],
      				'urutan'  =>2,
      				'keterangan'  => 'Persediaan Barang Jadi'
      			);

            $detail[]=array(
      				'ref_akun'  =>$aktiva['persediaan'],
      				'debet' => 0,
      				'kredit'  => $aset['hargabeli'],
      				'urutan'  =>3,
      				'keterangan'  => 'Aktiva Tetap'
      			);

      			$jurnal=array(
      				'tanggal' => $tgl_proses,
      				'keterangan' => 'Transfer Aset ke Produk Dagang',
      				'ref' => $id,
      				'type' => 110001,
      				'details' => $detail
      			);
      			$this->model_keuangan_jurnal->addJurnalUmum($jurnal);
          }
    }
  }

  public function updatePermintaan($data,$where){
    $this->db->update('transfer_aset',$data,$where);
  }
  public function getTransferAsets($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('transfer_aset',$column,$join,$leftjoin,$where,array('id'=> 'DESC'),$limit,$offset);
  }

  public function totalTransferAsets($where,$join=array()){
		return $this->db->countAll('transfer_aset',$where,$join);
	}

  public function getTransferAset($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('transfer_aset',$column,$join,$where,array());
  }


}
?>
