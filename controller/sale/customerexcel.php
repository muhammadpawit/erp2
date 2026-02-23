<?php
class ControllerSaleCustomerExcel extends Controller {
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
		
		/*
				'KATEGORI CUSTOMER'	=> $result['customer_group'],
				'NAMA CUSTOMER'        => $title.' '.$result['name'],
				'ALAMAT'        => $result['alamat'],
				'date_added'        => date('d/m/y',strtotime($result['date_added'])),
				'tgllahir'        => date('d/m/y',strtotime($result['tgllahir'])),
				'TELEPHONE'        => $result['telephone'],
				'NPWP'	=> $result['npwp'],
				//'area'	=> $area,
				'EMAIL'	=> $result['email'],
				'SALES'	=> $result['firstname'],
				'DEPOSIT'	=> $this->currency->format($result['deposit']),
				'PIUTANG'	=> $this->currency->format($piutang),
				'LIMIT PIUTANG'	=> $this->currency->format($result['limit_piutang']),
		*/
		$cell_definition = array(
			'A' => 'SALES',
			'B' => 'KATEGORI CUSTOMER',
			'C' => 'NAMA CUSTOMER',
			'D' => 'ALAMAT',
			'E' => 'Kab/Kota',
			'F' => 'Provinsi',
			'G' => 'TELEPHONE',
			'H' => 'Telp Pemilik',
			'I' => 'Hp Pemilik',
			'J' => 'TELEPHONE2',
			'K' => 'NPWP',
			'L' => 'EMAIL',
			'M' => 'DEPOSIT',
			'N' => 'PIUTANG',
			'O' => 'LIMIT PIUTANG',
			'P' => 'Penjualan6bulanterakhir'
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
		$fileName = "Data_customer_nisson_".$presentDate.".xlsx";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		die();
	}
	
	public function cetakexcel() {
		$this->load->model('sale/customer');
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		if (isset($this->request->get['filter_alamat'])) {
			$filter_alamat = $this->request->get['filter_alamat'];
		} else {
			$filter_alamat = null;
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['filter_sales'])) {
			$filter_sales = $this->request->get['filter_sales'];
		} else {
			$filter_sales = null;
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$filter_provinsi = $this->request->get['filter_provinsi'];
		} else {
			$filter_provinsi = null;
		}

		if (isset($this->request->get['filter_customer_group'])) {
			$filter_customer_group = $this->request->get['filter_customer_group'];
		} else {
			$filter_customer_group = null;
		}

		if (isset($this->request->get['filter_tgllahir'])) {
			$filter_tgllahir = $this->request->get['filter_tgllahir'];
		} else {
			$filter_tgllahir = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'customer_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_alamat'])) {
			$url .= '&filter_alamat=' . urlencode(html_entity_decode($this->request->get['filter_alamat'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}

		if (isset($this->request->get['filter_tgllahir'])) {
			$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('sale/customer/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('sale/customer/delete', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('catalog/title');
		$this->load->model('catalog/area');
		$this->load->model('user/user');
		//$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		$this->data['customers'] = array();

		$data = array(
			'customer.name'	  => array('LIKE',$filter_name),
			'customer.alamat'	  => array('LIKE',$filter_alamat),
			'customer.sales'	=> ($filter_sales != null)?$filter_sales:array('>=',0),
			'customer.hapus'	=> array('<',1)
			//'customer.customer_group_id'	=> ($filter_customer_group != null)?$filter_customer_group:array('>=',0)
		);
		if(!empty($filter_customer_group)){
			$data['customer.customer_group_id']=array('IN',$filter_customer_group);
		}
		if(!empty($filter_provinsi)){
			$data['country']=array('IN',$filter_provinsi);
		}
		if(!empty($filter_tgllahir)){
			//$tgl=explode('-',$filter_tgllahir);
			/*$data["DATE_TRUNC('month',tgllahir)"]=$tgl[0];
			$data["DATE_TRUNC('day',tgllahir)"]=$tgl[1];*/
			$data['EXTRACT(month FROM "tgllahir")']=$filter_tgllahir;
			//$data['EXTRACT(day FROM "tgllahir")']=$tgl[1];
		}
		//if($custdata['']){}
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$orders=array();
		if($sort == 'sales'){
			$orders=array('users.firstname'=>$order);
		}else{
			$orders=array($sort=>$order);
		}

		$results = $this->model_sale_customer->getVendors($data,$orders,0,null);
		$product_total = $this->model_sale_customer->totalVendors($data);
		//print_r($results);exit;
		$this->load->model('sale/invoice');
		$this->load->model('sale/customer');
		$penjualanterakhir=0;
		foreach ($results as $result) {
			$action = array();
			$title=$this->model_catalog_title->getTitle($result['title']);
			$piutang=$this->model_sale_invoice->getPiutang($result['customer_id']);
			$penjualanterakhir=$this->model_sale_customer->penjualanterakhir($result['customer_id']);
			$this->data['customers'][] = array(
				'customer_id' => $result['customer_id'],
				'KATEGORI CUSTOMER'	=> $result['customer_group'],
				'NAMA CUSTOMER'        => $title.' '.$result['name'],
				//'ALAMAT'        => $result['alamat'] ." ".$kota['name']." ".$provinsi['name'],
				'ALAMAT'        => $result['alamat'],
				'Kab/Kota' =>$result['namakota'],
				'Provinsi' =>$result['namaprovinsi'],
				'date_added'        => date('d/m/y',strtotime($result['date_added'])),
				'tgllahir'        => date('d/m/y',strtotime($result['tgllahir'])),
				'TELEPHONE'        => $result['telephone'],
				'TELEPHONE2'        => $result['telephone2'],
				'Telp Pemilik' => $result['telp_pemilik'],
				'Hp Pemilik' => str_replace(" ","",$result['hp_pemilik']),
				'NPWP'	=> $result['npwp'],
				//'area'	=> $area,
				'EMAIL'	=> $result['email'],
				'SALES'	=> $result['firstname'],
				'DEPOSIT'	=> $this->currency->format($result['deposit']),
				'PIUTANG'	=> $this->currency->format($piutang),
				'LIMIT PIUTANG'	=> $this->currency->format($result['limit_piutang']),
				'Penjualan6bulanterakhir'	=> $this->currency->format($penjualanterakhir),
				//'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				//'action'      => $action
			);
		}
		return $this->data['customers'];
	}
	
	// end baru
	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Customer to Excel');

		$this->load->model('sale/customer');

		$this->getList();
	}


	public function insert() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Customer');

		$this->load->model('sale/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_customer->addVendor($this->request->post);

			$this->session->data['success'] = 'Data Customer berhasil ditambahkan.';
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_sales'])) {
				$url .= '&filter_sales=' . $this->request->get['filter_sales'];
			}
			if (isset($this->request->get['filter_customer_group'])) {
				$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
			}
			if (isset($this->request->get['filter_provinsi'])) {
				$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
			}

			if (isset($this->request->get['filter_tgllahir'])) {
				$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Customer');

		$this->load->model('sale/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_customer->updateVendor($this->request->post, array('customer_id'=>$this->request->get['customer_id']));

			$this->session->data['success'] = 'Data Customer berhasil diperbarui';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_sales'])) {
				$url .= '&filter_sales=' . $this->request->get['filter_sales'];
			}
			if (isset($this->request->get['filter_provinsi'])) {
				$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
			}

			if (isset($this->request->get['filter_customer_group'])) {
				$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
			}

			if (isset($this->request->get['filter_tgllahir'])) {
				$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Customer');

		$this->load->model('sale/customer');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $id) {
				$data=array('hapus'	=> 1);
				$where=array('customer_id' => $id);
				$this->model_sale_customer->updateVendor($data,$where);
			}

			$this->session->data['success'] = 'Data Customer berhasil dihapus';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_sales'])) {
				$url .= '&filter_sales=' . $this->request->get['filter_sales'];
			}
			if (isset($this->request->get['filter_customer_group'])) {
				$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
			}
			if (isset($this->request->get['filter_provinsi'])) {
				$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
			}

			if (isset($this->request->get['filter_tgllahir'])) {
				$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		if (isset($this->request->get['filter_alamat'])) {
			$filter_alamat = $this->request->get['filter_alamat'];
		} else {
			$filter_alamat = null;
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['filter_sales'])) {
			$filter_sales = $this->request->get['filter_sales'];
		} else {
			$filter_sales = null;
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$filter_provinsi = $this->request->get['filter_provinsi'];
		} else {
			$filter_provinsi = null;
		}

		if (isset($this->request->get['filter_customer_group'])) {
			$filter_customer_group = $this->request->get['filter_customer_group'];
		} else {
			$filter_customer_group = null;
		}

		if (isset($this->request->get['filter_tgllahir'])) {
			$filter_tgllahir = $this->request->get['filter_tgllahir'];
		} else {
			$filter_tgllahir = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'customer_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_alamat'])) {
			$url .= '&filter_alamat=' . urlencode(html_entity_decode($this->request->get['filter_alamat'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}

		if (isset($this->request->get['filter_tgllahir'])) {
			$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('sale/customer/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('sale/customer/delete', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('catalog/title');
		$this->load->model('catalog/area');
		$this->load->model('user/user');
		//$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		$this->data['customers'] = array();

		$data = array(
			'customer.name'	  => array('LIKE',$filter_name),
			'customer.alamat'	  => array('LIKE',$filter_alamat),
			'customer.sales'	=> ($filter_sales != null)?$filter_sales:array('>=',0),
			'customer.hapus'	=> array('<',1)
			//'customer.customer_group_id'	=> ($filter_customer_group != null)?$filter_customer_group:array('>=',0)
		);
		if(!empty($filter_customer_group)){
			$data['customer.customer_group_id']=array('IN',$filter_customer_group);
		}
		if(!empty($filter_provinsi)){
			$data['country']=array('IN',$filter_provinsi);
		}
		if(!empty($filter_tgllahir)){
			//$tgl=explode('-',$filter_tgllahir);
			/*$data["DATE_TRUNC('month',tgllahir)"]=$tgl[0];
			$data["DATE_TRUNC('day',tgllahir)"]=$tgl[1];*/
			$data['EXTRACT(month FROM "tgllahir")']=$filter_tgllahir;
			//$data['EXTRACT(day FROM "tgllahir")']=$tgl[1];
		}
		//if($custdata['']){}
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$orders=array();
		if($sort == 'sales'){
			$orders=array('users.firstname'=>$order);
		}else{
			$orders=array($sort=>$order);
		}
		/*if($sort == 'customer.name'){
			$orders=array($sort=>$order);
		}else{
			$orders=array($sort=>$order,'name'=>'ASC','date_added'=>'DESC','tgllahir'=>'DESC','deposit'=>'DESC');
		}*/
		//name,telephone, alamat, date_added, tgllahir, deposit
		//echo "<pre>";print_r($orders);exit;
		$results = $this->model_sale_customer->getVendors($data,$orders,$limit,$offset);
		$product_total = $this->model_sale_customer->totalVendors($data);

		$this->load->model('sale/invoice');
		$this->load->model('sale/customer');
		$penjualanterakhir=0;
		foreach ($results as $result) {
			$action = array();
			$title=$this->model_catalog_title->getTitle($result['title']);
			$piutang=$this->model_sale_invoice->getPiutang($result['customer_id']);
			$penjualanterakhir=$this->model_sale_customer->penjualanterakhir($result['customer_id']);
			$this->data['customers'][] = array(
				'customer_id' => $result['customer_id'],
				'customer_group'	=> $result['customer_group'],
				'name'        => $title.' '.$result['name'],
				'alamat'        => $result['alamat'],
				'date_added'        => date('d/m/y',strtotime($result['date_added'])),
				'tgllahir'        => date('d/m/y',strtotime($result['tgllahir'])),
				'telephone'        => $result['telephone'],
				'npwp'	=> $result['npwp'],
				//'area'	=> $area,
				'email'	=> $result['email'],
				'sales'	=> $result['firstname'],
				'deposit'	=> $this->currency->format($result['deposit']),
				'piutang'	=> $this->currency->format($piutang),
				'limit_piutang'	=> $this->currency->format($result['limit_piutang']),
				'penjualanterakhir'=>$this->currency->format($penjualanterakhir),
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'      => $action
			);
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_alamat'])) {
			$url .= '&filter_alamat=' . urlencode(html_entity_decode($this->request->get['filter_alamat'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}

		if (isset($this->request->get['filter_tgllahir'])) {
			$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$this->data['sort_date_added'] = $this->url->link('sale/customerexcel', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');
		$this->data['sort_name'] = $this->url->link('sale/customerexcel', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$this->data['sort_tgllahir'] = $this->url->link('sale/customerexcel', 'token=' . $this->session->data['token'] . '&sort=tgllahir' . $url, 'SSL');
		$this->data['sort_deposit'] = $this->url->link('sale/customerexcel', 'token=' . $this->session->data['token'] . '&sort=deposit' . $url, 'SSL');
		$this->data['sort_sales'] = $this->url->link('sale/customerexcel', 'token=' . $this->session->data['token'] . '&sort=sales' . $url, 'SSL');
		$this->data['export'] = $this->url->link('sale/customerexcel/exporttoexcel', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_alamat'])) {
			$url .= '&filter_alamat=' . urlencode(html_entity_decode($this->request->get['filter_alamat'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}

		if (isset($this->request->get['filter_tgllahir'])) {
			$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/customerexcel', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_provinsi'] = $filter_provinsi;
		$this->data['filter_customer_group'] = $filter_customer_group;
		$this->data['filter_tgllahir'] = $filter_tgllahir;
		$this->data['filter_alamat'] = $filter_alamat;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['token'] = $this->session->data['token'];

		$this->data['areas']=$this->model_catalog_area->getOptions();

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('sale/customer_group');
		$this->data['customer_groups']=$this->model_sale_customer_group->getCustomerGroups();


		$this->template = 'sale/customer_listexcel.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getForm() {

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}

		if (isset($this->request->get['filter_tgllahir'])) {
			$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

 		if (isset($this->error['warning'])) {
			$this->data['error'] = $this->error;
		} else {
			$this->data['error'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}



		if (!isset($this->request->get['id'])) {
			$this->data['action'] = $this->url->link('sale/customer/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'].$url. '&id=' . $this->request->get['id'].'&customer_id=' . $this->request->get['id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'].$url, 'SSL');

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$option_info = $this->model_sale_customer->getVendor(array('customer_id'	=> $this->request->get['id']));
    	}

		$this->data['token'] = $this->session->data['token'];


		if (!empty($this->request->post)) {
			$this->data['title'] = $this->request->post['title'];
			$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
			$this->data['area'] = $this->request->post['area'];
			$this->data['name'] = $this->request->post['name'];
			$this->data['alamat'] = $this->request->post['alamat'];
			$this->data['email'] = $this->request->post['email'];
			$this->data['npwp'] = $this->request->post['npwp'];
			$this->data['telephone'] = $this->request->post['telephone'];
			$this->data['siup'] = $this->request->post['siup'];
			$this->data['siup_expire'] = $this->request->post['siup_expire'];
			$this->data['tdp'] = $this->request->post['tdp'];
			$this->data['tdp_expire'] = $this->request->post['tdp_expire'];
			$this->data['tdp_expire'] = $this->request->post['tdp_expire'];
			$this->data['status'] = $this->request->post['status'];
			$this->data['nama_pemilik'] = $this->request->post['nama_pemilik'];
			$this->data['alamat_pemilik'] = $this->request->post['alamat_pemilik'];
			$this->data['telp_pemilik'] = $this->request->post['telp_pemilik'];
			$this->data['hp_pemilik'] = $this->request->post['hp_pemilik'];
			$this->data['tempatlahir'] = $this->request->post['tempatlahir'];
			$this->data['tgllahir'] = $this->request->post['tgllahir'];
			$this->data['status_perkawinan'] = $this->request->post['status_perkawinan'];
			$this->data['fax'] = $this->request->post['fax'];
			$this->data['sales'] = $this->request->post['sales'];
			$this->data['limit_piutang'] = $this->request->post['limit_piutang'];
			$this->data['piutang'] = $this->request->post['piutang'];
			$this->data['country']=$this->request->post['country'];
			$this->data['zone']=$this->request->post['zone'];
			$this->data['city']=$this->request->post['city'];
			$this->data['namanpwp']=$this->request->post['namanpwp'];
			$this->data['alamatnpwp']=$this->request->post['alamatnpwp'];
			$this->data['namaktp']=$this->request->post['namaktp'];
			$this->data['alamatktp']=$this->request->post['alamatktp'];
			$this->data['noktp']=$this->request->post['noktp'];

		} elseif (!empty($option_info)) {
			//print_r($option_info);
			$this->data['title'] = empty($option_info['title'])?0:$option_info['title'];
			$this->data['area'] = empty($option_info['area'])?0:$option_info['area'];
			$this->data['name'] = $option_info['name'];
			$this->data['alamat'] = $option_info['alamat'];
			$this->data['fax'] = $option_info['fax'];
			$this->data['customer_group_id'] = empty($option_info['customer_group_id'])?0:$option_info['customer_group_id'];
			$this->data['email'] = $option_info['email'];
			$this->data['npwp'] = $option_info['npwp'];
			$this->data['telephone'] = $option_info['telephone'];
			$this->data['siup'] = $option_info['siup'];
			$this->data['siup_expire'] = empty($option_info['siup_expire'])?date('Y-m-d'):$option_info['siup_expire'];
			$this->data['tdp'] = $option_info['tdp'];
			$this->data['tdp_expire'] = empty($option_info['tdp_expire'])?date('Y-m-d'):$option_info['tdp_expire'];
			$this->data['status'] = empty($option_info['status'])?1:$option_info['status'];
			$this->data['nama_pemilik'] = $option_info['nama_pemilik'];
			$this->data['alamat_pemilik'] = $option_info['alamat_pemilik'];
			$this->data['telp_pemilik'] = $option_info['telp_pemilik'];
			$this->data['hp_pemilik'] = $option_info['hp_pemilik'];
			$this->data['tempat_lahir'] =$option_info['tempat_lahir'];
			$this->data['tgllahir'] = empty($option_info['tgllahir'])?date('Y-m-d'):$option_info['tgllahir'];
			$this->data['status_perkawinan'] = empty($option_info['status_perkawinan'])?1:$option_info['status_perkawinan'];
			$this->data['sales'] = $option_info['sales'];
			$this->data['piutang'] = empty($option_info['piutang'])?0:$option_info['piutang'];
			$this->data['limit_piutang'] = empty($option_info['limit_piutang'])?0:$option_info['limit_piutang'];
			$this->data['country']=empty($option_info['country'])?0:$option_info['country'];
			$this->data['zone']=empty($option_info['zone'])?0:$option_info['zone'];
			$this->data['city']=empty($option_info['city'])?0:$option_info['city'];
			$this->data['namanpwp']=$option_info['namanpwp'];
			$this->data['alamatnpwp']=$option_info['alamatnpwp'];
			$this->data['namaktp']=$option_info['namaktp'];
			$this->data['alamatktp']=$option_info['alamatktp'];
			$this->data['noktp']=$option_info['noktp'];
		} else {
			$this->data['title'] = 0;
			$this->data['area'] = 0;
			$this->data['name'] = "";
			$this->data['alamat'] = "";
			$this->data['email'] = "";
			$this->data['fax'] = "";
			$this->data['npwp'] = "";
			$this->data['telephone'] = "";
			$this->data['siup'] = "";
			$this->data['siup_expire'] = date('Y-m-d');
			$this->data['tdp'] = "";
			$this->data['tdp_expire'] = date('Y-m-d');
			//$this->data['tdp_expire'] = "";
			$this->data['status'] = 0;
			$this->data['nama_pemilik'] = "";
			$this->data['alamat_pemilik'] = "";
			$this->data['telp_pemilik'] = "";
			$this->data['hp_pemilik'] = "";
			$this->data['tempatlahir'] = "";
			$this->data['tgllahir'] = date('Y-m-d');
			$this->data['status_perkawinan'] = 0;
			$this->data['sales'] = 0;
			$this->data['limit_piutang'] = 0;
			$this->data['customer_group_id'] =1;
			$this->data['piutang']=0;
			$this->data['country']=0;
			$this->data['zone']=0;
			$this->data['city']=0;
			$this->data['namanpwp']="";
			$this->data['alamatnpwp']="";
			$this->data['namaktp']="";
			$this->data['alamatktp']="";
			$this->data['noktp']="";
		}

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();


		$this->load->model('localisation/zone');
		$this->load->model('localisation/city');
		//foreach ($this->data['addresses'] as $i => $value) {
     if(!empty($this->data['country_id'])){
      $this->data['zones'] = $this->model_localisation_zone->getZonesByCountryId($this->data['country_id']);
     }
     if(!empty($this->data['zone_id'])){
  $this->data['cities'] = $this->model_localisation_city->getCitiesByZoneId($this->data['zone_id']);
     }

		$this->load->model('sale/customer_group');
		$this->data['customer_groups']=$this->model_sale_customer_group->getCustomerGroups();

		$this->load->model('catalog/title');
		$this->data['titles']=$this->model_catalog_title->getOptions();

		$this->load->model('catalog/area');
		$this->data['areas']=$this->model_catalog_area->getOptions();

		$this->template = 'sale/customer_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		/*if (!$this->user->hasPermission('modify', 'sale/customer')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Customer';
		}*/

		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 255)) {
			$this->error['name'] = 'Nama customer harus diisi.';
		}

		/*if(empty($this->request->post['sales'])){
			$this->error['sales'] = 'Sales harus dipilih.';
		}*/
		if(empty($this->request->post['customer_group_id'])){
			$this->error['customer_group_id'] = 'Customer Group harus dipilih.';
		}

		if(empty($this->request->post['country'])){
			$this->error['country'] = 'Provinsi harus dipilih.';
		}

		if(empty($this->request->post['zone'])){
			$this->error['zone'] = 'Kabupaten harus dipilih.';
		}

		if(empty($this->request->post['city'])){
			$this->error['city'] = 'Kecamatan harus dipilih.';
		}

		//checkdate
		$tgllahir=$this->request->post['tgllahir'];

		if(empty($tgllahir)){
			$this->error['tgllahir'] = 'Tanggal Lahir harus diisi.';
		}else{
			$tgl=explode('-',$tgllahir);
			if(!checkdate($tgl[1],$tgl[2],$tgl[0])){
				$this->error['tgllahir'] = 'Tanggal Lahir tidak valid.';
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = 'Mohon cek kembali form Anda.';
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		/*if (!$this->user->hasPermission('modify', 'sale/customer')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Customer';
		}*/

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function autocomplete() {
		$json = array();

		$this->load->model('sale/customer');
		$this->load->model('catalog/title');

			if (isset($this->request->get['q'])) {
				$filter_name = $this->request->get['q'];
			} else {
				$filter_name = null;
			}

			if (isset($this->request->get['s'])) {
				$sales = $this->request->get['s'];
			} else {
				$sales = 0;
			}

			$this->load->model('user/user');
			$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

			if($custdata != 1){
				$sales=$this->user->getId();
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}
			if($sales){
				$data = array(
				'customer.name'	  => array('LIKE',$filter_name),
				'customer.sales'	=> $sales
					//'start'               => 0,
					//'limit'               => $limit
				);
			}else{
				$data = array(
				'customer.name'	  => array('LIKE',$filter_name),
				//'sales'	=> $s
					//'start'               => 0,
					//'limit'               => $limit
				);
			}
			$offset=0;
			$limit=$limit;

			$results = $this->model_sale_customer->getVendors($data,array(),$limit,$offset);

			foreach ($results as $result) {
				$title='';
				if(!empty($result['title'])){
					$title=$this->model_catalog_title->getTitle($result['title']);
				}
				$json[] = array(
					'id' => $result['customer_id'],
					'text' => strip_tags(html_entity_decode($title.' '.$result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}


		$this->response->setOutput(json_encode($json));
	}

	public function autocompleteaddress() {
		$json = array();

		$this->load->model('sale/customer');
			if(isset($this->request->get['customer_id'])){

			if (isset($this->request->get['q'])) {
				$filter_name = $this->request->get['q'];
			} else {
				$filter_name = null;
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
			'filter_name'	  => $filter_name,
			'customer_id'	=> $this->request->get['customer_id'],
			'start'	=> 0,
			'limit'	=> $limit
				//'start'               => 0,
				//'limit'               => $limit
			);
			$offset=0;
			$limit=$limit;

			$results = $this->model_sale_customer->getAddresses2($this->request->get['customer_id'],$data);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['address_id'],
					'text' => strip_tags(html_entity_decode($result['firstname'].' '.$result['address_1'].', '.$result['city'].', '.$result['zone'].', '.$result['country'], ENT_QUOTES, 'UTF-8')),

				);
			}
		}


		$this->response->setOutput(json_encode($json));
	}

	public function alamat() {
        $this->load->language('sale/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');
    if (isset($this->request->get['customer_id'])) {
			if(empty($this->request->get['customer_id'])){
				$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				$customer_id = $this->request->get['customer_id'];
			}
		} else {
			$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}


		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'customer.name';
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

        if (isset($this->request->get['pagealamat'])) {
			$pagealamat = $this->request->get['pagealamat'];
		} else {
			$pagealamat = 1;
		}

		$url = '';
        if (isset($this->request->get['customer_id'])) {
			$url .= '&customer_id=' .$this->request->get['customer_id'];
		}


		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}

		if (isset($this->request->get['filter_tgllahir'])) {
			$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
        if (isset($this->request->get['pagealamat'])) {
			$url .= '&pagealamat=' . $this->request->get['pagealamat'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['cancel'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['insert'] = $this->url->link('sale/customer/insertalamat', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/customer/deletealamat', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['addresses'] = array();

		$data = array(
			'start'                    => ($pagealamat - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);

		$address_total = $this->model_sale_customer->getTotalAddress($customer_id);

		$results = $this->model_sale_customer->getAddresses($customer_id,$data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/customer/updatealamat', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'].'&address_id='.$result['address_id'] . $url, 'SSL')
			);

			$this->data['addresses'][] = array(
				'address_id'    => $result['address_id'],
				'firstname'           => $result['firstname'],
				'lastname'           => $result['lastname'],
				'address'          => $result['address_1'].' '.$result['address_2'],

				'city'             => $result['city'],
				'zone'             => $result['zone'],
				'country'             => $result['country'],
				'selected'       => isset($this->request->post['selected']) && in_array($result['address_id'], $this->request->post['selected']),

			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_email'] = $this->language->get('column_email');
		$this->data['column_customer_group'] = $this->language->get('column_customer_group');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_approved'] = $this->language->get('column_approved');
		$this->data['column_ip'] = $this->language->get('column_ip');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_login'] = $this->language->get('column_login');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_approve'] = $this->language->get('button_approve');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

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
        if (isset($this->request->get['customer_id'])) {
			$url .= '&customer_id=' .$this->request->get['customer_id'];
		}


		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_tgllahir'])) {
			$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$pagination = new Pagination();
		$pagination->total = $address_total;
		$pagination->page = $pagealamat;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/customer/alamat', 'token=' . $this->session->data['token'] . $url . '&pagealamat={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->template = 'sale/alamat_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  public function insertalamat() {
		$this->load->language('sale/customer');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');
		if (!isset($this->request->get['customer_id'])) {
            $this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormAlamat()) {
      	  	$this->model_sale_customer->addAddress($this->request->post,$this->request->get['customer_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			if (isset($this->request->get['customer_id'])) {
				$url .= '&customer_id=' . $this->request->get['customer_id'];
			}
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_provinsi'])) {
				$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
			}
			if (isset($this->request->get['filter_customer_group'])) {
				$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
			}

			if (isset($this->request->get['filter_tgllahir'])) {
				$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

            if (isset($this->request->get['pagealamat'])) {
				$url .= '&pagealamat=' . $this->request->get['pagealamat'];
			}

			$this->redirect($this->url->link('sale/customer/alamat', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getFormAlamat();
  	}



  	public function deletealamat() {
		$this->load->language('sale/customer');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

        if (!isset($this->request->get['customer_id'])) {
            $this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $address_id) {
				$this->model_sale_customer->deleteAddress($address_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
      if (isset($this->request->get['customer_id'])) {
				$url .= '&customer_id=' . $this->request->get['customer_id'];
			}
			if (isset($this->request->get['filter_provinsi'])) {
				$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
			}

			if (isset($this->request->get['filter_tgllahir'])) {
				$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
			}
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_customer_group'])) {
				$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
			}


			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

            if (isset($this->request->get['pagealamat'])) {
				$url .= '&pagealamat=' . $this->request->get['pagealamat'];
			}

			$this->redirect($this->url->link('sale/customer/alamat', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->alamat();
  	}

    private function getFormAlamat() {
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
    	$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_add_blacklist'] = $this->language->get('text_add_blacklist');
		$this->data['text_remove_blacklist'] = $this->language->get('text_remove_blacklist');

		$this->data['column_ip'] = $this->language->get('column_ip');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');

    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
    	$this->data['entry_fax'] = $this->language->get('entry_fax');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
    	$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_company_id'] = $this->language->get('entry_company_id');
		$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_default'] = $this->language->get('entry_default');
		$this->data['entry_amount'] = $this->language->get('entry_amount');
		$this->data['entry_points'] = $this->language->get('entry_points');
 		$this->data['entry_description'] = $this->language->get('entry_description');

		$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
    	$this->data['button_add_address'] = $this->language->get('button_add_address');
		$this->data['button_add_transaction'] = $this->language->get('button_add_transaction');
		$this->data['button_add_reward'] = $this->language->get('button_add_reward');
    	$this->data['button_remove'] = $this->language->get('button_remove');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_address'] = $this->language->get('tab_address');
		$this->data['tab_transaction'] = $this->language->get('tab_transaction');
		$this->data['tab_reward'] = $this->language->get('tab_reward');
		$this->data['tab_ip'] = $this->language->get('tab_ip');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['customer_id'])) {
			$this->data['customer_id'] = $this->request->get['customer_id'];
		} else {
			$this->data['customer_id'] = 0;
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}




		if (isset($this->error['address_firstname'])) {
			$this->data['error_address_firstname'] = $this->error['address_firstname'];
		} else {
			$this->data['error_address_firstname'] = '';
		}

 		if (isset($this->error['address_lastname'])) {
			$this->data['error_address_lastname'] = $this->error['address_lastname'];
		} else {
			$this->data['error_address_lastname'] = '';
		}



		if (isset($this->error['address_address_1'])) {
			$this->data['error_address_address_1'] = $this->error['address_address_1'];
		} else {
			$this->data['error_address_address_1'] = '';
		}

		if (isset($this->error['address_city'])) {
			$this->data['error_address_city'] = $this->error['address_city'];
		} else {
			$this->data['error_address_city'] = '';
		}

		if (isset($this->error['address_postcode'])) {
			$this->data['error_address_postcode'] = $this->error['address_postcode'];
		} else {
			$this->data['error_address_postcode'] = '';
		}

		if (isset($this->error['address_country'])) {
			$this->data['error_address_country'] = $this->error['address_country'];
		} else {
			$this->data['error_address_country'] = '';
		}

		if (isset($this->error['address_zone'])) {
			$this->data['error_address_zone'] = $this->error['address_zone'];
		} else {
			$this->data['error_address_zone'] = '';
		}

		$url = '';
      if (isset($this->request->get['customer_id'])) {
				$url .= '&customer_id=' . $this->request->get['customer_id'];
			}
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_provinsi'])) {
				$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
			}

			if (isset($this->request->get['filter_customer_group'])) {
				$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
			}

			if (isset($this->request->get['filter_tgllahir'])) {
				$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

            if (isset($this->request->get['pagealamat'])) {
				$url .= '&pagealamat=' . $this->request->get['pagealamat'];
			}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('sale/customer/insertalamat', 'token=' . $this->session->data['token'] . $url, 'SSL');


    	$this->data['cancel'] = $this->url->link('sale/customer/alamat', 'token=' . $this->session->data['token'] . $url, 'SSL');


    	if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = $this->request->post['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	} else {
      		$this->data['lastname'] = '';
    	}

    	if (isset($this->request->post['address_1'])) {
      		$this->data['address_1'] = $this->request->post['address_1'];
    	} else {
      		$this->data['address_1'] = '';
    	}


		if (isset($this->request->post['address_2'])) {
      		$this->data['address_2'] = $this->request->post['address_2'];
    	} else {
      		$this->data['address_2'] = '';
    	}

        if (isset($this->request->post['city_id'])) {
      		$this->data['city_id'] = $this->request->post['city_id'];
    	} else {
      		$this->data['city_id'] = '';
    	}

        if (isset($this->request->post['postcode'])) {
      		$this->data['postcode'] = $this->request->post['postcode'];
    	} else {
      		$this->data['postcode'] = '';
    	}

        if (isset($this->request->post['country_id'])) {
      		$this->data['country_id'] = $this->request->post['country_id'];
    	} else {
      		$this->data['country_id'] = '';
    	}

        if (isset($this->request->post['zone_id'])) {
      		$this->data['zone_id'] = $this->request->post['zone_id'];
    	} else {
      		$this->data['zone_id'] = '';
    	}

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();

		/* if (isset($this->request->post['address'])) {
      		$this->data['addresses'] = $this->request->post['address'];
		} elseif (isset($this->request->get['customer_id'])) {
			$this->data['addresses'] = $this->model_sale_customer->getAddresses($this->request->get['customer_id']);
		} else {
			$this->data['addresses'] = array();

    	}
 */
     	// tokocepat
		$this->load->model('localisation/zone');
		$this->load->model('localisation/city');
		//foreach ($this->data['addresses'] as $i => $value) {
           if(!empty($this->data['country_id'])){
            $this->data['zones'] = $this->model_localisation_zone->getZonesByCountryId($this->data['country_id']);
           }
           if(!empty($this->data['zone_id'])){
		    $this->data['cities'] = $this->model_localisation_city->getCitiesByZoneId($this->data['zone_id']);
           }
        //}


		$this->template = 'sale/alamat_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

    private function validateFormAlamat() {
    	if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
            $this->error['address_firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
            $this->error['address_lastname']= $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
            $this->error['address_address_1'][$key] = $this->language->get('error_address_1');
        }



        if ($this->request->post['country_id'] == '') {
            $this->error['address_country']= $this->language->get('error_country');
        }

        if ($this->request->post['zone_id'] == '') {
            $this->error['address_zone'] = $this->language->get('error_zone');
        }

        // tokocepat
        if ($this->request->post['city_id'] == '') {
            $this->error['address_city']= $this->language->get('error_city');
        }



		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

		public function country() {
			$json = array();

			$this->load->model('localisation/country');

	    	$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

			if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
				}

				$this->response->setOutput(json_encode($json));
	}

	public function address() {
		$json = array();

		if (!empty($this->request->get['address_id'])) {
			$this->load->model('sale/customer');

			$json = $this->model_sale_customer->getAddress($this->request->get['address_id']);
		}

		$this->response->setOutput(json_encode($json));
	}

    // tokocepat
	public function zone() {
		$json = array();

		$this->load->model('localisation/zone');

    	$zone_info = $this->model_localisation_zone->getZone($this->request->get['zone_id']);

		if ($zone_info) {
			$this->load->model('localisation/city');

			$json = array(
				'zone_id'           => $zone_info['zone_id'],
				'name'              => $zone_info['name'],
				'city'              => $this->model_localisation_city->getCitiesByZoneId($this->request->get['zone_id']),
				'status'            => $zone_info['status']
			);
		}

		$this->response->setOutput(json_encode($json));
	}

	public function detail(){
		$hasil = array();

		$this->load->model('sale/customer');
		if(isset($this->request->get['customer_id'])){
			if(!empty($this->request->get['customer_id'])){
				$column=array();
				$customer_id=$this->request->get['customer_id'];
				$data = array(
					'customer_id'      =>$customer_id
				);

				$hasil=$this->model_sale_customer->getVendor($data);
				if($hasil['limit_piutang'] == 0){
					$hasil['limit_piutang'] = 9999999999999999;
				}
				$hasil['pdeposit']=$this->currency->format($hasil['deposit']);


			}
		}
		$this->response->setOutput(json_encode($hasil));
	}

	public function contact() {
        $this->load->language('sale/customer');

		$this->document->setTitle("Kontak Customer");

		$this->load->model('sale/customer');
    	if (isset($this->request->get['customer_id'])) {
			if(empty($this->request->get['customer_id'])){
				$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				$customer_id = $this->request->get['customer_id'];
			}
		} else {
			$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}


        if (isset($this->request->get['pagealamat'])) {
			$pagealamat = $this->request->get['pagealamat'];
		} else {
			$pagealamat = 1;
		}

		$url = '';
    	if (isset($this->request->get['customer_id'])) {
			$url .= '&customer_id=' .$this->request->get['customer_id'];
		}


		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}

		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_tgllahir'])) {
			$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
        if (isset($this->request->get['pagealamat'])) {
			$url .= '&pagealamat=' . $this->request->get['pagealamat'];
		}


		$this->data['cancel'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['insert'] = $this->url->link('sale/customer/insertcontact', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/customer/deletecontact', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['addresses'] = array();

		$data = array(
			'start'                    => ($pagealamat - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);

		$address_total = $this->model_sale_customer->getTotalContact($customer_id);

		$results = $this->model_sale_customer->getContacts($customer_id,$data);
		//print_r($results);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/customer/updatecontact', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'].'&address_id='.$result['address_id'] . $url, 'SSL')
			);

			$this->data['addresses'][] = array(
				'address_id'    => $result['address_id'],
				'firstname'           => $result['firstname'],
				'lastname'           => $result['lastname'],
				'telephone'           => $result['company'],
				'address'          => $result['address_1'].' '.$result['address_2'],

				'city'             => $result['city'],
				'zone'             => $result['zone'],
				'country'             => $result['country'],
				'selected'       => isset($this->request->post['selected']) && in_array($result['address_id'], $this->request->post['selected']),

			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');



		$this->data['token'] = $this->session->data['token'];

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
        if (isset($this->request->get['customer_id'])) {
			$url .= '&customer_id=' .$this->request->get['customer_id'];
		}


		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}

		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}


		if (isset($this->request->get['filter_tgllahir'])) {
			$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$pagination = new Pagination();
		$pagination->total = $address_total;
		$pagination->page = $pagealamat;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/customer/contact', 'token=' . $this->session->data['token'] . $url . '&pagealamat={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->template = 'sale/contact_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

    public function insertcontact() {
		$this->load->language('sale/customer');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');
		if (!isset($this->request->get['customer_id'])) {
            $this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormContact()) {
      	  	$this->model_sale_customer->addContact($this->request->post,$this->request->get['customer_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
      if (isset($this->request->get['customer_id'])) {
				$url .= '&customer_id=' . $this->request->get['customer_id'];
			}
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_group'])) {
				$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
			}

			if (isset($this->request->get['filter_provinsi'])) {
				$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
			}

			if (isset($this->request->get['filter_tgllahir'])) {
				$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

            if (isset($this->request->get['pagealamat'])) {
				$url .= '&pagealamat=' . $this->request->get['pagealamat'];
			}

			$this->redirect($this->url->link('sale/customer/contact', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getFormContact();
  	}



  	public function deletecontact() {
		$this->load->language('sale/customer');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer');

        if (!isset($this->request->get['customer_id'])) {
            $this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $address_id) {
				$this->model_sale_customer->deleteContact($address_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
      if (isset($this->request->get['customer_id'])) {
				$url .= '&customer_id=' . $this->request->get['customer_id'];
			}
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_provinsi'])) {
				$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
			}

			if (isset($this->request->get['filter_customer_group'])) {
				$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
			}

			if (isset($this->request->get['filter_tgllahir'])) {
				$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

            if (isset($this->request->get['pagealamat'])) {
				$url .= '&pagealamat=' . $this->request->get['pagealamat'];
			}

			$this->redirect($this->url->link('sale/customer/contact', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getFormContact();
  	}

    private function getFormContact() {
    	$this->data['heading_title'] = $this->language->get('heading_title');


		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['customer_id'])) {
			$this->data['customer_id'] = $this->request->get['customer_id'];
		} else {
			$this->data['customer_id'] = 0;
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}




		if (isset($this->error['address_firstname'])) {
			$this->data['error_address_firstname'] = $this->error['address_firstname'];
		} else {
			$this->data['error_address_firstname'] = '';
		}

 		if (isset($this->error['address_lastname'])) {
			$this->data['error_address_lastname'] = $this->error['address_lastname'];
		} else {
			$this->data['error_address_lastname'] = '';
		}



		if (isset($this->error['address_address_1'])) {
			$this->data['error_address_address_1'] = $this->error['address_address_1'];
		} else {
			$this->data['error_address_address_1'] = '';
		}

		if (isset($this->error['address_city'])) {
			$this->data['error_address_city'] = $this->error['address_city'];
		} else {
			$this->data['error_address_city'] = '';
		}

		if (isset($this->error['address_postcode'])) {
			$this->data['error_address_postcode'] = $this->error['address_postcode'];
		} else {
			$this->data['error_address_postcode'] = '';
		}

		if (isset($this->error['address_country'])) {
			$this->data['error_address_country'] = $this->error['address_country'];
		} else {
			$this->data['error_address_country'] = '';
		}

		if (isset($this->error['address_zone'])) {
			$this->data['error_address_zone'] = $this->error['address_zone'];
		} else {
			$this->data['error_address_zone'] = '';
		}

		$url = '';
            if (isset($this->request->get['customer_id'])) {
				$url .= '&customer_id=' . $this->request->get['customer_id'];
			}
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_provinsi'])) {
				$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
			}

			if (isset($this->request->get['filter_customer_group'])) {
				$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
			}

			if (isset($this->request->get['filter_tgllahir'])) {
				$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
			}


			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

            if (isset($this->request->get['pagealamat'])) {
				$url .= '&pagealamat=' . $this->request->get['pagealamat'];
			}

  		$this->data['action'] = $this->url->link('sale/customer/insertcontact', 'token=' . $this->session->data['token'] . $url, 'SSL');


    	$this->data['cancel'] = $this->url->link('sale/customer/contact', 'token=' . $this->session->data['token'] . $url, 'SSL');


    	if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = $this->request->post['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	} else {
      		$this->data['lastname'] = '';
    	}

    	if (isset($this->request->post['address_1'])) {
      		$this->data['address_1'] = $this->request->post['address_1'];
    	} else {
      		$this->data['address_1'] = '';
    	}


		if (isset($this->request->post['address_2'])) {
      		$this->data['address_2'] = $this->request->post['address_2'];
    	} else {
      		$this->data['address_2'] = '';
    	}

        if (isset($this->request->post['city_id'])) {
      		$this->data['city_id'] = $this->request->post['city_id'];
    	} else {
      		$this->data['city_id'] = '';
    	}

        if (isset($this->request->post['postcode'])) {
      		$this->data['postcode'] = $this->request->post['postcode'];
    	} else {
      		$this->data['postcode'] = '';
    	}

        if (isset($this->request->post['country_id'])) {
      		$this->data['country_id'] = $this->request->post['country_id'];
    	} else {
      		$this->data['country_id'] = '';
    	}

        if (isset($this->request->post['zone_id'])) {
      		$this->data['zone_id'] = $this->request->post['zone_id'];
    	} else {
      		$this->data['zone_id'] = '';
    	}

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();


		$this->load->model('localisation/zone');
		$this->load->model('localisation/city');
		//foreach ($this->data['addresses'] as $i => $value) {
           if(!empty($this->data['country_id'])){
            $this->data['zones'] = $this->model_localisation_zone->getZonesByCountryId($this->data['country_id']);
           }
           if(!empty($this->data['zone_id'])){
		    $this->data['cities'] = $this->model_localisation_city->getCitiesByZoneId($this->data['zone_id']);
           }
        //}


		$this->template = 'sale/contact_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

    private function validateFormContact() {
    	if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
            $this->error['address_firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
            $this->error['address_lastname']= $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
            $this->error['address_address_1'][$key] = $this->language->get('error_address_1');
        }



        if ($this->request->post['country_id'] == '') {
            $this->error['address_country']= $this->language->get('error_country');
        }

        if ($this->request->post['zone_id'] == '') {
            $this->error['address_zone'] = $this->language->get('error_zone');
        }

        // tokocepat
        if ($this->request->post['city_id'] == '') {
            $this->error['address_city']= $this->language->get('error_city');
        }



		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

		public function documents() {
	    	$this->load->language('catalog/product');

	    	$this->document->setTitle("Dokumen Customer");

				$this->load->model('sale/customer');

	    	if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
				$this->model_sale_customer->addDocuments($this->request->get['customer_id'], $this->request->post['product_image']);

			$this->session->data['success'] = 'Data dokumen customer berhasil diperbarui';

			$url = '';
	            if (isset($this->request->get['customer_id'])) {
					$url .= '&customer_id=' . $this->request->get['customer_id'];
				}
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['filter_provinsi'])) {
					$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
				}

				if (isset($this->request->get['filter_tgllahir'])) {
					$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
				}


				if (isset($this->request->get['filter_customer_group'])) {
					$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
				}

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

			$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			$url = '';
            if (isset($this->request->get['customer_id'])) {
				$url .= '&customer_id=' . $this->request->get['customer_id'];
			}
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}



			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

    		if(!isset($this->request->get['customer_id'])){
				$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				if(empty($this->request->get['customer_id'])){
					$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}else{
					$this->data['token'] = $this->session->data['token'];

						$this->load->model('tool/image');

						$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 40, 40);

						$this->data['product_images']= array();
						$product_images=$this->model_sale_customer->getDocuments($this->request->get['customer_id']);

						foreach ($product_images as $product_image) {
							if ($product_image['image'] && file_exists(DIR_IMAGE . $product_image['image'])) {
								$image = $product_image['image'];
							} else {
								$image = 'no_image.jpg';
							}

							$this->data['product_images'][] = array(
								'product_image_id'	=> $product_image['product_image_id'],
								'image'      => $image,
								'thumb'      => $this->model_tool_image->resize($image, 100, 100),
								'imageorigin'      => HTTP_IMAGE.$image,
								'name' => $product_image['name']
							);
						}


				  	$this->data['action'] = $this->url->link('sale/customer/documents', 'token=' . $this->session->data['token'].'&product_id='.$this->request->get['product_id'] . $url, 'SSL');
						$this->data['cancel'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL');

						$this->template = 'sale/customer_document.tpl';
						$this->children = array(
							'common/header',
							'common/footer'
						);

						$this->response->setOutput($this->render());
				}
			}
  	}

		public function hapusdocument(){
			//$product_option_id=$this->request->get['product_option_id'];
			$product_image_id=$this->request->get['product_image_id'];

			$this->load->model('sale/customer');
			$this->model_sale_customer->deleteDocuments($product_image_id);

			echo 1;
		}

		public function deposit() {
	        $this->load->language('sale/customer');

			$this->document->setTitle("Deposit Customer");

			$this->load->model('sale/customer');
	    	if (isset($this->request->get['customer_id'])) {
				if(empty($this->request->get['customer_id'])){
					$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}else{
					$customer_id = $this->request->get['customer_id'];
				}
			} else {
				$this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}


	        if (isset($this->request->get['pagealamat'])) {
				$pagealamat = $this->request->get['pagealamat'];
			} else {
				$pagealamat = 1;
			}

			$url = '';
	    	if (isset($this->request->get['customer_id'])) {
				$url .= '&customer_id=' .$this->request->get['customer_id'];
			}


			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_provinsi'])) {
				$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
			}

			if (isset($this->request->get['filter_customer_group'])) {
				$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
			}

			if (isset($this->request->get['filter_tgllahir'])) {
				$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
	        if (isset($this->request->get['pagealamat'])) {
				$url .= '&pagealamat=' . $this->request->get['pagealamat'];
			}


			$this->data['cancel'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['insert'] = $this->url->link('keuangan/penerimaandana/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['penyesuaian'] = $this->url->link('sale/customer/insertdeposit', 'token=' . $this->session->data['token'] . $url, 'SSL');

			$this->data['addresses'] = array();

			$data = array(
				'start'                    => ($pagealamat - 1) * $this->config->get('config_admin_limit'),
				'limit'                    => $this->config->get('config_admin_limit')
			);

			$address_total = $this->model_sale_customer->getTotalDeposits($this->request->get['customer_id']);

			$results = $this->model_sale_customer->getDeposits($this->request->get['customer_id'],$data);
			//print_r($results);

	    	foreach ($results as $result) {
				$action = array();
				/*if(empty($result['ref']) & $result['saldomasuk'] > 0){
					$action[] = array(
						'text' => 'Batalkan',
						'href' => $this->url->link('sale/customer/batalkandeposit', 'token=' . $this->session->data['token'] . '&id=' . $result['customer_id'], 'SSL')
					);
				}*/

				$this->data['addresses'][] = array(
					'date_trans'    => date('d/m/y',strtotime($result['date_trans'])),
					'saldomasuk'           => $this->currency->format($result['saldomasuk']),
					'saldokeluar'           => $this->currency->format($result['saldokeluar']),
					'ref'             => $result['ref'],
					'keterangan'             => $result['keterangan'],
					'selected'       => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
					'actions'	=> $action

				);
			}

			$this->data['heading_title'] = $this->language->get('heading_title');



			$this->data['token'] = $this->session->data['token'];

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
	        if (isset($this->request->get['customer_id'])) {
				$url .= '&customer_id=' .$this->request->get['customer_id'];
			}


			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_provinsi'])) {
				$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
			}

			if (isset($this->request->get['filter_tgllahir'])) {
				$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			$pagination = new Pagination();
			$pagination->total = $address_total;
			$pagination->page = $pagealamat;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('sale/customer/deposit', 'token=' . $this->session->data['token'] . $url . '&pagealamat={page}', 'SSL');

			$this->data['pagination'] = $pagination->render();


			$this->template = 'sale/deposit_list.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render());
	  	}

	  public function insertdeposit() {
			$this->load->language('sale/customer');

	    	$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('sale/customer');
			if (!isset($this->request->get['customer_id'])) {
	            $this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	        }
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormDeposit()) {
	      	  	$this->model_sale_customer->addDeposit($this->request->post,$this->request->get['customer_id']);

				$this->session->data['success'] = $this->language->get('text_success');

				$url = '';
	            if (isset($this->request->get['customer_id'])) {
					$url .= '&customer_id=' . $this->request->get['customer_id'];
				}
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['filter_provinsi'])) {
					$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
				}

				if (isset($this->request->get['filter_customer_group'])) {
					$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
				}

				if (isset($this->request->get['filter_tgllahir'])) {
					$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
				}

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}


				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

	            if (isset($this->request->get['pagealamat'])) {
					$url .= '&pagealamat=' . $this->request->get['pagealamat'];
				}

				$this->redirect($this->url->link('sale/customer/deposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}

	    	$this->getFormDeposit();
	  	}



	  	public function deletedeposit() {
			$this->load->language('sale/customer');

	    	$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('sale/customer');

	        if (!isset($this->request->get['customer_id'])) {
	            $this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	        }

	    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
				foreach ($this->request->post['selected'] as $address_id) {
					$this->model_sale_customer->deleteContact($address_id);
				}

				$this->session->data['success'] = $this->language->get('text_success');

				$url = '';
	            if (isset($this->request->get['customer_id'])) {
					$url .= '&customer_id=' . $this->request->get['customer_id'];
				}
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}
				if (isset($this->request->get['filter_provinsi'])) {
					$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
				}

				if (isset($this->request->get['filter_customer_group'])) {
					$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
				}

				if (isset($this->request->get['filter_tgllahir'])) {
					$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
				}


				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

	            if (isset($this->request->get['pagealamat'])) {
					$url .= '&pagealamat=' . $this->request->get['pagealamat'];
				}

				$this->redirect($this->url->link('sale/customer/contact', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	    	}

	    	$this->getFormContact();
	  	}

	    private function getFormDeposit() {
	    	$this->data['heading_title'] = $this->language->get('heading_title');


			$this->data['token'] = $this->session->data['token'];

			if (isset($this->request->get['customer_id'])) {
				$this->data['customer_id'] = $this->request->get['customer_id'];
			} else {
				$this->data['customer_id'] = 0;
			}

	 		if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
			} else {
				$this->data['error_warning'] = '';
			}






			$url = '';
	            if (isset($this->request->get['customer_id'])) {
					$url .= '&customer_id=' . $this->request->get['customer_id'];
				}
				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['filter_provinsi'])) {
					$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
				}

				if (isset($this->request->get['filter_customer_group'])) {
					$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
				}

				if (isset($this->request->get['filter_tgllahir'])) {
					$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
				}

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
			if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

	            if (isset($this->request->get['pagealamat'])) {
					$url .= '&pagealamat=' . $this->request->get['pagealamat'];
				}

	  		$this->data['action'] = $this->url->link('sale/customer/insertdeposit', 'token=' . $this->session->data['token'] . $url, 'SSL');


	    	$this->data['cancel'] = $this->url->link('sale/customer/deposit', 'token=' . $this->session->data['token'] . $url, 'SSL');




			$this->load->model('keuangan/bank');

			$this->data['banks'] = $this->model_keuangan_bank->getBanks(array(),array(),array('currency'	=> 1),array(),0,null);



			$this->template = 'sale/deposit_form.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render());
		}

		// public function autocomplete() {
		// 	$json = array();

		// 	$this->load->model('user/user');

		// 		if (isset($this->request->get['q'])) {
		// 			$filter_name = $this->request->get['q'];
		// 		} else {
		// 			$filter_name = null;
		// 		}

		// 		if (isset($this->request->get['j'])) {
		// 			$filter_jabatan = $this->request->get['j'];

		// 		} else {
		// 			$filter_jabatan = null;
		// 		}

		// 		if (isset($this->request->get['filter_statuspegawai'])) {
		// 			$filter_statuspegawai = $this->request->get['filter_statuspegawai'];
		// 		} else {
		// 			$filter_statuspegawai = '';
		// 		}

		// 		if (isset($this->request->get['limit'])) {
		// 			$limit = $this->request->get['limit'];
		// 		} else {
		// 			$limit = 20;
		// 		}


		// 		$data = array(
		// 		'filter_name'	  => $filter_name,
		// 		'filter_jabatan'	=> $filter_jabatan,
		// 		'start'	=>0,
		// 		'limit'	=> 10,
		// 		'filter_statuspegawai'	=> $filter_statuspegawai
		// 			//'start'               => 0,
		// 			//'limit'               => $limit
		// 		);

		// 		$results = $this->model_user_user->getUsers($data);

		// 		foreach ($results as $result) {
		// 			$json[] = array(
		// 				'id' => $result['user_id'],
		// 				'text'       => strip_tags(html_entity_decode($result['firstname'], ENT_QUOTES, 'UTF-8')),

		// 			);
		// 		}


		// 	$this->response->setOutput(json_encode($json));
		// }		

	   private function validateFormDeposit() {
				if ($this->error && !isset($this->error['warning'])) {
					$this->error['warning'] = $this->language->get('error_warning');
				}

				if (!$this->error) {
			  		return true;
				} else {
			  		return false;
				}
	  	}

			public function insertpiutang() {
				$this->load->language('sale/customer');

		    	$this->document->setTitle($this->language->get('heading_title'));

				$this->load->model('sale/customer');
				if (!isset($this->request->get['customer_id'])) {
		            $this->redirect($this->url->link('sale/customer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		        }
				if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormDeposit()) {
		      	  	$this->model_sale_customer->addDeposit($this->request->post,$this->request->get['customer_id']);

					$this->session->data['success'] = $this->language->get('text_success');

					$url = '';
		            if (isset($this->request->get['customer_id'])) {
						$url .= '&customer_id=' . $this->request->get['customer_id'];
					}
					if (isset($this->request->get['filter_name'])) {
						$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->get['filter_provinsi'])) {
						$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
					}

					if (isset($this->request->get['filter_customer_group'])) {
						$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
					}

					if (isset($this->request->get['filter_tgllahir'])) {
						$url .= '&filter_tgllahir=' . $this->request->get['filter_tgllahir'];
					}

					if (isset($this->request->get['sort'])) {
						$url .= '&sort=' . $this->request->get['sort'];
					}

					if (isset($this->request->get['order'])) {
						$url .= '&order=' . $this->request->get['order'];
					}
					if (isset($this->request->get['page'])) {
						$url .= '&page=' . $this->request->get['page'];
					}

		            if (isset($this->request->get['pagealamat'])) {
						$url .= '&pagealamat=' . $this->request->get['pagealamat'];
					}

					$this->redirect($this->url->link('sale/customer/deposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}

		    	$this->getFormDeposit();
		  	}


}
?>
