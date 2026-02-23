<?php
class ModelPembelianPembeliankredit extends Model {
  public function addPembelian($data){
    /*
    jenis barang
    1. bahan baku
    2. produk dagang
    3. ATK
    4. Perlengkapan/aktiva tetap
    5. Tabung MP

    penjurnalan
    pada saat PO tidak ada jurnal
    pada saat invoice juga tidak ada jurnal kecuali ada pembayaran uang muka
    pada saat barang datang persediaan X hutang dagang
    pada saat pembayaran hutang X bank atau hutang X uang Muka --> artinya pada saat barang datang apabila ada uang muka akan langsung mengurangi hutang dagang
    pembayaran uang muka uang muka x bank
    */
    $p=array(
      'vendor_id' => empty($data['vendor_id'])?0:$data['vendor_id'],
      'gudang_id' => $data['gudang_id'],
      'surat_id'  => $data['surat_id'],
      'sub_total' => $data['sub_total'],
      //'biaya' => $data['totalbiaya'],
      'pajak' => $data['pajak'],
      'diskon'  => $data['diskon']==null?0:$data['diskon'],
      'total_pembelian' => $data['total_pembelian'],
      'date_added'  => date('Y-m-d H:i:s',time()),
      'date_modified'  => date('Y-m-d H:i:s',time()),
      'hapus' => 0,
      'status'  => 0,
      'jenis_barang'  => $data['jenis_barang'],
      'jenis_aktiva'  => $this->db->escape($data['jenis_aktiva']),
      'metode_pembayaran' => $data['metode_pembayaran'],
      'jatuhtempo'  => empty($data['jatuhtempo'])?date('Y-m-d'):$data['jatuhtempo']
      //'no_faktur' => '',
      //'tglfaktur' => '1970-01-01',
      //'batasbayar'  => '1970-01-01'
    );

    $this->db->insert('pembelian_kredit',$p);
    $id=$this->db->getLastId();
    $data['id'] = $id;
    $data['status']=1;
    $no_surat='PO-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;

    $this->db->update('pembelian_kredit',array('no_po'  => $no_surat),array('id' => $id));
    $this->db->update('permintaan_pembelian',array('status'=>4),array('id'=>$data['surat_id']));
    $this->addProduct($data);
    //$this->addBiaya($data);
    return $no_po;


  }
  public function koreksibiaya($data){
    $pembelian=$this->getPermintaanPembelian(array(),array(),array('id'  => $data['id']));
    $totalreal=0;
    foreach($data['biaya'] as $b){
      if(!empty($b['id'])){
        $totalreal += $b['totalreal'];
        $bia=array(
          'tglfaktur' => empty($b['tglfaktur'])?'1970-01-01':$b['tglfaktur'],
          'total' => $b['totalreal'],
          'totalreal' => $b['totalreal'],
          'no_faktur' => $b['no_faktur']
        );
        $this->db->update('biaya_pembelian',$bia,array('id'=>$b['id']));
      }else{
        $bia=array(
          'name'  => $this->db->escape($b['nama']),
          'total' => $b['totalreal'],
          'totalreal' => $b['totalreal'],
          'order_id'  => $data['id'],
          'no_faktur' => empty($b['no_faktur'])?'':$b['no_faktur'],
          'tglfaktur' => empty($b['tglfaktur'])?'1970-01-01':$b['tglfaktur']
        );
        $this->db->insert('biaya_pembelian',$bia);
      }

    }
    //update total biayazxh6
    $total_pembelian=$pembelian['sub_total']+$pembelian['pajak']-$pembelian['diskon']+$pembelian['biaya'];
    $this->db->update('pembelian_kredit',array('biaya'=>$totalreal,'total_pembelian'=>$total_pembelian),array('id'=>$data['id']));
    //update hutang dagang
    //$this->db->update('pembelian_kredit',);
    $this->load->model('keuangan/jurnal');

    $details=array();
    $jurnal=array();
    //if($pembelian['status'] == 1){
      if($pembelian['biaya'] != $totalreal){
        if($totalreal > $pembelian['biaya']){
          $selisih=$totalreal - $pembelian['biaya'];
          if(strlen($pembelian['no_faktur']) > 0){
            $this->load->model('catalog/vendorlokal');

            $this->model_catalog_vendorlokal->updateDetailHutang($data['id'],$selisih,1);
          }
          if($pembelian['status'] == 1){
            $details[]=array(
              'ref_akun'  => '8002',
              'keterangan'  => 'Kesalahan perhitungan HPP',
              'debet' => $selisih,
              'kredit'  => 0,
              'urutan'  => 1,
              'hapus' => 0
            );
            $details[]=array(
              'ref_akun'  => '2101',
              'keterangan'  => 'Hutang Dagang',
              'kredit' => $selisih,
              'debet'  => 0,
              'urutan'  => 2,
              'hapus' => 0
            );
            $j=array(
              'tanggal' => date('Y-m-d'),
              'keterangan'  => 'Beban salah estimasi HPP pada PO '.$pembelian['no_po'],
              'details' => $details,
              'hapus' =>0,
              'ref' => $data['id'],
              'type'  => 100
            );
            $this->model_keuangan_jurnal->addJurnalUmum($j);
          }
        }
        if($totalreal < $pembelian['biaya']){
          $selisih= $pembelian['biaya'] - $totalreal;
          if(strlen($pembelian['no_faktur']) > 0){
            $this->load->model('catalog/vendorlokal');

            $this->model_catalog_vendorlokal->updateDetailHutang($data['id'],$selisih,2);
          }
          if($pembelian['status'] == 1){
            $details[]=array(
              'ref_akun'  => '2101',
              'keterangan'  => 'Hutang Dagang',
              'debet' => $selisih,
              'kredit'  => 0,
              'urutan'  => 1,
              'hapus' => 0
            );
            $details[]=array(
              'ref_akun'  => '7002',
              'keterangan'  => 'Pendapatan Salah Estimasi HPP',
              'kredit' => $totalreal,
              'debet'  => 0,
              'urutan'  => 2,
              'hapus' => 0
            );

            $j=array(
              'tanggal' => date('Y-m-d'),
              'keterangan'  => 'Pendapatan salah estimasi HPP pada PO '.$pembelian['no_po'],
              'details' => $details,
              'hapus' =>0,
              'ref' => $data['id'],
              'type'  => 101
            );
            $this->model_keuangan_jurnal->addJurnalUmum($j);
          }
        }
      }
  //  }
  }
  public function addBiaya($data){
    $total=0;
    foreach($data['biaya'] as $b){
      if(!empty($b['jenisbiaya_id'])){
        if($b['id'] == 0){
          $bia=array(
            'jenisbiaya_id'  => $b['jenisbiaya_id'],
            'total' => $b['total'],
            'totalreal' => $b['total'],
            'order_id'  => $data['id'],
            //'no_faktur' => empty($b['no_faktur'])?'':$b['no_faktur'],
            //'tglfaktur' => empty($b['tglfaktur'])?'1970-01-01':$b['tglfaktur']
          );
          $this->db->insert('biaya_pembelian',$bia);
        }else{
          $bia=array(
            'jenisbiaya_id'  => $b['jenisbiaya_id'],
            'total' => $b['total'],
            'totalreal' => $b['total'],
            'order_id'  => $data['id'],
            //'no_faktur' => empty($b['no_faktur'])?'':$b['no_faktur'],
            //'tglfaktur' => empty($b['tglfaktur'])?'1970-01-01':$b['tglfaktur']
          );
          $this->db->update('biaya_pembelian',$bia,array('id'=>$b['id']));
        }
        $total += $b['total'];
      }

    }
    $this->db->update('pembelian_kredit',array('biaya'=>$total),array('id'=>$data['id']));

  }

  public function addProduct($data){

    foreach($data['products'] as $d){
      if($data['statuspajak'] == 1){
        $pajak = $d['harga'] * 0.1;
      }else{
        $pajak=0;
      }
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
        'ppn' => $pajak,
        'hapus' => 0
      );
      $this->db->insert('pembelian_produk_kredit',$prod);

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
    $this->db->insert('suratjalan_pembelian',$sj);
    $sj_id=$this->db->getLastId();

    //$this->db->update('pembelian_kredit',array('status'  => 1),array('id' => $data['id']));
    $this->load->model('gudang/kartustok');
    $this->load->model('pembelian/invoicepembeliankredit');
    //update stok
    $pb=$this->getPermintaanPembelian(array(),array(),array('id'  => $data['pembelian_kredit_id']));
    $total=0;
    $nilaipersediaan=0;
    $nilaibiaya=0;
    $nilaipajak=0;
    $totalquantity=0;
    $totalquantitybeli=0;
    $uangmuka=0;
    $uangmukainvoice=0;

    foreach($data['products'] as $p){


      //$inv=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelian(array(),array(),array('id'=>$detail['invoice_id']));

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
      $this->db->insert('suratjalan_produk',$pro);

      if($p['quantityterima'] > 0){


      $total +=($detail['harga'] + $detail['ppn'])*$p['quantityterima'];
      $totalquantity += $p['quantityterima'];


      if($p['quantityterima'] + $detail['quantityterima'] == $detail['quantity']){
        $semua=true;
        $this->db->update('pembelian_produk_kredit',array('quantityterima'  => $p['quantityterima']+$detail['quantityterima'],'status'=>1),array('id' => $p['id']));
      }else{
        $semua=false;
        $this->db->update('pembelian_produk_kredit',array('quantityterima'  => $p['quantityterima']+$detail['quantityterima']),array('id' => $p['id']));
      }

      /*if(!empty($detail['invoice_id'])){
        $invs=$this->db->first('invoice_pembelian_product',array('invoice_id'=>$detail['invoice_id'],'po_id'=>$data['pembelian_kredit_id'],'po_product_id'=>$p['id']));

        if(!empty($invs)){
          $this->db->update('invoice_pembelian_product',array('quantityterima'  => $p['quantityterima']+$detail['quantityterima']),array('invoice_id'=>$detail['invoice_id'],'po_id'=>$data['pembelian_kredit_id'],'po_product_id'=>$p['id']));
        }

        //cek biaya
      //  $headerinv=$this->db->first('invoice_pembelian',array('invoice_id'=>$detail['invoice_id']));


    }*/
      /*$biaya=0;
      if($pb['biaya'] > 0){
        $biaya=(($detail['harga'] + $detail['ppn'])/$pb['sub_total']+$pb['pajak'])*$pb['biaya'];
      }*/
      $diskon=0;
      if($pb['diskon'] > 0){
        $diskon=(($detail['harga'] + $detail['ppn'])/($pb['sub_total']+$pb['pajak']))*$pb['diskon'];
      }

      $detail=$this->getPermintaanPembelianProductDetail(array('id' => $p['id']));

      $harga=$detail['harga'] + $detail['ppn']-$diskon;
      $biayaperproduk=0;

      $nilaipersediaan += ($detail['harga']-$diskon)*$p['quantityterima'];
      $nilaipajak += $detail['ppn']*$p['quantityterima'];

      //invoice_id
      $invs=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelianProduct(array('po_product_id'=>$p['id']));
      foreach($invs as $ivs){
        $inv=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelian(array(),array(),array('id'=>$ivs['invoice_id']));
        if(!empty($inv)){
          if($inv['status'] == 4){
            //jika quantity invoice sama dengan quantity terima atau kurang dari quantity terima
            //if($inv){
              $diskoninvoice=(($ivs['price'] + $ivs['pajak'])/($inv['sub_total']+$inv['pajak']))*$inv['diskon'];
              $uangmukainvoice += (($ivs['price']-$diskoninvoice)*$p['quantityterima']) + ($detail['ppn']*$p['quantityterima']);

              //biaya
              //jika biaya sudah dibayar maka masuk ke perhitungan hpp
              $biaya=$this->model_pembelian_biayapembeliankredit->getPermintaanPembelian(array('COALESCE(SUM(totalreal),0) as total'),array(),array('statuspembayaran'=>3,'order_id'=>$ivs['invoice_id']));
              $hargainvoice=($ivs['price'] + $ivs['pajak'])-$diskoninvoice;
              if($biaya['total'] > 0){
                $nilaibiaya+=(($biaya['total']/($biaya['total']+$inv['total']))*$hargainvoice)*$p['quantityterima'];
                $biayaperproduk+=(($biaya['total']/($biaya['total']+$inv['total']))*$hargainvoice);

              }

          }
          break;
        }

      }
      //




      if($pb['jenis_barang'] == 1){
        //bahan baku
        $this->load->model('catalog/bahanbaku');
        $curqty=$this->model_catalog_bahanbaku->getProduct($detail['product_id']);
        /*if($curqty['level'] != $p['levelawal']){
          if($curqty['level']){

          }
        }*/
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



        $this->db->update('bahanbaku',array('hargabeli' => ($harga+$biayaperproduk),'level'=>$p['levelakhir']),array('id'  => $detail['product_id']));
      }
      if($pb['jenis_barang'] == 2){
        //produk dagang gudang
        $this->load->model('gudang/product');
        $curqty=$this->model_gudang_product->getProduct($detail['product_id'],$data['gudang_id']);
        $update=$this->model_gudang_product->updateQty($detail['product_id'],$data['gudang_id'],$p['quantityterima'],1);



      //  $netcost=(($curqty['quantity']*$curqty['net_cost'])+($p['quantityterima'] * ($detail['harga']+$detail['ppn']+$biaya-$diskon)))/($curqty['quantity']+$p['quantityterima']);
      if($curqty['quantity'] > 0){
        //if(){
          /*if($curqty['net_cost'] > 0){
            $netcost=(($curqty['quantity']*$curqty['net_cost'])+($p['quantityterima'] * ($detail['harga']+$detail['ppn']+$biaya-$diskon)))/($curqty['quantity']+$p['quantityterima']);
          }else{
            $netcost=(($p['quantityterima'] * ($detail['harga']+$detail['ppn']+$biaya-$diskon)))/($p['quantityterima']);
          }*/
          if($curqty['net_cost'] > 0){
            $netcost=(($p['quantityterima'] * ($harga+$biayaperproduk))+($curqty['quantity']*$curqty['net_cost']))/($p['quantityterima']+$curqty['quantity']);

          }else{
            $netcost=(($p['quantityterima'] * ($harga+$biayaperproduk)))/($p['quantityterima']);
          }
        //}
      }else{
        $netcost=(($p['quantityterima'] * ($harga+$biayaperproduk)))/($p['quantityterima']);
      }
        $this->model_gudang_product->updateNetCost($detail['product_id'],$data['gudang_id'],$netcost);

        $kartustok=array(
          'product_id'	=> $detail['product_id'],
          'product_name'	=> $detail['product_name'],
          'tgl'	=> date('Y-m-d h:i:s',time()),
          'stokkeluar'	=> 0,
          'stokmasuk'	=> $p['quantityterima'],
          'ket'	=> 'Pembelian produk dagang',
          'saldo'	=> $update,
          'quantityawal'	=> $curqty['quantity'],
          'invoice'	=>$sj_id,
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

        if($curqty['qty'] > 0){
          //if(){
            $netcost=(($curqty['qty']*$curqty['net_cost'])+($p['quantityterima'] * ($harga+$biayaperproduk)))/($curqty['qty']+$p['quantityterima']);
          //}
        }else{
          $netcost=(($p['quantityterima'] * ($harga+$biayaperproduk)))/($p['quantityterima']);
        }
        $this->model_catalog_atk->updateNetCost($detail['product_id'],$netcost);

        $kartustok=array(
          'product_id'	=> $detail['product_id'],
          'product_name'	=> $detail['product_name'],
          'tgl'	=> date('Y-m-d h:i:s',time()),
          'stokkeluar'	=> 0,
          'stokmasuk'	=> $p['quantityterima'],
          'ket'	=> 'Pembelian ATK',
          'saldo'	=> $update,
          'quantityawal'	=> $curqty['qty'],
          'invoice'	=> $sj_id,
          //'gudang_id'	=> $data['gudang_id'],
          'type'	=> 1
        );

        $this->model_gudang_kartustok->addKartuStokGlobal('kartustok_atk',$kartustok);
      }
      if($pb['jenis_barang'] == 4){
        //aset
        $this->load->model('catalog/kelompokaset');
        $ak=$this->model_catalog_kelompokaset->getAktiva(array('no_akun'  => $pb['jenis_aktiva']));



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
          $this->db->update('aset',array('status' => 1, 'hargabeli' => $harga*$p['quantityterima'],'nilaibuku' => $harga*$p['quantityterima'],'tglpembelian'=>isset($data['tgl_terima'])?$data['tgl_terima']:date('Y-m-d'),'akumulasipenyusutan'=>0,'penyusutan'=>$penyusutantahunan,'penyusutanbulanan'=>$penyusutanbulanan),array('aset_id'  => $detail['product_id']));
        //}
      }
      if($pb['jenis_barang'] == 5){
        //tabung mp
        $this->load->model('catalog/tabungmp');
        $this->db->update('tabung_mp',array('status' => 1, 'hargabeli' => $harga*$p['quantityterima'],'nilaibuku' => $harga*$p['quantityterima'],'tglpembelian'=>isset($data['tgl_terima'])?$data['tgl_terima']:date('Y-m-d')),array('id'  => $detail['product_id']));
      }
      }
    }

    $this->db->update('suratjalan_pembelian',array('total'  => $total,'totalquantity' => $totalquantity),array('no_suratjalan'=>$data['no_suratjalan']));
    if($totalquantitybeli == $totalquantity){
      $this->db->update('pembelian_kredit',array('status'  => 1),array('id' => $data['pembelian_kredit_id']));

      //catat jurnal

    }else{
      $this->db->update('pembelian_kredit',array('status'  => 2),array('id' => $data['pembelian_kredit_id']));
    }

    $pb=$this->getPermintaanPembelian(array(),array(),array('id'  => $data['pembelian_kredit_id']));

    //$hutang=$pb['total_pembelian'];


    $this->load->model('keuangan/jurnal');


    $this->load->model('keuangan/pajak');

    $details=array();
    $details[]=array(
      'ref_akun'  => $data['jenispersediaan'],
      'keterangan'  => 'Barang Datang Pembelian Lokal',
      'debet' => $nilaipersediaan+$nilaibiaya,
      'kredit'  => 0,
      'urutan'  => 1,
      'hapus' => 0
    );
    if($nilaipajak > 0){
      $details[]=array(
        'ref_akun'  =>'1555',
        'debet' => $nilaipajak,
        'kredit'  => 0,
        'urutan'  =>2,
        'keterangan'  => 'PPN Masukan'
      );

      $pajak=array(
        'ref' => $data['pembelian_kredit_id'],
        'jumlah'  => $nilaipajak,
        'akun' => '1555',
        'jenis' => 1
      );
      $this->model_keuangan_pajak->addPajak($pajak);
    }

    $details[]=array(
      'ref_akun'  => '2101',
      'keterangan'  => $this->db->escape('Hutang Dagang'),
      'kredit' => $nilaipersediaan+$nilaipajak+$nilaibiaya,
      'debet'  => 0,
      'urutan'  => 4,
    );

    $j=array(
      'tanggal' => isset($data['tgl_terima'])?$data['tgl_terima']:date('Y-m-d'),
      'keterangan'  => 'Pembelian kredit '.$pb['no_po'].' Dengan Surat Jalan '.$data['no_suratjalan'],
      'details' => $details,
      'hapus' =>0,
      'ref' => $sj_id,
      'type'  => 6
    );
    $this->model_keuangan_jurnal->addJurnalUmum($j);

    if(($uangmukainvoice+$nilaibiaya) > 0){

      $detail[]=array(
        'ref_akun'  => '2101',
        'keterangan'  => $this->db->escape('Hutang Dagang'),
        'debet' => $nilaipersediaan+$nilaipajak+$nilaibiaya,
        'kredit'  => 0,
        'urutan'  => 1,
      );

      if(($uangmukainvoice+$nilaibiaya) > ($nilaipersediaan+$nilaipajak)){
          $beban=($uangmukainvoice+$nilaibiaya) - ($nilaipersediaan+$nilaipajak);
          $details[]=array(
            'ref_akun'  =>'8002',
            'debet' => $beban,
            'kredit'  => 0,
            'urutan'  =>3,
            'keterangan'  => 'Beban salah estimasi HPP'
          );
      }
      $details[]=array(
          'ref_akun'  => '1311',
          //'jenis_akun'  => 52,
          'keterangan'  => 'Uang Muka Pembelian',
          'debet' => 0,
          'kredit'  => ($uangmukainvoice+$nilaibiaya),
          'urutan'  => 4,
          'hapus' => 0
        );

      if(($uangmukainvoice+$nilaibiaya) < ($nilaipersediaan+$nilaipajak)){
        $pendapatan=($nilaipersediaan+$nilaipajak)-($uangmukainvoice+$nilaibiaya);
        $details[]=array(
          'ref_akun'  =>'7002',
          'kredit' => $pendapatan,
          'debet'  => 0,
          'urutan'  =>5,
          'keterangan'  => 'Pendapatan salah estimasi HPP'
        );
      }

      $j=array(
        'tanggal' => isset($data['date_added'])?$data['date_added']:date('Y-m-d'),
        'keterangan'  => 'Alokasi Uang Muka Pembelian kredit '.$pb['no_po'].' Dengan Surat Jalan '.$data['no_suratjalan'],
        'details' => $details,
        'hapus' =>0,
        'ref' => $sj_id,
        'type'  => 7000
      );
      $this->model_keuangan_jurnal->addJurnalUmum($j);
    }


  }
  public function updatePermintaan($data,$where){
    if($data['status'] == 3){
      $pembelian=$this->getPermintaanPembelian(array(),array(),$where);
      $this->db->update('permintaan_pembelian',array('status'=>2),array('id'=>$pembelian['surat_id']));
    }
    $this->db->update('pembelian_kredit',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('pembelian_kredit',$column,$join,$leftjoin,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where,$join=array(),$leftjoin=array()){
		return $this->db->countAll('pembelian_kredit',$where,$join,$leftjoin);
	}

  public function getBarangdatangs($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('suratjalan_pembelian',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalBarangdatangs($where,$join=array(),$leftjoin=array()){
		return $this->db->countAll('suratjalan_pembelian',$where,$join,$leftjoin);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('pembelian_kredit',$column,$join,$where,array());
  }

  public function getPermintaanPembelianProduct($where){
    return $this->db->all('pembelian_produk_kredit',$where,array(),0,null);
  }
  /*public function getPermintaanPembelianBiaya($where){
    return $this->db->alljoin('biaya_pembelian',array(),array(),$where,array(),0,null);
  }*/
  public function getPermintaanPembelianProductDetail($where){
    return $this->db->firstdetail('pembelian_produk_kredit',array(),array(),$where,array());
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

  public function getPoTanpaInvoice($vendor_id,$jenisbarang,$gudang_id){
    //tampilkan semua yang
    //$sql="SELECT pi.*,p.no_po FROM pembelian_produk_kredit pi JOIN pembelian_kredit p ON(pi.pembelian_id=p.id) WHERE vendor_id='".$vendor_id."' AND p.status <> 3 AND (pi.invoice_id IS NULL OR pi.invoice_id=0) AND p.gudang_id='".$gudang_id."' AND p.jenis_barang='".$jenisbarang."'";
    $this->load->model('pembelian/invoicepembeliankredit');
    $sql="SELECT pi.*,p.no_po,p.status as statuspenerimaan FROM pembelian_produk_kredit pi JOIN pembelian_kredit p ON(pi.pembelian_id=p.id) WHERE vendor_id='".$vendor_id."' AND p.status <> 3 AND (pi.invoice_id IS NULL OR pi.invoice_id=0) AND p.gudang_id='".$gudang_id."' AND p.jenis_barang='".$jenisbarang."'";
    $result=$this->db->query($sql);
    $hasil=array();
    foreach($result->rows as $r){
      //$ditagih=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelianProduct(array('p'=>$i['invoice_id']));
      $ditagih=$this->db->query("SELECT COALESCE(SUM(quantity),0) as total FROM invoice_pembelian_product ip JOIN invoice_pembelian i ON(ip.invoice_id=i.id) WHERE (i.status <> 5 AND i.status <> 3) AND ip.po_product_id='".$r['id']."'");
      $qty=$ditagih->row['total'];
      $r['quantity']=$r['quantity'] - $qty;
      $r['ditagih']=$qty;
      $r['statuspenerimaan']= $r['statuspenerimaan'];
      $hasil[]=$r;
    }
    return $hasil;
  }

  public function tutuppo($id){
    //status po harus 0 atau 2
    //kalau status 0 cek status invoice, jika invoice sudah dibuat maka quantity sesuai dengan quantity invoice, kalau belum ada invoice berarti dibatalkan
    //jika status 2 juga cek invoice jika invoice sudah dibuat maka quantity sesuai invoice, jika tidak maka sesuai barang yg sudah diterima
    $pembelian=$this->getPermintaanPembelian(array(),array(),array('id'	=> $id));
    /*if($pembelian['status'] == 0){
      //cek status invoice
      $sql="SELECT pi.*,p.no_po FROM pembelian_produk_kredit pi JOIN pembelian_kredit p ON(pi.pembelian_id=p.id) WHERE pembelian_id='".$id."'";
      $result=$this->db->query($sql);
      $totalinv=0;
      foreach($result->rows as $r){
        $ditagih=$this->db->query("SELECT COALESCE(SUM(quantity),0) as total FROM invoice_pembelian_product ip JOIN invoice_pembelian i ON(ip.invoice_id=i.id) WHERE (i.status <> 5 AND i.status <> 3) AND ip.po_product_id='".$r['id']."'");
        $totalinv+=$ditagih->row['total'];
        $total=$ditagih->row['total'];
        if($total == 0 ){
          break;
          return false;
        }
      }
      if($totalinv == 0){
        return false;
      }else{
        foreach($result->rows as $r){
          $ditagih=$this->db->query("SELECT COALESCE(SUM(quantity),0) as total FROM invoice_pembelian_product ip JOIN invoice_pembelian i ON(ip.invoice_id=i.id) WHERE (i.status <> 5 AND i.status <> 3) AND ip.po_product_id='".$r['id']."'");
          $qtyinv =$ditagih->row['total'];

          $this->db->update("pembelian_produk_kredit",array('quantity'=>$qtyinv),array('id'=>$r['id']));

        }

        //update
      }

      //if($totalinv){}

    }else*/
    if($pembelian['status'] == 2){
      $sql="SELECT pi.*,p.no_po FROM pembelian_produk_kredit pi JOIN pembelian_kredit p ON(pi.pembelian_id=p.id) WHERE pembelian_id='".$id."'";
      $result=$this->db->query($sql);
      $totalinv=0;
      foreach($result->rows as $r){
        //jika sudah ada invoice maka quantity sesuai dengan quantity invoice jika belumada maka quantity sesuai quantity yang diterima
        if($r['quantityterima'] == 0){
          break;
          return false;
        }else{
          $ditagih=$this->db->query("SELECT COALESCE(SUM(quantity),0) as total FROM invoice_pembelian_product ip JOIN invoice_pembelian i ON(ip.invoice_id=i.id) WHERE (i.status <> 5 AND i.status <> 3) AND ip.po_product_id='".$r['id']."'");

          $total=$ditagih->row['total'];
          if($total > 0){
            $this->db->update('pembelian_produk_kredit',array('quantity'=>$total),array('id'=>$r['id']));
          }else{
            $this->db->update('pembelian_produk_kredit',array('quantity'=>$r['quantityterima']),array('id'=>$r['id']));
          }
        }

      }

      //update sub_total,pajak,total
      $update=$this->db->query("SELECT COALESCE(SUM(quantity*harga),0) as subtotal,COALESCE(SUM(ppn*quantity),0) as pajak FROM pembelian_produk_kredit WHERE pembelian_id='".$id."'");
      $subtotal=$update->row['subtotal'];
      $pajak=$update->row['pajak'];

      $diskon=$pembelian['diskon'];
      $totalbaru=$subtotal+$pajak-$diskon;

      $this->db->update('pembelian_kredit',array('status'=>5,'sub_total'=>$subtotal,'pajak'=>$pajak,'total_pembelian'=>$totalbaru),array('id'=>$id));
      return true;
    }else{
      return false;
    }

  }

}
?>
