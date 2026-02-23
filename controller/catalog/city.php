<?php
class ControllerCatalogCity extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Kecamatan');

		$this->load->model('catalog/city');

		$this->getList();
	}

	public function insert() {
		$error=false;
		/*if (!$this->user->hasPermission('modify', 'catalog/city')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Provinsi';
			$error=true;
		}*/

		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Kecamatan');

		$this->load->model('catalog/city');
		if(!$error){

			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_catalog_city->addCity($this->request->post);

				$this->session->data['success'] = 'Data Kecamatan berhasil ditambahkan.';
				$url = '';

				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['filter_zone_id'])) {
					$url .= '&filter_zone_id=' . urlencode(html_entity_decode($this->request->get['filter_zone_id'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['filter_country_id'])) {
					$url .= '&filter_country_id=' . urlencode(html_entity_decode($this->request->get['filter_country_id'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				$this->redirect($this->url->link('catalog/city', 'token=' . $this->session->data['token'].$url, 'SSL'));
			}

			$this->getForm();
		}else{
			$this->load->language('catalog/category');

			$this->document->setTitle('Master Data Kecamatan');

			$this->load->model('catalog/city');

			$this->getList();
		}
	}

	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Kecamatan');

		$this->load->model('catalog/city');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_city->editCity($this->request->get['id'], $this->request->post);

			$this->session->data['success'] = 'Data Kecamatan berhasil diperbarui';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_zone_id'])) {
				$url .= '&filter_zone_id=' . urlencode(html_entity_decode($this->request->get['filter_zone_id'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_country_id'])) {
				$url .= '&filter_country_id=' . urlencode(html_entity_decode($this->request->get['filter_country_id'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/city', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Kecamatan');

		$this->load->model('catalog/city');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_options_id) {
				$this->model_catalog_city->deleteCity($product_options_id);
			}

			$this->session->data['success'] = 'Data Kecamatan berhasil dihapus';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_zone_id'])) {
				$url .= '&filter_zone_id=' . urlencode(html_entity_decode($this->request->get['filter_zone_id'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_country_id'])) {
				$url .= '&filter_country_id=' . urlencode(html_entity_decode($this->request->get['filter_country_id'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			$this->redirect($this->url->link('catalog/city', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_zone_id'])) {
			$filter_zone_id = $this->request->get['filter_zone_id'];
		} else {
			$filter_zone_id = null;
		}

		if (isset($this->request->get['filter_country_id'])) {
			$filter_country_id = $this->request->get['filter_country_id'];
		} else {
			$filter_country_id = null;
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
		if (isset($this->request->get['filter_zone_id'])) {
			$url .= '&filter_zone_id=' . urlencode(html_entity_decode($this->request->get['filter_zone_id'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_country_id'])) {
			$url .= '&filter_country_id=' . urlencode(html_entity_decode($this->request->get['filter_country_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

   	$this->data['insert'] = $this->url->link('catalog/city/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/city/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['options'] = array();

		$data = array(
			'filter_name'	  => $filter_name,
			'filter_country_id'	=> $filter_country_id,
			'filter_zone_id'	=> $filter_zone_id,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

		$results = $this->model_catalog_city->getCitys($data);
		$product_total = $this->model_catalog_city->totalCitys($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('catalog/city/update', 'token=' . $this->session->data['token'].$url . '&id=' . $result['city_id'], 'SSL')
			);

		//	$cek= $this->model_catalog_city->cekOption($result['product_options_id']);

			$this->data['options'][] = array(
				'id' => $result['city_id'],
				'name'        => $result['name'],
				'provinsi'	=> $result['provinsi'],
				'kabupaten'	=> $result['kabupaten'],
				//'cek'		=> $cek,
				'selected'    => isset($this->request->post['selected']) && in_array($result['city_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}

		//print_r($this->error);

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
		if (isset($this->request->get['filter_zone_id'])) {
			$url .= '&filter_zone_id=' . urlencode(html_entity_decode($this->request->get['filter_zone_id'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_country_id'])) {
			$url .= '&filter_country_id=' . urlencode(html_entity_decode($this->request->get['filter_country_id'], ENT_QUOTES, 'UTF-8'));
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/city', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_country_id'] = $filter_country_id;
		$this->data['filter_zone_id'] = $filter_zone_id;
		$this->data['token'] = $this->session->data['token'];

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();

		$this->template = 'catalog/city_list.tpl';
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
		if (isset($this->request->get['filter_zone_id'])) {
			$url .= '&filter_zone_id=' . urlencode(html_entity_decode($this->request->get['filter_zone_id'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_country_id'])) {
			$url .= '&filter_country_id=' . urlencode(html_entity_decode($this->request->get['filter_country_id'], ENT_QUOTES, 'UTF-8'));
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
			$this->data['action'] = $this->url->link('catalog/city/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/city/update', 'token=' . $this->session->data['token'].$url. '&id=' . $this->request->get['id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/city', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$option_info = $this->model_catalog_city->getCity($this->request->get['id']);
    	}

		$this->data['token'] = $this->session->data['token'];


		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (!empty($option_info)) {
			$this->data['name'] = $option_info['name'];
		} else {
			$this->data['name'] = '';
		}

		if (isset($this->request->post['country_id'])) {
			$this->data['country_id'] = $this->request->post['country_id'];
		} elseif (!empty($option_info)) {
			$this->data['country_id'] = $option_info['country_id'];
		} else {
			$this->data['country_id'] = '';
		}

		if (isset($this->request->post['zone_id'])) {
			$this->data['zone_id'] = $this->request->post['zone_id'];
		} elseif (!empty($option_info)) {
			$this->data['zone_id'] = $option_info['zone_id'];
		} else {
			$this->data['zone_id'] = '';
		}

		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');

		$this->data['countries'] = $this->model_localisation_country->getCountries();
		if(!empty($this->data['country_id'])){
		 $this->data['zones'] = $this->model_localisation_zone->getZonesByCountryId($this->data['country_id']);
		}
		$this->template = 'catalog/city_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		/*if (!$this->user->hasPermission('modify', 'catalog/city')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Provinsi';
		}*/

		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 255)) {
			$this->error['name'] = 'Nama Kecamatan harus diisi.';
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
		/*if (!$this->user->hasPermission('modify', 'catalog/city')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Provinsi';
		}*/

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) ) {
			$this->load->model('catalog/city');

			if (isset($this->request->get['filter_name'])) {
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
				'filter_name'	  => $filter_name,
				'start'               => 0,
				'limit'               => $limit
			);

			$results = $this->model_catalog_city->getKabupatens($data);

			foreach ($results as $result) {
				$json[] = array(
					'country_id' => $result['country_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>
