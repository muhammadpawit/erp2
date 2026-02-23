<?php
class ControllerKeuanganBukubesar extends Controller {
	private $error=array();
	public function cetak(){
		//echo "under";
		$this->document->setTitle('Buku Besar');
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_date_start ="";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			//$filter_date_end = date('Y-m-d');
			$filter_date_end ="";
		}
		if (isset($this->request->get['filter_jenis'])) {
			$filter_jenis = $this->request->get['filter_jenis'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_jenis ="";
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

		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('keuangan/jurnal');
		$this->load->model('keuangan/coa');

		$this->data['orders'] = array();
		if(!empty($filter_jenis)){
			$data = array(
				//'filter_date_start'	     => $filter_date_start,
				'filter_date_end'	     => $filter_date_end,
				'filter_jenis'	=> $filter_jenis,
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);

			$order_total = $this->model_keuangan_jurnal->totalJurnalUmum($data);
			$results = $this->model_keuangan_jurnal->jurnalUmumBukuBesarnew($data);
			$coa=$this->model_keuangan_coa->getCategoryByKodeRek($filter_jenis);
			$this->data['namaakun']=$this->model_keuangan_jurnal->ambilnamacoa($filter_jenis);
			$this->data['type']=$coa['type'];
			if(!empty($filter_jenis)){
				$this->data['totaldebet']=$this->model_keuangan_jurnal->totalDebet($filter_jenis,$data);
				$this->data['totalkredit']=$this->model_keuangan_jurnal->totalKredit($filter_jenis,$data);
			}else{
				$this->data['totaldebet']=0;
				$this->data['totalkredit']=0;
			}
			$sblm=0;
			$a=0;
			$sisa=0;
			$this->load->model('keuangan/saldoawalcoa');
		$saldoawalcoa=0;
		//if($this->user->getUsername()=="pawit"){
			$saldoawalcoa=$this->model_keuangan_saldoawalcoa->getSaldoawal($filter_jenis,date('Y',strtotime($filter_date_start)));
			//echo "<pre>";print_r($saldoawalcoa);exit;
		//}
		/* Baru 27 April 2020 */
		$tahunawal='2018';
		$tahunakhir='2018';
		$tahun=explode("-",$filter_date_start);
		if($tahun[0] > 2018){
			$filterawal=array(
				'filter_date_start'	=> $tahun[0].'-01-01',
				//'filter_date_end'	=> $filter_date_start
				'filter_date_end'=>date('Y-m-d',strtotime('-1 day',strtotime($filter_date_start))),
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
							//'filter_date_end'	=> $filter_date_start
							'filter_date_end'=>date('Y-m-d',strtotime('-1 day',strtotime($filter_date_start))),
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

		$debetawal=$this->model_keuangan_jurnal->totalDebet($filter_jenis,$filterawal);
	  	$kreditawal=$this->model_keuangan_jurnal->totalKredit($filter_jenis,$filterawal);
		
		$awal=$this->model_keuangan_saldoawalcoa->getSaldo(array('saldoawalcoa.kode_rek'=>$filter_jenis,'hapus'=>array('<',1),'tahun'=>$tahunawal));
		$akhir=$this->model_keuangan_saldoawalcoa->getSaldo(array('saldoawalcoa.kode_rek'=>$filter_jenis,'hapus'=>array('<',1),'tahun'=>$tahunakhir));
		/*
		$saldoawal=$debetawal-$kreditawal;
		$saldo=$awal['debet']-$awal['kredit'];
		*/
		/*if($filter_jenis>=2000 & $filter_jenis<=2699){
			$saldoawal=$kreditawal-$debetawal;
			$saldo=$awal['kredit']-$awal['debet'];
			$this->data['awaldebet']=0;
			$this->data['awalkredit']=$saldoawal;
		}else{*/
			$saldoawal=$debetawal-$kreditawal;
			$saldo=$awal['debet']-$awal['kredit'];
			$this->data['awalkredit']=0;
			$this->data['awaldebet']=$saldo;
		//}
		if($tahun[0] < 2018){
			$saldoawal=$saldo;
		}else{
			$saldoawal += $saldo;

		}
		$this->data['saldoawal']=$saldoawal;
		/* End baru */
		$this->data['saldoawalcoa']=$saldoawalcoa['debet'];
			//foreach (array_reverse($results) as $result) {
			/*
			foreach ($results as $result) {
				$detail=$this->model_keuangan_jurnal->getDetailJurnalUmumBukuBesarnew($result['id'],$filter_jenis);
				$this->data['orders'][] = array(
					'keterangan'	=> $result['keterangan'],
					'tanggal'	=>$result['tanggal'],
					'ref'	=> $result['ref'],
					'type'	=> $result['type'],
					'linkterkait' => empty($result['linkterkait'])?$result['ref']:$result['linkterkait'],
					'detail'	=> $detail
				);
			}*/
				$ket=null;
				foreach ($results as $result) {
					$detail=$this->model_keuangan_jurnal->getDetailJurnalUmumBukuBesarnew($result['id'],$filter_jenis);
					if($filter_jenis==1101){
						//$ket=$this->model_keuangan_jurnal->getnamacust($result['ref']);
					}
					$this->data['orders'][] = array(
						'keterangan'	=> $result['keterangan'].' '.$ket,
						'tanggal'	=>$result['tanggal'],
						'ref'	=> $result['ref'],
						'type'	=> $result['type'],
						'linkterkait' => empty($result['linkterkait'])?$result['ref']:$result['linkterkait'],
						'detail'	=> $detail
					);
				}
		}
		$this->data['sblmnya']=$sblm;

		$this->data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('keuangan/bukubesar', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_jenis'] = $filter_jenis;
		$this->data['cetak'] = $this->url->link('keuangan/bukubesar/cetak', 'token=' . $this->session->data['token'] . $url . '&print=1', 'SSL');
		$this->template = 'laporan/bukubesar_print.tpl';		

		$this->response->setOutput($this->render());
	}
	public function index() {
		$this->document->setTitle('Buku Besar');
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_date_start ="";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			//$filter_date_end = date('Y-m-d');
			$filter_date_end ="";
		}
		if (isset($this->request->get['filter_jenis'])) {
			$filter_jenis = $this->request->get['filter_jenis'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_jenis ="";
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

		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('keuangan/jurnal');
		$this->load->model('keuangan/coa');
		$this->load->model('keuangan/saldoawalcoa');
		$saldoawalcoa=0;
		//if($this->user->getUsername()=="pawit"){
			$saldoawalcoa=$this->model_keuangan_saldoawalcoa->getSaldoawal($filter_jenis,date('Y',strtotime($filter_date_start)));
			//echo "<pre>";print_r($saldoawalcoa);exit;
		//}

		$this->data['saldoawalcoa']=$saldoawalcoa['debet'];
		/* Baru 27 April 2020 */
		$tahunawal='2018';
		$tahunakhir='2018';
		$tahun=explode("-",$filter_date_start);
		if($tahun[0] > 2018){
			$filterawal=array(
				'filter_date_start'	=> $tahun[0].'-01-01',
				//'filter_date_end'	=> $filter_date_start
				'filter_date_end'=>date('Y-m-d',strtotime('-1 day',strtotime($filter_date_start))),
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
							//'filter_date_end'	=> $filter_date_start
							'filter_date_end'=>date('Y-m-d',strtotime('-1 day',strtotime($filter_date_start))),
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

		$debetawal=$this->model_keuangan_jurnal->totalDebet($filter_jenis,$filterawal);
	  	$kreditawal=$this->model_keuangan_jurnal->totalKredit($filter_jenis,$filterawal);
		$awal=$this->model_keuangan_saldoawalcoa->getSaldo(array('saldoawalcoa.kode_rek'=>$filter_jenis,'hapus'=>array('<',1),'tahun'=>$tahunawal));
		$akhir=$this->model_keuangan_saldoawalcoa->getSaldo(array('saldoawalcoa.kode_rek'=>$filter_jenis,'hapus'=>array('<',1),'tahun'=>$tahunakhir));
		/*if($filter_jenis>=2000 & $filter_jenis<=2699){
			$saldoawal=$kreditawal-$debetawal;
			$saldo=$awal['kredit']-$awal['debet'];
			$this->data['awaldebet']=0;
			$this->data['awalkredit']=$saldoawal;
		}else{*/
			$saldoawal=$debetawal-$kreditawal;
			$saldo=$awal['debet']-$awal['kredit'];
			$this->data['awalkredit']=0;
			$this->data['awaldebet']=$saldo;
		//}
		
		if($tahun[0] < 2018){
			$saldoawal=$saldo;
		}else{
			$saldoawal += $saldo;

		}
		$this->data['saldoawal']=$saldoawal;
		if($this->user->getUsername()=="pawitx"){
			//$saldoawalcoa=$this->model_keuangan_saldoawalcoa->getSaldoawal($filter_jenis,date('Y',strtotime($filter_date_start)));
			echo "<pre>";print_r($filterawal);exit;
		}
		/* End baru */
		$this->data['orders'] = array();
		if(!empty($filter_jenis)){
			if($this->user->getUsername()=="pawitx"){
				$data = array(
					'filter_date_start'	     => $filter_date_start,
					'filter_date_end'	     => $filter_date_end,
					'filter_jenis'	=> $filter_jenis,
					'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
					//'limit'                  => $this->config->get('config_admin_limit')
					'limit'	=>100,
				);
			}else{
				$data = array(
					'filter_date_start'	     => $filter_date_start,
					'filter_date_end'	     => $filter_date_end,
					'filter_jenis'	=> $filter_jenis,
					'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
					//'limit'                  => $this->config->get('config_admin_limit')
					'limit'                  =>100
				);
			}
			
			// baru
			$dataall = array(
				'filter_date_start'	     => $filter_date_start,
				'filter_date_end'	     => $filter_date_end,
				'filter_jenis'	=> $filter_jenis,
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);

			$baru = $this->model_keuangan_jurnal->jurnalUmumBukuBesarnew($dataall);
			$this->data['jumlah']=count($baru);
			$all=0;
			$all=$this->model_keuangan_jurnal->getldebkredit($dataall,$filter_jenis);
			$this->data['alldebet']=$all['debet'];
			$this->data['allkredit']=$all['kredit'];
			// end baru
			$order_total = $this->model_keuangan_jurnal->totalJurnalUmum($data);
			$results = $this->model_keuangan_jurnal->jurnalUmumBukuBesarnew($data);
			$coa=$this->model_keuangan_coa->getCategoryByKodeRek($filter_jenis);
			$this->data['namaakun']=$this->model_keuangan_jurnal->ambilnamacoa($filter_jenis);
			$this->data['type']=$coa['type'];
			if(!empty($filter_jenis)){
				$this->data['totaldebet']=$this->model_keuangan_jurnal->totalDebet($filter_jenis,$data);
				$this->data['totalkredit']=$this->model_keuangan_jurnal->totalKredit($filter_jenis,$data);
			}else{
				$this->data['totaldebet']=0;
				$this->data['totalkredit']=0;
			}
			$sblm=0;
			$a=0;
			$sisa=0;
			if($this->user->getUsername()=="pawitx"){
				foreach ($results as $result) {
					$detail=$this->model_keuangan_jurnal->getDetailJurnalUmumBukuBesarnew($result['id'],$filter_jenis);
					$this->data['orders'][] = array(
						'keterangan'	=> $result['keterangan'],
						'tanggal'	=>$result['tanggal'],
						'ref'	=> $result['ref'],
						'type'	=> $result['type'],
						'linkterkait' => empty($result['linkterkait'])?$result['ref']:$result['linkterkait'],
						'detail'	=> $detail
					);
				}
			}else{
				/*
				foreach (array_reverse($results) as $result) {
					$detail=$this->model_keuangan_jurnal->getDetailJurnalUmumBukuBesarnew($result['id'],$filter_jenis);
					$this->data['orders'][] = array(
						'keterangan'	=> $result['keterangan'],
						'tanggal'	=>$result['tanggal'],
						'ref'	=> $result['ref'],
						'type'	=> $result['type'],
						'linkterkait' => empty($result['linkterkait'])?$result['ref']:$result['linkterkait'],
						'detail'	=> $detail
					);
				}*/
				$ket=null;
				foreach ($results as $result) {
					$detail=$this->model_keuangan_jurnal->getDetailJurnalUmumBukuBesarnew($result['id'],$filter_jenis);
					if($filter_jenis==1101){
						//$ket=$this->model_keuangan_jurnal->getnamacust($result['ref']);
					}
					$this->data['orders'][] = array(
						'keterangan'	=> $result['keterangan'].''.$ket,
						'tanggal'	=>$result['tanggal'],
						'ref'	=> $result['ref'],
						'type'	=> $result['type'],
						'linkterkait' => empty($result['linkterkait'])?$result['ref']:$result['linkterkait'],
						'detail'	=> $detail
					);
				}
			}
			
		}
		$this->data['sblmnya']=$sblm;

		$this->data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('keuangan/bukubesar', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_jenis'] = $filter_jenis;
		$this->data['cetak'] = $this->url->link('keuangan/bukubesar/cetak', 'token=' . $this->session->data['token'] . $url . '&print=1', 'SSL');
		
		if($this->user->getUsername()=="pawitx"){
			$this->template = 'laporan/bukubesarnewtest.tpl';
		}else{
			$this->template = 'laporan/bukubesarnew.tpl';
		}
		$this->children = array(
			'common/header',
			'common/footer'
		);
		

		$this->response->setOutput($this->render());
	}

	public function indexlama() {
		$this->document->setTitle('Buku Besar');
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_date_start ="";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			//$filter_date_end = date('Y-m-d');
			$filter_date_end ="";
		}
		if (isset($this->request->get['filter_jenis'])) {
			$filter_jenis = $this->request->get['filter_jenis'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_jenis ="";
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

		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('keuangan/jurnal');
		$this->load->model('keuangan/coa');

		$this->data['orders'] = array();
		if(!empty($filter_jenis)){
			$data = array(
				'filter_date_start'	     => $filter_date_start,
				'filter_date_end'	     => $filter_date_end,
				'filter_jenis'	=> $filter_jenis,
				'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit'                  => $this->config->get('config_admin_limit')
			);

			$order_total = $this->model_keuangan_jurnal->totalJurnalUmum($data);
			
			//$results = $this->model_keuangan_jurnal->jurnalUmum($data);
			$results = $this->model_keuangan_jurnal->jurnalUmumBukuBesar($data);
			//$this->load->model('pembelian/pembeliantunai');
			//$this->load->model('pembelian/pembayarandp');
			//print_r($results);
			$coa=$this->model_keuangan_coa->getCategoryByKodeRek($filter_jenis);
			$this->data['type']=$coa['type'];
			if(!empty($filter_jenis)){
				$this->data['totaldebet']=$this->model_keuangan_jurnal->totalDebet($filter_jenis,$data);
				$this->data['totalkredit']=$this->model_keuangan_jurnal->totalKredit($filter_jenis,$data);
			}else{
				$this->data['totaldebet']=0;
				$this->data['totalkredit']=0;
			}
			foreach ($results as $result) {

				//$detail=$this->model_keuangan_jurnal->getDetailJurnalUmum($result['id'],$filter_jenis);
				$detail=$this->model_keuangan_jurnal->getDetailJurnalUmumBukuBesar($result['id'],$filter_jenis);
				$this->data['orders'][] = array(
					'keterangan'	=> $result['keterangan'],
					'tanggal'	=>date('d/m/y',strtotime($result['tanggal'])),
					'ref'	=> $result['ref'],
					'type'	=> $result['type'],
					'detail'	=> $detail

				);
			}
		}

		$this->data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('keuangan/bukubesar', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_jenis'] = $filter_jenis;
		$this->data['namaakun']=$this->model_keuangan_jurnal->ambilnamacoa($filter_jenis);
		$this->template = 'laporan/bukubesar.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function excel() {
		$this->document->setTitle('Buku Besar');
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_date_start ="";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			//$filter_date_end = date('Y-m-d');
			$filter_date_end ="";
		}
		if (isset($this->request->get['filter_jenis'])) {
			$filter_jenis = $this->request->get['filter_jenis'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_jenis ="";
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

		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('keuangan/jurnal');
		$this->load->model('keuangan/coa');

		$this->data['orders'] = array();
		if(!empty($filter_jenis)){
			$data = array(
				'filter_date_start'	     => $filter_date_start,
				'filter_date_end'	     => $filter_date_end,
				'filter_jenis'	=> $filter_jenis,
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);

			$order_total = $this->model_keuangan_jurnal->totalJurnalUmum($data);

			//$results = $this->model_keuangan_jurnal->jurnalUmum($data);
			$results = $this->model_keuangan_jurnal->jurnalUmumBukuBesar($data);
			
			//$this->load->model('pembelian/pembeliantunai');
			//$this->load->model('pembelian/pembayarandp');
			//print_r($results);
			$coa=$this->model_keuangan_coa->getCategoryByKodeRek($filter_jenis);
			$this->data['type']=$coa['type'];
			if(!empty($filter_jenis)){
				$this->data['totaldebet']=$this->model_keuangan_jurnal->totalDebet($filter_jenis,$data);
				$this->data['totalkredit']=$this->model_keuangan_jurnal->totalKredit($filter_jenis,$data);
			}else{
				$this->data['totaldebet']=0;
				$this->data['totalkredit']=0;
			}
			foreach ($results as $result) {

				//$detail=$this->model_keuangan_jurnal->getDetailJurnalUmum($result['id'],$filter_jenis);
				$detail=$this->model_keuangan_jurnal->getDetailJurnalUmumBukuBesar($result['id'],$filter_jenis);

				$this->data['orders'][] = array(
					'keterangan'	=> $result['keterangan'],
					'tanggal'	=>date('d/m/y',strtotime($result['tanggal'])),
					'ref'	=> $result['ref'],
					'type'	=> $result['type'],
					'detail'	=> $detail

				);
			}
		}

		$this->data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('keuangan/bukubesar', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_jenis'] = $filter_jenis;

		$this->template = 'laporan/bukubesarexcel.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

}
?>
