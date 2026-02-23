<?php
class ControllerGudangPermohonanstokopname extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Permohonan Stok Opname');

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

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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

		$this->data['insert'] = $this->url->link('gudang/permohonanstokopname/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

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

		$this->load->model('gudang/permohonanstokopname');
		$this->data['permintaans'] = array();
		$column=array('permohonan_stokopname.id','permohonan_stokopname.tgl_diproses','permohonan_stokopname.no_surat','permohonan_stokopname.keterangan','permohonan_stokopname.tanggal','permohonan_stokopname.status','gudang.nama');
		$join=array();
	$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=>'permohonan_stokopname.gudang_id',
			'secondtable'	=> 'gudang.gudang_id'
		);

		$data = array(
			'no_surat'      =>array('LIKE',$filter_no_surat),
			'date(permohonan_stokopname.tanggal)'      =>$filter_tanggal,
			'permohonan_stokopname.status'			=> $filter_status,
			'permohonan_stokopname.gudang_id'			=> $filter_gudang_id,
			'permohonan_stokopname.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'permohonan_stokopname.id'	=> 'DESC',
			'permohonan_stokopname.tanggal'	=> 'DESC',
			'permohonan_stokopname.status'	=> 'ASC'
		);

		$product_total = $this->model_gudang_permohonanstokopname->totalPermintaans($data);

		$results = $this->model_gudang_permohonanstokopname->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('gudang/permohonanstokopname/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
			/*$action[] = array(
				'text' => 'Cetak',
				'href' => $this->url->link('gudang/permohonanstokopname/cetak', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);*/

			if($result['status'] == 1){


				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('gudang/permohonanstokopname/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
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
				'gudang'	=> $result['nama'],
				'keterangan'	=> $result['keterangan'],
				'tanggal'	=> date('d/m/y',strtotime($result['tanggal'])),
				'tgl_diproses'	=> empty($result['tgl_diproses'])?'Belum Diproses':date('d/m/y',strtotime($result['tgl_diproses'])),
				'no_surat'	=> $result['no_surat'],
				'status'	=> $result['status'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Permohonan Stok Opname';

		$this->data['token'] = $this->session->data['token'];
		$url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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
		$pagination->url = $this->url->link('gudang/permohonanstokopname', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);
	$this->load->model('catalog/gudang');
	$gudangs = $this->model_catalog_gudang->getGudangs(true);
	$this->data['gudangs']=$gudangs;

	$this->data['filter_no_surat'] = $filter_no_surat;
		$this->data['filter_status'] = $filter_status;

		$this->template = 'gudang/permohonanstokopname.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Permohonan Stok Opname');

		$this->load->model('gudang/permohonanstokopname');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_surat=$this->model_gudang_permohonanstokopname->addPermintaanPembelian($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Permohonan Stok Opname berhasil disimpan dengan nomor surat '.$no_surat;

			$url = '';
			if (isset($this->request->get['filter_tanggal'])) {
				$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
			}

			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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

			$this->redirect($this->url->link('gudang/permohonanstokopname', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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

		//get divisi user


		$this->data['token'] = $this->session->data['token'];

		$this->data['cancel']= $this->url->link('gudang/permohonanstokopname', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('gudang/permohonanstokopname/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->load->model('catalog/gudang');
		$gudangs = $this->model_catalog_gudang->getGudangs(true);
		$this->data['gudangs']=$gudangs;

		$this->template = 'gudang/permohonanstokopname_form.tpl';
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
		$this->document->setTitle('Permohonan Stok Opname');
		$url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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
				$this->redirect($this->url->link('gudang/permohonanstokopname', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('gudang/permohonanstokopname', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('catalog/gudang');
		$this->load->model('gudang/permohonanstokopname');

		$column=array('permohonan_stokopname.id','permohonan_stokopname.no_surat','gudang.nama','permohonan_stokopname.keterangan','permohonan_stokopname.tanggal','permohonan_stokopname.tgl_diproses','permohonan_stokopname.status','permohonan_stokopname.gudang_id');
		$join=array();
		$join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=>'permohonan_stokopname.gudang_id',
			'secondtable'	=> 'gudang.gudang_id'
		);

		$data = array(
			'permohonan_stokopname.id'      =>$id,

		);

		$trans=$this->model_gudang_permohonanstokopname->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_gudang_permohonanstokopname->getPermintaanPembelianProduct(array('permohonan_id'	=> $id));
		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['cancel']= $this->url->link('gudang/permohonanstokopname', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'gudang/permohonanstokopname_info.tpl';
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
				$this->redirect($this->url->link('gudang/permohonanstokopname', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('gudang/permohonanstokopname', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('user/divisi');
		$this->load->model('catalog/gudang');
		$this->load->model('gudang/permohonanstokopname');

		$column=array('permohonan_stokopname.id','permohonan_stokopname.no_surat','permohonan_stokopname.tujuan_pembelian','permohonan_stokopname.jenis_pembelian','permohonan_stokopname.jenis_barang','divisi.name','permohonan_stokopname.date_added','permohonan_stokopname.status');
		$join=array();
		$join[]=array(
			'tablename'	=> 'divisi',
			'firsttable'	=>'permohonan_stokopname.divisi_asal',
			'secondtable'	=> 'divisi.id'
		);

		$data = array(
			'permohonan_stokopname.id'      =>$id,

		);

		$trans=$this->model_gudang_permohonanstokopname->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_gudang_permohonanstokopname->getPermintaanPembelianProduct(array('surat_id'	=> $id));
		$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
	//	print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['gudang']=$gudang;

		$this->template = 'gudang/permohonanstokopname_cetak.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function batalkan(){
		$this->load->model('gudang/permohonanstokopname');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_gudang_permohonanstokopname->updatePermintaan(array('status' => 3,'alasan_dibatalkan'=> 'Dibatalkan oleh admin'),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Permohonan Stokopname berhasil dibatalkan.';
			}
		}
		$url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}

		if (isset($this->request->get['filter_no_surat'])) {
			$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
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

			$this->redirect($this->url->link('gudang/permohonanstokopname', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
		public function autocomplete(){
		$rests = array();

		$this->load->model('gudang/permohonanstokopname');
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

			$results = $this->model_gudang_permohonanstokopname->getPermintaanPembelians($column,$join,array(),$data,array(),$limit,$start);
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

		$this->load->model('gudang/permohonanstokopname');
		$this->load->model('catalog/gudang');
		if(isset($this->request->get['surat_id'])){
			if(!empty($this->request->get['surat_id'])){
				$column=array();
				$surat_id=$this->request->get['surat_id'];
				$data = array(
					'id'      =>$surat_id,
					'status'	=> 2
				);

				$trans=$this->model_gudang_permohonanstokopname->getPermintaanPembelian($column,array(),$data);
				$prods=$this->model_gudang_permohonanstokopname->getPermintaanPembelianProduct(array('surat_id'	=> $surat_id));
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
