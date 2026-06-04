<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=".$namasales.".xls");
?>
<div class="row">
  <div class="col-md-12">
    <table border="1" style="border-collapse:collapse">
      <thead>
        <tr>
          <th>Tgl Penawaran</th>
          <th>Tgl Inv</th>
          <th>Tgl Lunas</th>
          <th>Nama Sales</th>
          <th>Kode Customer</th>
          <th>Nama Customer</th>
          <th>Gudang</th>
          <th>Nama Barang</th>
          <th>QTY</th>
          <th>Poin Penjualan</th>
          <th>Harga Satuan</th>
          <th>Nilai Invoice</th>
          <th>Harga Terendah</th>
          <th>Total Profit Margin Kotor</th>
          <th>Biaya Transport</th>
          <th>Biaya Bunga Kredit</th>
          <th>Invoice</th>
          <th>Metode Pembayaran</th>
          <th>Lama Bayar (Hari)</th>
          <th>Status</th>
          <th>Keterangan</th>
          <th> Profit Margin Kotor Setelah Pajak </th>
          <th> Profit Margin Bersih</th>
          <th>%</th>
          <th> Kota</th>
          <th> Provinsi</th>
        <tr>
      </thead>
      <tbody>
        <?php $totalpoin=0;$allivs=0;$allih=0;?>
        <?php foreach($penjualans as $p){?>
        <?php
                          $th=0; 
                          $tqty=0;
                          $tpoin=0;
                          $bersih=0;
                          $allivs+=($p['total']);
                          //$allih+=($p['totalhargaterendah']);
                          foreach($p['products'] as $prd){
                            $th+=($prd['totalhargaterendah']);
                            $allih+=($prd['totalhargaterendah']);
                            $tqty+=($prd['qty']);
                            $tpoin+=($prd['qty']*$prd['poin']);
                                  //if($prd['status'] == 1 && $prd['tgllunas']<='2021-02-27'){
                                  if($prd['status'] == 1){
                                    $tanggal_lahir  = strtotime($prd['tglinvoice']);
                                    $sekarang    = strtotime($prd['tgllunas']); // Waktu sekarang
                                    $diff   = $sekarang - $tanggal_lahir;
                                    $h=floor($diff / (60 * 60 * 24)) . ' '; // Umur anda dalam hitungan hari
                                  }else{
                                    $tanggal_lahir  = strtotime($prd['tglinvoice']);
                                    $sekarang    = strtotime(date('Y-m-d')); // Waktu sekarang
                                    //$sekarang=strtotime('2021-02-27');
                                    $diff   = $sekarang - $tanggal_lahir;
                                    $h=floor($diff / (60 * 60 * 24)) . ' '; // Umur anda dalam hitungan hari
                                  }

                                  $mp_ori_temp = trim($prd['metodepembayaran']);
                                  $mp_lower_temp = strtolower($mp_ori_temp);
                                  $mp_hari_temp = 0;
                                  if (in_array($mp_lower_temp, ['cbd', 'c.b.d', 'tunai', 'transfer', 'c.o.d', ''])) {
                                    $mp_hari_temp = 0;
                                  } elseif (preg_match('/net\s*(\d+)/i', $mp_lower_temp, $matches)) {
                                    $mp_hari_temp = (int)$matches[1];
                                  } elseif (preg_match('/\d+/', $mp_lower_temp, $matches)) {
                                    $mp_hari_temp = (int)$matches[0];
                                  }

                                  // Rumus Biaya Bunga Kredit
                                  if ($prd['status'] != 1 && $mp_hari_temp > 60) {
                                      // 1. Bila metode pembayaran > 60 hari dan belum lunas
                                      $biayabungakredit = round($th * $mp_hari_temp * (0.025 / 30));
                                  } elseif ($prd['status'] != 1 && $mp_hari_temp <= 60) {
                                      // 2. Bila metode pembayaran < 60 hari dan belum lunas maka ambil kolom lama bayar
                                      if ($h > 0) {
                                          $biayabungakredit = round($th * $h * (0.025 / 30));
                                      } else {
                                          $biayabungakredit = 0;
                                      }
                                  } else {
                                      // Lunas (rumus sebelumnya)
                                      if ($h > 0) {
                                          $biayabungakredit = round($th * $h * (0.025 / 30));
                                      } else {
                                          $biayabungakredit = 0;
                                      }
                                  }

                                  $bersih=($p['total']-$th)-$p['bkirim']-$biayabungakredit; // rumus lama

                                  if($bersih>=0){
                                    $color="black";
                                  }else{
                                    $color="red";
                                  }

                          }

                          $totalpoin+=($tpoin);

                          ?>
        <tr style="background-color:#e3fbfc">
          <?php 
                                $i=0;

                                if($p['tglinvoice']<'2022-04-01'){
                                  $totalprofitkotor=(($p['total']-$th)/1.1);
                                }else{
                                  $totalprofitkotor=(($p['total']-$th)/1.11);
                                }

                                $bersihbaru=$totalprofitkotor-($p['bkirim']+$biayabungakredit);
                                  if($bersihbaru>=0){
                                    $color="black";
                                  }else{
                                    $color="red";
                                  }
                                ?>
          <td></td>
          <td>
            <?php echo $p['tglinvoice']?>
          </td>
          <td>
            <?php echo $p['tgllunas']=='1970-01-01'?'':$p['tgllunas']?>
          </td>
          <td>-</td>
          <td>
            <?php echo $p['kodecustomer']?>
          </td>
          <td>
            <?php echo $p['namacustomer']?>
          </td>
          <td>
            <?php echo isset($p['namagudang']) ? $p['namagudang'] : ''?>
          </td>
          <td>-</td>
          <td>
            <?php echo $tqty?>
          </td>
          <td>
            <?php echo $tpoin?>
          </td>
          <td></td>
          <td>
            <?php echo $p['total'] ; ?>
          </td>
          <td>
            <?php echo ($th) ; ?>
          </td>
          <td>
            <?php echo ($p['total']-$th) ; ?>
          </td>
          <td>
            <?php echo ($p['bkirim']);?>
          </td>
          <td>
            <?php echo ($biayabungakredit);?>
          </td>
          <td></td>
          <td></td>
          <td>
            <?php echo $h>=0?$h:0?>
          </td>
          <td></td>
          <td>
            <?php echo $p['customerbaru']=="Ya"?'Customer Baru':'';?>
          </td>
          <td>
            <?php echo (round($totalprofitkotor));?>
          </td>
          <td>
            <?php echo (round($bersihbaru));?>
          </td>
          <td>
            <?php echo number_format(($th != 0 ? ($bersihbaru/$th) : 0) * 100, 2, ',', '.'); ?> %
          </td>
          <td>
            <?php echo $p['kota']?>
          </td>
          <td>
            <?php echo $p['provinsi']?>
          </td>
          <?php foreach($p['products'] as $pr){?>
        <tr>
          <td>
            <?php echo $pr['tglso']?>
          </td>
          <td>
            <?php echo $pr['tglinvoice']?>
          </td>
          <td>
            <?php echo $pr['tgllunas']=='1970-01-01'?'':$pr['tgllunas']?>
          </td>
          <td>
            <?php echo $pr['namasales']?>
          </td>
          <td>
            <?php echo $pr['kodecustomer']?>
          </td>
          <td>
            <?php echo $pr['namacustomer']?>
          </td>
          <td></td>
          <td>
            <?php echo $pr['namabarang']?>
          </td>
          <td>
            <?php echo $pr['qty']?>
          </td>
          <td>
            <?php echo $pr['poin']*$pr['qty']?>
          </td>
          <td>
            <?php echo $pr['hargasatuan']?>
          </td>
          <td></td>
          <td>
            <?php echo $pr['harga_terendah']?>
          </td>
          <td>0</td>
          <td>
            <?php //echo $pr['biayatransport'] ?>
          </td>
          <td>0</td>
          <td>
            <?php echo $pr['nomorinvoice']?>
          </td>
          <td>
            <?php
                                    $mp_ori = trim($pr['metodepembayaran']);
                                    $mp = strtolower($mp_ori);
                                    $val = '';
                                    if (in_array($mp, ['cbd', 'c.b.d', 'tunai', 'transfer', 'c.o.d', ''])) {
                                      $val = $mp == '' ? '' : '0';
                                    } elseif (preg_match('/net\s*(\d+)/i', $mp, $matches)) {
                                      $val = $matches[1];
                                    } elseif (preg_match('/\d+/', $mp, $matches)) {
                                      $val = $matches[0];
                                    } else {
                                      $val = '0';
                                    }

                                    echo $val;
                                  ?>
          </td>
          <td>0</td>
          <td>
            <?php
                                    if($pr['status']==1){
                                      echo "Lunas";
                                    }else{
                                      echo "Belum Lunas";
                                    }
                                    /*
                                    if($pr['status']==1 && $pr['tgllunas']<='2021-02-27'){
                                      echo "Lunas";
                                    }else{
                                      echo "Belum Lunas";
                                    }
                                    */
                                    ?>
          </td>
          <!--<td><?php echo $pr['status']==1?'Lunas':'Belum Lunas'?></td>-->
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <?php } ?>
        </tr>
        <?php } ?>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>Total Poin</td>
          <td></td>
          <td></td>
          <td>
            <?php echo $totalpoin?>
          </td>
          <td></td>
          <td>
            <?php echo $allivs ?>
          </td>
          <td>
            <?php echo $allih ?>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

</div>
<div class="box-footer">
  <div class="row">
    <div class="col-md-12">
    </div>
  </div>
</div>
</div>
</div>
</div>
</section>
</div>
</div>