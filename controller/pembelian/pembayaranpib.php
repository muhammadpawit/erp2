<?php
class ControllerPembelianPembayaranpib extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Pembayaran PIB');

		if (isset($this->request->get['filter_no_faktur'])) {
			$filter_no_faktur = $this->request->get['filter_no_faktur'];
		} else {
			$filter_no_faktur = '';
		}
		if (isset($this->request->get['filter_no_pib'])) {
			$filter_no_pib = $this->request->get['filter_no_pib'];
		} else {
			$filter_no_pib = null;
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

		$url='';
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_no_pib'])) {
			$url .= '&filter_no_pib=' . $this->request->get['filter_no_pib'];
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
		$this->data['url']=$url;

		$this->data['insert'] = $this->url->link('pembelian/pembayaranpib/insert', 'token=' . $this->session->data['token'].$url, 'SSL');

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
			'invoice_pembelian_import.inputpib'	=>1,
      'invoice_pembelian_import.statuspembayaranpib'	=> empty($filter_status)?array('>',-1):$filter_status,
			'invoice_pembelian_import.status'	=>array('<>',3)
			//'invoice_pembelian_import.status'	=> empty($filter_status)?array('>=',1):$filter_status,
			//'invoice_pembelian_import.statuspenerimaan'	=> empty($filter_status_penerimaan)?array('<=',3):$filter_status_penerimaan,

		);
		$limit=5;
		$offset=($page - 1) * 5;

		$order=array(
			'invoice_pembelian_import.id'	=> 'DESC',
			'invoice_pembelian_import.status'	=> 'ASC'
		);

		$this->load->model('catalog/gudang');

		$product_total = $this->model_pembelian_invoicepembelianimport->totalPermintaans($data);

		$results = $this->model_pembelian_invoicepembelianimport->getPermintaanPembelians($column,$join,$leftjoin,$data,$order,$limit,$offset);

		foreach ($results as $result) {
			$action = array();

			$pembayaran=$this->model_pembelian_invoicepembelianimport->getPembayaranPib(array('*'),array(),array('order_id'=>$result['id'],'status'=> 1),array('tgl_bayar'=>'ASC'));


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
				'tanggal'	=> date('d/m/y',strtotime($result['tglfaktur'])),
				/*'sub_total'	=> '$'.number_format($result['sub_total'],2,'.',','),
				'diskon'	=> '$'.number_format($result['diskon'],2,'.',','),
				'pajak'	=> '$'.number_format($result['pajak'],0,'.',','),
				'total'	=> '$'.number_format($result['total'],2,'.',','),
				'totalbayar'	=> '$'.number_format($result['totalbayar'],2,'.',','),
				'tanggal'	=> date('d/m/y',strtotime($result['tglfaktur'])),
				'jatuhtempo'	=> date('d/m/y',strtotime($result['jatuhtempo'])),
				'tgllunas'	=> date('d/m/y',strtotime($result['tgllunas'])),
				'jenis_barang'	=> $result['jenisproduk'],*/
				'no_pib'	=> $result['no_pib'],
				'statuspembayaranpib'	=> $result['statuspembayaranpib'] == 0?'Belum Dibayar':($result['statuspembayaranpib']==2?'Dibayar Sebagian':($result['statuspembayaranpib'] == 1?'Lunas':'Dibatalkan')),
				'ppnpib'	=> $this->currency->format($result['ppnpib']),
				'pphpib'	=> $this->currency->format($result['pphpib']),
				'bmpib'	=> $this->currency->format($result['bmpib']),
				'pembayaran'	=> $pembayaran,
				'total'	=> $this->currency->format($result['bmpib']+$result['pphpib']+$result['ppnpib']),
				'status'	=> $result['status'] == 1?'Ditagih':($result['status'] == 2?'Dibayar Sebagian':($result['status'] == 3?'Dibatalkan':'Lunas')),
				'statuspenerimaan'	=> $result['statuspenerimaan'] == 0?'Belum Diterima':($result['statuspenerimaan'] == 2?'Diterima Sebagian':'Sudah Diterima'),
				'actions'	=> $action
			);
		}

		$this->data['heading_title'] = 'Pembayaran PIB';

		$this->data['token'] = $this->session->data['token'];
		$url='';

		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_no_pib'])) {
			$url .= '&filter_no_pib=' . $this->request->get['filter_no_pib'];
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
		$pagination->limit = 5;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('pembelian/pembayaranpib', 'token=' . $this->session->data['token'] . $url . '&page={page}');

		$this->data['pagination'] = $pagination->render();

	//	$this->data['gudangasals']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['filter_no_faktur'] = $filter_no_faktur;
		$this->data['filter_no_pib'] = $filter_no_pib;
		$this->data['filter_jenis_barang'] = $filter_jenis_barang;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_vendor'] = $filter_vendor;

		$this->template = 'pembelian/pembayaranpib.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->load->language('catalog/pembelian');

		$this->document->setTitle('Pembayaran PIB');

		$this->load->model('pembelian/invoicepembelianimport');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
      $no_po=$this->model_pembelian_invoicepembelianimport->addPembayaranPib($this->request->post);

			$this->session->data['success'] = 'Sukses: Data Pembayaran Pib  berhasil disimpan';
			//$this->session->data['success'] =$no_po;
			$url='';
			if (isset($this->request->get['filter_no_faktur'])) {
				$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
			}
			if (isset($this->request->get['filter_no_pib'])) {
				$url .= '&filter_no_pib=' . $this->request->get['filter_no_pib'];
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
			$this->redirect($this->url->link('pembelian/pembayaranpib', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$url='';
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_no_pib'])) {
			$url .= '&filter_no_pib=' . $this->request->get['filter_no_pib'];
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


		if (isset($this->request->post['order_id'])) {
			$this->data['order_id'] = $this->request->post['order_id'];
		}  else {
			$this->data['order_id'] = '';
		}

		if (isset($this->request->post['bank_id'])) {
			$this->data['bank_id'] = $this->request->post['bank_id'];
		}  else {
			$this->data['bank_id'] = '';
		}

		if (isset($this->request->post['nominal'])) {
			$this->data['nominal'] = $this->request->post['nominal'];
		}  else {
			$this->data['nominal'] = '';
		}

		if (isset($this->request->post['keterangan'])) {
			$this->data['keterangan'] = $this->request->post['keterangan'];
		}  else {
			$this->data['keterangan'] = '';
		}

		if (isset($this->request->post['tgl_bayar'])) {
			$this->data['tgl_bayar'] = $this->request->post['tgl_bayar'];
		}  else {
			$this->data['tgl_bayar'] = '';
		}


		$this->data['cancel']= $this->url->link('pembelian/pembayaranpib', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['action']= $this->url->link('pembelian/pembayaranpib/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');

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
        

		$this->template = 'pembelian/pembayaranpib_form.tpl';
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



	public function batalkan(){
		$this->load->model('pembelian/invoicepembelianimport');

		if (isset($this->request->get['id'])) {
			if(!empty($this->request->get['id'])){
      $result=$this->model_pembelian_invoicepembelianimport->batalkanPembayaranpib($this->request->get['id']);

			$this->session->data['success'] = 'Sukses: Data Pembayaran PIB berhasil dibatalkan.';
			//$this->session->data['success'] =json_encode($result);
			}
		}
		$url='';
		if (isset($this->request->get['filter_no_faktur'])) {
			$url .= '&filter_no_faktur=' . $this->request->get['filter_no_faktur'];
		}
		if (isset($this->request->get['filter_no_pib'])) {
			$url .= '&filter_no_pib=' . $this->request->get['filter_no_pib'];
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

			$this->redirect($this->url->link('pembelian/pembayaranpib', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
				$filter_vendor = $this->request->get['f'];
			} else {
				$filter_vendor = null;
			}


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_faktur'         => array('LIKE',$filter_no_po),
				'status'	=> array('<>',3)
			);
			if(!is_null($filter_vendor)){
				$data['vendor_id']	=	$filter_vendor;
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



	public function autocompletepembayaran(){
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
				'no_faktur'         => array('LIKE',$filter_no_po),
				'status'	=> array('<',3)
			);

			$start=0;
			$limit=0;
			$column=array('id','no_faktur','totaltagihan');
			$join=array();
			$leftjoin=array();

			$results = $this->model_pembelian_invoicepembelianimport->getPermintaanPembelians($column,$join,$join,$data,array(),$limit,$start);
			foreach($results as $r){
				$rests[]=array(
					'id'	=> $r['id'],
					//'text'	=> $r['no_po'].' Total $'.number_format($r['total_pembelian'])
					'text'	=> $r['no_faktur'].'- Total Tagihan '
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function detail(){
		$hasil = array();

		$this->load->model('pembelian/permintaanpembelian');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$id=$this->request->get['id'];
			$this->load->model('pembelian/invoicepembelianimport');
			$column=array('invoice_pembelian_import.*');
			$join=array();


			$data = array(
				'invoice_pembelian_import.id'	=> $id,
	      'invoice_pembelian_import.hapus'	=> 0,
				'invoice_pembelian_import.status'	=> array('<',3)
				//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
				//'limit'                  => $this->config->get('config_admin_limit')
			);

			$hasil=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelian($column,$join,$data);
		}
	}
		$this->response->setOutput(json_encode($hasil));


	}




}
?>
