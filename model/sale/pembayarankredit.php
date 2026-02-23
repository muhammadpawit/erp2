<?php
class ModelSalePembayarankredit extends Model {
  // baru 20 Januari 2020
  public function getpembayarankredit($invoice_id){
    $sql ="SELECT pembayaran_kredit.*, customer.name FROM pembayaran_kredit JOIN pembayaran_kredit_invoice ON(pembayaran_kredit_invoice.pembayaran_id=pembayaran_kredit.id) LEFT JOIN customer ON(customer.customer_id=pembayaran_kredit.customer_id) WHERE pembayaran_kredit_invoice.invoice_id='$invoice_id' ";
    $d = $this->db->query($sql);
    return $d->rows;
  }
  // end baru
  public function addPembelian($data){
	$linkterkait =null;
  	if(isset($data['linkterkait'])){
  		$linkterkait=$data['linkterkait'];
  	}
    //$pajak=0.1*$data['jumlah'];
    $data['date_added']=isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s',time());
    
    $bayar=array(
      'customer_id' => $data['customer_id'],
      'total' => $data['total'],
      'date_added'  => isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s',time()),
      'date_modified' => date('Y-m-d H:i:s',time()),
      'linkterkait' => $linkterkait,
      
      'hapus' => 0,
      'status'=>1,
    );
    /*$p=array(
      'penjualan_id' => $data['no_po'],
      'jumlah'  => $data['jumlah'],
      'date_added'  => isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s',time()),
      'date_modified' => date('Y-m-d H:i:s',time()),
      'status'  => 1,
      'hapus' => 0,
      'bank_id' => $data['bank_id']
    );*/

    $this->db->insert('pembayaran_kredit',$bayar);
    $id=$this->db->getLastId();
    $no_dokumen="PBK-".$id."-".date('Y')."-".date("m")."-".$this->user->getId();
    
    $this->db->update('pembayaran_kredit',array('no_dokumen'=>$no_dokumen),array('id'=>$id));
    $data['id']=$id;

    $this->addPembayaranInvoice($data);
    $this->load->model('sale/customer');

    //update deposit customer
    $cust=$this->model_sale_customer->getVendor(array('customer_id' => $data['customer_id']));
    $this->model_sale_customer->updatePiutang($data['customer_id'],$data['total'],2);
    $this->model_sale_customer->updateDeposit($data['customer_id'],$data['total'],2);
    $hutang=array(
			'ref'=> $data['id'],
			'date_trans'	=> $bayar['date_added'],
			'saldomasuk'	=> 0,
			'saldokeluar'	=> $data['total'],
			'keterangan'	=> "Pembayaran piutang",
			'hapus'	=> 0,
			'customer_id'=> $data['customer_id'],
			'date_added'	=> $bayar['date_added'],
      'date_modified' => date('Y-m-d H:i:s'),
      'urlref'  => 'sale/pembayarankredit',
      'no_dokumen'  => $no_dokumen
		);

    $this->model_sale_customer->addHistoryDeposit($hutang);

    $piutang=array(
      'customer_id'=>$data['customer_id'],
      'date_added'=>isset($bayar['date_added'])?$bayar['date_added']:date('Y-m-d H:i:s'),
      'saldokeluar'=>$data['total'],
      'saldomasuk' =>0,
      'idref' =>$id,
      'urlref'  =>'sale/pembayarankredit',
      'no_dokumen'  =>$no_dokumen,
      'referensi' =>$no_dokumen
    );

    $this->db->insert('buku_piutang',$piutang);
    //jurnal umum
    $this->load->model('keuangan/jurnal');
    $detail=array();


      $detail[]=array(
        'ref_akun'  =>'2401',
        'debet' => $data['total'],
        'kredit'  => 0,
        'urutan'  =>1,
        'keterangan'  => 'Uang Muka Penjualan'
      );
      $detail[]=array(
        'ref_akun'  =>'1101',
        'debet' => 0,
        'kredit'  => $data['total'],
        'urutan'  =>2,
        'keterangan'  => 'Piutang Usaha'
      );

	
      $jurnal=array(
        'tanggal' => $bayar['date_added'],
        'keterangan' => 'Alokasi Pembayaran Penjualan',
        'ref' => $data['id'],
		  'linkterkait' => $linkterkait,
        'type' => 5,
        'details' => $detail,
        'idref' => $id,
        'no_dokumen'  => $no_dokumen,
        'urlref'  => 'sale/pembayarankredit'
      );
      $this->model_keuangan_jurnal->addJurnalUmum($jurnal);
    //debet



  }

  public function addPembayaranInvoice($data){
    foreach($data['orders'] as $i){
      $inv=array(
        'pembayaran_id' => $data['id'],
        'invoice_id'  => $i['invoice_id'],
        'total' => $i['total']
      );
      $this->db->insert('pembayaran_kredit_invoice',$inv);

      $this->load->model('sale/invoice');
      $pb=$this->model_sale_invoice->getPenjualan(array('id'=>$i['invoice_id']));



      //update total bayar
      $totalbayar=$pb['totalbayar'] + $i['total'];
      if($totalbayar >= $pb['totaltagihan']){
        $status=3;
      }else{
        $kekurangan=$pb['totaltagihan'] - $totalbayar;
        if($kekurangan < 0.01){
          $status=3;
        }else{
          $status=2;
        }
      }

      if($status == 3){
        $this->db->update('invoice',array('status'=>3,'totalbayar'=>$totalbayar,'tgllunas'=>$data['date_added']),array('id'=>$i['invoice_id']));
      }else{
          $this->db->update('invoice',array('status'=>2,'totalbayar'=>$totalbayar),array('id'=>$i['invoice_id']));
      }
      //$this->db->update('invoice',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'=>$i['invoice_id']));
    }
  }

  public function updatePermintaan($data,$where){
    if($data['status'] == 3){
      $pb=$this->getPermintaanPembelian(array(),array(),$where);
    //  $this->db->update('invoice',array('status'=>1,'totalbayar'=>0),array('id'=>$pb['penjualan_id']));
      $inv=$this->getPembayaranInvoice(array('pembayaran_id' => $pb['id']));
      $this->load->model('sale/invoice');

      foreach($inv as $i){
        $iv=$this->model_sale_invoice->getPenjualan(array('id'=>$i['invoice_id']));
        $totalbayar=$iv['totalbayar'] - $i['total'];

        if($totalbayar <= 0){
          $status=1;
        }else{
          $status=2;
        }
        $this->db->update('invoice',array('status'=>$status,'tgllunas'=>'1970-01-01','totalbayar'=>$totalbayar),array('id'=>$i['invoice_id']));
      }

      $this->load->model('keuangan/jurnal');
      $this->model_keuangan_jurnal->updateJurnalumum(array('hapus' => 1),array('type'  => 5,'ref'  => $pb['id']));

      $this->load->model('sale/customer');

      //update deposit customer
      $cust=$this->model_sale_customer->getVendor(array('customer_id' => $pb['customer_id']));
      $this->model_sale_customer->updatePiutang($pb['customer_id'],$pb['total'],1);
      $this->model_sale_customer->updateDeposit($pb['customer_id'],$pb['total'],1);
      $hutang=array(
  			'ref'=> $pb['id'],
  			'date_trans'	=> date('Y-m-d H:i:s'),
  			'saldomasuk'	=> $pb['total'],
  			'saldokeluar'	=> 0,
  			'keterangan'	=> "Pembatalan Pembayaran piutang oleh ".$this->user->getUsername(),
  			'hapus'	=> 0,
  			'customer_id'=> $pb['customer_id'],
  			'date_added'	=> date('Y-m-d H:i:s'),
        'date_modified' => date('Y-m-d H:i:s'),
        'urlref'  =>'sale/pembayarankredit',
        'no_dokumen'  =>$pb['no_dokumen'],
  		);

      $this->model_sale_customer->addHistoryDeposit($hutang);
      $piutang=array(
        'customer_id'=>$pb['customer_id'],
        'date_added'=>date('Y-m-d H:i:s'),
        'saldomasuk'=>$pb['total'],
        'saldokeluar' =>0,
        'idref' =>$pb['id'],
        'urlref'  =>'sale/pembayarankredit',
        'no_dokumen'  =>$pb['no_dokumen'],
        'referensi' =>$pb['no_dokumen']
      );
  
      $this->db->insert('buku_piutang',$piutang);


    }
    $this->db->update('pembayaran_kredit',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('pembayaran_kredit',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('pembayaran_kredit',$where);
	}
  public function totalDp($no_po){
    return $this->db->firstdetail('pembayaran_kredit',array('COALESCE(SUM(jumlah)) as total'),array(),array('penjualan_id' => $no_po,'hapus' => array('<',1),'status' => array('<>',3)),array());
  }

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('pembayaran_kredit',$column,$join,$where,array());
  }
  public function getPembayaranInvoice($where){
    return $this->db->alljoin('pembayaran_kredit_invoice',array(),array(),$where,array(),0,null);
  }


}
?>
