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
            <h3 class="box-title">Pengiriman Barang Pameran</h3>
            <div class="button pull-right">
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Nomor Surat Jalan:</td>
                      <td><?php echo $transfer['detail']['invoice_no'] ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal:</td>
                      <td><?php echo date('d F Y',strtotime($transfer['detail']['date_added'])) ?></td>
                  </tr>
                   <tr>
                      <td>Gudang Asal:</td>
                      <td><?php echo $transfer['detail']['asal'] ?></td>
                  </tr>
                   <tr>
                      <td>PAmeran Tujuan:</td>
                      <td><?php echo $transfer['detail']['gudang_tujuan'] ?></td>
                  </tr>
                   <tr>
                      <td>Status:</td>
                      <td><?php echo ($transfer['detail']['status'] == 0)?'Barang Belum diterima':($transfer['detail']['status'] == 1?'Barang telah diterima':($transfer['detail']['status'] == 2?'Terdapat Selisih':'Transfer dibatalkan')); ?></td>
                  </tr>

                   <tr>
                      <td>Total Pengiriman Barang:</td>
                      <td><?php echo $this->currency->format($transfer['detail']['total']) ?> (<?php echo $transfer['detail']['qtykirim']; ?> pcs)</td>
                  </tr>
                  <tr>
                     <td>Total Diterima:</td>
                     <td><?php echo $this->currency->format($transfer['detail']['totalterima']) ?> (<?php echo $transfer['detail']['qtyterima']; ?> pcs)</td>
                 </tr>


                </table>
                <table class="table">
                    <thead>
                      <th>Nama Produk</th>
                      <th>Ukuran</th>
                      <th>Quantity Kirim</th>
                      <th>Quantity Terima</th>
                      <th>Status</th>
                    </thead>
                    <tbody>
                      <?php
                      foreach($transfer['products'] as $p){
                      ?>
                      <tr>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php echo $p['option']; ?></td>
                        <td><?php echo $p['quantity']; ?></td>
                        <td><?php echo $p['quantity_actual']; ?></td>
                        <td><?php echo $p['status'] == 0?'Belum diterima':($p['status'] == 1?'Sudah diterima':($p['status'] == 2?'Terdapat Selisih':'Dibatalkan')); ?></td>
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
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-pengiriman-barang').addClass('active');
$('.sidebar-menu').find('#menu-pengiriman-barang-pameran').addClass('active');

</script>

<?php echo $footer; ?>
