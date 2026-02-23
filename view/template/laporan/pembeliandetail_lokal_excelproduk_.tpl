<?php

if($this->user->getUsername()=="pawit"){
  
}else{
  header("Content-type: application/vnd-ms-excel");
  header("Content-Disposition: attachment; filename=Laporan_pembelian_detail_lokal_with_Produk.xls");  
}

?>
<style>
table{border-collapse:collapse;width:100%;}
</style>
                  <table border="1">
                  <thead>
                    <th>Tgl</th>
                    <th>No.SJ/Invoice</th>
                    <th>No.Dokumen</th>
                    <th>Tgl.PO</th>
                    <th>No.PO</th>
                    <th>Quantity</th>
                    <th>Nama Barang</th>
                    <th>Harga Satuan</th>
                    <th>PPN</th>
                    <th>Total Pajak</th>
                    <th>Total</th>
                  </thead>
                   <?php
                        $totaljumlah=0;
                        $totalbayar=0;
                        $totalharga=0;
                        $totalppn=0;
                        $totalpajak=0;
                      ?>  
                  <?php if ($penjualans) { ?>
                  <?php foreach ($penjualans as $p) {
                    ?>
                  <tbody class="list-product" id="list<?php echo $p['id']; ?>">
                      <tr>
                        <td colspan="11">Rincian Invoice</td>
                      </tr>
                      <?php
                          if($this->user->getUsername()=="pawit"){
                            //echo "<pre>";print_r($p);
                          }
                        foreach($p['products'] as $product){
                          $po=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelian(array(),array(),array('id'=>$product['po_id']));
                          $sjp=$this->model_pembelian_pembeliankreditdagang->getsjpembelian($product['po_product_id']);
                      ?>
                      <tr >
                        <td><?php echo $p['tgl_inv'];?></td>
                        <td><?php echo $p['invoice'];?></td>
                        <td><?php echo $p['no_dokumen'];?></td>
                        <td><?php echo date('d-m-Y',strtotime($po['date_added']));?></td>
                        <td><?php echo $po['no_po'];?></td>
                        <td><?php echo $product['quantity'].' '.$product['namasatuan']; ?></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo $this->currency->format($product['price']); ?></td>
                        <td><?php echo $this->currency->format(round(($product['price']/10))); ?></td>
                        <td><?php echo $this->currency->format($product['pajak']); ?></td>
                        <td><?php echo $this->currency->format($product['total']); ?></td>
                      </tr>
                    <?php
                    }
                    ?>

                      <tr>
                        <td colspan="11">Rincian Terima Barang</td>
                      </tr>
                    <?php
                        foreach($p['products'] as $product){
                          $po=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelian(array(),array(),array('id'=>$product['po_id']));
                          $sjp=$this->model_pembelian_pembeliankreditdagang->getsjpembelian($product['po_product_id']);
                      ?>
                      <tr >
                        <?php if(!empty($filter_date_startsj)){?>
                        <td><small><?php echo date('d-m-Y',strtotime($product['tgl_terima']));?></small></td>
                        <?php }else{ ?>
                        <td><small><?php echo date('d-m-Y',strtotime($sjp['tgl_terima']));?></small></td>
                        <?php } ?>
                        <td><?php echo $sjp['no_suratjalan'];?></td>
                        <td><?php echo $sjp['no_dokumen'];?></td>
                        <td><?php echo date('d-m-Y',strtotime($po['date_added']));?></td>
                        <td><?php echo $po['no_po'];?></td>
                        <td><?php echo $sjp['quantity'].' '.$product['namasatuan']; ?></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo $this->currency->format($product['price']); ?></td>
                        <td><?php //echo $this->currency->format(round(($product['price']/10))); ?></td>
                        <td><?php //echo $this->currency->format($product['pajak']); ?></td>
                        <td><?php echo $this->currency->format( ($product['price']*$sjp['quantity']) ); ?></td>
                      </tr>
                    <?php
                    }
                    ?>

                      <?php 
                          $totaljumlah+=($product['total']);
                          $totalharga+=($product['price']);
                          $totalppn+=($product['price']/10);
                          $totalpajak+=($product['pajak']);
                      ?>
                    <?php
                    }
                    ?>
                  </tbody>
                    <?php
                    
                    }
                    ?>
                      <!--
                      <tr>
                          <td colspan="6"><b>Total</b></td>
                          <td><b><?php echo $this->currency->format($totalharga) ?></b></td>
                          <td><b><?php echo $this->currency->format($totalppn) ?></b></td>
                          <td><b><?php echo $this->currency->format($totalpajak) ?></b></td>
                          <td><b><?php echo $this->currency->format($totaljumlah) ?></b></td>
                      </tr>-->
                </table>