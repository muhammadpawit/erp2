<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_deposit_customer.xls");
 
?>
<style>
table { border-collapse:collapse;width:100%}
</style>
<table class="table table-bordered" border="1">
                    <thead>
                      <tr>
                        <th class="left">Customer ID</th>
                        <th class="left">Nama</th>
                        <th class="left" >Saldo Awal</th>
                        <th class="left" >Saldo Masuk</th>
                        <th class="left" >Saldo Keluar</th>
                        <th class="left" >Sisa Saldo</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($customers) { ?>
                      <?php foreach ($customers as $customer) { ?>
                      <tr>
                        <td class="left"><?php echo $customer['customer_id']; ?>
                        <td class="left"><?php echo $customer['name']; ?></td>
                        <td class="left"><?php echo $customer['awal']; ?></td>
                        <td class="left"><?php echo $customer['saldomasuk'] ?></td>
                        <td class="left"><?php echo $customer['saldokeluar'] ?></td>
                        <td class="left"><?php echo $customer['sisasaldo']; ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                      <!--<tr>
                        <td colspan="2"><b>Total</b></td>
                        <td><?php echo $this->currency->format($totaldeposit) ?></td>
                        <td><?php echo $this->currency->format($totalgiro) ?></td>
                        <td><?php echo $this->currency->format($totalpiutang) ?></td>
                        <td><?php echo $this->currency->format($totalsisaharusbayar) ?></td>
                      </tr>-->
                    </tbody>
                  </table>