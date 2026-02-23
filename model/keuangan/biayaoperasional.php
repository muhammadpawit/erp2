<?php
class ModelKeuanganBiayaoperasional extends Model {
  public function addPembelian($data){
    $p=array(
      'date_added' => date('Y-m-d H:i:s'),
      'nominal'  => $data['nominal'],
      'tgl_bayar'  => $data['tgl_bayar'],
      'status'  => 1,
      'keterangan'  => $this->db->escape($data['keterangan']),
      'bank_id' => $data['bank_id'],
      'coa_id'  => $data['coa_id'],
      'no_faktur'  => $data['no_faktur'],
      'user_id' => $this->user->getId()
    );

    $this->db->insert('biaya_operasional',$p);
    $id=$this->db->getLastId();

    $this->load->model('keuangan/coa');
    $coa=$this->model_keuangan_coa->getCategory($data['coa_id']);

    $this->load->model('keuangan/bank');

    $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$data['bank_id']));
    $saldo=$b['saldo'] - $data['nominal'];
    $coabank=$this->model_keuangan_coa->getCategory($b['rek_parent']);

    $sal=$this->model_keuangan_bank->updateBank(array('saldo' => $saldo),array('id'  => $data['bank_id']));
    $curb=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$data['bank_id']));



    $aruskas=array(
      'date_added' => $data['tgl_bayar'],
      'date_trans'  => $data['tgl_bayar'],
			'bank_id'	=>$data['bank_id'],
			'saldomasuk'	=> 0,
			'saldokeluar'	=> $data['nominal'],
      'saldoawal' => $b['saldo'],
      'saldoakhir'  => $saldo,
			'type'	=> 28,
			'ref'	=>$id,
			'keterangan'	=> $this->db->escape($data['keterangan']),
			'ref_akun' => ''
    );

    $this->model_keuangan_bank->addAruskas($aruskas);
    $id=$this->db->getLastId();

    $this->load->model('keuangan/jurnal');
    //jurnal
    $jurnal=array();
    $detail=array();
    if($data['nominal'] > 0){
      $detail[]=array(
        'ref_akun'  => $coa['kode_rek'],
        'keterangan'  => $this->db->escape($coa['name']),
        'debet' => $data['nominal'],
        'kredit'  => 0,
        'urutan'  => 1,
      );

      $detail[]=array(
        'ref_akun'  => $b['rek_parent'],
        'keterangan'  => $this->db->escape($b['name']),
        'kredit' => $data['nominal'],
        'debet'  => 0,
        'urutan'  => 2,
      );

      $jurnal=array(
        'tanggal' => $data['tgl_bayar'],
        'keterangan'  => $data['keterangan'],
        'hapus' => 0,
        'ref' => $id,
        'type'  => 20,
        'detail'  => $detail
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
      $this->db->delete('bank_saldo',array('type'=>28,'ref'=>$pb['id']));
      $this->db->delete('jurnal_umum',array('type'=>20,'ref'=>$pb['id']));



    }
    $this->db->update('biaya_operasional',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('biaya_operasional',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('biaya_operasional',$where);
	}
  public function totalDp($no_po){
    return $this->db->firstdetail('biaya_operasional',array('COALESCE(SUM(nominal)) as total'),array(),array('order_id' => $no_po),array());
  }

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('biaya_operasional',$column,$join,$where,array());
  }


}
?>
