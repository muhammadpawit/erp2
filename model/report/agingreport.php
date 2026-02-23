<?php
/*
Created by morebit   | http://morebit.co   |  info@morebit.co
*/
class ModelReportAgingreport extends Model {
  /*public function getProductTerjual($product_id,$product_option_id,$data){
    $sql ="SELECT SUM(quantity) AS quantity,SUM(total) as total,SUM(quantity*net_cost) AS net_cost FROM new_order_product np JOIN";
  }*/
  // baru 4 Mei 2020
  public function getfollowup($customer_id){
    $hasil=array();
    $sql="SELECT followuppenagihan.*,users.firstname as oleh FROM followuppenagihan left join users on (users.user_id=followuppenagihan.user_created)WHERE customer_id='$customer_id' ORDER BY id DESC LIMIT 1";
    $d=$this->db->query($sql);
    if(!empty($d->row)){
      $hasil=array(
        'hasilpembicaraan'=>$d->row['hasil_pembicaraan'],
        'tanggal'=>date('d/m/Y',strtotime($d->row['tanggal'])),
        'oleh'=>' oleh '.$d->row['oleh'],
      );
    }else{
      $hasil=array(
        'hasilpembicaraan'=>null,
        'tanggal'=>'belum difollow up',
        'oleh'=>null,
      );
    }
    return $hasil['hasilpembicaraan'].' <br>('.$hasil['oleh'].' '.$hasil['tanggal'].')';
  }
  // end baru
  // baru 30 Maret 2020
  public function getjadwalpenagihancustomer($customer_id){
    $hasil=array();
    $d=$this->db->query("SELECT cara_penagihan,jam_penagihan FROM customer WHERE customer_id='$customer_id' ");
    foreach($d->rows as $r){
      if($r['cara_penagihan']==0){
        $cara_penagihan="belum diatur";
      }
      if($r['cara_penagihan']==1){
        $cara_penagihan="Tukar TT By Kurir Nisson";
      }
      if($r['cara_penagihan']==2){
        $cara_penagihan="Tukar TT By Titip Sales";
      }
      if($r['cara_penagihan']==3){
        $cara_penagihan="Scan By E-mail";
      }
      if($r['cara_penagihan']==4){
        $cara_penagihan="Tukar TT By Titip Sopir";
      }
      if($r['cara_penagihan']==5){
        $cara_penagihan="Tukar TT By JNE";
      }
      $hasil[]=array(
        'jam_penagihan'=>$r['jam_penagihan']!='0'?$r['jam_penagihan']:'belum diatur',
        'cara_penagihan'=>$cara_penagihan,
      );
    }
    return $hasil;
  }
  public function getjadwalharicustomer($customer_id){
    $d=$this->db->query("SELECT h.namahari FROM hari h JOIN customer_jadwalpenagihan cj ON(cj.hari=h.id) LEFT JOIN customer c ON (c.customer_id=cj.customer_id) WHERE c.customer_id='$customer_id' ");
    return $d->rows;
  }
  // end baru
  public function agingreportcustomer($data=array()){
    //data invoice
    //history pembayaran
    //history deposit
    $this->load->model('sale/customer');
    $this->load->model('sale/invoice');
    $this->load->model('catalog/title');
    $this->load->model('keuangan/penerimaandana');
    $agings=array();
    $inv="SELECT i.customer_id,customer.name,COALESCE(SUM(totaltagihan),0) as totaltagihan FROM invoice i JOIN customer  ON(i.customer_id=customer.customer_id) WHERE i.status <> 4  ";
    $inv .= "AND gudang_id IN(".$data['filter_gudang'].") AND i.jenisinvoice=3 ";
    //$inv .= "AND gudang_id IN(1) AND i.jenisinvoice=3 ";
    if(!empty($data['filter_customer'])){
      $inv .=" AND i.customer_id = '".$data['filter_customer']."'";
    }
    if($data['filter_type'] == 2){
      if(!empty($data['filter_tanggal'])){
          $inv .=" AND i.date_added <= '".$data['filter_tanggal']."'";
      }else{
        $inv .=" AND i.date_added <= '".date('Y-m-d')."'";
      }
    }
    if($data['filter_type'] == 1){
      if(!empty($data['filter_tanggal'])){
          $inv .=" AND jatuhtempo <= '".$data['filter_tanggal']."'";
      }else{
        $inv .=" AND jatuhtempo <= '".date('Y-m-d')."'";
      }
    }
    $inv .= "GROUP BY i.customer_id,customer.name ";
    $inv .= "ORDER BY ".$data['sort']." ".$data['order'];
    //$inv .=" LIMIT 100";
    $invoices=$this->db->query($inv);


    foreach($invoices->rows as $i){
      $cust=$this->model_sale_customer->getVendor(array('customer_id'=> $i['customer_id']));
      //cek penerimaan dana
      /*$pd="SELECT COALESCE(SUM(nominal),0) as danamasuk FROM penerimaan_dana WHERE status = 2  AND customer_id='".$i['customer_id']."' ";

      if(!empty($data['filter_tanggal'])){
          $pd .=" AND tgl_diterima <= '".$data['filter_tanggal']."'";
      }else{
        $pd .=" AND tgl_diterima <= '".date('Y-m-d')."'";
      }
      */
      //cek deposit
      $dpst="SELECT COALESCE((SUM(saldomasuk)-SUM(saldokeluar)),0) as totaldeposit FROM history_deposit WHERE customer_id='".$i['customer_id']."' AND hapus=0 ";
      if(!empty($data['filter_tanggal'])){
          $dpst .=" AND date_trans <= '".$data['filter_tanggal']."'";
      }else{
        $dpst .=" AND date_trans <= '".date('Y-m-d')."'";
      }
      $deposit=$this->db->query($dpst);

      //cek pembayaran tunai
      $pembayarantunai="SELECT COALESCE(SUM(jumlah),0) as total FROM pembayaran_penjualan JOIN invoice iv ON(pembayaran_penjualan.penjualan_id=iv.id) WHERE pembayaran_penjualan.hapus=0 AND pembayaran_penjualan.status=1 AND iv.customer_id='".$i['customer_id']."' ";
      if(!empty($data['filter_tanggal'])){
          $pembayarantunai .=" AND pembayaran_penjualan.date_added <= '".$data['filter_tanggal']."'";
      }else{
        $pembayarantunai .=" AND pembayaran_penjualan.date_added <= '".date('Y-m-d')."'";
      }
      $pbyt=$this->db->query($pembayarantunai);

      //pembayarankredit
      $pembayarankredit="SELECT COALESCE(SUM(pk.total),0) as total FROM pembayaran_kredit_invoice pk JOIN pembayaran_kredit kr ON(pk.pembayaran_id=kr.id) WHERE hapus=0 AND status=1 AND kr.customer_id='".$i['customer_id']."' ";
      if(!empty($data['filter_tanggal'])){
          $pembayarankredit .=" AND kr.date_added <= '".$data['filter_tanggal']."'";
      }else{
        $pembayarankredit .=" AND kr.date_added <= '".date('Y-m-d')."'";
      }
      $pbyk=$this->db->query($pembayarankredit);

      //$bayar=$this->db->query($pd);
      $sumdeposit=$deposit->row['totaldeposit'] < 0?0:$deposit->row['totaldeposit'];
      $bayartunai=$pbyt->row['total'];
      $bayarkredit=$pbyk->row['total'];
     // $sisabayar=$i['totaltagihan'] - ($bayartunai+$bayarkredit+$sumdeposit);
	$sisabayar=$i['totaltagihan'] - ($bayartunai+$bayarkredit);
      $filter = array(
        //'invoice.id'	=> empty($filter_type)?array('>',0):$filter_type,
        'gudang_id'	=>array('IN',$data['filter_gudang']),
        'customer_id'	=> $i['customer_id'],

        'invoice.status'	=> array('<>',4),

      );
      if($data['filter_type'] == 2){
        if(!empty($data['filter_tanggal'])){
          //  $inv .=" AND date_added <= '".$data['filter_tanggal']."'";
          $filter['date_added'] = array('<=',$data['filter_tanggal']);
        }else{
            $filter['date_added'] = array('<=',date('Y-m-d'));
        }
      }
      if($data['filter_type'] == 1){
        if(!empty($data['filter_tanggal'])){
              $filter['jatuhtempo'] = array('<=',$data['filter_tanggal']);
        }else{
          $filter['jatuhtempo'] = array('<=',date('Y-m-d'));
        }
      }


      $o=array();
      $o=array('date_added'=>'ASC');

      $listinvoice=$this->model_sale_invoice->getPenjualans(array(),array(),$filter,$o,0,null);
      $invs=array();
      $totaldeposit=$deposit->row['totaldeposit'];
      if($totaldeposit < 0){
        $totaldeposit=0;
      }
      $total15=0;
      $total30=0;
      $total60=0;
      $total90=0;
      $total120=0;
      $total121=0;

      $totaltagihan=0;
      $historybayar=0;
      foreach($listinvoice as $ivs){
        //totalbayar
        $totalbayar=0;
        //if($ivs['metode_pembayaran'] == 1 | $ivs['metode_pembayaran'] == 2){
          $pembayaran1="SELECT COALESCE(SUM(jumlah),0) as total FROM pembayaran_penjualan WHERE hapus=0 AND status=1 AND penjualan_id='".$ivs['id']."' ";
          if(!empty($data['filter_tanggal'])){
              $pembayaran1 .=" AND DATE(date_added) <= '".$data['filter_tanggal']."'";
          }else{
            $pembayaran1 .=" AND DATE(date_added) <= '".date('Y-m-d')."'";
          }
          $pby1=$this->db->query($pembayaran1);
        //}else{
          $pembayaran="SELECT COALESCE(SUM(pi.total),0) as total FROM pembayaran_kredit_invoice pi JOIN pembayaran_kredit pk ON(pi.pembayaran_id=pk.id) WHERE pk.status=1 AND hapus=0 AND pi.invoice_id='".$ivs['id']."'";
          if(!empty($data['filter_tanggal'])){
              $pembayaran .=" AND date_added <= '".$data['filter_tanggal']."'";
          }else{
            $pembayaran .=" AND date_added <= '".date('Y-m-d')."'";
          }
          $pby2=$this->db->query($pembayaran);
      //  }
        /*if($pby1->row['total'] > 0){
          $totalbayar=$pby1->row['total'];
        }else{
          $totalbayar=$pby2->row['total'];
        }*/
        $totalbayar=$pby1->row['total'] + $pby2->row['total'];
        //if(){}

        $sisabayars=$ivs['totaltagihan'] - $totalbayar;
        /*if($totaldeposit > 0){
          if($sisabayars > $totaldeposit){
            $sisabayars -= $totaldeposit;
            $totaldeposit=0;

          }else{
            $sisabayars=0;
            $totaldeposit -= $sisabayars;
          }
        }*/
       if($sisabayars > 0){
          /*if($totaldeposit > 0){
            if($sisabayars > $totaldeposit){
              $sisabayars -= $totaldeposit;
              $totaldeposit=0;

            }else{
              $sisabayars=0;
              $totaldeposit -= $sisabayars;
            }
          }*/
         $totalhari=0;
         $dateadd=strtotime($data['filter_tanggal']);
         $tglinvoice=strtotime($ivs['date_added']);
         $jatuhtempo=strtotime($ivs['jatuhtempo']);

         if($data['filter_type'] == 1){
           $selisih=$dateadd - $jatuhtempo;
           $totalhari=floor($selisih / (60 * 60 * 24));
         }else{
           $selisih=$dateadd - $tglinvoice;
           $totalhari=floor($selisih / (60 * 60 * 24));
         }

         if($totalhari <= 15){
          $total15 += $sisabayars;
        }
        if($totalhari > 15 & $totalhari <= 30){
          $total30 += $sisabayars;
        }
         if($totalhari > 30 & $totalhari <=60){
           $total60 += $sisabayars;
         }
         if($totalhari > 60 & $totalhari <=90){
           $total90 += $sisabayars;
         }

         if($totalhari > 90 & $totalhari <=120){
           $total120 += $sisabayars;
         }

         if($totalhari > 120){
           $total121 += $sisabayars;
         }

         $totaltagihan+=$ivs['totaltagihan'];

          $invs[]=array(
            'id'  => $ivs['id'],
            'no_faktur' =>$ivs['no_faktur'],
            'date_added'  => $ivs['date_added'],
            'jatuhtempo'  => $ivs['jatuhtempo'],
            'totaltagihan'  => $ivs['totaltagihan'],
            'totalbayar'  => $totalbayar
          );
          $historybayar+=$totalbayar;
        }
      }

      //0-30

      //31 - 60

      //61-90

      //91-120

      //
      $sisabayar=$totaltagihan-$sumdeposit-$historybayar;
    
      if($sisabayar > 0){
        $agings[]=array(
          'customer_id' => $i['customer_id'],
          'name'  => $this->model_catalog_title->getTitle(empty($cust['title'])?0:$cust['title']).' '.$cust['name'],
          'totaltagihan'  => $totaltagihan,
          'totaldeposit'  => $sumdeposit,
          'sisabayar' => $sisabayar > 0?$sisabayar:0,
          'jumlah15'  => $total15,
          'jumlah30'  => $total30,
          'jumlah60'  => $total60,
          'jumlah90'  => $total90,
          'jumlah120' => $total120,
          'jumlah121' => $total121,
          'inv' => $invs,
          'totalbayar'=>$historybayar
        );
      }
    }

    return $agings;

  }


  public function aginghutang($data=array()){
    //data invoice
    //history pembayaran
    //history deposit
    $this->load->model('sale/customer');
    $this->load->model('catalog/vendorlokal');
    $this->load->model('sale/invoice');
    $this->load->model('pembelian/invoicepembeliandagang');
    $this->load->model('catalog/title');
    $this->load->model('keuangan/penerimaandana');
    $agings=array();
    $inv="SELECT i.vendor_id,vendorlokal.name,COALESCE(SUM(totaltagihan),0) as totaltagihan FROM invoice_pembeliandagang i JOIN vendorlokal  ON(i.vendor_id=vendorlokal.id) WHERE i.status <> 3  ";
    $inv .= "AND gudang_id IN(".$data['filter_gudang'].") ";
    if(!empty($data['filter_customer'])){
      $inv .=" AND i.vendor_id = '".$data['filter_customer']."'";
    }
    
    if($data['filter_type'] == 2){
      if(!empty($data['filter_tanggal'])){
          $inv .=" AND date(i.tglfaktur) <= '".$data['filter_tanggal']."'";
      }else{
        $inv .=" AND date(i.tglfaktur) <= '".date('Y-m-d')."'";
      }
    }
    /*
    if($data['filter_type'] == 1){
      if(!empty($data['filter_tanggal'])){
          $inv .=" AND vendorlokal.jatuhtempo <= '".$data['filter_tanggal']."'";
      }else{
        $inv .=" AND vendorlokal.jatuhtempo <= '".date('Y-m-d')."'";
      }
    }*/
    //$inv .=" AND date(i.tglfaktur) <= '".date('Y-m-d')."'";

    $inv .= "GROUP BY i.vendor_id,vendorlokal.name ";
    $inv .= "ORDER BY ".$data['sort']." ".$data['order'];
    //echo $inv;exit;
    $invoices=$this->db->query($inv);


    foreach($invoices->rows as $i){
      $cust=$this->model_catalog_vendorlokal->getVendor(array('id'=> $i['vendor_id']));
      //cek penerimaan dana
      /*$pd="SELECT COALESCE(SUM(nominal),0) as danamasuk FROM penerimaan_dana WHERE status = 2  AND customer_id='".$i['customer_id']."' ";

      if(!empty($data['filter_tanggal'])){
          $pd .=" AND tgl_diterima <= '".$data['filter_tanggal']."'";
      }else{
        $pd .=" AND tgl_diterima <= '".date('Y-m-d')."'";
      }
      */
      //cek deposit
      $dpst="SELECT COALESCE((SUM(saldomasuk)-SUM(saldokeluar)),0) as totaldeposit FROM history_depositvendor_lokal WHERE vendor_id='".$i['vendor_id']."' AND hapus=0 ";
      if(!empty($data['filter_tanggal'])){
          $dpst .=" AND date(date_trans) <= '".$data['filter_tanggal']."'";
      }else{
        $dpst .=" AND date(date_trans) <= '".date('Y-m-d')."'";
      }
      $deposit=$this->db->query($dpst);

      //cek pembayaran tunai
      /*
      $pembayarantunai="SELECT COALESCE(SUM(jumlah),0) as total FROM pembayaran_penjualan JOIN invoice iv ON(pembayaran_penjualan.penjualan_id=iv.id) WHERE pembayaran_penjualan.hapus=0 AND pembayaran_penjualan.status=1 AND iv.customer_id='".$i['customer_id']."' ";
      if(!empty($data['filter_tanggal'])){
          $pembayarantunai .=" AND pembayaran_penjualan.date_added <= '".$data['filter_tanggal']."'";
      }else{
        $pembayarantunai .=" AND pembayaran_penjualan.date_added <= '".date('Y-m-d')."'";
      }
      $pbyt=$this->db->query($pembayarantunai);
      */

      //pembayarankredit
      /*
      $pembayarankredit="SELECT COALESCE(SUM(pk.nominal),0) as total FROM alokasi_deposit_kredit pk JOIN pembayaran_kredit kr ON(pk.pembayaran_id=kr.id) WHERE hapus=0 AND status=1 AND kr.customer_id='".$i['customer_id']."' ";
      if(!empty($data['filter_tanggal'])){
          $pembayarankredit .=" AND kr.date_added <= '".$data['filter_tanggal']."'";
      }else{
        $pembayarankredit .=" AND kr.date_added <= '".date('Y-m-d')."'";
      }
      $pbyk=$this->db->query($pembayarankredit);
      */
      //$bayar=$this->db->query($pd);
      $sumdeposit=$deposit->row['totaldeposit'] < 0?0:$deposit->row['totaldeposit'];
      //echo "<pre>";print_r($sumdeposit);exit();
      //$bayartunai=$pbyt->row['total'];
      $bayartunai=0;
      //$bayarkredit=$pbyk->row['total'];
      $bayarkredit=0;
     // $sisabayar=$i['totaltagihan'] - ($bayartunai+$bayarkredit+$sumdeposit);
      $sisabayar=$i['totaltagihan'] - ($bayartunai+$bayarkredit);
      $filter = array(
        //'invoice.id'  => empty($filter_type)?array('>',0):$filter_type,
        'gudang_id' =>array('IN',$data['filter_gudang']),
        'vendor_id' => $i['vendor_id'],
        'invoice_pembeliandagang.status'  => array('<>',3),
      );
      
      /*
      if($data['filter_type'] == 2){
        if(!empty($data['filter_tanggal'])){
          //  $inv .=" AND date_added <= '".$data['filter_tanggal']."'";
          $filter['date_added'] = array('<=',$data['filter_tanggal']);
        }else{
            $filter['date_added'] = array('<=',date('Y-m-d'));
        }
      }
      if($data['filter_type'] == 1){
        if(!empty($data['filter_tanggal'])){
              $filter['jatuhtempo'] = array('<=',$data['filter_tanggal']);
        }else{
          $filter['jatuhtempo'] = array('<=',date('Y-m-d'));
        }
      }
      */
      if(!empty($data['filter_tanggal'])){
        //  $inv .=" AND date_added <= '".$data['filter_tanggal']."'";
        //$filter['date_added'] = array('<=',$data['filter_tanggal']);
        $filter['tglfaktur'] = array('<=',$data['filter_tanggal']);
      }else{
        $filter['tglfaktur'] = array('<=',date('Y-m-d'));
        //$filter['tglfaktur'] = array('<=',$data['filter_tanggal']);
      }

      //$filter['tglfaktur'] = array('<=',date('Y-m-d'));


      $o=array();
      $o=array('tglfaktur'=>'ASC');

      $listinvoice=$this->model_pembelian_invoicepembeliandagang->getPenjualans(array(),array(),$filter,$o,0,null);
      //echo "<pre>";print_r($listinvoice);exit();
      $invs=array();
      $totaldeposit=$deposit->row['totaldeposit'];
      if($totaldeposit < 0){
        $totaldeposit=0;
      }
      $total15=0;
      $total30=0;
      $total60=0;
      $total90=0;
      $total120=0;
      $total121=0;

      $totaltagihan=0;
      $historybayar=0;
      foreach($listinvoice as $ivs){
        //totalbayar
        $totalbayar=0;
        //if($ivs['metode_pembayaran'] == 1 | $ivs['metode_pembayaran'] == 2){
          $pembayaran1="SELECT COALESCE(SUM(nominal),0) as total FROM alokasi_deposit_kredit WHERE hapus=0 AND status=1 AND invoice_id='".$ivs['id']."' ";
          if(!empty($data['filter_tanggal'])){
              $pembayaran1 .=" AND DATE(date_added) <= '".$data['filter_tanggal']."'";
          }else{
            $pembayaran1 .=" AND DATE(date_added) <= '".date('Y-m-d')."'";
          }
          $pby1=$this->db->query($pembayaran1);
        //}else{
          /*
          $pembayaran="SELECT COALESCE(SUM(pi.total),0) as total FROM pembayaran_kredit_invoice pi JOIN pembayaran_kredit pk ON(pi.pembayaran_id=pk.id) WHERE pk.status=1 AND hapus=0 AND pi.invoice_id='".$ivs['id']."'";
          if(!empty($data['filter_tanggal'])){
              $pembayaran .=" AND date_added <= '".$data['filter_tanggal']."'";
          }else{
            $pembayaran .=" AND date_added <= '".date('Y-m-d')."'";
          }*/
          //$pby2=$this->db->query($pembayaran);
          $pby2=0;
      //  }
        /*if($pby1->row['total'] > 0){
          $totalbayar=$pby1->row['total'];
        }else{
          $totalbayar=$pby2->row['total'];
        }*/
        //$totalbayar=$pby1->row['total'] + $pby2->row['total'];
        $totalbayar=$pby1->row['total'] + $pby2;
        //if(){}

        $sisabayars=$ivs['totaltagihan'] - $totalbayar;
        /*if($totaldeposit > 0){
          if($sisabayars > $totaldeposit){
            $sisabayars -= $totaldeposit;
            $totaldeposit=0;

          }else{
            $sisabayars=0;
            $totaldeposit -= $sisabayars;
          }
        }*/
       if($sisabayars > 0){
          /*if($totaldeposit > 0){
            if($sisabayars > $totaldeposit){
              $sisabayars -= $totaldeposit;
              $totaldeposit=0;

            }else{
              $sisabayars=0;
              $totaldeposit -= $sisabayars;
            }
          }*/
         $totalhari=0;
         $dateadd=strtotime($data['filter_tanggal']);
         $tglinvoice=strtotime($ivs['tglfaktur']);
         $jatuhtempo=strtotime($ivs['jatuhtempo']);

          if($data['filter_type'] == 1){
            $selisih=$dateadd - $jatuhtempo;
            $totalhari=floor($selisih / (60 * 60 * 24));
          }else{
            $selisih=$dateadd - $tglinvoice;
            $totalhari=floor($selisih / (60 * 60 * 24));
          }

          if($totalhari <= 15){
            $total15 += $sisabayars;
          }
          if($totalhari > 15 & $totalhari <= 30){
            $total30 += $sisabayars;
          }
          if($totalhari > 30 & $totalhari <=60){
            $total60 += $sisabayars;
          }
          if($totalhari > 60 & $totalhari <=90){
           $total90 += $sisabayars;
          }

          if($totalhari > 90 & $totalhari <=120){
            $total120 += $sisabayars;
          }

          if($totalhari > 120){
            $total121 += $sisabayars;
          }

          $totaltagihan+=$ivs['totaltagihan'];

          $invs[]=array(
            'id'  => $ivs['id'],
            'no_faktur' =>$ivs['no_faktur'],
            'date_added'  => $ivs['tglfaktur'],
            'jatuhtempo'  => $ivs['jatuhtempo'],
            'totaltagihan'  => $ivs['totaltagihan'],
            'totalbayar'  => $totalbayar,
            'sisa'=>round($sisabayars)
          );
          $historybayar+=$totalbayar;
        }
      }

      //0-30

      //31 - 60

      //61-90

      //91-120

      //
      $sisabayar=$totaltagihan-$sumdeposit-$historybayar;
    
      //if($sisabayars > 0){
        if($totaltagihan>0){
          $agings[]=array(
            'customer_id' => $i['vendor_id'],
            'name'  => $this->model_catalog_title->getTitle(empty($cust['title'])?0:$cust['title']).' '.$cust['name'],
            'totaltagihan'  => $totaltagihan,
            'totaldeposit'  => $sumdeposit,
            'sisabayar' => $sisabayar > 0?$sisabayar:0,
            'jumlah15'  => $total15,
            'jumlah30'  => $total30,
            'jumlah60'  => $total60,
            'jumlah90'  => $total90,
            'jumlah120' => $total120,
            'jumlah121' => $total121,
            'inv' => $invs,
            'totalbayar'=>$historybayar
          );
        }
      //}
    }

    return $agings;

  }

  // end baru 3 Agustus 2020

}
?>
