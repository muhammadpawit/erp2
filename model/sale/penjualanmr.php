<?php
class ModelSalePenjualanmr extends Model {
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
      'gudang_id' => $data['gudang_id']
      //'status_pembayaran' => 1,
      //'totaltabung' => $data['totaltabung']
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
    $data['date_added']=$penj['date_added'];
    $this->db->insert('penjualan_mr',$penj);
    $id=$this->db->getlastId();
    $data['id']=$id;
    $no_invoice="SJ-".$id."-".date('Y')."-".date("m")."-".$this->user->getId();
    $data['no_sj']=$no_invoice;
    $this->db->update('penjualan_mr',array('no_sj' => $no_invoice),array('id' => $id));
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
        'ref_akun'  => '11.05.02',
        //'jenis_akun'  => 52,
        'keterangan'  => 'Persediaan barang jadi',
        'debet' => 0,
        'kredit'  => $data['net_cost'],
        'urutan'  => 2,
        'hapus' => 0
      );


      $j=array(
        'tanggal' => date('Y-m-d'),
        'keterangan'  => 'Pengiriman Penjualan '.$no_invoice,
        'details' => $details,
        'hapus' =>0,
        'ref' => $id,
        'type'  => 7
      );
      $this->model_keuangan_jurnal->addJurnalUmum($j);
    }

    return $id;

  }

  public function batalkan($order_id){
    //hapus jurnal
    $this->load->model('keuangan/jurnal');
    $this->model_keuangan_jurnal->updateJurnalumum(array('hapus'=>0),array('ref'=>$order_id));
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


          $this->db->insert('penjualan_kernet',$penj);



      }
    }

  }
  public function addPenjualanProduct($data){
    $total=0;
    $diskon=0;

  //  $penjualan=$this->getPenjualan(array('id' => $data['id']));

  $this->load->model('catalog/product');
  $this->load->model('catalog/tabungmr');
  $this->load->model('sale/salesordermr');
  $this->load->model('gudang/kartustok');
  $this->load->model('gudang/product');
  $this->load->model('catalog/kartustoktabungmr');

  $salesorder=$this->model_sale_salesordermr->getPenjualan(array('id'=>$data['no_so']));
    foreach($data['product'] as $p){
      if(!empty($p['product_id']) & $p['quantity'] > 0){
        //getnetcost
        //$net=$this->db->first('product_toko_pameran',array('gudang_id'  => $penjualan['pameran_id'],'product_id'  => $p['product_id']));

          $curqty=$this->model_catalog_product->getProduct($p['product_id']);
          //$pajak=round(($p['price']-$p['diskon'])*0.1);
        //  $total=(($p['price']-$p['diskon'])*$p['quantity']) + ($pajak*$p['quantity']);
          $penj=array(
            'id' => $p['id'],
            'sales_order_id' => $data['id'],
            'product_id'  => $p['product_id'],
            'quantity' => $p['quantity'],
            'quantitypesan' => $p['quantitypesan'],
            'tabung_id' => empty($p['tabung_id'])?0:$p['tabung_id'],
            'price' => $p['price'],
           'diskon' => !isset($p['diskon'])?0:$p['diskon'],
            'pajak' => $p['pajak'],
            'total' => $p['total'],
            'net_cost'  => empty($curqty['net_cost'])?0:$curqty['net_cost'],
          );

        $this->db->insert('penjualan_product',$penj);

        //salesorder
        if(!empty($salesorder['tttk'])){
        //  $prodtttk=$this->db->query("SELECT * FROM tttk_tabungmr WHERE tttk_id='".$salesorder['tttk']."' AND product_id");
          $this->db->update('tttk_tabungmr',array('quantity_kirim'=>$p['quantity']),array('tttk_id' => $salesorder['tttk'],'product_id'=>$p['product_id']));
        }

        //update sales order
        $qtytrm=$this->model_sale_salesordermr->updateqtykirim($p['id'],$p['quantity'],1);

        //update Stok
        $prod=$this->model_gudang_product->getProduct($p['product_id'],$data['gudang_id']);
        if(empty($prod)){
          $awal=array(
      			'gudang_id'	=> $data['gudang_id'],
      			'product_id'	=> $p['product_id'],
      			'quantity'	=> 0,
      			'status'	=>1,
      			'net_cost'	=> 0,
      			'date_added'	=>date('Y-m-d H:i:s',time())
      		);
          $this->model_gudang_product->addStokAwal($awal);
          $prod=$this->model_gudang_product->getProduct($p['product_id'],$data['gudang_id']);
        }
        $update=$this->model_gudang_product->updateQty($p['product_id'],$data['gudang_id'],$p['quantity'],2);

        $kartustok=array(
          'product_id'	=> $p['product_id'],
          'product_name'	=> $prod['name'],
          'tgl'	=> date('Y-m-d h:i:s',time()),
          'stokkeluar'	=> $p['quantity'],
          'stokmasuk'	=> 0,
          'ket'	=> 'Penjualan ',
          'saldo'	=> $update,
          'quantityawal'	=> isset($prod['quantity'])?$prod['quantity']:0,
          'invoice'	=> $data['no_sj'],
          'gudang_id'	=> $data['gudang_id'],
          'type'	=> 2
        );

        $this->model_gudang_kartustok->addKartuStok($kartustok);

        //kartustok
        $tabung=$this->model_catalog_tabungmr->getTabungByProduct($p['product_id'],$data['customer_id']);
        if(!empty($tabung)){
          $qty=$tabung['quantity']-$p['quantity'];
          $this->db->update('tabung_mr',array('quantity'=>$qty,'date_modified'=>date('Y-m-d H:i:s',time())),array('id'=>$tabung['id']));

          $kartustok=array(
            'tabung_id'	=> $tabung['id'],
            'tgl'	=> date('Y-m-d H:i:s'),
            'stokmasuk'	=> 0,
            'stokkeluar'	=> $p['quantity'],
            'ket'	=> $this->db->escape("Pengiriman barang"),
            'saldo'	=> $qty,
            'quantityawal'	=> $tabung['quantity'],
            'invoice'	=> $data['id'],
            //'gudang_id'	=> $data['gudang_id'],
            'type'	=> 1
          );
          $this->model_catalog_kartustoktabungmr->addKartuStok($kartustok);
        }

    }
    }

  }




  public function cancelPenjualan($id){
    $inv=$this->db->first('invoice',array('jenisinvoice' => 3,'referensi'=>$id,'jenispenjualan'=>2));
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
    $this->db->update('penjualan_mr',$penj,$where);
    //kembalikan stok produk
    $penj=$this->getPenjualan(array('id'=>$id));
    $products=$this->getPenjualanProducts(array('sales_order_id'=>$id));

    $this->load->model('catalog/product');
    $this->load->model('catalog/tabungmr');
    $this->load->model('sale/salesordermr');
    $this->load->model('catalog/bahanbaku');
    $this->load->model('gudang/kartustok');
    $this->load->model('gudang/product');
    $this->load->model('catalog/kartustoktabungmr');

    foreach($products as $p){
      $curqty=$this->model_catalog_product->getProduct($p['product_id']);
      //update sales order
      $qtytrm=$this->model_sale_salesordermr->updateqtykirim($p['id'],$p['quantity'],2);

      //update Stok
      $prod=$this->model_gudang_product->getProduct($p['product_id'],$penj['gudang_id']);
      $update=$this->model_gudang_product->updateQty($p['product_id'],$penj['gudang_id'],$p['quantity'],1);

      //kartustok

      $kartustok=array(
        'product_id'	=> $p['product_id'],
        'product_name'	=> $prod['name'],
        'tgl'	=> date('Y-m-d h:i:s',time()),
        'stokkeluar'	=> 0,
        'stokmasuk'	=> $p['quantity'],
        'ket'	=> 'Pembatalan Pengiriman Barang ',
        'saldo'	=> $update,
        'quantityawal'	=> isset($prod['quantity'])?$prod['quantity']:0,
        'invoice'	=> $penj['no_sj'],
        'gudang_id'	=> $penj['gudang_id'],
        'type'	=> 2
      );

      $this->model_gudang_kartustok->addKartuStok($kartustok);

      $tabung=$this->model_catalog_tabungmr->getTabungByProduct($p['product_id'],$penj['customer_id']);
      if(!empty($tabung)){
        $qty=$tabung['quantity']+$p['quantity'];
        $this->db->update('tabung_mr',array('quantity'=>$qty,'date_modified'=>date('Y-m-d H:i:s',time())),array('id'=>$tabung['id']));

        $kartustok=array(
          'tabung_id'	=> $tabung['id'],
          'tgl'	=> date('Y-m-d H:i:s',time()),
          'stokkeluar'	=> 0,
          'stokmasuk'	=> $p['quantity'],
          'ket'	=> $this->db->escape("Pembatalan Pengiriman barang"),
          'saldo'	=> $qty,
          'quantityawal'	=> $tabung['quantity'],
          'invoice'	=> $data['id'],
          //'gudang_id'	=> $data['gudang_id'],
          'type'	=> 1
        );
        $this->model_catalog_kartustoktabungmr->addKartuStok($kartustok);
      }
    }

    //hapus jurnal
    $this->load->model('keuangan/jurnal');
    $this->model_keuangan_jurnal->updateJurnalumum(array('hapus'=>0),array('ref'=>$id));
    //$this->db->update();
    }
  }

  public function updatePenjualan($data,$where=array()){
	$this->db->update('penjualan_mr',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('penjualan_mr',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order=array()){
		return $this->db->firstdetail('penjualan_mr',$column,$join,$where,$order);
	}
  public function getPenjualanProducts($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('satuan.name as namasatuan','penjualan_product.product_id','penjualan_product.id','penjualan_product.diskon','penjualan_product.pajak','penjualan_product.total','product.name','penjualan_product.quantity','penjualan_product.net_cost','penjualan_product.price');
    $join=array();
    $join[]=array(
      'tablename' => 'product',
      'firsttable'  => 'penjualan_product.product_id',
      'secondtable' => 'product.product_id'
    );
    $leftjoin=array();

    $leftjoin[]=array(
      'tablename' => 'satuan',
      'firsttable'  => 'product.satuan',
      'secondtable' => 'satuan.id'
    );

    return $this->db->alljoins('penjualan_product',$column,$join,$leftjoin,$where,array(),0,null);
	}
  public function getPenjualanProduct($where){
		return $this->db->first('penjualan_product',$where);
	}
	public function getPenjualans($column=array(),$join=array(),$where=array(),$order,$limit,$offset){
		return $this->db->alljoin('penjualan_mr',$column,$join,$where,$order,$limit,$offset);
	}
  public function getPenjualanDetailss($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order,$limit,$offset){
		return $this->db->alljoin('penjualan_mr',$column,$join,$leftjoin,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where){
		return $this->db->count('penjualan_mr',$where);
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
}
?>
