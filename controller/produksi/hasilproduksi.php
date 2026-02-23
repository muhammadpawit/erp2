<?php
class ControllerProduksiHasilproduksi extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Hasil Produksi');

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

		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}

		if (isset($this->request->get['filter_jenis_pembelian'])) {
			$filter_jenis_pembelian = $this->request->get['filter_jenis_pembelian'];
		} else {
			$filter_jenis_pembelian = null;
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

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_jenis_pembelian'])) {
			$url .= '&filter_jenis_pembelian=' . $this->request->get['filter_jenis_pembelian'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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
		$this->load->model('produksi/permintaanproduksi');
		$this->load->model('catalog/gudang');
		*/

		$this->load->model('user/divisi');
		$this->load->model('produksi/permintaanproduksi');
		$this->load->model('produksi/prosesproduksi');
		$this->load->model('sale/customer');
		$this->load->model('sale/salesordermr');
		$divisis = $this->model_user_divisi->getDivisis();
		$this->data['divisis']=$divisis;



		$this->data['permintaans'] = array();
		$column=array('proses_produksi.*','permintaan_produksi.no_surat','product.name namaproduk','product_options.name as ukuran','gudang.nama');
		$join=array();
		/*$join[]=array(
			'tablename'	=> 'divisi',
			'firsttable'	=>'proses_produksi.asal',
			'secondtable'	=> 'divisi.id'
		);*/
		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=>'proses_produksi.gudang_id',
			'secondtable'	=> 'gudang.gudang_id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'product',
			'firsttable'	=>'proses_produksi.product_id',
			'secondtable'	=> 'product.product_id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'product_options',
			'firsttable'	=>'proses_produksi.ukuran_tabung',
			'secondtable'	=> 'product_options.product_options_id'
		);

		$leftjoin[]=array(
			'tablename'	=> 'permintaan_produksi',
			'firsttable'	=>'proses_produksi.permintaan',
			'secondtable'	=> 'permintaan_produksi.id'
		);

		$data = array(
			//'no_surat'      =>array('LIKE',$filter_no_surat),
			//'asal'=> $filter_divisi,
			'jenis_produksi'	=> $filter_jenis_pembelian,
			//'proses_produksi.status'			=> $filter_status,
			'proses_produksi.gudang_id'			=> $filter_gudang_id,
			//'proses_produksi.hapus'	=> 0,
			'date(proses_produksi.tanggal)'	=> $filter_tanggal,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'proses_produksi.id'	=> 'DESC',
			//'permintaan_produksi.status'	=> 'ASC'
		);

		$product_total = $this->model_produksi_prosesproduksi->totalPermintaans($data);

		$results = $this->model_produksi_prosesproduksi->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('produksi/hasilproduksi/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);

			/*if($result['status'] == 1){


				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('produksi/permintaanproduksi/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);

			}*/


			$trans=array();

			if($result['jenis_produksi'] == 1){
				$det=$this->model_sale_salesordermr->getPenjualanProduct(array('jenisref'=>2,'referensi'=>$result['id']));
				if(empty($det)){
					$column=array('sales_ordermr.no_so','customer.name','customer.telephone','customer.email',"gudang.nama");
					$join=array();
					$join[]=array(
						'tablename'	=> 'customer',
						'firsttable'	=> 'sales_ordermr.customer_id',
						'secondtable'	=> 'customer.customer_id',
					);
					$join[]=array(
						'tablename'	=> 'gudang',
						'firsttable'	=> 'sales_ordermr.gudang_id',
						'secondtable'	=> 'gudang.gudang_id',
					);

					$wheredata = array(
						'sales_ordermr.id'	=> $order_id,

					);
					$trans=$this->model_sale_salesordermr->getPenjualanDetail($column,$join,$wheredata,array());

					$trans['no_tttk']="Tanpa TTTK";
					if(!empty($trans['tttk'])){
						$tttk=$this->model_sale_tttkmr->getPenjualan(array('id'=>$trans['tttk']));
						$trans['no_tttk']=$tttk['no_so'];
					}
				}
			}

			$this->data['permintaans'][] = array(
				'name'	=> $result['name'],
				'namaproduk'	=> $result['namaproduk'],
				'ukuran'	=> $result['ukuran'],
				'urlpermintaan'	=> $this->url->link('produksi/permintaanproduksi/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['permintaan'].$url, 'SSL'),
				'permintaan'	=> $result['no_surat'],
				'gudang'	=> $result['nama'],
				'keterangan'	=> $result['keterangan'],
				'tanggal'	=> date('d/m/y',strtotime($result['tanggal'])),
				'waktumulai'	=> date('H:i:s',strtotime($result['waktumulai'])),
				'waktuselesai'	=> date('H:i:s',strtotime($result['waktuselesai'])),
				'jenis_produksi'	=> $result['jenis_produksi'],
				'quantityproses'	=> $result['quantityproses'],
				'quantityhasil'	=> $result['quantityhasil'],
				//'status'	=> $result['status'],
				'trans'	=> $trans,
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Hasil Produksi';

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
		$pagination->url = $this->url->link('produksi/hasilproduksi', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_jenis_pembelian'] = $filter_jenis_pembelian;
		//$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		//$this->data['filter_no_surat'] = $filter_no_surat;
		//$this->data['filter_status'] = $filter_status;
		$this->data['filter_tanggal'] = $filter_tanggal;

		$this->template = 'produksi/hasilproduksi.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function tampil(){
		$this->document->setTitle('Hasil Produksi');
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
				$this->redirect($this->url->link('produksi/hasilproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('produksi/hasilproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('user/divisi');
		$this->load->model('catalog/gudang');
		$this->load->model('catalog/product');
		$this->load->model('catalog/options');
		$this->load->model('catalog/tabungmp');
		$this->load->model('produksi/prosesproduksi');

		$column=array('proses_produksi.*');
		$join=array();


		$data = array(
			'proses_produksi.id'      =>$id,

		);

		$trans=$this->model_produksi_prosesproduksi->getPermintaanPembelian($column,$join,$data);
		$tabung=$this->model_produksi_prosesproduksi->getPermintaanPembelianTabung(array('produksi_id'	=> $id));
		$this->data['tabungs']=array();
		$bahanbaku=$this->model_produksi_prosesproduksi->getPermintaanPembelianBahanbaku(array('produksi_id'	=> $id));

		$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
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
	//	print_r($prods);
		$this->data['bahanbaku']=$bahanbaku;
		$this->data['products']=$this->model_catalog_product->getProduct($trans['product_id']);
		$this->data['gudang']=$gudang;
		$this->data['cancel']= $this->url->link('produksi/hasilproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->load->model('sale/salesordermr');
		$this->load->model('sale/tttkmr');

		$detailcust=array();

		/*if($trans['jenis_produksi'] == 1){
			$det=$this->model_sale_salesordermr->getPenjualanProduct(array('jenisref'=>2,'referensi'=>$id));
			if(!empty($det)){
				$column=array('sales_ordermr.no_so','customer.name','customer.telephone','customer.email',"gudang.nama");
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'sales_ordermr.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);
				$join[]=array(
					'tablename'	=> 'gudang',
					'firsttable'	=> 'sales_ordermr.gudang_id',
					'secondtable'	=> 'gudang.gudang_id',
				);

				$wheredata = array(
					'sales_ordermr.id'	=> $order_id,

				);
				$detailcust=$this->model_sale_salesordermr->getPenjualanDetail($column,$join,$wheredata,array());

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
		$trans['detailproses'] = $detail;*/
		$this->data['permintaan']=$trans;

		$this->template = 'produksi/hasilproduksi_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}




}
?>
