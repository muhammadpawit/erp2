<?php
class ModelKeuanganPendapatanlain extends Model {

  public function batalkan($id){
        $data = array(
          'id'  => $id,
        );
      $cek=$this->model_keuangan_pendapatanlain->getPermintaanPembelian(array(),array(),$data);
      if($cek['type']==1){
        $this->db->query("UPDATE customer set deposit=deposit+".$cek['pendapatan_lain']." WHERE customer_id='".$cek['customer_id']."' ");
        $historydeposit=array(
          'customer_id'=>$cek['customer_id'],
          'saldomasuk'=>$cek['pendapatan_lain'],
          'saldokeluar'=>0,
          'ref'=>$id,
          'keterangan'=>'Pembatalan pendapatan lain-lain dengan keterangan '.$cek['keterangan'].' oleh '.$this->user->getUsername(),
          'date_added'=>date('Y-m-d'),
          'date_modified'=>date('Y-m-d'),
          'hapus'=>0,
          'date_trans'=>$cek['tgl_bayar'],
          'idref'=>$cek['id'],
          'no_dokumen'=>$cek['no_dokumen'],
          'urlref'=>'keuangan/pendapatanlain',
        );
      }else{
        $this->db->query("UPDATE customer set deposit=deposit-".$cek['biaya_lain']." WHERE customer_id='".$cek['customer_id']."' ");
        $historydeposit=array(
          'customer_id'=>$cek['customer_id'],
          'saldomasuk'=>0,
          'saldokeluar'=>$cek['biaya_lain'],
          'ref'=>$id,
          'keterangan'=>'Pembatalan biaya lain-lain dengan keterangan '.$cek['keterangan'].' oleh '.$this->user->getUsername(),
          'date_added'=>date('Y-m-d'),
          'date_modified'=>date('Y-m-d'),
          'hapus'=>0,
          'date_trans'=>$cek['tgl_bayar'],
          'idref'=>$cek['id'],
          'no_dokumen'=>$cek['no_dokumen'],
          'urlref'=>'keuangan/pendapatanlain',
        );
      }
      $this->db->insert('history_deposit',$historydeposit);
      $this->db->query("UPDATE penerimaan_pendapatanlain set status='3',hapus='1' WHERE id='".$cek['id']."' ");
      $this->db->query("UPDATE jurnal_umum set hapus=1 WHERE type='7171' and urlref='keuangan/pendapatanlain' AND idref='".$cek['id']."' ");

  }
  public function getnamacust($customer_id){
    $d=$this->db->query("SELECT name FROM customer WHERE customer_id='$customer_id' ");
    return $d->row['name'];
  }
  public function Addpendapatanlain($data){
      $type=1;
      if(isset($data['type'])){
        $type=$data['type'];
      }  
      if($type==1){
        $insert=array(
          'tgl_diterima'=>$data['tgl_bayar'],
          'tgl_bayar'=>$data['tgl_bayar'],
          'status'=>2,
          'customer_id'=>$data['customer_id'],
          'customer_name'=>$this->getnamacust($data['customer_id']),
          'nominal'=>0,
          'ref'=>0,
          'metode_pembayaran'=>$data['metode_pembayaran'],
          'cetak'=>0,
          'jenis'=>$data['jenis'],
          'hapus'=>0,
          'keterangan'=>$data['keterangan'],
          'pendapatan_lain'=>$data['nominal'],
          'tgl_input'=>date('Y-m-d'),
          'type'=>$type,
        );
      }else{
        $insert=array(
          'tgl_diterima'=>$data['tgl_bayar'],
          'tgl_bayar'=>$data['tgl_bayar'],
          'status'=>2,
          'customer_id'=>$data['customer_id'],
          'customer_name'=>$this->getnamacust($data['customer_id']),
          'nominal'=>0,
          'ref'=>0,
          'metode_pembayaran'=>$data['metode_pembayaran'],
          'cetak'=>0,
          'jenis'=>$data['jenis'],
          'hapus'=>0,
          'keterangan'=>$data['keterangan'],
          'biaya_lain'=>$data['nominal'],
          'tgl_input'=>date('Y-m-d'),
          'type'=>$type,
        );
      }
      $this->db->insert('penerimaan_pendapatanlain',$insert);
      $id=$this->db->getLastId();
      $no_dokumen="PPL-".date('m-Y').'-'.$this->user->getId().'-'.$id;
      $this->db->query("UPDATE penerimaan_pendapatanlain set no_dokumen='$no_dokumen', no_pd='$no_dokumen' WHERE id='$id' ");
      if($type==1){
        // update deposit customer
        $this->db->query("UPDATE customer set deposit=deposit-".$data['nominal']." WHERE customer_id='".$data['customer_id']."' ");
        // history deposit
        $historydeposit=array(
          'customer_id'=>$data['customer_id'],
          'saldomasuk'=>0,
          'saldokeluar'=>$data['nominal'],
          'ref'=>$id,
          'keterangan'=>$data['keterangan'].' oleh '.$this->user->getUsername(),
          'date_added'=>date('Y-m-d'),
          'date_modified'=>date('Y-m-d'),
          'hapus'=>0,
          'date_trans'=>$data['tgl_bayar'],
          'idref'=>$id,
          'no_dokumen'=>$no_dokumen,
          'urlref'=>'keuangan/pendapatanlain',
        );
        $this->db->insert('history_deposit',$historydeposit);
        // proses jurnal
        $this->load->model('keuangan/jurnal');
        $detail=array();
        
        $detail[]=array(
          'ref_akun'  =>'2401',
          'debet'  => $data['nominal'],
          'kredit' => 0,
          'urutan'  =>1,
          'keterangan'  =>$data['keterangan'],
        );

        $detail[]=array(
          'ref_akun'  =>'7003',
          'kredit' => $data['nominal'],
          'debet'  =>0,
          'urutan'  =>2,
          'keterangan'  =>$data['keterangan'],
        );

      }else{
        // update deposit customer
        $this->db->query("UPDATE customer set deposit=deposit+".$data['nominal']." WHERE customer_id='".$data['customer_id']."' ");
        // history deposit
        $historydeposit=array(
          'customer_id'=>$data['customer_id'],
          'saldomasuk'=>$data['nominal'],
          'saldokeluar'=>0,
          'ref'=>$id,
          'keterangan'=>$data['keterangan'].' oleh '.$this->user->getUsername(),
          'date_added'=>date('Y-m-d'),
          'date_modified'=>date('Y-m-d'),
          'hapus'=>0,
          'date_trans'=>$data['tgl_bayar'],
          'idref'=>$id,
          'no_dokumen'=>$no_dokumen,
          'urlref'=>'keuangan/pendapatanlain',
        );
        $this->db->insert('history_deposit',$historydeposit);
        // proses jurnal
        $this->load->model('keuangan/jurnal');
        $detail=array();
        $detail[]=array(
          'ref_akun'  =>'6299',
          'kredit' =>0,
          'debet'  =>$data['nominal'],
          'urutan'  =>1,
          'keterangan'  =>'Biaya lain-lain '.$data['keterangan'],
        );

        $detail[]=array(
          'ref_akun'  =>'2401',
          'debet'  =>0,
          'kredit' =>$data['nominal'],
          'urutan'  =>2,
          'keterangan'  =>'Hutang uang muka penjualan '.$data['keterangan'],
        );

      }

      $jurnal=array(
        'tanggal' =>$data['tgl_bayar'],
        'keterangan' =>$data['keterangan'],
        'ref' => $id,
        'type' =>7171,
        'details' => $detail,
        'idref' => isset($data['ref'])?$data['ref']:$id,
        'urlref'  => isset($data['urlref'])?$data['urlref']:'keuangan/pendapatanlain',
        'no_dokumen'  => isset($data['no_dokumen'])?$data['no_dokumen']:$no_dokumen,
      );
      $jurnal_id=$this->model_keuangan_jurnal->addJurnalUmum($jurnal);

  }
  public function hitungtotal($data){
    $sql="SELECT * FROM penerimaan_pendapatanlain WHERE id>1 and hapus=0 ";
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
  public function getpenerimaandananya($id){
    $d = $this->db->query("SELECT * FROM penerimaan_pendapatanlain where id='$id' ");
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
    $sql="SELECT * FROM penerimaan_pendapatanlain WHERE id>0 and hapus=0 ";
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
    $sql="SELECT * FROM penerimaan_pendapatanlain WHERE id>1 and hapus=0 ";
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

   public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('penerimaan_pendapatanlain',$column,$join,$where,array());
  }


}
?>
