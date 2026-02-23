<?php
class ControllerProduksiPermintaanproduksi extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Permintaan Produksi');

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
		$this->load->model('produksi/bukaproduksi');
		$this->data['cek']=$this->model_produksi_bukaproduksi->getPermintaanPembelian(array(),array(),array('status' => 1));

		$this->data['insert'] = $this->url->link('produksi/permintaanproduksi/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

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
		$this->load->model('produksi/permintaanproduksi');
		$this->load->model('catalog/gudang');
		*/

		$this->load->model('user/divisi');
		$this->load->model('produksi/permintaanproduksi');
		$this->load->model('sale/salesorder');
		$this->load->model('sale/customer');
		$divisis = $this->model_user_divisi->getDivisis();
		$this->data['divisis']=$divisis;



		$this->data['permintaans'] = array();
		$column=array('permintaan_produksi.id','permintaan_produksi.no_surat','permintaan_produksi.keterangan','permintaan_produksi.jenis_produksi','divisi.name','permintaan_produksi.date_added','permintaan_produksi.status','gudang.nama','product.name as product_name','permintaan_produksi_product.quantity','permintaan_produksi_product.quantity_proses');
		$join=array();
		$join[]=array(
			'tablename'	=> 'divisi',
			'firsttable'	=>'permintaan_produksi.asal',
			'secondtable'	=> 'divisi.id'
		);
		$join[]=array(
			'tablename'	=> 'permintaan_produksi_product',
			'firsttable'	=>'permintaan_produksi.id',
			'secondtable'	=> 'permintaan_produksi_product.surat_id'
		);
		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=>'permintaan_produksi.gudang_id',
			'secondtable'	=> 'gudang.gudang_id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'product',
			'firsttable'	=>'permintaan_produksi_product.product_id',
			'secondtable'	=> 'product.product_id'
		);

		$data = array(
			'no_surat'      =>array('LIKE',$filter_no_surat),
			'asal'=> $filter_divisi,
			'jenis_produksi'	=> $filter_jenis_pembelian,
			'permintaan_produksi.status'			=> $filter_status,
			'permintaan_produksi.gudang_id'			=> $filter_gudang_id,
			'permintaan_produksi.hapus'	=> 0,
			'date(permintaan_produksi.date_added)'	=> $filter_tanggal,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'permintaan_produksi.id'	=> 'DESC',
			'permintaan_produksi.status'	=> 'ASC'
		);

		$product_total = $this->model_produksi_permintaanproduksi->totalPermintaans($data);

		$results = $this->model_produksi_permintaanproduksi->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('produksi/permintaanproduksi/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
		/*	$action[] = array(
				'text' => 'Cetak',
				'href' => $this->url->link('produksi/permintaanproduksi/cetak', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);*/

			if($result['status'] == 1){


				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('produksi/permintaanproduksi/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);

			}
			/*if($result['status'] == 1 | $result['status'] == 4){
				if(!empty($this->data['cek'])){
					$action[] = array(
						'text' => 'Proses Produksi',
						'href' => $this->url->link('produksi/permintaanproduksi/proses', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
					);
				}
			}*/

			$trans=array();

			if($result['jenis_produksi'] == 1){
				$det=$this->model_sale_salesorder->getPenjualanProduct(array('jenisref'=>2,'referensi'=>$result['id']));
				if(empty($det)){
					$column=array('sales_order.no_so','customer.name','customer.telephone','customer.email',"gudang.nama");
					$join=array();
					$join[]=array(
						'tablename'	=> 'customer',
						'firsttable'	=> 'sales_order.customer_id',
						'secondtable'	=> 'customer.customer_id',
					);
					$join[]=array(
						'tablename'	=> 'gudang',
						'firsttable'	=> 'sales_order.gudang_id',
						'secondtable'	=> 'gudang.gudang_id',
					);

					$wheredata = array(
						'sales_order.id'	=> $order_id,

					);
					$trans=$this->model_sale_salesorder->getPenjualanDetail($column,$join,$wheredata,array());

					$trans['no_tttk']="Tanpa TTTK";
					if(!empty($trans['tttk'])){
						$tttk=$this->model_sale_tttkmr->getPenjualan(array('id'=>$trans['tttk']));
						$trans['no_tttk']=$tttk['no_so'];
					}
				}
			}

			$this->data['permintaans'][] = array(
				'name'	=> $result['name'],
				'gudang'	=> $result['nama'],
				'keterangan'	=> $result['keterangan'],
				'tanggal'	=> date('d/m/y',strtotime($result['date_added'])),
				'no_surat'	=> $result['no_surat'],
				'jenis_produksi'	=> $result['jenis_produksi'],
				'status'	=> $result['status'],
				'trans'	=> $trans,
				'product_name'	=> $result['product_name'],
				'quantity'	=> $result['quantity'],
				'quantity_proses'=> $result['quantity_proses'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Permintaan Produksi';

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
		$pagination->url = $this->url->link('produksi/permintaanproduksi', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_jenis_pembelian'] = $filter_jenis_pembelian;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_no_surat'] = $filter_no_surat;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_tanggal'] = $filter_tanggal;

		$this->template = 'produksi/permintaanproduksi.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Permintaan Produksi');

		$this->load->model('produksi/permintaanproduksi');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_surat=$this->model_produksi_permintaanproduksi->addPermintaanPembelian($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Permintaan Produksi berhasil disimpan dengan nomor surat '.$no_surat;

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

			$this->redirect($this->url->link('produksi/permintaanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
		$this->data['cancel']= $this->url->link('produksi/permintaanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('produksi/permintaanproduksi/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->template = 'produksi/permintaanproduksi_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {
    	/*if (!$this->user->hasPermission('modify', 'produksi/permintaanproduksi')) {
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
		$this->document->setTitle('Permintaan Produksi');
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
				$this->redirect($this->url->link('produksi/permintaanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('produksi/permintaanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('user/divisi');
		$this->load->model('catalog/gudang');
		$this->load->model('produksi/permintaanproduksi');

		$column=array('permintaan_produksi.id','permintaan_produksi.no_surat','permintaan_produksi.keterangan','permintaan_produksi.jenis_produksi','divisi.name','permintaan_produksi.date_added','permintaan_produksi.status','permintaan_produksi.gudang_id');
		$join=array();
		$join[]=array(
			'tablename'	=> 'divisi',
			'firsttable'	=>'permintaan_produksi.asal',
			'secondtable'	=> 'divisi.id'
		);

		$data = array(
			'permintaan_produksi.id'      =>$id,

		);

		$trans=$this->model_produksi_permintaanproduksi->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_produksi_permintaanproduksi->getPermintaanPembelianProduct(array('surat_id'	=> $id));
		$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
		$trans['detailcust']=array();
	//	print_r($prods);

		$this->data['products']=$prods;
		$this->data['gudang']=$gudang;
		$this->data['cancel']= $this->url->link('produksi/permintaanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->load->model('sale/salesorder');
		$this->load->model('sale/tttkmr');

		$detailcust=array();

		if($trans['jenis_produksi'] == 1){
			$det=$this->model_sale_salesorder->getPenjualanProduct(array('jenisref'=>2,'referensi'=>$id));
			if(!empty($det)){
				$column=array('sales_order.no_so','customer.name','customer.telephone','customer.email',"gudang.nama");
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'sales_order.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);
				$join[]=array(
					'tablename'	=> 'gudang',
					'firsttable'	=> 'sales_order.gudang_id',
					'secondtable'	=> 'gudang.gudang_id',
				);

				$wheredata = array(
					'sales_order.id'	=> $det['sales_order_id'],

				);
				$detailcust=$this->model_sale_salesorder->getPenjualanDetail($column,$join,$wheredata,array());

				$detailcust['no_tttk']="Tanpa TTTK";
				if(!empty($trans['tttk'])){
					$tttk=$this->model_sale_tttkmr->getPenjualan(array('id'=>$trans['tttk']));
					$detailcust['no_tttk']=$tttk['no_so'];
				}
			}



		}
		//detailprosesproduksi
		$this->load->model('produksi/prosesproduksi');
		$detail=$this->model_produksi_prosesproduksi->getPermintaanPembelians(array(),array(),array(),$where=array('permintaan'=>$trans['id']));
		//print_r($detail);
		$trans['detailcust']=$detailcust;
		$trans['detailproses'] = $detail;
		$this->data['permintaan']=$trans;

		$this->template = 'produksi/permintaanproduksi_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}



	public function cetak(){
		$this->document->setTitle('Permintaan Produksi');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('produksi/permintaanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('produksi/permintaanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('user/divisi');
		$this->load->model('catalog/gudang');
		$this->load->model('produksi/permintaanproduksi');

		$column=array('permintaan_produksi.id','permintaan_produksi.no_surat','permintaan_produksi.tujuan_pembelian','permintaan_produksi.jenis_pembelian','permintaan_produksi.jenis_barang','divisi.name','permintaan_produksi.date_added','permintaan_produksi.status');
		$join=array();
		$join[]=array(
			'tablename'	=> 'divisi',
			'firsttable'	=>'permintaan_produksi.divisi_asal',
			'secondtable'	=> 'divisi.id'
		);

		$data = array(
			'permintaan_produksi.id'      =>$id,

		);

		$trans=$this->model_produksi_permintaanproduksi->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_produksi_permintaanproduksi->getPermintaanPembelianProduct(array('surat_id'	=> $id));
		$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
	//	print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['gudang']=$gudang;

		$this->template = 'produksi/permintaanproduksi_cetak.tpl';
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
				$this->redirect($this->url->link('produksi/permintaanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('produksi/permintaanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('user/divisi');
		$this->load->model('catalog/gudang');
		$this->load->model('produksi/permintaanproduksi');

		$column=array('permintaan_produksi.id','permintaan_produksi.no_surat','permintaan_produksi.tujuan_pembelian','permintaan_produksi.jenis_pembelian','permintaan_produksi.jenis_barang','divisi.name','permintaan_produksi.date_added','permintaan_produksi.status');
		$join=array();
		$join[]=array(
			'tablename'	=> 'divisi',
			'firsttable'	=>'permintaan_produksi.divisi_asal',
			'secondtable'	=> 'divisi.id'
		);

		$data = array(
			'permintaan_produksi.id'      =>$id,

		);

		$trans=$this->model_produksi_permintaanproduksi->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_produksi_permintaanproduksi->getPermintaanPembelianProduct(array('surat_id'	=> $id));
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
		$this->load->model('produksi/permintaanproduksi');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_produksi_permintaanproduksi->updatePermintaan(array('status' => 3),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Permintaan Produksi berhasil dibatalkan.';
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

			$this->redirect($this->url->link('produksi/permintaanproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('produksi/permintaanproduksi');

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

			$results = $this->model_produksi_permintaanproduksi->getPermintaanPembelians($column,$join,array(),$data,array(),$limit,$start);
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

		$this->load->model('produksi/permintaanproduksi');
		$this->load->model('catalog/gudang');
		if(isset($this->request->get['surat_id'])){
			if(!empty($this->request->get['surat_id'])){
				$column=array();
				$surat_id=$this->request->get['surat_id'];
				$data = array(
					'id'      =>$surat_id,
					'status'	=> 2
				);

				$trans=$this->model_produksi_permintaanproduksi->getPermintaanPembelian($column,array(),$data);
				$prods=$this->model_produksi_permintaanproduksi->getPermintaanPembelianProduct(array('surat_id'	=> $surat_id));
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
