<?php
class ModelPembelianInvoicepembeliankreditnondagang extends Model {
  /*
  1. ditagih
  2. belum lunas
  3. lunas
  4. dibatalkan

  jurnal invoice

  biaya kirim
  hutang usaha

  persediaan
  biaya kirim
    hutang usaha

  kalau barang datang belum dibayar
  barang datang sudah dibayar

  persediaan
  biaya kirim
    uang muka pembelian

  */

  public function addPenjualan($data){
    $usia=1;

    if($data['totalpo'] != ($data['total']-$data['biayakirim'])){
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
      'statuspenerimaan' => 0,
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
      'biayakirim'  => empty($data['biayakirim'])?0:$data['biayakirim'],
      

    );
    $this->db->insert('invoice_pembeliannondagang',$j);
    $id=$this->db->getlastId();
    $data['id']=$id;
    $no_dokumen='IPA-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;
    $data['no_dokumen']=$no_dokumen;
    $this->db->update('invoice_pembeliannondagang',array('no_dokumen'=>$no_dokumen),array('id'=> $id));
    //add product
    $this->addPenjualanProduct($data);
    //$this->addBiaya($data);



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
          'totalterima' => 0,
          'hapus' => 0

        );

      $this->db->insert('invoice_pembeliannondagang_product',$penj);
    // $this->db->update('pembelian_produk_kredit',array('invoice_id'=> $data['id'],'quantity_invoice'=>$p['quantity']),array('id'=>$p['po_product_id']));
    }


    }
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
      $this->db->update('invoice_pembeliannondagang',$inv,array('id'  => $data['id']));


      //hapus data invoice_di po
      //$this->db->update('pembelian_produk_kreditnondagang',array('invoice_id'=>0,'quantity_invoice'=> 0),array('invoice_id'=> $data['id']));
    }

  }





  public function updatePenjualan($data,$where=array()){
	$this->db->update('invoice_pembeliannondagang',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('invoice_pembeliannondagang',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order=array()){
		return $this->db->firstdetail('invoice_pembeliannondagang',$column,$join,$where,$order);
	}


  //public function getTotal

  public function getPenjualanProduct($where){
		return $this->db->first('invoice_pembeliannondagang_product',$where);
	}
  public function getPenjualanProductDetail($column,$join,$where){
		return $this->db->firstdetail('invoice_pembeliannondagang',$where);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('invoice_pembeliannondagang',$column,$join,$where,array());
  }

  public function getPermintaanPembelianProduct($where){
    return $this->db->all('invoice_pembeliannondagang_product',$where,array(),0,null);
  }
  public function getPermintaanPembelianProductDetail($where){
    return $this->db->firstdetail('invoice_pembeliannondagang_product',array(),array(),$where,array());
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('invoice_pembeliannondagang',$column,$join,$leftjoin,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('invoice_pembeliannondagang',$where);
	}
	public function getPenjualans($column=array(),$join=array(),$where=array(),$order,$limit,$offset){

		return $this->db->alljoin('invoice_pembeliannondagang',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where){
		return $this->db->count('invoice_pembeliannondagang',$where);
	}
  public function totalPenjualanDetail($where,$join){
    return $this->db->countAll('invoice_pembeliannondagang',$where,$join);
  }





  public function setujuiPerubahanHarga($data){
    $this->load->model('pembelian/pembeliankredit');
  }

  public function addPembayaran($data){
    $data['date_added']=isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s',time());

    $this->load->model('pembelian/pembayarandepositkredit');
    $this->load->model('pembelian/pembeliankreditnondagang');
    $this->load->model('keuangan/jurnal');
    $this->load->model('catalog/vendorlokal');

    $deposit=$this->model_pembelian_pembayarandepositkredit->getPermintaanPembelian(array('COALESCE(totalalokasi,0) as totalalokasi'),array(),array('id'=>$data['deposit_id']));
    $totalalokasi=$deposit['totalalokasi'];


    /*deposit_id,invoice_id,nominal,status,kurs,user_id,tglalokasi,date_added,keterangan,hapus*/
    foreach($data['orders'] as $i){
      if($i['total'] > 0){
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
          'keterangan'  => 'Alokasi pembayaran invoice pembelian lokal non produk dagang',
          'hapus' => 0
        );
        $this->db->insert('alokasi_deposit_kreditnondagang',$inv);
        $idalokasi=$this->db->getLastId();

        $no_dokumen="AAS-".$idalokasi.'-'.date('Y').'-'.date('m').'-'.$this->user->getId();
        
        $this->db->update('alokasi_deposit_kreditnondagang',array('no_dokumen'=>$no_dokumen),array('id'=>$idalokasi));
        
        $hutang=array(
          'ref'=> $idalokasi,
          'date_trans'	=> $data['date_added'],
          'saldomasuk'	=> 0,
          'saldokeluar'	=> $i['total'],
          'keterangan'	=> 'Alokasi pembayaran invoice pembelian produk non dagang',
          'hapus'	=> 0,
          'vendor_id'=> $data['vendor_id'],
          'no_dokumen'	=> $no_dokumen,
          'idref'	=> $i['invoice_id'],
          'urlref'	=> 'pembelian/pembayaranpembeliannondagang'
      
        );
        $this->model_catalog_vendorlokal->addHistoryDeposit($hutang);
        $this->model_catalog_vendorlokal->updateDeposit($data['vendor_id'],$i['total'],2);


        $totalalokasi +=$i['total'];

        $this->db->update('alokasi_deposit_kreditnondagang',array('no_dokumen'=>$no_dokumen),array('id'=>$idalokasi));
        
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
          $this->db->update('invoice_pembeliannondagang',array('status'=>4,'totalbayar'=>$totalbayar,'tgllunas'=>$data['date_added'],),array('id'=>$i['invoice_id']));





        }else{
            $this->db->update('invoice_pembeliannondagang',array('status'=>2,'totalbayar'=>$totalbayar),array('id'=>$i['invoice_id']));
        }
        if($pb['statuspenerimaan'] == 1){
          $details=array();
          $details[]=array(
            'ref_akun'  => '2101',
            'keterangan'  => 'Hutang Usaha',
            'debet' => $i['total']-$pb['biayakirim'],
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );
          if($pb['biayakirim'] > 0){
            $details[]=array(
              'ref_akun'  => '6221',
              'keterangan'  => 'Biaya Kirim',
              'debet' => $pb['biayakirim'],
              'kredit'  => 0,
              'urutan'  => 2,
              'hapus' => 0
            );
          }


          $details[]=array(
            'ref_akun'  => '1311',
            //'jenis_akun'  => 52,
            'keterangan'  => 'Uang Muka Pembelian',
            'debet' => 0,
            'kredit'  => $i['total'],
            'urutan'  => 3,
            'hapus' => 0
          );

          $j=array(
            'tanggal' => isset($data['date_added'])?$data['date_added']:date('Y-m-d'),
            'keterangan'  => 'Alokasi Pembayaran Pembelian Non Produk Dagang untuk invoice '.$pb['no_faktur'],
            'details' => $details,
            'hapus' =>0,
            'ref' => $idalokasi,
            'type'  => 2027,
            'no_dokumen'	=> $no_dokumen,
            'idref'	=> $i['invoice_id'],
            'urlref'	=> 'pembelian/pembayaranpembeliannondagang'
          );
          $this->model_keuangan_jurnal->addJurnalUmum($j);



      }
      }
      //$this->db->update('invoice',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'=>$i['invoice_id']));
    }
    $this->db->update('pembayaran_deposit_lokal',array('totalalokasi'=>$totalalokasi),array('id'=>$data['deposit_id']));

  }

  public function batalPembayaran($id){
    $alokasi=$this->db->first('alokasi_deposit_kreditnondagang',array('id'=>$id));

    //cek status barang datang
    $invoice=$this->getPermintaanPembelian(array(),array(),array('id'=>$alokasi['invoice_id']));

    $this->load->model('pembelian/pembayarandepositkredit');


    if($alokasi['status'] == 1){
      $deposit=$this->model_pembelian_pembayarandepositkredit->getPermintaanPembelian(array('COALESCE(totalalokasi,0) as totalalokasi'),array(),array('id'=>$alokasi['deposit_id']));
      $totalalokasi =$deposit['totalalokasi']-$alokasi['nominal'];

      $this->db->update('pembayaran_deposit_lokal',array('totalalokasi'=>$totalalokasi),array('id'=>$alokasi['deposit_id']));

      $totalbayar=$invoice['totalbayar'] - $alokasi['nominal'];
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
        $this->db->update('invoice_pembeliannondagang',array('status'=>4,'totalbayar'=>$totalbayar,'tgllunas'=>$alokasi['date_added']),array('id'=>$alokasi['invoice_id']));
      }else{
          $this->db->update('invoice_pembeliannondagang',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'=>$alokasi['invoice_id']));
      }

      $this->db->update('alokasi_deposit_kreditnondagang',array('status'=>0),array('id'=>$id));

      $ju=$this->db->first('jurnal_umum',array('ref'=>$id,'type'=>2027));
      if(!empty($ju)){
        $this->db->delete('jurnal_umum',array('id'=>$ju['id']));
        $this->db->delete('jurnal_umum_detail',array('jurnal_id'=>$ju['id']));
      }

      $ju2=$this->db->first('jurnal_umum',array('ref'=>$alokasi['invoice_id'],'type'=>2028));
      if(!empty($ju2)){
        $this->db->delete('jurnal_umum',array('id'=>$ju2['id']));
        $this->db->delete('jurnal_umum_detail',array('jurnal_id'=>$ju2['id']));
      }

    }
  }

  public function getPermintaanPembelianPembayaran($where,$join=array(),$column=array()){
    $d=$this->db->query("SELECT * FROM alokasi_deposit_kreditnondagang WHERE hapus=0 and invoice_id='".$where['invoice_id']."' ");
    return $d->rows;
    //return $this->db->alljoin('alokasi_deposit_kreditnondagang',$column,$join,$where,array(),0,null);
  }

  public function barangdatang($data){
    $this->load->model('catalog/kelompokaset');
    $this->load->model('pembelian/biayapembeliankredit');

    $sj=array(
      'no_suratjalan' => $this->db->escape($data['no_suratjalan']),
      'pembelian_kredit_id'=> $data['pembelian_kredit_id'],
      'penerima'  => $this->db->escape($data['penerima']),
      'penerima_id' => empty($data['penerima_id'])?0:$data['penerima_id'],
      'pengangkut_id' => empty($data['pengangkut_id'])?0:$data['pengangkut_id'],
      'pengangkut'  => $this->db->escape($data['pengangkut']),
      'no_pol'  =>$this->db->escape($data['no_pol']),
      'gudang_id' => empty($data['gudang_id'])?0:$data['gudang_id'],
      'date_added' => date('Y-m-d H:i:s',time()),
      'total' => 0,
      'totalquantity' => 0,
      'hapus' => 0,
      'tgl_surat' => empty($data['tgl_surat'])?date('Y-m-d H:i:s',time()):$data['tgl_surat'],
      'tgl_terima' => empty($data['tgl_terima'])?date('Y-m-d H:i:s',time()):$data['tgl_terima']
    );
    $this->db->insert('suratjalan_pembeliannondagang',$sj);

    $sj_id=$this->db->getLastId();
    //$this->db->update('pembelian_import',array('status'  => 1),array('id' => $data['id']));
    $this->load->model('gudang/kartustok');
    $this->load->model('keuangan/jurnal');
    //update stok
    $pb=$this->getPermintaanPembelian(array(),array(),array('id'  => $data['pembelian_kredit_id']));

    $total=0;
    $totalpersediaan=0;
    $totalquantity=0;
    $totalquantitybeli=0;

    //biaya
    //$totalb=$this->db->query("SELECT COALESCE(SUM(totalreal),0) as totalbiaya FROM biaya_pembelianimport WHERE order_id='".$data['pembelian_import_id']."' ");
    //$totalbiaya=$totalb->row['totalbiaya']+$pb['bmpib'];

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
        'po_id' => $detail['invoice_id'],
        'quantity'  => $p['quantityterima']
      );
      $this->db->insert('suratjalan_produknondagang',$pro);
      if($p['quantityterima'] > 0){
        //$detail=$this->getPermintaanPembelianProductDetail(array('id' => $p['id']));
        $total +=($detail['price'] + $detail['pajak'] - $detail['diskon'])*$p['quantityterima'];
        $totalquantity += $p['quantityterima'];
        $totalpersediaan += ($detail['price']-$detail['diskon'])*$p['quantityterima'];

        if($p['quantityterima'] + $detail['quantityterima'] == $detail['quantity']){
          $semua=true;
          $this->db->update('invoice_pembeliannondagang_product',array('quantityterima'  => $p['quantityterima']+$detail['quantityterima']),array('id' => $p['id']));
          $this->db->update('pembelian_produk_kreditnondagang',array('quantityterima'  => $p['quantityterima']+$detail['quantityterima']),array('id' => $detail['po_product_id']));
        }else{
          $semua=false;
          $this->db->update('invoice_pembeliannondagang_product',array('quantityterima'  => $p['quantityterima']+$detail['quantityterima']),array('id' => $p['id']));
          $this->db->update('pembelian_produk_kreditnondagang',array('quantityterima'  => $p['quantityterima']+$detail['quantityterima']),array('id' => $detail['po_product_id']));
        }

        //update po
        $poterima=$this->db->query("SELECT COALESCE(SUM(quantityterima),0) as totalterima, COALESCE(SUM(quantity),0) as totalpo FROM pembelian_produk_kreditnondagang WHERE pembelian_id='".$detail['po_id']."'");
        if($poterima->row['totalterima'] == $poterima->row['totalpo'] ){
          $this->db->update('pembelian_kreditnondagang',array('status'=>1),array('id'=>$detail['po_id']));
        }else{
            $this->db->update('pembelian_kreditnondagang',array('status'=>2),array('id'=>$detail['po_id']));
        }

        $biaya=0;
        /*if($totalbiaya > 0){
          $biaya=((($detail['price'] + $detail['ppn'])*$pb['kursdatang'])/(($pb['sub_total']+$pb['pajak'])*$pb['kursdatang']))*$totalbiaya;
        }*/
        $diskon=0;
        if($pb['diskon'] > 0){
          $diskon=(($detail['price'] + $detail['pajak'])/($detail['price'] + $detail['pajak']))*$pb['diskon'];
        }
        $harga=($detail['price'] + $detail['pajak'])-$diskon;


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
          //$this->load->model('catalog/kelompokaset');
          //$ak=$this->model_catalog_kelompokaset->getAktiva(array('no_akun'  => $pb['jenis_aktiva']));



        /*  if($ak['no_akun'] == '12.01.06'){
            $this->load->model('catalog/tabungmp');

            $this->db->update('tabungmp',array('status' => 1, 'hargabeli' => ($detail['harga'] + $detail['ppn']+$biaya-$diskon),'tglpembelian'=>date('Y-m-d',time())),array('id'  => $detail['product_id']));
          }
          else{*/
            $this->load->model('catalog/aset');
            $aset=$this->model_catalog_aset->getAset(array('aset_id'=>$detail['product_id']));
        		$kel=$this->model_catalog_kelompokaset->getKelompokaset($aset['kelompok_aset']);

        		$manfaat=$kel['masa_manfaat'];
        		$tarif=$kel['nilai_depresiasi'];

        		$penyusutantahunan=($tarif/100)*(($harga*$p['quantityterima'])/$manfaat);
        		$penyusutanbulanan=$penyusutantahunan/12;
            $this->db->update('aset',array('status' => 1, 'hargabeli' => $harga*$p['quantityterima'],'nilaibuku' => $harga*$p['quantityterima'],'tglpembelian'=>isset($data['tgl_terima'])?$data['tgl_terima']:$pb['tglfaktur'],'akumulasipenyusutan'=>0,'penyusutan'=>$penyusutantahunan,'penyusutanbulanan'=>$penyusutanbulanan),array('aset_id'  => $detail['product_id']));
          //}
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
          $this->db->update('tabung_mp',array('status' => 1, 'hargabeli' => $harga*$p['quantityterima'],'nilaibuku' => $harga*$p['quantityterima'],'tglpembelian'=>isset($data['tgl_terima'])?$data['tgl_terima']:$pb['tglfaktur'],'akumulasipenyusutan'=>0,'penyusutan'=>$penyusutantahunan,'penyusutanbulanan'=>$penyusutanbulanan),array('id'  => $detail['product_id']));
      }
    }
    }

    $this->db->update('suratjalan_pembeliannondagang',array('total'  => $total,'totalquantity' => $totalquantity),array('id'=>$sj_id));
    if($totalquantity > 0){
      if($totalquantitybeli == $totalquantity){
        $this->db->update('invoice_pembeliannondagang',array('statuspenerimaan'  => 1),array('id' => $data['pembelian_kredit_id']));
      }else{
        $this->db->update('invoice_pembeliannondagang',array('statuspenerimaan'  => 2),array('id' => $data['pembelian_kredit_id']));
      }


      $this->load->model('keuangan/jurnal');


      $this->load->model('keuangan/pajak');


      $details=array();
      $details[]=array(
        'ref_akun'  => $data['jenispersediaan'],
        'keterangan'  => 'Barang datang non produk dagang',
        'debet' => $totalpersediaan,
        'kredit'  => 0,
        'urutan'  => 1,
        'hapus' => 0
      );
      if($pb['pajak'] > 0){
        $details[]=array(
          'ref_akun'  =>'1555',
          'debet' => $pb['pajak'],
          'kredit'  => 0,
          'urutan'  =>2,
          'keterangan'  => 'PPN Masukan'
        );

        $pajak=array(
          'ref' => $data['pembelian_kredit_id'],
          'jumlah'  => $pb['pajak'],
          'akun' => '1555',
          'jenis' => 1
        );
        $this->model_keuangan_pajak->addPajak($pajak);
      }

      $details[]=array(
        'ref_akun'  => '2101',
        'keterangan'  => 'Hutang Usaha',
        'kredit' => $totalpersediaan+$pb['pajak'],
        'debet'  => 0,
        'urutan'  => 4,
        'hapus' => 0
      );




      $j=array(
        'tanggal' => $data['tgl_terima'],
        'keterangan'  => 'Pembelian Lokal Non Produk Dagang '.$pb['no_faktur'],
        'details' => $details,
        'hapus' =>0,
        'ref' => $data['pembelian_kredit_id'],
        'type'  => 6,
        'no_dokumen'  => $pb['no_dokumen'],
        'idref' => $pb['id'],
        'urlref'  => 'pembelian/barangdatangnondagang'
      );
      $this->model_keuangan_jurnal->addJurnalUmum($j);

    if($pb['status'] == 4){
      $details=array();
      $details[]=array(
        'ref_akun'  => '2101',
        'keterangan'  => 'Hutang Usaha',
        'debet' => $pb['sub_total']-$pb['diskon']+$pb['pajak'],
        'kredit'  => 0,
        'urutan'  => 1,
        'hapus' => 0
      );

      if($pb['biayakirim'] > 0){
        $details[]=array(
          'ref_akun'  => '6221',
          'keterangan'  => 'Biaya Kirim',
          'debet' => $pb['biayakirim'],
          'kredit'  => 0,
          'urutan'  => 2,
          'hapus' => 0
        );
      }


      $details[]=array(
        'ref_akun'  => '1311',
        //'jenis_akun'  => 52,
        'keterangan'  => 'Uang Muka Pembelian',
        'debet' => 0,
        'kredit'  => $pb['sub_total']-$pb['diskon']+$pb['pajak']+$pb['biayakirim'],
        'urutan'  =>3,
        'hapus' => 0
      );

      $j=array(
        'tanggal' => isset($data['tgl_terima'])?$data['tgl_terima']:$pb['tglfaktur'],
        'keterangan'  => 'Alokasi Pembayaran Pembelian Non Produk Dagang untuk invoice '.$pb['no_faktur'],
        'details' => $details,
        'hapus' =>0,
        'ref' => $pb['id'],
        'type'  => 2028,
        'no_dokumen'  => $pb['no_dokumen'],
        'idref' => $pb['id'],
        'urlref'  => 'pembelian/barangdatangnondagang'
      );
      $this->model_keuangan_jurnal->addJurnalUmum($j);
      }
    }


  }
  public function getBiaya($where){
		return $this->db->firstdetail('biaya_pembelian',array(),array(),$where,array(),0,null);
	}


}
?>
