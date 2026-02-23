<?php
class ControllerDaftarpoImportDaftarpoImport extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Pembelian Import');

		if (isset($this->request->get['filter_no_faktur'])) {
			$filter_no_faktur = $this->request->get['filter_no_faktur'];
		} else {
			$filter_no_faktur = '';
		}

		if (isset($this->request->get['filter_no_surat'])) {
			$filter_no_surat = $this->request->get['filter_no_surat'];
		} else {
			$filter_no_surat = '';
		}
		if (isset($this->request->get['filter_no_po'])) {
			$filter_no_po = $this->request->get['filter_no_po'];
		} else {
			$filter_no_po = '';
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
		
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
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

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('pembelian/pembelianimport/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		/*$this->load->model('report/product');
        $this->load->model('catalog/product');
		*/

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

		$this->load->model('catalog/vendorimport');
		$this->load->model('pembelian/pembelianimport');

		$this->data['permintaans'] = array();
		$column=array('pembelian_import.*','pembelian_produk_import.product_name','pembelian_produk_import.quantity','pembelian_produk_import.quantity_invoice','pembelian_produk_import.invoice_id','invoice_pembelian_import.no_faktur','permintaan_pembelian.no_surat','vendorimport.name','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_import.surat_id'
		);
		$join[]=array(
			'tablename'	=> 'pembelian_produk_import',
			'secondtable'	=>'pembelian_produk_import.pembelian_id',
			'firsttable'	=> 'pembelian_import.id'
		);
		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'pembelian_import.vendor_id'
		);
		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'secondtable'	=>'gudang.gudang_id',
			'firsttable'	=> 'pembelian_import.gudang_id'
		);
	$leftjoin[]=array(
			'tablename'	=> 'invoice_pembelian_import_product',
			'secondtable'	=>'invoice_pembelian_import_product.po_product_id',
			'firsttable'	=> 'pembelian_produk_import.id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'invoice_pembelian_import',
			'secondtable'	=>'invoice_pembelian_import.id',
			'firsttable'	=> 'invoice_pembelian_import_product.invoice_id'
		);


		$data = array(
			'no_po'      =>array('LIKE',$filter_no_po),
			'no_faktur'      =>array('LIKE',$filter_no_faktur),
			'pembelian_produk_import.product_name'      =>array('LIKE',$filter_name),
			'pembelian_import.vendor_id'=> $filter_vendor,
			'surat_id'=> $filter_no_surat,
			'jenis_barang'	=> $filter_jenis_barang,
			'pembelian_import.status'	=> array('<>',3),
			//'invoice_pembelian_import.status' => array('<>',4),
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		if(!empty($filter_date_end)){
			$data['pembelian_import.date_added']=array('>=',$filter_date_start,'<=',$filter_date_end);
		}else{
			$data['pembelian_import.date_added']=array('>=',$filter_date_start);
		}

		if($filter_date_start == '1970-01-01'){
			$filter_date_start=null;
		}

		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'pembelian_import.id'	=> 'DESC',
			'pembelian_import.status'	=> 'ASC'
		);

		$this->load->model('catalog/gudang');

		$product_total = $this->model_pembelian_pembelianimport->totalPermintaans($data,$join,$leftjoin);

		$results = $this->model_pembelian_pembelianimport->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();
			/*if($result['status'] == 0 & empty($result['no_faktur'])){
				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('pembelian/pembelianimport/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}
			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('pembelian/pembelianimport/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			$action[] = array(
				'text' => 'Biaya',
				'href' => $this->url->link('pembelian/pembelianimport/biaya', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			$action[] = array(
				'text' => 'Invoice',
				'href' => $this->url->link('pembelian/pembelianimport/invoice', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			$action[] = array(
				'text' => 'PIB',
				'href' => $this->url->link('pembelian/pembelianimport/pib', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);*/
			/*if($result['status'] == 0 | $result['status'] == 2){
			$action[] = array(
				'text' => 'Barang Datang',
				'href' => $this->url->link('pembelian/pembelianimport/barangdatang', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
		}*/
			$action[] = array(
				'text' => 'Cetak PO',
				'href' => $this->url->link('daftarpoimport/daftarpoimport/cetak', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			/*$action[] = array(
				'text' => 'Email PO',
				'href' => $this->url->link('pembelian/pembelianimport/email', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);*/
			//if($result['status'] == 1){

			//}

			/*if($result['status'] == 0){
				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('pembelian/pembelianimport/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}*/
			$gudang="";
			if($result['gudang_id'] > 0){
				$g=$this->model_catalog_gudang->getGudang($result['gudang_id']);
				$gudang=$g['nama'];
			}
			$this->data['permintaans'][] = array(
				'name'	=> $result['name'],
				'gudang'	=> $result['nama'],
				'no_po'	=> $result['no_po'],
				'no_faktur'	=> $result['no_faktur'],
				'id'	=> $result['id'],
				'no_surat'	=> $result['no_surat'],
				'hrefsurat' => $this->url->link('pembelian/permintaanpembelian/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['surat_id'].$url, 'SSL'),

				'surat_id'	=> $result['surat_id'],
				'sub_total'	=> '$'.number_format($result['sub_total'],2,'.',','),
				'diskon'	=> '$'.number_format($result['diskon'],2,'.',','),
				'pajak'	=> '$'.number_format($result['pajak'],0,'.',','),
				'total_pembelian'	=> '$'.number_format($result['total_pembelian'],2,'.',','),
				'tanggal'	=> date('d/m/y',strtotime($result['date_added'])),
				'no_surat'	=> $result['no_surat'],
				'jenis_barang'	=> $result['jenis_barang'],
				'status'	=> $result['status'] == 0?'Belum Diterima':($result['status'] == 2?'Diterima Sebagian':'Sudah Diterima'),
				'product_name'=> $result['product_name'],
				'quantity'	=> $result['quantity'],
				'quantity_invoice'	=> $result['quantity_invoice'],
				'no_faktur'	=> $result['no_faktur'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Pembelian Import';

		$this->data['token'] = $this->session->data['token'];
		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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
		$pagination->url = $this->url->link('daftarpoimport/daftarpoimport', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_faktur'] = $filter_no_faktur;
		$this->data['filter_no_po'] = $filter_no_po;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_vendor'] = $filter_vendor;
		$this->data['filter_name'] = $filter_name;

		$this->template = 'pembelian/daftarpoimport.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Pembelian Import');

		$this->load->model('pembelian/pembelianimport');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_po=$this->model_pembelian_pembelianimport->addPembelian($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Purchased Order berhasil disimpan dengan nomor PO '.$no_po;

			if (isset($this->request->get['filter_no_po'])) {
				$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
			}
			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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

			$this->redirect($this->url->link('pembelian/pembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->post['vendor_id'])) {
			$this->data['vendor_id'] = $this->request->post['vendor_id'];
		}  else {
			$this->data['vendor_id'] = '';
		}

		if (isset($this->request->post['surat_id'])) {
			$this->data['surat_id'] = $this->request->post['surat_id'];
		}  else {
			$this->data['surat_id'] = '';
		}

		if (isset($this->request->post['sub_total'])) {
			$this->data['sub_total'] = $this->request->post['sub_total'];
		}  else {
			$this->data['sub_total'] = '';
		}

		if (isset($this->request->post['pembulatan'])) {
			$this->data['pembulatan'] = $this->request->post['pembulatan'];
		}  else {
			$this->data['pembulatan'] = '0';
		}

		if (isset($this->request->post['pajak'])) {
			$this->data['pajak'] = $this->request->post['pajak'];
		}  else {
			$this->data['pajak'] = '';
		}

		if (isset($this->request->post['total_pembelian'])) {
			$this->data['total_pembelian'] = $this->request->post['total_pembelian'];
		}  else {
			$this->data['total_pembelian'] = '';
		}
		if (isset($this->request->post['tagihan'])) {
			$this->data['tagihan'] = $this->request->post['tagihan'];
		}  else {
			$this->data['tagihan'] = '0';
		}

		if (isset($this->request->post['jenis_barang'])) {
			$this->data['jenis_barang'] = $this->request->post['jenis_barang'];
		}  else {
			$this->data['jenis_barang'] = '';
		}


		$this->data['cancel']= $this->url->link('pembelian/pembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/pembelianimport/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = array();
		}

		if (isset($this->request->post['product'])) {
			$this->data['products'] = $this->request->post['product'];
		} else {
			$this->data['products'] = array();
		}

		$this->template = 'pembelian/pembelianimport_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {
    	/*if (!$this->user->hasPermission('modify', 'pembelian/permintaanpembelian')) {
      		$this->error['warning'] = 'Permission Denied.';
    	}

    	/*if (empty($this->request->post['date_added'])) {
      		$this->error['warning'] = 'Tanggal input product cacat harus diisi';
    	}*/

		if (empty($this->request->post['products'])) {
		  		$this->error['products'] = 'Produk tidak boleh kosong';
			}
		if (empty($this->request->post['vendor_id'])) {
		  		$this->error['vendor'] = 'Vendor tidak boleh kosong';
			}
		if (empty($this->request->post['surat_id'])) {
		  		$this->error['surat'] = 'Nomor Surat Permintaan Pembelian  tidak boleh kosong';
			}
		if(!is_numeric($this->request->post['sub_total']) ){
			$this->error['subtotal'] = 'Nilai Sub Total Harus Berupa Angka';
		}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function tampil(){
		$this->document->setTitle('Pembelian Import');
		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/pembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/pembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/pembelianimport');
		$this->load->model('pembelian/pembayarandpimport');

		$column=array('pembelian_import.*','permintaan_pembelian.no_surat','vendorimport.name','permintaan_pembelian.jenis_barang');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_import.surat_id'
		);
		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'pembelian_import.vendor_id'
		);

		$data = array(
			'pembelian_import.id'	=> $id,
      'permintaan_pembelian.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_pembelianimport->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_pembelian_pembelianimport->getPermintaanPembelianProduct(array('pembelian_id'	=> $id));
		//print_r($prods);
		$biayas=$this->model_pembelian_pembelianimport->getPermintaanPembelianBiaya(array('order_id'	=> $id));
		$pbjoin[]=array(
			'tablename'=>'banks',
			'firsttable'	=> 'pembayaran_import.bank_id',
			'secondtable'=> 'banks.id'
		);
		$pembayarans=$this->model_pembelian_pembayarandpimport->getPermintaanPembelians(array('pembayaran_import.*','banks.name'),$pbjoin,array('no_po'=>$trans['no_po'],'status'=>1));
		//print_r($pembayarans);
		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['biayas']=$biayas;
		$this->data['pembayarans']=$pembayarans;

		$this->data['cancel']= $this->url->link('pembelian/pembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/pembelianimport_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function invoice(){
		$this->load->model('pembelian/pembelianimport');
		$this->document->setTitle('Pembelian Import');
		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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

		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/pembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/pembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$data['action']=$this->url->link('pembelian/pembelianimport/invoice', 'token=' . $this->session->data['token'] . '&id='.$this->request->get['id'].$url, 'SSL');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$invoice=array(
				'id'	=> $id,
				'no_faktur'	=> $this->request->post['no_faktur'],
				'kurskmk'	=> $this->request->post['kurskmk'],
				'kursbi'	=> $this->request->post['kursbi'],
				'tglfaktur'	=> $this->request->post['tglfaktur'],
				'batasbayar'	=> $this->request->post['batasbayar'],
				'biaya'	=> $this->request->post['biaya']
			);
      $this->model_pembelian_pembelianimport->addInvoice($invoice);

			$this->session->data['success'] = 'Sukses: Data invoice berhasil disimpan';



			$this->redirect($this->url->link('pembelian/pembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}


		$column=array('pembelian_import.*','permintaan_pembelian.no_surat','vendorimport.name','permintaan_pembelian.jenis_barang');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_import.surat_id'
		);
		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'pembelian_import.vendor_id'
		);

		$data = array(
			'pembelian_import.id'	=> $id,
      'permintaan_pembelian.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_pembelianimport->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_pembelian_pembelianimport->getPermintaanPembelianProduct(array('pembelian_id'	=> $id));
		//print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['cancel']= $this->url->link('pembelian/pembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/pembelianimport_invoice.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function barangdatang(){
		$this->load->model('pembelian/pembelianimport');
		$this->document->setTitle('Pembelian Import');
		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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
		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
      $no_po=$this->model_pembelian_pembelianimport->barangdatang($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Barang Datang berhasil disimpan';



			$this->redirect($this->url->link('pembelian/pembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/pembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/pembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->data['action']= $this->url->link('pembelian/pembelianimport/barangdatang', 'token=' . $this->session->data['token'] . $url, 'SSL');


		$this->load->model('catalog/gudang');

		$column=array('pembelian_import.*','permintaan_pembelian.no_surat','vendorimport.name','permintaan_pembelian.jenis_barang');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_import.surat_id'
		);
		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'pembelian_import.vendor_id'
		);

		$data = array(
			'pembelian_import.id'	=> $id,
      'permintaan_pembelian.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_pembelianimport->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_pembelian_pembelianimport->getPermintaanPembelianProduct(array('pembelian_id'	=> $id));
		$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
	//	print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['gudang']=$gudang;
		$this->data['cancel']= $this->url->link('pembelian/pembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/barangdatangimport_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function cetak(){
		$this->document->setTitle('Pembelian Import');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/pembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/pembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/pembelianimport');

		$column=array('pembelian_import.*','permintaan_pembelian.no_surat','vendorimport.name','permintaan_pembelian.jenis_barang');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_import.surat_id'
		);
		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'pembelian_import.vendor_id'
		);

		$data = array(
			'pembelian_import.id'	=> $id,
      'permintaan_pembelian.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_pembelianimport->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_pembelian_pembelianimport->getPermintaanPembelianProduct(array('pembelian_id'	=> $id));
		//print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;

		$this->template = 'pembelian/daftarpoimport_cetak.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function batalkan(){
		$this->load->model('pembelian/pembelianimport');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_pembelian_pembelianimport->updatePermintaan(array('status' => 3),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Pembelian Import berhasil dibatalkan.';
			}
		}
		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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

			$this->redirect($this->url->link('pembelian/pembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	public function autocomplete(){
		$rests = array();

		$this->load->model('pembelian/pembelianimport');

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
				'no_po'         => array('LIKE',$filter_no_po)
			);
			if(!is_null($filter_fatur)){
				$data['tglfaktur']	=	array('<>','1970-01-01');
			}
			$start=0;
			$limit=0;
			$column=array('id','no_po','total_pembelian');
			$join=array();
			$leftjoin=array();

			$results = $this->model_pembelian_pembelianimport->getPermintaanPembelians($column,$join,$join,$data,array(),$limit,$start);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['no_po'],
					//'text'	=> $r['no_po'].' Total $'.number_format($r['total_pembelian'])
					'text'	=> $r['no_po']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function detail(){
		$hasil = array();

		//$this->load->model('pembelian/permintaanpembelian');
		if(isset($this->request->get['vendor_id'])){
			if(!empty($this->request->get['vendor_id'])){

			$this->load->model('pembelian/pembelianimport');
			if(!empty($this->request->get['jenisbarang'])){
				$products=$this->model_pembelian_pembelianimport->getPoTanpaInvoice($this->request->get['vendor_id'],$this->request->get['jenisbarang'],$this->request->get['gudang_id']);
			}

			$hasil=array(
				//'order'	=> $trans,
				'products'	=> $products,
				//'address'	=> $this->data['address']
			);
		}
	}
		$this->response->setOutput(json_encode($hasil));
	}


}
?>
