<?php
class ModelCatalogBahanbaku extends Model {
	public function updateQty($id,$qty,$jenis){
		$data=$this->getProduct($id);

		//update qty
		if($jenis == 1){
			$qtyf=$data['quantity'] + $qty;
		}
		if($jenis == 2){
			$qtyf=$data['quantity'] - $qty;
		}
	  $this->db->query("UPDATE ".DB_PREFIX."bahanbaku SET quantity='".$qtyf."' WHERE id='".$id."'");
		return $qtyf;
	}
	public function updateNetCost($id,$net_cost){
		$this->db->query("UPDATE ".DB_PREFIX."bahanbaku SET hargabeli='".$net_cost."' WHERE id='".$id."'");

	}

	/*public function updateNetCost($product_id,$net_cost){
		$this->db->query("UPDATE ".DB_PREFIX."product SET net_cost='".$net_cost."' WHERE product_id='".$product_id."'");
		$hist=array(
			'product_id'=>$product_id,
			'net_cost'=>$net_cost,
			'date_added'	=> date('Y-m-d h:i:s',time())
		);
		$this->db->insert('netcost_history',$hist);
	}*/
	public function addStokAwal($data){
		/**/
		$curqty=$this->getProduct($data['id']);
		$this->updateQty($data['id'],$data['qty'],1);
		$this->db->update('bahanbaku',array('level'=>$data['level']),array('id'=>$data['id']));
		$this->load->model('gudang/kartustok');
		$kartustok=array(
			'product_id'	=> $data['id'],
			'product_name'	=> $curqty['name'],
			'tglawal'	=> date('Y-m-d h:i:s',time()),
			'tglakhir'	=> date('Y-m-d h:i:s',time()),
			'levelawal'	=> 0,
			'levelakhir'	=> $data['level'],
			'qtyawal'	=> 0,
			'qtyakhir'	=> $data['qty'],
			'ket'	=> 'Set Stok Awal',
			'perubahan'	=> $data['qty'],
			'ref'	=> 0,
			//'gudang_id'	=> $data['gudang_id'],
			'type'	=> 4
		);

		$this->model_gudang_kartustok->addKartuStokBahanbaku($kartustok);

		/*
		Type:
		1. Pembelian
		2. penjualan
		3. Stok Opname
		4. Set Stok Awal
		5. Produk Cacat
		6. Produk Hilang
		7.produksi
		8. Penggembosan
		*/

	}

	public function stokOpname($data){
		$curqty=$this->getProduct($data['id']);

		if($data['qty'] <= $curqty['quantity']){
			$cur=$curqty['quantity'] - $data['qty'];
			//$stokmasuk=0;
			$perubahan=$cur;
			$this->updateQty($data['id'],$cur,2);
		}
		if($data['qty'] > $curqty['quantity']){
			$cur=$data['qty']-$curqty['quantity'];
			$perubahan=$cur;
			$stokkeluar=0;
			$this->updateQty($data['id'],$cur,1);
		}


		$this->load->model('gudang/kartustok');
		$kartustok=array(
			'product_id'	=> $data['id'],
			'product_name'	=> $curqty['name'],
			'tglawal'	=> date('Y-m-d h:i:s',time()),
			'tglakhir'	=> date('Y-m-d h:i:s',time()),
			'levelawal'	=> $curqty['level'],
			'levelakhir'	=> $data['level'],
			'qtyawal'	=> $curqty['quantity'],
			'qtyakhir'	=> $data['qty'],
			'ket'	=> 'Stok opname oleh '.$this->user->getName(),
			'perubahan'	=> $perubahan,
			'ref'	=> 0,
			//'gudang_id'	=> $data['gudang_id'],
			'type'	=> 5
		);

		$this->model_gudang_kartustok->addKartuStokBahanbaku($kartustok);

		/*$kartustok=array(
			'product_id'	=> $data['id'],
			'product_name'	=> $curqty['name'],
			'tgl'	=> date('Y-m-d h:i:s',time()),
			'stokkeluar'	=> $stokkeluar,
			'stokmasuk'	=> $stokmasuk,
			'ket'	=> 'Stok opname oleh '.$this->user->getName(),
			'saldo'	=> $data['qty'],
			'quantityawal'	=> $curqty['quantity'],
			'invoice'	=> '',
			//'gudang_id'	=> $data['gudang_id'],
			'type'	=> 5
		);

		$this->model_gudang_kartustok->addKartuStokGlobal('kartustok_bahanbaku',$kartustok);
*/
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

	public function addProduct($data) {
		$data['date_added']=date('Y-m-d H:i:s',time());
		$data['date_modified']=date('Y-m-d H:i:s',time());
		$data['hapus']=0;
		if(!isset($data['hargabeli'])){
			$data['hargabeli']=0;
		}
		$bhnbaku=array(
			'name'	=> $this->db->escape($data['name']),
			'quantity'	=> isset($data['quantity'])?$data['quantity']:0,
			'date_added'	=> date('Y-m-d H:i:s',time()),
			'date_modified'	=> date('Y-m-d H:i:s',time()),
			'hapus'	=> 0,
			'satuan'	=> $data['satuan'],
			'hargabeli'	=> 0,
			'level'	=> 0,
			'quantity'	=> 0

		);

		$this->db->insert('bahanbaku',$bhnbaku);

	}

	public function editProduct($product_id,$data) {
		$data['date_modified']=date('Y-m-d H:i:s',time());
		$sql="UPDATE ".DB_PREFIX."bahanbaku SET ";
		$i=1;
		foreach($data as $key => $value){
			if($key != 'categories' & $key != 'keyword'){
				if($i != 1){
			         $sql .=",";
				}
				if($key == 'name' | $key == 'description' | $key == 'meta_description' | $key == 'meta_keyword' | $key == 'tag'){
					$sql .=$key."= '".$this->db->escape($value)."'";
				}else if($key == 'image'){
					$sql .=$key."= '".$this->db->escape(html_entity_decode($value, ENT_QUOTES, 'UTF-8'))."'";
				}
			 else{
					$sql .=$key."= '".$value."'";
				}
			}
			$i++;
		}
		$sql .= " WHERE id='".$product_id."'";

		$this->db->query($sql);



	}

	public function getProducts($data = array()) {
		$sql="SELECT p.id,name,quantity,date_added,p.satuan,level FROM ".DB_PREFIX."bahanbaku p";
		$sql.=" WHERE hapus=0 ";
		if (!empty($data['filter_name'])) {
	    $sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
	  }
		$sql .= " ORDER BY name";
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
	public function getTotalProducts($data=array()){
		$sql="SELECT p.id,name,quantity FROM ".DB_PREFIX."bahanbaku p";
		$sql.=" WHERE hapus=0 ";
		if (!empty($data['filter_name'])) {
	    $sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
	  }
	    $query = $this->db->query($sql);

	    return $query->num_rows;
	}

	public function getProduct($product_id) {
	  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bahanbaku p WHERE p.id = '" . (int)$product_id . "' AND hapus=0");

	  return $query->row;
	}


	public function deleteProduct($product_id){
		$this->db->query("UPDATE ".DB_PREFIX."bahanbaku SET hapus=1 WHERE id='".$product_id."' ");
	}

	public function getLastProductPrice($product_id,$customer_id){
		$sql="SELECT * FROM price_bahanbaku_history WHERE id='".$product_id."' AND customer_id='".$customer_id."' ORDER BY date_added DESC LIMIT 1";
		$res=$this->db->query($sql);
		return $res->row;
	}

	public function addKonversi($product_id,$datas){
		foreach($datas as $data){

			$cek=$this->cekKonversi($product_id,$data['satuan']);
			if(empty($cek)){
				$spec=array(
					'product_id' => $product_id,
					'satuan'	=> $data['satuan'],
					'nilai'	=> $data['nilai']
				);
				$this->db->insert('konversi_bahanbaku',$spec);
				//$this->db->query("INSERT INTO ".DB_PREFIX."product_special SET product_id='".$product_id."',priority='".$data['priority']."',customer_group_id='".$data['customer_group_id']."',date_start='".$data['date_start']."' ,date_end='".$data['date_end']."',price='".$data['price']."' ");
			}
		}
	}

	public function deleteKonversi($product_special_id){
		$this->db->query("DELETE FROM ".DB_PREFIX."konversi_bahanbaku WHERE id='".$product_special_id."' ");
	}

	public function cekKonversi($product_id,$satuan){
		$sql = "SELECT * FROM ".DB_PREFIX."konversi_bahanbaku WHERE product_id='".$product_id."' AND satuan='".$satuan."'";

		$res=$this->db->query($sql);

		return $res->row;
	}

	public function getKonversi($product_id) {
	  $query = $this->db->query("SELECT kb.*,s.name as name FROM " . DB_PREFIX . "konversi_bahanbaku kb LEFT JOIN satuan s ON(kb.satuan=s.id) WHERE product_id = '" . (int)$product_id . "' ORDER BY satuan");

	  return $query->rows;
	}



}
?>
