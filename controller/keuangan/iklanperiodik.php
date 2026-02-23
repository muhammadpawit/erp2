<?php
class ControllerKeuanganIklanperiodik extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Iklan Periodik');

		if (isset($this->request->get['filter_pameran_id'])) {
			$filter_pameran_id = $this->request->get['filter_pameran_id'];
		} else {
			$filter_pameran_id = 0;
		}

		if (isset($this->request->get['filter_biaya'])) {
			$filter_biaya = $this->request->get['filter_biaya'];
		} else {
			$filter_biaya = 0;
		}

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

		if (isset($this->request->get['filter_jenisbiaya'])) {
			$filter_jenisbiaya = $this->request->get['filter_jenisbiaya'];
		} else {
			$filter_jenisbiaya = '';
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
		if (isset($this->request->get['filter_pameran_id'])) {
			$url .= '&filter_pameran_id=' . $this->request->get['filter_pameran_id'];
		}
		if (isset($this->request->get['filter_biaya'])) {
			$url .= '&filter_biaya=' . $this->request->get['filter_biaya'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_jenisbiaya'])) {
			$url .= '&filter_jenisbiaya=' . $this->request->get['filter_jenisbiaya'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('keuangan/iklanperiodik/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		/*$this->load->model('report/product');
        $this->load->model('catalog/product');
		*/

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('keuangan/iklanperiodik');

		/*
		PAjak
		1 PPh 21
		2 PPh 23
		3 PPh 4 (2) PP 46
		4 PPh 29
		5 PPh 4 (2) atas Sewa
		*/

		$this->data['permintaans'] = array();
		$column=array('iklan_periodik.*');
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
			'tglawal'      =>!empty($filter_date_start)?$filter_date_start:array('>','1901-01-01'),
			'tglawal'      =>!empty($filter_date_end)?$filter_date_end:array('>','1901-01-01'),
			'status'	=> !empty($filter_status)?$filter_status:array('<>',4),
			'vendor_id'	=> $filter_pameran_id,
			'jenisbiaya'	=> $filter_biaya
			//'biaya_operasional.coa_id'	=> $filter_status

		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'vendor_id'	=> 'ASC',
			'tglawal'	=> 'DESC',

		);

		$product_total = $this->model_keuangan_iklanperiodik->totalPermintaans($data);

		$results = $this->model_keuangan_iklanperiodik->getPermintaanPembelians($column,$join,$data,$order,$limit,$offset);

		$this->load->model('catalog/vendorlokal');
		$this->load->model('keuangan/coa');

		foreach ($results as $result) {
			$action = array();
			if($result['status'] == 1){
			$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('keuangan/iklanperiodik/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}
			$jenisbiaya=$this->model_keuangan_coa->getCategory($result['jenisbiaya']);
			//if($result['status'] != 4 & $result['status'] != 3){

			//}
			$toko=$this->model_catalog_vendorlokal->getVendor(array('id'=>$result['vendor_id']));
			$this->data['permintaans'][] = array(
				//'nama_bank'	=> $result['nama_bank'],
				'id'	=> $result['id'],
				'vendor'	=> $toko['name'],
				'tglawal'	=> date('d/m/y',strtotime($result['tglawal'])),
				'tglakhir'	=> date('d/m/y',strtotime($result['tglakhir'])),
				'masaberlaku'	=> $result['masaberlaku'],
				'keterangan'	=> $result['keterangan'],
				'status'	=> $result['status'] == 1?'Belum Dibayar':($result['status'] == 2?'Dibayar Sebagian':($result['status'] == 3?'Lunas':'Dibatalkan')),
				'bulanan'	=> $this->currency->format($result['bulanan']),
				'nilaisewa'	=> $this->currency->format($result['nilaisewa']),
				'ppn'	=> $this->currency->format($result['ppn']),
				'total'	=> $this->currency->format($result['total']),
				'totalbayar'	=> $this->currency->format($result['totalbayar']),
				'jenisbiaya'	=> $result['jenisbiaya'].' '.$jenisbiaya['name'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Iklan Periodik';

		$this->data['token'] = $this->session->data['token'];
		$url = '';
		if (isset($this->request->get['filter_pameran_id'])) {
			$url .= '&filter_pameran_id=' . $this->request->get['filter_pameran_id'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_biaya'])) {
			$url .= '&filter_biaya=' . $this->request->get['filter_biaya'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}


		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('keuangan/iklanperiodik', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

	$this->data['filter_pameran_id']=$filter_pameran_id;
		$this->data['filter_tgl_awal'] = $filter_tgl_awal;
		$this->data['filter_tgl_akhir'] = $filter_tgl_akhir;
		$this->data['filter_status'] = $filter_status;

		$this->template = 'keuangan/iklanperiodik.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Biaya Iklan Periodik');

		$this->load->model('keuangan/iklanperiodik');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_po=$this->model_keuangan_iklanperiodik->addPembelian($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Iklan Periodik  berhasil disimpan';

			$url = '';
			if (isset($this->request->get['filter_pameran_id'])) {
				$url .= '&filter_pameran_id=' . $this->request->get['filter_pameran_id'];
			}
			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['filter_jenisbiaya'])) {
				$url .= '&filter_jenisbiaya=' . $this->request->get['filter_jenisbiaya'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('keuangan/iklanperiodik', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url = '';
		if (isset($this->request->get['filter_pameran_id'])) {
			$url .= '&filter_pameran_id=' . $this->request->get['filter_pameran_id'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_jenisbiaya'])) {
			$url .= '&filter_jenisbiaya=' . $this->request->get['filter_jenisbiaya'];
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


		if (isset($this->request->post['nilaisewa'])) {
			$this->data['nilaisewa'] = $this->request->post['nilaisewa'];
		}  else {
			$this->data['nilaisewa'] = 0;
		}

		if (isset($this->request->post['ppn'])) {
			$this->data['ppn'] = $this->request->post['ppn'];
		}  else {
			$this->data['ppn'] = 0;
		}
		if (isset($this->request->post['tglawal'])) {
			$this->data['tglawal'] = $this->request->post['tglawal'];
		}  else {
			$this->data['tglawal'] = date('Y-m-d');
		}

		if (isset($this->request->post['masaberlaku'])) {
			$this->data['masaberlaku'] = $this->request->post['masaberlaku'];
		}  else {
			$this->data['masaberlaku'] = 1;
		}

		if (isset($this->request->post['keterangan'])) {
			$this->data['keterangan'] = $this->request->post['keterangan'];
		}  else {
			$this->data['keterangan'] = '';
		}


		$this->data['cancel']= $this->url->link('keuangan/iklanperiodik', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('keuangan/iklanperiodik/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = array();
		}

		$this->template = 'keuangan/iklanperiodik_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'keuangan/iklanperiodik')) {
				$this->error['warning'] = 'Anda tidak diijinkan untuk memodifikasi menu Pengeluaran.';
		}

		if(!is_numeric($this->request->post['nilaisewa']) ){
			$this->error['nilaisewa'] = 'Jumlah Pembayaran Harus Berupa Angka';
		}


		//print_r($cek);
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}


	public function batalkan(){
		$this->load->model('keuangan/iklanperiodik');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_keuangan_iklanperiodik->updatePermintaan(array('status' => 4),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Biaya Iklan Periodik berhasil dibatalkan.';
			}
		}
		$url = '';
		if (isset($this->request->get['filter_pameran_id'])) {
			$url .= '&filter_pameran_id=' . $this->request->get['filter_pameran_id'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

			$this->redirect($this->url->link('keuangan/iklanperiodik', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('keuangan/iklanperiodik');

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

			$column=array('iklan_periodik.*','vendorlokal.name');
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
			$join[]=array(
				'tablename'	=> 'vendorlokal',
				'firsttable'	=>'iklan_periodik.vendor_id',
				'secondtable'	=> 'vendorlokal.id'
			);

			$data = array(
				//'tgl_tagihan'      =>!empty($filter_date_start)?$filter_date_start:array('>','1901-01-01'),
				//'tgl_tagihan'      =>!empty($filter_date_end)?$filter_date_end:array('>','1901-01-01'),
				'status'	=> array('<>',4),
				'no_faktur'	=> $filter_no_po

			);
			$limit=20;
			$offset=0;

			$order=array(
				'date_added'	=> 'DESC',

			);

			$results = $this->model_keuangan_iklanperiodik->getPermintaanPembelians($column,$join,$data,$order,$limit,$offset);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['name'].' - '.$r['keterangan'].' '.$this->currency->format($r['total'])
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

}
?>
