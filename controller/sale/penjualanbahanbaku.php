<?php
class ControllerSalePenjualanbahanbaku extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Daftar Penjualan');

		$this->load->model('sale/penjualanbahanbaku');

		$this->getList();
	}
	public function terima(){
		$this->load->model('sale/penjualanbahanbaku');
		$this->document->setTitle('Pengiriman Barang');
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

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
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

				//get Penjualan
				$penj=$this->model_sale_penjualanbahanbaku->getPenjualan(array('id'=>$order_id));
				if($penj['status'] == 1){
					$this->model_sale_penjualanbahanbaku->updatePenjualan(array('status' => 2),array('id'	=> $order_id));
					$this->session->data['success'] = 'Sukses: Data Pengiriman Barang berhasil diterima';
				}else{
					if($penj['status'] == 2){
						$this->session->data['warning'] = 'Peringatan: Data Pengiriman Barang telah diterima';
					}else{
						$this->session->data['warning'] = 'Peringatan: Data Pengiriman Barang telah dibatalkan';
					}
				}
				$this->redirect($this->url->link('sale/penjualanbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				$this->redirect($this->url->link('sale/penjualanbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/penjualanbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	}

	public function batalkan(){
		$this->load->model('sale/penjualanbahanbaku');
		$this->document->setTitle('Pengiriman Barang');
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

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
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
				$penj=$this->model_sale_penjualanbahanbaku->getPenjualan(array('id'=>$order_id));
				if($penj['status'] == 1){
					$this->model_sale_penjualanbahanbaku->cancelPenjualan($order_id);
					$this->session->data['success'] = 'Sukses: Data Pengiriman Barang berhasil dibatalkan';
				}else{
					if($penj['status'] == 2){
						$this->session->data['warning'] = 'Peringatan: Data Pengiriman Barang telah diterima';
					}else{
						$this->session->data['warning'] = 'Peringatan: Data Pengiriman Barang telah dibatalkan';
					}
				}
				$this->redirect($this->url->link('sale/penjualanbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				$this->redirect($this->url->link('sale/penjualanbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/penjualanbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	}


	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Daftar Penjualan');

		$this->load->model('sale/penjualanbahanbaku');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_penjualanbahanbaku->updateOrderPenjualan($this->request->post, array('id'=>$this->request->get['id']));

			$this->session->data['success'] = 'Daftar Penjualan berhasil diperbarui';

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

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/penjualanbahanbaku', 'token=' . $this->session->data['token'].$url, 'SSL'));
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
		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
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

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/penjualanbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

   	$this->data['insert'] = $this->url->link('sale/penjualanbahanbaku/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/penjualanbahanbaku/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['penjualans'] = array();

		//$column=array('aset.penjualan_pameran_id','aset.name as name','aset.tglpembelian','aset.hargabeli','aset.status','kelompok_aset.name as kelompok','kelompok_aset.jenis_aset as jenis');
		$column=array('penjualan_bahanbaku.*','sales_order_bahanbaku.no_so as nom_so','customer.name','customer.alamat','customer.telephone','customer.email');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'penjualan_bahanbaku.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$join[]=array(
			'tablename'	=> 'sales_order_bahanbaku',
			'firsttable'	=> 'penjualan_bahanbaku.no_so',
			'secondtable'	=> 'sales_order_bahanbaku.id',
		);
		$order=array(
			'id'	=> 'DESC',

		);
		$data = array(
			'penjualan_bahanbaku.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'penjualan_bahanbaku.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			'penjualan_bahanbaku.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):$filter_tanggal,
			'penjualan_bahanbaku.total'	=> empty($filter_total)?array('>=',0):$filter_total,
			'penjualan_bahanbaku.status'	=> empty($filter_status)?array('>',0):$filter_status,
			'penjualan_bahanbaku.no_invoice'	=> $filter_shipping_method != null ?array('>',0):$filter_shipping_method,
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$results = $this->model_sale_penjualanbahanbaku->getPenjualans($column,$join,$data,$order,$limit,$offset);
		$product_total = $this->model_sale_penjualanbahanbaku->totalPenjualans($data);
		//print_r($results);
		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('sale/penjualanbahanbaku/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['id'], 'SSL')
			);


			if($result['status'] == 1){
				$action[] = array(
					'text' => 'Diterima',
					'href' => $this->url->link('sale/penjualanbahanbaku/terima', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['id'], 'SSL')
				);
			}

			if($result['status'] == 1){
				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('sale/penjualanbahanbaku/batalkan', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['id'], 'SSL')
				);
			}


			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'customer_id'        => $result['customer_id'],
				'no_sj'        => $result['no_sj'],
				'no_so'        => $result['no_so'],
				'nom_so'	=> $result['nom_so'],
				'no_invoice'        => empty($result['no_invoice'])?'Belum Ada Invoice':$result['no_invoice'],
				'total'        => $this->currency->format($result['total']),
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

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/penjualanbahanbaku', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_tanggal']	= $filter_tanggal;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'sale/penjualanbahanbaku_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('sale/penjualanbahanbaku');

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
				'no_invoice'         => array('LIKE',$filter_order_id),

			);
			$offset=0;
			$limit=10;

			$results = $this->model_sale_penjualanbahanbaku->getPenjualans(array(),array(),$data,array(),10,0);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_sj']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}
	public function autocompletedetail(){
		$rests = array();

		$this->load->model('sale/penjualanbahanbaku');

			if (isset($this->request->get['q'])) {
				$filter_order_id = $this->request->get['q'];
			} else {
				$filter_order_id = '';
			}

			if (isset($this->request->get['p'])) {
				$status_pengiriman = $this->request->get['p'];
			} else {
				$status_pengiriman = null;
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}
			$column=array('penjualan_bahanbaku.no_sj','penjualan_bahanbaku.id','penjualan_bahanbaku.total','customer.name','penjualan_bahanbaku.date_added');
			$join=array();
			$join[]=array(
				'tablename'	=> 'customer',
				'firsttable'	=> 'penjualan_bahanbaku.customer_id',
				'secondtable'	=> 'customer.customer_id'
			);

			$data = array(
				'no_sj'         => array('LIKE',$filter_order_id),
				'penjualan_bahanbaku.status'         => $status_pengiriman != null?$status_pengiriman:array('>=',1),


			);
			$offset=0;
			$limit=10;

			$results = $this->model_sale_penjualanbahanbaku->getPenjualans($column,$join,$data,array(),10,0);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_sj'].' - Tanggal '.date('d/m/y',strtotime($r['date_added'])).' - '.$r['name'].' Total '.$this->currency->format($r['total'])
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function tampil(){
		$this->document->setTitle('Daftar Penjualan');
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

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
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
				$this->redirect($this->url->link('sale/penjualanbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/penjualanbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/penjualanbahanbaku');
		$this->load->model('sale/customer');
		$column=array('penjualan_bahanbaku.*','sales_order_bahanbaku.no_so as salesorder','customer.name','customer.alamat','customer.title','customer.telephone','customer.email');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'penjualan_bahanbaku.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$join[]=array(
			'tablename'	=> 'sales_order_bahanbaku',
			'firsttable'	=> 'penjualan_bahanbaku.no_so',
			'secondtable'	=> 'sales_order_bahanbaku.id',
		);
		$data = array(
			'penjualan_bahanbaku.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_penjualanbahanbaku->getPenjualanDetail($column,$join,$data,array());

		$sales=$this->model_user_user->getUser($trans['sales']);
		$trans['sales']=$sales['firstname'];
		$sopir=$this->model_user_user->getUser($trans['sopir']);
		$trans['sales']=$sales['firstname'];
		$trans['sopir']=$sopir['firstname'];
		$trans['kernets']=$this->model_sale_penjualanbahanbaku->getPenjualanKernets(array('tttk_id'	=> $order_id));
		$products=$this->model_sale_penjualanbahanbaku->getPenjualanProducts(array('sales_order_id'	=> $order_id));

		$this->load->model('catalog/title');
		$trans['titlename']=$this->model_catalog_title->getTitle($trans['title']);

		$this->data['order']=$trans;
		$this->data['products']=$products;
		$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);

		$comp=array(
			'compname' => $this->config->get('config_name'),
			'address'	=> $this->config->get('config_address'),
			'email'	=> $this->config->get('config_email'),
			'phone'	=> $this->config->get('config_telephone'),
			'fax'	=> $this->config->get('config_fax'),
			'web'	=> 'http://nissonindonesia.com'
		);

		$this->data['fulldetail']=array(
			'comp'	=> $comp,
			'order'	=> $trans,
			'products'	=> $products,
			'address'	=> $this->data['address']
		);

		$this->data['printer']=$this->config->get('config_printer');
		$this->data['printerstatus']=$this->config->get('config_printer_status');

		//print_r($this->model_sale_customer->getAddress($trans['address_id']));
		$this->data['cancel']= $this->url->link('sale/penjualanbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['suratjalan']= $this->url->link('sale/penjualanbahanbaku/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=2'. $url, 'SSL');
		$this->data['invoice']= $this->url->link('sale/penjualanbahanbaku/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=3'. $url, 'SSL');
		if($this->request->get['view'] == 1){
			$this->template = 'sale/penjualanbahanbaku_info.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

		}
		/*if($this->request->get['view'] == 2){
			$this->template = 'sale/suratjalan.tpl';
		}*/
		if($this->request->get['view'] == 3){
			$this->template = 'sale/invoice.tpl';
		}



		$this->response->setOutput($this->render());
	}

	public function ordersukses() {
		$this->document->setTitle('Penjualan Gudang & Website');
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

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('sale/penjualanbahanbaku');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			print_r($this->request->post);
			$this->model_sale_penjualanbahanbaku->ordersukses($this->request->post);

			$this->session->data['success'] = 'Order sukses Penjualan Website berhasil ditambahkan.';

			$this->redirect($this->url->link('sale/penjualanbahanbaku', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}


		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}


		$this->data['cancel']= $this->url->link('sale/penjualanbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('sale/penjualanbahanbaku/ordersukses', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		if($this->request->server['REQUEST_METHOD'] == 'POST'){
			$this->data['orders']=$this->request->post['orders'];
		}else{
			$this->data['orders']=array();
		}


		$this->template = 'sale/sukses_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}
	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Daftar Penjualan');

		$this->load->model('sale/penjualanbahanbaku');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//print_r($this->request->post);
      $order= $this->model_sale_penjualanbahanbaku->addPenjualan($this->request->post);

			$this->session->data['success'] = 'Sukses: Daftar Penjualan berhasil disimpan dengan ID '.$order;

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

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/penjualanbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
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

		$this->data['cancel']= $this->url->link('sale/penjualanbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('sale/penjualanbahanbaku/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->template = 'sale/penjualanbahanbaku_form.tpl';
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

			//$this->load->model('pembelian/permintaanpembelian');
			if(isset($this->request->get['id'])){
				if(!empty($this->request->get['id'])){

				$this->load->model('sale/penjualanbahanbaku');
				$this->load->model('sale/salesorderbahanbaku');

				$this->load->model('sale/customer');
				if($this->request->get['j'] == 3){
					$column=array('penjualan_bahanbaku.*','customer.name','customer.telephone','customer.email');
					$join=array();
					$join[]=array(
						'tablename'	=> 'customer',
						'firsttable'	=> 'penjualan_bahanbaku.customer_id',
						'secondtable'	=> 'customer.customer_id',
					);

					$data = array(
						'penjualan_bahanbaku.id'	=> $this->request->get['id'],

					);
					$this->load->model('user/user');
					$this->load->model('catalog/gudang');
					$trans=$this->model_sale_penjualanbahanbaku->getPenjualanDetail($column,$join,$data,array());
					$so=$this->db->first('salesorder_bahanbaku',array('id' => $trans['no_so']));
					$trans['usia']=$so['usia'];
					$sales=$this->model_user_user->getUser($trans['sales']);
					$products=$this->model_sale_penjualanbahanbaku->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));
					//cek dp
					$dp=$this->model_sale_invoice->getTotalDp($trans['no_so'],1);
					$trans['dp']=$dp;
				}else{
					$column=array('salesorder_bahanbaku.*','customer.name','customer.telephone','customer.email');
					$join=array();
					$join[]=array(
						'tablename'	=> 'customer',
						'firsttable'	=> 'salesorder_bahanbaku.customer_id',
						'secondtable'	=> 'customer.customer_id',
					);

					$data = array(
						'salesorder_bahanbaku.id'	=> $this->request->get['id'],

					);
					$this->load->model('user/user');
					$this->load->model('catalog/gudang');
					$trans=$this->model_sale_salesorderbahanbaku->getPenjualanDetail($column,$join,$data,array());

					//$sales=$this->model_user_user->getUser($trans['sales']);
					$products=$this->model_sale_salesorderbahanbaku->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));
					//cek dp
					$dp=$this->model_sale_invoice->getTotalDp($tthis->request->get['id'],1);
					$trans['dp']=$dp;
				}

				$hasil=array(
					'order'	=> $trans,
					'products'	=> $products,
					//'address'	=> $this->data['address']
				);
			}
		}
			$this->response->setOutput(json_encode($hasil));
		}
}
?>
