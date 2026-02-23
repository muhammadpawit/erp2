<?php
class ControllerLaporanLaporannilaibarang extends Controller {
	public function index() {
		$this->document->setTitle('Laporan Persediaan');

		if (isset($this->request->get['filter_product_id'])) {
			$product_id = $this->request->get['filter_product_id'];
		} else {
			$product_id = '';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['gudang_id'])) {
			$gudang_id = $this->request->get['gudang_id'];
		} else {
			$gudang_id = '';
		}

		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$filter_category_id = '';
		}



		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['gudang_id'])) {
			$url .= '&gudang_id=' . $this->request->get['gudang_id'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}



		//$this->load->model('report/laporanpersediaan');
		$this->load->model('gudang/product');
		//$this->load->model('gudang/product');
		$this->load->model('catalog/product');

		$this->load->model('catalog/gudang');
		$this->data['gudangs']=$this->model_catalog_gudang->getGudangs();

		$this->data['products'] = array();

		$data = array(
			'filter_name'			=> $filter_name,
			'filter_product_id'			=> $product_id,
			'filter_gudang_id'		=> $gudang_id,
			'filter_category_id'	=> $filter_category_id,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		//print_r($data);


		$product_total = $this->model_gudang_product->getTotalProducts($data);

			$results = $this->model_gudang_product->getProducts($data);
		foreach ($results as $result) {

			//stok awal
			//options


			$this->data['products'][] = array(
				'nama'	=> strip_tags(html_entity_decode($result['nama'], ENT_QUOTES, 'UTF-8')),
				'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
				'quantity'	=> $result['quantity'],
				//'options'	=> $option_data,
				//'stokawal'	=> $gstokawal,
				//'stokmasuk'	=>$gstokinfo['stokmasuk'],
				//'stokkeluar'	=> $gstokinfo['stokkeluar'],
				'net_cost'	=> $this->currency->format($result['net_cost']),
					//'saldo'	=> $saldo

			);
		}
		//print_r($results);

		$this->data['heading_title'] = $this->language->get('Laporan Nilai Barang');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = 'Option<br> (Size/Color)';
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_size'] = $this->language->get('column_size');
		$this->data['column_total'] = $this->language->get('column_total');

		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];



		$url = '';

		if (isset($this->request->get['product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}
		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['gudang_id'])) {
			$url .= '&gudang_id=' . $this->request->get['gudang_id'];
		}


		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/laporannilaibarang', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['product_id'] = $product_id;
        $this->data['filter_category_id'] = $filter_category_id;

		$this->data['filter_name'] = $filter_name;



		$this->load->model('catalog/category');

		$this->data['categories']=$this->model_catalog_category->getCategories();

		$this->template = 'laporan/laporan_nilaibarang.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
}
?>
