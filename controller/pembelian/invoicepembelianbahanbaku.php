<?php
class ControllerPembelianInvoicepembelianbahanbaku extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Invoice Pembelian Bahan Baku');

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

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('pembelian/invoicepembelianbahanbaku/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

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
		$this->load->model('pembelian/invoicepembelianbahanbaku');

		$this->data['permintaans'] = array();
		$column=array('invoice_pembelianbahanbaku.*','vendorlokal.name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'invoice_pembelianbahanbaku.vendor_id'
		);

		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'secondtable'	=>'gudang.gudang_id',
			'firsttable'	=> 'invoice_pembelianbahanbaku.gudang_id'
		);

		$data = array(
			//'no_po'      =>array('LIKE',$filter_no_po),
			'invoice_pembelianbahanbaku.id'      =>$filter_no_faktur,
			'vendor_id'=> $filter_vendor,
			//'surat_id'=> $filter_no_surat,
			'invoice_pembelianbahanbaku.status'	=> empty($filter_status)?array('>=',1):$filter_status,

		);

		if(!empty($filter_date_end)){
			$data['invoice_pembelianbahanbaku.tglfaktur']=array('>=',$filter_date_start,'<=',$filter_date_end);
		}else{
			$data['invoice_pembelianbahanbaku.tglfaktur']=array('>=',$filter_date_start);
		}

		if($filter_date_start == '1970-01-01'){
			$filter_date_start=null;
		}


		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'invoice_pembelianbahanbaku.tglfaktur'	=> 'DESC',
			'invoice_pembelianbahanbaku.id'	=> 'DESC',
			'invoice_pembelianbahanbaku.status'	=> 'ASC'
		);

		$this->load->model('catalog/gudang');

		$product_total = $this->model_pembelian_invoicepembelianbahanbaku->totalPermintaans($data);

		$results = $this->model_pembelian_invoicepembelianbahanbaku->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();
			if($result['status'] == 5){
				$action[] = array(
					'text' => 'Setujui Perubahan Harga',
					'href' => $this->url->link('pembelian/invoicepembelianbahanbaku/setujui', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
				$action[] = array(
					'text' => 'Tolak Perubahan Harga',
					'href' => $this->url->link('pembelian/invoicepembelianbahanbaku/tolak', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}
		if($result['status'] == 1 & $result['statuspenerimaan'] == 0 /*& $result['inputpib'] != 1 & $result['statuspembayaranpib'] != 1 & $result['inputkursdatang'] != 1*/){
				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('pembelian/invoicepembelianbahanbaku/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}
			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('pembelian/invoicepembelianbahanbaku/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);



			$this->data['permintaans'][] = array(
				'name'	=> $result['name'],
				'no_faktur'	=> $result['no_faktur'],
				'id'	=> $result['id'],
				'sub_total'	=> $this->currency->format($result['sub_total']),
				'diskon'	=> $this->currency->format($result['diskon']),
				'pajak'	=> $this->currency->format($result['pajak']),
				'total'	=> $this->currency->format($result['total']),
				'totalbayar'	=> $this->currency->format($result['totalbayar']),
				'tanggal'	=> date('d/m/y',strtotime($result['tglfaktur'])),
				'jatuhtempo'	=> date('d/m/y',strtotime($result['jatuhtempo'])),
				'tgllunas'	=> date('d/m/y',strtotime($result['tgllunas'])),
				'status'	=> $result['status'] == 1?'Ditagih':($result['status'] == 2?'Dibayar Sebagian':($result['status'] == 3?'Dibatalkan':($result['status'] == 5?'Permintaan Perubahan Harga':'Lunas'))),
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Invoice Pembelian Bahan Baku';

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
		$pagination->url = $this->url->link('pembelian/invoicepembelianbahanbaku', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_faktur'] = $filter_no_faktur;
		$this->data['filter_no_po'] = $filter_no_po;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_vendor'] = $filter_vendor;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->template = 'pembelian/invoicepembelianbahanbaku.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function setujui(){
		$this->load->model('pembelian/invoicepembelianbahanbaku');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
			$status=$this->model_pembelian_invoicepembelianbahanbaku->getPermintaanPembelian(array(),array(),array('id'  => $this->request->get['id']));
			if($status['status'] == 5){
	      $this->model_pembelian_invoicepembelianbahanbaku->setujuiPerubahanHarga($this->request->get['id']);

				$this->session->data['success'] = 'Sukses: perubahan harga berhasil disetujui.';
			}
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

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

			$this->redirect($this->url->link('pembelian/invoicepembelianbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function tolak(){
		$this->load->model('pembelian/invoicepembelianbahanbaku');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
			$status=$this->model_pembelian_invoicepembelianbahanbaku->getPermintaanPembelian(array(),array(),array('id'  => $this->request->get['id']));
			if($status['status'] == 5){
	      $this->model_pembelian_invoicepembelianbahanbaku->batalInvoice(array('id'	=> $this->request->get['id']));

				$this->session->data['success'] = 'Sukses: perubahan harga berhasil ditolak.';
			}
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
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

			$this->redirect($this->url->link('pembelian/invoicepembelianbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Invoice Pembelian Produk Dagang');

		$this->load->model('pembelian/invoicepembelianbahanbaku');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_po=$this->model_pembelian_invoicepembelianbahanbaku->addPenjualan($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Invoice pembelian bahan baku berhasil disimpan ';

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
			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('pembelian/invoicepembelianbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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




		$this->data['cancel']= $this->url->link('pembelian/invoicepembelianbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/invoicepembelianbahanbaku/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = array();
		}

		$this->template = 'pembelian/invoicepembelianbahanbaku_form.tpl';
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
		$this->document->setTitle('Invoice Pembelian Bahan Baku');
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
				$this->redirect($this->url->link('pembelian/invoicepembelianbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/invoicepembelianbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/invoicepembelianbahanbaku');
		$this->load->model('pembelian/pembeliankreditdagang');

		$column=array('invoice_pembelianbahanbaku.*','vendorlokal.name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'invoice_pembelianbahanbaku.vendor_id'
		);

		$data = array(
			'invoice_pembelianbahanbaku.id'	=> $id,
      'invoice_pembelianbahanbaku.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_invoicepembelianbahanbaku->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_pembelian_invoicepembelianbahanbaku->getPermintaanPembelianProduct(array('invoice_id'	=> $id));
		//print_r($prods);
		//$biayas=$this->model_pembelian_invoicepembelianbahanbaku->getPermintaanPembelianBiaya(array('order_id'	=> $id));
		$pembayarans=$this->model_pembelian_invoicepembelianbahanbaku->getPermintaanPembelianPembayaran(array('invoice_id'=>$id));

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['biayas']=$biayas;
		$this->data['pembayarans']=$pembayarans;

		$this->data['cancel']= $this->url->link('pembelian/invoicepembelianbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/invoicepembelianbahanbaku_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function batalkan(){
		$this->load->model('pembelian/invoicepembelianbahanbaku');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_pembelian_invoicepembelianbahanbaku->batalInvoice(array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Pembelian Bahan Baku berhasil dibatalkan.';
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
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

			$this->redirect($this->url->link('pembelian/invoicepembelianbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	public function autocomplete(){
		$rests = array();

		$this->load->model('pembelian/invoicepembelianbahanbaku');

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

			$results = $this->model_pembelian_invoicepembelianbahanbaku->getPermintaanPembelians($column,$join,$join,$data,array(),$limit,$start);
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

		$this->load->model('pembelian/invoicepembelianbahanbaku');

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
				'vendor_id'	=> $filter_faktur,
				'status'	=> array('<',3)
			);

			$start=0;
			$limit=0;
			$column=array('id','no_faktur','totaltagihan');
			$join=array();
			$leftjoin=array();

			$results = $this->model_pembelian_invoicepembelianbahanbaku->getPermintaanPembelians($column,$join,$join,$data,array(),$limit,$start);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					//'text'	=> $r['no_po'].' Total $'.number_format($r['total_pembelian'])
					'text'	=> $r['no_faktur']
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
			$this->load->model('pembelian/invoicepembelianbahanbaku');
			$column=array('invoice_pembelianbahanbaku.*');
			$join=array();


			$data = array(
				'invoice_pembelianbahanbaku.id'	=> $id,
	      'invoice_pembelianbahanbaku.hapus'	=> 0,
				'invoice_pembelianbahanbaku.status'	=> array('<',3)
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);

			$hasil=$this->model_pembelian_invoicepembelianbahanbaku->getPermintaanPembelian($column,$join,$data);
		}
	}
		$this->response->setOutput(json_encode($hasil));


	}

	

	public function detailbiaya(){
		$hasil = array();


		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$jenisbiaya_id=$this->request->get['id'];
				$order_id=$this->request->get['invoice_id'];
			$this->load->model('pembelian/invoicepembeliankredit');
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

			$hasil=$this->model_pembelian_invoicepembeliankredit->getBiaya($data);
		}
	}
		$this->response->setOutput(json_encode($hasil));


	}


}
?>
