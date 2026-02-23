<?php
class ControllerCatalogGudang extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle('Master Data Gudang');

		$this->load->model('catalog/gudang');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle('Master Data Gudang');

		$this->load->model('catalog/gudang');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if($this->user->getUsername()=="pawits"){
				echo "<pre>";
				print_r($this->request->post);
				exit;
			}
			$m =$this->model_catalog_gudang->addGudang($this->request->post['gudang']);
			if($this->user->getUsername()=="paaswit"){
				echo "<pre>";
				print_r($m);
				exit;
			}
			$this->session->data['success'] = 'Data gudang berhasil ditambahkan';

			$this->redirect($this->url->link('catalog/gudang', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle($this->language->get('Master Data Gudang'));

		$this->load->model('catalog/gudang');
		if($this->user->getUsername()=="psawit"){
				echo "<pre>";
				print_r($this->request->post);
				//print_r($m);
				exit;
			}
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			$m = $this->model_catalog_gudang->editGudang($this->request->get['gudang_id'], $this->request->post['gudang']);
			if($this->user->getUsername()=="pawits"){
				echo "<pre>";
				//print_r($this->request->post);
				print_r($m);
				exit;
			}
			$this->session->data['success'] = 'Data gudang berhasil diperbarui';

			$this->redirect($this->url->link('catalog/gudang', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle($this->language->get('Master Data Gudang'));

		$this->load->model('catalog/gudang');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $gudang_id) {
				$this->model_catalog_gudang->deleteGudang($gudang_id);
			}

			$this->session->data['success'] = 'Data gudang berhasil dihapus.';

			$this->redirect($this->url->link('catalog/gudang', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
   		$this->data['insert'] = $this->url->link('catalog/gudang/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('catalog/gudang/delete', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['Gudangs'] = array();

		$results = $this->model_catalog_gudang->getGudangs(0);
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";
			print_r($results);
			exit;
		}
		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/gudang/update', 'token=' . $this->session->data['token'] . '&gudang_id=' . $result['gudang_id'], 'SSL')
			);

			$this->data['gudangs'][] = array(
				'gudang_id' => $result['gudang_id'],
				'name'        => $result['nama'],
				'printer'  => $result['printer'],
				'supplier'  => $result['supplier'],
				'action'      => $action,
				'selected'   => isset($this->request->post['selected']) && in_array($result['gudang_id'], $this->request->post['selected']),
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

		$this->template = 'catalog/gudang_list.tpl';
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
			$this->data['error_name'] = array();
		}


		if (!isset($this->request->get['gudang_id'])) {
			$this->data['action'] = $this->url->link('catalog/gudang/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/gudang/update', 'token=' . $this->session->data['token'] . '&gudang_id=' . $this->request->get['gudang_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/gudang', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['gudang_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$gudang_info = $this->model_catalog_gudang->getGudang($this->request->get['gudang_id']);
    	}

		$this->data['token'] = $this->session->data['token'];

		/*if (isset($gudang_info)) {
			foreach ($gudangs as $key => $gudang) {
				if ($gudang['gudang_id'] == $gudang_info['gudang_id']) {
					unset($gudang[$key]);
				}
			}
		}*/
		if(isset($gudang_info)){
			$this->data['gudang'] = $gudang_info;
		}
		/*echo "<pre>";
		print_r($gudang_info);
		exit;*/

		$this->template = 'catalog/gudang_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/gudang')) {
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

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/gudang')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu gudang';
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>
