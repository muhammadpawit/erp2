<?php
class ControllerLaporanPembelianDetailImport extends Controller {
	private $error = array();
	// baru 30 September 2019	
	public function exporttoexcel(){
		//echo "<pre>";print_r($this->cetakexcel());exit;
		$this->xlscreation_directtanggal();
	}
	
	function xlscreation_directtanggal() {

		$reportdetails = $this->cetakexcel();

		$objPHPExcel = new PHPExcel(); 
		$objPHPExcel->getProperties()
				->setCreator("IT Division")
				->setLastModifiedBy("IT Division")
				->setTitle("PT.Nisson Indonesia ")
				->setSubject("PT.Nisson Indonesia")
				->setDescription("Export Item to Excel")
				->setKeywords("IT Division")
				->setCategory("IT Division");

		// Set the active Excel worksheet to sheet 0
		$objPHPExcel->setActiveSheetIndex(0); 

		// Initialise the Excel row number
		$rowCount = 0;

		$cell_definition = array(
			'A' => 'Tanggal',
			'B' => 'Tanggal Lunas',
			'C' => 'Nama Sales',
			'D' => 'Nama Customer',
			'E' => 'Jumlah',
			'F' => 'Total Bayar',
			'G' => 'No.Invoice',
			'H' => 'Metode Pembayaran',
			'I' => 'Status'
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
		$fileName = "Laporan_Penjualan_Detail_".$presentDate.".xls";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		die();
	}
	
	public function cetakexcel() {
		$this->load->model('sale/invoice');
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
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
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-01');
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-t');
		}
		if (isset($this->request->get['filter_register_start'])) {
			$filter_register_start = $this->request->get['filter_register_start'];
		} else {
			$filter_register_start = "";
		}
		if (isset($this->request->get['filter_register_end'])) {
			$filter_register_end = $this->request->get['filter_register_end'];
		} else {
			$filter_register_end = "";
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'invoice.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
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
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/invoice/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/invoice/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['penjualans'] = array();
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



		$columnjumlah=array("COALESCE(SUM(totaltagihan),0) as total,COALESCE(SUM(pajak),0) as totalpajak");
		$columntotal=array("COUNT(*) as total");

		//echo $this->request->get['filter_status'];
		$column=array('invoice.*','customer.name','customer.email','customer.telephone','customer.title','customer.date_added as register');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'invoice.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);

		$data = array(
			'invoice.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'invoice.gudang_id'	=>array('IN',$arrsql),
			'invoice.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			'invoice.status'	=> empty($filter_status)?array('<>',4):array('IN',$filter_status),
			'invoice.jenisinvoice'	=> 3,
			//'invoice.date_added'	=> empty($filter_date_start)?array('>','1901-01-01'):array('>=',$filter_date_start),
			//'invoice.date_added'	=> empty($filter_date_added)?array('<',date('Y-m-d',strtotime("+1day"))):array('<=',$filter_date_end)
		);

		if(!empty($filter_date_start) & !empty($filter_date_end)){

			$data['DATE(invoice.date_added)']=array('>=',$filter_date_start,'<=',$filter_date_end);

		}
		else if(empty($filter_date_start) & !empty($filter_date_end)){
			$data['DATE(invoice.date_added)']= array('<=',$filter_date_end);
		}
		else if(!empty($filter_date_start) & empty($filter_date_end)){
			$data['DATE(invoice.date_added)']= array('>=',$filter_date_start);
		}else{
			$data['DATE(invoice.date_added)']= array('>','1901-01-01');
		}

		if(!empty($filter_register_start) & !empty($filter_register_end)){

			$data['DATE(customer.date_added)']=array('>=',$filter_register_start,'<=',$filter_register_end);

		}
		else if(empty($filter_register_start) & !empty($filter_register_end)){
			$data['DATE(customer.date_added)']= array('<=',$filter_register_end);
		}
		else if(!empty($filter_register_start) & empty($filter_register_end)){
			$data['DATE(customer.date_added)']= array('>=',$filter_register_start);
		}else{
			$data['DATE(customer.date_added)']= array('>','1901-01-01');
		}
		if(!isset($this->request->get['print'])){
			$offset=($page - 1) * $this->config->get('config_admin_limit');
			$limit=$this->config->get('config_admin_limit');
		}else{
			$offset=null;
			$limit=0;
		}

		$orders=array();
		if($sort == 'customer.name'){
			$orders=array($sort=>$order);
		}else{
			$orders=array($sort=>$order,'customer.name'=>'ASC','invoice.id'=>'ASC');
		}
		$jumlah=$this->model_sale_invoice->getPenjualanDetail($columnjumlah,$join,$data);
		$this->data['jumlah']=$this->currency->format($jumlah['total']);
		$this->data['jumlahtanpapajak']=$this->currency->format($jumlah['total']-$jumlah['totalpajak']);

		$total=$this->model_sale_invoice->getPenjualanDetail($columntotal,$join,$data);

		$results = $this->model_sale_invoice->getPenjualans($column,$join,$data,$orders,0,null);
		// baru 10 agustus 2019
		$this->load->model('user/user');
		$d = $this->model_sale_invoice->getnoso(6427);
		$sales=$this->model_user_user->getUser($d['sales']);
		// end baru 10 agustus 2019
			if($this->user->getUsername()=="pawiast"){
				echo "<pre>";
				print_r($results);
				exit;
			}
		$product_total=$total['total'];
		$this->data['total']=$product_total;
		$this->load->model('catalog/title');
		foreach ($results as $result) {
			$d = $this->model_sale_invoice->getnoso($result['id']);
			$sales=$this->model_user_user->getUser($d['sales']);
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['id'], 'SSL')
			);

			$namagudang=$this->model_catalog_gudang->getGudang($result['gudang_id']);
			/*
			$cell_definition = array(
				'A' => 'Tanggal',
				'B' => 'Tanggal Lunas',
				'C' => 'Nama Sales',
				'D' => 'Nama Customer',
				'E' => 'Jumlah',
				'F' => 'Total Bayar',
				'G' => 'No.Invoice',
				'H' => 'Metode Pembayaran',
				'I' => 'Status'
			);
			*/
								$metode = $result['metode_pembayaran'];
								if($metode==1){
									$metode="Tunai";
								}else if($metode==2){
									$metode= "COD";
								}else if($metode==3){
									$metode= "Kredit";
								}else{
									$metode= "CBD";
								}
								
								$status = $result['status'];
								if($status== 1){
                                  $status = 'Ditagih';
                                }else if($status == 2){
                                  $status = 'Belum Lunas';
                                }else if($status == 3){
                                  $status = 'Lunas';
                                }else{
                                  $status = 'Dibatalkan';
                                }

			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'No.Invoice'        => $result['no_faktur'],
				//'no_so'	=> $so,
				//'no_sj'	=> $sj,
				//'sales_order_id'        => $result['sales_order_id'],
				'Nama Customer'	=> $this->model_catalog_title->getTitle($result['title']).' '.$result['name'],
				'Metode Pembayaran'	=> $metode,
				'namagudang'	=> $namagudang['nama'],
				'email'	=> $result['email'],
				'telephone'	=> $result['telephone'],
				'jenisinvoice'	=> $result['jenisinvoice'],
				'Jumlah'	=> $this->currency->format($result['totaltagihan']),
				'totaltagihan'	=> $this->currency->format($result['total']),
				'Total Bayar'	=> $this->currency->format($result['totalbayar']),
				'sub_total'	=> $this->currency->format($result['sub_total']),
				'diskon'	=> $this->currency->format($result['diskon']),
				'dasar'	=> $this->currency->format($result['sub_total']-$result['diskon']),
				'dp'	=> $this->currency->format($result['dp']),
				'pajak'	=> $this->currency->format($result['pajak']),
				'Status'	=> $status,
				'Tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
				'register'	=>date('d/m/y',strtotime($result['register'])),
				'Tanggal Lunas'	=>empty($result['tgllunas'])?'Belum Lunas':date('d/m/y',strtotime($result['tgllunas'])),
				'jatuhtempo'	=>date('d/m/y',strtotime($result['jatuhtempo'])),
				'Nama Sales' => $sales['firstname'],
				'products'	=> $this->model_sale_invoice->getPenjualanProducts($result['jenispenjualan'],array('sales_order_id'	=> $result['id']))
			);
		}
		
		return $this->data['penjualans'];

	}
	

	public function cetakbiasa() {
		
		$this->load->language('catalog/category');

		$this->document->setTitle('Laporan Penjualan Detail');

		$this->load->model('sale/invoice');
		$this->load->model('report/pembelian');
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_provinsi'])) {
			$filter_provinsi = $this->request->get['filter_provinsi'];
		} else {
			$filter_provinsi = null;
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
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-01');
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-t');
		}
		if (isset($this->request->get['filter_register_start'])) {
			$filter_register_start = $this->request->get['filter_register_start'];
		} else {
			$filter_register_start = "";
		}
		if (isset($this->request->get['filter_sales'])) {
			$filter_sales = $this->request->get['filter_sales'];
		} else {
			$filter_sales =null;
		}
		if (isset($this->request->get['filter_register_end'])) {
			$filter_register_end = $this->request->get['filter_register_end'];
		} else {
			$filter_register_end = "";
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'invoice.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
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
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/invoice/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/invoice/delete', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['cetak'] = $this->url->link('laporan/penjualandetail', 'token=' . $this->session->data['token'].'&print=1'.$url, 'SSL');

		$this->data['penjualans'] = array();
		$this->load->model('catalog/gudang');
		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs(true);
		$this->load->model('catalog/vendorlokal');
		$this->load->model('catalog/vendorimport');
		$this->load->model('pembelian/pembeliankreditdagang');

		$this->data['permintaans'] = array();
		$results=array();
			$filter=array(
				'filter_customer_id'		  =>$filter_customer_id,
				'filter_date_start'	  => $filter_date_start,
				'filter_date_end'	  => $filter_date_end,
				'filter_status'		  =>$filter_status,
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);
		
		$paging=array(
			'filter_customer_id'		  =>$filter_customer_id,
			'filter_date_start'	  => $filter_date_start,
			'filter_date_end'	  => $filter_date_end,
			'filter_status'		  =>$filter_status,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$product_total=count($this->model_report_pembelian->getpembelianImport($paging));
		$results=$this->model_report_pembelian->getpembelianImport($filter);
		$namavendor=null;
		$this->load->model('pembelian/invoicepembeliandagang');
		$this->load->model('pembelian/pembeliankreditdagang');
		$this->load->model('pembelian/invoicepembelianimport');
		$this->load->model('pembelian/pembelianimport');
		// baru 6 Juli 2020
		$totaljumlah=0;
		$alltotalbayar=0;
		$alltotalbayarrp=0;
		$all=$this->model_report_pembelian->getpembelianImport($paging);
		foreach($all as $a){
			$totaljumlah+=($a['total']);
			$alltotalbayar+=($a['totalbayar']);
			$alltotalbayarrp+=($a['totalbayarrp']);
		}

		$this->data['totaljumlah']=$totaljumlah;
		$this->data['alltotalbayar']=$alltotalbayar;
		$this->data['alltotalbayarrp']=$alltotalbayarrp;

		// end baru
		$products=array();
		foreach ($results as $result) {
			$action=array();
			$namavendor=$this->model_catalog_vendorimport->getVendor(array('id'=>$result['vendor_id']));
			$products=$this->model_report_pembelian->getPermintaanPembelianProduct(array('invoice_id'	=> $result['id']));
			$now = strtotime($result['tglfaktur']); // or your date as well
			$your_date = strtotime($result['jatuhtempo']);
			$datediff = $now-$your_date;
			$tgl1 = new DateTime($result['tglfaktur']);
			$tgl2 = new DateTime($result['jatuhtempo']);
			$d = $tgl2->diff($tgl1)->days + 0;
			$this->data['penjualans'][] = array(
				'id'=>$result['id'],
				'tgl_po'	=>'-',
				'no_po'		=> '',
				'supplier'	=>$namavendor['name'],
				'jumlah'	=>'$'.number_format($result['total'],2),
				'totalbayar' =>'$'.number_format($result['totalbayar'],2),
				'totalbayarrp' =>$this->currency->format($result['totalbayarrp']),
				'tgl_inv'	=>date('d/m/Y',strtotime($result['tglfaktur'])),
				'invoice'	=>$result['no_faktur'],
				'metode_pembayaran'	=> $result['metode_pembayaran'] == 1?'CBD':($result['metode_pembayaran'] == 2?'COD':'Kredit'),
				'lamakredit'=>$d,
				'tgl_jatuhtempo'=>date('d/m/Y',strtotime($result['jatuhtempo'])),
				'status'	=> $result['status'] == 1?'Ditagih':($result['status'] == 2?'Dibayar Sebagian':($result['status'] == 3?'Dibatalkan':($result['status'] == 5?'Permintaan Perubahan Harga':'Lunas'))),
				'tgl_lunas'=>$result['tgllunas']==null?'belum lunas':date('d/m/Y',strtotime($result['tgllunas'])),
				'products'=>$products,
				'actions'	=> $action
			);
		}

		//echo "<pre>";print_r($this->data['penjualans']);exit;

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
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
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}


		$this->data['sort_date_added'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=invoice.date_added' . $url, 'SSL');
		$this->data['sort_register'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=customer.date_added' . $url, 'SSL');
		$this->data['sort_tgl_lunas'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=invoice.tgllunas' . $url, 'SSL');
		$this->data['sort_customer'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=customer.name' . $url, 'SSL');
		$this->data['sort_tagihan'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=invoice.totaltagihan' . $url, 'SSL');
		$this->data['sort_bayar'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=invoice.totalbayar' . $url, 'SSL');
		$this->data['sort_invoice'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=invoice.id' . $url, 'SSL');
		$this->data['sort_metode_pembayaran'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=invoice.metode_pembayaran' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=invoice.status' . $url, 'SSL');
		$this->data['sort_nama'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&nama=asc' . $url, 'SSL');
		$this->data['exportexcel'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&excel=true' . $url, 'SSL');
		$this->data['cetak'] = $this->url->link('laporan/pembeliandetailimport/cetakbiasa', 'token=' . $this->session->data['token'] . '&print=true' . $url, 'SSL');
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
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$fs=explode(',',$filter_status);
		$this->data['filter_status']	= $fs;
		$this->data['filter_date_start']	= $filter_date_start;
		$this->data['filter_date_end']	= $filter_date_end;
		$this->data['filter_register_start']	= $filter_register_start;
		$this->data['filter_register_end']	= $filter_register_end;
		$this->data['token'] = $this->session->data['token'];
		$this->data['filter_provinsi'] = $filter_provinsi;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

			$this->template = 'laporan/pembeliandetail_import_cetak.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);


		$this->response->setOutput($this->render());
	}
	// end baru
	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Laporan Penjualan Detail');

		$this->load->model('sale/invoice');
		$this->load->model('report/pembelian');

		$this->getList();
	}




	private function getList() {
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_provinsi'])) {
			$filter_provinsi = $this->request->get['filter_provinsi'];
		} else {
			$filter_provinsi = null;
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
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		
		if (isset($this->request->get['filter_datesj_start'])) {
			$filter_datesj_start = $this->request->get['filter_datesj_start'];
		} else {
			$filter_datesj_start =null;
		}
		if (isset($this->request->get['filter_datesj_end'])) {
			$filter_datesj_end = $this->request->get['filter_datesj_end'];
		} else {
			$filter_datesj_end =null;
		}
		if(!empty($filter_datesj_start)){
			$filter_date_start=null;
			$filter_date_end=null;
		}else{
			if (isset($this->request->get['filter_date_start'])) {
				$filter_date_start = $this->request->get['filter_date_start'];
			} else {
				$filter_date_start = date('Y-m-01');
			}
			if (isset($this->request->get['filter_date_end'])) {
				$filter_date_end = $this->request->get['filter_date_end'];
			} else {
				$filter_date_end = date('Y-m-t');
			}
		}
		if (isset($this->request->get['filter_register_start'])) {
			$filter_register_start = $this->request->get['filter_register_start'];
		} else {
			$filter_register_start = "";
		}
		if (isset($this->request->get['filter_sales'])) {
			$filter_sales = $this->request->get['filter_sales'];
		} else {
			$filter_sales =null;
		}
		if (isset($this->request->get['filter_register_end'])) {
			$filter_register_end = $this->request->get['filter_register_end'];
		} else {
			$filter_register_end = "";
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'invoice.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
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
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_datesj_start'])) {
			$url .= '&filter_datesj_start=' . $this->request->get['filter_datesj_start'];
		}
		if (isset($this->request->get['filter_datesj_end'])) {
			$url .= '&filter_datesj_end=' . $this->request->get['filter_datesj_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/invoice/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/invoice/delete', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['cetak'] = $this->url->link('laporan/penjualandetail', 'token=' . $this->session->data['token'].'&print=1'.$url, 'SSL');

		$this->data['penjualans'] = array();
		$this->load->model('catalog/gudang');
		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs(true);
		$this->load->model('catalog/vendorlokal');
		$this->load->model('catalog/vendorimport');
		$this->load->model('pembelian/pembeliankreditdagang');

		$this->data['permintaans'] = array();
		$results=array();
		if(!isset($this->request->get['excel'])){
			$filter=array(
				'filter_customer_id'		  =>$filter_customer_id,
				'filter_date_start'	  => $filter_date_start,
				'filter_date_end'	  => $filter_date_end,
				'filter_datesj_start'	  => $filter_datesj_start,
				'filter_datesj_end'	  => $filter_datesj_end,
				'filter_status'		  =>$filter_status,
				'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit'                  => $this->config->get('config_admin_limit')
			);
		}else{
			$filter=array(
				'filter_customer_id'		  =>$filter_customer_id,
				'filter_date_start'	  => $filter_date_start,
				'filter_date_end'	  => $filter_date_end,
				'filter_status'		  =>$filter_status,
				'filter_datesj_start'	  => $filter_datesj_start,
				'filter_datesj_end'	  => $filter_datesj_end,
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);
		}
		
		$paging=array(
			'filter_customer_id'		  =>$filter_customer_id,
			'filter_date_start'	  => $filter_date_start,
			'filter_date_end'	  => $filter_date_end,
			'filter_status'		  =>$filter_status,
			'filter_datesj_start'	  => $filter_datesj_start,
			'filter_datesj_end'	  => $filter_datesj_end,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$product_total=count($this->model_report_pembelian->getpembelianImport($paging));
		$results=$this->model_report_pembelian->getpembelianImport($filter);
		$namavendor=null;
		$this->load->model('pembelian/invoicepembeliandagang');
		$this->load->model('pembelian/pembeliankreditdagang');
		$this->load->model('pembelian/invoicepembelianimport');
		$this->load->model('pembelian/pembelianimport');
		// baru 6 Juli 2020
		$totaljumlah=0;
		$alltotalbayar=0;
		$alltotalbayarrp=0;
		$all=$this->model_report_pembelian->getpembelianImport($paging);
		foreach($all as $a){
			$totaljumlah+=($a['total']);
			$alltotalbayar+=($a['totalbayar']);
			$alltotalbayarrp+=($a['totalbayarrp']);
		}

		$this->data['totaljumlah']=$totaljumlah;
		$this->data['alltotalbayar']=$alltotalbayar;
		$this->data['alltotalbayarrp']=$alltotalbayarrp;

		// end baru
		$products=array();
		foreach ($results as $result) {
			$action=array();
			$namavendor=$this->model_catalog_vendorimport->getVendor(array('id'=>$result['vendor_id']));
			$products=$this->model_report_pembelian->getPermintaanPembelianProduct(array('invoice_id'	=> $result['id']));
			$now = strtotime($result['tglfaktur']); // or your date as well
			$your_date = strtotime($result['jatuhtempo']);
			$datediff = $now-$your_date;
			$tgl1 = new DateTime($result['tglfaktur']);
			$tgl2 = new DateTime($result['jatuhtempo']);
			$d = $tgl2->diff($tgl1)->days + 0;
			$biaya=$this->model_report_pembelian->getBiayaImport($result['id']);
			$this->data['penjualans'][] = array(
				'id'=>$result['id'],
				'tgl_po'	=>'-',
				'no_po'		=> '',
				'supplier'	=>$namavendor['name'],
				'jumlah'	=>'$'.number_format($result['total'],2),
				'biaya'	=> $this->currency->format($biaya),
				'totalbayar' =>'$'.number_format($result['totalbayar'],2),
				'totalbayarrp' =>$this->currency->format(($result['total']*$result['kursdatang'])+$biaya+$result['bmpib']),
				'tgl_inv'	=>date('d/m/Y',strtotime($result['tglfaktur'])),
				'invoice'	=>$result['no_faktur'],
				'kursdatang'	=> $result['kursdatang'],
				'bmpib'	=> $this->currency->format($result['bmpib']),
				'metode_pembayaran'	=> $result['metode_pembayaran'] == 1?'CBD':($result['metode_pembayaran'] == 2?'COD':'Kredit'),
				'lamakredit'=>$d,
				'totalbiaya'	=> $biaya+$result['bmpib'],
				'plaintotal'	=> $result['total']*$result['kursdatang'],
				'tgl_jatuhtempo'=>date('d/m/Y',strtotime($result['jatuhtempo'])),
				'status'	=> $result['status'] == 1?'Ditagih':($result['status'] == 2?'Dibayar Sebagian':($result['status'] == 3?'Dibatalkan':($result['status'] == 5?'Permintaan Perubahan Harga':'Lunas'))),
				'tgl_lunas'=>$result['tgllunas']==null?'belum lunas':date('d/m/Y',strtotime($result['tgllunas'])),
				'products'=>$products,
				'actions'	=> $action
			);
		}
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";print_r($this->data['penjualans']);exit;
		}
		

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
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
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_datesj_start'])) {
			$url .= '&filter_datesj_start=' . $this->request->get['filter_datesj_start'];
		}
		if (isset($this->request->get['filter_datesj_end'])) {
			$url .= '&filter_datesj_end=' . $this->request->get['filter_datesj_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}


		$this->data['sort_date_added'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=invoice.date_added' . $url, 'SSL');
		$this->data['sort_register'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=customer.date_added' . $url, 'SSL');
		$this->data['sort_tgl_lunas'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=invoice.tgllunas' . $url, 'SSL');
		$this->data['sort_customer'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=customer.name' . $url, 'SSL');
		$this->data['sort_tagihan'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=invoice.totaltagihan' . $url, 'SSL');
		$this->data['sort_bayar'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=invoice.totalbayar' . $url, 'SSL');
		$this->data['sort_invoice'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=invoice.id' . $url, 'SSL');
		$this->data['sort_metode_pembayaran'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=invoice.metode_pembayaran' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&sort=invoice.status' . $url, 'SSL');
		$this->data['sort_nama'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&nama=asc' . $url, 'SSL');
		$this->data['exportexcel'] = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . '&excel=true' . $url, 'SSL');
		$this->data['cetak'] = $this->url->link('laporan/pembeliandetailimport/cetakbiasa', 'token=' . $this->session->data['token'] . $url, 'SSL');
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
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_datesj_start'])) {
			$url .= '&filter_datesj_start=' . $this->request->get['filter_datesj_start'];
		}
		if (isset($this->request->get['filter_datesj_end'])) {
			$url .= '&filter_datesj_end=' . $this->request->get['filter_datesj_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/pembeliandetailimport', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$fs=explode(',',$filter_status);
		$this->data['filter_status']	= $fs;
		$this->data['filter_date_start']	= $filter_date_start;
		$this->data['filter_date_end']	= $filter_date_end;
		$this->data['filter_datesj_start']	= $filter_datesj_start;
		$this->data['filter_datesj_end']	= $filter_datesj_end;
		$this->data['filter_register_start']	= $filter_register_start;
		$this->data['filter_register_end']	= $filter_register_end;
		$this->data['token'] = $this->session->data['token'];
		$this->data['exporttoexcel'] = $this->url->link('laporan/pembeliandetailimport/exporttoexcel', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['filter_provinsi'] = $filter_provinsi;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		if(!isset($this->request->get['excel'])){
			$this->template = 'laporan/pembeliandetail_import.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
		}else{
			$this->template = 'laporan/pembeliandetail_import_excel.tpl';
		}



		$this->response->setOutput($this->render());
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('sale/invoice');

			if (isset($this->request->get['q'])) {
				$filter_order_id = $this->request->get['q'];
			} else {
				$filter_order_id = '';
			}

			if (isset($this->request->get['p'])) {
				$p = $this->request->get['p'];
			} else {
				$p = null;
			}

			if (isset($this->request->get['customer_id'])) {
				$customer_id = $this->request->get['customer_id'];
			} else {
				$customer_id = null;
			}
			/*if (isset($this->request->get['p'])) {
				$p = $this->request->get['p'];
			} else {
				$p = '';
			}*/


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_faktur'         => array('LIKE',$filter_order_id),
				'metode_pembayaran'	=> $p != null?($p == 4?array('<>',3):$p):array('>=',1),
				'customer_id'	=> $customer_id != null ?$customer_id:array('>=',1),

			);
			$offset=0;
			$limit=10;

			$results = $this->model_sale_invoice->getPenjualans(array(),array(),$data,array(),10,0);
			foreach($results as $r){
				/*if($r['jenisinvoice'] == 2){
					$total=$this->currency->format($r['totaltagihan']);
				}*/
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_faktur'].' Total Tagihan '.$this->currency->format($r['totaltagihan'] - $r['totalbayar'])
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function detailinvoice(){
		$hasil = array();

		$this->load->model('sale/invoice');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$column=array();
				$id=$this->request->get['id'];
				$data = array(
					'id'      =>$id
				);

				$hasil=$this->model_sale_invoice->getPenjualan($data);
			//	$hasil['pdeposit']=$this->currency->format($hasil['deposit']);


			}
		}
		$this->response->setOutput(json_encode($hasil));


	}

	public function tampil(){
		$this->document->setTitle('Invoice');
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
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
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
				$this->redirect($this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/invoice');
		$this->load->model('sale/customer');
		$column=array('invoice.*','customer.name','customer.npwp','customer.title','customer.telephone','customer.email','customer.alamat','customer.alamat as address');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'invoice.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);


		$data = array(
			'invoice.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_invoice->getPenjualanDetail($column,$join,$data,array());

		$products=$this->model_sale_invoice->getPenjualanProducts($trans['jenispenjualan'],array('sales_order_id'	=> $order_id));

		//referensi
		if($trans['jenisinvoice'] == 3){
			if($trans['jenispenjualan'] == 1){
				$this->load->model('sale/penjualan');
				$ref=$this->model_sale_penjualan->getPenjualan(array('id'=>$trans['referensi']));
				$trans['ref']=$ref['no_sj'];
			}
			if($trans['jenispenjualan'] == 2){
				$this->load->model('sale/penjualanmr');
				$ref=$this->model_sale_penjualanmr->getPenjualan(array('id'=>$trans['referensi']));
				$trans['ref']=$ref['no_sj'];
			}
			if($trans['jenispenjualan'] == 3){
				$this->load->model('sale/penjualanbahanbaku');
				$ref=$this->model_sale_penjualanbahanbaku->getPenjualan(array('id'=>$trans['referensi']));
				$trans['ref']=$ref['no_sj'];
			}
		}else{
			if($trans['jenispenjualan'] == 1){
				$this->load->model('sale/salesorder');
				$ref=$this->model_sale_salesorder->getPenjualan(array('id'=>$trans['referensi']));
				$trans['ref']=$ref['no_so'];
			}
			if($trans['jenispenjualan'] == 2){
				$this->load->model('sale/salesordermr');
				$ref=$this->model_sale_salesordermr->getPenjualan(array('id'=>$trans['referensi']));
				$trans['ref']=$ref['no_so'];
			}
			if($trans['jenispenjualan'] == 3){
				$this->load->model('sale/salesorderbahanbaku');
				$ref=$this->model_sale_salesorderbahanbaku->getPenjualan(array('id'=>$trans['referensi']));
				$trans['ref']=$ref['no_so'];
			}
		}

		$this->load->model('keuangan/bank');
		$this->data['banks']=$this->model_keuangan_bank->getBanks(array(),array(),array('display_order' => 1,'hapus'	=> array('<',1)),array(),0,null);
		//bank pembayaran

		$this->data['order']=$trans;
		$this->data['products']=$products;
		//$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);
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
			'comp'	=> $comp,
			'order'	=> $trans,
			'products'	=> $products,
			//'address'	=> $this->data['address'],
			'banks'	=> $this->data['banks']
		);

		$this->data['printer']=$this->config->get('config_printer');
		$this->data['printerstatus']=$this->config->get('config_printer_status');

		//print_r($this->data['fulldetail']);

		//print_r($this->model_sale_customer->getAddress($trans['address_id']));
		$this->data['cancel']= $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['suratjalan']= $this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=2'. $url, 'SSL');
		$this->data['invoice']= $this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=3'. $url, 'SSL');
		if($this->request->get['view'] == 1){
			$this->template = 'sale/invoice_info.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

		}
		if($this->request->get['view'] == 2){
			$this->template = 'sale/suratjalan.tpl';
		}
		if($this->request->get['view'] == 3){
			$this->template = 'sale/invoice.tpl';
		}



		$this->response->setOutput($this->render());
	}



		public function detail(){
			$hasil = array();

			$this->load->model('pembelian/permintaanpembelian');
			if(isset($this->request->get['id'])){
				if(!empty($this->request->get['id'])){

				$this->load->model('sale/invoice');
				$this->load->model('sale/customer');
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
				$trans=$this->model_sale_invoice->getPenjualanDetail($column,$join,$data,array());

				$sales=$this->model_user_user->getUser($trans['sales']);
				$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
				$trans['namasales']=$sales['firstname'];
				$trans['namagudang']=$gudang['nama'];
				$products=$this->model_sale_invoice->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));

				//$this->data['order']=$trans;
				//$this->data['products']=$products;
				$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);

				$hasil=array(
					'order'	=> $trans,
					'products'	=> $products,
					'address'	=> $this->data['address']
				);
			}
		}
			$this->response->setOutput(json_encode($hasil));


		}
}
?>
