<?php
class ModelSaleSalesordermr extends Model {
  /*
  Statuss
  0. belum diterima
  1. sudah diterima
  2. terdapat selisih
  3. dibatalkan

  $act=array(
    'activity'	=> 'Penerimaan pembelian nomor  '.$pro['invoice_no'],
    'menu'	=> 'Pembelian'
  );
  $this->user->addUserActivity($act);
  */
  public function updateqtykirim($id,$qty,$jenis){
    $data=$this->getPenjualanProduct(array('id' => $id));

		//update qty
		if($jenis == 1){
			$qtyf=$data['quantityterima'] + $qty;
		}
		if($jenis == 2){
			$qtyf=$data['quantityterima'] - $qty;
		}

    if($qtyf == $data['quantity']){
      $status=3;
    }else{
      if($qtyf <= 0){
        $status =1;
      }else{
      $status=2;
      }
    }

	  $this->db->query("UPDATE ".DB_PREFIX."sales_ordermr_product SET quantityterima='".$qtyf."',status_pengiriman='".$status."' WHERE id='".$id."'");
		return $qtyf;
  }
  public function addPenjualan($data){
    $this->load->model('sale/customer');
    if($data['pengiriman'] == 1){
      $data['address_id'] = 0;
    }
    if($data['address_id'] == -1){
      if(!empty($data['country_id'])){
        $add=array(
          'customer_id'	=> $data['customer_id'],
          'firstname'	=>$this->db->escape($data['firstname']),
          'lastname'	=>$this->db->escape($data['lastname']),
          'address_1'	=>$this->db->escape($data['address_1']),
          'address_2'	=>$this->db->escape($data['address_2']),
          'city_id'	=>empty($data['city_id'])?0:$data['city_id'],
          'postcode'	=>$this->db->escape($data['postcode']),
          'country_id'	=>empty($data['country_id'])?0:$data['country_id'],
          'zone_id'	=>empty($data['zone_id'])?0:$data['zone_id'],
          'hapus'	=> 0
        );
      }
      $data['address_id']=$this->model_sale_customer->addAddress($add,$data['customer_id']);
    }
    $penj=array(
      'date_added' => isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s'),
      'sales'  => $data['sales'],
      'customer_id'  => $data['customer_id'],
      'status' => 1,
      'pengiriman'  => $data['pengiriman'],
      'hapus' => 0,
      'user_id'  => $this->user->getId(),
      'address_id' =>empty($data['address_id'])?0:$data['address_id'],
      //'jenisorder'  => $data['jenisorder'],
      //'statustabung'  => $data['statustabung'],
      'sub_total' => $data['sub_total'],
      'diskon'  => 0,
      'pajak' => $data['pajak'],
      'total' => $data['total'],
      'gudang_id'  => $data['gudang_id'],
      'tttk'  => empty($data['tttk'])?0:$data['tttk'],
      'usia'  => empty($data['usia'])?0:$data['usia'],
      'metode_pembayaran' => $data['metode_pembayaran'],
      //'pembulatan'  => $data['pembulatan']
    );
    $this->db->insert('sales_ordermr',$penj);
    $id=$this->db->getlastId();
    $data['id']=$id;
    $no_so="SO-".$id."-".date('Y')."-".date("m")."-".$this->user->getId();
    $this->db->update('sales_ordermr',array('no_so' => $no_so),array('id' => $id));
    $this->addPenjualanProduct($data);


    return $id;

  }
  public function addPenjualanProduct($data){
    $total=0;
    $diskon=0;

  //  $penjualan=$this->getPenjualan(array('id' => $data['id']));

  $this->load->model('catalog/product');
    foreach($data['product'] as $p){
      if(!empty($p['product_id'])){
        //getnetcost
        //$net=$this->db->first('product_toko_pameran',array('gudang_id'  => $penjualan['pameran_id'],'product_id'  => $p['product_id']));

        $curqty=$this->model_catalog_product->getProduct($p['product_id']);
        //$pajak=($p['price']-$p['diskon'])*0.1;
        //$total=(($p['price']-$p['diskon'])*$p['quantity']) + ($pajak*$p['quantity']);
        if($p['nilaipajak'] == 1){
          //$p['pajak']=round(($p['price'])*0.1);
          $p['totalpajak']=($p['price'] * $p['quantity'])*0.1;
          $p['pajak']=$p['totalpajak']/$p['quantity'];
          $p['pembulatan']=0;
        }
        if($p['nilaipajak'] == 2){
          $p['totalpajak']=(10/110)*($p['price']*$p['quantity']);
          $p['pajak']=$p['totalpajak']/$p['quantity'];
          //$p['pajak']=(round((10/110)*($p['price']*$p['quantity'])))/$p['quantity'];
          $np=(round((100/110)*($p['price']*$p['quantity'])))/$p['quantity'];
          $p['pembulatan']=$p['price'] - ($p['pajak']+$np);
          $p['price']=$np;
        }
        if($p['nilaipajak'] == 3){
          $p['totalpajak']=0;
          $p['pajak'] = 0;
          $p['pembulatan'] = 0;
        }
        $penj=array(
          'sales_order_id' => $data['id'],
          'product_id'  => $p['product_id'],
          //'tabung_id'  => $p['tabung_id'],
          'quantity' => $p['quantity'],
          'quantityterima' => 0,
          'status_pengiriman'  => 1,
          'price' => $p['price'],
          'diskon' => 0,
          'pajak' => $p['pajak'],
          'total' => $p['total'],
          //'pembulatan'  => $p['pembulatan'],
          'hapus' => 0,
          'net_cost'  => empty($curqty['net_cost'])?0:$curqty['net_cost'],
        );

      $this->db->insert('sales_ordermr_product',$penj);

      //history harga
      $hist=array(
        'price' => $p['price'],
        'product_id'  => $p['product_id'],
        'date_added'  => date('Y-m-d'),
        'gudang_id' => $data['gudang_id'],
        'customer_id' => $data['customer_id'],
        'so_id' => $data['id']
      );
      $this->db->insert('price_history',$hist);



    }
    }

  }





  public function cancelPenjualan($id,$alasan_batal){
    $penj=array(
      'status'  => 4,
      'alasan_batal'  => $alasan_batal
    );

    $where=array(
      'id'  => $id
    );
    $this->db->update('sales_ordermr',$penj,$where);
    //kembalikan stok produk

  }

  public function updatePenjualan($data,$where=array()){
	   $this->db->update('sales_ordermr',$data,$where);
	}
  public function updatePenjualanProduct($data,$where=array()){
	   $this->db->update('sales_ordermr_product',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('sales_ordermr',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order){
		return $this->db->firstdetail('sales_ordermr',$column,$join,$where,$order);
	}
  public function getPenjualanProducts($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('sales_ordermr_product.product_id','sales_ordermr_product.jenisref','sales_ordermr_product.referensi','sales_ordermr_product.referensi2','satuan.name as namasatuan','sales_ordermr_product.id','sales_ordermr_product.diskon','sales_ordermr_product.pajak','sales_ordermr_product.total','product.name','sales_ordermr_product.quantity','sales_ordermr_product.net_cost','sales_ordermr_product.price','sales_ordermr_product.quantityterima');
    $join=array();
    $join[]=array(
      'tablename' => 'product',
      'firsttable'  => 'sales_ordermr_product.product_id',
      'secondtable' => 'product.product_id'
    );

    $leftjoin=array();

    $leftjoin[]=array(
      'tablename' => 'satuan',
      'firsttable'  => 'product.satuan',
      'secondtable' => 'satuan.id'
    );

    return $this->db->alljoins('sales_ordermr_product',$column,$join,$leftjoin,$where,array(),0,null);
	}


  public function getListDetail($where,$order,$limit=0,$offset=null){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('sales_order.id as sales_order_id','sales_order.no_so','gudang.nama as namagudang','sales_order.date_added','customer.name as namacustomer','customer.email','customer.telephone','sales_order_product.product_id','sales_order_product.id','sales_order_product.diskon','sales_order_product.pajak','sales_order_product.total','product.name as namaproduct','sales_order_product.quantity','sales_order_product.quantityterima','sales_order_product.status_pengiriman','sales_order_product.net_cost','sales_order_product.price');
    $join=array();
    $join[]=array(
      'tablename' => 'product',
      'firsttable'  => 'sales_order_product.product_id',
      'secondtable' => 'product.product_id'
    );
    $join[]=array(
      'tablename' => 'sales_order',
      'firsttable'  => 'sales_order_product.sales_order_id',
      'secondtable' => 'sales_order.id'
    );
    $join[]=array(
      'tablename' => 'customer',
      'firsttable'  => 'sales_order.customer_id',
      'secondtable' => 'customer.customer_id'
    );
    $join[]=array(
      'tablename' => 'gudang',
      'firsttable'  => 'sales_order.gudang_id',
      'secondtable' => 'gudang.gudang_id'
    );

    return $this->db->alljoin('sales_order_product',$column,$join,$where,$order,$limit,$offset);
	}
  public function getTotalListDetail($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('sales_order.id as sales_order_id','sales_order.no_so','gudang.nama as namagudang','sales_order.date_added','customer.name as namacustomer','customer.email','customer.telephone','sales_order_product.product_id','sales_order_product.id','sales_order_product.diskon','sales_order_product.pajak','sales_order_product.total','product.name as namaproduct','sales_order_product.quantity','sales_order_product.quantityterima','sales_order_product.status_pengiriman','sales_order_product.net_cost','sales_order_product.price');
    $join=array();
    $join[]=array(
      'tablename' => 'product',
      'firsttable'  => 'sales_order_product.product_id',
      'secondtable' => 'product.product_id'
    );
    $join[]=array(
      'tablename' => 'sales_order',
      'firsttable'  => 'sales_order_product.sales_order_id',
      'secondtable' => 'sales_order.id'
    );
    $join[]=array(
      'tablename' => 'customer',
      'firsttable'  => 'sales_order.customer_id',
      'secondtable' => 'customer.customer_id'
    );
    $join[]=array(
      'tablename' => 'gudang',
      'firsttable'  => 'sales_order.gudang_id',
      'secondtable' => 'gudang.gudang_id'
    );
    $total=$this->db->alljoin('sales_order_product',$column,$join,$where,array(),0,null);
    return count($total);
	}
  public function getPenjualanProduct($where){
		return $this->db->first('sales_ordermr_product',$where);
	}
	public function getPenjualans($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order,$limit,$offset){
		return $this->db->alljoins('sales_ordermr',$column,$join,$leftjoin,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where){
		return $this->db->count('sales_ordermr',$where);
	}
  public function totalqty($sales_order_id){
    $total=$this->db->query("SELECT SUM(quantity) as total FROM sales_ordermr_product WHERE sales_order_id='".$sales_order_id."'");
    return $total->row['total'];
  }

  /*public function getPenjualanProduct(){

  }*/


}
?>
