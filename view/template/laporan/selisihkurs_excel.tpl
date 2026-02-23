<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Selisih_Kurs.xls");
?>
<style>
table{width:100%;border-collapse:collapse}
</style>                  
                  <table border="1">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <!--<th>Jatuh Tempo</th>-->
                        <th>Nomor Faktur</th>
                        <th>Gudang</th>
                        <!--<th>Vendor</th>-->
                        <th>Total Tagihan</th>
                        <th>Total Bayar</th>
                        <th>Kurs PIB</th>
                        <th>Total PIB</th>
                        <th>Selisih Kurs</th>
                        <th>Status Pembayaran</th>
                        <!--<th>Status Penerimaan</th>-->
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <!--<td><?php echo $product['jatuhtempo']; ?></td>-->
                        <td><?php echo $product['no_faktur']; ?></td>
                        <td><?php echo $product['gudang']; ?></td>
                        <!--<td><?php echo $product['name']; ?></td>-->
                        <td><?php echo $product['total']; ?></td>
                        <td><?php echo $product['totalbayar']; ?></td>
                        <td><?php echo $product['kurspib'] ?></td>
                        <td><?php echo $product['totalpib']?></td>
                        <td><?php echo $product['selisihkurs']?></td>
                        <td><?php echo $product['status']; ?></td>
                        <!--<td><?php echo $product['statuspenerimaan']; ?></td>-->
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="9">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <td colspan="3"><b>Total</b></td>
                        <td><b><?php echo $alltagihan ?></b></td>
                        <td><b><?php echo $allbayar ?></b></td>
                        <td><b><?php echo $allkurs ?></b></td>
                        <td><b><?php echo $allpib ?></b></td>
                        <td><b><?php echo $allselisih ?></b></td>
                      </tr>
                    </tbody>
                  </table>