<?php
class ModelKeuanganLabarugi extends Model {
  public function addLabaRugi($data){
    $this->load->model('kepegawaian/periode');
    $periode=$this->model_kepegawaian_periode->getPeriode($data['periode_id']);

    $date_start=date('Y-m-01',strtotime($periode['tgl_awal']));
    $date_end=date('Y-m-t',strtotime($periode['tgl_awal']));

    $laba=array(
      'periode_id'  => $data['periode_id'],
      'tglawal' => $date_start,
      'tglselesai'  => $date_end,
      'date_added'  => date('Y-m-d H:i:s'),
      'user_id' => $this->user->getId(),
      'hapus' => 0,
      'status'  => 1
    );
    $this->db->insert('laporan_labarugi',$laba);
    $laba['id']=$this->db->getLastId();

    $this->addDetailLabaRugi($laba);
  }

  public function addDetailLabaRugi($data){
    $this->load->model('keuangan/jurnal');
  	$this->load->model('keuangan/coa');


    $filter=array(
			'filter_date_start'	=> $data['tglawal'],
			'filter_date_end'	=> $data['tglselesai']
		);
    $type = array(
  		'filter_type'	=> 4,

  	);

    $pendapatan=$this->model_keuangan_coa->getAllCategories($type);
  	$totalpendapatan=0;
    $i=1;
  	foreach($pendapatan as $p){

  		$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
  		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

  		$saldo=$kredit-$debet;
  		/*$this->data['pendapatan'][]=array(
  			'name'        => $p['name'],
  			'kode_rek'	=> $p['kode_rek'],
  			'parent_id'	=> $p['parent_id'],
  			'saldo'	=> $this->currency->format(abs($saldo)),
  			'plainsaldo'	=> $saldo
  		);*/
      $detail=array(
        'laporan_id'  => $data['id'],
        'ref_akun'  => $p['kode_rek'],
        'name'  => $this->db->escape($p['name']),
        'type'  => 4,
        'debet' => 0,
        'kredit'  => $saldo,
        'urutan'  => $i
      );
  		$totalpendapatan += $saldo;
      $this->db->insert('laporan_labarugi_detail',$detail);
      $i++;
  	}

    $type = array(
  		'filter_type'	=> 5,

  	);

    $hpp=$this->model_keuangan_coa->getAllCategories($type);
  	$totalhpp=0;

  	foreach($hpp as $p){
  		$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
  		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

  		$saldo=$debet-$kredit;
  		/*$this->data['pendapatan'][]=array(
  			'name'        => $p['name'],
  			'kode_rek'	=> $p['kode_rek'],
  			'parent_id'	=> $p['parent_id'],
  			'saldo'	=> $this->currency->format(abs($saldo)),
  			'plainsaldo'	=> $saldo
  		);*/
      $detail=array(
        'laporan_id'  => $data['id'],
        'ref_akun'  => $p['kode_rek'],
        'name'  => $this->db->escape($p['name']),
        'type'  => 5,
        'debet' => $saldo,
        'kredit'  => 0,
        'urutan'  => $i
      );
  		$totalhpp += $saldo;
      $this->db->insert('laporan_labarugi_detail',$detail);
      $i++;
  	}

    $labakotor=$totalpendapatan-$totalhpp;

    $type = array(
  		'filter_type'	=> 6,

  	);

    $biaya=$this->model_keuangan_coa->getAllCategories($type);
  	$totalbiaya=0;

  	foreach($biaya as $p){
  		$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
  		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

  		$saldo=$debet-$kredit;
  		/*$this->data['pendapatan'][]=array(
  			'name'        => $p['name'],
  			'kode_rek'	=> $p['kode_rek'],
  			'parent_id'	=> $p['parent_id'],
  			'saldo'	=> $this->currency->format(abs($saldo)),
  			'plainsaldo'	=> $saldo
  		);*/
      $detail=array(
        'laporan_id'  => $data['id'],
        'ref_akun'  => $p['kode_rek'],
        'name'  => $this->db->escape($p['name']),
        'type'  => 6,
        'debet' => $saldo,
        'kredit'  => 0,
        'urutan'  => $i
      );
  		$totalbiaya += $saldo;
      $this->db->insert('laporan_labarugi_detail',$detail);
      $i++;
  	}

    $type = array(
  		'filter_type'	=> 7,

  	);

    $pendapatanlain=$this->model_keuangan_coa->getAllCategories($type);
  	$totalpendapatanlain=0;

  	foreach($pendapatanlain as $p){
  		$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
  		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

  		$saldo=$debet-$kredit;
  		/*$this->data['pendapatan'][]=array(
  			'name'        => $p['name'],
  			'kode_rek'	=> $p['kode_rek'],
  			'parent_id'	=> $p['parent_id'],
  			'saldo'	=> $this->currency->format(abs($saldo)),
  			'plainsaldo'	=> $saldo
  		);*/
      $detail=array(
        'laporan_id'  => $data['id'],
        'ref_akun'  => $p['kode_rek'],
        'name'  => $this->db->escape($p['name']),
        'type'  => 7,
        'debet' => 0,
        'kredit'  => $saldo,
        'urutan'  => $i
      );
  		$totalpendapatanlain += $saldo;
      $this->db->insert('laporan_labarugi_detail',$detail);
      $i++;
  	}

    $type = array(
  		'filter_type'	=> 8,

  	);

    $biayalain=$this->model_keuangan_coa->getAllCategories($type);
  	$totalbiayalain=0;

  	foreach($biayalain as $p){
  		$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
  		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

  		$saldo=$kredit-$debet;
  		/*$this->data['pendapatan'][]=array(
  			'name'        => $p['name'],
  			'kode_rek'	=> $p['kode_rek'],
  			'parent_id'	=> $p['parent_id'],
  			'saldo'	=> $this->currency->format(abs($saldo)),
  			'plainsaldo'	=> $saldo
  		);*/
      $detail=array(
        'laporan_id'  => $data['id'],
        'ref_akun'  => $p['kode_rek'],
        'name'  => $this->db->escape($p['name']),
        'type'  => 8,
        'debet' => $saldo,
        'kredit'  => 0,
        'urutan'  => $i
      );
  		$totalbiayalain += $saldo;
      $this->db->insert('laporan_labarugi_detail',$detail);
      $i++;
  	}

    $type = array(
  		'filter_type'	=> 9,

  	);

    $pendapatanluarbiasa=$this->model_keuangan_coa->getAllCategories($type);
  	$totalpendapatanluarbiasa=0;

  	foreach($pendapatanluarbiasa as $p){
  		$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
  		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

  		$saldo=$kredit-$debet;
  		/*$this->data['pendapatan'][]=array(
  			'name'        => $p['name'],
  			'kode_rek'	=> $p['kode_rek'],
  			'parent_id'	=> $p['parent_id'],
  			'saldo'	=> $this->currency->format(abs($saldo)),
  			'plainsaldo'	=> $saldo
  		);*/
      $detail=array(
        'laporan_id'  => $data['id'],
        'ref_akun'  => $p['kode_rek'],
        'name'  => $this->db->escape($p['name']),
        'type'  => 8,
        'debet' => 0,
        'kredit'  => $saldo,
        'urutan'  => $i
      );
  		$totalpendapatanluarbiasa += $saldo;
      $this->db->insert('laporan_labarugi_detail',$detail);
      $i++;
  	}
    $labarugibersih=$labakotor - $totalbiaya - $totalbiayalain + $totalpendapatanlain + $totalpendapatanluarbiasa;
    $this->db->update('laporan_labarugi',array('labarugi'=>$labarugibersih),array('id'=>$data['id']));

  }

  public function labarugi($data=array()){
    $sql="SELECT * FROM laporan_labarugi WHERE hapus=0 ";
    if(!empty($data['filter_periode'])){
        $sql .=" AND periode_id='".$data['filter_periode']."'";
    }
      $sql .=" ORDER BY tglawal DESC,id DESC ";

    if (isset($data['start']) || isset($data['limit'])) {
      if ($data['start'] < 0) {
        $data['start'] = 0;
      }

      if ($data['limit'] < 1) {
        $data['limit'] = 20;
      }

      $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
    }

    $query = $this->db->query($sql);
    return $query->rows;
  }

  public function totallabarugi($data=array()){
    $sql="SELECT COUNT(*) as total FROM laporan_labarugi WHERE hapus=0 ";
    if(!empty($data['filter_periode'])){
        $sql .=" AND periode_id='".$data['filter_periode']."'";
    }
    $query = $this->db->query($sql);
    return $query->row['total'];
  }

  public function getLabaRugi($id){
    $sql=$this->db->query("SELECT * FROM laporan_labarugi WHERE hapus=0 AND id='".$id."'");
    return $sql->row;
  }
  public function getLabaRugiDetail($id,$type){
    $sql="SELECT * FROM laporan_labarugi_detail WHERE laporan_id='".$id."' AND type='".$type."' ORDER BY urutan";
    $result=$this->db->query($sql);

    return $result->rows;
  }



}
