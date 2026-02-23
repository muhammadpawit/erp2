<?php
class ControllerLaporanJurnalmanual extends Controller {
	private $error=array();

	

	public function index() {
		
		$this->document->setTitle('Jurnal Memo');
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_date_start ="";
		}

		if (isset($this->request->get['filter_ref'])) {
			$filter_ref = $this->request->get['filter_ref'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_ref =null;
		}

		if (isset($this->request->get['filter_id'])) {
			$filter_id = $this->request->get['filter_id'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_id =0;
		}

	
		if (isset($this->request->get['filter_keterangan'])) {
			$filter_keterangan = $this->request->get['filter_keterangan'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_keterangan =null;
		}
		
		if (isset($this->request->get['balance'])) {
			$balance = $this->request->get['balance'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$balance =null;
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			//$filter_date_end = date('Y-m-d');
			$filter_date_end ="";
		}
		if (isset($this->request->get['filter_jenis'])) {
			$filter_jenis = $this->request->get['filter_jenis'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_jenis ="";
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
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
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		
		if (isset($this->request->get['filter_keterangan'])) {
			$url .= '&filter_keterangan=' . $this->request->get['filter_keterangan'];
		}

		
		if (isset($this->request->get['balance'])) {
			$url .= '&balance=' . $this->request->get['balance'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('keuangan/jurnalmanual');
		$this->data['insert'] = $this->url->link('laporan/jurnalmanual/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['orders'] = array();

		$data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_id'	=> $filter_id,
			'filter_jenis'	=> $filter_jenis,
			'filter_ref'	=> $filter_ref,
			'filter_keterangan' => $filter_keterangan,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
			//'limit'                  => 50
		);
		
		
		
		$tot = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_id'	=> $filter_id,
			'filter_jenis'	=> $filter_jenis,
			'filter_ref'	=> $filter_ref,
			'filter_keterangan' => $filter_keterangan
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		
		$order_total = count($this->model_keuangan_jurnalmanual->jurnalUmum($tot));
		if(isset($this->request->get['excel'])){
			$results = $this->model_keuangan_jurnalmanual->jurnalUmum($tot);
		}else{
			$results = $this->model_keuangan_jurnalmanual->jurnalUmum($data);
		}
		$t = $this->model_keuangan_jurnalmanual->totalss($data);
		
		foreach ($results as $result) {

			$action=array();
			$action[] = array(
                'text' => 'Edit',
                'href' => $this->url->link('laporan/jurnalmanual/editjurnal', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
            );
            $action[] = array(
                'text' => 'Hapus',
                'href' => $this->url->link('laporan/jurnalmanual/hapusjurnal', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
            );
			$detail=$this->model_keuangan_jurnalmanual->getDetailJurnalUmum($result['id'],$filter_jenis,$data=array('balance'=>$balance));
			foreach($detail as $d){
				$totdeb += $d['debet'];
				$totkred += $d['kredit'];
			}
					$this->data['orders'][] = array(
						'keterangan'	=> $result['keterangan'],
						'tanggal'	=>date('d/m/y',strtotime($result['tanggal'])),
                        'ref'	=> $result['ref'],
                        'no_dokumen'	=> $result['no_dokumen'],
						'detail'	=> $detail,
						'action'	=> $action

					);
		}
		

		$this->data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['balance'])) {
			$url .= '&balance=' . $this->request->get['balance'];
		}
		
		if (isset($this->request->get['filter_keterangan'])) {
			$url .= '&filter_keterangan=' . $this->request->get['filter_keterangan'];
		}
		

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		
		$pagination->page = $page;
	
		
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/jurnalmanual', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		$this->data['pagination'] = $pagination->render();
		$this->data['excel'] = $this->url->link('laporan/jurnalmanual', 'token=' . $this->session->data['token'] . $url . '&excel=true', 'SSL');

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_jenis'] = $filter_jenis;
		$this->data['filter_ref'] = $filter_ref;
		$this->data['balance'] = $balance;
		$this->data['filter_keterangan'] = $filter_keterangan;
		
		if(isset($this->request->get['excel'])){
			$this->template = 'laporan/jurnalmanual_excel.tpl';
		}else{
			$this->template = 'laporan/jurnalmanual.tpl';
		}
		
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function tampil(){
		$url='';
		if (isset($this->request->get['id'])) {
			$url .= '&filter_id=' . $this->request->get['id'];
		}
		$this->redirect($this->url->link('laporan/jurnalmanual', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Jurnal Memo');

		$this->load->model('keuangan/jurnalmanual');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
      $no_po=$this->model_keuangan_jurnalmanual->addJurnalUmumManual($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Jurnal Memo berhasil disimpan';

			$url = '';

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
			if (isset($this->request->get['filter_nodokumen'])) {
				$url .= '&filter_nodokumen=' . $this->request->get['filter_nodokumen'];
			}
			if (isset($this->request->get['filter_jenis'])) {
				$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('laporan/jurnalmanual', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		if (isset($this->request->get['filter_nodokumen'])) {
			$url .= '&filter_nodokumen=' . $this->request->get['filter_nodokumen'];
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

		$this->data['token'] = $this->session->data['token'];





		$this->data['cancel']= $this->url->link('laporan/jurnalmanual', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('laporan/jurnalmanual/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = array();
		}

		$this->template = 'laporan/jurnalmanual_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	public function hapusjurnal() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Jurnal Umum');

		$this->load->model('keuangan/jurnalmanual');
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		if (isset($this->request->get['filter_nodokumen'])) {
			$url .= '&filter_nodokumen=' . $this->request->get['filter_nodokumen'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		if (isset($this->request->get['id'])) {
			if(empty($this->request->get['id'])){
				$this->redirect($this->url->link('laporan/jurnalmanual', 'token=' . $this->session->data['token'].$url , 'SSL'));
			}else{
				$id = $this->request->get['id'];
				$this->model_keuangan_jurnalmanual->hapusJurnal($id);
				$this->session->data['success'] = 'Sukses: Data Jurnal Memo berhasil dihapus';
				$this->redirect($this->url->link('laporan/jurnalmanual', 'token=' . $this->session->data['token'].$url , 'SSL'));
			}

		} else {
			$this->redirect($this->url->link('laporan/jurnalmanual', 'token=' . $this->session->data['token'].$url , 'SSL'));

		}


	}

	public function editjurnal() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Jurnal Umum');

		$this->load->model('keuangan/jurnalmanual');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if($this->user->getUsername()=="pawit"){
				echo "<pre>";print_r($this->request->post);exit;
			}
			$no_po=$this->model_keuangan_jurnalmanual->editJurnalUmumManual($this->request->post,$this->request->get['id']);

			$this->session->data['success'] = 'Sukses: Data Jurnal Memo berhasil diperbarui';

			$url = '';

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
			if (isset($this->request->get['filter_keterangan'])) {
				$url .= '&filter_keterangan=' . $this->request->get['filter_keterangan'];
			}
			if (isset($this->request->get['balance'])) {
				$url .= '&balance=' . $this->request->get['balance'];
			}
			if (isset($this->request->get['filter_nodokumen'])) {
				$url .= '&filter_nodokumen=' . $this->request->get['filter_nodokumen'];
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
			if (isset($this->request->get['filter_jenis'])) {
				$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('laporan/jurnalmanual', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_keterangan'])) {
			$url .= '&filter_keterangan=' . $this->request->get['filter_keterangan'];
		}
		if (isset($this->request->get['balance'])) {
			$url .= '&balance=' . $this->request->get['balance'];
		}			

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		if (isset($this->request->get['filter_nodokumen'])) {
			$url .= '&filter_nodokumen=' . $this->request->get['filter_nodokumen'];
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

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['id'])) {
			$id = $this->request->get['id'];
		} else {
			if(empty($this->request->get['id'])){
				$this->redirect($this->url->link('laporan/jurnalmanual', 'token=' . $this->session->data['token'] , 'SSL'));
			}
		}
		$this->data['action']= $this->url->link('laporan/jurnalmanual/editjurnal', 'token=' . $this->session->data['token'].'&id='.$id . $url, 'SSL');

		$jurnal=$this->model_keuangan_jurnalmanual->getJurnalUmum(array('id'=>$id));
			$detail=$this->model_keuangan_jurnalmanual->getDetailJurnalUmum($jurnal['id'],array());

		$this->data['jurnal']=$jurnal;
		$this->data['detail']=$detail;


		$this->data['cancel']= $this->url->link('laporan/jurnalmanual', 'token=' . $this->session->data['token'] . $url, 'SSL');


		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = array();
		}

		$this->template = 'laporan/jurnalmanual_edit.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}



	private function validateForm() {

		if(!is_numeric($this->request->post['nominal']) ){
			$this->error['nominal'] = 'Jumlah Pembayaran Harus Berupa Angka';
		}


		//print_r($cek);
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}


}
?>
