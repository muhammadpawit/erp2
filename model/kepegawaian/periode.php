<?php
class ModelKepegawaianPeriode extends Model {
	public function addPeriode($data){
			$periode=array(
				'nama'	=>$this->db->escape($data['nama']),
				'tgl_awal'	=>$data['tgl_awal'],
				'tgl_selesai'	=>$data['tgl_selesai'],
				'status'	=>1,
				'status_periode'	=> 0
			);
			$this->db->insert('periode',$periode);
			  $id=$this->db->getLastId();

        $tglawal=strtotime($data['tgl_awal']);
        $tglakhir=strtotime($data['tgl_selesai']);
        $i=$tglawal;
        while($i <= $tglakhir){
            if(date("D",$i) == "Sun"){
							$libur=array(
								'tanggal'	=> date('Y-m-d',$i),
								'keterangan'	=> 'Libur hari Minggu',
								'periode_id'	=> $id
							);
              $this->db->insert('libur',$libur);
            }
            $tgl=date('Y-m-d',$i);
            $i=strtotime($tgl."+1 day");
        }
        //$i=strtotime('+1 day',date('Y-m-d',$i));

    }

   // public function activate($periode_id){}

    public function editPeriode($periode_id,$data){
        $this->db->query("UPDATE ".DB_PREFIX."periode SET nama='".$this->db->escape($data['nama'])."',tgl_awal='".$data['tgl_awal']."',tgl_Selesai='".$data['tgl_selesai']."' WHERE periode_id='".$periode_id."'");


    }

    public function deletePeriode($periode_id){
       $this->db->query("UPDATE ".DB_PREFIX."periode set status='0' where periode_id='".$periode_id."'");
    }

    public function getPeriodes($data=array()) {


        $sql="SELECT * FROM " . DB_PREFIX . "periode WHERE status > 0 ";

        if (!empty($data['filter_name'])) {
            $sql .= " AND lower(nama) LIKE '%". $this->db->escape(utf8_strtolower($data['filter_name']))."%'";
        }

        if(!empty($data['filter_date_start'])){
            $sql .= " AND tgl_awal = '".$data['filter_date_start']."'";
        }

        if(!empty($data['filter_date_end'])){
            $sql .= " AND tgl_selesai = '".$data['filter_date_end']."'";
        }

        $sql .= " ORDER BY tgl_awal DESC";
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

	public function getTotalPeriode($data=array()){
		$sql="SELECT COUNT(*) as total FROM " . DB_PREFIX . "periode WHERE status > 0 ";

        if (!empty($data['filter_name'])) {
            $sql .= " AND lower(nama) LIKE '%". $this->db->escape(utf8_strtolower($data['filter_name']))."%'";
        }

        if(!empty($data['filter_date_start'])){
            $sql .= " AND tgl_awal = '".$data['filter_date_start']."'";
        }

        if(!empty($data['filter_date_end'])){
            $sql .= " AND tgl_selesai = '".$data['filter_date_end']."'";
        }
		$query=$this->db->query($sql);
		return $query->row['total'];
	}

	public function getPeriode($periode_id) {
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."periode WHERE periode_id ='" . (int)$periode_id . "'");

		return $query->row;
	}

	public function cekAktif(){
        $query=$this->db->query("SELECT COUNT(*) as total FROM ".DB_PREFIX."periode WHERE status_periode=1");
        return $query->row['total'];
    }

    public function aktivasiPeriode($periode_id){
        $this->db->query("UPDATE ".DB_PREFIX."periode SET status_periode=0");
        $this->db->query("UPDATE ".DB_PREFIX."periode SET status_periode=1 WHERE periode_id='".$periode_id."'");
    }
    public function deaktivasiPeriode($periode_id){
        //$this->db->query("UPDATE ".DB_PREFIX."periode SET status_periode=0");
        $this->db->query("UPDATE ".DB_PREFIX."periode SET status_periode=0 WHERE periode_id='".$periode_id."'");
    }


}
?>
