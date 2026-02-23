<?php
class ControllerPembelianBarangdatangaset extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Penerimaan Pembelian Aset');

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

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}



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
		$this->load->model('pembelian/pembeliankredit');

		$this->data['permintaans'] = array();
		$column=array('pembelian_kredit.*','pembelian_produk_kredit.product_name','pembelian_produk_kredit.quantity','pembelian_produk_kredit.quantityterima','vendorlokal.name','gudang.nama','pembelian_produk_kredit.status AS statusproduk');
		//$column=array('pembelian_import.*','pembelian_produk_import.product_name','pembelian_produk_import.quantity','pembelian_produk_import.quantity_invoice','pembelian_produk_import.invoice_id','invoice_pembelian_import.no_faktur','permintaan_pembelian.no_surat','vendorimport.name','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_kredit.surat_id'
		);
		$join[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'pembelian_kredit.vendor_id'
		);

		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'secondtable'	=>'gudang.gudang_id',
			'firsttable'	=> 'pembelian_kredit.gudang_id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'pembelian_produk_kredit',
			'secondtable'	=>'pembelian_produk_kredit.pembelian_id',
			'firsttable'	=> 'pembelian_kredit.id'
		);



		$data = array(
			'no_po'      =>array('LIKE',$filter_no_po),
			//'no_faktur'      =>array('LIKE',$filter_no_faktur),
			'vendor_id'=> $filter_vendor,
			'surat_id'=> $filter_no_surat,
			'pembelian_kredit.jenis_barang'	=> 4,
      'pembelian_kredit.status'	=> array('<>',3),
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		if($filter_status != '*'){
			if($filter_status == 0){
				$data['pembelian_kredit.status']=array('<',1);
			}else{
				$data['pembelian_kredit.status']=$filter_status;
			}
		}
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'pembelian_kredit.id'	=> 'DESC',
			'pembelian_kredit.status'	=> 'ASC'
		);

		$this->load->model('catalog/gudang');
		//$this->load->model('pembelian/pembayarandp');

		$product_total = $this->model_pembelian_pembeliankredit->totalPermintaans($data);

		$results = $this->model_pembelian_pembeliankredit->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();
			if($result['status'] != 1 ){
				$action[] = array(
					'text' => 'Terima',
					'href' => $this->url->link('pembelian/barangdatangaset/barangdatang', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}


			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('pembelian/barangdatangaset/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);


			$gudang="";
			if($result['gudang_id'] > 0){
				$g=$this->model_catalog_gudang->getGudang($result['gudang_id']);
				$gudang=$g['nama'];
			}

			$joinpemb=array();


			$pemb = array(
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

			$this->data['permintaans'][] = array(
				'name'	=> $result['name'],
				'gudang'	=> $result['nama'],
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

		$this->data['heading_title'] = 'Pembelian Kredit';

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

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pembelian/barangdatangaset', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_faktur'] = $filter_no_faktur;
		$this->data['filter_no_po'] = $filter_no_po;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_vendor'] = $filter_vendor;

		$this->template = 'pembelian/barangdatangaset.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}




	public function tampil(){
		$this->document->setTitle('Barang Datang Pembelian Aset');
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
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/barangdatangaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/barangdatangaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/barangdatang');

		$this->load->model('pembelian/pembeliankredit');
		$this->load->model('pembelian/pembayarandp');

		$column=array('pembelian_kredit.*','permintaan_pembelian.no_surat','vendorlokal.name','permintaan_pembelian.jenis_barang');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_kredit.surat_id'
		);
		$join[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'pembelian_kredit.vendor_id'
		);

		$data = array(
			'pembelian_kredit.id'	=> $id,
      'permintaan_pembelian.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_pembeliankredit->getPermintaanPembelian($column,$join,$data);

		$column=array('suratjalan_pembelian.no_suratjalan','suratjalan_pembelian.penerima_id','suratjalan_pembelian.pengangkut_id','suratjalan_pembelian.tgl_surat','suratjalan_pembelian.tgl_terima','suratjalan_pembelian.date_added','suratjalan_pembelian.no_pol','pembelian_kredit.no_po','suratjalan_produk.quantity as qtyterima','pembelian_produk_kredit.*');
		$join=array();
		$join[]=array(
			'tablename'	=> 'pembelian_kredit',
			'firsttable'	=>'suratjalan_pembelian.pembelian_kredit_id',
			'secondtable'	=> 'pembelian_kredit.id'
		);
		$join[]=array(
			'tablename'	=> 'suratjalan_produk',
			'firsttable'	=>'suratjalan_pembelian.id',
			'secondtable'	=> 'suratjalan_produk.id_suratjalan'
		);
		$join[]=array(
      'tablename' => 'pembelian_produk_kredit',
      'firsttable'  => 'suratjalan_produk.pembelian_product_id',
      'secondtable' => 'pembelian_produk_kredit.id'
    );

	/*	$join[]=array(
			'tablename'	=> 'users',
			'firsttable'	=>'suratjalan_pembelian.penerima_id',
			'secondtable'	=> 'suratjalan_produk.id_suratjalan'
		);
*/
		$data = array(
			'suratjalan_pembelian.pembelian_kredit_id'	=> $id,
      'suratjalan_pembelian.hapus'	=> array('<',1),
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$prods=$this->model_pembelian_barangdatang->getPermintaanPembelianFull($column,$join,$data);
		//$prods=$this->model_pembelian_barangdatang->getPermintaanPembelianProduct(array('id_suratjalan'	=> $id));
		//print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['cancel']= $this->url->link('pembelian/barangdatangaset', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/barangdatangaset_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function barangdatang(){
		$this->load->model('pembelian/pembeliankredit');
		$this->document->setTitle('Pembelian Kredit');
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
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
      $no_po=$this->model_pembelian_pembeliankredit->barangdatang($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Barang Datang berhasil disimpan';



			$this->redirect($this->url->link('pembelian/barangdatangaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/barangdatangaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/barangdatangaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->data['action']= $this->url->link('pembelian/barangdatangaset/barangdatang', 'token=' . $this->session->data['token'] . $url, 'SSL');


		$this->load->model('catalog/gudang');

		$column=array('pembelian_kredit.*','permintaan_pembelian.no_surat','vendorlokal.name','permintaan_pembelian.jenis_barang');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_kredit.surat_id'
		);
		$join[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'pembelian_kredit.vendor_id'
		);

		$data = array(
			'pembelian_kredit.id'	=> $id,
      'permintaan_pembelian.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_pembeliankredit->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_pembelian_pembeliankredit->getPermintaanPembelianProduct(array('pembelian_id'	=> $id));
		$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
	//print_r($trans);
		if($trans['jenis_barang'] == 1){
			$this->load->model('catalog/bahanbaku');
			$this->load->model('produksi/bukaproduksi');

			$this->data['cek']=$this->model_produksi_bukaproduksi->getPermintaanPembelian(array(),array(),array('status' => 1));
			if(!empty($this->data['cek'])){
				$this->session->data['warning'] = 'Peringatan: Mohon tutup proses produksi sebelum pemrosesan barang datang.';
				$this->redirect($this->url->link('pembelian/barangdatangaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));

			}

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
		$this->data['cancel']= $this->url->link('pembelian/barangdatangaset', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/barangdatangaset_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

}
?>
