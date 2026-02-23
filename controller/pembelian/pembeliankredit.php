<?php
class ControllerPembelianPembeliankredit extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Pembelian Kredit');

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
		//echo $filter_status;
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

		$this->data['insert'] = $this->url->link('pembelian/pembeliankredit/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

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

		$this->load->model('catalog/vendorlokal');
		$this->load->model('pembelian/pembeliankredit');

		$this->data['permintaans'] = array();
		$column=array('pembelian_kredit.*','pembelian_produk_kredit.product_name','pembelian_produk_kredit.invoice_id as invoice','pembelian_produk_kredit.id as idproduk','pembelian_produk_kredit.quantity','pembelian_produk_kredit.quantityterima','permintaan_pembelian.no_surat','vendorlokal.name','gudang.nama');
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

		/*$leftjoin[]=array(
				'tablename'	=> 'invoice_pembelian_product',
				'secondtable'	=>'invoice_pembelian_product.po_product_id',
				'firsttable'	=> 'pembelian_produk_kredit.id'
			);
			$leftjoin[]=array(
				'tablename'	=> 'invoice_pembelian',
				'secondtable'	=>'invoice_pembelian.id',
				'firsttable'	=> 'invoice_pembelian_product.invoice_id'
			);*/

		$data = array(
			'no_po'      =>array('LIKE',$filter_no_po),
			//'invoice_pembelian.status' => array('<>',4),
			//'no_faktur'      =>array('LIKE',$filter_no_faktur),
			'pembelian_kredit.vendor_id'=> $filter_vendor,
			'surat_id'=> $filter_no_surat,
			'pembelian_kredit.jenis_barang'	=> $filter_jenis_barang,
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

		if(!empty($filter_date_end)){
			$data['pembelian_kredit.date_added']=array('>=',$filter_date_start,'<=',$filter_date_end);
		}else{
			$data['pembelian_kredit.date_added']=array('>=',$filter_date_start);
		}

		if($filter_date_start == '1970-01-01'){
			$filter_date_start=null;
		}

		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'pembelian_kredit.date_added'	=> 'DESC',
			'pembelian_kredit.id'	=> 'DESC',
			'pembelian_kredit.status'	=> 'ASC'
		);

		$this->load->model('catalog/gudang');
		$this->load->model('pembelian/pembayarandp');
		$this->load->model('pembelian/invoicepembeliankredit');

		$product_total = $this->model_pembelian_pembeliankredit->totalPermintaans($data,$join,$leftjoin);
		//countAll($tablename,$where=array(),$join=array(),$leftjoin=array())
		//$data['invoice_pembelian.status']=array('<>',4);
		$results = $this->model_pembelian_pembeliankredit->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();
			if($result['status'] == 0 ){
				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('pembelian/pembeliankredit/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}
			if($result['status'] == 2 ){
				$action[] = array(
					'text' => 'Tutup PO',
					'href' => $this->url->link('pembelian/pembeliankredit/tutuppo', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}


		$action[] = array(
			'text' => 'Tampil',
			'href' => $this->url->link('pembelian/pembeliankredit/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
		);
			$action[] = array(
				'text' => 'Cetak PO',
				'href' => $this->url->link('pembelian/pembeliankredit/cetak', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			/*if($result['status'] == 0 ){
				$action[] = array(
					'text' => 'Estimasi Biaya',
					'href' => $this->url->link('pembelian/pembeliankredit/biaya', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}*/



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

			$no_inv='';
		/*	if($result['invoice'] > 0){
				$inv=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelian(array(),array(),array('id'=>$result['invoice'],'hapus'=>array('<',1)));
				if(!empty($inv)){
					$no_inv=$inv['no_faktur'];
				}
			}*/
			$inv=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelianProduct(array('po_product_id'=> $result['idproduk']));
			foreach($inv as $i){
				$nof=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelian(array(),array(),array('id'=>$i['invoice_id'],'hapus'=>array('<',1)));
				if(!empty($nof)){
					$no_inv .=$nof['no_faktur'].'<br>';
				}
			}

			$col=array("COALESCE(SUM(jumlah),0) as total");
			/*$totalbayar=$this->model_pembelian_pembayarandp->getPermintaanPembelian($col,$joinpemb,$pemb);
			$totalbayar=$totalbayar['total'];
			*/
			//if($result['statusinvoice'] != 4){
			$this->data['permintaans'][] = array(
				'name'	=> $result['name'],
				'gudang'	=> $result['nama'],
				'no_po'	=> $result['no_po'],
				'no_surat'	=> $result['no_surat'],
				'no_faktur'	=> $no_inv,
				'id'	=> $result['id'],
				'hrefsurat' => $this->url->link('pembelian/permintaanpembelian/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['surat_id'].$url, 'SSL'),
				'surat_id'	=> $result['surat_id'],
				'sub_total'	=> $this->currency->format($result['sub_total']),
				'diskon'	=> $this->currency->format($result['diskon']),
				'pajak'	=> $this->currency->format($result['pajak']),
				'total_pembelian'	=> $this->currency->format($result['total_pembelian']),
				//'totalbayar'	=> $this->currency->format($result['totalbayarinv']),
				//'statusbayar'	=> ($totalbayar < $result['total_pembelian'])?'Belum Lunas':'Sudah Lunas',
				'tanggal'	=> date('d/m/y',strtotime($result['date_added'])),
				//'jatuhtempo'	=> date('d/m/y',strtotime($result['jatuhtempo'])),

				'no_surat'	=> $result['no_surat'],
				'jenis_barang'	=> $result['jenis_barang'],
				'status'	=> $result['status'] == 0?'Belum Diterima':($result['status'] == 2?'Diterima Sebagian':($result['status'] == 5?'Sudah Diterima (PO Ditutup)':'Sudah Diterima')),
				'metode_pembayaran'	=> $result['metode_pembayaran'] == 1?'CBD':($result['metode_pembayaran'] == 2?'COD':'Kredit'),
				'product_name'=> $result['product_name'],
				'quantity'	=> $result['quantity'],
				'quantityterima'	=> $result['quantityterima'],
			//	'quantity_invoice'	=> $result['quantity_invoice'],
				'actions'	=> $action
			);
			//}
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

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_faktur'] = $filter_no_faktur;
		$this->data['filter_no_po'] = $filter_no_po;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_vendor'] = $filter_vendor;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_status'] = $filter_status;
		$this->template = 'pembelian/pembeliankredit.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Pembelian Kredit');

		$this->load->model('pembelian/pembeliankredit');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_po=$this->model_pembelian_pembeliankredit->addPembelian($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Purchased Order berhasil disimpan dengan nomor PO '.$no_po;

			$url = '';

			if (isset($this->request->get['filter_no_faktur'])) {
				$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
			}
			if (isset($this->request->get['filter_no_po'])) {
				$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
			}
			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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

			$this->redirect($this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url = '';

		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
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

		if (isset($this->request->post['jenis_barang'])) {
			$this->data['jenis_barang'] = $this->request->post['jenis_barang'];
		}  else {
			$this->data['jenis_barang'] = '';
		}


		$this->data['cancel']= $this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/pembeliankredit/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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
        
         $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
        

		$this->template = 'pembelian/pembeliankredit_form.tpl';
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
		$this->document->setTitle('Pembelian Kredit');
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
				$this->redirect($this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

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
		$prods=$this->model_pembelian_pembeliankredit->getPermintaanPembelianProduct(array('pembelian_id'	=> $id));
		$biayas=$this->model_pembelian_pembeliankredit->getPermintaanPembelianBiaya(array('order_id'	=> $id));
		$pbjoin[]=array(
			'tablename'=>'banks',
			'firsttable'	=> 'pembayaran_dp.bank_id',
			'secondtable'=> 'banks.id'
		);

		$pembayarans=$this->model_pembelian_pembayarandp->getPermintaanPembelians(array('pembayaran_dp.*','banks.name'),$pbjoin,array('no_po'=>$trans['no_po'],'status'=>1));
		//print_r($pembayarans);



		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['biayas']=$biayas;
		$this->data['pembayarans']=$pembayarans;

		$this->data['cancel']= $this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/pembeliankredit_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function biaya(){
		$this->document->setTitle('Biaya Pembelian');
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
				$this->redirect($this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/pembeliankredit');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_pembelian_pembeliankredit->addBiaya($this->request->post);

			$this->session->data['success'] = 'Sukses: Data biaya berhasil diperbarui';



			$this->redirect($this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

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
        
         $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
        

		$trans=$this->model_pembelian_pembeliankredit->getPermintaanPembelian($column,$join,$data);
		$biayas=$this->model_pembelian_pembeliankredit->getPermintaanPembelianBiaya(array('order_id'	=> $id));
		$this->data['permintaan']=$trans;
		$this->data['biayas']=$biayas;
		$this->data['cancel']= $this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['action']=$this->url->link('pembelian/pembeliankredit/biaya', 'token=' . $this->session->data['token'] . '&id='.$this->request->get['id'].$url, 'SSL');
		$this->template = 'pembelian/pembeliankredit_biaya.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function invoice(){
		$this->load->model('pembelian/pembeliankredit');
		$this->document->setTitle('Pembelian Kredit');
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
				$this->redirect($this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$data['action']=$this->url->link('pembelian/pembeliankredit/invoice', 'token=' . $this->session->data['token'] . '&id='.$this->request->get['id'].$url, 'SSL');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$invoice=array(
				'id'	=> $id,
				'no_faktur'	=> $this->request->post['no_faktur'],
				'tglfaktur'	=> $this->request->post['tglfaktur'],
				'batasbayar'	=> $this->request->post['batasbayar']
			);
      $this->model_pembelian_pembeliankredit->addInvoice($invoice);

			$this->session->data['success'] = 'Sukses: Data invoice berhasil disimpan';



			$this->redirect($this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}


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
		//print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['cancel']= $this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL');
        
         $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
        
        
		$this->template = 'pembelian/pembeliankredit_invoice.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function cetak(){
		$this->document->setTitle('Pembelian Kredit');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/pembeliankredit');

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
		//print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;

		$this->template = 'pembelian/pebeliankredit_cetak.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function batalkan(){
		$this->load->model('pembelian/pembeliankredit');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_pembelian_pembeliankredit->updatePermintaan(array('status' => 3),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Pembelian Kredit berhasil dibatalkan.';
			}
		}
			$url = '';

			if (isset($this->request->get['filter_no_po'])) {
				$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
			}
			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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

			$this->redirect($this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function tutuppo(){
		$this->load->model('pembelian/pembeliankredit');
		$this->load->model('pembelian/invoicepembeliankredit');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
			$pembelian=$this->model_pembelian_pembeliankredit->getPermintaanPembelian(array(),array(),array('id'	=> $this->request->get['id']));

			if($pembelian['status'] == 2){
				//cek invoice
				$invpembelian=$this->model_pembelian_pembeliankredit->tutuppo($this->request->get['id']);
				if($invpembelian){
					$this->session->data['success'] = 'Peringatan: Data Pembelian lokal berhasil di tutup.';
      		//$this->model_pembelian_pembeliankredit->updatePermintaan(array('status' => 1),array('id'	=> $this->request->get['id']));
				}else{
					$this->session->data['warning'] = 'Peringatan: Data Pembelian lokal gagal di tutup karena terdapat item dengan quantity belum diterima/diterima sebagian.';
				}
			}

			}
		}
			$url = '';

			if (isset($this->request->get['filter_no_po'])) {
				$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
			}
			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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

			$this->redirect($this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('pembelian/pembeliankredit');

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
				'no_po'         => array('LIKE',$filter_no_po)
			);
			$start=0;
			$limit=0;
			$column=array('id','no_po');
			$join=array();

			$results = $this->model_pembelian_pembeliankredit->getPermintaanPembelians($column,$join,$join,$data,array(),$limit,$start);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['no_po'],
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

			$this->load->model('pembelian/pembeliankredit');
			if(!empty($this->request->get['jenisbarang'])){
				$products=$this->model_pembelian_pembeliankredit->getPoTanpaInvoice($this->request->get['vendor_id'],$this->request->get['jenisbarang'],$this->request->get['gudang_id']);
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
