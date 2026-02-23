<?php
class ControllerGudangStokopname extends Controller {
	public function index() {
		$this->document->setTitle('Produk Cacat');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d',strtotime('last week'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

    if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
			$filter_product_id = '';
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
			if(!empty($filter_gudang_id)){
				if(!in_array($filter_gudang_id,$this->user->getGudang())){
					$this->data['permission']=false;
					$filter_gudang_id='';
				}

			}
		} else {
			$filter_gudang_id = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

        if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('gudang/stokopname/insert', 'token=' . $this->session->data['token'], 'SSL');

		/*$this->load->model('report/product');
        $this->load->model('catalog/product');
		*/

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('catalog/product');
		$this->load->model('gudang/product');
		$this->load->model('catalog/gudang');

		$gudangs = $this->model_catalog_gudang->getGudangs(true);
		$this->data['gudangs']=$gudangs;

		$this->data['products'] = array();

		$data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
      'filter_product_id'      =>$filter_product_id,
      'filter_gudang_id'			=> $filter_gudang_id,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$product_total = $this->model_gudang_product->getTotalStokopname($data,true);

		$results = $this->model_gudang_product->getStokopname($data,true);

		foreach ($results as $result) {
        //print_r($opt);
			$this->data['products'][] = array(
				'name'       => $result['name'],
				'qtytercatat'   => $result['qtytercatat'],
				'qtytersedia'	=> $result['qtytersedia'],
				'date_added'	=> date('d F Y',strtotime($result['tanggal'])),
				'gudang_id'	=> $result['gudang_id'],
				'nama'	=> $result['nama'],
				'keterangan'	=> $result['keterangan']
			);
		}

		$this->data['heading_title'] = 'Stok Opname';

		$this->data['token'] = $this->session->data['token'];



		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
        if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('gudang/stokopname', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_product_id'] = $filter_product_id;
		$this->data['filter_gudang_id'] = $filter_gudang_id;

		$this->template = 'gudang/stokopname.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Stok Opname');

		$this->load->model('gudang/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      	  	$this->model_gudang_product->addStokopname($this->request->post);

			$this->session->data['success'] = 'Sukses: Data stokopname berhasil disimpan.';

			$url = '';

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}

		    if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
			}

			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('gudang/stokopname', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('catalog/gudang');
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

			if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$gudangs = $this->model_catalog_gudang->getGudangs(true);
		$this->data['gudangs']=$gudangs;

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->post['gudang_id'])) {
			$this->data['gudang_id'] = $this->request->post['gudang_id'];
		}  else {
			$this->data['gudang_id'] = '';
		}
		$this->data['cancel']= $this->url->link('gudang/stokopname', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('gudang/stokopname/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->request->post['product'])) {
			$this->data['products'] = $this->request->post['product'];
		} else {
			$this->data['products'] = '';
		}

		$this->template = 'gudang/stokopname_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {
    	/*if (!$this->user->hasPermission('modify', 'gudang/stokopname')) {
      		$this->error['warning'] = 'Permission Denied.';
    	}

    	/*if (empty($this->request->post['date_added'])) {
      		$this->error['warning'] = 'Tanggal input product cacat harus diisi';
    	}*/

		if (empty($this->request->post['product'])) {
		  		$this->error['warning'] = 'Produk tidak boleh kosong';
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

}
?>
