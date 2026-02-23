<?php
/*
Created by morebit   | http://morebit.co   |  info@morebit.co
*/
class ModelReportPembelian extends Model {
  
  // baru 23 Juli 2020
  public function getinvoiceselected($invoice_id){
    $sql="SELECT  ip.no_faktur,ipd.* FROM invoice_pembeliandagang ip JOIN invoice_pembelian_productdagang ipd ON (ipd.invoice_id=ip.id) and ip.hapus=0 and ip.status<>3 ";
    $sql.="  WHERE ipd.po_product_id='".$invoice_id."' ";
    $d=$this->db->query($sql);
    return $d->row;
    //return $sql;
  }
  public function getinvoice($invoice_id){
    $sql="SELECT ip.tglfaktur,po.no_po,ip.no_faktur,ip.diskon,ip.no_dokumen,ip.total as totalivs,ip.status,ipd.* FROM invoice_pembeliandagang ip JOIN invoice_pembelian_productdagang ipd ON (ipd.invoice_id=ip.id) and ip.hapus=0 and ip.status<>3 ";
    $sql.=" LEFT JOIN pembelian_kreditdagang po ON (po.id=ipd.po_id) ";
    $sql.="  WHERE ipd.po_product_id='".$invoice_id."' ";
    $d=$this->db->query($sql);
    return $d->rows;
    //return $sql;
  }
  public function getproducts($invoice_id,$data){
    $sql="SELECT sjp.tgl_terima,sjp.no_dokumen,ipp.* FROM invoice_pembeliandagang ipd JOIN invoice_pembelian_productdagang ipp ON (ipp.invoice_id=ipd.id) LEFT JOIN suratjalan_produkdagang sjpd ON(sjpd.pembelian_product_id=ipp.po_product_id) LEFT JOIN suratjalan_pembeliandagang sjp ON(sjp.id=sjpd.id_suratjalan)";
    $sql.="  WHERE ipp.invoice_id='$invoice_id' AND sjp.tgl_terima BETWEEN '".$data['filter_date_startsj']."' and '".$data['filter_date_endsj']."'";
    $d=$this->db->query($sql);
    return $d->rows;
  }
  // baru 10 Juni 2020
  public function getpembelianImport($data){
    // lama 
   //$sql="SELECT * FROM invoice_pembelian_import WHERE hapus=0 ";
    
    // baru
    $sql=" SELECT invoice_pembelian_import.id,invoice_pembelian_import.vendor_id,invoice_pembelian_import.status,invoice_pembelian_import.hapus,invoice_pembelian_import.total,invoice_pembelian_import.totalbayar,invoice_pembelian_import.kursdatang,invoice_pembelian_import.bmpib,invoice_pembelian_import.tglfaktur,invoice_pembelian_import.metode_pembayaran,invoice_pembelian_import.jatuhtempo,invoice_pembelian_import.tgllunas,invoice_pembelian_import.no_faktur FROM invoice_pembelian_import ";
    if(!empty($data['filter_datesj_start'])){
      $sql.=" JOIN suratjalan_pembelianimport ON(suratjalan_pembelianimport.pembelian_import_id=invoice_pembelian_import.id) ";
      $sql .=" WHERE invoice_pembelian_import.hapus=0 AND tgl_terima >= '".$data['filter_datesj_start']."' AND tgl_terima <= '".$data['filter_datesj_end']."' ";
    }else{
      $sql.=" WHERE invoice_pembelian_import.hapus=0  ";
    }
   
    if(empty($data['filter_datesj_start'])){
      if(!empty($data['filter_date_start'])){
        $sql .=" AND tglfaktur >= '".$data['filter_date_start']."'";
      }
      if(!empty($data['filter_date_end'])){
        $sql .=" AND tglfaktur <= '".$data['filter_date_end']."'";
      }
    } 
    /*if(!empty($data['filter_date_start'])){
      $sql .=" AND tglfaktur >= '".$data['filter_date_start']."'";
    }
    if(!empty($data['filter_date_end'])){
      $sql .=" AND tglfaktur <= '".$data['filter_date_end']."'";
    }*/

    if(!empty($data['filter_status'])){
        $sql .=" AND status IN(".$data['filter_status'].") ";
    }

      if(!empty($data['filter_customer_id'])){
        $sql .=" AND vendor_id = '".$data['filter_customer_id']."'";
    }

  $sql.=" ORDER by invoice_pembelian_import.date_added DESC";
    if (isset($data['start']) || isset($data['limit'])) {
      if ($data['start'] < 0) {
        $data['start'] = 0;
      }

      if ($data['limit'] < 1) {
        $data['limit'] = 20;
      }

      $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
    }
    
    $query = $this->db->query($sql);
    return $query->rows;
    //return $sql;
    
}

public function getBiayaImport($id){
  
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
    //$total=$this->db->firstdetail('biaya_pembelianimport',array('COALESCE(COUNT(total),0) as total'),array(),array('order_id'=>$id),array(),0,null);
    $total=$this->db->query("SELECT COALESCE(SUM(total),0) as total FROM biaya_pembelianimport WHERE order_id='".$id."' ");
    return $total->row['total'];
}
public function getPermintaanPembelianProduct($where){
  return $this->db->all('invoice_pembelian_import_product',$where,array(),0,null);
}
  // end baru
  // pembelian
  public function getpembelianlokal($data){      
    $sql=" SELECT ipd.* FROM invoice_pembeliandagang ipd  ";  
    if(!empty($data['filter_date_startsj'])){
        //$sql=" SELECT sjp.tgl_terima,ipd.* FROM invoice_pembeliandagang ipd ";
        //$sql.=" JOIN invoice_pembelian_productdagang ipp ON (ipp.invoice_id=ipd.id) LEFT JOIN suratjalan_produkdagang sjpd ON(sjpd.pembelian_product_id=ipp.po_product_id) LEFT JOIN suratjalan_pembeliandagang sjp ON(sjp.id=sjpd.id_suratjalan)";
        //$sql.=" WHERE ipd.hapus=0  AND (sjp.tgl_terima) BETWEEN '".$data['filter_date_startsj']."' AND '".$data['filter_date_endsj']."' ";
        $sql.=" WHERE ipd.hapus=0 ";
      }else{
        //$sql=" SELECT ipd.* FROM invoice_pembeliandagang ipd  ";
        $sql.=" WHERE ipd.hapus=0 ";
      }
      if(!empty($data['filter_date_start'])){
        $sql .=" AND ipd.tglfaktur >= '".$data['filter_date_start']."'";
      }
    if(!empty($data['filter_date_end'])){
        $sql .=" AND ipd.tglfaktur <= '".$data['filter_date_end']."'";
    }

    if(!empty($data['filter_status'])){
      $sql .=" AND ipd.status IN(".$data['filter_status'].") ";
    }else{
      $sql.=" AND ipd.status NOT IN (3) ";
    }

    if(!empty($data['filter_customer_id'])){
      $sql .=" AND ipd.vendor_id = '".$data['filter_customer_id']."'";
  }

    $sql.=" ORDER by date_added DESC";
      if (isset($data['start']) || isset($data['limit'])) {
        if ($data['start'] < 0) {
          $data['start'] = 0;
        }
  
        if ($data['limit'] < 1) {
          $data['limit'] = 20;
        }
  
        $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
      }
      
      $query = $this->db->query($sql);
      return $query->rows;
      
  }


  // end pembelian
  public function listSo($product_id,$gudang_id,$data){
    $sql="SELECT * FROM sales_order_product ip JOIN sales_order i ON(ip.sales_order_id=i.id) WHERE i.status <> 4 AND ip.status_pengiriman <> 4 AND ip.product_id='".$product_id."' AND i.gudang_id='".$gudang_id."'";
    if(!empty($data['filter_date_start'])){
        $sql .=" AND i.date_added >= '".$data['filter_date_start']."'";
    }
    if(!empty($data['filter_date_end'])){
        $sql .=" AND i.date_added <= '".$data['filter_date_end']."'";
    }
    $q=$this->db->query($sql);



    $list=array();
    foreach($q->rows as $r){
      $sj=$this->db->all('penjualan_product',array('no_so'  => $r['sales_order_id']));
      $detailsj=array();
      foreach($sj as $s){
        //invoice
        $nosj=$this->db->first('penjualan',array('id'=>$s['sales_order_id']));
        $inv=$this->db->firstdetail('invoice',array(),array(),array('jenisinvoice'=>3,'jenispenjualan'=> 1,'status'=>array('<>',4),'referensi'=>$s['sales_order_id']));
        $detailsj[]=array(
          'id'  => $s['sales_order_id'],
          'no_sj' => $nosj['no_sj'],
          'date_added'  => date('d/m/y',strtotime($nosj['date_added'])),
          'invoice' => $inv
        );

      }
      $pinv=$this->db->firstdetail('invoice',array(),array(),array('jenisinvoice'=>1,'jenispenjualan'=> 1,'status'=>array('<>',4),'referensi'=>$r['id']));
      $dpinv=$this->db->firstdetail('invoice',array(),array(),array('jenisinvoice'=>2,'jenispenjualan'=> 1,'status'=>array('<>',4),'referensi'=>$r['id']));
      $list[]=array(
        'date_added'  => date('d/m/y',strtotime($r['date_added'])),
        'sales_order_id'  => $r['sales_order_id'],
        'no_so' => $r['no_so'],
        'quantity'  => $r['quantity'],
        'quantityterima'  => $r['quantityterima'],
        'sj'  => $detailsj,
        'proforma'=>$pinv,
        'dp'  => $dpinv
      );
    }
    $this->log->write('Report ' . json_encode($list));
    return $list;
    //return $q->rows
  }
  public function getProductTerjualByGudang($data=array()){
    //$sql="SELECT i.gudang_id,g.nama,COALESCE(SUM(ip.pajak*ip.quantity),0) as pajak,COALESCE(SUM(ip.total),0) as total,COALESCE(SUM(ip.quantity),0) as quantity,COALESCE(SUM(ip.net_cost*ip.quantity),0) as net_cost,COALESCE(SUM(ip.price*ip.quantity),0) as price ";
    $sql="SELECT i.gudang_id,g.nama,COALESCE(SUM(ip.pajak),0) as pajak,COALESCE(SUM(ip.total),0) as total,COALESCE(SUM(ip.quantity),0) as quantity,COALESCE(SUM(ip.net_cost*ip.quantity),0) as net_cost,COALESCE(SUM(ip.price*ip.quantity),0) as price ";
	$sql .=" FROM invoice_product ip JOIN invoice i ON(ip.sales_order_id=i.id) JOIN product p ON(ip.product_id=p.product_id) JOIN gudang g ON(i.gudang_id=g.gudang_id) WHERE i.status <> 4 AND (i.jenispenjualan=1 OR i.jenispenjualan=2) AND i.jenisinvoice=3 ";
    //$sql .= " FROM sales_order_product ip JOIN sales_order i ON(ip.sales_order_id=i.id) JOIN product p ON(ip.product_id=p.product_id) JOIN gudang g ON(i.gudang_id=g.gudang_id) WHERE i.status <> 4 AND ip.status_pengiriman <> 4 ";
    if(!empty($data['filter_name'])){
      $sql .=" AND lower(p.name) LIKE '%".strtolower($data['filter_name'])."%'";
    }
    if(!empty($data['filter_date_start'])){
        $sql .=" AND i.date_added >= '".$data['filter_date_start']."'";
    }
    if(!empty($data['filter_date_end'])){
        $sql .=" AND i.date_added <= '".$data['filter_date_end']."'";
    }
    if(!empty($data['gudang_id'])){
      $sql .= " AND i.gudang_id IN(".$data['gudang_id'].")";
    }
    $sql .=" GROUP BY i.gudang_id,g.nama ";


    $query = $this->db->query($sql);
    return $query->rows;
  }
  public function getProductTerjuals($data=array()){
   // $sql="SELECT ip.product_id,p.name,i.gudang_id,g.nama,COALESCE(SUM(ip.pajak*ip.quantity),0) as pajak,COALESCE(SUM(ip.totalpajak),0) as totalpajak,COALESCE(SUM(ip.total),0) as total,COALESCE(SUM(ip.quantity),0) as quantity,COALESCE(SUM(ip.net_cost*ip.quantity),0) as net_cost,COALESCE(SUM(ip.price*ip.quantity),0) as price ";
   $sql="SELECT ip.product_id,p.name,i.gudang_id,g.nama,COALESCE(SUM(ip.pajak),0) as pajak,COALESCE(SUM(ip.totalpajak),0) as totalpajak,COALESCE(SUM(ip.total),0) as total,COALESCE(SUM(ip.quantity),0) as quantity,COALESCE(SUM(ip.net_cost*ip.quantity),0) as net_cost,COALESCE(SUM(ip.price*ip.quantity),0) as price ";
   $sql .=" FROM invoice_product ip JOIN invoice i ON(ip.sales_order_id=i.id) JOIN product p ON(ip.product_id=p.product_id) JOIN gudang g ON(i.gudang_id=g.gudang_id) WHERE i.status <> 4 AND (i.jenispenjualan=1 OR i.jenispenjualan=2) AND i.jenisinvoice=3 ";
  //  $sql .= " FROM sales_order_product ip JOIN sales_order i ON(ip.sales_order_id=i.id) JOIN product p ON(ip.product_id=p.product_id) JOIN gudang g ON(i.gudang_id=g.gudang_id) WHERE i.status <> 4 AND ip.status_pengiriman <> 4 ";
    if(!empty($data['filter_name'])){
      $sql .=" AND lower(p.name) LIKE '%".strtolower($data['filter_name'])."%'";
    }
    if(!empty($data['filter_date_start'])){
        $sql .=" AND i.date_added >= '".$data['filter_date_start']."'";
    }
    if(!empty($data['filter_date_end'])){
        $sql .=" AND i.date_added <= '".$data['filter_date_end']."'";
    }
    if(!empty($data['gudang_id'])){
      $sql .= " AND i.gudang_id IN(".$data['gudang_id'].")";
    }
    $sql .=" GROUP BY ip.product_id,p.name,i.gudang_id,g.nama ";
	if(!empty($data['filter_urutkan'])){
       if($data['filter_urutkan']==1 && $data['sort']=='ASC'){
		   $sql .=" ORDER BY g.nama ASC ";
	   }
	   if($data['filter_urutkan']==1 && $data['sort']=='DESC'){
		   $sql .=" ORDER BY g.nama DESC ";
	   }
	   if($data['filter_urutkan']==2 && $data['sort']=='ASC'){
		   $sql .=" ORDER BY p.name ASC ";
	   }
	   if($data['filter_urutkan']==2 && $data['sort']=='DESC'){
		   $sql .=" ORDER BY p.name DESC ";
	   }
	   if($data['filter_urutkan']==3 && $data['sort']=='ASC'){
		   $sql .=" ORDER BY COALESCE(SUM(ip.quantity),0) ASC ";
	   }
	   if($data['filter_urutkan']==3 && $data['sort']=='DESC'){
		   $sql .=" ORDER BY COALESCE(SUM(ip.quantity),0) DESC ";
	   }
	   if($data['filter_urutkan']==4 && $data['sort']=='ASC'){
		   $sql .=" ORDER BY COALESCE(SUM(ip.price*ip.quantity),0) ASC ";
	   }
	   if($data['filter_urutkan']==4 && $data['sort']=='DESC'){
		   $sql .=" ORDER BY COALESCE(SUM(ip.price*ip.quantity),0) DESC ";
	   }
	   if($data['filter_urutkan']==5 && $data['sort']=='ASC'){
		   $sql .=" ORDER BY COALESCE(SUM(ip.pajak*ip.quantity),0) ASC ";
	   }
	   if($data['filter_urutkan']==5 && $data['sort']=='DESC'){
		   $sql .=" ORDER BY COALESCE(SUM(ip.pajak*ip.quantity),0) DESC ";
	   }
	   if($data['filter_urutkan']==6 && $data['sort']=='ASC'){
		   $sql .=" ORDER BY COALESCE(SUM(ip.net_cost*ip.quantity),0) ASC ";
	   }
	   if($data['filter_urutkan']==6 && $data['sort']=='DESC'){
		   $sql .=" ORDER BY COALESCE(SUM(ip.net_cost*ip.quantity),0) DESC ";
	   }
	   if($data['filter_urutkan']==7 && $data['sort']=='ASC'){
		   $sql .=" ORDER BY COALESCE(SUM(ip.total),0) ASC ";
	   }
	   if($data['filter_urutkan']==7 && $data['sort']=='DESC'){
		   $sql .=" ORDER BY COALESCE(SUM(ip.total),0) DESC ";
	   }
    }
    if (isset($data['start']) || isset($data['limit'])) {
      if ($data['start'] < 0) {
        $data['start'] = 0;
      }

      if ($data['limit'] < 1) {
        $data['limit'] = 20;
      }

      $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
    }

    $query = $this->db->query($sql);
    return $query->rows;
	//return $sql;
  }

  public function totalProductTerjuals($data=array()){
    $sql="SELECT ip.product_id,p.name,i.gudang_id,g.nama,COALESCE(SUM(ip.pajak*ip.quantity),0) as pajak,COALESCE(SUM(ip.total),0) as total,COALESCE(SUM(ip.quantity),0) as quantity,COALESCE(SUM(ip.net_cost*ip.quantity),0) as net_cost,COALESCE(SUM(ip.price*ip.quantity),0) as price ";
    $sql .=" FROM invoice_product ip JOIN invoice i ON(ip.sales_order_id=i.id) JOIN product p ON(ip.product_id=p.product_id) JOIN gudang g ON(i.gudang_id=g.gudang_id) WHERE i.status <> 4 AND (i.jenispenjualan=1 OR i.jenispenjualan=2) AND i.jenisinvoice=3 ";
    //$sql .= " FROM sales_order_product ip JOIN sales_order i ON(ip.sales_order_id=i.id) JOIN product p ON(ip.product_id=p.product_id) JOIN gudang g ON(i.gudang_id=g.gudang_id) WHERE i.status <> 4 AND ip.status_pengiriman <> 4 ";
    if(!empty($data['filter_name'])){
      $sql .=" AND lower(p.name) LIKE '%".strtolower($data['filter_name'])."%'";
    }
    if(!empty($data['filter_date_start'])){
        $sql .=" AND i.date_added >= '".$data['filter_date_start']."'";
    }
    if(!empty($data['filter_date_end'])){
        $sql .=" AND i.date_added <= '".$data['filter_date_end']."'";
    }
    if(!empty($data['gudang_id'])){
      $sql .= " AND i.gudang_id IN(".$data['gudang_id'].")";
    }
    $sql .=" GROUP BY ip.product_id,p.name,i.gudang_id,g.nama ";


    $query = $this->db->query($sql);
    return $query->num_rows;
  }


  public function getProductTerjual($data){
    $column=array('COALESCE(SUM(invoice_product.pajak),0) as pajak','COALESCE(SUM(invoice_product.total),0) as total','COALESCE(SUM(invoice_product.quantity),0) as quantity','COALESCE(SUM(invoice_product.net_cost*invoice_product.quantity),0) as net_cost','COALESCE(SUM(invoice_product.price*invoice_product.quantity),0) as price');
    $join=array();
    $join[]=array(
      'tablename' => 'invoice',
      'firsttable'  => 'invoice_product.sales_order_id',
      'secondtable' => 'invoice.id'
    );
    if($data['jenis'] == 1){
      $join[]=array(
        'tablename' => 'product',
        'firsttable'  => 'invoice_product.product_id',
        'secondtable' => 'product.product_id'
      );

    }
    if($data['jenis']== 2){
      $join[]=array(
        'tablename' => 'product',
        'firsttable'  => 'invoice_product.product_id',
        'secondtable' => 'product.product_id'
      );

    }
    if($data['jenis'] == 3){
      $join[]=array(
        'tablename' => 'bahanbaku',
        'firsttable'  => 'invoice_product.product_id',
        'secondtable' => 'bahanbaku.id'
      );


    }

    $where=array();
    if(!empty($data['product_id'])){
      $where['invoice_product.product_id'] = $data['product_id'];
    }
    if(isset($data['filter_name'])){
      $where['product.name'] = array('LIKE',$data['filter_name']);
    }
    if(!empty($data['gudang_id'])){
      $where['invoice.gudang_id'] = $data['gudang_id'];
    }
    if(!empty($data['filter_date_start'])){
      $where['invoice.date_added'] = array('>=',$data['filter_date_start']);
    }
    if(!empty($data['filter_date_end'])){
      $where['invoice.date_added'] = array('<=',$data['filter_date_end']);
    }
    $where['invoice.status'] = array('<>',4);
    $where['invoice.jenispenjualan'] = 3;


    return $this->db->firstdetail('invoice_product',$column,$join,$where);

  }

}
?>
