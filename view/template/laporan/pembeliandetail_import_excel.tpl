<?php
if($this->user->getUsername()=="pawit"){

}else{
  header("Content-type: application/vnd-ms-excel");
  header("Content-Disposition: attachment; filename=laporan_pembelian_detail_import_dengan_produk.xls");
}
?>
<html>
<head>
<style>
table{width:100%;border-collapse:collapse}
</style>
</head>
<body>
                <table border="1">
                  <thead>
                    <th class="left">Nama Supplier</th>    
                    <th>Tgl.SJ</th>
                    <th>No.SJ</th>
                    <th>No.Dokumen</th> 
                    <th>Tgl.PO</th>
                    <th>No.PO</th>
                    <th>Tgl Invoice</th>
                    <th>Tgl Lunas</th>
                    <th>Invoice</th>
                    <th>Tgl jatuh tempo</th>
                    <th>Quantity</th>
                    <th>Nama Barang</th>
                    <th>Harga Satuan</th>
                    <th>PPN</th>
                    <th>Nilai Persediaan</th>
                    <th>Total</th>
                    <th>Status</th>
                  </thead>
                  <?php 
                    $qty=0;
                    $harga=0;
                    $total=0;
                    $totalall=0;
                  ?>
                  <?php if ($penjualans) { ?>
                  <?php foreach ($penjualans as $p) {
                    //$totalpersediaan=0;
                          if($this->user->getUsername()=="pawit"){
                            //echo "<pre>";print_r($p);
                          }
                    ?>
                  <tbody class="list-product" id="list<?php echo $p['id']; ?>">
                      <?php
                        foreach($p['products'] as $product){
                          $po=$this->model_pembelian_pembelianimport->getPermintaanPembelian(array(),array(),array('id'=>$product['po_id']));
                          $sj=$this->model_pembelian_pembelianimport->getsjpembelian($p['id']);
                      ?>
                      <tr >
                        <td><?php echo $p['supplier']; ?></td>
                        <td><?php echo date('d/m/Y',strtotime($sj['tgl_terima']));?></td>
                        <td><?php echo $sj['no_suratjalan'];?></td>
                        <td><?php echo $sj['no_dokumen'];?></td>
                        <td><?php echo date('d-m-Y',strtotime($po['date_added']));?></td>
                        <td><?php echo $po['no_po'];?></td>
                        <td><?php echo $p['tgl_inv']; ?></td>
                        <td><?php echo $p['tgl_lunas']; ?></td>
                        <td><?php echo $p['invoice']; ?></td>
                        <td><?php echo $p['tgl_jatuhtempo']; ?></td>
                        <td align="center"><?php echo $product['quantity'].' '.$product['namasatuan']; ?></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td><?php echo '$'.number_format($product['price'],2); ?></td>
                        <td><?php echo '$'.number_format($product['pajak'],2); ?></td>
                        <td><?php 
                        $biaya=0;
                        if($p['totalbiaya'] > 0){
                          $biaya=((($product['price'] + $product['ppn'])*$p['kursdatang'])/$p['plaintotal'])*$p['totalbiaya'];
                        }
                        
                        $harga=(($product['price'] + $product['ppn'])*$p['kursdatang'])+$biaya;
                       //$totalpersediaan =$harga*$product['quantityterima'];
                       $totalall +=$harga*$product['quantityterima'];
                        echo $this->currency->format(($harga*$product['quantityterima']),2); ?></td>
                        <td><?php echo '$'.number_format($product['total'],2); ?></td>
                        <td><?php echo $p['status']; ?></td>
                      </tr>
                    <?php
                      $qty+=($product['quantity']);
                      $harga+=($product['price']);
                      $total+=($product['total']);
                    }
                    ?>
                  </tbody>
                    <?php
                    }
                    }
                    ?>
                    <tr>
                      <td colspan="10"><b>Total</b></td>
                      <td align="center"><b><?php echo $qty ?></b></td>
                      <td></td>
                      <td><?php echo '$'.number_format($harga,2); ?></td>
                      <td></td>
                      <td><b><?php echo $this->currency->format($totalall,2); ?></b></td>
                      <td><b><?php echo '$'.number_format($total,2); ?></b></td>
                      <td></td>
                    </tr>
                </table>

</body>
</html>