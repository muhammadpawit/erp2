<?php
class ModelPembelianPembayarandepositkredit extends Model {
  public function addPembelian($data){
    /*status
    1. disimpan
    2. diterima
    3. dibatalkan

    jenis
    1. deposit customer
    2. pembayaran tunai & cod

    metode_pembayaran
    1. tunai
    2. transfer bank
    3. giro
    4. cheque
    */
    $data['status']=2;
    $data['jenis']=1;
    $p=array(
      'tgl_diterima' => !empty($data['tgl_diterima'])?$data['tgl_diterima']:'1970-01-01',
      'nominal'  => empty($data['nominal'])?0:$data['nominal'],
      'biaya_bank'  => empty($data['nominal'])?0:$data['biaya_bank'],
      'no_giro'  => $data['no_giro'],
      'tgl_bayar'  => $data['tgl_bayar'],
      'status'  => empty($data['status'])?1:$data['status'],
      'keterangan'  => $this->db->escape($data['keterangan']),
      'bank_id' => $data['bank_id'],
      'vendor_id' => $data['vendor_id'],
      'vendor_name' => $data['vendor_name'],
      'cetak'  => 0,
      'ref'  => empty($data['ref'])?0:$data['ref'],
      'user_id' => $this->user->getId(),
      'hapus' =>0,
      'metode_pembayaran' => $data['metode_pembayaran'],
      'jenis' => $data['jenis'],
      'biaya_lain'=>empty($data['biaya_lain'])?0:$data['biaya_lain'],
      'pendapatan_lain'=>empty($data['pendapatan_lain'])?0:$data['pendapatan_lain'],
    );

    $this->db->insert('pembayaran_deposit_lokal',$p);
    $id=$this->db->getLastId();
    $no_pd='PbDK-'.$id.'-'.date('Y').'-'.date('m').'-'.$this->user->getId();

    $this->db->update('pembayaran_deposit_lokal',array('no_pd'=>$no_pd),array('id'=>$id));

    if($data['status'] == 2){

      if($data['jenis'] == 1){
        $this->load->model('catalog/vendorlokal');
        $depositdata=array(
          'ref'=> $id,
    			'date_trans'	=> $data['tgl_diterima'],
    			'nominal'	=> $data['nominal'],
          'keterangan'	=> $data['keterangan'],
          'metode_pembayaran'=>$data['metode_pembayaran'],
    			'bank_id'	=> $data['bank_id'],
          'biaya_bank'  => $data['biaya_bank'],
          'biaya_lain'=>empty($data['biaya_lain'])?0:$data['biaya_lain'],
          'pendapatan_lain'=>empty($data['pendapatan_lain'])?0:$data['pendapatan_lain'],
          'no_dokumen'  => $no_pd

        );
        $this->model_catalog_vendorlokal->addDeposit($depositdata,$data['vendor_id']);

 

      }



  }
}

  public function updatePermintaan($data,$where){
    if($data['status'] == 3){
      $pb=$this->getPermintaanPembelian(array('*'),array(),$where);
      if($pb['status'] == 2){
        if($pb['jenis'] == 1){
          $this->load->model('catalog/vendorlokal');
          $depositdata=array(
            'ref'=> $pb['id'],
      			'date_trans'	=> $pb['tgl_diterima'],
      			'nominal'	=> $pb['nominal'],
      			'keterangan'	=> 'Pembatalan deposit ',
      			'bank_id'	=> $pb['bank_id'],
            'biaya_bank'  => $pb['biaya_bank'],
            'biaya_lain'  => $pb['biaya_lain'],
            'pendapatan_lain'  => $pb['pendapatan_lain'],
            'no_dokumen'  => $pb['no_pd']

          );
          $this->model_catalog_vendorlokal->cancelDeposit($depositdata,$pb['vendor_id']);
        }

      }


      $this->db->update('pembayaran_deposit_lokal',$data,$where);

    }
    if($data['status'] == 2){
      $pb=$this->getPermintaanPembelian(array('*'),array(),$where);
      if($pb['status'] == 1){
        if($pb['jenis'] == 1){
          $this->load->model('catalog/vendorlokal');
          $depositdata=array(
            'ref'=> $pb['id'],
      			'date_trans'	=> $data['tgl_diterima'],
      			'nominal'	=> $pb['nominal'],
      			'keterangan'	=> $data['keterangan'],
      			'bank_id'	=> $pb['bank_id'],
            'biaya_bank'  => $pb['biaya_bank'],
            'biaya_lain'  => $pb['biaya_lain'],
            'pendapatan_lain'  => $pb['pendapatan_lain'],
            'no_dokumen'  => $pb['no_pd']


          );
          $this->model_catalog_vendorlokal->addDeposit($depositdata,$pb['vendor_id']);



        }



        $this->db->update('pembayaran_deposit_lokal',$data,$where);

      }
    }

  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('pembayaran_deposit_lokal',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('pembayaran_deposit_lokal',$where);
	}
  public function totalDp($no_po){
    return $this->db->firstdetail('pembayaran_deposit_lokal',array('COALESCE(SUM(nominal)) as total'),array(),array('order_id' => $no_po),array());
  }

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('pembayaran_deposit_lokal',$column,$join,$where,array());
  }

  public function getDepositTersedia($vendor_id){
    $sql="SELECT * FROM pembayaran_deposit_lokal WHERE status=2 AND nominal > COALESCE(totalalokasi,0) AND vendor_id='".$vendor_id."' ORDER BY tgl_bayar ASC";
    $results=$this->db->query($sql);

    return $results->rows;
  }

  public function getDepositTersediasum($vendor_id){
    $sisa=0;
    $sql="SELECT sum(nominal) as nominal, sum(totalalokasi) as totalalokasi FROM pembayaran_deposit_lokal WHERE status=2 AND nominal > COALESCE(totalalokasi,0) AND vendor_id='".$vendor_id."'";
    $results=$this->db->query($sql);
    $d=$results->row;
    if(!empty($d)){
      $sisa=$d['nominal']-$d['totalalokasi'];
    }
    return $sisa;
  }


}
?>
