<?php
/*
Created by morebit   | http://morebit.co   |  info@morebit.co
*/
class ModelReportLaporanpenjualanproduktoko extends Model {
  /*public function getProductTerjual($product_id,$product_option_id,$data){
    $sql ="SELECT SUM(quantity) AS quantity,SUM(total) as total,SUM(quantity*net_cost) AS net_cost FROM new_order_product np JOIN";
  }*/
  public function getProductTerjual($data){
    $sql="SELECT op.product_id,p.name,SUM(op.qty) AS quantity,SUM((op.price*op.qty)-(op.discount*op.qty)) as total,SUM(op.qty*op.net_cost) AS net_cost,SUM(op.qty*op.discount) FROM ".DB_PREFIX."penjualan_toko_product op JOIN ".DB_PREFIX."penjualan_toko og ON(op.penjualan_pameran_id=og.penjualan_pameran_id) JOIN ".DB_PREFIX."product p ON(op.product_id=p.product_id) WHERE og.status > 0 ";
    if(!empty($data['filter_date_start'])){
			$sql .=" AND og.tanggal >= '".$data['filter_date_start']."'";

		}
		if(!empty($data['filter_date_end'])){
			$sql .=" AND og.tanggal <= '".$data['filter_date_end']."'";

		}

    if(!empty($data['filter_name'])){
			$sql .=" AND lower(name)='".utf8_strtolower($data['filter_name'])."'";

		}

    if(!empty($data['filter_pameran_id'])){
			$sql .=" AND og.pameran_id='".$data['filter_pameran_id']."' ";

		}
    $sql .=" GROUP BY op.product_id,p.name ";
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
    return $query->rows;

  }
  public function totalProductTerjual($data){
    $sql="SELECT COUNT(* as total FROM ".DB_PREFIX."penjualan_toko_product op JOIN ".DB_PREFIX."penjualan_toko og ON(op.penjualan_pameran_id=og.penjualan_pameran_id) JOIN ".DB_PREFIX."product p ON(op.product_id=p.product_id) WHERE og.status > 0 ";
    if(!empty($data['filter_date_start'])){
			$sql .=" AND og.tanggal >= '".$data['filter_date_start']."'";

		}
		if(!empty($data['filter_date_end'])){
			$sql .=" AND og.tanggal <= '".$data['filter_date_end']."'";

		}

    if(!empty($data['filter_name'])){
			$sql .=" AND lower(name)='".utf8_strtolower($data['filter_name'])."'";

		}

    if(!empty($data['filter_pameran_id'])){
			$sql .=" AND og.pameran_id='".$data['filter_pameran_id']."' ";

		}
    $sql .=" GROUP BY op.product_id,p.name ";

    $q=$this->db->query($sql);
    return $q->row['total'];
  }
}
?>
