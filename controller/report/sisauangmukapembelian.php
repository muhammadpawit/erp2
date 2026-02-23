<?php
class ControllerReportSisaUangmukaPembelian extends Controller {
	private $error = array();

	function exceldirect() {

		$reportdetails = $this->direct();

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
		$cell_definition = array(
			'A' => 'ID Supplier',
			'B' => 'Nama Supplier',
			'C' => 'Total',
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
		$fileName = "Laporan_sisa_uangmuka_pembelian_".$presentDate.".xlsx";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		die();
	}	
	public function excel(){
		$this->exceldirect();
	}

	public function direct(){
		$this->load->model('catalog/vendorlokal');
		
		$this->load->model('pembelian/pembayarandepositkredit');
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = null;
			}
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
	
			$url = '';
	
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
	
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
	
			$this->data['excel'] = $this->url->link('report/sisauangmukapembelian/excel', 'token=' . $this->session->data['token'] .'&excel=true'. $url, 'SSL');
	
			$this->data['vendors'] = array();
	
			$data = array(
				'name'	  => array('LIKE',$filter_name),
				//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'           => $this->config->get('config_admin_limit')
			);
			if(isset($this->request->get['excel'])){
				$offset=null;
				$limit=0;
			}else{
				$offset=($page - 1) * $this->config->get('config_admin_limit');
				$limit=$this->config->get('config_admin_limit');
			}
			
	
			$results = $this->model_catalog_vendorlokal->getVendors($data,array(),$limit,$offset);
			$product_total = $this->model_catalog_vendorlokal->totalVendors($data);
			$sisa=0;
			foreach ($results as $result) {
				$action = array();
	
			//	$cek= $this->model_catalog_vendorlokal->cekOption($result['id']);
				$sisa=$this->model_pembelian_pembayarandepositkredit->getDepositTersediasum($result['id']);
				$this->data['vendors'][] = array(
					'ID Supplier' => $result['id'],
					'Nama Supplier'        => $result['name'],
					'alamat'        => $result['alamat'],
					'telephone'        => $result['telephone'],
					'npwp'	=> $result['npwp'],
					'email'	=> $result['email'],
					'siup'	=> $result['siup'],
					'tdp'	=> $result['tdp'],
					'ho'	=> $result['ho'],
					'sppkp'	=> $result['sppkp'],
					'hutang'	=> $this->currency->format($result['hutang']),
					'deposit'	=> $this->currency->format($result['deposit']),
					'Total'	=> $this->currency->format($sisa),
					'jatuhtempo'=> date('d/m/y',strtotime($result['jatuhtempo'])),
					//'cek'		=> $cek,
					'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
					'action'      => $action
				);
			}

			return $this->data['vendors'];
	
	}
	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Vendor Lokal');

		$this->load->model('catalog/vendorlokal');
		
		$this->load->model('pembelian/pembayarandepositkredit');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Vendor Lokal');

		$this->load->model('catalog/vendorlokal');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_vendorlokal->addVendor($this->request->post);

			$this->session->data['success'] = 'Data Vendor Lokal berhasil ditambahkan.';
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('catalog/vendorlokal', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Vendor Lokal');

		$this->load->model('catalog/vendorlokal');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_vendorlokal->updateVendor($this->request->post, array('id'=>$this->request->get['id']));

			$this->session->data['success'] = 'Data Vendor Lokal berhasil diperbarui';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/vendorlokal', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Vendor Lokal');

		$this->load->model('catalog/vendorlokal');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $id) {
				$data=array('hapus'	=> 1);
				$where=array('id' => $id);
				$this->model_catalog_vendorlokal->updateVendor($data,$where);
			}

			$this->session->data['success'] = 'Data Vendor Lokal berhasil dihapus';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			$this->redirect($this->url->link('catalog/vendorlokal', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['excel'] = $this->url->link('report/sisauangmukapembelian/excel', 'token=' . $this->session->data['token'] .'&excel=true'. $url, 'SSL');

		$this->data['vendors'] = array();

		$data = array(
			'name'	  => array('LIKE',$filter_name),
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		if(isset($this->request->get['excel'])){
			$offset=null;
			$limit=0;
		}else{
			$offset=($page - 1) * $this->config->get('config_admin_limit');
			$limit=$this->config->get('config_admin_limit');
		}
		

		$results = $this->model_catalog_vendorlokal->getVendors($data,array(),$limit,$offset);
		$product_total = $this->model_catalog_vendorlokal->totalVendors($data);
		$sisa=0;
		foreach ($results as $result) {
			$action = array();

		//	$cek= $this->model_catalog_vendorlokal->cekOption($result['id']);
			$sisa=$this->model_pembelian_pembayarandepositkredit->getDepositTersediasum($result['id']);
			$this->data['vendors'][] = array(
				'id' => $result['id'],
				'name'        => $result['name'],
				'alamat'        => $result['alamat'],
				'telephone'        => $result['telephone'],
				'npwp'	=> $result['npwp'],
				'email'	=> $result['email'],
				'siup'	=> $result['siup'],
				'tdp'	=> $result['tdp'],
				'ho'	=> $result['ho'],
				'sppkp'	=> $result['sppkp'],
				'hutang'	=> $this->currency->format($result['hutang']),
				'deposit'	=> $this->currency->format($result['deposit']),
				'sisa'	=> $this->currency->format($sisa),
				'jatuhtempo'=> date('d/m/y',strtotime($result['jatuhtempo'])),
				//'cek'		=> $cek,
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
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/sisauangmukapembelian', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['token'] = $this->session->data['token'];

		if(isset($this->request->get['excel'])){
			$this->template = 'catalog/vendorlokal_list_excel.tpl';
		}else{
			$this->template = 'report/sisauangmukapembelian.tpl';
		}
		
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

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}



		if (!isset($this->request->get['id'])) {
			$this->data['action'] = $this->url->link('catalog/vendorlokal/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/vendorlokal/update', 'token=' . $this->session->data['token'].$url. '&id=' . $this->request->get['id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/vendorlokal', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$option_info = $this->model_catalog_vendorlokal->getVendor(array('id'	=> $this->request->get['id']));
    	}

		$this->data['token'] = $this->session->data['token'];


		if (!empty($this->request->post)) {
			$this->data['name'] = $this->request->post['name'];
			$this->data['alamat'] = $this->request->post['alamat'];
			$this->data['email'] = $this->request->post['email'];
			$this->data['npwp'] = $this->request->post['npwp'];
			$this->data['telephone'] = $this->request->post['telephone'];
			$this->data['siup'] = $this->request->post['siup'];
			$this->data['tdp'] = $this->request->post['tdp'];
			$this->data['ho'] = $this->request->post['ho'];
			$this->data['sppkp'] = $this->request->post['sppkp'];

		} elseif (!empty($option_info)) {
			$this->data['name'] = $option_info['name'];
			$this->data['alamat'] = $option_info['alamat'];
			$this->data['npwp'] = $option_info['npwp'];
			$this->data['email'] = $option_info['email'];
			$this->data['telephone'] = $option_info['telephone'];
			$this->data['siup'] = $option_info['siup'];
			$this->data['tdp'] = $option_info['tdp'];
			$this->data['ho'] = $option_info['ho'];
			$this->data['sppkp'] = $option_info['sppkp'];
		} else {
			$this->data['name'] = '';
			$this->data['alamat'] = '';
			$this->data['npwp'] = '';
			$this->data['email'] = '';
			$this->data['telephone'] = '';
			$this->data['siup'] ='';
			$this->data['tdp'] = '';
			$this->data['ho'] = '';
			$this->data['sppkp'] = '';
		}


		$this->template = 'catalog/vendorlokal_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		/*if (!$this->user->hasPermission('modify', 'catalog/vendorlokal')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Vendor Lokal';
		}*/

		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 255)) {
			$this->error['name'] = 'Nama vendor harus diisi.';
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
		/*if (!$this->user->hasPermission('modify', 'catalog/vendorlokal')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Vendor Lokal';
		}*/

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function autocomplete() {
		$json = array();

		$this->load->model('catalog/vendorlokal');



			if (isset($this->request->get['q'])) {
				$filter_name = $this->request->get['q'];
			} else if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = null;
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
			'name'	  => array('LIKE',$filter_name),
				//'start'               => 0,
				//'limit'               => $limit
			);
			$offset=0;
			$limit=$limit;

			$results = $this->model_catalog_vendorlokal->getVendors($data,array(),$limit,$offset);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['id'],
					'text'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}


		$this->response->setOutput(json_encode($json));
	}

	//contact

	public function contact() {
		$this->document->setTitle('Master Data Vendor Lokal');

		$this->load->model('catalog/vendorcontact');
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['filter_name_contact'])) {
			$filter_name_contact = $this->request->get['filter_name_contact'];
		} else {
			$filter_name_contact = null;
		}
		if (isset($this->request->get['pagecontact'])) {
			$pagecontact = $this->request->get['pagecontact'];
		} else {
			$pagecontact = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['cancel'] = $this->url->link('catalog/vendorlokal', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url'] = $this->url->link('catalog/vendorlokal/contact', 'token=' . $this->session->data['token'], 'SSL');
		if (isset($this->request->get['id'])) {
			$vendor_id = $this->request->get['id'];
			if(empty($vendor_id)){
				$this->redirect($this->url->link('catalog/vendorlokal', 'token=' . $this->session->data['token'].$url, 'SSL'));
			}else{
				$url .= '&id=' . $this->request->get['id'];
			}
		} else {
				$this->redirect($this->url->link('catalog/vendorlokal', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		if (isset($this->request->get['filter_name_contact'])) {
			$url .= '&filter_name_contact=' . urlencode(html_entity_decode($this->request->get['filter_name_contact'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['pagecontact'])) {
			$url .= '&pagecontact=' . $this->request->get['pagecontact'];
		}

   	$this->data['insert'] = $this->url->link('catalog/vendorlokal/insertcontact', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/vendorlokal/deletecontact', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['vendors'] = array();

		$data = array(
			'name'	  => array('LIKE',$filter_name_contact),
			'jenis'	=> 1,
			'vendor_id'	=> $vendor_id
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		$offset=($pagecontact - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$results = $this->model_catalog_vendorcontact->getVendors($data,array(),$limit,$offset);
		$product_total = $this->model_catalog_vendorcontact->totalVendors($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('catalog/vendorlokal/updatecontact', 'token=' . $this->session->data['token'].$url . '&contactid=' . $result['id'], 'SSL')
			);
			$this->data['vendors'][] = array(
				'id' => $result['id'],
				'name'        => $result['name'],
				'telephone'        => $result['telephone'],
				'email'	=> $result['email'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'	=> $action
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
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['filter_name_contact'])) {
			$url .= '&filter_name_contact=' . urlencode(html_entity_decode($this->request->get['filter_name_contact'], ENT_QUOTES, 'UTF-8'));
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $pagecontact;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/vendorlokal/contact', 'token=' . $this->session->data['token'] . $url . '&pagecontact={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'catalog/vendorcontact_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function insertcontact() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Vendor Lokal');

		$this->load->model('catalog/vendorcontact');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormContact()) {
			$this->model_catalog_vendorcontact->addVendor($this->request->post);

			$this->session->data['success'] = 'Data Kontak Vendor Lokal berhasil ditambahkan.';
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['id'])) {
				$url .= '&id=' . $this->request->get['id'];
			}
			if (isset($this->request->get['filter_name_contact'])) {
				$url .= '&filter_name_contact=' . urlencode(html_entity_decode($this->request->get['filter_name_contact'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['pagecontact'])) {
				$url .= '&pagecontact=' . $this->request->get['pagecontact'];
			}
			$this->redirect($this->url->link('catalog/vendorlokal/contact', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getFormContact();
	}

	public function updatecontact() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Vendor Lokal');

		$this->load->model('catalog/vendorcontact');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormContact()) {
			$this->model_catalog_vendorcontact->updateVendor($this->request->post, array('id'=>$this->request->get['contactid']));

			$this->session->data['success'] = 'Data Kontak Vendor Lokal berhasil diperbarui';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['id'])) {
				$url .= '&id=' . $this->request->get['id'];
			}
			if (isset($this->request->get['filter_name_contact'])) {
				$url .= '&filter_name_contact=' . urlencode(html_entity_decode($this->request->get['filter_name_contact'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['pagecontact'])) {
				$url .= '&pagecontact=' . $this->request->get['pagecontact'];
			}
			$this->redirect($this->url->link('catalog/vendorlokal/contact', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getFormContact();
	}

	public function deletecontact() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Vendor Lokal');

		$this->load->model('catalog/vendorcontact');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $id) {
				$data=array('hapus'	=> 1);
				$where=array('id' => $id);
				$this->model_catalog_vendorcontact->updateVendor($data,$where);
			}

			$this->session->data['success'] = 'Data Kontak Vendor Lokal berhasil dihapus';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['id'])) {
				$url .= '&id=' . $this->request->get['id'];
			}
			if (isset($this->request->get['filter_name_contact'])) {
				$url .= '&filter_name_contact=' . urlencode(html_entity_decode($this->request->get['filter_name_contact'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['pagecontact'])) {
				$url .= '&pagecontact=' . $this->request->get['pagecontact'];
			}
			$this->redirect($this->url->link('catalog/vendorlokal/contact', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->redirect($this->url->link('catalog/vendorlokal/contact', 'token=' . $this->session->data['token'].$url, 'SSL'));
	}
	private function getFormContact() {
	$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['id'])) {
			$vendor_id = $this->request->get['id'];
			if(empty($vendor_id)){
				$this->redirect($this->url->link('catalog/vendorlokal', 'token=' . $this->session->data['token'].$url, 'SSL'));
			}else{
				$url .= '&id=' . $this->request->get['id'];
			}
		} else {
				$this->redirect($this->url->link('catalog/vendorlokal', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}
		if (isset($this->request->get['filter_name_contact'])) {
			$url .= '&filter_name_contact=' . urlencode(html_entity_decode($this->request->get['filter_name_contact'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['pagecontact'])) {
			$url .= '&pagecontact=' . $this->request->get['pagecontact'];
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}



		if (!isset($this->request->get['contactid'])) {
			$this->data['action'] = $this->url->link('catalog/vendorlokal/insertcontact', 'token=' . $this->session->data['token'].$url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/vendorlokal/updatecontact', 'token=' . $this->session->data['token'].$url. '&contactid=' . $this->request->get['contactid'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/vendorlokal/contact', 'token=' . $this->session->data['token'].$url, 'SSL');

		if (isset($this->request->get['contactid']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$option_info = $this->model_catalog_vendorcontact->getVendor(array('id'	=> $this->request->get['contactid']));
    	}

		$this->data['token'] = $this->session->data['token'];


		if (!empty($this->request->post)) {
			$this->data['name'] = $this->request->post['name'];
			$this->data['email'] = $this->request->post['email'];
			$this->data['telephone'] = $this->request->post['telephone'];
			$this->data['vendor_id'] = $this->request->post['vendor_id'];
			$this->data['jenis'] = $this->request->post['jenis'];

		} elseif (!empty($option_info)) {
			$this->data['name'] = $option_info['name'];
			$this->data['email'] = $option_info['email'];
			$this->data['telephone'] = $option_info['telephone'];
			$this->data['vendor_id'] = $this->request->get['id'];
			$this->data['jenis'] = $option_info['jenis'];

		} else {
			$this->data['name'] = '';
			$this->data['email'] = '';
			$this->data['telephone'] = '';
			$this->data['vendor_id'] = $this->request->get['id'];
			$this->data['jenis'] = 1;
		}


		$this->template = 'catalog/vendorcontact_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateFormContact() {
		/*if (!$this->user->hasPermission('modify', 'catalog/vendorlokal')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Vendor Lokal';
		}*/

		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 255)) {
			$this->error['name'] = 'Nama vendor harus diisi.';
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

	public function deposit() {
				$this->load->language('sale/customer');

		$this->document->setTitle("Deposit Vendor");

		$this->load->model('catalog/vendorlokal');
		if (isset($this->request->get['id'])) {
			if(empty($this->request->get['id'])){
				$this->redirect($this->url->link('catalog/vendorlokal', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				$id = $this->request->get['id'];
			}
		} else {
			$this->redirect($this->url->link('catalog/vendorlokal', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}


		if (isset($this->request->get['pagealamat'])) {
			$pagealamat = $this->request->get['pagealamat'];
		} else {
			$pagealamat = 1;
		}

		$url = '';
				if (isset($this->request->get['id'])) {
			$url .= '&id=' .$this->request->get['id'];
		}


		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
				if (isset($this->request->get['pagealamat'])) {
			$url .= '&pagealamat=' . $this->request->get['pagealamat'];
		}


		$this->data['cancel'] = $this->url->link('catalog/vendorlokal', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['insert'] = $this->url->link('pembelian/pembayarandepositkredit/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['penyesuaian'] = $this->url->link('catalog/vendorlokal/insertdeposit', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['addresses'] = array();

		$data = array(
			'start'                    => ($pagealamat - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);

		$address_total = $this->model_catalog_vendorlokal->getTotalDeposits($this->request->get['id']);

		$results = $this->model_catalog_vendorlokal->getDeposits($this->request->get['id'],$data);
		//print_r($results);

		foreach ($results as $result) {
			$action = array();
			/*if(empty($result['ref']) & $result['saldomasuk'] > 0){
				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('sale/customer/batalkandeposit', 'token=' . $this->session->data['token'] . '&id=' . $result['id'], 'SSL')
				);
			}*/

			$this->data['addresses'][] = array(
				'date_trans'    => date('d/m/y',strtotime($result['date_trans'])),
				'saldomasuk'           => $this->currency->format($result['saldomasuk']),
				'saldokeluar'           => $this->currency->format($result['saldokeluar']),
				'no_dokumen'             => $result['no_dokumen'],
				'keterangan'             => $result['keterangan'],
				'urlref'	=> $this->url->link($result['urlref'].'/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['idref'], 'SSL'),
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
				if (isset($this->request->get['id'])) {
			$url .= '&id=' .$this->request->get['id'];
		}


		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

	if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$pagination = new Pagination();
		$pagination->total = $address_total;
		$pagination->page = $pagealamat;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/vendorlokal/deposit', 'token=' . $this->session->data['token'] . $url . '&pagealamat={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->template = 'catalog/depositlokal_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
		}

}
?>
