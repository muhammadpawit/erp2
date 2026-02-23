<?php
class ModelKeuanganBiayaiklan extends Model {
  public function addPembelian($data){
    /*
    Status
    1 belum dibayar
    2 dibayar sebagian
    3 lunas
    4 dibatalkan
    */
    if(empty($data['nilaipajak'])){
      $data['nilaipajak']=0;
    }
    if(empty($data['nominal'])){
      $data['nominal']=0;
    }
    if(empty($data['tgl_tagihan'])){
      $data['tgl_tagihan']=date('Y-m-d');
    }
    if(empty($data['jatuhtempo'])){
      $data['jatuhtempo']=date('Y-m-d');
    }
    $total=$data['nominal']+$data['nilaipajak'];

    $p=array(
      'date_added' => date('Y-m-d H:i:s'),
      'nominal'  => $data['nominal'],
      'no_faktur'  => $data['no_faktur'],
      'pajak'  => $data['pajak'],
      'nilaipajak'  => $data['nilaipajak'],
      'tgl_tagihan'  => $data['tgl_tagihan'],
      'jatuhtempo'  => $data['jatuhtempo'],
      'total'  => $total,
      'totalbayar'  => 0,
      'status'  => 1,
      'keterangan'  => $this->db->escape($data['keterangan']),
      'user_id' => $this->user->getId()
    );

    $this->db->insert('biaya_listrik',$p);
    $id=$this->db->getLastId();

  /*  $this->load->model('keuangan/coa');
    $coa=$this->model_keuangan_coa->getCategory($data['coa_id']);

    $this->load->model('keuangan/bank');

    $b=$this->model_keuangan_bank->getBank($data['bank_id']);
    $saldo=$b['saldo'] - $data['nominal'];
    $coabank=$this->model_keuangan_coa->getCategory($b['kode_rek']);

    $sal=$this->model_keuangan_bank->updateSaldo($data['bank_id'],$data['nominal'],2);
    $curb=$this->model_keuangan_bank->getBank($data['bank_id']);

    $aruskas=array(
      'tgl_transaksi' => $data['tgl_bayar'],
			'bank_id'	=>$data['bank_id'],
			'saldo_masuk'	=> 0,
			'saldo_keluar'	=> $data['nominal'],
			'type'	=> 28,
			'ref'	=>$id,
			'keterangan'	=> $this->db->escape($data['keterangan']),
			'saldo_awal'	=> $b['saldo'],
			'saldo_akhir'	=> $saldo
    );

    $this->model_keuangan_bank->addSaldo($aruskas);
    $id=$this->db->getLastId();*/

    $this->load->model('keuangan/jurnal');
    //jurnal
    $jurnal=array();
    $detail=array();
    if($data['nominal'] > 0){
      $detail[]=array(
        'ref_akun'  => $data['ref_akun'],
        'keterangan'  => $this->db->escape('Biaya listrik, telepon, dan air'),
        'debet' => $total,
        'kredit'  => 0,
        'urutan'  => 1,
      );


      $detail[]=array(
        'ref_akun'  => '2201',
        'keterangan'  => $this->db->escape('Hutang Lain-lain'),
        'kredit' => $data['nominal'],
        'debet'  => 0,
        'urutan'  => 2,
      );
      if($data['nilaipajak'] > 0){
        if($data['pajak'] == 1){
          $refpajak=2501;
        }
        if($data['pajak'] == 2){
          $refpajak=2502;
        }
        if($data['pajak'] == 3){
          $refpajak=2503;
        }
        if($data['pajak'] == 4){
          $refpajak=2504;
        }
        if($data['pajak'] == 5){
          $refpajak=2505;
        }

        $detail[]=array(
          'ref_akun'  => $refpajak,
          'keterangan'  => $this->db->escape('Hutang Pajak'),
          'kredit' => $data['nilaipajak'],
          'debet'  => 0,
          'urutan'  => 3,
        );
      }

      $jurnal=array(
        'tanggal' => $data['tgl_tagihan'],
        'keterangan'  => $data['keterangan'],
        'hapus' => 0,
        'ref' => $id,
        'type'  => 30,
        'details'  => $detail
      );
      $this->model_keuangan_jurnal->addJurnalUmum($jurnal);
    }


  }

  public function updatePermintaan($data,$where){
    if($data['status'] == 4){
      $pb=$this->getPermintaanPembelian(array(),array(),$where);
      if($pb['status'] == 1){
        //$this->db->delete('jurnal_umum',array('type'=>30,'ref'=>$pb['id']));
        $this->load->model('keuangan/jurnal');
        $ju=$this->model_keuangan_jurnal->getJurnalUmum(array('ref'=>$pb['id'],'type'=>30));
        if(!empty($ju['id'])){
          $this->db->delete('jurnal_umum_detail',array('jurnal_id' => $ju['id']));
          $this->db->delete('jurnal_umum',array('id'=>$ju['id']));
        }
      }

    }
    $this->db->update('biaya_listrik',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('biaya_listrik',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('biaya_listrik',$where);
	}
  public function totalDp($no_po){
    return $this->db->firstdetail('biaya_listrik',array('COALESCE(SUM(total)) as total'),array(),array('id' => $no_po),array());
  }

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('biaya_listrik',$column,$join,$where,array());
  }


}
?>
