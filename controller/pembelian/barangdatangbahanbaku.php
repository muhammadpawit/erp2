<?php
class ControllerPembelianBarangdatangbahanbaku extends Controller {
	private $error=array();
	public function index() {		
		$this->document->setTitle('Pembelian Bahan Baku');

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



		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->session->data['warning'])) {
			$this->data['error_warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else {
			$this->data['error_warning'] = '';
		}

		/*$this->load->model('catalog/product');
		$this->load->model('gudang/product');
		$this->load->model('pembelian/permintaanpembelian');
		$this->load->model('catalog/gudang');
		*/

		$this->load->model('catalog/vendorlokal');
		$this->load->model('pembelian/pembeliankreditbahanbaku');

		$this->data['permintaans'] = array();
		$column=array('pembelian_kreditbahanbaku.*','pembelian_produk_kreditbahanbaku.product_name','pembelian_produk_kreditbahanbaku.quantity','pembelian_produk_kreditbahanbaku.quantityterima','vendorlokal.name','pembelian_produk_kreditbahanbaku.status AS statusproduk');
		//$column=array('pembelian_import.*','pembelian_produk_import.product_name','pembelian_produk_import.quantity','pembelian_produk_import.quantity_invoice','pembelian_produk_import.invoice_id','invoice_pembelian_import.no_faktur','permintaan_pembelian.no_surat','vendorimport.name','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_kreditbahanbaku.surat_id'
		);
		$join[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'pembelian_kreditbahanbaku.vendor_id'
		);

		$leftjoin=array();
		/*$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'secondtable'	=>'gudang.gudang_id',
			'firsttable'	=> 'pembelian_kredit.gudang_id'
		);*/
		$leftjoin[]=array(
			'tablename'	=> 'pembelian_produk_kreditbahanbaku',
			'secondtable'	=>'pembelian_produk_kreditbahanbaku.pembelian_id',
			'firsttable'	=> 'pembelian_kreditbahanbaku.id'
		);



		$data = array(
			'no_po'      =>array('LIKE',$filter_no_po),
			//'no_faktur'      =>array('LIKE',$filter_no_faktur),
			'vendor_id'=> $filter_vendor,
			'surat_id'=> $filter_no_surat,
			'pembelian_kreditbahanbaku.jenis_barang'	=> $filter_jenis_barang,
      'pembelian_kreditbahanbaku.status'	=> array('<>',3),
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		if($filter_status != '*'){
			if($filter_status == 0){
				$data['pembelian_kreditbahanbaku.status']=array('<',1);
			}else{
				$data['pembelian_kreditbahanbaku.status']=$filter_status;
			}
		}

		if(!empty($filter_date_end)){
			$data['pembelian_kreditbahanbaku.date_added']=array('>=',$filter_date_start,'<=',$filter_date_end);
		}else{
			$data['pembelian_kreditbahanbaku.date_added']=array('>=',$filter_date_start);
		}

		if($filter_date_start == '1970-01-01'){
			$filter_date_start=null;
		}

		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'pembelian_kreditbahanbaku.date_added'	=> 'DESC',
			'pembelian_kreditbahanbaku.id'	=> 'DESC',
			'pembelian_kreditbahanbaku.status'	=> 'ASC'
		);

		$this->load->model('catalog/gudang');

		$product_total = $this->model_pembelian_pembeliankreditbahanbaku->totalPermintaans($data);

		$results = $this->model_pembelian_pembeliankreditbahanbaku->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();
			if($result['status'] != 1 ){
				$action[] = array(
					'text' => 'Terima',
					'href' => $this->url->link('pembelian/barangdatangbahanbaku/barangdatang', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}


			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('pembelian/barangdatangbahanbaku/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);




			if(($result['quantityterima'] < $result['quantity']) & $result['status'] != 3 & $result['status'] != 1){
				if($result['quantityterima'] == 0){
					$result['status']=0;
				}else{
					$result['status'] = 2;
				}
			}

			$this->data['permintaans'][] = array(
				'name'	=> $result['name'],
				'no_po'	=> $result['no_po'],
				'id'	=> $result['id'],
				'tanggal'	=> date('d/m/y',strtotime($result['date_added'])),
				'jenis_barang'	=> $result['jenis_barang'],
				'status'	=> $result['status'] == 0?'Belum Diterima':($result['status'] == 2?'Diterima Sebagian':($result['status'] == 5?'Sudah Diterima (PO Ditutup)':'Sudah Diterima')),
				'metode_pembayaran'	=> $result['metode_pembayaran'] == 1?'CBD':($result['metode_pembayaran'] == 2?'COD':'Kredit'),
				'product_name'=> $result['product_name'],
				'quantity'	=> $result['quantity'],
				'quantityterima'	=> $result['quantityterima'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Pembelian Bahan Baku';

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
		$pagination->url = $this->url->link('pembelian/barangdatangbahanbaku', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_faktur'] = $filter_no_faktur;
		$this->data['filter_no_po'] = $filter_no_po;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_vendor'] = $filter_vendor;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;

		$this->template = 'pembelian/barangdatangbahanbaku.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}



	public function tampil(){
		$this->document->setTitle('Barang Datang Bahan Baku');
		$url = '';

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
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
				$this->redirect($this->url->link('pembelian/barangdatangbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/barangdatangbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/barangdatangbahanbaku');

		$this->load->model('pembelian/pembeliankreditbahanbaku');

		$column=array('pembelian_kreditbahanbaku.*','permintaan_pembelian.no_surat','vendorlokal.name','permintaan_pembelian.jenis_barang');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_kreditbahanbaku.surat_id'
		);
		$join[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'pembelian_kreditbahanbaku.vendor_id'
		);

		$data = array(
			'pembelian_kreditbahanbaku.id'	=> $id,
      'permintaan_pembelian.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_pembeliankreditbahanbaku->getPermintaanPembelian($column,$join,$data);

		$column=array('suratjalan_produkbahanbaku.id_suratjalan','suratjalan_produkbahanbaku.status as statusterima','suratjalan_produkbahanbaku.id as idkedatangan','suratjalan_pembelianbahanbaku.no_suratjalan','suratjalan_pembelianbahanbaku.penerima_id','suratjalan_pembelianbahanbaku.pengangkut_id','suratjalan_pembelianbahanbaku.tgl_surat','suratjalan_pembelianbahanbaku.tgl_terima','suratjalan_pembelianbahanbaku.date_added','suratjalan_pembelianbahanbaku.no_pol','pembelian_kreditbahanbaku.no_po','suratjalan_produkbahanbaku.quantity as qtyterima','pembelian_produk_kreditbahanbaku.*');
		$join=array();
		$join[]=array(
			'tablename'	=> 'pembelian_kreditbahanbaku',
			'firsttable'	=>'suratjalan_pembelianbahanbaku.pembelian_kredit_id',
			'secondtable'	=> 'pembelian_kreditbahanbaku.id'
		);
		$join[]=array(
			'tablename'	=> 'suratjalan_produkbahanbaku',
			'firsttable'	=>'suratjalan_pembelianbahanbaku.id',
			'secondtable'	=> 'suratjalan_produkbahanbaku.id_suratjalan'
		);
		$join[]=array(
			'tablename' => 'pembelian_produk_kreditbahanbaku',
			'firsttable'  => 'suratjalan_produkbahanbaku.pembelian_product_id',
			'secondtable' => 'pembelian_produk_kreditbahanbaku.id'
			);

	/*	$join[]=array(
			'tablename'	=> 'users',
			'firsttable'	=>'suratjalan_pembelian.penerima_id',
			'secondtable'	=> 'suratjalan_produk.id_suratjalan'
		);
		//idkeatangan => id kedatangan, po_product_id => id, po_id=$id,
*/
		$data = array(
			'suratjalan_pembelianbahanbaku.pembelian_kredit_id'	=> $id,
      'suratjalan_pembelianbahanbaku.hapus'	=> array('<',1),
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$prods=$this->model_pembelian_barangdatangbahanbaku->getPermintaanPembelianFull($column,$join,$data);
		//$prods=$this->model_pembelian_barangdatang->getPermintaanPembelianProduct(array('id_suratjalan'	=> $id));
		//print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['cancel']= $this->url->link('pembelian/barangdatangbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/barangdatangbahanbaku_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function barangdatang(){
		$this->load->model('pembelian/pembeliankreditbahanbaku');
		$this->document->setTitle('Pembelian Bahan Baku');
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
		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
      		$no_po=$this->model_pembelian_pembeliankreditbahanbaku->barangdatang($this->request->post);
			$this->session->data['success'] = 'Sukses: Data Barang Datang berhasil disimpan';
			$this->redirect($this->url->link('pembelian/barangdatangbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/barangdatangbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/barangdatangbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->data['action']= $this->url->link('pembelian/barangdatangbahanbaku/barangdatang', 'token=' . $this->session->data['token'] . $url, 'SSL');


		$this->load->model('catalog/gudang');

		$column=array('pembelian_kreditbahanbaku.*','permintaan_pembelian.no_surat','vendorlokal.name','permintaan_pembelian.jenis_barang');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_kreditbahanbaku.surat_id'
		);
		$join[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'pembelian_kreditbahanbaku.vendor_id'
		);

		$data = array(
			'pembelian_kreditbahanbaku.id'	=> $id,
      'permintaan_pembelian.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_pembeliankreditbahanbaku->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_pembelian_pembeliankreditbahanbaku->getPermintaanPembelianProduct(array('pembelian_id'	=> $id));
		$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
	//print_r($trans);
		if($trans['jenis_barang'] == 1){
			$this->load->model('catalog/bahanbaku');
			$this->load->model('produksi/bukaproduksi');
			$this->data['cek']=$this->model_produksi_bukaproduksi->getPermintaanPembelian(array(),array(),array('status' => 1));
			/*if(!empty($this->data['cek'])){
				$this->session->data['warning'] = 'Peringatan: Mohon tutup proses produksi sebelum pemrosesan barang datang.';
				$this->redirect($this->url->link('pembelian/barangdatangbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));

			}*/
			$nprods=array();
			$i=1;
			foreach($prods as $p){
				$bb=$this->model_catalog_bahanbaku->getProduct($p['product_id']);
				$nprods[$i]=$p;
				$nprods[$i]['detail']=$bb;
				$i++;
			}
			$this->data['products']=$nprods;
		}else{
			$this->data['products']=$prods;
		}
		$this->data['permintaan']=$trans;

		//print_r($nprods);
		$this->data['gudang']=$gudang;
		$this->data['cancel']= $this->url->link('pembelian/barangdatangbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/barangdatangbahanbaku_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function batal(){
		$this->load->model('pembelian/pembeliankreditbahanbaku');
		$this->document->setTitle('Pembelian Bahan Baku');
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
		/*if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
      		$no_po=$this->model_pembelian_pembeliankreditbahanbaku->barangdatang($this->request->post);
			$this->session->data['success'] = 'Sukses: Data Barang Datang berhasil disimpan';
			$this->redirect($this->url->link('pembelian/barangdatangbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}*/
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/barangdatangbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/barangdatangbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		/*if(isset($this->request->get['po_id'])){
			if(!empty($this->request->get['po_id'])){
				$po_id=$this->request->get['po_id'];
			}else{
				$this->redirect($this->url->link('pembelian/barangdatangbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/barangdatangbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}*/
		//$this->model_pembelian_pembeliankreditbahanbaku->batalbarangdatang($po_id,$id);
		//cek invoice
		$cekinvoice = $this->model_pembelian_pembeliankreditbahanbaku->cekinvoicebarangdatang($id);
		if($cekinvoice){
			$batal=$this->model_pembelian_pembeliankreditbahanbaku->batalbarangdatang($id);
			$this->session->data['success'] ="Barang datang bahan baku berhasil dibatalkan";
		}else{
			$this->session->data['warning'] = 'Error: PO sudah dibuat invoice tidak dapat dibatalkan';
			
		}
		$this->redirect($this->url->link('pembelian/barangdatangbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));

		
	}

}
?>
