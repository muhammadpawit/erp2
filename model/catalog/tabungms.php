<?php
class ModelCatalogTabungms extends Model {
	public function addTabung($data) {
		//cek
		//variabel yang dibawa
		/*
		product_id
		gudang_id
		jenisgas
		quantity
		tanggal
		ref
		*/
		$this->load->model('catalog/kartustoktabungstok');
		$cek=$this->getTabungByGudang($data['product_id'],$data['gudang_id']);
		if(empty($cek)){
			$tabung=array(
				'product_id'	=> !isset($data['product_id'])?0:$data['product_id'],
				'quantity'	=> !isset($data['quantity'])?0:$data['quantity'],
				'jenisgas'	=> $data['jenisgas'],
				'gudang_id'	=> $data['gudang_id'],
				'hapus'	=> 0

			);
			$this->db->insert('tabung_stok',$tabung);

			$kartustok=array(
				'tabung_id'	=> $data['product_id'],
				'tgl'	=> $data['tanggal'],
				'stokkeluar'	=> 0,
				'stokmasuk'	=> $data['quantity'],
				'ket'	=> $this->db->escape("Pengisian tabung"),
				'saldo'	=> $data['quantity'],
				'quantityawal'	=> 0,
				'invoice'	=> $data['ref'],
				'gudang_id'	=> $data['gudang_id'],
				'type'	=> 1
			);
		}else{
			$curqty=$this->getTabungByGudang($data['product_id'],$data['gudang_id']);


			$update=$this->updateQty($data['product_id'],$data['gudang_id'],$data['quantity'],1);

			$kartustok=array(
				'tabung_id'	=> $data['product_id'],
				'tgl'	=> $data['tanggal'],
				'stokkeluar'	=> 0,
				'stokmasuk'	=> $data['quantity'],
				'ket'	=> $this->db->escape("Pengisian tabung"),
				'saldo'	=> $update,
				'quantityawal'	=> $curqty['quantity'],
				'invoice'	=> $data['ref'],
				'gudang_id'	=> $data['gudang_id'],
				'type'	=> 1
			);
		}
		$this->model_catalog_kartustoktabungstok->addKartuStok($kartustok);
		return $this->db->getLastId();

	}

	public function UpdateQty($product_id,$gudang_id,$quantity,$jenis){
			//get Current quantity
			$cur=$this->getTabungByGudang($product_id,$gudang_id);

			/*jenis
			1. +
			2. -
			*/

			if($jenis == 1){
				$curquantity = $cur['quantity'] + $quantity;
			}

			if($jenis == 2){
				$curquantity = $cur['quantity'] - $quantity;
			}

			$this->db->query("UPDATE ".DB_PREFIX."tabung_stok SET quantity='".$curquantity."' WHERE product_id='".$product_id."' AND gudang_id='".$gudang_id."'");


			return $curquantity;
	}

	public function UpdateQtyProduct($product_id,$gudang_id,$quantity,$jenis){
			//get Current quantity
			$cur=$this->getTabungByGudang($product_id,$gudang_id);

			/*jenis
			1. +
			2. -
			*/

			if($jenis == 1){
				$curquantity = $cur['quantity'] + $quantity;
			}

			if($jenis == 2){
				$curquantity = $cur['quantity'] - $quantity;
			}

			$this->db->query("UPDATE ".DB_PREFIX."tabung_stok SET quantity='".$curquantity."' WHERE product_id='".$product_id."' AND gudang_id='".$gudang_id."'");


			return $curquantity;
	}

	public function editTabung($id, $data) {
		$tabung=array(
			'status'	=> $data['status'],
			'ukuran_tabung'	=> $data['ukuran_tabung'],
			'quantity'	=> $data['quantity'],
			'date_added'	=> date('Y-m-d H:i:s',time()),
			'date_modified'	=> date('Y-m-d H:i:s',time())
		);
		$where=array(
			'id'=> $id
		);
		$this->db->update('tabung_stok',$tabung,$where);

	}



	public function deleteTabung($id) {

		$tabung=array(
			'hapus'	=> 1,
			'date_modified'	=> date('Y-m-d H:i:s',time())
		);
		$where=array(
			'id'=> $id
		);
		$this->db->update('tabung_stok',$tabung,$where);
	}

	public function getTabungs($data = array()) {
		$sql = "SELECT t.*,o.name as ukuran,p.name as namaproduct,tb.name as tabungname,g.nama FROM " . DB_PREFIX . "tabung_stok t LEFT JOIN ".DB_PREFIX."product p ON(t.jenisgas=p.product_id) LEFT JOIN ".DB_PREFIX."product tb ON(tb.product_id=t.product_id) LEFT JOIN product_options o ON(tb.ukuran_tabung=o.product_options_id)  LEFT JOIN gudang g ON(t.gudang_id=g.gudang_id)  WHERE t.hapus=0 ";
		if(isset($data['filter_product_id'])){
			if(!empty($data['filter_product_id'])){
				$sql .="  AND t.product_id='".$data['filter_product_id']."' ";
			}
		}
		if(isset($data['filter_name'])){
			if(!empty($data['filter_name'])){
				$sql .="  AND lower(tb.name)='".utf8_strtolower($data['filter_name'])."' ";
			}
		}
		if(isset($data['filter_gas'])){
			if(!empty($data['filter_gas'])){
				$sql .="  AND lower(p.name)='".utf8_strtolower($data['filter_gas'])."' ";
			}
		}

		if(isset($data['filter_jenisgas'])){
			if(!empty($data['filter_jenisgas'])){
				$sql .="  AND jenisgas='".$data['filter_jenisgas']."' ";
			}
		}
		if(isset($data['filter_gudang_id'])){
			if(!empty($data['filter_gudang_id'])){
				$sql .="  AND t.gudang_id='".$data['filter_gudang_id']."' ";
			}
		}
		if(isset($data['filter_ukuran_tabung'])){
			if(!empty($data['filter_ukuran_tabung'])){
				$sql .="  AND tb.ukuran_tabung='".$data['filter_ukuran_tabung']."' ";
			}
		}
		$sql .=" ORDER BY t.product_id ";
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}

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

	public function getTabung($tabung) {
		$query = $this->db->query("SELECT t.*,o.name as namaukuran ,pd.name as namaproduct FROM " . DB_PREFIX . "tabung_stok t JOIN product_options o ON(t.ukuran_tabung=o.product_options_id) LEFT JOIN product pd ON(t.product_id = pd.product_id) WHERE id='".$tabung."' ");

		return $query->row;
	}
	public function getTabungByProduct($jenisgas,$gudang_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tabung_stok WHERE jenisgas='".$jenisgas."' AND gudang_id='".$gudang_id."'");

		return $query->row;
	}

	public function getTabungByGudang($jenisgas,$gudang_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tabung_stok WHERE product_id='".$jenisgas."' AND gudang_id='".$gudang_id."'");

		return $query->row;
	}
	public function getProductByGudang($jenisgas,$gudang_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tabung_stok WHERE product_id='".$jenisgas."' AND gudang_id='".$gudang_id."'");

		return $query->row;
	}

	public function getTotalTabungs($data=array()) {
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "tabung_stok t LEFT JOIN ".DB_PREFIX."product p ON(t.jenisgas=p.product_id) LEFT JOIN ".DB_PREFIX."product tb ON(tb.product_id=p.product_id) LEFT JOIN product_options o ON(tb.ukuran_tabung=o.product_options_id)  LEFT JOIN gudang g ON(t.gudang_id=g.gudang_id)  WHERE t.hapus=0 ";
		if(isset($data['filter_product_id'])){
			if(!empty($data['filter_product_id'])){
				$sql .="  AND t.product_id='".$data['filter_product_id']."' ";
			}
		}
		if(isset($data['filter_name'])){
			if(!empty($data['filter_name'])){
				$sql .="  AND lower(tb.name)='".utf8_strtolower($data['filter_name'])."' ";
			}
		}
		if(isset($data['filter_gas'])){
			if(!empty($data['filter_gas'])){
				$sql .="  AND lower(p.name)='".utf8_strtolower($data['filter_gas'])."' ";
			}
		}
		if(isset($data['filter_jenisgas'])){
			if(!empty($data['filter_jenisgas'])){
				$sql .="  AND jenisgas='".$data['filter_jenisgas']."' ";
			}
		}
		if(isset($data['filter_gudang_id'])){
			if(!empty($data['filter_gudang_id'])){
				$sql .="  AND t.gudang_id='".$data['filter_gudang_id']."' ";
			}
		}
		if(isset($data['filter_ukuran_tabung'])){
			if(!empty($data['filter_ukuran_tabung'])){
				$sql .="  AND tb.ukuran_tabung='".$data['filter_ukuran_tabung']."' ";
			}
		}
			$query = $this->db->query($sql);

		return $query->row['total'];
	}



	public function getKartuStoks($data=array()){
		$sql="SELECT * FROM ".DB_PREFIX."kartu_tabung  WHERE aset_id='".$data['aset_id']."' ";
		if(!empty($data['tanggal'])){
			$sql .=" AND DATE(tanggal)='".$data['tanggal']."' ";
		}
		$sql.="ORDER BY id ";
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





	public function getAsets($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
		return $this->db->alljoin('tabung_stok',$column,$join,$where,$order,$limit,$offset);
	}
}
?>
