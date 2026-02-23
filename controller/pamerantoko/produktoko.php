<?php
class ControllerPamerantokoProduktoko extends Controller {
	private $error = array();
	public function index() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Produk Toko dan Pameran');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}


		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = '';
		}

		if (isset($this->request->get['filter_toko'])) {
			$filter_toko = $this->request->get['filter_toko'];
		} else {
			$filter_toko = '';
		}

        if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
			$filter_product_id = '';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_qty'])) {
			$filter_qty = $this->request->get['filter_qty'];
		} else {
			$filter_qty = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}
		if (isset($this->request->get['filter_toko'])) {
			$url .= '&filter_toko=' . $this->request->get['filter_toko'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

   		$this->load->model('pamerantoko/product');
			$this->load->model('pamerantoko/pameran');
			$this->load->model('pamerantoko/toko');
		//$this->load->model('pameran/produk');

		$this->data['orders'] = array();
		$this->data['cetak'] = $this->url->link('pamerantoko/produktoko/cetak', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//produk toko
		$data = array(
          'filter_gudang_id'     => $filter_gudang_id,
          'filter_toko'     => $filter_toko,
          'filter_qty'     => $filter_qty,
					'filter_product_id'	=> $filter_product_id,
					'filter_name'	=> $filter_name,
          'start'                  => ($page - 1) * 20,
					'limit'                  => 20
		);



		$order_total = $this->model_pamerantoko_product->getTotalProductToko($data);

		$results = $this->model_pamerantoko_product->getProductToko($data);
		//print_r($results);
		foreach ($results as $result) {
		//if(isset($result['product_gudang_id'])){
			$action = array();

			$action[] = array(
				'text' => $this->language->get('Kartu Stok'),
				'href' => $this->url->link('pamerantoko/produktoko/kartustok', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].'&pameran_id='.$result['gudang_id'].'&jenistoko='.$result['jenis'].$url, 'SSL')
			);


			if($result['jenis'] == 1){
				$jenis='pameran';
			}

			if($result['jenis'] == 2){
				$jenis='toko';
			}

			$toko=$this->{'model_pamerantoko_'.$jenis}->getPameran($result['gudang_id']);

			$this->data['products'][] = array(
				'jenis'	=> $result['jenis'] == 1?'Pameran':'Toko',
				'product_name'   => $result['name'],
				'lokasi'   => $toko['lokasi'],
				'qty'   => $result['qty'],
				'price'	=> $this->currency->format($result['price']),
				'action'	=> $action

			);

		}



		$this->data['heading_title'] = 'Produk Toko dan Pameran';

		$this->data['token'] = $this->session->data['token'];
		$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}
		if (isset($this->request->get['filter_toko'])) {
			$url .= '&filter_toko=' . $this->request->get['filter_toko'];
		}



		$pagination = new Pagination();
		$pagination->total = $order_total ;
		$pagination->page = $page;
		$pagination->limit = 20;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pamerantoko/produktoko', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		$this->data['filter_toko'] = $filter_toko;
		$this->data['filter_qty'] = $filter_qty;
		$this->data['filter_name'] = $filter_name;

		$this->template = 'pamerantoko/produktoko.tpl';

		$this->children = array(
					'common/header',
					'common/footer'
				);

		$this->response->setOutput($this->render());
	}



	public function cetak() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Produk Toko');

		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = '';
		}

		if (isset($this->request->get['filter_toko'])) {
			$filter_toko = $this->request->get['filter_toko'];
		} else {
			$filter_toko = '';
		}

        if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
			$filter_product_id = '';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_qty'])) {
			$filter_qty = $this->request->get['filter_qty'];
		} else {
			$filter_qty = '';
		}



		$this->load->model('pamerantoko/product');
		$this->load->model('pamerantoko/toko');
		$this->load->model('pamerantoko/pameran');

		$this->data['products'] = array();

		$data = array(
          'filter_gudang_id'     => $filter_gudang_id,
          'filter_toko'     => $filter_toko,
          'filter_qty'     => $filter_qty,
					'filter_product_id'	=> $filter_product_id,
					'filter_name'	=> $filter_name,

		);



		$order_total = $this->model_pamerantoko_product->getTotalProductToko($data);

		$results = $this->model_pamerantoko_product->getProductToko($data);
		//print_r($results);
		//print_r($results);
		foreach ($results as $result) {
		//if(isset($result['product_gudang_id'])){
			if($result['jenis'] == 1){
				$jenis='pameran';
			}

			if($result['jenis'] == 2){
				$jenis='toko';
			}

			$toko=$this->{'model_pamerantoko_'.$jenis}->getPameran($result['gudang_id']);

			$this->data['products'][] = array(
				'jenis'	=> $result['jenis'] == 1?'Pameran':'Toko',
				'product_name'   => $result['name'],
				'nama'   => $toko['lokasi'],
				'qty'   => $result['qty'],
				'price'	=> $this->currency->format($result['price']),
				'action'	=> $action

			);

		}
		$this->data['heading_title'] = 'Produk Toko';
		$this->data['nama']	='Lokasi';

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');


		$this->data['token'] = $this->session->data['token'];




		$this->template = 'catalog/cetakproduct.tpl';


		$this->response->setOutput($this->render());
	}
	public function kartustok() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Kartu Stok Produk Pameran dan Toko');

			$url = '';

			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_qty'])) {
				$url .= '&filter_qty=' . $this->request->get['filter_qty'];
			}
			if (isset($this->request->get['filter_toko'])) {
				$url .= '&filter_toko=' . $this->request->get['filter_toko'];
			}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
		}
		if (isset($this->request->get['pameran_id'])) {
			$url .= '&pameran_id=' . $this->request->get['pameran_id'];
		}
		if (isset($this->request->get['jenistoko'])) {
			$url .= '&jenistoko=' . $this->request->get['jenistoko'];
		}

		$this->data['url'] = $this->url->link('pamerantoko/produktoko/kartustok', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['pagekartu'])) {
			$url .= '&pagekartu=' . $this->request->get['pagekartu'];
		}


		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$this->redirect($this->url->link('pamerantoko/produktoko', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['pameran_id'])) {
			$pameran_id = $this->request->get['pameran_id'];
		} else {
			$this->redirect($this->url->link('pamerantoko/produktoko', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['jenistoko'])) {
			$jenistoko = $this->request->get['jenistoko'];
		} else {
			$this->redirect($this->url->link('pamerantoko/produktoko', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		$this->load->model('pamerantoko/product');
		$this->load->model('pamerantoko/kartustok');


		$this->data['orders'] = array();

		$data = array(
        'tanggal'     => $tanggal,
				'product_id'	=> $product_id,
				'type'	=> $type,
				'pameran_id'	=> $pameran_id,
      'start'                  => ($pagekartu - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		if($jenistoko ==1){
			$table="kartustok_produk_pameran";
		}
		if($jenistoko ==2){
			$table="kartustok_produk_toko";
		}

		$order_total = $this->model_pamerantoko_kartustok->getTotalKartustoks($table,$data);

		$results = $this->model_pamerantoko_kartustok->getKartustoks($table,$data);
		$this->data['cancel'] = $this->url->link('pamerantoko/produktoko', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->load->model('catalog/gudang');
		$this->load->model('gudang/product');

		//print_r($results);
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
				'type'	=> $result['type']
			);

		}

		$this->data['heading_title'] = 'Kartu Stok Produk';
		$this->data['token'] = $this->session->data['token'];



		$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}
		if (isset($this->request->get['filter_toko'])) {
			$url .= '&filter_toko=' . $this->request->get['filter_toko'];
		}

	if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
	}

	if (isset($this->request->get['product_id'])) {
		$url .= '&product_id=' . $this->request->get['product_id'];
	}
	if (isset($this->request->get['pameran_id'])) {
		$url .= '&pameran_id=' . $this->request->get['pameran_id'];
	}
	if (isset($this->request->get['jenistoko'])) {
		$url .= '&jenistoko=' . $this->request->get['jenistoko'];
	}


	if (isset($this->request->get['pagekartu'])) {
		$url .= '&pagekartu=' . $this->request->get['pagekartu'];
	}


	if (isset($this->request->get['tanggal'])) {
		$url .= '&tanggal=' . $this->request->get['tanggal'];
	}

	if (isset($this->request->get['type'])) {
		$url .= '&type=' . $this->request->get['type'];
	}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $pagekartu;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pamerantoko/produktoko/kartustok', 'token=' . $this->session->data['token'] . $url . '&pagekartu={pagekartu}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['tanggal']=$tanggal;
		$this->data['type']=$type;
		$this->template = 'pamerantoko/kartustok.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

}
?>
