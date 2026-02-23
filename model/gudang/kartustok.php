<?php
/*
Purchase Order Controller
Created by morebit   | http://morebit.co   |  info@morebit.co
*/
class ModelGudangKartustok extends Model {
	// baru 27 Desember 2019
	public function getsj($no_sj){
		$d = $this->db->query("SELECT * FROM penjualan where no_sj LIKE '%".trim($no_sj)."%' ");
		return $d->row['customer_id'];
	}
	public function getcust($no_sj){
		$d = $this->db->query("SELECT * FROM customer where customer_id='$no_sj' ");
		return $d->row['name'];
	}
	// end baru
	public function addKartuStok($data){
		$netcost=$this->db->query("SELECT * FROM product_gudang WHERE product_id='".$data['product_id']."' AND gudang_id='".$data['gudang_id']."'");
		if(!empty($netcost->row)){
			$nc=$netcost->row['net_cost'];
		}else{
			$nc=0;
		}
		$kartustok=array(
			'product_id'	=> $data['product_id'],
			'product_name'	=> $this->db->escape($data['product_name']),
			'tgl'	=> $this->db->escape($data['tgl']),
			'stokkeluar'	=> $data['stokkeluar'],
			'stokmasuk'	=> $data['stokmasuk'],
			'ket'	=> $this->db->escape($data['ket']),
			'saldo'	=> $data['saldo'],
			'quantityawal'	=> $data['quantityawal'],
			'invoice'	=> $data['invoice'],
			'gudang_id'	=> $data['gudang_id'],
			'type'	=> $data['type'],
			'net_cost'	=> $nc,
			'no_dokumen'  => isset($data['no_dokumen'])?$data['no_dokumen']:'',
			'urlref'  => isset($data['urlref'])?$data['urlref']:'',
			'idref' => isset($data['idref'])?$data['idref']:0
		);

		/*
		Type:
		1. Pembelian
		2. penjualan
		3. Stok Opname
		4. Set Stok Awal
		5. Produk Cacat
		6. Produk Hilang
		*/
		$this->db->insert('kartustok_produk',$kartustok);



	}
	public function addKartuStokBahanbaku($data){
		$kartustok=array(
			'product_id'	=> $data['product_id'],
			'product_name'	=> $this->db->escape($data['product_name']),
			'tglawal'	=> $data['tglawal'],
			'tglakhir'	=> $data['tglakhir'],
			'levelawal'	=> $data['levelawal'],
			'levelakhir'	=> $data['levelakhir'],
			'qtyawal'	=> $data['qtyawal'],
			'qtyakhir'	=> $data['qtyakhir'],
			'perubahan'	=> $data['perubahan'],
			'type'	=> $data['type'],
			'ref'	=> $data['ref']
		);
		$this->db->insert('kartustok_bahanbaku',$kartustok);
	}
	public function addKartuStokGlobal($tablename,$data){
		$kartustok=array(
			'product_id'	=> $data['product_id'],
			'product_name'	=> $this->db->escape($data['product_name']),
			'tgl'	=> $this->db->escape($data['tgl']),
			'stokkeluar'	=> $data['stokkeluar'],
			'stokmasuk'	=> $data['stokmasuk'],
			'ket'	=> $this->db->escape($data['ket']),
			'saldo'	=> $data['saldo'],
			'quantityawal'	=> $data['quantityawal'],
			'invoice'	=> $data['invoice'],
			//'gudang_id'	=> $data['gudang_id'],
			'type'	=> $data['type']
		);

		/*
		Type:
		1. Pembelian
		2. penjualan
		3. Stok Opname
		4. Set Stok Awal
		5. Produk Cacat
		6. Produk Hilang
		*/
		$this->db->insert($tablename,$kartustok);



	}


	public function getKartuStoks($data=array()){
		$sql="SELECT * FROM ".DB_PREFIX."kartustok_produk k LEFT JOIN ".DB_PREFIX."type_perubahan_stok t ON(k.type=t.id) WHERE product_id='".$data['product_id']."' AND gudang_id='".$data['gudang_id']."' ";
		if(!empty($data['type'])){
			$sql .=" AND type='".$data['type']."'";
		}
		if(!empty($data['tanggal']) && !empty($data['tanggal2'])){
			//$sql .=" AND DATE(tgl)='".$data['tanggal']."' ";
			$sql .=" AND DATE(tgl) BETWEEN '".$data['tanggal']."' AND '".$data['tanggal2']."' ";
		}else  if(!empty($data['tanggal'])){
			$sql .=" AND DATE(tgl)='".$data['tanggal']."' ";
			//$sql .=" AND DATE(tgl) BETWEEN '".$data['tanggal']."' AND '".$data['tanggal2']."' ";
		}
		$sql.="ORDER BY kartustok_id ASC ";		
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



	public function getTotalKartustoks($data=array()){
		$sql="SELECT * FROM ".DB_PREFIX."kartustok_produk WHERE product_id='".$data['product_id']."' AND gudang_id='".$data['gudang_id']."' ";
		if(!empty($data['type'])){
			$sql .=" AND type='".$data['type']."'";
		}
		if(!empty($data['tanggal'])){
			$sql .=" AND DATE(tgl)='".$data['tanggal']."' ";
		}
		$sql .="ORDER BY kartustok_id ";

		$query = $this->db->query($sql);

		return $query->num_rows;
	}

	public function getKartuStokGlobals($tablename,$data=array()){
		$sql="SELECT * FROM ".DB_PREFIX.$tablename." k LEFT JOIN ".DB_PREFIX."type_perubahan_stok t ON(k.type=t.id) WHERE product_id='".$data['product_id']."' ";
		if(!empty($data['type'])){
			$sql .=" AND type='".$data['type']."'";
		}
		if(!empty($data['tanggal'])){
			$sql .=" AND DATE(tgl)='".$data['tanggal']."' ";
		}
		$sql.="ORDER BY kartustok_id ";
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



	public function getTotalKartustokGlobals($tablename,$data=array()){
		$sql="SELECT * FROM ".DB_PREFIX.$tablename." k WHERE product_id='".$data['product_id']."' ";
		if(!empty($data['type'])){
			$sql .=" AND type='".$data['type']."'";
		}
		if(!empty($data['tanggal'])){
			$sql .=" AND DATE(tgl)='".$data['tanggal']."' ";
		}
		$sql .="ORDER BY kartustok_id ";

		$query = $this->db->query($sql);

		return $query->num_rows;
	}
}
?>
