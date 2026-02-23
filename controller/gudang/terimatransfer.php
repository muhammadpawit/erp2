<?php
class ControllerGudangTerimatransfer extends Controller {
	public function index() {
		$this->document->setTitle('Terima Transfer');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d',strtotime('last week'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
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
			'filter_tujuan'			=> $filter_tujuan,
			'filter_status'			=> $filter_status,
			'filter_jenis'	=> $filter_jenis,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$product_total = $this->model_gudang_transferitem->getTotalTransferitems($data,false);

		$results = $this->model_gudang_transferitem->getTransferitems($data,false);
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";
			print_r($gudangs);
			exit;
		}
		foreach ($results as $result) {
			$action = array();
			if($result['status'] == 0  & $result['invoice_no'] != 'in process'){
				$action[] = array(
					'text' => 'Terima Transfer',
					'href' => $this->url->link('gudang/terimatransfer/tampil', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'].$url, 'SSL')
				);
			}

					
			$namagudang = $this->model_catalog_gudang->getGudang($result['tujuan']);

			$this->data['products'][] = array(
				'asal'       => $result['asal'],
				//'gudang_tujuan'       => $result['gudang_tujuan'],
				'gudang_tujuan'       => $namagudang['nama'],
				'order_id'	=> $result['order_id'],
				'invoice_no'	=> $result['invoice_no'],
				'no_terimadokumen'	=> $result['no_terimadokumen'],
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

		$this->data['heading_title'] = 'Terima Transfer';

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
		$pagination->url = $this->url->link('gudang/terimatransfer', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(false);
		$this->data['tujuans']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_invoice_no'] = $filter_invoice_no;
		$this->data['filter_gudang_asal'] = $filter_gudang_asal;
		$this->data['filter_tujuan'] = $filter_tujuan;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_jenis']	= $filter_jenis;

		$this->template = 'gudang/terimatransfer.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}



	public function tampil(){
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
				$this->redirect($this->url->link('gudang/terimatransfer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('gudang/terimatransfer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('gudang/product');
		$this->load->model('gudang/transferitem');

		$trans=$this->model_gudang_transferitem->getTransferitem($order_id);

		if($trans['detail']['status'] != 0 & $trans['detail']['status'] != 1){
			$this->redirect($this->url->link('gudang/terimatransfer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->data['transfer']=$trans;
		$this->data['cancel']= $this->url->link('gudang/terimatransfer', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('gudang/terimatransfer/terima', 'token=' . $this->session->data['token'] .'&order_id='.$order_id. $url, 'SSL');

		$this->template = 'gudang/terimtransfer_info.tpl';
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
				$this->redirect($this->url->link('gudang/terimatransfer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('gudang/terimatransfer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('gudang/product');
		$this->load->model('gudang/transferitem');
		$s = $this->model_gudang_transferitem->terimaTransfer($order_id,$this->request->post);
		$this->session->data['success'] = 'Sukses: Transfer item berhasil diterima.';

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

		$this->redirect($this->url->link('gudang/terimatransfer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	public function cetak(){
		$this->document->setTitle('Terima Transfer');
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

		$this->session->data['success'] = 'Sukses: Data Terima Transfer berhasil dibatalkan.';

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

}
?>
