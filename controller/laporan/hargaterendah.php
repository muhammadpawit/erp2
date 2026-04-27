<?php
class ControllerLaporanhargaterendah extends Controller {
	private $error = array();

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['q'])) {
			$filter_q = $this->request->get['q'];
		} else {
			$filter_q = '';
		}

		$sql = "SELECT DATE(tgl_berlaku) as tgl 
				FROM harga_terendah_new 
				WHERE hapus = 0 ";
		
		if ($filter_q) {
			$sql .= " AND tgl_berlaku::text LIKE '%" . $this->db->escape($filter_q) . "%' ";
		}
		
		$sql .= " GROUP BY tgl ORDER BY tgl DESC";

		$results = $this->db->query($sql)->rows;

		foreach ($results as $result) {
			$json[] = array(
				'id' => $result['tgl'],
				'text' => date('d F Y', strtotime($result['tgl'])),
			);
		}

		$this->response->setOutput(json_encode($json));
	}

	public function index() {
		$this->document->setTitle('Daftar Harga Terendah');

		if (isset($this->request->get['filter_tanggal'])) {
			$filter_tanggal = $this->request->get['filter_tanggal'];
		} else {
			// Default to the latest available date in the system
			$latest = $this->db->query("SELECT MAX(tgl_berlaku) as latest FROM harga_terendah_new WHERE hapus = 0")->row;
			$filter_tanggal = !empty($latest['latest']) ? date('Y-m-d', strtotime($latest['latest'])) : date('Y-m-d');
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if ($filter_tanggal) {
			$url .= '&filter_tanggal=' . urlencode($filter_tanggal);
		}

		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['permintaans'] = array();
		$limit = 20;
		$start = ($page - 1) * $limit;

		// 1. Get dynamic warehouses that have ANY price up to the selected date
		$gudang_sql = "SELECT DISTINCT h.gudang, g.nama 
					  FROM harga_terendah_new h 
					  LEFT JOIN " . DB_PREFIX . "gudang g ON h.gudang = g.gudang_id 
					  WHERE h.hapus = 0 AND DATE(h.tgl_berlaku) <= '" . $this->db->escape($filter_tanggal) . "' 
					  AND h.gudang > 0 ORDER BY g.nama ASC";
		$gudang_results = $this->db->query($gudang_sql)->rows;

		$gudangs = array();
		foreach ($gudang_results as $gr) {
			$gudangs[$gr['gudang']] = !empty($gr['nama']) ? $gr['nama'] : "Gudang " . $gr['gudang'];
		}
		$this->data['gudangs'] = $gudangs;

		// 2. Get total products having any price up to the selected date
		$product_total = 0;
		$total_query = $this->db->query("SELECT COUNT(DISTINCT kodebarang) as total FROM harga_terendah_new WHERE hapus = 0 AND DATE(tgl_berlaku) <= '" . $this->db->escape($filter_tanggal) . "'");
		if ($total_query->num_rows) {
			$product_total = $total_query->row['total'];
		}

		// 3. Get distinct products for the current page (ordered by name)
		// We try to get the latest name from product_baru or fallback to the one in harga_terendah_new
		$sql = "SELECT DISTINCT h.kodebarang, 
				COALESCE(pb.nama, h.nama) as nama_display 
				FROM harga_terendah_new h
				LEFT JOIN product_baru pb ON h.kodebarang = pb.kodebarang
				WHERE h.hapus = 0 AND DATE(h.tgl_berlaku) <= '" . $this->db->escape($filter_tanggal) . "' 
				ORDER BY nama_display ASC 
				LIMIT " . (int)$limit . " OFFSET " . (int)$start;
		
		$results = $this->db->query($sql)->rows;

		if ($results) {
			$codes = array();
			foreach ($results as $result) {
				$codes[] = "'" . $this->db->escape($result['kodebarang']) . "'";
			}

			// 4. Batch fetch the LATEST prices as of the selected date for these products
			$price_sql = "SELECT h.kodebarang, h.gudang, h.harga_terendah, h.tgl_berlaku 
						 FROM harga_terendah_new h
						 INNER JOIN (
							SELECT kodebarang, gudang, MAX(tgl_berlaku) as max_tgl
							FROM harga_terendah_new
							WHERE hapus = 0 AND DATE(tgl_berlaku) <= '" . $this->db->escape($filter_tanggal) . "'
							AND kodebarang IN (" . implode(',', $codes) . ")
							GROUP BY kodebarang, gudang
						 ) latest ON h.kodebarang = latest.kodebarang AND h.gudang = latest.gudang AND h.tgl_berlaku = latest.max_tgl
						 WHERE h.hapus = 0";
			$price_results = $this->db->query($price_sql)->rows;

			$price_map = array();
			$latest_date_per_product = array();

			foreach ($price_results as $pr) {
				$price_map[$pr['kodebarang']][$pr['gudang']] = $pr['harga_terendah'];
				
				// Track the most recent update date for this product across warehouses for display
				if (!isset($latest_date_per_product[$pr['kodebarang']]) || $pr['tgl_berlaku'] > $latest_date_per_product[$pr['kodebarang']]) {
					$latest_date_per_product[$pr['kodebarang']] = $pr['tgl_berlaku'];
				}
			}

			$i = ($page - 1) * $limit + 1;
			foreach ($results as $result) {
				$item_prices = array();
				foreach ($gudangs as $g_id => $g_name) {
					$item_prices[$g_id] = isset($price_map[$result['kodebarang']][$g_id]) ? $this->currency->format($price_map[$result['kodebarang']][$g_id]) : '-';
				}

				$display_date = isset($latest_date_per_product[$result['kodebarang']]) ? $latest_date_per_product[$result['kodebarang']] : $filter_tanggal;

				$this->data['permintaans'][] = array(
					'no'      => $i++,
					'tanggal' => date('d-m-Y', strtotime($display_date)),
					'kode'    => $result['kodebarang'],
					'nama'    => $result['nama_display'],
					'prices'  => $item_prices
				);
			}
		}

		$this->data['heading_title'] = 'Daftar Harga Terendah';
		$this->data['filter_tanggal'] = $filter_tanggal;

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('laporan/hargaterendah', 'token=' . $this->session->data['token'] . '&filter_tanggal=' . $filter_tanggal . '&page={page}');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'hargaterendah/list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
}
?>
