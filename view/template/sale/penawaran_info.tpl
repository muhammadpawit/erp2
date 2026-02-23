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
            <h3 class="box-title">Penawaran Harga Jual</h3>
            <div class="button pull-right">
              <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

            </div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Nomor Penawaran:</td>
                      <td><?php echo $order['no_so']; ?></td>
                  </tr>
                  <tr>
                      <td>Customer:</td>
                      <td><?php echo $order['name']; ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal:</td>
                      <td><?php echo date('d F Y',strtotime($order['date_added'])) ?></td>
                  </tr>
                   <tr>
                      <td>Gudang:</td>
                      <td><?php echo $order['nama']; ?></td>
                  </tr>
                  <tr>
                     <td>Subtotal:</td>
                     <td><?php echo $this->currency->format($order['sub_total']); ?></td>
                 </tr>
                 <tr>
                    <td>Diskon:</td>
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
                <table class="table">
                    <thead>
                      <th>Nama Produk</th>
                      <th>Quantity</th>
                      <th>Harga Satuan</th>
                      <th>Diskon</th>
                      <th>Pajak</th>
                      <th>Total</th>
                    </thead>
                    <tbody>
                      <?php
                      foreach($products as $p){
                      ?>
                      <tr>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php echo $p['quantity']; ?></td>
                        <td><?php echo $this->currency->format($p['price']); ?></td>
                        <td><?php echo $this->currency->format($p['diskon']); ?></td>
                        <td><?php echo $this->currency->format($p['pajak']); ?></td>
                        <td><?php echo $this->currency->format($p['total']); ?></td>

                      </tr>
                      <?php
                      }
                      ?>
                    </tbody>
                </table>

              </div>
            </div>

          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">

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
$('.sidebar-menu').find('#menu-penawaran-harga').addClass('active');
</script>

<?php echo $footer; ?>
