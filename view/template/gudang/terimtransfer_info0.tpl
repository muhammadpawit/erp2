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
            <h3 class="box-title">Terima Transfer</h3>
            <div class="button pull-right">
              <a onclick="simpan()" ><button type="button" class="btn btn-primary">Simpan</button></a>
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
                      <th>Ukuran</th>
                      <th>Quantity Kirim</th>
                      <th>Quantity Terima</th>
                      <th>Status</th>
                    </thead>
                    <tbody>
                      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                      <?php
                      $i=1;
                      foreach($transfer['products'] as $p){
                      ?>
                      <tr>
                        <td><?php echo $p['name']; ?>
                          <input type="hidden" name="products[<?php echo $i; ?>][order_product_id]" value="<?php echo $p['order_product_id']?>">
                        </td>
                        <td><?php echo $p['option']; ?></td>
                        <td><?php echo $p['quantity']; ?></td>
                        <td><?php
                          $t=$p['quantity'] - $p['quantity_actual'];
                          if($t > 0){
                          ?>
                          <select name="products[<?php echo $i;?>][quantity_actual]" class="form-control">
                            <?php
                            for($y=$t;$y>=0;$y--){
                            ?>
                              <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                          <?php
                          }else{
                            echo $p['quantity_actual'];
                          }
                          //echo $p['quantity_actual']; ?></td>
                        <td><?php echo $p['status'] == 0?'Belum diterima':($p['status'] == 1?'Sudah diterima':($p['status'] == 2?'Terdapat Selisih':'Dibatalkan')); ?></td>
                      </tr>
                      <?php
                      $i++;
                      }
                      ?>
                    </tbody>
                    <form>
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
$('.sidebar-menu').find('#menu-terima-transfer').addClass('active');

</script>
<script>
function simpan(){
  $('#form').submit();
}
function cetakjakarta(detail){
  $.ajax({
    url: '<?php echo $printer; ?>cetaktransfer',
    dataType: 'json',
    method:'POST',
    success: function(json) {
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
