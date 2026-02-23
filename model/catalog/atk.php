<?php
class ModelCatalogAtk extends Model {
	public function addAtk($data){
		$atk=array(
			'nama'	=>$this->db->escape($data['nama']),
			'qty'	=>0,
			'hapus'	=>0,
			'net_cost'	=>!isset($data['net_cost'])?0:$data['net_cost']
		);
		$this->db->insert('atk',$atk);
	}

	public function updateNetCost($atk_id,$net_cost){
		$this->db->query("UPDATE ".DB_PREFIX."atk SET net_cost='".$net_cost."' WHERE atk_id='".$atk_id."'");
		
	}

	public function setStokAwal($data){
		$curqty=$this->getAtk(array('atk_id'=>$data['atk_id']));
		$updateqty=$this->updateQty($data['atk_id'],$data['qty'],1);

		//kartustok
		$this->load->model('gudang/kartustok');
		$kartustok=array(
			'product_id'	=> $data['atk_id'],
			'product_name'	=> $curqty['nama'],
			'tgl'	=> date('Y-m-d h:i:s',time()),
			'stokkeluar'	=> 0,
			'stokmasuk'	=> $data['qty'],
			'ket'	=> 'Set stok awal oleh '.$this->user->getName(),
			'saldo'	=> $data['qty'],
			'quantityawal'	=> 0,
			'invoice'	=> '',
			//'gudang_id'	=> $data['gudang_id'],
			'type'	=> 4
		);

		$this->model_gudang_kartustok->addKartuStokGlobal('kartustok_atk',$kartustok);

		/*
		Type:
		1. Pembelian
		2. penjualan
		3. Stok Opname
		4. Set Stok Awal
		5. Produk Cacat
		6. Produk Hilang
		7.produksi
		*/
	}

	public function stokopname($data){
		$curqty=$this->getAtk(array('atk_id'=>$data['atk_id']));
		if($data['qty'] <= $curqty['qty']){
			$cur=$curqty['qty'] - $data['qty'];
			$stokmasuk=0;
			$stokkeluar=$cur;
			$this->updateQty($data['atk_id'],$cur,2);
		}
		if($data['qty'] > $curqty['qty']){
			$cur=$data['qty']-$curqty['qty'];
			$stokmasuk=$cur;
			$stokkeluar=0;
			$this->updateQty($data['atk_id'],$cur,1);
		}


		$this->load->model('gudang/kartustok');
		$kartustok=array(
			'product_id'	=> $data['atk_id'],
			'product_name'	=> $curqty['nama'],
			'tgl'	=> date('Y-m-d h:i:s',time()),
			'stokkeluar'	=> $stokkeluar,
			'stokmasuk'	=> $stokmasuk,
			'ket'	=> 'Stok opname oleh '.$this->user->getName(),
			'saldo'	=> $data['qty'],
			'quantityawal'	=> $curqty['qty'],
			'invoice'	=> '',
			//'gudang_id'	=> $data['gudang_id'],
			'type'	=> 5
		);

		$this->model_gudang_kartustok->addKartuStokGlobal('kartustok_atk',$kartustok);
	}

	public function updateAtk($data,$where=array()){
	$this->db->update('atk',$data,$where);
	}
	public function getAtk($where){
		return $this->db->first('atk',$where);
	}
	public function getAtks($where,$order,$limit,$offset){
		return $this->db->all('atk',$where,$order,$limit,$offset);
	}
	public function totalAtks($where){
		return $this->db->count('atk',$where);
	}
	public function updateQty($id,$qty,$jenis){
		$data=$this->getAtk(array('atk_id'=>$id));

		//update qty
		if($jenis == 1){
			$qtyf=$data['qty'] + $qty;
		}
		if($jenis == 2){
			$qtyf=$data['qty'] - $qty;
		}
		$this->updateAtk(array('qty'=>$qtyf),array('atk_id'	=> $id));
		return $qtyf;
	  //$this->db->query("UPDATE ".DB_PREFIX."bahanbaku SET quantity='".$qtyf."' WHERE id='".$id."'");
	}
	//atk master
	/*public function addAtklist($data){
		$this->db->query("INSERT INTO ".DB_PREFIX."atk(nama,keterangan,qty,status) values('".$this->db->escape($data['nama'])."','".$this->db->escape($data['keterangan'])."','0','1')");
	}

	public function editAtklist($atk_id,$data){
		$this->db->query("UPDATE ".DB_PREFIX."atk set nama='".$this->db->escape($data['nama'])."',keterangan='".$this->db->escape($data['keterangan'])."' where atk_id='".$atk_id."'");
	}

	public function editQtyAtkList($data){
		$this->db->query("UPDATE ".DB_PREFIX."atk set qty='".$data['qty']."' where atk_id='".$data['atk_id']."'");
	}

	public function deleteAtklist($atk_id){
		$this->db->query("UPDATE ".DB_PREFIX."atk set status='0' where atk_id='".$atk_id."'");
	}

	//atk master per gudang

	public function addAtk($data){
		foreach($data['atk'] as $a){
			$simpan=$this->isSaved($data['atk_id'],$a['toko_id']);
			if(!$simpan & !empty($a['quantity'])){
				$this->db->query("INSERT INTO ".DB_PREFIX."atk_toko(toko_id,atk_id,qty,status,harga_beli) values('".$a['toko_id']."','".$data['atk_id']."','".$a['quantity']."','1','".$a['harga_beli']."')");
				$this->db->query("INSERT INTO ". DB_PREFIX ."kartustok_atk values('','".$data['atk_id']."','".$this->db->escape($data['nama'])."',NOW(),'".$a['quantity']."',2,'Stok Awal','".$a['quantity']."','-','0','".$a['toko_id']."')");
				$this->db->query("UPDATE ".DB_PREFIX."atk set qty=qty+'".$a['quantity']."' WHERE atk_id='".$data['atk_id']."'");
			}

		}
	}

	public function editAtk($atk_id,$data){
		$this->db->query("UPDATE ".DB_PREFIX."atk set toko_id='".$data['toko_id']."',harga_beli='".$data['harga_beli']."' where atk_toko_id='".$atk_id."'");
	}



	public function deleteAtk($atk_toko_id){
		$this->db->query("UPDATE ".DB_PREFIX."atk_toko set status='0' where atk_toko_id='".$atk_toko_id."'");
	}

	public function getAtklist($atk_id) {
		$query = $this->db->query("SELECT * from ".DB_PREFIX."atk where atk_id='".$atk_id."' AND status='1'");

		return $query->row;
	}

	public function isSaved($atk_id,$toko_id){
		$query=$this->db->query("SELECT * FROM " . DB_PREFIX . "atk_toko pd WHERE pd.status=1 AND pd.atk_id='".$atk_id."' AND pd.toko_id='".$toko_id."'");
		if($query->num_rows){
			return true;
		}
		else{
			return false;
		}
	}
	public function getAtk($atk_toko_id){
		$query=$this->db->query("SELECT p.atk_id,pd.atk_toko_id,p.nama,p.keterangan,pd.toko_id,pd.gudang_id as gudang_id,g.nama as nama_gudang,pd.harga_beli,pd.qty FROM " . DB_PREFIX . "atk p LEFT JOIN " . DB_PREFIX . "atk_toko pd ON (p.atk_id = pd.atk_id) LEFT JOIN ".DB_PREFIX."gudang g ON(pd.toko_id=g.gudang_id) WHERE p.status='1' AND pd.status='1' AND pd.atk_toko_id='".$atk_toko_id."'");
		return $query->row;
	}
	public function getAtkToko($toko_id,$atk_id){
		$query=$this->db->query("SELECT p.atk_id,pd.atk_toko_id,p.nama,p.keterangan,pd.toko_id,g.nama as nama_gudang,pd.harga_beli,pd.qty FROM " . DB_PREFIX . "atk p LEFT JOIN " . DB_PREFIX . "atk_toko pd ON (p.atk_id = pd.atk_id) LEFT JOIN ".DB_PREFIX."gudang g ON(pd.toko_id=g.gudang_id) WHERE p.status='1' AND pd.status='1' AND pd.toko_id='".$toko_id."' AND pd.atk_id='".$atk_id."'");
		return $query->row;
	}

	public function getAtks($data = array()) {
		if ($data) {
			$sql = "SELECT p.atk_id,pd.atk_toko_id,p.nama,p.keterangan,pd.toko_id,pd.toko_id as gudang_id,g.nama as nama_gudang,pd.harga_beli,pd.qty FROM " . DB_PREFIX . "atk p LEFT JOIN " . DB_PREFIX . "atk_toko pd ON (p.atk_id = pd.atk_id) LEFT JOIN ".DB_PREFIX."gudang g ON(pd.toko_id=g.gudang_id)";


			$sql .= " WHERE p.status='1' AND pd.status='1' ";

			if (!empty($data['filter_name'])) {

				$sql .= " AND LCASE(p.nama) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			}

			if (!empty($data['filter_toko_id'])) {
				$sql .= " AND pd.toko_id='".$data['filter_toko_id']."'";
			}

			$sql .= " GROUP BY pd.atk_toko_id";

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

			$query = $this->db->query("SELECT p.atk_id,pd.atk_toko_id,p.nama,pd.toko_id as gudang_id,p.keterangan,pd.toko_id,g.nama as nama_gudang,pd.harga_beli,pd.qty FROM " . DB_PREFIX . "atk p LEFT JOIN " . DB_PREFIX . "atk_toko pd ON (p.atk_id = pd.atk_id) LEFT JOIN ".DB_PREFIX."gudang g ON(pd.toko_id=g.gudang_id) WHERE p.status='1' ORDER BY p.nama ASC");

			$product_data = $query->rows;



			return $product_data;
		}
	}

	public function getAtkLists($data = array()) {
		if ($data) {
			$sql = "SELECT * from ".DB_PREFIX."atk ";
			$sql .= " WHERE status='1'";

			if (!empty($data['filter_name'])) {

				$sql .= " AND LCASE(nama) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
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

			$query = $this->db->query("SEELECT * from ".DB_PREFIX."atk ORDER BY p.nama ASC");

			$product_data = $query->rows;



			return $product_data;
		}
	}

	public function getTotalAtkList($data = array()){
		$sql = "SELECT count(atk_id) as total from ".DB_PREFIX."atk";

		$sql .= " WHERE status='1'";
			if (!empty($data['filter_name'])) {

				$sql .= " AND LCASE(nama) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			}



			$query = $this->db->query($sql);

			return $query->row['total'];
	}



	public function getTotalAtk($data = array()) {
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "atk p LEFT JOIN " . DB_PREFIX . "atk_toko pd ON (p.atk_id = pd.atk_id) LEFT JOIN ".DB_PREFIX."gudang g ON(pd.toko_id=g.gudang_id)";

			$sql .= " WHERE p.status='1' AND pd.status='1' ";


			if (!empty($data['filter_name'])) {

				$sql .= " AND LCASE(p.nama) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			}

			if (!empty($data['filter_toko_id'])) {
				$sql .= " AND ";

				$sql .= " pd.toko_id='".$data['filter_toko_id']."'";
			}


			$sql .= " GROUP BY pd.atk_toko_id";





			$query = $this->db->query($sql);


		if($query->num_rows){
			return $query->row['total'];
		}
		else{
			return 0;
		}
	}

	public function addProductHilang($data){
		$sql="INSERT INTO ".DB_PREFIX."atk_hilang_gudang SET ";
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
		$this->db->query("UPDATE ".DB_PREFIX."atk SET qty='".$qty."' WHERE atk_id='".$atk_id."' ");
	}

	public function updateQtyGudang($atk_id,$qty,$gudang_id){
		$this->db->query("UPDATE ".DB_PREFIX."atk_toko SET qty='".$qty."' WHERE atk_id='".$atk_id."' AND toko_id='".$gudang_id."' ");
	}*/
}
?>
