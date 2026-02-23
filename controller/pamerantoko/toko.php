<?php
class ControllerPamerantokoToko extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/atk');

		$this->document->setTitle($this->language->get('Master Data Toko'));

		$this->load->model('toko/toko');

		$this->getList();
  	}

  	public function insert() {
    	$this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Master Data Toko'));

		$this->load->model('toko/toko');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_toko_toko->addPameran($this->request->post['pameran']);
	  		//print_r($this->request->post);
			$this->session->data['success'] = 'Success: Data toko berhasil ditambah.';

			$url = '';



			if (isset($this->request->get['filter_kode'])) {
				$url .= '&filter_kode=' . urlencode(html_entity_decode($this->request->get['filter_kode'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('toko/toko', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

	public function update() {
    	$this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Master Data Toko'));

		$this->load->model('toko/toko');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_toko_toko->editPameran($this->request->get['pameran_id'],$this->request->post['pameran']);
	  		//print_r($this->request->post);
			$this->session->data['success'] = 'Success: Data toko berhasil diperbarui.';

			$url = '';



			if (isset($this->request->get['filter_kode'])) {
				$url .= '&filter_kode=' . urlencode(html_entity_decode($this->request->get['filter_kode'], ENT_QUOTES, 'UTF-8'));
			}


			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('toko/toko', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

  	public function updateStatus() {
    	$this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Master Data Toko'));

		$this->load->model('toko/toko');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_toko_toko->editPameran($this->request->get['pameran_id'],$this->request->post['pameran']);
	  		//print_r($this->request->post);
			$this->session->data['success'] = 'Success: Data toko berhasil diperbarui.';

			$url = '';



			if (isset($this->request->get['filter_kode'])) {
				$url .= '&filter_kode=' . urlencode(html_entity_decode($this->request->get['filter_kode'], ENT_QUOTES, 'UTF-8'));
			}


			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('toko/home', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}







  	private function getList() {
		if (isset($this->request->get['filter_kode'])) {
			$filter_kode = $this->request->get['filter_kode'];
		} else {
			$filter_kode = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_kode'])) {
				$url .= '&filter_kode=' . urlencode(html_entity_decode($this->request->get['filter_kode'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('Master Data Toko'),
			'href'      => $this->url->link('toko/toko', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = $this->url->link('toko/toko/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('toko/toko/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['products'] = array();

		$data = array(

			'filter_kode'	  => $filter_kode,

			'filter_status'	  => $filter_status,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

		$this->load->model('tool/image');
		$product_total = $this->model_toko_toko->getTotalPameran($data);

		$results = $this->model_toko_toko->getPamerans($data);
		//$this->load->model('pameran/pengirimanbarang');
		foreach ($results as $result) {
			$action = array();
			/*if(!$this->model_pameran_pengirimanbarang->isNotReceived($result['pameran_id'])){
				$action[] = array(
				'text' => 'Terima Barang',
				'href' => $this->url->link('toko/pengirimanbarang', 'token=' . $this->session->data['token'] . '&filter_pameran_id=' . $result['pameran_id'], 'SSL')
			);
			}*/

      		/*	$action[] = array(
				'text' => 'Biaya Sewa',
				'href' => $this->url->link('toko/sewa', 'token=' . $this->session->data['token'] . '&filter_pameran_id=' . $result['pameran_id'] . $url, 'SSL')
			);*/

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('toko/toko/update', 'token=' . $this->session->data['token'] . '&pameran_id=' . $result['pameran_id'] . $url, 'SSL')
			);
			$action[] = array(
				'text' => $this->language->get('View'),
				'href' => $this->url->link('toko/home', 'token=' . $this->session->data['token'] . '&pameran_id=' . $result['pameran_id']. $url, 'SSL')
			);




      		$this->data['products'][] = array(
				'pameran_id' => $result['pameran_id'],
				'kode' => $result['kode'],
				'lokasi' => $result['lokasi'],

				'status'      => $result['status'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['pameran_id'], $this->request->post['selected']),
				'action'     => $action
			);
    	}

		$this->data['heading_title'] = $this->language->get('Master Data Toko');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');

		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_copy'] = $this->language->get('button_copy');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

 		$this->data['token'] = $this->session->data['token'];

 		if(isset($this->session->data['warning'])){
			$this->data['error_warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		}
		else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_kode'])) {
				$url .= '&filter_kode=' . urlencode(html_entity_decode($this->request->get['filter_kode'], ENT_QUOTES, 'UTF-8'));
			}



			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		/*$this->data['sort_name'] = $this->url->link('catalog/atk', 'token=' . $this->session->data['token'] . '&sort=p.nama' . $url, 'SSL');
		$this->data['sort_toko_id'] = $this->url->link('catalog/atk', 'token=' . $this->session->data['token'] . '&sort=pd.toko_id' . $url, 'SSL');

		$this->data['sort_order'] = $this->url->link('catalog/atk', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');*/

		$url = '';


		if (isset($this->request->get['filter_kode'])) {
				$url .= '&filter_kode=' . urlencode(html_entity_decode($this->request->get['filter_kode'], ENT_QUOTES, 'UTF-8'));
			}



			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('toko/toko', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		/*$this->data['filter_name'] = $filter_name;
		$this->data['filter_toko_id'] = $filter_toko_id;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		*/
		$this->data['insert'] = $this->url->link('toko/toko/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->template = 'toko/toko_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('Master Data Toko');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_none'] = $this->language->get('text_none');
    	$this->data['text_yes'] = $this->language->get('text_yes');
    	$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$this->data['text_plus'] = $this->language->get('text_plus');
		$this->data['text_minus'] = $this->language->get('text_minus');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['text_option'] = $this->language->get('text_option');
		$this->data['text_option_value'] = $this->language->get('text_option_value');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_percent'] = $this->language->get('text_percent');
		$this->data['text_amount'] = $this->language->get('text_amount');


    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_attribute'] = $this->language->get('button_add_attribute');
		$this->data['button_add_option'] = $this->language->get('button_add_option');
		$this->data['button_add_option_value'] = $this->language->get('button_add_option_value');
		$this->data['button_add_discount'] = $this->language->get('button_add_discount');
		$this->data['button_add_special'] = $this->language->get('button_add_special');
		$this->data['button_add_image'] = $this->language->get('button_add_image');
		$this->data['button_remove'] = $this->language->get('button_remove');

    	$this->data['tab_general'] = $this->language->get('tab_general');
    	$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_attribute'] = $this->language->get('tab_attribute');
		$this->data['tab_option'] = $this->language->get('tab_option');
		$this->data['tab_discount'] = $this->language->get('tab_discount');
		$this->data['tab_special'] = $this->language->get('tab_special');
    	$this->data['tab_image'] = $this->language->get('tab_image');
		$this->data['tab_links'] = $this->language->get('tab_links');
		$this->data['tab_reward'] = $this->language->get('tab_reward');
		$this->data['tab_design'] = $this->language->get('tab_design');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error)) {
			$this->data['error'] = $this->error;
		} else {
			$this->data['error'] = '';
		}

		if(isset($this->request->get['updatestatus'])){
			$this->data['updatestatus'] = true;
		}


		$url = '';

		if (isset($this->request->get['filter_kode'])) {
				$url .= '&filter_kode=' . urlencode(html_entity_decode($this->request->get['filter_kode'], ENT_QUOTES, 'UTF-8'));
			}



			if (isset($this->request->get['updatestatus'])) {
				$url .= '&updatestatus=' . urlencode(html_entity_decode($this->request->get['updatestatus'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('Master Data Toko'),
			'href'      => $this->url->link('toko/toko', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['pameran_id'])) {
			$this->data['action'] = $this->url->link('toko/toko/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('toko/toko/update', 'token=' . $this->session->data['token'] . '&pameran_id=' . $this->request->get['pameran_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('toko/toko', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->load->model('catalog/gudang');

		$this->data['gudangs']=$this->model_catalog_gudang->getGudangs();
		//this->data['exists']=array();
		if (!isset($this->request->get['pameran_id'])) {
			$this->data['action'] = $this->url->link('toko/toko/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('toko/toko/update', 'token=' . $this->session->data['token'] . '&pameran_id=' . $this->request->get['pameran_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('toko/toko', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['pameran_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
	      		$pameran_info = $this->model_toko_toko->getPameran($this->request->get['pameran_id']);
	    	}
		$this->data['pameran']=array();
		if(!empty($pameran_info)){
			$this->data['pameran']=$pameran_info;
		}
		elseif($this->request->server['REQUEST_METHOD'] == 'POST'){
			$this->data['pameran']=$this->request->post['pameran'];
		}
		else{
			$this->data['pameran']=array();
		}
//print_r($this->data['pameran']);

		$this->data['token'] = $this->session->data['token'];

		$this->load->model('user/user');
		$this->data['users']=$this->model_user_user->getAllUser();


		$this->template = 'toko/toko_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'toko/toko')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}



    	if ((utf8_strlen($this->request->post['pameran']['lokasi']) < 1) || (utf8_strlen($this->request->post['pameran']['lokasi']) > 64)) {
      		$this->error['lokasi'] = $this->language->get('Lokasi harus diisi');
    	}



		if ($this->error && !isset($this->error['warning'])) {
			//$this->error['warning'] = 'Mohon cek form Anda kembali: ';
		}

    	if (!$this->error) {
			return true;
    	} else {
      		return false;
    	}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'toko/toko')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

		public function autocomplete() {
			$rests = array();

			$this->load->model('pamerantoko/toko');

				if (isset($this->request->get['q'])) {
					$filter_lokasi = $this->request->get['q'];
				} else {
					$filter_lokasi = '';
				}


				if (isset($this->request->get['limit'])) {
					$limit = $this->request->get['limit'];
				} else {
					$limit = 20;
				}

				$data = array(
					'filter_lokasi'         => $filter_lokasi,
					'start'               => 0,
					'limit'               => $limit
				);

				$results = $this->model_pamerantoko_toko->getPamerans($data);
				foreach($results as $r){
					$rests[]=array(
						'id'	=> $r['pameran_id'],
						'text'	=> $r['lokasi']
					);
				}
			$this->response->setOutput(json_encode($rests));
		}


}
?>
