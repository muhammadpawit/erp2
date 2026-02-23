<?php
class ControllerPembelianPembayaranpembelianimport extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Pembayaran Pembelian Import');

		if (isset($this->request->get['filter_no_faktur'])) {
			$filter_no_faktur = $this->request->get['filter_no_faktur'];
		} else {
			$filter_no_faktur = '';
		}


		if (isset($this->request->get['filter_jenis_barang'])) {
			$filter_jenis_barang = $this->request->get['filter_jenis_barang'];
		} else {
			$filter_jenis_barang = null;
		}

		if (isset($this->request->get['filter_vendor'])) {
			$filter_vendor = $this->request->get['filter_vendor'];
		} else {
			$filter_vendor = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$filter_status_penerimaan = $this->request->get['filter_status_penerimaan'];
		} else {
			$filter_status_penerimaan = null;
		}



		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['insert'] = $this->url->link('pembelian/pembayaranpembelianimport/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

		/*$this->load->model('report/product');
        $this->load->model('catalog/product');
		*/

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->session->data['warning'])) {
			$this->data['warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} else {
			$this->data['warning'] = '';
		}

		/*$this->load->model('catalog/product');
		$this->load->model('gudang/product');
		$this->load->model('pembelian/permintaanpembelian');
		$this->load->model('catalog/gudang');
		*/

		$this->load->model('catalog/vendorimport');
		$this->load->model('pembelian/invoicepembelianimport');

		$this->data['permintaans'] = array();
		$column=array('invoice_pembelian_import.*','vendorimport.name','gudang.nama');
		$join=array();
		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'invoice_pembelian_import.vendor_id'
		);

		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'	=> 'gudang',
			'secondtable'	=>'gudang.gudang_id',
			'firsttable'	=> 'invoice_pembelian_import.gudang_id'
		);

		$data = array(
			//'no_po'      =>array('LIKE',$filter_no_po),
			'invoice_pembelian_import.id'      =>$filter_no_faktur,
			'vendor_id'=> $filter_vendor,
			//'surat_id'=> $filter_no_surat,
			'jenisproduk'	=> $filter_jenis_barang,
      'invoice_pembelian_import.status'	=> empty($filter_status)?array('<>',3):$filter_status,
			'invoice_pembelian_import.statuspenerimaan'	=> empty($filter_status_penerimaan)?array('<=',3):$filter_status_penerimaan,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);
		$limit=20;
		$offset=($page - 1) * $this->config->get('config_admin_limit');

		$order=array(
			'invoice_pembelian_import.id'	=> 'DESC',
			'invoice_pembelian_import.status'	=> 'ASC'
		);

		$this->load->model('catalog/gudang');

		$product_total = $this->model_pembelian_invoicepembelianimport->totalPermintaans($data);

		$results = $this->model_pembelian_invoicepembelianimport->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('pembelian/pembayaranpembelianimport/tampil', 'token=' . $this->session->data['token'] . '&id=' . $result['id'].$url, 'SSL')
			);


			$gudang="";
			if($result['gudang_id'] > 0){
				$g=$this->model_catalog_gudang->getGudang($result['gudang_id']);
				$gudang=$g['nama'];
			}
			$this->data['permintaans'][] = array(
				'name'	=> $result['name'],
				'gudang'	=> $result['nama'],
				'no_faktur'	=> $result['no_faktur'],
				'id'	=> $result['id'],
				'total'	=> '$'.number_format($result['total'],2,'.',','),
				'totalbayar'	=> '$'.number_format($result['totalbayar'],2,'.',','),
				'totalbayarrp'	=> $this->currency->format($result['totalbayarrp']),
				'tanggal'	=> date('d/m/y',strtotime($result['tglfaktur'])),
				'jatuhtempo'	=> date('d/m/y',strtotime($result['jatuhtempo'])),
				'tgllunas'	=> date('d/m/y',strtotime($result['tgllunas'])),
				'status'	=> $result['status'] == 1?'Ditagih':($result['status'] == 2?'Dibayar Sebagian':'Lunas'),
				'statuspenerimaan'	=> $result['statuspenerimaan'] == 0?'Belum Diterima':($result['statuspenerimaan'] == 2?'Diterima Sebagian':'Sudah Diterima'),
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Pembayaran Pembelian Import';

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pembelian/pembayaranpembelianimport', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_faktur'] = $filter_no_faktur;
		$this->data['filter_no_po'] = $filter_no_po;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_vendor'] = $filter_vendor;

		$this->template = 'pembelian/pembayaranpembelianimport.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Alokasi Pembayaran Pembelian Import');

		$this->load->model('pembelian/invoicepembelianimport');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_po=$this->model_pembelian_invoicepembelianimport->addPembayaran($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Pembayaran pembelian import berhasil disimpan ';

			if (isset($this->request->get['filter_no_faktur'])) {
				$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
			}
			if (isset($this->request->get['filter_jenis_barang'])) {
				$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
			}

			if (isset($this->request->get['filter_vendor'])) {
				$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_status_penerimaan'])) {
				$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('pembelian/pembayaranpembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
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

		if (isset($this->request->post['vendor_id'])) {
			$this->data['vendor_id'] = $this->request->post['vendor_id'];
		}  else {
			$this->data['vendor_id'] = '';
		}




		$this->data['cancel']= $this->url->link('pembelian/pembayaranpembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/pembayaranpembelianimport/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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
        

	$this->template = 'pembelian/pembayaranpembelianimport_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

	}

	private function validateForm() {

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}

	public function tampil(){
		$this->document->setTitle('Invoice Pembelian Import');
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			}else{
				$this->redirect($this->url->link('pembelian/pembayaranpembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('pembelian/pembayaranpembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->data['url']=$url;

		$this->load->model('pembelian/invoicepembelianimport');
		$column=array('invoice_pembelian_import.*','vendorimport.name');
		$join=array();
		$join[]=array(
			'tablename'	=> 'vendorimport',
			'secondtable'	=>'vendorimport.id',
			'firsttable'	=> 'invoice_pembelian_import.vendor_id'
		);

		$data = array(
			'invoice_pembelian_import.id'	=> $id,
      'invoice_pembelian_import.hapus'	=> 0,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
		);

		$trans=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelian($column,$join,$data);
		$pembayarans=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelianPembayaran(array('invoice_id'=>$id));

		//print_r($pembayarans);*/
		$this->data['permintaan']=$trans;
		$this->data['pembayarans']=$pembayarans;

		$this->data['cancel']= $this->url->link('pembelian/pembayaranpembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->template = 'pembelian/pembayaranpembelianimport_info.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function batalkan(){
		$this->load->model('pembelian/invoicepembelianimport');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      	$this->model_pembelian_invoicepembelianimport->batalPembayaran($this->request->get['id']);

				$this->session->data['success'] = 'Sukses: Data Alokasi Pembayaran Pembelian Import berhasil dibatalkan.';
			}
		}
		$url='';
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_jenis_barang'])) {
			$url .= '&filter_jenis_barang=' . $this->request->get['filter_jenis_barang'];
		}

		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_status_penerimaan'])) {
			$url .= '&filter_status_penerimaan=' . $this->request->get['filter_status_penerimaan'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

			$this->redirect($this->url->link('pembelian/pembayaranpembelianimport', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}
	public function autocomplete(){
		$rests = array();

		$this->load->model('pembelian/invoicepembelianimport');

			if (isset($this->request->get['q'])) {
				$filter_no_po = $this->request->get['q'];
			} else {
				$filter_no_po = '';
			}
			if (isset($this->request->get['f'])) {
				$filter_faktur = $this->request->get['f'];
			} else {
				$filter_faktur = null;
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_faktur'         => array('LIKE',$filter_no_po)
			);
			if(!is_null($filter_fatur)){
				$data['tglfaktur']	=	array('<>','1970-01-01');
			}
			$start=0;
			$limit=0;
			$column=array('id','no_faktur','total');
			$join=array();
			$leftjoin=array();

			$results = $this->model_pembelian_invoicepembelianimport->getPermintaanPembelians($column,$join,$join,$data,array(),$limit,$start);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					//'text'	=> $r['no_po'].' Total $'.number_format($r['total_pembelian'])
					'text'	=> $r['no_faktur']
				);
			}
		$this->response->setOutput(json_encode($rests));
	}



}
?>
