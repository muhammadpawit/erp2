<?php
class ModelKeuanganJurnalmanual extends Model {
  // baru 28 Januari 2019
  
	
	public function getnamacoa($id){
		$sql ="SELECT name from coamnb where kode_rek='$id' ";
		$d = $this->db->query($sql);
		return $d->row['name'];
	}
	
	
	
	// end baru
  /*public function addJurnalUmumManual($data){
	$linkterkait =null;
  	if(isset($data['linkterkait'])){
  		$linkterkait=$data['linkterkait'];
  	}
    $details[]=array(
      'ref_akun'  => $data['ref_debet'],
      'debet' => $data['nominal'],
      'keterangan'  => $data['keterangan'],
      'kredit'  => 0,
      'urutan'=> 1
    );
    $details[]=array(
      'ref_akun'  => $data['ref_kredit'],
      'kredit' => $data['nominal'],
      'keterangan'  => $data['keterangan'],
      'debet'  => 0,
      'urutan'=> 2
    );
    $j=array(
      'tanggal' => $data['tanggal'],
      'keterangan'  => $data['keterangan'],
      'hapus' => 0,
      'ref' => !empty($data['referensi'])?$data['referensi']:0,
	  'linkterkait' => $linkterkait,
      'type'  => 999,
      'details' => $details
    );

    $this->addJurnalUmum($j);
  }*/

  public function addJurnalUmumManual($data,$id){
    $jurnal=array(
        'tanggal'   => $data['tanggal'],
        'keterangan'    => $this->db->escape('keterangan'),
        'hapus' => 0,
        'ref'   => 0,
        'type'  => 999,
      
    );

    $this->db->insert('jurnal_umum',$jurnal);
    $id=$this->db->getLastId();

    $i=1;
    foreach($data['detail'] as $d){
      $det=array(
        'ref_akun'  => $d['ref_akun'],
        'debet' => empty($d['debet'])?0:$d['debet'],
        'kredit'  => empty($d['kredit'])?0:$d['kredit'],
        'urutan'  => empty($d['urutan'])?$i:$d['urutan'],
        'jurnal_id' => $id,
        'hapus' => 0
      );
      $this->db->insert('jurnal_umum_detail',$det);
      $i++;
     
    }

    //add ke jurnal manual

    $jurnal=array(
        'tanggal'   => $data['tanggal'],
        'keterangan'    => $this->db->escape($data['keterangan']),
        'hapus' => 0,
        'ref'   => 0,
        'type'  => 999,
        'date_added'    => date('Y-m-d H:i:s'),
        'user_id'   => $this->user->getId(),
        'jurnal_umum' => $id
      
    );

    $this->db->insert('jurnal_manual',$jurnal);
    $jm_id=$this->db->getLastId();

   $i=1;
    foreach($data['detail'] as $d){
      $det=array(
        'ref_akun'  => $d['ref_akun'],
        'debet' => empty($d['debet'])?0:$d['debet'],
        'kredit'  => empty($d['kredit'])?0:$d['kredit'],
        'urutan'  => empty($d['urutan'])?$i:$d['urutan'],
        'jurnal_id' => $jm_id,
        'hapus' => 0
      );
      $this->db->insert('jurnal_manual_detail',$det);
     
    }

  }

  public function addJurnalUmum($data){
	$linkterkait =null;
  	if(isset($data['linkterkait'])){
  		$linkterkait=$data['linkterkait'];
  	}
    $j=array(
      'tanggal' => $data['tanggal'],
      'keterangan'  => $this->db->escape($data['keterangan']),
      'hapus' => 0,
      'ref' => $data['ref'],
	  'linkterkait' => $linkterkait,
      'type'  => $data['type']
    );
    $this->db->insert('jurnal_umum',$j);
    $id=$this->db->getLastId();

    foreach($data['details'] as $d){
      $this->addJurnalUmumDetail($id,$d);
    }

    return $id;
  }
  public function addJurnalUmumDetail($jurnal_id,$data){
    $jd=array(
      'jurnal_id'=> $jurnal_id,
      'ref_akun'  => $data['ref_akun'],
      'keterangan'  => $this->db->escape($data['keterangan']),
      'debet' => $data['debet'],
      'kredit'  => $data['kredit'],
      'urutan'  => $data['urutan'],
      'hapus' => 0
    );
    $this->db->insert('jurnal_umum_detail',$jd);
  }
   
  public function jurnalUmum($data=array()){
    $sql="SELECT DISTINCT(ju.*) FROM jurnal_manual ju JOIN jurnal_manual_detail jd ON(ju.id=jd.jurnal_id) WHERE ju.hapus = 0 ";
    if(!empty($data['filter_date_start'])){
        $sql .=" AND tanggal >= '".$data['filter_date_start']."'";
    }
	/*else{
		$sql .=" AND tanggal >= '".$data['filter_date_start']."'";
	}*/
	
    if(!empty($data['filter_date_end'])){
        $sql .=" AND tanggal <= '".$data['filter_date_end']."'";
    }
	
	if(!empty($data['filter_keterangan'])){
        //$sql .=" AND lower(jd.keterangan) LIKE '%".$this->db->escape(utf8_strtolower($data['filter_keterangan']))."%' OR lower(ju.keterangan) LIKE '%".$this->db->escape(utf8_strtolower($data['filter_keterangan']))."%' ";
		$sql .=" AND lower(ju.keterangan) LIKE '%".$this->db->escape(utf8_strtolower($data['filter_keterangan']))."%' ";
    }
	
	
	if(!empty($data['filter_jenis'])){
        if(is_array($data['filter_jenis'])){
          //$arr=implode(",",$data['filter_jenis']);
          $arr='';
          foreach($data['filter_jenis'] as $f){
            $arr .="'".$f."'";
            if($f != end($data['filter_jenis'])){
              $arr .=",";
            }
          }
          $sql .=" AND jd.ref_akun IN (".$arr.")";
        }else{
          $sql .=" AND jd.ref_akun='".$data['filter_jenis']."' ";
        }
    }

    if($data['filter_ref'] != null){
        $sql .=" AND ref='".$data['filter_ref']."' ";
    }

   /* if($data['filter_nodokumen'] != null){
      $sql .=" AND no_dokumen='".$data['filter_nodokumen']."' ";
  }*/

    $sql .=" ORDER BY tanggal DESC,ju.id DESC ";

    if (isset($data['start']) || isset($data['limit'])) {
      if ($data['start'] < 0) {
        $data['start'] = 0;
      }

      if ($data['limit'] < 1) {
        $data['limit'] = 20;
      }

      $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
    }

    $query = $this->db->query($sql);
    $datas = $query->rows;
    
    return $datas;
  }
  
  public function totalss($data=array()){
    $sql="SELECT COUNT(*) as total FROM jurnal_manual ju JOIN jurnal_manual_detail jd ON(ju.id=jd.jurnal_id) WHERE ju.hapus = 0 ";
    if(!empty($data['filter_date_start'])){
        $sql .=" AND tanggal >= '".$data['filter_date_start']."'";
    }
	/*else{
		$sql .=" AND tanggal >= '".$data['filter_date_start']."'";
	}*/
	
    if(!empty($data['filter_date_end'])){
        $sql .=" AND tanggal <= '".$data['filter_date_end']."'";
    }
	
	if(!empty($data['filter_keterangan'])){
        //$sql .=" AND lower(jd.keterangan) LIKE '%".$this->db->escape(utf8_strtolower($data['filter_keterangan']))."%' OR lower(ju.keterangan) LIKE '%".$this->db->escape(utf8_strtolower($data['filter_keterangan']))."%' ";
		$sql .=" AND lower(ju.keterangan) LIKE '%".$this->db->escape(utf8_strtolower($data['filter_keterangan']))."%' ";
    }
	
	if(!empty($data['filter_jenis'])){
        if(is_array($data['filter_jenis'])){
          //$arr=implode(",",$data['filter_jenis']);
          $arr='';
          foreach($data['filter_jenis'] as $f){
            $arr .="'".$f."'";
            if($f != end($data['filter_jenis'])){
              $arr .=",";
            }
          }
          $sql .=" AND jd.ref_akun IN (".$arr.")";
        }else{
          $sql .=" AND jd.ref_akun='".$data['filter_jenis']."' ";
        }
    }

    if($data['filter_ref'] != null){
        $sql .=" AND ref='".$data['filter_ref']."' ";
    }

    

    $query = $this->db->query($sql);
    $datas = $query->rows;
    
    return $datas;
  }
  
      

  public function totalJurnalUmum($data=array()){
    $sql="SELECT COUNT(DISTINCT(ju.*)) as total FROM jurnal_manual ju JOIN jurnal_manual_detail jd ON(ju.id=jd.jurnal_id) WHERE ju.hapus = 0 ";
    if(!empty($data['filter_date_start'])){
        $sql .=" AND tanggal >= '".$data['filter_date_start']."'";
    }
    if(!empty($data['filter_date_end'])){
        $sql .=" AND tanggal <= '".$data['filter_date_end']."'";
    }
	if(!empty($data['filter_keterangan'])){
        $sql .=" AND jd.keterangan LIKE '%".$data['filter_keterangan']."%'";
    }
    if(!empty($data['filter_jenis'])){
        if(is_array($data['filter_jenis'])){
          //$arr=implode(",",$data['filter_jenis']);
          $arr='';
          foreach($data['filter_jenis'] as $f){
            $arr .="'".$f."'";
            if($f != end($data['filter_jenis'])){
              $arr .=",";
            }
          }
          $sql .=" AND jd.ref_akun IN (".$arr.")";
        }else{
          $sql .=" AND jd.ref_akun='".$data['filter_jenis']."' ";
        }
    }

    if($data['filter_ref'] != null){
        $sql .=" AND ref='".$data['filter_ref']."' ";
    }

    $query = $this->db->query($sql);
    return $query->row['total'];
  }
  
  public function getJurnalUmum($where){
    return $this->db->first('jurnal_manual',$where);
  }

  public function editJurnalUmumManual($data,$id){
    $this->db->update('jurnal_manual',array('tanggal'=>$data['tanggal']),array('id'=>$id));
    $this->db->delete('jurnal_manual_detail',array('jurnal_id'=>$id));
    $i=1;
    foreach($data['detail'] as $d){
      $det=array(
        'ref_akun'  => $d['ref_akun'],
        'debet' => empty($d['debet'])?0:$d['debet'],
        'kredit'  => empty($d['kredit'])?0:$d['kredit'],
        'urutan'  => empty($d['urutan'])?$i:$d['urutan'],
        'jurnal_id' => $id,
        'hapus' => 0
      );
      $this->db->insert('jurnal_manual_detail',$det);
     


    }

    //jurnal_umum

    $this->db->update('jurnal_umum',array('tanggal'=>$data['tanggal']),array('id'=>$data['jurnal_umum']));
    $this->db->delete('jurnal_umum_detail',array('jurnal_id'=>$data['jurnal_umum']));
    $i=1;
    foreach($data['detail'] as $d){
      $det=array(
        'ref_akun'  => $d['ref_akun'],
        'debet' => empty($d['debet'])?0:$d['debet'],
        'kredit'  => empty($d['kredit'])?0:$d['kredit'],
        'urutan'  => empty($d['urutan'])?$i:$d['urutan'],
        'jurnal_id' => $data['jurnal_umum'],
        'hapus' => 0
      );
      $this->db->insert('jurnal_umum_detail',$det);
     


    }
  }

  public function hapusJurnal($id){
   /* $this->db->delete('jurnal_manual',array('id'=>$id));
    $this->db->delete('jurnal_manual_detail',array('jurnal_id'=>$id));
*/
    $jm=$this->getJurnalUmum(array('id'=>$id));
    $this->db->update('jurnal_manual',array('hapus'=>1),array('id'=>$id));

    $this->db->delete('jurnal_umum',array('id'=>$jm['jurnal_umum']));
    $this->db->delete('jurnal_umum_detail',array('jurnal_id'=>$jm['jurnal_umum']));


  }
  
  

  public function updateJurnalumum($data,$where){
    $this->db->update('jurnal_manual',$data,$where);
  }
  public function updateJurnalUmumDetail($data,$where){
    $this->db->update('jurnal_manual_detail',$data,$where);
  }
  public function totalAkun($ref_akun,$data){
    $sql="SELECT COALESCE(SUM(debet),0) as totaldebet,COALESCE(SUM(kredit),0) as totalkredit FROM jurnal_umum_detail jd JOIN jurnal_umum ju ON(jd.jurnal_id=ju.id) WHERE ju.hapus=0 AND jd.hapus=0 AND jd.ref_akun='".$ref_akun."'";
    if(!empty($data['filter_date_start'])){
        $sql .=" AND tanggal >= '".$data['filter_date_start']."'";
    }
    if(!empty($data['filter_date_end'])){
        $sql .=" AND tanggal <= '".$data['filter_date_end']."'";
    }
    $result=$this->db->query($sql);

    /*if($ref_akun < 2000){
      $hasil=$result->row['totaldebet'] - $result->row['totalkredit'];
    }else{
      $hasil=$result->row['totalkredit'] - $result->row['totaldebet'];
    }*/
    $hasil=$result->row['totaldebet'] - $result->row['totalkredit'];
    return $hasil;
  }
  public function getDetailJurnalUmum($jurnal_id,$filter_jenis,$data){
    $join=array();
    $join[]=array(
      'tablename' => 'coamnb',
      'firsttable'  => 'jurnal_manual_detail.ref_akun',
      'secondtable' => 'coamnb.kode_rek'
    );
    if(is_array($filter_jenis)){
      if(!empty($filter_jenis)){
        $arr='';
        foreach($filter_jenis as $f){
          $arr .="'".$f."'";
          if($f != end($filter_jenis)){
            $arr .=",";
          }
        }
        //$sql .=" AND jd.ref_akun IN (".$arr.")";
        $filter_jenis=array("IN",$arr);
      }
    }
    //$detail = $this->db->alljoins('jurnal_umum_detail',array('jurnal_umum_detail.id','jurnal_umum_detail.jurnal_id','jurnal_umum_detail.ref_akun','jurnal_umum_detail.debet','jurnal_umum_detail.kredit','jurnal_umum_detail.hapus','jurnal_umum_detail.urutan','coamnb.name as keterangan'),array(),$join,array('jurnal_umum_detail.hapus'=> array('<',1),'jurnal_id' => $jurnal_id,'ref_akun'=>$filter_jenis),array('urutan'=>'ASC'));
	$detail = $this->db->alljoins('jurnal_manual_detail',array('jurnal_manual_detail.id','jurnal_manual_detail.jurnal_id','jurnal_manual_detail.ref_akun','jurnal_manual_detail.debet','jurnal_manual_detail.kredit','jurnal_manual_detail.hapus','jurnal_manual_detail.urutan','coamnb.name as keterangan'),array(),$join,array('jurnal_manual_detail.hapus'=> array('<',1),'jurnal_id' => $jurnal_id),array('urutan'=>'ASC'));
	$hasil = array();
	foreach($detail as $d){
		$debet += $d['debet'];
		$kredit += $d['kredit'];
		
		
		
	}
	$hasil = array(
		
		'detail' => $details,
	);
	return $detail;
	//return $detail;
  }
  
}
