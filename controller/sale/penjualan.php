<?php
class ControllerSalePenjualan extends Controller {
	private $error = array();
	// baru 4 Januari 2019
	public function downloadexcel(){
		//echo "<pre>";print_r($this->report_detailsgrosir());
		$this->xlscreation_directgrosir();
	}
	
	function xlscreation_directgrosir() {

		$reportdetails = $this->report_detailsgrosir();

		$objPHPExcel = new PHPExcel(); 
		$objPHPExcel->getProperties()
				->setCreator("IT Division")
				->setLastModifiedBy("IT Division")
				->setTitle("Mom & Bab ")
				->setSubject("Mom & Bab")
				->setDescription("Export Item to Excel")
				->setKeywords("IT Division")
				->setCategory("IT Division");

		// Set the active Excel worksheet to sheet 0
		$objPHPExcel->setActiveSheetIndex(0); 

		// Initialise the Excel row number
		$rowCount = 0; 

		// Sheet cells
		/*$cell_definition = array(
			'A' => 'BrandIcon',
			'B' => 'Comapany',
			'C' => 'Rank',
			'D' => 'Link'
		);*/
		$cell_definition = array(
			'A' => 'Tanggal',
			'B' => 'Gudang',
			'C' => 'No.SJ',
			'D' => 'No.Invoice',
			'E' => 'Nama',
			'F' => 'Nama Produk',
			'G' => 'Quantity',
			'H' => 'Satuan',
		);

		// Build headers
		foreach( $cell_definition as $column => $value )
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension("{$column}")->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value ); 
		}

		// Build cells
		while( $rowCount < count($reportdetails) ){ 
			$cell = $rowCount + 2;
			foreach( $cell_definition as $column => $value ) {

				$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(30);
				//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
				
				switch ($value) {
					case 'image':
						if (file_exists($reportdetails[$rowCount][$value])) {
							$objDrawing = new PHPExcel_Worksheet_Drawing();
							$objDrawing->setName('Customer Signature');
							$objDrawing->setDescription('Customer Signature');
							//Path to signature .jpg file
							$signature = $reportdetails[$rowCount][$value];    
							$objDrawing->setPath($signature);
							$objDrawing->setOffsetX(5);                     //setOffsetX works properly
							$objDrawing->setOffsetY(5);                     //setOffsetY works properly
							$objDrawing->setCoordinates($column.$cell);             //set image to cell 
							$objDrawing->setWidth(100);  
							$objDrawing->setHeight(100);                     //signature height  
							$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 
						} else {
							$objPHPExcel->getActiveSheet()->setCellValue($column.$cell,"No Image"); 
						}
						break;
					default:
						$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, $reportdetails[$rowCount][$value] ); 
						break;
				}

			}
				
			$rowCount++; 
		} 

		$rand = rand(1234, 9898);
		$presentDate = date('d-m-Y H:i:s');
		//$fileName = "Produk_Gudang_" . $rand . "_" . $presentDate . ".xls";
		$fileName = "Surat_jalan_".$presentDate.".xls";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		die();
	}	
	
	public function report_detailsgrosir($display = null) {
		$this->load->language('catalog/category');

		$this->document->setTitle('Daftar Penjualan');

		$this->load->model('sale/penjualan');
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_invoice'])) {
			$filter_invoice = $this->request->get['filter_invoice'];
		} else {
			$filter_invoice = null;
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$filter_sales_order = $this->request->get['filter_sales_order'];
		} else {
			$filter_sales_order = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$filter_shipping_method = $this->request->get['filter_shipping_method'];
		} else {
			$filter_shipping_method = null;
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$filter_tanggal_awal = $this->request->get['filter_tanggal_awal'];
		} else {
			$filter_tanggal_awal = null;
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$filter_tanggal_akhir = $this->request->get['filter_tanggal_akhir'];
		} else {
			$filter_tanggal_akhir = null;
		}
		if (isset($this->request->get['filter_sales_order'])) {
			$filter_sales_order = $this->request->get['filter_sales_order'];
		} else {
			$filter_sales_order = null;
		}

		if (isset($this->request->get['filter_statustabung'])) {
			$filter_statustabung= $this->request->get['filter_statustabung'];
		} else {
			$filter_statustabung = null;
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['insert'] = $this->url->link('sale/penjualan/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/penjualan/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['penjualans'] = array();

		$column=array('penjualan_product.sales_order_id','penjualan_product.product_id','penjualan_product.quantity','penjualan_product.no_so','penjualan_product.invoice_id','satuan.name as namasatuan','product.name as namaproduct','penjualan.*','sales_order.no_so as no_salesorder','gudang.nama','customer.name','customer.alamat','customer.telephone','customer.email','invoice.no_faktur');
		$join=array();
			$join[]=array(
		  'tablename' => 'penjualan_product',
		  'firsttable'  => 'penjualan.id',
		  'secondtable' => 'penjualan_product.sales_order_id'
		);
			$join[]=array(
				'tablename'	=> 'gudang',
				'firsttable'	=> 'penjualan.gudang_id',
				'secondtable'	=> 'gudang.gudang_id',
			);
		$join[]=array(
		  'tablename' => 'product',
		  'firsttable'  => 'penjualan_product.product_id',
		  'secondtable' => 'product.product_id'
		);

		$join[]=array(
		  'tablename' => 'sales_order',
		  'firsttable'  => 'penjualan_product.no_so',
		  'secondtable' => 'sales_order.id'
		);

		$leftjoin=array();
			$leftjoin[]=array(
				'tablename'	=> 'customer',
				'firsttable'	=> 'penjualan.customer_id',
				'secondtable'	=> 'customer.customer_id',
			);

		$leftjoin[]=array(
		  'tablename' => 'satuan',
		  'firsttable'  => 'product.satuan',
		  'secondtable' => 'satuan.id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'invoice',
			'firsttable'	=> 'penjualan_product.invoice_id',
			'secondtable'	=> 'invoice.id',
		);

		$this->load->model('catalog/gudang');

		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs(true);
		$gudangs=array();
		if(empty($filter_gudang_id)){
			foreach($this->data['gudangs'] as $g){
				$gudangs[]=$g['gudang_id'];
			}
		}else{
			$gudangs[]=$filter_gudang_id;
		}

		$arrsql=implode(',',$gudangs);
		$data=array();
		if(isset($filter_tanggal_awal) && !isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):$filter_tanggal_awal,
			);
		}
		else if(!isset($filter_tanggal_awal) && isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):$filter_tanggal_akhir,
			);
		}
		else if(isset($filter_tanggal_awal) && isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):array('>=',$filter_tanggal_awal),
				'penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):array('<=',$filter_tanggal_akhir),
			);
		}

		$data += array(
			'penjualan.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'penjualan.gudang_id'	=>array('IN',$arrsql),
			'penjualan.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			'penjualan.status'	=> empty($filter_status)?array('>=',0):$filter_status,
			'penjualan_product.no_so'	=> empty($filter_sales_order)?array('>=',0):$filter_sales_order,
		);
		if(!empty($filter_invoice)){
			if($filter_invoice != 'na'){
				$data['penjualan_product.invoice_id']=$filter_invoice;
			}else{
				$data['penjualan_product.invoice_id']=array('<',1);
			}
		}
		$order=array(
			'penjualan.id'	=> 'DESC',

		);
		//$offset=($page - 1) * $this->config->get('config_admin_limit');
		$offset=null;
		//$limit=$this->config->get('config_admin_limit');
		$limit=0;

		$results = $this->model_sale_penjualan->getPenjualans($column,$join,$leftjoin,$data,$order,$limit,$offset);
		$product_total = $this->model_sale_penjualan->totalPenjualans($data,$join,$leftjoin);

		$this->load->model('sale/invoice');
		$this->load->model('catalog/gudang');

		$this->load->model('user/user');
		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),4);
		
		// baru 11 November 2019
		$editdata=$this->model_user_user->getAksesData($this->user->getId(),10);
		foreach ($results as $result) {
			$action = array();

			$penjualans[] = array(
				'id' => $result['id'],
				'customer_id'        => $result['customer_id'],
				'No.SJ'        => $result['no_sj'],
				'no_so'        => $result['no_so'],
				'Gudang'        => $result['nama'],
				'Nama Produk'        => $result['namaproduct'],
				'Satuan'        => $result['namasatuan'],
				'Quantity'        => $result['quantity'],
				'nom_so'	=> $result['nom_so'],
				'No.SO'	=> $result['no_salesorder'],
				'No.Invoices'        => empty($result['no_invoice'])?'Belum Ada Invoice':$result['no_invoice'],
				'No.Invoice'        => empty($result['invoice_id'])?'Belum Ada Invoice':$result['no_faktur'],
				'total'        => $this->currency->format($result['total']),
				'Nama'	=> $result['name'],
				'email'	=> $result['email'],
				'telephone'	=> $result['telephone'],
				'status'	=> $result['status'],
				'Tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'      => $action
			);

		}
		return $penjualans;
		
	}
	// end baru

	// baru 12 November 2019
	public function jurnal(){
		$id = $this->request->get['id'];
		$sql="SELECT * FROM jurnal_umum WHERE type=7 and ref='".$id."' ";
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
		echo "<td></td><td></td><td></td><td><b>ref akun</b></td><td></td><td><b>ref akun </b></td><td></td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>".date('d/m/Y',strtotime($j->row['tanggal']))."</td><td>".$linkterkait."</td><td>".$j->row['keterangan']."</td><td></td><td></td><td></td><td></td>";
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
	
	public function tampilbelumdifaktur(){
		$this->document->setTitle('Daftar Penjualan');
		$this->data['token'] = $this->session->data['token'];
		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/penjualan');
		$this->load->model('sale/customer');

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),6);

		$column=array('penjualan.*','COALESCE(penjualan.cetak,0) as totalcetak','customer.name','customer.alamat','customer.telephone','customer.title','customer.email','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'penjualan.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=> 'penjualan.gudang_id',
			'secondtable'	=> 'gudang.gudang_id',
		);

		/*$join[]=array(
			'tablename'	=> 'sales_order',
			'firsttable'	=> 'penjualan.no_so',
			'secondtable'	=> 'sales_order.id',
		);*/

		$data = array(
			'penjualan.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_penjualan->getPenjualanDetail($column,$join,$data,array());
		if(!empty($trans['user_cetak'])){
		$trans['reqcetak']=$this->model_user_user->getUser($trans['user_cetak']);
		}
		if(!empty($trans['user_setuju'])){
		$trans['usersetujui']=$this->model_user_user->getUser($trans['user_setuju']);

		}
		$trans['setujui']=$custdata;


		//$sales=$this->model_user_user->getUser($trans['sales']);
		//$trans['sales']=$sales['firstname'];
		$sopir=$this->model_user_user->getUser($trans['sopir']);
		//$trans['sales']=$sales['firstname'];
		$trans['sopir']=$sopir['firstname'];
		$trans['kernets']=$this->model_sale_penjualan->getPenjualanKernets(array('tttk_id'	=> $order_id));
		$products=$this->model_sale_penjualan->getPenjualanProducts(array('sales_order_id'	=> $order_id));

		$salesorder="";
		$cekso=array();
		$i=1;

		$this->load->model('user/user');
		$this->data['canceldata']=$this->model_user_user->getAksesData($this->user->getId(),4);

		foreach($products as $p){

			if(!isset($cekso[$p['no_salesorder']])){
				if($i != 1){
					$salesorder .=", ";
				}
				$salesorder .=$p['no_salesorder'];
				$cekso[$p['no_salesorder']]=1;
			}
			$i++;
		}

		$trans['salesorder']=$salesorder;

		$this->data['order']=$trans;
		$this->data['products']=$products;
		$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);

		$comp=array(
			'compname' => $this->config->get('config_name'),
			'address'	=> $this->config->get('config_address'),
			'email'	=> $this->config->get('config_email'),
			'phone'	=> $this->config->get('config_telephone'),
			'fax'	=> $this->config->get('config_fax'),
			'web'	=> 'http://nissonindonesia.com'
		);

		$this->load->model('catalog/title');
		$trans['titlename']=$this->model_catalog_title->getTitle($trans['title']);

		$this->data['fulldetail']=array(
			'order'	=> $trans,
			'products'	=> $products,
			'address'	=> $this->data['address'],
			'comp'	=> $comp
		);
		
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";
			print_r($this->data['fulldetail']);
			exit;
		}

		//print_r($this->data['fulldetail']);

		$this->data['printer']=$this->config->get('config_printer');
		$this->data['printerstatus']=$this->config->get('config_printer_status');

		//print_r($this->model_sale_customer->getAddress($trans['address_id']));
		$this->data['cancel']= $this->url->link('sale/penjualan/belumdifaktur', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['suratjalan']= $this->url->link('sale/penjualan/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=2'. $url, 'SSL');
		$this->data['invoice']= $this->url->link('sale/penjualan/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=3'. $url, 'SSL');
		if($this->request->get['view'] == 1){
			$this->template = 'sale/penjualan_info.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

		}
		/*if($this->request->get['view'] == 2){
			$this->template = 'sale/suratjalan.tpl';
		}*/
		if($this->request->get['view'] == 3){
			$this->template = 'sale/invoice.tpl';
		}



		$this->response->setOutput($this->render());
	}
	
	public function exporttoexcel(){
		//echo "<pre>";print_r($this->cetakexcel());
		$this->xlscreation_direct();
	}	
	// baru 19 September 2019
	function xlscreation_direct() {

		$reportdetails = $this->cetakexcel();
		$objPHPExcel = new PHPExcel(); 
		$objPHPExcel->getProperties()
				->setCreator("IT Division")
				->setLastModifiedBy("IT Division")
				->setTitle("Mom & Bab ")
				->setSubject("Mom & Bab")
				->setDescription("Export Item to Excel")
				->setKeywords("IT Division")
				->setCategory("IT Division");

		// Set the active Excel worksheet to sheet 0
		$objPHPExcel->setActiveSheetIndex(0); 
		// Initialise the Excel row number
		$rowCount = 0; 
		// Sheet cells
		/*$cell_definition = array(
			'A' => 'BrandIcon',
			'B' => 'Comapany',
			'C' => 'Rank',
			'D' => 'Link'
			
			'Tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
					'Gudang'        => $result['nama'],
					'No.SJ'        => $result['no_sj'],
					'No.SO'	=> $result['no_salesorder'],
					'ProduK'        => $result['namaproduct'],
					//'Satuan'        => $result['namasatuan'],
					'Quantity'        => $result['quantity']." ".$result['namasatuan'],
					'no_invoice'        => empty($result['no_invoice'])?'Belum Ada Invoice':$result['no_invoice'],
					'no_faktur'        => empty($result['invoice_id'])?'Belum Ada Invoice':$result['no_faktur'],
					'total'        => $this->currency->format($result['total']),
					'Customer'	=> $result['name'],
					'Email'	=> $result['email'],
					'Telephone'	=> $result['telephone'],
					'Status Pengiriman'	=> $s
					
		);*/
		$cell_definition = array(
			'A' => 'Tanggal',
			'B' => 'Gudang',
			'C' => 'NO.SJ',
			'D' => 'No.SO',
			'E' => 'Produk',
			'F' => 'Quantity',
			'G' => 'Customer',
			'H' => 'Status Pengiriman'
		);

		// Build headers
		foreach( $cell_definition as $column => $value )
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension("{$column}")->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value ); 
		}

		// Build cells
		while( $rowCount < count($reportdetails) ){ 
			$cell = $rowCount + 2;
			foreach( $cell_definition as $column => $value ) {

				$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(35); 
				
				switch ($value) {
					case 'Gambar':
						if (file_exists($reportdetails[$rowCount][$value])) {
							$objDrawing = new PHPExcel_Worksheet_Drawing();
							$objDrawing->setName('Customer Signature');
							$objDrawing->setDescription('Customer Signature');
							//Path to signature .jpg file
							$signature = $reportdetails[$rowCount][$value];    
							$objDrawing->setPath($signature);
							$objDrawing->setOffsetX(5);                     //setOffsetX works properly
							$objDrawing->setOffsetY(5);                     //setOffsetY works properly
							$objDrawing->setCoordinates($column.$cell);             //set image to cell 
							$objDrawing->setWidth(40);  
							$objDrawing->setHeight(40);                     //signature height  
							$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 
						} else {
							$objPHPExcel->getActiveSheet()->setCellValue($column.$cell,"No Image"); 
						}
						break;
					

					default:
						$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, $reportdetails[$rowCount][$value] ); 
						break;
				}

			}
				
			$rowCount++; 
		} 

		$rand = rand(1234, 9898);
		$presentDate = date('d-m-Y H:i:s');
		//$fileName = "Produk_Gudang_" . $rand . "_" . $presentDate . ".xls";
		$fileName = "SJ_Belum_Difakturkan_".$presentDate.".xls";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		die();
	}
	
	public function cetakexcel($display = null) {
		$this->load->model('sale/penjualan');
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_invoice'])) {
			$filter_invoice = null;
		} else {
			$filter_invoice = null;
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$filter_sales_order = $this->request->get['filter_sales_order'];
		} else {
			$filter_sales_order = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$filter_shipping_method = $this->request->get['filter_shipping_method'];
		} else {
			$filter_shipping_method = null;
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$filter_tanggal_awal = $this->request->get['filter_tanggal_awal'];
		} else {
			$filter_tanggal_awal = null;
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$filter_tanggal_akhir = $this->request->get['filter_tanggal_akhir'];
		} else {
			$filter_tanggal_akhir = null;
		}
		if (isset($this->request->get['filter_sales_order'])) {
			$filter_sales_order = $this->request->get['filter_sales_order'];
		} else {
			$filter_sales_order = null;
		}

		if (isset($this->request->get['filter_statustabung'])) {
			$filter_statustabung= $this->request->get['filter_statustabung'];
		} else {
			$filter_statustabung = null;
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/penjualan/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/penjualan/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['penjualans'] = array();


		$column=array('penjualan_product.sales_order_id','penjualan_product.product_id','penjualan_product.quantity','penjualan_product.no_so','penjualan_product.invoice_id','satuan.name as namasatuan','product.name as namaproduct','penjualan.*','sales_order.no_so as no_salesorder','gudang.nama','customer.name','customer.alamat','customer.telephone','customer.email','invoice.no_faktur');
		$join=array();
			$join[]=array(
		  'tablename' => 'penjualan_product',
		  'firsttable'  => 'penjualan.id',
		  'secondtable' => 'penjualan_product.sales_order_id'
		);
			$join[]=array(
				'tablename'	=> 'gudang',
				'firsttable'	=> 'penjualan.gudang_id',
				'secondtable'	=> 'gudang.gudang_id',
			);
		$join[]=array(
		  'tablename' => 'product',
		  'firsttable'  => 'penjualan_product.product_id',
		  'secondtable' => 'product.product_id'
		);

		$join[]=array(
		  'tablename' => 'sales_order',
		  'firsttable'  => 'penjualan_product.no_so',
		  'secondtable' => 'sales_order.id'
		);

		$leftjoin=array();
			$leftjoin[]=array(
				'tablename'	=> 'customer',
				'firsttable'	=> 'penjualan.customer_id',
				'secondtable'	=> 'customer.customer_id',
			);

		$leftjoin[]=array(
		  'tablename' => 'satuan',
		  'firsttable'  => 'product.satuan',
		  'secondtable' => 'satuan.id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'invoice',
			'firsttable'	=> 'penjualan_product.invoice_id',
			'secondtable'	=> 'invoice.id',
		);

		$this->load->model('catalog/gudang');

		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs(true);
		$gudangs=array();
		if(empty($filter_gudang_id)){
			foreach($this->data['gudangs'] as $g){
				$gudangs[]=$g['gudang_id'];
			}
		}else{
			$gudangs[]=$filter_gudang_id;
		}
		//print_r($gudangs);

		$arrsql=implode(',',$gudangs);
		$data=array();
		if(isset($filter_tanggal_awal) && !isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):$filter_tanggal_awal,
			);
		}
		else if(!isset($filter_tanggal_awal) && isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):$filter_tanggal_akhir,
			);
		}
		else if(isset($filter_tanggal_awal) && isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):array('>=',$filter_tanggal_awal),
				'penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):array('<=',$filter_tanggal_akhir),
			);
		}

		$data += array(
			'penjualan.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'penjualan.gudang_id'	=>array('IN',$arrsql),
			'penjualan.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			/* 'penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):array('>=',$filter_tanggal_awal),
			'penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):array('<=',$filter_tanggal_akhir), */
			//'penjualan.total'	=> empty($filter_sales_order)?array('>=',0):$filter_sales_order,
			'penjualan.status'	=> empty($filter_status)?array('>=',0):$filter_status,
			//'penjualan_product.invoice_id'	=> empty($filter_invoice)?array('>=',0):$filter_invoice,
			'penjualan_product.no_so'	=> empty($filter_sales_order)?array('>=',0):$filter_sales_order,
			//'penjualan.no_invoice'	=> $filter_shipping_method != null ?array('<>',):$filter_shipping_method,
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		if(!empty($filter_invoice)){
			if($filter_invoice != 'na'){
				$data['penjualan_product.invoice_id']=$filter_invoice;
			}else{
				$data['penjualan_product.invoice_id']=array('<',1);
			}
		}
		$order=array(
			'penjualan.id'	=> 'DESC',

		);
		//$offset=($page - 1) * $this->config->get('config_admin_limit');
		//$limit=$this->config->get('config_admin_limit');

		//$results = $this->model_sale_penjualan->getPenjualans($column,$join,$leftjoin,$data,$order,$limit,$offset);
		$datas = array(
			'filter_sales_order' => $filter_sales_order,
			'filter_order_id' => $filter_order_id,
			'filter_gudang_id' => $filter_gudang_id,
			'filter_customer_id' => $filter_customer_id,
			'status'	=> $filter_status,
			'filter_tanggal_awal' => $filter_tanggal_awal,
			'filter_tanggal_akhir' => $filter_tanggal_akhir,
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		$results = $this->model_sale_penjualan->sjbelumdifaktur($datas);
		//echo "<pre>";print_r($results);exit;
		$tot = $this->model_sale_penjualan->totalsjbelumdifaktur($datas);
		$product_total = count($tot);

		$this->load->model('sale/invoice');
		$this->load->model('catalog/gudang');

		$this->load->model('user/user');
		/*$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$data['customer.sales']=$this->user->getId();
		}*/

		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),4);


		//print_r($results);
		$s=null;
		foreach ($results as $result) {
			//if(in_array($result['gudang_id'],$gudangs)){
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('sale/penjualan/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'], 'SSL')
			);

								if($result['status'] == 1){
                                  $s ='Proses Kirim';
                                }
                                if($result['status'] == 2){
                                  $s = 'Diterima';
                                }
                                if($result['status'] == 3){
                                  $s = 'Dibatalkan';
                                }
			//if(empty($result['invoice_id'])){
				$this->data['penjualans'][] = array(
					'Tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
					'Gudang'        => $result['nama'],
					'NO.SJ'        => $result['no_sj'],
					'No.SO'	=> $result['no_salesorder'],
					'Produk'        => $result['namaproduct'],
					//'Satuan'        => $result['namasatuan'],
					'Quantity'        => $result['quantity']." ".$result['namasatuan'],
					'no_invoice'        => empty($result['no_invoice'])?'Belum Ada Invoice':$result['no_invoice'],
					'no_faktur'        => empty($result['invoice_id'])?'Belum Ada Invoice':$result['no_faktur'],
					'total'        => $this->currency->format($result['total']),
					'Customer'	=> $result['name'],
					'Email'	=> $result['email'],
					'Telephone'	=> $result['telephone'],
					'Status Pengiriman'	=> $s
					//'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected'])
					//'action'      => $action
				);
			//}

		}
		return $this->data['penjualans'];		
	}
	
	public function cetakbiasa() {
		$this->load->model('sale/penjualan');
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_invoice'])) {
			$filter_invoice = null;
		} else {
			$filter_invoice = null;
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$filter_sales_order = $this->request->get['filter_sales_order'];
		} else {
			$filter_sales_order = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$filter_shipping_method = $this->request->get['filter_shipping_method'];
		} else {
			$filter_shipping_method = null;
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$filter_tanggal_awal = $this->request->get['filter_tanggal_awal'];
		} else {
			$filter_tanggal_awal = null;
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$filter_tanggal_akhir = $this->request->get['filter_tanggal_akhir'];
		} else {
			$filter_tanggal_akhir = null;
		}
		if (isset($this->request->get['filter_sales_order'])) {
			$filter_sales_order = $this->request->get['filter_sales_order'];
		} else {
			$filter_sales_order = null;
		}

		if (isset($this->request->get['filter_statustabung'])) {
			$filter_statustabung= $this->request->get['filter_statustabung'];
		} else {
			$filter_statustabung = null;
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/penjualan/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/penjualan/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['penjualans'] = array();


		$column=array('penjualan_product.sales_order_id','penjualan_product.product_id','penjualan_product.quantity','penjualan_product.no_so','penjualan_product.invoice_id','satuan.name as namasatuan','product.name as namaproduct','penjualan.*','sales_order.no_so as no_salesorder','gudang.nama','customer.name','customer.alamat','customer.telephone','customer.email','invoice.no_faktur');
		$join=array();
			$join[]=array(
		  'tablename' => 'penjualan_product',
		  'firsttable'  => 'penjualan.id',
		  'secondtable' => 'penjualan_product.sales_order_id'
		);
			$join[]=array(
				'tablename'	=> 'gudang',
				'firsttable'	=> 'penjualan.gudang_id',
				'secondtable'	=> 'gudang.gudang_id',
			);
		$join[]=array(
		  'tablename' => 'product',
		  'firsttable'  => 'penjualan_product.product_id',
		  'secondtable' => 'product.product_id'
		);

		$join[]=array(
		  'tablename' => 'sales_order',
		  'firsttable'  => 'penjualan_product.no_so',
		  'secondtable' => 'sales_order.id'
		);

		$leftjoin=array();
			$leftjoin[]=array(
				'tablename'	=> 'customer',
				'firsttable'	=> 'penjualan.customer_id',
				'secondtable'	=> 'customer.customer_id',
			);

		$leftjoin[]=array(
		  'tablename' => 'satuan',
		  'firsttable'  => 'product.satuan',
		  'secondtable' => 'satuan.id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'invoice',
			'firsttable'	=> 'penjualan_product.invoice_id',
			'secondtable'	=> 'invoice.id',
		);

		$this->load->model('catalog/gudang');

		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs(true);
		$gudangs=array();
		if(empty($filter_gudang_id)){
			foreach($this->data['gudangs'] as $g){
				$gudangs[]=$g['gudang_id'];
			}
		}else{
			$gudangs[]=$filter_gudang_id;
		}
		//print_r($gudangs);

		$arrsql=implode(',',$gudangs);
		$data=array();
		if(isset($filter_tanggal_awal) && !isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):$filter_tanggal_awal,
			);
		}
		else if(!isset($filter_tanggal_awal) && isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):$filter_tanggal_akhir,
			);
		}
		else if(isset($filter_tanggal_awal) && isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):array('>=',$filter_tanggal_awal),
				'penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):array('<=',$filter_tanggal_akhir),
			);
		}

		$data += array(
			'penjualan.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'penjualan.gudang_id'	=>array('IN',$arrsql),
			'penjualan.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			/* 'penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):array('>=',$filter_tanggal_awal),
			'penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):array('<=',$filter_tanggal_akhir), */
			//'penjualan.total'	=> empty($filter_sales_order)?array('>=',0):$filter_sales_order,
			'penjualan.status'	=> empty($filter_status)?array('>=',0):$filter_status,
			//'penjualan_product.invoice_id'	=> empty($filter_invoice)?array('>=',0):$filter_invoice,
			'penjualan_product.no_so'	=> empty($filter_sales_order)?array('>=',0):$filter_sales_order,
			//'penjualan.no_invoice'	=> $filter_shipping_method != null ?array('<>',):$filter_shipping_method,
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		if(!empty($filter_invoice)){
			if($filter_invoice != 'na'){
				$data['penjualan_product.invoice_id']=$filter_invoice;
			}else{
				$data['penjualan_product.invoice_id']=array('<',1);
			}
		}
		$order=array(
			'penjualan.id'	=> 'DESC',

		);
		//$offset=($page - 1) * $this->config->get('config_admin_limit');
		//$limit=$this->config->get('config_admin_limit');

		//$results = $this->model_sale_penjualan->getPenjualans($column,$join,$leftjoin,$data,$order,$limit,$offset);
		$datas = array(
			'filter_sales_order' => $filter_sales_order,
			'filter_order_id' => $filter_order_id,
			'filter_gudang_id' => $filter_gudang_id,
			'filter_customer_id' => $filter_customer_id,
			'status'	=> $filter_status,
			'filter_tanggal_awal' => $filter_tanggal_awal,
			'filter_tanggal_akhir' => $filter_tanggal_akhir,
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		$results = $this->model_sale_penjualan->sjbelumdifaktur($datas);
		//echo "<pre>";print_r($results);exit;
		$tot = $this->model_sale_penjualan->totalsjbelumdifaktur($datas);
		$product_total = count($tot);

		$this->load->model('sale/invoice');
		$this->load->model('catalog/gudang');

		$this->load->model('user/user');
		/*$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$data['customer.sales']=$this->user->getId();
		}*/

		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),4);


		//print_r($results);
		foreach ($results as $result) {
			//if(in_array($result['gudang_id'],$gudangs)){
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('sale/penjualan/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'], 'SSL')
			);

			

		
			//if(empty($result['invoice_id'])){
				$this->data['penjualans'][] = array(
					'id' => $result['id'],
					'customer_id'        => $result['customer_id'],
					'no_sj'        => $result['no_sj'],
					'no_so'        => $result['no_so'],
					'nama'        => $result['nama'],
					'namaproduct'        => $result['namaproduct'],
					'satuan'        => $result['namasatuan'],
					'quantity'        => $result['quantity'],
					'nom_so'	=> $result['nom_so'],
					'no_salesorder'	=> $result['no_salesorder'],
					'no_invoice'        => empty($result['no_invoice'])?'Belum Ada Invoice':$result['no_invoice'],
					'no_faktur'        => empty($result['invoice_id'])?'Belum Ada Invoice':$result['no_faktur'],
					'total'        => $this->currency->format($result['total']),
					'name'	=> $result['name'],
					'email'	=> $result['email'],
					'telephone'	=> $result['telephone'],
					'status'	=> $result['status'],
					'tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
					'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
					'action'      => $action
				);
			//}

		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['warning'])) {
			$this->data['error_warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/penjualan/belumdifaktur', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_tanggal_awal']	= $filter_tanggal_awal;
		$this->data['filter_tanggal_akhir']	= $filter_tanggal_akhir;
		$this->data['filter_gudang_id']	= $filter_gudang_id;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'sale/penjualan_listbelumdifakturcetakbiasa.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	// end baru
	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Daftar Penjualan');

		$this->load->model('sale/penjualan');

		$this->getList();
	}
	/*public function terima(){
		$this->load->model('sale/penjualan');
		$this->document->setTitle('Pengiriman Barang');
		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];

				//get Penjualan
				$penj=$this->model_sale_penjualan->getPenjualan(array('id'=>$order_id));
				if($penj['status'] == 1){
					$this->model_sale_penjualan->updatePenjualan(array('status' => 2),array('id'	=> $order_id));
					$this->session->data['success'] = 'Sukses: Data Pengiriman Barang berhasil diterima';
				}else{
					if($penj['status'] == 2){
						$this->session->data['warning'] = 'Peringatan: Data Pengiriman Barang telah diterima';
					}else{
						$this->session->data['warning'] = 'Peringatan: Data Pengiriman Barang telah dibatalkan';
					}
				}
				$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	}*/
	public function terima(){
		$this->document->setTitle('Daftar Penjualan');
		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->load->model('sale/penjualan');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
      		$this->model_sale_penjualan->terimaPenjualan($this->request->post,$this->request->get['order_id']);

			$this->session->data['success'] = 'Sukses: Pengiriman berhasil diterima';
			$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

	
		$this->load->model('sale/customer');

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),6);

		$column=array('penjualan.*','COALESCE(penjualan.cetak,0) as totalcetak','customer.name','customer.alamat','customer.telephone','customer.title','customer.email','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'penjualan.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=> 'penjualan.gudang_id',
			'secondtable'	=> 'gudang.gudang_id',
		);

		/*$join[]=array(
			'tablename'	=> 'sales_order',
			'firsttable'	=> 'penjualan.no_so',
			'secondtable'	=> 'sales_order.id',
		);*/

		

		$data = array(
			'penjualan.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_penjualan->getPenjualanDetail($column,$join,$data,array());
		if(!empty($trans['user_cetak'])){
		$trans['reqcetak']=$this->model_user_user->getUser($trans['user_cetak']);
		}
		if(!empty($trans['user_setuju'])){
		$trans['usersetujui']=$this->model_user_user->getUser($trans['user_setuju']);

		}
		$trans['setujui']=$custdata;

		$this->load->model('sale/deliveryorder');
		if($trans['do_id'] > 0){
			$do=$this->model_sale_deliveryorder->getPenjualan(array('id'=>$trans['do_id']));
			$trans['no_do']=$do['no_do'];
			$trans['hrefdo']=$this->url->link('sale/deliveryorder/tampil', 'token=' . $this->session->data['token'] .'&view=1&order_id='.$trans['do_id'], 'SSL');
		}else{
			$trans['no_do']='';
		}
		//$sales=$this->model_user_user->getUser($trans['sales']);
		//$trans['sales']=$sales['firstname'];
		$sopir=$this->model_user_user->getUser($trans['sopir']);
		//$trans['sales']=$sales['firstname'];
		$trans['sopir']=$sopir['firstname'];
		$trans['kernets']=$this->model_sale_penjualan->getPenjualanKernets(array('tttk_id'	=> $order_id));
		$products=$this->model_sale_penjualan->getPenjualanProducts(array('sales_order_id'	=> $order_id));

		$salesorder="";
		$cekso=array();
		$i=1;
		$this->data['token']=$this->request->get['token'];
		$this->load->model('user/user');
		$this->data['canceldata']=$this->model_user_user->getAksesData($this->user->getId(),4);
		$trans['jenispenjualan']=2;
		foreach($products as $p){
			if($p['jenispenjualan'] == 1){
				$trans['jenispenjualan'] =1;
			}
			if(!isset($cekso[$p['no_salesorder']])){
				if($i != 1){
					$salesorder .=", ";
				}
				$salesorder .=$p['no_salesorder'];
				$cekso[$p['no_salesorder']]=1;
			}
			$i++;
		}

		$trans['salesorder']=$salesorder;

		$this->data['order']=$trans;
		$this->data['products']=$products;
		$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);

	
		$this->load->model('catalog/title');
		$trans['titlename']=$this->model_catalog_title->getTitle($trans['title']);

		

		
		//print_r($this->model_sale_customer->getAddress($trans['address_id']));
		$this->data['cancel']= $this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('sale/penjualan/terima', 'token=' . $this->session->data['token'] .'&order_id='.$this->request->get['order_id']. $url, 'SSL');

		//if($this->request->get['view'] == 1){
			$this->template = 'sale/penjualan_terima.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

		//}
		/*if($this->request->get['view'] == 2){
			$this->template = 'sale/suratjalan.tpl';
		}
		if($this->request->get['view'] == 3){
			$this->template = 'sale/invoice.tpl';
		}

		*/

		$this->response->setOutput($this->render());
	}

	public function batalkanproduk(){
		$this->load->model('sale/penjualan');
		$this->document->setTitle('Pengiriman Barang');
		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}$this->load->model('user/user');


			$canceldata=$this->model_user_user->getAksesData($this->user->getId(),4);
			if($canceldata == 1){
			if(isset($this->request->get['order_id'])){
				if(!empty($this->request->get['order_id'])){
					$order_id=$this->request->get['order_id'];
					$penj=$this->model_sale_penjualan->getPenjualan(array('id'=>$order_id));
					if($penj['status'] == 1){

						$this->model_sale_penjualan->cancelPenjualan($order_id);
						$this->session->data['success'] = 'Sukses: Data Pengiriman Barang berhasil dibatalkan';
					}else{
						if($penj['status'] == 2){
							$this->session->data['warning'] = 'Peringatan: Data Pengiriman Barang telah diterima';
						}else{
							$this->session->data['warning'] = 'Peringatan: Data Pengiriman Barang telah dibatalkan';
						}
					}
					$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}else{
					$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}
			}else{
				$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->session->data['warning'] = 'Anda tidak diijinkan untuk membatalkan Surat Jalan';
			$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}
	}

	public function batalkan(){
		$this->load->model('sale/penjualan');
		$this->document->setTitle('Pengiriman Barang');
		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}$this->load->model('user/user');


			$canceldata=$this->model_user_user->getAksesData($this->user->getId(),5);
			if($canceldata == 1){
			if(isset($this->request->get['order_id'])){
				if(!empty($this->request->get['order_id'])){
					$order_id=$this->request->get['order_id'];
					$penj=$this->model_sale_penjualan->getPenjualan(array('id'=>$order_id));
					if($penj['status'] == 1){
						$cancel=$this->model_sale_penjualan->cancelPenjualan($order_id);
						if($cancel){
								$this->session->data['success'] = 'Sukses: Data Pengiriman Barang berhasil dibatalkan';
						}else{
								$this->session->data['warning'] = 'Peringatan: Data Pengiriman Barang gagal dibatalkan';
						}

					}else{
						if($penj['status'] == 2){
							$this->session->data['warning'] = 'Peringatan: Data Pengiriman Barang telah diterima';
						}else{
							$this->session->data['warning'] = 'Peringatan: Data Pengiriman Barang telah dibatalkan';
						}
					}
					$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}else{
					$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}
			}else{
				$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->session->data['warning'] = 'Anda tidak diijinkan untuk membatalkan Surat Jalan';
			$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}
	}


	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Daftar Penjualan');

		$this->load->model('sale/penjualan');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_penjualan->updateOrderPenjualan($this->request->post, array('id'=>$this->request->get['id']));

			$this->session->data['success'] = 'Daftar Penjualan berhasil diperbarui';

			$url = '';

			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
			}
			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			if (isset($this->request->get['filter_invoice'])) {
				$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
			}
			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_sales_order'])) {
				$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	// baru 12 September 2019
	
	public function belumdifaktur() {
		$this->load->model('sale/penjualan');
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_invoice'])) {
			$filter_invoice = null;
		} else {
			$filter_invoice = null;
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$filter_sales_order = $this->request->get['filter_sales_order'];
		} else {
			$filter_sales_order = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$filter_shipping_method = $this->request->get['filter_shipping_method'];
		} else {
			$filter_shipping_method = null;
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$filter_tanggal_awal = $this->request->get['filter_tanggal_awal'];
		} else {
			$filter_tanggal_awal = null;
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$filter_tanggal_akhir = $this->request->get['filter_tanggal_akhir'];
		} else {
			$filter_tanggal_akhir = null;
		}
		if (isset($this->request->get['filter_sales_order'])) {
			$filter_sales_order = $this->request->get['filter_sales_order'];
		} else {
			$filter_sales_order = null;
		}

		if (isset($this->request->get['filter_statustabung'])) {
			$filter_statustabung= $this->request->get['filter_statustabung'];
		} else {
			$filter_statustabung = null;
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/penjualan/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/penjualan/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['penjualans'] = array();


		$column=array('penjualan_product.sales_order_id','penjualan_product.product_id','penjualan_product.quantity','penjualan_product.no_so','penjualan_product.invoice_id','satuan.name as namasatuan','product.name as namaproduct','penjualan.*','sales_order.no_so as no_salesorder','gudang.nama','customer.name','customer.alamat','customer.telephone','customer.email','invoice.no_faktur');
		$join=array();
			$join[]=array(
		  'tablename' => 'penjualan_product',
		  'firsttable'  => 'penjualan.id',
		  'secondtable' => 'penjualan_product.sales_order_id'
		);
			$join[]=array(
				'tablename'	=> 'gudang',
				'firsttable'	=> 'penjualan.gudang_id',
				'secondtable'	=> 'gudang.gudang_id',
			);
		$join[]=array(
		  'tablename' => 'product',
		  'firsttable'  => 'penjualan_product.product_id',
		  'secondtable' => 'product.product_id'
		);

		$join[]=array(
		  'tablename' => 'sales_order',
		  'firsttable'  => 'penjualan_product.no_so',
		  'secondtable' => 'sales_order.id'
		);

		$leftjoin=array();
			$leftjoin[]=array(
				'tablename'	=> 'customer',
				'firsttable'	=> 'penjualan.customer_id',
				'secondtable'	=> 'customer.customer_id',
			);

		$leftjoin[]=array(
		  'tablename' => 'satuan',
		  'firsttable'  => 'product.satuan',
		  'secondtable' => 'satuan.id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'invoice',
			'firsttable'	=> 'penjualan_product.invoice_id',
			'secondtable'	=> 'invoice.id',
		);

		$this->load->model('catalog/gudang');

		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs(true);
		$gudangs=array();
		if(empty($filter_gudang_id)){
			foreach($this->data['gudangs'] as $g){
				$gudangs[]=$g['gudang_id'];
			}
		}else{
			$gudangs[]=$filter_gudang_id;
		}
		//print_r($gudangs);

		$arrsql=implode(',',$gudangs);
		$data=array();
		if(isset($filter_tanggal_awal) && !isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):$filter_tanggal_awal,
			);
		}
		else if(!isset($filter_tanggal_awal) && isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):$filter_tanggal_akhir,
			);
		}
		else if(isset($filter_tanggal_awal) && isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):array('>=',$filter_tanggal_awal),
				'penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):array('<=',$filter_tanggal_akhir),
			);
		}

		$data += array(
			'penjualan.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'penjualan.gudang_id'	=>array('IN',$arrsql),
			'penjualan.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			/* 'penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):array('>=',$filter_tanggal_awal),
			'penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):array('<=',$filter_tanggal_akhir), */
			//'penjualan.total'	=> empty($filter_sales_order)?array('>=',0):$filter_sales_order,
			'penjualan.status'	=> empty($filter_status)?array('>=',0):$filter_status,
			//'penjualan_product.invoice_id'	=> empty($filter_invoice)?array('>=',0):$filter_invoice,
			'penjualan_product.no_so'	=> empty($filter_sales_order)?array('>=',0):$filter_sales_order,
			//'penjualan.no_invoice'	=> $filter_shipping_method != null ?array('<>',):$filter_shipping_method,
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		if(!empty($filter_invoice)){
			if($filter_invoice != 'na'){
				$data['penjualan_product.invoice_id']=$filter_invoice;
			}else{
				$data['penjualan_product.invoice_id']=array('<',1);
			}
		}
		$order=array(
			'penjualan.id'	=> 'DESC',

		);
		//$offset=($page - 1) * $this->config->get('config_admin_limit');
		//$limit=$this->config->get('config_admin_limit');

		//$results = $this->model_sale_penjualan->getPenjualans($column,$join,$leftjoin,$data,$order,$limit,$offset);
		$datas = array(
			'filter_sales_order' => $filter_sales_order,
			'filter_order_id' => $filter_order_id,
			'filter_gudang_id' => $filter_gudang_id,
			'filter_customer_id' => $filter_customer_id,
			'status'	=> $filter_status,
			'filter_tanggal_awal' => $filter_tanggal_awal,
			'filter_tanggal_akhir' => $filter_tanggal_akhir,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);
		$results = $this->model_sale_penjualan->sjbelumdifaktur($datas);
		//echo "<pre>";print_r($results);exit;
		$tot = $this->model_sale_penjualan->totalsjbelumdifaktur($datas);
		$product_total = count($tot);

		$this->load->model('sale/invoice');
		$this->load->model('catalog/gudang');

		$this->load->model('user/user');
		/*$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$data['customer.sales']=$this->user->getId();
		}*/

		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),4);


		//print_r($results);
		foreach ($results as $result) {
			//if(in_array($result['gudang_id'],$gudangs)){
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('sale/penjualan/tampilbelumdifaktur', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'], 'SSL')
			);

			

		
			//if(empty($result['invoice_id'])){
				$this->data['penjualans'][] = array(
					'id' => $result['id'],
					'customer_id'        => $result['customer_id'],
					'no_sj'        => $result['no_sj'],
					'no_so'        => $result['no_so'],
					'nama'        => $result['nama'],
					'namaproduct'        => $result['namaproduct'],
					'satuan'        => $result['namasatuan'],
					'quantity'        => $result['quantity'],
					'nom_so'	=> $result['nom_so'],
					'no_salesorder'	=> $result['no_salesorder'],
					'no_invoice'        => empty($result['no_invoice'])?'Belum Ada Invoice':$result['no_invoice'],
					'no_faktur'        => empty($result['invoice_id'])?'Belum Ada Invoice':$result['no_faktur'],
					'total'        => $this->currency->format($result['total']),
					'name'	=> $result['name'],
					'email'	=> $result['email'],
					'telephone'	=> $result['telephone'],
					'status'	=> $result['status'],
					'tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
					'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
					'action'      => $action
				);
			//}

		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['warning'])) {
			$this->data['error_warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/penjualan/belumdifaktur', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_tanggal_awal']	= $filter_tanggal_awal;
		$this->data['filter_tanggal_akhir']	= $filter_tanggal_akhir;
		$this->data['filter_gudang_id']	= $filter_gudang_id;
		$this->data['token'] = $this->session->data['token'];
		
		$this->data['exporttoexcel'] = $this->url->link('sale/penjualan/exporttoexcel', 'token=' . $this->session->data['token'] . $url , 'SSL');
		$this->data['printbiasa'] = $this->url->link('sale/penjualan/cetakbiasa', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->template = 'sale/penjualan_listbelumdifaktur.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	
	// end baru
	
	
	
	private function getList() {
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_invoice'])) {
			$filter_invoice = $this->request->get['filter_invoice'];
		} else {
			$filter_invoice = null;
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$filter_sales_order = $this->request->get['filter_sales_order'];
		} else {
			$filter_sales_order = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$filter_shipping_method = $this->request->get['filter_shipping_method'];
		} else {
			$filter_shipping_method = null;
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$filter_tanggal_awal = $this->request->get['filter_tanggal_awal'];
		} else {
			$filter_tanggal_awal = null;
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$filter_tanggal_akhir = $this->request->get['filter_tanggal_akhir'];
		} else {
			$filter_tanggal_akhir = null;
		}
		if (isset($this->request->get['filter_sales_order'])) {
			$filter_sales_order = $this->request->get['filter_sales_order'];
		} else {
			$filter_sales_order = null;
		}

		if (isset($this->request->get['filter_statustabung'])) {
			$filter_statustabung= $this->request->get['filter_statustabung'];
		} else {
			$filter_statustabung = null;
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/penjualan/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/penjualan/delete', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['downloadexcel'] = $this->url->link('sale/penjualan/downloadexcel', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['penjualans'] = array();

		//$column=array('aset.penjualan_pameran_id','aset.name as name','aset.tglpembelian','aset.hargabeli','aset.status','kelompok_aset.name as kelompok','kelompok_aset.jenis_aset as jenis');
		/*$column=array('penjualan.*','gudang.nama','customer.name','customer.alamat','customer.telephone','customer.email');
		$join=array();
		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'penjualan.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=> 'penjualan.gudang_id',
			'secondtable'	=> 'gudang.gudang_id',
		);

		$order=array(
			'id'	=> 'DESC',

		);
		$this->load->model('catalog/gudang');

		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs(true);
		$gudangs=array();
		if(empty($filter_gudang_id)){
			foreach($this->data['gudangs'] as $g){
				$gudangs[]=$g['gudang_id'];
			}
		}else{
			$gudangs[]=$filter_gudang_id;
		}
		//print_r($gudangs);

		$arrsql=implode(',',$gudangs);
		$data=array();
		if(isset($filter_tanggal_awal) && !isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):$filter_tanggal_awal,
			);
		}
		else if(!isset($filter_tanggal_awal) && isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):$filter_tanggal_akhir,
			);
		}
		else if(isset($filter_tanggal_awal) && isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):array('>=',$filter_tanggal_awal),
				'penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):array('<=',$filter_tanggal_akhir),
			);
		}*/

		$column=array('penjualan_product.sales_order_id','penjualan_product.product_id','penjualan_product.quantity','penjualan_product.no_so','penjualan_product.invoice_id','satuan.name as namasatuan','product.name as namaproduct','penjualan.*','sales_order.no_so as no_salesorder','gudang.nama','customer.name','customer.alamat','customer.telephone','customer.email','invoice.no_faktur','penjualan_product.quantityreturn');
		$join=array();
			$join[]=array(
		  'tablename' => 'penjualan_product',
		  'firsttable'  => 'penjualan.id',
		  'secondtable' => 'penjualan_product.sales_order_id'
		);
			$join[]=array(
				'tablename'	=> 'gudang',
				'firsttable'	=> 'penjualan.gudang_id',
				'secondtable'	=> 'gudang.gudang_id',
			);
		$join[]=array(
		  'tablename' => 'product',
		  'firsttable'  => 'penjualan_product.product_id',
		  'secondtable' => 'product.product_id'
		);

		$join[]=array(
		  'tablename' => 'sales_order',
		  'firsttable'  => 'penjualan_product.no_so',
		  'secondtable' => 'sales_order.id'
		);

		$leftjoin=array();
			$leftjoin[]=array(
				'tablename'	=> 'customer',
				'firsttable'	=> 'penjualan.customer_id',
				'secondtable'	=> 'customer.customer_id',
			);

		$leftjoin[]=array(
		  'tablename' => 'satuan',
		  'firsttable'  => 'product.satuan',
		  'secondtable' => 'satuan.id'
		);
		$leftjoin[]=array(
			'tablename'	=> 'invoice',
			'firsttable'	=> 'penjualan_product.invoice_id',
			'secondtable'	=> 'invoice.id',
		);

		$this->load->model('catalog/gudang');

		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs(true);
		$gudangs=array();
		if(empty($filter_gudang_id)){
			foreach($this->data['gudangs'] as $g){
				$gudangs[]=$g['gudang_id'];
			}
		}else{
			$gudangs[]=$filter_gudang_id;
		}
		//print_r($gudangs);

		$arrsql=implode(',',$gudangs);
		$data=array();
		if(isset($filter_tanggal_awal) && !isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):$filter_tanggal_awal,
			);
		}
		else if(!isset($filter_tanggal_awal) && isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):$filter_tanggal_akhir,
			);
		}
		else if(isset($filter_tanggal_awal) && isset($filter_tanggal_akhir)){
			$data+= array(
				'penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):array('>=',$filter_tanggal_awal),
				'penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):array('<=',$filter_tanggal_akhir),
			);
		}

		$data += array(
			'penjualan.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'penjualan.gudang_id'	=>array('IN',$arrsql),
			'penjualan.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			/* 'penjualan.date_added'	=> empty($filter_tanggal_awal)?array('>','1901-01-01'):array('>=',$filter_tanggal_awal),
			'penjualan.date_added '	=> empty($filter_tanggal_akhir)?array('>','1901-01-01'):array('<=',$filter_tanggal_akhir), */
			//'penjualan.total'	=> empty($filter_sales_order)?array('>=',0):$filter_sales_order,
			'penjualan.status'	=> empty($filter_status)?array('>=',0):$filter_status,
			//'penjualan_product.invoice_id'	=> empty($filter_invoice)?array('>=',0):$filter_invoice,
			'penjualan_product.no_so'	=> empty($filter_sales_order)?array('>=',0):$filter_sales_order,
			//'penjualan.no_invoice'	=> $filter_shipping_method != null ?array('<>',):$filter_shipping_method,
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		if(!empty($filter_invoice)){
			if($filter_invoice != 'na'){
				$data['penjualan_product.invoice_id']=$filter_invoice;
			}else{
				$data['penjualan_product.invoice_id']=array('<',1);
			}
		}
		$order=array(
			'penjualan.id'	=> 'DESC',

		);
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$results = $this->model_sale_penjualan->getPenjualans($column,$join,$leftjoin,$data,$order,$limit,$offset);
		$product_total = $this->model_sale_penjualan->totalPenjualans($data,$join,$leftjoin);

		$this->load->model('sale/invoice');
		$this->load->model('catalog/gudang');

		$this->load->model('user/user');
		/*$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$data['customer.sales']=$this->user->getId();
		}*/

		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),4);
		
		// baru 11 November 2019
		$editdata=$this->model_user_user->getAksesData($this->user->getId(),10);

		//print_r($results);
		foreach ($results as $result) {
			//if(in_array($result['gudang_id'],$gudangs)){
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('sale/penjualan/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'], 'SSL')
			);

			if($result['status'] != 3){
				if(!empty($result['no_dokumen'])){
					$action[] = array(
						'text' => 'Lihat Jurnal',
						'href' => $this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'] . '&filter_nodokumen=' . $result['no_dokumen'].$url, 'SSL')
					);
				}
			}
			
			if($editdata==1){
				$action[] = array(
					'text' => 'Edit',
					'href' => $this->url->link('sale/penjualan/tampil', 'token=' . $this->session->data['token'] . '&edit=1&view=10&order_id=' . $result['sales_order_id'].$url, 'SSL')
				);
			}
			
			if($this->user->getUsername()=="pawits"){
				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('sale/penjualan/batalkan', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['sales_order_id'], 'SSL')
				);
			}
			if($result['status'] == 1){
				$action[] = array(
					'text' => 'Diterima',
					'href' => $this->url->link('sale/penjualan/terima', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['sales_order_id'], 'SSL')
				);

				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('sale/penjualan/batalkan', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['sales_order_id'], 'SSL')
				);
			}

			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'customer_id'        => $result['customer_id'],
				'no_sj'        => $result['no_sj'],
				'no_so'        => $result['no_so'],
				'no_dokumen'        => $result['no_dokumen'],
				'nama'        => $result['nama'],
				'namaproduct'        => $result['namaproduct'],
				'satuan'        => $result['namasatuan'],
				'quantity'        => $result['quantity'],
				'qtyreturn'        => $result['quantityreturn'],
				//'nom_so'	=> $result['nom_so'],
				'no_salesorder'	=> $result['no_salesorder'],
				'no_invoice'        => empty($result['no_invoice'])?'Belum Ada Invoice':$result['no_invoice'],
				'no_faktur'        => empty($result['invoice_id'])?'Belum Ada Invoice':$result['no_faktur'],
				'total'        => $this->currency->format($result['total']),
				'name'	=> $result['name'],
				'email'	=> $result['email'],
				'telephone'	=> $result['telephone'],
				'status'	=> $result['status'],
				'no_dokumen'	=> $result['no_dokumen'],
				'tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'      => $action
			);

		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['warning'])) {
			$this->data['error_warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal_awal'])) {
			$url .= '&filter_tanggal_awal=' . $this->request->get['filter_tanggal_awal'];
		}
		if (isset($this->request->get['filter_tanggal_akhir'])) {
			$url .= '&filter_tanggal_akhir=' . $this->request->get['filter_tanggal_akhir'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_tanggal_awal']	= $filter_tanggal_awal;
		$this->data['filter_tanggal_akhir']	= $filter_tanggal_akhir;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'sale/penjualan_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('sale/penjualan');

			if (isset($this->request->get['q'])) {
				$filter_order_id = $this->request->get['q'];
			} else {
				$filter_order_id = '';
			}

			if (isset($this->request->get['p'])) {
				$p = $this->request->get['p'];
			} else {
				$p = '';
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_sj'         => array('LIKE',$filter_order_id),

			);
			$offset=0;
			$limit=10;

			$results = $this->model_sale_penjualan->getPenjualans(array(),array(),array(),$data,array(),10,0);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_sj']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}
	public function autocompletedetail(){
		$rests = array();



			if (isset($this->request->get['q'])) {
				$filter_order_id = $this->request->get['q'];
			} else {
				$filter_order_id = '';
			}

			if (isset($this->request->get['p'])) {
				$status_pengiriman = $this->request->get['p'];
			} else {
				$status_pengiriman = null;
			}

			if (isset($this->request->get['jenis'])) {
				$jenis = $this->request->get['jenis'];
			} else {
				$jenis = 1;
			}

			if (isset($this->request->get['jenispenjualan'])) {
				$jenispenjualan = $this->request->get['jenispenjualan'];
			} else {
				$jenispenjualan = 1;
			}

			if (isset($this->request->get['customer_id'])) {
				$customer_id = $this->request->get['customer_id'];
			} else {
				$customer_id = null;
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}
			if($jenis == 1 | $jenis == 2){
				//salesorder
				if($jenispenjualan == 1){
					//mp
					$this->load->model('sale/salesorder');
					$column=array('sales_order.no_so','sales_order.id','sales_order.total','customer.name','sales_order.date_added');
					$join=array();
					$join[]=array(
						'tablename'	=> 'customer',
						'firsttable'	=> 'sales_order.customer_id',
						'secondtable'	=> 'customer.customer_id'
					);

					$data = array(
						'no_so'         => array('LIKE',$filter_order_id),
						'sales_order.status'         => 1,
						'sales_order.customer_id'	=>$customer_id != 'null'?$customer_id:array('>=',1),


					);
					$offset=0;
					$limit=10;

					$results = $this->model_sale_salesorder->getPenjualans($column,$join,$data,array(),10,0);
					foreach($results as $r){
						$rests[]=array(
							'id'	=> $r['id'],
							'text'	=> $r['no_so'].' Customer '.$r['name'].' - Tanggal '.date('d/m/y',strtotime($r['date_added'])).' - '.' Total '.$this->currency->format($r['total']),
							'total'	=>$this->currency->format($r['total']),
							'plaintotal'	=> $r['total']
						);
					}
				}
				if($jenispenjualan == 2){
					//mr
					$this->load->model('sale/salesordermr');
					$column=array('sales_ordermr.no_so','sales_ordermr.id','sales_ordermr.total','customer.name','sales_ordermr.date_added');
					$join=array();
					$join[]=array(
						'tablename'	=> 'customer',
						'firsttable'	=> 'sales_ordermr.customer_id',
						'secondtable'	=> 'customer.customer_id'
					);

					$data = array(
						'no_so'         => array('LIKE',$filter_order_id),
						'sales_ordermr.status'         => 1,
						'sales_ordermr.customer_id'	=>$customer_id != 'null'?$customer_id:array('>=',1),


					);
					$offset=0;
					$limit=10;

					$results = $this->model_sale_salesordermr->getPenjualans($column,$join,$data,array(),10,0);
					foreach($results as $r){
						$rests[]=array(
							'id'	=> $r['id'],
							'text'	=> $r['no_so'].' Customer '.$r['name'].' - Tanggal '.date('d/m/y',strtotime($r['date_added'])).' - '.' Total '.$this->currency->format($r['total']),
							'total'	=>$this->currency->format($r['total']),
							'plaintotal'	=> $r['total']
						);
					}
				}
				if($jenispenjualan == 3){
					//bahanbaku
					$this->load->model('sale/salesorderbahanbaku');
					$column=array('sales_order_bahanbaku.no_so','sales_order_bahanbaku.id','sales_order_bahanbaku.total','customer.name','sales_order_bahanbaku.date_added');
					$join=array();
					$join[]=array(
						'tablename'	=> 'customer',
						'firsttable'	=> 'sales_order_bahanbaku.customer_id',
						'secondtable'	=> 'customer.customer_id'
					);

					$data = array(
						'no_so'         => array('LIKE',$filter_order_id),
						'sales_order_bahanbaku.status'         => 1,
						'sales_order_bahanbaku.customer_id'	=>$customer_id != 'null'?$customer_id:array('>=',1),


					);
					$offset=0;
					$limit=10;

					$results = $this->model_sale_salesorder_bahanbaku->getPenjualans($column,$join,$data,array(),10,0);
					foreach($results as $r){
						$rests[]=array(
							'id'	=> $r['id'],
							'text'	=> $r['no_so'].' Customer '.$r['name'].' - Tanggal '.date('d/m/y',strtotime($r['date_added'])).' - '.' Total '.$this->currency->format($r['total']),
							'total'	=>$this->currency->format($r['total']),
							'plaintotal'	=> $r['total']
						);
					}
				}
			}

			if($jenis == 3){
				//salesorder
				if($jenispenjualan == 1){
					//mp
					$this->load->model('sale/penjualan');
					$column=array('penjualan.no_sj','penjualan.no_invoice','penjualan.id','penjualan.customer_id','penjualan.total','customer.name','penjualan.date_added');
					$join=array();
					$join[]=array(
						'tablename'	=> 'customer',
						'firsttable'	=> 'penjualan.customer_id',
						'secondtable'	=> 'customer.customer_id'
					);

					/*if(strlen(trim($filter_order_id)) > 0){
						$data['no_sj']=$filter_order_id;
					}*/

					$data = array(
						'no_sj'         => array('LIKE',$filter_order_id),
						'penjualan.status'         => array('<',3),
						'penjualan.customer_id'	=>$customer_id != null?$customer_id:array('>=',1),
						//'no_invoice'	=> array('=','')

					);
					$offset=0;
					$limit=10;

					$this->load->model('sale/invoice');

					$results = $this->model_sale_penjualan->getPenjualans($column,$join,array(),$data,array(),0,null);
					foreach($results as $r){
						$disp=true;
						if(!empty($r['no_invoice'])){
							$cek=$this->model_sale_invoice->getPenjualan(array('no_faktur'=>$r['no_invoice'],'customer_id'=>$r['customer_id']));
							if(!empty($cek)){
								if($cek['status'] != 4){
									$disp=false;
								}
							}
						}
						if($disp){
						$rests[]=array(
							'id'	=> $r['id'],
							'text'	=> $r['no_sj'].' - Tanggal '.date('d/m/y',strtotime($r['date_added'])).' - '.' Total '.$this->currency->format($r['total']),
							'total'	=>$this->currency->format($r['total']),
							'plaintotal'	=> $r['total']
						);
						}
					}

				}
				if($jenispenjualan == 2){
					//mr
					$this->load->model('sale/penjualanmr');
					$column=array('penjualan_mr.no_sj','penjualan_mr.id','penjualan_mr.total','customer.name','penjualan_mr.date_added');
					$join=array();
					$join[]=array(
						'tablename'	=> 'customer',
						'firsttable'	=> 'penjualan_mr.customer_id',
						'secondtable'	=> 'customer.customer_id'
					);

					$data = array(
						'no_sj'         => array('LIKE',$filter_order_id),
						'penjualan_mr.status'         => array('<>',4),
						'penjualan_mr.customer_id'	=>$customer_id != null?$customer_id:array('>=',1),
						'no_invoice'	=> array('=','')

					);
					$offset=0;
					$limit=10;

					$results = $this->model_sale_penjualanmr->getPenjualans($column,$join,$data,array(),10,0);
					foreach($results as $r){
						$rests[]=array(
							'id'	=> $r['id'],
							'text'	=> $r['no_sj'].' - Tanggal '.date('d/m/y',strtotime($r['date_added'])).' - '.' Total '.$this->currency->format($r['total']),
							'total'	=>$this->currency->format($r['total']),
							'plaintotal'	=> $r['total']
						);
					}
				}
				if($jenispenjualan == 3){
					//bahanbaku
					$this->load->model('sale/penjualanbahanbaku');
					$column=array('penjualan_bahanbaku.no_sj','penjualan_bahanbaku.id','penjualan_bahanbaku.total','customer.name','penjualan_bahanbaku.date_added');
					$join=array();
					$join[]=array(
						'tablename'	=> 'customer',
						'firsttable'	=> 'penjualan_bahanbaku.customer_id',
						'secondtable'	=> 'customer.customer_id'
					);

					$data = array(
						'no_sj'         => array('LIKE',$filter_order_id),
						'penjualan_bahanbaku.status'         => $status_pengiriman != null?$status_pengiriman:array('>=',1),
						'penjualan_bahanbaku.customer_id'	=>$customer_id != null?$customer_id:array('>=',1),
						'no_invoice'	=> array('=','')

					);
					$offset=0;
					$limit=10;

					$results = $this->model_sale_penjualanbahanbaku->getPenjualans($column,$join,$data,array(),10,0);
					foreach($results as $r){
						$rests[]=array(
							'id'	=> $r['id'],
							'text'	=> $r['no_sj'].' - Tanggal '.date('d/m/y',strtotime($r['date_added'])).' - '.' Total '.$this->currency->format($r['total']),
							'total'	=>$this->currency->format($r['total']),
							'plaintotal'	=> $r['total']
						);
					}
				}
			}

		$this->response->setOutput(json_encode($rests));
	}

	public function tampil(){
		$this->document->setTitle('Daftar Penjualan');
		$this->data['token'] = $this->session->data['token'];
		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/penjualan');
		$this->load->model('sale/customer');

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),6);

		$column=array('penjualan.*','COALESCE(penjualan.cetak,0) as totalcetak','customer.name','customer.alamat','customer.telephone','customer.title','customer.email','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'penjualan.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=> 'penjualan.gudang_id',
			'secondtable'	=> 'gudang.gudang_id',
		);

		/*$join[]=array(
			'tablename'	=> 'sales_order',
			'firsttable'	=> 'penjualan.no_so',
			'secondtable'	=> 'sales_order.id',
		);*/

		$data = array(
			'penjualan.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_penjualan->getPenjualanDetail($column,$join,$data,array());
		if(!empty($trans['user_cetak'])){
		$trans['reqcetak']=$this->model_user_user->getUser($trans['user_cetak']);
		}
		if(!empty($trans['user_setuju'])){
		$trans['usersetujui']=$this->model_user_user->getUser($trans['user_setuju']);

		}
		$trans['setujui']=$custdata;


		//$sales=$this->model_user_user->getUser($trans['sales']);
		//$trans['sales']=$sales['firstname'];
		$sopir=$this->model_user_user->getUser($trans['sopir']);
		//$trans['sales']=$sales['firstname'];
		$trans['sopir']=$sopir['firstname'];
		$trans['kernets']=$this->model_sale_penjualan->getPenjualanKernets(array('tttk_id'	=> $order_id));
		$products=$this->model_sale_penjualan->getPenjualanProducts(array('sales_order_id'	=> $order_id));
		$tabungs=$this->model_sale_penjualan->getPenjualanTabungs(array('penjualan_id'=>$order_id));

		$salesorder="";
		$cekso=array();
		$i=1;

		$this->load->model('user/user');
		$this->data['canceldata']=$this->model_user_user->getAksesData($this->user->getId(),4);
		$this->data['namauser']=$this->model_user_user->getUser($trans['user_cetak']);
		foreach($products as $p){

			if(!isset($cekso[$p['no_salesorder']])){
				if($i != 1){
					$salesorder .=",";
				}
				//$salesorder .=$p['no_salesorder'];
				$salesorder .=$p['id_salesorder'];
				$cekso[$p['no_salesorder']]=1;
			}
			$i++;
		}

		$trans['salesorder']="SO ".$salesorder;

		$this->load->model('sale/deliveryorder');
		if($trans['do_id'] > 0){
			$do=$this->model_sale_deliveryorder->getPenjualan(array('id'=>$trans['do_id']));
			$trans['no_do']=$do['no_do'];
			$trans['hrefdo']=$this->url->link('sale/deliveryorder/tampil', 'token=' . $this->session->data['token'] .'&view=1&order_id='.$trans['do_id'], 'SSL');
		}else{
			$trans['no_do']='-';
		}

		$this->data['order']=$trans;
		$this->data['products']=$products;
		$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);
		
		$this->data['tabungs']=$tabungs;
		$comp=array(
			'compname' => $this->config->get('config_name'),
			'address'	=> $this->config->get('config_address'),
			'email'	=> $this->config->get('config_email'),
			'phone'	=> $this->config->get('config_telephone'),
			'fax'	=> $this->config->get('config_fax'),
			'web'	=> 'http://nissonindonesia.com'
		);

		$this->load->model('catalog/title');
		$trans['titlename']=$this->model_catalog_title->getTitle($trans['title']);
		//tambahan
		$alamat=str_replace("'","",$this->data['address']['address_1']);
$this->data['address']['address_1']=$alamat;
$address=$this->data['address'];
		$this->data['fulldetail']=array(
			'order'	=> $trans,
			'products'	=> $products,
			'address'	=> $address,
			'saddress'	=> $address,
			'comp'	=> $comp
		);
		
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";
			print_r($this->data['fulldetail']);
			exit;
		}

		//print_r($this->data['fulldetail']);

		$this->data['printer']=$this->config->get('config_printer');
		$this->data['printerstatus']=$this->config->get('config_printer_status');

		//print_r($this->model_sale_customer->getAddress($trans['address_id']));
		$this->data['cancel']= $this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['suratjalan']= $this->url->link('sale/penjualan/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=2'. $url, 'SSL');
		$this->data['invoice']= $this->url->link('sale/penjualan/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=3'. $url, 'SSL');
		$this->data['simpanedit']= $this->url->link('sale/penjualan/simpanedit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//if($this->request->get['view'] == 1){
			$this->template = 'sale/penjualan_info.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

		//}
		
		if($this->request->get['edit'] == 1){
			$this->template = 'sale/penjualan_edit.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
		}
		
		/*if($this->request->get['view'] == 2){
			$this->template = 'sale/suratjalan.tpl';
		}*/
		if($this->request->get['view'] == 3){
			$this->template = 'sale/invoice.tpl';
		}



		$this->response->setOutput($this->render());
	}
	
	// baru 6 November 2019
	public function simpanedit(){
		$this->load->model('sale/penjualan');
		//print_r($this->request->post);exit();
		$data = $this->request->post;
		//echo "<pre>";print_r($data);exit();
		//$this->model_sale_penjualan->simpanedit($data);
		$this->model_sale_penjualan->editsupir($data);
		//exit();
		$this->session->data['success'] = 'Sukses: Daftar Penjualan berhasil disimpan dengan ID '.$order;

			$url = '';

			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
			}
			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			if (isset($this->request->get['filter_shipping_method'])) {
				$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
			}
			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['filter_invoice'])) {
				$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
			}

			if (isset($this->request->get['filter_sales_order'])) {
				$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	// end baru

	public function ordersukses() {
		$this->document->setTitle('Penjualan Gudang & Website');
		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('sale/penjualan');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			print_r($this->request->post);
			$this->model_sale_penjualan->ordersukses($this->request->post);

			$this->session->data['success'] = 'Order sukses Penjualan Website berhasil ditambahkan.';

			$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}


		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}


		$this->data['cancel']= $this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('sale/penjualan/ordersukses', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		if($this->request->server['REQUEST_METHOD'] == 'POST'){
			$this->data['orders']=$this->request->post['orders'];
		}else{
			$this->data['orders']=array();
		}


		$this->template = 'sale/sukses_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}
	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Daftar Penjualan');

		$this->load->model('sale/penjualan');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if($this->user->getUsername()=="pawit"){
				echo "<pre>";print_r($this->request->post);exit;
			}
			$order= $this->model_sale_penjualan->addPenjualan($this->request->post);

			$this->session->data['success'] = 'Sukses: Daftar Penjualan berhasil disimpan dengan ID '.$order;

			$url = '';

			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
			}
			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			if (isset($this->request->get['filter_shipping_method'])) {
				$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
			}
			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['filter_invoice'])) {
				$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
			}

			if (isset($this->request->get['filter_sales_order'])) {
				$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('catalog/gudang');

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_invoice'])) {
			$url .= '&filter_invoice=' . $this->request->get['filter_invoice'];
		}

		if (isset($this->request->get['filter_sales_order'])) {
			$url .= '&filter_sales_order=' . $this->request->get['filter_sales_order'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
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

		/*$this->load->model('sale/customer_group');

		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		*/
		$this->data['token'] = $this->session->data['token'];

		$this->data['cancel']= $this->url->link('sale/penjualan', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('sale/penjualan/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->request->post['product'])) {
			$this->data['product'] = $this->request->post['product'];
		} else {
			$this->data['product'] = array();
		}

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('catalog/gudang');

		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs(true);
        
        $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}

		$this->template = 'sale/penjualan_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {
    	/*if (!$this->user->hasPermission('modify', 'gudang/pembelian')) {
      		$this->error['warning'] = 'Permission Denied.';
    	}

    	/*if (empty($this->request->post['date_added'])) {
      		$this->error['warning'] = 'Tanggal input product cacat harus diisi';
    	}*/

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function logcetak(){
		$hasil=array();
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$this->load->model('sale/penjualan');
				$id=$this->request->get['id'];
				$trans=$this->model_sale_penjualan->getPenjualanDetail(array('COALESCE(cetak,0) as totalcetak'),array(),array('id'=>$id),array());
				$totalcetak=$trans['totalcetak'];
				$this->model_sale_penjualan->updatePenjualan(array('cetak'=>$totalcetak+1,'user_cetak'=>$this->user->getId()),array('id'=>$id));
				$hasil['status']=1;
			}
		}
		$this->response->setOutput(json_encode($hasil));
	}

	public function cetakulang(){
		$hasil=array();
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$this->load->model('sale/penjualan');
				$id=$this->request->get['id'];
				$trans=$this->model_sale_penjualan->getPenjualanDetail(array('COALESCE(cetak,0) as totalcetak'),array(),array('id'=>$id),array());
				$totalcetak=$trans['totalcetak'];
				if($totalcetak == 1){
					$this->model_sale_penjualan->updatePenjualan(array('cetakulang'=>2,'alasan_cetak'=> $this->request->get['alasan'],'user_cetak'=>$this->user->getId()),array('id'=>$id));
					$hasil['status']=1;
				}else{
					$hasil['status']=0;
				}
			}
		}
		$this->response->setOutput(json_encode($hasil));
	}
	public function setujui(){
		$hasil=array();
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$this->load->model('sale/penjualan');
				$id=$this->request->get['id'];
				$this->load->model('user/user');
				$custdata=$this->model_user_user->getAksesData($this->user->getId(),6);

				if($custdata){
					$this->model_sale_penjualan->updatePenjualan(array('cetakulang'=>$this->request->get['status'],'user_setuju'=>$this->user->getId()),array('id'=>$id));
					$hasil['status']=1;
				}else{
					$hasil['status']=0;
				}

			}
		}
		$this->response->setOutput(json_encode($hasil));
	}

	public function detail(){
		$hasil = array();

		//$this->load->model('pembelian/permintaanpembelian');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){

			$this->load->model('sale/penjualan');
			$this->load->model('sale/salesorder');
			$this->load->model('sale/invoice');
			$this->load->model('sale/customer');

			if($this->request->get['j'] == 3){
				$column=array('penjualan.*','customer.name','customer.telephone','customer.email');
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'penjualan.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);

				$data = array(
					'penjualan.id'	=> $this->request->get['id'],

				);
				$this->load->model('user/user');
				$this->load->model('catalog/gudang');
				$trans=$this->model_sale_penjualan->getPenjualanDetail($column,$join,$data,array());
				//$so=$this->db->first('sales_order',array('id' => $trans['no_so']));
				$trans['usia']=1;

				//$sales=$this->model_user_user->getUser($trans['sales']);
				$products=$this->model_sale_penjualan->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));
				//$dp=$this->model_sale_invoice->getTotalDp($trans['no_so'],1);
				$dp=0;
			}else{
				$column=array('sales_order.*','customer.name','customer.telephone','customer.email');
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'sales_order.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);

				$data = array(
					'sales_order.id'	=> $this->request->get['id'],

				);
				$this->load->model('user/user');
				$this->load->model('catalog/gudang');
				$trans=$this->model_sale_salesorder->getPenjualanDetail($column,$join,$data,array());

				//$sales=$this->model_user_user->getUser($trans['sales']);
				$products=$this->model_sale_salesorder->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));
				$dp=$this->model_sale_invoice->getTotalDp($this->request->get['id'],1);
			}
			//cek dp

			$trans['dp']=$dp;

			$hasil=array(
				'order'	=> $trans,
				'products'	=> $products,
				//'address'	=> $this->data['address']
			);
		}
	}
		$this->response->setOutput(json_encode($hasil));
	}

	public function detailTanpaInvoice(){
		$hasil = array();

		//$this->load->model('pembelian/permintaanpembelian');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){

			$this->load->model('sale/penjualan');
			//$products=$this->model_sale_penjualan->
			/*$this->load->model('sale/salesorder');
			$this->load->model('sale/invoice');
			$this->load->model('sale/customer');*/

			//if($this->request->get['j'] == 3){
				/*$column=array('penjualan.*','customer.name','customer.telephone','customer.email');
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'penjualan.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);

				$data = array(
					'penjualan.id'	=> $this->request->get['id'],

				);
				$this->load->model('user/user');
				$this->load->model('catalog/gudang');
				$trans=$this->model_sale_penjualan->getPenjualanDetail($column,$join,$data,array());
				$so=$this->db->first('sales_order',array('id' => $trans['no_so']));
				$trans['usia']=$so['usia'];

				//$sales=$this->model_user_user->getUser($trans['sales']);
				$products=$this->model_sale_penjualan->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));
				$dp=$this->model_sale_invoice->getTotalDp($trans['no_so'],1);*/
		/*	}else{
				$column=array('sales_order.*','customer.name','customer.telephone','customer.email');
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'sales_order.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);

				$data = array(
					'sales_order.id'	=> $this->request->get['id'],

				);
				$this->load->model('user/user');
				$this->load->model('catalog/gudang');
				$trans=$this->model_sale_salesorder->getPenjualanDetail($column,$join,$data,array());

				//$sales=$this->model_user_user->getUser($trans['sales']);
				$products=$this->model_sale_salesorder->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));
				$dp=$this->model_sale_invoice->getTotalDp($this->request->get['id'],1);
			}*/
			//cek dp

			//$trans['dp']=$dp;
			if(!empty($this->request->get['gudang_id'])){
				$products=$this->model_sale_penjualan->getSjTanpaInv($this->request->get['id'],$this->request->get['gudang_id']);

				$hasil=array(
					'order'	=> array(),
					'products'	=> $products,
					//'address'	=> $this->data['address']
				);
			}
		}
	}
		$this->response->setOutput(json_encode($hasil));
	}

	public function detailBelumDo(){
		$hasil = array();

		//$this->load->model('pembelian/permintaanpembelian');
		if(isset($this->request->get['customer_id'])){
			if(!empty($this->request->get['customer_id'])){

			$this->load->model('sale/penjualan');
			
			if(!empty($this->request->get['gudang_id'])){
				$products=$this->model_sale_penjualan->getSjBelumDo($this->request->get['customer_id'],$this->request->get['gudang_id']);

				$hasil=array(
					'order'	=> array(),
					'products'	=> $products,
					//'address'	=> $this->data['address']
				);
			}
		}
	}
		$this->response->setOutput(json_encode($hasil));
	}
	public function autocompletebelumdo(){
		$rests = array();

		$this->load->model('sale/penjualan');

			if (isset($this->request->get['q'])) {
				$filter_order_id = $this->request->get['q'];
			} else {
				$filter_order_id = null;
			}
			if (isset($this->request->get['gudang'])) {
				$gudang = $this->request->get['gudang'];
			} else {
				$gudang = '';
			}
			


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$leftjoin=array();
			$leftjoin[]=array(
				'tablename' => 'penjualan_product',
				'secondtable'  => 'penjualan_product.sales_order_id',
				'firsttable' => 'penjualan.id'
			);
			$leftjoin[]=array(
				'tablename' => 'sales_order',
				'firsttable'  => 'penjualan_product.no_so',
				'secondtable' => 'sales_order.id'
			);

			$data = array(
				'penjualan.no_sj'         => array('LIKE',$filter_order_id),
				'do_id'	=> array('<',1),
				'penjualan.gudang_id'	=> $gudang,
				'sales_order.jenispenjualan'	=> 1

			);
			$offset=0;
			$limit=10;
			//$rests=array();

			if(!is_null($filter_order_id)){
			$results = $this->model_sale_penjualan->getPenjualans(array('penjualan.*'),array(),$leftjoin,$data,array(),10,0);
				foreach($results as $r){
					if(!isset($a[$r['id']])){
						$rests[]=array(
							'id'	=> $r['id'],
							'text'	=> $r['no_sj']
						);
						$a[$r['id']]=1;
					}
				}
			}
		$this->response->setOutput(json_encode($rests));
	}
}
?>
