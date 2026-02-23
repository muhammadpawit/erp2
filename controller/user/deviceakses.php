<?php
class ControllerUserDeviceakses extends Controller {
	private $error = array();

  	public function index() {
    	$this->load->language('user/user');

    	$this->document->setTitle("Limitasi Perangkat Pengakses");

		$this->load->model('user/deviceakses');

    	$this->getList();
  	}

  	/*public function insert() {
    	$this->load->language('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/deviceakses');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_user_deviceakses->addUser($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->redirect($this->url->link('user/deviceakses', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}*/

  	public function approved() {
    	$this->load->language('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/deviceakses');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_user_deviceakses->approved($this->request->get['id'], $this->request->post);

			$this->session->data['success'] = "Perangkat pengakses telah dsetujui.";

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('user/deviceakses', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->load->language('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/deviceakses');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
      		foreach ($this->request->post['selected'] as $user_id) {
				$this->model_user_deviceakses->block($user_id);
			}

			$this->session->data['success'] = "Perangkat pengakses berhasil di block";

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('user/deviceakses', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getList();
  	}

		public function openakses() {
    	$this->load->language('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/deviceakses');

    	if (isset($this->request->get['id']) && $this->validateDelete()) {
      		//foreach ($this->request->post['selected'] as $user_id) {
				$this->model_user_deviceakses->openakses($this->request->get['id']);
			//}

			$this->session->data['success'] = "Perangkat pengakses berhasil di setujui kembali";

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('user/deviceakses', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getList();
  	}

  	private function getList() {
			if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  	$this->data['delete'] = $this->url->link('user/deviceakses/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	$this->data['devices'] = array();

		$data = array(
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);

		$user_total = $this->model_user_deviceakses->getTotalDevices();

		$results = $this->model_user_deviceakses->getDevices($data);
		$devicetoken=$_COOKIE["validatedevice"];
		$this->data['devicetoken']=$devicetoken;

		foreach ($results as $result) {
			$action = array();
			if($result['status'] == 2){
				$action[] = array(
					'text' => 'Setujui',
					'href' => $this->url->link('user/deviceakses/approved', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
				);

			}

			if($result['status'] == 3){
				$action[] = array(
					'text' => 'Buka Akses',
					'href' => $this->url->link('user/deviceakses/openakses', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
				);

			}
			/*$action[] = array(
				'text' => 'History',
				'href' => $this->url->link('user/deviceakses/history', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
			);*/


      $this->data['devices'][] = array(
				'id'	=> $result['id'],
				'token'	=> $result['token'],
				'namadevice'	=> empty($result['namadevice'])?'Perangkat belum terdaftar':$result['namadevice'],
				'os'	=> $result['os'],
				'browser'	=> $result['browser'],
				'user'	=> $result['user_id'],
				//'location'	=> $result
				//'approvedby'	=> $result['browser'],
				'plainstatus'	=> $result['status'],
				'status'     => $result['status']==1?'Disetujui':($result['status'] == 2?'Menunggu Persetujuan':'Di block'),
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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$url = '';

		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('user/deviceakses', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'user/deviceakses_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['namadevice'])) {
			$this->data['error_namadevice'] = $this->error['namadevice'];
		} else {
			$this->data['error_namadevice'] = '';
		}



		$url = '';


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();



		if (!isset($this->request->get['id'])) {
			$this->redirect($this->url->link('user/deviceakses', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		} else {
			$this->data['action'] = $this->url->link('user/deviceakses/approved', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'] . $url, 'SSL');
		}

    	$this->data['cancel'] = $this->url->link('user/deviceakses', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$user_info = $this->model_user_deviceakses->getDevice($this->request->get['id']);
    	}

    	if (isset($this->request->post['namadevice'])) {
      		$this->data['namadevice'] = $this->request->post['namadevice'];
    	} elseif (!empty($user_info)) {
				$this->data['namadevice'] = $user_info['namadevice'];
			} else {
	      		$this->data['namadevice'] = '';
	    	}

			if (isset($this->request->post['os'])) {
					$this->data['os'] = $this->request->post['os'];
			} elseif (!empty($user_info)) {
				$this->data['os'] = $user_info['os'];
			} else {
						$this->data['os'] = '';
				}

				if (isset($this->request->post['browser'])) {
						$this->data['browser'] = $this->request->post['browser'];
				} elseif (!empty($user_info)) {
					$this->data['browser'] = $user_info['browser'];
				} else {
							$this->data['browser'] = '';
					}
			if (isset($this->request->post['user_id'])) {
					$this->data['user_id'] = $this->request->post['user_id'];
			} elseif (!empty($user_info)) {
				$this->data['user_id'] = $user_info['user_id'];
			} else {
						$this->data['user_id'] = '';
				}

				if (isset($this->request->post['date_added'])) {
						$this->data['date_added'] = $this->request->post['date_added'];
				} elseif (!empty($user_info)) {
					$this->data['date_added'] = date('d/m/y H:i:s',strtotime($user_info['date_added']));
				} else {
							$this->data['date_added'] = '';
					}





		$this->template = 'user/deviceakses_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'user/deviceakses')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((utf8_strlen($this->request->post['namadevice']) < 1) || (utf8_strlen($this->request->post['namadevice']) > 20)) {
      		$this->error['namadevice'] = "Nama device harus diisi";
    	}


    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'user/deviceakses')) {
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
}
?>
