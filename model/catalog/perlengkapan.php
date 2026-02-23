<?php
class ModelCatalogPerlengkapan extends Model {
	//perlengkapanmaster	
	public function addPerlengkapanlist($data){
		$this->db->query("INSERT INTO ".DB_PREFIX."perlengkapan(nama,keterangan,qty,status) values('".$this->db->escape($data['nama'])."','".$this->db->escape($data['keterangan'])."','0','1')");
	}
	
	public function editPerlengkapanlist($perlengkapan_id,$data){
		$this->db->query("UPDATE ".DB_PREFIX."perlengkapan set nama='".$this->db->escape($data['nama'])."',keterangan='".$this->db->escape['keterangan']."' where perlengkapan_id='".$perlengkapan_id."'");
	}

	public function editQtyPerlengkapanList($data){
		$this->db->query("UPDATE ".DB_PREFIX."perlengkapan set qty='".$data['qty']."' where perlengkapan_id='".$data['perlengkapan_id']."'");
	}
	
	public function deletePerlengkapanlist($perlengkapan_id){
		$this->db->query("UPDATE ".DB_PREFIX."perlengkapan set status='0' where perlengkapan_id='".$perlengkapan_id."'");
	}

	//perlengkapanmaster per gudang
	
	public function addPerlengkapan($data){
		foreach($data['atk'] as $a){
			$simpan=$this->isSaved($data['atk_id'],$a['toko_id']);
			if(!$simpan  & !empty($a['quantity'])){
				$this->db->query("INSERT INTO ".DB_PREFIX."perlengkapan_toko(toko_id,perlengkapan_id,status,qty,harga_beli,tglpembelian,tahunekonomis) values('".$a['toko_id']."','".$data['atk_id']."','1','".$a['quantity']."','".$a['harga_beli']."','".$a['tglpembelian']."','".$a['tahunekonomis']."')");
				$this->db->query("INSERT INTO ". DB_PREFIX ."kartustok_perlengkapan values('','".$data['atk_id']."','".$this->db->escape($data['nama'])."',NOW(),'".$a['quantity']."',2,'Stok Awal','".$a['quantity']."','-','0','".$a['toko_id']."','0','".$a['harga_beli']."')");	
				$this->db->query("UPDATE ".DB_PREFIX."perlengkapan set qty=qty+'".$a['quantity']."' WHERE perlengkapan_id='".$data['atk_id']."'");
			}
		}
	}

	public function editPerlengkapan($perlengkapan_id,$data){
		$this->db->query("UPDATE ".DB_PREFIX."perlengkapanset toko_id='".$data['toko_id']."',harga_beli='".$data['harga_beli']."' where perlengkapan_toko_id='".$perlengkapan_id."'");
	}

	
	
	public function deletePerlengkapan($perlengkapan_toko_id){
		$this->db->query("UPDATE ".DB_PREFIX."perlengkapan_toko set status='0' where perlengkapan_toko_id='".$perlengkapan_toko_id."'");
	}

	public function getPerlengkapanlist($perlengkapan_id) {
		$query = $this->db->query("SELECT * from ".DB_PREFIX."perlengkapan where perlengkapan_id='".$perlengkapan_id."' AND status='1'");
				
		return $query->row;
	}
	
	public function isSaved($perlengkapan_id,$toko_id){
		$query=$this->db->query("SELECT * FROM " . DB_PREFIX . "perlengkapan_toko pd WHERE pd.status=1 AND pd.perlengkapan_id='".$perlengkapan_id."' AND pd.toko_id='".$toko_id."'");
		if($query->num_rows){
			return true;
		}
		else{
			return false;
		}
	}
	public function getPerlengkapan($perlengkapan_toko_id){
		$query=$this->db->query("SELECT p.perlengkapan_id,pd.perlengkapan_toko_id,p.nama,p.keterangan,pd.toko_id,g.nama as nama_gudang,pd.harga_beli,pd.nilai_barang,pd.qty FROM " . DB_PREFIX . "perlengkapan p LEFT JOIN " . DB_PREFIX . "perlengkapan_toko pd ON (p.perlengkapan_id= pd.perlengkapan_id) LEFT JOIN ".DB_PREFIX."gudang g ON(pd.toko_id=g.gudang_id) WHERE p.status='1' AND pd.status='1' AND pd.perlengkapan_toko_id='".$perlengkapan_toko_id."'");
		return $query->row;
	}
	
	public function getPerlengkapanToko($toko_id,$perlengkapan_id){
		$query=$this->db->query("SELECT p.perlengkapan_id,pd.perlengkapan_toko_id,p.nama,p.keterangan,pd.toko_id,g.nama as nama_gudang,pd.harga_beli,pd.qty FROM " . DB_PREFIX . "perlengkapan p LEFT JOIN " . DB_PREFIX . "perlengkapan_toko pd ON (p.perlengkapan_id = pd.perlengkapan_id) LEFT JOIN ".DB_PREFIX."gudang g ON(pd.toko_id=g.gudang_id) WHERE p.status='1' AND pd.status='1' AND pd.toko_id='".$toko_id."' AND pd.perlengkapan_id='".$perlengkapan_id."'");
		return $query->row;
	}
        
        
	public function getPerlengkapans($data = array()) {
		if ($data) {
			$sql = "SELECT p.perlengkapan_id,pd.perlengkapan_toko_id,p.nama,p.keterangan,pd.toko_id,g.nama as nama_gudang,pd.harga_beli,pd.nilai_barang,pd.qty,pd.tglpembelian,pd.tahunekonomis FROM " . DB_PREFIX . "perlengkapan p LEFT JOIN " . DB_PREFIX . "perlengkapan_toko pd ON (p.perlengkapan_id= pd.perlengkapan_id) LEFT JOIN ".DB_PREFIX."gudang g ON(pd.toko_id=g.gudang_id)";
			
				
			$sql .= " WHERE p.status='1' AND pd.status='1' "; 
			
			if (!empty($data['filter_name'])) {

				$sql .= " AND LCASE(p.nama) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			}

			if (!empty($data['filter_toko_id'])) {
				$sql .= " AND pd.toko_id='".$data['filter_toko_id']."'";
			}
			
			$sql .= " GROUP BY pd.perlengkapan_toko_id";
						
			$sort_data = array(
				'p.nama',
				'pd.toko_id'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY p.nama";	
			}
			
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
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			$query = $this->db->query($sql);
		
			return $query->rows;
		} else {
			
			$query = $this->db->query("SELECT p.perlengkapan_id,pd.perlengkapan_toko_id,p.nama,p.keterangan,pd.toko_id,g.nama as nama_gudang,pd.harga_beli,pd.qty FROM " . DB_PREFIX . "perlengkapan p LEFT JOIN " . DB_PREFIX . "perlengkapan_toko pd ON (p.perlengkapan_id= pd.perlengkapan_id) LEFT JOIN ".DB_PREFIX."gudang g ON(pd.toko_id=g.gudang_id) WHERE p.status='1' ORDER BY p.nama ASC");

			$product_data = $query->rows;
		
				
	
			return $product_data;
		}
	}
	
	public function getPerlengkapanLists($data = array()) {
		if ($data) {
			$sql = "SELECT * from ".DB_PREFIX."perlengkapan";
			$sql .= " WHERE status='1'"; 
				
			if (!empty($data['filter_name'])) {

				$sql .= " AND LCASE(nama) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			}

			
		
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			$query = $this->db->query($sql);
		
			return $query->rows;
		} else {
			
			$query = $this->db->query("SEELECT * from ".DB_PREFIX."perlengkapanORDER BY p.nama ASC");

			$product_data = $query->rows;
		
				
	
			return $product_data;
		}
	}
	
	public function getTotalPerlengkapanList($data = array()){
		$sql = "SELECT count(perlengkapan_id) as total from ".DB_PREFIX."perlengkapan";
			
		$sql .= " WHERE status='1'"; 		
			if (!empty($data['filter_name'])) {

				$sql .= " AND LCASE(nama) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			}

				
			
			$query = $this->db->query($sql);
		
			return $query->row['total'];
	}

	
	
	public function getTotalPerlengkapan($data = array()) {
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "perlengkapan p LEFT JOIN " . DB_PREFIX . "perlengkapan_toko pd ON (p.perlengkapan_id= pd.perlengkapan_id) LEFT JOIN ".DB_PREFIX."gudang g ON(pd.toko_id=g.gudang_id)";
			
			$sql .= " WHERE p.status='1' AND pd.status='1' "; 
			
			
			if (!empty($data['filter_name'])) {

				$sql .= " AND LCASE(p.nama) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			}

			if (!empty($data['filter_toko_id'])) {
				$sql .= " AND "; 
				
				$sql .= " pd.toko_id='".$data['filter_toko_id']."'";
			}		
			
			
			$sql .= " GROUP BY pd.perlengkapan_toko_id";
						
			
		
			
			
			$query = $this->db->query($sql);
		
		
		if($query->num_rows){
			return $query->row['total'];
		}
		else{
			return 0;
		}
	}	
	public function addProductHilang($data){
		$sql="INSERT INTO ".DB_PREFIX."perlengkapan_hilang_gudang SET ";
		$i=1;
		foreach($data as $key => $value){
			if($i != 1){
		         $sql .=",";
			}
			$sql .=$key."= '".$value."'";
			$i++;
		}

		$this->db->query($sql);
		
	}
	
	public function updateQty($atk_id,$qty){
		$this->db->query("UPDATE ".DB_PREFIX."perlengkapan SET qty='".$qty."' WHERE perlengkapan_id='".$atk_id."' ");
	}
	
	public function updateQtyGudang($atk_id,$qty,$gudang_id){
		$this->db->query("UPDATE ".DB_PREFIX."perlengkapan_toko SET qty='".$qty."' WHERE perlengkapan_id='".$atk_id."' AND toko_id='".$gudang_id."' ");
	}
	
}
?>
