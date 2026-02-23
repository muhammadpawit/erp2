<?php
class ControllerKeuanganTagihanbiaya extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Tagihan Biaya');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '1970-01-01';
		}


		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['filter_jenis'])) {
			$filter_jenis = $this->request->get['filter_jenis'];
		} else {
			$filter_jenis = '';
		}

		if (isset($this->request->get['filter_no_faktur'])) {
			$filter_no_faktur = $this->request->get['filter_no_faktur'];
		} else {
			$filter_no_faktur = '';
		}

		if (isset($this->request->get['filter_vendor'])) {
			$filter_vendor = $this->request->get['filter_vendor'];
		} else {
			$filter_vendor =null;
		}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('keuangan/tagihanbiaya/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('keuangan/tagihanbiaya');

		/*
		PAjak
		1 PPh 21
		2 PPh 23
		3 PPh 4 (2) PP 46
		4 PPh 29
		5 PPh 4 (2) atas Sewa
		*/

		$this->data['permintaans'] = array();
		$column=array('tagihan_biaya.*');
		$join=array();
		/*$join[]=array(
			'tablename'	=> 'bank',
			'firsttable'	=>'biaya_operasional.bank_id',
			'secondtable'	=> 'bank.bank_id'
		);
		$join[]=array(
			'tablename'	=> 'coamnb',
			'firsttable'	=>'biaya_operasional.coa_id',
			'secondtable'	=> 'coamnb.category_id'
		);*/


		$data = array(
			//'tgl_tagihan'      =>!empty($filter_date_start)?$filter_date_start:array('>','1901-01-01'),
			//'tgl_tagihan'      =>!empty($filter_date_end)?$filter_date_end:array('>','1901-01-01'),
			'vendor_id'	=> empty($filter_vendor)?null:array('=',$filter_vendor),
			'id'	=> $filter_no_faktur,
			'status'	=> !empty($filter_jenis)?$filter_jenis:array('<>',4),
			//'biaya_operasional.coa_id'	=> $filter_jenis

		);
		if(!empty($filter_date_end)){
			$data['tgl_tagihan']=array('>=',$filter_date_start,'<=',$filter_date_end);
		}else{
			$data['tgl_tagihan']=array('>=',$filter_date_start);
		}
		$limit=5;
		$offset=($page - 1) * 5;

		$order=array(
			'tgl_tagihan'	=> 'DESC',
			'id'=>'DESC'
		);

		$product_total = $this->model_keuangan_tagihanbiaya->totalPermintaans($data);

		$results = $this->model_keuangan_tagihanbiaya->getPermintaanPembelians($column,$join,$data,$order,$limit,$offset);
		$this->load->model('keuangan/coa');
		$this->load->model('keuangan/pembayarantagihan');
		$this->load->model('keuangan/jurnal');
		$this->load->model('catalog/vendorlokal');
		$vendor=null;
		foreach ($results as $result) {
			$vendor=$this->model_catalog_vendorlokal->getVendor(array('id'=>$result['vendor_id']));
			$action = array();
			if($result['status'] == 1){
			$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('keuangan/tagihanbiaya/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}


			if(!empty($result['pajak'])){
				$hutangpajak=$this->model_keuangan_coa->getCategoryByKodeRek($result['pajak']);
			}else{
				$hutangpajak=array('name'=>'Tanpa Hutang Pajak');
			}

			if(!empty($result['akun_biaya'])){
				$akunbiaya=$this->model_keuangan_coa->getCategoryByKodeRek($result['akun_biaya']);
			}else{
				$akunbiaya=array('name'=>'Bukan Biaya');
			}

			if(!empty($result['akun_hutang'])){
				$hutang=$this->model_keuangan_coa->getCategoryByKodeRek($result['akun_hutang']);
			}else{
				$hutang=array('name'=>'Bukan Hutang');
			}

			if(!empty($result['pajakdimuka'])){
				$pajakdimuka=$this->model_keuangan_coa->getCategoryByKodeRek($result['pajakdimuka']);
			}else{
				$pajakdimuka=array('name'=>'Tanpa Pajak Dibayar Dimuka');
			}

			//riwayat pembayaran
			$pembayaran=$this->model_keuangan_pembayarantagihan->getPermintaanPembelians(array('*'),array(),array('order_id'=>$result['id'],'status'=> 1),array('tgl_bayar'=>'ASC'));

			//dokumen
			$dokumentagihan=array();
			if(!empty($result['no_dokumen'])){
				$dokumentagihan=$this->model_keuangan_jurnal->getJurnalForDokumen($result['no_dokumen']);
			}
			$this->data['permintaans'][] = array(
				//'nama_bank'	=> $result['nama_bank'],
				'id'	=> $result['id'],
				'pajak'	=> $result['pajak'].' '.$hutangpajak['name'],
				'pajakdimuka'	=> $result['pajakdimuka'].' '.$pajakdimuka['name'],
				'akun_biaya'	=> $result['akun_biaya'].' '.$akunbiaya['name'],
				'akun_hutang'	=> $result['akun_hutang'].' '.$hutang['name'],
				//'akun_biayadimuka'	=> $result['akun_biayadimuka'].' '.$akunbiayadimuka['name'],
				'jumlah'	=> $this->currency->format($result['nominal']),
				'ppn'	=> $this->currency->format($result['ppn']),
				'total'	=> $this->currency->format($result['total']),
				'nilaipajak'	=> $this->currency->format($result['nilaipajak']),
				'totalbayar'	=> $this->currency->format($result['totalbayar']),
				'tanggal'	=> date('d/m/y',strtotime($result['tgl_tagihan'])),
				'jatuhtempo'	=> date('d/m/y',strtotime($result['jatuhtempo'])),
				'status'	=> $result['status'],
				'statuspajak'	=> $result['statuspajak'],
				//'masaberlaku'	=> $result['masaberlaku'],
				'keterangan'	=> $result['keterangan'],
				'no_faktur'	=> $result['no_faktur'],
				'pembayaran'	=> $pembayaran,
				'dokumentagihan'	=> $dokumentagihan,
				'vendor'			=>$vendor['name']==null?'-':$vendor['name'],
				//'jenispembayaran'	=> $result['kode_rek'].' '.$result['name'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Tagihan Biaya ';

		$this->data['token'] = $this->session->data['token'];
		$url = '';
		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}


		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('keuangan/tagihanbiaya', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_tgl_awal'] = $filter_tgl_awal;
		$this->data['filter_tgl_akhir'] = $filter_tgl_akhir;
		$this->data['filter_jenis'] = $filter_jenis;

		$this->template = 'keuangan/tagihanbiaya.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Biaya Iklan ');

		$this->load->model('keuangan/tagihanbiaya');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if($this->user->getUsername()=="pawit"){
				echo "<pre>";print_r($this->request->post);exit;
			}  
			$no_po=$this->model_keuangan_tagihanbiaya->addPembelian($this->request->post);
			if($this->user->getUsername()=="pawits"){
				echo "<pre>";print_r($no_po);exit;
			}  
			$this->session->data['success'] = 'Sukses: Data Tagihan Biaya  berhasil disimpan';

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
			if (isset($this->request->get['filter_no_faktur'])) {
				$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('keuangan/tagihanbiaya', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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


		if (isset($this->request->post['nominal'])) {
			$this->data['nominal'] = $this->request->post['nominal'];
		}  else {
			$this->data['nominal'] = '';
		}

		if (isset($this->request->post['no_faktur'])) {
			$this->data['no_faktur'] = $this->request->post['no_faktur'];
		}  else {
			$this->data['no_faktur'] = '';
		}

		if (isset($this->request->post['tgl_tagihan'])) {
			$this->data['tgl_tagihan'] = $this->request->post['tgl_tagihan'];
		}  else {
			$this->data['tgl_tagihan'] = date('Y-m-d');
		}
		if (isset($this->request->post['keterangan'])) {
			$this->data['keterangan'] = $this->request->post['keterangan'];
		}  else {
			$this->data['keterangan'] = '';
		}

		if (isset($this->request->post['jatuhtempo'])) {
			$this->data['jatuhtempo'] = $this->request->post['jatuhtempo'];
		}  else {
			$this->data['jatuhtempo'] = date('Y-m-d');
		}
		if (isset($this->request->post['pajak'])) {
			$this->data['pajak'] = $this->request->post['pajak'];
		}  else {
			$this->data['pajak'] = 0;
		}
		if (isset($this->request->post['nilaipajak'])) {
			$this->data['nilaipajak'] = $this->request->post['nilaipajak'];
		}  else {
			$this->data['nilaipajak'] = 0;
		}


		$this->data['cancel']= $this->url->link('keuangan/tagihanbiaya', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('keuangan/tagihanbiaya/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->template = 'keuangan/tagihanbiaya_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}



	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'keuangan/tagihanbiaya')) {
				$this->error['warning'] = 'Anda tidak diijinkan untuk memodifikasi menu tagihan biaya.';
		}

		if(!is_numeric($this->request->post['nominal']) ){
			$this->error['nominal'] = 'Jumlah Pembayaran Harus Berupa Angka';
		}

		if(empty($this->request->post['no_faktur']) ){
			$this->error['no_faktur'] = 'Nomor faktur harus diisi';
		}

		if(strtotime($this->request->post['jatuhtempo']) < strtotime($this->request->post['tgl_tagihan'])){
			$this->error['tanggal'] = 'Tanggal jatuh tempo harus lebih dari sama dengan tanggal tagihan';
		}
		//print_r($cek);
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}


	public function batalkan(){
		$this->load->model('keuangan/tagihanbiaya');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_keuangan_tagihanbiaya->updatePermintaan(array('status' => 4),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Biaya Iklan  berhasil dibatalkan.';
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
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

			$this->redirect($this->url->link('keuangan/tagihanbiaya', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('keuangan/tagihanbiaya');

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

			$column=array('tagihan_biaya.*');
			$join=array();
			/*$join[]=array(
				'tablename'	=> 'bank',
				'firsttable'	=>'biaya_operasional.bank_id',
				'secondtable'	=> 'bank.bank_id'
			);
			$join[]=array(
				'tablename'	=> 'coamnb',
				'firsttable'	=>'biaya_operasional.coa_id',
				'secondtable'	=> 'coamnb.category_id'
			);*/

			$data = array(
				//'tgl_tagihan'      =>!empty($filter_date_start)?$filter_date_start:array('>','1901-01-01'),
				//'tgl_tagihan'      =>!empty($filter_date_end)?$filter_date_end:array('>','1901-01-01'),
				'status'	=> array('<>',4),
				//'no_faktur'	=> $filter_no_po
				'no_faktur' => array('LIKE',$filter_no_po),

			);
			$limit=20;
			$offset=0;

			$order=array(
				'tgl_tagihan'	=> 'DESC',

			);

			$results = $this->model_keuangan_tagihanbiaya->getPermintaanPembelians($column,$join,$data,$order,$limit,$offset);


			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_faktur'].' - '.$r['keterangan'].' '.$this->currency->format($r['total'])
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

}
?>
