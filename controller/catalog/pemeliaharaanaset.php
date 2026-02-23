<?php
class ControllerCatalogPemeliharaanaset extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Pemeliharaan Aset');

		$this->load->model('catalog/aset');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Pemeliharaan Aset');

		$this->load->model('catalog/aset');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_aset->addPemeliharaan($this->request->post);

			$this->session->data['success'] = 'Data Pemeliharaan Aset berhasil ditambahkan.';
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('catalog/pemeliharaanaset', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Pemeliharaan Aset');

		$this->load->model('catalog/aset');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_aset->updatePemeliharaan($this->request->post, array('id'=>$this->request->get['id']));

			$this->session->data['success'] = 'Data Pemeliharaan Aset berhasil diperbarui';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/pemeliharaanaset', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}



	public function delete() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Pemeliharaan Aset');

		$this->load->model('catalog/aset');

		if (isset($this->request->get['id']) && $this->validateDelete()) {
			/*foreach ($this->request->post['selected'] as $id) {

				$this->model_catalog_aset->batalkanPemeliharaan($id);
			}*/
			$pem=$this->model_catalog_aset->getPemeliharaan(array('id'=>$this->request->get['id']));
			if($pem['status'] == 1){
				$this->model_catalog_aset->batalkanPemeliharaan($this->request->get['id']);
				$this->session->data['success'] = 'Data Pemeliharaan Aset berhasil dibatalkan';
			}else{
				$this->session->data['warning'] = 'Data Pemeliharaan Aset gagal dibatalkan';
			}

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			$this->redirect($this->url->link('catalog/pemeliharaanaset', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		if (isset($this->request->get['filter_kelompok_aset'])) {
			$filter_kelompok_aset = $this->request->get['filter_kelompok_aset'];
		} else {
			$filter_kelompok_aset = null;
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

		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('catalog/pemeliharaanaset', 'token=' . $this->session->data['token'] . $url, 'SSL');

   	$this->data['insert'] = $this->url->link('catalog/pemeliharaanaset/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/pemeliharaanaset/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['tasets'] = array();

		$column=array('aset.aset_id','aset.name as name','pemeliharaan.name as pemeliharaanname','pemeliharaan_aset.*');
		$join=array();
		$join[]=array(
			'tablename'	=> 'aset',
			'firsttable'	=> 'pemeliharaan_aset.aset_id',
			'secondtable'	=> 'aset.aset_id',
		);
		$join[]=array(
			'tablename'	=> 'pemeliharaan',
			'firsttable'	=> 'pemeliharaan_aset.pemeliharaan_id',
			'secondtable'	=> 'pemeliharaan.id',
		);
		/*$data = array(
			'aset.name'	  => array('LIKE',$filter_name),
			'pemeliharaan_id'	=> $filter_kelompok_aset,
			'pemeliharaan_aset.hapus'	=>array('<',1),

			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);*/
		$data = array(
			'filter_name'	  => $filter_name,
			'pemeliharaan_id'	=> $filter_kelompok_aset,
			'pemeliharaan_aset.hapus'	=>array('<',1),

			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);
		
		/*$data = array(
			'aset.name'	  => array('LIKE',$filter_name),
			'pemeliharaan_id'	=> $filter_kelompok_aset,
			'pemeliharaan_aset.hapus'	=>array('<',1),

			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');
		*/
		//$results = $this->model_catalog_aset->getPemeliharaans($column,$join,$data,$orderby,$limit,$offset);
		$results = $this->model_catalog_aset->gets($data);
		if(isset($filter_name)!=null)
		{
			//$product_total = $this->model_catalog_aset->totalPemeliharaans();
		}
		else
		{
			//$product_total = $this->model_catalog_aset->totalPemeliharaans($data);
		}
		//print_r($results);
		foreach ($results as $result) {
			$action = array();

			/*$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('catalog/pemeliharaanaset/update', 'token=' . $this->session->data['token'] . '&aset_id=' . $result['aset_id'], 'SSL')
			);*/

			if($result['status'] == 1){

			$action[] = array(
				'text' => 'Batalkan',
				'href' => $this->url->link('catalog/pemeliharaanaset/delete', 'token=' . $this->session->data['token'] . '&id=' . $result['id'], 'SSL')
			);
			}

		//	$cek= $this->model_catalog_aset->cekOption($result['id']);

			$this->data['tasets'][] = array(
				'id' => $result['id'],
				'name'        => $result['name'],
				'pemeliharaanname'	=> $result['pemeliharaanname'],
				'status'        => $result['status'] == 1?'Belum Dibayar':($result['status'] == 2?'Dibayar Sebagian':($result['status'] == 3?'Lunas':'Dibatalkan')),
				'tanggal'	=> date('d/m/y',strtotime($result['tanggal'])),
				'biaya'	=> $this->currency->format($result['biaya']),
				'totalbayar'	=> $this->currency->format($result['totalbayar']),
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'      => $action
			);
		}


		if (isset($this->session->data['warning'])) {
			$this->data['warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
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

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/pemeliharaanaset', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->load->model('catalog/pemeliharaan');
		$this->data['asets'] = $this->model_catalog_pemeliharaan->getOptions();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_kelompok_aset']	= $filter_kelompok_aset;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'catalog/pemeliharaanaset_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getForm() {

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

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



		$this->data['action'] = $this->url->link('catalog/pemeliharaanaset/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['cancel'] = $this->url->link('catalog/pemeliharaanaset', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['aset_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$option_info = $this->model_catalog_aset->getAset(array('aset_id'	=> $this->request->get['aset_id']));
    	}

		$this->data['token'] = $this->session->data['token'];




		$this->load->model('catalog/pemeliharaan');
		$this->data['asets'] = $this->model_catalog_pemeliharaan->getOptions();

		$this->load->model('keuangan/bank');
		$this->data['banks'] = $this->model_keuangan_bank->getBanks();

		$this->template = 'catalog/pemeliharaanaset_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getFormAset() {

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

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



		$this->data['action'] = $this->url->link('catalog/pemeliharaanaset/updateinfo', 'token=' . $this->session->data['token'].$url. '&aset_id=' . $this->request->get['aset_id'], 'SSL');

		$this->data['cancel'] = $this->url->link('catalog/pemeliharaanaset', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['aset_id']) ) {
      		$option_info = $this->model_catalog_aset->getAset(array('aset_id'	=> $this->request->get['aset_id']));
    	}else{
				$this->redirect($this->url->link('catalog/pemeliharaanaset', 'token=' . $this->session->data['token'].$url, 'SSL'));
			}
		$this->data['name'] = $option_info['name'];

		$this->data['token'] = $this->session->data['token'];


		if (!empty($this->request->post)) {
			$this->data['tglpembelian'] = $this->request->post['tglpembelian'];
			$this->data['hargabeli'] = $this->request->post['hargabeli'];

		} elseif (!empty($option_info)) {
			$this->data['name'] = $option_info['name'];
			$this->data['tglpembelian'] = $option_info['tglpembelian'];
			$this->data['hargabeli'] = $option_info['hargabeli'];

		} else {
			$this->data['tglpembelian'] = '';
			$this->data['hargabeli'] = '';

		}

		$this->load->model('catalog/kelompokaset');
		$this->data['asets'] = $this->model_catalog_kelompokaset->getKelompokasets();
		$this->data['aktivas'] = $this->model_catalog_kelompokaset->getAktivas();


		$this->template = 'catalog/asetinfo_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = 'Mohon cek kembali form Anda.';
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateFormInfo() {
		/*if (!$this->user->hasPermission('modify', 'catalog/aset')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Aset';
		}*/

		if(empty($this->request->post['tglpembelian'])){
			$this->error['tglpembelian']='Tanggal pembelian harus diisi';
		}
		if(!is_numeric($this->request->post['hargabeli'])){
			$this->error['hargabeli']='Harga beli harus berupa angka';
		}else{
			if($this->request->post['hargabeli'] < 0){
				$this->error['hargabeli']='Harga beli harus lebih dari 0';
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		/*if (!$this->user->hasPermission('modify', 'catalog/aset')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Aset';
		}*/

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function autocomplete() {
		$json = array();

		//if (isset($this->request->get['filter_name']) ) {
			$this->load->model('catalog/aset');

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
			'name'	  => array('LIKE',$filter_name),
			'tglpembelian'	=>'1970-01-01'
				//'start'               => 0,
				//'limit'               => $limit
			);
			$offset=0;
			$limit=$limit;

			$results = $this->model_catalog_aset->getAsets(array(),array(),$data,array(),$limit,$offset);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['aset_id'],
					'text'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')).' '.$this->currency->format($result['biaya']),

				);
			}
		//}

		$this->response->setOutput(json_encode($json));
	}

	public function autocompletes() {
		$json = array();

		//if (isset($this->request->get['filter_name']) ) {
			$this->load->model('catalog/aset');

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
			'pemeliharaan.name'	  => array('LIKE',$filter_name),

			);

			$join=array();
			$join[]=array(
				'tablename' => 'pemeliharaan',
				'firsttable'	=> 'pemeliharaan_aset.pemeliharaan_id',
				'secondtable'	=> 'pemeliharaan.id'
			);
			$join[]=array(
				'tablename' => 'aset',
				'firsttable'	=> 'pemeliharaan_aset.aset_id',
				'secondtable'	=> 'aset.aset_id'
			);

			$offset=0;
			$limit=$limit;

			$results = $this->model_catalog_aset->getPemeliharaans(array('pemeliharaan_aset.*','aset.name','pemeliharaan.name as jenis'),$join,$data,array(),$limit,$offset);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['aset_id'],
					'text'       => date('d/m/y',strtotime($result['tanggal'])).' '.$result['jenis'].' - '.strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')).' '.$this->currency->format($result['biaya']),

				);
			}
		//}

		$this->response->setOutput(json_encode($json));
	}

	public function kartustok() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Kartu Stok Aset');

			$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['cancel'] = $this->url->link('catalog/pemeliharaanaset', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['aset_id'])) {
			$url .= '&aset_id=' . $this->request->get['aset_id'];
		}

		$this->data['url'] = $this->url->link('catalog/pemeliharaanaset/kartustok', 'token=' . $this->session->data['token'] . $url, 'SSL');


		if (isset($this->request->get['pagekartu'])) {
			$url .= '&pagekartu=' . $this->request->get['pagekartu'];
		}


		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}

		if (isset($this->request->get['aset_id'])) {
			$product_id = $this->request->get['aset_id'];
		} else {
			$this->redirect($this->url->link('catalog/pemeliharaanaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		$order_total = $this->model_gudang_kartustok->getTotalKartustokGlobals('kartustok_aset',$data);

		$results = $this->model_gudang_kartustok->getKartustokGlobals('kartustok_aset',$data);


		$this->data['kartustoks']=array();
		foreach ($results as $result) {


      $this->data['kartustoks'][] = array(
				'tanggal'=> date('d F Y',strtotime($result['tgl'])),
				'waktu'	=> date('H:i:s',strtotime($result['tgl'])),
				'name'   => $result['product_name'],
				'stokmasuk'   => $result['stokmasuk'],
				'stokkeluar'	=> $result['stokkeluar'],
				'ket'	=> $result['ket'],
				'saldo'	=> $result['saldo'],
				'invoice'	=> $result['invoice'],
				'quantityawal'	=> $result['quantityawal'],
				'type'	=> $result['type_name']
			);

		}

		$this->data['heading_title'] = 'Kartu Aset';
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

		if (isset($this->request->get['aset_id'])) {
			$url .= '&aset_id=' . $this->request->get['aset_id'];
		}

		$next=$pagekartu+1;

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $pagekartu;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/pemeliharaanaset/kartustok', 'token=' . $this->session->data['token'] . $url . '&pagekartu='.$next+1, 'SSL');

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
	public function stokAwal(){
		//$this->load->model('gudang/product');
		$this->load->model('catalog/aset');


		$this->document->setTitle('Input Stok Awal Aset');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['aset_id'])) {
				$aset_id=$this->request->get['aset_id'];
			}
		else{
			$this->redirect($this->url->link('catalog/pemeliharaanaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));

		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormStok()) {

			$this->model_catalog_aset->setStokAwal($this->request->post);
	  	$this->session->data['success'] = 'Success: Stok berhasil ditambahkan.';

				$url = '';

				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				$this->redirect($this->url->link('catalog/pemeliharaanaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}



			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->data['action'] = $this->url->link('catalog/pemeliharaanaset/stokAwal', 'token=' . $this->session->data['token'] . '&aset_id=' . $this->request->get['aset_id'] . $url, 'SSL');

			$this->data['cancel'] = $this->url->link('catalog/pemeliharaanaset', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->error)) {
				$this->data['error'] = $this->error;
			} else {
				$this->data['error'] = '';
			}

    	//if(empty($this->request->post)){
			$this->data['productdesc']=$this->model_catalog_aset->getAset(array('aset_id' => $aset_id));
			$this->data['aset_id']=$aset_id;


		$this->template = 'catalog/stokawal_aset.tpl';
		$this->children = array(
					'common/header',
					'common/footer'
				);
			$this->response->setOutput($this->render());

	}
	public function validateFormStok(){
	/*	if (!$this->user->hasPermission('modify', 'catalog/aset')) {
      		$this->error['warning'] = 'Anda tidak memiliki ijin untuk memodifikasi data stok Aset.';
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
	public function stokopname(){
		//$this->load->model('gudang/product');
		$this->load->model('catalog/aset');


		$this->document->setTitle('Stok Opname Aset');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['aset_id'])) {
				$aset_id=$this->request->get['aset_id'];
			}
		else{
			$this->redirect($this->url->link('catalog/pemeliharaanaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));

		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormStok()) {

			$this->model_catalog_aset->stokOpname($this->request->post);
	  	$this->session->data['success'] = 'Success: Data Stok berhasil diperbarui.';

				$url = '';

				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				$this->redirect($this->url->link('catalog/pemeliharaanaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}



			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->data['action'] = $this->url->link('catalog/pemeliharaanaset/stokopname', 'token=' . $this->session->data['token'] . '&aset_id=' . $this->request->get['aset_id'] . $url, 'SSL');

			$this->data['cancel'] = $this->url->link('catalog/pemeliharaanaset', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->error)) {
				$this->data['error'] = $this->error;
			} else {
				$this->data['error'] = '';
			}

    	//if(empty($this->request->post)){
			$this->data['productdesc']=$this->model_catalog_aset->getAset(array('aset_id'	=> $aset_id));
			$this->data['aset_id']=$aset_id;


		$this->template = 'catalog/stokopname_aset.tpl';
		$this->children = array(
					'common/header',
					'common/footer'
				);
			$this->response->setOutput($this->render());

	}
}
?>
