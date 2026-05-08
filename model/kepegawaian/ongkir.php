<?php
class ModelKepegawaianOngkir extends Model {
	public function addOngkir($data){
		$insert_data = array(
			'tanggal'           => $data['tanggal'],
			'nomor'             => $data['nomor'],
			'pelanggan'         => $data['pelanggan'],
			'jenis_request'     => $data['jenis_request'],
			'no_pembayaran'     => $data['no_pembayaran'],
			'biaya_pengiriman'  => (float)str_replace('.', '', $data['biaya_pengiriman']),
			'biaya_lain'        => (float)str_replace('.', '', $data['biaya_lain']),
			'cabang'            => $data['cabang'],
			'penjual1'          => $data['penjual1'],
			'penjual2'          => $data['penjual2'],
			'hapus'             => 0
		);
		$this->db->insert('ongkir_sales', $insert_data);
	}

	public function updateOngkir($data, $where = array()){
		if (isset($data['biaya_pengiriman'])) {
			$data['biaya_pengiriman'] = (float)str_replace('.', '', $data['biaya_pengiriman']);
		}
		if (isset($data['biaya_lain'])) {
			$data['biaya_lain'] = (float)str_replace('.', '', $data['biaya_lain']);
		}
		$this->db->update('ongkir_sales', $data, $where);
	}

	public function getOngkir($id){
		$sql = "SELECT * FROM ongkir_sales WHERE id = '" . (int)$id . "'";
		return $this->db->query($sql)->row;
	}

	public function getOngkirs($data = array()){
		$sql = "SELECT * FROM ongkir_sales WHERE hapus = 0";

		if (!empty($data['filter_tanggal_start'])) {
			$sql .= " AND tanggal >= '" . $this->db->escape($data['filter_tanggal_start']) . "'";
		}

		if (!empty($data['filter_tanggal_end'])) {
			$sql .= " AND tanggal <= '" . $this->db->escape($data['filter_tanggal_end']) . "'";
		}

		if (!empty($data['filter_nomor'])) {
			$sql .= " AND nomor LIKE '%" . $this->db->escape($data['filter_nomor']) . "%'";
		}

		if (!empty($data['filter_pelanggan'])) {
			$sql .= " AND pelanggan LIKE '%" . $this->db->escape($data['filter_pelanggan']) . "%'";
		}

		$sql .= " ORDER BY tanggal DESC, id DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
		}

		return $this->db->query($sql)->rows;
	}

	public function getTotalOngkirs($data = array()){
		$sql = "SELECT COUNT(*) AS total FROM ongkir_sales WHERE hapus = 0";

		if (!empty($data['filter_tanggal_start'])) {
			$sql .= " AND tanggal >= '" . $this->db->escape($data['filter_tanggal_start']) . "'";
		}

		if (!empty($data['filter_tanggal_end'])) {
			$sql .= " AND tanggal <= '" . $this->db->escape($data['filter_tanggal_end']) . "'";
		}

		if (!empty($data['filter_nomor'])) {
			$sql .= " AND nomor LIKE '%" . $this->db->escape($data['filter_nomor']) . "%'";
		}

		if (!empty($data['filter_pelanggan'])) {
			$sql .= " AND pelanggan LIKE '%" . $this->db->escape($data['filter_pelanggan']) . "%'";
		}

		$query = $this->db->query($sql);
		return $query->row['total'];
	}
}
?>
