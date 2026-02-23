<?php
class ControllerKeuanganCoa extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data COA');

		$this->load->model('keuangan/coa');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data COA');

		$this->load->model('keuangan/coa');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_keuangan_coa->addCategory($this->request->post);

			$this->session->data['success'] = 'Data COA berhasil ditambahkan.';
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kode_rek'])) {
				$url .= '&filter_kode_rek=' . urlencode(html_entity_decode($this->request->get['filter_kode_rek'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('keuangan/coa', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data COA');

		$this->load->model('keuangan/coa');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_keuangan_coa->editCategory($this->request->get['category_id'], $this->request->post);

			$this->session->data['success'] = 'Data COA berhasil diperbarui';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kode_rek'])) {
				$url .= '&filter_kode_rek=' . urlencode(html_entity_decode($this->request->get['filter_kode_rek'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('keuangan/coa', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data COA');

		$this->load->model('keuangan/coa');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $category_id) {
				$this->model_keuangan_coa->deleteCategory($category_id);
			}

			$this->session->data['success'] = 'Data COA berhasil dihapus';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kode_rek'])) {
				$url .= '&filter_kode_rek=' . urlencode(html_entity_decode($this->request->get['filter_kode_rek'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
			}

			$this->redirect($this->url->link('keuangan/coa', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_kode_rek'])) {
			$filter_kode_rek = $this->request->get['filter_kode_rek'];
		} else {
			$filter_kode_rek = null;
		}

		if (isset($this->request->get['filter_type'])) {
			$filter_type = $this->request->get['filter_type'];
		} else {
			$filter_type = null;
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
		if (isset($this->request->get['filter_kode_rek'])) {
			$url .= '&filter_kode_rek=' . urlencode(html_entity_decode($this->request->get['filter_kode_rek'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
   	$this->data['insert'] = $this->url->link('keuangan/coa/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('keuangan/coa/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['categories'] = array();
		$data = array(
			'filter_name'	  => $filter_name,
			'filter_type'	=> $filter_type,
			'filter_kode_rek'	=> $filter_kode_rek,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

		$results = $this->model_keuangan_coa->getAllCategories($data);
		$product_total = $this->model_keuangan_coa->getTotalCategories($data);
		//echo count($results);
		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('keuangan/coa/update', 'token=' . $this->session->data['token'] . '&category_id=' . $result['category_id'].$url, 'SSL')
			);

			$this->data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'parent_id'        => $result['parent_id'],
				'type'	=> $result['type'],
				'saldo'	=> $this->currency->format($result['saldo']),
				'kode_rek'        => $result['kode_rek'],
				'sort_order'  => $result['sort_order'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['category_id'], $this->request->post['selected']),
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
		if (isset($this->request->get['filter_kode_rek'])) {
			$url .= '&filter_kode_rek=' . urlencode(html_entity_decode($this->request->get['filter_kode_rek'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('keuangan/coa', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_kode_rek'] = $filter_kode_rek;
		$this->data['filter_type'] = $filter_type;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'keuangan/coa_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getForm() {


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

		if (isset($this->error['kode_rek'])) {
			$this->data['error_rek'] = $this->error['kode_rek'];
		} else {
			$this->data['error_rek'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_kode_rek'])) {
			$url .= '&filter_kode_rek=' . urlencode(html_entity_decode($this->request->get['filter_kode_rek'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}



		if (!isset($this->request->get['category_id'])) {
			$this->data['action'] = $this->url->link('keuangan/coa/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('keuangan/coa/update', 'token=' . $this->session->data['token'].$url . '&category_id=' . $this->request->get['category_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('keuangan/coa', 'token=' . $this->session->data['token'].$url, 'SSL');

		if (isset($this->request->get['category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$category_info = $this->model_keuangan_coa->getCategory($this->request->get['category_id']);
    	}

		$this->data['token'] = $this->session->data['token'];

		$categories = $this->model_keuangan_coa->getCategories(0);

		// Remove own id from list
		if (!empty($category_info)) {
			foreach ($categories as $key => $category) {
				if ($category['category_id'] == $category_info['category_id']) {
					unset($categories[$key]);
				}
			}
		}

		$this->data['categories'] = $categories;

		if (isset($this->request->post['parent_id'])) {
			$this->data['parent_id'] = $this->request->post['parent_id'];
		} elseif (!empty($category_info)) {
			$this->data['parent_id'] = $category_info['parent_id'];
		} else {
			$this->data['parent_id'] = 0;
		}


		if (isset($this->request->post['kode_rek'])) {
			$this->data['kode_rek'] = $this->request->post['keywkode_rekord'];
		} elseif (!empty($category_info)) {
			$this->data['kode_rek'] = $category_info['kode_rek'];
		} else {
			$this->data['kode_rek'] = '';
		}



		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($category_info)) {
			$this->data['sort_order'] = $category_info['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}

		if (isset($this->request->post['type'])) {
			$this->data['type'] = $this->request->post['type'];
		} elseif (!empty($category_info)) {
			$this->data['type'] = $category_info['type'];
		} else {
			$this->data['type'] = 1;
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($category_info)) {
			$this->data['status'] = $category_info['status'];
		} else {
			$this->data['status'] = 1;
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (!empty($category_info)) {
			$this->data['name'] = $category_info['name'];
		} else {
			$this->data['name'] = '';
		}



		$this->template = 'keuangan/coa_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'keuangan/coa')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu COA';
		}

		if ((utf8_strlen($this->request->post['name']) < 2) || (utf8_strlen($this->request->post['name']) > 255)) {
			$this->error['name'] = 'Nama COA harus diisi.';
		}
		if ((utf8_strlen($this->request->post['kode_rek']) < 2) || (utf8_strlen($this->request->post['kode_rek']) > 255)) {
			$this->error['kode_rek'] = 'Kode rekening COA harus diisi.';
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
		/*if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu COA';
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
			$this->load->model('keuangan/coa');

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

			$results = $this->model_keuangan_coa->getAllCategories($data);

			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result['category_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}
	public function autocompletes() {
		$json = array();

		//if (isset($this->request->get['filter_name']) ) {
			$this->load->model('keuangan/coa');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = null;
			}

			if (isset($this->request->get['p'])) {
				$filter_parent= $this->request->get['p'];
			} else {
				$filter_parent = null;
			}

			if (isset($this->request->get['t'])) {
				$filter_type= $this->request->get['t'];
			} else {
				$filter_type = null;
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'filter_name'	  => $filter_name,
				'filter_parent'	=> $filter_parent,
				'filter_type'	=> $filter_type,
				'start'               => 0,
				'limit'               => $limit
			);

			$results = $this->model_keuangan_coa->getAllCategories($data);
			/*$json[]=array(
				'id'	=> 0,
				'text'	=>'Semua COA'
			);*/
			foreach ($results as $result) {
				if($result['parent_id'] == 0){
					$text=$result['kode_rek'].' '.$result['name'];
				}else{
					$text=$result['kode_rek'].' '.$result['name'];
				}
				$json[] = array(
					'id' => $result['kode_rek'],
					'text'       => $text,

				);
			}
		//}

		$this->response->setOutput(json_encode($json));
	}

}
?>
