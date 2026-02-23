<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Buku_Besar_Nisson_".date('d-F-Y',strtotime($_REQUEST['filter_date_start']))."_s/d_".date('d-F-Y',strtotime($_REQUEST['filter_date_end'])).".xls");
?>
                <table class="table table-bordered" border="1" style="border-collapse: collapse;width: 100%">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th class="text-center">Debet</th>
                        <th class="text-center">Kredit</th>
                        <th class="text-center">Saldo</th>

                      </tr>


                    </thead>
                    <tbody>
                      <tr>
                        <td></td>
                        <td><b>Total</b></td>
                        <td><b><?php echo $this->currency->format($totaldebet); ?></b></td>
                        <td><b><?php echo $this->currency->format($totalkredit); ?></b></td>
                        <td><b>
                          <?php
                            if($type == 1){
                              echo $this->currency->format($totaldebet-$totalkredit);
                            }else{
                              echo $this->currency->format($totalkredit-$totaldebet);
                            }
                            ?>
                          </b>
                        </td>
                      </tr>

                      <?php
                      if ($orders) { ?>
                      <?php foreach ($orders as $product) { ?>

                      <?php
                      foreach($product['detail'] as $d){
                      ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>

                        <td><b><?php echo $product['keterangan']; ?></b></td>
                        <td><?php echo $this->currency->format($d['debet']); ?></td>
                        <td><?php echo $this->currency->format($d['kredit']); ?></td>
                        <td><?php
                          if($type == 1){
                            echo $this->currency->format($d['debet']-$d['kredit']);
                          }else{
                            echo $this->currency->format($d['kredit']-$d['debet']);
                          }
                          ?>
                          </td>
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
