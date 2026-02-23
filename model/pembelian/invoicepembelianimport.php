<?php
class ModelPembelianInvoicepembelianimport extends Model {
	
	// baru 21 November 2019
	public function getpemb($id){
		$d = $this->db->query("SELECT jpb.name, bp.total, bp.totalreal,bp.jatuhtempo FROM biaya_pembelianimport bp LEFT JOIN jenis_biaya_pembelian jpb ON(jpb.id=bp.jenisbiaya_id) WHERE order_id='$id' ORDER BY bp.id DESC");
		return $d->rows;
	}
	// end baru
  /*
  1. ditagih
  2. belum lunas
  3. lunas
  4. dibatalkan
  */

  public function addPenjualan($data){
    $usia=1;


    $j=array(
      'tglfaktur' => isset($data['tglfaktur'])?$data['tglfaktur']:date('Y-m-d H:i:s'),
      'date_added' => date('Y-m-d H:i:s'),
      'jatuhtempo' => isset($data['jatuhtempo'])?$data['jatuhtempo']:date('Y-m-d H:i:s'),
      //'sales'  => $data['sales'],
      'vendor_id'  => $data['vendor_id'],
      'status' => 1,
      'statuspenerimaan' =>0,
      'hapus' => 0,
      'user_id'  => $this->user->getId(),
      'gudang_id' =>empty($data['gudang_id'])?0:$data['gudang_id'],
      'sub_total' => $data['sub_total'],
      'diskon'  => $data['diskon'],
      'pajak' => $data['pajak'],
      'total' => $data['total'],
      'totalbayar'  => 0,
      'cetak' => 0,
      'totaltagihan'  => empty($data['totaltagihan'])?$data['total']:$data['totaltagihan'],
      'jenisproduk'  => $data['jenisproduk'],
      'metode_pembayaran'  => $data['metode_pembayaran'],
      'no_faktur' => $data['no_faktur'],
      'inputkursdatang' => 0,
      'inputpib'  => 0,
      'statuspembayaranpib'  => 0,
      'keterangan' => isset($data['keterangan'])?$data['keterangan']:'-',
    );
    $this->db->insert('invoice_pembelian_import',$j);
    $id=$this->db->getlastId();
    $data['id']=$id;
    $no_dokumen='IPI-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;
    $data['no_dokumen']=$no_dokumen;
    $this->db->update('invoice_pembelian_import',array('no_dokumen'=>$no_dokumen),array('id'=> $id));
   
    $this->addPenjualanProduct($data);
    $this->addBiaya($data);
    //$this->addPib($data);




    return $id;

  }
  public function addPenjualanProduct($data){
    $total=0;
    $diskon=0;

  //  $penjualan=$this->getPenjualan(array('id' => $data['id']));

  $this->load->model('catalog/product');
    foreach($data['product'] as $p){
      if(!empty($p['product_id'])){
        if($p['pilih']){
        //getnetcost
        //$net=$this->db->first('product_toko_pameran',array('gudang_id'  => $penjualan['pameran_id'],'product_id'  => $p['product_id']));

        $curqty=$this->model_catalog_product->getProduct($p['product_id']);
        //$pajak=$p['price']*0.1;
      //  $total=($p['price']*$p['quantity']) + ($pajak*$p['quantity']);

        $penj=array(
          'invoice_id' => $data['id'],
          'product_id'  => $p['product_id'],
          'product_name'  => $p['name'],
          'po_id' => $p['po_id'],
          'po_product_id' => $p['po_product_id'],
          'quantity' => $p['quantity'],
          'price' => $p['price'],
          'diskon' => 0,
          'pajak' => $p['pajak'],
          'total' => $p['total'],
          'totalpajak' => $p['pajak']*$p['quantity'],
          'jenisproduk' => $data['jenisproduk'],
          'hapus' => 0

        );

      $this->db->insert('invoice_pembelian_import_product',$penj);
     $this->db->update('pembelian_produk_import',array('invoice_id'=> $data['id'],'quantity_invoice'=>$p['quantity']),array('id'=>$p['po_product_id']));
    }


    }
    }

  }

  public function addBiaya($data){
    $biaya=0;
    //$this->load->model('keuangan/jurnal');
    foreach($data['biaya'] as $b){

      $bia=array(
        'jenisbiaya_id'  => $this->db->escape($b['jenisbiaya_id']),
        'total' => $b['total'],
        'order_id'  => $data['id'],
        'statuspembayaran'  => 0
        /*'totalreal' => $b['total'],
        'order_id'  => $data['id'],
        'no_faktur' => empty($b['no_faktur'])?'':$b['no_faktur'],
        'tglfaktur' => empty($b['tglfaktur'])?'1970-01-01':$b['tglfaktur'],
        'akunpajakdimuka' => $b['akunpajakdimuka'],
        'pajakdimuka' => $b['pajakdimuka'],
        'akunhutangpajak' => $b['akunhutangpajak'],
        'hutangpajak' => $b['hutangpajak'],
        'statuspajakdimuka' => $b['statuspajakdimuka'],
        'statushutangpajak' => $b['statushutangpajak'],
        'perhitunganhpp'  => $b['perhitunganhpp']*/
      );
      $this->db->insert('biaya_pembelianimport',$bia);
      //$biaya += $b['total'];

    }



  }

  public function batalInvoice($data){
    $result=$this->getPermintaanPembelian(array(),array(),array('id'  => $data['id']));
  if($result['status'] == 1 & $result['statuspenerimaan'] == 0 /*& $result['inputpib'] != 1 & $result['statuspembayaranpib'] != 1 & $result['inputkursdatang'] != 1*/){
      /*$pib=array(
        'inputpib'=> 0,
        'statuspembayaranpib'=>0,
        'no_pib' => null,
        'userpib' => $this->user->getId(),
        'ppnpib'  => 0,
        'pphpib'  => 0,
        'bmpib' => 0,
        'kurspajakpib'  => 0
      );*/
      $inv=array(
        'status'  => 3
      );
      $this->db->update('invoice_pembelian_import',$inv,array('id'  => $data['id']));

      //batalkan biaya
      $this->db->delete('biaya_pembelianimport',array('order_id'=>$data['id']));

      //hapus data invoice_di po
      $this->db->update('pembelian_produk_import',array('invoice_id'=>0,'quantity_invoice'=> 0),array('invoice_id'=> $data['id']));
    }

  }





  public function updatePenjualan($data,$where=array()){
	$this->db->update('invoice_pembelian_import',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('invoice_pembelian_import',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order=array()){
		return $this->db->firstdetail('invoice_pembelian_import',$column,$join,$where,$order);
	}


  //public function getTotal

  public function getPenjualanProduct($where){
		return $this->db->first('invoice_pembelian_import_product',$where);
	}
  public function getPenjualanProductDetail($column,$join,$where){
		return $this->db->firstdetail('invoice_pembelian_import_product',$where);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('invoice_pembelian_import',$column,$join,$where,array());
  }

  public function getPermintaanPembelianProduct($where){
    return $this->db->all('invoice_pembelian_import_product',$where,array(),0,null);
  }
  public function getPermintaanPembelianProductDetail($where){
    return $this->db->firstdetail('invoice_pembelian_import_product',array(),array(),$where,array());
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('invoice_pembelian_import',$column,$join,$leftjoin,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('invoice_pembelian_import',$where);
	}
	public function getPenjualans($column=array(),$join=array(),$where=array(),$order,$limit,$offset){

		return $this->db->alljoin('invoice_pembelian_import',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where){
		return $this->db->count('invoice_pembelian_import',$where);
	}
  public function totalPenjualanDetail($where,$join){
    return $this->db->countAll('invoice_pembelian_import',$where,$join);
  }

  public function getTotalDp($referensi,$jenispenjualan){
    //ambil yg status 2/3
    $sql="SELECT COALESCE(SUM(totaltagihan),0) as total FROM invoice WHERE status <> 4 AND referensi='".$referensi."' AND jenispenjualan='".$jenispenjualan."' AND jenisinvoice < 3";
    $res=$this->db->query($sql);

    return $res->row['total'];
  }

  public function getPiutang($customer_id){
    $sql="SELECT COALESCE(SUM(totaltagihan)-SUM(totalbayar),0) as total FROM invoice WHERE status <> 4 AND jenisinvoice=3 AND customer_id='".$customer_id."' ";
    $res=$this->db->query($sql);

    return $res->row['total'];
  }

  public function getPermintaanPembelianBiaya($where){
    $join=array();
    $join[]=array(
      'tablename'=> 'jenis_biaya_pembelian',
      'firsttable'  => 'biaya_pembelianimport.jenisbiaya_id',
      'secondtable' => 'jenis_biaya_pembelian.id'
    );

    $leftjoin=array();

		$leftjoin[]=array(
			'tablename'	=> 'vendorlokal',
			'secondtable'	=>'vendorlokal.id',
			'firsttable'	=> 'biaya_pembelianimport.vendor_id'
		);
    return $this->db->alljoins('biaya_pembelianimport',array('vendorlokal.name as vendor','biaya_pembelianimport.*','jenis_biaya_pembelian.name'),$join,$leftjoin,$where,array(),0,null);
  }

  public function koreksibiaya($data){
    $pembelian=$this->getPermintaanPembelian(array(),array(),array('id'  => $data['id']));
    $totalreal=0;
    foreach($data['biaya'] as $b){
      if(!empty($b['id'])){
        $biaya=$this->getBiaya(array('id'=>$b['id']));
        if($biaya['statuspembayaran'] == 0){
          $bia=array(
            //'tglfaktur' => empty($b['tglfaktur'])?'1970-01-01':$b['tglfaktur'],
            'total' => $b['totalreal'],
            'totalreal' => $b['totalreal'],
            'statuspembayaran'  => 0
            //'no_faktur' => $b['no_faktur']
          );
          $this->db->update('biaya_pembelianimport',$bia,array('id'=>$b['id']));
        }
      }else{
        $bia=array(
          'jenisbiaya_id'  => $b['jenisbiaya_id'],
          'total' => $b['totalreal'],
          'totalreal' => $b['totalreal'],
          'vendor_id' => $b['vendor_id'],
          'order_id'  => $data['id'],
          'statuspembayaran'  => 0
          //'no_faktur' => empty($b['no_faktur'])?'':$b['no_faktur'],
          //'tglfaktur' => empty($b['tglfaktur'])?'1970-01-01':$b['tglfaktur']
        );
        $this->db->insert('biaya_pembelianimport',$bia);
        $this->load->model('keuangan/jurnal');

        $details=array();
        $details[]=array(
          'ref_akun'  => '2150',
          'keterangan'  => 'Hutang Usaha Belum Ditagih',
          'debet' => $data['totalestimasi'] - $data['total'] + $data['nilaipajak'],
          'kredit'  => 0,
          'urutan'  => 2,
          'hapus' => 0
          );
      }

    }

  //  }
  }

  public function addPib($data){
    $pembelian=$this->getPermintaanPembelian(array(),array(),array('id'  => $data['id']));
    if($pembelian['statuspembayaranpib'] != 1){
      $pib=array(
        'inputpib'=> 1,
        'statuspembayaranpib'=>0,
        'no_pib' => $this->db->escape($data['no_pib']),
        'userpib' => $this->user->getId(),
        'ppnpib'  => $data['ppnpib'],
        'pphpib'  => $data['pphpib'],
        'bmpib' => $data['bmpib'],
        'kurspajakpib'  => $data['kurspajakpib']
      );
      $this->db->update('invoice_pembelian_import',$pib,array('id'  => $data['id']));
    }

  }

  public function batalPib($data){
    $pembelian=$this->getPermintaanPembelian(array(),array(),array('id'  => $data['id']));
    if($pembelian['statuspembayaranpib'] != 1){
      $pib=array(
        'inputpib'=> 0,
        'statuspembayaranpib'=>0,
        'no_pib' => null,
        'userpib' => $this->user->getId(),
        'ppnpib'  => 0,
        'pphpib'  => 0,
        'bmpib' => 0,
        'kurspajakpib'  => 0
      );
      $this->db->update('invoice_pembelian_import',$pib,array('id'  => $data['id']));
    }

  }

  public function addKurs($data){
    $pembelian=$this->getPermintaanPembelian(array(),array(),array('id'  => $data['id']));
    if($pembelian['statuspenerimaan'] == 0){
      $pib=array(
        'inputkursdatang'=> 1,
        'tglkursdatang'=>$data['tglkursdatang'],
        'userinputkurs' => $this->user->getId(),
        'kursdatang'  => $data['kursdatang'],

      );
      $this->db->update('invoice_pembelian_import',$pib,array('id'  => $data['id']));
    }

  }

  public function addPembayaran($data){
    $data['date_added']=isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s',time());

    $this->load->model('pembelian/pembayarandepositimport');
    $this->load->model('catalog/vendorimport');
    $deposit=$this->model_pembelian_pembayarandepositimport->getPermintaanPembelian(array('COALESCE(totalalokasi,0) as totalalokasi'),array(),array('id'=>$data['deposit_id']));
    $totalalokasi=$deposit['totalalokasi'];
    /*deposit_id,invoice_id,nominal,status,kurs,user_id,tglalokasi,date_added,keterangan,hapus*/
    foreach($data['orders'] as $i){
      $inv=array(
        'deposit_id' => $data['deposit_id'],
        'invoice_id'  => $i['invoice_id'],
        'vendor_id' => $data['vendor_id'],
        'status'  =>1,
        'kurs'  => $data['kurs'],
        'nominal' => $i['total'],
        'user_id' => $this->user->getId(),
        'tglalokasi'  => $data['date_added'],
        'date_added'  => date('Y-m-d H:i:s'),
        'keterangan'  => 'Alokasi pembayaran invoice pembelian import',
        'hapus' => 0
      );
      $this->db->insert('alokasi_deposit_import',$inv);
      $id=$this->db->getLastId();
      $no_dokumen='ADI-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;
    $this->db->update('alokasi_deposit_import',array('no_dokumen'=>$no_dokumen),array('id'=> $id));
      
    $hutang=array(
      'ref'=> $id,
      'date_trans'	=> $data['date_added'],
      'saldomasuk'	=> 0,
      'saldokeluar'	=> $i['total'],
      'keterangan'	=> 'Alokasi pembayaran invoice pembelian import',
      'kurs'  => $data['kurs'],
      'hapus'	=> 0,
      'vendor_id'=> $data['vendor_id'],
      'no_dokumen'	=> $no_dokumen,
      'idref'	=> $i['invoice_id'],
      'urlref'	=> 'pembelian/pembayaranpembelianimport'
  
    );
    $this->model_catalog_vendorimport->addHistoryDeposit($hutang);
    $this->model_catalog_vendorimport->updateDeposit($data['vendor_id'],$i['total'],2);


      $totalalokasi +=$i['total'];


      $pb=$this->getPermintaanPembelian(array(),array(),array('id'=>$i['invoice_id']));
      //update total bayar
      $totalbayar=$pb['totalbayar'] + $i['total'];

      if(empty($pb['totalbayarrp'])){
        $pb['totalbayarrp']=0;
      }

      if($pb['totalbayarrp'] < 0){
        $pb['totalbayarrp']=0;
      }

      $totalbayarrp=$pb['totalbayarrp'] + ($i['total']*$data['kurs']);
      if($totalbayar >= $pb['totaltagihan']){
        $status=4;
      }else{
        $kekurangan=$pb['totaltagihan'] - $totalbayar;
        if($kekurangan < 0.01){
          $status=4;
        }else{
          $status=2;
        }
      }

      if($status == 4){
        $this->db->update('invoice_pembelian_import',array('status'=>4,'totalbayar'=>$totalbayar,'tgllunas'=>$data['date_added'],'totalbayarrp'=>$totalbayarrp),array('id'=>$i['invoice_id']));
      }else{
          $this->db->update('invoice_pembelian_import',array('status'=>2,'totalbayar'=>$totalbayar,'totalbayarrp'=>$totalbayarrp),array('id'=>$i['invoice_id']));
      }
      //$this->db->update('invoice',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'=>$i['invoice_id']));
    }
    $this->db->update('pembayaran_deposit_import',array('totalalokasi'=>$totalalokasi),array('id'=>$data['deposit_id']));

  }

  public function batalPembayaran($id){
    $alokasi=$this->db->first('alokasi_deposit_import',array('id'=>$id));

    //cek status barang datang
    $invoice=$this->getPermintaanPembelian(array(),array(),array('id'=>$alokasi['invoice_id']));

    $this->load->model('pembelian/pembayarandepositimport');
    $this->load->model('catalog/vendorimport');

    if($alokasi['status'] == 1){
      $deposit=$this->model_pembelian_pembayarandepositimport->getPermintaanPembelian(array('COALESCE(totalalokasi,0) as totalalokasi'),array(),array('id'=>$alokasi['deposit_id']));
      $totalalokasi=$deposit['totalalokasi'];

      if($invoice['statuspenerimaan'] == 0){
        $this->db->update('alokasi_deposit_import',array('status' => 2),array('id'=>$id));

        $totalalokasi -=$alokasi['nominal'];

        $this->db->update('pembayaran_deposit_import',array('totalalokasi'=>$totalalokasi),array('id'=>$alokasi['deposit_id']));

        $totalbayar=$invoice['totalbayar'] - $alokasi['nominal'];

        if(empty($invoice['totalbayarrp'])){
          $invoice['totalbayarrp']=0;
        }

        if($invoice['totalbayarrp'] < 0){
          $invoice['totalbayarrp']=0;
        }

        $totalbayarrp = $invoice['totalbayarrp'] - ($alokasi['nominal']*$alokasi['kurs']);
        if($totalbayar >= $invoice['totaltagihan']){
          $status=4;
        }else{
          //$kekurangan=$invoice['totaltagihan'] - $totalbayar;
          if($totalbayar == 0){
            $status=1;
          }else{
            $status=2;
          }
        }

        if($status == 4){
          $this->db->update('invoice_pembelian_import',array('status'=>4,'totalbayar'=>$totalbayar,'tgllunas'=>$data['date_added'],'totalbayarrp'=>$totalbayarrp),array('id'=>$alokasi['invoice_id']));
        }else{
            $this->db->update('invoice_pembelian_import',array('status'=>$status,'totalbayar'=>$totalbayar,'totalbayarrp'=>$totalbayarrp),array('id'=>$alokasi['invoice_id']));
        }

        $hutang=array(
          'ref'=> $id,
          'date_trans'	=> date('Y-m-d H:i:s'),
          'saldomasuk'	=> $alokasi['nominal'],
          'saldokeluar'	=> 0,
          'keterangan'	=> 'Pembatalan Alokasi pembayaran invoice pembelian import',
          'hapus'	=> 0,
          'vendor_id'=> $alokasi['vendor_id'],
          'kurs'  => $alokasi['kurs'],
          'no_dokumen'	=> $alokasi['no_dokumen'],
          'idref'	=> $alokasi['invoice_id'],
          'urlref'	=> 'pembelian/pembayaranpembelianimport'
      
        );
        $this->model_catalog_vendorimport->addHistoryDeposit($hutang);
        $this->model_catalog_vendorimport->updateDeposit($alokasi['vendor_id'],$alokasi['nominal'],1);
  
      }
    }
  }

  public function getPermintaanPembelianPembayaran($where){
    $join=array();

    return $this->db->alljoin('alokasi_deposit_import',array(),$join,$where,array(),0,null);
  }

  public function barangdatang($data){
    $sj=array(
      'no_suratjalan' => $this->db->escape($data['no_suratjalan']),
      'pembelian_import_id'=> $data['pembelian_import_id'],
      'penerima'  => $this->db->escape($data['penerima']),
      'pengangkut'  => $this->db->escape($data['pengangkut']),
      'no_pol'  =>$this->db->escape($data['no_pol']),
      'gudang_id' => empty($data['gudang_id'])?0:$data['gudang_id'],
      'date_added' => date('Y-m-d H:i:s',time()),
      'total' => 0,
      'totalquantity' => 0,
      'hapus' => 0,
      'kursbank'  => !empty($data['kursbank'])?$data['kursbank']:0,
      'kursbi'  => !empty($data['kursbi'])?$data['kursbi']:0,
      'kurskmk'  => !empty($data['kurskmk'])?$data['kurskmk']:0,
      'penerima_id'  => !empty($data['penerima_id'])?$data['penerima_id']:0,
      'pengangkut_id'  => !empty($data['pengangkut_id'])?$data['pengangkut_id']:0,
      'tgl_surat' => empty($data['tgl_surat'])?date('Y-m-d H:i:s',time()):$data['tgl_surat'],
      'tgl_terima' => empty($data['tgl_terima'])?date('Y-m-d H:i:s',time()):$data['tgl_terima']
    );
    $this->db->insert('suratjalan_pembelianimport',$sj);
    $sj_id=$this->db->getLastId();
    //$this->db->update('pembelian_import',array('status'  => 1),array('id' => $data['id']));
    $this->load->model('gudang/kartustok');
    $this->load->model('keuangan/jurnal');
    //update stok
    $pb=$this->getPermintaanPembelian(array(),array(),array('id'  => $data['pembelian_import_id']));
    $total=0;
    $totalpersediaan=0;
    $totalquantity=0;
    $totalquantitybeli=0;
	$totalbiayadatang=0;

    //biaya
    $totalb=$this->db->query("SELECT COALESCE(SUM(totalreal),0) as totalbiaya FROM biaya_pembelianimport WHERE order_id='".$data['pembelian_import_id']."' AND statuspembayaran <> 4 ");
   $totalbiaya=$totalb->row['totalbiaya']+$pb['bmpib'];
   $totalbiayahutang=$pb['bmpib'];
   $totalbelumditagih=$totalb->row['totalbiaya'];
   $semua=true;
   
   $totalpajak=0;

    foreach($data['products'] as $p){

      $detail=$this->getPermintaanPembelianProductDetail(array('id' => $p['id']));
      $totalquantitybeli += $detail['quantity'];
      if($p['quantityterima'] + $detail['quantityterima'] > $detail['quantity']){
        $p['quantityterima'] =0;
      }
      if(empty($p['quantityterima'])){
        $p['quantityterima'] =0;
      }
      
      
      if($p['quantityterima'] > 0){

        $pro=array(
          'id_suratjalan' => $sj_id,
          //'no_suratjalan' => $this->db->escape($data['no_suratjalan']),
          'pembelian_product_id'  => $p['id'],
          'quantity'  => $p['quantityterima']
        );
        $this->db->insert('suratjalan_produkimport',$pro);

        $detail=$this->getPermintaanPembelianProductDetail(array('id' => $p['id']));
        $total +=($detail['price'] + $detail['pajak'] - $detail['diskon'])*$p['quantityterima'];
        $totalquantity += $p['quantityterima'];
		
        $totalpersediaan += (($detail['price'] - $detail['diskon'])*$p['quantityterima'])*$pb['kursdatang'];
		 
        if($p['quantityterima'] + $detail['quantityterima'] != $detail['quantity']){
          $semua=false;
          
        }
        /*else{
          $semua=false;
          $this->db->update('invoice_pembelian_import_product',array('quantityterima'  => $p['quantityterima']+$detail['quantityterima']),array('id' => $p['id']));
        }*/

        $this->db->update('invoice_pembelian_import_product',array('quantityterima'  => $p['quantityterima']+$detail['quantityterima']),array('id' => $p['id']));

        $biaya=0;
        if($totalbiaya > 0){
          $biaya=((($detail['price'] + $detail['ppn'])*$pb['kursdatang'])/(($pb['sub_total']+$pb['pajak'])*$pb['kursdatang']))*$totalbiaya;
        }
        $diskon=0;
        if($pb['diskon'] > 0){
          $diskon=((($detail['price'] + $detail['ppn'])*$pb['kursdatang'])/(($detail['price'] + $detail['ppn'])*$pb['kursdatang']))*($pb['diskon']*$pb['kursdatang']);
        }
        $harga=(($detail['price'] + $detail['ppn'])*$pb['kursdatang'])-$diskon+$biaya;
		
		$totalbiayadatang += $p['quantityterima'] * $biaya;
		$totalpajak += $detail['ppn'] * $p['quantityterima'];

        if($pb['jenisproduk'] == 1){
          //bahan baku
          $this->load->model('catalog/bahanbaku');
          $curqty=$this->model_catalog_bahanbaku->getProduct($detail['product_id']);
          $update=$this->model_catalog_bahanbaku->updateQty($detail['product_id'],$p['quantityterima'],1);

          $kartustok=array(
            'product_id'	=> $detail['product_id'],
            'product_name'	=> $detail['product_name'],
      			'tglawal'	=> $data['tglawal'].' '.$data['jamawal'],
      			'tglakhir'	=> $data['tglakhir'].' '.$data['jamakhir'],
      			'levelawal'	=> $p['levelawal'],
      			'levelakhir'	=> $p['levelakhir'],
      			'qtyawal'	=> $curqty['quantity'],
      			'qtyakhir'	=> $update,
      			'ket'	=> 'Set Stok Awal',
      			'perubahan'	=> $p['quantityterima'],
      			'ref'	=> $sj_id,
      			//'gudang_id'	=> $data['gudang_id'],
            'type'	=> 1,
            'urlref'  => 'pembelian/barangdatangimport',
            'idref' => $data['pembelian_import_id'],
            'no_dokumen'  => $pb['no_dokumen']
      		);

          $this->model_gudang_kartustok->addKartuStokBahanbaku($kartustok);


          if($curqty['quantity'] > 0){
            //if(){
              if($curqty['net_cost'] > 0){
                $netcost=(($curqty['quantity']*$curqty['net_cost'])+($p['quantityterima'] * $harga))/($curqty['quantity']+$p['quantityterima']);
              }else{
                $netcost=($p['quantityterima'] * $harga)/($p['quantityterima']);
              }
            //}
          }else{
            $netcost=($p['quantityterima'] * $harga)/($p['quantityterima']);
          }
          $this->db->update('bahanbaku',array('hargabeli' => $net_cost,'level'=>$p['levelakhir']),array('id'  => $detail['product_id']));
        }
        if($pb['jenisproduk'] == 2){
          //produk dagang gudang
          $this->load->model('gudang/product');
          $curqty=$this->model_gudang_product->getProduct($detail['product_id'],$data['gudang_id']);
          $update=$this->model_gudang_product->updateQty($detail['product_id'],$data['gudang_id'],$p['quantityterima'],1);

        //  $netcost=(($curqty['quantity']*$curqty['net_cost'])+($p['quantityterima'] * $detail['harga']))/$curqty['quantity']+$p['quantityterima'];

          //$this->model_gudang_product->updateNetCost($detail['product_id'],$data['gudang_id'],$netcost);

          $kartustok=array(
            'product_id'	=> $detail['product_id'],
            'product_name'	=> $detail['name'],
            'tgl'	=> date('Y-m-d h:i:s',time()),
            'stokkeluar'	=> 0,
            'stokmasuk'	=> $p['quantityterima'],
            'ket'	=> 'Pembelian produk dagang import',
            'saldo'	=> $update,
            'quantityawal'	=> $curqty['quantity'],
            'invoice'	=> $data['id'],
            'gudang_id'	=> $data['gudang_id'],
            'type'	=> 1,
            'idref' => $data['pembelian_import_id'],
            'urlref'  => 'pembelian/invoicepembelianimport',
            'no_dokumen'  => $pb['no_dokumen']
          );

          $this->model_gudang_kartustok->addKartuStok($kartustok);

          if($curqty['quantity'] > 0){
            //if(){
              if($curqty['net_cost'] > 0){
                $netcost=(($curqty['quantity']*$curqty['net_cost'])+($p['quantityterima'] * $harga))/($curqty['quantity']+$p['quantityterima']);
              }else{
                $netcost=($p['quantityterima'] * $harga)/($p['quantityterima']);
              }
            //}
          }else{
            $netcost=(($p['quantityterima'] * $harga))/($p['quantityterima']);
          }
          $this->model_gudang_product->updateNetCost($detail['product_id'],$data['gudang_id'],$netcost);

        }
        if($pb['jenisproduk'] == 3){
          //atk
          $this->load->model('catalog/atk');
          $curqty=$this->model_catalog_atk->getAtk($detail['product_id']);
          $update=$this->model_catalog_atk->updateQty($detail['product_id'],$p['quantityterima'],1);
        //  $netcost=(($curqty['qty']*$curqty['net_cost'])+($p['quantityterima'] * ($detail['harga']+$detail['ppn'])))/$curqty['qty']+$p['quantityterima'];

        //  $this->model_catalog_atk->updateNetCost($detail['product_id'],$netcost);

          $kartustok=array(
            'product_id'	=> $detail['product_id'],
            'product_name'	=> $detail['name'],
            'tgl'	=> date('Y-m-d h:i:s',time()),
            'stokkeluar'	=> 0,
            'stokmasuk'	=> $p['quantityterima'],
            'ket'	=> 'Pembelian ATK',
            'saldo'	=> $update,
            'quantityawal'	=> $curqty['qty'],
            'invoice'	=> $data['id'],
            //'gudang_id'	=> $data['gudang_id'],
            'type'	=> 1
          );

          $this->model_gudang_kartustok->addKartuStokGlobal('kartustok_atk',$kartustok);

          if($curqty['qty'] > 0){
            //if(){
              if($curqty['net_cost'] > 0){
                $netcost=(($curqty['qty']*$curqty['net_cost'])+($p['quantityterima'] * $harga))/($curqty['qty']+$p['quantityterima']);
              }else{
                $netcost=($p['quantityterima'] * $harga)/($p['quantityterima']);
              }
            //}
          }else{
            $netcost=(($p['quantityterima'] * $harga))/($p['quantityterima']);
          }

          $this->model_catalog_atk->updateNetCost($detail['product_id'],$netcost);
        }
        if($pb['jenisproduk'] == 4){
          //aset
          $this->load->model('catalog/kelompokaset');
          //$ak=$this->model_catalog_kelompokaset->getAktiva(array('no_akun'  => $pb['jenis_aktiva']));
          /*if($ak['no_akun'] == '12.01.06'){
            $this->load->model('catalog/tabungmp');
          //  $this->db->update('tabungmp',array('status' => 1, 'hargabeli' => ($detail['harga'] + $detail['ppn'])*$data['kurskmk'],'tglpembelian'=>date('Y-m-d',time())),array('id'  => $detail['product_id']));
            $this->db->update('tabung_mp',array('status' => 1,'tglpembelian'=>date('Y-m-d',time())),array('id'  => $detail['product_id']));
          }
          */

          $this->load->model('catalog/aset');
          $aset=$this->model_catalog_aset->getAset(array('aset_id'=>$detail['product_id']));
      		$kel=$this->model_catalog_kelompokaset->getKelompokaset($aset['kelompok_aset']);

      		$manfaat=$kel['masa_manfaat'];
      		$tarif=$kel['nilai_depresiasi'];

      		$penyusutantahunan=($tarif/100)*($harga/$manfaat);
      		$penyusutanbulanan=$penyusutantahunan/12;
          $this->db->update('aset',array('status' => 1, 'hargabeli' => $harga,'nilaibuku' => $harga,'tglpembelian'=>$pb['tglfaktur'],'akumulasipenyusutan'=>0,'penyusutan'=>$penyusutantahunan,'penyusutanbulanan'=>$penyusutanbulanan),array('aset_id'  => $detail['product_id']));
        }
        if($pb['jenisproduk'] == 5){
          //tabung mp
          $this->load->model('catalog/tabungmp');

          $this->load->model('catalog/aset');
          $aset=$this->model_catalog_tabungmp->getTabung($detail['product_id']);
          $kel=$this->model_catalog_kelompokaset->getKelompokaset($aset['kelompok_aset']);

          $manfaat=$kel['masa_manfaat'];
          $tarif=$kel['nilai_depresiasi'];

          $penyusutantahunan=($tarif/100)*($harga/$manfaat);
          $penyusutanbulanan=$penyusutantahunan/12;
          //$this->db->update('tabung_mp',array('status' => 1, 'hargabeli' => ($detail['harga'] + $detail['ppn'])*$data['kurskmk'],'tglpembelian'=>date('Y-m-d',time())),array('id'  => $detail['product_id']));
          $this->db->update('aset',array('status' => 1, 'hargabeli' => $harga,'nilaibuku' => $harga,'tglpembelian'=>$pb['tglfaktur'],),array('id'  => $detail['product_id']));
      }
    }
    }

    $this->db->update('suratjalan_pembelianimport',array('total'  => $total,'totalquantity' => $totalquantity),array('id'=>$sj_id));
    if($semua){
      $this->db->update('invoice_pembelian_import',array('statuspenerimaan'  => 1),array('id' => $data['pembelian_import_id']));
    }else{
      $this->db->update('invoice_pembelian_import',array('statuspenerimaan'  => 2),array('id' => $data['pembelian_import_id']));
    }

    //uang muka = $pb['totalbayarrp'](K)
    //persediaan (D)
    //labarugi K-D

    $this->load->model('keuangan/jurnal');


    $this->load->model('keuangan/pajak');
	if($totalquantity > 0){
		$totalbayarrp=($totalquantity/$totalquantitybeli) * $pb['totalbayarrp'];
		$selisih=$totalbayarrp - $totalpersediaan;
		//dibayar lebih besar berarti debet persediaan lebih besar berarti kredit

		$details=array();
		$details[]=array(
		  'ref_akun'  => $data['jenispersediaan'],
		  'keterangan'  => 'Barang datang import',
		  'debet' => $totalpersediaan+$totalbiayadatang,
		  'kredit'  => 0,
		  'urutan'  => 1,
		  'hapus' => 0
		);
		/*belum diupdate*/
		if($pb['pajak'] > 0){
		  $details[]=array(
			'ref_akun'  =>'1555',
			'debet' => $totalpajak*$pb['kursdatang'],
			'kredit'  => 0,
			'urutan'  =>2,
			'keterangan'  => 'PPN Masukan'
		  );

		  $pajak=array(
			'ref' => $data['pembelian_import_id'],
			'jumlah'  => $pb['pajak']*$pb['kursdatang'],
			'akun' => '1555',
			'jenis' => 1
		  );
		  $this->model_keuangan_pajak->addPajak($pajak);
		}
		if($selisih > 0){
		  $details[]=array(
			'ref_akun'  => '9001',
			'keterangan'  => 'Laba Rugi Selisih Kurs',
			'debet' => $selisih,
			'kredit'  => 0,
			'urutan'  => 3,
			'hapus' => 0
		  );
		}
		$details[]=array(
		  'ref_akun'  => '1311',
		  //'jenis_akun'  => 52,
		  'keterangan'  => 'Uang Muka Pembelian Persediaan',
		  'debet' => 0,
		  'kredit'  => $totalbayarrp,
		  'urutan'  => 4,
		  'hapus' => 0
		);
		if($selisih < 0){
		  $details[]=array(
			'ref_akun'  => '9001',
			'keterangan'  => 'Laba Rugi Selisih Kurs',
			'kredit' => abs($selisih),
			'debet'  => 0,
			'urutan'  => 5,
			'hapus' => 0
		  );
		}
		if($totalbiayahutang > 0){
			$tbh=($totalquantity/$totalquantitybeli) * $totalbiayahutang;
			$details[]=array(
				'ref_akun'  => '2101',
				'keterangan'  => 'Hutang Usaha',
				'kredit' => $tbh,
				'debet'  => 0,
				'urutan'  => 6,
				'hapus' => 0
			  );
		}
		if($totalbelumditagih > 0){
			$tbd= ($totalquantity/$totalquantitybeli) * $totalbelumditagih;
		  $details[]=array(
			'ref_akun'  => '2150',
			'keterangan'  => 'Hutang Usaha Belum Ditagih',
			'kredit' => $tbd,
			'debet'  => 0,
			'urutan'  => 7,
			'hapus' => 0
		  );
		}
		/*$totalbiayahutang=$pb['bmpib'];
	   $totalbelumditagih=$totalb->row['totalbiaya']; */

		$j=array(
		  'tanggal' => $data['tgl_terima'],
		  'keterangan'  => 'Pembelian Import '.$pb['no_faktur'],
		  'details' => $details,
		  'hapus' =>0,
		  'ref' => $data['pembelian_import_id'],
		  'type'  => 6,
      'idref' => $data['pembelian_import_id'],
      'urlref'  => 'pembelian/invoicepembelianimport',
      'no_dokumen'  => $pb['no_dokumen']
		);
		$this->model_keuangan_jurnal->addJurnalUmum($j);

	}

  }
  public function getBiaya($where){
		return $this->db->firstdetail('biaya_pembelianimport',array(),array(),$where,array(),0,null);
	}

  public function addPembayaranPib($data){
    $this->load->model('keuangan/bank');
    $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$data['bank_id']));
    $saldo=$b['saldo'] - $data['nominal'];

    $this->model_keuangan_bank->updateBank(array('saldo' => $saldo),array('id'  => $data['bank_id']));
    $curb=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$data['bank_id']));
    $sal=$curb['saldo'];

    //$this->load->model('keuangan/tagihanbiaya');
    $invoice=$this->getPermintaanPembelian(array(),array(),array('id'=>$data['order_id']));

    $p=array(
      'order_id' => $data['order_id'],
      'nominal'  => $data['nominal'],
      'tgl_bayar'  => $data['tgl_bayar'],
      'status'  => 1,
      'keterangan'  => $this->db->escape($data['keterangan']),
      'bank_id' => $data['bank_id']
      /*'akun_hutang' => $biaya['akun_hutang'],
      'akun_kas'  =>$curb['rek_parent']*/
    );

    $this->db->insert('pembayaran_pib',$p);
    $id=$this->db->getLastId();
//update total bayar
    //$this->load->model('keuangan/tagihan');

    //$biaya=$this->model_keuangan_tagihanbiaya->getPermintaanPembelian(array(),array(),array('id'=>$data['order_id']));
    $totalbayar=$this->db->query("SELECT COALESCE(SUM(nominal),0) as total FROM pembayaran_pib WHERE status=1 AND order_id='".$data['order_id']."'");
    $totalba=$totalbayar->row['total'];
    $tagihan=$invoice['ppnpib']+$invoice['pphpib']+$invoice['bmpib'];
    if($totalba == $tagihan){
      $status=1;

    }else{
      $status=2;

    }
    $this->db->update('invoice_pembelian_import',array('statuspembayaranpib'=>$status),array('id'  => $data['order_id']));
    $ak=array(
      'date_added' => date('Y-m-d H:i:s'),
      'date_trans'  => isset($data['tgl_bayar'])?$data['tgl_bayar']:date('Y-m-d H:i:s'),
      'bank_id' => $data['bank_id'],
      'saldomasuk'  => 0,
      'saldokeluar' => $data['nominal'],
      'saldoawal' => $b['saldo'],
      'saldoakhir'  => $sal,
      'ref' => $id,
      'keterangan'  => $this->db->escape($data['keterangan']),
      'type'  => 5009,
      'date_modified' => date('Y-m-d H:i:s'),
      'ref_akun'  => $curb['rek_parent'],
      'idref' =>$data['order_id'],
      'urlref'  => 'pembelian/invoicepembelianimport',
      'no_dokumen'  => $invoice['no_dokumen'],
      'jurnal_id' => 0
      
    );
    $this->model_keuangan_bank->addAruskas($ak);
    //jurnal
    $this->load->model('keuangan/jurnal');

    $jurnal=array();
    $detail=array();
    if($data['nominal'] > 0){
      if($status == 1){
        /*
        1552 pph 22
        2505 ppn keluaran
        */
        if($invoice['ppnpib'] > 0){
          $detail[]=array(
            'ref_akun'  => '1555',
            'keterangan'  => $this->db->escape('PPn Masukan'),
            'debet' => ($invoice['ppnpib']),
            'kredit'  => 0,
            'urutan'  => 1,
          );
        }

        if($invoice['pphpib'] > 0){
          $detail[]=array(
            'ref_akun'  => '1552',
            'keterangan'  => $this->db->escape('PPh Pasal 22'),
            'debet' => ($invoice['pphpib']),
            'kredit'  => 0,
            'urutan'  => 2,
          );
        }

        if($invoice['bmpib'] > 0){
          $detail[]=array(
            'ref_akun'  => '2101',
            'keterangan'  => $this->db->escape('Hutang Usaha Pajak BM'),
            'debet' => ($invoice['bmpib']),
            'kredit'  => 0,
            'urutan'  => 3,
          );
        }
        $total=$invoice['ppnpib']+$invoice['pphpib']+$invoice['bmpib'];
        if($b['saldo'] < ($total)){

          if($b['hutangprk'] == 1){
            if($b['saldo'] > 0){
              $hutangprk=abs($b['saldo'] - $total);
              $detail[]=array(
                'ref_akun'  => $curb['rek_parent'],
                'keterangan'  => $this->db->escape('Pembayaran PIB Pembelian Import'),
                'kredit' => $b['saldo'],
                'debet'  => 0,
                'urutan'  => 4,
              );
              $detail[]=array(
                'ref_akun'  => '2001',
                'keterangan'  => $this->db->escape('Hutang PRK'),
                'kredit' => $hutangprk,
                'debet'  => 0,
                'urutan'  => 5,
              );
            }else{
              $hutangprk=$data['nominal'];
              $detail[]=array(
                'ref_akun'  => '2001',
                'keterangan'  => $this->db->escape('Hutang PRK'),
                'kredit' => $hutangprk,
                'debet'  => 0,
                'urutan'  => 4,
              );
            }


          }else{
            $detail[]=array(
              'ref_akun'  => $curb['rek_parent'],
              'keterangan'  => $this->db->escape('Pembayaran PIB pembelian import'),
              'kredit' => $total,
              'debet'  => 0,
              'urutan'  => 4,
            );
          }
        }else{
          $detail[]=array(
            'ref_akun'  => $curb['rek_parent'],
            'keterangan'  => $this->db->escape('Pembayaran PIB'),
            'kredit' => $total,
            'debet'  => 0,
            'urutan'  => 4,
          );
        }



      $jurnal=array(
        'tanggal' => $data['tgl_bayar'],
        'keterangan'  => 'Pembayaran PIB  '.$invoice['no_faktur'],
        'hapus' => 0,
        'ref' => $id,
        'type'  => 5009,
        'details'  => $detail,
        'idref' =>$invoice['id'],
        'urlref'  => 'pembelian/invoicepembelianimport',
        'no_dokumen'  => $invoice['no_dokumen']
      );
      $jurnal_id=$this->model_keuangan_jurnal->addJurnalUmum($jurnal);
      

    }
  }
  return $totalba.' '.$tagihan;
}
public function totalbayarpib($id){
  $totalbayar=$this->db->query("SELECT COALESCE(SUM(nominal),0) as total FROM pembayaran_pib WHERE status=1 AND order_id='".$id."'");
  return $totalbayar->row['total'];
}

public function getPembayaranPib($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
  return $this->db->alljoin('pembayaran_pib',$column,$join,$where,$order,$limit,$offset);
}

public function getPembayaranPibDetail($column=array(),$join=array(),$where=array(),$order=array()){
  return $this->db->firstdetail('pembayaran_pib',$column,$join,$where,$order);
}

public function batalkanPembayaranpib($pembayaran_id){
  $detail=$this->getPembayaranPibDetail(array(),array(),array('pembayaran_id'=>$pembayaran_id));
  if($detail['status'] == 1){
    $inv=$this->getPermintaanPembelian(array(),array(),array('id'=>$detail['order_id']));
    $this->db->update('pembayaran_pib',array('status'=>0),array('pembayaran_id'=>$pembayaran_id));

    $this->load->model('keuangan/bank');
    $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$detail['bank_id']));
    $saldo=$b['saldo'] + $detail['nominal'];

    $this->model_keuangan_bank->updateBank(array('saldo' => $saldo),array('id'  => $detail['bank_id']));
    $curb=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$detail['bank_id']));
    $sal=$curb['saldo'];


    $ak=array(
      'date_added' => date('Y-m-d H:i:s'),
      'date_trans'  => isset($detail['tgl_bayar'])?$detail['tgl_bayar']:date('Y-m-d H:i:s'),
      'bank_id' => $detail['bank_id'],
      'saldokeluar'  => 0,
      'saldomasuk' => $detail['nominal'],
      'saldoawal' => $b['saldo'],
      'saldoakhir'  => $sal,
      'ref' => $pembayaran_id,
      'keterangan'  => $this->db->escape('Pembatalan Pembayaran PIB Pembelian Import'),
      'type'  => 5009,
      'date_modified' => date('Y-m-d H:i:s'),
      'ref_akun'  => $curb['rek_parent'],
      'idref' =>$inv['id'],
      'urlref'  => 'pembelian/invoicepembelianimport',
      'no_dokumen'  => $invoice['no_dokumen'],
      'jurnal_id' => 0
    );


    $this->model_keuangan_bank->addAruskas($ak);

    if($inv['statuspembayaranpib'] == 2){


      $totalbayarpib=$this->totalbayarpib($detail['order_id']);

      if($totalbayarpib >= ($inv['ppnpib']+$inv['pphpib']+$inv['bmpib'])){
        $status=1;
      }else if($totalbayarpib > 0){
        $status=2;

      }else{
        $status=0;
      }

      $this->db->update('invoice_pembelian_import',array('statuspembayaranpib'=>$status),array('id'  => $detail['order_id']));
    }

    if($inv['statuspembayaranpib'] == 1){
    //  $this->db->update('pembayaran_pib',array('status'=>0),array('pembayaran_id'=>$pembayaran_id));

      $totalbayarpib=$this->totalbayarpib($detail['order_id']);
      if($totalbayarpib >= ($inv['ppnpib']+$inv['pphpib']+$inv['bmpib'])){
        $status=1;
      }else if($totalbayarpib > 0){
        $status=2;

      }else{
        $status=0;
      }

      $this->db->update('invoice_pembelian_import',array('statuspembayaranpib'=>$status),array('id'  => $detail['order_id']));

      $ju=$this->db->first('jurnal_umum',array('ref'=>$pembayaran_id));
      $this->db->delete('jurnal_umum_detail',array('jurnal_id'=>$ju['id']));
      $this->db->delete('jurnal_umum',array('id'=>$ju['id']));
    }
  }
  return $detail;
}


}
?>
