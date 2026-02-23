<?php
class ControllerApiPenyesuaian extends Controller {
public function mothly(){
	$tgl='2019-03-27';
	$akhirbulan=date("Y-m-t", time());
	$tanggalproses='2019-01-01';
	$hasil=array();

	while($tanggalproses <= $tgl){
		$hasil[]=$tanggalproses;
		$tanggalproses=date('Y-m-t', strtotime('+1 month', strtotime($tanggalproses)));

	}
	echo json_encode($hasil);
}
public function prosesperiodik(){
	$this->load->model('keuangan/iklanperiodik');
	if(isset($this->request->get['randomkeys'])){
		$key=$this->request->get['randomkeys'];
		if($key == 'AA100u1u2'){
			$periodik=$this->model_keuangan_iklanperiodik->prosesPeriodik();
		}
	}
	echo json_encode($periodik);
}

  public function updateSalesOrder(){
    if(isset($this->request->get['randomkeys'])){
      $key=$this->request->get['randomkeys'];
      if($key == 'AA100u1u2'){
        $so=$this->db->all('sales_order',array());
        foreach($so as $s){

        }
      }
    }
  }
  public function gudanginvoice(){
    if(isset($this->request->get['randomkeys'])){
      $key=$this->request->get['randomkeys'];
      if($key == 'AA100u1u2'){
        $this->load->model('sale/invoice');
        $this->load->model('sale/penjualan');
        $this->load->model('sale/penjualanmr');
        $this->load->model('sale/salesordermr');
        $this->load->model('sale/salesorder');

        $results = $this->model_sale_invoice->getPenjualans(array(),array(),array(),array(),0,null);
        foreach ($results as $trans) {
          $ref['gudang_id']=1;
          if($trans['jenisinvoice'] == 3){
      			if($trans['jenispenjualan'] == 1){
      				$this->load->model('sale/penjualan');
      				$ref=$this->model_sale_penjualan->getPenjualan(array('id'=>$trans['referensi']));
      				//$trans['ref']=$ref['no_sj'];

      			}
      			if($trans['jenispenjualan'] == 2){
      				$this->load->model('sale/penjualanmr');
      				$ref=$this->model_sale_penjualanmr->getPenjualan(array('id'=>$trans['referensi']));
      				//$trans['ref']=$ref['no_sj'];
      			}
      			if($trans['jenispenjualan'] == 3){
      				$this->load->model('sale/penjualanbahanbaku');
      				$ref=$this->model_sale_penjualanbahanbaku->getPenjualan(array('id'=>$trans['referensi']));
      				//$trans['ref']=$ref['no_sj'];
      			}
      		}else{
      			if($trans['jenispenjualan'] == 1){
      				$this->load->model('sale/salesorder');
      				$ref=$this->model_sale_salesorder->getPenjualan(array('id'=>$trans['referensi']));
      				//$trans['ref']=$ref['no_so'];
      			}
      			if($trans['jenispenjualan'] == 2){
      				$this->load->model('sale/salesordermr');
      				$ref=$this->model_sale_salesordermr->getPenjualan(array('id'=>$trans['referensi']));
      				//$trans['ref']=$ref['no_so'];
      			}
      			if($trans['jenispenjualan'] == 3){
      				$this->load->model('sale/salesorderbahanbaku');
      				$ref=$this->model_sale_salesorderbahanbaku->getPenjualan(array('id'=>$trans['referensi']));
      				//$trans['ref']=$ref['no_so'];
      			}
      		}
          if(empty($ref['gudang_id'])){
            $ref['gudang_id']=0;
          }
          $this->db->update('invoice',array('gudang_id'=>$ref['gudang_id']),array('id'=>$trans['id']));
        }

      }
    }
    echo 'oke';
  }
  public function updatenetcost(){
    $hasil=array();
    $prods=$this->db->query("SELECT * FROM product_gudang WHERE net_cost > 0");

    foreach($prods->rows as $p){
      $invs=$this->db->query("SELECT ip.* FROM invoice_product ip JOIN invoice i ON(ip.sales_order_id=i.id) WHERE ip.product_id='".$p['product_id']."' AND i.gudang_id='".$p['gudang_id']."' AND ip.net_cost =0");
      foreach($invs->rows as $i){
        $this->db->update('invoice_product',array('net_cost'=>$p['net_cost']),array('id'=>$i['id']));

      }

      $invs=$this->db->query("SELECT ip.* FROM sales_order_product ip JOIN sales_order i ON(ip.sales_order_id=i.id) WHERE ip.product_id='".$p['product_id']."' AND i.gudang_id='".$p['gudang_id']."' AND ip.net_cost =0");
      foreach($invs->rows as $i){
        $this->db->update('sales_order_product',array('net_cost'=>$p['net_cost']),array('id'=>$i['id']));

      }

      $invs=$this->db->query("SELECT ip.* FROM penjualan_product ip JOIN penjualan i ON(ip.sales_order_id=i.id) WHERE ip.product_id='".$p['product_id']."' AND i.gudang_id='".$p['gudang_id']."' AND ip.net_cost =0");
      foreach($invs->rows as $i){
        $this->db->update('penjualan_product',array('net_cost'=>$p['net_cost']),array('id'=>$i['id']));

      }

    }
    echo json_encode($hasil);
  }
  public function updateqty(){
    $this->load->model('catalog/product');
    $products=$this->model_catalog_product->getProducts();

    foreach($products as $p){
      $this->model_catalog_product->updateQty($p['product_id'],1,1);
    }
  }

  public function hapusjurnal(){
    $ju=$this->db->all('jurnal_umum',array('type'=>1000));
    foreach($ju as $j){
      $this->db->delete('jurnal_umum_detail',array('jurnal_id'=>$j['id']));
      $this->db->delete('jurnal_umum',array('id'=>$j['id']));
    }
  }
  public function customer(){
    $hasil=array();
    $cust=$this->db->query("SELECT * FROM customer");
    $hasil['customer']=$cust->rows;
    echo json_encode($hasil);
  }
  public function dump(){
    $data_string=json_encode(array());

    $address='http://erp.nissonindonesia.com/index.php?route=api/penyesuaian/customer';

        $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $address,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 500,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
       CURLOPT_POSTFIELDS	=>$data_string,
      CURLOPT_HTTPHEADER => array(
      'Content-Type: application/x-www-form-urlencoded'
      ),
    ));

    $response = curl_exec($curl);
    $hasil=json_decode($response,true);
    $err = curl_error($curl);

    /*foreach($hasil['bp'] as $a){
      $this->db->insert('cicilan',$a);
    }
    foreach($hasil['bt'] as $a){
      $a['name']=$this->db->escape($a['name']);
      $this->db->insert('city',$a);
    }

    foreach($hasil['c'] as $a){
      $a['name']=$this->db->escape($a['name']);
      $this->db->insert('country',$a);
    }*/
    /*foreach($hasil['coupon'] as $a){

      $this->db->insert('coupon',$a);
    }*/
    //$this->db->delete('country',array('country_id'=>array('>',0)));
    $this->db->query("DELETE FROM customer");
    foreach($hasil['customer'] as $a){

      $column='';
  		$vals='';
  		$i=1;
  		foreach($a as $key => $value){
  			  if(!empty($value) & !is_null($value)){
  				if($i != 1){
  			         $column .=",";
  							 $vals .=",";
  				}
  				$column .= $key;
  				$vals .= "'".$this->db->escape($value)."'";
  				$i++;
          }
  			}


  		$sql="INSERT INTO customer(".$column.") values(".$vals.")";

  		$this->db->query($sql);
    //$this->db->insert('customer',$a);
    //$this->db->update('tabung_mp',array('status'=>6),array('id'=>$a['id_tabung']));
    }


    echo json_encode($hasil);
  }
  public function catatDeposit(){
    $cust=$this->db->query("SELECT * FROM customer WHERE hapus=0");
    $cid=array();
    $total=0;
    foreach($cust->rows as $c){
      if($c['deposit'] > 0){
        $total++;
        $hutang=array(
    			'ref'=> 0,
    			'date_trans'	=> '2018-02-28',
    			'saldomasuk'	=> $c['deposit'],
    			'saldokeluar'	=> 0,
    			'keterangan'	=> 'Set Saldo Awal',
    			'hapus'	=> 0,
    			'customer_id'=> $c['customer_id'],
    			'date_added'	=> date('Y-m-d H:i:s'),
    			'date_modified' => date('Y-m-d H:i:s')
    		);
    		$this->db->insert('history_deposit',$hutang);
      /*  if(!isset($cid[$c['customer_id']])){
        $p=array(
          'tgl_diterima' => '2018-02-28',
          'nominal'  => $c['deposit'],
          'biaya_bank'  => 0,
          'no_giro'  => '',
          'tgl_bayar'  => '2018-02-28',
          'status'  => 2,
          'keterangan'  => 'Set awal nilai deposit',
          'bank_id' => 502,
          'customer_id' => $c['customer_id'],
          'customer_name' => $c['name'],
          'cetak'  => 0,
          'ref'  => 0,
          'user_id' => 12,
          'hapus' =>0,
          'metode_pembayaran' => 2,
          'jenis' => 1
        );

        $this->db->insert('penerimaan_dana',$p);
      }else{
        $cid[$c['customer_id']]=0;
      }*/
      }

    }
    $hasil[]=$total;
    echo json_encode($hasil);
  }
}
?>
