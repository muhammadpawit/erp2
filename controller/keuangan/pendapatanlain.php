<?php
class ControllerKeuanganPendapatanlain extends Controller {
	private $error=array();
	
	public function index() {
		$this->document->setTitle('Penerimaan Pembayaran');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = null;
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = null;
		}

		if (isset($this->request->get['filter_no_giro'])) {
			$filter_no_giro = $this->request->get['filter_no_giro'];
		} else {
			$filter_no_giro = null;
		}

		if (isset($this->request->get['filter_jenis'])) {
			$filter_jenis = $this->request->get['filter_jenis'];
		} else {
			$filter_jenis = null;
		}

		if (isset($this->request->get['filter_metode'])) {
			$filter_metode = $this->request->get['filter_metode'];
		} else {
			$filter_metode =null;
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}

		if (isset($this->request->get['filter_bank_id'])) {
			$filter_bank_id = $this->request->get['filter_bank_id'];
		} else {
			$filter_bank_id = null;
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

		$this->data['insert'] = $this->url->link('keuangan/pendapatanlain/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('keuangan/penerimaandana');
		$this->load->model('keuangan/pendapatanlain');
		$this->load->model('catalog/title');
		$this->load->model('sale/invoice');

		$filter = array(
			'tgl_awal' => $filter_date_start,
			'tgl_akhir' => $filter_date_end,
			'customer_id' => $filter_customer_id,
			'jenis' => $filter_jenis,
			'status' => $filter_status,
			'metode' => $filter_metode,
			'order' => 'DESC',
			//'limit' =>20,
			//'offset' => ($page - 1) * $this->config->get('config_admin_limit'),
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'            => $this->config->get('config_admin_limit')
		);
		$allfilter = array(
			'tgl_awal' => $filter_date_start,
			'tgl_akhir' => $filter_date_end,
			'customer_id' => $filter_customer_id,
			'jenis' => $filter_jenis,
			'status' => $filter_status,
			'metode' => $filter_metode,
		);
		$r = $this->model_keuangan_pendapatanlain->getpenerimaandana($filter);
		$total=0;
		$all=$this->model_keuangan_pendapatanlain->getpenerimaandana($allfilter);
		foreach($all as $a){
			$total+=($a['pendapatan_lain']+$a['biaya_lain']);
		}
		$this->data['totals']=$total;
		$product_total = count($this->model_keuangan_pendapatanlain->totalgetpenerimaandana($filter));
		$loop=$r;
		$bi=0;
		foreach ($loop as $result) {
			$action = array();
			
				$action[] = array(
					'text' => 'Tampil',
					'newtarget'	=> 0,
					'href' => $this->url->link('keuangan/pendapatanlain/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			if($result['status']==2){
				if($this->user->getId()!=148 OR $this->user->getId()!=145 ){
					$action[] = array(
						'text' => 'Batalkan',
						'newtarget'	=> 0,
						'href' => $this->url->link('keuangan/pendapatanlain/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
					);
				}
				if(!empty($result['no_dokumen'])){
					$action[] = array(
						'text' => 'Lihat Jurnal',
						'newtarget'	=> 1,
						'href' => $this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'] . '&filter_nodokumen=' . $result['no_dokumen'].$url, 'SSL')
					);
				}
			}
			if(!empty($result['ref'])){
				$ref=$this->model_sale_invoice->getPenjualan(array('id'=>$result['ref']));
			}
			$cst = $this->model_keuangan_pendapatanlain->getcust($result['customer_id']);
			
			$this->data['permintaans'][] = array(
				'id'	=> $result['id'],
				'jumlah'	=> $this->currency->format($result['pendapatan_lain']+$result['biaya_lain']),
				'tgl_bayar'	=> date('d/m/Y',strtotime($result['tgl_bayar'])),
				'tgl_diterima'	=> date('d/m/Y',strtotime($result['tgl_diterima'])),
				'tglterima_giro'	=> ($result['tglterima_giro']=='1970-01-01' OR $result['tglterima_giro']==null)?'':date('d/m/Y',strtotime($result['tglterima_giro'])),
				'tanggalditerima'	=> date('d/m/y',strtotime($result['tgl_diterima'])) == '01/01/70'?'Belum Diterima':date('d/m/y',strtotime($result['tgl_diterima'])),
				'status'	=> $result['status'],
				'keterangan'	=> $result['keterangan'],
				'ref'	=> empty($result['ref'])?'':$ref['no_faktur'],
				'href'	=>$this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['ref'], 'SSL'),
				'jenis'	=> $result['jenis'],
				'no_giro'	=> $result['no_giro'],
				'metode_pembayaran'	=> $result['metode_pembayaran'],
				//'customer'	=> $this->model_catalog_title->getTitle($result['title']).' '.$result['name'],
				'customer' => $this->model_catalog_title->getTitle($cst['title']).' '. $cst['name'],
				'customer_id' =>$result['customer_id'],
				'type'	=> $result['type'],
				'actions'	=> $action
			);
		}
		$this->data['heading_title'] = 'Penerimaan Pembayran Customer';

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
		$pagination->url = $this->url->link('keuangan/pendapatanlain', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_tgl_awal'] = $filter_date_start;
		$this->data['filter_tgl_akhir'] = $filter_date_end;
		$this->data['filter_jenis'] = $filter_jenis;
		$this->data['filter_status'] = $filter_status;
		$this->template = 'pendapatanlain/pendapatanlain.tpl';
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
		$this->load->model('keuangan/pendapatanlain');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//echo "<pre>";print_r($this->request->post);exit;
			if($this->user->getUsername()=="pawits"){
				echo "<pre>";print_r($this->request->post);exit;
			}
			$no_po=$this->model_keuangan_pendapatanlain->Addpendapatanlain($this->request->post);

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

			$this->redirect($this->url->link('keuangan/pendapatanlain', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
		if (isset($this->request->post['keterangan'])) {
			$this->data['keterangan'] = $this->request->post['keterangan'];
		}  else {
			$this->data['keterangan'] = '';
		}

		if (isset($this->request->post['no_giro'])) {
			$this->data['no_giro'] = $this->request->post['no_giro'];
		}  else {
			$this->data['no_giro'] = '';
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

		$this->data['cancel']= $this->url->link('keuangan/pendapatanlain', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('keuangan/pendapatanlain/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->template = 'pendapatanlain/pendapatanlain_form.tpl';
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
		$this->load->model('keuangan/pendapatanlain');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
			$id=$this->request->get['id'];
			$this->model_keuangan_pendapatanlain->batalkan($id);
			$this->session->data['success'] = 'Sukses: Data Penerimaan Dana berhasil dibatalkan.';
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
			$this->redirect($this->url->link('keuangan/pendapatanlain', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
				$this->redirect($this->url->link('keuangan/penerimaandana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('keuangan/penerimaandana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('keuangan/penerimaandana');
		$this->load->model('keuangan/pendapatanlain');
		$this->load->model('sale/customer');
		$this->load->model('catalog/title');
		$this->load->model('sale/invoice');
		$this->load->model('keuangan/bank');
			$column=array('penerimaan_pendapatanlain.*','customer.name as cname','customer.title','customer.alamat');
			$join=array();
			$join[]=array(
				'tablename'	=> 'customer',
				'firsttable'	=>'penerimaan_pendapatanlain.customer_id',
				'secondtable'	=> 'customer.customer_id'
			);
			$data = array(
				'penerimaan_pendapatanlain.id'	=> $id,

			);
		

		$trans=$this->model_keuangan_pendapatanlain->getPermintaanPembelian($column,$join,$data);
		$trans['customer']=$this->model_catalog_title->getTitle($trans['title']).' '.$trans['cname'];
		if(!empty($trans['ref'])){
			$trans['inv']=$this->model_sale_invoice->getPenjualan($trans['ref']);
			$trans['href']=$this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $trans['ref'], 'SSL');
		}
		$nominal=$trans['pendapatan_lain']+$trans['biaya_bank']+$trans['biaya_lain'];
		$trans['nominal']=$trans['nominal']+$trans['pendapatan_lain'];
		//$trans['terbilang']=ucwords($this->terbilang($trans['nominal'])+$trans['biaya_bank']).' Rupiah';
		$trans['terbilang']=ucwords($this->terbilang($nominal)).' Rupiah';
		$this->data['penerimaan']=$trans;
		$this->data['cancel']= $this->url->link('keuangan/pendapatanlain', 'token=' . $this->session->data['token'] . $url, 'SSL');
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";print_r($trans);exit;
		}
		$this->template = 'pendapatanlain/pendapatanlain_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	// baru 17 Desember 2019
	public function tampilmetodebiaya(){
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
				$this->redirect($this->url->link('keuangan/penerimaandana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('keuangan/penerimaandana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('keuangan/penerimaandana');
		$this->load->model('sale/customer');
		$this->load->model('catalog/title');
		$this->load->model('sale/invoice');
		$this->load->model('keuangan/bank');

		//$column=array('penerimaan_dana.*','customer.name as cname','customer.title','banks.name','customer.alamat');
		$column=array('penerimaan_dana.*','customer.name as cname','customer.title','customer.alamat');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=>'penerimaan_dana.customer_id',
			'secondtable'	=> 'customer.customer_id'
		);
		/*
		$join[]=array(
			'tablename'	=> 'banks',
			'firsttable'	=>'penerimaan_dana.bank_id',
			'secondtable'	=> 'banks.id'
		);
		*/
		$data = array(
			'penerimaan_dana.id'	=> $id,

		);

		$trans=$this->model_keuangan_pendapatanlain->getPermintaanPembelian($column,$join,$data);
		$trans['customer']=$this->model_catalog_title->getTitle($trans['title']).' '.$trans['cname'];
		$bnk = $this->model_keuangan_pendapatanlain->getbank($trans['bank_id']);
		$trans['name'] = $bnk['name'];
		if(!empty($trans['ref'])){
			$trans['inv']=$this->model_sale_invoice->getPenjualan($trans['ref']);
			$trans['href']=$this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $trans['ref'], 'SSL');
		}
		$nominal=$trans['nominal']+$trans['biaya_bank']+$trans['biaya_lain'];
		//$trans['terbilang']=ucwords($this->terbilang($trans['nominal'])+$trans['biaya_bank']).' Rupiah';
		$trans['terbilang']=ucwords($this->terbilang($nominal)).' Rupiah';
		$this->data['penerimaan']=$trans;
		$this->data['cancel']= $this->url->link('keuangan/penerimaandana', 'token=' . $this->session->data['token'] . $url, 'SSL');
		if($this->user->getUsername()=="pawits"){
			//echo ucwords($this->terbilang($trans['nominal'])+$trans['biaya_bank']).' Rupiah';
			echo "<pre>";print_r($trans);
			exit;
		}
		$this->template = 'keuangan/penerimaandana_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	// end baru
	/*public function terima(){
		$this->document->setTitle('Penerimaan Pembayaran Customer');
		$this->load->model('keuangan/penerimaandana');
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
		if (isset($this->request->get['id'])) {
			$url .= '&id=' . $this->request->get['id'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('keuangan/penerimaandana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('keuangan/penerimaandana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
      $no_po=$this->model_keuangan_pendapatanlain->updatePermintaan(array('status'=>2,'tgl_diterima'=>$this->request->post['tgl_diterima']),array('id'=>$id));

			$this->session->data['success'] = 'Sukses: Data Pembayaran Customer  berhasil diterima';



			$this->redirect($this->url->link('keuangan/penerimaandana', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/customer');
		$this->load->model('catalog/title');
		$this->load->model('sale/invoice');
		$this->load->model('keuangan/bank');

		$column=array('penerimaan_dana.*','customer.name as cname','customer.title','banks.name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=>'penerimaan_dana.customer_id',
			'secondtable'	=> 'customer.customer_id'
		);
		$join[]=array(
			'tablename'	=> 'banks',
			'firsttable'	=>'penerimaan_dana.bank_id',
			'secondtable'	=> 'banks.id'
		);

		$data = array(
			'penerimaan_dana.id'	=> $id,

		);

		$trans=$this->model_keuangan_pendapatanlain->getPermintaanPembelian($column,$join,$data);
		$trans['customer']=$this->model_catalog_title->getTitle($trans['title']).' '.$trans['cname'];
		if(!empty($trans['ref'])){
			$trans['inv']=$this->model_sale_invoice->getPenjualan($trans['ref']);
			$trans['href']=$this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $trans['ref'], 'SSL');
		}
		$trans['terbilang']=ucwords($this->terbilang($trans['nominal'])).' Rupiah';
		$this->data['penerimaan']=$trans;
		$this->data['cancel']= $this->url->link('keuangan/penerimaandana', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('keuangan/penerimaandana/terima', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->template = 'keuangan/penerimaandana_terima.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}*/
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
