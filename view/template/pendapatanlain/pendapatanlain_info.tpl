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
            <h3 class="box-title">Info Pembulatan</h3>
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
                      <td><?php echo $penerimaan['no_pd']; ?></td>
                  </tr>
                  <tr>
                      <td>Jenis Pembayaran:</td>
                      <td><?php echo $penerimaan['jenis'] == 1?'Deposit Customer':'Pembayaran Tunai/COD'; ?></td>
                  </tr>
                  <?php
                  if($penerimaan['jenis'] == 2){
                  ?>
                  <tr>
                      <td>Nomor Invoice:</td>
                      <td><a href="<?php echo $penerimaan['href']; ?>" target="_blank"><?php echo $penerimaan['inv']['no_faktur']; ?></td>
                  </tr>
                  <?php
                  }
                  ?>
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
                        if($penerimaan['metode_pembayaran'] == 5){
                          echo 'Dari Hutang Lain';
                        }
                        if($penerimaan['metode_pembayaran'] == 6){
                          echo 'Biaya';
                        }
                         ?>
                      </td>
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
                      <td>Tanggal Bayar:</td>
                      <td><?php echo date('d/m/y',strtotime($penerimaan['tgl_bayar'])); ?></td>
                  </tr>
                  <?php
                  if($penerimaan['status'] == 2){
                  ?>
                  <tr>
                      <td>Tanggal Diterima:</td>
                      <td><?php echo date('d/m/y',strtotime($penerimaan['tgl_diterima'])); ?></td>
                  </tr>
                  <?php
                  }
                  ?>
                  <tr>
                      <td>Customer:</td>
                      <td><?php echo $penerimaan['customer']; ?></td>
                  </tr>
                  <tr>
                      <td>Alamat:</td>
                      <td><?php echo $penerimaan['alamat']; ?></td>
                  </tr>
                  <tr>
                      <td>Status:</td>
                      <td>
                          <?php
                              if($penerimaan['status'] == 1){
                                echo 'Disimpan';
                              }
                              if($penerimaan['status'] == 2){
                                echo 'Diterima';
                              }
                              if($penerimaan['status'] == 3){
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
                      <td><?php echo $penerimaan['name']; ?></td>
                  </tr>
                  <tr>
                      <td>Jumlah:</td>
                      <td><?php echo $this->currency->format($penerimaan['nominal']+$penerimaan['biaya_bank']); ?> (<?php echo $penerimaan['terbilang']; ?>)</td>
                  </tr>
                  <tr>
                      <td>Biaya Administrasi Bank:</td>
                      <td><?php echo $this->currency->format($penerimaan['biaya_bank']); ?> </td>
                  </tr>
                  <tr>
                      <td>Biaya Lain-lain:</td>
                      <td><?php echo $this->currency->format($penerimaan['biaya_lain']); ?></td>
                  </tr>
                  <tr>
                      <td>Pendapatan Lain-lain:</td>
                      <td><?php echo $this->currency->format($penerimaan['pendapatan_lain']); ?></td>
                  </tr>
                  <tr>
                      <td>Biaya Marketplace:</td>
                      <td><?php echo $this->currency->format($penerimaan['biayamarketplace']); ?></td>
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
                <?php
                if($penerimaan['cetak'] < 1){
                ?>
                <div class="row no-print">
                  <div class="col-xs-12">
                      <a onclick='cetakjakarta(<?php echo json_encode($penerimaan); ?>)' class="btn btn-default"><i class="fa fa-print"></i> Cetak Tangerang</a>
                      <a onclick='cetaksby(<?php echo json_encode($penerimaan); ?>)' class="btn btn-default"><i class="fa fa-print"></i> Cetak Surabaya</a>

                  </div>
                </div>
                <?php
                }
                ?>
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
