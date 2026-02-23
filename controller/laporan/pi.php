<?php
class ControllerLaporanPi extends Controller {
	private $error = array();
	// baru 30 September 2019	
	public function exporttoexcel(){
		if($this->user->getUsername()=="pawitx"){
			echo "<pre>";print_r($this->cetakexcel());exit;
		}else{
			$this->xlscreation_directtanggal();
		}
	}
	
	function xlscreation_directtanggal() {

		$reportdetails = $this->cetakexcel();
		//print_r($reportdetails);
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
			'B' => 'Gudang',
			'C' => 'No. PI',
			'D' => 'Nama Customer',
			'E' => 'Jatuh Tempo',
			'F' => 'Metode Pembayaran',
			'G' => 'Total',
			
		);

		// Build headers
		foreach( $cell_definition as $column => $value )
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension("{$column}")->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value ); 
		}

		// Build cells
		/*while( $rowCount < count($reportdetails) ){ 
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

			}*/
			$row=2;
			foreach($reportdetails as $r){
				foreach($cell_definition as $key=>$value){
					$objPHPExcel->getActiveSheet()->setCellValue($key.$row, $r[$key] );
				}
				$row++;
			}
				
			//$rowCount++; 
		/*}*/ 

		$rand = rand(1234, 9898);
		$presentDate = date('d-m-Y H:i:s');
		$fileName = "Laporan_Proforma_Invoice_".$presentDate.".xls";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		die();
	}
	
	public function cetakexcel() {
		$this->load->model('sale/proforma');
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
		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = null;
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
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('laporan/pi', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		
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


		$column=array('proforma_invoice.*','customer.name','customer.email','customer.telephone');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'proforma_invoice.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);

		$data = array(
			'proforma_invoice.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'proforma_invoice.gudang_id'	=>array('IN',$arrsql),
			'proforma_invoice.hapus'	=>array('<>',1),
			'proforma_invoice.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			'proforma_invoice.status'	=> empty($filter_status)?array('>',0):$filter_status,
			'proforma_invoice.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):$filter_tanggal
		);

		$order=array();
		$order=array('proforma_invoice.date_added' => 'DESC','proforma_invoice.id'=>'DESC');
		//$this->load->model('sale/proforma');

		$results = $this->model_sale_proforma->getPenjualans($column,$join,$data,$order,0,null);
		$pt = $this->model_sale_proforma->getPenjualans($column,$join,$data,$order,0,null);
		$product_total=count($pt);
		if($this->user->getUsername()=="pawitd"){
			echo "<pre>";print_r($results);exit;
		}
		$user=$this->user->getUsername();
		// $date=date('Y-m-d H:i:s');
		$i=0;
		foreach ($results as $result) {
			//if($result['gudang_id']==1 && date('Y-m-d',strtotime($result['date_added'])) <= date('2020-01-01') ){
				$noso =$this->model_sale_proforma->getnomorso($result['id']);
				$inv =($noso==null)?'':$this->model_sale_proforma->getinv(substr($noso,0,4));
			//}
			
			
			
			$namagudang=$this->model_catalog_gudang->getGudang($result['gudang_id']);
			if($result['gudang_id']==1){
				$metodes=substr($result['metode_pembayaran'],0,1);
				$metode = $metodes== 1?'Tunai':($metodes == 2?'COD':($metodes == 3?'Kredit':'CBD'));
			}else{
				$metode = $result['metode_pembayaran'] == 1?'Tunai':($result['metode_pembayaran'] == 2?'COD':($result['metode_pembayaran'] == 3?'Kredit':'CBD'));
			}
			/*$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'no_faktur'        => $result['no_faktur'],
				'tgllunas'=>date('d/m/y',strtotime($result['tgllunas'])),
				'name'	=> $result['name'],
				'namagudang'	=> $namagudang['nama'],
				'email'	=> $result['email'],
				'telephone'	=> $result['telephone'],
				'total'	=> $this->currency->format($result['totaltagihan']),
				'totalbayar'	=> $this->currency->format($result['totalbayar']),
				'status'	=> $result['status'],
				'tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
				'jatuhtempo'	=>date('d/m/y',strtotime($result['jatuhtempo'])),
				'metode_pembayaran'	=>$metode,
				'inv' => ($inv==null)?'':$inv,
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'      => $action
			);*/
			$this->data['penjualans'][]=array(
				'A'	=>date('d/m/y',strtotime($result['date_added'])),
				'B'	=> $namagudang['nama'],
				'C'        => $result['no_faktur'],
				'D'	=> $result['name'],
				'E'	=>date('d/m/y',strtotime($result['jatuhtempo'])),
				'F'	=>$metode,
				'G'=>$this->currency->format($result['totaltagihan']),
				
				
			);
		}
		//echo "<pre>";print_r($noso);exit;
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";print_r($noso);exit;
		}
		

		
		
		return $this->data['penjualans'];

	}
	
	// end baru
	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Laporan Proforma Invoice');

		$this->load->model('sale/proforma');

		$this->getList();
	}

	private function getList() {
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
		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = null;
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
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('laporan/pi', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/proforma/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/proforma/delete', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['export'] = $this->url->link('laporan/pi/exporttoexcel', 'token=' . $this->session->data['token'] . $url, 'SSL');

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


		$column=array('proforma_invoice.*','customer.name','customer.email','customer.telephone');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'proforma_invoice.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);

		$data = array(
			'proforma_invoice.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'proforma_invoice.gudang_id'	=>array('IN',$arrsql),
			'proforma_invoice.hapus'	=>array('<>',1),
			'proforma_invoice.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			'proforma_invoice.status'	=> empty($filter_status)?array('>',0):$filter_status,
			'proforma_invoice.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):$filter_tanggal
		);

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$data['customer.sales']=$this->user->getId();
		}

		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),5);


		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$order=array();
		$order=array('proforma_invoice.date_added' => 'DESC','proforma_invoice.id'=>'DESC');
		//$this->load->model('sale/proforma');

		$results = $this->model_sale_proforma->getPenjualans($column,$join,$data,$order,$limit,$offset);
		$pt = $this->model_sale_proforma->getPenjualans($column,$join,$data,$order,0,null);
		$product_total=count($pt);
		if($this->user->getUsername()=="pawitd"){
			echo "<pre>";print_r($results);exit;
		}
		$user=$this->user->getUsername();
		// $date=date('Y-m-d H:i:s');
		$i=0;
		foreach ($results as $result) {
			//if($result['gudang_id']==1 && date('Y-m-d',strtotime($result['date_added'])) <= date('2020-01-01') ){
				$noso =$this->model_sale_proforma->getnomorso($result['id']);
				$inv =($noso==null)?'':$this->model_sale_proforma->getinv(substr($noso,0,4));
			//}
			
			$action = array();
				if($user=='admin' OR $user=='Sonny' && $result['gudang_id']==1 && $result['date_added']>='2020-01-03 00:00:00'){
					$action[] = array(
						'text' => 'Tampil',
						'href' => $this->url->link('sale/proforma/tampilnew', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['id'].$url, 'SSL')
					);
				}else if($user=='anita' OR $user=='pawit' && $result['gudang_id']==1 && $result['date_added']>='2020-01-03 00:00:00'){
					$action[] = array(
						'text' => 'Tampil',
						'href' => $this->url->link('sale/proforma/tampilnew', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['id'].$url, 'SSL')
					);
				}else{
					$action[] = array(
						'text' => 'Tampil',
						'href' => $this->url->link('sale/proforma/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['id'].$url, 'SSL')
					);
				}
			
			$namagudang=$this->model_catalog_gudang->getGudang($result['gudang_id']);
			if($result['gudang_id']==1){
				$metodes=substr($result['metode_pembayaran'],0,1);
				$metode = $metodes== 1?'Tunai':($metodes == 2?'COD':($metodes == 3?'Kredit':'CBD'));
			}else{
				$metode = $result['metode_pembayaran'] == 1?'Tunai':($result['metode_pembayaran'] == 2?'COD':($result['metode_pembayaran'] == 3?'Kredit':'CBD'));
			}
			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'no_faktur'        => $result['no_faktur'],
				'tgllunas'=>date('d/m/y',strtotime($result['tgllunas'])),
				//'sales_order_id'        => $result['sales_order_id'],
				'name'	=> $result['name'],
				'namagudang'	=> $namagudang['nama'],
				'email'	=> $result['email'],
				'telephone'	=> $result['telephone'],
				'total'	=> $this->currency->format($result['totaltagihan']),
				'totalbayar'	=> $this->currency->format($result['totalbayar']),
				'status'	=> $result['status'],
				'tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
				'jatuhtempo'	=>date('d/m/y',strtotime($result['jatuhtempo'])),
				'metode_pembayaran'	=>$metode,
				'inv' => ($inv==null)?'':$inv,
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'      => $action
			);
		}
		//echo "<pre>";print_r($noso);exit;
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";print_r($noso);exit;
		}
		if (isset($this->session->data['warning'])) {
			$this->data['warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else if (isset($this->error['warning'])) {
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
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}


		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/pi', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_tanggal']	= $filter_tanggal;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'laporan/pi.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

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
