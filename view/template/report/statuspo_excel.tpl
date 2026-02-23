<?php
// Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=Laporan_status_po.xls");
?>                
                <table style="width:100%;border-collapse:collapse;" border="1">
                    <thead>
                      <tr>
                        <th>Tanggal PO</th>
                        <!--th>Jatuh Tempo</th-->
                        <th>Nama Barang</th>
                        <th>Nomor PO</th>
                        <th>Terkirim</th>
                        <!--<th>No. Invoice</th>-->
                        <!--<th>Metode Pembayaran</th>
                        <th>Metode Pengiriman</th>
                        <th>Nomor Surat Permintaan</th>
                        <th>Gudang</th>
                        <th>Vendor</th>
                        <th>Total</th>-->
                        <th>Status Pengiriman</th>
                        <th>Tertagih</th>
                        <th>Status Tagihan</th>
                        <!--<th>Quantity PO</th>-->
                        <!--<th>Quantity Diterima</th>-->

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <!--td><?php echo $product['jatuhtempo']=='01/01/70'?'':$product['jatuhtempo']; ?></td-->
                        <td><?php echo $product['no_po']; ?></td>
                        <td>
                          <?php foreach($product['sj'] as $sj){ ?>
                              No.SJ <?php echo $sj['nosj'];?><br>
                          <?php } ?>
                        </td>
                        <!--<td></td>-->
                        <!--<td><?php echo $product['metode_pembayaran']; ?></td>
                        <td><?php echo $product['metode_pengiriman']; ?></td>-->
                          <!--td><a target="_blank" href="<?php echo $product['hrefsurat']; ?>"><?php echo $product['no_surat']; ?></a></td-->
                        <!--<td><?php echo $product['no_surat']; ?></td>
                        <td><?php echo $product['gudang']; ?></td>
                        <td><?php echo $product['name']; ?></td>
                        <!--<td><?php echo $product['total_pembelian']; ?></td>-->
                        <td><?php echo $product['status_pengiriman']; ?></td>
                        <td>
                          <?php $jiv=0;?>
                          <?php foreach($product['iv'] as $ivs){?>
                              <?php $jiv+=$ivs['qty'];?>
                              No.Inv <?php echo $ivs['no_faktur']?><br>
                          <?php } ?>
                        </td>
                        <td>
                          <?php 
                            if($jiv>0){
                              echo $jiv==$product['quantity']?'Tertagih semua':'Tertagih sebagian';   
                            }
                            
                          ?>
                        </td>
                        <!--<td><?php echo $product['quantity']; ?></td>-->
                        <!--<td><?php echo $product['quantityterima']; ?></td>-->
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="9">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>