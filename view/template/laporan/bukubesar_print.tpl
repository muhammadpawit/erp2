<?php
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=bukubesar.xls");
?>                  
<h4>Nama Akun <?php echo $namaakun ?></h4>
                  <table border="1" style="width:100%">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>No.Dokumen</th>
                        <th>Keterangan</th>
                        <th class="text-center">Debet</th>
                        <th class="text-center">Kredit</th>
                        <th class="text-center">Saldo</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($orders){ ?>
                        <?php
                          $format="d/m/Y";
                          $year=date('Y',strtotime($filter_date_start));
                          $awal= date($format, strtotime($year."-01-01"));
                          $sisa=0;
                          $a=0;
                          $sblm=0;
                          $debet=0;
                          $kredit=0;
                          $saldoakhir=0;
                          foreach ($orders as $product) {
                            foreach($product['detail'] as $d){
                              if($a==0){
                                $sisa += ($d['debet']-$d['kredit']);
                              }else{
                                $sisa += ($d['debet']-$d['kredit']);
                              }

                              if($product['tanggal'] < $filter_date_start){
                                $sblm = $sisa;
                              } 
                              $a++;
                            }
                          }
                        ?>
                            <tr>
                              <td><b><?php //echo echo $awal ?></b></td>
                              <td>-</td>
                              <td>Saldo Awal </td>
                              <td align="right"><?php echo number_format(0,2,',','.')?></td>
                              <td align="right"><?php echo number_format(0,2,',','.')?></td>
                              <td align="right"><?php echo number_format($saldoawal,2,',','.')?></td>
                            </tr>
                      <?php } ?>
                      <?php
                      $sisa=0;
                      $a=0;
                      $sblm=0;
                      $debet=0;
                      $kredit=0;
                      $saldoakhir=0;
                      if ($orders) { ?>
                      <?php foreach ($orders as $product) { ?>

                      <?php
                      foreach($product['detail'] as $d){
                      ?>
                      <?php
                          /*
                            if($a==0){
                              $sisa = $saldoawal+($d['debet']-$d['kredit']);
                            }else{
                              $sisa = $saldoawal+($d['debet']-$d['kredit']);
                            }
                          
                          if($product['tanggal'] < $filter_date_start){
                            $sblm = $sisa;
                          }else{
                            $sblm=$sisa;
                          }*/
                          if( date('Y-m-d',strtotime($product['tanggal'])) >= $filter_date_start & date('Y-m-d',strtotime($product['tanggal'])) <= $filter_date_end  ) { 
                            if($a==0){
                              $sisa=$saldoawal+($d['debet']-$d['kredit']);
                            }else{
                              $sisa=$sisa+($d['debet']-$d['kredit']);
                            }
                            $a++;
                          }
                      ?>
                      <?php if( date('Y-m-d',strtotime($product['tanggal'])) >= $filter_date_start & date('Y-m-d',strtotime($product['tanggal'])) <= $filter_date_end  ) { ?>
                      <?php 
                          $debet +=$d['debet'];
                          $kredit +=$d['kredit'];
                      ?>
                      <?php 
                        $this->load->model('pembelian/invoicepembeliandagang');
                        $ket=null;       
                        $ketivc=null;                 
                        if($filter_jenis==1101){
                          if($d['kredit']>0){
                            $ket=$this->model_keuangan_jurnal->getnamacust($product['ref']);
                            $ketivc='( '.$this->model_keuangan_jurnal->getnamacustivc($product['ref']).' )';
                          }else{
                            $ket=$this->model_keuangan_jurnal->getnamacustdeb($product['ref']);
                          }
                        }else if($filter_jenis==2101){
                          $ketname=substr($product['keterangan'],0,57);
                          if( strtolower($ketname) == "alokasi pembayaran pembelian produk dagang untuk invoice "){
                            $akhir=substr($product['keterangan'],57);
                            $ket=$this->model_pembelian_invoicepembeliandagang->getkethutangusaha($akhir);
                          }else if( strtolower(substr($product['keterangan'],0,49)) == "invoice pembelian produk dagang dengan no faktur "){
                            $akhir=substr($product['keterangan'],49);
                            $ket=$this->model_pembelian_invoicepembeliandagang->getkethutangusaha($akhir);
                          }else{
                            $ket=null;
                          }
                        }else if($filter_jenis==1311){
                          $ketname=substr($product['keterangan'],0,57);
                          if( strtolower($ketname) == "alokasi pembayaran pembelian produk dagang untuk invoice "){
                            $akhir=substr($product['keterangan'],57);
                            $ket=$this->model_pembelian_invoicepembeliandagang->getkethutangusaha($akhir);
                          }else if( strtolower(substr($product['keterangan'],0,49)) == "invoice pembelian produk dagang dengan no faktur "){
                            $akhir=substr($product['keterangan'],49);
                            $ket=$this->model_pembelian_invoicepembeliandagang->getkethutangusaha($akhir);
                          }else if( strtolower($product['keterangan']) == "deposit pembelian ke vendor"){
                            $ket=$this->model_pembelian_invoicepembeliandagang->getkethutangusaha2($product['ref'],$product['type']);
                          }else{
                            $ket=null;
                          }
                        }
                      ?>
                      <tr>
                        <td><?php echo date('d/m/Y',strtotime($product['tanggal'])); ?></td>
                        <td><?php echo $product['linkterkait'] ?></td>
                        <td><b><?php echo $product['keterangan']; ?> <?php echo $ket ?> <?php echo $ketivc ?></b></td>
                        <td align="right"><?php echo number_format($d['debet'],2,',','.'); ?></td>
                        <td align="right"><?php echo number_format($d['kredit'],2,',','.'); ?></td>
                        <td align="right">
                          <?php echo number_format($sisa,2,',','.') ?>
                        </td>
                      </tr>
                      <?php }?>
                      <?php
                      }
                      ?>
                      <?php } ?>
                      <tr>
                        <td colspan="2"><b>Saldo Awal</b></td>
                        <td><?php echo $this->currency->format($saldoawal); ?></td>
                      </tr>
                      <tr>
                        <td colspan="2"><b>Total Debet</b></td>
                        <td><?php echo $this->currency->format($debet); ?></td>
                      </tr>
                      <tr>
                        <td colspan="2"><b>Total Kredit</b></td>
                        <td><?php echo $this->currency->format($kredit); ?></td>
                      </tr>
                      <tr>
                        <td colspan="2"><b>Total Saldo</b></td>
                        <td><?php echo $this->currency->format($saldoawal+$debet-$kredit); ?></td>
                      </tr>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="6">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>