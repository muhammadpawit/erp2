<?php
class ModelKeuanganBankCabang extends Model {
  // baru 18 Januari 2019
  public function saldoawalnew($bank_id){
    $sql="SELECT saldoawal,saldoakhir FROM aruskas where bank_id='$bank_id' order by date_trans ASC, id ASC LIMIT 1";
    $d = $this->db->query($sql);
    return $d->row;
  }
  public function laporanmutasibank($bank_id,$data){
    $sql ="SELECT linkterkait,idref,no_dokumen,urlref,jurnal_id,id,date_added as tanggal_input,date_trans as tanggal_transaksi, saldomasuk,saldokeluar, keterangan, ref as referensi FROM aruskas WHERE bank_id='$bank_id' and hapus=0 ";
    if (!empty($data['filter_tgl_awal'])) {
      //$sql .= " AND date(date_trans) >= '" . $data['filter_tgl_awal'] . "'";
    }
    if(!empty($data['filter_tgl_akhir'])) {
      //$sql .= " AND date(date_trans) <= '" . $data['filter_tgl_akhir'] . "'";
    }
    $sql .=" ORDER BY date_trans DESC, id DESC ";
    if (isset($data['start']) || isset($data['limit'])) {
      if ($data['start'] < 0) {
        $data['start'] = 0;
      }

      if ($data['limit'] < 1) {
        $data['limit'] = 20;
      }

      $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
    }
    $d = $this->db->query($sql);
    return $d->rows;
  }
  public function totallaporanmutasibank($bank_id,$data){
    $sql ="SELECT count(*) as total FROM aruskas WHERE bank_id='$bank_id' and hapus=0";
    if (!empty($data['filter_tgl_awal'])) {
      $sql .= " AND date(date_trans) >= '" . $data['filter_tgl_awal'] . "'";
    }
    if(!empty($data['filter_tgl_akhir'])) {
      $sql .= " AND date(date_trans) <= '" . $data['filter_tgl_akhir'] . "'";
    }
    $d = $this->db->query($sql);
    return $d->row['total'];
  }
  // End baru
	// baru 19 November 2019
	public function getsaldosebelumnya($bank_id,$data){
    $sql="SELECT
             id,
             date_trans,
             date_added,
             keterangan,
             ref,
             bank_id,
             saldomasuk,
             saldokeluar,
             saldoakhir,
             LAG(saldoakhir,1) OVER (
                PARTITION BY bank_id
                ORDER BY id asc
             ) saldo_sebelumnya
          FROM
             aruskas where bank_id='$bank_id' and hapus=0 ";

            if (!empty($data['filter_tgl_awal'])) {
              $sql .= " AND date(date_trans) >= '" . $data['filter_tgl_awal'] . "'";
            }
             if (!empty($data['filter_tgl_akhir'])) {
                $sql .= " AND date(date_trans) <= '" . $data['filter_tgl_akhir'] . "'";
              }

              if (!empty($data['filter_jenis'])) {
                $sql .= " AND type = '" . $data['filter_jenis'] . "'";
              }

              if (!empty($data['filter_saldo'])) {
                  if($data['filter_saldo']==1){
                    $sql .=" AND saldomasuk>0 ";
                  }else{
                    $sql .=" AND saldokeluar>0";
                  }
              }

            if (!empty($data['filter_keterangan'])) {
              $sql .= " AND lower(keterangan) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_keterangan']))  . "%'";
            }
            if(isset($data['sorttransaksi'])){
              if($data['sorttransaksi']==1){
                $sql .=" order by date_trans desc, date_added desc ";
              }else{
                $sql .=" order by id desc ";
              }
            }else{
              $sql .=" order by id desc ";
            }
            if (isset($data['start']) || isset($data['limit'])) {
              if ($data['start'] < 0) {
                $data['start'] = 0;
              }

              if ($data['limit'] < 1) {
                $data['limit'] = 20;
              }

              $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
            }
            if($this->user->getUsername()=="pawits"){
              return $sql;
            }else{
              $d = $this->db->query($sql);
              return $d->rows;
            }
  }

  public function totalgetsaldosebelumnya($bank_id,$data){
    $sql="SELECT
             id,
             date_trans,
             date_added,
             keterangan,
             ref,
             bank_id,
             saldomasuk,
             saldokeluar,
             saldoakhir,
             LAG(saldoakhir,1) OVER (
                PARTITION BY bank_id
                ORDER BY id asc
             ) saldo_sebelumnya
          FROM
             aruskas where bank_id='$bank_id' and hapus=0 ";

             if (!empty($data['filter_tgl_awal'])) {
              $sql .= " AND date(date_trans) >= '" . $data['filter_tgl_awal'] . "'";
            }
              if (!empty($data['filter_tgl_akhir'])) {
                $sql .= " AND date(date_trans) <= '" . $data['filter_tgl_akhir'] . "'";
              }
              
              if (!empty($data['filter_saldo'])) {
                  if($data['filter_saldo']==1){
                    $sql .=" AND saldomasuk>0 ";
                  }else{
                    $sql .=" AND saldokeluar>0";
                  }
              }
              if (!empty($data['filter_jenis'])) {
                $sql .= " AND type = '" . $data['filter_jenis'] . "'";
              }
            if (!empty($data['filter_keterangan'])) {
              $sql .= " AND lower(keterangan) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_keterangan']))  . "%'";
            }              
            $sql .=" order by id desc ";
            if (isset($data['start']) || isset($data['limit'])) {
              if ($data['start'] < 0) {
                $data['start'] = 0;
              }

              if ($data['limit'] < 1) {
                $data['limit'] = 20;
              }

              //$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
            }
    $d = $this->db->query($sql);
    return $d->rows;
    //return $sql;
  }
	public function getbarispertama($data=array()){
		$sql="SELECT b.*,t.type_name FROM ".DB_PREFIX."aruskas b LEFT JOIN ".DB_PREFIX."type_mutasi t ON(b.type=t.id) WHERE bank_id='".$data['bank_id']."' AND hapus=0 ";
		if (!empty($data['filter_tgl_awal'])) {
		  $sql .= " AND date(date_trans) >= '" . $data['filter_tgl_awal'] . "'";
		}

		if (!empty($data['filter_tgl_akhir'])) {
		  $sql .= " AND date(date_trans) <= '" . $data['filter_tgl_akhir'] . "'";
		}
		if (!empty($data['filter_jenis'])) {
		  $sql .= " AND type = '" . $data['filter_jenis'] . "'";
		}
		if (!empty($data['filter_saldo'])) {
		  if($data['filter_saldo']==1){
			$sql .=" AND saldomasuk > 0 ";
		  }else{
			$sql .=" AND saldokeluar > 0 ";
		  }
		}
		if (!empty($data['filter_ref'])) {
		  $sql .= " AND ref = '" . $data['filter_ref'] . "'";
		}
		if (!empty($data['filter_keterangan'])) {
		  $sql .= " AND lower(keterangan) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_keterangan']))  . "%'";
		}
		$sql .= " ORDER BY date_trans  ASC,b.id ASC LIMIT 1";
		// if (isset($data['start']) || isset($data['limit'])) {
		//   if ($data['start'] < 0) {
		//     $data['start'] = 0;
		//   }

		//   if ($data['limit'] < 1) {
		//     $data['limit'] = 20;
		//   }

		//   $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
		// }

		$query = $this->db->query($sql);

		return $query->row;
		//echo $sql;
	}  
  // end baru
	// baru 14 November 2019
	public function totalan($data=array()){
		$sql="SELECT sum(saldomasuk) as saldomasuk, sum(saldokeluar) as saldokeluar FROM ".DB_PREFIX."aruskas b LEFT JOIN ".DB_PREFIX."type_mutasi t ON(b.type=t.id) WHERE bank_id='".$data['bank_id']."' AND hapus=0 ";
		if (!empty($data['filter_tgl_awal'])) {
			$sql .= " AND date(date_trans) >= '" . $data['filter_tgl_awal'] . "'";
		}

		if (!empty($data['filter_tgl_akhir'])) {
			$sql .= " AND date(date_trans) <= '" . $data['filter_tgl_akhir'] . "'";
		}
		if (!empty($data['filter_jenis'])) {
			$sql .= " AND type = '" . $data['filter_jenis'] . "'";
		}
		if (!empty($data['filter_saldo'])) {
			if($data['filter_saldo']==1){
				$sql .=" AND saldomasuk > 0 ";
			}else{
				$sql .=" AND saldokeluar > 0 ";
			}
		}
		if (!empty($data['filter_ref'])) {
			$sql .= " AND ref = '" . $data['filter_ref'] . "'";
		}
		if (!empty($data['filter_keterangan'])) {
			$sql .= " AND lower(keterangan) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_keterangan']))  . "%'";
		}
		/*
		$sql .= " ORDER BY date_trans	 DESC,b.id DESC";
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
		}*/
		$query = $this->db->query($sql);
		return $query->row;
		//echo $sql;
	}	
	// end baru
	// baru 4 Oktober 2019
	public function listbank(){
		$sql="SELECT * FROM banks WHERE hapus < 1 and display_order=1 ";
		$d =$this->db->query($sql);
		return $d->rows;
	}
	public function addBank($data){
		if(empty($data['plafon'])){
      $data['plafon']=0;
    }

    $bank=array(
      'name'  => $this->db->escape($data['name']),
      'rekening'  => $data['rekening'],
      'pemilik' => $data['pemilik'],
      'cabang'  => $data['cabang'],
      'kota'  => $data['kota'],
      'swiftcode'  => $data['swiftcode'],
      'currency'  => $data['currency'],
      'rek_parent'  => $data['rek_parent'],
      'saldo' => $data['saldo'],
      'hutangprk'  => $data['hutangprk'],
      'plafon'  => $data['plafon'],
      'display_order' => $data['display_order'],
      'hapus' =>0,
      'bankpusat'=>3,
    );

		$this->db->insert('banks',$bank);
		/*$this->db->query("INSERT INTO ".DB_PREFIX."bank(nama_bank,status,rekening,display_order,code,display_name,pemilik) values('".$this->db->escape($data['nama_bank'])."','1','".$data['rekening']."')");*/
		$bank_id=$this->db->getLastId();

    if($data['saldo'] != 0){
      if($data['saldo'] > 0){
        $saldomasuk=$data['saldo'];
        $saldokeluar=0;
      }else{
        $saldokeluar=$data['saldo'];
        $saldomasuk=0;
      }

  		$saldo=array(
        'date_added'  => date('Y-m-d H:i:s'),
  			'date_trans' => date('Y-m-d'),
  			'bank_id'	=>$bank_id,
  			'saldomasuk'	=> $saldomasuk,
  			'saldokeluar'	=> $saldokeluar,
  			'type'	=> 6,
  			'ref'	=>'',
  			'keterangan'	=> 'Saldo awal bank',
  			'saldoawal'	=> 0,
  			'saldoakhir'	=> $data['saldo']
  		);
      $this->addAruskas($saldo);
      
    }

		//$this->db->query("INSERT INTO ".DB_PREFIX."bank_setoran SET bank_id='".$bank_id."',keterangan='Saldo awal bank',nominal='".$data['saldo']."',tgl_transaksi='".$saldo['tgl_transaksi']."'");
	}

  public function getBanks($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    // $where=array(
    //   'hapus'=>array('<',1),
    //   'gudang'=>array('=',3),
    // );
    return $this->db->alljoin('banks',$column,$join,$where,$order,$limit,$offset);
  }
  public function getBank($column=array(),$join=array(),$where=array()){

    $total=0;
    // $where=array(
    //   'hapus'=>array('<',1),
    //   'gudang'=>array('=',3),
    // );
    $bank=$this->db->firstdetail('banks',array(),array(),$where,array());
    if(!empty($bank)){
      $total=$this->getSelectedSaldo(array('bank_id' => $bank['id']));
      $this->db->update('banks',array('saldo'=>$total['total']),array('id'=> $bank['id']));
    }else{
      $total=0;
    }
    return $this->db->firstdetail('banks',$column,$join,$where,array());
  }
  public function editBank($data,$where){
    if(!isset($data['plafon'])){
      $data['plafon']=0;
    }
    $this->db->update('banks',$data,$where);
  }
  public function updateBank($data,$where){
    /*if(isset($data['saldo'])){
      $bank=$this->getBank(array(),array(),$where);
      $totalsaldo=$this->getSelectedSaldo(array('bank_id'=>$bank['id']));
      $data['saldo']=$totalsaldo['total'];
    }*/
    $this->db->update('banks',$data,$where);
  }
  public function getAruskass($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('aruskas',$column,$join,$where,$order,$limit,$offset);
  }
  public function getAruskas($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('aruskas',$column,$join,$where,array());
  }
  public function addAruskas($data){

    $ak=array(
      'date_added' => $data['date_added'],
      'date_trans'  => isset($data['date_trans'])?$data['date_trans']:$data['date_added'],
      'bank_id' => $data['bank_id'],
      'saldomasuk'  => empty($data['saldomasuk'])?0:$data['saldomasuk'],
      'saldokeluar' => empty($data['saldokeluar'])?0:$data['saldokeluar'],
      'saldoawal' => empty($data['saldoawal'])?0:$data['saldoawal'],
      'saldoakhir'  => empty($data['saldoakhir'])?0:$data['saldoakhir'],
      'ref' => $data['ref'],
      'keterangan'  => $this->db->escape($data['keterangan']),
      'type'  => $data['type'],
      'date_modified' => $data['date_added'],
      'hapus' => 0,
      'linkterkait' => empty($data['linkterkait'])?$data['ref']:$data['linkterkait'],
      'ref_akun'  => $data['ref_akun'],
      'urlref'  => isset($data['urlref'])?$data['urlref']:'',
      'idref' => isset($data['idref'])?$data['idref']:0,
      'no_dokumen' => isset($data['no_dokumen'])?$data['no_dokumen']:'',
      'jurnal_id' => isset($data['jurnal_id'])?$data['jurnal_id']:0,
      

    );

    /*'urlref'	=> 'pembelian/pembayarandepositkredit',
		'no_dokumen'	=> $data['no_dokumen'],
		'idref'	=> $data['ref'],
		'jurnal_id'	=> $jurnal_id */

    $this->db->insert('aruskas',$ak);
    $id=$this->db->getLastId();
    return $id;

  }

  public function updateAruskas($data,$where){
    $this->db->update('aruskas',$data,$where);
  }

  public function getTotalRecordSaldo($data=array()){
		$sql="SELECT count(*) as total FROM ".DB_PREFIX."aruskas WHERE bank_id='".$data['bank_id']."' AND hapus=0";
		if (!empty($data['filter_tgl_awal'])) {
			$sql .= " AND date(date_trans) >= '" . $data['filter_tgl_awal'] . "'";
		}

		if (!empty($data['filter_tgl_akhir'])) {
			$sql .= " AND date(date_trans) <= '" . $data['filter_tgl_akhir'] . "'";
		}

		if (!empty($data['filter_jenis'])) {
			$sql .= " AND type = '" . $data['filter_jenis'] . "'";
		}
		
		if (!empty($data['filter_saldo'])) {
			if($data['filter_saldo']==1){
				$sql .=" AND saldomasuk > 0 ";
			}else{
				$sql .=" AND saldokeluar > 0 ";
			}
		}
		if (!empty($data['filter_ref'])) {
			$sql .= " AND ref = '" . $data['filter_ref'] . "'";
		}
		if (!empty($data['filter_keterangan'])) {
			$sql .= " AND lower(keterangan) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_keterangan']))  . "%'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

  public function getDetailSaldo($id){
    $sql="SELECT b.*,t.type_name FROM ".DB_PREFIX."aruskas b LEFT JOIN ".DB_PREFIX."type_mutasi t ON(b.type=t.id) WHERE b.id='".$id."' AND hapus=0 ";
    $query = $this->db->query($sql);

		return $query->row;
  }

	public function getSaldo($data=array()){
		$sql="SELECT b.*,t.type_name FROM ".DB_PREFIX."aruskas b LEFT JOIN ".DB_PREFIX."type_mutasi t ON(b.type=t.id) WHERE bank_id='".$data['bank_id']."' AND hapus=0 ";
		if (!empty($data['filter_tgl_awal'])) {
			$sql .= " AND date(date_trans) >= '" . $data['filter_tgl_awal'] . "'";
		}

		if (!empty($data['filter_tgl_akhir'])) {
			$sql .= " AND date(date_trans) <= '" . $data['filter_tgl_akhir'] . "'";
		}
		if (!empty($data['filter_jenis'])) {
			$sql .= " AND type = '" . $data['filter_jenis'] . "'";
		}
		if (!empty($data['filter_saldo'])) {
			if($data['filter_saldo']==1){
				$sql .=" AND saldomasuk > 0 ";
			}else{
				$sql .=" AND saldokeluar > 0 ";
			}
		}
		if (!empty($data['filter_ref'])) {
			$sql .= " AND ref = '" . $data['filter_ref'] . "'";
		}
		if (!empty($data['filter_keterangan'])) {
			$sql .= " AND lower(keterangan) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_keterangan']))  . "%'";
		}
		$sql .= " ORDER BY date_trans	 DESC,b.id DESC";
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
		//echo $sql;
	}

  public function getSelectedSaldo($data=array()){
		$sql="SELECT SUM(saldomasuk) as total_masuk,SUM(saldokeluar) as total_keluar FROM ".DB_PREFIX."aruskas WHERE bank_id='".$data['bank_id']."' AND hapus=0";
		if (!empty($data['filter_tgl_awal'])) {
			$sql .= " AND date(date_trans) >= '" . $data['filter_tgl_awal'] . "'";
		}

		if (!empty($data['filter_tgl_akhir'])) {
			$sql .= " AND date(date_trans) <= '" . $data['filter_tgl_akhir'] . "'";
		}

		if (!empty($data['filter_jenis'])) {
			$sql .= " AND type = '" . $data['filter_jenis'] . "'";
		}
		$query = $this->db->query($sql);
		$result=array(
			'saldo_masuk'	=> $query->row['total_masuk'],
			'saldo_keluar'	=> $query->row['total_keluar'],
			'total'	=> $query->row['total_masuk'] - $query->row['total_keluar']
		);
		return $result;
  }
  
  public function getSelectedSaldoAwal($data=array()){
		//$sql="SELECT SUM(saldomasuk) as total_masuk,SUM(saldokeluar) as total_keluar FROM ".DB_PREFIX."aruskas WHERE bank_id='".$data['bank_id']."' AND hapus=0";
    $sql="SELECT id, SUM(saldomasuk-saldokeluar) as saldoawal FROM ".DB_PREFIX."aruskas WHERE bank_id='".$data['bank_id']."' AND hapus=0";
    /*
    if (!empty($data['filter_tgl_awal'])) {
      $date = date('Y-m-d',strtotime(''.$data["filter_tgl_awal"].' -1 month'));
			$sql .= " AND date(date_trans) >= '" . $date . "'";
		}
		if (!empty($data['filter_tgl_akhir'])) {
      $date = date('Y-m-d',strtotime(''.$data["filter_tgl_akhir"].' -1 month'));
			$sql .= " AND date(date_trans) <= '" . $date . "'";
		}
    */
    if (!empty($data['filter_tgl_awal'])) {
      $date = date('Y-m-d',strtotime($data["filter_tgl_awal"]));
			$sql .= " AND date(date_trans) >= '" . $date . "'";
		}
		if (!empty($data['filter_tgl_akhir'])) {
      $date = date('Y-m-d',strtotime($data["filter_tgl_akhir"]));
			$sql .= " AND date(date_trans) <= '" . $date . "'";
		}
		if (!empty($data['filter_jenis'])) {
			$sql .= " AND type = '" . $data['filter_jenis'] . "'";
    }
    $sql .=" GROUP BY id ORDER BY id ASC LIMIT 1";
		$query = $this->db->query($sql);
		$result=array(
			//'saldo_awal'	=> $query->row['total_masuk'],
			//'saldo_keluar'	=> $query->row['total_keluar'],
      //'saldo_awal'	=> $query->row['total_masuk'] - $query->row['total_keluar']
      'saldo_awal'	=> $query->row['saldoawal']
    );
    if($this->user->getUsername()=="pawits"){
      return $sql;
    }else{
      return $result;
    }
	}

  public function getTypeMutasi(){
		$sql="SELECT * FROM ".DB_PREFIX."type_mutasi ";
		$res=$this->db->query($sql);

		return $res->rows;
	}

  public function editTransaksi($data,$id){
    $this->db->update('aruskas',$data,array('id'=>$id));
  }

  public function hapusTransaksi($id){
    $this->db->update('aruskas',array('hapus'=>1),$id);
  }

  public function addTransaksi($data){
    $saldo=$this->getBank(array(),array(),array('id'  => $data['bank_id']));
    $user_id=$this->user->getId();
    $selectedsaldo=array(
      'filter_tgl_akhir' => isset($data['date_trans'])?$data['date_trans']:date('Y-m-d H:i:s'),
      'bank_id' => $data['bank_id']
    );
    $saldoterakhir=$this->getSelectedSaldo($selectedsaldo);

		if($data['transaksi'] != 6 & $data['transaksi'] != 7 ){
			//update saldo updateSaldo($bank_id,$nominal,$jenis)

			if($data['transaksi'] == 1){
				$saldomasuk=$data['nominal'];
				$saldokeluar=0;
        $cursaldo=$saldo['saldo'] + $data['nominal'];
			}else{
				$saldokeluar=$data['nominal'];
				$saldomasuk=0;
        $cursaldo=$saldo['saldo'] - $data['nominal'];
			}
      $this->updateBank(array('saldo' => $cursaldo),array('id'  => $data['bank_id']));

      //get selected saldo

      $ak=array(
        'date_added' => date('Y-m-d H:i:s'),
        'date_trans'  => isset($data['date_trans'])?$data['date_trans']:date('Y-m-d H:i:s'),
        'bank_id' => $data['bank_id'],
        'saldomasuk'  => $saldomasuk,
        'saldokeluar' => $saldokeluar,
        'saldoawal' => $saldo['saldo'],
        'saldoakhir'  => $cursaldo,
        'ref' => 0,
        'keterangan'  => $this->db->escape($data['keterangan']),
        'type'  => 1,
        'date_modified' => date('Y-m-d H:i:s'),
        'hapus' => 0,
        'ref_akun'  => $saldo['rek_parent'],
        'user_id' => $user_id > 0?$user_id:0
      );
			$this->addAruskas($ak);
      $id=$this->db->getLastId();
      $no_dokumen='TrxM-'.date('m-Y').'-'.$user_id.'-'.$id;
      $this->db->update('aruskas',array('no_dokumen'=>$no_dokumen,'linkterkait'=>$no_dokumen,'ref'=>$id),array('id'=>$id));
      if($data['transaksi'] == 1){
          $this->load->model('keuangan/jurnal');
          $details=array();
          if($saldoterakhir['total'] <= 0){
            if($saldo['hutangprk'] == 1){
              /*saldo setelah diisi dana*/
              $selectedsaldo=array(
                'filter_tgl_akhir' => isset($data['date_trans'])?$data['date_trans']:date('Y-m-d H:i:s'),
                'bank_id' => $data['bank_id']
              );
              $saldoupdate=$this->getSelectedSaldo($selectedsaldo);

              if($saldoupdate['total'] > 0){
                $hutangprk=0-$saldoterakhir['total'];
                $debetkas=$saldoupdate['total'];
              }
              if($saldoupdate['total'] == 0){
                $hutangprk=$data['nominal'];
                $debetkas=0;
              }
              if($saldoupdate['total'] < 0){
                $hutangprk=$data['nominal'];
                $debetkas=0;
              }

              if($hutangprk > 0){
                $details[]=array(
                  'ref_akun'  => '2001',
                  'keterangan'  => $this->db->escape($data['keterangan']),
                  'debet' => $hutangprk,
                  'kredit'  => 0,
                  'urutan'  => 2,
                  'hapus' => 0
                );
              }
              if($debetkas > 0){
                $details[]=array(
                   'ref_akun'  => $saldo['rek_parent'],
                  'keterangan'  => $this->db->escape($data['keterangan']),
                  'debet' => $debetkas,
                  'kredit'  => 0,
                  'urutan'  => 2,
                  'hapus' => 0
                );
              }
            }else{
              $details[]=array(
                'ref_akun'  => $saldo['rek_parent'],
                'keterangan'  => $this->db->escape($data['keterangan']),
                'debet' => $data['nominal'],
                'kredit'  => 0,
                'urutan'  => 1,
                'hapus' => 0
              );
            }
          }else{
            $details[]=array(
              'ref_akun'  => $saldo['rek_parent'],
              'keterangan'  => $this->db->escape($data['keterangan']),
              'debet' => $data['nominal'],
              'kredit'  => 0,
              'urutan'  => 1,
              'hapus' => 0
            );
          }
          $i=2;
          if(isset($data['ref_kredit'])){
            if(!empty($data['ref_kredit'])){
              foreach($data['ref_kredit'] as $kred){
                $details[]=array(
                  'ref_akun'  => $kred['akun'],
                  'keterangan'  => $this->db->escape($kred['keterangan']),
                  'debet' => 0,
                  'kredit'  => $kred['nominal'],
                  'urutan'  => $i,
                  'hapus' => 0
                );
                $i++;
              }
            }
          }


          $j=array(
            'tanggal' => $data['date_trans'],
            'keterangan'  => $this->db->escape($data['keterangan']),
            'details' => $details,
            'hapus' =>0,
            'ref' => 0,
            'type'  => 1002,/*setoran tunai*/
            'no_dokumen'=>$no_dokumen,
            'linkterkait'=>$no_dokumen,
          );
          $this->model_keuangan_jurnal->addJurnalUmum($j);

      }

      if($data['transaksi'] == 8){

          $this->load->model('keuangan/jurnal');
          $details=array();
          $i=1;
          if(isset($data['ref_debet'])){
            if(!empty($data['ref_debet'])){
              foreach($data['ref_debet'] as $deb){
                $details[]=array(
                  'ref_akun'  => $deb['akun'],
                  'keterangan'  => $this->db->escape($deb['keterangan']),
                  'debet' => $deb['nominal'],
                  'kredit'  =>0,
                  'urutan'  => $i,
                  'hapus' => 0
                );
                $i++;
              }
            }
          }

          if($saldo['hutangprk'] == 1){
            $selectedsaldo=array(
              'filter_tgl_akhir' => isset($data['date_trans'])?$data['date_trans']:date('Y-m-d H:i:s'),
              'bank_id' => $data['bank_id']
            );
            $saldoupdate=$this->getSelectedSaldo($selectedsaldo);

            if($saldoupdate['total'] < 0){
              if($saldoterakhir['total'] <= 0){
                $hutangprk=$data['nominal'];
                $kredit=0;
              }
              if($saldoterakhir['total'] > 0){
                $hutangprk=0-$saldoupdate['total'];
                $kredit=$saldoterakhir['total'];
              }
              if($hutangprk > 0){
                $details[]=array(
                  'ref_akun'  => '2001',
                  'keterangan'  => $this->db->escape($data['keterangan']),
                  'kredit' => $hutangprk,
                  'debet'  => 0,
                  'urutan'  =>3,
                  'hapus' => 0
                );
              }
              if($kredit > 0){
                $details[]=array(
                  'ref_akun'  => $saldo['rek_parent'],
                  'keterangan'  => $this->db->escape($data['keterangan']),
                  'debet' => 0,
                  'kredit'  => $kredit,
                  'urutan'  => 4,
                  'hapus' => 0
                );
              }
            }else{
              $details[]=array(
                'ref_akun'  => $saldo['rek_parent'],
                'keterangan'  => $this->db->escape($data['keterangan']),
                'debet' => 0,
                'kredit'  => $data['nominal'],
                'urutan'  => 5,
                'hapus' => 0
              );
            }
          }else{
            $details[]=array(
              'ref_akun'  => $saldo['rek_parent'],
              'keterangan'  => $this->db->escape($data['keterangan']),
              'debet' => 0,
              'kredit'  => $data['nominal'],
              'urutan'  =>6,
              'hapus' => 0
            );
          }


          $j=array(
            'tanggal' => $data['date_trans'],
            'keterangan'  => $this->db->escape($data['keterangan']),
            'details' => $details,
            'hapus' =>0,
            'ref' => 0,
            'linkterkait' =>$no_dokumen,
            'type'  => 1001,/*pengeluaran kas*/
            'no_dokumen'=>$no_dokumen,
          );
          $this->model_keuangan_jurnal->addJurnalUmum($j);

      }

		}
		if($data['transaksi'] == 7){
			//$saldo=$this->getBank($data['bank_id']);
			//$cursaldo=$this->updateSaldo($data['bank_id'],$data['nominal'],2);
      $cursaldo=$saldo['saldo'] - $data['nominal'];
      $this->updateBank(array('saldo' => $cursaldo),array('id'  => $data['bank_id']));

      $ak=array(
        'date_added' => date('Y-m-d H:i:s'),
        'date_trans'  => isset($data['date_trans'])?$data['date_trans']:date('Y-m-d H:i:s'),
        'bank_id' => $data['bank_id'],
        'saldomasuk'  => 0,
        'saldokeluar' => $data['nominal'],
        'saldoawal' => $saldo['saldo'],
        'saldoakhir'  => $cursaldo,
        'ref' => 0,
        'keterangan'  => $this->db->escape($data['keterangan']),
        'type'  => 7,
        'date_modified' => date('Y-m-d H:i:s'),
        'hapus' => 0,
        'ref_akun'  => $saldo['rek_parent'],
        'user_id' => $user_id > 0?$user_id:0
      );
      $data['ref_kredit']=$saldo['rek_parent'];

      $selectedsaldo=array(
        'filter_tgl_akhir' => isset($data['date_trans'])?$data['date_trans']:date('Y-m-d H:i:s'),
        'bank_id' => $data['bank_id']
      );
      $saldoupdate=$this->getSelectedSaldo($selectedsaldo);


			$this->addAruskas($ak);
      $this->load->model('keuangan/jurnal');


			$saldotujuan=$this->getBank(array(),array(),array('id'=>$data['tujuan']));
      $selectedsaldotujuan=array(
        'filter_tgl_akhir' => isset($data['date_trans'])?$data['date_trans']:date('Y-m-d H:i:s'),
        'bank_id' => $data['tujuan']
      );
      $saldoterakhirtujuan=$this->getSelectedSaldo($selectedsaldotujuan);

			$cursaldo=$saldotujuan['saldo'] + $data['nominal'];
      $this->updateBank(array('saldo' => $cursaldo),array('id'  => $data['tujuan']));

      $ak=array(
        'date_added' => date('Y-m-d H:i:s'),
        'date_trans'  => isset($data['date_trans'])?$data['date_trans']:date('Y-m-d H:i:s'),
        'bank_id' => $data['tujuan'],
        'saldomasuk'  => $data['nominal'],
        'saldokeluar' => 0,
        'saldoawal' => $saldotujuan['saldo'],
        'saldoakhir'  => $cursaldo,
        'ref' => 0,
        'keterangan'  => $this->db->escape($data['keterangan']),
        'type'  => 7,
        'date_modified' => date('Y-m-d H:i:s'),
        'hapus' => 0,
        'ref_akun'  => $saldotujuan['rek_parent'],
        'user_id' => $user_id > 0?$user_id:0
      );


			$this->addAruskas($ak);
      $data['ref_debet']=$saldotujuan['rek_parent'];

      $selectedsaldodebet=array(
        'filter_tgl_akhir' => isset($data['date_trans'])?$data['date_trans']:date('Y-m-d H:i:s'),
        'bank_id' => $data['tujuan']
      );
      $saldoupdatedebet=$this->getSelectedSaldo($selectedsaldodebet);

          $this->load->model('keuangan/jurnal');
          $details=array();

          if($saldoterakhirtujuan['total'] <= 0){
            if($saldotujuan['hutangprk'] == 1){
              /*saldo setelah diisi dana*/


              if($saldoupdatedebet['total'] > 0){
                $hutangprk=0-$saldoterakhirtujuan['total'];
                $debetkas=$saldoupdatedebet['total'];
              }
              if($saldoupdatedebet['total'] == 0){
                $hutangprk=$data['nominal'];
                $debetkas=0;
              }
              if($saldoupdatedebet['total'] < 0){
                $hutangprk=$data['nominal'];
                $debetkas=0;
              }

              if($hutangprk > 0){
                $details[]=array(
                  'ref_akun'  => '2001',
                  'keterangan'  => $this->db->escape($data['keterangan']),
                  'debet' => $hutangprk,
                  'kredit'  => 0,
                  'urutan'  => 1,
                  'hapus' => 0
                );
              }
              if($debetkas > 0){
                $details[]=array(
                  'ref_akun'  => $data['ref_debet'],
                  'keterangan'  => $this->db->escape($data['keterangan']),
                  'debet' => $debetkas,
                  'kredit'  => 0,
                  'urutan'  => 2,
                  'hapus' => 0
                );
              }
            }else{
              $details[]=array(
                'ref_akun'  => $data['ref_debet'],
                'keterangan'  => $this->db->escape($data['keterangan']),
                'debet' => $data['nominal'],
                'kredit'  => 0,
                'urutan'  => 1,
                'hapus' => 0
              );
            }
          }else{
            $details[]=array(
              'ref_akun'  => $data['ref_debet'],
              'keterangan'  => $this->db->escape($data['keterangan']),
              'debet' => $data['nominal'],
              'kredit'  => 0,
              'urutan'  => 1,
              'hapus' => 0
            );
          }

          if($saldo['hutangprk'] == 1){
            $selectedsaldo=array(
              'filter_tgl_akhir' => isset($data['date_trans'])?$data['date_trans']:date('Y-m-d H:i:s'),
              'bank_id' => $data['bank_id']
            );
            $saldoupdate=$this->getSelectedSaldo($selectedsaldo);

            if($saldoupdate['total'] < 0){
              if($saldoterakhir['total'] <= 0){
                $hutangprk=$data['nominal'];
                $kredit=0;
              }
              if($saldoterakhir['total'] > 0){
                $hutangprk=0-$saldoupdate['total'];
                $kredit=$saldoterakhir['total'];
              }
              if($hutangprk > 0){
                $details[]=array(
                  'ref_akun'  => '2001',
                  'keterangan'  => $this->db->escape($data['keterangan']),
                  'kredit' => $hutangprk,
                  'debet'  => 0,
                  'urutan'  => 3,
                  'hapus' => 0
                );
              }
              if($kredit > 0){
                $details[]=array(
                  'ref_akun'  => $data['ref_kredit'],
                  //'jenis_akun'  => 52,
                  'keterangan'  => $this->db->escape($data['keterangan']),
                  'debet' => 0,
                  'kredit'  => $kredit,
                  'urutan'  => 4,
                  'hapus' => 0
                );
              }
            }else{
              $details[]=array(
                'ref_akun'  => $data['ref_kredit'],
                //'jenis_akun'  => 52,
                'keterangan'  => $this->db->escape($data['keterangan']),
                'debet' => 0,
                'kredit'  => $data['nominal'],
                'urutan'  => 5,
                'hapus' => 0
              );
            }
          }else{
            $details[]=array(
              'ref_akun'  =>$data['ref_kredit'],
              //'jenis_akun'  => 52,
              'keterangan'  => $this->db->escape($data['keterangan']),
              'debet' => 0,
              'kredit'  => $data['nominal'],
              'urutan'  => 6,
              'hapus' => 0
            );
          }


          $j=array(
            'tanggal' => $data['date_trans'],
            'keterangan'  => $this->db->escape($data['keterangan']),
            'details' => $details,
            'hapus' =>0,
            'ref' => 0,
            'linkterkait' => $this->db->escape($data['linkterkait']),
            'type'  => 1000/*transfer bank*/
          );
          $this->model_keuangan_jurnal->addJurnalUmum($j);


		}

		if($data['transaksi'] == 6){
			//update saldo updateSaldo($bank_id,$nominal,$jenis)
			//$saldo=$this->getBank(array(),array(),array('id'=>$data['bank_id']));
			if($data['nominal'] <= $saldo['saldo']){
				$saldomasuk=0;
				$saldokeluar=$saldo['saldo'] - $data['nominal'];

        if(!empty($data['ref_debet'])){
          $this->load->model('keuangan/jurnal');
          $details=array();
          $details[]=array(
            'ref_akun'  => $data['ref_debet'],
            'keterangan'  => $this->db->escape($data['keterangan']),
            'debet' => $saldo['saldo'] - $data['nominal'],
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );


          $details[]=array(
            'ref_akun'  => $data['ref_kredit'],
            //'jenis_akun'  => 52,
            'keterangan'  => $this->db->escape($data['keterangan']),
            'debet' => 0,
            'kredit'  => $saldo['saldo'] - $data['nominal'],
            'urutan'  => 2,
            'hapus' => 0
          );


          $j=array(
            'tanggal' => $data['date_trans'],
            'keterangan'  => $this->db->escape($data['keterangan']),
            'details' => $details,
            'hapus' =>0,
            'ref' => 0,
            'linkterkait' => $this->db->escape($data['linkterkait']),
            'type'  => 1003/*penyesuaian saldo*/
          );
          $this->model_keuangan_jurnal->addJurnalUmum($j);
        }

			}else{
				$saldokeluar=0;
				$saldomasuk=$data['nominal'] - $saldo['saldo'];

        if(!empty($data['ref_debet'])){
          $this->load->model('keuangan/jurnal');
          $details=array();
          $details[]=array(
            'ref_akun'  => $data['ref_debet'],
            'keterangan'  => $this->db->escape($data['keterangan']),
            'debet' => $saldo['saldo'] - $data['nominal'],
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );


          $details[]=array(
            'ref_akun'  => $data['ref_kredit'],
            //'jenis_akun'  => 52,
            'keterangan'  => $this->db->escape($data['keterangan']),
            'debet' => 0,
            'kredit'  => $saldo['saldo'] - $data['nominal'],
            'urutan'  => 2,
            'hapus' => 0
          );


          $j=array(
            'tanggal' => $data['date_trans'],
            'keterangan'  => $this->db->escape($data['keterangan']),
            'details' => $details,
            'hapus' =>0,
            'ref' => 0,
            'linkterkait' => $this->db->escape($data['linkterkait']),
            'type'  => 1003/*penyesuaian saldo*/
          );
          $this->model_keuangan_jurnal->addJurnalUmum($j);
        }
				//$cursaldo=$this->updateSaldo($data['bank_id'],$saldomasuk,1);
			}

      $this->updateBank(array('saldo' => $data['nominal']),array('id'  => $data['bank_id']));

      $ak=array(
        'date_added' => date('Y-m-d H:i:s'),
        'date_trans'  => isset($data['date_trans'])?$data['date_trans']:date('Y-m-d H:i:s'),
        'bank_id' => $data['bank_id'],
        'saldomasuk'  => $saldomasuk,
        'saldokeluar' => $saldokeluar,
        'saldoawal' => $saldo['saldo'],
        'saldoakhir'  => $data['nominal'],
        'ref' => 0,
        'keterangan'  => $this->db->escape($data['keterangan']),
        'type'  => 6,
        'date_modified' => date('Y-m-d H:i:s'),
        'hapus' => 0,
        'ref_akun'  => $saldo['rek_parent'],
        'user_id' => $user_id > 0?$user_id:0
      );


      $this->addAruskas($ak);



		}

	}

}
