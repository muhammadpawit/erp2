<?php
class ModelCatalogTabungmp extends Model {
	public function addTabung($data) {
		$tabung=array(
			'no_tabung'	=> $data['no_tabung'],
			'status'	=> $data['status'],
			'ukuran_tabung'	=> $data['ukuran_tabung'],
			'tglpembelian'	=> empty($data['tglpembelian'])?date('Y-m-d'):$data['tglpembelian'],
			'hargabeli'	=> 0,
			'nilaibuku'	=> 0,
			'kelompok_aset'	=> $data['kelompok_aset'],
			'hapus'	=> 0,
			'customer_id'	=> 0,
			'date_added'	=> date('Y-m-d H:i:s',time()),
			'date_modified'	=> date('Y-m-d H:i:s',time()),
			'pemilik'	=> !isset($data['pemilik'])?1:$data['pemilik'],
			'product_id'	=> !isset($data['product_id'])?0:$data['product_id']
		);
		$this->db->insert('tabung_mp',$tabung);
		$id=$this->db->getLastId();

		if($data['status'] == 6){
			$this->load->model('catalog/kartustoktabungmp');
			$kartustok=array(
				'tabung_id'	=> $id,
				'tglpeminjaman'	=> $data['tglpeminjaman'],
				'tglpengembalian'	=> '1901-01-01',
				'tglisiulang'	=> '1901-01-01',
				'customer_id'	=> $data['customer_id'],
				'invoice'	=> "",
				'ket'	=> $data['keterangan'],
				'date_added'	=> date('Y-m-d H:i:s',time()),
				'date_modified'	=> date('Y-m-d H:i:s',time()),
				'biayasewa'	=> empty($data['biayasewa'])?0:$data['biayasewa']
			);
			$this->model_catalog_kartustoktabungmp->addKartuStok($kartustok);
		}

	}

	public function editTabung($id, $data) {
		$tabung=array(
			'no_tabung'=>$data['no_tabung'],
			'status'	=> $data['status'],
			'ukuran_tabung'	=> $data['ukuran_tabung'],
			//'tglpembelian'	=> $data['tglpembelian'],
			//'hargabeli'	=> $data['hargabeli'],
			'kelompok_aset'	=> $data['kelompok_aset'],
			'date_added'	=> date('Y-m-d H:i:s',time()),
			'date_modified'	=> date('Y-m-d H:i:s',time())
		);
		$where=array(
			'id'=> $id
		);
		$this->db->update('tabung_mp',$tabung,$where);
		if($data['status'] == 6){
			$this->load->model('catalog/kartustoktabungmp');
			$kartustok=array(
				'tabung_id'	=> $id,
				'tglpeminjaman'	=> $data['tglpeminjaman'],
				'tglpengembalian'	=> '1901-01-01',
				'tglisiulang'	=> '1901-01-01',
				'customer_id'	=> $data['customer_id'],
				'invoice'	=> "",
				'ket'	=> $data['keterangan'],
				'date_added'	=> date('Y-m-d H:i:s',time()),
				'date_modified'	=> date('Y-m-d H:i:s',time()),
				'biayasewa'	=> empty($data['biayasewa'])?0:$data['biayasewa'],
				'tabel_ref'	=> '',
				'idref'	=> 0,
				'jenistransaksi'	=> 1
			);
			$this->model_catalog_kartustoktabungmp->addKartuStok($kartustok);
		}
	}

	public function editTabungInfo($id, $data) {
		$tabung=array(
			//'no_tabung'=>$data['no_tabung'],
			'status'	=> 1,
			'tglpembelian'	=> $data['tglpembelian'],
			'hargabeli'	=> $data['hargabeli'],
			'date_modified'	=> date('Y-m-d H:i:s',time())
		);
		$where=array(
			'id'=> $id
		);
		$this->db->update('tabung_mp',$tabung,$where);

	}

	public function deleteTabung($id) {

		$tabung=array(
			'hapus'	=> 1,
			'date_modified'	=> date('Y-m-d H:i:s',time())
		);
		$where=array(
			'id'=> $id
		);
		$this->db->update('tabung_mp',$tabung,$where);
	}

	public function getTabungs($data = array()) {
		$sql = "SELECT t.id,no_tabung,hargabeli,nilaibuku,t.status,k.name,o.name as ukuran,tglpembelian,t.pemilik,c.name as namaproduct, cust.name as peminjam FROM " . DB_PREFIX . "tabung_mp t LEFT JOIN product_options o ON(t.ukuran_tabung=o.product_options_id) LEFT JOIN kelompok_aset k ON(t.kelompok_aset=k.kelompok_aset_id) LEFT JOIN ".DB_PREFIX."product c ON(t.product_id=c.product_id) LEFT JOIN customer cust ON(t.customer_id=cust.customer_id)  WHERE t.hapus=0 ";
		if(isset($data['filter_no_tabung'])){
			if(!empty($data['filter_no_tabung'])){
				$sql .="  AND lower(no_tabung) LIKE '%".utf8_strtolower($data['filter_no_tabung'])."%' ";
			}
		}

		if(isset($data['filter_pemilik'])){
			if(!empty($data['filter_pemilik'])){
				$sql .="  AND pemilik='".$data['filter_pemilik']."' ";
			}
		}

		if(isset($data['filter_product_id'])){
			if(!empty($data['filter_product_id'])){
				$sql .="  AND t.product_id='".$data['filter_product_id']."' ";
			}
		}
		if(isset($data['filter_customer_id'])){
			if(!empty($data['filter_customer_id'])){
				$sql .="  AND t.customer_id='".$data['filter_customer_id']."' ";
			}
		}
		if(isset($data['filter_kelompok_aset'])){
			if(!empty($data['filter_kelompok_aset'])){
				$sql .="  AND kelompok_aset='".$data['filter_kelompok_aset']."' ";
			}
		}
		if(isset($data['filter_status'])){
			if(!empty($data['filter_status'])){
				$sql .="  AND t.status='".$data['filter_status']."' ";
			}
		}
		if(isset($data['filter_ukuran_tabung'])){
			if(!empty($data['filter_ukuran_tabung'])){
				$sql .="  AND t.ukuran_tabung='".$data['filter_ukuran_tabung']."' ";
			}
		}
		$sql .=" ORDER BY tglpembelian ";
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

			return $query->rows;

	}

	public function getTabung($tabung) {
		$query = $this->db->query("SELECT t.*,o.name as namaukuran ,pd.name as namaproduct FROM " . DB_PREFIX . "tabung_mp t LEFT JOIN product_options o ON(t.ukuran_tabung=o.product_options_id) LEFT JOIN kelompok_aset k ON(t.kelompok_aset=k.kelompok_aset_id) LEFT JOIN product pd ON(t.product_id = pd.product_id) WHERE id='".$tabung."' ");

		return $query->row;
	}

	public function getTabungByNomor($tabung,$pemilik=1,$product_id=0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tabung_mp t JOIN product_options o ON(t.ukuran_tabung=o.product_options_id) LEFT JOIN kelompok_aset k ON(t.kelompok_aset=k.kelompok_aset_id)  WHERE no_tabung='".$tabung."' AND pemilik ='".$pemilik."' AND product_id='".$product_id."'");

		return $query->row;
	}



	public function getTotalTabungs($data=array()) {
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "tabung_mp t LEFT JOIN product_options o ON(t.ukuran_tabung=o.product_options_id) LEFT JOIN kelompok_aset k ON(t.kelompok_aset=k.kelompok_aset_id)  WHERE t.hapus=0 ";
		if(isset($data['filter_no_tabung'])){
			if(!empty($data['filter_no_tabung'])){
				$sql .="  AND lower(no_tabung) LIKE '%".utf8_strtolower($data['filter_no_tabung'])."%' ";
			}
		}
		if(isset($data['filter_kelompok_aset'])){
			if(!empty($data['filter_kelompok_aset'])){
				$sql .="  AND kelompok_aset='".$data['filter_kelompok_aset']."' ";
			}
		}
		if(isset($data['filter_pemilik'])){
			if(!empty($data['filter_pemilik'])){
				$sql .="  AND pemilik='".$data['filter_pemilik']."' ";
			}
		}
		if(isset($data['filter_customer_id'])){
			if(!empty($data['filter_customer_id'])){
				$sql .="  AND t.customer_id='".$data['filter_customer_id']."' ";
			}
		}

		if(isset($data['filter_product_id'])){
			if(!empty($data['filter_product_id'])){
				$sql .="  AND t.product_id='".$data['filter_product_id']."' ";
			}
		}
		if(isset($data['filter_status'])){
			if(!empty($data['filter_status'])){
				$sql .="  AND status='".$data['filter_status']."' ";
			}
		}
		if(isset($data['filter_ukuran_tabung'])){
			if(!empty($data['filter_ukuran_tabung'])){
				$sql .="  AND ukuran_tabung='".$data['filter_ukuran_tabung']."' ";
			}
		}


			$query = $this->db->query($sql);

		return $query->row['total'];
	}

	//penyesuaian nilai
	public function penyesuaianNilai(){
		$curdate=date('Y-m-d');
		$aset=$this->getAsets(array(),array(),array('hapus'=>array('<',1)));
		foreach($aset as $a){

			$tglbeli=$a['tglpembelian'];
			$usia=(int)abs((strtotime($curdate) - strtotime($tglbeli))/(60*60*24*30));
			$usiatahun=$usia/12;

			//depresiasi
			$this->load->model('catalog/kelompokaset');
			$kel=$this->model_catalog_kelompokaset->getKelompokaset($a['kelompok_aset']);

			$manfaat=$kel['masa_manfaat'];
			$tarif=$kel['nilai_depresiasi'];

			if(($usiatahun <= $manfaat) & ($tglbeli != '1970-01-01')){

				//penyusutan=tarif* (harga/umur)
				$penyusutantahunan=($tarif/100)*($a['hargabeli']/$manfaat);
				$akumulasipenyusutan=($usia/12)*$penyusutantahunan;

				$nilaibuku=$a['hargabeli'] - $akumulasipenyusutan;
				if($a['status'] == 3){
					$nilaibuku=0;
					$akumulasipenyusutan=$a['hargabeli'];
				}

				$kartu=array(
					'aset_id'	=> $a['id'],
					'hargabeli'	=> $a['hargabeli'],
					'penyusutan'	=> $penyusutantahunan,
					'akumulasipenyusutan'=> $akumulasipenyusutan,
					'nilaibuku'=>$nilaibuku
				);
				$this->kartuaset($kartu);
				//$this->updateAset(array('nilaibuku'=>$nilaibuku));
			}
		}
	}
	public function kartuaset($data){
		$kartu=array(
			'aset_id'	=> $data['aset_id'],
			'tanggal'	=> date('Y-m-d H:i:s'),
			'hargabeli'	=> $data['hargabeli'],
			'penyusutan'	=> $data['penyusutan'],
			'akumulasipenyusutan'	=> $data['akumulasipenyusutan'],
			'nilaibuku'	=> $data['nilaibuku']
		);

		$this->db->insert('kartu_tabung',$kartu);
		$this->db->update('tabung_mp',array('nilaibuku'=>$data['nilaibuku']),array('id'=>$data['aset_id']));

	}

	public function getKartuStoks($data=array()){
		$sql="SELECT * FROM ".DB_PREFIX."kartu_tabung  WHERE aset_id='".$data['aset_id']."' ";
		if(!empty($data['tanggal'])){
			$sql .=" AND DATE(tanggal)='".$data['tanggal']."' ";
		}
		$sql.="ORDER BY id ";
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
		$sql="SELECT * FROM ".DB_PREFIX."kartu_tabung WHERE aset_id='".$data['aset_id']."' ";
		if(!empty($data['tanggal'])){
			$sql .=" AND DATE(tanggal)='".$data['tanggal']."' ";
		}
		$sql .="ORDER BY id ";

		$query = $this->db->query($sql);

		return $query->num_rows;
	}

	public function getAsets($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
		return $this->db->alljoin('tabung_mp',$column,$join,$where,$order,$limit,$offset);
	}
}
?>
