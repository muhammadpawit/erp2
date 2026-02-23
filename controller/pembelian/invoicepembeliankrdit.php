<?php
class ControllerPembelianInvoicepembeliankredit extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Invoice Pembelian Lokal');

		if (isset($this->request->get['filter_no_faktur'])) {
			$filter_no_faktur = $this->request->get['filter_no_faktur'];
		} else {
			$filter_no_faktur = '';
		}


		if (isset($this->request->get['filter_jenis_barang'])) {
			$filter_jenis_barang = $this->request->get['filter_jenis_barang'];
		} else {
			$filter_jenis_barang = null;
		}

		if (isset($this->request->get['filter_vendor'])) {
			$filter_vendor = $this->request->get['filter_vendor'];
		} else {
			$filter_vendor = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$filter_status_penerimaan = $this->request->get['filter_status_penerimaan'];
		} else {
			$filter_status_penerimaan = null;
		}



		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('pembelian/invoicepembeliankredit/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		/*$this->load->model('report/product');
        $this->load->model('catalog/product');
		*/

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->session->data['warning'])) {
			$this->data['warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else {
			$this->data['warning'] = '';
		}

		/*$this->load->model('catalog/product');
		$this->load->model('gudang/product');
		$this->load->model('pembelian/permintaanpembelian');
		$this->load->model('catalog/gudang');
		*/

		$this->load->model('catalog/vendorimport');
		$this->load->model('pembelian/invoicepembeliankredit');

		$this->data['permintaans'] = array();
		$column=array('invoice_pembelian.*','vendorimport.name','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'invoice_pembelian.vendor_id'
		);

		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'secondtable'	=>'gudang.gudang_id',
			'firsttable'	=> 'invoice_pembelian.gudang_id'
		);

		$data = array(
			//'no_po'      =>array('LIKE',$filter_no_po),
			'invoice_pembelian.id'      =>$filter_no_faktur,
			'vendor_id'=> $filter_vendor,
			//'surat_id'=> $filter_no_surat,
			'jenisproduk'	=> $filter_jenis_barang,
      'invoice_pembelian.status'	=> empty($filter_status)?array('>=',1):$filter_status,
			'invoice_pembelian.statuspenerimaan'	=> empty($filter_status_penerimaan)?array('<=',3):$filter_status_penerimaan,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'invoice_pembelian.id'	=> 'DESC',
			'invoice_pembelian.status'	=> 'ASC'
		);

		$this->load->model('catalog/gudang');

		$product_total = $this->model_pembelian_invoicepembeliankredit->totalPermintaans($data);

		$results = $this->model_pembelian_invoicepembeliankredit->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();
		if($result['status'] == 1 & $result['statuspenerimaan'] == 0 /*& $result['inputpib'] != 1 & $result['statuspembayaranpib'] != 1 & $result['inputkursdatang'] != 1*/){
				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('pembelian/invoicepembeliankredit/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}
			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('pembelian/invoicepembeliankredit/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			if($result['status'] != 3){
				$action[] = array(
					'text' => 'Estimasi Biaya',
					'href' => $this->url->link('pembelian/invoicepembeliankredit/biaya', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}



			$gudang="";
			if($result['gudang_id'] > 0){
				$g=$this->model_catalog_gudang->getGudang($result['gudang_id']);
				$gudang=$g['nama'];
			}
			$this->data['permintaans'][] = array(
				'name'	=> $result['name'],
				'gudang'	=> $result['nama'],
				'no_faktur'	=> $result['no_faktur'],
				'id'	=> $result['id'],
				'sub_total'	=> $this->currency->format($result['sub_total'],2,'.',','),
				'diskon'	=> $this->currency->format($result['diskon'],2,'.',','),
				'pajak'	=> $this->currency->format($result['pajak'],0,'.',','),
				'total'	=> $this->currency->format($result['total'],2,'.',','),
				'totalbayar'	=> $this->currency->format($result['totalbayar'],2,'.',','),
				'tanggal'	=> date('d/m/y',strtotime($result['tglfaktur'])),
				'jatuhtempo'	=> date('d/m/y',strtotime($result['jatuhtempo'])),
				'tgllunas'	=> date('d/m/y',strtotime($result['tgllunas'])),
				'jenis_barang'	=> $result['jenisproduk'],
				'status'	=> $result['status'] == 1?'Ditagih':($result['status'] == 2?'Dibayar Sebagian':($result['status'] == 3?'Dibatalkan':'Lunas')),
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Invoice Pembelian Lokal';

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_faktur'] = $filter_no_faktur;
		$this->data['filter_no_po'] = $filter_no_po;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_vendor'] = $filter_vendor;

		$this->template = 'pembelian/invoicepembeliankredit.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Invoice Pembelian Import');

		$this->load->model('pembelian/invoicepembeliankredit');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_po=$this->model_pembelian_invoicepembeliankredit->addPenjualan($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Invoice pembelian import berhasil disimpan ';

			if (isset($this->request->get['filter_no_faktur'])) {
				$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
			}
			if (isset($this->request->get['filter_jenis_barang'])) {
				$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
			}

			if (isset($this->request->get['filter_vendor'])) {
				$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_status_penerimaan'])) {
				$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
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

		if (isset($this->request->post['vendor_id'])) {
			$this->data['vendor_id'] = $this->request->post['vendor_id'];
		}  else {
			$this->data['vendor_id'] = '';
		}




		$this->data['cancel']= $this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/invoicepembeliankredit/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = array();
		}

		$this->load->model('catalog/gudang');
		$gudangs = $this->model_catalog_gudang->getGudangs(true);
		$this->data['gudangs']=$gudangs;
        
        $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}

		$this->template = 'pembelian/invoicepembeliankredit_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function tampil(){
		$this->document->setTitle('Invoice Pembelian Import');
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/invoicepembeliankredit');

		$column=array('invoice_pembelian_import.*','vendorimport.name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'invoice_pembelian_import.vendor_id'
		);

		$data = array(
			'invoice_pembelian_import.id'	=> $id,
      'invoice_pembelian_import.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelianProduct(array('invoice_id'	=> $id));
		//print_r($prods);
		$biayas=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelianBiaya(array('order_id'	=> $id));
		$pembayarans=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelianPembayaran(array('invoice_id'=>$id));
		/*$pbjoin[]=array(
			'tablename'=>'banks',
			'firsttable'	=> 'pembayaran_import.bank_id',
			'secondtable'=> 'banks.id'
		);
		$pembayarans=$this->model_pembelian_pembayarandpimport->getPermintaanPembelians(array('pembayaran_import.*','banks.name'),$pbjoin,array('no_po'=>$trans['no_po'],'status'=>1));
		//print_r($pembayarans);*/
		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['biayas']=$biayas;
		$this->data['pembayarans']=$pembayarans;

		$this->data['cancel']= $this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/invoicepembeliankredit_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function batalkan(){
		$this->load->model('pembelian/invoicepembeliankredit');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_pembelian_invoicepembeliankredit->batalInvoice(array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Pembelian Import berhasil dibatalkan.';
			}
		}
		$url='';
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

			$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	public function autocomplete(){
		$rests = array();

		$this->load->model('pembelian/invoicepembeliankredit');

			if (isset($this->request->get['q'])) {
				$filter_no_po = $this->request->get['q'];
			} else {
				$filter_no_po = '';
			}
			if (isset($this->request->get['f'])) {
				$filter_vendor = $this->request->get['f'];
			} else {
				$filter_vendor = null;
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_faktur'         => array('LIKE',$filter_no_po),
				'status'	=> array('<>',3)
			);
			if(!is_null($filter_vendor)){
				$data['vendor_id']	=	$filter_vendor;
			}
			$start=0;
			$limit=0;
			$column=array('id','no_faktur','total');
			$join=array();
			$leftjoin=array();

			$results = $this->model_pembelian_invoicepembeliankredit->getPermintaanPembelians($column,$join,$join,$data,array(),$limit,$start);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					//'text'	=> $r['no_po'].' Total $'.number_format($r['total_pembelian'])
					'text'	=> $r['no_faktur']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}



	public function autocompletepembayaran(){
		$rests = array();

		$this->load->model('pembelian/invoicepembeliankredit');

			if (isset($this->request->get['q'])) {
				$filter_no_po = $this->request->get['q'];
			} else {
				$filter_no_po = '';
			}
			if (isset($this->request->get['f'])) {
				$filter_faktur = $this->request->get['f'];
			} else {
				$filter_faktur = null;
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_faktur'         => array('LIKE',$filter_no_po),
				'status'	=> array('<',3)
			);

			$start=0;
			$limit=0;
			$column=array('id','no_faktur','totaltagihan');
			$join=array();
			$leftjoin=array();

			$results = $this->model_pembelian_invoicepembeliankredit->getPermintaanPembelians($column,$join,$join,$data,array(),$limit,$start);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					//'text'	=> $r['no_po'].' Total $'.number_format($r['total_pembelian'])
					'text'	=> $r['no_faktur'].'- Total Tagihan '
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function detail(){
		$hasil = array();

		$this->load->model('pembelian/permintaanpembelian');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			$this->load->model('pembelian/invoicepembeliankredit');
			$column=array('invoice_pembelian_import.*');
			$join=array();


			$data = array(
				'invoice_pembelian_import.id'	=> $id,
	      'invoice_pembelian_import.hapus'	=> 0,
				'invoice_pembelian_import.status'	=> array('<',3)
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);

			$hasil=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelian($column,$join,$data);
		}
	}
		$this->response->setOutput(json_encode($hasil));


	}

	public function biaya(){
		$this->document->setTitle('Biaya Pembelian Import');
		$url='';
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/invoicepembeliankredit');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_pembelian_invoicepembeliankredit->koreksibiaya($this->request->post);

			$this->session->data['success'] = 'Sukses: Data biaya berhasil diperbarui';



			$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$column=array('invoice_pembelian_import.*','vendorimport.name');
		$join=array();

		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'invoice_pembelian_import.vendor_id'
		);

		$data = array(
			'invoice_pembelian_import.id'	=> $id,
      'invoice_pembelian_import.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelian($column,$join,$data);
		if($trans['statuspenerimaan'] == 1){
			$this->session->data['warning'] = 'Perhatian: Estimasi Biaya Tidak Dapat Ditambahkan karena barang telah diterima';



			$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$biayas=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelianBiaya(array('order_id'	=> $id));
		
        $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
        
        $this->data['permintaan']=$trans;
		$this->data['biayas']=$biayas;
		$this->data['cancel']= $this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']=$this->url->link('pembelian/invoicepembeliankredit/biaya', 'token=' . $this->session->data['token'] . '&id='.$this->request->get['id'].$url, 'SSL');
		$this->template = 'pembelian/pembelianimport_biaya.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function pib(){
		$this->document->setTitle('Pemberitahuan Import Barang');
		$url='';
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/invoicepembeliankredit');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_pembelian_invoicepembeliankredit->addPib($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Pemberitahuan Import Barang berhasil diperbarui';



			$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$column=array('invoice_pembelian_import.*','vendorimport.name');
		$join=array();

		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'invoice_pembelian_import.vendor_id'
		);

		$data = array(
			'invoice_pembelian_import.id'	=> $id,
      'invoice_pembelian_import.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelian($column,$join,$data);
		if($trans['statuspembayaranpib'] == 1){
			$this->session->data['warning'] = 'Perhatian: Pemberatuhan Import Barang telah dibuat atau dibayar';



			$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
        
        $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
        
		$this->data['permintaan']=$trans;
		$this->data['cancel']= $this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']=$this->url->link('pembelian/invoicepembeliankredit/pib', 'token=' . $this->session->data['token'] . '&id='.$this->request->get['id'].$url, 'SSL');
		$this->template = 'pembelian/pembelianimport_pib.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function batalkanpib(){
		$this->document->setTitle('Pemberitahuan Import Barang');
		$url='';
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/invoicepembeliankredit');

		/*if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_pembelian_invoicepembeliankredit->addPib($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Pemberitahuan Import Barang berhasil diperbarui';



			$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}*/

		$column=array('invoice_pembelian_import.*','vendorimport.name');
		$join=array();

		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'invoice_pembelian_import.vendor_id'
		);

		$data = array(
			'invoice_pembelian_import.id'	=> $id,
      'invoice_pembelian_import.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelian($column,$join,$data);
		if($trans['statuspembayaranpib'] == 1){
			$this->session->data['warning'] = 'Perhatian: Pemberitahuan Import Barang tidak dapat dibatalkan karena telah dilakukan pembayaran';



			$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}else{
			$data['id']=$id;
			$this->model_pembelian_invoicepembeliankredit->batalPib($data);

			$this->session->data['success'] = 'Sukses: Data Pemberitahuan Import Barang berhasil dibatalkan';



			$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

	}

	public function kurs(){
		$this->document->setTitle('Kurs Tengah BI');
		$url='';
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/invoicepembeliankredit');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_pembelian_invoicepembeliankredit->addKurs($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Kurs Barang Datang berhasil diperbarui';



			$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$column=array('invoice_pembelian_import.*','vendorimport.name');
		$join=array();

		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'invoice_pembelian_import.vendor_id'
		);

		$data = array(
			'invoice_pembelian_import.id'	=> $id,
      'invoice_pembelian_import.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelian($column,$join,$data);
		if($trans['statuspenerimaan'] == 1 ){
			$this->session->data['warning'] = 'Perhatian: Kurs Barang Datang tidak dapat diubah karena barang telah diterima';



			$this->redirect($this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->data['permintaan']=$trans;
		$this->data['cancel']= $this->url->link('pembelian/invoicepembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']=$this->url->link('pembelian/invoicepembeliankredit/kurs', 'token=' . $this->session->data['token'] . '&id='.$this->request->get['id'].$url, 'SSL');
		
        $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
        
        $this->template = 'pembelian/pembelianimport_kurs.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}


}
?>
