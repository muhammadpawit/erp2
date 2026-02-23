<?php
if($this->user->getUsername()=="pawit"){

}else{
  header("Content-type: application/vnd-ms-excel");
  header("Content-Disposition: attachment; filename=Laporan_pembelian_detail_lokal.xls");
}
?>
<style>
table{border-collapse:collapse;width:100%;}
</style>
                  <table border="1">
                    <thead>
                      <tr>
                        <th class="left">Tanggal</th>
                        <th class="left">No.Dokumen</th>
                        <th class="left">No.Surat Jalan</th>
                        <th class="left">No.PO</th>
                        <th class="left">Nama Supplier</th>
                        <th class="left">Nama Barang</th>
                        <th class="left">Harga</th>
                        <th class="left">Quantity</th>
                        <th class="left">Total</th>
                        <!--<th class="left">Status</th>
                        <th class="left">Jumlah</th>
                        <th class="left">Total Bayar</th>
                        <th class="left">Tgl Invoice</th>
                        <th class="left">Invoice</th>
                        <th class="left">Metode Pembayaran</th>
                        <th width="1" class="left">Lama Kredit (Hari)</th>
                        <th width="1" class="left">Tgl Jatuh Tempo</th>
                        <th width="3" class="left">Status</th>
                        <th width="1"style="text-align: center;">Tgl Lunas</th>-->
                      </tr>
                    </thead>
                    <tbody>                      
                      <?php if ($penjualans) { ?>
                      <?php foreach ($penjualans as $product) { ?>
                      <tr class="invoice" data-id="<?php echo $product['id']; ?>" data-faktur="<?php echo $product['no_faktur']; ?>" id="list-invoice-<?php echo $product['id']; ?>" >
                        <td><?php echo $product['tgl_terima']; ?></td>
                        <td><?php echo $product['no_dokumen']; ?></td>
                        <td><?php echo $product['no_suratjalan']; ?></td>
                        <td><?php echo $product['no_po']; ?></td>
                        <td><?php echo $product['supplier']; ?></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo $product['harga']; ?></td>
                        <td><?php echo $product['quantity']; ?></td>
                        <td><?php echo $product['total']; ?></td>
                        <!--<td><?php echo $product['status']; ?></td>-->
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td colspan="12">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                      <tr>
                          <td colspan="7"><b>Total</b></td>
                          <td><b><?php echo $totaljumlah ?></b></td>
                          <td><b><?php echo $this->currency->format($total) ?></b></td>
                      </tr>
                    </tbody>
                  </table>