<?php
class ModelSalePembayaranpenjualan extends Model {
  public function addPembelian($data){
    //$pajak=0.1*$data['jumlah'];
    if(isset($data['biaya_bank'])){
      $data['jumlah']+=$data['biaya_bank'];
    }
    $p=array(
      'penjualan_id' => $data['no_po'],
      'jumlah'  => $data['jumlah'],
      'date_added'  => isset($data['date_added'])?$data['date_added']:date('Y-m-d H:i:s',time()),
      'date_modified' => date('Y-m-d H:i:s',time()),
      'status'  => 1,
      'hapus' => 0,
      'bank_id' => $data['bank_id']
    );

    $this->db->insert('pembayaran_penjualan',$p);
    $id=$this->db->getLastId();


    $this->load->model('sale/invoice');
    $pb=$this->model_sale_invoice->getPenjualan(array('id'=>$data['no_po']));
    
    

    $totalbayar=$pb['totalbayar'] + $data['jumlah'];
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
      $this->db->update('invoice',array('status'=>3,'totalbayar'=>$totalbayar,'tgllunas'=>$p['date_added']),array('id'=>$data['no_po']));
    }else{
        $this->db->update('invoice',array('status'=>2,'totalbayar'=>$totalbayar),array('id'=>$data['no_po']));
    }
    //jurnal umum
    $this->load->model('keuangan/jurnal');
    $detail=array();

    $this->load->model('keuangan/bank');
    $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $data['bank_id']));
    $saldo=$b['saldo'] + $data['jumlah'];
    $this->model_keuangan_bank->updateBank(array('saldo'  => $saldo),array('id'=> $data['bank_id']));

    //if($pb['jenisinvoice'] == 1 | $pb['jenisinvoice'] == 3){
      $aruskas=array(
        'date_added'  => $p['date_added'],
        'bank_id' => $data['bank_id'],
        'saldomasuk'  => $data['jumlah']-$data['biaya_bank'],
        'saldokeluar' => 0,
        'saldoawal' => $b['saldo'],
        'saldoakhir'  => $saldo+$data['biaya_bank'],
        'ref' => $data['no_po'],
        'keterangan'  => 'Penjualan',
        'type'  => 4,
        'ref_akun'  => '4001'
      );

      $this->model_keuangan_bank->addAruskas($aruskas);

      /*if($data['biaya_bank'] > 0){
  			$aruskas=array(
  				'date_added'	=> date('Y-m-d H:i:s'),
  				'date_trans'	=> $p['date_added'],
  				'bank_id' => $data['bank_id'],
  				'saldokeluar'  => $data['biaya_bank'],
  				'saldomasuk' => 0,
  				'saldoawal' => $b['saldo']+$data['jumlah']+$data['biaya_bank'],
  				'saldoakhir'  => $saldo,
  				'ref' => $data['no_po'],
  				'keterangan'  => 'Biaya Administrasi Bank ',
  				'type'  => 32,
  				'ref_akun'  => '6265'
  			);

  			$this->model_keuangan_bank->addAruskas($aruskas);
  		}*/


      /*$detail[]=array(
        'ref_akun'  =>$b['rek_parent'],
        'debet' => $data['jumlah'],
        'kredit'  => 0,
        'urutan'  =>1,
        'keterangan'  => 'Kas/Bank'
      );*/
      $jumlah=$data['jumlah']-$data['biaya_bank'];
      if($b['saldo'] < 0){
  			if($b['hutangprk'] == 1){

  				if($b['saldo']+$jumlah > 0){


  					$hutangprk=0-$b['saldo'];
  					$kas=$jumlah-$hutangprk;
  					$detail[]=array(
  						'ref_akun'  => $b['rek_parent'],
  						'keterangan'  => $this->db->escape('Kas/Bank'),
  						'debet' => $kas,
  						'kredit'  => 0,
  						'urutan'  => 1,
  					);
  					$detail[]=array(
  						'ref_akun'  => '2001',
  						'keterangan'  => $this->db->escape('Pembayaran Hutang PRK'),
  						'debet' => $hutangprk,
  						'kredit'  => 0,
  						'urutan'  => 2,
  					);
  				}else{
  					$hutangprk=$jumlah;
  					$detail[]=array(
  						'ref_akun'  => '2001',
  						'keterangan'  => $this->db->escape('Pembayaran Hutang PRK'),
  						'debet' => $hutangprk,
  						'kredit'  => 0,
  						'urutan'  => 2,
  					);
  				}
  			}else{
  				$detail[]=array(
  					'ref_akun'  => $b['rek_parent'],
  					'keterangan'  => $this->db->escape('Kas/Bank'),
  					'debet' => $jumlah,
  					'kredit'  => 0,
  					'urutan'  => 1,
  				);
  			}
  		}else{
  			$detail[]=array(
  				'ref_akun'  => $b['rek_parent'],
  				'keterangan'  => $this->db->escape('Kas/Bank'),
  				'debet' => $jumlah,
  				'kredit'  => 0,
  				'urutan'  => 1,
  			);
  		}

      if(isset($data['biaya_bank'])){
        if($data['biaya_bank'] > 0){
          $detail[]=array(
            'ref_akun'  =>'6265',
            'debet' => $data['biaya_bank'],
            'kredit'  => 0,
            'urutan'  =>2,
            'keterangan'  => 'Biaya Administrasi Bank'
          );
        }
      }

      $detail[]=array(
        'ref_akun'  =>'1101',
        'debet' => 0,
        'kredit'  => $data['jumlah'],
        'urutan'  =>3,
        'keterangan'  => 'Piutang Usaha'
      );


      $jurnal=array(
        'tanggal' => $p['date_added'],
        'keterangan' => 'Pembayaran Penjualan Tunai',
        'ref' => $data['no_po'],
        'type' => 1200,
        'details' => $detail
      );
      $this->model_keuangan_jurnal->addJurnalUmum($jurnal);
    //debet



  }

  public function updatePermintaan($data,$where){
    if($data['status'] == 3){
      $pb=$this->getPermintaanPembelian(array(),array(),$where);
      $this->db->update('invoice',array('status'=>1,'totalbayar'=>0),array('id'=>$pb['penjualan_id']));

      $this->load->model('keuangan/bank');
      $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $pb['bank_id']));
      $saldo=$b['saldo'] - $pb['jumlah'];
      $this->model_keuangan_bank->updateBank(array('saldo'  => $saldo),array('id'=> $pb['bank_id']));

      //$this->model_keuangan_bank->updateAruskas(array('hapus' => 1),array('type'  => 4,'ref'  => $pb['penjualan_id']));
      $aruskas=array(
        'date_added'  => $data['date_trans'],
        'bank_id' => $data['bank_id'],
        'saldomasuk'  => 0,
        'saldokeluar' => $data['nominal'],
        'saldoawal' => $b['saldo'],
        'saldoakhir'  => 0,
        'ref' => $data['no_po'],
        'keterangan'  => 'Pembatalan Pembayaran Penjualan Tunai',
        'type'  => 4,
        'ref_akun'  => '4001'
      );

      $this->model_keuangan_bank->addAruskas($aruskas);

      $this->load->model('keuangan/jurnal');
      $this->model_keuangan_jurnal->updateJurnalumum(array('hapus' => 1),array('type'  => 1200,'ref'  => $pb['penjualan_id']));

    }
    $this->db->update('pembayaran_penjualan',array('status'=>3),$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('pembayaran_penjualan',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('pembayaran_penjualan',$where);
	}
  public function totalDp($no_po){
    return $this->db->firstdetail('pembayaran_penjualan',array('COALESCE(SUM(jumlah)) as total'),array(),array('penjualan_id' => $no_po,'hapus' => array('<',1),'status' => array('<>',3)),array());
  }

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('pembayaran_penjualan',$column,$join,$where,array());
  }


}
?>
