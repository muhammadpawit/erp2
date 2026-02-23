<?php
class ControllerSaleProforma extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Proforma Invoice');

		$this->load->model('sale/proforma');

		$this->getList();
	}

	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Proforma Invoice');

		$this->load->model('sale/proforma');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_proforma->updateOrderPenjualan($this->request->post, array('id'=>$this->request->get['id']));

			$this->session->data['success'] = 'Proforma Invoice berhasil diperbarui';

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

			if (isset($this->request->get['filter_jenisorder'])) {
				$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/proforma', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function batal() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Invoice');

		$this->load->model('sale/proforma');

		$this->load->model('user/user');


		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),5);
		if($canceldata == 1){
		if (isset($this->request->get['order_id'])) {
			$this->model_sale_proforma->voidInvoice($this->request->get['order_id']);

			$this->session->data['success'] = 'Invoice berhasil dibatalkan';

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

			if (isset($this->request->get['filter_jenisorder'])) {
				$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/proforma', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}
	}else{
		$this->session->data['warning'] = 'Anda tidak diijinkan untuk membatalkan proforma invoice';
		$this->redirect($this->url->link('sale/proforma', 'token=' . $this->session->data['token'].$url, 'SSL'));
	}

		$this->getList();
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
		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = null;
		}

		if (isset($this->request->get['filter_tanggalakhir'])) {
			$filter_tanggalakhir = $this->request->get['filter_tanggalakhir'];
		} else {
			$filter_tanggalakhir = null;
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
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_tanggalakhir'])) {
			$url .= '&filter_tanggalakhir=' . $this->request->get['filter_tanggalakhir'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/proforma', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/proforma/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/proforma/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['penjualans'] = array();
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


		$column=array('proforma_invoice.*','customer.name','customer.email','customer.telephone');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'proforma_invoice.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);

		$data = array(
			'proforma_invoice.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'proforma_invoice.gudang_id'	=>array('IN',$arrsql),
			'proforma_invoice.hapus'	=>array('<>',1),
			'proforma_invoice.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			'proforma_invoice.status'	=> empty($filter_status)?array('>',0):$filter_status,
			//'proforma_invoice.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):$filter_tanggal
			'proforma_invoice.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):array('>=',$filter_tanggal,'<=',$filter_tanggalakhir),
		);

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$data['customer.sales']=$this->user->getId();
		}

		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),5);


		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$order=array();
		$order=array('proforma_invoice.date_added' => 'DESC','proforma_invoice.id'=>'DESC');
		//$this->load->model('sale/proforma');

		$results = $this->model_sale_proforma->getPenjualans($column,$join,$data,$order,$limit,$offset);
		$pt = $this->model_sale_proforma->getPenjualans($column,$join,$data,$order,0,null);
		$product_total=count($pt);
		if($this->user->getUsername()=="pawitd"){
			echo "<pre>";print_r($results);exit;
		}
		$user=$this->user->getUsername();
		// $date=date('Y-m-d H:i:s');
		foreach ($results as $result) {
			$action = array();
				if($user=='admin' OR $user=='Sonny' && $result['gudang_id']==1 && $result['date_added']>='2020-01-03 00:00:00'){
					$action[] = array(
						'text' => 'Tampil',
						'href' => $this->url->link('sale/proforma/tampilnew', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['id'].$url, 'SSL')
					);
				}else if($user=='anita' OR $user=='pawit' && $result['gudang_id']==1 && $result['date_added']>='2020-01-03 00:00:00'){
					$action[] = array(
						'text' => 'Tampil',
						'href' => $this->url->link('sale/proforma/tampilnew', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['id'].$url, 'SSL')
					);
				}else{
					$action[] = array(
						'text' => 'Tampil',
						'href' => $this->url->link('sale/proforma/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['id'].$url, 'SSL')
					);
				}

			$namagudang=$this->model_catalog_gudang->getGudang($result['gudang_id']);
			if($result['gudang_id']==1){
				$metodes=substr($result['metode_pembayaran'],0,1);
				$metode = $metodes== 1?'Tunai':($metodes == 2?'COD':($metodes == 3?'Kredit':'CBD'));
			}else{
				$metode = $result['metode_pembayaran'] == 1?'Tunai':($result['metode_pembayaran'] == 2?'COD':($result['metode_pembayaran'] == 3?'Kredit':'CBD'));
			}
			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'no_faktur'        => $result['no_faktur'],
				'tgllunas'=>date('d/m/y',strtotime($result['tgllunas'])),
				//'sales_order_id'        => $result['sales_order_id'],
				'name'	=> $result['name'],
				'namagudang'	=> $namagudang['nama'],
				'email'	=> $result['email'],
				'telephone'	=> $result['telephone'],
				'total'	=> $this->currency->format($result['totaltagihan']),
				'totalbayar'	=> $this->currency->format($result['totalbayar']),
				'status'	=> $result['status'],
				'tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
				'jatuhtempo'	=>date('d/m/y',strtotime($result['jatuhtempo'])),
				'metode_pembayaran'	=>$metode,
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'      => $action
			);
		}

		if (isset($this->session->data['warning'])) {
			$this->data['warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else if (isset($this->error['warning'])) {
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
		if (isset($this->request->get['filter_tanggalakhir'])) {
			$url .= '&filter_tanggalakhir=' . $this->request->get['filter_tanggalakhir'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}


		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/proforma', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_tanggal']	= $filter_tanggal;
		$this->data['filter_tanggalakhir'] = $filter_tanggalakhir;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'sale/proforma_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('sale/proforma');

			if (isset($this->request->get['q'])) {
				$filter_order_id = $this->request->get['q'];
			} else {
				$filter_order_id = '';
			}

			if (isset($this->request->get['p'])) {
				$p = $this->request->get['p'];
			} else {
				$p = null;
			}

			if (isset($this->request->get['customer_id'])) {
				$customer_id = $this->request->get['customer_id'];
			} else {
				$customer_id = null;
			}
			/*if (isset($this->request->get['p'])) {
				$p = $this->request->get['p'];
			} else {
				$p = '';
			}*/


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_faktur'         => array('LIKE',$filter_order_id),
				'metode_pembayaran'	=> $p != null?($p == 4?array('<>',3):$p):array('>=',1),
				'customer_id'	=> $customer_id != null ?$customer_id:array('>=',1),
				'status'	=> array('<>',4)
			);
			$offset=0;
			$limit=10;

			$results = $this->model_sale_proforma->getPenjualans(array(),array(),$data,array(),10,0);
			foreach($results as $r){
				/*if($r['jenisinvoice'] == 2){
					$total=$this->currency->format($r['totaltagihan']);
				}*/
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_faktur'].' Total Tagihan '.$this->currency->format($r['totaltagihan'] - $r['totalbayar'])
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function detailinvoice(){
		$hasil = array();

		$this->load->model('sale/proforma');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$column=array();
				$id=$this->request->get['id'];
				$data = array(
					'id'      =>$id
				);

				$hasil=$this->model_sale_proforma->getPenjualan($data);
			//	$hasil['pdeposit']=$this->currency->format($hasil['deposit']);


			}
		}
		$this->response->setOutput(json_encode($hasil));


	}
	
	public function cek()
	{
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('sale/proforma', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/proforma', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->load->model('sale/proforma');
		$this->load->model('sale/customer');
		$column=array('proforma_invoice.*','COALESCE(cetak,0) as totalcetak','customer.name','customer.npwp','customer.title','customer.telephone','customer.email','customer.alamat','customer.alamat as address');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'proforma_invoice.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);


		$data = array(
			'proforma_invoice.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_proforma->getPenjualanDetail($column,$join,$data,array());
		$products=$this->model_sale_proforma->getPenjualanProducts($trans['jenispenjualan'],array('sales_order_id'	=> $order_id));

		//referensi
		if($trans['jenispenjualan'] == 1){
			$this->load->model('sale/salesorder');
			$ref=$this->model_sale_salesorder->getPenjualan(array('id'=>$trans['referensi']));
			$trans['ref']=$ref['no_so'];
		}
		if($trans['jenispenjualan'] == 2){
			$this->load->model('sale/salesordermr');
			$ref=$this->model_sale_salesordermr->getPenjualan(array('id'=>$trans['referensi']));
			$trans['ref']=$ref['no_so'];
		}
		if($trans['jenispenjualan'] == 3){
			$this->load->model('sale/salesorderbahanbaku');
			$ref=$this->model_sale_salesorderbahanbaku->getPenjualan(array('id'=>$trans['referensi']));
			$trans['ref']=$ref['no_so'];
		}

		$this->load->model('keuangan/bank');
		$this->data['banks']=$this->model_keuangan_bank->getBanks(array(),array(),array('display_order' => 1,'hapus'	=> array('<',1)),array(),0,null);
		//bank pembayaran

		$this->data['order']=$trans;
		$this->data['products']=$products;
		//$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);
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
			'comp'	=> $comp,
			'order'	=> $trans,
			'products'	=> $products,
			//'address'	=> $this->data['address'],
			'banks'	=> $this->data['banks']
		);
		$this->model_sale_proforma->ubah(5725);
		$p = $this->model_sale_proforma->pro(5725);
		echo "<pre>";
		print_r($p);
		echo "</pre>";
	}

	// baru 2 Januari 2019
	public function tampilnew(){
		$this->document->setTitle('Invoice');
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

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('sale/proforma', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/proforma', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/proforma');
		$this->load->model('sale/customer');
		$column=array('proforma_invoice.*','COALESCE(cetak,0) as totalcetak','customer.name','customer.npwp','customer.title','customer.telephone','customer.email','customer.alamat','customer.alamat as address');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'proforma_invoice.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$data = array(
			'proforma_invoice.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_proforma->getPenjualanDetail($column,$join,$data,array());
		$products=$this->model_sale_proforma->getPenjualanProducts($trans['jenispenjualan'],array('sales_order_id'	=> $order_id));
		foreach($products as $p){

			if(!isset($cekso[$p['no_salesorder']])){
				if($i != 1){
					$salesorder .=",";
				}
				//$salesorder .=$p['no_salesorder'];
				$salesorder .=$p['id_salesorder'];
				$cekso[$p['no_salesorder']]=1;
			}
			$i++;
		}

		//referensi
		//$this->data['salesorder'] = $this->model_sale_proforma->getsonew();
		$refso=array();
		$salesorder=array();
		$refso = $this->model_sale_proforma->refso($order_id);
		foreach($refso as $so){
			$salesorder[] = $this->model_sale_proforma->getsonew($so['referensi_so']);
		}
		//echo "<pre>";print_r($salesorder);exit;
		$this->data['salesorder']=$salesorder;
		if($trans['jenispenjualan'] == 1){
			$this->load->model('sale/salesorder');
			//$ref=$this->model_sale_salesorder->getPenjualan(array('id'=>$trans['referensi']));
			//$trans['ref']=$ref['no_so'];
		}
		if($trans['jenispenjualan'] == 2){
			$this->load->model('sale/salesordermr');
			$ref=$this->model_sale_salesordermr->getPenjualan(array('id'=>$trans['referensi']));
			$trans['ref']=$ref['no_so'];
		}
		if($trans['jenispenjualan'] == 3){
			$this->load->model('sale/salesorderbahanbaku');
			$ref=$this->model_sale_salesorderbahanbaku->getPenjualan(array('id'=>$trans['referensi']));
			$trans['ref']=$ref['no_so'];
		}

		$this->load->model('keuangan/bank');
		$this->data['banks']=$this->model_keuangan_bank->getBanks(array(),array(),array('display_order' => 1,'hapus'	=> array('<',1)),array(),0,null);
		$this->data['listbank']=$this->model_keuangan_bank->listbank();
		//bank pembayaran

		$this->data['order']=$trans;
		$this->data['products']=$products;
		//$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);
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
			'comp'	=> $comp,
			'order'	=> $trans,
			'products'	=> $products,
			//'address'	=> $this->data['address'],
			'banks'	=> $this->data['banks']
		);

		//$this->data['so'] = $this->model_sale_proforma->getso($trans['referensi']);
		$this->data['so'] = array();
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";print_r($this->data['salesorder']);exit;
		}

		if($this->user->getUsername()=="pawits"){
			echo "<pre>";print_r($this->data['so']);exit;
		}
		$this->data['printer']=$this->config->get('config_printer');
		$this->data['printerstatus']=$this->config->get('config_printer_status');
		$this->data['cancel']= $this->url->link('sale/proforma', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['suratjalan']= $this->url->link('sale/proforma/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=2'. $url, 'SSL');
		$this->data['invoice']= $this->url->link('sale/proforma/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=3'. $url, 'SSL');
		if($this->request->get['view'] == 1){
			$this->template = 'sale/proforma_info_new.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

		}
		if($this->request->get['view'] == 2){
			$this->template = 'sale/suratjalan.tpl';
		}
		if($this->request->get['view'] == 3){
			$this->template = 'sale/proforma.tpl';
		}

		$this->response->setOutput($this->render());
	}
	// end baru
	
	public function tampil(){
		$this->document->setTitle('Invoice');
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

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('sale/proforma', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/proforma', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/proforma');
		$this->load->model('sale/customer');
		$column=array('proforma_invoice.*','COALESCE(cetak,0) as totalcetak','customer.name','customer.npwp','customer.title','customer.telephone','customer.email','customer.alamat','customer.alamat as address');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'proforma_invoice.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);


		$data = array(
			'proforma_invoice.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_proforma->getPenjualanDetail($column,$join,$data,array());

		$products=$this->model_sale_proforma->getPenjualanProducts($trans['jenispenjualan'],array('sales_order_id'	=> $order_id));
		foreach($products as $p){

			if(!isset($cekso[$p['no_salesorder']])){
				if($i != 1){
					$salesorder .=",";
				}
				//$salesorder .=$p['no_salesorder'];
				$salesorder .=$p['id_salesorder'];
				$cekso[$p['no_salesorder']]=1;
			}
			$i++;
		}

		//referensi
		if($trans['jenispenjualan'] == 1){
			$this->load->model('sale/salesorder');
			$ref=$this->model_sale_salesorder->getPenjualan(array('id'=>$trans['referensi']));
			$trans['ref']=$ref['no_so'];
		}
		if($trans['jenispenjualan'] == 2){
			$this->load->model('sale/salesordermr');
			$ref=$this->model_sale_salesordermr->getPenjualan(array('id'=>$trans['referensi']));
			$trans['ref']=$ref['no_so'];
		}
		if($trans['jenispenjualan'] == 3){
			$this->load->model('sale/salesorderbahanbaku');
			$ref=$this->model_sale_salesorderbahanbaku->getPenjualan(array('id'=>$trans['referensi']));
			$trans['ref']=$ref['no_so'];
		}

		$this->load->model('keuangan/bank');
		$this->data['banks']=$this->model_keuangan_bank->getBanks(array(),array(),array('display_order' => 1,'hapus'	=> array('<',1)),array(),0,null);
		$this->data['listbank']=$this->model_keuangan_bank->listbank();
		//bank pembayaran

		$this->data['order']=$trans;
		$this->data['products']=$products;
		//$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);
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
			'comp'	=> $comp,
			'order'	=> $trans,
			'products'	=> $products,
			//'address'	=> $this->data['address'],
			'banks'	=> $this->data['banks']
		);

		$this->data['so'] = $this->model_sale_proforma->getso($trans['referensi']);

		if($this->user->getUsername()=="pawits"){
			echo "<pre>";
			print_r($this->data['fulldetail']);
			exit;
		}

		if($this->user->getUsername()=="pawits"){
			echo "<pre>";print_r($this->data['so']);exit;
		}
		$this->data['printer']=$this->config->get('config_printer');
		$this->data['printerstatus']=$this->config->get('config_printer_status');
		$this->data['cancel']= $this->url->link('sale/proforma', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['suratjalan']= $this->url->link('sale/proforma/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=2'. $url, 'SSL');
		$this->data['invoice']= $this->url->link('sale/proforma/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=3'. $url, 'SSL');
		if($this->request->get['view'] == 1){
			if($this->user->getUsername()=="pawits"){
			$this->template = 'sale/proforma_info_new.tpl';
			}else{
				$this->template = 'sale/proforma_info.tpl';
			}
			$this->children = array(
				'common/header',
				'common/footer'
			);

		}
		if($this->request->get['view'] == 2){
			$this->template = 'sale/suratjalan.tpl';
		}
		if($this->request->get['view'] == 3){
			$this->template = 'sale/proforma.tpl';
		}



		$this->response->setOutput($this->render());
	}
	
	public function tampil2(){
		$this->document->setTitle('Invoice');
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

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('sale/proforma', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/proforma', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/proforma');
		$this->load->model('sale/customer');
		$column=array('proforma_invoice.*','COALESCE(cetak,0) as totalcetak','customer.name','customer.npwp','customer.title','customer.telephone','customer.email','customer.alamat','customer.alamat as address');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'proforma_invoice.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);


		$data = array(
			'proforma_invoice.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_proforma->getPenjualanDetail($column,$join,$data,array());

		$products=$this->model_sale_proforma->getPenjualanProducts($trans['jenispenjualan'],array('sales_order_id'	=> $order_id));

		//referensi
		if($trans['jenispenjualan'] == 1){
			$this->load->model('sale/salesorder');
			$ref=$this->model_sale_salesorder->getPenjualan(array('id'=>$trans['referensi']));
			$trans['ref']=$ref['no_so'];
		}
		if($trans['jenispenjualan'] == 2){
			$this->load->model('sale/salesordermr');
			$ref=$this->model_sale_salesordermr->getPenjualan(array('id'=>$trans['referensi']));
			$trans['ref']=$ref['no_so'];
		}
		if($trans['jenispenjualan'] == 3){
			$this->load->model('sale/salesorderbahanbaku');
			$ref=$this->model_sale_salesorderbahanbaku->getPenjualan(array('id'=>$trans['referensi']));
			$trans['ref']=$ref['no_so'];
		}

		$this->load->model('keuangan/bank');
		$this->data['banks']=$this->model_keuangan_bank->getBanks(array(),array(),array('display_order' => 1,'hapus'	=> array('<',1)),array(),0,null);
		//bank pembayaran

		$this->data['order']=$trans;
		print_r($trans);exit;
		$this->data['products']=$products;
		//$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);
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
			'comp'	=> $comp,
			'order'	=> $trans,
			'products'	=> $products,
			//'address'	=> $this->data['address'],
			'banks'	=> $this->data['banks']
		);

		$this->data['printer']=$this->config->get('config_printer');
		$this->data['printerstatus']=$this->config->get('config_printer_status');

		//print_r($this->data['fulldetail']);

		//print_r($this->model_sale_customer->getAddress($trans['address_id']));
		$this->data['cancel']= $this->url->link('sale/proforma', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['suratjalan']= $this->url->link('sale/proforma/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=2'. $url, 'SSL');
		$this->data['invoice']= $this->url->link('sale/proforma/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=3'. $url, 'SSL');
		if($this->request->get['view'] == 1){
			$this->template = 'sale/proforma_info.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

		}
		if($this->request->get['view'] == 2){
			$this->template = 'sale/suratjalan.tpl';
		}
		if($this->request->get['view'] == 3){
			$this->template = 'sale/proforma.tpl';
		}



		$this->response->setOutput($this->render());
	}

	public function insert() {
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
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->load->model('sale/proforma');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			// OR $this->user->getUsername()=="anita"
			$user=$this->user->getUsername();
			$date=date('Y-m-d H:i:s');
			if($this->user->getUsername()=="pawit"){
				echo "<pre>";print_r($this->request->post);exit;
				//$s= $this->model_sale_proforma->addPenjualan($this->request->post);
				
			}
			if($user=='anita' or $user=='pawit' && $date >='2020-01-03 00:00:00'){
			//if($this->user->getUsername()=="pawit"){
				//echo "<pre>";print_r($this->request->post);exit;
				$s= $this->model_sale_proforma->newaddPenjualan($this->request->post);
				//echo "<pre>";print_r($s);exit;
			}else{
				$this->model_sale_proforma->addPenjualan($this->request->post);	
			}
			//$this->model_sale_proforma->addPenjualan($this->request->post);

			$this->session->data['success'] = 'Invoice berhasil ditambahkan.';

			$this->redirect($this->url->link('sale/proforma', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}


		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}


		$this->data['cancel']= $this->url->link('sale/proforma', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('sale/proforma/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->load->model('catalog/gudang');
        
        $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}

		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs(true);
		$user=$this->user->getUsername();
		$date=date('Y-m-d H:i:s');
		if($user=='anita' or $user=='pawit' && $date >='2020-01-03 00:00:00'){
		//if($this->user->getUsername()=="pawit" OR $this->user->getUsername()=="anitas"){
			$this->template = 'sale/proforma_form_new.tpl';
		}else{
			$this->template = 'sale/proforma_form.tpl';
		}
		
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

		public function detail(){
			$hasil = array();

			$this->load->model('pembelian/permintaanpembelian');
			if(isset($this->request->get['id'])){
				if(!empty($this->request->get['id'])){

				$this->load->model('sale/proforma');
				$this->load->model('sale/customer');
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
				$trans=$this->model_sale_proforma->getPenjualanDetail($column,$join,$data,array());

				$sales=$this->model_user_user->getUser($trans['sales']);
				$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
				$trans['namasales']=$sales['firstname'];
				$trans['namagudang']=$gudang['nama'];
				$products=$this->model_sale_proforma->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));

				//$this->data['order']=$trans;
				//$this->data['products']=$products;
				$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);

				$hasil=array(
					'order'	=> $trans,
					'products'	=> $products,
					'address'	=> $this->data['address']
				);
			}
		}
			$this->response->setOutput(json_encode($hasil));


		}

		public function logcetak(){
			$hasil=array();
			if(isset($this->request->get['id'])){
				if(!empty($this->request->get['id'])){
					$this->load->model('sale/proforma');
					$id=$this->request->get['id'];
					$trans=$this->model_sale_proforma->getPenjualanDetail(array('COALESCE(cetak,0) as totalcetak'),array(),array('id'=>$id),array());
					$totalcetak=$trans['totalcetak'];
					$this->model_sale_proforma->updatePenjualan(array('cetak'=>$totalcetak+1),array('id'=>$id));
					$hasil['status']=1;
				}
			}
			$this->response->setOutput(json_encode($hasil));
		}
}
?>
