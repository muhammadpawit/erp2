<?php
class ControllerMarketplaceMarketplace extends Controller {
	private $error = array();
  	public function index() {
		$this->load->language('catalog/product');

		$this->document->setTitle('Master Data Marketplace');

		$this->load->model('catalog/product');
		$this->load->model('marketplace/marketplace');
		$this->getList();
  	}

  	public function insert() {
    	$this->load->language('catalog/product');
    	$this->document->setTitle('Master Data Produk');
		$this->load->model('catalog/product');
		$this->load->model('marketplace/marketplace');
    	//if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			$this->model_marketplace_marketplace->simpan($this->request->post);

			$this->session->data['success'] ="Sukses: Data berhasil diupdate!";

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
			}

				if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

	        if (isset($this->request->get['filter_urutkan'])) {
				$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('marketplace/marketplace', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('catalog/product');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');
		$this->load->model('marketplace/marketplace');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			$this->model_marketplace_marketplace->edit($this->request->get['id'], $this->request->post);

			$this->session->data['success'] ="Sukses: Data berhasil diupdate!";

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
			}

				if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

	        if (isset($this->request->get['filter_urutkan'])) {
				$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('marketplace/marketplace', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getForm();
  	}

  	public function hapus(){
  		$this->load->model('marketplace/marketplace');
  		if(isset($this->request->get['id'])){
  			$id=$this->request->get['id'];
  			$this->model_marketplace_marketplace->hapus($id);
  			echo 1;
  		}else{
  			echo 0;
  		}
  	}

  	private function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$filter_category_id = null;
		}



		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$filter_urutkan = $this->request->get['filter_urutkan'];
		} else {
			$filter_urutkan = '3';
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

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

        if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}



		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}



		$this->data['insert'] = $this->url->link('marketplace/marketplace/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['copy'] = $this->url->link('marketplace/marketplace/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('marketplace/marketplace/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['exporttoexcel'] = $this->url->link('marketplace/marketplace/exporttoexcel', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['products'] = array();
		$this->load->model('catalog/satuan');

		$data = array(
			'filter_name'	  => $filter_name,
			// 'filter_category_id'	=> $filter_category_id,
			// 'filter_status'   => $filter_status,
			// 'filter_urutkan'   => $filter_urutkan,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);


		$product_total = $this->model_marketplace_marketplace->gettotal($data);

		$results = $this->model_marketplace_marketplace->getall($data);

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),9);

		$freestok=0;
		$no=1;
		foreach ($results as $result) {
			
			if($this->user->getUsername()=="pawits"){
				echo $this->data['freestok'];exit;
			}
			$action = array();
    		$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('marketplace/marketplace/update', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
			);
			
      		$this->data['products'][] = array(
				'no' => $no,
				'id' => $result['id'],
				'nama'       => $result['nama'],
				'action'     => $action
			);

			$no++;
    	}


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

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

        if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

        if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
            }

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

        if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_category_id'] = $filter_category_id;
		$this->data['filter_urutkan'] = $filter_urutkan;
		$this->data['filter_status'] = $filter_status;

			$this->load->model('catalog/category');

		$this->data['categories']=$this->model_catalog_category->getAllCategories();

		$this->template = 'marketplace/marketplace_list.tpl';
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

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
		}

			if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

        if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		if (!isset($this->request->get['id'])) {
			$this->data['action'] = $this->url->link('marketplace/marketplace/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('marketplace/marketplace/update', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('marketplace/marketplace', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$product_info = $this->model_marketplace_marketplace->getdetail($this->request->get['id']);
    	}

		$this->data['token'] = $this->session->data['token'];



		if (isset($this->request->post['name'])) {
      		$this->data['nama'] = $this->request->post['name'];
    	} elseif (!empty($product_info)) {
			$this->data['nama'] = $product_info['nama'];
		} else {
      		$this->data['nama'] = '';
    	}

		$this->template = 'marketplace/marketplace_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = 'Anda tidak diijinkan untuk memodifikasi menu produk.';
    	}

			if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 255)) {
				$this->error['name'] = 'Nama produk tidak boleh dikosongkan.';
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
}
?>
