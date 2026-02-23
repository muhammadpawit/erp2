<?php
class ModelSaleSalesorderbahanbaku extends Model {
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
    $data=$this->getPenjualanProduct(array('id'=> $id));

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

	  $this->db->query("UPDATE ".DB_PREFIX." sales_order_product_bahanbaku SET quantityterima='".$qtyf."',status_pengiriman='".$status."'WHERE id='".$id."'");
		return $qtyf;
  }
  public function addPenjualan($data){
    $this->load->model('sale/customer');
    $catatan=null;
    $status=1;
    if($data['pengiriman'] == 1){
      $data['address_id'] = 0;
	  if(!empty($data['catatan'])){
		$catatan=$data['catatan'];
	  }
    }
	if($this->user->getUsername()=="pawits"){
		return $catatan;
		exit;
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
        $data['address_id']=$this->model_sale_customer->addAddress($add,$data['customer_id']);
      }

    }
    if($data['date_added']==date('Y-m-d')){
      $status=1;
    }else{
      $status=5; // menunggu persetujuan perubahan tanggal
    }
    $penj=array(
      'date_added' => isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s'),
      'sales' => $data['sales'],
      'customer_id' => $data['customer_id'],
      'status'=> 1,
      'pengiriman' => $data['pengiriman'],
      'hapus'=> 0,
      'user_id' => $this->user->getId(),
      'address_id'=>$data['address_id'],
      'tttk'  => $data['tttk'],
      //'jenisorder' => $data['jenisorder'],
      //'statustabung' => $data['statustabung'],
      'sub_total'=> $data['sub_total'],
      'diskon' => 0,
      'pajak'=> $data['pajak'],
      'total'=> $data['total'],
      'usia' => $data['usia'],
      'metode_pembayaran'=> $data['metode_pembayaran'],
      'catatan'  => $this->db->escape($data['catatan'])
    );
    $this->db->insert('sales_order_bahanbaku',$penj);
    $id=$this->db->getlastId();
    $data['id']=$id;
    $no_so="SOBB-".$id."-".date('Y')."-".date("m")."-".$this->user->getId();
    $this->db->update('sales_order_bahanbaku',array('no_so'=> $no_so),array('id'=> $id));
    $this->addPenjualanProduct($data);


    return $id;

  }
  public function addPenjualanProduct($data){
    $total=0;
    $diskon=0;

  //  $penjualan=$this->getPenjualan(array('id'=> $data['id']));

  $this->load->model('catalog/bahanbaku');
    foreach($data['product'] as $p){
      if(!empty($p['product_id'])){
        //getnetcost
        //$net=$this->db->first('product_toko_pameran',array('gudang_id' => $penjualan['pameran_id'],'product_id' => $p['product_id']));

        $curqty=$this->model_catalog_bahanbaku->getProduct($p['product_id']);
        //cek konversi satuan, harga beli tersimpan adalah harga per konversi litter

        $konversi=$this->model_catalog_bahanbaku->cekKonversi($p['product_id'],508);
        //1kg = $konversi['nilai'] liter
        //x liter berarti berapa kg
        //berarti x/$konversi['nilai']
        //net costnya (x/$konversi['nilai'])*hargabeli

        if(empty($konversi)){
          $nilai=1;
        }else{
          if($konversi['nilai'] == 0){
            $nilai=1;
          }else{
            $nilai=$konversi['nilai'];
          }
        }

        if($curqty['hargabeli'] > 0){
          $hargabeli=$curqty['hargabeli'];
        }else{
          $hargabeli=0;
        }
        //$netcost=($p['quantity']/$konversi['nilai'])*$hargabeli;
        if($nilai > 1){
          $netcost=(1/$konversi['nilai'])*$hargabeli;
        }else{
          $netcost=$hargabeli;
        }
        /*if($p['nilaipajak'] == 1){
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
        }*/
      //  $total=(($p['price']-$p['diskon'])*$p['quantity']) + ($pajak*$p['quantity']);
        $penj=array(
          'sales_order_id'=> $data['id'],
          'product_id' => $p['product_id'],
          'quantity'=> $p['quantity'],
          'quantityterima'=> 0,
          'status_pengiriman' => 1,
          'price'=> $p['price'],
          'diskon'=> 0,
          'pajak'=> 0,
          'total'=> $p['total'],
          'hapus'=> 0,
          'net_cost' => $netcost,
          'konversi'  => $konversi['nilai'],
          'statuspajak' => $p['nilaipajak'] ==3 ?2:1
        );

      $this->db->insert('sales_order_product_bahanbaku',$penj);
      //history harga
      $hist=array(
        'price' => $p['price'],
        'product_id'  => $p['product_id'],
        'date_added'  => date('Y-m-d'),
        'customer_id' => $data['customer_id'],
        'so_id' => $data['id']
      );
      $this->db->insert('price_bahanbaku_history',$hist);


    }
    }

  }




  public function cancelPenjualan($id,$alasan_batal){
    $penj=array(
      'status' => 4,
      'alasan_batal' => $alasan_batal.' Dibatalkan Oleh '.$this->user->getName()
    );

    $where=array(
      'id' => $id
    );
    $this->db->update('sales_order_bahanbaku',$penj,$where);
    $this->db->update('sales_order_product_bahanbaku',array('status_pengiriman'=>4),array('sales_order_id'=>$id));
    //kembalikan stok produk
    /*$products=$this->getPenjualanProducts(array('sales_order_id'=> $id));
    foreach($products as $p){
      $this->deleteProduct($p['id']);
    }*/
  }

  public function updatePenjualan($data,$where=array()){
	$this->db->update('sales_order_bahanbaku',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('sales_order_bahanbaku',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order){
		return $this->db->firstdetail('sales_order_bahanbaku',$column,$join,$where,$order);
	}
  public function getPenjualanProducts($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('satuan.name as namasatuan','sales_order_product_bahanbaku.product_id','sales_order_product_bahanbaku.statuspajak','sales_order_product_bahanbaku.id','sales_order_product_bahanbaku.diskon','sales_order_product_bahanbaku.pajak','sales_order_product_bahanbaku.total','bahanbaku.name','sales_order_product_bahanbaku.quantity','sales_order_product_bahanbaku.net_cost','sales_order_product_bahanbaku.price','sales_order_product_bahanbaku.quantityterima');
    $join=array();
    $leftjoin=array();
    $join[]=array(
      'tablename'=> 'bahanbaku',
      'firsttable' => 'sales_order_product_bahanbaku.product_id',
      'secondtable'=> 'bahanbaku.id'
    );
    $leftjoin[]=array(
      'tablename'=> 'satuan',
      'firsttable' => 'bahanbaku.satuan',
      'secondtable'=> 'satuan.id'
    );


    return $this->db->alljoins('sales_order_product_bahanbaku',$column,$join,$leftjoin,$where,array(),0,null);
	}


  public function getListDetail($where,$order,$limit=0,$offset=null){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('sales_order_bahanbaku.status','sales_order_bahanbaku.id as sales_order_id','sales_order_product_bahanbaku.status_pengiriman','sales_order_bahanbaku.no_so','sales_order_bahanbaku.date_added','customer.name as namacustomer','customer.email','customer.telephone','sales_order_product_bahanbaku.product_id','sales_order_product_bahanbaku.id','sales_order_product_bahanbaku.diskon','sales_order_product_bahanbaku.pajak','sales_order_product_bahanbaku.total','bahanbaku.name as namaproduct','sales_order_product_bahanbaku.quantity','sales_order_product_bahanbaku.quantityterima','sales_order_product_bahanbaku.status_pengiriman','sales_order_product_bahanbaku.net_cost','sales_order_product_bahanbaku.price');
    $join=array();
    $join[]=array(
      'tablename'=> 'bahanbaku',
      'firsttable' => 'sales_order_product_bahanbaku.product_id',
      'secondtable'=> 'bahanbaku.id'
    );
    $join[]=array(
      'tablename'=> 'sales_order_bahanbaku',
      'firsttable' => 'sales_order_product_bahanbaku.sales_order_id',
      'secondtable'=> 'sales_order_bahanbaku.id'
    );
    $join[]=array(
      'tablename'=> 'customer',
      'firsttable' => 'sales_order_bahanbaku.customer_id',
      'secondtable'=> 'customer.customer_id'
    );

    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$where['sales_order_bahanbaku.sales']=$this->user->getId();
		}


    return $this->db->alljoin('sales_order_product_bahanbaku',$column,$join,$where,$order,$limit,$offset);
	}
  public function getTotalListDetail($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('sales_order_bahanbaku.id as sales_order_id','sales_order_bahanbaku.no_so','sales_order_bahanbaku.date_added','customer.name as namacustomer','customer.email','customer.telephone','sales_order_product_bahanbaku.product_id','sales_order_product_bahanbaku.id','sales_order_product_bahanbaku.diskon','sales_order_product_bahanbaku.pajak','sales_order_product_bahanbaku.total','bahanbaku.name as namaproduct','sales_order_product_bahanbaku.quantity','sales_order_product_bahanbaku.quantityterima','sales_order_product_bahanbaku.status_pengiriman','sales_order_product_bahanbaku.net_cost','sales_order_product_bahanbaku.price');
    $join=array();
    $join[]=array(
      'tablename'=> 'bahanbaku',
      'firsttable' => 'sales_order_product_bahanbaku.product_id',
      'secondtable'=> 'bahanbaku.id'
    );
    $join[]=array(
      'tablename'=> 'sales_order_bahanbaku',
      'firsttable' => 'sales_order_product_bahanbaku.sales_order_id',
      'secondtable'=> 'sales_order_bahanbaku.id'
    );
    $join[]=array(
      'tablename'=> 'customer',
      'firsttable' => 'sales_order_bahanbaku.customer_id',
      'secondtable'=> 'customer.customer_id'
    );
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$where['sales_order_bahanbaku.sales']=$this->user->getId();
		}
    $total=$this->db->alljoin('sales_order_product_bahanbaku',$column,$join,$where,array(),0,null);
    return count($total);
	}
  public function getPenjualanProduct($where){
		return $this->db->first('sales_order_product_bahanbaku',$where);
	}
	public function getPenjualans($column=array(),$join=array(),$where=array(),$order,$limit,$offset){
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$where['sales_order_bahanbaku.sales']=$this->user->getId();
		}
		return $this->db->alljoin('sales_order_bahanbaku',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where){
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$where['sales_order_bahanbaku.sales']=$this->user->getId();
		}
		return $this->db->count('sales_order_bahanbaku',$where);
	}


}
?>
