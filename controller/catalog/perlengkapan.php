<?php 
class ControllerCatalogPerlengkapan extends Controller {
	private $error = array(); 
     
  	public function index() {
		$this->load->language('catalog/atk');
    	
		$this->document->setTitle($this->language->get('Master Data Perlengkapan')); 
		
		$this->load->model('catalog/perlengkapan');
		
		$this->getList();
  	}
  
  	public function insert() {
    	$this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Master Data Perlengkapan')); 
		
		$this->load->model('catalog/perlengkapan');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_perlengkapan->addPerlengkapan($this->request->post);
	  		
			$this->session->data['success'] = $this->language->get('text_success');
	  
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_toko_id'])) {
			$url .= '&filter_toko_id=' . urlencode($this->request->get['filter_toko_id']);
		}		
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('catalog/perlengkapan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
	if(!isset($this->request->get['perlengkapan_id'])){
		$this->redirect($this->url->link('catalog/perlengkapanall', 'token=' . $this->session->data['token'] , 'SSL'));
	}
    	$this->getForm();
  	}

  	

  	public function delete() {
    	$this->load->language('catalog/atk');

    	$this->document->setTitle($this->language->get('Master Data Perlengkapan'));
		
		$this->load->model('catalog/perlengkapan');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $perlengkapan_toko_id) {
				$this->model_catalog_perlengkapan->deletePerlengkapan($perlengkapan_toko_id);
	  		}

			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_toko_id'])) {
			$url .= '&filter_toko_id=' . urlencode($this->request->get['filter_toko_id']);
		}
					
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('catalog/perlengkapan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getList();
  	}

  	
	
  	private function getList() {				
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_toko_id'])) {
			$filter_toko_id = $this->request->get['filter_toko_id'];
		} else {
			$filter_toko_id = null;
		}
		
		

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.nama';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
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
		
		if (isset($this->request->get['filter_toko_id'])) {
			$url .= '&filter_toko_id=' . urlencode($this->request->get['filter_toko_id']);
		}
		
		
						
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('Master Data Perlengkapan'),
			'href'      => $this->url->link('catalog/perlengkapan', 'token=' . $this->session->data['token'] . $url, 'SSL'),       		
      		'separator' => ' :: '
   		);
		
		$this->data['insert'] = $this->url->link('catalog/perlengkapan/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['copy'] = $this->url->link('catalog/perlengkapan/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');	
		$this->data['delete'] = $this->url->link('catalog/perlengkapan/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
    	
		$this->data['products'] = array();

		$data = array(
			'filter_name'	  => $filter_name, 
			'filter_toko_id'	  => $filter_toko_id,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);
		
		$this->load->model('tool/image');
		$this->load->model('catalog/gudang');
		
		$this->data['gudangs']=$this->model_catalog_gudang->getGudangs();
		
		$product_total = $this->model_catalog_perlengkapan->getTotalPerlengkapan($data);
			
		$results = $this->model_catalog_perlengkapan->getPerlengkapans($data);
				    	
		foreach ($results as $result) {
			$action = array();
			
			/*$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/perlengkapan/update', 'token=' . $this->session->data['token'] . '&perlengkapan_toko_id=' . $result['perlengkapan_toko_id'] . $url, 'SSL')
			);*/
			$action[] = array(
				'text' => $this->language->get('Kartu Stok'),
				'href' => $this->url->link('report/kartustok_perlengkapan', 'token=' . $this->session->data['token'] . '&filter_perlengkapan_id=' . $result['perlengkapan_id'] .'&gudang_id='.$result['toko_id']. $url, 'SSL')
			);
			
			$susut=$result['harga_beli']/($result['tahunekonomis']*12);
			$d1 = new DateTime($result['tglpembelian']);
			$d2 = new DateTime(date('Y-m-d'));
			
			$selisih=(int)abs((strtotime($d2) - strtotime($d1))/(60*60*24*30));
			$nilai=$result['harga_beli']-($susut*$selisih);
	
      		$this->data['products'][] = array(
				'perlengkapan_id' => $result['perlengkapan_id'],
				'toko_id' => $result['toko_id'],
				'perlengkapan_toko_id' => $result['perlengkapan_toko_id'],
				'nama'       => $result['nama'],
				'nama_gudang'       => $result['nama_gudang'],
				'tglpembelian'	=> $result['tglpembelian'],
				'harga_beli'	=> $this->currency->format($result['harga_beli']),
				'nilai_barang'	=> $this->currency->format($nilai),
				'qty'      => $result['qty'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['perlengkapan_toko_id'], $this->request->post['selected']),
				'action'     => $action
			);
    	}

$this->data['perlengkapan']=true;
		
		$this->data['heading_title'] = $this->language->get('Master Data Perlengkapan');		
				
		$this->data['text_enabled'] = $this->language->get('text_enabled');		
		$this->data['text_disabled'] = $this->language->get('text_disabled');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');		
			
		$this->data['column_image'] = $this->language->get('column_image');		
		$this->data['column_name'] = $this->language->get('column_name');		
		$this->data['column_model'] = $this->language->get('column_model');		
		$this->data['column_price'] = $this->language->get('column_price');		
		$this->data['column_quantity'] = $this->language->get('column_quantity');		
		$this->data['column_status'] = $this->language->get('column_status');		
		$this->data['column_action'] = $this->language->get('column_action');		
				
		$this->data['button_copy'] = $this->language->get('button_copy');		
		$this->data['button_insert'] = $this->language->get('button_insert');		
		$this->data['button_delete'] = $this->language->get('button_delete');		
		$this->data['button_filter'] = $this->language->get('button_filter');
		 
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
		
		if (isset($this->request->get['filter_toko_id'])) {
			$url .= '&filter_toko_id=' . urlencode($this->request->get['filter_toko_id']);
		}
		
		
								
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
					
		$this->data['sort_name'] = $this->url->link('catalog/perlengkapan', 'token=' . $this->session->data['token'] . '&sort=p.nama' . $url, 'SSL');
		$this->data['sort_toko_id'] = $this->url->link('catalog/perlengkapan', 'token=' . $this->session->data['token'] . '&sort=pd.toko_id' . $url, 'SSL');
		
		$this->data['sort_order'] = $this->url->link('catalog/perlengkapan', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_toko_id'])) {
			$url .= '&filter_toko_id=' . urlencode($this->request->get['filter_toko_id']);
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/perlengkapan', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
	
		$this->data['filter_name'] = $filter_name;
		$this->data['filter_toko_id'] = $filter_toko_id;
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/atk_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
  	}

  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');
 
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
    	$this->data['text_none'] = $this->language->get('text_none');
    	$this->data['text_yes'] = $this->language->get('text_yes');
    	$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$this->data['text_plus'] = $this->language->get('text_plus');
		$this->data['text_minus'] = $this->language->get('text_minus');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['text_option'] = $this->language->get('text_option');
		$this->data['text_option_value'] = $this->language->get('text_option_value');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_percent'] = $this->language->get('text_percent');
		$this->data['text_amount'] = $this->language->get('text_amount');

					
    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_attribute'] = $this->language->get('button_add_attribute');
		$this->data['button_add_option'] = $this->language->get('button_add_option');
		$this->data['button_add_option_value'] = $this->language->get('button_add_option_value');
		$this->data['button_add_discount'] = $this->language->get('button_add_discount');
		$this->data['button_add_special'] = $this->language->get('button_add_special');
		$this->data['button_add_image'] = $this->language->get('button_add_image');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
    	$this->data['tab_general'] = $this->language->get('tab_general');
    	$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_attribute'] = $this->language->get('tab_attribute');
		$this->data['tab_option'] = $this->language->get('tab_option');		
		$this->data['tab_discount'] = $this->language->get('tab_discount');
		$this->data['tab_special'] = $this->language->get('tab_special');
    	$this->data['tab_image'] = $this->language->get('tab_image');		
		$this->data['tab_links'] = $this->language->get('tab_links');
		$this->data['tab_reward'] = $this->language->get('tab_reward');
		$this->data['tab_design'] = $this->language->get('tab_design');
		 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		
		$this->data['perlengkapan'] =true;
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_toko_id'])) {
			$url .= '&filter_toko_id=' . urlencode($this->request->get['filter_toko_id']);
		}
								
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/perlengkapan', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->load->model('catalog/gudang');
		
		$this->data['gudangs']=$this->model_catalog_gudang->getGudangs();
									
		if (!isset($this->request->get['perlengkapan_toko_id'])) {
			if($this->request->get['perlengkapan_id']){
				$this->data['action'] = $this->url->link('catalog/perlengkapan/insert', 'token=' . $this->session->data['token'].'&perlengkapan_id='.$this->request->get['perlengkapan_id'] . $url, 'SSL');
			}
		} else {
			$this->data['action'] = $this->url->link('catalog/perlengkapan/update', 'token=' . $this->session->data['token'] . '&perlengkapan_toko_id=' . $this->request->get['perlengkapan_toko_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('catalog/perlengkapan', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['perlengkapan_toko_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
	      		$product_info = $this->model_catalog_perlengkapan->getPerlengkapan($this->request->get['perlengkapan_toko_id']);
	    	}
		else{
			if (isset($this->request->get['perlengkapan_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$product_info = $this->model_catalog_perlengkapan->getPerlengkapanlist($this->request->get['perlengkapan_id']);
				foreach($this->data['gudangs'] as $g){
					$atk=$this->model_catalog_perlengkapan->getPerlengkapanToko($g['gudang_id'],$this->request->get['perlengkapan_id']);
					if(!$atk){
						$newgudangs[]=$g;
					}
				}
				$this->data['gudangs']=$newgudangs;
	    		}
			elseif(isset($this->request->get['perlengkapan_id']) && ($this->request->server['REQUEST_METHOD'] == 'POST')){
				foreach($this->data['gudangs'] as $g){
					$atk=$this->model_catalog_perlengkapan->getPerlengkapanToko($g['gudang_id'],$this->request->get['perlengkapan_id']);
					if(!$atk){
						$newgudangs[]=$g;
					}
				}
				$this->data['gudangs']=$newgudangs;
			}
			else{
				
				$this->redirect($this->url->link('catalog/perlengkapanall', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}
		
		$this->data['atk']=array();
		$this->data['atk_id']=$this->request->get['perlengkapan_id'];
		if(!empty($product_info)){
			$this->data['atk']=$product_info;
		}

		$this->data['token'] = $this->session->data['token'];
		
		
												
		$this->template = 'catalog/atk_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
  	} 
	
  	private function validateForm() { 
    	if (!$this->user->hasPermission('modify', 'catalog/atk')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	
		
    	if ((utf8_strlen($this->request->post['nama']) < 1) || (utf8_strlen($this->request->post['nama']) > 64)) {
      		$this->error['nama'] = $this->language->get('Nama barang harus diisi');
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
    	if (!$this->user->hasPermission('modify', 'catalog/atk')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
  	
  	private function validateCopy() {
    	if (!$this->user->hasPermission('modify', 'catalog/atk')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
		
	public function autocomplete() {
		$results = array();
		
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/perlengkapan');
			
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}
			
			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = 20;	
			}			
						
			$data = array(
				'filter_name'         => $filter_name,
				'start'               => 0,
				'limit'               => $limit
			);
			
			$results = $this->model_catalog_perlengkapan->getPerlengkapanLists($data);
			
			

		
		}
		$this->response->setOutput(json_encode($results));
	}

	public function autocompletegudang() {
		$results = array();
		
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/perlengkapan');
			
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_toko_id'])) {
				$filter_toko_id = $this->request->get['filter_toko_id'];
			} else {
				$filter_toko_id = '';
			}
			
			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = 20;	
			}			
						
			$data = array(
				'filter_name'         => $filter_name,
				'filter_toko_id'         => $filter_toko_id,
				'start'               => 0,
				'limit'               => $limit
			);
			
			$results = $this->model_catalog_perlengkapan->getPerlengkapans($data);
			
			

		
		}
		$this->response->setOutput(json_encode($results));
	}
}
?>
