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
                        <th class="left" >Deposit</th>
                        <th class="left" >Giro Belum Cair</th>
                        <th class="left" >Piutang</th>
                        <th class="left" >Sisa harus bayar</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($customers) { ?>
                      <?php foreach ($customers as $customer) { ?>
                      <tr>
                        <td class="left"><?php echo $customer['customer_id']; ?>
                        <td class="left"><?php echo $customer['name']; ?>
                          <br><small><?php //echo $customer['email']; ?></small>
                          <br><small><?php //echo $customer['telephone']; ?></small>
                        </td>
                        <td class="left"><?php echo $customer['deposit']; ?></td>
                        <td class="left"><?php echo $customer['nominal'] ?></td>
                        <td class="left"><?php echo $customer['piutang'] ?></td>
                        <td class="left"><?php echo $customer['sisa']; ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <td colspan="2" align="center"><b>Total</b></td>
                        <td><?php echo $this->currency->format($totaldeposit) ?></td>
                        <td><?php echo $this->currency->format($totalgiro) ?></td>
                        <td><?php echo $this->currency->format($totalpiutang) ?></td>
                        <td><?php echo $this->currency->format($totalsisaharusbayar) ?></td>
                      </tr>
                    </tbody>
                  </table>