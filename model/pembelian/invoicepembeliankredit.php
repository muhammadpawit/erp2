<?php
class ModelPembelianInvoicepembeliankredit extends Model {
  /*
  1. ditagih
  2. belum lunas
  3. lunas
  4. dibatalkan
  */

  public function addPenjualan($data){
    $usia=1;

    if($data['totalpo'] != $data['total']){
      $status=5;
    }else{
      $status=1;
    }
    $j=array(
      'tglfaktur' => isset($data['tglfaktur'])?$data['tglfaktur']:date('Y-m-d H:i:s'),
      'date_added' => date('Y-m-d H:i:s'),
      'jatuhtempo' => isset($data['jatuhtempo'])?$data['jatuhtempo']:date('Y-m-d H:i:s'),
      //'sales'  => $data['sales'],
      'vendor_id'  => $data['vendor_id'],
      'status' => $status,
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
      'biayakirim'  => empty($data['biayakirim'])?0:$data['biayakirim']

    );
    $this->db->insert('invoice_pembelian',$j);
    $id=$this->db->getlastId();
    $data['id']=$id;

    //add product
    $this->addPenjualanProduct($data);
    $this->addBiaya($data);



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
          'quantityterima' => $p['quantityterima'],
          'price' => $p['price'],
          'diskon' => 0,
          'pajak' => $p['pajak'],
          'total' => $p['total'],
          'totalpajak' => $p['pajak']*$p['quantity'],
          'jenisproduk' => $data['jenisproduk'],
          'hapus' => 0

        );

      $this->db->insert('invoice_pembelian_product',$penj);
    // $this->db->update('pembelian_produk_kredit',array('invoice_id'=> $data['id'],'quantity_invoice'=>$p['quantity']),array('id'=>$p['po_product_id']));
    }


    }
    }

  }

  public function addBiaya($data){
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
      $this->db->insert('biaya_pembelian',$bia);

    }

  }

  public function batalInvoice($data){
    $result=$this->getPermintaanPembelian(array(),array(),array('id'  => $data['id']));
    if($result['status'] == 1 | $result['status'] == 5){
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
      $this->db->update('invoice_pembelian',$inv,array('id'  => $data['id']));

      //batalkan biaya
      $this->db->delete('biaya_pembelian',array('order_id'=>$data['id']));

      //hapus data invoice_di po
      $this->db->update('pembelian_produk_kredit',array('invoice_id'=>0,'quantity_invoice'=> 0),array('invoice_id'=> $data['id']));
    }

  }





  public function updatePenjualan($data,$where=array()){
	$this->db->update('invoice_pembelian',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('invoice_pembelian',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order=array()){
		return $this->db->firstdetail('invoice_pembelian',$column,$join,$where,$order);
	}


  //public function getTotal

  public function getPenjualanProduct($where){
		return $this->db->first('invoice_pembelian_product',$where);
	}
  public function getPenjualanProductDetail($column,$join,$where){
		return $this->db->firstdetail('invoice_pembelian',$where);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('invoice_pembelian',$column,$join,$where,array());
  }

  public function getPermintaanPembelianProduct($where){
    return $this->db->all('invoice_pembelian_product',$where,array(),0,null);
  }
  public function getPermintaanPembelianProductDetail($where){
    return $this->db->firstdetail('invoice_pembelian_product',array(),array(),$where,array());
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('invoice_pembelian',$column,$join,$leftjoin,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('invoice_pembelian',$where);
	}
	public function getPenjualans($column=array(),$join=array(),$where=array(),$order,$limit,$offset){

		return $this->db->alljoin('invoice_pembelian',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where){
		return $this->db->count('invoice_pembelian',$where);
	}
  public function totalPenjualanDetail($where,$join){
    return $this->db->countAll('invoice_pembelian',$where,$join);
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
      'firsttable'  => 'biaya_pembelian.jenisbiaya_id',
      'secondtable' => 'jenis_biaya_pembelian.id'
    );
    return $this->db->alljoin('biaya_pembelian',array('biaya_pembelian.*','jenis_biaya_pembelian.name'),$join,$where,array(),0,null);
  }

  public function koreksibiaya($data){
    $pembelian=$this->getPermintaanPembelian(array(),array(),array('id'  => $data['id']));
    $totalreal=0;
    foreach($data['biaya'] as $b){
      if(!empty($b['id'])){
        $bia=array(
          //'tglfaktur' => empty($b['tglfaktur'])?'1970-01-01':$b['tglfaktur'],
          'total' => $b['totalreal'],
          'totalreal' => $b['totalreal'],
          'statuspembayaran'  => 0
          //'no_faktur' => $b['no_faktur']
        );
        $this->db->update('biaya_pembelian',$bia,array('id'=>$b['id']));
      }else{
        $bia=array(
          'jenisbiaya_id'  => $b['jenisbiaya_id'],
          'total' => $b['totalreal'],
          'totalreal' => $b['totalreal'],
          'order_id'  => $data['id'],
          'statuspembayaran'  => 0
          //'no_faktur' => empty($b['no_faktur'])?'':$b['no_faktur'],
          //'tglfaktur' => empty($b['tglfaktur'])?'1970-01-01':$b['tglfaktur']
        );
        $this->db->insert('biaya_pembelian',$bia);
      }

    }

  //  }
  }

  public function setujuiPerubahanHarga($data){
    $this->load->model('pembelian/pembeliankredit');
  }

  public function addPembayaran($data){
    $data['date_added']=isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s',time());

    $this->load->model('pembelian/pembayarandepositkredit');
    $this->load->model('pembelian/pembeliankredit');
    $this->load->model('keuangan/jurnal');

    $deposit=$this->model_pembelian_pembayarandepositkredit->getPermintaanPembelian(array('COALESCE(totalalokasi,0) as totalalokasi'),array(),array('id'=>$data['deposit_id']));
    $totalalokasi=$deposit['totalalokasi'];


    /*deposit_id,invoice_id,nominal,status,kurs,user_id,tglalokasi,date_added,keterangan,hapus*/
    foreach($data['orders'] as $i){
      $inv=array(
        'deposit_id' => $data['deposit_id'],
        'invoice_id'  => $i['invoice_id'],
        'vendor_id' => $data['vendor_id'],
        'status'  =>1,
        //'kurs'  => $data['kurs'],
        'nominal' => $i['total'],
        'user_id' => $this->user->getId(),
        'tglalokasi'  => $data['date_added'],
        'date_added'  => date('Y-m-d H:i:s'),
        'keterangan'  => 'Alokasi pembayaran invoice pembelian kredit',
        'hapus' => 0
      );
      $this->db->insert('alokasi_deposit_kredit',$inv);
      $totalalokasi +=$i['total'];


      $pb=$this->getPermintaanPembelian(array(),array(),array('id'=>$i['invoice_id']));
      $debetpajak=0;
      $kreditpajak=0;

      $pendapatan=0;
      $beban=0;

      //update total bayar
      $totalbayar=$pb['totalbayar']+$i['total'];

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
        $this->db->update('invoice_pembelian',array('status'=>4,'totalbayar'=>$totalbayar,'tgllunas'=>$data['date_added'],),array('id'=>$i['invoice_id']));

        if($pb['biayakirim'] > 0){
          $biayakirim = $pb['biayakirim'];
        }

        /*
        tampil list produk di invoice

        */
        /*list produk di invoice*/
        $productinvs=$this->getPermintaanPembelianProduct(array('invoice_id'=>$i['invoice_id']));
        $nilaipersediaan=0;
        $nilaibayar=0;
        foreach($productinvs as $productinv){
          /*detail produk di PO*/
          $barang=$this->model_pembelian_pembeliankredit->getPermintaanPembelianProductDetail(array('id' => $productinv['po_product_id']));
          $pembelian=$this->model_pembelian_pembeliankredit->getPermintaanPembelian(array(),array(),array('id'  => $barang['pembelian_id']));
          if(($barang['quantityterima'] == $barang['quantity']) | $pembelian['status'] == 5){
            if($barang['harga'] != $productinv['price']){
              if($barang['ppn'] != $productinv['pajak']){
                if($barang['ppn'] < $productinv['pajak']){
                  $debetpajak+=($productinv['pajak']-$barang['ppn'])*$productinv['quantity'];
                }

                if($barang['ppn'] > $productinv['pajak']){
                  $kreditpajak+=($barang['ppn']-$productinv['pajak'])*$productinv['quantity'];
                }
              }

              if($barang['harga'] < $productinv['price']){
                $beban+=($productinv['price'] - $barang['harga'])*$productinv['quantity'];
              }
              if($barang['harga'] > $productinv['price']){
                $pendapatan+=($barang['harga']-$productinv['price'])*$productinv['quantity'];
              }

            }
          }
          if($pembelian['status'] == 1 | $pembelian['status'] == 5){
            $diskon=0;
            if($pembelian['diskon'] > 0){
              $diskon=(($barang['harga'] + $barang['ppn'])/$pembelian['sub_total']+$pembelian['pajak'])*$pembelian['diskon'];
            }
            //hutang x uangmuka
            //$diskoninv=(($productinv['price'] + $productinv['pajak'])/$pb['sub_total']+$pb['pajak'])*$pb['diskon'];
            //$nilaibayar +=(($productinv['price']-$diskoninv)*$productinv['quantity'])+($productinv['pajak']*$productinv['quantity']);
            $nilaibayar=($productinv['price']*$productinv['quantity'])+($productinv['pajak']*$productinv['quantity']);
            $nilaipersediaan += (($barang['harga']-$diskon)*$productinv['quantity'])+($barang['ppn']*$productinv['quantity']);
          }

        }


        if($nilaipersediaan > 0){
          $details=array();
          $details[]=array(
            'ref_akun'  => '2101',
            'keterangan'  => 'Hutang Dagang',
            'debet' => $nilaipersediaan,
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );

          if($beban > 0){   //selisih harga PO dan harga invoice
            if($beban > $pendapatan){
              $beban=$beban-$pendapatan;
              $details[]=array(
                'ref_akun'  =>'8002',
                'debet' => $beban,
                'kredit'  => 0,
                'urutan'  =>2,
                'keterangan'  => 'Beban salah estimasi HPP'
              );
            }
          }

          if($debetpajak > 0){  //selisih pajak PO dan PO invoice
            if($debetpajak > $kreditpajak){
              $debetpajak=$debetpajak-$kreditpajak;
              $details[]=array(
                'ref_akun'  =>'1555',
                'debet' => $debetpajak,
                'kredit'  => 0,
                'urutan'  =>3,
                'keterangan'  => 'PPN Masukan'
              );
            }
          }

          if($biayakirim > 0){
            $details[]=array(
              'ref_akun'  => '6221',
              'keterangan'  => 'Biaya Kirim',
              'debet' => $biayakirim,
              'kredit'  => 0,
              'urutan'  => 4,
              'hapus' => 0
            );
          }
          if($pb['diskon'] > 0){
            $diskoninv=($nilaibayar/($pb['sub_total']+$pb['pajak']))*$pb['diskon'];
            $nilaibayar=$nilaibayar-$diskoninv;
          }
          $details[]=array(
            'ref_akun'  => '1311',
            //'jenis_akun'  => 52,
            'keterangan'  => 'Uang Muka Pembelian',
            'debet' => 0,
            'kredit'  => $nilaibayar+$biayakirim,
            'urutan'  => 5,
            'hapus' => 0
          );
          if($pendapatan > 0){  //selisih PO dan invoice
            if($pendapatan > $beban){
              $pendapatan=$pendapatan - $beban;
              $details[]=array(
                'ref_akun'  =>'7002',
                'kredit' => $pendapatan,
                'debet'  => 0,
                'urutan'  =>6,
                'keterangan'  => 'Pendapatan salah estimasi HPP'
              );
            }
          }
          if($kreditpajak > 0){ // selisih PO dan invoice
            if($kreditpajak > $debetpajak){
              $kreditpajak=$kreditpajak-$debetpajak;
              $details[]=array(
                'ref_akun'  =>'1555',
                'kredit' => $kreditpajak,
                'debet'  => 0,
                'urutan'  =>7,
                'keterangan'  => 'PPN Masukan'
              );
            }
          }

          $j=array(
            'tanggal' => isset($data['date_added'])?$data['date_added']:date('Y-m-d'),
            'keterangan'  => 'Alokasi Pembayaran Pembelian Lokal untuk invoice '.$pb['no_faktur'],
            'details' => $details,
            'hapus' =>0,
            'ref' => $i['invoice_id'],
            'type'  => 2022
          );
          $this->model_keuangan_jurnal->addJurnalUmum($j);



      }

      }else{
          $this->db->update('invoice_pembelian',array('status'=>2,'totalbayar'=>$totalbayar),array('id'=>$i['invoice_id']));
      }
      //$this->db->update('invoice',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'=>$i['invoice_id']));
    }
    $this->db->update('pembayaran_deposit_lokal',array('totalalokasi'=>$totalalokasi),array('id'=>$data['deposit_id']));

  }

  public function batalPembayaran($id){
    $alokasi=$this->db->first('alokasi_deposit_import',array('id'=>$id));

    //cek status barang datang
    $invoice=$this->getPermintaanPembelian(array(),array(),array('id'=>$alokasi['invoice_id']));

    $this->load->model('pembelian/pembayarandepositimport');


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
      }
    }
  }

  public function getPermintaanPembelianPembayaran($where){
    $join=array();

    return $this->db->alljoin('alokasi_deposit_kredit',array(),$join,$where,array(),0,null);
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

    //biaya
    $totalb=$this->db->query("SELECT COALESCE(SUM(totalreal),0) as totalbiaya FROM biaya_pembelianimport WHERE order_id='".$data['pembelian_import_id']."' ");
    $totalbiaya=$totalb->row['totalbiaya']+$pb['bmpib'];

    foreach($data['products'] as $p){

      $detail=$this->getPermintaanPembelianProductDetail(array('id' => $p['id']));
      $totalquantitybeli += $detail['quantity'];
      if($p['quantityterima'] + $detail['quantityterima'] > $detail['quantity']){
        $p['quantityterima'] =0;
      }
      if(empty($p['quantityterima'])){
        $p['quantityterima'] =0;
      }
      $pro=array(
        'id_suratjalan' => $sj_id,
        //'no_suratjalan' => $this->db->escape($data['no_suratjalan']),
        'pembelian_product_id'  => $p['id'],
        'quantity'  => $p['quantityterima']
      );
      $this->db->insert('suratjalan_produkimport',$pro);
      if($p['quantityterima'] > 0){
        $detail=$this->getPermintaanPembelianProductDetail(array('id' => $p['id']));
        $total +=($detail['price'] + $detail['pajak'] - $detail['diskon'])*$p['quantityterima'];
        $totalquantity += $p['quantityterima'];
        $totalpersediaan += (($detail['price'] - $detail['diskon'])*$p['quantityterima'])*$pb['kursdatang'];

        if($p['quantityterima'] + $detail['quantityterima'] == $detail['quantity']){
          $semua=true;
          $this->db->update('invoice_pembelian_import_product',array('quantityterima'  => $p['quantityterima']+$detail['quantityterima']),array('id' => $p['id']));
        }else{
          $semua=false;
          $this->db->update('invoice_pembelian_import_product',array('quantityterima'  => $p['quantityterima']+$detail['quantityterima']),array('id' => $p['id']));
        }

        $biaya=0;
        if($totalbiaya > 0){
          $biaya=((($detail['price'] + $detail['ppn'])*$pb['kursdatang'])/(($pb['sub_total']+$pb['pajak'])*$pb['kursdatang']))*$totalbiaya;
        }
        $diskon=0;
        if($pb['diskon'] > 0){
          $diskon=((($detail['price'] + $detail['ppn'])*$pb['kursdatang'])/(($detail['price'] + $detail['ppn'])*$pb['kursdatang']))*($pb['diskon']*$pb['kursdatang']);
        }
        $harga=(($detail['price'] + $detail['ppn'])*$pb['kursdatang'])-$diskon+$biaya;

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
      			'type'	=> 1
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
            'type'	=> 1
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
    if($totalquantitybeli == $totalquantity){
      $this->db->update('invoice_pembelian_import',array('statuspenerimaan'  => 1),array('id' => $data['pembelian_import_id']));
    }else{
      $this->db->update('invoice_pembelian_import',array('statuspenerimaan'  => 2),array('id' => $data['pembelian_import_id']));
    }

    //uang muka = $pb['totalbayarrp'](K)
    //persediaan (D)
    //labarugi K-D

    $this->load->model('keuangan/jurnal');


    $this->load->model('keuangan/pajak');

    $selisih=$pb['totalbayarrp'] - $totalpersediaan;
    //dibayar lebih besar berarti debet persediaan lebih besar berarti kredit

    $details=array();
    $details[]=array(
      'ref_akun'  => $data['jenispersediaan'],
      'keterangan'  => 'Barang datang import',
      'debet' => $totalpersediaan+$totalbiaya,
      'kredit'  => 0,
      'urutan'  => 1,
      'hapus' => 0
    );
    if($pb['pajak'] > 0){
      $details[]=array(
        'ref_akun'  =>'1555',
        'debet' => $pb['pajak']*$pb['kursdatang'],
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
      'kredit'  => $pb['totalbayarrp'],
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

    $j=array(
      'tanggal' => $data['tgl_terima'],
      'keterangan'  => 'Pembelian Import '.$pb['no_faktur'],
      'details' => $details,
      'hapus' =>0,
      'ref' => $data['pembelian_import_id'],
      'type'  => 6
    );
    $this->model_keuangan_jurnal->addJurnalUmum($j);



  }
  public function getBiaya($where){
		return $this->db->firstdetail('biaya_pembelian',array(),array(),$where,array(),0,null);
	}


}
?>
