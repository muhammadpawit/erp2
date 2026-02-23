<?php
class ModelKeuanganIklanperiodik extends Model {
  public function addPembelian($data){
    /*
    Status
    1 belum dibayar
    2 dibayar sebagian
    3 lunas
    4 dibatalkan
    */

    /*
    proses periodik
    jumlah sudah diproses tagihan periodik bulanan
    */
    if(empty($data['tglakhir'])){
      $data['tglakhir']=date('Y-m-d');
    }

    if(empty($data['tglawal'])){
      $data['tglawal']=date('Y-m-t',time());
      if($data['masaberlaku'] > 1){
        $data['tglakhir']=date('Y-m-t',strtotime("+".$data['masaberlaku']."months"));
      }
    }

    $total=$data['nilaisewa']+$data['ppn'];
    /*if($data['statuspajak'] == 2){
      $total=$total+$data['nilaipajak'];
    }else if($data['statuspajak'] == 1){
      $total=$total-$data['nilaipajak'];
    }*/

    if($data['masaberlaku'] > 1){
      $berulang=$total/$data['masaberlaku'];
      $data['tglakhir']=date('Y-m-t',strtotime("+".$data['masaberlaku']."months"));
    }else{
      $berulang=0;
    }

    $p=array(
      'vendor_id'  => $data['vendor_id'],
      'tglawal' => $data['tglawal'],
      'tglakhir'  => $data['tglakhir'],
      'masaberlaku' => $data['masaberlaku'],
      'keterangan'  => $this->db->escape($data['keterangan']),
      'status'  => 1,
      'bulanan' => $berulang,
      'nilaisewa' => $data['nilaisewa'],
      'jenisbiaya'  => $data['jenisbiaya'],
      'total' => $data['nilaisewa']+$data['ppn'],
      'totalbayar'  => 0,
      'ppn' => $data['ppn'],
      'date_added'  => date('Y-m-d H:i:s'),
      'date_modified' => date('Y-m-d H:i:s'),
      'totalbayar'  => 0,
      'user_id' => $this->user->getId()
    );

    $this->db->insert('iklan_periodik',$p);
    $id=$this->db->getLastId();




  }

  public function updatePermintaan($data,$where){
  /*  if($data['status'] == 4){
      $pb=$this->getPermintaanPembelian(array(),array(),$where);
      if($pb['status'] == 1){
        //$this->db->delete('jurnal_umum',array('type'=>30,'ref'=>$pb['id']));
        $this->load->model('keuangan/jurnal');
        $ju=$this->model_keuangan_jurnal->getJurnalUmum(array('ref'=>$pb['id'],'type'=>30));
        if(!empty($ju['id'])){
          $this->db->delete('jurnal_umum_detail',array('jurnal_id' => $ju['id']));
          $this->db->delete('jurnal_umum',array('id'=>$ju['id']));
        }
      }

    }*/
    if(empty($data['tglakhir'])){
      $data['tglakhir']=date('Y-m-d');
    }

    if(empty($data['tglawal'])){
      $data['tglawal']=date('Y-m-d');
      if($data['masaberlaku'] > 1){
        $data['tglakhir']=date('Y-m-d',strtotime("+".$data['masaberlaku']."months"));
      }
    }

    $data['total']=$data['nilaisewa']+$data['ppn'];
    /*if($data['statuspajak'] == 2){
      $total=$total+$data['nilaipajak'];
    }else if($data['statuspajak'] == 1){
      $total=$total-$data['nilaipajak'];
    }*/

    if($data['masaberlaku'] > 1){
      $data['bulanan']=$data['total']/$data['masaberlaku'];
    }else{
      $data['bulanan']=0;
    }

    $data['date_modified']=date('Y-m-d H:i:s');
    $data['user_id']=$this->user->getId();
    $this->db->update('iklan_periodik',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('iklan_periodik',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('iklan_periodik',$where);
	}
  public function totalDp($no_po){
    return $this->db->firstdetail('iklan_periodik',array('COALESCE(SUM(totalbayar)) as total'),array(),array('id' => $no_po),array());
  }

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('iklan_periodik',$column,$join,$where,array());
  }

  public function updateTotalBayar($id,$jumlah,$jenis){
    $data=$this->getPermintaanPembelian(array(),array(),array('id'=>$id));
    if($jenis == 2){
      $total=$data['totalbayar'] - $jumlah;
    }
    if($jenis == 1){
      $total=$data['totalbayar'] + $jumlah;
    }

    if($total == $data['total']){
      $status=3;
    }else if($total <= 0){
      $status=1;
    }
    else{
      $status = 2;
    }

    $this->db->update('iklan_periodik',array('totalbayar'=>$total,'status'=>$status),array('id'=>$id));
    return $total;
  }

  //proses periodik
  /*
  jenisbiaya
  1. sewa kantor dan gudang
  debet

  6217 biaya sewa
       1301  sewa dibayar dimuka

  2. perjalanan dinas
  1340
    6220
  3. profesional
  1399
      6261
  4. asuransi
  1302
    6262
  5. pembuatan software
  1399
    6299
  6. lain-lain
  1399
    6299
  */

  function prosesPeriodik(){
    /*tanggal hari ini*/
    $this->load->model('keuangan/jurnal');
    $tgl=date('Y-m-d');
    $akhirbulan=date("Y-m-t", time());
  //  if($tgl == $akhirbulan){
      /*ambil data biaya periodik dengan tanggal awal <=akhirbulandan status <> 4 dan lebih dari 1 */
      $periodik=$this->db->query("SELECT * FROM iklan_periodik WHERE prosesperiodik < masaberlaku AND totalperiodik < total AND status <> 4 AND status > 1 ");
      foreach($periodik->rows as $p){
        /*periodik akan diproses*/

          if(is_null($p['tglakhirproses'])){
            $tanggalproses=date('Y-m-t',strtotime($p['tglawal']));
          }else{
            $tglakhirproses=date('Y-m-01',strtotime($p['tglakhirproses']));
            $tanggalproses=date('Y-m-t', strtotime('+1 month', strtotime($tglakhirproses)));
          }

          $totalproses=is_null($p['totalperiodik'])?0:$p['totalperiodik'];
          $totalberulang=is_null($p['prosesperiodik'])?0:$p['prosesperiodik'];

          while($tanggalproses <= $tgl){
            /*tulis jurnal*/
            $totalproses +=$p['bulanan'];
            $totalberulang += 1;

            if($totalproses <= $p['total'] & $totalberulang <= $p['masaberlaku']){
              if($p['jenisbiaya'] == 1){
                $debet='6217';
                $kredit='1301';
              }

              if($p['jenisbiaya'] == 2){
                $debet='6220';
                $kredit='1304';
              }

              if($p['jenisbiaya'] == 3){
                $debet='6261';
                $kredit='1399';
              }

              if($p['jenisbiaya'] == 5){
                $debet='6299';
                $kredit='1399';
              }

              if($p['jenisbiaya'] == 4){
                $debet='6262';
                $kredit='1302';
              }

              if($p['jenisbiaya'] == 6){
                $debet='6299';
                $kredit='1399';
              }

              //jurnal
              $jurnal=array();
              $detail=array();
              if($p['bulanan'] > 0){
                $detail[]=array(
                  'ref_akun'  => $debet,
                  'keterangan'  => $this->db->escape('Biaya periodik tanggal '.$tanggalproses),
                  'debet' => $p['bulanan'],
                  'kredit'  => 0,
                  'urutan'  => 1,
                );

                $detail[]=array(
                  'ref_akun'  => $kredit,
                  'keterangan'  => $this->db->escape('Biaya periodik tanggal '.$tanggalproses),
                  'kredit' => $p['bulanan'],
                  'debet'  => 0,
                  'urutan'  => 2,
                );

                $jurnal=array(
                  'tanggal' => $tanggalproses,
                  'keterangan'  => $this->db->escape('Biaya periodik tanggal '.$tanggalproses),
                  'hapus' => 0,
                  'ref' => $p['id'],
                  'type'  => 37000,
                  'details'  => $detail
                );
                $this->model_keuangan_jurnal->addJurnalUmum($jurnal);
                //print_r($jurnal);
              }
              /*update tabel*/
              $this->db->update('iklan_periodik',array('totalperiodik'=>$totalproses,'prosesperiodik'=>$totalberulang,'tglakhirproses'=>date('Y-m-t',strtotime($tanggalproses))),array('id'=>$p['id']));
              $tglawalproses=date('Y-m-01',strtotime($tanggalproses));
              $tanggalproses=date('Y-m-t', strtotime('+1 month', strtotime($tglawalproses)));
            }else{
              break;
            }


          }
        //}
      }
    //}
    return $periodik;
  }

}
?>
