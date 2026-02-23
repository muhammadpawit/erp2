<?php
class ModelPembelianPib extends Model {
  public function addPembelian($data){
    $p=array(
      'po_id' => $data['po_id'],
      'no_pengajuan'  => $data['no_pengajuan'],
      'date_added'  => date('Y-m-d H:i:s',time()),
      'perkiraan_tiba' => $data['perkiraan_tiba'],
      'hapus' => 0,
      'user_id' => $this->user->getId(),
      'kurs' => empty($data['kurs'])?0:$data['kurs'],
      'nilai_fob' => empty($data['nilai_fob'])?0:$data['nilai_fob'],

    );

    $this->db->insert('pib',$p);
    $id=$this->db->getLastId();

  //  $this->db->update();
  }

  public function updatePermintaan($data,$where){

    $this->db->update('pib',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('pib',$column,$join,$where,$order,$limit,$offset);
  }


  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('pib',$column,$join,$where,array());
  }

/*  public function penyesuaianKurs($data){
    //history
    $this->db->insert('penyesuaian_kurs',$data);

    $this->load->model('keuangan/jurnal');
    //list pembelian pada bulan yang akan disesuaikan
    $this->load->model('keuangan/bank');
    $this->load->model('pembelian/pembelianimport');

    $pembayaran=$this->getPermintaanPembelians(array(),array(),array('status'=>1,'date_added'=>array('>=',$data['tglawal']),'date_added'=>array('<=',$data['tglakhir']),'penyesuaian'=>array('<>',1)),array(),0,null);
    foreach($pembayaran as $p){
      $this->db->update('pembayaran_import',array('kursbi'=>$data['kursbi'],'kurskmk'=>$data['kurskmk'],'penyesuaian'=>1),array('id'=>$p['id']));

      //hitung selisih
      $kursbank=$p['kursbank']*$p['jumlah'];
      $kursbi=$p['kursbi']*$p['jumlah'];
      $selisih=$kursbank-$kursbi;

      $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $p['bank_id']));

      $pb=$this->model_pembelian_pembelianimport->getPermintaanPembelian(array(),array(),array('no_po'  => $p['no_po']));

      $details=array();
      if($pb['status'] == 0){
        if($selisih > 0){
          $details[]=array(
            'ref_akun'  => "30.02.02",
            'keterangan'  => "Rugi selisih kurs",
            'debet' => abs($selisih),
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );
          $details[]=array(
            'ref_akun'  => "11.07.01",
            'keterangan'  => "Uang muka pembelian",
            'debet' => 0,
            'kredit'  => abs($selisih),
            'urutan'  => 1,
            'hapus' => 0
          );
        }
        if($selisih < 0){
          $details[]=array(
            'ref_akun'  => "11.07.01",
            'keterangan'  => "Uang Muka Pembelian",
            'debet' => abs($selisih),
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );
          $details[]=array(
            'ref_akun'  => "30.02.02",
            'keterangan'  => "Rugi selisih kurs",
            'debet' => 0,
            'kredit'  => abs($selisih),
            'urutan'  => 1,
            'hapus' => 0
          );
        }
        $j=array(
          'tanggal' => date('Y-m-d'),
          'keterangan'  => 'Laba/Rugi Selisih Kurs '.$p['no_po'],
          'details' => $details,
          'hapus' =>0,
          'ref' => $p['id'],
          'type'  => 1
        );
      }else{
        if($selisih > 0){
          $details[]=array(
            'ref_akun'  => "30.02.02",
            'keterangan'  => "Rugi selisih kurs",
            'debet' => abs($selisih),
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );
          $details[]=array(
            'ref_akun'  => "21.02.01",
            'keterangan'  => "Pembayaran Hutang",
            'debet' => 0,
            'kredit'  => abs($selisih),
            'urutan'  => 1,
            'hapus' => 0
          );
        }
        if($selisih < 0){
          $details[]=array(
            'ref_akun'  => "21.02.01",
            'keterangan'  => "Pembayaran Hutang",
            'debet' => abs($selisih),
            'kredit'  => 0,
            'urutan'  => 1,
            'hapus' => 0
          );
          $details[]=array(
            'ref_akun'  => "30.02.02",
            'keterangan'  => "Rugi selisih kurs",
            'debet' => 0,
            'kredit'  => abs($selisih),
            'urutan'  => 1,
            'hapus' => 0
          );
        }
        $j=array(
          'tanggal' => date('Y-m-d'),
          'keterangan'  => 'Laba/Rugi Selisih Kurs '.$p['no_po'],
          'details' => $details,
          'hapus' =>0,
          'ref' => $p['id'],
          'type'  => 2
        );
      }

      $this->model_keuangan_jurnal->addJurnalUmum($j);
    }
  }*/
}
?>
