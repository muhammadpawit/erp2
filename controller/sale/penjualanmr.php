<?php
class ControllerSalePenjualanmr extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Daftar Penjualan');

		$this->load->model('sale/penjualanmr');

		$this->getList();
	}
	public function terima(){
		$this->load->model('sale/penjualanmr');
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
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
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
				$penj=$this->model_sale_penjualanmr->getPenjualan(array('id'=>$order_id));
				if($penj['status'] == 1){
					$this->model_sale_penjualanmr->updatePenjualan(array('status' => 2),array('id'	=> $order_id));
					$this->session->data['success'] = 'Sukses: Data Pengiriman Barang berhasil diterima';
				}else{
					if($penj['status'] == 2){
						$this->session->data['warning'] = 'Peringatan: Data Pengiriman Barang telah diterima';
					}else{
						$this->session->data['warning'] = 'Peringatan: Data Pengiriman Barang telah dibatalkan';
					}
				}
				$this->redirect($this->url->link('sale/penjualanmr', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				$this->redirect($this->url->link('sale/penjualanmr', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/penjualanmr', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	}

	public function batalkan(){
		$this->load->model('sale/penjualanmr');
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
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
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
				$penj=$this->model_sale_penjualanmr->getPenjualan(array('id'=>$order_id));
				if($penj['status'] == 1){
					$this->model_sale_penjualanmr->cancelPenjualan($order_id);
					$this->session->data['success'] = 'Sukses: Data Pengiriman Barang berhasil dibatalkan';
				}else{
					if($penj['status'] == 2){
						$this->session->data['warning'] = 'Peringatan: Data Pengiriman Barang telah diterima';
					}else{
						$this->session->data['warning'] = 'Peringatan: Data Pengiriman Barang telah dibatalkan';
					}
				}
				$this->redirect($this->url->link('sale/penjualanmr', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				$this->redirect($this->url->link('sale/penjualanmr', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/penjualanmr', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	}


	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Daftar Penjualan');

		$this->load->model('sale/penjualanmr');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_penjualanmr->updateOrderPenjualan($this->request->post, array('id'=>$this->request->get['id']));

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
			if (isset($this->request->get['filter_shipping_method'])) {
				$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
			}
			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/penjualanmr', 'token=' . $this->session->data['token'].$url, 'SSL'));
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
		if (isset($this->request->get['filter_shipping_method'])) {
			$filter_shipping_method = $this->request->get['filter_shipping_method'];
		} else {
			$filter_shipping_method = null;
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = null;
		}
		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}

		if (isset($this->request->get['filter_statustabung'])) {
			$filter_statustabung= $this->request->get['filter_statustabung'];
		} else {
			$filter_statustabung = null;
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
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/penjualanmr', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

   	$this->data['insert'] = $this->url->link('sale/penjualanmr/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/penjualanmr/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

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

		$arrsql=implode(',',$gudangs);

		$this->data['penjualans'] = array();

		//$column=array('aset.penjualan_pameran_id','aset.name as name','aset.tglpembelian','aset.hargabeli','aset.status','kelompok_aset.name as kelompok','kelompok_aset.jenis_aset as jenis');
		$column=array('penjualan_mr.*','sales_ordermr.no_so as nom_so','gudang.nama','customer.name','customer.alamat','customer.telephone','customer.email');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'penjualan_mr.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=> 'penjualan_mr.gudang_id',
			'secondtable'	=> 'gudang.gudang_id',
		);
		$join[]=array(
			'tablename'	=> 'sales_ordermr',
			'firsttable'	=> 'penjualan_mr.no_so',
			'secondtable'	=> 'sales_ordermr.id',
		);
		$order=array(
			'id'	=> 'DESC',

		);
		$data = array(
			'penjualan_mr.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'penjualan_mr.gudang_id'	=>array('IN',$arrsql),
			'penjualan_mr.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			'penjualan_mr.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):$filter_tanggal,
			'penjualan_mr.total'	=> empty($filter_total)?array('>=',0):$filter_total,
			'penjualan_mr.status'	=> empty($filter_status)?array('>',0):$filter_status,
			'penjualan_mr.no_invoice'	=> $filter_shipping_method != null ?array('>',0):$filter_shipping_method,
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$results = $this->model_sale_penjualanmr->getPenjualans($column,$join,$data,$order,$limit,$offset);
		$product_total = $this->model_sale_penjualanmr->totalPenjualans($data);
		//print_r($results);
		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('sale/penjualanmr/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['id'], 'SSL')
			);


			if($result['status'] == 1){
				$action[] = array(
					'text' => 'Diterima',
					'href' => $this->url->link('sale/penjualanmr/terima', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['id'], 'SSL')
				);
			}

			if($result['status'] == 1){
				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('sale/penjualanmr/batalkan', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['id'], 'SSL')
				);
			}

			/*if($result['status'] == 2){
				$action[] = array(
					'text' => 'Email Invoice',
					'href' => $this->url->link('sale/penjualanmr/email', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['order_gudang_id'], 'SSL')
				);
			}

			/*$action[] = array(
				'text' => 'Invoice',
				'href' => $this->url->link('sale/penjualanmr/tampil', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['order_gudang_id'], 'SSL')
			);*/
			/*$action[] = array(
				'text' => 'Surat Jalan',
				'href' => $this->url->link('sale/penjualanmr/tampil', 'token=' . $this->session->data['token'] . '&view=2&order_id=' . $result['id'], 'SSL')
			);*/


			/*if($result['payment_status'] == 3 & $result['status_pengiriman'] == 2){
				$action[] = array(
					'text' => 'Selesai',
					'href' => $this->url->link('sale/penjualanmr/selesai', 'token=' . $this->session->data['token'] . '&order_gudang_id=' . $result['order_gudang_id'], 'SSL')
				);

			}*/
			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'customer_id'        => $result['customer_id'],
				'no_sj'        => $result['no_sj'],
				'no_so'        => $result['no_so'],
				'nama'        => $result['nama'],
				'nom_so'	=> $result['nom_so'],
				'no_invoice'        => empty($result['no_invoice'])?'Belum Ada Invoice':$result['no_invoice'],
				'total'        => $this->currency->format($result['total']),
				'name'	=> $result['name'],
				'email'	=> $result['email'],
				'telephone'	=> $result['telephone'],
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
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/penjualanmr', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_tanggal']	= $filter_tanggal;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'sale/penjualanmr_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('sale/penjualanmr');

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
				'no_invoice'         => array('LIKE',$filter_order_id),

			);
			$offset=0;
			$limit=10;

			$results = $this->model_sale_penjualanmr->getPenjualans(array(),array(),$data,array(),10,0);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_sj']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}
	public function autocompletedetail(){
		$rests = array();



			if (isset($this->request->get['q'])) {
				$filter_order_id = $this->request->get['q'];
			} else {
				$filter_order_id = '';
			}

			if (isset($this->request->get['p'])) {
				$status_pengiriman = $this->request->get['p'];
			} else {
				$status_pengiriman = null;
			}

			if (isset($this->request->get['jenis'])) {
				$jenis = $this->request->get['jenis'];
			} else {
				$jenis = 1;
			}

			if (isset($this->request->get['jenispenjualan'])) {
				$jenispenjualan = $this->request->get['jenispenjualan'];
			} else {
				$jenispenjualan = 1;
			}

			if (isset($this->request->get['customer_id'])) {
				$customer_id = $this->request->get['customer_id'];
			} else {
				$customer_id = null;
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}
			if($jenis == 1 | $jenis == 2){
				//salesorder
				if($jenispenjualan == 1){
					//mp
					$this->load->model('sale/salesorder');
					$column=array('sales_order.no_so','sales_order.id','sales_order.total','customer.name','sales_order.date_added');
					$join=array();
					$join[]=array(
						'tablename'	=> 'customer',
						'firsttable'	=> 'sales_order.customer_id',
						'secondtable'	=> 'customer.customer_id'
					);

					$data = array(
						'no_so'         => array('LIKE',$filter_order_id),
						'sales_order.status'         => 1,
						'sales_order.customer_id'	=>$customer_id != 'null'?$customer_id:array('>=',1),


					);
					$offset=0;
					$limit=10;

					$results = $this->model_sale_salesorder->getPenjualans($column,$join,$data,array(),10,0);
					foreach($results as $r){
						$rests[]=array(
							'id'	=> $r['id'],
							'text'	=> $r['no_so'].' Customer '.$r['name'].' - Tanggal '.date('d/m/y',strtotime($r['date_added'])).' - '.' Total '.$this->currency->format($r['total']),
							'total'	=>$this->currency->format($r['total']),
							'plaintotal'	=> $r['total']
						);
					}
				}
				if($jenispenjualan == 2){
					//mr
					$this->load->model('sale/salesordermr');
					$column=array('sales_ordermr.no_so','sales_ordermr.id','sales_ordermr.total','customer.name','sales_ordermr.date_added');
					$join=array();
					$join[]=array(
						'tablename'	=> 'customer',
						'firsttable'	=> 'sales_ordermr.customer_id',
						'secondtable'	=> 'customer.customer_id'
					);

					$data = array(
						'no_so'         => array('LIKE',$filter_order_id),
						'sales_ordermr.status'         => 1,
						'sales_ordermr.customer_id'	=>$customer_id != 'null'?$customer_id:array('>=',1),


					);
					$offset=0;
					$limit=10;

					$results = $this->model_sale_salesordermr->getPenjualans($column,$join,$data,array(),10,0);
					foreach($results as $r){
						$rests[]=array(
							'id'	=> $r['id'],
							'text'	=> $r['no_so'].' Customer '.$r['name'].' - Tanggal '.date('d/m/y',strtotime($r['date_added'])).' - '.' Total '.$this->currency->format($r['total']),
							'total'	=>$this->currency->format($r['total']),
							'plaintotal'	=> $r['total']
						);
					}
				}
				if($jenispenjualan == 3){
					//bahanbaku
					$this->load->model('sale/salesorderbahanbaku');
					$column=array('sales_order_bahanbaku.no_so','sales_order_bahanbaku.id','sales_order_bahanbaku.total','customer.name','sales_order_bahanbaku.date_added');
					$join=array();
					$join[]=array(
						'tablename'	=> 'customer',
						'firsttable'	=> 'sales_order_bahanbaku.customer_id',
						'secondtable'	=> 'customer.customer_id'
					);

					$data = array(
						'no_so'         => array('LIKE',$filter_order_id),
						'sales_order_bahanbaku.status'         => 1,
						'sales_order_bahanbaku.customer_id'	=>$customer_id != 'null'?$customer_id:array('>=',1),


					);
					$offset=0;
					$limit=10;

					$results = $this->model_sale_salesorder_bahanbaku->getPenjualans($column,$join,$data,array(),10,0);
					foreach($results as $r){
						$rests[]=array(
							'id'	=> $r['id'],
							'text'	=> $r['no_so'].' Customer '.$r['name'].' - Tanggal '.date('d/m/y',strtotime($r['date_added'])).' - '.' Total '.$this->currency->format($r['total']),
							'total'	=>$this->currency->format($r['total']),
							'plaintotal'	=> $r['total']
						);
					}
				}
			}

			if($jenis == 3){
				//salesorder
				if($jenispenjualan == 1){
					//mp
					$this->load->model('sale/penjualan');
					$column=array('penjualan.no_sj','penjualan.id','penjualan.total','customer.name','penjualan.date_added');
					$join=array();
					$join[]=array(
						'tablename'	=> 'customer',
						'firsttable'	=> 'penjualan.customer_id',
						'secondtable'	=> 'customer.customer_id'
					);

					$data = array(
						'no_sj'         => array('LIKE',$filter_order_id),
						//'penjualan.status'         => $status_pengiriman != null?$status_pengiriman:array('>=',1),
						'penjualan.customer_id'	=>$customer_id != null?$customer_id:array('>=',1),
						'no_invoice'	=> array('=','')

					);
					$offset=0;
					$limit=10;

					$results = $this->model_sale_penjualan->getPenjualans($column,$join,$data,array(),10,0);
					foreach($results as $r){
						$rests[]=array(
							'id'	=> $r['id'],
							'text'	=> $r['no_sj'].' - Tanggal '.date('d/m/y',strtotime($r['date_added'])).' - '.' Total '.$this->currency->format($r['total']),
							'total'	=>$this->currency->format($r['total']),
							'plaintotal'	=> $r['total']
						);
					}

				}
				if($jenispenjualan == 2){
					//mr
					$this->load->model('sale/penjualanmr');
					$column=array('penjualan_mr.no_sj','penjualan_mr.id','penjualan_mr.total','customer.name','penjualan_mr.date_added');
					$join=array();
					$join[]=array(
						'tablename'	=> 'customer',
						'firsttable'	=> 'penjualan_mr.customer_id',
						'secondtable'	=> 'customer.customer_id'
					);

					$data = array(
						'no_sj'         => array('LIKE',$filter_order_id),
						//'penjualan.status'         => $status_pengiriman != null?$status_pengiriman:array('>=',1),
						'penjualan_mr.customer_id'	=>$customer_id != null?$customer_id:array('>=',1),
						'no_invoice'	=> array('=','')

					);
					$offset=0;
					$limit=10;

					$results = $this->model_sale_penjualanmr->getPenjualans($column,$join,$data,array(),10,0);
					foreach($results as $r){
						$rests[]=array(
							'id'	=> $r['id'],
							'text'	=> $r['no_sj'].' - Tanggal '.date('d/m/y',strtotime($r['date_added'])).' - '.' Total '.$this->currency->format($r['total']),
							'total'	=>$this->currency->format($r['total']),
							'plaintotal'	=> $r['total']
						);
					}
				}
				if($jenispenjualan == 3){
					//bahanbaku
					$this->load->model('sale/penjualanmrbahanbaku');
					$column=array('penjualan_bahanbaku.no_sj','penjualan_bahanbaku.id','penjualan_bahanbaku.total','customer.name','penjualan_bahanbaku.date_added');
					$join=array();
					$join[]=array(
						'tablename'	=> 'customer',
						'firsttable'	=> 'penjualan_bahanbaku.customer_id',
						'secondtable'	=> 'customer.customer_id'
					);

					$data = array(
						'no_sj'         => array('LIKE',$filter_order_id),
						'penjualan_bahanbaku.status'         => $status_pengiriman != null?$status_pengiriman:array('>=',1),
						'penjualan_bahanbaku.customer_id'	=>$customer_id != null?$customer_id:array('>=',1),
						'no_invoice'	=> array('=','')

					);
					$offset=0;
					$limit=10;

					$results = $this->model_sale_penjualanmrbahanbaku->getPenjualans($column,$join,$data,array(),10,0);
					foreach($results as $r){
						$rests[]=array(
							'id'	=> $r['id'],
							'text'	=> $r['no_sj'].' - Tanggal '.date('d/m/y',strtotime($r['date_added'])).' - '.' Total '.$this->currency->format($r['total']),
							'total'	=>$this->currency->format($r['total']),
							'plaintotal'	=> $r['total']
						);
					}
				}
			}

		$this->response->setOutput(json_encode($rests));
	}

	public function tampil(){
		$this->document->setTitle('Daftar Penjualan');
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

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
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
			}else{
				$this->redirect($this->url->link('sale/penjualanmr', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/penjualanmr', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/penjualanmr');
		$this->load->model('sale/customer');
		$column=array('penjualan_mr.*','sales_ordermr.no_so as salesorder','customer.name','customer.alamat','customer.telephone','customer.title','customer.email','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'penjualan_mr.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=> 'penjualan_mr.gudang_id',
			'secondtable'	=> 'gudang.gudang_id',
		);

		$join[]=array(
			'tablename'	=> 'sales_ordermr',
			'firsttable'	=> 'penjualan_mr.no_so',
			'secondtable'	=> 'sales_ordermr.id',
		);

		$data = array(
			'penjualan_mr.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_penjualanmr->getPenjualanDetail($column,$join,$data,array());

		$sales=$this->model_user_user->getUser($trans['sales']);
		$trans['sales']=$sales['firstname'];
		$sopir=$this->model_user_user->getUser($trans['sopir']);
		$trans['sales']=$sales['firstname'];
		$trans['sopir']=$sopir['firstname'];
		$trans['kernets']=$this->model_sale_penjualanmr->getPenjualanKernets(array('tttk_id'	=> $order_id));
		$products=$this->model_sale_penjualanmr->getPenjualanProducts(array('sales_order_id'	=> $order_id));

		$this->data['order']=$trans;
		$this->data['products']=$products;
		$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);

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
			'address'	=> $this->data['address'],
			'comp'	=> $comp
		);

		//print_r($this->data['fulldetail']);

		$this->data['printer']=$this->config->get('config_printer');
		$this->data['printerstatus']=$this->config->get('config_printer_status');

		//print_r($this->model_sale_customer->getAddress($trans['address_id']));
		$this->data['cancel']= $this->url->link('sale/penjualanmr', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['suratjalan']= $this->url->link('sale/penjualanmr/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=2'. $url, 'SSL');
		$this->data['invoice']= $this->url->link('sale/penjualanmr/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=3'. $url, 'SSL');
		if($this->request->get['view'] == 1){
			$this->template = 'sale/penjualanmr_info.tpl';
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

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Daftar Penjualan');

		$this->load->model('sale/penjualanmr');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//print_r($this->request->post);
      $order= $this->model_sale_penjualanmr->addPenjualan($this->request->post);

			$this->session->data['success'] = 'Sukses: Daftar Penjualan berhasil disimpan dengan ID '.$order;

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

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/penjualanmr', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
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

		$this->data['cancel']= $this->url->link('sale/penjualanmr', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('sale/penjualanmr/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->template = 'sale/penjualanmr_form.tpl';
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

	public function cetak(){
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$this->load->model();
				$id=$this->request->get['id'];

			}
		}
	}
	public function detail(){
		$hasil = array();

		//$this->load->model('pembelian/permintaanpembelian');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){

			$this->load->model('sale/penjualanmr');
			$this->load->model('sale/salesordermr');
			$this->load->model('sale/invoice');
			$this->load->model('sale/customer');

			if($this->request->get['j'] == 3){
				$column=array('penjualan_mr.*','customer.name','customer.telephone','customer.email');
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'penjualan_mr.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);

				$data = array(
					'penjualan_mr.id'	=> $this->request->get['id'],

				);
				$this->load->model('user/user');
				$this->load->model('catalog/gudang');
				$trans=$this->model_sale_penjualanmr->getPenjualanDetail($column,$join,$data,array());
				$so=$this->db->first('sales_ordermr',array('id' => $trans['no_so']));
				$trans['usia']=$so['usia'];
				//$sales=$this->model_user_user->getUser($trans['sales']);
				$products=$this->model_sale_penjualanmr->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));
				$dp=$this->model_sale_invoice->getTotalDp($trans['no_so'],1);
			}else{
				$column=array('sales_ordermr.*','customer.name','customer.telephone','customer.email');
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'sales_ordermr.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);

				$data = array(
					'sales_ordermr.id'	=> $this->request->get['id'],

				);
				$this->load->model('user/user');
				$this->load->model('catalog/gudang');
				$trans=$this->model_sale_salesordermr->getPenjualanDetail($column,$join,$data,array());

				//$sales=$this->model_user_user->getUser($trans['sales']);
				$products=$this->model_sale_salesordermr->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));
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
}
?>
