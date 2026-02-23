<?php
class ControllerKeuanganPenerimaandanaHutangLain extends Controller {
	private $error=array();
	// baru 14 Januari 2019
	public function batalkan(){
		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
			$this->db->update('penerimaandana_hutanglain',array('user_batal' => $this->user->getId(),'status' => 2),array('id'	=> $this->request->get['id']));
			$this->db->update('jurnal_umum',array('type' => '201912','hapus' => 1),array('ref'	=> $this->request->get['id']));
			// hapus mutasi
			$this->db->update('aruskas', array('hapus'=>1),array('hutanglain_id'=>$this->request->get['id']));
			$this->load->model('keuangan/bank');
    		$b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$this->request->get['bank_id']));
    		$saldo=$b['saldo'] - $this->request->get['nominal'];
    		$this->model_keuangan_bank->editBank(array('saldo'  => $saldo),array('id'=> $this->request->get['bank_id']));
			$this->session->data['success'] = 'Sukses: Data Penerimaan Dana Hutang Lain berhasil dibatalkan.';
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
			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
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
			$this->redirect($this->url->link('keuangan/penerimaandanahutanglain', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	// end baru
	// baru 
	public function rek(){
		$this->load->model('keuangan/penerimaandana');
		$id = $this->request->get['bank_id'];
		$d = $this->model_keuangan_penerimaandana->getbank($id);
		echo $d['rek_parent'];
	}
	// end baru
	public function index() {
		$this->document->setTitle('Penerimaan Pembayaran');

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

		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = '';
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
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
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

		$this->data['insert'] = $this->url->link('keuangan/penerimaandanahutanglain/insert', 'token=' . $this->session->data['token'].$url, 'SSL');


		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('keuangan/penerimaandana');
		$this->load->model('catalog/title');
		$this->load->model('sale/invoice');

		$this->data['permintaans'] = array();
		$column=array('penerimaan_dana.*','banks.name as nama_bank','customer.name','customer.title');
		$join=array();
		$join[]=array(
			'tablename'	=> 'banks',
			'firsttable'	=>'penerimaan_dana.bank_id',
			'secondtable'	=> 'banks.id'
		);
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=>'penerimaan_dana.customer_id',
			'secondtable'	=> 'customer.customer_id'
		);
		/*$join[]=array(
			'tablename'	=> 'coamnb',
			'firsttable'	=>'biaya_operasional.coa_id',
			'secondtable'	=> 'coamnb.category_id'
		);*/
		if(!empty($filter_date_start) && !empty($filter_date_end)){
			$data=array(
				'penerimaan_dana.tgl_bayar '      =>!empty($filter_date_start)?array(">=",$filter_date_start):array('>','1901-01-01'),
				'penerimaan_dana.tgl_bayar'      =>!empty($filter_date_end)?array("<=",$filter_date_end):array('>','1901-01-01'),
			);
		}
		else if(!empty($filter_date_start) && empty($filter_date_end)){
			$data=array(
				'penerimaan_dana.tgl_bayar '      =>!empty($filter_date_start)?$filter_date_start:array('>','1901-01-01'),
			);
		}
		else if(empty($filter_date_start) && !empty($filter_date_end)){
			$data=array(
				'penerimaan_dana.tgl_bayar '      =>!empty($filter_date_end)?$filter_date_end:array('>','1901-01-01'),
			);
		}
		else{
			$data=array(
				'penerimaan_dana.tgl_bayar '      =>!empty($filter_date_end)?$filter_date_end:array('>','1901-01-01'),
			);
		}

		$data+=array(
		//	'penerimaan_dana.status'	=> !empty($filter_status)?$filter_status:array('<=',3),
			'penerimaan_dana.customer_id'	=> !empty($filter_customer_id)?$filter_customer_id:array('>=',1),
			'penerimaan_dana.jenis'	=> !empty($filter_jenis)?$filter_jenis:array('>=',1),
			'penerimaan_dana.metode_pembayaran'	=> !empty($filter_metode)?$filter_metode:array('>=',1),
			'penerimaan_dana.status'	=> !empty($filter_status)?$filter_status:array('>=',1),
			'penerimaan_dana.no_giro'	=> $filter_no_giro != null ?array('LIKE',$filter_no_giro):'',
		);

		$order=array(
			'tgl_bayar'	=> 'DESC',

		);

		$datas = array(
			'status' =>$filter_status,
			'limit' => 20,
			'start' => $offset=($page - 1) * $this->config->get('config_admin_limit'),
		);

		$product_total = count($this->model_keuangan_penerimaandana->totalgetpenerimaanhutanglain($datas));

		$results = $this->model_keuangan_penerimaandana->getpenerimaanhutanglain($datas);
		$status=null;
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('keuangan/penerimaandanahutanglain/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			if($result['status']!=3 && $result['status']!=2 ){
				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('keuangan/penerimaandanahutanglain/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].'&bank_id='.$result['bank_tujuan'].'&nominal='.$result['nominal'].$url, 'SSL')
				);
			}
			if(!empty($result['no_dokumen'])){
				$action[] = array(
					'text' => 'Lihat Jurnal',
					'newtarget'	=> 1,
					'href' => $this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'] . '&filter_nodokumen=' . $result['no_dokumen'].$url, 'SSL')
				);
			}
			if(!empty($result['ref'])){
				$ref=$this->model_sale_invoice->getPenjualan(array('id'=>$result['ref']));
			}
			$bank = $this->model_keuangan_penerimaandana->getbanks($result['bank_tujuan']);
			  if($result['status'] == 1){
				$status ='<span class="label label-primary">Disimpan</span>';
			  }
			  if($result['status'] == 2){
				$status = '<span class="label label-danger">Dibatalkan</span>';
			  }
			  if($result['status'] == 3){
				$status = '<span class="label label-success">Diterima</span>';
			  }
			  if($result['status'] == 4){
				$status = 'Ditolak';
			  }
			  if($result['status'] == 5){
				$status = 'Sudah diinput';
			  }
			$this->data['permintaans'][] = array(
				'id'	=> $result['id'],
				'tanggal'	=> date('d/m/y',strtotime($result['tanggal'])),
				'jumlah'	=> $this->currency->format($result['nominal']),
				'keterangan'	=> $result['keterangan'],
				'nama_bank'	=> $bank,
				'no_giro'	=> $result['no_giro'],
				'status'	=> $status,
				'linkterkait' => $result['linkterkait'],
				'metode_pembayaran'	=> $result['metode_pembayaran'],
				'actions' => $action
			);
		}

		$this->data['heading_title'] = 'Penerimaan Dana Hutang Lain Customer';

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
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
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
		$pagination->url = $this->url->link('keuangan/penerimaandanahutanglain', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_tgl_awal'] = $filter_date_start;
		$this->data['filter_tgl_akhir'] = $filter_date_end;
		$this->data['filter_jenis'] = $filter_jenis;

		$this->template = 'keuangan/penerimaandanahutanglain.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Penerimaan Pembayaran Customer ');

		$this->load->model('keuangan/penerimaandana');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if($this->user->getUsername()=="pawitx"){
				echo "<pre>";
				print_r($this->request->post);
				exit;
			}
			$no_po=$this->model_keuangan_penerimaandana->addpenerimaandanahutanglain($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Pembayaran Customer  berhasil disimpan';

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
			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
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

			$this->redirect($this->url->link('keuangan/penerimaandanahutanglain', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
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

		if (isset($this->request->post['customer_id'])) {
			$this->data['customer_id'] = $this->request->post['customer_id'];
		}  else {
			$this->data['customer_id'] = '';
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
		
		if (isset($this->request->post['biaya_lain'])) {
			$this->data['biaya_lain'] = $this->request->post['biaya_lain'];
		}  else {
			$this->data['biaya_lain'] = '0';
		}

		$this->data['cancel']= $this->url->link('keuangan/penerimaandanahutanglain', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('keuangan/penerimaandanahutanglain/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->template = 'keuangan/penerimaandanahutanglain_form.tpl';
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



	public function tampil(){
		$this->document->setTitle('Penerimaan Pembayaran Customer');
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
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
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
				$this->redirect($this->url->link('keuangan/penerimaandanahutanglain', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('keuangan/penerimaandanahutanglain', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('keuangan/penerimaandana');
		$this->load->model('sale/customer');
		$this->load->model('catalog/title');
		$this->load->model('sale/invoice');
		$this->load->model('keuangan/bank');


		$trans=$this->model_keuangan_penerimaandana->getgetpenerimaanhutanglain($id);
		$this->data['bank'] = $this->model_keuangan_penerimaandana->getbanks($trans['bank_tujuan']);
		if(!empty($trans['ref'])){
			$trans['inv']=$this->model_sale_invoice->getPenjualan($trans['ref']);
			$trans['href']=$this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $trans['ref'], 'SSL');
		}
		$nominal=$trans['nominal'];
		$trans['terbilang']=ucwords($this->terbilang($nominal)).' Rupiah';
		$this->data['penerimaan']=$trans;
		$this->data['cancel']= $this->url->link('keuangan/penerimaandanahutanglain', 'token=' . $this->session->data['token'] . $url, 'SSL');
		if($this->user->getUsername()=="pawits"){
			echo ucwords($this->terbilang($trans['nominal'])).' Rupiah';exit;
		}
		$this->template = 'keuangan/penerimaandanahutanglain_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function terbilang($x){
        $ambil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        if ($x < 12)
            return " " . $ambil[$x];
        elseif ($x < 20)
            return $this->terbilang($x - 10) . " belas";
        elseif ($x < 100)
            return $this->terbilang($x / 10) . " puluh" . $this->terbilang($x % 10);
        elseif ($x < 200)
            return " seratus" . $this->terbilang($x - 100);
        elseif ($x < 1000)
            return $this->terbilang($x / 100) . " ratus" . $this->terbilang($x % 100);
        elseif ($x < 2000)
            return " seribu" . $this->terbilang($x - 1000);
        elseif ($x < 1000000)
            return $this->terbilang($x / 1000) . " ribu" . $this->terbilang($x % 1000);
        elseif ($x < 1000000000)
            return $this->terbilang($x / 1000000) . " juta" . $this->terbilang($x % 1000000);
    }

}
?>
