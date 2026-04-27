<?php
class ModelGudangProduct extends Model {
	// baru 24 Agustus 2020
	public function getprodukbelumsetharga($gudang_id,$date){
		$sql = "SELECT pg.product_id, p.name 
				FROM product_gudang pg
				LEFT JOIN product p ON (p.product_id = pg.product_id)
				WHERE pg.gudang_id = '$gudang_id' 
				AND NOT EXISTS (
					SELECT 1 
					FROM harga_terendah_new h 
					WHERE LOWER(h.nama) = LOWER(p.name) 
					AND h.gudang = '$gudang_id' 
					AND h.tgl_berlaku <= '$date 23:59:59' 
					AND h.hapus = 0
				)
				ORDER BY p.name";
		$d = $this->db->query($sql);
		return $d->rows;
	}
	
	public function getqty($product_id,$gudang_id,$data){
		$sql="SELECT * FROM kartustok_produk WHERE product_id='$product_id' AND gudang_id='$gudang_id' ";
		if(!empty($data['filter_date_start'])){
			$sql.=" AND DATE(tgl) <='".$data['filter_date_start']."' ";
		}
		
		$sql.=" ORDER BY tgl desc limit 1";
		$d=$this->db->query($sql);
		if(!empty($d)){
			return $d->row;
		}else{
			return 0;
		}
	}

	public function gettglterakhir($gudang_id){
		$sql="SELECT tgl_berlaku FROM harga_terendah_new WHERE gudang='$gudang_id' GROUP BY tgl_berlaku ORDER BY tgl_berlaku DESC LIMIT 1 ";
		$query=$this->db->query($sql);
		return isset($query->row['tgl_berlaku']) ? $query->row['tgl_berlaku'] : date('Y-m-d');
	}

	public function getdaftarbarang($gudang_id){
		$sql="SELECT product_gudang.*, product.name FROM product_gudang LEFT JOIN product ON(product.product_id=product_gudang.product_id) WHERE product_gudang.gudang_id='$gudang_id' AND product_gudang.status=1 ORDER BY product.name ";
		$query=$this->db->query($sql);
		return $query->rows;
	}

	public function gethargarendah($product_id,$gudang_id,$date){
		$sql="SELECT h.* FROM harga_terendah_new h JOIN product p ON (LOWER(h.nama) = LOWER(p.name)) WHERE h.hapus=0 AND DATE(h.tgl_berlaku) <='".$this->db->escape($date)."' AND p.product_id='".$this->db->escape($product_id)."' and h.gudang='".$this->db->escape($gudang_id)."' ORDER BY h.tgl_berlaku DESC LIMIT 1 ";
		$d= $this->db->query($sql);
		return $d->row;
	}

	public function getpoinproduct($date,$product_id,$gudang_id){
		$sql="SELECT h.* FROM harga_terendah_new h JOIN product p ON (LOWER(h.nama) = LOWER(p.name)) WHERE h.hapus=0 AND DATE(h.tgl_berlaku) <='".$this->db->escape($date)."' AND p.product_id='".$this->db->escape($product_id)."' and h.gudang='".$this->db->escape($gudang_id)."' ORDER BY h.tgl_berlaku DESC LIMIT 1 ";
		$d= $this->db->query($sql);
		return isset($d->row['poin']) ? $d->row['poin'] : 0;
	}

	public function gethargaterendahdetailkomisi($date,$product_id,$gudang_id){
		$sql="SELECT h.* FROM harga_terendah_new h JOIN product p ON (LOWER(h.nama) = LOWER(p.name)) WHERE h.hapus=0 AND DATE(h.tgl_berlaku) <='".$this->db->escape($date)."' AND p.product_id='".$this->db->escape($product_id)."' and h.gudang='".$this->db->escape($gudang_id)."' ORDER BY h.tgl_berlaku DESC LIMIT 1 ";
		$d= $this->db->query($sql);
		return isset($d->row['harga_terendah']) ? $d->row['harga_terendah'] : 0;
	}

	public function getperiodeharga($gudang_id){
		$sql="SELECT DISTINCT tgl_berlaku as date FROM harga_terendah_new WHERE gudang='$gudang_id' and hapus=0 ORDER BY tgl_berlaku DESC ";
		$d= $this->db->query($sql);
		return $d->rows;
	}

	public function deleteperiode($gudang_id, $date){
		$this->db->query("UPDATE harga_terendah_new SET hapus = 1 WHERE gudang = '" . (int)$gudang_id . "' AND tgl_berlaku = '" . $this->db->escape($date) . "'");
	}

	public function deletehargaterendah($id) {
		$this->db->query("UPDATE harga_terendah_new SET hapus = 1 WHERE id = '" . (int)$id . "'");
	}

	public function deleteproductgudang($product_id, $gudang_id) {
		$this->db->query("UPDATE product_gudang SET status = 0 WHERE product_id = '" . (int)$product_id . "' AND gudang_id = '" . (int)$gudang_id . "'");
	}

	public function daftarhargaterendah($data){
		$url_where = " WHERE pg.status=1 AND h.hapus=0 ";
		
		if(!empty($data['filter_name'])){
			$url_where .= " AND LOWER(p.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		if(!empty($data['date'])){
			$url_where .=" AND h.tgl_berlaku <='".$this->db->escape($data['date'])." 23:59:59' ";
		}
		if(!empty($data['filter_gudang_id'])){
			$url_where .=" AND h.gudang='".$this->db->escape($data['filter_gudang_id'])."' ";
		}
        
		$sql = "SELECT DISTINCT ON (pg.product_id, pg.gudang_id)
					h.id,
					pg.product_id,
					p.name,
					pg.gudang_id,
					g.nama as gudang_nama,
					h.harga_terendah,
					h.poin,
					h.tgl_berlaku as date
				FROM product_gudang pg
				LEFT JOIN product p ON p.product_id = pg.product_id
				LEFT JOIN gudang g ON g.gudang_id = pg.gudang_id
				JOIN harga_terendah_new h ON (LOWER(h.nama) = LOWER(p.name) AND h.gudang = pg.gudang_id::text)
				$url_where
				ORDER BY pg.product_id, pg.gudang_id, h.tgl_berlaku DESC";

		$d = $this->db->query($sql);
		return $d->rows;
	}
	
	public function gethargaterendahdetail($product_id,$gudang_id){
		$sql="SELECT h.* FROM harga_terendah_new h JOIN product p ON (LOWER(h.nama) = LOWER(p.name)) WHERE h.hapus=0 AND p.product_id='".$product_id."' and h.gudang='".$gudang_id."' ORDER BY h.tgl_berlaku DESC LIMIT 1 ";
		$d= $this->db->query($sql);
		return isset($d->row['harga_terendah']) ? $d->row['harga_terendah'] : 0;
	}

	public function addhargaterendah($data){
		$pinfo = $this->db->query("SELECT name FROM product WHERE product_id = '" . (int)$data['product_id'] . "'")->row;
		// Since we don't have a reliable 'kodebarang' in the product table, we might need to find it from product_baru
		$p_baru = $this->db->query("SELECT kodebarang FROM product_baru WHERE nama = '" . $this->db->escape($pinfo['name']) . "' LIMIT 1")->row;
		$kodebarang = isset($p_baru['kodebarang']) ? $p_baru['kodebarang'] : 'UNKNOWN';

		foreach($data['product_special'] as $p ){
			$harga=array(
				'kodebarang'=>$kodebarang,
				'gudang'=>$data['gudang_id'],
				'harga_terendah' => $p['harga_terendah'],
				'tgl_berlaku'=>$p['date'],
				'nama'=>$pinfo['name'],
				'poin'=>$p['poin'],
				'hapus'=>0
			);
			$this->db->insert('harga_terendah_new',$harga);
		}
	}

	public function gethargaterendah($data) {
		$sql="SELECT h.* FROM harga_terendah_new h JOIN product p ON (LOWER(h.nama) = LOWER(p.name)) WHERE h.hapus=0 AND p.product_id='".$data['product_id']."' and h.gudang='".$data['gudang_id']."' ORDER BY h.tgl_berlaku ASC";
		$d= $this->db->query($sql);
		return $d->rows;
	}

	public function getsaldokartustok($data,$product_id,$gudang_id){
		$sql =" SELECT tgl, saldo from kartustok_produk WHERE product_id='$product_id' and gudang_id='$gudang_id' ";
		if(!empty($data['tanggal'])){
			$sql .= " AND DATE(tgl) <='".$data['tanggal']."' ";
		}else{
			$sql .=" AND DATE(tgl) <='".date('Y-m-d')."'";
		}
		$sql .=" ORDER BY kartustok_id DESC LIMIT 1";
		$d = $this->db->query($sql);
		return $d->row;
	}
	
	public function getProductGudang($product_gudang_id){
		$sql="SELECT pg.product_gudang_id,p.product_id,p.name,pg.gudang_id,pg.quantity,pg.status,pg.rak,g.nama as gudang_nama,pg.link_non_web FROM product p LEFT JOIN product_gudang pg ON(p.product_id=pg.product_id) LEFT JOIN gudang g ON(pg.gudang_id=g.gudang_id) ";
		$sql.=" WHERE p.hapus=0 AND pg.product_gudang_id='".$product_gudang_id."'";

		$res=$this->db->query($sql);
		return $res->row;
	}
	public function getProduct($product_id,$gudang_id){
		$sql="SELECT pg.net_cost,pg.product_gudang_id,p.product_id,p.name,pg.gudang_id,pg.quantity,pg.status,g.nama as gudang_nama,pg.net_cost,p.jenistabung,pg.premijual,pg.premikirim,pg.premiambil,pg.premibongkar FROM product p LEFT JOIN product_gudang pg ON(p.product_id=pg.product_id) LEFT JOIN gudang g ON(pg.gudang_id=g.gudang_id) ";
		$sql.=" WHERE p.hapus=0 AND pg.product_id='".$product_id."' AND pg.gudang_id='".$gudang_id."'";

		$res=$this->db->query($sql);
		return $res->row;
	}
}
?>
