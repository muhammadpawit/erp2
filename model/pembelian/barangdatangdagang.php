<?php
class ModelPembelianBarangdatangdagang extends Model {

  public function updatePermintaan($data,$where,$id){
    $sukses=true;
    $cekstatus=$this->getPermintaanPembelian(array(),array(),array('id'=>$id));
    if($cekstatus['status'] == 2){
      //cek invoice
      $products=$this->getPermintaanPembelianProduct(array('id_suratjalan'=>$id));
      foreach($products as $p){
        $inv=$this->cekinvoicebarangdatang($p['po_product_id']);
        if(!$inv){
          $sukses=false;
        }
      }
      $lbk='LBK-'.date('Y-m').'-'.$trans['no_suratjalan'];
      if($sukses){
        $this->load->model('gudang/product');
        $this->load->model('gudang/kartustok');
        $column=array('suratjalan_pembeliandagang.*','gudang.nama');
        $join=array();
        $join[]=array(
          'tablename'	=> 'gudang',
          'secondtable'	=>'gudang.gudang_id',
          'firsttable'	=> 'suratjalan_pembeliandagang.gudang_id'
        );


        $where = array(
          'suratjalan_pembeliandagang.id'	=> $id,

        );

        $trans=$this->getPermintaanPembelian($column,$join,$where);

        //if($trans['status'] == 1){


          $column=array('pembelian_kreditdagang.*','suratjalan_produkdagang.*','suratjalan_produkdagang.quantity as qtyterima','pembelian_produk_kreditdagang.*');
          $join=array();


          $join[]=array(
            'tablename'	=> 'pembelian_kreditdagang',
            'firsttable'	=>'suratjalan_produkdagang.po_id',
            'secondtable'	=> 'pembelian_kreditdagang.id'
          );
          $join[]=array(
            'tablename' => 'pembelian_produk_kreditdagang',
            'firsttable'  => 'suratjalan_produkdagang.pembelian_product_id',
            'secondtable' => 'pembelian_produk_kreditdagang.id'
          );


          $whereprods = array(
            'suratjalan_produkdagang.id_suratjalan'	=> $id,

          );

          $prods=$this->getPermintaanPembelianFull($column,$join,$whereprods);

          //biaya
          $biaya=$this->totalBiaya(array('order_id'	=> $id));

          $nilaipersediaan=0;
          $nilaibiaya=0;
          foreach($prods as $p){
            /*if($p['qtyterima'] + $p['quantityterima'] > $p['quantity']){
              $p['qtyterima'] =0;
            }
            if(empty($p['qtyterima'])){
              $p['qtyterima'] =0;
            }*/

            if($p['qtyterima'] > 0){
              $total +=$p['harga']*$p['qtyterima'];
              //$totalquantity += $p['qtyterima'];


             /* if($p['qtyterima'] + $p['quantityterima'] == $p['quantity']){
                $semua=true;
                $this->db->update('pembelian_produk_kreditdagang',array('quantityterima'  => $p['qtyterima']+$p['quantityterima'],'status'=>1),array('id' => $p['id']));
              }else{
                $semua=false;
                $this->db->update('pembelian_produk_kreditdagang',array('quantityterima'  => $p['qtyterima']+$p['quantityterima']),array('id' => $p['id']));
              }
              */
              //totalterima
              $q=$this->db->query("SELECT * FROM pembelian_produk_kreditdagang WHERE id='".$p['id']."'");
              $totalterima=$q->row['quantityterima'];
              $this->db->update('pembelian_produk_kreditdagang',array('quantityterima'  => $totalterima-$p['qtyterima']),array('id' => $p['id']));
              $diskon=0;
              if($p['diskon'] > 0){
                $diskon=($p['harga']/$p['sub_total'])*$p['diskon'];
              }

              $detailbiaya=0;
              if($biaya > 0){
                $detailbiaya=($p['harga']/$trans['total'])*$biaya;
              }

              $harga=$p['harga']-$diskon+$detailbiaya;

              $nilaipersediaan += ($harga)*$p['qtyterima'];
              $persediaanhilang=($harga)*$p['qtyterima'];
            //  $nilaipajak += $detail['ppn']*$p['quantityterima'];


              $curqty=$this->model_gudang_product->getProduct($p['product_id'],$p['gudang_id']);
              $update=$this->model_gudang_product->updateQty($p['product_id'],$p['gudang_id'],$p['qtyterima'],2);


              $persediaanawal=$curqty['quantity']*$curqty['net_cost'];
              if($persediaanawal > 0){
                $netcost=($persediaanawal-$persediaanhilang)/($curqty['quantity']-$p['qtyterima']);
              }else{
                $netcost=0;
              }
            //  $netcost=(($curqty['quantity']*$curqty['net_cost'])+($p['quantityterima'] * ($detail['harga']+$detail['ppn']+$biaya-$diskon)))/($curqty['quantity']+$p['quantityterima']);
             /* if($curqty['quantity'] > 0){

                  if($curqty['net_cost'] > 0){
                    $netcost=(($p['qtyterima'] * $harga)+($curqty['quantity']*$curqty['net_cost']))/($p['qtyterima']+$curqty['quantity']);

                  }else{
                    $netcost=(($p['qtyterima'] * $harga))/($p['qtyterima']);
                  }
                //}
              }else{
                $netcost=(($p['qtyterima'] * $harga))/($p['qtyterima']);
              }*/
              //hitung net cost
              /* Hitung total yang dicancel
              netcost * 
              */
              $this->model_gudang_product->updateNetCost($p['product_id'],$p['gudang_id'],$netcost);

              $kartustok=array(
                'product_id'	=> $p['product_id'],
                'product_name'	=> $p['product_name'],
                'tgl'	=> date('Y-m-d h:i:s',time()),
                'stokmasuk'	=> 0,
                'stokkeluar'	=> $p['qtyterima'],
                'ket'	=> 'Pembatalan Terima produk dagang',
                'saldo'	=> $update,
                'quantityawal'	=> $curqty['quantity'],
                'invoice'	=>$id,
                'gudang_id'	=> $p['gudang_id'],
                'type'	=> 1,
                'no_dokumen'  => $lbk,
			          'idref' => $id,
                'urlref'  => 'pembelian/terimabarangdagang'
              );

              $this->model_gudang_kartustok->addKartuStok($kartustok);

              //hitung total quantity telah diterima
              $totalterima=$this->db->query("SELECT SUM(quantity) as totalquantity, SUM(quantityterima) as totalquantityterima FROM pembelian_produk_kreditdagang WHERE pembelian_id='".$p['po_id']."'");

              if($totalterima->row['totalquantity'] == $totalterima->row['totalquantityterima']){
                $this->db->update('pembelian_kreditdagang',array('status'  => 1),array('id' => $p['po_id']));
              }else{
                $this->db->update('pembelian_kreditdagang',array('status'  => 2),array('id' =>$p['po_id']));
              }
          }

          }

          $this->load->model('keuangan/jurnal');

          $details=array();
          

          $details[]=array(
            'ref_akun'  => '2150',
            'keterangan'  => $this->db->escape('Hutang Usaha Belum Ditagih'),
            'debet' => $nilaipersediaan,
            'kredit'  => 0,
            'urutan'  => 1,
          );
          $details[]=array(
            'ref_akun'  => '1202',
            'keterangan'  => 'Barang Datang Pembelian Lokal',
            'kredit' => $nilaipersediaan,
            'debet'  => 0,
            'urutan'  => 3,
            'hapus' => 0
          );
          
          $j=array(
            'tanggal' => isset($data['tgl_terima'])?$data['tgl_terima']:date('Y-m-d'),
            'keterangan'  => 'Pembatalan Peneriman Pembelian Produk Dagang Dengan Surat Jalan '.$trans['no_suratjalan'],
            'details' => $details,
            'hapus' =>0,
            'ref' => $id,
            //'linkterkait'=> $id,
            'linkterkait'=> empty($trans['no_dokumen'])?$lbk:$trans['no_dokumen'],
            'type'  => 6,
            'no_dokumen'  => empty($trans['no_dokumen'])?$lbk:$trans['no_dokumen'],
            'idref' => $id,
            'urlref'  => 'pembelian/terimabarangdagang'
            
          );
          $this->model_keuangan_jurnal->addJurnalUmum($j);

          $update=array(
            'penerima'  => $this->db->escape($data['penerima']),
            'penerima_id' => empty($data['penerima_id'])?0:$data['penerima_id'],
            'pengangkut_id' => empty($data['pengangkut_id'])?0:$data['pengangkut_id'],
            'pengangkut'  => $this->db->escape($data['pengangkut']),
            'no_pol'  =>$this->db->escape($data['no_pol']),
            'tgl_surat' => empty($data['tgl_surat'])?date('Y-m-d H:i:s',time()):$data['tgl_surat'],
            'status'  => 1,
            'tgl_terima' => empty($data['tgl_terima'])?date('Y-m-d H:i:s',time()):$data['tgl_terima']
          );
          $this->db->update('suratjalan_pembeliandagang',$update,array('id'=>$id));

        //}
      }
    }else{
      $this->db->update('suratjalan_pembeliandagang',$data,$where);
      if($data['status'] == 3){
        $this->db->delete('biaya_pembelian',array('order_id'=>$id));
      }
    }

    
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('pembelian_kreditdagang',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('pembelian_kreditdagang',$where);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('suratjalan_pembeliandagang',$column,$join,$where,array());
  }
  public function getPermintaanPembelianFull($column=array(),$join=array(),$where=array(),$leftjoin=array()){
    return $this->db->alljoins('suratjalan_produkdagang',$column,$join,$leftjoin,$where,array(),0,null);
  }

  public function getPermintaanPembelianProduct($where){
    $join=array();
    $join[]=array(
      'tablename' => 'pembelian_produk_kredit',
      'firsttable'  => 'suratjalan_produk.pembelian_product_id',
      'secondtable' => 'pembelian_produk_kredit.id'
    );
    return $this->db->alljoin('suratjalan_produk',array('suratjalan_produk.quantity as qtyterima','pembelian_produk_kredit.*'),$join,$where,array(),0,null);
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

  public function totalBiaya($where){
    $column=array('COALESCE(SUM(total),0) as totalbiaya');
    $biaya=$this->db->firstdetail('biaya_pembelian',$column,array(),$where,array());

    return $biaya['totalbiaya'];
  }

  public function terimabarangdatang($data,$id){
    $this->load->model('gudang/product');
    $this->load->model('gudang/kartustok');
    $column=array('suratjalan_pembeliandagang.*','gudang.nama');
    $join=array();
    $join[]=array(
      'tablename'	=> 'gudang',
      'secondtable'	=>'gudang.gudang_id',
      'firsttable'	=> 'suratjalan_pembeliandagang.gudang_id'
    );


    $where = array(
      'suratjalan_pembeliandagang.id'	=> $id,

    );

    $trans=$this->getPermintaanPembelian($column,$join,$where);

    if($trans['status'] == 1){

      $lbk='LBK-'.date('Y-m').'-'.$trans['no_suratjalan'];
     
      $column=array('pembelian_kreditdagang.*','suratjalan_produkdagang.*','suratjalan_produkdagang.quantity as qtyterima','pembelian_produk_kreditdagang.*');
  		$join=array();


  		$join[]=array(
  			'tablename'	=> 'pembelian_kreditdagang',
  			'firsttable'	=>'suratjalan_produkdagang.po_id',
  			'secondtable'	=> 'pembelian_kreditdagang.id'
  		);
  		$join[]=array(
        'tablename' => 'pembelian_produk_kreditdagang',
        'firsttable'  => 'suratjalan_produkdagang.pembelian_product_id',
        'secondtable' => 'pembelian_produk_kreditdagang.id'
      );


  		$whereprods = array(
  			'suratjalan_produkdagang.id_suratjalan'	=> $id,

  		);

  		$prods=$this->getPermintaanPembelianFull($column,$join,$whereprods);

      //biaya
      $biaya=$this->totalBiaya(array('order_id'	=> $id));

      $nilaipersediaan=0;
      $nilaibiaya=0;
      foreach($prods as $p){
        if($p['qtyterima'] + $p['quantityterima'] > $p['quantity']){
          $p['qtyterima'] =0;
        }
        if(empty($p['qtyterima'])){
          $p['qtyterima'] =0;
        }

        if($p['qtyterima'] > 0){
          $total +=$p['harga']*$p['qtyterima'];
          $totalquantity += $p['qtyterima'];


          if($p['qtyterima'] + $p['quantityterima'] == $p['quantity']){
            $semua=true;
            $this->db->update('pembelian_produk_kreditdagang',array('quantityterima'  => $p['qtyterima']+$p['quantityterima'],'status'=>1),array('id' => $p['id']));
          }else{
            $semua=false;
            $this->db->update('pembelian_produk_kreditdagang',array('quantityterima'  => $p['qtyterima']+$p['quantityterima']),array('id' => $p['id']));
          }


          $diskon=0;
          if($p['diskon'] > 0){
            $diskon=($p['harga']/$p['sub_total'])*$p['diskon'];
          }

          $detailbiaya=0;
          if($biaya > 0){
            $detailbiaya=($p['harga']/$trans['total'])*$biaya;
          }

          $harga=$p['harga']-$diskon+$detailbiaya;

          $nilaipersediaan += ($harga)*$p['qtyterima'];
        //  $nilaipajak += $detail['ppn']*$p['quantityterima'];


          $curqty=$this->model_gudang_product->getProduct($p['product_id'],$p['gudang_id']);
          $update=$this->model_gudang_product->updateQty($p['product_id'],$p['gudang_id'],$p['qtyterima'],1);



        //  $netcost=(($curqty['quantity']*$curqty['net_cost'])+($p['quantityterima'] * ($detail['harga']+$detail['ppn']+$biaya-$diskon)))/($curqty['quantity']+$p['quantityterima']);
          if($curqty['quantity'] > 0){

              if($curqty['net_cost'] > 0){
                $netcost=(($p['qtyterima'] * $harga)+($curqty['quantity']*$curqty['net_cost']))/($p['qtyterima']+$curqty['quantity']);

              }else{
                $netcost=(($p['qtyterima'] * $harga))/($p['qtyterima']);
              }
            //}
          }else{
            $netcost=(($p['qtyterima'] * $harga))/($p['qtyterima']);
          }
          $this->model_gudang_product->updateNetCost($p['product_id'],$p['gudang_id'],$netcost);

          $kartustok=array(
            'product_id'	=> $p['product_id'],
            'product_name'	=> $p['product_name'],
            'tgl'	=> date('Y-m-d h:i:s',time()),
            'stokkeluar'	=> 0,
            'stokmasuk'	=> $p['qtyterima'],
            'ket'	=> 'Pembelian produk dagang',
            'saldo'	=> $update,
            'quantityawal'	=> $curqty['quantity'],
            'invoice'	=>$id,
            'gudang_id'	=> $p['gudang_id'],
            'type'	=> 1,
            'no_dokumen'  => $lbk,
            'idref' => $id,
            'urlref'  => 'pembelian/terimabarangdagang'
          );

          $this->model_gudang_kartustok->addKartuStok($kartustok);

          //hitung total quantity telah diterima
          $totalterima=$this->db->query("SELECT SUM(quantity) as totalquantity, SUM(quantityterima) as totalquantityterima FROM pembelian_produk_kreditdagang WHERE pembelian_id='".$p['po_id']."'");

          if($totalterima->row['totalquantity'] == $totalterima->row['totalquantityterima']){
            $this->db->update('pembelian_kreditdagang',array('status'  => 1),array('id' => $p['po_id']));
          }else{
            $this->db->update('pembelian_kreditdagang',array('status'  => 2),array('id' =>$p['po_id']));
          }
      }

      }

      $this->load->model('keuangan/jurnal');

      $details=array();
      $details[]=array(
        'ref_akun'  => '1202',
        'keterangan'  => 'Barang Datang Pembelian Lokal',
        'debet' => $nilaipersediaan,
        'kredit'  => 0,
        'urutan'  => 1,
        'hapus' => 0
      );

      $details[]=array(
        'ref_akun'  => '2150',
        'keterangan'  => $this->db->escape('Hutang Usaha Belum Ditagih'),
        'kredit' => $nilaipersediaan,
        'debet'  => 0,
        'urutan'  => 4,
      );
       $j=array(
        'tanggal' => isset($data['tgl_terima'])?$data['tgl_terima']:date('Y-m-d'),
        'keterangan'  => 'Peneriman Pembelian Produk Dagang Dengan Surat Jalan '.$trans['no_suratjalan'],
        'details' => $details,
        'hapus' =>0,
        'ref' => $id,
        //'linkterkait'=> $id,
        'linkterkait'=> empty($trans['no_dokumen'])?$lbk:$trans['no_dokumen'],
        'type'  => 6,
        'no_dokumen'  => empty($trans['no_dokumen'])?$lbk:$trans['no_dokumen'],
        'idref' => $id,
        'urlref'  => 'pembelian/terimabarangdagang'
        
      );
      $this->model_keuangan_jurnal->addJurnalUmum($j);

      $update=array(
        'penerima'  => $this->db->escape($data['penerima']),
        'penerima_id' => empty($data['penerima_id'])?0:$data['penerima_id'],
        'pengangkut_id' => empty($data['pengangkut_id'])?0:$data['pengangkut_id'],
        'pengangkut'  => $this->db->escape($data['pengangkut']),
        'no_pol'  =>$this->db->escape($data['no_pol']),
        'tgl_surat' => empty($data['tgl_surat'])?date('Y-m-d H:i:s',time()):$data['tgl_surat'],
        'status'  => 2,
        'tgl_terima' => empty($data['tgl_terima'])?date('Y-m-d H:i:s',time()):$data['tgl_terima']
      );
      $this->db->update('suratjalan_pembeliandagang',$update,array('id'=>$id));

    }
  }
  public function getBiaya($where){
		return $this->db->firstdetail('biaya_pembelian',array(),array(),$where,array(),0,null);
  }
  
  public function cekinvoicebarangdatang($id){
   
      //cek invoice
      $invoice=$this->db->query("SELECT * FROM invoice_pembelian_productdagang ip JOIN invoice_pembeliandagang i ON(ip.invoice_id=i.id) WHERE (i.status <> 5 AND i.status <> 3) AND ip.po_product_id='".$id."'");
      if(!empty($invoice->row)){
        return false;
      }else{
        return true;
      }
    
  }

  public function getPermintaanPembelianProductDetail($where){
    return $this->db->firstdetail('pembelian_produk_kreditdagang',array(),array(),$where,array());
  }

  public function getPermintaanPembelianPo($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('pembelian_kreditdagang',$column,$join,$where,array());
  }

  public function batal($id){
    $sql="SELECT * FROM suratjalan_produkdagang WHERE id='".$id."'";
    $query=$this->db->query($sql);
    if(!empty($query->row)){
      $detail=$this->getPermintaanPembelianProductDetail(array('id' => $query->row['pembelian_product_id']));
      $pb=$this->getPermintaanPembelianPo(array(),array(),array('id'  => $detail['pembelian_id']));
      
    }
    /*$this->load->model('gudang/product');
    $this->load->model('gudang/kartustok');
    $column=array('suratjalan_pembeliandagang.*','gudang.nama');
    $join=array();
    $join[]=array(
      'tablename'	=> 'gudang',
      'secondtable'	=>'gudang.gudang_id',
      'firsttable'	=> 'suratjalan_pembeliandagang.gudang_id'
    );


    $where = array(
      'suratjalan_pembeliandagang.id'	=> $id,

    );

    $trans=$this->getPermintaanPembelian($column,$join,$where);

    if($trans['status'] == 1){


      $column=array('pembelian_kreditdagang.*','suratjalan_produkdagang.*','suratjalan_produkdagang.quantity as qtyterima','pembelian_produk_kreditdagang.*');
  		$join=array();


  		$join[]=array(
  			'tablename'	=> 'pembelian_kreditdagang',
  			'firsttable'	=>'suratjalan_produkdagang.po_id',
  			'secondtable'	=> 'pembelian_kreditdagang.id'
  		);
  		$join[]=array(
        'tablename' => 'pembelian_produk_kreditdagang',
        'firsttable'  => 'suratjalan_produkdagang.pembelian_product_id',
        'secondtable' => 'pembelian_produk_kreditdagang.id'
      );


  		$whereprods = array(
  			'suratjalan_produkdagang.id_suratjalan'	=> $id,

  		);

  		$prods=$this->getPermintaanPembelianFull($column,$join,$whereprods);

      //biaya
      $biaya=$this->totalBiaya(array('order_id'	=> $id));

      $nilaipersediaan=0;
      $nilaibiaya=0;
      foreach($prods as $p){
        if($p['qtyterima'] + $p['quantityterima'] > $p['quantity']){
          $p['qtyterima'] =0;
        }
        if(empty($p['qtyterima'])){
          $p['qtyterima'] =0;
        }

        if($p['qtyterima'] > 0){
          $total +=$p['harga']*$p['qtyterima'];
          $totalquantity += $p['qtyterima'];


          if($p['qtyterima'] + $p['quantityterima'] == $p['quantity']){
            $semua=true;
            $this->db->update('pembelian_produk_kreditdagang',array('quantityterima'  => $p['qtyterima']+$p['quantityterima'],'status'=>1),array('id' => $p['id']));
          }else{
            $semua=false;
            $this->db->update('pembelian_produk_kreditdagang',array('quantityterima'  => $p['qtyterima']+$p['quantityterima']),array('id' => $p['id']));
          }


          $diskon=0;
          if($p['diskon'] > 0){
            $diskon=($p['harga']/$p['sub_total'])*$p['diskon'];
          }

          $detailbiaya=0;
          if($biaya > 0){
            $detailbiaya=($p['harga']/$trans['total'])*$biaya;
          }

          $harga=$p['harga']-$diskon+$detailbiaya;

          $nilaipersediaan += ($harga)*$p['qtyterima'];
        //  $nilaipajak += $detail['ppn']*$p['quantityterima'];


          $curqty=$this->model_gudang_product->getProduct($p['product_id'],$p['gudang_id']);
          $update=$this->model_gudang_product->updateQty($p['product_id'],$p['gudang_id'],$p['qtyterima'],1);



        //  $netcost=(($curqty['quantity']*$curqty['net_cost'])+($p['quantityterima'] * ($detail['harga']+$detail['ppn']+$biaya-$diskon)))/($curqty['quantity']+$p['quantityterima']);
          if($curqty['quantity'] > 0){

              if($curqty['net_cost'] > 0){
                $netcost=(($p['qtyterima'] * $harga)+($curqty['quantity']*$curqty['net_cost']))/($p['qtyterima']+$curqty['quantity']);

              }else{
                $netcost=(($p['qtyterima'] * $harga))/($p['qtyterima']);
              }
            //}
          }else{
            $netcost=(($p['qtyterima'] * $harga))/($p['qtyterima']);
          }
          $this->model_gudang_product->updateNetCost($p['product_id'],$p['gudang_id'],$netcost);

          $kartustok=array(
            'product_id'	=> $p['product_id'],
            'product_name'	=> $p['product_name'],
            'tgl'	=> date('Y-m-d h:i:s',time()),
            'stokkeluar'	=> 0,
            'stokmasuk'	=> $p['qtyterima'],
            'ket'	=> 'Pembelian produk dagang',
            'saldo'	=> $update,
            'quantityawal'	=> $curqty['quantity'],
            'invoice'	=>$id,
            'gudang_id'	=> $p['gudang_id'],
            'type'	=> 1
          );

          $this->model_gudang_kartustok->addKartuStok($kartustok);

          //hitung total quantity telah diterima
          $totalterima=$this->db->query("SELECT SUM(quantity) as totalquantity, SUM(quantityterima) as totalquantityterima FROM pembelian_produk_kreditdagang WHERE pembelian_id='".$p['po_id']."'");

          if($totalterima->row['totalquantity'] == $totalterima->row['totalquantityterima']){
            $this->db->update('pembelian_kreditdagang',array('status'  => 1),array('id' => $p['po_id']));
          }else{
            $this->db->update('pembelian_kreditdagang',array('status'  => 2),array('id' =>$p['po_id']));
          }
      }

      }

      $this->load->model('keuangan/jurnal');

      $details=array();
      $details[]=array(
        'ref_akun'  => '1202',
        'keterangan'  => 'Barang Datang Pembelian Lokal',
        'debet' => $nilaipersediaan,
        'kredit'  => 0,
        'urutan'  => 1,
        'hapus' => 0
      );

      $details[]=array(
        'ref_akun'  => '2150',
        'keterangan'  => $this->db->escape('Hutang Usaha Belum Ditagih'),
        'kredit' => $nilaipersediaan,
        'debet'  => 0,
        'urutan'  => 4,
      );
      $lbk='LBK-'.date('Y-m').'-'.$trans['no_suratjalan'];
      $j=array(
        'tanggal' => isset($data['tgl_terima'])?$data['tgl_terima']:date('Y-m-d'),
        'keterangan'  => 'Peneriman Pembelian Produk Dagang Dengan Surat Jalan '.$trans['no_suratjalan'],
        'details' => $details,
        'hapus' =>0,
        'ref' => $id,
        //'linkterkait'=> $id,
        'linkterkait'=> empty($trans['no_dokumen'])?$lbk:$trans['no_dokumen'],
        'type'  => 6,
        'no_dokumen'  => empty($trans['no_dokumen'])?$lbk:$trans['no_dokumen'],
        'idref' => $id,
        'urlref'  => 'pembelian/terimabarangdagang'
        
      );
      $this->model_keuangan_jurnal->addJurnalUmum($j);

      $update=array(
        'penerima'  => $this->db->escape($data['penerima']),
        'penerima_id' => empty($data['penerima_id'])?0:$data['penerima_id'],
        'pengangkut_id' => empty($data['pengangkut_id'])?0:$data['pengangkut_id'],
        'pengangkut'  => $this->db->escape($data['pengangkut']),
        'no_pol'  =>$this->db->escape($data['no_pol']),
        'tgl_surat' => empty($data['tgl_surat'])?date('Y-m-d H:i:s',time()):$data['tgl_surat'],
        'status'  => 2,
        'tgl_terima' => empty($data['tgl_terima'])?date('Y-m-d H:i:s',time()):$data['tgl_terima']
      );
      $this->db->update('suratjalan_pembeliandagang',$update,array('id'=>$id));

    }*/
  }

}
?>
