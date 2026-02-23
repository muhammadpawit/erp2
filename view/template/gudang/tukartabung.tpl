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
            <h3 class="box-title">Permintaan Tukar Kran</h3>
            <div class="button pull-right">
                  <a href="<?php echo $insert; ?>"><button type="button" class="btn btn-primary">Tambah</button></a>
                </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">

                <?php if ($success) { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <?php echo $success; ?>
                </div>
                <?php
                }
                ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <table class="table table-stripped">
                    <thead>
                      <tr>
                        <th>Tanggal</th>
                        <th>Gudang</th>
                        <th>Tabung Hasil</th>
                        <th>Status</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><input type="text" class="form-control date" readonly name="filter_tanggal" value=""></td>
                      <td ><select style="width:200px" name="filter_gudang_id" class="select-ads">
                      <option value="*">Semua Gudang</option>
                      <?php
                      foreach($gudangs as $g){
                      ?>
                        <option value="<?php echo $g['gudang_id'] ?>" <?php echo $filter_gudang_id == $g['gudang_id']?'selected':'';?>><?php echo $g['nama'] ?></option>
                      <?php
                      }
                      ?>
                    </select>
                      </td>
                      <td><input type="text" class="form-control" name="filter_tabunghasil" value="<?php echo $filter_name; ?>" />
                      </td>

                      <td>
                          <select class="form-control" name="filter_status">
                            <option value="*" >Semua Status</option>
                            <option value="3" <?php echo $filter_status == 3?'selected':''; ?>>Ditolak/Dibatalkan</option>
                            <option value="1" <?php echo $filter_status == 1?'selected':''; ?>>Menunggu Persetujuan</option>
                            <option value="2" <?php echo $filter_status == 2?'selected':''; ?>>Selesai Diproses</option>

                          </status>
                      </td>
                      <td ><a onclick="filter();" class="btn btn-info">Filter</a></td>
                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Tgl Tukar</th>
                        <th>Tgl Proses</th>
                        <th>No Dokumen</th>
                        <th>Gudang</th>
                        <th>Tabung Asal</th>
                        <th>Kran Dipasang</th>
                        <th>Tabung Hasil</th>
                        <th>Kran Yang Dilepas</th>
                        <th>Quantity</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tgl_tukar']; ?></td>
                        <td><?php echo $product['tgl_proses']; ?></td>
                        <td><?php echo $product['no_dokumen']; ?></td>
                        <td><?php echo $product['gudang']; ?></td>
                        <td><?php echo $product['tabung_a']; ?></td>
                        <td><?php echo $product['kran_b']; ?></td>
                        <td><?php echo $product['tabung_b']; ?></td>
                        <td><?php echo $product['kran_lepasan']; ?></td>
                        <td><?php echo $product['quantity']; ?></td>
                        <td><?php echo $product['keterangan']; ?></td>
                        <td>
                          <?php
                            if($product['status'] == 3){
                              echo 'Ditolak/Dibatalkan';
                            }
                            if($product['status'] == 1){
                              echo 'Menunggu Persetujuan';
                            }
                            if($product['status'] == 2){
                              echo 'Selesai Diproses';
                            }
                          ?>
                        </td>
                        <td class="right"><?php foreach ($product['actions'] as $action) {

                          ?>

                           <a href="<?php echo $action['href']; ?>"  <?php echo $action['text']=='Cetak'?'target="_blank"':''; ?> class="badge bg-yellow"><?php echo $action['text']; ?></a>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="9">Data tidak ditemukan</td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>

              </div>
            </div>

          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">
                <div class="pull-right"><?php echo $pagination; ?></div>
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
$('.sidebar-menu').find('#menu-tukar-kran').addClass('active');

$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
})
</script>
<script type="text/javascript"><!--
$(document).ready(function() {
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
})

//--></script>
<script type="text/javascript"><!--
$('input[name=\'filter_tabunghasil\']').autocomplete({
  delay: 0,
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request.term),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item.name,
            value: item.product_id,

          }
        }));
      }
    });
  },
  select: function(event, ui) {
    $('input[name=\'filter_tabunghasil\']').val(ui.item['label']);

    //$('input[name=\'filter_product_id\']').attr('value', ui.item['value']);



    return false;
  },
  focus: function(event, ui) {
        return false;
    }
});
//--></script>
<script type="text/javascript"><!--
function filter() {
  url = 'index.php?route=gudang/tukartabung&token=<?php echo $token; ?>';

  var filter_tanggal = $('input[name=\'filter_tanggal\']').val();

  if (filter_tanggal) {
    url += '&filter_tanggal=' + encodeURIComponent(filter_tanggal);
  }


  var filter_status = $('select[name=\'filter_status\']').val();

  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status);
  }

  var filter_tabunghasil = $('input[name=\'filter_tabunghasil\']').val();

  if (filter_tabunghasil) {
    url += '&filter_tabunghasil=' + encodeURIComponent(filter_tabunghasil);
  }

  var filter_gudang_id = $('select[name=\'filter_gudang_id\']').val();

  if (filter_gudang_id !="*") {
    url += '&filter_gudang_id=' + filter_gudang_id;
  }





  location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
  if (e.keyCode == 13) {
    filter();
  }
});
//--></script>
<?php echo $footer; ?>
