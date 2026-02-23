<?php
class ModelCatalogGudang extends Model {
	public function addGudang($data) {
		//$sql="INSERT INTO " . DB_PREFIX . "gudang(web,nama,keterangan,statuss,deleted,printer,supplier) values('0','".$this->db->escape($data['nama'])."','',1,0,'".$this->db->escape($data['printer']).",'".$this->db->escape($data['supplier'])."')";
		$sql="INSERT INTO " . DB_PREFIX . "gudang(web,nama,keterangan,statuss,deleted,printer,supplier) values('0','".$this->db->escape($data['nama'])."','gudang',1,0,'printer','".$this->db->escape($data['supplier'])."')";
		$this->db->query($sql);


	}

	public function editGudang($gudang_id, $data) {
		$supplier=0;
		if(isset($data['supplier'])){
			$supplier = $data['supplier'];
		}
		
		$this->db->query("UPDATE " . DB_PREFIX . "gudang SET nama='".$this->db->escape($data['nama'])."',printer='".$this->db->escape($data['printer'])."', supplier='".(int)$supplier."' WHERE gudang_id = '" . (int)$gudang_id . "'");
		//$sql="UPDATE " . DB_PREFIX . "gudang SET nama='".$this->db->escape($data['nama'])."',printer='".$this->db->escape($data['printer'])."', vendor='".(int)$vendor."' WHERE gudang_id = '" . (int)$gudang_id . "'";
		//return $sql;

	}

	public function deleteGudang($gudang_id) {
		//$this->db->query("DELETE FROM " . DB_PREFIX . "gudang WHERE gudang_id = '" . (int)$gudang_id . "'");
		$this->db->query("UPDATE ".DB_PREFIX."gudang SET deleted='1' WHERE gudang_id='".$gudang_id."' ");
	}

	public function getGudang($gudang_id) {
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."gudang WHERE gudang_id ='" . (int)$gudang_id . "' AND deleted=0");

		return $query->row;
	}

	public function getGudangs($permission=false) {
		$category_data = array();
			$user_id=$this->user->getId();
			$sql="SELECT * FROM " . DB_PREFIX . "gudang WHERE deleted=0";
			if($permission){
				$sql .=" AND gudang_id IN(SELECT gudang_id FROM ".DB_PREFIX."user_gudang WHERE user_id='".$user_id."' )";
			}
			$sql .="  ORDER BY nama ASC";
			$query = $this->db->query($sql);

			foreach ($query->rows as $result) {
				$category_data[] = array(
					'gudang_id' => $result['gudang_id'],
					'nama'        => $result['nama'],
					'printer'        => $result['printer'],
					'supplier'  => $result['supplier'],
					'web'  	  => $result['web']
				);


			}




		return $category_data;
	}

	public function getGudangWeb($gudang_id){
		$res=$this->db->query("SELECT web FROM ".DB_PREFIX."gudang WHERE gudang_id='".$gudang_id."' AND deleted=0");
		return $res->row['web'];
	}



}
?>
