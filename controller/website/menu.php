<?php
class ControllerWebsiteMenu extends Controller {
	private $error = array();

	// baru 1 Juli 2020
	public function getsub(){
		$this->load->model('website/menu');
		if(isset($this->request->get['grouping'])){
			$grouping=$this->request->get['grouping'];
		}
		$submenu=$this->model_website_menu->getsub($grouping);

		$test[]=array(
			'id'=>1,
			'nama'=>'test',
		);
		echo json_encode($submenu);
	}
	// end baru
	
	public function index() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle('Menu Method');

		$this->load->model('website/menu');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('website/menu');


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$test = $this->model_website_menu->addMenu($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_group'])) {
				$url .= '&filter_group=' . $this->request->get['filter_group'];
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

			$this->redirect($this->url->link('website/menu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('website/menu');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_website_menu->editMenu($this->request->get['menu_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_group'])) {
				$url .= '&filter_group=' . $this->request->get['filter_group'];
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

			$this->redirect($this->url->link('website/menu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('website/menu');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $menu_id) {
				$this->model_website_menu->deleteMenu($menu_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_group'])) {
				$url .= '&filter_group=' . $this->request->get['filter_group'];
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

			$this->redirect($this->url->link('website/menu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name= $this->request->get['filter_name'];
		} else {
			$filter_name = $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_group'])) {
			$filter_group= $this->request->get['filter_group'];
		} else {
			$filter_group = $this->request->get['filter_group'];
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'i.name';
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'i.name';
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

			if (isset($this->request->get['filter_group'])) {
				$url .= '&filter_group=' . $this->request->get['filter_group'];
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

  	$this->data['insert'] = $this->url->link('website/menu/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('website/menu/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['informations'] = array();

		$data = array(
			'filter_name'	=> $filter_name,
			'filter_group'	=> $filter_group,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);

		$information_total = $this->model_website_menu->getTotalMenus($data);

		$results = $this->model_website_menu->getMenus($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('website/menu/update', 'token=' . $this->session->data['token'] . '&menu_id=' . $result['menu_id'] . $url, 'SSL')
			);
			

			$this->data['informations'][] = array(
				'menu_id' => $result['menu_id'],
				'nama'          => $result['nama'],
				'url'          => $result['url'],
				'grouping'          => $result['grouping'],
				'sort_order'     => $result['sort_order'],
				'selected'       => isset($this->request->post['selected']) && in_array($result['menu_id'], $this->request->post['selected']),
				'action'         => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_url'] = $this->language->get('column_url');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
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

			if (isset($this->request->get['filter_group'])) {
				$url .= '&filter_group=' . $this->request->get['filter_group'];
			}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_title'] = $this->url->link('website/menu', 'token=' . $this->session->data['token'] . '&sort=id.title' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('website/menu', 'token=' . $this->session->data['token'] . '&sort=i.sort_order' . $url, 'SSL');

		$url = '';
		if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_group'])) {
				$url .= '&filter_group=' . $this->request->get['filter_group'];
			}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $information_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('website/menu', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'website/menu_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');

		$this->data['entry_nama'] = $this->language->get('entry_nama');
		$this->data['entry_url'] = $this->language->get('entry_url');
		$this->data['entry_grouping'] = $this->language->get('entry_grouping');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_layout'] = $this->language->get('entry_layout');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');
    	$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_design'] = $this->language->get('tab_design');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['nama'])) {
			$this->data['error_nama'] = $this->error['nama'];
		} else {
			$this->data['error_nama'] = '';
		}
		if (isset($this->error['url'])) {
			$this->data['error_url'] = $this->error['url'];
		} else {
			$this->data['error_url'] = '';
		}
		if (isset($this->error['grouping'])) {
			$this->data['error_grouping'] = $this->error['grouping'];
		} else {
			$this->data['error_grouping'] = '';
		}


		$url = '';
		if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_group'])) {
				$url .= '&filter_group=' . $this->request->get['filter_group'];
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

  		if (!isset($this->request->get['menu_id'])) {
			$this->data['action'] = $this->url->link('website/menu/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('website/menu/update', 'token=' . $this->session->data['token'] . '&information_id=' . $this->request->get['menu_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('website/menu', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['menu_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$information_info = $this->model_website_menu->getMenu($this->request->get['menu_id']);
		}

		$this->data['token'] = $this->session->data['token'];


		if (isset($this->request->post['nama'])) {
			$this->data['nama'] = $this->request->post['nama'];
		} elseif (!empty($information_info)) {
			$this->data['nama'] = $information_info['nama'];
		} else {
			$this->data['nama'] = '';
		}

		if (isset($this->request->post['grouping'])) {
			$this->data['grouping'] = $this->request->post['grouping'];
		} elseif (!empty($information_info)) {
			$this->data['grouping'] = $information_info['grouping'];
		} else {
			$this->data['grouping'] = '';
		}

		if (isset($this->request->post['url'])) {
			$this->data['url'] = $this->request->post['url'];
		} elseif (!empty($information_info)) {
			$this->data['url'] = $information_info['url'];
		} else {
			$this->data['url'] = '';
		}

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($information_info)) {
			$this->data['sort_order'] = $information_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}

		$this->data['submenu']=$this->model_website_menu->getsub($IN=null);
		if (isset($this->request->post['sub_id'])) {
			$this->data['sub_id'] = $this->request->post['sub_id'];
			$this->data['namasubmenu'] = $this->request->post['sub_id'];;
		} elseif (!empty($information_info)) {
			$this->data['sub_id'] = $information_info['sub_id'];
			$this->data['namasubmenu'] = $this->model_website_menu->getnamasubmenu($information_info['sub_id']);
		} else {
			$this->data['sub_id'] =0;
			$this->data['namasubmenu'] ='Pilih';
		}

		$this->template = 'website/menu_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		/* if (!$this->user->hasPermission('modify', 'website/widget')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['title']) < 3) || (utf8_strlen($this->request->post['title']) > 64)) {
			$this->error['title'] = $this->language->get('error_title');
		}

		if (utf8_strlen($this->request->post['description']) < 3) {
			$this->error['description'] = $this->language->get('error_description');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		} */

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'website/menu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}


		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}


}
?>
