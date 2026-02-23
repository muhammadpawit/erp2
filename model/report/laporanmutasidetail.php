<?php
/*
Created by morebit   | http://morebit.co   |  info@morebit.co
*/
class ModelReportLaporanmutasidetail extends Model {
  /*public function getProductTerjual($product_id,$product_option_id,$data){
    $sql ="SELECT SUM(quantity) AS quantity,SUM(total) as total,SUM(quantity*net_cost) AS net_cost FROM new_order_product np JOIN";
  }*/

  public function getSaldoAwal($data){
      $sql="SELECT COALESCE(SUM(totaltagihan),0) as saldo FROM invoice WHERE DATE(date_added) < '".$data['tanggal']."' AND customer_id='".$data['customer_id']."' AND status IN(1,2,3) AND hapus=0";
      $query=$this->db->query($sql);

      $totalbayar=$this->pembayaranawal($data);
      return $query->row['saldo'] - $totalbayar;
  }

  public function pembayaranawal($data){
    $pembayarantunai="SELECT COALESCE(SUM(jumlah),0) as total FROM pembayaran_penjualan JOIN invoice iv ON(pembayaran_penjualan.penjualan_id=iv.id) WHERE pembayaran_penjualan.hapus=0 AND pembayaran_penjualan.status=1 AND iv.customer_id='".$data['customer_id']."' ";
   $pembayarantunai .=" AND pembayaran_penjualan.date_added <= '".$data['tanggal']."'";
    
    $pbyt=$this->db->query($pembayarantunai);

    //pembayarankredit
    $pembayarankredit="SELECT COALESCE(SUM(pk.total),0) as total FROM pembayaran_kredit_invoice pk JOIN pembayaran_kredit kr ON(pk.pembayaran_id=kr.id) WHERE hapus=0 AND status=1 AND kr.customer_id='".$data['customer_id']."' ";
    $pembayarankredit .=" AND kr.date_added <= '".$data['tanggal']."'";
   
    $pbyk=$this->db->query($pembayarankredit);

    $totalbayar=$pbyt->row['total'] + $pbyk->row['total'];

    return $totalbayar;
  }

  public function penambahan($data){
    $sql="SELECT COALESCE(SUM(totaltagihan),0) as saldo FROM invoice WHERE DATE(date_added) >= '".$data['filter_date_start']."' AND DATE(date_added) <= '".$data['filter_date_end']."' AND customer_id='".$data['customer_id']."' AND status IN(1,2,3) AND hapus=0";
    $query=$this->db->query($sql);

    return $query->row['saldo'];
}

/*public function pelunasan($data){
    $sql="SELECT COALESCE(SUM(totalbayar),0) as saldo FROM invoice WHERE DATE(date_added) <= '".$data['tanggal']."' AND  DATE(tgllunas) <= '".$data['tanggal']."' AND customer_id='".$data['customer_id']."' AND status IN(1,2,3) AND hapus=0";
      $query=$this->db->query($sql);

      return $query->row['saldo'];
}*/

public function pembayaran($data){
  $pembayarantunai="SELECT COALESCE(SUM(jumlah),0) as total FROM pembayaran_penjualan JOIN invoice iv ON(pembayaran_penjualan.penjualan_id=iv.id) WHERE pembayaran_penjualan.hapus=0 AND pembayaran_penjualan.status=1 AND iv.customer_id='".$data['customer_id']."' ";
 $pembayarantunai .=" AND pembayaran_penjualan.date_added >= '".$data['filter_date_start']."'  AND pembayaran_penjualan.date_added <= '".$data['filter_date_end']."'";
  
  $pbyt=$this->db->query($pembayarantunai);

  //pembayarankredit
  $pembayarankredit="SELECT COALESCE(SUM(pk.total),0) as total FROM pembayaran_kredit_invoice pk JOIN pembayaran_kredit kr ON(pk.pembayaran_id=kr.id) WHERE hapus=0 AND status=1 AND kr.customer_id='".$data['customer_id']."' ";
  $pembayarankredit .=" AND kr.date_added >= '".$data['filter_date_start']."' AND kr.date_added <= '".$data['filter_date_end']."' ";
 
  $pbyk=$this->db->query($pembayarankredit);

  $totalbayar=$pbyt->row['total'] + $pbyk->row['total'];

  return $totalbayar;
}

public function returnpenjualan($data){
    $sql="SELECT COALESCE(SUM(totalrefund),0) AS total FROM return_penjualan WHERE date_added >= '".$data['filter_date_start']."' AND date_added <= '".$data['filter_date_end']."' AND customer_id='".$data['customer_id']."'";
    $retur=$this->db->query($sql);

    return $retur->row['total'];
}

public function uangmuka($data){
    $dpst="SELECT COALESCE((SUM(saldomasuk)-SUM(saldokeluar)),0) as totaldeposit FROM history_deposit WHERE customer_id='".$data['customer_id']."' AND hapus=0 ";
      if(!empty($data['filter_tanggal'])){
          $dpst .=" AND date_trans <= '".$data['filter_date_start']."'";
      }else{
        $dpst .=" AND date_trans <= '".$data['filter_date_end']."'";
      }
      $deposit=$this->db->query($dpst);
    
    return $deposit->row['totaldeposit'];
}
  
public function penyesuaian($data){
  $dpst="SELECT COALESCE(SUM(selisih),0) as total FROM penyesuaian_deposit WHERE customer_id='".$data['customer_id']."'";
    if(!empty($data['filter_tanggal'])){
        $dpst .=" AND tgl_diproses <= '".$data['filter_date_start']."'";
    }else{
      $dpst .=" AND tgl_diproses <= '".$data['filter_date_end']."'";
    }
    $penyesuaian=$this->db->query($dpst);
  
  return $penyesuaian->row['total'];
}
}
?>
