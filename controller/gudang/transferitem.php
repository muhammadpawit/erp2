<?php
class ControllerGudangTransferitem extends Controller {
	// baru 12 November 2019
	public function cekgudang(){
		$this->load->model('gudang/transferitem');
		$gudang_id = $this->request->get['gudang_id'];
		$gudang = $this->model_gudang_transferitem->cekgudang($gudang_id);
		print_r($gudang);
	}
	
	// end baru
	
	public function index() {
		$this->document->setTitle('Transfer Item');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = "";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = "";
		}

		if (isset($this->request->get['filter_invoice_no'])) {
			$filter_invoice_no = $this->request->get['filter_invoice_no'];
		} else {
			$filter_invoice_no = '';
		}

		if (isset($this->request->get['filter_tujuan'])) {
			$filter_tujuan = $this->request->get['filter_tujuan'];
		} else {
			$filter_tujuan = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['filter_jenis'])) {
			$filter_jenis = $this->request->get['filter_jenis'];
		} else {
			$filter_jenis = '';
		}

		if (isset($this->request->get['filter_gudang_asal'])) {
			$filter_gudang_asal = $this->request->get['filter_gudang_asal'];
			if(!empty($filter_gudang_asal)){
				if(!in_array($filter_gudang_asal,$this->user->getGudang())){
					$this->data['permission']=false;
					$filter_gudang_asal='';
				}

			}
		} else {
			$filter_gudang_asal = '';
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

		if (isset($this->request->get['filter_invoice_no'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_gudang_asal'])) {
			$url .= '&filter_gudang_asal=' . $this->request->get['filter_gudang_asal'];
		}

		if (isset($this->request->get['filter_tujuan'])) {
			$url .= '&filter_tujuan=' . $this->request->get['filter_tujuan'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('gudang/transferitem/insert', 'token=' . $this->session->data['token'], 'SSL');

		/*$this->load->model('report/product');
        $this->load->model('catalog/product');
		*/

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('catalog/product');
		$this->load->model('gudang/product');
		$this->load->model('gudang/transferitem');
		$this->load->model('catalog/gudang');

		$gudangs = $this->model_catalog_gudang->getGudangs(true);
		$this->data['gudangs']=$gudangs;

		$this->data['products'] = array();

		$data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_invoice_no'      =>$filter_invoice_no,
			'filter_gudang_asal'			=> $filter_gudang_asal,
			//'filter_jenis'	=> $filter_jenis,
			'filter_tujuan'			=> $filter_tujuan,
			'filter_status'			=> $filter_status,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$product_total = $this->model_gudang_transferitem->getTotalTransferitems($data,true);

		$results = $this->model_gudang_transferitem->getTransferitems($data,true);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('gudang/transferitem/tampil', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'].$url, 'SSL')
			);
			if($result['status'] == 2){
				$action[] = array(
					'text' => 'x',
					'href' => $this->url->link('gudang/transferitem/selisih', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'].$url, 'SSL')
				);
			}
			if($result['status'] != 3){
				$action[] = array(
					'text' => 'Cetak SJ',
					'href' => $this->url->link('gudang/transferitem/cetakbaru', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'].$url, 'SSL')
					//'href' => $this->url->link('gudang/transferitem/cetak', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'].$url, 'SSL')
				);
			}

			if($result['status'] == 0){
				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('gudang/transferitem/cancel', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'].$url, 'SSL')
				);
			}

			$this->data['products'][] = array(
				'asal'       => $result['asal'],
				'gudang_tujuan'       => $result['gudang_tujuan'],
				'order_id'	=> $result['order_id'],
				'invoice_no'	=> $result['invoice_no'],
				'no_dokumen'	=> $result['no_dokumen'],
				'date_added'	=> date('d F Y',strtotime($result['date_added'])),
				'total'	=> $result['jenis'] == 2?$result['total'].' Poin':$this->currency->format($result['total']),
				'totalterima' => $result['jenis'] == 2?$result['totalterima'].' Poin':$this->currency->format($result['totalterima']),
				'qtykirim'	=> $result['qtykirim'],
				'qtyterima'	=> $result['qtyterima'],
				'status'	=> $result['status'],
				//'jenis'	=> $result['jenis'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Transfer Item';

		$this->data['token'] = $this->session->data['token'];



		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_invoice_no'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_gudang_asal'])) {
			$url .= '&filter_gudang_asal=' . $this->request->get['filter_gudang_asal'];
		}

		if (isset($this->request->get['filter_tujuan'])) {
			$url .= '&filter_tujuan=' . $this->request->get['filter_tujuan'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);
		$this->data['tujuans']=$this->model_catalog_gudang->getGudangs(false);
		

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_invoice_no'] = $filter_invoice_no;
		$this->data['filter_gudang_asal'] = $filter_gudang_asal;
		$this->data['filter_tujuan'] = $filter_tujuan;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_jenis'] = $filter_jenis;

		$this->template = 'gudang/transferitem.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Transfer Item');

		$this->load->model('gudang/transferitem');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if($this->user->getUsername()=="pawit"){
				echo "<pre>";print_r($this->request->post);exit;
			}
			$this->model_gudang_transferitem->addTransferitem($this->request->post);

			$this->session->data['success'] = 'Sukses: Data transfer item berhasil disimpan.';

			$url = '';

			$url = '';

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}

			if (isset($this->request->get['filter_invoice_no'])) {
				$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
			}

			if (isset($this->request->get['filter_gudang_asal'])) {
				$url .= '&filter_gudang_asal=' . $this->request->get['filter_gudang_asal'];
			}

			if (isset($this->request->get['filter_tujuan'])) {
				$url .= '&filter_tujuan=' . $this->request->get['filter_tujuan'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_jenis'])) {
				$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('catalog/gudang');
		$url = '';

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_invoice_no'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_gudang_asal'])) {
			$url .= '&filter_gudang_asal=' . $this->request->get['filter_gudang_asal'];
		}

		if (isset($this->request->get['filter_tujuan'])) {
			$url .= '&filter_tujuan=' . $this->request->get['filter_tujuan'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
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

		$gudangs = $this->model_catalog_gudang->getGudangs(true);
		$this->data['gudangs']=$gudangs;

		$tujuans = $this->model_catalog_gudang->getGudangs(false);
		$this->data['tujuans']=$tujuans;

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->post['gudang_id'])) {
			$this->data['gudang_id'] = $this->request->post['gudang_id'];
		}  else {
			$this->data['gudang_id'] = '';
		}
		$this->data['cancel']= $this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('gudang/transferitem/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->request->post['product'])) {
			$this->data['products'] = $this->request->post['product'];
		} else {
			$this->data['products'] = '';
		}

		$this->template = 'gudang/transferitem_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {
    	/*if (!$this->user->hasPermission('modify', 'gudang/transferitem')) {
      		$this->error['warning'] = 'Permission Denied.';
    	}

    	/*if (empty($this->request->post['date_added'])) {
      		$this->error['warning'] = 'Tanggal input product cacat harus diisi';
    	}*/

		if (empty($this->request->post['product'])) {
		  		$this->error['warning'] = 'Produk tidak boleh kosong';
			}

    	if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function tampil(){
		$this->document->setTitle('Transfer Item');
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_invoice_no'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_gudang_asal'])) {
			$url .= '&filter_gudang_asal=' . $this->request->get['filter_gudang_asal'];
		}

		if (isset($this->request->get['filter_tujuan'])) {
			$url .= '&filter_tujuan=' . $this->request->get['filter_tujuan'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('gudang/product');
		$this->load->model('gudang/transferitem');

		$trans=$this->model_gudang_transferitem->getTransferitem($order_id);
		if($this->user->getUsername()=="pawist"){
			echo "<pre>";print_r($trans);exit;
		}
		$this->data['transfer']=$trans;
		$detailtrans=$this->model_gudang_transferitem->getTransferItemCetak($order_id);
		$this->data['detailtransfer']=$detailtrans;
		$this->data['cancel']= $this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['cetakjakarta']=$this->url->link('gudang/transferitem/cetakbaru', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id.$url, 'SSL');
		$this->template = 'gudang/transferitem_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	public function cetakbaru(){
		$this->document->setTitle('Transfer Item');
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_invoice_no'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_gudang_asal'])) {
			$url .= '&filter_gudang_asal=' . $this->request->get['filter_gudang_asal'];
		}

		if (isset($this->request->get['filter_tujuan'])) {
			$url .= '&filter_tujuan=' . $this->request->get['filter_tujuan'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('gudang/product');
		$this->load->model('gudang/transferitem');

		$trans=$this->model_gudang_transferitem->getTransferitem($order_id);

		$this->data['transfer']=$trans;
		$detailtrans=$this->model_gudang_transferitem->getTransferItemCetak($order_id);
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";
			print_r($trans);
			exit;
		}
		$this->data['detailtransfer']=$detailtrans;
		$this->data['cancel']= $this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['cetakjakarta']=$this->url->link('gudang/transferitem/cetakbaru', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id.$url, 'SSL');
		$this->template = 'gudang/cetaktransferitem_suratjalan.tpl';
		//$this->template = 'gudang/transferitem_suratjalan.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	
	public function cetak(){
		$this->document->setTitle('Transfer Item');
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('gudang/product');
		$this->load->model('gudang/transferitem');

		$trans=$this->model_gudang_transferitem->getTransferItemCetak($order_id);
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";
			print_r($trans);
			exit;
		}
		$this->data['transfer']=$trans;

		$this->template = 'gudang/transferitem_suratjalan.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function cancel(){
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

    if (isset($this->request->get['filter_invoice_no'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_gudang_asal'])) {
			$url .= '&filter_gudang_asal=' . $this->request->get['filter_gudang_asal'];
		}

		if (isset($this->request->get['filter_tujuan'])) {
			$url .= '&filter_tujuan=' . $this->request->get['filter_tujuan'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('gudang/product');
		$this->load->model('gudang/transferitem');
		$this->model_gudang_transferitem->cancelTransfer($order_id);

		$this->session->data['success'] = 'Sukses: Data transfer item berhasil dibatalkan.';

		$$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

    if (isset($this->request->get['filter_invoice_no'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_gudang_asal'])) {
			$url .= '&filter_gudang_asal=' . $this->request->get['filter_gudang_asal'];
		}

		if (isset($this->request->get['filter_tujuan'])) {
			$url .= '&filter_tujuan=' . $this->request->get['filter_tujuan'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->redirect($this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function selisih(){
		$this->document->setTitle('Terima Transfer');
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

    if (isset($this->request->get['filter_invoice_no'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_gudang_asal'])) {
			$url .= '&filter_gudang_asal=' . $this->request->get['filter_gudang_asal'];
		}

		if (isset($this->request->get['filter_tujuan'])) {
			$url .= '&filter_tujuan=' . $this->request->get['filter_tujuan'];
		}

		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('gudang/product');
		$this->load->model('gudang/transferitem');

		$trans=$this->model_gudang_transferitem->getTransferitem($order_id);

		if($trans['detail']['status'] != 2){
			$this->redirect($this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->data['transfer']=$trans;
		$this->data['cancel']= $this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('gudang/transferitem/terima', 'token=' . $this->session->data['token'] .'&order_id='.$order_id. $url, 'SSL');

		$this->template = 'gudang/selisihtransfer.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function terima(){
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

    if (isset($this->request->get['filter_invoice_no'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_gudang_asal'])) {
			$url .= '&filter_gudang_asal=' . $this->request->get['filter_gudang_asal'];
		}

		if (isset($this->request->get['filter_tujuan'])) {
			$url .= '&filter_tujuan=' . $this->request->get['filter_tujuan'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('gudang/product');
		$this->load->model('gudang/transferitem');

		$this->model_gudang_transferitem->selisih($order_id,$this->request->post);

		$this->session->data['success'] = 'Sukses: Data selisih transfer berhasil diproses.';

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

    if (isset($this->request->get['filter_invoice_no'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_gudang_asal'])) {
			$url .= '&filter_gudang_asal=' . $this->request->get['filter_gudang_asal'];
		}

		if (isset($this->request->get['filter_tujuan'])) {
			$url .= '&filter_tujuan=' . $this->request->get['filter_tujuan'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->redirect($this->url->link('gudang/transferitem', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

}
?>
