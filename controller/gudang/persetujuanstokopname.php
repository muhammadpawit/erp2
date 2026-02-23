<?php
class ControllerGudangPersetujuanstokopname extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Persetujuan Stok Opname');

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

				/*$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('gudang/permohonanstokopname/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);
		$action[] = array(
				'text' => 'Cetak',
				'href' => $this->url->link('gudang/permohonanstokopname/cetak', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);*/

			if($result['status'] == 1){


				/*$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('gudang/permohonanstokopname/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);*/
				$action[] = array(
					'text' => 'Proses Stok Opname',
					'href' => $this->url->link('gudang/persetujuanstokopname/setujui', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
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

		$this->data['heading_title'] = 'Persetujuan Stok Opname';

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
		$pagination->url = $this->url->link('gudang/persetujuanstokopname', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);
	$this->load->model('catalog/gudang');
	$gudangs = $this->model_catalog_gudang->getGudangs(true);
	$this->data['gudangs']=$gudangs;

	$this->data['filter_no_surat'] = $filter_no_surat;
		$this->data['filter_status'] = $filter_status;
		$this->data['config_qtyhilang'] = $this->config->get('config_qtyhilang');
		$this->data['config_qtyrusak'] = $this->config->get('config_qtyrusak');
		$this->data['config_kelebihanstok'] = $this->config->get('config_kelebihanstok');
		$this->template = 'gudang/persetujuanstokopname.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}


	public function setujui(){
		$this->document->setTitle('Permohonan Stok Opname');
		$this->load->model('gudang/permohonanstokopname');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
      $no_surat=$this->model_gudang_permohonanstokopname->setujuiStokopname($this->request->post,$this->request->get['id']);

			$this->session->data['success'] = 'Sukses: Data Permohonan Stok Opname berhasil diproses.';

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

			$this->redirect($this->url->link('gudang/persetujuanstokopname', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('gudang/persetujuanstokopname', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('gudang/persetujuanstokopname', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if($this->config->get('config_qtyhilang') < 1 | $this->config->get('config_qtyrusak') < 1 | $this->config->get('config_kelebihanstok') < 1){
			$this->redirect($this->url->link('gudang/persetujuanstokopname', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
		$this->data['cancel']= $this->url->link('gudang/persetujuanstokopname', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->data['action']= $this->url->link('gudang/persetujuanstokopname/setujui', 'token=' . $this->session->data['token'] . $url.'&id='.$id, 'SSL');


		$locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
		$this->template = 'gudang/persetujuanstokopname_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}


}
?>
