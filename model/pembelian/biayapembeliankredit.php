<?php
class ModelPembelianBiayapembeliankredit extends Model {
  /*
  0. belum ada tagihan
  1. ditagih
  2. belum lunas
  3. lunas
  4. dibatalkan
  */

  public function addTagihanBiaya($data){
    $this->log->write(json_encode($data));
    $tagihan=array(
      'date_added'  => date('Y-m-d H:i:s'),
      'keterangan'  => $this->db->escape($data['keterangan']),
      'nominal' => $data['nominal'],
      'status'  => 1,
      'user_id' => $this->user->getId(),
      'nilaipajak'  => empty($data['nilaipajak'])?0:$data['nilaipajak'],
      'total' => $data['total'],
      'vendor_id' => $data['vendor_id'],
      'totalbayar'  => 0,
      'no_faktur' => $this->db->escape($data['no_faktur']),
      'tgl_tagihan' => $data['tgl_tagihan'],
      'jatuhtempo'  => $data['jatuhtempo'],
      'pajak' => $data['pajak'],
      'statuspajak' => $data['statuspajak'],
      'ppn' => $data['ppn'],
      'ref' => $data['ref'],
      'pajakdimuka' => $data['pajakdimuka'],
      'statuspajakdimuka' => $data['statuspajakdimuka'],
      'totalestimasi' => $data['totalestimasi']
    );
    $this->db->insert('tagihan_biaya_lokal',$tagihan);

    $id=$this->db->getLastId();

    $no_dokumen='TBL-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;
    
    $this->db->update('tagihan_biaya_lokal',array('no_dokumen'=>$no_dokumen),array('id'=>$id));


    foreach($data['biaya'] as $b){
      if($b['jenisbiaya_id'] > 0){
      if($b['id'] > 0){
        $this->db->update('biaya_pembelian',array('tagihan_id'=> $id,'statuspembayaran'=>1,'totalreal'=> $b['totalreal'],'no_faktur'=>$data['no_faktur'],'tglfaktur'=> $data['tgl_tagihan'],'jatuhtempo'  => $data['jatuhtempo']),array('id'=>$b['id']));
      }else{
        $biaya=array(
          'order_id'  => $data['ref'],
          'total' => 0,
          'totalreal' => $b['totalreal'],
          'jenisbiaya_id' => $b['jenisbiaya_id'],
          'statuspembayaran'  =>1,
          'tagihan_id'  => $id,
          'no_faktur'=>$data['no_faktur'],
          'tglfaktur'=> $data['tgl_tagihan'],
          'jatuhtempo'  => $data['jatuhtempo'],
          'no_dokumen'  => $no_dokumen
        );
        $this->db->insert('biaya_pembelian',$biaya);
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
    /*if($data['statuspajakdimuka'] == 1){
      $total=$data['total']
    }*/
    $details=array();
    $details[]=array(
      'ref_akun'  => '2150',
      'keterangan'  => 'Hutang Belum Tertagih',
      'debet' => $data['totalestimasi'],
      'kredit'  => 0,
      'urutan'  => 1,
      'hapus' => 0
    );

    if($data['ppn'] > 0 & $data['pajakdimuka'] > 0){
      $details[]=array(
        'ref_akun'  => $data['pajakdimuka'],
        'keterangan'  => 'PPn Masukan',
        'debet' => $data['ppn'],
        'kredit'  => 0,
        'urutan'  => 2,
        'hapus' => 0
      );
    }
    if($data['statuspajak'] > 1){
      $details[]=array(
        'ref_akun'  => $data['statuspajak'],
        'keterangan'  => 'Biaya Pajak',
        'debet' => $data['nilaipajak'],
        'kredit'  => 0,
        'urutan'  => 3,
        'hapus' => 0
      );
    }
    if($data['total'] == 0){
      $data['total']=$data['totalestimasi'];
    }
    $total=$data['total'];

    if($data['statuspajak'] == 1){
        $total =$data['total'] + $data['nilaipajak'];
    }
    if(($total-$data['ppn']) > $data['totalestimasi']){
      $beban=($total-$data['ppn']) - $data['totalestimasi'];

      $details[]=array(
        'ref_akun'  => '8002',
        'keterangan'  => 'Beban Salah Hitung HPP',
        'debet' => $beban,
        'kredit'  => 0,
        'urutan'  => 4,
        'hapus' => 0
      );
    }
    /*if($data['totalestimasi'] > $total){
      $pendapatan=$data['totalestimasi'] - $total;
      $details[]=array(
        'ref_akun'  => '2101',
        'keterangan'  => 'Hutang Usaha',
        'kredit' => $pendapatan,
        'debet'  => 0,
        'urutan'  => 5,
        'hapus' => 0
      );
    }*/

    $details[]=array(
      'ref_akun'  => '2101',
      'keterangan'  => 'Hutang Usaha',
      'kredit' => $data['total'],
      'debet'  => 0,
      'urutan'  => 6,
      'hapus' => 0
    );
    if($data['nilaipajak'] > 0){
      $details[]=array(
        'ref_akun'  => $data['pajak'],
        'keterangan'  => $this->db->escape('Hutang Pajak'),
        'kredit' => $data['nilaipajak'],
        'debet'  => 0,
        'urutan'  => 7,
        'hapus' => 0
      );
    }
    if($data['totalestimasi'] > $total){
      $pendapatan=$data['totalestimasi'] - $total;
      $details[]=array(
        'ref_akun'  => '7002',
        'keterangan'  => 'Pendapatan Salah Hitung HPP',
        'kredit' => $pendapatan,
        'debet'  => 0,
        'urutan'  =>8,
        'hapus' => 0
      );
    }

    $j=array(
      'tanggal' => isset($data['tgl_tagihan'])?$data['tgl_tagihan']:date('Y-m-d'),
      'keterangan'  => 'Invoice Biaya Pembelian Produk Lokal No Faktur '.$data['no_faktur'],
      'details' => $details,
      'hapus' =>0,
      'ref' => $id,
      'type'  => 33001,
      'urlref' => 'pembelian/tagihanbiayalokal',
      'idref' => $id,
      'no_dokumen'  => $no_dokumen
    );
    $this->model_keuangan_jurnal->addJurnalUmum($j);
  }

  public function addPenjualan($data){
    //cek biaya
    $biaya=$this->getPenjualanDetail(array(),array(),array('order_id'=>$data['order_id'],'jenisbiaya_id'=>$data['jenisbiaya_id']));



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
        //'perhitunganhpp'  => 1,
        'statuspembayaran'  => 1,
        'jatuhtempo'  => $data['jatuhtempo'],
        'totalbayar'  => 0

      );
      $this->db->insert('biaya_pembelian',$j);
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
          //'perhitunganhpp'  => 1,
          'statuspembayaran'  => 1,
          'jatuhtempo'  => $data['jatuhtempo'],
          'totalbayar'  => 0

        );
        $this->db->update('biaya_pembelian',$j,array('id'=>$biaya['id']));


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
    $this->load->model('pembelian/invoicepembeliankredit');
    $this->load->model('pembelian/pembeliankredit');
    //$inv=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelian(array(),array(),array('id'=>$result['order_id']));
    if($result['statuspembayaran'] == 0 | $result['statuspembayaran'] == 1 /*& $result['inputpib'] != 1 & $result['statuspembayaranpib'] != 1 & $result['inputkursdatang'] != 1*/){
      if($result['total'] == 0){
        $inv=array(
          'statuspembayaran'  => 4
        );
        $this->db->update('biaya_pembelian',$inv,array('id'  => $data['id']));
      }else{
        $this->load->model('pembelian/invoicepembelianimport');
        $inv=$this->model_pembelian_invoicepembelianimport->getPermintaanPembelian(array(),array(),array('id'  => $result['order_id']));
        if($inv['statuspenerimaan'] != 2 & $inv['statuspenerimaan'] != 3){
          $inv=array(
            'statuspembayaran'  => 4
          );
          $this->db->update('biaya_pembelian',$inv,array('id'  => $data['id']));
        }

      }

    }

  }





  public function updatePenjualan($data,$where=array()){
	$this->db->update('biaya_pembelian',$data,$where);
	}
	public function getPenjualan($where){
		return $this->db->first('biaya_pembelian',$where);
	}
  public function getPenjualanDetail($column=array(),$join=array(),$where=array(),$order=array()){
		return $this->db->firstdetail('biaya_pembelian',$column,$join,$where,$order);
	}


  //public function getTotal

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('biaya_pembelian',$column,$join,$where,array());
  }

  public function getPermintaanPembelians($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('biaya_pembelian',$column,$join,$leftjoin,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where,$join=array(),$leftjoin=array()){
		return $this->db->countAll('biaya_pembelian',$where,$join,$leftjoin);
	}
	public function getPenjualans($column=array(),$join=array(),$where=array(),$order,$limit,$offset){

		return $this->db->alljoin('biaya_pembelian',$column,$join,$where,$order,$limit,$offset);
	}
	public function totalPenjualans($where){
		return $this->db->count('biaya_pembelian',$where);
	}
  public function totalPenjualanDetail($where,$join){
    return $this->db->countAll('biaya_pembelian',$where,$join);
  }

  public function batalkanPembayaran($id){
    $data=$this->db->firstdetail('pembayaran_biayakredit',array(),array(),array('pembayaran_id'=>$id),array());
    if($data['status'] == 1){
      $this->load->model('keuangan/bank');
      $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$data['bank_id']));
      $saldo=$b['saldo'] + $data['nominal'];

      $this->model_keuangan_bank->updateBank(array('saldo' => $saldo),array('id'  => $data['bank_id']));
      $curb=$this->model_keuangan_bank->getBank(array(),array(),array('id'=>$data['bank_id']));
      $sal=$curb['saldo'];

      //$this->load->model('keuangan/tagihanbiaya');
      $biaya=$this->getPermintaanPembelian(array(),array(),array('id'=>$data['order_id']));

      $ak=array(
        'date_added' => date('Y-m-d H:i:s'),
        'date_trans'  => isset($data['tgl_bayar'])?$data['tgl_bayar']:date('Y-m-d H:i:s'),
        'bank_id' => $data['bank_id'],
        'saldokeluar'  => 0,
        'saldomasuk' => $data['nominal'],
        'saldoawal' => $b['saldo'],
        'saldoakhir'  => $sal,
        'ref' => $id,
        'keterangan'  => $this->db->escape('Pembatalan Pembayaran Biaya Pembelian Lokal'),
        'type'  => 5025,
        'date_modified' => date('Y-m-d H:i:s'),
        'ref_akun'  => $curb['rek_parent'],
        'idref' => $data['order_id'],
      'no_dokumen'  => $data['no_dokumen'],
       'urlref'  => 'pembelian/tagihanbiayalokal'
      );


      $this->model_keuangan_bank->addAruskas($ak);

      $totalbayar=$biaya['totalbayar'] - $data['nominal'];

      if($totalbayar == $biaya['totalreal']){
        $status=3;
        $this->db->update('biaya_pembelian',array('statuspembayaran'=>$status,'totalbayar'=>$totalbayar,'tgllunas'=>$data['tgl_bayar']),array('id'  => $data['order_id']));
      }else{
        if($totalbayar > 0){
          $status=2;
        }else{
          $status=1;
        }

        $this->db->update('biaya_pembelian',array('statuspembayaran'=>$status,'totalbayar'=>$totalbayar),array('id'  => $data['order_id']));
      }
      if($biaya['statuspembayaran'] == 3){
        $ju=$this->db->first('jurnal_umum',array('ref'=>$id,'type'=>5025));
        $this->db->delete('jurnal_umum_detail',array('jurnal_id'=>$ju['id']));
        $this->db->delete('jurnal_umum',array('id'=>$ju['id']));
      }
      $this->db->update('pembayaran_biayakredit',array('status'=>2),array('pembayaran_id'=>$id));
    }
    return $data;
  }

  public function addPembayaran($data){
    $this->load->model('keuangan/bank');
    $this->load->model('pembelian/barangdatangdagang');
    $this->load->model('pembelian/pembeliankredit');

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

    $this->db->insert('pembayaran_biayakredit',$p);
    $id=$this->db->getLastId();
    $no_dokumen='BBL-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;
    $this->db->update('pembayaran_tagihan_biayaimport',array('no_dokumen'=>$no_dokumen),array('pembayaran_id'=>$id));
    
    

    //update total bayar
    //$this->load->model('keuangan/tagihan');

    //$biaya=$this->model_keuangan_tagihanbiaya->getPermintaanPembelian(array(),array(),array('id'=>$data['order_id']));
    $totalbayar=$biaya['totalbayar'] + $data['nominal'];

    if($totalbayar == $biaya['totalreal']){
      $status=3;
      $this->db->update('biaya_pembelian',array('statuspembayaran'=>$status,'totalbayar'=>$totalbayar,'tgllunas'=>$data['tgl_bayar']),array('id'  => $data['order_id']));
    }else{
      $status=2;
      $this->db->update('biaya_pembelian',array('statuspembayaran'=>$status,'totalbayar'=>$totalbayar),array('id'  => $data['order_id']));
    }

    //jurnal
    $this->load->model('keuangan/jurnal');

    /*
    cek status penerimaan barang
    kalau sudah diterima jurnalnya persediaan x kas
    update net cost

    kalau belum diterima uang muka x kas

    kalau baru diterima sebagian

    sejumlah yang diterima
      persediaan x kas
      update net cost

    sisanya uang muka x kas

    */

    $jurnal=array();
    $detail=array();

    $uangmuka=$totalbiaya-$nilaipersediaan;

    $detail[]=array(
      'ref_akun'  => $jenispersediaan,
      'keterangan'  => $this->db->escape('Persediaan'),
      'debet' => $nilaipersediaan,
      'kredit'  => 0,
      'urutan'  => 1,
    );

    if($uangmuka > 0){
      $detail[]=array(
        'ref_akun'  => '1311',
        'keterangan'  => $this->db->escape('Uang Muka'),
        'debet' => $uangmuka,
        'kredit'  => 0,
        'urutan'  => 2,
      );
    }


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
              'keterangan'  => $this->db->escape('Pembayaran Biaya Pembelian Lokal'),
              'kredit' => $b['saldo'],
              'debet'  => 0,
              'urutan'  => 4,
            );
            $detail[]=array(
              'ref_akun'  => '2001',
              'keterangan'  => $this->db->escape('Hutang PRK'),
              'kredit' => $hutangprk,
              'debet'  => 0,
              'urutan'  => 5,
            );
          }else{
            $hutangprk=$totalbayar;
            $detail[]=array(
              'ref_akun'  => '2001',
              'keterangan'  => $this->db->escape('Hutang PRK'),
              'kredit' => $hutangprk,
              'debet'  => 0,
              'urutan'  => 6,
            );
          }


        }else{
          $detail[]=array(
            'ref_akun'  => $curb['rek_parent'],
            'keterangan'  => $this->db->escape('Pembayaran Biaya pembelian lokal'),
            'kredit' => $totalbayar,
            'debet'  => 0,
            'urutan'  => 7,
          );
        }
      }else{
        $detail[]=array(
          'ref_akun'  => $curb['rek_parent'],
          'keterangan'  => $this->db->escape('Pembayaran Biaya pembelian lokal'),
          'kredit' => $totalbayar,
          'debet'  => 0,
          'urutan'  => 8,
        );
      }
      if($biaya['akunhutangpajak'] > 0){
        if($biaya['hutangpajak'] > 0){
          $detail[]=array(
            'ref_akun'  => $biaya['akunhutangpajak'],
            'keterangan'  => $this->db->escape('Hutang Pajak'),
            'kredit' => $biaya['hutangpajak'],
            'debet'  => 0,
            'urutan'  => 9,
          );
        }
      }

  $jurnal=array(
    'tanggal' => $data['tgl_bayar'],
    'keterangan'  => 'Pembayaran Biaya Pembelian Lokal  '.$biaya['keterangan'],
    'hapus' => 0,
    'ref' => $id,
    'type'  => 5025,
    'details'  => $detail,
    'idref' => $biaya['id'],
    'no_dokumen'  => $no_dokumen,
    'urlref'  => 'pembelian/tagihanbiayalokal'
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
    'type'  => 5010,
    'date_modified' => date('Y-m-d H:i:s'),
    'ref_akun'  => $curb['rek_parent'],
    'jurnal_id' => $jurnal_id,
    'idref' => $biaya['id'],
    'no_dokumen'  => $no_dokumen,
    'urlref'  => 'pembelian/tagihanbiayalokal'
  );


  $this->model_keuangan_bank->addAruskas($ak);

}
public function getPembayaran($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
  return $this->db->alljoin('pembayaran_biayakredit',$column,$join,$where,$order,$limit,$offset);
}
public function getPembayaranDetail($column=array(),$join=array(),$where=array(),$order=array()){
  $this->db->firstdetail('pembayaran_biayakredit',$column,$join,$where,$order);
}

public function getTagihanBiayas($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
  return $this->db->alljoin('tagihan_biaya_lokal',$column,$join,$where,$order,$limit,$offset);
}

public function totalTagihanBiayas($where){
  return $this->db->countAll('tagihan_biaya_lokal',$where);
}
/*public function totalDp($no_po){
  return $this->db->firstdetail('tagihan_biaya',array('COALESCE(SUM(total)) as total'),array(),array('id' => $no_po),array());
}*/

public function getTagihanBiaya($column=array(),$join=array(),$where=array()){
  return $this->db->firstdetail('tagihan_biaya_lokal',$column,$join,$where,array());
}

public function getPembayaranTagihan($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
  return $this->db->alljoin('pembayaran_tagihan_biayalokal',$column,$join,$where,$order,$limit,$offset);
}
public function batalkanTagihan($data){
  $result=$this->getTagihanBiaya(array(),array(),array('id'  => $data['id']));
  if($result['status'] == 1){
    //status tagihan harus 1
    //batalkan status di tagihan, di tabel biaya pembelian hapus tunjangan_id, no_faktur, totalreal, status pembayaran jadi 0
    $this->db->update('biaya_pembelian',array('tagihan_id'=>0,'no_faktur'=>null,'totalreal' => 0,'statuspembayaran'=>0),array('tagihan_id'  => $data['id']));
    $this->db->update('tagihan_biaya_lokal',array('status'=>4),array('id'  => $data['id']));

    $ju=$this->db->first('jurnal_umum',array('ref'=>$data['id'],'type'=>33001));
    if(!empty($ju)){
      $this->db->delete('jurnal_umum',array('id'=>$ju['id']));
      $this->db->delete('jurnal_umum_detail',array('jurnal_id'=>$ju['id']));
    }

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
    /*'akun_hutang' => $biaya['akun_hutang'],
    'akun_kas'  =>$curb['rek_parent']*/
  );

  $this->db->insert('pembayaran_tagihan_biayalokal',$p);
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
    'type'  => 5007,
    'date_modified' => date('Y-m-d H:i:s'),
    'ref_akun'  => $curb['rek_parent']
  );


  $this->model_keuangan_bank->addAruskas($ak);

  //update total bayar
  //$this->load->model('keuangan/tagihan');

  //$biaya=$this->model_keuangan_tagihanbiaya->getPermintaanPembelian(array(),array(),array('id'=>$data['order_id']));
  $totalbayar=$biaya['totalbayar'] + $data['nominal'];

  if($totalbayar == $biaya['total']){
    $status=3;
    $this->db->update('biaya_pembelian',array('statuspembayaran'=>$status,'tgllunas'=>$data['tgl_bayar']),array('tagihan_id'  => $data['order_id']));
    $this->db->update('tagihan_biaya_lokal',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'  => $data['order_id']));
  }else{
    $status=2;
    $this->db->update('biaya_pembelian',array('statuspembayaran'=>$status),array('id'  => $data['order_id']));
    $this->db->update('tagihan_biaya_lokal',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'  => $data['order_id']));
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
          'keterangan'  => $this->db->escape('Pembayaran Biaya Pembelian Lokal'),
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
        'keterangan'  => $this->db->escape('Pembayaran Biaya pembelian lokal'),
        'kredit' => $data['nominal'],
        'debet'  => 0,
        'urutan'  => 3,
      );
    }
  }else{
    $detail[]=array(
      'ref_akun'  => $curb['rek_parent'],
      'keterangan'  => $this->db->escape('Pembayaran Biaya Pembelian Lokal'),
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
      'type'  => 5007,
      'details'  => $detail
    );
    $this->model_keuangan_jurnal->addJurnalUmum($jurnal);



//}
}
public function batalkanTagihanPembayaran($id){
  $data=$this->db->firstdetail('pembayaran_tagihan_biayalokal',array(),array(),array('pembayaran_id'=>$id),array());
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
      'keterangan'  => $this->db->escape('Pembatalan Pembayaran Biaya Pembelian Lokal'),
      'type'  => 5007,
      'date_modified' => date('Y-m-d H:i:s'),
      'ref_akun'  => $curb['rek_parent']
    );


    $this->model_keuangan_bank->addAruskas($ak);

    $totalbayar=$biaya['totalbayar'] - $data['nominal'];

    if($totalbayar == $biaya['total']){
      $status=3;
      $this->db->update('biaya_pembelian',array('statuspembayaran'=>$status),array('id'  => $data['order_id']));
      $this->db->update('tagihan_biaya_lokal',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'  => $data['order_id']));
    }else{
      if($totalbayar > 0){
        $status=2;
      }else{
        $status=1;
      }

      if($biaya['status'] == 3){
        $this->db->update('biaya_pembelian',array('statuspembayaran'=>$status),array('id'  => $data['order_id']));
      }

      $this->db->update('tagihan_biaya_lokal',array('status'=>$status,'totalbayar'=>$totalbayar),array('id'  => $data['order_id']));
    }
  //  if($biaya['stat'] == 3){
      $ju=$this->db->first('jurnal_umum',array('ref'=>$id,'type'=>5007));
      $this->db->delete('jurnal_umum_detail',array('jurnal_id'=>$ju['id']));
      $this->db->delete('jurnal_umum',array('id'=>$ju['id']));
    //}
    $this->db->update('pembayaran_tagihan_biayalokal',array('status'=>2),array('pembayaran_id'=>$id));
  }
  return $data;
}
}
?>
