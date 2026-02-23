<?php
class ControllerKeuanganPembayaraniklan extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Pembayaran Biaya Iklan dan Promosi ');

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

		$this->data['insert'] = $this->url->link('keuangan/pembayaraniklan/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

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
		$this->load->model('keuangan/pembayaraniklan');

		$this->data['permintaans'] = array();
		$column=array('pembayaran_iklan.*','biaya_iklan.no_faktur as invoice_no','bank.nama_bank');
		$join=array();
		$join[]=array(
			'tablename'	=> 'biaya_iklan',
			'firsttable'	=>'pembayaran_iklan.order_id',
			'secondtable'	=> 'biaya_iklan.id'
		);

		$join[]=array(
			'tablename'	=> 'bank',
			'firsttable'	=>'pembayaran_iklan.bank_id',
			'secondtable'	=> 'bank.bank_id'
		);

		$data = array(
			'pembayaran_iklan.order_id'      =>$filter_no_po,
			'pembayaran_iklan.status'	=> array('<>',3),

		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'date_added'	=> 'DESC',

		);

		$product_total = $this->model_keuangan_pembayaraniklan->totalPermintaans($data);

		$results = $this->model_keuangan_pembayaraniklan->getPermintaanPembelians($column,$join,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();
			if($result['status'] == 1){
			$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('keuangan/pembayaraniklan/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['pembayaran_id'].$url, 'SSL')
				);
			}
			$this->data['permintaans'][] = array(
				'no_po'	=> $result['invoice_no'],
				'nama_bank'	=> $result['nama_bank'],
				'pembayaran_id'	=> $resut['pembayaran_id'],
				'jumlah'	=> $this->currency->format($result['nominal']),
				'tanggal'	=> date('d/m/y',strtotime($result['tgl_bayar'])),
				'status'	=> $result['status'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Pembayaran Biaya Iklan dan Promosi ';

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
		$pagination->url = $this->url->link('keuangan/pembayaraniklan', 'token=' . $this->session->data['token'] . $url . '&page={page}');

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

		$this->document->setTitle('Pembayaran Biaya Iklan dan Promosi ');

		$this->load->model('keuangan/pembayaraniklan');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_po=$this->model_keuangan_pembayaraniklan->addPembelian($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Pembayaran Biaya Iklan dan Promosi untuk Faktur '.$this->request->post['no_po'].' berhasil disimpan';

			$url = '';

			if (isset($this->request->get['filter_no_po'])) {
				$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('keuangan/pembayaraniklan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		if (isset($this->request->post['tgl_bayar'])) {
			$this->data['tgl_bayar'] = $this->request->post['tgl_bayar'];
		}  else {
			$this->data['tgl_bayar'] = '';
		}


		$this->data['cancel']= $this->url->link('keuangan/pembayaraniklan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('keuangan/pembayaraniklan/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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
		if (!$this->user->hasPermission('modify', 'keuangan/pembayaraniklan')) {
				$this->error['warning'] = 'Anda tidak diijinkan untuk memodifikasi menu ini.';
		}

		if (empty($this->request->post['order_id'])) {
		  		$this->error['order_id'] = 'Invoice pembelian tidak boleh kosong';
			}
		if(!is_numeric($this->request->post['nominal']) ){
			$this->error['nominal'] = 'Jumlah Pembayaran Harus Berupa Angka';
		}else{
			//cek
			$this->load->model('keuangan/pembayaraniklan');
			$cek=$this->load->model_keuangan_pembayaraniklan->getPermintaanPembelian(array('COALESCE(SUM(nominal),0) as total'),array(),array('status' =>1,'order_id'=>$this->request->post['order_id']));
			/*if(!empty($cek)){
				$this->error['jumlah'] = 'Duplikasi data Pembayaran Biaya Iklan dan Promosi';
			}*/

			$this->load->model('keuangan/biayaiklan');

      $biaya=$this->model_keuangan_biayaiklan->getPermintaanPembelian(array(),array(),array('id'=>$this->request->post['order_id']));

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
		$this->load->model('keuangan/pembayaraniklan');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_keuangan_pembayaraniklan->updatePermintaan(array('status' => 3),array('pembayaran_id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Pembayaran Biaya Iklan dan Promosi  berhasil dibatalkan.';
			}
		}
		$url = '';

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

			$this->redirect($this->url->link('keuangan/pembayaraniklan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}



}
?>
