<?php
class ModelPembelianPembayarandp extends Model {
  public function addPembelian($data){
    $p=array(
      'no_po' => $this->db->escape($data['no_po']),
      'jumlah'  => $data['jumlah'],
      'date_added'  => date('Y-m-d H:i:s',time()),
      'date_modified' => date('Y-m-d H:i:s',time()),
      'status'  => 1,
      'hapus' => 0,
      'bank_id' => $data['bank_id']
    );

    $this->db->insert('pembayaran_dp',$p);
    $id=$this->db->getLastId();


    $this->load->model('pembelian/pembeliankredit');
    $pb=$this->model_pembelian_pembeliankredit->getPermintaanPembelian(array(),array(),array('no_po'  => $data['no_po']));
    //jurnal umum
    $this->load->model('keuangan/jurnal');
    $detail=array();
    //debet
    if($pb['status'] == 0){
      $uangmuka=$pb['uangmuka']+$data['jumlah'];
      $this->db->update('pembelian_kredit',array('uangmuka'  => $uangmuka),array('id' => $pb['id']));

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
        'keterangan'  => 'Uang Muka pembelian kredit',
        'type'  => 3,
        'ref_akun'  => '1311'
      );

      $this->model_keuangan_bank->addAruskas($aruskas);
      if($pb['jenis_aktiva'] > 0){
        $detail[]=array(
          'ref_akun'  =>'12.04.01',
          'debet' => $data['jumlah'],
          'kredit'  => 0,
          'urutan'  =>1,
          'keterangan'  => 'Uang Muka Pembelian Aktiva Tetap'
        );
      }else{
        $detail[]=array(
          'ref_akun'  =>'1311',
          'debet' => $data['jumlah'],
          'kredit'  => 0,
          'urutan'  =>1,
          'keterangan'  => 'Uang Muka Pembelian Persediaan'
        );
        $detail[]=array(
          'ref_akun'  =>$b['rek_parent'],
          'debet' => 0,
          'kredit'  => $data['jumlah'],
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

        $this->load->model('catalog/vendorlokal');
        $v=$this->model_catalog_vendorlokal->getVendor(array('id' => $pb['vendor_id']));

        //$hutangv=$v['hutang']+$hutang;
        $this->model_catalog_vendorlokal->updateDetailHutang($pb['id'],$data['jumlah'],2);

        $this->load->model('keuangan/jurnal');
        $detail=array();
        //debet
        $detail[]=array(
          'ref_akun'  =>'2101',
          'debet' => $data['jumlah'],
          'kredit'  => 0,
          'urutan'  =>1,
          'keterangan'  => 'Pembayaran hutang'
        );

        $detail[]=array(
          'ref_akun'  =>$b['rek_parent'],
          'debet' => 0,
          'kredit'  => $data['jumlah'],
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
      $this->load->model('pembelian/pembeliankredit');
      $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $pb['bank_id']));
      $saldo=$b['saldo'] + $pb['jumlah'];
      $this->model_keuangan_bank->updateBank(array('saldo'  => $saldo),array('id'=> $pb['bank_id']));

      $this->model_keuangan_bank->updateAruskas(array('hapus' => 1),array('type'  => 3,'ref'  => $pb['id']));

      $this->load->model('keuangan/jurnal');
      $this->model_keuangan_jurnal->updateJurnalumum(array('hapus' => 1),array('type'  => 2,'ref'  => $pb['id']));

      $pembelian=$this->model_pembelian_pembeliankredit->getPermintaanPembelian(array(),array(),array('no_po'=>$pb['no_po']));
      $uangmuka=$pembelian['uangmuka'] - $pb['jumlah'];
      $this->db->update('pembelian_kredit',array('uangmuka'=>$uangmuka),array('id'=>$pembelian['id']));

    }
    $this->db->update('pembayaran_dp',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('pembayaran_dp',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('pembayaran_dp',$where);
	}
  public function totalDp($no_po){
    return $this->db->firstdetail('pembayaran_dp',array('COALESCE(SUM(jumlah)) as total'),array(),array('no_po' => $no_po,'hapus' => array('<',1)),array());
  }

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('pembayaran_dp',$column,$join,$where,array());
  }


}
?>
