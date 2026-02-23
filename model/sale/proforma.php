<?php
class ModelSaleProforma extends Model {
  // baru 24 Januari 2020
  public function getnomorso($sales_order_id){
    $sql ="SELECT referensi FROM proforma_invoice WHERE id='$sales_order_id' ";
    $query = $this->db->query($sql);
    return $query->row['referensi'];
  }
  public function getinv($noso){
    $sql ="SELECT i.no_faktur FROM invoice i JOIN invoice_product ip ON(ip.sales_order_id=i.id) WHERE ip.no_so='$noso' ";
    $query = $this->db->query($sql);
    if(!empty($query->row)){
      return $query->row['no_faktur'];
    }
  }
  //end baru
  // baru 5 Desember 2019
  public function getsonew($id){
    $s="SELECT id, no_so FROM sales_order WHERE id='$id' GROUP BY id";
    $d = $this->db->query($s);
    return $d->row;
  }
  public function refso($id){
    $s="SELECT referensi_so FROM proforma_invoice_product WHERE sales_order_id='$id' GROUP BY referensi_so";
    $d = $this->db->query($s);
    return $d->rows;
  }
  // end baru 

  // baru 5 Desember 2019
  public function getso($id){
    $s="SELECT id, no_so,usia FROM sales_order WHERE id='$id'";
    $d = $this->db->query($s);
    return $d->row;
  }
  // end baru 

	public function ubah($id)
	{
		$s="UPDATE proforma_invoice_product SET pajak=0, price=620000,total=6200000 where sales_order_id=".$id;
		$this->db->query($s);
	}
	
	public function pro($id)
	{
		//$s="SELECT * FROM proforma_invoice_product where sales_order_id=".$id;
		$s="SELECT * FROM proforma_invoice where id=".$id;
		$q = $this->db->query($s);
		return $q->row;
  }
  
  // baru testing tangerang
  public function newaddPenjualan($data){
    if($data['jenispenjualan'] == 1){
      $this->load->model('sale/salesorder');
      //$so=$this->model_sale_salesorder->getPenjualan(array('id'=>$data['referensi']));
      $referensi_so=array();
      $m=array();
      foreach($data['product'] as $p){
        if(isset($p['pilih'])){
          $so =$this->getso($p['no_so']);
          $referensi_so[] = $so['id'];
          $m[] = $p['metode_pembayaran'];
        }
      }
    }
    $i=0;
    for($i=0;$i<count($referensi_so);$i++){
      $ref .= $referensi_so[$i];
    }
    for($i=0;$i<count($m);$i++){
      $metode .= $m[$i];
    }
    //echo json_encode($metode);exit;
    if($data['jenispenjualan'] == 2){
      $this->load->model('sale/salesordermr');
      $so=$this->model_sale_salesordermr->getPenjualan(array('id'=>$data['referensi']));
    }
    if($data['jenispenjualan'] == 3){
        $this->load->model('sale/salesorderbahanbaku');
        $so=$this->model_sale_salesorderbahanbaku->getPenjualan(array('id'=>$data['referensi']));

    }
    $time= strtotime($data['date_added']) + ($so['usia']*86400);

    $jatuhtempo=date("Y-m-d H:i:s",$time);
    if($this->user->getUsername()=="pawit"){
      echo "<pre>";print_r($jatuhtempo);exit;
    }
    $j=array(
      'date_added' => isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s'),
      'customer_id'  => $data['customer_id'],
      'status' => 1,
      'hapus' => 0,
      'user_id'  => $this->user->getId(),
      'gudang_id' =>$data['gudang_id'],
      'sub_total' => $data['sub_total'],
      'diskon'  => $data['diskon'],
      'pajak' => $data['pajak'],
      'total' => $data['total'],
      'referensi' => isset($data['referensi'])?$data['referensi']:$ref,
      'totalbayar'  => 0,
      'cetak' => 0,
      'pembulatan' => 0,
      'jatuhtempo'  => $jatuhtempo,
      'jenisinvoice'  => 1,
      'totaltagihan'  => empty($data['totaltagihan'])?$data['total']:$data['totaltagihan'],
      'jenispenjualan'  => $data['jenispenjualan'],
      //'metode_pembayaran'  => $so['metode_pembayaran'],
      'metode_pembayaran'  => $metode,
      'dp'  => 0
    );
    $this->db->insert('proforma_invoice',$j);
    $id=$this->db->getlastId();
    $data['id']=$id;
    $data['jenisinvoice']=1;
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
    $this->db->update('proforma_invoice',array('no_faktur' => $no_faktur),array('id' => $id));
    //add product
    $this->newaddPenjualanProduct($data);
  }
  public function newaddPenjualanProduct($data){
    $this->load->model('catalog/product');
    foreach($data['product'] as $p){
      if(isset($p['pilih'])){
        if(!empty($p['product_id'])){
            $curqty=$this->model_catalog_product->getProduct($p['product_id']);
            $penj=array(
              'sales_order_id' => $data['id'],
              'product_id'  => $p['product_id'],
              'quantity' => $p['quantity'],
              'price' => $p['price'],
              'diskon' => $p['diskon'],
              'pajak' => $p['pajak'],
              'total' => $p['total'],
              'pembulatan'  => empty($p['pembulatan'])?0:$p['pembulatan'],
              'net_cost'  => $p['net_cost'],
              'jenispenjualan' => 1,
              'referensi_so' => $p['no_so'],
            );
          $this->db->insert('proforma_invoice_product',$penj);
        }
      }
    }
  }
  // end baru testing tangerang
  // baru
  public function addPenjualan($data){
    if($data['jenispenjualan'] == 1){

      $this->load->model('sale/salesorder');
      $so=$this->model_sale_salesorder->getPenjualan(array('id'=>$data['referensi']));

      // baru 20 Desember 2019
      $referensi_so=array();
      $m=array();
      foreach($data['product'] as $p){
        if(isset($p['pilih'])){
          $so =$this->getso($p['no_so']);
          $referensi_so[] = $so['id'];
          $m[] = $so['metode_pembayaran'];
        }
      }
      // end baru
    }
    // baru 20 Desember 2019
    $i=0;
    for($i=0;$i<count($referensi_so);$i++){
      $ref .= $referensi_so[$i];
    }
    for($i=0;$i<count($m);$i++){
      $metode .= $m[$i];
    }
    // end baru
    if($data['jenispenjualan'] == 2){
      $this->load->model('sale/salesordermr');
      $so=$this->model_sale_salesordermr->getPenjualan(array('id'=>$data['referensi']));
    }
    if($data['jenispenjualan'] == 3){
        $this->load->model('sale/salesorderbahanbaku');
        $so=$this->model_sale_salesorderbahanbaku->getPenjualan(array('id'=>$data['referensi']));
    }
    $time= strtotime($data['date_added']) + ($so['usia']*86400);
    $jatuhtempo=date("Y-m-d H:i:s",$time);
    if($this->user->getUsername()=="pawit"){
      echo "<pre>";print_r($jatuhtempo);exit;
    }
    $j=array(
      'date_added' => isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s'),
      'customer_id'  => $data['customer_id'],
      'status' => 1,
      'hapus' => 0,
      'user_id'  => $this->user->getId(),
      'gudang_id' =>$data['gudang_id'],
      'sub_total' => $data['sub_total'],
      'diskon'  => $data['diskon'],
      'pajak' => $data['pajak'],
      'total' => $data['total'],
      'referensi' => $data['referensi'],
      //'referensi' => isset($data['referensi'])?$data['referensi']:$ref,
      'totalbayar'  => 0,
      'cetak' => 0,
      'pembulatan' => 0,
      'jatuhtempo'  => $jatuhtempo,
      'jenisinvoice'  => 1,
      'totaltagihan'  => empty($data['totaltagihan'])?$data['total']:$data['totaltagihan'],
      'jenispenjualan'  => $data['jenispenjualan'],
      'metode_pembayaran'  => $so['metode_pembayaran'],
      //'metode_pembayaran'  => $metode,
      'dp'  => 0
    );
    $this->db->insert('proforma_invoice',$j);
    $id=$this->db->getlastId();
    $data['id']=$id;
    $data['jenisinvoice']=1;
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
    $this->db->update('proforma_invoice',array('no_faktur' => $no_faktur),array('id' => $id));
    //add product
    $this->addPenjualanProduct($data);
  }

  public function addPenjualanProduct($data){
    $this->load->model('catalog/product');
    // baru 20 Desember 2019
    /*
    foreach($data['product'] as $p){
      if(isset($p['pilih'])){
        if(!empty($p['product_id'])){
            $curqty=$this->model_catalog_product->getProduct($p['product_id']);
            $penj=array(
              'sales_order_id' => $data['id'],
              'product_id'  => $p['product_id'],
              'quantity' => $p['quantity'],
              'price' => $p['price'],
              'diskon' => 0,
              'pajak' => $p['pajak'],
              'total' => $p['total'],
              'pembulatan'  => empty($p['pembulatan'])?0:$p['pembulatan'],
              'net_cost'  => $p['net_cost'],
              'jenispenjualan' => 1,
              'referensi_so' => $p['no_so'],
            );
          $this->db->insert('proforma_invoice_product',$penj);
        }
      }
    }
    */
    // end baru
    // lama
    foreach($data['product'] as $p){
        if(!empty($p['product_id'])){
          $curqty=$this->model_catalog_product->getProduct($p['product_id']);
          $penj=array(
            'sales_order_id' => $data['id'],
            'product_id'  => $p['product_id'],
            'quantity' => $p['quantity'],
            'price' => $p['price'],
            'diskon' => 0,
            'pajak' => $p['pajak'],
            'total' => $p['total'],
            'pembulatan'  => empty($p['pembulatan'])?0:$p['pembulatan'],
            'net_cost'  => $p['net_cost'],
            'jenispenjualan' => 1,
          );
        $this->db->insert('proforma_invoice_product',$penj);
      }
    }
    // end lama
  }

  public function updatePenjualan($data,$where=array()){
	$this->db->update('proforma_invoice',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('proforma_invoice',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order=array()){
		return $this->db->firstdetail('proforma_invoice',$column,$join,$where,$order);
	}
  public function getPenjualanProducts($jenis,$where){
		//return $this->db->all('penjualan_toko_product',$where);

    $column=array('proforma_invoice_product.product_id','satuan.name as namasatuan','proforma_invoice_product.id','proforma_invoice_product.diskon','proforma_invoice_product.pajak','proforma_invoice_product.total','product.name','proforma_invoice_product.quantity','proforma_invoice_product.net_cost','proforma_invoice_product.price','proforma_invoice_product.referensi_so');
    $join=array();
    if($jenis == 1){
      $join[]=array(
        'tablename' => 'product',
        'firsttable'  => 'proforma_invoice_product.product_id',
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
        'firsttable'  => 'proforma_invoice_product.product_id',
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
        'firsttable'  => 'proforma_invoice_product.product_id',
        'secondtable' => 'bahanbaku.id'
      );
      $leftjoin=array();
      $leftjoin[]=array(
        'tablename' => 'satuan',
        'firsttable'  => 'bahanbaku.satuan',
        'secondtable' => 'satuan.id'
      );
    }




    return $this->db->alljoins('proforma_invoice_product',$column,$join,$leftjoin,$where,array(),0,null);
	}

  //public function getTotal

  public function getPenjualanProduct($where){
		return $this->db->first('proforma_invoice_product',$where);
	}
  public function getPenjualanProductDetail($column,$join,$where){
		return $this->db->firstdetail('proforma_invoice_product',$where);
	}
	public function getPenjualans($column=array(),$join=array(),$where=array(),$order,$limit,$offset){

		return $this->db->alljoin('proforma_invoice',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where){
		return $this->db->count('proforma_invoice',$where);
	}
  public function totalPenjualanDetail($where,$join){
    return $this->db->countAll('proforma_invoice',$where,$join);
  }


}
?>
