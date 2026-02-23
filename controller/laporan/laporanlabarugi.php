<?php
class ControllerLaporanLaporanlabarugi extends Controller {
	public function index() {
		$this->document->setTitle('Laporan Laba Rugi');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = null;
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = null;
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

	if(!is_null($filter_date_start)  && !is_null($filter_date_end)){

		$filter=array(
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end
		);



	//pendapatan
	$this->load->model('keuangan/jurnal');
	$this->load->model('keuangan/coa');

	$this->data['pendapatan']=array();
	$data = array(
		'filter_type'	=> 4,

	);
	$pendapatan=$this->model_keuangan_coa->getAllCategories($data);
	$totalpendapatan=0;
	foreach($pendapatan as $p){
		/*$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

		$saldo=$kredit-$debet;*/
		$saldo=$this->model_keuangan_jurnal->totalsumkredit($p['kode_rek'],$filter);
		$this->data['pendapatan'][]=array(
			'name'        => $p['name'],
			'kode_rek'	=> $p['kode_rek'],
			'parent_id'	=> $p['parent_id'],
			'saldo'	=> $this->currency->format(abs($saldo)),
			'plainsaldo'	=> $saldo
		);
		$totalpendapatan += $saldo;
	}
	$this->data['totalpendapatan']=$this->currency->format($totalpendapatan);

	//hpp
	$this->data['hpp']=array();
	$data = array(
		'filter_type'	=> 5,

	);
	$hpp=$this->model_keuangan_coa->getAllCategories($data);
	$totalhpp=0;
	foreach($hpp as $p){
		/*$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

		$saldo=$debet-$kredit;*/
		if($p['kode_rek'] == '7002'){
			$saldo=$this->model_keuangan_jurnal->totalsumdebet($p['kode_rek'],$filter);
			$this->data['hpp'][]=array(
				'name'        => $p['name'],
				'kode_rek'	=> $p['kode_rek'],
				'parent_id'	=> $p['parent_id'],
				'saldo'	=> $this->currency->format($saldo),
				'plainsaldo'	=> $saldo
			);
		}else{
			$saldo=$this->model_keuangan_jurnal->totalsumdebet($p['kode_rek'],$filter);
			$this->data['hpp'][]=array(
				'name'        => $p['name'],
				'kode_rek'	=> $p['kode_rek'],
				'parent_id'	=> $p['parent_id'],
				'saldo'	=> $this->currency->format(abs($saldo)),
				'plainsaldo'	=> $saldo
			);
		}
		
		$totalhpp += $saldo;
	}
	$this->data['totalhpp']=$this->currency->format($totalhpp);
	$labakotor=$totalpendapatan-$totalhpp;

	$this->data['labakotor']=$this->currency->format($labakotor);

	//biayaoperasional
	$this->data['biaya']=array();
	$data = array(
		'filter_type'	=> 6,

	);
	$biaya=$this->model_keuangan_coa->getAllCategories($data);
	$totalbiaya=0;
	foreach($biaya as $p){
		/*$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

		$saldo=$debet-$kredit;*/
		$saldo=$this->model_keuangan_jurnal->totalsumdebet($p['kode_rek'],$filter);
		$this->data['biaya'][]=array(
			'name'        => $p['name'],
			'kode_rek'	=> $p['kode_rek'],
			'parent_id'	=> $p['parent_id'],
			'saldo'	=> $this->currency->format(abs($saldo)),
			'plainsaldo'	=> $saldo
		);
		$totalbiaya += $saldo;
	}
	$this->data['totalbiaya']=$this->currency->format($totalbiaya);
	if($this->user->getUsername()=="pawits"){
		echo "<pre>";print_r($this->data['biaya']);exit;
	}
	//beban lain-lain
	$this->data['biayalain']=array();
	$data = array(
		'filter_type'	=> 7,

	);
	$biayalain=$this->model_keuangan_coa->getAllCategories($data);
	$totalbiayalain=0;
	foreach($biayalain as $p){
		/*$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

		$saldo=$kredit-$debet;*/
		$saldo=$this->model_keuangan_jurnal->totalsumkredit($p['kode_rek'],$filter);
		$this->data['biayalain'][]=array(
			'name'        => $p['name'],
			'kode_rek'	=> $p['kode_rek'],
			'parent_id'	=> $p['parent_id'],
			'saldo'	=> $this->currency->format(abs($saldo)),
			'plainsaldo'	=> $saldo
		);
		$totalbiayalain += $saldo;
	}
	$this->data['totalbiayalain']=$this->currency->format($totalbiayalain);

	//pendapatan lain-lain
	$this->data['pendapatanlain']=array();
	$data = array(
		'filter_type'	=> 8,

	);
	$pendapatanlain=$this->model_keuangan_coa->getAllCategories($data);
	$totalpendapatanlain=0;
	foreach($pendapatanlain as $p){
		/*$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

		$saldo=$debet-$kredit;*/
		$saldo=$this->model_keuangan_jurnal->totalsumdebet($p['kode_rek'],$filter);
		$this->data['pendapatanlain'][]=array(
			'name'        => $p['name'],
			'kode_rek'	=> $p['kode_rek'],
			'parent_id'	=> $p['parent_id'],
			'saldo'	=> $this->currency->format(abs($saldo)),
			'plainsaldo'	=> $saldo
		);
		$totalpendapatanlain += $saldo;
	}
	$this->data['totalpendapatanlain']=$this->currency->format($totalpendapatanlain);

	//pendapatan luarbiasa
	$this->data['pendapatanluarbiasa']=array();
	$data = array(
		'filter_type'	=> 9,

	);
	$pendapatanluarbiasa=$this->model_keuangan_coa->getAllCategories($data);
	$totalpendapatanluarbiasa=0;
	foreach($pendapatanluarbiasa as $p){
		/*$debet=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filter);
		$kredit=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filter);

		$saldo=$kredit-$debet;*/
		$saldo=$this->model_keuangan_jurnal->totalsumkredit($p['kode_rek'],$filter);
		$this->data['pendapatanluarbiasa'][]=array(
			'name'        => $p['name'],
			'kode_rek'	=> $p['kode_rek'],
			'parent_id'	=> $p['parent_id'],
			'saldo'	=> $this->currency->format(abs($saldo)),
			'plainsaldo'	=> $saldo
		);
		$totalpendapatanluarbiasa += $saldo;
	}
	$this->data['totalpendapatanluarbiasa']=$this->currency->format($totalpendapatanluarbiasa);

	$labarugibersih=$labakotor - $totalbiaya + $totalbiayalain - $totalpendapatanlain + $totalpendapatanluarbiasa;
	$this->data['lababersih']=$this->currency->format($labarugibersih);


		

	}
	$this->data['token'] = $this->session->data['token'];
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;

		$this->template = 'laporan/laporanlabarugi.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
}
?>
