<?php
class ControllerPembelianPermintaanpembelianbahanbaku extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Permintaan Pembelian');

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

		if (isset($this->request->get['filter_jenis_pembelian'])) {
			$filter_jenis_pembelian = $this->request->get['filter_jenis_pembelian'];
		} else {
			$filter_jenis_pembelian = null;
		}

		if (isset($this->request->get['filter_jenis_barang'])) {
			$filter_jenis_barang = $this->request->get['filter_jenis_barang'];
		} else {
			$filter_jenis_barang = null;
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}

		if (isset($this->request->get['filter_divisi'])) {
			$filter_divisi = $this->request->get['filter_divisi'];
		} else {
			$filter_divisi = null;
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

		if (isset($this->request->get['filter_jenis_pembelian'])) {
			$url .= '&filter_jenis_pembelian=' . $this->request->get['filter_jenis_pembelian'];
		}

		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_divisi'])) {
			$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
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

		$this->data['insert'] = $this->url->link('pembelian/permintaanpembelianbahanbaku/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

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
		$this->load->model('pembelian/permintaanpembelian');
		$divisis = $this->model_user_divisi->getDivisis();
		$this->data['divisis']=$divisis;



		$this->data['permintaans'] = array();
		$column=array('permintaan_pembelian.id','permintaan_pembelian.no_surat','permintaan_pembelian.tujuan_pembelian','permintaan_pembelian.jenis_pembelian','permintaan_pembelian.jenis_barang','divisi.name','permintaan_pembelian.date_added','permintaan_pembelian.status','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'divisi',
			'firsttable'	=>'permintaan_pembelian.divisi_asal',
			'secondtable'	=> 'divisi.id'
		);
		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=>'permintaan_pembelian.gudang_id',
			'secondtable'	=> 'gudang.gudang_id'
		);

		$data = array(
			'no_surat'      =>array('LIKE',$filter_no_surat),
			'date(permintaan_pembelian.date_added)'      =>$filter_tanggal,
			'divisi_asal'=> $filter_divisi,
			'jenis_pembelian'	=> $filter_jenis_pembelian,
			'jenis_barang'	=> 1,
      'permintaan_pembelian.status'			=> $filter_status,
			'permintaan_pembelian.gudang_id'			=> $filter_gudang_id,
			'permintaan_pembelian.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'permintaan_pembelian.id'	=> 'DESC',
			'permintaan_pembelian.status'	=> 'ASC'
		);

		$product_total = $this->model_pembelian_permintaanpembelian->totalPermintaans($data);

		$results = $this->model_pembelian_permintaanpembelian->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('pembelian/permintaanpembelianbahanbaku/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			$action[] = array(
				'text' => 'Cetak',
				'href' => $this->url->link('pembelian/permintaanpembelianbahanbaku/cetak', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			/*if($result['status'] == 2 & $result['jenis_pembelian'] == 2){
				$action[] = array(
					'text' => 'SPPH',
					'href' => $this->url->link('pembelian/permintaanpembelian/spph', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}*/
			if($result['status'] == 1){


				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('pembelian/permintaanpembelianbahanbaku/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
				$action[] = array(
					'text' => 'Setujui',
					'href' => $this->url->link('pembelian/permintaanpembelianbahanbaku/setujui', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}

			$this->data['permintaans'][] = array(
				'name'	=> $result['name'],
				'gudang'	=> $result['nama'],
				'tujuan_pembelian'	=> $result['tujuan_pembelian'],
				'tanggal'	=> date('d/m/y',strtotime($result['date_added'])),
				'no_surat'	=> $result['no_surat'],
				'jenis_pembelian'	=> $result['jenis_pembelian'],
				'jenis_barang'	=> $result['jenis_barang'],
				'status'	=> $result['status'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Permintaan Pembelian';

		$this->data['token'] = $this->session->data['token'];
		$url = '';

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}

		if (isset($this->request->get['filter_jenis_pembelian'])) {
			$url .= '&filter_jenis_pembelian=' . $this->request->get['filter_jenis_pembelian'];
		}

		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_divisi'])) {
			$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pembelian/permintaanpembelianbahanbaku', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_jenis_pembelian'] = $filter_jenis_pembelian;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_no_surat'] = $filter_no_surat;
		$this->data['filter_status'] = $filter_status;

		$this->template = 'pembelian/permintaanpembelianbahanbaku.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Permintaan Pembelian');

		$this->load->model('pembelian/permintaanpembelian');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_surat=$this->model_pembelian_permintaanpembelian->addPermintaanPembelian($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Permintaan Pembelian berhasil disimpan dengan nomor surat '.$no_surat;

			$url = '';

			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
			}

			if (isset($this->request->get['filter_jenis_pembelian'])) {
				$url .= '&filter_jenis_pembelian=' . $this->request->get['filter_jenis_pembelian'];
			}
			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}

			if (isset($this->request->get['filter_jenis_barang'])) {
				$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
			}

			if (isset($this->request->get['filter_divisi'])) {
				$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('pembelian/permintaanpembelianbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('user/divisi');
		$url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}

		if (isset($this->request->get['filter_jenis_pembelian'])) {
			$url .= '&filter_jenis_pembelian=' . $this->request->get['filter_jenis_pembelian'];
		}

		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_divisi'])) {
			$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
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
		$user=$this->user->getId();
		$this->load->model('user/user');
		$datauser=$this->model_user_user->getUser($user);

		//print_r($datauser);
		if($datauser['divisi'] > 0){
			$gudangs = $this->model_user_divisi->getDivisis(array('id'=>$datauser['divisi']));
		}else{
			$gudangs = $this->model_user_divisi->getDivisis();
		}
		$this->data['divisis']=$gudangs;

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->post['divisi_asal'])) {
			$this->data['divisi_asal'] = $this->request->post['divisi_asal'];
		}  else {
			$this->data['divisi_asal'] = '';
		}
		$this->data['cancel']= $this->url->link('pembelian/permintaanpembelianbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/permintaanpembelianbahanbaku/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->template = 'pembelian/permintaanpembelianbahanbaku_form.tpl';
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

		if (empty($this->request->post['product'])) {
		  		$this->error['warning'] = 'Produk tidak boleh kosong';
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

	public function tampil(){
		$this->document->setTitle('Permintaan Pembelian');
		$url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
		}

		if (isset($this->request->get['filter_jenis_pembelian'])) {
			$url .= '&filter_jenis_pembelian=' . $this->request->get['filter_jenis_pembelian'];
		}

		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_divisi'])) {
			$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/permintaanpembelianbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/permintaanpembelianbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('user/divisi');
		$this->load->model('catalog/gudang');
		$this->load->model('pembelian/permintaanpembelian');

		$column=array('permintaan_pembelian.id','permintaan_pembelian.no_surat','permintaan_pembelian.tujuan_pembelian','permintaan_pembelian.jenis_pembelian','permintaan_pembelian.jenis_barang','divisi.name','permintaan_pembelian.date_added','permintaan_pembelian.status','permintaan_pembelian.gudang_id');
		$join=array();
		$join[]=array(
			'tablename'	=> 'divisi',
			'firsttable'	=>'permintaan_pembelian.divisi_asal',
			'secondtable'	=> 'divisi.id'
		);

		$data = array(
			'permintaan_pembelian.id'      =>$id,

		);

		$trans=$this->model_pembelian_permintaanpembelian->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_pembelian_permintaanpembelian->getPermintaanPembelianProduct(array('surat_id'	=> $id));
		$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
	//	print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['gudang']=$gudang;
		$this->data['cancel']= $this->url->link('pembelian/permintaanpembelianbahanbaku', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/permintaanpembelianbahanbaku_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function cetak(){
		$this->document->setTitle('Permintaan Pembelian');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/permintaanpembelian', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/permintaanpembelian', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('user/divisi');
		$this->load->model('catalog/gudang');
		$this->load->model('pembelian/permintaanpembelian');

		$column=array('permintaan_pembelian.id','permintaan_pembelian.no_surat','permintaan_pembelian.tujuan_pembelian','permintaan_pembelian.jenis_pembelian','permintaan_pembelian.jenis_barang','divisi.name','permintaan_pembelian.date_added','permintaan_pembelian.status');
		$join=array();
		$join[]=array(
			'tablename'	=> 'divisi',
			'firsttable'	=>'permintaan_pembelian.divisi_asal',
			'secondtable'	=> 'divisi.id'
		);

		$data = array(
			'permintaan_pembelian.id'      =>$id,

		);

		$trans=$this->model_pembelian_permintaanpembelian->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_pembelian_permintaanpembelian->getPermintaanPembelianProduct(array('surat_id'	=> $id));
		$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
	//	print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['gudang']=$gudang;

		$this->template = 'pembelian/permintaanpembelian_cetak.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function spph(){
		$this->document->setTitle('Permintaan Penawaran Harga');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/permintaanpembelian', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/permintaanpembelian', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('user/divisi');
		$this->load->model('catalog/gudang');
		$this->load->model('pembelian/permintaanpembelian');

		$column=array('permintaan_pembelian.id','permintaan_pembelian.no_surat','permintaan_pembelian.tujuan_pembelian','permintaan_pembelian.jenis_pembelian','permintaan_pembelian.jenis_barang','divisi.name','permintaan_pembelian.date_added','permintaan_pembelian.status');
		$join=array();
		$join[]=array(
			'tablename'	=> 'divisi',
			'firsttable'	=>'permintaan_pembelian.divisi_asal',
			'secondtable'	=> 'divisi.id'
		);

		$data = array(
			'permintaan_pembelian.id'      =>$id,

		);

		$trans=$this->model_pembelian_permintaanpembelian->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_pembelian_permintaanpembelian->getPermintaanPembelianProduct(array('surat_id'	=> $id));
		$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
	//	print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['gudang']=$gudang;

		$this->template = 'pembelian/penawaranharga.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function batalkan(){
		$this->load->model('pembelian/permintaanpembelian');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_pembelian_permintaanpembelian->updatePermintaan(array('status' => 3),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Permintaan Pembelian berhasil dibatalkan.';
			}
		}
			$url = '';
			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}
			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
			}

			if (isset($this->request->get['filter_jenis_pembelian'])) {
				$url .= '&filter_jenis_pembelian=' . $this->request->get['filter_jenis_pembelian'];
			}

			if (isset($this->request->get['filter_jenis_barang'])) {
				$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
			}

			if (isset($this->request->get['filter_divisi'])) {
				$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('pembelian/permintaanpembelian', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	public function setujui(){
		$this->load->model('pembelian/permintaanpembelian');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_pembelian_permintaanpembelian->updatePermintaan(array('status' => 2),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Permintaan Pembelian berhasil disetujui.';
			}
		}
			$url = '';
			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}
			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
			}

			if (isset($this->request->get['filter_jenis_pembelian'])) {
				$url .= '&filter_jenis_pembelian=' . $this->request->get['filter_jenis_pembelian'];
			}

			if (isset($this->request->get['filter_jenis_barang'])) {
				$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
			}

			if (isset($this->request->get['filter_divisi'])) {
				$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('pembelian/permintaanpembelian', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	public function autocomplete(){
		$rests = array();

		$this->load->model('pembelian/permintaanpembelian');
		$this->load->model('pembelian/pembeliankreditbahanbaku');
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
				'jenis_pembelian'	=> $jenis_pembelian,
				'jenis_barang'	=> 1
				//'jenis_pembelian'	=> !mpty()$jenis_pembelian
			);
			$start=0;
			$limit=0;
			$column=array('id','no_surat');
			$join=array();

			$results = $this->model_pembelian_permintaanpembelian->getPermintaanPembelians($column,$join,array(),$data,array(),$limit,$start);
			foreach($results as $r){
				$tampil=true;
				/*if (isset($this->request->get['s'])) {
					if($jenis_pembelian == 2){
						$pem=$this->model_pembelian_pembeliankredit->getPermintaanPembelian(array(),array(),array('surat_id'=>$r['id'],'status'=>array('<>',3)));
					}
					if($jenis_pembelian == 3){
						$pem=$this->model_pembelian_pembelianimport->getPermintaanPembelian(array(),array(),array('surat_id'=>$r['id'],'status'=>array('<>',3)));
					}
					if(!empty($pem)){
						$tampil=false;
					}
				}*/
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

		$this->load->model('pembelian/permintaanpembelian');
		$this->load->model('catalog/gudang');
		if(isset($this->request->get['surat_id'])){
			if(!empty($this->request->get['surat_id'])){
				$column=array();
				$surat_id=$this->request->get['surat_id'];
				$data = array(
					'id'      =>$surat_id,
					'status'	=> 2
				);

				$trans=$this->model_pembelian_permintaanpembelian->getPermintaanPembelian($column,array(),$data);
				$prods=$this->model_pembelian_permintaanpembelian->getPermintaanPembelianProduct(array('surat_id'	=> $surat_id));
			//	print_r($prods);
				$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
				$hasil=array(
					'detail'	=> $trans,
					'products' => $prods,
					'gudang'	=> $gudang
				);

			}
		}
		$this->response->setOutput(json_encode($hasil));


	}


}
?>
