<?php
class ControllerPembelianBarangdatangdagang extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Pembelian Produk Dagang');

		if (isset($this->request->get['filter_no_faktur'])) {
			$filter_no_faktur = $this->request->get['filter_no_faktur'];
		} else {
			$filter_no_faktur = '';
		}
		if (isset($this->request->get['filter_no_po'])) {
			$filter_no_po = $this->request->get['filter_no_po'];
		} else {
			$filter_no_po = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '*';
		}

		if (isset($this->request->get['filter_no_surat'])) {
			$filter_no_surat = $this->request->get['filter_no_surat'];
		} else {
			$filter_no_surat = '';
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


		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start ='1970-01-01';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = null;
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
		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('pembelian/barangdatangdagang/insert', 'token=' . $this->session->data['token'].$url, 'SSL');


		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		/*$this->load->model('catalog/product');
		$this->load->model('gudang/product');
		$this->load->model('pembelian/permintaanpembelian');
		$this->load->model('catalog/gudang');
		*/

		$this->load->model('catalog/vendorlokal');
		$this->load->model('pembelian/pembeliankreditdagang');
		$this->load->model('pembelian/barangdatangdagang');

		$this->data['permintaans'] = array();
		//$column=array('pembelian_kreditdagang.*','pembelian_produk_kreditdagang.product_name','pembelian_produk_kreditdagang.quantity','pembelian_produk_kreditdagang.quantityterima','vendorlokal.name','gudang.nama','pembelian_produk_kreditdagang.status AS statusproduk');
		//$column=array('pembelian_import.*','pembelian_produk_import.product_name','pembelian_produk_import.quantity','pembelian_produk_import.quantity_invoice','pembelian_produk_import.invoice_id','invoice_pembelian_import.no_faktur','permintaan_pembelian.no_surat','vendorimport.name','gudang.nama');

		$column=array('pembelian_produk_kreditdagang.id as po_product','suratjalan_pembeliandagang.*','product.name','vendorlokal.name as vendor','gudang.nama','suratjalan_produkdagang.quantity','pembelian_kreditdagang.no_po');
		$join=array();
		$join[]=array(
			'tablename'	=> 'suratjalan_produkdagang',
			'secondtable'	=>'suratjalan_produkdagang.id_suratjalan',
			'firsttable'	=> 'suratjalan_pembeliandagang.id'
		);


		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'secondtable'	=>'gudang.gudang_id',
			'firsttable'	=> 'suratjalan_pembeliandagang.gudang_id'
		);

		$leftjoin[]=array(
			'tablename'	=> 'pembelian_produk_kreditdagang',
			'secondtable'	=>'pembelian_produk_kreditdagang.id',
			'firsttable'	=> 'suratjalan_produkdagang.pembelian_product_id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'pembelian_kreditdagang',
			'secondtable'	=>'pembelian_kreditdagang.id',
			'firsttable'	=> 'suratjalan_produkdagang.po_id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'pembelian_kreditdagang.vendor_id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'product',
			'secondtable'	=>'product.product_id',
			'firsttable'	=> 'pembelian_produk_kreditdagang.product_id'
		);



		$data = array(
			'pembelian_kreditdagang.no_po'      =>array('LIKE',$filter_no_po),
			//'no_faktur'      =>array('LIKE',$filter_no_faktur),
			'pembelian_kreditdagang.vendor_id'=> $filter_vendor,
			'suratjalan_pembeliandagang.no_suratjalan'=> array('LIKE',$filter_no_surat),
			'pembelian_kreditdagang.status'	=> array('<>',3),
			'suratjalan_pembeliandagang.hapus'	=> array('<>',1),
			//'suratjalan_pembeliandagang.status'=> array('<>',3),
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		if($filter_status != '*'){
			if($filter_status == 0){
				$data['suratjalan_pembeliandagang.status']=array('<>',3);
			}else{
				$data['suratjalan_pembeliandagang.status']=$filter_status;
			}
		}else{
				$data['suratjalan_pembeliandagang.status']=array('<>',3);
		}

		if(!empty($filter_date_end)){
			$data['suratjalan_pembeliandagang.date_added']=array('>=',$filter_date_start,'<=',$filter_date_end);
		}else{
			$data['suratjalan_pembeliandagang.date_added']=array('>=',$filter_date_start);
		}

		if($filter_date_start == '1970-01-01'){
			$filter_date_start=null;
		}

		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'suratjalan_pembeliandagang.date_added'	=> 'DESC',
			'suratjalan_pembeliandagang.id'	=> 'DESC',
			'suratjalan_pembeliandagang.status'	=> 'ASC'
		);

		$this->load->model('catalog/gudang');

		$product_total = $this->model_pembelian_pembeliankreditdagang->totalBarangdatangs($data,$join,$leftjoin);

		$results = $this->model_pembelian_pembeliankreditdagang->getBarangdatangs($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('pembelian/barangdatangdagang/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			if($result['status'] == 1){
				/*$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('pembelian/barangdatangdagang/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);*/
			}else{
				$cekinvoice = $this->model_pembelian_barangdatangdagang->cekinvoicebarangdatang($result['po_product']);
				if($cekinvoice){
					/*$action[] = array(
						'text' => 'Batalkan',
						'href' => $this->url->link('pembelian/barangdatangdagang/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
					);*/
				}
			}


			//cekinvoice
			
			/*$pemb = array(
				'no_po'      =>$result['no_po'],
				'status'	=> array('<>',3),

			);

			if(($result['quantityterima'] < $result['quantity']) & $result['status'] != 3 & $result['status'] != 1){
				if($result['quantityterima'] == 0){
					$result['status']=0;
				}else{
					$result['status'] = 2;
				}
			}
			*/

			$this->data['permintaans'][] = array(
				'vendor'	=> $result['vendor'],
				'nama'	=> $result['nama'],
				'no_po'	=> $result['no_po'],
				'no_suratjalan'	=> $result['no_suratjalan'],
				'id'	=> $result['id'],
				'tanggal'	=> date('d/m/y',strtotime($result['date_added'])),
				'jenis_barang'	=> $result['jenis_barang'],
				'status'	=> $result['status'] == 1?'Belum Diterima':($result['status'] == 2?'Diterima':'Dibatalkan'),
				'product_name'=> $result['name'],
				'quantity'	=> $result['quantity'],
			//	'quantityterima'	=> $result['quantityterima'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Pembelian Produk Dagang';

		$this->data['token'] = $this->session->data['token'];
		$url = '';

		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pembelian/barangdatangdagang', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_faktur'] = $filter_no_faktur;
		$this->data['filter_no_po'] = $filter_no_po;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_vendor'] = $filter_vendor;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;



		$this->template = 'pembelian/barangdatangkreditdagang.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}



	public function tampil(){
		$this->document->setTitle('Barang Datang Pembelian Produk Dagang');
		$url = '';

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}

		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/barangdatangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/barangdatangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/barangdatangdagang');

		$this->load->model('pembelian/pembeliankreditdagang');

		$column=array('suratjalan_pembeliandagang.*','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'gudang',
			'secondtable'	=>'gudang.gudang_id',
			'firsttable'	=> 'suratjalan_pembeliandagang.gudang_id'
		);
		/*$join[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'pembelian_kreditdagang.vendor_id'
		);*/

		$data = array(
			'suratjalan_pembeliandagang.id'	=> $id,
      //'permintaan_pembelian.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_barangdatangdagang->getPermintaanPembelian($column,$join,$data);

		$column=array('pembelian_kreditdagang.no_po','suratjalan_produkdagang.quantity as qtyterima','pembelian_produk_kreditdagang.*');
		$join=array();

		/*$join[]=array(
			'tablename'	=> 'suratjalan_produk',
			'firsttable'	=>'suratjalan_pembelian.id',
			'secondtable'	=> 'suratjalan_produk.id_suratjalan'
		);*/
		$join[]=array(
			'tablename'	=> 'pembelian_kreditdagang',
			'firsttable'	=>'suratjalan_produkdagang.po_id',
			'secondtable'	=> 'pembelian_kreditdagang.id'
		);
		$join[]=array(
      'tablename' => 'pembelian_produk_kreditdagang',
      'firsttable'  => 'suratjalan_produkdagang.pembelian_product_id',
      'secondtable' => 'pembelian_produk_kreditdagang.id'
    );

	/*	$join[]=array(
			'tablename'	=> 'users',
			'firsttable'	=>'suratjalan_pembelian.penerima_id',
			'secondtable'	=> 'suratjalan_produk.id_suratjalan'
		);
*/
		$data = array(
			'suratjalan_produkdagang.id_suratjalan'	=> $id,
    //'suratjalan_pembelian.hapus'	=> array('<',1),
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$prods=$this->model_pembelian_barangdatangdagang->getPermintaanPembelianFull($column,$join,$data);
		$biayas=$this->model_pembelian_barangdatangdagang->getPermintaanPembelianBiaya(array('order_id'	=> $id));

		//$prods=$this->model_pembelian_barangdatang->getPermintaanPembelianProduct(array('id_suratjalan'	=> $id));
		//print_r($prods);
		$this->load->model('user/user');
		$this->data['penerima']=$trans['penerima_id']>0?$this->model_user_user->getUser($trans['penerima_id']):'';

		$this->data['pengangkut']=$trans['pengangkut_id']>0?$this->model_user_user->getUser($trans['pengangkut_id']):'';
		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['biayas']=$biayas;
		$this->data['cancel']= $this->url->link('pembelian/barangdatangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/barangdatangdagang_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Invoice Pembelian Lokal');

		$this->load->model('pembelian/pembeliankreditdagang');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
      $no_po=$this->model_pembelian_pembeliankreditdagang->barangdatang($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Invoice pembelian lokal berhasil disimpan ';

			$url = '';

			if (isset($this->request->get['filter_no_po'])) {
				$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
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

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('pembelian/barangdatangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url = '';

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
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

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
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




		$this->data['cancel']= $this->url->link('pembelian/barangdatangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/barangdatangdagang/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->template = 'pembelian/barangdatangdagang_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	public function batalkan(){

			$this->load->language('catalog/pembelian');

			$this->document->setTitle('Invoice Pembelian Lokal');

			$this->load->model('pembelian/barangdatangdagang');
			$url = '';

			if (isset($this->request->get['filter_no_po'])) {
				$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
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

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['id'])) {

				if(!empty($this->request->get['id'])){
					$cekstatus=$this->model_pembelian_barangdatangdagang->getPermintaanPembelian(array(),array(),array('id'=>$this->request->get['id']));

					//if($cekstatus['status'] == 1){
						$this->model_pembelian_barangdatangdagang->updatePermintaan(array('status'=>3),array('id'=>$this->request->get['id']),$this->request->get['id']);
						$this->session->data['success'] = 'Sukses: Data surat jalan berhasil dibatalkan ';
						$this->redirect($this->url->link('pembelian/barangdatangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
					/*}else{
						
						$this->redirect($this->url->link('pembelian/barangdatangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
					}*/
				}else{
					$this->redirect($this->url->link('pembelian/barangdatangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}



			}

	}
	public function autocomplete(){
		$rests = array();

		$this->load->model('pembelian/pembeliankreditdagang');

			if (isset($this->request->get['q'])) {
				$filter_no_po = $this->request->get['q'];
			} else {
				$filter_no_po = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_suratjalan'         => array('LIKE',$filter_no_po),
				'status'	=> 2
			);


			$start=0;
			$limit=0;
			$column=array('id','no_suratjalan','total');
			$join=array();
			$leftjoin=array();

			$results = $this->model_pembelian_pembeliankreditdagang->getBarangdatangs($column,$join,$leftjoin,$data,$order,$limit,$offset);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					//'text'	=> $r['no_po'].' Total $'.number_format($r['total_pembelian'])
					'text'	=> $r['no_suratjalan']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}
	public function detailbiaya(){
		$hasil = array();


		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$jenisbiaya_id=$this->request->get['id'];
				$order_id=$this->request->get['invoice_id'];
			$this->load->model('pembelian/barangdatangdagang');
			/*
			0 belum ada
			1 ditagih
			2 dibayar sebagian
			3 Lunas
			4 Dibatalkan
			*/

			$data = array(
				'jenisbiaya_id'	=> $jenisbiaya_id,
	      'order_id'	=> $order_id,
			//	'invoice_pembelian_import.statuspembayaran'	=> array('<',3)
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);

			$hasil=$this->model_pembelian_barangdatangdagang->getBiaya($data);
		}
	}
		$this->response->setOutput(json_encode($hasil));


	}

}
?>
