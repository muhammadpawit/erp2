<?php
class ModelKeuanganRefunddeposit extends Model {
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
    $p=array(
      'tgl_diterima' => !empty($data['tgl_diterima'])?$data['tgl_diterima']:'1970-01-01',
      'nominal'  => empty($data['nominal'])?0:$data['nominal'],
      'biaya_bank'  => empty($data['nominal'])?0:$data['biaya_bank'],
      'no_giro'  => $data['no_giro'],
      'tgl_bayar'  => $data['tgl_bayar'],
      'status'  => empty($data['status'])?1:$data['status'],
      'keterangan'  => $this->db->escape($data['keterangan']),
      'bank_id' => $data['bank_id'],
      'customer_id' => $data['customer_id'],
      'customer_name' => $data['customer_name'],
      'cetak'  => 0,
      'ref'  => empty($data['ref'])?0:$data['ref'],
      'user_id' => $this->user->getId(),
      'hapus' =>0,
      'metode_pembayaran' => $data['metode_pembayaran'],
      'jenis' => 1
    );

    $this->db->insert('refund_deposit',$p);
    $id=$this->db->getLastId();
    /*$no_pd='PD-'.$id.'-'.date('Y').'-'.date('m').'-'.$this->user->getId();

    $this->db->update('penerimaan_dana',array('no_pd'=>$no_pd),array('id'=>$id));
    */

    $this->load->model('sale/customer');

    $this->model_sale_customer->updateDeposit($data['customer_id'],$data['nominal'],2);
    $hutang=array(
			'ref'=> $id,
			'date_trans'	=> $data['tgl_bayar'],
			'saldomasuk'	=> 0,
			'saldokeluar'	=> $data['nominal'],
			'keterangan'	=> $this->db->escape("Refund Deposit Customer ".$data['keterangan']),
			'hapus'	=> 0,
			'customer_id'=> $data['customer_id'],
			'date_added'	=> date('Y-m-d H:i:s'),
			'date_modified' => date('Y-m-d H:i:s')
		);

    $this->model_sale_customer->addHistoryDeposit($hutang);

    //input mutasi
    $this->load->model('keuangan/bank');

    $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $data['bank_id']));
		$saldo=$b['saldo'] - $data['nominal'] - $data['biaya_bank'];
		$this->model_keuangan_bank->editBank(array('saldo'  => $saldo),array('id'=> $data['bank_id']));

		$aruskas=array(
			'date_added'	=> date('Y-m-d H:i:s'),
			'date_trans'	=> $data['tgl_bayar'],
			'bank_id' => $data['bank_id'],
			'saldokeluar'  => $data['nominal']+$data['biaya_bank'],
			'saldomasuk' => 0,
			'saldoawal' => $b['saldo'],
			'saldoakhir'  => $saldo,
			'ref' => $id,
			'keterangan'  => 'Refund Uang Muka Penjualan '.$data['customer_name'],
			'type'  => 2003,
			'ref_akun'  => '2401'
		);

		$this->model_keuangan_bank->addAruskas($aruskas);

    $this->load->model('keuangan/jurnal');
    $detail=array();


      $detail[]=array(
        'ref_akun'  =>'2401',
        'debet' => $data['nominal'],
        'kredit'  => 0,
        'urutan'  =>1,
        'keterangan'  => 'Uang Muka Penjualan'
      );
      if($data['biaya_bank'] > 0){
  			$detail[]=array(
  				'ref_akun'  => '6265',
  				'keterangan'  => $this->db->escape('Biaya Administrasi Bank'),
  				'debet' => $data['biaya_bank'],
  				'kredit'  => 0,
  				'urutan'  => 2,
  			);
  		}
      if($b['saldo'] < 0){
  			if($b['hutangprk'] == 1){
          $detail[]=array(
            'ref_akun'  => '2001',
            'keterangan'  => $this->db->escape('Hutang PRK'),
            'kredit' => $data['nominal']+$data['biaya_bank'],
            'debet'  => 0,
            'urutan'  => 3,
          );

  			}else{
  				$detail[]=array(
  					'ref_akun'  => $b['rek_parent'],
  					'keterangan'  => $this->db->escape('Refund Uang Muka Penjualan'),
  					'kredit' => $data['nominal']+$data['biaya_bank'],
  					'debet'  => 0,
  					'urutan'  => 3,
  				);
  			}
  		}else{
        if(($b['saldo'] - $data['nominal'] - $data['biaya_bank']) < 0){
          $detail[]=array(
  					'ref_akun'  => $b['rek_parent'],
  					'keterangan'  => $this->db->escape('Refund Uang Muka Penjualan'),
  					'kredit' => $b['saldo'],
  					'debet'  => 0,
  					'urutan'  => 3,
  				);

          $hutangprk=$b['saldo'] - $data['nominal'] - $data['biaya_bank'];
          $detail[]=array(
  					'ref_akun'  => '2001',
  					'keterangan'  => $this->db->escape('Refund Uang Muka Penjualan'),
  					'kredit' => abs($hutangprk),
  					'debet'  => 0,
  					'urutan'  => 4,
  				);
        }else{
          $detail[]=array(
  					'ref_akun'  => $b['rek_parent'],
  					'keterangan'  => $this->db->escape('Refund Uang Muka Penjualan'),
  					'kredit' => $data['nominal']+$data['biaya_bank'],
  					'debet'  => 0,
  					'urutan'  => 3,
  				);
        }

  		}


      $jurnal=array(
        'tanggal' => $data['tgl_bayar'],
        'keterangan' => 'Refund Uang Muka Penjualan',
        'ref' => $id,
        'type' => 2003,
        'details' => $detail
      );
      $this->model_keuangan_jurnal->addJurnalUmum($jurnal);



}

  public function updatePermintaan($data,$where){
    if($data['status'] == 3){
      $pb=$this->getPermintaanPembelian(array(),array(),$where);

      $this->load->model('sale/customer');

      $this->model_sale_customer->updateDeposit($pb['customer_id'],$pb['nominal'],1);
      $hutang=array(
  			'ref'=> $pb['id'],
  			'date_trans'	=> $pb['tgl_bayar'],
  			'saldokeluar'	=> 0,
  			'saldomasuk'	=> $pb['nominal'],
  			'keterangan'	=> $this->db->escape("Pembatalan Refund Deposit Customer ".$data['keterangan']),
  			'hapus'	=> 0,
  			'customer_id'=> $pb['customer_id'],
  			'date_added'	=> date('Y-m-d H:i:s'),
  			'date_modified' => date('Y-m-d H:i:s')
  		);

      $this->model_sale_customer->addHistoryDeposit($hutang);

      //input mutasi
      $this->load->model('keuangan/bank');

      $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $pb['bank_id']));
  		$saldo=$b['saldo'] + $pb['nominal'] + $pb['biaya_bank'];
  		$this->model_keuangan_bank->editBank(array('saldo'  => $saldo),array('id'=> $pb['bank_id']));

  		$aruskas=array(
  			'date_added'	=> date('Y-m-d H:i:s'),
  			'date_trans'	=> $pb['tgl_bayar'],
  			'bank_id' => $pb['bank_id'],
  			'saldomasuk'  => $pb['nominal']+$pb['biaya_bank'],
  			'saldokeluar' => 0,
  			'saldoawal' => $b['saldo'],
  			'saldoakhir'  => $saldo,
  			'ref' => $pb['id'],
  			'keterangan'  => 'Pembatalan Refund Uang Muka Penjualan '.$data['customer_name'],
  			'type'  => 2003,
  			'ref_akun'  => '2401'
  		);

  		$this->model_keuangan_bank->addAruskas($aruskas);

      $ju=$this->db->first('jurnal_umum',array('ref'=>$pb['id'],'type'=>2003));
      if(!empty($ju)){
        $this->db->delete('jurnal_umum_detail',array('jurnal_id'=>$ju['id']));
        $this->db->delete('jurnal_umum',array('id'=>$ju['id']));
      }



      $this->db->update('refund_deposit',$data,$where);

    }


  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('refund_deposit',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('refund_deposit',$where);
	}
  public function totalDp($no_po){
    return $this->db->firstdetail('refund_deposit',array('COALESCE(SUM(nominal)) as total'),array(),array('order_id' => $no_po),array());
  }

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('refund_deposit',$column,$join,$where,array());
  }


}
?>
