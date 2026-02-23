<?php
class ModelCatalogProduct extends Model {
	// baru 20 Januari 2020
	public function sumsogudang($product_id,$gudang_id){
		$sql ="SELECT sum(sop.quantity) jumlah_so FROM sales_order_product sop LEFT JOIN sales_order so ON (so.id=sop.sales_order_id) WHERE sop.product_id = '$product_id' and sop.status_pengiriman=1 and so.status<>4 and so.gudang_id='$gudang_id' ";
		$d = $this->db->query($sql);
		return $d->row['jumlah_so'];
	}
	// end baru
	// baru 18 November 2019
	public function getgudang($customer_id){
		$d = $this->db->query("SELECT * FROM gudang WHERE gudang_id='$customer_id' ");
		return $d->row;
	}
	
	public function getcustomer($customer_id){
		$d = $this->db->query("SELECT * FROM customer WHERE customer_id='$customer_id' ");
		return $d->row;
	}
	public function getso($product_id){
		$sql = "SELECT sales_order.customer_id,sales_order.gudang_id,sales_order.no_so,sales_order_product.quantity FROM sales_order JOIN sales_order_product ON(sales_order_product.sales_order_id=sales_order.id) WHERE sales_order_product.status_pengiriman=1 AND sales_order_product.product_id='$product_id' order by sales_order_product.quantity DESC";
		$d = $this->db->query($sql);
		return $d->rows;
	}
	// end baru
	// baru 15 November 2019
	public function sumso($product_id){
		$sql ="SELECT sum(sop.quantity) jumlah_so FROM sales_order_product sop LEFT JOIN sales_order so ON (so.id=sop.sales_order_id) WHERE sop.product_id = '$product_id' and sop.status_pengiriman=1 and so.status<>4";
		$d = $this->db->query($sql);
		return $d->row['jumlah_so'];
	}
	// end baru
	public function updateQty($product_id,$qty,$jenis){
		$data=$this->getProduct($product_id);

		//update qty
		/*if($jenis == 1){
			$qtyf=$data['quantity'] + $qty;
		}
		if($jenis == 2){
			$qtyf=$data['quantity'] - $qty;
		}
	  $this->db->query("UPDATE ".DB_PREFIX."product SET quantity='".$qtyf."' WHERE product_id='".$product_id."'");
		return $qtyf;*/
		//total
		$total=$this->db->query("SELECT COALESCE(SUM(quantity),0) as total FROM product_gudang WHERE product_id='".$product_id."'");
		$this->db->query("UPDATE ".DB_PREFIX."product SET quantity='".$total->row['total']."' WHERE product_id='".$product_id."'");
	}

	public function stokOpname($data){
		$curqty=$this->getProduct($data['product_id']);

		if($data['qty'] <= $curqty['quantity']){
			$cur=$curqty['quantity'] - $data['qty'];
			$stokmasuk=0;
			$stokkeluar=$cur;
			$this->updateQty($data['product_id'],$cur,2);
		}
		if($data['qty'] > $curqty['quantity']){
			$cur=$data['qty']-$curqty['quantity'];
			$stokmasuk=$cur;
			$stokkeluar=0;
			$this->updateQty($data['product_id'],$cur,1);
		}


		$this->load->model('gudang/kartustok');
		$kartustok=array(
			'product_id'	=> $data['product_id'],
			'product_name'	=> $curqty['name'],
			'tgl'	=> date('Y-m-d h:i:s',time()),
			'stokkeluar'	=> $stokkeluar,
			'stokmasuk'	=> $stokmasuk,
			'ket'	=> 'Stok opname oleh '.$this->user->getName(),
			'saldo'	=> $data['qty'],
			'quantityawal'	=> $curqty['quantity'],
			'invoice'	=> '',
			//'gudang_id'	=> $data['gudang_id'],
			'type'	=> 3
		);

		$this->model_gudang_kartustok->addKartuStok($kartustok);



	}

	public function updateNetCost($product_id,$net_cost){
		$this->db->query("UPDATE ".DB_PREFIX."product SET net_cost='".$net_cost."' WHERE product_id='".$product_id."'");
		$hist=array(
			'product_id'=>$product_id,
			'net_cost'=>$net_cost,
			'date_added'	=> date('Y-m-d h:i:s',time())
		);
		$this->db->insert('netcost_history',$hist);
	}
	public function addStokAwal($data){
		$curqty=$this->getProduct($data['product_id']);
		$this->updateQty($data['product_id'],$data['qty'],1);
		$this->load->model('gudang/kartustok');
		if(empty($data['net_cost'])){
			$data['net_cost'] = 0;
		}

		$kartustok=array(
			'product_id'	=> $data['product_id'],
			'product_name'	=> $curqty['name'],
			'tgl'	=> date('Y-m-d h:i:s',time()),
			'stokkeluar'	=> 0,
			'stokmasuk'	=> $data['qty'],
			'ket'	=> $this->db->escape('Set stok awal produk'),
			'saldo'	=> $data['qty'],
			'quantityawal'	=> 0,
			'invoice'	=> '',
			//'gudang_id'	=> $data['gudang_id'],
			'type'	=> 4
		);

		$this->model_gudang_kartustok->addKartuStok($kartustok);
		$this->updateNetCost($data['product_id'],$data['net_cost']);

		$act=array(
      'activity'	=> 'Input master produk dagang ID '.$product_id.' Nama '.$data['name'],
      'menu'	=> 'Daftar Persediaan Produk Dagang'
    );
    $this->user->addUserActivity($act);
		/*
		Type:
		1. Pembelian
		2. penjualan
		3. Stok Opname
		4. Set Stok Awal
		5. Produk Cacat
		6. Produk Hilang
		*/

	}
	public function addProduct($data) {
		/*if(!isset($data['net_cost'])){
			$data['net_cost'] =0;
		}

		if(empty($data['price'])){
			$data['price'] = 0;
		}
		*/
		$data['date_added']=date('Y-m-d H:i:s',time());
		$data['date_modified']=date('Y-m-d H:i:s',time());
		$data['hapus']=0;
		$column='';
		$vals='';

		$i=1;
		foreach($data as $key => $value){
			if($key != 'categories' & $key != 'keyword'){
				if($i != 1){
			         $column .=",";
							 $vals .=",";
				}
				$column .= $key;
				if($key == 'name'){
					//$sql .=$key."= '".$this->db->escape($value)."'";

					$vals .= "'".$this->db->escape($value)."'";
				}else if($key == 'image'){
					//$sql .=$key."= '".$this->db->escape(html_entity_decode($value, ENT_QUOTES, 'UTF-8'))."'";
					$vals .= "'".$this->db->escape(html_entity_decode($value, ENT_QUOTES, 'UTF-8'))."'";
				}
			 else{
					//$sql .=$key."= '".$value."'";
					$vals .= "'".$value."'";
				}
			}
			$i++;
		}
		$sql="INSERT INTO ".DB_PREFIX."product(".$column.") values(".$vals.")";

		$this->db->query($sql);



		$product_id = $this->db->getLastId();
		if(!empty($data['barcode'])){
				$barcode=$data['barcode'];
		}
		else{
			$t=date('Y',time());
			$b=date('m',time());
			$barcode=$t.$b.$product_id;
		}

		$this->db->query("UPDATE ".DB_PREFIX."product SET barcode='".$barcode."' WHERE product_id='".$product_id."' ");
		//$this->updateNetCost($product_id,$data['net_cost']);

		/*$act=array(
      'activity'	=> 'Input master produk dagang ID '.$product_id.' Nama '.$data['name'],
      'menu'	=> 'Daftar Persediaan Produk Dagang'
    );
    $this->user->addUserActivity($act);
		*/
	}

	public function editProduct($product_id,$data) {
		$data['date_modified']=date('Y-m-d H:i:s',time());
		$sql="UPDATE ".DB_PREFIX."product SET ";
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
		$sql .= " WHERE product_id='".$product_id."'";

		$this->db->query($sql);



	}

	public function getProducts($data = array()) {
		$sql="SELECT p.product_id,p.name,barcode,quantity,p.status,p.satuan FROM ".DB_PREFIX."product p";
		if (!empty($data['filter_category_id'])) {
      $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category c ON (p.product_id = c.product_id)";
    }

		$sql.=" WHERE hapus=0 ";
		/*if(!empty($data['filter_qty']) ){
				if($data['filter_qty'] == 1){
						$sql .=" AND pg.qty > 0";
				}
				if($data['filter_qty'] == 2){
						$sql .=" AND pg.qty <= 0";
				}
		}*/
		if (!empty($data['filter_name'])) {
	    $sql .= " AND lower(p.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
	  }
		if(isset($data['filter_status'])){
		if ($data['filter_status'] != null) {
	    $sql .= " AND p.status='".$data['filter_status']."'";
	  }
		}
		if (!empty($data['filter_category_id'])) {
	    $sql .= " AND c.category_id='".$data['filter_category_id']."'";
	  }
		if(isset($data['jenistabung'])){
		if ($data['jenistabung'] != null) {
	    $sql .= " AND jenistabung='".$data['jenistabung']."'";
	  }
		}


			if(isset($data['filter_urutkan'])){
					if($data['filter_urutkan'] == 1){
							$sql .= " ORDER BY product_id DESC";
					}
					if($data['filter_urutkan'] == 2){
							$sql .= " ORDER BY product_id ASC";
					}
					if($data['filter_urutkan'] == 3){
							$sql .= " ORDER BY p.name ASC";
					}
					if($data['filter_urutkan'] == 4){
							$sql .= " ORDER BY p.name DESC";
					}
					if($data['filter_urutkan'] == 5){
							$sql .= " ORDER BY quantity DESC";
					}

					if($data['filter_urutkan'] == 6){
							$sql .= " ORDER BY quantity ASC";
					}
			}else{
					$sql .= " ORDER BY name";
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
	public function getTotalProducts($data=array()){
		$sql="SELECT p.product_id,p.name,barcode,quantity,p.status FROM ".DB_PREFIX."product p";
		if (!empty($data['filter_category_id'])) {
      $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category c ON (p.product_id = c.product_id)";
    }

		$sql.=" WHERE hapus=0 ";
		/*if(!empty($data['filter_qty']) ){
				if($data['filter_qty'] == 1){
						$sql .=" AND pg.qty > 0";
				}
				if($data['filter_qty'] == 2){
						$sql .=" AND pg.qty <= 0";
				}
		}*/
		if (!empty($data['filter_name'])) {
	    $sql .= " AND lower(p.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
	  }
		if ($data['filter_status'] != null) {
	    $sql .= " AND p.status='".$data['filter_status']."'";
	  }
		if (!empty($data['filter_category_id'])) {
	    $sql .= " AND c.category_id='".$data['filter_category_id']."'";
	  }
		if(isset($data['jenistabung'])){
		if ($data['jenistabung'] != null) {
	    $sql .= " AND jenistabung='".$data['jenistabung']."'";
	  }
		}

	    $query = $this->db->query($sql);

	    return $query->num_rows;
	}

	public function getProduct($product_id) {
	  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p WHERE p.product_id = '" . (int)$product_id . "' AND hapus=0");

	  return $query->row;
	}

	public function getProductGudang($gudang_id,$product_id) {
	  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p WHERE p.product_id = '" . (int)$product_id . "' AND hapus=0");

	  return $query->row;
	}


	public function addProductSpecial($product_id,$datas){
		foreach($datas as $data){

			$cek=$this->cekProductSpecial($product_id,$data['customer_group_id']);
			if(!empty($cek)){
				if($cek['hapus'] == 1){
					$this->db->query("UPDATE ".DB_PREFIX."product_special SET hapus=0,price='".$data['price']."' WHERE product_id='".$product_id."' AND customer_group_id='".$data['customer_group_id']."'");
				}
			}else{
				$spec=array(
					'product_id' => $product_id,
					'customer_group_id'	=> $data['customer_group_id'],
					'hapus'	=> 0,
					'price'	=> $data['price']
				);
				$this->db->insert('product_special',$spec);
				//$this->db->query("INSERT INTO ".DB_PREFIX."product_special SET product_id='".$product_id."',priority='".$data['priority']."',customer_group_id='".$data['customer_group_id']."',date_start='".$data['date_start']."' ,date_end='".$data['date_end']."',price='".$data['price']."' ");
			}
		}
	}

	public function deleteProductSpecial($product_special_id){
		$this->db->query("UPDATE ".DB_PREFIX."product_special SET hapus=1 WHERE product_special_id='".$product_special_id."' ");
	}

	public function cekProductSpecial($product_id,$customer_group_id){
		$sql = "SELECT * FROM ".DB_PREFIX."product_special WHERE product_id='".$product_id."' AND customer_group_id='".$customer_group_id."'";

		$res=$this->db->query($sql);

		return $res->row;
	}

	public function getProductSpecials($product_id) {
	  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND hapus=0 ORDER BY customer_group_id");

	  return $query->rows;
	}

	public function historyNetcosts($column=array(),$join=array(),$where=array(),$order,$limit,$offset){
		return $this->db->alljoin('netcost_history',$column,$join,$where,$order,$limit,$offset);
	}

	public function totalNetcosts($where){
		return $this->db->countAll('netcost_history',$where);
	}

	public function getProductSpecialsActiveDefault($product_id,$customer_group_id=1) {
		/*$where=array(
			'product_id' => $product_id,
			'hapus'	=> array('<',1),
			'date_end'	=>array('>=',date('Y-m-d',time()))
		);*/
	  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND hapus=0 AND customer_group_id='".$customer_group_id."'");
		if(!empty($query->row)){
	  	return $query->row['price'];
		}else{
			return false;
		}
	}
	public function deleteProduct($product_id){
		$this->db->query("UPDATE product SET hapus=1 WHERE product_id='".$product_id."'");
	}

	public function editKategori($product_id,$data) {
		$this->db->query("DELETE FROM ".DB_PREFIX."product_to_category WHERE product_id=" . (int)$product_id . " ");
		foreach ($data as $category_id) {
			$pc = array(
				'product_id'	=> $product_id,
				'category_id'	=> $category_id
			);
			$this->db->insert('product_to_category',$pc);
			}
	}

	public function getProductCategories($product_id) {
	  $product_category_data = array();

	  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

	  foreach ($query->rows as $result) {
	    $product_category_data[] = $result['category_id'];
	  }

	  return $product_category_data;
	}
	public function addBahanbaku($product_id,$datas){
		foreach($datas as $data){

			$cek=$this->cekBahanbaku($product_id,$data['bahanbaku_id']);
			if(empty($cek)){
				$spec=array(
					'product_id' => $product_id,
					'bahanbaku_id'	=> $data['bahanbaku_id'],
					'jumlah'	=> $data['jumlah']
				);
				$this->db->insert('bahanbaku_product',$spec);
				//$this->db->query("INSERT INTO ".DB_PREFIX."product_special SET product_id='".$product_id."',priority='".$data['priority']."',customer_group_id='".$data['customer_group_id']."',date_start='".$data['date_start']."' ,date_end='".$data['date_end']."',price='".$data['price']."' ");
			}
		}
	}

	public function deleteBahanbaku($product_special_id){
		$this->db->query("DELETE FROM ".DB_PREFIX."bahanbaku_product WHERE id='".$product_special_id."' ");
	}

	public function cekBahanbaku($product_id,$satuan){
		$sql = "SELECT * FROM ".DB_PREFIX."bahanbaku_product WHERE product_id='".$product_id."' AND bahanbaku_id='".$satuan."'";

		$res=$this->db->query($sql);

		return $res->row;
	}

	public function getBahanbaku($product_id) {
	  $query = $this->db->query("SELECT kb.*,s.name,s.quantity,s.satuan,s.level FROM " . DB_PREFIX . "bahanbaku_product kb LEFT JOIN bahanbaku s ON(kb.bahanbaku_id=s.id) WHERE product_id = '" . (int)$product_id . "' ORDER BY bahanbaku_id");

	  return $query->rows;
	}
}
?>
