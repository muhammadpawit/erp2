<?php
class ControllerCatalogTabungmp extends Controller {
	private $error = array();

	function xlscreation() {

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
			'A' => 'No Tabung',
			'B' => 'Ukuran Tabung',
			'C' => 'Jenis Gas',
			'D' => 'Kelompok Aset',
			'E' => 'Tanggal Pembelian',
			'F' => 'Harga Beli',
			'G' => 'Nilai',
			'H' => 'Dipinjam Oleh',
			'I' => 'Status',
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
		$fileName = "Tabung_Gas_MP_".$presentDate.".xlsx";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		die();
	}
	
	public function cetakexcel(){
		$this->load->model('catalog/tabungmp');
		if (isset($this->request->get['filter_no_tabung'])) {
			$filter_no_tabung = $this->request->get['filter_no_tabung'];
		} else {
			$filter_no_tabung = null;
		}
	
		if (isset($this->request->get['filter_kelompok_aset'])) {
			$filter_kelompok_aset = $this->request->get['filter_kelompok_aset'];
		} else {
			$filter_kelompok_aset = null;
		}
		if (isset($this->request->get['filter_pemilik'])) {
			$filter_pemilik = $this->request->get['filter_pemilik'];
		} else {
			$filter_pemilik = null;
		}
	
		if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
			$filter_product_id= null;
		}
	
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
	
		if (isset($this->request->get['filter_ukuran_tabung'])) {
			$filter_ukuran_tabung = $this->request->get['filter_ukuran_tabung'];
		} else {
			$filter_ukuran_tabung = null;
		}
	
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
	
		$url = '';
	
		if (isset($this->request->get['filter_no_tabung'])) {
			$url .= '&filter_no_tabung=' . $filter_no_tabung;
		}
	
		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
		}
	
		if (isset($this->request->get['filter_pemilik'])) {
			$url .= '&filter_pemilik=' . $filter_pemilik;
		}
	
		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $filter_product_id;
		}
	
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $filter_status;
		}
	
		if (isset($this->request->get['filter_ukuran_tabung'])) {
			$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
		}
	
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
	
	
	
		$this->data['insert'] = $this->url->link('catalog/tabungmp/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['penyesuaian'] = $this->url->link('catalog/tabungmp/penyesuaian', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/tabungmp/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
	
		$this->data['products'] = array();
	
		$data = array(
			'filter_no_tabung'	  => $filter_no_tabung,
			'filter_kelompok_aset'	=> $filter_kelompok_aset,
			'filter_status'=> $filter_status,
			'filter_ukuran_tabung'	=> $filter_ukuran_tabung,
			'filter_pemilik'	=> $filter_pemilik,
			'filter_product_id'	=> $filter_product_id,
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
	
	
		$product_total = $this->model_catalog_tabungmp->getTotalTabungs($data);
	
		$results = $this->model_catalog_tabungmp->getTabungs($data);
	
		foreach ($results as $result) {
			$action = array();
			if($result['status'] == 1){
				$status='Tersedia (Terisi)';
			}
			if($result['status'] == 2){
				$status='Tidak Tersedia';
			}
			if($result['status'] == 3){
				$status='Hilang';
			}
			if($result['status'] == 4){
				$status='Tersedia (Kosong)';
			}
			if($result['status'] == 5){
				$status='Proses Produksi/pengisian';
			}
			if($result['status'] == 6){
				$status='Dipinjam';
			}
	
			if($result['status'] == 7){
				$status='Dijual';
			}
	
			$this->data['products'][] = array(
				'id' => $result['id'],
				'No Tabung'       => $result['no_tabung'],
				'pemilik'       => $result['pemilik'],
				'Dipinjam Oleh'	=> $result['peminjam'],
				'Jenis Gas'	=> $result['namaproduct'],
				'Kelompok Aset'   => $result['name'],
				'Ukuran Tabung'	=> $result['ukuran'],
				'Harga Beli'	=> $this->currency->format($result['hargabeli']),
				'Nilai'	=> $this->currency->format($result['nilaibuku']),
				'statuss'	=> $result['status'],
				'Status'	=> $status,
				'Tanggal Pembelian'	=>$result['tglpembelian'] != '1970-01-01'?date('d/m/y',strtotime($result['tglpembelian'])):'Belum dilakukan pembelian',
				'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}

		return $this->data['products'];
	}

	public function index() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle('Persedian Tabung Milik Perusahaan');

		$this->load->model('catalog/tabungmp');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle('Persedian Tabung Milik Perusahaan');

		$this->load->model('catalog/tabungmp');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_tabungmp->addTabung($this->request->post);

			$this->session->data['success'] = 'Data tabung milik perusahaan berhasil ditambahkan';
			$url = '';

			if (isset($this->request->get['filter_no_tabung'])) {
				$url .= '&filter_no_tabung=' . $filter_no_tabung;
			}

			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
			}

			if (isset($this->request->get['filter_pemilik'])) {
				$url .= '&filter_pemilik=' . $filter_pemilik;
			}
			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $filter_product_id;
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $filter_status;
			}

			if (isset($this->request->get['filter_ukuran_tabung'])) {
				$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/tabungmp', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function penyesuaian() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Aset');

		$this->load->model('catalog/tabungmp');
		$this->model_catalog_tabungmp->penyesuaianNilai();
		$this->session->data['success'] = 'Nilai Buku Tabung Berhasil Diperbarui';

		$url = '';

		if (isset($this->request->get['filter_no_tabung'])) {
			$url .= '&filter_no_tabung=' . $filter_no_tabung;
		}

		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
		}

		if (isset($this->request->get['filter_pemilik'])) {
			$url .= '&filter_pemilik=' . $filter_pemilik;
		}
		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $filter_product_id;
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $filter_status;
		}

		if (isset($this->request->get['filter_ukuran_tabung'])) {
			$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$this->redirect($this->url->link('catalog/tabungmp', 'token=' . $this->session->data['token'].$url, 'SSL'));
	}

	public function update() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle($this->language->get('Persedian Tabung Milik Perusahaan'));

		$this->load->model('catalog/tabungmp');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//echo '<pre>';print_r( $this->request->post);exit;
			$this->model_catalog_tabungmp->editTabung($this->request->get['id'], $this->request->post);

			$this->session->data['success'] = 'Data tabung milik perusahaan berhasil diperbarui';
			$url = '';

			if (isset($this->request->get['filter_no_tabung'])) {
				$url .= '&filter_no_tabung=' . $filter_no_tabung;
			}

			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
			}

			if (isset($this->request->get['filter_pemilik'])) {
				$url .= '&filter_pemilik=' . $filter_pemilik;
			}

			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $filter_product_id;
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $filter_status;
			}

			if (isset($this->request->get['filter_ukuran_tabung'])) {
				$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/tabungmp', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function updateinfo() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle($this->language->get('Persedian Tabung Milik Perusahaan'));

		$this->load->model('catalog/tabungmp');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormAset()) {
			$this->model_catalog_tabungmp->editTabungInfo($this->request->get['id'], $this->request->post);

			$this->session->data['success'] = 'Data informasi pembelian tabung milik perusahaan berhasil diperbarui';
			$url = '';

			if (isset($this->request->get['filter_no_tabung'])) {
				$url .= '&filter_no_tabung=' . $filter_no_tabung;
			}

			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
			}

			if (isset($this->request->get['filter_pemilik'])) {
				$url .= '&filter_pemilik=' . $filter_pemilik;
			}

			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $filter_product_id;
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $filter_status;
			}

			if (isset($this->request->get['filter_ukuran_tabung'])) {
				$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/tabungmp', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getFormAset();
	}

	public function delete() {
		$this->load->language('catalog/gudang');

		$this->document->setTitle($this->language->get('Persedian Tabung Milik Perusahaan'));

		$this->load->model('catalog/tabungmp');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $gudang_id) {
				$this->model_catalog_tabungmp->deleteTabung($gudang_id);
			}

			$this->session->data['success'] = 'Data tabung milik perusahaan berhasil dihapus.';
			$url = '';

			if (isset($this->request->get['filter_no_tabung'])) {
				$url .= '&filter_no_tabung=' . $filter_no_tabung;
			}

			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
			}

			if (isset($this->request->get['filter_pemilik'])) {
				$url .= '&filter_pemilik=' . $filter_pemilik;
			}

			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $filter_product_id;
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $filter_status;
			}

			if (isset($this->request->get['filter_ukuran_tabung'])) {
				$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/tabungmp', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
	if (isset($this->request->get['filter_no_tabung'])) {
		$filter_no_tabung = $this->request->get['filter_no_tabung'];
	} else {
		$filter_no_tabung = null;
	}

	if (isset($this->request->get['filter_kelompok_aset'])) {
		$filter_kelompok_aset = $this->request->get['filter_kelompok_aset'];
	} else {
		$filter_kelompok_aset = null;
	}
	if (isset($this->request->get['filter_pemilik'])) {
		$filter_pemilik = $this->request->get['filter_pemilik'];
	} else {
		$filter_pemilik = null;
	}

	if (isset($this->request->get['filter_product_id'])) {
		$filter_product_id = $this->request->get['filter_product_id'];
	} else {
		$filter_product_id= null;
	}

	if (isset($this->request->get['filter_status'])) {
		$filter_status = $this->request->get['filter_status'];
	} else {
		$filter_status = null;
	}

	if (isset($this->request->get['filter_ukuran_tabung'])) {
		$filter_ukuran_tabung = $this->request->get['filter_ukuran_tabung'];
	} else {
		$filter_ukuran_tabung = null;
	}

	if (isset($this->request->get['page'])) {
		$page = $this->request->get['page'];
	} else {
		$page = 1;
	}

	$url = '';

	if (isset($this->request->get['filter_no_tabung'])) {
		$url .= '&filter_no_tabung=' . $filter_no_tabung;
	}

	if (isset($this->request->get['filter_kelompok_aset'])) {
		$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
	}

	if (isset($this->request->get['filter_pemilik'])) {
		$url .= '&filter_pemilik=' . $filter_pemilik;
	}

	if (isset($this->request->get['filter_product_id'])) {
		$url .= '&filter_product_id=' . $filter_product_id;
	}

	if (isset($this->request->get['filter_status'])) {
		$url .= '&filter_status=' . $filter_status;
	}

	if (isset($this->request->get['filter_ukuran_tabung'])) {
		$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
	}

	if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
	}



	$this->data['insert'] = $this->url->link('catalog/tabungmp/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
	$this->data['penyesuaian'] = $this->url->link('catalog/tabungmp/penyesuaian', 'token=' . $this->session->data['token'] . $url, 'SSL');
	$this->data['delete'] = $this->url->link('catalog/tabungmp/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
	$this->data['excel'] = $this->url->link('catalog/tabungmp/xlscreation', 'token=' . $this->session->data['token'] . $url, 'SSL');

	$this->data['products'] = array();

	$data = array(
		'filter_no_tabung'	  => $filter_no_tabung,
		'filter_kelompok_aset'	=> $filter_kelompok_aset,
		'filter_status'=> $filter_status,
		'filter_ukuran_tabung'	=> $filter_ukuran_tabung,
		'filter_pemilik'	=> $filter_pemilik,
		'filter_product_id'	=> $filter_product_id,
		'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
		'limit'           => $this->config->get('config_admin_limit')
	);


	$product_total = $this->model_catalog_tabungmp->getTotalTabungs($data);

	$results = $this->model_catalog_tabungmp->getTabungs($data);

	foreach ($results as $result) {
		$action = array();
		if($result['pemilik'] == 1){
			if($result['tglpembelian'] == '1970-01-01' & $result['hargabeli'] == 0){
				$action[] = array(
					'text' => 'Input Informasi Pembelian',
					'href' => $this->url->link('catalog/tabungmp/updateinfo', 'token=' . $this->session->data['token'] . '&id=' . $result['id'], 'SSL')
				);
			}


		}

		/*$action[] = array(
			'text' => 'Pemeliharaan',
			'href' => $this->url->link('catalog/tabungmp/pemeliharaan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'], 'SSL')
		);*/
		$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('catalog/tabungmp/update', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
			);
		//}
		$action[] = array(
			'text' => 'Kartu Stok',
			'href' => $this->url->link('catalog/tabungmp/kartustok', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
		);
		$action[] = array(
			'text' => 'Kartu Aset',
			'href' => $this->url->link('catalog/tabungmp/kartuaset', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
		);

		if($result['status'] == 1){
			$status='Tersedia (Terisi)';
		}
		if($result['status'] == 2){
			$status='Tidak Tersedia';
		}
		if($result['status'] == 3){
			$status='Hilang';
		}
		if($result['status'] == 4){
			$status='Tersedia (Kosong)';
		}
		if($result['status'] == 5){
			$status='Proses Produksi/pengisian';
		}
		if($result['status'] == 6){
			$status='Dipinjam';
		}

		if($result['status'] == 7){
			$status='Dijual';
		}
		if($result['status'] == 8){
			$status='Rusak';
		}

		$this->data['products'][] = array(
			'id' => $result['id'],
			//'stok' => $this->model_gudang_product->cekStok($result['product_id']),
			'no_tabung'       => $result['no_tabung'],
			'pemilik'       => $result['pemilik'],
			'peminjam'	=> $result['peminjam'],
			'customer'	=> $result['namaproduct'],
			'kelompok_aset'   => $result['name'],
			'ukurantabung'	=> $result['ukuran'],
			'hargabeli'	=> $this->currency->format($result['hargabeli']),
			'nilaibuku'	=> $this->currency->format($result['nilaibuku']),
			'statuss'	=> $result['status'],
			'status'	=> $status,
			'tglpembelian'	=>$result['tglpembelian'] != '1970-01-01'?date('d/m/y',strtotime($result['tglpembelian'])):'Belum dilakukan pembelian',
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

	if (isset($this->request->get['filter_no_tabung'])) {
		$url .= '&filter_no_tabung=' . $filter_no_tabung;
	}

	if (isset($this->request->get['filter_kelompok_aset'])) {
		$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
	}

	if (isset($this->request->get['filter_pemilik'])) {
		$url .= '&filter_pemilik=' . $filter_pemilik;
	}

	if (isset($this->request->get['filter_product_id'])) {
		$url .= '&filter_product_id=' . $filter_product_id;
	}

	if (isset($this->request->get['filter_status'])) {
		$url .= '&filter_status=' . $filter_status;
	}

	if (isset($this->request->get['filter_ukuran_tabung'])) {
		$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
	}


	$pagination = new Pagination();
	$pagination->total = $product_total;
	$pagination->page = $page;
	$pagination->limit = $this->config->get('config_admin_limit');
	$pagination->text = $this->language->get('text_pagination');
	$pagination->url = $this->url->link('catalog/tabungmp', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

	$this->data['pagination'] = $pagination->render();

	$this->load->model('catalog/options');
	$this->data['ukurans'] = $this->model_catalog_options->getOptions();
	$this->load->model('catalog/kelompokaset');
	$this->data['asets'] = $this->model_catalog_kelompokaset->getKelompokasets();

	$this->data['filter_no_tabung'] = $filter_no_tabung;
	$this->data['filter_kelompok_aset'] = $filter_kelompok_aset;
	$this->data['filter_status'] = $filter_status;
	$this->data['filter_ukuran_tabung']	= $filter_ukuran_tabung;
	$this->template = 'catalog/tabungmp_list.tpl';
	$this->children = array(
		'common/header',
		'common/footer'
	);

	$this->response->setOutput($this->render());
	}

	private function getForm() {

		$url = '';

		if (isset($this->request->get['filter_no_tabung'])) {
			$url .= '&filter_no_tabung=' . $filter_no_tabung;
		}

		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
		}

		if (isset($this->request->get['filter_pemilik'])) {
			$url .= '&filter_pemilik=' . $filter_pemilik;
		}

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $filter_product_id;
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $filter_status;
		}

		if (isset($this->request->get['filter_ukuran_tabung'])) {
			$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
 		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = '';
		}

 		if (!isset($this->request->get['id'])) {
			$this->data['action'] = $this->url->link('catalog/tabungmp/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/tabungmp/update', 'token=' . $this->session->data['token'].$url . '&id=' . $this->request->get['id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/tabungmp', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				if(!empty($this->request->get['id'])){
      		$info = $this->model_catalog_tabungmp->getTabung($this->request->get['id']);
				}else{
					$this->redirect($this->url->link('catalog/tabungmp', 'token=' . $this->session->data['token'], 'SSL'));
				}
    	}

		$this->data['token'] = $this->session->data['token'];

		if($this->request->server['REQUEST_METHOD'] == 'POST'){
			$this->data['no_tabung'] = $this->request->post['no_tabung'];
			$this->data['status'] = $this->request->post['status'];
			$this->data['ukuran_tabung'] = $this->request->post['ukuran_tabung'];
			$this->data['tglpembelian'] = $this->request->post['tglpembelian'];
			$this->data['kelompok_aset'] = $this->request->post['kelompok_aset'];
			$this->data['hargabeli'] = $this->request->post['hargabeli'];
			$this->data['pemilik'] = $this->request->post['pemilik'];
			$this->data['product_id'] = $this->request->post['product_id'];
		}
		else if(isset($info)){
			$this->data['no_tabung'] = $info['no_tabung'];
			$this->data['status'] = $info['status'];
			$this->data['ukuran_tabung'] = $info['ukuran_tabung'];
			$this->data['tglpembelian'] = $info['tglpembelian'];
			$this->data['kelompok_aset'] = $info['kelompok_aset'];
			$this->data['hargabeli'] = $info['hargabeli'];
			$this->data['pemilik'] = $info['pemilik'];
			$this->data['product_id'] = $info['product_id'];
			$this->data['namaproduct'] = $info['namaproduct'];

		}else{
			$this->data['no_tabung'] = '';
			$this->data['status'] = '';
			$this->data['ukuran_tabung'] = '';
			$this->data['tglpembelian'] = '';
			$this->data['kelompok_aset'] = '';
			$this->data['hargabeli'] = '';
			$this->data['pemilik'] = 1;
			$this->data['product_id'] = 0;
		}
		//print_r($gudang_info);

		$this->load->model('catalog/options');
		$this->data['ukurans'] = $this->model_catalog_options->getOptions();
		$this->load->model('catalog/kelompokaset');
		$this->data['asets'] = $this->model_catalog_kelompokaset->getKelompokasets();
		$this->template = 'catalog/tabungmp_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		/*if (!$this->user->hasPermission('modify', 'catalog/tabungmp')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		*/
		/*if(empty($this->request->post['tglpembelian'])){
			$this->error['tglpembelian']='Tanggal pembelian harus diisi';
		}
		if(empty($this->request->post['hargabeli'])){
			$this->error['hargabeli']='Harga beli harus diisi';
		}*/
		if(empty($this->request->post['ukuran_tabung'])){
			$this->error['ukuran_tabung']='Ukuran tabung harus dipilih';
		}
		if(empty($this->request->post['kelompok_aset'])){
			$this->error['kelompok_aset']='Kelompok aset harus dipilih';
		}
		if(empty($this->request->post['product_id'])){
			$this->error['product_id']='Nama produk harus dipilih';
		}

		if(empty($this->request->post['no_tabung'])){
			$this->error['no_tabung']='Nomor tabung harus diisi';
		}else{
			$this->load->model('catalog/tabungmp');
			$cek=$this->model_catalog_tabungmp->getTabungByNomor($this->request->post['no_tabung']);
			if(!empty($cek)){
				$this->error['no_tabung']='Duplikasi nomor tabung';
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function getFormAset() {


 		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = '';
		}

 		$this->data['action'] = $this->url->link('catalog/tabungmp/updateinfo', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'], 'SSL');

		$this->data['cancel'] = $this->url->link('catalog/tabungmp', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['id'])) {
				if(!empty($this->request->get['id'])){
      		$info = $this->model_catalog_tabungmp->getTabung($this->request->get['id']);
				}else{
					$this->redirect($this->url->link('catalog/tabungmp', 'token=' . $this->session->data['token'], 'SSL'));
				}
    	}else{
				$this->redirect($this->url->link('catalog/tabungmp', 'token=' . $this->session->data['token'], 'SSL'));
			}

		$this->data['token'] = $this->session->data['token'];

		if(isset($info)){
			$this->data['no_tabung'] = $info['no_tabung'];
			$this->data['tglpembelian'] = $info['tglpembelian'];
			$this->data['hargabeli'] = $info['hargabeli'];

		}else{
			$this->data['no_tabung'] = '';
			$this->data['status'] = '';
			$this->data['ukuran_tabung'] = '';
			$this->data['tglpembelian'] = '';
			$this->data['kelompok_aset'] = '';
			$this->data['hargabeli'] = '';
		}
		//print_r($gudang_info);

		$this->template = 'catalog/tabungmpinfo_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateFormAset() {
		if(empty($this->request->post['tglpembelian'])){
			$this->error['tglpembelian']='Tanggal pembelian harus diisi';
		}
		if(!is_numeric($this->request->post['hargabeli'])){
			$this->error['hargabeli']='Harga beli harus berupa angka';
		}else{
			if($this->request->post['hargabeli'] < 0){
				$this->error['hargabeli']='Harga beli harus lebih dari 0';
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/tabungmp')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu tabung milik perusahaan';
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function autocomplete() {
		$json = array();

		//if (isset($this->request->get['filter_name']) ) {
			$this->load->model('catalog/tabungmp');

			if (isset($this->request->get['filter_no_tabung'])) {
				$filter_no_tabung = $this->request->get['filter_no_tabung'];
			} else {
				$filter_no_tabung = null;
			}

			if (isset($this->request->get['filter_name'])) {
				$filter_no_tabung = $this->request->get['filter_name'];
			} else {
				$filter_no_tabung = null;
			}

			if (isset($this->request->get['q'])) {
				$filter_no_tabung = $this->request->get['q'];
			} else {
				$filter_no_tabung = null;
			}

			if (isset($this->request->get['jenisgas'])) {
				$filter_product_id = $this->request->get['jenisgas'];
			} else {
				$filter_product_id = null;
			}

			if (isset($this->request->get['jenistabung'])) {
				$jenistabung = $this->request->get['jenistabung'];
			} else {
				$jenistabung = 1;
			}

			if (isset($this->request->get['gudang_id'])) {
				$gudang_id = $this->request->get['gudang_id'];
			} else {
				$gudang_id = 1;
			}

			if (isset($this->request->get['status'])) {
				$filter_status = $this->request->get['status'];
			} else {
				$filter_status = null;
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			if($jenistabung == 1){

				$data = array(
					'filter_no_tabung'	  => $filter_no_tabung,
					'filter_status'	  => $filter_status,
					'filter_product_id'	  => $filter_product_id,
					'filter_status'	=> $filter_status,
					'start'               => 0,
					'limit'               => $limit
				);

				$results = $this->model_catalog_tabungmp->getTabungs($data);


				foreach ($results as $result) {
					$json[] = array(
						'id' => $result['id'],
						'text'       => strip_tags(html_entity_decode($result['no_tabung'], ENT_QUOTES, 'UTF-8')),

					);
				}
			}

			if($jenistabung == 3){
				$this->load->model('catalog/tabungms');
				$data = array(
					'filter_jenisgas'	  => $filter_product_id,
					'filter_gudang_id'	  => $gudang_id,
					'start'               => 0,
					'limit'               => $limit
				);

				$results = $this->model_catalog_tabungms->getTabungs($data);


				foreach ($results as $result) {
					$json[] = array(
						'id' => $result['product_id'],
						'text'       => strip_tags(html_entity_decode($result['tabungname'], ENT_QUOTES, 'UTF-8')),

					);
				}
			}


		$this->response->setOutput(json_encode($json));
	}

	public function detail(){
		$hasil = array();

		$this->load->model('catalog/tabungmp');
		if(isset($this->request->get['product_id'])){
			if(!empty($this->request->get['product_id'])){
				$product_id=$this->request->get['product_id'];
				$hasil=$this->model_catalog_tabungmp->getTabung($product_id);


			}
		}
		$this->response->setOutput(json_encode($hasil));


	}

	public function kartustok() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Kartu Stok Tabung Gas Milik Perusahaan');

		$url = '';

		if (isset($this->request->get['filter_no_tabung'])) {
			$url .= '&filter_no_tabung=' . $filter_no_tabung;
		}

		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $filter_status;
		}

		if (isset($this->request->get['filter_ukuran_tabung'])) {
			$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['id'])) {
			$url .= '&id=' . $this->request->get['id'];
		}

		$this->data['url'] = $this->url->link('catalog/tabungmp/kartustok', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['pagekartu'])) {
			$url .= '&pagekartu=' . $this->request->get['pagekartu'];
		}


		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}



		if (isset($this->request->get['id'])) {
			$product_id = $this->request->get['id'];
		} else {
			$this->redirect($this->url->link('catalog/tabungmp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		$this->load->model('catalog/tabungmp');
		$this->load->model('catalog/kartustoktabungmp');


		$this->data['orders'] = array();

		$data = array(
        'tanggal'     => $tanggal,
				'tabung_id'	=> $product_id,
				'start'                  => ($pagekartu - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_catalog_kartustoktabungmp->getTotalKartustoks($data);
		$this->data['tabung']=$this->model_catalog_tabungmp->getTabung($product_id);

		$results = $this->model_catalog_kartustoktabungmp->getKartustoks($data);
		$this->data['cancel'] = $this->url->link('catalog/tabungmp', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['kartustoks']=array();
		foreach ($results as $result) {

			if($result['jenistransaksi'] == 3){
				$urlref=$this->url->link($result['tabel_ref'].'/tampil', 'token=' . $this->session->data['token'] . '&id='.$result['idref'].'&view=1', 'SSL');
			}else{
				$urlref=$this->url->link($result['tabel_ref'].'/tampil', 'token=' . $this->session->data['token'] . '&order_id='.$result['idref'].'&view=1', 'SSL');
			}

      $this->data['kartustoks'][] = array(
				'tglpeminjaman'=> date('d/m/y',strtotime($result['tglpeminjaman'])),
				'tglpengembalian'=> $result['tglpengembalian'] != '1901-01-01'?date('d/m/y',strtotime($result['tglpengembalian'])):'Tabung Belum Kembali',
				'tglisiulang'=> $result['tglisiulang'] != '1901-01-01'?date('d/m/y',strtotime($result['tglisiulang'])):'',
				'customer'=> $result['name'],
				'urlref'=> $urlref,
				'ket'	=> $result['ket'],
				'jenistransaksi'	=> $result['jenistransaksi'],
				'biayasewa' => $this->currency->format($result['biayasewa']),
				'invoice'	=> $result['invoice']

			);

		}

		$this->data['heading_title'] = 'Kartu Stok Tabung Gas Milik Perusahaan';
		$this->data['token'] = $this->session->data['token'];



		$url = '';

		if (isset($this->request->get['filter_no_tabung'])) {
			$url .= '&filter_no_tabung=' . $filter_no_tabung;
		}

		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $filter_status;
		}

		if (isset($this->request->get['filter_ukuran_tabung'])) {
			$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['id'])) {
			$url .= '&id=' . $this->request->get['id'];
		}

		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		$next=$pagekartu+1;

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $pagekartu;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/tabungmp/kartustok', 'token=' . $this->session->data['token'] . $url . '&pagekartu={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['tanggal']=$tanggal;
		$this->data['type']=$type;
		$this->template = 'catalog/kartustok_tabungmp.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function kartuaset() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Kartu Aset');

		$$url = '';

		if (isset($this->request->get['filter_no_tabung'])) {
			$url .= '&filter_no_tabung=' . $filter_no_tabung;
		}

		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $filter_status;
		}

		if (isset($this->request->get['filter_ukuran_tabung'])) {
			$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['id'])) {
			$url .= '&id=' . $this->request->get['id'];
		}


		$this->data['url'] = $this->url->link('catalog/tabungmp/kartuaset', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['pagekartu'])) {
			$url .= '&pagekartu=' . $this->request->get['pagekartu'];
		}


		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		if (isset($this->request->get['id'])) {
			$id = $this->request->get['id'];
		} else {
			$this->redirect($this->url->link('catalog/tabungmp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		$this->load->model('catalog/tabungmp');
		//$this->load->model('gudang/kartustok');


		$this->data['orders'] = array();

		$this->data['aset']=$this->model_catalog_tabungmp->getTabung($id);
		$this->data['aset']['name']=$this->data['aset']['no_tabung'];

		$data = array(
        'tanggal'     => $tanggal,
				'aset_id'	=> $id,
				'start'                  => ($pagekartu - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_catalog_tabungmp->getTotalKartustoks($data);

		$results = $this->model_catalog_tabungmp->getKartustoks($data);
		$this->data['cancel'] = $this->url->link('catalog/tabungmp', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['kartustoks']=array();
		foreach ($results as $result) {


      $this->data['kartustoks'][] = array(
				'tanggal'=> date('d F Y',strtotime($result['tanggal'])),
				'waktu'	=> date('H:i:s',strtotime($result['tanggal'])),
				'hargabeli'	=> $this->currency->format($result['hargabeli']),
				'penyusutan'	=> $this->currency->format($result['penyusutan']),
				'akumulasipenyusutan'	=> $this->currency->format($result['akumulasipenyusutan']),
				'nilaibuku'	=> $this->currency->format($result['nilaibuku']),
			);

		}

		$this->data['heading_title'] = 'Kartu Aset Tabung';
		$this->data['token'] = $this->session->data['token'];



		$url = '';

		if (isset($this->request->get['filter_no_tabung'])) {
			$url .= '&filter_no_tabung=' . $filter_no_tabung;
		}

		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . $filter_kelompok_aset;
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $filter_status;
		}

		if (isset($this->request->get['filter_ukuran_tabung'])) {
			$url .= '&filter_ukuran_tabung=' . $filter_ukuran_tabung;
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['id'])) {
			$url .= '&id=' . $this->request->get['id'];
		}

		if (isset($this->request->get['pagekartu'])) {
			$url .= '&pagekartu=' . $this->request->get['pagekartu'];
		}


		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		$next=$pagekartu+1;

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $pagekartu;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/tabungmp/kartuaset', 'token=' . $this->session->data['token'] . $url . '&pagekartu={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['tanggal']=$tanggal;
		$this->template = 'catalog/kartu_aktiva.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

}
?>
