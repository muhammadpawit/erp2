<?php
class ControllerSaleReturnpenjualan extends Controller {
	private $error = array();
	
	
	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Return Penjualan');

		$this->load->model('sale/returnpenjualan');

		$this->getList();
	}
	


	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Daftar Penjualan');

		$this->load->model('sale/penjualan');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_penjualan->updateOrderPenjualan($this->request->post, array('id'=>$this->request->get['id']));

			$this->session->data['success'] = 'Daftar Penjualan berhasil diperbarui';

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
			if (isset($this->request->get['filter_invoice'])) {
				$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
			}
			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_sales_order'])) {
				$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	
	
	private function getList() {
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_invoice'])) {
			$filter_invoice = $this->request->get['filter_invoice'];
		} else {
			$filter_invoice = null;
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

		if (isset($this->request->get['filter_sales_order'])) {
			$filter_sales_order = $this->request->get['filter_sales_order'];
		} else {
			$filter_sales_order = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$filter_shipping_method = $this->request->get['filter_shipping_method'];
		} else {
			$filter_shipping_method = null;
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$filter_tanggal_awal = $this->request->get['filter_tanggal_awal'];
		} else {
			$filter_tanggal_awal = null;
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$filter_tanggal_akhir = $this->request->get['filter_tanggal_akhir'];
		} else {
			$filter_tanggal_akhir = null;
		}
		if (isset($this->request->get['filter_sales_order'])) {
			$filter_sales_order = $this->request->get['filter_sales_order'];
		} else {
			$filter_sales_order = null;
		}

		if (isset($this->request->get['filter_statustabung'])) {
			$filter_statustabung= $this->request->get['filter_statustabung'];
		} else {
			$filter_statustabung = null;
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
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/returnpenjualan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/returnpenjualan/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/returnpenjualan/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['penjualans'] = array();

	

		$column=array('return_penjualan.*','gudang.nama','customer.name','customer.alamat','customer.telephone','customer.email');
		$join[]=array(
				'tablename'	=> 'gudang',
				'firsttable'	=> 'return_penjualan.gudang_id',
				'secondtable'	=> 'gudang.gudang_id',
			);
		

        $leftjoin=array();
       
        $leftjoin[]=array(
            'tablename'	=> 'customer',
            'firsttable'	=> 'return_penjualan.customer_id',
            'secondtable'	=> 'customer.customer_id',
        );

		
		

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
		//print_r($gudangs);

		$arrsql=implode(',',$gudangs);
		$data=array();
		if(isset($filter_tanggal_awal) && !isset($filter_tanggal_akhir)){
			$data+= array(
				'return_penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):$filter_tanggal_awal,
			);
		}
		else if(!isset($filter_tanggal_awal) && isset($filter_tanggal_akhir)){
			$data+= array(
				'return_penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):$filter_tanggal_akhir,
			);
		}
		else if(isset($filter_tanggal_awal) && isset($filter_tanggal_akhir)){
			$data+= array(
				'return_penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):array('>=',$filter_tanggal_awal),
				'return_penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):array('<=',$filter_tanggal_akhir),
			);
		}

		$data += array(
			'return_penjualan.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'return_penjualan.gudang_id'	=>array('IN',$arrsql),
			'return_penjualan.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			'return_penjualan.status'	=> empty($filter_status)?array('>=',0):$filter_status,
			
		);
		
		$order=array(
			'return_penjualan.id'	=> 'DESC',

		);
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$results = $this->model_sale_returnpenjualan->getPenjualans($column,$join,$leftjoin,$data,$order,$limit,$offset);
		$product_total = $this->model_sale_returnpenjualan->totalPenjualans($data,$join,$leftjoin);

		$this->load->model('sale/invoice');
		$this->load->model('catalog/gudang');

		$this->load->model('user/user');
		/*$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$data['customer.sales']=$this->user->getId();
		}*/

		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),4);
		
		// baru 11 November 2019
		$editdata=$this->model_user_user->getAksesData($this->user->getId(),10);

		//print_r($results);
		foreach ($results as $result) {
			//if(in_array($result['gudang_id'],$gudangs)){
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('sale/returnpenjualan/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['id'], 'SSL')
			);
			
		/*	if($editdata==1){
				$action[] = array(
					'text' => 'Edit',
					'href' => $this->url->link('sale/penjualan/tampil', 'token=' . $this->session->data['token'] . '&edit=1&view=10&order_id=' . $result['sales_order_id'], 'SSL')
				);
			}
			
			if($this->user->getUsername()=="pawits"){
				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('sale/penjualan/batalkan', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['sales_order_id'], 'SSL')
				);
			}
		*/
			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'customer_id'        => $result['customer_id'],
				'nama'        => $result['nama'],
				'product'        => json_decode($result['product'],true),
				'total'        => $this->currency->format($result['total']),
                'totalrefund'        => $this->currency->format($result['totalrefund']),
				'name'	=> $result['name'],
				'email'	=> $result['email'],
				'telephone'	=> $result['telephone'],
				'status'	=> $result['status'],
				'tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'      => $action
			);

		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['warning'])) {
			$this->data['error_warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
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
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/returnpenjualan', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_tanggal_awal']	= $filter_tanggal_awal;
		$this->data['filter_tanggal_akhir']	= $filter_tanggal_akhir;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'sale/returnpenjualan_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('sale/penjualan');

			if (isset($this->request->get['q'])) {
				$filter_order_id = $this->request->get['q'];
			} else {
				$filter_order_id = '';
			}

			if (isset($this->request->get['p'])) {
				$p = $this->request->get['p'];
			} else {
				$p = '';
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_sj'         => array('LIKE',$filter_order_id),

			);
			$offset=0;
			$limit=10;

			$results = $this->model_sale_penjualan->getPenjualans(array(),array(),array(),$data,array(),10,0);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_sj']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}
	

	public function tampil(){
		$this->document->setTitle('Return Penjualan');
		$this->data['token'] = $this->session->data['token'];
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
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('sale/returnpenjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			if(isset($this->request->get['id'])){
				if(!empty($this->request->get['id'])){
					$order_id=$this->request->get['id'];
				}else{
					$this->redirect($this->url->link('sale/returnpenjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}
			}else{
				$this->redirect($this->url->link('sale/returnpenjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}

		$this->load->model('sale/returnpenjualan');
		$this->load->model('sale/customer');

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),6);

		$column=array('return_penjualan.*','customer.name','customer.alamat','customer.telephone','customer.title','customer.email','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'return_penjualan.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=> 'return_penjualan.gudang_id',
			'secondtable'	=> 'gudang.gudang_id',
		);

		
		$data = array(
			'return_penjualan.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_returnpenjualan->getPenjualanDetail($column,$join,$data,array());
		
		$salesorder="";
		$cekso=array();
		$i=1;

		$this->load->model('user/user');
		

		$this->data['order']=$trans;
		$this->data['products']=json_decode($trans['product'],true);
		
		
		$this->load->model('catalog/title');
		$trans['titlename']=$this->model_catalog_title->getTitle($trans['title']);

		
		
		//print_r($this->model_sale_customer->getAddress($trans['address_id']));
		$this->data['cancel']= $this->url->link('sale/returnpenjualan', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->template = 'sale/returnpenjualan_info.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

		


		$this->response->setOutput($this->render());
	}
	
	
	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Return Penjualan');

		$this->load->model('sale/returnpenjualan');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//print_r($this->request->post);
			$order= $this->model_sale_returnpenjualan->addPenjualan($this->request->post);

			$this->session->data['success'] = 'Sukses: Daftar return Penjualan berhasil disimpan dengan ID '.$order;

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
			if (isset($this->request->get['filter_shipping_method'])) {
				$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
			}
			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['filter_invoice'])) {
				$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
			}

			if (isset($this->request->get['filter_sales_order'])) {
				$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/returnpenjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('catalog/gudang');

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
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
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

		/*$this->load->model('sale/customer_group');

		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		*/
		$this->data['token'] = $this->session->data['token'];

		$this->data['cancel']= $this->url->link('sale/returnpenjualan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('sale/returnpenjualan/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->request->post['product'])) {
			$this->data['product'] = $this->request->post['product'];
		} else {
			$this->data['product'] = array();
		}

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('catalog/gudang');

		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs(true);
        
        $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}

		//bank

		$this->template = 'sale/returnpenjualan_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {
    	/*if (!$this->user->hasPermission('modify', 'gudang/pembelian')) {
      		$this->error['warning'] = 'Permission Denied.';
    	}

    	/*if (empty($this->request->post['date_added'])) {
      		$this->error['warning'] = 'Tanggal input product cacat harus diisi';
    	}*/

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function logcetak(){
		$hasil=array();
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$this->load->model('sale/penjualan');
				$id=$this->request->get['id'];
				$trans=$this->model_sale_penjualan->getPenjualanDetail(array('COALESCE(cetak,0) as totalcetak'),array(),array('id'=>$id),array());
				$totalcetak=$trans['totalcetak'];
				$this->model_sale_penjualan->updatePenjualan(array('cetak'=>$totalcetak+1),array('id'=>$id));
				$hasil['status']=1;
			}
		}
		$this->response->setOutput(json_encode($hasil));
	}

	public function cetakulang(){
		$hasil=array();
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$this->load->model('sale/penjualan');
				$id=$this->request->get['id'];
				$trans=$this->model_sale_penjualan->getPenjualanDetail(array('COALESCE(cetak,0) as totalcetak'),array(),array('id'=>$id),array());
				$totalcetak=$trans['totalcetak'];
				if($totalcetak == 1){
					$this->model_sale_penjualan->updatePenjualan(array('cetakulang'=>2,'alasan_cetak'=> $this->request->get['alasan'],'user_cetak'=>$this->user->getId()),array('id'=>$id));
					$hasil['status']=1;
				}else{

						$hasil['status']=0;
				}
			}
		}
		$this->response->setOutput(json_encode($hasil));
	}
	public function setujui(){
		$hasil=array();
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$this->load->model('sale/penjualan');
				$id=$this->request->get['id'];
				$this->load->model('user/user');
				$custdata=$this->model_user_user->getAksesData($this->user->getId(),6);

				if($custdata){
					$this->model_sale_penjualan->updatePenjualan(array('cetakulang'=>$this->request->get['status'],'user_setuju'=>$this->user->getId()),array('id'=>$id));
					$hasil['status']=1;
				}else{
					$hasil['status']=0;
				}

			}
		}
		$this->response->setOutput(json_encode($hasil));
	}

	public function detail(){
		$hasil = array();

		//$this->load->model('pembelian/permintaanpembelian');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){

			$this->load->model('sale/penjualan');
			$this->load->model('sale/salesorder');
			$this->load->model('sale/invoice');
			$this->load->model('sale/customer');

			if($this->request->get['j'] == 3){
				$column=array('penjualan.*','customer.name','customer.telephone','customer.email');
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'penjualan.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);

				$data = array(
					'penjualan.id'	=> $this->request->get['id'],

				);
				$this->load->model('user/user');
				$this->load->model('catalog/gudang');
				$trans=$this->model_sale_penjualan->getPenjualanDetail($column,$join,$data,array());
				//$so=$this->db->first('sales_order',array('id' => $trans['no_so']));
				$trans['usia']=1;

				//$sales=$this->model_user_user->getUser($trans['sales']);
				$products=$this->model_sale_penjualan->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));
				//$dp=$this->model_sale_invoice->getTotalDp($trans['no_so'],1);
				$dp=0;
			}else{
				$column=array('sales_order.*','customer.name','customer.telephone','customer.email');
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'sales_order.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);

				$data = array(
					'sales_order.id'	=> $this->request->get['id'],

				);
				$this->load->model('user/user');
				$this->load->model('catalog/gudang');
				$trans=$this->model_sale_salesorder->getPenjualanDetail($column,$join,$data,array());

				//$sales=$this->model_user_user->getUser($trans['sales']);
				$products=$this->model_sale_salesorder->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));
				$dp=$this->model_sale_invoice->getTotalDp($this->request->get['id'],1);
			}
			//cek dp

			$trans['dp']=$dp;

			$hasil=array(
				'order'	=> $trans,
				'products'	=> $products,
				//'address'	=> $this->data['address']
			);
		}
	}
		$this->response->setOutput(json_encode($hasil));
	}

	public function detailTanpaInvoice(){
		$hasil = array();

		//$this->load->model('pembelian/permintaanpembelian');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){

			$this->load->model('sale/penjualan');
			//$products=$this->model_sale_penjualan->
			/*$this->load->model('sale/salesorder');
			$this->load->model('sale/invoice');
			$this->load->model('sale/customer');*/

			//if($this->request->get['j'] == 3){
				/*$column=array('penjualan.*','customer.name','customer.telephone','customer.email');
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'penjualan.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);

				$data = array(
					'penjualan.id'	=> $this->request->get['id'],

				);
				$this->load->model('user/user');
				$this->load->model('catalog/gudang');
				$trans=$this->model_sale_penjualan->getPenjualanDetail($column,$join,$data,array());
				$so=$this->db->first('sales_order',array('id' => $trans['no_so']));
				$trans['usia']=$so['usia'];

				//$sales=$this->model_user_user->getUser($trans['sales']);
				$products=$this->model_sale_penjualan->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));
				$dp=$this->model_sale_invoice->getTotalDp($trans['no_so'],1);*/
		/*	}else{
				$column=array('sales_order.*','customer.name','customer.telephone','customer.email');
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'sales_order.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);

				$data = array(
					'sales_order.id'	=> $this->request->get['id'],

				);
				$this->load->model('user/user');
				$this->load->model('catalog/gudang');
				$trans=$this->model_sale_salesorder->getPenjualanDetail($column,$join,$data,array());

				//$sales=$this->model_user_user->getUser($trans['sales']);
				$products=$this->model_sale_salesorder->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));
				$dp=$this->model_sale_invoice->getTotalDp($this->request->get['id'],1);
			}*/
			//cek dp

			//$trans['dp']=$dp;
			if(!empty($this->request->get['gudang_id'])){
				$products=$this->model_sale_penjualan->getSjTanpaInv($this->request->get['id'],$this->request->get['gudang_id']);

				$hasil=array(
					'order'	=> array(),
					'products'	=> $products,
					//'address'	=> $this->data['address']
				);
			}
		}
	}
		$this->response->setOutput(json_encode($hasil));
	}
	public function sosudahdikirim (){
		$rests = array();

		$this->load->model('sale/salesorder');

			if (isset($this->request->get['q'])) {
				$no_so = $this->request->get['q'];
			} else {
				$no_so = '';
			}

			if (isset($this->request->get['customer_id'])) {
				$customer_id = $this->request->get['customer_id'];
			} else {
				$customer_id = 0;
			}

			if (isset($this->request->get['gudang_id'])) {
				$gudang_id = $this->request->get['gudang_id'];
			} else {
				$gudang_id = 0;
			}

			
			if($customer_id > 0 & $gudang_id > 0){

				/*$data = array(
					'no_so'         => array('LIKE',$filter_order_id),
					'customer_id'	=> $customer_id,
					'gudang_id'	=> $gudang_id
	
				);
				$offset=0;
				$limit=10;
	
				$results = $this->model_sale_salesorder->getPenjualans(array(),array(),$data,array(),10,0);
				*/
				$results=$this->model_sale_salesorder->getSoSudahdikirim($no_so,$customer_id,$gudang_id);
				foreach($results as $r){
					$rests[]=array(
						'id'	=> $r['sales_order_id'],
						'text'	=> $r['no_so']
					);
				}
			}
		$this->response->setOutput(json_encode($rests));
	}
}
?>
