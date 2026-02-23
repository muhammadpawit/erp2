<?php
class ModelPembelianPembayarandpimport extends Model {
  public function addPembelian($data){
    $p=array(
      'no_po' => $this->db->escape($data['no_po']),
      'jumlah'  => $data['jumlah'],
      'date_added'  => date('Y-m-d H:i:s',time()),
      'date_modified' => date('Y-m-d H:i:s',time()),
      'status'  => 1,
      'hapus' => 0,
      'bank_id' => $data['bank_id'],
      'kursbank' => empty($data['kursbank'])?0:$data['kursbank'],
      'biaya_bank' => empty($data['biaya_bank'])?0:$data['biaya_bank'],
      'currency' => empty($data['currency'])?1:$data['currency'],
      'kursbi' => empty($data['kursbi'])?0:$data['kursbi'],
      'kurskmk' => empty($data['kurskmk'])?0:$data['kurskmk'],
      'penyesuaian' => 0
    );

    $this->db->insert('pembayaran_import',$p);
    $id=$this->db->getLastId();


    $this->load->model('pembelian/pembelianimport');
    $pb=$this->model_pembelian_pembelianimport->getPermintaanPembelian(array(),array(),array('no_po'  => $data['no_po']));
    //jurnal umum
    $this->load->model('keuangan/jurnal');
    $detail=array();
    //debet
    if($pb['status'] == 0){
      $this->load->model('keuangan/bank');
      $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $data['bank_id']));
      $saldo=$b['saldo'] - $data['jumlah'];
      $this->model_keuangan_bank->updateBank(array('saldo'  => $saldo),array('id'=> $data['bank_id']));

      $aruskas=array(
        'date_added'  => $p['date_added'],
        'bank_id' => $data['bank_id'],
        'saldomasuk'  => 0,
        'saldokeluar' => $data['jumlah'],
        'saldoawal' => $b['saldo'],
        'saldoakhir'  => $saldo,
        'ref' => $id,
        'keterangan'  => 'Uang Muka pembelian import',
        'type'  => 8,
        'ref_akun'  => '1311'
      );

      $this->model_keuangan_bank->addAruskas($aruskas);
      if($pb['jenis_aktiva'] > 0){
        $detail[]=array(
          'ref_akun'  =>'1631',
          'debet' => $data['jumlah']*$data['kursbank'],
          'kredit'  => 0,
          'urutan'  =>1,
          'keterangan'  => 'Uang Muka Pembelian Aktiva Tetap'
        );
        $detail[]=array(
          'ref_akun'  =>$b['rek_parent'],
          'debet' => 0,
          'kredit'  => $data['jumlah']*$data['kursbank'],
          'urutan'  =>2,
          'keterangan'  => 'Kas/Bank'
        );

        $jurnal=array(
          'tanggal' => date('Y-m-d'),
          'keterangan' => 'Uang Muka Pembelian',
          'ref' => $id,
          'type' => 1,
          'details' => $detail
        );
        $this->model_keuangan_jurnal->addJurnalUmum($jurnal);
      }else{
        $detail[]=array(
          'ref_akun'  =>'1311',
          'debet' => $data['jumlah']*$data['kursbank'],
          'kredit'  => 0,
          'urutan'  =>1,
          'keterangan'  => 'Uang Muka Pembelian Persediaan'
        );
        $detail[]=array(
          'ref_akun'  =>$b['rek_parent'],
          'debet' => 0,
          'kredit'  => $data['jumlah']*$data['kursbank'],
          'urutan'  =>2,
          'keterangan'  => 'Kas/Bank'
        );

        $jurnal=array(
          'tanggal' => date('Y-m-d'),
          'keterangan' => 'Uang Muka Pembelian',
          'ref' => $id,
          'type' => 1,
          'details' => $detail
        );
        $this->model_keuangan_jurnal->addJurnalUmum($jurnal);
      }
    }else{
      if($pb['status'] == 1){
        $this->load->model('keuangan/bank');
        $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $data['bank_id']));
        $saldo=$b['saldo'] - $data['jumlah'];
        $this->model_keuangan_bank->updateBank(array('saldo'  => $saldo),array('id'=> $data['bank_id']));

        $aruskas=array(
          'date_added'  => $p['date_added'],
          'bank_id' => $data['bank_id'],
          'saldomasuk'  => 0,
          'saldokeluar' => $data['jumlah'],
          'saldoawal' => $b['saldo'],
          'saldoakhir'  => $saldo,
          'ref' => $id,
          'keterangan'  => 'Pembayaran hutang dagang',
          'type'  => 4,
          'ref_akun'  => '2101'
        );

        $this->model_keuangan_bank->addAruskas($aruskas);

        $this->load->model('catalog/vendorimport');
        $v=$this->model_catalog_vendorimport->getVendor(array('id' => $pb['vendor_id']));

        //$hutangv=$v['hutang']+$hutang;
        $this->model_catalog_vendorimport->updateDetailHutang($pb['id'],$data['jumlah'],2);

        $this->load->model('keuangan/jurnal');
        $detail=array();
        //debet
        $detail[]=array(
          'ref_akun'  =>'2101',
          'debet' => $data['jumlah']*$data['kursbank'],
          'kredit'  => 0,
          'urutan'  =>1,
          'keterangan'  => 'Pembayaran hutang'
        );

        $detail[]=array(
          'ref_akun'  =>$b['rek_parent'],
          'debet' => 0,
          'kredit'  => $data['jumlah']*$data['kursbank'],
          'urutan'  =>2,
          'keterangan'  => 'Kas'
        );

        $jurnal=array(
          'tanggal' => date('Y-m-d'),
          'keterangan' => 'Pembayaran hutang',
          'ref' => $id,
          'type' => 2,
          'details' => $detail
        );
        $this->model_keuangan_jurnal->addJurnalUmum($jurnal);

      }
    }


  }

  public function updatePermintaan($data,$where){
    if($data['status'] == 3){
      $pb=$this->getPermintaanPembelian(array(),array(),$where);

      $this->load->model('keuangan/bank');
      $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $pb['bank_id']));
      $saldo=$b['saldo'] + $pb['jumlah'];
      $this->model_keuangan_bank->updateBank(array('saldo'  => $saldo),array('id'=> $pb['bank_id']));

      $this->model_keuangan_bank->updateAruskas(array('hapus' => 1),array('type'  => 8,'ref'  => $pb['id']));

      $this->load->model('keuangan/jurnal');
      $this->model_keuangan_jurnal->updateJurnalumum(array('hapus' => 1),array('type'  => 2,'ref'  => $pb['id']));

    }
    $this->db->update('pembayaran_import',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('pembayaran_import',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('pembayaran_import',$where);
	}
  public function totalDp($no_po){
    return $this->db->firstdetail('pembayaran_import',array('COALESCE(SUM(jumlah)) as total'),array(),array('no_po' => $no_po,'hapus' => array('<',1)),array());
  }

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('pembayaran_import',$column,$join,$where,array());
  }

  public function penyesuaianKurs($data){
    //history
    $this->db->insert('penyesuaian_kurs',$data);

    $this->load->model('keuangan/jurnal');
    //list pembelian pada bulan yang akan disesuaikan
    $this->load->model('keuangan/bank');
    $this->load->model('pembelian/pembelianimport');

    $pembayaran=$this->getPermintaanPembelians(array(),array(),array('status'=>1,'date_added'=>array('>=',$data['tglawal']),'date_added'=>array('<=',$data['tglakhir']),'penyesuaian'=>array('<>',1)),array(),0,null);
    foreach($pembayaran as $p){
      $this->db->update('pembayaran_import',array('kursbi'=>$data['kursbi'],'kurskmk'=>$data['kurskmk'],'penyesuaian'=>1),array('id'=>$p['id']));

      //hitung selisih
      $kursbank=$p['kursbank']*$p['jumlah'];
      $kursbi=$p['kursbi']*$p['jumlah'];
      $selisih=$kursbank-$kursbi;

      $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $p['bank_id']));

      $pb=$this->model_pembelian_pembelianimport->getPermintaanPembelian(array(),array(),array('no_po'  => $p['no_po']));

      $details=array();
      if($pb['status'] == 0){
        if($selisih > 0){
          $details[]=array(
            'ref_akun'  => "30.02.02",
            'keterangan'  => "Rugi selisih kurs",
            'debet' => abs($selisih),
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );
          $details[]=array(
            'ref_akun'  => "11.07.01",
            'keterangan'  => "Uang muka pembelian",
            'debet' => 0,
            'kredit'  => abs($selisih),
            'urutan'  => 1,
            'hapus' => 0
          );
        }
        if($selisih < 0){
          $details[]=array(
            'ref_akun'  => "11.07.01",
            'keterangan'  => "Uang Muka Pembelian",
            'debet' => abs($selisih),
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );
          $details[]=array(
            'ref_akun'  => "30.02.02",
            'keterangan'  => "Rugi selisih kurs",
            'debet' => 0,
            'kredit'  => abs($selisih),
            'urutan'  => 1,
            'hapus' => 0
          );
        }
        $j=array(
          'tanggal' => date('Y-m-d'),
          'keterangan'  => 'Laba/Rugi Selisih Kurs '.$p['no_po'],
          'details' => $details,
          'hapus' =>0,
          'ref' => $p['id'],
          'type'  => 1
        );
      }else{
        if($selisih > 0){
          $details[]=array(
            'ref_akun'  => "30.02.02",
            'keterangan'  => "Rugi selisih kurs",
            'debet' => abs($selisih),
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );
          $details[]=array(
            'ref_akun'  => "21.02.01",
            'keterangan'  => "Pembayaran Hutang",
            'debet' => 0,
            'kredit'  => abs($selisih),
            'urutan'  => 1,
            'hapus' => 0
          );
        }
        if($selisih < 0){
          $details[]=array(
            'ref_akun'  => "21.02.01",
            'keterangan'  => "Pembayaran Hutang",
            'debet' => abs($selisih),
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );
          $details[]=array(
            'ref_akun'  => "30.02.02",
            'keterangan'  => "Rugi selisih kurs",
            'debet' => 0,
            'kredit'  => abs($selisih),
            'urutan'  => 1,
            'hapus' => 0
          );
        }
        $j=array(
          'tanggal' => date('Y-m-d'),
          'keterangan'  => 'Laba/Rugi Selisih Kurs '.$p['no_po'],
          'details' => $details,
          'hapus' =>0,
          'ref' => $p['id'],
          'type'  => 2
        );
      }

      $this->model_keuangan_jurnal->addJurnalUmum($j);
    }
  }
}
?>
