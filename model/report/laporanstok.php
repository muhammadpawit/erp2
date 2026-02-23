<?php
/*
Created by morebit   | http://morebit.co   |  info@morebit.co
*/
class ModelReportLaporanstok extends Model {
	
	public function getproduk($gudang_id){
		$sql="SELECT p.name, pg.* from product_gudang pg join product p on(p.product_id=pg.product_id) where pg.gudang_id='".$gudang_id."' and pg.quantity>0 limit 50";
		$res=$this->db->query($sql);
		return $res->rows;
	}
	public function getStokGudang($gudang_id){
		$sql="SELECT SUM(pg.quantity) as qty,SUM(pg.net_cost*pg.quantity) as net_cost FROM ".DB_PREFIX."product_gudang pg LEFT JOIN product p ON(pg.product_id=p.product_id) WHERE pg.quantity > 0 AND pg.gudang_id='".$gudang_id."'";
		$res=$this->db->query($sql);
		return $res->row;
	}



  //stok proses transfer
  public function getStokProsesTransfer($gudang_id){
    $sql="SELECT COALESCE(SUM(quantity-quantity_actual),0) as qty FROM transfer_item_product nop JOIN transfer_item no ON(nop.order_id=no.order_id) WHERE no.gudang_asal='".$gudang_id."' AND (nop.status=0 OR nop.status=2) AND no.status <> 3";
    $res=$this->db->query($sql);
	$qtytoko=$res->row['qty'];

    return $qtytoko;
  }

  //stok proses pengiriman barang


}
?>
