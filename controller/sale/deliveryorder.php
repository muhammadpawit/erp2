<?php
class ControllerSaleDeliveryorder extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Delivery Order');

		$this->load->model('sale/deliveryorder');

		$this->getList();
	}
	public function terima(){
		$this->load->model('sale/deliveryorder');
		$this->document->setTitle('Pengiriman Barang');
		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];

				//get Penjualan
				$penj=$this->model_sale_deliveryorder->getPenjualan(array('id'=>$order_id));
				if($penj['status'] == 1){
					$this->model_sale_deliveryorder->updatePenjualan(array('status' => 2),array('id'	=> $order_id));
					$this->session->data['success'] = 'Sukses: Data Delivery Order berhasil diterima';
				}else{
					if($penj['status'] == 2){
						$this->session->data['warning'] = 'Peringatan: Data Delivery Order telah diterima';
					}else{
						$this->session->data['warning'] = 'Peringatan: Data Delivery Order telah dibatalkan';
					}
				}
				$this->redirect($this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				$this->redirect($this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	}

	

	public function batalkan(){
        //belum dikerjakkan
		$this->load->model('sale/deliveryorder');
		$this->document->setTitle('Delivery Order');
		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}$this->load->model('user/user');


			$canceldata=$this->model_user_user->getAksesData($this->user->getId(),9);
			if($canceldata == 1){
			if(isset($this->request->get['order_id'])){
				if(!empty($this->request->get['order_id'])){
					$order_id=$this->request->get['order_id'];
					$penj=$this->model_sale_deliveryorder->getPenjualan(array('id'=>$order_id));
					if($penj['status'] == 1){
						$cancel=$this->model_sale_deliveryorder->cancelPenjualan($order_id);
						if($cancel){
								$this->session->data['success'] = 'Sukses: Data Delivery Order berhasil dibatalkan';
						}else{
								$this->session->data['warning'] = 'Peringatan: Data Delivery Order gagal dibatalkan';
						}

					}else{
						if($penj['status'] == 2){
							$this->session->data['warning'] = 'Peringatan: Data Delivery Order telah diterima';
						}else{
							$this->session->data['warning'] = 'Peringatan: Data Delivery Order telah dibatalkan';
						}
					}
					$this->redirect($this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}else{
					$this->redirect($this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}
			}else{
				$this->redirect($this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->session->data['warning'] = 'Anda tidak diijinkan untuk membatalkan Surat Jalan';
			$this->redirect($this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}
	}


	public function update() {
        //belum dikerjakkan
		$this->load->language('catalog/category');

		$this->document->setTitle('Daftar Penjualan');

		$this->load->model('sale/deliveryorder');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_deliveryorder->updateOrderPenjualan($this->request->post, array('id'=>$this->request->get['id']));

			$this->session->data['success'] = 'Daftar Penjualan berhasil diperbarui';

			$url = '';

			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
			}
			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			if (isset($this->request->get['filter_invoice'])) {
				$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
			}
			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_sales_order'])) {
				$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}



	private function getList() {
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		
		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$filter_tanggal_awal = $this->request->get['filter_tanggal_awal'];
		} else {
			$filter_tanggal_awal = null;
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$filter_tanggal_akhir = $this->request->get['filter_tanggal_akhir'];
		} else {
			$filter_tanggal_akhir = null;
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/deliveryorder/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/deliveryorder/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['penjualans'] = array();

		

		$column=array('deliveryorder.*','gudang.nama','users.firstname');
    $join=array();
	/*	$join[]=array(
      'tablename' => 'deliveryorder_product',
      'firsttable'  => 'deliveryorder.id',
      'secondtable' => 'deliveryorder_product.do_id'
    );*/
		$join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=> 'deliveryorder.gudang_id',
			'secondtable'	=> 'gudang.gudang_id',
		);
   /* $join[]=array(
      'tablename' => 'product',
      'firsttable'  => 'deliveryorder_product.product_id',
      'secondtable' => 'product.product_id'
    );*/

    /*$join[]=array(
      'tablename' => 'sales_order',
      'firsttable'  => 'deliveryorder_product.no_so',
      'secondtable' => 'sales_order.id'
    );
    $join[]=array(
        'tablename' => 'penjualan',
        'firsttable'  => 'deliveryorder_product.sj_id',
        'secondtable' => 'penjualan.id'
      );*/
  

	$leftjoin=array();
	$leftjoin[]=array(
		'tablename'	=> 'users',
		'firsttable'	=> 'deliveryorder.sopir',
		'secondtable'	=> 'users.user_id',
	);
		/*$leftjoin[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'deliveryorder.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);

    $leftjoin[]=array(
      'tablename' => 'satuan',
      'firsttable'  => 'product.satuan',
      'secondtable' => 'satuan.id'
    );*/
	
		$this->load->model('catalog/gudang');

		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs(true);
		$gudangs=array();
		if(empty($filter_gudang_id)){
			foreach($this->data['gudangs'] as $g){
				$gudangs[]=$g['gudang_id'];
			}
		}else{
			$gudangs[]=$filter_gudang_id;
		}
		//print_r($gudangs);

		$arrsql=implode(',',$gudangs);
		$data=array();
		if(isset($filter_tanggal_awal) && !isset($filter_tanggal_akhir)){
			$data+= array(
				'deliveryorder.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):$filter_tanggal_awal,
			);
		}
		else if(!isset($filter_tanggal_awal) && isset($filter_tanggal_akhir)){
			$data+= array(
				'deliveryorder.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):$filter_tanggal_akhir,
			);
		}
		else if(isset($filter_tanggal_awal) && isset($filter_tanggal_akhir)){
			$data+= array(
				'deliveryorder.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):array('>=',$filter_tanggal_awal),
				'deliveryorder.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):array('<=',$filter_tanggal_akhir),
			);
		}

		$data += array(
			'deliveryorder.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'deliveryorder.gudang_id'	=>array('IN',$arrsql),
			'deliveryorder.status'	=> empty($filter_status)?array('>=',0):$filter_status,
			'deliveryorder.sopir'	=> empty($filter_customer_id)?array('>=',0):$filter_customer_id,
			
		);
		
		$order=array(
			'deliveryorder.id'	=> 'DESC',

		);
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$results = $this->model_sale_deliveryorder->getPenjualans($column,$join,$leftjoin,$data,$order,$limit,$offset);
		$product_total = $this->model_sale_deliveryorder->totalPenjualans($data,$join,$leftjoin);

		$this->load->model('sale/invoice');
		$this->load->model('catalog/gudang');

		$this->load->model('user/user');
		/*$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$data['customer.sales']=$this->user->getId();
		}*/

		//$canceldata=$this->model_user_user->getAksesData($this->user->getId(),4);


		//print_r($results);
		foreach ($results as $result) {
			//if(in_array($result['gudang_id'],$gudangs)){
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('sale/deliveryorder/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['id'], 'SSL')
			);


			if($result['status'] == 1){
				$action[] = array(
					'text' => 'Cetak',
					'href' => $this->url->link('sale/deliveryorder/cetak', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['id'], 'SSL')
				);
				/*$action[] = array(
					'text' => 'Diterima',
					'href' => $this->url->link('sale/deliveryorder/terima', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['sales_order_id'], 'SSL')
				);*/
				/*$action[] = array(
					'text' => 'Alokasi Tabung',
					'href' => $this->url->link('sale/deliveryorder/terimatabung', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['id'], 'SSL')
				);*/

				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('sale/deliveryorder/batalkan', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['id'], 'SSL')
				);
			}

		
			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'customer_id'        => $result['customer_id'],
				'nama'        => $result['nama'],
				'totaltabung'        => $result['totaltabung'],
				'name'	=> $result['firstname'],
				'no_do'	=> $result['no_do'],
				'status'	=> $result['status'],
				'tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'      => $action
			);

		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['warning'])) {
			$this->data['error_warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
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

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_tanggal_awal']	= $filter_tanggal_awal;
		$this->data['filter_tanggal_akhir']	= $filter_tanggal_akhir;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'sale/deliveryorder_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('sale/deliveryorder');

			if (isset($this->request->get['q'])) {
				$filter_order_id = $this->request->get['q'];
			} else {
				$filter_order_id = '';
			}

			if (isset($this->request->get['p'])) {
				$p = $this->request->get['p'];
			} else {
				$p = '';
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_do'         => array('LIKE',$filter_order_id),

			);
			$offset=0;
			$limit=10;

			$results = $this->model_sale_deliveryorder->getPenjualans(array(),array(),array(),$data,array(),10,0);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_do']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function tampil(){
		$this->document->setTitle('Daftar Delivery Order');
		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/deliveryorder');
		$this->load->model('sale/customer');

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),6);

		$column=array('deliveryorder.*','COALESCE(deliveryorder.cetak,0) as totalcetak','gudang.nama');
		$join=array();
		/*$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'deliveryorder.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);*/
		$join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=> 'deliveryorder.gudang_id',
			'secondtable'	=> 'gudang.gudang_id',
		);

		/*$join[]=array(
			'tablename'	=> 'sales_order',
			'firsttable'	=> 'penjualan.no_so',
			'secondtable'	=> 'sales_order.id',
		);*/

		$data = array(
			'deliveryorder.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_deliveryorder->getPenjualanDetail($column,$join,$data,array());
		if(!empty($trans['user_cetak'])){
		$trans['reqcetak']=$this->model_user_user->getUser($trans['user_cetak']);
		}
		if(!empty($trans['user_setuju'])){
		$trans['usersetujui']=$this->model_user_user->getUser($trans['user_setuju']);

		}
		$trans['setujui']=$custdata;


		//$sales=$this->model_user_user->getUser($trans['sales']);
		//$trans['sales']=$sales['firstname'];
		$sopir=$this->model_user_user->getUser($trans['sopir']);
		$kernet1=$this->model_user_user->getUser($trans['kernet1']);
		$kernet2=$this->model_user_user->getUser($trans['kernet2']);
		$kernet3=$this->model_user_user->getUser($trans['kernet3']);
		//$trans['sales']=$sales['firstname'];
		$trans['sopir']=$sopir['firstname'];
		$trans['kernet1']=$kernet1['firstname'];
		$trans['kernet2']=$kernet2['firstname'];
		$trans['kernet3']=$kernet3['firstname'];
		$products=$this->model_sale_deliveryorder->getPenjualanProducts(array('deliveryorder_product.do_id'	=> $order_id));
		$tabungs=$this->model_sale_deliveryorder->getPenjualanTabungs(array('do_id'	=> $order_id));


		$salesorder="";
		$suratjalan="";
		$cekso=array();
		$ceksj=array();
		$i=1;

		$this->load->model('user/user');
		$this->data['canceldata']=$this->model_user_user->getAksesData($this->user->getId(),4);
		
		foreach($products as $p){

			if(!isset($ceksj[$p['no_sj']])){
				if($i != 1){
					$suratjalan .=", ";
				}
				$suratjalan .=$p['no_sj'];
				$ceksj[$p['no_sj']]=1;
			}
			$i++;
		}

		$trans['suratjalan']=$suratjalan;

		$this->data['order']=$trans;
		$this->data['products']=$products;
		$this->data['tabungs']=$tabungs;
		
		$comp=array(
			'compname' => $this->config->get('config_name'),
			'address'	=> $this->config->get('config_address'),
			'email'	=> $this->config->get('config_email'),
			'phone'	=> $this->config->get('config_telephone'),
			'fax'	=> $this->config->get('config_fax'),
			'web'	=> 'http://nissonindonesia.com'
		);

		$this->load->model('catalog/title');
		$trans['titlename']=$this->model_catalog_title->getTitle($trans['title']);

		$this->data['fulldetail']=array(
			'order'	=> $trans,
			'products'	=> $products,
			'tabungs'	=> $tabungs,
			'address'	=> $this->data['address'],
			'comp'	=> $comp
		);

		//print_r($this->data['fulldetail']);

		$this->data['printer']=$this->config->get('config_printer');
		$this->data['printerstatus']=$this->config->get('config_printer_status');

		//print_r($this->model_sale_customer->getAddress($trans['address_id']));
		$this->data['cancel']= $this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['suratjalan']= $this->url->link('sale/deliveryorder/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=2'. $url, 'SSL');
		$this->data['invoice']= $this->url->link('sale/deliveryorder/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=3'. $url, 'SSL');
		if($this->request->get['view'] == 1){
			$this->template = 'sale/deliveryorder_info.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

		}
		/*if($this->request->get['view'] == 2){
			$this->template = 'sale/suratjalan.tpl';
		}*/
		if($this->request->get['view'] == 3){
			$this->template = 'sale/invoice.tpl';
		}



		$this->response->setOutput($this->render());
	}

	public function cetak(){
		$this->document->setTitle('Delivery Order');
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/deliveryorder');
		$this->load->model('sale/customer');

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),6);

		$column=array('deliveryorder.*','COALESCE(deliveryorder.cetak,0) as totalcetak','gudang.nama');
		$join=array();
		/*$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'deliveryorder.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);*/
		$join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=> 'deliveryorder.gudang_id',
			'secondtable'	=> 'gudang.gudang_id',
		);

		/*$join[]=array(
			'tablename'	=> 'sales_order',
			'firsttable'	=> 'penjualan.no_so',
			'secondtable'	=> 'sales_order.id',
		);*/

		$data = array(
			'deliveryorder.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_deliveryorder->getPenjualanDetail($column,$join,$data,array());
		$products=$this->model_sale_deliveryorder->getPenjualanProducts(array('deliveryorder_product.do_id'	=> $order_id));
		$tabungs=$this->model_sale_deliveryorder->getPenjualanTabungs(array('do_id'	=> $order_id));

		$sopir=$this->model_user_user->getUser($trans['sopir']);
		$kernet1=$this->model_user_user->getUser($trans['kernet1']);
		$kernet2=$this->model_user_user->getUser($trans['kernet2']);
		$kernet3=$this->model_user_user->getUser($trans['kernet3']);
		//$trans['sales']=$sales['firstname'];
		$trans['sopir']=$sopir['firstname'];
		$trans['kernet1']=$kernet1['firstname'];
		$trans['kernet2']=$kernet2['firstname'];
		$trans['kernet3']=$kernet3['firstname'];

		$salesorder="";
		$suratjalan="";
		$cekso=array();
		$ceksj=array();
		$i=1;

		$this->load->model('user/user');
		$this->data['canceldata']=$this->model_user_user->getAksesData($this->user->getId(),4);
		
		foreach($products as $p){

			if(!isset($ceksj[$p['no_sj']])){
				if($i != 1){
					$suratjalan .=", ";
				}
				$suratjalan .=$p['no_sj'];
				$ceksj[$p['no_sj']]=1;
			}
			$i++;
		}

		$trans['suratjalan']=$suratjalan;

		$this->data['order']=$trans;
		$this->data['products']=$products;
		$this->data['tabungs']=$tabungs;
		$this->load->model('catalog/title');
		$trans['titlename']=$this->model_catalog_title->getTitle($trans['title']);

		$this->template = 'sale/deliveryorder_cetak.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function terimatabung(){
		$this->document->setTitle('Daftar Delivery Order');
		$this->load->model('sale/deliveryorder');
		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
      		$order= $this->model_sale_deliveryorder->terimaTabung($this->request->post,$this->request->get['order_id']);

			$this->session->data['success'] = 'Sukses: Tabung berhasil diterima';

			$url = '';

			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
			}
			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			if (isset($this->request->get['filter_shipping_method'])) {
				$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
			}
			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['filter_invoice'])) {
				$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
			}

			if (isset($this->request->get['filter_sales_order'])) {
				$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/deliveryorder');
		$this->load->model('sale/customer');

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),6);

		$column=array('deliveryorder.*','COALESCE(deliveryorder.cetak,0) as totalcetak','customer.name','customer.alamat','customer.telephone','customer.title','customer.email','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'deliveryorder.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=> 'deliveryorder.gudang_id',
			'secondtable'	=> 'gudang.gudang_id',
		);

		/*$join[]=array(
			'tablename'	=> 'sales_order',
			'firsttable'	=> 'penjualan.no_so',
			'secondtable'	=> 'sales_order.id',
		);*/

		$data = array(
			'deliveryorder.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_deliveryorder->getPenjualanDetail($column,$join,$data,array());
		if(!empty($trans['user_cetak'])){
		$trans['reqcetak']=$this->model_user_user->getUser($trans['user_cetak']);
		}
		if(!empty($trans['user_setuju'])){
		$trans['usersetujui']=$this->model_user_user->getUser($trans['user_setuju']);

		}
		$trans['setujui']=$custdata;


		//$sales=$this->model_user_user->getUser($trans['sales']);
		//$trans['sales']=$sales['firstname'];
		$sopir=$this->model_user_user->getUser($trans['sopir']);
		$kernet1=$this->model_user_user->getUser($trans['kernet1']);
		$kernet2=$this->model_user_user->getUser($trans['kernet2']);
		$kernet3=$this->model_user_user->getUser($trans['kernet3']);
		//$trans['sales']=$sales['firstname'];
		$trans['sopir']=$sopir['firstname'];
		$trans['kernet1']=$kernet1['firstname'];
		$trans['kernet2']=$kernet2['firstname'];
		$trans['kernet3']=$kernet3['firstname'];
		$products=$this->model_sale_deliveryorder->getPenjualanProducts(array('do_id'	=> $order_id));
		$tabungs=$this->model_sale_deliveryorder->getPenjualanTabungs(array('do_id'	=> $order_id));


		$salesorder="";
		$suratjalan="";
		$cekso=array();
		$ceksj=array();
		$i=1;

		$this->load->model('user/user');
		$this->data['canceldata']=$this->model_user_user->getAksesData($this->user->getId(),4);

		foreach($products as $p){

			if(!isset($ceksj[$p['no_sj']])){
				if($i != 1){
					$suratjalan .=", ";
				}
				$suratjalan .=$p['no_sj'];
				$ceksj[$p['no_sj']]=1;
			}
			$i++;
		}

		$trans['suratjalan']=$suratjalan;

		$this->data['order']=$trans;
		$this->data['products']=$products;
		$this->data['tabungs']=$tabungs;
		$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);

		
		$this->load->model('catalog/title');
		$trans['titlename']=$this->model_catalog_title->getTitle($trans['title']);


		//print_r($this->data['fulldetail']);

		
		//print_r($this->model_sale_customer->getAddress($trans['address_id']));
		$this->data['cancel']= $this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL');
		if($this->request->get['view'] == 1){
			$this->template = 'sale/deliveryorder_terima.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

		}
		



		$this->response->setOutput($this->render());
	}

	public function ordersukses() {
		$this->document->setTitle('Penjualan Gudang & Website');
		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('sale/deliveryorder');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			print_r($this->request->post);
			$this->model_sale_deliveryorder->ordersukses($this->request->post);

			$this->session->data['success'] = 'Order sukses Penjualan Website berhasil ditambahkan.';

			$this->redirect($this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}


		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}


		$this->data['cancel']= $this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('sale/deliveryorder/ordersukses', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		if($this->request->server['REQUEST_METHOD'] == 'POST'){
			$this->data['orders']=$this->request->post['orders'];
		}else{
			$this->data['orders']=array();
		}


		$this->template = 'sale/sukses_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}
	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Daftar Penjualan');

		$this->load->model('sale/deliveryorder');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//print_r($this->request->post);
			if($this->user->getUsername()=="pawit"){
				echo "<pre>";print_r($this->request->post);exit;
			}
      		$order= $this->model_sale_deliveryorder->addPenjualan($this->request->post);

			$this->session->data['success'] = 'Sukses: Delivery Order berhasil disimpan dengan ID '.$order;

			$url = '';

			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
			}
			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			if (isset($this->request->get['filter_shipping_method'])) {
				$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
			}
			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['filter_invoice'])) {
				$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
			}

			if (isset($this->request->get['filter_sales_order'])) {
				$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('catalog/gudang');

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
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

		/*$this->load->model('sale/customer_group');

		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		*/
		$this->data['token'] = $this->session->data['token'];

		$this->data['cancel']= $this->url->link('sale/deliveryorder', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('sale/deliveryorder/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->request->post['product'])) {
			$this->data['product'] = $this->request->post['product'];
		} else {
			$this->data['product'] = array();
		}

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('catalog/gudang');

		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs(true);

		$this->template = 'sale/deliveryorder_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {
    	/*if (!$this->user->hasPermission('modify', 'gudang/pembelian')) {
      		$this->error['warning'] = 'Permission Denied.';
    	}

    	/*if (empty($this->request->post['date_added'])) {
      		$this->error['warning'] = 'Tanggal input product cacat harus diisi';
    	}*/

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function logcetak(){
		$hasil=array();
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$this->load->model('sale/deliveryorder');
				$id=$this->request->get['id'];
				$trans=$this->model_sale_deliveryorder->getPenjualanDetail(array('COALESCE(cetak,0) as totalcetak'),array(),array('id'=>$id),array());
				$totalcetak=$trans['totalcetak'];
				$this->model_sale_deliveryorder->updatePenjualan(array('cetak'=>$totalcetak+1),array('id'=>$id));
				$hasil['status']=1;
			}
		}
		$this->response->setOutput(json_encode($hasil));
	}

	public function cetakulang(){
		$hasil=array();
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$this->load->model('sale/deliveryorder');
				$id=$this->request->get['id'];
				$trans=$this->model_sale_deliveryorder->getPenjualanDetail(array('COALESCE(cetak,0) as totalcetak'),array(),array('id'=>$id),array());
				$totalcetak=$trans['totalcetak'];
				if($totalcetak == 1){
					$this->model_sale_deliveryorder->updatePenjualan(array('cetakulang'=>2,'alasan_cetak'=> $this->request->get['alasan'],'user_cetak'=>$this->user->getId()),array('id'=>$id));
					$hasil['status']=1;
				}else{

						$hasil['status']=0;
				}
			}
		}
		$this->response->setOutput(json_encode($hasil));
	}
	public function setujui(){
		$hasil=array();
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$this->load->model('sale/deliveryorder');
				$id=$this->request->get['id'];
				$this->load->model('user/user');
				$custdata=$this->model_user_user->getAksesData($this->user->getId(),6);

				if($custdata){
					$this->model_sale_deliveryorder->updatePenjualan(array('cetakulang'=>$this->request->get['status'],'user_setuju'=>$this->user->getId()),array('id'=>$id));
					$hasil['status']=1;
				}else{
					$hasil['status']=0;
				}

			}
		}
		$this->response->setOutput(json_encode($hasil));
	}

	public function detail(){
		$hasil = array();

		//$this->load->model('pembelian/permintaanpembelian');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){

			$this->load->model('sale/deliveryorder');
			$this->load->model('sale/salesorder');
			$this->load->model('sale/invoice');
			$this->load->model('sale/customer');

			if($this->request->get['j'] == 3){
				$column=array('penjualan.*','customer.name','customer.telephone','customer.email');
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'penjualan.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);

				$data = array(
					'penjualan.id'	=> $this->request->get['id'],

				);
				$this->load->model('user/user');
				$this->load->model('catalog/gudang');
				$trans=$this->model_sale_deliveryorder->getPenjualanDetail($column,$join,$data,array());
				//$so=$this->db->first('sales_order',array('id' => $trans['no_so']));
				$trans['usia']=1;

				//$sales=$this->model_user_user->getUser($trans['sales']);
				$products=$this->model_sale_deliveryorder->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));
				//$dp=$this->model_sale_invoice->getTotalDp($trans['no_so'],1);
				$dp=0;
			}else{
				$column=array('sales_order.*','customer.name','customer.telephone','customer.email');
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'sales_order.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);

				$data = array(
					'sales_order.id'	=> $this->request->get['id'],

				);
				$this->load->model('user/user');
				$this->load->model('catalog/gudang');
				$trans=$this->model_sale_salesorder->getPenjualanDetail($column,$join,$data,array());

				//$sales=$this->model_user_user->getUser($trans['sales']);
				$products=$this->model_sale_salesorder->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));
				$dp=$this->model_sale_invoice->getTotalDp($this->request->get['id'],1);
			}
			//cek dp

			$trans['dp']=$dp;

			$hasil=array(
				'order'	=> $trans,
				'products'	=> $products,
				//'address'	=> $this->data['address']
			);
		}
	}
		$this->response->setOutput(json_encode($hasil));
	}

	public function detailTanpaInvoice(){
		$hasil = array();

		//$this->load->model('pembelian/permintaanpembelian');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){

			$this->load->model('sale/deliveryorder');
			//$products=$this->model_sale_deliveryorder->
			/*$this->load->model('sale/salesorder');
			$this->load->model('sale/invoice');
			$this->load->model('sale/customer');*/

			//if($this->request->get['j'] == 3){
				/*$column=array('penjualan.*','customer.name','customer.telephone','customer.email');
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'penjualan.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);

				$data = array(
					'penjualan.id'	=> $this->request->get['id'],

				);
				$this->load->model('user/user');
				$this->load->model('catalog/gudang');
				$trans=$this->model_sale_deliveryorder->getPenjualanDetail($column,$join,$data,array());
				$so=$this->db->first('sales_order',array('id' => $trans['no_so']));
				$trans['usia']=$so['usia'];

				//$sales=$this->model_user_user->getUser($trans['sales']);
				$products=$this->model_sale_deliveryorder->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));
				$dp=$this->model_sale_invoice->getTotalDp($trans['no_so'],1);*/
		/*	}else{
				$column=array('sales_order.*','customer.name','customer.telephone','customer.email');
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'sales_order.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);

				$data = array(
					'sales_order.id'	=> $this->request->get['id'],

				);
				$this->load->model('user/user');
				$this->load->model('catalog/gudang');
				$trans=$this->model_sale_salesorder->getPenjualanDetail($column,$join,$data,array());

				//$sales=$this->model_user_user->getUser($trans['sales']);
				$products=$this->model_sale_salesorder->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));
				$dp=$this->model_sale_invoice->getTotalDp($this->request->get['id'],1);
			}*/
			//cek dp

			//$trans['dp']=$dp;
			if(!empty($this->request->get['gudang_id'])){
				$products=$this->model_sale_deliveryorder->getSjTanpaInv($this->request->get['id'],$this->request->get['gudang_id']);

				$hasil=array(
					'order'	=> array(),
					'products'	=> $products,
					//'address'	=> $this->data['address']
				);
			}
		}
	}
		$this->response->setOutput(json_encode($hasil));
	}

	public function autocompletetabung() {
		$json = array();

		//if (isset($this->request->get['filter_name']) ) {
			$this->load->model('sale/deliveryorder');

			
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

			if (isset($this->request->get['jenistabung'])) {
				$jenistabung = $this->request->get['jenistabung'];
			} else {
				$jenistabung = 1;
			}

			if (isset($this->request->get['do_id'])) {
				$do_id = $this->request->get['do_id'];
			} else {
				$do_id = 0;
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

			if($do_id > 0){
				$data = array(
					'filter_no_tabung'	  => $filter_no_tabung,
					'filter_status'	  => $fiter_status,
					'filter_product_id'	  => $filter_product_id,
					'filter_status'	=> $filter_status,
					'do_id'	=> $do_id,
					'start'               => 0,
					'limit'               => $limit
				);

				$results = $this->model_sale_deliveryorder->getTabungs($data);


				foreach ($results as $result) {
					$json[] = array(
						'id' => $result['doproduct_id'],
						'text'       => strip_tags(html_entity_decode($result['no_tabung'], ENT_QUOTES, 'UTF-8')),

					);
				}
			}

			


		$this->response->setOutput(json_encode($json));
	}
	public function detailtabung(){
		$hasil = array();

		$this->load->model('sale/deliveryorder');
		if(isset($this->request->get['product_id'])){
			if(!empty($this->request->get['product_id'])){
				$product_id=$this->request->get['product_id'];
				$hasil=$this->model_sale_deliveryorder->getTabung($product_id);


			}
		}
		$this->response->setOutput(json_encode($hasil));


	}
}
?>
