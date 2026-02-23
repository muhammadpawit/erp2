<?php
/*
Purchase Order Controller
Created by morebit   | http://morebit.co   |  info@morebit.co
*/
class ModelCatalogKartustoktabungmp extends Model {
	public function addKartuStok($data){
		$kartustok=array(
			'tabung_id'	=> $data['tabung_id'],
			'tglpeminjaman'	=> $data['tglpeminjaman'],
			'tglpengembalian'	=> $data['tglpengembalian'],
			'tglisiulang'	=> $data['tglisiulang'],
			'customer_id'	=> $data['customer_id'],
			'invoice'	=> $data['invoice'],
			'ket'	=> $data['ket'],
			'date_added'	=> date('Y-m-d H:i:s',time()),
			'date_modified'	=> date('Y-m-d H:i:s',time()),
			'biayasewa'	=> $data['biayasewa'],
			'tabel_ref'	=> $data['tabel_ref'],
			'idref'	=> $data['idref'],
			'jenistransaksi'	=> $data['jenistransaksi']
		);


		$this->db->insert('kartustok_tabungmp',$kartustok);



	}

	public function updateKartuStok($tabung_id,$customer_id,$tglpengembalian){
		$kartu=array(
			'tglpengembalian'	=> $tglpengembalian,
			'date_modified'	=> date('Y-m-d H:i:s',time())

		);
		$where=array(
			'tabung_id'	=> $tabung_id,
			'customer_id'	=> $customer_id
		);

		$this->db->update('kartustok_tabungmp',$kartu,$where);
	}
	public function getKartuStoks($data=array()){
		$sql="SELECT * FROM ".DB_PREFIX."kartustok_tabungmp k LEFT JOIN ".DB_PREFIX."customer t ON(k.customer_id=t.customer_id) JOIN ".DB_PREFIX."tabung_mp p ON(k.tabung_id=p.id) WHERE tabung_id='".$data['tabung_id']."' ";
		if(!empty($data['tanggal'])){
			$sql .=" AND tglpeminjaman='".$data['tanggal']."' ";
		}
		$sql.="ORDER BY k.id ";
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
		$sql="SELECT * FROM ".DB_PREFIX."kartustok_tabungmp WHERE tabung_id='".$data['tabung_id']."' ";
		if(!empty($data['tanggal'])){
			$sql .=" AND tglpeminjaman='".$data['tglpeminjaman']."' ";
		}
		$sql .="ORDER BY id ";

		$query = $this->db->query($sql);

		return $query->num_rows;
	}


}
?>
