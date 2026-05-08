<?php
class ControllerLaporanImportKomisiSales extends Controller {
	private $error = array();

	public function poinbarang() { // master poin
		$this->load->language('catalog/category');
		$this->load->model('gudang/product');
		$this->document->setTitle('Laporan Penjualan Detail');
		
		$this->load->model('sale/invoice');
		$this->load->model('gudang/product');
		if (isset($this->request->get['filter_provinsi'])) {
			$filter_provinsi = $this->request->get['filter_provinsi'];
		} else {
			$filter_provinsi = null;
		}
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['kodebarang'])) {
			$filter_date_start = $this->request->get['kodebarang'];
		} else {
			$filter_date_start = null;
			//$filter_date_start =null;
		}
		if (isset($this->request->get['nama'])) {
			$filter_date_end = $this->request->get['nama'];
		} else {
			$filter_date_end = null;
			//$filter_date_end =null;
		}
		if (isset($this->request->get['filter_register_start'])) {
			$filter_register_start = $this->request->get['filter_register_start'];
		} else {
			$filter_register_start = "";
		}
		if (isset($this->request->get['filter_sales'])) {
			$filter_sales = $this->request->get['filter_sales'];
		} else {
			$filter_sales =null;
		}
		if (isset($this->request->get['filter_register_end'])) {
			$filter_register_end = $this->request->get['filter_register_end'];
		} else {
			$filter_register_end = "";
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'invoice.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('laporan/importkomisisales/hapusinv', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['batal'] = $this->url->link('laporan/importkomisisales', 'token=' . $this->session->data['token'], 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['importhargaterendahnew'] = $this->url->link('laporan/importkomisisales/importhargaterendahnew', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['excel'] = $this->url->link('laporan/importkomisisales/excel', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['importlunas'] = $this->url->link('laporan/importkomisisales/import_lunas', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['penjualans'] = array();
		$this->load->model('catalog/gudang');
		$this->data['total']=0;
		$this->load->model('catalog/title');
		$this->load->model('sale/penjualan');
		$this->load->model('setting/setting');
		$this->load->model('import/komisisales');
		$filter=array(
			'tanggal'=>$filter_date_start,
			'tanggal2'=>$filter_date_end,
			'filter_sales'=>$filter_sales,
			'filter_status'=>$filter_status,
			'filter_gudang_id'=>$filter_gudang_id,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);
		$filterall=array(
			'tanggal'=>$filter_date_start,
			'tanggal2'=>$filter_date_end,
			'filter_sales'=>$filter_sales,
			'filter_status'=>$filter_status,
			'filter_gudang_id'=>$filter_gudang_id,
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		
		$this->load->model('localisation/country');
		$sql="SELECT * FROM product_baru WHERE hapus=0 ";
		
		if(isset($this->request->get['id'])){
			$sql.=" AND id='".$this->request->get['id']."' ";
		}

		if(!empty($filter_date_start)){
			$sql.=" AND LOWER(kodebarang)='".strtolower($filter_date_start)."' ";
		}

		if(!empty($filter_date_end)){
			$sql.=" AND LOWER(nama) LIKE '%".strtolower($filter_date_end)."%' ";
		}
		$sql.=" ORDER BY nama ";
		$results = $this->db->query($sql)->rows;
		foreach($results as $r){
			$this->data['prods'][]=array(
				'id'=>$r['id'],
				'kodebarang'=>$r['kodebarang'],
				'nama'=>$r['nama'],
				'poin'=>$r['poin'],

			);
		}

		$this->data['simpan']=$this->url->link('laporan/importkomisisales/poinbarang', 'token=' . $this->session->data['token'], 'SSL');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} elseif (isset($this->session->data['error_warning'])) {
			$this->data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
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
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}




		$this->data['sort_date_added'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.date_added' . $url, 'SSL');
		$this->data['sort_register'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=customer.date_added' . $url, 'SSL');
		$this->data['sort_tgl_lunas'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.tgllunas' . $url, 'SSL');
		$this->data['sort_customer'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=customer.name' . $url, 'SSL');
		$this->data['sort_tagihan'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.totaltagihan' . $url, 'SSL');
		$this->data['sort_bayar'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.totalbayar' . $url, 'SSL');
		$this->data['sort_invoice'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.id' . $url, 'SSL');
		$this->data['sort_metode_pembayaran'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.metode_pembayaran' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.status' . $url, 'SSL');
		$this->data['sort_nama'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&nama=asc' . $url, 'SSL');
		
		$this->data['uploadex'] = $this->url->link('laporan/importkomisisales/import', 'token=' . $this->session->data['token'] . '&nama=asc' . $url, 'SSL');
		$url = '';
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			//echo "<pre>";print_r($this->request->post);exit;
			if(isset($this->request->post['add'])){
				$tambah=array(
					'kodebarang'=>strtoupper($this->request->post['kodebarang']),
					'nama'=>($this->request->post['nama'])
				);
				$this->db->insert('product_baru',$tambah);
				$this->session->data['success'] = 'Sukses: Data berhasil disimpan';
			}else if(isset($this->request->post['delete'])){
				$sql="UPDATE product_baru set hapus=1 WHERE id='".$this->request->post['id']."' ";
				$this->db->query($sql);
				$this->session->data['success'] = 'Sukses: Data berhasil dihapus';
			}else{
				$sql="UPDATE product_baru set poin='".$this->request->post['poin']."' WHERE id='".$this->request->post['id']."' ";
				$this->db->query($sql);

				$this->session->data['success'] = 'Sukses: Data berhasil diperbarui';
			}
			

			$url = '';

			if (isset($this->request->post['id'])) {
				$url .= '&id=' . $this->request->post['id'];
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

			if (isset($this->request->get['nama'])) {
				$url .= '&nama=' . $this->request->get['nama'];
			}
			if (isset($this->request->get['filter_jenis'])) {
				$url .= '&filter_jenis=' . $this->request->get['filter_jenis'];
			}
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->redirect($this->url->link('laporan/importkomisisales/poinbarang', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_date_start']	= $filter_date_start;
		$this->data['filter_date_end']	= $filter_date_end;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_gudang_id']	= $filter_gudang_id;
		$this->data['filter_sales']	= $filter_sales;
		$this->data['token'] = $this->session->data['token'];
		$this->data['exporttoexcel'] = $this->url->link('laporan/komisisales', '&print=1&token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['filter_provinsi'] = $filter_provinsi;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		if(!isset($this->request->get['print'])){
			$this->template = 'laporan/poinbarang.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
		}else{
			$this->template = 'laporan/hapusinv.tpl';
		}



		$this->response->setOutput($this->render());
	}

	public function inv() { // hapus komisi sales
		$this->load->language('catalog/category');
		$this->load->model('gudang/product');
		$this->document->setTitle('Laporan Penjualan Detail');
		
		$this->load->model('sale/invoice');
		$this->load->model('gudang/product');
		if (isset($this->request->get['filter_provinsi'])) {
			$filter_provinsi = $this->request->get['filter_provinsi'];
		} else {
			$filter_provinsi = null;
		}
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = null;
			//$filter_date_start =null;
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = null;
			//$filter_date_end =null;
		}
		if (isset($this->request->get['filter_register_start'])) {
			$filter_register_start = $this->request->get['filter_register_start'];
		} else {
			$filter_register_start = "";
		}
		if (isset($this->request->get['filter_sales'])) {
			$filter_sales = $this->request->get['filter_sales'];
		} else {
			$filter_sales =null;
		}
		if (isset($this->request->get['filter_register_end'])) {
			$filter_register_end = $this->request->get['filter_register_end'];
		} else {
			$filter_register_end = "";
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'invoice.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('laporan/importkomisisales/hapusinv', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['batal'] = $this->url->link('laporan/importkomisisales', 'token=' . $this->session->data['token'], 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['importhargaterendahnew'] = $this->url->link('laporan/importkomisisales/importhargaterendahnew', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['excel'] = $this->url->link('laporan/importkomisisales/excel', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['importlunas'] = $this->url->link('laporan/importkomisisales/import_lunas', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['penjualans'] = array();
		$this->load->model('catalog/gudang');
		$this->data['total']=0;
		$this->load->model('catalog/title');
		$this->load->model('sale/penjualan');
		$this->load->model('setting/setting');
		$this->load->model('import/komisisales');
		$filter=array(
			'tanggal'=>$filter_date_start,
			'tanggal2'=>$filter_date_end,
			'filter_sales'=>$filter_sales,
			'filter_status'=>$filter_status,
			'filter_gudang_id'=>$filter_gudang_id,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);
		$filterall=array(
			'tanggal'=>$filter_date_start,
			'tanggal2'=>$filter_date_end,
			'filter_sales'=>$filter_sales,
			'filter_status'=>$filter_status,
			'filter_gudang_id'=>$filter_gudang_id,
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		
		$this->load->model('localisation/country');
		$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		$this->data['total_data'] = $this->model_import_komisisales->countInv($filterall);

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} elseif (isset($this->session->data['error_warning'])) {
			$this->data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
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
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}




		$this->data['sort_date_added'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.date_added' . $url, 'SSL');
		$this->data['sort_register'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=customer.date_added' . $url, 'SSL');
		$this->data['sort_tgl_lunas'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.tgllunas' . $url, 'SSL');
		$this->data['sort_customer'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=customer.name' . $url, 'SSL');
		$this->data['sort_tagihan'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.totaltagihan' . $url, 'SSL');
		$this->data['sort_bayar'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.totalbayar' . $url, 'SSL');
		$this->data['sort_invoice'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.id' . $url, 'SSL');
		$this->data['sort_metode_pembayaran'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.metode_pembayaran' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.status' . $url, 'SSL');
		$this->data['sort_nama'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&nama=asc' . $url, 'SSL');
		
		$this->data['uploadex'] = $this->url->link('laporan/importkomisisales/import', 'token=' . $this->session->data['token'] . '&nama=asc' . $url, 'SSL');
		$url = '';
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			//echo "<pre>";print_r($this->request->post);exit;
			$sql="UPDATE inv_komisi_sales set hapus=1 WHERE tglinvoice BETWEEN '".$this->request->get['filter_date_start']."' AND '".$this->request->get['filter_date_end']."' ";
			$this->db->query($sql);
			$count = $this->db->countAffected();

			$this->session->data['success'] = 'Sukses: ' . $count . ' Data Umum berhasil dihapus';

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
			$this->redirect($this->url->link('laporan/importkomisisales/inv', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_date_start']	= $filter_date_start;
		$this->data['filter_date_end']	= $filter_date_end;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_gudang_id']	= $filter_gudang_id;
		$this->data['filter_sales']	= $filter_sales;
		$this->data['token'] = $this->session->data['token'];
		$this->data['exporttoexcel'] = $this->url->link('laporan/komisisales', '&print=1&token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['filter_provinsi'] = $filter_provinsi;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		if(!isset($this->request->get['print'])){
			$this->template = 'laporan/hapusinv.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
		}else{
			$this->template = 'laporan/hapusinv.tpl';
		}



		$this->response->setOutput($this->render());
	}
	
	public function import_lunas(){
		$this->load->model('import/komisisales');
		$fileExt = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
		$d=array();
		$a=1;
		$iu=array();
		$pengiriman=1;
		$sales_id=0;
		//echo "<pre>";print_r($_FILES["file"]);exit;
		if(in_array($fileExt, ['xls', 'xlsx'])){
	  
			  $targetPath = DIR_SYSTEM.'uploads/'.$_FILES['file']['name'];
			  move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
			  
			  $Reader = new SpreadsheetReader($targetPath);
			  
			  $sheetCount = count($Reader->sheets());
			  for($i=0;$i<$sheetCount;$i++)
			  {
				  
				  $Reader->ChangeSheet($i);
				  
				  foreach ($Reader as $Row)
				  {
					  if($a>1){
						$ceks=$this->model_import_komisisales->cek_iv($Row[1]);
						if(!empty($ceks)){
							if($Row[2]=='Lunas'){
								$statusbayar=1;
								$this->db->update('inv_komisi_sales',array('status'=>1,'tgllunas'=>date('Y-m-d',strtotime($Row[0]))),array('nomorinvoice'=>$Row[1]));
							}else{
								$statusbayar=2;
								$this->db->update('inv_komisi_sales',array('status'=>2,'tgllunas'=>null),array('nomorinvoice'=>$Row[1]));
							}
						}
					  }
					  $a++;
				   }
			   }
			   //echo "<pre>";print_r(($d));exit;
			   $this->session->data['success'] = 'Data compare berhasil di update';
		}
		else
		{ 
			  $this->session->data['error_warning'] = "Invalid File Type. Upload Excel File dengan format xls/xlsx.";
		}
		if (isset($targetPath) && file_exists($targetPath)) {
			unlink($targetPath);
		}

		$url = '';

		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . urlencode(html_entity_decode($this->request->get['filter_sales'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}

			if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->redirect($this->url->link('laporan/importkomisisales', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function compare(){
		$this->load->language('catalog/category');
		$this->load->model('gudang/product');
		$this->document->setTitle('Laporan Penjualan Detail');
		
		$this->load->model('sale/invoice');
		$this->load->model('gudang/product');
		if (isset($this->request->get['filter_provinsi'])) {
			$filter_provinsi = $this->request->get['filter_provinsi'];
		} else {
			$filter_provinsi = null;
		}
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d',strtotime('First day of last month'));
			//$filter_date_start =null;
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d',strtotime('Last day of last month'));
			//$filter_date_end =null;
		}
		if (isset($this->request->get['filter_register_start'])) {
			$filter_register_start = $this->request->get['filter_register_start'];
		} else {
			$filter_register_start = "";
		}
		if (isset($this->request->get['filter_sales'])) {
			$filter_sales = $this->request->get['filter_sales'];
		} else {
			$filter_sales =null;
		}
		if (isset($this->request->get['filter_register_end'])) {
			$filter_register_end = $this->request->get['filter_register_end'];
		} else {
			$filter_register_end = "";
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'invoice.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/invoice/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/invoice/delete', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['cetak'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'].'&print=1'.$url, 'SSL');
		$this->data['importhargaterendahnew'] = $this->url->link('laporan/importkomisisales/importhargaterendahnew', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['excel'] = $this->url->link('laporan/importkomisisales/excel', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['penjualans'] = array();
		$this->load->model('catalog/gudang');
		$this->data['total']=0;
		$this->load->model('catalog/title');
		$this->load->model('sale/penjualan');
		$this->load->model('setting/setting');
		$this->load->model('import/komisisales');
		$filter=array(
			'tanggal'=>$filter_date_start,
			'tanggal2'=>$filter_date_end,
			'filter_sales'=>$filter_sales,
			'filter_status'=>$filter_status,
			'filter_gudang_id'=>$filter_gudang_id,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);
		$filterall=array(
			'tanggal'=>$filter_date_start,
			'tanggal2'=>$filter_date_end,
			'filter_sales'=>$filter_sales,
			'filter_status'=>$filter_status,
			'filter_gudang_id'=>$filter_gudang_id,
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		$i=0;
		$this->data['hargaterendah']=0;
		
		$import=$this->model_import_komisisales->GetImportGroupCompare($filter);
		$importAll=$this->model_import_komisisales->GetImportGroupCompare($filterall);
		$this->data['saless']=$this->model_import_komisisales->GetImportGroupSales();
		
		$nilaiivs=0;
		$tot=0;
		$sat=array();
		$nomorinvoice=array();
		$totivs=0;
		$harga_terendah=0;
		$poin=0;
		$biayatransport=0;
		$products=array();
		$totalhargaterendah=0;
		$biayatransport=0;
		foreach($import as $im){
			$get=$this->model_import_komisisales->getcompare($im['nomorinvoice']);
			$res=$this->model_import_komisisales->GetImportdetailsCompare($im['nomorinvoice']);
			$totivs=$this->model_import_komisisales->sumIvscompare($get['nomorinvoice']);
			
			if($get['pengiriman']==1){
				if(strtoupper($get['kodecustomer'])=='20-10-C-0055' OR strtoupper($get['kodecustomer'])=='20-11-C-0065' OR strtoupper($im['kodecustomer'])=='20-10-C-0004' OR strtoupper($get['kodecustomer'])=='20-10-C-0224' OR strtoupper($get['kodecustomer'])=='20-11-C-0035'){
				  $biayatransport=0;
				}else{
				  if($get['gudang_id']==1){
					$biayatransport=350000;
				  }else{
					$biayatransport=250000;
				  }
				}
				
			  }else{
				$biayatransport=0;
			  }
			$this->data['penjualans'][]=array(
				'product_id'=>0,
				'tglinvoice'=>$get['tglinvoice'],
				'tgllunas'=>$get['tgllunas'],
				'tglso'=>0,
				'namasales'=>0,
				'kodecustomer'=>$get['kodecustomer'],
				'namacustomer'=>$get['namacustomer'],
				'namabarang'=>0,
				'qty'=>0,
				'hargasatuan'=>$this->currency->format(0),
				'totalhargaterendah'=>$this->currency->format($totalhargaterendah),
				'biayatransport'=>$this->currency->format(0),
				'poin'=>0,
				'nomorinvoice'=>$get['nomorinvoice'],
				'metodepembayaran'=>0,
				'status'=>0,
				'ivs'=>$this->currency->format(0),
				'kodebarang'=>0,
				'total'=>$totivs,
				'bkirim'=>$biayatransport,
				'products'=>$res,
				'customerbaru'=>$get['customerbaru'],
			);	
		}
		$is=0;
		$is=$this->model_import_komisisales->sumcompare($filter);
		$this->data['is']=$this->currency->format($is);
		foreach($importAll as $im){
			$res=$this->model_import_komisisales->GetImportdetailsCompare($im['nomorinvoice']);
			$totivs=$this->model_import_komisisales->sumIvscompare($im['nomorinvoice']);
			if($im['pengiriman']==1){
				if(strtoupper($im['kodecustomer'])=='20-10-C-0055' OR strtoupper($im['kodecustomer'])=='20-11-C-0065' OR strtoupper($im['kodecustomer'])=='20-10-C-0004' OR strtoupper($im['kodecustomer'])=='20-10-C-0224' OR strtoupper($im['kodecustomer'])=='20-11-C-0035'){
				  $biayatransport=0;
				}else{
				  if($im['gudang_id']==1){
					$biayatransport=350000;
				  }else{
					$biayatransport=250000;
				  }
				}
				
			  }else{
				$biayatransport=0;
			  }
			$this->data['penjualansAll'][]=array(
				'product_id'=>0,
				'tglinvoice'=>$im['tglinvoice'],
				'tgllunas'=>$im['tgllunas'],
				'tglso'=>0,
				'namasales'=>0,
				'namacustomer'=>$im['namacustomer'],
				'namabarang'=>0,
				'qty'=>0,
				'hargasatuan'=>$this->currency->format(0),
				'totalhargaterendah'=>$this->currency->format($totalhargaterendah),
				'biayatransport'=>$this->currency->format(0),
				'poin'=>0,
				'nomorinvoice'=>$im['nomorinvoice'],
				'metodepembayaran'=>0,
				'status'=>0,
				'ivs'=>$this->currency->format(0),
				'kodebarang'=>0,
				'total'=>$totivs,
				'bkirim'=>$biayatransport,
				'products'=>$res,
			);	
		}
		
		//echo "<pre>";print_r($this->data['penjualans']);exit;
		$this->load->model('localisation/country');
		$this->data['countries'] = $this->model_localisation_country->getCountries();

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} elseif (isset($this->session->data['error_warning'])) {
			$this->data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
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
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}


		$this->data['sort_date_added'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.date_added' . $url, 'SSL');
		$this->data['sort_register'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=customer.date_added' . $url, 'SSL');
		$this->data['sort_tgl_lunas'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.tgllunas' . $url, 'SSL');
		$this->data['sort_customer'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=customer.name' . $url, 'SSL');
		$this->data['sort_tagihan'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.totaltagihan' . $url, 'SSL');
		$this->data['sort_bayar'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.totalbayar' . $url, 'SSL');
		$this->data['sort_invoice'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.id' . $url, 'SSL');
		$this->data['sort_metode_pembayaran'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.metode_pembayaran' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.status' . $url, 'SSL');
		$this->data['sort_nama'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&nama=asc' . $url, 'SSL');
		
		$this->data['uploadex'] = $this->url->link('laporan/importkomisisales/import_compare', 'token=' . $this->session->data['token'] . '&nama=asc' . $url, 'SSL');
		$url = '';
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		$all=$this->model_import_komisisales->CountGetImportGroupcompare($filter);
		$pagination = new Pagination();
		$pagination->total = count($all);
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/importkomisisales/compare', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_date_start']	= $filter_date_start;
		$this->data['filter_date_end']	= $filter_date_end;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_gudang_id']	= $filter_gudang_id;
		$this->data['token'] = $this->session->data['token'];
		$this->data['exporttoexcel'] = $this->url->link('laporan/komisisales/compare', '&print=1&token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['filter_provinsi'] = $filter_provinsi;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		if(!isset($this->request->get['print'])){
			$this->template = 'laporan/importkomisisales_compare.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
		}else{
			$this->template = 'laporan/komisisales_cetak.tpl';
		}
		$this->response->setOutput($this->render());
	}

	public function import_compare(){
		$fileExt = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
		$d=array();
		$a=1;
		$iu=array();
		$pengiriman=1;
		$sales_id=0;
		if(in_array($fileExt, ['xls', 'xlsx'])){
	  
			  $targetPath = DIR_SYSTEM.'uploads/'.$_FILES['file']['name'];
			  move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
			  
			  $Reader = new SpreadsheetReader($targetPath);
			  
			  $this->load->model('catalog/gudang');
			  $gudangs = $this->model_catalog_gudang->getGudangs();
			  $gudang_map = array();
			  foreach ($gudangs as $g) {
				  $gudang_map[strtolower(trim($g['nama']))] = $g['gudang_id'];
			  }

			  $sheetCount = count($Reader->sheets());
			  for($i=0;$i<$sheetCount;$i++)
			  {
				  
				  if (!$Reader->ChangeSheet($i)) {
					  continue;
				  }
				  
				  foreach ($Reader as $Row)
				  {
					  if($a>1){
						
						$g_name = strtolower(trim($Row[15]));
						if ($g_name == "kantor pusat - tangerang") $g_name = "tangerang";
						else if ($g_name == "kantor cabang - surabaya") $g_name = "surabaya";
						$gudang_id = isset($gudang_map[$g_name]) ? $gudang_map[$g_name] : 0;
						
						if(!empty($Row[16])){
							if(strtolower($Row[16])=="diantar"){
								$pengiriman=1; // diantar
							}
							if(strtolower($Row[16])=="diambil"){
								$pengiriman=2; // diambil
							}	
						}

						if(empty($Row[4])){
							$di[]=array(
								'nomorinvoice'=>$Row[1],
								'total'=>$Row[10],
							);
						}else{
							if(!empty($Row[3])){
								if(strtolower($Row[3])=='yuli susanti'){
									$sales_id=1;
								}else if(strtolower($Row[3])=='andre yohannes - jkt'){
									$sales_id=2;
								}else if(strtolower($Row[3])=='wahyudi pratama'){
									$sales_id=3;
								}else if(strtolower($Row[3])=='anita wahyuni'){
									$sales_id=4;
								}else if(strtolower($Row[3])=='rifandita saputra'){
									$sales_id=5;
								}else if(strtolower($Row[3])=='erika susanti'){
									$sales_id=6;
								}else if(strtolower($Row[3])=='andre yohannes'){
									$sales_id=7;
								}else if(strtolower($Row[3])=='yuli susanti - sby'){
									$sales_id=8;
								}else if(strtolower($Row[3])=='tasrif arifin'){
									$sales_id=9;
								}else if(strtolower($Row[3])=='agus heri dewanna'){
									$sales_id=11;
								}else if(strtolower($Row[3])=='nico swardana'){
									$sales_id=12;
								}else if(strtolower($Row[3])=='sonny'){
									$sales_id=13;
								}else if(strtolower($Row[3])=='irfani arista'){
									$sales_id=14;
								}else if(strtolower($Row[3])=='Tjahjadi Yadi'){
									$sales_id=15;
								}else if(strtolower($Row[3])=='sonny - sby'){
									$sales_id=16;
								}else if(strtolower($Row[3])=='Cristy Nararya Pradipta'){
									$sales_id=17;
								}								
								else{
									$sales_id=0;
								}
							}

							if($Row[12]=='Lunas'){
								$statusbayar=1;
							}else{
								$statusbayar=2;
							}
						  $d=array(
							  'tglinvoice' =>date('Y-m-d',strtotime($Row[0])),
							  'tgllunas' =>date('Y-m-d',strtotime($Row[13])),
							  'tglso'=>date('Y-m-d',strtotime($Row[2])),
							  'namasales'=>$Row[3],
							  'namacustomer'=>$this->db->escape($Row[4]),
							  'kodebarang'=>$Row[5],
							  'namabarang'=>$Row[6],
							  'qty'=>$Row[7],
							  'poin'=>$Row[8],
							  'hargasatuan'=>$Row[9],
							  'nomorinvoice'=>$Row[1],
							  'metodepembayaran'=>$Row[11],
							  'status'=>$statusbayar,
							  'catatan'=>$Row[14],
							  'cabang'=>$Row[15],
							  'gudang_id'=>$gudang_id,
							  'pengiriman'=>$pengiriman,
							  'sales_id'=>$sales_id,
							  'kodecustomer'=>$Row[17],
						  );
						  $this->db->insert('inv_komisi_sales_compare',$d);
						}
						/*$this->db->update('harga_terendah',array('poin'=>$Row[5]),array('product_id'=>$Row[0]));*/
					  }
					  $a++;
				   }
			   }
			   //echo "<pre>";print_r(($d));exit;
			   $this->session->data['success'] = 'Data compare berhasil di import';
		}
		else
		{ 
			  $this->session->data['error_warning'] = "Invalid File Type. Upload Excel File dengan format xls/xlsx.";
		}

		if (isset($targetPath) && file_exists($targetPath)) {
			unlink($targetPath);
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
		}

			if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

				if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->redirect($this->url->link('laporan/importkomisisales/compare', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	// baru 30 September 2019	
	public function exporttoexcel(){
		//echo "<pre>";print_r($this->cetakexcel());exit;
		$this->xlscreation_directtanggal();
	}
	
	function xlscreation_directtanggal() {

		$reportdetails = $this->cetakexcel();

		$objPHPExcel = new PHPExcel(); 
		$objPHPExcel->getProperties()
				->setCreator("IT Division")
				->setLastModifiedBy("IT Division")
				->setTitle("PT.Nisson Indonesia ")
				->setSubject("PT.Nisson Indonesia")
				->setDescription("Export Item to Excel")
				->setKeywords("IT Division")
				->setCategory("IT Division");

		// Set the active Excel worksheet to sheet 0
		$objPHPExcel->setActiveSheetIndex(0); 

		// Initialise the Excel row number
		$rowCount = 0;

		$cell_definition = array(
			'A' => 'Tanggal',
			'B' => 'Tanggal Lunas',
			'C' => 'Nama Sales',
			'D' => 'Nama Customer',
			'E' => 'Jumlah',
			'F' => 'Total Bayar',
			'G' => 'No.Invoice',
			'H' => 'Metode Pembayaran',
			'I' => 'Status'
		);

		// Build headers
		foreach( $cell_definition as $column => $value )
		{
			$objPHPExcel->getActiveSheet()->getColumnDimension("{$column}")->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->setCellValue( "{$column}1", $value ); 
		}

		// Build cells
		while( $rowCount < count($reportdetails) ){ 
			$cell = $rowCount + 2;
			foreach( $cell_definition as $column => $value ) {

				$objPHPExcel->getActiveSheet()->getRowDimension($rowCount + 2)->setRowHeight(35); 
				
				switch ($value) {
					case 'Gambar':
						if (file_exists($reportdetails[$rowCount][$value])) {
							$objDrawing = new PHPExcel_Worksheet_Drawing();
							$objDrawing->setName('Customer Signature');
							$objDrawing->setDescription('Customer Signature');
							//Path to signature .jpg file
							$signature = $reportdetails[$rowCount][$value];    
							$objDrawing->setPath($signature);
							$objDrawing->setOffsetX(5);                     //setOffsetX works properly
							$objDrawing->setOffsetY(5);                     //setOffsetY works properly
							$objDrawing->setCoordinates($column.$cell);             //set image to cell 
							$objDrawing->setWidth(40);  
							$objDrawing->setHeight(40);                     //signature height  
							$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 
						} else {
							$objPHPExcel->getActiveSheet()->setCellValue($column.$cell,"No Image"); 
						}
						break;
					

					default:
						$objPHPExcel->getActiveSheet()->setCellValue($column.$cell, $reportdetails[$rowCount][$value] ); 
						break;
				}

			}
				
			$rowCount++; 
		} 

		$rand = rand(1234, 9898);
		$presentDate = date('d-m-Y H:i:s');
		$fileName = "Laporan_Penjualan_Detail_".$presentDate.".xls";

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		die();
	}
	
	public function cetakexcel() {
		$this->load->model('sale/invoice');
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-01');
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-t');
		}
		if (isset($this->request->get['filter_register_start'])) {
			$filter_register_start = $this->request->get['filter_register_start'];
		} else {
			$filter_register_start = "";
		}
		if (isset($this->request->get['filter_register_end'])) {
			$filter_register_end = $this->request->get['filter_register_end'];
		} else {
			$filter_register_end = "";
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'invoice.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/invoice/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/invoice/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

		$this->data['penjualans'] = array();
		$this->load->model('catalog/gudang');
		$this->load->model('gudang/product');
		$this->data['gudangs'] = $this->model_catalog_gudang->getGudangs(true);
		$gudangs=array();
		if(empty($filter_gudang_id)){
			foreach($this->data['gudangs'] as $g){
				$gudangs[]=$g['gudang_id'];
			}
		}else{
			$gudangs[]=$filter_gudang_id;
		}

		$arrsql=implode(',',$gudangs);



		$columnjumlah=array("COALESCE(SUM(totaltagihan),0) as total,COALESCE(SUM(pajak),0) as totalpajak");
		$columntotal=array("COUNT(*) as total");

		//echo $this->request->get['filter_status'];
		$column=array('invoice.*','customer.name','customer.email','customer.telephone','customer.title','customer.date_added as register');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'invoice.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);

		$data = array(
			'invoice.id'	=> empty($filter_order_id)?array('>',0):$filter_order_id,
			'invoice.gudang_id'	=>array('IN',$arrsql),
			'invoice.customer_id'	=> empty($filter_customer_id)?array('>',0):$filter_customer_id,
			'invoice.status'	=> empty($filter_status)?array('<>',4):array('IN',$filter_status),
			'invoice.jenisinvoice'	=> 3,
			//'invoice.date_added'	=> empty($filter_date_start)?array('>','1901-01-01'):array('>=',$filter_date_start),
			//'invoice.date_added'	=> empty($filter_date_added)?array('<',date('Y-m-d',strtotime("+1day"))):array('<=',$filter_date_end)
		);

		if(!empty($filter_date_start) & !empty($filter_date_end)){

			$data['DATE(invoice.date_added)']=array('>=',$filter_date_start,'<=',$filter_date_end);

		}
		else if(empty($filter_date_start) & !empty($filter_date_end)){
			$data['DATE(invoice.date_added)']= array('<=',$filter_date_end);
		}
		else if(!empty($filter_date_start) & empty($filter_date_end)){
			$data['DATE(invoice.date_added)']= array('>=',$filter_date_start);
		}else{
			$data['DATE(invoice.date_added)']= array('>','1901-01-01');
		}

		if(!empty($filter_register_start) & !empty($filter_register_end)){

			$data['DATE(customer.date_added)']=array('>=',$filter_register_start,'<=',$filter_register_end);

		}
		else if(empty($filter_register_start) & !empty($filter_register_end)){
			$data['DATE(customer.date_added)']= array('<=',$filter_register_end);
		}
		else if(!empty($filter_register_start) & empty($filter_register_end)){
			$data['DATE(customer.date_added)']= array('>=',$filter_register_start);
		}else{
			$data['DATE(customer.date_added)']= array('>','1901-01-01');
		}
		if(!isset($this->request->get['print'])){
			$offset=($page - 1) * $this->config->get('config_admin_limit');
			$limit=$this->config->get('config_admin_limit');
		}else{
			$offset=null;
			$limit=0;
		}

		$orders=array();
		if($sort == 'customer.name'){
			$orders=array($sort=>$order);
		}else{
			$orders=array($sort=>$order,'customer.name'=>'ASC','invoice.id'=>'ASC');
		}
		$jumlah=$this->model_sale_invoice->getPenjualanDetail($columnjumlah,$join,$data);
		$this->data['jumlah']=$this->currency->format($jumlah['total']);
		$this->data['jumlahtanpapajak']=$this->currency->format($jumlah['total']-$jumlah['totalpajak']);

		$total=$this->model_sale_invoice->getPenjualanDetail($columntotal,$join,$data);

		$results = $this->model_sale_invoice->getPenjualans($column,$join,$data,$orders,0,null);
		// baru 10 agustus 2019
		$this->load->model('user/user');
		$d = $this->model_sale_invoice->getnoso(6427);
		$sales=$this->model_user_user->getUser($d['sales']);
		// end baru 10 agustus 2019
			if($this->user->getUsername()=="pawiast"){
				echo "<pre>";
				print_r($results);
				exit;
			}
		$product_total=$total['total'];
		$this->data['total']=$product_total;
		$this->load->model('catalog/title');
		foreach ($results as $result) {
			$d = $this->model_sale_invoice->getnoso($result['id']);
			$sales=$this->model_user_user->getUser($d['sales']);
			$action = array();

			$action[] = array(
				'text' => 'Tampil',
				'href' => $this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] . '&view=1&order_id=' . $result['id'], 'SSL')
			);

			$namagudang=$this->model_catalog_gudang->getGudang($result['gudang_id']);
			$metode = $result['metode_pembayaran'];
				if($metode==1){
					$metode="Tunai";
				}else if($metode==2){
					$metode= "COD";
				}else if($metode==3){
					$metode= "Kredit";
				}else{
					$metode= "CBD";
				}
						
				$status = $result['status'];
				if($status== 1){
                    $status = 'Ditagih';
                }else if($status == 2){
                    $status = 'Belum Lunas';
				}else if($status == 3){
                    $status = 'Lunas';
                }else{
                    $status = 'Dibatalkan';
                }

			$this->data['penjualans'][] = array(
				'id' => $result['id'],
				'No.Invoice'        => $result['no_faktur'],
				//'no_so'	=> $so,
				//'no_sj'	=> $sj,
				//'sales_order_id'        => $result['sales_order_id'],
				'Nama Customer'	=> $this->model_catalog_title->getTitle($result['title']).' '.$result['name'],
				'Metode Pembayaran'	=> $metode,
				'namagudang'	=> $namagudang['nama'],
				'email'	=> $result['email'],
				'telephone'	=> $result['telephone'],
				'jenisinvoice'	=> $result['jenisinvoice'],
				'Jumlah'	=> $this->currency->format($result['totaltagihan']),
				'totaltagihan'	=> $this->currency->format($result['total']),
				'Total Bayar'	=> $this->currency->format($result['totalbayar']),
				'sub_total'	=> $this->currency->format($result['sub_total']),
				'diskon'	=> $this->currency->format($result['diskon']),
				'dasar'	=> $this->currency->format($result['sub_total']-$result['diskon']),
				'dp'	=> $this->currency->format($result['dp']),
				'pajak'	=> $this->currency->format($result['pajak']),
				'Status'	=> $status,
				'Tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
				'register'	=>date('d/m/y',strtotime($result['register'])),
				'Tanggal Lunas'	=>empty($result['tgllunas'])?'Belum Lunas':date('d/m/y',strtotime($result['tgllunas'])),
				'jatuhtempo'	=>date('d/m/y',strtotime($result['jatuhtempo'])),
				'Nama Sales' => $sales['firstname'],
				'products'	=> $this->model_sale_invoice->getPenjualanProducts($result['jenispenjualan'],array('sales_order_id'	=> $result['id']))
			);
		}
		
		return $this->data['penjualans'];

	}
	
	// end baru

	public function index() {
		$this->load->language('catalog/category');
		$this->load->model('gudang/product');
		$this->document->setTitle('Laporan Penjualan Detail');
		
		$this->load->model('sale/invoice');

		$this->getList();
	}

	public function excel() {
		$this->load->model('gudang/product');
		if (isset($this->request->get['filter_provinsi'])) {
			$filter_provinsi = $this->request->get['filter_provinsi'];
		} else {
			$filter_provinsi = null;
		}
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date('Y-m-d',strtotime('First day of last month'));
			$filter_date_start =null;
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			//$filter_date_end = date('Y-m-d',strtotime('Last day of last month'));
			$filter_date_end =null;
		}
		if (isset($this->request->get['filter_register_start'])) {
			$filter_register_start = $this->request->get['filter_register_start'];
		} else {
			$filter_register_start = "";
		}
		if (isset($this->request->get['filter_sales'])) {
			$filter_sales = $this->request->get['filter_sales'];
			$sales=$this->db->query("SELECT * FROM namasales WHERE hapus=0 and id='".$filter_sales."' ")->row;
			$this->data['namasales']=str_replace("&amp;","&",$sales['namasales']);
		} else {
			$filter_sales =null;
			$this->data['namasales']=null;
		}
		//echo "<pre>";print_r($this->data['namasales']);exit;
		if (isset($this->request->get['filter_register_end'])) {
			$filter_register_end = $this->request->get['filter_register_end'];
		} else {
			$filter_register_end = "";
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'invoice.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/invoice/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/invoice/delete', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['cetak'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'].'&print=1'.$url, 'SSL');
		$this->data['importhargaterendahnew'] = $this->url->link('laporan/importkomisisales/importhargaterendahnew', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['excel'] = $this->url->link('laporan/importkomisisales/excel', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['penjualans'] = array();
		$this->load->model('catalog/gudang');
		$this->data['total']=0;
		$this->load->model('catalog/title');
		$this->load->model('sale/penjualan');
		$this->load->model('setting/setting');
		$this->load->model('import/komisisales');
		$filter=array(
			'tanggal'=>$filter_date_start,
			'tanggal2'=>$filter_date_end,
			'filter_sales'=>$filter_sales,
			'filter_status'=>$filter_status,
			'filter_gudang_id'=>$filter_gudang_id,
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		
		$i=0;
		$this->data['hargaterendah']=0;
		$results=array();
		//$results=$this->model_import_komisisales->GetImport(array());
		$import=$this->model_import_komisisales->GetImportGroup($filter);
		$this->data['saless']=$this->model_import_komisisales->GetImportGroupSales();
		//echo "<pre>";print_r($this->data['sales']);exit;
		$nilaiivs=0;
		$tot=0;
		$sat=array();
		$nomorinvoice=array();
		$totivs=0;
		$harga_terendah=0;
		$poin=0;
		$biayatransport=0;
		$products=array();
		$totalhargaterendah=0;
		$biayatransport=0;
		//echo "<pre>";print_r($import);exit;
		
		foreach($import as $im){
			$get=$this->model_import_komisisales->Get($im['nomorinvoice']);
			$res=$this->model_import_komisisales->GetImportdetails($im['nomorinvoice']);
			$totivs=$this->model_import_komisisales->sumIvs($im['nomorinvoice']);
			
			if($get['pengiriman']==1){
				if(strtoupper($get['kodecustomer'])=='20-10-C-0055' OR strtoupper($get['kodecustomer'])=='20-11-C-0065' OR strtoupper($im['kodecustomer'])=='20-10-C-0004' OR strtoupper($get['kodecustomer'])=='20-10-C-0224' OR strtoupper($get['kodecustomer'])=='20-11-C-0035' OR strtoupper($get['kodecustomer'])=='00010710-00004' OR strtoupper($get['kodecustomer'])=='00010707-00002' OR strtoupper($get['kodecustomer'])=='00010707-00003' OR strtoupper($get['kodecustomer'])=='00010710-00005'){
				  $biayatransport=0;
				}else{
				  if($get['gudang_id']==1){
					$biayatransport=350000;
				  }else{
					$biayatransport=250000;
				  }
				}
			}else if($get['pengiriman']==3){
				$biayatransport=50000; // diantar kurir nisson
			}else{
				$biayatransport=0;
			}


			$this->data['penjualans'][]=array(
				'product_id'=>0,
				'tglinvoice'=>$get['tglinvoice'],
				'tgllunas'=>$get['tgllunas'],
				'tglso'=>0,
				'namasales'=>0,
				'kodecustomer'=>$get['kodecustomer'],
				'namacustomer'=>$get['namacustomer'],
				'namabarang'=>0,
				'qty'=>0,
				'hargasatuan'=>$this->currency->format(0),
				'totalhargaterendah'=>$this->currency->format($totalhargaterendah),
				'biayatransport'=>$this->currency->format(0),
				'poin'=>0,
				'nomorinvoice'=>$get['nomorinvoice'],
				'metodepembayaran'=>0,
				'status'=>0,
				'ivs'=>$this->currency->format(0),
				'kodebarang'=>0,
				'total'=>$totivs,
				'bkirim'=>$biayatransport,
				'products'=>$res,
				'customerbaru'=>$get['customerbaru'],
				'kota'=>$get['kota'],
				'provinsi'=>$get['provinsi'],
			);	
		}
	
		//echo "<pre>";print_r($this->data['penjualans']);exit;
		$this->load->model('localisation/country');
		$this->data['countries'] = $this->model_localisation_country->getCountries();

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} elseif (isset($this->session->data['error_warning'])) {
			$this->data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
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
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}


		$this->data['sort_date_added'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.date_added' . $url, 'SSL');
		$this->data['sort_register'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=customer.date_added' . $url, 'SSL');
		$this->data['sort_tgl_lunas'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.tgllunas' . $url, 'SSL');
		$this->data['sort_customer'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=customer.name' . $url, 'SSL');
		$this->data['sort_tagihan'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.totaltagihan' . $url, 'SSL');
		$this->data['sort_bayar'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.totalbayar' . $url, 'SSL');
		$this->data['sort_invoice'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.id' . $url, 'SSL');
		$this->data['sort_metode_pembayaran'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.metode_pembayaran' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.status' . $url, 'SSL');
		$this->data['sort_nama'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&nama=asc' . $url, 'SSL');
		
		$this->data['uploadex'] = $this->url->link('laporan/importkomisisales/import', 'token=' . $this->session->data['token'] . '&nama=asc' . $url, 'SSL');
		$url = '';
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		$all=$this->model_import_komisisales->CountGetImportGroup($filter);
		$pagination = new Pagination();
		$pagination->total = count($all);
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/importkomisisales', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_date_start']	= $filter_date_start;
		$this->data['filter_date_end']	= $filter_date_end;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_gudang_id']	= $filter_gudang_id;
		$this->data['token'] = $this->session->data['token'];
		$this->data['exporttoexcel'] = $this->url->link('laporan/komisisales', '&print=1&token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['filter_provinsi'] = $filter_provinsi;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'laporan/importkomisisales_cetak.tpl';


		$this->response->setOutput($this->render());
	}




	private function getList() {
		//echo "string";die();
		$this->load->model('gudang/product');
		if (isset($this->request->get['filter_provinsi'])) {
			$filter_provinsi = $this->request->get['filter_provinsi'];
		} else {
			$filter_provinsi = null;
		}
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$filter_gudang_id = $this->request->get['filter_gudang_id'];
		} else {
			$filter_gudang_id = null;
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = null;
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d',strtotime('-1 days'));
			//$filter_date_start =null;
		}
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
			//$filter_date_end =null;
		}
		if (isset($this->request->get['filter_register_start'])) {
			$filter_register_start = $this->request->get['filter_register_start'];
		} else {
			$filter_register_start = "";
		}
		if (isset($this->request->get['filter_sales'])) {
			$filter_sales = $this->request->get['filter_sales'];
		} else {
			$filter_sales =null;
		}
		if (isset($this->request->get['filter_register_end'])) {
			$filter_register_end = $this->request->get['filter_register_end'];
		} else {
			$filter_register_end = "";
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'invoice.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->data['url'] = $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$this->data['urllokasi'] = $this->url->link('pamerantoko/toko/autocomplete', 'token=' . $this->session->data['token'] , 'SSL');

		$this->data['insert'] = $this->url->link('sale/invoice/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/invoice/delete', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['cetak'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'].'&print=1'.$url, 'SSL');
		$this->data['importhargaterendahnew'] = $this->url->link('laporan/importkomisisales/importhargaterendahnew', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['excel'] = $this->url->link('laporan/importkomisisales/excel', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['importlunas'] = $this->url->link('laporan/importkomisisales/import_lunas', 'token=' . $this->session->data['token'].$url, 'SSL');
		$this->data['hapusinv'] = $this->url->link('laporan/importkomisisales/inv', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['penjualans'] = array();
		$this->load->model('catalog/gudang');
		$this->data['total']=0;
		$this->load->model('catalog/title');
		$this->load->model('sale/penjualan');
		$this->load->model('setting/setting');
		$this->load->model('import/komisisales');
		$filter=array(
			'tanggal'=>$filter_date_start,
			'tanggal2'=>$filter_date_end,
			'filter_sales'=>$filter_sales,
			'filter_status'=>$filter_status,
			'filter_gudang_id'=>$filter_gudang_id,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);
		$filterall=array(
			'tanggal'=>$filter_date_start,
			'tanggal2'=>$filter_date_end,
			'filter_sales'=>$filter_sales,
			'filter_status'=>$filter_status,
			'filter_gudang_id'=>$filter_gudang_id,
			//'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'           => $this->config->get('config_admin_limit')
		);
		$i=0;
		$this->data['hargaterendah']=0;
		$results=array();
		//$results=$this->model_import_komisisales->GetImport(array());
		$import=$this->model_import_komisisales->GetImportGroup($filter);
		$this->data['saless']=$this->db->query("SELECT * FROM namasales WHERE hapus=0 ORDER BY namasales ASC ")->rows;
		
		foreach($import as $im){
			$res=$this->model_import_komisisales->GetImportdetails($im['nomorinvoice']);
			$totivs=$im['total_ivs'];
			
			$biayatransport = 0;
			if($im['pengiriman']==1){
				$bypass_customers = array('20-10-C-0055', '20-11-C-0065', '20-10-C-0004', '20-10-C-0224', '20-11-C-0035', '00010710-00004', '00010707-00002', '00010707-00003', '00010710-00005');
				if(in_array(strtoupper($im['kodecustomer']), $bypass_customers)){
				  $biayatransport=0;
				}else{
				  $biayatransport = ($im['gudang_id']==1) ? 350000 : 250000;
				}
			}else if($im['pengiriman']==3){
				$biayatransport=50000; // diantar kurir nisson
			}

			$this->data['penjualans'][]=array(
				'product_id'=>0,
				'tglinvoice'=>$im['tglinvoice'],
				'tgllunas'=>$im['tgllunas'],
				'tglso'=>0,
				'namasales'=>0,
				'kodecustomer'=>$im['kodecustomer'],
				'namacustomer'=>$im['namacustomer'],
				'namabarang'=>0,
				'qty'=>0,
				'hargasatuan'=>$this->currency->format(0),
				'totalhargaterendah'=>$this->currency->format(0),
				'biayatransport'=>$this->currency->format(0),
				'poin'=>0,
				'nomorinvoice'=>$im['nomorinvoice'],
				'metodepembayaran'=>0,
				'status'=>0,
				'ivs'=>$this->currency->format(0),
				'kodebarang'=>0,
				'total'=>$totivs,
				'bkirim'=>$biayatransport,
				'products'=>$res,
				'customerbaru'=>$im['customerbaru'],
			);	
		}
		
		$is = $this->model_import_komisisales->sum($filterall);
		$this->data['is']=$this->currency->format($is);
		//echo "<pre>";print_r($this->data['penjualansAll']);exit;
		$this->load->model('localisation/country');
		$this->data['countries'] = $this->model_localisation_country->getCountries();

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} elseif (isset($this->session->data['error_warning'])) {
			$this->data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
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
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}


		$this->data['sort_date_added'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.date_added' . $url, 'SSL');
		$this->data['sort_register'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=customer.date_added' . $url, 'SSL');
		$this->data['sort_tgl_lunas'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.tgllunas' . $url, 'SSL');
		$this->data['sort_customer'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=customer.name' . $url, 'SSL');
		$this->data['sort_tagihan'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.totaltagihan' . $url, 'SSL');
		$this->data['sort_bayar'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.totalbayar' . $url, 'SSL');
		$this->data['sort_invoice'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.id' . $url, 'SSL');
		$this->data['sort_metode_pembayaran'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.metode_pembayaran' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&sort=invoice.status' . $url, 'SSL');
		$this->data['sort_nama'] = $this->url->link('laporan/komisisales', 'token=' . $this->session->data['token'] . '&nama=asc' . $url, 'SSL');
		
		$this->data['uploadex'] = $this->url->link('laporan/importkomisisales/import', 'token=' . $this->session->data['token'] . '&nama=asc' . $url, 'SSL');
		$url = '';
		if (isset($this->request->get['filter_provinsi'])) {
			$url .= '&filter_provinsi=' . $this->request->get['filter_provinsi'];
		}
		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . $this->request->get['filter_sales'];
		}
		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_register_start'])) {
			$url .= '&filter_register_start=' . $this->request->get['filter_register_start'];
		}
		if (isset($this->request->get['filter_register_end'])) {
			$url .= '&filter_register_end=' . $this->request->get['filter_register_end'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}
		if (isset($this->request->get['print'])) {
			$url .= '&print=' . $this->request->get['print'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		$all=$this->model_import_komisisales->CountGetImportGroup($filter);
		$pagination = new Pagination();
		$pagination->total = count($all);
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/importkomisisales', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();


		$this->data['filter_customer_id'] = $filter_customer_id;
		$this->data['filter_order_id']	= $filter_order_id;
		$this->data['filter_date_start']	= $filter_date_start;
		$this->data['filter_date_end']	= $filter_date_end;
		$this->data['filter_status']	= $filter_status;
		$this->data['filter_gudang_id']	= $filter_gudang_id;
		$this->data['filter_sales']	= $filter_sales;
		$this->data['token'] = $this->session->data['token'];
		$this->data['exporttoexcel'] = $this->url->link('laporan/komisisales', '&print=1&token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['filter_provinsi'] = $filter_provinsi;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		if(!isset($this->request->get['print'])){
			$this->template = 'laporan/importkomisisales.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
		}else{
			$this->template = 'laporan/komisisales_cetak.tpl';
		}



		$this->response->setOutput($this->render());
	}

	public function import(){
		$this->load->model('import/komisisales');
		$fileExt = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
		$d=array();
		$a=1;
		$iu=array();
		$pengiriman=1;
		$sales_id=0;
		if(in_array($fileExt, ['xls', 'xlsx'])){
	  
			  $targetPath = DIR_SYSTEM.'uploads/'.$_FILES['file']['name'];
			  move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
			  
			  $Reader = new SpreadsheetReader($targetPath);
			  
			  $formatDate = function($val) {
				  if (empty($val)) return null;
				  if (is_numeric($val)) return date('Y-m-d', ($val - 25569) * 86400);
				  return date('Y-m-d', strtotime($val));
			  };

			  $this->load->model('catalog/gudang');
			  $gudangs = $this->model_catalog_gudang->getGudangs();
			  $gudang_map = array();
			  foreach ($gudangs as $g) {
				  $gudang_map[strtolower(trim($g['nama']))] = $g['gudang_id'];
			  }

			  $sheetCount = count($Reader->sheets());
			  for($i=0;$i<$sheetCount;$i++)
			  {
				  
				  if (!$Reader->ChangeSheet($i)) {
					  continue;
				  }
				  
				  foreach ($Reader as $Row)
				  {
					  if($a>1){
						
						$g_name = strtolower(trim($Row[15]));
						$gudang_id = isset($gudang_map[$g_name]) ? $gudang_map[$g_name] : 0;
						
						if(!empty($Row[16])){
							if(strtolower($Row[16])=="diantar"){
								$pengiriman=1; // diantar
							}
							if(strtolower($Row[16])=="diambil"){
								$pengiriman=2; // diambil
							}
							if(strtolower($Row[16])=="diantar kurir/gojek"){
								$pengiriman=2; // diambil
							}
							if(strtolower($Row[16])=="jne"){
								$pengiriman=2; // diambil
							}
							if(strtolower($Row[16])=="diantar kurir nisson"){
								$pengiriman=3; // diantar kurir nisson 50000
							}

							if(strtolower($Row[16])=="diantar kurir"){
								$pengiriman=3; // diantar kurir 50000
							}
						}

						if(empty($Row[4])){
							$di[]=array(
								'nomorinvoice'=>$Row[1],
								'total'=>$Row[10],
							);
						}else{
							if(!empty($Row[3])){
								/*
								if(strtolower($Row[3])=='yuli susanti'){
									$sales_id=1;
								}else if(strtolower($Row[3])=='andre yohannes - jkt'){
									$sales_id=2;
								}else if(strtolower($Row[3])=='andre yohannes - tgr'){
									$sales_id=2;
								}else if(strtolower($Row[3])=='rifandita saputra'){
									$sales_id=5;
								}else if(strtolower($Row[3])=='erika susanti'){
									$sales_id=6;
								}else if(strtolower($Row[3])=='erika'){
									$sales_id=6;
								}else if(strtolower($Row[3])=='andre yohannes'){
									$sales_id=7;
								}else if(strtolower($Row[3])=='yuli susanti - sby'){
									$sales_id=8;
								}else if(strtolower($Row[3])=='tasrif arifin'){
									$sales_id=9;
								}else if(strtolower($Row[3])=='nico swardana'){
									$sales_id=12;
								}else if(strtolower($Row[3])=='nico'){
									$sales_id=12;
								}else if(strtolower($Row[3])=='sonny'){
									$sales_id=13;
								}else if(strtolower($Row[3])=='irfani arista'){
									$sales_id=14;
								}else if(strtolower($Row[3])=='irfani'){
									$sales_id=14;
								}else if(strtolower($Row[3])=='tjahjadi yadi'){
									$sales_id=15;
								}else if(strtolower($Row[3])=='tjahjadi'){
									$sales_id=15;
								}else if(strtolower($Row[3])=='sonny - sby'){
									$sales_id=16;
								}else if(strtolower($Row[3])=='sonny yohannes - sby'){
									$sales_id=16;
								}else if(strtolower($Row[3])=='tasrif jkt'){
									$sales_id=18;
								}else if(strtolower($Row[3])=='tasrif - tgr'){
									$sales_id=18;
								}else if(strtolower($Row[3])=='yadiandyulis'){
									$sales_id=19;
								}else if(strtolower($Row[3])=='yadi yulis'){
									$sales_id=19;
								}else if(strtolower($Row[3])=='yadi & yulis'){
									$sales_id=19;
								}else if(strtolower($Row[3])=='rifan & irfani'){
									$sales_id=20;
								}else if(strtolower($Row[3])=='rifandita saputra - sby'){
									$sales_id=21;
								}else if(strtolower($Row[3])=='haryo puthut'){
									$sales_id=23;
								}else if(strtolower($Row[3])=='haryo puthut '){
									$sales_id=23;
								}else if(strtolower($Row[3])=='haryo puthut wicaksono'){
									$sales_id=23;
								}else if(strtolower($Row[3])=='cash sales counter'){
									$sales_id=24;
								}else if(strtolower($Row[3])=='yadi & yulis - sby'){
									$sales_id=25;
								}else if(strtolower($Row[3])=='haryo puthut - tgr'){
									$sales_id=26;
								}else if(strtolower($Row[3])=='haryo - tgr'){
									$sales_id=26;
								}else if(strtolower($Row[3])=='marketplace - tgr'){
									$sales_id=27;
								}else if(strtolower($Row[3])=='hayu safaat - sby'){
									$sales_id=28;
								}else if(strtolower($Row[3])=='rifan & irfani - sby'){
									$sales_id=29;
								}else if(strtolower($Row[3])=='daniel robertus'){
									$sales_id=30;
								}else if(strtolower($Row[3])=='daniel & siti - tgr'){
									$sales_id=31;
								}else if(strtolower($Row[3])=='daniel & yulis - tgr'){
									$sales_id=32;
								}else if(strtolower($Row[3])=='haryo & fitri - tgr'){
									$sales_id=33;
								}else if(strtolower($Row[3])=='siti & rifan - tgr'){
									$sales_id=34;
								}else if(strtolower($Row[3])=='tasrif & fitri - tgr'){
									$sales_id=35;
								}else if(strtolower($Row[3])=='haryo & erika'){
									$sales_id=36;
								}else if(strtolower($Row[3])=='haryo & fitri'){
									$sales_id=37;
								}else if(strtolower($Row[3])=='hayu & erika'){
									$sales_id=38;
								}else if(strtolower($Row[3])=='hayu & fitri'){
									$sales_id=39;
								}else if(strtolower($Row[3])=='tasrif & erika'){
									$sales_id=40;
								}else if(strtolower($Row[3])=='tasrif & fitri'){
									$sales_id=41;
								}else if(strtolower($Row[3])=='hayu & fitri - tgr'){
									$sales_id=42;
								}else if(strtolower($Row[3])=='tasrif & erika - tgr'){
									$sales_id=43;
								}else if(strtolower($Row[3])=='yadi & fani - tgr'){
									$sales_id=44;
								}else{
									$sales_id=0;
								}
								*/
								$sales=$this->model_import_komisisales->getSales(strtolower(trim($Row[3])));
								if(!empty($sales)){
									$sales_id=$sales['id'];
								}else{
									$sales_id=0;
								}
							}

							if($Row[12]=='Lunas'){
								$statusbayar=1;
							}else{
								$statusbayar=2;
							}

						  $d=array(
							  'tglinvoice' => $formatDate($Row[0]),
							  'nomorinvoice'=>$Row[1],
							  'tglso'=>!empty($Row[2]) ? $formatDate($Row[2]) : $formatDate($Row[0]),
							  'namasales'=>$Row[3],
							  'namacustomer'=>$this->db->escape($Row[4]),
							  'kodebarang'=>$Row[5],
							  'namabarang'=>$Row[6],
							  'qty'=>$Row[7],
							  //'poin'=>$Row[8],
							  'poin'=>0,
							  'hargasatuan'=>$Row[9],
							  'metodepembayaran'=>$Row[11],
							  'status'=>$statusbayar, // index ke 12
							  'tgllunas' => $formatDate($Row[13]),
							  //'catatan'=>$Row[14],
							  'catatan'=>'-', // index ke 14
							  'cabang'=>$Row[15],
							  'pengiriman'=>$pengiriman, // index ke 16
							  'kodecustomer'=>$Row[17],
							  'kota'=>!empty($Row[18])?$Row[18]:null,
							  'provinsi'=>!empty($Row[19])?$Row[19]:null,
							  'gudang_id'=>$gudang_id,
							  'sales_id'=>$sales_id,
						  );
						  $this->db->insert('inv_komisi_sales',$d);
						}
						/*$this->db->update('harga_terendah',array('poin'=>$Row[5]),array('product_id'=>$Row[0]));*/
					  }
					  $a++;
				   }
			   }
			   $this->session->data['success'] = 'Data berhasil di import';
		}
		else
		{ 
			  $this->session->data['error_warning'] = "Invalid File Type. Upload Excel File dengan format xls/xlsx.";
		}
		if (isset($targetPath) && file_exists($targetPath)) {
			unlink($targetPath);
		}
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . urlencode(html_entity_decode($this->request->get['filter_date_start'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . urlencode(html_entity_decode($this->request->get['filter_date_end'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_sales'])) {
			$url .= '&filter_sales=' . urlencode(html_entity_decode($this->request->get['filter_sales'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
		}

			if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

				if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->redirect($this->url->link('laporan/importkomisisales', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function importhargaterendahnew(){
		$fileExt = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
		
		if(in_array($fileExt, ['xls', 'xlsx'])){
			  $targetPath = DIR_SYSTEM.'uploads/'.$_FILES['file']['name'];
			  move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
			  
			  $Reader = new SpreadsheetReader($targetPath);
			  
			  $this->load->model('catalog/gudang');
			  $gudangs = $this->model_catalog_gudang->getGudangs();
			  $gudang_map = array();
			  foreach ($gudangs as $g) {
				  $gudang_map[strtolower(trim($g['nama']))] = $g['gudang_id'];
			  }

			  $sheetCount = count($Reader->sheets());
			  for($i=0;$i<$sheetCount;$i++)
			  {
				  $Reader->ChangeSheet($i);
				  $a = 1;
				  foreach ($Reader as $Row)
				  {
					  if($a > 1 && !empty($Row[0])){
						  $g_name = strtolower(trim($Row[2]));
						  $gudang_id = isset($gudang_map[$g_name]) ? $gudang_map[$g_name] : 0;
						  
						  if($gudang_id > 0){
							  $d=array(
								  'kodebarang'=>$Row[0],
								  'nama'=>$Row[1],
								  'gudang'=>$gudang_id,
								  'harga_terendah'=>$Row[3],
								  'poin'=>$Row[4],
								  'tgl_berlaku' =>date('Y-m-d',strtotime($Row[5])),
								  'hapus'=>0
							  );
							  $this->db->insert('harga_terendah_new', $d);
						  }
					  }
					  $a++;
				   }
			   }
			   $this->session->data['success'] = 'Harga Terendah berhasil di import';
		}
		else
		{ 
			  $this->session->data['error_warning'] = "Invalid File Type. Upload Excel File dengan format xls/xlsx.";
		}
		if (isset($targetPath) && file_exists($targetPath)) {
			unlink($targetPath);
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . urlencode(html_entity_decode($this->request->get['filter_category_id'], ENT_QUOTES, 'UTF-8'));
		}

			if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

				if (isset($this->request->get['filter_urutkan'])) {
			$url .= '&filter_urutkan=' . $this->request->get['filter_urutkan'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->redirect($this->url->link('laporan/importkomisisales', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function autocomplete(){
		$rests = array();

		$this->load->model('sale/invoice');

			if (isset($this->request->get['q'])) {
				$filter_order_id = $this->request->get['q'];
			} else {
				$filter_order_id = '';
			}

			if (isset($this->request->get['p'])) {
				$p = $this->request->get['p'];
			} else {
				$p = null;
			}

			if (isset($this->request->get['customer_id'])) {
				$customer_id = $this->request->get['customer_id'];
			} else {
				$customer_id = null;
			}
			/*if (isset($this->request->get['p'])) {
				$p = $this->request->get['p'];
			} else {
				$p = '';
			}*/


			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 20;
			}

			$data = array(
				'no_faktur'         => array('LIKE',$filter_order_id),
				'metode_pembayaran'	=> $p != null?($p == 4?array('<>',3):$p):array('>=',1),
				'customer_id'	=> $customer_id != null ?$customer_id:array('>=',1),

			);
			$offset=0;
			$limit=10;

			$results = $this->model_sale_invoice->getPenjualans(array(),array(),$data,array(),10,0);
			foreach($results as $r){
				/*if($r['jenisinvoice'] == 2){
					$total=$this->currency->format($r['totaltagihan']);
				}*/
				$rests[]=array(
					'id'	=> $r['id'],
					'text'	=> $r['no_faktur'].' Total Tagihan '.$this->currency->format($r['totaltagihan'] - $r['totalbayar'])
				);
			}
		$this->response->setOutput(json_encode($rests));
	}

	public function detailinvoice(){
		$hasil = array();

		$this->load->model('sale/invoice');
		if(isset($this->request->get['id'])){
			if(!empty($this->request->get['id'])){
				$column=array();
				$id=$this->request->get['id'];
				$data = array(
					'id'      =>$id
				);

				$hasil=$this->model_sale_invoice->getPenjualan($data);
			//	$hasil['pdeposit']=$this->currency->format($hasil['deposit']);


			}
		}
		$this->response->setOutput(json_encode($hasil));


	}

	public function tampil(){
		$this->document->setTitle('Invoice');
		$url = '';

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . $this->request->get['filter_customer_id'];
		}
		if (isset($this->request->get['filter_gudang_id'])) {
			$url .= '&filter_gudang_id=' . $this->request->get['filter_gudang_id'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['filter_shipping_method'])) {
			$url .= '&filter_shipping_method=' . $this->request->get['filter_shipping_method'];
		}
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_jenisorder'])) {
			$url .= '&filter_jenisorder=' . $this->request->get['filter_jenisorder'];
		}
		if (isset($this->request->get['filter_statustabung'])) {
			$url .= '&filter_statustabung=' . $this->request->get['filter_statustabung'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if(isset($this->request->get['order_id'])){
			if(!empty($this->request->get['order_id'])){
				$order_id=$this->request->get['order_id'];
			}else{
				$this->redirect($this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}
		}else{
			$this->redirect($this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->load->model('sale/invoice');
		$this->load->model('sale/customer');
		$column=array('invoice.*','customer.name','customer.npwp','customer.title','customer.telephone','customer.email','customer.alamat','customer.alamat as address');
		$join=array();
		$join[]=array(
			'tablename'	=> 'customer',
			'firsttable'	=> 'invoice.customer_id',
			'secondtable'	=> 'customer.customer_id',
		);


		$data = array(
			'invoice.id'	=> $order_id,

		);
		$this->load->model('user/user');
		$trans=$this->model_sale_invoice->getPenjualanDetail($column,$join,$data,array());

		$products=$this->model_sale_invoice->getPenjualanProducts($trans['jenispenjualan'],array('sales_order_id'	=> $order_id));

		//referensi
		if($trans['jenisinvoice'] == 3){
			if($trans['jenispenjualan'] == 1){
				$this->load->model('sale/penjualan');
				$ref=$this->model_sale_penjualan->getPenjualan(array('id'=>$trans['referensi']));
				$trans['ref']=$ref['no_sj'];
			}
			if($trans['jenispenjualan'] == 2){
				$this->load->model('sale/penjualanmr');
				$ref=$this->model_sale_penjualanmr->getPenjualan(array('id'=>$trans['referensi']));
				$trans['ref']=$ref['no_sj'];
			}
			if($trans['jenispenjualan'] == 3){
				$this->load->model('sale/penjualanbahanbaku');
				$ref=$this->model_sale_penjualanbahanbaku->getPenjualan(array('id'=>$trans['referensi']));
				$trans['ref']=$ref['no_sj'];
			}
		}else{
			if($trans['jenispenjualan'] == 1){
				$this->load->model('sale/salesorder');
				$ref=$this->model_sale_salesorder->getPenjualan(array('id'=>$trans['referensi']));
				$trans['ref']=$ref['no_so'];
			}
			if($trans['jenispenjualan'] == 2){
				$this->load->model('sale/salesordermr');
				$ref=$this->model_sale_salesordermr->getPenjualan(array('id'=>$trans['referensi']));
				$trans['ref']=$ref['no_so'];
			}
			if($trans['jenispenjualan'] == 3){
				$this->load->model('sale/salesorderbahanbaku');
				$ref=$this->model_sale_salesorderbahanbaku->getPenjualan(array('id'=>$trans['referensi']));
				$trans['ref']=$ref['no_so'];
			}
		}

		$this->load->model('keuangan/bank');
		$this->data['banks']=$this->model_keuangan_bank->getBanks(array(),array(),array('display_order' => 1,'hapus'	=> array('<',1)),array(),0,null);
		//bank pembayaran

		$this->data['order']=$trans;
		$this->data['products']=$products;
		//$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);
		$comp=array(
			'compname' => $this->config->get('config_name'),
			'address'	=> $this->config->get('config_address'),
			'email'	=> $this->config->get('config_email'),
			'phone'	=> $this->config->get('config_telephone'),
			'fax'	=> $this->config->get('config_fax'),
			'web'	=> 'http://nissonindonesia.com'
		);

		$this->load->model('catalog/title');
		$trans['titlename']=$this->model_catalog_title->getTitle($trans['title']);
		$this->data['fulldetail']=array(
			'comp'	=> $comp,
			'order'	=> $trans,
			'products'	=> $products,
			//'address'	=> $this->data['address'],
			'banks'	=> $this->data['banks']
		);

		$this->data['printer']=$this->config->get('config_printer');
		$this->data['printerstatus']=$this->config->get('config_printer_status');

		//print_r($this->data['fulldetail']);

		//print_r($this->model_sale_customer->getAddress($trans['address_id']));
		$this->data['cancel']= $this->url->link('sale/invoice', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['suratjalan']= $this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=2'. $url, 'SSL');
		$this->data['invoice']= $this->url->link('sale/invoice/tampil', 'token=' . $this->session->data['token'] .'&order_id='.$order_id.'&view=3'. $url, 'SSL');
		if($this->request->get['view'] == 1){
			$this->template = 'sale/invoice_info.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

		}
		if($this->request->get['view'] == 2){
			$this->template = 'sale/suratjalan.tpl';
		}
		if($this->request->get['view'] == 3){
			$this->template = 'sale/invoice.tpl';
		}



		$this->response->setOutput($this->render());
	}



		public function detail(){
			$hasil = array();

			$this->load->model('pembelian/permintaanpembelian');
			if(isset($this->request->get['id'])){
				if(!empty($this->request->get['id'])){

				$this->load->model('sale/invoice');
				$this->load->model('sale/customer');
				$column=array('sales_order.*','customer.name','customer.telephone','customer.email');
				$join=array();
				$join[]=array(
					'tablename'	=> 'customer',
					'firsttable'	=> 'sales_order.customer_id',
					'secondtable'	=> 'customer.customer_id',
				);

				$data = array(
					'sales_order.id'	=> $this->request->get['id'],

				);
				$this->load->model('user/user');
				$this->load->model('catalog/gudang');
				$trans=$this->model_sale_invoice->getPenjualanDetail($column,$join,$data,array());

				$sales=$this->model_user_user->getUser($trans['sales']);
				$gudang=$this->model_catalog_gudang->getGudang($trans['gudang_id']);
				$trans['namasales']=$sales['firstname'];
				$trans['namagudang']=$gudang['nama'];
				$products=$this->model_sale_invoice->getPenjualanProducts(array('sales_order_id'	=> $this->request->get['id']));

				//$this->data['order']=$trans;
				//$this->data['products']=$products;
				$this->data['address']=$this->model_sale_customer->getAddress($trans['address_id']);

				$hasil=array(
					'order'	=> $trans,
					'products'	=> $products,
					'address'	=> $this->data['address']
				);
			}
		}
			$this->response->setOutput(json_encode($hasil));


		}
}
?>
