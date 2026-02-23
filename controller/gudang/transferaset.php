 <?php
class ControllerGudangTransferaset extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Transfer Aset');

		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			$filter_tanggal = '';
		}

		if (isset($this->request->get['filter_aset_id'])) {
			$filter_aset_id = $this->request->get['filter_aset_id'];
		} else {
			$filter_aset_id = '';
		}

    if (isset($this->request->get['filter_jenisaset'])) {
			$filter_jenisaset = $this->request->get['filter_jenisaset'];
		} else {
			$filter_jenisaset = '';
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

    if (isset($this->request->get['filter_jenisaset'])) {
			$url .= '&filter_jenisaset=' . $this->request->get['filter_jenisaset'];
		}
		if (isset($this->request->get['filter_aset_id'])) {
			$url .= '&filter_aset_id=' . $this->request->get['filter_aset_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('gudang/transferaset/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

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

		$this->load->model('gudang/transferaset');
		$this->load->model('gudang/product');
    $this->load->model('catalog/aset');
    $this->load->model('catalog/tabungmp');

		$this->data['permintaans'] = array();
		$column=array('transfer_aset.*','gudang.nama','product.name');
		$join=array();
    $join[]=array(
			'tablename'	=> 'product',
			'firsttable'	=>'transfer_aset.product_id',
			'secondtable'	=> 'product.product_id'
		);
		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=>'transfer_aset.gudang_id',
			'secondtable'	=> 'gudang.gudang_id'
		);

		$data = array(
			'tgl_tukar'      =>$filter_tanggal,
			'transfer_aset.status'			=> $filter_status,
			'transfer_aset.gudang_id'			=> $filter_gudang_id,
      'transfer_aset.aset_id'			=> $filter_aset_id,
			'transfer_aset.hapus'	=> 0,
      'transfer_aset.jenisaset' => $filter_jenisaset
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'transfer_aset.id'	=> 'DESC',
			'transfer_aset.status'	=> 'ASC'
		);

		$product_total = $this->model_gudang_transferaset->totalTransferAsets($data,$join);

		$results = $this->model_gudang_transferaset->getTransferAsets($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('gudang/transferaset/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);

			if($result['status'] == 1){
				$action[] = array(
					'text' => 'Setujui',
					'href' => $this->url->link('gudang/transferaset/setujui', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);

				$action[] = array(
					'text' => 'Batalkan/Tolak',
					'href' => $this->url->link('gudang/transferaset/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}

			//tabung a
      if($result['jenisaset'] == 1){
        $aset=$this->model_catalog_aset->getAset(array('aset_id'=>$result['aset_id']));
      }else{
        $aset=$this->model_catalog_tabungmp->getTabung($result['aset_id']);
      }
			$this->data['permintaans'][] = array(
				'aset'	=> empty($aset['name'])?$aset['no_tabung']:$aset['name'],
				'name'	=> $result['name'],
        'jenisaset'	=> $result['jenisaset'],
				'quantity'	=> $result['quantity'],
        'no_transferaset'	=> $result['no_transferaset'],
				'gudang'	=> $result['nama'],
				'tanggal'	=> date('d/m/y',strtotime($result['tanggal'])),
				'tgl_proses'	=> !empty($result['tanggal_disetujui'])?date('d/m/y',strtotime($result['tanggal_disetujui'])):'Belum Diproses',
				'status'	=> $result['status'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Permintaan Pembelian';

		$this->data['token'] = $this->session->data['token'];
    $url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}


		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_aset_id'])) {
			$url .= '&filter_aset_id=' . $this->request->get['filter_aset_id'];
		}

    if (isset($this->request->get['filter_jenisaset'])) {
			$url .= '&filter_jenisaset=' . $this->request->get['filter_jenisaset'];
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
		$pagination->url = $this->url->link('gudang/transferaset', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

			$this->data['filter_status'] = $filter_status;
			$this->data['filter_tanggal'] = $filter_tanggal;

		$this->template = 'gudang/transferaset.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Transfer Aset');

		$this->load->model('gudang/transferaset');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_surat=$this->model_gudang_transferaset->addTransferAset($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Transfer Aset Berhasil Disimpan dengan nomor '.$no_surat;

      $url = '';
  		if (isset($this->request->get['filter_tanggal'])) {
  			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
  		}
      if (isset($this->request->get['filter_jenisaset'])) {
  			$url .= '&filter_jenisaset=' . $this->request->get['filter_jenisaset'];
  		}

  		if (isset($this->request->get['filter_gudang_id'])) {
  			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
  		}
  		if (isset($this->request->get['filter_aset_id'])) {
  			$url .= '&filter_aset_id=' . $this->request->get['filter_aset_id'];
  		}

  		if (isset($this->request->get['filter_status'])) {
  			$url .= '&filter_status=' . $this->request->get['filter_status'];
  		}


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('gudang/transferaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    $url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
    if (isset($this->request->get['filter_jenisaset'])) {
			$url .= '&filter_jenisaset=' . $this->request->get['filter_jenisaset'];
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_aset_id'])) {
			$url .= '&filter_aset_id=' . $this->request->get['filter_aset_id'];
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

		$this->data['cancel']= $this->url->link('gudang/transferaset', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('gudang/transferaset/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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

		$this->template = 'gudang/transferaset_form.tpl';
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
		$this->document->setTitle('Transfer Aset');
    $url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}

    if (isset($this->request->get['filter_jenisaset'])) {
			$url .= '&filter_jenisaset=' . $this->request->get['filter_jenisaset'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_aset_id'])) {
			$url .= '&filter_aset_id=' . $this->request->get['filter_aset_id'];
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
				$this->redirect($this->url->link('gudang/transferaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('gudang/transferaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		/*$this->load->model('user/divisi');
		$this->load->model('catalog/gudang');

    */

    $this->load->model('gudang/transferaset');
    $this->load->model('gudang/product');

    $this->load->model('catalog/aset');
    $this->load->model('catalog/tabungmp');

    $column=array('transfer_aset.*','gudang.nama');
		$join=array();
	   $join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=>'transfer_aset.gudang_id',
			'secondtable'	=> 'gudang.gudang_id'
		);

		$data = array(
			'id'      =>$id,
			'hapus'			=>array('<',1),
		);


		$trans=$this->model_gudang_transferaset->getTransferAset($column,$join,$data);

    $product=$this->model_gudang_product->getProduct($trans['product_id'],$trans['gudang_id']);
    if($trans['jenisaset'] == 1){
      $aset=$this->model_catalog_aset->getAset(array('aset_id'=>$trans['aset_id']));
    }else{
      $aset=$this->model_catalog_tabungmp->getTabung($trans['aset_id']);
    }


		$this->data['permintaan']=$trans;
    $this->data['aset']=$aset;
    $this->data['product']=$product;

		$this->data['cancel']= $this->url->link('gudang/transferaset', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'gudang/transferaset_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

  public function setujui(){
    $this->load->model('gudang/transferaset');
		$this->document->setTitle('Tukar Kran');
    if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
      $no_surat=$this->model_gudang_transferaset->prosesTransferAset($this->request->get['id'],$this->request->post['tglproses']);

			$this->session->data['success'] = 'Sukses: Data Transfer Aset Berhasil Diproses';

      $url = '';
  		if (isset($this->request->get['filter_tanggal'])) {
  			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
  		}
      if (isset($this->request->get['filter_jenisaset'])) {
  			$url .= '&filter_jenisaset=' . $this->request->get['filter_jenisaset'];
  		}

  		if (isset($this->request->get['filter_gudang_id'])) {
  			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
  		}
  		if (isset($this->request->get['filter_aset_id'])) {
  			$url .= '&filter_aset_id=' . $this->request->get['filter_aset_id'];
  		}

  		if (isset($this->request->get['filter_status'])) {
  			$url .= '&filter_status=' . $this->request->get['filter_status'];
  		}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('gudang/transferaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
    $url = '';
		if (isset($this->request->get['filter_tanggal'])) {
			$url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
		}
    if (isset($this->request->get['filter_jenisaset'])) {
			$url .= '&filter_jenisaset=' . $this->request->get['filter_jenisaset'];
		}

		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_aset_id'])) {
			$url .= '&filter_aset_id=' . $this->request->get['filter_aset_id'];
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
				$this->redirect($this->url->link('gudang/transferaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('gudang/transferaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		/*$this->load->model('user/divisi');
		$this->load->model('catalog/gudang');

    */
    $this->data['action']= $this->url->link('gudang/transferaset/setujui', 'token=' . $this->session->data['token'] . $url.'&id='.$id, 'SSL');


    $this->load->model('gudang/transferaset');
    $this->load->model('gudang/product');

    $this->load->model('catalog/aset');
    $this->load->model('catalog/tabungmp');

    $column=array('transfer_aset.*','gudang.nama');
		$join=array();
	   $join[]=array(
			'tablename'	=> 'gudang',
			'firsttable'	=>'transfer_aset.gudang_id',
			'secondtable'	=> 'gudang.gudang_id'
		);

		$data = array(
			'id'      =>$id,
			'hapus'			=>array('<',1),
		);


		$trans=$this->model_gudang_transferaset->getTransferAset($column,$join,$data);

    $product=$this->model_gudang_product->getProduct($trans['product_id'],$trans['gudang_id']);
    if($trans['jenisaset'] == 1){
      $aset=$this->model_catalog_aset->getAset(array('aset_id'=>$trans['aset_id']));
    }else{
      $aset=$this->model_catalog_tabungmp->getTabung($trans['aset_id']);
    }


		$this->data['permintaan']=$trans;
    $this->data['aset']=$aset;
    $this->data['product']=$product;

		$this->data['cancel']= $this->url->link('gudang/transferaset', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'gudang/transferaset_setujui.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}


	public function batalkan(){
		$this->load->model('gudang/transferaset');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_gudang_transferaset->updatePermintaan(array('status' => 3),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Transfer Aset berhasil dibatalkan.';
			}
		}
    if (isset($this->request->get['filter_tanggal'])) {
      $url .= '&filter_tanggal=' . $this->request->get['filter_tanggal'];
    }

    if (isset($this->request->get['filter_jenisaset'])) {
			$url .= '&filter_jenisaset=' . $this->request->get['filter_jenisaset'];
		}
    if (isset($this->request->get['filter_gudang_id'])) {
      $url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
    }
    if (isset($this->request->get['filter_aset_id'])) {
      $url .= '&filter_aset_id=' . $this->request->get['filter_aset_id'];
    }

    if (isset($this->request->get['filter_status'])) {
      $url .= '&filter_status=' . $this->request->get['filter_status'];
    }

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('gudang/transferaset', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

  public function autocomplete() {
		$json = array();

		//if (isset($this->request->get['filter_name']) ) {
			$this->load->model('catalog/aset');
      $this->load->model('catalog/tabungmp');

			if (isset($this->request->get['q'])) {
				$filter_name = $this->request->get['q'];
			} else {
				$filter_name = null;
			}

      if (isset($this->request->get['j'])) {
				$jenis = $this->request->get['j'];
			} else {
				$jenis = 1;
			}




			$offset=0;
			$limit=$limit;
      if($jenis == 1){
        $data = array(
        'name'	  => array('LIKE',$filter_name),
        //'tglpembelian'	=>'1970-01-01'
          //'start'               => 0,
          //'limit'               => $limit
        );
			     $results = $this->model_catalog_aset->getAsets(array(),array(),$data,array(),$limit,$offset);
      }else{
        $data = array(
        'no_tabung'	  => array('LIKE',$filter_name),
        //'tglpembelian'	=>'1970-01-01'
          //'start'               => 0,
          //'limit'               => $limit
        );
          $results = $this->model_catalog_tabungmp->getAsets(array('id as aset_id','no_tabung as name'),array(),$data,array(),$limit,$offset);
      }

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['aset_id'],
					'text'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),

				);
			}
		//}

		$this->response->setOutput(json_encode($json));
	}


}
?>
