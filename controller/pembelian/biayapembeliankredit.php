<?php
class ControllerPembelianBiayapembeliankredit extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Biaya Pembelian');

		if (isset($this->request->get['filter_no_faktur'])) {
			$filter_no_faktur = $this->request->get['filter_no_faktur'];
		} else {
			$filter_no_faktur = '';
		}


		if (isset($this->request->get['filter_invoice'])) {
			$filter_invoice = $this->request->get['filter_invoice'];
		} else {
			$filter_invoice = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '*';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
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


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('pembelian/biayapembeliankredit/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['insertpembayaran'] = $this->url->link('pembelian/biayapembeliankredit/insertpembayaran', 'token=' . $this->session->data['token'].$url, 'SSL');

		/*$this->load->model('report/product');
        $this->load->model('catalog/product');
		*/

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->session->data['warning'])) {
			$this->data['warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else {
			$this->data['warning'] = '';
		}

		/*$this->load->model('catalog/product');
		$this->load->model('gudang/product');
		$this->load->model('pembelian/permintaanpembelian');
		$this->load->model('catalog/gudang');
		*/

		$this->load->model('catalog/vendorlokal');
		$this->load->model('pembelian/biayapembeliankredit');
		$this->load->model('keuangan/coa');

		$this->data['permintaans'] = array();
		$column=array('biaya_pembelian.*','suratjalan_pembeliandagang.no_suratjalan as invoice','jenis_biaya_pembelian.name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'suratjalan_pembeliandagang',
			'secondtable'	=>'suratjalan_pembeliandagang.id',
			'firsttable'	=> 'biaya_pembelian.order_id'
		);
		$join[]=array(
			'tablename'	=> 'jenis_biaya_pembelian',
			'secondtable'	=>'jenis_biaya_pembelian.id',
			'firsttable'	=> 'biaya_pembelian.jenisbiaya_id'
		);

		$leftjoin=array();


		$data = array(
			//'no_po'      =>array('LIKE',$filter_no_po),
			'suratjalan_pembeliandagang.id'      =>$filter_invoice,
		//	'suratjalan_pembeliandagang.status'	=> array('<>',3),
			'biaya_pembelian.no_faktur'=> $filter_no_faktur,
			//'biaya_pembelian.statuspembayaran'	=> $filter_status,

		);

		if($filter_status != '*'){
			if($filter_status == 0){
				$data['biaya_pembelian.statuspembayaran'] =array('<',1);
			}else{
				$data['biaya_pembelian.statuspembayaran'] =$filter_status;
			}
		}
		$limit=20;
		$offset=($page - 1) * 20;

		$order=array(
			'suratjalan_pembeliandagang.id'	=> 'DESC',
			'biaya_pembelian.id'	=> 'DESC'
		);

		$this->load->model('catalog/gudang');

		$product_total = $this->model_pembelian_biayapembeliankredit->totalPermintaans($data,$join,$leftjoin);

		$results = $this->model_pembelian_biayapembeliankredit->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);
		$this->data['url']=$url;
		foreach ($results as $result) {
			$action = array();
			if($result['statuspembayaran'] == 1 | $result['statuspembayaran'] == 0){
				$action[] = array(
					'text' => 'Batalkan Tagihan',
					'href' => $this->url->link('pembelian/biayapembeliankredit/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}


			if(!empty($result['akunhutangpajak'])){
				$hutangpajak=$this->model_keuangan_coa->getCategoryByKodeRek($result['akunhutangpajak']);
			}else{
				$hutangpajak=array('name'=>'Tanpa Hutang Pajak');
			}

			if(!empty($result['akunpajakdimuka'])){
				$pajakdimuka=$this->model_keuangan_coa->getCategoryByKodeRek($result['akunpajakdimuka']);
			}else{
				$pajakdimuka=array('name'=>'Tanpa Pajak Dimuka');
			}


			//riwayat pembayaran
			$pembayaran=$this->model_pembelian_biayapembeliankredit->getPembayaran(array('*'),array(),array('order_id'=>$result['id'],'status'=> 1),array('tgl_bayar'=>'ASC'));




			$this->data['permintaans'][] = array(
				'namabiaya'	=> $result['name'],
				'no_faktur'	=> $result['no_faktur'],
				'id'	=> $result['id'],
				'invoice'	=> $result['invoice'],
				'invoice_id'	=> $result['order_id'],
				'hrefinv'	=>$this->url->link('pembelian/invoicepembeliankredit/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['order_id'].$url, 'SSL'),
				'estimasibiaya'	=> $this->currency->format($result['total']),
				'totalreal'	=> $this->currency->format($result['totalreal']),
				'totalbayar'	=> $this->currency->format($result['totalbayar']),
				'nilaihutangpajak'	=> $this->currency->format($result['hutangpajak']),
				'hutangpajak'	=> $result['akunhutangpajak'].' '.$hutangpajak['name'],
				'nilaipajakdimuka'	=> $this->currency->format($result['pajakdimuka']),
				'pajakdimuka'	=> $result['akunpajakdimuka'].' '.$pajakdimuka['name'],
				'statushutangpajak'	=> $result['statushutangpajak'],
				'statuspajakdimuka'	=> $result['statuspajakdimuka'],
				'tanggal'	=> empty($result['tglfaktur'])?'Belum Dibuat Tagihan':date('d/m/y',strtotime($result['tglfaktur'])),
				'jatuhtempo'	=> empty($result['jatuhtempo'])?'Belum Dibuat Tagihan':date('d/m/y',strtotime($result['jatuhtempo'])),
				'tgllunas'	=> date('d/m/y',strtotime($result['tgllunas'])),
				'statuspembayaran'	=> $result['statuspembayaran'] == 1?'Ditagih':($result['statuspembayaran'] == 2?'Dibayar Sebagian':($result['statuspembayaran']==3?'Lunas':($result['statuspembayaran']==4?'Dibatalkan':'Belum Ada Tagihan'))),
				'status'=> $result['statuspembayaran'],
				'pembayaran'=>$pembayaran,
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Biaya Pembelian';

		$this->data['token'] = $this->session->data['token'];

		$url='';
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


		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = 20;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pembelian/biayapembeliankredit', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_faktur'] = $filter_no_faktur;
		$this->data['filter_invoice'] = $filter_invoice;
		$this->data['filter_status'] = $filter_status;

		$this->template = 'pembelian/biayapembeliankredit.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Biaya Pembelian Import');

		$this->load->model('pembelian/biayapembeliankredit');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_po=$this->model_pembelian_biayapembeliankredit->addPenjualan($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Biaya pembelian lokal berhasil ditambahkan ';

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

			$this->redirect($this->url->link('pembelian/biayapembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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


		$this->data['cancel']= $this->url->link('pembelian/biayapembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/biayapembeliankredit/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->template = 'pembelian/biayapembeliankredit_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	public function insertpembayaran() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Pembayaran Tagihan Biaya Pembelian');

		$this->load->model('pembelian/biayapembeliankredit');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_po=$this->model_pembelian_biayapembeliankredit->addPembayaran($this->request->post);

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

			$this->redirect($this->url->link('pembelian/biayapembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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


		$this->data['cancel']= $this->url->link('pembelian/biayapembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/biayapembeliankredit/insertpembayaran', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->template = 'pembelian/pembayaranbiayakredit_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function batalkan(){
		$this->load->model('pembelian/biayapembeliankredit');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
				$cek=$this->model_pembelian_biayapembeliankredit->getPenjualanDetail(array(),array(),array('id'  => $data['id']));
				if($cek['statuspembayaran'] == 0 | $cek['statuspembayaran'] == 1){
		      $this->model_pembelian_biayapembeliankredit->batalInvoice(array('id'	=> $this->request->get['id']));

					$this->session->data['success'] = 'Sukses: Data BiayaPembelian Import berhasil dibatalkan.';
				}else{
					$this->session->data['warning'] = 'Peringatan: Data BiayaPembelian Import gagal dibatalkan karena telah terdapat tagihan atau telah dibayar.';
				}
			}
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

			$this->redirect($this->url->link('pembelian/biayapembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function batalkanpembayaran(){
		$this->load->model('pembelian/biayapembeliankredit');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
				//$cek=$this->model_pembelian_biayapembeliankredit->getPe(array(),array(),array('id'  => $data['id']));
				//if($cek['statuspembayaran'] == 0 | $cek['statuspembayaran'] == 1){
		      $result=$this->model_pembelian_biayapembeliankredit->batalkanPembayaran($this->request->get['id']);

					$this->session->data['success'] = 'Sukses: Data Pembayaran Biaya Pembelian Import berhasil dibatalkan.';
				//$this->session->data['success'] =json_encode($result);
			}
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

			$this->redirect($this->url->link('pembelian/biayapembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('pembelian/biayapembeliankredit');

			if (isset($this->request->get['q'])) {
				$filter_no_po = $this->request->get['q'];
			} else {
				$filter_no_po = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_faktur'         => array('LIKE',$filter_no_po),
				'statuspembayaran'	=> array('<>',4)
			);
			$start=0;
			$limit=0;
			$column=array('id','no_faktur','totalreal');
			$join=array();
			$leftjoin=array();

			$results = $this->model_pembelian_biayapembeliankredit->getPermintaanPembelians($column,$join,$join,$data,array(),$limit,$start);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					//'text'	=> $r['no_po'].' Total $'.number_format($r['total_pembelian'])
					'text'	=> $r['no_faktur']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function autocompletepembayaran(){
		$rests = array();

		$this->load->model('pembelian/biayapembeliankredit');

			if (isset($this->request->get['q'])) {
				$filter_no_po = $this->request->get['q'];
			} else {
				$filter_no_po = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_faktur'         => array('LIKE',$filter_no_po),
				'statuspembayaran'	=> array('<>',4)
			);
			$start=0;
			$limit=0;
			$column=array('id','no_faktur','totalreal','statuspembayaran');
			$join=array();
			$leftjoin=array();

			$results = $this->model_pembelian_biayapembeliankredit->getPermintaanPembelians($column,$join,$join,$data,array(),$limit,$start);
			foreach($results as $r){
				if($r['statuspembayaran'] == 1 | $r['statuspembayaran'] == 2){
					$rests[]=array(
						'id'	=> $r['id'],
						//'text'	=> $r['no_po'].' Total $'.number_format($r['total_pembelian'])
						'text'	=> $r['no_faktur'].' Total Tagihan '.$this->currency->format($r['totalreal'])
					);
				}
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function detailbiaya(){
		$hasil = array();


		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){

			$this->load->model('pembelian/invoicepembeliankredit');
			/*
			0 belum ada
			1 ditagih
			2 dibayar sebagian
			3 Lunas
			4 Dibatalkan
			*/

			$data = array(
				'id'	=> $this->request->get['id'],

			//	'invoice_pembelian.statuspembayaran'	=> array('<',3)
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);

			$hasil=$this->model_pembelian_invoicepembeliankredit->getBiaya($data);
		}
	}
		$this->response->setOutput(json_encode($hasil));
	}







}
?>
