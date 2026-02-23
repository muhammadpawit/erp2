<?php
class ControllerCatalogKelompokaset extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Kelompok Aset');

		$this->load->model('catalog/kelompokaset');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Kelompok Aset');

		$this->load->model('catalog/kelompokaset');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_kelompokaset->addKelompokaset($this->request->post);

			$this->session->data['success'] = 'Data Kelompok Aset berhasil ditambahkan.';
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['jenis_aset'])) {
				$url .= '&jenis_aset=' . urlencode(html_entity_decode($this->request->get['jenis_aset'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('catalog/kelompokaset', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Kelompok Aset');

		$this->load->model('catalog/kelompokaset');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_kelompokaset->editKelompokaset($this->request->get['kelompok_aset_id'], $this->request->post);

			$this->session->data['success'] = 'Data Kelompok Aset berhasil diperbarui';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['jenis_aset'])) {
				$url .= '&jenis_aset=' . urlencode(html_entity_decode($this->request->get['jenis_aset'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/kelompokaset', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Kelompok Aset');

		$this->load->model('catalog/kelompokaset');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $kelompok_aset_id) {
				$this->model_catalog_kelompokaset->deleteKelompokaset($kelompok_aset_id);
			}

			$this->session->data['success'] = 'Data Kelompok Aset berhasil dihapus';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['jenis_aset'])) {
				$url .= '&jenis_aset=' . urlencode(html_entity_decode($this->request->get['jenis_aset'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			$this->redirect($this->url->link('catalog/kelompokaset', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		if (isset($this->request->get['jenis_aset'])) {
			$jenis_aset = $this->request->get['jenis_aset'];
		} else {
			$jenis_aset = null;
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
		if (isset($this->request->get['jenis_aset'])) {
			$url .= '&jenis_aset=' . urlencode(html_entity_decode($this->request->get['jenis_aset'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

   	$this->data['insert'] = $this->url->link('catalog/kelompokaset/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('catalog/kelompokaset/delete', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['options'] = array();

		$data = array(
			'filter_name'	  => $filter_name,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

		$results = $this->model_catalog_kelompokaset->getKelompokasets($data);
		$product_total = $this->model_catalog_kelompokaset->totalKelompokasets($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('catalog/kelompokaset/update', 'token=' . $this->session->data['token'] . '&kelompok_aset_id=' . $result['kelompok_aset_id'], 'SSL')
			);

		//	$cek= $this->model_catalog_kelompokaset->cekOption($result['product_options_id']);

			$this->data['options'][] = array(
				'kelompok_aset_id' => $result['kelompok_aset_id'],
				'name'        => $result['name'],
				'jenis_aset'	=> $result['jenis_aset'] == 1?'Bukan Bangunan':'Bangunan',
				'masa_manfaat'	=> $result['masa_manfaat'],
				'jenis_depresiasi'	=> $result['jenis_depresiasi']?'Garis Lurus':'Saldo Menurun',
				'nilai_depresiasi'	=> $result['nilai_depresiasi'],
				//'cek'		=> $cek,
				'selected'    => isset($this->request->post['selected']) && in_array($result['kelompok_aset_id'], $this->request->post['selected']),
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
		if (isset($this->request->get['jenis_aset'])) {
			$url .= '&jenis_aset=' . urlencode(html_entity_decode($this->request->get['jenis_aset'], ENT_QUOTES, 'UTF-8'));
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/kelompokaset', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['jenis_aset'] = $jenis_aset;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'catalog/kelompokaset_list.tpl';
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

		if (isset($this->request->get['jenis_aset'])) {
			$url .= '&jenis_aset=' . urlencode(html_entity_decode($this->request->get['jenis_aset'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error)) {
			$this->data['errors'] = $this->error;
		} else {
			$this->data['errors'] = '';
		}



		if (!isset($this->request->get['kelompok_aset_id'])) {
			$this->data['action'] = $this->url->link('catalog/kelompokaset/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/kelompokaset/update', 'token=' . $this->session->data['token'].$url. '&category_id=' . $this->request->get['category_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/kelompokaset', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['kelompok_aset_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$option_info = $this->model_catalog_kelompokaset->getKelompokaset($this->request->get['kelompok_aset_id']);
    	}

		$this->data['token'] = $this->session->data['token'];


		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (!empty($option_info)) {
			$this->data['name'] = $option_info['name'];
		} else {
			$this->data['name'] = '';
		}

		if (isset($this->request->post['jenis_aset'])) {
			$this->data['jenis_aset'] = $this->request->post['jenis_aset'];
		} elseif (!empty($option_info)) {
			$this->data['jenis_aset'] = $option_info['jenis_aset'];
		} else {
			$this->data['jenis_aset'] = '';
		}

		if (isset($this->request->post['masa_manfaat'])) {
			$this->data['masa_manfaat'] = $this->request->post['masa_manfaat'];
		} elseif (!empty($option_info)) {
			$this->data['masa_manfaat'] = $option_info['masa_manfaat'];
		} else {
			$this->data['masa_manfaat'] = '';
		}

		if (isset($this->request->post['jenis_depresiasi'])) {
			$this->data['jenis_depresiasi'] = $this->request->post['jenis_depresiasi'];
		} elseif (!empty($option_info)) {
			$this->data['jenis_depresiasi'] = $option_info['jenis_depresiasi'];
		} else {
			$this->data['jenis_depresiasi'] = '';
		}

		if (isset($this->request->post['nilai_depresiasi'])) {
			$this->data['nilai_depresiasi'] = $this->request->post['nilai_depresiasi'];
		} elseif (!empty($option_info)) {
			$this->data['nilai_depresiasi'] = $option_info['nilai_depresiasi'];
		} else {
			$this->data['nilai_depresiasi'] = '';
		}




		$this->template = 'catalog/kelompokaset_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		/*if (!$this->user->hasPermission('modify', 'catalog/kelompokaset')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Kelompok Aset';
		}*/

		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 255)) {
			$this->error['name'] = 'Nama Kelompok Aset harus diisi.';
		}

		if($this->request->post['masa_manfaat'] <= 0){
			$this->error['masa_manfaat'] = 'Masa manfaat harus lebih dari 0.';
		}

		if($this->request->post['nilai_depresiasi'] <= 0){
			$this->error['nilai_depresiasi'] = 'Nilai depresiasi harus lebih dari 0.';
		}

		/*if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = 'Mohon cek kembali form Anda.';
		}*/

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		/*if (!$this->user->hasPermission('modify', 'catalog/kelompokaset')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Kelompok Aset';
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
			$this->load->model('catalog/kelompokaset');

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

			$results = $this->model_catalog_kelompokaset->getKelompokasets($data);

			foreach ($results as $result) {
				$json[] = array(
					'kelompok_aset_id' => $result['kelompok_aset_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>
