<?php
class ControllerKeuanganDaftarpph extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('PPn Masukan');
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			//$filter_date_start ="";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
			//$filter_date_end ="";
		}
		if (isset($this->request->get['filter_jenis'])) {
			$filter_jenis = $this->request->get['filter_jenis'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_jenis ="";
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

		/*if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}*/


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		//$filter_jenis=array('1551','1552','1553','1554','2501','2502','2503','2504','2506','2507');
		$this->load->model('keuangan/jurnal');
		$this->load->model('keuangan/coa');
		//$this->data['coas'] = $this->model_keuangan_coa->getcoa($filter_jenis);
		$this->data['orders'] = array();
			$data = array(
				'filter_date_start'	     => $filter_date_start,
				'filter_date_end'	     => $filter_date_end,
				'filter_jenis'	=> $filter_jenis,
				'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit'                  => $this->config->get('config_admin_limit')
			);

			//$order_total = $this->model_keuangan_jurnal->totalPPh($data);
			//$this->data['order_total'] = $this->model_keuangan_jurnal->totalPPhs($data);
			//$results = $this->model_keuangan_jurnal->jurnalUmum($data);
			
			$results = $this->model_keuangan_jurnal->dpph($data);
			$total = count($this->model_keuangan_jurnal->totaldpph($data));
			//print_r($this->data['totalkredit']);exit;
			$totdebet=0;
			$totkredit=0;
			$n=1;
			$nt=1;
			foreach ($results as $result) {

				//$detail=$this->model_keuangan_jurnal->getDetailJurnalUmum($result['id'],$filter_jenis);
				
				$detail=array();
				$this->data['orders'][] = array(
					'keterangan'	=> $result['keterangan'],
					'tanggal'	=>date('d/m/y',strtotime($result['tanggal'])),
					//'ref'	=> $result['ref'],
					'kredit'	=> $result['kredit'],
					'debet'	=> $result['debet'],
					'akun'	=> $this->model_keuangan_jurnal->getnamacoa($result['ref_akun']),
					'detail'	=> $detail
					

				);
				/*
				foreach($detail as $d){
					$jenis=array('1551','1552','1553','1554','2501','2502','2503','2504','2505','2506','2507');
					if(in_array($d['ref_akun'],$jenis) && $d['kredit']!=0){ 
						$totdebet = $totdebet + $d['debet'];
						$totkredit = $totkredit + $d['kredit'];
						$nt=$n++;
					}
				}
				*/
				
			}
			$totalk = $this->model_keuangan_jurnal->totalnominaldpph($data);
			//$totalk=0;
			//print_r($results);exit;
			$this->data['tk'] = $totalk['total'];
			$this->data['tb'] = $totalk['totaldebet'];
			$this->data['transaksi'] = $totalk['transaksi'];
			$order_total=$total;
			$this->data['no']=1;
			$this->data['n']=$nt;

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
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('keuangan/daftarpph', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_jenis'] = $filter_jenis;

		$this->template = 'keuangan/daftarpph.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	public function coa(){
		
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
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('keuangan/tagihanbiaya/insert', 'token=' . $this->session->data['token'].$url, 'SSL');


		/*$this->load->model('report/product');
        $this->load->model('catalog/product');
		*/

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

		);

		$product_total = $this->model_keuangan_tagihanbiaya->totalPermintaans($data);

		$results = $this->model_keuangan_tagihanbiaya->getPermintaanPembelians($column,$join,$data,$order,$limit,$offset);
		$this->load->model('keuangan/coa');
		$this->load->model('keuangan/pembayarantagihan');

		foreach ($results as $result) {
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
				//'jenispembayaran'	=> $result['kode_rek'].' '.$result['name'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Tagihan Biaya ';

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
	
	public function jurnalumum()
	{
		
	$this->document->setTitle('Jurnal Umum');
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_date_start ="";
		}

		if (isset($this->request->get['filter_ref'])) {
			$filter_ref = $this->request->get['filter_ref'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_ref =null;
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			//$filter_date_end = date('Y-m-d');
			$filter_date_end ="";
		}
		if (isset($this->request->get['filter_jenis'])) {
			$filter_jenis = $this->request->get['filter_jenis'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_jenis ="";
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
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

		$this->load->model('keuangan/jurnal');
		$this->data['insert'] = $this->url->link('laporan/jurnalumum/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['orders'] = array();

		$data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_jenis'	=> $filter_jenis,
			'filter_ref'	=> $filter_ref,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_keuangan_jurnal->totalJurnalUmum($data);

		$results = $this->model_keuangan_jurnal->jurnalUmum($data);

		$this->load->model('pembelian/pembeliantunai');
		$this->load->model('pembelian/pembayarandp');
		//print_r($results);

		if(!empty($filter_jenis)){
			$this->data['totalakun']=$this->model_keuangan_jurnal->totalAkun($filter_jenis,$data);
		}else{
			$this->data['totalakun']=0;
		}

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),8);

		foreach ($results as $result) {

			$action=array();
			if($custdata){
				$action[] = array(
					'text' => 'Edit',
					'href' => $this->url->link('laporan/jurnalumum/editjurnal', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
				$action[] = array(
					'text' => 'Hapus',
					'href' => $this->url->link('laporan/jurnalumum/hapusjurnal', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}

			$detail=$this->model_keuangan_jurnal->getDetailJurnalUmum($result['id'],$filter_jenis);

			$this->data['orders'][] = array(
				'keterangan'	=> $result['keterangan'],
				'tanggal'	=>date('d/m/y',strtotime($result['tanggal'])),
				'ref'	=> $result['ref'],
				'type'	=> $result['type'],
				'detail'	=> $detail,
				'action'	=> $action

			);
		}

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
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_jenis'] = $filter_jenis;
		$this->data['filter_ref'] = $filter_ref;

		$this->template = 'laporan/jurnalumum.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	
	}

}
?>
