<?php
class ModelKeuanganPenerimaandana extends Model {
  // baru 6 Mei 2020
  public function hitungtotal($data){
    $sql="SELECT * FROM penerimaan_dana WHERE id>1 and hapus=0 ";
    if(isset($data['customer_id'])){
      $sql .=" AND customer_id='".$data['customer_id']."' ";
    }

    if(isset($data['status'])){
      if($data['status']==0){
        $sql .=" AND status>'".$data['status']."' ";
      }else{
        $sql .=" AND status='".$data['status']."' ";
      }
    }    

    if(isset($data['tgl_awal'])){
      $sql .=" AND tgl_bayar >='".$data['tgl_awal']."' ";
    }

    if(isset($data['tgl_akhir'])){
      $sql .=" AND tgl_bayar <='".$data['tgl_akhir']."' ";
    }
    if(isset($data['customer_id'])){
      $sql .=" AND customer_id='".$data['customer_id']."' ";
    }

    if(isset($data['metode'])){
      if($data['metode']==0){
        $sql .=" AND metode_pembayaran >'".$data['metode']."' ";
      }else{
        $sql .=" AND metode_pembayaran ='".$data['metode']."' ";
      }
    }
    if(isset($data['order'])){
      $sql .=" ORDER BY id ".$data['order']." ";
    }else{
      $sql .=" ORDER BY tgl_bayar DESC ";  
    }
    
    if (isset($data['start']) || isset($data['limit'])) {
      if ($data['start'] < 0) {
      $data['start'] = 0;
      }

      if ($data['limit'] < 1) {
      $data['limit'] = 20;
      }

      //$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
    }
    if($this->user->getUsername()=="pawits"){
      return $sql;
    }else{
      $d= $this->db->query($sql);
      return $d->rows;
    }
    
  }
  // end baru
  // baru 22 Januari 2020
  public function terimadanahutanglain($data){
    
    $this->db->update('penerimaan_dana',array('status'=>2,'tgl_diterima'=>$data['tgl_diterima']),array('id'=>$data['id']));
    $idhutanglain = $this->getpenerimaandananya($data['id']);
    $this->db->update('penerimaandana_hutanglain',array('status'=>3),array('id'=>$idhutanglain['id_hutanglain']));
    $customer_id =$this->getpenerimaandananya($data['id']);
    $pb =$this->getpenerimaandananya($data['id']);

    
    $this->load->model('sale/customer');
    $depositdata=array(
      'ref'=> $data['id'],
      'date_trans'  => $data['tgl_diterima'],
      'nominal' => $pb['nominal'],
      'keterangan'  => $data['keterangan'],
      'bank_id' => $pb['bank_id'],
      'biaya_bank'  => $pb['biaya_bank'],
      'biaya_lain' => $pb['biaya_lain'],
      'urlref'  => 'keuangan/penerimaandanahutanglain',
      'no_dokumen'  => $pb['no_dokumen']
    );
    $this->model_sale_customer->addDeposithutanglain($depositdata,$pb['customer_id']);
            
            $this->load->model('keuangan/jurnal');
            $jurnal=array();
            $detail=array();
            $detail[]=array(
              'ref_akun'  => '2299',
              'keterangan'  => $this->db->escape($pb['keterangan']),
              'kredit' =>0,
              'debet'  =>$pb['nominal']+$pb['biaya_bank']+$pb['biaya_lain'],
              'urutan'  =>1,
            );
            $detail[]=array(
              'ref_akun'  => '2401',
              'keterangan'  => $this->db->escape($pb['keterangan']),
              'debet' => 0,
              'kredit'  => $pb['nominal']+$pb['biaya_bank']+$pb['biaya_lain'],
              'urutan'  => 2,
            );
            $jurnal=array(
              'tanggal' => $data['tgl_diterima'],
              'keterangan'  => $data['keterangan'],
              'hapus' => 0,
              'ref' => $pb['id'],
              'type'  => 101,
              'linkterkait' => 'PDHL-'.date('Y').'-'.$pb['id_hutanglain'],
              'details'  => $detail,
              'no_dokumen'  => $pb['no_dokumen'],
              'idref' => $pb['id'],
              'urlref'  => 'keuangan/penerimaandanahutanglain'
            );
            $this->model_keuangan_jurnal->addJurnalUmum($jurnal);
  }
  public function getpenerimaandananya($id){
    $d = $this->db->query("SELECT * FROM penerimaan_dana where id='$id' ");
    return $d->row;
  }
  // end baru
  // baru 16 Desember 2019
  public function getbank($id){
    $sql="SELECT * FROM banks WHERE id='$id' ";
    $d=$this->db->query($sql);
    return $d->row;
  }
  public function getcust($id){
    $sql="SELECT * FROM customer WHERE customer_id='$id' ";
    $d=$this->db->query($sql);
    return $d->row;
  }
  public function getpenerimaandana($data){
    $sql="SELECT * FROM penerimaan_dana WHERE id>1 and hapus=0 ";
		if(isset($data['customer_id'])){
			$sql .=" AND customer_id='".$data['customer_id']."' ";
    }

    if(isset($data['status'])){
      if($data['status']==0){
        $sql .=" AND status>'".$data['status']."' ";
      }else{
        $sql .=" AND status='".$data['status']."' ";
      }
    }

    

    if(isset($data['tgl_awal'])){
			$sql .=" AND tgl_bayar >='".$data['tgl_awal']."' ";
    }

    if(isset($data['tgl_akhir'])){
			$sql .=" AND tgl_bayar <='".$data['tgl_akhir']."' ";
    }
    if(isset($data['customer_id'])){
			$sql .=" AND customer_id='".$data['customer_id']."' ";
    }

    if(isset($data['metode'])){
			if($data['metode']==0){
        $sql .=" AND metode_pembayaran >'".$data['metode']."' ";
      }else{
        $sql .=" AND metode_pembayaran ='".$data['metode']."' ";
      }
    }
    if(isset($data['order'])){
      $sql .=" ORDER BY id ".$data['order']." ";
    }else{
      $sql .=" ORDER BY tgl_bayar DESC ";  
    }
    
    if (isset($data['start']) || isset($data['limit'])) {
		  if ($data['start'] < 0) {
			$data['start'] = 0;
		  }

		  if ($data['limit'] < 1) {
			$data['limit'] = 20;
		  }

		  $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
    }
    if($this->user->getUsername()=="pawits"){
      return $sql;
    }else{
      $d= $this->db->query($sql);
      return $d->rows;
    }
    
  }
  public function totalgetpenerimaandana($data){
    $sql="SELECT * FROM penerimaan_dana WHERE id>1 and hapus=0 ";
		if(isset($data['customer_id'])){
			$sql .=" AND customer_id='".$data['customer_id']."' ";
    }

    if(isset($data['status'])){
      if($data['status']==0){
        $sql .=" AND status>'".$data['status']."' ";
      }else{
        $sql .=" AND status='".$data['status']."' ";
      }
    }

    if(isset($data['tgl_awal'])){
			$sql .=" AND tgl_bayar >='".$data['tgl_awal']."' ";
    }

    if(isset($data['tgl_akhir'])){
			$sql .=" AND tgl_bayar <='".$data['tgl_akhir']."' ";
    }
    if(isset($data['customer_id'])){
			$sql .=" AND customer_id='".$data['customer_id']."' ";
    }

    if(isset($data['metode'])){
			if($data['metode']==0){
        $sql .=" AND metode_pembayaran >'".$data['metode']."' ";
      }else{
        $sql .=" AND metode_pembayaran ='".$data['metode']."' ";
      }
    }

    $sql .=" ORDER BY id ".$data['order']." ";
		$d= $this->db->query($sql);
    return $d->rows;
    //return $sql;
  }
	// baru 2 November 2019
	public function getpenerimaanhutanglainjson($data){
		$sql="SELECT * FROM penerimaandana_hutanglain WHERE status=1 and hapus=0 ";
    
    if(isset($data['keterangan'])){
			$sql .=" AND keterangan LIKE '%".$data['keterangan']."%' ";
    }
    if(isset($data['linkterkait'])){
			$sql .=" AND lower(linkterkait) LIKE '%".$this->db->escape($data['linkterkait'])."%' ";
    }
    
    $sql.=" ORDER BY id DESC";
    if (isset($data['start']) || isset($data['limit'])) {
		  if ($data['start'] < 0) {
			$data['start'] = 0;
		  }

		  if ($data['limit'] < 1) {
			$data['limit'] = 20;
		  }

		  $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
		}
		$d= $this->db->query($sql);
    if($this->user->getUsername()=="pawitz"){
      return $sql;
    }else{
      return $d->rows;
    }
	}

	public function getpenerimaanhutanglainjsons($id){
		$sql="SELECT * FROM penerimaandana_hutanglain WHERE status=1 and id='$id' ";
		if(isset($data['keterangan'])){
			//$sql .=" AND keterangan like '%".$data['keterangan']."%' ";
		}

		$d= $this->db->query($sql);
		return $d->rows;
	}
	// end baru
  
	// baru 20 November 2019
  public function getpenerimaanhutanglain($data){
    $sql="SELECT * FROM penerimaandana_hutanglain WHERE id > 0 and hapus=0 ";
    if($data['status']!=null){
			if($data['status']>0){
        $sql .=" AND status ='".$data['status']."' ";
      }else{
       $sql .=" AND status >'".$data['status']."' ";
      }
		}
    $sql .="order by id desc";
    if (isset($data['start']) || isset($data['limit'])) {
		  if ($data['start'] < 0) {
			$data['start'] = 0;
		  }

		  if ($data['limit'] < 1) {
			$data['limit'] = 20;
		  }

		  $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
		}
    $d= $this->db->query($sql);
    return $d->rows;
  }

  public function totalgetpenerimaanhutanglain($data){
    $sql="SELECT * FROM penerimaandana_hutanglain WHERE id > 0 ";
    if($data['status']!=null){
			if($data['status']>0){
        $sql .=" AND status ='".$data['status']."' ";
      }else{
       $sql .=" AND status >'".$data['status']."' ";
      }
		}
    $sql .="order by id desc";
    $d= $this->db->query($sql);
    return $d->rows;
  }
	public function getgetpenerimaanhutanglain($id){
		$sql="SELECT * FROM penerimaandana_hutanglain WHERE id='".$id."' ";
		$d= $this->db->query($sql);
		return $d->row;
	}

	public function getbanks($id){
		$sql="SELECT name FROM banks WHERE id='".$id."' ";
		$d= $this->db->query($sql);
		return $d->row['name'];
	}

  // Baru
  public function addpenerimaandanahutanglain($data){
    $t = array(
      'tanggal' => date('Y-m-d',strtotime($data['tanggal'])),
      'nominal' => empty($data['nominal'])?0:$data['nominal'],
      'keterangan' => empty($data['keterangan'])?'-':$data['keterangan'],
      'bank_tujuan' => empty($data['bank_id'])?0:$data['bank_id'],
      'metode_pembayaran' => empty($data['metode_pembayaran'])?0:$data['metode_pembayaran'],
      'no_giro' => empty($data['no_giro'])?'-':$data['no_giro'],
      'linkterkait' => empty($data['linkterkait'])?'-':$data['linkterkait'],
      'biaya_bank' => empty($data['biaya_bank'])?0:$data['biaya_bank'],
      'biaya_lain' => empty($data['biaya_lain'])?0:$data['biaya_lain'],
      'status' =>1,
      'hapus' =>0,
      
    );
    $this->db->insert('penerimaandana_hutanglain',$t);
    $id=$this->db->getLastId();
    $no_dokumen='PDHL-'.$id.'-'.date('Y').'-'.date('m').'-'.$this->user->getId();
    // catat mutasi bank

    $this->db->update('penerimaandana_hutanglain',array('no_dokumen' => $no_dokumen),array('id'=>$id));
    $this->load->model('keuangan/bank');
    $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $data['bank_id']));
    $saldo=$b['saldo'] + $data['nominal'];
    $this->model_keuangan_bank->editBank(array('saldo'  => $saldo),array('id'=> $data['bank_id']));
      
      // jurnal
      $this->load->model('keuangan/jurnal');
      if($b['hutangprk']==1){
        $detail[]=array(
          'ref_akun'  => '2001',
          'keterangan'  => $this->db->escape($data['keterangan']),
          'debet' => $data['nominal']+$data['biaya_bank']+$data['biaya_lain'],
          'kredit'  => 0,
          'urutan'  => 1,
        );
      }else{
        $detail[]=array(
          'ref_akun'  => $data['ref_akun'],
          'keterangan'  => $this->db->escape($data['keterangan']),
          'debet' => $data['nominal']+$data['biaya_bank']+$data['biaya_lain'],
          'kredit'  => 0,
          'urutan'  => 1,
        );
      }
      $detail[]=array(
        'ref_akun'  => '2299',
        'keterangan'  => $this->db->escape($data['keterangan']),
        'kredit' => $data['nominal']+$data['biaya_bank']+$data['biaya_lain'],
        'debet'  => 0,
        'urutan'  => 2,
      );
      $jurnal=array(
        'tanggal' => date('Y-m-d',strtotime($data['tanggal'])),
        'keterangan'  => $data['keterangan'],
        'hapus' => 0,
        'ref' => $id,
        'type'  => 201912,
        'linkterkait' =>$no_dokumen,
        'details'  => $detail,
        'idref' => $id,
        'urlref'  =>'keuangan/penerimaandanahutanglain',
        'no_dokumen'  => $no_dokumen
      );
      $jurnal_id==$this->model_keuangan_jurnal->addJurnalUmum($jurnal);

      $aruskas=array(
        'date_added'  => date('Y-m-d H:i:s'),
        'date_trans'  => $data['tanggal'],
        'bank_id' => $data['bank_id'],
        'saldomasuk'  => $data['nominal']+$data['biaya_bank']+$data['biaya_lain'],
        'saldokeluar' => 0,
        'saldoawal' => $b['saldo'],
        'saldoakhir'  => $saldo+$data['biaya_bank']+$data['biaya_lain'],
        'ref' => 'PDHL-'.date('Y').'-'.$id,
        'keterangan'  => 'Penerimaan Dana Hutang Lain dengan keterangan '.$data['keterangan'],
        'type'  => 4,
        'ref_akun'  => '2401',
        'hutanglain_id' => $id,
        'idref' => $id,
        'urlref'  =>'keuangan/penerimaandanahutanglain',
        'no_dokumen'  => $no_dokumen,
        'jurnal_id' => $jurnal_id
      );
      $aruskas_id = $this->model_keuangan_bank->addAruskas($aruskas);
      $this->db->query("UPDATE aruskas set hutanglain_id='$id' WHERE id='$aruskas_id' ");
  }
  // end baru
  public function addPembelian($data){
    /*status
    1. disimpan
    2. diterima
    3. dibatalkan
	4. ditolak

    jenis
    1. deposit customer
    2. pembayaran tunai & cod

    metode_pembayaran
    1. tunai
    2. transfer bank
    3. giro
    4. cheque
    5. Piutang Lain 
    6. Biaya
    */
  $biaya_lain = ($data['biaya_lain']==0)?0:$data['biaya_lain'];
    $idhutanglain=0;
    if(isset($data['hutanglain'])){
      $idhutanglain=$data['hutanglain'];
      if($idhutanglain>0){
        $this->db->update('penerimaandana_hutanglain',array('status'=>5),array('id'=>$idhutanglain));
      }
    }
    $p=array(
      'tgl_diterima' => !empty($data['tgl_diterima'])?$data['tgl_diterima']:'1970-01-01',
      'nominal'  => empty($data['nominal'])?0:$data['nominal'],
      'biaya_bank'  => empty($data['biaya_bank'])?0:$data['biaya_bank'],
	    'biaya_lain'  => empty($data['biaya_lain'])?0:$data['biaya_lain'],
      'no_giro'  => $data['no_giro'],
      'tgl_bayar'  => $data['tgl_bayar'],
      'status'  => empty($data['status'])?1:$data['status'],
      'keterangan'  => $this->db->escape($data['keterangan']),
      'bank_id' => empty($data['bank_id'])?0:$data['bank_id'],
      'customer_id' => $data['customer_id'],
      'customer_name' => empty($data['customer_name'])?'':$data['customer_name'],
      'cetak'  => 0,
      'ref'  => empty($data['ref'])?0:$data['ref'],
      'user_id' => $this->user->getId(),
      'hapus' =>0,
      'metode_pembayaran' => $data['metode_pembayaran'],
      'jenis' => $data['jenis'],
      'id_hutanglain' => $idhutanglain,
      'tgl_input' => date('Y-m-d'),
      'tglterima_giro' => empty($data['tglterima_giro'])?'1970-01-01':$data['tglterima_giro'],
      'pendapatan_lain' => empty($data['pendapatan_lain'])?0:$data['pendapatan_lain'],
      'biayamarketplace' => empty($data['biayamarketplace'])?0:$data['biayamarketplace'],
    );

    $this->db->insert('penerimaan_dana',$p);
    $id=$this->db->getLastId();
    $no_pd='PD-'.$id.'-'.date('Y').'-'.date('m').'-'.$this->user->getId();
    $no_dokumen='UMP-'.$id.'-'.date('Y').'-'.date('m').'-'.$this->user->getId();

    $this->db->update('penerimaan_dana',array('no_pd'=>$no_pd,'no_dokumen'=>$no_dokumen),array('id'=>$id));

    if($data['bank_id']==0){

    }else{
      if($data['status'] == 2){
        if($data['jenis'] == 1){
          $this->load->model('sale/customer');
          $depositdata=array(
            'ref'=> $id,
            'date_trans'	=> $data['tgl_diterima'],
            'nominal'	=> $data['nominal'],
            'keterangan'	=> $data['keterangan'],
            'bank_id'	=> $data['bank_id'],
            'biaya_bank'  => $data['biaya_bank'],
            'urlref'  => 'keuangan/penerimaandana',
            'no_dokumen'  => $no_dokumen
          );
          $this->model_sale_customer->addDeposit($depositdata,$data['customer_id']);
        }

        if($data['jenis'] == 2){
          $this->load->model('sale/pembayaranpenjualan');
          $pembayaranpenjualan=array(
            'no_po' => $data['ref'],
            'jumlah'  => $data['nominal'],
            'date_added'  => isset($data['tgl_diterima'])?$data['tgl_diterima']:date('Y-m-d H:i:s',time()),
            'bank_id' => $data['bank_id'],
            'biaya_bank'  => $data['biaya_bank'],
            'biaya_lain' => $biaya_lain,
            'no_dokumen'  => $pb['no_dokumen'],
            'urlref'  => 'keuangan/penerimaandana'
          );
          $this->model_sale_pembayaranpenjualan->addPembelian($pembayaranpenjualan);
        }

    }else{
      if($data['metode_pembayaran'] == 3){
        $this->load->model('keuangan/jurnal');
        $jurnal=array();
        $detail=array();
        if($data['nominal'] > 0){
          $detail[]=array(
            'ref_akun'  => '1501',
            'keterangan'  => $this->db->escape($data['keterangan']),
            'debet' => $data['nominal']+$data['biaya_bank']+$biaya_lain,
            'kredit'  => 0,
            'urutan'  => 1,
          );
          $detail[]=array(
            'ref_akun'  => '2201',
            'keterangan'  => $this->db->escape($data['keterangan']),
            'kredit' => $data['nominal']+$data['biaya_bank'],
            'debet'  => 0,
            'urutan'  => 2,
          );
          /*
          $detail[]=array(
            'ref_akun'  => '8003',
            'keterangan'  => $this->db->escape($data['keterangan']),
            'kredit' => $biaya_lain,
            'debet'  => 0,
            'urutan'  => 3,
          );
          */
          $jurnal=array(
            'tanggal' => $data['tgl_bayar'],
            'keterangan'  => 'Giro mundur belum cair, '.$data['keterangan'],
            'hapus' => 0,
            'ref' => $id,
            'type'  => 100,
            'details'  => $detail,
            'idref' => $id,
            'urlref'  =>'keuangan/penerimaandana',
            'no_dokumen'  => $no_dokumen
          );
          $idjurnal=$this->model_keuangan_jurnal->addJurnalUmum($jurnal);

         /* $dokumen=array(
            'nama_table'  => 'penerimaan_dana',
            'jurnal_id' => $idjurnal,
            'hapus' => 0,
            'id_transaksi'  => $id,
            'datakas' => 1,
            'id_mutasi' => 0,
            'jenis_transaksi' => 'Penerimaan Dana Customer Giro Belum Cair',
            'date_added'  => date('Y-m-d H:i:s')
    
          );
          $this->db->insert('no_dokumen',$dokumen);
          $iddokumen=$this->db->getLastId();
    
          $no_dokumen='PDG-'.$iddokumen;
          $this->db->update('no_dokumen',array('no_dokumen'=>$no_dokumen),array('id'=>$iddokumen));
          $this->db->update('penerimaan_dana',array('no_dokumen'=>$no_dokumen),array('id'=>$id));
          $this->db->update('jurnal_umum',array('no_dokumen'=>$no_dokumen),array('id'=>$idjurnal));*/
        }
      }
    }
  }
}

  public function updatePermintaan($data,$where){
    if($data['status'] == 3){
      $pb=$this->getPermintaanPembelian(array(),array(),$where);
      if($this->user->getUsername()=="pawits" | $this->user->getUsername()=="admin2"){
        echo "<pre>";print_r($pb);exit;
      }
      if($pb['status'] == 2){
        if($pb['jenis'] == 1){
          $this->load->model('sale/customer');
          $depositdata=array(
            'ref'=> $pb['id'],
      			'date_trans'	=> $pb['tgl_diterima'],
      			'nominal'	=> $pb['nominal'],
      			'keterangan'	=> 'Pembatalan deposit ',
            'bank_id'	=> $pb['bank_id'],
            'pendapatan_lain'	=> $pb['pendapatan_lain'],
            'biaya_bank'  => $pb['biaya_bank'],
            'biaya_lain'  => $pb['biaya_lain'],
            'no_dokumen'  => $pb['no_dokumen'],
            'urlref'  => 'keuangan/penerimaandana',
            'metode_pembayaran'=>$pb['metode_pembayaran'],
            'id_hutanglain'=>$pb['id_hutanglain'],
          );
          $this->model_sale_customer->cancelDeposit($depositdata,$pb['customer_id']);
        }
        if($pb['jenis'] == 2){
          $this->load->model('sale/pembayaranpenjualan');
          $depositdata=array(
            'ref'=> $pb['id'],
      			'date_trans'	=> $pb['tgl_diterima'],
      			'nominal'	=> $pb['nominal'],
      			'keterangan'	=> 'Pembatalan deposit ',
      			'bank_id'	=> $pb['bank_id'],
            'biaya_bank'  => $pb['biaya_bank'],
            'status'  => 3,
            'no_dokumen'  => $pb['no_dokumen'],
            'urlref'  => 'keuangan/penerimaandana'
          );
          $this->model_sale_pembayaranpenjualan->updatePermintaan($depositdata,array('penjualan_id' => $pb['ref']));
        }
      }
     
      $this->db->update('penerimaan_dana',$data,$where);
      
    }
    if($data['status'] == 4){
      $pb=$this->getPermintaanPembelian(array(),array(),$where);
      if($pb['status'] == 1){
        
        if($pb['metode_pembayaran'] == 3){
          //if($pb['status']== 1){
            $this->load->model('keuangan/jurnal');
            $jurnal=array();
            $detail=array();
            //if($pb['nominal'] > 0){
              $detail[]=array(
                'ref_akun'  => '2201',
                'keterangan'  => $this->db->escape($pb['keterangan']),
                'debet' => $pb['nominal']+$pb['biaya_bank'],
                'kredit'  => 0,
                'urutan'  => 1
              );
              if($pb['biaya_lain'] > 0){
              $detail[]=array(
                'ref_akun'  => '8003',
                'keterangan'  => $this->db->escape($pb['keterangan']),
                'debet' => $pb['biaya_lain'],
                'kredit'  => 0,
                'urutan'  => 2,
              );
              }
              $detail[]=array(
                'ref_akun'  => '1501',
                'keterangan'  => $this->db->escape($pb['keterangan']),
                'kredit' => $pb['nominal']+$pb['biaya_bank']+$pb['biaya_lain'],
                'debet'  => 0,
                'urutan'  => 3,
              );
              
    
              $jurnal=array(
                'tanggal' => $pb['tgl_bayar'],
                'keterangan'  => 'Pembatalan Giro mundur belum cair, '.$pb['keterangan'],
                'hapus' => 0,
                'ref' => $pb['id'],
                'type'  => 100,
                'details'  => $detail,
                'idref' => $pb['id'],
                'urlref'  =>'keuangan/penerimaandana',
                'no_dokumen'  => $pb['no_dokumen']
              );
              $this->model_keuangan_jurnal->addJurnalUmum($jurnal);
         // }
          }
        //}
      }
      $this->db->update('penerimaan_dana',$data,$where);
    }
    if($data['status'] == 2){
      $pb=$this->getPermintaanPembelian(array(),array(),$where);
      if($this->user->getUsername()=="pawits"){
				echo "<pre>";print_r($pb);exit;
			}
      if($pb['status'] == 1){
        if($pb['jenis'] == 1){
          $this->load->model('sale/customer');
          $depositdata=array(
            'ref'=> $pb['id'],
      			'date_trans'	=> $data['tgl_diterima'],
      			'nominal'	=> $pb['nominal'],
      			'keterangan'	=> $data['keterangan'],
      			'bank_id'	=> $pb['bank_id'],
				    'biaya_bank'  => $pb['biaya_bank'],
            'biaya_lain' => $pb['biaya_lain'],
            'pendapatan_lain' => $pb['pendapatan_lain'],
            'biayamarketplace' => $pb['biayamarketplace'],
            'no_dokumen'  => $pb['no_dokumen'],
            'urlref'  => 'keuangan/penerimaandana'
          );
          $this->model_sale_customer->addDeposit($depositdata,$pb['customer_id']);
          // baru 21 Desember 2019
          if($pb['metode_pembayaran']==5){
            $this->db->update('penerimaandana_hutanglain',array('status'=>3),array('id'=>$pb['id_hutanglain']));
            $this->load->model('keuangan/jurnal');
            $jurnal=array();
            $detail=array();
            $detail[]=array(
              'ref_akun'  => '2101', // hutang lain
              'keterangan'  => $this->db->escape($pb['keterangan']),
              'kredit' =>0,
              'debet'  =>$pb['nominal']+$pb['biaya_bank']+$pb['biaya_lain'],
              'urutan'  =>1,
            );
            $detail[]=array(
              'ref_akun'  => '1101', // piutang usaha
              'keterangan'  => $this->db->escape($pb['keterangan']),
              'debet' => 0,
              'kredit'  => $pb['nominal']+$pb['biaya_bank']+$pb['biaya_lain'],
              'urutan'  => 2,
            );
            $jurnal=array(
              'tanggal' => $data['tgl_diterima'],
              'keterangan'  => $data['keterangan'],
              'hapus' => 0,
              'ref' => $pb['id'],
              'type'  => 101,
              'linkterkait' => $data['keterangan'],
              'details'  => $detail,
              'idref' => $pb['id'],
              'no_dokumen'  => $pb['no_dokumen'],
              'urlref'  => 'keuangan/penerimaandana'
            );
            $this->model_keuangan_jurnal->addJurnalUmum($jurnal);
          }
          // end baru
        }
        if($pb['jenis'] == 2){
          $this->load->model('sale/pembayaranpenjualan');
          $pembayaranpenjualan=array(
            'no_po' => $pb['ref'],
            'jumlah'  => $pb['nominal'],
            'date_added'  => isset($data['tgl_diterima'])?$data['tgl_diterima']:date('Y-m-d H:i:s',time()),
            'bank_id' => $pb['bank_id'],
            'biaya_bank'  => $pb['biaya_bank'],
            'idref' => $pb['id'],
            'no_dokumen'  => $pb['no_dokumen'],
            'urlref'  => 'keuangan/penerimaandana'
          );
          $this->model_sale_pembayaranpenjualan->addPembelian($pembayaranpenjualan);
        }

        $this->db->update('penerimaan_dana',$data,$where);
        $this->load->model('keuangan/jurnal');
        if($pb['metode_pembayaran'] == 3){
          $jurnal=array();
          $detail=array();
          if($pb['nominal'] > 0){

            $detail[]=array(
              'ref_akun'  => '1501',
              'keterangan'  => $this->db->escape($pb['keterangan']),
              //'kredit' => $pb['nominal']+$pb['biaya_bank']+$pb['biaya_lain'],
              'kredit' => $pb['nominal']+$pb['biaya_bank'],
              'debet'  => 0,
              'urutan'  => 2,
            );

            $detail[]=array(
              'ref_akun'  => '2201',
              'keterangan'  => $this->db->escape($pb['keterangan']),
              //'debet' => $pb['nominal']+$pb['biaya_bank']+$pb['biaya_lain'],
              'debet' => $pb['nominal']+$pb['biaya_bank'],
              'kredit'  => 0,
              'urutan'  => 1,
            );


            $jurnal=array(
              'tanggal' => $data['tgl_diterima'],
              'keterangan'  => 'Giro mundur cair, '.$data['keterangan'],
              'hapus' => 0,
              'ref' => $pb['id'],
              'type'  => 101,
              'details'  => $detail,
              'idref' => $pb['id'],
              'no_dokumen'  => $pb['no_dokumen'],
              'urlref'  => 'keuangan/penerimaandana'
            );
            $this->model_keuangan_jurnal->addJurnalUmum($jurnal);
          }
        }
      }
    }

  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('penerimaan_dana',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('penerimaan_dana',$where);
	}
  public function totalDp($no_po){
    return $this->db->firstdetail('penerimaan_dana',array('COALESCE(SUM(nominal)) as total'),array(),array('order_id' => $no_po),array());
  }

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('penerimaan_dana',$column,$join,$where,array());
  }


}
?>
