<?php
class ControllerCatalogPremijual extends Controller {
	private $error = array();
	public function index() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Set Premi Jual');

		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = '';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

        if (isset($this->request->get['filter_option'])) {
			$filter_option = $this->request->get['filter_option'];
		} else {
			$filter_option = '';
		}

        if (isset($this->request->get['filter_qty'])) {
			$filter_qty = $this->request->get['filter_qty'];
		} else {
			$filter_qty = '';
		}

       /* if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
			$filter_product_id = '';
		}*/

		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$filter_category_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		/*if(!empty($filter_gudang_id)){
			if(!in_array($filter_gudang_id,$this->user->getGudang())){
				$this->data['permission']=false;
				$filter_gudang_id='';
			}

		}*/

		if (isset($this->request->get['filter_urutkan'])) {
			$filter_urutkan = $this->request->get['filter_urutkan'];
		} else {
			$filter_urutkan = '3';
		}

		$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

        if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}

		/*if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}*/
		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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

		$this->load->model('gudang/product');


		$this->data['orders'] = array();

		$data = array(
        'filter_gudang_id'     => $filter_gudang_id,
				'filter_urutkan'	=> $filter_urutkan,
      	'filter_status'	=> $filter_status,
		   'filter_name'	=> $filter_name,
		   'filter_option'	=> $filter_option,
		   'filter_category_id'	=> $filter_category_id,
		   'filter_qty'	=> $filter_qty,
      'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_gudang_product->getTotalProducts($data,true);

		$results = $this->model_gudang_product->getProducts($data,true);
		$this->data['cetak'] = $this->url->link('catalog/productgudang/cetak', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->load->model('catalog/gudang');
		$this->load->model('gudang/product');

		//print_r($results);
		$this->data['products']=array();
		foreach ($results as $result) {
		//if(isset($result['product_gudang_id'])){
			$action = array();

			$action[] = array(
				'text' => 'Premi',
				'href' => $this->url->link('catalog/premijual/premi', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].'&gudang_id='.$result['gudang_id'].$url, 'SSL')
			);

      $this->data['products'][] = array(
				'name'   => $result['name'],
				'nama'   => $result['nama'],
				'quantity'   => $result['quantity'],
				'action'	=> $action

			);

		}

		$this->data['heading_title'] = 'Set Premi Jual';



		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];



		$url = '';
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];

		}
		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
		/*if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}*/
		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}



		$this->data['gudangs']=$this->model_catalog_gudang->getGudangs(true);

		$this->load->model('catalog/category');

		$this->data['categories']=$this->model_catalog_category->getCategories();


		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/premijual', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name']=$filter_name;
		$this->data['filter_option']=$filter_option;
		$this->data['filter_category_id']=$filter_category_id;
		$this->data['filter_gudang_id']=$filter_gudang_id;
		$this->data['filter_qty']=$filter_qty;
		$this->data['filter_urutkan']=$filter_urutkan;
		$this->data['filter_status']=$filter_status;


		$this->template = 'catalog/premijual_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}


	public function premi() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

	$this->load->model('kepegawaian/kodepremi');
	$this->load->model('gudang/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			//print_r($this->request->post['product_special']);
		$this->model_gudang_product->addProductPremi($this->request->get['gudang_id'],$this->request->get['product_id'], $this->request->post);

		$this->session->data['success'] = 'Premi Jual berhasil diperbarui';

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
		}

			if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

				if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->redirect($this->url->link('catalog/premijual', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	$url = '';

	if (isset($this->request->get['filter_name'])) {
		$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
	}

	if (isset($this->request->get['filter_category_id'])) {
		$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
	}

		if (isset($this->request->get['filter_status'])) {
		$url .= '&filter_status=' . $this->request->get['filter_status'];
	}

			if (isset($this->request->get['filter_urutkan'])) {
		$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
	}

	if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
	}

		if(!isset($this->request->get['product_id'])){
			$this->redirect($this->url->link('catalog/premijual', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}else{
			if(empty($this->request->get['product_id'])){
				$this->redirect($this->url->link('catalog/premijual', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				if(!isset($this->request->get['gudang_id'])){
					$this->redirect($this->url->link('catalog/premijual', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}else{
					if(empty($this->request->get['gudang_id'])){
						$this->redirect($this->url->link('catalog/premijual', 'token=' . $this->session->data['token'] . $url, 'SSL'));
					}else{

						$this->data['token'] = $this->session->data['token'];

						$this->data['premis'] = $this->model_kepegawaian_kodepremi->getOptions();

							//$this->data['options'] = $this->model_catalog_options->getOptions();

							$this->data['product'] = $this->model_gudang_product->getProduct($this->request->get['product_id'],$this->request->get['gudang_id']);

							//print_r($this->data['product']);
							$this->data['action'] = $this->url->link('catalog/premijual/premi', 'token=' . $this->session->data['token'].'&product_id='.$this->request->get['product_id'].'&gudang_id='.$this->request->get['gudang_id'] . $url, 'SSL');
							$this->data['cancel'] = $this->url->link('catalog/premijual', 'token=' . $this->session->data['token'] . $url, 'SSL');

							$this->template = 'catalog/product_premi.tpl';
							$this->children = array(
								'common/header',
								'common/footer'
							);


							$this->response->setOutput($this->render());
					}
				}
			}
		}
	}


}
?>
