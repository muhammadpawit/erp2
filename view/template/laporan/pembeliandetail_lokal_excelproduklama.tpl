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
                    <th>Nama Supplier</th>
                    <th>Tgl.PO</th>
                    <th>No.PO</th>
                    <th>Invoice</th>
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
                      <?php
                        foreach($p['products'] as $product){
                          $po=$this->model_pembelian_pembeliankreditdagang->getPermintaanPembelian(array(),array(),array('id'=>$product['po_id']));
                      ?>
                      <tr >
                        <td><?php echo $p['supplier']; ?></td>
                        <td><?php echo date('d-m-Y',strtotime($po['date_added']));?></td>
                        <td><?php echo $po['no_po'];?></td>
                        <td><?php echo $p['invoice']; ?></td>
                        <td><?php echo $product['quantity'].' '.$product['namasatuan']; ?></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo $this->currency->format($product['price']); ?></td>
                        <td><?php echo $this->currency->format(round(($product['price']/10))); ?></td>
                        <td><?php echo $this->currency->format($product['pajak']); ?></td>
                        <td><?php echo $this->currency->format($product['total']); ?></td>
                      </tr>
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
                    }
                    ?>
                    <tr>
                          <td colspan="6"><b>Total</b></td>
                          <td><b><?php echo $this->currency->format($totalharga) ?></b></td>
                          <td><b><?php echo $this->currency->format($totalppn) ?></b></td>
                          <td><b><?php echo $this->currency->format($totalpajak) ?></b></td>
                          <td><b><?php echo $this->currency->format($totaljumlah) ?></b></td>
                      </tr>
                </table>