<?php
class ModelPembelianPembeliankreditdagang extends Model {
  
  // baru 29 Agustus 2020
  public function getsj($po_id,$idpem){
    $sql="SELECT spd.*,sjp.no_suratjalan as nosj FROM suratjalan_produkdagang spd LEFT JOIN suratjalan_pembeliandagang sjp ON(spd.id_suratjalan=sjp.id)";
    $sql.=" WHERE spd.po_id='$po_id' AND spd.pembelian_product_id='$idpem'";
    $d=$this->db->query($sql);
    return $d->rows;
    //return $sql;
  }
  public function getiv($po_id,$idpem){
    $sql="SELECT iv.no_faktur,sum(ivpb.quantity) as qty FROM invoice_pembelian_productdagang ivpb LEFT JOIN invoice_pembeliandagang iv on (iv.id=ivpb.invoice_id)";
    $sql.=" WHERE ivpb.po_id='$po_id' AND ivpb.po_product_id='$idpem' and iv.status<>3 and iv.hapus=0 GROUP BY iv.no_faktur ";
    $d=$this->db->query($sql);
    return $d->rows;
    //return $sql;
  }
  // end baru

  // baru 15 Juli 2020
  public function getsjpembelian($id){
    if($id>0){
      $d=$this->db->query("SELECT sp.no_suratjalan,sp.tgl_surat,sp.tgl_terima,sp.no_dokumen,sjp.quantity FROM suratjalan_pembeliandagang sp JOIN suratjalan_produkdagang sjp ON (sjp.id_suratjalan=sp.id) WHERE sjp.pembelian_product_id='$id' ");
      return $d->row;
    }else{
      return 0;
    }
  }
  // end baru
  public function addPembelian($data){
    if($data['keterangan_pembayaran']!=null)
    {
      $top = $data['keterangan_pembayaran'];
    }
    else
    {
      $top =null;
    }
    $p=array(
      'vendor_id' => empty($data['vendor_id'])?0:$data['vendor_id'],
      'gudang_id' => $data['gudang_id'],
      'surat_id'  => isset($data['surat_id'])?$data['surat_id']:0,
      'sub_total' => $data['sub_total'],
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
      'keterangan_pembayaran' => $top,
      'jatuhtempo'  => empty($data['jatuhtempo'])?date('Y-m-d'):$data['jatuhtempo'],
      'metode_pengiriman' => $data['metode_pengiriman'],
      'tglkirim' => $data['tglkirim'],
      'permintaan_pembelian'=> isset($data['spp'])?json_encode($data['spp']):''
    );

    $this->db->insert('pembelian_kreditdagang',$p);
    $id=$this->db->getLastId();
    $data['id'] = $id;
    $data['status']=1;
    $no_surat='PO-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;
    $no_dokumen='LBK-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;
   
  $this->db->update('pembelian_kreditdagang',array('no_po'  => $no_surat/*,'no_dokumen'=>$no_dokumen*/),array('id' => $id));
    
  if(isset($data['surat_id'])){
    $this->db->update('permintaan_pembelian',array('status'=>4),array('id'=>$data['surat_id']));
  }
  if(isset($data['spp'])){
    foreach($data['spp'] as $spp){
      $this->db->update('permintaan_pembelian',array('status'=>4),array('id'=>$spp['permintaan_id']));
    }
  }
    $this->addProduct($data);
    //$this->addBiaya($data);
    return $no_po;


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
        'permintaan_id' => $d['permintaan_id'],
        'hapus' => 0
      );
      $this->db->insert('pembelian_produk_kreditdagang',$prod);

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

  public function barangdatang($data){
    //$this->load->model('catalog/kelompokaset');
    //$this->load->model('pembelian/biayapembeliankredit');
    /*
    status
    1. belum diterima
    2. telah diterima
    3. dibatalkan
    */


    $sj=array(
      'no_suratjalan' => $this->db->escape($data['no_suratjalan']),
      'gudang_id' => empty($data['gudang_id'])?0:$data['gudang_id'],
      'date_added' => date('Y-m-d H:i:s',time()),
      'total' => 0,
      'totalquantity' => 0,
      'hapus' => 0,
      //'tgl_surat' => empty($data['tgl_surat'])?date('Y-m-d H:i:s',time()):$data['tgl_surat'],
      'status'  => 1
      //'tgl_terima' => empty($data['tgl_terima'])?date('Y-m-d H:i:s',time()):$data['tgl_terima']
    );
    $this->db->insert('suratjalan_pembeliandagang',$sj);
    $sj_id=$this->db->getLastId();
    $data['id']=$sj_id;

    //$this->db->update('pembelian_kredit',array('status'  => 1),array('id' => $data['id']));
    $this->load->model('gudang/kartustok');
    $this->load->model('pembelian/invoicepembeliankredit');
    //update stok
    //$pb=$this->getPermintaanPembelian(array(),array(),array('id'  => $data['pembelian_kredit_id']));
    $total=0;
    $nilaipersediaan=0;
    $nilaibiaya=0;
    $nilaipajak=0;
    $totalquantity=0;
    $totalquantitybeli=0;
    $uangmuka=0;
    $uangmukainvoice=0;

    foreach($data['product'] as $p){


      //$inv=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelian(array(),array(),array('id'=>$detail['invoice_id']));
      if($p['pilih']){
        $p['id']=$p['po_product_id'];

        $detail=$this->getPermintaanPembelianProductDetail(array('id' => $p['po_product_id']));
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
          'po_id'  => $p['po_id'],
          'pembelian_product_id'  => $p['po_product_id'],
          'quantity'  => $p['quantityterima']
        );
        $this->db->insert('suratjalan_produkdagang',$pro);
        $total += $p['quantityterima'] * $detail['harga'];

        }
      }

    $no_dokumen='LBK-'.date('Y-m').'-'.$data['no_suratjalan'];
    $this->db->update('suratjalan_pembeliandagang',array('total'  => $total,'totalquantity' => $totalquantity,'no_dokumen'=>$no_dokumen),array('no_suratjalan'=>$data['no_suratjalan']));
    $this->addBiaya($data);


  }

  public function addBiaya($data){
    foreach($data['biaya'] as $b){

      $bia=array(
        'jenisbiaya_id'  => $this->db->escape($b['jenisbiaya_id']),
        'total' => $b['total'],
        'order_id'  => $data['id'],
        'statuspembayaran'  => 0

      );
      $this->db->insert('biaya_pembelian',$bia);

    }

  }

  public function updatePermintaan($data,$where){
    $pp=array();
    if($data['status'] == 3){
      $pembelian=$this->getPermintaanPembelian(array(),array(),$where);
      //$this->db->update('permintaan_pembelian',array('status'=>2),array('id'=>$pembelian['surat_id']));
      // baru 16 Juli 2020
      $pp=json_decode($pembelian['permintaan_pembelian'],true);
      foreach($pp as $p){
        $this->db->update('permintaan_pembelian',array('status'=>2),array('id'=>$p['permintaan_id']));
      }
    }
    $this->db->update('pembelian_kreditdagang',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('pembelian_kreditdagang',$column,$join,$leftjoin,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where,$join=array(),$leftjoin=array()){
		return $this->db->countAll('pembelian_kreditdagang',$where,$join,$leftjoin);
	}

  public function getBarangdatangs($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('suratjalan_pembeliandagang',$column,$join,$leftjoin,$where,$order,$limit,$offset);
  }

  public function totalBarangdatangs($where,$join=array(),$leftjoin=array()){
		return $this->db->countAll('suratjalan_pembeliandagang',$where,$join,$leftjoin);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('pembelian_kreditdagang',$column,$join,$where,array());
  }

  public function getPermintaanPembelianProduct($where){
    return $this->db->all('pembelian_produk_kreditdagang',$where,array(),0,null);
  }
  /*public function getPermintaanPembelianBiaya($where){
    return $this->db->alljoin('biaya_pembelian',array(),array(),$where,array(),0,null);
  }*/
  public function getPermintaanPembelianProductDetail($where){
    return $this->db->firstdetail('pembelian_produk_kreditdagang',array(),array(),$where,array());
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

  public function getPoTanpaInvoice($vendor_id,$gudang_id){
    //tampilkan semua yang
    //$sql="SELECT pi.*,p.no_po FROM pembelian_produk_kredit pi JOIN pembelian_kredit p ON(pi.pembelian_id=p.id) WHERE vendor_id='".$vendor_id."' AND p.status <> 3 AND (pi.invoice_id IS NULL OR pi.invoice_id=0) AND p.gudang_id='".$gudang_id."' AND p.jenis_barang='".$jenisbarang."'";
    $this->load->model('pembelian/invoicepembeliandagang');
    $sql="SELECT pi.*,p.no_po,p.status as statuspenerimaan,p.sub_total,p.diskon as diskontotal  FROM pembelian_produk_kreditdagang pi JOIN pembelian_kreditdagang p ON(pi.pembelian_id=p.id) WHERE vendor_id='".$vendor_id."' AND p.status <> 3 AND p.gudang_id='".$gudang_id."' ";
    $result=$this->db->query($sql);
    $hasil=array();
    foreach($result->rows as $r){
      //$ditagih=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelianProduct(array('p'=>$i['invoice_id']));
      $ditagih=$this->db->query("SELECT COALESCE(SUM(quantity),0) as total FROM invoice_pembelian_productdagang ip JOIN invoice_pembeliandagang i ON(ip.invoice_id=i.id) WHERE (i.status <> 5 AND i.status <> 3) AND ip.po_product_id='".$r['id']."'");
      $qty=$ditagih->row['total'];
      $r['quantity']=$r['quantityterima'] - $qty;
      $r['ditagih']=$qty;
      $r['statuspenerimaan']= $r['statuspenerimaan'];
      $r['harga']=$r['harga'] - (($r['harga']/$r['sub_total'])*$r['diskontotal']);
      if($r['quantityterima'] > 0){
        $hasil[]=$r;
      }
    }
    return $hasil;
  }

  public function getPoBelumDatang($vendor_id,$gudang_id){
    //tampilkan semua yang
    //$sql="SELECT pi.*,p.no_po FROM pembelian_produk_kredit pi JOIN pembelian_kredit p ON(pi.pembelian_id=p.id) WHERE vendor_id='".$vendor_id."' AND p.status <> 3 AND (pi.invoice_id IS NULL OR pi.invoice_id=0) AND p.gudang_id='".$gudang_id."' AND p.jenis_barang='".$jenisbarang."'";

    $sql="SELECT pi.*,p.no_po,p.status as statuspenerimaan FROM pembelian_produk_kreditdagang pi JOIN pembelian_kreditdagang p ON(pi.pembelian_id=p.id) WHERE vendor_id='".$vendor_id."' AND (p.status <> 3 AND p.status <> 5) AND p.gudang_id='".$gudang_id."' AND pi.quantityterima < pi.quantity";
    $result=$this->db->query($sql);
    $hasil=array();
    foreach($result->rows as $r){
      //$ditagih=$this->model_pembelian_invoicepembeliankredit->getPermintaanPembelianProduct(array('p'=>$i['invoice_id']));

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

    if($pembelian['status'] == 2){
      $sql="SELECT pi.*,p.no_po FROM pembelian_produk_kreditdagang pi JOIN pembelian_kreditdagang p ON(pi.pembelian_id=p.id) WHERE pembelian_id='".$id."'";
      $result=$this->db->query($sql);
      $totalinv=0;
      foreach($result->rows as $r){
        //jika sudah ada invoice maka quantity sesuai dengan quantity invoice jika belumada maka quantity sesuai quantity yang diterima
        if($r['quantityterima'] == 0){
          break;
          return false;
        }else{
        //  $ditagih=$this->db->query("SELECT COALESCE(SUM(quantity),0) as total FROM invoice_pembelian_product ip JOIN invoice_pembelian i ON(ip.invoice_id=i.id) WHERE (i.status <> 5 AND i.status <> 3) AND ip.po_product_id='".$r['id']."'");

          $total=$r['quantityterima'];
          if($total > 0){
            $this->db->update('pembelian_produk_kreditdagang',array('quantity'=>$total),array('id'=>$r['id']));
          }else{
            $this->db->update('pembelian_produk_kreditdagang',array('quantity'=>$r['quantityterima']),array('id'=>$r['id']));
          }
        }

      }

      //update sub_total,pajak,total
      $update=$this->db->query("SELECT COALESCE(SUM(quantity*harga),0) as subtotal,COALESCE(SUM(ppn*quantity),0) as pajak FROM pembelian_produk_kreditdagang WHERE pembelian_id='".$id."'");
      $subtotal=$update->row['subtotal'];
      $pajak=$update->row['pajak'];

      $diskon=$pembelian['diskon'];
      $totalbaru=$subtotal+$pajak-$diskon;

      $this->db->update('pembelian_kreditdagang',array('status'=>5,'sub_total'=>$subtotal,'pajak'=>$pajak,'total_pembelian'=>$totalbaru),array('id'=>$id));
      return true;
    }else{
      return false;
    }

  }

}
?>
