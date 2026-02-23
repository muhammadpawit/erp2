<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_deposit_supplier_vendor_lokal.xls");
?>
<style>
table{width:100%;border-collapse:collapse}
</style>
                  <table border="1">
                    <thead>
                      <tr>
                        <th class="left">Nama Supplier</th>
                        <th class="left">Deposit</th>
                        <th class="left">Giro Belum Cair</th>
                        <th class="left">Hutang</th>
                        <th class="left">Sisa Harus Bayar</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($vendors) { ?>
                      <?php foreach ($vendors as $category) { ?>
                      <tr>
                        <td class="left"><?php echo $category['name']; ?></td>
                        <td class="left"><?php echo $category['deposit']; ?></td>
                        <td class="left"><?php echo $category['giro']?></td>
                        <td align="left">&nbsp;<?php echo $category['hutang']; ?></td>
                        <td align="left">&nbsp;<?php echo $this->currency->format($category['sisa']); ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="8">Data vendor tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                      <tr>
                          <td><b>Total</b></td>
                          <td>&nbsp;<b><?php echo $totaldeposit?></b></td>
                          <td>&nbsp;<b><?php echo $totalgiro?></b></td>
                          <td>&nbsp;<b><?php echo $totalhutang?></b></td>
                          <td>&nbsp;<b><?php echo $totalsisa?></b></td>
                      </tr>
                    </tbody>
                  </table>