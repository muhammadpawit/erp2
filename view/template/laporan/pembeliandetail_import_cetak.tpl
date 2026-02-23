<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_pembelian_detail_import.xls");
?>
<style>
table{width:100%;border-collapse:collapse}
</style>                  
                  <table border="1">
                    <thead>
                      <tr>
                        <th class="left">Nama Supplier</th>                        
                        <th class="left">Jumlah</th>
                        <th class="left">Total Bayar</th>
                        <th class="left">Tgl Invoice</th>
                        <th class="left">Invoice</th>
                        <th class="left">Metode Pembayaran</th>
                        <th class="left">Lama Kredit (Hari)</th>
                        <th class="left">Tgl Jatuh Tempo</th>
                        <th class="left">Status</th>
                        <th style="text-align: center;">Tgl Lunas</th>
                      </tr>
                    </thead>
                    <tbody>                      
                      <?php if ($penjualans) { ?>
                      <?php foreach ($penjualans as $product) { ?>
                      <tr class="invoice" data-id="<?php echo $product['id']; ?>" data-faktur="<?php echo $product['no_faktur']; ?>" id="list-invoice-<?php echo $product['id']; ?>" >
                        <td><?php echo $product['supplier']; ?></td>
                        <td><?php echo $product['jumlah']; ?></td>
                        <td><?php echo $product['totalbayar']; ?> (<?php echo $product['totalbayarrp']; ?>)</td>
                        <td><?php echo $product['tgl_inv']; ?></td>
                        <td><?php echo $product['invoice']; ?></td>
                        <td><?php echo $product['metode_pembayaran']; ?></td>
                        <td><?php echo $product['lamakredit']; ?></td>
                        <td><?php echo $product['tgl_jatuhtempo']; ?></td>
                        <td><?php echo $product['status']; ?></td>
                        <td><?php echo $product['tgl_lunas']; ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td colspan="12">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <td><b>Total</b></td>
                        <td><b><?php echo '$'.number_format($totaljumlah,2) ?></b></td>
                        <td><b><?php echo '$'.number_format($alltotalbayar,2) ?> (<?php echo 'Rp'.number_format($alltotalbayarrp,2) ?>)</b></td>
                      </tr>
                    </tbody>
                  </table>