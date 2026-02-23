<?php
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=Harga_Terendah_mulai_berlaku_$_REQUEST[date].xls");

?>                  
                  <table border="1" width="100%">
                    <thead>
                      <tr>
                        <th class="right">Id Produ</th>
                        <th class="left">Nama Produk</th>
                        <th class="left">Gudang</th>
                        <th class="right">Harga Terendah (termasuk PPn)</th>
                        <th class="right">Mulai berlaku</th>
                        <th class="right">Poin</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($products) { ?>
                      <?php foreach ($products as $product) { ?>
                      <tr>
                        <td><?php echo $product['product_id']; ?></td>
                        <td class="left"><?php echo $product['name']; ?></td>
                        <td class="left"><?php echo $product['gudang_id'];?></td>
                        <td><?php echo $product['hargatanpaformat']; ?></td>
                        <td><?php echo $product['date']; ?></td>
                        <td><?php echo $product['poin']; ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="9">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>