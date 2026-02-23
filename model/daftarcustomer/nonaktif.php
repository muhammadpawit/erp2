<?php
/**
 * 
 */
class ModelDaftarcustomerNonaktif extends Model {
	// baru 29 Juni 2020
	public function test(){
		$draw=$_REQUEST['draw'];
		$length=$_REQUEST['length'];
		$start=$_REQUEST['start'];
		$search=$_REQUEST['search']["value"];
		$output['data']=array();
		$output=array();
		$output['draw']=$draw;
		$pro=null;
		$cats=null;
		$settingsales= $this->get($this->user->getId());
		$daftarprov=$this->getsettingprov($this->user->getId());
		if(!empty($daftarprov)){
			foreach($daftarprov as $dp){
				$pro[]=$dp['provinsi'];
			}
		}
		$daftarcat=$this->getsettingcat($this->user->getId());
		if(!empty($daftarcat)){
			foreach($daftarcat as $dp){
				$cats[]=$dp['category'];
			}
		}
		$aprov=implode(",",$pro);
		$cg=implode(",",$cats);
		$sql="SELECT * FROM customer where hapus=0  and country IS NOT NULL and customer_group_id IS NOT NULL ";
		if(!empty($settingsales['setting'])){
			if($settingsales['setting']>0){
				$minimalhari = $settingsales['setting']['lamanonaktif'];
			}else{
				$minimalhari=60;
			}

			if(!empty($daftarprov)){
				$sql.=" AND country IN($aprov) ";
			}else{
				$sql.=" AND country IN(0) ";
			}

			if(!empty($daftarcat)){
				$sql.=" AND customer_group_id IN($cg) ";
			}else{
				$sql.=" AND customer_group_id IN(0) ";
			}

		}else{
			$minimalhari=60;
		}
		
		
		$sql.=" ORDER BY name ASC ";
		$d=$this->db->query($sql);
		$data=$d->rows;
		$nomor_urut=$start+1;
		$total=count($data);
		$output['recordsTotal']=$output['recordsFiltered']=$total;
		$status=null;
		
		foreach ($data as $c) {
			$provinsi=$this->pro("country","country_id",$c['country']>0?$c['country']:0);
			$kotakab=$this->pro("zone","zone_id",$c['zone']>0?$c['zone']:0);
			$category=$this->category("customer_group","customer_group_id",$c['customer_group_id']);
			$invoice=$this->getinv($c['customer_id']);
			$tanggal = date('Y-m-d',strtotime($invoice));
            $tanggal_lahir  = strtotime(date('Y-m-d',strtotime($invoice)));
            $sekarang    = time(); // Waktu sekarang
            $diff   = $sekarang - $tanggal_lahir;
            $lama=floor($diff / (60 * 60 * 24)) ;
            $hari=floor($diff / (60 * 60 * 24)) ;
            if($lama<60){
                $status="<span class='badge bg-blue'>customer aktif</span>";
            }else if($lama>=60 && $lama<18178){
                $status="<span class='badge bg-red'>customer non aktif</span>";
            }else if($lama==18178){
            	$status="<span class='badge bg-yellow'>Belum Customer</span>";
            }else{
                $status="";
			}
			if($lama>=$minimalhari && $lama<18178){
				$url="&filter_customer_id=".$c['customer_id']."&filter_date_start=".$tanggal."&filter_date_end=".$tanggal."&filter_status=3";
				$link=$this->url->link('laporan/penjualandetail', 'token=' . $this->session->data['token'].$url, 'SSL');;
				$output['data'][]=array($nomor_urut,$category,$c['name'],$c['telephone'],$c['alamat'],$provinsi,$kotakab,date('d/m/Y',strtotime($tanggal)),$lama,'<a href="'.$link.'" target="_blank" class="badge bg-blue">lihat penjualan detail</a>');
				$nomor_urut++;
			}
		}

		return $output;
	}

	public function pro($table,$column,$id){
		$d=$this->db->query("SELECT name from $table WHERE $column='$id' ");
		return $d->row['name'];
	}
	public function category($table,$column,$id){
		$d=$this->db->query("SELECT name from $table WHERE $column='$id' ");
		return $d->row['name'];
	}
	public function getinv($customer_id){
		$date_added=array();
		$d = $this->db->query("SELECT date_added FROM invoice WHERE status<> 4 AND customer_id='$customer_id' ORDER BY date_added DESC LIMIT 1");
		if(!empty($d->row)){
			return $d->row['date_added'];
		}else{
			return $date_added;
		}
	}
	// end baru
	public function getnamacat($id){
		$d=$this->db->query("SELECT name from customer_group WHERE customer_group_id='$id' ");
		return $d->row['name'];
	}
	public function getsettingcat($user_id){
		$sp = $this->db->query("SELECT sup.category FROM setting_user_sales_category sup JOIN setting_user_sales sus on(sus.id=sup.setting_user_id)  WHERE sus.user_id='$user_id' ");
		$d=$sp->rows;
		foreach($d as $p){
			$hasil[]=array(
				'category'=>$p['category']
			);
		}
		return $hasil;
	}

	public function getnamaprov($id){
		$d=$this->db->query("SELECT name from country WHERE country_id='$id' ");
		return $d->row['name'];
	}
	public function getsettingprov($user_id){
		$sp = $this->db->query("SELECT sup.provinsi FROM setting_user_sales_provinsi sup JOIN setting_user_sales sus on(sus.id=sup.setting_user_id)  WHERE sus.user_id='$user_id' ");
		$d=$sp->rows;
		foreach($d as $p){
			$hasil[]=array(
				'provinsi'=>$p['provinsi']
			);
		}
		return $hasil;
	}

	public function getlamahari($user_id){
		//$sp=$this->db->query("SELECT provinsi FROM setting_user_sales_provinsi JOIN setting_user_sales ON(setting_user_sales.id=setting_user_sales_provinsi.setting_user_id) WHERE setting_user_sales.user_id='".$user_id."' ");
		$sp = $this->db->query("SELECT * FROM setting_user_sales WHERE user_id='$user_id' ");
		return $sp->row['lamanonaktif'];
	}
	public function simpaneditsetting($data){
		$this->db->query("DELETE FROM setting_user_sales WHERE id='".$data['idsetting']."' ");
		$this->db->query("DELETE FROM setting_user_sales_provinsi WHERE setting_user_id='".$data['idsetting']."' ");
		$this->db->query("DELETE FROM setting_user_sales_category WHERE setting_user_id='".$data['idsetting']."' ");
		$insert=array(
			'user_id'=>$data['user_id'],
			'nama_sales'=>$data['namasales'],
			'lamanonaktif'=>empty($data['lamahari'])?0:$data['lamahari'],
			'hapus'=>0,
			'date'=>date('Y-m-d H:i:s'),
		);
		$this->db->insert('setting_user_sales',$insert);
		$id=$this->db->getLastId();

		if(isset($data['filter_provinsi'])){
			foreach($data['filter_provinsi'] as $prov=>$value){
				$insp=array(
					'setting_user_id'=>$id,
					'provinsi'=>$value
				);
				$this->db->insert('setting_user_sales_provinsi',$insp);
			}
		}

		if(isset($data['filter_customer_group'])){
			foreach($data['filter_customer_group'] as $cat=>$values){
				$inscat=array(
					'setting_user_id'=>$id,
					'category'=>$values
				);
				$this->db->insert('setting_user_sales_category',$inscat);
			}
		}
	}

	public function simpansetting($data){
		$insert=array(
			'user_id'=>$data['user_id'],
			'nama_sales'=>$data['namasales'],
			'lamanonaktif'=>empty($data['lamahari'])?0:$data['lamahari'],
			'hapus'=>0,
			'date'=>date('Y-m-d H:i:s'),
		);
		$this->db->insert('setting_user_sales',$insert);
		$id=$this->db->getLastId();

		if(isset($data['filter_provinsi'])){
			foreach($data['filter_provinsi'] as $prov=>$value){
				$insp=array(
					'setting_user_id'=>$id,
					'provinsi'=>$value
				);
				$this->db->insert('setting_user_sales_provinsi',$insp);
			}
		}

		if(isset($data['filter_customer_group'])){
			foreach($data['filter_customer_group'] as $cat=>$values){
				$inscat=array(
					'setting_user_id'=>$id,
					'category'=>$values
				);
				$this->db->insert('setting_user_sales_category',$inscat);
			}
		}
	}

	public function get($user_id){
		$hasil=array();
		$dsp=array();
		$dsc=array();
		$cek=$this->db->query("SELECT * FROM setting_user_sales WHERE user_id='$user_id' ");
		$row=$cek->row;
		if(!empty($row)){
			$sp=$this->db->query("SELECT provinsi FROM setting_user_sales_provinsi WHERE setting_user_id='".$row['id']."' ");
			$sc=$this->db->query("SELECT category FROM setting_user_sales_category WHERE setting_user_id='".$row['id']."' ");
			$dsp=$sp->rows;
			foreach($dsp as $h){
				$pro[]=$h['provinsi'];
			}
			$dsc=$sc->rows;
			foreach($dsc as $c){
				$cat[]=$c['category'];
			}
			$hasil=array(
				'setting'=>$row,
				'provinsi'=>$pro,
				'category'=>$cat,
			);
		}else{
			$hasil=array(
				'setting'=>null,
				'provinsi'=>null,
				'category'=>null,
			);
		}
		return $hasil;
	}
	public function getVendors($where,$order,$limit,$offset){
		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		// if($custdata != 1){
		// 	$where['sales']=$this->user->getId();
		// }
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
		//$order=array('name'=> 'ASC');
		return $this->db->alljoins('customer',$column,$join,$leftjoin,$where,$order,$limit,$offset);
	}

	public function totalVendors($where){
		$this->load->model('user/user');
		$custdata=$this->model_user_user->getAksesData($this->user->getId(),1);

		// if($custdata != 1){
		// 	$where['sales']=$this->user->getId();
		// }

		$join=array();
		$leftjoin=array();
		$leftjoin[]=array(
			'tablename'=>'users',
			'firsttable'	=> 'customer.sales',
			'secondtable'	=> 'users.user_id'
		);

		return $this->db->countAll('customer',$where,$join,$leftjoin);
	}

	public function getsales(){
		$sql="SELECT u.user_id,u.firstname,sus.lamanonaktif,ug.name FROM users u left join setting_user_sales sus ON(sus.user_id=u.user_id) LEFT JOIN user_group ug ON(ug.user_group_id=u.user_group_id) WHERE u.resign='0' AND u.hapus=0 AND u.status=1 ";
		$sql.=" AND u.user_group_id IN(21,25)";
		$sql.=" ORDER BY u.firstname ASC";
		$d=$this->db->query($sql);
		return $d->rows;
	}
}