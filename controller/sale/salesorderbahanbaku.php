<?php
class ControllerSaleSalesorderbahanbaku extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Sales Order Bahan Baku');

		$this->load->model('sale/salesorderbahanbaku');

		$this->getList();
	}




	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Sales Order Bahan Baku');

		$this->load->model('sale/salesorderbahanbaku');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_salesorderbahanbaku->updateOrderPenjualan($this->request->post, array('id'=>$this->request->get['id']));

			$this->session->data['success'] = 'Sales Order Bahan Baku berhasil diperbarui';

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

			$this->redirect($this->url->link('sale/salesorderbahanbaku', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
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
		if (isset($this->request->get['filter_tanggalakhir'])) {
			$filter_tanggalakhir = $this->request->get['filter_tanggalakhir'];
		} else {
			$filter_tanggalakhir = null;
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

		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_tanggalakhir'])) {
			$url .= '&filter_tanggalakhir=' . $this->request->get['filter_tanggalakhir'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/salesorderbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

   	$this->data['insert'] = $this->url->link('sale/salesorderbahanbaku/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/salesorderbahanbaku/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['penjualans'] = array();

		//$column=array('aset.penjualan_pameran_id','aset.name as name','aset.tglpembelian','aset.hargabeli','aset.status','kelompok_aset.name as kelompok','kelompok_aset.jenis_aset as jenis');
		/*$column=array('sales_order_bahanbaku.*','customer.name','customer.alamat','customer.telephone','customer.email','category.name as cjenis');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'sales_order_bahanbaku.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$join[]=array(
			'tablename'	=> 'category',
			'firsttable'	=> 'sales_order_bahanbaku.jenisorder',
			'secondtable'	=> 'category.category_id',
		);
		$order=array(
			'id'	=> 'DESC',

		);*/
		$data = array(
			'sales_order_bahanbaku.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'sales_order_bahanbaku.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			//'sales_order_bahanbaku.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):$filter_tanggal,
			'sales_order_bahanbaku.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):array('>=',$filter_tanggal,'<=',$filter_tanggalakhir),
			//'sales_rder.pengiriman'	=> empty($filter_shipping_method)?array('>',0):$filter_shipping_method,
			'sales_order_product_bahanbaku.status_pengiriman'	=> empty($filter_status)?array('>=',0):$filter_status,
			'sales_order_product_bahanbaku.product_id'	=> empty($filter_jenisorder)?array('>',0):$filter_jenisorder,

		);
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$order=array();
		$order=array('sales_order_product_bahanbaku.sales_order_id'=>'DESC');

		$this->load->model('user/user');
		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),3);
		
		$results = $this->model_sale_salesorderbahanbaku->getListDetail($data,$order,$limit,$offset);
		$product_total = $this->model_sale_salesorderbahanbaku->getTotalListDetail($data);
		//echo $product_total;
		//print_r($results);
		$this->load->model('keuangan/bank');
		//print_r($results);
		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('sale/salesorderbahanbaku/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'], 'SSL')
			);

			if($result['status_pengiriman'] == 1 & $result['status'] == 1){
				if($canceldata == 1){
					$action[] = array(
						'text' => 'Batal',
						'href' => $this->url->link('sale/salesorderbahanbaku/batal', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['sales_order_id'], 'SSL')
					);
				}
			}


			/*$action[] = array(
				'text' => 'Invoice',
				'href' => $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['order_gudang_id'], 'SSL')
			);*/
			/*$action[] = array(
				'text' => 'Surat Jalan',
				'href' => $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] . '&view=2&order_id=' . $result['id'], 'SSL')
			);*/


			/*if($result['payment_status'] == 3 & $result['status_pengiriman'] == 2){
				$action[] = array(
					'text' => 'Selesai',
					'href' => $this->url->link('sale/salesorder/selesai', 'token=' . $this->session->data['token'] . '&order_gudang_id=' . $result['order_gudang_id'], 'SSL')
				);

			}*/
			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'no_so'        => $result['no_so'],
				'sales_order_id'        => $result['sales_order_id'],
				'name'	=> $result['namacustomer'],
				'nameproduct'	=> $result['namaproduct'],
				'quantity'	=> $result['quantity'],
				'quantityterima'	=> $result['quantityterima'],
				'email'	=> $result['email'],
				'telephone'	=> $result['telephone'],
				//'status'	=> $result['status'],
				'status_pengiriman'	=> $result['status_pengiriman'],
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
		if (isset($this->request->get['filter_tanggalakhir'])) {
			$url .= '&filter_tanggalakhir=' . $this->request->get['filter_tanggalakhir'];
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
		$pagination->url = $this->url->link('sale/salesorderbahanbaku', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_tanggal']	= $filter_tanggal;
		$this->data['filter_tanggalakhir']	= $filter_tanggalakhir;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'sale/bahanbakuorder_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('sale/salesorderbahanbaku');

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

			$results = $this->model_sale_salesorderbahanbaku->getPenjualans(array(),array(),$data,array(),10,0);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_so']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function tampil(){
		$this->document->setTitle('Sales Order Bahan Baku');
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
				$this->redirect($this->url->link('sale/salesorderbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/salesorderbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/salesorderbahanbaku');
		$this->load->model('sale/customer');
		$column=array('sales_order_bahanbaku.*','customer.name','customer.telephone','customer.email');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'sales_order_bahanbaku.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$data = array(
			'sales_order_bahanbaku.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_salesorderbahanbaku->getPenjualanDetail($column,$join,$data,array());

		$sales=$this->model_user_user->getUser($trans['sales']);
		$trans['sales']=$sales['firstname'];
		$products=$this->model_sale_salesorderbahanbaku->getPenjualanProducts(array('sales_order_id'	=> $order_id));

		$this->data['order']=$trans;
		$this->data['products']=$products;
		$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);

		$this->data['fulldetail']=array(
			'order'	=> $trans,
			'products'	=> $products,
			'address'	=> $this->data['address']
		);

			//print_r($trans);

		$this->data['printer']=$this->config->get('config_printer');
		$this->data['printerstatus']=$this->config->get('config_printer_status');

		//print_r($this->model_sale_customer->getAddress($trans['address_id']));
		$this->data['cancel']= $this->url->link('sale/salesorderbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['suratjalan']= $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=2'. $url, 'SSL');
		$this->data['invoice']= $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=3'. $url, 'SSL');
		if($this->request->get['view'] == 1){
			$this->template = 'sale/orderbahanbaku_info.tpl';
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

	public function tampilcetak(){
		$this->document->setTitle('Sales Order Bahan Baku');
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
				$this->redirect($this->url->link('sale/salesorderbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/salesorderbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/salesorderbahanbaku');
		$this->load->model('sale/customer');
		$column=array('sales_order_bahanbaku.*','customer.name','customer.telephone','customer.email');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'sales_order_bahanbaku.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$data = array(
			'sales_order_bahanbaku.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_salesorderbahanbaku->getPenjualanDetail($column,$join,$data,array());

		$sales=$this->model_user_user->getUser($trans['sales']);
		$trans['pencetak']=$this->user->getName();
		$trans['sales']=$sales['firstname'];
		$products=$this->model_sale_salesorderbahanbaku->getPenjualanProducts(array('sales_order_id'	=> $order_id));

		$this->data['order']=$trans;
		$this->data['products']=$products;
		$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);

		$this->data['fulldetail']=array(
			'order'	=> $trans,
			'products'	=> $products,
			'address'	=> $this->data['address']
		);

			//print_r($trans);

		$this->logcetak($order_id);
			$this->template = 'sale/order_info_cetakbahanbaku.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

		$this->response->setOutput($this->render());
	}

	public function logcetak($id){
		$this->db->update('sales_order_bahanbaku',array('user_cetak'=>$this->user->getId(),'cetak'=>1),array('id'=>$id));
	}
	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Sales Order Bahan Baku');

		$this->load->model('sale/salesorderbahanbaku');
		$this->load->model('catalog/gudang');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//print_r($this->request->post);
      $order= $this->model_sale_salesorderbahanbaku->addPenjualan($this->request->post);

			$this->session->data['success'] = 'Sukses: Sales Order Bahan Baku berhasil disimpan dengan ID '.$order;

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

			$this->redirect($this->url->link('sale/salesorderbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		$this->data['cancel']= $this->url->link('sale/salesorderbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('sale/salesorderbahanbaku/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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


		$this->template = 'sale/bahanbakuorder_form.tpl';
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

				$this->load->model('sale/salesorderbahanbaku');
				$this->load->model('sale/customer');
				$column=array('sales_order_bahanbaku.*','customer.name','customer.telephone','customer.email');
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'sales_order_bahanbaku.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);

				$data = array(
					'sales_order_bahanbaku.id'	=> $this->request->get['id'],

				);
				$this->load->model('user/user');
				$this->load->model('catalog/gudang');
				$trans=$this->model_sale_salesorderbahanbaku->getPenjualanDetail($column,$join,$data,array());

				$sales=$this->model_user_user->getUser($trans['sales']);
				$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
				$trans['namasales']=$sales['firstname'];
				$trans['namagudang']=$gudang['nama'];
				$products=$this->model_sale_salesorderbahanbaku->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));

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
		public function batal() {
			$this->load->language('catalog/pembelian');
	
			$this->document->setTitle('Sales Order');
	
			$this->load->model('sale/salesorderbahanbaku');
	
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
	
			if (isset($this->request->get['filter_jenisorder'])) {
				$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
			}
				
			$this->load->model('user/user');
			/*$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);
	
			if($custdata != 1){
				$data['customer.sales']=$this->user->getId();
			}*/
	
			$canceldata=$this->model_user_user->getAksesData($this->user->getId(),3);
				if($canceldata==1){
				if (isset($this->request->get['order_id'])) {
					$order_id=$this->request->get['order_id'];
					$url .= '&order_id=' . $this->request->get['order_id'];
				}else{
					$this->session->data['warning'] = 'Error: Sales Order tidak ditemukan';
					$this->redirect($this->url->link('sale/salesorderbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}
	
				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				if ($this->request->server['REQUEST_METHOD'] == 'POST') {
					if ($this->request->get['order_id']) {
						if(!empty($this->request->get['order_id'])){
						//print_r($this->request->post);
							$p=$this->model_sale_salesorderbahanbaku->getPenjualan(array('id'=>$this->request->get['order_id']));
							if($p['status'] == 1){
						  	$this->model_sale_salesorderbahanbaku->cancelPenjualan($this->request->get['order_id'],$_POST['alasan_batal']);
	
								$this->session->data['success'] = 'Sukses: Sales Order berhasil dibatalkan';
							}else{
								$this->session->data['warning'] = 'Error: Sales Order tidak diijinkan untuk dibatalkan';
							}
						}
					}
	
					$this->redirect($this->url->link('sale/salesorderbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}
				$this->data['token'] = $this->session->data['token'];
	
				$this->data['cancel']= $this->url->link('sale/salesorderbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');
				$this->data['action']= $this->url->link('sale/salesorderbahanbaku/batal', 'token=' . $this->session->data['token'].'&order_id='.$order_id . $url, 'SSL');
				$this->template = 'sale/tttkmr_batal.tpl';
				$this->children = array(
					'common/header',
					'common/footer'
				);
	
				$this->response->setOutput($this->render());
			}else{
				$this->session->data['warning'] = 'Anda tidak diijinkan untuk membatalkan Sales Order';
				$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'].$url, 'SSL'));
			}
		}
}
?>
