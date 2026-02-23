<?php
class ControllerKeuanganTerimadana extends Controller {
	private $error=array();
	// baru 22 Januari 2020
	public function terimahutanglain(){
				$this->document->setTitle('Penerimaan Pembayaran Customer');
		$this->load->model('keuangan/penerimaandana');
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
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_bank_id'])) {
			$url .= '&filter_bank_id=' . $this->request->get['filter_bank_id'];
		}

		if (isset($this->request->get['filter_metode'])) {
			$url .= '&filter_metode=' . $this->request->get['filter_metode'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_no_giro'])) {
			$url .= '&filter_no_giro=' . $this->request->get['filter_no_giro'];
		}
		if (isset($this->request->get['id'])) {
			$url .= '&id=' . $this->request->get['id'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			//,'keterangan'=>$this->request->post['keterangan']
			if($this->user->getUsername()=="pawitss"){
				echo "<pre>";print_r($this->request->post);exit;
			}
		    $no_po=$this->model_keuangan_penerimaandana->updatePermintaan(array('status'=>2,'tgl_diterima'=>$this->request->post['tgl_diterima'],'keterangan'=>$this->request->post['keterangan']),array('id'=>$id));
			if($this->user->getUsername()=="pawit"){
				echo "<pre>";print_r($no_po);exit;
			}
			$this->session->data['success'] = 'Sukses: Data Pembayaran Customer  berhasil diterima';
			$this->redirect($this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/customer');
		$this->load->model('catalog/title');
		$this->load->model('sale/invoice');
		$this->load->model('keuangan/bank');

		$column=array('penerimaan_dana.*','customer.name as cname','customer.title');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=>'penerimaan_dana.customer_id',
			'secondtable'	=> 'customer.customer_id'
		);
		$data = array(
			'penerimaan_dana.id'	=> $id,

		);

		$trans=$this->model_keuangan_penerimaandana->getPermintaanPembelian($column,$join,$data);
		$trans['customer']=$this->model_catalog_title->getTitle($trans['title']).' '.$trans['cname'];
		if(!empty($trans['ref'])){
			$trans['inv']=$this->model_sale_invoice->getPenjualan($trans['ref']);
			$trans['href']=$this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $trans['ref'], 'SSL');
		}
		//$trans['terbilang']=ucwords($this->terbilang($trans['nominal'])).' Rupiah';
		$this->data['penerimaan']=$trans;
		$this->data['cancel']= $this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('keuangan/terimadana/terimahl', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->template = 'keuangan/penerimaandana_terima.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function terimahl(){
		$this->document->setTitle('Penerimaan Pembayaran Customer');
		$this->load->model('keuangan/penerimaandana');
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
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_bank_id'])) {
			$url .= '&filter_bank_id=' . $this->request->get['filter_bank_id'];
		}

		if (isset($this->request->get['filter_metode'])) {
			$url .= '&filter_metode=' . $this->request->get['filter_metode'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_no_giro'])) {
			$url .= '&filter_no_giro=' . $this->request->get['filter_no_giro'];
		}
		if (isset($this->request->get['id'])) {
			$url .= '&id=' . $this->request->get['id'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			//,'keterangan'=>$this->request->post['keterangan']
			if($this->user->getUsername()=="pawits"){
				echo "<pre>";print_r($this->request->post);exit;
			}
		    $no_po=$this->model_keuangan_penerimaandana->terimadanahutanglain(array('status'=>2,'tgl_diterima'=>$this->request->post['tgl_diterima'],'keterangan'=>$this->request->post['keterangan'],'id'=>$id));
			if($this->user->getUsername()=="pawits"){
				echo "<pre>";print_r($no_po);exit;
			}
			$this->session->data['success'] = 'Sukses: Data Pembayaran Customer  berhasil diterima';



			$this->redirect($this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/customer');
		$this->load->model('catalog/title');
		$this->load->model('sale/invoice');
		$this->load->model('keuangan/bank');

		$column=array('penerimaan_dana.*','customer.name as cname','customer.title','banks.name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=>'penerimaan_dana.customer_id',
			'secondtable'	=> 'customer.customer_id'
		);
		$join[]=array(
			'tablename'	=> 'banks',
			'firsttable'	=>'penerimaan_dana.bank_id',
			'secondtable'	=> 'banks.id'
		);

		$data = array(
			'penerimaan_dana.id'	=> $id,

		);

		$trans=$this->model_keuangan_penerimaandana->getPermintaanPembelian($column,$join,$data);
		$trans['customer']=$this->model_catalog_title->getTitle($trans['title']).' '.$trans['cname'];
		if(!empty($trans['ref'])){
			$trans['inv']=$this->model_sale_invoice->getPenjualan($trans['ref']);
			$trans['href']=$this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $trans['ref'], 'SSL');
		}
		//$trans['terbilang']=ucwords($this->terbilang($trans['nominal'])).' Rupiah';
		$this->data['penerimaan']=$trans;
		$this->data['cancel']= $this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('keuangan/terimadana/terima', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->template = 'keuangan/penerimaandana_terima.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	// end baru
	public function index() {
		$this->document->setTitle('Penerimaan Dana Customer');

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

		if (isset($this->request->get['filter_no_giro'])) {
			$filter_no_giro = $this->request->get['filter_no_giro'];
		} else {
			$filter_no_giro = null;
		}

		if (isset($this->request->get['filter_jenis'])) {
			$filter_jenis = $this->request->get['filter_jenis'];
		} else {
			$filter_jenis = null;
		}

		if (isset($this->request->get['filter_metode'])) {
			$filter_metode = $this->request->get['filter_metode'];
		} else {
			$filter_metode = null;
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}

		if (isset($this->request->get['filter_bank_id'])) {
			$filter_bank_id = $this->request->get['filter_bank_id'];
		} else {
			$filter_bank_id = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
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
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_bank_id'])) {
			$url .= '&filter_bank_id=' . $this->request->get['filter_bank_id'];
		}

		if (isset($this->request->get['filter_metode'])) {
			$url .= '&filter_metode=' . $this->request->get['filter_metode'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_no_giro'])) {
			$url .= '&filter_no_giro=' . $this->request->get['filter_no_giro'];
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

		$this->load->model('keuangan/penerimaandana');
		$this->load->model('catalog/title');
		$this->load->model('sale/invoice');

		$this->data['permintaans'] = array();
		$column=array('penerimaan_dana.*','banks.name as nama_bank','customer.name','customer.title');
		$join=array();
		$join[]=array(
			'tablename'	=> 'banks',
			'firsttable'	=>'penerimaan_dana.bank_id',
			'secondtable'	=> 'banks.id'
		);
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=>'penerimaan_dana.customer_id',
			'secondtable'	=> 'customer.customer_id'
		);
		/*$join[]=array(
			'tablename'	=> 'coamnb',
			'firsttable'	=>'biaya_operasional.coa_id',
			'secondtable'	=> 'coamnb.category_id'
		);*/
		if(!empty($filter_date_start) && !empty($filter_date_end)){
			$data=array(
				'penerimaan_dana.tgl_bayar '      =>!empty($filter_date_start)?array(">=",$filter_date_start):array('>','1901-01-01'),
				'penerimaan_dana.tgl_bayar'      =>!empty($filter_date_end)?array("<=",$filter_date_end):array('>','1901-01-01'),
			);
		}
		else if(!empty($filter_date_start) && empty($filter_date_end)){
			$data=array(
				'penerimaan_dana.tgl_bayar '      =>!empty($filter_date_start)?$filter_date_start:array('>','1901-01-01'),
			);
		}
		else if(empty($filter_date_start) && !empty($filter_date_end)){
			$data=array(
				'penerimaan_dana.tgl_bayar '      =>!empty($filter_date_end)?$filter_date_end:array('>','1901-01-01'),
			);
		}
		else{
			$data=array(
				'penerimaan_dana.tgl_bayar '      =>!empty($filter_date_end)?$filter_date_end:array('>','1901-01-01'),
			);
		}

		$data+=array(
		//	'penerimaan_dana.status'	=> !empty($filter_status)?$filter_status:array('<=',3),
			'penerimaan_dana.customer_id'	=> !empty($filter_customer_id)?$filter_customer_id:array('>=',1),
			'penerimaan_dana.jenis'	=> !empty($filter_jenis)?$filter_jenis:array('>=',1),
			'penerimaan_dana.metode_pembayaran'	=> !empty($filter_metode)?$filter_metode:array('>=',1),
			'penerimaan_dana.status'	=> !empty($filter_status)?$filter_status:array('>=',1),
			'penerimaan_dana.no_giro'	=> $filter_no_giro != null ?array('LIKE',$filter_no_giro):'',
			'penerimaan_dana.hapus'	=> array('=',0),
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'tgl_bayar'	=> 'DESC',
			//'id'	=> 'DESC',
		);

		$filter = array(
			'tgl_awal' => $filter_date_start,
			'tgl_akhir' => $filter_date_end,
			'customer_id' => $filter_customer_id,
			'jenis' => $jenis,
			'status' => $filter_status,
			'metode' => $filter_metode,
			//'column' => 'tgl_bayar',
			//'order' => 'DESC',
			//'limit' =>20,
			//'offset' => ($page - 1) * $this->config->get('config_admin_limit'),
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'            => $this->config->get('config_admin_limit')
		);

		//$product_total = $this->model_keuangan_penerimaandana->totalPermintaans($data);
		$results = $this->model_keuangan_penerimaandana->getPermintaanPembelians($column,$join,$data,$order,$limit,$offset);
		
		//print_r($data);
		$r = $this->model_keuangan_penerimaandana->getpenerimaandana($filter);
		if($this->user->getUsername()=="pawit"){
			$product_total = count($this->model_keuangan_penerimaandana->totalgetpenerimaandana($filter));
			$loop=$r;
		}else{
			$product_total = count($this->model_keuangan_penerimaandana->totalgetpenerimaandana($filter));
			$loop=$r;
		}
		foreach ($loop as $result) {
			$action = array();
			if($result['status'] == 1){
				if($result['metode_pembayaran']==6){
					$action[] = array(
						'text' => 'Terima Dana',
						'href' => $this->url->link('keuangan/terimadana/terimametodebiaya', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
					);
				}
				if($result['metode_pembayaran']==5){
					$action[] = array(
						'text' => 'Terima Dana',
						'href' => $this->url->link('keuangan/terimadana/terimahutanglain', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
					);
				}else{
					$action[] = array(
						'text' => 'Terima Dana',
						'href' => $this->url->link('keuangan/terimadana/terima', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
					);
				}
				
				if($result['metode_pembayaran']==2 OR $result['metode_pembayaran']==1 OR $result['metode_pembayaran']==3){
					$action[] = array(
						'text' => 'Tolak',
						'href' => $this->url->link('keuangan/terimadana/tolak', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
					);
				}
			}
			
			if($result['status'] == 2){
				$action[] = array(
						'text' => 'Batalkan',
						'href' => $this->url->link('keuangan/terimadana/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}

			if(!empty($result['ref'])){
				$ref=$this->model_sale_invoice->getPenjualan(array('id'=>$result['ref']));
			}

			$cst = $this->model_keuangan_penerimaandana->getcust($result['customer_id']);
			$bnk = $this->model_keuangan_penerimaandana->getbank($result['bank_id']);
			
			$this->data['permintaans'][] = array(
				//'nama_bank'	=> $result['nama_bank'],
				'nama_bank'	=> $bnk['name'],
				'id'	=> $result['id'],
				'jumlah'	=> $this->currency->format($result['nominal']),
				'tanggal'	=> date('d/m/y',strtotime($result['tgl_bayar'])),
				'tanggalditerima'	=> date('d/m/y',strtotime($result['tgl_diterima'])) == '01/01/70'?'Belum Diterima':date('d/m/y',strtotime($result['tgl_diterima'])),
				'status'	=> $result['status'],
				'keterangan'	=> $result['keterangan'],
				'ref'	=> empty($result['ref'])?'':$ref['no_faktur'],
				'href'	=>$this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['ref'], 'SSL'),
				'jenis'	=> $result['jenis'],
				'no_giro'	=> $result['no_giro'],
				'biaya'	=> $this->currency->format($result['biaya_lain']),
				'pendapatan'	=> $this->currency->format($result['pendapatan_lain']),
				'jenis'	=> $result['jenis'],
				'metode_pembayaran'	=> $result['metode_pembayaran'],
				//'customer'	=> $this->model_catalog_title->getTitle($result['title']).' '.$result['name'],
				'customer' => $this->model_catalog_title->getTitle($cst['title']).' '. $cst['name'],
				'customer_id' =>$result['customer_id'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Penerimaan Pembayaran Customer';

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
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_bank_id'])) {
			$url .= '&filter_bank_id=' . $this->request->get['filter_bank_id'];
		}

		if (isset($this->request->get['filter_metode'])) {
			$url .= '&filter_metode=' . $this->request->get['filter_metode'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_no_giro'])) {
			$url .= '&filter_no_giro=' . $this->request->get['filter_no_giro'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);
		$this->data['filter_tgl_awal'] = $filter_tgl_awal;
		$this->data['filter_tgl_akhir'] = $filter_tgl_akhir;
		$this->data['filter_jenis'] = $filter_jenis;
		$this->template = 'keuangan/terimadana.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}


	public function terima(){
		$this->document->setTitle('Penerimaan Pembayaran Customer');
		$this->load->model('keuangan/penerimaandana');
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
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_bank_id'])) {
			$url .= '&filter_bank_id=' . $this->request->get['filter_bank_id'];
		}

		if (isset($this->request->get['filter_metode'])) {
			$url .= '&filter_metode=' . $this->request->get['filter_metode'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_no_giro'])) {
			$url .= '&filter_no_giro=' . $this->request->get['filter_no_giro'];
		}
		if (isset($this->request->get['id'])) {
			$url .= '&id=' . $this->request->get['id'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			//,'keterangan'=>$this->request->post['keterangan']
			if($this->user->getUsername()=="pawits"){
				echo "<pre>";print_r($this->request->post);exit;
			}
		    $no_po=$this->model_keuangan_penerimaandana->updatePermintaan(array('status'=>2,'tgl_diterima'=>$this->request->post['tgl_diterima'],'keterangan'=>$this->request->post['keterangan']),array('id'=>$id));
			if($this->user->getUsername()=="pawits"){
				echo "<pre>";print_r($no_po);exit;
			}
			$this->session->data['success'] = 'Sukses: Data Pembayaran Customer  berhasil diterima';



			$this->redirect($this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/customer');
		$this->load->model('catalog/title');
		$this->load->model('sale/invoice');
		$this->load->model('keuangan/bank');

		$column=array('penerimaan_dana.*','customer.name as cname','customer.title','banks.name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=>'penerimaan_dana.customer_id',
			'secondtable'	=> 'customer.customer_id'
		);
		$join[]=array(
			'tablename'	=> 'banks',
			'firsttable'	=>'penerimaan_dana.bank_id',
			'secondtable'	=> 'banks.id'
		);

		$data = array(
			'penerimaan_dana.id'	=> $id,

		);

		$trans=$this->model_keuangan_penerimaandana->getPermintaanPembelian($column,$join,$data);
		$trans['customer']=$this->model_catalog_title->getTitle($trans['title']).' '.$trans['cname'];
		if(!empty($trans['ref'])){
			$trans['inv']=$this->model_sale_invoice->getPenjualan($trans['ref']);
			$trans['href']=$this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $trans['ref'], 'SSL');
		}
		//$trans['terbilang']=ucwords($this->terbilang($trans['nominal'])).' Rupiah';
		$this->data['penerimaan']=$trans;
		$this->data['cancel']= $this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('keuangan/terimadana/terima', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->template = 'keuangan/penerimaandana_terima.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	// baru 17 Desember 2019
	public function terimametodebiaya(){
		$this->document->setTitle('Penerimaan Pembayaran Customer');
		$this->load->model('keuangan/penerimaandana');
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
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_bank_id'])) {
			$url .= '&filter_bank_id=' . $this->request->get['filter_bank_id'];
		}

		if (isset($this->request->get['filter_metode'])) {
			$url .= '&filter_metode=' . $this->request->get['filter_metode'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_no_giro'])) {
			$url .= '&filter_no_giro=' . $this->request->get['filter_no_giro'];
		}
		if (isset($this->request->get['id'])) {
			$url .= '&id=' . $this->request->get['id'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			//,'keterangan'=>$this->request->post['keterangan']
			if($this->user->getUsername()=="pawitss"){
				echo "<pre>";print_r($this->request->post);exit;
			}
		    $no_po=$this->model_keuangan_penerimaandana->updatePermintaan(array('status'=>2,'tgl_diterima'=>$this->request->post['tgl_diterima'],'keterangan'=>$this->request->post['keterangan']),array('id'=>$id));
			if($this->user->getUsername()=="pawit"){
				echo "<pre>";print_r($no_po);exit;
			}
			$this->session->data['success'] = 'Sukses: Data Pembayaran Customer  berhasil diterima';



			$this->redirect($this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/customer');
		$this->load->model('catalog/title');
		$this->load->model('sale/invoice');
		$this->load->model('keuangan/bank');

		$column=array('penerimaan_dana.*','customer.name as cname','customer.title');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=>'penerimaan_dana.customer_id',
			'secondtable'	=> 'customer.customer_id'
		);
		/*
		$join[]=array(
			'tablename'	=> 'banks',
			'firsttable'	=>'penerimaan_dana.bank_id',
			'secondtable'	=> 'banks.id'
		);
		*/
		$data = array(
			'penerimaan_dana.id'	=> $id,

		);

		$trans=$this->model_keuangan_penerimaandana->getPermintaanPembelian($column,$join,$data);
		$trans['customer']=$this->model_catalog_title->getTitle($trans['title']).' '.$trans['cname'];
		if(!empty($trans['ref'])){
			$trans['inv']=$this->model_sale_invoice->getPenjualan($trans['ref']);
			$trans['href']=$this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $trans['ref'], 'SSL');
		}
		//$trans['terbilang']=ucwords($this->terbilang($trans['nominal'])).' Rupiah';
		$this->data['penerimaan']=$trans;
		$this->data['cancel']= $this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('keuangan/terimadana/terima', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->template = 'keuangan/penerimaandana_terima.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	// end baru
	
	// baru 25 Oktober 2019
	public function tolak(){
		$this->load->model('keuangan/penerimaandana');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
			$this->model_keuangan_penerimaandana->updatePermintaan(array('status' => 4),array('id'	=> $this->request->get['id']));
			//echo $this->request->get['id'];exit;
			//$this->db->query("UPDATE penerimaan_dana set status=4 WHERE id='".$this->request->get['id']."' ");
			$this->session->data['success'] = 'Sukses: Data Penerimaan Dana berhasil ditolak.';
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
			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
			}
			if (isset($this->request->get['filter_bank_id'])) {
				$url .= '&filter_bank_id=' . $this->request->get['filter_bank_id'];
			}

			if (isset($this->request->get['filter_metode'])) {
				$url .= '&filter_metode=' . $this->request->get['filter_metode'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_no_giro'])) {
				$url .= '&filter_no_giro=' . $this->request->get['filter_no_giro'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	public function batalkan(){
		$this->load->model('keuangan/penerimaandana');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
			$p = $this->model_keuangan_penerimaandana->updatePermintaan(array('status' => 3),array('id'	=> $this->request->get['id']));
			if($this->user->getUsername()=="pawits"){
				echo "<pre>";print_r($p);exit;
			}
			$this->session->data['success'] = 'Sukses: Data Penerimaan Dana berhasil dibatalkan.';
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
			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
			}
			if (isset($this->request->get['filter_bank_id'])) {
				$url .= '&filter_bank_id=' . $this->request->get['filter_bank_id'];
			}

			if (isset($this->request->get['filter_metode'])) {
				$url .= '&filter_metode=' . $this->request->get['filter_metode'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_no_giro'])) {
				$url .= '&filter_no_giro=' . $this->request->get['filter_no_giro'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('keuangan/terimadana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	// end baru

}
?>
