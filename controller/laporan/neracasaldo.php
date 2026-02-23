<?php
class ControllerLaporanNeracasaldo extends Controller {
	public function index() {
		$this->document->setTitle('Neraca Saldo');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date("Y-m-d", strtotime("first day of previous month"));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date("Y-m-d", strtotime("last day of previous month"));
		}



		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}




		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		//pendapatan web
		/*$pw=$this->model_report_laporanlabarugi->pendapatanWeb($filter);

		//pendapatan toko
		$pt=$this->model_report_laporanlabarugi->pendapatanToko($filter);
		//pendapatan pameran
		$pp=$this->model_report_laporanlabarugi->pendapatanPameran($filter);
		//print_r($results);

		//netcost
		$nw=$this->model_report_laporanlabarugi->netcostWebsite($filter);
		$np=$this->model_report_laporanlabarugi->netcostPameran($filter);
		$np=$this->model_report_laporanlabarugi->netcostToko($filter);

		//return penjualan
		$rw=$this->model_report_laporanlabarugi->returnPenjualan($filter);
		$kaskeluar=abs($this->model_report_laporanlabarugi->biaya(2,$filter));
		$penyesuaian=$this->model_report_laporanlabarugi->biaya(4,$filter);
		$biayaadmin=abs($this->model_report_laporanlabarugi->biaya(5,$filter));
		$lainlain=$this->model_report_laporanlabarugi->biaya(8,$filter);

		$biayasewa=abs($this->model_report_laporanlabarugi->biaya(16,$filter));
		$biayapameran=abs($this->model_report_laporanlabarugi->biaya(19,$filter));
		$biayatoko=abs($this->model_report_laporanlabarugi->biaya(18,$filter));
		$biayagaji=abs($this->model_report_laporanlabarugi->biaya(20,$filter));
		$biayaedc=abs($this->model_report_laporanlabarugi->biaya(25,$filter));

		$totaldebet=$pw['total']+$pp['total']+$pt['total'];
		$totalkredit=$rw['total']+$kaskeluar+$biayaadmin+$biayasewa+$biayapameran+$biayatoko+$biayagaji+$biayaedc+$nw['net_cost']+$np['net_cost']+$nt['net_cost']+$pw['voucher']+$pp['voucher']+$pt['voucher']+$pw['diskonglobal']+$pp['diskonpaket']+$pw['diskon']+$pp['diskon']+$pt['diskon'];

		if($penyesuaian < 0){
			$totalkredit += abs($penyesuaian);
		}else{
			$totaldebet += abs($penyesuaian);
		}

		if($lainlain < 0){
			$totalkredit += abs($lainlain);
		}else{
			$totaldebet += abs($lainlain);
		}

		$labarugi=$totaldebet - $totalkredit;

*/

	//pendapatan
	$this->load->model('keuangan/jurnal');
	$this->load->model('keuangan/coa');

	$this->data['pendapatan']=array();
	$filter=array(
		'filter_date_start'	=> $filter_date_start,
		'filter_date_end'	=> $filter_date_end

	);

	//filterawal
	$filterawal=array(
		'filter_date_end'	=> date('Y-m-d',strtotime($filter_date_start.' -1 day'))
	);

	$filterakhir=array(
		'filter_date_end'	=> $filter_date_end
	);

	//aset
	$data = array(
		'filter_type'	=> 1,

	);

	//aset awal
	$asetawal=$this->model_keuangan_coa->getAllCategories($data);
	$totalasetawal=0;
	$totalasetberjalan=0;
	$totalasetakhir=0;
	foreach($asetawal as $p){
		//saldoawal
		$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

		$saldo=$debet-$kredit;

		//saldoberjalan
		$debetberjalan=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
		$kreditberjalan=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

		$saldoberjalan=$debetberjalan-$kreditberjalan;

		//saldoakhir
		$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
		$kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

		$saldoakhir=$debetakhir-$kreditakhir;


		if($debet != 0 | $kredit !=0 | $debetberjalan != 0 | $kreditberjalan != 0 | $debetakhir !=0 |$kreditakhir != 0 ){
			$this->data['aset'][]=array(
				'name'        => $p['name'],
				'kode_rek'	=> $p['kode_rek'],
				'parent_id'	=> $p['parent_id'],
				'saldoawal'	=> $this->currency->format($saldo),
				'debetawal'	=>$this->currency->format($debet),
				'kreditawal'	=>$this->currency->format($kredit),
				'saldoberjalan'	=> $this->currency->format($saldoberjalan),
				'debetberjalan'	=>$this->currency->format($debetberjalan),
				'kreditberjalan'	=>$this->currency->format($kreditberjalan),
				'saldoakhir'	=> $this->currency->format($saldoakhir),
				'debetakhir'	=>$this->currency->format($debetakhir),
				'kreditakhir'	=>$this->currency->format($kreditakhir),
			);
		}else{
			if($p['parent_id'] == 0){
				$this->data['aset'][]=array(
					'name'        => $p['name'],
					'kode_rek'	=> $p['kode_rek'],
					'parent_id'	=> $p['parent_id'],
					'saldoawal'	=> $this->currency->format($saldo),
					'debetawal'	=>$this->currency->format($debet),
					'kreditawal'	=>$this->currency->format($kredit),
					'saldoberjalan'	=> $this->currency->format($saldoberjalan),
					'debetberjalan'	=>$this->currency->format($debetberjalan),
					'kreditberjalan'	=>$this->currency->format($kreditberjalan),
					'saldoakhir'	=> $this->currency->format($saldoakhir),
					'debetakhir'	=>$this->currency->format($debetakhir),
					'kreditakhir'	=>$this->currency->format($kreditakhir),
				);
			}
		}
		$totalasetawal += $saldo;
		$totalasetberjalan += $saldoberjalan;
		$totalasetakhir += $saldoakhir;
	}
	$this->data['asetawal']=$this->currency->format($totalasetawal);
	$this->data['asetberjalan']=$this->currency->format($totalasetberjalan);
	$this->data['asetakhir']=$this->currency->format($totalasetakhir);

	//hutang
	$this->data['hutang']=array();
	$data = array(
		'filter_type'	=> 2,

	);
	$hutangawal=$this->model_keuangan_coa->getAllCategories($data);
	$totalhutangawal=0;
	$totalhutangberjalan=0;
	$totalhutangakhir=0;
	foreach($hutangawal as $p){
		//saldoawal
		$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

		$saldo=$kredit-$debet;

		//saldoberjalan
		$debetberjalan=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
		$kreditberjalan=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

		$saldoberjalan=$kreditberjalan-$debetberjalan;

		//saldoakhir
		$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
		$kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

		$saldoakhir=$kreditakhir-$debetakhir;


		if($debet != 0 | $kredit !=0 | $debetberjalan != 0 | $kreditberjalan != 0 | $debetakhir !=0 |$kreditakhir != 0 ){

		$this->data['hutang'][]=array(
			'name'        => $p['name'],
			'kode_rek'	=> $p['kode_rek'],
			'parent_id'	=> $p['parent_id'],
			'saldoawal'	=> $this->currency->format($saldo),
			'debetawal'	=>$this->currency->format($debet),
			'kreditawal'	=>$this->currency->format($kredit),
			'saldoberjalan'	=> $this->currency->format($saldoberjalan),
			'debetberjalan'	=>$this->currency->format($debetberjalan),
			'kreditberjalan'	=>$this->currency->format($kreditberjalan),
			'saldoakhir'	=> $this->currency->format($saldoakhir),
			'debetakhir'	=>$this->currency->format($debetakhir),
			'kreditakhir'	=>$this->currency->format($kreditakhir),
		);
		}
		else{
			if($p['parent_id'] == 0){
				$this->data['hutang'][]=array(
					'name'        => $p['name'],
					'kode_rek'	=> $p['kode_rek'],
					'parent_id'	=> $p['parent_id'],
					'saldoawal'	=> $this->currency->format($saldo),
					'debetawal'	=>$this->currency->format($debet),
					'kreditawal'	=>$this->currency->format($kredit),
					'saldoberjalan'	=> $this->currency->format($saldoberjalan),
					'debetberjalan'	=>$this->currency->format($debetberjalan),
					'kreditberjalan'	=>$this->currency->format($kreditberjalan),
					'saldoakhir'	=> $this->currency->format($saldoakhir),
					'debetakhir'	=>$this->currency->format($debetakhir),
					'kreditakhir'	=>$this->currency->format($kreditakhir),
				);
			}
		}
		$totalhutangawal += $saldo;
		$totalhutangberjalan += $saldoberjalan;
		$totalhutangakhir += $saldoakhir;
	}
	$this->data['hutangawal']=$this->currency->format($totalhutangawal);
	$this->data['hutangberjalan']=$this->currency->format($totalhutangberjalan);
	$this->data['hutangakhir']=$this->currency->format($totalhutangakhir);

	//modal
	$this->data['modal']=array();
	$data = array(
		'filter_type'	=> 3,

	);
	$modalawal=$this->model_keuangan_coa->getAllCategories($data);
	$totalmodalawal=0;
	$totalmodalberjalan=0;
	$totalmodalakhir=0;
	foreach($modalawal as $p){
		//saldoawal
		$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

		$saldo=$kredit-$debet;

		//saldoberjalan
		$debetberjalan=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
		$kreditberjalan=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

		$saldoberjalan=$kreditberjalan-$debetberjalan;

		//saldoakhir
		$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
		$kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

		$saldoakhir=$kreditakhir-$debetakhir;


		if($debet != 0 | $kredit !=0 | $debetberjalan != 0 | $kreditberjalan != 0 | $debetakhir !=0 |$kreditakhir != 0 ){

		$this->data['modal'][]=array(
			'name'        => $p['name'],
			'kode_rek'	=> $p['kode_rek'],
			'parent_id'	=> $p['parent_id'],
			'saldoawal'	=> $this->currency->format($saldo),
			'debetawal'	=>$this->currency->format($debet),
			'kreditawal'	=>$this->currency->format($kredit),
			'saldoberjalan'	=> $this->currency->format($saldoberjalan),
			'debetberjalan'	=>$this->currency->format($debetberjalan),
			'kreditberjalan'	=>$this->currency->format($kreditberjalan),
			'saldoakhir'	=> $this->currency->format($saldoakhir),
			'debetakhir'	=>$this->currency->format($debetakhir),
			'kreditakhir'	=>$this->currency->format($kreditakhir),
		);
		}
		else{
			if($p['parent_id'] == 0){
				$this->data['modal'][]=array(
					'name'        => $p['name'],
					'kode_rek'	=> $p['kode_rek'],
					'parent_id'	=> $p['parent_id'],
					'saldoawal'	=> $this->currency->format($saldo),
					'debetawal'	=>$this->currency->format($debet),
					'kreditawal'	=>$this->currency->format($kredit),
					'saldoberjalan'	=> $this->currency->format($saldoberjalan),
					'debetberjalan'	=>$this->currency->format($debetberjalan),
					'kreditberjalan'	=>$this->currency->format($kreditberjalan),
					'saldoakhir'	=> $this->currency->format($saldoakhir),
					'debetakhir'	=>$this->currency->format($debetakhir),
					'kreditakhir'	=>$this->currency->format($kreditakhir),
				);
			}
		}
		$totalmodalawal += $saldo;
		$totalmodalberjalan += $saldoberjalan;
		$totalmodalakhir += $saldoakhir;
	}
	$this->data['modalawal']=$this->currency->format($totalmodalawal);
	$this->data['modalberjalan']=$this->currency->format($totalmodalberjalan);
	$this->data['modalakhir']=$this->currency->format($totalmodalakhir);

	//pendapatan
	$this->data['pendapatan']=array();
	$data = array(
		'filter_type'	=> 4,

	);
	$pendapatanawal=$this->model_keuangan_coa->getAllCategories($data);
	$totalpendapatanawal=0;
	$totalpendapatanberjalan=0;
	$totalpendapatanakhir=0;
	foreach($pendapatanawal as $p){
		//saldoawal
		$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

		$saldo=$kredit-$debet;

		//saldoberjalan
		$debetberjalan=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
		$kreditberjalan=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

		$saldoberjalan=$kreditberjalan-$debetberjalan;

		//saldoakhir
		$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
		$kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

		$saldoakhir=$kreditakhir-$debetakhir;


		if($debet != 0 | $kredit !=0 | $debetberjalan != 0 | $kreditberjalan != 0 | $debetakhir !=0 |$kreditakhir != 0 ){

		$this->data['pendapatan'][]=array(
			'name'        => $p['name'],
			'kode_rek'	=> $p['kode_rek'],
			'parent_id'	=> $p['parent_id'],
			'saldoawal'	=> $this->currency->format($saldo),
			'debetawal'	=>$this->currency->format($debet),
			'kreditawal'	=>$this->currency->format($kredit),
			'saldoberjalan'	=> $this->currency->format($saldoberjalan),
			'debetberjalan'	=>$this->currency->format($debetberjalan),
			'kreditberjalan'	=>$this->currency->format($kreditberjalan),
			'saldoakhir'	=> $this->currency->format($saldoakhir),
			'debetakhir'	=>$this->currency->format($debetakhir),
			'kreditakhir'	=>$this->currency->format($kreditakhir),
		);
		}
		else{
			if($p['parent_id'] == 0){
				$this->data['pendapatan'][]=array(
					'name'        => $p['name'],
					'kode_rek'	=> $p['kode_rek'],
					'parent_id'	=> $p['parent_id'],
					'saldoawal'	=> $this->currency->format($saldo),
					'debetawal'	=>$this->currency->format($debet),
					'kreditawal'	=>$this->currency->format($kredit),
					'saldoberjalan'	=> $this->currency->format($saldoberjalan),
					'debetberjalan'	=>$this->currency->format($debetberjalan),
					'kreditberjalan'	=>$this->currency->format($kreditberjalan),
					'saldoakhir'	=> $this->currency->format($saldoakhir),
					'debetakhir'	=>$this->currency->format($debetakhir),
					'kreditakhir'	=>$this->currency->format($kreditakhir),
				);
			}
		}
		$totalpendapatanawal += $saldo;
		$totalpendapatanberjalan += $saldoberjalan;
		$totalpendapatanakhir += $saldoakhir;
	}
	$this->data['pendapatanawal']=$this->currency->format($totalmodalawal);
	$this->data['pendapatanberjalan']=$this->currency->format($totalmodalberjalan);
	$this->data['pendapatanakhir']=$this->currency->format($totalmodalakhir);

	//hpp
	$this->data['hpp']=array();
	$data = array(
		'filter_type'	=> 5,

	);
	$hpp=$this->model_keuangan_coa->getAllCategories($data);
	$totalhppawal=0;
	$totalhppberjalan=0;
	$totalhppakhir=0;
	foreach($hpp as $p){
		//saldoawal
		$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

		$saldo=$kredit-$debet;

		//saldoberjalan
		$debetberjalan=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
		$kreditberjalan=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

		$saldoberjalan=$kreditberjalan-$debetberjalan;

		//saldoakhir
		$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
		$kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

		$saldoakhir=$kreditakhir-$debetakhir;


		if($debet != 0 | $kredit !=0 | $debetberjalan != 0 | $kreditberjalan != 0 | $debetakhir !=0 |$kreditakhir != 0 ){

		$this->data['hpp'][]=array(
			'name'        => $p['name'],
			'kode_rek'	=> $p['kode_rek'],
			'parent_id'	=> $p['parent_id'],
			'saldoawal'	=> $this->currency->format($saldo),
			'debetawal'	=>$this->currency->format($debet),
			'kreditawal'	=>$this->currency->format($kredit),
			'saldoberjalan'	=> $this->currency->format($saldoberjalan),
			'debetberjalan'	=>$this->currency->format($debetberjalan),
			'kreditberjalan'	=>$this->currency->format($kreditberjalan),
			'saldoakhir'	=> $this->currency->format($saldoakhir),
			'debetakhir'	=>$this->currency->format($debetakhir),
			'kreditakhir'	=>$this->currency->format($kreditakhir),
		);
		}
		else{
			if($p['parent_id'] == 0){
				$this->data['hpp'][]=array(
					'name'        => $p['name'],
					'kode_rek'	=> $p['kode_rek'],
					'parent_id'	=> $p['parent_id'],
					'saldoawal'	=> $this->currency->format($saldo),
					'debetawal'	=>$this->currency->format($debet),
					'kreditawal'	=>$this->currency->format($kredit),
					'saldoberjalan'	=> $this->currency->format($saldoberjalan),
					'debetberjalan'	=>$this->currency->format($debetberjalan),
					'kreditberjalan'	=>$this->currency->format($kreditberjalan),
					'saldoakhir'	=> $this->currency->format($saldoakhir),
					'debetakhir'	=>$this->currency->format($debetakhir),
					'kreditakhir'	=>$this->currency->format($kreditakhir),
				);
			}
		}
		$totalhppawal += $saldo;
		$totalhppberjalan += $saldoberjalan;
		$totalhppakhir += $saldoakhir;
	}
	$this->data['hppawal']=$this->currency->format($totalmodalawal);
	$this->data['hppberjalan']=$this->currency->format($totalmodalberjalan);
	$this->data['hppakhir']=$this->currency->format($totalmodalakhir);

	//beban
	$this->data['beban']=array();
	$data = array(
		'filter_type'	=> 6,

	);
	$beban=$this->model_keuangan_coa->getAllCategories($data);
	$totalbebanawal=0;
	$totalbebanberjalan=0;
	$totalbebanakhir=0;
	foreach($beban as $p){
		//saldoawal
		$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

		$saldo=$kredit-$debet;

		//saldoberjalan
		$debetberjalan=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
		$kreditberjalan=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

		$saldoberjalan=$kreditberjalan-$debetberjalan;

		//saldoakhir
		$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
		$kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

		$saldoakhir=$kreditakhir-$debetakhir;


		if($debet != 0 | $kredit !=0 | $debetberjalan != 0 | $kreditberjalan != 0 | $debetakhir !=0 |$kreditakhir != 0 ){

		$this->data['beban'][]=array(
			'name'        => $p['name'],
			'kode_rek'	=> $p['kode_rek'],
			'parent_id'	=> $p['parent_id'],
			'saldoawal'	=> $this->currency->format($saldo),
			'debetawal'	=>$this->currency->format($debet),
			'kreditawal'	=>$this->currency->format($kredit),
			'saldoberjalan'	=> $this->currency->format($saldoberjalan),
			'debetberjalan'	=>$this->currency->format($debetberjalan),
			'kreditberjalan'	=>$this->currency->format($kreditberjalan),
			'saldoakhir'	=> $this->currency->format($saldoakhir),
			'debetakhir'	=>$this->currency->format($debetakhir),
			'kreditakhir'	=>$this->currency->format($kreditakhir),
		);
		}
		else{
			if($p['parent_id'] == 0){
				$this->data['beban'][]=array(
					'name'        => $p['name'],
					'kode_rek'	=> $p['kode_rek'],
					'parent_id'	=> $p['parent_id'],
					'saldoawal'	=> $this->currency->format($saldo),
					'debetawal'	=>$this->currency->format($debet),
					'kreditawal'	=>$this->currency->format($kredit),
					'saldoberjalan'	=> $this->currency->format($saldoberjalan),
					'debetberjalan'	=>$this->currency->format($debetberjalan),
					'kreditberjalan'	=>$this->currency->format($kreditberjalan),
					'saldoakhir'	=> $this->currency->format($saldoakhir),
					'debetakhir'	=>$this->currency->format($debetakhir),
					'kreditakhir'	=>$this->currency->format($kreditakhir),
				);
			}
		}
		$totalbebanawal += $saldo;
		$totalbebanberjalan += $saldoberjalan;
		$totalbebanakhir += $saldoakhir;
	}
	$this->data['bebanawal']=$this->currency->format($totalmodalawal);
	$this->data['bebanberjalan']=$this->currency->format($totalmodalberjalan);
	$this->data['bebanakhir']=$this->currency->format($totalmodalakhir);

	//beban lain
	$this->data['bebanlain']=array();
	$data = array(
		'filter_type'	=> 7,

	);
	$bebanlain=$this->model_keuangan_coa->getAllCategories($data);
	$totalbebanlainawal=0;
	$totalbebanlainberjalan=0;
	$totalbebanlainakhir=0;
	foreach($bebanlain as $p){
		//saldoawal
		$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

		$saldo=$kredit-$debet;

		//saldoberjalan
		$debetberjalan=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
		$kreditberjalan=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

		$saldoberjalan=$kreditberjalan-$debetberjalan;

		//saldoakhir
		$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
		$kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

		$saldoakhir=$kreditakhir-$debetakhir;


		if($debet != 0 | $kredit !=0 | $debetberjalan != 0 | $kreditberjalan != 0 | $debetakhir !=0 |$kreditakhir != 0 ){

		$this->data['bebanlain'][]=array(
			'name'        => $p['name'],
			'kode_rek'	=> $p['kode_rek'],
			'parent_id'	=> $p['parent_id'],
			'saldoawal'	=> $this->currency->format($saldo),
			'debetawal'	=>$this->currency->format($debet),
			'kreditawal'	=>$this->currency->format($kredit),
			'saldoberjalan'	=> $this->currency->format($saldoberjalan),
			'debetberjalan'	=>$this->currency->format($debetberjalan),
			'kreditberjalan'	=>$this->currency->format($kreditberjalan),
			'saldoakhir'	=> $this->currency->format($saldoakhir),
			'debetakhir'	=>$this->currency->format($debetakhir),
			'kreditakhir'	=>$this->currency->format($kreditakhir),
		);
		}
		else{
			if($p['parent_id'] == 0){
				$this->data['bebanlain'][]=array(
					'name'        => $p['name'],
					'kode_rek'	=> $p['kode_rek'],
					'parent_id'	=> $p['parent_id'],
					'saldoawal'	=> $this->currency->format($saldo),
					'debetawal'	=>$this->currency->format($debet),
					'kreditawal'	=>$this->currency->format($kredit),
					'saldoberjalan'	=> $this->currency->format($saldoberjalan),
					'debetberjalan'	=>$this->currency->format($debetberjalan),
					'kreditberjalan'	=>$this->currency->format($kreditberjalan),
					'saldoakhir'	=> $this->currency->format($saldoakhir),
					'debetakhir'	=>$this->currency->format($debetakhir),
					'kreditakhir'	=>$this->currency->format($kreditakhir),
				);
			}
		}
		$totalbebanlainawal += $saldo;
		$totalbebanlainberjalan += $saldoberjalan;
		$totalbebanlainakhir += $saldoakhir;
	}
	$this->data['bebanlainawal']=$this->currency->format($totalmodalawal);
	$this->data['bebanlainberjalan']=$this->currency->format($totalmodalberjalan);
	$this->data['bebanlainakhir']=$this->currency->format($totalmodalakhir);

	//pendapatan lain
	$this->data['pendapatanlain']=array();
	$data = array(
		'filter_type'	=> 8,

	);
	$pendapatanlain=$this->model_keuangan_coa->getAllCategories($data);
	$totalpendapatanlainawal=0;
	$totalpendapatanlainberjalan=0;
	$totalpendapatanlainakhir=0;
	foreach($pendapatanlain as $p){
		//saldoawal
		$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

		$saldo=$kredit-$debet;

		//saldoberjalan
		$debetberjalan=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
		$kreditberjalan=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

		$saldoberjalan=$kreditberjalan-$debetberjalan;

		//saldoakhir
		$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
		$kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

		$saldoakhir=$kreditakhir-$debetakhir;


		if($debet != 0 | $kredit !=0 | $debetberjalan != 0 | $kreditberjalan != 0 | $debetakhir !=0 |$kreditakhir != 0 ){

		$this->data['pendapatanlain'][]=array(
			'name'        => $p['name'],
			'kode_rek'	=> $p['kode_rek'],
			'parent_id'	=> $p['parent_id'],
			'saldoawal'	=> $this->currency->format($saldo),
			'debetawal'	=>$this->currency->format($debet),
			'kreditawal'	=>$this->currency->format($kredit),
			'saldoberjalan'	=> $this->currency->format($saldoberjalan),
			'debetberjalan'	=>$this->currency->format($debetberjalan),
			'kreditberjalan'	=>$this->currency->format($kreditberjalan),
			'saldoakhir'	=> $this->currency->format($saldoakhir),
			'debetakhir'	=>$this->currency->format($debetakhir),
			'kreditakhir'	=>$this->currency->format($kreditakhir),
		);
		}
		else{
			if($p['parent_id'] == 0){
				$this->data['pendapatanlain'][]=array(
					'name'        => $p['name'],
					'kode_rek'	=> $p['kode_rek'],
					'parent_id'	=> $p['parent_id'],
					'saldoawal'	=> $this->currency->format($saldo),
					'debetawal'	=>$this->currency->format($debet),
					'kreditawal'	=>$this->currency->format($kredit),
					'saldoberjalan'	=> $this->currency->format($saldoberjalan),
					'debetberjalan'	=>$this->currency->format($debetberjalan),
					'kreditberjalan'	=>$this->currency->format($kreditberjalan),
					'saldoakhir'	=> $this->currency->format($saldoakhir),
					'debetakhir'	=>$this->currency->format($debetakhir),
					'kreditakhir'	=>$this->currency->format($kreditakhir),
				);
			}
		}
		$totalpendapatanlainawal += $saldo;
		$totalpendapatanlainberjalan += $saldoberjalan;
		$totalpendapatanlainakhir += $saldoakhir;
	}
	$this->data['pendapatanlainawal']=$this->currency->format($totalmodalawal);
	$this->data['pendapatanlainberjalan']=$this->currency->format($totalmodalberjalan);
	$this->data['pendapatanlainakhir']=$this->currency->format($totalmodalakhir);

	//pendapatan luar biasa
	$this->data['pendapatanluar']=array();
	$data = array(
		'filter_type'	=> 9,

	);
	$pendapatanluar=$this->model_keuangan_coa->getAllCategories($data);
	$totalpendapatanluarawal=0;
	$totalpendapatanluarberjalan=0;
	$totalpendapatanluarakhir=0;
	foreach($pendapatanluar as $p){
		//saldoawal
		$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

		$saldo=$kredit-$debet;

		//saldoberjalan
		$debetberjalan=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
		$kreditberjalan=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

		$saldoberjalan=$kreditberjalan-$debetberjalan;

		//saldoakhir
		$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
		$kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

		$saldoakhir=$kreditakhir-$debetakhir;


		if($debet != 0 | $kredit !=0 | $debetberjalan != 0 | $kreditberjalan != 0 | $debetakhir !=0 |$kreditakhir != 0 ){

		$this->data['pendapatanluar'][]=array(
			'name'        => $p['name'],
			'kode_rek'	=> $p['kode_rek'],
			'parent_id'	=> $p['parent_id'],
			'saldoawal'	=> $this->currency->format($saldo),
			'debetawal'	=>$this->currency->format($debet),
			'kreditawal'	=>$this->currency->format($kredit),
			'saldoberjalan'	=> $this->currency->format($saldoberjalan),
			'debetberjalan'	=>$this->currency->format($debetberjalan),
			'kreditberjalan'	=>$this->currency->format($kreditberjalan),
			'saldoakhir'	=> $this->currency->format($saldoakhir),
			'debetakhir'	=>$this->currency->format($debetakhir),
			'kreditakhir'	=>$this->currency->format($kreditakhir),
		);
		}
		else{
			if($p['parent_id'] == 0){
				$this->data['pendapatanluar'][]=array(
					'name'        => $p['name'],
					'kode_rek'	=> $p['kode_rek'],
					'parent_id'	=> $p['parent_id'],
					'saldoawal'	=> $this->currency->format($saldo),
					'debetawal'	=>$this->currency->format($debet),
					'kreditawal'	=>$this->currency->format($kredit),
					'saldoberjalan'	=> $this->currency->format($saldoberjalan),
					'debetberjalan'	=>$this->currency->format($debetberjalan),
					'kreditberjalan'	=>$this->currency->format($kreditberjalan),
					'saldoakhir'	=> $this->currency->format($saldoakhir),
					'debetakhir'	=>$this->currency->format($debetakhir),
					'kreditakhir'	=>$this->currency->format($kreditakhir),
				);
			}
		}
		$totalpendapatanluarawal += $saldo;
		$totalpendapatanluarberjalan += $saldoberjalan;
		$totalpendapatanluarakhir += $saldoakhir;
	}

	$this->data['pendapatanluarawal']=$this->currency->format($totalmodalawal);
	$this->data['pendapatanluarberjalan']=$this->currency->format($totalmodalberjalan);
	$this->data['pendapatanluarakhir']=$this->currency->format($totalmodalakhir);

	$this->data['token'] = $this->session->data['token'];

	$totaldebetawal=$this->model_keuangan_jurnal->totalDebet(0,$filterawal);
	$totalkreditawal=$this->model_keuangan_jurnal->totalKredit(0,$filterawal);

	$totaldebetberjalan=$this->model_keuangan_jurnal->totalDebet(0,$filter);
	$totalkreditberjalan=$this->model_keuangan_jurnal->totalKredit(0,$filter);

	$totaldebetakhir=$this->model_keuangan_jurnal->totalDebet(0,$filterakhir);
	$totalkreditakhir=$this->model_keuangan_jurnal->totalKredit(0,$filterakhir);

	$this->data['totaldebetawal']=$this->currency->format($totaldebetawal);
	$this->data['totalkreditawal']=$this->currency->format($totalkreditawal);

	$this->data['totaldebetberjalan']=$this->currency->format($totaldebetberjalan);
	$this->data['totalkreditberjalan']=$this->currency->format($totalkreditberjalan);

	$this->data['totaldebetakhir']=$this->currency->format($totaldebetakhir);
	$this->data['totalkreditakhir']=$this->currency->format($totalkreditakhir);

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;

		$this->template = 'laporan/neraca.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
}
?>
