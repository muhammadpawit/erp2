<?php
class ControllerUserUser extends Controller {
	private $error = array();

  	public function index() {
    	$this->load->language('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/user');

    	$this->getList();
  	}

  	public function insert() {
    	$this->load->language('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/user');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_user_user->addUser($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}
			if (isset($this->request->get['filter_tglakhir'])) {
				$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
			}
			if (isset($this->request->get['filter_divisi'])) {
				$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
			}
			if (isset($this->request->get['filter_jabatan'])) {
				$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['filter_statuspegawai'])) {
				$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
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

			$this->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/user');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_user_user->editUser($this->request->get['user_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}
			if (isset($this->request->get['filter_tglakhir'])) {
				$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
			}
			if (isset($this->request->get['filter_divisi'])) {
				$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
			}
			if (isset($this->request->get['filter_jabatan'])) {
				$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['filter_statuspegawai'])) {
				$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
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

			$this->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->load->language('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/user');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
      		foreach ($this->request->post['selected'] as $user_id) {
				$this->model_user_user->deleteUser($user_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}
			if (isset($this->request->get['filter_tglakhir'])) {
				$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
			}
			if (isset($this->request->get['filter_divisi'])) {
				$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
			}
			if (isset($this->request->get['filter_jabatan'])) {
				$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['filter_statuspegawai'])) {
				$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
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

			$this->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getList();
  	}

  	private function getList() {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}
			if (isset($this->request->get['filter_gudang_id'])) {
				$filter_gudang_id = $this->request->get['filter_gudang_id'];
			} else {
				$filter_gudang_id = '';
			}
			if (isset($this->request->get['filter_tglakhir'])) {
				$filter_tglakhir = $this->request->get['filter_tglakhir'];
			} else {
				$filter_tglakhir = '';
			}
			if (isset($this->request->get['filter_jabatan'])) {
				$filter_jabatan = $this->request->get['filter_jabatan'];
			} else {
				$filter_jabatan = '';
			}
			if (isset($this->request->get['filter_divisi'])) {
				$filter_divisi = $this->request->get['filter_divisi'];
			} else {
				$filter_divisi = '';
			}
			if (isset($this->request->get['filter_statuspegawai'])) {
				$filter_statuspegawai = $this->request->get['filter_statuspegawai'];
			} else {
				$filter_statuspegawai = '';
			}
			if (isset($this->request->get['filter_status'])) {
				$filter_status = $this->request->get['filter_status'];
			} else {

				$filter_status = null;
			}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'username';
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
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_tglakhir'])) {
			$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
		}
		if (isset($this->request->get['filter_divisi'])) {
			$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
		}
		if (isset($this->request->get['filter_jabatan'])) {
			$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_statuspegawai'])) {
			$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
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

  		$this->data['insert'] = $this->url->link('user/user/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('user/user/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	$this->data['users'] = array();

		$data = array(
			'filter_name'	=> $filter_name,
			'filter_gudang_id'	=> $filter_gudang_id,
			'filter_tglakhir'	=> $filter_tglakhir,
			'filter_divisi'	=> $filter_divisi,
			'filter_jabatan'	=> $filter_jabatan,
			'filter_status'	=> $filter_status,
			'filter_statuspegawai'	=> $filter_statuspegawai,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);

		$user_total = $this->model_user_user->getTotalUsers($data);

		$results = $this->model_user_user->getUsers($data);
		//print_r($results);
		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('user/user/update', 'token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url, 'SSL')
			);

			/*if($result['jenispegawai'] == 2){
				$action[] = array(
					'text' => "Kontrak",
					'href' => $this->url->link('user/user/kontrak', 'token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url, 'SSL')
				);
			}*/



      $this->data['users'][] = array(
				'user_id'    => $result['user_id'],
				'namagudang'	=> $result['nama'],
				'firstname'   => $result['firstname'],
				'username'   => $result['username'],
				'email'	=> $result['email'],
				'telephone'	=> $result['telephone'],
				'hp'	=> $result['hp'],
				'alamat'	=> $result['alamat'],
				'tglakhir'	=> date('d/m/y',strtotime($result['tglakhir'])),
				'jenispegawai'	=> $result['jenispegawai'],
				'divisi'	=> $result['namadivisi'],
				'jabatan'	=> $result['namajabatan'],
				'status'     => ($result['status'] ? 'Aktif' : 'Tidak Aktif'),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['user_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_username'] = $this->language->get('column_username');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');

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
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_tglakhir'])) {
			$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
		}
		if (isset($this->request->get['filter_divisi'])) {
			$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
		}
		if (isset($this->request->get['filter_jabatan'])) {
			$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_statuspegawai'])) {
			$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_username'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&sort=username' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->load->model('user/user_group');

    	$this->data['user_groups'] = $this->model_user_user_group->getUserGroups();

			$this->load->model('catalog/gudang');
			$this->data['gudangs']=$this->model_catalog_gudang->getGudangs();
    	$this->load->model('user/divisi');
			$this->data['divisis']=$this->model_user_divisi->getDivisis();

		$this->template = 'user/user_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');


    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['username'])) {
			$this->data['error_username'] = $this->error['username'];
		} else {
			$this->data['error_username'] = '';
		}

 		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}

 		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}

	 	if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}

	 	if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}

		$url = '';
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_tglakhir'])) {
			$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
		}
		if (isset($this->request->get['filter_divisi'])) {
			$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
		}
		if (isset($this->request->get['filter_jabatan'])) {
			$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['filter_statuspegawai'])) {
			$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		if (!isset($this->request->get['user_id'])) {
			$this->data['action'] = $this->url->link('user/user/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('user/user/update', 'token=' . $this->session->data['token'] . '&user_id=' . $this->request->get['user_id'] . $url, 'SSL');
		}

    	$this->data['cancel'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->request->get['user_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$user_info = $this->model_user_user->getUser($this->request->get['user_id']);
    	}

			if (!empty($this->request->post)) {
				$this->data['firstname'] = $this->request->post['firstname'];
				$this->data['username'] = $this->request->post['username'];
				$this->data['password'] = $this->request->post['password'];
				$this->data['alamat'] = $this->request->post['alamat'];
				$this->data['email'] = $this->request->post['email'];
				$this->data['npwp'] = $this->request->post['npwp'];
				$this->data['telephone'] = $this->request->post['telephone'];
				$this->data['hp'] = $this->request->post['hp'];
				$this->data['tempat_lahir'] = $this->request->post['tempatlahir'];
				$this->data['tgl_lahir'] = $this->request->post['tgl_lahir'];
				$this->data['agama'] = $this->request->post['agama'];
				$this->data['ktp'] = $this->request->post['ktp'];
				$this->data['jeniskelamin'] = $this->request->post['jeniskelamin'];
				$this->data['divisi'] = $this->request->post['divisi'];
				$this->data['jenispegawai'] = $this->request->post['jenispegawai'];
				$this->data['tglmasuk'] = $this->request->post['tglmasuk'];
				$this->data['tglakhir'] = $this->request->post['tglakhir'];
				$this->data['pendidikan'] = $this->request->post['pendidikan'];
				$this->data['bank'] = $this->request->post['bank'];
				$this->data['rekening'] = $this->request->post['rekening'];
				$this->data['statuskawin'] = $this->request->post['statuskawin'];
				$this->data['namakerabat'] = $this->request->post['namakerabat'];
				$this->data['alamatkerabat'] = $this->request->post['alamatkerabat'];
				$this->data['telpkerabat'] = $this->request->post['telpkerabat'];
				$this->data['foto'] = $this->request->post['foto'];
				$this->data['status'] = $this->request->post['status'];
				$this->data['user_group_id'] = $this->request->post['user_group_id'];
				$this->data['gudang_id'] = $this->request->post['gudang_id'];
			} elseif (!empty($user_info)) {
				$this->data['firstname'] = $user_info['firstname'];
				$this->data['username'] = $user_info['username'];
				$this->data['password'] = "";
				$this->data['alamat'] = $user_info['alamat'];
				$this->data['email'] = $user_info['email'];
				$this->data['npwp'] = $user_info['npwp'];
				$this->data['telephone'] = $user_info['telephone'];
				$this->data['hp'] = $user_info['hp'];
				$this->data['tempat_lahir'] = $user_info['tempatlahir'];
				$this->data['tgl_lahir'] = $user_info['tgl_lahir'];
				$this->data['agama'] = $user_info['agama'];
				$this->data['ktp'] = $user_info['ktp'];
				$this->data['jeniskelamin'] = $user_info['jeniskelamin'];
				$this->data['divisi'] = $user_info['divisi'];
				$this->data['jenispegawai'] = $user_info['jenispegawai'];
				$this->data['tglmasuk'] = $user_info['tglmasuk'];
				$this->data['tglakhir'] = $user_info['tglakhir'];
				$this->data['pendidikan'] = $user_info['pendidikan'];
				$this->data['bank'] = $user_info['bank'];
				$this->data['rekening'] = $user_info['rekening'];
				$this->data['statuskawin'] = $user_info['statuskawin'];
				$this->data['namakerabat'] = $user_info['namakerabat'];
				$this->data['alamatkerabat'] = $user_info['alamatkerabat'];
				$this->data['telpkerabat'] = $user_info['telpkerabat'];
				$this->data['foto'] = $user_info['foto'];
				$this->data['status'] = $user_info['status'];
				$this->data['user_group_id'] = $user_info['user_group_id'];
				$this->data['gudang_id'] = $user_info['gudang_id'];
			} else {
				$this->data['firstname'] = "";
				$this->data['username'] = "";
				$this->data['password'] = "";
				$this->data['alamat'] = "";
				$this->data['email'] = "";
				$this->data['npwp'] = "";
				$this->data['telephone'] = "";
				$this->data['hp'] = "";
				$this->data['tempat_lahir'] = "";
				$this->data['tgl_lahir'] = "";
				$this->data['agama'] = "";
				$this->data['ktp'] = "";
				$this->data['jeniskelamin'] = "";
				$this->data['divisi'] = "";
				$this->data['jenispegawai'] = "";
				$this->data['tglmasuk'] = "";
				$this->data['tglakhir'] = "";
				$this->data['pendidikan'] = "";
				$this->data['bank'] = "";
				$this->data['rekening'] = "";
				$this->data['statuskawin'] ="";
				$this->data['namakerabat'] = "";
				$this->data['alamatkerabat'] = "";
				$this->data['telpkerabat'] = "";
				$this->data['foto'] = "";
				$this->data['status'] = 0;
				$this->data['gudang_id'] = 1;
			}

		$this->load->model('user/user_group');

    	$this->data['user_groups'] = $this->model_user_user_group->getUserGroups();


    	$this->load->model('user/divisi');
			$this->data['divisis']=$this->model_user_divisi->getDivisis();

			$this->load->model('catalog/gudang');
			$this->data['gudangs']=$this->model_catalog_gudang->getGudangs();

		$this->template = 'user/user_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'user/user')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
			if($this->request->post['status']){
	    	if ((utf8_strlen($this->request->post['username']) < 3) || (utf8_strlen($this->request->post['username']) > 20)) {
	      		$this->error['username'] = $this->language->get('error_username');
	    	}
				if ($this->request->post['password'] || (!isset($this->request->get['user_id']))) {
	      		if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
	        		$this->error['password'] = $this->language->get('error_password');
	      		}

		  		if ($this->request->post['password'] != $this->request->post['confirm']) {
		    		$this->error['confirm'] = $this->language->get('error_confirm');
		  		}
	    	}

				$user_info = $this->model_user_user->getUserByUsername($this->request->post['username']);
				if (!isset($this->request->get['user_id'])) {
					if ($user_info) {
						$this->error['warning'] = $this->language->get('error_exists');
					}
				} else {
					if ($user_info && ($this->request->get['user_id'] != $user_info['user_id'])) {
						$this->error['warning'] = $this->language->get('error_exists');
					}
				}
			}


    	if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
    	}


    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'user/user')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

		foreach ($this->request->post['selected'] as $user_id) {
			if ($this->user->getId() == $user_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}
		}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

		public function autocomplete() {
			$json = array();

			$this->load->model('user/user');

				if (isset($this->request->get['q'])) {
					$filter_name = $this->request->get['q'];
				} else {
					$filter_name = null;
				}

				if (isset($this->request->get['j'])) {
					$filter_jabatan = $this->request->get['j'];

				} else {
					$filter_jabatan = null;
				}

				if (isset($this->request->get['filter_statuspegawai'])) {
					$filter_statuspegawai = $this->request->get['filter_statuspegawai'];
				} else {
					$filter_statuspegawai = '';
				}

				if (isset($this->request->get['limit'])) {
					$limit = $this->request->get['limit'];
				} else {
					$limit = 20;
				}


				$data = array(
				'filter_name'	  => $filter_name,
				'filter_jabatan'	=> $filter_jabatan,
				'start'	=>0,
				'limit'	=> 10,
				'filter_statuspegawai'	=> $filter_statuspegawai
					//'start'               => 0,
					//'limit'               => $limit
				);

				$results = $this->model_user_user->getUsers($data);

				foreach ($results as $result) {
					$json[] = array(
						'id' => $result['user_id'],
						'text'       => strip_tags(html_entity_decode($result['firstname'], ENT_QUOTES, 'UTF-8')),

					);
				}


			$this->response->setOutput(json_encode($json));
		}


		public function kontrak() {
			    $this->load->language('sale/customer');

					$this->document->setTitle($this->language->get('heading_title'));

					$this->load->model('user/user');
			    if (isset($this->request->get['user_id'])) {
						if(empty($this->request->get['user_id'])){
							$this->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
						}else{
							$user_id = $this->request->get['user_id'];
						}
					} else {
						$this->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
					}


			    if (isset($this->request->get['pagealamat'])) {
						$pagealamat = $this->request->get['pagealamat'];
					} else {
						$pagealamat = 1;
					}

					$url = '';
					if (isset($this->request->get['filter_name'])) {
						$url .= '&filter_name=' . $this->request->get['filter_name'];
					}
					if (isset($this->request->get['filter_tglakhir'])) {
						$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
					}
					if (isset($this->request->get['filter_divisi'])) {
						$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
					}
					if (isset($this->request->get['filter_jabatan'])) {
						$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
					}
					if (isset($this->request->get['filter_status'])) {
						$url .= '&filter_status=' . $this->request->get['filter_status'];
					}
					if (isset($this->request->get['sort'])) {
						$url .= '&sort=' . $this->request->get['sort'];
					}
					if (isset($this->request->get['filter_statuspegawai'])) {
						$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
					}

					if (isset($this->request->get['order'])) {
						$url .= '&order=' . $this->request->get['order'];
					}

					if (isset($this->request->get['page'])) {
						$url .= '&page=' . $this->request->get['page'];
					}
			        if (isset($this->request->get['pagealamat'])) {
						$url .= '&pagealamat=' . $this->request->get['pagealamat'];
					}

			  		$this->data['cancel'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL');

						if (isset($this->request->get['user_id'])) {
							$url .= '&user_id=' . $this->request->get['user_id'];
						}
					$this->data['insert'] = $this->url->link('user/user/insertkontrak', 'token=' . $this->session->data['token'] . $url, 'SSL');
					$this->data['delete'] = $this->url->link('user/user/deletekontrak', 'token=' . $this->session->data['token'] . $url, 'SSL');

					$this->data['visits'] = array();

					$data = array(
						'start'                    => ($pagealamat - 1) * $this->config->get('config_admin_limit'),
						'limit'                    => $this->config->get('config_admin_limit')
					);

					$address_total = $this->model_user_user->getTotalkontraks($user_id);

					$results = $this->model_user_user->getKontraks($user_id,$data);

					$this->load->model('tool/image');
					$this->load->model('user/user');

					$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 40, 40);

			   foreach ($results as $result) {
						$action = array();

						if ($result['file'] && file_exists(DIR_IMAGE . $result['file'])) {
							$image = $result['file'];
						} else {
							$image = 'no_image.jpg';
						}

						/*$action[] = array(
							'text' => $this->language->get('text_edit'),
							'href' => $this->url->link('user/user/kontrak', 'token=' . $this->session->data['token'] . '&user_id=' . $result['pegawai_id'].'&id='.$result['id'] . $url, 'SSL')
						);*/
						$action[] = array(
							'text' => 'batalkan',
							'href' => $this->url->link('user/user/batalkankontrak', 'token=' . $this->session->data['token'] . '&user_id=' . $result['pegawai_id'].'&id='.$result['id'] . $url, 'SSL')
						);

						$this->data['kontraks'][] = array(

							'tglawal'	=> date('d/m/y',strtotime($result['tglawal'])),
							'tglakhir'	=> date('d/m/y ',strtotime($result['tglakhir'])),
							'keterangan'	=> $result['keterangan'],
							'no_kontrak'	=> $result['no_kontrak'],
							'id'=> $result['id'],
							'thumb'	=> $this->model_tool_image->resize($image, 200, 400),
							'image'	=> $this->model_tool_image->resize($image, 600, 800),
							'action'	=> $action,
							'status'	=> $result['status'] == 1?'Aktif':($result['status'] == 0?'Masa Berlaku Habis':'Dibatalkan'),
							'selected'       => isset($this->request->post['selected']) && in_array($result['product_image_id'], $this->request->post['selected']),

						);
					}

					$this->data['heading_title'] = $this->language->get('heading_title');

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
						$url .= '&filter_name=' . $this->request->get['filter_name'];
					}
					if (isset($this->request->get['filter_tglakhir'])) {
						$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
					}
					if (isset($this->request->get['filter_divisi'])) {
						$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
					}
					if (isset($this->request->get['filter_jabatan'])) {
						$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
					}
					if (isset($this->request->get['filter_status'])) {
						$url .= '&filter_status=' . $this->request->get['filter_status'];
					}
					if (isset($this->request->get['sort'])) {
						$url .= '&sort=' . $this->request->get['sort'];
					}
					if (isset($this->request->get['filter_statuspegawai'])) {
						$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
					}

					if (isset($this->request->get['order'])) {
						$url .= '&order=' . $this->request->get['order'];
					}

					if (isset($this->request->get['page'])) {
						$url .= '&page=' . $this->request->get['page'];
					}


					$pagination = new Pagination();
					$pagination->total = $address_total;
					$pagination->page = $pagealamat;
					$pagination->limit = $this->config->get('config_admin_limit');
					$pagination->text = $this->language->get('text_pagination');
					$pagination->url = $this->url->link('user/user/kontrak', 'token=' . $this->session->data['token'] . $url . '&pagealamat={page}', 'SSL');

					$this->data['pagination'] = $pagination->render();


					$this->template = 'kepegawaian/kontrak_list.tpl';
					$this->children = array(
						'common/header',
						'common/footer'
					);

					$this->response->setOutput($this->render());
		}

		public function insertkontrak() {
			$this->load->language('user/user');

	    	$this->document->setTitle("Kontrak Pegawai");

			$this->load->model('user/user');
			if (!isset($this->request->get['user_id'])) {
	            $this->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	        }
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormKontrak()) {
	      	  	$this->model_user_user->addKontraks($this->request->get['user_id'],$this->request->post);

				$this->session->data['success'] = $this->language->get('text_success');

				$url = '';
				if (isset($this->request->get['user_id'])) {
					$url .= '&user_id=' . $this->request->get['user_id'];
				}
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . $this->request->get['filter_name'];
				}
				if (isset($this->request->get['filter_tglakhir'])) {
					$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
				}
				if (isset($this->request->get['filter_divisi'])) {
					$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
				}
				if (isset($this->request->get['filter_jabatan'])) {
					$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
				}
				if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
				}
				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				if (isset($this->request->get['filter_statuspegawai'])) {
					$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
						if (isset($this->request->get['pagealamat'])) {
					$url .= '&pagealamat=' . $this->request->get['pagealamat'];
				}

				$this->redirect($this->url->link('user/user/kontrak', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}

	    	$this->getFormKunjungan();
	  	}



	 private function getFormKunjungan() {
	    	$this->data['heading_title'] = $this->language->get('heading_title');

	  $this->data['token'] = $this->session->data['token'];

			if (isset($this->request->get['user_id'])) {
				$this->data['user_id'] = $this->request->get['user_id'];
			} else {
				$this->data['user_id'] = 0;
			}

	 		if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
			} else {
				$this->data['error_warning'] = '';
			}

			$url = '';
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			if (isset($this->request->get['filter_tglakhir'])) {
				$url .= '&filter_tglakhir=' . $this->request->get['filter_tglakhir'];
			}
			if (isset($this->request->get['filter_divisi'])) {
				$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
			}
			if (isset($this->request->get['filter_jabatan'])) {
				$url .= '&filter_jabatan=' . $this->request->get['filter_jabatan'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['filter_statuspegawai'])) {
				$url .= '&filter_statuspegawai=' . $this->request->get['filter_statuspegawai'];
			}
			if (isset($this->request->get['user_id'])) {
				$url .= '&user_id=' . $this->request->get['user_id'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
					if (isset($this->request->get['pagealamat'])) {
				$url .= '&pagealamat=' . $this->request->get['pagealamat'];
			}

	  		$this->data['action'] = $this->url->link('user/user/insertkontrak', 'token=' . $this->session->data['token'] . $url, 'SSL');


	    	$this->data['cancel'] = $this->url->link('user/user/kontrak', 'token=' . $this->session->data['token'] . $url, 'SSL');
				$this->data['user']=$this->model_user_user->getUser($this->request->get['user_id']);

	    $this->template = 'kepegawaian/kontrak_form.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render());
		}

	 private function validateFormKontrak() {
		 if (!$this->user->hasPermission('modify', 'user/user')) {
				 $this->error['warning'] = $this->language->get('error_permission');
		 }
			if ($this->error && !isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_warning');
			}

			if (!$this->error) {
		  		return true;
			} else {
		  		return false;
			}
	  }

		private function aksesdata() {
			$this->load->language('user/user');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('user/user');

			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_user_user->editPermission($this->request->get['user_id'], $this->request->post);

				$this->session->data['success'] = "Pembatasan Akses Data User Berhasil Disimpan";

				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

				$this->redirect($this->url->link('user/user_permission', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}

			$this->getForm();

			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['text_select_all'] = $this->language->get('text_select_all');
			$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');

			$this->data['entry_name'] = $this->language->get('entry_name');
			$this->data['entry_access'] = $this->language->get('entry_access');
			$this->data['entry_modify'] = $this->language->get('entry_modify');

			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');

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
	       		'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('user/user_permission', 'token=' . $this->session->data['token'] . $url, 'SSL'),
	      		'separator' => ' :: '
	   		);

			if (!isset($this->request->get['user_group_id'])) {
				$this->data['action'] = $this->url->link('user/user_permission/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
			} else {
				$this->data['action'] = $this->url->link('user/user_permission/update', 'token=' . $this->session->data['token'] . '&user_group_id=' . $this->request->get['user_group_id'] . $url, 'SSL');
			}

	    	$this->data['cancel'] = $this->url->link('user/user_permission', 'token=' . $this->session->data['token'] . $url, 'SSL');

			if (isset($this->request->get['user_group_id']) && $this->request->server['REQUEST_METHOD'] != 'POST') {
				$user_group_info = $this->model_user_user_group->getUserGroup($this->request->get['user_group_id']);
			}

			if (isset($this->request->post['name'])) {
				$this->data['name'] = $this->request->post['name'];
			} elseif (!empty($user_group_info)) {
				$this->data['name'] = $user_group_info['name'];
			} else {
				$this->data['name'] = '';
			}

			$ignore = array(
				'common/home',
				'common/startup',
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission',
				'common/footer',
				'common/header',
				'pameran/header',
				'pameran/footer',
				'gudang/header',
				'gudang/footer'
			);

			$this->data['permissions'] = array();

			$files = glob(DIR_APPLICATION . 'controller/*/*.php');

			foreach ($files as $file) {
				$data = explode('/', dirname($file));

				$permission = end($data) . '/' . basename($file, '.php');

				if (!in_array($permission, $ignore)) {
					$this->data['permissions'][] = $permission;
				}
			}

			$this->data['menus']=$this->model_user_user_group->getMenu();

			if (isset($this->request->post['permission']['access'])) {
				$this->data['access'] = $this->request->post['permission']['access'];
			} elseif (isset($user_group_info['permission']['access'])) {
				$this->data['access'] = $user_group_info['permission']['access'];
			} else {
				$this->data['access'] = array();
			}

			if (isset($this->request->post['permission']['modify'])) {
				$this->data['modify'] = $this->request->post['permission']['modify'];
			} elseif (isset($user_group_info['permission']['modify'])) {
				$this->data['modify'] = $user_group_info['permission']['modify'];
			} else {
				$this->data['modify'] = array();
			}

			$this->template = 'user/user_group_form.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render());
		}

		private function validateFormData() {
			if (!$this->user->hasPermission('modify', 'user/user/aksesdata')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}

			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
				$this->error['name'] = $this->language->get('error_name');
			}

			if (!$this->error) {
				return true;
			} else {
				return false;
			}
		}
		
}
?>
