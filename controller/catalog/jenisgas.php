<?php
class ControllerCatalogJenisgas extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Jenis Gas');

		$this->load->model('catalog/options');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Jenis Gas');

		$this->load->model('catalog/options');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_options->addOptions($this->request->post);

			$this->session->data['success'] = 'Data Jenis Gas berhasil ditambahkan.';
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('catalog/options', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Jenis Gas');

		$this->load->model('catalog/options');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_options->editOptions($this->request->get['product_options_id'], $this->request->post);

			$this->session->data['success'] = 'Data Jenis Gas berhasil diperbarui';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/options', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Jenis Gas');

		$this->load->model('catalog/options');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_options_id) {
				$this->model_catalog_options->deleteOptions($product_options_id);
			}

			$this->session->data['success'] = 'Data Jenis Gas berhasil dihapus';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			$this->redirect($this->url->link('catalog/options', 'token=' . $this->session->data['token'].$url, 'SSL'));
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

   	$this->data['insert'] = $this->url->link('catalog/options/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('catalog/options/delete', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['options'] = array();

		$data = array(
			'filter_name'	  => $filter_name,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

		$results = $this->model_catalog_options->getOptions($data);
		$product_total = $this->model_catalog_options->totalOptions($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('catalog/options/update', 'token=' . $this->session->data['token'] . '&product_options_id=' . $result['product_options_id'], 'SSL')
			);

		//	$cek= $this->model_catalog_options->cekOption($result['product_options_id']);

			$this->data['options'][] = array(
				'product_options_id' => $result['product_options_id'],
				'name'        => $result['name'],
				//'cek'		=> $cek,
				'selected'    => isset($this->request->post['selected']) && in_array($result['product_options_id'], $this->request->post['selected']),
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
		$pagination->url = $this->url->link('catalog/options', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'catalog/option_list.tpl';
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



		if (!isset($this->request->get['product_options_id'])) {
			$this->data['action'] = $this->url->link('catalog/options/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/options/update', 'token=' . $this->session->data['token'].$url. '&category_id=' . $this->request->get['category_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/options', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['product_options_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$option_info = $this->model_catalog_options->getOption($this->request->get['product_options_id']);
    	}

		$this->data['token'] = $this->session->data['token'];


		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (!empty($option_info)) {
			$this->data['name'] = $option_info['name'];
		} else {
			$this->data['name'] = '';
		}


		$this->template = 'catalog/option_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		/*if (!$this->user->hasPermission('modify', 'catalog/options')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Jenis Gas';
		}*/

		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 255)) {
			$this->error['name'] = 'Nama kategori harus diisi.';
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
		/*if (!$this->user->hasPermission('modify', 'catalog/options')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Jenis Gas';
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
			$this->load->model('catalog/options');

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

			$results = $this->model_catalog_options->getOptions($data);

			foreach ($results as $result) {
				$json[] = array(
					'product_options_id' => $result['product_options_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>
