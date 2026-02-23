<?php
class ControllerReportStatusPo extends Controller {
	private $error=array();
	public function excel() {
		$this->document->setTitle('Laporan Status PO');

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
		//echo $filter_status;
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

		$this->data['insert'] = $this->url->link('pembelian/pembeliankreditdagang/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

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

		$this->load->model('catalog/vendorlokal');
		$this->load->model('pembelian/pembeliankreditdagang');

		$this->data['permintaans'] = array();
		$column=array('pembelian_kreditdagang.*','pembelian_produk_kreditdagang.id as idpem','pembelian_produk_kreditdagang.product_name','pembelian_produk_kreditdagang.invoice_id as invoice','pembelian_produk_kreditdagang.id as idproduk','pembelian_produk_kreditdagang.quantity','pembelian_produk_kreditdagang.quantityterima','permintaan_pembelian.no_surat','vendorlokal.name','gudang.nama');
		//$column=array('pembelian_import.*','pembelian_produk_import.product_name','pembelian_produk_import.quantity','pembelian_produk_import.quantity_invoice','pembelian_produk_import.invoice_id','invoice_pembelian_import.no_faktur','permintaan_pembelian.no_surat','vendorimport.name','gudang.nama');
		$join=array();
		/*$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_kreditdagang.surat_id'
		);*/
		$join[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'pembelian_kreditdagang.vendor_id'
		);

		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_kreditdagang.surat_id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'secondtable'	=>'gudang.gudang_id',
			'firsttable'	=> 'pembelian_kreditdagang.gudang_id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'pembelian_produk_kreditdagang',
			'secondtable'	=>'pembelian_produk_kreditdagang.pembelian_id',
			'firsttable'	=> 'pembelian_kreditdagang.id'
		);


		$data = array(
			'no_po'      =>array('LIKE',$filter_no_po),
			//'invoice_pembelian.status' => array('<>',4),
			//'no_faktur'      =>array('LIKE',$filter_no_faktur),
			'pembelian_kreditdagang.vendor_id'=> $filter_vendor,
			'pembelian_produk_kreditdagang.permintaan_id'=> $filter_no_surat,
			'pembelian_kreditdagang.jenis_barang'	=> 2,
			'pembelian_kreditdagang.status'	=> array('<>',3),
			'pembelian_kreditdagang.hapus'	=> array('<>',1),
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		if($filter_status != '*'){
			if($filter_status == 0){
				$data['pembelian_kreditdagang.status']=array('<',1);
			}else{
				$data['pembelian_kreditdagang.status']=$filter_status;
			}
		}

		if(!empty($filter_date_end)){
			$data['pembelian_kreditdagang.date_added']=array('>=',$filter_date_start,'<=',$filter_date_end);
		}else{
			$data['pembelian_kreditdagang.date_added']=array('>=',$filter_date_start);
		}

		if($filter_date_start == '1970-01-01'){
			$filter_date_start=null;
		}

		$limit=0;
		//$offset=($page - 1) * $this->config->get('config_admin_limit');
		$offset=null;

		$order=array(
			'pembelian_kreditdagang.date_added'	=> 'DESC',
			'pembelian_kreditdagang.id'	=> 'DESC',
			'pembelian_kreditdagang.status'	=> 'ASC'
		);

		$this->load->model('catalog/gudang');
		$this->load->model('pembelian/invoicepembeliankredit');
		$this->load->model('pembelian/permintaanpembelian');

		$product_total = $this->model_pembelian_pembeliankreditdagang->totalPermintaans($data,$join,$leftjoin);
		//countAll($tablename,$where=array(),$join=array(),$leftjoin=array())
		//$data['invoice_pembelian.status']=array('<>',4);
		$results = $this->model_pembelian_pembeliankreditdagang->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);
		//echo "<pre>";print_r($results);exit;
		$sj=array();
		$iv=array();
		foreach ($results as $result) {
			$action = array();
			$sj=$this->model_pembelian_pembeliankreditdagang->getsj($result['id'],$result['idpem']);
			$iv=$this->model_pembelian_pembeliankreditdagang->getiv($result['id'],$result['idpem']);
			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('report/statuspo/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			

			$gudang="";
			if($result['gudang_id'] > 0){
				$g=$this->model_catalog_gudang->getGudang($result['gudang_id']);
				$gudang=$g['nama'];
			}

			$joinpemb=array();



			$no_inv='';
		/*	if($result['invoice'] > 0){
				$inv=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelian(array(),array(),array('id'=>$result['invoice'],'hapus'=>array('<',1)));
				if(!empty($inv)){
					$no_inv=$inv['no_faktur'];
				}
			}*/
			$inv=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelianProduct(array('po_product_id'=> empty($result['idproduk'])?0:$result['idproduk']));
			foreach($inv as $i){
				$nof=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelian(array(),array(),array('id'=>$i['invoice_id'],'hapus'=>array('<',1)));
				if(!empty($nof)){
					$no_inv .=$nof['no_faktur'].'<br>';
				}
			}

			$col=array("COALESCE(SUM(jumlah),0) as total");

			//permintaan pembelian
			//getPermintaanPembelian($column=array(),$join=array(),$where=array())
			$permintaan=json_decode($result['permintaan_pembelian'],true);
			if(!empty($permintaan)){
				$no_surat='';
				foreach($permintaan as $p){
					$perm=$this->model_pembelian_permintaanpembelian->getPermintaanPembelian(array(),array(),array('id'=>$p['permintaan_id']));
					
					$no_surat.='<a href="'.$this->url->link('pembelian/permintaanpembelian/tampil', 'token=' . $this->session->data['token'] . '&id=' . $p['permintaan_id'].$url, 'SSL').'" target="_blank">'.$perm['no_surat'].'</a><br><br>';
				}
			}else{
				$no_surat='<a href="'.$this->url->link('pembelian/permintaanpembelian/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['surat_id'].$url, 'SSL').'" target="_blank">'.$result['no_surat'].'</a>';
			}
			/*$totalbayar=$this->model_pembelian_pembayarandp->getPermintaanPembelian($col,$joinpemb,$pemb);
			$totalbayar=$totalbayar['total'];
			*/
			//if($result['statusinvoice'] != 4){
				if($result['quantity']==$result['quantityterima']){
					$st='Terkirim semua';
				}else if($result['quantity']>$result['quantityterima'] & $result['quantityterima']>0){
					$st='Terkirim sebagian';
				}else{
					$st='';
				}
			$this->data['permintaans'][] = array(
				'sj'=>$sj,
				'iv'=>$iv,
				'name'	=> $result['name'],
				'gudang'	=> $result['nama'],
				'no_po'	=> $result['no_po'],
				'no_surat'	=> $no_surat,
				'no_faktur'	=> $no_inv,
				'id'	=> $result['id'],
				'idpem'	=> $result['idpem'],
				//'hrefsurat' => $this->url->link('pembelian/permintaanpembelian/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['surat_id'].$url, 'SSL'),
				'surat_id'	=> $result['surat_id'],
				'sub_total'	=> $this->currency->format($result['sub_total']),
				'diskon'	=> $this->currency->format($result['diskon']),
				'pajak'	=> $this->currency->format($result['pajak']),
				'total_pembelian'	=> $this->currency->format($result['total_pembelian']),
				//'totalbayar'	=> $this->currency->format($result['totalbayarinv']),
				//'statusbayar'	=> ($totalbayar < $result['total_pembelian'])?'Belum Lunas':'Sudah Lunas',
				'tanggal'	=> date('d/m/y',strtotime($result['date_added'])),
				//'jatuhtempo'	=> date('d/m/y',strtotime($result['jatuhtempo'])),

				//'no_surat'	=> $result['no_surat'],
				//'status_pengiriman'	=> ($result['quantity']==$result['quantityterima'])?'Terkirim semua':'Terkirim sebagian',
				//'status_pengiriman'	=> ($result['quantity']==$result['quantityterima'])?'Terkirim Semua':($result['quantity']>$result['quantityterima'])?'Terkirim sebagian':'',
				'status_pengiriman'=>$st,
				'metode_pembayaran'	=> $result['metode_pembayaran'] == 1?'CBD':($result['metode_pembayaran'] == 2?'COD':'Kredit'),
				'metode_pengiriman'	=> $result['metode_pengiriman'] == 1?'Antar Jemput ke Nisson':($result['metode_pengiriman'] == 2?'Tidak antar jemput':''),
				'product_name'=> $result['product_name'],
				'quantity'	=> $result['quantity'],
				'quantityterima'	=> $result['quantityterima'],
			//	'quantity_invoice'	=> $result['quantity_invoice'],
				'actions'	=> $action
			);
			//}
		}

		//echo "<pre>";print_r($this->data['permintaans']);exit;

		$this->data['heading_title'] = 'Pembelian Produk Dagang';

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

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/statuspo', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_faktur'] = $filter_no_faktur;
		$this->data['filter_no_po'] = $filter_no_po;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_vendor'] = $filter_vendor;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_status'] = $filter_status;
		$this->template = 'report/statuspo_excel.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function index() {
		$this->document->setTitle('Laporan Status PO');

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
		//echo $filter_status;
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

		
		$this->data['excel'] = $this->url->link('report/statuspo/excel', 'token=' . $this->session->data['token'].$url, 'SSL');

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

		$this->load->model('catalog/vendorlokal');
		$this->load->model('pembelian/pembeliankreditdagang');

		$this->data['permintaans'] = array();
		$column=array('pembelian_kreditdagang.*','pembelian_produk_kreditdagang.id as idpem','pembelian_produk_kreditdagang.product_name','pembelian_produk_kreditdagang.invoice_id as invoice','pembelian_produk_kreditdagang.id as idproduk','pembelian_produk_kreditdagang.quantity','pembelian_produk_kreditdagang.quantityterima','permintaan_pembelian.no_surat','vendorlokal.name','gudang.nama');
		//$column=array('pembelian_import.*','pembelian_produk_import.product_name','pembelian_produk_import.quantity','pembelian_produk_import.quantity_invoice','pembelian_produk_import.invoice_id','invoice_pembelian_import.no_faktur','permintaan_pembelian.no_surat','vendorimport.name','gudang.nama');
		$join=array();
		/*$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_kreditdagang.surat_id'
		);*/
		$join[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'pembelian_kreditdagang.vendor_id'
		);

		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_kreditdagang.surat_id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'secondtable'	=>'gudang.gudang_id',
			'firsttable'	=> 'pembelian_kreditdagang.gudang_id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'pembelian_produk_kreditdagang',
			'secondtable'	=>'pembelian_produk_kreditdagang.pembelian_id',
			'firsttable'	=> 'pembelian_kreditdagang.id'
		);


		$data = array(
			'no_po'      =>array('LIKE',$filter_no_po),
			//'invoice_pembelian.status' => array('<>',4),
			//'no_faktur'      =>array('LIKE',$filter_no_faktur),
			'pembelian_kreditdagang.vendor_id'=> $filter_vendor,
			'pembelian_produk_kreditdagang.permintaan_id'=> $filter_no_surat,
			'pembelian_kreditdagang.jenis_barang'	=> 2,
			'pembelian_kreditdagang.status'	=> array('<>',3),
			'pembelian_kreditdagang.hapus'	=> array('<>',1),
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		if($filter_status != '*'){
			if($filter_status == 0){
				$data['pembelian_kreditdagang.status']=array('<',1);
			}else{
				$data['pembelian_kreditdagang.status']=$filter_status;
			}
		}

		if(!empty($filter_date_end)){
			$data['pembelian_kreditdagang.date_added']=array('>=',$filter_date_start,'<=',$filter_date_end);
		}else{
			$data['pembelian_kreditdagang.date_added']=array('>=',$filter_date_start);
		}

		if($filter_date_start == '1970-01-01'){
			$filter_date_start=null;
		}

		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'pembelian_kreditdagang.date_added'	=> 'DESC',
			'pembelian_kreditdagang.id'	=> 'DESC',
			'pembelian_kreditdagang.status'	=> 'ASC'
		);

		$this->load->model('catalog/gudang');
		$this->load->model('pembelian/invoicepembeliankredit');
		$this->load->model('pembelian/permintaanpembelian');

		$product_total = $this->model_pembelian_pembeliankreditdagang->totalPermintaans($data,$join,$leftjoin);
		//countAll($tablename,$where=array(),$join=array(),$leftjoin=array())
		//$data['invoice_pembelian.status']=array('<>',4);
		$results = $this->model_pembelian_pembeliankreditdagang->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);
		//echo "<pre>";print_r($results);exit;
		$sj=array();
		$iv=array();
		foreach ($results as $result) {
			$action = array();
			$sj=$this->model_pembelian_pembeliankreditdagang->getsj($result['id'],$result['idpem']);
			$iv=$this->model_pembelian_pembeliankreditdagang->getiv($result['id'],$result['idpem']);
			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('report/statuspo/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			

			$gudang="";
			if($result['gudang_id'] > 0){
				$g=$this->model_catalog_gudang->getGudang($result['gudang_id']);
				$gudang=$g['nama'];
			}

			$joinpemb=array();



			$no_inv='';
		/*	if($result['invoice'] > 0){
				$inv=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelian(array(),array(),array('id'=>$result['invoice'],'hapus'=>array('<',1)));
				if(!empty($inv)){
					$no_inv=$inv['no_faktur'];
				}
			}*/
			$inv=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelianProduct(array('po_product_id'=> empty($result['idproduk'])?0:$result['idproduk']));
			foreach($inv as $i){
				$nof=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelian(array(),array(),array('id'=>$i['invoice_id'],'hapus'=>array('<',1)));
				if(!empty($nof)){
					$no_inv .=$nof['no_faktur'].'<br>';
				}
			}

			$col=array("COALESCE(SUM(jumlah),0) as total");

			//permintaan pembelian
			//getPermintaanPembelian($column=array(),$join=array(),$where=array())
			$permintaan=json_decode($result['permintaan_pembelian'],true);
			if(!empty($permintaan)){
				$no_surat='';
				foreach($permintaan as $p){
					$perm=$this->model_pembelian_permintaanpembelian->getPermintaanPembelian(array(),array(),array('id'=>$p['permintaan_id']));
					
					$no_surat.='<a href="'.$this->url->link('pembelian/permintaanpembelian/tampil', 'token=' . $this->session->data['token'] . '&id=' . $p['permintaan_id'].$url, 'SSL').'" target="_blank">'.$perm['no_surat'].'</a><br><br>';
				}
			}else{
				$no_surat='<a href="'.$this->url->link('pembelian/permintaanpembelian/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['surat_id'].$url, 'SSL').'" target="_blank">'.$result['no_surat'].'</a>';
			}
			/*$totalbayar=$this->model_pembelian_pembayarandp->getPermintaanPembelian($col,$joinpemb,$pemb);
			$totalbayar=$totalbayar['total'];
			*/
			//if($result['statusinvoice'] != 4){
				if($result['quantity']==$result['quantityterima']){
					$st='Terkirim semua';
				}else if($result['quantity']>$result['quantityterima'] & $result['quantityterima']>0){
					$st='Terkirim sebagian';
				}else{
					$st='';
				}
			$this->data['permintaans'][] = array(
				'sj'=>$sj,
				'iv'=>$iv,
				'name'	=> $result['name'],
				'gudang'	=> $result['nama'],
				'no_po'	=> $result['no_po'],
				'no_surat'	=> $no_surat,
				'no_faktur'	=> $no_inv,
				'id'	=> $result['id'],
				'idpem'	=> $result['idpem'],
				//'hrefsurat' => $this->url->link('pembelian/permintaanpembelian/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['surat_id'].$url, 'SSL'),
				'surat_id'	=> $result['surat_id'],
				'sub_total'	=> $this->currency->format($result['sub_total']),
				'diskon'	=> $this->currency->format($result['diskon']),
				'pajak'	=> $this->currency->format($result['pajak']),
				'total_pembelian'	=> $this->currency->format($result['total_pembelian']),
				//'totalbayar'	=> $this->currency->format($result['totalbayarinv']),
				//'statusbayar'	=> ($totalbayar < $result['total_pembelian'])?'Belum Lunas':'Sudah Lunas',
				'tanggal'	=> date('d/m/y',strtotime($result['date_added'])),
				//'jatuhtempo'	=> date('d/m/y',strtotime($result['jatuhtempo'])),

				//'no_surat'	=> $result['no_surat'],
				//'status_pengiriman'	=> ($result['quantity']==$result['quantityterima'])?'Terkirim semua':'Terkirim sebagian',
				//'status_pengiriman'	=> ($result['quantity']==$result['quantityterima'])?'Terkirim Semua':($result['quantity']>$result['quantityterima'])?'Terkirim sebagian':'',
				'status_pengiriman'=>$st,
				'metode_pembayaran'	=> $result['metode_pembayaran'] == 1?'CBD':($result['metode_pembayaran'] == 2?'COD':'Kredit'),
				'metode_pengiriman'	=> $result['metode_pengiriman'] == 1?'Antar Jemput ke Nisson':($result['metode_pengiriman'] == 2?'Tidak antar jemput':''),
				'product_name'=> $result['product_name'],
				'quantity'	=> $result['quantity'],
				'quantityterima'	=> $result['quantityterima'],
			//	'quantity_invoice'	=> $result['quantity_invoice'],
				'actions'	=> $action
			);
			//}
		}

		//echo "<pre>";print_r($this->data['permintaans']);exit;

		$this->data['heading_title'] = 'Pembelian Produk Dagang';

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

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/statuspo', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_faktur'] = $filter_no_faktur;
		$this->data['filter_no_po'] = $filter_no_po;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_vendor'] = $filter_vendor;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_status'] = $filter_status;
		$this->template = 'report/statuspo.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Pembelian Produk Dagang');

		$this->load->model('pembelian/pembeliankreditdagang');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      	$no_po=$this->model_pembelian_pembeliankreditdagang->addPembelian($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Pembelian Produk Dagang berhasil disimpan dengan nomor PO '.$no_po;

			$url = '';

			if (isset($this->request->get['filter_no_faktur'])) {
				$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
			}
			if (isset($this->request->get['filter_no_po'])) {
				$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
			}
			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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

			$this->redirect($this->url->link('pembelian/pembeliankreditdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url = '';

		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
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

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->post['vendor_id'])) {
			$this->data['vendor_id'] = $this->request->post['vendor_id'];
		}  else {
			$this->data['vendor_id'] = '';
		}

		if (isset($this->request->post['surat_id'])) {
			$this->data['surat_id'] = $this->request->post['surat_id'];
		}  else {
			$this->data['surat_id'] = '';
		}

		if (isset($this->request->post['sub_total'])) {
			$this->data['sub_total'] = $this->request->post['sub_total'];
		}  else {
			$this->data['sub_total'] = '';
		}

		if (isset($this->request->post['pajak'])) {
			$this->data['pajak'] = $this->request->post['pajak'];
		}  else {
			$this->data['pajak'] = '';
		}

		if (isset($this->request->post['total_pembelian'])) {
			$this->data['total_pembelian'] = $this->request->post['total_pembelian'];
		}  else {
			$this->data['total_pembelian'] = '';
		}

		if (isset($this->request->post['jenis_barang'])) {
			$this->data['jenis_barang'] = $this->request->post['jenis_barang'];
		}  else {
			$this->data['jenis_barang'] = '';
		}


		$this->data['cancel']= $this->url->link('pembelian/pembeliankreditdagang', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/pembeliankreditdagang/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = array();
		}

		if (isset($this->request->post['product'])) {
			$this->data['products'] = $this->request->post['product'];
		} else {
			$this->data['products'] = array();
		}

		$this->load->model('catalog/gudang');
		$gudangs = $this->model_catalog_gudang->getGudangs(true);
		$this->data['gudangs']=$gudangs;

		$locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
		$this->template = 'pembelian/pembeliankreditdagang_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {
    	/*if (!$this->user->hasPermission('modify', 'pembelian/permintaanpembelian')) {
      		$this->error['warning'] = 'Permission Denied.';
    	}

    	/*if (empty($this->request->post['date_added'])) {
      		$this->error['warning'] = 'Tanggal input product cacat harus diisi';
    	}*/

		/*if (empty($this->request->post['products'])) {
		  		$this->error['products'] = 'Produk tidak boleh kosong';
			}
		if (empty($this->request->post['vendor_id'])) {
		  		$this->error['vendor'] = 'Vendor tidak boleh kosong';
			}
		if (empty($this->request->post['surat_id'])) {
		  		$this->error['surat'] = 'Nomor Surat Permintaan Pembelian  tidak boleh kosong';
			}
		if(!is_numeric($this->request->post['sub_total']) ){
			$this->error['subtotal'] = 'Nilai Sub Total Harus Berupa Angka';
		}*/

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function tampil(){
		$this->document->setTitle('Pembelian Produk Dagang');
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
				$this->redirect($this->url->link('report/statuspo', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('report/statuspo', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/pembeliankreditdagang');
		if($id < 1477){
			$column=array('pembelian_kreditdagang.*','permintaan_pembelian.no_surat','vendorlokal.name');
			$join=array();
			$join[]=array(
				'tablename'	=> 'permintaan_pembelian',
				'secondtable'	=>'permintaan_pembelian.id',
				'firsttable'	=> 'pembelian_kreditdagang.surat_id'
			);
			$join[]=array(
				'tablename'	=> 'vendorlokal',
				'secondtable'	=>'vendorlokal.id',
				'firsttable'	=> 'pembelian_kreditdagang.vendor_id'
			);

			$data = array(
				'pembelian_kreditdagang.id'	=> $id,
		'permintaan_pembelian.hapus'	=> array('<',1),
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);

			$trans=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelian($column,$join,$data);
			$prods=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelianProduct(array('pembelian_id'	=> $id));
		//	$biayas=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelianBiaya(array('order_id'	=> $id));
			/*$pbjoin[]=array(
				'tablename'=>'banks',
				'firsttable'	=> 'pembayaran_dp.bank_id',
				'secondtable'=> 'banks.id'
			);

			$pembayarans=$this->model_pembelian_pembayarandp->getPermintaanPembelians(array('pembayaran_dp.*','banks.name'),$pbjoin,array('no_po'=>$trans['no_po'],'status'=>1));
			//print_r($pembayarans);

			*/
		}else{
			$column=array('pembelian_kreditdagang.*','vendorlokal.name');
			$join=array();
			/*$join[]=array(
				'tablename'	=> 'permintaan_pembelian',
				'secondtable'	=>'permintaan_pembelian.id',
				'firsttable'	=> 'pembelian_kreditdagang.surat_id'
			);*/
			$join[]=array(
				'tablename'	=> 'vendorlokal',
				'secondtable'	=>'vendorlokal.id',
				'firsttable'	=> 'pembelian_kreditdagang.vendor_id'
			);

			$data = array(
				'pembelian_kreditdagang.id'	=> $id,
		// 'permintaan_pembelian.hapus'	=> array('<',1),
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);

			$trans=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelian($column,$join,$data);
			$prods=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelianProduct(array('pembelian_id'	=> $id));
		//	$biayas=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelianBiaya(array('order_id'	=> $id));
			/*$pbjoin[]=array(
				'tablename'=>'banks',
				'firsttable'	=> 'pembayaran_dp.bank_id',
				'secondtable'=> 'banks.id'
			);

			$pembayarans=$this->model_pembelian_pembayarandp->getPermintaanPembelians(array('pembayaran_dp.*','banks.name'),$pbjoin,array('no_po'=>$trans['no_po'],'status'=>1));
			//print_r($pembayarans);

			*/
			$this->load->model('pembelian/permintaanpembelian');
			$ppermintaan=json_decode($trans['permintaan_pembelian'],true);
				if(!empty($ppermintaan)){
					$no_surat='';
					foreach($ppermintaan as $p){
						$perm=$this->model_pembelian_permintaanpembelian->getPermintaanPembelian(array(),array(),array('id'=>$p['permintaan_id']));
						
						$no_surat.='<a href="'.$this->url->link('pembelian/permintaanpembelian/tampil', 'token=' . $this->session->data['token'] . '&id=' . $p['permintaan_id'].$url, 'SSL').'" target="_blank">'.$perm['no_surat'].'</a><br>';
					}
				}else{
					$no_surat='<a href="'.$this->url->link('pembelian/permintaanpembelian/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['surat_id'].$url, 'SSL').'" target="_blank">'.$result['no_surat'].'</a>';
				}
			$trans['no_surat']=$no_surat;
		}
		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		//$this->data['biayas']=$biayas;
		//$this->data['pembayarans']=$pembayarans;

		$this->data['cancel']= $this->url->link('report/statuspo', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/pembeliankreditdagang_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function editvendor(){
		$this->document->setTitle('Pembelian Produk Dagang');
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
				$this->redirect($this->url->link('pembelian/pembeliankreditdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/pembeliankreditdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->load->model('pembelian/pembeliankreditdagang');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_pembelian_pembeliankreditdagang->updatePermintaan(array('vendor_id' => $this->request->post['vendor_id']),array('id'	=> $this->request->get['id']));;

			$this->session->data['success'] = 'Sukses: Data biaya berhasil diperbarui';



			$this->redirect($this->url->link('pembelian/pembeliankreditdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}



		$column=array('pembelian_kreditdagang.*','permintaan_pembelian.no_surat','vendorlokal.name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_kreditdagang.surat_id'
		);
		$join[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'pembelian_kreditdagang.vendor_id'
		);

		$data = array(
			'pembelian_kreditdagang.id'	=> $id,
      'permintaan_pembelian.hapus'	=> array('<',1),
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelian($column,$join,$data);

		$this->data['permintaan']=$trans;

		$this->data['cancel']= $this->url->link('pembelian/pembeliankreditdagang', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/pembeliankreditdagang/editvendor', 'token=' . $this->session->data['token'] . $url.'&id='.$id, 'SSL');

		$this->template = 'pembelian/pembeliankreditdagang_editvendor.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function biaya(){
		$this->document->setTitle('Biaya Pembelian');
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
				$this->redirect($this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/pembeliankredit');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_pembelian_pembeliankredit->addBiaya($this->request->post);

			$this->session->data['success'] = 'Sukses: Data biaya berhasil diperbarui';



			$this->redirect($this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$column=array('pembelian_kredit.*','permintaan_pembelian.no_surat','vendorlokal.name','permintaan_pembelian.jenis_barang');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_kredit.surat_id'
		);
		$join[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'pembelian_kredit.vendor_id'
		);

		$data = array(
			'pembelian_kredit.id'	=> $id,
      'permintaan_pembelian.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_pembeliankredit->getPermintaanPembelian($column,$join,$data);
		$biayas=$this->model_pembelian_pembeliankredit->getPermintaanPembelianBiaya(array('order_id'	=> $id));
		$this->data['permintaan']=$trans;
		$this->data['biayas']=$biayas;
		$this->data['cancel']= $this->url->link('pembelian/pembeliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['action']=$this->url->link('pembelian/pembeliankredit/biaya', 'token=' . $this->session->data['token'] . '&id='.$this->request->get['id'].$url, 'SSL');
		$this->template = 'pembelian/pembeliankredit_biaya.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}



	public function cetak(){
		$this->document->setTitle('Pembelian Kredit');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/pembeliankreditdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/pembepembeliankreditdagangliankredit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/pembeliankreditdagang');
		if($id <= 1477){
			$column=array('pembelian_kreditdagang.*','permintaan_pembelian.no_surat','permintaan_pembelian.disetujui_oleh','vendorlokal.name','vendorlokal.alamat as alamatvendor','permintaan_pembelian.jenis_barang');
			$join=array();
			
			$join[]=array(
				'tablename'	=> 'permintaan_pembelian',
				'secondtable'	=>'permintaan_pembelian.id',
				'firsttable'	=> 'pembelian_kreditdagang.surat_id'
			);
			$join[]=array(
				'tablename'	=> 'vendorlokal',
				'secondtable'	=>'vendorlokal.id',
				'firsttable'	=> 'pembelian_kreditdagang.vendor_id'
			);
			// $leftjoin=array();
			// $leftjoin[]=array(
			// 	'tablename'	=> 'users',
			// 	'secondtable'	=>'gudang.gudang_id',
			// 	'firsttable'	=> 'pembelian_kreditdagang.gudang_id'
			// );
			$data = array(
				'pembelian_kreditdagang.id'	=> $id,
				'permintaan_pembelian.hapus'	=> 0,
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);
		

		$trans=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelian($column,$join,$data);
		//var_dump($trans);exit();
		$prods=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelianProduct(array('pembelian_id'	=> $id));
		// print_r($prods);
		//print_r($trans);
		
		}else{
			$column=array('pembelian_kreditdagang.*','vendorlokal.name','vendorlokal.alamat as alamatvendor');
			$join=array();
			/*$join[]=array(
				'tablename'	=> 'permintaan_pembelian',
				'secondtable'	=>'permintaan_pembelian.id',
				'firsttable'	=> 'pembelian_kreditdagang.surat_id'
			);*/
			$join[]=array(
				'tablename'	=> 'vendorlokal',
				'secondtable'	=>'vendorlokal.id',
				'firsttable'	=> 'pembelian_kreditdagang.vendor_id'
			);
			// $leftjoin=array();
			// $leftjoin[]=array(
			// 	'tablename'	=> 'users',
			// 	'secondtable'	=>'gudang.gudang_id',
			// 	'firsttable'	=> 'pembelian_kreditdagang.gudang_id'
			// );
			$data = array(
				'pembelian_kreditdagang.id'	=> $id,
			//	'permintaan_pembelian.hapus'	=> 0,
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);

			$trans=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelian($column,$join,$data);
			//var_dump($trans);exit();
			$prods=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelianProduct(array('pembelian_id'	=> $id));
			// print_r($prods);
			//print_r($trans);
			$this->load->model('pembelian/permintaanpembelian');
			$ppermintaan=json_decode($trans['permintaan_pembelian'],true);
			if(!empty($ppermintaan)){
				$no_surat='';
				foreach($ppermintaan as $p){
					$perm=$this->model_pembelian_permintaanpembelian->getPermintaanPembelian(array(),array(),array('id'=>$p['permintaan_id']));
					
					$no_surat.=$perm['no_surat'].'<br>';
				}
			}else{
				$no_surat=$trans['no_surat'];
			}
			$trans['no_surat']=$no_surat;
		}
		$this->load->model('user/user');
		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$permintaan=$trans; // $permintaan['no_po']
		$kpf = substr($permintaan['no_po'],0,6);
		$rkpf = str_replace("-","",$kpf);
		$tmp=$rkpf;
		$jk = strlen($tmp);
		if($jk==4)
		{
			$rkpf = substr($tmp,-2);
		}
		else
		{
			$rkpf = substr($tmp,-3);
		}
        $this->data['namauser'] = $this->model_user_user->getUser($rkpf);
        $this->data['disetujui'] = $this->model_user_user->getUser($permintaan['disetujui_oleh']);
		$this->data['no'] =1;
		if($this->user->getUsername()=="pawitx"){
			echo "<pre>";print_r($permintaan);exit;
		}
		$this->template = 'pembelian/pebeliankreditdagang_cetak.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
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

	public function batalkan(){
		$this->load->model('pembelian/pembeliankreditdagang');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_pembelian_pembeliankreditdagang->updatePermintaan(array('status' => 3),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Pembelian Produk Dagang berhasil dibatalkan.';
			}
		}
			$url = '';

			if (isset($this->request->get['filter_no_po'])) {
				$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
			}
			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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

			$this->redirect($this->url->link('pembelian/pembeliankreditdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function tutuppo(){
		$this->load->model('pembelian/pembeliankreditdagang');
		$this->load->model('pembelian/invoicepembeliankredit');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
			$pembelian=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelian(array(),array(),array('id'	=> $this->request->get['id']));

			if($pembelian['status'] == 2){
				//cek invoice
				$invpembelian=$this->model_pembelian_pembeliankreditdagang->tutuppo($this->request->get['id']);
				if($invpembelian){
					$this->session->data['success'] = 'Peringatan: Data Pembelian Produk Dagang berhasil di tutup.';
      		//$this->model_pembelian_pembeliankredit->updatePermintaan(array('status' => 1),array('id'	=> $this->request->get['id']));
				}else{
					$this->session->data['warning'] = 'Peringatan: Data Pembelian Produk Dagang  gagal di tutup karena terdapat item dengan quantity belum diterima/diterima sebagian.';
				}
			}

			}
		}
			$url = '';

			if (isset($this->request->get['filter_no_po'])) {
				$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
			}
			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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

			$this->redirect($this->url->link('pembelian/pembeliankreditdagang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('pembelian/pembeliankreditdagang');

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

			$data = array(
				'no_po'         => array('LIKE',$filter_no_po)
			);
			$start=0;
			$limit=0;
			$column=array('id','no_po');
			$join=array();

			$results = $this->model_pembelian_pembeliankreditdagang->getPermintaanPembelians($column,$join,$join,$data,array(),$limit,$start);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['no_po'],
					'text'	=> $r['no_po']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function detail(){
		$hasil = array();

		//$this->load->model('pembelian/permintaanpembelian');
		if(isset($this->request->get['vendor_id'])){
			if(!empty($this->request->get['vendor_id'])){

			$this->load->model('pembelian/pembeliankreditdagang');
			$products=$this->model_pembelian_pembeliankreditdagang->getPoTanpaInvoice($this->request->get['vendor_id'],$this->request->get['gudang_id']);


			$hasil=array(
				//'order'	=> $trans,
				'products'	=> $products,
				//'address'	=> $this->data['address']
			);
		}
	}
		$this->response->setOutput(json_encode($hasil));
	}

	public function detailbelumdatang(){
		$hasil = array();

		//$this->load->model('pembelian/permintaanpembelian');
		if(isset($this->request->get['vendor_id'])){
			if(!empty($this->request->get['vendor_id'])){

			$this->load->model('pembelian/pembeliankreditdagang');
			$products=$this->model_pembelian_pembeliankreditdagang->getPoBelumDatang($this->request->get['vendor_id'],$this->request->get['gudang_id']);


			$hasil=array(
				//'order'	=> $trans,
				'products'	=> $products,
				//'address'	=> $this->data['address']
			);
		}
	}
		$this->response->setOutput(json_encode($hasil));
	}

}
?>