<?php
/*
Created by morebit   | http://morebit.co   |  info@morebit.co
*/
class ModelReportPenjualan extends Model {
  /*public function getProductTerjual($product_id,$product_option_id,$data){
    $sql ="SELECT SUM(quantity) AS quantity,SUM(total) as total,SUM(quantity*net_cost) AS net_cost FROM new_order_product np JOIN";
  }*/
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
