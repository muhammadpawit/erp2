<?php
class ModelGudangTransferitem extends Model {
	
	// baru 12 November 2019
	public function cekgudang($gudang_id){
		$g = $this->db->query("SELECT supplier from gudang where gudang_id='$gudang_id' ");
		$data = $g->row;
		echo $data['supplier'];
		
	}
	
	// end baru
  /*
  Status
  0. belum diterima
  1. sudah diterima
  2. terdapat selisih
  3. dibatalkan
  */
  public function getTransferItemCetak($order_id){
    $this->load->model('gudang/product');
    $sql="select t.order_id,t.invoice_no,t.date_added,t.status,t.total,t.totalterima,t.qtykirim,t.qtyterima,t.tujuan,t.tglterima,g.nama as asal,tj.nama as gudang_tujuan,t.keterangan,t.gudang_asal from ".DB_PREFIX."transfer_item t LEFT JOIN ".DB_PREFIX."gudang g ON(t.gudang_asal=g.gudang_id)  LEFT JOIN ".DB_PREFIX."gudang tj ON(t.tujuan=tj.gudang_id) WHERE order_id='".$order_id."'";
    $res=$this->db->query($sql);

    $transfer=array();
    $prods=$this->getMainProduct($order_id,$res->row['gudang_asal']);
    $products=array();
    foreach($prods as $p){
      $products[]=array(
        'product_id'  => $p['product_id'],
        'name'  => $p['name'],
        'rak' => $p['rak'],
        'harga_jual'  => $p['harga_jual'],
        'quantity'  => $p['quantity'],
        //'options' => $this->getOptions($p['product_id'],$order_id)
      );
    }
    $transfer=array(
      'detail'  => $res->row,
      'products'  => $products
    );
    return $transfer;
    //get product id dan total
  }
  public function getMainProduct($order_id,$gudang_id){
    $this->load->model('gudang/product');
    $m=$this->db->query("SELECT pc.product_id,p.barcode,p.name,SUM(pc.quantity) as quantity,pc.harga_jual from ".DB_PREFIX."transfer_item_product pc JOIN ".DB_PREFIX."product p ON(pc.product_id=p.product_id) JOIN ".DB_PREFIX."product_gudang pg ON(pc.product_id=pg.product_id) WHERE order_id='".$order_id."' AND pg.gudang_id=".$gudang_id." GROUP BY pc.product_id,p.name,pc.harga_jual,p.barcode ORDER BY pc.product_id ASC ");
    return $m->rows;
  }

  public function getOptions($id,$order_id){
    $this->load->model('gudang/product');
    $m=$this->db->query("SELECT pc.product_option_id,pc.quantity,pc.harga_jual,po.name  from ".DB_PREFIX."transfer_item_product pc JOIN ".DB_PREFIX."product_option pt ON(pc.product_option_id=pt.product_option_id) JOIN ".DB_PREFIX."product_options po ON (pt.product_options_id = po.product_options_id) WHERE order_id='".$order_id."' AND pc.product_id='".$id."' ");
    return $m->rows;
  }
  public function getTransferitem($order_id){
    $this->load->model('gudang/product');
    $sql="select t.order_id,t.invoice_no,t.date_added,t.status,t.total,t.totalterima,t.qtykirim,t.qtyterima,t.tujuan,t.tglterima,g.nama as asal,tj.nama as gudang_tujuan,t.keterangan,t.gudang_asal,t.* from ".DB_PREFIX."transfer_item t LEFT JOIN ".DB_PREFIX."gudang g ON(t.gudang_asal=g.gudang_id)  LEFT JOIN ".DB_PREFIX."gudang tj ON(t.tujuan=tj.gudang_id) WHERE order_id='".$order_id."'";
    $res=$this->db->query($sql);

    $transfer=array();

    $this->load->model('gudang/product');
    //product
    $sql2="SELECT pc.order_product_id,pc.product_id,pc.product_option_id,p.name,pc.status,pc.quantity,pc.quantity_actual,pc.harga_jual from ".DB_PREFIX."transfer_item_product pc JOIN ".DB_PREFIX."product p ON(pc.product_id=p.product_id) WHERE order_id='".$order_id."' ORDER BY pc.product_id ASC";
    $resp=$this->db->query($sql2);

    $prod=array();
    foreach($resp->rows as $r){
      if(!empty($r['product_option_id'])){
          $opt=$this->model_gudang_product->getOptionProduct($r['product_id'],$res->row['gudang_asal'],$r['product_option_id']);
      }
      else{
          $opt='';
          $opt['name']='';
      }
      $det=$this->model_gudang_product->getProductGudangT($r['product_id'],$res->row['gudang_asal']);
      $prod[]=array(
        'order_product_id'  => $r['order_product_id'],
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

  public function getTransferItemProduct($order_product_id){
    $this->load->model('gudang/product');
    $this->load->model('catalog/product');
    //product
    $sql2="SELECT pc.product_id,pc.product_option_id,p.name,pc.status,pc.quantity,pc.quantity_actual,pc.harga_jual,pc.price from ".DB_PREFIX."transfer_item_product pc LEFT JOIN ".DB_PREFIX."product p ON(pc.product_id=p.product_id) WHERE order_product_id='".$order_product_id."' ORDER BY pc.product_id,pc.product_option_id";
    $resp=$this->db->query($sql2);
    $r=$resp->row;
    $prod=array();
    if(!empty($r['product_option_id'])){
        $opt=$this->model_catalog_product->getProductOption($r['product_option_id']);
    }
    else{
        $opt='';
        $opt['name']='';
    }
    //$det=$this->model_gudang_product->getProductGudangT($r['product_id'],$res->row['gudang_asal']);
    $prod=array(
      'product_id'  => $r['product_id'],
      'product_option_id' => $r['product_option_id'],
      'name'  => $r['name'],
      'status'  => $r['status'],
      'quantity'  => $r['quantity'],
      'quantity_actual' => $r['quantity_actual'],
      'harga_jual' => $r['harga_jual'],
      'price' => $r['price']
    );
    return $prod;
  }

  public function getTransferitems($data=array(),$permission=false){
      $sql="select t.order_id,t.invoice_no,t.date_added,t.status,t.total,t.totalterima,t.qtykirim,t.qtyterima,g.nama as asal,tj.nama as gudang_tujuan,t.keterangan,t.* from ".DB_PREFIX."transfer_item t LEFT JOIN ".DB_PREFIX."gudang g ON(t.gudang_asal=g.gudang_id)  LEFT JOIN ".DB_PREFIX."gudang tj ON(t.tujuan=tj.gudang_id) ";
			if (!empty($data['filter_gudang_asal'])) {
				$sql .= " WHERE t.gudang_asal= '" . $data['filter_gudang_asal'] . "'";
			}
			else{
				if($permission){
					$sql .=" WHERE t.gudang_asal IN(SELECT gudang_id FROM ".DB_PREFIX."user_gudang WHERE user_id='".$this->user->getId()."' )";
				}
			}
      if (!empty($data['filter_tujuan'])) {
				$sql .= " AND t.tujuan= '" . $data['filter_tujuan'] . "'";
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


			$sql .=" ORDER BY t.order_id DESC";
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
    if($this->user->getUsername()=="pawits"){
		return $sql;
	}else{
		return $query->rows;
	}

  }

	public function getTotalTransferitems($data=array(),$permission=false){
    $sql="select COUNT(*) as total from ".DB_PREFIX."transfer_item t LEFT JOIN ".DB_PREFIX."gudang g ON(t.gudang_asal=g.gudang_id)  LEFT JOIN ".DB_PREFIX."gudang tj ON(t.tujuan=tj.gudang_id) ";
    if (!empty($data['filter_gudang_asal'])) {
      $sql .= " WHERE t.gudang_asal= '" . $data['filter_gudang_asal'] . "'";
    }
    else{
      if($permission){
        $sql .=" WHERE t.gudang_asal IN(SELECT gudang_id FROM ".DB_PREFIX."user_gudang WHERE user_id='".$this->user->getId()."' )";
      }
    }
    if (!empty($data['filter_tujuan'])) {
      $sql .= " AND t.tujuan= '" . $data['filter_tujuan'] . "'";
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
		$alamatexpedisi=null;
		$nopol=null;
		$no_po=null;
		if(!empty($pro['alamatexpedisi'])){
			$alamatexpedisi = $pro['alamatexpedisi'];
		}
		
		if(!empty($pro['nopol'])){
			$nopol = $pro['nopol'];
		}
		if(!empty($pro['no_po'])){
			$no_po = $pro['no_po'];
		}
		
		$trans=array(
		  'date_added' => date('Y-m-d',time()),
		  'status'  => 0,
		  'total' => 0,
		  'gudang_asal' => $pro['gudang_asal'],
		  'tujuan'  => $pro['tujuan'],
		  'keterangan'  => $this->db->escape($pro['keterangan']),
		  'alamatexpedisi' => $alamatexpedisi,
		  'nopol' => $nopol,
		  'no_po' => $no_po,
		  'user_id' => $this->user->getId(),
		  'totalterima'=>0,
		  'qtykirim'  => 0,
		  'qtyterima' =>0,
		  //'jenis' => $pro['jenis'],
		  'invoice_no'  =>'in process'
		);

		$this->db->insert('transfer_item',$trans);
		$order_id=$this->db->getLastId();

		$tgl=explode('-',$trans['date_added']);
		$invoice_no='SJ-TI-'.$trans['user_id'].'-'.$tgl[0].'-'.$tgl[1].'-'.$order_id;
		$no_dokumen='TI-'.$trans['user_id'].'-'.$tgl[0].'-'.$tgl[1].'-'.$order_id;

		$total=0;
		$qty=0;
		foreach($pro['product'] as $data){
		$data['quantity'] = $data['qty'];
			if(!empty($data['product_id'])){
  			$data['product_option_id']=0;


  			$transprod=array(
			'order_id'  => $order_id,
  				'product_id'	=> $data['product_id'],
  				'product_option_id'	=> 0,
  				'quantity'	=> $data['qty'],
  				'price'	=> 0,
  				'status'	=> 0,
  				'quantity_actual'	=> 0,
  				'harga_jual'	=> 0
  			);

  			$this->db->insert('transfer_item_product',$transprod);
        $total += $data['harga_jual'] * $data['quantity'];
        $qty += $data['quantity'];

			$prod=$this->model_catalog_product->getProduct($data['product_id']);
			$prodg=$this->model_gudang_product->getProductGudangT($data['product_id'],$pro['gudang_asal']);

      $stokkeluar=$data['quantity'];
      $stokmasuk=0;

      //gudang
			$this->load->model('catalog/gudang');
			$tuj=$this->model_catalog_gudang->getGudang($pro['tujuan']);
      		$gud=$this->model_catalog_gudang->getGudang($pro['gudang_asal']);

			//updateQtyOption($product_option_id,$product_id,$gudang_id,$quantity,$jenis)
			if(!empty($data['product_option_id'])){
				$opt=$this->model_gudang_product->getOptionProduct($data['product_id'],$pro['gudang_asal'],$data['product_option_id']);

				$update=$this->model_gudang_product->updateQtyOption($data['product_option_id'],$data['product_id'],$pro['gudang_asal'],$data['quantity'],2);

				$kartustok=array(
					'product_id'	=> $data['product_id'],
					'product_option_name'	=> $this->db->escape($opt['name']),
					'product_option_id'	=> $data['product_option_id'],
					'tgl'	=> date('Y-m-d H:i:s'),
					'stokmasuk'	=> $stokmasuk,
					'stokkeluar'	=> $stokkeluar,
					'ket'	=> 'Transfer item ke '.$tuj['nama'].' oleh user '.$this->user->getName(),
					'saldo'	=> $update,
					'quantityawal'	=> $opt['quantity'],
					'invoice'	=> $invoice_no,
					'gudang_id'	=> $pro['gudang_asal'],
					'type'	=> 3
				);

				$this->model_gudang_kartustok->addKartuStokOption($kartustok);
			}else{
				$gupdate=$this->model_gudang_product->updateQty($data['product_id'],$pro['gudang_asal'],$data['quantity'],2);

			}

			$curprodg=$this->model_gudang_product->getProductGudangT($data['product_id'],$pro['gudang_asal']);
			$gkartustok=array(
					'product_id'	=> $data['product_id'],
					'product_name'	=> $this->db->escape($prod['name']),
					'tgl'	=> date('Y-m-d H:i:s'),
					'stokmasuk'	=> $stokmasuk,
					'stokkeluar'	=> $stokkeluar,
					'ket'	=> 'Transfer item ke '.$tuj['nama'].' oleh user '.$this->user->getName(),
					'saldo'	=> $curprodg['quantity'],
					'quantityawal'	=> $prodg['quantity'],
					'invoice'	=> $invoice_no,
					'gudang_id'	=> $pro['gudang_asal'],
					'type'	=> 3
				);
			$this->model_gudang_kartustok->addKartustok($gkartustok);
		}
		}

    $this->db->query("UPDATE ".DB_PREFIX."transfer_item SET no_dokumen='".$no_dokumen."',invoice_no='".$invoice_no."',total='".$total."',qtykirim='".$qty."' WHERE order_id='".$order_id."'");

    $act=array(
      'activity'	=> 'Input transfer item  dari gudang '.$gud['nama'].' ke gudang '.$tuj['nama'].', nomor  '.$invoice_no,
      'menu'	=> 'Transfer Item'
    );
    $this->user->addUserActivity($act);
	}

  public function cancelTransfer($order_id){
    $this->load->model('catalog/product');
    $this->load->model('gudang/product');
    $this->load->model('gudang/kartustok');
    $this->load->model('catalog/gudang');

    $trans=$this->getTransferitem($order_id);
    $this->db->query("UPDATE ".DB_PREFIX."transfer_item SET status=3 WHERE order_id='".$order_id."'");
    $this->db->query("UPDATE ".DB_PREFIX."transfer_item_product SET status=3 WHERE order_id='".$order_id."'");

    $pro=$trans['detail'];
    //gudang

    $tuj=$this->model_catalog_gudang->getGudang($pro['tujuan']);
    $gud=$this->model_catalog_gudang->getGudang($pro['gudang_asal']);

    foreach($trans['products'] as $data){
			if(!empty($data['product_option_id'])){
  				$data['product_option_id']=$data['product_option_id'];
  			}
  			else{
  				$data['product_option_id']=0;
  			}

	   $prod=$this->model_catalog_product->getProduct($data['product_id']);
			$prodg=$this->model_gudang_product->getProductGudangT($data['product_id'],$pro['gudang_asal']);

      $stokmasuk=$data['quantity'];
      $stokkeluar=0;


			//updateQtyOption($product_option_id,$product_id,$gudang_id,$quantity,$jenis)
			if(!empty($data['product_option_id'])){
				$opt=$this->model_gudang_product->getOptionProduct($data['product_id'],$pro['gudang_asal'],$data['product_option_id']);

				$update=$this->model_gudang_product->updateQtyOption($data['product_option_id'],$data['product_id'],$pro['gudang_asal'],$data['quantity'],1);

				$kartustok=array(
					'product_id'	=> $data['product_id'],
					'product_option_name'	=> $this->db->escape($opt['name']),
					'product_option_id'	=> $data['product_option_id'],
					'tgl'	=> date('Y-m-d H:i:s'),
					'stokmasuk'	=> $stokmasuk,
					'stokkeluar'	=> $stokkeluar,
					'ket'	=> 'Pembatalan Transfer item ke '.$tuj['nama'].' oleh user '.$this->user->getName(),
					'saldo'	=> $update,
					'quantityawal'	=> $opt['quantity'],
					'invoice'	=> $trans['detail']['invoice_no'],
					'gudang_id'	=> $pro['gudang_asal'],
					'type'	=> 3
				);

				$this->model_gudang_kartustok->addKartuStokOption($kartustok);
			}else{
				$gupdate=$this->model_gudang_product->updateQty($data['product_id'],$pro['gudang_asal'],$data['quantity'],1);

			}

			$curprodg=$this->model_gudang_product->getProductGudangT($data['product_id'],$pro['gudang_asal']);
			$gkartustok=array(
					'product_id'	=> $data['product_id'],
					'product_name'	=> $this->db->escape($prod['name']),
					'tgl'	=> date('Y-m-d H:i:s'),
					'stokmasuk'	=> $stokmasuk,
					'stokkeluar'	=> $stokkeluar,
					'ket'	=> 'Pembatalan Transfer item ke '.$tuj['nama'].' oleh user '.$this->user->getName(),
					'saldo'	=> $curprodg['quantity'],
					'quantityawal'	=> $prodg['quantity'],
					'invoice'	=> $pro['invoice_no'],
					'gudang_id'	=> $pro['gudang_asal'],
					'type'	=> 3
				);
			$this->model_gudang_kartustok->addKartustok($gkartustok);

		}
    $act=array(
      'activity'	=> 'Pembatalan transfer item  dari gudang '.$gud['nama'].' ke gudang '.$tuj['nama'].', nomor  '.$pro['invoice_no'],
      'menu'	=> 'Transfer Item'
    );
    $this->user->addUserActivity($act);
  }

  public function terimaTransfer($order_id,$data){
    $this->load->model('catalog/product');
    $this->load->model('gudang/product');
    $this->load->model('gudang/kartustok');
    $this->load->model('catalog/gudang');

    $trans=$this->getTransferitem($order_id);
    //$this->db->query("UPDATE ".DB_PREFIX."transfer_item SET status=3 WHERE order_id='".$order_id."'");
    //$this->db->query("UPDATE ".DB_PREFIX."transfer_item_product SET status=3 WHERE order_id='".$order_id."'");

    $pro=$trans['detail'];
    $total=0;
    $totalqty=0;
    $status=1;
    $globalstatus=1;
	$no_po = empty($pro['no_po'])?'':$pro['no_po'];

    $tuj=$this->model_catalog_gudang->getGudang($pro['tujuan']);
    $gud=$this->model_catalog_gudang->getGudang($pro['gudang_asal']);

    foreach($data['products'] as $d){
		$pros=$this->getTransferItemProduct($d['order_product_id']);
		if(!empty($pros['product_id']) & !empty($d['quantity_actual'])){
			//update tabel transfer
			if($d['quantity_actual']+$$pros['quantity_actual'] == $pros['quantity']){
				$status = 1;
			}else{
				$status = 2;
				$globalstatus=2;
			}
			$this->db->query("UPDATE ".DB_PREFIX."transfer_item_product SET quantity_actual='".$d['quantity_actual']."',status ='".$status."' WHERE order_product_id='".$d['order_product_id']."' ");
			$total += $d['quantity_actual'] * $pros['harga_jual'];
			$totalqty += $d['quantity_actual'];
			if($d['quantity_actual'] > 0){
				$stokmasuk=$d['quantity_actual'];
				$stokkeluar=0;
				$prodg=$this->model_gudang_product->getProductGudangT($pros['product_id'],$pro['tujuan']);
				if(empty($prodg)){
					$productkosong=array(
							'gudang_id'	=> $pro['tujuan'],
							'product_id'	=> $pros['product_id'],
							'qty'	=> 0,
							'status'	=>1,
							'net_cost'	=> 0,
							'rak'	=> '',
							'link_non_web'	=>'',
							'name'=> $pros['name'],
							'date_added'	=>date('Y-m-d H:i:s',time())
						);
					$this->model_gudang_product->addStokAwal($productkosong);
					$prodg=$this->model_gudang_product->getProductGudangT($pros['product_id'],$pro['tujuan']);
				}
				if(!empty($pros['product_option_id'])){
					$opt=$this->model_gudang_product->getOptionProduct($pros['product_id'],$pro['tujuan'],$pros['product_option_id']);
					if(empty($opt)){
						$productoption=array(
								'gudang_id'	=> $pro['tujuan'],
								'product_id'	=> $pros['product_id'],
								'qty'	=> 0,
								'status'	=>1,
								'product_option_id'	=> $pros['product_option_id']
							);
						$this->model_gudang_product->addOptionAwal($productoption);
						$opt=$this->model_gudang_product->getOptionProduct($pros['product_id'],$pro['tujuan'],$pros['product_option_id']);
					}
					$update=$this->model_gudang_product->updateQtyOption($pros['product_option_id'],$pros['product_id'],$pro['tujuan'],$d['quantity_actual'],1);
						$kartustok=array(
							'product_id'	=> $pros['product_id'],
							'product_option_name'	=> $this->db->escape($opt['name']),
							'product_option_id'	=> $pros['product_option_id'],
							'tgl'	=> date('Y-m-d H:i:s'),
							'stokmasuk'	=> $stokmasuk,
							'stokkeluar'	=> $stokkeluar,
							'ket'	=> "Penerimaan Transfer item dari ".$gud['nama']." oleh user ".$this->user->getName()." dg No.PO ".$no_po." ",
							'saldo'	=> $update,
							'quantityawal'	=> $opt['quantity'],
							'invoice'	=> $trans['detail']['invoice_no'],
							'gudang_id'	=> $pro['tujuan'],
							'type'	=> 4
						);
						$this->model_gudang_kartustok->addKartuStokOption($kartustok);
					}else{
						$gupdate=$this->model_gudang_product->updateQty($pros['product_id'],$pro['tujuan'],$d['quantity_actual'],1);

					}
					//netcost
					$asal=$this->model_gudang_product->getProductGudangT($d['product_id'],$pro['gudang_asal']);
					if($prodg['quantity'] > 0){
						if($prodg['net_cost'] > 0){
							$netcost=(($d['quantity_actual'] * $asal['net_cost'])+($prodg['quantity']*$prodg['net_cost']))/($d['quantity_actual']+$prodg['quantity']);
						}else{
							$netcost=(($d['quantity_actual'] * $asal['net_cost']))/($d['quantity_actual']);
						}
					}else{
						$netcost=(($d['quantity_actual'] * $asal['net_cost']))/($d['quantity_actual']);
					}
					$this->model_gudang_product->updateNetCost($d['product_id'],$pro['tujuan'],$netcost);
					$curprodg=$this->model_gudang_product->getProductGudangT($pros['product_id'],$pro['tujuan']);
					$gkartustok=array(
							'product_id'	=> $pros['product_id'],
							'product_name'	=> $this->db->escape($pros['name']),
							'tgl'	=> date('Y-m-d H:i:s'),
							'stokmasuk'	=> $stokmasuk,
							'stokkeluar'	=> $stokkeluar,
							'ket'	=> 'Penerimaan Transfer item dari '.$gud['nama'].' oleh user '.$this->user->getName(),
							'saldo'	=> $curprodg['quantity'],
							'quantityawal'	=> $prodg['quantity'],
							'invoice'	=> $pro['invoice_no'],
							'gudang_id'	=> $pro['tujuan'],
							'type'	=> 4
						);
					$this->model_gudang_kartustok->addKartustok($gkartustok);
			}
		}
	}

    $this->db->query("UPDATE ".DB_PREFIX."transfer_item SET no_terimadokumen='TTI-".date('Y').'-'.date('m').'-'.$this->user->getId().'-'.$order_id."',status ='".$globalstatus."',qtyterima ='".$totalqty."',totalterima='".$total."',tglterima=NOW() WHERE order_id='".$order_id."' ");
    $act=array(
      'activity'	=> 'Penerimaan transfer item  dari gudang '.$gud['nama'].' ke gudang '.$tuj['nama'].', nomor  '.$pro['invoice_no'],
      'menu'	=> 'Transfer Item'
    );
    $this->user->addUserActivity($act);
  }

  public function selisih($order_id,$datas){
    $this->load->model('catalog/product');
    $this->load->model('gudang/product');
    $this->load->model('gudang/kartustok');
    $this->load->model('catalog/gudang');

    $trans=$this->getTransferitem($order_id);
    $this->db->query("UPDATE ".DB_PREFIX."transfer_item SET status=1,qtykirim=qtyterima,total=totalterima WHERE order_id='".$order_id."'");
    $this->db->query("UPDATE ".DB_PREFIX."transfer_item_product SET status=1 WHERE order_id='".$order_id."'");

    $pro=$trans['detail'];
    //gudang

    $tuj=$this->model_catalog_gudang->getGudang($pro['tujuan']);
    $gud=$this->model_catalog_gudang->getGudang($pro['gudang_asal']);

    foreach($datas['products'] as $data){
      $pros=$this->getTransferItemProduct($data['order_product_id']);
      $selisih = $pros['quantity'] - $pros['quantity_actual'];
      if($data['prosesselisih'] == 1){
        $prod=$this->model_catalog_product->getProduct($pros['product_id']);
   			$prodg=$this->model_gudang_product->getProductGudangT($pros['product_id'],$pro['gudang_asal']);

         $stokmasuk=$selisih;
         $stokkeluar=0;

   			//updateQtyOption($product_option_id,$product_id,$gudang_id,$quantity,$jenis)
   			if(!empty($pros['product_option_id'])){
   				$opt=$this->model_gudang_product->getOptionProduct($pros['product_id'],$pro['gudang_asal'],$pros['product_option_id']);

   				$update=$this->model_gudang_product->updateQtyOption($pros['product_option_id'],$pros['product_id'],$pro['gudang_asal'],$selisih,1);

   				$kartustok=array(
   					'product_id'	=> $pros['product_id'],
   					'product_option_name'	=> $this->db->escape($opt['name']),
   					'product_option_id'	=> $pros['product_option_id'],
   					'tgl'	=> date('Y-m-d H:i:s'),
   					'stokmasuk'	=> $stokmasuk,
   					'stokkeluar'	=> $stokkeluar,
   					'ket'	=> 'Pemrosesan selisih Transfer item ke '.$tuj['nama'].' oleh user '.$this->user->getName(),
   					'saldo'	=> $update,
   					'quantityawal'	=> $opt['quantity'],
   					'invoice'	=> $trans['detail']['invoice_no'],
   					'gudang_id'	=> $pro['gudang_asal'],
   					'type'	=> 3
   				);

   				$this->model_gudang_kartustok->addKartuStokOption($kartustok);
   			}else{
   				$gupdate=$this->model_gudang_product->updateQty($pros['product_id'],$pro['gudang_asal'],$selisih,1);

   			}

   			$curprodg=$this->model_gudang_product->getProductGudangT($pros['product_id'],$pro['gudang_asal']);
   			$gkartustok=array(
   					'product_id'	=> $pros['product_id'],
   					'product_name'	=> $this->db->escape($prod['name']),
   					'tgl'	=> date('Y-m-d H:i:s'),
   					'stokmasuk'	=> $stokmasuk,
   					'stokkeluar'	=> $stokkeluar,
   					'ket'	=> 'Pemrosesan Selisih Transfer item ke '.$tuj['nama'].' oleh user '.$this->user->getName(),
   					'saldo'	=> $curprodg['quantity'],
   					'quantityawal'	=> $prodg['quantity'],
   					'invoice'	=> $pro['invoice_no'],
   					'gudang_id'	=> $pro['gudang_asal'],
   					'type'	=> 3
   				);
   			$this->model_gudang_kartustok->addKartustok($gkartustok);
      }
      if($data['prosesselisih'] == 2){
        $prod=$this->model_catalog_product->getProduct($pros['product_id']);
   			$prodg=$this->model_gudang_product->getProductGudangT($pros['product_id'],$pro['gudang_asal']);
        $hilang['gudang_id']= $pro['gudang_asal'];
        $hilang['product'][]=array(
  				'product_id'	=> $pros['product_id'],
  				'product_otion'	=> $pros['product_option_id'],
  				'qty'	=> $selisih,
  				'date_added'	=> date('Y-m-d'),
  				'gudang_id'	=> $pro['gudang_asal'],
  				'price'	=> $pros['harga_jual']
  			);
        $this->model_gudang_product->addProductHilang($hilang);
      }

		}
    $act=array(
      'activity'	=> 'Pemrosesan selisih transfer item  dari gudang '.$gud['nama'].' ke gudang '.$tuj['nama'].', nomor  '.$pro['invoice_no'],
      'menu'	=> 'Transfer Item'
    );
    $this->user->addUserActivity($act);
  }

}
?>
