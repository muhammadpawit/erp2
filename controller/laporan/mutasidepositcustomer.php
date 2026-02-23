<?php
class ControllerLaporanMutasiDepositcustomer extends Controller {
	private $error = array();
	// baru 31 Januari 2020
	public function historygirobelumcair() {
		$this->load->language('sale/customer');
		$this->document->setTitle("History Deposit Customer");
		$this->load->model('sale/customer');
		if (isset($this->request->get['customer_id'])) {
			if(empty($this->request->get['customer_id'])){
				$this->redirect($this->url->link('laporan/depositcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				$customer_id = $this->request->get['customer_id'];
			}
		} else {
			$this->redirect($this->url->link('laporan/depositcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		if (isset($this->request->get['deposit'])) {
			$url .= '&deposit=' . urlencode(html_entity_decode($this->request->get['deposit'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
				if (isset($this->request->get['pagealamat'])) {
			$url .= '&pagealamat=' . $this->request->get['pagealamat'];
		}


		$this->data['cancel'] = $this->url->link('laporan/depositcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['addresses'] = array();

		$data = array(
			'start'                    => ($pagealamat - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);
		$address_total = $this->model_sale_customer->getTotalDeposits($this->request->get['customer_id']);
		$results = $this->model_sale_customer->historygirobelumcair($this->request->get['customer_id'],$data);
		foreach ($results as $result) {
			$action = array();
			$this->data['addresses'][] = array(
				'tglterima_giro'    => ($result['tglterima_giro']=='1970-01-01' OR $result['tglterima_giro']==null)?'-':date('d/m/Y',strtotime($result['tglterima_giro'])),
				'tgl_cair'           => ($result['tgl_terima']=='1970-01-01' OR $result['tgl_terima']==null)?'Belum diterima':date('d/m/Y',strtotime($result['tgl_terima'])),
				'tgl_jatuhtempo'           => date('d/m/y',strtotime($result['tgl_bayar'])),
				'nominal'           => $this->currency->format($result['nominal']),
				'no_giro'             => $result['no_giro'],
				'keterangan'             => $result['keterangan'],
				'status'             => $result['status'],
				'selected'       => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'actions'	=> $action

			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['halaman'] = "Histori Giro Belum Cair";
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

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$pagination = new Pagination();
		$pagination->total = $address_total;
		$pagination->page = $pagealamat;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/depositcustomer/deposit', 'token=' . $this->session->data['token'] . $url . '&pagealamat={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->template = 'laporan/historygiro_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function historygiro() {
		$this->data['halaman'] = "Histori Giro";
		$this->load->language('sale/customer');
		$this->document->setTitle("History Deposit Customer");
		$this->load->model('sale/customer');
		if (isset($this->request->get['customer_id'])) {
			if(empty($this->request->get['customer_id'])){
				$this->redirect($this->url->link('laporan/depositcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				$customer_id = $this->request->get['customer_id'];
			}
		} else {
			$this->redirect($this->url->link('laporan/depositcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		if (isset($this->request->get['deposit'])) {
			$url .= '&deposit=' . urlencode(html_entity_decode($this->request->get['deposit'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
				if (isset($this->request->get['pagealamat'])) {
			$url .= '&pagealamat=' . $this->request->get['pagealamat'];
		}


		$this->data['cancel'] = $this->url->link('laporan/depositcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['addresses'] = array();

		$data = array(
			'start'                    => ($pagealamat - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);
		$address_total = $this->model_sale_customer->getTotalDeposits($this->request->get['customer_id']);
		$results = $this->model_sale_customer->historygiro($this->request->get['customer_id'],$data);
		foreach ($results as $result) {
			$action = array();
			$this->data['addresses'][] = array(
				'tglterima_giro'    => ($result['tglterima_giro']=='1970-01-01' OR $result['tglterima_giro']==null)?'-':date('d/m/y',strtotime($result['tglterima_giro'])),
				'tgl_cair'           => date('d/m/y',strtotime($result['tgl_diterima'])),
				'tgl_jatuhtempo'           => date('d/m/y',strtotime($result['tgl_bayar'])),
				'nominal'           => $this->currency->format($result['nominal']),
				'no_giro'             => $result['no_giro'],
				'keterangan'             => $result['keterangan'],
				'status'             => $result['status'],
				'selected'       => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'actions'	=> $action

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

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$pagination = new Pagination();
		$pagination->total = $address_total;
		$pagination->page = $pagealamat;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/depositcustomer/deposit', 'token=' . $this->session->data['token'] . $url . '&pagealamat={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->template = 'laporan/historygiro_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	// end baru

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Laporan Deposit Customer');

		$this->load->model('sale/customer');

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start =date('Y-m-d');
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end =date('Y-m-d',strtotime("last day of this month"));;
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['filter_area'])) {
			$filter_area = $this->request->get['filter_area'];
		} else {
			$filter_area = null;
		}
		
		if (isset($this->request->get['deposit'])) {
			$deposit = $this->request->get['deposit'];
		} else {
			$deposit = null;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['deposit'])) {
			$url .= '&deposit=' . urlencode(html_entity_decode($this->request->get['deposit'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_area'])) {
			$url .= '&filter_area=' . $this->request->get['filter_area'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('catalog/title');
		$this->load->model('catalog/area');

		$this->data['customers'] = array();
		$sales=$this->user->getGroupId();
		if(isset($this->request->get['excel'])){
			$data = array(
				'filter_name'	  => $filter_name,
				'deposit'		  => $deposit,
				//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'           => $this->config->get('config_admin_limit')
			);
		}else{
			$data = array(
				'filter_name'	  => $filter_name,
				'deposit'		  => $deposit,
				'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit'           => $this->config->get('config_admin_limit')
			);
		}
			
			
		//get status
		$this->load->model('sale/invoice');
		$nominal=0;
		$piutang=0;
		// baru 6 Juli 2020
		$alldata = array(
			'filter_name'	  => $filter_name,
			'deposit'		  => $deposit,
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		$results = $this->model_sale_customer->getVendorsnew($data);
		$product_total = count($this->model_sale_customer->getVendorsnew($alldata));
		$totaldeposit=0;
		$totalgiro=0;
		$totalpiutang=0;
		$totalsisaharusbayar=0;
		$alls = $this->model_sale_customer->getVendorsnew($alldata);
		/*
		foreach($alls as $all){
			$nominal = $this->model_sale_customer->getnominalgiro($all['customer_id']);
			$piutang = $this->model_sale_customer->piutang($all['customer_id']);
			$sis=($piutang-$all['deposit']-$nominal<0)?0:$piutang-$all['deposit']-$nominal;
			$totaldeposit+=($all['deposit']);
			$totalgiro+=($nominal);
			$totalpiutang+=($piutang);
			$totalsisaharusbayar+=($sis);
		}*/
		$this->data['totaldeposit']=0;
		$this->data['totalgiro']=0;
		$this->data['totalpiutang']=0;
		$this->data['totalsisaharusbayar']=0;
		// end baru
		$saldoawal=0;
		$saldomasuk=0;
		$saldokeluar=0;
		$filtersaldo=array(
			'filter_name'=>$filter_name,
			'filter_date_start'=>$filter_date_start,
			'filter_date_end'=>$filter_date_end,
		);
		foreach ($results as $result) {
			$action = array();	
			$title=null;
			$area=null;
			$saldoawal=$this->model_sale_customer->getsaldoawal($filtersaldo,$result['customer_id']);
			$saldomasuk=$this->model_sale_customer->getsaldomasuk($filtersaldo,$result['customer_id']);
			$saldokeluar=$this->model_sale_customer->getsaldokeluar($filtersaldo,$result['customer_id']);
			if(isset($this->request->get['excel'])){
				if($saldoawal+($saldomasuk-$saldokeluar) <> 0){
					$this->data['customers'][] = array(
						'customer_id' => $result['customer_id'],
						'name'        => $title.' '.$result['name'],
						'awal'	=> $this->currency->format($saldoawal),
						'saldomasuk'=>$this->currency->format($saldomasuk),
						'saldokeluar'=>$this->currency->format($saldokeluar),
						'sisasaldo'	=> $this->currency->format($saldoawal+($saldomasuk-$saldokeluar)),
						'sisa'	=> $this->currency->format(0),
						'action'      => $action
					);
				}
			}else{
				$this->data['customers'][] = array(
					'customer_id' => $result['customer_id'],
					'name'        => $title.' '.$result['name'],
					'awal'	=> $this->currency->format($saldoawal),
					'saldomasuk'=>$this->currency->format($saldomasuk),
					'saldokeluar'=>$this->currency->format($saldokeluar),
					'sisasaldo'	=> $this->currency->format($saldoawal+($saldomasuk-$saldokeluar)),
					'sisa'	=> $this->currency->format(0),
					'action'      => $action
				);
			}
			
			

		}
		if($this->user->getUsername()=="pawitx"){
			echo "<pre>";print_r($this->data['customers']);exit;
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
		
		if (isset($this->request->get['deposit'])) {
			$url .= '&deposit=' . urlencode(html_entity_decode($this->request->get['deposit'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_area'])) {
			$url .= '&filter_area=' . $this->request->get['filter_area'];
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/mutasidepositcustomer', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$this->data['exportexcel']= $this->url->link('laporan/mutasidepositcustomer', 'token=' . $this->session->data['token'] . $url . '&excel=1', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['deposit'] = $deposit;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['token'] = $this->session->data['token'];

		$this->data['areas']=$this->model_catalog_area->getOptions();
		if(isset($this->request->get['excel'])){
			$this->template = 'laporan/mutasi_depositcustomerexcel.tpl';	
		}else{
			if($this->user->getUsername()=="pawitx"){
				$this->template = 'laporan/mutasi_depositcustomernew.tpl';
			}else{
				$this->template = 'laporan/mutasi_depositcustomernew.tpl';
			}
			
		}
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	// baru 11 Agustus 2020
	public function serversidebaru(){
		$this->load->model('sale/customer');
		$cust=$this->model_sale_customer->service();
		$this->response->setOutput(json_encode($cust));
	}
	// end

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

	public function deposit() {
		$this->load->language('sale/customer');
		$this->document->setTitle("History Deposit Customer");
		$this->load->model('sale/customer');
		if (isset($this->request->get['customer_id'])) {
			if(empty($this->request->get['customer_id'])){
				$this->redirect($this->url->link('laporan/depositcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				$customer_id = $this->request->get['customer_id'];
			}
		} else {
			$this->redirect($this->url->link('laporan/depositcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		if (isset($this->request->get['deposit'])) {
			$url .= '&deposit=' . urlencode(html_entity_decode($this->request->get['deposit'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
				if (isset($this->request->get['pagealamat'])) {
			$url .= '&pagealamat=' . $this->request->get['pagealamat'];
		}


		$this->data['cancel'] = $this->url->link('laporan/depositcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['addresses'] = array();

		$data = array(
			'start'                    => ($pagealamat - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);

		$address_total = $this->model_sale_customer->getTotalDeposits($this->request->get['customer_id']);

		$results = $this->model_sale_customer->getDeposits($this->request->get['customer_id'],$data);
		//print_r($results);

		foreach ($results as $result) {
			$action = array();
			/*if(empty($result['ref']) & $result['saldomasuk'] > 0){
				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('sale/customer/batalkandeposit', 'token=' . $this->session->data['token'] . '&id=' . $result['customer_id'], 'SSL')
				);
			}*/

			$this->data['addresses'][] = array(
				'date_trans'    => date('d/m/y',strtotime($result['date_trans'])),
				'saldomasuk'           => $this->currency->format($result['saldomasuk']),
				'saldokeluar'           => $this->currency->format($result['saldokeluar']),
				'ref'             => $result['ref'],
				'keterangan'             => $result['keterangan'],
				'selected'       => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'actions'	=> $action

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

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$pagination = new Pagination();
		$pagination->total = $address_total;
		$pagination->page = $pagealamat;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/depositcustomer/deposit', 'token=' . $this->session->data['token'] . $url . '&pagealamat={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->template = 'laporan/deposit_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

}
?>
