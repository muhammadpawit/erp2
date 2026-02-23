<?php
class ControllerSaleDaftarcustomer extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Customer');

		$this->load->model('sale/customer');

		$this->getList();
	}



	private function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		if (isset($this->request->get['filter_alamat'])) {
			$filter_alamat = $this->request->get['filter_alamat'];
		} else {
			$filter_alamat = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['filter_sales'])) {
			$filter_sales = $this->request->get['filter_sales'];
		} else {
			$filter_sales = null;
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$filter_provinsi = $this->request->get['filter_provinsi'];
		} else {
			$filter_provinsi = null;
		}

		if (isset($this->request->get['filter_customer_group'])) {
			$filter_customer_group = $this->request->get['filter_customer_group'];
		} else {
			$filter_customer_group = null;
		}

		if (isset($this->request->get['filter_tgllahir'])) {
			$filter_tgllahir = $this->request->get['filter_tgllahir'];
		} else {
			$filter_tgllahir = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_tgllahir'])) {
			$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

   		$this->data['delete'] = $this->url->link('sale/daftarcustomer/delete', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('catalog/title');
		$this->load->model('catalog/area');

		$this->data['customers'] = array();
		$sales=$this->user->getGroupId();


		$data = array(
			'customer.name'	  => array('LIKE',$filter_name),
			'customer.alamat'	  => array('LIKE',$filter_alamat),
			'customer.sales'	=> ($filter_sales != null)?$filter_sales:array('>=',0),
			'customer.hapus'	=> array('<',1)
			//'customer.customer_group_id'	=> ($filter_customer_group != null)?$filter_customer_group:array('>=',0)
		);
		if(!empty($filter_customer_group)){
			$data['customer.customer_group_id']=array('IN',$filter_customer_group);
		}
		if(!empty($filter_provinsi)){
			$data['country']=array('IN',$filter_provinsi);
		}
		if(!empty($filter_tgllahir)){
			//$tgl=explode('-',$filter_tgllahir);
			/*$data["DATE_TRUNC('month',tgllahir)"]=$tgl[0];
			$data["DATE_TRUNC('day',tgllahir)"]=$tgl[1];*/
			$data['EXTRACT(month FROM "tgllahir")']=$filter_tgllahir;
			//$data['EXTRACT(day FROM "tgllahir")']=$tgl[1];
		}
		/*if($sales == 25){
			$data['sales']=$this->user->getId();
		}*/
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$orders=array();
		if($sort == 'sales'){
			$orders=array('users.firstname'=>$order);
		}else{
			$orders=array($sort=>$order);
		}

		$results = $this->model_sale_customer->getVendors($data,$orders,$limit,$offset);
		$product_total = $this->model_sale_customer->totalVendors($data);

		//get status
		$this->load->model('sale/invoice');
		//$this->load->model('user/user');

		foreach ($results as $result) {


			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('sale/daftarcustomer/tampil', 'token=' . $this->session->data['token'].$url . '&id=' . $result['customer_id'], 'SSL')
			);

			$action[] = array(
				'text' => 'Kunjungan',
				'href' => $this->url->link('sale/daftarcustomer/kunjungan', 'token=' . $this->session->data['token'].$url . '&customer_id=' . $result['customer_id'], 'SSL')
			);



			$action[] = array(
				'text' => 'Alamat Pengiriman',
				'href' => $this->url->link('sale/daftarcustomer/alamat', 'token=' . $this->session->data['token'].$url . '&customer_id=' . $result['customer_id'], 'SSL')
			);


				$piutang=$this->model_sale_invoice->getPiutang($result['customer_id']);

			$title=$this->model_catalog_title->getTitle($result['title']);
		//	$area=$this->model_catalog_area->getArea($result['area']);
			//$sales=$this->model_user_user->getUser($result['sales']);
			$this->load->model('catalog/provinsi');
			$this->load->model('localisation/zone');
			$this->load->model('localisation/city');
			$pro=$this->model_catalog_provinsi->getProvinsi($result['country']);
			$kot=$this->model_localisation_zone->getZone($result['zone']);
			$kota=array();
			$city=array();
			$cit=$this->model_localisation_city->getCity($result['city']);
			$this->data['customers'][] = array(
				'customer_id' => $result['customer_id'],
				'customer_group'	=> $result['customer_group'],
				'name'        => $title.' '.$result['name'],
				'alamat'        => $result['alamat'],
				'kodepos'        => $result['kodepos'],
				'provinsi'=>$pro['name']==null?null:$pro['name'],
				'kota'=>$kot['name'],
				'kecamatan'=>$cit['name'],
				'telephone'        => $this->model_sale_customer->formattelp($result['telephone']),
				'date_added'        => date('d/m/y',strtotime($result['date_added'])),
				'tgllahir'        => date('d/m/y',strtotime($result['tgllahir'])),
				'npwp'	=> $result['npwp'],
				//'area'	=> $area,
				'email'	=> $result['email'],
				'sales'	=> $result['firstname'],
				'deposit'	=> $this->currency->format($result['deposit']),
				'piutang'	=> $this->currency->format($piutang),
				'limit_piutang'	=> $this->currency->format($result['limit_piutang']),
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'namapicdepantengah' => $result['namapicdepantengah'],
				'namapicbelakang' => $result['namapicbelakang'],
				'action'      => $action
			);

		}

		//echo $product_total;

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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_alamat'])) {
			$url .= '&filter_alamat=' . urlencode(html_entity_decode($this->request->get['filter_alamat'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];

		}
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_tgllahir'])) {
			$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$this->data['sort_date_added'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');
		$this->data['sort_name'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$this->data['sort_tgllahir'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=tgllahir' . $url, 'SSL');
		$this->data['sort_deposit'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=deposit' . $url, 'SSL');
		$this->data['sort_sales'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&sort=sales' . $url, 'SSL');
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_alamat'])) {
			$url .= '&filter_alamat=' . urlencode(html_entity_decode($this->request->get['filter_alamat'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_tgllahir'])) {
			$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/daftarcustomer', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['token'] = $this->session->data['token'];
		$this->data['filter_provinsi'] = $filter_provinsi;
		$this->data['filter_tgllahir'] = $filter_tgllahir;
		$this->data['filter_alamat'] = $filter_alamat;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->data['areas']=$this->model_catalog_area->getOptions();

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('sale/customer_group');
		$this->data['customer_groups']=$this->model_sale_customer_group->getCustomerGroups();

		$this->template = 'sale/customer_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function tampil(){
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_tgllahir'])) {
			$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

 		if (isset($this->error['warning'])) {
			$this->data['error'] = $this->error;
		} else {
			$this->data['error'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}

		$this->load->model('sale/customer');

		$this->data['cancel'] = $this->url->link('sale/daftarcustomer', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['customer'] = $this->model_sale_customer->getVendor(array('customer_id'	=> $this->request->get['id']));

		$this->data['token'] = $this->session->data['token'];

		$this->template = 'sale/customer_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}



	private function validateDelete() {
		/*if (!$this->user->hasPermission('modify', 'sale/customer')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Customer';
		}*/

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function autocomplete() {
		$json = array();

		$this->load->model('sale/customer');

			if (isset($this->request->get['q'])) {
				$filter_name = $this->request->get['q'];
			} else {
				$filter_name = null;
			}

			if (isset($this->request->get['s'])) {
				$sales = $this->request->get['s'];
			} else {
				$sales = 0;
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}
			if($sales){
				$data = array(
				'name'	  => array('LIKE',$filter_name),
				'sales'	=> $sales
					//'start'               => 0,
					//'limit'               => $limit
				);
			}else{
				$data = array(
				'name'	  => array('LIKE',$filter_name),
				//'sales'	=> $s
					//'start'               => 0,
					//'limit'               => $limit
				);
			}
			$offset=0;
			$limit=$limit;

			$results = $this->model_sale_customer->getVendors($data,array(),$limit,$offset);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['customer_id'],
					'text' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}


		$this->response->setOutput(json_encode($json));
	}

	public function autocompleteaddress() {
		$json = array();

		$this->load->model('sale/customer');
			if(isset($this->request->get['customer_id'])){

			if (isset($this->request->get['q'])) {
				$filter_name = $this->request->get['q'];
			} else {
				$filter_name = null;
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
			'firstname'	  => array('LIKE',$data['filter_name']),
			'customer_id'	=> $this->request->get['customer_id'],
			'start'	=> 0,
			'limit'	=> $limit
				//'start'               => 0,
				//'limit'               => $limit
			);
			$offset=0;
			$limit=$limit;

			$results = $this->model_sale_customer->getAddresses2($this->request->get['customer_id'],$data);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['address_id'],
					'text' => strip_tags(html_entity_decode($result['firstname'].' '.$result['address_1'].', '.$result['city'].', '.$result['zone'].', '.$result['country'], ENT_QUOTES, 'UTF-8')),

				);
			}
		}


		$this->response->setOutput(json_encode($json));
	}

	public function alamat() {
    $this->load->language('sale/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');
    if (isset($this->request->get['customer_id'])) {
			if(empty($this->request->get['customer_id'])){
				$this->redirect($this->url->link('sale/daftarcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				$customer_id = $this->request->get['customer_id'];
			}
		} else {
			$this->redirect($this->url->link('sale/daftarcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}



		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

        if (isset($this->request->get['pagealamat'])) {
			$pagealamat = $this->request->get['pagealamat'];
		} else {
			$pagealamat = 1;
		}

		$url = '';
    if (isset($this->request->get['customer_id'])) {
			$url .= '&customer_id=' .$this->request->get['customer_id'];
		}


		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}

		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_tgllahir'])) {
			$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
        if (isset($this->request->get['pagealamat'])) {
			$url .= '&pagealamat=' . $this->request->get['pagealamat'];
		}

  	$this->data['cancel'] = $this->url->link('sale/daftarcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['insert'] = $this->url->link('sale/daftarcustomer/insertalamat', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/daftarcustomer/deletealamat', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['addresses'] = array();

		$data = array(
			'start'                    => ($pagealamat - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);

		$address_total = $this->model_sale_customer->getTotalAddress($customer_id);

		$results = $this->model_sale_customer->getAddresses($customer_id,$data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/daftarcustomer/updatealamat', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'].'&address_id='.$result['address_id'] . $url, 'SSL')
			);

			$this->data['addresses'][] = array(
				'address_id'    => $result['address_id'],
				'firstname'           => $result['firstname'],
				'lastname'           => $result['lastname'],
				'address'          => $result['address_1'].' '.$result['address_2'],

				'city'             => $result['city'],
				'zone'             => $result['zone'],
				'country'             => $result['country'],
				'selected'       => isset($this->request->post['selected']) && in_array($result['address_id'], $this->request->post['selected']),

			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_customer_group'] = $this->language->get('column_customer_group');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_approved'] = $this->language->get('column_approved');
		$this->data['column_ip'] = $this->language->get('column_ip');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_login'] = $this->language->get('column_login');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_approve'] = $this->language->get('button_approve');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

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
        if (isset($this->request->get['customer_id'])) {
			$url .= '&customer_id=' .$this->request->get['customer_id'];
		}


		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}

		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_tgllahir'])) {
			$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$pagination = new Pagination();
		$pagination->total = $address_total;
		$pagination->page = $pagealamat;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/daftarcustomer/alamat', 'token=' . $this->session->data['token'] . $url . '&pagealamat={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->template = 'sale/alamat_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  public function insertalamat() {
		$this->load->language('sale/customer');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');
		if (!isset($this->request->get['customer_id'])) {
            $this->redirect($this->url->link('sale/daftarcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormAlamat()) {
      	  	$this->model_sale_customer->addAddress($this->request->post,$this->request->get['customer_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
            if (isset($this->request->get['customer_id'])) {
				$url .= '&customer_id=' . $this->request->get['customer_id'];
			}
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_provinsi'])) {
				$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
			}

			if (isset($this->request->get['filter_customer_group'])) {
				$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
			}

			if (isset($this->request->get['filter_tgllahir'])) {
				$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

            if (isset($this->request->get['pagealamat'])) {
				$url .= '&pagealamat=' . $this->request->get['pagealamat'];
			}

			$this->redirect($this->url->link('sale/daftarcustomer/alamat', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getFormAlamat();
  	}



  	public function deletealamat() {
		$this->load->language('sale/customer');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

        if (!isset($this->request->get['customer_id'])) {
            $this->redirect($this->url->link('sale/daftarcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $address_id) {
				$this->model_sale_customer->deleteAddress($address_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
            if (isset($this->request->get['customer_id'])) {
				$url .= '&customer_id=' . $this->request->get['customer_id'];
			}
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_provinsi'])) {
				$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
			}

			if (isset($this->request->get['filter_customer_group'])) {
				$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
			}

			if (isset($this->request->get['filter_tgllahir'])) {
				$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

            if (isset($this->request->get['pagealamat'])) {
				$url .= '&pagealamat=' . $this->request->get['pagealamat'];
			}

			$this->redirect($this->url->link('sale/daftarcustomer/alamat', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->alamat();
  	}

    private function getFormAlamat() {
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
    	$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_add_blacklist'] = $this->language->get('text_add_blacklist');
		$this->data['text_remove_blacklist'] = $this->language->get('text_remove_blacklist');

		$this->data['column_ip'] = $this->language->get('column_ip');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');

    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
    	$this->data['entry_fax'] = $this->language->get('entry_fax');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
    	$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_default'] = $this->language->get('entry_default');
		$this->data['entry_amount'] = $this->language->get('entry_amount');
		$this->data['entry_points'] = $this->language->get('entry_points');
 		$this->data['entry_description'] = $this->language->get('entry_description');

		$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    	$this->data['button_add_address'] = $this->language->get('button_add_address');
		$this->data['button_add_transaction'] = $this->language->get('button_add_transaction');
		$this->data['button_add_reward'] = $this->language->get('button_add_reward');
    	$this->data['button_remove'] = $this->language->get('button_remove');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_address'] = $this->language->get('tab_address');
		$this->data['tab_transaction'] = $this->language->get('tab_transaction');
		$this->data['tab_reward'] = $this->language->get('tab_reward');
		$this->data['tab_ip'] = $this->language->get('tab_ip');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['customer_id'])) {
			$this->data['customer_id'] = $this->request->get['customer_id'];
		} else {
			$this->data['customer_id'] = 0;
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}




		if (isset($this->error['address_firstname'])) {
			$this->data['error_address_firstname'] = $this->error['address_firstname'];
		} else {
			$this->data['error_address_firstname'] = '';
		}

 		if (isset($this->error['address_lastname'])) {
			$this->data['error_address_lastname'] = $this->error['address_lastname'];
		} else {
			$this->data['error_address_lastname'] = '';
		}



		if (isset($this->error['address_address_1'])) {
			$this->data['error_address_address_1'] = $this->error['address_address_1'];
		} else {
			$this->data['error_address_address_1'] = '';
		}

		if (isset($this->error['address_city'])) {
			$this->data['error_address_city'] = $this->error['address_city'];
		} else {
			$this->data['error_address_city'] = '';
		}

		if (isset($this->error['address_postcode'])) {
			$this->data['error_address_postcode'] = $this->error['address_postcode'];
		} else {
			$this->data['error_address_postcode'] = '';
		}

		if (isset($this->error['address_country'])) {
			$this->data['error_address_country'] = $this->error['address_country'];
		} else {
			$this->data['error_address_country'] = '';
		}

		if (isset($this->error['address_zone'])) {
			$this->data['error_address_zone'] = $this->error['address_zone'];
		} else {
			$this->data['error_address_zone'] = '';
		}

		$url = '';
            if (isset($this->request->get['customer_id'])) {
				$url .= '&customer_id=' . $this->request->get['customer_id'];
			}
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_provinsi'])) {
				$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
			}

			if (isset($this->request->get['filter_customer_group'])) {
				$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
			}

			if (isset($this->request->get['filter_tgllahir'])) {
				$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

            if (isset($this->request->get['pagealamat'])) {
				$url .= '&pagealamat=' . $this->request->get['pagealamat'];
			}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/daftarcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('sale/daftarcustomer/insertalamat', 'token=' . $this->session->data['token'] . $url, 'SSL');


    	$this->data['cancel'] = $this->url->link('sale/daftarcustomer/alamat', 'token=' . $this->session->data['token'] . $url, 'SSL');


    	if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = $this->request->post['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	} else {
      		$this->data['lastname'] = '';
    	}

    	if (isset($this->request->post['address_1'])) {
      		$this->data['address_1'] = $this->request->post['address_1'];
    	} else {
      		$this->data['address_1'] = '';
    	}


		if (isset($this->request->post['address_2'])) {
      		$this->data['address_2'] = $this->request->post['address_2'];
    	} else {
      		$this->data['address_2'] = '';
    	}

        if (isset($this->request->post['city_id'])) {
      		$this->data['city_id'] = $this->request->post['city_id'];
    	} else {
      		$this->data['city_id'] = '';
    	}

        if (isset($this->request->post['postcode'])) {
      		$this->data['postcode'] = $this->request->post['postcode'];
    	} else {
      		$this->data['postcode'] = '';
    	}

        if (isset($this->request->post['country_id'])) {
      		$this->data['country_id'] = $this->request->post['country_id'];
    	} else {
      		$this->data['country_id'] = '';
    	}

        if (isset($this->request->post['zone_id'])) {
      		$this->data['zone_id'] = $this->request->post['zone_id'];
    	} else {
      		$this->data['zone_id'] = '';
    	}

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();


		$this->load->model('localisation/zone');
		$this->load->model('localisation/city');
		//foreach ($this->data['addresses'] as $i => $value) {
           if(!empty($this->data['country_id'])){
            $this->data['zones'] = $this->model_localisation_zone->getZonesByCountryId($this->data['country_id']);
           }
           if(!empty($this->data['zone_id'])){
		    $this->data['cities'] = $this->model_localisation_city->getCitiesByZoneId($this->data['zone_id']);
           }
        //}

		$this->template = 'sale/alamat_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

    private function validateFormAlamat() {
    	if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
            $this->error['address_firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
            $this->error['address_lastname']= $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
            $this->error['address_address_1'][$key] = $this->language->get('error_address_1');
        }



        if ($this->request->post['country_id'] == '') {
            $this->error['address_country']= $this->language->get('error_country');
        }

        if ($this->request->post['zone_id'] == '') {
            $this->error['address_zone'] = $this->language->get('error_zone');
        }

        // tokocepat
        if ($this->request->post['city_id'] == '') {
            $this->error['address_city']= $this->language->get('error_city');
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

		public function country() {
		$json = array();

		$this->load->model('localisation/country');

    	$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->setOutput(json_encode($json));
	}

	public function address() {
		$json = array();

		if (!empty($this->request->get['address_id'])) {
			$this->load->model('sale/customer');

			$json = $this->model_sale_customer->getAddress($this->request->get['address_id']);
		}

		$this->response->setOutput(json_encode($json));
	}

    // tokocepat
	public function zone() {
		$json = array();

		$this->load->model('localisation/zone');

    	$zone_info = $this->model_localisation_zone->getZone($this->request->get['zone_id']);

		if ($zone_info) {
			$this->load->model('localisation/city');

			$json = array(
				'zone_id'           => $zone_info['zone_id'],
				'name'              => $zone_info['name'],
				'city'              => $this->model_localisation_city->getCitiesByZoneId($this->request->get['zone_id']),
				'status'            => $zone_info['status']
			);
		}

		$this->response->setOutput(json_encode($json));
	}

	public function detail(){
		$hasil = array();

		$this->load->model('sale/customer');
		if(isset($this->request->get['customer_id'])){
			if(!empty($this->request->get['customer_id'])){
				$column=array();
				$customer_id=$this->request->get['customer_id'];
				$data = array(
					'customer_id'      =>$customer_id
				);

				$hasil=$this->model_sale_customer->getVendor($data);
				if($hasil['limit_piutang'] == 0){
					$hasil['limit_piutang'] = 9999999999999999;
				}
				$hasil['pdeposit']=$this->currency->format($hasil['deposit']);


			}
		}
		$this->response->setOutput(json_encode($hasil));


	}


public function kunjungan() {
			    $this->load->language('sale/customer');

					$this->document->setTitle($this->language->get('heading_title'));

					$this->load->model('sale/customer');
			    if (isset($this->request->get['customer_id'])) {
						if(empty($this->request->get['customer_id'])){
							$this->redirect($this->url->link('sale/daftarcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
						}else{
							$customer_id = $this->request->get['customer_id'];
						}
					} else {
						$this->redirect($this->url->link('sale/daftarcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
					}
					if (isset($this->request->get['filter_name'])) {
						$filter_name = $this->request->get['filter_name'];
					} else {
						$filter_name = null;
					}



			    if (isset($this->request->get['pagealamat'])) {
						$pagealamat = $this->request->get['pagealamat'];
					} else {
						$pagealamat = 1;
					}

					$url = '';
			        if (isset($this->request->get['customer_id'])) {
						$url .= '&customer_id=' .$this->request->get['customer_id'];
					}


					if (isset($this->request->get['filter_name'])) {
						$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->get['filter_provinsi'])) {
						$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
					}

					if (isset($this->request->get['filter_customer_group'])) {
						$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
					}

					if (isset($this->request->get['filter_tgllahir'])) {
						$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
					}
					if (isset($this->request->get['sort'])) {
						$url .= '&sort=' . $this->request->get['sort'];
					}
					if (isset($this->request->get['order'])) {
						$url .= '&order=' . $this->request->get['order'];
					}

					if (isset($this->request->get['page'])) {
						$url .= '&page=' . $this->request->get['page'];
					}
			        if (isset($this->request->get['pagealamat'])) {
						$url .= '&pagealamat=' . $this->request->get['pagealamat'];
					}

			  		$this->data['cancel'] = $this->url->link('sale/daftarcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL');
					$this->data['insert'] = $this->url->link('sale/daftarcustomer/insertkunjungan', 'token=' . $this->session->data['token'] . $url, 'SSL');
					$this->data['delete'] = $this->url->link('sale/daftarcustomer/deletekunjungan', 'token=' . $this->session->data['token'] . $url, 'SSL');

					$this->data['visits'] = array();

					$data = array(
						'start'                    => ($pagealamat - 1) * $this->config->get('config_admin_limit'),
						'limit'                    => $this->config->get('config_admin_limit')
					);

					$address_total = $this->model_sale_customer->getTotalVisits($customer_id);

					$results = $this->model_sale_customer->getVisits($customer_id,$data);

					$this->load->model('tool/image');
					$this->load->model('user/user');

					$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 40, 40);

			   foreach ($results as $result) {
						$action = array();

						if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
							$image = $result['image'];
						} else {
							$image = 'no_image.jpg';
						}

						$sales=$this->model_user_user->getUser($result['sales']);

						/*$action[] = array(
							'text' => $this->language->get('text_edit'),
							'href' => $this->url->link('sale/daftarcustomer/updatealamat', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'].'&address_id='.$result['address_id'] . $url, 'SSL')
						);*/

						$this->data['visits'][] = array(
							'date_added'	=> date('d/m/y H:i:s',strtotime($result['date_added'])),
							'sales'	=> $sales['firstname'].' '.$sales['lastname'],
							'keterangan'	=> $result['keterangan'],
							'product_image_id'=> $result['product_image_id'],
							'thumb'	=> $this->model_tool_image->resize($image, 200, 200),
							'selected'       => isset($this->request->post['selected']) && in_array($result['product_image_id'], $this->request->post['selected']),

						);
					}

					$this->data['heading_title'] = $this->language->get('heading_title');

					$this->data['token'] = $this->session->data['token'];

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
			        if (isset($this->request->get['customer_id'])) {
						$url .= '&customer_id=' .$this->request->get['customer_id'];
					}


					if (isset($this->request->get['filter_name'])) {
						$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->get['filter_provinsi'])) {
						$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
					}

					if (isset($this->request->get['filter_customer_group'])) {
						$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
					}

					if (isset($this->request->get['filter_tgllahir'])) {
						$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
					}
					if (isset($this->request->get['sort'])) {
						$url .= '&sort=' . $this->request->get['sort'];
					}
					if (isset($this->request->get['order'])) {
						$url .= '&order=' . $this->request->get['order'];
					}

					if (isset($this->request->get['page'])) {
						$url .= '&page=' . $this->request->get['page'];
					}


					$pagination = new Pagination();
					$pagination->total = $address_total;
					$pagination->page = $pagealamat;
					$pagination->limit = $this->config->get('config_admin_limit');
					$pagination->text = $this->language->get('text_pagination');
					$pagination->url = $this->url->link('sale/daftarcustomer/kunjungan', 'token=' . $this->session->data['token'] . $url . '&pagealamat={page}', 'SSL');

					$this->data['pagination'] = $pagination->render();


					$this->template = 'sale/kunjungan_list.tpl';
					$this->children = array(
						'common/header',
						'common/footer'
					);

					$this->response->setOutput($this->render());
		}

		public function insertkunjungan() {
			$this->load->language('sale/customer');

	    	$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('sale/customer');
			if (!isset($this->request->get['customer_id'])) {
	            $this->redirect($this->url->link('sale/daftarcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	        }
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormKunjungan()) {
	      	  	$this->model_sale_customer->addVisits($this->request->get['customer_id'],$this->request->post);

				$this->session->data['success'] = $this->language->get('text_success');

				$url = '';
	            if (isset($this->request->get['customer_id'])) {
					$url .= '&customer_id=' . $this->request->get['customer_id'];
				}
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['filter_provinsi'])) {
					$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
				}

				if (isset($this->request->get['filter_customer_group'])) {
					$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
				}

				if (isset($this->request->get['filter_tgllahir'])) {
					$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
				}
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

	            if (isset($this->request->get['pagealamat'])) {
					$url .= '&pagealamat=' . $this->request->get['pagealamat'];
				}

				$this->redirect($this->url->link('sale/daftarcustomer/kunjungan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}

	    	$this->getFormKunjungan();
	  	}



	 private function getFormKunjungan() {
	    	$this->data['heading_title'] = $this->language->get('heading_title');

	  $this->data['token'] = $this->session->data['token'];

			if (isset($this->request->get['customer_id'])) {
				$this->data['customer_id'] = $this->request->get['customer_id'];
			} else {
				$this->data['customer_id'] = 0;
			}

	 		if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
			} else {
				$this->data['error_warning'] = '';
			}

			$url = '';
	      if (isset($this->request->get['customer_id'])) {
					$url .= '&customer_id=' . $this->request->get['customer_id'];
				}
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['filter_provinsi'])) {
					$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
				}

				if (isset($this->request->get['filter_customer_group'])) {
					$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
				}

				if (isset($this->request->get['filter_tgllahir'])) {
					$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
				}
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

	            if (isset($this->request->get['pagealamat'])) {
					$url .= '&pagealamat=' . $this->request->get['pagealamat'];
				}

	  		$this->data['action'] = $this->url->link('sale/daftarcustomer/insertkunjungan', 'token=' . $this->session->data['token'] . $url, 'SSL');


	    	$this->data['cancel'] = $this->url->link('sale/daftarcustomer/kunjungan', 'token=' . $this->session->data['token'] . $url, 'SSL');
				$this->data['customer']=$this->model_sale_customer->getVendor(array('customer_id' => $this->request->get['customer_id']));

	    $this->template = 'sale/kunjungan_form.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render());
		}

	 private function validateFormKunjungan() {

			if ($this->error && !isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_warning');
			}

			if (!$this->error) {
		  		return true;
			} else {
		  		return false;
			}
	  }

}
?>
