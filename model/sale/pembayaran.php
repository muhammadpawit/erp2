<?php
class ModelSalePembayaranpenjualan extends Model {
  public function addPembelian($data){
    $pajak=0.1*$data['jumlah'];
    $p=array(
      'penjualan_id' => $data['penjualan_id'],
      'jumlah'  => $data['jumlah']+$pajak,
      'date_added'  => date('Y-m-d H:i:s',time()),
      'date_modified' => date('Y-m-d H:i:s',time()),
      'status'  => 1,
      'hapus' => 0,
      'bank_id' => $data['bank_id']
    );

    $this->db->insert('pembayaran_penjualan',$p);
    $id=$this->db->getLastId();


    $this->load->model('sale/penjualan');
    $pb=$this->model_sale_penjualan->getPenjualan(array('id'=>$data['penjualan_id']));
    //jurnal umum
    $this->load->model('keuangan/jurnal');
    $detail=array();
    //debet
    if($pb['status_pegiriman'] == 1){
      $pajak=0.1*$data['jumlah'];
      $this->load->model('keuangan/bank');
      $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $data['bank_id']));
      $saldo=$b['saldo'] + $data['jumlah'] + $pajak;
      $this->model_keuangan_bank->updateBank(array('saldo'  => $saldo),array('id'=> $data['bank_id']));

      $aruskas=array(
        'date_added'  => $p['date_added'],
        'bank_id' => $data['bank_id'],
        'saldomasuk'  => $data['jumlah']+$pajak,
        'saldokeluar' => 0,
        'saldoawal' => $b['saldo'],
        'saldoakhir'  => $saldo,
        'ref' => $id,
        'keterangan'  => 'Uang Muka Penjualan',
        'type'  => 4,
        'ref_akun'  => '21.05.02'
      );

      $this->model_keuangan_bank->addAruskas($aruskas);

      $detail[]=array(
        'ref_akun'  =>$b['rek_parent'],
        'debet' => $data['jumlah'] + $pajak,
        'kredit'  => 0,
        'urutan'  =>1,
        'keterangan'  => 'Kas/Bank'
      );
      $detail[]=array(
        'ref_akun'  =>'21.05.02',
        'debet' => 0,
        'kredit'  => $data['jumlah'],
        'urutan'  =>2,
        'keterangan'  => 'Uang Muka Penjualan'
      );


    //  if($pb['pajak'] > 0){
    $this->load->model('keuangan/pajak');
        $details[]=array(
          'ref_akun'  =>'21.06.05',
          'debet' => 0,
          'kredit'  => $pajak,
          'urutan'  =>3,
          'keterangan'  => 'PPN Keluaran'
        );

        $pajak=array(
          'ref' => $data['id'],
          'jumlah'  => $pb['pajak'],
          'akun' => '21.06.05',
          'jenis' => 2
        );
        $this->model_keuangan_pajak->addPajak($pajak);
      //}

      $jurnal=array(
        'tanggal' => date('Y-m-d'),
        'keterangan' => 'Uang Muka Penjualan',
        'ref' => $id,
        'type' => 1,
        'details' => $detail
      );
      $this->model_keuangan_jurnal->addJurnalUmum($jurnal);
    }else{
      if($pb['status'] == 1){
        $this->load->model('keuangan/bank');
        $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $data['bank_id']));
        $pajak=0.1*$data['jumlah'];
        $saldo=$b['saldo'] + $data['jumlah'] + $pajak;
        $this->model_keuangan_bank->updateBank(array('saldo'  => $saldo),array('id'=> $data['bank_id']));

        $aruskas=array(
          'date_added'  => $p['date_added'],
          'bank_id' => $data['bank_id'],
          'saldomasuk'  => $data['jumlah']+$pajak,
          'saldokeluar' =>0,
          'saldoawal' => $b['saldo'],
          'saldoakhir'  => $saldo,
          'ref' => $id,
          'keterangan'  => 'Piutang',
          'type'  => 4,
          'ref_akun'  => '11.03.01'
        );

        $this->model_keuangan_bank->addAruskas($aruskas);

        $this->load->model('sale/customer');
        //$penj=$this->model_sale_customer->updatePenjualan($data['customer_id'],$data['total'],1);

        //cek pembayaran
        $piutang=$this->model_sale_customer->updatePiutang($pb['customer_id'],$data['jumlah']+$pajak,2);
        $detpiutang=$this->model_sale_customer->updatePiutang($pb['id'],$data['jumlah']+$pajak,2);


        $this->load->model('keuangan/jurnal');
        $detail=array();
        //debet
        $detail[]=array(
          'ref_akun'  =>$b['rek_parent'],
          'debet' => $data['jumlah'] + $pajak,
          'kredit'  => 0,
          'urutan'  =>1,
          'keterangan'  => 'Kas/Bank'
        );
        $detail[]=array(
          'ref_akun'  =>'11.03.01',
          'debet' => 0,
          'kredit'  => $data['jumlah']+$pajak,
          'urutan'  =>2,
          'keterangan'  => 'Piutang Usaha'
        );

        $jurnal=array(
          'tanggal' => date('Y-m-d'),
          'keterangan' => 'Pembayaran piutang',
          'ref' => $id,
          'type' => 1,
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
      $saldo=$b['saldo'] - $pb['jumlah'];
      $this->model_keuangan_bank->updateBank(array('saldo'  => $saldo),array('id'=> $pb['bank_id']));

      $this->model_keuangan_bank->updateAruskas(array('hapus' => 1),array('type'  => 4,'ref'  => $pb['id']));

      $this->load->model('keuangan/jurnal');
      $this->model_keuangan_jurnal->updateJurnalumum(array('hapus' => 1),array('type'  => 4,'ref'  => $pb['id']));

    }
    $this->db->update('pembayaran_penjualan',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('pembayaran_penjualan',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('pembayaran_penjualan',$where);
	}
  public function totalDp($no_po){
    return $this->db->firstdetail('pembayaran_penjualan',array('COALESCE(SUM(jumlah)) as total'),array(),array('no_po' => $no_po,'hapus' => array('<',1)),array());
  }

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('pembayaran_penjualan',$column,$join,$where,array());
  }


}
?>
