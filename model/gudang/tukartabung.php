<?php
class ModelGudangTukartabung extends Model {
  public function addTukartabung($data){
    $this->load->model('gudang/product');
    $tabungasal=$this->model_gudang_product->getProduct($data['tabung_a'],$data['gudang_id']);
    $data['netcost_a']=!empty($tabungasal['net_cost'])?$tabungasal['net_cost']:0;

    if($data['kran_b'] > 0){
      $kranasal=$this->model_gudang_product->getProduct($data['kran_b'],$data['gudang_id']);
      $data['netcost_b']=$kranasal['net_cost'];
    }else{
      $data['netcost_b']=0;
    }
    if($data['tambahan_harga'] > $data['netcost_b']){
      $data['netcost_tabungb']=$data['netcost_a']+$data['netcost_b'];
      $data['netcost_lepasan']=0;
    }else{
      $data['netcost_tabungb']=$data['netcost_a']+$data['tambahan_harga'];
      $data['netcost_lepasan']=$data['netcost_b']-$data['tambahan_harga'];

      if($data['netcost_lepasan'] < 0){
        $data['netcost_lepasan']=0;
      }
    }

    //netcost asal --> ((netcost saat ini * totalsaat ini) - (netcost tabung b * qty))/totalsaatini-qty
    /*necost awal 200 qty 100
      netcost b 100 qty 50 

      netcost akhir 166.666666667

     

    */

    $kranlepasan=$this->model_gudang_product->getProduct($data['kran_lepasan'],$data['gudang_id']);
    if(empty($kranlepasan)){
      $kl=array(
        'gudang_id' => $data['gudang_id'],
        'product_id'  => $data['kran_lepasan'],
        'qty' => 0,
        'net_cost'  => 0,
        'date_added'  =>date('Y-m-d H:i:s',time())
      );
      $this->model_gudang_product->addStokAwal($kl);
    }

    $tabungb=$this->model_gudang_product->getProduct($data['tabung_b'],$data['gudang_id']);
    if(empty($tabungb)){
      $tb=array(
        'gudang_id' => $data['gudang_id'],
        'product_id'  => $data['tabung_b'],
        'qty' => 0,
        'net_cost'  => 0,
        'date_added'  =>date('Y-m-d H:i:s',time())
      );
      $this->model_gudang_product->addStokAwal($tb);
    }

    $p=array(
      'tgl_tukar' => $data['tgl_tukar'],
      'user_tukar'  => $this->user->getId(),
      'tabung_a'  => $data['tabung_a'],
      'netcost_a' => $data['netcost_a'],
      'kran_b'  => $data['kran_b'],
      'netcost_b' => $data['netcost_b'],
      'kran_lepasan'  => $data['kran_lepasan'],
      'netcost_lepasan' => $data['netcost_lepasan'],
      'tabung_b'  => $data['tabung_b'],
      'netcost_tabungb' => $data['netcost_tabungb'],
      'tambahan_harga'  => $data['tambahan_harga'],
      'quantity'  => $data['quantity'],
      'status'  => 1,
      'hapus' => 0,
      'gudang_id' => $data['gudang_id'],
      'no_dokumen' =>'-',
      'keterangan'  => $data['keterangan']
    );
    $this->db->insert('tukartabung',$p);
    $id=$this->db->getLastId();

    $no_tukartabung='TT-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;
    $no_dokumen='TK-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;

    $this->db->update('tukartabung',array('no_tukartabung' => $no_tukartabung,'no_dokumen'=>$no_dokumen),array('id'  => $id));

    $dokumen=array(
      'nama_table'  => 'tukartabung',
      'jurnal_id' => 0,
      'hapus' => 0,
      'id_transaksi'  => $id,
      'datakas' => 1,
      'id_mutasi' => 0,
      'jenis_transaksi' => 'Tukar Kran',
      'no_dokumen'  => $no_dokumen,
      'date_added'  => date('Y-m-d H:i:s')

    );
    $this->db->insert('no_dokumen',$dokumen);
   

    return $no_tukartabung;
  }

  public function prosesTukarTabung($id,$tgl_proses){
    $detail=$this->getTukarTabung(array(),array(),array('id'=>$id));
    if($detail['status'] == 1){
      $this->load->model('gudang/product');
      $this->load->model('gudang/kartustok');

      //tabung_a

      $curqtytabunga=$this->model_gudang_product->getProduct($detail['tabung_a'],$detail['gudang_id']);
      $updatetabunga=$this->model_gudang_product->updateQty($detail['tabung_a'],$detail['gudang_id'],$detail['quantity'],2);

      $kartustok=array(
        'product_id'  => $detail['tabung_a'],
        'product_name'  => "",
        'tgl' => $tgl_proses,
        'stokkeluar'  => $detail['quantity'],
        'stokmasuk' => 0,
        'ket' => 'Proses Tukar Kran Tabung',
        'saldo' => $updatetabunga,
        'quantityawal'  => $curqtytabunga['quantity'],
        'invoice' =>$id,
        'gudang_id' => $detail['gudang_id'],
        'type'  => 11000,
        'no_dokumen'  => $detail['no_dokumen'],
        'urlref'  => 'gudang/tukartabung',
        'idref' => $id
      );

      $this->model_gudang_kartustok->addKartuStok($kartustok);

      //kranb
      if($detail['kran_b'] > 0){
      $curqtykranb=$this->model_gudang_product->getProduct($detail['kran_b'],$detail['gudang_id']);
      $updatekranb=$this->model_gudang_product->updateQty($detail['kran_b'],$detail['gudang_id'],$detail['quantity'],2);

      $kartustok=array(
        'product_id'  => $detail['kran_b'],
        'product_name'  => "",
        'tgl' => $tgl_proses,
        'stokkeluar'  => $detail['quantity'],
        'stokmasuk' => 0,
        'ket' => 'Proses Tukar Kran Tabung',
        'saldo' => $updatekranb,
        'quantityawal'  => $curqtykranb['quantity'],
        'invoice' =>$id,
        'gudang_id' => $detail['gudang_id'],
        'type'  => 11000,
        'no_dokumen'  => $detail['no_dokumen'],
        'urlref'  => 'gudang/tukartabung',
        'idref' => $id
      );

      $this->model_gudang_kartustok->addKartuStok($kartustok);
      }
      //tabunghasil

      $curqtytabungb=$this->model_gudang_product->getProduct($detail['tabung_b'],$detail['gudang_id']);
      $updatetabungb=$this->model_gudang_product->updateQty($detail['tabung_b'],$detail['gudang_id'],$detail['quantity'],1);

      $kartustok=array(
        'product_id'  => $detail['tabung_b'],
        'product_name'  => "",
        'tgl' => $tgl_proses,
        'stokmasuk' => $detail['quantity'],
        'stokkeluar'  => 0,
        'ket' => 'Proses Tukar Kran Tabung',
        'saldo' => $updatetabungb,
        'quantityawal'  => $curqtytabungb['quantity'],
        'invoice' =>$id,
        'gudang_id' => $detail['gudang_id'],
        'type'  => 11000,
        'no_dokumen'  => $detail['no_dokumen'],
        'urlref'  => 'gudang/tukartabung',
        'idref' => $id
      );

      $this->model_gudang_kartustok->addKartuStok($kartustok);

      if($curqtytabungb['quantity'] > 0){

          if($curqtytabungb['net_cost'] > 0){
            $netcost=(($detail['quantity'] * $detail['netcost_tabungb'])+($curqtytabungb['quantity']*$curqtytabungb['net_cost']))/($detail['quantity']+$curqtytabungb['quantity']);

          }else{
            $netcost=(($detail['quantity'] * $detail['netcost_tabungb']))/($detail['quantity']);
          }
        //}
      }else{
        $netcost=(($detail['quantity'] * $detail['netcost_tabungb']))/($detail['quantity']);
      }
        $this->model_gudang_product->updateNetCost($detail['tabung_b'],$detail['gudang_id'],$netcost);


        //kranlepasan

        $curqtykranlepasan=$this->model_gudang_product->getProduct($detail['kran_lepasan'],$detail['gudang_id']);
        $updatekranlepasan=$this->model_gudang_product->updateQty($detail['kran_lepasan'],$detail['gudang_id'],$detail['quantity'],1);

        $kartustok=array(
          'product_id'  => $detail['kran_lepasan'],
          'product_name'  => "",
          'tgl' => $tgl_proses,
          'stokmasuk' => $detail['quantity'],
          'stokkeluar'  => 0,
          'ket' => 'Proses Tukar Kran Tabung',
          'saldo' => $updatekranlepasan,
          'quantityawal'  => $curqtykranlepasan['quantity'],
          'invoice' =>$id,
          'gudang_id' => $detail['gudang_id'],
          'type'  => 11000,
          'no_dokumen'  => $detail['no_dokumen'],
          'urlref'  => 'gudang/tukartabung',
          'idref' => $id
        );

        $this->model_gudang_kartustok->addKartuStok($kartustok);

        if($curqtykranlepasan['quantity'] > 0){

            if($curqtykranlepasan['net_cost'] > 0){
              $netcostlepasan=(($detail['quantity'] * $detail['netcost_lepasan'])+($curqtykranlepasan['quantity']*$curqtykranlepasan['net_cost']))/($detail['quantity']+$curqtykranlepasan['quantity']);

            }else{
              $netcostlepasan=(($detail['quantity'] * $detail['netcost_lepasan']))/($detail['quantity']);
            }
          //}
        }else{
          $netcostlepasan=(($detail['quantity'] * $detail['netcost_lepasan']))/($detail['quantity']);
        }
          $this->model_gudang_product->updateNetCost($detail['kran_lepasan'],$detail['gudang_id'],$netcostlepasan);

          $this->db->update('tukartabung',array('status'=>2,'user_proses'=> $this->user->getId(),'tgl_proses'=>$tgl_proses),array('id'=>$id));
    }
  }

  public function batalkanTukarTabung($id){
    $detail=$this->getTukarTabung(array(),array(),array('id'=>$id));

    
    if($detail['status'] == 2){
      $this->load->model('gudang/product');
      $this->load->model('gudang/kartustok');

      //tabung_a

      $curqtytabunga=$this->model_gudang_product->getProduct($detail['tabung_a'],$detail['gudang_id']);
      $updatetabunga=$this->model_gudang_product->updateQty($detail['tabung_a'],$detail['gudang_id'],$detail['quantity'],1);

      $kartustok=array(
        'product_id'  => $detail['tabung_a'],
        'product_name'  => "",
        'tgl' => date('Y-m-d H:i:s'),
        'stokmasuk'  => $detail['quantity'],
        'stokkeluar' => 0,
        'ket' => 'Pembatalan Proses Tukar Kran Tabung',
        'saldo' => $updatetabunga,
        'quantityawal'  => $curqtytabunga['quantity'],
        'invoice' =>$id,
        'gudang_id' => $detail['gudang_id'],
        'type'  => 11001,
        'no_dokumen'  => $detail['no_dokumen'],
        'urlref'  => 'gudang/tukartabung',
        'idref' => $id
      );

      $this->model_gudang_kartustok->addKartuStok($kartustok);

      //kranb
      if($detail['kran_b'] > 0){
      $curqtykranb=$this->model_gudang_product->getProduct($detail['kran_b'],$detail['gudang_id']);
      $updatekranb=$this->model_gudang_product->updateQty($detail['kran_b'],$detail['gudang_id'],$detail['quantity'],1);

      $kartustok=array(
        'product_id'  => $detail['kran_b'],
        'product_name'  => "",
        'tgl' => date('Y-m-d H:i:s'),
        'stokmasuk'  => $detail['quantity'],
        'stokkeluar' => 0,
        'ket' => 'Pembatalan Proses Tukar Kran Tabung',
        'saldo' => $updatekranb,
        'quantityawal'  => $curqtykranb['quantity'],
        'invoice' =>$id,
        'gudang_id' => $detail['gudang_id'],
        'type'  => 11001,
        'no_dokumen'  => $detail['no_dokumen'],
        'urlref'  => 'gudang/tukartabung',
        'idref' => $id
      );

      $this->model_gudang_kartustok->addKartuStok($kartustok);
      }
      //tabunghasil

      $curqtytabungb=$this->model_gudang_product->getProduct($detail['tabung_b'],$detail['gudang_id']);
      $updatetabungb=$this->model_gudang_product->updateQty($detail['tabung_b'],$detail['gudang_id'],$detail['quantity'],2);

      $kartustok=array(
        'product_id'  => $detail['tabung_b'],
        'product_name'  => "",
        'tgl' => date('Y-m-d H:i:s'),
        'stokkeluar' => $detail['quantity'],
        'stokmasuk'  => 0,
        'ket' => 'Pembatalan Proses Tukar Kran Tabung',
        'saldo' => $updatetabungb,
        'quantityawal'  => $curqtytabungb['quantity'],
        'invoice' =>$id,
        'gudang_id' => $detail['gudang_id'],
        'type'  => 11001,
        'no_dokumen'  => $detail['no_dokumen'],
        'urlref'  => 'gudang/tukartabung',
        'idref' => $id
      );

      $this->model_gudang_kartustok->addKartuStok($kartustok);

      /*if($curqtytabungb['quantity'] > 0){

          if($curqtytabungb['net_cost'] > 0){
            $netcost=(($detail['quantity'] * $detail['netcost_tabungb'])+($curqtytabungb['quantity']*$curqtytabungb['net_cost']))/($detail['quantity']+$curqtytabungb['quantity']);

          }else{
            $netcost=(($detail['quantity'] * $detail['netcost_tabungb']))/($detail['quantity']);
          }
        //}
      }else{
        $netcost=(($detail['quantity'] * $detail['netcost_tabungb']))/($detail['quantity']);
      }*/
      if(($curqtytabungb['quantity']-$detail['quantity']) > 0){
        $netcost=(($curqtytabungb['net_cost']*$curqtytabungb['quantity'])-($detail['netcost_b']*$detail['quantity']))/($curqtytabungb['quantity']-$detail['quantity']);
      }else{
        $netcost=0;
      }
      $this->model_gudang_product->updateNetCost($detail['tabung_b'],$detail['gudang_id'],$netcost);

      //netcost asal --> ((netcost saat ini * totalsaat ini) - (netcost tabung b * qty))/totalsaatini-qty
    /*necost awal 200 qty 100
      netcost b 100 qty 50 

      netcost akhir 166.666666667

     

    */
        //kranlepasan

        $curqtykranlepasan=$this->model_gudang_product->getProduct($detail['kran_lepasan'],$detail['gudang_id']);
        $updatekranlepasan=$this->model_gudang_product->updateQty($detail['kran_lepasan'],$detail['gudang_id'],$detail['quantity'],2);

        $kartustok=array(
          'product_id'  => $detail['kran_lepasan'],
          'product_name'  => "",
          'tgl' => date('Y-m-d H:i:s'),
          'stokkeluar' => $detail['quantity'],
          'stokmasuk'  => 0,
          'ket' => 'Pembatalan Proses Tukar Kran Tabung',
          'saldo' => $updatekranlepasan,
          'quantityawal'  => $curqtykranlepasan['quantity'],
          'invoice' =>$id,
          'gudang_id' => $detail['gudang_id'],
          'type'  => 11001,
          'no_dokumen'  => $detail['no_dokumen'],
          'urlref'  => 'gudang/tukartabung',
          'idref' => $id
        );

        $this->model_gudang_kartustok->addKartuStok($kartustok);

        /*if($curqtykranlepasan['quantity'] > 0){

            if($curqtykranlepasan['net_cost'] > 0){
              $netcostlepasan=(($detail['quantity'] * $detail['netcost_lepasan'])+($curqtykranlepasan['quantity']*$curqtykranlepasan['net_cost']))/($detail['quantity']+$curqtykranlepasan['quantity']);

            }else{
              $netcostlepasan=(($detail['quantity'] * $detail['netcost_lepasan']))/($detail['quantity']);
            }
          //}
        }else{
          $netcostlepasan=(($detail['quantity'] * $detail['netcost_lepasan']))/($detail['quantity']);
        }*/
        if(($curqtykranlepasan['quantity']-$detail['quantity']) > 0){
          $netcostlepasan=(($curqtykranlepasan['net_cost']*$curqtykranlepasan['quantity'])-($detail['netcost_lepasan']*$detail['quantity']))/($curqtykranlepasan['quantity']-$detail['quantity']);
        } else{
          $netcostlepasan=0;
        } 
        $this->model_gudang_product->updateNetCost($detail['kran_lepasan'],$detail['gudang_id'],$netcostlepasan);

          $this->db->update('tukartabung',array('status'=>3,'user_proses'=> $this->user->getId()),array('id'=>$id));
    }
  }

  public function updatePermintaan($data,$where){
    $this->db->update('tukartabung',$data,$where);
  }
  public function getTukarTabungs($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('tukartabung',$column,$join,$leftjoin,$where,array('id'=> 'DESC'),$limit,$offset);
  }

  public function totalTukarTabungs($where,$join=array()){
    return $this->db->countAll('tukartabung',$where,$join);
  }

  public function getTukarTabung($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('tukartabung',$column,$join,$where,array());
  }


}
?>
