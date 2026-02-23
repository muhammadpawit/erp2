<?php
class ControllerCatalogProduct extends Controller {
	private $error = array();
	// baru 18 November 2019
	public function getso(){
		$this->load->model('catalog/product');
		$id = $this->request->get['id'];
		$so = $this->model_catalog_product->getso($this->request->get['id']);
		echo '
		<table class="table">
	        <tr>
	          <th>Gudang</th>
			  <th>No.Sales Order</th>
	          <th>Customer</th>
	          <th>Quantity</th>
	        </tr>';
			if(!empty($so)){
				foreach($so as $s){
					$namacust =$this->model_catalog_product->getcustomer($s['customer_id']);
					$gudang =$this->model_catalog_product->getgudang($s['gudang_id']);
					echo '<tr>';
					echo '<td>'.$gudang['nama'].'</td>';
					echo '<td>'.$s['no_so'].'</td>';
					echo '<td>'.$namacust['name'].'</td>';
					echo '<td>'.$s['quantity'].'</td>';
					echo '</tr>';
				}
			}
	    echo '
	    </table>
		';
	}
	// end baru	
	// baru 23 September 2019
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
		$cell_definition = array(
			'A' => 'Produk ID',
			'B' => 'Nama Produk',
			'C' => 'Barcode',
			'D' => 'Quantity'
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
		$fileName = "Daftar_Persediaan_Produk_".$presentDate.".xls";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		die();
	}
	
	public function exporttoexcel(){
		//echo "<pre>";print_r($this->cetakexcel());
		$this->xlscreation_direct();
	}
	
	public function cetakexcel() {
		$this->load->model('catalog/product');
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$filter_category_id = null;
		}



		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$filter_urutkan = $this->request->get['filter_urutkan'];
		} else {
			$filter_urutkan = '3';
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

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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

		$this->data['insert'] = $this->url->link('catalog/product/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['copy'] = $this->url->link('catalog/product/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/product/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['exporttoexcel'] = $this->url->link('catalog/product/exporttoexcel', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['products'] = array();
		$this->load->model('catalog/satuan');

		$data = array(
			'filter_name'	  => $filter_name,
			'filter_category_id'	=> $filter_category_id,
			'filter_status'   => $filter_status,
			'filter_urutkan'   => $filter_urutkan,
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);


		$product_total = $this->model_catalog_product->getTotalProducts($data);

		$results = $this->model_catalog_product->getProducts($data);

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),9);


		foreach ($results as $result) {
			$action = array();
    	$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
			/*$action[] = array(
				'text' => 'Kartu Stok',
				'href' => $this->url->link('catalog/product/kartustok', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);*/
			//if($result['quantity'] == 0){
			if($custdata){
				$action[] = array(
					'text' => 'Input Stok Awal',
					'href' => $this->url->link('catalog/productgudang/stokAwal', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
				);
			}
			//}
			$action[] = array(
				'text' => 'Kategori',
				'href' => $this->url->link('catalog/product/kategori', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
			$action[] = array(
				'text' => 'Bahan Baku',
				'href' => $this->url->link('catalog/product/bahanbaku', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
			/*$action[] = array(
				'text' => 'History HPP',
				'href' => $this->url->link('catalog/product/hpp', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
			$action[] = array(
				'text' => 'Stok Opname',
				'href' => $this->url->link('catalog/product/stokopname', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);*/

      $this->data['products'][] = array(
				'Produk ID' => $result['product_id'],
				//'stok' => $this->model_gudang_product->cekStok($result['product_id']),
				'Nama Produk'       => $result['name'],
				'Barcode'       => $result['barcode'],
				'Quantity'   => $result['quantity']." ".$this->model_catalog_satuan->getTitle($result['satuan']),
				'Satuan'	=> $this->model_catalog_satuan->getTitle($result['satuan']),
				//'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
				//'action'     => $action
			);
    	}


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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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

		return $this->data['products'];
	}	
	// end baru
  	public function index() {
		$this->load->language('catalog/product');

		$this->document->setTitle('Master Data Produk');

		$this->load->model('catalog/product');

		$this->getList();
  	}

  	public function insert() {
    	$this->load->language('catalog/product');

    	$this->document->setTitle('Master Data Produk');

			$this->load->model('catalog/product');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_product->addProduct($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('catalog/product');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_product->editProduct($this->request->get['product_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

    	$this->getForm();
  	}

		public function kategori() {
    	$this->load->language('catalog/product');

    	$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('catalog/product');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_catalog_product->editKategori($this->request->get['product_id'], $this->request->post['product_category']);

			$this->session->data['success'] = 'Data kategori produk berhasil diperbarui.';

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

    	if(!isset($this->request->get['product_id'])){
				$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				if(empty($this->request->get['product_id'])){
					$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}else{
					$this->data['token'] = $this->session->data['token'];
					$this->load->model('catalog/category');

						$this->data['categories'] = $this->model_catalog_category->getAllCategories(0);

						if (isset($this->request->post['product_category'])) {
							$this->data['product_category'] = $this->request->post['product_category'];
						} elseif (isset($this->request->get['product_id'])) {
							$this->data['product_category'] = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);
						} else {
							$this->data['product_category'] = array();
						}


				  	$this->data['action'] = $this->url->link('catalog/product/kategori', 'token=' . $this->session->data['token'].'&product_id='.$this->request->get['product_id'] . $url, 'SSL');
						$this->data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

						$this->template = 'catalog/product_category.tpl';
						$this->children = array(
							'common/header',
							'common/footer'
						);

						$this->response->setOutput($this->render());
				}
			}
  	}
		public function hapusoption(){
			$product_option_id=$this->request->get['product_option_id'];
			$product_id=$this->request->get['product_id'];

			$this->load->model('catalog/product');
			$d=$this->model_catalog_product->deleteOption($product_option_id);

			if(!$d){
				$json[error]='Quantity produk tidak sama dengan 0';
				echo json_encode($json);
			}else{
				echo 1;
			}
		}
		public function option() {
    	$this->load->language('catalog/product');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_catalog_product->addOptions($this->request->get['product_id'], $this->request->post['product_options']);

			$this->session->data['success'] = 'Data ukuran/warna produk berhasil diperbarui';

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

    	if(!isset($this->request->get['product_id'])){
				$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				if(empty($this->request->get['product_id'])){
					$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}else{
					$this->data['token'] = $this->session->data['token'];
					$this->load->model('catalog/options');

						$this->data['options'] = $this->model_catalog_options->getOptions();

						if (isset($this->request->post['product_options'])) {
							$this->data['product_options'] = $this->request->post['product_options'];
						} elseif (isset($this->request->get['product_id'])) {
							$this->data['product_options'] = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
						} else {
							$this->data['product_options'] = array();
						}


				  	$this->data['action'] = $this->url->link('catalog/product/option', 'token=' . $this->session->data['token'].'&product_id='.$this->request->get['product_id'] . $url, 'SSL');
						$this->data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

						$this->template = 'catalog/product_options.tpl';
						$this->children = array(
							'common/header',
							'common/footer'
						);

						$this->response->setOutput($this->render());
				}
			}
  	}

		public function diskon() {
    	$this->load->language('catalog/product');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_catalog_product->addProductSpecial($this->request->get['product_id'], $this->request->post['product_special']);

			$this->session->data['success'] = 'Data diskon produk berhasil diperbarui';

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

    	if(!isset($this->request->get['product_id'])){
				$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				if(empty($this->request->get['product_id'])){
					$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}else{
					$this->data['token'] = $this->session->data['token'];
					$this->load->model('sale/customer_group');

					$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

						//$this->data['options'] = $this->model_catalog_options->getOptions();

						if (isset($this->request->post['product_specials'])) {
							$this->data['product_specials'] = $this->request->post['product_specials'];
						} elseif (isset($this->request->get['product_id'])) {
							$this->data['product_specials'] = $this->model_catalog_product->getProductSpecials($this->request->get['product_id']);
						} else {
							$this->data['product_specials'] = array();
						}


				  	$this->data['action'] = $this->url->link('catalog/product/diskon', 'token=' . $this->session->data['token'].'&product_id='.$this->request->get['product_id'] . $url, 'SSL');
						$this->data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

						$this->template = 'catalog/product_special.tpl';
						$this->children = array(
							'common/header',
							'common/footer'
						);

						$this->response->setOutput($this->render());
				}
			}
  	}

		public function hapusspecial(){
			//$product_option_id=$this->request->get['product_option_id'];
			$product_special_id=$this->request->get['product_special_id'];

			$this->load->model('catalog/product');
			$this->model_catalog_product->deleteProductSpecial($product_special_id);

			echo 1;
		}

		public function images() {
    	$this->load->language('catalog/product');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_catalog_product->addImages($this->request->get['product_id'], $this->request->post['product_image']);

			$this->session->data['success'] = 'Data image produk berhasil diperbarui';

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

    	if(!isset($this->request->get['product_id'])){
				$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				if(empty($this->request->get['product_id'])){
					$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}else{
					$this->data['token'] = $this->session->data['token'];
						//$this->data['options'] = $this->model_catalog_options->getOptions();

						/*if (isset($this->request->post['product_images'])) {
							$this->data['product_images'] = $this->request->post['product_images'];
						} elseif (isset($this->request->get['product_id'])) {
							$this->data['product_images'] = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
						} else {
							$this->data['product_images'] = array();
						}*/

						$this->load->model('tool/image');

						$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 40, 40);

						$this->data['product_images']= array();
						$product_images=$this->model_catalog_product->getProductImages($this->request->get['product_id']);

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
								'sort_order' => $product_image['sort_order']
							);
						}


				  	$this->data['action'] = $this->url->link('catalog/product/images', 'token=' . $this->session->data['token'].'&product_id='.$this->request->get['product_id'] . $url, 'SSL');
						$this->data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

						$this->template = 'catalog/product_image.tpl';
						$this->children = array(
							'common/header',
							'common/footer'
						);

						$this->response->setOutput($this->render());
				}
			}
  	}

		public function hapusimage(){
			//$product_option_id=$this->request->get['product_option_id'];
			$product_image_id=$this->request->get['product_image_id'];

			$this->load->model('catalog/product');
			$this->model_catalog_product->deleteProductImages($product_image_id);

			echo 1;
		}

  	public function delete() {
    	$this->load->language('catalog/product');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->deleteProduct($product_id);
	  		}

			$this->session->data['success'] = $this->language->get('text_success');

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

    	$this->getList();
  	}



  	private function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$filter_category_id = null;
		}



		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_urutkan'])) {
			$filter_urutkan = $this->request->get['filter_urutkan'];
		} else {
			$filter_urutkan = '3';
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

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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



		$this->data['insert'] = $this->url->link('catalog/product/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['copy'] = $this->url->link('catalog/product/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/product/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['exporttoexcel'] = $this->url->link('catalog/product/exporttoexcel', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['products'] = array();
		$this->load->model('catalog/satuan');

		$data = array(
			'filter_name'	  => $filter_name,
			'filter_category_id'	=> $filter_category_id,
			'filter_status'   => $filter_status,
			'filter_urutkan'   => $filter_urutkan,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);


		$product_total = $this->model_catalog_product->getTotalProducts($data);

		$results = $this->model_catalog_product->getProducts($data);

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),9);

		$freestok=0;
		foreach ($results as $result) {
			
			$freestok = $this->model_catalog_product->sumso($result['product_id']);
			if($this->user->getUsername()=="pawits"){
				echo $this->data['freestok'];exit;
			}
			$action = array();
    	$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
			/*$action[] = array(
				'text' => 'Kartu Stok',
				'href' => $this->url->link('catalog/product/kartustok', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);*/
			//if($result['quantity'] == 0){
			if($custdata){
				$action[] = array(
					'text' => 'Input Stok Awal',
					'href' => $this->url->link('catalog/productgudang/stokAwal', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
				);
			}
			//}
			$action[] = array(
				'text' => 'Kategori',
				'href' => $this->url->link('catalog/product/kategori', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
			$action[] = array(
				'text' => 'Bahan Baku',
				'href' => $this->url->link('catalog/product/bahanbaku', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
			/*$action[] = array(
				'text' => 'History HPP',
				'href' => $this->url->link('catalog/product/hpp', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
			$action[] = array(
				'text' => 'Stok Opname',
				'href' => $this->url->link('catalog/product/stokopname', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);*/

      $this->data['products'][] = array(
				'product_id' => $result['product_id'],
				//'stok' => $this->model_gudang_product->cekStok($result['product_id']),
				'name'       => $result['name'],
				'barcode'       => $result['barcode'],
				'quantity'   => $result['quantity'],
				'freestok'  => $freestok,
				'satuan'	=> $this->model_catalog_satuan->getTitle($result['satuan']),
				'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
				'action'     => $action
			);
    	}


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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_category_id'] = $filter_category_id;
		$this->data['filter_urutkan'] = $filter_urutkan;
		$this->data['filter_status'] = $filter_status;

			$this->load->model('catalog/category');

		$this->data['categories']=$this->model_catalog_category->getAllCategories();

		$this->template = 'catalog/product_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  private function getForm() {


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

  		if (!isset($this->request->get['product_id'])) {
			$this->data['action'] = $this->url->link('catalog/product/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
    	}

		$this->data['token'] = $this->session->data['token'];



		if (isset($this->request->post['name'])) {
      		$this->data['name'] = $this->request->post['name'];
    	} elseif (!empty($product_info)) {
			$this->data['name'] = $product_info['name'];
		} else {
      		$this->data['name'] = '';
    	}

		if (isset($this->request->post['barcode'])) {
      		$this->data['barcode'] = $this->request->post['barcode'];
    	} elseif (!empty($product_info)) {
			$this->data['barcode'] = $product_info['barcode'];
		} else {
      		$this->data['barcode'] = '';
    	}


		if (isset($this->request->post['quantity'])) {
      		$this->data['quantity'] = $this->request->post['quantity'];
    	} elseif (!empty($product_info)) {
			$this->data['quantity'] = $product_info['quantity'];
		} else {
      		$this->data['quantity'] = 0;
    	}

			if (isset($this->request->post['satuan'])) {
						$this->data['satuan'] = $this->request->post['satuan'];
				} elseif (!empty($product_info)) {
				$this->data['satuan'] = empty($product_info['satuan'])?1:$product_info['satuan'];
			} else {
						$this->data['satuan'] = 1;
				}



		/*if (isset($this->request->post['sub_kategori'])) {
			$this->data['sub_kategori'] = $this->request->post['sub_kategori'];
		} elseif (!empty($product_info)) {
			$this->data['sub_kategori'] = $product_info['sub_kategori'];
		} else {
			$this->data['sub_kategori'] = '';
		}*/


			if (isset($this->request->post['status'])) {
      		$this->data['status'] = $this->request->post['status'];
    	} elseif (!empty($product_info)) {
			$this->data['status'] = $product_info['status'];
		} else {
      		$this->data['status'] = 0;
    	}

			if (isset($this->request->post['jenistabung'])) {
      		$this->data['jenistabung'] = $this->request->post['jenistabung'];
    	} elseif (!empty($product_info)) {
			$this->data['jenistabung'] = $product_info['jenistabung'];
		} else {
      		$this->data['jenistabung'] = 0;
    	}

		/*if (isset($this->request->post['kategori'])) {
      		$this->data['kategori'] = $this->request->post['kategori'];
    	} elseif (!empty($product_info)) {
			$this->data['kategori'] = $product_info['kategori'];
		} else {
      		$this->data['kategori'] = 0;
		}*/
		if (isset($this->request->post['ukuran_tabung'])) {
			$this->data['ukuran_tabung'] = $this->request->post['ukuran_tabung'];
	  } elseif (!empty($product_info)) {
		  $this->data['ukuran_tabung'] = $product_info['ukuran_tabung'];
	  } else {
			$this->data['ukuran_tabung'] = 0;
	  }

			$this->load->model('catalog/satuan');

		$this->data['satuans']=$this->model_catalog_satuan->getOptions();

		$this->load->model('catalog/options');
		$this->data['ukurans'] = $this->model_catalog_options->getOptions();

		$this->template = 'catalog/product_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = 'Anda tidak diijinkan untuk memodifikasi menu produk.';
    	}

			if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 255)) {
				$this->error['name'] = 'Nama produk tidak boleh dikosongkan.';
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
    	if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

  	private function validateCopy() {
			if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = 'Anda tidak diijinkan untuk memodifikasi menu produk.';
    	}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) ) {
			$this->load->model('catalog/product');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = null;
			}

			if (isset($this->request->get['filter_category_id'])) {
				$filter_category_id = $this->request->get['filter_category_id'];
			} else {
				$filter_category_id = null;
			}



			if (isset($this->request->get['filter_status'])) {
				$filter_status = $this->request->get['filter_status'];
			} else {
				$filter_status = null;
			}

	    if (isset($this->request->get['filter_urutkan'])) {
				$filter_urutkan = $this->request->get['filter_urutkan'];
			} else {
				$filter_urutkan = '3';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'filter_name'	  => $filter_name,
				'filter_category_id'	=> $filter_category_id,
				'filter_status'   => $filter_status,
				'filter_urutkan'   => $filter_urutkan,
				'start'               => 0,
				'limit'               => $limit
			);

			$results = $this->model_catalog_product->getProducts($data);

			foreach ($results as $result) {
				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function autocompleteprod() {
		$json = array();

		$this->load->model('catalog/product');

			if (isset($this->request->get['q'])) {
				$filter_name = $this->request->get['q'];
			} else {
				$filter_name = null;
			}

			if (isset($this->request->get['customer_group_id'])) {
				$customer_group_id = $this->request->get['customer_group_id'];
			} else {
				$customer_group_id = null;
			}

			if (isset($this->request->get['kategori'])) {
				$kategori = $this->request->get['kategori'];

			} else {
				$kategori = null;
			}
			if (isset($this->request->get['statustabung'])) {
				$statustabung = $this->request->get['statustabung'];
			} else {
				$statustabung = null;
			}



			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
			'filter_name'	  => $filter_name,
			'filter_category_id'	=> $kategori,
			'jenistabung'	=> $statustabung,
				'start'               => 0,
				'limit'               => $limit
			);
			$offset=0;
			$limit=$limit;

			$results = $this->model_catalog_product->getProducts($data);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['product_id'],
					'text' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}


		$this->response->setOutput(json_encode($json));
	}

	public function detail(){
		$hasil = array();

		$this->load->model('catalog/product');
		if(isset($this->request->get['product_id'])){
			if(!empty($this->request->get['product_id'])){
				$product_id=$this->request->get['product_id'];
				$hasil=$this->model_catalog_product->getProduct($product_id);
				$column=array();

				$diskon=0;
				if(isset($this->request->get['customer_group_id'])){
					if(!empty($this->request->get['customer_group_id'])){
						$pdiskon=$this->model_catalog_product->getProductSpecialsActiveDefault($product_id,$this->request->get['customer_group_id']);
						if($pdiskon){
							$diskon=$hasil['price']-$pdiskon;
						}


					}
				}

				$hasil['diskon']=$diskon;



			}
		}
		$this->response->setOutput(json_encode($hasil));


	}

	public function autocompletegudang() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_gudang_id']) ) {
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

			$results = $this->model_catalog_product->getProducts($data);

			foreach ($results as $result) {
				$option_data = array();
				//$gudangs=$this->model_catalog_product->getProductGudangs($result['product_id']);
				$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

				foreach ($product_options as $product_option) {

						$option_value_data = array();

						$option_data[] = array(
							'product_option_id' => $product_option['product_option_id'],
							'product_options_id'         => $product_option['product_options_id'],
							'name'                    => $product_option['name'],
							//'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
							'qty'				=> $product_option['quantity'],
						);

				}

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'gudang_id'	=> $filter_gudang_id,
					'nama_gudang'	=> $result['nama'],
					'qty'		=> $result['quantity'],
					'option'     => $option_data,
					'price'      => $result['price'],
					'net_cost'	=> $this->model_catalog_product->getNetcostGudang($result['product_id'],$filter_gudang_id)
				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function getProductOptionGudang(){
		$this->load->model('tool/image');
		$this->load->model('catalog/product');
		$gudang_id=$this->request->get['gudang_id'];
		$product_id=$this->request->get['product_id'];
		//$product_id=$this->request->get['gudang_id'];
		//$po=$this->model_catalog_product->getProductOptionGudang($this->request->get['product_id'],$g['gudang_id']);
		$options=array();
		foreach ($this->model_catalog_product->getProductOptions($product_id) as $option) {

					$option_value_data = array();

					foreach ($option['product_option_value'] as $option_value) {

						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {

							$vg=$this->model_catalog_product->getProductOptionValueGudang($option_value['product_option_value_id'],$gudang_id);
							if($vg){
								$option_value_data[] = array(
									'product_option_value_id' => $option_value['product_option_value_id'],
									'option_value_id'         => $option_value['option_value_id'],
									'name'                    => $option_value['name'],
									//'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
									'qty'				=> $vg['qty']
								);
							}
						}
					}

					$options = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option_value_data,
						'required'          => $option['required'],
						'product_id'		=> $product_id,
						'gudang_id'		=> $gudang_id
					);

			}
		$this->response->setOutput(json_encode($options));
	}

	public function getProductOptionGudang2($gudang_id,$product_id){
		$this->load->model('tool/image');
		$this->load->model('catalog/product');

		$options=array();
		foreach ($this->model_catalog_product->getProductOptions($product_id) as $option) {

					$option_value_data = array();

					foreach ($option['product_option_value'] as $option_value) {

						if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {

							$vg=$this->model_catalog_product->getProductOptionValueGudang($option_value['product_option_value_id'],$gudang_id);
							if($vg){
								$option_value_data[] = array(
									'product_option_value_id' => $option_value['product_option_value_id'],
									'option_value_id'         => $option_value['option_value_id'],
									'name'                    => $option_value['name'],
									//'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
									'qty'				=> $vg['qty']
								);
							}
						}
					}

					$options = array(
						'product_option_id' => $option['product_option_id'],
						'option_id'         => $option['option_id'],
						'name'              => $option['name'],
						'type'              => $option['type'],
						'option_value'      => $option_value_data,
						'required'          => $option['required'],
						'product_id'		=> $product_id,
						'gudang_id'		=> $gudang_id
					);

			}
		return $options;
	}
	public function stokAwal(){
		//$this->load->model('gudang/product');
		$this->load->model('catalog/product');


		$this->document->setTitle('Input Stok Awal Produk');
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

		if (isset($this->request->get['product_id'])) {
				$product_id=$this->request->get['product_id'];
			}
		else{
			$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));

		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormStok()) {

			$this->model_catalog_product->addStokAwal($this->request->post);
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

			$this->data['action'] = $this->url->link('catalog/product/stokAwal', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');

			$this->data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->error)) {
				$this->data['error'] = $this->error;
			} else {
				$this->data['error'] = '';
			}

    	//if(empty($this->request->post)){
			$this->data['productdesc']=$this->model_catalog_product->getProduct($product_id);
			$this->data['product_id']=$product_id;


		$this->template = 'catalog/stokawal_product.tpl';
		$this->children = array(
					'common/header',
					'common/footer'
				);
			$this->response->setOutput($this->render());

	}
	public function validateFormStok(){
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
      		$this->error['warning'] = 'Anda tidak memiliki ijin untuk memodifikasi data stok produk.';
    	}
    	if(empty($this->request->post['qty'])){
    		$this->error['qty'] = 'Data quantity harus diisi';

    	}
    	if($this->request->post['qty'] < 1){
    		$this->error['qty'] = 'Data quantity harus lebih dari 0';

    	}
			if(!is_numeric($this->request->post['qty'])){
    		$this->error['qty'] = 'Data quantity harus berupa angka';

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

		$this->data['url'] = $this->url->link('catalog/product/kartustok', 'token=' . $this->session->data['token'] . $url, 'SSL');

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
			$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		$this->load->model('catalog/product');
		$this->load->model('gudang/kartustok');


		$this->data['orders'] = array();

		$data = array(
        'tanggal'     => $tanggal,
				'product_id'	=> $product_id,
				'type'	=> $type,
				'start'                  => ($pagekartu - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_gudang_kartustok->getTotalKartustoks($data);

		$results = $this->model_gudang_kartustok->getKartustoks($data);
		$this->data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->load->model('catalog/gudang');
		//print_r($results);
		$this->data['kartustoks']=array();
		foreach ($results as $result) {


      $this->data['kartustoks'][] = array(
				'tanggal'=> date('d F Y',strtotime($result['tgl'])),
				'waktu'	=> date('H:i:s',strtotime($result['tgl'])),
				'name'   => $result['product_name'],
				'stokmasuk'   => $result['stokmasuk'],
				'stokkeluar'	=> $result['stokkeluar'],
				'ket'	=> $result['ket'],
				'saldo'	=> $result['saldo'],
				'invoice'	=> $result['invoice'],
				'quantityawal'	=> $result['quantityawal'],
				'type'	=> $result['type_name']
			);

		}

		$this->data['heading_title'] = 'Kartu Stok Produk';
		$this->data['token'] = $this->session->data['token'];



		$url = '';

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

		$next=$pagekartu+1;

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $pagekartu;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/product/kartustok', 'token=' . $this->session->data['token'] . $url . '&pagekartu='.$next, 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['tanggal']=$tanggal;
		$this->data['type']=$type;
		$this->template = 'catalog/kartustok.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function hpp() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Kartu Stok Produk');

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

		$this->data['url'] = $this->url->link('catalog/product/hpp', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['pagekartu'])) {
			$url .= '&pagekartu=' . $this->request->get['pagekartu'];
		}


	if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['pagekartu'])) {
			$pagekartu = $this->request->get['pagekartu'];
		} else {
			$pagekartu = 1;
		}


   	if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->load->model('catalog/product');
		$this->data['orders'] = array();

		$data = array(
        'product_id'	=> $product_id,
				//'type'	=> $type,
				//'start'                  => ($pagekartu - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$offset = ($pagekartu - 1) * $this->config->get('config_admin_limit');
		$limit = $this->config->get('config_admin_limit');

		$order_total = $this->model_catalog_product->totalNetcosts($data);

		$results = $this->model_catalog_product->historyNetcosts(array(),array(),$data,array('id' => 'DESC'),$limit,$offset);
		$this->data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['hpps']=array();
		foreach ($results as $result) {


      $this->data['hpps'][] = array(
				'tanggal'=> date('d F Y',strtotime($result['date_added'])),
				'net_cost'	=> $this->currency->format($result['net_cost'])
			);

		}

		$this->data['heading_title'] = 'History HPP Produk';
		$this->data['token'] = $this->session->data['token'];



		$url = '';

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


		$next=$pagekartu+1;

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $pagekartu;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/product/hpp', 'token=' . $this->session->data['token'] . $url . '&pagekartu='.$next+1, 'SSL');

		$this->data['pagination'] = $pagination->render();
		$this->template = 'catalog/hpp.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function stokopname(){
		//$this->load->model('gudang/product');
		$this->load->model('catalog/product');


		$this->document->setTitle('Stok Opname Product');

		$url = '';

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
				$product_id=$this->request->get['product_id'];
			}
		else{
			$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));

		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormStok()) {

			$this->model_catalog_product->stokOpname($this->request->post);
	  	$this->session->data['success'] = 'Success: Data Stok berhasil diperbarui.';

			$url = '';

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
				$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

			$url = '';

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

			$this->data['action'] = $this->url->link('catalog/product/stokopname', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');

			$this->data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->error)) {
				$this->data['error'] = $this->error;
			} else {
				$this->data['error'] = '';
			}

    	//if(empty($this->request->post)){
			$this->data['productdesc']=$this->model_catalog_product->getProduct($product_id);
			$this->data['product_id']=$product_id;


		$this->template = 'catalog/stokopname_product2.tpl';
		$this->children = array(
					'common/header',
					'common/footer'
				);
			$this->response->setOutput($this->render());

	}
	public function bahanbaku() {
    	$this->load->language('catalog/product');

    	$this->document->setTitle("Rumus Bahan Baku Produksi");

		$this->load->model('catalog/product');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			//print_r($this->request->post['product_options']);
			$this->model_catalog_product->addBahanbaku($this->request->get['product_id'], $this->request->post['product_options']);

			$this->session->data['success'] = 'Bahab baku produksi berhasil diperbarui';

			$url = '';

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

			$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$url = '';

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

    	if(!isset($this->request->get['product_id'])){
				$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				if(empty($this->request->get['product_id'])){
					$this->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				}else{
					$this->data['token'] = $this->session->data['token'];
					$this->load->model('catalog/bahanbaku');

						$this->data['options'] = $this->model_catalog_bahanbaku->getProducts();

						if (isset($this->request->post['product_options'])) {
							$this->data['product_options'] = $this->request->post['product_options'];
						} elseif (isset($this->request->get['product_id'])) {
							$this->data['product_options'] = $this->model_catalog_product->getBahanbaku($this->request->get['product_id']);
						} else {
							$this->data['product_options'] = array();
						}

						//print_r($this->data['product_options']);
				  	$this->data['action'] = $this->url->link('catalog/product/bahanbaku', 'token=' . $this->session->data['token'].'&product_id='.$this->request->get['product_id'] . $url, 'SSL');
						$this->data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

						$this->template = 'catalog/bahanbakuproduct.tpl';
						$this->children = array(
							'common/header',
							'common/footer'
						);

						$this->response->setOutput($this->render());
				}
			}
  	}
		public function hapusbahanbaku(){
			$product_option_id=$this->request->get['product_option_id'];
			$product_id=$this->request->get['product_id'];

			$this->load->model('catalog/product');
			$d=$this->model_catalog_product->deleteBahanbaku($product_option_id);

			echo 1;
		}
}
?>
