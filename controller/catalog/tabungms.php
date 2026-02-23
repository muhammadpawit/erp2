<?php
class ControllerCatalogTabungms extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle('Persedian Tabung Stok Kosong');

		$this->load->model('catalog/tabungms');

		$this->getList();
	}

private function getList() {
	if (isset($this->request->get['filter_name'])) {
		$filter_name = $this->request->get['filter_name'];
	} else {
		$filter_name = null;
	}

	if (isset($this->request->get['filter_gas'])) {
		$filter_gas = $this->request->get['filter_gas'];
	} else {
		$filter_gas = null;
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

	if (isset($this->request->get['filter_name'])) {
		$url .= '&filter_name=' . $filter_name;
	}

	if (isset($this->request->get['filter_gas'])) {
		$url .= '&filter_gas=' . $filter_gas;
	}

	if (isset($this->request->get['filter_ukuran_tabung'])) {
		$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
	}


	if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
	}



	$this->data['products'] = array();

	$data = array(
		'filter_name'	  => $filter_name,
		'filter_gas'	=> $filter_gas,
		'filter_ukuran_tabung'	=> $filter_ukuran_tabung,
		'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
		'limit'           => $this->config->get('config_admin_limit')
	);


	$product_total = $this->model_catalog_tabungms->getTotalTabungs($data);

	$results = $this->model_catalog_tabungms->getTabungs($data);

	$this->load->model('catalog/title');

	foreach ($results as $result) {
		$action = array();

/*		$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('catalog/tabungms/update', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
			);
		//}*/
		$action[] = array(
			'text' => 'Kartu Stok',
			'href' => $this->url->link('catalog/tabungms/kartustok', 'token=' . $this->session->data['token'] . '&tabung_id=' . $result['product_id'].'&gudang_id='.$result['gudang_id'] . $url, 'SSL')
		);
		
		$this->data['products'][] = array(
			'id' => $result['id'],
			'quantity'       => $result['quantity'],
			'namatabung'       => $result['tabungname'],
			'jenisgas'	=> $result['namaproduct'],
			'ukurantabung'	=> $result['ukuran'],
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

	if (isset($this->request->get['filter_name'])) {
		$url .= '&filter_name=' . $filter_name;
	}

	if (isset($this->request->get['filter_gas'])) {
		$url .= '&filter_gas=' . $filter_gas;
	}

	if (isset($this->request->get['filter_ukuran_tabung'])) {
		$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
	}




	$pagination = new Pagination();
	$pagination->total = $product_total;
	$pagination->page = $page;
	$pagination->limit = $this->config->get('config_admin_limit');
	$pagination->text = $this->language->get('text_pagination');
	$pagination->url = $this->url->link('catalog/tabungms', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

	$this->data['pagination'] = $pagination->render();

	$this->load->model('catalog/options');
	$this->data['ukurans'] = $this->model_catalog_options->getOptions();

	$this->data['filter_no_tabung'] = $filter_no_tabung;
	$this->data['filter_kelompok_aset'] = $filter_kelompok_aset;
	$this->data['filter_status'] = $filter_status;
	$this->data['filter_ukuran_tabung']	= $filter_ukuran_tabung;
	$this->template = 'catalog/tabungms_list.tpl';
	$this->children = array(
		'common/header',
		'common/footer'
	);

	$this->response->setOutput($this->render());
	}

	public function autocompleteprod() {
		$json = array();

		$this->load->model('catalog/tabungms');

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

			$results = $this->model_catalog_tabungms->getTabungs($data);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['product_id'],
					'text' => strip_tags(html_entity_decode($result['namaproduct'], ENT_QUOTES, 'UTF-8')),

				);
			}


		$this->response->setOutput(json_encode($json));
	}
	public function autocomplete() {
		$json = array();

		//if (isset($this->request->get['filter_name']) ) {
			$this->load->model('catalog/tabungms');

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

			$results = $this->model_catalog_tabungms->getTabungs($data);

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

		$this->document->setTitle('Kartu Stok Tabung MS');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $filter_name;
		}

		if (isset($this->request->get['filter_gas'])) {
			$url .= '&filter_gas=' . $filter_gas;
		}

		if (isset($this->request->get['filter_ukuran_tabung'])) {
			$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['id'])) {
			$url .= '&id=' . $this->request->get['id'];
		}


		$this->data['url'] = $this->url->link('catalog/tabungms/kartustok', 'token=' . $this->session->data['token'] . $url, 'SSL');

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
				$this->redirect($this->url->link('catalog/tabungms', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}

		if (isset($this->request->get['gudang_id'])) {
			$gudang_id = $this->request->get['gudang_id'];
		} else {

			$this->redirect($this->url->link('catalog/tabungms', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		$this->load->model('catalog/tabungms');
		$this->load->model('catalog/kartustoktabungstok');


		$this->data['orders'] = array();

		$data = array(
        'tanggal'     => $tanggal,
				'tabung_id'	=> $tabung_id,
				'gudang_id'	=> $gudang_id,
				'type'	=> $type,
				'start'                  => ($pagekartu - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_catalog_kartustoktabungstok->getTotalKartustoks($data);

		$results = $this->model_catalog_kartustoktabungstok->getKartustoks($data);
		$this->data['cancel'] = $this->url->link('catalog/tabungms', 'token=' . $this->session->data['token'] . $url, 'SSL');


		//print_r($results);
		$this->data['kartustoks']=array();
		foreach ($results as $result) {


      $this->data['kartustoks'][] = array(
				'tanggal'=> date('d F Y',strtotime($result['tgl'])),
				'waktu'	=> date('H:i:s',strtotime($result['tgl'])),
				'stokmasuk'   => $result['stokmasuk'],
				'stokkeluar'	=> $result['stokkeluar'],
				'ket'	=> $result['ket'],
				'saldo'	=> $result['saldo'],
				'invoice'	=> $result['invoice'],
				'quantityawal'	=> $result['quantityawal'],
				'type'	=> $result['type']
			);

		}

		$this->data['heading_title'] = 'Kartu Stok Tabung MS';
		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $filter_name;
		}

		if (isset($this->request->get['filter_gas'])) {
			$url .= '&filter_gas=' . $filter_gas;
		}

		if (isset($this->request->get['filter_ukuran_tabung'])) {
			$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['tabung_id'])) {
			$url .= '&tabung_id=' . $this->request->get['tabung_id'];
		}
		if (isset($this->request->get['gudang_id'])) {
			$url .= '&gudang_id=' . $this->request->get['gudang_id'];
		}
		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}


		$next=$pagekartu+1;

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $pagekartu;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/tabungms/kartustok', 'token=' . $this->session->data['token'] . $url . '&pagekartu='.$next+1, 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['tanggal']=$tanggal;
		$this->data['type']=$type;
		$this->template = 'catalog/kartustok_tabungms.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getForm() {

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
 		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = '';
		}

 		if (!isset($this->request->get['id'])) {
			$this->data['action'] = $this->url->link('catalog/tabungms/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/tabungms/update', 'token=' . $this->session->data['token'].$url . '&id=' . $this->request->get['id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/tabungms', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				if(!empty($this->request->get['id'])){
      		$info = $this->model_catalog_tabungms->getTabung($this->request->get['id']);
				}else{
					$this->redirect($this->url->link('catalog/tabungms', 'token=' . $this->session->data['token'], 'SSL'));
				}
    	}

		$this->data['token'] = $this->session->data['token'];

		if($this->request->server['REQUEST_METHOD'] == 'POST'){
			$this->data['status'] = $this->request->post['status'];
			$this->data['ukuran_tabung'] = $this->request->post['ukuran_tabung'];
			$this->data['hargabeli'] = $this->request->post['hargabeli'];
			$this->data['product_id'] = $this->request->post['product_id'];
		}
		else if(isset($info)){
			$this->data['status'] = $info['status'];
			$this->data['ukuran_tabung'] = $info['ukuran_tabung'];
			$this->data['hargabeli'] = $info['hargabeli'];
			$this->data['product_id'] = $info['product_id'];
			$this->data['namaproduct'] = $info['namaproduct'];

		}else{
			$this->data['status'] = '';
			$this->data['ukuran_tabung'] = '';
			$this->data['hargabeli'] = '';
			$this->data['pemilik'] = 1;
			$this->data['product_id'] = 0;
		}
		//print_r($gudang_info);

		$this->load->model('catalog/options');
		$this->data['ukurans'] = $this->model_catalog_options->getOptions();
		$this->load->model('catalog/kelompokaset');
		$this->data['asets'] = $this->model_catalog_kelompokaset->getKelompokasets();
		$this->template = 'catalog/tabungms_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		/*if (!$this->user->hasPermission('modify', 'catalog/tabungms')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		*/
		/*if(empty($this->request->post['tglpembelian'])){
			$this->error['tglpembelian']='Tanggal pembelian harus diisi';
		}
		if(empty($this->request->post['hargabeli'])){
			$this->error['hargabeli']='Harga beli harus diisi';
		}*/
		if(empty($this->request->post['ukuran_tabung'])){
			$this->error['ukuran_tabung']='Ukuran tabung harus dipilih';
		}
		if(empty($this->request->post['kelompok_aset'])){
			$this->error['kelompok_aset']='Kelompok aset harus dipilih';
		}
		if(empty($this->request->post['product_id'])){
			$this->error['product_id']='Nama produk harus dipilih';
		}

		if(empty($this->request->post['no_tabung'])){
			$this->error['no_tabung']='Nomor tabung harus diisi';
		}else{
			$this->load->model('catalog/tabungms');
			$cek=$this->model_catalog_tabungms->getTabungByNomor($this->request->post['no_tabung']);
			if(!empty($cek)){
				$this->error['no_tabung']='Duplikasi nomor tabung';
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
