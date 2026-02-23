<?php
class ModelPembelianBiayapembelianimport extends Model {
  public function getnamavendor($vendor_id){
    $d = $this->db->query("SELECT * FROM vendorlokal WHERE id='$vendor_id' ");
    return $d->row['name'];
  }
  /*
  0. belum ada tagihan
  1. ditagih
  2. belum lunas
  3. lunas
  4. dibatalkan
  */
  public function addTagihanBiaya($data){
    $this->load->model('keuangan/jurnal');
    $this->log->write(json_encode($data));
    $subtotal=str_replace(",", "", $data['nominal']);
    $totaltagihan=str_replace(",", "", $data['total']);
    $pajakpph=empty($data['nilaipajak'])?0:str_replace(",", "", $data['nilaipajak']);
    $akunhutangpajak=str_replace(",", "", $data['pajak']);
    $ppn=str_replace(",", "", $data['ppn']);
    $pajakdimuka=str_replace(",", "", $data['pajakdimuka']);
    $statuspajakdimuka=$data['statuspajakdimuka'];
    $totalestimasi=str_replace(",", "", $data['totalestimasi']);
    $e=0;
	  $tagihan=array(
      'date_added'  => date('Y-m-d H:i:s'),
      'keterangan'  => $this->db->escape($data['keterangan']),
      'nominal' =>$subtotal,
      'status'  => 1,
      'user_id' => $this->user->getId(),
      'nilaipajak'  =>$pajakpph,
      'total' => str_replace(",", "", $data['total']),
      'vendor_id' => $data['vendor_id'],
      'totalbayar'  => 0,
      'no_faktur' => $this->db->escape($data['no_faktur']),
      'tgl_tagihan' => $data['tgl_tagihan'],
      'jatuhtempo'  => $data['jatuhtempo'],
      'pajak' =>$akunhutangpajak,
      'statuspajak' => $data['statuspajak'],
      'ppn' =>$ppn,
      'ref' => $data['ref'],
      'pajakdimuka' =>$pajakdimuka,
      'statuspajakdimuka' =>$statuspajakdimuka,
      'totalestimasi' =>$totalestimasi
    );
    $this->db->insert('tagihan_biaya_import',$tagihan);
    $id=$this->db->getLastId();
    $no_dokumen='TBI-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;
    
    $this->db->update('tagihan_biaya_import',array('no_dokumen'=>$no_dokumen),array('id'=>$id));

    foreach($data['biaya'] as $b){
      $totalreal=str_replace(",", "", $b['totalreal']);
      if($b['jenisbiaya_id'] > 0){
      if($b['id'] > 0){
		    $this->db->update('biaya_pembelianimport',array('tagihan_id'=> $id,'statuspembayaran'=>1,'no_dokumen'=>$no_dokumen,'totalreal'=> str_replace(",", "", $b['totalreal']),'no_faktur'=>$data['no_faktur'],'tglfaktur'=> $data['tgl_tagihan'],'jatuhtempo'  => $data['jatuhtempo']),array('id'=>$b['id']));
      }else{
        $biaya=array(
          'order_id'  => $data['ref'],
          'total' => 0,
		      'totalreal' =>$totalreal,
          'jenisbiaya_id' => $b['jenisbiaya_id'],
          'statuspembayaran'  =>1,
          'tagihan_id'  => $id,
          'no_faktur'=>$data['no_faktur'],
		      'ppn'=>str_replace(",","",$b['ppn']),
          'tglfaktur'=> $data['tgl_tagihan'],
          'jatuhtempo'  => $data['jatuhtempo'],
          'no_dokumen'  => $no_dokumen
        );
        $this->db->insert('biaya_pembelianimport',$biaya);
      }
      }
    }

    $this->load->model('keuangan/jurnal');

    /*
    hutang belum ditagih x hutang usaha
    jika estimasi lebih besar
    8002
    jika estimasi lebih kecil
    7002

    jika ppn terhitung total
    total = total - ppn
    */
    $details=array();

	  // status pajak = potong total & Status Pajak Dibayar Dimuka = Termasuk total
    if($data['statuspajak'] == 1 && $data['statuspajakdimuka']==1){
      // pph 23 diambil dari $data['nilaipajak']
      // case 1 total estimasi > total tagihan-ppn
      if( ($totalestimasi+$ppn) > $totaltagihan ){
        // PPN
        if($ppn>0){
          $details[]=array(
            'ref_akun'  => '1555',
            'keterangan'  => 'PPN',
            'debet' =>$ppn,
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );
        }
        // hutang usaha belum ditagih
       if($totalestimasi>0){
          $details[]=array(
            'ref_akun'  => '2150',
            'keterangan'  => 'Hutang Usaha Belum Ditagih',
            'debet' => ($totalestimasi),
            'kredit'  => 0,
            'urutan'  => 2,
            'hapus' => 0
          );
       }
        // Pph 23
        if($pajakpph>0){
          $details[]=array(
            'ref_akun'  => $data['pajak'],
            'keterangan'  => 'PPh',
            'debet' => 0,
            'kredit'  => $pajakpph,
            'urutan'  =>3,
            'hapus' => 0
          );	
        }
        // pendapatan estimasi Hpp
        $details[]=array(
          'ref_akun'  => '7002',
          'keterangan'  => 'Pendapatan Estimasi HPP',
          'debet' => 0,
          'kredit'  => ( ($totalestimasi+$ppn) - ($totaltagihan+$pajakpph) ),
          'urutan'  => 4,
          'hapus' => 0
        );
        $details[]=array(
          'ref_akun'  => '2101',
          'keterangan'  => 'Hutang Usaha',
          'debet' =>0,
          'kredit'  => ($totaltagihan),
          'urutan'  => 5,
          'hapus' => 0
        );        
      }
      // case 2 total tagihan - ppn > total estimasi
      if($totaltagihan > ($totalestimasi+$ppn)){
        if($ppn>0){
          $details[]=array(
            'ref_akun'  => '1555',
            'keterangan'  => 'PPN',
            'debet' => $ppn,
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );
        }
        if($totalestimasi>0){
          $details[]=array(
            'ref_akun'  => '2150',
            'keterangan'  => 'Hutang Usaha Belum Ditagih',
            'debet' => ($totalestimasi),
            'kredit'  => 0,
            'urutan'  => 2,
            'hapus' => 0
          );
        }
        $details[]=array(
          'ref_akun'  => '8002',
          'keterangan'  => 'Beban Salah Hitung HPP',
          'debet' => ( ($totaltagihan+$pajakpph) - ($totalestimasi+$ppn) ),
          'kredit'  => 0,
          'urutan'  => 3,
          'hapus' => 0
        );		  
        if($pajakpph>0){
          $details[]=array(
            'ref_akun'  => $data['pajak'],
            'keterangan'  => 'PPh',
            'debet' => 0,
            'kredit'  => $pajakpph,
            'urutan'  =>4,
            'hapus' => 0
          );	
        }	  
        $details[]=array(
          'ref_akun'  => '2101',
          'keterangan'  => 'Hutang Usaha',
          'debet' => 0,
          'kredit'  => ($totaltagihan),
          'urutan'  => 5,
          'hapus' => 0
        );
      }

      // case 3 totaltagihan=totalestimasi
      if($totaltagihan==($totalestimasi+$ppn)){
        if($ppn>0){
          $details[]=array(
            'ref_akun'  => '1555',
            'keterangan'  => 'PPN',
            'debet' => $ppn,
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );
        }
        if($totalestimasi>0){
          $details[]=array(
            'ref_akun'  => '2150',
            'keterangan'  => 'Hutang Usaha Belum Ditagih',
            'debet' => ($totalestimasi),
            'kredit'  => 0,
            'urutan'  => 2,
            'hapus' => 0
          );
        }
        if($pajakpph>0){
          $details[]=array(
            'ref_akun'  => $data['pajak'],
            'keterangan'  => 'PPh',
            'debet' => 0,
            'kredit'  => $pajakpph,
            'urutan'  =>3,
            'hapus' => 0
          );	
        }
        $details[]=array(
          'ref_akun'  =>'2101',
          'keterangan'  => 'Hutang Usaha',
          'debet' =>0,
          'kredit'  =>$totaltagihan,
          'urutan'  =>4,
          'hapus' => 0
        );
      }
    }
    // Status Pajak = Tidak Potong Total  (catatan: Total Tagihan adalah Tagihan tidak termasuk PPh)
    if($data['statuspajak']==2){
      // case 1, pendapatan estimasi hpp,, Total estimasi > (totaltagihan-ppn)
      if(($totalestimasi+$ppn)>$totaltagihan ){
        if($totalestimasi>0){
          $details[]=array(
            'ref_akun'  => '2150',
            'keterangan'  => 'Hutang Usaha Belum Ditagih',
            'debet' => ($totalestimasi),
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );
        }
        if($ppn>0){
          $details[]=array(
            'ref_akun'  => '1555',
            'keterangan'  => 'PPN',
            'debet' => $ppn,
            'kredit'  => 0,
            'urutan'  => 2,
            'hapus' => 0
          );
        }

        if($pajakpph>0){
          $details[]=array(
            'ref_akun'  => '6252',
            'keterangan'  => 'Biaya Pajak dan Retribusi',
            'debet' => $pajakpph,
            'kredit'  => 0,
            'urutan'  => 3,
            'hapus' => 0
          );
        }

        if($pajakpph>0){
          $details[]=array(
            'ref_akun'  => $data['pajak'],
            'keterangan'  => 'PPh',
            'debet' => 0,
            'kredit'  => $pajakpph,
            'urutan'  => 4,
            'hapus' => 0
          );
        }

        $details[]=array(
          'ref_akun'  => '7002',
          'keterangan'  => 'Pendapatan Estimasi HPP',
          'debet' => 0,
          'kredit'  => ( ($totalestimasi+$ppn) - ($totaltagihan+$pajakpph) ),
          'urutan'  => 5,
          'hapus' => 0
        );
                
        
        $details[]=array(
          'ref_akun'  => '2101',
          'keterangan'  => 'Hutang Usaha',
          'debet' =>0,
          'kredit'  =>($totaltagihan+$pajakpph),
          'urutan'  =>6,
          'hapus' => 0
        );
       
      }
      // case 2
      if(($totalestimasi+$ppn) < $totaltagihan){
        if($ppn>0){
          $details[]=array(
            'ref_akun'  => '1555',
            'keterangan'  => 'PPN',
            'debet' =>$ppn,
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );
        }
        $details[]=array(
          'ref_akun'  => '2150',
          'keterangan'  => 'Hutang Usaha Belum Ditagih',
          'debet' => ($totalestimasi),
          'kredit'  => 0,
          'urutan'  => 2,
          'hapus' => 0
        );
        $details[]=array(
        'ref_akun'  => '8002',
        'keterangan'  => 'Beban Salah Hitung HPP',
        'debet' => ( ($totaltagihan+$pajakpph) -($totalestimasi+$ppn)) ,
        'kredit'  => 0,
        'urutan'  =>3,
        'hapus' => 0
        );
        if($pajakpph>0){
          $details[]=array(
            'ref_akun'  => '6252',
            'keterangan'  => 'Biaya Pajak dan Retribusi',
            'debet' =>$pajakpph,
            'kredit'  => 0,
            'urutan'  =>4,
            'hapus' => 0
          );
        }
        if($pajakpph>0){
          $details[]=array(
            'ref_akun'  => $data['pajak'],
            'keterangan'  => 'PPh',
            'debet' => 0,
            'kredit'  => $pajakpph,
            'urutan'  => 5,
            'hapus' => 0
            );
        }	   
        $details[]=array(
          'ref_akun'  => '2101',
          'keterangan'  => 'Hutang Usaha',
          'debet' =>0,
          'kredit'  =>($totaltagihan+$pajakpph),
          'urutan'  =>6,
          'hapus' => 0
        );
      }
      // case 3
      if(($totalestimasi+$ppn)==$totaltagihan){
        if($ppn>0){
          $details[]=array(
            'ref_akun'  => '1555',
            'keterangan'  => 'PPN',
            'debet' =>$ppn,
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );
        }
        $details[]=array(
          'ref_akun'  => '2150',
          'keterangan'  => 'Hutang Usaha Belum Ditagih',
          'debet' => ($totalestimasi),
          'kredit'  => 0,
          'urutan'  => 2,
          'hapus' => 0
        );
        if($pajakpph>0){
          $details[]=array(
            'ref_akun'  => '6252',
            'keterangan'  => 'Biaya Pajak dan Retribusi',
            'debet' =>$pajakpph,
            'kredit'  => 0,
            'urutan'  =>4,
            'hapus' => 0
          );
        }
        if($pajakpph>0){
          $details[]=array(
            'ref_akun'  => '6252',
            'keterangan'  => 'Biaya Pajak dan Retribusi',
            'debet' =>$pajakpph,
            'kredit'  => 0,
            'urutan'  =>4,
            'hapus' => 0
          );
        }
        $details[]=array(
          'ref_akun'  => '2101',
          'keterangan'  => 'Hutang Usaha',
          'debet' =>0,
          'kredit'  =>(($totaltagihan+$pajakpph)),
          'urutan'  =>5,
          'hapus' => 0
        );
      }
    }
    $j=array(
      'tanggal' => isset($data['tgl_tagihan'])?$data['tgl_tagihan']:date('Y-m-d'),
      'keterangan'  => 'Invoice Biaya Pembelian Produk Import No Faktur '.$data['no_faktur']." ( ".$data['refinvoice']." ) ",
      'details' => $details,
      'hapus' =>0,
      'ref' => $id,
	    'linkterkait' => $data['no_faktur'],
      'type'  => 33000,
      'urlref' => 'pembelian/tagihanbiayaimport',
      'idref' => $id,
      'no_dokumen'  => $no_dokumen
    );
    $this->model_keuangan_jurnal->addJurnalUmum($j);
    if($this->user->getUsername()=="pawitx"){
      echo "<pre>";print_r($j);exit;
      }
  }

  public function addPembayaranTagihan($data){
    $this->load->model('keuangan/bank');
    $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$data['bank_id']));
    $saldo=$b['saldo'] - $data['nominal'];

    $this->model_keuangan_bank->updateBank(array('saldo' => $saldo),array('id'  => $data['bank_id']));
    $curb=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$data['bank_id']));
    $sal=$curb['saldo'];

    //$this->load->model('keuangan/tagihanbiaya');
    $biaya=$this->getTagihanBiaya(array(),array(),array('id'  => $data['order_id']));

    $p=array(
      'order_id' => $data['order_id'],
      'nominal'  => $data['nominal'],
      'tgl_bayar'  => $data['tgl_bayar'],
      'status'  => 1,
      'keterangan'  => $this->db->escape($data['keterangan']),
      'bank_id' => $data['bank_id'],
     // 'no_dokumen'  => $biaya[]
      /*'akun_hutang' => $biaya['akun_hutang'],
      'akun_kas'  =>$curb['rek_parent']*/
    );

    $this->db->insert('pembayaran_tagihan_biayaimport',$p);
    $id=$this->db->getLastId();
    $no_dokumen='BBI-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;
    $this->db->update('pembayaran_tagihan_biayaimport',array('no_dokumen'=>$no_dokumen),array('pembayaran_id'=>$id));
    

    //update total bayar
    //$this->load->model('keuangan/tagihan');

    //$biaya=$this->model_keuangan_tagihanbiaya->getPermintaanPembelian(array(),array(),array('id'=>$data['order_id']));
    $totalbayar=$biaya['totalbayar'] + $data['nominal'];

    if($totalbayar == $biaya['total']){
      $status=3;
      $this->db->update('biaya_pembelianimport',array('statuspembayaran'=>$status,'tgllunas'=>$data['tgl_bayar']),array('tagihan_id'  => $data['order_id']));
      $this->db->update('tagihan_biaya_import',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'  => $data['order_id']));
    }else{
      $status=2;
      $this->db->update('biaya_pembelianimport',array('statuspembayaran'=>$status),array('id'  => $data['order_id']));
      $this->db->update('tagihan_biaya_import',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'  => $data['order_id']));
    }

    //jurnal
    $this->load->model('keuangan/jurnal');


    $jurnal=array();
    $detail=array();
    //if($data['nominal'] > 0){
    $detail[]=array(
      'ref_akun'  => '2101',
      'keterangan'  => $this->db->escape('Hutang Usaha'),
      'debet' => $data['nominal'],
      'kredit'  => 0,
      'urutan'  => 2,
    );

    if($b['saldo'] < $data['nominal']){
      if($b['hutangprk'] == 1){
        if($b['saldo'] > 0){
          $hutangprk=abs($b['saldo'] - $data['nominal']);
          $detail[]=array(
            'ref_akun'  => $curb['rek_parent'],
            'keterangan'  => $this->db->escape('Pembayaran Biaya Pembelian Import'),
            'kredit' => $b['saldo'],
            'debet'  => 0,
            'urutan'  => 3,
          );
          $detail[]=array(
            'ref_akun'  => '2001',
            'keterangan'  => $this->db->escape('Pembayaran Hutang'),
            'kredit' => $hutangprk,
            'debet'  => 0,
            'urutan'  => 4,
          );
        }else{
          $hutangprk=$data['nominal'];
          $detail[]=array(
            'ref_akun'  => '2001',
            'keterangan'  => $this->db->escape('Pembayaran Hutang'),
            'kredit' => $hutangprk,
            'debet'  => 0,
            'urutan'  => 3,
          );
        }


      }else{
        $detail[]=array(
          'ref_akun'  => $curb['rek_parent'],
          'keterangan'  => $this->db->escape('Pembayaran Biaya pembelian import'),
          'kredit' => $data['nominal'],
          'debet'  => 0,
          'urutan'  => 3,
        );
      }
    }else{
      $detail[]=array(
        'ref_akun'  => $curb['rek_parent'],
        'keterangan'  => $this->db->escape('Pembayaran Biaya Pembelian Import'),
        'kredit' => $data['nominal'],
        'debet'  => 0,
        'urutan'  => 3,
      );
    }


      $jurnal=array(
        'tanggal' => $data['tgl_bayar'],
        'keterangan'  => 'Pembayaran Biaya Pembelian  '.$biaya['keterangan'],
        'hapus' => 0,
        'ref' => $id,
        'type'  => 5005,
        'details'  => $detail,
        'idref' => $biaya['id'],
        'no_dokumen'  => $no_dokumen,
        'urlref'  => 'pembelian/tagihanbiayaimport'
      );
      $jurnal_id=$this->model_keuangan_jurnal->addJurnalUmum($jurnal);

      $ak=array(
        'date_added' => date('Y-m-d H:i:s'),
        'date_trans'  => isset($data['tgl_bayar'])?$data['tgl_bayar']:date('Y-m-d H:i:s'),
        'bank_id' => $data['bank_id'],
        'saldomasuk'  => 0,
        'saldokeluar' => $data['nominal'],
        'saldoawal' => $b['saldo'],
        'saldoakhir'  => $sal,
        'ref' => $id,
        'keterangan'  => $this->db->escape($data['keterangan']),
        'type'  => 5005,
        'date_modified' => date('Y-m-d H:i:s'),
        'ref_akun'  => $curb['rek_parent'],
        'jurnal_id' => $jurnal_id,
        'idref' => $biaya['id'],
        'no_dokumen'  => $no_dokumen,
        'urlref'  => 'pembelian/tagihanbiayaimport'
      );
  
  
      $this->model_keuangan_bank->addAruskas($ak);

  //}
}



public function batalkanTagihanPembayaran($id){
  $data=$this->db->firstdetail('pembayaran_tagihan_biayaimport',array(),array(),array('pembayaran_id'=>$id),array());
  if($data['status'] == 1){
    $this->load->model('keuangan/bank');
    $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$data['bank_id']));
    $saldo=$b['saldo'] + $data['nominal'];

    $this->model_keuangan_bank->updateBank(array('saldo' => $saldo),array('id'  => $data['bank_id']));
    $curb=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$data['bank_id']));
    $sal=$curb['saldo'];

    //$this->load->model('keuangan/tagihanbiaya');
    $biaya=$this->getTagihanBiaya(array(),array(),array('id'  => $data['order_id']));

    $ak=array(
      'date_added' => date('Y-m-d H:i:s'),
      'date_trans'  => isset($data['tgl_bayar'])?$data['tgl_bayar']:date('Y-m-d H:i:s'),
      'bank_id' => $data['bank_id'],
      'saldokeluar'  => 0,
      'saldomasuk' => $data['nominal'],
      'saldoawal' => $b['saldo'],
      'saldoakhir'  => $sal,
      'ref' => $id,
      'keterangan'  => $this->db->escape('Pembatalan Pembayaran Biaya Pembelian Import'),
      'type'  => 5005,
      'date_modified' => date('Y-m-d H:i:s'),
      'ref_akun'  => $curb['rek_parent'],
      'jurnal_id' => 0,
      'idref' => $data['order_id'],
      'no_dokumen'  => $data['no_dokumen'],
       'urlref'  => 'pembelian/tagihanbiayaimport'
    );


    $this->model_keuangan_bank->addAruskas($ak);

    $totalbayar=$biaya['totalbayar'] - $data['nominal'];

    if($totalbayar == $biaya['total']){
      $status=3;
      $this->db->update('biaya_pembelianimport',array('statuspembayaran'=>$status),array('id'  => $data['order_id']));
      $this->db->update('tagihan_biaya_import',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'  => $data['order_id']));
    }else{
      if($totalbayar > 0){
        $status=2;
      }else{
        $status=1;
      }

      if($biaya['status'] == 3){
        $this->db->update('biaya_pembelianimport',array('statuspembayaran'=>$status),array('id'  => $data['order_id']));
      }

      $this->db->update('tagihan_biaya_import',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'  => $data['order_id']));
    }
  //  if($biaya['stat'] == 3){
      $ju=$this->db->first('jurnal_umum',array('ref'=>$id,'type'=>5005));
      $this->db->delete('jurnal_umum_detail',array('jurnal_id'=>$ju['id']));
      $this->db->delete('jurnal_umum',array('id'=>$ju['id']));
    //}
    $this->db->update('pembayaran_tagihan_biayaimport',array('status'=>2),array('pembayaran_id'=>$id));
  }
  return $data;
}

  public function batalkanTagihan($data){
    $result=$this->getTagihanBiaya(array(),array(),array('id'  => $data['id']));
    if($result['status'] == 1){
      //status tagihan harus 1
      //batalkan status di tagihan, di tabel biaya pembelian hapus tunjangan_id, no_faktur, totalreal, status pembayaran jadi 0
      $this->db->update('biaya_pembelianimport',array('tagihan_id'=>0,'no_faktur'=>null,'totalreal' => 0,'statuspembayaran'=>0),array('tagihan_id'  => $data['id']));
      $this->db->update('tagihan_biaya_import',array('status'=>4),array('id'  => $data['id']));

      $ju=$this->db->first('jurnal_umum',array('ref'=>$data['id'],'type'=>33000));
      if(!empty($ju)){
        $this->db->delete('jurnal_umum',array('id'=>$ju['id']));
        $this->db->delete('jurnal_umum_detail',array('jurnal_id'=>$ju['id']));
      }

    }

  }

  public function addPenjualan($data){
    //cek biaya
    $biaya=$this->getPenjualanDetail(array(),array(),array('order_id'=>$data['order_id'],'jenisbiaya_id'=>$data['jenisbiaya_id']));

    /*
    jika estimasi 0
    misal totalreal 100000
    ppn 10.000
    pph 1.000

    ppn termasuk total pph potong total
        --> kas berkurang 99000
        jurnal
            (beban=(total-ppn-pph))
            beban salah estimasi 89.000
            ppn 10.000
              kas/hutang prk 99.000 (total-pph)
              pph 1.000
      pph tidak potong total
      --> kas 100.000
      beban (total+pph-ppn)
      beban 91.000
      ppn 10.000
        kas/hutang 100.000
        pph 1.000

    ppn tidak termasuk total pph potong total
        --> kas berkurang 109.000
        (beban=total-pph)
        beban 99.000
        ppn 10.000
          kas/hutang 109.000
          pph 1.000

        pph tidak potong total
        --> kas 110.000
        beban (total+pph)
        beban 101.000
        ppn 10.000
          kas/hutang 110.000
          pph 1.000
    */
    if(empty($data['ppn'])){
      $data['ppn']=0;
    }

    if(empty($data['nilaipajak'])){
      $data['nilaipajak']=0;
    }

    if(empty($biaya)){
      if($data['statuspajakdimuka'] == 0){
        $data['ppn']=0;
      }
      if($data['statuspajak'] == 0){
        $data['nilaipajak']=0;
      }
      if($data['statuspajak'] == 1){
        $data['total'] -= $data['nilaipajak'];
      }

      if($data['statuspajakdimuka'] == 2){
        $data['total'] += $data['ppn'];
      }



      $j=array(
        'tglfaktur' => isset($data['tglfaktur'])?$data['tglfaktur']:date('Y-m-d H:i:s'),
        'order_id'  => $data['order_id'],
        'total' => 0,/*estimasi*/
        'totalreal' => $data['total'],
        'jenisbiaya_id' => $data['jenisbiaya_id'],
        'no_faktur' => $data['no_faktur'],
        'akunpajakdimuka' => $data['pajakdimuka'],
        'pajakdimuka' => $data['ppn'],
        'akunhutangpajak' => $data['pajak'],
        'hutangpajak' => $data['nilaipajak'],
        'statuspajakdimuka' => $data['statuspajakdimuka'],
        'statushutangpajak' => $data['statuspajak'],
        'perhitunganhpp'  => 1,
        'statuspembayaran'  => 1,
        'jatuhtempo'  => $data['jatuhtempo'],
        'totalbayar'  => 0,
        'vendor_id' => empty($data['vendor_id'])?0:$data['vendor_id']

      );
      $this->db->insert('biaya_pembelianimport',$j);
      $id=$this->db->getlastId();
    }else{
      if($biaya['statuspembayaran'] == 0 | $biaya['statuspembayaran'] == 4){
        if($data['statuspajak'] == 1){
          $data['total'] -= $data['nilaipajak'];
        }

        if($data['statuspajakdimuka'] == 2){
          $data['total'] += $data['ppn'];
        }


        $j=array(
          'tglfaktur' => isset($data['tglfaktur'])?$data['tglfaktur']:date('Y-m-d H:i:s'),
          'order_id'  => $data['order_id'],
          //'total' => $data['total'],
          'totalreal' => $data['total'],
          'jenisbiaya_id' => $data['jenisbiaya_id'],
          'no_faktur' => $data['no_faktur'],
          'akunpajakdimuka' => $data['pajakdimuka'],
          'pajakdimuka' => $data['ppn'],
          'akunhutangpajak' => $data['pajak'],
          'hutangpajak' => $data['nilaipajak'],
          'statuspajakdimuka' => $data['statuspajakdimuka'],
          'statushutangpajak' => $data['statuspajak'],
          'perhitunganhpp'  => 1,
          'statuspembayaran'  => 1,
          'jatuhtempo'  => $data['jatuhtempo'],
          'totalbayar'  => 0,
          'vendor_id' => empty($data['vendor_id'])?0:$data['vendor_id']

        );
        $this->db->update('biaya_pembelianimport',$j,array('id'=>$biaya['id']));


      }
    }

  }
  /*
  pembatalan hanya bisa dilakukan jika status belum ada tagihan atau ditagih
  jika estimasi 0 maka hutang dagang tidak berkurang karena berarti belum dicatat di hutang dagang
  jika estimasi lebih dari 0 maka hutang dagang berkurang senilai estimasi
  debet hutang dagang
  kredit penghasilan salah estimasi hpp
  */


  public function batalInvoice($data){
    $result=$this->getPenjualanDetail(array(),array(),array('id'  => $data['id']));
    $this->load->model('pembelian/invoicepembelianimport');
    //$inv=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelian(array(),array(),array('id'=>$result['order_id']));
  if($result['statuspembayaran'] == 0 | $result['statuspembayaran'] == null /*& $result['inputpib'] != 1 & $result['statuspembayaranpib'] != 1 & $result['inputkursdatang'] != 1*/){
      if($result['total'] == 0){
        $inv=array(
          'statuspembayaran'  => 4
        );
        $this->db->update('biaya_pembelianimport',$inv,array('id'  => $data['id']));
      }else{
        $this->load->model('pembelian/invoicepembelianimport');
        $inv=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelian(array(),array(),array('id'  => $result['order_id']));
        if($inv['statuspenerimaan'] == 1){
          $this->load->model('keuangan/jurnal');
          /*debet hutang dagang
          kredit 7002*/
          $details=array();
          $details[]=array(
            'ref_akun'  => '2101',
            'keterangan'  => 'Hutang Usaha',
            'debet' => $result['total'],
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );


          $details[]=array(
            'ref_akun'  => '7002',
            //'jenis_akun'  => 52,
            'keterangan'  => 'Pendapatan Salah Estimasi HPP',
            'debet' => 0,
            'kredit'  => $result['total'],
            'urutan'  => 2,
            'hapus' => 0
          );


          $j=array(
            'tanggal' => isset($result['tglfaktur'])?$result['tglfaktur']:date('Y-m-d H:i:s'),
            'keterangan'  => $this->db->escape('Pembatalan biaya pembelian import'),
            'details' => $details,
            'hapus' =>0,
            'ref' => $data['id'],
            'type'  => 5002,/*pembatalan biaya pembelian import*/
            'idref' => $data['id'],
            'no_dokumen'  => $result['no_dokumen'],
            'urlref'  => 'pembelian/tagihanbiayaimport'
          );
          $this->model_keuangan_jurnal->addJurnalUmum($j);
        }
        $inv=array(
          'statuspembayaran'  => 4
        );
        $this->db->update('biaya_pembelianimport',$inv,array('id'  => $data['id']));
      }

    }

  }





  public function updatePenjualan($data,$where=array()){
	$this->db->update('biaya_pembelianimport',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('biaya_pembelianimport',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order=array()){
		return $this->db->firstdetail('biaya_pembelianimport',$column,$join,$where,$order);
	}


  //public function getTotal

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('biaya_pembelianimport',$column,$join,$where,array());
  }

  public function getPermintaanPembelians($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('biaya_pembelianimport',$column,$join,$leftjoin,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where,$join=array(),$leftjoin=array()){
		return $this->db->countAll('biaya_pembelianimport',$where,$join,$leftjoin);
	}
	public function getPenjualans($column=array(),$join=array(),$where=array(),$order,$limit,$offset){

		return $this->db->alljoin('biaya_pembelianimport',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where){
		return $this->db->count('biaya_pembelianimport',$where);
	}
  public function totalPenjualanDetail($where,$join){
    return $this->db->countAll('biaya_pembelianimport',$where,$join);
  }

  public function batalkanPembayaran($id){
    $data=$this->db->firstdetail('pembayaran_tagihan_biayaimport',array(),array(),array('pembayaran_id'=>$id),array());
    if($this->user->getUsername()=="pawit"){
      $this->load->model('keuangan/bank');
      $biaya=$this->getTagihanBiaya(array(),array(),array('id'=>$data['order_id']));
      $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$data['bank_id']));
      $post=array(
        'bank'=>$b,
        'data'=>$data,
        'biaya'=>$biaya
      );
      echo "<pre>";print_r($post);exit;
    }
    if($data['status'] == 1){
      $this->load->model('keuangan/bank');
      $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$data['bank_id']));
      $saldo=$b['saldo'] + $data['nominal'];
      $this->model_keuangan_bank->updateBank(array('saldo' => $saldo),array('id'  => $data['bank_id']));
      $curb=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$data['bank_id']));
      $sal=$curb['saldo'];
      //$this->load->model('keuangan/tagihanbiaya');
      $biaya=$this->getTagihanBiaya(array(),array(),array('id'=>$data['order_id']));
      $ak=array(
        'date_added' => date('Y-m-d H:i:s'),
        'date_trans'  => isset($data['tgl_bayar'])?$data['tgl_bayar']:date('Y-m-d H:i:s'),
        'bank_id' => $data['bank_id'],
        'saldokeluar'  => 0,
        'saldomasuk' => $data['nominal'],
        'saldoawal' => $b['saldo'],
        'saldoakhir'  => $sal,
        'ref' => $id,
        'keterangan'  => $this->db->escape('Pembatalan Pembayaran Biaya Pembelian Import'),
        'type'  => 5005,
        'date_modified' => date('Y-m-d H:i:s'),
        'ref_akun'  => $curb['rek_parent'],
        'jurnal_id' => 0,
        'idref' => $data['order_id'],
        'no_dokumen'  => $data['no_dokumen'],
       'urlref'  => 'pembelian/tagihanbiayaimport'
      );
      $this->model_keuangan_bank->addAruskas($ak);
      $totalbayar=$biaya['totalbayar'] - $data['nominal'];
      /*if($totalbayar == $biaya['totalreal']){
        $status=1;
        $this->db->update('biaya_pembelianimport',array('statuspembayaran'=>$status,'totalbayar'=>$totalbayar),array('id'  => $data['order_id']));
        $this->db->update('tagihan_biaya_import',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'  => $data['order_id']));
      }else{
        if($totalbayar > 0){
          $status=2;
        }else{
          $status=3;
        }
        $this->db->update('biaya_pembelianimport',array('statuspembayaran'=>$status,'totalbayar'=>$totalbayar),array('id'  => $data['order_id']));
        $this->db->update('tagihan_biaya_import',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'  => $data['order_id']));
      }*/
      /*
      if($biaya['statuspembayaran'] == 3){
        $ju=$this->db->first('jurnal_umum',array('ref'=>$id,'type'=>5005));
        $this->db->delete('jurnal_umum_detail',array('jurnal_id'=>$ju['id']));
        $this->db->delete('jurnal_umum',array('id'=>$ju['id']));
      }*/
      if($totalbayar>0){
        $status=2;
      }else{
        $status=1;
      }
      $this->db->update('biaya_pembelianimport',array('statuspembayaran'=>$status,'totalbayar'=>$totalbayar),array('tagihan_id'  => $data['order_id']));
      $this->db->update('tagihan_biaya_import',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'  => $data['order_id']));
      $this->db->update('pembayaran_tagihan_biayaimport',array('status'=>4),array('pembayaran_id'  => $data['pembayaran_id'])); // status 4 dibatalkan
      $this->load->model('keuangan/jurnal');
      $jurnal=array();
      $detail=array();
      $detail[]=array(
        'ref_akun'  =>$b['rek_parent'],
        'keterangan'  => $this->db->escape('Pembatalan Pembayaran Biaya Pembelian'),
        'debet'  =>$biaya['totalbayar'],
        'kredit' =>0,
        'urutan'  =>1,
      );
      $detail[]=array(
        'ref_akun'  => '2101',
        'keterangan'  => $this->db->escape('Pembatalan Pembayaran Biaya Pembelian'),
        'debet'  => 0,
        'kredit' => $data['nominal'],
        'urutan'  =>2,
      );
      $jurnal=array(
        'tanggal' => $data['tgl_bayar'],
        'keterangan'  => 'Pembatalan Pembayaran Biaya Pembelian  '.$biaya['keterangan'],
        'hapus' => 0,
        'ref' => $data['order_id'],
        'type'  => 5005,
        'details'  => $detail,
        'no_dokumen'=>$data['no_dokumen'],
      );
      $this->model_keuangan_jurnal->addJurnalUmum($jurnal);
      $this->db->update('pembayaran_tagihan_biayaimport',array('status'=>2),array('pembayaran_id'=>$id));
    }
    return $data;
  }

  public function addPembayaran($data){
    $this->load->model('keuangan/bank');
    $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$data['bank_id']));
    $saldo=$b['saldo'] - $data['nominal'];

    $this->model_keuangan_bank->updateBank(array('saldo' => $saldo),array('id'  => $data['bank_id']));
    $curb=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$data['bank_id']));
    $sal=$curb['saldo'];

    //$this->load->model('keuangan/tagihanbiaya');
    $biaya=$this->getPermintaanPembelian(array(),array(),array('id'=>$data['order_id']));

    $p=array(
      'order_id' => $data['order_id'],
      'nominal'  => $data['nominal'],
      'tgl_bayar'  => $data['tgl_bayar'],
      'status'  => 1,
      'keterangan'  => $this->db->escape($data['keterangan']),
      'bank_id' => $data['bank_id'],
      /*'akun_hutang' => $biaya['akun_hutang'],
      'akun_kas'  =>$curb['rek_parent']*/
    );

    $this->db->insert('pembayaran_biayaimport',$p);
    $id=$this->db->getLastId();

    $ak=array(
      'date_added' => date('Y-m-d H:i:s'),
      'date_trans'  => isset($data['tgl_bayar'])?$data['tgl_bayar']:date('Y-m-d H:i:s'),
      'bank_id' => $data['bank_id'],
      'saldomasuk'  => 0,
      'saldokeluar' => $data['nominal'],
      'saldoawal' => $b['saldo'],
      'saldoakhir'  => $sal,
      'ref' => $id,
      'keterangan'  => $this->db->escape($data['keterangan']),
      'type'  => 5005,
      'date_modified' => date('Y-m-d H:i:s'),
      'ref_akun'  => $curb['rek_parent']
    );


    $this->model_keuangan_bank->addAruskas($ak);

    //update total bayar
    //$this->load->model('keuangan/tagihan');

    //$biaya=$this->model_keuangan_tagihanbiaya->getPermintaanPembelian(array(),array(),array('id'=>$data['order_id']));
    $totalbayar=$biaya['totalbayar'] + $data['nominal'];

    if($totalbayar == $biaya['totalreal']){
      $status=3;
      $this->db->update('biaya_pembelianimport',array('statuspembayaran'=>$status,'totalbayar'=>$totalbayar,'tgllunas'=>$data['tgl_bayar']),array('id'  => $data['order_id']));
    }else{
      $status=2;
      $this->db->update('biaya_pembelianimport',array('statuspembayaran'=>$status,'totalbayar'=>$totalbayar),array('id'  => $data['order_id']));
    }

    //jurnal
    $this->load->model('keuangan/jurnal');

    /*
    jika estimasi 0
    misal totalreal 100000
    ppn 10.000
    pph 1.000

    ppn termasuk total pph potong total
        --> kas berkurang 99000
        jurnal
            (beban=(total-ppn-pph))
            beban salah estimasi 89.000
            ppn 10.000
              kas/hutang prk 99.000 (total-pph)
              pph 1.000
      pph tidak potong total
      --> kas 100.000
      beban (total+pph-ppn)
      beban 91.000
      ppn 10.000
        kas/hutang 100.000
        pph 1.000

    ppn tidak termasuk total pph potong total
        --> kas berkurang 109.000
        (beban=total-pph)
        beban 99.000
        ppn 10.000
          kas/hutang 109.000
          pph 1.000

        pph tidak potong total
        --> kas 110.000
        beban (total+pph)
        beban 101.000
        ppn 10.000
          kas/hutang 110.000
          pph 1.000

    debet beban salah estimasi hpp estimasi
          pajakdimuka pajak
    kredit kas nominal
          hutang pajak hutangpajak

    jika estimasi lebih dari 0
    jika total real > estimasi
    selisih= total real - estimasi

    berarti ada rugi salah estimasi hpp
    debet beban salah estimasi hpp selisih
          pajakdimuka pajak
          hutang dagang total
    kredit kas total real
          hutang pajak hutangpajak

    jika total real < estimasi
    selisih=estimasi - total real

    debet hutang dagang total
          pajakdimuka pajak
    kredit kas total real
         pendapatan salah estimasi hpp selisih
         hutang pajak hutangpajak
    */

    $jurnal=array();
    $detail=array();
    //if($data['nominal'] > 0){
      if($status == 3){
        if($biaya['total'] == 0){
          $detail[]=array(
            'ref_akun'  => '8002',
            'keterangan'  => $this->db->escape('Beban salah estimasi HPP'),
            'debet' => ($biaya['totalreal']-$biaya['pajakdimuka']+$biaya['hutangpajak']),
            'kredit'  => 0,
            'urutan'  => 1,
          );
          if($biaya['akunpajakdimuka'] > 0){
            if($biaya['pajakdimuka'] > 0){
              $detail[]=array(
                'ref_akun'  => $biaya['akunpajakdimuka'],
                'keterangan'  => $this->db->escape('Pajak dibayar dimuka'),
                'debet' => $biaya['pajakdimuka'],
                'kredit'  => 0,
                'urutan'  => 2,
              );
            }
          }

          if($b['saldo'] < $totalbayar){
            if($b['hutangprk'] == 1){
              if($b['saldo'] > 0){
                $hutangprk=abs($b['saldo'] - $totalbayar);
                $detail[]=array(
                  'ref_akun'  => $curb['rek_parent'],
                  'keterangan'  => $this->db->escape('Pembayaran Biaya Pembelian Import'),
                  'kredit' => $b['saldo'],
                  'debet'  => 0,
                  'urutan'  => 3,
                );
                $detail[]=array(
                  'ref_akun'  => '2001',
                  'keterangan'  => $this->db->escape('Pembayaran Hutang'),
                  'kredit' => $hutangprk,
                  'debet'  => 0,
                  'urutan'  => 4,
                );
              }else{
                $hutangprk=$totalbayar;
                $detail[]=array(
                  'ref_akun'  => '2001',
                  'keterangan'  => $this->db->escape('Pembayaran Hutang'),
                  'kredit' => $hutangprk,
                  'debet'  => 0,
                  'urutan'  => 3,
                );
              }


            }else{
              $detail[]=array(
                'ref_akun'  => $curb['rek_parent'],
                'keterangan'  => $this->db->escape('Pembayaran Biaya pembelian import'),
                'kredit' => $totalbayar,
                'debet'  => 0,
                'urutan'  => 3,
              );
            }
          }else{
            $detail[]=array(
              'ref_akun'  => $curb['rek_parent'],
              'keterangan'  => $this->db->escape('Pembayaran Biaya iklan dan promosi'),
              'kredit' => $totalbayar,
              'debet'  => 0,
              'urutan'  => 3,
            );
          }
          if($biaya['akunhutangpajak'] > 0){
            if($biaya['hutangpajak'] > 0){
              $detail[]=array(
                'ref_akun'  => $biaya['akunhutangpajak'],
                'keterangan'  => $this->db->escape('Hutang Pajak'),
                'kredit' => $biaya['hutangpajak'],
                'debet'  => 0,
                'urutan'  => 4,
              );
            }
          }
        }else{
          if($biaya['total'] < $biaya['totalreal']){
            $selisih=$biaya['totalreal'] - $biaya['total'];

            $detail[]=array(
              'ref_akun'  => '2101',
              'keterangan'  => $this->db->escape('Hutang Dagang'),
              'debet' => $biaya['total'],
              'kredit'  => 0,
              'urutan'  => 1,
            );
              $detail[]=array(
                'ref_akun'  => '8002',
                'keterangan'  => $this->db->escape('Beban salah estimasi HPP'),
                'debet' => $selisih-$biaya['pajakdimuka']+$biaya['hutangpajak'],
                'kredit'  => 0,
                'urutan'  => 2,
              );
              if($biaya['akunpajakdimuka'] > 0){
                if($biaya['pajakdimuka'] > 0){
                  $detail[]=array(
                    'ref_akun'  => $biaya['akunpajakdimuka'],
                    'keterangan'  => $this->db->escape('Pajak dibayar dimuka'),
                    'debet' => $biaya['pajakdimuka'],
                    'kredit'  => 0,
                    'urutan'  => 3,
                  );
                }
              }

              if($b['saldo'] < $totalbayar){
                if($b['hutangprk'] == 1){
                  if($b['saldo'] > 0){
                    $hutangprk=abs($b['saldo'] - $totalbayar);
                    $detail[]=array(
                      'ref_akun'  => $curb['rek_parent'],
                      'keterangan'  => $this->db->escape('Pembayaran Biaya Pembelian Import'),
                      'kredit' => $b['saldo'],
                      'debet'  => 0,
                      'urutan'  => 3,
                    );
                    $detail[]=array(
                      'ref_akun'  => '2001',
                      'keterangan'  => $this->db->escape('Pembayaran Hutang'),
                      'kredit' => $hutangprk,
                      'debet'  => 0,
                      'urutan'  => 4,
                    );
                  }else{
                    $hutangprk=$totalbayar;
                    $detail[]=array(
                      'ref_akun'  => '2001',
                      'keterangan'  => $this->db->escape('Pembayaran Hutang'),
                      'kredit' => $hutangprk,
                      'debet'  => 0,
                      'urutan'  => 3,
                    );
                  }


                }else{
                  $detail[]=array(
                    'ref_akun'  => $curb['rek_parent'],
                    'keterangan'  => $this->db->escape('Pembayaran Biaya pembelian import'),
                    'kredit' => $totalbayar,
                    'debet'  => 0,
                    'urutan'  => 3,
                  );
                }
              }else{
                $detail[]=array(
                  'ref_akun'  => $curb['rek_parent'],
                  'keterangan'  => $this->db->escape('Pembayaran Biaya iklan dan promosi'),
                  'kredit' => $totalbayar,
                  'debet'  => 0,
                  'urutan'  => 3,
                );
              }
              if($biaya['akunhutangpajak'] > 0){
                if($biaya['hutangpajak'] > 0){
                  $detail[]=array(
                    'ref_akun'  => $biaya['akunhutangpajak'],
                    'keterangan'  => $this->db->escape('Hutang Pajak'),
                    'kredit' => $biaya['hutangpajak'],
                    'debet'  => 0,
                    'urutan'  => 4,
                  );
                }
              }
        }
        if($biaya['total'] > $biaya['totalreal']){
          $selisih=$biaya['total'] - $biaya['totalreal']+$biaya['hutangpajak'];

          $detail[]=array(
            'ref_akun'  => '2101',
            'keterangan'  => $this->db->escape('Hutang Dagang'),
            'debet' => $biaya['total']-$biaya['pajakdimuka'],
            'kredit'  => 0,
            'urutan'  => 1,
          );

            if($biaya['akunpajakdimuka'] > 0){
              if($biaya['pajakdimuka'] > 0){
                $detail[]=array(
                  'ref_akun'  => $biaya['akunpajakdimuka'],
                  'keterangan'  => $this->db->escape('Pajak dibayar dimuka'),
                  'debet' => $biaya['pajakdimuka'],
                  'kredit'  => 0,
                  'urutan'  => 3,
                );
              }
            }

            if($b['saldo'] < $totalbayar){
              if($b['hutangprk'] == 1){
                if($b['saldo'] > 0){
                  $hutangprk=abs($b['saldo'] - $totalbayar);
                  $detail[]=array(
                    'ref_akun'  => $curb['rek_parent'],
                    'keterangan'  => $this->db->escape('Pembayaran Biaya Pembelian Import'),
                    'kredit' => $b['saldo'],
                    'debet'  => 0,
                    'urutan'  => 3,
                  );
                  $detail[]=array(
                    'ref_akun'  => '2001',
                    'keterangan'  => $this->db->escape('Pembayaran Hutang'),
                    'kredit' => $hutangprk,
                    'debet'  => 0,
                    'urutan'  => 4,
                  );
                }else{
                  $hutangprk=$totalbayar;
                  $detail[]=array(
                    'ref_akun'  => '2001',
                    'keterangan'  => $this->db->escape('Pembayaran Hutang'),
                    'kredit' => $hutangprk,
                    'debet'  => 0,
                    'urutan'  => 3,
                  );
                }


              }else{
                $detail[]=array(
                  'ref_akun'  => $curb['rek_parent'],
                  'keterangan'  => $this->db->escape('Pembayaran Biaya pembelian import'),
                  'kredit' => $totalbayar,
                  'debet'  => 0,
                  'urutan'  => 3,
                );
              }
            }else{
              $detail[]=array(
                'ref_akun'  => $curb['rek_parent'],
                'keterangan'  => $this->db->escape('Pembayaran Biaya iklan dan promosi'),
                'kredit' => $totalbayar,
                'debet'  => 0,
                'urutan'  => 3,
              );
            }
            $detail[]=array(
              'ref_akun'  => '7002',
              'keterangan'  => $this->db->escape('Pendapatan salah estimasi HPP'),
              'kredit' => $selisih,
              'debet'  => 0,
              'urutan'  => 4,
            );
            if($biaya['akunhutangpajak'] > 0){
              if($biaya['hutangpajak'] > 0){
                $detail[]=array(
                  'ref_akun'  => $biaya['akunhutangpajak'],
                  'keterangan'  => $this->db->escape('Hutang Pajak'),
                  'kredit' => $biaya['hutangpajak'],
                  'debet'  => 0,
                  'urutan'  => 5,
                );
              }
            }
        }

      }
      $jurnal=array(
        'tanggal' => $data['tgl_bayar'],
        'keterangan'  => 'Pembayaran Biaya Pembelian  '.$biaya['keterangan'],
        'hapus' => 0,
        'ref' => $id,
        'type'  => 5005,
        'details'  => $detail
      );
      $this->model_keuangan_jurnal->addJurnalUmum($jurnal);


    }
  //}
}
public function getPembayaran($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
  return $this->db->alljoin('pembayaran_biayaimport',$column,$join,$where,$order,$limit,$offset);
}
public function getPembayaranDetail($column=array(),$join=array(),$where=array(),$order=array()){
  $this->db->firstdetail('pembayaran_biayaimport',$column,$join,$where,$order);
}

public function getTagihanBiayas($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
  return $this->db->alljoin('tagihan_biaya_import',$column,$join,$where,$order,$limit,$offset);
}

public function totalTagihanBiayas($where){
  return $this->db->countAll('tagihan_biaya_import',$where);
}
/*public function totalDp($no_po){
  return $this->db->firstdetail('tagihan_biaya',array('COALESCE(SUM(total)) as total'),array(),array('id' => $no_po),array());
}*/

public function getTagihanBiaya($column=array(),$join=array(),$where=array()){
  return $this->db->firstdetail('tagihan_biaya_import',$column,$join,$where,array());
}

public function getPembayaranTagihan($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
  return $this->db->alljoin('pembayaran_tagihan_biayaimport',$column,$join,$where,$order,$limit,$offset);
}

}
?>
