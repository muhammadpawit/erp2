<?php
class ControllerLaporanSelisihkurs extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Laporan Selisih Kurs');

		if (isset($this->request->get['filter_no_faktur'])) {
			$filter_no_faktur = $this->request->get['filter_no_faktur'];
		} else {
			$filter_no_faktur = '';
		}


		if (isset($this->request->get['filter_jenis_barang'])) {
			$filter_jenis_barang = $this->request->get['filter_jenis_barang'];
		} else {
			$filter_jenis_barang = null;
		}

		if (isset($this->request->get['filter_vendor'])) {
			$filter_vendor = $this->request->get['filter_vendor'];
		} else {
			$filter_vendor = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$filter_status_penerimaan = $this->request->get['filter_status_penerimaan'];
		} else {
			$filter_status_penerimaan = null;
		}

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start ='1970-01-01';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = null;
		}



		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url='';

		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
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

		if (isset($this->session->data['warning'])) {
			$this->data['warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else {
			$this->data['warning'] = '';
		}


		$this->load->model('catalog/vendorimport');
		$this->load->model('pembelian/invoicepembelianimport');

		$this->data['permintaans'] = array();
		$column=array('invoice_pembelian_import.*','vendorimport.name','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'invoice_pembelian_import.vendor_id'
		);

		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'secondtable'	=>'gudang.gudang_id',
			'firsttable'	=> 'invoice_pembelian_import.gudang_id'
		);

		$data = array(
			//'no_po'      =>array('LIKE',$filter_no_po),
			'invoice_pembelian_import.id'      =>$filter_no_faktur,
			'vendor_id'=> $filter_vendor,
			//'surat_id'=> $filter_no_surat,
			'jenisproduk'	=> $filter_jenis_barang,
			  //'invoice_pembelian_import.status'	=> empty($filter_status)?array('>=',1):$filter_status,
			  'invoice_pembelian_import.status'	=> empty($filter_status)?array('<>',3):$filter_status,
			'invoice_pembelian_import.statuspenerimaan'	=> empty($filter_status_penerimaan)?array('<=',3):$filter_status_penerimaan,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		if(!empty($filter_date_end)){
			$data['invoice_pembelian_import.tglfaktur']=array('>=',$filter_date_start,'<=',$filter_date_end);
		}else{
			$data['invoice_pembelian_import.tglfaktur']=array('>=',$filter_date_start);
		}

		if($filter_date_start == '1970-01-01'){
			$filter_date_start=null;
		}


		$order=array(
			'invoice_pembelian_import.id'	=> 'DESC',
			'invoice_pembelian_import.tglfaktur'	=> 'DESC',
			'invoice_pembelian_import.status'	=> 'ASC'
		);

		$this->load->model('catalog/gudang');

		// $product_total = $this->model_pembelian_invoicepembelianimport->totalPermintaans($data);
		if(isset($this->request->get['excel'])){
			$results = $this->model_pembelian_invoicepembelianimport->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,0,null);
		}else{
			$results = $this->model_pembelian_invoicepembelianimport->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);
		}
		
		$kurspib=0;
		$all = $this->model_pembelian_invoicepembelianimport->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,0,null);
		$product_total = count($all);
		$alltagihan=0;
		$allbayar=0;
		$allkurspib=0;
		$allpib=0;
		$allselisih=0;
		foreach($all as $a ){
			$kurspib=$a['kurspajakpib']==null?0:$a['kurspajakpib'];
			$alltagihan+=($a['total']);
			$allbayar+=($a['totalbayarrp']);
			$allkurspib+=($kurspib);
			$allpib+=( ($a['total']*$kurspib) );
			$allselisih+=( ($a['total']*$kurspib) - $a['totalbayarrp'] );
		}
		$this->data['alltagihan']='$'.number_format($alltagihan,2,'.',',');
		$this->data['allbayar']=$this->currency->format($allbayar);
		$this->data['allkurs']=$this->currency->format($allkurspib);
		$this->data['allpib']=$this->currency->format($allpib);
		$this->data['allselisih']=$this->currency->format($allselisih);
		foreach ($results as $result) {
			$action = array();

			$gudang="";
			if($result['gudang_id'] > 0){
				$g=$this->model_catalog_gudang->getGudang($result['gudang_id']);
				$gudang=$g['nama'];
			}
			$kurspib=$result['kurspajakpib']==null?0:$result['kurspajakpib'];
			$this->data['permintaans'][] = array(
				'name'	=> $result['name'],
				'gudang'	=> $result['nama'],
				'no_faktur'	=> $result['no_faktur'],
				'id'	=> $result['id'],
				'sub_total'	=> '$'.number_format($result['sub_total'],2,'.',','),
				'diskon'	=> '$'.number_format($result['diskon'],2,'.',','),
				'pajak'	=> '$'.number_format($result['pajak'],0,'.',','),
				'total'	=> '$'.number_format($result['total'],2,'.',','),
				'totalbayar'	=> $this->currency->format($result['totalbayarrp']),
				'kurspib'	=> $this->currency->format($kurspib),
				'totalpib'=>$this->currency->format(($kurspib*$result['total'])),
				'selisihkurs'=>$this->currency->format( ($kurspib*$result['total']) - $result['totalbayarrp'] ),
				'tanggal'	=> date('d/m/y',strtotime($result['tglfaktur'])),
				'jatuhtempo'	=> date('d/m/y',strtotime($result['jatuhtempo'])),
				'tgllunas'	=> date('d/m/y',strtotime($result['tgllunas'])),
				'jenis_barang'	=> $result['jenisproduk'],
				'status'	=> $result['status'] == 1?'Ditagih':($result['status'] == 2?'Dibayar Sebagian':($result['status'] == 3?'Dibatalkan':'Lunas')),
				'statuspenerimaan'	=> $result['statuspenerimaan'] == 0?'Belum Diterima':($result['statuspenerimaan'] == 2?'Diterima Sebagian':'Sudah Diterima'),
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Invoice Pembelian Import';

		$this->data['token'] = $this->session->data['token'];
		$url='';

		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/selisihkurs', 'token=' . $this->session->data['token'] . $url . '&page={page}');
		$this->data['excel']= $this->url->link('laporan/selisihkurs', 'token=' . $this->session->data['token'] .'&excel=1'. $url, 'SSL');
		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_faktur'] = $filter_no_faktur;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_vendor'] = $filter_vendor;

		if(isset($this->request->get['excel'])){
			$this->template = 'laporan/selisihkurs_excel.tpl';
		}else{
			$this->template = 'laporan/selisihkurs.tpl';
		}
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function tampil(){
		$this->document->setTitle('Invoice Pembelian Import');
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/invoicepembelianimport');

		$column=array('invoice_pembelian_import.*','vendorimport.name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'invoice_pembelian_import.vendor_id'
		);

		$data = array(
			'invoice_pembelian_import.id'	=> $id,
      'invoice_pembelian_import.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelianProduct(array('invoice_id'	=> $id));
		//print_r($prods);
		$biayas=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelianBiaya(array('order_id'	=> $id));
		$pembayarans=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelianPembayaran(array('invoice_id'=>$id));
		/*$pbjoin[]=array(
			'tablename'=>'banks',
			'firsttable'	=> 'pembayaran_import.bank_id',
			'secondtable'=> 'banks.id'
		);
		$pembayarans=$this->model_pembelian_pembayarandpimport->getPermintaanPembelians(array('pembayaran_import.*','banks.name'),$pbjoin,array('no_po'=>$trans['no_po'],'status'=>1));
		//print_r($pembayarans);*/
		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['biayas']=$biayas;
		$this->data['pembayarans']=$pembayarans;

		$this->data['cancel']= $this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/invoicepembelianimport_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function batalkan(){
		$this->load->model('pembelian/invoicepembelianimport');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_pembelian_invoicepembelianimport->batalInvoice(array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Pembelian Import berhasil dibatalkan.';
			}
		}
		$url='';
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

			$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	public function autocomplete(){
		$rests = array();

		$this->load->model('pembelian/invoicepembelianimport');

			if (isset($this->request->get['q'])) {
				$filter_no_po = $this->request->get['q'];
			} else {
				$filter_no_po = '';
			}
			if (isset($this->request->get['f'])) {
				$filter_vendor = $this->request->get['f'];
			} else {
				$filter_vendor = null;
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_faktur'         => array('LIKE',$filter_no_po),
				'status'	=> array('<>',3)
			);
			if(!is_null($filter_vendor)){
				$data['vendor_id']	=	$filter_vendor;
			}
			$start=0;
			$limit=0;
			$column=array('id','no_faktur','total');
			$join=array();
			$leftjoin=array();

			$results = $this->model_pembelian_invoicepembelianimport->getPermintaanPembelians($column,$join,$join,$data,array(),$limit,$start);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					//'text'	=> $r['no_po'].' Total $'.number_format($r['total_pembelian'])
					'text'	=> $r['no_faktur']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}
	
	public function autocompletedetail(){
		$rests = array();

		$this->load->model('pembelian/invoicepembelianimport');

			if (isset($this->request->get['q'])) {
				$filter_no_po = $this->request->get['q'];
			} else {
				$filter_no_po = '';
			}
			if (isset($this->request->get['f'])) {
				$filter_vendor = $this->request->get['f'];
			} else {
				$filter_vendor = null;
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				//'id'         => array('LIKE',$filter_no_po),
				'id'         => $filter_no_po,
				'status'	=> array('<>',3)
			);
			if(!is_null($filter_vendor)){
				$data['vendor_id']	=	$filter_vendor;
			}
			$start=0;
			$limit=0;
			$column=array('id','no_faktur','total');
			$join=array();
			$leftjoin=array();

			$results = $this->model_pembelian_invoicepembelianimport->getPermintaanPembelians($column,$join,$join,$data,array(),$limit,$start);
			$bpi = $this->model_pembelian_invoicepembelianimport->getpemb($filter_no_po);
			foreach($results as $r){
				$rests=array(
					'biaya' => $bpi,
					'id'	=> $r['id'],
					//'text'	=> $r['no_po'].' Total $'.number_format($r['total_pembelian'])
					'text'	=> $r['no_faktur']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}



	public function autocompletepembayaran(){
		$rests = array();

		$this->load->model('pembelian/invoicepembelianimport');

			if (isset($this->request->get['q'])) {
				$filter_no_po = $this->request->get['q'];
			} else {
				$filter_no_po = '';
			}
			if (isset($this->request->get['f'])) {
				$filter_faktur = $this->request->get['f'];
			} else {
				$filter_faktur = null;
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_faktur'         => array('LIKE',$filter_no_po),
				'status'	=> array('<',3)
			);

			$start=0;
			$limit=0;
			$column=array('id','no_faktur','totaltagihan');
			$join=array();
			$leftjoin=array();

			$results = $this->model_pembelian_invoicepembelianimport->getPermintaanPembelians($column,$join,$join,$data,array(),$limit,$start);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					//'text'	=> $r['no_po'].' Total $'.number_format($r['total_pembelian'])
					'text'	=> $r['no_faktur'].'- Total Tagihan '
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function autocompletepib(){
		$rests = array();

		$this->load->model('pembelian/invoicepembelianimport');

			if (isset($this->request->get['q'])) {
				$filter_no_po = $this->request->get['q'];
			} else {
				$filter_no_po = '';
			}
			if (isset($this->request->get['f'])) {
				$filter_faktur = $this->request->get['f'];
			} else {
				$filter_faktur = null;
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_faktur'         => array('LIKE',$filter_no_po),
				'status'	=> array('<>',3),
				'statuspembayaranpib'	=> array('<>',1)
			);

			$start=0;
			$limit=0;
			$column=array('id','no_faktur','no_pib','ppnpib','pphpib','bmpib');
			$join=array();
			$leftjoin=array();

			$results = $this->model_pembelian_invoicepembelianimport->getPermintaanPembelians($column,$join,$join,$data,array(),$limit,$start);
			foreach($results as $r){
				$totalpib=$r['ppnpib']+$r['pphpib']+$r['bmpib'];
				$rests[]=array(
					'id'	=> $r['id'],
					//'text'	=> $r['no_po'].' Total $'.number_format($r['total_pembelian'])
					'text'	=> $r['no_faktur'].' ('.$r['no_pib'].') - Total Tagihan '.$this->currency->format($totalpib)
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function detail(){
		$hasil = array();

		$this->load->model('pembelian/permintaanpembelian');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			$this->load->model('pembelian/invoicepembelianimport');
			$column=array('invoice_pembelian_import.*');
			$join=array();


			$data = array(
				'invoice_pembelian_import.id'	=> $id,
	      'invoice_pembelian_import.hapus'	=> 0,
				'invoice_pembelian_import.status'	=> array('<',3)
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);

			$hasil=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelian($column,$join,$data);
		}
	}
		$this->response->setOutput(json_encode($hasil));


	}

	public function detailbiaya(){
		$hasil = array();


		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$jenisbiaya_id=$this->request->get['id'];
				$order_id=$this->request->get['invoice_id'];
			$this->load->model('pembelian/invoicepembelianimport');
			/*
			0 belum ada
			1 ditagih
			2 dibayar sebagian
			3 Lunas
			4 Dibatalkan
			*/

			$data = array(
				'jenisbiaya_id'	=> $jenisbiaya_id,
	      'order_id'	=> $order_id,
			//	'invoice_pembelian_import.statuspembayaran'	=> array('<',3)
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);

			$hasil=$this->model_pembelian_invoicepembelianimport->getBiaya($data);
		}
	}
		$this->response->setOutput(json_encode($hasil));


	}

	public function detailpib(){
		$hasil = array();

		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			$this->load->model('pembelian/invoicepembelianimport');
			$column=array('invoice_pembelian_import.*');
			$join=array();


			$data = array(
				'invoice_pembelian_import.id'	=> $id,
	      'invoice_pembelian_import.hapus'	=> 0,
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);

			$inv=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelian($column,$join,$data);
			$hasil['no_pib']=$inv['no_pib'];
			$hasil['ppnpib']=$this->currency->format($inv['ppnpib']);
			$hasil['pphpib']=$this->currency->format($inv['pphpib']);
			$hasil['bmpib']=$this->currency->format($inv['bmpib']);
			$hasil['totalpib']=$this->currency->format($inv['ppnpib']+$inv['pphpib']+$inv['bmpib']);
			$totalbayar=$this->model_pembelian_invoicepembelianimport->totalbayarpib($id);
			$hasil['totalbayar']=$this->currency->format($totalbayar);
			$hasil['plaintotal']=$inv['ppnpib']+$inv['pphpib']+$inv['bmpib'];
			$hasil['plaintotalbayar']=$totalbayar;
			$hasil['plainsisa']=$inv['ppnpib']+$inv['pphpib']+$inv['bmpib']-$totalbayar;
			$hasil['sisa']=$this->currency->format($inv['ppnpib']+$inv['pphpib']+$inv['bmpib']-$totalbayar);
		}
	}
		$this->response->setOutput(json_encode($hasil));


	}

	public function biaya(){
		$this->document->setTitle('Biaya Pembelian Import');
		$url='';
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/invoicepembelianimport');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_pembelian_invoicepembelianimport->koreksibiaya($this->request->post);

			$this->session->data['success'] = 'Sukses: Data biaya berhasil diperbarui';



			$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$column=array('invoice_pembelian_import.*','vendorimport.name');
		$join=array();

		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'invoice_pembelian_import.vendor_id'
		);

		$data = array(
			'invoice_pembelian_import.id'	=> $id,
      'invoice_pembelian_import.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelian($column,$join,$data);
		if($trans['statuspenerimaan'] == 1){
			$this->session->data['warning'] = 'Perhatian: Estimasi Biaya Tidak Dapat Ditambahkan karena barang telah diterima';



			$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
        
        $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
        
		$biayas=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelianBiaya(array('order_id'	=> $id));
		$this->data['permintaan']=$trans;
		$this->data['biayas']=$biayas;
		$this->data['cancel']= $this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']=$this->url->link('pembelian/invoicepembelianimport/biaya', 'token=' . $this->session->data['token'] . '&id='.$this->request->get['id'].$url, 'SSL');
		$this->template = 'pembelian/pembelianimport_biaya.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function pib(){
		$this->document->setTitle('Pemberitahuan Import Barang');
		$url='';
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/invoicepembelianimport');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_pembelian_invoicepembelianimport->addPib($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Pemberitahuan Import Barang berhasil diperbarui';



			$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$column=array('invoice_pembelian_import.*','vendorimport.name');
		$join=array();

		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'invoice_pembelian_import.vendor_id'
		);

		$data = array(
			'invoice_pembelian_import.id'	=> $id,
      'invoice_pembelian_import.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelian($column,$join,$data);
		if($trans['statuspembayaranpib'] == 1){
			$this->session->data['warning'] = 'Perhatian: Pemberatuhan Import Barang telah dibuat atau dibayar';



			$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->data['permintaan']=$trans;
        
        $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
        
		$this->data['cancel']= $this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']=$this->url->link('pembelian/invoicepembelianimport/pib', 'token=' . $this->session->data['token'] . '&id='.$this->request->get['id'].$url, 'SSL');
		$this->template = 'pembelian/pembelianimport_pib.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function batalkanpib(){
		$this->document->setTitle('Pemberitahuan Import Barang');
		$url='';
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/invoicepembelianimport');

		/*if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_pembelian_invoicepembelianimport->addPib($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Pemberitahuan Import Barang berhasil diperbarui';



			$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}*/

		$column=array('invoice_pembelian_import.*','vendorimport.name');
		$join=array();

		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'invoice_pembelian_import.vendor_id'
		);

		$data = array(
			'invoice_pembelian_import.id'	=> $id,
      'invoice_pembelian_import.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelian($column,$join,$data);
		if($trans['statuspembayaranpib'] == 1){
			$this->session->data['warning'] = 'Perhatian: Pemberitahuan Import Barang tidak dapat dibatalkan karena telah dilakukan pembayaran';



			$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}else{
			$data['id']=$id;
			$this->model_pembelian_invoicepembelianimport->batalPib($data);

			$this->session->data['success'] = 'Sukses: Data Pemberitahuan Import Barang berhasil dibatalkan';



			$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

	}

	public function kurs(){
		$this->document->setTitle('Kurs Tengah BI');
		$url='';
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/invoicepembelianimport');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_pembelian_invoicepembelianimport->addKurs($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Kurs Barang Datang berhasil diperbarui';



			$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$column=array('invoice_pembelian_import.*','vendorimport.name');
		$join=array();

		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'invoice_pembelian_import.vendor_id'
		);

		$data = array(
			'invoice_pembelian_import.id'	=> $id,
      'invoice_pembelian_import.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelian($column,$join,$data);
		if($trans['statuspenerimaan'] == 1 ){
			$this->session->data['warning'] = 'Perhatian: Kurs Barang Datang tidak dapat diubah karena barang telah diterima';



			$this->redirect($this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
        
        $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
        
		$this->data['permintaan']=$trans;
		$this->data['cancel']= $this->url->link('pembelian/invoicepembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']=$this->url->link('pembelian/invoicepembelianimport/kurs', 'token=' . $this->session->data['token'] . '&id='.$this->request->get['id'].$url, 'SSL');
		$this->template = 'pembelian/pembelianimport_kurs.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}


}
?>
