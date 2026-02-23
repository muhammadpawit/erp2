<?php
class ControllerKeuanganfolowuppenagihan extends Controller {
	private $error=array();

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
			$filter_customer_id =null;
		}

		if (isset($this->request->get['filter_bank_id'])) {
			$filter_bank_id = $this->request->get['filter_bank_id'];
		} else {
			$filter_bank_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status =null;
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

		$this->data['insert'] = $this->url->link('keuangan/folowuppenagihan/insert', 'token=' . $this->session->data['token'].$url, 'SSL');


		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('keuangan/penerimaandana');
		$this->load->model('catalog/title');
		$this->load->model('sale/customer');

		$this->load->model('followup/penagihan');
		$this->data['permintaans'] = array();
		$filter = array(
			'filter_date_start'=>$filter_date_start,
			'filter_date_end'=>$filter_date_end,
			'customer_id'=>$filter_customer_id,
			'media'=>$filter_jenis,
			'limit' => 20,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
		);

		$product_total = count($this->model_followup_penagihan->totalgetdata($filter));
		$results = $this->model_followup_penagihan->getdata($filter);
		//$status=null;
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('keuangan/folowuppenagihan/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			$this->data['permintaans'][] = array(
				'id'	=> $result['id'],
				'tanggal'	=> date('d/m/Y H:i:s',strtotime($result['tanggal'])),
				'namacustomer'	=>$this->model_followup_penagihan->getcusts($result['customer_id']),
				'media'	=> $result['media'],
				'hasil_pembicaraan'	=> $result['hasil_pembicaraan'],
				'user'=>$this->model_followup_penagihan->getuser($result['user_created']),
				'actions' => $action
			);
		}

		$this->data['heading_title'] = 'Daftar Penerimaan Dana Hutang Lain Customer';

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
		$pagination->url = $this->url->link('keuangan/folowuppenagihan', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_tgl_awal'] = $filter_date_start;
		$this->data['filter_tgl_akhir'] = $filter_date_end;
		$this->data['filter_jenis'] = $filter_jenis;

		$this->template = 'followup/penagihancustomer.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Tambah Follow Up Customer ');

		$this->load->model('keuangan/penerimaandana');
		$this->load->model('followup/penagihan');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if($this->user->getUsername()=="pawits"){
				echo "<pre>";
				print_r($this->request->post);
				exit;
			}
			$no_po=$this->model_followup_penagihan->simpan($this->request->post);

			$this->session->data['success'] = 'Sukses: Data berhasil disimpan dengan id '.$no_po;

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

			$this->redirect($this->url->link('keuangan/folowuppenagihan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		$this->data['cancel']= $this->url->link('keuangan/folowuppenagihan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('keuangan/folowuppenagihan/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = array();
		}

		$this->template = 'followup/penagihancustomer_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {
		/*
		if(!is_numeric($this->request->post['nominal']) ){
			$this->error['nominal'] = 'Jumlah Pembayaran Harus Berupa Angka';
		}
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
		*/
		return true;
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
				$this->redirect($this->url->link('keuangan/folowuppenagihan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('keuangan/folowuppenagihan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('keuangan/penerimaandana');
		$this->load->model('sale/customer');
		$this->load->model('catalog/title');
		$this->load->model('sale/invoice');
		$this->load->model('keuangan/bank');
		$this->load->model('followup/penagihan');

		$trans=$this->model_followup_penagihan->getdetail($id);
		$this->data['product']=$trans;
		$this->data['namacustomer']=$this->model_followup_penagihan->getcusts($trans['customer_id']);
		$this->data['user']=$this->model_followup_penagihan->getuser($trans['user_created']);
		$this->data['cancel']= $this->url->link('keuangan/folowuppenagihan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		if($this->user->getUsername()=="pawits"){
			echo ucwords($this->terbilang($trans['nominal'])).' Rupiah';exit;
		}
		$this->template = 'followup/penagihan_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
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
      $no_po=$this->model_keuangan_penerimaandana->updatePermintaan(array('status'=>2,'tgl_diterima'=>$this->request->post['tgl_diterima']),array('id'=>$id));

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

		$trans=$this->model_keuangan_penerimaandana->getPermintaanPembelian($column,$join,$data);
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
