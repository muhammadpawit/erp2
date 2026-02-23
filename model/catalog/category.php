<?php
class ModelCatalogCategory extends Model {
	public function addCategory($data) {
		$column='';
		$vals='';
		$i=1;
		$data['status'] = 1;
		foreach($data as $key => $value){
			if($key != 'keyword'){
				if($i != 1){
							 $column .=",";
							 $vals .=",";
				}
				if($key == 'column'){
					$column .= '"'.$key.'"';
				}else{
					$column .= $key;
				}
				if($key == 'name' | $key == 'description' | $key == 'meta_description' | $key == 'meta_keyword'){
					$vals .=  "'".$this->db->escape($value)."'";
					//$sql .=$key."= '".$this->db->escape($value)."'";
				}else if($key == 'image'){
					$vals .= "'".$this->db->escape(html_entity_decode($value, ENT_QUOTES, 'UTF-8'))."'";
					//$sql .=$key."= '".$this->db->escape(html_entity_decode($value, ENT_QUOTES, 'UTF-8'))."'";
				}
				else{
					$vals .= "'".$value."'";
					//$sql .=$key."= '".$value."'";
				}
			}
			$i++;
		}

		$sql="INSERT INTO ".DB_PREFIX."category(".$column.") values(".$vals.")";

		$this->db->query($sql);
		$category_id = $this->db->getLastId();


	}

	public function editCategory($category_id,$data){
		$cat=array(
			'name'	=> $this->db->escape($data['name']),
			'status'	=> $data['status'],
			'date_modified'	=> date('Y-m-d H:i:s')
		);
		$this->db->update('category',$cat,array('category_id'=>$category_id));
	}



	public function deleteCategory($category_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "'");

	}

	public function getCategory($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "'");

		return $query->row;
	}

	public function getCategories($data=array()) {
			$category_data = array();

			$sql = "SELECT * FROM " . DB_PREFIX . "category c WHERE c.category_id > 0 ";
			if(isset($data['filter_name'])){
				if (!empty($data['filter_name'])) {
			    $sql .= " AND lower(c.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			  }
			}
			$sql .= " ORDER BY c.name ASC";
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

			foreach ($query->rows as $result) {
				$category_data[] = array(
					'category_id' => $result['category_id'],
					'name'        => $result['name'],
					'status'  	  => $result['status'],
					//'sort_order'  => $result['sort_order']
				);


			}



		return $category_data;
	}

	public function getAllCategories($data=array()) {
			$category_data = array();

			$sql = "SELECT c.*,f.name as parent FROM " . DB_PREFIX . "category c LEFT JOIN category f ON(c.parent_id=f.category_id) WHERE c.category_id > 0 ";
			if(isset($data['filter_name'])){
				if (!empty($data['filter_name'])) {
			    $sql .= " AND lower(c.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			  }
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

			$query = $this->db->query($sql);

			foreach ($query->rows as $result) {
				$category_data[] = array(
					'category_id' => $result['category_id'],
					'name'        => $this->getPath($result['category_id']),
					'parent'        => $result['parent'],
					'status'  	  => $result['status']

				);

				//$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
			}



		return $category_data;
	}

	public function getParentCategories($data=array()) {
			$category_data = array();

			$sql = "SELECT c.*,f.name as parent FROM " . DB_PREFIX . "category c LEFT JOIN category f ON(c.parent_id=f.category_id) WHERE c.category_id > 0 AND c.parent_id=0 ";
			if(isset($data['filter_name'])){
				if (!empty($data['filter_name'])) {
			    $sql .= " AND lower(c.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			  }
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

			$query = $this->db->query($sql);

			foreach ($query->rows as $result) {
				$category_data[] = array(
					'category_id' => $result['category_id'],
					'name'        => $this->getPath($result['category_id']),
					'parent'        => $result['parent'],
					'status'  	  => $result['status']

				);

				//$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
			}



		return $category_data;
	}


	public function getPath($category_id) {
		$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "category c WHERE c.category_id = '" . (int)$category_id . "' ");

		if ($query->row['parent_id']) {
			return $this->getPath($query->row['parent_id']) . $this->language->get('text_separator') . $query->row['name'];
		} else {
			return $query->row['name'];
		}
	}


	public function getTotalCategories($data=array()) {
		$sql="SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category";
		if(isset($data['filter_name'])){
			if (!empty($data['filter_name'])) {
				$sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			}
		}
    $query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalCategoriesByImageId($image_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category WHERE image_id = '" . (int)$image_id . "'");

		return $query->row['total'];
	}


}
?>
