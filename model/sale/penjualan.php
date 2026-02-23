<?php
class ModelSalePenjualan extends Model {
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
  // baru 20 Mei 2020
  public function cekkernet($id){
    $d=$this->db->query("SELECT * FROM penjualan_kernet WHERE tttk_id='$id' ");
    return $d->rows;
  }
  public function editsupir($data){
    // 1.edit table penjualan
		if(isset($data['sopir'])){
		  if($data['sopir']!="*"){
			$this->db->update('penjualan',array('sopir' => $data['sopir']),array('id'=>$data['id']));
		  }
		}
		
		if(isset($data['kernet'])){
		  if($data['kernet'][1]!="*"){
        $cek=$this->cekkernet($data['id']);
        if(!empty($cek)){
          $this->db->update('penjualan_kernet',array('pegawai_id' => $data['kernet'][1]),array('tttk_id'=>$data['id']));
        }else{
          $ins=array(
            'tttk_id'=>$data['id'],
            'pegawai_id'=>$data['kernet'][1],
          );
          $this->db->insert('penjualan_kernet',$ins);
        }
			
		  }
		}
  }
  //end baru
  // baru 11 November 2019
  public function simpanedit($data){
    $totalharga=0;
    $total=0;
    $totalpajak=0;
    $totalnetcost=0;
    if(!empty($data['products'])){
      foreach($data['products'] as $p){
        $totalharga += ($p['price']*$p['qty']);
        $totalpajak += ($p['price']*$p['qty'])/10;
        $totalnetcost += ($p['net_cost']*$p['qty']);
      }
    }
    // 1.edit table penjualan
		if(isset($data['sopir'])){
		  if($data['sopir']!="*"){
			$this->db->update('penjualan',array('sopir' => $data['sopir']),array('id'=>$data['id']));
		  }
		}
		
		if(isset($data['kernet'])){
		  if($data['kernet'][1]!="*"){
			$this->db->update('penjualan_kernet',array('pegawai_id' => $data['kernet'][1]),array('tttk_id'=>$data['id']));
		  }
		}
        $this->db->update('penjualan',array('sub_total' => $totalharga,'pajak'=>$totalpajak,'total'=>($totalharga+$totalpajak),'net_cost'=>$totalnetcost),array('id' => $data['id']));
      
    // 3.perubahan di stok dan kartustok
      $this->load->model('gudang/product');
      $this->load->model('gudang/kartustok');
      $curqty=0;
      foreach($data['products'] as $p){
        $prod=$this->model_gudang_product->getProduct($p['product_id'],$data['gudang_id']);
        $curqty=$this->model_gudang_product->getProduct($p['product_id'],$data['gudang_id']);
        $stok=$curqty['quantity'];
        $qtypesan=$p['quantitypesan'];
        $qtykirimpertama=$p['qtykirimpertama'];
        $qty=$p['qty'];
        $stokjalan=0;
        if($qtykirimpertama>$qty & $qty<$qtypesan){
          $stok = $stok + ($qtykirimpertama - $qty);
          $stokjalan =($qtykirimpertama - $qty);
          $this->db->query("UPDATE product_gudang set quantity='".$stok."' WHERE product_id='".$p['product_id']."' and gudang_id='".$data['gudang_id']."' ");
          $kartustok=array(
            'product_id'  => $p['product_id'],
            'product_name'  => $prod['name'],
            'tgl' => date('Y-m-d h:i:s',time()),
            'stokkeluar'  => 0,
            'stokmasuk' => $stokjalan,
            'ket' => 'Edit Surat Jalan oleh '.$this->user->getUsername().' No.Surat Jalan '.$data['no_sj'].' ',
            'saldo' => $stok,
            'quantityawal'  => isset($curqty['quantity'])?$curqty['quantity']:0,
            'invoice' => $data['no_sj'],
            'gudang_id' => $data['gudang_id'],
            'type'  => 15
          );
          $this->model_gudang_kartustok->addKartuStok($kartustok);
          // 2.perubahan di Jurnal
          /**/
          $this->load->model('keuangan/jurnal');
          $this->db->query("UPDATE jurnal_umum set hapus=1 WHERE ref='".$data['id']."' AND hapus=0 AND type=7 ");
          $details=array();
          $details[]=array(
              'ref_akun'  => '5001',
              'keterangan'  => 'Harga Pokok Penjualan Produk Dagang',
              'debet' => $totalnetcost,
              'kredit'  => 0,
              'urutan'  => 1,
              'hapus' => 0
          );
          $details[]=array(
              'ref_akun'  => '1202',
              'keterangan'  => 'Persediaan barang jadi',
              'debet' => 0,
              'kredit'  => $totalnetcost,
              'urutan'  => 2,
              'hapus' => 0
          );
          $j=array(
                'tanggal' => isset($data['date_added'])?$data['date_added']:date('Y-m-d'),
                'keterangan'  => 'Surat Jalan Pengiriman Penjualan '.$data['no_sj'],
                'details' => $details,
                'hapus' =>0,
                'ref' => $data['id'],
                'linkterkait' =>$data['no_sj'],
                'type'  => 7
          );
          $this->model_keuangan_jurnal->addJurnalUmum($j);
        }elseif($qtykirimpertama<$qty & $qty==$qtypesan){
          $stok = ($stok -$qty)+$qtykirimpertama;
          $stokjalan =(($qty+$qtypesan)-($qtykirimpertama + $qty));
          $this->db->query("UPDATE product_gudang set quantity='".$stok."' WHERE product_id='".$p['product_id']."' and gudang_id='".$data['gudang_id']."' ");
          $kartustok=array(
            'product_id'  => $p['product_id'],
            'product_name'  => $prod['name'],
            'tgl' => date('Y-m-d h:i:s',time()),
            'stokkeluar'  => $stokjalan,
            'stokmasuk' => 0,
            'ket' => 'Edit Surat Jalan oleh '.$this->user->getUsername().' No.Surat Jalan '.$data['no_sj'].' ',
            'saldo' => $stok,
            'quantityawal'  => isset($curqty['quantity'])?$curqty['quantity']:0,
            'invoice' => $data['no_sj'],
            'gudang_id' => $data['gudang_id'],
            'type'  => 15
          );
          $this->model_gudang_kartustok->addKartuStok($kartustok);
          // 2.perubahan di Jurnal
          /**/
          $this->load->model('keuangan/jurnal');
          $this->db->query("UPDATE jurnal_umum set hapus=1 WHERE ref='".$data['id']."' AND hapus=0 AND type=7 ");
          $details=array();
          $details[]=array(
              'ref_akun'  => '5001',
              'keterangan'  => 'Harga Pokok Penjualan Produk Dagang',
              'debet' => $totalnetcost,
              'kredit'  => 0,
              'urutan'  => 1,
              'hapus' => 0
          );
          $details[]=array(
              'ref_akun'  => '1202',
              'keterangan'  => 'Persediaan barang jadi',
              'debet' => 0,
              'kredit'  => $totalnetcost,
              'urutan'  => 2,
              'hapus' => 0
          );
          $j=array(
                'tanggal' => isset($data['date_added'])?$data['date_added']:date('Y-m-d'),
                'keterangan'  => 'Surat Jalan Pengiriman Penjualan '.$data['no_sj'],
                'details' => $details,
                'hapus' =>0,
                'ref' => $data['id'],
                'linkterkait' =>$data['no_sj'],
                'type'  => 7
          );
          $this->model_keuangan_jurnal->addJurnalUmum($j);
        }else{
          $stok;
          $stokjalan;
        }
        //kartustok
        if($p['qtykirimpertama']>$p['qty'] & $p['qty'] < $p['quantitypesan']){
          //$totalqty = $curqty['quantity']+($p['qtykirimpertama']-$p['qty']);
        }else if($p['qtykirimpertama']<$p['qty'] & $p['qty'] < $p['quantitypesan']){
          $totalqty = $curqty['quantity']-$p['qty'];
          
        }else{

        }
        
      }
    // 4.Perubahan Status di Sales Order
      $totalpp=0;
      $pajakpp=0;
      foreach($data['products'] as $psj){
        $pajakpp += (($psj['price']*$psj['qty'])/10);
        $totalpp += ($psj['price']*$psj['qty']);
        $a = $this->editproductsj($totalpp+$totalpajak,$totalpajak,$psj['product_id'],$data['id'],$psj['qty']);
        //$b = $this->editsoproduct($totalpp,$totalpajak,$psj['product_id'],$data['id'],$psj['qty']);
        if($psj['qty']==$psj['quantitypesan']){
          $this->db->update('sales_order_product',array('quantityterima'=>$psj['qty'],'status_pengiriman'=>3),array('product_id'=>$psj['product_id'],'sales_order_id'=>$psj['sales_order_id']));
        }else if($psj['qty']<$psj['qtykirimpertama'] & $psj['qty']<$psj['quantitypesan']){
          $this->db->update('sales_order_product',array('quantityterima'=>$psj['qty'],'status_pengiriman'=>2),array('product_id'=>$psj['product_id'],'sales_order_id'=>$psj['sales_order_id']));
        }else{

        }
      }
    // print_r($b);
    //print_r($curqty);
    // echo "\n";
    // echo "Total Harga ".$totalharga."\n";
    // echo "Total Pajak ".$totalpajak."\n";
    // echo "Total ".($totalharga+$totalpajak)."\n";
    // echo "Total Net Cost ".$totalnetcost."\n";
    //print_r($data['products']);
  }

  public function editproductsj($total,$pajak,$product_id,$sales_order_id,$qty){
    $sql="UPDATE penjualan_product set total='$total', pajak='$pajak',quantity='$qty' WHERE product_id='$product_id' and sales_order_id='$sales_order_id' ";
    $this->db->query($sql);
    //echo $sql;
  }
  public function editsoproduct($total,$pajak,$product_id,$sales_order_id,$qty){
    $d = $this->db->query("SELECT * FROM penjualan_product WHERE sales_order_id='$sales_order_id' ");
    foreach($d->rows as $r){
      $sql="UPDATE sales_order_product set total='$total', pajak='$pajak', quantityterima='$qty' WHERE product_id='$product_id' and sales_order_id='".$r['id']."' ";
      $sql="UPDATE sales_order_product set quantityterima='$qty' WHERE product_id='$product_id' and sales_order_id='".$r['id']."' ";
      $this->db->query($sql);
      if($qty==$r['quantitypesan']){
        $this->db->update('sales_order_product',array('status_pengiriman'=>3),array('sales_order_id'=>$r['id']));
      }else if($qty<$r['quantitypesan']){
        $this->db->update('sales_order_product',array('status_pengiriman'=>2),array('sales_order_id'=>$r['id']));
      }else{

      }
      // echo $qty." ".$r['quantitypesan'];
    }
  }
  // end baru  


  // baru 29 oktober 
    public function getusia($invoice_id){
		$d = $this->db->query("SELECT usia FROM penjualan_product WHERE invoice_id='$invoice_id' ");
		return $d->row['usia'];
	}
  // end baru
  // baru 12 September 2019
	public function sjbelumdifaktur($data){
		$sql ="SELECT penjualan_product.sales_order_id, penjualan_product.product_id,penjualan_product.quantity,penjualan_product.no_so,penjualan_product.invoice_id,satuan.name as namasatuan,product.name as namaproduct,penjualan.*,sales_order.no_so as no_salesorder,gudang.nama,customer.name,customer.email,customer.alamat,customer.email,invoice.no_faktur FROM penjualan JOIN penjualan_product ON (penjualan_product.sales_order_id=penjualan.id) JOIN gudang ON(gudang.gudang_id=penjualan.gudang_id) JOIN product ON(product.product_id=penjualan_product.product_id) JOIN sales_order ON(sales_order.id=penjualan_product.no_so) LEFT JOIN customer ON(customer.customer_id=penjualan.customer_id) LEFT JOIN satuan on(satuan.id=product.satuan) LEFT JOIN invoice ON(penjualan_product.invoice_id=invoice.id) WHERE invoice.no_faktur is null AND penjualan.status=1 ";
		
		if(!empty($data['filter_sales_order'])){
			$sql .=" AND penjualan.no_so='".$data['filter_sales_order']."' ";
		}
		
		if(!empty($data['filter_order_id'])){
			$sql .=" AND penjualan.id='".$data['filter_order_id']."' ";
		}
		
		if(!empty($data['filter_customer_id'])){
			$sql .=" AND penjualan.customer_id='".$data['filter_customer_id']."' ";
		}
		
		if(!empty($data['filter_gudang_id'])){
			$sql .=" AND penjualan.gudang_id='".$data['filter_gudang_id']."' ";
		}
		
		if(!empty($data['filter_tanggal_awal'])){
			$sql .=" AND date(penjualan.date_added) >='".$data['filter_tanggal_awal']."' ";
		}
		
		if(!empty($data['filter_tanggal_akhir'])){
			$sql .=" AND date(penjualan.date_added) <='".$data['filter_tanggal_akhir']."' ";
		}
		
		$sql .="ORDER BY penjualan.id DESC ";
		if (isset($data['start']) || isset($data['limit'])) {
          if ($data['start'] < 0) {
            $data['start'] = 0;
          }

          if ($data['limit'] < 1) {
            $data['limit'] = 20;
          }

         $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
        }
		$q= $this->db->query($sql);
		return $q->rows;
	}
	
	public function totalsjbelumdifaktur($data){
		$sql ="SELECT penjualan_product.sales_order_id, penjualan_product.product_id,penjualan_product.quantity,penjualan_product.no_so,penjualan_product.invoice_id,satuan.name as namasatuan,product.name as namaproduct,penjualan.*,sales_order.no_so as no_salesorder,gudang.nama,customer.name,customer.email,customer.alamat,customer.email,invoice.no_faktur FROM penjualan JOIN penjualan_product ON (penjualan_product.sales_order_id=penjualan.id) JOIN gudang ON(gudang.gudang_id=penjualan.gudang_id) JOIN product ON(product.product_id=penjualan_product.product_id) JOIN sales_order ON(sales_order.id=penjualan_product.no_so) LEFT JOIN customer ON(customer.customer_id=penjualan.customer_id) LEFT JOIN satuan on(satuan.id=product.satuan) LEFT JOIN invoice ON(penjualan_product.invoice_id=invoice.id) WHERE invoice.no_faktur is null AND penjualan.status=1 ";
		
		
		if(!empty($data['filter_sales_order'])){
			$sql .=" AND penjualan.no_so='".$data['filter_sales_order']."' ";
		}
		
		if(!empty($data['filter_order_id'])){
			$sql .=" AND penjualan.id='".$data['filter_order_id']."' ";
		}
		
		if(!empty($data['filter_customer_id'])){
			$sql .=" AND penjualan.customer_id='".$data['filter_customer_id']."' ";
		}
		
		if(!empty($data['filter_gudang_id'])){
			$sql .=" AND penjualan.gudang_id='".$data['filter_gudang_id']."' ";
		}
		
		if(!empty($data['filter_tanggal_awal'])){
			$sql .=" AND date(penjualan.date_added) >='".$data['filter_tanggal_awal']."' ";
		}
		
		if(!empty($data['filter_tanggal_akhir'])){
			$sql .=" AND date(penjualan.date_added) <='".$data['filter_tanggal_akhir']."' ";
		}
		
		$q= $this->db->query($sql);
		return $q->rows;
	}
  
  // end baru
  
  public function addPenjualan($data){
	$keterangan='-';
	if(isset($data['keterangan'])){
		$keterangan = empty($data['keterangan'])?$keterangan:$data['keterangan'];
	}
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
        $data['address_id']=$this->model_sale_customer->addAddress($add,$data['customer_id']);
      }

    }

    /*$penj=array(
      'date_added' => isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s'),
      'sopir'  => empty($data['sopir'])?0:$data['sopir'],
      'no_pol'  => $data['no_pol'],
      //'sales'  => $data['sales'],
      'customer_id'  => $data['customer_id'],
      'status' => 1,
      'pengiriman'  => $data['pengiriman'],
      'hapus' => 0,
      'user_id'  => $this->user->getId(),
      'address_id' =>$data['address_id'],
    //  'jenispenjualan'  => empty($data['jenispenjualan'])?1:$data['jenispenjualan'],
      //'jenisstok' => empty($data['jenisstok'])?1:$data['jenisstok'],
      //'jenisorder'  => $data['jenisorder'],
      'sub_total' => $data['sub_total'],
      'pembulatan' => empty($data['pembulatan'])?0:$data['pembulatan'],
      'diskon'  => $data['diskon'],
      'pajak' => $data['pajak'],
      'total' => $data['total'],
      //'no_so' => $data['no_so'],
      ///'no_tttk' => empty($data['no_tttk'])?0:$data['no_tttk'],
      'no_invoice'  => '',
      'no_sj'  => 'in process',
      //'jatuhtempo'  => empty($data['jatuhtempo'])?date('Y-m-d'):$data['jatuhtempo'],
      'net_cost'  => $data['net_cost'],
      'cetak' => 0,
      'status_pengiriman' => 1,
	  'keterangan' => $keterangan,
      'gudang_id' => $data['gudang_id']
      //'status_pembayaran' => 1,
      //'totaltabung' => $data['totaltabung']
    );*/
    $penj=array(
      'date_added' => isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s'),
      'sopir'  => empty($data['sopir'])?0:$data['sopir'],
      'no_pol'  => $data['no_pol'],
      //'sales'  => $data['sales'],
      'customer_id'  => $data['customer_id'],
      'status' => 1,
      'pengiriman'  => $data['pengiriman'],
      'hapus' => 0,
      'user_id'  => $this->user->getId(),
      'address_id' =>$data['address_id'],
    //  'jenispenjualan'  => empty($data['jenispenjualan'])?1:$data['jenispenjualan'],
      //'jenisstok' => empty($data['jenisstok'])?1:$data['jenisstok'],
      //'jenisorder'  => $data['jenisorder'],
      'sub_total' => $data['sub_total'],
      'pembulatan' => empty($data['pembulatan'])?0:$data['pembulatan'],
      'diskon'  => $data['diskon'],
      'pajak' => $data['pajak'],
      'total' => $data['total'],
      'do_id'=>0,
      //'no_so' => $data['no_so'],
      ///'no_tttk' => empty($data['no_tttk'])?0:$data['no_tttk'],
      'no_invoice'  => '',
      'no_sj'  => 'in process',
      //'jatuhtempo'  => empty($data['jatuhtempo'])?date('Y-m-d'):$data['jatuhtempo'],
      'net_cost'  => $data['net_cost'],
      'cetak' => 0,
      'status_pengiriman' => 1,
      'gudang_id' => $data['gudang_id'],
      'keterangan' => $keterangan,
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

    $this->db->insert('penjualan',$penj);
    $id=$this->db->getlastId();
    $data['id']=$id;
    $no_invoice="SJ-".$id."-".date('Y')."-".date("m")."-".$this->user->getId();
    $no_dokumen="PJJ-".$id."-".date('Y')."-".date("m")."-".$this->user->getId();
    $data['no_sj']=$no_invoice;
    $data['no_dokumen']=$no_dokumen;
    $this->db->update('penjualan',array('no_sj' => $no_invoice,'no_dokumen'=>$no_dokumen),array('id' => $id));
    $this->addPenjualanProduct($data);

    /*if(!empty($data['tabungs'])){
      $this->addPenjualanTabung($data);
    }*/

    $this->addPenjualanKernet($data);

    $this->load->model('sale/customer');
    $penj=$this->model_sale_customer->updatePenjualan($data['customer_id'],$data['total'],1);

    //update jurnal
  /*  $this->load->model('keuangan/jurnal');
    if(!empty($data['net_cost'])){
      if($so['jenisstok'] == 1){
        $details=array();
        $details[]=array(
          'ref_akun'  => '5001',
          'keterangan'  => 'Harga Pokok Penjualan Produk Dagang',
          'debet' => $data['net_cost'],
          'kredit'  => 0,
          'urutan'  => 1,
          'hapus' => 0
        );


        $details[]=array(
          'ref_akun'  => '1202',
          //'jenis_akun'  => 52,
          'keterangan'  => 'Persediaan barang jadi',
          'debet' => 0,
          'kredit'  => $data['net_cost'],
          'urutan'  => 2,
          'hapus' => 0
        );


        $j=array(
          'tanggal' => isset($data['date_added'])?$data['date_added']:date('Y-m-d'),
          'keterangan'  => 'Pengiriman Penjualan '.$no_invoice,
          'details' => $details,
          'hapus' =>0,
          'ref' => $id,
          'type'  => 7
        );
        $this->model_keuangan_jurnal->addJurnalUmum($j);
      }
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
  $this->load->model('sale/salesorder');
  $this->load->model('catalog/bahanbaku');
  $this->load->model('catalog/tabungmr');
  $this->load->model('gudang/kartustok');
  $this->load->model('gudang/product');
  $this->load->model('catalog/kartustoktabungmp');
  $this->load->model('catalog/kartustoktabungstok');
  $this->load->model('catalog/kartustoktabungmr');
  $this->load->model('catalog/tabungms');
    $netcostproduksi=0;
    $netcostdagang=0;
	$hargajualppn=0;
	$pajakppn=0;
  $hargajual=0;
  $biayapembulatan=0;
    foreach($data['product'] as $p){
      if(!empty($p['product_id']) & $p['quantity'] > 0){
        if($p['pilih']){
          $curqty=$this->model_gudang_product->getProduct($p['product_id'],$data['gudang_id']);
          //$pajak=round(($p['price']-$p['diskon'])*0.1);
        //  $total=(($p['price']-$p['diskon'])*$p['quantity']) + ($pajak*$p['quantity']);
        //$this->load->model('sale/salesorder');
        $so=$this->model_sale_salesorder->getPenjualan(array('id'=>$p['no_so']));
        if(!empty($so)){
          $data['jenispenjualan']=$so['jenispenjualan'];
          $data['jenisstok']=$so['jenisstok'];

          $data['tttk']=$so['tttk'];
        }else{
          $data['jenispenjualan']=1;
          $data['jenisstok']=1;
          $data['tttk']=0;
        }
          $diskon+=($p['diskon']*$p['quantity']);
          if($data['jenisstok'] == 1){
            $curqty['net_cost']=empty($curqty['net_cost'])?0:$curqty['net_cost'];
            $netcostdagang += ($curqty['net_cost']*$p['quantity']);
            // baru 16 September 2019
            /**/
            $hargajualppn += ($p['total']);
            //$pajakppn += ($p['pajak']);
            //$pajakppn += (($p['price']*$p['quantity'])*0.1);
            if($p['pajak']>0){
              $pajakppn += (($p['price']*$p['quantity'])*0.1);
            }else{
              $pajakppn =0;
            }
            $hargajual += ($p['price']*$p['quantity']);			
          }else{
            $curqty['net_cost']=0;
            $netcostproduksi += ($curqty['net_cost']*$p['quantity']);
          }

          if(empty($so['usia'])){
            $so['usia']=0;
          }

          $time= strtotime($data['date_added']) + ($so['usia']*86400);

          $jatuhtempo=date("Y-m-d",$time);

          $penj=array(
            'id' => $p['id'],
            'sales_order_id' => $data['id'],
            'product_id'  => $p['product_id'],
            'quantity' => $p['quantity'],
            'pembulatan' => $p['pembulatan'],
            'quantitypesan' => $p['quantitypesan'],
            'tabung_id' => empty($p['tabung_id'])?0:$p['tabung_id'],
            'price' => $p['price'],
            'diskon' => !isset($p['diskon'])?0:$p['diskon'],
            'pajak' => $p['pajak'],
            'total' => $p['total'] - ($p['diskon']*$p['quantity']),
            'net_cost'  => $curqty['net_cost'],
            'jenispenjualan'  => empty($data['jenisstok'])?1:$data['jenisstok'],
            'jatuhtempo'  => $jatuhtempo,
            'no_so' => $p['no_so'],
            'jenisstok' => empty($data['jenispenjualan'])?1:$data['jenispenjualan'],
            'tttk'  => $data['tttk'],
            'sales' => $so['sales'],
            'metode_pembayaran' => $so['metode_pembayaran'],
            'hapus' => 0,
            'usia'  => $so['usia'],
            'invoice_id'  => 0,
            'harga_terendah' => empty($p['harga_terendah'])?0:$p['harga_terendah'],
            'quantityreturn'=>0,
          );

        $this->db->insert('penjualan_product',$penj);

        if(!empty($data['tttk'])){
        //  $prodtttk=$this->db->query("SELECT * FROM tttk_tabungmr WHERE tttk_id='".$salesorder['tttk']."' AND product_id");
          $this->db->update('tttk_tabungmr',array('quantity_kirim'=>$p['quantity']),array('tttk_id' => $data['tttk'],'product_id'=>$p['product_id']));
        }

        //update sales order
        $qtytrm=$this->model_sale_salesorder->updateqtykirim($p['id'],$p['quantity'],1);

        //update Stok
        $prod=$this->model_gudang_product->getProduct($p['product_id'],$data['gudang_id']);
        $update=$this->model_gudang_product->updateQty($p['product_id'],$data['gudang_id'],$p['quantity'],2);

        //kartustok
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
          'type'	=> 2,
          'urlref'  => 'sale/penjualan',
          'idref' => $data['id'],
          'no_dokumen'  => $data['no_dokumen'],
          
        );

        $this->model_gudang_kartustok->addKartuStok($kartustok);

        //cek tabungms
        $cek=$this->model_catalog_tabungms->getProductByGudang($p['product_id'],$data['gudang_id']);
        if(!empty($cek)){
          $curmsqty=$this->model_catalog_tabungms->getProductByGudang($p['product_id'],$data['gudang_id']);
    			$updatems=$this->model_catalog_tabungms->updateQty($p['product_id'],$data['gudang_id'],$p['quantity'],2);
    			$kartustok=array(
    				'tabung_id'	=> $p['product_id'],
    				'tgl'	=> date('Y-m-d h:i:s',time()),
    				'stokkeluar'	=> $p['quantity'],
    				'stokmasuk'	=> 0,
    				'ket'	=> $this->db->escape("Penjualan"),
    				'saldo'	=> $updatems,
    				'quantityawal'	=> $curmsqty['quantity'],
    				'invoice'	=> $data['no_sj'],
    				'gudang_id'	=> $data['gudang_id'],
            'type'	=> 3,
            'urlref'  => 'sale/penjualan',
            'idref' => $id,
            'no_dokumen'  => $penj['no_dokumen'],
    			);
    		  $this->model_catalog_kartustoktabungstok->addKartuStok($kartustok);
        }
        if(!empty($p['tabung_id'])){
            if($p['tabung_id'] > 0){
              $this->db->update('tabung_mp',array('status' => 6,'date_modified'=>date('Y-m-d H:i:s',time())),array('id'=>$p['tabung_id']));
            //  $penj=$this->getPenjualanDetail(array('id'=>$penj['id']));
              $kartustok=array(
          			'tabung_id'	=> $p['tabung_id'],
          			'tglpeminjaman'	=> date('Y-m-d',time()),
          			'tglpengembalian'	=> '1901-01-01',
          			'tglisiulang'	=> date('Y-m-d',time()),
          			'customer_id'	=> $data['customer_id'],
                'invoice'	=> $data['no_sj'],
          			'ket'	=> 'Penyewaan tabung',
          			'biayasewa'	=> 0
          		);

              $this->model_catalog_kartustoktabungmp->addKartuStok($kartustok);
            }
        }
        if($data['jenispenjualan'] == 2){
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
    }

      $tglaktifscript = date('2020-01-16');
      $this->load->model('keuangan/jurnal');
      
        $biayapembulatan=($hargajual+$pajakppn)-$hargajualppn;
        $details=array();
              $details[]=array(
                'ref_akun'  => '1102',
                'keterangan'  => 'Piutang belum tertagih',
                'debet' => $hargajualppn-$diskon,
                'kredit'  => 0,
                'urutan'  => 1,
                'hapus' => 0
              );
      if(!empty($data['net_cost'])){
            if($netcostdagang > 0){
              $details[]=array(
                'ref_akun'  => '5001',
                'keterangan'  => 'Harga Pokok Penjualan Produk Dagang',
                'debet' => $netcostdagang,
                'kredit'  => 0,
                'urutan'  => 2,
                'hapus' => 0
              );
            }
            if($netcostproduksi > 0){
              $details[]=array(
                'ref_akun'  => '5002',
                'keterangan'  => 'Harga Pokok Penjualan Hasil Produksi',
                'debet' => $netcostproduksi,
                'kredit'  => 0,
                'urutan'  => 3,
                'hapus' => 0
              );
            }
      }
            if($biayapembulatan>0){
              $details[]=array(
                'ref_akun'  => '6299',
                'keterangan'  => 'Biaya Lain-lain',
                'debet' => $biayapembulatan,
                'kredit'  => 0,
                'urutan'  => 4,
                'hapus' => 0
              );
            }
            if($diskon>0){
              $details[]=array(
                'ref_akun'  => '4002',
                'keterangan'  => 'Potongan pendapatan barang dagang',
                'debet' => round($diskon),
                'kredit'  => 0,
                'urutan'  => 5,
                'hapus' => 0
              );
            }
            $details[]=array(
              'ref_akun'  => '4001',
              'keterangan'  => 'Pendapatan',
              'debet' =>0,
              'kredit'  =>$hargajual,
              'urutan'  => 4,
              'hapus' => 0
            );
      if(!empty($data['net_cost'])){
            $details[]=array(
              'ref_akun'  => '1202',
              'keterangan'  => 'Persediaan barang jadi',
              'debet' => 0,
              'kredit'  => $netcostdagang + $netcostproduksi,
              'urutan'  => 5,
              'hapus' => 0
            );
        }
            $details[]=array(
              'ref_akun'  => '2505',
              'keterangan'  => 'Ppn',
              'debet' => 0,
              'kredit'  => $pajakppn,
              'urutan'  => 6,
              'hapus' => 0
            );

          $j=array(
            'tanggal' => isset($data['date_added'])?$data['date_added']:date('Y-m-d'),
            'keterangan'  => 'Pengiriman Penjualan '.$data['no_sj'],
            'details' => $details,
            'hapus' =>0,
            'ref' => $data['id'],
			      'linkterkait' =>$data['no_sj'],
            'type'  => 7,
            'urlref'  => 'sale/penjualan',
          'idref' => $data['id'],
          'no_dokumen'  => $data['no_dokumen'],

          );
          $this->model_keuangan_jurnal->addJurnalUmum($j);
      // }
      

  }

  public function addPenjualanTabung($data){
    $this->load->model('catalog/kartustoktabungmp');
    foreach($data['tabungs'] as $p){
        if(!empty($p['product_id'])){
          $penj=array(
            'penjualan_id' => $data['id'],
            'tabung_id' => $p['product_id'],
            'tutup' => $p['tutup']
          );

          $this->db->insert('penjualan_tabung',$penj);
        $this->db->update('tabung_mp',array('status' => 6,'date_modified'=>date('Y-m-d H:i:s',time())),array('id'=>$p['product_id']));

          $kartustok=array(
      			'tabung_id'	=> $p['product_id'],
      			'tglpeminjaman'	=> date('Y-m-d',time()),
      			'tglpengembalian'	=> '1901-01-01',
      			'tglisiulang'	=> date('Y-m-d',time()),
      			'customer_id'	=> $data['customer_id'],
            'invoice'	=> $data['invoice'],
      			'ket'	=> 'Penyewaan tabung',
      			'date_added'	=> date('Y-m-d H:i:s',time()),
      			'date_modified'	=> date('Y-m-d H:i:s',time()),
      			'biayasewa'	=> 0
      		);

          $this->model_catalog_kartustoktabungmp->addKartuStok($kartustok);



      }
    }
  }





  public function cancelPenjualan($id){
    //cek invoice
    $penj=$this->getPenjualan(array('id'=>$id));
    $products=$this->getPenjualanProducts(array('sales_order_id'=>$id));
  if($penj['status'] == 1 /*& ($inv['status'] == 1 | $inv['status'] == 4)*/){
    $this->load->model('catalog/product');
    $this->load->model('catalog/tabungms');
    $this->load->model('catalog/tabungmr');
    $this->load->model('sale/salesorder');
    $this->load->model('catalog/bahanbaku');
    $this->load->model('gudang/kartustok');
    $this->load->model('gudang/product');
    $this->load->model('catalog/kartustoktabungmp');
    $this->load->model('catalog/kartustoktabungmr');
    $this->load->model('catalog/kartustoktabungstok');
    $this->load->model('sale/invoice');

    $invstatus=false;

    foreach($products as $p){
      if($p['invoice_id'] > 0){
        $inv=$this->db->first('invoice',array('jenisinvoice' => 3,'id'=>$p['invoice_id'],'jenispenjualan'=>1));


        if(!empty($inv)){
          if($inv['status'] == 1){
            $this->model_sale_invoice->voidInvoice($inv['id']);
          }else{
            $invstatus=true;
          }
        }else{
          $inv['status'] = 1;
        }
      }

    /*  if($inv['status'] == 1 | $inv['status'] == 4){

    }*/

    }

    if(!$invstatus){
      $penj=array(
        'status'  => 3
      );

      $where=array(
        'id'  => $id
      );
      $this->db->update('penjualan',$penj,$where);
      $penj=$this->getPenjualan(array('id'=>$id));

      foreach($products as $p){
        $curqty=$this->model_catalog_product->getProduct($p['product_id']);
        //update sales order
        $qtytrm=$this->model_sale_salesorder->updateqtykirim($p['id'],$p['quantity'],2);

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
          'type'	=> 2,
          'urlref'  => 'sale/penjualan',
          'idref' => $id,
          'no_dokumen'  => $penj['no_dokumen'],
        );

        $this->model_gudang_kartustok->addKartuStok($kartustok);
        //kalau mr
        if($penj['jenispenjualan'] == 2){
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

        //cek tabungms
        $cek=$this->model_catalog_tabungms->getProductByGudang($p['product_id'],$penj['gudang_id']);
        if(!empty($cek)){
          $curmsqty=$this->model_catalog_tabungms->getProductByGudang($p['product_id'],$penj['gudang_id']);


          $updatems=$this->model_catalog_tabungms->updateQty($p['product_id'],$penj['gudang_id'],$p['quantity'],1);

          $kartustok=array(
            'tabung_id'	=> $p['product_id'],
            'tgl'	=> date('Y-m-d h:i:s',time()),
            'stokkeluar'	=> $p['quantity'],
            'stokmasuk'	=> 0,
            'ket'	=> $this->db->escape("Pembatalan Penjualan"),
            'saldo'	=> $updatems,
            'quantityawal'	=> $curmsqty['quantity'],
            'invoice'	=> $penj['no_sj'],
            'gudang_id'	=> $penj['gudang_id'],
            'type'	=> 3,
            'urlref'  => 'sale/penjualan',
            'idref' => $id,
            'no_dokumen'  => $penj['no_dokumen'],
          );

            $this->model_catalog_kartustoktabungstok->addKartuStok($kartustok);
        }
        if($p['tabung_id'] > 0){
          $this->db->update('tabung_mp',array('status' => 1,'date_modified'=>date('Y-m-d H:i:s',time())),array('id'=>$p['tabung_id']));
        //  $penj=$this->getPenjualanDetail(array('id'=>$penj['id']));
          $this->model_catalog_kartustoktabungmp->updateKartuStok($p['tabung_id'],$penj['customer_id'],date('Y-m-d',time()));
        }
      }

      $this->load->model('keuangan/jurnal');
      $this->model_keuangan_jurnal->updateJurnalumum(array('hapus'=>1),array('ref'=>$id,'type'=>7));

      return true;
    }else{
      return false;
    }
  }


   
  }

  public function updatePenjualan($data,$where=array()){
	$this->db->update('penjualan',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('penjualan',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order=array()){
		return $this->db->firstdetail('penjualan',$column,$join,$where,$order);
	}
  public function getPenjualanProducts($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('satuan.name as namasatuan','sales_order.id as id_salesorder','sales_order.no_so as no_salesorder','tabung_mp.no_tabung','penjualan_product.product_id','penjualan_product.pembulatan','penjualan_product.tabung_id','penjualan_product.id','penjualan_product.diskon','penjualan_product.pajak','penjualan_product.total','product.name','penjualan_product.quantity','penjualan_product.net_cost','penjualan_product.price','penjualan_product.quantitypesan','sales_order.id as nomor');
    $join=array();
    $join[]=array(
      'tablename' => 'product',
      'firsttable'  => 'penjualan_product.product_id',
      'secondtable' => 'product.product_id'
    );
    $leftjoin=array();
    $leftjoin[]=array(
      'tablename' => 'tabung_mp',
      'firsttable'  => 'penjualan_product.tabung_id',
      'secondtable' => 'tabung_mp.id'
    );
    $leftjoin[]=array(
      'tablename' => 'satuan',
      'firsttable'  => 'product.satuan',
      'secondtable' => 'satuan.id'
    );
    $leftjoin[]=array(
      'tablename' => 'sales_order',
      'firsttable'  => 'penjualan_product.no_so',
      'secondtable' => 'sales_order.id'
    );

    return $this->db->alljoins('penjualan_product',$column,$join,$leftjoin,$where,array(),0,null);
	}
  public function getPenjualanProduct($where){
		return $this->db->first('penjualan_product',$where);
	}
	public function getPenjualans($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order,$limit,$offset){
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$where['penjualan.sales']=$this->user->getId();
		}
		return $this->db->alljoins('penjualan',$column,$join,$leftjoin,$where,$order,$limit,$offset);
	}
  public function getPenjualanDetailss($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order,$limit,$offset){
		return $this->db->alljoin('penjualan',$column,$join,$leftjoin,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where,$join=array(),$leftjoin=array()){
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$where['penjualan.sales']=$this->user->getId();
		}
		return $this->db->countAll('penjualan',$where,$join,$leftjoin);
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

  public function getSjTanpaInv($customer_id,$gudang_id){
    //$column=array('penjualan.id as sales_order_id','COALESCE(sales_order.usia,0)','sales_order.no_so','sales_order.status as status_so','sales_order.jenispenjualan','sales_order.status','sales_order.gudang_id','gudang.nama as namagudang','sales_order.date_added','customer.name as namacustomer','customer.email','customer.telephone','product.name as namaproduct','sales_order_product.*');
    $column=array('penjualan_product.*','satuan.name as namasatuan','tabung_mp.no_tabung','product.name as namaproduct','penjualan.no_sj','sales_order.no_so as no_salesorder');
    $join=array();
    $join[]=array(
      'tablename' => 'product',
      'firsttable'  => 'penjualan_product.product_id',
      'secondtable' => 'product.product_id'
    );
    $join[]=array(
      'tablename' => 'penjualan',
      'firsttable'  => 'penjualan_product.sales_order_id',
      'secondtable' => 'penjualan.id'
    );
    $join[]=array(
      'tablename' => 'sales_order',
      'firsttable'  => 'penjualan_product.no_so',
      'secondtable' => 'sales_order.id'
    );

    $leftjoin=array();
    $leftjoin[]=array(
      'tablename' => 'tabung_mp',
      'firsttable'  => 'penjualan_product.tabung_id',
      'secondtable' => 'tabung_mp.id'
    );
    $leftjoin[]=array(
      'tablename' => 'satuan',
      'firsttable'  => 'product.satuan',
      'secondtable' => 'satuan.id'
    );

    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

    if($this->user->getUsername()=="pawitx"){
      $where=array(
        'penjualan.customer_id' => $customer_id,
        'penjualan.gudang_id' => $gudang_id,
        'penjualan_product.invoice_id'  => array('=',10144),
      'penjualan.status'	=> array('<',3)
      //'product_gudang.gudang_id' => $gudang_id,
        //'sales_order_product.status_pengiriman'  => array('<>',3),
      //  'sales_order.status'  => array('<>',1),
        //'sales_order_product'
      );
    }else{
      $where=array(
        'penjualan.customer_id' => $customer_id,
        'penjualan.gudang_id' => $gudang_id,
        'penjualan_product.invoice_id'  => array('<=',1),
      'penjualan.status'	=> array('<',3)
      //'product_gudang.gudang_id' => $gudang_id,
        //'sales_order_product.status_pengiriman'  => array('<>',3),
      //  'sales_order.status'  => array('<>',1),
        //'sales_order_product'
      );
    }
    
    /*if($custdata != 1){
			$where['penjualan.sales']=$this->user->getId();
		}*/
    $result=$this->db->alljoins('penjualan_product',$column,$join,$leftjoin,$where,array('penjualan_product.sales_order_id'=>'ASC'),0,null);
    /*$this->load->model('catalog/tabungmp');
    $result=$this->db->alljoin('sales_order_product',$column,$join,$where,array('sales_order.id'=>'ASC'),0,null);
    $hasil=array();
    */
    $hasil=array();
    foreach($result as $r){
      //if($r['status_so'] != 3){

      /*$r['tabung']="";
        if($r['jenispenjualan'] == 1){
          if($r['tabung_id'] > 0){
            $tabung=$this->model_catalog_tabungmp->getTabung($r['tabung_id']);
            $r['tabung']=$tabung['no_tabung'];
          }
        }*/
        if(($r['quantity']-$r['quantityreturn']) > 0){
          $hasil[]=$r;
        }
      //}
    }
    //$hasil=$result;

    //$this->log->write('Hasil ' . json_encode($hasil));
    //$this->log->write('data dikirim ' . $customer_id.' '.$gudang_id);
    return $hasil;

  }

  public function getSjTerkirim($customer_id,$gudang_id,$no_so){
    $column=array('penjualan_product.*','sales_order_product.totalpajak as totalpajakso','sales_order_product.pajak as pajakso','invoice.status as statuspembayaran','satuan.name as namasatuan','tabung_mp.no_tabung','product.name as namaproduct','penjualan.no_sj','sales_order.no_so as no_salesorder');
    $join=array();
    $join[]=array(
      'tablename' => 'product',
      'firsttable'  => 'penjualan_product.product_id',
      'secondtable' => 'product.product_id'
    );
    $join[]=array(
      'tablename' => 'penjualan',
      'firsttable'  => 'penjualan_product.sales_order_id',
      'secondtable' => 'penjualan.id'
    );
    $join[]=array(
      'tablename' => 'sales_order',
      'firsttable'  => 'penjualan_product.no_so',
      'secondtable' => 'sales_order.id'
    );
    $join[]=array(
      'tablename' => 'sales_order_product',
      'firsttable'  => 'penjualan_product.id',
      'secondtable' => 'sales_order_product.id'
    );

    $leftjoin=array();
    $leftjoin[]=array(
      'tablename' => 'tabung_mp',
      'firsttable'  => 'penjualan_product.tabung_id',
      'secondtable' => 'tabung_mp.id'
    );
    $leftjoin[]=array(
      'tablename' => 'satuan',
      'firsttable'  => 'product.satuan',
      'secondtable' => 'satuan.id'
    );
    $leftjoin[]=array(
      'tablename' => 'invoice',
      'firsttable'  => 'penjualan_product.invoice_id',
      'secondtable' => 'invoice.id'
    );

    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

    $where=array(
      'penjualan.customer_id' => $customer_id,
      'penjualan.gudang_id' => $gudang_id,
      'sales_order.id' => $no_so,
     // 'penjualan_product.invoice_id'  => array('<=',1),
	  'penjualan.status'	=> array('<',3)
	  
    );
    
    $result=$this->db->alljoins('penjualan_product',$column,$join,$leftjoin,$where,array('penjualan_product.sales_order_id'=>'ASC'),0,null);
   
    $hasil=array();
    
    $hasil=$result;

   
    return $hasil;
  }
  public function getSjBelumDo($customer_id,$gudang_id){
    $column=array('penjualan_product.*','satuan.name as namasatuan','product.name as namaproduct','penjualan.no_sj','sales_order.no_so as no_salesorder');
    $join=array();
    $join[]=array(
      'tablename' => 'product',
      'firsttable'  => 'penjualan_product.product_id',
      'secondtable' => 'product.product_id'
    );
    $join[]=array(
      'tablename' => 'penjualan',
      'firsttable'  => 'penjualan_product.sales_order_id',
      'secondtable' => 'penjualan.id'
    );
    $join[]=array(
      'tablename' => 'sales_order',
      'firsttable'  => 'penjualan_product.no_so',
      'secondtable' => 'sales_order.id'
    );

    $leftjoin=array();
    
    $leftjoin[]=array(
      'tablename' => 'satuan',
      'firsttable'  => 'product.satuan',
      'secondtable' => 'satuan.id'
    );

    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

    $where=array(
      'penjualan.customer_id' => $customer_id,
      'penjualan.gudang_id' => $gudang_id,
      'sales_order.jenispenjualan'  => 1,
      //'penjualan_product.quantitydo'  => array('<=',1),
      'penjualan.status'  => array('<',3),
      'product.jenistabung' => 1
      
    );
    /*if($custdata != 1){
			$where['penjualan.sales']=$this->user->getId();
		}*/
    $result=$this->db->alljoins('penjualan_product',$column,$join,$leftjoin,$where,array('penjualan_product.sales_order_id'=>'ASC'),0,null);
    /*$this->load->model('catalog/tabungmp');
    $result=$this->db->alljoin('sales_order_product',$column,$join,$where,array('sales_order.id'=>'ASC'),0,null);
    $hasil=array();
    */
    $hasil=array();
    foreach($result as $r){
      if($r['quantitydo'] < $r['quantity']){
        $hasil[]=$r;
      }
    }
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
    //$hasil=$result;

    $this->log->write('Hasil ' . json_encode($hasil));
    $this->log->write('data dikirim ' . $customer_id.' '.$gudang_id);
    return $hasil;
  }
  public function terimaPenjualan($data,$id){
    $penj=$this->getPenjualan(array('id'=>$id));
    if($penj['status'] == 1){
      $terima=array(
        'status'  => 2,
        'totaltabung' => $data['totaltabung'],
        'user_terima'  => $this->user->getId(),
        'tglterima' => $data['tglterima'],
        'inputterima' => date('Y-m-d H:i:s')
      );
      $this->model_sale_penjualan->updatePenjualan($terima,array('id'	=> $id));
      if($penj['do_id'] > 0){
        $data['customer_id']=$penj['customer_id'];
        $data['no_sj']=$penj['no_sj'];
        $data['id']=$id;
        $data['do_id']=$penj['do_id'];
        $this->addTerimaTabung($data);
      }
     
    }

  }

  public function addTerimaTabung($data){
    $this->load->model('catalog/tabungmp');
    $this->load->model('catalog/kartustoktabungmp');
    foreach($data['tabung'] as $p){
        if(!empty($p['tabung_id'])){
          $penj=array(
            'do_id' => $data['do_id'],
            'tabung_id' => $p['tabung_id'],
            'keterangan'  => $this->db->escape($p['keterangan']),
            'hapus' =>0,
            'penjualan_id'  => $data['id'],
            'doproduct_id'  => $p['doproduct_id']

            
          );

          /*status
          1. belum diterima
          2. sudah diterima
          */

          $this->db->insert('penjualan_tabung',$penj);

          //update tanggal pengembalian
             $tabung=$this->model_catalog_tabungmp->getTabung($p['tabung_id']);
          $tab=array(
            'status'	=> 6,
            'customer_id' => $data['customer_id'],
            'date_modified'	=> date('Y-m-d H:i:s',time())
          );
          
          $this->db->update('tabung_mp',$tab,array('id'=>$p['tabung_id']));
          //  $penj=$this->getPenjualanDetail(array('id'=>$penj['id']));
            $kartustok=array(
              'tabung_id'	=> $p['tabung_id'],
              'tglpeminjaman'	=> $data['tglterima'],
              'tglpengembalian'	=> '1901-01-01',
              'tglisiulang'	=> $data['tglterima'],
              'customer_id'	=> $data['customer_id'],
              'invoice'	=> $data['no_sj'],
              'ket'	=> 'Alokasi Penyewaan tabung no '.$data['no_sj'],
              'biayasewa'	=> 0,
              'tabel_ref' => 'sale/penjualan',
              'idref' => $data['id'],
              'jenistransaksi'  =>1
            );

            $this->model_catalog_kartustoktabungmp->addKartuStok($kartustok);
            
          //input kartustok
          //if($tabung['pemilik'] == 1){

           

      }
    }
  }
}
?>
