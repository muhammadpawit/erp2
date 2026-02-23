<?php
class ControllerSaleSalesorder extends Controller {
	private $error = array();
	// baru 3 April 2020
	public function tolaktgl(){
		if(isset($this->request->get['order_id'])){
			$id=$this->request->get['order_id'];
			$penj=array(
		      'status'  => 4,
		      'alasan_batal'  =>'perubahan tanggal SO ditolak',
		    );
		    $this->db->update('sales_order',$penj,array('id'=>$id));
		    $penj=array(
		      'status_pengiriman'  => 4,
		    );
		    $this->db->update('sales_order_product',$penj,array('sales_order_id'=>$id));
		    $this->session->data['success'] = 'Sukses: Sales Order berhasil disimpan';
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
			if (isset($this->request->get['filter_jenisorder'])) {
				$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}else{
			echo "Gagal";
		}
	}
	public function setujuitgl(){
		if(isset($this->request->get['order_id'])){
			$id=$this->request->get['order_id'];
			$penj=array(
		      'status'  => 1,
		    );
		    $this->db->update('sales_order',$penj,array('id'=>$id));
		    $this->session->data['success'] = 'Sukses: Sales Order berhasil disimpan';
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
			if (isset($this->request->get['filter_jenisorder'])) {
				$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}else{
			echo "Gagal";
		}
	}
	public function tolakharga(){
		if(isset($this->request->get['id'])){
			$id=$this->request->get['id'];
			$penj=array(
		      'status_pengiriman'  => 4,
		    );
		    $this->db->update('sales_order_product',$penj,array('id'=>$id));
		    $this->session->data['success'] = 'Sukses: Sales Order berhasil disimpan';
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
			if (isset($this->request->get['filter_jenisorder'])) {
				$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}else{
			echo "Gagal";
		}
	}
	public function setujuiharga(){
		if(isset($this->request->get['id'])){
			$id=$this->request->get['id'];
			$penj=array(
				//'harga_terendah'  => $this->request->get['price'],
				'persetujuanharga'=>0,
				'disetujuioleh'=>$this->user->getId(),
				'disetujuitgl'=>date('Y-m-d H:i:s'),
			);
		    $this->db->update('sales_order_product',$penj,array('id'=>$id));
		    $this->session->data['success'] = 'Sukses: Sales Order berhasil disimpan';
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
			if (isset($this->request->get['filter_jenisorder'])) {
				$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}else{
			echo "Gagal";
		}
	}
	// baru 17 Februari 2020
	public function tutupsobelumdikirim(){
		$this->load->model('sale/salesorder');
		$id= $this->request->get['tutup'];
		$so = $this->model_sale_salesorder->tutupsobelumdikirim($id);
		$pajaktotal=0;
		$total=0;
		$pajaksatuan=0;
		$hargasatuan=0;
		$selisih=0;
		foreach($so as $s ){
			$selisih = ($s['quantity']-$s['quantityterima']);
			$pajaktotal =(($s['quantityterima']*$s['price'])*0.1);
			$total =(($s['quantityterima']*$s['price'])+$pajaktotal);
			$pajaksatuan += ($s['pajak']);
			$hargasatuan += ($s['price']*$selisih);
			$this->db->update('sales_order_product',array('status_pengiriman'=>3,'quantity'=>$s['quantityterima'],'totalpajak'=>$pajaktotal,'total'=>$total,'qtypesanan'=>$s['quantity']),array('id'=>$s['id']));
		}
		if($this->user->getUsername()=="pawits"){
			//echo "<pre>";print_r(ceil($hargasatuan+($hargasatuan*0.1)));exit;
			//echo "<pre>";print_r($so);exit;
		}
			$this->db->query("UPDATE sales_order SET pajak=pajak-".ceil(($hargasatuan*0.1)).",sub_total=sub_total-".$hargasatuan.",total=total-".ceil($hargasatuan+($hargasatuan*0.1))." WHERE id='$id' ");
			$this->session->data['success'] = 'Sukses: Sales Order berhasil ditutup';
			$url = '';
			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
			}
			if (isset($this->request->get['tutup'])) {
				//$url .= '&filter_order_id=' . $this->request->get['tutup'];
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
				if($this->request->get['page']>0){
					$url .= '&page=' . $this->request->get['page'];
				}
			}
			$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	// end baru
	// baru 12 Februari 2020
	public function tutupso(){
		$this->load->model('sale/salesorder');
		$id= $this->request->get['tutup'];
		$so = $this->model_sale_salesorder->tutupso($id);
		$pajaktotal=0;
		$total=0;
		$pajaksatuan=0;
		$hargasatuan=0;
		$selisih=0;
		foreach($so as $s ){
			$selisih = ($s['quantity']-$s['quantityterima']);
			$pajaktotal =(($s['quantityterima']*$s['price'])*0.1);
			$total =(($s['quantityterima']*$s['price'])+$pajaktotal);
			$pajaksatuan += ($s['pajak']);
			$hargasatuan += ($s['price']*$selisih);
			$this->db->update('sales_order_product',array('status_pengiriman'=>3,'quantity'=>$s['quantityterima'],'totalpajak'=>$pajaktotal,'total'=>$total,'qtypesanan'=>$s['quantity']),array('id'=>$s['id']));
		}
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";print_r(ceil($hargasatuan+($hargasatuan*0.1)));exit;
		}
		$this->db->query("UPDATE sales_order SET pajak=pajak-".ceil(($hargasatuan*0.1)).",sub_total=sub_total-".$hargasatuan.",total=total-".ceil($hargasatuan+($hargasatuan*0.1))." WHERE id='$id' ");
			$this->session->data['success'] = 'Sukses: Sales Order berhasil ditutup';
			$url = '';
			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
			}
			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}
			if (isset($this->request->get['tutup'])) {
				$url .= '&filter_order_id=' . $this->request->get['tutup'];
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
			if (isset($this->request->get['filter_jenisorder'])) {
				$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	// end baru
	// baru 18 September 2019
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
			'A' => 'tanggal',
			'B' => 'Gudang',
			'C' => 'NO.SO',
			'D' => 'Nama Customer',
			'E' => 'Produk',
			'F' => 'Quantity Kirim',
			'G' => 'Quantity Terima',
			'H' => 'Status Pengiriman',
			'I' => 'Catatan'
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
		$fileName = "Sales_Order_Gantung_".$presentDate.".xls";

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
	
	public function cetakexcel($display = null) {
		$this->load->model('sale/salesorder');
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
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
		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = null;
		}
		if (isset($this->request->get['filter_jenisorder'])) {
			$filter_jenisorder = $this->request->get['filter_jenisorder'];
		} else {
			$filter_jenisorder = null;
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
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
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
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/salesorder/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/salesorder/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

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

		$data = array(
			'sales_order.id'	=> empty($filter_order_id)?array('>=',1):$filter_order_id,
			'sales_order.customer_id'	=> empty($filter_customer_id)?array('>=',1):$filter_customer_id,
			'sales_order.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):$filter_tanggal,
			//'sales_order.gudang_id'	=>empty($filter_gudang_id)?array('>',0):$filter_gudang_id,
			'sales_order.gudang_id'	=>array('IN',$arrsql),
			//'sales_rder.pengiriman'	=> empty($filter_shipping_method)?array('>',0):$filter_shipping_method,
			'sales_order_product.status_pengiriman'	=> empty($filter_status)?array('>=',1):$filter_status,
			'sales_order_product.product_id'	=> empty($filter_jenisorder)?array('>=',1):$filter_jenisorder,

		);
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		//echo $offset;exit;
		$limit=$this->config->get('config_admin_limit');

		$order=array();
		$order=array('sales_order_product.sales_order_id'=>'DESC');
		
		

		$results = $this->model_sale_salesorder->getSoTanpaSjnew($data,$filter_customer_id,$filter_gudang_id,0,null);
		$product_total = count($this->model_sale_salesorder->totalgetSoTanpaSjnew($data,$filter_customer_id,$filter_gudang_id,$limit,$offset));
		//echo $product_total;
		//print_r($results);
		$this->load->model('keuangan/bank');

		//print_r($results);
		$this->load->model('user/user');
		/*$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$data['customer.sales']=$this->user->getId();
		}*/

		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),3);
		foreach ($results as $result) {
			//if(in_array($result['gudang_id'],$gudangs)){
			$action = array();
			
					$action[] = array(
						'text' => 'cetak',
						'href' => $this->url->link('sale/salesorder/tampilcetak', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'], 'SSL')
					);
			
			/*$action[] = array(
				'text' => 'Invoice',
				'href' => $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['order_gudang_id'], 'SSL')
			);*/
			/*$action[] = array(
				'text' => 'Surat Jalan',
				'href' => $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] . '&view=2&order_id=' . $result['id'], 'SSL')
			);*/


			/*if($result['payment_status'] == 3 & $result['status_pengiriman'] == 2){
				$action[] = array(
					'text' => 'Selesai',
					'href' => $this->url->link('sale/salesorder/selesai', 'token=' . $this->session->data['token'] . '&order_gudang_id=' . $result['order_gudang_id'], 'SSL')
				);

			}*/
								if($result['status_pengiriman'] == 1){
                                  $statuskirim = 'Belum Dikirm';
                                }
                                if($result['status_pengiriman'] == 2){
                                   $statuskirim = 'Dikirim Sebagian';
                                }
                                if($result['status_pengiriman'] == 3){
                                   $statuskirim = 'Sudah Dikirim';
                                }
                                if($result['status_pengiriman'] == 4){
                                   $statuskirim = 'Dibatalkan';
                                }
								
			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'NO.SO'        => $result['no_so'],
				'sales_order_id'        => $result['sales_order_id'],
				'Nama Customer'	=> $result['namacustomer'],
				'Gudang'	=> $result['namagudang'],
				'Produk'	=> $result['namaproduct'],
				'Quantity Kirim'	=> $result['quantity'],
				'Quantity Terima'	=> $result['quantityterima'],
				'alasan_batal'	=> $result['alasan_batal'],
				'email'	=> $result['email'],
				'telephone'	=> $result['telephone'],
				'Status Pengiriman'	=> $statuskirim,
				'Catatan'	=> $result['catatan'],
				'status_pengiriman'	=> $result['status_pengiriman'],
				'tanggal'	=>date('d/m/y',strtotime($result['date_added']))
			);
		/*}else{
			$product_total -=1;
		}*/
		}
		return $this->data['penjualans'];		
	}
	
	public function cetakbiasa() {
		$this->load->model('sale/salesorder');
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
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
		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = null;
		}
		if (isset($this->request->get['filter_jenisorder'])) {
			$filter_jenisorder = $this->request->get['filter_jenisorder'];
		} else {
			$filter_jenisorder = null;
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
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
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
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/salesorder/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/salesorder/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

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

		$data = array(
			'sales_order.id'	=> empty($filter_order_id)?array('>=',1):$filter_order_id,
			'sales_order.customer_id'	=> empty($filter_customer_id)?array('>=',1):$filter_customer_id,
			'sales_order.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):$filter_tanggal,
			//'sales_order.gudang_id'	=>empty($filter_gudang_id)?array('>',0):$filter_gudang_id,
			'sales_order.gudang_id'	=>array('IN',$arrsql),
			//'sales_rder.pengiriman'	=> empty($filter_shipping_method)?array('>',0):$filter_shipping_method,
			'sales_order_product.status_pengiriman'	=> empty($filter_status)?array('>=',1):$filter_status,
			'sales_order_product.product_id'	=> empty($filter_jenisorder)?array('>=',1):$filter_jenisorder,

		);
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		//echo $offset;exit;
		$limit=$this->config->get('config_admin_limit');

		$order=array();
		$order=array('sales_order_product.sales_order_id'=>'DESC');
		
		

		$results = $this->model_sale_salesorder->getSoTanpaSjnew($data,$filter_customer_id,$filter_gudang_id,0,null);
		$product_total = count($this->model_sale_salesorder->totalgetSoTanpaSjnew($data,$filter_customer_id,$filter_gudang_id,$limit,$offset));
		//echo $product_total;
		//print_r($results);
		$this->load->model('keuangan/bank');

		//print_r($results);
		$this->load->model('user/user');
		/*$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$data['customer.sales']=$this->user->getId();
		}*/

		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),3);
		foreach ($results as $result) {
			//if(in_array($result['gudang_id'],$gudangs)){
			$action = array();
			
					$action[] = array(
						'text' => 'cetak',
						'href' => $this->url->link('sale/salesorder/tampilcetak', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'], 'SSL')
					);
			
			/*$action[] = array(
				'text' => 'Invoice',
				'href' => $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['order_gudang_id'], 'SSL')
			);*/
			/*$action[] = array(
				'text' => 'Surat Jalan',
				'href' => $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] . '&view=2&order_id=' . $result['id'], 'SSL')
			);*/


			/*if($result['payment_status'] == 3 & $result['status_pengiriman'] == 2){
				$action[] = array(
					'text' => 'Selesai',
					'href' => $this->url->link('sale/salesorder/selesai', 'token=' . $this->session->data['token'] . '&order_gudang_id=' . $result['order_gudang_id'], 'SSL')
				);

			}*/
                                if($result['status_pengiriman'] == 1){
                                  $statuskirim = 'Belum Dikirm';
                                }
                                if($result['status_pengiriman'] == 2){
                                   $statuskirim = 'Dikirim Sebagian';
                                }
                                if($result['status_pengiriman'] == 3){
                                   $statuskirim = 'Sudah Dikirim';
                                }
                                if($result['status_pengiriman'] == 4){
                                   $statuskirim = 'Dibatalkan';
                                }
			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'no_so'        => $result['no_so'],
				'sales_order_id'        => $result['sales_order_id'],
				'name'	=> $result['namacustomer'],
				'namagudang'	=> $result['namagudang'],
				'nameproduct'	=> $result['namaproduct'],
				'quantity'	=> $result['quantity'],
				'quantityterima'	=> $result['quantityterima'],
				'alasan_batal'	=> $result['alasan_batal'],
				'email'	=> $result['email'],
				'telephone'	=> $result['telephone'],
				'status'	=> $statuskirim,
				'catatan'	=> $result['catatan'],
				'status_pengiriman'	=> $result['status_pengiriman'],
				'tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'      => $action
			);
		/*}else{
			$product_total -=1;
		}*/
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
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
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
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/salesorder/sogantung', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		
		$this->data['cetakexcel'] = $this->url->link('sale/salesorder/exporttoexcel', 'token=' . $this->session->data['token'] . $url , 'SSL');

		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_tanggal']	= $filter_tanggal;
		$this->data['filter_gudang_id']	= $filter_gudang_id;
		$this->data['token'] = $this->session->data['token'];



		$this->template = 'sale/detailoredergantungcetak_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	// baru 13 Agustus 2019
	
	public function sogantung() {
		$this->load->model('sale/salesorder');
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
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
		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = null;
		}
		if (isset($this->request->get['filter_jenisorder'])) {
			$filter_jenisorder = $this->request->get['filter_jenisorder'];
		} else {
			$filter_jenisorder = null;
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
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
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
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/salesorder/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/salesorder/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

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

		$data = array(
			'sales_order.id'	=> empty($filter_order_id)?array('>=',1):$filter_order_id,
			'sales_order.customer_id'	=> empty($filter_customer_id)?array('>=',1):$filter_customer_id,
			'sales_order.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):$filter_tanggal,
			//'sales_order.gudang_id'	=>empty($filter_gudang_id)?array('>',0):$filter_gudang_id,
			'sales_order.gudang_id'	=>array('IN',$arrsql),
			//'sales_rder.pengiriman'	=> empty($filter_shipping_method)?array('>',0):$filter_shipping_method,
			'sales_order_product.status_pengiriman'	=> empty($filter_status)?array('>=',1):$filter_status,
			'sales_order_product.product_id'	=> empty($filter_jenisorder)?array('>=',1):$filter_jenisorder,

		);
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		//echo $offset;exit;
		$limit=$this->config->get('config_admin_limit');

		$order=array();
		$order=array('sales_order_product.sales_order_id'=>'DESC');
		
		

		$results = $this->model_sale_salesorder->getSoTanpaSjnew($data,$filter_customer_id,$filter_gudang_id,$limit,$offset);
		$product_total = count($this->model_sale_salesorder->totalgetSoTanpaSjnew($data,$filter_customer_id,$filter_gudang_id,$limit,$offset));
		//echo $product_total;
		//print_r($results);
		$this->load->model('keuangan/bank');

		//print_r($results);
		$this->load->model('user/user');
		/*$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$data['customer.sales']=$this->user->getId();
		}*/

		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),3);
		foreach ($results as $result) {
			//if(in_array($result['gudang_id'],$gudangs)){
			$action = array();
			
					$action[] = array(
						'text' => 'cetak',
						'href' => $this->url->link('sale/salesorder/tampilcetak', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'], 'SSL')
					);
			
			/*$action[] = array(
				'text' => 'Invoice',
				'href' => $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['order_gudang_id'], 'SSL')
			);*/
			/*$action[] = array(
				'text' => 'Surat Jalan',
				'href' => $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] . '&view=2&order_id=' . $result['id'], 'SSL')
			);*/


			/*if($result['payment_status'] == 3 & $result['status_pengiriman'] == 2){
				$action[] = array(
					'text' => 'Selesai',
					'href' => $this->url->link('sale/salesorder/selesai', 'token=' . $this->session->data['token'] . '&order_gudang_id=' . $result['order_gudang_id'], 'SSL')
				);

			}*/
			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'no_so'        => $result['no_so'],
				'sales_order_id'        => $result['sales_order_id'],
				'name'	=> $result['namacustomer'],
				'namagudang'	=> $result['namagudang'],
				'nameproduct'	=> $result['namaproduct'],
				'quantity'	=> $result['quantity'],
				'quantityterima'	=> $result['quantityterima'],
				'alasan_batal'	=> $result['alasan_batal'],
				'email'	=> $result['email'],
				'telephone'	=> $result['telephone'],
				'status'	=> $result['status'],
				'catatan'	=> $result['catatan'],
				'status_pengiriman'	=> $result['status_pengiriman'],
				'tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'      => $action
			);
		/*}else{
			$product_total -=1;
		}*/
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
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
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
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/salesorder/sogantung', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		
		$this->data['cetakexcel'] = $this->url->link('sale/salesorder/exporttoexcel', 'token=' . $this->session->data['token'] . $url , 'SSL');
		$this->data['cetakbiasa'] = $this->url->link('sale/salesorder/cetakbiasa','token='.$this->session->data['token'].$url,'SSL');
		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_tanggal']	= $filter_tanggal;
		$this->data['filter_gudang_id']	= $filter_gudang_id;
		$this->data['token'] = $this->session->data['token'];



		$this->template = 'sale/detailoredergantung_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	
	// end 
	public function test()
	{
		/*$this->load->model('gudang/product');
		$detail=$this->db->query("SELECT * FROM product_gudang where product_id=3294");
		print_r($detail->rows());*/
		$detail=$this->db->query("SELECT * FROM sales_order WHERE id=6245");
		echo "<pre>";
		print_r($detail->rows);
		echo "</pre>";
	}
	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Sales Order');

		$this->load->model('sale/salesorder');

		$this->getList();
	}




	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Sales Order');

		$this->load->model('sale/salesorder');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_salesorder->updateOrderPenjualan($this->request->post, array('id'=>$this->request->get['id']));

			$this->session->data['success'] = 'Sales order berhasil diperbarui';

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

			if (isset($this->request->get['filter_jenisorder'])) {
				$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function batal() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Sales Order');

		$this->load->model('sale/salesorder');

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

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}
		$this->load->model('user/user');
		/*$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$data['customer.sales']=$this->user->getId();
		}*/

		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),3);
			if($canceldata==1){
			if (isset($this->request->get['order_id'])) {
				$order_id=$this->request->get['order_id'];
				$url .= '&order_id=' . $this->request->get['order_id'];
			}else{
				$this->session->data['warning'] = 'Error: Sales Order tidak ditemukan';
				$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				if ($this->request->get['order_id']) {
					if(!empty($this->request->get['order_id'])){
					//print_r($this->request->post);
						$p=$this->model_sale_salesorder->getPenjualan(array('id'=>$this->request->get['order_id']));
						if($p['status'] == 1){
				      $this->model_sale_salesorder->cancelPenjualan($this->request->get['order_id'],$_POST['alasan_batal']);

							$this->session->data['success'] = 'Sukses: Sales Order berhasil dibatalkan';
						}else{
							$this->session->data['warning'] = 'Error: Sales Order tidak diijinkan untuk dibatalkan';
						}
					}
				}

				$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
			$this->data['token'] = $this->session->data['token'];

			$this->data['cancel']= $this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['action']= $this->url->link('sale/salesorder/batal', 'token=' . $this->session->data['token'].'&order_id='.$order_id . $url, 'SSL');
			$this->template = 'sale/tttkmr_batal.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render());
		}else{
			$this->session->data['warning'] = 'Anda tidak diijinkan untuk membatalkan Sales Order';
			$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}
	}

	private function getList() {
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
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
		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = null;
		}
		if (isset($this->request->get['filter_tanggalakhir'])) {
			$filter_tanggalakhir = $this->request->get['filter_tanggalakhir'];
		} else {
			$filter_tanggalakhir = null;
		}
		if (isset($this->request->get['filter_jenisorder'])) {
			$filter_jenisorder = $this->request->get['filter_jenisorder'];
		} else {
			$filter_jenisorder = null;
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
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_tanggalakhir'])) {
			$url .= '&filter_tanggalakhir=' . $this->request->get['filter_tanggalakhir'];
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
		$this->data['url'] = $this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

   		$this->data['insert'] = $this->url->link('sale/salesorder/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/salesorder/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

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

		$data = array(
			'sales_order.id'	=> empty($filter_order_id)?array('>=',1):$filter_order_id,
			'sales_order.customer_id'	=> empty($filter_customer_id)?array('>=',1):$filter_customer_id,
			//'sales_order.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):$filter_tanggal,
			'sales_order.date_added'	=> empty($filter_tanggal)?array('>','1901-01-01'):array('>=',$filter_tanggal,'<=',$filter_tanggalakhir),
			//'sales_order.gudang_id'	=>empty($filter_gudang_id)?array('>',0):$filter_gudang_id,
			'sales_order.gudang_id'	=>array('IN',$arrsql),
			'sales_order.hapus'	=>array('=',0),
			//'sales_rder.pengiriman'	=> empty($filter_shipping_method)?array('>',0):$filter_shipping_method,
			'sales_order_product.status_pengiriman'	=> empty($filter_status)?array('>=',1):$filter_status,
			'sales_order_product.product_id'	=> empty($filter_jenisorder)?array('>=',1):$filter_jenisorder,

		);
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$order=array();
		$order=array('sales_order_product.sales_order_id'=>'DESC');
		$results = $this->model_sale_salesorder->getListDetail($data,$order,$limit,$offset);
		$product_total = $this->model_sale_salesorder->getTotalListDetail($data);
		$this->load->model('keuangan/bank');
		$this->load->model('user/user');
		$canceldata=$this->model_user_user->getAksesData($this->user->getId(),3);
		$tso=$this->model_user_user->getAksesData($this->user->getId(),13);
		$setujuitgl=$this->model_user_user->getAksesData($this->user->getId(),15); // menyetujui perubahan tanggal sales order
		$setujuiharga=$this->model_user_user->getAksesData($this->user->getId(),16); // menyetujui perubahan harga sales order
		$this->data['fulldetail'] = $results;
		$hargasatuan=0;
		$permintaanpersetujuantgl=0;
		$permintaanpersetujuanharga=0;
		$this->data['perlupersetujuan']=array();
		$all = $this->model_sale_salesorder->getListDetail($data,$order,$limit=0,$offset=null);
		foreach($all as $result){
			$action = array();
			$hargasatuan=round($result['total']/$result['quantity']);
			if($result['persetujuanharga']==1 & $result['status_pengiriman']<>3 & $result['status_pengiriman']<>4 & $result['status_pengiriman']<>6){
				if($setujuiharga==1){
					$action[] = array(
						'text' => 'Lihat Harga SO',
						'badge'=> 'badge bg-navy',
						'href' => $this->url->link('sale/salesorder/lihatharga', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'].'&price='.$result['price'], 'SSL')
					);
					$action[] = array(
						'text' => 'Setujui Harga SO',
						'badge'=> 'badge bg-navy',
						'href' => $this->url->link('sale/salesorder/setujuiharga', 'token=' . $this->session->data['token'] . '&view=1&id=' . $result['id'].'&price='.$hargasatuan, 'SSL')
					);
					$action[] = array(
						'text' => 'Tolak Harga SO',
						'badge'=> 'badge bg-red',
						'href' => $this->url->link('sale/salesorder/tolakharga', 'token=' . $this->session->data['token'] . '&view=1&id=' . $result['id'], 'SSL')
					);
				}else{
					$action[] = array(
						'text' => 'Perlu Persetujuan Harga',
						'badge'=> 'badge bg-navy',
						'href' => $this->url->link('sale/salesorder/lihatharga', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'].'&price='.$result['price'], 'SSL')
					);
				}
			}			
			if($result['status'] ==5){
				$permintaanpersetujuantgl=1;
				if($setujuitgl==1){
					$action[] = array(
						'text' => 'Setujui Perubahan Tanggal',
						'badge'=> 'badge bg-purple',
						'href' => $this->url->link('sale/salesorder/setujuitgl', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'], 'SSL')
					);
					$action[] = array(
						'text' => 'Tolak Perubahan Tanggal',
						'badge'=> 'badge bg-red',
						'href' => $this->url->link('sale/salesorder/tolaktgl', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'], 'SSL')
					);
				}
			}
			if($result['status'] != 4){
				$action[] = array(
					'text' => 'Tampil',
					'badge'=> 'badge bg-green',
					'href' => $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'], 'SSL')
				);
			}
		
			if($result['status_pengiriman'] == 1 & $result['status'] == 1){
				if($canceldata == 1){
					$action[] = array(
						'text' => 'Batal',
						'badge'=> 'badge bg-red',
						'href' => $this->url->link('sale/salesorder/batal', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['sales_order_id'], 'SSL')
					);
				}
			}
			if($this->user->getUsername()=="pawit" && $result['status_pengiriman']==2){
				//$this->data['tutupso']=$this->url->link('sale/salesorder/tutupso', 'token=' . $this->session->data['token'] . '&view=3&tutup=' . $result['sales_order_id'], 'SSL');
				/*
				$action[] = array(
					'text' => 'Tutup SO',
					'badge'=> 'badge bg-yellow',
					'href' => $this->url->link('sale/sogantung/tutupso', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['sales_order_id'], 'SSL')
				);
				*/
			}else{
				//$this->data['tutupso']=$this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['sales_order_id'], 'SSL');
			}	
			if($this->user->getUsername()=="pawit" & $result['status_pengiriman']<>3){
				$this->data['tutupsobelumdikirim']=$this->url->link('sale/salesorder/tutupsobelumdikirim', 'token=' . $this->session->data['token'] . '&view=3&tutup=' . $result['sales_order_id'], 'SSL');
			}
			if($result['persetujuanharga']==1 & $result['status_pengiriman']!=4 OR $result['status']==5 ){
				$this->data['perlupersetujuan'][] = array(
					'id' => $result['id'],
					'no_so'        => $result['no_so'],
					'sales_order_id'        => $result['sales_order_id'],
					'idso'        => $result['sales_order_id'],
					'name'	=> $result['namacustomer'],
					'namagudang'	=> $result['namagudang'],
					'nameproduct'	=> $result['namaproduct'],
					'quantity'	=> $result['quantity'],
					'quantityterima'	=> $result['quantityterima'],
					'alasan_batal'	=> $result['alasan_batal'],
					'email'	=> $result['email'],
					'telephone'	=> $result['telephone'],
					'status'	=> $result['status'],
					'status_pengiriman'	=> $result['status_pengiriman'],
					'tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
					'tglso'	=>date('d/m/y',strtotime($result['date_added'])),
					'tglinput'	=>date('d/m/y',strtotime($result['date_modified'])),
					'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
					'setujuitgl'=>$result['status']==5?1:0,
					'permintaanpersetujuanharga'=>$result['persetujuanharga'],
					'action'      => $action
				);
			}
		}
		if($this->user->getUsername()=="pawist"){
			echo "<pre>";print_r(count($all));exit;
		}
		foreach ($results as $result) {
			$action = array();
			$hargasatuan=round($result['total']/$result['quantity']);
			if($result['persetujuanharga']==1 & $result['status_pengiriman']<>3 & $result['status_pengiriman']<>4 & $result['status_pengiriman']<>6){
				if($setujuiharga==1){
					$action[] = array(
						'text' => 'Lihat Harga SO',
						'badge'=> 'badge bg-navy',
						'href' => $this->url->link('sale/salesorder/lihatharga', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'].'&price='.$result['price'], 'SSL')
					);
					$action[] = array(
						'text' => 'Setujui Harga SO',
						'badge'=> 'badge bg-navy',
						'href' => $this->url->link('sale/salesorder/setujuiharga', 'token=' . $this->session->data['token'] . '&view=1&id=' . $result['id'].'&price='.$hargasatuan, 'SSL')
					);
					$action[] = array(
						'text' => 'Tolak Harga SO',
						'badge'=> 'badge bg-red',
						'href' => $this->url->link('sale/salesorder/tolakharga', 'token=' . $this->session->data['token'] . '&view=1&id=' . $result['id'], 'SSL')
					);
				}else{
					$action[] = array(
						'text' => 'Perlu Persetujuan Harga',
						'badge'=> 'badge bg-navy',
						'href' => $this->url->link('sale/salesorder/lihatharga', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'].'&price='.$result['price'], 'SSL')
					);
				}
			}			
			if($result['status'] ==5){
				$permintaanpersetujuantgl=1;
				if($setujuitgl==1){
					$action[] = array(
						'text' => 'Setujui Perubahan Tanggal',
						'badge'=> 'badge bg-purple',
						'href' => $this->url->link('sale/salesorder/setujuitgl', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'], 'SSL')
					);
					$action[] = array(
						'text' => 'Tolak Perubahan Tanggal',
						'badge'=> 'badge bg-red',
						'href' => $this->url->link('sale/salesorder/tolaktgl', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'], 'SSL')
					);
				}
			}
			if($result['status'] != 4){
				$action[] = array(
					'text' => 'Tampil',
					'badge'=> 'badge bg-green',
					'href' => $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['sales_order_id'], 'SSL')
				);
			}
		
			if($result['status_pengiriman'] == 1 & $result['status'] == 1){
				if($canceldata == 1){
					$action[] = array(
						'text' => 'Batal',
						'badge'=> 'badge bg-red',
						'href' => $this->url->link('sale/salesorder/batal', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['sales_order_id'], 'SSL')
					);
				}
			}
			if($this->user->getUsername()=="pawit" && $result['status_pengiriman']==2){
				//$this->data['tutupso']=$this->url->link('sale/salesorder/tutupso', 'token=' . $this->session->data['token'] . '&view=3&tutup=' . $result['sales_order_id'], 'SSL');
				/*
				$action[] = array(
					'text' => 'Tutup SO',
					'badge'=> 'badge bg-yellow',
					'href' => $this->url->link('sale/sogantung/tutupso', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['sales_order_id'], 'SSL')
				);
				*/
			}else{
				//$this->data['tutupso']=$this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] . '&view=3&order_id=' . $result['sales_order_id'], 'SSL');
			}	
			if($this->user->getUsername()=="pawit" & $result['status_pengiriman']<>3){
				$this->data['tutupsobelumdikirim']=$this->url->link('sale/salesorder/tutupsobelumdikirim', 'token=' . $this->session->data['token'] . '&view=3&tutup=' . $result['sales_order_id'], 'SSL');
			}
			if($result['persetujuanharga']!=1 & $result['status']!=5 ){
				$this->data['penjualans'][] = array(
					'id' => $result['id'],
					'no_so'        => $result['no_so'],
					'sales_order_id'        => $result['sales_order_id'],
					'idso'        => $result['sales_order_id'],
					'name'	=> $result['namacustomer'],
					'namagudang'	=> $result['namagudang'],
					'nameproduct'	=> $result['namaproduct'],
					'quantity'	=> $result['quantity'],
					'quantityterima'	=> $result['quantityterima'],
					'alasan_batal'	=> $result['alasan_batal'],
					'email'	=> $result['email'],
					'telephone'	=> $result['telephone'],
					'status'	=> $result['status'],
					'status_pengiriman'	=> $result['status_pengiriman'],
					'tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
					'tglso'	=>date('d/m/y',strtotime($result['date_added'])),
					'tglinput'	=>date('d/m/y',strtotime($result['date_modified'])),
					'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
					'setujuitgl'=>$permintaanpersetujuantgl,
					'permintaanpersetujuanharga'=>$result['persetujuanharga'],
					'action'      => $action
				);
			}
		}

		if($this->user->getUsername()=="pawitx"){
			echo "<pre>";print_r($this->data['penjualans']);exit;
		}
		$this->data['tso']=$tso;
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
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_tanggalakhir'])) {
			$url .= '&filter_tanggalakhir=' . $this->request->get['filter_tanggalakhir'];
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

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_tanggal']	= $filter_tanggal;
		$this->data['filter_tanggalakhir'] = $filter_tanggalakhir;
		$this->data['token'] = $this->session->data['token'];



		$this->template = 'sale/detailoreder_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('sale/salesorder');

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
				'no_so'         => array('LIKE',$filter_order_id),

			);
			$offset=0;
			$limit=10;

			$results = $this->model_sale_salesorder->getPenjualans(array(),array(),$data,array(),10,0);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_so']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function tampil(){
		$this->document->setTitle('Sales Order');
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
				$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/salesorder');
		$this->load->model('sale/customer');
		$column=array('sales_order.*','customer.name','customer.telephone','customer.email',"gudang.nama",'customer.alamat');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'sales_order.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=> 'sales_order.gudang_id',
			'secondtable'	=> 'gudang.gudang_id',
		);

		$data = array(
			'sales_order.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_salesorder->getPenjualanDetail($column,$join,$data,array());

		$sales=$this->model_user_user->getUser($trans['sales']);
		$trans['sales']=$sales['firstname'];
		$products=$this->model_sale_salesorder->getPenjualanProducts(array('sales_order_id'	=> $order_id));

		// dicetak oleh
		$cetak=$this->model_user_user->getUser($trans['user_cetak']);
		$this->data['pencetak']=$cetak['firstname'];
		$this->data['order']=$trans;
		$this->data['products']=$products;
		$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);

		$this->data['fulldetail']=array(
			'order'	=> $trans,
			'products'	=> $products,
			'address'	=> $this->data['address']
		);
		$this->load->model('marketplace/marketplace');
		$marketplace=$this->model_marketplace_marketplace->getdetail($trans['marketplace']);
		$this->data['marketplace']=$marketplace==null?'-':$marketplace['nama'];
		$this->data['printer']=$this->config->get('config_printer');
		$this->data['printerstatus']=$this->config->get('config_printer_status');
		$this->data['token'] = $this->session->data['token'];
		//print_r($this->model_sale_customer->getAddress($trans['address_id']));
		$this->data['cancel']= $this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['suratjalan']= $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=2'. $url, 'SSL');
		$this->data['invoice']= $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=3'. $url, 'SSL');
		//if($this->request->get['view'] == 1){
			$this->template = 'sale/order_info.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

		/*}
		if($this->request->get['view'] == 2){
			$this->template = 'sale/suratjalan.tpl';
		}
		if($this->request->get['view'] == 3){
			$this->template = 'sale/invoice.tpl';
		}*/


		//$kantor=$this->user->getKantor();
		$this->response->setOutput($this->render());
	}

	public function lihatharga(){
		$this->document->setTitle('Sales Order');
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
				$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/salesorder');
		$this->load->model('sale/customer');
		$column=array('sales_order.*','customer.name','customer.telephone','customer.email',"gudang.nama",'customer.alamat');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'sales_order.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=> 'sales_order.gudang_id',
			'secondtable'	=> 'gudang.gudang_id',
		);

		$data = array(
			'sales_order.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_salesorder->getPenjualanDetail($column,$join,$data,array());

		$sales=$this->model_user_user->getUser($trans['sales']);
		$trans['sales']=$sales['firstname'];
		$products=$this->model_sale_salesorder->getPenjualanProducts(array('sales_order_id'	=> $order_id));

		// dicetak oleh
		$cetak=$this->model_user_user->getUser($trans['user_cetak']);
		$this->data['pencetak']=$cetak['firstname'];
		$this->data['order']=$trans;
		$this->data['products']=$products;
		$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);

		$this->data['fulldetail']=array(
			'order'	=> $trans,
			'products'	=> $products,
			'address'	=> $this->data['address']
		);
		$this->load->model('marketplace/marketplace');
		$marketplace=$this->model_marketplace_marketplace->getdetail($trans['marketplace']);
		$this->data['marketplace']=$marketplace==null?'-':$marketplace['nama'];
		$this->data['printer']=$this->config->get('config_printer');
		$this->data['printerstatus']=$this->config->get('config_printer_status');
		$this->data['token'] = $this->session->data['token'];
		//print_r($this->model_sale_customer->getAddress($trans['address_id']));
		$this->data['cancel']= $this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['suratjalan']= $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=2'. $url, 'SSL');
		$this->data['invoice']= $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=3'. $url, 'SSL');
			$this->template = 'sale/order_info_harga.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

		$kantor=$this->user->getKantor();
		$this->response->setOutput($this->render());
	}
	
	public function tampilcetak(){
		$this->document->setTitle('Sales Order');
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
				$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/salesorder');
		$this->load->model('sale/customer');
		$column=array('sales_order.*','customer.name','customer.telephone','customer.email',"gudang.nama",'customer.alamat');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'sales_order.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);
		$join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=> 'sales_order.gudang_id',
			'secondtable'	=> 'gudang.gudang_id',
		);

		$data = array(
			'sales_order.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_salesorder->getPenjualanDetail($column,$join,$data,array());
		// dicetak oleh
		$cetak=$this->model_user_user->getUser($trans['user_cetak']);
		$this->data['pencetak']=$cetak['firstname'];

		$sales=$this->model_user_user->getUser($trans['sales']);
		$trans['sales']=$sales['firstname'];
		$products=$this->model_sale_salesorder->getPenjualanProducts(array('sales_order_id'	=> $order_id));

		$this->data['order']=$trans;
		$this->data['products']=$products;
		$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);

		$this->data['fulldetail']=array(
			'order'	=> $trans,
			'products'	=> $products,
			'address'	=> $this->data['address']
		);
		
		if($this->user->getUsername()=="pawitd"){
			echo "<pre>";
			print_r($trans);
			exit;
		}

		$this->load->model('marketplace/marketplace');
		$marketplace=$this->model_marketplace_marketplace->getdetail($trans['marketplace']);
		$this->data['marketplace']=$marketplace==null?'-':$marketplace['nama'];

		$this->data['printer']=$this->config->get('config_printer');
		$this->data['printerstatus']=$this->config->get('config_printer_status');
		$this->data['token'] = $this->session->data['token'];
		//print_r($this->model_sale_customer->getAddress($trans['address_id']));
		$this->data['cancel']= $this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['suratjalan']= $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=2'. $url, 'SSL');
		$this->data['invoice']= $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=3'. $url, 'SSL');
		if($this->request->get['view'] == 1){
			$this->logcetak($order_id);
			$this->template = 'sale/order_info_cetak.tpl';
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


		$kantor=$this->user->getKantor();
		$this->response->setOutput($this->render());
	}

	// baru 6 Desember 2019
	public function logcetak($id){
		$this->db->update('sales_order',array('user_cetak'=>$this->user->getId(),'cetak'=>1),array('id'=>$id));
	}

	// end baru
	


	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Sales Order');

		$this->load->model('sale/salesorder');
		$this->load->model('catalog/gudang');
		$this->load->model('marketplace/marketplace');
		$this->data['marketplace']=$this->model_marketplace_marketplace->getAll(array());
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			//print_r($this->request->post);
			if($this->user->getUsername()=="pawits"){
				echo "<pre>";
				print_r($this->request->post);
				exit;
			}
			$order= $this->model_sale_salesorder->addPenjualan($this->request->post);
			if($this->user->getUsername()=="pawits"){
				echo "<pre>";
				print_r($order);
				exit;
			}
			$this->session->data['success'] = 'Sukses: Sales Order berhasil disimpan dengan ID '.$order;
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
			if (isset($this->request->get['filter_jenisorder'])) {
				$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
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

		$this->data['cancel']= $this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('sale/salesorder/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->template = 'sale/order_form.tpl';
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

		public function detail(){
			$hasil = array();

			$this->load->model('pembelian/permintaanpembelian');
			if(isset($this->request->get['id'])){
				if(!empty($this->request->get['id'])){

				$this->load->model('sale/salesorder');
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
				$trans=$this->model_sale_salesorder->getPenjualanDetail($column,$join,$data,array());

				$sales=$this->model_user_user->getUser($trans['sales']);
				$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
				$trans['namasales']=$sales['firstname'];
				$trans['namagudang']=$gudang['nama'];
				$products=$this->model_sale_salesorder->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id'],'product_gudang.gudang_id'=>$trans['gudang_id']));

				//$this->data['order']=$trans;
				//$this->data['products']=$products;
				$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);


				$hasil=array(
					'order'	=> $trans,
					'products'	=> $products,
					'address'	=> $this->data['address']
				);
			}
		}if(isset($this->request->get['customer_id'])){
			if(!empty($this->request->get['customer_id'])){

			$this->load->model('sale/salesorder');
			$this->load->model('sale/customer');
			$column=array('sales_order.*');
			/*$join=array();
			$join[]=array(
				'tablename'	=> 'customer',
				'firsttable'	=> 'sales_order.customer_id',
				'secondtable'	=> 'customer.customer_id',
			);*/

			$data = array(
				'sales_order.customer_id'	=> $this->request->get['customer_id'],
				'sales_order.status_pengiriman'	=> array('<',3)
			);
			$this->load->model('user/user');
			$this->load->model('catalog/gudang');
			$transdets=$this->model_sale_salesorder->getPenjualans($column,$join,$data,array(),0,null);
			foreach($transdets as $t){
				//$
				$sales=$this->model_user_user->getUser($trans['sales']);
				$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
				$trans['namasales']=$sales['firstname'];
				$trans['namagudang']=$gudang['nama'];
				$products=$this->model_sale_salesorder->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));

				//$this->data['order']=$trans;
				//$this->data['products']=$products;
				$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);
			}


			$hasil=array(
				'order'	=> $trans,
				'products'	=> $products,
				'address'	=> $this->data['address']
			);
		}
	}
			$this->response->setOutput(json_encode($hasil));


		}
		public function detailtanpasj(){
			//getListDetail($where,$order,$limit=0,$offset=null)
			$hasil = array();

				$this->load->model('sale/salesorder');
				if(isset($this->request->get['customer_id'])){
					if(!empty($this->request->get['customer_id'])){

					$this->load->model('sale/salesorder');




					/*$hasil=array(
						'order'	=> $trans,
						'products'	=> $products,
						'address'	=> $this->data['address']
					);*/
					$tglawal=$this->request->get['tahun_so'].'-'.$this->request->get['bulan_so'].'-01';
					$tglakhir=$this->request->get['tahun_so'].'-'.$this->request->get['bulan_so'].'-'.date('t');
					$hasil=array(
						'order'	=> array(),
						'products'	=> $this->model_sale_salesorder->getSoTanpaSj($this->request->get['customer_id'],$this->request->get['gudang_id'],$this->request->get['bulan_so'],$this->request->get['tahun_so']),
						'address'	=> array()
					);
				}
			}
			$this->response->setOutput(json_encode($hasil));
		}
		public function detailtanpasproforma(){
			//getListDetail($where,$order,$limit=0,$offset=null)
			$hasil = array();

				$this->load->model('sale/salesorder');
				if(isset($this->request->get['customer_id'])){
					if(!empty($this->request->get['customer_id'])){

					$this->load->model('sale/salesorder');
					/*$hasil=array(
						'order'	=> $trans,
						'products'	=> $products,
						'address'	=> $this->data['address']
					);*/
					$hasil=array(
						'order'	=> array(),
						'products'	=> $this->model_sale_salesorder->getSoTanpaProforma($this->request->get['customer_id'],$this->request->get['gudang_id']),
						'address'	=> array()
					);
				}
			}
			$this->response->setOutput(json_encode($hasil));
		}

		public function detailsudahdikirim(){
			//getListDetail($where,$order,$limit=0,$offset=null) 
			$hasil = array();

				$this->load->model('sale/penjualan');
				if(isset($this->request->get['customer_id'])){
					if(!empty($this->request->get['customer_id'])){

					//$this->load->model('sale/salesorder');




					/*$hasil=array(
						'order'	=> $trans,
						'products'	=> $products,
						'address'	=> $this->data['address']
					);*/
					$hasil=array(
						'order'	=> array(),
						'products'	=> $this->model_sale_penjualan->getSjTerkirim($this->request->get['customer_id'],$this->request->get['gudang_id'],$this->request->get['no_so']),
						'address'	=> array()
					);
				}
			}
			$this->response->setOutput(json_encode($hasil));
		}
		public function sosudahdikirim (){
			$rests = array();
	
			$this->load->model('sale/salesorder');
	
				if (isset($this->request->get['q'])) {
					$no_so = $this->request->get['q'];
				} else {
					$no_so = '';
				}
	
				if (isset($this->request->get['customer_id'])) {
					$customer_id = $this->request->get['customer_id'];
				} else {
					$customer_id = 0;
				}

				if (isset($this->request->get['gudang_id'])) {
					$gudang_id = $this->request->get['gudang_id'];
				} else {
					$gudang_id = 0;
				}
	
				
				if($customer_id > 0 & $gudang_id > 0){
	
					/*$data = array(
						'no_so'         => array('LIKE',$filter_order_id),
						'customer_id'	=> $customer_id,
						'gudang_id'	=> $gudang_id
		
					);
					$offset=0;
					$limit=10;
		
					$results = $this->model_sale_salesorder->getPenjualans(array(),array(),$data,array(),10,0);
					*/
					$results=$this->model_sale_salesorder->getSoSudahdikirim($no_so,$customer_id,$gudang_id);
					foreach($results as $r){
						$rests[]=array(
							'id'	=> $r['sales_order_id'],
							'text'	=> $r['no_so']
						);
					}
				}
			$this->response->setOutput(json_encode($rests));
		}
		public function pembelian() {
			$this->load->language('catalog/pembelian');

			$this->document->setTitle('Sales Order Penjualan');

			$this->load->model('sale/salesorder');
			$this->load->model('pembelian/permintaanpembelian');

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

			if (isset($this->request->get['filter_jenisorder'])) {
				$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				//print_r($this->request->post);
	      $pid= $this->model_pembelian_permintaanpembelian->addPermintaanPembelian($this->request->post);
				$this->model_sale_salesorder->updatePenjualanProduct(array('referensi'=>$pid,'jenisref'=>1),array('id' => $this->request->get['id']));

				$this->session->data['success'] = 'Sukses: Permintaan pembelian berhasil disimpan <a href="'.$this->url->link('pembelian/permintaanpembelian/tampil', 'token=' . $this->session->data['token'].'&id='.$pid, 'SSL').'">Lihat</a>';



				$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}

			$this->load->model('catalog/gudang');

			$this->load->model('sale/salesorder');
			$this->load->model('sale/customer');
			$column=array('sales_order.*','customer.name','customer.telephone','customer.email');
			$join=array();
			$join[]=array(
				'tablename'	=> 'customer',
				'firsttable'	=> 'sales_order.customer_id',
				'secondtable'	=> 'customer.customer_id',
			);

			$data = array(
				'sales_order.id'	=> $this->request->get['order_id'],

			);
			$this->load->model('user/user');
			$this->load->model('catalog/gudang');
			$trans=$this->model_sale_salesorder->getPenjualanDetail($column,$join,$data,array());

			$sales=$this->model_user_user->getUser($trans['sales']);
			$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
			$trans['namasales']=$sales['firstname'];
			$trans['namagudang']=$gudang['nama'];
			$products=$this->model_sale_salesorder->getPenjualanProducts(array('sales_order_product.id'	=> $this->request->get['id']));
			$this->data['order']=$trans;
			$this->data['products']=$products;


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

			$url .= '&id=' . $this->request->get['id'];
			$url .= '&order_id=' . $this->request->get['order_id'];
			$this->data['cancel']= $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['action']= $this->url->link('sale/salesorder/pembelian', 'token=' . $this->session->data['token'] . $url, 'SSL');

			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
			} else {
				$this->data['error_warning'] = '';
			}


			$this->template = 'sale/pembelian_form.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render());

		}
		public function produksi() {
			$this->load->language('catalog/pembelian');

			$this->document->setTitle('Sales Order Penjualan');

			$this->load->model('sale/salesorder');
			$this->load->model('produksi/permintaanproduksi');

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

			if (isset($this->request->get['filter_jenisorder'])) {
				$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
			}
			if (isset($this->request->get['filter_statustabung'])) {
				$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				//print_r($this->request->post);
	      $pid= $this->model_produksi_permintaanproduksi->addPermintaanPembelian($this->request->post);
				$this->model_sale_salesorder->updatePenjualanProduct(array('referensi'=>$pid,'jenisref'=>2),array('id' => $this->request->get['id']));

				$this->session->data['success'] = 'Sukses: Permintaan produksi berhasil disimpan <a href="'.$this->url->link('produksi/permintaanproduksi/tampil', 'token=' . $this->session->data['token'].'&id='.$pid, 'SSL').'">Lihat</a>';



				$this->redirect($this->url->link('sale/salesorder', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}

			$this->load->model('catalog/gudang');

			$this->load->model('sale/salesorder');
			$this->load->model('sale/customer');
			$column=array('sales_order.*','customer.name','customer.telephone','customer.email');
			$join=array();
			$join[]=array(
				'tablename'	=> 'customer',
				'firsttable'	=> 'sales_order.customer_id',
				'secondtable'	=> 'customer.customer_id',
			);

			$data = array(
				'sales_order.id'	=> $this->request->get['order_id'],

			);
			$this->load->model('user/user');
			$this->load->model('catalog/gudang');
			$trans=$this->model_sale_salesorder->getPenjualanDetail($column,$join,$data,array());

			$sales=$this->model_user_user->getUser($trans['sales']);
			$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
			$trans['namasales']=$sales['firstname'];
			$trans['namagudang']=$gudang['nama'];
			$products=$this->model_sale_salesorder->getPenjualanProducts(array('sales_order_product.id'	=> $this->request->get['id']));
			$this->data['order']=$trans;
			$this->data['products']=$products;


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

			$url .= '&id=' . $this->request->get['id'];
			$url .= '&order_id=' . $this->request->get['order_id'];
			$this->data['cancel']= $this->url->link('sale/salesorder/tampil', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['action']= $this->url->link('sale/salesorder/produksi', 'token=' . $this->session->data['token'] . $url, 'SSL');

			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
			} else {
				$this->data['error_warning'] = '';
			}


			$this->template = 'sale/produksi_form.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render());

		}
}
?>
