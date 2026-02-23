<?php
class ModelSaleInvoice extends Model {
  
  // baru 5 September 2020
  public function getlapsalebysj($data){
    $total=0;
    $pajak=0;
    $this->load->model('catalog/gudang');
		$gd = $this->model_catalog_gudang->getGudangs(true);
		$this->load->model('localisation/country');
		$this->data['countries'] = $this->model_localisation_country->getCountries();
		$gudangs=array();
		if(!empty($gd)){
			foreach($gd as $g){
				$gudangs[]=$g['gudang_id'];
			}
		}else{
			$gudangs[]=$data['filter_gudang_id'];
		}
    
		$arrsql=implode(',',$gudangs);
    $hasil=array();
    $sql="SELECT pp.invoice_id FROM penjualan p LEFT JOIN penjualan_product pp ON(p.id=pp.sales_order_id) ";
    $sql.=" WHERE p.status<>3 ";
    if(!empty($data['filter_date_start'])){
      $sql.=" AND DATE(p.date_added) >= '".$data['filter_date_start']."' AND DATE(p.date_added) <='".$data['filter_date_end']."' ";
    }
    $sql.=" GROUP BY pp.invoice_id ";
    $s=$this->db->query($sql);
    $sj=$s->rows;
    foreach($sj as $s){
      //$total+=round($s['total']);
      //$pajak+=round($s['pajak']);
      if(!empty($s['invoice_id'])){
        //$nosj[]=$s['id'];
        $ivs="SELECT i.* FROM invoice i LEFT JOIN customer c ON(c.customer_id=i.customer_id) WHERE i.id='".$s['invoice_id']."' ";
        $ivs.=" AND i.gudang_id IN($arrsql) and i.status IN (".$data['filter_status'].") ";
        $i=$this->db->query($ivs);
        $inv=$i->rows;
        foreach($inv as $is){
          $ki[]=$is['id'];
          $total+=($is['total']);
          $pajak+=($is['pajak']);
        }
      }
      
    }

    $hasil=array(
      'total'=>$total,
      'pajak'=>$pajak,
      'jumlah'=>$total-$pajak,
      //'sj'=>implode(",",$nosj),
      //'count'=>count($nosj),
      'iv'=>implode(",",$ki),
      'countiv'=>count($ki),
    );

    return $hasil;
  }
  // end baru
  // baru 4 September 2020
  public function gettglsj($invoice_id){
    $sql="SELECT DATE(p.date_added) as tglsj FROM penjualan p JOIN penjualan_product pp ON(pp.sales_order_id=p.id) WHERE pp.invoice_id='$invoice_id' ";
    $d=$this->db->query($sql);
    return $d->row['tglsj'];
  }
  public function getnosj($invoice_id){
    $sql="SELECT p.no_sj as nosj FROM penjualan p JOIN penjualan_product pp ON(pp.sales_order_id=p.id) WHERE pp.invoice_id='$invoice_id' ";
    $d=$this->db->query($sql);
    return $d->row['nosj'];
  }
  // end baru
  // baru 23 Januari 2020
  public function getnomorso($sales_order_id){
    $sql ="SELECT no_so FROM invoice_product WHERE sales_order_id='$sales_order_id' ";
    $query = $this->db->query($sql);
    return $query->row['no_so'];
  }
  public function getpi($no_so){
    $hasil=array();
    $sql ="SELECT pi.no_faktur FROM proforma_invoice pi JOIN proforma_invoice_product pip ON (pip.sales_order_id=pi.id) WHERE pip.referensi_so='$no_so' ";
    $query = $this->db->query($sql);
    $pi = $query->row['no_faktur'];
    if($pi==null){
      $sql2 ="SELECT pi.no_faktur FROM proforma_invoice pi WHERE pi.referensi='$no_so' ";
      $query2 = $this->db->query($sql2);
      $pi2 = $query2->row['no_faktur'];
    }
    $hasil = array(
      'pi1' => $pi,
      'pi2' => $pi2,
    );
    return $hasil;
  }
  public function getpisby($no_so){
      $sql2 ="SELECT pi.no_faktur FROM proforma_invoice pi WHERE pi.referensi='$no_so' ";
      $query2 = $this->db->query($sql2);
      $pi2 = $query2->row['no_faktur'];
      $hasil = array(
        //'pi1' => $pi,
        'pi2' => $pi2,
      );
      return $hasil;
  }
  // end baru
  /*
  1. ditagih
  2. belum lunas
  3. lunas
  4. dibatalkan
  */
  
  // baru 24 Agustus 2019
  
  public function getdetailproduct($sales_order_id){
	$sql ="SELECT ip.*, p.name FROM invoice_product ip JOIN product p ON(p.product_id=ip.product_id) WHERE ip.sales_order_id='$sales_order_id' ";
	$query = $this->db->query($sql);
	return $query->rows;
  }
  
  //
  public function test()
  {
    $q = $this->db->query("select * from invoice order by id desc limit 20");
    return $q->rows;
  }

  public function getnofakturpajak($id)
  {
    $q = $this->db->query("select * from invoice where id=$id");
    return $q->rows;
  }

  public function addPenjualan($data){
    $usia=1;


    $j=array(
      'date_added' => isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s'),
      //'sales'  => $data['sales'],
      'customer_id'  => $data['customer_id'],
      'status' => 1,
      //'referensi'  => $data['referensi'],
      'hapus' => 0,
      'user_id'  => $this->user->getId(),
      'gudang_id' =>$data['gudang_id'],
      'sub_total' => $data['sub_total'],
      'diskon'  => $data['diskon'],
      'pajak' => $data['pajak'],
      'total' => $data['total'],
      'totalbayar'  => 0,
      'cetak' => 0,
      //'pembulatan'  => empty($data['pembulatan'])?0:$data['pembulatan'],
      'pembulatan' => 0,
      //'gudang_id'  => $data['gudang_id'],
      //'metode_pembayaran' => $data['metode_pembayaran'],
      'jatuhtempo'  => $data['jatuh_tempo'],
      'jenisinvoice'  => 3,
      'totaltagihan'  => empty($data['totaltagihan'])?$data['total']:$data['totaltagihan'],
      'jenispenjualan'  => $data['jenispenjualan'],
      'metode_pembayaran'  => $data['metode_pembayaran'],
      'dp'  => 0,
    //  'jenisstok' => empty($data['jenisstok'])?1:$data['jenisstok']
      //'no_faktur'
    );
    $this->db->insert('invoice',$j);
    $id=$this->db->getlastId();
    $data['id']=$id;
    $data['jenisinvoice']=3;
    if($data['jenisinvoice'] == 1){
      $prefix="PI-";
    }
    if($data['jenisinvoice'] == 2){
      $prefix="UM-";
    }
    if($data['jenisinvoice'] == 3){
      $prefix="INV-";
    }
    $no_faktur=$prefix.$id."-".date('Y')."-".date("m")."-".$this->user->getId();
    $no_dokumen="PDG-".$id."-".date('Y')."-".date("m")."-".$this->user->getId();

    //buku piutang
    /*
    customer_id integer,
    date_added timestamp without time zone,
    saldomasuk double precision,
    saldokeluar double precision,
    idref integer,
    urlref character varying(255) COLLATE pg_catalog."default",
    no_dokumen character varying(255) COLLATE pg_catalog."default",
    referensi character varying(255) COLLATE pg_catalog."default",
    */
    $piutang=array(
      'customer_id'=>$data['customer_id'],
      'date_added'=>isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s'),
      'saldomasuk'=>$data['total'],
      'saldokeluar' =>0,
      'idref' =>$id,
      'urlref'  =>'sale/invoice',
      'no_dokumen'  =>$no_dokumen,
      'referensi' =>$no_faktur
    );

    $this->db->insert('buku_piutang',$piutang);

    /*if($data['jenisinvoice'] == 3){
    if($data['jenispenjualan'] == 1){

     $this->load->model('sale/penjualan');

      $this->model_sale_penjualan->updatePenjualan(array('no_invoice' => $no_faktur),array('id'=>$data['referensi']));
      $so['usia']=$data['usia'];
    }
    if($data['jenispenjualan'] == 2){
      $this->load->model('sale/penjualanmr');
      $penj=$this->model_sale_penjualanmr->getPenjualan(array('id'=>$data['referensi']));
      $this->model_sale_penjualanmr->updatePenjualan(array('no_invoice' => $no_faktur),array('id'=>$data['referensi']));

      $this->load->model('sale/salesorder');
      $so=$this->model_sale_salesorder->getPenjualan(array('id'=>$penj['no_so']));
    }
    if($data['jenispenjualan'] == 3){
        $this->load->model('sale/penjualanbahanbaku');
        $penj=$this->model_sale_penjualanbahanbaku->getPenjualan(array('id'=>$data['referensi']));
        $this->model_sale_penjualanbahanbaku->updatePenjualan(array('no_invoice' => $no_faktur),array('id'=>$data['referensi']));

        $this->load->model('sale/salesorderbahanbaku');
        $so=$this->model_sale_salesorderbahanbaku->getPenjualan(array('id'=>$penj['no_so']));

    }
  }else{
    if($data['jenispenjualan'] == 1){

      $this->load->model('sale/salesorder');
      $so=$this->model_sale_salesorder->getPenjualan(array('id'=>$data['referensi']));
    }
    if($data['jenispenjualan'] == 2){
      $this->load->model('sale/salesordermr');
      $so=$this->model_sale_salesordermr->getPenjualan(array('id'=>$data['referensi']));
    }
    if($data['jenispenjualan'] == 3){
        $this->load->model('sale/salesorderbahanbaku');
        $so=$this->model_sale_salesorderbahanbaku->getPenjualan(array('id'=>$data['referensi']));

    }
  }*/

/*  if($so['usia'] < $data['usia']){
    $usia=$so['usia'];
  }else{
    $usia=$data['usia'];
  }

  $time= strtotime($j['date_added']) + ($usia*86400);

    $jatuhtempo=date("Y-m-d H:i:s",$time);

    $this->db->update('invoice',array('no_faktur' => $no_faktur,'jatuhtempo'=>$jatuhtempo),array('id' => $id));*/
    $this->db->update('invoice',array('no_faktur' => $no_faktur,'no_dokumen'=>$no_dokumen),array('id' => $id));



    //add product
    $pendapatan=$this->addPenjualanProduct($data);

    $disk['produkdagang']=($pendapatan['produkdagang']/$data['sub_total'])*$data['diskon'];
    $disk['produksi']=($pendapatan['produksi']/$data['sub_total'])*$data['diskon'];

    //add piutang
    if($data['jenisinvoice'] == 3){
      $this->load->model('sale/customer');
      //cek uang muka
      //add jurnal
      $this->model_sale_customer->updatePiutang($data['customer_id'],$data['total'],1);

      $this->load->model('keuangan/jurnal');

      $this->load->model('keuangan/pajak');
      $details=array();
      /* Jurnal Baru */
      //if($this->user->getUsername()=="pawit"){
          $details[]=array(
            'ref_akun'  => "1101",
            'keterangan'  => "Piutang Usaha",
            'debet' => $data['total'],
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );
          $details[]=array(
            'ref_akun'  => "1102",
            'keterangan'  => "Piutang Belum Tertagih",
            'debet' =>0,
            'kredit'  => $data['total'],
            'urutan'  => 1,
            'hapus' => 0
          );
      //}
      /* End Jurnal Baru */
      /* Jurnal Lama */
      /*
      $details[]=array(
        'ref_akun'  => "1101",
        'keterangan'  => "Piutang Usaha",
        'debet' => $data['total'],
        'kredit'  => 0,
        'urutan'  => 1,
        'hapus' => 0
      );
      /*
      baru 17 September 2019, aktifkan jika surat jalan belum difakturkan sudah celar / tidak ada data
      $details[]=array(
        'ref_akun'  => "1102",
        'keterangan'  => "Piutang dagang belum tertagih",
        'debet' => 0,
        'kredit'  => $data['total'],
        'urutan'  => 1,
        'hapus' => 0
      );
      */
      /*
        if($disk['produkdagang'] > 0){
          $details[]=array(
            'ref_akun'  => "4002",
            'keterangan'  => "Potongan Harga Produk Dagang",
            'debet' => $disk['produkdagang'],
            'kredit'  => 0,
            'urutan'  => 2,
            'hapus' => 0
          );
        }
        if($disk['produksi'] > 0){
          $details[]=array(
            'ref_akun'  => "4005",
            'keterangan'  => "Potongan Harga Hasil Produksi",
            'debet' => $disk['produksi'],
            'kredit'  => 0,
            'urutan'  => 3,
            'hapus' => 0
          );
        }
      if($data['pajak'] > 0){
        $details[]=array(
          'ref_akun'  =>'2505',
          'debet' => 0,
          'kredit'  => $data['pajak'],
          'urutan'  =>4,
          'keterangan'  => 'Hutang PPN Keluaran'
        );

        $pajak=array(
          'ref' => $data['id'],
          'jumlah'  => $data['pajak'],
          'akun' => '2505',
          'jenis' => 2
        );
        $this->model_keuangan_pajak->addPajak($pajak);
      }
      if($pendapatan['produkdagang'] > 0){
        $details[]=array(
          'ref_akun'  =>'4001',
          'debet' => 0,
          'kredit'  => $pendapatan['produkdagang'],
          'urutan'  =>5,
          'keterangan'  => 'Pendapatan'
        );
      }

      if($pendapatan['produksi'] > 0){
        $details[]=array(
          'ref_akun'  =>'4004',
          'debet' => 0,
          'kredit'  => $pendapatan['produksi'],
          'urutan'  =>6,
          'keterangan'  => 'Pendapatan'
        );
      }

      /* End Jurnal Lama */

      $j=array(
        'tanggal' => $data['date_added'],
        'keterangan'  => 'Pendapatan '.$no_faktur,
        'details' => $details,
        'hapus' =>0,
        'ref' => $data['id'],
		    'linkterkait' => $no_faktur,
        'type'  => 4,
        'urlref'  => 'sale/invoice',
        'idref' => $data['id'],
        'no_dokumen'  => $no_dokumen
      );
      $this->model_keuangan_jurnal->addJurnalUmum($j);
    }



    return $id;

  }
  public function addPenjualanProduct($data){
    $total=0;
    $diskon=0;
  //  $pendapatan=array();

  //  $penjualan=$this->getPenjualan(array('id' => $data['id']));
    $pendapatan=array(
      'produkdagang'  => 0,
      'produksi'  => 0
    );
  $this->load->model('catalog/product');
    foreach($data['product'] as $p){
      if($p['pilih']){
        if(!empty($p['product_id'])){
          //getnetcost
          //$net=$this->db->first('product_toko_pameran',array('gudang_id'  => $penjualan['pameran_id'],'product_id'  => $p['product_id']));

          $curqty=$this->model_catalog_product->getProduct($p['product_id']);
          //$pajak=$p['price']*0.1;
        //  $total=($p['price']*$p['quantity']) + ($pajak*$p['quantity']);

          $penj=array(
            'sales_order_id' => $data['id'],
            'product_id'  => $p['product_id'],
            'quantity' => $p['quantity'],
            'price' => $p['price'],
            'diskon' => 0,
            'pajak' => $p['pajak'],
            'total' => $p['total'],
            'pembulatan'  => empty($p['pembulatan'])?0:$p['pembulatan'],
            //'hapus' => 0,
            //'net_cost'  => empty($p['net_cost'])?$curqty['net_cost']:$p['net_cost'],
            'net_cost'  => $p['net_cost'],
            'jenispenjualan' => $p['jenispenjualan'],
            'penjualan_id' => $p['sales_order_id'],
            'penjualan_product_id'  => $p['id'],
            'no_so' => $p['no_so'],
            'sales_order_product_id'  => $p['id'],
            'jenisstok' => $p['jenisstok'],
            'harga_terendah' => empty($p['harga_terendah'])?0:$p['harga_terendah'],
          );

          if($p['jenispenjualan'] == 1){
            $pendapatan['produkdagang'] += $p['price'] * $p['quantity'];
          }else{
            $pendapatan['produksi'] += $p['price'] * $p['quantity'];
          }
        $this->db->insert('invoice_product',$penj);

        if($data['jenispenjualan'] == 1){
          $this->db->update('penjualan_product',array('invoice_id'=>$data['id']),array('id'=>$p['id'],'sales_order_id'=>$p['sales_order_id']));
        }



      }
    }
    }
    return $pendapatan;

  }

  


  public function cancelPenjualan($id){
    $penj=array(
      'status'  => 0
    );

    $where=array(
      'id'  => $id
    );
    $this->db->update('sales_order',$penj,$where);
    //kembalikan stok produk
    $products=$this->getPenjualanProducts(array('sales_order_id'=> $id));
    foreach($products as $p){
      $this->deleteProduct($p['id']);
    }
  }

  public function voidInvoice($id){
    if(!empty($id)){
    $inv=$this->getPenjualan(array('id' => $id));
    if($inv['status'] == 1){
      if($inv['jenisinvoice'] == 3){
        $this->load->model('sale/customer');
        $this->model_sale_customer->updatePiutang($inv['customer_id'],$inv['total'],2);

        $piutang=array(
          'customer_id'=>$inv['customer_id'],
          'date_added'=>date('Y-m-d H:i:s'),
          'saldokeluar'=>$inv['total'],
          'saldomasuk' =>0,
          'idref' =>$id,
          'urlref'  =>'sale/invoice',
          'no_dokumen'  =>$inv['no_dokumen'],
          'referensi' =>$inv['no_faktur']
        );
    
        $this->db->insert('buku_piutang',$piutang);

        if($inv['pajak'] > 0){
          $this->db->delete('pajak',array('jenis'=>2,'ref'=>$id));
        }
        $this->db->update('jurnal_umum',array('hapus' => 1),array('type'=>4,'ref'=>$id));
        $this->db->update('penjualan_product',array('invoice_id' => 0),array('invoice_id'=>$id));
      }

      $this->updatePenjualan(array('status'=>4),array('id'=>$id));
      }
    }
  }

  public function updatePenjualan($data,$where=array()){
	$this->db->update('invoice',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('invoice',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order=array()){
		return $this->db->firstdetail('invoice',$column,$join,$where,$order);
	}
  public function getPenjualanProducts($jenis,$where){
		//return $this->db->all('penjualan_toko_product',$where);

    $column=array('invoice_product.product_id','satuan.name as namasatuan','invoice_product.id','invoice_product.diskon','invoice_product.pajak','invoice_product.total','product.name','invoice_product.quantity','invoice_product.net_cost','invoice_product.price','invoice_product.penjualan_id','invoice_product.no_so','invoice_product.harga_terendah');
    $join=array();
    if($jenis == 1){
      $join[]=array(
        'tablename' => 'product',
        'firsttable'  => 'invoice_product.product_id',
        'secondtable' => 'product.product_id'
      );
      $leftjoin=array();
      $leftjoin[]=array(
        'tablename' => 'satuan',
        'firsttable'  => 'product.satuan',
        'secondtable' => 'satuan.id'
      );
    }
    if($jenis == 2){
      $join[]=array(
        'tablename' => 'product',
        'firsttable'  => 'invoice_product.product_id',
        'secondtable' => 'product.product_id'
      );
      $leftjoin=array();
      $leftjoin[]=array(
        'tablename' => 'satuan',
        'firsttable'  => 'product.satuan',
        'secondtable' => 'satuan.id'
      );
    }
    if($jenis == 3){
      $join[]=array(
        'tablename' => 'bahanbaku',
        'firsttable'  => 'invoice_product.product_id',
        'secondtable' => 'bahanbaku.id'
      );
      $leftjoin=array();
      $leftjoin[]=array(
        'tablename' => 'satuan',
        'firsttable'  => 'bahanbaku.satuan',
        'secondtable' => 'satuan.id'
      );
    }




    return $this->db->alljoins('invoice_product',$column,$join,$leftjoin,$where,array(),0,null);
	}

  //public function getTotal

  public function getPenjualanProduct($where){
		return $this->db->first('invoice_product',$where);
	}
  public function getPenjualanProductDetail($column,$join,$where){
		return $this->db->firstdetail('invoice_product',$where);
	}
	public function getPenjualans($column=array(),$join=array(),$where=array(),$order,$limit,$offset){

		return $this->db->alljoin('invoice',$column,$join,$where,$order,$limit,$offset);
	}
	/* baru 10 Agustus 2019*/
	public function getnoso($sales_order_id){
		$d = $this->db->query(" SELECT ip.no_so, so.sales FROM invoice_product ip JOIN sales_order so ON(so.id=ip.no_so) WHERE sales_order_id='$sales_order_id' LIMIT 1 ");
		return $d->row;
	}
	/* End Baru 10 Agustus 2019 */
	public function totalPenjualans($where){
		return $this->db->count('invoice',$where);
	}
  public function totalPenjualanDetail($where,$join){
    return $this->db->countAll('invoice',$where,$join);
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

  public function addFakturPajak($data){
    foreach($data['orders'] as $o){
      if(!empty($o['invoice_id'])){
        $this->db->update('invoice',array('no_fakturpajak'=>$o['no_fakturpajak']),array('id'=>$o['invoice_id']));
      }
    }
  }


}
?>
