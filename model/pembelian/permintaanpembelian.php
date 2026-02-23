<?php
class ModelPembelianPermintaanPembelian extends Model {
  public function addPermintaanPembelian($data){
    /*
    jenis pembelian:
    1. tunai
    2. kredit
    3. import
    */

    /*
    jenis barang
    1. bahan baku
    2. produk dagang
    3. ATK
    4. Perlengkapan/aktiva tetap
    */
    if($data['vendor_id']==null)
    {
      $vendor_id =0;
    }
    else
    {
      $vendor_id = $data['vendor_id'];
    }
    $kebutuhan_tanggal ="1970-01-01";
    if($data['kebutuhan_tanggal']!=null)
    {
      $kebutuhan_tanggal = $data['kebutuhan_tanggal'];
    }
    else
    {
      $kebutuhan_tanggal=$kebutuhan_tanggal;
    }
    $p=array(
      'no_surat'  => null,
      'jenis_pembelian' => $data['jenis_pembelian'],
      'jenis_barang'  => $data['jenis_barang'],
      'divisi_asal'  => $data['divisi_asal'],
      'tujuan_pembelian'  => $this->db->escape($data['tujuan_pembelian']),
      'status'  => 1,
      'gudang_id' => $data['gudang_id'],
      'vendor_id' => $vendor_id,
      'jenis_aktiva'  => $data['jenis_aktiva'],
      'date_added'  => date('Y-m-d H:i:s',time()),
      'date_modified' => date('Y-m-d H:i:s',time()),
      'hapus' => 0,
      'disetujui_oleh' => 0,
      'tgl_kebutuhan' => $kebutuhan_tanggal
    );
    $this->db->insert('permintaan_pembelian',$p);
    $id=$this->db->getLastId();

    $no_surat='SP-PB-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;

    $this->db->update('permintaan_pembelian',array('no_surat' => $no_surat),array('id'  => $id));
    $data['id'] = $id;
    $this->addPermintaanProduct($data);
    return $id;
  }

  public function addPermintaanProduct($data){
    foreach($data['product'] as $d){
      if(!empty($d['name'])){
      $prod=array(
        'surat_id'  => $data['id'],
        'product_id'  => $d['product_id'],
        'product_name'  => $this->db->escape($d['name']),
        'spesifikasi' =>  $this->db->escape($d['spesifikasi']),
        'quantity'  => $d['quantity'],
        'keterangan'  => $this->db->escape($d['keterangan']),
        'hapus' => 0
      );
      $this->db->insert('permintaan_pembelian_product',$prod);
    }
    }
  }
  public function updatePermintaan($data,$where){
    $this->db->update('permintaan_pembelian',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('permintaan_pembelian',$column,$join,$leftjoin,$where,array('id'=> 'DESC'),$limit,$offset);
  }

  public function totalPermintaans($where,$join=array(),$leftjoin=array()){
    return $this->db->countAll('permintaan_pembelian',$where,$join,$leftjoin);
  }

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('permintaan_pembelian',$column,$join,$where,array());
  }

  public function getPermintaanPembelianProduct($where){
    return $this->db->all('permintaan_pembelian_product',$where,array(),0,null);
  }
  public function getReferensi($id){
    $sql="SELECT sop.sales_order_id,no_so FROM sales_order_product sop JOIN sales_order so ON(sop.sales_order_id=so.id) WHERE referensi='".$id."' AND jenisref=1 ";
    $query=$this->db->query($sql);

    return $query->row;
  }
}
?>
