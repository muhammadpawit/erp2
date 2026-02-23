<?php
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->data['title'] = $this->document->getTitle();

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}

		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');

		$this->load->language('common/header');

		$this->data['heading_title'] = $this->language->get('heading_title');


		$this->load->model('website/menu');
		if (!$this->user->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$this->data['logged'] = '';

			$this->data['home'] = $this->url->link('common/login', '', 'SSL');
		} else {
			// baru 22 Juni 2020
			$this->load->model('website/menu');
			$in=null;
			$cekuser=$this->load->model_website_menu->cekuser($this->user->getId());
			if(empty($cekuser)){
				$in=null;
			}else{
				$in=$this->load->model_website_menu->getin($this->user->getId());
			}
			$resultsmenu=$this->load->model_website_menu->getmastermenu($in);
			foreach($resultsmenu as $menu){
				$getmenuforuser=$this->load->model_website_menu->getmenuforuser($this->user->getId(),$menu['sort_order'],$in);
				$submenu=$this->load->model_website_menu->getsub($menu['grouping']);
				$this->data['menu'][] = array(
					'menu'=>$menu['grouping'],
					'sub'=>$submenu,
					'rincian'=>$getmenuforuser,
				);
			}
			$this->data['in']=$this->load->model_website_menu->getin($this->user->getId());
			//echo "<pre>";print_r($this->data['menu']);exit;
			// end baru
			$this->data['logged'] = $this->user->getUserName();
			$this->data['logout'] = $this->url->link('common/logout', 'token=' . $this->session->data['token'], 'SSL');

			//master data
			$this->data['category'] = $this->url->link('catalog/category', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['options'] = $this->url->link('catalog/options', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['kelompok_aset'] = $this->url->link('catalog/kelompokaset', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['vendor_lokal'] = $this->url->link('catalog/vendorlokal', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['vendor_import'] = $this->url->link('catalog/vendorimport', 'token=' . $this->session->data['token'], 'SSL');



			//persediaan
			$this->data['product'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['bahanbaku'] = $this->url->link('catalog/bahanbaku', 'token=' . $this->session->data['token'], 'SSL');

			//customer
			$this->data['customer_group'] = $this->url->link('sale/customer_group', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['customer'] = $this->url->link('sale/customer', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['member'] = $this->url->link('sale/member', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['hadiah'] = $this->url->link('sale/hadiah', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['redeem_poin'] = $this->url->link('sale/redeem_poin', 'token=' . $this->session->data['token'], 'SSL');
			//notif

			//pembelian
			$this->data['pembelian_produk'] = $this->url->link('gudang/pembelian', 'token=' . $this->session->data['token'], 'SSL');
			$this->data['terima_pembelian'] = $this->url->link('gudang/barangdatang', 'token=' . $this->session->data['token'], 'SSL');
			/*
			total penjualan baru status pending
			total konfirmasi pembayaran belum dikonfirmasi

			*/

			/*Back Office*/


			/*$this->data['pembayarangudangs'] = array();
			$this->load->model('catalog/gudang');

			$gudangs=$this->model_catalog_gudang->getGudangs(true);
			foreach($gudangs as $g){
				if($g['web'] == 2){
					$this->data['pembayarangudangs'][] = array(
						'nama' => $g['nama'],
						'href' => $this->url->link('keuangan/pembayarannonweb','gudang_id='.$g['gudang_id'].'&token=' . $this->session->data['token'], 'SSL')
					);
				}
			}
			*/
			//$this->data['pembayarandoku'] = $this->url->link('keuangan/pembayarandoku', 'token=' . $this->session->data['token'], 'SSL');

			/*$this->load->model('setting/store');

			$results = $this->model_setting_store->getStores();

			foreach ($results as $result) {
				$this->data['stores'][] = array(
					'name' => $result['name'],
					'href' => $result['url']
				);
			}
		}
		*/
	}
		$this->template = 'common/header.tpl';

		$this->render();

	}
}
?>
