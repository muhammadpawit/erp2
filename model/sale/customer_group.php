<?php
class ModelSaleCustomerGroup extends Model {
	// baru 19 Mei 2020
	public function getCustomerGroupsNewset($data = array()) {
		$sql = "SELECT cg.*,c.name as parent FROM " . DB_PREFIX . "customer_group cg LEFT JOIN customer_group c ON(cg.parent_id=c.customer_group_id) WHERE cg.hapus = 0 ";
		//$sql.=" AND cg.customer_group_id IN(28,30,31,32,33,34,35,36,37,38,39,40)";
		if(isset($data['filter_name'])){
			if (!empty($data['filter_name'])) {
				$sql .= " AND lower(cg.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			}
		}
		$sort_data = array(
			'cg.name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY cg.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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
		$results=$query->rows;
		$cg=array();
		foreach ($results as $result) {
			$cg[] = array(
				'customer_group_id' => $result['customer_group_id'],
				'name'        => $this->getPath($result['customer_group_id']),
				'parent'        => $result['parent'],

			);

			//$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
		}
		return $cg;
	}

	public function getCustomerGroupsNew($data = array()) {
		$sql = "SELECT cg.*,c.name as parent FROM " . DB_PREFIX . "customer_group cg LEFT JOIN customer_group c ON(cg.parent_id=c.customer_group_id) WHERE cg.hapus = 0 ";
		$sql.=" AND cg.customer_group_id IN(28,30,31,32,33,34,35,36,37,38,39,40)";
		if(isset($data['filter_name'])){
			if (!empty($data['filter_name'])) {
				$sql .= " AND lower(cg.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			}
		}
		$sort_data = array(
			'cg.name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY cg.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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
		$results=$query->rows;
		$cg=array();
		foreach ($results as $result) {
			$cg[] = array(
				'customer_group_id' => $result['customer_group_id'],
				'name'        => $this->getPath($result['customer_group_id']),
				'parent'        => $result['parent'],

			);

			//$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
		}
		return $cg;
	}

	// end baru
	public function addCustomerGroup($data) {
		$cust=array(
			'name'	=> $this->db->escape($data['name']),
			'parent_id' => $data['parent_id'],
			'date_added' => date('Y-m-d H:i:s',time()),
			'date_modified'	=> date('Y-m-d H:i:s',time()),
			'status'	=> 1,
			'hapus'	=> 0
		);

		$this->db->insert('customer_group',$cust);
		$customer_group_id = $this->db->getLastId();
	}

	public function editCustomerGroup($customer_group_id, $data) {

		$cust=array(
			'name'	=> $this->db->escape($data['name']),
			'parent_id' => $data['parent_id'],
			'date_modified'	=> date('Y-m-d H:i:s',time()),
			'status'	=> 1
		);

		$this->db->update('customer_group',$data,array('customer_group_id' => $customer_group_id));

	}

	public function deleteCustomerGroup($customer_group_id) {
		$cust=array(
			'hapus'	=> 1
		);

		$this->db->update('customer_group',$cust,array('customer_group_id' => $customer_group_id));
	}

	public function getCustomerGroup($customer_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group cg WHERE cg.customer_group_id = '" . (int)$customer_group_id . "' ");

		return $query->row;
	}

	public function getCustomerGroups($data = array()) {
		$sql = "SELECT cg.*,c.name as parent FROM " . DB_PREFIX . "customer_group cg LEFT JOIN customer_group c ON(cg.parent_id=c.customer_group_id) WHERE cg.hapus = 0 ";
		$sql.=" AND cg.customer_group_id IN(28,30,31,32,33,34,35,36,37,38,39,40)";
		if(isset($data['filter_name'])){
			if (!empty($data['filter_name'])) {
				$sql .= " AND lower(cg.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
			}
		}
		$sort_data = array(
			'cg.name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY cg.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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
		$results=$query->rows;
		$cg=array();
		foreach ($results as $result) {
			$cg[] = array(
				'customer_group_id' => $result['customer_group_id'],
				'name'        => $this->getPath($result['customer_group_id']),
				'parent'        => $result['parent'],

			);

			//$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
		}
		return $cg;
	}

	public function getPath($category_id) {
		$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "customer_group c WHERE c.customer_group_id = '" . (int)$category_id . "' ");

		if ($query->row['parent_id']) {
			return $this->getPath($query->row['parent_id']) . $this->language->get('text_separator') . $query->row['name'];
		} else {
			return $query->row['name'];
		}
	}

	public function getTotalCustomerGroups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_group");

		return $query->row['total'];
	}
	public function getCustomerGroupName($customer_group_id){
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_group cg WHERE cg.customer_group_id = '" . (int)$customer_group_id . "' ");

		$res=$query->row;
		if(!empty($res)){
			return $res['name'];
		}else{
			return '';
		}
	}
}
?>
