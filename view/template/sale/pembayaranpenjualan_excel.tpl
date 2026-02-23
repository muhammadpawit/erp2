<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Pembayaran_tunai_COD".time().".xls");
?>                  
                  <table width="90%" border="1">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Nomor Faktur</th>
                        <th>Jumlah Pembayaran</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['no_po']; ?></td>
                        <td align="right"><?php echo $product['jumlah']; ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="5">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>