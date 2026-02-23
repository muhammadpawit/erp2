<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Pembayaran_Penjualan_Kredit_".time().".xls");
?>                    
                    <table border="1" width="90%">
                      <thead>
                        <tr>
                          <th>Tanggal Input</th>
                          <th>Tanggal Bayar</th>
                          <th>Nama Customer</th>
                          <th>Total Alokasi Pembayaran</th>
                          <th>Invoice</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if ($permintaans) { ?>
                        <?php foreach ($permintaans as $product) { ?>
                        <tr>
                          <td valign="top"><?php echo $product['tanggalinput']; ?></td>
                          <td valign="top"><?php echo $product['tanggal']; ?></td>
                          <td valign="top"><?php echo $product['name']; ?></td>
                          <td valign="top"><?php echo $product['total']; ?></td>
                          <td>
                              <?php
                                foreach($product['invoice'] as $i){
                                  echo $i['invoice'].' '.$i['totalbayar'].'<br>';
                                }
                                ?>
                          </td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                        <tr>
                          <td class="center" colspan="5">Data tidak ditemukan</td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>