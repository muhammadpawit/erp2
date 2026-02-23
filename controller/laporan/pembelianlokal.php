<?php
class ControllerLaporanPembelianLokal extends Controller {
	private $error=array();
	// baru 30 Januari 2020
	public function exporttoexcel() {
		$this->document->setTitle('Penerimaan Pembelian Produk Dagang');

		if (isset($this->request->get['filter_no_faktur'])) {
			$filter_no_faktur = $this->request->get['filter_no_faktur'];
		} else {
			$filter_no_faktur = '';
		}
		if (isset($this->request->get['filter_no_po'])) {
			$filter_no_po = $this->request->get['filter_no_po'];
		} else {
			$filter_no_po = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '*';
		}

		if (isset($this->request->get['filter_no_surat'])) {
			$filter_no_surat = $this->request->get['filter_no_surat'];
		} else {
			$filter_no_surat = '';
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

		$url = '';
		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
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

		$this->data['insert'] = $this->url->link('pembelian/terimabarangdagang/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['exporttoexcel'] = $this->url->link('pembelian/terimabarangdagang/exporttoexcel', 'token=' . $this->session->data['token'].$url, 'SSL');

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

		$this->load->model('catalog/vendorlokal');
		$this->load->model('pembelian/pembeliankreditdagang');

		$this->data['permintaans'] = array();
		//$column=array('pembelian_kreditdagang.*','pembelian_produk_kreditdagang.product_name','pembelian_produk_kreditdagang.quantity','pembelian_produk_kreditdagang.quantityterima','vendorlokal.name','gudang.nama','pembelian_produk_kreditdagang.status AS statusproduk');
		//$column=array('pembelian_import.*','pembelian_produk_import.product_name','pembelian_produk_import.quantity','pembelian_produk_import.quantity_invoice','pembelian_produk_import.invoice_id','invoice_pembelian_import.no_faktur','permintaan_pembelian.no_surat','vendorimport.name','gudang.nama');

		$column=array('suratjalan_pembeliandagang.*','product.name','vendorlokal.name as vendor','gudang.nama','suratjalan_produkdagang.quantity','pembelian_kreditdagang.no_po','pembelian_produk_kreditdagang.harga');
		$join=array();
		$join[]=array(
			'tablename'	=> 'suratjalan_produkdagang',
			'secondtable'	=>'suratjalan_produkdagang.id_suratjalan',
			'firsttable'	=> 'suratjalan_pembeliandagang.id'
		);


		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'secondtable'	=>'gudang.gudang_id',
			'firsttable'	=> 'suratjalan_pembeliandagang.gudang_id'
		);

		$leftjoin[]=array(
			'tablename'	=> 'pembelian_produk_kreditdagang',
			'secondtable'	=>'pembelian_produk_kreditdagang.id',
			'firsttable'	=> 'suratjalan_produkdagang.pembelian_product_id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'pembelian_kreditdagang',
			'secondtable'	=>'pembelian_kreditdagang.id',
			'firsttable'	=> 'suratjalan_produkdagang.po_id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'pembelian_kreditdagang.vendor_id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'product',
			'secondtable'	=>'product.product_id',
			'firsttable'	=> 'pembelian_produk_kreditdagang.product_id'
		);



		$data = array(
			'pembelian_kreditdagang.no_po'      =>array('LIKE',$filter_no_po),
			//'no_faktur'      =>array('LIKE',$filter_no_faktur),
			'pembelian_kreditdagang.vendor_id'=> $filter_vendor,
			'suratjalan_pembeliandagang.no_suratjalan'=> array('LIKE',$filter_no_surat),
			'pembelian_kreditdagang.status'	=> array('<>',3),
			'suratjalan_pembeliandagang.hapus'	=> array('=',0),
			//'suratjalan_pembeliandagang.status'=> array('<>',3),
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		if($filter_status != '*'){
			if($filter_status == 0){
				$data['suratjalan_pembeliandagang.status']=array('<>',3);
			}else{
				$data['suratjalan_pembeliandagang.status']=$filter_status;
			}
		}else{
				$data['suratjalan_pembeliandagang.status']=array('<>',3);
		}

		if(!empty($filter_date_end)){
			$data['suratjalan_pembeliandagang.date_added']=array('>=',$filter_date_start,'<=',$filter_date_end);
		}else{
			$data['suratjalan_pembeliandagang.date_added']=array('>=',$filter_date_start);
		}

		if($filter_date_start == '1970-01-01'){
			$filter_date_start=null;
		}

		$limit=0;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'suratjalan_pembeliandagang.date_added'	=> 'DESC',
			'suratjalan_pembeliandagang.id'	=> 'DESC',
			'suratjalan_pembeliandagang.status'	=> 'ASC'
		);

		$this->load->model('catalog/gudang');

		$product_total = $this->model_pembelian_pembeliankreditdagang->totalBarangdatangs($data,$join,$leftjoin);

		$results = $this->model_pembelian_pembeliankreditdagang->getBarangdatangs($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();

			if($result['status'] == 1){
				$action[] = array(
					'text' => 'Terima',
					'href' => $this->url->link('pembelian/terimabarangdagang/terima', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}
			
			$action[] = array(
					'text' => 'Tampil',
					'href' => $this->url->link('pembelian/terimabarangdagang/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);


			$this->data['permintaans'][] = array(
				'vendor'	=> $result['vendor'],
				'nama'	=> $result['nama'],
				'no_po'	=> $result['no_po'],
				'no_dokumen' => empty($result['no_dokumen'])?'-':$result['no_dokumen'],
				'no_suratjalan'	=> $result['no_suratjalan'],
				'id'	=> $result['id'],
				'tanggal'	=> date('d/m/y',strtotime($result['date_added'])),
				'tgl_terima'	=> date('d/m/y',strtotime($result['tgl_terima'])),
				'jenis_barang'	=> $result['jenis_barang'],
				'status'	=> $result['status'] == 1?'Belum Diterima':($result['status'] == 2?'Diterima':'Dibatalkan'),
				'product_name'=> $result['name'],
				'quantity'	=> $result['quantity'],
				'harga'	=> $this->currency->format($result['harga']),
				//'quantityterima'	=> $result['quantity'],
			//	'quantityterima'	=> $result['quantityterima'],
				'actions'	=> $action
			);
		}
		
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";print_r($this->data['permintaans']);exit;
		}

		$this->data['heading_title'] = 'Penerimaan Barang Pembelian Produk Dagang';

		$this->data['token'] = $this->session->data['token'];
		$url = '';

		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pembelian/terimabarangdagang', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_no_faktur'] = $filter_no_faktur;
		$this->data['filter_no_po'] = $filter_no_po;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_vendor'] = $filter_vendor;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;

		$this->template = 'pembelian/terimabarangdagang_excel.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	// end baru
	// baru 11 Desember 2019
	public function jurnal(){
		$id = $this->request->get['id'];
		$sql="SELECT * FROM jurnal_umum WHERE type=6 and ref='".$id."' or linkterkait ='".$id."'";
		$j = $this->db->query($sql);
		$jd = $this->db->query("SELECT * FROM jurnal_umum_detail WHERE jurnal_id='".$j->row['id']."' order by urutan asc");
		$linkterkait = ($j->row['linkterkait']==null)?$j->row['ref']:$j->row['linkterkait'];
		echo "<table class='table table-bordered'>";
		echo "<tr align='center'>";
		echo "<td><b>Tanggal</b></td>";
		echo "<td><b>Ref</b></td>";
		echo "<td><b>Keterangan</b></td>";
		echo "<td colspan='2'><b>Debet</b></td>";
		echo "<td colspan='2'><b>Kredit</b></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td></td><td></td><td></td><td><b>ref akun</b></td><td></td><td><b>ref akun</b></td><td></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>".date('d/m/Y',strtotime($j->row['tanggal']))."</td><td>".$j->row['ref'].' '.$linkterkait."</td><td>".$j->row['keterangan']."</td><td></td><td></td><td></td><td></td>";
		foreach($jd->rows as $detail  ){
			echo "<tr>";
			echo "<td></td><td></td>";
			if($detail['debet']>0){
			echo "<td>".$detail['keterangan']."</td>";
			echo "<td>".$detail['ref_akun']."</td>";
			echo "<td>".$this->currency->format($detail['debet'])."</td>";
			echo "<td></td>";
			echo "<td></td>";
			}
			if($detail['kredit']>0){
			echo "<td>".$detail['keterangan']."</td>";
			echo "<td></td>";
			echo "<td></td>";
			echo "<td>".$detail['ref_akun']."</td>";
			echo "<td>".$this->currency->format($detail['kredit'])."</td>";			
			}			
			echo "</tr>";
		}
		echo "</tr>";
		/**/
		echo "<table>";
	}
	// end baru
	public function index() {
		$this->document->setTitle('Penerimaan Pembelian Produk Dagang');
		if (isset($this->request->get['filter_no_faktur'])) {
			$filter_no_faktur = $this->request->get['filter_no_faktur'];
		} else {
			$filter_no_faktur = '';
		}
		if (isset($this->request->get['filter_no_po'])) {
			$filter_no_po = $this->request->get['filter_no_po'];
		} else {
			$filter_no_po = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '*';
		}

		if (isset($this->request->get['filter_no_surat'])) {
			$filter_no_surat = $this->request->get['filter_no_surat'];
		} else {
			$filter_no_surat = '';
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

		$url = '';
		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
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

		$this->data['insert'] = $this->url->link('pembelian/terimabarangdagang/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['exporttoexcel'] = $this->url->link('laporan/pembelianlokal/exporttoexcel', 'token=' . $this->session->data['token'].$url, 'SSL');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('catalog/vendorlokal');
		$this->load->model('pembelian/pembeliankreditdagang');

		$this->data['permintaans'] = array();
		$column=array('suratjalan_pembeliandagang.*','product.name','vendorlokal.name as vendor','gudang.nama','suratjalan_produkdagang.quantity','pembelian_kreditdagang.no_po','pembelian_produk_kreditdagang.harga');
		$join=array();
		$join[]=array(
			'tablename'	=> 'suratjalan_produkdagang',
			'secondtable'	=>'suratjalan_produkdagang.id_suratjalan',
			'firsttable'	=> 'suratjalan_pembeliandagang.id'
		);


		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'secondtable'	=>'gudang.gudang_id',
			'firsttable'	=> 'suratjalan_pembeliandagang.gudang_id'
		);

		$leftjoin[]=array(
			'tablename'	=> 'pembelian_produk_kreditdagang',
			'secondtable'	=>'pembelian_produk_kreditdagang.id',
			'firsttable'	=> 'suratjalan_produkdagang.pembelian_product_id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'pembelian_kreditdagang',
			'secondtable'	=>'pembelian_kreditdagang.id',
			'firsttable'	=> 'suratjalan_produkdagang.po_id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'pembelian_kreditdagang.vendor_id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'product',
			'secondtable'	=>'product.product_id',
			'firsttable'	=> 'pembelian_produk_kreditdagang.product_id'
		);

		$data = array(
			'pembelian_kreditdagang.no_po'      =>array('LIKE',$filter_no_po),
			//'no_faktur'      =>array('LIKE',$filter_no_faktur),
			'pembelian_kreditdagang.vendor_id'=> $filter_vendor,
			'suratjalan_pembeliandagang.no_suratjalan'=> array('LIKE',$filter_no_surat),
			'pembelian_kreditdagang.status'	=> array('<>',3),
			'suratjalan_pembeliandagang.hapus'	=> array('=',0),
			//'suratjalan_pembeliandagang.status'=> array('<>',3),
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		if($filter_status != '*'){
			if($filter_status == 0){
				$data['suratjalan_pembeliandagang.status']=array('<>',3);
			}else{
				$data['suratjalan_pembeliandagang.status']=$filter_status;
			}
		}else{
				$data['suratjalan_pembeliandagang.status']=array('<>',3);
		}

		if(!empty($filter_date_end)){
			$data['suratjalan_pembeliandagang.date_added']=array('>=',$filter_date_start,'<=',$filter_date_end);
		}else{
			$data['suratjalan_pembeliandagang.date_added']=array('>=',$filter_date_start);
		}

		if($filter_date_start == '1970-01-01'){
			$filter_date_start=null;
		}

		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'suratjalan_pembeliandagang.date_added'	=> 'DESC',
			'suratjalan_pembeliandagang.id'	=> 'DESC',
			'suratjalan_pembeliandagang.status'	=> 'ASC'
		);

		$this->load->model('catalog/gudang');

		$product_total = $this->model_pembelian_pembeliankreditdagang->totalBarangdatangs($data,$join,$leftjoin);

		$results = $this->model_pembelian_pembeliankreditdagang->getBarangdatangs($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();

			if($result['status'] == 1){
			}
			
			$action[] = array(
					'text' => 'Tampil',
					'href' => $this->url->link('laporan/pembelianlokal/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);


			$this->data['permintaans'][] = array(
				'vendor'	=> $result['vendor'],
				'nama'	=> $result['nama'],
				'no_po'	=> $result['no_po'],
				'no_dokumen' => empty($result['no_dokumen'])?'-':$result['no_dokumen'],
				'no_suratjalan'	=> $result['no_suratjalan'],
				'id'	=> $result['id'],
				'tanggal'	=> date('d/m/y',strtotime($result['date_added'])),
				'tgl_terima'	=> date('d/m/y',strtotime($result['tgl_terima'])),
				'jenis_barang'	=> $result['jenis_barang'],
				'status'	=> $result['status'] == 1?'Belum Diterima':($result['status'] == 2?'Diterima':'Dibatalkan'),
				'product_name'=> $result['name'],
				'quantity'	=> $result['quantity'],
				'harga'	=> $this->currency->format($result['harga']),
				//'quantityterima'	=> $result['quantity'],
			//	'quantityterima'	=> $result['quantityterima'],
				'actions'	=> $action
			);
		}
		
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";print_r($this->data['permintaans']);exit;
		}

		$this->data['heading_title'] = 'Penerimaan Barang Pembelian Produk Dagang';

		$this->data['token'] = $this->session->data['token'];
		$url = '';

		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/pembelianlokal', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_no_faktur'] = $filter_no_faktur;
		$this->data['filter_no_po'] = $filter_no_po;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_vendor'] = $filter_vendor;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;

		$this->template = 'laporan/terimabarangdaganglokal.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}



	public function terima(){
		$this->document->setTitle('Barang Datang Pembelian Produk Dagang');
		$this->load->model('pembelian/barangdatangdagang');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_pembelian_barangdatangdagang->terimabarangdatang($this->request->post,$this->request->get['id']);

			$this->session->data['success'] = 'Sukses: Pembelian barang dagang berhasil diterima';

			$url = '';

			if (isset($this->request->get['filter_no_po'])) {
				$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
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

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('pembelian/terimabarangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$url = '';

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}

		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
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
				$this->redirect($this->url->link('pembelian/terimabarangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/terimabarangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/barangdatangdagang');

		$this->load->model('pembelian/pembeliankreditdagang');

		$column=array('suratjalan_pembeliandagang.*','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'gudang',
			'secondtable'	=>'gudang.gudang_id',
			'firsttable'	=> 'suratjalan_pembeliandagang.gudang_id'
		);
		/*$join[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'pembelian_kreditdagang.vendor_id'
		);*/

		$data = array(
			'suratjalan_pembeliandagang.id'	=> $id,
      //'permintaan_pembelian.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_barangdatangdagang->getPermintaanPembelian($column,$join,$data);

		if($trans['status'] != 1){
			$this->redirect($this->url->link('pembelian/terimabarangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$column=array('pembelian_kreditdagang.no_po','suratjalan_produkdagang.quantity as qtyterima','pembelian_produk_kreditdagang.*');
		$join=array();

		/*$join[]=array(
			'tablename'	=> 'suratjalan_produk',
			'firsttable'	=>'suratjalan_pembelian.id',
			'secondtable'	=> 'suratjalan_produk.id_suratjalan'
		);*/
		$join[]=array(
			'tablename'	=> 'pembelian_kreditdagang',
			'firsttable'	=>'suratjalan_produkdagang.po_id',
			'secondtable'	=> 'pembelian_kreditdagang.id'
		);
		$join[]=array(
      'tablename' => 'pembelian_produk_kreditdagang',
      'firsttable'  => 'suratjalan_produkdagang.pembelian_product_id',
      'secondtable' => 'pembelian_produk_kreditdagang.id'
    );

	/*	$join[]=array(
			'tablename'	=> 'users',
			'firsttable'	=>'suratjalan_pembelian.penerima_id',
			'secondtable'	=> 'suratjalan_produk.id_suratjalan'
		);
*/
		$data = array(
			'suratjalan_produkdagang.id_suratjalan'	=> $id,
    //'suratjalan_pembelian.hapus'	=> array('<',1),
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$prods=$this->model_pembelian_barangdatangdagang->getPermintaanPembelianFull($column,$join,$data);

		//$prods=$this->model_pembelian_barangdatang->getPermintaanPembelianProduct(array('id_suratjalan'	=> $id));
		//print_r($prods);
		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['cancel']= $this->url->link('pembelian/terimabarangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/terimabarangdagang/terima', 'token=' . $this->session->data['token'] . $url.'&id='.$id, 'SSL');

		$this->template = 'pembelian/terimabarangdagang_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	// baru 22 Agustus 2019
	public function tampil(){
		$this->document->setTitle('Barang Datang Pembelian Produk Dagang');
		$this->load->model('pembelian/barangdatangdagang');
		$url = '';

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}

		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
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
				//$this->redirect($this->url->link('pembelian/terimabarangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			//$this->redirect($this->url->link('pembelian/terimabarangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/barangdatangdagang');

		$this->load->model('pembelian/pembeliankreditdagang');

		$column=array('suratjalan_pembeliandagang.*','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'gudang',
			'secondtable'	=>'gudang.gudang_id',
			'firsttable'	=> 'suratjalan_pembeliandagang.gudang_id'
		);

		$data = array(
			'suratjalan_pembeliandagang.id'	=> $id,
		);

		$trans=$this->model_pembelian_barangdatangdagang->getPermintaanPembelian($column,$join,$data);
		
		if($trans['status'] != 1){
			//$this->redirect($this->url->link('pembelian/terimabarangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$column=array('pembelian_kreditdagang.no_po','suratjalan_produkdagang.quantity as qtyterima','pembelian_produk_kreditdagang.*');
		$join=array();

		$join[]=array(
			'tablename'	=> 'pembelian_kreditdagang',
			'firsttable'	=>'suratjalan_produkdagang.po_id',
			'secondtable'	=> 'pembelian_kreditdagang.id'
		);
		$join[]=array(
		  'tablename' => 'pembelian_produk_kreditdagang',
		  'firsttable'  => 'suratjalan_produkdagang.pembelian_product_id',
		  'secondtable' => 'pembelian_produk_kreditdagang.id'
		);

		$data = array(
			'suratjalan_produkdagang.id_suratjalan'	=> $id,

		);
		
		$prods=$this->model_pembelian_barangdatangdagang->getPermintaanPembelianFull($column,$join,$data);
		$this->load->model('user/user');
		$pen=$this->model_user_user->getUser($trans['penerima_id']);
		$this->data['penerima']=$pen['firstname'];
		$peng=$this->model_user_user->getUser($trans['pengangkut_id']);
		$this->data['pengangkut']=$peng['firstname'];
		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['cancel']= $this->url->link('laporan/pembelianlokal', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['action']= $this->url->link('pembelian/terimabarangdagang/terima', 'token=' . $this->session->data['token'] . $url.'&id='.$id, 'SSL');
		$this->data['cetak']= $this->url->link('laporan/pembelianlokal/cetak', 'token=' . $this->session->data['token'] . $url.'&id='.$id, 'SSL');
		if($this->user->getUsername()=="pawitf"){
			echo "<pre>";
			print_r($this->data['permintaan']);
			exit;
		}
		$this->template = 'pembelian/terimabarangdagang_tampil.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	public function cetak(){
		$this->document->setTitle('Barang Datang Pembelian Produk Dagang');
		$this->load->model('pembelian/barangdatangdagang');
		$url = '';

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}

		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
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
				//$this->redirect($this->url->link('pembelian/terimabarangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			//$this->redirect($this->url->link('pembelian/terimabarangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/barangdatangdagang');

		$this->load->model('pembelian/pembeliankreditdagang');

		$column=array('suratjalan_pembeliandagang.*','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'gudang',
			'secondtable'	=>'gudang.gudang_id',
			'firsttable'	=> 'suratjalan_pembeliandagang.gudang_id'
		);

		$data = array(
			'suratjalan_pembeliandagang.id'	=> $id,
		);

		$trans=$this->model_pembelian_barangdatangdagang->getPermintaanPembelian($column,$join,$data);

		if($trans['status'] != 1){
			//$this->redirect($this->url->link('pembelian/terimabarangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$column=array('pembelian_kreditdagang.no_po','suratjalan_produkdagang.quantity as qtyterima','pembelian_produk_kreditdagang.*');
		$join=array();

		$join[]=array(
			'tablename'	=> 'pembelian_kreditdagang',
			'firsttable'	=>'suratjalan_produkdagang.po_id',
			'secondtable'	=> 'pembelian_kreditdagang.id'
		);
		$join[]=array(
		  'tablename' => 'pembelian_produk_kreditdagang',
		  'firsttable'  => 'suratjalan_produkdagang.pembelian_product_id',
		  'secondtable' => 'pembelian_produk_kreditdagang.id'
		);

		$data = array(
			'suratjalan_produkdagang.id_suratjalan'	=> $id,

		);
		$prods=$this->model_pembelian_barangdatangdagang->getPermintaanPembelianFull($column,$join,$data);
		$this->load->model('user/user');
		$pen=$this->model_user_user->getUser($trans['penerima_id']);
		$this->data['penerima']=$pen['firstname'];
		$peng=$this->model_user_user->getUser($trans['pengangkut_id']);
		$this->data['pengangkut']=$peng['firstname'];
		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['cancel']= $this->url->link('pembelian/terimabarangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/terimabarangdagang/terima', 'token=' . $this->session->data['token'] . $url.'&id='.$id, 'SSL');

		$this->template = 'pembelian/terimabarangdagang_cetak.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	// end baru 



	public function batalkan(){

			$this->load->language('catalog/pembelian');

			$this->document->setTitle('Invoice Pembelian Lokal');

			$this->load->model('pembelian/barangdatangdagang');
			$url = '';

			if (isset($this->request->get['filter_no_po'])) {
				$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
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

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['id'])) {

				if(!empty($this->request->get['id'])){
					$cekstatus=$this->model_pembelian_barangdatangdagang->getPermintaanPembelian(array(),array(),array('id'=>$this->request->get['id']));

					if($cekstatus['status'] == 1){
						$this->model_pembelian_barangdatangdagang->updatePermintaan(array('status'=>3),array('id'=>$this->request->get['id']));
						$this->session->data['success'] = 'Sukses: Data surat jalan berhasil dibatalkan ';
						$this->redirect($this->url->link('pembelian/barangdatangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
					}else{

						$this->redirect($this->url->link('pembelian/barangdatangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
					}
				}else{
					$this->redirect($this->url->link('pembelian/barangdatangdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}



			}

	}

}
?>
