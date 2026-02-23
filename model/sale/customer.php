<?php
class ModelSaleCustomer extends Model {

	public function getcustomer($customer_id){
		$sql="SELECT * FROM customer where customer_id='$customer_id' ";
		$d=$this->db->query($sql);
		return $d->row;
	}

	public function getcustomercat($customer_id){
		if(!empty($customer_id)){
			$sql="SELECT * FROM customer_group where customer_group_id='$customer_id' ";
			$d=$this->db->query($sql);
			return $d->row['name'];
		}else{
			return null;
		}
	}

	public function getcustomerprov($customer_id){
		if(!empty($customer_id)){
			$sql="SELECT * FROM country where country_id='$customer_id' ";
			$d=$this->db->query($sql);
			return $d->row['name'];
		}else{
			return null;
		}
	}

	// baru 11 Agustus 2020
	public function service($bank_id){
		$draw=$_REQUEST['draw'];
		$length=$_REQUEST['length'];
		$start=$_REQUEST['start'];
		$search=$_REQUEST['search']["value"];
		$output['data']=array();
		$output=array();
		$output['draw']=$draw;
		$pro=null;
		$cats=null;
		$filter_date_start=$_REQUEST['filter_date_start'];
		$filter_date_end=$_REQUEST['filter_date_end'];
		$filter_name=$_REQUEST['filter_name'];
		$filtersaldo=array(
			'filter_name'=>$filter_name,
			'filter_date_start'=>$filter_date_start,
			'filter_date_end'=>$filter_date_end,
		);
		$sql="SELECT customer_id,name,sum(deposit) as deposit FROM customer WHERE hapus=0 ";
		if(!empty($filter_name)){
			$sql .=" AND lower(name) LIKE '%".$this->db->escape(utf8_strtolower($filter_name))."%'";
		}
		$sql.=" GROUP BY name,customer_id ORDER BY customer_id ASC  ";
		$d=$this->db->query($sql);
		$data=$d->rows;
		$nomor_urut=$start+1;
		$total=count($data);
		$output['recordsTotal']=$output['recordsFiltered']=$total;
		$status=null;
		$i=0;
		$j=0;
		$saldoawal=0;
		$saldomasuk=0;
		$saldokeluar=0;
			foreach (($data) as $c) {
						$saldoawal=$this->getsaldoawal($filtersaldo,$c['customer_id']);
						$saldomasuk=$this->getsaldomasuk($filtersaldo,$c['customer_id']);
						$saldokeluar=$this->getsaldokeluar($filtersaldo,$c['customer_id']);
						$sisa=$saldoawal+($saldomasuk-$saldokeluar);
						if($sisa!=0){
							$output['data'][]=array($nomor_urut,$c['customer_id'],$c['name'],$this->currency->format($saldoawal),$this->currency->format($saldomasuk),$this->currency->format($saldokeluar),$this->currency->format($sisa));
							$nomor_urut++;
						}
						//$nomor_urut++;
						$i++;
			}
			if(!empty($output['data'])){
				return $output;
			}else{
				$output['data'][]=array('data tidak ditemukan','','','','','','');
				return $output;
			}
			
	}
	// end baru

	// baru 5 Asgustus 2020
	public function getsaldoawal($data,$customer_id){
		$saldoawal=0;
		$sql="SELECT SUM(saldomasuk-saldokeluar) as awal FROM history_deposit WHERE hapus=0 and customer_id='$customer_id' ";
		if(!empty($data['filter_date_start'])){
			$sql.=" AND DATE(date_trans) <='".date('Y-m-d',strtotime('-1 day',strtotime($data['filter_date_start'])))."' ";
		}else{
			$date=date('Y-m-d');
			$sql.=" AND DATE(date_trans) <='".date('Y-m-d',strtotime('-1 day',strtotime($date)))."' ";
		}
		$d=$this->db->query($sql);
		$data=$d->row;
		if(!empty($data)){
			$saldoawal=$data['awal'];
		}
		return $saldoawal;
	}

	public function getsaldomasuk($data,$customer_id){
		$saldoawal=0;
		$sql="SELECT SUM(saldomasuk) as awal FROM history_deposit WHERE hapus=0 and customer_id='$customer_id' ";
		if(!empty($data['filter_date_start'])){
			$sql.=" AND DATE(date_trans) >='".$data['filter_date_start']."' and DATE(date_trans) <='".$data['filter_date_end']."'  ";
		}
		$d=$this->db->query($sql);
		$data=$d->row;
		if(!empty($data)){
			$saldoawal=$data['awal'];
		}
		return $saldoawal;
	}

	public function getsaldokeluar($data,$customer_id){
		$saldoawal=0;
		$sql="SELECT SUM(saldokeluar) as awal FROM history_deposit WHERE hapus=0 and customer_id='$customer_id' ";
		if(!empty($data['filter_date_start'])){
			$sql.=" AND DATE(date_trans) >='".$data['filter_date_start']."' and DATE(date_trans) <='".$data['filter_date_end']."'  ";
		}
		$d=$this->db->query($sql);
		$data=$d->row;
		if(!empty($data)){
			$saldoawal=$data['awal'];
		}
		return $saldoawal;
	}

	// end baru

	// baru 13 Juni 2020
	public function getzone($country_id){
		$d=$this->db->query("SELECT * FROM zone WHERE country_id='$country_id' ");
		$data=$d->rows;
		foreach($data as $k){
			$kt.='"'.$k['zone_id'].'":"'.$k['name'].'",';
		}
		return $kt;
	}
	// baru 3 Juni 2020
	public function penjualanterakhir($customer_id){
	$date=date('Y-m-d');
		$sql="SELECT SUM(total) as total FROM invoice WHERE customer_id='$customer_id' AND date(date_added) BETWEEN '".date('Y-m-d',strtotime($date . ' -6 month'))."' AND '".$date."' AND status<>4 ";
		$d=$this->db->query($sql);
		return $d->row['total'];
	}
	// baru 20 Mei 2020
	public function formattelp($telp){
		$nohp =$telp;
		$hilangkansps= str_replace(' ','',$nohp);
		$nohpbel=substr($hilangkansps,1);
		$nodep = substr($hilangkansps,0,1);
		if($nodep=="+"){
			$angdeps = str_replace('+','',$nodep);
		}
		elseif($nodep==0){
			$angdeps = str_replace('0','+62',$nodep);
		}elseif($nodep==6){
			$angdeps = str_replace('6','6',$nodep);
		}
		$dapat = $angdeps.$nohpbel;
		return $dapat;
	}
	// baru 31 Januari 2020
	public function piutang($customer_id){
		$d=$this->db->query("SELECT sum(totaltagihan-totalbayar) as total FROM invoice WHERE customer_id='$customer_id' and status<>4 and date_added >='2019-01-01' ");
		return $d->row['total'];
	}
	public function historygirobelumcair($customer_id,$data){
		$sql="SELECT * FROM penerimaan_dana WHERE customer_id = '$customer_id' AND metode_pembayaran = '3' and status<>3 and status<>4 and status=1";
		$d=$this->db->query($sql);
		return $d->rows;
	}
	public function historygiro($customer_id,$data){
		$sql="SELECT * FROM penerimaan_dana WHERE customer_id = '$customer_id' AND metode_pembayaran = '3' and status<>3 and status<>4 and status<>1";
		$d=$this->db->query($sql);
		return $d->rows;
	}
	// end baru
	// baru 27 Januari 2020
	public function getnominalgiro($customer_id){
		$sql ="SELECT sum(nominal) as nominal FROM penerimaan_dana WHERE status<>3 and status<>4 and status=1 and hapus=0 and metode_pembayaran=3 and customer_id='$customer_id' ";
		$d = $this->db->query($sql);
		return $d->row['nominal'];
	}
	// end baru
	// baru 23 Januari 2020
	public function getcust($id){
		$sql ="SELECT * FROM customer where customer_id='$id' ";
		$d = $this->db->query($sql);
		return $d->rows;
	}
	// end baru
	// baru 20 Januari 2020
	public function addDeposithutanglain($data,$customer_id){
		$this->updateDeposit($customer_id,$data['nominal']+$data['biaya_bank'],1);
		$cust=$this->getVendor(array('customer_id'	=> $customer_id));

		if(!isset($data['biaya_bank'])){
			$data['biaya_bank']=0;
		}
		$hutang=array(
			'ref'=> isset($data['ref'])?$data['ref']:0,
			'date_trans'	=> $data['date_trans'],
			'saldomasuk'	=> $data['nominal']+$data['biaya_bank']+$data['biaya_lain'],
			'saldokeluar'	=> 0,
			'keterangan'	=> $this->db->escape($data['keterangan'].' untuk Customer '.$cust['name'].' oleh ' .$this->user->getUsername()),
			'hapus'	=> 0,
			'customer_id'=> $customer_id
		);
		
		$id=$this->addHistoryDeposit($hutang);
	}
	// end baru
	
	// baru 10 Desember 2019
	public function aktifkanlimit($customer_id,$variable){
		$this->db->update('customer',array('limit_tagihan'=>$variable),array('customer_id'=>$customer_id));
	}
	// baru 15 Oktober 2019
	public function getkota($zone_id){
		$d = $this->db->query("SELECT * FROM zone WHERE zone_id='$zone_id' ");
		return $d->row;
	}
	
	public function getprovinsi($zone_id){
		$d = $this->db->query("SELECT * FROM country WHERE country_id='$zone_id' ");
		return $d->row;
	}
	// end baru 
	// baru 9 Oktober 2019
	public function getinv($customer_id){
		$date_added=array();
		$d = $this->db->query("SELECT date_added FROM invoice WHERE status<> 4 AND customer_id='$customer_id' ORDER BY date_added DESC LIMIT 1");
		if(!empty($d->row)){
			return $d->row['date_added'];
		}else{
			return $date_added;
		}
	}
	public function newdatacust(){

		$sql ="SELECT customer.*, users.firstname, customer_group.name AS customer_group FROM customer LEFT JOIN users ON(users.user_id=customer.sales) LEFT JOIN customer_group ON(customer_group.customer_group_id=customer.customer_group_id) WHERE customer.hapus=0 ";
		$sql .=" ORDER BY customer.date_added DESC";
		//$sql .=" LIMIT 20";
		$cust = $this->db->query($sql);
		$dp = $cust->rows;
		foreach($dp as $c){
			$tanggal = $this->getinv($c['customer_id']);
            $tanggal_lahir  = strtotime(($tanggal!=null)?$tanggal:'19700101');
            $sekarang    = time(); // Waktu sekarang
            $diff   = $sekarang - $tanggal_lahir;
			$status=floor($diff / (60 * 60 * 24));
			$hasil[$status][] = $c;
		}
		$a = $hasil;
		return array_slice($a,0,1);
		
	}
	// end baru
	public function addVendor($data){
		$vendor=array(
			'name'	=>$this->db->escape($data['name']),
			'alamat'	=>$this->db->escape($data['alamat']),
			'npwp'	=>$this->db->escape($data['npwp']),
			'telephone'	=>$this->db->escape($data['telephone']),
			'telephone2'	=>$this->db->escape($data['telephone2']),
			'fax'	=>$this->db->escape($data['fax']),
			'email'	=>$this->db->escape($data['email']),
			'siup'	=>$this->db->escape($data['siup']),
			'siup_expire'	=>empty($data['siup_expire'])?date('Y-m-d'):$data['siup_expire'],
			'tdp'	=>$this->db->escape($data['tdp']),
			'tdp_expire'	=>empty($data['tdp_expire'])?date('Y-m-d'):$data['tdp_expire'],
			'npwp'	=>$this->db->escape($data['npwp']),
			'date_added'	=> date('Y-m-d H:i:s'),
			'date_modified'	=> date('Y-m-d H:i:s'),
			'status'	=>1,
			'nama_pemilik'	=> $this->db->escape($data['nama_pemilik']),
			'alamat_pemilik'	=> $this->db->escape($data['alamat_pemilik']),
			'telp_pemilik'	=> $this->db->escape($data['telp_pemilik']),
			'hp_pemilik'	=> $this->db->escape($data['hp_pemilik']),
			'tempat_lahir'	=> $this->db->escape($data['tempat_lahir']),
			'tgllahir'	=> empty($data['tgllahir'])?date('Y-m-d'):$data['tgllahir'],
			'status_perkawinan'	=> empty($data['status_perkawinan'])?0:$data['status_perkawinan'],
			'sales'	=> empty($data['sales'])?0:$data['sales'],
			'jumlah_tabung'	=> 0,
			'penjualan'	=> 0,
			'piutang'	=> isset($data['piutang'])?$data['piutang']:0,
			'customer_group_id'	=> empty($data['customer_group_id'])?0:$data['customer_group_id'],
			'limit_piutang'	=> empty($data['limit_piutang'])?0:$data['limit_piutang'],
			'title'	=> empty($data['title'])?0:$data['title'],
			'area'	=> empty($data['area'])?0:$data['area'],
			'country'	=> empty($data['country'])?0:$data['country'],
			'zone'	=> empty($data['zone'])?0:$data['zone'],
			'city'	=> empty($data['city'])?0:$data['city'],
			'deposit'	=> 0,
			'hapus'	=>0,
			'namanpwp'	=>$this->db->escape($data['namanpwp']),
			'alamatnpwp'	=>$this->db->escape($data['alamatnpwp']),
			'namaktp'	=> $this->db->escape($data['namaktp']),
			'noktp'	=>$this->db->escape($data['noktp']),
			'alamatktp'=>$this->db->escape($data['alamatktp']),
			'cara_penagihan'	=> empty($data['cara_penagihan'])?0:$data['cara_penagihan'],
			'jam_penagihan'	=> empty($data['jam_penagihan'])?'0':$data['jam_penagihan'],
			'namapicdepantengah'	=> empty($data['namapicdepantengah'])?'-':$this->db->escape($data['namapicdepantengah']),
			'namapicbelakang'	=> empty($data['namapicbelakang'])?'-':$this->db->escape($data['namapicbelakang']),
			'kodepos'=>empty($data['kodepos'])?'-':$data['kodepos'],
		);
		$this->db->insert('customer',$vendor);
	}
	public function updateVendor($data,$where=array()){
		$ins=array(
			'name'	=>$this->db->escape($data['name']),
			'alamat'	=>$this->db->escape($data['alamat']),
			'npwp'	=>$this->db->escape($data['npwp']),
			'telephone'	=>$this->db->escape($data['telephone']),
			'telephone2'	=>$this->db->escape($data['telephone2']),
			'fax'	=>$this->db->escape($data['fax']),
			'email'	=>$this->db->escape($data['email']),
			'siup'	=>$this->db->escape($data['siup']),
			'siup_expire'	=>empty($data['siup_expire'])?date('Y-m-d'):$data['siup_expire'],
			'tdp'	=>$this->db->escape($data['tdp']),
			'tdp_expire'	=>empty($data['tdp_expire'])?date('Y-m-d'):$data['tdp_expire'],
			'npwp'	=>$this->db->escape($data['npwp']),
			'nama_pemilik'	=> $this->db->escape($data['nama_pemilik']),
			'alamat_pemilik'	=> $this->db->escape($data['alamat_pemilik']),
			'telp_pemilik'	=> $this->db->escape($data['telp_pemilik']),
			'hp_pemilik'	=> $this->db->escape($data['hp_pemilik']),
			'tempat_lahir'	=> $this->db->escape($data['tempat_lahir']),
			'tgllahir'	=> empty($data['tgllahir'])?date('Y-m-d'):$data['tgllahir'],
			'status_perkawinan'	=> empty($data['status_perkawinan'])?0:$data['status_perkawinan'],
			'sales'	=> empty($data['sales'])?$data['salesold']:$data['sales'],
			'customer_group_id'	=> empty($data['customer_group_id'])?0:$data['customer_group_id'],
			'title'	=> empty($data['title'])?0:$data['title'],
			'area'	=> empty($data['area'])?0:$data['area'],
			'country'	=> empty($data['country'])?0:$data['country'],
			'zone'	=> empty($data['zone'])?0:$data['zone'],
			'city'	=> empty($data['city'])?0:$data['city'],
			'hapus'	=>0,
			'namanpwp'	=>$this->db->escape($data['namanpwp']),
			'alamatnpwp'	=>$this->db->escape($data['alamatnpwp']),
			'namaktp'	=> $this->db->escape($data['namaktp']),
			'noktp'	=>$this->db->escape($data['noktp']),
			'alamatktp'=>$this->db->escape($data['alamatktp']),
			'cara_penagihan'	=> empty($data['cara_penagihan'])?0:$data['cara_penagihan'],
			'jam_penagihan'	=> empty($data['jam_penagihan'])?'0':$data['jam_penagihan'],
			'namapicdepantengah'	=> empty($data['namapicdepantengah'])?'-':$this->db->escape($data['namapicdepantengah']),
			'namapicbelakang'	=> empty($data['namapicbelakang'])?'-':$this->db->escape($data['namapicbelakang']),
			'kodepos'=>empty($data['kodepos'])?'-':$data['kodepos'],
		);
		if(isset($data['jadwalpenagihan']) OR isset($data['cara_penagihan'])){
			if(!empty($data['jadwalpenagihan']) OR isset($data['cara_penagihan'])){
				$this->db->update('customer',$ins,$where);
			}else{
				$this->db->update('customer',$data,$where);
			}
		}else{
			$this->db->update('customer',$data,$where);
		}
		if(isset($data['jadwalpenagihan'])){
			if(!empty($data['jadwalpenagihan'])){
				$cek = $this->db->query("SELECT * FROM customer_jadwalpenagihan WHERE customer_id='".$where['customer_id']."' ");
				$r=$cek->rows;
				if(!empty($r)){
				$this->db->query("DELETE FROM customer_jadwalpenagihan WHERE customer_id='".$where['customer_id']."' ");
				}
				foreach($data['jadwalpenagihan'] as $d=>$value){
					$in=array(
						'customer_id' =>$where['customer_id'],
						'hari' =>$value,
					);
					$this->db->insert('customer_jadwalpenagihan',$in);
				}				
			}
		}
	}

	public function gethari($customer_id){
		$hasil = array();
		$d= $this->db->query("SELECT hari FROM customer_jadwalpenagihan WHERE customer_id='$customer_id' ");
		foreach($d->rows as $h){
			$hasil[]=$h['hari'];
		}
		return $hasil;
	}
	public function getharihari(){
		$d= $this->db->query("SELECT * FROM hari ");
		return $d->rows;
	}
	public function getVendor($where){
		return $this->db->first('customer',$where);
	}
	public function getVendors($where,$order,$limit,$offset){
		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$where['sales']=$this->user->getId();
		}
		$join=array();
		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'=>'users',
			'firsttable'	=> 'customer.sales',
			'secondtable'	=> 'users.user_id'
		);
		$leftjoin[]=array(
			'tablename'=>'customer_group',
			'firsttable'	=> 'customer.customer_group_id',
			'secondtable'	=> 'customer_group.customer_group_id'
		);

		$leftjoin[]=array(
			'tablename'=>'zone',
			'firsttable'	=> 'customer.zone',
			'secondtable'	=> 'zone.zone_id'
		);
		
		$leftjoin[]=array(
			'tablename'=>'country',
			'firsttable'	=> 'customer.country',
			'secondtable'	=> 'country.country_id'
		);

		$column=array('customer.*','users.firstname','customer_group.name AS customer_group', 'zone.name as namakota','country.name as namaprovinsi');
		$order=array('customer.customer_id'=> 'DESC');
		return $this->db->alljoins('customer',$column,$join,$leftjoin,$where,$order,$limit,$offset);
	}
	public function getVendorsnew($data){
		$sql ="SELECT customer_id,name,sum(deposit) as deposit FROM customer WHERE hapus=0 ";
		if(!empty($data['filter_name'])){
			$sql .=" AND lower(name) LIKE '%".$this->db->escape(utf8_strtolower($data['filter_name']))."%'";
			//lower(jd.keterangan) LIKE '%".$this->db->escape(utf8_strtolower($data['filter_keterangan']))."%'
		}
		if(!empty($data['deposit'])){
			if($data['deposit']==1){
				$sql .=" AND deposit is not null AND deposit >0 ";
				$sql.=" GROUP BY name,customer_id ";
			}else if($data['deposit']==2){
				$sql .=" AND deposit is not null AND deposit < 0 ";
				$sql.=" GROUP BY name,customer_id ";
			}else if($data['deposit']==3){
				$sql .=" AND deposit is not null and deposit > 0 and deposit <= 1000 ";
				$sql.=" GROUP BY name,customer_id ";
			}else if($data['deposit']==4){
				$sql.=" GROUP BY name,customer_id ";
				$sql .=" ORDER BY name ASC";
			}else if($data['deposit']==5){
				$sql.=" GROUP BY name,customer_id ";
				$sql .=" ORDER BY name DESC";
			}else if($data['deposit']==6){
				$sql .=" AND deposit >=0 OR deposit is not null ";
				$sql.=" GROUP BY name,customer_id ";
				$sql.=" ORDER BY sum(deposit) DESC";
			}else if($data['deposit']==7){
				$sql .=" AND deposit >=0 OR deposit is not null";
				$sql.=" GROUP BY name,customer_id ";
				$sql.=" ORDER BY sum(deposit) ASC";
			}else if($data['deposit']==8){
				$sql .=" AND deposit <>0 ";
				$sql.=" GROUP BY name,customer_id ";
				$sql.=" ORDER BY name ASC,customer_id DESC ";
			}
			else{
				$sql.=" GROUP BY name,customer_id ";
			}
		}else{
			$sql.=" GROUP BY name,customer_id ";
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

		$d= $this->db->query($sql);
		return $d->rows;
	}
	public function getVendorsnewtotal($data){
		$sql ="SELECT * FROM customer where customer_id > 0 and hapus=0";
		if(!empty($data['filter_name'])){
			$sql .=" AND lower(name) LIKE '%".$this->db->escape(utf8_strtolower($data['filter_name']))."%'";
			//lower(jd.keterangan) LIKE '%".$this->db->escape(utf8_strtolower($data['filter_keterangan']))."%'
		}
		if(!empty($data['deposit'])){
			if($data['deposit']==1){
				$sql .=" AND deposit is not null AND deposit >0 ";
				$sql.=" GROUP BY name,customer_id ";
			}else if($data['deposit']==2){
				$sql .=" AND deposit is not null AND deposit < 0";
				$sql.=" GROUP BY name,customer_id ";
			}else if($data['deposit']==3){
				$sql .=" AND deposit is not null and deposit > 0 and deposit <= 1000 ";
				$sql.=" GROUP BY name,customer_id ";
			}else if($data['deposit']==4){
				$sql.=" GROUP BY name,customer_id ";
				$sql .=" ORDER BY name ASC";
			}else if($data['deposit']==5){
				$sql.=" GROUP BY name,customer_id ";
				$sql .=" ORDER BY name DESC";
			}else if($data['deposit']==6){
				$sql .=" AND deposit >=0 OR deposit is not null ";
				$sql.=" GROUP BY name,customer_id ";
				$sql.=" ORDER BY sum(deposit) DESC";
			}else if($data['deposit']==7){
				$sql .=" AND deposit >=0 OR deposit is not null ";
				$sql.=" GROUP BY name,customer_id ";
				$sql.=" ORDER BY sum(deposit) ASC";
			}else{
				$sql.=" GROUP BY name,customer_id ";
			}			
		}else{
			$sql.=" GROUP BY name,customer_id ";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				//$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
			}

		$d= $this->db->query($sql);
		return $d->rows;
	}
	public function totalVendors($where){
		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		if($custdata != 1){
			$where['sales']=$this->user->getId();
		}

		$join=array();
		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'=>'users',
			'firsttable'	=> 'customer.sales',
			'secondtable'	=> 'users.user_id'
		);

		return $this->db->countAll('customer',$where,$join,$leftjoin);
	}
	public function updateTabung($id,$tabung,$jenis){
		$data=$this->getVendor(array('customer_id'=>$id));

		//update qty
		if($jenis == 1){
			$qtyf=$data['jumlah_tabung'] + $hutang;
		}
		if($jenis == 2){
			$qtyf=$data['jumlah_tabung'] - $hutang;
		}
		$this->updateVendor(array('jumlah_tabung'=>$qtyf),array('customer_id'	=> $id));
	  //$this->db->query("UPDATE ".DB_PREFIX."bahanbaku SET quantity='".$qtyf."' WHERE id='".$id."'");
	}
	public function updatePenjualan($id,$penj,$jenis){
		$data=$this->getVendor(array('customer_id'=>$id));

		//update qty
		if($jenis == 1){
			$qtyf=$data['penjualan'] + $penj;
		}
		if($jenis == 2){
			$qtyf=$data['penjualan'] - $penj;
		}
		$this->updateVendor(array('penjualan'=>$qtyf),array('customer_id'	=> $id));
	  //$this->db->query("UPDATE ".DB_PREFIX."bahanbaku SET quantity='".$qtyf."' WHERE id='".$id."'");
	}

	public function updatePiutang($id,$penj,$jenis){
		$data=$this->getVendor(array('customer_id'=>$id));

		//update qty
		if($jenis == 1){
			$qtyf=$data['piutang'] + $penj;
		}
		if($jenis == 2){
			$qtyf=$data['piutang'] - $penj;
		}
		$this->updateVendor(array('piutang'=>$qtyf),array('customer_id'	=> $id));
	  //$this->db->query("UPDATE ".DB_PREFIX."bahanbaku SET quantity='".$qtyf."' WHERE id='".$id."'");
	}

	public function updateDeposit($id,$penj,$jenis){
		$data=$this->getVendor(array('customer_id'=>$id));

		//update qty


		$total=$this->db->query("SELECT COALESCE((SUM(saldomasuk)-SUM(saldokeluar)),0) as total FROM history_deposit WHERE customer_id='".$id."'");
		if($jenis == 1){
			//$qtyf=$data['deposit'] + $penj;
			$qtyf=$total->row['total'] + $penj;
		}
		if($jenis == 2){
			//$qtyf=$data['deposit'] - $penj;
			$qtyf=$total->row['total'] - $penj;
		}
		$this->db->update('customer',array('deposit'=>$qtyf),array('customer_id'	=> $id));
	  //$this->db->query("UPDATE ".DB_PREFIX."bahanbaku SET quantity='".$qtyf."' WHERE id='".$id."'");


	}

	public function addHistoryDeposit($data){
		$hutang=array(
			'ref'=> $data['ref'],
			'date_trans'	=> $data['date_trans'],
			'saldomasuk'	=> $data['saldomasuk'],
			'saldokeluar'	=> $data['saldokeluar'],
			'keterangan'	=> $this->db->escape($data['keterangan']),
			'hapus'	=> 0,
			'customer_id'=> $data['customer_id'],
			'date_added'	=> date('Y-m-d H:i:s'),
			'date_modified' => date('Y-m-d H:i:s'),
			'idref'	=> $data['ref'],
			'no_dokumen'	=> isset($data['no_dokumen'])?$data['no_dokumen']:'',
			'urlref'	=> isset($data['urlref'])?$data['urlref']:''
		);
		$this->db->insert('history_deposit',$hutang);
		$id=$this->db->getLastId();

		return $id;
	}

	public function addDeposit($data,$customer_id){
		$this->updateDeposit($customer_id,$data['nominal']+$data['biaya_bank']+$data['biayamarketplace']+$data['biaya_lain']-$data['pendapatan_lain'],1);
		$cust=$this->getVendor(array('customer_id'	=> $customer_id));

		if(!isset($data['biaya_bank'])){
			$data['biaya_bank']=0;
		}
		$keterangan=$this->db->escape($data['keterangan'].' untuk Customer '.$cust['name'] .' oleh ' .$this->user->getUsername());
		$hutang=array(
			'ref'=> isset($data['ref'])?$data['ref']:0,
			'date_trans'	=> $data['date_trans'],
			'saldomasuk'	=> $data['nominal']+$data['biaya_bank']+$data['biaya_lain']+$data['biayamarketplace']-$data['pendapatan_lain'],
			'saldokeluar'	=>0,
			'keterangan'	=> $keterangan,
			'hapus'	=> 0,
			'customer_id'=> $customer_id,
			'idref'	=> isset($data['ref'])?$data['ref']:0,
			'urlref'	=> isset($data['urlref'])?$data['urlref']:'',
			'no_dokumen'	=> isset($data['no_dokumen'])?$data['no_dokumen']:'',
		);		
		$id=$this->addHistoryDeposit($hutang);
		$this->load->model('keuangan/bank');
		$this->load->model('keuangan/jurnal');
		if($data['nominal']>0){		
				$b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $data['bank_id']));
				$saldo=$b['saldo'] + $data['nominal'];
				$this->model_keuangan_bank->editBank(array('saldo'  => $saldo),array('id'=> $data['bank_id']));
				/*
				hutang prk

				*/

				if($b['saldo'] < 0){
					if($b['hutangprk'] == 1){
						if($b['saldo']+$data['nominal'] > 0){
							//saldo -500
							//nominal 1000
							//hutang prk= 0 - saldo
							//kas nominal - hutangprk
							$hutangprk=0-$b['saldo'];
							$kas=$data['nominal']-$hutangprk;
							$detail[]=array(
								'ref_akun'  => $b['rek_parent'],
								'keterangan'  => $this->db->escape('Uang Muka Penjualan'),
								'debet' => $kas,
								'kredit'  => 0,
								'urutan'  => 1,
							);
							$detail[]=array(
								'ref_akun'  => '2001',
								'keterangan'  => $this->db->escape('Pembayaran Hutang PRK'),
								'debet' => $hutangprk,
								'kredit'  => 0,
								'urutan'  => 2,
							);
						}else{
							$hutangprk=$data['nominal'];
							$detail[]=array(
								'ref_akun'  => '2001',
								'keterangan'  => $this->db->escape('Pembayaran Hutang PRK'),
								'debet' => $hutangprk,
								'kredit'  => 0,
								'urutan'  => 2,
							);
						}
					}else{
						$detail[]=array(
							'ref_akun'  => $b['rek_parent'],
							'keterangan'  => $this->db->escape('Uang Muka Penjualan'),
							'debet' => $data['nominal'],
							'kredit'  => 0,
							'urutan'  => 1,
						);
					}
				}else{
					$detail[]=array(
						'ref_akun'  => $b['rek_parent'],
						'keterangan'  => $this->db->escape('Bank Uang Muka Penjualan'),
						'debet' => $data['nominal'],
						'kredit'  => 0,
						'urutan'  => 1,
					);
				}

				if($data['biaya_bank'] > 0){
					$detail[]=array(
						'ref_akun'  => '6265',
						'keterangan'  => $this->db->escape('Biaya Administrasi Bank'),
						'debet' => $data['biaya_bank'],
						'kredit'  => 0,
						'urutan'  => 2,
					);
				}
				if($data['biaya_lain'] > 0){
					$detail[]=array(
						'ref_akun'  => '6299',
						'keterangan'  => $this->db->escape('Biaya Lain-lain'),
						'debet' => $data['biaya_lain'],
						'kredit'  => 0,
						'urutan'  => 3,
					);
				}
				if($data['biayamarketplace'] > 0){
					$detail[]=array(
						'ref_akun'  => '6238',
						'keterangan'  => $this->db->escape('Biaya marketplace'),
						'debet' => $data['biayamarketplace'],
						'kredit'  => 0,
						'urutan'  => 4,
					);
				}

				$detail[]=array(
					'ref_akun'  =>'2401',
					'debet' => 0,
					'kredit'  => $data['nominal']+$data['biaya_bank']+$data['biaya_lain']-$data['pendapatan_lain']+$data['biayamarketplace'],
					'urutan'  =>5,
					'keterangan'  => 'Hutang Uang Muka Penjualan '.$cust['name']
				);

				$jurnal=array(
					'tanggal' => $data['date_trans'],
					'keterangan' => 'Deposit Customer',
					'ref' => $id,
					'linkterkait' =>'UMP-'.date('Y-m').'-'.$id,
					'type' => 1,
					'details' => $detail,
					'idref'	=> isset($data['ref'])?$data['ref']:0,
					'urlref'	=> isset($data['urlref'])?$data['urlref']:'',
					'no_dokumen'	=> isset($data['no_dokumen'])?$data['no_dokumen']:'',
				);
				$jurnal_id=$this->model_keuangan_jurnal->addJurnalUmum($jurnal);
				$aruskas=array(
					'date_added'	=> date('Y-m-d H:i:s'),
					'date_trans'	=> $data['date_trans'],
					'bank_id' => $data['bank_id'],
					'saldomasuk'  => $data['nominal']+$data['biaya_bank'],
					'saldokeluar' => 0,
					'saldoawal' => $b['saldo'],
					'saldoakhir'  => $saldo+$data['biaya_bank'],
					'ref' => $id,
					'keterangan'  => 'Uang Muka Penjualan '.$cust['name'],
					'linkterkait' =>'UMP-'.date('Y-m').'-'.$id,
					'type'  => 4,
					'ref_akun'  => '2401',
					'idref'	=> isset($data['ref'])?$data['ref']:0,
					'urlref'	=> isset($data['urlref'])?$data['urlref']:'',
					'no_dokumen'	=> isset($data['no_dokumen'])?$data['no_dokumen']:'',
					'jurnal_id'	=> $jurnal_id

				);
				$this->model_keuangan_bank->addAruskas($aruskas);
				if($data['biaya_bank'] > 0){
					$aruskas=array(
						'date_added'	=> date('Y-m-d H:i:s'),
						'date_trans'	=> $data['date_trans'],
						'bank_id' => $data['bank_id'],
						'saldokeluar'  => $data['biaya_bank'],
						'saldomasuk' => 0,
						'saldoawal' => $b['saldo']+$data['nominal']+$data['biaya_bank'],
						'saldoakhir'  => $saldo,
						'ref' => $id,
						'keterangan'  => 'Biaya Administrasi Bank ',
						'type'  => 32,
						'ref_akun'  => '6265',
						'idref'	=> isset($data['ref'])?$data['ref']:0,
						'urlref'	=> isset($data['urlref'])?$data['urlref']:'',
						'no_dokumen'	=> isset($data['no_dokumen'])?$data['no_dokumen']:'',
						'jurnal_id'	=> $jurnal_id
					);
					$this->model_keuangan_bank->addAruskas($aruskas);
				}	
		}
	}
	public function cancelDeposit($data,$customer_id){
		if(!isset($data['biaya_bank'])){
			$data['biaya_bank']=0;
		}
		
		$this->updateDeposit($customer_id,$data['nominal']+$data['biaya_bank']+$data['biaya_lain']-$data['pendapatan_lain'],2);
		
		$cust=$this->getVendor(array('customer_id'	=> $customer_id));
			$hutang=array(
				'ref'=> isset($data['ref'])?$data['ref']:0,
				'date_trans'	=> $data['date_trans'],
				'saldomasuk'	=> 0,
				'saldokeluar'	=> $data['nominal']+$data['biaya_bank']+$data['biaya_lain']-$data['pendapatan_lain'],
				'keterangan'	=> $this->db->escape('Pembatalan '.$data['keterangan'].' untuk Customer '.$cust['name'].' oleh ' .$this->user->getUsername()),
				'hapus'	=> 0,
				'customer_id'=> $customer_id,
				'idref'	=> isset($data['ref'])?$data['ref']:0,
				'urlref'	=> isset($data['urlref'])?$data['urlref']:'',
				'no_dokumen'	=> isset($data['no_dokumen'])?$data['no_dokumen']:'',
			);
		
		$id=$this->addHistoryDeposit($hutang);

		$this->load->model('keuangan/bank');
		$this->load->model('keuangan/jurnal');
		
		if($data['bank_id']>0){
			$b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $data['bank_id']));
		}
		if($data['metode_pembayaran']==5){
			$detail[]=array(
				'ref_akun'  =>'2299',
				'kredit' =>$data['nominal']+$data['biaya_bank']-$data['pendapatan_lain'],
				'debet'  =>0,
				'urutan'  =>1,
				'keterangan'  => 'Pembatalan Uang Muka Penjualan '.$cust['name']
			);
			$this->db->query("UPDATE penerimaandana_hutanglain set status=1 WHERE id='".$data['id_hutanglain']."' ");
		}else{
			if($saldo < 0){
				if($b['hutangprk'] == 1){
					if($b['saldo'] > 0){
						$kas=$b['saldo'];
						$hutangprk=$data['nominal'] - $b['saldo'];

						$detail[]=array(
							'ref_akun'  =>$b['rek_parent'],
							'kredit' => $kas,
							'debet'  => 0,
							'urutan'  =>2,
							'keterangan'  => 'Kas/Bank'
						);

						$detail[]=array(
							'ref_akun'  =>'2001',
							'kredit' => $hutangprk,
							'debet'  => 0,
							'urutan'  =>3,
							'keterangan'  => 'Hutang PRK'
						);
					}else{
						$detail[]=array(
							'ref_akun'  =>'2001',
							'kredit' => $data['nominal'],
							'debet'  => 0,
							'urutan'  =>3,
							'keterangan'  => 'Hutang PRK'
						);
					}
				}else{
					$detail[]=array(
						'ref_akun'  =>$b['rek_parent'],
						'kredit' => $data['nominal'],
						'debet'  => 0,
						'urutan'  =>1,
						'keterangan'  => 'Kas/Bank'
					);
				}
			}else{
				if($data['bank_id']>0){
					$detail[]=array(
						'ref_akun'  =>$b['rek_parent'],
						'kredit' => $data['nominal'],
						'debet'  => 0,
						'urutan'  =>1,
						'keterangan'  => 'Kas/Bank'
					);
				}
			}
			if($data['biaya_bank'] > 0){
				$detail[]=array(
					'ref_akun'  =>'6265',
					'kredit' => $data['biaya_bank'],
					'debet'  => 0,
					'urutan'  =>3,
					'keterangan'  => 'Biaya Administrasi Bank'
				);
			}

			if($data['biaya_lain']>0){
				$detail[]=array(
					'ref_akun'  =>'6299',
					'kredit' =>$data['biaya_lain'],
					'debet'  =>0,
					'urutan'  =>3,
					'keterangan'  => 'Biaya Lain-lain'
				);
			}
		}

			$detail[]=array(
				'ref_akun'  =>'2401',
				'kredit' => 0,
				'debet'  => $data['nominal']+$data['biaya_bank']+$data['biaya_lain']-$data['pendapatan_lain'],
				'urutan'  =>4,
				'keterangan'  => 'Pembatalan Uang Muka Penjualan '.$cust['name']
			);

		$jurnal=array(
			'tanggal' => $data['date_trans'],
			'keterangan' => 'Pembatalan Deposit Customer',
			'ref' => $id,
			'type' => 1,
			'details' => $detail,
			'idref'	=> isset($data['ref'])?$data['ref']:0,
			'urlref'	=> isset($data['urlref'])?$data['urlref']:'',
			'no_dokumen'	=> isset($data['no_dokumen'])?$data['no_dokumen']:'',
		);
		$jurnal_id=$this->model_keuangan_jurnal->addJurnalUmum($jurnal);
		if($data['bank_id']>0){
			$b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $data['bank_id']));
			$saldo=$b['saldo'] - $data['nominal'];
			$this->model_keuangan_bank->editBank(array('saldo'  => $saldo),array('id'=> $data['bank_id']));
			$aruskas=array(
				'date_added'	=> date('Y-m-d H:i:s'),
				'date_trans'	=> $data['date_trans'],
				'bank_id' => $data['bank_id'],
				'saldomasuk'  => 0,
				'saldokeluar' => $data['nominal']+$data['biaya_bank'],
				'saldoawal' => $b['saldo'],
				'saldoakhir'  => $saldo-$data['biaya_bank'],
				'ref' => $id,
				'keterangan'  => 'Pembatalan Uang Muka Penjualan '.$cust['name'],
				'type'  => 4,
				'ref_akun'  => '2401',
				'idref'	=> isset($data['ref'])?$data['ref']:0,
				'urlref'	=> isset($data['urlref'])?$data['urlref']:'',
				'no_dokumen'	=> isset($data['no_dokumen'])?$data['no_dokumen']:'',
				'jurnal_id'	=> $jurnal_id
			);
			$this->model_keuangan_bank->addAruskas($aruskas);

			if($data['biaya_bank'] > 0){
				$aruskas=array(
					'date_added'	=> date('Y-m-d H:i:s'),
					'date_trans'	=> $data['date_trans'],
					'bank_id' => $data['bank_id'],
					'saldokeluar'  => 0,
					'saldomasuk' => $data['biaya_bank'],
					'saldoawal' => $saldo-$data['biaya_bank'],
					'saldoakhir'  => $saldo,
					'ref' => $id,
					'keterangan'  => 'Pembatalan Biaya Administrasi Bank '.$cust['name'],
					'type'  => 32,
					'ref_akun'  => '6265',
					'idref'	=> isset($data['ref'])?$data['ref']:0,
					'urlref'	=> isset($data['urlref'])?$data['urlref']:'',
					'no_dokumen'	=> isset($data['no_dokumen'])?$data['no_dokumen']:'',
					'jurnal_id'	=> $jurnal_id
				);
				$this->model_keuangan_bank->addAruskas($aruskas);
			}
		}
	}

	public function addPiutang($data){
		$hutang=array(
			'ref'=> $data['ref'],
			'tanggal'	=> $data['tanggal'],
			'total_penjualan'	=> $data['total_penjualan'],
			'total_piutang'	=> $data['total_piutang'],
			'jatuhtempo'	=> $data['jatuhtempo'],
			'hapus'	=> 0,
			'customer_id'=> $data['customer_id']
		);
		$this->db->insert('piutang',$hutang);
	}
	public function getPiutang($where){
		return $this->db->first('piutang',$where);
	}
	public function updateDetailPiutang($ref,$hutang,$jenis){
		$data=$this->getPiutang(array('ref'=>$ref));

		//update qty
		if($jenis == 1){
			$qtyf=$data['total_piutang'] + $hutang;
		}
		if($jenis == 2){
			$qtyf=$data['total_piutang'] - $hutang;
		}
		$this->updateHutang($data['customer_id'],$hutang,$jenis);

	}

	public function setPiutang($data,$customer_id){
		//$this->updateDetailPiutang();
	}

	public function addAddress($address,$customer_id){
	        $add=array(
						'customer_id'	=> $customer_id,
						'firstname'	=>$this->db->escape($address['firstname']),
						'lastname'	=>$this->db->escape($address['lastname']),
						'address_1'	=>$this->db->escape($address['address_1'])." ".$this->db->escape($address['address_2']),
						//'address_2'	=>$this->db->escape($address['address_2']),
						'address_2' => '.',
						'city_id'	=>(int)$address['city_id'],
						'postcode'	=>$this->db->escape($address['postcode']),
						'country_id'	=>(int)$address['country_id'],
						'zone_id'	=>(int)$address['zone_id'],
						'hapus'	=> 0
					);

					$this->db->insert('address',$add);
					$address_id = $this->db->getLastId();
					if (isset($address['default'])) {
	            $address_id = $this->db->getLastId();

	            $this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . $address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
	        }
					return $address_id;
	    }
			public function deleteAddress($address_id) {
				//$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "'");
				$this->db->query("UPDATE address SET hapus=1 WHERE address_id = '" . (int)$address_id . "'");
			}
			public function getAddress($address_id) {
				$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "'");

				if ($address_query->num_rows) {
					$country_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

					if ($country_query->num_rows) {
						$country = $country_query->row['name'];
						$iso_code_2 = $country_query->row['iso_code_2'];
						$iso_code_3 = $country_query->row['iso_code_3'];
						$address_format = $country_query->row['address_format'];
					} else {
						$country = '';
						$iso_code_2 = '';
						$iso_code_3 = '';
						$address_format = '';
					}

					$zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

					if ($zone_query->num_rows) {
						$zone = $zone_query->row['name'];
						$zone_code = $zone_query->row['code'];
					} else {
						$zone = '';
						$zone_code = '';
					}

		            // tokocepat
					$city_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "city WHERE city_id = '" . (int)$address_query->row['city_id'] . "'");

					if ($city_query->num_rows) {
						$city = $city_query->row['name'];
						$city_code = $city_query->row['code'];
					} else {
						$city = '';
						$city_code = '';
					}


					return array(
						'address_id'     => $address_query->row['address_id'],
						'customer_id'    => $address_query->row['customer_id'],
						'firstname'      => $address_query->row['firstname'],
						'lastname'       => $address_query->row['lastname'],
						'company'        => $address_query->row['company'],
						'company_id'     => $address_query->row['company_id'],
						'tax_id'         => $address_query->row['tax_id'],
						'address_1'      => $address_query->row['address_1'],
						'address_2'      => $address_query->row['address_2'],
						'postcode'       => $address_query->row['postcode'],
		                // tokocepat
						'city_id'        => $address_query->row['city_id'],
						'city'           => $city,
						'city_code'      => $city_code,
						'zone_id'        => $address_query->row['zone_id'],
						'zone'           => $zone,
						'zone_code'      => $zone_code,
						'country_id'     => $address_query->row['country_id'],
						'country'        => $country,
						'iso_code_2'     => $iso_code_2,
						'iso_code_3'     => $iso_code_3,
						'address_format' => $address_format
					);
				}
			}

			public function getAddresses($customer_id,$data) {
				$address_data = array();


		      $sql="SELECT address_id FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "' AND (hapus IS null OR hapus=0)";
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
					$address_info = $this->getAddress($result['address_id']);

					if ($address_info) {
						$address_data[$result['address_id']] = $address_info;
					}
				}

				return $address_data;
			}

			public function getAddresses2($customer_id,$data) {
				$address_data = array();


		      $sql="SELECT address_id FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "' AND (hapus IS null OR hapus=0)";
					if(isset($data['filter_name'])){
						$sql .= " AND (lower(firstname) LIKE '%".strtolower($data['filter_name'])."%' OR lower(address_1) LIKE '%".strtolower($data['filter_name'])."%')";
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
					$address_info = $this->getAddress($result['address_id']);

					if ($address_info) {
						$address_data[$result['address_id']] = $address_info;
					}
				}

				return $address_data;
			}

		    public function getTotalAddress($customer_id){

		        $sql="SELECT count(*) as total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "' AND (hapus IS null OR hapus=0)";
		        $query = $this->db->query($sql);

				return $query->row['total'];
		    }

				public function addContact($address,$customer_id){
				        $add=array(
									'customer_id'	=> $customer_id,
									'firstname'	=>$this->db->escape($address['firstname']),
									'lastname'	=>$this->db->escape($address['lastname']),
									'address_1'	=>$this->db->escape($address['address_1']),
									'address_2'	=>$this->db->escape($address['address_2']),
									'company'	=>$this->db->escape($address['company']),
									'city_id'	=>(int)$address['city_id'],
									'postcode'	=>$this->db->escape($address['postcode']),
									'country_id'	=>(int)$address['country_id'],
									'zone_id'	=>(int)$address['zone_id'],
									'hapus'	=> 0
								);

								$this->db->insert('customer_contact',$add);
								$address_id = $this->db->getLastId();

								return $address_id;
				    }
						public function deleteContact($address_id) {
							//$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "'");
							$this->db->query("UPDATE customer_contact SET hapus=1 WHERE address_id = '" . (int)$address_id . "'");
						}
						public function getContact($address_id) {
							$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_contact WHERE address_id = '" . (int)$address_id . "'");

							if ($address_query->num_rows) {
								$country_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

								if ($country_query->num_rows) {
									$country = $country_query->row['name'];
									$iso_code_2 = $country_query->row['iso_code_2'];
									$iso_code_3 = $country_query->row['iso_code_3'];
									$address_format = $country_query->row['address_format'];
								} else {
									$country = '';
									$iso_code_2 = '';
									$iso_code_3 = '';
									$address_format = '';
								}

								$zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

								if ($zone_query->num_rows) {
									$zone = $zone_query->row['name'];
									$zone_code = $zone_query->row['code'];
								} else {
									$zone = '';
									$zone_code = '';
								}

					            // tokocepat
								$city_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "city WHERE city_id = '" . (int)$address_query->row['city_id'] . "'");

								if ($city_query->num_rows) {
									$city = $city_query->row['name'];
									$city_code = $city_query->row['code'];
								} else {
									$city = '';
									$city_code = '';
								}


								return array(
									'address_id'     => $address_query->row['address_id'],
									'customer_id'    => $address_query->row['customer_id'],
									'firstname'      => $address_query->row['firstname'],
									'lastname'       => $address_query->row['lastname'],
									'company'        => $address_query->row['company'],
									'company_id'     => $address_query->row['company_id'],
									'tax_id'         => $address_query->row['tax_id'],
									'address_1'      => $address_query->row['address_1'],
									'address_2'      => $address_query->row['address_2'],
									'postcode'       => $address_query->row['postcode'],
					                // tokocepat
									'city_id'        => $address_query->row['city_id'],
									'city'           => $city,
									'city_code'      => $city_code,
									'zone_id'        => $address_query->row['zone_id'],
									'zone'           => $zone,
									'zone_code'      => $zone_code,
									'country_id'     => $address_query->row['country_id'],
									'country'        => $country,
									'iso_code_2'     => $iso_code_2,
									'iso_code_3'     => $iso_code_3,
									'address_format' => $address_format
								);
							}
						}

						public function getContacts($customer_id,$data) {
							$address_data = array();


					      $sql="SELECT address_id FROM " . DB_PREFIX . "customer_contact WHERE customer_id = '" . (int)$customer_id . "' AND (hapus IS null OR hapus=0)";
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
								$address_info = $this->getContact($result['address_id']);

								if ($address_info) {
									$address_data[$result['address_id']] = $address_info;
								}
							}

							return $address_data;
						}

				public function getContacts2($customer_id,$data) {
							$address_data = array();


					      $sql="SELECT address_id FROM " . DB_PREFIX . "customer_contact WHERE customer_id = '" . (int)$customer_id . "' AND (hapus IS null OR hapus=0)";
								if(isset($data['filter_name'])){
									$sql .= " AND firstname LIKE '%".$data['filter_name']."%'";
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
								$address_info = $this->getAddress($result['address_id']);

								if ($address_info) {
									$address_data[$result['address_id']] = $address_info;
								}
							}

							return $address_data;
				}

		    public function getTotalContact($customer_id){

		        $sql="SELECT count(*) as total FROM " . DB_PREFIX . "customer_contact WHERE customer_id = '" . (int)$customer_id . "' AND (hapus IS null OR hapus=0)";
		        $query = $this->db->query($sql);

				return $query->row['total'];
		    }

			public function getDocuments($customer_id) {
			  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_document WHERE customer_id = '" . (int)$customer_id . "'");

			  return $query->rows;
			}

			public function deleteDocuments($product_image_id){
				$this->db->query("DELETE FROM ".DB_PREFIX."customer_document WHERE product_image_id='".$product_image_id."' ");
			}

			public function addDocuments($customer_id,$data){
				$this->db->delete('customer_document',array('customer_id'=>$customer_id));
				foreach ($data as $product_image) {
						$im=array(
							'customer_id'	=> $customer_id,
							'name'	=> $this->db->escape($product_image['name']),
							'image'	=> $this->db->escape(html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8')),
							'sort_order'	=>0
						);
						$this->db->insert("customer_document",$im);

				}

			}

			public function getDeposits($customer_id,$data) {

					$sql="SELECT * FROM " . DB_PREFIX . "history_deposit WHERE customer_id = '".$customer_id."' AND (hapus IS null OR hapus=0) ORDER BY date_trans DESC,id DESC";
					if (isset($data['start']) || isset($data['limit'])) {
					if ($data['start'] < 0) {
						$data['start'] = 0;
					}

					if ($data['limit'] < 1) {
						$data['limit'] = 20;
					}

					$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
				}

					$query=$this->db->query($sql);

				return $query->rows;
			}
			public function getTotalDeposits($customer_id,$data) {

					$sql="SELECT COUNT(*) as total FROM " . DB_PREFIX . "history_deposit WHERE customer_id = '" . (int)$customer_id . "' AND (hapus IS null OR hapus=0) ";


					$query=$this->db->query($sql);

				return $query->row['total'];
			}

			public function getPiutangs($customer_id,$data) {

					$sql="SELECT * FROM " . DB_PREFIX . "piutang WHERE customer_id = '" . (int)$customer_id . "' AND (hapus IS null OR hapus=0) ORDER BY tanggal DESC";
					if (isset($data['start']) || isset($data['limit'])) {
					if ($data['start'] < 0) {
						$data['start'] = 0;
					}

					if ($data['limit'] < 1) {
						$data['limit'] = 20;
					}

					$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
				}

					$query=$this->db->query($sql);

				return $query->rows;
			}
			public function getTotalPiutangs($customer_id,$data) {

					$sql="SELECT COUNT(*) as total FROM " . DB_PREFIX . "piutang WHERE customer_id = '" . (int)$customer_id . "' AND (hapus IS null OR hapus=0) ";


					$query=$this->db->query($sql);

				return $query->row['total'];
			}

			public function addVisits($customer_id,$data){
				//$this->db->delete('customer_document',array('customer_id'=>$customer_id));
				$im=array(
					'customer_id'	=> $customer_id,
					'keterangan'	=> $this->db->escape($data['keterangan']),
					'image'	=> $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')),
					'date_added'	=> empty($data['tanggal'])?date('Y-m-d H:i:s'):$data['tanggal'].' '.$data['jam'],
					'date_modified'	=> date('Y-m-d H:i:s'),
					'sales'	=> $data['sales'],
					'hapus'	=> 0
				);
				$this->db->insert("customer_visit",$im);

			}
			public function getVisits($customer_id,$data) {

					$sql="SELECT * FROM " . DB_PREFIX . "customer_visit WHERE customer_id = '".$customer_id."' AND (hapus IS null OR hapus=0) ORDER BY date_added DESC";
					if (isset($data['start']) || isset($data['limit'])) {
					if ($data['start'] < 0) {
						$data['start'] = 0;
					}

					if ($data['limit'] < 1) {
						$data['limit'] = 20;
					}

					$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
				}

					$query=$this->db->query($sql);

				return $query->rows;
			}
			public function getTotalVisits($customer_id,$data) {

					$sql="SELECT COUNT(*) as total FROM " . DB_PREFIX . "customer_visit WHERE customer_id = '" . (int)$customer_id . "' AND (hapus IS null OR hapus=0) ";


					$query=$this->db->query($sql);

				return $query->row['total'];
			}

	public function getBukuPiutangs($customer_id,$data) {

		$sql="SELECT * FROM " . DB_PREFIX . "buku_piutang WHERE customer_id = '".$customer_id."' ORDER BY date_added DESC";
		if (isset($data['start']) || isset($data['limit'])) {
		if ($data['start'] < 0) {
			$data['start'] = 0;
		}

		if ($data['limit'] < 1) {
			$data['limit'] = 20;
		}

		$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
		}

		$query=$this->db->query($sql);

		return $query->rows;
	}
	public function getTotalBukuPiutangs($customer_id,$data) {

			$sql="SELECT COUNT(*) as total FROM " . DB_PREFIX . "buku_piutang WHERE customer_id = '" . (int)$customer_id . "' ";


			$query=$this->db->query($sql);

		return $query->row['total'];
	}
}
?>
