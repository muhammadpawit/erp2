<?php
class ControllerKeuanganGeneratelabarugi extends Controller {
	public function index() {
		$this->document->setTitle('Laporan Laba Rugi');

		if (isset($this->request->get['filter_periode'])) {
			$filter_periode = $this->request->get['filter_periode'];
		} else {
			$filter_periode = '';
		}


	if (isset($this->request->get['page'])) {
		$page = $this->request->get['page'];
	} else {
		$page = 1;
	}

		$url = '';

		if (isset($this->request->get['filter_periode'])) {
			$url .= '&filter_periode=' . $this->request->get['filter_periode'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}



		$this->data['token'] = $this->session->data['token'];
		$this->data['insert'] = $this->url->link('keuangan/generatelabarugi/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['labarugis'] = array();
		$this->load->model('keuangan/labarugi');

		$data = array(
			'filter_periode'	  => $filter_periode,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);


		$product_total = $this->model_keuangan_labarugi->totallabarugi($data);

		$results = $this->model_keuangan_labarugi->labarugi($data);

		foreach ($results as $result) {
			$action = array();
      if($result['status'] == 1){
          $action[] = array(
              'text' => 'Batalkan',
              'href' => $this->url->link('keuangan/generatelabarugi/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
          );
      }
    	$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('keuangan/generatelabarugi/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'] . $url, 'SSL')
			);

      $this->data['labarugis'][] = array(
				'id' => $result['id'],
				'tglawal'       => date('d/m/y',strtotime($result['tglawal'])),
				'tglselesai'       => date('d/m/y',strtotime($result['tglselesai'])),
				'status'       => $result['status'] == 1?'Disimpan':'Dibatalkan',
				'date_added'	=> date('d/m/y',strtotime($result['date_added'])),
				'labarugi'	=> $this->currency->format($result['labarugi']),
				'action'     => $action
			);
  }

		$this->data['heading_title'] = $this->language->get('Generate Laba Rugi');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';
		if (isset($this->request->get['filter_periode'])) {
			$url .= '&filter_periode=' . $this->request->get['filter_periode'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('keuangan/generatelabarugi', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_periode'] = $filter_periode;


		$this->template = 'keuangan/generatelabarugi.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/atk');

		$this->document->setTitle($this->language->get('Generate Laba Rugi'));

		$this->load->model('keuangan/labarugi');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_keuangan_labarugi->addLabarugi($this->request->post);
			$this->session->data['success'] = "Laporan laba rugi berhasil diproses";

			$url = '';

			if (isset($this->request->get['filter_periode'])) {
				$url .= '&filter_periode=' . $this->request->get['filter_periode'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('keuangan/generatelabarugi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	private function getForm() {
		$this->data['heading_title'] = 'Generate Laba Rugi';

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_periode'])) {
			$url .= '&filter_periode=' . $this->request->get['filter_periode'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['action'] = $this->url->link('keuangan/generatelabarugi/insert', 'token=' . $this->session->data['token']. $url, 'SSL');

		$this->data['cancel'] = $this->url->link('keuangan/generatelabarugi', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['labarugi']=array();
		if($this->request->server['REQUEST_METHOD'] == 'POST'){
					$this->data['labarugi']= $this->request->post;
			}
		else{
					$this->data['labarugi']=array();
			}

		$this->data['token'] = $this->session->data['token'];



		$this->template = 'keuangan/generatelabarugi_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	private function validateForm() {
		if(empty($this->request->post['periode_id'])){
			$this->error['periode'] = 'Periode harus dipilih';
		}
		if(empty($this->request->post['tglawal'])){
			$this->error['tglawal'] = 'Tanggal Awal Perhitungan tidak boleh kosong';
		}

		if(empty($this->request->post['tglselesai'])){
			$this->error['tglawal'] = 'Tanggal Selesai Perhitungan tidak boleh kosong';
		}
		if ($this->error && !isset($this->error['warning'])) {
					$warning = 'Peringatan: Mohon cek error berikut. <br>';
					foreach($this->error as $e){
							$warning .= $e.'<br>';
					}
			$this->error['warning'] = $warning;
		}

		if (!$this->error) {
		return true;
		} else {
				return false;
		}
	}

	public function tampil(){
		$this->data['heading_title'] = 'Generate Laba Rugi';

		$url = '';

		if (isset($this->request->get['filter_periode'])) {
			$url .= '&filter_periode=' . $this->request->get['filter_periode'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['cancel'] = $this->url->link('keuangan/generatelabarugi', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('keuangan/generatelabarugi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('keuangan/generatelabarugi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('keuangan/labarugi');
		$labarugi=$this->model_keuangan_labarugi->getLabarugi($id);

		if(empty($labarugi)){
			$this->redirect($this->url->link('keuangan/generatelabarugi', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$pendapatan=$this->model_keuangan_labarugi->getLabarugiDetail($id,4);
		$hpp=$this->model_keuangan_labarugi->getLabarugiDetail($id,5);
		$biaya=$this->model_keuangan_labarugi->getLabarugiDetail($id,6);
		$biayalain=$this->model_keuangan_labarugi->getLabarugiDetail($id,8);
		$pendapatanlain=$this->model_keuangan_labarugi->getLabarugiDetail($id,7);
		$pendapatanluarbiasa=$this->model_keuangan_labarugi->getLabarugiDetail($id,9);

		$this->data['labarugi']=$labarugi;
		$this->data['pendapatan']=$pendapatan;
		$this->data['hpp']=$hpp;
		$this->data['biaya']=$biaya;
		$this->data['biayalain']=$biayalain;
		$this->data['pendapatanlain']=$pendapatanlain;
		$this->data['pendapatanluarbiasa']=$pendapatanluarbiasa;

		$this->data['token'] = $this->session->data['token'];



		$this->template = 'keuangan/labarugi.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}
}
?>
