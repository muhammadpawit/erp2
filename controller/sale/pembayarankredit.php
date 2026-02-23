<?php
class ControllerSalePembayarankredit extends Controller {
	private $error=array();
	// baru 17 Januari 2020
	public function cetakexcel() {
		$this->document->setTitle('Pembayaran Penjualan Kredit');
		if (isset($this->request->get['tgl_input_awal'])) {
			$tgl_input_awal = $this->request->get['tgl_input_awal'];
		} else {
			$tgl_input_awal =null;
		}

		if (isset($this->request->get['tgl_input_akhir'])) {
			$tgl_input_akhir = $this->request->get['tgl_input_akhir'];
		} else {
			$tgl_input_akhir =null;
		}
		if (isset($this->request->get['filter_no_po'])) {
			$filter_no_po = $this->request->get['filter_no_po'];
		} else {
			$filter_no_po = '';
		}

		if (isset($this->request->get['filter_no_invoice'])) {
			$filter_no_invoice = $this->request->get['filter_no_invoice'];
		} else {
			$filter_no_invoice =null;
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = '';
		}

		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$filter_tanggal_akhir = $this->request->get['filter_tanggal_akhir'];
		} else {
			$filter_tanggal_akhir ='';
		}

		if (isset($this->request->get['filter_tanggal_awal'])) {
			$filter_tanggal_awal = $this->request->get['filter_tanggal_awal'];
		} else {
			$filter_tanggal_awal ='';
		}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['tgl_input_awal'])) {
			$url .= '&tgl_input_awal=' . $this->request->get['tgl_input_awal'];
		}
		if (isset($this->request->get['tgl_input_akhir'])) {
			$url .= '&tgl_input_akhir=' . $this->request->get['tgl_input_akhir'];
		}
		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_no_invoice'])) {
			$url .= '&filter_no_invoice=' . $this->request->get['filter_no_invoice'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('sale/pembayarankredit/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['cetakexcel'] = $this->url->link('sale/pembayarankredit/cetakexcel', 'token=' . $this->session->data['token'].$url, 'SSL');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('sale/penjualan');
		$this->load->model('sale/pembayarankredit');
		$this->load->model('sale/invoice');

		$this->data['permintaans'] = array();
		$column=array('pembayaran_kredit.*','customer.name as name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'pembayaran_kredit.customer_id',
			'secondtable'	=> 'customer.customer_id'
		);
		$leftjoin=array();
		$leftjoin[]= array(
			'tablename' => 'pembayaran_kredit_invoice',
			'firsttable' => 'pembayaran_kredit.id',
			'secondtable' => 'pembayaran_kredit_invoice.pembayaran_id'
		);
		$data = array(
			'pembayaran_kredit.customer_id'	=> !empty($filter_customer_id)?$filter_customer_id:array('>=',1),
			'pembayaran_kredit.status'	=> array('<>',3),
			'date(pembayaran_kredit.date_added)' =>!empty($filter_tanggal_awal)?array(" >= ",$filter_tanggal_awal,'<=',$filter_tanggal_akhir):array('>','1901-01-01'),
			'date(pembayaran_kredit.date_modified)' =>!empty($tgl_input_awal)?array(" >= ",$tgl_input_awal,'<=',$tgl_input_akhir):array('>','1901-01-01'),
		);
		$limit=0;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			//'id'	=> 'DESC',
			'date_modified'	=> 'DESC',
		);

		if($filter_no_invoice==null){
			$product_total = $this->model_sale_pembayarankredit->totalPermintaans($data);
			$results = $this->model_sale_pembayarankredit->getPermintaanPembelians($column,$join,$data,$order,$limit,$offset);
		}else{ 
			$product_total =count($this->model_sale_pembayarankredit->getpembayarankredit($filter_no_invoice));
			$results = $this->model_sale_pembayarankredit->getpembayarankredit($filter_no_invoice);
		}

		foreach ($results as $result) {
			$inv=$this->model_sale_pembayarankredit->getPembayaranInvoice(array('pembayaran_id' =>$result['id']));
			$invoice=array();
			foreach($inv as $i){
				$iv=$this->model_sale_invoice->getPenjualan(array('id'=>$i['invoice_id']));
				$invoice[]=array(
					'invoice'=>$iv['no_faktur'],
					'totalbayar'=> $this->currency->format($i['total'])
				);
			}
			$action = array();
			if($result['status'] == 1){
			$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('sale/pembayarankredit/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}
			$this->data['permintaans'][] = array(
				'no_dokumen'	=> $result['no_dokumen'],
				'name'	=> $result['name'],
				'total'	=> $this->currency->format($result['total']),
				'tanggal'	=> date('d/m/Y',strtotime($result['date_added'])),
				'tanggalinput'	=> date('d/m/Y H:i:s',strtotime($result['date_modified'])),
				'status'	=> $result['status'],
				'invoice'	=> $invoice,
				'actions'	=> $action
			);
		}
		$this->template = 'sale/pembayarankredit_excel.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	// end baru
	public function index() {
		$this->document->setTitle('Pembayaran Penjualan Kredit');

		if (isset($this->request->get['filter_no_po'])) {
			$filter_no_po = $this->request->get['filter_no_po'];
		} else {
			$filter_no_po = '';
		}

		if (isset($this->request->get['filter_no_invoice'])) {
			$filter_no_invoice = $this->request->get['filter_no_invoice'];
		} else {
			$filter_no_invoice =null;
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = '';
		}

		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$filter_tanggal_akhir = $this->request->get['filter_tanggal_akhir'];
		} else {
			$filter_tanggal_akhir ='';
		}

		if (isset($this->request->get['filter_tanggal_awal'])) {
			$filter_tanggal_awal = $this->request->get['filter_tanggal_awal'];
		} else {
			$filter_tanggal_awal ='';
		}

		if (isset($this->request->get['tgl_input_awal'])) {
			$tgl_input_awal = $this->request->get['tgl_input_awal'];
		} else {
			$tgl_input_awal =null;
		}

		if (isset($this->request->get['tgl_input_akhir'])) {
			$tgl_input_akhir = $this->request->get['tgl_input_akhir'];
		} else {
			$tgl_input_akhir =null;
		}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['tgl_input_awal'])) {
			$url .= '&tgl_input_awal=' . $this->request->get['tgl_input_awal'];
		}
		if (isset($this->request->get['tgl_input_akhir'])) {
			$url .= '&tgl_input_akhir=' . $this->request->get['tgl_input_akhir'];
		}
		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_no_invoice'])) {
			$url .= '&filter_no_invoice=' . $this->request->get['filter_no_invoice'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('sale/pembayarankredit/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['cetakexcel'] = $this->url->link('sale/pembayarankredit/cetakexcel', 'token=' . $this->session->data['token'].$url, 'SSL');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('sale/penjualan');
		$this->load->model('sale/pembayarankredit');
		$this->load->model('sale/invoice');

		$this->data['permintaans'] = array();
		$column=array('pembayaran_kredit.*','customer.name as name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'pembayaran_kredit.customer_id',
			'secondtable'	=> 'customer.customer_id'
		);
		$leftjoin=array();
		$leftjoin[]= array(
			'tablename' => 'pembayaran_kredit_invoice',
			'firsttable' => 'pembayaran_kredit.id',
			'secondtable' => 'pembayaran_kredit_invoice.pembayaran_id'
		);
		$data = array(
			'pembayaran_kredit.customer_id'	=> !empty($filter_customer_id)?$filter_customer_id:array('>=',1),
			'pembayaran_kredit.status'	=> array('<>',3),
			'date(pembayaran_kredit.date_added)' =>!empty($filter_tanggal_awal)?array(" >= ",$filter_tanggal_awal,'<=',$filter_tanggal_akhir):array('>','1901-01-01'),
			'date(pembayaran_kredit.date_modified)' =>!empty($tgl_input_awal)?array(" >= ",$tgl_input_awal,'<=',$tgl_input_akhir):array('>','1901-01-01'),
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			//'id'	=> 'DESC',
			'date_modified'	=> 'DESC',
		);

		if($filter_no_invoice==null){
			$product_total = $this->model_sale_pembayarankredit->totalPermintaans($data);
			$results = $this->model_sale_pembayarankredit->getPermintaanPembelians($column,$join,$data,$order,$limit,$offset);
		}else{ 
			$product_total =count($this->model_sale_pembayarankredit->getpembayarankredit($filter_no_invoice));
			$results = $this->model_sale_pembayarankredit->getpembayarankredit($filter_no_invoice);
		}

		foreach ($results as $result) {
			$inv=$this->model_sale_pembayarankredit->getPembayaranInvoice(array('pembayaran_id' =>$result['id']));
			$invoice=array();
			foreach($inv as $i){
				$iv=$this->model_sale_invoice->getPenjualan(array('id'=>$i['invoice_id']));
				$invoice[]=array(
					'invoice'=>$iv['no_faktur'],
					'totalbayar'=> $this->currency->format($i['total'])
				);
			}
			$action = array();
			if($result['status'] == 1){
				$action[] = array(
						'text' => 'Batalkan',
						'href' => $this->url->link('sale/pembayarankredit/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
					);
				if(!empty($result['no_dokumen'])){
					$action[] = array(
						'text' => 'Lihat Jurnal',
						'href' => $this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'] . '&filter_nodokumen=' . $result['no_dokumen'].$url, 'SSL')
					);
				}
			}
			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('sale/pembayarankredit/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			$start_date = new DateTime($result['date_modified']);
			$end_date = new DateTime($result['date_added']);
			$interval = $start_date->diff($end_date);
			$this->data['permintaans'][] = array(
				'name'	=> $result['name'],
				'total'	=> $this->currency->format($result['total']),
				'tanggal'	=> date('d/m/Y',strtotime($result['date_added'])),
				'tanggalinput'	=> date('d/m/Y H:i:s',strtotime($result['date_modified'])),
				'lamaalokasi'=>$interval->days,
				'status'	=> $result['status'],
				'invoice'	=> $invoice,
				'no_dokumen'	=> $result['no_dokumen'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Pembayaran Penjualan Kredit';

		$this->data['token'] = $this->session->data['token'];
		$url = '';

		if (isset($this->request->get['tgl_input_awal'])) {
			$url .= '&tgl_input_awal=' . $this->request->get['tgl_input_awal'];
		}
		if (isset($this->request->get['tgl_input_akhir'])) {
			$url .= '&tgl_input_akhir=' . $this->request->get['tgl_input_akhir'];
		}

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_no_invoice'])) {
			$url .= '&filter_no_invoice=' . $this->request->get['filter_no_invoice'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/pembayarankredit', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_po'] = $filter_no_po;
		$this->data['filter_tanggal_awal'] = $filter_tanggal_awal;
		$this->data['filter_tanggal_akhir'] = $filter_tanggal_akhir;
		$this->data['tgl_input_awal'] = $tgl_input_awal;
		$this->data['tgl_input_akhir'] = $tgl_input_akhir;

		$this->template = 'sale/pembayarankredit.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function tampil(){
		$url = '';
		if (isset($this->request->get['tgl_input_awal'])) {
			$url .= '&tgl_input_awal=' . $this->request->get['tgl_input_awal'];
		}
		if (isset($this->request->get['tgl_input_akhir'])) {
			$url .= '&tgl_input_akhir=' . $this->request->get['tgl_input_akhir'];
		}
		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_no_invoice'])) {
			$url .= '&filter_no_invoice=' . $this->request->get['filter_no_invoice'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$order_id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('sale/pembayarankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/pembayarankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->load->model('sale/penjualan');
		$this->load->model('sale/pembayarankredit');
		$this->load->model('sale/invoice');

		$column=array('pembayaran_kredit.*','customer.name as name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'pembayaran_kredit.customer_id',
			'secondtable'	=> 'customer.customer_id'
		);
		/*$join[]= array(
			'tablename' => 'pembayaran_kredit_invoice',
			'firsttable' => 'pembayaran_kredit.id',
			'secondtable' => 'pembayaran_kredit_invoice.pembayaran_id'
		);*/
		
		$trans=$this->model_sale_pembayarankredit->getPermintaanPembelian($column,$join,array('id'=>$order_id));
		
		$inv=$this->model_sale_pembayarankredit->getPembayaranInvoice(array('pembayaran_id'=>$order_id));
		$invoice=array();
		//print_r($inv);
		foreach($inv as $i){
			$iv=$this->model_sale_invoice->getPenjualan(array('id'=>$i['invoice_id']));
			$invoice[]=array(
				'invoice'=>$iv['no_faktur'],
				'totalbayar'=> $this->currency->format($i['total'])
			);
		}
		$this->data['order']=$trans;
		$this->data['products']=$invoice;
		/*$invoice[]=array(
			'invoice'=>$iv['no_faktur'],
			'totalbayar'=> $this->currency->format($i['total'])
		);*/

		$this->data['cancel']= $this->url->link('sale/pembayarankredit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['token'] = $this->session->data['token'];
		//if($this->request->get['view'] == 1){
			$this->template = 'sale/pembayaran_info.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
			$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Pembayaran Penjualan Kredit');

		$this->load->model('sale/pembayarankredit');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if($this->user->getUsername()=="pawit"){
				echo "<pre>";print_r($this->request->post);exit;
			}
			$this->model_sale_pembayarankredit->addPembelian($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Pembayaran Penjualan Kredit berhasil disimpan';

			$url = '';

			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/pembayarankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
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

		$this->data['token'] = $this->session->data['token'];


		if (isset($this->request->post['total'])) {
			$this->data['total'] = $this->request->post['total'];
		}  else {
			$this->data['total'] = '';
		}


		$this->data['cancel']= $this->url->link('sale/pembayarankredit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('sale/pembayarankredit/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = array();
		}
        
        $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}

		$this->template = 'sale/pembayarankredit_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {


		//print_r($cek);
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}


	public function cetak(){
		$this->document->setTitle('Pembayaran Penjualan Kredit');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('sale/pembayarankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/pembayarankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/pembayarankredit');

		$column=array('penjualan.*','permintaan_pembelian.no_surat','vendorlokal.name','permintaan_pembelian.jenis_barang');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'penjualan.surat_id'
		);
		$join[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'penjualan.vendor_id'
		);

		$data = array(
			'penjualan.id'	=> $id,
      'permintaan_pembelian.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_sale_pembayarankredit->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_sale_pembayarankredit->getPermintaanPembelianProduct(array('pembelian_id'	=> $id));
		//print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;

		$this->template = 'sale/pebeliankredit_cetak.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function batalkan(){
		$this->load->model('sale/pembayarankredit');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_sale_pembayarankredit->updatePermintaan(array('status' => 3),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Pembayaran Penjualan Kredit berhasil dibatalkan.';
			}
		}
			$url = '';

			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
			}

			if (isset($this->request->get['filter_jenis_pembelian'])) {
				$url .= '&filter_jenis_pembelian=' . $this->request->get['filter_jenis_pembelian'];
			}

			if (isset($this->request->get['filter_jenis_barang'])) {
				$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
			}

			if (isset($this->request->get['filter_divisi'])) {
				$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/pembayarankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	public function autocomplete(){
		$rests = array();

		$this->load->model('sale/permintaanpembelian');

			if (isset($this->request->get['q'])) {
				$filter_no_surat = $this->request->get['q'];
			} else {
				$filter_no_surat = '';
			}
			if (isset($this->request->get['j'])) {
				$jenis_pembelian = $this->request->get['j'];
			} else {
				$jenis_pembelian = '';
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_surat'         => array('LIKE',$filter_no_surat),
				'status'	=> 2,
				'jenis_pembelian'	=> $jenis_pembelian
			);
			$start=0;
			$limit=0;
			$column=array('id','no_surat');
			$join=array();

			$results = $this->model_pembelian_permintaanpembelian->getPermintaanPembelians($column,$join,$data,array(),$limit,$start);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_surat']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}


}
?>
