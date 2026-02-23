<?php
class ModelSettingSetting extends Model {
	public function updatebiayakirim($data){
		foreach($data['product_special'] as $d){
			$ins = array(
				'metode_pengiriman'=>$d['metode_pengiriman'],
				'kirimtgr'=>$d['kirimtgr'],
				'kirimsby'=>$d['kirimsby'],
			);
			$this->db->update('biaya_kirim_detail',$ins,array('id'=>$d['id']));
		}
	}
	public function getbiayakirimdetailnew(){
		$d = $this->db->query("SELECT * FROM biaya_kirim_detail WHERE hapus=0 ORDER BY id ASC");
		return $d->rows;
	}
	public function getbiayakirimnew(){
		$d = $this->db->query("SELECT * FROM biaya_kirim WHERE hapus=0 ORDER BY id ASC");
		return $d->rows;
	}
	public function simpanbiayakirim($data){
		$i = array(
			'tglmulaiberlaku' => $data['tglmulaiberlaku'],
			'hapus' =>0,
			'date_added'=>date('Y-m-d H:i:s',time()),
			'user_add'=>$this->user->getId(),
		);
		$this->db->insert('biaya_kirim',$i);
		$id=$this->db->getLastId();
		foreach($data['product_special'] as $d){
			$ins = array(
				'id_biayakirim'=>$id,
				'metode_pengiriman'=>$d['metode_pengiriman'],
				'kirimtgr'=>$d['kirimtgr'],
				'kirimsby'=>$d['kirimsby'],
				'hapus'=>0,
			);
			$this->db->insert('biaya_kirim_detail',$ins);
		}
	}
	// baru 10 Maret 2020
	public function addbiayakirim($data){
		$b=array(
			'gudang_id'=>$data['gudang_id'],
			'metode_pengiriman'=>$data['metode_pengiriman'],
			'dikirim_pake'=>$data['dikirim_pake'],
			'nominal'=>$data['nominal'],
			'hapus'=>0
		);
		$this->db->insert('biaya_pengiriman',$b);
	}
	public function getbiayakirim(){
		$d = $this->db->query("SELECT * FROM biaya_pengiriman WHERE hapus=0 ORDER BY id ASC");
		return $d->rows;
	}
	public function getbiayakirimdetail($no_sj){
		$biaya=0;
		$sql="SELECT pengiriman,gudang_id FROM penjualan WHERE id='$no_sj' ";
		$sqlsj=$this->db->query($sql);
		$d=$sqlsj->row;
		if(!empty($d)){
			$sqlbiaya=$this->db->query("SELECT nominal FROM biaya_pengiriman WHERE hapus=0 AND metode_pengiriman='".$d['pengiriman']."' and gudang_id='".$d['gudang_id']."' ");
			if($sqlbiaya->row>0){
				$biaya=$sqlbiaya->row['nominal'];
			}else{
				$biaya=0;
			}
		}else{
			$biaya=0;
		}
		return $biaya;
	}
	public function getSetting($group, $store_id = 0) {
		$data = array();
		$sql="SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "'";
		$sql.='AND "group"=';
		$sql .="'".$this->db->escape($group)."'";
		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$data[$result['key']] = $result['value'];
			} else {
				$data[$result['key']] = unserialize($result['value']);
			}
		}

		return $data;
	}

	public function editSetting($group, $data, $store_id = 0) {


		foreach ($data as $key => $value) {
			$sql="DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "'";
			$sql.=' AND "group"=';
			$sql .="'".$this->db->escape($group)."' ";
			$sql.=' AND "key"=';
			$sql .="'".$this->db->escape($group)."' ";

			$this->db->query($sql);
			if (!is_array($value)) {

				$set=array(
					'store_id'=> $store_id,
					'"group"' => $group,
					'"key"'	=> $this->db->escape($key),
					'"value"'	=> $this->db->escape($value)
				);
				$this->db->insert('setting',$set);
				//$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
			} else {
				$set=array(
					'store_id'=> $store_id,
					'"group"' => $group,
					'"key"'	=> $this->db->escape($key),
					'"value"'	=> $this->db->escape($value),
					'serialized'	=>1
				);
				$this->db->insert('setting',$set);
				//$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
			}
		}
	}

	public function deleteSetting($group, $store_id = 0) {
		$sql="DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "'";
		$sql.=' AND "group"=';
		$sql .="'".$this->db->escape($group)."'";
		$this->db->query($sql);
	}

	public function getOngkir(){
		$table_data = array();

		$query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "` LIKE '".DB_PREFIX."city%'");

		foreach ($query->rows as $result) {
			if (utf8_substr($result['Tables_in_' . DB_DATABASE.' ('.DB_PREFIX.'city%)'], 0, strlen(DB_PREFIX)) == DB_PREFIX) {
				if (isset($result['Tables_in_' . DB_DATABASE.' ('.DB_PREFIX.'city%)'])) {
					$table_data[] = $result['Tables_in_' . DB_DATABASE.' ('.DB_PREFIX.'city%)'];
				}
			}
		}

		return $table_data;
		/*$res=$this->db->query("SHOW TABLES LIKE '".DB_PREFIX."city%' ");
		return $res->rows;*/
	}

	public function getDataOngkir(){
		$o=array();
		$q=$this->db->query("SELECT * FROM ".DB_PREFIX."jne_gudang ");
		foreach($q->rows as $z){
			$o[$z['gudang_id']]=$z['table_name'];
		}

		return $o;
	}

	public function addOngkir($data){
		$this->db->query("DELETE FROM ".DB_PREFIX."jne_gudang ");
		foreach($data as $key => $value){
			$this->db->query("INSERT INTO ".DB_PREFIX."jne_gudang SET gudang_id='".$key."',table_name='".$value."' ");
		}
	}
}
?>
