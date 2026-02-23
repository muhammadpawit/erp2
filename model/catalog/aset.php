<?php
class ModelCatalogAset extends Model {
	public function gets($data)
	{
		$sql ="SELECT aset.aset_id, aset.name as name, pemeliharaan.name as pemeliharaanname, pemeliharaan_aset.* FROM pemeliharaan_aset JOIN aset ON(pemeliharaan_aset.aset_id=aset.aset_id) JOIN pemeliharaan ON(pemeliharaan_aset.pemeliharaan_id=pemeliharaan.id) ";
		$sql .=" WHERE pemeliharaan_aset.hapus < 1";
		if(isset($data['filter_name']))
		{
			$sql .=" AND lower(aset.name) LIKE '%".strtolower($data['filter_name'])."%'";
		}
		if(isset($data['filter_kelompok_aset']))
		{
			$sql .=" AND pemeliharaan_id = '".$data['filter_kelompok_aset']."'";
		}
		$sql .=" ORDER BY pemeliharaan_aset.tanggal DESC";
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

	public function totalgets($data)
	{
		$sql ="SELECT aset.aset_id, aset.name as name, pemeliharaan.name as pemeliharaanname, pemeliharaan_aset.* FROM pemeliharaan_aset JOIN aset ON(pemeliharaan_aset.aset_id=aset.aset_id) JOIN pemeliharaan ON(pemeliharaan_aset.pemeliharaan_id=pemeliharaan.id) ";
		$sql .=" WHERE pemeliharaan_aset.hapus < 1";
		if(isset($data['filter_name']))
		{
			$sql .=" AND lower(aset.name) LIKE '%".strtolower($data['filter_name'])."%'";
		}
		if(isset($data['filter_kelompok_aset']))
		{
			$sql .=" AND pemeliharaan_id = '".$data['filter_kelompok_aset']."'";
		}
		$sql .=" ORDER BY pemeliharaan_aset.tanggal DESC";
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			//$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}	
	public function addAset($data){


		$aset=array(
			'kode'	=> $this->db->escape($data['kode']),
			'name'	=> $this->db->escape($data['name']),
			'jumlah' => empty($data['jumlah'])?0:$data['jumlah'],
			'harga' => empty($data['harga'])?0:$data['harga'],
			'status'	=> $data['status'],
			'hargabeli'	=> 0,
			'nilaibuku'	=> 0,
			'tglpembelian'	=> '1970-01-01',
			'kelompok_aset'	=> $data['kelompok_aset'],
			'jenis_aktiva'	=> $data['jenis_aktiva'],
			'date_added'	=> date('Y-m-d H:i:s',time()),
			'date_modified'	=> date('Y-m-d H:i:s',time()),
			'hapus'	=>0,

		);
		$this->db->insert('aset',$aset);
	}


	public function updateAset($data,$where=array()){
		/*if($data['hargabeli'] > 0){

		}*/
		if($data['status'] == 3){
			$this->load->model('catalog/kelompokaset');
			$aset=$this->getAset($where);

			$aktiva=$this->model_catalog_kelompokaset->getAktiva(array('no_akun'  => $aset['jenis_aktiva']));
			$manfaat=$kel['masa_manfaat'];
			$tarif=$kel['nilai_depresiasi'];

			$penyusutantahunan=($tarif/100)*($a['hargabeli']/$manfaat);
			$penyusutanbulanan=$penyusutantahunan/12;


			$kartu=array(
				'aset_id'	=> $aset['aset_id'],
				'tglbuku'	=> date('Y-m-d H:i:s'),
				'hargabeli'	=> $aset['hargabeli'],
				'penyusutan'	=> $penyusutantahunan,
				'penyusutanbulanan'	=> $penyusutanbulanan,
				'akumulasipenyusutan'=> $aset['nilaibuku'],
				'nilaibuku'=>0,
				'nilaibukuawal'	=> $aset['hargabeli']
			);
			$this->kartuaset($kartu);

			$this->load->model('keuangan/jurnal');
			$detail=array();
			$detail[]=array(
				'ref_akun'  =>$aktiva['beban'],
				'debet' => $aset['nilaibuku'],
				'kredit'  => 0,
				'urutan'  =>1,
				'keterangan'  => 'Penyusutan Aset'
			);

			$detail[]=array(
				'ref_akun'  =>$aktiva['akumulasi'],
				'debet' => 0,
				'kredit'  => $aset['nilaibuku'],
				'urutan'  =>2,
				'keterangan'  => 'Akumulasi Penyusutan'
			);

			$jurnal=array(
				'tanggal' => date('Y-m-d H:i:s'),
				'keterangan' => 'Penyusutan Aset',
				'ref' => 0,
				'type' => 1000,
				'details' => $detail
			);
			$this->model_keuangan_jurnal->addJurnalUmum($jurnal);
		}
		$update = array(
			'kode' => empty($data['kode'])?'-':$data['kode'],
			'name' => $data['name'],
			'jumlah' =>empty($data['jumlah'])?0:$data['jumlah'],
			'harga' =>empty($data['harga'])?0:$data['harga'],
			'jenis_aktiva' => $data['jenis_aktiva'],
			'kelompok_aset' => $data['kelompok_aset'],
			//'status' => $data['status'],
		);
		$this->db->update('aset',$update,$where);
		//$this->db->update('aset',$data,$where);
	}
	public function getAset($where){
		return $this->db->first('aset',$where);
	}
	public function getAsets($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
		return $this->db->alljoin('aset',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalAsets($where){
	//	$where[]=array('hapus'	=> 0);
		return $this->db->countAll('aset',$where);
	}

	public function getPemeliharaans($column,$join,$where,$order,$limit,$offset){
		return $this->db->alljoin('pemeliharaan_aset',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalPemeliharaans($where){
	//	$where[]=array('hapus'	=> 0);
		return $this->db->countAll('pemeliharaan_aset',$where);
	}
	public function getPemeliharaan($where){
		return $this->db->first('pemeliharaan_aset',$where);
	}

	public function addPemeliharaan($data){
		$this->load->model('keuangan/jurnal');
		$aset=array(
			'aset_id'	=> $data['aset_id'],
			'pemeliharaan_id'	=> $data['pemeliharaan_id'],
			'tanggal'	=> $data['tanggal'],
			'biaya'	=> $data['biaya'],
			'bank_id'	=> 0,
			'totalbayar'	=> 0,
			'akun'	=> $data['akun'],
			'hapus'	=> 0,
			'status'	=>1

		);
		$this->db->insert('pemeliharaan_aset',$aset);

		$id=$this->db->getLastId();

		/*$detail=array();
		$detail[]=array(
			'ref_akun'  =>$data['akun'],
			'debet' => $data['biaya'],
			'kredit'  => 0,
			'urutan'  =>1,
			'keterangan'  => 'Pemeliharaan Aset'
		);

		$detail[]=array(
			'ref_akun'  =>'2399',
			'debet' => 0,
			'kredit'  => $data['biaya'],
			'urutan'  =>2,
			'keterangan'  => 'Biaya masih harus dibayar lain-lain'
		);

		$jurnal=array(
			'tanggal' => $data['tanggal'],
			'keterangan' => 'Pemeliharaan Aset',
			'ref' => $id,
			'type' => 12,
			'details' => $detail
		);
		$this->model_keuangan_jurnal->addJurnalUmum($jurnal);
		*/
	}


	public function updatePemeliharaan($data,$where=array()){

		$this->db->update('pemeliharaan_aset',$data,$where);
	}

	public function updateTotalBayar($id,$jumlah,$jenis){

    $data=$this->getPemeliharaan(array('id'=>$id));
    if($jenis == 2){
      $total=$data['totalbayar'] - $jumlah;
    }
    if($jenis == 1){
      $total=$data['totalbayar'] + $jumlah;
    }

    if($total >= $data['biaya']){
      $status=3;
    }else{
			if($total <= 0){
	      $status=1;
	    }
	    else{
	      $status = 2;
	    }
		}

    $this->db->update('pemeliharaan_aset',array('totalbayar'=>$total,'status'=>$status),array('id'=>$id));
    return $total;
  }

	public function batalkanPemeliharaan($id){
		$pem=$this->getPemeliharaan(array('id'=>$id));
		$this->updatePemeliharaan(array('hapus'=>1,'status'=>'4'),array('id'=>$id));
		//$this->db->update('jurnal_umum',array('hapus'=>1),array('ref'=>$id,'type'=>12));

		/*$this->db->delete('aruskas',array('type'	=> 10,'ref'=>$id));
		$this->load->model('keuangan/bank');
		$b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $pem['bank_id']));
		$saldo=$b['saldo'] - $pem['biaya'];
		$this->model_keuangan_bank->updateBank(array('saldo'  => $saldo),array('id'=> $pem['bank_id']));*/
	}

	//penyesuaian nilai
	public function penyesuaianNilai(){
		$curdate=date('Y-m-d');
		$aset=$this->getAsets(array(),array(),array('hapus'=>array('<',1)));
		foreach($aset as $a){
			$tglbeli=$a['tglpembelian'];
			$usia=(int)abs((strtotime($curdate) - strtotime($tglbeli))/(60*60*24*30));
			$usiatahun=$usia/12;

			//depresiasi
			$this->load->model('catalog/kelompokaset');
			$kel=$this->model_catalog_kelompokaset->getKelompokaset($a['kelompok_aset']);
			$aktiva=$this->model_catalog_kelompokaset->getAktiva(array('no_akun'  => $a['jenis_aktiva']));

			$manfaat=$kel['masa_manfaat'];
			$tarif=$kel['nilai_depresiasi'];

			if(($usiatahun <= $manfaat) & ($tglbeli != '1970-01-01')){

				//penyusutan=tarif* (harga/umur)
				$penyusutantahunan=($tarif/100)*$a['hargabeli'];
				$penyusutanbulanan=$penyusutantahunan/12;
				$akumulasipenyusutan=($usia/12)*$penyusutantahunan;

				$nilaibuku=$a['hargabeli'] - $akumulasipenyusutan;

				$kartu=array(
					'aset_id'	=> $a['aset_id'],
					'hargabeli'	=> $a['hargabeli'],
					'penyusutan'	=> $penyusutantahunan,
					'penyusutanbulanan'	=> $penyusutanbulanan,
					'akumulasipenyusutan'=> $akumulasipenyusutan,
					'nilaibuku'=>$nilaibuku,
					'nilaibukuawal'=> $a['nilaibuku']
				);
				$this->kartuaset($kartu);

				/*if($nilaibuku != $a['nilaibuku']){
					if($nilaibuku-$a['nilaibuku'] > 0){
						$this->load->model('keuangan/jurnal');
						$detail=array();
						$detail[]=array(
							'ref_akun'  =>$aktiva['beban'],
							'debet' => $nilaibuku-$a['nilaibuku'],
							'kredit'  => 0,
							'urutan'  =>1,
							'keterangan'  => 'Penyusutan Aset'
						);

						$detail[]=array(
							'ref_akun'  =>$aktiva['akumulasi'],
							'debet' => 0,
							'kredit'  => $nilaibuku-$a['nilaibuku'],
							'urutan'  =>2,
							'keterangan'  => 'Akumulasi Penyusutan'
						);

						$jurnal=array(
							'tanggal' => date('Y-m-d H:i:s'),
							'keterangan' => 'Penyusutan Aset',
							'ref' => 0,
							'type' => 1000,
							'details' => $detail
						);
						$this->model_keuangan_jurnal->addJurnalUmum($jurnal);
					}
				}*/
				//$this->updateAset(array('nilaibuku'=>$nilaibuku));
			}
		}
	}

	public function setInfoAset($a,$aset_id){
		$this->load->model('catalog/kelompokaset');
		$aset=$this->getAset(array('aset_id'=>$aset_id));
		$this->updateAset(array('tglpembelian'=>$a['tglpembelian'],'status'=>1),array('aset_id'=>$aset_id));
		$kel=$this->model_catalog_kelompokaset->getKelompokaset($aset['kelompok_aset']);

		$aktiva=$this->model_catalog_kelompokaset->getAktiva(array('no_akun'  => $aset['jenis_aktiva']));
		$manfaat=$kel['masa_manfaat'];
		$tarif=$kel['nilai_depresiasi'];

		$penyusutantahunan=($tarif/100)*$a['hargabeli'];
		$penyusutanbulanan=$penyusutantahunan/12;

		$this->updateAset(array('nilaibuku'=>$a['nilaibuku'],'hargabeli'=>$a['hargabeli'],'akumulasipenyusutan'=>$a['hargabeli']-$a['nilaibuku'],'penyusutan'=>$penyusutantahunan,'penyusutanbulanan'=>$penyusutanbulanan),array('aset_id'=>$aset_id));



		$kartu=array(
			'aset_id'	=> $aset_id,
			'tglbuku'	=> $a['tglbuku'],
			'hargabeli'	=> $a['hargabeli'],
			'penyusutan'	=> $penyusutantahunan,
			'penyusutanbulanan'	=> $penyusutanbulanan,
			'akumulasipenyusutan'=> $a['hargabeli']-$a['nilaibuku'],
			'nilaibuku'=>$a['nilaibuku'],
			'nilaibukuawal'	=> $a['hargabeli']
		);
		$this->kartuaset($kartu);

		/*$this->load->model('keuangan/jurnal');
		$detail=array();
		$detail[]=array(
			'ref_akun'  =>$aktiva['beban'],
			'debet' => $a['hargabeli']-$a['nilaibuku'],
			'kredit'  => 0,
			'urutan'  =>1,
			'keterangan'  => 'Penyusutan Aset'
		);

		$detail[]=array(
			'ref_akun'  =>$aktiva['akumulasi'],
			'debet' => 0,
			'kredit'  => $a['hargabeli']-$a['nilaibuku'],
			'urutan'  =>2,
			'keterangan'  => 'Akumulasi Penyusutan'
		);

		$jurnal=array(
			'tanggal' => date('Y-m-d H:i:s'),
			'keterangan' => 'Penyusutan Aset',
			'ref' => 0,
			'type' => 1000,
			'details' => $detail
		);
		$this->model_keuangan_jurnal->addJurnalUmum($jurnal);*/


	}

	public function kartuaset($data){
		if($data['nilaibuku'] != $data['nilaibukuawal']){
			$kartu=array(
				'aset_id'	=> $data['aset_id'],
				'tanggal'	=> isset($data['tglbuku'])?$data['tglbuku']:date('Y-m-d H:i:s'),
				'hargabeli'	=> $data['hargabeli'],
				'penyusutan'	=> $data['penyusutan'],
				'penyusutanbulanan'	=> $data['penyusutanbulanan'],
				'akumulasipenyusutan'	=> $data['akumulasipenyusutan'],
				'nilaibuku'	=> $data['nilaibuku']
			);

			$this->db->insert('kartu_aktiva',$kartu);
			$this->updateAset(array('nilaibuku'=>$data['nilaibuku'],'hargabeli'=>$data['hargabeli'],'akumulasipenyusutan'=>$data['akumulasipenyusutan'],'penyusutan'=>$data['penyusutan'],'penyusutanbulanan'=>$data['penyusutanbulanan']),array('aset_id'=>$data['aset_id']));

			//jurnal

		}
	}

	public function getKartuStoks($data=array()){
		$sql="SELECT * FROM ".DB_PREFIX."kartu_aktiva  WHERE aset_id='".$data['aset_id']."' ";
		if(!empty($data['tanggal'])){
			$sql .=" AND DATE(tanggal)='".$data['tanggal']."' ";
		}
		$sql.="ORDER BY id ";
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



	public function getTotalKartustoks($data=array()){
		$sql="SELECT * FROM ".DB_PREFIX."kartu_aktiva WHERE aset_id='".$data['aset_id']."' ";
		if(!empty($data['tanggal'])){
			$sql .=" AND DATE(tanggal)='".$data['tanggal']."' ";
		}
		$sql .="ORDER BY id ";

		$query = $this->db->query($sql);

		return $query->num_rows;
	}
}
?>
