<?php
class ControllerLaporanAgingreport extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Aging Report');

		$this->load->model('sale/invoice');

		$this->getList();
	}




	private function getList() {
		if (isset($this->request->get['filter_type'])) {
			$filter_type = $this->request->get['filter_type'];
		} else {
			$filter_type = null;
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id =null;
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}
		if (isset($this->request->get['filter_type'])) {
			$filter_type = $this->request->get['filter_type'];
		} else {
			$filter_type = 1;
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = date('Y-m-d');
		}
		/*if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-01');
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-t');
		}*/

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'customer.name';
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

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

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

		$this->load->model('report/agingreport');
		$data = array(
			'filter_gudang'	=>$arrsql,
			'filter_customer'	=> $filter_customer_id,
	   		'filter_tanggal'	=> $filter_tanggal,
			'filter_type'	=> $filter_type,
			'sort'	=>$sort,
			'order'	=> $order

	  );
	  if($this->user->getUsername()=="pawitc"){
		echo "<pre>";print_r($data);exit;
	  }

		$results=$this->model_report_agingreport->agingreportcustomer($data);
		$totaltagihan=0;
		$totaldeposit=0;
		$total=0;

		$jumlah15=0;
		$jumlah30=0;
		$jumlah60=0;
		$jumlah90=0;
		$jumlah120=0;
		$jumlah121=0;
	  	  
		  $this->load->model('user/user');
		foreach($results as $result){
			$sales=$this->model_sale_customer->getVendor(array('customer_id'=> $result['customer_id']));
			$namasales=$this->model_user_user->getUser($sales['sales']);
			$this->data['penjualans'][] = array(
		    'customer_id' => $result['customer_id'],
		    'name'	=> $result['name'],
		    'deposit'	=> $this->currency->format($result['totaldeposit']),
		    'plaindeposit'	=> $result['totaldeposit'],
			'totaltagihan'	=> $this->currency->format($result['totaltagihan']),
		    'sisabayar'	=> $this->currency->format($result['sisabayar']),
			'sisabayar15'	=> $this->currency->format($result['jumlah15']),
			'sisabayar30'	=> $this->currency->format($result['jumlah30']),
		    'sisabayar60'	=> $this->currency->format($result['jumlah60']),
		    'sisabayar90'	=> $this->currency->format($result['jumlah90']),
		    'sisabayar120'	=> $this->currency->format($result['jumlah120']),
		    'sisabayar121'	=> $this->currency->format($result['jumlah121']),
			'invoice'	=> $result['inv'],
			'jadwal'=>$this->model_report_agingreport->getjadwalharicustomer($result['customer_id']),
			'jadwal_penagihan'	=>$this->model_report_agingreport->getjadwalpenagihancustomer($result['customer_id']),
			'sales'=>$namasales['firstname'],
			'followup'=>$this->model_report_agingreport->getfollowup($result['customer_id']),
			//'sudahbayar'	=> $this->currency->format(($result['jumlah15']+$result['jumlah30']+$result['jumlah60']+$result['jumlah90']+$result['jumlah120']+$result['jumlah121'])),
			'historybayar'=>$this->currency->format($result['totaltagihan']-$result['totalbayar']),
		  );

			$totaltagihan += $result['totaltagihan'];
			$totaldeposit += $result['totaldeposit'];
			$total += $result['sisabayar'];

			$jumlah15 += $result['jumlah15'];
			$jumlah30 += $result['jumlah30'];
			$jumlah60 += $result['jumlah60'];
			$jumlah90 += $result['jumlah90'];
			$jumlah120 += $result['jumlah120'];
			$jumlah121 += $result['jumlah121'];
		}

		$this->data['jumlah']=array(
			'totaltagihan'	=> $totaltagihan,
			'totaldeposit'	=> $totaldeposit,
			'total'	=> $total,
		);

		$this->data['jumlah15']=$this->currency->format($jumlah15);
		$this->data['jumlah30']=$this->currency->format($jumlah30);
		$this->data['jumlah60']=$this->currency->format($jumlah60);
		$this->data['jumlah90']=$this->currency->format($jumlah90);
		$this->data['jumlah120']=$this->currency->format($jumlah120);
		$this->data['jumlah121']=$this->currency->format($jumlah121);

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

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		/*if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}*/
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}


		$this->data['sort_date_added'] = $this->url->link('laporan/agingreport', 'token=' . $this->session->data['token'] . '&sort=invoice.date_added' . $url, 'SSL');
		$this->data['sort_jatuh_tempo'] = $this->url->link('laporan/agingreport', 'token=' . $this->session->data['token'] . '&sort=invoice.jatuhtempo' . $url, 'SSL');
		$this->data['sort_customer'] = $this->url->link('laporan/agingreport', 'token=' . $this->session->data['token'] . '&sort=customer.name' . $url, 'SSL');
		$this->data['sort_tagihan'] = $this->url->link('laporan/agingreport', 'token=' . $this->session->data['token'] . '&sort=invoice.totaltagihan' . $url, 'SSL');
		$this->data['sort_bayar'] = $this->url->link('laporan/agingreport', 'token=' . $this->session->data['token'] . '&sort=invoice.totalbayar' . $url, 'SSL');
		$this->data['sort_invoice'] = $this->url->link('laporan/agingreport', 'token=' . $this->session->data['token'] . '&sort=invoice.id' . $url, 'SSL');



		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_type']	= $filter_type;
		$this->data['filter_tanggal']	= $filter_tanggal;
		$this->data['filter_date_end']	= $filter_date_end;
		$this->data['filter_gudang_id']	= $filter_gudang_id;
		$this->data['token'] = $this->session->data['token'];
		if($filter_gudang_id==null){
			$this->data['gudang']='Semua';
		}else{
			$gudang=$gudang=$this->model_catalog_gudang->getGudang($filter_gudang_id);
			$this->data['gudang']=$gudang['nama'];
		}
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		if(!isset($this->request->get['print'])){
			$this->template = 'laporan/agingreport_list.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
		}
		/*else if(!isset($this->request->get['excel'])){
			$this->template = 'laporan/agingreport_cetakexcel.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
		}*/else{
			$this->template = 'laporan/agingreport_cetakexcel.tpl';
		}
		

		$this->response->setOutput($this->render());
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('sale/invoice');

			if (isset($this->request->get['q'])) {
				$filter_type = $this->request->get['q'];
			} else {
				$filter_type = '';
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
				'no_faktur'         => array('LIKE',$filter_type),
				'metode_pembayaran'	=> $p != null?($p == 4?array('<>',3):$p):array('>=',1),
				'customer_id'	=> $customer_id != null ?$customer_id:array('>=',1),

			);
			$offset=0;
			$limit=10;

			$results = $this->model_sale_invoice->getPenjualans(array(),array(),$data,array(),10,0);
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

		$this->load->model('sale/invoice');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$column=array();
				$id=$this->request->get['id'];
				$data = array(
					'id'      =>$id
				);

				$hasil=$this->model_sale_invoice->getPenjualan($data);
			//	$hasil['pdeposit']=$this->currency->format($hasil['deposit']);


			}
		}
		$this->response->setOutput(json_encode($hasil));


	}

	public function tampil(){
		$this->document->setTitle('Invoice');
		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
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
				$this->redirect($this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/invoice');
		$this->load->model('sale/customer');
		$column=array('invoice.*','customer.name','customer.npwp','customer.title','customer.telephone','customer.email','customer.alamat','customer.alamat as address');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'invoice.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);


		$data = array(
			'invoice.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_invoice->getPenjualanDetail($column,$join,$data,array());

		$products=$this->model_sale_invoice->getPenjualanProducts($trans['jenispenjualan'],array('sales_order_id'	=> $order_id));

		//referensi
		if($trans['jenisinvoice'] == 3){
			if($trans['jenispenjualan'] == 1){
				$this->load->model('sale/penjualan');
				$ref=$this->model_sale_penjualan->getPenjualan(array('id'=>$trans['referensi']));
				$trans['ref']=$ref['no_sj'];
			}
			if($trans['jenispenjualan'] == 2){
				$this->load->model('sale/penjualanmr');
				$ref=$this->model_sale_penjualanmr->getPenjualan(array('id'=>$trans['referensi']));
				$trans['ref']=$ref['no_sj'];
			}
			if($trans['jenispenjualan'] == 3){
				$this->load->model('sale/penjualanbahanbaku');
				$ref=$this->model_sale_penjualanbahanbaku->getPenjualan(array('id'=>$trans['referensi']));
				$trans['ref']=$ref['no_sj'];
			}
		}else{
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

		$this->data['printer']=$this->config->get('config_printer');
		$this->data['printerstatus']=$this->config->get('config_printer_status');

		//print_r($this->data['fulldetail']);

		//print_r($this->model_sale_customer->getAddress($trans['address_id']));
		$this->data['cancel']= $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['suratjalan']= $this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=2'. $url, 'SSL');
		$this->data['invoice']= $this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=3'. $url, 'SSL');
		if($this->request->get['view'] == 1){
			$this->template = 'sale/invoice_info.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

		}
		if($this->request->get['view'] == 2){
			$this->template = 'sale/suratjalan.tpl';
		}
		if($this->request->get['view'] == 3){
			$this->template = 'sale/invoice.tpl';
		}



		$this->response->setOutput($this->render());
	}



		public function detail(){
			$hasil = array();

			$this->load->model('pembelian/permintaanpembelian');
			if(isset($this->request->get['id'])){
				if(!empty($this->request->get['id'])){

				$this->load->model('sale/invoice');
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
				$trans=$this->model_sale_invoice->getPenjualanDetail($column,$join,$data,array());

				$sales=$this->model_user_user->getUser($trans['sales']);
				$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
				$trans['namasales']=$sales['firstname'];
				$trans['namagudang']=$gudang['nama'];
				$products=$this->model_sale_invoice->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));

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
}
?>
