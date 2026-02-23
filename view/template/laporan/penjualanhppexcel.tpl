<?php
$waktu = date('d F Y');
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Penjualan_".date('d-m-Y',strtotime($_REQUEST['filter_date_start']))."_sd_".date('d-m-Y',strtotime($_REQUEST['filter_date_end'])).".xls");
?>
				<table class="table table-bordered" border="1">
                    <thead>
                      <tr>
                        <th class="left">Gudang</th>
                        <th class="left">Nama Produk</th>
          				<th class="right">Qty Terjual</th>
                        <th class="right">Harga</th>
                        <th class="right">Pajak</th>
                        <th class="right">HPP</th>
                        <th class="right">Total</th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($products) { ?>
                      <?php foreach ($products as $product) { ?>
                      <tr class="product" id="product-<?php echo $product['product_id']?>-<?php echo $product['gudang_id']; ?>" data-id="<?php echo $product['product_id']?>-<?php echo $product['gudang_id']; ?>" data-nama="<?php echo $product['name']?>" data-gudang="<?php echo $product['nama']?>">
                        <td class="left"><?php echo $product['nama'];
                          ?></td>
                        <td class="left"><?php echo $product['name']; ?></td>
          			            <td class="right"><?php echo $product['quantity']; ?></td>
                            <td class="right"><?php echo $product['price']; ?></td>
                            <td class="right"><?php echo $product['pajak']; ?></td>
                            <td class="right"><?php echo $product['net_cost']; ?></td>
                            <td class="right"><?php echo $product['total']; ?></td>

                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="10">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                </table>
                <div class="callout callout-success lead">
                  <h4>Total Penjualan</h4>

                </div>				
				<table class="table table-bordered" border="1">
                  <thead>
                    <th class="left" colspan="2">Gudang</th>
                    <th class="right">Qty Terjual</th>
                    <th class="right">Harga</th>
                    <th class="right">Pajak</th>
                    <th class="right">Total</th>
                  </thead>
                  <tbody>
                    <?php
                    $qty=0;
                    $price=0;
                    $pajak=0;
                    $total=0;
                    foreach ($penjualangudang as $product) {
                      $qty += $product['quantity'];
                      $price += $product['price'];
                      $pajak += $product['pajak'];
                      $total += $product['total'];
                      ?>
                      <tr>
                      <td class="left"  colspan="2"><?php echo $product['nama']; ?></td>
                          <td class="right"><?php echo $product['quantity']; ?></td>
                          <td class="right"><?php echo $this->currency->format($product['price']); ?></td>
                          <td class="right"><?php echo $this->currency->format($product['pajak']); ?></td>
                          <td class="right"><?php echo $this->currency->format($product['total']); ?></td>
                      </tr>
                    <?php
                    }
                    ?>
                    <tr>
                      <td colspan="2"><b>Total</b></td>
                      <td class="right"><b><?php echo $qty; ?></b></td>
                      <td class="right"><b><?php echo $this->currency->format($price); ?></b></td>
                      <td class="right"><b><?php echo $this->currency->format($pajak); ?></b></td>
                      <td class="right"><b><?php echo $this->currency->format($total); ?></b></td>
                    </tr>
                  </tbody>
                </table>				
<?php echo $footer; ?>
