<?php
class ControllerPembelianPembayarandepositkredit extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Pembayaran Deposit Pembelian Lokal');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['filter_no_giro'])) {
			$filter_no_giro = $this->request->get['filter_no_giro'];
		} else {
			$filter_no_giro = null;
		}

		if (isset($this->request->get['filter_jenis'])) {
			$filter_jenis = $this->request->get['filter_jenis'];
		} else {
			$filter_jenis = '';
		}

		if (isset($this->request->get['filter_metode'])) {
			$filter_metode = $this->request->get['filter_metode'];
		} else {
			$filter_metode = '';
		}

		if (isset($this->request->get['filter_vendor_id'])) {
			$filter_vendor_id = $this->request->get['filter_vendor_id'];
		} else {
			$filter_vendor_id = '';
		}

		if (isset($this->request->get['filter_bank_id'])) {
			$filter_bank_id = $this->request->get['filter_bank_id'];
		} else {
			$filter_bank_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		if (isset($this->request->get['filter_vendor_id'])) {
			$url .= '&filter_vendor_id=' . $this->request->get['filter_vendor_id'];
		}
		if (isset($this->request->get['filter_bank_id'])) {
			$url .= '&filter_bank_id=' . $this->request->get['filter_bank_id'];
		}

		if (isset($this->request->get['filter_metode'])) {
			$url .= '&filter_metode=' . $this->request->get['filter_metode'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_no_giro'])) {
			$url .= '&filter_no_giro=' . $this->request->get['filter_no_giro'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('pembelian/pembayarandepositkredit/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		/*$this->load->model('report/product');
        $this->load->model('catalog/product');
		*/

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('pembelian/pembayarandepositkredit');
		//$this->load->model('catalog/title');
		//$this->load->model('sale/invoice');

		$this->data['permintaans'] = array();
		$column=array('pembayaran_deposit_lokal.*','banks.name as nama_bank','vendorlokal.name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'banks',
			'firsttable'	=>'pembayaran_deposit_lokal.bank_id',
			'secondtable'	=> 'banks.id'
		);
		$join[]=array(
			'tablename'	=> 'vendorlokal',
			'firsttable'	=>'pembayaran_deposit_lokal.vendor_id',
			'secondtable'	=> 'vendorlokal.id'
		);


		$data = array(
			'pembayaran_deposit_lokal.tgl_bayar'      =>!empty($filter_date_start)?array('>=',$filter_date_start,'<=',$filter_date_end):array('>','1901-01-01'),
			//'pembayaran_deposit_lokal.tgl_bayar'      =>!empty($filter_date_end)?array('<=',$filter_date_end):array('>','1901-01-01'),
			'pembayaran_deposit_lokal.status'	=> !empty($filter_status)?$filter_status:array('<=',3),
			'pembayaran_deposit_lokal.vendor_id'	=> !empty($filter_vendor_id)?$filter_vendor_id:array('>=',1),
			'pembayaran_deposit_lokal.jenis'	=> !empty($filter_jenis)?$filter_jenis:array('>=',1),
			'pembayaran_deposit_lokal.bank_id'	=> !empty($filter_bank_id)?$filter_bank_id:array('>=',1),
			'pembayaran_deposit_lokal.metode_pembayaran'	=> !empty($filter_metode)?$filter_metode:array('>=',1),
			'pembayaran_deposit_lokal.status'	=> !empty($filter_status)?$filter_status:array('>=',1),
			'pembayaran_deposit_lokal.hapus'	=> !empty($filter_status)?$filter_status:array('=',0),
			'pembayaran_deposit_lokal.no_giro'	=> $filter_no_giro != null ?array('LIKE',$filter_no_giro):'',
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'tgl_bayar'	=> 'DESC',

		);

		$product_total = $this->model_pembelian_pembayarandepositkredit->totalPermintaans($data);

		$results = $this->model_pembelian_pembayarandepositkredit->getPermintaanPembelians($column,$join,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();

			if($result['status'] != 3 & $result['totalalokasi'] == 0 & $result['biaya_lain']==0 & $result['pendapatan_lain']==0){
				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('pembelian/pembayarandepositkredit/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}
			$action[] = array(
					'text' => 'Tampil',
					'href' => $this->url->link('pembelian/pembayarandepositkredit/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);

			$action[] = array(
				'text' => 'Lihat Jurnal',
				'href' => $this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'] . '&filter_nodokumen=' . $result['no_pd'].$url, 'SSL')
			);
			//}

			$this->data['permintaans'][] = array(
				'nama_bank'	=> $result['nama_bank'],
				'id'	=> $result['id'],
				'jumlah'	=> $this->currency->format($result['nominal']),
				'totalalokasi'	=> $this->currency->format($result['totalalokasi']),
				'biaya'	=> $this->currency->format($result['biaya_lain']),
				'pendapatan'	=> $this->currency->format($result['pendapatan_lain']),
				'totalalokasi'	=> $this->currency->format($result['totalalokasi']),
				'tanggal'	=> date('d/m/y',strtotime($result['tgl_bayar'])),
				'tanggalditerima'	=> date('d/m/y',strtotime($result['tgl_diterima'])) == '01/01/70'?'Belum Diterima':date('d/m/y',strtotime($result['tgl_diterima'])),
				'status'	=> $result['status'],
				'keterangan'	=> $result['keterangan'],
				'jenis'	=> $result['jenis'],
				'no_giro'	=> $result['no_giro'],
				'metode_pembayaran'	=> $result['metode_pembayaran'],
				'vendor'	=> $result['name'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Pembayaran Deposit Pembelian Lokal';

		$this->data['token'] = $this->session->data['token'];
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		if (isset($this->request->get['filter_vendor_id'])) {
			$url .= '&filter_vendor_id=' . $this->request->get['filter_vendor_id'];
		}
		if (isset($this->request->get['filter_bank_id'])) {
			$url .= '&filter_bank_id=' . $this->request->get['filter_bank_id'];
		}

		if (isset($this->request->get['filter_metode'])) {
			$url .= '&filter_metode=' . $this->request->get['filter_metode'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_no_giro'])) {
			$url .= '&filter_no_giro=' . $this->request->get['filter_no_giro'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pembelian/pembayarandepositkredit', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_tgl_awal'] = $filter_date_start;
		$this->data['filter_tgl_akhir'] = $filter_date_end;
		$this->data['filter_jenis'] = $filter_jenis;

		$this->template = 'pembelian/pembayarandepositkredit.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Pembayaran Deposit Pembelian Kredit');

		$this->load->model('pembelian/pembayarandepositkredit');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if($this->user->getUsername()=="pawits"){
				echo "<pre>";print_r($this->request->post);exit;
			}
      		$no_po=$this->model_pembelian_pembayarandepositkredit->addPembelian($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Pembayaran Deposit Pembelian Lokal  berhasil disimpan';

			$url = '';

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
			if (isset($this->request->get['filter_jenis'])) {
				$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
			}
			if (isset($this->request->get['filter_vendor_id'])) {
				$url .= '&filter_vendor_id=' . $this->request->get['filter_vendor_id'];
			}
			if (isset($this->request->get['filter_bank_id'])) {
				$url .= '&filter_bank_id=' . $this->request->get['filter_bank_id'];
			}

			if (isset($this->request->get['filter_metode'])) {
				$url .= '&filter_metode=' . $this->request->get['filter_metode'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_no_giro'])) {
				$url .= '&filter_no_giro=' . $this->request->get['filter_no_giro'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('pembelian/pembayarandepositkredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		if (isset($this->request->get['filter_vendor_id'])) {
			$url .= '&filter_vendor_id=' . $this->request->get['filter_vendor_id'];
		}
		if (isset($this->request->get['filter_bank_id'])) {
			$url .= '&filter_bank_id=' . $this->request->get['filter_bank_id'];
		}

		if (isset($this->request->get['filter_metode'])) {
			$url .= '&filter_metode=' . $this->request->get['filter_metode'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_no_giro'])) {
			$url .= '&filter_no_giro=' . $this->request->get['filter_no_giro'];
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


		if (isset($this->request->post['order_id'])) {
			$this->data['order_id'] = $this->request->post['order_id'];
		}  else {
			$this->data['order_id'] = '';
		}

		if (isset($this->request->post['bank_id'])) {
			$this->data['bank_id'] = $this->request->post['bank_id'];
		}  else {
			$this->data['bank_id'] = '';
		}

		if (isset($this->request->post['nominal'])) {
			$this->data['nominal'] = $this->request->post['nominal'];
		}  else {
			$this->data['nominal'] = '0';
		}

	if (isset($this->request->post['ref'])) {
			$this->data['ref'] = $this->request->post['ref'];
		}  else {
			$this->data['ref'] = '';
		}

		if (isset($this->request->post['vendor_id'])) {
			$this->data['vendor_id'] = $this->request->post['vendor_id'];
		}  else {
			$this->data['vendor_id'] = '';
		}

		if (isset($this->request->post['tgl_bayar'])) {
			$this->data['tgl_bayar'] = $this->request->post['tgl_bayar'];
		}  else {
			$this->data['tgl_bayar'] = '';
		}

		if (isset($this->request->post['tgl_diterima'])) {
			$this->data['tgl_diterima'] = $this->request->post['tgl_diterima'];
		}  else {
			$this->data['tgl_diterima'] = '';
		}

		if (isset($this->request->post['jenis'])) {
			$this->data['jenis'] = $this->request->post['jenis'];
		}  else {
			$this->data['jenis'] = '';
		}

		if (isset($this->request->post['metode_pembayaran'])) {
			$this->data['metode_pembayaran'] = $this->request->post['metode_pembayaran'];
		}  else {
			$this->data['metode_pembayaran'] = '';
		}

		if (isset($this->request->post['biaya_bank'])) {
			$this->data['biaya_bank'] = $this->request->post['biaya_bank'];
		}  else {
			$this->data['biaya_bank'] = '0';
		}

		$this->data['cancel']= $this->url->link('pembelian/pembayarandepositkredit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/pembayarandepositkredit/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = array();
		}
        
         $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
        

		$this->template = 'pembelian/pembayarandepositkredit_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {

		if(!is_numeric($this->request->post['nominal']) ){
			$this->error['nominal'] = 'Jumlah Pembayaran Harus Berupa Angka';
		}
		//print_r($cek);
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}


	public function batalkan(){
		$this->load->model('pembelian/pembayarandepositkredit');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_pembelian_pembayarandepositkredit->updatePermintaan(array('status' => 3),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Pembayaran Deposit berhasil dibatalkan.';
			}
		}
			$url = '';

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
			if (isset($this->request->get['filter_jenis'])) {
				$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
			}
			if (isset($this->request->get['filter_vendor_id'])) {
				$url .= '&filter_vendor_id=' . $this->request->get['filter_vendor_id'];
			}
			if (isset($this->request->get['filter_bank_id'])) {
				$url .= '&filter_bank_id=' . $this->request->get['filter_bank_id'];
			}

			if (isset($this->request->get['filter_metode'])) {
				$url .= '&filter_metode=' . $this->request->get['filter_metode'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_no_giro'])) {
				$url .= '&filter_no_giro=' . $this->request->get['filter_no_giro'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('pembelian/pembayarandepositkredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function tampil(){
		$this->document->setTitle('Pembayaran Deposit Pembelian Kredit');
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		if (isset($this->request->get['filter_vendor_id'])) {
			$url .= '&filter_vendor_id=' . $this->request->get['filter_vendor_id'];
		}
		if (isset($this->request->get['filter_bank_id'])) {
			$url .= '&filter_bank_id=' . $this->request->get['filter_bank_id'];
		}

		if (isset($this->request->get['filter_metode'])) {
			$url .= '&filter_metode=' . $this->request->get['filter_metode'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_no_giro'])) {
			$url .= '&filter_no_giro=' . $this->request->get['filter_no_giro'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/pembayarandepositkredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/pembayarandepositkredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/pembayarandepositkredit');
		$this->load->model('pembelian/invoicepembeliandagang');
		$this->load->model('pembelian/invoicepembeliankreditnondagang');
		$this->load->model('pembelian/invoicepembelianbahanbaku');
		$this->load->model('catalog/vendorlokal');
		$this->load->model('keuangan/bank');

		$column=array('pembayaran_deposit_lokal.*','vendorlokal.name as cname','banks.name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'vendorlokal',
			'firsttable'	=>'pembayaran_deposit_lokal.vendor_id',
			'secondtable'	=> 'vendorlokal.id'
		);
		$join[]=array(
			'tablename'	=> 'banks',
			'firsttable'	=>'pembayaran_deposit_lokal.bank_id',
			'secondtable'	=> 'banks.id'
		);

		$data = array(
			'pembayaran_deposit_lokal.id'	=> $id,

		);

		$trans=$this->model_pembelian_pembayarandepositkredit->getPermintaanPembelian($column,$join,$data);
		$trans['vendor']=$trans['cname'];
		$joins[]=array(
			'tablename'	=> 'invoice_pembeliandagang',
			'firsttable'	=>'alokasi_deposit_kredit.invoice_id',
			'secondtable'	=> 'invoice_pembeliandagang.id'
		);
		$pembayaran=$this->model_pembelian_invoicepembeliandagang->getPermintaanPembelianPembayaran(array('deposit_id'=>$id,'alokasi_deposit_kredit.status'=>array('>=',1)),$joins,array('alokasi_deposit_kredit.*','invoice_pembeliandagang.no_faktur'));
		/*if(!empty($trans['ref'])){
			$trans['inv']=$this->model_sale_invoice->getPenjualan($trans['ref']);
			$trans['href']=$this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $trans['ref'], 'SSL');
		}*/
		if(empty($pembayaran)){
			$joins=array();
			$joins[]=array(
				'tablename'	=> 'invoice_pembeliannondagang',
				'firsttable'	=>'alokasi_deposit_kreditnondagang.invoice_id',
				'secondtable'	=> 'invoice_pembeliannondagang.id'
			);
			$pembayaran=$this->model_pembelian_invoicepembeliankreditnondagang->getPermintaanPembelianPembayaran(array('deposit_id'=>$id,'alokasi_deposit_kreditnondagang.status'=>array('>=',1)),$joins,array('alokasi_deposit_kreditnondagang.*','invoice_pembeliannondagang.no_faktur'));
			
		}

		if(empty($pembayaran)){
			$joins=array();
			$joins[]=array(
				'tablename'	=> 'invoice_pembelianbahanbaku',
				'firsttable'	=>'alokasi_deposit_bahanbaku.invoice_id',
				'secondtable'	=> 'invoice_pembelianbahanbaku.id'
			);
			$pembayaran=$this->model_pembelian_invoicepembelianbahanbaku->getPermintaanPembelianPembayaran(array('deposit_id'=>$id,'alokasi_deposit_bahanbaku.status'=>array('>=',1)),$joins,array('alokasi_deposit_bahanbaku.*','invoice_pembelianbahanbaku.no_faktur'));
		}

			$this->data['penerimaan']=$trans;
			$this->data['pembayarans']=$pembayaran;
		$this->data['cancel']= $this->url->link('pembelian/pembayarandepositkredit', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/pembayarandepositkredit_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function autocomplete() {
		$json = array();

		//if (isset($this->request->get['filter_name']) ) {

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = null;
			}


			$this->load->model('pembelian/pembayarandepositkredit');
			//$this->load->model('catalog/title');
			//$this->load->model('sale/invoice');




			$results = $this->model_pembelian_pembayarandepositkredit->getDepositTersedia($filter_name);


			foreach ($results as $result) {
				$dana=$result['nominal']-$result['totalalokasi'];
					$json[] = array(
						'id' => $result['id'],
						'text'       => date('d/m/y',strtotime($result['tgl_bayar'])).'- Tersedia '.$this->currency->format($dana),

					);

			}
		//}

		$this->response->setOutput(json_encode($json));
	}

	public function detail(){
		$hasil = array();


		$this->load->model('pembelian/pembayarandepositkredit');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$column=array();
				$id=$this->request->get['id'];
				$this->load->model('pembelian/pembayarandepositkredit');
				$this->load->model('catalog/vendorlokal');
				$this->load->model('keuangan/bank');

				$column=array('pembayaran_deposit_lokal.*','vendorlokal.name as cname');
				$join=array();
				$join[]=array(
					'tablename'	=> 'vendorlokal',
					'firsttable'	=>'pembayaran_deposit_lokal.vendor_id',
					'secondtable'	=> 'vendorlokal.id'
				);


				$data = array(
					'pembayaran_deposit_lokal.id'	=> $id,

				);

				$hasil=$this->model_pembelian_pembayarandepositkredit->getPermintaanPembelian($column,$join,$data);
				$hasil['deposit']=$hasil['nominal']-$hasil['totalalokasi'];
				$hasil['pdeposit']=$this->currency->format($hasil['nominal']-$hasil['totalalokasi']);



			}
		}
		$this->response->setOutput(json_encode($hasil));


	}

}
?>
