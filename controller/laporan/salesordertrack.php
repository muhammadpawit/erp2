<?php
class ControllerLaporanSalesordertrack extends Controller {
	private $error = array();
	public function index() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Laporan Penjualan');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-01');
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-t');
		}
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
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

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

		$this->load->model('catalog/gudang');
		$this->load->model('gudang/product');
		$this->load->model('sale/salesorder');

		$gs=$this->model_catalog_gudang->getGudangs(true);
		$gudangs=array();
		if(empty($filter_gudang_id)){
			foreach($gs as $g){
				$gudangs[]=$g['gudang_id'];
			}
		}else{
			$gudangs[]=$filter_gudang_id;
		}

		$arrsql=implode(',',$gudangs);
		$data = array(
			'sales_order.id'	=> empty($filter_order_id)?array('>=',1):$filter_order_id,
			'sales_order.customer_id'	=> empty($filter_customer_id)?array('>=',1):$filter_customer_id,
			//'sales_order.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):$filter_tanggal,
			'sales_order.gudang_id'	=>array('IN',$arrsql),
			'sales_order_product.status_pengiriman'	=> empty($filter_status)?array('>=',1):$filter_status,
			'sales_order_product.product_id'	=> empty($filter_jenisorder)?array('>=',1):$filter_jenisorder,
			'sales_order.date_added'	=> array('>=',$filter_date_start,'<=',$filter_date_end),


		);
		/*$data = array(
        'gudang_id'     => $arrsql,
				'filter_name'	=> $filter_name,
				'filter_date_start'	=> $filter_date_start,
				'filter_date_end'	=> $filter_date_end,
      	'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit'                  => $this->config->get('config_admin_limit')
		);*/
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$order=array();
		$order=array('sales_order_product.sales_order_id'=>'DESC');

		//$this->data['penjualangudang']=$this->model_sale_salesorder->getListDetail($data,$order,$limit,$offset);
		$order_total = $this->model_sale_salesorder->getTotalListDetail($data);
		$results = $this->model_sale_salesorder->getListDetail($data,$order,$limit,$offset);

		$this->data['products']=array();
		foreach ($results as $result) {
			//no_so
			$listso=$this->model_report_salesordertrack->listSo($result['product_id'],$result['gudang_id'],$data);
			//print_r($listso);
			$this->data['products'][] = array(
				'product_id'	=> $result['product_id'],
				'gudang_id'	=> $result['gudang_id'],
				'name'   => $result['name'],
				'nama'   => $result['namagudang'],
				'namacustomer'	=> $result['namacustomer'],
				'quantity'	=> $result['quantity'],
				'quantityterima'	=> $result['quantityterima'],
				'pajak'   => $this->currency->format($result['totalpajak'] == 0?$result['pajak']:$result['totalpajak']),
				'price'   => $this->currency->format($result['price']),
				'total'   => $this->currency->format($result['total']),
				'listso'	=> $listso

			);
		}


		$this->data['heading_title'] = 'Laporan Penjualan';



		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];



		$url = '';
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
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
		$pagination->url = $this->url->link('laporan/salesordertrack', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name']=$filter_name;
		$this->data['filter_gudang_id']=$filter_gudang_id;
		$this->data['filter_date_start']=$filter_date_start;
		$this->data['filter_date_end']=$filter_date_end;


		$this->template = 'laporan/salesordertrack.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}


}
?>
