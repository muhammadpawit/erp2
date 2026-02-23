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
            <h3 class="box-title">Transfer Item</h3>
            <div class="button pull-right">
              <!-- <a onclick='cetakjakarta(<?php echo json_encode($detailtransfer); ?>)' ><button type="button" class="btn btn-primary">Cetak Kantor Jakarta</button></a>
              <a onclick='cetaksurabaya(<?php echo json_encode($detailtransfer); ?>)' ><button type="button" class="btn btn-primary">Cetak Kantor Surabaya</button></a> -->
              <a href="<?php echo $cetakjakarta ?>" target="_blank"><button type="button" class="btn btn-primary">Cetak Kantor Jakarta</button></a>
              <a href="<?php echo $cetakjakarta ?>" target="_blank"><button type="button" class="btn btn-primary">Cetak Kantor Surabaya</button></a>

                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-danger">Kembali</button></a>

            </div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr>
                      <td>Nomor Surat Jalan:</td>
                      <td><?php echo $transfer['detail']['invoice_no'] ?></td>
                  </tr>
                  <tr>
                      <td>Nomor PO:</td>
                      <td><?php echo empty($transfer['detail']['no_po'])?'-':$transfer['detail']['no_po'];?></td>
                  </tr>
                  <tr>
                      <td>Nomor Dokumen:</td>
                      <td><?php echo empty($transfer['detail']['no_dokumen'])?'-':$transfer['detail']['no_dokumen'];?></td>
                  </tr>
                  <tr>
                      <td>Tanggal:</td>
                      <td><?php echo date('d F Y',strtotime($transfer['detail']['date_added'])) ?></td>
                  </tr>
                   <tr>
                      <td>Gudang Asal:</td>
                      <td><?php echo $transfer['detail']['asal'] ?></td>
                  </tr>
                   <tr>
                      <td>Gudang Tujuan:</td>
                      <td><?php echo $transfer['detail']['gudang_tujuan'] ?></td>
                  </tr>
                   <tr>
                      <td>Status:</td>
                      <td><?php echo ($transfer['detail']['status'] == 0)?'Barang Belum diterima':($transfer['detail']['status'] == 1?'Barang telah diterima':($transfer['detail']['status'] == 2?'Terdapat Selisih':'Transfer dibatalkan')); ?></td>
                  </tr>

                   <tr>
                      <td>Total Pengiriman Barang:</td>
                      <td><?php echo $transfer['detail']['jenis'] == 2?$transfer['detail']['total'].' Poin':$this->currency->format($transfer['detail']['total']) ?> (<?php echo $transfer['detail']['qtykirim']; ?> pcs)</td>
                  </tr>
                  <tr>
                     <td>Total Diterima:</td>
                     <td><?php echo $transfer['detail']['jenis'] == 2?$transfer['detail']['total'].' Poin':$this->currency->format($transfer['detail']['totalterima']) ?> (<?php echo $transfer['detail']['qtyterima']; ?> pcs)</td>
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
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-transfer-item').addClass('active');
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
