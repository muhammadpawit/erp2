<?php
class ModelCatalogTabungmrbb extends Model {
	public function addTabung($data) {
		$tabung=array(
			'status'	=> $data['status'],
			'ukuran_tabung'	=> $data['ukuran_tabung'],
			'hapus'	=> 0,
			'date_added'	=> date('Y-m-d H:i:s',time()),
			'date_modified'	=> date('Y-m-d H:i:s',time()),
			'customer_id'	=> !isset($data['customer_id'])?1:$data['customer_id'],
			'product_id'	=> !isset($data['product_id'])?0:$data['product_id'],
			'quantity'	=> !isset($data['quantity'])?0:$data['quantity'],

		);
		$this->db->insert('tabungmr_bb',$tabung);
		return $this->db->getLastId();

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
		$this->db->update('tabungmr_bb',$tabung,$where);

	}



	public function deleteTabung($id) {

		$tabung=array(
			'hapus'	=> 1,
			'date_modified'	=> date('Y-m-d H:i:s',time())
		);
		$where=array(
			'id'=> $id
		);
		$this->db->update('tabungmr_bb',$tabung,$where);
	}

	public function getTabungs($data = array()) {
		$sql = "SELECT t.*,t.status,o.name as ukuran,c.name as namecustomer,p.name as namaproduct,c.title FROM " . DB_PREFIX . "tabungmr_bb t LEFT JOIN product_options o ON(t.ukuran_tabung=o.product_options_id) LEFT JOIN ".DB_PREFIX."customer c ON(t.customer_id=c.customer_id) LEFT JOIN ".DB_PREFIX."bahanbaku p ON(t.product_id=p.id)  WHERE t.hapus=0 ";
		if(isset($data['filter_pemilik'])){
			if(!empty($data['filter_pemilik'])){
				$sql .="  AND t.customer_id='".$data['filter_pemilik']."' ";
			}
		}

		if(isset($data['filter_product_id'])){
			if(!empty($data['filter_product_id'])){
				$sql .="  AND t.product_id='".$data['filter_product_id']."' ";
			}
		}
		if(isset($data['filter_name'])){
			if(!empty($data['filter_name'])){
				$sql .="  AND lower(p.name) LIKE '%".utf8_strtolower($data['filter_name'])."%' ";
			}
		}
		if(isset($data['filter_status'])){
			if(!empty($data['filter_status'])){
				$sql .="  AND t.status='".$data['filter_status']."' ";
			}
		}
		if(isset($data['filter_ukuran_tabung'])){
			if(!empty($data['filter_ukuran_tabung'])){
				$sql .="  AND t.ukuran_tabung='".$data['filter_ukuran_tabung']."' ";
			}
		}
		$sql .=" ORDER BY t.date_added ";
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
		$query = $this->db->query("SELECT t.*,o.name as namaukuran ,pd.name as namaproduct,c.name FROM " . DB_PREFIX . "tabungmr_bb t LEFT JOIN product_options o ON(t.ukuran_tabung=o.product_options_id) LEFT JOIN ".DB_PREFIX."customer c ON(t.customer_id=c.customer_id) LEFT JOIN bahanbaku pd ON(t.product_id = pd.id) WHERE t.id='".$tabung."' ");

		return $query->row;
	}
	public function getTabungByProduct($product_id,$customer_id) {
		$query = $this->db->query("SELECT t.*,o.name as namaukuran ,pd.name as namaproduct,c.name FROM " . DB_PREFIX . "tabungmr_bb t LEFT JOIN product_options o ON(t.ukuran_tabung=o.product_options_id) LEFT JOIN ".DB_PREFIX."customer c ON(t.customer_id=c.customer_id) LEFT JOIN product pd ON(t.product_id = pd.product_id) WHERE t.product_id='".$product_id."' AND t.customer_id='".$customer_id."'");

		return $query->row;
	}

	public function getTotalTabungs($data=array()) {
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "tabungmr_bb t LEFT JOIN product_options o ON(t.ukuran_tabung=o.product_options_id)  WHERE t.hapus=0 ";
		if(isset($data['filter_pemilik'])){
			if(!empty($data['filter_pemilik'])){
				$sql .="  AND t.customer_id='".$data['filter_pemilik']."' ";
			}
		}

		if(isset($data['filter_product_id'])){
			if(!empty($data['filter_product_id'])){
				$sql .="  AND t.product_id='".$data['filter_product_id']."' ";
			}
		}
		if(isset($data['filter_name'])){
			if(!empty($data['filter_name'])){
				$sql .="  AND lower(p.name)='".utf8_strtolower($data['filter_name'])."' ";
			}
		}
		if(isset($data['filter_status'])){
			if(!empty($data['filter_status'])){
				$sql .="  AND t.status='".$data['filter_status']."' ";
			}
		}
		if(isset($data['filter_ukuran_tabung'])){
			if(!empty($data['filter_ukuran_tabung'])){
				$sql .="  AND ukuran_tabung='".$data['filter_ukuran_tabung']."' ";
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
		return $this->db->alljoin('tabungmr_bb',$column,$join,$where,$order,$limit,$offset);
	}
}
?>
