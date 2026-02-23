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
            <h3 class="box-title">Kartu Stok Tabung</h3>
            <div class="button pull-right">
								<a href="<?php echo $cancel; ?>" ><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">


            <div class="row">
              <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <?php
                        if($tabung['pemilik'] == 1){
                        ?>
                        <th class="left">Tanggal</th>
                        <th class="left">Jenis Transaksi</th>
                        <!--th class="left">Tanggal Pengembalian</th-->

          				      <th class="left">Customer</th>
                        <th class="left">Biaya Sewa</th>
                        <?php
                        }
                        ?>
                        <!--th class="left">Tanggal Isi Ulang</th-->
                        <th class="left">Keterangan</th>

                        <th class="right">Referensi</th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($kartustoks) { ?>
                      <?php foreach ($kartustoks as $product) { ?>

                      <tr>
                        <?php
                        if($tabung['pemilik'] == 1){
                        ?>
                        <td class="left"><?php echo $product['tglpeminjaman'];
                          ?></td>
                        <td>
                          <?php 
                          if($product['jenistransaksi'] == 1){
                              echo 'Peminjaman Tabung';
                          }
                          if($product['jenistransaksi'] == 2){
                              echo 'Pembatalan Peminjaman Tabung';
                          }
                          if($product['jenistransaksi'] == 3){
                              echo 'Retur Tabung';
                          }
                          if($product['jenistransaksi'] == 4){
                              echo 'Pengembalian Peminjaman Tabung';
                          }
                          if($product['jenistransaksi'] == 5){
                              echo 'Pengisian Gas';
                          }
                          ?>
                        </td>
                        <!--td class="left"><?php echo $product['tglpengembalian']; ?></td-->

          			          <td class="left"><?php echo $product['customer']; ?></td>
                          <td class="left"><?php echo $product['biayasewa']; ?></td>
                          <?php
                          }
                          ?>
                          <!--td class="left"><?php echo $product['tglisiulang']; ?></td-->
                          <td class="left"><?php echo $product['ket']; ?></td>

                          <td class="left"><a href="<?php echo $product['urlref'];?>" target="_blank"><?php echo $product['invoice']; ?></a></td>

                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="7">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>

              </div>
            </div>

          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">
                <div class="pull-right"><?php echo $pagination; ?></div>
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
$('.sidebar-menu').find('#menu-persediaan-tabungmp').addClass('active');

</script>

<script>
$(function(){
  $('#tanggal').datepicker({dateFormat: 'yy-mm-dd'});
})
</script>
<?php echo $footer; ?>
