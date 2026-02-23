<?php
class ControllerCatalogVendorimport extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Vendor Import');

		$this->load->model('catalog/vendorimport');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Vendor Import');

		$this->load->model('catalog/vendorimport');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_vendorimport->addVendor($this->request->post);

			$this->session->data['success'] = 'Data Vendor Import berhasil ditambahkan.';
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('catalog/vendorimport', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Vendor Import');

		$this->load->model('catalog/vendorimport');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_vendorimport->updateVendor($this->request->post, array('id'=>$this->request->get['id']));

			$this->session->data['success'] = 'Data Vendor Import berhasil diperbarui';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/vendorimport', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Vendor Import');

		$this->load->model('catalog/vendorimport');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $id) {
				$data=array('hapus'	=> 1);
				$where=array('id' => $id);
				$this->model_catalog_vendorimport->updateVendor($data,$where);
			}

			$this->session->data['success'] = 'Data Vendor Import berhasil dihapus';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			$this->redirect($this->url->link('catalog/vendorimport', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
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

   		$this->data['insert'] = $this->url->link('catalog/vendorimport/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('catalog/vendorimport/delete', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['excel'] = $this->url->link('catalog/vendorimport', 'token=' . $this->session->data['token'] .'&excel=true'. $url, 'SSL');
		$this->data['vendors'] = array();

		$data = array(
			'name'	  => array('LIKE',$filter_name),
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		if(isset($this->request->get['excel'])){
			$offset=null;
			$limit=0;
		}else{
			$offset=($page - 1) * $this->config->get('config_admin_limit');
			$limit=$this->config->get('config_admin_limit');
		}
		

		$results = $this->model_catalog_vendorimport->getVendors($data,array(),$limit,$offset);
		$product_total = $this->model_catalog_vendorimport->totalVendors($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('catalog/vendorimport/update', 'token=' . $this->session->data['token'] . '&id=' . $result['id'], 'SSL')
			);

			$action[] = array(
				'text' => 'Contact',
				'href' => $this->url->link('catalog/vendorimport/contact', 'token=' . $this->session->data['token'] . '&id=' . $result['id'], 'SSL')
			);

			$action[] = array(
				'text' => 'Deposit',
				'href' => $this->url->link('catalog/vendorimport/deposit', 'token=' . $this->session->data['token'] . '&id=' . $result['id'], 'SSL')
			);

		//	$cek= $this->model_catalog_vendorimport->cekOption($result['id']);

			$this->data['vendors'][] = array(
				'id' => $result['id'],
				'name'        => $result['name'],
				'alamat'        => $result['alamat'],
				'telephone'        => $result['telephone'],
				'npwp'	=> $result['npwp'],
				'email'	=> $result['email'],
				'hutang'	=> '$'.number_format($result['hutang'],2,'.',','),
				'deposit'	=> '$'.number_format($result['deposit'],2,'.',','),
				'jatuhtempo'=> date('d/m/y',strtotime($result['jatuhtempo'])),
				//'cek'		=> $cek,
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
		$pagination->url = $this->url->link('catalog/vendorimport', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['token'] = $this->session->data['token'];

		if(isset($this->request->get['excel'])){
			$this->template = 'catalog/vendorimport_list_excel.tpl';
		}else{
			$this->template = 'catalog/vendorimport_list.tpl';
		}
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getForm() {

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}



		if (!isset($this->request->get['id'])) {
			$this->data['action'] = $this->url->link('catalog/vendorimport/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/vendorimport/update', 'token=' . $this->session->data['token'].$url. '&id=' . $this->request->get['id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/vendorimport', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$option_info = $this->model_catalog_vendorimport->getVendor(array('id'	=> $this->request->get['id']));
    	}

		$this->data['token'] = $this->session->data['token'];


		if (!empty($this->request->post)) {
			$this->data['name'] = $this->request->post['name'];
			$this->data['alamat'] = $this->request->post['alamat'];
			$this->data['email'] = $this->request->post['email'];
			$this->data['npwp'] = $this->request->post['npwp'];
			$this->data['telephone'] = $this->request->post['telephone'];


		} elseif (!empty($option_info)) {
			$this->data['name'] = $option_info['name'];
			$this->data['alamat'] = $option_info['alamat'];
			$this->data['npwp'] = $option_info['npwp'];
			$this->data['email'] = $option_info['email'];
			$this->data['telephone'] = $option_info['telephone'];

		} else {
			$this->data['name'] = '';
			$this->data['alamat'] = '';
			$this->data['npwp'] = '';
			$this->data['email'] = '';
			$this->data['telephone'] = '';

		}


		$this->template = 'catalog/vendorimport_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		/*if (!$this->user->hasPermission('modify', 'catalog/vendorimport')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Vendor Import';
		}*/

		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 255)) {
			$this->error['name'] = 'Nama vendor harus diisi.';
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = 'Mohon cek kembali form Anda.';
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		/*if (!$this->user->hasPermission('modify', 'catalog/vendorimport')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Vendor Import';
		}*/

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function autocomplete() {
		$json = array();

		//if (isset($this->request->get['filter_name']) ) {
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
				//'start'               => 0,
				//'limit'               => $limit
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
		//}

		$this->response->setOutput(json_encode($json));
	}

	//contact

	public function contact() {
		$this->document->setTitle('Master Data Vendor Import');

		$this->load->model('catalog/vendorcontact');
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

		if (isset($this->request->get['filter_name_contact'])) {
			$filter_name_contact = $this->request->get['filter_name_contact'];
		} else {
			$filter_name_contact = null;
		}
		if (isset($this->request->get['pagecontact'])) {
			$pagecontact = $this->request->get['pagecontact'];
		} else {
			$pagecontact = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['cancel'] = $this->url->link('catalog/vendorimport', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url'] = $this->url->link('catalog/vendorimport/contact', 'token=' . $this->session->data['token'], 'SSL');
		if (isset($this->request->get['id'])) {
			$vendor_id = $this->request->get['id'];
			if(empty($vendor_id)){
				$this->redirect($this->url->link('catalog/vendorimport', 'token=' . $this->session->data['token'].$url, 'SSL'));
			}else{
				$url .= '&id=' . $this->request->get['id'];
			}
		} else {
				$this->redirect($this->url->link('catalog/vendorimport', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		if (isset($this->request->get['filter_name_contact'])) {
			$url .= '&filter_name_contact=' . urlencode(html_entity_decode($this->request->get['filter_name_contact'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['pagecontact'])) {
			$url .= '&pagecontact=' . $this->request->get['pagecontact'];
		}

   	$this->data['insert'] = $this->url->link('catalog/vendorimport/insertcontact', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/vendorimport/deletecontact', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['vendors'] = array();

		$data = array(
			'name'	  => array('LIKE',$filter_name_contact),
			'jenis'	=> 2,
			'vendor_id'	=> $vendor_id
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		$offset=($pagecontact - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$results = $this->model_catalog_vendorcontact->getVendors($data,array(),$limit,$offset);
		$product_total = $this->model_catalog_vendorcontact->totalVendors($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('catalog/vendorimport/updatecontact', 'token=' . $this->session->data['token'].$url . '&contactid=' . $result['id'], 'SSL')
			);
			$this->data['vendors'][] = array(
				'id' => $result['id'],
				'name'        => $result['name'],
				'telephone'        => $result['telephone'],
				'email'	=> $result['email'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'	=> $action
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
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['filter_name_contact'])) {
			$url .= '&filter_name_contact=' . urlencode(html_entity_decode($this->request->get['filter_name_contact'], ENT_QUOTES, 'UTF-8'));
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $pagecontact;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/vendorimport/contact', 'token=' . $this->session->data['token'] . $url . '&pagecontact={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'catalog/vendorcontact_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function insertcontact() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Vendor Import');

		$this->load->model('catalog/vendorcontact');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormContact()) {
			$this->model_catalog_vendorcontact->addVendor($this->request->post);

			$this->session->data['success'] = 'Data Kontak Vendor Import berhasil ditambahkan.';
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['id'])) {
				$url .= '&id=' . $this->request->get['id'];
			}
			if (isset($this->request->get['filter_name_contact'])) {
				$url .= '&filter_name_contact=' . urlencode(html_entity_decode($this->request->get['filter_name_contact'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['pagecontact'])) {
				$url .= '&pagecontact=' . $this->request->get['pagecontact'];
			}
			$this->redirect($this->url->link('catalog/vendorimport/contact', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getFormContact();
	}

	public function updatecontact() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Vendor Import');

		$this->load->model('catalog/vendorcontact');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormContact()) {
			$this->model_catalog_vendorcontact->updateVendor($this->request->post, array('id'=>$this->request->get['contactid']));

			$this->session->data['success'] = 'Data Kontak Vendor Import berhasil diperbarui';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['id'])) {
				$url .= '&id=' . $this->request->get['id'];
			}
			if (isset($this->request->get['filter_name_contact'])) {
				$url .= '&filter_name_contact=' . urlencode(html_entity_decode($this->request->get['filter_name_contact'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['pagecontact'])) {
				$url .= '&pagecontact=' . $this->request->get['pagecontact'];
			}
			$this->redirect($this->url->link('catalog/vendorimport/contact', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getFormContact();
	}

	public function deletecontact() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Vendor Import');

		$this->load->model('catalog/vendorcontact');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $id) {
				$data=array('hapus'	=> 1);
				$where=array('id' => $id);
				$this->model_catalog_vendorcontact->updateVendor($data,$where);
			}

			$this->session->data['success'] = 'Data Kontak Vendor Import berhasil dihapus';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['id'])) {
				$url .= '&id=' . $this->request->get['id'];
			}
			if (isset($this->request->get['filter_name_contact'])) {
				$url .= '&filter_name_contact=' . urlencode(html_entity_decode($this->request->get['filter_name_contact'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['pagecontact'])) {
				$url .= '&pagecontact=' . $this->request->get['pagecontact'];
			}
			$this->redirect($this->url->link('catalog/vendorimport/contact', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->redirect($this->url->link('catalog/vendorimport/contact', 'token=' . $this->session->data['token'].$url, 'SSL'));
	}
	private function getFormContact() {
	$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['id'])) {
			$vendor_id = $this->request->get['id'];
			if(empty($vendor_id)){
				$this->redirect($this->url->link('catalog/vendorimport', 'token=' . $this->session->data['token'].$url, 'SSL'));
			}else{
				$url .= '&id=' . $this->request->get['id'];
			}
		} else {
				$this->redirect($this->url->link('catalog/vendorimport', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}
		if (isset($this->request->get['filter_name_contact'])) {
			$url .= '&filter_name_contact=' . urlencode(html_entity_decode($this->request->get['filter_name_contact'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['pagecontact'])) {
			$url .= '&pagecontact=' . $this->request->get['pagecontact'];
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}



		if (!isset($this->request->get['contactid'])) {
			$this->data['action'] = $this->url->link('catalog/vendorimport/insertcontact', 'token=' . $this->session->data['token'].$url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/vendorimport/updatecontact', 'token=' . $this->session->data['token'].$url. '&contactid=' . $this->request->get['contactid'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/vendorimport/contact', 'token=' . $this->session->data['token'].$url, 'SSL');

		if (isset($this->request->get['contactid']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$option_info = $this->model_catalog_vendorcontact->getVendor(array('id'	=> $this->request->get['contactid']));
    	}

		$this->data['token'] = $this->session->data['token'];


		if (!empty($this->request->post)) {
			$this->data['name'] = $this->request->post['name'];
			$this->data['email'] = $this->request->post['email'];
			$this->data['telephone'] = $this->request->post['telephone'];
			$this->data['vendor_id'] = $this->request->post['vendor_id'];
			$this->data['jenis'] = $this->request->post['jenis'];

		} elseif (!empty($option_info)) {
			$this->data['name'] = $option_info['name'];
			$this->data['email'] = $option_info['email'];
			$this->data['telephone'] = $option_info['telephone'];
			$this->data['vendor_id'] = $this->request->get['id'];
			$this->data['jenis'] = $option_info['jenis'];

		} else {
			$this->data['name'] = '';
			$this->data['email'] = '';
			$this->data['telephone'] = '';
			$this->data['vendor_id'] = $this->request->get['id'];
			$this->data['jenis'] = 2;
		}


		$this->template = 'catalog/vendorcontact_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateFormContact() {
		/*if (!$this->user->hasPermission('modify', 'catalog/vendorimport')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Vendor Import';
		}*/

		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 255)) {
			$this->error['name'] = 'Nama vendor harus diisi.';
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = 'Mohon cek kembali form Anda.';
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function deposit() {
				$this->load->language('sale/customer');

		$this->document->setTitle("Deposit Customer");

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


		$this->data['cancel'] = $this->url->link('catalog/vendorimport', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['insert'] = $this->url->link('pembelian/pembayarandepositimport/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['penyesuaian'] = $this->url->link('catalog/vendorimport/insertdeposit', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['addresses'] = array();

		$data = array(
			'start'                    => ($pagealamat - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);

		$address_total = $this->model_catalog_vendorimport->getTotalDeposits($this->request->get['id']);

		$results = $this->model_catalog_vendorimport->getDeposits($this->request->get['id'],$data);
		//print_r($results);

		foreach ($results as $result) {
			$action = array();
			/*if(empty($result['ref']) & $result['saldomasuk'] > 0){
				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('sale/customer/batalkandeposit', 'token=' . $this->session->data['token'] . '&id=' . $result['id'], 'SSL')
				);
			}*/

			$this->data['addresses'][] = array(
				'date_trans'    => date('d/m/y',strtotime($result['date_trans'])),
				'saldomasuk'           => '$'.number_format($result['saldomasuk'],2,'.',','),
				'saldokeluar'           => '$'.number_format($result['saldokeluar'],2,'.',','),
				'kurs'           => $this->currency->format($result['kurs']),
				'ref'             => $result['ref'],
				'keterangan'             => $result['keterangan'],
				'urlref'	=> $this->url->link($result['urlref'].'/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['idref'], 'SSL'),
				'no_dokumen'             => $result['no_dokumen'],
				
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


		$this->template = 'catalog/depositimport_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
		}

	public function insertdeposit() {
		$this->load->language('sale/customer');

			$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');
		if (!isset($this->request->get['id'])) {
						$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormDeposit()) {
						$this->model_catalog_vendorimport->addDeposit($this->request->post,$this->request->get['id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
						if (isset($this->request->get['customer_id'])) {
				$url .= '&customer_id=' . $this->request->get['customer_id'];
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

						if (isset($this->request->get['pagealamat'])) {
				$url .= '&pagealamat=' . $this->request->get['pagealamat'];
			}

			$this->redirect($this->url->link('sale/customer/deposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

			$this->getFormDeposit();
		}



		public function deletedeposit() {
		$this->load->language('sale/customer');

			$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

				if (!isset($this->request->get['customer_id'])) {
						$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}

			if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $address_id) {
				$this->model_catalog_vendorimport->deleteContact($address_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
						if (isset($this->request->get['customer_id'])) {
				$url .= '&customer_id=' . $this->request->get['customer_id'];
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

			if (isset($this->request->get['filter_ip'])) {
				$url .= '&filter_ip=' . $this->request->get['filter_ip'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

			$this->redirect($this->url->link('sale/customer/contact', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}

			$this->getFormContact();
		}

		private function getFormDeposit() {
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

						if (isset($this->request->get['pagealamat'])) {
				$url .= '&pagealamat=' . $this->request->get['pagealamat'];
			}

			$this->data['action'] = $this->url->link('sale/customer/insertdeposit', 'token=' . $this->session->data['token'] . $url, 'SSL');


			$this->data['cancel'] = $this->url->link('sale/customer/deposit', 'token=' . $this->session->data['token'] . $url, 'SSL');




		$this->load->model('keuangan/bank');

		$this->data['banks'] = $this->model_keuangan_bank->getBanks(array(),array(),array('currency'	=> 1),array(),0,null);



		$this->template = 'sale/deposit_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	 private function validateFormDeposit() {
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
