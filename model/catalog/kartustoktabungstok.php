<?php
/*
Purchase Order Controller
Created by morebit   | http://morebit.co   |  info@morebit.co
*/
class ModelCatalogKartustoktabungstok extends Model {
	public function addKartuStok($data){
		$kartustok=array(
			'tabung_id'	=> $data['tabung_id'],
			'tgl'	=> $this->db->escape($data['tgl']),
			'stokkeluar'	=> $data['stokkeluar'],
			'stokmasuk'	=> $data['stokmasuk'],
			'ket'	=> $this->db->escape($data['ket']),
			'saldo'	=> $data['saldo'],
			'quantityawal'	=> $data['quantityawal'],
			'invoice'	=> $data['invoice'],
			'gudang_id'	=> $data['gudang_id'],
			'type'	=> $data['type']
		);

		/*
		Type:
		1. Pengisian Produksi
		2. Penggembosan
		3. Penjualan


		*/
		$this->db->insert('kartustok_tabungstok',$kartustok);



	}



	public function getKartuStoks($data=array()){
		$sql="SELECT * FROM ".DB_PREFIX."kartustok_tabungstok WHERE tabung_id='".$data['tabung_id']."' AND gudang_id='".$data['gudang_id']."' ";
		if(!empty($data['type'])){
			$sql .=" AND type='".$data['type']."'";
		}
		if(!empty($data['tanggal'])){
			$sql .=" AND DATE(tgl)='".$data['tanggal']."' ";
		}
		$sql.="ORDER BY kartustok_id ";
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}



	public function getTotalKartustoks($data=array()){
		$sql="SELECT * FROM ".DB_PREFIX."kartustok_tabungstok WHERE tabung_id='".$data['tabung_id']."' AND gudang_id='".$data['gudang_id']."' ";
		if(!empty($data['type'])){
			$sql .=" AND type='".$data['type']."'";
		}
		if(!empty($data['tanggal'])){
			$sql .=" AND DATE(tgl)='".$data['tanggal']."' ";
		}
		$sql .="ORDER BY kartustok_id ";

		$query = $this->db->query($sql);

		return $query->num_rows;
	}

	public function getKartuStokGlobals($tablename,$data=array()){
		$sql="SELECT * FROM ".DB_PREFIX.$tablename." k JOIN ".DB_PREFIX."type_perubahan_stok t ON(k.type=t.id) WHERE product_id='".$data['product_id']."' ";
		if(!empty($data['type'])){
			$sql .=" AND type='".$data['type']."'";
		}
		if(!empty($data['tanggal'])){
			$sql .=" AND DATE(tgl)='".$data['tanggal']."' ";
		}
		$sql.="ORDER BY kartustok_id ";
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}



	public function getTotalKartustokGlobals($tablename,$data=array()){
		$sql="SELECT * FROM ".DB_PREFIX.$tablename." k WHERE product_id='".$data['product_id']."' ";
		if(!empty($data['type'])){
			$sql .=" AND type='".$data['type']."'";
		}
		if(!empty($data['tanggal'])){
			$sql .=" AND DATE(tgl)='".$data['tanggal']."' ";
		}
		$sql .="ORDER BY kartustok_id ";

		$query = $this->db->query($sql);

		return $query->num_rows;
	}
}
?>
