<?php
class ControllerKepegawaianLemburjam extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Lembur Pegawai');

		$this->load->model('kepegawaian/lemburjam');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Lembur Pegawai');

		$this->load->model('kepegawaian/lemburjam');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_kepegawaian_lemburjam->addIjin($this->request->post);

			$this->session->data['success'] = 'Data Lembur Pegawai berhasil ditambahkan.';
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
			$this->redirect($this->url->link('kepegawaian/lemburjam', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Lembur Pegawai');

		$this->load->model('kepegawaian/lemburjam');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_kepegawaian_lemburjam->updateIjin($this->request->post, array('id'=>$this->request->get['id']));

			$this->session->data['success'] = 'Data Lembur Pegawai berhasil diperbarui';

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

			$this->redirect($this->url->link('kepegawaian/lemburjam', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function batalkan() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Lembur Pegawai');

		$this->load->model('kepegawaian/lemburjam');

		if (isset($this->request->get['id']) && $this->validateDelete()) {
			//foreach ($this->request->post['selected'] as $id) {

				$data=array('status'=>3);
				$where=array('id' => $this->request->get['id']);
				$this->model_kepegawaian_lemburjam->updateIjin($data,$where);
			//}

			$this->session->data['success'] = 'Data Lembur Pegawai berhasil dibatalkan';

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


			$this->redirect($this->url->link('kepegawaian/lemburjam', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getList();
	}

	public function setujui() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Lembur Pegawai');

		$this->load->model('kepegawaian/lemburjam');

		if (isset($this->request->get['id']) && $this->validateDelete()) {
			//foreach ($this->request->post['selected'] as $id) {
			//cek status
				$where=array('id' => $this->request->get['id'],'hapus'	=> 0);
				$cek=$this->model_kepegawaian_lemburjam->getIjin($where);
				if(!empty($cek)){
					if($cek['status'] == 1){
						$data=array('status'=>2);

						$this->model_kepegawaian_lemburjam->updateIjin($data,$where);
					//}

						$this->session->data['success'] = 'Data Lembur Pegawai berhasil disetujui';
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


						$this->redirect($this->url->link('kepegawaian/lemburjam', 'token=' . $this->session->data['token'].$url, 'SSL'));

					}
					if($cek['status'] == 2){
						$this->error['warning']="Permohonan Ijin Telah Disetujui";
					}
					if($cek['status'] == 3){
						$this->error['warning']="Permohonan Ijin yang telah dibatalkan tidak dapat disetujui";
					}
				}



		}

		$this->getList();
	}

	public function delete() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Lembur Pegawai');

		$this->load->model('kepegawaian/lemburjam');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $id) {
				$data=array('hapus'	=> 1);
				$where=array('id' => $id);
				$this->model_kepegawaian_lemburjam->updateIjin($data,$where);
			}

			$this->session->data['success'] = 'Data Lembur Pegawai berhasil dihapus';

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


			$this->redirect($this->url->link('kepegawaian/lemburjam', 'token=' . $this->session->data['token'].$url, 'SSL'));
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
			$filter_date_start = "";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = "";
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

   	$this->data['insert'] = $this->url->link('kepegawaian/lemburjam/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('kepegawaian/lemburjam/delete', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['absensis'] = array();

		$column=array('ijin_pegawai.*,users.firstname');
		$join=array();
		$join[]=array(
			'tablename'=>'users',
			'firsttable'	=> 'ijin_pegawai.pegawai_id',
			'secondtable'	=> 'users.user_id'
		);

		$data = array(
			'users.firstname'	  => array('LIKE',$filter_name),
			'tgl_awal'	=> !empty($filter_date_start)?$filter_date_start:array('>','1901-01-01'),
			'tgl_akhir'	=> !empty($filter_date_end)?$filter_date_end:array('>','1901-01-01'),
			'ijin_pegawai.hapus'	=> array('<',1)
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$order=array(
			'tgl_awal'	=> 'DESC',
			'users.firstname'	=> 'ASC'
		);

		$results = $this->model_kepegawaian_lemburjam->getIjins($column,$join,$data,$order,$limit,$offset);
		$product_total = $this->model_kepegawaian_lemburjam->totalIjins($data,$join);
		//echo $product_total;

		foreach ($results as $result) {
			$action = array();

			if($result['status'] == 1){
				$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('kepegawaian/lemburjam/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'], 'SSL')
				);
				$action[] = array(
					'text' => 'Setujui',
					'href' => $this->url->link('kepegawaian/lemburjam/setujui', 'token=' . $this->session->data['token'] . '&id=' . $result['id'], 'SSL')
				);
			}


		//	$cek= $this->model_kepegawaian_lemburjam->cekOption($result['id']);

			$this->data['absensis'][] = array(
				'id' => $result['id'],
				'name'        => $result['firstname'],
				'keperluan'        => $result['keperluan'],
				'status'        => $result['status'],
				'tgl_awal'=> date('d/m/y',strtotime($result['tgl_awal'])),
				'tgl_akhir'=> date('d/m/y',strtotime($result['tgl_akhir'])),

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
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('kepegawaian/lemburjam', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'kepegawaian/lemburjam_list.tpl';
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
			$this->data['action'] = $this->url->link('kepegawaian/lemburjam/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('kepegawaian/lemburjam/update', 'token=' . $this->session->data['token'].$url. '&id=' . $this->request->get['id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('kepegawaian/lemburjam', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$option_info = $this->model_kepegawaian_lemburjam->getAbsensi(array('id'	=> $this->request->get['id']));
    	}

		$this->data['token'] = $this->session->data['token'];


		if (!empty($this->request->post)) {
			$this->data['pegawai_id'] = $this->request->post['pegawai_id'];
			$this->data['tgl_awal'] = $this->request->post['tgl_awal'];
			$this->data['tgl_akhir'] = $this->request->post['tgl_akhir'];
			$this->data['keperluan'] = $this->request->post['keperluan'];
			$this->data['status'] = $this->request->post['status'];


		} elseif (!empty($option_info)) {
			$this->data['pegawai_id'] = $option_info['pegawai_id'];
			$this->data['tgl_awal'] = $option_info['tgl_awal'];
			$this->data['tgl_akhir'] = $option_info['tgl_akhir'];
			$this->data['keperluan'] = $option_info['keperluan'];
			$this->data['status'] = $this->request->post['status'];

		} else {
			$this->data['pegawai_id'] = 0;
			$this->data['tgl_awal'] = date('Y-m-d',time());
			$this->data['tgl_akhir'] = date('Y-m-d',time());
			$this->data['keperluan'] = '';

		}


		$this->template = 'kepegawaian/lemburjam_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'kepegawaian/lemburjam')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Lembur Pegawai';
		}

		/*$this->load->model('kepegawaian/lemburjam');
		$cek=$this->model_kepegawaian_lemburjam->getIjin(array('tgl_awal'=>array('>=',$this->request->post['tgl_awal']),'tgl_akhir'=>array('<=',$this->request->post['tgl_awal']),'status'=>array('<>',3),'hapus'=>array('<',1),'pegawai_id'=>$this->request->post['pegawai_id']));
		if(!empty($cek)){
			$this->error['name']	= 'Duplikasi data absensi';
		}*/
		if(strtotime($this->request->post['tgl_awal']) > strtotime($this->request->post['tgl_akhir'])){
			$this->error['tgl']	= 'Tanggal awal ijin harus kurang dari tanggal akhir ijin.';
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
		if (!$this->user->hasPermission('modify', 'kepegawaian/lemburjam')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Lembur Pegawai';
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}



}
?>
