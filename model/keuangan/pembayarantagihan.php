<?php
class ModelKeuanganPembayarantagihan extends Model {
  public function addPembelian($data){

    $this->load->model('keuangan/bank');
    $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$data['bank_id']));
    $saldo=$b['saldo'] - $data['nominal'];

    $this->model_keuangan_bank->updateBank(array('saldo' => $saldo),array('id'  => $data['bank_id']));
    $curb=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$data['bank_id']));
    $sal=$curb['saldo'];

    $this->load->model('keuangan/tagihanbiaya');
    $biaya=$this->model_keuangan_tagihanbiaya->getPermintaanPembelian(array(),array(),array('id'=>$data['order_id']));

    $p=array(
      'order_id' => $data['order_id'],
      'nominal'  => $data['nominal'],
      'tgl_bayar'  => $data['tgl_bayar'],
      'status'  => 1,
      'keterangan'  => $this->db->escape($data['keterangan']),
      'bank_id' => $data['bank_id'],
      'akun_hutang' => $biaya['akun_hutang'],
      'akun_kas'  =>$curb['rek_parent']
    );

    $this->db->insert('pembayaran_tagihan',$p);
    $id=$this->db->getLastId();

    $ak=array(
      'date_added' => date('Y-m-d H:i:s'),
      'date_trans'  => isset($data['tgl_bayar'])?$data['tgl_bayar']:date('Y-m-d H:i:s'),
      'bank_id' => $data['bank_id'],
      'saldomasuk'  => 0,
      'saldokeluar' => $data['nominal'],
      'saldoawal' => $b['saldo'],
      'saldoakhir'  => $sal,
      'ref' => $id,
      'keterangan'  => $this->db->escape($data['keterangan']),
      'type'  => 31,
      'date_modified' => date('Y-m-d H:i:s'),
      'ref_akun'  => $curb['rek_parent']
    );


    $idaruskas=$this->model_keuangan_bank->addAruskas($ak);

    //update total bayar 
    //$this->load->model('keuangan/tagihan');

    //$biaya=$this->model_keuangan_tagihanbiaya->getPermintaanPembelian(array(),array(),array('id'=>$data['order_id']));
    $totalbayar=$biaya['totalbayar'] + $data['nominal'];

    if($totalbayar == $biaya['total']){
      $status=3;
    }else{
      $status=2;
    }
    $this->model_keuangan_tagihanbiaya->updatePermintaan(array('status'=>$status,'totalbayar'=>$totalbayar),array('id'=>$data['order_id']));

    if($biaya['jenisbiaya'] == 2){
      $this->load->model('keuangan/iklanperiodik');
      $bp=$this->model_keuangan_iklanperiodik->getPermintaanPembelian(array(),array(),array('id'=>$biaya['ref']));

      /*$bpbayar=$bp['totalbayar']+$data['nominal'];
      if($bpbayar == $bp['total']){
        $status=3;
      }else{
        $status=2;
      }*/
      if(!empty($bp)){
        $this->model_keuangan_iklanperiodik->updateTotalBayar($biaya['ref'],$data['nominal'],1);
      }
    }
    if($biaya['jenisbiaya'] == 5){
      $this->load->model('catalog/aset');
      $bp=$this->model_catalog_aset->getPemeliharaan(array('id'=>$biaya['refaset']));

      /*$bpbayar=$bp['totalbayar']+$data['nominal'];
      if($bpbayar == $bp['total']){
        $status=3;
      }else{
        $status=2;
      }*/
      if(!empty($bp)){
        $this->model_catalog_aset->updateTotalBayar($biaya['refaset'],$data['nominal'],1);
      }
    }
    //jurnal
    $this->load->model('keuangan/jurnal');
    $jurnal=array();
    $detail=array();
    if($data['nominal'] > 0){
      $detail[]=array(
        'ref_akun'  => $biaya['akun_hutang'],
        'keterangan'  => $this->db->escape('Pembayaran Hutang'),
        'debet' => $data['nominal'],
        'kredit'  => 0,
        'urutan'  => 1,
      );
      if($b['saldo'] < $data['nominal']){
        if($b['hutangprk'] == 1){
          if($b['saldo'] > 0){
            $hutangprk=abs($b['saldo'] - $data['nominal']);
            $detail[]=array(
              'ref_akun'  => $curb['rek_parent'],
              'keterangan'  => $this->db->escape('Pembayaran Hutang'),
              'kredit' => $b['saldo'],
              'debet'  => 0,
              'urutan'  => 2,
            );
            $detail[]=array(
              'ref_akun'  => '2001',
              'keterangan'  => $this->db->escape('Pembayaran Hutang'),
              'kredit' => $hutangprk,
              'debet'  => 0,
              'urutan'  => 3,
            );
          }else{
            $hutangprk=$data['nominal'];
            $detail[]=array(
              'ref_akun'  => '2001',
              'keterangan'  => $this->db->escape('Pembayaran Hutang'),
              'kredit' => $hutangprk,
              'debet'  => 0,
              'urutan'  => 3,
            );
          }

          //jika saldo 500 hutang 700
          //hutang prk 200
          //jika saldo -200 hutang 700
          //hutang prk 700
        }else{
          $detail[]=array(
            'ref_akun'  => $curb['rek_parent'],
            'keterangan'  => $this->db->escape('Pembayaran Hutang Biaya iklan dan promosi'),
            'kredit' => $data['nominal'],
            'debet'  => 0,
            'urutan'  => 2,
          );
        }
      }else{
        $detail[]=array(
          'ref_akun'  => $curb['rek_parent'],
          'keterangan'  => $this->db->escape('Pembayaran Hutang Biaya iklan dan promosi'),
          'kredit' => $data['nominal'],
          'debet'  => 0,
          'urutan'  => 2,
        );
      }
    
      $jurnal=array(
        'tanggal' => $data['tgl_bayar'],
        'keterangan'  => 'Pembayaran Hutang  '.$biaya['keterangan'],
        'hapus' => 0,
        'ref' => $id,
        'type'  => 31,
		  'linkterkait' => empty($data['linkterkait'])?'-':$data['linkterkait'],
        'details'  => $detail
      );
      $idjurnal=$this->model_keuangan_jurnal->addJurnalUmum($jurnal);

      //dokumen
      $dokumen=array(
        'nama_table'  => 'pembayaran_tagihan',
        'jurnal_id' => $idjurnal,
        'hapus' => 0,
        'id_transaksi'  => $id,
        'datakas' => 2,
        'id_mutasi' => $idaruskas,
        'jenis_transaksi' => 'Pembayaran Tagihan Biaya',
        'date_added'  => date('Y-m-d H:i:s')

      );
      $this->db->insert('no_dokumen',$dokumen);
      $iddokumen=$this->db->getLastId();

      $no_dokumen='PTB-'.$iddokumen;
      $this->db->update('no_dokumen',array('no_dokumen'=>$no_dokumen),array('id'=>$iddokumen));
      $this->db->update('pembayaran_tagihan',array('no_dokumen'=>$no_dokumen),array('pembayaran_id'=>$id));
      $this->db->update('jurnal_umum',array('no_dokumen'=>$no_dokumen),array('id'=>$idjurnal));
      $this->db->update('aruskas',array('no_dokumen'=>$no_dokumen),array('id'=>$idaruskas));
    }
  }

  public function updatePermintaan($data,$where){
    if($data['status'] == 3){
      $pb=$this->getPermintaanPembelian(array(),array(),$where);

      $this->load->model('keuangan/bank');
      $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$pb['bank_id']));
      $saldo=$b['saldo'] + $pb['nominal'];
      //$sal=$this->model_keuangan_bank->updateSaldo($pb['bank_id'],$pb['nominal'],1);
      $this->model_keuangan_bank->updateBank(array('saldo' => $saldo),array('id'  => $pb['bank_id']));
      $cursal=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$pb['bank_id']));
      $sal=$cursal['saldo'];
      $this->db->delete('aruskas',array('type'=>31,'ref'=>$pb['pembayaran_id']));

      $this->load->model('keuangan/jurnal');
      $ju=$this->model_keuangan_jurnal->getJurnalUmum(array('ref'=>$pb['pembayaran_id'],'type'=>31));

      $this->load->model('keuangan/tagihanbiaya');

      $biaya=$this->model_keuangan_tagihanbiaya->getPermintaanPembelian(array(),array(),array('id'=>$pb['order_id']));
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
      $this->model_keuangan_tagihanbiaya->updatePermintaan(array('status'=>$status,'totalbayar'=>$totalbayar),array('id'=>$pb['order_id']));

      if($biaya['jenisbiaya'] == 2){
        $this->load->model('keuangan/iklanperiodik');
        $bp=$this->model_keuangan_iklanperiodik->getPermintaanPembelian(array(),array(),array('id'=>$biaya['ref']));

        /*$bpbayar=$bp['totalbayar']+$data['nominal'];
        if($bpbayar == $bp['total']){
          $status=3;
        }else{
          $status=2;
        }*/
        if(!empty($bp)){
          $this->model_keuangan_iklanperiodik->updateTotalBayar($biaya['ref'],$pb['nominal'],2);
        }
      }
      if($biaya['jenisbiaya'] == 5){
        $this->load->model('catalog/aset');
        $bp=$this->model_catalog_aset->getPemeliharaan(array('id'=>$biaya['refaset']));

        /*$bpbayar=$bp['totalbayar']+$data['nominal'];
        if($bpbayar == $bp['total']){
          $status=3;
        }else{
          $status=2;
        }*/
        if(!empty($bp)){
          $this->model_catalog_aset->updateTotalBayar($biaya['refaset'],$pb['nominal'],2);
        }
      }
      if(!empty($ju['id'])){
        $this->db->delete('jurnal_umum_detail',array('jurnal_id' => $ju['id']));
        $this->db->delete('jurnal_umum',array('id'=>$ju['id']));
        $this->db->delete('no_dokumen',array('jurnal_id'=>$ju['id']));
      }

    }
    $this->db->update('pembayaran_tagihan',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('pembayaran_tagihan',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('pembayaran_tagihan',$where);
	}
  public function totalDp($no_po){
    return $this->db->firstdetail('pembayaran_tagihan',array('COALESCE(SUM(nominal)) as total'),array(),array('order_id' => $no_po),array());
  }

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('pembayaran_tagihan',$column,$join,$where,array());
  }


}
?>
