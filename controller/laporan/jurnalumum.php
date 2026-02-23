<?php
class ControllerLaporanJurnalumum extends Controller {
	private $error=array();

	public function jurnaltoexcel() {
		$this->document->setTitle('Jurnal Umum');
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_date_start ="";
		}

		if (isset($this->request->get['filter_ref'])) {
			$filter_ref = $this->request->get['filter_ref'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_ref =null;
		}
		
		if (isset($this->request->get['filter_keterangan'])) {
			$filter_keterangan = $this->request->get['filter_keterangan'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_keterangan =null;
		}
		
		if (isset($this->request->get['balance'])) {
			$balance = $this->request->get['balance'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$balance =null;
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			//$filter_date_end = date('Y-m-d');
			$filter_date_end ="";
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

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

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
		
		if (isset($this->request->get['filter_keterangan'])) {
			$url .= '&filter_keterangan=' . $this->request->get['filter_keterangan'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('keuangan/jurnal');
		$this->data['insert'] = $this->url->link('laporan/jurnalumum/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['orders'] = array();

		$data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_jenis'	=> $filter_jenis,
			'filter_ref'	=> $filter_ref,
			'balance'      => $balance,
			'filter_keterangan' => $filter_keterangan
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		
		$tot = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_jenis'	=> $filter_jenis,
			'filter_ref'	=> $filter_ref,
			'balance'      => $balance,
			'filter_keterangan' => $filter_keterangan
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		//$order_total = $this->model_keuangan_jurnal->totalJurnalUmum($data);
		$order_total = count($this->model_keuangan_jurnal->jurnalUmum($tot));
		$results = $this->model_keuangan_jurnal->jurnalUmum($data);
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";print_r($results);exit;
		}
		$detail=$this->model_keuangan_jurnal->getDetailJurnalUmum(4647,$filter_jenis);
		$this->load->model('pembelian/pembeliantunai');
		$this->load->model('pembelian/pembayarandp');
		$getb = $this->model_keuangan_jurnal->getb(33037);
		if($this->user->getUsername()=="pawitas"){
			echo "<pre>";
			print_r($results);exit;
		}
		
		/*foreach($detail as $d){
			$totdeb += $d['debet'];
			$totkred += $d['kredit'];
		}
		echo $totdeb." ".$totkred;exit;
		*/
		if(!empty($filter_jenis)){
			$this->data['totalakun']=$this->model_keuangan_jurnal->totalAkun($filter_jenis,$data);
		}else{
			$this->data['totalakun']=0;
		}

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),8);
		
		foreach ($results as $result) {

			$action=array();
			if($custdata){
				if(empty($result['idref'])){
					$action[] = array(
						'text' => 'Edit',
						'href' => $this->url->link('laporan/jurnalumum/editjurnal', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
					);
					$action[] = array(
						'text' => 'Hapus',
						'href' => $this->url->link('laporan/jurnalumum/hapusjurnal', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
					);
				}
			}

			$detail=$this->model_keuangan_jurnal->getDetailJurnalUmum($result['id'],$filter_jenis,$data=array('balance'=>$balance));
			//$this->data['det'] = count($detail);
			foreach($detail as $d){
				$totdeb += $d['debet'];
				$totkred += $d['kredit'];
			}
			//if(empty($balance)){
				//if($totdeb==$totkred){
					$this->data['orders'][] = array(
						'keterangan'	=> $result['keterangan'],
						'tanggal'	=>date('d/m/y',strtotime($result['tanggal'])),
						'ref'	=> $result['ref'],
						'type'	=> $result['type'],
						'linkterkait'	=> $result['linkterkait'],
						'detail'	=> $detail,
						'no_dokumen'	=> $result['no_dokumen'],
						'urlref'	=> $this->url->link($result['urlref'].'/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'], 'SSL'),
						'action'	=> $action

					);
				//}
			//}
		}
		//echo "<pre>";
		//print_r($detail);exit;

		$this->data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['balance'])) {
			$url .= '&balance=' . $this->request->get['balance'];
		}
		
		if (isset($this->request->get['filter_keterangan'])) {
			$url .= '&filter_keterangan=' . $this->request->get['filter_keterangan'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		$pagination = new Pagination();
		if(!empty($balance)){
			if($balance==2){
				$pagination->total = count($results);
			}else{
				$pagination->total = count($results);
			}
		}else{
			$pagination->total = $order_total;
		}
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_jenis'] = $filter_jenis;
		$this->data['filter_ref'] = $filter_ref;
		$this->data['balance'] = $balance;
		$this->data['filter_keterangan'] = $filter_keterangan;

		$this->template = 'laporan/jurnalumumexcel.tpl';
		
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function index() {
		
		$this->document->setTitle('Jurnal Umum');
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_date_start ="";
		}

		if (isset($this->request->get['filter_ref'])) {
			$filter_ref = $this->request->get['filter_ref'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_ref =null;
		}

		if (isset($this->request->get['filter_nodokumen'])) {
			$filter_nodokumen = $this->request->get['filter_nodokumen'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_nodokumen =null;
		}
		
		if (isset($this->request->get['filter_keterangan'])) {
			$filter_keterangan = $this->request->get['filter_keterangan'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_keterangan =null;
		}
		
		if (isset($this->request->get['balance'])) {
			$balance = $this->request->get['balance'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$balance =null;
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			//$filter_date_end = date('Y-m-d');
			$filter_date_end ="";
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

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

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
		
		if (isset($this->request->get['filter_keterangan'])) {
			$url .= '&filter_keterangan=' . $this->request->get['filter_keterangan'];
		}

		if (isset($this->request->get['filter_nodokumen'])) {
			$url .= '&filter_nodokumen=' . $this->request->get['filter_nodokumen'];
		}
		
		if (isset($this->request->get['balance'])) {
			$url .= '&balance=' . $this->request->get['balance'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('keuangan/jurnal');
		$this->data['insert'] = $this->url->link('laporan/jurnalumum/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['orders'] = array();

		$data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_jenis'	=> $filter_jenis,
			'filter_nodokumen'	=> $filter_nodokumen,
			'filter_ref'	=> $filter_ref,
			'balance'      => $balance,
			'filter_keterangan' => $filter_keterangan,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
			//'limit'                  => 50
		);
		$results = $this->model_keuangan_jurnal->jurnalUmum($data);
		if($this->user->getUsername()=="pawits"){
			print_r($data);exit;
		}
		
		$tot = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_jenis'	=> $filter_jenis,
			'filter_ref'	=> $filter_ref,
			'filter_nodokumen'	=> $filter_nodokumen,
			'balance'      => $balance,
			'filter_keterangan' => $filter_keterangan
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$hh=767;
		//$order_total = $this->model_keuangan_jurnal->totalJurnalUmum($data);

		$order_total = count($this->model_keuangan_jurnal->jurnalUmum($tot));
		$results = $this->model_keuangan_jurnal->jurnalUmum($data);
		$t = $this->model_keuangan_jurnal->totalss($data);
		if($this->user->getUsername()=="pawits"){
			echo "<pre>";print_r($results);exit;
		}

		$detail=$this->model_keuangan_jurnal->getDetailJurnalUmum(4647,$filter_jenis);
		$this->load->model('pembelian/pembeliantunai');
		$this->load->model('pembelian/pembayarandp');
		$getb = $this->model_keuangan_jurnal->getb(33037);
		/*foreach($detail as $d){
			$totdeb += $d['debet'];
			$totkred += $d['kredit'];
		}
		echo $totdeb." ".$totkred;exit;
		*/
		if(!empty($filter_jenis)){
			$this->data['totalakun']=$this->model_keuangan_jurnal->totalAkun($filter_jenis,$data);
		}else{
			$this->data['totalakun']=0;
		}

		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),8);
		foreach ($results as $result) {

			$action=array();
			if($custdata){
				if($this->user->getUsername()=="yuliana" OR $this->user->getUsername()=="pawit" OR $this->user->getUsername()=="roro"){
				//if(empty($result['idref']) OR $this->user->getUsername()=="yuliana"){
					$action[] = array(
						'text' => 'Edit',
						'href' => $this->url->link('laporan/jurnalumum/editjurnal', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
					);
					$action[] = array(
						'text' => 'Hapus',
						'href' => $this->url->link('laporan/jurnalumum/hapusjurnal', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
					);
				}
			}

			

			$detail=$this->model_keuangan_jurnal->getDetailJurnalUmum($result['id'],$filter_jenis,$data=array('balance'=>$balance));
			//$this->data['det'] = count($detail);
			foreach($detail as $d){
				$totdeb += $d['debet'];
				$totkred += $d['kredit'];
			}

			if($result['urlref'] == 'sale/penjualan' | $result['urlref'] == 'sale/invoice'){
				$urlref=$this->url->link($result['urlref'].'/tampil', 'token=' . $this->session->data['token'] . '&order_id=' . $result['idref'], 'SSL');
			}else if($result['urlref'] == 'pembelian/tagihanbiayaimport' | $result['urlref'] == 'pembelian/tagihanbiayalokal'){
				$urlref=$this->url->link($result['urlref'], 'token=' . $this->session->data['token'] . '&filter_no_faktur=' . $result['idref'], 'SSL');
			}
			else{
				$urlref=$this->url->link($result['urlref'].'/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['idref'], 'SSL');
			}
			//if(empty($balance)){
				//if($totdeb==$totkred){
					$this->data['orders'][] = array(
						'keterangan'	=> $result['keterangan'],
						'tanggal'	=>date('d/m/y',strtotime($result['tanggal'])),
						'ref'	=> $result['ref'],
						'type'	=> $result['type'],
						'linkterkait'	=> $result['linkterkait'],
						'no_dokumen'	=> $result['no_dokumen'],
						'detail'	=> $detail,
						'urlref'	=> $urlref,
						'idref'	=> $result['idref'],
						'action'	=> $action

					);
				//}
			//}
		}
		//echo "<pre>";
		//print_r($detail);exit;

		$this->data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['balance'])) {
			$url .= '&balance=' . $this->request->get['balance'];
		}
		
		if (isset($this->request->get['filter_keterangan'])) {
			$url .= '&filter_keterangan=' . $this->request->get['filter_keterangan'];
		}
		if (isset($this->request->get['filter_nodokumen'])) {
			$url .= '&filter_nodokumen=' . $this->request->get['filter_nodokumen'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		$pagination = new Pagination();
		if(!empty($balance)){
			if($balance==2){
				$pagination->total = count($results);
			}else{
				$pagination->total = $t;
			}
		}else{
			$pagination->total = $order_total;
		}
		
		if($this->user->getUsername()=="pawits"){
			echo $pagination->total;
		}
		$pagination->page = $page;
		if(!empty($balance)){
			if($balance==2){
				$pagination->limit = $this->config->get('config_admin_limit');
			}else{
				$pagination->limit = 50;
			}
		}else{
			$pagination->limit = $this->config->get('config_admin_limit');
		}
		
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;
		$this->data['filter_jenis'] = $filter_jenis;
		$this->data['filter_ref'] = $filter_ref;
		$this->data['balance'] = $balance;
		$this->data['filter_keterangan'] = $filter_keterangan;
		$this->data['exportexcel']=$this->url->link('laporan/jurnalumum/jurnaltoexcel', 'token=' . $this->session->data['token'] . $url ,'SSL');
		$this->template = 'laporan/jurnalumum.tpl';
		if (isset($this->request->get['excel'])==1) {
			$this->template = 'laporan/jurnalumumexcel.tpl';
		}
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	

	public function hapusjurnal() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Jurnal Umum');

		$this->load->model('keuangan/jurnal');
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
		if (isset($this->request->get['filter_nodokumen'])) {
			$url .= '&filter_nodokumen=' . $this->request->get['filter_nodokumen'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}


		if (isset($this->request->get['id'])) {
			if(empty($this->request->get['id'])){
				$this->redirect($this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'].$url , 'SSL'));
			}else{
				$id = $this->request->get['id'];
				$this->model_keuangan_jurnal->hapusJurnal($id);
				$this->session->data['success'] = 'Sukses: Data Jurnal Umum berhasil dihapus';
				$this->redirect($this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'].$url , 'SSL'));
			}

		} else {
			$this->redirect($this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'].$url , 'SSL'));

		}


	}

	public function editjurnal() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Jurnal Umum');

		$this->load->model('keuangan/jurnal');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			//echo "<pre>";print_r($this->request->post);exit;
			$no_po=$this->model_keuangan_jurnal->editJurnalUmumManual($this->request->post,$this->request->get['id']);

			$this->session->data['success'] = 'Sukses: Data Jurnal Umum berhasil diperbarui';

			$url = '';

			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
			if (isset($this->request->get['filter_keterangan'])) {
				$url .= '&filter_keterangan=' . $this->request->get['filter_keterangan'];
			}
			if (isset($this->request->get['balance'])) {
				$url .= '&balance=' . $this->request->get['balance'];
			}
			if (isset($this->request->get['filter_nodokumen'])) {
				$url .= '&filter_nodokumen=' . $this->request->get['filter_nodokumen'];
			}

			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
			if (isset($this->request->get['filter_jenis'])) {
				$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_keterangan'])) {
			$url .= '&filter_keterangan=' . $this->request->get['filter_keterangan'];
		}
		if (isset($this->request->get['balance'])) {
			$url .= '&balance=' . $this->request->get['balance'];
		}			

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_jenis'])) {
			$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
		}
		if (isset($this->request->get['filter_nodokumen'])) {
			$url .= '&filter_nodokumen=' . $this->request->get['filter_nodokumen'];
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

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['id'])) {
			$id = $this->request->get['id'];
		} else {
			if(empty($this->request->get['id'])){
				$this->redirect($this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'] , 'SSL'));
			}
		}
		$this->data['action']= $this->url->link('laporan/jurnalumum/editjurnal', 'token=' . $this->session->data['token'].'&id='.$id . $url, 'SSL');

		$jurnal=$this->model_keuangan_jurnal->getJurnalUmum(array('id'=>$id));
			$detail=$this->model_keuangan_jurnal->getDetailJurnalUmum($jurnal['id'],array());

		$this->data['jurnal']=$jurnal;
		$this->data['detail']=$detail;


		$this->data['cancel']= $this->url->link('laporan/jurnalumum', 'token=' . $this->session->data['token'] . $url, 'SSL');


		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = array();
		}

		$this->template = 'laporan/jurnalumum_edit.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}



	private function validateForm() {

		if(!is_numeric($this->request->post['nominal']) ){
			$this->error['nominal'] = 'Jumlah Pembayaran Harus Berupa Angka';
		}


		//print_r($cek);
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}


}
?>
