<?php
class ModelKeuanganPajak extends Model {

  public function getPajaks($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('pajak',$column,$join,$where,$order,$limit,$offset);
  }
  public function getPajak($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('pajak',$column,$join,$where,array());
  }
  public function updatePajak($data,$where){
    $this->db->update('pajak',$data,$where);
  }
  public function addPajak($data){
    $ak=array(
      'date_added' => date('Y-m-d H:i:s'),
      'ref' => $data['ref'],
      'jumlah'  => $data['jumlah'],
      'akun' => $data['akun'],
      'hapus' => 0,
      'jenis' => $data['jenis']
    );
    $this->db->insert('pajak',$ak);
  }



}
