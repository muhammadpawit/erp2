<?php
class ModelKepegawaianPremijual extends Model {
	

	public function akumulasiSopirs($user_id,$date_start,$date_end) {
		$sql="SELECT kodepremi,COALESCE(SUM(quantity),0) as jumlah FROM penjualan_product pp JOIN penjualan p ON(pp.sales_order_id=p.id) WHERE sopir='".$user_id."' AND p.status <> 3 AND DATE(date_added) <= '".$date_end."' AND DATE(date_added) >= '".$date_start."'  AND kodepremi > 0 GROUP BY kodepremi";
		$result=$this->db->query($sql);
		return $result->rows;
	}

	public function akumulasiSopir($user_id,$date_start,$date_end) {
		$sql="SELECT kodepremi,COALESCE(SUM(quantity),0) as jumlah FROM penjualan_product pp JOIN penjualan p ON(pp.sales_order_id=p.id) WHERE sopir='".$user_id."' AND p.status <> 3 AND DATE(date_added) <= '".$date_end."' AND DATE(date_added) >= '".$date_start."'  AND kodepremi > 0 GROUP BY kodepremi";
		$result=$this->db->query($sql);

		return $result->rows;
	}

	public function akumulasiKernet($user_id,$date_start,$date_end) {
		$sql="SELECT COALESCE(SUM(quantity),0) as jumlah FROM penjualan_product pp JOIN penjualan p ON(pp.sales_order_id=p.id) JOIN penjualan_kernet pk  ON(pk.tttk_id=p.id) WHERE pegawai_id='".$user_id."' AND status <> 3 AND DATE(date_added) <= '".$date_end."' AND DATE(date_added) >= '".$date_start."' AND kodepremi > 0 GROUP BY kodepremi";
		$result=$this->db->query($sql);

		return $result->rows;
	}

	public function hitungpremi($user_id,$date_start,$date_end) {
		$sql="SELECT * FROM penjualan WHERE sopir='".$user_id."' AND status <> 3 AND DATE(date_added) <= '".$date_end."' AND DATE(date_added) >= '".$date_start."'";
		$result=$this->db->query($sql);

		$premi=$this->db->all('kodepremi',array());


		$akumulasi=array();
		$total=0;
		foreach($result->rows as $r){
			//$akumulasisj=array();
			//hitung kernet
			$ker="SELECT COUNT(*) as total FROM penjualan_kernet WHERE tttk_id='".$r['id']."'";
			$kernet=$this->db->query($ker);
			//$jumlahkernet=($kernet->row['total']);

			foreach($premi as $p){
				$totalsopir=0;
				$premijual=0;
				$jumlah=$this->db->query("SELECT COALESCE(SUM(pp.quantity),0) as total FROM penjualan_product pp JOIN product_gudang pg ON(pp.product_id=pg.product_id) WHERE sales_order_id='".$r['id']."' AND pg.premijual='".$p['id']."' AND pg.gudang_id='".$r['gudang_id']."'");
				$totalsopir=$jumlah->row['total'];
				if($kernet->row['total'] > 0){
					if($kernet->row['total']==1 ){
						$persen=0.6;
					}
					if($kernet->row['total']==2 ){
						$persen=0.4;
					}
				}
				else{
					$persen=1;
				}

				if($totalsopir > 0 & $totalsopir <= 500){
					$premijual=$persen*$p['kelompok']*$totalsopir;
				}
				if($totalsopir > 500 & $totalsopir <= 1000){
					$premijual=$persen*$p['kelompok2']*$totalsopir;
				}
				if($totalsopir > 1000 & $totalsopir <= 1500){
					$premijual=$persen*$p['kelompok3']*$totalsopir;
				}
				if($totalsopir > 1500){
					$premijual=$persen*$p['kelompok4']*$totalsopir;
				}
				//$akumulasi[$p['id']]['premi']+=$premijual;
				$total+=$premijual;


				if(isset($akumulasi[$p['id']])){
					$a=$akumulasi[$p['id']];
					$akumulasi[$p['id']]=array(
						'kodepremi'	=> $p['id'],
						'kelompok'	=> $p['kelompok'],
						'kelompok2'	=> $p['kelompok2'],
						'kelompok3'	=> $p['kelompok3'],
						'kelompok4'	=> $p['kelompok4'],
						'total'	=> $a['total'] + $jumlah->row['total'],
						'totalkernet'	=> 0,
						'premisopir'	=> $a['premisopir'] + $premijual,
						'premikernet'	=> $a['premikernet']
					);
				}else{
					$akumulasi[$p['id']]=array(
						'kodepremi'	=> $p['id'],
						'kelompok'	=> $p['kelompok'],
						'kelompok2'	=> $p['kelompok2'],
						'kelompok3'	=> $p['kelompok3'],
						'kelompok4'	=> $p['kelompok4'],
						'total'	=> $jumlah->row['total'],
						'totalkernet'	=> 0,
						'premisopir'	=> $premijual,
						'premikernet'	=>0
					);
				}
			}



		}
		$sql2="SELECT p.* FROM penjualan p JOIN penjualan_kernet pk ON(pk.tttk_id=p.id) WHERE pegawai_id='".$user_id."' AND status <> 3 AND DATE(date_added) <= '".$date_end."' AND DATE(date_added) >= '".$date_start."'";
		$result2=$this->db->query($sql2);
		foreach($result2->rows as $r){
			//$akumulasisj=array();
			//hitung kernet
			$ker="SELECT COALESCE(COUNT(*),0) as total FROM penjualan_kernet WHERE tttk_id=".$r['id'];
			$kernet=$this->db->query($ker);
			//$jumlahkernet=($kernet->row['total']);

			foreach($premi as $p){
				$totalsopir=0;
				$premijual=0;
				$jumlah=$this->db->query("SELECT COALESCE(SUM(pp.quantity),0) as total FROM penjualan_product pp JOIN product_gudang pg ON(pp.product_id=pg.product_id) WHERE sales_order_id='".$r['id']."' AND pg.premijual='".$p['id']."' AND pg.gudang_id='".$r['gudang_id']."'");
				$totalsopir=$jumlah->row['total'];

				if($kernet->row['total'] > 0){
					if($kernet->row['total']== 1 ){
						$persen=0.4;
					}
					if($kernet->row['total']== 2 ){
						$persen=0.3;
					}
				}
				else{
					$persen=0;
				}
				//$persen=0.4;

				if($totalsopir > 0 & $totalsopir <= 500){
					$premijual=$persen*$p['kelompok']*$totalsopir;
				}
				if($totalsopir > 500 & $totalsopir <= 1000){
					$premijual=$persen*$p['kelompok2']*$totalsopir;
				}
				if($totalsopir > 1000 & $totalsopir <= 1500){
					$premijual=$persen*$p['kelompok3']*$totalsopir;
				}
				if($totalsopir > 1500){
					$premijual=$persen*$p['kelompok4']*$totalsopir;
				}
				//$akumulasi[$p['id']]['premi']+=$premijual;
				$total+=$premijual;


				if(isset($akumulasi[$p['id']])){
					$a=$akumulasi[$p['id']];
					$akumulasi[$p['id']]=array(
						'kodepremi'	=> $p['id'],
						'kelompok'	=> $p['kelompok'],
						'kelompok2'	=> $p['kelompok2'],
						'kelompok3'	=> $p['kelompok3'],
						'kelompok4'	=> $p['kelompok4'],
						'total'	=> $a['total'],
						'totalkernet'	=> $a['totalkernet']+$totalsopir,
						'premikernet'	=> $a['premikernet'] + $premijual,
						'premisopir'	=> $a['premisopir']
					);
				}else{
					$akumulasi[$p['id']]=array(
						'kodepremi'	=> $p['id'],
						'kelompok'	=> $p['kelompok'],
						'kelompok2'	=> $p['kelompok2'],
						'kelompok3'	=> $p['kelompok3'],
						'kelompok4'	=> $p['kelompok4'],
						'total'	=> 0,
						'totalkernet'	=> $totalsopir,
						'premikernet'	=> $premijual,
						'premisopir'	=> 0
					);
				}
			}



		}

		/*$sql="SELECT * FROM penjualan p JOIN penjualan_kernet pk  ON(pk.tttk_id=p.id) WHERE pegawai_id='".$user_id."' AND status <> 3 AND DATE(date_added) <= '".$date_end."' AND DATE(date_added) >= '".$date_start."'";
		$result=$this->db->query($sql);
		foreach($result->rows as $r){
			//$akumulasisj=array();
			//hitung kernet
			$ker="SELECT COUNT(*) as total FROM penjualan_kernet WHERE tttk_id='".$r['id']."'";
			$kernet=$this->db->query($ker);
			foreach($premi as $p){
				$jumlah=$this->db->query("SELECT COALESCE(SUM(pp.quantity),0) as total FROM penjualan_product pp JOIN product_gudang pg ON(pp.product_id=pg.product_id) WHERE sales_order_id='".$r['id']."' AND pg.premijual='".$p['id']."' AND pg.gudang_id='".$r['gudang_id']."'");
				if(isset($akumulasisj[$p['id']])){
					$a=$akumulasisj[$p['id']];
					$akumulasisj[$p['id']]=array(
						'kodepremi'	=> $p['id'],
						'kelompok'	=> $p['kelompok'],
						'kelompok2'	=> $p['kelompok2'],
						'kelompok3'	=> $p['kelompok3'],
						'kelompok4'	=> $p['kelompok4'],
						'totalkernet'	=> $a['totalkernet'] + $jumlah->row['total'],
						'total'	=> $totalsopir
					);
				}else{
					$akumulasisj[$p['id']]=array(
						'kodepremi'	=> $p['id'],
						'kelompok'	=> $p['kelompok'],
						'kelompok2'	=> $p['kelompok2'],
						'kelompok3'	=> $p['kelompok3'],
						'kelompok4'	=> $p['kelompok4'],
						'totalkernet'	=> $jumlah->row['total'],
						'total'	=> 0
					);
				}

				if(isset($akumulasi[$p['id']])){
					$a=$akumulasi[$p['id']];
					$akumulasi[$p['id']]=array(
						'kodepremi'	=> $p['id'],
						'kelompok'	=> $p['kelompok'],
						'kelompok2'	=> $p['kelompok2'],
						'kelompok3'	=> $p['kelompok3'],
						'kelompok4'	=> $p['kelompok4'],
						'totalkernet'	=> $a['totalkernet'] + $jumlah->row['total'],
						'total'	=> $a['total'],
						'premi'	=> $a['premi']
					);
				}else{
					$akumulasi[$p['id']]=array(
						'kodepremi'	=> $p['id'],
						'kelompok'	=> $p['kelompok'],
						'kelompok2'	=> $p['kelompok2'],
						'kelompok3'	=> $p['kelompok3'],
						'kelompok4'	=> $p['kelompok4'],
						'totalkernet'	=> $jumlah->row['total'],
						'total'	=> 0,
						'premi'	=> 0
					);
				}
			}
			foreach($premi as $p){
				$premijual=0;
				if(isset($akumulasisj[$p['id']])){
					if(!empty($p['id'])){
						$a=$akumulasisj[$p['id']];
						if($kernet->row['total'] > 0){
							if($kernet->row['total']==1 ){
								$persen=0.4;
							}
							if($kernet->row['total']==2 ){
								$persen=0.3;
							}
						}
						else{
							$persen=0;
						}

						if($a['totalkernet'] > 0 & $a['totalkernet'] <= 500){
							$premijual=$persen*$a['kelompok']*$a['totalkernet'];
						}
						if($a['totalkernet'] > 500 & $a['totalkernet'] <= 1000){
							$premijual=$persen*$a['kelompok2']*$a['totalkernet'];
						}
						if($a['totalkernet'] > 1000 & $a['totalkernet'] <= 1500){
							$premijual=$persen*$a['kelompok3']*$a['totalkernet'];
						}
						if($a['totalkernet'] > 1500){
							$premijual=$persen*$a['kelompok4']*$a['totalkernet'];
						}
						$akumulasi[$p['id']]['premi']+=$premijual;
						$total+=$premijual;
					}
				}

			}

		}


*/


		$hasil=array(
			'total'	=> $total,
			'akumulasi'=> $akumulasi
		);
		return $hasil;
	}

	public function details($user_id,$date_start,$date_end) {
		$hasil=[];
		$supir=[];
		$kenek=[];
		$sql="SELECT DISTINCT spr.nomor,sp.nama, sp.qty,sp.kodepremi FROM sj_supir spr JOIN sj_product sp ON(sp.nomor=spr.nomor) WHERE spr.supir='$user_id' ";
		$sql.=" AND DATE(spr.tanggal) BETWEEN '$date_start' AND '$date_end' ";
		$result=$this->db->query($sql);
		foreach($result->rows as $spr){
			$supir[]=array(
				'nama'=>$spr['nama'],
				'qty'=>$spr['qty'],
				'kodepremi'=>$spr['kodepremi'],
			);
		}

		$sql2="SELECT DISTINCT spr.nomor,sp.nama, sp.qty,sp.kodepremi FROM sj_supir spr JOIN sj_product sp ON(sp.nomor=spr.nomor) JOIN sj_kernet sk ON(sk.nomor=spr.nomor) WHERE sk.kernet='$user_id' ";
		$sql2.=" AND DATE(spr.tanggal) BETWEEN '$date_start' AND '$date_end' ";
		$results=$this->db->query($sql2);
		foreach($results->rows as $knk){
			$kenek[]=array(
				'nama'=>$knk['nama'],
				'qty'=>$knk['qty'],
				'kodepremi'=>$knk['kodepremi'],
			);
		}

		$hasil=array_merge($supir,$kenek);
		return $hasil;
		//return $sql;
	}

	public function hitungpremi_new($user_id,$date_start,$date_end) {
		$sql="SELECT DISTINCT nomor FROM sj_supir WHERE supir='".$user_id."' AND status <> 3 AND DATE(tanggal) <= '".$date_end."' AND DATE(tanggal) >= '".$date_start."' ORDER BY nomor asc";
		//return $sql;exit;
		$result=$this->db->query($sql);
		$premi=$this->db->all('kodepremi',array('hapus'=>0));
		$akumulasi=array();
		$total=0;
		$tsopir=array();
		$sop=1;
		foreach($result->rows as $r){
			//hitung kernet
			//$he="SELECT COUNT(DISTINCT kernet) as total FROM sj_kernet WHERE kernet='".$user_id."' AND nomor='".$r['nomor']."'";
			$ker="SELECT COALESCE(COUNT(DISTINCT kernet),0) as total FROM sj_kernet WHERE nomor='".$r['nomor']."'";
			$kernet=$this->db->query($ker);
			foreach($premi as $p){
				$totalsopir=0;
				$premijual=0;
				$qk="SELECT COALESCE(SUM(pp.qty),0) as total FROM sj_product pp WHERE pp.nomor='".$r['nomor']."' AND pp.kodepremi='".$p['id']."'";
				//$jumlah=$this->db->query("SELECT COALESCE(SUM(pp.qty),0) as total FROM sj_product pp WHERE pp.nomor='".$r['nomor']."' AND pp.kodepremi='".$p['id']."'");
				$jumlah=$this->db->query("SELECT COALESCE(SUM(pp.qty),0) as total FROM sj_product pp WHERE pp.nomor='".$r['nomor']."' AND pp.kodepremi='".$p['id']."'");
				$sop=$jumlah->row['total'];
				$totalsopir=$jumlah->row['total'];
				//if($p['id']=='505'){
					$supire[]=array(
						'totalsopir'=>$jumlah->row['total'],
						'akumulasi'=>$akumulasi[505],
					);
				//}
				if($kernet->row['total'] > 0){
					if($kernet->row['total']==1 ){
						$persen=0.6;
					}
					if($kernet->row['total']==2 ){
						$persen=0.4;
					}
				}
				else{
					$persen=1;
				}

				if($totalsopir > 0 & $totalsopir <= 500){
					$premijual=$persen*$p['kelompok']*$totalsopir;
				}
				if($totalsopir > 500 & $totalsopir <= 1000){
					$premijual=$persen*$p['kelompok2']*$totalsopir;
				}
				if($totalsopir > 1000 & $totalsopir <= 1500){
					$premijual=$persen*$p['kelompok3']*$totalsopir;
				}
				if($totalsopir > 1500){
					$premijual=$persen*$p['kelompok4']*$totalsopir;
				}
				//$akumulasi[$p['id']]['premi']+=$premijual;
				$total+=$premijual;

				if(isset($akumulasi[$p['id']])){
					$a=$akumulasi[$p['id']];
					$akumulasi[$p['id']]=array(
						'kodepremi'	=> $p['id'],
						'kelompok'	=> $p['kelompok'],
						'kelompok2'	=> $p['kelompok2'],
						'kelompok3'	=> $p['kelompok3'],
						'kelompok4'	=> $p['kelompok4'],
						'total'	=> $a['total'] + $jumlah->row['total'],
						'totalkernet'	=> 0,
						'premisopir'	=> $a['premisopir'] + $premijual,
						'premikernet'	=> $a['premikernet'],
					);
				}else{
					$akumulasi[$p['id']]=array(
						'kodepremi'	=> $p['id'],
						'kelompok'	=> $p['kelompok'],
						'kelompok2'	=> $p['kelompok2'],
						'kelompok3'	=> $p['kelompok3'],
						'kelompok4'	=> $p['kelompok4'],
						'total'	=> $jumlah->row['total'],
						'totalkernet'	=> 0,
						'premisopir'	=> $premijual,
						'premikernet'	=>0,
					);
				}
			}
		}
		//echo "<pre>";print_r($supire);exit;
		
		$sql2="SELECT DISTINCT p.nomor FROM sj_supir p JOIN sj_kernet pk ON(pk.nomor=p.nomor) WHERE pk.kernet='".$user_id."' AND status <> 3 AND DATE(tanggal) <= '".$date_end."' AND DATE(tanggal) >= '".$date_start."'";
		$result2=$this->db->query($sql2);
		foreach($result2->rows as $r){
			//hitung kernet
			$ker="SELECT COALESCE(COUNT(DISTINCT kernet),0) as total FROM sj_kernet WHERE nomor='".$r['nomor']."' AND kernet='".$user_id."' ";
			$kernet=$this->db->query($ker);

			foreach($premi as $p){
				$totalsopir=0;
				$premijual=0;
				$jumlah=$this->db->query("SELECT COALESCE(SUM(pp.qty),0) as total FROM sj_product pp WHERE nomor='".$r['nomor']."' AND pp.kodepremi='".$p['id']."'");
				$totalsopir=$jumlah->row['total'];

				if($kernet->row['total'] > 0){
					if($kernet->row['total']== 1 ){
						$persen=0.4;
					}
					if($kernet->row['total']== 2 ){
						$persen=0.3;
					}
				}
				else{
					$persen=0;
				}

				if($totalsopir > 0 & $totalsopir <= 500){
					$premijual=$persen*$p['kelompok']*$totalsopir;
				}
				if($totalsopir > 500 & $totalsopir <= 1000){
					$premijual=$persen*$p['kelompok2']*$totalsopir;
				}
				if($totalsopir > 1000 & $totalsopir <= 1500){
					$premijual=$persen*$p['kelompok3']*$totalsopir;
				}
				if($totalsopir > 1500){
					$premijual=$persen*$p['kelompok4']*$totalsopir;
				}
				$total+=$premijual;
				if(isset($akumulasi[$p['id']])){
					$a=$akumulasi[$p['id']];
					$akumulasi[$p['id']]=array(
						'kodepremi'	=> $p['id'],
						'kelompok'	=> $p['kelompok'],
						'kelompok2'	=> $p['kelompok2'],
						'kelompok3'	=> $p['kelompok3'],
						'kelompok4'	=> $p['kelompok4'],
						'total'	=> $a['total'],
						'totalkernet'	=> $a['totalkernet']+$totalsopir,
						'premikernet'	=> $a['premikernet'] + $premijual,
						'premisopir'	=> $a['premisopir']
					);
				}else{
					$akumulasi[$p['id']]=array(
						'kodepremi'	=> $p['id'],
						'kelompok'	=> $p['kelompok'],
						'kelompok2'	=> $p['kelompok2'],
						'kelompok3'	=> $p['kelompok3'],
						'kelompok4'	=> $p['kelompok4'],
						'total'	=> 0,
						'totalkernet'	=> $totalsopir,
						'premikernet'	=> $premijual,
						'premisopir'	=> 0
					);
				}
			}
		}

		$hasil=array(
			'total'	=> $total,
			'akumulasi'=> $akumulasi
		);
		return $hasil;
	}

	public function hitungpremi_breakdwon($user_id,$date_start,$date_end) {
		$sql="SELECT DISTINCT nomor FROM sj_supir WHERE supir='".$user_id."' AND status <> 3 AND DATE(tanggal) <= '".$date_end."' AND DATE(tanggal) >= '".$date_start."' ORDER BY nomor asc";
		$result=$this->db->query($sql);
		$premi=$this->db->all('kodepremi',array('hapus'=>0));
		$akumulasi=array();
		$total=0;
		$tsopir=array();
		$sop=1;
		$spr=[];
		foreach($result->rows as $r){
			//hitung kernet
			$ker="SELECT COALESCE(COUNT(DISTINCT kernet),0) as total FROM sj_kernet WHERE nomor='".$r['nomor']."'";
			$kernet=$this->db->query($ker);
			foreach($premi as $p){
				$totalsopir=0;
				$premijual=0;
				$qk[$p['id']]=$this->db->query("SELECT COALESCE(SUM(pp.qty),0) as total FROM sj_product pp WHERE pp.nomor='".$r['nomor']."' AND pp.kodepremi='".$p['id']."'");
				$spr[$p['id']]+=$qk[$p['id']]->row['total'];
				$jumlah=$this->db->query("SELECT COALESCE(SUM(pp.qty),0) as total FROM sj_product pp WHERE pp.nomor='".$r['nomor']."' AND pp.kodepremi='".$p['id']."'");
				$sop=$jumlah->row['total'];
				$totalsopir=$jumlah->row['total'];
				//if($p['id']=='505'){
				//}
				if($kernet->row['total'] > 0){
					if($kernet->row['total']==1 ){
						$persen=0.6;
					}
					if($kernet->row['total']==2 ){
						$persen=0.4;
					}
				}else{
					$persen=1;
				}

				if($totalsopir > 0 & $totalsopir <= 500){
					$premijual=$persen*$p['kelompok']*$totalsopir;
				}
				if($totalsopir > 500 & $totalsopir <= 1000){
					$premijual=$persen*$p['kelompok2']*$totalsopir;
				}
				if($totalsopir > 1000 & $totalsopir <= 1500){
					$premijual=$persen*$p['kelompok3']*$totalsopir;
				}
				if($totalsopir > 1500){
					$premijual=$persen*$p['kelompok4']*$totalsopir;
				}
				
				$total+=$premijual;

				if(isset($akumulasi[$p['id']])){
					$a=$akumulasi[$p['id']];
					$akumulasi[$p['id']]=array(
						'kodepremi'	=> $p['id'],
						'kelompok'	=> $p['kelompok'],
						'kelompok2'	=> $p['kelompok2'],
						'kelompok3'	=> $p['kelompok3'],
						'kelompok4'	=> $p['kelompok4'],
						'total'	=> $a['total'] + $jumlah->row['total'],
						'totalkernet'	=> 0,
						'premisopir'	=> $a['premisopir'] + $premijual,
						'premikernet'	=> $a['premikernet'],
					);
				}else{
					$akumulasi[$p['id']]=array(
						'kodepremi'	=> $p['id'],
						'kelompok'	=> $p['kelompok'],
						'kelompok2'	=> $p['kelompok2'],
						'kelompok3'	=> $p['kelompok3'],
						'kelompok4'	=> $p['kelompok4'],
						'total'	=> $jumlah->row['total'],
						'totalkernet'	=> 0,
						'premisopir'	=> $premijual,
						'premikernet'	=>0,
					);
				}
			}
			$supire[]=array(
				'qk'=>$spr[505],
				'akumulasi'=>$akumulasi[505],
			);
		}
		//echo "<pre>";print_r($supire);exit;
		
		$sql2="SELECT DISTINCT p.nomor FROM sj_supir p JOIN sj_kernet pk ON(pk.nomor=p.nomor) WHERE pk.kernet='".$user_id."' AND status <> 3 AND DATE(tanggal) <= '".$date_end."' AND DATE(tanggal) >= '".$date_start."'";
		$result2=$this->db->query($sql2);
		foreach($result2->rows as $r){
			//hitung kernet
			$ker="SELECT COALESCE(COUNT(DISTINCT kernet),0) as total FROM sj_kernet WHERE nomor='".$r['nomor']."' AND kernet='".$user_id."' ";
			$kernet=$this->db->query($ker);

			foreach($premi as $p){
				$totalsopir=0;
				$premijual=0;
				$jumlah=$this->db->query("SELECT COALESCE(SUM(pp.qty),0) as total FROM sj_product pp WHERE nomor='".$r['nomor']."' AND pp.kodepremi='".$p['id']."'");
				$totalsopir=$jumlah->row['total'];

				if($kernet->row['total'] > 0){
					if($kernet->row['total']== 1 ){
						$persen=0.4;
					}
					if($kernet->row['total']== 2 ){
						$persen=0.3;
					}
				}
				else{
					$persen=0;
				}

				if($totalsopir > 0 & $totalsopir <= 500){
					$premijual=$persen*$p['kelompok']*$totalsopir;
				}
				if($totalsopir > 500 & $totalsopir <= 1000){
					$premijual=$persen*$p['kelompok2']*$totalsopir;
				}
				if($totalsopir > 1000 & $totalsopir <= 1500){
					$premijual=$persen*$p['kelompok3']*$totalsopir;
				}
				if($totalsopir > 1500){
					$premijual=$persen*$p['kelompok4']*$totalsopir;
				}
				$total+=$premijual;
				if(isset($akumulasi[$p['id']])){
					$a=$akumulasi[$p['id']];
					$akumulasi[$p['id']]=array(
						'kodepremi'	=> $p['id'],
						'kelompok'	=> $p['kelompok'],
						'kelompok2'	=> $p['kelompok2'],
						'kelompok3'	=> $p['kelompok3'],
						'kelompok4'	=> $p['kelompok4'],
						'total'	=> $a['total'],
						'totalkernet'	=> $a['totalkernet']+$totalsopir,
						'premikernet'	=> $a['premikernet'] + $premijual,
						'premisopir'	=> $a['premisopir']
					);
				}else{
					$akumulasi[$p['id']]=array(
						'kodepremi'	=> $p['id'],
						'kelompok'	=> $p['kelompok'],
						'kelompok2'	=> $p['kelompok2'],
						'kelompok3'	=> $p['kelompok3'],
						'kelompok4'	=> $p['kelompok4'],
						'total'	=> 0,
						'totalkernet'	=> $totalsopir,
						'premikernet'	=> $premijual,
						'premisopir'	=> 0
					);
				}
			}
		}

		$hasil=array(
			'total'	=> $total,
			'akumulasi'=> $akumulasi
		);
		return $hasil;
	}

}
?>
