<?php
class ControllerPembelianPembayarandepositimport extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Pembayaran Deposit Pembelian Import');

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

		if (isset($this->request->get['filter_keterangan'])) {
			$filter_keterangan = $this->request->get['filter_keterangan'];
		} else {
			$filter_keterangan =null;
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

		$this->data['insert'] = $this->url->link('pembelian/pembayarandepositimport/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		/*$this->load->model('report/product');
        $this->load->model('catalog/product');
		*/

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('pembelian/pembayarandepositimport');
		//$this->load->model('catalog/title');
		//$this->load->model('sale/invoice');

		$this->data['permintaans'] = array();
		$column=array('pembayaran_deposit_import.*','banks.name as nama_bank','vendorimport.name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'banks',
			'firsttable'	=>'pembayaran_deposit_import.bank_id',
			'secondtable'	=> 'banks.id'
		);
		$join[]=array(
			'tablename'	=> 'vendorimport',
			'firsttable'	=>'pembayaran_deposit_import.vendor_id',
			'secondtable'	=> 'vendorimport.id'
		);
		/*$join[]=array(
			'tablename'	=> 'coamnb',
			'firsttable'	=>'biaya_operasional.coa_id',
			'secondtable'	=> 'coamnb.category_id'
		);*/
		$between=" ".$filter_date_start."' AND '".$filter_date_end." ";
		$data = array(
			'pembayaran_deposit_import.tgl_bayar'      =>!empty($filter_date_start)?array(' BETWEEN ',$between):array('>','1901-01-01'),
			//'pembayaran_deposit_import.tgl_bayar'      =>!empty($filter_date_end)?array('<',$filter_date_end):array('>','1901-01-01'),
			'pembayaran_deposit_import.status'	=> !empty($filter_status)?$filter_status:array('<=',3),
			'pembayaran_deposit_import.vendor_id'	=> !empty($filter_vendor_id)?$filter_vendor_id:array('>=',1),
			'pembayaran_deposit_import.jenis'	=> !empty($filter_jenis)?$filter_jenis:array('>=',1),
			'pembayaran_deposit_import.bank_id'	=> !empty($filter_bank_id)?$filter_bank_id:array('>=',1),
			'pembayaran_deposit_import.metode_pembayaran'	=> !empty($filter_metode)?$filter_metode:array('>=',1),
			'pembayaran_deposit_import.status'	=> !empty($filter_status)?$filter_status:array('>=',1),
			'pembayaran_deposit_import.no_giro'	=> $filter_no_giro != null ?array('LIKE',$filter_no_giro):'',
			'pembayaran_deposit_import.keterangan'	=> $filter_keterangan != null ?array('LIKE',$filter_keterangan):'',
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'tgl_bayar'	=> 'DESC',

		);

		$product_total = $this->model_pembelian_pembayarandepositimport->totalPermintaans($data);

		$results = $this->model_pembelian_pembayarandepositimport->getPermintaanPembelians($column,$join,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();

			if($result['status'] != 3){
			$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('pembelian/pembayarandepositimport/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}
			$action[] = array(
					'text' => 'Tampil',
					'href' => $this->url->link('pembelian/pembayarandepositimport/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			//}

			$action[] = array(
				'text' => 'Lihat Jurnal',
				'href' => $this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'] . '&filter_nodokumen=' . $result['no_pd'].$url, 'SSL')
			);

			$this->data['permintaans'][] = array(
				'nama_bank'	=> $result['nama_bank'],
				'id'	=> $result['id'],
				'jumlah'	=> '$'.number_format($result['nominal'],2,'.',','),
				'totalalokasi'	=> '$'.number_format($result['totalalokasi'],2,'.',','),
				'kurs'	=> $this->currency->format($result['kurs']),
				'biaya'	=> $this->currency->format($result['biaya_lain']),
				'pendapatan'	=> $this->currency->format($result['pendapatan_lain']),
				'totalrupiah'	=> $this->currency->format($result['kurs']*$result['nominal']),
				'tanggal'	=> date('d/m/y',strtotime($result['tgl_bayar'])),
				'tanggalditerima'	=> date('d/m/y',strtotime($result['tgl_diterima'])) == '01/01/70'?'Belum Diterima':date('d/m/y',strtotime($result['tgl_diterima'])),
				'status'	=> $result['status'],
				'keterangan'	=> $result['keterangan'],
				'jenis'	=> $result['jenis'],
				'no_giro'	=> $result['no_giro'],
				'no_cheque'	=> $result['no_cheque'],
				'no_kontrak'	=> $result['no_kontrak'],
				'metode_pembayaran'	=> $result['metode_pembayaran'],
				'vendor'	=> $result['name'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Pembayaran Deposit Pembelian Import';

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
		$pagination->url = $this->url->link('pembelian/pembayarandepositimport', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_tgl_awal'] = $filter_date_start;
		$this->data['filter_tgl_akhir'] = $filter_date_end;
		$this->data['filter_jenis'] = $filter_jenis;
		$this->data['filter_keterangan'] = $filter_keterangan;
		$this->template = 'pembelian/pembayarandepositimport.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Pembayaran Deposit Pembelian Import');

		$this->load->model('pembelian/pembayarandepositimport');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if($this->user->getUsername()=="pawit"){
				echo "<pre>";print_r($this->request->post);exit;
			}
			$no_po=$this->model_pembelian_pembayarandepositimport->addPembelian($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Pembayaran Deposit Pembelian Import  berhasil disimpan';

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

			$this->redirect($this->url->link('pembelian/pembayarandepositimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		if (isset($this->request->post['kurs'])) {
			$this->data['kurs'] = $this->request->post['kurs'];
		}  else {
			$this->data['kurs'] = '1';
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

		$this->data['cancel']= $this->url->link('pembelian/pembayarandepositimport', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/pembayarandepositimport/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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
        

		$this->template = 'pembelian/pembayarandepositimport_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {

		if(!is_numeric($this->request->post['nominals']) ){
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
		$this->load->model('pembelian/pembayarandepositimport');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_pembelian_pembayarandepositimport->updatePermintaan(array('status' => 3),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Pembayarn Deposit berhasil dibatalkan.';
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
			$this->redirect($this->url->link('pembelian/pembayarandepositimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function tampil(){
		$this->document->setTitle('Pembayaran Deposit Pembelian Import');
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
				$this->redirect($this->url->link('pembelian/pembayarandepositimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/pembayarandepositimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/pembayarandepositimport');
		$this->load->model('catalog/vendorimport');
		$this->load->model('keuangan/bank');

		$column=array('pembayaran_deposit_import.*','vendorimport.name as cname','banks.name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'vendorimport',
			'firsttable'	=>'pembayaran_deposit_import.vendor_id',
			'secondtable'	=> 'vendorimport.id'
		);
		$join[]=array(
			'tablename'	=> 'banks',
			'firsttable'	=>'pembayaran_deposit_import.bank_id',
			'secondtable'	=> 'banks.id'
		);

		$data = array(
			'pembayaran_deposit_import.id'	=> $id,

		);

		$trans=$this->model_pembelian_pembayarandepositimport->getPermintaanPembelian($column,$join,$data);
		$trans['vendor']=$trans['cname'];
		if(!empty($trans['ref'])){
			$trans['inv']=$this->model_sale_invoice->getPenjualan($trans['ref']);
			$trans['href']=$this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $trans['ref'], 'SSL');
		}
			$this->data['penerimaan']=$trans;
		$this->data['cancel']= $this->url->link('pembelian/pembayarandepositimport', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/pembayarandepositimport_info.tpl';
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


			$this->load->model('pembelian/pembayarandepositimport');
			//$this->load->model('catalog/title');
			//$this->load->model('sale/invoice');




			$results = $this->model_pembelian_pembayarandepositimport->getDepositTersedia($filter_name);


			foreach ($results as $result) {
				$dana=$result['nominal']-$result['totalalokasi'];
					$json[] = array(
						'id' => $result['id'],
						'text'       => date('d/m/y',strtotime($result['tgl_bayar'])).'- Tersedia $'.number_format($dana,'2','.',',').' - Kurs '.$this->currency->format($result['kurs']),

					);

			}
		//}

		$this->response->setOutput(json_encode($json));
	}

	public function detail(){
		$hasil = array();


		$this->load->model('pembelian/pembayarandepositimport');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$column=array();
				$id=$this->request->get['id'];
				$this->load->model('pembelian/pembayarandepositimport');
				$this->load->model('catalog/vendorimport');
				$this->load->model('keuangan/bank');

				$column=array('pembayaran_deposit_import.*','vendorimport.name as cname');
				$join=array();
				$join[]=array(
					'tablename'	=> 'vendorimport',
					'firsttable'	=>'pembayaran_deposit_import.vendor_id',
					'secondtable'	=> 'vendorimport.id'
				);


				$data = array(
					'pembayaran_deposit_import.id'	=> $id,

				);

				$hasil=$this->model_pembelian_pembayarandepositimport->getPermintaanPembelian($column,$join,$data);
				$hasil['deposit']=$hasil['nominal']-$hasil['totalalokasi'];
				$hasil['pdeposit']='$'.number_format($hasil['nominal']-$hasil['totalalokasi'],'2','.',',');

				$hasil['pkurs']=$this->currency->format($hasil['kurs']);

			}
		}
		$this->response->setOutput(json_encode($hasil));


	}

}
?>
