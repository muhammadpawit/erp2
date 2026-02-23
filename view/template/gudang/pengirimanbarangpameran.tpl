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
            <h3 class="box-title">Pengiriman Barang Pameran</h3>
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
                        <th>Gudang Asal</th>
                        <th>Pameran Tujuan</th>
                        <th>Surat Jalan</th>
                        <th>Status</th>


                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td>
                        <select style="width:200px" name="filter_gudang_asal" class="select-ads">
                          <option value="*">Semua Gudang</option>
                          <?php
                          foreach($gudangasals as $g){
                          ?>
                            <option value="<?php echo $g['gudang_id'] ?>" <?php echo $filter_gudang_asal == $g['gudang_id']?'selected':'';?>><?php echo $g['nama'] ?></option>
                          <?php
                          }
                          ?>
                        </select>
                      </td>
                      <td>
                        <select style="width:200px" name="filter_tujuan" class="lokasi-pameran">
                          <option value="*">Semua Pameran</option>

                        </select>
                      </td>

                      <td><input type="text" class="form-control" name="filter_invoice_no" value="<?php echo $filter_invoice_no; ?>"></td>
                      <td>
                          <select class="form-control" name="filter_status">
                            <option value="0" <?php echo $filter_status == 0?'selected':''; ?>>Belum Diterima</option>
                            <option value="1" <?php echo $filter_status == 1?'selected':''; ?>>Sudah Diterima</option>
                            <option value="2" <?php echo $filter_status == 2?'selected':''; ?>>Terdapat Selisih</option>
                            <option value="3" <?php echo $filter_status == 3?'selected':''; ?>>Dibatalkan</option>
                          </status>
                      </td>
                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <table class="table table-stripped">
                    <thead>
                      <tr>
                        <th>Tanggal Awal</th>

                        <th>Tanggal Akhir</th>
                        <th></td>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><input type="text" class="form-control" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" readonly /></td>
                      <td><input type="text" class="form-control" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" readonly /></td>

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
                        <th class="left">Asal</th>
                        <th class="left">Tujuan</th>
          				      <th class="left">Tanggal</th>
                        <th class="left">No. Surat Jalan</th>
                        <th class="right">Quantity <br>(Kirim/Terima)</th>
                        <th class="left">Total <br> (Kirim/Terima)</th>
                        <th class="right">Status</th>
                        <th class="right" ></th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($products) { ?>
                      <?php foreach ($products as $product) { ?>
                      <tr>
                        <td><?php echo $product['asal']; ?></td>
                        <td><?php echo $product['gudang_tujuan']; ?></td>
                        <td><?php echo $product['date_added']; ?></td>
                        <td><?php echo $product['invoice_no']; ?></td>
                        <td><?php echo $product['qtykirim']; ?>/<?php echo $product['qtyterima']; ?></td>
                        <td><?php echo $product['total']; ?>/<?php echo $product['totalterima']; ?></td>
                        <td>
                          <?php
                            if($product['status'] == 0){
                              echo 'Belum diterima';
                            }
                            if($product['status'] == 1){
                              echo 'Sudah diterima';
                            }
                            if($product['status'] == 2){
                              echo 'Terdapat selisih';
                            }
                            if($product['status'] == 3){
                              echo 'Dibatalkan';
                            }
                          ?>
                        </td>
                        <td class="right"><?php foreach ($product['actions'] as $action) {

                          ?>
                           <a href="<?php echo $action['href']; ?>" <?php echo $action['text']=='Cetak SJ'?'target="_blank"':''; ?> class="badge bg-yellow"><?php echo $action['text']; ?></a>
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
$('.sidebar-menu').find('#menu-pengiriman-barang').addClass('active');
$('.sidebar-menu').find('#menu-pengiriman-barang-pameran').addClass('active');
$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });

    $(".lokasi-pameran").select2({
      ajax: {
      url: 'index.php?route=pamerantoko/pameran/autocomplete&token=<?php echo $token; ?>',
      dataType: 'json',
      data: function (params) {
        return {
          q: params.term, // search term

        };
      },
      delay: 250,
      processResults: function (data) {
        return {
          results: data
        };
      },
      //cache: true
    },
    theme:"bootstrap"
    });
})
</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=gudang/pengirimanbarangpameran&token=<?php echo $token; ?>';

	var filter_order_id = $('input[name=\'filter_order_id\']').val();

	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}

	var filter_invoice_no = $('input[name=\'filter_invoice_no\']').val();

	if (filter_invoice_no) {
		url += '&filter_invoice_no=' + encodeURIComponent(filter_invoice_no);
	}

	var filter_gudang_asal = $('select[name=\'filter_gudang_asal\']').val();

	if (filter_gudang_asal != '*') {
		url += '&filter_gudang_asal=' + encodeURIComponent(filter_gudang_asal);
	}

	var filter_tujuan = $('select[name=\'filter_tujuan\']').val();

	if (filter_tujuan != '*') {
		url += '&filter_tujuan=' + encodeURIComponent(filter_tujuan);
	}



	var filter_date_added = $('input[name=\'filter_date_added\']').val();

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
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
