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
            <h3 class="box-title">Penerimaan Dana Piutang Lain</h3>
            <div class="button pull-right">
              <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

            </div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Nomor Penerimaan:</td>
                      <td><?php echo $penerimaan['id']; ?></td>
                  </tr>
                  <tr>
                      <td>Jenis Pembayaran:</td>
                      <td>Hutang Lain</td>
                  </tr>
                  <tr>
                      <td>Metode Pembayaran:</td>
                      <td><?php
                        if($penerimaan['metode_pembayaran'] == 1){
                          echo 'Tunai';
                        }
                        if($penerimaan['metode_pembayaran'] == 2){
                          echo 'Transfer Bank';
                        }
                        if($penerimaan['metode_pembayaran'] == 3){
                          echo 'Giro';
                        }
                        if($penerimaan['metode_pembayaran'] == 4){
                          echo 'Cheque';
                        }
                         ?></td>
                  </tr>
                  <?php
                  if($penerimaan['metode_pembayaran'] == 4){
                  ?>
                  <tr>
                      <td>Nomor Giro:</td>
                      <td><?php echo $penerimaan['no_giro']; ?></td>
                  </tr>
                  <?php
                  }
                  ?>
                  <tr>
                      <td>Tanggal:</td>
                      <td><?php echo date('d/m/y',strtotime($penerimaan['tanggal'])); ?></td>
                  </tr>
                  <?php
                  if($penerimaan['status'] == 3){
                  ?>
                  <tr>
                      <td>Tanggal Diterima:</td>
                      <td><?php echo date('d/m/y',strtotime($penerimaan['tgl_diterima'])); ?></td>
                  </tr>
                  <?php
                  }
                  ?>
                  <tr>
                      <td>Status:</td>
                      <td>
                          <?php
                              if($penerimaan['status'] == 1){
                                echo 'Disimpan';
                              }
                              if($penerimaan['status'] == 3){
                                echo 'Diterima';
                              }
                              if($penerimaan['status'] == 2){
                                echo 'Dibatalkan';
                              }
                              if($penerimaan['status'] == 4){
                                echo 'Ditolak';
                              }
                          ?>

                      </td>
                  </tr>
                  <tr>
                      <td>Bank/Kas:</td>
                      <td><?php echo $bank ?></td>
                  </tr>
                  <tr>
                      <td>Jumlah:</td>
                      <td><?php echo $this->currency->format($penerimaan['nominal']); ?> (<?php echo $penerimaan['terbilang']; ?>)</td>
                  </tr>
                  <tr>
                      <td>Keterangan:</td>
                      <td><?php echo $penerimaan['keterangan']; ?></td>
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
$('.sidebar-menu').find('#menu-keuangan').addClass('active');

function cetakjakarta(detail){
  $.ajax({
    url: '<?php echo PRINTER_JKT; ?>cetakpenerimaandana',
    dataType: 'json',
    method:'POST',
    data:JSON.stringify(detail),
    success: function(json) {
      alert("Penerimaan dana berhasil dicetak.");
      /*response($.map(json, function(item) {
        return {
          label: item.nama,
          value: item.atk_id
        }
      }));*/

    }
  });
}
function cetaksby(detail){
  $.ajax({
    url: '<?php echo PRINTER_SBY; ?>cetakpenerimaandana',
    dataType: 'json',
    method:'POST',
    success: function(json) {
      alert("Penerimaan Dana berhasil dicetak.");
      /*response($.map(json, function(item) {
        return {
          label: item.nama,
          value: item.atk_id
        }
      }));*/

    }
  });
}

</script>

<?php echo $footer; ?>
