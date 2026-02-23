<?php
class ControllerLaporanNeraca extends Controller {
	public function index() {
		$this->document->setTitle('Neraca');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date("Y-m-d", strtotime("first day of previous month"));
			$filter_date_start =null;
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			//$filter_date_end = date("Y-m-d");
			$filter_date_end =null;
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
	$this->load->model('keuangan/saldoawalcoa');

	//if(){}
	/*$tahun=explode("-",$filter_date_start);

	if($tahun[0] == '2018'){

	}*/

	
	$this->data['pendapatan']=array();

	//aset awal
	/*
	range tanggal awal tahun - tanggal awal
	*/

	$tahunawal='2018';
	$tahunakhir='2018';
if($filter_date_start!=null){
	$tahun=explode("-",$filter_date_start);
	if($tahun[0] > 2018){
		$filterawal=array(
			'filter_date_start'	=> $tahun[0].'-01-01',
			'filter_date_end'	=> $filter_date_start
		);
		$tahunawal=$tahun[0];
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
		$tahunakhir=$tahun2[0];
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
	 /*$debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
	  $kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);
		*/
		
		$awal=$this->model_keuangan_saldoawalcoa->getSaldo(array('saldoawalcoa.kode_rek'=>$p['kode_rek'],'hapus'=>array('<',1),'tahun'=>$tahunawal));
		$akhir=$this->model_keuangan_saldoawalcoa->getSaldo(array('saldoawalcoa.kode_rek'=>$p['kode_rek'],'hapus'=>array('<',1),'tahun'=>$tahunakhir));
	    
		//$kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);

	  //$saldoawal=$debetawal-$kreditawal;
		$saldo=$awal['debet']-$awal['kredit'];
		
	
	 // $saldoakhir=$debetakhir-$kreditakhir;
	 	$saldoawal=$this->model_keuangan_jurnal->totalsumdebet($p['kode_rek'],$filterawal);
		$saldoakhir=$this->model_keuangan_jurnal->totalsumdebet($p['kode_rek'],$filterakhir);
		$saldo2=$akhir['debet']-$akhir['kredit'];
		/*if($tahun2[0] == '2018'){

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
		}*/
		if($tahun[0] < 2018){
			$saldoawal=$saldo;
		}else{
			$saldoawal += $saldo;

		}
		if($tahun2[0] < 2018){
			$saldoakhir=$saldo2;
		}else{
			
			$saldoakhir += $saldo2;


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
	  $saldoawal=$this->model_keuangan_jurnal->totalsumkredit($p['kode_rek'],$filterawal);
		$saldoakhir=$this->model_keuangan_jurnal->totalsumkredit($p['kode_rek'],$filterakhir);
		
	 
	  $awal=$this->model_keuangan_saldoawalcoa->getSaldo(array('saldoawalcoa.kode_rek'=>$p['kode_rek'],'hapus'=>array('<',1),'tahun'=>$tahunawal));
		$akhir=$this->model_keuangan_saldoawalcoa->getSaldo(array('saldoawalcoa.kode_rek'=>$p['kode_rek'],'hapus'=>array('<',1),'tahun'=>$tahunakhir));
	    
	  	//$saldoawal=$kreditawal-$debetawal;
		$saldo=$awal['kredit']- $awal['debet'];
		
		/*$debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
		$kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);
	  
		$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
	 	 $kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);

	  $saldoakhir=$kreditakhir-$debetakhir;
	  */

		$saldo2=$akhir['kredit']-$akhir['debet'];
		if($tahun[0] < 2018){
			$saldoawal=$saldo;
		}else{
			$saldoawal += $saldo;

		}
		if($tahun2[0] < 2018){
			$saldoakhir=$saldo2;
		}else{
			
			$saldoakhir += $saldo2;


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
	  
		/*$debetawal=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterawal);
		$kreditawal=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterawal);
		*/  
		  $awal=$this->model_keuangan_saldoawalcoa->getSaldo(array('saldoawalcoa.kode_rek'=>$p['kode_rek'],'hapus'=>array('<',1),'tahun'=>$tahunawal));
			$akhir=$this->model_keuangan_saldoawalcoa->getSaldo(array('saldoawalcoa.kode_rek'=>$p['kode_rek'],'hapus'=>array('<',1),'tahun'=>$tahunakhir));
	   

		if($p['kode_rek'] !=  3102){ 
			$saldoawal=$this->model_keuangan_jurnal->totalsumkredit($p['kode_rek'],$filterawal);
	  
		}

		$saldo=$awal['kredit']-$awal['debet'];
		
		/*$debetakhir=$this->model_keuangan_jurnal->totalDebet($p['kode_rek'],$filterakhir);
		  $kreditakhir=$this->model_keuangan_jurnal->totalKredit($p['kode_rek'],$filterakhir);
		*/
		if($p['kode_rek'] !=  3102){
			$saldoakhir=$this->model_keuangan_jurnal->totalsumkredit($p['kode_rek'],$filterakhir);
	 
		}

		$saldo2=$akhir['kredit']-$akhir['debet'];
		
		if($tahun[0] < 2018){
			$saldoawal=$saldo;
		}else{
			$saldoawal += $saldo;

		}
		if($tahun2[0] < 2018){
			$saldoakhir=$saldo2;
		}else{
			
			$saldoakhir += $saldo2;


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
				$hitung=true;
				$hitungakhir=true;
				$datalabarugi=$p;
				$datalr = array(
				    'filter_type'	=> 4,

				);

				if($tahun[0] < 2018){
					$hitung=false;
				}else{
					if($tahun[0] == 2018){
						if($tahun[1] < 3){
							$hitung=false;
						}
					}
				}

				if($tahun2[0] < 2018){
					$hitungakhir=false;
				}else{
					if($tahun2[0] == 2018){
						if($tahun2[1] < 3){
							$hitungakhir=false;
						}
					}
				}

			
				
				$pendapatan=$this->model_keuangan_coa->getAllCategories($datalr);
				$totalpendapatanawal=0;
				$totalpendapatanakhir=0;
				foreach($pendapatan as $d){
					/*$debetawal=$this->model_keuangan_jurnal->totalDebet($d['kode_rek'],$filterawal);
					$kreditawal=$this->model_keuangan_jurnal->totalKredit($d['kode_rek'],$filterawal);
					*/

				
					/*$saldopendapatanawal=$this->model_keuangan_jurnal->totalkredit($d['kode_rek'],$filterawal);

					$saldopendapatanakhir=$this->model_keuangan_jurnal->totalkredit($d['kode_rek'],$filterakhir);*/
					$saldopendapatanawal=$this->model_keuangan_jurnal->totalsumkredit($d['kode_rek'],$filterawal);
					$saldopendapatanakhir=$this->model_keuangan_jurnal->totalsumkredit($d['kode_rek'],$filterakhir);
					
					//echo $d['kode_rek'].' '.$saldopendapatanawal.'<br>';
					$totalpendapatanawal += $saldopendapatanawal;
					$totalpendapatanakhir += $saldopendapatanakhir;
				}
				/* HPP */

				$datalr = array(
				    'filter_type'	=> 5,

				);
				$hpp=$this->model_keuangan_coa->getAllCategories($datalr);
				$totalhppawal=0;
				$totalhppakhir=0;
				foreach($hpp as $d){
					/*
					$debetawal=$this->model_keuangan_jurnal->totalDebet($d['kode_rek'],$filterawal);
					$kreditawal=$this->model_keuangan_jurnal->totalKredit($d['kode_rek'],$filterawal);
					

				
					$saldohppawal=($debetawal-$kreditawal);

					$debetakhir=$this->model_keuangan_jurnal->totalDebet($d['kode_rek'],$filterakhir);
					$kreditakhir=$this->model_keuangan_jurnal->totalKredit($d['kode_rek'],$filterakhir);
					
					$saldohppakhir=($debetakhir-$kreditakhir);

					$totalhppawal += $saldohppawal;
					$totalhppakhir += $saldohppakhir;
					*/
					$saldohppawal=$this->model_keuangan_jurnal->totalsumdebet($d['kode_rek'],$filterawal);
					$saldohppakhir=$this->model_keuangan_jurnal->totalsumdebet($d['kode_rek'],$filterakhir);
				//	echo $d['kode_rek'].' '.$saldohppawal.'<br>';
					$totalhppawal += $saldohppawal;
					$totalhppakhir += $saldohppakhir;

				}
				/*Laba Kotor */
				$labakotorawal=$totalpendapatanawal-$totalhppawal;
				$labakotorakhir=$totalpendapatanakhir-$totalhppakhir;
				//echo $totalpendapatanawal;
				$datalr = array(
					'filter_type'	=> 6,

				  );
				$biaya=$this->model_keuangan_coa->getAllCategories($datalr);
				$totalbiayaawal=0;
				$totalbiayaakhir=0;
				foreach($biaya as $d){
					/*$debetawal=$this->model_keuangan_jurnal->totalDebet($d['kode_rek'],$filterawal);
					$kreditawal=$this->model_keuangan_jurnal->totalKredit($d['kode_rek'],$filterawal);
					
					
					$saldobiayaawal=($debetawal-$kreditawal);

					$debetakhir=$this->model_keuangan_jurnal->totalDebet($d['kode_rek'],$filterakhir);
					$kreditakhir=$this->model_keuangan_jurnal->totalKredit($d['kode_rek'],$filterakhir);

					
					$saldobiayaakhir=($debetakhir-$kreditakhir);
					*/
					$saldobiayaawal=$this->model_keuangan_jurnal->totalsumdebet($d['kode_rek'],$filterawal);
					$saldobiayaakhir=$this->model_keuangan_jurnal->totalsumdebet($d['kode_rek'],$filterakhir);
					
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
				  foreach($biayalain as $d){
						/*$debetawal=$this->model_keuangan_jurnal->totalDebet($d['kode_rek'],$filterawal);
						$kreditawal=$this->model_keuangan_jurnal->totalKredit($d['kode_rek'],$filterawal);
						
						
						$saldobiayalainawal=($kreditawal-$debetawal);

						$debetakhir=$this->model_keuangan_jurnal->totalDebet($d['kode_rek'],$filterakhir);
						$kreditakhir=$this->model_keuangan_jurnal->totalKredit($d['kode_rek'],$filterakhir);

						
						$saldobiayalainakhir=($kreditakhir-$debetakhir);
						*/
						$saldobiayalainawal=$this->model_keuangan_jurnal->totalsumkredit($d['kode_rek'],$filterawal);
						$saldobiayalainakhir=$this->model_keuangan_jurnal->totalsumkredit($d['kode_rek'],$filterakhir);
					
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
				  
				  foreach($pendapatanlain as $d){
					/*$debetawal=$this->model_keuangan_jurnal->totalDebet($d['kode_rek'],$filterawal);
					$kreditawal=$this->model_keuangan_jurnal->totalKredit($d['kode_rek'],$filterawal);
					
					
					$saldopendapatanlainawal=($debetawal-$kreditawal);

					$debetakhir=$this->model_keuangan_jurnal->totalDebet($d['kode_rek'],$filterakhir);
					$kreditakhir=$this->model_keuangan_jurnal->totalKredit($d['kode_rek'],$filterakhir);
					
					
					$saldopendapatanlainakhir=($debetakhir-$kreditakhir);
					*/

					$saldopendapatanlainawal=$this->model_keuangan_jurnal->totalsumdebet($d['kode_rek'],$filterawal);
					$saldopendapatanlainakhir=$this->model_keuangan_jurnal->totalsumdebet($d['kode_rek'],$filterakhir);
					
					$totalpendapatanlainawal += $saldopendapatanlainawal;
					$totalpendapatanlainakhir += $saldopendapatanlainakhir;
				  }

				  $datalr = array(
					'filter_type'	=> 9,

				  );
				  $pendapatanluarbiasa=$this->model_keuangan_coa->getAllCategories($datalr);
				  $totapendapatanluarbiasaawal=0;
				  $totalpendapatanluarbiasaakhir=0;
				  
				  foreach($pendapatanluarbiasa as $d){
					/*$debetawal=$this->model_keuangan_jurnal->totalDebet($d['kode_rek'],$filterawal);
					$kreditawal=$this->model_keuangan_jurnal->totalKredit($d['kode_rek'],$filterawal);
					
					
					$saldopendapatanluarbiasaawal=($kreditawal-$debetawal);

					$debetakhir=$this->model_keuangan_jurnal->totalDebet($d['kode_rek'],$filterakhir);
					$kreditakhir=$this->model_keuangan_jurnal->totalKredit($d['kode_rek'],$filterakhir);

					
					$saldopendapatanluarbiasaakhir=($kreditakhir-$debetakhir);
					*/
					$saldopendapatanluarbiasaawal=$this->model_keuangan_jurnal->totalsumkredit($d['kode_rek'],$filterawal);
					$saldopendapatanluarbiasaakhir=$this->model_keuangan_jurnal->totalsumkredit($d['kode_rek'],$filterakhir);
					
					$totalpendapatanluarbiasaawal += $saldopendapatanluarbiasaawal;
					$totalpendapatanluarbiasaakhir += $saldopendapatanluarbiasaakhir;
				  }

				  $labarugibersihawal=$labakotorawal- $totalbiayaawal - $totalpendapatanlainawal + $totalbiayalainawal + $totalpendapatanluarbiasaawal;
				  $saldoawal =$labarugibersihawal;

				$labarugibersihakhir=$labakotorakhir- $totalbiayaakhir - $totalpendapatanlainakhir + $totalbiayalainakhir + $totalpendapatanluarbiasaakhir;
				$saldoakhir =$labarugibersihakhir;

			  $saldo=$datalabarugi['kredit']-$datalabarugi['debet'];

				$this->data['modal'][]=array(
			      'name'        => 'Laba (rugi) Periode Berjalan',
			      'kode_rek'	=> 3102,
			      'parent_id'	=> 3100,
			      'saldoakhir'	=> $hitungakhir?$this->currency->format($saldoakhir):$this->currency->format(0),
			      'debetakhir'	=>$hitungakhir?$this->currency->format($debetakhir):$this->currency->format(0),
			      'kreditakhir'	=>$hitungakhir?$this->currency->format($kreditakhir):$this->currency->format(0),
					'saldoawal'	=> $hitung?$this->currency->format($saldoawal):$this->currency->format(0),
			      'debetawal'	=>$hitung?$this->currency->format($debetawal):$this->currency->format(0),
			      'kreditawal'	=>$hitung?$this->currency->format($kreditawal):$this->currency->format(0),
			    );
			}

	  $totalmodalawal += $saldoawal;
	 // $totalasetberjalan += $saldoberjalan;
	  $totalmodalakhir += $saldoakhir;
	}
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
