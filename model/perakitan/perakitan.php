<?php
class ModelPerakitanPerakitan extends Model {

	// baru 4 September 2020
	public function simpanprosesnew($data){
		$this->load->model('gudang/product');
		$stokproduklama=0;
		$stokprodukbaru=0;
		$hppproduklama=0;
		$hppprodukbaru=0;
		$qtytotal=0;
		$hppavg=0;
		$qtyawalgudang=0;
		$qtybarugudang=0;
		$hppA=0;
		$qtyA=0;
		// tambah qty produk gudang untuk produk A dan AVERAGE HPP Produk A
		// Tambah qty produk A
		//$sa = $this->getOneproductGudang($data['product_id'],$data['gudang_id']);
		$qtyawalgudang =0;
		$hppA = 0;
		$qtyA = 0;
		$qtybarugudang = $qtyawalgudang + $data['qtyperakitan'];
		//$this->updateqtygudangdariperakitan($qtybarugudang,$data['product_id'],$data['gudang_id']);
		$ip=array(
			'product_id'=>$data['product_id'],
			'gudang_id'=>$data['gudang_id'],
			'net_cost'=>0,
			'quantity'=>$data['qtyperakitan'],
			'status'=>1,
			'date_added'=>date('Y-m-d H:i:s'),
		);
		$this->db->insert('product_gudang',$ip);

		// catat kartustok

		$perakitan=$this->getOne($data['id']);

		$kartustok=array(
			'product_id'	=> $data['product_id'],
			'product_name'	=> $this->db->escape($data['nama_product']),
			'tgl'	=> date('Y-m-d H:i:s'),
			'stokkeluar'	=> 0,
			'stokmasuk'	=> $data['qtyperakitan'],
			'ket'	=> 'Perakitan ',
			'saldo'	=> $data['qtyperakitan'],
			'quantityawal'	=> 0,
			'invoice'	=> $data['id'],
			'gudang_id'	=> $data['gudang_id'],
			'type'	=> 15,
			'net_cost'	=> $hppavg,
			'no_dokumen'  => $perakitan['no_dokumen'],
			'urlref'  => 'perakitan/perakitan',
			'idref' => $data['id']
		);
		$this->db->insert('kartustok_produk',$kartustok);
		// Kurangi QTY produk B,C, dst
		$pl = $this->getDetail($data['id'],'ASC');
		$ncc=0;
		if(!empty($pl)){
			foreach($pl as $pm){
				$pgfp = $this->getProductgudangforperakitan($pm['product_id'],$data['gudang_id']);
				$this->updateqtygudangdariperakitan(($pgfp['quantity']-$pm['quantity']),$pm['product_id'],$data['gudang_id']);
				// tulis ke kartustok masing2x
				$netcost=$this->db->query("SELECT * FROM product_gudang WHERE product_id='".$pm['product_id']."' AND gudang_id='".$data['gudang_id']."'");
				if(!empty($netcost->row)){
					$nc=$netcost->row['net_cost'];
					$ncc += $netcost->row['net_cost'];
				}else{
					$nc=0;
					$ncc=0;
				}
				// catat kartustok
				$kartustoks=array(
					'product_id'	=> $pm['product_id'],
					'product_name'	=> $this->db->escape($pm['product_name']),
					'tgl'	=> date('Y-m-d H:i:s'),
					'stokkeluar'	=> $pm['quantity'],
					'stokmasuk'	=> 0,
					'ket'	=> 'Perakitan oleh '.$this->user->getUsername().'',
					'saldo'	=> $pgfp['quantity']-$pm['quantity'],
					'quantityawal'	=> $pgfp['quantity'],
					'invoice'	=> $data['id'],
					'gudang_id'	=> $data['gudang_id'],
					'type'	=> 15,
					'net_cost'	=> $nc,
					'no_dokumen'  => $perakitan['no_dokumen'],
					'urlref'  => 'perakitan/perakitan',
					'idref' => $data['id']
				);
				$this->db->insert('kartustok_produk',$kartustoks);
			}
		}
		$hppakhir=0;
		$hppakhir = (($hppA*$qtyA)+($ncc*$data['qtyperakitan']))/($qtyA+$data['qtyperakitan']);
		if($qtyawalgudang>0){
			$this->updatenetcostdariperakitan($data['gudang_id'],$data['product_id'],$ncc);
			// update status perakitan 
			$this->updateStatusPerakitan(1,$data['id'],$ncc);
		}else{
			$this->updatenetcostdariperakitan($data['gudang_id'],$data['product_id'],$hppakhir);
			// update status perakitan 
			$this->updateStatusPerakitan(1,$data['id'],$hppakhir);
		}
		return true;
	}
	// end baru

	public function addPerakitan($data){
		$perakitan = array(
			'product_id' => $data['product_id'],
			'nama_product' => $this->db->escape($data['nama_product']),
			'qty' => $data['qty'],
			'net_cost' =>0,
			'status' =>0,
			'hapus' =>0,
			'tanggal_perakitan' => $data['date_added'],
			'gudang_id' => $data['gudang_id'],
		);
		$this->db->insert('perakitan',$perakitan);
		$id=$this->db->getLastId();
		$no_dokumen ='PR-'.date('Y').'-'.date('m').'-'.$this->user->getId().'-'.$id;
		$this->db->update('perakitan',array('no_dokumen'=>$no_dokumen),array('id'=>$id));
		
		$dokumen=array(
			'nama_table'  => 'perakitan',
			'jurnal_id' => 0,
			'hapus' => 0,
			'id_transaksi'  => $id,
			'datakas' => 1,
			'id_mutasi' => 0,
			'jenis_transaksi' => 'Perakitan',
			'no_dokumen'  => $no_dokumen,
			'date_added'  => date('Y-m-d H:i:s')
	  
		  );
		  $this->db->insert('no_dokumen',$dokumen);
		
		if(isset($data['product'])){
			if(!empty($data['product'])){
				foreach($data['product'] as $p){
					$prod = array(
						'id_perakitan' => $id,
						'product_id' => $p['product_id'],
						'product_name' => empty($p['product_name'])?'-':$p['product_name'],
						'quantity' => $p['quantity'],
						'net_cost' => empty($p['net_cost'])?0:$p['net_cost'],
					);
					$this->db->insert('perakitan_detail',$prod);
				}
			}
		}
	}
	public function getAll($data){
		$sql ="SELECT * FROM perakitan WHERE hapus=0 ";
		
		if(!empty($data['filter_name'])){
			//$sql .=" AND lower(nama_product) LIKE '%".$this->db->escape($data['filter_name'])."%' ";
			$sql .=" AND nama_product LIKE '%".$this->db->escape($data['filter_name'])."%' ";
		}
		if(!empty($data['gudang_id'])){
			$sql .=" AND gudang_id='".$data['gudang_id']."' ";
		}
		$sql .="ORDER BY id DESC ";
		if (isset($data['start']) || isset($data['limit'])) {
	      if ($data['start'] < 0) {
	        $data['start'] = 0;
	      }

	      if ($data['limit'] < 1) {
	        $data['limit'] = 20;
	      }

	      $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
	    }
		$data=$this->db->query($sql);
		if($this->user->getUsername()=="pawits"){
			return $sql;
		}else { 
			return $data->rows;
		}
	}
	
	public function totalperakitan($data){
		$sql ="SELECT COUNT(*) as total FROM perakitan WHERE hapus=0 ";
		if(!empty($data['filter_name'])){
			$sql .=" AND nama_product LIKE '%".$this->db->escape($data['filter_name'])."%' ";
		}
		if(!empty($data['gudang_id'])){
			$sql .=" AND gudang_id='".$data['gudang_id']."' ";
		}
		$data=$this->db->query($sql);
		return $data->row['total'];
	}
	
	public function getOne($id){
		$sql ="SELECT * FROM perakitan WHERE hapus=0 and id='$id' ";
		$data=$this->db->query($sql);
		return $data->row;
	}
	
	public function getDetail($id,$sort){
		$sort='ASC';
		$sql ="SELECT * FROM perakitan_detail WHERE id_perakitan='$id' ORDER BY net_cost $sort ";
		$data=$this->db->query($sql);
		return $data->rows;
	}
	
	public function batalkan($id){
		$this->db->query("UPDATE perakitan set hapus=1 WHERE id='$id' ");
	}
	
	
	
	// baru 3 September 2019
	public function getOneproductGudang($id,$gudang_id){
		$sql ="SELECT * FROM product_gudang WHERE product_id='$id' and gudang_id='$gudang_id' ";
		$data=$this->db->query($sql);
		return $data->row;
	}
	
	public function updateqtygudangdariperakitan($qty,$product_id,$gudang_id){
		$this->db->query("UPDATE product_gudang set quantity='$qty' where product_id='$product_id' and gudang_id='$gudang_id' ");
	}
	
	public function getProductgudangforperakitan($product_id,$gudang_id){
		$sql="SELECT pg.product_gudang_id,p.product_id,p.name,pg.gudang_id,pg.quantity,pg.status,nama,pg.net_cost,p.jenistabung,pg.premijual,pg.premikirim,pg.premiambil,pg.premibongkar FROM ".DB_PREFIX."product p LEFT JOIN ".DB_PREFIX."product_gudang pg ON(p.product_id=pg.product_id) LEFT JOIN ".DB_PREFIX."gudang g ON(pg.gudang_id=g.gudang_id) ";
		$sql.=" WHERE p.hapus=0 AND pg.product_id='".$product_id."' AND pg.gudang_id='".$gudang_id."'";

		$res=$this->db->query($sql);
		return $res->row;
	}
	
	public function updateStatusPerakitan($status,$id,$net_cost){
		$this->db->query("UPDATE perakitan set status='$status', net_cost='$net_cost', tgl_proses='".date('Y-m-d')."' WHERE id='$id' ");
	}
	
	public function updatenetcostdariperakitan($gudang_id,$product_id,$hpp){
		$this->db->query("UPDATE product_gudang set net_cost='$hpp' where product_id='$product_id' and gudang_id='$gudang_id' ");
	}

	
	// end baru
	
	public function simpanproses($data){
		$this->load->model('gudang/product');
		$stokproduklama=0;
		$stokprodukbaru=0;
		$hppproduklama=0;
		$hppprodukbaru=0;
		$qtytotal=0;
		$hppavg=0;
		$qtyawalgudang=0;
		$qtybarugudang=0;
		$hppA=0;
		$qtyA=0;
		// tambah qty produk gudang untuk produk A dan AVERAGE HPP Produk A
		// Tambah qty produk A
		$sa = $this->getOneproductGudang($data['product_id'],$data['gudang_id']);
		$qtyawalgudang = $sa['quantity'];
		$hppA = $sa['net_cost'];
		$qtyA = $sa['quantity'];
		$qtybarugudang = $qtyawalgudang + $data['qtyperakitan'];
		$this->updateqtygudangdariperakitan($qtybarugudang,$data['product_id'],$data['gudang_id']);
		// catat kartustok

		$perakitan=$this->getOne($data['id']);

		$kartustok=array(
			'product_id'	=> $data['product_id'],
			'product_name'	=> $this->db->escape($data['nama_product']),
			'tgl'	=> date('Y-m-d H:i:s'),
			'stokkeluar'	=> 0,
			'stokmasuk'	=> $data['qtyperakitan'],
			'ket'	=> 'Perakitan ',
			'saldo'	=> $data['qtyperakitan']+$sa['quantity'],
			'quantityawal'	=> $sa['quantity'],
			'invoice'	=> $data['id'],
			'gudang_id'	=> $data['gudang_id'],
			'type'	=> 15,
			'net_cost'	=> $hppavg,
			'no_dokumen'  => $perakitan['no_dokumen'],
			'urlref'  => 'perakitan/perakitan',
			'idref' => $data['id']
		);
		$this->db->insert('kartustok_produk',$kartustok);
		// Kurangi QTY produk B,C, dst
		$pl = $this->getDetail($data['id'],'ASC');
		$ncc=0;
		if(!empty($pl)){
			foreach($pl as $pm){
				$pgfp = $this->getProductgudangforperakitan($pm['product_id'],$data['gudang_id']);
				$this->updateqtygudangdariperakitan(($pgfp['quantity']-$pm['quantity']),$pm['product_id'],$data['gudang_id']);
				// tulis ke kartustok masing2x
				$netcost=$this->db->query("SELECT * FROM product_gudang WHERE product_id='".$pm['product_id']."' AND gudang_id='".$data['gudang_id']."'");
				if(!empty($netcost->row)){
					$nc=$netcost->row['net_cost'];
					$ncc += $netcost->row['net_cost'];
				}else{
					$nc=0;
					$ncc=0;
				}
				// catat kartustok
				$kartustoks=array(
					'product_id'	=> $pm['product_id'],
					'product_name'	=> $this->db->escape($pm['product_name']),
					'tgl'	=> date('Y-m-d H:i:s'),
					'stokkeluar'	=> $pm['quantity'],
					'stokmasuk'	=> 0,
					'ket'	=> 'Perakitan oleh '.$this->user->getUsername().'',
					'saldo'	=> $pgfp['quantity']-$pm['quantity'],
					'quantityawal'	=> $pgfp['quantity'],
					'invoice'	=> $data['id'],
					'gudang_id'	=> $data['gudang_id'],
					'type'	=> 15,
					'net_cost'	=> $nc,
					'no_dokumen'  => $perakitan['no_dokumen'],
					'urlref'  => 'perakitan/perakitan',
					'idref' => $data['id']
				);
				$this->db->insert('kartustok_produk',$kartustoks);
			}
		}
		$hppakhir=0;
		$hppakhir = (($hppA*$qtyA)+($ncc*$data['qtyperakitan']))/($qtyA+$data['qtyperakitan']);
		if($qtyawalgudang>0){
			$this->updatenetcostdariperakitan($data['gudang_id'],$data['product_id'],$ncc);
			// update status perakitan 
			$this->updateStatusPerakitan(1,$data['id'],$ncc);
		}else{
			$this->updatenetcostdariperakitan($data['gudang_id'],$data['product_id'],$hppakhir);
			// update status perakitan 
			$this->updateStatusPerakitan(1,$data['id'],$hppakhir);
		}
		return true;
	}

	// baru 6 Desember 2019

	public function batalkanperakitan($data){
		$this->load->model('gudang/product');
		$stokproduklama=0;
		$stokprodukbaru=0;
		$hppproduklama=0;
		$hppprodukbaru=0;
		$qtytotal=0;
		$hppavg=0;
		$qtyawalgudang=0;
		$qtybarugudang=0;
		$hppA=0;
		$qtyA=0;
		// tambah qty produk gudang untuk produk A dan AVERAGE HPP Produk A
		// Tambah qty produk A
		$sa = $this->getOneproductGudang($data['product_id'],$data['gudang_id']);
		$qtyawalgudang = $sa['quantity'];
		$hppA = $sa['net_cost'];
		$qtyA = $sa['quantity'];
		$qtybarugudang = $qtyawalgudang - $data['qtyperakitan'];

		$perakitan=$this->getOne($data['id']);
		$this->updateqtygudangdariperakitan($qtybarugudang,$data['product_id'],$data['gudang_id']);
		// catat kartustok
		$kartustok=array(
					'product_id'	=> $data['product_id'],
					'product_name'	=> $this->db->escape($data['nama_product']),
					'tgl'	=> date('Y-m-d H:i:s'),
					'stokkeluar'	=> $data['qtyperakitan'],
					'stokmasuk'	=> 0,
					'ket'	=> 'Pembatalan Perakitan ',
					'saldo'	=> $sa['quantity']-$data['qtyperakitan'],
					'quantityawal'	=> $sa['quantity'],
					'invoice'	=> $data['id'],
					'gudang_id'	=> $data['gudang_id'],
					'type'	=> 17,
					'net_cost'	=> $hppA,
					'no_dokumen'  => $perakitan['no_dokumen'],
					'urlref'  => 'perakitan/perakitan',
					'idref' => $data['id']
				);
		$this->db->insert('kartustok_produk',$kartustok);
		// Kurangi QTY produk B,C, dst
		$pl = $this->getDetail($data['id'],'DESC');
		$ncc=$pl[0]['net_cost'];
		//echo "<pre>";print_r($pl);exit();
		if(!empty($pl)){
			foreach($pl as $pm){
				if($pm['product_id']==$data['product_id']){

				}else{
					$pgfp = $this->getProductgudangforperakitan($pm['product_id'],$data['gudang_id']);
					$this->updateqtygudangdariperakitan(($pgfp['quantity']+$pm['quantity']),$pm['product_id'],$data['gudang_id']);
					// tulis ke kartustok masing2x
					$netcost=$this->db->query("SELECT * FROM product_gudang WHERE product_id='".$pm['product_id']."' AND gudang_id='".$data['gudang_id']."'");
					if(!empty($netcost->row)){
						$nc=$netcost->row['net_cost'];
						//$ncc = $netcost->row['net_cost'];
					}else{
						$nc=0;
						$ncc=0;
					}
					// catat kartustok
					$kartustoks=array(
						'product_id'	=> $pm['product_id'],
						'product_name'	=> $this->db->escape($pm['product_name']),
						'tgl'	=> date('Y-m-d H:i:s'),
						'stokkeluar'	=> 0,
						'stokmasuk'	=> $pm['quantity'],
						'ket'	=> 'Pembatalan Perakitan ',
						'saldo'	=> $pgfp['quantity']+$pm['quantity'],
						'quantityawal'	=> $pgfp['quantity'],
						'invoice'	=> $data['id'],
						'gudang_id'	=> $data['gudang_id'],
						'type'	=> 17,
						'net_cost'	=> $nc,
						'no_dokumen'  => $perakitan['no_dokumen'],
						'urlref'  => 'perakitan/perakitan',
						'idref' => $data['id']
					);
					$this->db->insert('kartustok_produk',$kartustoks);
				}
			}
		}
		$hppakhir=0;
		//$hppakhir = (($hppA*$qtyA)+($ncc*$data['qtyperakitan']))/($qtyA+$data['qtyperakitan']);
		$hppakhir = $ncc;
		if($qtyawalgudang>0){
			$this->updatenetcostdariperakitan($data['gudang_id'],$data['product_id'],$ncc);
			// update status perakitan 
			$this->updateStatusPerakitan(4,$data['id'],$ncc);
		}else{
			$this->updatenetcostdariperakitan($data['gudang_id'],$data['product_id'],$hppakhir);
			// update status perakitan 
			$this->updateStatusPerakitan(4,$data['id'],$hppakhir);
		}
		return true;
	}


	// end baru
	
	public function updateQty($id,$qty,$jenis){
		$data=$this->getProduct($id);

		//update qty
		if($jenis == 1){
			$qtyf=$data['quantity'] + $qty;
		}
		if($jenis == 2){
			$qtyf=$data['quantity'] - $qty;
		}
	  $this->db->query("UPDATE ".DB_PREFIX."bahanbaku SET quantity='".$qtyf."' WHERE id='".$id."'");
		return $qtyf;
	}
	public function updateNetCost($id,$net_cost){
		$this->db->query("UPDATE ".DB_PREFIX."bahanbaku SET hargabeli='".$net_cost."' WHERE id='".$id."'");

	}

	/*public function updateNetCost($product_id,$net_cost){
		$this->db->query("UPDATE ".DB_PREFIX."product SET net_cost='".$net_cost."' WHERE product_id='".$product_id."'");
		$hist=array(
			'product_id'=>$product_id,
			'net_cost'=>$net_cost,
			'date_added'	=> date('Y-m-d h:i:s',time())
		);
		$this->db->insert('netcost_history',$hist);
	}*/
	public function addStokAwal($data){
		/**/
		$curqty=$this->getProduct($data['id']);
		$this->updateQty($data['id'],$data['qty'],1);
		$this->db->update('bahanbaku',array('level'=>$data['level']),array('id'=>$data['id']));
		$this->load->model('gudang/kartustok');
		$kartustok=array(
			'product_id'	=> $data['id'],
			'product_name'	=> $curqty['name'],
			'tglawal'	=> date('Y-m-d h:i:s',time()),
			'tglakhir'	=> date('Y-m-d h:i:s',time()),
			'levelawal'	=> 0,
			'levelakhir'	=> $data['level'],
			'qtyawal'	=> 0,
			'qtyakhir'	=> $data['qty'],
			'ket'	=> 'Set Stok Awal',
			'perubahan'	=> $data['qty'],
			'ref'	=> 0,
			//'gudang_id'	=> $data['gudang_id'],
			'type'	=> 4
		);

		$this->model_gudang_kartustok->addKartuStokBahanbaku($kartustok);

		/*
		Type:
		1. Pembelian
		2. penjualan
		3. Stok Opname
		4. Set Stok Awal
		5. Produk Cacat
		6. Produk Hilang
		7.produksi
		8. Penggembosan
		*/

	}

	public function stokOpname($data){
		$curqty=$this->getProduct($data['id']);

		if($data['qty'] <= $curqty['quantity']){
			$cur=$curqty['quantity'] - $data['qty'];
			//$stokmasuk=0;
			$perubahan=$cur;
			$this->updateQty($data['id'],$cur,2);
		}
		if($data['qty'] > $curqty['quantity']){
			$cur=$data['qty']-$curqty['quantity'];
			$perubahan=$cur;
			$stokkeluar=0;
			$this->updateQty($data['id'],$cur,1);
		}


		$this->load->model('gudang/kartustok');
		$kartustok=array(
			'product_id'	=> $data['id'],
			'product_name'	=> $curqty['name'],
			'tglawal'	=> date('Y-m-d h:i:s',time()),
			'tglakhir'	=> date('Y-m-d h:i:s',time()),
			'levelawal'	=> $curqty['level'],
			'levelakhir'	=> $data['level'],
			'qtyawal'	=> $curqty['quantity'],
			'qtyakhir'	=> $data['qty'],
			'ket'	=> 'Stok opname oleh '.$this->user->getName(),
			'perubahan'	=> $perubahan,
			'ref'	=> 0,
			//'gudang_id'	=> $data['gudang_id'],
			'type'	=> 5
		);

		$this->model_gudang_kartustok->addKartuStokBahanbaku($kartustok);

		/*$kartustok=array(
			'product_id'	=> $data['id'],
			'product_name'	=> $curqty['name'],
			'tgl'	=> date('Y-m-d h:i:s',time()),
			'stokkeluar'	=> $stokkeluar,
			'stokmasuk'	=> $stokmasuk,
			'ket'	=> 'Stok opname oleh '.$this->user->getName(),
			'saldo'	=> $data['qty'],
			'quantityawal'	=> $curqty['quantity'],
			'invoice'	=> '',
			//'gudang_id'	=> $data['gudang_id'],
			'type'	=> 5
		);

		$this->model_gudang_kartustok->addKartuStokGlobal('kartustok_bahanbaku',$kartustok);
*/
		/*
		Type:
		1. Pembelian
		2. penjualan
		3. Stok Opname
		4. Set Stok Awal
		5. Produk Cacat
		6. Produk Hilang
		7.produksi
		*/

	}

	public function addProduct($data) {
		$data['date_added']=date('Y-m-d H:i:s',time());
		$data['date_modified']=date('Y-m-d H:i:s',time());
		$data['hapus']=0;
		if(!isset($data['hargabeli'])){
			$data['hargabeli']=0;
		}
		$bhnbaku=array(
			'name'	=> $this->db->escape($data['name']),
			'quantity'	=> isset($data['quantity'])?$data['quantity']:0,
			'date_added'	=> date('Y-m-d H:i:s',time()),
			'date_modified'	=> date('Y-m-d H:i:s',time()),
			'hapus'	=> 0,
			'satuan'	=> $data['satuan'],
			'hargabeli'	=> 0,
			'level'	=> 0,
			'quantity'	=> 0

		);

		$this->db->insert('bahanbaku',$bhnbaku);

	}

	public function editProduct($product_id,$data) {
		$data['date_modified']=date('Y-m-d H:i:s',time());
		$sql="UPDATE ".DB_PREFIX."bahanbaku SET ";
		$i=1;
		foreach($data as $key => $value){
			if($key != 'categories' & $key != 'keyword'){
				if($i != 1){
			         $sql .=",";
				}
				if($key == 'name' | $key == 'description' | $key == 'meta_description' | $key == 'meta_keyword' | $key == 'tag'){
					$sql .=$key."= '".$this->db->escape($value)."'";
				}else if($key == 'image'){
					$sql .=$key."= '".$this->db->escape(html_entity_decode($value, ENT_QUOTES, 'UTF-8'))."'";
				}
			 else{
					$sql .=$key."= '".$value."'";
				}
			}
			$i++;
		}
		$sql .= " WHERE id='".$product_id."'";

		$this->db->query($sql);



	}

	public function getProducts($data = array()) {
		$sql="SELECT p.id,name,quantity,date_added,p.satuan,level FROM ".DB_PREFIX."bahanbaku p";
		$sql.=" WHERE hapus=0 ";
		if (!empty($data['filter_name'])) {
	    $sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
	  }
		$sql .= " ORDER BY name";
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
	public function getTotalProducts($data=array()){
		$sql="SELECT p.id,name,quantity FROM ".DB_PREFIX."bahanbaku p";
		$sql.=" WHERE hapus=0 ";
		if (!empty($data['filter_name'])) {
	    $sql .= " AND lower(name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
	  }
	    $query = $this->db->query($sql);

	    return $query->num_rows;
	}

	public function getProduct($product_id) {
	  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "bahanbaku p WHERE p.id = '" . (int)$product_id . "' AND hapus=0");

	  return $query->row;
	}


	public function deleteProduct($product_id){
		$this->db->query("UPDATE ".DB_PREFIX."bahanbaku SET hapus=1 WHERE id='".$product_id."' ");
	}

	public function getLastProductPrice($product_id,$customer_id){
		$sql="SELECT * FROM price_bahanbaku_history WHERE id='".$product_id."' AND customer_id='".$customer_id."' ORDER BY date_added DESC LIMIT 1";
		$res=$this->db->query($sql);
		return $res->row;
	}

	public function addKonversi($product_id,$datas){
		foreach($datas as $data){

			$cek=$this->cekKonversi($product_id,$data['satuan']);
			if(empty($cek)){
				$spec=array(
					'product_id' => $product_id,
					'satuan'	=> $data['satuan'],
					'nilai'	=> $data['nilai']
				);
				$this->db->insert('konversi_bahanbaku',$spec);
				//$this->db->query("INSERT INTO ".DB_PREFIX."product_special SET product_id='".$product_id."',priority='".$data['priority']."',customer_group_id='".$data['customer_group_id']."',date_start='".$data['date_start']."' ,date_end='".$data['date_end']."',price='".$data['price']."' ");
			}
		}
	}

	public function deleteKonversi($product_special_id){
		$this->db->query("DELETE FROM ".DB_PREFIX."konversi_bahanbaku WHERE id='".$product_special_id."' ");
	}

	public function cekKonversi($product_id,$satuan){
		$sql = "SELECT * FROM ".DB_PREFIX."konversi_bahanbaku WHERE product_id='".$product_id."' AND satuan='".$satuan."'";

		$res=$this->db->query($sql);

		return $res->row;
	}

	public function getKonversi($product_id) {
	  $query = $this->db->query("SELECT kb.*,s.name as name FROM " . DB_PREFIX . "konversi_bahanbaku kb LEFT JOIN satuan s ON(kb.satuan=s.id) WHERE product_id = '" . (int)$product_id . "' ORDER BY satuan");

	  return $query->rows;
	}



}
?>
