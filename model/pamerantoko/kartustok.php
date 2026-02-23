<?php
/*
Purchase Order Controller
Created by morebit   | http://morebit.co   |  info@morebit.co
*/
class ModelPamerantokoKartustok extends Model {
	public function addKartuStok($table,$data){
		$kartustok=array(
			'product_id'	=> $data['product_id'],
			'product_name'	=> $this->db->escape($data['product_name']),
			'tgl'	=> $this->db->escape($data['tgl']),
			'stokkeluar'	=> $data['stokkeluar'],
			'stokmasuk'	=> $data['stokmasuk'],
			'ket'	=> $this->db->escape($data['ket']),
			'saldo'	=> $data['saldo'],
			'quantityawal'	=> $data['quantityawal'],
			'invoice'	=> $data['invoice'],
			'pameran_id'	=> $data['pameran_id'],
			'type'	=> $data['type']
		);

		$this->db->insert($table,$kartustok);



	}

	public function getKartuStoks($table,$data=array()){
		$sql="SELECT * FROM ".DB_PREFIX.$table." k JOIN ".DB_PREFIX."type_perubahan_stok_pt t ON(k.type=t.id) WHERE product_id='".$data['product_id']."' AND pameran_id='".$data['pameran_id']."' ";
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

	public function getTotalKartustoks($table,$data=array()){
		$sql="SELECT * FROM ".DB_PREFIX.$table." WHERE product_id='".$data['product_id']."' AND pameran_id='".$data['pameran_id']."' ";
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
