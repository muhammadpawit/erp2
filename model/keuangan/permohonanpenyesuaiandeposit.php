<?php
class ModelKeuanganPermohonanpenyesuaiandeposit extends Model {
  public function addPermintaanPembelian($data){
    /*
    status
    1. menunggu persetujuan
    2. selesai diproses
    3. dibatalkan/ditolak
    */
    $selisih=$data['nominal_tersedia'] - $data['nominal_tersimpan'];
    $p=array(
      'tanggal' => empty($data['tanggal'])?date('Y-m-d'):$data['tanggal'],
      'date_added'  => date('Y-m-d H:i:s'),
      'status'  => 1,
      'customer_id' => $data['customer_id'],
      'nominal_tersedia'    => $data['nominal_tersedia'],
      'nominal_tersimpan'   => $data['nominal_tersimpan'],
      'selisih' => $selisih,
      'user_id' => $this->user->getId(),
      'Keterangan'  => $this->db->escape($data['keterangan']),
      
    );
    $this->db->insert('penyesuaian_deposit',$p);
    $id=$this->db->getLastId();
    $no_surat='PUM-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;
    $this->db->update('penyesuaian_deposit',array('no_surat' => $no_surat),array('id'  => $id));
    
   
    $data['id'] = $id;
    return $id;
  }

  
  public function updatePermintaan($data,$where){
    $this->db->update('penyesuaian_deposit',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('penyesuaian_deposit',$column,$join,$leftjoin,$where,array('id'=> 'DESC'),$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('penyesuaian_deposit',$where);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('penyesuaian_deposit',$column,$join,$where,array());
  }

  

  public function setujuiPenyesuaian($data,$id){
    $sop=$this->getPermintaanPembelian(array(),array(),array('id'=>$id));
    	$this->load->model('keuangan/jurnal');
      $this->load->model('sale/customer');
     
    if($sop['status'] == 1){
     if($data['status'] == 2){
        if($sop['selisih'] != 0){
            if($sop['selisih'] > 0){
                //menambah jumlah deposit
                $this->load->model('sale/customer');
                $this->model_sale_customer->updateDeposit($sop['customer_id'],$sop['selisih'],1);

                //history deposit
                $depo=array(
                    'ref'=> $sop['id'],
                    'date_trans'	=> $data['tgl_diproses'],
                    'saldomasuk'	=> $sop['selisih'],
                    'saldokeluar'	=> 0,
                    'keterangan'	=> $this->db->escape('Penyesuaian saldo deposit dengan keterangan '.$sop['keterangan']),
                    'hapus'	=> 0,
                    'customer_id'=> $sop['customer_id'],
                    'date_added'	=> date('Y-m-d H:i:s'),
                    'date_modified' => date('Y-m-d H:i:s'),
                    'no_dokumen'	=> $sop['no_surat'],
                    'urlref'	=> 'keuangan/permohonanpenyesuaiandeposit'
                );
                $this->model_sale_customer->addHistoryDeposit($depo);

                if($data['catat_jurnal']){
                    //dekreditbet uang muka x debet jurnal kelebihan
                    $jurnal=array();
                    $detail=array();
                    $detail[]=array(
                        'ref_akun'  => $this->config->get('config_kelebihan'),
                        'keterangan'  => $this->db->escape('Penyesuaian Saldo dengan keterangan '.$sop['keterangan']),
                        //'kredit' => $pb['nominal']+$pb['biaya_bank']+$pb['biaya_lain'],
                        'debet' => $sop['selisih'],
                        'kredit'  => 0,
                        'urutan'  => 1,
                      );
                    $detail[]=array(
                        'ref_akun'  => '2401',
                        'keterangan'  => $this->db->escape('Penyesuaian Saldo dengan keterangan '.$sop['keterangan']),
                        //'kredit' => $pb['nominal']+$pb['biaya_bank']+$pb['biaya_lain'],
                        'kredit' => $sop['selisih'],
                        'debet'  => 0,
                        'urutan'  => 2,
                      );
                    
                      $jurnal=array(
                        'tanggal' => $data['tgl_diproses'],
                        'keterangan'  => 'Penyesuaian saldo deposit customer dengan keterangan, '.$sop['keterangan'],
                        'hapus' => 0,
                        'ref' => $sop['id'],
                        'type'  => 6565,
                        'details'  => $detail,
                        'idref' => $sop['id'],
                        'urlref'  =>'keuangan/permohonanpenyesuaiandeposit',
                        'no_dokumen'  => $sop['no_surat']
                      );
                      $this->model_keuangan_jurnal->addJurnalUmum($jurnal);
                }
            }else{
                //mengurangi jumlah deposit
                $this->load->model('sale/customer');
                $this->model_sale_customer->updateDeposit($sop['customer_id'],abs($sop['selisih']),2);

                //history deposit
                $depo=array(
                    'ref'=> $sop['id'],
                    'date_trans'	=> $data['tgl_diproses'],
                    'saldokeluar'	=> abs($sop['selisih']),
                    'saldomasuk'	=> 0,
                    'keterangan'	=> $this->db->escape('Penyesuaian saldo deposit dengan keterangan '.$sop['keterangan']),
                    'hapus'	=> 0,
                    'customer_id'=> $sop['customer_id'],
                    'date_added'	=> date('Y-m-d H:i:s'),
                    'date_modified' => date('Y-m-d H:i:s'),
                    'no_dokumen'	=> $sop['no_surat'],
                    'urlref'	=> 'keuangan/permohonanpenyesuaiandeposit'
                );
                $this->model_sale_customer->addHistoryDeposit($depo);

                if($data['catat_jurnal']){
                    //debet kekurangan x kredit uang muka
                    $jurnal=array();
                    $detail=array();
                    
                    $detail[]=array(
                        'ref_akun'  => '2401',
                        'keterangan'  => $this->db->escape('Penyesuaian Saldo dengan keterangan '.$sop['keterangan']),
                        //'kredit' => $pb['nominal']+$pb['biaya_bank']+$pb['biaya_lain'],
                        'debet' => abs($sop['selisih']),
                        'kredit'  => 0,
                        'kekurangan'  => 0,
                        'urutan'  => 1,
                      );
                      $detail[]=array(
                        'ref_akun'  => $this->config->get('config_kekurangan'),
                        'keterangan'  => $this->db->escape('Penyesuaian Saldo dengan keterangan '.$sop['keterangan']),
                        //'kredit' => $pb['nominal']+$pb['biaya_bank']+$pb['biaya_lain'],
                        'kredit' => abs($sop['selisih']),
                        'debet'  => 0,
                        'urutan'  => 1,
                      );
                    
                      $jurnal=array(
                        'tanggal' => $data['tgl_diproses'],
                        'keterangan'  => 'Penyesuaian saldo deposit customer dengan keterangan, '.$sop['keterangan'],
                        'hapus' => 0,
                        'ref' => $sop['id'],
                        'type'  => 6565,
                        'details'  => $detail,
                        'idref' => $sop['id'],
                        'urlref'  =>'keuangan/permohonanpenyesuaiandeposit',
                        'no_dokumen'  => $sop['no_surat']
                      );
                      $this->model_keuangan_jurnal->addJurnalUmum($jurnal);
                }
            }
        }

    
       
      
        $update=array(
          'tgl_diproses'  => $data['tgl_diproses'],
          'status'  => 2,
          'user_setujui' => $this->user->getId(),
          'catat_jurnal'  => $data['catat_jurnal'],
          //'no_suratpersetujuan' =>'PSO-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id,
        );

        $this->db->update('penyesuaian_deposit',$update,array('id'=>$id));
      }
      if($data['status'] == 3){
        $update=array(
          'tgl_diproses'  => $data['tgl_diproses'],
          'status'  => 3,
          'user_proses' => $this->user->getId(),
          'alasan_dibatalkan' => $this->db->escape($data['alasan_dibatalkan'])
        );

        $this->db->update('penyesuaian_deposit',$update,array('id'=>$id));
      }
    }
  }
}
?>
