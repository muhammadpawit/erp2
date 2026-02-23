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
            <h3 class="box-title">Proses Transfer Aset -> Produk Dagang</h3>
            <div class="button pull-right">
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Tanggal Permintaan:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['tanggal'])); ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal Proses:</td>
                      <td><?php echo !empty($permintaan['tanggal_disetujui'])?date('d/m/y',strtotime($permintaan['tanggal_disetujui'])):'Belum Diproses' ?></td>
                  </tr>
                  <tr>
                     <td>Gudang:</td>
                     <td><?php echo $permintaan['nama']; ?></td>
                 </tr>

                   <tr>
                      <td>Status:</td>
                      <td><?php
                        if($permintaan['status'] == 3){
                          echo 'Ditolak/Dibatalkan';
                        }
                        if($permintaan['status'] == 1){
                          echo 'Menunggu Persetujuan';
                        }
                        if($permintaan['status'] == 2){
                          echo 'Selesai Diproses';
                        }



                      ?></td>
                  </tr>




                </table>
                <table class="table">
                    <thead>
                      <th>Nama Aset</th>
                      <th>Nama Produk</th>

                    </thead>
                    <tbody>

                      <tr>
                        <td><?php echo empty($aset['name'])?$aset['no_tabung']:$aset['name']; ?></td>
                        <td><?php echo $product['name']; ?></td>

                      </tr>

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
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-transferaset').addClass('active');
</script>

<?php echo $footer; ?>
