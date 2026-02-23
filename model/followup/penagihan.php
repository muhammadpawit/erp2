<?php
class ModelFollowUpPenagihan extends Model {
  // baru 9 Juni 2020
  public function cekinvoice($customer_id){
    $sql="SELECT SUM(total) as total FROM invoice WHERE customer_id='$customer_id' AND status IN(1,2) ";
    $d=$this->db->query($sql);
    return $d->row['total'];
  }
  public function cekdeposit($customer_id){
    $sql="SELECT deposit FROM customer WHERE customer_id='$customer_id' AND hapus=0 ";
    $d=$this->db->query($sql);
    return $d->row['deposit'];
  }
  // end baru
  // baru 8 Juni 2020
  public function getlaporandata($data){
    $sql ="SELECT f.*, c.sales FROM followuppenagihan f LEFT JOIN customer c ON(c.customer_id=f.customer_id) ";
    if(!empty($data['sales'])){
      $sql.=" LEFT JOIN users u ON (u.user_id=c.sales)";
    }
    $sql.=" WHERE f.hapus=0 ";
    if(!empty($data['sales'])){
      $sql.=" AND c.sales='".$data['sales']."' ";
    }
    if(!empty($data['customer_id'])){
      $sql .=" AND f.customer_id='".$data['customer_id']."' ";
    }
    if(!empty($data['filter_date_start'])){
      $sql .=" AND date(tanggal)>='".$data['filter_date_start']."' ";
    }
    if(!empty($data['filter_date_end'])){
      $sql .=" AND date(tanggal)<='".$data['filter_date_end']."' ";
    }
    if(!empty($data['media'])){
      $sql .=" AND media='".$data['media']."' ";
    }
    $sql.=" ORDER BY id DESC ";
    if (isset($data['start']) || isset($data['limit'])) {
		  if ($data['start'] < 0) {
			$data['start'] = 0;
		  }

		  if ($data['limit'] < 1) {
			$data['limit'] = 20;
		  }

		  $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
    }
    $d=$this->db->query($sql);
    return $d->rows;
  }
  public function totalgetlaporandata($data){
    $sql ="SELECT f.*, c.sales FROM followuppenagihan f LEFT JOIN customer c ON(c.customer_id=f.customer_id) ";
    if(!empty($data['sales'])){
      $sql.=" LEFT JOIN users u ON (u.user_id=c.sales)";
    }
    $sql.=" WHERE f.hapus=0 ";
    if(!empty($data['sales'])){
      $sql.=" AND c.sales='".$data['sales']."' ";
    }
    if(!empty($data['customer_id'])){
      $sql .=" AND f.customer_id='".$data['customer_id']."' ";
    }
    if(!empty($data['filter_date_start'])){
      $sql .=" AND date(tanggal)>='".$data['filter_date_start']."' ";
    }
    if(!empty($data['filter_date_end'])){
      $sql .=" AND date(tanggal)<='".$data['filter_date_end']."' ";
    }
    if(!empty($data['media'])){
      $sql .=" AND media='".$data['media']."' ";
    }
    
    $d=$this->db->query($sql);
    return $d->rows;
  }

  // end baru
  public function getuser($id){
    $sql ="SELECT * FROM users WHERE hapus=0 and user_id='$id' ";
    $d=$this->db->query($sql);
    return $d->row['firstname'];
  }
  public function getdetail($id){
    $sql ="SELECT * FROM followuppenagihan WHERE hapus=0 and id='$id' ";
    $d=$this->db->query($sql);
    return $d->row;
  }

  public function getcusts($id){
		$sql ="SELECT * FROM customer where customer_id='$id' ";
		$d = $this->db->query($sql);
		return $d->row['name'];
	}
  public function getdata($data){
    $sql ="SELECT * FROM followuppenagihan WHERE hapus=0 ";
    if(!empty($data['customer_id'])){
      $sql .=" AND customer_id='".$data['customer_id']."' ";
    }
    if(!empty($data['filter_date_start'])){
      $sql .=" AND date(tanggal)>='".$data['filter_date_start']."' ";
    }
    if(!empty($data['filter_date_end'])){
      $sql .=" AND date(tanggal)<='".$data['filter_date_end']."' ";
    }
    if(!empty($data['media'])){
      $sql .=" AND media='".$data['media']."' ";
    }
    $sql.=" ORDER BY id DESC ";
    if (isset($data['start']) || isset($data['limit'])) {
		  if ($data['start'] < 0) {
			$data['start'] = 0;
		  }

		  if ($data['limit'] < 1) {
			$data['limit'] = 20;
		  }

		  $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
    }
    $d=$this->db->query($sql);
    return $d->rows;
  }
  public function totalgetdata($data){
    $sql ="SELECT * FROM followuppenagihan WHERE hapus=0 ";
    if(!empty($data['customer_id'])){
      $sql .=" AND customer_id='".$data['customer_id']."' ";
    }
    if(!empty($data['filter_date_start'])){
      $sql .=" AND date(tanggal)>='".$data['filter_date_start']."' ";
    }
    if(!empty($data['filter_date_end'])){
      $sql .=" AND date(tanggal)<='".$data['filter_date_end']."' ";
    }
    if(!empty($data['media'])){
      $sql .=" AND media='".$data['media']."' ";
    }
    
    $d=$this->db->query($sql);
    return $d->rows;
  }
  public function simpan($data){
    $ins = array(
      'tanggal' =>$data['tanggal'],
      'customer_id'=>$data['customer_id'],
      'media'=>$data['media'],
      'hasil_pembicaraan'=>$this->db->escape($data['hasil_pembicaraan']),
      'hapus'=>0,
      'status'=>1,
      'date_created'=>date('Y-m-d H:i:s'),
      'user_created'=>$this->user->getId(),
    );
    $this->db->insert('followuppenagihan',$ins);
    $id=$this->db->getLastId();
    return $id;
  }

}
?>
