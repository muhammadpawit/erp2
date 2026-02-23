<?php
class ControllerSalePpnmasukan extends Controller {
	private $error = array();

	// baru 24 Agustus 2019
	
	public function export() {
		$this->load->language('catalog/category');

		$this->document->setTitle('PPN Masukan');

		$this->load->model('sale/invoice');

		$this->getListExport();
	}
	
	private function getListExport() {
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/ppnmasukan/fakturpajak', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/invoice/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['penjualans'] = array();
		$this->load->model('catalog/gudang');
		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs(true);
		$gudangs=array();
		if(empty($filter_gudang_id)){
			foreach($this->data['gudangs'] as $g){
				$gudangs[]=$g['gudang_id'];
			}
		}else{
			$gudangs[]=$filter_gudang_id;
		}

		$arrsql=implode(',',$gudangs);

		//$column=array('aset.penjualan_pameran_id','aset.name as name','aset.tglpembelian','aset.hargabeli','aset.status','kelompok_aset.name as kelompok','kelompok_aset.jenis_aset as jenis');
				$column=array('invoice.*','customer.name','customer.email','customer.telephone');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'invoice.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);

		$data = array(
			'invoice.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'invoice.gudang_id'	=>array('IN',$arrsql),
			'invoice.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			'invoice.status'	=> empty($filter_status)?array('<>',4):$filter_status,
			'invoice.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):$filter_tanggal
		);

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$data['customer.sales']=$this->user->getId();
		}

		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),5);


		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$order=array();
		$order=array('invoice.date_added' => 'DESC','invoice.id'=>'DESC');
		//$this->load->model('sale/invoice');

		$results = $this->model_sale_invoice->getPenjualans($column,$join,$data,$order,$limit,$offset);
		$pt = $this->model_sale_invoice->getPenjualans($column,$join,$data,$order,0,null);
		$product_total=count($pt);
		//print_r($results);
		foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('sale/ppnmasukan/updatefakturpajak', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);


			$namagudang=$this->model_catalog_gudang->getGudang($result['gudang_id']);

			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'no_faktur'        => $result['no_faktur'],
				'name'	=> $result['name'],
				'no_fakturpajak'	=> $result['no_fakturpajak'],
				'namagudang'	=> $namagudang['nama'],
				'dpp'	=> $this->currency->format($result['sub_total']),
				'ppn'	=> $this->currency->format($result['pajak']),
				'tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'      => $action
			);
		}

		if (isset($this->session->data['warning'])) {
			$this->data['warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}


		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_tanggal']	= $filter_tanggal;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'sale/ppnmasukan_listexport.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	// End Baru 
	
	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('PPN Masukan');

		$this->load->model('sale/invoice');

		$this->getList();
	}

	/*
	private function getList() {
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/ppnmasukan/fakturpajak', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/invoice/delete', 'token=' . $this->session->data['token'].$url, 'SSL');
		
		// baru 24 Agustus 2019
		$this->data['export'] = $this->url->link('sale/ppnmasukan/export', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['penjualans'] = array();
		$this->load->model('catalog/gudang');
		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs(true);
		$gudangs=array();
		if(empty($filter_gudang_id)){
			foreach($this->data['gudangs'] as $g){
				$gudangs[]=$g['gudang_id'];
			}
		}else{
			$gudangs[]=$filter_gudang_id;
		}

		$arrsql=implode(',',$gudangs);

		//$column=array('aset.penjualan_pameran_id','aset.name as name','aset.tglpembelian','aset.hargabeli','aset.status','kelompok_aset.name as kelompok','kelompok_aset.jenis_aset as jenis');
				$column=array('invoice.*','customer.name','customer.email','customer.telephone');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'invoice.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);

		$data = array(
			'invoice.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'invoice.gudang_id'	=>array('IN',$arrsql),
			'invoice.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			'invoice.status'	=> empty($filter_status)?array('<>',4):$filter_status,
			'invoice.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):$filter_tanggal
		);

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$data['customer.sales']=$this->user->getId();
		}

		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),5);


		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$order=array();
		$order=array('invoice.date_added' => 'DESC','invoice.id'=>'DESC');
		//$this->load->model('sale/invoice');

		$results = $this->model_sale_invoice->getPenjualans($column,$join,$data,$order,$limit,$offset);
		$pt = $this->model_sale_invoice->getPenjualans($column,$join,$data,$order,0,null);
		$product_total=count($pt);
		//print_r($results);
		foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('sale/ppnmasukan/updatefakturpajak', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);


			$namagudang=$this->model_catalog_gudang->getGudang($result['gudang_id']);

			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'no_faktur'        => $result['no_faktur'],
				'name'	=> $result['name'],
				'no_fakturpajak'	=> $result['no_fakturpajak'],
				'namagudang'	=> $namagudang['nama'],
				'dpp'	=> $this->currency->format($result['sub_total']),
				'ppn'	=> $this->currency->format($result['pajak']),
				'tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'      => $action
			);
		}

		if (isset($this->session->data['warning'])) {
			$this->data['warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}


		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_tanggal']	= $filter_tanggal;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'sale/ppnmasukan_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}*/

	// baru 12 mei 2020
	private function getList() {
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = null;
		}

		if (isset($this->request->get['filter_tanggal2'])) {
			$filter_tanggal2 = $this->request->get['filter_tanggal2'];
		} else {
			$filter_tanggal2 = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_tanggal2'])) {
			$url .= '&filter_tanggal2=' . $this->request->get['filter_tanggal2'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/ppnmasukan/fakturpajak', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/invoice/delete', 'token=' . $this->session->data['token'].$url, 'SSL');
		
		// baru 24 Agustus 2019
		$this->data['export'] = $this->url->link('sale/ppnmasukan/export', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['penjualans'] = array();
		$this->load->model('catalog/gudang');
		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs(true);
		$gudangs=array();
		if(empty($filter_gudang_id)){
			foreach($this->data['gudangs'] as $g){
				$gudangs[]=$g['gudang_id'];
			}
		}else{
			$gudangs[]=$filter_gudang_id;
		}

		$arrsql=implode(',',$gudangs);

		//$column=array('aset.penjualan_pameran_id','aset.name as name','aset.tglpembelian','aset.hargabeli','aset.status','kelompok_aset.name as kelompok','kelompok_aset.jenis_aset as jenis');
				$column=array('invoice.*','customer.name','customer.email','customer.telephone');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'invoice.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);

		$data = array(
			'invoice.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'invoice.gudang_id'	=>array('IN',$arrsql),
			'invoice.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			'invoice.status'	=> empty($filter_status)?array('<>',4):$filter_status,
			'invoice.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):array("BETWEEN '".$filter_tanggal."' AND ",$filter_tanggal2),
			//'invoice.date_added'	=> empty($filter_tanggal2)?array('>','1901-01-01'):array('<=',$filter_tanggal2),
		);

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$data['customer.sales']=$this->user->getId();
		}

		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),5);


		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$order=array();
		$order=array('invoice.date_added' => 'DESC','invoice.id'=>'DESC');
		//$this->load->model('sale/invoice');

		$results = $this->model_sale_invoice->getPenjualans($column,$join,$data,$order,$limit,$offset);
		// baru 12 mei 2020
		$resultsall = $this->model_sale_invoice->getPenjualans($column,$join,$data,$order,0,null);
		$totaldpp=0;
		$totalppn=0;
		foreach($resultsall as $r){
			$totaldpp+=$r['sub_total'];
			$totalppn+=$r['pajak'];
		}
		$this->data['totaldpp']= $this->currency->format($totaldpp);
		$this->data['totalppn']= $this->currency->format($totalppn);
		//end baru
		$pt = $this->model_sale_invoice->getPenjualans($column,$join,$data,$order,0,null);
		$product_total=count($pt);
		//print_r($results);
		foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('sale/ppnmasukan/updatefakturpajak', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);


			$namagudang=$this->model_catalog_gudang->getGudang($result['gudang_id']);

			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'no_faktur'        => $result['no_faktur'],
				'name'	=> $result['name'],
				'no_fakturpajak'	=> $result['no_fakturpajak'],
				'namagudang'	=> $namagudang['nama'],
				'dpp'	=> $this->currency->format($result['sub_total']),
				'ppn'	=> $this->currency->format($result['pajak']),
				'tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'      => $action
			);
		}

		if (isset($this->session->data['warning'])) {
			$this->data['warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_tanggal2'])) {
			$url .= '&filter_tanggal2=' . $this->request->get['filter_tanggal2'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}


		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/ppnmasukan', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_tanggal']	= $filter_tanggal;
		$this->data['filter_tanggal2']	= $filter_tanggal2;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'sale/ppnmasukan_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	// end baru
	
	public function updatefakturpajak()
	{
		if (isset($this->request->get['id'])) {
			$id = $this->request->get['id'];
		}

		if (isset($this->session->data['warning'])) {
			$this->data['warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_tanggal2'])) {
			$url .= '&filter_tanggal2=' . $this->request->get['filter_tanggal2'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->load->model('sale/invoice');
		$results = $this->model_sale_invoice->getnofakturpajak($id);
		// echo "<pre>";
		// print_r($fp);exit();
		foreach($results as $result)
		{
			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'no_faktur'        => $result['no_faktur'],
				'no_fakturpajak'	=> $result['no_fakturpajak']
				// 'action'      => $action
			);
		}
        
        $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
        
		$this->data['cancel']= $this->url->link('sale/ppnmasukan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('sale/ppnmasukan/updatefakturpajaksave', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->template = 'sale/editppnmasukan_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	public function updatefakturpajaksave()
	{
		// echo "<pre>";
		// print_r($this->request->post['no_fakturpajak']);exit();
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
			}
			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}
			if (isset($this->request->get['filter_tanggal2'])) {
				$url .= '&filter_tanggal2=' . $this->request->get['filter_tanggal2'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
	
			if (isset($this->request->get['filter_jenisorder'])) {
				$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
			}
	
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->db->query("UPDATE invoice set no_fakturpajak='".$this->request->post['no_fakturpajak']."' WHERE id=".$this->request->post['id']." ");

			$this->session->data['success'] = 'Nomor Faktur Pajak berhasil diperbarui.';

			$this->redirect($this->url->link('sale/ppnmasukan', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('sale/invoice');

			if (isset($this->request->get['q'])) {
				$filter_order_id = $this->request->get['q'];
			} else {
				$filter_order_id = '';
			}

			if (isset($this->request->get['p'])) {
				$p = $this->request->get['p'];
			} else {
				$p = null;
			}

			if (isset($this->request->get['customer_id'])) {
				$customer_id = $this->request->get['customer_id'];
			} else {
				$customer_id = null;
			}
			/*if (isset($this->request->get['p'])) {
				$p = $this->request->get['p'];
			} else {
				$p = '';
			}*/


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_faktur'         => array('LIKE',$filter_order_id),
				'metode_pembayaran'	=> $p != null?($p == 4?array('<>',3):$p):array('>=',1),
				'customer_id'	=> $customer_id != null ?$customer_id:array('>=',1),
				'status'	=> array('<>',4)
			);
			$offset=0;
			$limit=10;

			$results = $this->model_sale_invoice->getPenjualans(array(),array(),$data,array(),10,0);
			foreach($results as $r){
				/*if($r['jenisinvoice'] == 2){
					$total=$this->currency->format($r['totaltagihan']);
				}*/
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_faktur'].' Total Tagihan '.$this->currency->format($r['totaltagihan'] - $r['totalbayar'])
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function fakturpajak() {
		$this->document->setTitle('Input Faktur Pajak');
		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_tanggal2'])) {
			$url .= '&filter_tanggal2=' . $this->request->get['filter_tanggal2'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('sale/invoice');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			//print_r($this->request->post);
			$this->model_sale_invoice->addFakturpajak($this->request->post);

			$this->session->data['success'] = 'Faktur Pajak berhasil ditambahkan.';

			$this->redirect($this->url->link('sale/ppnmasukan', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}


		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}


		$this->data['cancel']= $this->url->link('sale/ppnmasukan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('sale/ppnmasukan/fakturpajak', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}


		$this->template = 'sale/ppnmasukan_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}
}
?>
