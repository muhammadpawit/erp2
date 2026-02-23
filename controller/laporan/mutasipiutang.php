<?php
class ControllerLaporanMutasipiutang extends Controller {
	private $error = array();
	

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Laporan Deposit Customer');

		$this->load->model('sale/customer');

		$this->getList();
	}

	// baru 14 Juli 2020
	function xlscreation_directgrosir() {

		$reportdetails = $this->excel();

		$objPHPExcel = new PHPExcel(); 
		$objPHPExcel->getProperties()
				->setCreator("IT Division")
				->setLastModifiedBy("IT Division")
				->setTitle("PT.Nisson Indonesia")
				->setSubject("PT.Nisson Indonesia")
				->setDescription("Export Item to Excel")
				->setKeywords("IT Division")
				->setCategory("IT Division");

		// Set the active Excel worksheet to sheet 0
		$objPHPExcel->setActiveSheetIndex(0); 

		// Initialise the Excel row number
		$rowCount = 0; 
		// Sheet cells

		$cell_definition = array(
			'A' => 'Customer ID',
			'B' => 'Nama Customer',
			'C' => 'Saldo Awal',
			'D' => 'Penambahan',
			'E' => 'Pelunasan',
			'F' => 'Saldoakhir'
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
				//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
				
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
		$fileName = "Laporan_mutasi_piutang_".$presentDate."_didownload_oleh_".$this->user->getUsername().".xlsx";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		die();
	}
	public function cetak(){
		if($this->user->getUsername()=="pawitx"){
			echo "<pre>";print_r($this->excel());
		}else{
			$this->xlscreation_directgrosir();
		}
		
	}
	public function excel() {
		$this->load->model('sale/customer');
        if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = null;
			$filter_date_start=date('Y-m-d',strtotime('first day of this month'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
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
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['deposit'])) {
			$url .= '&deposit=' . urlencode(html_entity_decode($this->request->get['deposit'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_area'])) {
			$url .= '&filter_area=' . $this->request->get['filter_area'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('catalog/title');
		$this->load->model('catalog/area');

        if(!is_null($filter_date_start)){
            $this->data['customers'] = array();
                $data = array(
                    'filter_name'	  => $filter_name,
                    //'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
                    //'limit'           => $this->config->get('config_admin_limit')
				);
				if (isset($this->request->get['filter_page_start'])) {
					$start=($this->request->get['filter_page_start']-1)*20;
					$data['start']=$start;
				}else{
					$data['start']=0;
				}
		
				if (isset($this->request->get['filter_page_end'])) {
					//2 - 5 berarti data ke 20 - 99=79
					$limit=($this->request->get['filter_page_end']-$this->request->get['filter_page_start'])*20+20;
					$data['limit']=$limit;
				}else{
					$data['limit']=20;
				}
                $results = $this->model_sale_customer->getVendorsnew($data);
                $product_total = count($this->model_sale_customer->getVendorsnewtotal($data));

            //get status
            $this->load->model('report/laporanmutasi');
            $nominal=0;
            $piutang=0;
            foreach ($results as $result) {
            //	$nominal = $this->model_sale_customer->getnominalgiro($result['customer_id']);
                //$piutang = $this->model_sale_customer->piutang($result['customer_id']);
                
                //$saldoawal=
                $title=null;
                $area=null;
                $saldoawal=$this->model_report_laporanmutasi->getSaldoAwal(array('tanggal'=>$filter_date_start,'customer_id'=>$result['customer_id']));
                $penambahan=$this->model_report_laporanmutasi->penambahan(array('filter_date_start'=>$filter_date_start,'filter_date_end'=>$filter_date_end,'customer_id'=>$result['customer_id']));
                $pelunasan= $this->model_report_laporanmutasi->pembayaran(array('filter_date_start'=>$filter_date_start,'filter_date_end'=>$filter_date_end,'customer_id'=>$result['customer_id']));
                $customers[]= array(
                    'Customer ID' => $result['customer_id'],
                    'Nama Customer'        => $title.' '.$result['name'],
                    'Saldo Awal'	=> $this->currency->format($saldoawal),
                    'Penambahan'	=> $this->currency->format($penambahan),
					'Pelunasan'	=> $this->currency->format($pelunasan),
					'Saldoakhir'	=> $this->currency->format($saldoawal+$penambahan-$pelunasan),
                );

            }
        }

 		return $customers;
	}
	// end baru
	private function getList() {
        if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = null;
			$filter_date_start=date('Y-m-d',strtotime('first day of this month'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
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
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['deposit'])) {
			$url .= '&deposit=' . urlencode(html_entity_decode($this->request->get['deposit'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_area'])) {
			$url .= '&filter_area=' . $this->request->get['filter_area'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('catalog/title');
		$this->load->model('catalog/area');

        if(!is_null($filter_date_start)){
            $this->data['customers'] = array();
                $data = array(
                    'filter_name'	  => $filter_name,
                    'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
                    'limit'           => $this->config->get('config_admin_limit')
				);
				$all = array(
                    'filter_name'	  => $filter_name,
                    //'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
                    //'limit'           => $this->config->get('config_admin_limit')
				);
				$alls = $this->model_sale_customer->getVendorsnew($all);
                $results = $this->model_sale_customer->getVendorsnew($data);
                $product_total = count($this->model_sale_customer->getVendorsnewtotal($data));

            //get status
            $this->load->model('report/laporanmutasi');
            $nominal=0;
			$piutang=0;
			$totalsaldoawal=0;
			$totalpenambahanpiutang=0;
			$totalpelunasan=0;
			$totalsaldoakhir=0;
			foreach ($alls as $result) {
				$saldoawal=$this->model_report_laporanmutasi->getSaldoAwal(array('tanggal'=>$filter_date_start,'customer_id'=>$result['customer_id']));
                $penambahan=$this->model_report_laporanmutasi->penambahan(array('filter_date_start'=>$filter_date_start,'filter_date_end'=>$filter_date_end,'customer_id'=>$result['customer_id']));
				$pelunasan= $this->model_report_laporanmutasi->pembayaran(array('filter_date_start'=>$filter_date_start,'filter_date_end'=>$filter_date_end,'customer_id'=>$result['customer_id']));
				$totalsaldoawal+=($saldoawal);
				$totalpenambahanpiutang+=($penambahan);
				$totalpelunasan+=($pelunasan);
				$totalsaldoakhir+=($saldoawal+$penambahan-$pelunasan);
			}
			$this->data['totalsaldoawal']=$this->currency->format($totalsaldoawal);
			$this->data['totalpenambahanpiutang']=$this->currency->format($totalpenambahanpiutang);
			$this->data['totalpelunasan']=$this->currency->format($totalpelunasan);
			$this->data['totalsaldoakhir']=$this->currency->format($totalsaldoakhir);
            foreach ($results as $result) {
            //	$nominal = $this->model_sale_customer->getnominalgiro($result['customer_id']);
                //$piutang = $this->model_sale_customer->piutang($result['customer_id']);
                
                //$saldoawal=
                $title=null;
                $area=null;
                $saldoawal=$this->model_report_laporanmutasi->getSaldoAwal(array('tanggal'=>$filter_date_start,'customer_id'=>$result['customer_id']));
                $penambahan=$this->model_report_laporanmutasi->penambahan(array('filter_date_start'=>$filter_date_start,'filter_date_end'=>$filter_date_end,'customer_id'=>$result['customer_id']));
                $pelunasan= $this->model_report_laporanmutasi->pembayaran(array('filter_date_start'=>$filter_date_start,'filter_date_end'=>$filter_date_end,'customer_id'=>$result['customer_id']));
                $this->data['customers'][] = array(
                    'customer_id' => $result['customer_id'],
                    'name'        => $title.' '.$result['name'],
                    'saldoawal'	=> $this->currency->format($saldoawal),
                    'penambahan'	=> $this->currency->format($penambahan),
					'pelunasan'	=> $this->currency->format($pelunasan),
					'saldoakhir'	=> $this->currency->format($saldoawal+$penambahan-$pelunasan),
                    //'piutang'	=> $this->currency->format($piutang),
                    //'nominal'	=> $this->currency->format($nominal),
                    //'sisa'	=> $this->currency->format(($piutang-$result['deposit']-$nominal<0)?0:$piutang-$result['deposit']-$nominal),
                    'action'      => $action
                );

            }
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
		
        if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_area'])) {
			$url .= '&filter_area=' . $this->request->get['filter_area'];
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/mutasipiutang', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();
		$this->data['totalpage']=ceil($product_total/($this->config->get('config_admin_limit')));

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['token'] = $this->session->data['token'];

		
		$this->template = 'laporan/mutasipiutang.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}


	public function autocomplete() {
		$json = array();

		$this->load->model('sale/customer');

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

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}
			if($sales){
				$data = array(
				'name'	  => array('LIKE',$filter_name),
				'sales'	=> $sales
					//'start'               => 0,
					//'limit'               => $limit
				);
			}else{
				$data = array(
				'name'	  => array('LIKE',$filter_name),
				//'sales'	=> $s
					//'start'               => 0,
					//'limit'               => $limit
				);
			}
			$offset=0;
			$limit=$limit;

			$results = $this->model_sale_customer->getVendors($data,array(),$limit,$offset);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['customer_id'],
					'text' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

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
			'firstname'	  => array('LIKE',$data['filter_name']),
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

	public function deposit() {
		$this->load->language('sale/customer');
		$this->document->setTitle("History Deposit Customer");
		$this->load->model('sale/customer');
		if (isset($this->request->get['customer_id'])) {
			if(empty($this->request->get['customer_id'])){
				$this->redirect($this->url->link('laporan/depositcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}else{
				$customer_id = $this->request->get['customer_id'];
			}
		} else {
			$this->redirect($this->url->link('laporan/depositcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		if (isset($this->request->get['deposit'])) {
			$url .= '&deposit=' . urlencode(html_entity_decode($this->request->get['deposit'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
				if (isset($this->request->get['pagealamat'])) {
			$url .= '&pagealamat=' . $this->request->get['pagealamat'];
		}


		$this->data['cancel'] = $this->url->link('laporan/depositcustomer', 'token=' . $this->session->data['token'] . $url, 'SSL');
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

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		$pagination = new Pagination();
		$pagination->total = $address_total;
		$pagination->page = $pagealamat;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/depositcustomer/deposit', 'token=' . $this->session->data['token'] . $url . '&pagealamat={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->template = 'laporan/deposit_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

}
?>
