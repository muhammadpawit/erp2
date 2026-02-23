<?php
class ControllerProduksiPenggembosanproduksi extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Penggembosan Produksi');

		if (isset($this->request->get['filter_no_surat'])) {
			$filter_no_surat = $this->request->get['filter_no_surat'];
		} else {
			$filter_no_surat = '';
		}

		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = '';
		}

		if (isset($this->request->get['filter_jenis_pembelian'])) {
			$filter_jenis_pembelian = $this->request->get['filter_jenis_pembelian'];
		} else {
			$filter_jenis_pembelian = null;
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}

		if (isset($this->request->get['filter_divisi'])) {
			$filter_divisi = $this->request->get['filter_divisi'];
		} else {
			$filter_divisi = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}

		if (isset($this->request->get['filter_jenis_pembelian'])) {
			$url .= '&filter_jenis_pembelian=' . $this->request->get['filter_jenis_pembelian'];
		}

		if (isset($this->request->get['filter_divisi'])) {
			$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('produksi/penggembosanproduksi/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

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
		$this->load->model('produksi/penggembosanproduksi');
		$this->load->model('catalog/gudang');
		*/

		$this->load->model('user/divisi');
		$this->load->model('produksi/penggembosanproduksi');
		$this->load->model('sale/salesordermr');
		$this->load->model('sale/customer');
		$divisis = $this->model_user_divisi->getDivisis();
		$this->data['divisis']=$divisis;



		$this->data['permintaans'] = array();
		$column=array('penggembosan_produksi.id','penggembosan_produksi.no_surat','penggembosan_produksi.keterangan','penggembosan_produksi.jenis_produksi','divisi.name','penggembosan_produksi.date_added','penggembosan_produksi.status','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'divisi',
			'firsttable'	=>'penggembosan_produksi.asal',
			'secondtable'	=> 'divisi.id'
		);
		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=>'penggembosan_produksi.gudang_id',
			'secondtable'	=> 'gudang.gudang_id'
		);

		$data = array(
			'no_surat'      =>array('LIKE',$filter_no_surat),
			'asal'=> $filter_divisi,
			'jenis_produksi'	=> $filter_jenis_pembelian,
			'penggembosan_produksi.status'			=> $filter_status,
			'penggembosan_produksi.gudang_id'			=> $filter_gudang_id,
			//'penggembosan_produksi.hapus'	=> 0,
			'date(penggembosan_produksi.date_added)'	=> $filter_tanggal,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'penggembosan_produksi.id'	=> 'DESC',
			'penggembosan_produksi.status'	=> 'ASC'
		);

		$product_total = $this->model_produksi_penggembosanproduksi->totalPermintaans($data);

		$results = $this->model_produksi_penggembosanproduksi->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('produksi/penggembosanproduksi/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			/*$action[] = array(
				'text' => 'Cetak',
				'href' => $this->url->link('produksi/penggembosanproduksi/cetak', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			/*if($result['status'] == 2 & $result['jenis_pembelian'] == 2){
				$action[] = array(
					'text' => 'SPPH',
					'href' => $this->url->link('produksi/penggembosanproduksi/spph', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}
			if($result['status'] == 1){


				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('produksi/penggembosanproduksi/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);

			}*/
			/*if($result['status'] == 1 | $result['status'] == 4){
				$action[] = array(
					'text' => 'Proses Produksi',
					'href' => $this->url->link('produksi/penggembosanproduksi/proses', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}*/

			$trans=array();


			$this->data['permintaans'][] = array(
				'name'	=> $result['name'],
				'gudang'	=> $result['nama'],
				'keterangan'	=> $result['keterangan'],
				'tanggal'	=> date('d/m/y',strtotime($result['date_added'])),
				'no_surat'	=> $result['no_surat'],
				'jenis_produksi'	=> $result['jenis_produksi'],
				'status'	=> $result['status'],
				//'trans'	=> $trans,
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Penggembosan Produksi';

		$this->data['token'] = $this->session->data['token'];
		$url = '';

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}

		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
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
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('produksi/penggembosanproduksi', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_jenis_pembelian'] = $filter_jenis_pembelian;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_no_surat'] = $filter_no_surat;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_tanggal'] = $filter_tanggal;

		$this->template = 'produksi/penggembosanproduksi.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Penggembosan Produksi');

		$this->load->model('produksi/penggembosanproduksi');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_surat=$this->model_produksi_penggembosanproduksi->addPermintaanPembelian($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Penggembosan Produksi berhasil disimpan dengan nomor surat '.$no_surat;

			$url = '';

			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
			}

			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
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
			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('produksi/penggembosanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('user/divisi');
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

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
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

		$gudangs = $this->model_user_divisi->getDivisis(true);
		$this->data['divisis']=$gudangs;

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->post['divisi_asal'])) {
			$this->data['divisi_asal'] = $this->request->post['divisi_asal'];
		}  else {
			$this->data['divisi_asal'] = '';
		}
		$this->data['cancel']= $this->url->link('produksi/penggembosanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('produksi/penggembosanproduksi/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->request->post['products'])) {
			$this->data['products'] = $this->request->post['products'];
		} else {
			$this->data['products'] = '';
		}

		$this->load->model('catalog/gudang');
		$gudangs = $this->model_catalog_gudang->getGudangs(true);
		$this->data['gudangs']=$gudangs;

		$this->template = 'produksi/penggembosanproduksi_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {
    	/*if (!$this->user->hasPermission('modify', 'produksi/penggembosanproduksi')) {
      		$this->error['warning'] = 'Permission Denied.';
    	}

    	/*if (empty($this->request->post['date_added'])) {
      		$this->error['warning'] = 'Tanggal input product cacat harus diisi';
    	}*/

		if (empty($this->request->post['product'])) {
		  		$this->error['warning'] = 'Produk tidak boleh kosong';
			}

    	if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function tampil(){
		$this->document->setTitle('Penggembosan Produksi');
		$url = '';

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}

		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
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
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('produksi/penggembosanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('produksi/penggembosanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('user/divisi');
		$this->load->model('catalog/gudang');
		$this->load->model('produksi/penggembosanproduksi');

		$column=array('penggembosan_produksi.id','penggembosan_produksi.no_surat','penggembosan_produksi.keterangan','penggembosan_produksi.jenis_produksi','divisi.name','penggembosan_produksi.date_added','penggembosan_produksi.status','penggembosan_produksi.gudang_id');
		$join=array();
		$join[]=array(
			'tablename'	=> 'divisi',
			'firsttable'	=>'penggembosan_produksi.asal',
			'secondtable'	=> 'divisi.id'
		);

		$data = array(
			'penggembosan_produksi.id'      =>$id,

		);

		$trans=$this->model_produksi_penggembosanproduksi->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_produksi_penggembosanproduksi->getPermintaanPembelianProduct(array('surat_id'	=> $id));
		$tabung=$this->model_produksi_penggembosanproduksi->getPermintaanPembelianTabung(array('produksi_id'	=> $id));
		$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);

		$this->load->model('catalog/product');
		$this->load->model('catalog/tabungmp');

		$trans['detailcust']=array();
		$this->data['tabungs']=array();
		if($trans['jenis_produksi'] == 2){
			foreach($tabung as $t){
				$tbg=$this->model_catalog_product->getProduct($t['tabung_id']);
				$this->data['tabungs'][]=array(
					'name'	=> $tbg['name'],
					'quantity'	=> $t['quantity']
				);
			}
		}
		if($trans['jenis_produksi'] == 3){
			foreach($tabung as $t){
				$tbg=$this->model_catalog_tabungmp->getTabung($t['tabung_id']);
				$this->data['tabungs'][]=array(
					'name'	=> $tbg['no_tabung'],
					'quantity'	=> $t['quantity']
				);
			}
		}
	//print_r($tbg);

		$this->data['products']=$prods;
		$this->data['gudang']=$gudang;
		$this->data['cancel']= $this->url->link('produksi/penggembosanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->load->model('sale/salesordermr');
		$this->load->model('sale/tttkmr');

		$detailcust=array();


		$this->data['permintaan']=$trans;

		$this->template = 'produksi/penggembosanproduksi_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function cetak(){
		$this->document->setTitle('Penggembosan Produksi');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('produksi/penggembosanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('produksi/penggembosanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('user/divisi');
		$this->load->model('catalog/gudang');
		$this->load->model('produksi/penggembosanproduksi');

		$column=array('penggembosan_produksi.id','penggembosan_produksi.no_surat','penggembosan_produksi.tujuan_pembelian','penggembosan_produksi.jenis_pembelian','penggembosan_produksi.jenis_barang','divisi.name','penggembosan_produksi.date_added','penggembosan_produksi.status');
		$join=array();
		$join[]=array(
			'tablename'	=> 'divisi',
			'firsttable'	=>'penggembosan_produksi.divisi_asal',
			'secondtable'	=> 'divisi.id'
		);

		$data = array(
			'penggembosan_produksi.id'      =>$id,

		);

		$trans=$this->model_produksi_penggembosanproduksi->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_produksi_penggembosanproduksi->getPermintaanPembelianProduct(array('surat_id'	=> $id));
		$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
	//	print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['gudang']=$gudang;

		$this->template = 'produksi/penggembosanproduksi_cetak.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function spph(){
		$this->document->setTitle('Permintaan Penawaran Harga');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('produksi/penggembosanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('produksi/penggembosanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('user/divisi');
		$this->load->model('catalog/gudang');
		$this->load->model('produksi/penggembosanproduksi');

		$column=array('penggembosan_produksi.id','penggembosan_produksi.no_surat','penggembosan_produksi.tujuan_pembelian','penggembosan_produksi.jenis_pembelian','penggembosan_produksi.jenis_barang','divisi.name','penggembosan_produksi.date_added','penggembosan_produksi.status');
		$join=array();
		$join[]=array(
			'tablename'	=> 'divisi',
			'firsttable'	=>'penggembosan_produksi.divisi_asal',
			'secondtable'	=> 'divisi.id'
		);

		$data = array(
			'penggembosan_produksi.id'      =>$id,

		);

		$trans=$this->model_produksi_penggembosanproduksi->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_produksi_penggembosanproduksi->getPermintaanPembelianProduct(array('surat_id'	=> $id));
		$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
	//	print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['gudang']=$gudang;

		$this->template = 'pembelian/penawaranharga.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function batalkan(){
		$this->load->model('produksi/penggembosanproduksi');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_produksi_penggembosanproduksi->updatePermintaan(array('status' => 3),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Penggembosan Produksi berhasil dibatalkan.';
			}
		}
			$url = '';

			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
			}

			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
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

			$this->redirect($this->url->link('produksi/penggembosanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('produksi/penggembosanproduksi');

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
				//'jenis_pembelian'	=> !mpty()$jenis_pembelian
			);
			$start=0;
			$limit=0;
			$column=array('id','no_surat');
			$join=array();

			$results = $this->model_produksi_penggembosanproduksi->getPermintaanPembelians($column,$join,array(),$data,array(),$limit,$start);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_surat']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function detail(){
		$hasil = array();

		$this->load->model('produksi/penggembosanproduksi');
		$this->load->model('catalog/gudang');
		if(isset($this->request->get['surat_id'])){
			if(!empty($this->request->get['surat_id'])){
				$column=array();
				$surat_id=$this->request->get['surat_id'];
				$data = array(
					'id'      =>$surat_id,
					'status'	=> 2
				);

				$trans=$this->model_produksi_penggembosanproduksi->getPermintaanPembelian($column,array(),$data);
				$prods=$this->model_produksi_penggembosanproduksi->getPermintaanPembelianProduct(array('surat_id'	=> $surat_id));
			//	print_r($prods);
				$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
				$hasil=array(
					'detail'	=> $trans,
					'products' => $prods,
					'gudang'	=> $gudang
				);

			}
		}
		$this->response->setOutput(json_encode($hasil));


	}


}
?>
