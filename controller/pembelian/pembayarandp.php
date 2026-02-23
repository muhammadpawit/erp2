 <?php
class ControllerPembelianPembayarandp extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Pembayaran DP ');

		if (isset($this->request->get['filter_no_po'])) {
			$filter_no_po = $this->request->get['filter_no_po'];
		} else {
			$filter_no_po = '';
		}


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('pembelian/pembayarandp/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

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

		$this->load->model('pembelian/pembeliankredit');
		$this->load->model('pembelian/pembayarandp');

		$this->data['permintaans'] = array();
		$column=array('pembayaran_dp.*','banks.name as nama');
		$join=array();


		$data = array(
			'no_po'      =>$filter_no_po,
			'status'	=> array('<>',3),

		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'date_added'	=> 'DESC',

		);
		$col=array("COALESCE(SUM(jumlah),0) as total");
		$totalbayar=$this->model_pembelian_pembayarandp->getPermintaanPembelian($col,$join,$data);
		$this->data['totalbayar']=$this->currency->format($totalbayar['total']);

		$totalpembelian=$this->model_pembelian_pembeliankredit->getPermintaanPembelian(array('COALESCE(SUM(sub_total),0) as sub_total','COALESCE(SUM(pajak),0) as pajak','COALESCE(SUM(diskon),0) as diskon'),array(),array('no_po'=>$filter_no_po));
		$this->data['totalpembelian']=$this->currency->format($totalpembelian['sub_total']+$totalpembelian['pajak']-$totalpembelian['diskon']);

		$join[]=array(
			'tablename'=>'banks',
			'firsttable'	=> 'pembayaran_dp.bank_id',
			'secondtable'=> 'banks.id'
		);

		$product_total = $this->model_pembelian_pembayarandp->totalPermintaans($data);

		$results = $this->model_pembelian_pembayarandp->getPermintaanPembelians($column,$join,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();
			if($result['status'] == 1){
			$action[] = array(
					'text' => 'Batalkan',
					'href' => $this->url->link('pembelian/pembayarandp/batalkan', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
				);
			}
			$this->data['permintaans'][] = array(
				'no_po'	=> $result['no_po'],
				'nama'	=> $result['nama'],
				'jumlah'	=> $this->currency->format($result['jumlah']),
				'tanggal'	=> date('d/m/y',strtotime($result['date_added'])),
				'status'	=> $result['status'],
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Pembayaran DP ';

		$this->data['token'] = $this->session->data['token'];
		$url = '';

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pembelian/pembayarandp', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_po'] = $filter_no_po;

		$this->template = 'pembelian/pembayarandp.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Pembayaran DP ');

		$this->load->model('pembelian/pembayarandp');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_po=$this->model_pembelian_pembayarandp->addPembelian($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Pembayaran DP untuk PO '.$this->request->post['no_po'].' berhasil disimpan';

			$url = '';

			if (isset($this->request->get['filter_no_po'])) {
				$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('pembelian/pembayarandp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url = '';

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
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

		if (isset($this->request->post['no_po'])) {
			$this->data['vendor_id'] = $this->request->post['vendor_id'];
		}  else {
			$this->data['vendor_id'] = '';
		}

		if (isset($this->request->post['surat_id'])) {
			$this->data['no_po'] = $this->request->post['no_po'];
		}  else {
			$this->data['no_po'] = '';
		}

		if (isset($this->request->post['jumlah'])) {
			$this->data['jumlah'] = $this->request->post['jumlah'];
		}  else {
			$this->data['jumlah'] = '';
		}


		$this->data['cancel']= $this->url->link('pembelian/pembayarandp', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/pembayarandp/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->error)) {
			$this->data['error_warning'] = $this->error;
		} else {
			$this->data['error_warning'] = array();
		}
        
         $locktanggal=$this->config->get('config_locktanggal');

		if(!empty($locktanggal)){
			$this->data['locktanggal']=$locktanggal;

		}else{
			$this->data['locktanggal']=date('Y-m-d');
		}
        

		$this->template = 'pembelian/pembayarandp_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {

		if (empty($this->request->post['no_po'])) {
		  		$this->error['no_po'] = 'Nomor PO tidak boleh kosong';
			}
		if(!is_numeric($this->request->post['jumlah']) ){
			$this->error['jumlah'] = 'Jumlah Pembayaran Harus Berupa Angka';
		}else{
			//cek
			$this->load->model('pembelian/pembayarandp');
			$cek=$this->load->model_pembelian_pembayarandp->getPermintaanPembelian(array('COALESCE(SUM(jumlah),0) as total'),array(),array('hapus'	=> array('<',1),'status' =>1,'no_po'=>$this->request->post['no_po']));
			/*if(!empty($cek)){
				$this->error['jumlah'] = 'Duplikasi data pembayaran DP';
			}*/

			$this->load->model('pembelian/pembeliankredit');
			$kred=$this->model_pembelian_pembeliankredit->getPermintaanPembelian(array(),array(),array('no_po'=>$this->request->post['no_po'],'hapus'	=> array('<',1),'status' =>0));
			if(!empty($kred)){
				if(($cek['total']+$this->request->pos['jumlah']) > $kred['total_pembelian']){
					$this->error['jumlah'] = 'Nilai DP melebihi nilai total pembelian.';
				}
			}else{
				$this->error['jumlah'] = "Pembelian tidak ditemukan";
			}
		}
		//print_r($cek);
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function tampil(){
		$this->document->setTitle('Pembayaran DP ');
		$url = '';

		if (isset($this->request->get['filter_no_po'])) {
			$url .= '&filter_no_po=' . $this->request->get['filter_no_po'];
		}

		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/pembayarandp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/pembayarandp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/pembayarandp');

		$column=array('pembelian_kredit.*','permintaan_pembelian.no_surat','vendorlokal.name','permintaan_pembelian.jenis_barang');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_kredit.surat_id'
		);
		$join[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'pembelian_kredit.vendor_id'
		);

		$data = array(
			'pembelian_kredit.id'	=> $id,
      'permintaan_pembelian.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_pembayarandp->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_pembelian_pembayarandp->getPermintaanPembelianProduct(array('pembelian_id'	=> $id));
		//print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;
		$this->data['cancel']= $this->url->link('pembelian/pembayarandp', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/pembayarandp_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function cetak(){
		$this->document->setTitle('Pembayaran DP ');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/pembayarandp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/pembayarandp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('pembelian/pembayarandp');

		$column=array('pembelian_kredit.*','permintaan_pembelian.no_surat','vendorlokal.name','permintaan_pembelian.jenis_barang');
		$join=array();
		$join[]=array(
			'tablename'	=> 'permintaan_pembelian',
			'secondtable'	=>'permintaan_pembelian.id',
			'firsttable'	=> 'pembelian_kredit.surat_id'
		);
		$join[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'pembelian_kredit.vendor_id'
		);

		$data = array(
			'pembelian_kredit.id'	=> $id,
      'permintaan_pembelian.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_pembayarandp->getPermintaanPembelian($column,$join,$data);
		$prods=$this->model_pembelian_pembayarandp->getPermintaanPembelianProduct(array('pembelian_id'	=> $id));
		//print_r($prods);

		$this->data['permintaan']=$trans;
		$this->data['products']=$prods;

		$this->template = 'pembelian/pebeliankredit_cetak.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function batalkan(){
		$this->load->model('pembelian/pembayarandp');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $this->model_pembelian_pembayarandp->updatePermintaan(array('status' => 3),array('id'	=> $this->request->get['id']));

			$this->session->data['success'] = 'Sukses: Data Pembayaran DP  berhasil dibatalkan.';
			}
		}
			$url = '';

			if (isset($this->request->get['filter_no_surat'])) {
				$url .= '&filter_no_surat=' . $this->request->get['filter_no_surat'];
			}

			if (isset($this->request->get['filter_jenis_pembelian'])) {
				$url .= '&filter_jenis_pembelian=' . $this->request->get['filter_jenis_pembelian'];
			}

			if (isset($this->request->get['filter_jenis_barang'])) {
				$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
			}

			if (isset($this->request->get['filter_divisi'])) {
				$url .= '&filter_divisi=' . $this->request->get['filter_divisi'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}


			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('pembelian/pembayarandp', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	public function autocomplete(){
		$rests = array();

		$this->load->model('pembelian/permintaanpembelian');

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

			$data = array(
				'no_surat'         => array('LIKE',$filter_no_surat),
				'status'	=> 2,
				'jenis_pembelian'	=> $jenis_pembelian
			);
			$start=0;
			$limit=0;
			$column=array('id','no_surat');
			$join=array();

			$results = $this->model_pembelian_permintaanpembelian->getPermintaanPembelians($column,$join,$data,array(),$limit,$start);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_surat']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}


}
?>
