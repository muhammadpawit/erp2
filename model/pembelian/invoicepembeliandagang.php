<?php
class ModelPembelianInvoicepembeliandagang extends Model {
  // baru 22 02 2022
  public function getkethutangusaha($id){
    $sql="SELECT c.name FROM vendorlokal c JOIN invoice_pembeliandagang ipd ON(ipd.vendor_id=c.id) ";
    $sql.=" WHERE no_faktur='$id' ";
    $d=$this->db->query($sql);
    return $d->row['name'];
  }

  public function getkethutangusaha2($id,$type){
      $sql="SELECT keterangan FROM aruskas WHERE ref= '$id' and type='$type' AND ref_akun ='1311' AND hapus = '0' ";
      $d=$this->db->query($sql);
      return $d->row['keterangan'];
  }
  
  /*
  1. ditagih
  2. belum lunas
  3. lunas
  4. dibatalkan
  */

  // baru 10 Juli 2020
  public function sum($fill){
    $tglawal=$fill['tglawal'];
    $tglakhir=$fill['tglakhir'];
    $nofaktur=$fill['faktur'];
    $vendor=$fill['vendor'];
     // ivs lokal
     $sql="SELECT * FROM invoice_pembeliandagang";	
     $sql.=" WHERE status IN(1,2) ";
     if(!empty($vendor)){
       $sql.=" AND vendor_id='".$vendor."' ";
     }
     if(!empty($tglawal)){
       $sql.=" AND DATE(tglfaktur) >= '".$tglawal."' ";
     }
     if(!empty($tglawal)){
       $sql.=" AND DATE(tglfaktur) <= '".$tglakhir."' ";
     }
     $sql.=" ORDER BY tglfaktur DESC,id DESC, status ASC ";
     $d=$this->db->query($sql);
     $datainvlokal=$d->rows;
     // ivs import
     $sqlimport="SELECT * FROM tagihan_biaya_import ";
     $sqlimport.=" WHERE status IN(1,2) ";
     if(!empty($vendor)){
       $sqlimport.=" AND vendor_id='".$vendor."' ";
     }
     if(!empty($tglawal)){
       $sqlimport.=" AND DATE(tgl_tagihan) >= '".$tglawal."' ";
     }
     if(!empty($tglawal)){
       $sqlimport.=" AND DATE(tgl_tagihan) <= '".$tglakhir."' ";
     }
     $sqlimport.=" ORDER BY tgl_tagihan DESC,id DESC, status ASC  ";
     $di=$this->db->query($sqlimport);
     $datainvimport=$di->rows;
     $total=count($datainvlokal) + count($datainvimport);
     $nomor_urut=$start+1;
     $output['recordsTotal']=$output['recordsFiltered']=$total;
     $status=null;
     $ivslokal=array();
     $totaltagihanlokal=0;
     $totaltagihanimport=0;
     $totaltagihanall=0;
     foreach($datainvlokal as $c){
       $ivslokal[]=array(
         'tgl'=>date('Y-m-d',strtotime($c['tglfaktur'])),
         'vendor_id'=>$c['vendor_id'],
         'tglfaktur'=>date('d/m/Y',strtotime($c['tglfaktur'])),
         'jatuhtempo'=>date('d/m/Y',strtotime($c['jatuhtempo'])),
         'no_faktur'=>$c['no_faktur'],
         'no_dokumen'=>$c['no_dokumen'],
         'gudang'=>$this->getnama($c['gudang_id'],'nama','gudang','gudang_id'),
         'vendor'=>$this->getnama($c['vendor_id'],'name','vendorlokal','id'),
         'tagihan'=>$c['totaltagihan'],
         'totalbayar'=>$c['totalbayar'],
         'keterangan'=>'lokal',
       );
       $totaltagihanlokal+=($c['totaltagihan']);
     }
     $ivsimport=array();
     foreach($datainvimport as $c){
       $ivsimport[]=array(
         'tgl'=>date('Y-m-d',strtotime($c['tgl_tagihan'])),
         'vendor_id'=>$c['vendor_id'],
         'tglfaktur'=>date('d/m/Y',strtotime($c['tgl_tagihan'])),
         'jatuhtempo'=>date('d/m/Y',strtotime($c['jatuhtempo'])),
         'no_faktur'=>$c['no_faktur'],
         'no_dokumen'=>$c['no_dokumen'],
         'gudang'=>$this->getnama(1,'nama','gudang','gudang_id'),
         'vendor'=>$this->getnamaimport($c['vendor_id'],'name','vendorlokal','id'),
         'tagihan'=>$c['total'],
         'totalbayar'=>$c['totalbayar'],
         'keterangan'=>'tagihan biaya import',
       );
     }
     $data=array_merge($ivslokal,$ivsimport);
     return $data;
  }
  public function getnama($id,$kolom,$table,$kolomwhere){
    $d=$this->db->query("SELECT $kolom FROM $table WHERE $kolomwhere='$id' ");
    return $d->row[$kolom];
  }
  public function getnamaimport($id,$kolom,$table,$kolomwhere){
    $d=$this->db->query("SELECT $kolom FROM $table WHERE $kolomwhere='$id' ");
    return $d->row[$kolom];
  }
  public function test(){
    $draw=$_REQUEST['draw'];
		$length=$_REQUEST['length'];
		$start=$_REQUEST['start'];
		$search=$_REQUEST['search']["value"];
		$output['data']=array();
		$output=array();
		$output['draw']=$draw;
		$pro=null;
    $cats=null;
    $tglawal=$_REQUEST['filter_date_start'];
    $tglakhir=$_REQUEST['filter_date_end'];
    $nofaktur=$_REQUEST['filter_no_faktur'];
    $vendor=$_REQUEST['filter_vendor'];
    // ivs lokal
    $sql="SELECT * FROM invoice_pembeliandagang";	
    $sql.=" WHERE status IN(1,2) ";
    if(!empty($vendor)){
      $sql.=" AND vendor_id='".$vendor."' ";
    }
    if(!empty($tglawal)){
      $sql.=" AND DATE(tglfaktur) >= '".$tglawal."' ";
    }
    if(!empty($tglawal)){
      $sql.=" AND DATE(tglfaktur) <= '".$tglakhir."' ";
    }
		$sql.=" ORDER BY tglfaktur DESC,id DESC, status ASC ";
    $d=$this->db->query($sql);
    $datainvlokal=$d->rows;
    // ivs import
    $sqlimport="SELECT * FROM tagihan_biaya_import ";
    $sqlimport.=" WHERE status IN(1,2) ";
    if(!empty($vendor)){
      $sqlimport.=" AND vendor_id='".$vendor."' ";
    }
    if(!empty($tglawal)){
      $sqlimport.=" AND DATE(tgl_tagihan) >= '".$tglawal."' ";
    }
    if(!empty($tglawal)){
      $sqlimport.=" AND DATE(tgl_tagihan) <= '".$tglakhir."' ";
    }
    $sqlimport.=" ORDER BY tgl_tagihan DESC,id DESC, status ASC  ";
    $di=$this->db->query($sqlimport);
    $datainvimport=$di->rows;
    $total=count($datainvlokal) + count($datainvimport);
		$nomor_urut=$start+1;
		$output['recordsTotal']=$output['recordsFiltered']=$total;
    $status=null;
    $ivslokal=array();
    $totaltagihanlokal=0;
    $totaltagihanimport=0;
    $totaltagihanall=0;
    foreach($datainvlokal as $c){
      $linklokal=$this->url->link('pembelian/invoicepembeliandagang/tampil', 'token=' . $this->session->data['token'].'&id='.$c['id'], 'SSL');
      $ivslokal[]=array(
        'tgl'=>date('Y-m-d',strtotime($c['tglfaktur'])),
        'vendor_id'=>$c['vendor_id'],
        'tglfaktur'=>date('d/m/Y',strtotime($c['tglfaktur'])),
        'jatuhtempo'=>date('d/m/Y',strtotime($c['jatuhtempo'])),
        'no_faktur'=>$c['no_faktur'],
        'no_dokumen'=>$c['no_dokumen'],
        'gudang'=>$this->getnama($c['gudang_id'],'nama','gudang','gudang_id'),
        'vendor'=>$this->getnama($c['vendor_id'],'name','vendorlokal','id'),
        'tagihan'=>$this->currency->format($c['totaltagihan']),
        'totalbayar'=>$this->currency->format($c['totalbayar']),
        'keterangan'=>'invoice pembelian lokal',
        'action'=>'<a href="'.$linklokal.'" class="badge bg-orange" target="_blank">lihat</a> ',
      );
      $totaltagihanlokal+=($c['totaltagihan']);
    }
    $ivsimport=array();
    foreach($datainvimport as $c){
      $linkimport=$this->url->link('pembelian/tagihanbiayaimport', 'token=' . $this->session->data['token'].'&filter_jenis=1&vendor_id='.$c['vendor_id'], 'SSL');
      $ivsimport[]=array(
        'tgl'=>date('Y-m-d',strtotime($c['tgl_tagihan'])),
        'vendor_id'=>$c['vendor_id'],
        'tglfaktur'=>date('d/m/Y',strtotime($c['tgl_tagihan'])),
        'jatuhtempo'=>date('d/m/Y',strtotime($c['jatuhtempo'])),
        'no_faktur'=>$c['no_faktur'],
        'no_dokumen'=>$c['no_dokumen'],
        'gudang'=>$this->getnama(1,'nama','gudang','gudang_id'),
        'vendor'=>$this->getnamaimport($c['vendor_id'],'name','vendorlokal','id'),
        'tagihan'=>'Rp'.number_format($c['total'],2),
        'totalbayar'=>'Rp'.number_format($c['totalbayar'],2),
        'keterangan'=>'tagihan biaya import',
        'action'=>'<a href="'.$linkimport.'" class="badge bg-orange" target="_blank">lihat</a> ',
      );
      $totaltagihanimport+=($c['total']);
    }
    $data=array_merge($ivslokal,$ivsimport);
    if(!empty($data)){
      foreach ($data as $c) {
            $output['data'][]=array( 
              $nomor_urut,
              $c['tglfaktur'],
              $c['jatuhtempo'],
              $c['no_faktur'],
              $c['no_dokumen'],
              $c['gudang'],
              $c['vendor'],
              $c['tagihan'],
              $c['totalbayar'],
              $c['keterangan'],
              $c['action']
            );
            $nomor_urut++;
      }
    }else{
      $output['data'][]=array( 
        'tidak ditemukan',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        ''
      );
    }
		return $output;
  }

  // end baru

  public function addPenjualan($data){
    $usia=1;

    if($data['totalpo'] != ($data['sub_total']-$data['diskon'])){
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
      'totalpo' => $data['totalpo'],
      'totalbayar'  => 0,
      'cetak' => 0,
      'totaltagihan'  => empty($data['totaltagihan'])?$data['total']:$data['totaltagihan'],
      //'jenisproduk'  => $data['jenisproduk'],
      'metode_pembayaran'  => $data['metode_pembayaran'],
      'no_faktur' => $data['no_faktur'],
      'keterangan' => isset($data['keterangan'])?$data['keterangan']:'-',
    //  'biayakirim'  => empty($data['biayakirim'])?0:$data['biayakirim']

    );
    $this->db->insert('invoice_pembeliandagang',$j);
    $id=$this->db->getlastId();
    $data['id']=$id;

    $no_dokumen="IPD-".$id.'-'.date('Y').'-'.date('m').'-'.$this->user->getId();

    $this->db->update('invoice_pembeliandagang',array('no_dokumen'=>$no_dokumen),array('id'=>$id));

    //add product
    $this->addPenjualanProduct($data);

    /*jurnal*/
    $this->load->model('keuangan/jurnal');

    if($status == 1){
      $details=array();
      $details[]=array(
        'ref_akun'  => '2150',
        'keterangan'  => 'Hutang Belum Ditagih',
        'debet' => $data['sub_total']-$data['diskon'],
        'kredit'  => 0,
        'urutan'  => 1,
        'hapus' => 0
      );

      if($data['pajak'] > 0){
        $details[]=array(
          'ref_akun'  => '1555',
          'keterangan'  => 'PPn Masukan',
          'debet' => $data['pajak'],
          'kredit'  => 0,
          'urutan'  => 2,
          'hapus' => 0
        );
      }

      $details[]=array(
        'ref_akun'  => '2101',
        'keterangan'  => $this->db->escape('Hutang Usaha'),
        'kredit' => $data['sub_total']-$data['diskon']+$data['pajak'],
        'debet'  => 0,
        'urutan'  => 4,
      );

      $j=array(
        'tanggal' => isset($data['tglfaktur'])?$data['tglfaktur']:date('Y-m-d'),
        'keterangan'  => 'Invoice Pembelian Produk Dagang Dengan No Faktur '.$data['no_faktur'],
        'details' => $details,
        'hapus' =>0,
        'ref' => $id,
        'type'  => 15000,
        'urlref'=> 'pembelian/invoicepembeliandagang',
        'idref' => $id,
        'no_dokumen'  => $no_dokumen
      );
      $this->model_keuangan_jurnal->addJurnalUmum($j);
    }
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
        $curqty=$this->model_catalog_product->getProduct($p['product_id']);
        $penj=array(
          'invoice_id' => $data['id'],
          'product_id'  => $p['product_id'],
          'product_name'  => $p['name'],
          'po_id' => $p['po_id'],
          'po_product_id' => $p['po_product_id'],
          //'sj_id' => $p['sj_id'],
          'quantity' => $p['quantity'],
          'quantityterima' => $p['quantityterima'],
          'price' => $p['price'],
          'hargapo' => $p['hargapo'],
          'diskon' => 0,
          'pajak' => $p['pajak'],
          'total' => $p['total'],
          'totalpajak' => $p['pajak']*$p['quantity'],
          //'jenisproduk' => $data['jenisproduk'],
          'hapus' => 0

        );

      $this->db->insert('invoice_pembelian_productdagang',$penj);
    // $this->db->update('pembelian_produk_kredit',array('invoice_id'=> $data['id'],'quantity_invoice'=>$p['quantity']),array('id'=>$p['po_product_id']));
    }


    }
    }

  }



  public function batalInvoice($data){
    $result=$this->getPermintaanPembelian(array(),array(),array('id'  => $data['id']));
    if($result['status'] == 1 | $result['status'] == 5){

      $inv=array(
        'status'  => 3
      );
      $this->db->update('invoice_pembeliandagang',$inv,array('id'  => $data['id']));
      //hapus data invoice_di po
      if($result['status'] == 1){
        $ju=$this->db->first('jurnal_umum',array('ref'=>$data['id'],'type'=>15000));
        $this->db->delete('jurnal_umum',array('id'=>$ju['id']));
        $this->db->delete('jurnal_umum_detail',array('jurnal_id'=>$ju['id']));
      }
      //$this->db->update('pembelian_produk_kredit',array('invoice_id'=>0,'quantity_invoice'=> 0),array('invoice_id'=> $data['id']));
    }

  }

  public function updatePenjualan($data,$where=array()){
	   $this->db->update('invoice_pembeliandagang',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('invoice_pembeliandagang',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order=array()){
		return $this->db->firstdetail('invoice_pembeliandagang',$column,$join,$where,$order);
	}


  //public function getTotal

  public function getPenjualanProduct($where){
		return $this->db->first('invoice_pembelian_productdagang',$where);
	}
  public function getPenjualanProductDetail($column,$join,$where){
		return $this->db->firstdetail('invoice_pembeliandagang',$where);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('invoice_pembeliandagang',$column,$join,$where,array());
  }

  public function getPermintaanPembelianProduct($where){
    return $this->db->all('invoice_pembelian_productdagang',$where,array(),0,null);
  }
  public function getPermintaanPembelianProductDetail($where){
    return $this->db->firstdetail('invoice_pembelian_productdagang',array(),array(),$where,array());
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('invoice_pembeliandagang',$column,$join,$leftjoin,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('invoice_pembeliandagang',$where);
	}
	public function getPenjualans($column=array(),$join=array(),$where=array(),$order,$limit,$offset){

		return $this->db->alljoin('invoice_pembeliandagang',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where){
		return $this->db->count('invoice_pembeliandagang',$where);
	}
  public function totalPenjualanDetail($where,$join){
    return $this->db->countAll('invoice_pembeliandagang',$where,$join);
  }




  public function setujuiPerubahanHarga($id){
    //$this->load->model('pembelian/pembelian');
    $result=$this->getPermintaanPembelian(array(),array(),array('id'  => $id));
    if($result['status'] == 5){
      $this->db->update('invoice_pembeliandagang',array('status' => 1),array('id'  => $id));


      $this->load->model('keuangan/jurnal');

      //jika total lebih sedikit dari total po berarti 7002
      //kalau lebih banyak berarti 80023
        $details=array();
        $details[]=array(
          'ref_akun'  => '2150',
          'keterangan'  => 'Hutang Belum Ditagih',
          'debet' => $result['totalpo'],
          'kredit'  => 0,
          'urutan'  => 1,
          'hapus' => 0
        );

        if($result['pajak'] > 0){
          $details[]=array(
            'ref_akun'  => '1555',
            'keterangan'  => 'PPn Masukan',
            'debet' => $result['pajak'],
            'kredit'  => 0,
            'urutan'  => 2,
            'hapus' => 0
          );
        }
        if(($result['sub_total']-$result['diskon']) > $result['totalpo']){
          $details[]=array(
            'ref_akun'  => '8002',
            'keterangan'  => 'Beban salah perhitungan HPP',
            'debet' => $result['sub_total'] - $result['diskon'] - $result['totalpo'],
            'kredit'  => 0,
            'urutan'  => 3,
            'hapus' => 0
          );
        }

        $details[]=array(
          'ref_akun'  => '2101',
          'keterangan'  => $this->db->escape('Hutang Usaha'),
          'kredit' => $result['sub_total']-$result['diskon']+$result['pajak'],
          'debet'  => 0,
          'urutan'  => 4,
        );
        if(($result['sub_total']-$result['diskon']) < $result['totalpo']){
          $details[]=array(
            'ref_akun'  => '7002',
            'keterangan'  => 'Pendapatan salah perhitungan HPP',
            'kredit' => $result['totalpo'] - $result['sub_total'] - $result['diskon'],
            'debet'  => 0,
            'urutan'  => 5,
            'hapus' => 0
          );
        }

        $j=array(
          'tanggal' => isset($result['tglfaktur'])?$result['tglfaktur']:date('Y-m-d'),
          'keterangan'  => 'Invoice Pembelian Produk Dagang Dengan No Faktur '.$result['no_faktur'],
          'details' => $details,
          'hapus' =>0,
          'ref' => $id,
          'type'  => 15000,
        'urlref'=> 'pembelian/invoicepembeliandagang',
        'idref' => $id,
        'no_dokumen'  => $result['no_dokumen']
        );
        $this->model_keuangan_jurnal->addJurnalUmum($j);
    }

  }

  public function addPembayaran($data){
    $data['date_added']=isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s',time());

    $this->load->model('pembelian/pembayarandepositkredit');
    $this->load->model('pembelian/pembeliankreditdagang');
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
          'keterangan'  => 'Alokasi pembayaran invoice pembelian kredit',
          'hapus' => 0
        );
        $this->db->insert('alokasi_deposit_kredit',$inv);
        $idalokasi=$this->db->getLastId();

        $no_dokumen="ADG-".$idalokasi.'-'.date('Y').'-'.date('m').'-'.$this->user->getId();

        $this->db->update('alokasi_deposit_kredit',array('no_dokumen'=>$no_dokumen),array('id'=>$idalokasi));
        
        $hutang=array(
          'ref'=> $idalokasi,
          'date_trans'	=> $data['date_added'],
          'saldomasuk'	=> 0,
          'saldokeluar'	=> $i['total'],
          'keterangan'	=> 'Alokasi pembayaran invoice pembelian produk dagang',
          'hapus'	=> 0,
          'vendor_id'=> $data['vendor_id'],
          'no_dokumen'	=> $no_dokumen,
          'idref'	=> $i['invoice_id'],
          'urlref'	=> 'pembelian/pembayaranpembeliandagang'
      
        );
        $this->model_catalog_vendorlokal->addHistoryDeposit($hutang);
        $this->model_catalog_vendorlokal->updateDeposit($data['vendor_id'],$i['total'],2);

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
          $this->db->update('invoice_pembeliandagang',array('status'=>4,'totalbayar'=>$totalbayar,'tgllunas'=>$data['date_added'],),array('id'=>$i['invoice_id']));





        }else{
            $this->db->update('invoice_pembeliandagang',array('status'=>2,'totalbayar'=>$totalbayar),array('id'=>$i['invoice_id']));
        }
        if($i['total'] > 0){
          $details=array();
          $details[]=array(
            'ref_akun'  => '2101',
            'keterangan'  => 'Hutang Usaha',
            'debet' => $i['total'],
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );


          $details[]=array(
            'ref_akun'  => '1311',
            //'jenis_akun'  => 52,
            'keterangan'  => 'Uang Muka Pembelian',
            'debet' => 0,
            'kredit'  => $i['total'],
            'urutan'  => 2,
            'hapus' => 0
          );

          $j=array(
            'tanggal' => isset($data['date_added'])?$data['date_added']:date('Y-m-d'),
            'keterangan'  => 'Alokasi Pembayaran Pembelian Produk Dagang untuk invoice '.$pb['no_faktur'],
            'details' => $details,
            'hapus' =>0,
            'ref' => $idalokasi,
            'type'  => 2025,
            'no_dokumen'	=> $no_dokumen,
            'idref'	=> $i['invoice_id'],
            'urlref'	=> 'pembelian/pembayaranpembeliandagang'
          );
          $this->model_keuangan_jurnal->addJurnalUmum($j);



      }
      }
      //$this->db->update('invoice',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'=>$i['invoice_id']));
    }
    $this->db->update('pembayaran_deposit_lokal',array('totalalokasi'=>$totalalokasi),array('id'=>$data['deposit_id']));

  }

  public function batalPembayaran($id){
    $alokasi=$this->db->first('alokasi_deposit_kredit',array('id'=>$id));

    //cek status barang datang
    $invoice=$this->getPermintaanPembelian(array(),array(),array('id'=>$alokasi['invoice_id']));

    $this->load->model('pembelian/pembayarandepositkredit');
    $this->load->model('catalog/vendorlokal');


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
        $this->db->update('invoice_pembeliandagang',array('status'=>4,'totalbayar'=>$totalbayar,'tgllunas'=>$alokasi['tglalokasi']),array('id'=>$alokasi['invoice_id']));
      }else{
          $this->db->update('invoice_pembeliandagang',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'=>$alokasi['invoice_id']));
      }

      $this->db->update('alokasi_deposit_kredit',array('status'=>0),array('id'=>$id));

      $hutang=array(
        'ref'=> $id,
        'date_trans'	=> $alokasi['tglalokasi'],
        'saldomasuk'	=> $alokasi['nominal'],
        'saldokeluar'	=> 0,
        'keterangan'	=> 'Pembatalan Alokasi pembayaran invoice pembelian produk dagang',
        'hapus'	=> 0,
        'vendor_id'=> $alokasi['vendor_id'],
        'no_dokumen'	=> $alokasi['no_dokumen'],
        'idref'	=> $alokasi['invoice_id'],
        'urlref'	=> 'pembelian/pembayaranpembeliandagang'
    
      );
      $this->model_catalog_vendorlokal->addHistoryDeposit($hutang);
      $this->model_catalog_vendorlokal->updateDeposit($alokasi['vendor_id'],$alokasi['nominal'],1);

      $ju=$this->db->first('jurnal_umum',array('ref'=>$id,'type'=>2025));
      if(!empty($ju)){
        $this->db->query("UPDATE jurnal_umum set hapus=1 WHERE id='".$ju['id']."' ");
        //$this->db->delete('jurnal_umum',array('id'=>$ju['id']));
        //$this->db->delete('jurnal_umum_detail',array('jurnal_id'=>$ju['id']));
      }

    }
  }

  public function getPermintaanPembelianPembayaran($id){
    return $this->db->alljoin('alokasi_deposit_kredit',$column,$join,$where,array(),0,null);
  }

  public function getPermintaanPembelianPembayaraninv($id){
    $d=$this->db->query("SELECT * FROM alokasi_deposit_kredit WHERE hapus=0 and invoice_id=$id ");
    return $d->rows;
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
