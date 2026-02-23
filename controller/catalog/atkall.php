<?php
class ControllerCatalogAtkall extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/atk');

		$this->document->setTitle($this->language->get('Master Data ATK'));

		$this->load->model('catalog/atk');

		$this->getList();
  	}

  	public function insert() {
    	$this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Master Data ATK'));

		$this->load->model('catalog/atk');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_atk->addAtkList($this->request->post);

			$this->session->data['success'] = "Data ATK berhasil diperbarui.";

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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

			$this->redirect($this->url->link('catalog/atkall', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Master Data ATK'));

		$this->load->model('catalog/atk');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_atk->editAtkList($this->request->get['atk_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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

			$this->redirect($this->url->link('catalog/atkall', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Master Data ATK'));

		$this->load->model('catalog/atk');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_atk->deleteAtkList($product_id);
	  		}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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

			$this->redirect($this->url->link('catalog/atkall', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getList();
  	}



  	private function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}



		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'nama';
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

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('Master Data ATK'),
			'href'      => $this->url->link('catalog/atkall', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = $this->url->link('catalog/atkall/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['copy'] = $this->url->link('catalog/atkall/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/atkall/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['products'] = array();

		$data = array(
			'filter_name'	  => $filter_name,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);


		$product_total = $this->model_catalog_atk->getTotalAtklist($data);

		$results = $this->model_catalog_atk->getAtkLists($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/atkall/update', 'token=' . $this->session->data['token'] . '&atk_id=' . $result['atk_id'] . $url, 'SSL')


			);
			/*$action[] = array(
				'text' => $this->language->get('Input Stok Awal'),
				'href' => $this->url->link('catalog/atk/insert', 'token=' . $this->session->data['token'] . '&atk_id=' . $result['atk_id'] . $url, 'SSL')


			);

			*/

      		$this->data['products'][] = array(
				'atk_id' => $result['atk_id'],
				'nama'       => $result['nama'],
				'qty'	=> $result['qty'],
				'keterangan'	=> html_entity_decode($result['keterangan']),
				'selected'   => isset($this->request->post['selected']) && in_array($result['atk_id'], $this->request->post['selected']),
				'action'     => $action
			);
    	}

		$this->data['heading_title'] = $this->language->get('Master Data ATK');



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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_name'] = $this->url->link('catalog/atkall', 'token=' . $this->session->data['token'] . '&sort=nama' . $url, 'SSL');
		$this->data['sort_order'] = $this->url->link('catalog/atkall', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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
		$pagination->url = $this->url->link('catalog/atkall', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/atklist_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('Master Data ATK');



 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
		}



		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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



		if (!isset($this->request->get['atk_id'])) {
			$this->data['action'] = $this->url->link('catalog/atkall/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/atkall/update', 'token=' . $this->session->data['token'] . '&atk_id=' . $this->request->get['atk_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/atkall', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['atk_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$product_info = $this->model_catalog_atk->getAtklist($this->request->get['atk_id']);
    	}

		$this->data['token'] = $this->session->data['token'];



		if (isset($this->request->post['nama'])) {
      		$this->data['nama'] = $this->request->post['nama'];
    	} elseif (!empty($product_info)) {
			$this->data['nama'] = $product_info['nama'];
		} else {
      		$this->data['nama'] = '';
    	}

		if (isset($this->request->post['keterangan'])) {
      		$this->data['keterangan'] = $this->request->post['keterangan'];
    	} elseif (!empty($product_info)) {
			$this->data['keterangan'] = $product_info['keterangan'];
		} else {
      		$this->data['keterangan'] = '';
    	}



		$this->template = 'catalog/atkall_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/atkall')) {
      		$this->error['warning'] = 'Anda tidak diijinkan memodifikasi menu ATK';
    	}



    	if ((utf8_strlen($this->request->post['nama']) < 1) || (utf8_strlen($this->request->post['nama']) > 64)) {
      		$this->error['nama'] = $this->language->get('Nama tidak boleh kosong');
    	}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = "Mohon cek kembali form anda.";
		}

    	if (!$this->error) {
			return true;
    	} else {
      		return false;
    	}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/atkall')) {
      		$this->error['warning'] = 'Anda tidak diijinkan memodifikasi menu ATK';
    	}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}



	public function autocomplete() {
		$results = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/atk');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'filter_name'         => $filter_name,
				'start'               => 0,
				'limit'               => $limit
			);

			$results = $this->model_catalog_atk->getAtkLists($data);




		}
		$this->response->setOutput(json_encode($results));
	}
}
?>
