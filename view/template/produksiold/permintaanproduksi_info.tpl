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
            <h3 class="box-title">Permintaan Produksi</h3>
            <div class="button pull-right">
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Nomor Surat:</td>
                      <td><?php echo $permintaan['no_surat'] ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal Pesan:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['date_added'])) ?></td>
                  </tr>
                  <tr>
                      <td>Jam Pesan:</td>
                      <td><?php echo date('H:i:s',strtotime($permintaan['date_added'])) ?></td>
                  </tr>
                  <tr>
                     <td>Gudang:</td>
                     <td><?php echo !empty($gudang)?$gudang['nama']:'Tanpa Gudang'; ?></td>
                 </tr>
                   <tr>
                      <td>Divisi Asal:</td>
                      <td><?php echo $permintaan['name'] ?></td>
                  </tr>
                   <tr>
                      <td>Keterangan:</td>
                      <td><?php echo $permintaan['keterangan'] ?></td>
                  </tr>
                  <tr>
                     <td>Jenis Produksi:</td>
                     <td><?php echo $permintaan['jenis_produksi'] == 1?'MR':($permintaan['jenis_produksi'] == 2?'Stok':'MP'); ?></td>
                 </tr>
                 <?php
                 if($permintaan['jenis_produksi'] == 1){
                  ?>
                  <tr>
                     <td>No. SO:</td>
                     <td>
                       <?php
                       echo $permintaan['detailcust']['no_so']; ?></td>
                 </tr>
                 <tr>
                    <td>Gudang:</td>
                    <td>
                      <?php
                      echo $permintaan['detailcust']['nama']; ?></td>
                </tr>
                <tr>
                   <td>Telephone:</td>
                   <td>
                     <?php
                     echo $permintaan['detailcust']['telephone']; ?></td>
               </tr>
               <tr>
                  <td>Email:</td>
                  <td>
                    <?php
                    echo $permintaan['detailcust']['email']; ?></td>
              </tr>
                  <tr>
                     <td>Nama Customer:</td>
                     <td>
                       <?php
                       echo $permintaan['detailcust']['name']; ?></td>
                 </tr>
                  <?php
                 }
                 ?>
                  <tr>
                      <td>Status:</td>
                      <td>
                        <?php
                        if($permintaan['status'] == 1){
                          echo 'Menunggu';
                        }
                        if($permintaan['status'] == 2){
                          echo 'Proses Produksi';
                        }
                        if($permintaan['status'] == 3){
                          echo 'Dibatalkan';
                        }
                        if($permintaan['status'] == 4){
                          echo 'Partial';
                        }
                        if($permintaan['status'] == 5){
                          echo 'Produksi Selesai';
                        }
                        ?>
                      </td>
                  </tr>




                </table>
                <table class="table">
                    <thead>
                      <th>Jenis Gas</th>
                      <th>Ukuran Tabung</th>
                      <th>Quantity Pesan</th>
                      <th>Quantity Proses</th>
                      <th>Keterangan</th>

                    </thead>
                    <tbody>
                      <?php
                      foreach($products as $p){
                      ?>
                      <tr>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php echo $p['namaukuran']; ?></td>
                      <td><?php echo $p['quantity']; ?></td>
                        <td><?php echo empty($p['quantity_proses'])?0:$p['quantity_proses']; ?></td>
                        <td><?php echo $p['keterangan']; ?></td>
                      </tr>
                      <?php
                      }
                      ?>
                    </tbody>
                </table>
                <div class="callout callout-success lead">
                  <h4>Detail Proses</h4>

                </div>
                <table class="table">
                    <thead>
                      <th>Tanggal</th>
                      <th>Quantity Proses</th>
                      <th>Quantity Hasil</th>
                      <th>Keterangan</th>

                    </thead>
                    <tbody>
                      <?php
                      foreach($permintaan['detailproses'] as $p){
                      ?>
                      <tr>
                        <td><?php echo date('d/m/y',strtotime($p['tanggal'])); ?></td>
                        <td><?php echo $p['quantityproses']; ?></td>
                        <td><?php echo $p['quantityhasil']; ?></td>
                        <td><?php echo $p['keterangan']; ?></td>
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
$('.sidebar-menu').find('#menu-produksi').addClass('active');

</script>

<?php echo $footer; ?>
