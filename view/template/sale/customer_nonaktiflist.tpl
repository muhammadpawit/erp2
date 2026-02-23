<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Customer_Non_Aktif_".time().".xls");
?>
  
  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="left">sales</th>
                        <th class="left">Kategori Customer</th>
                        <th class="left">Nama</th>                        
                        <th class="left">Telephone</th>
                        <th class="left">Alamat</th>
                        <th class="left" style="width:10%">Deposit</th>
                        <th class="left" style="width:10%">Piutang</th>
                        <th class="right" style="width: 10%">status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($customers) { ?>
                      <?php foreach ($customers as $customer) { ?>
                        <?php 
                        $tanggal = $customer['invterakhir'];
                        $tanggal_lahir  = strtotime($customer['invterakhir']);
                        $sekarang    = time(); // Waktu sekarang
                        $diff   = $sekarang - $tanggal_lahir;
                        $status=floor($diff / (60 * 60 * 24)) ;
                        $st=1;
                        if($status<=60){
                          $st=0;
                          $status="<span class='badge bg-blue'>customer aktif</span>";
                        }else if($status>=61 && $status<18178){
                          $st=1;
                          $status="<span class='badge bg-red'>customer non aktif</span>";
                        }else if($status==18178){
                          $status="<span class='badge bg-yellow'>Belum Customer</span>";
                        }else{
                          $status="-";
                        }
                        ?>
                      <?php if($st==1){?>
                      <tr>
                        <td class="left"><?php echo $customer['sales']; ?>
                        <td class="left"><?php echo $customer['customer_group']; ?>
                        <td class="left"><?php echo $customer['name']; ?>
                          <br><small><?php echo $customer['email']; ?></small>
                        </td>
                        <td class="left"><?php echo $customer['telephone']; ?></td>
                        <td class="left"><?php echo $customer['alamat']; ?></td>
                        <td class="left"><?php echo $customer['deposit']; ?></td>
                        <td class="left"><?php echo $customer['piutang']; ?><br>
                          <small>Limit: <?php echo $customer['limit_piutang']; ?></small>
                        </td>
                        <td><?php echo $status; ?></td>
                      </tr>
                      <?php } ?>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="11"><?php echo $text_no_results; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>