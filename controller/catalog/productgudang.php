<?php
class ControllerCatalogProductgudang extends Controller {
	private $error = array();
	// baru 4 Maret 2020
	public function uploadexcel(){
		//echo "test";exit;
		$allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
		$d=array();
		$a=1;
		if(in_array($_FILES["file"]["type"],$allowedFileType)){
	  
			  $targetPath = DIR_SYSTEM.'uploads/'.$_FILES['file']['name'];
			  move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
			  
			  $Reader = new SpreadsheetReader($targetPath);
			  
			  $sheetCount = count($Reader->sheets());
			  for($i=0;$i<$sheetCount;$i++)
			  {
				  
				  $Reader->ChangeSheet($i);
				  
				  foreach ($Reader as $Row)
				  {
					  if($a>1){
						if($Row[2]=="TANGERANG"){
							$gudang_id=1;
						  }
						  if($Row[2]=="SURABAYA"){
							$gudang_id=3;
						  }
						  $d=array(
							  'product_id' =>$Row[0],
							  'gudang_id'=>$gudang_id,
							  'harga_terendah'=>$Row[3],
							  'date'=>$Row[4],
							  'name' =>$Row[1],
						  );
						  $this->db->insert('harga_terendah',$d);
					  }
					  $a++;
				   }
			   }
			   //echo "<pre>";print_r($d);exit;
		}
		else
		{ 
			  $type = "error";
			  $message = "Invalid File Type. Upload Excel File.";
		}
		$this->session->data['success'] = 'Harga Terendah produk berhasil diperbarui';

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
		}

			if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

				if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	// end baru
	// baru 24 September 2019
	function xlscreation_directtanggal() {

		$reportdetails = $this->cetakexcelpertanggal();

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
			'A' => 'Gudang',
			'B' => 'Produk',
			'C' => 'Quantity',
			'D' => 'net_cost',
			'E' => 'total_netcost'
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
		$fileName = "Stok_Gudang_".$presentDate.".xlsx";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		die();
	}
	
	public function cetakstokpertanggal(){
		//echo "<pre>";print_r($this->cetakexcelpertanggal());exit;
		$this->xlscreation_directtanggal();
	}
	
	public function cetakexcelpertanggal() {

		$this->document->setTitle('Stok per Gudang');

		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = '';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}
		
		if (isset($this->request->get['date_start'])) {
			$date_start = $this->request->get['date_start'];
		} else {
			$date_start =null;
		}

        if (isset($this->request->get['filter_option'])) {
			$filter_option = $this->request->get['filter_option'];
		} else {
			$filter_option = '';
		}

        if (isset($this->request->get['filter_qty'])) {
			$filter_qty = $this->request->get['filter_qty'];
		} else {
			$filter_qty = '';
		}

		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$filter_category_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		
		if (isset($this->request->get['date_start'])) {
			$url .= '&date_start=' . $this->request->get['date_start'];
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$filter_urutkan = $this->request->get['filter_urutkan'];
		} else {
			$filter_urutkan = '3';
		}

		$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

        if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['date_start'])) {
			$url .= '&date_start=' . $this->request->get['date_start'];
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
		$this->load->model('gudang/product');
		$this->data['orders'] = array();
		$data = array(
				'filter_gudang_id'     => $filter_gudang_id,
				'filter_urutkan'	=> $filter_urutkan,
				'filter_status'	=> $filter_status,
			   'filter_name'	=> $filter_name,
			   'filter_option'	=> $filter_option,
			   'filter_category_id'	=> $filter_category_id,
			   'filter_qty'	=> $filter_qty,
		);
		
		$filter = 	array(
						'tanggal'     => $date_start,
						'gudang_id'	=> $gudang_id,
					);
		$order_total = $this->model_gudang_product->getTotalProducts($data,true);

		$results = $this->model_gudang_product->getProducts($data,true);
		$this->data['cetak'] = $this->url->link('catalog/productgudang/cetakstokpertanggal', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->load->model('catalog/gudang');
		$this->load->model('gudang/product');
		$this->load->model('gudang/kartustok');
		$this->data['products']=array();
		foreach ($results as $result) {
			$saldo = $this->model_gudang_product->getsaldokartustok($filter,$result['product_id'],$result['gudang_id']);
			//if($saldo['saldo']>0){
				$this->data['products'][] = array(
					'Produk'   => $result['name'],
					'Gudang'   => $result['nama'],
					'Quantity'   => $saldo['saldo'],
					'net_cost'   => round($result['net_cost']),
					'total_netcost' =>($saldo['saldo']==null)?0:$this->currency->format($result['net_cost']*$saldo['saldo']),

				);
			//}
		}
		return $this->data['products'];
	}
	
	public function stokpertanggal() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Stok per Gudang');

		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = '';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}
		
		if (isset($this->request->get['date_start'])) {
			$date_start = $this->request->get['date_start'];
		} else {
			$date_start =null;
		}

        if (isset($this->request->get['filter_option'])) {
			$filter_option = $this->request->get['filter_option'];
		} else {
			$filter_option = '';
		}

        if (isset($this->request->get['filter_qty'])) {
			$filter_qty = $this->request->get['filter_qty'];
		} else {
			$filter_qty = '';
		}


		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$filter_category_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}


		if (isset($this->request->get['filter_urutkan'])) {
			$filter_urutkan = $this->request->get['filter_urutkan'];
		} else {
			$filter_urutkan = '3';
		}

		$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

        if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		if (isset($this->request->get['date_start'])) {
			$url .= '&date_start=' . $this->request->get['date_start'];
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('gudang/product');


		$this->data['orders'] = array();

		$data = array(
				'filter_gudang_id'     => $filter_gudang_id,
				'filter_urutkan'	=> $filter_urutkan,
				'filter_status'	=> $filter_status,
			   'filter_name'	=> $filter_name,
			   'filter_option'	=> $filter_option,
			   'filter_category_id'	=> $filter_category_id,
			   'filter_qty'	=> $filter_qty,
		);
		
		$filter = 	array(
						'tanggal'     => $date_start,
						'gudang_id'	=> $gudang_id,
					);
		$order_total = $this->model_gudang_product->getTotalProducts($data,true);

		$results = $this->model_gudang_product->getProducts($data,true);
		if($this->user->getUsername()=="pawitx"){
			echo "<pre>";print_r($results);exit;
		}
		$this->data['cetak'] = $this->url->link('catalog/productgudang/cetakstokpertanggal', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->load->model('catalog/gudang');
		$this->load->model('gudang/product');
		$this->load->model('gudang/kartustok');
		
		$this->data['products']=array();
		foreach ($results as $result) {
			$saldo = $this->model_gudang_product->getsaldokartustok($filter,$result['product_id'],$result['gudang_id']);
			$action = array();

			$action[] = array(
				'text' => 'Harga',
				'href' => $this->url->link('catalog/productgudang/diskon', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].'&gudang_id='.$result['gudang_id'].$url, 'SSL')
			);
			$action[] = array(
				'text' => $this->language->get('Kartu Stok'),
				'href' => $this->url->link('catalog/productgudang/kartustok', 'token=' . $this->session->data['token'] . '&pertanggal=1&product_id=' . $result['product_id'].'&gudang_id='.$result['gudang_id'].$url, 'SSL')
			);
			$action[] = array(
				'text' => 'History Harga',
				'href' => $this->url->link('catalog/productgudang/historyharga', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].'&gudang_id='.$result['gudang_id'].$url, 'SSL')
			);


      		$this->data['products'][] = array(
				'name'   => $result['name'],
				'nama'   => $result['nama'],
				'quantity'   => ($saldo['saldo']==null)?'belum tercatat gudang':$saldo['saldo'],
				'net_cost'   => $this->currency->format($result['net_cost']),
				'total_netcost' =>($saldo['saldo']==null)?0:$this->currency->format($result['net_cost']*$saldo['saldo']),
				'action'	=> $action

			);

		}
		$this->data['heading_title'] = 'Stok per Gudang';

		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];



		$url = '';
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];

		}
		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['date_start'])) {
			$url .= '&date_start=' . $this->request->get['date_start'];
		}



		$this->data['gudangs']=$this->model_catalog_gudang->getGudangs(true);

		$this->load->model('catalog/category');

		$this->data['categories']=$this->model_catalog_category->getCategories();


		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/productgudang/stokpertanggal', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name']=$filter_name;
		$this->data['filter_option']=$filter_option;
		$this->data['filter_category_id']=$filter_category_id;
		$this->data['filter_gudang_id']=$filter_gudang_id;
		$this->data['filter_qty']=$filter_qty;
		$this->data['filter_urutkan']=$filter_urutkan;
		$this->data['filter_status']=$filter_status;
		$this->data['filter_tanggal']=$date_start;
		
		$this->data['cetak'] =$this->url->link('catalog/productgudang/cetakstokpertanggal', 'token=' . $this->session->data['token'] . $url , 'SSL');

		$this->template = 'catalog/productgudangpertanggal.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	// end baru 
	// baru 21 September 2019
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
		);*/
		$cell_definition = array(
			'A' => 'Gudang',
			'B' => 'Produk',
			'C' => 'Quantity'
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
		$fileName = "Stok_Gudang_".$presentDate.".xls";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		die();
	}
	
	public function exporttoexcel(){
		//echo "<pre>";print_r($this->cetakbiasa());
		$this->xlscreation_direct();
	}
	
	public function cetakexcel() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Stok per Gudang');

		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = '';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

        if (isset($this->request->get['filter_option'])) {
			$filter_option = $this->request->get['filter_option'];
		} else {
			$filter_option = '';
		}

        if (isset($this->request->get['filter_qty'])) {
			$filter_qty = $this->request->get['filter_qty'];
		} else {
			$filter_qty = '';
		}

       /* if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
			$filter_product_id = '';
		}*/

		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$filter_category_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		/*if(!empty($filter_gudang_id)){
			if(!in_array($filter_gudang_id,$this->user->getGudang())){
				$this->data['permission']=false;
				$filter_gudang_id='';
			}

		}*/

		if (isset($this->request->get['filter_urutkan'])) {
			$filter_urutkan = $this->request->get['filter_urutkan'];
		} else {
			$filter_urutkan = '3';
		}

		$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

        if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}

		/*if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}*/
		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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

		$this->load->model('gudang/product');


		$this->data['orders'] = array();

		$data = array(
        'filter_gudang_id'     => $filter_gudang_id,
				'filter_urutkan'	=> $filter_urutkan,
      	'filter_status'	=> $filter_status,
		   'filter_name'	=> $filter_name,
		   'filter_option'	=> $filter_option,
		   'filter_category_id'	=> $filter_category_id,
		   'filter_qty'	=> $filter_qty
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_gudang_product->getTotalProducts($data,true);

		$results = $this->model_gudang_product->getProducts($data,true);
		$this->data['cetak'] = $this->url->link('catalog/productgudang/cetak', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->load->model('catalog/gudang');
		$this->load->model('gudang/product');

		//print_r($results);
		$this->data['products']=array();
		foreach ($results as $result) {
		//if(isset($result['product_gudang_id'])){
			$action = array();

			$action[] = array(
				'text' => 'Harga',
				'href' => $this->url->link('catalog/productgudang/diskon', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].'&gudang_id='.$result['gudang_id'].$url, 'SSL')
			);
			/*$action[] = array(
				'text' => 'Premi',
				'href' => $this->url->link('catalog/productgudang/premi', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].'&gudang_id='.$result['gudang_id'].$url, 'SSL')
			);*/
			$action[] = array(
				'text' => $this->language->get('Kartu Stok'),
				'href' => $this->url->link('catalog/productgudang/kartustok', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].'&gudang_id='.$result['gudang_id'].$url, 'SSL')
			);
			/*$action[] = array(
				'text' => 'History HPP',
				'href' => $this->url->link('catalog/productgudang/historyhpp', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].'&gudang_id='.$result['gudang_id'].$url, 'SSL')
			);*/
			$action[] = array(
				'text' => 'History Harga',
				'href' => $this->url->link('catalog/productgudang/historyharga', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].'&gudang_id='.$result['gudang_id'].$url, 'SSL')
			);


      $this->data['products'][] = array(
				'Gudang'   => $result['nama'],
				'Produk'   => $result['name'],
				'Quantity'   => $result['quantity']
				//'action'	=> $action

			);

		}

		$this->data['heading_title'] = 'Stok per Gudang';



		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];



		$url = '';
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];

		}
		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
		/*if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}*/
		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}



		$this->data['gudangs']=$this->model_catalog_gudang->getGudangs(true);

		$this->load->model('catalog/category');

		$this->data['categories']=$this->model_catalog_category->getCategories();


		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name']=$filter_name;
		$this->data['filter_option']=$filter_option;
		$this->data['filter_category_id']=$filter_category_id;
		$this->data['filter_gudang_id']=$filter_gudang_id;
		$this->data['filter_qty']=$filter_qty;
		$this->data['filter_urutkan']=$filter_urutkan;
		$this->data['filter_status']=$filter_status;


		return $this->data['products'];
	}	
	// end baru
	public function index() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Stok per Gudang');

		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = '';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

        if (isset($this->request->get['filter_option'])) {
			$filter_option = $this->request->get['filter_option'];
		} else {
			$filter_option = '';
		}

        if (isset($this->request->get['filter_qty'])) {
			$filter_qty = $this->request->get['filter_qty'];
		} else {
			$filter_qty = '';
		}

       /* if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
			$filter_product_id = '';
		}*/

		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$filter_category_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		/*if(!empty($filter_gudang_id)){
			if(!in_array($filter_gudang_id,$this->user->getGudang())){
				$this->data['permission']=false;
				$filter_gudang_id='';
			}

		}*/

		if (isset($this->request->get['filter_urutkan'])) {
			$filter_urutkan = $this->request->get['filter_urutkan'];
		} else {
			$filter_urutkan = '3';
		}

		$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

        if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}

		/*if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}*/
		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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

		$this->load->model('gudang/product');


		$this->data['orders'] = array();

		$data = array(
        	'filter_gudang_id'     => $filter_gudang_id,
			'filter_urutkan'	=> $filter_urutkan,
      		'filter_status'	=> $filter_status,
		   	'filter_name'	=> $filter_name,
		   	'filter_option'	=> $filter_option,
		   	'filter_category_id'	=> $filter_category_id,
		   	'filter_qty'	=> $filter_qty,
      		'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_gudang_product->getTotalProducts($data,true);

		$results = $this->model_gudang_product->getProducts($data,true);
		$this->load->model('user/user');
		$sethargaterendah=$this->model_user_user->getAksesData($this->user->getId(),14);
		$this->data['cetak'] = $this->url->link('catalog/productgudang/cetak', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['uploadex'] = $this->url->link('catalog/productgudang/uploadexcel', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->load->model('catalog/gudang');
		$this->load->model('gudang/product');
		$this->load->model('catalog/product');
		$freestok=0;
		//print_r($results);
		$this->data['products']=array();
		foreach ($results as $result) {
		//if(isset($result['product_gudang_id'])){
			$freestok = $this->model_catalog_product->sumsogudang($result['product_id'],$result['gudang_id']);
			$action = array();

			$action[] = array(
				'text' => 'Harga',
				'href' => $this->url->link('catalog/productgudang/diskon', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].'&gudang_id='.$result['gudang_id'].$url, 'SSL')
			);
			/*$action[] = array(
				'text' => 'Premi',
				'href' => $this->url->link('catalog/productgudang/premi', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].'&gudang_id='.$result['gudang_id'].$url, 'SSL')
			);*/
			$action[] = array(
				'text' => $this->language->get('Kartu Stok'),
				'href' => $this->url->link('catalog/productgudang/kartustok', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].'&gudang_id='.$result['gudang_id'].$url, 'SSL')
			);
			/*$action[] = array(
				'text' => 'History HPP',
				'href' => $this->url->link('catalog/productgudang/historyhpp', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].'&gudang_id='.$result['gudang_id'].$url, 'SSL')
			);*/
			$action[] = array(
				'text' => 'History Harga',
				'href' => $this->url->link('catalog/productgudang/historyharga', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].'&gudang_id='.$result['gudang_id'].$url, 'SSL')
			);

			if($sethargaterendah==1){
				/*
				$action[] = array(
					'text' => 'Harga Terendah',
					'href' => $this->url->link('catalog/productgudang/hargaterendah', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'].'&gudang_id='.$result['gudang_id'].$url, 'SSL')
				);*/
			}


      $this->data['products'][] = array(
				'name'   => $result['name'],
				'nama'   => $result['nama'],
				'quantity'   => $result['quantity'],
				'freestok'  => $freestok,
				'action'	=> $action

			);

		}

		$this->data['heading_title'] = 'Stok per Gudang';



		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];



		$url = '';
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];

		}
		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
		/*if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}*/
		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}



		$this->data['gudangs']=$this->model_catalog_gudang->getGudangs(true);

		$this->load->model('catalog/category');

		$this->data['categories']=$this->model_catalog_category->getCategories();


		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name']=$filter_name;
		$this->data['filter_option']=$filter_option;
		$this->data['filter_category_id']=$filter_category_id;
		$this->data['filter_gudang_id']=$filter_gudang_id;
		$this->data['filter_qty']=$filter_qty;
		$this->data['filter_urutkan']=$filter_urutkan;
		$this->data['filter_status']=$filter_status;
		$this->data['sethargaterendah']=$sethargaterendah;
		
		$this->data['cetak'] =$this->url->link('catalog/productgudang/exporttoexcel', 'token=' . $this->session->data['token'] . $url , 'SSL');

		$this->template = 'catalog/productgudang.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function diskon() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

	$this->load->model('gudang/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			//print_r($this->request->post['product_special']);
		$this->model_gudang_product->addProductSpecial($this->request->get['gudang_id'],$this->request->get['product_id'], $this->request->post['product_special']);

		$this->session->data['success'] = 'Harga produk berhasil diperbarui';

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
		}

			if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

				if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	$url = '';

	if (isset($this->request->get['filter_name'])) {
		$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
	}

	if (isset($this->request->get['filter_category_id'])) {
		$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
	}

		if (isset($this->request->get['filter_status'])) {
		$url .= '&filter_status=' . $this->request->get['filter_status'];
	}

			if (isset($this->request->get['filter_urutkan'])) {
		$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
	}

	if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
	}

		if(!isset($this->request->get['product_id'])){
			$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}else{
			if(empty($this->request->get['product_id'])){
				$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				if(!isset($this->request->get['gudang_id'])){
					$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}else{
					if(empty($this->request->get['gudang_id'])){
						$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
					}else{

						$this->data['token'] = $this->session->data['token'];
						$this->load->model('sale/customer_group');

						$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

							//$this->data['options'] = $this->model_catalog_options->getOptions();

							if (isset($this->request->post['product_specials'])) {
								$this->data['product_specials'] = $this->request->post['product_specials'];
							} elseif (isset($this->request->get['product_id'])) {
								$this->data['product_specials'] = $this->model_gudang_product->getProductSpecials($this->request->get['gudang_id'],$this->request->get['product_id']);
							} else {
								$this->data['product_specials'] = array();
							}


							$this->data['action'] = $this->url->link('catalog/productgudang/diskon', 'token=' . $this->session->data['token'].'&product_id='.$this->request->get['product_id'].'&gudang_id='.$this->request->get['gudang_id'] . $url, 'SSL');
							$this->data['cancel'] = $this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL');

							$this->template = 'catalog/product_special.tpl';
							$this->children = array(
								'common/header',
								'common/footer'
							);


							$this->response->setOutput($this->render());
					}
				}
			}
		}
	}



	public function cetak() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Stok per Gudang');

		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = '';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

    if (isset($this->request->get['filter_option'])) {
			$filter_option = $this->request->get['filter_option'];
		} else {
			$filter_option = '';
		}
		if (isset($this->request->get['filter_urutkan'])) {
			$filter_urutkan = $this->request->get['filter_urutkan'];
		} else {
			$filter_urutkan = '';
		}

  if (isset($this->request->get['filter_qty'])) {
			$filter_qty = $this->request->get['filter_qty'];
		} else {
			$filter_qty = '';
		}

		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$filter_category_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}




		$this->load->model('catalog/product');

		$this->data['products'] = array();

		$data = array(
      'filter_gudang_id'     => $filter_gudang_id,
      'filter_status'	=> $filter_status,
			'filter_urutkan'	=> $filter_urutkan,
		   'filter_qty'	=> $filter_qty,
		   'filter_name'	=> $filter_name,
		   'filter_category_id'	=> $filter_category_id,

		);
		$this->load->model('gudang/product');
		$results = $this->model_gudang_product->getProducts($data,false);

		$this->load->model('catalog/gudang');


		//print_r($results);
		foreach ($results as $result) {


			$this->data['products'][] = array(
				'product_name'   => $result['name'],
				'nama'   => $result['nama'],
				'options'     => $this->model_gudang_product->getOptionGudang($result['product_id'],$result['gudang_id']),
				'qty'   => $result['quantity'],
				'status'	=> $status

			);
			//print_r($this->model_catalog_product->getProductOptionsGudang($result['product_id'],$result['gudang_id']));
		//}
		}

		$this->data['heading_title'] = 'Stok per Gudang';
		$this->data['nama']	='Nama Gudang';

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');



		$this->template = 'catalog/cetakproduct.tpl';


		$this->response->setOutput($this->render());
	}

	public function stokAwal(){
		$this->load->model('gudang/product');
		$this->load->model('catalog/product');
		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),8);


		$this->document->setTitle('Input Stok Awal produk Gudang');

		if (isset($this->request->get['product_id'])) {
				$product_id=$this->request->get['product_id'];
			}
		else{
			$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));

		}

		if(!$custdata){
			$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));

		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormStok()) {

			$this->model_gudang_product->addStokAwal($this->request->post);
		  	$this->session->data['success'] = 'Success: Stok berhasil ditambahkan.';

				$url = '';

				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['filter_category_id'])) {
					$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
				}

					if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
				}

		        if (isset($this->request->get['filter_urutkan'])) {
					$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
			}

				if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

					if (isset($this->request->get['filter_urutkan'])) {
				$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->data['action'] = $this->url->link('catalog/productgudang/stokAwal', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');

			$this->data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->error)) {
				$this->data['error'] = $this->error;
			} else {
				$this->data['error'] = '';
			}

    	//if(empty($this->request->post)){
			$this->data['productdesc']=$this->model_catalog_product->getProduct($product_id);
			$this->data['product_id']=$product_id;
			//$this->data['options']=$this->model_catalog_product->getProductOptions($product_id);

    	$this->load->model('catalog/gudang');
			$this->load->model('gudang/product');

		$gudangs = $this->model_catalog_gudang->getGudangs();
		$this->data['gudangs']=array();
		foreach($gudangs as $g){
			$cek=$this->model_gudang_product->getProductGudangT($product_id,$g['gudang_id']);
			if(empty($cek)){
				$this->data['gudangs'][]=$g;
			}
		}

		$this->template = 'catalog/stokawal_product.tpl';
		$this->children = array(
					'common/header',
					'common/footer'
				);
			$this->response->setOutput($this->render());
	}

	public function edit(){
		$this->load->model('gudang/product');
		$this->load->model('catalog/product');


		$this->document->setTitle('Stok Gudang');

		if (isset($this->request->get['product_gudang_id'])) {
				$product_gudang_id=$this->request->get['product_gudang_id'];
			}
		else{
			$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));

		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			$this->model_gudang_product->editProduk($this->request->get['product_gudang_id'],$this->request->post);
	  	$this->session->data['success'] = 'Success: Data produk gudang berhasil diperbarui.';

				$url = '';

				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['filter_category_id'])) {
					$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
				}

					if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
				}

		        if (isset($this->request->get['filter_urutkan'])) {
					$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_category_id'])) {
				$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
			}

				if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

					if (isset($this->request->get['filter_urutkan'])) {
				$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->data['action'] = $this->url->link('catalog/productgudang/edit', 'token=' . $this->session->data['token'] . '&product_gudang_id=' . $this->request->get['product_gudang_id'] . $url, 'SSL');

			$this->data['cancel'] = $this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->error)) {
				$this->data['error'] = $this->error;
			} else {
				$this->data['error'] = '';
			}

    	//if(empty($this->request->post)){
			$this->data['product']=$this->model_gudang_product->getProductGudang($product_gudang_id);


			$this->template = 'catalog/productgudang_form.tpl';
			$this->children = array(
					'common/header',
					'common/footer'
				);
			$this->response->setOutput($this->render());
	}

	public function validateFormStok(){
		if (!$this->user->hasPermission('modify', 'catalog/productgudang')) {
      		$this->error['warning'] = 'Anda tidak memiliki ijin untuk memodifikasi data stok produk.';
    	}
    	/*if(empty($this->request->post['qty'])){
    		$this->error['qty'] = 'Data quantity harus diisi';

    	}

			if(empty($this->request->post['net_cost'])){
    		$this->error['net_cost'] = 'HPP harus diisi';

    	}
    	/*if($this->request->post['qty'] < 1){
    		$this->error['qty'] = 'Data quantity harus lebih dari 0';

    	}*/
			if(!is_numeric($this->request->post['qty'])){
    		$this->error['qty'] = 'Data quantity harus berupa angka';

    	}
			if(!is_numeric($this->request->post['net_cost'])){
    		$this->error['net_cost'] = 'Net Cost harus berupa angka';

    	}

    	if(empty($this->request->post['gudang_id']) ){
    		$this->error['gudang'] = 'Gudang tidak boleh kosong';

    	}


    	if (!$this->error) {
			return true;
    	} else {
      		return false;
    	}
	}

	public function kartustok() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Kartu Stok Produk');

			$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		
		if (isset($this->request->get['tanggal2'])) {
			$url .= '&tanggal2=' . $this->request->get['tanggal2'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

        if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
		}
		if (isset($this->request->get['gudang_id'])) {
			$url .= '&gudang_id=' . $this->request->get['gudang_id'];
		}

		$this->data['url'] = $this->url->link('catalog/productgudang/kartustok', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['pagekartu'])) {
			$url .= '&pagekartu=' . $this->request->get['pagekartu'];
		}


		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}
		
		if (isset($this->request->get['tanggal2'])) {
			$url .= '&tanggal2=' . $this->request->get['tanggal2'];
		}

		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['gudang_id'])) {
			$gudang_id = $this->request->get['gudang_id'];
		} else {
			$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['pagekartu'])) {
			$pagekartu = $this->request->get['pagekartu'];
		} else {
			$pagekartu = 1;
		}

		if (isset($this->request->get['tanggal'])) {
			$tanggal = $this->request->get['tanggal'];
		} else {
			$tanggal = '';
		}
		
		if (isset($this->request->get['pertanggal'])) {
			$pertanggal = $this->request->get['pertanggal'];
		} else {
			$pertanggal = 2;
		}
		
		if (isset($this->request->get['tanggal2'])) {
			$tanggal2 = $this->request->get['tanggal2'];
		} else {
			$tanggal2 = '';
		}

		if (isset($this->request->get['type'])) {
			$type = $this->request->get['type'];
		} else {
			$type = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('gudang/product');
		$this->load->model('gudang/kartustok');


		$this->data['orders'] = array();

		$data = array(
			'tanggal'     => $tanggal,
			'tanggal2'     => $tanggal2,
			'product_id'	=> $product_id,
			'type'	=> $type,
			'gudang_id'	=> $gudang_id,
			'start'                  => ($pagekartu - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$all = array(
			'tanggal'     => $tanggal,
			'tanggal2'     => $tanggal2,
			'product_id'	=> $product_id,
			'type'	=> $type,
			'gudang_id'	=> $gudang_id,
			//'start'                  => ($pagekartu - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		//$order_total = $this->model_gudang_kartustok->getTotalKartustoks($data);
		$order_total = count($this->model_gudang_kartustok->getKartustoks($all));
		$results = $this->model_gudang_kartustok->getKartustoks($data);
		if($pertanggal==1){
			$this->data['cancel'] = $this->url->link('catalog/productgudang/stokpertanggal', 'token=' . $this->session->data['token'] . $url, 'SSL');
		}else{
			$this->data['cancel'] = $this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL');
		}
		

		$this->load->model('catalog/gudang');
		$this->load->model('gudang/product');
		$this->load->model('catalog/product');
		$n = $this->model_catalog_product->getProduct($product_id);
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";
			$n=1;
			$n = $this->model_catalog_product->getProduct($product_id);
			//$this->data['namaproduk'] = "test";
			print_r($n['name']);
			exit;
		}
		$this->data['namaproduk'] = $n['name'];
		//print_r($results);
		$this->data['kartustoks']=array();
		$customer='';
		foreach ($results as $result) {
			if($result['type']==2){
				$sj = $this->model_gudang_kartustok->getsj($result['invoice']);
				$customer = $this->model_gudang_kartustok->getcust($sj);
			}
			$urlref=$this->url->link($result['urlref'].'/tampil', 'token=' . $this->session->data['token'] .'&id='.$result['idref'], 'SSL');
			if($result['type'] == 3 | $result['type'] == 4 | $result['type'] == 2){
				$urlref=$this->url->link($result['urlref'].'/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$result['idref'], 'SSL');
			}
			$this->data['kartustoks'][] = array(
				'kartustok_id'	=> $result['kartustok_id'],
				'tanggal'=> date('d F Y',strtotime($result['tgl'])),
				'waktu'	=> date('H:i:s',strtotime($result['tgl'])),
				'name'   => $result['product_name'],
				'stokmasuk'   => $result['stokmasuk'],
				'stokkeluar'	=> $result['stokkeluar'],
				'ket'	=> $result['ket'],
				'saldo'	=> $result['saldo'],
				'customer'	=> "<b>".$customer."</b>",
				'typ' =>$result['type'],
				'invoice'	=> $result['invoice'],
				'quantityawal'	=> $result['quantityawal'],
				'type'	=> $result['type_name'],
				'no_dokumen'=> $result['no_dokumen'],
				'urlref'	=> $urlref
			);

		}

		$this->data['heading_title'] = 'Kartu Stok Produk';
		$this->data['token'] = $this->session->data['token'];



		$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		
		if (isset($this->request->get['pertanggal'])) {
			$url .= '&pertanggal=' . $this->request->get['pertanggal'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

        if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		if (isset($this->request->get['tanggal2'])) {
			$url .= '&tanggal2=' . $this->request->get['tanggal2'];
		}

		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}

		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
		}
		if (isset($this->request->get['gudang_id'])) {
			$url .= '&gudang_id=' . $this->request->get['gudang_id'];
		}

		$next=$pagekartu+1;

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $pagekartu;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/productgudang/kartustok', 'token=' . $this->session->data['token'] . $url . '&pagekartu={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->load->model('user/user');
		$canceldata=0;
		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),17);
		$this->data['canceldata']=$canceldata;
		if($canceldata == 1){
			$this->data['cetakexcel'] = $this->url->link('catalog/productgudang/kartustokexcel', 'token=' . $this->session->data['token'] .'&nama='. $n['name'].$url , 'SSL');
		}
		

		$this->data['tanggal']=$tanggal;
		$this->data['tanggal2']=$tanggal2;
		$this->data['type']=$type;
		$this->template = 'catalog/kartustok.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	// excel kartusok produk
	public function kartustokexcel(){
		if(isset($this->request->get['nama'])){
			$nama=$this->request->get['nama'];
		}
		//echo "<pre>";print_r($this->directkartustok());
		$this->xlscreation_kartustok($nama);
	}

	public function directkartustok(){
		$this->document->setTitle('Kartu Stok Produk');

			$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		
		if (isset($this->request->get['tanggal2'])) {
			$url .= '&tanggal2=' . $this->request->get['tanggal2'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

        if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
		}
		if (isset($this->request->get['gudang_id'])) {
			$url .= '&gudang_id=' . $this->request->get['gudang_id'];
		}

		$this->data['url'] = $this->url->link('catalog/productgudang/kartustok', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['pagekartu'])) {
			$url .= '&pagekartu=' . $this->request->get['pagekartu'];
		}


		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}
		
		if (isset($this->request->get['tanggal2'])) {
			$url .= '&tanggal2=' . $this->request->get['tanggal2'];
		}

		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['gudang_id'])) {
			$gudang_id = $this->request->get['gudang_id'];
		} else {
			$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['pagekartu'])) {
			$pagekartu = $this->request->get['pagekartu'];
		} else {
			$pagekartu = 1;
		}

		if (isset($this->request->get['tanggal'])) {
			$tanggal = $this->request->get['tanggal'];
		} else {
			$tanggal = '';
		}
		
		if (isset($this->request->get['pertanggal'])) {
			$pertanggal = $this->request->get['pertanggal'];
		} else {
			$pertanggal = 2;
		}
		
		if (isset($this->request->get['tanggal2'])) {
			$tanggal2 = $this->request->get['tanggal2'];
		} else {
			$tanggal2 = '';
		}

		if (isset($this->request->get['type'])) {
			$type = $this->request->get['type'];
		} else {
			$type = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('gudang/product');
		$this->load->model('gudang/kartustok');


		$this->data['orders'] = array();

		$data = array(
			'tanggal'     => $tanggal,
			'tanggal2'     => $tanggal2,
			'product_id'	=> $product_id,
			'type'	=> $type,
			'gudang_id'	=> $gudang_id,
			//'start'                  => ($pagekartu - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$all = array(
			'tanggal'     => $tanggal,
			'tanggal2'     => $tanggal2,
			'product_id'	=> $product_id,
			'type'	=> $type,
			'gudang_id'	=> $gudang_id,
			//'start'                  => ($pagekartu - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		//$order_total = $this->model_gudang_kartustok->getTotalKartustoks($data);
		$order_total = count($this->model_gudang_kartustok->getKartustoks($all));
		$results = $this->model_gudang_kartustok->getKartustoks($data);
		if($pertanggal==1){
			$this->data['cancel'] = $this->url->link('catalog/productgudang/stokpertanggal', 'token=' . $this->session->data['token'] . $url, 'SSL');
		}else{
			$this->data['cancel'] = $this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL');
		}
		

		$this->load->model('catalog/gudang');
		$this->load->model('gudang/product');
		$this->load->model('catalog/product');
		$n = $this->model_catalog_product->getProduct($product_id);
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";
			$n=1;
			$n = $this->model_catalog_product->getProduct($product_id);
			//$this->data['namaproduk'] = "test";
			print_r($n['name']);
			exit;
		}
		$this->data['namaproduk'] = $n['name'];
		//print_r($results);
		$this->data['kartustoks']=array();
		$customer='';
		$referensi=null;
		foreach ($results as $result) {
			if($result['type']==2){
				$sj = $this->model_gudang_kartustok->getsj($result['invoice']);
				$customer = $this->model_gudang_kartustok->getcust($sj);
			}
			$urlref=$this->url->link($result['urlref'].'/tampil', 'token=' . $this->session->data['token'] .'&id='.$result['idref'], 'SSL');
			if($result['type'] == 3 | $result['type'] == 4 | $result['type'] == 2){
				$urlref=$this->url->link($result['urlref'].'/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$result['idref'], 'SSL');
			}
			if($result['type']==2){
				$referensi=$result['invoice'].' '." ".$customer."";
			}else{
				$referensi=$result['invoice'];
			}
			
			$this->data['kartustoks'][] = array(
				'kartustok_id'	=> $result['kartustok_id'],
				'Tanggal'=> date('d F Y',strtotime($result['tgl'])),
				'Waktu'	=> date('H:i:s',strtotime($result['tgl'])),
				'Stokmasuk'   => $result['stokmasuk'],
				'Stokkeluar'	=> $result['stokkeluar'],
				'Keterangan'	=> $result['ket'],
				'Saldo'	=> $result['saldo'],
				'Referensi'=>$referensi,
				'customer'	=> "<b>".$customer."</b>",
				'typ' =>$result['type'],
				'invoice'	=> $result['invoice'],
				'Quantityawal'	=> $result['quantityawal'],
				'Type'	=> $result['type_name'],
				'no_dokumen'=> $result['no_dokumen'],
				'urlref'	=> $urlref
			);

		}

		return $this->data['kartustoks'];
	}

	function xlscreation_kartustok($namaproduk) {

		$reportdetails = $this->directkartustok();

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
			'B' => 'Waktu',
			'C' => 'Referensi',
			'D' => 'Keterangan',
			'E' => 'Quantityawal',
			'F' => 'Stokmasuk',
			'G' => 'Stokkeluar',
			'H' => 'Saldo',
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
		$fileName = "Kartu_stok_Produk_".$namaproduk.$presentDate.".xlsx";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		die();
	}

	// end 

	public function kartustokoption() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Kartu Stok Produk per Ukuran');

			$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

        if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
		}
		if (isset($this->request->get['gudang_id'])) {
			$url .= '&gudang_id=' . $this->request->get['gudang_id'];
		}

		$this->data['url'] = $this->url->link('catalog/productgudang/kartustokoption', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['pagekartu'])) {
			$url .= '&pagekartu=' . $this->request->get['pagekartu'];
		}


		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}
		if (isset($this->request->get['kartuukuran'])) {
			$url .= '&kartuukuran=' . $this->request->get['kartuukuran'];
		}

		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['gudang_id'])) {
			$gudang_id = $this->request->get['gudang_id'];
		} else {
			$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['pagekartu'])) {
			$pagekartu = $this->request->get['pagekartu'];
		} else {
			$pagekartu = 1;
		}

		if (isset($this->request->get['tanggal'])) {
			$tanggal = $this->request->get['tanggal'];
		} else {
			$tanggal = '';
		}

		if (isset($this->request->get['kartuukuran'])) {
			$kartuukuran = $this->request->get['kartuukuran'];
		} else {
			$kartuukuran = '';
		}

		if (isset($this->request->get['type'])) {
			$type = $this->request->get['type'];
		} else {
			$type = '';
		}

   	if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('gudang/product');
		$this->load->model('gudang/kartustok');


		$this->data['orders'] = array();

		$data = array(
        'tanggal'     => $tanggal,
				'product_id'	=> $product_id,
				'kartuukuran'	=> $kartuukuran,
				'type'	=> $type,
				'gudang_id'	=> $gudang_id,
      'start'                  => ($pagekartu - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_gudang_kartustok->getTotalKartustokOptions($data);

		$results = $this->model_gudang_kartustok->getKartustokOptions($data);
		$this->data['cancel'] = $this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->load->model('catalog/gudang');
		$this->load->model('gudang/product');

		//print_r($results);
		$this->data['kartustoks']=array();
		foreach ($results as $result) {


      $this->data['kartustoks'][] = array(
				'tanggal'=> date('d F Y',strtotime($result['tgl'])),
				'waktu'	=> date('H:i:s',strtotime($result['tgl'])),
				'option'   => $result['product_option_name'],
				'stokmasuk'   => $result['stokmasuk'],
				'stokkeluar'	=> $result['stokkeluar'],
				'ket'	=> $result['ket'],
				'saldo'	=> $result['saldo'],
				'invoice'	=> $result['invoice'],
				'quantityawal'	=> $result['quantityawal'],
				'type'	=> $result['type_name']
			);

		}

		$this->data['heading_title'] = 'Kartu Stok Produk per Ukuran/Warna';
		$this->data['token'] = $this->session->data['token'];



		$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

        if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		if (isset($this->request->get['kartuukuran'])) {
			$url .= '&kartuukuran=' . $this->request->get['kartuukuran'];
		}

		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}

		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
		}
		if (isset($this->request->get['gudang_id'])) {
			$url .= '&gudang_id=' . $this->request->get['gudang_id'];
		}

		$next=$pagekartu+1;

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $pagekartu;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/productgudang/kartustokoption', 'token=' . $this->session->data['token'] . $url . '&pagekartu='.$next, 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['productoption']=$this->model_gudang_product->getOptionGudang($product_id,$gudang_id);

		$this->data['tanggal']=$tanggal;
		$this->data['kartuukuran']=$kartuukuran;
		$this->data['type']=$type;
		$this->template = 'catalog/kartustokoption.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function autocompletegudang() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_gudang_id']) ) {
			$this->load->model('gudang/product');
			$this->load->model('catalog/product');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_gudang_id'])) {
				$filter_gudang_id = $this->request->get['filter_gudang_id'];
			} else {
				$filter_gudang_id = '';
			}



			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'filter_name'         => $filter_name,
				'filter_gudang_id'        => $filter_gudang_id,
				'start'               => 0,
				'limit'               => $limit
			);

			$results = $this->model_gudang_product->getProducts($data);

			foreach ($results as $result) {

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'gudang_id'	=> $filter_gudang_id,
					'nama_gudang'	=> $result['nama'],
					'qty'		=> $result['quantity'],
					'net_cost'	=> $result['net_cost']
				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function hapusspecial(){
		//$product_option_id=$this->request->get['product_option_id'];
		$product_special_id=$this->request->get['product_special_id'];

		$this->load->model('gudang/product');
		$this->model_gudang_product->deleteProductSpecial($product_special_id);

		echo 1;
	}
	public function detail(){
		$hasil = array();
		$pricelist=0;
		$batasbawah=0;
		$lastprice=0;
		$hargaterendah=0;
		$detail=array();

		$this->load->model('gudang/product');
		if(isset($this->request->get['product_id'])){
			if(!empty($this->request->get['product_id'])){
				$product_id=$this->request->get['product_id'];
				$gudang_id=$this->request->get['gudang_id'];
				$customer_group_id=$this->request->get['customer_group_id'];
				$customer_id=$this->request->get['customer_id'];
				$detail=$this->model_gudang_product->getProduct($product_id,$gudang_id);
				$column=array();

				$hargaterendah=$this->model_gudang_product->gethargaterendahdetail($product_id,$gudang_id);
				if(isset($this->request->get['customer_group_id'])){
					if(!empty($this->request->get['customer_group_id'])){
						/*$pdiskon=$this->model_catalog_product->getProductSpecialsActiveDefault($product_id,$this->request->get['customer_group_id']);
						if($pdiskon){
							$diskon=$hasil['price']-$pdiskon;
						}*/
						$p=$this->model_gudang_product->getProductPrice($product_id,$gudang_id,$customer_group_id);
						$lp=$this->model_gudang_product->getLastProductPrice($product_id,$gudang_id,$customer_id);
						if(!empty($p)){
							$pricelist=$p['price'];
							$batasbawah=$p['batasbawah'];
						}

						if(!empty($lp)){
							$lastprice=$lp['price'];
						}else{
							$lastprice=$pricelist;
						}


					}
				}
				//$hasil['diskon']=$diskon;
			}
		}
		$hasil=array(
			'detail'	=> $detail,
			'pricelist'	=> $pricelist,
			'lastprice'	=> $lastprice,
			'batasbawah'	=> $batasbawah,
			'harga_terendah' => $hargaterendah==null?0:$hargaterendah,
		);
		$this->response->setOutput(json_encode($hasil));


	}
	// baru 29 Agustus 2019
	
	public function details(){
		$hasil = array();
		$pricelist=0;
		$batasbawah=0;
		$lastprice=0;
		$detail=array();

		$this->load->model('gudang/product');
		if(isset($this->request->get['product_id'])){
			if(!empty($this->request->get['product_id'])){
				$product_id=$this->request->get['product_id'];
				$gudang_id=$this->request->get['gudang_id'];
				$customer_group_id=$this->request->get['customer_group_id'];
				$customer_id=$this->request->get['customer_id'];
				$detail=$this->model_gudang_product->getProduct($product_id,$gudang_id);
				$column=array();


			}
		}
		$hasil=array(
			'detail'	=> $detail,
		);
		$this->response->setOutput(json_encode($hasil));


	}
	
	// end baru
	public function premi() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

	$this->load->model('kepegawaian/kodepremi');
	$this->load->model('gudang/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			//print_r($this->request->post['product_special']);
		$this->model_gudang_product->addProductPremi($this->request->get['gudang_id'],$this->request->get['product_id'], $this->request->post);

		$this->session->data['success'] = 'Harga produk berhasil diperbarui';

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
		}

			if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

				if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	$url = '';

	if (isset($this->request->get['filter_name'])) {
		$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
	}

	if (isset($this->request->get['filter_category_id'])) {
		$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
	}

		if (isset($this->request->get['filter_status'])) {
		$url .= '&filter_status=' . $this->request->get['filter_status'];
	}

			if (isset($this->request->get['filter_urutkan'])) {
		$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
	}

	if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
	}

		if(!isset($this->request->get['product_id'])){
			$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}else{
			if(empty($this->request->get['product_id'])){
				$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				if(!isset($this->request->get['gudang_id'])){
					$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}else{
					if(empty($this->request->get['gudang_id'])){
						$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
					}else{

						$this->data['token'] = $this->session->data['token'];

						$this->data['premis'] = $this->model_kepegawaian_kodepremi->getOptions();

							//$this->data['options'] = $this->model_catalog_options->getOptions();

							$this->data['product'] = $this->model_gudang_product->getProduct($this->request->get['product_id'],$this->request->get['gudang_id']);

							//print_r($this->data['product']);
							$this->data['action'] = $this->url->link('catalog/productgudang/premi', 'token=' . $this->session->data['token'].'&product_id='.$this->request->get['product_id'].'&gudang_id='.$this->request->get['gudang_id'] . $url, 'SSL');
							$this->data['cancel'] = $this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL');

							$this->template = 'catalog/product_premi.tpl';
							$this->children = array(
								'common/header',
								'common/footer'
							);


							$this->response->setOutput($this->render());
					}
				}
			}
		}
	}

	public function historyhpp() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('History HPP');

			$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

        if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
		}
		if (isset($this->request->get['gudang_id'])) {
			$url .= '&gudang_id=' . $this->request->get['gudang_id'];
		}

		$this->data['url'] = $this->url->link('catalog/productgudang/historyhpp', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['pagekartu'])) {
			$url .= '&pagekartu=' . $this->request->get['pagekartu'];
		}


		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['gudang_id'])) {
			$gudang_id = $this->request->get['gudang_id'];
		} else {
			$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['pagekartu'])) {
			$pagekartu = $this->request->get['pagekartu'];
		} else {
			$pagekartu = 1;
		}

		if (isset($this->request->get['tanggal'])) {
			$tanggal = $this->request->get['tanggal'];
		} else {
			$tanggal = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('gudang/product');

		$this->data['orders'] = array();

		$data = array(
        'tanggal'     => $tanggal,
				'product_id'	=> $product_id,
				'gudang_id'	=> $gudang_id,
      'start'                  => ($pagekartu - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_gudang_product->getTotalNetcosts($data);

		$results = $this->model_gudang_product->getNetcosts($data);
		$this->data['cancel'] = $this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->load->model('catalog/gudang');
		$this->load->model('catalog/product');
		$this->data['desc']=$this->model_catalog_product->getProduct($product_id);
		$this->data['gudang']=$this->model_catalog_gudang->getGudang($gudang_id);
		$this->load->model('gudang/product');

		//print_r($results);
		$this->data['hpps']=array();
		foreach ($results as $result) {


      $this->data['hpps'][] = array(
				'tanggal'=> date('d/m/y H:i:s',strtotime($result['date_added'])),
				'net_cost'   => $this->currency->format($result['net_cost']),

			);

		}

		$this->data['heading_title'] = 'History HPP';
		$this->data['token'] = $this->session->data['token'];



		$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

        if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}

		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
		}
		if (isset($this->request->get['gudang_id'])) {
			$url .= '&gudang_id=' . $this->request->get['gudang_id'];
		}

		$next=$pagekartu+1;

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $pagekartu;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/productgudang/historyhpp', 'token=' . $this->session->data['token'] . $url . '&pagekartu={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['tanggal']=$tanggal;
		$this->template = 'catalog/historyhpp.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function historyharga() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('History HPP');

			$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

        if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
		}
		if (isset($this->request->get['gudang_id'])) {
			$url .= '&gudang_id=' . $this->request->get['gudang_id'];
		}

		$this->data['url'] = $this->url->link('catalog/productgudang/historyharga', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['pagekartu'])) {
			$url .= '&pagekartu=' . $this->request->get['pagekartu'];
		}


		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['gudang_id'])) {
			$gudang_id = $this->request->get['gudang_id'];
		} else {
			$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['pagekartu'])) {
			$pagekartu = $this->request->get['pagekartu'];
		} else {
			$pagekartu = 1;
		}

		if (isset($this->request->get['tanggal'])) {
			$tanggal = $this->request->get['tanggal'];
		} else {
			$tanggal = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('gudang/product');

		$this->data['orders'] = array();

		$data = array(
        'tanggal'     => $tanggal,
				'product_id'	=> $product_id,
				'gudang_id'	=> $gudang_id,
      'start'                  => ($pagekartu - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_gudang_product->getTotalPrices($data);

		$results = $this->model_gudang_product->getPrices($data);
		$this->data['cancel'] = $this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->load->model('catalog/gudang');
		$this->load->model('catalog/product');
		$this->data['desc']=$this->model_catalog_product->getProduct($product_id);
		$this->data['gudang']=$this->model_catalog_gudang->getGudang($gudang_id);
		$this->load->model('gudang/product');
		$this->load->model('catalog/title');

		//print_r($results);
		$this->data['hpps']=array();
		foreach ($results as $result) {

			$title=$this->model_catalog_title->getTitle($result['title']);
      $this->data['hpps'][] = array(
				'tanggal'=> date('d/m/y H:i:s',strtotime($result['date_added'])),
				'price'   => $this->currency->format($result['price']),
				'name'        => $title.' '.$result['name']

			);

		}

		$this->data['heading_title'] = 'History Harga';
		$this->data['token'] = $this->session->data['token'];



		$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

        if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}

		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
		}
		if (isset($this->request->get['gudang_id'])) {
			$url .= '&gudang_id=' . $this->request->get['gudang_id'];
		}

		$next=$pagekartu+1;

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $pagekartu;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/productgudang/historyharga', 'token=' . $this->session->data['token'] . $url . '&pagekartu={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['tanggal']=$tanggal;
		$this->template = 'catalog/historyharga.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	// baru 2 Maret 2020
	public function hargaterendah() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('History HPP');

			$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

        if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
		}
		if (isset($this->request->get['gudang_id'])) {
			$url .= '&gudang_id=' . $this->request->get['gudang_id'];
		}

		$this->data['url'] = $this->url->link('catalog/productgudang/historyharga', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['pagekartu'])) {
			$url .= '&pagekartu=' . $this->request->get['pagekartu'];
		}


		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['gudang_id'])) {
			$gudang_id = $this->request->get['gudang_id'];
		} else {
			$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['pagekartu'])) {
			$pagekartu = $this->request->get['pagekartu'];
		} else {
			$pagekartu = 1;
		}

		if (isset($this->request->get['tanggal'])) {
			$tanggal = $this->request->get['tanggal'];
		} else {
			$tanggal = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('gudang/product');

		$this->data['orders'] = array();

		$data = array(
        	'tanggal'     => $tanggal,
			'product_id'	=> $product_id,
			'gudang_id'	=> $gudang_id,
      		'start'                  => ($pagekartu - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = 0;

		$results = $this->model_gudang_product->gethargaterendah($data);
		$this->data['cancel'] = $this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action'] = $this->url->link('catalog/productgudang/hargaterendahsave', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->load->model('catalog/gudang');
		$this->load->model('catalog/product');
		$this->data['desc']=$this->model_catalog_product->getProduct($product_id);
		$this->data['gudang']=$this->model_catalog_gudang->getGudang($gudang_id);
		$this->load->model('gudang/product');
		$this->load->model('catalog/title');

		//print_r($results);
		$this->data['product_specials']=array();
		foreach ($results as $result) {
      		$this->data['product_specials'][] = array(
				'date' => date('d/m/Y',strtotime($result['date'])),
				'harga_terendah' => $result['harga_terendah'],
				'poin' => $result['poin'],
				'id'=>$result['id']
			);

		}

		$this->data['heading_title'] = 'History Harga';
		$this->data['token'] = $this->session->data['token'];



		$url = '';

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

        if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . $this->request->get['filter_option'];
		}
        if (isset($this->request->get['filter_qty'])) {
			$url .= '&filter_qty=' . $this->request->get['filter_qty'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		if (isset($this->request->get['type'])) {
			$url .= '&type=' . $this->request->get['type'];
		}

		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
		}
		if (isset($this->request->get['gudang_id'])) {
			$url .= '&gudang_id=' . $this->request->get['gudang_id'];
		}

		$next=$pagekartu+1;

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $pagekartu;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/productgudang/historyharga', 'token=' . $this->session->data['token'] . $url . '&pagekartu={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['tanggal']=$tanggal;
		$this->template = 'catalog/hargaterendah.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function hargaterendahsave(){
		$this->load->model('gudang/product');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if($this->user->getUsername()=="pawit"){
			echo "<pre>";print_r($this->request->post);exit;
			}
			$this->model_gudang_product->addhargaterendah($this->request->post);
			$this->session->data['success'] = 'Success: Harga terendah berhasil ditambahkan.';
				
				$url = '';

				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['filter_category_id'])) {
					$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
				}

					if (isset($this->request->get['filter_status'])) {
					$url .= '&filter_status=' . $this->request->get['filter_status'];
				}

		        if (isset($this->request->get['filter_urutkan'])) {
					$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}
				$this->redirect($this->url->link('catalog/productgudang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
	}
}
?>
