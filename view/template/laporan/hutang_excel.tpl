<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Hutang_didownload_oleh_".$this->user->getUsername().".xls");
?>
<style>
table{width:100%;border-collapse:collapse}
</style>
                  <table border="1">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Tanggal Invoice</th>
                        <th>Jatuh Tempo</th>
                        <th>Nomor Faktur</th>
                        <th>No. Dokumen</th>
                        <th>Gudang</th>
                        <th>Vendor</th>
                        <th>Total Tagihan</th>
                        <th>Total Bayar</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php foreach($permintaans as $p){?>
                        <tr>
                          <td><?php echo $p['no']?></td>
                          <td><?php echo $p['tgl']?></td>
                          <td><?php echo $p['jatuhtempo']?></td>
                          <td><?php echo $p['no_faktur']?></td>
                          <td><?php echo $p['no_dokumen']?></td>
                          <td><?php echo $p['gudang']?></td>
                          <td><?php echo $p['vendor']?></td>
                          <td><?php echo $p['tagihan']?></td>
                          <td><?php echo $p['totalbayar']?></td>
                          <td><?php echo $p['keterangan']?></td>
                        </tr>
                        <?php }?>                      
                      <tr>
                        <td colspan="7"><b>Total</b></td>
                        <td><b><?php echo $totaltagihan?></b></td>
                        <td><b><?php echo $totalbayar?></b></td>
                        <td></td>
                      </tr>
                    <tbody>
                  </table>