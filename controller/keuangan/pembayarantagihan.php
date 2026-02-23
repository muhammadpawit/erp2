<?php
class ControllerKeuanganPembayarantagihan extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Pembayaran Tagihan ');

		if (isset($this->request->get['filter_no_po'])) {
			$filter_no_po = $this->request->get['filter_no_po'];
		} else {
			$filter_no_po = '';
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
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('keuangan/pembayarantagihan/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

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

		$this->load->model('gudang/pembelian');
		$this->load->model('keuangan/pembayarantagihan');

		$this->data['permintaans'] = array();
		$column=array('pembayaran_tagihan.*','tagihan_biaya.no_faktur as invoice_no','banks.name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'tagihan_biaya',
			'firsttable'	=>'pembayaran_tagihan.order_id',
			'secondtable'	=> 'tagihan_biaya.id'
		);

		$join[]=array(
			'tablename'	=> 'banks',
			'firsttable'	=>'pembayaran_tagihan.bank_id',
			'secondtable'	=> 'banks.id'
		);

		$data = array(
			'pembayaran_tagihan.order_id'      =>$filter_no_po,
			'pembayaran_tagihan.status'	=> array('<>',3),

		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'pembayaran_tagihan.tgl_bayar'	=> 'DESC',

		);

		$product_total = $this->model_keuangan_pembayarantagihan->totalPermintaans($data);

		$results = $this->model_keuangan_pembayarantagihan->getPermintaanPembelians($column,$join,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();
			if($result['status'] == 1){
				$action[] = array(
						'text' => 'Batalkan',
						'newtarget'	=> 0,
						'href' => $this->url->link('keuangan/pembayarantagihan/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['pembayaran_id'].$url, 'SSL')
					);
				
			
			}
			if(!empty($result['no_dokumen'])){
				$action[] = array(
					'text' => 'Lihat Jurnal',
					'newtarget'	=> 1,
					'href' => $this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'] . '&filter_nodokumen=' . $result['no_dokumen'].$url, 'SSL')
				);

				/*$action[] = array(
					'text' => 'Lihat Mutasi',
					'newtarget'	=> 1,
					'href' => $this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'] . '&filter_nodokumen=' . $result['no_dokumen'].$url, 'SSL')
				);*/
			}
			$this->data['permintaans'][] = array(
				'no_po'	=> $result['invoice_no'],
				'nama_bank'	=> $result['name'],
				'pembayaran_id'	=> $result['pembayaran_id'],
				'jumlah'	=> $this->currency->format($result['nominal']),
				'tanggal'	=> date('d/m/y',strtotime($result['tgl_bayar'])),
				'status'	=> $result['status'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Pembayaran Tagihan ';

		$this->data['token'] = $this->session->data['token'];
		$url = '';

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('keuangan/pembayarantagihan', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_po'] = $filter_no_po;

		$this->template = 'keuangan/pembayaraniklan.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Pembayaran Tagihan ');

		$this->load->model('keuangan/pembayarantagihan');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_po=$this->model_keuangan_pembayarantagihan->addPembelian($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Pembayaran Tagihan untuk Faktur '.$this->request->post['no_po'].' berhasil disimpan';

			$url = '';

			if (isset($this->request->get['filter_no_po'])) {
				$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('keuangan/pembayarantagihan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url = '';

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
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
			$this->data['nominal'] = '';
		}

		if (isset($this->request->post['keterangan'])) {
			$this->data['keterangan'] = $this->request->post['keterangan'];
		}  else {
			$this->data['keterangan'] = '';
		}

		if (isset($this->request->post['tgl_bayar'])) {
			$this->data['tgl_bayar'] = $this->request->post['tgl_bayar'];
		}  else {
			$this->data['tgl_bayar'] = '';
		}


		$this->data['cancel']= $this->url->link('keuangan/pembayarantagihan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('keuangan/pembayarantagihan/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->template = 'keuangan/pembayaraniklan_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'keuangan/pembayarantagihan')) {
				$this->error['warning'] = 'Anda tidak diijinkan untuk memodifikasi menu ini.';
		}

		if (empty($this->request->post['order_id'])) {
		  		$this->error['order_id'] = 'Invoice pembelian tidak boleh kosong';
			}
		if(!is_numeric($this->request->post['nominal']) ){
			$this->error['nominal'] = 'Jumlah Pembayaran Harus Berupa Angka';
		}else{
			//cek
			$this->load->model('keuangan/pembayarantagihan');
			$cek=$this->load->model_keuangan_pembayarantagihan->getPermintaanPembelian(array('COALESCE(SUM(nominal),0) as total'),array(),array('status' =>1,'order_id'=>$this->request->post['order_id']));
			/*if(!empty($cek)){
				$this->error['jumlah'] = 'Duplikasi data Pembayaran Tagihan';
			}*/

			$this->load->model('keuangan/tagihanbiaya');

      $biaya=$this->model_keuangan_tagihanbiaya->getPermintaanPembelian(array(),array(),array('id'=>$this->request->post['order_id']));

			if(!empty($biaya)){
				if(($biaya['totalbayar']+$this->request->post['nominal']) > $biaya['total']){
					$this->error['nominal'] = 'Nilai Pembayaran melebihi nilai total yang harus dibayar sebesar '.$this->currency->format($biaya['total']).'. Total telah dibayar '.$this->currency->format($biaya['totalbayar']);
				}
			}else{
				$this->error['nominal'] = "Biaya Iklan dan Promosi tidak ditemukan";
			}
		}
		//print_r($cek);
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}


	public function batalkan(){
		$this->load->model('keuangan/pembayarantagihan');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_keuangan_pembayarantagihan->updatePermintaan(array('status' => 3),array('pembayaran_id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Pembayaran Tagihan  berhasil dibatalkan.';
			}
		}
		$url = '';

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

			$this->redirect($this->url->link('keuangan/pembayarantagihan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}



}
?>
