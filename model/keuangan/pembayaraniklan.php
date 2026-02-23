<?php
class ModelKeuanganPembayaraniklan extends Model {
  public function addPembelian($data){
    $p=array(
      'order_id' => $data['order_id'],
      'nominal'  => $data['nominal'],
      'tgl_bayar'  => $data['tgl_bayar'],
      'status'  => 1,
      'keterangan'  => $this->db->escape($data['keterangan']),
      'bank_id' => $data['bank_id']
    );

    $this->db->insert('pembayaran_iklan',$p);
    $id=$this->db->getLastId();

    $this->load->model('keuangan/bank');
    $b=$this->model_keuangan_bank->getBank($data['bank_id']);
    $saldo=$b['saldo'] - $data['nominal'];

    $sal=$this->model_keuangan_bank->updateSaldo($data['bank_id'],$data['nominal'],2);
    $curb=$this->model_keuangan_bank->getBank($data['bank_id']);

    $aruskas=array(
      'tgl_transaksi' => $data['tgl_bayar'],
			'bank_id'	=>$data['bank_id'],
			'saldo_masuk'	=> 0,
			'saldo_keluar'	=> $data['nominal'],
			'type'	=> 31,
			'ref'	=>$id,
			'keterangan'	=> $this->db->escape($data['keterangan']),
			'saldo_awal'	=> $b['saldo'],
			'saldo_akhir'	=> $sal
    );

    $this->model_keuangan_bank->addSaldo($aruskas);

    //update total bayar
    $this->load->model('keuangan/biayaiklan');

    $biaya=$this->model_keuangan_biayaiklan->getPermintaanPembelian(array(),array(),array('id'=>$data['order_id']));
    $totalbayar=$biaya['totalbayar'] + $data['nominal'];

    if($totalbayar == $biaya['total']){
      $status=3;
    }else{
      $status=2;
    }
    $this->model_keuangan_biayaiklan->updatePermintaan(array('status'=>$status,'totalbayar'=>$totalbayar),array('id'=>$data['order_id']));

    if($biaya['jenis'] == 2){
      $this->load->model('keuangan/iklanperiodik');

      //$sewa=$this->model_pamerantoko_sewatoko->getPermintaanPembelian(array(),array(),array('id'=>$biaya['ref']));
      //$totalbayarsewa=$sewa['totalbayar'] + $data['nominal'];
      if($status == 3 & $biaya['statuspajak'] == 1){
        $data['nominal']=$data['nominal'] + $biaya['nilaipajak'];
      }
      /*if($totalbayar == $sewa['total']){
        $status=3;
      }else{
        $status=2;
      }*/
      //$this->model_pamerantoko_sewatoko->updatePermintaan(array('status'=>$status,'totalbayar'=>$totalbayar),array('id'=>$biaya['ref']));
      $this->model_keuangan_iklanperiodik->updateTotalBayar($biaya['ref'],$data['nominal'],1);
    }
    //jurnal
    $this->load->model('keuangan/jurnal');
    $jurnal=array();
    $detail=array();
    if($data['nominal'] > 0){
      $detail[]=array(
        'ref_akun'  => '2201',
        'keterangan'  => $this->db->escape('Pembayaran Hutang Biaya iklan dan promosi'),
        'debet' => $data['nominal'],
        'kredit'  => 0,
        'urutan'  => 1,
      );


      $detail[]=array(
        'ref_akun'  => $curb['kode_rek'],
        'keterangan'  => $this->db->escape('Pembayaran Hutang Biaya iklan dan promosi'),
        'kredit' => $data['nominal'],
        'debet'  => 0,
        'urutan'  => 2,
      );


      $jurnal=array(
        'tanggal' => $data['tgl_bayar'],
        'keterangan'  => 'Pembayaran Hutang Biaya iklan dan promosi '.$biaya['keterangan'],
        'hapus' => 0,
        'ref' => $id,
        'type'  => 31,
        'details'  => $detail
      );
      $this->model_keuangan_jurnal->addJurnalUmum($jurnal);
    }
  }

  public function updatePermintaan($data,$where){
    if($data['status'] == 3){
      $pb=$this->getPermintaanPembelian(array(),array(),$where);

      $this->load->model('keuangan/bank');
      $b=$this->model_keuangan_bank->getBank($pb['bank_id']);
      //$saldo=$b['saldo'] + $pb['jumlah'];
      $sal=$this->model_keuangan_bank->updateSaldo($pb['bank_id'],$pb['nominal'],1);
      $this->db->delete('bank_saldo',array('type'=>31,'ref'=>$pb['pembayaran_id']));

      $this->load->model('keuangan/jurnal');
      $ju=$this->model_keuangan_jurnal->getJurnalUmum(array('ref'=>$pb['pembayaran_id'],'type'=>31));

      $this->load->model('keuangan/biayaiklan');

      $biaya=$this->model_keuangan_biayaiklan->getPermintaanPembelian(array(),array(),array('id'=>$pb['order_id']));
      $totalbayar=$biaya['totalbayar'] - $pb['nominal'];
      if($totalbayar < 0){
        $totalbayar=0;
      }

      if($totalbayar == $biaya['total']){
        $status=3;
      }else if($totalbayar == 0){
        $status =1;
      }else{
        $status=2;
      }
      $this->model_keuangan_biayaiklan->updatePermintaan(array('status'=>$status,'totalbayar'=>$totalbayar),array('id'=>$pb['order_id']));

      if($biaya['jenis'] == 2){
        $this->load->model('keuangan/iklanperiodik');

      /*  $sewa=$this->model_pamerantoko_sewatoko->getPermintaanPembelian(array(),array(),array('id'=>$biaya['ref']));
        $totalbayarsewa=$sewa['totalbayar'] - $pb['nominal'];

        if($totalbayar == $sewa['total']){
          $status=3;
        }else{
          $status=2;
        }*/

        //$this->model_pamerantoko_sewatoko->updatePermintaan(array('status'=>$status,'totalbayar'=>$totalbayar),array('id'=>$biaya['ref']));
        if($biaya['status'] == 3 & $biaya['statuspajak'] == 1){
          $pb['nominal']=$pb['nominal'] + $biaya['nilaipajak'];
        }
        /*if($totalbayar == $sewa['total']){
          $status=3;
        }else{
          $status=2;
        }*/
        //$this->model_pamerantoko_sewatoko->updatePermintaan(array('status'=>$status,'totalbayar'=>$totalbayar),array('id'=>$biaya['ref']));
        $this->model_keuangan_iklanperiodik->updateTotalBayar($biaya['ref'],$pb['nominal'],2);
      }
      if(!empty($ju['id'])){
        $this->db->delete('jurnal_umum_detail',array('jurnal_id' => $ju['id']));
        $this->db->delete('jurnal_umum',array('id'=>$ju['id']));
      }

    }
    $this->db->update('pembayaran_iklan',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('pembayaran_iklan',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('pembayaran_iklan',$where);
	}
  public function totalDp($no_po){
    return $this->db->firstdetail('pembayaran_iklan',array('COALESCE(SUM(nominal)) as total'),array(),array('order_id' => $no_po),array());
  }

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('pembayaran_iklan',$column,$join,$where,array());
  }


}
?>
