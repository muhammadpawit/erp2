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
                      <td>Tanggal:</td>
                      <td><?php echo date('d F Y',strtotime($permintaan['date_added'])) ?></td>
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
                      <td>Tujuan Pembelian:</td>
                      <td><?php echo $permintaan['tujuan_pembelian'] ?></td>
                  </tr>
                  <tr>
                     <td>Jenis Pembelian:</td>
                     <td><?php echo $permintaan['jenis_pembelian'] == 1?'Tunai':($permintaan['jenis_pembelian'] == 2?'Kredit':'Import'); ?></td>
                 </tr>
                 <tr>
                    <td>Jenis Barang:</td>
                    <td><?php echo $permintaan['jenis_barang'] == 1?'Bahan Baku':($permintaan['jenis_barang'] == 2?'Produk Dagang':($permintaan['jenis_barang'] == 3?'ATK':'Aset')); ?></td>
                </tr>
                   <tr>
                      <td>Status:</td>
                      <td><?php echo ($permintaan['status'] == 3)?'Ditolak/DIbatalkan':($permintaan['status'] == 1?'Disimpan':'Disetujui'); ?></td>
                  </tr>




                </table>
                <table class="table">
                    <thead>
                      <th>Nama Produk</th>
                      <th>Spesifikasi</th>
                      <th>Quantity Kirim</th>
                      <th>Keterangan</th>

                    </thead>
                    <tbody>
                      <?php
                      foreach($products as $p){
                      ?>
                      <tr>
                        <td><?php echo $p['product_name']; ?></td>
                        <td><?php echo $p['spesifikasi']; ?></td>
                        <td><?php echo $p['quantity']; ?></td>
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
$('.sidebar-menu').find('#menu-surat-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-permintaan-pembelian').addClass('active');

</script>

<?php echo $footer; ?>
