<?php
class ControllerKeuanganPermohonanpenyesuaiandeposit extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Permohonan Penyesuaian Deposit');

		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = '';
		}
		if (isset($this->request->get['filter_no_surat'])) {
			$filter_no_surat = $this->request->get['filter_no_surat'];
		} else {
			$filter_no_surat = '';
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
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

		$url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('keuangan/permohonanpenyesuaiandeposit/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		/*$this->load->model('catalog/product');
		$this->load->model('gudang/product');
		$this->load->model('gudang/permohonanstokopname');
		$this->load->model('catalog/gudang');
		*/

		$this->load->model('keuangan/permohonanpenyesuaiandeposit');
		$this->data['permintaans'] = array();
		$column=array('penyesuaian_deposit.*','customer.name');
		$join=array();
	$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=>'penyesuaian_deposit.customer_id',
			'secondtable'	=> 'customer.customer_id'
		);

		$data = array(
			'no_surat'      =>array('LIKE',$filter_no_surat),
			'date(penyesuaian_deposit.tanggal)'      =>$filter_tanggal,
			'penyesuaian_deposit.status'			=> $filter_status,
			'penyesuaian_deposit.customer_id'			=> $filter_customer_id,
			'penyesuaian_deposit.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'penyesuaian_deposit.id'	=> 'DESC',
			'penyesuaian_deposit.tanggal'	=> 'DESC',
			'penyesuaian_deposit.status'	=> 'ASC'
		);

		$product_total = $this->model_keuangan_permohonanpenyesuaiandeposit->totalPermintaans($data);

		$results = $this->model_keuangan_permohonanpenyesuaiandeposit->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('keuangan/permohonanpenyesuaiandeposit/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			/*$action[] = array(
				'text' => 'Cetak',
				'href' => $this->url->link('gudang/permohonanstokopname/cetak', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);*/

			if($result['status'] == 1){


				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('keuangan/permohonanpenyesuaiandeposit/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
				/*$action[] = array(
					'text' => 'Setujui',
					'href' => $this->url->link('gudang/permohonanstokopname/setujui', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);*/
			}

			if($result['status'] == 2){
				$action[] = array(
					'text' => 'Lihat Jurnal',
					'href' => $this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'] . '&filter_nodokumen=' . $result['no_surat'].$url, 'SSL')
				);
			}

			$this->data['permintaans'][] = array(
				'name'	=> $result['name'],
				'keterangan'	=> $result['keterangan'],
				'tanggal'	=> date('d/m/y',strtotime($result['tanggal'])),
				'tgl_diproses'	=> empty($result['tgl_diproses'])?'Belum Diproses':date('d/m/y',strtotime($result['tgl_diproses'])),
				'no_surat'	=> $result['no_surat'],
                'status'	=> $result['status'],
                'nominal_tersimpan'  => $this->currency->format($result['nominal_tersimpan']),
                'nominal_tersedia'  => $this->currency->format($result['nominal_tersedia']),
                'selisih'   => $this->currency->format($result['selisih']),
                'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Permohonan Penyesuaian Deposit';

		$this->data['token'] = $this->session->data['token'];
		$url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('keuangan/permohonanpenyesuaiandeposit', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);
	/*$this->load->model('catalog/gudang');
	$gudangs = $this->model_catalog_gudang->getGudangs(true);
	$this->data['gudangs']=$gudangs;
    */
	$this->data['filter_no_surat'] = $filter_no_surat;
		$this->data['filter_status'] = $filter_status;

		$this->template = 'keuangan/permohonanpenyesuaiandeposit.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Permohonan Penyesuaian Deposit');

		$this->load->model('keuangan/permohonanpenyesuaiandeposit');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_surat=$this->model_keuangan_permohonanpenyesuaiandeposit->addPermintaanPembelian($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Permohonan Penyesuaian Deposit berhasil disimpan dengan nomor surat '.$no_surat;

			$url = '';
			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}

			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
			}

			if (isset($this->request->get['filter_customer_id'])) {
				$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('keuangan/permohonanpenyesuaiandeposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
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

		//get divisi user


		$this->data['token'] = $this->session->data['token'];

		$this->data['cancel']= $this->url->link('keuangan/permohonanpenyesuaiandeposit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('keuangan/permohonanpenyesuaiandeposit/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->request->post['products'])) {
			$this->data['products'] = $this->request->post['products'];
		} else {
			$this->data['products'] = '';
		}

		
		$this->template = 'keuangan/permohonanpenyesuaiandeposit_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {
    	/*if (!$this->user->hasPermission('modify', 'gudang/permohonanstokopname')) {
      		$this->error['warning'] = 'Permission Denied.';
    	}

    	*/
    	if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	
	public function batalkan(){
		$this->load->model('keuangan/permohonanpenyesuaiandeposit');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_keuangan_permohonanpenyesuaiandeposit->updatePermintaan(array('status' => 3,'alasan_dibatalkan'=> 'Dibatalkan oleh admin'),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Permohonan Penyesuaian Deposit berhasil dibatalkan.';
			}
		}
		$url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

			$this->redirect($this->url->link('keuangan/permohonanpenyesuaiandeposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
		public function autocomplete(){
		$rests = array();

		$this->load->model('keuangan/permohonanpenyesuaiandeposit');
		
			if (isset($this->request->get['q'])) {
				$filter_no_surat = $this->request->get['q'];
			} else {
				$filter_no_surat = '';
			}
			if (isset($this->request->get['j'])) {
				$jenis_pembelian = $this->request->get['j'];
			} else {
				$jenis_pembelian = '';
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			if(isset($this->request->get['status'])){
				if(!empty($this->request->get['status'])){
					if($this->request->get['status'] == 5){
						$status=array('<>',3);
					}else{
						$status=$this->request->get['status'];
					}
				}else{
					$status=2;
				}
			}else{
				$status=2;
			}
			$data = array(
				'no_surat'         => array('LIKE',$filter_no_surat),
				'status'	=> $status,
				
			);
			$start=0;
			$limit=0;
			$column=array('id','no_surat');
			$join=array();

			$results = $this->model_keuangan_permohonanpenyesuaiandeposit->getPermintaanPembelians($column,$join,array(),$data,array(),$limit,$start);
			foreach($results as $r){
				$tampil=true;
				
				if($tampil){
					$rests[]=array(
						'id'	=> $r['id'],
						'text'	=> $r['no_surat']
					);
				}
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function detail(){
		$hasil = array();

		$this->load->model('keuangan/permohonanpenyesuaiandeposit');
		if(isset($this->request->get['surat_id'])){
			if(!empty($this->request->get['surat_id'])){
				$column=array();
				$surat_id=$this->request->get['surat_id'];
				$data = array(
					'id'      =>$surat_id,
					'status'	=> 2
				);

				$trans=$this->model_keuangan_permohonanpenyesuaiandeposit->getPermintaanPembelian($column,array(),$data);
				$hasil=array(
					'detail'	=> $trans,
					
				);

			}
		}
		$this->response->setOutput(json_encode($hasil));


	}

	public function tampil(){
		$this->document->setTitle('Permohonan Penyesuaian Deposit');
		$this->load->model('keuangan/permohonanpenyesuaiandeposit');
		
		$url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('keuangan/permohonanpenyesuaiandeposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('keuangan/permohonanpenyesuaiandeposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		

		$this->load->model('keuangan/permohonanpenyesuaiandeposit');

		$column=array('penyesuaian_deposit.*','customer.name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=>'penyesuaian_deposit.customer_id',
			'secondtable'	=> 'customer.customer_id'
		);

		$data = array(
			'penyesuaian_deposit.id'      =>$id,

		);

		$trans=$this->model_keuangan_permohonanpenyesuaiandeposit->getPermintaanPembelian($column,$join,$data);
		$this->data['permintaan']=$trans;
		$this->data['cancel']= $this->url->link('keuangan/permohonanpenyesuaiandeposit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('keuangan/permohonanpenyesuaiandeposit/setujui', 'token=' . $this->session->data['token'] . $url.'&id='.$id, 'SSL');

		$this->template = 'keuangan/permohonanpenyesuaiandeposit_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
}
?>
