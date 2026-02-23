<?php
class ModelCatalogVendorimport extends Model {
	// baru 8 Juli 2020
	public function hutang($customer_id){
		$d=$this->db->query("SELECT sum(totaltagihan-totalbayar) as hutang FROM invoice_pembelian_import WHERE vendor_id='$customer_id' and status<>3");
		return $d->row['hutang'];
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
			'email'	=>$this->db->escape($data['email']),
			'npwp'	=>$this->db->escape($data['npwp']),
			'telephone'	=>$this->db->escape($data['telephone']),
			'hutang'	=>0,
			'deposit'	=> 0,
			'hapus'	=>0
		);
		$this->db->insert('vendorimport',$vendor);
	}
	public function updateVendor($data,$where=array()){
	$this->db->update('vendorimport',$data,$where);
	}
	public function getVendor($where){
		return $this->db->first('vendorimport',$where);
	}
	public function getVendors($where,$order,$limit,$offset){
		return $this->db->all('vendorimport',$where,$order,$limit,$offset);
	}
	public function totalVendors($where){
		return $this->db->count('vendorimport',$where);
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
		$this->db->insert('hutang_vendorimport',$hutang);
	}
	public function getHutang($where){
		return $this->db->first('hutang_vendorimport',$where);
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
		$this->updateHutang($data['vendor_id'],$hutang,2);

	}

	/*Deposit*/

	public function updateDeposit($id,$penj,$jenis){
		$data=$this->getVendor(array('id'=>$id));

		//update qty


		$total=$this->db->query("SELECT COALESCE((SUM(saldomasuk)-SUM(saldokeluar)),0) as total FROM history_depositvendor_import WHERE vendor_id='".$id."'");
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
		'kurs'	=> $data['kurs'],
		'date_added'	=> date('Y-m-d H:i:s'),
		'date_modified' => date('Y-m-d H:i:s'),
		'no_dokumen'	=> $data['no_dokumen'],
		'urlref'	=> $data['urlref'],
		'idref'	=> $data['idref']
	);
	$this->db->insert('history_depositvendor_import',$hutang);
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
		'kurs'	=> $data['kurs'],
		'no_dokumen'	=> $data['no_dokumen'],
		'idref'	=> $data['ref'],
		'urlref'	=> 'pembelian/pembayarandepositimport'
	);
	$id=$this->addHistoryDeposit($hutang);

	$this->load->model('keuangan/bank');
	$this->load->model('keuangan/jurnal');
	$b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $data['bank_id']));
	$saldoawal=$b['saldo'];
	$saldo=$b['saldo'] - ($data['nominal']*$data['kurs']) -$data['biaya_lain'];
	$this->model_keuangan_bank->editBank(array('saldo'  => $saldo),array('id'=> $data['bank_id']));

	
	$detail=array();
	$detail[]=array(
		'ref_akun'  => '1311',
		'keterangan'  => $this->db->escape('Uang Muka Pembelian'),
		'debet' => ($data['nominal']*$data['kurs'])+$data['pendapatan_lain'],
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
				'kredit' => $data['nominal']*$data['kurs'],
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
					'kredit' => $data['nominal'] * $data['kurs'],
					'debet'  => 0,
					'urutan'  => 2,
				);
			}
		}
	}else{
		$detail[]=array(
			'ref_akun'  => $b['rek_parent'],
			'keterangan'  => $this->db->escape('Uang Muka Pembelian'),
			'kredit' => ($data['nominal'] * $data['kurs'])+$data['biaya_lain'],
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
		'keterangan' => 'Deposit Pembelian ke Vendor '.$cust['name'],
		'ref' => $id,
		'type' => 2000,
		'details' => $detail,
		'no_dokumen'	=> $data['no_dokumen'],
		'idref'	=> $data['ref'],
		'urlref'	=> 'pembelian/pembayarandepositimport',
	);
	$jurnal_id=$this->model_keuangan_jurnal->addJurnalUmum($jurnal);

	$aruskas=array(
		'date_added'	=> date('Y-m-d H:i:s'),
		'date_trans'	=> $data['date_trans'],
		'bank_id' => $data['bank_id'],
		'saldokeluar'  => ($data['nominal']*$data['kurs']),
		'saldomasuk' => 0,
		'saldoawal' => $b['saldo'],
		'saldoakhir'  => $saldo,
		'ref' => $id,
		'keterangan'  => 'Uang Muka Pembelian '.$cust['name'],
		'type'  => 2000,
		'ref_akun'  => '1311',
		'urlref'	=> 'pembelian/pembayarandepositimport',
		'no_dokumen'	=> $data['no_dokumen'],
		'idref'	=> $data['ref'],
		'jurnal_id'	=> $jurnal_id
	);

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
			'type' => 2001,
			'details' => $detail,
			'no_dokumen'	=> $data['no_dokumen'],
			'idref'	=> $data['ref'],
			'urlref'	=> 'pembelian/pembayarandepositimport',
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
			'type'  => 2001,
			'ref_akun'  => '6265',
			'urlref'	=> 'pembelian/pembayarandepositimport',
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
		'kurs'	=> $data['kurs'],
		'no_dokumen'	=> $data['no_dokumen'],
		'idref'	=> $data['ref'],
		'urlref'	=> 'pembelian/pembayarandepositimport'
	);
	$id=$this->addHistoryDeposit($hutang);

	$this->load->model('keuangan/bank');
	$this->load->model('keuangan/jurnal');

	$b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $data['bank_id']));
	$saldoawal=$b['saldo'];
	$saldo=$b['saldo'] + ($data['nominal']*$data['kurs'])+$data['biaya_lain'];
	$this->model_keuangan_bank->editBank(array('saldo'  => $saldo),array('id'=> $data['bank_id']));

	//$this->db->delete('aruskas',array('type'=>2000,'ref'=>$id));
	

	$detail=array();


	if($b['hutangprk'] == 1){
		if($saldoawal < 0){
			$detail[]=array(
				'ref_akun'  => '2001',
				'keterangan'  => $this->db->escape('Pembatalan Hutang PRK'),
				'debet' => $data['nominal']*$data['kurs'],
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
					'debet' => $data['nominal'] * $data['kurs'],
					'kredit'  => 0,
					'urutan'  => 1,
				);
			}
		}
	}else{
		$detail[]=array(
			'ref_akun'  => $b['rek_parent'],
			'keterangan'  => $this->db->escape('Pembatalan Uang Muka Pembelian'),
			'debet' => ($data['nominal'] * $data['kurs'])+$data['biaya_lain'],
			'kredit'  => 0,
			'urutan'  => 1,
		);
	}
	if($data['biaya_lain']>0){
		$detail[]=array(
		'ref_akun'  => '6299',
		'keterangan'  => $this->db->escape('Biaya lain-lain'),
		'debet' =>0,
		'kredit'  =>$data['biaya_lain'],
		'urutan'  =>2,
		);
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
		'kredit' =>($data['nominal']*$data['kurs'])+$data['pendapatan_lain'],
		'debet'  => 0,
		'urutan'  => 4,
	);

	$jurnal=array(
		'tanggal' => $data['date_trans'],
		'keterangan' => 'Pembatalan Deposit Pembelian ke Vendor '.$cust['name'],
		'ref' => $id,
		'type' => 2000,
		'details' => $detail,
		'no_dokumen'	=> $data['no_dokumen'],
		'idref'	=> $data['ref'],
		'urlref'	=> 'pembelian/pembayarandepositimport'
	);
	$jurnal_id=$this->model_keuangan_jurnal->addJurnalUmum($jurnal);
	$aruskas=array(
		'date_added'	=> date('Y-m-d H:i:s'),
		'date_trans'	=> $data['date_trans'],
		'bank_id' => $data['bank_id'],
		'saldokeluar'  => 0,
		'saldomasuk' => $data['nominal']*$data['kurs'],
		'saldoawal' => $b['saldo'],
		'saldoakhir'  => $saldo,
		'ref' => $id,
		'keterangan'  => 'Pembatalan Uang Muka Pembelian '.$cust['name'],
		'type'  => 2000,
		'ref_akun'  => '1311',
		'urlref'	=> 'pembelian/pembayarandepositimport',
		'no_dokumen'	=> $data['no_dokumen'],
		'idref'	=> $data['ref'],
		'jurnal_id'	=> $jurnal_id
	);

	$this->model_keuangan_bank->addAruskas($aruskas);




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
			'type' => 2001,
			'details' => $detail,
			'no_dokumen'	=> $data['no_dokumen'],
			'idref'	=> $data['ref'],
			'urlref'	=> 'pembelian/pembayarandepositimport'
		);

		$jurnal_id=$this->model_keuangan_jurnal->addJurnalUmum($jurnal);
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
			'type'  => 2001,
			'ref_akun'  => '6265',
			'urlref'	=> 'pembelian/pembayarandepositimport',
			'no_dokumen'	=> $data['no_dokumen'],
			'idref'	=> $data['ref'],
			'jurnal_id'	=> $jurnal_id
		);

		$this->model_keuangan_bank->addAruskas($aruskas);
	}
}

	public function getDeposits($customer_id,$data) {

			$sql="SELECT * FROM " . DB_PREFIX . "history_depositvendor_import WHERE vendor_id = '".$customer_id."' AND (hapus IS null OR hapus=0) ORDER BY date_trans DESC,id DESC";
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

			$sql="SELECT COUNT(*) as total FROM " . DB_PREFIX . "history_depositvendor_import WHERE vendor_id = '" . (int)$customer_id . "' AND (hapus IS null OR hapus=0) ";


			$query=$this->db->query($sql);

		return $query->row['total'];
	}


}
?>
