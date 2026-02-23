<?php
class ModelPembelianPembeliantunai extends Model {
  public function addPembelian($data){
    /*
    jenis barang
    1. bahan baku
    2. produk dagang
    3. ATK
    4. Perlengkapan/aktiva tetap
    5. Tabung MP
    */
    $p=array(
      'no_nota' => $data['no_nota'],
      'no_faktur' => $data['no_faktur'],
      'vendor_id' => $data['vendor_id'],
      'surat_id'  => $data['surat_id'],
      'sub_total' => $data['sub_total'],
      'diskon'  => $data['diskon'],
      'pajak' => $data['pajak'],
      'total_pembelian' => $data['total_pembelian'],
      'pembayaran'  => $data['pembayaran'],
      'saldo' => $data['saldo'],
      'date_added'  => date('Y-m-d H:i:s',time()),
      'date_modified'  => date('Y-m-d H:i:s',time()),
      'hapus' => 0,
      'status'  => 1,
      'bank_id' => 1,
      'jenis_barang'  => $data['jenis_barang']
    );

    $this->db->insert('pembelian_tunai',$p);
    $id=$this->db->getLastId();
    $data['id'] = $id;
    $data['status']=1;
    $this->addProduct($data);

    if($data['jenis_barang'] == 1){
      $ref_akun='11.05.01';
    }
    if($data['jenis_barang'] == 2){
      $ref_akun='11.05.02';
    }
    if($data['jenis_barang'] == 3){
      $ref_akun='11.05.03';
    }

    $this->load->model('keuangan/bank');
    $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> 1));
    $saldo=$b['saldo'] - $data['total_pembelian'];
    $this->model_keuangan_bank->updateBank(array('saldo'  => $saldo),array('id'=> 1));

    $aruskas=array(
      'date_added'  => $p['date_added'],
      'bank_id' => 1,
      'saldomasuk'  => 0,
      'saldokeluar' => $data['total_pembelian'],
      'saldoawal' => $b['saldo'],
      'saldoakhir'  => $saldo,
      'ref' => $id,
      'keterangan'  => 'Pembelian Tunai',
      'type'  => 1,
      'ref_akun'  => $ref_akun
    );

    $this->model_keuangan_bank->addAruskas($aruskas);

    $this->load->model('keuangan/jurnal');
    $this->load->model('keuangan/pajak');
    $detail=array();
    //debet
    $detail[]=array(
      'ref_akun'  =>$ref_akun,
      'debet' => $data['sub_total'],
      'kredit'  => 0,
      'urutan'  =>1,
      'keterangan'  => 'Persediaan'
    );
    if($data['pajak'] > 0){
      $detail[]=array(
        'ref_akun'  =>'11.08.05',
        'debet' => $data['pajak'],
        'kredit'  => 0,
        'urutan'  =>2,
        'keterangan'  => 'PPN Masukan'
      );

      $pajak=array(
        'ref' => $id,
        'jumlah'  => $data['pajak'],
        'akun' => '11.08.05',
        'jenis' => 1
      );
      $this->model_keuangan_pajak->addPajak($pajak);
    }
    $detail[]=array(
      'ref_akun'  =>'11.01.01',
      'debet' => 0,
      'kredit'  => $data['total_pembelian'],
      'urutan'  =>3,
      'keterangan'  => 'Kas'
    );
    if($data['diskon'] > 0){
      $detail[]=array(
        'ref_akun'  =>'50.03.00',
        'debet' => 0,
        'kredit'  => $data['diskon'],
        'urutan'  =>4,
        'keterangan'  => 'Potongan Pembelian'
      );
    }
    $jurnal=array(
      'tanggal' => date('Y-m-d'),
      'keterangan' => 'Pembelian Tunai',
      'ref' => $id,
      'type' => 1,
      'details' => $detail
    );
    $this->model_keuangan_jurnal->addJurnalUmum($jurnal);
  }

  public function addProduct($data){
    foreach($data['products'] as $d){
      if(!empty($d['product_id'])){
        $this->load->model('gudang/kartustok');
        if($data['status'] == 1){
          if($data['jenis_barang'] == 1){
            $this->load->model('catalog/bahanbaku');

            $curqty=$this->model_catalog_bahanbaku->getProduct($d['product_id']);
            $update=$this->model_catalog_bahanbaku->updateQty($d['product_id'],$d['quantity'],1);

            $netcost=(($curqty['quantity']*$curqty['hargabeli'])+($d['quantity'] * $d['harga']))/$curqty['quantity']+$d['quantity'];

            $this->model_catalog_bahanbaku->updateNetCost($d['product_id'],$netcost);


            $kartustok=array(
              'product_id'	=> $d['product_id'],
              'product_name'	=> $d['name'],
              'tgl'	=> date('Y-m-d h:i:s',time()),
              'stokkeluar'	=> 0,
              'stokmasuk'	=> $d['quantity'],
              'ket'	=> 'Pembelian bahan baku',
              'saldo'	=> $update,
              'quantityawal'	=> $curqty['quantity'],
              'invoice'	=> $data['id'],
              //'gudang_id'	=> $data['gudang_id'],
              'type'	=> 1
            );

            $this->model_gudang_kartustok->addKartuStokGlobal('kartustok_bahanbaku',$kartustok);
          }

          if($data['jenis_barang'] == 2){
            $this->load->model('catalog/product');

            $curqty=$this->model_catalog_product->getProduct($d['product_id']);
            $update=$this->model_catalog_product->updateQty($d['product_id'],$d['quantity'],1);

            $netcost=(($curqty['quantity']*$curqty['net_cost'])+($d['quantity'] * $d['harga']))/$curqty['quantity']+$d['quantity'];

            $this->model_catalog_product->updateNetCost($d['product_id'],$netcost);

            $kartustok=array(
              'product_id'	=> $d['product_id'],
              'product_name'	=> $d['name'],
              'tgl'	=> date('Y-m-d h:i:s',time()),
              'stokkeluar'	=> 0,
              'stokmasuk'	=> $d['quantity'],
              'ket'	=> 'Pembelian bahan baku',
              'saldo'	=> $update,
              'quantityawal'	=> $curqty['quantity'],
              'invoice'	=> $data['id'],
              //'gudang_id'	=> $data['gudang_id'],
              'type'	=> 1
            );

            $this->model_gudang_kartustok->addKartuStok($kartustok);
          }

          if($data['jenis_barang'] == 3){
            $this->load->model('catalog/atk');

            $curqty=$this->model_catalog_atk->getAtk($d['product_id']);
            $update=$this->model_catalog_atk->updateQty($d['product_id'],$d['quantity'],1);
            $netcost=(($curqty['qty']*$curqty['net_cost'])+($d['quantity'] * $d['harga']))/$curqty['qty']+$d['quantity'];

            $this->model_catalog_atk->updateNetCost($d['product_id'],$netcost);

            $kartustok=array(
              'product_id'	=> $d['product_id'],
              'product_name'	=> $d['name'],
              'tgl'	=> date('Y-m-d h:i:s',time()),
              'stokkeluar'	=> 0,
              'stokmasuk'	=> $d['quantity'],
              'ket'	=> 'Pembelian bahan baku',
              'saldo'	=> $update,
              'quantityawal'	=> $curqty['qty'],
              'invoice'	=> $data['id'],
              //'gudang_id'	=> $data['gudang_id'],
              'type'	=> 1
            );

            $this->model_gudang_kartustok->addKartuStokGlobal('kartustok_atk',$kartustok);
          }

        }



      $prod=array(
        'pembelian_id'  => $data['id'],
        'product_id'  => $d['product_id'],
        'product_name'  => $this->db->escape($d['name']),
        'quantity'  => $d['quantity'],
        'status'  => 1,
        'date_added'  => date('Y-m-d H:i:s',time()),
        'date_modified'  => date('Y-m-d H:i:s',time()),
        'quantity_terima' => $d['quantity'],
        'kategori'  => 0,
        'ukuran_tabung' => 0,
        'harga' => $d['harga'],
        'hapus' => 0
      );
      $this->db->insert('pembelian_produk',$prod);


    }
    }
  }
  public function updatePermintaan($data,$where){
    if($data['status'] == 3){
      $pb=$this->getPermintaanPembelian(array(),array(),$where);

      $this->load->model('keuangan/bank');
      $b=$this->model_keuangan_bank->getBank(array(),array(),array('id'=> $pb['bank_id']));
      $saldo=$b['saldo'] + $pb['jumlah'];
      $this->model_keuangan_bank->updateBank(array('saldo'  => $saldo),array('id'=> $pb['bank_id']));

      $this->model_keuangan_bank->updateAruskas(array('hapus' => 1),array('type'  => 1,'ref'  => $pb['id']));

      $this->load->model('keuangan/jurnal');
      $this->model_keuangan_jurnal->updateJurnalumum(array('hapus' => 1),array('type'  => 1,'ref'  => $pb['id']));

    }
    $this->db->update('pembelian_tunai',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoin('pembelian_tunai',$column,$join,$where,$order,$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('pembelian_tunai',$where);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('pembelian_tunai',$column,$join,$where,array());
  }

  public function getPermintaanPembelianProduct($where){
    return $this->db->all('pembelian_produk',$where,array(),0,null);
  }
}
?>
