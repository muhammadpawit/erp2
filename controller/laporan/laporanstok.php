<?php
class ControllerLaporanLaporanstok extends Controller {
	public function index() {
		$this->language->load('report/product_purchased');

		$this->document->setTitle('Laporan Stok Global');


        if (isset($this->request->get['filter_product_id'])) {
			$product_id = $this->request->get['filter_product_id'];
		} else {
			$product_id = '';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}


		if (isset($this->request->get['filter_category_id'])) {
			$filter_category_id = $this->request->get['filter_category_id'];
		} else {
			$filter_category_id = '';
		}



		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('Laporan Stok Global'),
			'href'      => $this->url->link('report/laporanstok', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->load->model('report/laporanstok');
		//$this->load->model('gudang/product');
		$this->load->model('catalog/product');

		$this->load->model('catalog/gudang');
		$this->data['gudangs']=$this->model_catalog_gudang->getGudangs(true);

		$this->data['products'] = array();

		$data = array(
			'filter_name'			=> $filter_name,
			'filter_product_id'			=> $product_id,
			'filter_category_id'	=> $filter_category_id,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		//print_r($data);
			$gudangs=array();

			$totalqty=0;
			$totalnet=0;
			$totalprice=0;
			$totaldiskon=0;

			$totalgudang=0;
			$totalhnet=0;
			$totaljual=0;
			$totaljualdiskon=0;
			foreach($this->data['gudangs'] as $g){
				$g[] = $g['gudang_id'];
				$hasil=$this->model_report_laporanstok->getStokGudang($g['gudang_id']);
				$qty=$hasil['qty'] < 0?0:(empty($hasil['qty'])?'0':$hasil['qty']);
				$gudangs[]=array(
					'id'=>$g['gudang_id'],
					'nama'	=> $g['nama'],
					'qty'	=> $qty,
					'qtyprosestransfer'	=> $this->model_report_laporanstok->getStokProsesTransfer($g['gudang_id']),
					'net_cost'	=> $this->currency->format($hasil['net_cost'])

				);
				$totalgudang +=$qty;
				$totalhnet +=$hasil['net_cost'];


			}
			// gudang Tangerang
			$hasiltgr=$this->model_report_laporanstok->getStokGudang(1);
			$qtytgr=$hasil['qty'] < 0?0:(empty($hasiltgr['qty'])?'0':$hasiltgr['qty']);
			$this->data['qtytgr'] = $qtytgr;
			$this->data['prodtgr'] = $this->model_report_laporanstok->getproduk(1);
			$this->data['qtyprosestransfertgr'] = $this->model_report_laporanstok->getStokProsesTransfer(1);
			$this->data['totalnettgr'] =$this->currency->format($hasiltgr['net_cost']);
			// gudang Surabaya
			$hasilsby=$this->model_report_laporanstok->getStokGudang(3);
			$qtysby=$hasil['qty'] < 0?0:(empty($hasilsby['qty'])?'0':$hasilsby['qty']);
			$this->data['qtysby'] = $qtysby;
			$this->data['prodsby'] = $this->model_report_laporanstok->getproduk(3);	
			$this->data['totalnetsby'] =$this->currency->format($hasilsby['net_cost']);
			$this->data['qtyprosestransfersby'] = $this->model_report_laporanstok->getStokProsesTransfer(3);
			// total sby dan jkt
			$this->data['totalnetsbyjkt'] = $this->currency->format($hasiltgr['net_cost']+$hasilsby['net_cost']);
			// global
			$this->data['totalgudang'] = $totalgudang;
			

			$this->data['product']= array(

				'datagudang'	=> $gudangs,
				'totalgudang'	=> $totalgudang,
				'totalhnetgudang'	=> $this->currency->format($totalhnet),
				'totalqty'	=> $totalqty,
				'totalnet'	=> $this->currency->format($totalnet),
				'totalnetsbyjkt'=>$this->currency->format($hasiltgr['net_cost']+$hasilsby['net_cost']),
				'totalqtysbyjkt'=>$qtysby+$qtytgr,

			);


		$this->data['heading_title'] = $this->language->get('Laporan Persediaan');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = 'Option<br> (Size/Color)';
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_size'] = $this->language->get('column_size');
		$this->data['column_total'] = $this->language->get('column_total');

		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];



		$url = '';

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_category_id'])) {
			$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}



		$this->data['product_id'] = $product_id;
        $this->data['filter_category_id'] = $filter_category_id;

		$this->data['filter_name'] = $filter_name;



		$this->template = 'laporan/laporanstok.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
}
?>
