<?php
class ModelPamerantokoProduct extends Model {
public function getProductToko($data=array()){
		$sql="select pt.product_id,p.name,pt.qty,p.price,pt.jenis,pt.gudang_id from ".DB_PREFIX."product_toko_pameran pt LEFT JOIN ".DB_PREFIX."product p ON(pt.product_id=p.product_id)  WHERE p.product_id > 0 ";
        if (!empty($data['filter_name'])) {
			$sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}

		if(!empty($data['filter_qty'])){
			if($data['filter_qty'] == 1){
				$sql .=" AND qty > 0";
			}
			if($data['filter_qty'] == 2){
				$sql .=" AND qty = 0";
			}
            if($data['filter_qty'] == 3){
				$sql .=" AND qty < 0";
			}
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND status = '" . (int)$data['filter_status'] . "'";
		}
		/* if(!empty($data['filter_qty'])){
			if($data['filter_qty'] == 1){
				$sql .=" AND qty > 0";
			}
			if($data['filter_qty'] == 2){
				$sql .=" AND qty <= 0";
			}
		} */

		if(!empty($data['filter_toko'])){
			$sql .=" AND jenis ='".$data['filter_toko']."'";
			if (!empty($data['filter_gudang_id'])) {
				$sql .= " AND gudang_id = '" . $data['filter_gudang_id'] . "'";
			}

		}
		$sql.=" ORDER BY name,jenis,gudang_id";

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

	public function getTotalProductToko($data=array()){
		$sql="select COUNT(*) AS total from ".DB_PREFIX."product_toko_pameran pt LEFT JOIN ".DB_PREFIX."product p ON(pt.product_id=p.product_id)  WHERE p.product_id > 0  ";
        if (!empty($data['filter_name'])) {
			$sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}

		if(!empty($data['filter_qty'])){
			if($data['filter_qty'] == 1){
				$sql .=" AND qty > 0";
			}
			if($data['filter_qty'] == 2){
				$sql .=" AND qty <= 0";
			}
            if($data['filter_qty'] == 3){
				$sql .=" AND qty < 0";
			}
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}
		/* if(!empty($data['filter_qty'])){
			if($data['filter_qty'] == 1){
				$sql .=" AND qty > 0";
			}
			if($data['filter_qty'] == 2){
				$sql .=" AND qty <= 0";
			}
		} */

		if(!empty($data['filter_toko'])){
			$sql .=" AND jenis ='".$data['filter_toko']."'";
			if (!empty($data['filter_gudang_id'])) {
				$sql .= " AND gudang_id = '" . $data['filter_gudang_id'] . "'";
			}

		}
		
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
}
?>
