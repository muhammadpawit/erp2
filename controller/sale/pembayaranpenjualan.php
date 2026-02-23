<?php
class ControllerSalePembayaranpenjualan extends Controller {
	private $error=array();
	public function exportexcel(){
		$this->document->setTitle('Pembayaran Penjualan');

		if (isset($this->request->get['filter_no_po'])) {
			$filter_no_po = $this->request->get['filter_no_po'];
		} else {
			$filter_no_po = '';
		}

		if (isset($this->request->get['filter_tanggal_awal'])) {
			$filter_tanggal_awal = $this->request->get['filter_tanggal_awal'];
		} else {
			$filter_tanggal_awal = '';
		}

		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$filter_tanggal_akhir = $this->request->get['filter_tanggal_akhir'];
		} else {
			$filter_tanggal_akhir = '';
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('keuangan/penerimaandana/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['exportexcel'] = $this->url->link('keuangan/penerimaandana/exportexcel', 'token=' . $this->session->data['token'].$url, 'SSL');

		/*$this->load->model('report/product');
        $this->load->model('catalog/product');
		*/

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('sale/penjualan');
		$this->load->model('sale/pembayaranpenjualan');
		$this->load->model('catalog/title');

		$this->data['permintaans'] = array();
		$column=array('pembayaran_penjualan.*','invoice.no_faktur as no_invoice','customer.name','customer.title');
		$join=array();
		$join[]=array(
			'tablename'	=> 'invoice',
			'firsttable'	=> 'pembayaran_penjualan.penjualan_id',
			'secondtable'	=> 'invoice.id'
		);
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'invoice.customer_id',
			'secondtable'	=> 'customer.customer_id'
		);
		$data = array(
			'penjualan_id'      =>$filter_no_po,
			'pembayaran_penjualan.status'	=> array('<>',3),
			'pembayaran_penjualan.date_added' =>!empty($filter_tanggal_awal)?array(" >= ",$filter_tanggal_awal,'<=',$filter_tanggal_akhir):array('>','1901-01-01'),
			'invoice.customer_id'	=> !empty($filter_customer_id)?$filter_customer_id:array('>=',1),
		);
		$limit=0;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'date_added'	=> 'DESC',

		);

		//$product_total = $this->model_sale_pembayaranpenjualan->totalPermintaans($data);
		$product_total=count($this->model_sale_pembayaranpenjualan->getPermintaanPembelians($column,$join,$data,$order,0,null));
		$data = array(
			'penjualan_id'      =>$filter_no_po,
			'pembayaran_penjualan.status'	=> array('<>',3),
			'pembayaran_penjualan.date_added' =>!empty($filter_tanggal_awal)?array(" >= ",$filter_tanggal_awal,'<=',$filter_tanggal_akhir):array('>','1901-01-01'),
			'customer.customer_id'	=> !empty($filter_customer_id)?$filter_customer_id:array('>=',1),
		);

		$results = $this->model_sale_pembayaranpenjualan->getPermintaanPembelians($column,$join,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();
			$this->data['permintaans'][] = array(
				'no_po'	=> $result['no_invoice'],
				'name'	=> $this->model_catalog_title->getTitle($result['title']).' '.$result['name'],
				'jumlah'	=> $this->currency->format($result['jumlah']),
				'tanggal'	=> date('d/m/y',strtotime($result['date_added'])),
				'status'	=> $result['status'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Pembayaran Penjualan';

		$this->data['token'] = $this->session->data['token'];
		$url = '';

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
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
		$pagination->url = $this->url->link('sale/pembayaranpenjualan', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_no_po'] = $filter_no_po;
		$this->data['filter_tanggal_awal'] = $filter_tanggal_awal;
		$this->data['filter_tanggal_akhir'] = $filter_tanggal_akhir;

		$this->template = 'sale/pembayaranpenjualan_excel.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function index() {
		$this->document->setTitle('Pembayaran Penjualan');

		if (isset($this->request->get['filter_no_po'])) {
			$filter_no_po = $this->request->get['filter_no_po'];
		} else {
			$filter_no_po = '';
		}

		if (isset($this->request->get['filter_tanggal_awal'])) {
			$filter_tanggal_awal = $this->request->get['filter_tanggal_awal'];
		} else {
			$filter_tanggal_awal = '';
		}

		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$filter_tanggal_akhir = $this->request->get['filter_tanggal_akhir'];
		} else {
			$filter_tanggal_akhir = '';
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('keuangan/penerimaandana/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['exportexcel'] = $this->url->link('sale/pembayaranpenjualan/exportexcel', 'token=' . $this->session->data['token'].$url, 'SSL');

		/*$this->load->model('report/product');
        $this->load->model('catalog/product');
		*/

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('sale/penjualan');
		$this->load->model('sale/pembayaranpenjualan');
		$this->load->model('catalog/title');

		$this->data['permintaans'] = array();
		$column=array('pembayaran_penjualan.*','invoice.no_faktur as no_invoice','customer.name','customer.title');
		$join=array();
		$join[]=array(
			'tablename'	=> 'invoice',
			'firsttable'	=> 'pembayaran_penjualan.penjualan_id',
			'secondtable'	=> 'invoice.id'
		);
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'invoice.customer_id',
			'secondtable'	=> 'customer.customer_id'
		);
		$data = array(
			'penjualan_id'      =>$filter_no_po,
			'pembayaran_penjualan.status'	=> array('<>',3),
			'pembayaran_penjualan.date_added' =>!empty($filter_tanggal_awal)?array(" >= ",$filter_tanggal_awal,'<=',$filter_tanggal_akhir):array('>','1901-01-01'),
			'invoice.customer_id'	=> !empty($filter_customer_id)?$filter_customer_id:array('>=',1),
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'date_added'	=> 'DESC',

		);

		//$product_total = $this->model_sale_pembayaranpenjualan->totalPermintaans($data);
		$product_total=count($this->model_sale_pembayaranpenjualan->getPermintaanPembelians($column,$join,$data,$order,0,null));
		$data = array(
			'penjualan_id'      =>$filter_no_po,
			'pembayaran_penjualan.status'	=> array('<>',3),
			'pembayaran_penjualan.date_added' =>!empty($filter_tanggal_awal)?array(" >= ",$filter_tanggal_awal,'<=',$filter_tanggal_akhir):array('>','1901-01-01'),
			'customer.customer_id'	=> !empty($filter_customer_id)?$filter_customer_id:array('>=',1),
		);

		$results = $this->model_sale_pembayaranpenjualan->getPermintaanPembelians($column,$join,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();
			$this->data['permintaans'][] = array(
				'no_po'	=> $result['no_invoice'],
				'name'	=> $this->model_catalog_title->getTitle($result['title']).' '.$result['name'],
				'jumlah'	=> $this->currency->format($result['jumlah']),
				'tanggal'	=> date('d/m/y',strtotime($result['date_added'])),
				'status'	=> $result['status'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Pembayaran Penjualan';

		$this->data['token'] = $this->session->data['token'];
		$url = '';

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
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
		$pagination->url = $this->url->link('sale/pembayaranpenjualan', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_no_po'] = $filter_no_po;
		$this->data['filter_tanggal_awal'] = $filter_tanggal_awal;
		$this->data['filter_tanggal_akhir'] = $filter_tanggal_akhir;

		$this->template = 'sale/pembayaranpenjualan.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Pembayaran Penjualan');

		$this->load->model('sale/pembayaranpenjualan');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_po=$this->model_sale_pembayaranpenjualan->addPembelian($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Pembayaran Penjualan berhasil disimpan';

			$url = '';

			if (isset($this->request->get['filter_no_po'])) {
				$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/pembayaranpenjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url = '';

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
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


		if (isset($this->request->post['jumlah'])) {
			$this->data['jumlah'] = $this->request->post['jumlah'];
		}  else {
			$this->data['jumlah'] = '';
		}


		$this->data['cancel']= $this->url->link('sale/pembayaranpenjualan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('sale/pembayaranpenjualan/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->template = 'sale/pembayaranpenjualan_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {

		if (empty($this->request->post['no_po'])) {
		  		$this->error['no_po'] = 'Nomor Invoice tidak boleh kosong';
			}
		if(!is_numeric($this->request->post['jumlah']) ){
			$this->error['jumlah'] = 'Jumlah Pembayaran Harus Berupa Angka';
		}else{
			//cek
			$this->load->model('sale/pembayaranpenjualan');
			//$cek=$this->load->model_sale_pembayaranpenjualan->getPermintaanPembelian(array('COALESCE(SUM(jumlah),0) as total'),array(),array('hapus'	=> array('<',1),'status' =>1,'penjualan_id'=>$this->request->post['no_po']));
			/*if(!empty($cek)){
				$this->error['jumlah'] = 'Duplikasi data pembayaran DP';
			}*/

			$this->load->model('sale/invoice');
			$kred=$this->model_sale_invoice->getPenjualan(array('id'=>$this->request->post['no_po']));
			/*if(!empty($kred)){
				$pajak=$this->request->post['jumlah']*0.1;
				if(($cek['total']+$this->request->pos['jumlah']+$pajak) > $kred['total']){
					$this->error['jumlah'] = 'Nilai pembayaran melebihi nilai total transaksi.';
				}
			}else{
				$this->error['jumlah'] = "Penjualan tidak ditemukan";
			}*/
			if(empty($kred)){
				$this->error['jumlah'] = 'Invoice tidak ditemukan.';
			}else{
				if($kred['totaltagihan'] > $this->request->post['jumlah']){
					$this->error['jumlah'] = 'Nilai pembayaran kurang dari total transaksi.';
				}
			}
		}
		//print_r($cek);
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}


	public function cetak(){
		$this->document->setTitle('Pembayaran Penjualan');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('sale/pembayaranpenjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/pembayaranpenjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/pembayaranpenjualan');

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

		$trans=$this->model_sale_pembayaranpenjualan->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_sale_pembayaranpenjualan->getPermintaanPembelianProduct(array('pembelian_id'	=> $id));
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
		$this->load->model('sale/pembayaranpenjualan');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_sale_pembayaranpenjualan->updatePermintaan(array('status' => 3),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Pembayaran Penjualan berhasil dibatalkan.';
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

			$this->redirect($this->url->link('sale/pembayaranpenjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
