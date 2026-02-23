<?php
class ModelKeuanganSaldoawalcoa extends Model {
	public function addSaldo($data){
		$saldo=array(
			'tahun'	=>$data['tahun'],
            'date_added'	=>date('Y-m-d H:i:s'),
            'kode_rek'  => $this->db->escape($data['kode_rek']),
			'user_id'	=>$this->user->getId(),
            'kredit'    => $data['kredit'],
            'debet' => $data['debet'],
			'hapus'	=>0
		);
		$this->db->insert('saldoawalcoa',$saldo);
	}
	public function updateSaldo($data,$where=array()){
	$this->db->update('saldoawalcoa',$data,$where);
	}
	public function getSaldo($where){
        $leftjoin=array();
        $leftjoin[]=array(
            'tablename' => 'coamnb',
            'firsttable'    => 'saldoawalcoa.kode_rek',
            'secondtable'   => 'coamnb.kode_rek'
        );
		return $this->db->firstdetail('saldoawalcoa',array('saldoawalcoa.*','coamnb.name'),$leftjoin,$where);
	}
	public function getSaldos($column,$join,$leftjoin,$where,$order,$limit,$offset){
		return $this->db->alljoins('saldoawalcoa',$column,$join,$leftjoin,$where,$order,$limit,$offset);
	}
	public function totalSaldos($where,$join,$leftjoin){
		return $this->db->countAll('saldoawalcoa',$where,$join,$leftjoin);
	}
	// baru 16 April  2020
	public function getsaldoawal($kode_rek,$tahun){
		$sql="SELECT saldoawalcoa.*, coamnb.name FROM saldoawalcoa JOIN coamnb ON(saldoawalcoa.kode_rek = coamnb.kode_rek)  WHERE saldoawalcoa.kode_rek='$kode_rek' AND ";
		$sql.=" tahun='$tahun' AND saldoawalcoa.hapus=0 ORDER BY tahun DESC LIMIT 1 ";
		$d=$this->db->query($sql);
		return $d->row;
	}

	// baru  12 mei 2020
	public function ceksaldocoa($tahun,$kode_rek){
		$sql=$this->db->query("SELECT * FROM saldoawalcoa WHERE tahun='$tahun' AND kode_rek='$kode_rek' AND hapus=0 ");
		return $sql->rows;
	}
}
?>
