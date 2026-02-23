<?php
class ModelSaleSalesorder extends Model {
   // baru upload 17 Februari 2020
   public function tutupsobelumdikirim($id){
    $hasil=array();
    $sql="SELECT * FROM sales_order_product WHERE sales_order_id='$id' and quantityterima=0 and status_pengiriman=1 ";
    $d =$this->db->query($sql);
    return $d->rows;
  }
  // baru upload 13 Februari 2020
  public function tutupso($id){
    $hasil=array();
    $sql="SELECT * FROM sales_order_product WHERE sales_order_id='$id' and quantity<>quantityterima and status_pengiriman=2 ";
    $d =$this->db->query($sql);
    return $d->rows;
  }
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

	  $this->db->query("UPDATE ".DB_PREFIX."sales_order_product SET quantityterima='".$qtyf."',status_pengiriman='".$status."' WHERE id='".$id."'");
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
      'date_modified' =>date('Y-m-d'),
      'sales'  => $data['sales'],
      'customer_id'  => $data['customer_id'],
      'status' => $status,
      'pengiriman'  => $data['pengiriman'],
      'hapus' => 0,
      'user_id'  => $this->user->getId(),
      'address_id' =>$data['address_id'],
      'jenispenjualan'  => empty($data['jenispenjualan'])?1:$data['jenispenjualan'],
      'jenisstok' => empty($data['jenisstok'])?1:$data['jenisstok'],
      //'jenisorder'  => $data['jenisorder'],
      //'statustabung'  => $data['statustabung'],
      'tttk'  => empty($data['tttk'])?0:$data['tttk'],
      'sub_total' => $data['sub_total'],
      'diskon'  =>empty($data['diskon'])?0:$data['diskon'],
      'pajak' => $data['pajak'],
      'total' => $data['total'],
      'gudang_id'  => $data['gudang_id'],
	    'catatan'  => $this->db->escape($data['catatan']),
      'usia'  => empty($data['usia'])?0:$data['usia'],
      'metode_pembayaran' => $data['metode_pembayaran'],
      'marketplace'  => empty($data['marketplace'])?0:$data['marketplace'],
      'keteranganpajak'  =>empty($data['keteranganpajak'])?'-':$data['keteranganpajak'],
      //'pembulatan'  => $data['pembulatan']
    );
    $this->db->insert('sales_order',$penj);
    $id=$this->db->getlastId();
    $data['id']=$id;
    $no_so="SO-".$id."-".date('Y')."-".date("m")."-".$this->user->getId();
    $this->db->update('sales_order',array('no_so' => $no_so),array('id' => $id));
    $this->addPenjualanProduct($data);


    return $id;

  }
  public function addPenjualanProduct($data){
    $total=0;
    $diskon=0;
    $pembulatan=0;
    $persetujuanharga=0;

  //  $penjualan=$this->getPenjualan(array('id' => $data['id']));

  $this->load->model('gudang/product');
    foreach($data['product'] as $p){
      if(!empty($p['product_id'])){
			//getnetcost
			//$net=$this->db->first('product_toko_pameran',array('gudang_id'  => $penjualan['pameran_id'],'product_id'  => $p['product_id']));

			$curqty=$this->model_gudang_product->getProduct($p['product_id'],$data['gudang_id']);
			//$pajak=($p['price']-$p['diskon'])*0.1;
			//$total=(($p['price']-$p['diskon'])*$p['quantity']) + ($pajak*$p['quantity']);
			if($p['nilaipajak'] == 1){
			  //$p['pajak']=round(($p['price'])*0.1);
			  $p['totalpajak']=floor(($p['price'] * $p['quantity'])*0.1);
			  $p['pajak']=floor($p['totalpajak']/$p['quantity']);
        $p['pembulatan']=0;
        $hargapluspajak=round(($p['price']*0.1) + $p['price']);
        $toleransi=round($p['harga_terendah'])-round($hargapluspajak);
        if($toleransi>2){
          $persetujuanharga=1;
        } else{
          $persetujuanharga=0;
        }
			}
			if($p['nilaipajak'] == 2){
			  $np=floor(((100/110)*$p['price']));
			  $totalpajak=floor(0.1*($np*$p['quantity']));
			  $p['totalpajak']=$totalpajak;
			  $p['pajak']=floor($p['totalpajak']/$p['quantity']);
			  $p['pembulatan']=$p['total'] - (($np*$p['quantity'])+($p['pajak']*$p['quantity']));
        $p['price']=$np;
        $hargatanpapajak=round($p['price']+$p['pajak']);
        $toleransitp=round($p['harga_terendah'])-round($hargatanpapajak);
        //if(round($hargapluspajak)>=$p['harga_terendah']){
        if($toleransitp>2){
          $persetujuanharga=1;
        } else{
          $persetujuanharga=0;
        }
			}
			if($p['nilaipajak'] == 3){
			  $p['totalpajak']=0;
			  $p['pajak'] = 0;
			  $p['pembulatan'] = 0;
			}

			if($p['jenistabung'] == 3){
			  $p['tabung_id']=0;
			}
      $pembulatan += $p['pembulatan'];
      /*
      $hargapluspajak=round(($p['price']*0.1) + $p['price']);
      if(round($hargapluspajak)>=$p['harga_terendah']){
        $persetujuanharga=0;
      } else if(round($hargapluspajak)<$p['harga_terendah']){
        $persetujuanharga=1;
      } 
      */
      
      

			$penj=array(
			  'sales_order_id' => $data['id'],
			  'product_id'  => $p['product_id'],
			  'tabung_id'  => $p['tabung_id'],
			  'quantity' => $p['quantity'],
			  'quantityterima' => 0,
			  'status_pengiriman'  => 1,
			  'price' => $p['price'],
			  'diskon' => $p['diskonprods'],
			  'pajak' => $p['pajak'],
			  'total' => $p['total'],
			  'totalpajak'  => $p['totalpajak'],
			  'jenispenjualan'  => $data['jenisstok'],
			  //'jenistabung' => isset($p['jenistabung'])?$p['jenistabung']:0,
			  'pembulatan'  => $p['pembulatan'],
			  'hapus' => 0,
        'net_cost'  => empty($curqty['net_cost'])?$p['net_cost']:$curqty['net_cost'],
        'harga_terendah' => empty($p['harga_terendah'])?0:$p['harga_terendah'],
        'persetujuanharga'=>$persetujuanharga,
			);

		  $this->db->insert('sales_order_product',$penj);

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
    $this->db->update('sales_order',array('pembulatan'=>$pembulatan),array('id'=>$data['id']));

  }

  public function deleteProduct($id){
    $prod=$this->getPenjualanProduct(array('penjualan_pameran_product_id' => $id));
    $penj=$this->getPenjualan(array('penjualan_pameran_id' => $prod['penjualan_pameran_id']));

    $total = $penj['total'] - ($prod['price'] * $prod['qty']);
    $diskon = $penj['diskon'] - ($prod['discount'] * $prod['qty']);

    $updatepenj=array(
      'total' => $total,
      'diskon'  => $diskon,
      //'voucher' => $voucher
    );

    $where=array(
      'penjualan_pameran_id'  => $penj['penjualan_pameran_id']
    );

    $this->db->update('penjualan_toko',$updatepenj,$where);

    $this->db->delete('penjualan_toko_product',array('penjualan_pameran_product_id' => $id));

    //update stok
    $this->load->model('pamerantoko/product');
    $curqty=$this->model_pamerantoko_product->getProduct($prod['product_id'],$penj['pameran_id'],2);

    $update=$this->model_pamerantoko_product->updateQty($prod['product_id'],$penj['pameran_id'],$prod['qty'],2,1);

    //kartustok
    $this->load->model('pamerantoko/kartustok');

    $kartustok=array(
      'product_id'	=> $prod['product_id'],
      'product_name'	=> $curqty['name'],
      'tgl'	=> date('Y-m-d H:i:s',time()),
      'stokkeluar'	=> 0,
      'stokmasuk'	=> $prod['qty'],
      'ket'	=> 'Pembatalan penjualan harian',
      'saldo'	=> $update,
      'quantityawal'	=> $curqty['qty'],
      'invoice'	=> $penj['penjualan_pameran_id'],
      'pameran_id'	=> $penj['pameran_id'],
      'type'	=> 3
    );

    $this->model_pamerantoko_kartustok->addKartuStok('kartustok_produk_toko',$kartustok);
    $act=array(
      'activity'	=> 'Hapus produk terjual '.$penj['penjualan_pameran_id'],
      'menu'	=> 'Penjualan Harian Toko'
    );
    $this->user->addUserActivity($act);
  }



  public function cancelPenjualan($id,$alasan_batal){
    $penj=array(
      'status'  => 4,
      'alasan_batal'  => $alasan_batal
    );

    $where=array(
      'id'  => $id
    );
    $this->db->update('sales_order',$penj,$where);
    //kembalikan stok produk

    $penj=array(
      'status_pengiriman'  => 4,

    );

    $where=array(
      'sales_order_id'  => $id
    );
    $this->db->update('sales_order_product',$penj,$where);

    $this->load->model('sale/penjualan');
    $this->load->model('sale/invoice');
    //getPenjualans($column=array(),$join=array(),$where=array(),$order,$limit,$offset)
    $penjualans=$this->model_sale_penjualan->getPenjualan(array(),array(),array('no_so'=>$id),array(),0,null);
    foreach($penjualans as $p){
      //if($p['status'] != 3){
        $this->model_sale_penjualan->cancelPenjualan($p['id']);
      //}
    }

    $inv=$this->db->all('invoice',array('jenisinvoice' => 1,'referensi'=>$id,'jenispenjualan'=>1));
    if(!empty($inv)){
      if($inv['status'] == 1){
        $this->model_sale_invoice->voidInvoice($inv['id']);
      }
    }

    $inv2=$this->db->all('invoice',array('jenisinvoice' => 2,'referensi'=>$id,'jenispenjualan'=>1));
    if(!empty($inv2)){
      if($inv2['status'] == 1){
        $this->model_sale_invoice->voidInvoice($inv2['id']);
      }
    }

  }

  public function updatePenjualan($data,$where=array()){
	   $this->db->update('sales_order',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('sales_order',$where);
	}
	public function getPenjualantest($where){
		$sql="SELECT * FROM sales_order where order_id=5461 LIMIT 1 ";
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order){
		return $this->db->firstdetail('sales_order',$column,$join,$where,$order);
	}
  public function getPenjualanProducts($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('sales_order_product.product_id','sales_order_product.totalpajak','sales_order_product.pembulatan','satuan.name as namasatuan','sales_order_product.id','sales_order_product.diskon','sales_order_product.pajak','sales_order_product.total','sales_order_product.jenisref','sales_order_product.referensi','sales_order_product.referensi2','product.name','sales_order_product.quantity','sales_order_product.net_cost','sales_order_product.price','sales_order_product.tabung_id','sales_order_product.quantityterima','tabung_mp.no_tabung','sales_order_product.harga_terendah');
    $join=array();
    $join[]=array(
      'tablename' => 'product',
      'firsttable'  => 'sales_order_product.product_id',
      'secondtable' => 'product.product_id'
    );
    if(isset($where['product_gudang.gudang_id'])){
      $column[]='product_gudang.quantity as quantitymax';
      $join[]=array(
        'tablename' => 'product_gudang',
        'firsttable'  => 'sales_order_product.product_id',
        'secondtable' => 'product_gudang.product_id'
      );
    }

    $leftjoin=array();
    $leftjoin[]=array(
      'tablename' => 'tabung_mp',
      'firsttable'  => 'sales_order_product.tabung_id',
      'secondtable' => 'tabung_mp.id'
    );
    $leftjoin[]=array(
      'tablename' => 'satuan',
      'firsttable'  => 'product.satuan',
      'secondtable' => 'satuan.id'
    );
    //$where['product_gudang.gudang_id']

    return $this->db->alljoins('sales_order_product',$column,$join,$leftjoin,$where,array(),0,null);
	}


  public function getListDetail($where,$order,$limit=0,$offset=null){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('sales_order.id as sales_order_id','sales_order.no_so','sales_order.alasan_batal','sales_order.status','sales_order.gudang_id','gudang.nama as namagudang','sales_order.date_added','customer.name as namacustomer','customer.email','customer.telephone','sales_order_product.product_id','sales_order_product.id','sales_order_product.diskon','sales_order_product.pajak','sales_order_product.total','product.name as namaproduct','sales_order_product.quantity','sales_order_product.quantityterima','sales_order_product.status_pengiriman','sales_order_product.net_cost','sales_order_product.price','sales_order_product.harga_terendah','sales_order.date_modified','sales_order_product.persetujuanharga');
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
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$where['sales_order.sales']=$this->user->getId();
		}

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
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$where['sales_order.sales']=$this->user->getId();
		}

    $total=$this->db->alljoin('sales_order_product',$column,$join,$where,array(),0,null);
    return count($total);
	}
  public function getPenjualanProduct($where){
		return $this->db->first('sales_order_product',$where);
	}
	public function getPenjualans($column=array(),$join=array(),$where=array(),$order,$limit,$offset){
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$where['sales_order.sales']=$this->user->getId();
		}
		return $this->db->alljoin('sales_order',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where){
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$where['sales_order.sales']=$this->user->getId();
		}
		return $this->db->count('sales_order',$where);
	}
  public function updatePenjualanProduct($data,$where=array()){
	   $this->db->update('sales_order_product',$data,$where);
	}

 public function getSoTanpaSj($customer_id,$gudang_id,$bulan_so,$tahun_so){
    $column=array('sales_order.id as sales_order_id','COALESCE(sales_order.usia,0) as usia','sales_order.no_so','sales_order.status as status_so','sales_order.jenispenjualan','sales_order.status','sales_order.gudang_id','gudang.nama as namagudang','sales_order.date_added','customer.name as namacustomer','customer.email','customer.telephone','product.name as namaproduct','sales_order_product.*');
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
    if(isset($gudang_id)){
      $column[]='product_gudang.quantity as quantitymax';
      $join[]=array(
        'tablename' => 'product_gudang',
        'firsttable'  => 'sales_order_product.product_id',
        'secondtable' => 'product_gudang.product_id'
      );
    }
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

    $where=array(
      'sales_order.customer_id' => $customer_id,
      'sales_order.gudang_id' => $gudang_id,
      'product_gudang.gudang_id' => $gudang_id,
      //'sales_order_product.status_pengiriman'  => array('<>',3),
      'sales_order_product.status_pengiriman'  => array('IN',('1,2')),
      'sales_order.status'  => array('NOT IN',('4,5')),
      'sales_order.hapus'  => array('=',0),
      'EXTRACT(MONTH FROM sales_order.date_added)'=> $bulan_so,
      'EXTRACT(YEAR FROM sales_order.date_added)'  => $tahun_so
      //'sales_order.status'  => array('<>',4),
    //  'sales_order.status'  => array('<>',1),
      //'sales_order_product'
    );
    if($custdata != 1){
			$where['sales_order.sales']=$this->user->getId();
		}

    $this->load->model('catalog/tabungmp');
    $result=$this->db->alljoin('sales_order_product',$column,$join,$where,array('sales_order.id'=>'ASC'),0,null);
    $hasil=array();

    foreach($result as $r){
      //if($r['status_so'] != 3){

      $r['tabung']="";
        if($r['jenispenjualan'] == 1){
          if($r['tabung_id'] > 0){
            $tabung=$this->model_catalog_tabungmp->getTabung($r['tabung_id']);
            $r['tabung']=$tabung['no_tabung'];
          }
        }/*else{

        }*/
        $hasil[]=$r;
      //}
    }
    $this->log->write('Hasil ' . json_encode($hasil));
    return $hasil;

  }

  public function getSoTanpaProforma($customer_id,$gudang_id){
    $column=array('sales_order.id as sales_order_id','COALESCE(sales_order.usia,0) as usia','sales_order.no_so','sales_order.status as status_so','sales_order.jenispenjualan','sales_order.status','sales_order.gudang_id','gudang.nama as namagudang','sales_order.date_added','customer.name as namacustomer','customer.email','customer.telephone','product.name as namaproduct','sales_order_product.*','sales_order.metode_pembayaran');
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
    if(isset($gudang_id)){
      $column[]='product_gudang.quantity as quantitymax';
      $join[]=array(
        'tablename' => 'product_gudang',
        'firsttable'  => 'sales_order_product.product_id',
        'secondtable' => 'product_gudang.product_id'
      );
    }
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

    $where=array(
      'sales_order.customer_id' => $customer_id,
      'sales_order.gudang_id' => $gudang_id,
      'product_gudang.gudang_id' => $gudang_id,
      'sales_order_product.status_pengiriman'  => array('<>',4),
      'sales_order.status'  => array('<>',4),
      //'sales_order.status'  => array('<>',1),
      //'sales_order_product'
    );
    if($custdata != 1){
			$where['sales_order.sales']=$this->user->getId();
		}

    $this->load->model('catalog/tabungmp');
    $result=$this->db->alljoin('sales_order_product',$column,$join,$where,array('sales_order.id'=>'DESC'),0,null);
    $hasil=array();

    foreach($result as $r){
      //if($r['status_so'] != 3){

      $r['tabung']="";
        if($r['jenispenjualan'] == 1){
          if($r['tabung_id'] > 0){
            $tabung=$this->model_catalog_tabungmp->getTabung($r['tabung_id']);
            $r['tabung']=$tabung['no_tabung'];
          }
        }/*else{

        }*/
        $hasil[]=$r;
      //}
    }
    $this->log->write('Hasil ' . json_encode($hasil));
    return $hasil;

  }


 /* public function getSoSudahdikirim($customer_id,$gudang_id){
    $column=array('sales_order.id as sales_order_id','COALESCE(sales_order.usia,0) as usia','sales_order.no_so','sales_order.status as status_so','sales_order.jenispenjualan','sales_order.status','sales_order.gudang_id','gudang.nama as namagudang','sales_order.date_added','customer.name as namacustomer','customer.email','customer.telephone','product.name as namaproduct','sales_order_product.*');
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
    if(isset($gudang_id)){
      $column[]='product_gudang.quantity as quantitymax';
      $join[]=array(
        'tablename' => 'product_gudang',
        'firsttable'  => 'sales_order_product.product_id',
        'secondtable' => 'product_gudang.product_id'
      );
    }
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

    $curyear=date('Y');
    $curmonth=date('m');

    if($curmonth > 4){
      $hitungbulan=$curmonth - 3;
    }else{
      $hitungbulan=1;
    }

    $where=array(
      'sales_order.customer_id' => $customer_id,
      'sales_order.gudang_id' => $gudang_id,
      'product_gudang.gudang_id' => $gudang_id,
      'sales_order_product.quantityterima'  => array('>',0),
    
    );
    if($custdata != 1){
			$where['sales_order.sales']=$this->user->getId();
		}

    $this->load->model('catalog/tabungmp');
    $result=$this->db->alljoin('sales_order_product',$column,$join,$where,array('sales_order.id'=>'ASC'),0,null);
    $hasil=array();

    foreach($result as $r){
      //if($r['status_so'] != 3){

      $r['tabung']="";
        if($r['jenispenjualan'] == 1){
          if($r['tabung_id'] > 0){
            $tabung=$this->model_catalog_tabungmp->getTabung($r['tabung_id']);
            $r['tabung']=$tabung['no_tabung'];
          }
        }/*else{

        }
        $hasil[]=$r;
      //}
    }
    $this->log->write('Hasil ' . json_encode($hasil));
    return $hasil;

  }*/
  public function getSoSudahdikirim($no_so,$customer_id,$gudang_id){
  $column=array('sales_order.id as sales_order_id','COALESCE(sales_order.usia,0) as usia','sales_order.no_so','sales_order.status as status_so','sales_order.jenispenjualan','sales_order.status','sales_order.gudang_id','gudang.nama as namagudang','sales_order.date_added','customer.name as namacustomer','customer.email','customer.telephone'/*,'product.name as namaproduct','sales_order_product.*'*/);
    $join=array();
    /*$join[]=array(
      'tablename' => 'product',
      'firsttable'  => 'sales_order_product.product_id',
      'secondtable' => 'product.product_id'
    );
    $join[]=array(
      'tablename' => 'sales_order',
      'firsttable'  => 'sales_order_product.sales_order_id',
      'secondtable' => 'sales_order.id'
    );*/
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
    /*if(isset($gudang_id)){
      $column[]='product_gudang.quantity as quantitymax';
      $join[]=array(
        'tablename' => 'product_gudang',
        'firsttable'  => 'sales_order_product.product_id',
        'secondtable' => 'product_gudang.product_id'
      );
    }*/
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

   /* $curyear=date('Y');
    $curmonth=date('m');

    if($curmonth > 4){
      $hitungbulan=$curmonth - 3;
    }else{
      $hitungbulan=1;
    }**/

    $where=array(
      'sales_order.no_so' => array('LIKE',$no_so),
      'sales_order.customer_id' => $customer_id,
      'sales_order.gudang_id' => $gudang_id,
      //'product_gudang.gudang_id' => $gudang_id,
      //'sales_order_product.quantityterima'  => array('>',0),
      //'sales_order.date_added'  => array('>=',$curyear.'-'.$hitungbulan.'-01') /* ini klo diaktifin, yg so thun 2019 gk muncul */
    //  'sales_order.status'  => array('<>',1),
      //'sales_order_product'
    );
    if($custdata != 1){
			$where['sales_order.sales']=$this->user->getId();
		}

    $this->load->model('catalog/tabungmp');
    $result=$this->db->alljoin('sales_order',$column,$join,$where,array('sales_order.id'=>'ASC'),0,null);
    $hasil=array();

    /*foreach($result as $r){
      //if($r['status_so'] != 3){

      $r['tabung']="";
        if($r['jenispenjualan'] == 1){
          if($r['tabung_id'] > 0){
            $tabung=$this->model_catalog_tabungmp->getTabung($r['tabung_id']);
            $r['tabung']=$tabung['no_tabung'];
          }
        }
        $hasil[]=$r;
      //}
    }*/
    $this->log->write('Hasil ' . json_encode($result));
    return $result;

  }
  
   // baru 13 September 2019
   public function getSoTanpaSjnew($data,$customer_id,$gudang_id,$limit,$offset){
    $column=array('sales_order.id as sales_order_id','COALESCE(sales_order.usia,0) as usia','sales_order.no_so','sales_order.status as status_so','sales_order.jenispenjualan','sales_order.status','sales_order.gudang_id','gudang.nama as namagudang','sales_order.date_added','customer.name as namacustomer','customer.email','customer.telephone','product.name as namaproduct','sales_order_product.*','sales_order.catatan');
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
    if(isset($gudang_id)){
      $column[]='product_gudang.quantity as quantitymax';
      $join[]=array(
        'tablename' => 'product_gudang',
        'firsttable'  => 'sales_order_product.product_id',
        'secondtable' => 'product_gudang.product_id'
      );
    }
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

    $where=array(
	  'sales_order.id'	=> empty($data['sales_order.id'])?array('>=',1):$data['sales_order.id'],
      'sales_order.customer_id' => $customer_id,
      'sales_order.gudang_id' => $gudang_id,
      'product_gudang.gudang_id' => $gudang_id,
      'sales_order_product.status_pengiriman'  => array('<>',3),
      'sales_order.status'  => array('<>',4),
	  'sales_order.date_added'	=> empty($data['sales_order.date_added'])?array('>','1901-01-01'):$data['sales_order.date_added'],
      //'sales_order_product'
    );
    if($custdata != 1){
			$where['sales_order.sales']=$this->user->getId();
		}
	

    $result=$this->db->alljoin('sales_order_product',$column,$join,$where,array('sales_order.id'=>'DESC'),$limit,$offset);
    $hasil=array();

    foreach($result as $r){
      //if($r['status_so'] != 3){

      $r['tabung']="";
        if($r['jenispenjualan'] == 1){
          if($r['tabung_id'] > 0){
            $tabung=$this->model_catalog_tabungmp->getTabung($r['tabung_id']);
            $r['tabung']=$tabung['no_tabung'];
          }
        }/*else{

        }*/
        $hasil[]=$r;
      //}
    }
    $this->log->write('Hasil ' . json_encode($hasil));
    return $hasil;

  }
  
  public function totalgetSoTanpaSjnew($data,$customer_id,$gudang_id,$limit,$offset){
    $column=array('sales_order.id as sales_order_id','COALESCE(sales_order.usia,0) as usia','sales_order.no_so','sales_order.status as status_so','sales_order.jenispenjualan','sales_order.status','sales_order.gudang_id','gudang.nama as namagudang','sales_order.date_added','customer.name as namacustomer','customer.email','customer.telephone','product.name as namaproduct','sales_order_product.*');
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
    if(isset($gudang_id)){
      $column[]='product_gudang.quantity as quantitymax';
      $join[]=array(
        'tablename' => 'product_gudang',
        'firsttable'  => 'sales_order_product.product_id',
        'secondtable' => 'product_gudang.product_id'
      );
    }
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

    $where=array(
	  'sales_order.id'	=> empty($data['sales_order.id'])?array('>=',1):$data['sales_order.id'],
      'sales_order.customer_id' => $customer_id,
      'sales_order.gudang_id' => $gudang_id,
      'product_gudang.gudang_id' => $gudang_id,
      'sales_order_product.status_pengiriman'  => array('<>',3),
      'sales_order.status'  => array('<>',4),
	  'sales_order.date_added'	=> empty($data['sales_order.date_added'])?array('>','1901-01-01'):$data['sales_order.date_added'],
      //'sales_order_product'
    );
    if($custdata != 1){
			//$where['sales_order.sales']=$this->user->getId();
		}
	

    $result=$this->db->alljoin('sales_order_product',$column,$join,$where,array('sales_order.id'=>'DESC'),0,null);
    $hasil=array();

    foreach($result as $r){
      //if($r['status_so'] != 3){

      $r['tabung']="";
        if($r['jenispenjualan'] == 1){
          if($r['tabung_id'] > 0){
            $tabung=$this->model_catalog_tabungmp->getTabung($r['tabung_id']);
            $r['tabung']=$tabung['no_tabung'];
          }
        }/*else{

        }*/
        $hasil[]=$r;
      //}
    }
    $this->log->write('Hasil ' . json_encode($hasil));
    return $hasil;

  }


}
?>
