<?php
class ControllerSalePenawaran extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Penawaran Harga Jual');

		$this->load->model('sale/penawaran');

		$this->getList();
	}




	private function getList() {
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
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
		if (isset($this->request->get['filter_shipping_method'])) {
			$filter_shipping_method = $this->request->get['filter_shipping_method'];
		} else {
			$filter_shipping_method = null;
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = null;
		}
		if (isset($this->request->get['filter_jenisorder'])) {
			$filter_jenisorder = $this->request->get['filter_jenisorder'];
		} else {
			$filter_jenisorder = null;
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

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/penawaran', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

   	$this->data['insert'] = $this->url->link('sale/penawaran/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/penawaran/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['penjualans'] = array();

		//$column=array('aset.penjualan_pameran_id','aset.name as name','aset.tglpembelian','aset.hargabeli','aset.status','kelompok_aset.name as kelompok','kelompok_aset.jenis_aset as jenis');

		$data = array(
			'penawaran.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'penawaran.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			'penawaran.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):$filter_tanggal,
			'penawaran_product.product_id'	=> empty($filter_jenisorder)?array('>',0):$filter_jenisorder,

		);
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$order=array();
		$order=array('penawaran_product.sales_order_id'=>'DESC');

		$results = $this->model_sale_penawaran->getListDetail($data,$order,$limit,$offset);
		$product_total = $this->model_sale_penawaran->getTotalListDetail($data);
		//echo $product_total;
		//print_r($results);
		$this->load->model('keuangan/bank');
		//print_r($results);
		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('sale/penawaran/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'], 'SSL')
			);



			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'no_so'        => $result['no_so'],
				'sales_order_id'        => $result['sales_order_id'],
				'name'	=> $result['namacustomer'],
				'namagudang'	=> $result['namagudang'],
				'nameproduct'	=> $result['namaproduct'],
				'quantity'	=> $result['quantity'],
				'price'	=> $this->currency->format($result['price']+$result['pajak']),
				'email'	=> $result['email'],
				'telephone'	=> $result['telephone'],
				'tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'      => $action
			);
		}



 		if (isset($this->error['warning'])) {
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

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/penawaran', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_tanggal']	= $filter_tanggal;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'sale/penawaran_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('sale/penawaran');

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
				'no_so'         => array('LIKE',$filter_order_id),

			);
			$offset=0;
			$limit=10;

			$results = $this->model_sale_penawaran->getPenjualans(array(),array(),$data,array(),10,0);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_so']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function tampil(){
		$this->document->setTitle('Penawaran Harga Jual');
		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
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

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
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
				$this->redirect($this->url->link('sale/penawaran', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/penawaran', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/penawaran');
		$this->load->model('sale/customer');
		$column=array('penawaran.*','customer.name','customer.telephone','customer.email',"gudang.nama");
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'penawaran.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=> 'penawaran.gudang_id',
			'secondtable'	=> 'gudang.gudang_id',
		);

		$data = array(
			'penawaran.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_penawaran->getPenjualanDetail($column,$join,$data,array());

		$sales=$this->model_user_user->getUser($trans['sales']);
		$trans['sales']=$sales['firstname'];
		$products=$this->model_sale_penawaran->getPenjualanProducts(array('sales_order_id'	=> $order_id));

		$this->data['order']=$trans;
		$this->data['products']=$products;
		$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);

		$this->data['fulldetail']=array(
			'order'	=> $trans,
			'products'	=> $products,
			'address'	=> $this->data['address']
		);

		$this->data['printer']=$this->config->get('config_printer');
		$this->data['printerstatus']=$this->config->get('config_printer_status');

		//print_r($this->model_sale_customer->getAddress($trans['address_id']));
		$this->data['cancel']= $this->url->link('sale/penawaran', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['suratjalan']= $this->url->link('sale/penawaran/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=2'. $url, 'SSL');
		$this->data['invoice']= $this->url->link('sale/penawaran/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=3'. $url, 'SSL');
		if($this->request->get['view'] == 1){
			$this->template = 'sale/penawaran_info.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

		}
		if($this->request->get['view'] == 2){
			$this->template = 'sale/suratjalan.tpl';
		}
		if($this->request->get['view'] == 3){
			$this->template = 'sale/invoice.tpl';
		}



		$this->response->setOutput($this->render());
	}


	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Penawaran Harga Jual');

		$this->load->model('sale/penawaran');
		$this->load->model('catalog/gudang');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//print_r($this->request->post);
      $order= $this->model_sale_penawaran->addPenjualan($this->request->post);

			$this->session->data['success'] = 'Sukses: Penawaran Harga Jual berhasil disimpan dengan ID '.$order;

			$url = '';

			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
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

			if (isset($this->request->get['filter_jenisorder'])) {
				$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/penawaran', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('catalog/gudang');

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
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

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
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

		$this->data['cancel']= $this->url->link('sale/penawaran', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('sale/penawaran/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs();
        
        $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}

		$this->template = 'sale/penawaran_form.tpl';
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

		public function detail(){
			$hasil = array();

			$this->load->model('pembelian/permintaanpembelian');
			if(isset($this->request->get['id'])){
				if(!empty($this->request->get['id'])){

				$this->load->model('sale/penawaran');
				$this->load->model('sale/customer');
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
				$trans=$this->model_sale_penawaran->getPenjualanDetail($column,$join,$data,array());

				$sales=$this->model_user_user->getUser($trans['sales']);
				$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
				$trans['namasales']=$sales['firstname'];
				$trans['namagudang']=$gudang['nama'];
				$products=$this->model_sale_penawaran->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));

				//$this->data['order']=$trans;
				//$this->data['products']=$products;
				$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);

				$hasil=array(
					'order'	=> $trans,
					'products'	=> $products,
					'address'	=> $this->data['address']
				);
			}
		}
			$this->response->setOutput(json_encode($hasil));


		}
}
?>
