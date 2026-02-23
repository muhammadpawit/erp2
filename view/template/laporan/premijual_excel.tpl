<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Premi_Supir_Kenek.xls");
?>                
                <h3 class="box-title">Akumulasi Premi Jual <?php echo isset($periode)?'Periode '.$periode['nama']:'';?></h3>
                <table border="1" style="border-collapse:collapse;width:100%;margin-top:2%">
                    <thead>
                      <tr>
                        <th class="left">Nama</th>
                        <th class="left">Kode Premi</th>
                        <th class="left">Akumulasi</th>
                        <th class="left">Premi</th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($users) { ?>
                      <?php foreach ($users as $user) { ?>
                      <tr style="background-color:#5dcf8c">
                        <td class="left"><b><?php echo $user['firstname']; ?></b></td>
                        <td></td>
                        <td></td>
                        <td><b><?php echo $user['total']; ?></b></td>
                      </tr>
                      <?php
                      foreach($user['akumulasisopir'] as $a){
                        if(!empty($a)){
                      ?>
                      <tr style="font-weight:bold;">
                        <td></td>
                        <td class="left"><?php echo $a['kodepremi']; ?></td>
                          <td><?php echo $a['total']+$a['totalkernet']; ?></td>
                          <td><?php echo $this->currency->format($a['premikernet']+$a['premisopir']); ?></td>
                      </tr>
                      <?php foreach($user['details'] as $d){?>
                      <?php if($d['kodepremi']==$a['kodepremi']){?>
                        <tr>
                          <td></td>
                          <td><?php echo $d['nama']?></td>
                          <td><?php echo $d['qty']?></td>
                          <td></td>
                        </tr>
                      <?php } ?>
                      <?php } ?>
                      <?php
                        }
                      }
                      ?>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                </table>