<?php
class ControllerProduksiBukaproduksi extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Buka Produksi');

		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = '';
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

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('produksi/bukaproduksi/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

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
		$this->load->model('produksi/permintaanproduksi');
		$this->load->model('catalog/gudang');
		*/

		$this->load->model('produksi/bukaproduksi');
		$this->data['permintaans'] = array();
		$column=array('bukaproduksi.id','bukaproduksi.keterangan','bukaproduksi.tanggalmulai','bukaproduksi.tanggalselesai','bukaproduksi.status','gudang.nama');
		$join=array();
		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=>'bukaproduksi.gudang_id',
			'secondtable'	=> 'gudang.gudang_id'
		);

		$data = array(
			'bukaproduksi.status'			=> $filter_status,
			'bukaproduksi.gudang_id'			=> $filter_gudang_id,
			'bukaproduksi.hapus'	=> 0,
			'date(bukaproduksi.tanggalmulai)'	=> $filter_tanggal,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'bukaproduksi.id'	=> 'DESC',
			'bukaproduksi.status'	=> 'ASC'
		);

		$this->data['cek']=$this->model_produksi_bukaproduksi->getPermintaanPembelian(array(),array(),array('status' => 1));

		$product_total = $this->model_produksi_bukaproduksi->totalPermintaans($data);

		$results = $this->model_produksi_bukaproduksi->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('produksi/bukaproduksi/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);

			if($result['status'] == 1){


				$action[] = array(
					'text' => 'Tutup',
					'href' => $this->url->link('produksi/bukaproduksi/tutup', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);

			}


			$trans=array();



			$this->data['permintaans'][] = array(
				'gudang'	=> $result['nama'],
				'keterangan'	=> $result['keterangan'],
				'tanggalmulai'	=> date('d/m/y H:i:s',strtotime($result['tanggalmulai'])),
				'tanggalselesai'	=> !empty($result['tanggalselesai'])?date('d/m/y H:i:s',strtotime($result['tanggalselesai'])):'Produksi masih terbuka',
				'status'	=> $result['status'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Buka Produksi';

		$this->data['token'] = $this->session->data['token'];
		$url = '';

		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('produksi/bukaproduksi', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_status'] = $filter_status;
		$this->data['filter_tanggal'] = $filter_tanggal;
		$this->data['filter_gudang_id'] = $filter_gudang_id;

		$this->template = 'produksi/bukaproduksi.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Buka Produksi');

		$this->load->model('produksi/bukaproduksi');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$cek=$this->model_produksi_bukaproduksi->getPermintaanPembelian(array(),array(),array('status' => 1));
			if(empty($cek)){
	      $no_surat=$this->model_produksi_bukaproduksi->bukaProduksi($this->request->post);

				$this->session->data['success'] = 'Sukses: Data Buka Produksi berhasil disimpan dengan nomor surat '.$no_surat;
			}else{
				$this->session->data['warning'] = 'Proses produksi dalam status Buka';
			}
			$url = '';

			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}

			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('produksi/bukaproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('catalog/bahanbaku');
		$bahan=$this->model_catalog_bahanbaku->getProducts();
		$this->data['bahan']=$bahan;
		$url = '';

		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
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


		$this->data['cancel']= $this->url->link('produksi/bukaproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('produksi/bukaproduksi/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->load->model('catalog/gudang');
		$gudangs = $this->model_catalog_gudang->getGudangs(true);
		$this->data['gudangs']=$gudangs;

		$this->template = 'produksi/bukaproduksi_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {
    	/*if (!$this->user->hasPermission('modify', 'produksi/permintaanproduksi')) {
      		$this->error['warning'] = 'Permission Denied.';
    	}

    	/*if (empty($this->requesxt->post['date_added'])) {
      		$this->error['warning'] = 'Tanggal input product cacat harus diisi';
    	}*/

		if (empty($this->request->post['bahan'])) {
		  		$this->error['warning'] = 'Bahanbaku tidak boleh kosong';
			}

    	if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function tutup(){
		$this->document->setTitle('Buka Produksi');
		$url = '';

		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
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
				$this->redirect($this->url->link('produksi/bukaproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('produksi/bukaproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->data['action']= $this->url->link('produksi/bukaproduksi/tutup', 'token=' . $this->session->data['token'] . $url.'&id='.$id, 'SSL');


		$this->load->model('catalog/gudang');
		$this->load->model('produksi/bukaproduksi');


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$cek=$this->model_produksi_bukaproduksi->getPermintaanPembelian(array(),array(),array('status' => 1));
			if(!empty($cek)){
	      $no_surat=$this->model_produksi_bukaproduksi->tutupProduksi($this->request->post);

				$this->session->data['success'] = 'Sukses: Produksi Berhasil Ditutup';
			}else{
				$this->session->data['warning'] = 'Proses produksi dalam status Tutup';
			}


			$this->redirect($this->url->link('produksi/bukaproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$column=array('bukaproduksi.id','bukaproduksi.keterangan','bukaproduksi.tanggalmulai','bukaproduksi.tanggalselesai','bukaproduksi.status','bukaproduksi.gudang_id');
		$join=array();


		$data = array(
			'bukaproduksi.id'      =>$id,

		);

		$trans=$this->model_produksi_bukaproduksi->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_produksi_bukaproduksi->getPermintaanPembelianProduct(array('bukaproduksi_id'	=> $id));
		$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['gudang']=$gudang;
		$this->data['cancel']= $this->url->link('produksi/bukaproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL');



		$this->template = 'produksi/bukaproduksi_tutup.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function tampil(){
		$this->document->setTitle('Buka Produksi');
		$url = '';

		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
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
				$this->redirect($this->url->link('produksi/bukaproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('produksi/bukaproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('catalog/gudang');
		$this->load->model('produksi/bukaproduksi');

		$column=array('bukaproduksi.id','bukaproduksi.keterangan','bukaproduksi.tanggalmulai','bukaproduksi.tanggalselesai','bukaproduksi.status','bukaproduksi.gudang_id');
		$join=array();


		$data = array(
			'bukaproduksi.id'      =>$id,

		);

		$trans=$this->model_produksi_bukaproduksi->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_produksi_bukaproduksi->getPermintaanPembelianProduct(array('bukaproduksi_id'	=> $id));
		$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['gudang']=$gudang;
		$this->data['cancel']= $this->url->link('produksi/bukaproduksi', 'token=' . $this->session->data['token'] . $url, 'SSL');



		$this->template = 'produksi/bukaproduksi_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}




}
?>
