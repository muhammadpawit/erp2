<?php
class ControllerLaporanNeraca extends Controller {
	public function index() {
		$this->document->setTitle('Neraca');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date("Y-m-d", strtotime("first day of previous month"));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date("Y-m-d");
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



	//pendapatan
	$this->load->model('keuangan/jurnal');
	$this->load->model('keuangan/coa');

	//if(){}
	/*$tahun=explode("-",$filter_date_start);

	if($tahun[0] == '2018'){

	}*/

	$tahun=explode("-",$filter_date_start);
	$this->data['pendapatan']=array();

	//aset awal
	/*
	range tanggal awal tahun - tanggal awal
	*/

	if($tahun[0] > 2018){
		$filterawal=array(
			'filter_date_start'	=> $tahun[0].'-01-01',
			'filter_date_end'	=> $filter_date_start
		);
	}else{
		if($tahun[0] < 2018){
			$filterawal=array(
				'filter_date_start'	=> '2018-02-01',
				'filter_date_end'	=> '2018-02-28'
			);
			$tahun[1]=2;
		}else{
			if($tahun[1] < 3){
				$filterawal=array(
					'filter_date_start'	=> '2018-02-01',
					'filter_date_end'	=> '2018-02-28'
				);
				$tahun[1]=2;
			}else{
				$filterawal=array(
						'filter_date_start'	=> $tahun[0].'-03-01',
						'filter_date_end'	=> $filter_date_start
					);

			}
		}
	}


	$tahun2=explode("-",$filter_date_end);
	if($tahun2[0] > 2018){
		$filterakhir=array(
			'filter_date_start'	=> $tahun2[0].'-01-01',
			'filter_date_end'	=> $filter_date_end
		);
	}else{

		if($tahun2[0] < 2018){
			$filterakhir=array(
				'filter_date_start'	=> '2018-02-01',
				'filter_date_end'	=> '2018-02-28'
			);
			$tahun2[1]=2;
		}else{
			if($tahun2[1] < 3){
				$filterakhir=array(
					'filter_date_start'	=> '2018-02-01',
					'filter_date_end'	=> '2018-02-28'
				);
				$tahun2[1]=2;
			}else{
				$filterakhir=array(
						'filter_date_start'	=> $tahun2[0].'-03-01',
						'filter_date_end'	=> $filter_date_end
					);

			}
		}


	}

	//aset

	$data = array(
	  'filter_type'	=> 1,

	);

	//print_r($filterawal);

	$aset=$this->model_keuangan_coa->getAllCategories($data);
	$totalasetawal=0;
	$totalasetberjalan=0;
	$totalasetakhir=0;
	$this->data['aset']=array();
	foreach($aset as $p){
	  //$saldo=$p['debet']-$p['kredit'];

	  //saldoakhir
	//	if(){}
	  $debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
	  $kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

	  $saldoawal=$debetawal-$kreditawal;
		$saldo=$p['debet']-$p['kredit'];
		if($tahun[0] == '2018'){

			$saldoawal += $saldo;
			if($tahun[1] < 3){
				$saldoawal=$saldo;
			}

		}else{
			if($tahun[0] < 2018){
				$saldoawal=$saldo;
			}else{
				$filtertahunsebelumnya=array(
					'filter_date_start'	=> '2018-03-01',
					'filter_date_end'	=> ($tahun[0]-1).'-12-31'
				);

				$debettahunsebelumnya=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnya);
				$kredittahunsebelumnya=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnya);
				
				$saldoawal += $saldo +($debettahunsebelumnya - $kredittahunsebelumnya);


			}
		}
		//echo $p['kode_rek']." ".$debetawal." ".$kreditawal."\n";

		$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
	  $kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

	  $saldoakhir=$debetakhir-$kreditakhir;

		$saldo2=$p['debet']-$p['kredit'];
		if($tahun2[0] == '2018'){

			$saldoakhir += $saldo2;
			if($tahun2[1] < 3){
				$saldoakhir=$saldo2;
			}
		}else{
			if($tahun2[0] < 2018){
				$saldoakhir=$saldo2;
			}else{
				$filtertahunsebelumnya=array(
					'filter_date_start'	=> '2018-03-01',
					'filter_date_end'	=> ($tahun2[0]-1).'-12-31'
				);

				$debettahunsebelumnya=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnya);
				$kredittahunsebelumnya=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnya);
				
				$saldoakhir += $saldo2 +($debettahunsebelumnya - $kredittahunsebelumnya);


			}
		}


	  $this->data['aset'][$p['kode_rek']]=array(
	      'name'        => $p['name'],
	      'kode_rek'	=> $p['kode_rek'],
	      'parent_id'	=> $p['parent_id'],

	      'saldoakhir'	=> $this->currency->format($saldoakhir),
	      'debetakhir'	=>$this->currency->format($debetakhir),
	      'kreditakhir'	=>$this->currency->format($kreditakhir),
				'saldoawal'	=> $this->currency->format($saldoawal),
	      'debetawal'	=>$this->currency->format($debetawal),
	      'kreditawal'	=>$this->currency->format($kreditawal),
	    );

	  $totalasetawal += $saldoawal;
	 // $totalasetberjalan += $saldoberjalan;
	  $totalasetakhir += $saldoakhir;
	}
	//print_r($this->data['aset']);
	$this->data['asetawal']=$this->currency->format($totalasetawal);
//	$this->data['asetberjalan']=$this->currency->format($totalasetberjalan);
	$this->data['asetakhir']=$this->currency->format($totalasetakhir);

	$data = array(
	  'filter_type'	=> 2,

	);

	$hutang=$this->model_keuangan_coa->getAllCategories($data);
	$totalhutangawal=0;
	$totalhutangakhir=0;
	$this->data['hutang']=array();

	foreach($hutang as $p){
	  //$saldo=$p['debet']-$p['kredit'];

	  //saldoakhir
	  $debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
	  $kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

	  $saldoawal=$kreditawal-$debetawal;
		$saldo=$p['kredit']-$p['debet'];
		if($tahun[0] == '2018'){

			$saldoawal += $saldo;
			if($tahun[1] < 3){
				$saldoawal=$saldo;
			}
		}else{
			if($tahun[0] < 2018){
				$saldoawal=$saldo;
			}else{
				$filtertahunsebelumnya=array(
					'filter_date_start'	=> '2018-03-01',
					'filter_date_end'	=> ($tahun[0]-1).'-12-31'
				);

				$debettahunsebelumnya=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnya);
				$kredittahunsebelumnya=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnya);
				
				$saldoawal += $saldo +($kredittahunsebelumnya - $debettahunsebelumnya);


			}
		}

		$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
	  $kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

	  $saldoakhir=$kreditakhir-$debetakhir;

		$saldo2=$p['kredit']-$p['debet'];
		if($tahun2[0] == '2018'){

			$saldoakhir += $saldo2;
			if($tahun2[1] < 3){
				$saldoakhir=$saldo2;
			}
		}else{
			if($tahun2[0] < 2018){
				$saldoakhir=$saldo2;
			}else{
				$filtertahunsebelumnya=array(
					'filter_date_start'	=> '2018-03-01',
					'filter_date_end'	=> ($tahun2[0]-1).'-12-31'
				);

				$debettahunsebelumnya=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnya);
				$kredittahunsebelumnya=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnya);
				
				$saldoakhir += $saldo2 +($kredittahunsebelumnya - $debettahunsebelumnya);


			}
		}

	  $this->data['hutang'][]=array(
	      'name'        => $p['name'],
	      'kode_rek'	=> $p['kode_rek'],
	      'parent_id'	=> $p['parent_id'],
	      //'saldoawal'	=> $this->currency->format($saldo),
	      //'debetawal'	=>$this->currency->format($debet),
	      //'kreditawal'	=>$this->currency->format($kredit),
	      //'saldoberjalan'	=> $this->currency->format($saldoberjalan),
	      //'debetberjalan'	=>$this->currency->format($debetberjalan),
	      //'kreditberjalan'	=>$this->currency->format($kreditberjalan),
	      'saldoakhir'	=> $this->currency->format($saldoakhir),
	      'debetakhir'	=>$this->currency->format($debetakhir),
	      'kreditakhir'	=>$this->currency->format($kreditakhir),
				'saldoawal'	=> $this->currency->format($saldoawal),
	      'debetawal'	=>$this->currency->format($debetawal),
	      'kreditawal'	=>$this->currency->format($kreditawal),
	    );

	  $totalhutangawal += $saldoawal;
	 // $totalasetberjalan += $saldoberjalan;
	  $totalhutangakhir += $saldoakhir;
	}
	$this->data['hutangawal']=$this->currency->format($totalhutangawal);
//	$this->data['asetberjalan']=$this->currency->format($totalasetberjalan);
	$this->data['hutangakhir']=$this->currency->format($totalhutangakhir);

	//modal
	$data = array(
	  'filter_type'	=> 3,

	);

	$modal=$this->model_keuangan_coa->getAllCategories($data);
	$totalmodalawal=0;
	$totalmodalakhir=0;
	$this->data['modal']=array();

	foreach($modal as $p){
	  //$saldo=$p['debet']-$p['kredit'];

	  //saldoakhir

		$debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
	  $kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

		if($p['kode_rek'] !=  3102){
	  	$saldoawal=$kreditawal-$debetawal;
		}

		$saldo=$p['kredit']-$p['debet'];
		if($tahun[0] == '2018'){

			$saldoawal += $saldo;
			if($tahun[1] < 3){
				$saldoawal=$saldo;
			}
		}else{
			if($tahun[0] < 2018){
				$saldoawal=$saldo;
			}else{
				$filtertahunsebelumnya=array(
					'filter_date_start'	=> '2018-03-01',
					'filter_date_end'	=> ($tahun[0]-1).'-12-31'
				);

				$debettahunsebelumnya=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnya);
				$kredittahunsebelumnya=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnya);
				
				$saldoawal += $saldo +($kredittahunsebelumnya - $debettahunsebelumnya);


			}
		}

		$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
	  $kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);
		if($p['kode_rek'] !=  3102){
	  	$saldoakhir=$kreditakhir-$debetakhir;
		}

		$saldo2=$p['kredit']-$p['debet'];
		if($tahun2[0] == '2018'){

			$saldoakhir += $saldo2;
			if($tahun2[1] < 3){
				$saldoakhir=$saldo2;
			}
		}else{
			if($tahun2[0] < 2018){
				$saldoakhir=$saldo2;
			}else{
				$filtertahunsebelumnya=array(
					'filter_date_start'	=> '2018-03-01',
					'filter_date_end'	=> ($tahun2[0]-1).'-12-31'
				);

				$debettahunsebelumnya=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnya);
				$kredittahunsebelumnya=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnya);
				
				$saldoakhir += $saldo2 +($kredittahunsebelumnya - $debettahunsebelumnya);


			}
		}

		if($p['kode_rek'] !=  3102 /*& $p['kode_rek'] != '3101'*/){

		  $this->data['modal'][]=array(
		      'name'        => $p['name'],
		      'kode_rek'	=> $p['kode_rek'],
		      'parent_id'	=> $p['parent_id'],
		      //'saldoawal'	=> $this->currency->format($saldo),
		      //'debetawal'	=>$this->currency->format($debet),
		      //'kreditawal'	=>$this->currency->format($kredit),
		      //'saldoberjalan'	=> $this->currency->format($saldoberjalan),
		      //'debetberjalan'	=>$this->currency->format($debetberjalan),
		      //'kreditberjalan'	=>$this->currency->format($kreditberjalan),
		      'saldoakhir'	=> $this->currency->format($saldoakhir),
		      'debetakhir'	=>$this->currency->format($debetakhir),
		      'kreditakhir'	=>$this->currency->format($kreditakhir),
					'saldoawal'	=> $this->currency->format($saldoawal),
		      'debetawal'	=>$this->currency->format($debetawal),
		      'kreditawal'	=>$this->currency->format($kreditawal),
		    );
			}else if($p['kode_rek'] ==  3102){
				$datalabarugi=$p;
						$datalr = array(
				      'filter_type'	=> 4,

				    );
				    $pendapatan=$this->model_keuangan_coa->getAllCategories($datalr);
				    $totalpendapatanawal=0;
						$totalpendapatanakhir=0;
				    foreach($pendapatan as $p){
				      $debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
				      $kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

				      $saldopendapatanawal=$kreditawal-$debetawal;

							$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
				      $kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

				      $saldopendapatanakhir=$kreditakhir-$debetakhir;

				      $totalpendapatanawal += $saldopendapatanawal;
							$totalpendapatanakhir += $saldopendapatanakhir;
				    }
				    $datalr = array(
				      'filter_type'	=> 5,

				    );
						$hpp=$this->model_keuangan_coa->getAllCategories($datalr);
				    $totalhppawal=0;
						$totalhppakhir=0;
				    foreach($hpp as $p){
				      $debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
				      $kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

				      $saldohppawal=$debetawal-$kreditawal;

							$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
				      $kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

				      $saldohppakhir=$debetakhir-$kreditakhir;

				      $totalhppawal += $saldohppawal;
							$totalhppakhir += $saldohppakhir;
				    }
				    $labakotorawal=$totalpendapatanawal-$totalhppawal;
						$labakotorakhir=$totalpendapatanakhir-$totalhppakhir;

				    $datalr = array(
				      'filter_type'	=> 6,

				    );
						$biaya=$this->model_keuangan_coa->getAllCategories($datalr);
				    $totalbiayaawal=0;
						$totalbiayaakhir=0;
				    foreach($biaya as $p){
				      $debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
				      $kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

				      $saldobiayaawal=$debetawal-$kreditawal;

							$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
				      $kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

				      $saldobiayaakhir=$debetakhir-$kreditakhir;

				      $totalbiayaawal += $saldobiayaawal;
							$totalbiayaakhir += $saldobiayaakhir;
				    }

				    $datalr = array(
				      'filter_type'	=> 7,

				    );
						/*pendapatan lain*/
						$biayalain=$this->model_keuangan_coa->getAllCategories($datalr);
				    $totalbiayalainawal=0;
						$totalbiayalainakhir=0;
				    foreach($biayalain as $p){
				      $debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
				      $kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

				      $saldobiayalainawal=$kreditawal-$debetawal;

							$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
				      $kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

				      $saldobiayalainakhir=$kreditakhir-$debetakhir;

				      $totalbiayalainawal += $saldobiayalainawal;
							$totalbiayalainakhir += $saldobiayalainakhir;
				    }
				    $datalr = array(
				      'filter_type'	=> 8,

				    );
						/*biaya lain*/
						$pendapatanlain=$this->model_keuangan_coa->getAllCategories($datalr);
				    $totapendapatanlainawal=0;
						$totalpendapatanlainakhir=0;
				    foreach($pendapatanlain as $p){
				      $debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
				      $kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

				      $saldopendapatanlainawal=$debetawal-$kreditawal;

							$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
				      $kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

				      $saldopendapatanlainakhir=$debetakhir-$kreditakhir;

				      $totalpendapatanlainawal += $saldopendapatanlainawal;
							$totalpendapatanlainakhir += $saldopendapatanlainakhir;
				    }
				    $datalr = array(
				      'filter_type'	=> 9,

				    );
						$pendapatanluarbiasa=$this->model_keuangan_coa->getAllCategories($datalr);
				    $totapendapatanluarbiasaawal=0;
						$totalpendapatanluarbiasaakhir=0;
				    foreach($pendapatanluarbiasa as $p){
				      $debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
				      $kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

				      $saldopendapatanluarbiasaawal=$kreditawal-$debetawal;

							$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
				      $kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

				      $saldopendapatanluarbiasaakhir=$kreditakhir-$debetakhir;

				      $totalpendapatanluarbiasaawal += $saldopendapatanluarbiasaawal;
							$totalpendapatanluarbiasaakhir += $saldopendapatanluarbiasaakhir;
				    }

				    $labarugibersihawal=$labakotorawal- $totalbiayaawal - $totalpendapatanlainawal + $totalbiayalainawal + $totalpendapatanluarbiasaawal;
				    $saldoawal =$labarugibersihawal;

						$labarugibersihakhir=$labakotorakhir- $totalbiayaakhir - $totalpendapatanlainakhir + $totalbiayalainakhir + $totalpendapatanluarbiasaakhir;
				    $saldoakhir =$labarugibersihakhir;

				$saldo=$datalabarugi['kredit']-$datalabarugi['debet'];
				if($tahun[0] == '2018'){

					if($tahun[1] < 3){
							$saldoawal=$saldo;
						}
				}else{
					if($tahun[0] < 2018){
						$saldoawal=$saldo;
					}
				}
				$saldo2=$datalabarugi['kredit']-$datalabarugi['debet'];
				if($tahun2[0] == '2018'){

					if($tahun2[1] < 3){
							$saldoakhir=$saldo2;
						}
				}else{
					if($tahun2[0] < 2018){
						$saldoakhir=$saldo2;
					}
				}
				$this->data['modal'][]=array(
			      'name'        => 'Laba (rugi) Periode Berjalan',
			      'kode_rek'	=> 3102,
			      'parent_id'	=> 3100,
			      'saldoakhir'	=> $this->currency->format($saldoakhir),
			      'debetakhir'	=>$this->currency->format($debetakhir),
			      'kreditakhir'	=>$this->currency->format($kreditakhir),
						'saldoawal'	=> $this->currency->format($saldoawal),
			      'debetawal'	=>$this->currency->format($debetawal),
			      'kreditawal'	=>$this->currency->format($kreditawal),
			    );
			}/*else{
				$filtertahunsebelumnya=array(
					'filter_date_start'	=> '2018-03-01',
					'filter_date_end'	=> ($tahun[0]-1).'-12-31'
				);

				$filtertahunsebelumnyaakhir=array(
					'filter_date_start'	=> '2018-03-01',
					'filter_date_end'	=> ($tahun2[0]-1).'-12-31'
				);

				$datalabarugi=$p;
						$datalr = array(
				      'filter_type'	=> 4,

				    );
				    $pendapatan=$this->model_keuangan_coa->getAllCategories($datalr);
				    $totalpendapatanawal=0;
						$totalpendapatanakhir=0;
				    foreach($pendapatan as $p){
				      $debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnya);
				      $kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnya);

				      $saldopendapatanawal=$kreditawal-$debetawal;

							$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnyaakhir);
				      $kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnyaakhir);

				      $saldopendapatanakhir=$kreditakhir-$debetakhir;

				      $totalpendapatanawal += $saldopendapatanawal;
							$totalpendapatanakhir += $saldopendapatanakhir;
				    }
				    $datalr = array(
				      'filter_type'	=> 5,

				    );
						$hpp=$this->model_keuangan_coa->getAllCategories($datalr);
				    $totalhppawal=0;
						$totalhppakhir=0;
				    foreach($hpp as $p){
				      $debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnya);
				      $kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnya);

				      $saldohppawal=$debetawal-$kreditawal;

							$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnyaakhir);
				      $kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnyaakhir);

				      $saldohppakhir=$debetakhir-$kreditakhir;

				      $totalhppawal += $saldohppawal;
							$totalhppakhir += $saldohppakhir;
				    }
				    $labakotorawal=$totalpendapatanawal-$totalhppawal;
						$labakotorakhir=$totalpendapatanakhir-$totalhppakhir;

				    $datalr = array(
				      'filter_type'	=> 6,

				    );
						$biaya=$this->model_keuangan_coa->getAllCategories($datalr);
				    $totalbiayaawal=0;
						$totalbiayaakhir=0;
				    foreach($biaya as $p){
				      $debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnya);
				      $kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnya);

				      $saldobiayaawal=$debetawal-$kreditawal;

							$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnyaakhir);
				      $kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnyaakhir);

				      $saldobiayaakhir=$debetakhir-$kreditakhir;

				      $totalbiayaawal += $saldobiayaawal;
							$totalbiayaakhir += $saldobiayaakhir;
				    }

				    $datalr = array(
				      'filter_type'	=> 7,

				    );
						
						$biayalain=$this->model_keuangan_coa->getAllCategories($datalr);
				    $totalbiayalainawal=0;
						$totalbiayalainakhir=0;
				    foreach($biayalain as $p){
				      $debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnya);
				      $kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnya);

				      $saldobiayalainawal=$kreditawal-$debetawal;

							$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnyaakhir);
				      $kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnyaakhir);

				      $saldobiayalainakhir=$kreditakhir-$debetakhir;

				      $totalbiayalainawal += $saldobiayalainawal;
							$totalbiayalainakhir += $saldobiayalainakhir;
				    }
				    $datalr = array(
				      'filter_type'	=> 8,

				    );
						
						$pendapatanlain=$this->model_keuangan_coa->getAllCategories($datalr);
				    $totapendapatanlainawal=0;
						$totalpendapatanlainakhir=0;
				    foreach($pendapatanlain as $p){
				      $debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnya);
				      $kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnya);

				      $saldopendapatanlainawal=$debetawal-$kreditawal;

							$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnyaakhir);
				      $kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnyaakhir);

				      $saldopendapatanlainakhir=$debetakhir-$kreditakhir;

				      $totalpendapatanlainawal += $saldopendapatanlainawal;
							$totalpendapatanlainakhir += $saldopendapatanlainakhir;
				    }
				    $datalr = array(
				      'filter_type'	=> 9,

				    );
						$pendapatanluarbiasa=$this->model_keuangan_coa->getAllCategories($datalr);
				    $totapendapatanluarbiasaawal=0;
						$totalpendapatanluarbiasaakhir=0;
				    foreach($pendapatanluarbiasa as $p){
				      $debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnya);
				      $kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnya);

				      $saldopendapatanluarbiasaawal=$kreditawal-$debetawal;

							$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filtertahunsebelumnyaakhir);
				      $kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filtertahunsebelumnyaakhir);

				      $saldopendapatanluarbiasaakhir=$kreditakhir-$debetakhir;

				      $totalpendapatanluarbiasaawal += $saldopendapatanluarbiasaawal;
							$totalpendapatanluarbiasaakhir += $saldopendapatanluarbiasaakhir;
				    }

				    $labarugibersihawal=$labakotorawal- $totalbiayaawal - $totalpendapatanlainawal + $totalbiayalainawal + $totalpendapatanluarbiasaawal;
				    $saldoawal =$labarugibersihawal;

						$labarugibersihakhir=$labakotorakhir- $totalbiayaakhir - $totalpendapatanlainakhir + $totalbiayalainakhir + $totalpendapatanluarbiasaakhir;
				    $saldoakhir =$labarugibersihakhir;

				$saldo=$datalabarugi['kredit']-$datalabarugi['debet'];

				$debetdevidenawal=$this->model_keuangan_jurnal->totalDebet('3103',$filtertahunsebelumnya);
	  		$kreditdevidenawal=$this->model_keuangan_jurnal->totalKredit('3103',$filtertahunsebelumnya);

				if($tahun[0] == '2018'){

					
					$saldoawal=$saldo;
				}else{
					if($tahun[0] < 2018){
						$saldoawal=$saldo;
					}else{
						$saldoawal += $saldo + ($kreditdevidenawal-$debetdevidenawal);
					}
				}


				$saldo2=$datalabarugi['kredit']-$datalabarugi['debet'];
				$debetdevidenakhir=$this->model_keuangan_jurnal->totalDebet('3103',$filtertahunsebelumnyaakhir);
	  		$kreditdevidenakhir =$this->model_keuangan_jurnal->totalKredit('3103',$filtertahunsebelumnyaakhir);

				if($tahun2[0] == '2018'){

					
					$saldoakhir=$saldo2;
				}else{
					if($tahun2[0] < 2018){
						$saldoakhir=$saldo2;
					}else{
						$saldoakhir += $saldo2 + ($kreditdevidenakhir-$debetdevidenakhir);
					}
				}
				$this->data['modal'][]=array(
			      'name'        => 'Laba (rugi) Ditahan',
			      'kode_rek'	=> 3101,
			      'parent_id'	=> 3100,
			      'saldoakhir'	=> $this->currency->format($saldoakhir),
			      'debetakhir'	=>$this->currency->format($debetakhir),
			      'kreditakhir'	=>$this->currency->format($kreditakhir),
						'saldoawal'	=> $this->currency->format($saldoawal),
			      'debetawal'	=>$this->currency->format($debetawal),
			      'kreditawal'	=>$this->currency->format($kreditawal),
			    );
			}*/

	  $totalmodalawal += $saldoawal;
	 // $totalasetberjalan += $saldoberjalan;
	  $totalmodalakhir += $saldoakhir;
	}
	$this->data['modalawal']=$this->currency->format($totalmodalawal);
//	$this->data['asetberjalan']=$this->currency->format($totalasetberjalan);
	$this->data['modalakhir']=$this->currency->format($totalmodalakhir);
	$pasivaawal=$totalhutangawal+$totalmodalawal;
	$pasivaakhir=$totalhutangakhir+$totalmodalakhir;
	$this->data['totalpasivaawal'] = $this->currency->format($pasivaawal);
	$this->data['totalpasivaakhir'] = $this->currency->format($pasivaakhir);

//hutang


	//aset akhir
	/*
	range tanggal awal tahun - tanggal akhir
	*/

	/*$this->data['modalawal']=$this->currency->format($totalmodalawal);
	$this->data['modalberjalan']=$this->currency->format($totalhutangberjalan);
	$this->data['modalakhir']=$this->currency->format($totalmodalakhir);

$this->data['totalpasivaawal']=$this->currency->format($totalmodalawal+$totalhutangawal);
$this->data['totalpasivaakhir']=$this->currency->format($totalmodalakhir+$totalhutangakhir);
	$this->data['totaldebetawal']=$this->currency->format($totaldebetawal);
	$this->data['totalkreditawal']=$this->currency->format($totalkreditawal);

	$this->data['totaldebetberjalan']=$this->currency->format($totaldebetberjalan);
	$this->data['totalkreditberjalan']=$this->currency->format($totalkreditberjalan);

	$this->data['totaldebetakhir']=$this->currency->format($totaldebetakhir);
	$this->data['totalkreditakhir']=$this->currency->format($totalkreditakhir);*/

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;

		$this->template = 'laporan/neracaupdate.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
}
?>
