<?php
class ControllerCatalogTabungmrbb extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle('Persedian Tabung Milik Relasi');

		$this->load->model('catalog/tabungmrbb');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle('Persedian Tabung Milik Relasi');

		$this->load->model('catalog/tabungmrbb');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_tabungmrbb->addTabung($this->request->post);

			$this->session->data['success'] = 'Data tabung Milik Relasi berhasil ditambahkan';
			$url = '';

			if (isset($this->request->get['filter_no_tabung'])) {
				$url .= '&filter_no_tabung=' . $filter_no_tabung;
			}

			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
			}

			if (isset($this->request->get['filter_pemilik'])) {
				$url .= '&filter_pemilik=' . $filter_pemilik;
			}
			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $filter_product_id;
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $filter_status;
			}

			if (isset($this->request->get['filter_ukuran_tabung'])) {
				$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/tabungmrbb', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle($this->language->get('Persedian Tabung Milik Relasi'));

		$this->load->model('catalog/tabungmrbb');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_tabungmrbb->editTabung($this->request->get['id'], $this->request->post);

			$this->session->data['success'] = 'Data tabung Milik Relasi berhasil diperbarui';
			$url = '';

			if (isset($this->request->get['filter_no_tabung'])) {
				$url .= '&filter_no_tabung=' . $filter_no_tabung;
			}

			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
			}

			if (isset($this->request->get['filter_pemilik'])) {
				$url .= '&filter_pemilik=' . $filter_pemilik;
			}

			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $filter_product_id;
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $filter_status;
			}

			if (isset($this->request->get['filter_ukuran_tabung'])) {
				$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/tabungmrbb', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle($this->language->get('Persedian Tabung Milik Relasi'));

		$this->load->model('catalog/tabungmrbb');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $gudang_id) {
				$this->model_catalog_tabungmrbb->deleteTabung($gudang_id);
			}

			$this->session->data['success'] = 'Data tabung Milik Relasi berhasil dihapus.';
			$url = '';

			if (isset($this->request->get['filter_no_tabung'])) {
				$url .= '&filter_no_tabung=' . $filter_no_tabung;
			}

			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
			}

			if (isset($this->request->get['filter_pemilik'])) {
				$url .= '&filter_pemilik=' . $filter_pemilik;
			}

			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $filter_product_id;
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $filter_status;
			}

			if (isset($this->request->get['filter_ukuran_tabung'])) {
				$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/tabungmr', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
	if (isset($this->request->get['filter_no_tabung'])) {
		$filter_no_tabung = $this->request->get['filter_no_tabung'];
	} else {
		$filter_no_tabung = null;
	}

	if (isset($this->request->get['filter_kelompok_aset'])) {
		$filter_kelompok_aset = $this->request->get['filter_kelompok_aset'];
	} else {
		$filter_kelompok_aset = null;
	}
	if (isset($this->request->get['filter_pemilik'])) {
		$filter_pemilik = $this->request->get['filter_pemilik'];
	} else {
		$filter_pemilik = null;
	}

	if (isset($this->request->get['filter_product_id'])) {
		$filter_product_id = $this->request->get['filter_product_id'];
	} else {
		$filter_product_id= null;
	}

	if (isset($this->request->get['filter_status'])) {
		$filter_status = $this->request->get['filter_status'];
	} else {
		$filter_status = null;
	}

	if (isset($this->request->get['filter_ukuran_tabung'])) {
		$filter_ukuran_tabung = $this->request->get['filter_ukuran_tabung'];
	} else {
		$filter_ukuran_tabung = null;
	}

	if (isset($this->request->get['page'])) {
		$page = $this->request->get['page'];
	} else {
		$page = 1;
	}

	$url = '';

	if (isset($this->request->get['filter_no_tabung'])) {
		$url .= '&filter_no_tabung=' . $filter_no_tabung;
	}

	if (isset($this->request->get['filter_kelompok_aset'])) {
		$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
	}

	if (isset($this->request->get['filter_pemilik'])) {
		$url .= '&filter_pemilik=' . $filter_pemilik;
	}

	if (isset($this->request->get['filter_product_id'])) {
		$url .= '&filter_product_id=' . $filter_product_id;
	}

	if (isset($this->request->get['filter_status'])) {
		$url .= '&filter_status=' . $filter_status;
	}

	if (isset($this->request->get['filter_ukuran_tabung'])) {
		$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
	}

	if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
	}



	$this->data['insert'] = $this->url->link('catalog/tabungmrbb/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
	$this->data['delete'] = $this->url->link('catalog/tabungmrbb/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

	$this->data['products'] = array();

	$data = array(
		'filter_no_tabung'	  => $filter_no_tabung,
		'filter_kelompok_aset'	=> $filter_kelompok_aset,
		'filter_status'=> $filter_status,
		'filter_ukuran_tabung'	=> $filter_ukuran_tabung,
		'filter_pemilik'	=> $filter_pemilik,
		'filter_product_id'	=> $filter_product_id,
		'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
		'limit'           => $this->config->get('config_admin_limit')
	);


	$product_total = $this->model_catalog_tabungmrbb->getTotalTabungs($data);

	$results = $this->model_catalog_tabungmrbb->getTabungs($data);

	$this->load->model('catalog/title');

	foreach ($results as $result) {
		$action = array();

/*		$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('catalog/tabungmr/update', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
			);
		//}*/
		$action[] = array(
			'text' => 'Kartu Stok',
			'href' => $this->url->link('catalog/tabungmrbb/kartustok', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
		);
		/*$action[] = array(
				'text' => 'Koreksi Stok',
				'href' => $this->url->link('catalog/tabungmr/koreksi', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
			);*/

			if(!empty($result['title'])){
				$pemilik=$this->model_catalog_title->getTitle($result['title']).' '.$result['namecustomer'];
			}else{
				$pemilik=$result['namecustomer'];
			}
		$this->data['products'][] = array(
			'id' => $result['id'],
			'quantity'       => $result['quantity'],
			'jenisgas'	=> $result['namaproduct'],
			'pemilik'       => $pemilik,
			'ukurantabung'	=> $result['ukuran'],
			'statuss'	=> $result['status'],
			'status'	=> $result['status']==1?'Tersedia':($result['status'] == 2?'Tidak Tersedia':'Hilang'),
			'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
			'action'     => $action
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

	$url = '';

	if (isset($this->request->get['filter_no_tabung'])) {
		$url .= '&filter_no_tabung=' . $filter_no_tabung;
	}

	if (isset($this->request->get['filter_kelompok_aset'])) {
		$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
	}

	if (isset($this->request->get['filter_pemilik'])) {
		$url .= '&filter_pemilik=' . $filter_pemilik;
	}

	if (isset($this->request->get['filter_product_id'])) {
		$url .= '&filter_product_id=' . $filter_product_id;
	}

	if (isset($this->request->get['filter_status'])) {
		$url .= '&filter_status=' . $filter_status;
	}

	if (isset($this->request->get['filter_ukuran_tabung'])) {
		$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
	}


	$pagination = new Pagination();
	$pagination->total = $product_total;
	$pagination->page = $page;
	$pagination->limit = $this->config->get('config_admin_limit');
	$pagination->text = $this->language->get('text_pagination');
	$pagination->url = $this->url->link('catalog/tabungmrbb', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

	$this->data['pagination'] = $pagination->render();

	$this->load->model('catalog/options');
	$this->data['ukurans'] = $this->model_catalog_options->getOptions();

	$this->data['filter_no_tabung'] = $filter_no_tabung;
	$this->data['filter_kelompok_aset'] = $filter_kelompok_aset;
	$this->data['filter_status'] = $filter_status;
	$this->data['filter_ukuran_tabung']	= $filter_ukuran_tabung;
	$this->template = 'catalog/tabungmrbb_list.tpl';
	$this->children = array(
		'common/header',
		'common/footer'
	);

	$this->response->setOutput($this->render());
	}

	private function validateDelete() {
	/*	if (!$this->user->hasPermission('modify', 'catalog/tabungmrbb')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu tabung Milik Relasi';
		}
*/
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function autocompleteprod() {
		$json = array();

		$this->load->model('catalog/tabungmr');

			if (isset($this->request->get['q'])) {
				$filter_name = $this->request->get['q'];
			} else {
				$filter_name = null;
			}
			if (isset($this->request->get['filter_customer_id'])) {
				$filter_customer_id = $this->request->get['filter_customer_id'];
			} else {
				$filter_customer_id = null;
			}

			if (isset($this->request->get['tttk'])) {
				$tttk = $this->request->get['tttk'];
			} else {
				$tttk = null;
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'filter_name'	  => $filter_name,
				'filter_pemilik'	=> $filter_customer_id,
				'start'               => 0,
				'limit'               => $limit
			);
			$offset=0;
			$limit=$limit;

			$results = $this->model_catalog_tabungmrbb->getTabungs($data);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['product_id'],
					'text' => strip_tags(html_entity_decode($result['namaproduct'], ENT_QUOTES, 'UTF-8')),

				);
			}


		$this->response->setOutput(json_encode($json));
	}
	public function autocompleteprodso() {
		$json = array();

		$this->load->model('sale/tttkmrbb');

			if (isset($this->request->get['q'])) {
				$filter_name = $this->request->get['q'];
			} else {
				$filter_name = null;
			}
			if (isset($this->request->get['filter_customer_id'])) {
				$filter_customer_id = $this->request->get['filter_customer_id'];
			} else {
				$filter_customer_id = null;
			}

			if (isset($this->request->get['tttk'])) {
				$tttk = $this->request->get['tttk'];
			} else {
				$tttk = null;
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'product.name'	  => $filter_name,
				'tabungmr_bb.customer_id'	=> $filter_customer_id,
				'tttk_tabungmrbb.tttk_id'	=> $tttk,
				//'start'               => 0,
				//'limit'               => $limit
			);
			$offset=0;
			$limit=$limit;

			$results = $this->model_sale_tttkmrbb->getPenjualanProducts($data);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['product_id'],
					'text' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}


		$this->response->setOutput(json_encode($json));
	}
	public function autocomplete() {
		$json = array();

		//if (isset($this->request->get['filter_name']) ) {
			$this->load->model('catalog/tabungmrbb');

			if (isset($this->request->get['filter_no_tabung'])) {
				$filter_no_tabung = $this->request->get['filter_no_tabung'];
			} else {
				$filter_no_tabung = null;
			}

			if (isset($this->request->get['filter_name'])) {
				$filter_no_tabung = $this->request->get['filter_name'];
			} else {
				$filter_no_tabung = null;
			}

			if (isset($this->request->get['q'])) {
				$filter_no_tabung = $this->request->get['q'];
			} else {
				$filter_no_tabung = null;
			}

			if (isset($this->request->get['jenisgas'])) {
				$filter_product_id = $this->request->get['jenisgas'];
			} else {
				$filter_product_id = null;
			}

			if (isset($this->request->get['status'])) {
				$filter_status = $this->request->get['status'];
			} else {
				$filter_status = null;
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'filter_no_tabung'	  => $filter_no_tabung,
				'filter_status'	  => $fiter_status,
				'filter_product_id'	  => $filter_product_id,
				'filter_status'	=> $filter_status,
				'start'               => 0,
				'limit'               => $limit
			);

			$results = $this->model_catalog_tabungmrbb->getTabungs($data);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['id'],
					'text'       => strip_tags(html_entity_decode($result['no_tabung'], ENT_QUOTES, 'UTF-8')),

				);
			}


		$this->response->setOutput(json_encode($json));
	}

	public function kartustok() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Kartu Stok Tabung MR Bahan Baku');

		$url = '';

		if (isset($this->request->get['filter_no_tabung'])) {
			$url .= '&filter_no_tabung=' . $filter_no_tabung;
		}

		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
		}

		if (isset($this->request->get['filter_pemilik'])) {
			$url .= '&filter_pemilik=' . $filter_pemilik;
		}

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $filter_product_id;
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $filter_status;
		}

		if (isset($this->request->get['filter_ukuran_tabung'])) {
			$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['product_id'])) {
			$url .= '&tabung_id=' . $this->request->get['tabung_id'];
		}

		$this->data['url'] = $this->url->link('catalog/tabungmrbb/kartustok', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['pagekartu'])) {
			$url .= '&pagekartu=' . $this->request->get['pagekartu'];
		}


		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}

		if (isset($this->request->get['tabung_id'])) {
			$tabung_id = $this->request->get['tabung_id'];
		} else {

			if (isset($this->request->get['id'])) {
				$tabung_id = $this->request->get['id'];
			} else {
				$this->redirect($this->url->link('catalog/tabungmrbb', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}

		if (isset($this->request->get['pagekartu'])) {
			$pagekartu = $this->request->get['pagekartu'];
		} else {
			$pagekartu = 1;
		}

		if (isset($this->request->get['tanggal'])) {
			$tanggal = $this->request->get['tanggal'];
		} else {
			$tanggal = '';
		}

		if (isset($this->request->get['type'])) {
			$type = $this->request->get['type'];
		} else {
			$type = '';
		}

   	if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('catalog/tabungmrbb');
		$this->load->model('catalog/kartustoktabungmrbb');


		$this->data['orders'] = array();

		$data = array(
        'tanggal'     => $tanggal,
				'tabung_id'	=> $tabung_id,
				'type'	=> $type,
				'start'                  => ($pagekartu - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_catalog_kartustoktabungmrbb->getTotalKartustoks($data);

		$results = $this->model_catalog_kartustoktabungmrbb->getKartustoks($data);
		$this->data['cancel'] = $this->url->link('catalog/tabungmrbb', 'token=' . $this->session->data['token'] . $url, 'SSL');


		//print_r($results);
		$this->data['kartustoks']=array();
		foreach ($results as $result) {

			if($result['jenistransaksi'] == 3){
				$urlref=$this->url->link($result['tabel_ref'].'/tampil', 'token=' . $this->session->data['token'] . '&id='.$result['idref'].'&view=1', 'SSL');
			}else{
				$urlref=$this->url->link($result['tabel_ref'].'/tampil', 'token=' . $this->session->data['token'] . '&order_id='.$result['idref'].'&view=1', 'SSL');
			}
			$this->data['kartustoks'][] = array(
						'tanggal'=> date('d F Y',strtotime($result['tgl'])),
						'waktu'	=> date('H:i:s',strtotime($result['tgl'])),
						'stokmasuk'   => $result['stokmasuk'],
						'stokkeluar'	=> $result['stokkeluar'],
						'ket'	=> $result['ket'],
						'saldo'	=> $result['saldo'],
						'invoice'	=> $result['invoice'],
						'quantityawal'	=> $result['quantityawal'],
						'type'	=> $result['type'],
						'urlref'=> $urlref,
						'jenistransaksi'	=> $result['jenistransaksi'],
					);

		}

		$this->data['heading_title'] = 'Kartu Stok Tabung MR Bahan Baku';
		$this->data['token'] = $this->session->data['token'];



		$url = '';

		if (isset($this->request->get['filter_no_tabung'])) {
			$url .= '&filter_no_tabung=' . $filter_no_tabung;
		}

		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
		}

		if (isset($this->request->get['filter_pemilik'])) {
			$url .= '&filter_pemilik=' . $filter_pemilik;
		}

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $filter_product_id;
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $filter_status;
		}

		if (isset($this->request->get['filter_ukuran_tabung'])) {
			$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
		}

		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}

		if (isset($this->request->get['id'])) {
			$url .= '&id=' . $this->request->get['id'];
		}


		$next=$pagekartu+1;

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $pagekartu;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/tabungmrbb/kartustok', 'token=' . $this->session->data['token'] . $url . '&pagekartu={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['tanggal']=$tanggal;
		$this->data['type']=$type;
		$this->template = 'catalog/kartustok_tabungmrbb.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function detail(){
		$hasil = array();

		$this->load->model('catalog/tabungmrbb');
		if(isset($this->request->get['product_id'])){
			if(!empty($this->request->get['product_id'])){
				$product_id=$this->request->get['product_id'];
				$customer_id=$this->request->get['customer_id'];
				$hasil=$this->model_catalog_tabungmrbb->getTabungByProduct($product_id,$customer_id);


			}
		}
		$this->response->setOutput(json_encode($hasil));


	}

}
?>
