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
            <h3 class="box-title">Permintaan Pembelian</h3>
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
                      <td>Surat Permintaan Pembelian:</td>
                      <td><?php echo $permintaan['pno_surat'] ?></td>
                  </tr>
                   <tr>
                      <td>Tujuan:</td>
                      <td><?php echo $permintaan['tujuan'] ?></td>
                  </tr>
                  <tr>
                     <td>Jumlah:</td>
                     <td><?php echo $this->currency->format($permintaan['jumlah']) ?></td>
                 </tr>
                   <tr>
                      <td>Status:</td>
                      <td><?php echo ($permintaan['status'] == 3)?'Ditolak/DIbatalkan':($permintaan['status'] == 1?'Disimpan':'Disetujui'); ?></td>
                  </tr>




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
