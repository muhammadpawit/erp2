<?php
class ControllerPembelianKasbon extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Kasbon Pembelian');

		if (isset($this->request->get['filter_no_surat'])) {
			$filter_no_surat = $this->request->get['filter_no_surat'];
		} else {
			$filter_no_surat = '';
		}

		if (isset($this->request->get['filter_no_permintaan'])) {
			$filter_no_permintaan = $this->request->get['filter_no_permintaan'];
		} else {
			$filter_no_permintaan = '';
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

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}
		if (isset($this->request->get['filter_no_permintaan'])) {
			$url .= '&filter_no_permintaan=' . $this->request->get['filter_no_permintaan'];
		}

			if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('pembelian/kasbon/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		/*$this->load->model('report/product');
        $this->load->model('catalog/product');
		*/

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		/*$this->load->model('catalog/product');
		$this->load->model('gudang/product');
		$this->load->model('pembelian/permintaanpembelian');
		$this->load->model('catalog/gudang');
		*/

		$this->load->model('user/divisi');
	//	$this->load->model('pembelian/permintaanpembelian');
		$this->load->model('pembelian/kasbon');
		//$permintaans = $this->model_pembelian_kasbon->getPermintaanPembelians();
	//	$this->data['divisis']=$divisis;

		$this->data['permintaans'] = array();
		$column=array('kasbon_pembelian.id','kasbon_pembelian.no_surat','kasbon_pembelian.surat_id','permintaan_pembelian.no_surat as pno_surat','kasbon_pembelian.tujuan','kasbon_pembelian.jumlah','kasbon_pembelian.status','kasbon_pembelian.date_added');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'kasbon_pembelian.surat_id'
		);

		$data = array(
			'kasbon_pembelian.no_surat'      =>array('LIKE',$filter_no_surat),
			'permintaan_pembelian.id'=> $filter_no_permintaan,
			'kasbon_pembelian.status'			=> $filter_status,
			'kasbon_pembelian.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'kasbon_pembelian.id'	=> 'DESC',
			'kasbon_pembelian.status'	=> 'ASC'
		);

		$product_total = $this->model_pembelian_kasbon->totalPermintaans($data);

		$results = $this->model_pembelian_kasbon->getPermintaanPembelians($column,$join,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('pembelian/kasbon/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			if($result['status'] == 1){
				$action[] = array(
					'text' => 'Cetak',
					'href' => $this->url->link('pembelian/kasbon/cetak', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);

				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('pembelian/kasbon/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}

			$this->data['permintaans'][] = array(
				'surat_id'	=> $result['surat_id'],
				'pno_surat'	=> $result['pno_surat'],
				'tujuan'	=> $result['tujuan'],
				'tanggal'	=> date('d/m/y',strtotime($result['date_added'])),
				'no_surat'	=> $result['no_surat'],
				'jumlah'	=> $this->currency->format($result['jumlah']),
				'status'	=> $result['status'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Kasbon Pembelian';

		$this->data['token'] = $this->session->data['token'];
		$url = '';

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}
		if (isset($this->request->get['filter_no_permintaan'])) {
			$url .= '&filter_no_permintaan=' . $this->request->get['filter_no_permintaan'];
		}

			if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pembelian/kasbon', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_permintaan'] = $filter_no_permintaan;
		$this->data['filter_no_surat'] = $filter_no_surat;
		$this->data['filter_status'] = $filter_status;

		$this->template = 'pembelian/kasbon.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Kasbon Pembelian');

		$this->load->model('pembelian/permintaanpembelian');
		$this->load->model('pembelian/kasbon');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $this->model_pembelian_kasbon->addPermintaanPembelian($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Kasbon Pembelian berhasil disimpan.';

			$url = '';

			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
			}
			if (isset($this->request->get['filter_no_permintaan'])) {
				$url .= '&filter_no_permintaan=' . $this->request->get['filter_no_permintaan'];
			}

				if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('pembelian/kasbon', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url = '';

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}
		if (isset($this->request->get['filter_no_permintaan'])) {
			$url .= '&filter_no_permintaan=' . $this->request->get['filter_no_permintaan'];
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

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->post['jumlah'])) {
			$this->data['jumlah'] = $this->request->post['jumlah'];
		}  else {
			$this->data['jumlah'] = '';
		}
		if (isset($this->request->post['tujuan'])) {
			$this->data['tujuan'] = $this->request->post['tujuan'];
		}  else {
			$this->data['tujuan'] = '';
		}
		if (isset($this->request->post['surat_id'])) {
			$this->data['surat_id'] = $this->request->post['surat_id'];
		}  else {
			$this->data['surat_id'] = '';
		}
		$this->data['cancel']= $this->url->link('pembelian/kasbon', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/kasbon/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
        
         $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
        

		$this->template = 'pembelian/kasbon_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {
    	/*if (!$this->user->hasPermission('modify', 'pembelian/permintaanpembelian')) {
      		$this->error['warning'] = 'Permission Denied.';
    	}

    	/*if (empty($this->request->post['date_added'])) {
      		$this->error['warning'] = 'Tanggal input product cacat harus diisi';
    	}*/

		if (!is_numeric($this->request->post['jumlah'])) {
		  		$this->error['warning'] = 'Jumlah kasbon harus berupa angka';
			}
			else{
				if($this->request->post['jumlah'] < 0){
					$this->error['warning'] = 'Jumlah kasbon lebih dari 0';
				}
			}
			if(empty($this->request->post['surat_id'])){
					$this->error['warning'] = 'Nomor surat permintaan pembelian harus dipilih';
			}



		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function tampil(){
		$this->document->setTitle('Kasbon Pembelian');
		$url = '';

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}
		if (isset($this->request->get['filter_no_permintaan'])) {
			$url .= '&filter_no_permintaan=' . $this->request->get['filter_no_permintaan'];
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
				$this->redirect($this->url->link('pembelian/permintaanpembelian', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/permintaanpembelian', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/kasbon');

		$column=array('kasbon_pembelian.id','kasbon_pembelian.no_surat','permintaan_pembelian.no_surat as pno_surat','kasbon_pembelian.tujuan','kasbon_pembelian.jumlah','kasbon_pembelian.status','kasbon_pembelian.date_added');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'kasbon_pembelian.surat_id'
		);

		$data = array(
			'kasbon_pembelian.id'=> $id,

		);

		$trans=$this->model_pembelian_kasbon->getPermintaanPembelian($column,$join,$data);

		$this->data['permintaan']=$trans;
		$this->data['cancel']= $this->url->link('pembelian/kasbon', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/kasbon_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function cetak(){
		$this->document->setTitle('Kasbon Pembelian');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/kasbon', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/kasbon', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->load->model('pembelian/kasbon');
		$column=array('kasbon_pembelian.id','kasbon_pembelian.no_surat','permintaan_pembelian.no_surat as pno_surat','kasbon_pembelian.tujuan','kasbon_pembelian.jumlah','kasbon_pembelian.status','kasbon_pembelian.date_added');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'kasbon_pembelian.surat_id'
		);

		$data = array(
			'kasbon_pembelian.id'=> $id,

		);

		$trans=$this->model_pembelian_kasbon->getPermintaanPembelian($column,$join,$data);
		$this->data['permintaan']=$trans;

		$this->template = 'pembelian/kasbon_cetak.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function batalkan(){
		$this->load->model('pembelian/kasbon');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_pembelian_kasbon->updatePermintaan(array('status' => 3),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Kasbon Pembelian berhasil dibatalkan.';
			}
		}
		$url = '';

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}
		if (isset($this->request->get['filter_no_permintaan'])) {
			$url .= '&filter_no_permintaan=' . $this->request->get['filter_no_permintaan'];
		}

			if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

			$this->redirect($this->url->link('pembelian/permintaanpembelian', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}


}
?>
