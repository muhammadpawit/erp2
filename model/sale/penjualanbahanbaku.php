<?php
class ModelSalePenjualanbahanbaku extends Model {
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
    $penj=array(
      'date_added' => isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s'),
      'sopir'  => empty($data['sopir'])?0:$data['sopir'],
      'no_pol'  => $data['no_pol'],
      'sales'  => $data['sales'],
      'customer_id'  => $data['customer_id'],
      'status' => 1,
      'pengiriman'  => $data['pengiriman'],
      'hapus' => 0,
      'user_id'  => $this->user->getId(),
      'address_id' =>$data['address_id'],
      //'jenisorder'  => $data['jenisorder'],
      'sub_total' => $data['sub_total'],
      'diskon'  => $data['diskon'],
      'pajak' => $data['pajak'],
      'total' => $data['total'],
      'no_so' => $data['no_so'],
      ///'no_tttk' => empty($data['no_tttk'])?0:$data['no_tttk'],
      'no_invoice'  => '',
      'no_sj'  => 'in process',
      //'jatuhtempo'  => empty($data['jatuhtempo'])?date('Y-m-d'):$data['jatuhtempo'],
      'net_cost'  => $data['net_cost'],
      'cetak' => 0,
      'status_pengiriman' => 1,

    );

    /*
    1. belum dikirim
    2. dikirim
    3. sudah dikirim

    1. menunggu pembayaran
    2. dibayar sebagian
    3. lunas

    1. disimpan
    2. diproses
    3. sukses
    4. dibatalkan

    */

    $this->db->insert('penjualan_bahanbaku',$penj);
    $id=$this->db->getlastId();
    $data['id']=$id;
    $no_invoice="SJBB-".$id."-".date('Y')."-".date("m")."-".$this->user->getId();
    $data['no_sj']=$no_invoice;
    $this->db->update('penjualan_bahanbaku',array('no_sj' => $no_invoice),array('id' => $id));
    $this->addPenjualanProduct($data);

    /*if(!empty($data['tabungs'])){
      $this->addPenjualanTabung($data);
    }*/

    $this->addPenjualanKernet($data);

    $this->load->model('sale/customer');
    $penj=$this->model_sale_customer->updatePenjualan($data['customer_id'],$data['total'],1);

    //update jurnal
    $this->load->model('keuangan/jurnal');
    if(!empty($data['net_cost'])){
      $details=array();
      $details[]=array(
        'ref_akun'  => '50.06.00',
        'keterangan'  => 'Harga Pokok Penjualan',
        'debet' => $data['net_cost'],
        'kredit'  => 0,
        'urutan'  => 1,
        'hapus' => 0
      );


      $details[]=array(
        'ref_akun'  => '11.05.01',
        //'jenis_akun'  => 52,
        'keterangan'  => 'Persediaan bahan baku',
        'debet' => 0,
        'kredit'  => $data['net_cost'],
        'urutan'  => 2,
        'hapus' => 0
      );


      $j=array(
        'tanggal' => date('Y-m-d'),
        'keterangan'  => 'Pengiriman Penjualan bahanbaku '.$no_invoice,
        'details' => $details,
        'hapus' =>0,
        'ref' => $id,
        'type'  => 8
      );
      $this->model_keuangan_jurnal->addJurnalUmum($j);
    }

    return $id;

  }

  public function batalkan($order_id){
    //hapus jurnal
    $this->load->model('keuangan/jurnal');
    $this->model_keuangan_jurnal->updateJurnalumum(array('hapus'=>0),array('ref'=>$order_id,'type'  => 4));
    //update stok

    //update status
  }

  public function addPenjualanKernet($data){

    foreach($data['kernet'] as $p){
        if(!empty($p)){
          $penj=array(
            'tttk_id' => $data['id'],
            'pegawai_id' => $p
          );


          $this->db->insert('penjualan_bahanbaku_kernet',$penj);



      }
    }

  }
  public function addPenjualanProduct($data){
    $total=0;
    $diskon=0;

  //  $penjualan=$this->getPenjualan(array('id' => $data['id']));

  $this->load->model('catalog/product');
  $this->load->model('sale/salesorderbahanbaku');
  $this->load->model('catalog/bahanbaku');
  $this->load->model('gudang/kartustok');
  $this->load->model('gudang/product');
  $this->load->model('catalog/kartustoktabungmp');
    foreach($data['product'] as $p){
      if(!empty($p['product_id']) & $p['quantity'] > 0){
        //getnetcost
        //$net=$this->db->first('product_toko_pameran',array('gudang_id'  => $penjualan['pameran_id'],'product_id'  => $p['product_id']));

          $curqty=$this->model_catalog_bahanbaku->getProduct($p['product_id']);
        //  $pajak=($p['price']-$p['diskon'])*0.1;
        //  $total=(($p['price']-$p['diskon'])*$p['quantity']) + ($pajak*$p['quantity']);
          $penj=array(
            'id' => $p['id'],
            'sales_order_id' => $data['id'],
            'product_id'  => $p['product_id'],
            'quantity' => $p['quantity'],
            'quantitypesan' => $p['quantitypesan'],
            'price' => $p['price'],
           'diskon' => isset($p['diskon'])?$p['diskon']:0,
            'pajak' => $p['pajak'],
            'total' => $p['total'],
            'net_cost'  => empty($curqty['net_cost'])?0:$curqty['net_cost'],
          );

        $this->db->insert('penjualan_bahanbaku_product',$penj);

        //update sales order
        $qtytrm=$this->model_sale_salesorderbahanbaku->updateqtykirim($p['id'],$p['quantity'],1);

        //update Stok
        $prod=$this->model_catalog_bahanbaku->getProduct($p['product_id']);
        $update=$this->model_catalog_bahanbaku->updateQty($p['product_id'],$p['quantity'],2);

        //kartustok

        $kartustok=array(
          'product_id'	=> $p['product_id'],
          'product_name'	=> $prod['name'],
          'tgl'	=> date('Y-m-d h:i:s',time()),
          'stokkeluar'	=> $p['quantity'],
          'stokmasuk'	=> 0,
          'ket'	=> 'Penjualan bahanbaku',
          'saldo'	=> $update,
          'quantityawal'	=> $prod['quantity'],
          'invoice'	=> $data['no_sj'],
          'type'	=> 2
        );

        $this->model_gudang_kartustok->addKartuStokGlobal('kartustok_bahanbaku',$kartustok);




    }
    }

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



  public function cancelPenjualan($id){
    $inv=$this->db->first('invoice',array('jenisinvoice' => 3,'referensi'=>$id,'jenispenjualan'=>3));
    $this->load->model('sale/invoice');
    if(!empty($inv)){
      if($inv['status'] == 1){
        $this->model_sale_invoice->voidInvoice($inv['id']);
      }
    }else{
      $inv['status'] == 1;
    }

    $penj=$this->getPenjualan(array('id'=>$id));
    if($penj['status'] == 1 & $inv['status'] == 1){
    $penj=array(
      'status'  => 3
    );

    $where=array(
      'id'  => $id
    );
    $this->db->update('penjualan_bahanbaku',$penj,$where);
    //kembalikan stok produk
    $penj=$this->getPenjualan(array('id'=>$id));
    $products=$this->getPenjualanProducts(array('sales_order_id'=>$id));

    $this->load->model('catalog/product');
    $this->load->model('sale/salesorderbahanbaku');
    $this->load->model('catalog/bahanbaku');
    $this->load->model('gudang/kartustok');
    $this->load->model('gudang/product');
    $this->load->model('catalog/kartustoktabungmp');

    foreach($products as $p){
      $curqty=$this->model_cgudang_product->getProduct($p['product_id']);
      //update sales order
      $qtytrm=$this->model_sale_salesorderbahanbaku->updateqtykirim($p['id'],$p['quantity'],2);

      //update Stok
      $prod=$this->model_catalog_bahanbaku->getProduct($p['product_id']);
      $update=$this->model_catalog_bahanbaku->updateQty($p['product_id'],$p['quantity'],1);

      //kartustok

      $kartustok=array(
        'product_id'	=> $p['product_id'],
        'product_name'	=> $prod['name'],
        'tgl'	=> date('Y-m-d h:i:s',time()),
        'stokkeluar'	=> 0,
        'stokmasuk'	=> $p['quantity'],
        'ket'	=> 'Pembatalan Pengiriman Barang ',
        'saldo'	=> $update,
        'quantityawal'	=> $prod['quantity'],
        'invoice'	=> $penj['no_sj'],
        'type'	=> 2
      );

      $this->model_gudang_kartustok->addKartuStokGlobal('kartustok_bahanbaku',$kartustok);


    }
    //hapus jurnal
    $this->load->model('keuangan/jurnal');
    $this->model_keuangan_jurnal->updateJurnalumum(array('hapus'=>0),array('ref'=>$id,'type'=>4));
    //$this->db->update();
    }
  }

  public function updatePenjualan($data,$where=array()){
	$this->db->update('penjualan_bahanbaku',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('penjualan_bahanbaku',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order=array()){
		return $this->db->firstdetail('penjualan_bahanbaku',$column,$join,$where,$order);
	}
  public function getPenjualanProducts($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('satuan.name as namasatuan','penjualan_bahanbaku_product.product_id','penjualan_bahanbaku_product.id','penjualan_bahanbaku_product.diskon','penjualan_bahanbaku_product.pajak','penjualan_bahanbaku_product.total','bahanbaku.name','penjualan_bahanbaku_product.quantity','penjualan_bahanbaku_product.net_cost','penjualan_bahanbaku_product.price');
    $join=array();
    $join[]=array(
      'tablename' => 'bahanbaku',
      'firsttable'  => 'penjualan_bahanbaku_product.product_id',
      'secondtable' => 'bahanbaku.id'
    );
    $leftjoin=array();
    $leftjoin[]=array(
      'tablename' => 'satuan',
      'firsttable'  => 'bahanbaku.satuan',
      'secondtable' => 'satuan.id'
    );


    return $this->db->alljoins('penjualan_bahanbaku_product',$column,$join,$leftjoin,$where,array(),0,null);
	}
  public function getPenjualanProduct($where){
		return $this->db->first('penjualan_bahanbaku_product',$where);
	}
	public function getPenjualans($column=array(),$join=array(),$where=array(),$order,$limit,$offset){
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$where['penjualan_bahanbaku.sales']=$this->user->getId();
		}
		return $this->db->alljoin('penjualan_bahanbaku',$column,$join,$where,$order,$limit,$offset);
	}
  public function getPenjualanDetailss($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order,$limit,$offset){
		return $this->db->alljoin('penjualan_bahanbaku',$column,$join,$leftjoin,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where){
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$where['penjualan_bahanbaku.sales']=$this->user->getId();
		}
		return $this->db->count('penjualan_bahanbaku',$where);
	}

  public function getPenjualanTabungs($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('penjualan_tabung.tabung_id','penjualan_tabung.id','penjualan_tabung.id','tabung_mp.no_tabung','penjualan_tabung.tutup','product_options.name','tabung_mp.pemilik');
    $join=array();
    $join[]=array(
      'tablename' => 'tabung_mp',
      'firsttable'  => 'penjualan_tabung.tabung_id',
      'secondtable' => 'tabung_mp.id'
    );
    $join[]=array(
      'tablename' => 'product_options',
      'firsttable'  => 'tabung_mp.ukuran_tabung',
      'secondtable' => 'product_options.product_options_id'
    );

    return $this->db->alljoin('penjualan_tabung',$column,$join,$where,array(),0,null);
	}

  public function getPenjualanKernets($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('penjualan_bahanbaku_kernet.id','penjualan_bahanbaku_kernet.pegawai_id','users.firstname');
    $join=array();
    $join[]=array(
      'tablename' => 'users',
      'firsttable'  => 'penjualan_bahanbaku_kernet.pegawai_id',
      'secondtable' => 'users.user_id'
    );

    return $this->db->alljoin('penjualan_bahanbaku_kernet',$column,$join,$where,array(),0,null);
	}

  /*public function getTotalKirim($id){
    $total=$this->db->query("SELECT ");
  }*/
}
?>
