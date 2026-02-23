<?php
class ControllerLaporanDepositsupplierimport extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Vendor Lokal');

		$this->load->model('catalog/vendorimport');

		$this->getList();
	}


	private function getList() {
		$this->data['delete']=null;
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

   		$this->data['excel'] = $this->url->link('laporan/depositsupplierimport', 'token=' . $this->session->data['token'] .'&excel=true'.$url, 'SSL');

		$this->data['vendors'] = array();

		$data = array(
			'name'	  => array('LIKE',$filter_name),
		);
		if(isset($this->request->get['excel'])){
			$offset=null;
			$limit=0;
		}else{
			$offset=($page - 1) * $this->config->get('config_admin_limit');
			$limit=$this->config->get('config_admin_limit');
		}
		$results =$this->model_catalog_vendorimport->getVendors($data,array(),$limit,$offset);
		$product_total = $this->model_catalog_vendorimport->totalVendors($data);
		$hutang=0;
		$giro=0;
		$totaldeposit=0;
		$totalgiro=0;
		$totalhutang=0;
		$totalsisa=0;
		$all=$this->model_catalog_vendorimport->getVendors($data,array(),0,null);
		foreach($all as $result){
			$hutang=$this->model_catalog_vendorimport->hutang($result['id']);
			$sisa=$hutang-$result['deposit']<0?0:$hutang-$result['deposit'];
			$totaldeposit+=($result['deposit']);
			$totalgiro+=(0);
			$totalhutang+=($hutang);
			$totalsisa+=( $sisa );
		}
		$this->data['totaldeposit']='$'.number_format($totaldeposit,2);
		$this->data['totalgiro']='$'.number_format($totalgiro,2);
		$this->data['totalhutang']='$'.number_format($totalhutang,2);
		$this->data['totalsisa']='$'.number_format($totalsisa,2);
		foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => 'History Deposit',
				'href' => $this->url->link('laporan/depositsupplierimport/deposit', 'token=' . $this->session->data['token'] . '&id=' . $result['id'], 'SSL')
			);
			$hutang=$this->model_catalog_vendorimport->hutang($result['id']);
			$this->data['vendors'][] = array(
				'id' => $result['id'],
				'name'        => $result['name'],
				'hutang'	=> '$'.number_format(($hutang==null?0:$hutang),2),
				'deposit'	=> '$'.number_format($result['deposit'],2),
				'giro'	=> '$'.number_format(0,2),
				'sisa'	=> $hutang-$result['deposit']<0?0:$hutang-$result['deposit'],
				'jatuhtempo'=> date('d/m/y',strtotime($result['jatuhtempo'])),
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/depositsupplierlokal', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['token'] = $this->session->data['token'];

		if(isset($this->request->get['excel'])){
			$this->template = 'laporan/laporandepositvendorimport_excel.tpl';
		}else{
			$this->template = 'laporan/laporandepositvendorimport_list.tpl';
		}
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function autocomplete() {
		$json = array();

		$this->load->model('catalog/vendorimport');



			if (isset($this->request->get['q'])) {
				$filter_name = $this->request->get['q'];
			} else if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = null;
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
			'name'	  => array('LIKE',$filter_name),
			);
			$offset=0;
			$limit=$limit;

			$results = $this->model_catalog_vendorimport->getVendors($data,array(),$limit,$offset);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['id'],
					'text'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}


		$this->response->setOutput(json_encode($json));
	}

	//contact

	public function deposit() {
		$this->load->language('sale/customer');

		$this->document->setTitle("Deposit Vendor");

		$this->load->model('catalog/vendorimport');
		if (isset($this->request->get['id'])) {
			if(empty($this->request->get['id'])){
				$this->redirect($this->url->link('catalog/vendorimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				$id = $this->request->get['id'];
			}
		} else {
			$this->redirect($this->url->link('catalog/vendorimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}


		if (isset($this->request->get['pagealamat'])) {
			$pagealamat = $this->request->get['pagealamat'];
		} else {
			$pagealamat = 1;
		}

		$url = '';
				if (isset($this->request->get['id'])) {
			$url .= '&id=' .$this->request->get['id'];
		}


		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
				if (isset($this->request->get['pagealamat'])) {
			$url .= '&pagealamat=' . $this->request->get['pagealamat'];
		}


		$this->data['cancel'] = $this->url->link('laporan/depositsupplierlokal', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['insert'] = $this->url->link('laporan/depositsupplierlokal', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['addresses'] = array();

		$data = array(
			'start'                    => ($pagealamat - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);

		$address_total = $this->model_catalog_vendorimport->getTotalDeposits($this->request->get['id'],array());

		$results = $this->model_catalog_vendorimport->getDeposits($this->request->get['id'],$data);

		foreach ($results as $result) {
			$action = array();

			$this->data['addresses'][] = array(
				'date_trans'    => date('d/m/y',strtotime($result['date_trans'])),
				'saldomasuk'           => $this->currency->format($result['saldomasuk']),
				'saldokeluar'           => $this->currency->format($result['saldokeluar']),
				'no_dokumen'             => $result['no_dokumen'],
				'keterangan'             => $result['keterangan'],
				'urlref'	=> $this->url->link($result['urlref'].'/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['idref'], 'SSL'),
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
				if (isset($this->request->get['id'])) {
			$url .= '&id=' .$this->request->get['id'];
		}


		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

	if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$pagination = new Pagination();
		$pagination->total = $address_total;
		$pagination->page = $pagealamat;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/vendorimport/deposit', 'token=' . $this->session->data['token'] . $url . '&pagealamat={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->template = 'catalog/depositlokal_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
		}

}
?>
