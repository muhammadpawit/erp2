<?php
class ModelSalePenawaran extends Model {
  /*
  Statuss
  0. belum diterima
  1. sudah diterima
  2. terdapat selisih
  3. dibatalkan

  $act=array(
    'activity'	=> 'Penerimaan pembelian nomor  '.$pro['invoice_no'],
    'menu'	=> 'Pembelian'
  );
  $this->user->addUserActivity($act);
  */
  public function addPenjualan($data){
    $penawaran=array(
      'customer_id' => $data['customer_id'],
      'sales' => $data['sales'],
      'date_added'  => date('Y-m-d H:i:s'),
      'hapus' => 0,
      'user_id' => $this->user->getId(),
      'date_modified' => date('Y-m-d H:i:s'),
      'sub_total'  => $data['sub_total'],
      'diskon'  => $data['diskon'],
      'pajak' => $data['pajak'],
      'total' => $data['total'],
      'gudang_id' => $data['gudang_id'],
      'usia'  => $data['usia']
    );
    $this->db->insert('penawaran',$penawaran);
    $id=$this->db->getLastId();

    $no_so="PH-".date('Y').'-'.date('m').'-'.$id;
    $data['id']=$id;
    $data['no_so']=$no_so;

    $this->db->update('penawaran',array('no_so'=>$data['no_so']),array('id'=>$id));

    $this->addPenjualanProduct($data);
    return $id;
  }

  public function addPenjualanProduct($data){
    $total=0;
    $diskon=0;

  //  $penjualan=$this->getPenjualan(array('id' => $data['id']));

  $this->load->model('catalog/product');
    foreach($data['product'] as $p){
        if(!empty($p['product_id'])){

          $curqty=$this->model_catalog_product->getProduct($p['product_id']);
          //$pajak=($p['price']-$p['diskon'])*0.1;
          //$total=(($p['price']-$p['diskon'])*$p['quantity']) + ($pajak*$p['quantity']);
          if($p['nilaipajak'] == 1){
            $p['pajak']=round(($p['price'])*0.1);
            $p['pembulatan']=0;
          }
          if($p['nilaipajak'] == 2){
            $p['pajak']=round((10/110)*$p['price']);
            $np=round((100/110)*$p['price']);
            $p['pembulatan']=$p['price'] - ($p['pajak']+$np);
            $p['price']=$np;
          }
          if($p['nilaipajak'] == 3){
            $p['pajak'] = 0;
            $p['pembulatan'] = 0;
          }
          $penj=array(
            'sales_order_id' => $data['id'],
            'product_id'  => $p['product_id'],
            'tabung_id'  => isset($p['tabung_id'])?$p['tabung_id']:0,
            'quantity' => $p['quantity'],
            'quantityterima' => 0,
            'status_pengiriman'  => 1,
            'price' => $p['price'],
            'diskon' => 0,
            'pajak' => $p['pajak'],
            'total' => $p['total'],
            //'pembulatan'  => $p['pembulatan'],
            'hapus' => 0,
            'net_cost'  => empty($curqty['net_cost'])?0:$curqty['net_cost'],
          );

        $this->db->insert('penawaran_product',$penj);


      }
    }

  }
  public function getPenjualan($where){
		return $this->db->first('penawaran',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order){
		return $this->db->firstdetail('penawaran',$column,$join,$where,$order);
	}
  public function getPenjualanProducts($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('penawaran_product.product_id','satuan.name as namasatuan','penawaran_product.id','penawaran_product.diskon','penawaran_product.pajak','penawaran_product.total','product.name','penawaran_product.quantity','penawaran_product.net_cost','penawaran_product.price','penawaran_product.tabung_id','penawaran_product.quantityterima','tabung_mp.no_tabung');
    $join=array();
    $join[]=array(
      'tablename' => 'product',
      'firsttable'  => 'penawaran_product.product_id',
      'secondtable' => 'product.product_id'
    );

    $leftjoin=array();
    $leftjoin[]=array(
      'tablename' => 'tabung_mp',
      'firsttable'  => 'penawaran_product.tabung_id',
      'secondtable' => 'tabung_mp.id'
    );
    $leftjoin[]=array(
      'tablename' => 'satuan',
      'firsttable'  => 'product.satuan',
      'secondtable' => 'satuan.id'
    );

    return $this->db->alljoins('penawaran_product',$column,$join,$leftjoin,$where,array(),0,null);
	}


  public function getListDetail($where,$order,$limit=0,$offset=null){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('penawaran.id as sales_order_id','penawaran.no_so','gudang.nama as namagudang','penawaran.date_added','customer.name as namacustomer','customer.email','customer.telephone','penawaran_product.product_id','penawaran_product.id','penawaran_product.diskon','penawaran_product.pajak','penawaran_product.total','product.name as namaproduct','penawaran_product.quantity','penawaran_product.quantityterima','penawaran_product.status_pengiriman','penawaran_product.net_cost','penawaran_product.price','penawaran_product.pajak');
    $join=array();
    $join[]=array(
      'tablename' => 'product',
      'firsttable'  => 'penawaran_product.product_id',
      'secondtable' => 'product.product_id'
    );
    $join[]=array(
      'tablename' => 'penawaran',
      'firsttable'  => 'penawaran_product.sales_order_id',
      'secondtable' => 'penawaran.id'
    );
    $join[]=array(
      'tablename' => 'customer',
      'firsttable'  => 'penawaran.customer_id',
      'secondtable' => 'customer.customer_id'
    );
    $join[]=array(
      'tablename' => 'gudang',
      'firsttable'  => 'penawaran.gudang_id',
      'secondtable' => 'gudang.gudang_id'
    );

    return $this->db->alljoin('penawaran_product',$column,$join,$where,$order,$limit,$offset);
	}
  public function getTotalListDetail($where){
		//return $this->db->all('penjualan_toko_product',$where);
    $column=array('penawaran.id as sales_order_id','penawaran.no_so','gudang.nama as namagudang','penawaran.date_added','customer.name as namacustomer','customer.email','customer.telephone','penawaran_product.product_id','penawaran_product.id','penawaran_product.diskon','penawaran_product.pajak','penawaran_product.total','product.name as namaproduct','penawaran_product.quantity','penawaran_product.quantityterima','penawaran_product.status_pengiriman','penawaran_product.net_cost','penawaran_product.price');
    $join=array();
    $join[]=array(
      'tablename' => 'product',
      'firsttable'  => 'penawaran_product.product_id',
      'secondtable' => 'product.product_id'
    );
    $join[]=array(
      'tablename' => 'penawaran',
      'firsttable'  => 'penawaran_product.sales_order_id',
      'secondtable' => 'penawaran.id'
    );
    $join[]=array(
      'tablename' => 'customer',
      'firsttable'  => 'penawaran.customer_id',
      'secondtable' => 'customer.customer_id'
    );
    $join[]=array(
      'tablename' => 'gudang',
      'firsttable'  => 'penawaran.gudang_id',
      'secondtable' => 'gudang.gudang_id'
    );
    $total=$this->db->alljoin('penawaran_product',$column,$join,$where,array(),0,null);
    return count($total);
	}
  public function getPenjualanProduct($where){
		return $this->db->first('penawaran_product',$where);
	}
	public function getPenjualans($column=array(),$join=array(),$where=array(),$order,$limit,$offset){
		return $this->db->alljoin('penawaran',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where){
		return $this->db->count('penawaran',$where);
	}


}
?>
