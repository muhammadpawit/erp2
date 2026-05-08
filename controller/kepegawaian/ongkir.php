<?php
class ControllerKepegawaianOngkir extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');
		$this->document->setTitle('Penggantian Biaya Ongkir');
		$this->load->model('kepegawaian/ongkir');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/category');
		$this->document->setTitle('Tambah Penggantian Biaya Ongkir');
		$this->load->model('kepegawaian/ongkir');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_kepegawaian_ongkir->addOngkir($this->request->post);
			$this->session->data['success'] = 'Data berhasil ditambahkan.';
			$this->redirect($this->url->link('kepegawaian/ongkir', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/category');
		$this->document->setTitle('Edit Penggantian Biaya Ongkir');
		$this->load->model('kepegawaian/ongkir');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_kepegawaian_ongkir->updateOngkir($this->request->post, array('id' => $this->request->get['id']));
			$this->session->data['success'] = 'Data berhasil diperbarui.';
			$this->redirect($this->url->link('kepegawaian/ongkir', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/category');
		$this->document->setTitle('Hapus Penggantian Biaya Ongkir');
		$this->load->model('kepegawaian/ongkir');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $id) {
				$this->model_kepegawaian_ongkir->updateOngkir(array('hapus' => 1), array('id' => $id));
			}
			$this->session->data['success'] = 'Data berhasil dihapus.';
			$this->redirect($this->url->link('kepegawaian/ongkir', 'token=' . $this->session->data['token'], 'SSL'));
		} elseif (isset($this->request->get['id']) && $this->validateDelete()) {
			$this->model_kepegawaian_ongkir->updateOngkir(array('hapus' => 1), array('id' => $this->request->get['id']));
			$this->session->data['success'] = 'Data berhasil dihapus.';
			$this->redirect($this->url->link('kepegawaian/ongkir', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	public function import() {
		$this->load->model('kepegawaian/ongkir');
		$allowedFileType = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
		
		if (isset($_FILES["file"]) && in_array($_FILES["file"]["type"], $allowedFileType)) {
			$targetPath = DIR_SYSTEM . 'uploads/' . $_FILES['file']['name'];
			move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
			
			$Reader = new SpreadsheetReader($targetPath);
			$sheetCount = count($Reader->sheets());
			
			for ($i = 0; $i < $sheetCount; $i++) {
				if (!$Reader->ChangeSheet($i)) continue;
				
				$a = 1;
				foreach ($Reader as $Row) {
					if ($a > 1) { // Skip header
						if (!empty($Row[1])) { // Nomor column
							$data = array(
								'tanggal'          => date('Y-m-d', strtotime($Row[0])),
								'nomor'            => $Row[1],
								'pelanggan'        => $Row[2],
								'jenis_request'    => $Row[3],
								'no_pembayaran'    => $Row[4],
								'biaya_pengiriman' => str_replace(',', '', $Row[5]),
								'biaya_lain'       => str_replace(',', '', $Row[6]),
								'cabang'           => $Row[7],
								'penjual1'         => $Row[8],
								'penjual2'         => $Row[9]
							);
							$this->model_kepegawaian_ongkir->addOngkir($data);
						}
					}
					$a++;
				}
			}
			$this->session->data['success'] = 'Data berhasil diimport.';
		} else {
			$this->session->data['error_warning'] = 'Invalid file type or no file uploaded.';
		}
		
		$this->redirect($this->url->link('kepegawaian/ongkir', 'token=' . $this->session->data['token'], 'SSL'));
	}

	private function getList() {
		if (isset($this->request->get['filter_tanggal_start'])) {
			$filter_tanggal_start = $this->request->get['filter_tanggal_start'];
		} else {
			$filter_tanggal_start = "";
		}

		if (isset($this->request->get['filter_tanggal_end'])) {
			$filter_tanggal_end = $this->request->get['filter_tanggal_end'];
		} else {
			$filter_tanggal_end = "";
		}

		if (isset($this->request->get['filter_nomor'])) {
			$filter_nomor = $this->request->get['filter_nomor'];
		} else {
			$filter_nomor = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_tanggal_start'])) {
			$url .= '&filter_tanggal_start=' . $this->request->get['filter_tanggal_start'];
		}
		if (isset($this->request->get['filter_tanggal_end'])) {
			$url .= '&filter_tanggal_end=' . $this->request->get['filter_tanggal_end'];
		}
		if (isset($this->request->get['filter_nomor'])) {
			$url .= '&filter_nomor=' . urlencode($this->request->get['filter_nomor']);
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('kepegawaian/ongkir/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('kepegawaian/ongkir/delete', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['import'] = $this->url->link('kepegawaian/ongkir/import', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['ongkirs'] = array();

		$data = array(
			'filter_tanggal_start' => $filter_tanggal_start,
			'filter_tanggal_end'   => $filter_tanggal_end,
			'filter_nomor'         => $filter_nomor,
			'start'                => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                => $this->config->get('config_admin_limit')
		);

		$ongkir_total = $this->model_kepegawaian_ongkir->getTotalOngkirs($data);
		$results = $this->model_kepegawaian_ongkir->getOngkirs($data);

		foreach ($results as $result) {
			$this->data['ongkirs'][] = array(
				'id'               => $result['id'],
				'tanggal'          => date('d M Y', strtotime($result['tanggal'])),
				'nomor'            => $result['nomor'],
				'pelanggan'        => $result['pelanggan'],
				'jenis_request'    => $result['jenis_request'],
				'no_pembayaran'    => $result['no_pembayaran'],
				'biaya_pengiriman' => $this->currency->format($result['biaya_pengiriman']),
				'biaya_lain'       => $this->currency->format($result['biaya_lain']),
				'cabang'           => $result['cabang'],
				'penjual1'         => $result['penjual1'],
				'penjual2'         => $result['penjual2'],
				'edit'             => $this->url->link('kepegawaian/ongkir/update', 'token=' . $this->session->data['token'] . '&id=' . $result['id'], 'SSL'),
				'delete'           => $this->url->link('kepegawaian/ongkir/delete', 'token=' . $this->session->data['token'] . '&id=' . $result['id'], 'SSL')
			);
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

		$pagination = new Pagination();
		$pagination->total = $ongkir_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('kepegawaian/ongkir', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		$this->data['filter_tanggal_start'] = $filter_tanggal_start;
		$this->data['filter_tanggal_end'] = $filter_tanggal_end;
		$this->data['filter_nomor'] = $filter_nomor;

		$this->template = 'kepegawaian/ongkir_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getForm() {
		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (!isset($this->request->get['id'])) {
			$this->data['action'] = $this->url->link('kepegawaian/ongkir/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('kepegawaian/ongkir/update', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('kepegawaian/ongkir', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$ongkir_info = $this->model_kepegawaian_ongkir->getOngkir($this->request->get['id']);
		}

		$fields = array('tanggal', 'nomor', 'pelanggan', 'jenis_request', 'no_pembayaran', 'biaya_pengiriman', 'biaya_lain', 'cabang', 'penjual1', 'penjual2');

		foreach ($fields as $field) {
			if (isset($this->request->post[$field])) {
				$this->data[$field] = $this->request->post[$field];
			} elseif (!empty($ongkir_info)) {
				$this->data[$field] = $ongkir_info[$field];
			} else {
				$this->data[$field] = ($field == 'tanggal') ? date('Y-m-d') : '';
			}
		}

		$this->template = 'kepegawaian/ongkir_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'kepegawaian/ongkir')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu ini.';
		}

		if (empty($this->request->post['nomor'])) {
			$this->error['warning'] = 'Nomor harus diisi.';
		}

		return !$this->error;
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'kepegawaian/ongkir')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu ini.';
		}

		return !$this->error;
	}
}
?>
