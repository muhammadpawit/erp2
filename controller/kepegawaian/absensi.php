<?php
class ControllerKepegawaianAbsensi extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Absensi Pegawai');

		$this->load->model('kepegawaian/absensi');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Absensi Pegawai');

		$this->load->model('kepegawaian/absensi');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_kepegawaian_absensi->addAbsensi($this->request->post);

			$this->session->data['success'] = 'Data Absensi Pegawai berhasil ditambahkan.';
			$url = '';
			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('kepegawaian/absensi', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Absensi Pegawai');

		$this->load->model('kepegawaian/absensi');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_kepegawaian_absensi->updateAbsensi($this->request->post, array('id'=>$this->request->get['id']));

			$this->session->data['success'] = 'Data Absensi Pegawai berhasil diperbarui';

			$url = '';
			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('kepegawaian/absensi', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function batalkan() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Absensi Pegawai');

		$this->load->model('kepegawaian/absensi');

		if (isset($this->request->get['id']) && $this->validateDelete()) {
			//foreach ($this->request->post['selected'] as $id) {

				$data=array('hapus'	=> 1,'status'=>0);
				$where=array('absensi_id' => $this->request->get['id']);
				$this->model_kepegawaian_absensi->updateAbsensi($data,$where);
			//}

			$this->session->data['success'] = 'Data Absensi Pegawai berhasil dihapus';

			$url = '';
			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			$this->redirect($this->url->link('kepegawaian/absensi', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date("Y-m-d", strtotime("first day of this month"));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date("Y-m-d", strtotime("last day of this month"));
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

   	$this->data['insert'] = $this->url->link('kepegawaian/absensi/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('kepegawaian/absensi/delete', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['absensis'] = array();

		$column=array('absensi.*,users.firstname');
		$join=array();
		$join[]=array(
			'tablename'=>'users',
			'firsttable'	=> 'absensi.pegawai_id',
			'secondtable'	=> 'users.user_id'
		);

		$data = array(
			'users.firstname'	  => array('LIKE',$filter_name),
			'tanggal'	=> array('>=',$filter_date_start),
			'tanggal'	=> array('<=',$filter_date_end),
			'absensi.hapus'	=> array('<',1)
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$order=array(
			'tanggal'	=> 'DESC',
			'users.firstname'	=> 'ASC'
		);

		$results = $this->model_kepegawaian_absensi->getAbsensis($column,$join,$data,$order,$limit,$offset);
		$product_total = $this->model_kepegawaian_absensi->totalAbsensis($data,$join);
		//echo $product_total;

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Batalkan',
				'href' => $this->url->link('kepegawaian/absensi/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['absensi_id'], 'SSL')
			);


		//	$cek= $this->model_kepegawaian_absensi->cekOption($result['id']);

			$this->data['absensis'][] = array(
				'absensi_id' => $result['absensi_id'],
				'name'        => $result['firstname'],
				'tanggal'=> date('d/m/y',strtotime($result['tanggal'])),
				'jam_datang'        => date('H:i:s',strtotime($result['jam_datang'])),
				'jam_pulang'	=> date('H:i:s',strtotime($result['jam_pulang'])),
				//'cek'		=> $cek,
				'action'      => $action
			);
		}



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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('kepegawaian/absensi', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'kepegawaian/absensi_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getForm() {

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

 		if (!empty($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = array();
		}

 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}



		if (!isset($this->request->get['id'])) {
			$this->data['action'] = $this->url->link('kepegawaian/absensi/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('kepegawaian/absensi/update', 'token=' . $this->session->data['token'].$url. '&id=' . $this->request->get['id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('kepegawaian/absensi', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$option_info = $this->model_kepegawaian_absensi->getAbsensi(array('absensi_id'	=> $this->request->get['id']));
    	}

		$this->data['token'] = $this->session->data['token'];


		if (!empty($this->request->post)) {
			$this->data['pegawai_id'] = $this->request->post['pegawai_id'];
			$this->data['jam_datang'] = $this->request->post['jam_datang'];
			$this->data['jam_pulang'] = $this->request->post['jam_pulang'];
			$this->data['tanggal'] = $this->request->post['tanggal'];


		} elseif (!empty($option_info)) {
			$this->data['pegawai_id'] = $option_info['pegawai_id'];
			$this->data['jam_datang'] = $option_info['jam_datang'];
			$this->data['jam_pulang'] = $option_info['jam_pulang'];
			$this->data['tanggal'] = $option_info['tanggal'];

		} else {
			$this->data['pegawai_id'] = '';
			$this->data['jam_datang'] = date('H:i:s',time());
			$this->data['jam_pulang'] = date('H:i:s',time());
			$this->data['tanggal'] = date('Y-m-d',time());

		}


		$this->template = 'kepegawaian/absensi_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		/*if (!$this->user->hasPermission('modify', 'kepegawaian/absensi')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Absensi Pegawai';
		}*/

		$this->load->model('kepegawaian/absensi');
		$cek=$this->model_kepegawaian_absensi->getAbsensi(array('tanggal'=>$this->request->post['tanggal'],'status'=>1,'hapus'=>0,'pegawai_id'=>$this->request->post['pegawai_id']));
		if(!empty($cek)){
			$this->error['name']	= 'Duplikasi data absensi';
		}
		if(strtotime($this->request->post['jam_datang']) > strtotime($this->request->post['jam_pulang'])){
			$this->error['jam']	= 'Jam Datang harus kurang dari jam pulang.';
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = 'Mohon cek kembali form Anda.';
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function validateDelete() {
		/*if (!$this->user->hasPermission('modify', 'kepegawaian/absensi')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Absensi Pegawai';
		}*/

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}



}
?>
