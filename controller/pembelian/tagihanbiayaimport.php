<?php
class ControllerPembelianTagihanbiayaimport extends Controller {
	private $error=array();
	// baru 21 November 2019
	public function revinv(){
		echo "sukses";
	}
	
	// end baru
	public function index() {
		$this->document->setTitle('Tagihan Biaya Import');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '1970-01-01';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['filter_date_bayarawal'])) {
			$filter_date_bayarawal = $this->request->get['filter_date_bayarawal'];
		} else {
			$filter_date_bayarawal =null;
		}
		if (isset($this->request->get['filter_date_bayarakhir'])) {
			$filter_date_bayarakhir = $this->request->get['filter_date_bayarakhir'];
		} else {
			$filter_date_bayarakhir =null;
		}

		if (isset($this->request->get['filter_jenis'])) {
			$filter_jenis = $this->request->get['filter_jenis'];
		} else {
			$filter_jenis = '';
		}

		if (isset($this->request->get['filter_no_faktur'])) {
			$filter_no_faktur = $this->request->get['filter_no_faktur'];
		} else {
			$filter_no_faktur = '';
		}

		if (isset($this->request->get['vendor_id'])) {
			$vendor_id = $this->request->get['vendor_id'];
		} else {
			$vendor_id = '';
		}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_date_bayarawal'])) {
			$url .= '&filter_date_bayarawal=' . $this->request->get['filter_date_bayarawal'];
		}
		if (isset($this->request->get['filter_date_bayarakhir'])) {
			$url .= '&filter_date_bayarakhir=' . $this->request->get['filter_date_bayarakhir'];
		}
		if (isset($this->request->get['vendor_id'])) {
			$url .= '&vendor_id=' . $this->request->get['vendor_id'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('pembelian/tagihanbiayaimport/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['insertpembayaran'] = $this->url->link('pembelian/tagihanbiayaimport/insertpembayarantagihan', 'token=' . $this->session->data['token'].$url, 'SSL');


		/*$this->load->model('report/product');
        $this->load->model('catalog/product');
		*/

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('pembelian/biayapembelianimport');
		$this->load->model('keuangan/coa');

		/*
		PAjak
		1 PPh 21
		2 PPh 23
		3 PPh 4 (2) PP 46
		4 PPh 29
		5 PPh 4 (2) atas Sewa
		*/

		$this->data['permintaans'] = array();
		$column=array('tagihan_biaya_import.*');
		$join=array();

		$data = array(
			//'tgl_tagihan'      =>!empty($filter_date_start)?$filter_date_start:array('>','1901-01-01'),
			//'tgl_tagihan'      =>!empty($filter_date_end)?$filter_date_end:array('>','1901-01-01'),
			'id'	=> $filter_no_faktur,
			'vendor_id'	=> empty($vendor_id)?array('>',0):$vendor_id,
			'status'	=> !empty($filter_jenis)?$filter_jenis:array('<>',4),
			//'biaya_operasional.coa_id'	=> $filter_jenis

		);
		if(!empty($filter_date_end)){
			$data['tgl_tagihan']=array('>=',$filter_date_start,'<=',$filter_date_end);
		}else{
			$data['tgl_tagihan']=array('>=',$filter_date_start);
		}		

		$order=array(
			'tgl_tagihan'	=> 'DESC',
			'id'	=> 'DESC'

		);

		if($filter_date_bayarawal==null){
			$limit=5;
			$offset=($page - 1) * 5;
			$product_total = $this->model_pembelian_biayapembelianimport->totalTagihanBiayas($data);
			$results = $this->model_pembelian_biayapembelianimport->getTagihanBiayas($column,$join,$data,$order,$limit,$offset);
		}else{
			$limit=0;
			$offset=null;
			$product_total = $this->model_pembelian_biayapembelianimport->totalTagihanBiayas($data);
			$results = $this->model_pembelian_biayapembelianimport->getTagihanBiayas($column,$join,$data,$order,$limit,$offset);
		}

		

		$vendor=null;
		foreach ($results as $result) {
			$action = array();
			if($result['status'] == 1){
			$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('pembelian/tagihanbiayaimport/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}
			 
			$vendor = $this->model_pembelian_biayapembelianimport->getnamavendor($result['vendor_id']);

			if(!empty($result['pajak'])){
				$hutangpajak=$this->model_keuangan_coa->getCategoryByKodeRek($result['pajak']);
			}else{
				$hutangpajak=array('name'=>'Tanpa Hutang Pajak');
			}

			if(!empty($result['pajakdimuka'])){
				$pajakdimuka=$this->model_keuangan_coa->getCategoryByKodeRek($result['pajakdimuka']);
			}else{
				$pajakdimuka=array('name'=>'Tanpa Pajak Dibayar Dimuka');
			}

			//riwayat pembayaran
			$pembayaran=$this->model_pembelian_biayapembelianimport->getPembayaranTagihan(array('*'),array(),array('order_id'=>$result['id'],'status'=> 1),array('tgl_bayar'=>'ASC'));

			$bayar=array();
			$i=0;
			foreach($pembayaran as $pem){
				$pem['href']=$this->url->link('pembelian/tagihanbiayaimport/batalkanpembayaran', 'token=' . $this->session->data['token'] . '&id=' . $pem['pembayaran_id'].$url, 'SSL');
				$bayar[]=$pem;

			}
			//detail biaya
			$joinbiaya=array();
			$joinbiaya[]=array(
				'tablename'=> 'jenis_biaya_pembelian',
				'firsttable'	=> 'biaya_pembelianimport.jenisbiaya_id',
				'secondtable'	=> 'jenis_biaya_pembelian.id'
			);
			$leftjoinbiaya=array();
			$biayas=$this->model_pembelian_biayapembelianimport->getPermintaanPembelians(array(),$joinbiaya,$leftjoinbiaya,array('tagihan_id'=>$result['id']));

			if($filter_date_bayarawal==null){
				$this->data['permintaans'][] = array(
					'id'	=> $result['id'],
					'pajak'	=> $result['pajak'].' '.$hutangpajak['name'],
					'pajakdimuka'	=> $result['pajakdimuka'].' '.$pajakdimuka['name'],
					'jumlah'	=> $this->currency->format($result['nominal']),
					'ppn'	=> $this->currency->format($result['ppn']),
					'total'	=> $this->currency->format($result['total']),
					'nilaipajak'	=> $this->currency->format($result['nilaipajak']),
					'totalbayar'	=> $this->currency->format($result['totalbayar']),
					'tanggal'	=> date('d/m/Y',strtotime($result['tgl_tagihan'])),
					'jatuhtempo'	=> date('d/m/Y',strtotime($result['jatuhtempo'])),
					'status'	=> $result['status'],
					'statuspajak'	=> $result['statuspajak'],
					'keterangan'	=> $result['keterangan'],
					'no_faktur'	=> $result['no_faktur'],
					'biayas'	=> $biayas,
					'pembayaran'	=> $bayar,
					'tgl_bayar' =>empty($bayar[$i]['tgl_bayar'])?'':date('d/m/Y',strtotime($bayar[$i]['tgl_bayar'])),
					'vendor' =>$vendor,
					'actions'	=> $action
				);
			}else{
				if( date('Y-m-d',strtotime($bayar[$i]['tgl_bayar'])) >= $filter_date_bayarawal & date('Y-m-d',strtotime($bayar[$i]['tgl_bayar'])) <= $filter_date_bayarakhir ){
					$this->data['permintaans'][] = array(
						'id'	=> $result['id'],
						'pajak'	=> $result['pajak'].' '.$hutangpajak['name'],
						'pajakdimuka'	=> $result['pajakdimuka'].' '.$pajakdimuka['name'],
						'jumlah'	=> $this->currency->format($result['nominal']),
						'ppn'	=> $this->currency->format($result['ppn']),
						'total'	=> $this->currency->format($result['total']),
						'nilaipajak'	=> $this->currency->format($result['nilaipajak']),
						'totalbayar'	=> $this->currency->format($result['totalbayar']),
						'tanggal'	=> date('d/m/Y',strtotime($result['tgl_tagihan'])),
						'jatuhtempo'	=> date('d/m/Y',strtotime($result['jatuhtempo'])),
						'status'	=> $result['status'],
						'statuspajak'	=> $result['statuspajak'],
						'keterangan'	=> $result['keterangan'],
						'no_faktur'	=> $result['no_faktur'],
						'biayas'	=> $biayas,
						'pembayaran'	=> $bayar,
						'tgl_bayar' =>empty($bayar[$i]['tgl_bayar'])?'':date('d/m/Y',strtotime($bayar[$i]['tgl_bayar'])),
						'vendor' =>$vendor,
						'actions'	=> $action
					);
				}
			}
			
			$i++;
		}
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";
			print_r($bayar);
			exit;
		}
		$this->data['heading_title'] = 'Tagihan Biaya ';

		$this->data['token'] = $this->session->data['token'];
		$url = '';
		if (isset($this->request->get['filter_date_bayarawal'])) {
			$url .= '&filter_date_bayarawal=' . $this->request->get['filter_date_bayarawal'];
		}
		if (isset($this->request->get['filter_date_bayarakhir'])) {
			$url .= '&filter_date_bayarakhir=' . $this->request->get['filter_date_bayarakhir'];
		}

		if (isset($this->request->get['vendor_id'])) {
			$url .= '&vendor_id=' . $this->request->get['vendor_id'];
		}
		
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}


		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pembelian/tagihanbiayaimport', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_tgl_awal'] = $filter_tgl_awal;
		$this->data['filter_tgl_akhir'] = $filter_tgl_akhir;
		$this->data['filter_date_bayarawal'] = $filter_date_bayarawal;
		$this->data['filter_date_bayarakhir'] = $filter_date_bayarakhir;
		$this->data['filter_jenis'] = $filter_jenis;

		$this->template = 'pembelian/tagihanbiayaimport.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Biaya Iklan ');

		$this->load->model('pembelian/biayapembelianimport');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if($this->user->getUsername()=="pawit"){
				echo "<pre>";print_r($this->request->post);exit;
			}
			  $no_po=$this->model_pembelian_biayapembelianimport->addTagihanBiaya($this->request->post);
			  if($this->user->getUsername()=="pawit"){
				echo "<pre>";print_r($no_po);exit;
			  }
			$this->session->data['success'] = 'Sukses: Data Tagihan Biaya Pembelian Import  berhasil disimpan';

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
			if (isset($this->request->get['filter_no_faktur'])) {
				$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('pembelian/tagihanbiayaimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['token'] = $this->session->data['token'];


		if (isset($this->request->post['nominal'])) {
			$this->data['nominal'] = $this->request->post['nominal'];
		}  else {
			$this->data['nominal'] = '';
		}

		if (isset($this->request->post['no_faktur'])) {
			$this->data['no_faktur'] = $this->request->post['no_faktur'];
		}  else {
			$this->data['no_faktur'] = '';
		}

		if (isset($this->request->post['tgl_tagihan'])) {
			$this->data['tgl_tagihan'] = $this->request->post['tgl_tagihan'];
		}  else {
			$this->data['tgl_tagihan'] = date('Y-m-d');
		}
		if (isset($this->request->post['keterangan'])) {
			$this->data['keterangan'] = $this->request->post['keterangan'];
		}  else {
			$this->data['keterangan'] = '';
		}

		if (isset($this->request->post['jatuhtempo'])) {
			$this->data['jatuhtempo'] = $this->request->post['jatuhtempo'];
		}  else {
			$this->data['jatuhtempo'] = date('Y-m-d');
		}
		if (isset($this->request->post['pajak'])) {
			$this->data['pajak'] = $this->request->post['pajak'];
		}  else {
			$this->data['pajak'] = 0;
		}
		if (isset($this->request->post['nilaipajak'])) {
			$this->data['nilaipajak'] = $this->request->post['nilaipajak'];
		}  else {
			$this->data['nilaipajak'] = 0;
		}


		$this->data['cancel']= $this->url->link('pembelian/tagihanbiayaimport', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/tagihanbiayaimport/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = array();
		}
        
         $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
        

		$this->template = 'pembelian/tagihanbiayaimport_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}



	private function validateForm() {
		/*if (!$this->user->hasPermission('modify', 'keuangan/tagihanbiaya')) {
				$this->error['warning'] = 'Anda tidak diijinkan untuk memodifikasi menu tagihan biaya.';
		}

		if(!is_numeric($this->request->post['nominal']) ){
			$this->error['nominal'] = 'Jumlah Pembayaran Harus Berupa Angka';
		}

		if(empty($this->request->post['no_faktur']) ){
			$this->error['no_faktur'] = 'Nomor faktur harus diisi';
		}

		if(strtotime($this->request->post['jatuhtempo']) < strtotime($this->request->post['tgl_tagihan'])){
			$this->error['tanggal'] = 'Tanggal jatuh tempo harus lebih dari sama dengan tanggal tagihan';
		}*/
		//print_r($cek);
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}


	public function batalkan(){
		$this->load->model('pembelian/biayapembelianimport');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_pembelian_biayapembelianimport->batalkanTagihan(array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Tagihan Biaya Pembelian Import  berhasil dibatalkan.';
			}
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
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

			$this->redirect($this->url->link('pembelian/tagihanbiayaimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function batalkanpembayaran(){
		$this->load->model('pembelian/biayapembelianimport');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      			$batal=$this->model_pembelian_biayapembelianimport->batalkanPembayaran($this->request->get['id']);
				if($this->user->getUsername()=="pawit"){
					echo "<pre>";print_r($batal);exit;
				}
			$this->session->data['success'] = 'Sukses: Data Pembayaran Biaya Pembelian Import  berhasil dibatalkan.';
			}
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
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

			$this->redirect($this->url->link('pembelian/tagihanbiayaimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('pembelian/biayapembelianimport');

			if (isset($this->request->get['q'])) {
				$filter_no_po = $this->request->get['q'];
			} else {
				$filter_no_po = '';
			}

			if (isset($this->request->get['s'])) {
				$filter_status = $this->request->get['s'];
			} else {
				$filter_status = '';
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$column=array('tagihan_biaya_import.*');
			$join=array();
			/*$join[]=array(
				'tablename'	=> 'bank',
				'firsttable'	=>'biaya_operasional.bank_id',
				'secondtable'	=> 'bank.bank_id'
			);
			$join[]=array(
				'tablename'	=> 'coamnb',
				'firsttable'	=>'biaya_operasional.coa_id',
				'secondtable'	=> 'coamnb.category_id'
			);*/

			$data = array(
				//'tgl_tagihan'      =>!empty($filter_date_start)?$filter_date_start:array('>','1901-01-01'),
				//'tgl_tagihan'      =>!empty($filter_date_end)?$filter_date_end:array('>','1901-01-01'),
				'status'	=> empty($filter_status)?array('<>',4):array('IN','1,2'),
				'no_faktur'	=> $filter_no_po

			);
			$limit=20;
			$offset=0;

			$order=array(
				'tgl_tagihan'	=> 'DESC',

			);

			$results = $this->model_pembelian_biayapembelianimport->getTagihanBiayas($column,$join,$data,$order,$limit,$offset);


			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_faktur'].' - '.$r['keterangan'].' '.$this->currency->format($r['total'])
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function insertpembayarantagihan() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Pembayaran Tagihan Biaya Pembelian Import');

		$this->load->model('pembelian/biayapembelianimport');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_po=$this->model_pembelian_biayapembelianimport->addPembayaranTagihan($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Pembayaran Tagihan  berhasil disimpan';

			$url='';
			if (isset($this->request->get['filter_no_faktur'])) {
				$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
			}
			if (isset($this->request->get['filter_invoice'])) {
				$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_vendor'])) {
				$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('pembelian/tagihanbiayaimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url='';
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['token'] = $this->session->data['token'];


		if (isset($this->request->post['order_id'])) {
			$this->data['order_id'] = $this->request->post['order_id'];
		}  else {
			$this->data['order_id'] = '';
		}

		if (isset($this->request->post['bank_id'])) {
			$this->data['bank_id'] = $this->request->post['bank_id'];
		}  else {
			$this->data['bank_id'] = '';
		}

		if (isset($this->request->post['nominal'])) {
			$this->data['nominal'] = $this->request->post['nominal'];
		}  else {
			$this->data['nominal'] = '';
		}

		if (isset($this->request->post['keterangan'])) {
			$this->data['keterangan'] = $this->request->post['keterangan'];
		}  else {
			$this->data['keterangan'] = '';
		}

		if (isset($this->request->post['tgl_bayar'])) {
			$this->data['tgl_bayar'] = $this->request->post['tgl_bayar'];
		}  else {
			$this->data['tgl_bayar'] = '';
		}


		$this->data['cancel']= $this->url->link('pembelian/tagihanbiayaimport', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/tagihanbiayaimport/insertpembayarantagihan', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = array();
		}
        
         $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
        

		$this->template = 'pembelian/pembayarantagihanbiayaimport_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}
	public function detailbiaya(){
		$hasil = array();


		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){

			$this->load->model('pembelian/biayapembelianimport');
			/*
			0 belum ada
			1 ditagih
			2 dibayar sebagian
			3 Lunas
			4 Dibatalkan
			*/

			$data = array(
				'id'	=> $this->request->get['id'],

			//	'invoice_pembelian_import.statuspembayaran'	=> array('<',3)
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);

			$hasil=$this->model_pembelian_biayapembelianimport->getTagihanBiaya(array(),array(),$data);;
		}
	}
		$this->response->setOutput(json_encode($hasil));
	}

}
?>
