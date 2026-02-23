<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=daftar_vendor_lokal.xls");
?>
<style>
table{width:100%; border-collapse:collapse}
</style>
                  <table border="1">
                    <thead>
                      <tr>
                        <th class="left">Nama</th>
                        <th class="left">Alamat</th>
                        <th class="left">Email</th>
                        <th class="left">Telp</th>
                        <th class="left">Legalitas</th>
                        <th class="left">Hutang</th>
                        <th class="left">Deposit</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($vendors) { ?>
                      <?php foreach ($vendors as $category) { ?>
                      <tr>
                        <td class="left">&nbsp;<?php echo $category['name']; ?></td>
                        <td class="left">&nbsp;<?php echo $category['alamat']; ?></td>
                        <td class="left"><?php echo $category['email']; ?></td>
                        <td class="left"><?php echo $category['telephone']; ?></td>
                        <td class="left">SIUP: <?php echo $category['siup']; ?><br>
                          NPWP: <?php echo $category['npwp']; ?><br>
                          TDP: <?php echo $category['tdp']; ?><br>
                          HO: <?php echo $category['ho']; ?><br>
                          SPPKP: <?php echo $category['sppkp']; ?><br>
                        </td>
                        <td class="left"><?php echo $category['hutang']; ?><br><small><?php echo $category['jatuhtempo']; ?></small></td>
                          <td class="left"><?php echo $category['deposit']; ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="8">Data vendor tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>