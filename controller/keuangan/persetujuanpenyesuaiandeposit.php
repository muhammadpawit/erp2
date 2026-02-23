<?php
class ControllerKeuanganPersetujuanpenyesuaiandeposit extends Controller {
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

		$this->data['insert'] = $this->url->link('gudang/permohonanstokopname/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

	

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
					'text' => 'Proses Penyesuaian Deposit',
					'href' => $this->url->link('keuangan/persetujuanpenyesuaiandeposit/setujui', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
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

		$this->data['heading_title'] = 'Persetujuan Stok Opname';

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
		$pagination->url = $this->url->link('keuangan/persetujuanpenyesuaiandeposit', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);
	
	$this->data['filter_no_surat'] = $filter_no_surat;
		$this->data['filter_status'] = $filter_status;
		$this->data['config_kelebihan'] = $this->config->get('config_kelebihan');
		$this->data['config_kekurangan'] = $this->config->get('config_kekurangan');
		$this->template = 'keuangan/persetujuanpenyesuaiandeposit.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}


	public function setujui(){
		$this->document->setTitle('Permohonan Penyesuaian Deposit');
		$this->load->model('keuangan/permohonanpenyesuaiandeposit');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
      $no_surat=$this->model_keuangan_permohonanpenyesuaiandeposit->setujuiPenyesuaian($this->request->post,$this->request->get['id']);

			$this->session->data['success'] = 'Sukses: Data Permohonan Penyesuaian Deposit berhasil diproses.';

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

			$this->redirect($this->url->link('keuangan/persetujuanpenyesuaiandeposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('keuangan/persetujuanpenyesuaiandeposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('keuangan/persetujuanpenyesuaiandeposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if($this->config->get('config_kelebihan') < 1 | $this->config->get('config_kekurangan') < 1){
			$this->redirect($this->url->link('keuangan/persetujuanpenyesuaiandeposit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
		$this->data['cancel']= $this->url->link('keuangan/persetujuanpenyesuaiandeposit', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('keuangan/persetujuanpenyesuaiandeposit/setujui', 'token=' . $this->session->data['token'] . $url.'&id='.$id, 'SSL');


		$locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
		$this->template = 'keuangan/persetujuanpenyesuaiandeposit_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}


}
?>
