<?php
class ModelKeuanganAkun extends Model {
  /*public function addJurnalPengeluaran($data){
    $j=array(
      'no_faktur' => $data['no_faktur'],
      'tanggal' => $data['tanggal'],
      'keterangan'  => $data['keterangan'],
      'ref' => $data['ref'],
      'jenis_debet' => $data['jenis_debet'],
      'nilai_debet' => $data['nilai_debet'],
      'jenis_kredit'  => $data['jenis_kredit'],
      'nilai_kredit'  => $data['nilai_kredit'],
      'hapus' =>0
    );
    $this->db->insert('jurnal_pengeluaran_kas',$j);
  }*/
  public function getAkuns($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('akun',$column,$join,$where,$order,$limit,$offset);
  }
}
