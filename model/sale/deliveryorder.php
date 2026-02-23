<?php
class ModelSaleDeliveryorder extends Model {
  /*
  Status
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
  public function addPenjualan($data){

    $this->load->model('sale/customer');
   /* if($data['pengiriman'] == 1){
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
        $data['address_id']=$this->model_sale_customer->addAddress($add,$data['customer_id']);
      }

    }*/

    $penj=array(
      'date_added' => isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s'),
      'sopir'  => empty($data['sopir'])?0:$data['sopir'],
      'no_pol'  => $data['no_pol'],
      'status' => 1,
      'pengiriman'  => $data['pengiriman'],
      'hapus' => 0,
      'user_id'  => $this->user->getId(),
        'cetak' => 0,
      'gudang_id' => $data['gudang_id'],
      'totaltabung' => $data['totaltabung'],
      'totalsj' => $data['totalsj']
    );

    /*
    1. dikirim
    2. diterima sebagian
    3. diterima

    

    */

    $this->db->insert('deliveryorder',$penj);
    $id=$this->db->getlastId();
    $data['id']=$id;
    $no_invoice="DO-".$id."-".date('Y')."-".date("m")."-".$this->user->getId();
    $data['no_do']=$no_invoice;

    $this->db->update('deliveryorder',array('no_do' => $no_invoice),array('id' => $id));
    $this->addPenjualanProduct($data);
    $this->addPenjualanTabung($data);
    /*if(!empty($data['tabungs'])){
      $this->addPenjualanTabung($data);
    }*/

    
    return $id;

  }

  public function batalkan($order_id){
    //hapus jurnal
    $this->load->model('keuangan/jurnal');
    $this->model_keuangan_jurnal->updateJurnalumum(array('hapus'=>0),array('ref'=>$order_id));
    //update stok

    //update status
  }

 
  public function addPenjualanProduct($data){
    $total=0;
    $diskon=0;

  //  $penjualan=$this->getPenjualan(array('id' => $data['id']));

  $this->load->model('sale/salesorder');
  
    $netcostproduksi=0;
    $netcostdagang=0;
    foreach($data['product'] as $p){
      //if(!empty($p['product_id']) & $p['quantity'] > 0){ // sementara dimatiin biar bisa minus
        if(!empty($p['sj_id'])){
        
          
          $penj=array(
            'do_id' => $data['id'],
            'sj_id' => $p['sj_id'],
             'date_added'  => date('Y-m-d H:i:s'),
            'user_id' => $this->user->getId(),
            'hapus' => 0
          );

          

        $this->db->insert('deliveryorder_product',$penj);
        
        $sj=$this->db->first('penjualan', array('id'=>$p['sj_id']));
       
       $this->db->update('penjualan',array('do_id'=>$data['id']),array('id'=>$p['sj_id']));
      


    
      }
    }


     
     

  }

  public function addPenjualanTabung($data){
    $this->load->model('catalog/tabungmp');
    $this->load->model('catalog/kartustoktabungmp');
    foreach($data['tabung'] as $p){
        if(!empty($p['tabung_id'])){
          $penj=array(
            'do_id' => $data['id'],
            'tabung_id' => $p['tabung_id'],
            'tutup' => $p['tutup'],
            'keterangan'  => $this->db->escape($p['keterangan']),
            'hapus' =>0,
            'status'  => 1
          );

          /*status
          1. belum diterima
          2. sudah diterima
          */

          $this->db->insert('deliveryorder_tabung',$penj);

          //update tanggal pengembalian
        /*     $tabung=$this->model_catalog_tabungmp->getTabung($p['tabung_id']);
          $tab=array(
            'status'	=> 4,
            'ukuran_tabung'	=> $tabung['ukuran_tabung'],
            'tglpembelian'	=> $tabung['tglpembelian'],
            'hargabeli'	=> $tabung['hargabeli'],
            'kelompok_aset'	=> $tabung['kelompok_aset'],
            'date_added'	=> $tabung['date_added'],
            'date_modified'	=> date('Y-m-d H:i:s',time())
          );
          $this->model_catalog_tabungmp->editTabung($p['tabung_id'],$tab);
          $this->db->update('tabung_mp',array('status' => 6,'date_modified'=>date('Y-m-d H:i:s',time())),array('id'=>$p['tabung_id']));
          //  $penj=$this->getPenjualanDetail(array('id'=>$penj['id']));
            $kartustok=array(
              'tabung_id'	=> $p['tabung_id'],
              'tglpeminjaman'	=> $data['date_added'],
              'tglpengembalian'	=> '1901-01-01',
              'tglisiulang'	=> $data['date_added'],
              'customer_id'	=> $data['customer_id'],
              'invoice'	=> $data['no_do'],
              'ket'	=> 'Penyewaan tabung no '.$data['no_do'],
              'biayasewa'	=> 0,
              'tabel_ref' => 'sale/deliveryorder',
              'idref' => $data['id'],
              'jenistransaksi'  =>1
            );

            $this->model_catalog_kartustoktabungmp->addKartuStok($kartustok);
            */
          //input kartustok
          //if($tabung['pemilik'] == 1){

           

      }
    }
  }

  

  public function terimaTabung($data,$id){
    $do=array(
      'userterima'  => $this->user->getId(),
      'keteranganpenerima'  => $this->db->escape($data['keteranganpenerima']),
      'totaltabungterima' => $data['totaltabungterima'],
      'date_modified' => date('Y-m-d H:i:s'),
      'tglterima' => $data['tglterima'],
      'penerima'  => $this->db->escape($data['ppenerima']),
      'status'  => 2
    );

    $this->db->update('deliveryorder',$do,array('id'=>$id));
    foreach($data['tabung'] as $t){
      $this->db->update('deliveryorder_tabung',array('status'=>$t['status']),array('id'=>$t['id']));
    }
  }



  public function cancelPenjualan($id){
    //cek invoice
    $penj=$this->getPenjualan(array('id'=>$id));
    $products=$this->getPenjualanProducts(array('deliveryorder_product.do_id'=>$id));
    $tabungs=$this->getPenjualanTabungs(array('deliveryorder_tabung.do_id'=>$id));
  if($penj['status'] == 1 /*& ($inv['status'] == 1 | $inv['status'] == 4)*/){
    $this->load->model('catalog/product');
    $this->load->model('sale/penjualan');
     $this->load->model('gudang/product');
    $this->load->model('catalog/kartustoktabungmp');
    
   
    
      $penj=array(
        'status'  => 4
      );

      $where=array(
        'id'  => $id
      );
      $this->db->update('deliveryorder',$penj,$where);
      $penj=$this->getPenjualan(array('id'=>$id));

      $this->db->update('deliveryorder_tabung',array('status'=>4),array('do_id'=>$id));
      foreach($tabungs as $t){
        if($t['status'] == 1){
        
         /* $this->db->update('tabung_mp',array('status' => 1,'date_modified'=>date('Y-m-d H:i:s',time())),array('id'=>$t['tabung_id']));
       
          $kartustok=array(
            'tabung_id'	=> $t['tabung_id'],
            'tglpeminjaman'	=> date('Y-m-d'),
            'tglpengembalian'	=> '1901-01-01',
            'tglisiulang'	=> date('Y-m-d'),
            'customer_id'	=> $penj['customer_id'],
            'invoice'	=> $penj['no_do'],
            'ket'	=> 'Pembatalan Penyewaan tabung no '.$penj['no_do'],
            'biayasewa'	=> 0,
            'tabel_ref' => 'sale/deliveryorder',
            'idref' => $id,
            'jenistransaksi'  =>2
          );

          $this->model_catalog_kartustoktabungmp->addKartuStok($kartustok);*/
        }
      }

      $this->db->update('penjualan',array('do_id'=>0),array('do_id'=>$id));
      /*foreach($products as $p){
       $penjualanproduct=$this->db->first('penjualan',array('do_id'=>$p['sjproduct_id']));
       $qtydo=$penjualanproduct['quantitydo'] - $p['quantitypesan'];

        $this->db->update('penjualan_product',array('quantitydo'=> $qtydo),array('id'=>$p['sjproduct_id']));

        
      }*/

     
      return true;
    
  }


  }

  public function updatePenjualan($data,$where=array()){
	$this->db->update('deliveryorder',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('deliveryorder',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order=array()){
		return $this->db->firstdetail('deliveryorder',$column,$join,$where,$order);
	}
  public function getPenjualanProducts($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('deliveryorder_product.sj_id','deliveryorder_product.do_id','deliveryorder_product.id','penjualan.no_sj');
    $join=array();
   
    $leftjoin=array();
    
    
    $leftjoin[]=array(
      'tablename' => 'penjualan',
      'firsttable'  => 'deliveryorder_product.sj_id',
      'secondtable' => 'penjualan.id'
    );

    return $this->db->alljoins('deliveryorder_product',$column,$join,$leftjoin,$where,array(),0,null);
  }
  public function getPenjualanTabungs($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('deliveryorder_tabung.tabung_id','deliveryorder_tabung.id','deliveryorder_tabung.id','deliveryorder_tabung.status','tabung_mp.no_tabung','deliveryorder_tabung.tutup','product_options.name');
    $join=array();
    $join[]=array(
      'tablename' => 'tabung_mp',
      'firsttable'  => 'deliveryorder_tabung.tabung_id',
      'secondtable' => 'tabung_mp.id'
    );
    $join[]=array(
      'tablename' => 'product_options',
      'firsttable'  => 'tabung_mp.ukuran_tabung',
      'secondtable' => 'product_options.product_options_id'
    );

    return $this->db->alljoin('deliveryorder_tabung',$column,$join,$where,array(),0,null);
	}
  public function getPenjualanProduct($where){
		return $this->db->first('deliveryorder_product',$where);
	}
	public function getPenjualans($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order,$limit,$offset){
    $this->load->model('user/user');
		/*$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$where['penjualan.sales']=$this->user->getId();
		}*/
		return $this->db->alljoins('deliveryorder',$column,$join,$leftjoin,$where,$order,$limit,$offset);
	}
  public function getPenjualanDetailss($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order,$limit,$offset){
		return $this->db->alljoin('deliveryorder',$column,$join,$leftjoin,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where,$join=array(),$leftjoin=array()){
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$where['deliveryorder.sales']=$this->user->getId();
		}
		return $this->db->countAll('deliveryorder',$where,$join,$leftjoin);
	}

  

  public function getPenjualanKernets($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('penjualan_kernet.id','penjualan_kernet.pegawai_id','users.firstname');
    $join=array();
    $join[]=array(
      'tablename' => 'users',
      'firsttable'  => 'penjualan_kernet.pegawai_id',
      'secondtable' => 'users.user_id'
    );

    return $this->db->alljoin('penjualan_kernet',$column,$join,$where,array(),0,null);
	}

  /*public function getTotalKirim($id){
    $total=$this->db->query("SELECT ");
  }*/
  public function getTabungs($data = array()) {
		$sql = "SELECT dt.id as doproduct_id,t.id,no_tabung,t.status,k.name,o.name as ukuran,tglpembelian,c.name as namaproduct FROM " . DB_PREFIX . "tabung_mp t LEFT JOIN product_options o ON(t.ukuran_tabung=o.product_options_id) LEFT JOIN kelompok_aset k ON(t.kelompok_aset=k.kelompok_aset_id) LEFT JOIN ".DB_PREFIX."product c ON(t.product_id=c.product_id) LEFT JOIN deliveryorder_tabung dt ON(t.id=dt.tabung_id)  WHERE t.hapus=0 AND dt.status=1 AND dt.do_id='".$data['do_id']."' ";
		if(isset($data['filter_no_tabung'])){
			if(!empty($data['filter_no_tabung'])){
				$sql .="  AND lower(no_tabung) LIKE '%".utf8_strtolower($data['filter_no_tabung'])."%' ";
			}
		}

		
		if(isset($data['filter_status'])){
			if(!empty($data['filter_status'])){
				$sql .="  AND t.status='".$data['filter_status']."' ";
			}
		}
		
		$sql .=" ORDER BY tglpembelian ";
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
		$query = $this->db->query("SELECT t.*,o.name as namaukuran ,pd.name as namaproduct FROM " . DB_PREFIX . "tabung_mp t LEFT JOIN product_options o ON(t.ukuran_tabung=o.product_options_id) LEFT JOIN kelompok_aset k ON(t.kelompok_aset=k.kelompok_aset_id) LEFT JOIN product pd ON(t.product_id = pd.product_id) LEFT JOIN deliveryorder_tabung dt ON(t.id=dt.tabung_id) WHERE dt.id='".$tabung."' ");

		return $query->row;
	}

 
}
?>
