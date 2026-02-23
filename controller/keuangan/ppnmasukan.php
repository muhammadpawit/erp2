<?php
class ControllerKeuanganPpnmasukan extends Controller {
	private $error=array();
	public function edittable(){
		//echo "<pre>";print_r($this->request->post);exit;
		$update=array(
			'no_fakturpajak'=>$this->request->post['no_fakturpajak'],
		);
		$this->db->update('jurnal_umum_detail',$update,array('id'=>$this->request->post['jurnal_detail_id']));
		echo $this->request->post['no_fakturpajak'];
	}
	public function index() {
		$this->document->setTitle('PPn Masukan');
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			//$filter_date_start ="";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
			//$filter_date_end ="";
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

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		/*if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}*/


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$filter_jenis='1555';

		$this->load->model('keuangan/jurnal');
		$this->load->model('keuangan/coa');

		$this->data['orders'] = array();
		if(!empty($filter_jenis)){
			$data = array(
				'filter_date_start'	     => $filter_date_start,
				'filter_date_end'	     => $filter_date_end,
				'filter_jenis'	=> '1555',
				'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				'limit'                  => $this->config->get('config_admin_limit')
			);
			$filter = array(
				'filter_date_start'	     => $filter_date_start,
				'filter_date_end'	     => $filter_date_end,
				'filter_jenis'	=> '1555',
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);
			$all = $this->model_keuangan_jurnal->jurnalUmums($filter);
			//$all=array();
			foreach ($all as $a) {
				$detail=$this->model_keuangan_jurnal->getdet($a['id']);
				$this->data['alls'][] = array(
					'detail'	=> $detail
				);
			}

			$order_total = $this->model_keuangan_jurnal->totalJurnalUmum($data);
			$results = $this->model_keuangan_jurnal->jurnalUmums($data);
						
			$coa=$this->model_keuangan_coa->getCategoryByKodeRek('1555');
			$this->data['type']=$coa['type'];
			if(!empty($filter_jenis)){
				$this->data['totaldebet']=$this->model_keuangan_jurnal->totalDebet($filter_jenis,$data);
				$this->data['totalkredit']=$this->model_keuangan_jurnal->totalKredit($filter_jenis,$data);
			}else{
				$this->data['totaldebet']=0;
				$this->data['totalkredit']=0;
			}
			foreach ($results as $result) {
				//$detail=$this->model_keuangan_jurnal->getDetailJurnalUmum($result['id'],$filter_jenis);
				$detail=$this->model_keuangan_jurnal->getdet($result['id']);
				$this->data['orders'][] = array(
					'keterangan'	=> $result['keterangan'],
					'tanggal'	=>date('d/m/y',strtotime($result['tanggal'])),
					'ref'	=> $result['ref'],
					'type'	=> $result['type'],
					'detail'	=> $detail
				);
			}
		}

		$this->data['token'] = $this->session->data['token'];

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
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('keuangan/ppnmasukan', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
		$this->data['order_total'] = $order_total;
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_jenis'] = $filter_jenis;

		$this->template = 'keuangan/ppnmasukan.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}


}
?>
