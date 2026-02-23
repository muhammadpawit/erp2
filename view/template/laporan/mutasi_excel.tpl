<?php
header("Content-type: application/vnd-ms-excel");
if($this->user->getUsername()=="pawitx"){

}else{
header("Content-Disposition: attachment; filename=Laporan_Mutasi_Bank_".time().".xls");  
}

?>
            <h3 class="box-title">Laporan Mutasi Bank <?php echo $bank['name']; ?><br></h3>
              Saldo Awal: <?php echo $sblm ?><br>
              Saldo Masuk: <?php echo $totalmasuk ?><br>
              Saldo Keluar: <?php echo $totalkeluar ?><br>
              Saldo Akhir: <?php echo $totalsaldo; ?><br>
            </h3>
                <table border="1" width="90%">
                    <thead>
                      <tr>
                        <th class="left">Tgl Transaksi</th>
                       <th class="left">Tgl Input</th>
                        <th class="left">Referensi</th>
                        <th class="left">Keterangan</th>
                        <th class="left">Saldo Masuk</th>
                        <th class="left">Saldo Keluar</th>
                        <th class="left">Saldo Akhir </th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($kartustoks) { ?>
                      <?php foreach (($kartustoks) as $product) { ?>
                      <?php //foreach (array_reverse($kartustoks) as $product) { ?>
                      <tr>
                        <td class="left"><?php echo $product['date_trans'];?> </td>
                        <td class="left"><?php echo $product['date_added']; ?></td>
                        <td class="left">
                        <?php if($product['invoice']>=3000) { ?>
                        <a onclick="detail('<?php echo $product['invoice'] ?>')" data-toggle="modal" data-target="#jurnal"><?php echo empty($product['linkterkait'])?$product['invoice']:$product['linkterkait']; ?></a>
                        <?php }else{ ?>
                        <?php echo $product['invoice']; ?>
                        <?php } ?>
                        </td>
                        <td class="left"><?php echo $product['ket']; ?></td>
                        <td class="left"><?php echo str_replace(".",",", $product['saldo_masuk']); ?></td>
                        <td class="left"><?php echo str_replace(".",",", $product['saldo_keluar']); ?></td>
                        <!--<td><?php //echo $product['saldo_akhir'];?></td>-->
                        <td class="left"><?php echo str_replace(".",",", $product['sisa']);?> </td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="9">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <!--
                      <tr>
                        <td colspan="4"><b>Total</b></td>
                        <td><b><?php echo $totalmasuk ?></b></td>
                        <td><b><?php echo $totalkeluar ?></b></td>
                        <td><b><?php echo $totalsaldo ?></b></td>
                      </tr>
                      -->
                      <tr>
                      <!--  <td colspan="4"><b>Total Keseluruhan</b></td>
                        <td colspan="2" align="center"><?php echo $this->currency->format($totalsaldomasuk-$totalsaldokeluar) ?></td>
                        <td></td> -->
                      </tr>
                    </tfoot>
                  </table>