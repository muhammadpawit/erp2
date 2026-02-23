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
            <h3 class="box-title">Detail Buka Tutup Produksi</h3>
            <div class="button pull-right">
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Tanggal Mulai:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['tanggalmulai'])) ?></td>
                  </tr>
                  <tr>
                      <td>Jam Pesan:</td>
                      <td><?php echo date('H:i:s',strtotime($permintaan['tanggalmulai'])) ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal Selesai:</td>
                      <td><?php echo empty($permintaan['tanggalselesai'])?'Produksi belum ditutup':date('d F Y',strtotime($permintaan['tanggalselesai'])) ?></td>
                  </tr>
                  <tr>
                      <td>Jam Selesai:</td>
                      <td><?php echo empty($permintaan['tanggalselesai'])?'Produksi belum ditutup':date('H:i:s',strtotime($permintaan['tanggalselesai'])) ?></td>
                  </tr>
                  <tr>
                     <td>Gudang:</td>
                     <td><?php echo !empty($gudang)?$gudang['nama']:'Tanpa Gudang'; ?></td>
                 </tr>
                   <tr>
                      <td>Keterangan:</td>
                      <td><?php echo $permintaan['keterangan'] ?></td>
                  </tr>

                </table>

                <div class="callout callout-success lead">
                  <h4>Level Bahan Baku</h4>

                </div>
                <table class="table">
                    <thead>
                      <th>Bahan Baku</th>
                      <th>Level Awal</th>
                      <th>Level Akhir</th>
                      <th>Quantity Awal</th>
                      <th>Quantity Akhir</th>


                    </thead>
                    <tbody>
                      <?php
                      foreach($products as $p){
                      ?>
                      <tr>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php echo $p['levelawal']; ?></td>
                        <td><?php echo $p['levelakhir']; ?></td>
                        <td><?php echo $p['qtyawal']; ?></td>
                        <td><?php echo $p['qtyakhir']; ?></td>
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
