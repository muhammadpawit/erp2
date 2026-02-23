<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Jurnal_Memo_Nisson_".date('d-F-Y',strtotime($_REQUEST['filter_date_start']))."_s/d_".date('d-F-Y',strtotime($_REQUEST['filter_date_end'])).".xls");
?>
<style>
table{width:100%;border-collapse:collapse}
</style>
                  <table border="1">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>Ref</th>
                        <th>No. Dokumen</th>
                        <th>Keterangan</th>
                        <th colspan="2" class="text-center">Debet</th>
                        <th colspan="2" class="text-center">Kredit</th>
                        
                      </tr>
                      <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="text-center">Ref Akun</th>
                        <th></th>
                        <th class="text-center">Ref Akun</th>
                        <th></th>
                        
                      </tr>

                    </thead>
                    <tbody>

                      <?php
                      if ($orders) { ?>
                      <?php foreach ($orders as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>

                        <td>
                          <?php echo ($product['linkterkait']==null)?$product['ref']:$product['ref']; ?>
                        </td>
                        <!--td>
                          <?php echo ($product['linkterkait']==null)?'':$product['linkterkait']; ?>
                        </td-->
                        <td><?php echo $product['no_dokumen']; ?></td>
                        <td><b><?php echo $product['keterangan']; ?></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        
                      </tr>
                      <?php
                      foreach($product['detail'] as $d){
                      ?>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <?php
                        if($d['debet'] > 0){
                        ?>
                          <td><?php echo $d['keterangan']; ?></td>
                          <td><?php echo $d['ref_akun']; ?></td>
                          <td><?php echo $this->currency->format($d['debet']); ?></td>
                          <td></td>
                          <td></td>
                        <?php
                        }
                        ?>
                        <?php
                        if($d['kredit'] > 0){
                        ?>
                          <td style="padding-left:35px;"><?php echo $d['keterangan']; ?></td>
                          <td></td>
                          <td></td>
                          <td><?php echo $d['ref_akun']; ?></td>
                          <td><?php echo $this->currency->format($d['kredit']); ?></td>
                        <?php
                        }
                        ?>

                      </tr>
                      <?php
                      }
                      ?>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="6">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>