<?php
class ModelGudangPembelian extends Model {
  /*
  Status
  0. belum diterima
  1. sudah diterima
  2. terdapat selisih
  3. dibatalkan
  */
  public function getTransferitem($order_id){
    $this->load->model('gudang/product');
    $sql="select t.order_id,t.invoice_no,t.date_added,t.status,t.total,t.totalterima,t.qtykirim,t.qtyterima,g.nama as asal,t.supplier,t.email,t.gudang_id from ".DB_PREFIX."pembelian t LEFT JOIN ".DB_PREFIX."gudang g ON(t.gudang_id=g.gudang_id) WHERE order_id='".$order_id."'";
    $res=$this->db->query($sql);

    $transfer=array();

    $this->load->model('gudang/product');
    //product
    $sql2="SELECT pc.product_id,pc.product_option_id,p.name,pc.status,pc.quantity,pc.quantity_actual,pc.harga_jual from ".DB_PREFIX."pembelian_product pc LEFT JOIN ".DB_PREFIX."product p ON(pc.product_id=p.product_id) WHERE order_id='".$order_id."'";
    $resp=$this->db->query($sql2);

    $prod=array();
    foreach($resp->rows as $r){
      if(!empty($r['product_option_id'])){
          $opt=$this->model_gudang_product->getOptionProduct($r['product_id'],$res->row['gudang_id'],$r['product_option_id']);
      }
      else{
          $opt='';
          $opt['name']='';
      }
      $det=$this->model_gudang_product->getProductGudangT($r['product_id'],$res->row['gudang_id']);
      $prod[]=array(
        'product_id'  => $r['product_id'],
        'product_option_id' => $r['product_option_id'],
        'name'  => $r['name'],
        'status'  => $r['status'],
        'quantity'  => $r['quantity'],
        'quantity_actual' => $r['quantity_actual'],
        'option'  =>$opt['name'],
        'rak' => $det['rak'],
        'harga_jual' => $r['harga_jual']
      );
    }
    $transfer=array(
      'detail'  => $res->row,
      'products'  => $prod
    );
    return $transfer;
  }
  public function getTransferitems($data=array(),$permission=false){
      $sql="select t.order_id,t.invoice_no,t.date_added,t.supplier,t.email,t.status,t.total,t.totalterima,t.qtykirim,t.qtyterima,g.nama as asal,t.keterangan from ".DB_PREFIX."pembelian t LEFT JOIN ".DB_PREFIX."gudang g ON(t.gudang_id=g.gudang_id) ";
			if (!empty($data['filter_gudang_id'])) {
				$sql .= " WHERE t.gudang_id= '" . $data['filter_gudang_id'] . "'";
			}
			else{
				if($permission){
					$sql .=" WHERE t.gudang_id IN(SELECT gudang_id FROM ".DB_PREFIX."user_gudang WHERE user_id='".$this->user->getId()."' )";
				}
			}
      if (!empty($data['filter_status'])) {
				$sql .= " AND t.status= '" . $data['filter_status'] . "'";
			}
      if (!empty($data['filter_invoice_no'])) {
				$sql .= " AND lower(t.invoice_no)= '" . $this->db->escape(utf8_strtolower($data['filter_invoice_no'])) . "'";
			}
      if (!empty($data['filter_date_start'])) {
				$sql .= " AND t.date_added >= '" . $this->db->escape($data['filter_date_start']) . "'";
			}

			if (!empty($data['filter_date_end'])) {
				$sql .= " AND t.date_added <= '" . $this->db->escape($data['filter_date_end']) . "'";
			}


			$sql .=" ORDER BY t.date_added DESC";
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

	public function getTotalTransferitems($data=array(),$permission=false){
    $sql="select COUNT(*) as total from ".DB_PREFIX."pembelian t LEFT JOIN ".DB_PREFIX."gudang g ON(t.gudang_id=g.gudang_id) ";
    if (!empty($data['filter_gudang_id'])) {
      $sql .= " WHERE t.gudang_id= '" . $data['filter_gudang_id'] . "'";
    }
    else{
      if($permission){
        $sql .=" WHERE t.gudang_id IN(SELECT gudang_id FROM ".DB_PREFIX."user_gudang WHERE user_id='".$this->user->getId()."' )";
      }
    }
    if (!empty($data['filter_status'])) {
      $sql .= " AND t.status= '" . $data['filter_status'] . "'";
    }
    if (!empty($data['filter_invoice_no'])) {
      $sql .= " AND lower(t.invoice_no)= '" . $this->db->escape(utf8_strtolower($data['filter_invoice_no'])) . "'";
    }
    if (!empty($data['filter_date_start'])) {
      $sql .= " AND t.date_added >= '" . $this->db->escape($data['filter_date_start']) . "'";
    }

    if (!empty($data['filter_date_end'])) {
      $sql .= " AND t.date_added <= '" . $this->db->escape($data['filter_date_end']) . "'";
    }

    $query = $this->db->query($sql);
    return $query->row['total'];

  }



	public function addTransferitem($pro){
		$this->load->model('catalog/product');
    $this->load->model('gudang/product');
		$this->load->model('gudang/kartustok');

    $trans=array(
      'date_added' => date('Y-m-d',time()),
      'status'  => 0,
      'total' => 0,
      'gudang_id' => $pro['gudang_id'],
      'keterangan'  => $this->db->escape($pro['keterangan']),
      'supplier'  => $this->db->escape($pro['supplier']),
      'email' => $this->db->escape($pro['email']),
      'user_id' => $this->user->getId(),
      'totalterima'=>0,
      'qtykirim'  => 0,
      'qtyterima' =>0,
      'invoice_no'  =>$pro['invoice_no']
    );

    $this->db->insert('pembelian',$trans);
    $order_id=$this->db->getLastId();

    $tgl=explode('-',$trans['date_added']);
  //  $invoice_no='SJ-TI-'.$trans['user_id'].'-'.$tgl[0].'-'.$tgl[1].'-'.$order_id;

    $total=0;
    $qty=0;
		foreach($pro['product'] as $data){
      $data['quantity'] = $data['qty'];
			if(!empty($data['product_id'])){
  			if(!empty($data['product_otion'])){
  				$data['product_option_id']=$data['product_otion'];
  			}
  			else{
  				$data['product_option_id']=0;
  			}


  			$transprod=array(
          'order_id'  => $order_id,
  				'product_id'	=> $data['product_id'],
  				'product_option_id'	=> $data['product_option_id'],
  				'quantity'	=> $data['qty'],
  				'price'	=> $data['price'],
  				'status'	=> 0,
  				'quantity_actual'	=> 0

  			);

  			$this->db->insert('pembelian_product',$transprod);
        $total += $data['harga_jual'] * $data['quantity'];
        $qty += $data['quantity'];

			$prod=$this->model_catalog_product->getProduct($data['product_id']);
			$prodg=$this->model_gudang_product->getProductGudangT($data['product_id'],$pro['gudang_id']);



		}
		}

    $this->db->query("UPDATE ".DB_PREFIX."pembelian SET total='".$total."',qtykirim='".$qty."' WHERE order_id='".$order_id."'");

    $act=array(
      'activity'	=> 'Input pembelian nomor  '.$invoice_no,
      'menu'	=> 'Pembelian'
    );
    $this->user->addUserActivity($act);
	}



  public function terimaTransfer($order_id,$data){
    $this->load->model('catalog/product');
    $this->load->model('gudang/product');
    $this->load->model('gudang/kartustok');
    $this->load->model('catalog/gudang');

    $trans=$this->getTransferitem($order_id);
    //$this->db->query("UPDATE ".DB_PREFIX."pembelian SET status=3 WHERE order_id='".$order_id."'");
    //$this->db->query("UPDATE ".DB_PREFIX."pembelian_product SET status=3 WHERE order_id='".$order_id."'");

    $pro=$trans['detail'];
    foreach($data['products'] as $data){
			if(!empty($data['product_option_id'])){
  				$data['product_option_id']=$data['product_option_id'];
  			}
  			else{
  				$data['product_option_id']=0;
  			}

	   $prod=$this->model_catalog_product->getProduct($data['product_id']);
			$prodg=$this->model_gudang_product->getProductGudangT($data['product_id'],$pro['tujuan']);
      if(empty($prodg)){
        $pr=array(
          'gudang_id'	=> $pro['tujuan'],
    			'product_id'	=> $data['product_id'],
    			'quantity'	=> 0,
    			'status'	=>1,
    			'net_cost'	=> 0,
    			'rak'	=> '',
    			'link_non_web'	=>'',
    			'date_added'	=>date('Y-m-d H:i:s',time())
        );
        $prodg=$this->model_gudang_product->getProductGudangT($data['product_id'],$pro['tujuan']);
      }

      $this->model_gudang_product->setNetCost($data['product_id'],$pro['tujuan'],$data['price'],$data['quantity_actual']);

      $stokmasuk=$data['quantity_actual'];
      $stokkeluar=0;

      //gudang

			$tuj=$this->model_catalog_gudang->getGudang($pro['tujuan']);
      $gud=$this->model_catalog_gudang->getGudang($pro['gudang_id']);

			//updateQtyOption($product_option_id,$product_id,$gudang_id,$quantity,$jenis)
			if(!empty($data['product_option_id'])){
				$opt=$this->model_gudang_product->getOptionProduct($data['product_id'],$pro['tujuan'],$data['product_option_id']);

        if(empty($opt)){
          $opts=array(
      			'gudang_id'	=> $pro['tujuan'],
      			'product_id'	=> $data['product_id'],
      			'quantity'	=> 0,
      			'status'	=>1,
      			'product_option_id'	=> $data['product_option_id']
      		);
          $this->model_gudang_product->addOptionAwal($opts);
          $opt=$this->model_gudang_product->getOptionProduct($data['product_id'],$pro['tujuan'],$data['product_option_id']);
        }

				$update=$this->model_gudang_product->updateQtyOption($data['product_option_id'],$data['product_id'],$pro['tujuan'],$data['quantity'],1);

				$kartustok=array(
					'product_id'	=> $data['product_id'],
					'product_option_name'	=> $this->db->escape($opt['name']),
					'product_option_id'	=> $data['product_option_id'],
					'tgl'	=> date('Y-m-d H:i:s'),
					'stokmasuk'	=> $stokmasuk,
					'stokkeluar'	=> $stokkeluar,
					'ket'	=> 'Penerimaan Transfer item dari '.$gud['nama'].' oleh user '.$this->user->getNama(),
					'saldo'	=> $update,
					'quantityawal'	=> $opt['quantity'],
					'invoice'	=> $trans['detail']['invoice_no'],
					'gudang_id'	=> $pro['tujuan'],
					'type'	=> 4
				);

				$this->model_gudang_kartustok->addKartuStokOption($kartustok);
			}else{
				$gupdate=$this->model_gudang_product->updateQty($data['product_id'],$pro['tujuan'],$data['quantity'],1);

			}

			$curprodg=$this->model_gudang_product->getProductGudangT($data['product_id'],$pro['tujuan']);
			$gkartustok=array(
					'product_id'	=> $data['product_id'],
					'product_name'	=> $this->db->escape($prod['name']),
					'tgl'	=> date('Y-m-d H:i:s'),
					'stokmasuk'	=> $stokmasuk,
					'stokkeluar'	=> $stokkeluar,
					'ket'	=> 'Penerimaan Transfer item dari '.$gud['nama'].' oleh user '.$this->user->getNama(),
					'saldo'	=> $curprodg['quantity'],
					'quantityawal'	=> $prodg['quantity'],
					'invoice'	=> $pro['invoice_no'],
					'gudang_id'	=> $pro['tujuan'],
					'type'	=> 7
				);
			$this->model_gudang_kartustok->addKartustok($gkartustok);

		}
    $act=array(
      'activity'	=> 'Penerimaan transfer item  dari gudang '.$gud['nama'].' ke gudang '.$tuj['nama'].', nomor  '.$pro['invoice_no'],
      'menu'	=> 'Transfer Item'
    );
    $this->user->addUserActivity($act);
  }

}
?>
