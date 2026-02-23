<?php
class ModelSaleReturnpenjualan extends Model {
  /*
  Status
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
  // baru 11 November 2019
  
  
  public function addPenjualan($data){
	$keterangan='-';
	if(isset($data['keterangan'])){
		$keterangan = empty($data['keterangan'])?$keterangan:$data['keterangan'];
	}
    $this->load->model('sale/customer');
    
    $penj=array(
      'date_added' => isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s'),
      'customer_id'  => $data['customer_id'],
      'status' => 1,
      'hapus' => 0,
      'user_id'  => $this->user->getId(),
      'sub_total' => $data['sub_total'],
      'pajak' => $data['pajak'],
      'total' => $data['total'],
      'totalrefund' => $data['totalrefund'],
      'net_cost'  => $data['net_cost'],
      'keterangan' => $keterangan,
      'gudang_id' => $data['gudang_id'],
      'bank_id' => $data['bank_id']
      //'product'  => json_encode($data['product'])
    );

    /*
    1. belum dikirim
    2. dikirim
    3. sudah dikirim

    1. menunggu pembayaran
    2. dibayar sebagian
    3. lunas

    1. disimpan
    2. diproses
    3. sukses
    4. dibatalkan

    */

    $this->db->insert('return_penjualan',$penj);
    $id=$this->db->getlastId();
    $data['id']=$id;
    $no_invoice="RJ-".$id."-".date('Y')."-".date("m")."-".$this->user->getId();
    $data['no_return']=$no_invoice;
    $this->db->update('return_penjualan',array('no_return' => $no_invoice),array('id' => $id));
    
    $this->load->model('sale/customer');
    $prod=$this->addPenjualanProduct($data);

    $this->load->model('keuangan/jurnal');
    //retur x piutang
    $details=array();
    $details[]=array(
      'ref_akun'  => '4003',
      //'jenis_akun'  => 52,
      'keterangan'  => 'Retur penjualan',
      'kredit' => 0,
      'debet'  => $data['total'] - $data['pajak'],
      'urutan'  => 1,
      'hapus' => 0
    );
    if($data['pajak'] > 0){
      $details[]=array(
        'ref_akun'  =>'2505',
        'kredit' => 0,
        'debet'  => $data['pajak'],
        'urutan'  =>2,
        'keterangan'  => 'PPN Keluaran'
      );
    }
    
    if($prod['piutang'] > 0){
      $details[]=array(
        'ref_akun'  => '1101',
        'keterangan'  => 'Piutang',
        'kredit' => $prod['piutang'],
        'debet'  => 0,
        'urutan'  => 3,
        'hapus' => 0
      );
    }
    if($prod['piutangbelum'] > 0){
      $details[]=array(
        'ref_akun'  => '1102',
        'keterangan'  => 'Piutang Belum Tertagih',
        'kredit' => $prod['piutangbelum'],
        'debet'  => 0,
        'urutan'  => 3,
        'hapus' => 0
      );
    }
   
      $j=array(
        'tanggal' => isset($data['date_added'])?$data['date_added']:date('Y-m-d'),
        'keterangan'  => 'Return Penjualan '.$data['no_return'],
        'details' => $details,
        'hapus' =>0,
        'ref' => $id,
        'linkterkait' =>$data['no_return'],
        'type'  => 19001,
        'idref' => $id,
        'urlref'  =>'sale/returnpenjualan',
        'no_dokumen'  => $no_invoice
      );
      $this->model_keuangan_jurnal->addJurnalUmum($j);

    if($data['totalrefund'] > 0){
      $this->load->model('keuangan/bank');
      $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $data['bank_id']));
      $saldo=$b['saldo'] - $data['totalrefund'];
      $this->model_keuangan_bank->updateBank(array('saldo'  => $saldo),array('id'=> $data['bank_id']));
      $detail=array();
      
      /*piutang x kas/bank */
      $detail[]=array(
        'ref_akun'  => '1101',
        'keterangan'  => $this->db->escape($keterangan),
        'kredit' => 0,
        'debet'  => $data['totalrefund'],
        'urutan'  => 2,
      );
      if($b['hutangprk']==1){
        if($b['saldo'] > 0){
          if($saldo >= 0){
            $detail[]=array(
              'ref_akun'  => $b['rek_parent'],
              'keterangan'  => $this->db->escape($keterangan),
              'kredit' => $data['totalrefund'],
              'debet'  => 0,
              'urutan'  => 2,
            );
          }else{
            //saldo 100 refund 150 berarti bank 100 prk refund-saldo
            $detail[]=array(
              'ref_akun'  => $b['rek_parent'],
              'keterangan'  => $this->db->escape($keterangan),
              'kredit' => $b['saldo'],
              'debet'  => 0,
              'urutan'  => 3,
            );
            $detail[]=array(
              'ref_akun'  => '2001',
              'keterangan'  => $this->db->escape($keterangan),
              'kredit' => $data['totalrefund']-$b['saldo'],
              'debet'  => 0,
              'urutan'  => 3,
            );
          }
        }else{
          $detail[]=array(
            'ref_akun'  => '2001',
            'keterangan'  => $this->db->escape($keterangan),
            'kredit' => $data['totalrefund'],
            'debet'  => 0,
            'urutan'  => 2,
          );
        }
      }else{
        $detail[]=array(
          'ref_akun'  => $b['rek_parent'],
          'keterangan'  => $this->db->escape($keterangan),
          'kredit' => $data['totalrefund'],
          'debet'  => 0,
          'urutan'  => 2,
        );
      }
      
      $jurnal=array(
        'tanggal' => isset($data['date_added'])?$data['date_added']:date('Y-m-d'),
        'keterangan'  => $keterangan,
        'hapus' => 0,
        'ref' => $id,
        'type'  => 201912,
        'linkterkait' => $no_invoice,
        'details'  => $detail,
        'idref' => $id,
        'urlref'  =>'sale/returnpenjualan',
        'no_dokumen'  => $no_invoice
      );
      $jurnal_id=$this->model_keuangan_jurnal->addJurnalUmum($jurnal);

      $aruskas=array(
        'date_added'  => isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s'),
        'bank_id' => $data['bank_id'],
        'saldokeluar'  => $data['totalrefund'],
        'saldomasuk' => 0,
        'saldoawal' => $b['saldo'],
        'saldoakhir'  => $saldo,
        'ref' => $no_invoice,
        'keterangan'  => 'Return Penjualan',
        'type'  => 7000,
        'ref_akun'  => '4001',
        'idref' => $id,
        'urlref'  =>'sale/returnpenjualan',
        'no_dokumen'  => $no_invoice,
        'jurnal_id' => $jurnal_id
      );

      $this->model_keuangan_bank->addAruskas($aruskas);
    //i
      /*Debet 
      Kredit
      $this->load->model('sale/customer');
      $hutang=array(
        'ref'=> $data['id'],
        'date_trans'	=> $data['date_added'],
        'saldokeluar'	=> 0,
        'saldomasuk'	=> $data['totalrefund'],
        'keterangan'	=> "Retur Penjualan",
        'hapus'	=> 0,
        'customer_id'=> $data['customer_id'],
        'date_added'	=> $data['date_added'],
        'date_modified' => date('Y-m-d H:i:s')
      );
  
      $this->model_sale_customer->addHistoryDeposit($hutang);

      $this->load->model('keuangan/jurnal');
      if($data['totalrefund'] > 0){
        $details=array();
        $details[]=array(
          'ref_akun'  => '4003',
          //'jenis_akun'  => 52,
          'keterangan'  => 'Retur penjualan',
          'kredit' => 0,
          'debet'  => $data['totalrefund'] - $data['pajak'],
          'urutan'  => 1,
          'hapus' => 0
        );
        if($data['pajak'] > 0){
          $details[]=array(
            'ref_akun'  =>'2505',
            'kredit' => 0,
            'debet'  => $data['pajak'],
            'urutan'  =>2,
            'keterangan'  => 'PPN Keluaran'
          );
        }
        

          $details[]=array(
            'ref_akun'  => '2401',
            'keterangan'  => 'Uang Muka Penjualan',
            'kredit' => $data['totalrefund'],
            'debet'  => 0,
            'urutan'  => 3,
            'hapus' => 0
          );
        
       
          $j=array(
            'tanggal' => isset($data['date_added'])?$data['date_added']:date('Y-m-d'),
            'keterangan'  => 'Return Penjualan '.$data['no_return'],
            'details' => $details,
            'hapus' =>0,
            'ref' => $data['id'],
			      'linkterkait' =>$data['no_return'],
            'type'  => 19001
          );
          $this->model_keuangan_jurnal->addJurnalUmum($j);
      //  }
      }*/
      
    }
    
    return $id;

  }

  public function batalkan($order_id){
    //hapus jurnal
    $this->load->model('keuangan/jurnal');
    $this->model_keuangan_jurnal->updateJurnalumum(array('hapus'=>0),array('ref'=>$order_id));
    //update stok

    //update status
  }

 
  public function addPenjualanProduct($data){
    $total=0;
    $diskon=0;

  //  $penjualan=$this->getPenjualan(array('id' => $data['id']));

  $this->load->model('catalog/product');
  $this->load->model('sale/salesorder');
  $this->load->model('catalog/bahanbaku');
  $this->load->model('catalog/tabungmr');
  $this->load->model('gudang/kartustok');
  $this->load->model('gudang/product');
  $this->load->model('catalog/kartustoktabungmp');
  $this->load->model('catalog/kartustoktabungstok');
  $this->load->model('catalog/kartustoktabungmr');
  $this->load->model('catalog/tabungms');
    $netcostproduksi=0;
    $netcostdagang=0;
	$hargajualppn=0;
	$pajakppn=0;
  $hargajual=0;
  $penj=array();
  $hasil=array();

  $piutang=0;
  $piutangbelum=0;
    foreach($data['product'] as $p){
      if(!empty($p['product_id']) & $p['quantity'] > 0){
        if($p['pilih']){
        //getnetcost
        //$net=$this->db->first('product_toko_pameran',array('gudang_id'  => $penjualan['pameran_id'],'product_id'  => $p['product_id']));

          $curqty=$this->model_gudang_product->getProduct($p['product_id'],$data['gudang_id']);
          //$pajak=round(($p['price']-$p['diskon'])*0.1);
        //  $total=(($p['price']-$p['diskon'])*$p['quantity']) + ($pajak*$p['quantity']);
        //$this->load->model('sale/salesorder');
        $so=$this->model_sale_salesorder->getPenjualan(array('id'=>$p['no_so']));
        if(!empty($so)){
          $data['jenispenjualan']=$so['jenispenjualan'];
          $data['jenisstok']=$so['jenisstok'];

          $data['tttk']=$so['tttk'];
        }else{
          $data['jenispenjualan']=1;
          $data['jenisstok']=1;
          $data['tttk']=0;
        }
          if($data['jenisstok'] == 1){
            $curqty['net_cost']=empty($curqty['net_cost'])?0:$curqty['net_cost'];
            $netcostdagang += ($curqty['net_cost']*$p['quantity']);
			
			// baru 16 September 2019
			/*
			$hargajualppn += $p['total'];
			$pajakppn += $p['pajak'];
			$hargajual += ($p['price']*p['quantity']);
			*/
          }else{
            $curqty['net_cost']=0;
            $netcostproduksi += ($curqty['net_cost']*$p['quantity']);
          }

          if(empty($so['usia'])){
            $so['usia']=0;
          }

          $time= strtotime($data['date_added']) + ($so['usia']*86400);

          $jatuhtempo=date("Y-m-d",$time);

          $penj[]=array(
            'id' => $p['id'],
            'sales_order_id' => $data['id'],
            'product_id'  => $p['product_id'],
            'name'  => $this->db->escape($p['name']),
            'quantity' => $p['quantity'],
            'tabung_id' => empty($p['tabung_id'])?0:$p['tabung_id'],
            'price' => $p['price'],
           'pajak' => $p['pajak'],
            'total' => $p['total'],
            'net_cost'  => $curqty['net_cost'],
            'jenispenjualan'  => empty($data['jenisstok'])?1:$data['jenisstok'],
            'no_so' => $p['no_so'],
            'jenisstok' => empty($data['jenispenjualan'])?1:$data['jenispenjualan'],
            'tttk'  => $data['tttk'],
            'hapus' => 0,
            'nomor_so'  => $p['nomor_so']
          );

          $prod=$this->model_gudang_product->getProduct($p['product_id'],$data['gudang_id']);
          $update=$this->model_gudang_product->updateQty($p['product_id'],$data['gudang_id'],$p['quantity'],1);
  
        //cek invoice
        $inv=$this->db->query("SELECT * FROM penjualan_product WHERE id='".$p['id']."'");
        if($inv->row['invoice_id'] > 0){
          $piutang += $p['total'];
        }else{
          $piutangbelum += $p['total'];
        }
        //update sales order
        //$qtytrm=$this->model_sale_salesorder->updateqtykirim($p['id'],$p['quantity'],1);
        $this->db->update('sales_order_product',array('quantityreturn'=>$p['quantity']),array('id'=>$p['id']));
        $this->db->update('penjualan_product',array('quantityreturn'=>$p['quantity']),array('id'=>$p['id']));
        
        //update Stok
        
        //kartustok

        $kartustok=array(
          'product_id'	=> $p['product_id'],
          'product_name'	=> $p['name'],
          'tgl'	=> date('Y-m-d h:i:s',time()),
          'stokmasuk'	=> $p['quantity'],
          'stokkeluar'	=> 0,
          'ket'	=> 'Return Penjualan '.$data['no_return'],
          'saldo'	=> $update,
          'quantityawal'	=> isset($prod['quantity'])?$prod['quantity']:0,
          'invoice'	=> $data['no_return'],
          'gudang_id'	=> $data['gudang_id'],
          'type'	=> 19000,
          'idref' => $data['id'],
          'urlref'  =>'sale/returnpenjualan',
          'no_dokumen'  => $data['no_return']
        );

        /*
        'no_dokumen'  => isset($data['no_dokumen'])?$data['no_dokumen']:'',
			'urlref'  => isset($data['urlref'])?$data['urlref']:'',
			'idref' => isset($data['idref'])?$data['idref']:0
        */

        $this->model_gudang_kartustok->addKartuStok($kartustok);

        //cek tabungms
        $cek=$this->model_catalog_tabungms->getProductByGudang($p['product_id'],$data['gudang_id']);
        if(!empty($cek)){
          $curmsqty=$this->model_catalog_tabungms->getProductByGudang($p['product_id'],$data['gudang_id']);


    			$updatems=$this->model_catalog_tabungms->updateQty($p['product_id'],$data['gudang_id'],$p['quantity'],1 );

    			$kartustok=array(
    				'tabung_id'	=> $p['product_id'],
    				'tgl'	=> date('Y-m-d h:i:s',time()),
    				'stokmasuk'	=> $p['quantity'],
    				'stokkeluar'	=> 0,
    				'ket'	=> $this->db->escape("Return Penjualan".$data['no_return']),
    				'saldo'	=> $updatems,
    				'quantityawal'	=> $curmsqty['quantity'],
    				'invoice'	=> $data['no_return'],
    				'gudang_id'	=> $data['gudang_id'],
            'type'	=> 19000,
            'idref' => $data['id'],
            'urlref'  =>'sale/returnpenjualan',
            'no_dokumen'  => $data['no_return']
    			);

    		    $this->model_catalog_kartustoktabungstok->addKartuStok($kartustok);
        }
        /*if(!empty($p['tabung_id'])){
            if($p['tabung_id'] > 0){
              $this->db->update('tabung_mp',array('status' => 1,'date_modified'=>date('Y-m-d H:i:s',time())),array('id'=>$p['tabung_id']));
            //  $penj=$this->getPenjualanDetail(array('id'=>$penj['id']));
              $kartustok=array(
          			'tabung_id'	=> $p['tabung_id'],
          			'tglpengembalian'	=> date('Y-m-d',time()),
          			'tglpeminjaman'	=>date('Y-m-d',time()),
          			'tglisiulang'	=> date('Y-m-d',time()),
          			'customer_id'	=> $data['customer_id'],
                'invoice'	=> $data['no_return'],
          			'ket'	=> 'Return tabung',
          			'biayasewa'	=> 0
          		);

              $this->model_catalog_kartustoktabungmp->addKartuStok($kartustok);
            }
        }*/
        /*if($data['jenispenjualan'] == 2){
          $tabung=$this->model_catalog_tabungmr->getTabungByProduct($p['product_id'],$data['customer_id']);
          if(!empty($tabung)){
            $qty=$tabung['quantity']-$p['quantity'];
            $this->db->update('tabung_mr',array('quantity'=>$qty,'date_modified'=>date('Y-m-d H:i:s',time())),array('id'=>$tabung['id']));

            $kartustok=array(
              'tabung_id'	=> $tabung['id'],
              'tgl'	=> date('Y-m-d H:i:s'),
              'stokmasuk'	=> 0,
              'stokkeluar'	=> $p['quantity'],
              'ket'	=> $this->db->escape("Pengiriman barang"),
              'saldo'	=> $qty,
              'quantityawal'	=> $tabung['quantity'],
              'invoice'	=> $data['id'],
              //'gudang_id'	=> $data['gudang_id'],
              'type'	=> 1
            );
            $this->model_catalog_kartustoktabungmr->addKartuStok($kartustok);
          }
        }*/


    }
    }
   
    }
    $this->db->update('return_penjualan',array('product'=>json_encode($penj)),array('id'=>$data['id']));



      $this->load->model('keuangan/jurnal');
      if(!empty($data['net_cost'])){
        $details=array();
        $details[]=array(
          'ref_akun'  => '1202',
          //'jenis_akun'  => 52,
          'keterangan'  => 'Persediaan barang jadi',
          'kredit' => 0,
          'debet'  => $netcostdagang + $netcostproduksi,
          'urutan'  => 1,
          'hapus' => 0
        );
        if($netcostdagang > 0){

          $details[]=array(
            'ref_akun'  => '5001',
            'keterangan'  => 'Harga Pokok Penjualan Produk Dagang',
            'kredit' => $netcostdagang,
            'debet'  => 0,
            'urutan'  => 2,
            'hapus' => 0
          );
        }
        if($netcostproduksi > 0){
          //$details=array();
          $details[]=array(
            'ref_akun'  => '5002',
            'keterangan'  => 'Harga Pokok Penjualan Hasil Produksi',
            'kredit' => $netcostproduksi,
            'debet'  => 0,
            'urutan'  => 3,
            'hapus' => 0
          );
        }



          $j=array(
            'tanggal' => isset($data['date_added'])?$data['date_added']:date('Y-m-d'),
            'keterangan'  => 'Return Penjualan '.$data['no_return'],
            'details' => $details,
            'hapus' =>0,
            'ref' => $data['id'],
			      'linkterkait' =>$data['no_return'],
            'type'  => 19000,
            'idref' => $data['id'],
            'urlref'  =>'sale/returnpenjualan',
            'no_dokumen'  => $data['no_return']
          );
          $this->model_keuangan_jurnal->addJurnalUmum($j);
      //  }
      }

      $hasil=array(
        'piutang' => $piutang,
        'piutangbelum'  => $piutangbelum
      );

      return $hasil;

  }

  

  public function updatePenjualan($data,$where=array()){
	$this->db->update('return_penjualan',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('return_penjualan',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order=array()){
		return $this->db->firstdetail('return_penjualan',$column,$join,$where,$order);
	}
  
	public function getPenjualans($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order,$limit,$offset){
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		/*if($custdata != 1){
			$where['penjualan.sales']=$this->user->getId();
		}*/
		return $this->db->alljoins('return_penjualan',$column,$join,$leftjoin,$where,$order,$limit,$offset);
	}
  public function getPenjualanDetailss($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order,$limit,$offset){
		return $this->db->alljoin('return_penjualan',$column,$join,$leftjoin,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where,$join=array(),$leftjoin=array()){
    $this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		/*if($custdata != 1){
			$where['penjualan.sales']=$this->user->getId();
		}*/
		return $this->db->countAll('return_penjualan',$where,$join,$leftjoin);
	}

  
}
?>
