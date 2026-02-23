<?php
class ModelGudangPermohonanstokopname extends Model {
  public function addPermintaanPembelian($data){
    /*
    status
    1. menunggu persetujuan
    2. selesai diproses
    3. dibatalkan/ditolak
    */
    $p=array(
      'tanggal' => empty($data['tanggal'])?date('Y-m-d'):$data['tanggal'],
      'date_added'  => date('Y-m-d H:i:s'),
      'status'  => 1,
      'gudang_id' => $data['gudang_id'],
      'user_buat' => $this->user->getId(),
      'Keterangan'  => $this->db->escape($data['keterangan']),
      'hapus' => 0
    );
    $this->db->insert('permohonan_stokopname',$p);
    $id=$this->db->getLastId();
    $no_surat='PSO-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id;
    $this->db->update('permohonan_stokopname',array('no_surat' => $no_surat,'no_suratpersetujuan' => $no_surat),array('id'  => $id));
    
    /*$dokumen=array(
      'nama_table'  => 'perakitan',
      'jurnal_id' => 0,
      'hapus' => 0,
      'id_transaksi'  => $id,
      'datakas' => 1,
      'id_mutasi' => 0,
      'jenis_transaksi' => 'Perakitan',
      'no_dokumen'  => $no_dokumen,
      'date_added'  => date('Y-m-d H:i:s')
  
    );
    $this->db->insert('no_dokumen',$dokumen);
    */
    $data['id'] = $id;
    $this->addPermintaanProduct($data);
    return $id;
  }

  public function addPermintaanProduct($data){
    $this->load->model('gudang/product');
    foreach($data['product'] as $d){
      $pd=$this->model_gudang_product->getProduct($d['product_id'],$data['gudang_id']);
      $prod=array(
        'permohonan_id'  => $data['id'],
        'product_id'  => $d['product_id'],
        'qtytersedia' => empty($d['qtytersedia'])?0:$d['qtytersedia'],
        'qtytercatat' => $d['qtytercatat'],
        'keterangan'  => $this->db->escape($d['keterangan']),
        'gudang_id' => $data['gudang_id'],
        'nilaibarang' =>  empty($pd['net_cost'])?0:$pd['net_cost'],
        'qtyrusak'  =>  empty($d['qtyrusak'])?0:$d['qtyrusak'],
        'qtyhilang'  =>  empty($d['qtyhilang'])?0:$d['qtyhilang'],
        'hapus' => 0
      );
      $this->db->insert('permohonan_stokopname_product',$prod);

    }
  }
  public function updatePermintaan($data,$where){
    $this->db->update('permohonan_stokopname',$data,$where);
  }
  public function getPermintaanPembelians($column=array(),$join=array(),$leftjoin=array(),$where=array(),$order=array(),$limit=0,$offset=null){
    return $this->db->alljoins('permohonan_stokopname',$column,$join,$leftjoin,$where,array('id'=> 'DESC'),$limit,$offset);
  }

  public function totalPermintaans($where){
		return $this->db->countAll('permohonan_stokopname',$where);
	}

  public function getPermintaanPembelian($column=array(),$join=array(),$where=array()){
    return $this->db->firstdetail('permohonan_stokopname',$column,$join,$where,array());
  }

  public function getPermintaanPembelianProduct($where){
    //$sql="select  from ".DB_PREFIX."stokopname_gudang pc LEFT JOIN ".DB_PREFIX."product p ON(pc.product_id=p.product_id)  LEFT JOIN ".DB_PREFIX."gudang g ON(pc.gudang_id=g.gudang_id) ";
    $column=array('permohonan_stokopname_product.product_id','name','keterangan','qtytercatat','qtytersedia','qtyrusak','qtyhilang','nilaibarang');
    $join=array();
	   $leftjoin=array();
		  $leftjoin[]=array(
			'tablename'	=> 'product',
			'firsttable'	=>'permohonan_stokopname_product.product_id',
			'secondtable'	=> 'product.product_id'
		);
    return $this->db->alljoins('permohonan_stokopname_product',$column,$join,$leftjoin,$where,array(),0,null);
  }

  public function setujuiStokopname($data,$id){
    $sop=$this->getPermintaanPembelian(array(),array(),array('id'=>$id));
    	$this->load->model('keuangan/jurnal');
      $this->load->model('gudang/product');
      $this->load->model('catalog/product');
        $this->load->model('gudang/kartustok');
    if($sop['status'] == 1){
      $data['gudang_id']=$sop['gudang_id'];
      if($data['status'] == 2){
        $products=$this->getPermintaanPembelianProduct(array('permohonan_id'=>$id));

        $nilaihilang=0;
        $nilairusak=0;
        $nilaikelebihan=0;
        foreach($products as $p){
          $prod=$this->model_catalog_product->getProduct($p['product_id']);
  				$prodg=$this->model_gudang_product->getProductGudangT($p['product_id'],$sop['gudang_id']);

  				//selisih
  				$qtytersimpan=$p['qtytercatat'] - ($p['qtyrusak']+$p['qtyhilang']);

          if($qtytersimpan != $p['qtytersedia']){
  					/*if($qtytersimpan < $p['qtytersedia']){
  						$selisih=$p['qtytersedia'] - $qtytersimpan;
  						$stokmasuk=$selisih;
  						$stokkeluar=($p['qtyrusak']+$p['qtyhilang']);

  						$gupdate=$this->model_gudang_product->updateQty($p['product_id'],$sop['gudang_id'],$stokkeluar,2);
  						$gupdate=$this->model_gudang_product->updateQty($p['product_id'],$data['gudang_id'],$selisih,1);
  						//$gupdate=$this->updateQty($data['product_id'],$pro['gudang_id'],$selisih,1);
  					}

  					if($qtytersimpan > $p['qtytersedia']){
  						$selisih=$qtytersimpan - $p['qtytersedia'];
  						$stokmasuk=0;
  						$stokkeluar=$selisih+($p['qtyrusak']+$p['qtyhilang']);

  						$gupdate=$this->model_gudang_product->updateQty($p['product_id'],$data['gudang_id'],$stokkeluar,2);
  					}*/
            if($p['qtytercatat'] > $p['qtytersedia']){
              $stokmasuk=0;
              $stokkeluar=$p['qtytercatat'] - $p['qtytersedia'];
              $gupdate=$this->model_gudang_product->updateQty($p['product_id'],$sop['gudang_id'],$stokkeluar,2);
            }else{
              $stokkeluar=0;
              $stokmasuk=$p['qtytersedia'] - $p['qtytercatat'];
              $gupdate=$this->model_gudang_product->updateQty($p['product_id'],$sop['gudang_id'],$stokmasuk,1);
            }
  				}else{
  					$selisih=($p['qtyrusak']+$p['qtyhilang']);
  					$stokmasuk=0;
  					$stokkeluar=$selisih;
  					$gupdate=$this->model_gudang_product->updateQty($p['product_id'],$data['gudang_id'],$stokkeluar,2);
  				}

          $nilaihilang +=$p['qtyhilang'] * $p['nilaibarang'];
          $nilairusak +=$p['qtyrusak'] * $p['nilaibarang'];
          $nilaikelebihan += $stokmasuk * $p['nilaibarang'];

          $curprodg=$this->model_gudang_product->getProductGudangT($p['product_id'],$data['gudang_id']);
  				$gkartustok=array(
  						'product_id'	=> $p['product_id'],
  						'product_name'	=> $this->db->escape($prod['name']),
  						'tgl'	=> date('Y-m-d H:i:s'),
  						'stokmasuk'	=> $stokmasuk,
  						'stokkeluar'	=> $stokkeluar,
  						'ket'	=> 'Pemrosesan Stok opname dengan keterangan '.$sop['keterangan'].' oleh '.$this->user->getName(),
  						'saldo'	=> $curprodg['quantity'],
  						'quantityawal'	=> $prodg['quantity'],
  						'invoice'	=> $sop['id'],
  						'gudang_id'	=> $data['gudang_id'],
              'type'	=> 12,
              'no_dokumen'  => $sop['no_surat'],
              'urlref'  => 'gudang/permohonanstokopname',
              'idref' => $sop['id']
  					);
  				$this->model_gudang_kartustok->addKartustok($gkartustok);

          if($p['qtyhilang'] > 0){
            $hilang=array(
              'product_id'  => $p['product_id'],
              'qty' => $p['qtyhilang'],
              'date_added'  => $data['tgl_diproses'],
              'gudang_id' => $sop['gudang_id'],
              'nilaibarang' => $p['nilaibarang']
            );

            $this->db->insert('product_hilang_gudang',$hilang);
          }

          if($p['qtyrusak'] > 0){
            $hilang=array(
              'product_id'  => $p['product_id'],
              'qty' => $p['qtyrusak'],
              'date_added'  => $data['tgl_diproses'],
              'gudang_id' => $sop['gudang_id'],
              'nilaibarang' => $p['nilaibarang']
            );

            $this->db->insert('product_cacat_gudang',$hilang);
          }

        }

        if($data['catat_jurnal']){
          $kelebihanstok=$this->config->get('config_kelebihanstok');
          $jqtyhilang=$this->config->get('config_qtyhilang');
          $jqtyrusak=$this->config->get('config_qtyrusak');

          if($kelebihanstok >= 1 & $jqtyhilang >=1 & $jqtyrusak >= 1){
            $details=array();
            if($nilaihilang > 0){

			        $details[]=array(
			          'ref_akun'  => $jqtyhilang,
			          'keterangan'  => 'Biaya Kehilangan Barang',
			          'debet' => $nilaihilang,
			          'kredit'  => 0,
			          'urutan'  => 1,
			          'hapus' => 0
			        );
						}
						if($nilairusak > 0){
			        $details[]=array(
			          'ref_akun'  => $jqtyrusak,
			          'keterangan'  => 'Persediaan Barang Rusak',
			          'debet' => $nilairusak,
			          'kredit'  => 0,
			          'urutan'  => 2,
			          'hapus' => 0
			        );
						}
            if($nilaikelebihan > ($nilaihilang + $nilairusak)){
              $details[]=array(
								'ref_akun'  => '1202',
			          //'jenis_akun'  => 52,
			          'keterangan'  => 'Persediaan barang jadi',
								'debet' => $nilaikelebihan - ($nilaihilang + $nilairusak),
								'kredit'  => 0,
								'urutan'  => 3,
								'hapus' => 0
							);
            }
					/*	if($nilaikelebihan > 0){

							$details[]=array(
								'ref_akun'  => '1202',
			          //'jenis_akun'  => 52,
			          'keterangan'  => 'Persediaan barang jadi',
								'debet' => $nilaikelebihan,
								'kredit'  => 0,
								'urutan'  => 3,
								'hapus' => 0
							);
						}*/


			        /*$details[]=array(
			          'ref_akun'  => '1202',
			          //'jenis_akun'  => 52,
			          'keterangan'  => 'Persediaan barang jadi',
			          'debet' => 0,
			          'kredit'  => $nilaihilang + $nilairusak,
			          'urutan'  => 4,
			          'hapus' => 0
			        );*/
              if($nilaikelebihan < ($nilaihilang + $nilairusak)){
                $details[]=array(
                  'ref_akun'  => '1202',
  			          //'jenis_akun'  => 52,
  			          'keterangan'  => 'Persediaan barang jadi',
  			          'debet' => 0,
  			          'kredit'  => ($nilaihilang + $nilairusak) - $nilaikelebihan,
  			          'urutan'  => 4,
  			          'hapus' => 0
  							);
              }
							if($nilaikelebihan > 0){
				        $details[]=array(
				          'ref_akun'  => $kelebihanstok,
				          'keterangan'  => 'Pendapatan Lain-lain',
				          'kredit' => $nilaikelebihan,
				          'debet'  => 0,
				          'urutan'  => 5,
				          'hapus' => 0
				        );
							}

			        $j=array(
			          'tanggal' => isset($data['tgl_diproses'])?$data['tgl_diproses']:date('Y-m-d'),
			          'keterangan'  => 'Stok Opname '.$sop['no_surat'],
			          'details' => $details,
			          'hapus' =>0,
                'ref' => $sop['id'],
                'linkterkait' =>'PSO-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$sop['id'],
                'no_dokumen'  => $sop['no_surat'],
                'type'  => 9900
			        );
			        $this->model_keuangan_jurnal->addJurnalUmum($j);
          }
        }
        $update=array(
          'tgl_diproses'  => $data['tgl_diproses'],
          'status'  => 2,
          'user_proses' => $this->user->getId(),
          'catat_jurnal'  => $data['catat_jurnal'],
          'no_suratpersetujuan' =>'PSO-'.$this->user->getId().'-'.date('Y',time()).'-'.date('m',time()).'-'.$id,
        );

        $this->db->update('permohonan_stokopname',$update,array('id'=>$id));
      }
      if($data['status'] == 3){
        $update=array(
          'tgl_diproses'  => $data['tgl_diproses'],
          'status'  => 3,
          'user_proses' => $this->user->getId(),
          'alasan_dibatalkan' => $this->db->escape($data['alasan_dibatalkan'])
        );

        $this->db->update('permohonan_stokopname',$update,array('id'=>$id));
      }
    }
  }
}
?>
