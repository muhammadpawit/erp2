<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Terima_barang_dagang_".time().".xls");
?>
                
                <table border="1" style="width:100%">
                    <thead>
                      <tr>
                        <th>No.dokumen</th>
                        <th>Tgl Dibuat</th>
                        <th>Tgl Datang</th>
                        <th>No. Surat Jalan</th>
                        <th>Nomor PO</th>
                        <th>Gudang</th>
                        <th>Vendor</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Quantity PO</th>
                        <th>Status Penerimaan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['no_dokumen']; ?></td>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['tgl_terima']; ?></td>
                        <td><!--<a href=""><?php echo $product['no_suratjalan']; ?></a>--><a onclick="detail('<?php echo $product['id'] ?>')" data-toggle="modal" data-target="#jurnal"><?php echo $product['no_suratjalan']; ?></a></td>
                        <td><?php echo $product['no_po']; ?></td>
                        <td><?php echo $product['nama']; ?></td>
                        <td><?php echo $product['vendor']; ?></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo $product['harga']; ?></td>
                        <td><?php echo $product['quantity']; ?></td>
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