<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Invoice_Pembelian_Produk_Dagang_lokal_".time().".xls");
?>
                
                <table border="1" style="border: 1px solid black; width:100%">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>Jatuh Tempo</th>
                        <th>Nomor Faktur</th>
                        <th>Gudang</th>
                        <th>Vendor</th>
                        <th>Total</th>
                        <th>Total Bayar</th>
                        <th>Status Pembayaran</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['jatuhtempo']; ?></td>
                        <td><?php echo $product['no_faktur']; ?></td>
                        <td><?php echo $product['gudang']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <td><?php echo $product['total']; ?></td>
                        <td><?php echo $product['totalbayar']; ?></td>
                        <td><?php echo $product['status']; ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="9">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>