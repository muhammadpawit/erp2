<?php
class ControllerCatalogBahanbaku extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle('Persediaan Bahan Baku');

		$this->load->model('catalog/bahanbaku');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle('Persediaan Bahan Baku');

		$this->load->model('catalog/bahanbaku');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_bahanbaku->addProduct($this->request->post);

			$this->session->data['success'] = 'Data bahan baku berhasil ditambahkan';

			$this->redirect($this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle($this->language->get('Persediaan Bahan Baku'));

		$this->load->model('catalog/bahanbaku');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_bahanbaku->editProduct($this->request->get['id'], $this->request->post);

			$this->session->data['success'] = 'Data bahan baku berhasil diperbarui';

			$this->redirect($this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle($this->language->get('Persediaan Bahan Baku'));

		$this->load->model('catalog/bahanbaku');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $gudang_id) {
				$this->model_catalog_bahanbaku->deleteProduct($gudang_id);
			}

			$this->session->data['success'] = 'Data bahan baku berhasil dihapus.';

			$this->redirect($this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'], 'SSL'));
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

	$this->data['url'] = $this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'], 'SSL');

	$this->data['insert'] = $this->url->link('catalog/bahanbaku/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
	$this->data['copy'] = $this->url->link('catalog/bahanbaku/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
	$this->data['delete'] = $this->url->link('catalog/bahanbaku/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

	$this->data['products'] = array();

	$data = array(
		'filter_name'	  => $filter_name,
		'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
		'limit'           => $this->config->get('config_admin_limit')
	);

	$this->load->model('catalog/satuan');

	$product_total = $this->model_catalog_bahanbaku->getTotalProducts($data);

	$results = $this->model_catalog_bahanbaku->getProducts($data);

	foreach ($results as $result) {
		$action = array();
		$action[] = array(
			'text' => 'Edit',
			'href' => $this->url->link('catalog/bahanbaku/update', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
		);
		$action[] = array(
			'text' => 'Konversi Satuan',
			'href' => $this->url->link('catalog/bahanbaku/konversi', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
		);
		$action[] = array(
			'text' => 'Kartu Stok',
			'href' => $this->url->link('catalog/bahanbaku/kartustok', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
		);

		$action[] = array(
			'text' => 'Stok Opname',
			'href' => $this->url->link('catalog/bahanbaku/stokopname', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
		);
		if($result['quantity'] == 0){
		$action[] = array(
			'text' => 'Input Stok Awal',
			'href' => $this->url->link('catalog/bahanbaku/stokAwal', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
		);
		}


		$this->data['products'][] = array(
			'id' => $result['id'],
			//'stok' => $this->model_gudang_product->cekStok($result['product_id']),
			'name'       => $result['name'],
			'level'       => $result['level'],
			'quantity'   => $result['quantity'].' '.$this->model_catalog_satuan->getTitle($result['satuan']),
			'date_added'	=> date('d F Y',strtotime($result['date_added'])),
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
		$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
	}

	if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
	}

	$url = '';

	if (isset($this->request->get['filter_name'])) {
		$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
	}



	$pagination = new Pagination();
	$pagination->total = $product_total;
	$pagination->page = $page;
	$pagination->limit = $this->config->get('config_admin_limit');
	$pagination->text = $this->language->get('text_pagination');
	$pagination->url = $this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

	$this->data['pagination'] = $pagination->render();

	$this->data['filter_name'] = $filter_name;
	$this->template = 'catalog/bahanbaku_list.tpl';
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


		if (!isset($this->request->get['id'])) {
			$this->data['action'] = $this->url->link('catalog/bahanbaku/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/bahanbaku/update', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$info = $this->model_catalog_bahanbaku->getProduct($this->request->get['id']);
    	}

		$this->data['token'] = $this->session->data['token'];

		/*if (isset($gudang_info)) {
			foreach ($gudangs as $key => $gudang) {
				if ($gudang['gudang_id'] == $gudang_info['gudang_id']) {
					unset($gudang[$key]);
				}
			}
		}*/
		$this->load->model('catalog/satuan');

	$this->data['satuans']=$this->model_catalog_satuan->getOptions();

		if(isset($info)){
			$this->data['name'] = $info['name'];
			$this->data['quantity'] = $info['quantity'];
			$this->data['level'] = $info['level'];
			$this->data['satuan'] = $info['satuan'];


		}else{
			$this->data['name'] = '';
			$this->data['quantity'] = 0;
			$this->data['level'] = 0;
			$this->data['satuan'] = 0;
		}
		//print_r($gudang_info);

		$this->template = 'catalog/bahanbaku_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		/*if (!$this->user->hasPermission('modify', 'catalog/bahanbaku')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		*/


		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = "Mohon cek kembali form Anda.";
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/bahanbaku')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu bahan baku';
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) ) {
			$this->load->model('catalog/bahanbaku');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
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

				'start'               => 0,
				'limit'               => $limit
			);

			$results = $this->model_catalog_bahanbaku->getProducts($data);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}
	public function autocompleteprod() {
		$json = array();

		$this->load->model('catalog/bahanbaku');

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
			'filter_name'	  => array('LIKE',$filter_name),
			'start'               => 0,
				'limit'               => $limit
			);
			$offset=0;
			$limit=$limit;

			$results = $this->model_catalog_bahanbaku->getProducts($data);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['id'],
					'text' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}


		$this->response->setOutput(json_encode($json));
	}

	public function detail(){
		$hasil = array();

		$this->load->model('catalog/bahanbaku');
		if(isset($this->request->get['product_id'])){
			if(!empty($this->request->get['product_id'])){
				$product_id=$this->request->get['product_id'];
				$hasil=$this->model_catalog_bahanbaku->getProduct($product_id);
				$column=array();
				$hasil['net_cost']=$hasil['hargabeli'];

				$customer_id=$this->request->get['customer_id'];

				$lastprice=$this->model_catalog_bahanbaku->getLastProductPrice($product_id,$customer_id);
				if(!empty($lastprice)){
					$hasil['price']=$lastprice['price'];
				}else{
					$hasil['price']=0;
				}
				$hasil['diskon']=0;



			}
		}
		$this->response->setOutput(json_encode($hasil));


	}

	public function stokAwal(){
		//$this->load->model('gudang/product');
		$this->load->model('catalog/bahanbaku');


		$this->document->setTitle('Input Stok Awal Bahan Baku');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['id'])) {
				$id=$this->request->get['id'];
			}
		else{
			$this->redirect($this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));

		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormStok()) {

			$this->model_catalog_bahanbaku->addStokAwal($this->request->post);
	  	$this->session->data['success'] = 'Success: Stok berhasil ditambahkan.';

				$url = '';

				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				$this->redirect($this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}



			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->data['action'] = $this->url->link('catalog/bahanbaku/stokAwal', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'] . $url, 'SSL');

			$this->data['cancel'] = $this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->error)) {
				$this->data['error'] = $this->error;
			} else {
				$this->data['error'] = '';
			}

    	//if(empty($this->request->post)){
			$this->data['productdesc']=$this->model_catalog_bahanbaku->getProduct($id);
			$this->data['id']=$id;


		$this->template = 'catalog/stokawal_bahanbaku.tpl';
		$this->children = array(
					'common/header',
					'common/footer'
				);
			$this->response->setOutput($this->render());

	}
	public function validateFormStok(){
	/*	if (!$this->user->hasPermission('modify', 'catalog/bahanbaku')) {
      		$this->error['warning'] = 'Anda tidak memiliki ijin untuk memodifikasi data stok bahan baku.';
    	}*/
    	if(empty($this->request->post['qty'])){
    		$this->error['qty'] = 'Data quantity harus diisi';

    	}
    	if($this->request->post['qty'] < 1){
    		$this->error['qty'] = 'Data quantity harus lebih dari 0';

    	}
			if(!is_numeric($this->request->post['qty'])){
    		$this->error['qty'] = 'Data quantity harus berupa angka';

    	}




    	if (!$this->error) {
			return true;
    	} else {
      		return false;
    	}
	}
	public function kartustok() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Kartu Stok Bahanbaku');

			$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
		}

		$this->data['url'] = $this->url->link('catalog/bahanbaku/kartustok', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['pagekartu'])) {
			$url .= '&pagekartu=' . $this->request->get['pagekartu'];
		}


		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}

		if (isset($this->request->get['id'])) {
			$product_id = $this->request->get['id'];
		} else {
			$this->redirect($this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		$this->load->model('catalog/product');
		$this->load->model('gudang/kartustok');


		$this->data['orders'] = array();

		$data = array(
        'tanggal'     => $tanggal,
				'product_id'	=> $product_id,
				'type'	=> $type,
				'start'                  => ($pagekartu - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_gudang_kartustok->getTotalKartustokGlobals('kartustok_bahanbaku',$data);

		$results = $this->model_gudang_kartustok->getKartustokGlobals('kartustok_bahanbaku',$data);
		$this->data['cancel'] = $this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['kartustoks']=array();
		foreach ($results as $result) {


      $this->data['kartustoks'][] = array(
				'tanggal'=> date('d F Y',strtotime($result['tglawal'])),
				'waktuawal'	=> date('H:i:s',strtotime($result['tglawal'])),
				'waktuakhir'	=> date('H:i:s',strtotime($result['tglakhir'])),
				'name'   => $result['product_name'],
				'levelawal'   => $result['levelawal'],
				'levelakhir'	=> $result['levelakhir'],
				'isi'	=> $result['perubahan'],
				'ket'	=> $result['ket'],
				'ref'	=> $result['ref'],
				'typename'	=> $result['type_name'],
				'type'	=> $result['type']
			);

		}

		$this->data['heading_title'] = 'Kartu Stok Bahanbaku';
		$this->data['token'] = $this->session->data['token'];



		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}



		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
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
		$pagination->url = $this->url->link('catalog/bahanbaku/kartustok', 'token=' . $this->session->data['token'] . $url . '&pagekartu={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['tanggal']=$tanggal;
		$this->data['type']=$type;
		$this->template = 'catalog/kartustok_bahanbaku.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function stokopname(){
		//$this->load->model('gudang/product');
		$this->load->model('catalog/bahanbaku');


		$this->document->setTitle('Stok Opname Bahan Baku');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['id'])) {
				$id=$this->request->get['id'];
			}
		else{
			$this->redirect($this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));

		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormStok()) {

			$this->model_catalog_bahanbaku->stokOpname($this->request->post);
	  	$this->session->data['success'] = 'Success: Data Stok berhasil diperbarui.';

				$url = '';

				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				$this->redirect($this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}



			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->data['action'] = $this->url->link('catalog/bahanbaku/stokopname', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'] . $url, 'SSL');

			$this->data['cancel'] = $this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->error)) {
				$this->data['error'] = $this->error;
			} else {
				$this->data['error'] = '';
			}

    	//if(empty($this->request->post)){
			$this->data['productdesc']=$this->model_catalog_bahanbaku->getProduct($id);
			$this->data['id']=$id;


		$this->template = 'catalog/stokopname_bahanbaku.tpl';
		$this->children = array(
					'common/header',
					'common/footer'
				);
			$this->response->setOutput($this->render());

	}

	public function konversi() {
    	$this->load->language('catalog/product');

    	$this->document->setTitle("Bahan Baku Produksi");

		$this->load->model('catalog/bahanbaku');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			//print_r($this->request->post['product_options']);
			$this->model_catalog_bahanbaku->addKonversi($this->request->get['id'], $this->request->post['product_options']);

			$this->session->data['success'] = 'Nilai konversi satuan bahan baku berhasil diperbarui';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

    	if(!isset($this->request->get['id'])){
				$this->redirect($this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				if(empty($this->request->get['id'])){
					$this->redirect($this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}else{
					$this->data['token'] = $this->session->data['token'];
					$this->load->model('catalog/satuan');

						$this->data['options'] = $this->model_catalog_satuan->getOptions();

						if (isset($this->request->post['product_options'])) {
							$this->data['product_options'] = $this->request->post['product_options'];
						} elseif (isset($this->request->get['id'])) {
							$this->data['product_options'] = $this->model_catalog_bahanbaku->getKonversi($this->request->get['id']);
						} else {
							$this->data['product_options'] = array();
						}

						//print_r($this->data['product_options']);
				  	$this->data['action'] = $this->url->link('catalog/bahanbaku/konversi', 'token=' . $this->session->data['token'].'&id='.$this->request->get['id'] . $url, 'SSL');
						$this->data['cancel'] = $this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');

						$this->template = 'catalog/konversi.tpl';
						$this->children = array(
							'common/header',
							'common/footer'
						);

						$this->response->setOutput($this->render());
				}
			}
  	}
		public function hapusoption(){
			$product_option_id=$this->request->get['product_option_id'];
			$product_id=$this->request->get['product_id'];

			$this->load->model('catalog/bahanbaku');
			$d=$this->model_catalog_bahanbaku->deleteKonversi($product_option_id);

			echo 1;
		}

}
?>
