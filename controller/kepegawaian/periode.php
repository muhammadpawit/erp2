<?php
class ControllerKepegawaianPeriode extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/atk');

		$this->document->setTitle($this->language->get('Master Data Periode'));

		$this->load->model('kepegawaian/periode');

		$this->getList();
  	}

  	public function insert() {
    	$this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Master Data Periode'));

		$this->load->model('kepegawaian/periode');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_kepegawaian_periode->addPeriode($this->request->post);
	  		$this->session->data['success'] = "Data periode berhasil diperbarui";

			$url = '';

			if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_date_start'])) {
                $url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
            }
            if (isset($this->request->get['filter_date_end'])) {
                $url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
            }

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

    public function update() {
    	$this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Master Data Periode'));

		$this->load->model('kepegawaian/periode');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_kepegawaian_periode->editPeriode($this->request->get['periode_id'],$this->request->post);
	  		$this->session->data['success'] = "Data periode berhasil diperbarui";

			$url = '';

			if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_date_start'])) {
                $url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
            }
            if (isset($this->request->get['filter_date_end'])) {
                $url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
            }

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}



  	public function delete() {
    	$this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Master Data Periode'));

		$this->load->model('kepegawaian/periode');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $periode_id) {
				$this->model_kepegawaian_periode->deletePeriode($periode_id);
	  		}

			$this->session->data['success'] = "Data periode berhasil diperbarui";

			$url = '';

			if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_date_start'])) {
                $url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
            }
            if (isset($this->request->get['filter_date_end'])) {
                $url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
            }

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getList();
  	}



  	private function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

        if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = "";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = "";
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
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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
       		'text'      => $this->language->get('Master Data Periode'),
			'href'      => $this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = $this->url->link('kepegawaian/periode/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('kepegawaian/periode/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['periodes'] = array();

		$data = array(
			'filter_name'	  => $filter_name,
			'filter_date_start'	  => $filter_date_start,
			'filter_date_end'	  => $filter_date_end,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);


		$product_total = $this->model_kepegawaian_periode->getTotalPeriode($data);

		$results = $this->model_kepegawaian_periode->getPeriodes($data);
		$cek = $this->model_kepegawaian_periode->cekAktif();

		foreach ($results as $result) {
			$action = array();
            if($result['status_periode'] == 0 ){
                $action[] = array(
                    'text' => 'Aktivasi',
                    'href' => $this->url->link('kepegawaian/periode/aktivasiperiode', 'token=' . $this->session->data['token'] . '&periode_id=' . $result['periode_id'] . $url, 'SSL')
                );
            }
            if($result['status_periode'] == 1){
                $action[] = array(
                    'text' => 'Deaktif',
                    'href' => $this->url->link('kepegawaian/periode/deaktivasiperiode', 'token=' . $this->session->data['token'] . '&periode_id=' . $result['periode_id'] . $url, 'SSL')
                );
            }
    /*  $action[] = array(
				'text' => 'Absensi SPG',
				'href' => $this->url->link('kepegawaian/periode/absensi', 'token=' . $this->session->data['token'] . '&periode_id=' . $result['periode_id'] . $url, 'SSL')
			);*/
        $action[] = array(
				'text' => 'Hari Libur',
				'href' => $this->url->link('kepegawaian/periode/libur', 'token=' . $this->session->data['token'] . '&periode_id=' . $result['periode_id'] . $url, 'SSL')
			);

            $action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('kepegawaian/periode/update', 'token=' . $this->session->data['token'] . '&periode_id=' . $result['periode_id'] . $url, 'SSL')
			);

      		$this->data['periodes'][] = array(
				'periode_id' => $result['periode_id'],
				'nama'       => $result['nama'],
				'status'       => $result['status_periode'] == 1?'Aktif':'Tidak Aktif',
				'tgl_awal'       => $result['tgl_awal'],
				'tgl_selesai'       => $result['tgl_selesai'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['periode_id'], $this->request->post['selected']),
				'action'     => $action
			);
    	}

		$this->data['heading_title'] = $this->language->get('Master Data Periode');



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

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;


		$this->template = 'kepegawaian/periode_list.tpl';
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

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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
			'href'      => $this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['periode_id'])) {
			$this->data['action'] = $this->url->link('kepegawaian/periode/insert', 'token=' . $this->session->data['token']. $url, 'SSL');

		} else {
			$this->data['action'] = $this->url->link('kepegawaian/periode/update', 'token=' . $this->session->data['token'] . '&periode_id=' . $this->request->get['periode_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['periode_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
	      		$product_info = $this->model_kepegawaian_periode->getPeriode($this->request->get['periode_id']);
	    	}

		$this->data['periode']=array();
		if($this->request->server['REQUEST_METHOD'] == 'POST'){
            $this->data['periode']= $this->request->post;
        }
        else if(!empty($product_info)){
			$this->data['periode']=$product_info;
		}else{
            $this->data['periode']=array();
        }

		$this->data['token'] = $this->session->data['token'];



		$this->template = 'kepegawaian/periode_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function validateForm() {
    	/*if (!$this->user->hasPermission('modify', 'kepegawaian/periode')) {
      		$this->error['warning'] = "Anda tidak memiliki hak untuk memodifikasi menu periode";
    	}*/



    	if ((utf8_strlen($this->request->post['nama']) < 1) ) {
      		$this->error['nama'] = $this->language->get('Nama periode harus diisi');
    	}

        if(strtotime($this->request->post['tgl_awal']) > strtotime($this->request->post['tgl_selesai'])){
            $this->error['tanggal'] = $this->language->get('Tanggal awal periode harus kurang dari tanggal selesai periode');
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
    	/*if (!$this->user->hasPermission('modify', 'kepegawaian/periode')) {
      		$this->error['warning'] = "Anda tidak memiliki hak untuk memodifikasi menu periode";
    	}*/

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

  	public function libur(){
        $this->document->setTitle($this->language->get('Master Data Periode'));
        if(!isset($this->request->get['periode_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if(empty($this->request->get['periode_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

        if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = "";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = "";
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['periode_id'])) {
			$url .= '&periode_id=' .$this->request->get['periode_id'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Home',
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('Master Data Periode'),
			'href'      => $this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = $this->url->link('kepegawaian/periode/insertlibur', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('kepegawaian/periode/deletelibur', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['cancel'] = $this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->data['liburs'] = array();
        $this->load->model('kepegawaian/libur');
    	$results = $this->model_kepegawaian_libur->getLiburByPeriode($this->request->get['periode_id']);

		foreach ($results as $result) {

      		$this->data['liburs'][] = array(
				'periode_id' => $result['periode_id'],
				'libur_id'       => $result['libur_id'],
				'keterangan'       => $result['keterangan'],
				'tanggal'       => $result['tanggal'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['libur_id'], $this->request->post['selected']),
				'action'     => $action
			);
        }

        $this->data['heading_title'] = $this->language->get('Master Data Periode');



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
		if (isset($this->request->get['periode_id'])) {
			$url .= '&periode_id=' .$this->request->get['periode_id'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->template = 'kepegawaian/libur_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
    }

    public function insertlibur() {
    	$this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Hari Libur'));

		$this->load->model('kepegawaian/libur');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormLibur()) {
			$this->model_kepegawaian_libur->addLibur($this->request->post);
	  		$this->session->data['success'] = "Data hari libur berhasil diperbarui";

			$url = '';
			if (isset($this->request->get['periode_id'])) {
                $url .= '&periode_id=' . urlencode(html_entity_decode($this->request->get['periode_id'], ENT_QUOTES, 'UTF-8'));
            }
			if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_date_start'])) {
                $url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
            }
            if (isset($this->request->get['filter_date_end'])) {
                $url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
            }

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('kepegawaian/periode/libur', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getFormLibur();
  	}

    private function getFormLibur() {
        $this->document->setTitle($this->language->get('Master Data Periode'));
    	$this->data['heading_title'] = $this->language->get('heading_title');



 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}



		$url = '';
        if (isset($this->request->get['periode_id'])) {
			$url .= '&periode_id=' . urlencode(html_entity_decode($this->request->get['periode_id'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Home',
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['periode_id'])) {
			$this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));

		} else {
			$this->data['action'] = $this->url->link('kepegawaian/periode/insertlibur', 'token=' . $this->session->data['token'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('kepegawaian/periode/libur', 'token=' . $this->session->data['token'] . $url, 'SSL');


		$this->data['libur']=array();
		if($this->request->server['REQUEST_METHOD'] == 'POST'){
            $this->data['libur']= $this->request->post;
        }else{
            $this->data['libur']=array();
        }

		$this->data['token'] = $this->session->data['token'];



		$this->template = 'kepegawaian/libur_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}
    private function validateFormLibur() {
    	/*if (!$this->user->hasPermission('modify', 'kepegawaian/periode')) {
      		$this->error['warning'] = "Anda tidak memiliki hak untuk memodifikasi menu libur";
    	}*/



    	if ((utf8_strlen($this->request->post['keterangan']) < 1) ) {
      		$this->error['keterangan'] = $this->language->get('Keterangan harus diisi');
    	}

        $periode_id=$this->request->post['periode_id'];
        $this->load->model('kepegawaian/periode');
        $this->load->model('kepegawaian/libur');
        $periode=$this->model_kepegawaian_periode->getPeriode($periode_id);
        if(empty($periode)){
            $this->error['periode'] = 'Periode tidak valid';
        }else{
            $tanggal = strtotime($this->request->post['tanggal']);
            if($tanggal < strtotime($periode['tgl_awal']) | $tanggal > strtotime($periode['tgl_selesai'])){
                 $this->error['tanggal'] = 'Tanggal kurang dari atau melebihi periode.';
            }
        }

        $cek=$this->model_kepegawaian_libur->getLiburByTanggal($this->request->post['tanggal']);
        if(!empty($cek)){
            $this->error['tanggal'] = 'Duplikasi tanggal.';
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

    public function jadwal(){
      $this->document->setTitle($this->language->get('Master Data Jadwal'));
      $this->load->model('pamerantoko/toko');
      if(!isset($this->request->get['periode_id'])){
          $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
      }
      if(empty($this->request->get['periode_id'])){
          $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
      }
      if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = null;
			}

      if (isset($this->request->get['filter_date_start'])) {
				$filter_date_start = $this->request->get['filter_date_start'];
			} else {
				$filter_date_start = "";
			}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = "";
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

    if (isset($this->request->get['page2'])) {
			$page2 = $this->request->get['page2'];
		} else {
			$page2 = 1;
		}

		$url = '';
		if (isset($this->request->get['periode_id'])) {
			$url .= '&periode_id=' .$this->request->get['periode_id'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
        if (isset($this->request->get['page2'])) {
			$url .= '&page2=' . $this->request->get['page2'];
		}

  	$this->data['cancel'] = $this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL');
    $this->data['products'] = array();

		$data = array(
			'start'           => ($page2 - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

    $this->data['products'] = array();
    $this->load->model('pamerantoko/toko');
        $product_total = $this->model_pamerantoko_toko->getTotalPameran($data);
    	$results = $this->model_pamerantoko_toko->getPamerans($data);

		foreach ($results as $result) {

      		$action = array();


			$action[] = array(
				'text' => 'Tampil Jadwal',
				'href' => $this->url->link('kepegawaian/periode/jadwaltoko', 'token=' . $this->session->data['token'] . '&toko_id=' . $result['pameran_id'] . $url, 'SSL')
			);
            $this->data['products'][] = array(
				'pameran_id' => $result['pameran_id'],
				'kode' => $result['kode'],
				'lokasi' => $result['lokasi'],

				'selected'   => isset($this->request->post['selected']) && in_array($result['pameran_id'], $this->request->post['selected']),
				'action'     => $action
			);

        }

        $this->data['heading_title'] = $this->language->get('Jadwal SPG');



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
		if (isset($this->request->get['periode_id'])) {
			$url .= '&periode_id=' .$this->request->get['periode_id'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
        $pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page2;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('kepegawaian/periode/jadwal', 'token=' . $this->session->data['token'] . $url . '&page2={page2}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'kepegawaian/toko_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
    }
    public function deleteLibur() {
    	$this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Master Data Periode'));

		$this->load->model('kepegawaian/libur');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $libur_id) {
				$this->model_kepegawaian_libur->deleteLibur($libur_id);
	  		}

			$this->session->data['success'] = "Data hari libur berhasil diperbarui";

			$url = '';
		if (isset($this->request->get['periode_id'])) {
			$url .= '&periode_id=' .$this->request->get['periode_id'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

			$this->redirect($this->url->link('kepegawaian/periode/libur', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->libur();
  	}
    public function aktivasiperiode(){
        $this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Master Data Periode'));

		$this->load->model('kepegawaian/periode');
        if(isset($this->request->get['periode_id'])){
            if(!empty($this->request->get['periode_id'])){
                $this->model_kepegawaian_periode->aktivasiPeriode($this->request->get['periode_id']);
                $this->session->data['success'] = "Periode berhasil diaktifkan";
                $url = '';
                if (isset($this->request->get['periode_id'])) {
                    $url .= '&periode_id=' .$this->request->get['periode_id'];
                }
                if (isset($this->request->get['filter_name'])) {
                    $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
                }
                if (isset($this->request->get['filter_date_start'])) {
                    $url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
                }
                if (isset($this->request->get['filter_date_end'])) {
                    $url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
                    $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                }
            }
        }



    	$this->getList();
    }
    public function deaktivasiperiode(){
        $this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Master Data Periode'));

		$this->load->model('kepegawaian/periode');
        if(isset($this->request->get['periode_id'])){
            if(!empty($this->request->get['periode_id'])){
                $this->model_kepegawaian_periode->deaktivasiPeriode($this->request->get['periode_id']);
                $this->session->data['success'] = "Periode berhasil diaktifkan";
                $url = '';
                if (isset($this->request->get['periode_id'])) {
                    $url .= '&periode_id=' .$this->request->get['periode_id'];
                }
                if (isset($this->request->get['filter_name'])) {
                    $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
                }
                if (isset($this->request->get['filter_date_start'])) {
                    $url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
                }
                if (isset($this->request->get['filter_date_end'])) {
                    $url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
                    $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
                }
            }
        }



    	$this->getList();
    }
    public function jadwaltoko(){
        $this->document->setTitle($this->language->get('Jadwal SPG'));
        if(!isset($this->request->get['periode_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if(empty($this->request->get['periode_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if(!isset($this->request->get['toko_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if(empty($this->request->get['toko_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

        if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = "";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = "";
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['periode_id'])) {
			$url .= '&periode_id=' .$this->request->get['periode_id'];
		}
        if (isset($this->request->get['toko_id'])) {
			$url .= '&toko_id=' .$this->request->get['toko_id'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Home',
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('Master Data Periode'),
			'href'      => $this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = $this->url->link('kepegawaian/periode/insertjadwal', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['cetak'] = $this->url->link('kepegawaian/periode/cetakjadwal', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('kepegawaian/periode/deletejadwal', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['cancel'] = $this->url->link('kepegawaian/periode/jadwal', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->data['liburs'] = array();
        $this->load->model('kepegawaian/periode');
        $this->load->model('pamerantoko/toko');
        $this->load->model('kepegawaian/shift');
        $periode=$this->model_kepegawaian_periode->getPeriode($this->request->get['periode_id']);
        $this->data['periode']=$periode;
        if(empty($periode)){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $shift=$this->model_kepegawaian_shift->getShiftByToko($this->request->get['toko_id']);
        $this->data['shifts']=$shift;

        $toko=$this->model_pamerantoko_toko->getPameran($this->request->get['toko_id']);
        $this->data['toko']=$toko;
        $this->load->model('kepegawaian/jadwal');
    	/* $results = $this->model_kepegawaian_jadwal->geJadwalByPeriode($this->request->get['periode_id'],$this->request->get['toko_id']);

		foreach ($results as $result) {

      		$this->data['liburs'][] = array(
				'periode_id' => $result['periode_id'],
				'libur_id'       => $result['libur_id'],
				'keterangan'       => $result['keterangan'],
				'tanggal'       => $result['tanggal'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['libur_id'], $this->request->post['selected']),
				'action'     => $action
			);
        } */

        $this->data['heading_title'] = $this->language->get('Master Data Periode');



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
		if (isset($this->request->get['periode_id'])) {
			$url .= '&periode_id=' .$this->request->get['periode_id'];
		}
        if (isset($this->request->get['toko_id'])) {
			$url .= '&toko_id=' .$this->request->get['toko_id'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->template = 'kepegawaian/jadwal_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
    }
		public function insertjadwal() {
		  $this->load->language('catalog/atk');

		  $this->document->setTitle($this->language->get('Jadwal SPG Toko'));

			$this->load->model('kepegawaian/libur');

		  if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormJadwal()) {
					$this->model_kepegawaian_jadwal->addJadwal($this->request->post);
				    $this->session->data['success'] = "Data jadwal berhasil diperbarui";

					$url = '';
					if (isset($this->request->get['periode_id'])) {
		                $url .= '&periode_id=' . urlencode(html_entity_decode($this->request->get['periode_id'], ENT_QUOTES, 'UTF-8'));
		            }
		            if (isset($this->request->get['toko_id'])) {
		                $url .= '&toko_id=' . urlencode(html_entity_decode($this->request->get['toko_id'], ENT_QUOTES, 'UTF-8'));
		            }
					if (isset($this->request->get['filter_name'])) {
		                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		            }

		            if (isset($this->request->get['filter_date_start'])) {
		                $url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		            }
		            if (isset($this->request->get['filter_date_end'])) {
		                $url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		            }

					if (isset($this->request->get['page'])) {
						$url .= '&page=' . $this->request->get['page'];
					}

					$this->redirect($this->url->link('kepegawaian/periode/jadwaltoko', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		    	}

		    	$this->getFormJadwal();
		  	}

		    private function getFormJadwal() {
		        $this->document->setTitle($this->language->get('Jadwal SPG Toko'));
		    	$this->data['heading_title'] = $this->language->get('heading_title');



		 		if (isset($this->error['warning'])) {
					$this->data['error_warning'] = $this->error['warning'];
				} else {
					$this->data['error_warning'] = '';
				}



				$url = '';
		        if (isset($this->request->get['periode_id'])) {
					$url .= '&periode_id=' . urlencode(html_entity_decode($this->request->get['periode_id'], ENT_QUOTES, 'UTF-8'));
				}
		        if (isset($this->request->get['toko_id'])) {
					$url .= '&toko_id=' . urlencode(html_entity_decode($this->request->get['toko_id'], ENT_QUOTES, 'UTF-8'));
				}
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['filter_date_start'])) {
					$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
				}
		        if (isset($this->request->get['filter_date_end'])) {
					$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

		  	if (!isset($this->request->get['periode_id'])) {
					$this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));

				} else {
		            if(!isset($this->request->get['toko_id'])){
		                $this->redirect($this->url->link('kepegawaian/periode/jadwal', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		            }
		            if(empty($this->request->get['toko_id'])){
		            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		        }
					$this->data['action'] = $this->url->link('kepegawaian/periode/insertjadwal', 'token=' . $this->session->data['token'] . $url, 'SSL');
				}

				$this->data['cancel'] = $this->url->link('kepegawaian/periode/jadwaltoko', 'token=' . $this->session->data['token'] . $url, 'SSL');


				$this->data['jadwal']=array();
				if($this->request->server['REQUEST_METHOD'] == 'POST'){
		            $this->data['jadwal']= $this->request->post;
		        }else{
		            $this->data['jadwal']=array();
		        }

				$this->data['token'] = $this->session->data['token'];

				$this->load->model('kepegawaian/periode');
		        $this->load->model('pamerantoko/toko');
		        $this->load->model('kepegawaian/shift');
		        $periode=$this->model_kepegawaian_periode->getPeriode($this->request->get['periode_id']);
		        $this->data['periode']=$periode;
		        if(empty($periode)){
		            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		        }

		        $shift=$this->model_kepegawaian_shift->getShiftByToko($this->request->get['toko_id']);
		        $this->data['shifts']=$shift;

		        $toko=$this->model_pamerantoko_toko->getPameran($this->request->get['toko_id']);
		        $this->data['toko']=$toko;

				$this->template = 'kepegawaian/jadwal_form.tpl';
				$this->children = array(
					'common/header',
					'common/footer'
				);

				$this->response->setOutput($this->render());
		  	}
		    private function validateFormJadwal() {
		    	/*if (!$this->user->hasPermission('modify', 'kepegawaian/periode')) {
		      		$this->error['warning'] = "Anda tidak memiliki hak untuk memodifikasi menu jadwal";
		    	}
					*/

		        $periode_id=$this->request->post['periode'];
		        $this->load->model('kepegawaian/periode');
		        $this->load->model('kepegawaian/jadwal');
		        if(empty($this->request->post['pegawai_id'])){
		            $this->error['nama'] = 'Nama SPG tidak boleh kosong';
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
    public function deletejadwal(){
        $this->load->model('kepegawaian/jadwal');
        $jadwal_id=$this->request->get['jadwal_id'];
        $this->model_kepegawaian_jadwal->deleteJadwal($jadwal_id);

        echo '1';
    }
    public function cetakjadwal(){
        $this->document->setTitle($this->language->get('Jadwal SPG'));
        if(!isset($this->request->get['periode_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if(empty($this->request->get['periode_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if(!isset($this->request->get['toko_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if(empty($this->request->get['toko_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

        if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = "";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = "";
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['periode_id'])) {
			$url .= '&periode_id=' .$this->request->get['periode_id'];
		}
        if (isset($this->request->get['toko_id'])) {
			$url .= '&toko_id=' .$this->request->get['toko_id'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Home',
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('Master Data Periode'),
			'href'      => $this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = $this->url->link('kepegawaian/periode/insertjadwal', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['cetak'] = $this->url->link('kepegawaian/periode/cetakjadwal', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('kepegawaian/periode/deletejadwal', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['cancel'] = $this->url->link('kepegawaian/periode/jadwal', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->data['liburs'] = array();
        $this->load->model('kepegawaian/periode');
        $this->load->model('pamerantoko/toko');
        $this->load->model('kepegawaian/shift');
        $periode=$this->model_kepegawaian_periode->getPeriode($this->request->get['periode_id']);
        $this->data['periode']=$periode;
        if(empty($periode)){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $shift=$this->model_kepegawaian_shift->getShiftByToko($this->request->get['toko_id']);
        $this->data['shifts']=$shift;

        $toko=$this->model_pamerantoko_toko->getPameran($this->request->get['toko_id']);
        $this->data['toko']=$toko;
        $this->load->model('kepegawaian/jadwal');



		$this->template = 'kepegawaian/jadwal_cetak.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
    }
    public function absensi(){
        $this->document->setTitle($this->language->get('Absensi SPG'));
        $this->load->model('pamerantoko/toko');
        if(!isset($this->request->get['periode_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if(empty($this->request->get['periode_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

        if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = "";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = "";
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

        if (isset($this->request->get['page2'])) {
			$page2 = $this->request->get['page2'];
		} else {
			$page2 = 1;
		}

		$url = '';
		if (isset($this->request->get['periode_id'])) {
			$url .= '&periode_id=' .$this->request->get['periode_id'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
        if (isset($this->request->get['page2'])) {
			$url .= '&page2=' . $this->request->get['page2'];
		}

  		$this->data['cancel'] = $this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['products'] = array();

		$data = array(

			'start'           => ($page2 - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

        $this->data['products'] = array();
        $this->load->model('pamerantoko/toko');
        $product_total = $this->model_pamerantoko_toko->getTotalPameran($data);
    	$results = $this->model_pamerantoko_toko->getPamerans($data);

		foreach ($results as $result) {

      		$action = array();


			$action[] = array(
				'text' => 'Tampil Absensi',
				'href' => $this->url->link('kepegawaian/periode/absensitoko', 'token=' . $this->session->data['token'] . '&toko_id=' . $result['pameran_id'] . $url, 'SSL')
			);
            $this->data['products'][] = array(
				'pameran_id' => $result['pameran_id'],
				'kode' => $result['kode'],
				'lokasi' => $result['lokasi'],

				'selected'   => isset($this->request->post['selected']) && in_array($result['pameran_id'], $this->request->post['selected']),
				'action'     => $action
			);

        }

        $this->data['heading_title'] = $this->language->get('Absensi SPG');



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
		if (isset($this->request->get['periode_id'])) {
			$url .= '&periode_id=' .$this->request->get['periode_id'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
        $pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page2;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('kepegawaian/periode/absensi', 'token=' . $this->session->data['token'] . $url . '&page2={page2}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'kepegawaian/toko_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
    }
    public function absensitoko(){
        $this->document->setTitle($this->language->get('Absensi SPG'));
        if(!isset($this->request->get['periode_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if(empty($this->request->get['periode_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if(!isset($this->request->get['toko_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if(empty($this->request->get['toko_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

        if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = "";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = "";
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['periode_id'])) {
			$url .= '&periode_id=' .$this->request->get['periode_id'];
		}
        if (isset($this->request->get['toko_id'])) {
			$url .= '&toko_id=' .$this->request->get['toko_id'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Home',
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('Master Data Periode'),
			'href'      => $this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = $this->url->link('kepegawaian/periode/insertabsensi', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['cetak'] = $this->url->link('kepegawaian/periode/cetakabsensi', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('kepegawaian/periode/deleteabsensi', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['cancel'] = $this->url->link('kepegawaian/periode/absensi', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $this->data['liburs'] = array();
        $this->load->model('kepegawaian/periode');
        $this->load->model('pamerantoko/toko');
        $this->load->model('kepegawaian/shift');
        $this->load->model('kepegawaian/absensi');
        $periode=$this->model_kepegawaian_periode->getPeriode($this->request->get['periode_id']);
        $this->data['periode']=$periode;
        if(empty($periode)){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $shift=$this->model_kepegawaian_shift->getShiftByToko($this->request->get['toko_id']);
        $this->data['shifts']=$shift;

        $toko=$this->model_pamerantoko_toko->getPameran($this->request->get['toko_id']);
        $this->data['toko']=$toko;
       // $this->load->model('kepegawaian/jadwal');
    	/* $results = $this->model_kepegawaian_jadwal->geJadwalByPeriode($this->request->get['periode_id'],$this->request->get['toko_id']);

		foreach ($results as $result) {

      		$this->data['liburs'][] = array(
				'periode_id' => $result['periode_id'],
				'libur_id'       => $result['libur_id'],
				'keterangan'       => $result['keterangan'],
				'tanggal'       => $result['tanggal'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['libur_id'], $this->request->post['selected']),
				'action'     => $action
			);
        } */

        $this->data['heading_title'] = $this->language->get('Master Data Periode');



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
		if (isset($this->request->get['periode_id'])) {
			$url .= '&periode_id=' .$this->request->get['periode_id'];
		}
        if (isset($this->request->get['toko_id'])) {
			$url .= '&toko_id=' .$this->request->get['toko_id'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->template = 'kepegawaian/absensitoko_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
    }

    public function insertabsensi() {
    	$this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Absensi SPG Toko'));

		$this->load->model('kepegawaian/absensi');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormAbsensi()) {
			$this->model_kepegawaian_absensi->addAbsensi($this->request->post);
	  		$this->session->data['success'] = "Data absensi berhasil diperbarui";

			$url = '';
			if (isset($this->request->get['periode_id'])) {
                $url .= '&periode_id=' . urlencode(html_entity_decode($this->request->get['periode_id'], ENT_QUOTES, 'UTF-8'));
            }
            if (isset($this->request->get['toko_id'])) {
                $url .= '&toko_id=' . urlencode(html_entity_decode($this->request->get['toko_id'], ENT_QUOTES, 'UTF-8'));
            }
			if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_date_start'])) {
                $url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
            }
            if (isset($this->request->get['filter_date_end'])) {
                $url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
            }

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('kepegawaian/periode/absensitoko', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getFormAbsensi();
  	}
    private function getFormAbsensi() {
        $this->document->setTitle($this->language->get('Absensi SPG Toko'));
    	$this->data['heading_title'] = $this->language->get('heading_title');



 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}



		$url = '';
        if (isset($this->request->get['periode_id'])) {
			$url .= '&periode_id=' . urlencode(html_entity_decode($this->request->get['periode_id'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['toko_id'])) {
			$url .= '&toko_id=' . urlencode(html_entity_decode($this->request->get['toko_id'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Home',
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => 'Absensi SPG Toko',
			'href'      => $this->url->link('kepegawaian/periode/absensitoko', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['periode_id'])) {
			$this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));

		} else {
            if(!isset($this->request->get['toko_id'])){
                $this->redirect($this->url->link('kepegawaian/periode/absensi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }
			$this->data['action'] = $this->url->link('kepegawaian/periode/insertabsensi', 'token=' . $this->session->data['token'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('kepegawaian/periode/absensitoko', 'token=' . $this->session->data['token'] . $url, 'SSL');


		$this->data['absensi']=array();
		if($this->request->server['REQUEST_METHOD'] == 'POST'){
            $this->data['absensi']= $this->request->post;
        }else{
            $this->data['absensi']=array();
        }

		$this->data['token'] = $this->session->data['token'];

		$this->load->model('kepegawaian/periode');
        $this->load->model('pamerantoko/toko');
        $this->load->model('kepegawaian/shift');
        $periode=$this->model_kepegawaian_periode->getPeriode($this->request->get['periode_id']);
        $this->data['periode']=$periode;
        if(empty($periode)){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $shift=$this->model_kepegawaian_shift->getShiftByToko($this->request->get['toko_id']);
        $this->data['shifts']=$shift;

        $toko=$this->model_pamerantoko_toko->getPameran($this->request->get['toko_id']);
        $this->data['toko']=$toko;

		$this->template = 'kepegawaian/absensi_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}
    private function validateFormAbsensi() {
    	/* if (!$this->user->hasPermission('modify', 'kepegawaian/periode')) {
      		$this->error['warning'] = "Anda tidak memiliki hak untuk memodifikasi menu libur";
    	}
 */



        $periode_id=$this->request->post['periode'];
        $this->load->model('kepegawaian/periode');
        $this->load->model('kepegawaian/absensi');
        $periode=$this->model_kepegawaian_periode->getPeriode($periode_id);
        if(empty($periode)){
            $this->error['periode'] = 'Periode tidak valid';
        }else{
            $tanggal = strtotime($this->request->post['tanggal']);
            if($tanggal < strtotime($periode['tgl_awal']) | $tanggal > strtotime($periode['tgl_selesai'])){
                 $this->error['tanggal'] = 'Tanggal kurang dari atau melebihi periode.';
            }

            $jam_masuk=strtotime($this->request->post['tanggal'].' '.$this->request->post['jam_datang']);
            $jam_pulang=strtotime($this->request->post['tanggal'].' '.$this->request->post['jam_pulang']);

            if($jam_pulang < $jam_masuk){
                $this->error['jam'] = 'Jam datang tidak boleh melebihi jam pulang';
            }
        }


        $cek=$this->model_kepegawaian_absensi->cekabsensi($this->request->post['tanggal'],$this->request->post['shift'],$this->request->post['toko_id'],$this->request->post['pegawai_id']);
        if(!empty($cek)){
            $this->error['tanggal'] = 'Duplikasi data.';
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
    public function deleteabsensi(){
        $this->load->model('kepegawaian/absensi');
        $absensi_id=$this->request->get['absensi_id'];
        $this->model_kepegawaian_absensi->deleteabsensi($absensi_id);

        echo '1';
    }
    public function cetakabsensi(){
        $this->document->setTitle($this->language->get('Absensi SPG'));
        if(!isset($this->request->get['periode_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if(empty($this->request->get['periode_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if(!isset($this->request->get['toko_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if(empty($this->request->get['toko_id'])){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
        if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

        if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = "";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = "";
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['periode_id'])) {
			$url .= '&periode_id=' .$this->request->get['periode_id'];
		}
        if (isset($this->request->get['toko_id'])) {
			$url .= '&toko_id=' .$this->request->get['toko_id'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


        $this->data['liburs'] = array();
        $this->load->model('kepegawaian/periode');
        $this->load->model('pamerantoko/toko');
        $this->load->model('kepegawaian/shift');
        $this->load->model('kepegawaian/absensi');
        $periode=$this->model_kepegawaian_periode->getPeriode($this->request->get['periode_id']);
        $this->data['periode']=$periode;
        if(empty($periode)){
            $this->redirect($this->url->link('kepegawaian/periode', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $shift=$this->model_kepegawaian_shift->getShiftByToko($this->request->get['toko_id']);
        $this->data['shifts']=$shift;

        $toko=$this->model_pamerantoko_toko->getPameran($this->request->get['toko_id']);
        $this->data['toko']=$toko;


        $this->data['heading_title'] = $this->language->get('Master Data Periode');



 		$this->data['token'] = $this->session->data['token'];


		$url = '';
		if (isset($this->request->get['periode_id'])) {
			$url .= '&periode_id=' .$this->request->get['periode_id'];
		}
        if (isset($this->request->get['toko_id'])) {
			$url .= '&toko_id=' .$this->request->get['toko_id'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
        if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->template = 'kepegawaian/absensitoko_cetak.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
    }

		public function autocomplete(){
			$json = array();

			$this->load->model('kepegawaian/periode');

				if (isset($this->request->get['q'])) {
					$filter_name = $this->request->get['q'];
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
				'start'	=>0,
				'limit'	=> 10,

				);

				$results = $this->model_kepegawaian_periode->getPeriodes($data);

				foreach ($results as $result) {
					$json[] = array(
						'id' => $result['periode_id'],
						'text'       => strip_tags(html_entity_decode($result['nama'], ENT_QUOTES, 'UTF-8')),

					);
				}


			$this->response->setOutput(json_encode($json));
		}

		public function detail(){
			$hasil = array();

			$this->load->model('kepegawaian/periode');
			if(isset($this->request->get['periode_id'])){
				if(!empty($this->request->get['periode_id'])){
					$column=array();
					$periode_id=$this->request->get['periode_id'];
				/*	$data = array(
						'periode_id'      =>$customer_id
					);*/

					$hasil=$this->model_kepegawaian_periode->getPeriode($periode_id);
					$date_start=date('Y-m-01',strtotime($hasil['tgl_awal']));
			    $date_end=date('Y-m-t',strtotime($hasil['tgl_awal']));
					$hasil['tgl_awal']=$date_start;
					$hasil['tgl_selesai']=$date_end;

				}
			}
			$this->response->setOutput(json_encode($hasil));
		}
}
?>
