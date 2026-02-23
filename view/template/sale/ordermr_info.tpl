<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Sales Order Penjualan MR <?php echo $order['no_so']; ?></h3>
            <div class="button pull-right">
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <table class="table">
                <tr>
                  <td>No. TTTK</td>
                  <td><?php echo $order['no_tttk']; ?></td>
                </tr>
                <tr>
                  <td>Gudang</td>
                  <td><?php echo $order['nama']; ?></td>
                </tr>
                <tr>
                  <td>Customer</td>
                  <td><?php echo $order['name']; ?></td>
                </tr>
                <tr>
                  <td>Alamat</td>
                  <td>
                    <?php
                    if($order['pengiriman'] == 2){
                    ?>
                    <strong><?php echo $address['firstname']; ?></strong><br>
                    <?php echo $address['address_1'].' '.$address['address_2']; ?><br>
                    <?php echo $address['city'].', '.$address['zone'].', '.$address['country'].', '.$address['postcode']; ?><br>
                    <?php
                    }
                    ?>
                    Phone: <?php echo !empty($address['company_id'])?$address['company_id']:$order['telephone']; ?><br>
                    Email: <?php echo $order['email']; ?>

                  </td>
                </tr>
                <tr>
                  <td>Metode Pengiriman</td>
                  <td><?php echo $order['pengiriman'] == 1?'Diambil':'Diantar'; ?></td>
                </tr>
                <tr>
                  <td>Sales</td>
                  <td><?php echo $order['sales']; ?></td>
                </tr>
                <tr>
                  <td>Subtotal:</td>
                  <td><?php echo $this->currency->format($order['sub_total']); ?></td>
                </tr>
                <tr>
                  <td>Diskon</td>
                  <td><?php echo $this->currency->format($order['diskon']); ?></td>
                </tr>
                <tr>
                  <td>Pajak:</td>
                  <td><?php echo $this->currency->format($order['pajak']); ?></td>
                </tr>
                <tr>
                  <td>Total:</td>
                  <td><?php echo $this->currency->format($order['total']); ?></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                  <thead>
                  <tr>
                    <th>Qty</th>
                    <th>Produk ID</th>
                    <th>Nama Produk</th>
                    <th>Harga Satuan</th>
                    <th>Pajak</th>
                    <th>Total</th>
                    <th>Referensi</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  foreach($products as $p){
                  ?>
                  <tr>
                    <td><?php echo $p['quantity']; ?></td>
                    <td><?php echo $p['product_id']; ?></td>
                    <td>
                      <?php echo $p['name']; ?><br>

                    </td>
                    <td><?php echo $this->currency->format($p['price']); ?></td>
                  <td><?php echo $this->currency->format($p['pajak']); ?></td>
                    <td><?php echo $this->currency->format($p['total']); ?></td>
                    <td>
                      <?php
                      if(empty($p['jenisref'])){
                      ?>
                        <a target="_blank" href="<?php echo $this->url->link('sale/salesordermr/pembelian', 'token=' . $this->session->data['token'].'&order_id='.$order['id'].'&id='.$p['id'], 'SSL'); ?>" class="badge bg-yellow">Pembelian</a><br>
                        <a target="_blank" href="<?php echo $this->url->link('sale/salesordermr/produksi', 'token=' . $this->session->data['token'].'&order_id='.$order['id'].'&id='.$p['id'], 'SSL'); ?>" class="badge bg-blue">Produksi</a>
                      <?php
                    }else{
                      if(empty($p['referensi2'])){
                        if($p['jenisref'] == 1){
                        ?>
                        <a target="_blank" href="<?php echo $this->url->link('pembelian/permintaanpembelian/tampil', 'token=' . $this->session->data['token'].'&id='.$p['referensi'], 'SSL'); ?>" class="badge bg-yellow">Lihat Referensi</a>
                        <?php
                      }else{
                        ?>
                        <a target="_blank" href="<?php echo $this->url->link('produksi/permintaanproduksi/tampil', 'token=' . $this->session->data['token'].'&id='.$p['referensi'], 'SSL'); ?>" class="badge bg-yellow">Lihat Referensi</a>
                        <?php
                      }


                      }else{

                      }
                    }
                      ?>
                    </td>
                  </tr>
                  <?php
                  }
                  ?>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
</div>
<script>
$('.sidebar-menu').find('#menu-penjualan').addClass('active');
$('.sidebar-menu').find('#menu-penjualan-mr').addClass('active');
</script>

<?php echo $footer; ?>
