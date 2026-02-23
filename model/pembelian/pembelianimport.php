<?php
class ModelPembelianPembelianimport extends Model {
  // baru 17 Juli 2020
  public function getsjpembelian($id){
    if($id>0){
      $d=$this->db->query("SELECT sji.*, iv.no_dokumen FROM suratjalan_pembelianimport sji JOIN invoice_pembelian_import iv ON(sji.pembelian_import_id=iv.id)  WHERE pembelian_import_id='$id' ");
      return $d->row;
    }else{
      return 0;
    }
  }
  // end baru
  public function addPembelian($data){
    /*
    jenis barang
    1. bahan baku
    2. produk dagang
    3. ATK
    4. Perlengkapan/aktiva tetap
    5. Tabung MP
    */
    if($data['pembulatan'] > 0){
      $data['total_pembelian'] += $data['pembulatan'];
    }
    $p=array(
      'vendor_id' => $data['vendor_id'],
      'gudang_id' => $data['gudang_id'],
      'surat_id'  => $data['surat_id'],
      'sub_total' => $data['sub_total'],
      'pembulatan' => $data['pembulatan'],
      'pajak' => $data['pajak'],
      'diskon'  => empty($data['diskon'])?0:$data['diskon'],
      'total_pembelian' => $data['total_pembelian'],
      'date_added'  => date('Y-m-d H:i:s',time()),
      'date_modified'  => date('Y-m-d H:i:s',time()),
      'hapus' => 0,
      'status'  => 0,
      'jenis_barang'  => $data['jenis_barang'],
      'jenis_aktiva'  => $this->db->escape($data['jenis_aktiva']),
      'no_faktur' => '',
      'tglfaktur' => '1970-01-01',
      'batasbayar'  => '1970-01-01',
      'kursbank'  => empty($data['kursbank'])?0:$data['kursbank'],
      'kursbi'  => empty($data['kursbi'])?0:$data['kursbi'],
      'kurskmk'  => empty($data['kurskmk'])?0:$data['kurskmk'],
    );

    $this->db->insert('pembelian_import',$p);
    $id=$this->db->getLastId();
    $data['id'] = $id;
    $data['status']=1;
    $no_surat='POI-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;

    $this->db->update('pembelian_import',array('no_po'  => $no_surat),array('id' => $id));
    $this->db->update('permintaan_pembelian',array('status'=>4),array('id'=>$data['surat_id']));
    $this->addProduct($data);
    return $no_po;


  }

  public function addProduct($data){
    foreach($data['products'] as $d){
      $prod=array(
        'pembelian_id'  => $data['id'],
        'product_id'  => $d['product_id'],
        'product_name'  => $this->db->escape($d['name']),
        'quantity'  => $d['quantity'],
        'status'  => 1,
        'date_added'  => date('Y-m-d H:i:s',time()),
        'date_modified'  => date('Y-m-d H:i:s',time()),
        'quantityterima' => 0,
        'kategori'  => 0,
        'ukuran_tabung' => 0,
        'harga' => $d['harga'],
        'ppn' => $d['pajak'],
        'hapus' => 0,
        'kursbank'  => empty($data['kursbank'])?0:$data['kursbank'],
        'kursbi'  => empty($data['kursbi'])?0:$data['kursbi'],
        'kurskmk'  => empty($data['kurskmk'])?0:$data['kurskmk'],
      );
      $this->db->insert('pembelian_produk_import',$prod);

      if($data['jenis_barang'] == 2){
        //produk dagang gudang
        $this->load->model('gudang/product');
        $curqty=$this->model_gudang_product->getProduct($d['product_id'],$data['gudang_id']);
        if(empty($curqty)){
          $stokawal=array(
      			'gudang_id'	=> $data['gudang_id'],
      			'product_id'	=> $d['product_id'],
      			'qty'	=> 0,
      			'status'	=>1,
      			'net_cost'	=> 0,
      			'date_added'	=>date('Y-m-d H:i:s',time())
      		);
          $this->model_gudang_product->addStokAwal($stokawal);
        }

      }
    }
  }
  public function addInvoice($data){
    $p=array(
      'no_faktur' => $data['no_faktur'],
    //  'kurskmk' => empty($data['kurskmk'])?0:$data['kurskmk'],
      //'kursbi'  => empty($data['kursbi'])?0:$data['kursbi'],
      'tglfaktur' => !empty($data['tglfaktur'])?$data['tglfaktur']:date('Y-m-d'),
      'batasbayar'  => !empty($data['batasbayar'])?$data['batasbayar']:$data['tglfaktur']
    );

    $this->db->update('pembelian_import',$p,array('id'  => $data['id']));
    $pb=$this->getPermintaanPembelian(array(),array(),array('id'  => $data['id']));
    $this->addBiaya($data);
    //get DP
    $this->load->model('pembelian/pembayarandpimport');
    $dp=$this->model_pembelian_pembayarandpimport->totalDp($pb['no_po']);
    $totaldp=0;
    if(!empty($dp)){
      $totaldp=$dp['total'];
    }

    $hutang = $pb['total_pembelian'] - $totaldp;

    //hutang vendor
    $this->load->model('catalog/vendorimport');
    $v=$this->model_catalog_vendorimport->getVendor(array('id' => $pb['vendor_id']));

    //$hutangv=$v['hutang']+$hutang;
    $this->model_catalog_vendorimport->updateHutang($pb['vendor_id'],$hutang,1);
    $hutangvendor=array(
			'ref'=> $data['id'],
			'tanggal'	=> $data['tglfaktur'],
			'total_pembelian'	=> $pb['total_pembelian'],
			'total_hutang'	=> $hutang,
			'jatuhtempo'	=> $data['batasbayar'],
			'hapus'	=> 0,
      'vendor_id'=> $pb['vendor_id']
		);
    $this->model_catalog_vendorimport->addHutang($hutangvendor);

    //jurnal
    /*$this->load->model('keuangan/jurnal');
    if(!empty($data['kurskmk'])){
    if($pb['jenis_barang'] == 1){
      $ref['debet'] = '1201';
      $keterangan="Persediaan bahan baku";
    }
    if($pb['jenis_barang'] == 2){
        $ref['debet'] = '1202';
        $keterangan="Persediaan barang jadi";
    }
    if($pb['jenis_barang'] == 3){
      $ref['debet'] = '1203';
      $keterangan="Persediaan lain - lain";
    }
    if($pb['jenis_barang'] == 4 | $pb['jenis_barang'] == 5){
    //  $ref['debet'] = '12.01.00';
      $this->load->model('catalog/kelompokaset');
      $ak=$this->model_catalog_kelompokaset->getAktiva(array('no_akun'  => $pb['jenis_aktiva']));
      $ref['debet']=$ak['no_akun'];
      $keterangan=$ak['nama'];
    }
    if($pb['jenis_barang'] == 5){
    //  $ref['debet'] = '12.01.00';
      //$this->load->model('catalog/kelompokaset');
      //$ak=$this->model_catalog_kelompokaset->getAktiva(array('no_akun'  => $pb['jenis_aktiva']));
      $ref['debet']='12.01.06';
      $keterangan='Tabung Gas';
    }
    $this->load->model('keuangan/pajak');
    $details=array();
    $details[]=array(
      'ref_akun'  => $ref['debet'],
      'keterangan'  => $keterangan,
      'debet' => ($pb['total_pembelian'])*$data['kurskmk'],
      'kredit'  => 0,
      'urutan'  => 1,
      'hapus' => 0
    );

    if($pb['pembulatan'] > 0){
      $details[]=array(
        'ref_akun'  => '60.99.00',
        'keterangan'  => 'Pembulatan total pembelian',
        'debet' => ($pb['pembulatan'])*$data['kurskmk'],
        'kredit'  => 0,
        'urutan'  => 2,
        'hapus' => 0
      );
    }

    if($totaldp > 0){
      if($pb['jenis_aktiva'] == 0){
        $details[]=array(
          'ref_akun'  => "11.07.01",
          'keterangan'  => "Uang Muka Pembelian",
          'debet' => 0,
          'kredit'  => $totaldp,
          'urutan'  => 3,
          'hapus' => 0
        );
      }else{
        $details[]=array(
          'ref_akun'  => "12.04.01",
          'keterangan'  => "Uang muka pembelian aktiva tetap",
          'debet' => 0,
          'kredit'  => $totaldp,
          'urutan'  => 4,
          'hapus' => 0
        );
      }
    }
    $details[]=array(
      'ref_akun'  => '21.02.01',
      //'jenis_akun'  => 52,
      'keterangan'  => 'Hutang Dagang',
      'debet' => 0,
      'kredit'  => $hutang*$data['kurskmk'],
      'urutan'  => 5,
      'hapus' => 0
    );
    if($pb['pembulatan'] < 0){
      $details[]=array(
        'ref_akun'  => '70.01.00',
        'keterangan'  => 'Pembulatan total pembelian',
        'kredit' => (abs($pb['pembulatan']))*$data['kurskmk'],
        'debet'  => 0,
        'urutan'  => 6,
        'hapus' => 0
      );
    }

    $j=array(
      'tanggal' => date('Y-m-d'),
      'keterangan'  => 'Pembelian kredit '.$pb['no_po'],
      'details' => $details,
      'hapus' =>0,
      'ref' => $data['id'],
      'type'  => 6
    );
    $this->model_keuangan_jurnal->addJurnalUmum($j);

    //selisih
    $kurskmk=($pb['sub_total'] - $pb['diskon'])*$data['kurskmk'];
    $kursbi=($pb['sub_total'] - $pb['diskon'])*$data['kursbi'];

    $selisih=$kurskmk - $kursbi;
    $details=array();
    if($selisih > 0){
      $details[]=array(
        'ref_akun'  => $ref['debet'],
        'keterangan'  => $keterangan,
        'debet' => $selisih,
        'kredit'  => 0,
        'urutan'  => 1,
        'hapus' => 0
      );
      $details[]=array(
        'ref_akun'  => "30.02.02",
        'keterangan'  => "Laba selisih kurs",
        'debet' => 0,
        'kredit'  => $selisih,
        'urutan'  => 1,
        'hapus' => 0
      );
    }else{
      $details[]=array(
        'ref_akun'  => "30.02.02",
        'keterangan'  => "Rugi selisih kurs",
        'debet' => $selisih,
        'kredit'  => 0,
        'urutan'  => 1,
        'hapus' => 0
      );
      $details[]=array(
        'ref_akun'  => $ref['debet'],
        'keterangan'  => $keterangan,
        'debet' => 0,
        'kredit'  => $selisih,
        'urutan'  => 1,
        'hapus' => 0
      );
    }
    $j=array(
      'tanggal' => date('Y-m-d'),
      'keterangan'  => 'Laba/Rugi Selisih Kurs '.$pb['no_po'],
      'details' => $details,
      'hapus' =>0,
      'ref' => $data['id'],
      'type'  => 11
    );
    $this->model_keuangan_jurnal->addJurnalUmum($j);
  }*/

  }
  public function addBiaya($data){
    foreach($data['biaya'] as $b){

      $bia=array(
        'name'  => $this->db->escape($b['nama']),
        'total' => $b['total'],
        'totalreal' => $b['total'],
        'order_id'  => $data['id'],
        'currency'  => $b['currency']
        //'no_faktur' => empty($b['no_faktur'])?'':$b['no_faktur'],
        //'tglfaktur' => empty($b['tglfaktur'])?'1970-01-01':$b['tglfaktur']
      );
      $this->db->insert('biaya_pembelianimport',$bia);

    }

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
    );
    $this->db->insert('suratjalan_pembelianimport',$sj);
    $sj_id=$this->db->getLastId();
    //$this->db->update('pembelian_import',array('status'  => 1),array('id' => $data['id']));
    $this->load->model('gudang/kartustok');
    //update stok
    $pb=$this->getPermintaanPembelian(array(),array(),array('id'  => $data['pembelian_import_id']));
    $total=0;
    $totalquantity=0;
    $totalquantitybeli=0;

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
      $total +=($detail['harga'] + $detail['ppn'])*$p['quantityterima'];
      $totalquantity += $p['quantityterima'];


      if($p['quantityterima'] + $detail['quantityterima'] == $detail['quantity']){
        $semua=true;
        $this->db->update('pembelian_produk_import',array('quantityterima'  => $p['quantityterima']+$detail['quantityterima'],'status'=>1),array('id' => $p['id']));
      }else{
        $semua=false;
        $this->db->update('pembelian_produk_import',array('quantityterima'  => $p['quantityterima']+$detail['quantityterima']),array('id' => $p['id']));
      }

      if($pb['jenis_barang'] == 1){
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
        $this->db->update('bahanbaku',array('level'=>$p['levelakhir']),array('id'  => $detail['product_id']));
      }
      if($pb['jenis_barang'] == 2){
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
          'ket'	=> 'Pembelian produk dagang',
          'saldo'	=> $update,
          'quantityawal'	=> $curqty['quantity'],
          'invoice'	=> $data['id'],
          'gudang_id'	=> $data['gudang_id'],
          'type'	=> 1
        );

        $this->model_gudang_kartustok->addKartuStok($kartustok);

      }
      if($pb['jenis_barang'] == 3){
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
      }
      if($pb['jenis_barang'] == 4){
        //aset
        $this->load->model('catalog/kelompokaset');
        $ak=$this->model_catalog_kelompokaset->getAktiva(array('no_akun'  => $pb['jenis_aktiva']));
        if($ak['no_akun'] == '12.01.06'){
          $this->load->model('catalog/tabungmp');
        //  $this->db->update('tabungmp',array('status' => 1, 'hargabeli' => ($detail['harga'] + $detail['ppn'])*$data['kurskmk'],'tglpembelian'=>date('Y-m-d',time())),array('id'  => $detail['product_id']));
          $this->db->update('tabung_mp',array('status' => 1,'tglpembelian'=>date('Y-m-d',time())),array('id'  => $detail['product_id']));
        }
        else{
          $this->load->model('catalog/aset');
          //$this->db->update('aset',array('status' => 1, 'hargabeli' => ($detail['harga'] + $detail['ppn'])*$data['kurskmk'],'tglpembelian'=>date('Y-m-d',time())),array('aset_id'  => $detail['product_id']));
          $this->db->update('aset',array('status' => 1,'tglpembelian'=>date('Y-m-d',time())),array('aset_id'  => $detail['product_id']));
        }
      }
      if($pb['jenis_barang'] == 5){
        //tabung mp
        $this->load->model('catalog/tabungmp');
        //$this->db->update('tabung_mp',array('status' => 1, 'hargabeli' => ($detail['harga'] + $detail['ppn'])*$data['kurskmk'],'tglpembelian'=>date('Y-m-d',time())),array('id'  => $detail['product_id']));
        $this->db->update('tabung_mp',array('status' => 1,'tglpembelian'=>date('Y-m-d',time())),array('id'  => $detail['product_id']));
      }
      }
    }

    $this->db->update('suratjalan_pembelianimport',array('total'  => $total,'totalquantity' => $totalquantity),array('no_suratjalan'=>$data['no_suratjalan']));
    if($totalquantitybeli == $totalquantity){
      $this->db->update('pembelian_import',array('status'  => 1),array('id' => $data['pembelian_import_id']));
    }else{
      $this->db->update('pembelian_import',array('status'  => 2),array('id' => $data['pembelian_import_id']));
    }

  }
  public function updatePermintaan($data,$where){
    if($data['status'] == 3){
      $pembelian=$this->getPermintaanPembelian(array(),array(),$where);
      $this->db->update('permintaan_pembelian',array('status'=>2),array('id'=>$pembelian['surat_id']));
    }
    $this->db->update('pembelian_import',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('pembelian_import',$column,$join,$leftjoin,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where,$join=array(),$leftjoin=array()){
		return $this->db->countAll('pembelian_import',$where,$join,$leftjoin);
	}

  public function getBarangdatangs($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('suratjalan_pembelianimport',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalBarangdatangs($where,$join=array(),$leftjoin=array()){
		return $this->db->countAll('suratjalan_pembelianimport',$where,$join,$leftjoin);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('pembelian_import',$column,$join,$where,array());
  }

  public function getPermintaanPembelianProduct($where){
    return $this->db->all('pembelian_produk_import',$where,array(),0,null);
  }
  public function getPermintaanPembelianProductDetail($where){
    return $this->db->firstdetail('pembelian_produk_import',array(),array(),$where,array());
  }
  public function getPermintaanPembelianBiaya($where){
    return $this->db->alljoin('biaya_pembelianimport',array(),array(),$where,array(),0,null);
  }

  public function getPoTanpaInvoice($vendor_id,$jenisbarang,$gudang_id){
    $sql="SELECT pi.*,p.no_po FROM pembelian_produk_import pi JOIN pembelian_import p ON(pi.pembelian_id=p.id) WHERE vendor_id='".$vendor_id."' AND p.status <> 3 AND (pi.invoice_id IS NULL OR pi.invoice_id=0) AND p.gudang_id='".$gudang_id."' AND p.jenis_barang='".$jenisbarang."'";
    $result=$this->db->query($sql);

    return $result->rows;
  }

}
?>
