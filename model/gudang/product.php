<?php
class ModelGudangProduct extends Model {
	// baru 24 Agustus 2020
	public function getprodukbelumsetharga($gudang_id,$date){
		$hasil=array();
		//$sht="SELECT product_id FROM harga_terendah WHERE gudang_id='$gudang_id' AND date<='2020-08-24' and harga_terendah>0";
		$sht="SELECT product_id FROM harga_terendah WHERE gudang_id='$gudang_id' AND date<='$date' and hapus=0";
		$ht=$this->db->query($sht);
		$data=$ht->rows;
		foreach($data as $d){
			$hasil[]=$d['product_id'];
		}
		
		if(!empty($hasil)){
			$id=$id=implode(",",$hasil);
		}else{
			$id=0;
		}
		$sql="SELECT  pg.product_id,p.name from product_gudang pg ";
		$sql.="left join product p ON (p.product_id=pg.product_id) where pg.gudang_id='$gudang_id' and pg.product_id not in($id) ";
		$sql.=" ORDER BY p.name";
		$d=$this->db->query($sql);
		return $d->rows;
		//return $sql;
	}
	// end baru
	// baru 6 agustus 2020
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
		//return $sql;
	}

	// end baru
	// baru 17 Juli 2020
	public function gettglterakhir($gudang_id){
		$sql="SELECT date FROM harga_terendah WHERE gudang_id='$gudang_id' GROUP BY date ORDER BY date DESC LIMIT 1 ";
		$query=$this->db->query($sql);
		return isset($query->row['date']) ? $query->row['date'] : date('Y-m-d');
	}

	//end baru
	// baru 29 Mei 2020
	public function getdaftarbarang($gudang_id){
		$sql="SELECT product_gudang.*, product.name FROM product_gudang LEFT JOIN product ON(product.product_id=product_gudang.product_id) WHERE product_gudang.gudang_id='$gudang_id' AND product_gudang.status=1 ORDER BY product.name ";
		$query=$this->db->query($sql);
		return $query->rows;
	}

	public function gethargarendah($product_id,$gudang_id,$date){
		$sql="SELECT * FROM harga_terendah WHERE hapus=0 AND date <='".$date."' AND product_id='".$product_id."' and gudang_id='".$gudang_id."' ORDER BY date DESC LIMIT 1 ";
		$d= $this->db->query($sql);
		return $d->row;
	}

	// end baru
	// baru 18 Maret 2020
	public function gettglso($no_so){
		$d=$this->db->query("SELECT date_added FROM sales_order WHERE id='$no_so' ");
		$date=$d->row['date_added'];
		return date('Y-m-d',strtotime($date));
	}
	// baru 13 maret 2020
	public function getpoinproduct($date,$product_id,$gudang_id){
		//$sql="SELECT * FROM harga_terendah WHERE hapus=0 AND product_id='".$product_id."' and gudang_id='".$gudang_id."'";
		$sql="SELECT * FROM harga_terendah WHERE hapus=0 AND date <='".$date."' AND product_id='".$product_id."' and gudang_id='".$gudang_id."' ORDER BY date DESC LIMIT 1 ";
		$d= $this->db->query($sql);
		return $d->row['poin'];
	}
	// baru 7 Maret 2020
	public function gethargaterendahdetailkomisi($date,$product_id,$gudang_id){
		$sql="SELECT * FROM harga_terendah WHERE hapus=0 AND date <='".$date."' AND product_id='".$product_id."' and gudang_id='".$gudang_id."' ORDER BY date DESC LIMIT 1 ";
		$d= $this->db->query($sql);
		return $d->row['harga_terendah'];
	}
	public function getperiodeharga($gudang_id){
		$sql="SELECT DISTINCT date FROM harga_terendah WHERE gudang_id='$gudang_id' and hapus=0 ORDER BY date DESC ";
		$d= $this->db->query($sql);
		return $d->rows;
	}

	public function deleteperiode($gudang_id, $date){
		$this->db->query("UPDATE harga_terendah SET hapus = 1 WHERE gudang_id = '" . (int)$gudang_id . "' AND date = '" . $this->db->escape($date) . "'");
	}

	public function deletehargaterendah($id) {
		$this->db->query("UPDATE harga_terendah SET hapus = 1 WHERE id = '" . (int)$id . "'");
	}

	public function deleteproductgudang($product_id, $gudang_id) {
		$this->db->query("UPDATE product_gudang SET status = 0 WHERE product_id = '" . (int)$product_id . "' AND gudang_id = '" . (int)$gudang_id . "'");
	}
	// baru 5 Maret 2020
	public function daftarhargaterendah($data){
		$hasil=array();
		$products=array();
		//$sql="SELECT * FROM product_gudang WHERE status=1 ";
		$sql="SELECT product_gudang.*, product.name FROM product_gudang LEFT JOIN product ON(product.product_id=product_gudang.product_id) WHERE product_gudang.status=1 ORDER BY product.name ";
		$d= $this->db->query($sql);
		$products = $d->rows;
		foreach($products as $p){
			$sql2 =" SELECT * FROM harga_terendah WHERE hapus=0 and product_id='".$p['product_id']."' and gudang_id='".$p['gudang_id']."' ";
			if($data['filter_name']!=null){
				$sql2 .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			}
			if($data['date']){
				$sql2 .=" AND date <='".$data['date']."' ";
			}
			if($data['filter_gudang_id']!=null){
				$sql2 .=" AND gudang_id='".$data['filter_gudang_id']."' ";
			}
			$sql2 .="  ORDER BY date DESC LIMIT 1 ";
			$d2 = $this->db->query($sql2);
			$s2 = $d2->rows;
			foreach($s2 as $s){
				if($s['product_id']==$p['product_id']){
					$hasil[]=array(
						'id' => $s['id'],
						'product_id'=>$p['product_id'],
						'name' => $p['name'],
						'gudang_id' => $p['gudang_id'],
						'harga_terendah'=>$s['harga_terendah'],
						'poin'=>$s['poin'],
						'date' => $s['date']
					);
				}
			}
		}
		return $hasil;
	}
	
	// baru 3 Maret 2020
	public function gethargaterendahdetail($product_id,$gudang_id){
		$sql="SELECT * FROM harga_terendah WHERE hapus=0 AND product_id='".$product_id."' and gudang_id='".$gudang_id."' ORDER BY date DESC LIMIT 1 ";
		$d= $this->db->query($sql);
		return $d->row['harga_terendah'];
	}
	// baru 2 Maret 2020
	public function addhargaterendah($data){
		//$cek = $this->db->query("DELETE FROM harga_terendah WHERE product_id='".$data['product_id']."' and gudang_id='".$data['gudang_id']."' ");
		foreach($data['product_special'] as $p ){
			$harga=array(
				'product_id'=>$data['product_id'],
				'gudang_id'=>$data['gudang_id'],
				'harga_terendah' => $p['harga_terendah'],
				'date'=>$p['date'],
				'name'=>$data['name'],
				'poin'=>$p['poin'],
			);
			$this->db->insert('harga_terendah',$harga);
		}
	}

	public function gethargaterendah($data){
		$sql="SELECT * FROM harga_terendah WHERE hapus=0 AND product_id='".$data['product_id']."' and gudang_id='".$data['gudang_id']."' ORDER BY date ASC";
		$d= $this->db->query($sql);
		return $d->rows;
	}
	// baru 25 September 2019
	public function getsaldokartustok($data,$product_id,$gudang_id){
		$sql =" SELECT tgl, saldo from kartustok_produk WHERE product_id='$product_id' and gudang_id='$gudang_id' ";
		if(!empty($data['tanggal'])){
			$sql .= " AND DATE(tgl) <='".$data['tanggal']."' ";
		}else{
			$sql .=" AND DATE(tgl) <='".date('Y-m-d')."'";
		}
		$sql .=" ORDER BY kartustok_id DESC LIMIT 1";
		//$sql .=" ORDER BY tgl ASC LIMIT 1";
		$d = $this->db->query($sql);
		return $d->row;
		//return $sql;
	}
	
	// end baru
	public function getProductGudang($product_gudang_id){
		$sql="SELECT pg.product_gudang_id,p.product_id,p.name,pg.gudang_id,pg.quantity,pg.status,pg.rak,nama,pg.link_non_web FROM ".DB_PREFIX."product p LEFT JOIN ".DB_PREFIX."product_gudang pg ON(p.product_id=pg.product_id) LEFT JOIN ".DB_PREFIX."gudang g ON(pg.gudang_id=g.gudang_id) ";
		$sql.=" WHERE p.hapus=0 AND pg.product_gudang_id='".$product_gudang_id."'";

		$res=$this->db->query($sql);
		return $res->row;
	}
	public function getProduct($product_id,$gudang_id){
		$sql="SELECT pg.net_cost,pg.product_gudang_id,p.product_id,p.name,pg.gudang_id,pg.quantity,pg.status,nama,pg.net_cost,p.jenistabung,pg.premijual,pg.premikirim,pg.premiambil,pg.premibongkar FROM ".DB_PREFIX."product p LEFT JOIN ".DB_PREFIX."product_gudang pg ON(p.product_id=pg.product_id) LEFT JOIN ".DB_PREFIX."gudang g ON(pg.gudang_id=g.gudang_id) ";
		$sql.=" WHERE p.hapus=0 AND pg.product_id='".$product_id."' AND pg.gudang_id='".$gudang_id."'";

		$res=$this->db->query($sql);
		return $res->row;
	}

	public function getProductPrice($product_id,$gudang_id,$customer_group_id){
		$sql="SELECT * FROM product_special WHERE product_id='".$product_id."' AND gudang_id='".$gudang_id."' AND customer_group_id='".$customer_group_id."'";
		$res=$this->db->query($sql);
		return $res->row;
	}

	public function getLastProductPrice($product_id,$gudang_id,$customer_id){
		$sql="SELECT * FROM price_history WHERE product_id='".$product_id."' AND gudang_id='".$gudang_id."' AND customer_id='".$customer_id."' ORDER BY date_added DESC LIMIT 1";
		$res=$this->db->query($sql);
		return $res->row;
	}

	public function getProducts($data=array(),$permission=FALSE){
		$sql="SELECT pg.product_gudang_id,p.product_id,p.name,pg.gudang_id,pg.quantity,pg.status,nama,pg.net_cost FROM ".DB_PREFIX."product p JOIN ".DB_PREFIX."product_gudang pg ON(p.product_id=pg.product_id) LEFT JOIN ".DB_PREFIX."gudang g ON(pg.gudang_id=g.gudang_id) ";
		if (!empty($data['filter_category_id'])) {
      $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
    }
		/*if (!empty($data['filter_option'])) {
      $sql .= " LEFT JOIN " . DB_PREFIX . "product_option_gudang poc ON (p.product_id = poc.product_id) LEFT JOIN ".DB_PREFIX."product_option po ON(poc.product_option_id=po.product_option_id) ";
			$sql .= " LEFT JOIN ".DB_PREFIX."product_options ps ON(po.product_options_id=ps.product_options_id) ";
    }*/

		$sql.=" WHERE p.hapus=0 ";
		if(!empty($data['filter_qty']) ){
				if($data['filter_qty'] == 1){
						$sql .=" AND pg.quantity > 0";
				}
				if($data['filter_qty'] == 2){
						$sql .=" AND pg.quantity <= 0";
				}
		}

		if (!empty($data['filter_gudang_id'])) {
	        $sql .= " AND pg.gudang_id='".$data['filter_gudang_id']."' ";
	      }
    else{
      if($permission){
        $sql .=" AND pg.gudang_id IN(SELECT gudang_id FROM ".DB_PREFIX."user_gudang WHERE user_id='".$this->user->getId()."' )";
      }
		}

		/*if (!empty($data['filter_option'])) {
      $sql .= " AND ps.product_options_id = '".$data['filter_option']."'";
    }
*/
		if (!empty($data['filter_name'])) {
	    $sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
	  }
		if(isset($data['filter_status'])){
			if ($data['filter_status'] != null) {
		    $sql .= " AND status='".$data['filter_status']."'";
		  }
		}
		if (!empty($data['filter_category_id'])) {
	    if (!empty($data['filter_sub_category'])) {
	      $implode_data = array();

	      $implode_data[] = "p2c.category_id = '" . (int)$data['filter_category_id'] . "'";

	      $this->load->model('catalog/category');

	      $categories = $this->model_catalog_category->getCategories($data['filter_category_id']);

	      foreach ($categories as $category) {
	        $implode_data[] = "p2c.category_id = '" . (int)$category['category_id'] . "'";
	      }

	      $sql .= " AND (" . implode(' OR ', $implode_data) . ")";
	    } else {
	      $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
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
							$sql .= " ORDER BY name ASC";
					}
					if($data['filter_urutkan'] == 4){
							$sql .= " ORDER BY name DESC";
					}
					if($data['filter_urutkan'] == 5){
							$sql .= " ORDER BY pg.quantity DESC";
					}

					if($data['filter_urutkan'] == 6){
							$sql .= " ORDER BY pg.quantity ASC";
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
	public function getTotalProducts($data=array(),$permission=FALSE){
		$sql="SELECT * FROM ".DB_PREFIX."product p JOIN ".DB_PREFIX."product_gudang pg ON(p.product_id=pg.product_id) ";
		if (!empty($data['filter_category_id'])) {
      $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
    }
		/*if (!empty($data['filter_option'])) {
      $sql .= " LEFT JOIN " . DB_PREFIX . "product_option_gudang poc ON (p.product_id = poc.product_id) LEFT JOIN ".DB_PREFIX."product_option po ON(poc.product_option_id=po.product_option_id) ";
			$sql .= " LEFT JOIN ".DB_PREFIX."product_options ps ON(po.product_options_id=ps.product_options_id) ";
    }*/

		$sql.=" WHERE p.hapus=0 ";
		if(!empty($data['filter_qty']) ){
				if($data['filter_qty'] == 1){
						$sql .=" AND pg.quantity > 0";
				}
				if($data['filter_qty'] == 2){
						$sql .=" AND pg.quantity <= 0";
				}
		}

		if (!empty($data['filter_gudang_id'])) {
	        $sql .= " AND pg.gudang_id='".$data['filter_gudang_id']."' ";
	      }
    else{
      if($permission){
        $sql .=" AND pg.gudang_id IN(SELECT gudang_id FROM ".DB_PREFIX."user_gudang WHERE user_id='".$this->user->getId()."' )";
      }
		}

		/*if (!empty($data['filter_option'])) {
      $sql .= " AND ps.product_options_id = '".$data['filter_option']."'";
    }*/

		if (!empty($data['filter_name'])) {
	    $sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
	  }
		if ($data['filter_status'] != null) {
	    $sql .= " AND status='".$data['filter_status']."'";
	  }
		if (!empty($data['filter_category_id'])) {
	    if (!empty($data['filter_sub_category'])) {
	      $implode_data = array();

	      $implode_data[] = "p2c.category_id = '" . (int)$data['filter_category_id'] . "'";

	      $this->load->model('catalog/category');

	      $categories = $this->model_catalog_category->getCategories($data['filter_category_id']);

	      foreach ($categories as $category) {
	        $implode_data[] = "p2c.category_id = '" . (int)$category['category_id'] . "'";
	      }

	      $sql .= " AND (" . implode(' OR ', $implode_data) . ")";
	    } else {
	      $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
	    }
	  }
	    $query = $this->db->query($sql);

	    return $query->num_rows;
	}

	public function getOptionGudang($product_id,$gudang_id,$product_option_id=0){
		$sql="SELECT p.product_option_id,name,p.quantity,po.product_options_id FROM ".DB_PREFIX."product_option_gudang p LEFT JOIN ".DB_PREFIX."product_option po ON(p.product_option_id=po.product_option_id) ";
		$sql .= " LEFT JOIN ".DB_PREFIX."product_options ps ON(po.product_options_id=ps.product_options_id) ";
		$sql .=" WHERE p.product_id='".$product_id."' AND p.gudang_id='".$gudang_id."' ";
		if($product_option_id > 0){
			$sql .=" AND ps.product_options_id = '".$product_option_id."'";
		}

		$res=$this->db->query($sql);
		$total=$res->num_rows;
		if($product_option_id == 0){
			return $res->rows;
		}else{
			return $res->row;
		}
	}

	public function getOptionProduct($product_id,$gudang_id,$product_option_id){
		$sql="SELECT p.product_option_id,name,p.quantity,po.product_options_id FROM ".DB_PREFIX."product_option_gudang p LEFT JOIN ".DB_PREFIX."product_option po ON(p.product_option_id=po.product_option_id) ";
		$sql .= " LEFT JOIN ".DB_PREFIX."product_options ps ON(po.product_options_id=ps.product_options_id) ";
		$sql .=" WHERE p.product_id='".$product_id."' AND p.gudang_id='".$gudang_id."' ";
		if($product_option_id > 0){
			$sql .=" AND po.product_option_id = '".$product_option_id."'";
		}

		$res=$this->db->query($sql);
		return $res->row;
	}

	public function getOptionInUse(){
		$res=$this->db->query("SELECT DISTINCT ps.product_options_id,name FROM ".DB_PREFIX."product_option_gudang pog JOIN ".DB_PREFIX."product_option po ON(pog.product_option_id=po.product_option_id) JOIN ".DB_PREFIX."product_options ps ON(po.product_options_id=ps.product_options_id)");
		return $res->rows;
	}

	public function getStatus($product_id){
	  $res=$this->db->query("SELECT status FROM ".DB_PREFIX."product WHERE product_id='".$product_id."' ");
	  return $res->row['status'];
	}

	public function getProductGudangT($product_id,$gudang_id){
		$res=$this->db->query("SELECT * FROM ".DB_PREFIX."product_gudang WHERE product_id='".$product_id."' AND gudang_id='".$gudang_id."' ");
		return $res->row;
	}

	public function UpdateQty($product_id,$gudang_id,$quantity,$jenis){
			//get Current quantity
			$cur=$this->getProductGudangT($product_id,$gudang_id);

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

			$this->db->query("UPDATE ".DB_PREFIX."product_gudang SET quantity='".$curquantity."' WHERE product_id='".$product_id."' AND gudang_id='".$gudang_id."'");

			//get global quantity
			$this->load->model('catalog/product');

			//total
			$tot=$this->db->query("SELECT COALESCE(SUM(quantity),0) as total FROM ".DB_PREFIX."product_gudang WHERE product_id='".$product_id."' ");

			$this->model_catalog_product->updateQty($product_id,$tot->row['total'],$jenis);
			return $curquantity;
	}

	public function updateQtyOption($product_option_id,$product_id,$gudang_id,$quantity,$jenis){
		//cur qty
		$cur=$this->getOptionProduct($product_id,$gudang_id,$product_option_id);
		if($jenis == 1){
			$curquantity = $cur['quantity'] + $quantity;
		}

		if($jenis == 2){
			$curquantity = $cur['quantity'] - $quantity;
		}

		$this->db->query("UPDATE ".DB_PREFIX."product_option_gudang SET quantity='".$curquantity."' WHERE product_option_id='".$product_option_id."' AND gudang_id='".$gudang_id."'");

		//sum global
		$sumg=$this->db->query("SELECT SUM(quantity) as total FROM ".DB_PREFIX."product_option_gudang WHERE product_id='".$product_id."' AND gudang_id='".$gudang_id."'");
		$this->db->query("UPDATE ".DB_PREFIX."product_gudang SET quantity='".$sumg->row['total']."' WHERE product_id='".$product_id."' AND gudang_id='".$gudang_id."' ");

		//global
		$gcur=$this->model_catalog_product->getProductOption($product_option_id);
		$tot=$this->db->query("SELECT SUM(quantity) as total FROM ".DB_PREFIX."product_option_gudang WHERE product_option_id='".$product_option_id."' ");

		$this->model_catalog_product->updateQtyOption($product_option_id,$tot->row['total']);

		//global total
		//total
		$tot=$this->db->query("SELECT SUM(quantity) as total FROM ".DB_PREFIX."product_gudang WHERE product_id='".$product_id."' ");
		$this->model_catalog_product->updateQty($product_id,$tot->row['total']);

		return $curquantity;

	}

	//input stok awal
	public function addStokAwal($data){
		$product=array(
			'gudang_id'	=> $data['gudang_id'],
			'product_id'	=> $data['product_id'],
			'quantity'	=> empty($data['qty'])?0:$data['qty'],
			'status'	=>1,
			'net_cost'	=> empty($data['net_cost'])?0:$data['net_cost'],
			'date_added'	=>date('Y-m-d H:i:s',time())
		);

		$this->db->insert("product_gudang",$product);

		$product_id=$data['product_id'];
		$this->load->model('catalog/product');
		$this->load->model('gudang/kartustok');

		//$tot=$this->db->query("SELECT SUM(quantity) as total FROM ".DB_PREFIX."product_gudang WHERE product_id='".$data['product_id']."' ");
		//$this->model_catalog_product->updateQty($product_id,$tot->row['total']);

		//$pro=$this->model_catalog_product->getProduct($data['product_id']);
		$up=$this->model_catalog_product->UpdateQty($data['product_id'],$data['qty'],1);


			$kartustok=array(
					'product_id'	=> $data['product_id'],
					'product_name'	=> $this->db->escape($data['product_name']),
					'product_option_value'	=> '',
					'tgl'	=> date('Y-m-d H:i:s'),
					'stokmasuk'	=> $data['qty'],
					'stokkeluar'	=> 0,
					'ket'	=> 'Stok awal produk',
					'saldo'	=> $data['qty'],
					'quantityawal'	=> 0,
					'invoice'	=> '',
					'gudang_id'	=> $data['gudang_id'],
					'type'	=>10
				);
			$this->model_gudang_kartustok->addKartustok($kartustok);
			$this->updateNetCost($data['product_id'],$data['gudang_id'],$data['net_cost']);

	}

	public function addOptionAwal($data){
		$product=array(
			'gudang_id'	=> $data['gudang_id'],
			'product_id'	=> $data['product_id'],
			'quantity'	=> $data['qty'],
			'status'	=>1,
			'product_option_id'	=> $data['product_option_id']
		);
		$this->db->insert("product_option_gudang",$product);


		$this->load->model('catalog/product');
		$this->load->model('gudang/kartustok');

		$desc=$this->model_catalog_product->getProductOption($data['product_option_id']);
		if($data['qty'] != 0){

			$kartustok=array(
				'product_id'	=> $data['product_id'],
				'product_option_name'	=> $this->db->escape($desc['name']),
				'product_option_id'	=> $data['product_option_id'],
				'tgl'	=> date('Y-m-d H:i:s'),
				'stokmasuk'	=> $data['qty'],
				'stokkeluar'	=> 0,
				'ket'	=> 'Stok awal produk',
				'saldo'	=> $data['qty'],
				'quantityawal'	=> 0,
				'invoice'	=> '',
				'gudang_id'	=> $data['gudang_id'],
				'type'	=> 10
			);

			$this->model_gudang_kartustok->addKartuStokOption($kartustok);
		}

		$this->model_catalog_product->UpdateQtyOption($data['product_option_id'],$data['qty']+$desc['quantity']);

	}

	public function editProduk($product_gudang_id,$data){
		$this->db->query("UPDATE ".DB_PREFIX."product_gudang SET rak='".$this->db->escape($data['rak'])."',link_non_web='".$this->db->escape($data['link_non_web'])."',date_modified=NOW() WHERE product_gudang_id='".$product_gudang_id."' ");
	}

	//product cacat
	public function getProdukCacat($data=array(),$permission=false){
      $sql="select pc.product_id,pc.product_option_id,p.name,SUM(pc.qty) as quantity, SUM(nilaibarang*pc.qty) as nilaibarang,pc.gudang_id,nama,pc.date_added from ".DB_PREFIX."product_cacat_gudang pc LEFT JOIN ".DB_PREFIX."product p ON(pc.product_id=p.product_id)  LEFT JOIN ".DB_PREFIX."gudang g ON(pc.gudang_id=g.gudang_id) ";
			if (!empty($data['filter_gudang_id'])) {
				$sql .= " WHERE pc.gudang_id= '" . $data['filter_gudang_id'] . "'";
			}
			else{
				if($permission){
					$sql .=" WHERE pc.gudang_id IN(SELECT gudang_id FROM ".DB_PREFIX."user_gudang WHERE user_id='".$this->user->getId()."' )";
				}
			}
      if (!empty($data['filter_date_start'])) {
				$sql .= " AND pc.date_added >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}

			if (!empty($data['filter_date_end'])) {
				$sql .= " AND pc.date_added <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
		    if (!empty($data['filter_product_id'])) {
				$sql .= " AND pc.product_id= '" . $data['filter_product_id'] . "'";
			}

		    $sql .= " GROUP BY pc.gudang_id,g.nama,p.name,pc.product_id,pc.product_option_id,pc.date_added,pc.nilaibarang";
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

	public function getTotalProdukCacat($data=array(),$permission=false){
      $sql="select pc.product_id,SUM(pc.qty) as quantity, SUM(nilaibarang*pc.qty) as nilaibarang,pc.gudang_id,nama,pc.date_added from ".DB_PREFIX."product_cacat_gudang pc  LEFT JOIN ".DB_PREFIX."gudang g ON(pc.gudang_id=g.gudang_id) ";
      if (!empty($data['filter_gudang_id'])) {
				$sql .= " WHERE pc.gudang_id= '" . $data['filter_gudang_id'] . "'";
			}
			else{
				if($permission){
					$sql .=" WHERE pc.gudang_id IN(SELECT gudang_id FROM ".DB_PREFIX."user_gudang WHERE user_id='".$this->user->getId()."' )";
				}
			}
      if (!empty($data['filter_date_start'])) {
				$sql .= " AND pc.date_added >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}

			if (!empty($data['filter_date_end'])) {
				$sql .= " AND pc.date_added <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
		    if (!empty($data['filter_product_id'])) {
				$sql .= " AND pc.product_id= '" . $data['filter_product_id'] . "'";
			}

		    $sql .= " GROUP BY pc.gudang_id,pc.product_id,g.nama,pc.product_option_id,pc.date_added,pc.nilaibarang";


    $query = $this->db->query($sql);
    return $query->num_rows;

  }



	public function addProductCacat($pro){
		$this->load->model('catalog/product');
		$this->load->model('gudang/kartustok');
		foreach($pro['product'] as $data){
			if(!empty($data['product_id'])){
			if(!empty($data['product_otion'])){
				$data['product_option_id']=$data['product_otion'];
			}
			else{
				$data['product_option_id']=0;
			}
			$cacat=array(
				'product_id'	=> $data['product_id'],
				'product_option_id'	=> $data['product_option_id'],
				'qty'	=> $data['qty'],
				'date_added'	=> date('Y-m-d'),
				'gudang_id'	=> $pro['gudang_id'],
				'nilaibarang'	=> $data['price']
			);

			$this->db->insert('product_cacat_gudang',$cacat);

			$cacat_id=$this->db->getLastId();
			$prod=$this->model_catalog_product->getProduct($data['product_id']);
			$prodg=$this->model_gudang_product->getProductGudangT($data['product_id'],$pro['gudang_id']);

			//updateQtyOption($product_option_id,$product_id,$gudang_id,$quantity,$jenis)
			if(!empty($data['product_option_id'])){
				$opt=$this->getOptionProduct($data['product_id'],$pro['gudang_id'],$data['product_option_id']);
				$update=$this->updateQtyOption($data['product_option_id'],$data['product_id'],$pro['gudang_id'],$data['qty'],2);

				$kartustok=array(
					'product_id'	=> $data['product_id'],
					'product_option_name'	=> $this->db->escape($opt['name']),
					'product_option_id'	=> $data['product_option_id'],
					'tgl'	=> date('Y-m-d H:i:s'),
					'stokmasuk'	=> 0,
					'stokkeluar'	=> $data['qty'],
					'ket'	=> 'Produk cacat',
					'saldo'	=> $update,
					'quantityawal'	=> $opt['quantity'],
					'invoice'	=> $cacat_id,
					'gudang_id'	=> $pro['gudang_id'],
					'type'	=> 11
				);

				$this->model_gudang_kartustok->addKartuStokOption($kartustok);
			}else{
				$gupdate=$this->updateQty($data['product_id'],$pro['gudang_id'],$data['qty'],2);
			}

			$curprodg=$this->model_gudang_product->getProductGudangT($data['product_id'],$pro['gudang_id']);
			$gkartustok=array(
					'product_id'	=> $data['product_id'],
					'product_name'	=> $this->db->escape($prod['name']),
					'tgl'	=> date('Y-m-d H:i:s'),
					'stokmasuk'	=> 0,
					'stokkeluar'	=> $data['qty'],
					'ket'	=> 'Produk cacat',
					'saldo'	=> $curprodg['quantity'],
					'quantityawal'	=> $prodg['quantity'],
					'invoice'	=> $cacat_id,
					'gudang_id'	=> $pro['gudang_id'],
					'type'	=> 12
				);
			$this->model_gudang_kartustok->addKartustok($gkartustok);

			//gudang
			$this->load->model('catalog/gudang');
			$gud=$this->model_catalog_gudang->getGudang($pro['gudang_id']);

			$act=array(
				'activity'	=> 'Menambahkan produk cacat '.$prod['name'].' gudang '.$gud['nama'],
				'menu'	=> 'Produk Cacat'
			);
			$this->user->addUserActivity($act);
		}
		}
	}

	//produk hilang

	public function getProdukHilang($data=array(),$permission=false){
      $sql="select pc.product_id,pc.product_option_id,p.name,SUM(pc.qty) as quantity, SUM(nilaibarang*pc.qty) as nilaibarang,pc.gudang_id,nama,pc.date_added from ".DB_PREFIX."product_hilang_gudang pc LEFT JOIN ".DB_PREFIX."product p ON(pc.product_id=p.product_id)  LEFT JOIN ".DB_PREFIX."gudang g ON(pc.gudang_id=g.gudang_id) ";
			if (!empty($data['filter_gudang_id'])) {
				$sql .= " WHERE pc.gudang_id= '" . $data['filter_gudang_id'] . "'";
			}
			else{
				if($permission){
					$sql .=" WHERE pc.gudang_id IN(SELECT gudang_id FROM ".DB_PREFIX."user_gudang WHERE user_id='".$this->user->getId()."' )";
				}
			}
      if (!empty($data['filter_date_start'])) {
				$sql .= " AND pc.date_added >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}

			if (!empty($data['filter_date_end'])) {
				$sql .= " AND pc.date_added <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
		    if (!empty($data['filter_product_id'])) {
				$sql .= " AND pc.product_id= '" . $data['filter_product_id'] . "'";
			}

		    $sql .= " GROUP BY pc.gudang_id,g.nama,p.name,pc.product_id,pc.product_option_id,pc.date_added,pc.nilaibarang";
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

	public function getTotalProdukHilang($data=array(),$permission=false){
      $sql="select pc.product_id,SUM(pc.qty) as quantity, SUM(nilaibarang*pc.qty) as nilaibarang,pc.gudang_id,nama,pc.date_added from ".DB_PREFIX."product_hilang_gudang pc  LEFT JOIN ".DB_PREFIX."gudang g ON(pc.gudang_id=g.gudang_id) ";
      if (!empty($data['filter_gudang_id'])) {
				$sql .= " WHERE pc.gudang_id= '" . $data['filter_gudang_id'] . "'";
			}
			else{
				if($permission){
					$sql .=" WHERE pc.gudang_id IN(SELECT gudang_id FROM ".DB_PREFIX."user_gudang WHERE user_id='".$this->user->getId()."' )";
				}
			}
      if (!empty($data['filter_date_start'])) {
				$sql .= " AND pc.date_added >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}

			if (!empty($data['filter_date_end'])) {
				$sql .= " AND pc.date_added <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
		    if (!empty($data['filter_product_id'])) {
				$sql .= " AND pc.product_id= '" . $data['filter_product_id'] . "'";
			}

		    $sql .= " GROUP BY pc.gudang_id,pc.product_id,g.nama,pc.product_option_id,pc.date_added,pc.nilaibarang";


    $query = $this->db->query($sql);
    return $query->num_rows;

  }



	public function addProductHilang($pro){
		$this->load->model('catalog/product');
		$this->load->model('gudang/kartustok');
		foreach($pro['product'] as $data){
			if(!empty($data['product_id'])){
			if(!empty($data['product_otion'])){
				$data['product_option_id']=$data['product_otion'];
			}
			else{
				$data['product_option_id']=0;
			}
			$cacat=array(
				'product_id'	=> $data['product_id'],
				'product_option_id'	=> $data['product_option_id'],
				'qty'	=> $data['qty'],
				'date_added'	=> date('Y-m-d'),
				'gudang_id'	=> $pro['gudang_id'],
				'nilaibarang'	=> $data['price']
			);

			$this->db->insert('product_hilang_gudang',$cacat);

			$cacat_id=$this->db->getLastId();
			$prod=$this->model_catalog_product->getProduct($data['product_id']);
			$prodg=$this->model_gudang_product->getProductGudangT($data['product_id'],$pro['gudang_id']);

			//updateQtyOption($product_option_id,$product_id,$gudang_id,$quantity,$jenis)
			if(!empty($data['product_option_id'])){
				$opt=$this->getOptionProduct($data['product_id'],$pro['gudang_id'],$data['product_option_id']);
				$update=$this->updateQtyOption($data['product_option_id'],$data['product_id'],$pro['gudang_id'],$data['qty'],2);

				$kartustok=array(
					'product_id'	=> $data['product_id'],
					'product_option_name'	=> $this->db->escape($opt['name']),
					'product_option_id'	=> $data['product_option_id'],
					'tgl'	=> date('Y-m-d H:i:s'),
					'stokmasuk'	=> 0,
					'stokkeluar'	=> $data['qty'],
					'ket'	=> 'Produk hilang',
					'saldo'	=> $update,
					'quantityawal'	=> $opt['quantity'],
					'invoice'	=> $cacat_id,
					'gudang_id'	=> $pro['gudang_id'],
					'type'	=> 12
				);

				$this->model_gudang_kartustok->addKartuStokOption($kartustok);
				//$gupdate=$update;
			}else{
				$gupdate=$this->updateQty($data['product_id'],$pro['gudang_id'],$data['qty'],2);
			}

			$curprodg=$this->model_gudang_product->getProductGudangT($data['product_id'],$pro['gudang_id']);
			$gkartustok=array(
					'product_id'	=> $data['product_id'],
					'product_name'	=> $this->db->escape($prod['name']),
					'tgl'	=> date('Y-m-d H:i:s'),
					'stokmasuk'	=> 0,
					'stokkeluar'	=> $data['qty'],
					'ket'	=> 'Produk hilang',
					'saldo'	=> $curprodg['quantity'],
					'quantityawal'	=> $prodg['quantity'],
					'invoice'	=> $cacat_id,
					'gudang_id'	=> $pro['gudang_id'],
					'type'	=> 12
				);
			$this->model_gudang_kartustok->addKartustok($gkartustok);

			//gudang
			$this->load->model('catalog/gudang');
			$gud=$this->model_catalog_gudang->getGudang($pro['gudang_id']);

			$act=array(
				'activity'	=> 'Menambahkan produk hilang '.$prod['name'].' gudang '.$gud['nama'],
				'menu'	=> 'Produk Hilang'
			);
			$this->user->addUserActivity($act);
		}
		}
	}

	//stokopname
	public function getStokopname($data=array(),$permission=false){
      $sql="select pc.product_id,p.name,pc.gudang_id,nama,pc.tanggal,pc.keterangan,qtytercatat,qtytersedia,qtyrusak,qtyhilang from ".DB_PREFIX."stokopname_gudang pc LEFT JOIN ".DB_PREFIX."product p ON(pc.product_id=p.product_id)  LEFT JOIN ".DB_PREFIX."gudang g ON(pc.gudang_id=g.gudang_id) ";
			if (!empty($data['filter_gudang_id'])) {
				$sql .= " WHERE pc.gudang_id= '" . $data['filter_gudang_id'] . "'";
			}
			else{
				if($permission){
					$sql .=" WHERE pc.gudang_id IN(SELECT gudang_id FROM ".DB_PREFIX."user_gudang WHERE user_id='".$this->user->getId()."' )";
				}
			}
      if (!empty($data['filter_date_start'])) {
				$sql .= " AND pc.tanggal >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}

			if (!empty($data['filter_date_end'])) {
				$sql .= " AND pc.tanggal <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}
		    if (!empty($data['filter_product_id'])) {
				$sql .= " AND pc.product_id= '" . $data['filter_product_id'] . "'";
			}

			$sql .=" ORDER BY pc.tanggal DESC";
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

	public function getTotalStokopname($data=array(),$permission=false){
		$sql="select pc.product_id,p.name,pc.gudang_id,nama,pc.tanggal,pc.keterangan,qtytercatat,qtytersedia,qtyrusak,qtyhilang from ".DB_PREFIX."stokopname_gudang pc LEFT JOIN ".DB_PREFIX."product p ON(pc.product_id=p.product_id)  LEFT JOIN ".DB_PREFIX."gudang g ON(pc.gudang_id=g.gudang_id) ";
		if (!empty($data['filter_gudang_id'])) {
			$sql .= " WHERE pc.gudang_id= '" . $data['filter_gudang_id'] . "'";
		}
		else{
			if($permission){
				$sql .=" WHERE pc.gudang_id IN(SELECT gudang_id FROM ".DB_PREFIX."user_gudang WHERE user_id='".$this->user->getId()."' )";
			}
		}
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND pc.tanggal >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND pc.tanggal <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
			if (!empty($data['filter_product_id'])) {
			$sql .= " AND pc.product_id= '" . $data['filter_product_id'] . "'";
		}

		$sql .=" ORDER BY pc.tanggal DESC";

    $query = $this->db->query($sql);
    return $query->num_rows;

  }



	public function addStokopname($pro){
		$this->load->model('catalog/product');
		$this->load->model('gudang/kartustok');
		foreach($pro['product'] as $data){
			$selisih=0;
			if(!empty($data['product_id'])){
				if(!empty($data['product_otion'])){
					$data['product_option_id']=$data['product_otion'];
				}
				else{
					$data['product_option_id']=0;
				}


				$cacat=array(
					'product_id'	=> $data['product_id'],
					'product_option_id'	=> $data['product_option_id'],
					'qtytersedia'	=> empty($data['qtytersedia'])?0:$data['qtytersedia'],
					'qtyrusak'	=> empty($data['qtyrusak'])?0:$data['qtyrusak'],
					'qtyhilang'	=> empty($data['qtyhilang'])?0:$data['qtyhilang'],
					'qtytercatat'	=> empty($data['qtytercatat'])?0:$data['qtytercatat'],
					'tanggal'	=> $pro['date_added'],
					'gudang_id'	=> $pro['gudang_id'],
					'keterangan'	=> $this->db->escape($data['keterangan']).' oleh '.$this->user->getName(),
					'nilaibarang'	=> !empty($data['price'])?$data['price']:0
				);

				$this->db->insert('stokopname_gudang',$cacat);

				$cacat_id=$this->db->getLastId();
				$prod=$this->model_catalog_product->getProduct($data['product_id']);
				$prodg=$this->model_gudang_product->getProductGudangT($data['product_id'],$pro['gudang_id']);

				//selisih
				$qtytersimpan=$data['qtytercatat'] - ($data['qtyrusak']+$data['qtyhilang']);

				if($qtytersimpan != $data['qtytersedia']){
					if($qtytersimpan < $data['qtytersedia']){
						$selisih=$data['qtytersedia'] - $qtytersimpan;
						$stokmasuk=$selisih;
						$stokkeluar=($data['qtyrusak']+$data['qtyhilang']);

						$gupdate=$this->updateQty($data['product_id'],$pro['gudang_id'],$stokkeluar,2);
						$gupdate=$this->updateQty($data['product_id'],$pro['gudang_id'],$selisih,1);
						//$gupdate=$this->updateQty($data['product_id'],$pro['gudang_id'],$selisih,1);
					}

					if($qtytersimpan > $data['qtytersedia']){
						$selisih=$qtytersimpan - $data['qtytersedia'];
						$stokmasuk=0;
						$stokkeluar=$selisih+($data['qtyrusak']+$data['qtyhilang']);

						$gupdate=$this->updateQty($data['product_id'],$pro['gudang_id'],$stokkeluar,2);
					}
				}else{
					$selisih=($data['qtyrusak']+$data['qtyhilang']);
					$stokmasuk=0;
					$stokkeluar=$selisih;
					$gupdate=$this->updateQty($data['product_id'],$pro['gudang_id'],$stokkeluar,2);
				}



					/*if($data['qtytercatat'] < $data['qtytersedia']){
						$gupdate=$this->updateQty($data['product_id'],$pro['gudang_id'],$selisih,1);
					}

					if($data['qtytercatat'] >= $data['qtytersedia']){
						$gupdate=$this->updateQty($data['product_id'],$pro['gudang_id'],$selisih,2);
					}*/


				$curprodg=$this->model_gudang_product->getProductGudangT($data['product_id'],$pro['gudang_id']);
				$gkartustok=array(
						'product_id'	=> $data['product_id'],
						'product_name'	=> $this->db->escape($prod['name']),
						'tgl'	=> date('Y-m-d H:i:s'),
						'stokmasuk'	=> $stokmasuk,
						'stokkeluar'	=> $stokkeluar,
						'ket'	=> 'Stok opname dengan keterangan '.$data['keterangan'].' oleh '.$this->user->getName(),
						'saldo'	=> $curprodg['quantity'],
						'quantityawal'	=> $prodg['quantity'],
						'invoice'	=> $cacat_id,
						'gudang_id'	=> $pro['gudang_id'],
						'type'	=> 12
					);
				$this->model_gudang_kartustok->addKartustok($gkartustok);
				$this->load->model('keuangan/jurnal');
				if($pro['jurnal']){
			    if(!empty($prodg['net_cost'])){
			      if($data['qtyhilang'] > 0){
			        $details=array();
			        $details[]=array(
			          'ref_akun'  => '6266',
			          'keterangan'  => 'Biaya Kehilangan Barang',
			          'debet' => $prodg['net_cost'] * $data['qtyhilang'],
			          'kredit'  => 0,
			          'urutan'  => 1,
			          'hapus' => 0
			        );
						}
						if($data['qtyrusak'] > 0){
			        $details=array();
			        $details[]=array(
			          'ref_akun'  => '1204',
			          'keterangan'  => 'Persediaan Barang Rusak',
			          'debet' => $prodg['net_cost'] * $data['qtyhilang'],
			          'kredit'  => 0,
			          'urutan'  => 2,
			          'hapus' => 0
			        );
						}

						if($stokmasuk > 0){
							$details=array();
							$details[]=array(
								'ref_akun'  => '1202',
			          //'jenis_akun'  => 52,
			          'keterangan'  => 'Persediaan barang jadi',
								'debet' => $prodg['net_cost'] * $data['stokmasuk'],
								'kredit'  => 0,
								'urutan'  => 3,
								'hapus' => 0
							);
						}


			        $details[]=array(
			          'ref_akun'  => '1202',
			          //'jenis_akun'  => 52,
			          'keterangan'  => 'Persediaan barang jadi',
			          'debet' => 0,
			          'kredit'  => $prodg['net_cost']*($data['qtyhilang']+$data['qtyrusak']),
			          'urutan'  => 4,
			          'hapus' => 0
			        );
							if($stokmasuk > 0){
				        $details=array();
				        $details[]=array(
				          'ref_akun'  => '7003',
				          'keterangan'  => 'Pendapatan Lain-lain',
				          'kredit' => $prodg['net_cost'] * $data['stokmasuk'],
				          'debet'  => 0,
				          'urutan'  => 5,
				          'hapus' => 0
				        );
							}


			        $j=array(
			          'tanggal' => isset($pro['date_added'])?$pro['date_added']:date('Y-m-d'),
			          'keterangan'  => 'Stok Opname '.$cacat_id,
			          'details' => $details,
			          'hapus' =>0,
			          'ref' => $cacat_id,
			          'type'  => 9900
			        );
			        $this->model_keuangan_jurnal->addJurnalUmum($j);

			    }
				}

			}

		}
	}

	//net cost
	public function setNetCost($product_id,$gudang_id,$net_cost,$qty){
		$prod=$this->getProductGudangT($product_id,$gudang_id);
		if($prod['net_cost'] == 0 & $prod['quantity'] <= 0){
			$nnet_cost=$net_cost;
		}
		if($prod['net_cost'] == 0 & $prod['quantity'] > 0){
			$nnet_cost = ($qty * $net_cost)/($qty+$prod['quantity']);
		}
		if($prod['net_cost'] > 0){
			$nnet_cost = ($qty * $net_cost)+($prod['net_cost'] * $prod['quantity'])/($qty+$prod['quantity']);
		}

		$this->db->query("UPDATE ".DB_PREFIX."product_gudang SET net_cost='".$nnet_cost."' WHERE product_id='".$product_id."' AND gudang_id='".$gudang_id."' ");
	}

	public function addProductSpecial($gudang_id,$product_id,$datas){
		foreach($datas as $data){

			$cek=$this->cekProductSpecial($gudang_id,$product_id,$data['customer_group_id']);
			if(!empty($cek)){
				if($cek['hapus'] == 1){
					$this->db->query("UPDATE ".DB_PREFIX."product_special SET hapus=0,price='".$data['price']."',batasbawah='".$data['batasbawah']."' WHERE product_id='".$product_id."' AND customer_group_id='".$data['customer_group_id']."' AND gudang_id='".$gudang_id."'");
				}
			}else{
				$spec=array(
					'product_id' => $product_id,
					'customer_group_id'	=> $data['customer_group_id'],
					'gudang_id'	=> $gudang_id,
					'hapus'	=> 0,
					'price'	=> $data['price'],
					'batasbawah'	=> $data['batasbawah']
				);
				$this->db->insert('product_special',$spec);
				//$this->db->query("INSERT INTO ".DB_PREFIX."product_special SET product_id='".$product_id."',priority='".$data['priority']."',customer_group_id='".$data['customer_group_id']."',date_start='".$data['date_start']."' ,date_end='".$data['date_end']."',price='".$data['price']."' ");
			}
		}
	}

	public function deleteProductSpecial($product_special_id){
		$this->db->query("UPDATE ".DB_PREFIX."product_special SET hapus=1 WHERE product_special_id='".$product_special_id."' ");
	}

	public function cekProductSpecial($gudang_id,$product_id,$customer_group_id){
		$sql = "SELECT * FROM ".DB_PREFIX."product_special WHERE product_id='".$product_id."' AND gudang_id='".$gudang_id."' AND customer_group_id='".$customer_group_id."'";

		$res=$this->db->query($sql);

		return $res->row;
	}

	public function getProductSpecials($gudang_id,$product_id) {
	  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND gudang_id='".$gudang_id."' AND hapus=0 ORDER BY customer_group_id");

	  return $query->rows;
	}

	public function updateNetCost($product_id,$gudang_id,$net_cost,$pembelian_id=0){
		$this->db->query("UPDATE ".DB_PREFIX."product_gudang SET net_cost='".$net_cost."' WHERE product_id='".$product_id."' AND gudang_id='".$gudang_id."'");
		$hist=array(
			'product_id'=>$product_id,
			'gudang_id'=>$gudang_id,
			'net_cost'=>$net_cost,
			'date_added'	=> date('Y-m-d h:i:s',time())
		);
		$this->db->insert('netcost_history_gudang',$hist);

		//cek penjualan dengan net cost kosong
		//invoice
		//if(){}
		//$this->db->query("UPDATE invoice_product SET net_cost='".$net_cost."' WHERE product_id='".$product_id."' AND gudang_id='".$gudang_id."' AND net_cost=0");
		$invs=$this->db->query("SELECT ip.* FROM invoice_product ip JOIN invoice i ON(ip.sales_order_id=i.id) WHERE ip.product_id='".$product_id."' AND i.gudang_id='".$gudang_id."' AND ip.net_cost =0");
		foreach($invs->rows as $i){
			$this->db->update('invoice_product',array('net_cost'=>$net_cost),array('id'=>$i['id']));

		}

		$sos=$this->db->query("SELECT ip.* FROM sales_order_product ip JOIN sales_order i ON(ip.sales_order_id=i.id) WHERE ip.product_id='".$product_id."' AND i.gudang_id='".$gudang_id."' AND ip.net_cost =0");
		foreach($sos->rows as $i){
			$this->db->update('sales_order_product',array('net_cost'=>$net_cost),array('id'=>$i['id']));

		}

		$penjualans=$this->db->query("SELECT ip.* FROM penjualan_product ip JOIN penjualan i ON(ip.sales_order_id=i.id) WHERE ip.product_id='".$product_id."' AND i.gudang_id='".$gudang_id."' AND ip.net_cost =0");
		foreach($penjualans->rows as $i){
			$this->db->update('penjualan_product',array('net_cost'=>$net_cost),array('id'=>$i['id']));

		}
		//foreach()
	}
	public function addProductPremi($gudang_id,$product_id,$data){
		$premi=array(
			'premijual'	=>$data['premijual'],
			'premiambil'	=> $data['premiambil'],
			'premikirim'	=> $data['premikirim'],
			'premibongkar'	=> $data['premibongkar']
		);
		$this->db->update('product_gudang',$premi,array('product_id'=>$product_id,'gudang_id'=>$gudang_id));
	}
	public function getNetcosts($data=array()){
		$sql="SELECT * FROM ".DB_PREFIX."netcost_history_gudang  WHERE product_id='".$data['product_id']."' AND gudang_id='".$data['gudang_id']."' ";
		if(!empty($data['tanggal'])){
			$sql .=" AND DATE(date_added)='".$data['tanggal']."' ";
		}
		$sql.="ORDER BY date_added DESC ";
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



	public function getTotalNetcosts($data=array()){
		$sql="SELECT * FROM ".DB_PREFIX."netcost_history_gudang WHERE product_id='".$data['product_id']."' AND gudang_id='".$data['gudang_id']."'";
		if(!empty($data['tanggal'])){
			$sql .=" AND DATE(date_added)='".$data['tanggal']."' ";
		}
		$query = $this->db->query($sql);

		return $query->num_rows;
	}

	public function getPrices($data=array()){
		$sql="SELECT * FROM ".DB_PREFIX."price_history p LEFT JOIN customer c ON(p.customer_id=c.customer_id)  WHERE product_id='".$data['product_id']."' AND gudang_id='".$data['gudang_id']."' ";
		if(!empty($data['tanggal'])){
			$sql .=" AND DATE(date_added)='".$data['tanggal']."' ";
		}

		$sql.="ORDER BY p.date_added DESC ";
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



	public function getTotalPrices($data=array()){
		$sql="SELECT * FROM ".DB_PREFIX."price_history WHERE product_id='".$data['product_id']."' AND gudang_id='".$data['gudang_id']."'";
		if(!empty($data['tanggal'])){
			$sql .=" AND DATE(date_added)='".$data['tanggal']."' ";
		}
		$query = $this->db->query($sql);

		return $query->num_rows;
	}
}
?>
