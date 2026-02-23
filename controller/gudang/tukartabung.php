 <?php
class ControllerGudangTukartabung extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Tukar Tabung');

		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = '';
		}

		if (isset($this->request->get['filter_tabunghasil'])) {
			$filter_tabunghasil = $this->request->get['filter_tabunghasil'];
		} else {
			$filter_tabunghasil = '';
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
		if (isset($this->request->get['filter_tabunghasil'])) {
			$url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('gudang/tukartabung/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

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

		$this->load->model('gudang/tukartabung');
		$this->load->model('gudang/product');

		$this->data['permintaans'] = array();
		$column=array('tukartabung.*','gudang.nama');
		$join=array();
    	$join[]=array(
			'tablename'	=> 'product',
			'firsttable'	=>'tukartabung.tabung_b',
			'secondtable'	=> 'product.product_id'
		);
		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=>'tukartabung.gudang_id',
			'secondtable'	=> 'gudang.gudang_id'
		);

		$data = array(
			'tgl_tukar'      =>$filter_tanggal,
			'tukartabung.status'			=> $filter_status,
			'tukartabung.gudang_id'			=> $filter_gudang_id,
      		'product.name'			=> array('LIKE',$filter_tabunghasil),
			'tukartabung.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'tukartabung.id'	=> 'DESC',
			'tukartabung.status'	=> 'ASC'
		);

		$product_total = $this->model_gudang_tukartabung->totalTukarTabungs($data,$join);

		$results = $this->model_gudang_tukartabung->getTukarTabungs($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('gudang/tukartabung/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);

			if($result['status'] == 1){
				$action[] = array(
					'text' => 'Setujui',
					'href' => $this->url->link('gudang/tukartabung/setujui', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);

				$action[] = array(
					'text' => 'Batalkan/Tolak',
					'href' => $this->url->link('gudang/tukartabung/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}
			if($result['status'] == 2){
				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('gudang/tukartabung/batalkanproses', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}

			//tabung a
			$tabung_a=$this->model_gudang_product->getProduct($result['tabung_a'],$result['gudang_id']);

			$kran_b=$this->model_gudang_product->getProduct($result['kran_b'],$result['gudang_id']);
			$tabung_b=$this->model_gudang_product->getProduct($result['tabung_b'],$result['gudang_id']);
			$kran_lepasan=$this->model_gudang_product->getProduct($result['kran_lepasan'],$result['gudang_id']);
			$this->data['permintaans'][] = array(
				'tabung_a'	=> $tabung_a['name'],
				'no_dokumen'	=> $result['no_dokumen'],
				'kran_b'	=> $kran_b['name'],
				'kran_lepasan'	=> $kran_lepasan['name'],
				'tabung_b'	=> $tabung_b['name'],
				'quantity'	=> $result['quantity'],
        		'no_tukartabung'	=> $result['quantity'],
        		'keterangan'	=> $result['keterangan'],
				'gudang'	=> $result['nama'],
				'tgl_tukar'	=> date('d/m/y',strtotime($result['tgl_tukar'])),
				'tgl_proses'	=> !empty($result['tgl_proses'])?date('d/m/y',strtotime($result['tgl_proses'])):'Belum Diproses',
				'status'	=> $result['status'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Permintaan Pembelian';

		$this->data['token'] = $this->session->data['token'];
		$url='';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}


		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_tabunghasil'])) {
			$url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

	    $this->load->model('catalog/gudang');
	    $this->data['gudangs']=$this->model_catalog_gudang->getGudangs(true);

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('gudang/tukartabung', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

			$this->data['filter_status'] = $filter_status;
			$this->data['filter_tanggal'] = $filter_tanggal;

		$this->template = 'gudang/tukartabung.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Tukar Tabung');

		$this->load->model('gudang/tukartabung');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
     	$no_surat=$this->model_gudang_tukartabung->addTukarTabung($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Tukar Tabung Berhasil Disimpan dengan nomor '.$no_surat;

			$url = '';

			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}


			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}
			if (isset($this->request->get['filter_tabunghasil'])) {
				$url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('gudang/tukartabung', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}


		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_tabunghasil'])) {
			$url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
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
		$user=$this->user->getId();

		$this->data['cancel']= $this->url->link('gudang/tukartabung', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('gudang/tukartabung/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->load->model('catalog/gudang');
		$gudangs = $this->model_catalog_gudang->getGudangs(true);
		$this->data['gudangs']=$gudangs;

		$locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}

		$this->template = 'gudang/tukartabung_form.tpl';
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
		$this->document->setTitle('Tukar Kran');
		$url = '';
    if (isset($this->request->get['filter_tanggal'])) {
      $url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
    }


    if (isset($this->request->get['filter_gudang_id'])) {
      $url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
    }
    if (isset($this->request->get['filter_tabunghasil'])) {
      $url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
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
				$this->redirect($this->url->link('gudang/tukartabung', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('gudang/tukartabung', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		/*$this->load->model('user/divisi');
		$this->load->model('catalog/gudang');

    */

    $this->load->model('gudang/tukartabung');
    $this->load->model('gudang/product');

    $column=array('tukartabung.*','gudang.nama');
		$join=array();
	   $join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=>'tukartabung.gudang_id',
			'secondtable'	=> 'gudang.gudang_id'
		);

		$data = array(
			'id'      =>$id,
			'hapus'			=>array('<',1),
		);


		$trans=$this->model_gudang_tukartabung->getTukarTabung($column,$join,$data);

    $tabung_a=$this->model_gudang_product->getProduct($trans['tabung_a'],$trans['gudang_id']);

    $kran_b=$this->model_gudang_product->getProduct($trans['kran_b'],$trans['gudang_id']);
    $tabung_b=$this->model_gudang_product->getProduct($trans['tabung_b'],$trans['gudang_id']);
    $kran_lepasan=$this->model_gudang_product->getProduct($trans['kran_lepasan'],$trans['gudang_id']);


		$this->data['permintaan']=$trans;
    $this->data['tabung_a']=$tabung_a;
    $this->data['tabung_b']=$tabung_b;
    $this->data['kran_b']=$kran_b;
    $this->data['kran_lepasan']=$kran_lepasan;

		$this->data['cancel']= $this->url->link('gudang/tukartabung', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'gudang/tukartabung_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

  public function setujui(){
    $this->load->model('gudang/tukartabung');
		$this->document->setTitle('Tukar Kran');
    if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
      $no_surat=$this->model_gudang_tukartabung->prosesTukarTabung($this->request->get['id'],$this->request->post['tglproses']);

			$this->session->data['success'] = 'Sukses: Data Tukar Tabung Berhasil Diproses';

			$url = '';

			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}


			if (isset($this->request->get['filter_gudang_id'])) {
				$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
			}
			if (isset($this->request->get['filter_tabunghasil'])) {
				$url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('gudang/tukartabung', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$url = '';
    if (isset($this->request->get['filter_tanggal'])) {
      $url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
    }


    if (isset($this->request->get['filter_gudang_id'])) {
      $url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
    }
    if (isset($this->request->get['filter_tabunghasil'])) {
      $url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
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
				$this->redirect($this->url->link('gudang/tukartabung', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('gudang/tukartabung', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		/*$this->load->model('user/divisi');
		$this->load->model('catalog/gudang');

    */
    $this->data['action']= $this->url->link('gudang/tukartabung/setujui', 'token=' . $this->session->data['token'] . $url.'&id='.$id, 'SSL');


    $this->load->model('gudang/tukartabung');
    $this->load->model('gudang/product');

    $column=array('tukartabung.*','gudang.nama');
		$join=array();
	   $join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=>'tukartabung.gudang_id',
			'secondtable'	=> 'gudang.gudang_id'
		);

		$data = array(
			'id'      =>$id,
			'hapus'			=>array('<',1),
		);


		$trans=$this->model_gudang_tukartabung->getTukarTabung($column,$join,$data);

    $tabung_a=$this->model_gudang_product->getProduct($trans['tabung_a'],$trans['gudang_id']);

    $kran_b=$this->model_gudang_product->getProduct($trans['kran_b'],$trans['gudang_id']);
    $tabung_b=$this->model_gudang_product->getProduct($trans['tabung_b'],$trans['gudang_id']);
    $kran_lepasan=$this->model_gudang_product->getProduct($trans['kran_lepasan'],$trans['gudang_id']);

	$locktanggal=$this->config->get('config_locktanggal');

	if(!empty($locktanggal)){
		$this->data['locktanggal']=$locktanggal;

	}else{
		$this->data['locktanggal']=date('Y-m-d');
	}

		$this->data['permintaan']=$trans;
    $this->data['tabung_a']=$tabung_a;
    $this->data['tabung_b']=$tabung_b;
    $this->data['kran_b']=$kran_b;
    $this->data['kran_lepasan']=$kran_lepasan;

		$this->data['cancel']= $this->url->link('gudang/tukartabung', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'gudang/tukartabung_setujui.tpl';
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
	public function batalkan(){
		$this->load->model('gudang/tukartabung');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_gudang_tukartabung->updatePermintaan(array('status' => 3),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Tukar Kran berhasil dibatalkan.';
			}
		}
			$url = '';
      if (isset($this->request->get['filter_tanggal'])) {
  			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
  		}


  		if (isset($this->request->get['filter_gudang_id'])) {
  			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
  		}
  		if (isset($this->request->get['filter_tabunghasil'])) {
  			$url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
  		}

  		if (isset($this->request->get['filter_status'])) {
  			$url .= '&filter_status=' . $this->request->get['filter_status'];
  		}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('gudang/tukartabung', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	public function batalkanproses(){
		$this->load->model('gudang/tukartabung');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_gudang_tukartabung->batalkanTukarTabung($this->request->get['id']);

			$this->session->data['success'] = 'Sukses: Data Tukar Kran berhasil dibatalkan.';
			}
		}
			$url = '';
      if (isset($this->request->get['filter_tanggal'])) {
  			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
  		}


  		if (isset($this->request->get['filter_gudang_id'])) {
  			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
  		}
  		if (isset($this->request->get['filter_tabunghasil'])) {
  			$url .= '&filter_tabunghasil=' . $this->request->get['filter_tabunghasil'];
  		}

  		if (isset($this->request->get['filter_status'])) {
  			$url .= '&filter_status=' . $this->request->get['filter_status'];
  		}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('gudang/tukartabung', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	/*public function setujui(){
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
	}*/
	public function autocomplete(){
		$rests = array();

		$this->load->model('pembelian/permintaanpembelian');
		$this->load->model('pembelian/pembeliankredit');
		$this->load->model('pembelian/pembelianimport');

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
				'jenis_pembelian'	=> $jenis_pembelian
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
