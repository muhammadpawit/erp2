<?php
class ModelCatalogVendorlokal extends Model {
	public function addhistorydepositVendor(){
		$insert=array(
			'vendor_id'=>$data['vendor_id'],
			'keterangan'=>$this->db->escape($data['keterangan']),
			'saldomasuk'=>$data['saldomasuk'],
			'saldokeluar'=>$data['saldokeluar'],
			'ref'=>0,
			'date_added'=>date('Y-m-d H:i:s'),
			'date_modified'=>date('Y-m-d H:i:s'),
			'hapus'=>0,
			'urlref'=>'vendorlokal/inserthistory',
		);
		$this->db->insert('history_depositvendor_lokal',$insert);
		$id=$this->db->getLastId();
		$no_dukumen="TMHDV-".date('Y-m').'-'.$this->user->getId().'-'.$id;
		$u=array(
			'no_dokumen'=>$no_dukumen,
			'ref'=>$ref,
			'idref'=>$id,
		);
		$this->db->update('history_depositvendor_lokal',$u,array('id'=>$id));
	}

	// baru 19 Agustus 2020
	public function getsaldo($data,$vendor_id){
		$saldo=0;
		$sql="SELECT SUM(saldomasuk-saldokeluar) as saldo FROM history_depositvendor_lokal WHERE vendor_id='$vendor_id' ";
		$sql.=" AND hapus=0 ";
		if(!empty($data['filter_date_start'])){
			$sql.=" AND DATE(date_trans) >='".$data['filter_date_start']."'  AND DATE(date_trans) <='".$data['filter_date_end']."' ";
		}
		$d=$this->db->query($sql);
		$saldo=$d->row['saldo'];
		/*
		$rows=$d->row['saldo'];
		if(empty($rows)){
			$st="SELECT DATE(date_trans) FROM history_depositvendor_lokal WHERE vendor_id='$vendor_id' ORDER BY date_trans DESC limit 1 ";
			$t=$this->db->query($st);
			$tgl=$t->row['date'];
			//$xsql="SELECT deposit as saldo FROM vendorlokal WHERE id='$vendor_id' ";
			$xsql="SELECT SUM(saldomasuk-saldokeluar) as saldo FROM history_depositvendor_lokal WHERE vendor_id='$vendor_id' ";
			if(!empty($tgl)){
				$xsql.=" AND hapus=0 AND DATE(date_trans) >= '$tgl'  ";
			}
			$x=$this->db->query($xsql);
			$saldo = $x->row['saldo'];
		}else{
			$saldo = $d->row['saldo'];
		}*/
		
		return $saldo;		
		
	}
	// end baru
	// baru 7 Juli 2020
	public function hutang($data,$customer_id){
		$hutang=0;
		$sql="SELECT sum(totaltagihan-totalbayar) as hutang FROM invoice_pembeliandagang WHERE vendor_id='$customer_id' and status<>3 ";
		if(!empty($data['filter_date_start'])){
			$sql.=" AND DATE(tglfaktur) >='".$data['filter_date_start']."'  AND DATE(tglfaktur) <='".$data['filter_date_end']."' ";
		}
		$d=$this->db->query($sql);
		if(!empty($d->row['hutang'])){
			return $d->row['hutang'];
		}else{
			$e=$this->db->query("SELECT sum(totaltagihan-totalbayar) as hutang FROM invoice_pembeliandagang WHERE vendor_id='$customer_id' and status<>3 ");
			return $e->row['hutang'];
		}
		
	}
	public function giro($customer_id){
		//$d=$this->db->query("SELECT sum(total-totalbayar) as hutang FROM pembayaran_deposit_lokal WHERE vendor_id='$customer_id' and status<>4 and status<>3");
		//return $d->row['hutang'];
	}
	// end baru
	public function addVendor($data){
		$vendor=array(
			'name'	=>$this->db->escape($data['name']),
			'alamat'	=>$this->db->escape($data['alamat']),
			'npwp'	=>$this->db->escape($data['npwp']),
			'telephone'	=>$this->db->escape($data['telephone']),
			'siup'	=>$this->db->escape($data['siup']),
			'tdp'	=>$this->db->escape($data['tdp']),
			'ho'	=>$this->db->escape($data['ho']),
			'sppkp'	=>$this->db->escape($data['sppkp']),
			'hutang'	=>0,
			'hapus'	=>0
		);
		$this->db->insert('vendorlokal',$vendor);
	}
	public function updateVendor($data,$where=array()){
	$this->db->update('vendorlokal',$data,$where);
	}
	public function getVendor($where){
		return $this->db->first('vendorlokal',$where);
	}
	public function getVendors($where,$order,$limit,$offset){
		return $this->db->all('vendorlokal',$where,$order,$limit,$offset);
	}
	public function totalVendors($where){
		return $this->db->count('vendorlokal',$where);
	}
	public function updateHutang($id,$hutang,$jenis){
		$data=$this->getVendor(array('id'=>$id));

		//update qty
		if($jenis == 1){
			$qtyf=$data['hutang'] + $hutang;
		}
		if($jenis == 2){
			$qtyf=$data['hutang'] - $hutang;
		}
		$this->updateVendor(array('hutang'=>$qtyf),array('id'	=> $id));
	  //$this->db->query("UPDATE ".DB_PREFIX."bahanbaku SET quantity='".$qtyf."' WHERE id='".$id."'");
	}

	public function addHutang($data){
		$hutang=array(
			'ref'=> $data['ref'],
			'tanggal'	=> $data['tanggal'],
			'total_pembelian'	=> $data['total_pembelian'],
			'total_hutang'	=> $data['total_hutang'],
			'jatuhtempo'	=> $data['jatuhtempo'],
			'hapus'	=> 0,
			'vendor_id'=> $data['vendor_id']
		);
		$this->db->insert('hutang_vendorlokal',$hutang);
	}
	public function getHutang($where){
		return $this->db->first('hutang_vendorlokal',$where);
	}
	public function updateDetailHutang($ref,$hutang,$jenis){
		$data=$this->getHutang(array('ref'=>$ref));

		//update qty
		if($jenis == 1){
			$qtyf=$data['total_hutang'] + $hutang;
		}
		if($jenis == 2){
			$qtyf=$data['total_hutang'] - $hutang;
		}
		$this->db->update('hutang_vendorlokal',array('total_hutang'=>$qtyf),array('ref'=>$ref));
		$this->updateHutang($data['vendor_id'],$hutang,$jenis);

	}

	//Deposit
	/*Deposit*/

	public function updateDeposit($id,$penj,$jenis){
		$data=$this->getVendor(array('id'=>$id));

		//update qty


		$total=$this->db->query("SELECT COALESCE((SUM(saldomasuk)-SUM(saldokeluar)),0) as total FROM history_depositvendor_lokal WHERE vendor_id='".$id."'");
		if($jenis == 1){
			//$qtyf=$data['deposit'] + $penj;
			$qtyf=$total->row['total'] + $penj;
		}
		if($jenis == 2){
			//$qtyf=$data['deposit'] - $penj;
			$qtyf=$total->row['total'] - $penj;
		}
		$this->updateVendor(array('deposit'=>$qtyf),array('id'	=> $id));
		//$this->db->query("UPDATE ".DB_PREFIX."bahanbaku SET quantity='".$qtyf."' WHERE id='".$id."'");


	}

public function addHistoryDeposit($data){
	$hutang=array(
		'ref'=> $data['ref'],
		'date_trans'	=> $data['date_trans'],
		'saldomasuk'	=> $data['saldomasuk'],
		'saldokeluar'	=> $data['saldokeluar'],
		'keterangan'	=> $this->db->escape($data['keterangan']),
		'hapus'	=> 0,
		'vendor_id'=> $data['vendor_id'],
		//'kurs'	=> $data['kurs'],
		'date_added'	=> date('Y-m-d H:i:s'),
		'date_modified' => date('Y-m-d H:i:s'),
		'no_dokumen'	=> $data['no_dokumen'],
		'urlref'	=> $data['urlref'],
		'idref'	=> $data['idref']
	);
	$this->db->insert('history_depositvendor_lokal',$hutang);
	$id=$this->db->getLastId();

	return $id;
}

public function addDeposit($data,$customer_id){
	$this->updateDeposit($customer_id,$data['nominal']+$data['pendapatan_lain'],1);
	$cust=$this->getVendor(array('id'	=> $customer_id));

	if(!isset($data['biaya_bank'])){
		$data['biaya_bank']=0;
	}

	$hutang=array(
		'ref'=> isset($data['ref'])?$data['ref']:0,
		'date_trans'	=> $data['date_trans'],
		'saldomasuk'	=> $data['nominal']+$data['pendapatan_lain'],
		'saldokeluar'	=> 0,
		'keterangan'	=> $this->db->escape($data['keterangan'].' untuk Supplier '.$cust['name']),
		'hapus'	=> 0,
		'vendor_id'=> $customer_id,
		'no_dokumen'	=> $data['no_dokumen'],
		'idref'	=> $data['ref'],
		'urlref'	=> 'pembelian/pembayarandepositkredit'

	);
	$id=$this->addHistoryDeposit($hutang);

	$this->load->model('keuangan/bank');
	$this->load->model('keuangan/jurnal');
	$b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $data['bank_id']));
	$saldoawal=$b['saldo'];
	$saldo=$b['saldo'] - $data['nominal'] - $data['biaya_lain'];
	$this->model_keuangan_bank->editBank(array('saldo'  => $saldo),array('id'=> $data['bank_id']));

	
	$detail=array();
	$detail[]=array(
		'ref_akun'  => '1311',
		'keterangan'  => $this->db->escape('Uang Muka Pembelian'),
		'debet' => $data['nominal']+$data['pendapatan_lain'],
		'kredit'  => 0,
		'urutan'  => 1,
	);

	if($data['biaya_lain']>0){
		$detail[]=array(
			'ref_akun'  => '6299',
			'keterangan'  => $this->db->escape('Biaya lain-lain'),
			'debet' => $data['biaya_lain'],
			'kredit'  => 0,
			'urutan'  => 2,
		);
	}

	if($b['hutangprk'] == 1){
		if($saldoawal < 0){
			$detail[]=array(
				'ref_akun'  => '2001',
				'keterangan'  => $this->db->escape('Hutang PRK'),
				'kredit' => $data['nominal'],
				'debet'  => 0,
				'urutan'  => 2,
			);
		}else{
			if($saldo < 0){
				//misal saldoawal 3000
				//nominal 5000
				//saldo akhir -2000
				$hutangprk=abs(-$saldo);
				$detail[]=array(
					'ref_akun'  => $b['rek_parent'],
					'keterangan'  => $this->db->escape('Uang Muka Pembelian'),
					'kredit' => $saldoawal,
					'debet'  => 0,
					'urutan'  => 2,
				);
				$detail[]=array(
					'ref_akun'  => '2001',
					'keterangan'  => $this->db->escape('Hutang PRK'),
					'kredit' => $hutangprk,
					'debet'  => 0,
					'urutan'  => 3,
				);
			}else{
				$detail[]=array(
					'ref_akun'  => $b['rek_parent'],
					'keterangan'  => $this->db->escape('Uang Muka Pembelian'),
					'kredit' => $data['nominal'],
					'debet'  => 0,
					'urutan'  => 2,
				);
			}
		}
	}else{
		$detail[]=array(
			'ref_akun'  => $b['rek_parent'],
			'keterangan'  => $this->db->escape('Uang Muka Pembelian'),
			'kredit' => $data['nominal']+$data['biaya_lain'],
			'debet'  => 0,
			'urutan'  => 2,
		);
		if($data['pendapatan_lain']>0){
			$detail[]=array(
				'ref_akun'  => '7003',
				'keterangan'  => $this->db->escape('Pendapatan lain-lain'),
				'debet' =>0,
				'kredit'  =>$data['pendapatan_lain'],
				'urutan'  => 4,
			);
		}
	}

	$jurnal=array(
		'tanggal' => $data['date_trans'],
		'keterangan' => 'Deposit Pembelian ke Vendor '.$cust['name'].' dengan keterangan '.$data['keterangan'],
		'ref' => $id,
		'type' => 2009,
		'no_dokumen'	=> $data['no_dokumen'],
		'idref'	=> $data['ref'],
		'urlref'	=> 'pembelian/pembayarandepositkredit',
		'details' => $detail
	);
	$jurnal_id=$this->model_keuangan_jurnal->addJurnalUmum($jurnal);

	$aruskas=array(
		'date_added'	=> date('Y-m-d H:i:s'),
		'date_trans'	=> $data['date_trans'],
		'bank_id' => $data['bank_id'],
		'saldokeluar'  => ($data['nominal']+$data['biaya_lain']),
		'saldomasuk' => 0,
		'saldoawal' => $b['saldo'],
		'saldoakhir'  => $saldo,
		'ref' => $id,
		'keterangan'  => 'Uang Muka Pembelian '.$cust['name'],
		'type'  => 2009,
		'ref_akun'  => '1311',
		'urlref'	=> 'pembelian/pembayarandepositkredit',
		'no_dokumen'	=> $data['no_dokumen'],
		'idref'	=> $data['ref'],
		'jurnal_id'	=> $jurnal_id
		
	);

	/*urlref,idref,jurnal_id */

	$this->model_keuangan_bank->addAruskas($aruskas);

	if($data['biaya_bank'] > 0){
		$b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $data['bank_id']));
		$saldoawal=$b['saldo'];
		$saldo=$b['saldo'] - $data['biaya_bank'];
		$this->model_keuangan_bank->editBank(array('saldo'  => $saldo),array('id'=> $data['bank_id']));

		

		$detail=array();
		$detail[]=array(
			'ref_akun'  => '6265',
			'keterangan'  => $this->db->escape('Biaya Administrasi Bank Uang Muka Pembelian'),
			'debet' => $data['biaya_bank'],
			'kredit'  => 0,
			'urutan'  => 1,
		);

		if($b['hutangprk'] == 1){
			if($saldoawal < 0){
				$detail[]=array(
					'ref_akun'  => '2001',
					'keterangan'  => $this->db->escape('Hutang PRK'),
					'kredit' => $data['biaya_bank'],
					'debet'  => 0,
					'urutan'  => 2,
				);
			}else{
				if($saldo < 0){
					//misal saldoawal 3000
					//nominal 5000
					//saldo akhir -2000
					$hutangprk=abs(-$saldo);
					$detail[]=array(
						'ref_akun'  => $b['rek_parent'],
						'keterangan'  => $this->db->escape('Biaya Administrasi Uang Muka Pembelian'),
						'kredit' => $saldoawal,
						'debet'  => 0,
						'urutan'  => 2,
					);
					$detail[]=array(
						'ref_akun'  => '2001',
						'keterangan'  => $this->db->escape('Hutang PRK'),
						'kredit' => $hutangprk,
						'debet'  => 0,
						'urutan'  => 3,
					);
				}else{
					$detail[]=array(
						'ref_akun'  => $b['rek_parent'],
						'keterangan'  => $this->db->escape('Biaya Administrasi Uang Muka Pembelian'),
						'kredit' => $data['biaya_bank'],
						'debet'  => 0,
						'urutan'  => 2,
					);
				}
			}
		}else{
			$detail[]=array(
				'ref_akun'  => $b['rek_parent'],
				'keterangan'  => $this->db->escape('Biaya Administrasi Uang Muka Pembelian'),
				'kredit' => $data['biaya_bank'],
				'debet'  => 0,
				'urutan'  => 2,
			);
		}

		$jurnal=array(
			'tanggal' => $data['date_trans'],
			'keterangan' => 'Biaya Administrasi Deposit Pembelian ke Vendor',
			'ref' => $id,
			'type' => 2011,
			'no_dokumen'	=> $data['no_dokumen'],
			'idref'	=> $data['ref'],
			'urlref'	=> 'pembelian/pembayarandepositkredit',
			'details' => $detail
		);

		$jurnal_id=$this->model_keuangan_jurnal->addJurnalUmum($jurnal);
		$aruskas=array(
			'date_added'	=> date('Y-m-d H:i:s'),
			'date_trans'	=> $data['date_trans'],
			'bank_id' => $data['bank_id'],
			'saldokeluar'  => $data['biaya_bank'],
			'saldomasuk' => 0,
			'saldoawal' => $b['saldo'],
			'saldoakhir'  => $saldo,
			'ref' => $id,
			'keterangan'  => 'Biaya Administrasi Bank ',
			'type'  => 2011,
			'ref_akun'  => '6265',
			'urlref'	=> 'pembelian/pembayarandepositkredit',
			'no_dokumen'	=> $data['no_dokumen'],
			'idref'	=> $data['ref'],
			'jurnal_id'	=> $jurnal_id
		);

		$this->model_keuangan_bank->addAruskas($aruskas);
	}

}
public function cancelDeposit($data,$customer_id){
	if(!isset($data['biaya_bank'])){
		$data['biaya_bank']=0;
	}
	$this->updateDeposit($customer_id,$data['nominal']+$data['pendapatan_lain'],2);
	$cust=$this->getVendor(array('id'	=> $customer_id));
	$hutang=array(
		'ref'=> isset($data['ref'])?$data['ref']:0,
		'date_trans'	=> $data['date_trans'],
		'saldomasuk'	=> 0,
		'saldokeluar'	=> $data['nominal']+$data['pendapatan_lain'],
		'keterangan'	=> $this->db->escape('Pembatalan '.$data['keterangan'].' untuk Vendor '.$cust['name']),
		'hapus'	=> 0,
		'vendor_id'=> $customer_id,
		'no_dokumen'	=> $data['no_dokumen'],
		'idref'	=> $data['ref'],
		'urlref'	=> 'pembelian/pembayarandepositkredit'

	);
	$id=$this->addHistoryDeposit($hutang);

	$this->load->model('keuangan/bank');
	$this->load->model('keuangan/jurnal');

	$b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $data['bank_id']));
	$saldoawal=$b['saldo'];
	$saldo=$b['saldo'] + $data['nominal']+$data['biaya_lain'];
	$this->model_keuangan_bank->editBank(array('saldo'  => $saldo),array('id'=> $data['bank_id']));

	//$this->db->delete('aruskas',array('type'=>2000,'ref'=>$id));
	

	$detail=array();


	if($b['hutangprk'] == 1){
		if($saldoawal < 0){
			$detail[]=array(
				'ref_akun'  => '2001',
				'keterangan'  => $this->db->escape('Pembatalan Hutang PRK'),
				'debet' => $data['nominal'],
				'kredit'  => 0,
				'urutan'  => 1,
			);
		}else{
			if($saldo < 0){
				//misal saldoawal 3000
				//nominal 5000
				//saldo akhir -2000
				$hutangprk=abs(-$saldo);
				$detail[]=array(
					'ref_akun'  => $b['rek_parent'],
					'keterangan'  => $this->db->escape('Pembatalan Uang Muka Pembelian'),
					'debet' => $saldoawal,
					'kredit'  => 0,
					'urutan'  => 1,
				);
				$detail[]=array(
					'ref_akun'  => '2001',
					'keterangan'  => $this->db->escape('Pembatalan Hutang PRK'),
					'debet' => $hutangprk,
					'kredit'  => 0,
					'urutan'  => 2,
				);
			}else{
				$detail[]=array(
					'ref_akun'  => $b['rek_parent'],
					'keterangan'  => $this->db->escape('Pembatalan Uang Muka Pembelian'),
					'debet' => $data['nominal'],
					'kredit'  => 0,
					'urutan'  => 1,
				);
			}
		}
	}else{
		$detail[]=array(
			'ref_akun'  => $b['rek_parent'],
			'keterangan'  => $this->db->escape('Pembatalan Uang Muka Pembelian'),
			'debet' => $data['nominal']+$data['biaya_lain'],
			'kredit'  => 0,
			'urutan'  => 1,
		);
		if($data['biaya_lain']>0){
			$detail[]=array(
			'ref_akun'  => '6299',
			'keterangan'  => $this->db->escape('Biaya lain-lain'),
			'debet' =>0,
			'kredit'  =>$data['biaya_lain'],
			'urutan'  =>2,
			);
		}
	}
	if($data['pendapatan_lain']>0){
		$detail[]=array(
		'ref_akun'  => '7003',
		'keterangan'  => $this->db->escape('Pendapatan lain-lain'),
		'debet' =>$data['pendapatan_lain'],
		'kredit'  =>0,
		'urutan'  =>3,
		);
	}
	$detail[]=array(
		'ref_akun'  => '1311',
		'keterangan'  => $this->db->escape('Pembatalan Uang Muka Pembelian'),
		'kredit' => $data['nominal']+$data['pendapatan_lain'],
		'debet'  => 0,
		'urutan'  => 4,
	);

	$jurnal=array(
		'tanggal' => $data['date_trans'],
		'keterangan' => 'Pembatalan Deposit Pembelian ke Vendor '.$cust['name'],
		'ref' => $id,
		'type' => 2009,
		'details' => $detail,
		'no_dokumen'	=> $data['no_dokumen'],
		'idref'	=> $data['ref'],
		'urlref'	=> 'pembelian/pembayarandepositkredit'
		
	);
	$jurnal_id=$this->model_keuangan_jurnal->addJurnalUmum($jurnal);

	$aruskas=array(
		'date_added'	=> date('Y-m-d H:i:s'),
		'date_trans'	=> $data['date_trans'],
		'bank_id' => $data['bank_id'],
		'saldokeluar'  => 0,
		'saldomasuk' => $data['nominal'],
		'saldoawal' => $b['saldo'],
		'saldoakhir'  => $saldo,
		'ref' => $id,
		'keterangan'  => 'Pembatalan Uang Muka Pembelian '.$cust['name'],
		'type'  => 2009,
		'ref_akun'  => '1311',
		'urlref'	=> 'pembelian/pembayarandepositkredit',
		'no_dokumen'	=> $data['no_dokumen'],
		'idref'	=> $data['ref'],
		'jurnal_id'	=> $jurnal_id
	);

	$this->model_keuangan_bank->addAruskas($aruskas);

	/*$j=$this->db->first('jurnal_umum',array('type'=>2000,'ref'=>$id));

	if(!empty($j)){
		$this->db->delete('jurnal_umum_detail',array('jurnal_id'=>$j['id']));
		$this->db->delete('jurnal_umum',array('id'=>$j['id']));
	}*/




	if($data['biaya_bank'] > 0){
		$b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $data['bank_id']));
		$saldoawal=$b['saldo'];
		$saldo=$b['saldo'] + ($data['biaya_bank']);
		$this->model_keuangan_bank->editBank(array('saldo'  => $saldo),array('id'=> $data['bank_id']));

		

		//$this->db->delete('aruskas',array('type'=>2001,'ref'=>$id));

		$detail=array();


		if($b['hutangprk'] == 1){
			if($saldoawal < 0){
				$detail[]=array(
					'ref_akun'  => '2001',
					'keterangan'  => $this->db->escape('Pembatalan Hutang PRK'),
					'debet' => $data['biaya_bank'],
					'kredit'  => 0,
					'urutan'  => 1,
				);
			}else{
				if($saldo < 0){
					//misal saldoawal 3000
					//nominal 5000
					//saldo akhir -2000
					$hutangprk=abs(-$saldo);
					$detail[]=array(
						'ref_akun'  => $b['rek_parent'],
						'keterangan'  => $this->db->escape('Pembatalan Biaya Administrasi Uang Muka Pembelian'),
						'debet' => $saldoawal,
						'kredit'  => 0,
						'urutan'  => 1,
					);
					$detail[]=array(
						'ref_akun'  => '2001',
						'keterangan'  => $this->db->escape('Pembatalan Hutang PRK'),
						'debet' => $hutangprk,
						'kredit'  => 0,
						'urutan'  => 2,
					);
				}else{
					$detail[]=array(
						'ref_akun'  => $b['rek_parent'],
						'keterangan'  => $this->db->escape('Pembatalan Biaya Administrasi Uang Muka Pembelian'),
						'debet' => $data['biaya_bank'],
						'kredit'  => 0,
						'urutan'  => 1,
					);
				}
			}
		}else{
			$detail[]=array(
				'ref_akun'  => $b['rek_parent'],
				'keterangan'  => $this->db->escape('Pembatalan Biaya Administrasi Uang Muka Pembelian'),
				'debet' => $data['biaya_bank'],
				'kredit'  => 0,
				'urutan'  => 1,
			);
		}
		$detail[]=array(
			'ref_akun'  => '6265',
			'keterangan'  => $this->db->escape('Pembatalan Biaya Administrasi Bank Uang Muka Pembelian'),
			'kredit' => $data['biaya_bank'],
			'debet'  => 0,
			'urutan'  => 3,
		);

		$jurnal=array(
			'tanggal' => $data['date_trans'],
			'keterangan' => 'Pembatalan Biaya Administrasi Deposit Pembelian ke Vendor',
			'ref' => $id,
			'type' => 2011,
			'details' => $detail,
			'no_dokumen'	=> $data['no_dokumen'],
			'idref'	=> $data['ref'],
			'urlref'	=> 'pembelian/pembayarandepositkredit'
		);

		$this->model_keuangan_jurnal->addJurnalUmum($jurnal);
		$aruskas=array(
			'date_added'	=> date('Y-m-d H:i:s'),
			'date_trans'	=> $data['date_trans'],
			'bank_id' => $data['bank_id'],
			'saldokeluar'  => 0,
			'saldomasuk' => $data['biaya_bank'],
			'saldoawal' => $saldoawal,
			'saldoakhir'  => $saldo,
			'ref' => $id,
			'keterangan'  => 'Pembatalan Biaya Administrasi Bank '.$cust['name'],
			'type'  => 2011,
			'ref_akun'  => '6265',
			'urlref'	=> 'pembelian/pembayarandepositkredit',
			'no_dokumen'	=> $data['no_dokumen'],
			'idref'	=> $data['ref'],
			'jurnal_id'	=> $jurnal_id
		);

		$this->model_keuangan_bank->addAruskas($aruskas);


	}
}

	public function getDeposits($customer_id,$data) {

			$sql="SELECT * FROM " . DB_PREFIX . "history_depositvendor_lokal WHERE vendor_id = '".$customer_id."' AND (hapus IS null OR hapus=0) ORDER BY date_trans DESC,id DESC";
			if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
		}

			$query=$this->db->query($sql);

		return $query->rows;
	}
	public function getTotalDeposits($customer_id,$data) {

			$sql="SELECT COUNT(*) as total FROM " . DB_PREFIX . "history_depositvendor_lokal WHERE vendor_id = '" . (int)$customer_id . "' AND (hapus IS null OR hapus=0) ";


			$query=$this->db->query($sql);

		return $query->row['total'];
	}

}
?>
