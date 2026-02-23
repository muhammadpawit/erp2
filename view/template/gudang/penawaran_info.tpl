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
            <h3 class="box-title">Penawaran Harga Jual</h3>
            <div class="button pull-right">
              <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

            </div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Nomor Penawaran:</td>
                      <td><?php echo $order['no_so']; ?></td>
                  </tr>
                  <tr>
                      <td>Tanggal:</td>
                      <td><?php echo date('d F Y',strtotime($order['date_added'])) ?></td>
                  </tr>
                   <tr>
                      <td>Gudang:</td>
                      <td><?php echo $order['nama']; ?></td>
                  </tr>
                   
                </table>
                <table class="table">
                    <thead>
                      <th>Nama Produk</th>
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
$('.sidebar-menu').find('#menu-transfer-gudang').addClass('active');
$('.sidebar-menu').find('#menu-transfer-produk').addClass('active');
function cetakjakarta(detail){
  $.ajax({
    url: '<?php echo PRINTER_JKT; ?>cetaktransfer',
    dataType: 'json',
    method:'POST',
    data:JSON.stringify(detail),
    success: function(json) {
      alert("Surat jalan berhasil dicetak.");
      /*response($.map(json, function(item) {
        return {
          label: item.nama,
          value: item.atk_id
        }
      }));*/

    }
  });
}
function cetaksurabaya(detail){
  $.ajax({
    url: '<?php echo PRINTER_SBY; ?>cetaktransfer',
    dataType: 'json',
    method:'POST',
    success: function(json) {
      alert("Surat jalan berhasil dicetak.");
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
