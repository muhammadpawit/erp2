<?php echo $header; ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
  <form method="POST" action="<?php echo $action?>" id="form">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Pengiriman Barang</h3>
            <div class="button pull-right">
                  <a onclick="$('#form').submit();" ><button type="button" class="btn btn-primary">Simpan</button></a>
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Nomor Invoice:</td>
                      <td><?php echo $order['no_invoice'] ?>
                        <input type="hidden" name="pembelian_kredit_id" value="<?php echo $permintaan['id'] ?>">
                      </td>
                  </tr>
                  <tr>
                      <td>No. Surat Jalan:</td>
                      <td><input type="text" required name="no_suratjalan" value=""></td>
                  </tr>
                   <tr>
                      <td>No. Polisi:</td>
                      <td><input type="text" name="no_pol" value=""></td>
                  </tr>

            </table>
                <table class="table">
                    <thead>
                      <th>Nama Produk</th>
                      <th>Quantity</th>
                      <th>Quantity Kirim</th>
                      <th>Quantity Telah Kirim</th>

                    </thead>
                    <tbody>
                      <?php
                      $i=1;
                      foreach($products as $p){
                      ?>
                      <tr>
                        <td><?php echo $p['name']; ?>
                          <input type="hidden" name="products[<?php echo $i; ?>][product_id]" value="<?php echo $p['product_id']; ?>">
                        </td>
                        <td><?php echo $p['quantity']; ?></td>
                        <td><?php echo $p['quantityterima']; ?></td>
                        <td>
                          <?php
                          $sisa= $p['quantity']-$p['quantityterima'];
                          ?>
                          <input type="text" name="products[<?php echo $i; ?>][quantityterima]" value="">
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
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  </section>
</div>
</div>
<script>
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-kredit').addClass('active');


</script>

<?php echo $footer; ?>
