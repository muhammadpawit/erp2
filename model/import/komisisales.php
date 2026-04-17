<?php
class ModelImportKomisiSales extends Model {
  
  public function getSales($nama){
    $sql="SELECT id,namasales FROM namasales ";
    $sql.=" WHERE hapus=0 AND lower(namasales)='".htmlspecialchars($nama, ENT_QUOTES)."' ";
    $d=$this->db->query($sql);
    return $d->row;
  }

  public function cek_iv($nomor){
    $sql="SELECT * FROM inv_komisi_sales WHERE hapus=0 and nomorinvoice='$nomor' ";
    $d=$this->db->query($sql);
    return $d->row;
  }

  public function sumcompare($data){
    $sql="SELECT sum(qty*hargasatuan) as total FROM inv_komisi_sales_compare WHERE id>0 AND namasales<>'' ";
    if(!empty($data['tanggal'])){
      $sql.=" AND DATE(tglinvoice) BETWEEN '".$data['tanggal']."' AND '".$data['tanggal2']."' ";
    }
    if(!empty($data['filter_sales'])){
      //$sql .= " AND lower(namasales) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_sales'])) . "%'";
      $sql .= " AND sales_id='" . $this->db->escape(utf8_strtolower($data['filter_sales'])) . "'";
    }
    $d=$this->db->query($sql);
    return $d->row['total'];
  }

  public function sum($data){
    $sql="SELECT sum(qty*hargasatuan) as total FROM inv_komisi_sales WHERE id>0 AND namasales<>'' AND hapus=0 ";
    if(!empty($data['tanggal'])){
      $sql.=" AND DATE(tglinvoice) BETWEEN '".$data['tanggal']."' AND '".$data['tanggal2']."' ";
    }
    if(!empty($data['filter_sales'])){
      $sql .= " AND sales_id='" . $this->db->escape($data['filter_sales']) . "'";
    }
    if(!empty($data['filter_status'])){
      $sql .= " AND status='" . $this->db->escape($data['filter_status']) . "'";
    }
    if(!empty($data['filter_gudang_id'])){
      $sql .= " AND gudang_id='" . $this->db->escape($data['filter_gudang_id']) . "'";
    }
    $d=$this->db->query($sql);
    return $d->row['total'];
  }
  public function gethargaterendah($tglso,$gudang,$kodebarang){
      $harga=0;
      if(!empty($gudang)){
        $sql="SELECT harga_terendah FROM harga_terendah_new WHERE gudang='$gudang' and DATE(tgl_berlaku)<='$tglso' AND kodebarang='$kodebarang'";
        $sql.=" ORDER BY tgl_berlaku DESC LIMIT 1 ";
        $d=$this->db->query($sql);
        $n=$d->row;
        if(!empty($n)){
          $harga=$n['harga_terendah'];
        }else{
          $harga=0;
        }
      }
      return $harga;
  }
  public function getpoin($kodebarang){
    $tanggal=date('Y-m-d');
    if($tanggal>'2021-01-01'){
      $sql=" SELECT poin FROM product_baru WHERE kodebarang='$kodebarang' ORDER by id DESC LIMIT 1 ";
    }else{
      $sql=" SELECT poin FROM harga_terendah_new WHERE kodebarang='$kodebarang' ORDER by id DESC LIMIT 1 "; 
    }

    $d=$this->db->query($sql);

    return $d->row['poin'];
  }

  public function GetImportdetail($data){
    $sql="SELECT nomorinvoice FROM inv_komisi_sales WHERE id>0 group by nomorinvoice";

    $d=$this->db->query($sql);

    return $d->rows;
  }

  public function GetImport($data){
    $sql=" SELECT * FROM inv_komisi_sales WHERE id>0";

    $d=$this->db->query($sql);

    return $d->rows;
  }

  public function GetImportdetails($id){
    $products=array();
    // Optimized: Combine invoice items with latest harga_terendah and poin in one query
    $sql=" SELECT i.*, 
      (SELECT harga_terendah FROM harga_terendah_new h 
       WHERE h.gudang = i.gudang_id AND h.kodebarang = i.kodebarang AND h.tgl_berlaku <= i.tglso::date
       ORDER BY h.tgl_berlaku DESC LIMIT 1) as lookup_harga_terendah,
      (SELECT poin FROM product_baru p 
       WHERE p.kodebarang = i.kodebarang ORDER BY p.id DESC LIMIT 1) as lookup_poin
    FROM inv_komisi_sales i 
    WHERE i.nomorinvoice='" . $this->db->escape($id) . "' and i.hapus=0 ";
    
    $d=$this->db->query($sql);
    $res = $d->rows;
    foreach ($res as $result) {
      $harga_terendah = $result['lookup_harga_terendah'] ? $result['lookup_harga_terendah'] : 0;
      $poin = $result['lookup_poin'] ? $result['lookup_poin'] : 0;
      
      $biayatransport = 0;
      if($result['pengiriman']==1){
        $bypass_customers = array('20-10-C-0055', '20-11-C-0065', '20-10-C-0004', '20-10-C-0224', '20-11-C-0035');
        if(in_array(strtoupper($result['kodecustomer']), $bypass_customers)){
          $biayatransport=0;
        }else{
          $biayatransport = ($result['gudang_id']==1) ? 350000 : 250000;
        }
      }
      
      $products[]=array(
        'product_id'=>$result['kodebarang'],
        'tglinvoice'=>$result['tglinvoice'],
        'tgllunas'=>$result['tgllunas'],
        'tglso'=>$result['tglso'],
        'namasales'=>$result['namasales'],
        'nomorinvoice'=>$result['nomorinvoice'],
        'namacustomer'=>$result['namacustomer'],
        'namabarang'=>$result['namabarang'],
        'qty'=>$result['qty'],
        'hargasatuan'=>$result['hargasatuan'],
        'harga_terendah'=>$harga_terendah,
        'totalhargaterendah'=>$harga_terendah*$result['qty'],
        'biayatransport'=>$biayatransport,
        'poin'=>$poin,
        'metodepembayaran'=>$result['metodepembayaran'],
        'status'=>$result['status'],
        'ivs'=>0,
        'kodebarang'=>$result['kodebarang'],
        'pengiriman'=>$result['pengiriman'],
      );
    }
    return $products;
  }

  public function get($nomor){
    $sql="SELECT * FROM inv_komisi_sales WHERE id>0 AND hapus=0 AND nomorinvoice='$nomor' ";
    $d=$this->db->query($sql);
    return $d->row;
  }

  public function sumIvscompare($no){
    $sql="SELECT sum(hargasatuan*qty) as total FROM inv_komisi_sales_compare WHERE nomorinvoice='$no' AND hapus=0";
    $d=$this->db->query($sql);
    return $d->row['total'];
  }

  public function GetImportdetailsCompare($id){
    $products=array();
    // Optimized: Combine invoice items with latest harga_terendah and poin in one query
    $sql=" SELECT i.*, 
      (SELECT harga_terendah FROM harga_terendah_new h 
       WHERE h.gudang = i.gudang_id AND h.kodebarang = i.kodebarang AND h.tgl_berlaku <= i.tglso::date
       ORDER BY h.tgl_berlaku DESC LIMIT 1) as lookup_harga_terendah,
      (SELECT poin FROM product_baru p 
       WHERE p.kodebarang = i.kodebarang ORDER BY p.id DESC LIMIT 1) as lookup_poin
    FROM inv_komisi_sales_compare i 
    WHERE i.nomorinvoice='" . $this->db->escape($id) . "' and i.hapus=0 ";
    
    $d=$this->db->query($sql);
    $res = $d->rows;
    foreach ($res as $result) {
      $harga_terendah = $result['lookup_harga_terendah'] ? $result['lookup_harga_terendah'] : 0;
      $poin = $result['lookup_poin'] ? $result['lookup_poin'] : 0;
      
      $biayatransport = 0;
      if($result['pengiriman']==1){
        $bypass_customers = array('20-10-C-0055', '20-11-C-0065', '20-10-C-0004', '20-10-C-0224', '20-11-C-0035');
        if(in_array(strtoupper($result['kodecustomer']), $bypass_customers)){
          $biayatransport=0;
        }else{
          $biayatransport = ($result['gudang_id']==1) ? 350000 : 250000;
        }
      }
      
      $products[]=array(
        'product_id'=>$result['kodebarang'],
        'tglinvoice'=>$result['tglinvoice'],
        'tgllunas'=>$result['tgllunas'],
        'tglso'=>$result['tglso'],
        'namasales'=>$result['namasales'],
        'nomorinvoice'=>$result['nomorinvoice'],
        'namacustomer'=>$result['namacustomer'],
        'namabarang'=>$result['namabarang'],
        'qty'=>$result['qty'],
        'hargasatuan'=>$this->currency->format($result['hargasatuan']),
        'harga_terendah'=>$this->currency->format($harga_terendah),
        'totalhargaterendah'=>$harga_terendah*$result['qty'],
        'biayatransport'=>$this->currency->format($biayatransport),
        'poin'=>$poin,
        'nomorinvoice'=>$result['nomorinvoice'],
        'metodepembayaran'=>$result['metodepembayaran'],
        'status'=>$result['status'],
        'ivs'=>$this->currency->format(0),
        'kodebarang'=>$result['kodebarang'],
        'pengiriman'=>$result['pengiriman'],
      );
    }
    return $products;
  }

  public function getcompare($nomor){
    $sql="SELECT * FROM inv_komisi_sales_compare WHERE id>0 AND hapus=0 AND nomorinvoice='$nomor' ";
    $d=$this->db->query($sql);
    return $d->row;
  }

  public function GetImportGroupCompare($data){

    $sql="SELECT nomorinvoice FROM inv_komisi_sales_compare WHERE id>0 AND hapus=0 ";
    
    if(!empty($data['tanggal'])){
      $sql.=" AND DATE(tglinvoice) BETWEEN '".$data['tanggal']."' AND '".$data['tanggal2']."' ";
    }
    if(!empty($data['filter_sales'])){
      $sql .= " AND sales_id='" . $this->db->escape(utf8_strtolower($data['filter_sales'])) . "'";
    }

    if(!empty($data['filter_status'])){
      
      $sql .= " AND status='" . $this->db->escape(utf8_strtolower($data['filter_status'])) . "'";
    }

    if(!empty($data['filter_gudang_id'])){
      $sql .= " AND gudang_id='" . $this->db->escape(utf8_strtolower($data['filter_gudang_id'])) . "'";
    }

    $sql.=" Group by nomorinvoice ORDER BY tglso ASC";
    
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

  public function GetImportGroup($data){    
    $sql="SELECT nomorinvoice, tglinvoice, tgllunas, kodecustomer, namacustomer, customerbaru, pengiriman, gudang_id, kota, provinsi, 
          SUM(hargasatuan * qty) as total_ivs 
          FROM inv_komisi_sales 
          WHERE id>0 AND hapus=0 ";
          
    if(!empty($data['tanggal'])){
      $sql.=" AND DATE(tglinvoice) BETWEEN '".$data['tanggal']."' AND '".$data['tanggal2']."' ";
    }
    if(!empty($data['filter_sales'])){
      $sql .= " AND sales_id='" . $this->db->escape($data['filter_sales']) . "'";
    }
    if(!empty($data['filter_status'])){
      $sql .= " AND status='" . $this->db->escape($data['filter_status']) . "'";
    }
    if(!empty($data['filter_gudang_id'])){
      $sql .= " AND gudang_id='" . $this->db->escape($data['filter_gudang_id']) . "'";
    }

    $sql.=" GROUP BY nomorinvoice, tglinvoice, tgllunas, kodecustomer, namacustomer, customerbaru, pengiriman, gudang_id, kota, provinsi";
    $sql.=" ORDER BY tglinvoice ASC";
    
    if (isset($data['start']) || isset($data['limit'])) {
      if ($data['start'] < 0) { $data['start'] = 0; }
      if ($data['limit'] < 1) { $data['limit'] = 20; }
      $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
    }
    $d=$this->db->query($sql);
    return $d->rows;
  }

  public function CountGetImportGroup($data){
    //$sql="SELECT nomorinvoice FROM inv_komisi_sales GROUP BY nomorinvoice";
    $sql="SELECT tglso,tglinvoice,tgllunas,namacustomer,nomorinvoice,pengiriman,gudang_id,customerbaru,kodecustomer FROM inv_komisi_sales WHERE id>0 ";
    if(!empty($data['tanggal'])){
      $sql.=" AND DATE(tglso) BETWEEN '".$data['tanggal']."' AND '".$data['tanggal2']."' ";
    }
    if(!empty($data['filter_sales'])){
      //$sql .= " AND lower(namasales) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_sales'])) . "%'";
      $sql .= " AND sales_id='" . $this->db->escape(utf8_strtolower($data['filter_sales'])) . "'";
    }

    if(!empty($data['filter_status'])){
      //$sql .= " AND lower(status) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_status'])) . "%'";
      $sql .= " AND status='" . $this->db->escape(utf8_strtolower($data['filter_status'])) . "'";
    }

    if(!empty($data['filter_gudang_id'])){
      $sql .= " AND gudang_id='" . $this->db->escape(utf8_strtolower($data['filter_gudang_id'])) . "'";
    }

    $sql.=" group by tglso,tglinvoice,tgllunas,nomorinvoice,namacustomer,pengiriman,gudang_id,customerbaru,kodecustomer ORDER BY tglso ASC";
    $d=$this->db->query($sql);

    return $d->rows;
  }

  public function CountGetImportGroupcompare($data){
    //$sql="SELECT nomorinvoice FROM inv_komisi_sales GROUP BY nomorinvoice";
    $sql="SELECT tglso,tglinvoice,tgllunas,namacustomer,nomorinvoice,pengiriman,gudang_id,customerbaru,kodecustomer FROM inv_komisi_sales_compare WHERE id>0 ";
    if(!empty($data['tanggal'])){
      $sql.=" AND DATE(tglso) BETWEEN '".$data['tanggal']."' AND '".$data['tanggal2']."' ";
    }
    if(!empty($data['filter_sales'])){
      //$sql .= " AND lower(namasales) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_sales'])) . "%'";
      $sql .= " AND sales_id='" . $this->db->escape(utf8_strtolower($data['filter_sales'])) . "'";
    }

    if(!empty($data['filter_status'])){
      //$sql .= " AND lower(status) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_status'])) . "%'";
      $sql .= " AND status='" . $this->db->escape(utf8_strtolower($data['filter_status'])) . "'";
    }

    if(!empty($data['filter_gudang_id'])){
      $sql .= " AND gudang_id='" . $this->db->escape(utf8_strtolower($data['filter_gudang_id'])) . "'";
    }

    $sql.=" group by tglso,tglinvoice,tgllunas,nomorinvoice,namacustomer,pengiriman,gudang_id,customerbaru,kodecustomer ORDER BY tglso ASC";
    $d=$this->db->query($sql);

    return $d->rows;
  }

  public function GetImportGroupSales(){
    //$sql="SELECT nomorinvoice FROM inv_komisi_sales GROUP BY nomorinvoice";
    $sql="SELECT id,namasales FROM namasales ";
    //$sql.=" Group By namasales ORDER BY namasales";
    $d=$this->db->query($sql);
    return $d->rows;
  }

  public function sumIvs($no){
    $sql="SELECT sum(hargasatuan*qty) as total FROM inv_komisi_sales WHERE nomorinvoice='$no' AND hapus=0";
    $d=$this->db->query($sql);
    return $d->row['total'];
  }

  public function sumIvsql($no){
    $sql="SELECT sum(hargasatuan*qty) as total FROM inv_komisi_sales WHERE nomorinvoice='$no' AND hapus=0";
    $d=$this->db->query($sql);
    //return $d->row;
    return $sql;
  }
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
