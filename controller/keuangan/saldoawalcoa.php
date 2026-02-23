<?php
class ControllerKeuanganSaldoawalcoa extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Saldo Awal COA');

		$this->load->model('keuangan/saldoawalcoa');

		$this->getList();
	}

	public function insert() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Saldo Awal COA');

		$this->load->model('keuangan/saldoawalcoa');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if($this->user->getUsername()=="pawit"){
				echo "<pre>";print_r($this->request->post);exit();
			}
			$this->model_keuangan_saldoawalcoa->addSaldo($this->request->post);

			$this->session->data['success'] = 'Data Saldo Awal COA berhasil ditambahkan.';
			$url = '';
            if (isset($this->request->get['filter_tahun'])) {
				$url .= '&filter_tahun=' . urlencode(html_entity_decode($this->request->get['filter_tahun'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kode_rek'])) {
				$url .= '&filter_kode_rek=' . urlencode(html_entity_decode($this->request->get['filter_kode_rek'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('keuangan/saldoawalcoa', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Saldo Awal COA');

		$this->load->model('keuangan/saldoawalcoa');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_keuangan_saldoawalcoa->updateSaldo($this->request->post, array('id'=>$this->request->get['id']));

			$this->session->data['success'] = 'Data Saldo Awal COA berhasil diperbarui';

            $url = '';
            if (isset($this->request->get['filter_tahun'])) {
				$url .= '&filter_tahun=' . urlencode(html_entity_decode($this->request->get['filter_tahun'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kode_rek'])) {
				$url .= '&filter_kode_rek=' . urlencode(html_entity_decode($this->request->get['filter_kode_rek'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


			$this->redirect($this->url->link('keuangan/saldoawalcoa', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/category');

		$this->document->setTitle('Saldo Awal COA');

		$this->load->model('keuangan/saldoawalcoa');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $id) {
				$data=array('hapus'	=> 1);
				$where=array('id' => $id);
				$this->model_keuangan_saldoawalcoa->updateSaldo($data,$where);
			}

			$this->session->data['success'] = 'Data Saldo Awal COA berhasil dihapus';

            $url = '';
            if (isset($this->request->get['filter_tahun'])) {
				$url .= '&filter_tahun=' . urlencode(html_entity_decode($this->request->get['filter_tahun'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kode_rek'])) {
				$url .= '&filter_kode_rek=' . urlencode(html_entity_decode($this->request->get['filter_kode_rek'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}



			$this->redirect($this->url->link('keuangan/saldoawalcoa', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
        }
        
        if (isset($this->request->get['filter_tahun'])) {
			$filter_tahun = $this->request->get['filter_tahun'];
		} else {
			$filter_tahun = 0;
		}

		if (isset($this->request->get['filter_kode_rek'])) {
			$filter_kode_rek = $this->request->get['filter_kode_rek'];
		} else {
			$filter_kode_rek = null;
		}

		if (isset($this->request->get['filter_type'])) {
			$filter_type = $this->request->get['filter_type'];
		} else {
			$filter_type = null;
        }
        if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
        $url = '';
            if (isset($this->request->get['filter_tahun'])) {
                $url .= '&filter_tahun=' . urlencode(html_entity_decode($this->request->get['filter_tahun'], ENT_QUOTES, 'UTF-8'));
            }

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kode_rek'])) {
				$url .= '&filter_kode_rek=' . urlencode(html_entity_decode($this->request->get['filter_kode_rek'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}


   	$this->data['insert'] = $this->url->link('keuangan/saldoawalcoa/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('keuangan/saldoawalcoa/delete', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['saldos'] = array();
        $column=array('coamnb.name','coamnb.type','saldoawalcoa.*');
        $join=array();
        $leftjoin=array();
        $leftjoin[]=array(
            'tablename' => 'coamnb',
            'firsttable'    => 'saldoawalcoa.kode_rek',
            'secondtable'   => 'coamnb.kode_rek'
        );
		$data = array(
            'name'	  => array('LIKE',$filter_name),
            'saldoawalcoa.kode_rek' => $filter_kode_rek,
            'type'  => $filter_type,
            'tahun' =>$filter_tahun > 0?$filter_tahun:array('>=','2018'),
            'hapus' => array('<',1)
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		$offset=($page - 1) * $this->config->get('config_admin_limit');
		$limit=$this->config->get('config_admin_limit');

		$results = $this->model_keuangan_saldoawalcoa->getSaldos($column,$join,$leftjoin,$data,array('coamnb.kode_rek'=>'ASC','tahun'=>'DESC','coamnb.type'=>'ASC'),$limit,$offset);
		$product_total = $this->model_keuangan_saldoawalcoa->totalSaldos($data,$join,$leftjoin);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Edit',
				'href' => $this->url->link('keuangan/saldoawalcoa/update', 'token=' . $this->session->data['token'] . '&id=' . $result['id'], 'SSL')
			);
			
			

		//	$cek= $this->model_keuangan_saldoawalcoa->cekOption($result['id']);
            if($result['type'] == 1){
                $type="Aset";
            }
            if($result['type'] == 2){
                $type="Hutang";
            }
            if($result['type'] == 3){
                $type="Modal";
            }
            if($result['type'] == 4){
                $type="Pendapatan";
            }
            if($result['type'] == 5){
                $type="Harga Pokok Penjualan";
            }
            if($result['type'] == 6){
                $type="Beban";
            }
            if($result['type'] == 7){
                $type="Pendapatan Lain-lain";
            }
            if($result['type'] == 8){
                $type="Beban Lain-lain";
            }
			$this->data['saldos'][] = array(
				'id' => $result['id'],
                'name'        => $result['name'],
                'type'        => $type,
                'kode_rek'  => $result['kode_rek'],
                'tahun' => $result['tahun'],
                'debet' => $this->currency->format($result['debet']),
                'kredit'    => $this->currency->format($result['kredit']),
				'selected'    => isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
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
        if (isset($this->request->get['filter_tahun'])) {
            $url .= '&filter_tahun=' . urlencode(html_entity_decode($this->request->get['filter_tahun'], ENT_QUOTES, 'UTF-8'));
        }

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kode_rek'])) {
				$url .= '&filter_kode_rek=' . urlencode(html_entity_decode($this->request->get['filter_kode_rek'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
			}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('keuangan/saldoawalcoa', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'keuangan/saldoawalcoa_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function getForm() {

        $url = '';
        if (isset($this->request->get['filter_tahun'])) {
            $url .= '&filter_tahun=' . urlencode(html_entity_decode($this->request->get['filter_tahun'], ENT_QUOTES, 'UTF-8'));
        }

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_kode_rek'])) {
				$url .= '&filter_kode_rek=' . urlencode(html_entity_decode($this->request->get['filter_kode_rek'], ENT_QUOTES, 'UTF-8'));
			}
			if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . urlencode(html_entity_decode($this->request->get['filter_type'], ENT_QUOTES, 'UTF-8'));
			}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'][] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['duplicate'])) {
			$this->data['error_warning'][]= $this->error['duplicate'];
		} else {
			$this->data['error_name'] = '';
		}



		if (!isset($this->request->get['id'])) {
			$this->data['action'] = $this->url->link('keuangan/saldoawalcoa/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('keuangan/saldoawalcoa/update', 'token=' . $this->session->data['token'].$url. '&id=' . $this->request->get['id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('keuangan/saldoawalcoa', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$option_info = $this->model_keuangan_saldoawalcoa->getSaldo(array('id'	=> $this->request->get['id']));
		}
		

		$this->data['token'] = $this->session->data['token'];


		if (!empty($this->request->post)) {
            if(isset($this->request->get['id']) ){
				//$option_info = $this->model_keuangan_saldoawalcoa->getSaldo(array('id'	=> $this->request->get['id']));
				$this->data['id']=$this->request->get['id'];
				$option_info = $this->model_keuangan_saldoawalcoa->getSaldo(array('id'	=> $this->request->get['id']));
				$this->data['kode_rek'] = $option_info['kode_rek'];
				$this->data['tahun'] = $option_info['tahun'];
				$this->data['name'] = $option_info['name'];

			}else{
				$this->data['id']=0;
				$this->data['kode_rek'] = $this->request->post['kode_rek'];
            	$this->data['tahun'] = $this->request->post['tahun'];
            
			}
            $this->data['debet'] = $this->request->post['debet'];
			$this->data['kredit'] = $this->request->post['kredit'];

		} elseif (!empty($option_info)) {
            $this->data['id']=$this->request->get['id'];
			$this->data['kode_rek'] = $option_info['kode_rek'];
			$this->data['tahun'] = $option_info['tahun'];
			$this->data['debet'] = $option_info['debet'];
            $this->data['kredit'] = $option_info['kredit'];
            $this->data['name'] = $option_info['name'];
			
		} else {
            $this->data['id']=0;
			$this->data['kode_rek'] = '';
			$this->data['tahun'] = date('Y',time()) - 1;
			$this->data['debet'] = 0;
			$this->data['kredit'] = 0;
			
        }
        $this->load->model('keuangan/coa');

        $categories = $this->model_keuangan_coa->getCategories(0);

		// Remove own id from list
		if (!empty($category_info)) {
			foreach ($categories as $key => $category) {
				if ($category['category_id'] == $category_info['category_id']) {
					unset($categories[$key]);
				}
			}
		}

		$this->data['categories'] = $categories;


		$this->template = 'keuangan/saldoawalcoa_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validateForm() {
		/*if (!$this->user->hasPermission('modify', 'keuangan/saldoawalcoa')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Vendor Lokal';
		}

		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 255)) {
			$this->error['name'] = 'Nama vendor harus diisi.';
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = 'Mohon cek kembali form Anda.';
        }*/
        $this->load->model('keuangan/saldoawalcoa');
		//$cek=$this->model_keuangan_saldoawalcoa->getSaldo(array('tahun'=>$this->request->post['tahun'],'saldoawalcoa.kode_rek'=>$this->request->post['kode_rek'],'saldoawalcoa.hapus'=>0));
		$cek=$this->model_keuangan_saldoawalcoa->ceksaldocoa($this->request->post['tahun'],$this->request->post['kode_rek']);
		if($this->user->getUsername()=="pawit"){
			echo "<pre>";print_r($cek);exit();
		}
		if(!isset($this->request->get['id'])){
			if(!empty($cek)){
				$this->error['duplicate'] = 'Duplikat data: Saldo Awal untuk akun '.$this->request->post['kode_rek'].' tahun '.$this->request->post['tahun'].' telah disimpan.';
			}
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
		/*if (!$this->user->hasPermission('modify', 'keuangan/saldoawalcoa')) {
			$this->error['warning'] = 'Anda tidak memiliki hak untuk memodifikasi menu Vendor Lokal';
		}*/

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function autocomplete() {
		$json = array();

		$this->load->model('keuangan/saldoawalcoa');



			if (isset($this->request->get['q'])) {
				$filter_name = $this->request->get['q'];
			} else if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = null;
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
			'name'	  => array('LIKE',$filter_name),
				//'start'               => 0,
				//'limit'               => $limit
			);
			$offset=0;
			$limit=$limit;

			$results = $this->model_keuangan_saldoawalcoa->getVendors($data,array(),$limit,$offset);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['id'],
					'text'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}


		$this->response->setOutput(json_encode($json));
	}

	

}
?>
