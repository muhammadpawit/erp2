<?php
class ControllerCatalogAset extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Aset');

		$this->load->model('catalog/aset');

		$this->getList();
	}

	function xlscreation() {

		$reportdetails = $this->export();

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
			'A' => 'name',
			'B' => 'jumlah',
			'C' => 'tglpembelian',
			'D' => 'hargabeli',
			'E' => 'nilaibuku',
			'F' => 'penyusutan',
			'G' => 'penyusutanbulanan',
			'H' => 'kode'
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
		$fileName = "Aset_".$presentDate.".xlsx";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		die();
	}

	public function excel(){
		$this->xlscreation();
		//echo "<pre>";print_r($this->export());exit;
	}

	public function export() {
		$this->load->model('catalog/aset');

		if (isset($this->request->get['kode'])) {
			$kode = $this->request->get['kode'];
		} else {
			$kode = null;
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		if (isset($this->request->get['filter_kelompok_aset'])) {
			$filter_kelompok_aset = $this->request->get['filter_kelompok_aset'];
		} else {
			$filter_kelompok_aset = null;
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_jenis_aktiva'])) {
			$filter_jenis_aktiva = $this->request->get['filter_jenis_aktiva'];
		} else {
			$filter_jenis_aktiva = null;
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

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'tglpembelian';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['kode'])) {
			$url .= '&kode=' . urlencode(html_entity_decode($this->request->get['kode'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_jenis_aktiva'])) {
			$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_jenis_aktiva'])) {
			$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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
		$this->data['url'] = $this->url->link('catalog/aset', 'token=' . $this->session->data['token'] . $url, 'SSL');

   		$this->data['insert'] = $this->url->link('catalog/aset/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['penyesuaian'] = $this->url->link('catalog/aset/penyesuaian', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/aset/delete', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['excel'] = $this->url->link('catalog/aset/excel', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['tasets'] = array();

		$column=array('aset.aset_id','aset.kode','aset.jumlah','aset.name as name','aset.nilaibuku','aset.penyusutan','aset.penyusutanbulanan','aset.akumulasipenyusutan','jenis_aktiva.nama as nama_aktiva','aset.tglpembelian','aset.harga as hargabeli','aset.status','kelompok_aset.name as kelompok','kelompok_aset.jenis_aset as jenis');
		$join=array();
		$join[]=array(
			'tablename'	=> 'kelompok_aset',
			'firsttable'	=> 'aset.kelompok_aset',
			'secondtable'	=> 'kelompok_aset.kelompok_aset_id',
		);
		$join[]=array(
			'tablename'	=> 'jenis_aktiva',
			'firsttable'	=> 'aset.jenis_aktiva',
			'secondtable'	=> 'jenis_aktiva.no_akun',
		);
		$data = array(
			'aset.name'	  => array('LIKE',$filter_name),
			'kelompok_aset'	=> $filter_kelompok_aset,
			'jenis_aktiva'	=> $filter_jenis_aktiva,
			'aset.hapus'	=>array('<',1),
			'aset.status'	=>$filter_status,

			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		if(!empty($filter_date_end)){
			$data['aset.tglpembelian']=array('>=',$filter_date_start,'<=',$filter_date_end);
		}else{
			$data['aset.tglpembelian']=array('>=',$filter_date_start);
		}

		if($filter_date_start == '1970-01-01'){
			$filter_date_start=null;
		}
		$orders=array();
		$orders=array($sort=>$order);
		//$offset=($page - 1) * $this->config->get('config_admin_limit');
		//$limit=$this->config->get('config_admin_limit');
		$offset=null;
		$limit=0;

		$results = $this->model_catalog_aset->getAsets($column,$join,$data,$orders,$limit,$offset);
		$product_total = $this->model_catalog_aset->totalAsets($data);
		//print_r($results);
		foreach ($results as $result) {
			$action = array();
			/*
			$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('catalog/aset/update', 'token=' . $this->session->data['token'] . '&aset_id=' . $result['aset_id'], 'SSL')
			);
			if($result['tglpembelian'] == '1970-01-01' & $result['hargabeli'] == 0){
				$action[] = array(
					'text' => 'Input Informasi Pembelian',
					'href' => $this->url->link('catalog/aset/updateinfo', 'token=' . $this->session->data['token'] . '&aset_id=' . $result['aset_id'], 'SSL')
				);
			}
			$action[] = array(
				'text' => 'Kartu Aset',
				'href' => $this->url->link('catalog/aset/kartustok', 'token=' . $this->session->data['token'] . '&aset_id=' . $result['aset_id'], 'SSL')
			);*/

			$this->data['tasets'][] = array(
				'aset_id' => $result['aset_id'],
				'kode' => ($result['kode']==null)?'':$result['kode'],
				'jumlah' => ($result['jumlah']==0)?'':$result['jumlah'],
				'name'        => strtoupper($result['name']),
				'kelompok'	=> $result['kelompok'],
				'jenis'	=> $result['jenis'],
				'nama_aktiva'	=> $result['nama_aktiva'],
				'status'	=> $result['status']==1?'Tersedia':($result['status'] == 2?'Tidak Tersedia':($result['status'] == 4?'Dijual':'Hilang')),
				'tglpembelian'	=>$result['tglpembelian'] != '1970-01-01'?date('d-m-Y',strtotime($result['tglpembelian'])):'Belum dilakukan pembelian',
				'hargabeli'	=> round($result['hargabeli']),
				'nilaibuku'	=> round($result['nilaibuku']),
				'penyusutan'	=> round($result['penyusutan']),
				'penyusutanbulanan'	=> round($result['penyusutanbulanan']),
				'akumulasipenyusutan'	=> round($result['akumulasipenyusutan']),
				//'hargabeli'=>$result['harga'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['aset_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}

		return $this->data['tasets'];

	}

	public function insert() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Aset');

		$this->load->model('catalog/aset');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if($this->user->getUsername()=="pawit"){
				echo "<pre>";print_r($this->request->post);exit;
			}
			$this->model_catalog_aset->addAset($this->request->post);

			$this->session->data['success'] = 'Data Aset berhasil ditambahkan.';
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_jenis_aktiva'])) {
				$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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
			$this->redirect($this->url->link('catalog/aset', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Aset');

		$this->load->model('catalog/aset');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if($this->user->getUsername()=="pawixt"){
				echo "<pre>";print_r($this->request->post);exit;
			}
			$this->model_catalog_aset->updateAset($this->request->post, array('aset_id'=>$this->request->get['aset_id']));

			$this->session->data['success'] = 'Data Aset berhasil diperbarui';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_jenis_aktiva'])) {
				$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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

			$this->redirect($this->url->link('catalog/aset', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function updateinfo() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Aset');

		$this->load->model('catalog/aset');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormInfo()) {

			/*$this->load->model('catalog/kelompokaset');
			$aset=$this->model_catalog_aset($this->request->get['aset_id']);
			$kel=$this->model_catalog_kelompokaset->getKelompokaset($aset['kelompok_aset']);

			$manfaat=$kel['masa_manfaat'];
			$tarif=$kel['nilai_depresiasi'];

			$penyusutantahunan=($tarif/100)*($a['hargabeli']/$manfaat);
			$penyusutanbulanan=$penyusutantahunan/12;*/

			$info=array(
				'tglpembelian'	=> $this->request->post['tglpembelian'],
				'hargabeli'	=> $this->request->post['hargabeli'],
				//'nilaibuku'	=>
				'status'	=> 1,
				'nilaibuku'	=> $this->request->post['nilaibuku'],
				//'penyusutan'	=> $penyusutantahunan,
				//'penyusutanbulanan'	=> $penyusutanbulanan,
				//'akumulasipenyusutan'	=> $this->request->post['hargabeli'] - $this->request->post['nilaibuku']
			);

			$this->model_catalog_aset->setInfoAset($info, $this->request->get['aset_id']);

			/*$kartu=array(
				'aset_id'	=> $this->request->get['aset_id'],
				'tanggal'	=> date('Y-m-d H:i:s'),
				'hargabeli'	=> $this->request->post['hargabeli'],
				'penyusutan'	=> $penyusutantahunan,
				'penyusutanbulanan'	=> $penyusutanbulanan,
				'akumulasipenyusutan'	=> $this->request->post['hargabeli'] - $this->request->post['nilaibuku'],
				'nilaibuku'	=>  $this->request->post['nilaibuku']
			);

			$this->model_catalog_aset->kartuaset($kartu);*/

			$this->session->data['success'] = 'Data Informasi pembelian berhasil ditambahkan';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_jenis_aktiva'])) {
				$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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

			$this->redirect($this->url->link('catalog/aset', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getFormAset();
	}

	public function delete() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Aset');

		$this->load->model('catalog/aset');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $id) {
				$data=array('hapus'	=> 1);
				$where=array('aset_id' => $id);
				$this->model_catalog_aset->updateAset($data,$where);
			}

			$this->session->data['success'] = 'Data Aset berhasil dihapus';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_jenis_aktiva'])) {
				$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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


			$this->redirect($this->url->link('catalog/aset', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getList();
	}

	public function penyesuaian() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Master Data Aset');

		$this->load->model('catalog/aset');
		$this->model_catalog_aset->penyesuaianNilai();
		$this->session->data['success'] = 'Nilai Buku Aset Berhasil Diperbarui';

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_jenis_aktiva'])) {
			$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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


		$this->redirect($this->url->link('catalog/aset', 'token=' . $this->session->data['token'].$url, 'SSL'));
	}

	private function getList() {
		
		if (isset($this->request->get['kode'])) {
			$kode = $this->request->get['kode'];
		} else {
			$kode = null;
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		if (isset($this->request->get['filter_kelompok_aset'])) {
			$filter_kelompok_aset = $this->request->get['filter_kelompok_aset'];
		} else {
			$filter_kelompok_aset = null;
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_jenis_aktiva'])) {
			$filter_jenis_aktiva = $this->request->get['filter_jenis_aktiva'];
		} else {
			$filter_jenis_aktiva = null;
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

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'tglpembelian';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['kode'])) {
			$url .= '&kode=' . urlencode(html_entity_decode($this->request->get['kode'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_jenis_aktiva'])) {
			$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_jenis_aktiva'])) {
			$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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
		$this->data['url'] = $this->url->link('catalog/aset', 'token=' . $this->session->data['token'] . $url, 'SSL');

   		$this->data['insert'] = $this->url->link('catalog/aset/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['penyesuaian'] = $this->url->link('catalog/aset/penyesuaian', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/aset/delete', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['excel'] = $this->url->link('catalog/aset/excel', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['tasets'] = array();

		$column=array('aset.aset_id','aset.kode','aset.jumlah','aset.name as name','aset.nilaibuku','aset.penyusutan','aset.penyusutanbulanan','aset.akumulasipenyusutan','jenis_aktiva.nama as nama_aktiva','aset.tglpembelian','aset.hargabeli','aset.status','kelompok_aset.name as kelompok','kelompok_aset.jenis_aset as jenis');
		$join=array();
		$join[]=array(
			'tablename'	=> 'kelompok_aset',
			'firsttable'	=> 'aset.kelompok_aset',
			'secondtable'	=> 'kelompok_aset.kelompok_aset_id',
		);
		$join[]=array(
			'tablename'	=> 'jenis_aktiva',
			'firsttable'	=> 'aset.jenis_aktiva',
			'secondtable'	=> 'jenis_aktiva.no_akun',
		);
		$data = array(
			'aset.name'	  => array('LIKE',$filter_name),
			'kelompok_aset'	=> $filter_kelompok_aset,
			'jenis_aktiva'	=> $filter_jenis_aktiva,
			'aset.hapus'	=>array('<',1),
			'aset.status'	=>$filter_status,

			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		if(!empty($filter_date_end)){
			$data['aset.tglpembelian']=array('>=',$filter_date_start,'<=',$filter_date_end);
		}else{
			$data['aset.tglpembelian']=array('>=',$filter_date_start);
		}

		if($filter_date_start == '1970-01-01'){
			$filter_date_start=null;
		}
		$orders=array();
		$orders=array($sort=>$order);
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$results = $this->model_catalog_aset->getAsets($column,$join,$data,$orders,$limit,$offset);
		$product_total = $this->model_catalog_aset->totalAsets($data);
		//print_r($results);
		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('catalog/aset/update', 'token=' . $this->session->data['token'] . '&aset_id=' . $result['aset_id'], 'SSL')
			);


			if($result['tglpembelian'] == '1970-01-01' & $result['hargabeli'] == 0){
				$action[] = array(
					'text' => 'Input Informasi Pembelian',
					'href' => $this->url->link('catalog/aset/updateinfo', 'token=' . $this->session->data['token'] . '&aset_id=' . $result['aset_id'], 'SSL')
				);
			}

			/*$action[] = array(
				'text' => 'Penyesuaian',
				'href' => $this->url->link('catalog/aset/penyesuaian', 'token=' . $this->session->data['token'] . '&aset_id=' . $result['aset_id'], 'SSL')
			);*/

			$action[] = array(
				'text' => 'Kartu Aset',
				'href' => $this->url->link('catalog/aset/kartustok', 'token=' . $this->session->data['token'] . '&aset_id=' . $result['aset_id'], 'SSL')
			);

		//	$cek= $this->model_catalog_aset->cekOption($result['id']);

			$this->data['tasets'][] = array(
				'aset_id' => $result['aset_id'],
				'kode' => ($result['kode']==null)?'':$result['kode'],
				'jumlah' => ($result['jumlah']==0)?'':$result['jumlah'],
				'name'        => $result['name'],
				'kelompok'	=> $result['kelompok'],
				'jenis'	=> $result['jenis'],
				'nama_aktiva'	=> $result['nama_aktiva'],
				'status'	=> $result['status']==1?'Tersedia':($result['status'] == 2?'Tidak Tersedia':($result['status'] == 4?'Dijual':'Hilang')),
				'tglpembelian'	=>$result['tglpembelian'] != '1970-01-01'?date('d/m/y',strtotime($result['tglpembelian'])):'Belum dilakukan pembelian',
				'hargabeli'	=> $this->currency->format($result['hargabeli']),
				'nilaibuku'	=> $this->currency->format($result['nilaibuku']),
				'penyusutan'	=> $this->currency->format($result['penyusutan']),
				'penyusutanbulanan'	=> $this->currency->format($result['penyusutanbulanan']),
				'akumulasipenyusutan'	=> $this->currency->format($result['akumulasipenyusutan']),
				'selected'    => isset($this->request->post['selected']) && in_array($result['aset_id'], $this->request->post['selected']),
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

		if (isset($this->request->get['kode'])) {
			$url .= '&kode=' . urlencode(html_entity_decode($this->request->get['kode'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_jenis_aktiva'])) {
			$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$this->data['sort_tglpembelian'] = $this->url->link('catalog/aset', 'token=' . $this->session->data['token'] . '&sort=tglpembelian' . $url, 'SSL');
		$this->data['sort_name'] = $this->url->link('catalog/aset', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$this->data['sort_kelompok_aset'] = $this->url->link('catalog/aset', 'token=' . $this->session->data['token'] . '&sort=kelompok_aset' . $url, 'SSL');
		$this->data['sort_hargabeli'] = $this->url->link('catalog/aset', 'token=' . $this->session->data['token'] . '&sort=hargabeli' . $url, 'SSL');
		$this->data['sort_nilaibuku'] = $this->url->link('catalog/aset', 'token=' . $this->session->data['token'] . '&sort=nilaibuku' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['kode'])) {
			$url .= '&kode=' . urlencode(html_entity_decode($this->request->get['kode'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_jenis_aktiva'])) {
			$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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
		$pagination->url = $this->url->link('catalog/aset', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->load->model('catalog/kelompokaset');
		$this->data['asets'] = $this->model_catalog_kelompokaset->getKelompokasets();
		$this->data['aktivas'] = $this->model_catalog_kelompokaset->getAktivas();

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_kelompok_aset']	= $filter_kelompok_aset;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'catalog/aset_list.tpl';
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
		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_jenis_aktiva'])) {
			$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}



		if (!isset($this->request->get['aset_id'])) {
			$this->data['action'] = $this->url->link('catalog/aset/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/aset/update', 'token=' . $this->session->data['token'].$url. '&aset_id=' . $this->request->get['aset_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/aset', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['aset_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$option_info = $this->model_catalog_aset->getAset(array('aset_id'	=> $this->request->get['aset_id']));
    	}

		$this->data['token'] = $this->session->data['token'];


		if (!empty($this->request->post)) {
			$this->data['kode'] = $this->request->post['kode'];
			$this->data['name'] = $this->request->post['name'];
			$this->data['jumlah'] = $this->request->post['jumlah'];
			$this->data['harga'] = $this->request->post['harga'];
			$this->data['status'] = $this->request->post['status'];
			$this->data['tglpembelian'] = $this->request->post['tglpembelian'];
			$this->data['kelompok_aset'] = $this->request->post['kelompok_aset'];
			$this->data['hargabeli'] = $this->request->post['hargabeli'];
			$this->data['jenis_aktiva'] = $this->request->post['jenis_aktiva'];

		} elseif (!empty($option_info)) {
			$this->data['kode'] = $option_info['kode'];
			$this->data['name'] = $option_info['name'];
			$this->data['jumlah'] = $option_info['jumlah'];
			$this->data['harga'] = $option_info['harga'];
			$this->data['status'] = $option_info['status'];
			$this->data['tglpembelian'] = $option_info['tglpembelian'];
			$this->data['kelompok_aset'] = $option_info['kelompok_aset'];
			$this->data['hargabeli'] = $option_info['hargabeli'];
			$this->data['jenis_aktiva'] = $option_info['jenis_aktiva'];

		} else {
			$this->data['kode'] = '';
			$this->data['name'] = '';
			$this->data['jumlah'] = '';
			$this->data['harga'] = '';
			$this->data['status'] = '';
			$this->data['tglpembelian'] = '';
			$this->data['kelompok_aset'] = '';
			$this->data['hargabeli'] = '';
			$option_info['jenis_aktiva']='';
		}

		$this->load->model('catalog/kelompokaset');
		$this->data['asets'] = $this->model_catalog_kelompokaset->getKelompokasets();
		$this->data['aktivas'] = $this->model_catalog_kelompokaset->getAktivas();


		$this->template = 'catalog/aset_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getFormAset() {

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_jenis_aktiva'])) {
			$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}



		$this->data['action'] = $this->url->link('catalog/aset/updateinfo', 'token=' . $this->session->data['token'].$url. '&aset_id=' . $this->request->get['aset_id'], 'SSL');

		$this->data['cancel'] = $this->url->link('catalog/aset', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['aset_id']) ) {
      		$option_info = $this->model_catalog_aset->getAset(array('aset_id'	=> $this->request->get['aset_id']));
    	}else{
				$this->redirect($this->url->link('catalog/aset', 'token=' . $this->session->data['token'].$url, 'SSL'));
			}
		$this->data['name'] = $option_info['name'];

		$this->data['token'] = $this->session->data['token'];


		if (!empty($this->request->post)) {
			$this->data['tglpembelian'] = $this->request->post['tglpembelian'];
			$this->data['hargabeli'] = $this->request->post['hargabeli'];
			$this->data['nilaibuku'] = $this->request->post['nilaibuku'];
			//$this->data['aku'] = $this->request->post['nilaibuku'];

		} elseif (!empty($option_info)) {
			$this->data['name'] = $option_info['name'];
			$this->data['tglpembelian'] = $option_info['tglpembelian']=='1970-01-01'?date('Y-m-d'):$option_info['tglpembelian'];
			$this->data['hargabeli'] = $option_info['hargabeli'];
			$this->data['nilaibuku'] = $option_info['nilaibuku'];

		} else {
			$this->data['tglpembelian'] = '';
			$this->data['hargabeli'] = '';
			$this->data['nilaibuku'] = '';

		}

		$this->load->model('catalog/kelompokaset');
		$this->data['asets'] = $this->model_catalog_kelompokaset->getKelompokasets();
		$this->data['aktivas'] = $this->model_catalog_kelompokaset->getAktivas();


		$this->template = 'catalog/asetinfo_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		/*if (!$this->user->hasPermission('modify', 'catalog/aset')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Aset';
		}*/

		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 255 || $this->request->post['name']=='')) {
			$this->error['waring'] = 'Nama Aset harus diisi.';
		}
		/*if(empty($this->request->post['tglpembelian'])){
			$this->error['tglpembelian']='Tanggal pembelian harus diisi';
		}
		if(empty($this->request->post['hargabeli'])){
			$this->error['hargabeli']='Harga beli harus diisi';
		}*/
		if(empty($this->request->post['jumlah'])){
			$this->error['warning']='Jumlah harus diisi';
		}
		if(empty($this->request->post['kode'])){
			$this->error['warning']='Kode aset harus diisi';
		}
		
		if(empty($this->request->post['kelompok_aset'])){
			$this->error['warning']='Kelompok aset harus dipilih';
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

	private function validateFormInfo() {
		/*if (!$this->user->hasPermission('modify', 'catalog/aset')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Aset';
		}*/

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
		/*if (!$this->user->hasPermission('modify', 'catalog/aset')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Aset';
		}*/

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function autocomplete() {
		$json = array();

		//if (isset($this->request->get['filter_name']) ) {
			$this->load->model('catalog/aset');

			if (isset($this->request->get['filter_name'])) {
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
			'tglpembelian'	=>'1970-01-01'
				//'start'               => 0,
				//'limit'               => $limit
			);
			$offset=0;
			$limit=$limit;

			$results = $this->model_catalog_aset->getAsets(array(),array(),$data,array(),$limit,$offset);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['aset_id'],
					'text'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}
		//}

		$this->response->setOutput(json_encode($json));
	}
	public function autocompletefull() {
		$json = array();

		//if (isset($this->request->get['filter_name']) ) {
			$this->load->model('catalog/aset');

			if (isset($this->request->get['filter_name'])) {
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
			//'tglpembelian'	=>'1970-01-01'
				//'start'               => 0,
				//'limit'               => $limit
			);
			$offset=0;
			$limit=$limit;

			$results = $this->model_catalog_aset->getAsets(array(),array(),$data,array(),$limit,$offset);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['aset_id'],
					'text'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}
		//}

		$this->response->setOutput(json_encode($json));
	}


	public function stokAwal(){
		//$this->load->model('gudang/product');
		$this->load->model('catalog/aset');


		$this->document->setTitle('Input Stok Awal Aset');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_jenis_aktiva'])) {
			$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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

		if (isset($this->request->get['aset_id'])) {
				$aset_id=$this->request->get['aset_id'];
			}
		else{
			$this->redirect($this->url->link('catalog/aset', 'token=' . $this->session->data['token'] . $url, 'SSL'));

		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormStok()) {

			$this->model_catalog_aset->setStokAwal($this->request->post);
	  	$this->session->data['success'] = 'Success: Stok berhasil ditambahkan.';

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_jenis_aktiva'])) {
				$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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
				$this->redirect($this->url->link('catalog/aset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_jenis_aktiva'])) {
				$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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

			$this->data['action'] = $this->url->link('catalog/aset/stokAwal', 'token=' . $this->session->data['token'] . '&aset_id=' . $this->request->get['aset_id'] . $url, 'SSL');

			$this->data['cancel'] = $this->url->link('catalog/aset', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->error)) {
				$this->data['error'] = $this->error;
			} else {
				$this->data['error'] = '';
			}

    	//if(empty($this->request->post)){
			$this->data['productdesc']=$this->model_catalog_aset->getAset(array('aset_id' => $aset_id));
			$this->data['aset_id']=$aset_id;


		$this->template = 'catalog/stokawal_aset.tpl';
		$this->children = array(
					'common/header',
					'common/footer'
				);
			$this->response->setOutput($this->render());

	}
	public function validateFormStok(){
	/*	if (!$this->user->hasPermission('modify', 'catalog/aset')) {
      		$this->error['warning'] = 'Anda tidak memiliki ijin untuk memodifikasi data stok Aset.';
    	}*/
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
	public function stokopname(){
		//$this->load->model('gudang/product');
		$this->load->model('catalog/aset');


		$this->document->setTitle('Stok Opname Aset');

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_jenis_aktiva'])) {
			$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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

		if (isset($this->request->get['aset_id'])) {
				$aset_id=$this->request->get['aset_id'];
			}
		else{
			$this->redirect($this->url->link('catalog/aset', 'token=' . $this->session->data['token'] . $url, 'SSL'));

		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFormStok()) {

			$this->model_catalog_aset->stokOpname($this->request->post);
	  	$this->session->data['success'] = 'Success: Data Stok berhasil diperbarui.';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_jenis_aktiva'])) {
				$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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
				$this->redirect($this->url->link('catalog/aset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kelompok_aset'])) {
				$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_jenis_aktiva'])) {
				$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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

			$this->data['action'] = $this->url->link('catalog/aset/stokopname', 'token=' . $this->session->data['token'] . '&aset_id=' . $this->request->get['aset_id'] . $url, 'SSL');

			$this->data['cancel'] = $this->url->link('catalog/aset', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->error)) {
				$this->data['error'] = $this->error;
			} else {
				$this->data['error'] = '';
			}

    	//if(empty($this->request->post)){
			$this->data['productdesc']=$this->model_catalog_aset->getAset(array('aset_id'	=> $aset_id));
			$this->data['aset_id']=$aset_id;


		$this->template = 'catalog/stokopname_aset.tpl';
		$this->children = array(
					'common/header',
					'common/footer'
				);
			$this->response->setOutput($this->render());

	}
	public function kartustok() {
		//$this->load->language('report/stokbarang');

		$this->document->setTitle('Kartu Aset');

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_jenis_aktiva'])) {
			$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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

		if (isset($this->request->get['aset_id'])) {
			$url .= '&aset_id=' . $this->request->get['aset_id'];
		}


		$this->data['url'] = $this->url->link('catalog/aset/kartustok', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['pagekartu'])) {
			$url .= '&pagekartu=' . $this->request->get['pagekartu'];
		}


		if (isset($this->request->get['tanggal'])) {
			$url .= '&tanggal=' . $this->request->get['tanggal'];
		}

		if (isset($this->request->get['aset_id'])) {
			$aset_id = $this->request->get['aset_id'];
		} else {
			$this->redirect($this->url->link('catalog/aset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		$this->load->model('catalog/aset');
		//$this->load->model('gudang/kartustok');


		$this->data['orders'] = array();

		$this->data['aset']=$this->model_catalog_aset->getAset($aset_id);

		$data = array(
        'tanggal'     => $tanggal,
				'aset_id'	=> $aset_id,
				'start'                  => ($pagekartu - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_catalog_aset->getTotalKartustoks($data);

		$results = $this->model_catalog_aset->getKartustoks($data);
		$this->data['cancel'] = $this->url->link('catalog/aset', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->data['heading_title'] = 'Kartu Stok Produk';
		$this->data['token'] = $this->session->data['token'];



		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_kelompok_aset'])) {
			$url .= '&filter_kelompok_aset=' . urlencode(html_entity_decode($this->request->get['filter_kelompok_aset'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_jenis_aktiva'])) {
			$url .= '&filter_jenis_aktiva=' . urlencode(html_entity_decode($this->request->get['filter_jenis_aktiva'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
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

		if (isset($this->request->get['aset_id'])) {
			$url .= '&aset_id=' . $this->request->get['aset_id'];
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
		$pagination->url = $this->url->link('catalog/aset/kartustok', 'token=' . $this->session->data['token'] . $url . '&pagekartu={page}', 'SSL');

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
