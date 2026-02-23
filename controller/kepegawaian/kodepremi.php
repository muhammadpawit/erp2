<?php
class ControllerKepegawaianKodepremi extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Kode Premi');

		$this->load->model('kepegawaian/kodepremi');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Kode Premi');

		$this->load->model('kepegawaian/kodepremi');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_kepegawaian_kodepremi->addOptions($this->request->post);

			$this->session->data['success'] = 'Data Kode Premi berhasil ditambahkan.';
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('kepegawaian/kodepremi', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Kode Premi');

		$this->load->model('kepegawaian/kodepremi');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_kepegawaian_kodepremi->editOptions($this->request->get['id'], $this->request->post);

			$this->session->data['success'] = 'Data Kode Premi berhasil diperbarui';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('kepegawaian/kodepremi', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Kode Premi');

		$this->load->model('kepegawaian/kodepremi');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $id) {
				$this->model_kepegawaian_kodepremi->deleteOptions($id);
			}

			$this->session->data['success'] = 'Data Kode Premi berhasil dihapus';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			$this->redirect($this->url->link('kepegawaian/kodepremi', 'token=' . $this->session->data['token'].$url, 'SSL'));
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

   	$this->data['insert'] = $this->url->link('kepegawaian/kodepremi/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('kepegawaian/kodepremi/delete', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['options'] = array();

		$data = array(
			'filter_name'	  => $filter_name,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

		$results = $this->model_kepegawaian_kodepremi->getOptions($data);
		$product_total = $this->model_kepegawaian_kodepremi->totalOptions($data);

		foreach ($results as $result) {
			$action = array();

			/*$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('kepegawaian/kodepremi/update', 'token=' . $this->session->data['token'] . '&id=' . $result['id'], 'SSL')
			);*/



		//	$cek= $this->model_kepegawaian_kodepremi->cekOption($result['product_options_id']);

			$this->data['options'][] = array(
				'id' => $result['id'],
				'kelompok'        => $this->currency->format($result['kelompok']),
				'kelompok2'        => $this->currency->format($result['kelompok2']),
				'kelompok3'        => $this->currency->format($result['kelompok3']),
				'kelompok4'        => $this->currency->format($result['kelompok4']),
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
		$pagination->url = $this->url->link('kepegawaian/kodepremi', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'kepegawaian/kodepremi_list.tpl';
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
			$this->data['action'] = $this->url->link('kepegawaian/kodepremi/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('kepegawaian/kodepremi/update', 'token=' . $this->session->data['token'].$url. '&id=' . $this->request->get['id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('kepegawaian/kodepremi', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$option_info = $this->model_kepegawaian_kodepremi->getOption($this->request->get['id']);
    	}

		$this->data['token'] = $this->session->data['token'];


		if (isset($this->request->post['kelompok'])) {
			$this->data['kelompok'] = $this->request->post['kelompok'];
		} elseif (!empty($option_info)) {
			$this->data['kelompok'] = $option_info['kelompok'];
		} else {
			$this->data['kelompok'] = '';
		}

		if (isset($this->request->post['kelompok2'])) {
			$this->data['kelompok2'] = $this->request->post['kelompok2'];
		} elseif (!empty($option_info)) {
			$this->data['kelompok2'] = $option_info['kelompok2'];
		} else {
			$this->data['kelompok2'] = '';
		}

		if (isset($this->request->post['kelompok3'])) {
			$this->data['kelompok3'] = $this->request->post['kelompok3'];
		} elseif (!empty($option_info)) {
			$this->data['kelompok3'] = $option_info['kelompok3'];
		} else {
			$this->data['kelompok3'] = '';
		}

		if (isset($this->request->post['kelompok4'])) {
			$this->data['kelompok4'] = $this->request->post['kelompok4'];
		} elseif (!empty($option_info)) {
			$this->data['kelompok4'] = $option_info['kelompok4'];
		} else {
			$this->data['kelompok4'] = '';
		}


		$this->template = 'kepegawaian/kodepremi_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		/*if (!$this->user->hasPermission('modify', 'kepegawaian/kodepremi')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Kode Premi';
		}*/

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
		/*if (!$this->user->hasPermission('modify', 'kepegawaian/kodepremi')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Kode Premi';
		}*/

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	/*public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) ) {
			$this->load->model('kepegawaian/kodepremi');

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

			$results = $this->model_kepegawaian_kodepremi->getOptions($data);

			foreach ($results as $result) {
				$json[] = array(
					'product_options_id' => $result['product_options_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}*/
}
?>
