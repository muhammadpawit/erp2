<?php
/*
Created by morebit   | http://morebit.co   |  info@morebit.co
*/
class ModelReportHutangBelumditagih extends Model {


    public function sum($data){
      $tglawal=$data['filter_date_start'];
      $tglakhir=$data['filter_date_end'];
      $filter_vendor=$data['filter_vendor'];
      // ivs lokal
      $sql="SELECT sp.id,g.nama,sp.total,sp.tgl_surat as tanggal,sp.no_suratjalan,sjp.po_id,pkg.no_po,vl.name FROM suratjalan_pembeliandagang sp join suratjalan_produkdagang sjp on(sjp.id_suratjalan=sp.id) left join invoice_pembelian_productdagang ipp ON(ipp.po_product_id=sjp.pembelian_product_id) left join invoice_pembeliandagang ivp on(ivp.id=ipp.invoice_id) left join pembelian_produk_kreditdagang ppk ON(ppk.pembelian_id=sjp.po_id) JOIN pembelian_kreditdagang pkd ON(pkd.id=ppk.pembelian_id) LEFT JOIN vendorlokal vl ON(vl.id=pkd.vendor_id) ";	
      $sql.=" LEFT JOIN pembelian_kreditdagang pkg ON (pkg.id=sjp.po_id) left join gudang g on(g.gudang_id=pkg.gudang_id) ";
      $sql.=" WHERE ipp.invoice_id IS NULL ";
      if(!empty($tglawal)){
        $sql.=" AND date(sp.tgl_surat) >='".$tglawal."' AND date(sp.tgl_surat) <='".$tglakhir."' ";
      }
      if(!empty($filter_vendor)){
        $sql.=" AND pkd.vendor_id='".$filter_vendor."' ";
      }
      $sql.=" GROUP BY sp.id,sp.tgl_surat,sp.no_suratjalan,sp.date_added,sjp.po_id,vl.name,sp.total,pkg.no_po,g.nama order by sp.tgl_surat DESC ";
      $d=$this->db->query($sql);
      $datainvlokal=$d->rows;
      // ivs import
      $sqlimport="SELECT * FROM invoice_pembelian_import ";
      $sqlimport.=" WHERE status IN(1,2) ";
      if(!empty($tglawal)){
        $sqlimport.=" AND date(tglfaktur) >='".$tglawal."' AND date(tglfaktur) <='".$tglakhir."' ";
      }
      $sqlimport.=" ORDER BY id DESC, tglfaktur DESC, status ASC  ";
      $di=$this->db->query($sqlimport);
      $datainvimport=$di->rows;
      $total=count($datainvlokal) + count($datainvimport);
      $nomor_urut=$start+1;
      $output['recordsTotal']=$output['recordsFiltered']=$total;
      $status=null;
      $ivslokal=array();
      $total=0;
      $cekppn=0;
      foreach($datainvlokal as $c){
        $ul=$this->url->link('pembelian/barangdatangdagang/tampil', 'token=' . $this->session->data['token'].'&id='.$c['id'], 'SSL');
        $urllokal="<a href='".$ul."' class='badge bg-orange' target='_blank'>tampil</a>";
        $cekppn=$this->cekppnproduk($c['po_id']);
        if($cekppn>0){
          $total=($c['total']+($c['total']*0.1));
        }else{
          $total=($c['total']);
        }
        $ivslokal[]=array(
          'tgl'=>date('d/m/Y',strtotime($c['tanggal'])),
          'no_suratjalan'=>$c['no_suratjalan'],
          'quantity'=>$c['quantity'],
          'po_id'=>$c['po_id'],
          'product_name'=>$c['product_name'],
          'vendor'=>$c['name'],
          'keterangan'=>'Pembelian lokal',
          'total'=>$total,
          'no_po'=>$c['no_po'],
          'gudang'=>$c['nama'],
          'action'=>$urllokal,
        );
      }
      $ivsimport=array();
      foreach($datainvimport as $c){
        $uli=$this->url->link('pembelian/invoicepembelianimport/tampil', 'token=' . $this->session->data['token'].'&id='.$c['id'], 'SSL');
        $urlimport="<a href='".$uli."' class='badge bg-orange' target='_blank'>tampil</a>";
        $ivsimport[]=array(
          'tgl'=>date('Y-m-d',strtotime($c['tglfaktur'])),
          'vendor_id'=>$c['vendor_id'],
          'tglfaktur'=>date('d/m/Y',strtotime($c['tglfaktur'])),
          'jatuhtempo'=>date('d/m/Y',strtotime($c['tglfaktur'])),
          'no_faktur'=>$c['no_faktur'],
          'no_dokumen'=>$c['no_dokumen'],
          'gudang'=>$this->getnama($c['gudang_id'],'nama','gudang','gudang_id'),
          'vendor'=>$this->getnamaimport($c['vendor_id'],'name','vendorimport','id'),
          'tagihan'=>'$'.number_format($c['totaltagihan'],2),
          'totalbayar'=>'$'.number_format($c['totalbayar'],2),
          'total'=>($c['totaltagihan']),
          'keterangan'=>'invoice import',
          'no_po'=>null,
          'gudang'=>'Tangerang',
          'action'=>$urlimport,
        );
      }
      $data=array_merge($ivslokal,$ivsimport);
      return $data;
    }
    // baru 8 Juli 2020
    public function getnama($id,$kolom,$table,$kolomwhere){
      $d=$this->db->query("SELECT $kolom FROM $table WHERE $kolomwhere='$id' ");
      return $d->row[$kolom];
    }
    public function getnamaimport($id,$kolom,$table,$kolomwhere){
      $d=$this->db->query("SELECT $kolom FROM $table WHERE $kolomwhere='$id' ");
      return $d->row[$kolom];
    }

    public function cekppnproduk($po_id){
      $d=$this->db->query("SELECT pajak FROM pembelian_kreditdagang WHERE id='$po_id' ");
      return $d->row['pajak'];
    }

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
    $tglawal=$_REQUEST['filter_date_start'];
    $tglakhir=$_REQUEST['filter_date_end'];
    $filter_vendor=$_REQUEST['filter_vendor'];
    // ivs lokal
    $sql="SELECT sp.id,g.nama,sp.total,sp.tgl_surat as tanggal,sp.no_suratjalan,sjp.po_id,pkg.no_po,vl.name FROM suratjalan_pembeliandagang sp join suratjalan_produkdagang sjp on(sjp.id_suratjalan=sp.id) left join invoice_pembelian_productdagang ipp ON(ipp.po_product_id=sjp.pembelian_product_id) left join invoice_pembeliandagang ivp on(ivp.id=ipp.invoice_id) left join pembelian_produk_kreditdagang ppk ON(ppk.pembelian_id=sjp.po_id) JOIN pembelian_kreditdagang pkd ON(pkd.id=ppk.pembelian_id) LEFT JOIN vendorlokal vl ON(vl.id=pkd.vendor_id) ";	
    $sql.=" LEFT JOIN pembelian_kreditdagang pkg ON (pkg.id=sjp.po_id) left join gudang g on(g.gudang_id=pkg.gudang_id) ";
    $sql.=" WHERE ipp.invoice_id IS NULL ";
    if(!empty($tglawal)){
      $sql.=" AND date(sp.tgl_surat) >='".$tglawal."' AND date(sp.tgl_surat) <='".$tglakhir."' ";
    }
    if(!empty($filter_vendor)){
      $sql.=" AND pkd.vendor_id='".$filter_vendor."' ";
    }
		$sql.=" GROUP BY sp.id,sp.tgl_surat,sp.no_suratjalan,sp.date_added,sjp.po_id,vl.name,sp.total,pkg.no_po,g.nama order by sp.tgl_surat DESC ";
    $d=$this->db->query($sql);
    $datainvlokal=$d->rows;
    // ivs import
    $sqlimport="SELECT * FROM invoice_pembelian_import ";
    $sqlimport.=" WHERE status IN(1,2) ";
    if(!empty($tglawal)){
      $sqlimport.=" AND date(tglfaktur) >='".$tglawal."' AND date(tglfaktur) <='".$tglakhir."' ";
    }
    $sqlimport.=" ORDER BY id DESC, tglfaktur DESC, status ASC  ";
    $di=$this->db->query($sqlimport);
    $datainvimport=$di->rows;
    $total=count($datainvlokal) + count($datainvimport);
		$nomor_urut=$start+1;
		$output['recordsTotal']=$output['recordsFiltered']=$total;
    $status=null;
    $ivslokal=array();
    $total=0;
    $cekppn=0;
    foreach($datainvlokal as $c){
      $ul=$this->url->link('pembelian/barangdatangdagang/tampil', 'token=' . $this->session->data['token'].'&id='.$c['id'], 'SSL');
      $urllokal="<a href='".$ul."' class='badge bg-orange' target='_blank'>tampil</a>";
      $cekppn=$this->cekppnproduk($c['po_id']);
      if($cekppn>0){
        $total=$this->currency->format($c['total']+($c['total']*0.1));
      }else{
        $total=$this->currency->format($c['total']);
      }
      $ivslokal[]=array(
        'tgl'=>date('d/m/Y',strtotime($c['tanggal'])),
        'no_suratjalan'=>$c['no_suratjalan'],
        'quantity'=>$c['quantity'],
        'po_id'=>$c['po_id'],
        'product_name'=>$c['product_name'],
        'vendor'=>$c['name'],
        'keterangan'=>'Pembelian lokal',
        'total'=>$total,
        'no_po'=>$c['no_po'],
        'gudang'=>$c['nama'],
        'action'=>$urllokal,
      );
    }
    $ivsimport=array();
    foreach($datainvimport as $c){
      $uli=$this->url->link('pembelian/invoicepembelianimport/tampil', 'token=' . $this->session->data['token'].'&id='.$c['id'], 'SSL');
      $urlimport="<a href='".$uli."' class='badge bg-orange' target='_blank'>tampil</a>";
      $ivsimport[]=array(
        'tgl'=>date('Y-m-d',strtotime($c['tglfaktur'])),
        'vendor_id'=>$c['vendor_id'],
        'tglfaktur'=>date('d/m/Y',strtotime($c['tglfaktur'])),
        'jatuhtempo'=>date('d/m/Y',strtotime($c['tglfaktur'])),
        'no_faktur'=>$c['no_faktur'],
        'no_dokumen'=>$c['no_dokumen'],
        'gudang'=>$this->getnama($c['gudang_id'],'nama','gudang','gudang_id'),
        'vendor'=>$this->getnamaimport($c['vendor_id'],'name','vendorimport','id'),
        'tagihan'=>'$'.number_format($c['totaltagihan'],2),
        'totalbayar'=>'$'.number_format($c['totalbayar'],2),
        'total'=>'$'.number_format($c['totaltagihan'],2),
        'keterangan'=>'invoice import',
        'no_po'=>null,
        'gudang'=>'Tangerang',
        'action'=>$urlimport,
      );
    }
    $data=array_merge($ivslokal,$ivsimport);
    $tglawal=$_REQUEST['filter_date_start'];
    $tglakhir=$_REQUEST['filter_date_end'];
    $nofaktur=$_REQUEST['filter_no_faktur'];
    $vendor=$_REQUEST['filter_vendor'];
		foreach ($data as $c) {
        // if(!empty($tglawal) & !empty($tglakhir) ){
        //   if($c['tgl']>=$tglawal & $c['tgl']<=$tglakhir){
        //     $output['data'][]=array( 
        //       $nomor_urut,
        //       $c['tgl'],
        //       $c['gudang'],
        //       $c['vendor'],
        //       $c['no_suratjalan'],
        //       $c['no_po'],              
        //       $c['total'],
        //       $c['keterangan'],
        //       $c['action']
        //     );
        //     $nomor_urut++;
        //   }
        // }else{
        //   $output['data'][]=array( 
        //     $nomor_urut,
        //     $c['tgl'],
        //     $c['gudang'],
        //     $c['vendor'],
        //     $c['no_suratjalan'],
        //     $c['no_po'],
        //     $c['total'],
        //     $c['keterangan'],
        //     $c['action']
        //   );
        //   $nomor_urut++;
        // }
        $output['data'][]=array( 
          $nomor_urut,
          $c['tgl'],
          $c['gudang'],
          $c['vendor'],
          $c['no_suratjalan'],
          $c['no_po'],              
          $c['total'],
          $c['keterangan'],
          $c['action']
        );
        $nomor_urut++;
		}
		return $output;
  }

  // end baru


}
?>
