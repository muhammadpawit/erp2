<?php
class ModelPembelianKasbon extends Model {
  public function addPermintaanPembelian($data){
    $p=array(
      'no_surat'  => null,
      'surat_id'  => $data['surat_id'],
      'tujuan'  => $this->db->escape($data['tujuan']),
      'jumlah'  => $data['jumlah'],
      'status'  => 1,
      'date_added'  => date('Y-m-d H:i:s',time()),
      'date_modified' => date('Y-m-d H:i:s',time()),
      'hapus' => 0
    );
    $this->db->insert('kasbon_pembelian',$p);
    $id=$this->db->getLastId();

    $no_surat='SP-K-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;

    $this->db->update('kasbon_pembelian',array('no_surat' => $no_surat),array('id'  => $id));

  }


  public function updatePermintaan($data,$where){
    $this->db->update('kasbon_pembelian',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('kasbon_pembelian',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('kasbon_pembelian',$where);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('kasbon_pembelian',$column,$join,$where,array());
  }


}
?>
