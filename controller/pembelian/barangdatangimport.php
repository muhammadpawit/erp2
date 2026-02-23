<?php
class ControllerPembelianBarangdatangimport extends Controller {
	private $error=array();


	public function index() {
		$this->document->setTitle('Barang Datang Pembelian Import');

		if (isset($this->request->get['filter_no_faktur'])) {
			$filter_no_faktur = $this->request->get['filter_no_faktur'];
		} else {
			$filter_no_faktur =null;
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

		if (isset($this->request->get['date_terima_start'])) {
			$date_terima_start = $this->request->get['date_terima_start'];
		} else {
			$date_terima_start =null;
		}

		if (isset($this->request->get['date_terima_end'])) {
			$date_terima_end = $this->request->get['date_terima_end'];
		} else {
			$date_terima_end = null;
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

		if (isset($this->request->get['date_terima_start'])) {
			$url .= '&date_terima_start=' . $this->request->get['date_terima_start'];
		}

		if (isset($this->request->get['date_terima_end'])) {
			$url .= '&date_terima_end=' . $this->request->get['date_terima_end'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('pembelian/barangdatangimport/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		/*$this->load->model('report/product');
        $this->load->model('catalog/product');
		*/

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

		/*$this->load->model('catalog/product');
		$this->load->model('gudang/product');
		$this->load->model('pembelian/permintaanpembelian');
		$this->load->model('catalog/gudang');
		*/

		$this->load->model('catalog/vendorimport');
		$this->load->model('pembelian/invoicepembelianimport');

		$this->data['permintaans'] = array();
		$column=array('invoice_pembelian_import.*','vendorimport.name','gudang.nama');
		// baru test $column=array('invoice_pembelian_import.*','vendorimport.name','gudang.nama','suratjalan_pembelianimport.tgl_terima');
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
		
		/*$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'suratjalan_pembelianimport',
			'secondtable'	=>'suratjalan_pembelianimport.pembelian_import_id',
			'firsttable'	=> 'invoice_pembelian_import.id'
		);*/

		$data = array(
			//'no_po'      =>array('LIKE',$filter_no_po),
			'invoice_pembelian_import.id'      =>$filter_no_faktur,
			'vendor_id'=> $filter_vendor,
			//'surat_id'=> $filter_no_surat,
			'jenisproduk'	=> $filter_jenis_barang,
      		'invoice_pembelian_import.status'	=> empty($filter_status)?array('<>',3):$filter_status,
			'invoice_pembelian_import.statuspenerimaan'	=> empty($filter_status_penerimaan)?array('<=',3):$filter_status_penerimaan,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$limit=0;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		if(!empty($filter_date_end)){
			$data['invoice_pembelian_import.tglfaktur']=array('>=',$filter_date_start,'<=',$filter_date_end);
			//$data['.tgl_bayar']=array('>=',$date_terima_start,'<=',$filter_date_end);
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

		$product_total = $this->model_pembelian_invoicepembelianimport->totalPermintaans($data);

		$results = $this->model_pembelian_invoicepembelianimport->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";
			print_r($results);
			exit;
		}
		foreach ($results as $result) {
			$action = array();
			$tglterima = $this->gettglterima($result['id']);
			if(!empty($tglterima['tgl_terima'])){
				$tglterimas = date('Y-m-d',strtotime($tglterima['tgl_terima']));
			}else{
				$tglterimas='belum diterima';
			}
			
			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('pembelian/barangdatangimport/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			if(($result['statuspenerimaan'] == 0 | $result['statuspenerimaan'] == 2)  & $result['inputkursdatang'] == 1 & $result['inputpib'] == 1 & $result['status'] == 4){
				$action[] = array(
					'text' => 'Terima Barang',
					'href' => $this->url->link('pembelian/barangdatangimport/barangdatang', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}

			$gudang="";
			if($result['gudang_id'] > 0){
				$g=$this->model_catalog_gudang->getGudang($result['gudang_id']);
				$gudang=$g['nama'];
			}
			if($date_terima_start==null){
				if($filter_date_start==null){
					if( $result['tglfaktur'] >= date('Y-m-d',strtotime('first day of previous month')) ){
						$this->data['permintaans'][] = array(
							'tanggal'	=> date('d/m/y',strtotime($result['tglfaktur'])),
							'tgl_terima'	=> $tglterimas,
							'name'	=> $result['name'],
							'gudang'	=> $result['nama'],
							'no_faktur'	=> $result['no_faktur'],
							'id'	=> $result['id'],
							'jenis_barang'	=> $result['jenisproduk'],
							'statuspenerimaan'	=> $result['statuspenerimaan'] == 0?'Belum Diterima':($result['statuspenerimaan'] == 2?'Diterima Sebagian':'Sudah Diterima'),
							'actions'	=> $action
						);
					}
				}else{
					if( date('Y-m-d',strtotime($result['tglfaktur'])) >= $filter_date_start && date('Y-m-d',strtotime($result['tglfaktur'])) <= $filter_date_end ){
						$this->data['permintaans'][] = array(
							'tanggal'	=> date('d/m/y',strtotime($result['tglfaktur'])),
							'tgl_terima'	=> $tglterimas,
							'name'	=> $result['name'],
							'gudang'	=> $result['nama'],
							'no_faktur'	=> $result['no_faktur'],
							'id'	=> $result['id'],
							'jenis_barang'	=> $result['jenisproduk'],
							'statuspenerimaan'	=> $result['statuspenerimaan'] == 0?'Belum Diterima':($result['statuspenerimaan'] == 2?'Diterima Sebagian':'Sudah Diterima'),
							'actions'	=> $action
						);
					}
				}
			}else{
				if($tglterimas>=$date_terima_start && $tglterimas <= $date_terima_end){
					$this->data['permintaans'][] = array(
						'tanggal'	=> date('d/m/y',strtotime($result['tglfaktur'])),
						'tgl_terima'	=> $tglterimas,
						'name'	=> $result['name'],
						'gudang'	=> $result['nama'],
						'no_faktur'	=> $result['no_faktur'],
						'id'	=> $result['id'],
						'jenis_barang'	=> $result['jenisproduk'],
						'statuspenerimaan'	=> $result['statuspenerimaan'] == 0?'Belum Diterima':($result['statuspenerimaan'] == 2?'Diterima Sebagian':'Sudah Diterima'),
						'actions'	=> $action
					);
				}
			}
			if($filter_no_faktur!=null OR $filter_status!=null){
				$this->data['permintaans'][] = array(
					'tanggal'	=> date('d/m/y',strtotime($result['tglfaktur'])),
					'tgl_terima'	=> $tglterimas,
					'name'	=> $result['name'],
					'gudang'	=> $result['nama'],
					'no_faktur'	=> $result['no_faktur'],
					'id'	=> $result['id'],
					'jenis_barang'	=> $result['jenisproduk'],
					'statuspenerimaan'	=> $result['statuspenerimaan'] == 0?'Belum Diterima':($result['statuspenerimaan'] == 2?'Diterima Sebagian':'Sudah Diterima'),
					'actions'	=> $action
				);
			}
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

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}


		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['date_terima_start'])) {
			$url .= '&date_terima_start=' . $this->request->get['date_terima_start'];
		}

		if (isset($this->request->get['date_terima_end'])) {
			$url .= '&date_terima_end=' . $this->request->get['date_terima_end'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pembelian/barangdatangimport', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_faktur'] = $filter_no_faktur;
		$this->data['filter_no_po'] = $filter_no_po;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_vendor'] = $filter_vendor;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['date_terima_start'] = $date_terima_start;
		$this->data['date_terima_end'] = $date_terima_end;

		$this->template = 'pembelian/barangdatangimport_new.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	// baru 28 Agutus 2019 
	public function gettglterima($id){
		$d  = $this->db->query("SELECT * FROM suratjalan_pembelianimport WHERE pembelian_import_id='$id' ");
		return $d->row;
	}
	// end baru

	public function tampil(){
		$this->document->setTitle('Barang Datang Pembelian Lokal');
		$url = '';

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

		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/barangdatangimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/barangdatangimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/barangdatangimport');

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

		$column=array('suratjalan_pembelianimport.no_suratjalan','suratjalan_pembelianimport.penerima_id','suratjalan_pembelianimport.pengangkut_id','suratjalan_pembelianimport.tgl_surat','suratjalan_pembelianimport.tgl_terima','suratjalan_pembelianimport.date_added','suratjalan_pembelianimport.no_pol','invoice_pembelian_import.no_faktur','suratjalan_produkimport.quantity as qtyterima','invoice_pembelian_import_product.*');
		$join=array();
		$join[]=array(
			'tablename'	=> 'invoice_pembelian_import',
			'firsttable'	=>'suratjalan_pembelianimport.pembelian_import_id',
			'secondtable'	=> 'invoice_pembelian_import.id'
		);
		$join[]=array(
			'tablename'	=> 'suratjalan_produkimport',
			'firsttable'	=>'suratjalan_pembelianimport.id',
			'secondtable'	=> 'suratjalan_produkimport.id_suratjalan'
		);
		$join[]=array(
      'tablename' => 'invoice_pembelian_import_product',
      'firsttable'  => 'suratjalan_produkimport.pembelian_product_id',
      'secondtable' => 'invoice_pembelian_import_product.id'
    );

	/*	$join[]=array(
			'tablename'	=> 'users',
			'firsttable'	=>'suratjalan_pembelian.penerima_id',
			'secondtable'	=> 'suratjalan_produk.id_suratjalan'
		);
*/
		$data = array(
			'suratjalan_pembelianimport.pembelian_import_id'	=> $id,
      'suratjalan_pembelianimport.hapus'	=> array('<',1),
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$prods=$this->model_pembelian_barangdatangimport->getPermintaanPembelianFull($column,$join,$data);
		//$prods=$this->model_pembelian_barangdatang->getPermintaanPembelianProduct(array('id_suratjalan'	=> $id));
		//print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";
			print_r($prods);
			exit;
		}
		$this->data['cancel']= $this->url->link('pembelian/barangdatangimport', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['cetak']= $this->url->link('pembelian/barangdatangimport/cetak', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['cetak']= $this->url->link('pembelian/barangdatangimport/cetak', 'id='.$id. '&token=' . $this->session->data['token'] . $url, 'SSL');
		$this->template = 'pembelian/barangdatangimport_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	public function cetak(){
		$this->document->setTitle('Barang Datang Pembelian Import');
		$url = '';

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

		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/barangdatangimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/barangdatangimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/barangdatangimport');

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

		$column=array('suratjalan_pembelianimport.no_suratjalan','suratjalan_pembelianimport.penerima_id','suratjalan_pembelianimport.pengangkut_id','suratjalan_pembelianimport.tgl_surat','suratjalan_pembelianimport.tgl_terima','suratjalan_pembelianimport.date_added','suratjalan_pembelianimport.no_pol','invoice_pembelian_import.no_faktur','suratjalan_produkimport.quantity as qtyterima','invoice_pembelian_import_product.*');
		$join=array();
		$join[]=array(
			'tablename'	=> 'invoice_pembelian_import',
			'firsttable'	=>'suratjalan_pembelianimport.pembelian_import_id',
			'secondtable'	=> 'invoice_pembelian_import.id'
		);
		$join[]=array(
			'tablename'	=> 'suratjalan_produkimport',
			'firsttable'	=>'suratjalan_pembelianimport.id',
			'secondtable'	=> 'suratjalan_produkimport.id_suratjalan'
		);
		$join[]=array(
      'tablename' => 'invoice_pembelian_import_product',
      'firsttable'  => 'suratjalan_produkimport.pembelian_product_id',
      'secondtable' => 'invoice_pembelian_import_product.id'
    );

	/*	$join[]=array(
			'tablename'	=> 'users',
			'firsttable'	=>'suratjalan_pembelian.penerima_id',
			'secondtable'	=> 'suratjalan_produk.id_suratjalan'
		);
*/
		$data = array(
			'suratjalan_pembelianimport.pembelian_import_id'	=> $id,
      'suratjalan_pembelianimport.hapus'	=> array('<',1),
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$prods=$this->model_pembelian_barangdatangimport->getPermintaanPembelianFull($column,$join,$data);
		//$prods=$this->model_pembelian_barangdatang->getPermintaanPembelianProduct(array('id_suratjalan'	=> $id));
		//print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['cancel']= $this->url->link('pembelian/barangdatangimport', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['cetak']= $this->url->link('pembelian/barangdatangimport/cetak', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/barangdatangimport_cetak.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function barangdatang(){
		$this->load->model('pembelian/invoicepembelianimport');
		$this->document->setTitle('Pembelian Import');
		$url = '';

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
		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			
      		$no_po=$this->model_pembelian_invoicepembelianimport->barangdatang($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Barang Datang berhasil disimpan';



			$this->redirect($this->url->link('pembelian/barangdatangimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/barangdatangimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/barangdatangimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->data['action']= $this->url->link('pembelian/barangdatangimport/barangdatang', 'token=' . $this->session->data['token'] . $url, 'SSL');


		$this->load->model('catalog/gudang');

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

			if($trans['inputkursdatang'] != 1 & $trans['inputpib'] != 1 & $trans['status'] != 4){
				$this->redirect($this->url->link('pembelian/barangdatangimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}

		$prods=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelianProduct(array('invoice_id'	=> $id));
		$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
	//	print_r($prods);
	if($trans['jenisproduk'] == 1){
		$this->load->model('catalog/bahanbaku');
		$this->load->model('produksi/bukaproduksi');

		$this->data['cek']=$this->model_produksi_bukaproduksi->getPermintaanPembelian(array(),array(),array('status' => 1));
		if(!empty($this->data['cek'])){
			$this->session->data['warning'] = 'Peringatan: Mohon tutup proses produksi sebelum pemrosesan barang datang.';
			$this->redirect($this->url->link('pembelian/barangdatangimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));

		}

		$nprods=array();
		$i=1;
		foreach($prods as $p){
			$bb=$this->model_catalog_bahanbaku->getProduct($p['product_id']);
			$nprods[$i]=$p;
			$nprods[$i]['detail']=$bb;
			$i++;
		}
		$prods=$nprods;
	}
		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['gudang']=$gudang;
		$this->data['cancel']= $this->url->link('pembelian/barangdatangimport', 'token=' . $this->session->data['token'] . $url, 'SSL');
		if($this->user->getusername()=="pawitx"){
			echo "<pre>";print_r($prods);exit;
		}
		$this->template = 'pembelian/barangdatangimport_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

}
?>
