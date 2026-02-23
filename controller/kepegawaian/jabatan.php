<?php
class ControllerKepegawaianJabatan extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/atk');

		$this->document->setTitle($this->language->get('Master Data Jabatan'));

		$this->load->model('kepegawaian/jabatan');

		$this->getList();
  	}

  	public function insert() {
    	$this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Master Data Jabatan'));

		$this->load->model('kepegawaian/jabatan');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_kepegawaian_jabatan->addJabatan($this->request->post);
	  		$this->session->data['success'] = "Data Jabatan Berhasil Diperbarui";

			$url = '';

			if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }



			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('kepegawaian/jabatan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

    public function update() {
    	$this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Master Data Jabatan'));

		$this->load->model('kepegawaian/jabatan');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_kepegawaian_jabatan->editJabatan($this->request->get['jabatan_id'],$this->request->post);
	  		$this->session->data['success'] = "Data Jabatan Berhasil Diperbarui";

			$url = '';

			if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }



			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('kepegawaian/jabatan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}



  	public function delete() {
    	$this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Master Data Jabatan'));

		$this->load->model('kepegawaian/jabatan');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $jabatan_id) {
				$this->model_kepegawaian_jabatan->deleteJabatan($jabatan_id);
	  		}

			$this->session->data['success'] = "Data Jabatan Berhasil Diperbarui";

			$url = '';

			if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }



			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('kepegawaian/jabatan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('Master Data Jabatan'),
			'href'      => $this->url->link('kepegawaian/jabatan', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = $this->url->link('kepegawaian/jabatan/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('kepegawaian/jabatan/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['jabatans'] = array();

		$data = array(
			'filter_name'	  => $filter_name,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);


		$product_total = $this->model_kepegawaian_jabatan->getTotalJabatan($data);

		$results = $this->model_kepegawaian_jabatan->getJabatans($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('kepegawaian/jabatan/update', 'token=' . $this->session->data['token'] . '&jabatan_id=' . $result['jabatan_id'] . $url, 'SSL')
			);




      		$this->data['jabatans'][] = array(
				'jabatan_id' => $result['jabatan_id'],
				'nama'       => $result['nama'],
				'tunjangan'       => $this->currency->format($result['tunjangan']),
				'selected'   => isset($this->request->post['selected']) && in_array($result['jabatan_id'], $this->request->post['selected']),
				'action'     => $action
			);
    	}

		$this->data['heading_title'] = $this->language->get('Master Data Jabatan');



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


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}



		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('kepegawaian/jabatan', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'kepegawaian/jabatan_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');



 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}



		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('kepegawaian/jabatan', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['jabatan_id'])) {
			$this->data['action'] = $this->url->link('kepegawaian/jabatan/insert', 'token=' . $this->session->data['token']. $url, 'SSL');

		} else {
			$this->data['action'] = $this->url->link('kepegawaian/jabatan/update', 'token=' . $this->session->data['token'] . '&jabatan_id=' . $this->request->get['jabatan_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('kepegawaian/jabatan', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['jabatan_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
	      		$product_info = $this->model_kepegawaian_jabatan->getJabatan($this->request->get['jabatan_id']);
	    	}

		$this->data['jabatan']=array();
		if($this->request->server['REQUEST_METHOD'] == 'POST'){
            $this->data['jabatan']= $this->request->post;
        }
        else if(!empty($product_info)){
			$this->data['jabatan']=$product_info;
		}else{
            $this->data['jabatan']=array();
        }

		$this->data['token'] = $this->session->data['token'];



		$this->template = 'kepegawaian/jabatan_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'kepegawaian/jabatan')) {
      		$this->error['warning'] = "Anda tidak memiliki hak untuk memodifikasi menu jabatan";
    	}



    	if ((utf8_strlen($this->request->post['nama']) < 1) ) {
      		$this->error['nama'] = $this->language->get('Nama jabatan harus diisi');
    	}

        if($this->request->post['tunjangan'] < 0){
            $this->error['tunjangan'] = $this->language->get('Nilai tunjangan harus lebih dari sama dengan 0.');
        }

		if ($this->error && !isset($this->error['warning'])) {
            $warning = 'Peringatan: Mohon cek error berikut. <br>';
            foreach($this->error as $e){
                $warning .= $e.'<br>';
            }
			$this->error['warning'] = $warning;
		}

    	if (!$this->error) {
			return true;
    	} else {
      		return false;
    	}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'kepegawaian/jabatan')) {
      		$this->error['warning'] = "Anda tidak memiliki hak untuk memodifikasi menu jabatan";
    	}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}


}
?>
