<?php
class ControllerLaporanPengeluarankas extends Controller {
	private $error=array();
	public function index() {
		$this->document->setTitle('Jurnal Pengeluaran Kas');
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
			$filter_date_start ="";
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			//$filter_date_end = date('Y-m-d');
			$filter_date_end ="";
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->model('keuangan/jurnal');

		$this->data['orders'] = array();

		$data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_keuangan_jurnal->totalPengeluaranKas($data);

		$results = $this->model_keuangan_jurnal->jurnalPengeluaranKas($data);

		$this->load->model('pembelian/pembeliantunai');
		$this->load->model('pembelian/pembayarandp');
		//print_r($results);
		foreach ($results as $result) {


			if($result['type'] == 1){
				//pembelian Tunai
				$pb=$this->model_pembelian_pembeliantunai->getPermintaanPembelian(array('no_faktur,sub_total,diskon,pajak,total_pembelian'),array(),array('hapus'=> array('<',1) ));
				//print_r($pb);
				$pembelian=$pb['sub_total'];
				$hutangdagang=0;
				$serbaserbi=array(
					'akun'	=>"",
					'ref'	=> "",
					'jumlah'	=>0
				);
				$dp=0;
				$ppn=$pb['pajak'];
				$kas=$result['saldokeluar'];
				$diskon=$pb['diskon'];
			}
			if($result['type'] == 2){
				//pembelian kredit
			}
			if($result['type'] == 3){
				//uang muka pembelian
				$pb=$this->model_pembelian_pembayarandp->getPermintaanPembelian(array('no_po','jumlah'),array(),array('status'=> array('<>',3) ));
				$pembelian=0;
				$hutangdagang=0;
				$serbaserbi=array(
					'akun'	=>"",
					'ref'	=> "",
					'jumlah'	=>0
				);
				$dp=$pb['jumlah'];
				$ppn=0;
				$kas=$result['saldokeluar'];
				$diskon=0;
				$result['ref']=$pb['no_po'];
			}
			$this->data['orders'][] = array(
				'tanggal'	=>date('d/m/y',strtotime($result['date_added'])),
				'no_faktur'     => $pb['no_faktur'],
				'ref'	=> $result['ref'],
				'type'	=> $result['type'],
				'keterangan'	=> $result['keterangan'],
				'pembelian'	=> $pembelian,
				'hutangdagang'=> $hutangdagang,
				'serbaserbi'	=> $serbaserbi,
				'dp'	=> $dp,
				'ppn'	=> $ppn,
				'kas'	=> $kas,
				'diskon'	=> $diskon

			);
		}

		$this->data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/pengeluarankas', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;

		$this->template = 'laporan/pengeluarankas.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}


}
?>
