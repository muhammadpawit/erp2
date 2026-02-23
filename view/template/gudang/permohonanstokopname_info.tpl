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
            <h3 class="box-title">Permohonan Stok Opname</h3>
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
                      <td>Tanggal:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['tanggal'])) ?></td>
                  </tr>
                  <tr>
                     <td>Gudang:</td>
                     <td><?php echo !empty($gudang)?$gudang['nama']:'Tanpa Gudang'; ?></td>
                 </tr>
                   <tr>
                      <td>Keterangan:</td>
                      <td><?php echo $permintaan['keterangan'] ?></td>
                  </tr>

                   <tr>
                      <td>Status:</td>
                      <td><?php echo ($permintaan['status'] == 3)?'Ditolak/Dibatalkan':($permintaan['status'] == 1?'Menunggu Persetujuan':'Selesai Diproses'); ?></td>
                  </tr>




                </table>
                <table class="table">
                    <thead>
                      <th>Nama Produk</th>
                      <th>Qty Tercatat</th>
                      <th>Qty Hilang</th>
                      <th>Qty Rusak</th>
                      <th>Qty Tersedia</th>
                      <th>Keterangan</th>

                    </thead>
                    <tbody>
                      <?php
                      foreach($products as $p){
                      ?>
                      <tr>
                        <td><?php echo $p['name']; ?></td>
                        <td><?php echo $p['qtytercatat']; ?></td>
                        <td><?php echo $p['qtyhilang']; ?></td>
                        <td><?php echo $p['qtyrusak']; ?></td>
                        <td><?php echo $p['qtytersedia']; ?></td>
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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-produk').addClass('active');

</script>

<?php echo $footer; ?>
