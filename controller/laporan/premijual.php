<?php
class ControllerLaporanPremijual extends Controller {
	private $error = array();

	public function downloadrincian() {
		$this->load->language('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/user');

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}
		if (isset($this->request->get['filter_periode'])) {
			$filter_periode = $this->request->get['filter_periode'];
		} else {
			$filter_periode = '';
		}


	if (isset($this->request->get['page'])) {
		$page = $this->request->get['page'];
	} else {
		$page = 1;
	}

	$url = '';
	if (isset($this->request->get['filter_name'])) {
		$url .= '&filter_name=' . $this->request->get['filter_name'];
	}
	if (isset($this->request->get['filter_periode'])) {
		$url .= '&filter_periode=' . $this->request->get['filter_periode'];
	}


	if (isset($this->request->get['page'])) {
		$url .= '&page=' . $this->request->get['page'];
	}


	$this->data['users'] = array();
	if(!empty($filter_periode)){
		$this->load->model('kepegawaian/periode');
		$this->load->model('kepegawaian/premijual');
		$periode=$this->model_kepegawaian_periode->getPeriode($filter_periode);
		$this->data['periode']=$periode;

		$date_start=date('Y-m-01',strtotime($periode['tgl_awal']));
		$date_end=date('Y-m-t',strtotime($periode['tgl_awal']));
		/*if($filter_periode == 48){
			$date_start='2017-06-01';
			$date_end='2017-06-25';
		}*/

		$this->data['date_start']=date('d/m/y',strtotime($date_start));
		$this->data['date_end']=date('d/m/y',strtotime($date_end));
		//akumulasi premijual kernet
		//akumulasi premijual sopir

		$data = array(
			'filter_name'	=> $filter_name,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		$sql="SELECT id as user_id,nama as firstname, jabatan FROM supir_kenek_new WHERE hapus=0 ";
			if(!empty($filter_name)){
				$sql .= " AND lower(nama) LIKE '%".utf8_strtolower($filter_name)."%'";
			}
		$sql.="ORDER BY jabatan asc,nama asc";
		$data=$this->db->query($sql);
		$results=$data->rows;
		$user_total = count($results);
		//$user_total = $this->model_user_user->getTotalUsers($data);
		//$results = $this->model_user_user->getUsers($data);
		$details=[];
		foreach ($results as $result) {
			$premi=$this->model_kepegawaian_premijual->hitungpremi_breakdwon($result['user_id'],$date_start,$date_end);
			$details=$this->model_kepegawaian_premijual->details($result['user_id'],$date_start,$date_end);
			$action = array();
			if($premi['total']>0){
				$this->data['users'][] = array(
					'user_id'    => $result['user_id'],
					'firstname'   => $result['firstname'],
					'akumulasisopir' => $premi['akumulasi'],
					'total'	=> $this->currency->format($premi['total']),
					'details'=>$details
				);
			}
		}
	}

	//echo "<pre>";print_r($this->data['users']);exit;

	$this->data['heading_title'] = $this->language->get('heading_title');

	$this->data['text_no_results'] = $this->language->get('text_no_results');

	$this->data['column_username'] = $this->language->get('column_username');
	$this->data['column_status'] = $this->language->get('column_status');
	$this->data['column_date_added'] = $this->language->get('column_date_added');
	$this->data['column_action'] = $this->language->get('column_action');

	$this->data['button_insert'] = $this->language->get('button_insert');
	$this->data['button_delete'] = $this->language->get('button_delete');

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
		$url .= '&filter_name=' . $this->request->get['filter_name'];
	}
	if (isset($this->request->get['filter_periode'])) {
		$url .= '&filter_periode=' . $this->request->get['filter_periode'];
	}


	$pagination = new Pagination();
	$pagination->total = $user_total;
	$pagination->page = $page;
	$pagination->limit = $this->config->get('config_admin_limit');
	$pagination->text = $this->language->get('text_pagination');
	$pagination->url = $this->url->link('laporan/premijual', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

	$this->data['pagination'] = $pagination->render();

	$this->data['sort'] = $sort;
	$this->data['order'] = $order;
	$this->data['action'] = $this->url->link('laporan/premijual/import', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
	$this->data['downloadrincian'] = $this->url->link('laporan/premijual/downloadrincian', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
	$this->load->model('kepegawaian/periode');

	$this->data['periodes'] = $this->model_kepegawaian_periode->getPeriodes(array());
	$this->template = 'laporan/premijual_excel.tpl';
	$this->children = array(
		'common/header',
		'common/footer'
	);

	$this->response->setOutput($this->render());
  }

  	public function index() {
    	$this->load->language('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/user');
		/*
		if($this->user->getUsername()=="pawit"){
			$this->getList();
		}else{
			echo "maintenance";
		}*/
		$this->getList();    	
  	}



  	private function getList() {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}
			if (isset($this->request->get['filter_periode'])) {
				$filter_periode = $this->request->get['filter_periode'];
			} else {
				$filter_periode = '';
			}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_periode'])) {
			$url .= '&filter_periode=' . $this->request->get['filter_periode'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


    	$this->data['users'] = array();
		if(!empty($filter_periode)){
			$this->load->model('kepegawaian/periode');
			$this->load->model('kepegawaian/premijual');
			$periode=$this->model_kepegawaian_periode->getPeriode($filter_periode);
			$this->data['periode']=$periode;

			$date_start=date('Y-m-01',strtotime($periode['tgl_awal']));
			$date_end=date('Y-m-t',strtotime($periode['tgl_awal']));
			/*if($filter_periode == 48){
				$date_start='2017-06-01';
				$date_end='2017-06-25';
			}*/

			$this->data['date_start']=date('d/m/y',strtotime($date_start));
			$this->data['date_end']=date('d/m/y',strtotime($date_end));
			//akumulasi premijual kernet
			//akumulasi premijual sopir

			$data = array(
				'filter_name'	=> $filter_name,
				'start' => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit' => $this->config->get('config_admin_limit')
			);
			$sql="SELECT id as user_id,nama as firstname, jabatan FROM supir_kenek_new WHERE hapus=0 ";
				if(!empty($filter_name)){
					$sql .= " AND lower(nama) LIKE '%".utf8_strtolower($filter_name)."%'";
				}
			$sql.="ORDER BY jabatan asc,nama asc";
			$data=$this->db->query($sql);
			$results=$data->rows;
			$user_total = count($results);
			//$user_total = $this->model_user_user->getTotalUsers($data);
			//$results = $this->model_user_user->getUsers($data);
			
			foreach ($results as $result) {
				$premi=$this->model_kepegawaian_premijual->hitungpremi_new($result['user_id'],$date_start,$date_end);
				$action = array();
	      		$this->data['users'][] = array(
					'user_id'    => $result['user_id'],
					'firstname'   => $result['firstname'],
					'akumulasisopir' => $premi['akumulasi'],
					'total'	=> $this->currency->format($premi['total'])
				);
			}
		}


		if($this->user->getUsername()=="pawit"){
			
		}else{
			//echo "<pre>";print_r($premi);exit;
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_username'] = $this->language->get('column_username');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');

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
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_periode'])) {
			$url .= '&filter_periode=' . $this->request->get['filter_periode'];
		}


		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/premijual', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['action'] = $this->url->link('laporan/premijual/import', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$this->data['downloadrincian'] = $this->url->link('laporan/premijual/downloadrincian', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$this->load->model('kepegawaian/periode');

    	$this->data['periodes'] = $this->model_kepegawaian_periode->getPeriodes(array());
		$this->template = 'laporan/premijual_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	  }
	  
	  public function import(){
		$allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
		$sjsupir=array();
		$sjproduk=array();
		$sjkenek1=array();
		$sjkenek2=array();
		$a=1;
		$iu=array();
		$pengiriman=1;
		$sales_id=0;
		if(in_array($_FILES["file"]["type"],$allowedFileType)){
	  
			  $targetPath = DIR_SYSTEM.'uploads/'.$_FILES['file']['name'];
			  move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
			  
			  $Reader = new SpreadsheetReader($targetPath);
			  
			  //$sheetCount = count($Reader->sheets());
			  $sheetCount = 1;
			  for($i=0;$i<$sheetCount;$i++)
			  {
				  
				  $Reader->ChangeSheet(0);
				  
				  foreach ($Reader as $Row)
				  	{
					  if($a>1){						
						if(!empty($Row[2])){
							$sja=str_replace(".","",$Row[1]);
							$sj=str_replace("DO","",$sja);
							if(strtolower($Row[2])=="adam_n"){
								$supir=1;
							}else if(strtolower($Row[2])=="fauzi"){
								$supir=2;
							}else if(strtolower($Row[2])=="kusworo"){
								$supir=3;
							}else if(strtolower($Row[2])=="suhada"){
								$supir=4;
							}else if(strtolower($Row[2])=="suhanda"){
								$supir=4;
							}else if(strtolower($Row[2])=="tuparjan"){
								$supir=5;
							}else if(strtolower($Row[2])=="endang"){
								$supir=11;
							}else if(strtolower($Row[2])=="cadangan"){
								$supir=12;
							}else if(strtolower($Row[2])=="backup"){
								$supir=12;
							}else if(strtolower($Row[2])=="adnan"){
								$supir=13;
							}else if(strtolower($Row[2])=="marwan"){
								$supir=17;
							}else if(strtolower($Row[2])=="padji"){
								$supir=19;
							}else if(strtolower($Row[2])=="ruli"){
								$supir=21;
							}else{
								$supir=0;
							}
							
							/*
							if(strtolower($Row[3])=="sriyanto"){
								$kenek=6;
							}else if(strtolower($Row[3])=="ujang"){
								$kenek=7;
							}else if(strtolower($Row[3])=="hartanto"){
								$kenek=8;
							}else if(strtolower($Row[3])=="ade"){
								$kenek=9;
							}else if(strtolower($Row[3])=="haryadih"){
								$kenek=10;
							}else if(strtolower($Row[3])=="adam"){
								$kenek=14;
							}else if(strtolower($Row[3])=="sarifudin"){
								$kenek=15;
							}else if(strtolower($Row[3])=="pawit"){
								$kenek=16;
							}else if(strtolower($Row[3])=="roni"){
								$kenek=18;
							}else{
								$kenek=0;
							}
							*/

							if(!empty($Row[7])){
								$sjsupir=array(
									'nomor'=>$sj,
									'tanggal' =>date('Y-m-d',strtotime($Row[0])),
									//'tanggal'=>$Row[0],
									'namasupir'=>$Row[3],
									'supir'=>$supir,
									'status'=>1,
									'qty'=>$Row[6],
									'kodepremi'=>$Row[7],
								);
								$this->db->insert('sj_supir',$sjsupir);
								$sjproduk=array(
									'nomor'=>$sj,
									'nama'=>$Row[5],
									'qty'=>$Row[6],
									'kodepremi'=>$Row[7],
									'tgl' =>date('Y-m-d',strtotime($Row[0])),
								);
								$this->db->insert('sj_product',$sjproduk);

								if(!empty($Row[3])){
									if(strtolower($Row[3])=="sriyanto"){
										$kenek=6;
									}else if(strtolower($Row[3])=="ujang"){
										$kenek=7;
									}else if(strtolower($Row[3])=="hartanto"){
										$kenek=8;
									}else if(strtolower($Row[3])=="haryadih"){
										$kenek=10;
									}else if(strtolower($Row[3])=="adam"){
										$kenek=14;
									}else if(strtolower($Row[3])=="sarifudin"){
										$kenek=15;
									}else if(strtolower($Row[3])=="pawit"){
										$kenek=16;
									}else if(strtolower($Row[3])=="roni"){
										$kenek=18;
									}else if(strtolower($Row[3])=="syaifudin"){
										$kenek=20;
									}else if(strtolower($Row[3])=="anakgudang"){
										$kenek=22;
									}else if(strtolower($Row[3])=="ivqi"){
										$kenek=23;
									}else{
										$supir=0;
										$kenek=0;
									}
									$sjkenek1=array(
										'nomor'=>$sj,
										'kernet'=>$kenek,
										'tgl' =>date('Y-m-d',strtotime($Row[0])),
									);
									$this->db->insert('sj_kernet',$sjkenek1);
								}
								
								if(!empty($Row[4])){
									if(strtolower($Row[4])=="sriyanto"){
										$kenek2=6;
									}else if(strtolower($Row[4])=="ujang"){
										$kenek2=7;
									}else if(strtolower($Row[4])=="hartanto"){
										$kenek2=8;
									}else if(strtolower($Row[4])=="haryadih"){
										$kenek2=10;
									}else if(strtolower($Row[4])=="adam"){
										$kenek2=14;
									}else if(strtolower($Row[4])=="sarifudin"){
										$kenek2=15;
									}else if(strtolower($Row[4])=="pawit"){
										$kenek2=16;
									}else if(strtolower($Row[4])=="roni"){
										$kenek2=18;
									}else if(strtolower($Row[4])=="syaifudin"){
										$kenek2=20;
									}else if(strtolower($Row[3])=="anakgudang"){
										$kenek2=22;
									}else if(strtolower($Row[3])=="ivqi"){
										$kenek2=23;
									}else{
										$supir=0;
										$kenek2=0;
									}
									$sjkenek2=array(
										'nomor'=>$sj,
										'kernet'=>$kenek2,
										'tgl' =>date('Y-m-d',strtotime($Row[0])),
									);
									$this->db->insert('sj_kernet',$sjkenek2);
								}

							}

						}
					  }
					  $a++;
				   }
			   }
			   $uniq=array();
			   $uniq=array_unique($sjsupir);
			   //echo "<pre>";print_r(($sjsupir));exit;
			   //echo "<pre>";print_r(($sjkenek2));exit;
			   $this->session->data['success'] = 'Data berhasil di import.';
			   unlink($targetPath);
		}
		else
		{ 
			  $type = "error";
			  $message = "Invalid File Type. Upload Excel File.";
			  $this->session->data['success'] = $message;
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

		if (isset($this->request->get['filter_periode'])) {
			$url .= '&filter_periode=' . $this->request->get['filter_periode'];
		}


		$this->redirect($this->url->link('laporan/premijual', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	  }

		public function autocomplete() {
			$json = array();

			$this->load->model('user/user');

				if (isset($this->request->get['q'])) {
					$filter_name = $this->request->get['q'];
				} else {
					$filter_name = null;
				}

				if (isset($this->request->get['j'])) {
					$filter_jabatan = $this->request->get['j'];
				} else {
					$filter_jabatan = null;
				}

				if (isset($this->request->get['limit'])) {
					$limit = $this->request->get['limit'];
				} else {
					$limit = 20;
				}

				$data = array(
				'filter_name'	  => $filter_name,
				'filter_jabatan'	=> $filter_jabatan,
				'start'	=>0,
				'limit'	=> 10
					//'start'               => 0,
					//'limit'               => $limit
				);
				$sql="SELECT id,nama as firstname, jabatan FROM supir_kenek_new WHERE hapus=0 ";
				if(!empty($filter_name)){
					$sql .= " AND lower(nama) LIKE '%".utf8_strtolower($filter_name)."%'";
				}
				$data=$this->db->query($sql);
				$results=$data->rows;
				//$results = $this->model_user_user->getUsers($data);

				foreach ($results as $result) {
					$json[] = array(
						'id' => strip_tags(html_entity_decode($result['firstname'], ENT_QUOTES, 'UTF-8')),
						'text'       => strip_tags(html_entity_decode($result['firstname'], ENT_QUOTES, 'UTF-8')),

					);
				}


			$this->response->setOutput(json_encode($json));
		}
}
?>
