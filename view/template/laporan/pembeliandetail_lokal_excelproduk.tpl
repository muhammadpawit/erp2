<?php

if($this->user->getUsername()=="pawitx"){
  
}else{
  header("Content-type: application/vnd-ms-excel");
  header("Content-Disposition: attachment; filename=Laporan_pembelian_detail_lokal_with_Produk.xls");  
}

?>
<style>
table{border-collapse:collapse;width:100%;}
</style>
                  <table border="1">
                    <thead>
                      <tr>
                        <th class="left">No</th>
                        <th class="left">Tanggal</th>
                        <th class="left">No.Dokumen</th>
                        <th class="left">No.Surat Jalan</th>
                        <th class="left">No.PO</th>
                        <th class="left">Nama Supplier</th>
                        <th class="left">Nama Barang</th>
                        <th class="left">Harga</th>
                        <th class="left">Quantity</th>
                        <th class="left">Total</th>
                      </tr>
                    </thead>
                    <tbody>                      
                      <?php if ($penjualans) { ?>
                      <?php foreach ($penjualans as $product) { ?>
                      <tr>
                        <td align="center"><?php echo $no++; ?></td>
                        <td colspan="9"><b>Surat Jalan</b></td>
                        </tr>
                      <tr class="invoice" data-id="<?php echo $product['id']; ?>" data-faktur="<?php echo $product['no_faktur']; ?>" id="list-invoice-<?php echo $product['id']; ?>" >
                        <td></td>
                        <td align="left"><?php echo $product['tgl_terima']; ?></td>
                        <td><?php echo $product['no_dokumen']; ?></td>
                        <td align="center"><?php echo $product['no_suratjalan']; ?></td>
                        <td><?php echo $product['no_po']; ?></td>
                        <td><?php echo $product['supplier']; ?></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo $product['harga']; ?></td>
                        <td align="center"><?php echo $product['quantity']; ?></td>
                        <td><?php echo $product['total']; ?></td>
                      </tr>
                        <tr>
                          <td></td>
                          <td colspan="9"><b>Rincian Invoice</b></td>
                        </tr>
                      <?php if(!empty($product['products'])){ ?>
                        <?php foreach($product['products'] as $ivs) {?>
                          <tr>
                            <td></td>
                            <td align="left"><?php echo date('d/m/Y',strtotime($ivs['tglfaktur'])) ?></td>
                            <td><?php echo $ivs['no_dokumen']?></td>
                            <td></td>
                            <td align="left"><?php echo $ivs['no_faktur']?></td>
                            <td><?php echo $product['supplier']; ?></td>
                            <td><?php echo $ivs['product_name']?></td>
                            <td><?php echo $this->currency->format($ivs['price'])?></td>
                            <td align="center"><?php echo $ivs['quantity']?></td>
                            <td><?php echo $this->currency->format(($ivs['price']*$ivs['quantity']) +($ivs['price']*$ivs['quantity']*0.1) )?></td>
                          </tr>
                        <?php } ?>
                      <?php }else{ ?>
                        <tr>
                          <td></td>
                          <td colspan="9">Belum ada invoice</td>
                        </tr>
                      <?php } ?>
                      <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td colspan="12">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                      <!--
                      <tr>
                          <td colspan="7"><b>Total</b></td>
                          <td><b><?php echo $totaljumlah ?></b></td>
                          <td><b><?php echo $this->currency->format($total) ?></b></td>
                      </tr>-->
                    </tbody>
                  </table>