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
            <h3 class="box-title">Biaya</h3>
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
                        <th>Tanggal Awal</th>
                        <th>Tanggal Akhir</th>
                        <th>Vendor</th>
                        <th>Jenis Biaya</th>
                        <th>Status</th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><input class="date form-control" type="text" name="filter_date_start" value="<?php echo $filter_tgl_awal; ?>" readonly></td>
                      <td><input class="date form-control" type="text" name="filter_date_end" value="<?php echo $filter_tgl_akhir; ?>" readonly></td>
                      <td>
                        <select name="filter_pameran_id" class="form-control lokasi-pameran">
                    			<option value="0" <?php echo empty($filter_pameran_id)?'selected':'';?>>Semua Vendor</option>


                    		</select>
                      </td>
                      <td><select name="filter_biaya" class="form-control">
                        <option value="0">Semua Biaya</option>
                        <option value="1">Sewa Kantor dan Gudang</option>
                        <option value="2">Perjalanan Dinas</option>
                        <option value="3">Profesional</option>
                        <option value="4">Asuransi</option>
                        <option value="5">Renovasi Bangunan</option>
                        <option value="6">Pembuatan Software</option>
                        <option value="7">Lain-lain</option>
                     		</select></td>
                    <td><select name="filter_jenis" class="form-control">
                      <option value="0">Semua Status</option>
                      <option value="1" <?php echo $filter_status == 1?'selected':''; ?>>Belum Dibayar</option>
                      <option value="2" <?php echo $filter_status == 2?'selected':''; ?>>Dibayar Sebagian</option>
                      <option value="3" <?php echo $filter_status == 3?'selected':''; ?>>Lunas</option>
                   		</select></td>
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
                        <th>Vendor</th>
                        <th>Periode</th>
                        <th>Keterangan</th>
                        <th>Nominal</th>
                        <th>PPn</th>
                        <th>Total</th>
                        <th>Total Bayar</th>
                        <th>Biaya Bulanan</th>
                        <th>Status</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['vendor']; ?></td>
                        <td><?php echo $product['tglawal']; ?> - <?php echo $product['tglakhir']; ?><br><?php echo $product['masaberlaku']; ?> Bulan</td>
                        <td><?php echo $product['keterangan']; ?></td>
                        <td><?php echo $product['nilaisewa']; ?></td>
                        <td><?php echo $product['ppn']; ?></td>

                        <td><?php echo $product['total']; ?></td>
                        <td><?php echo $product['totalbayar']; ?></td>
                        <td><?php echo $product['bulanan']; ?></td>
                        <td><?php echo $product['status']; ?></td>

                      <td class="right"><?php foreach ($product['actions'] as $action) {

                          ?>

                           <a href="<?php echo $action['href']; ?>"  <?php echo $action['text']=='Cetak PO'?'target="_blank"':''; ?> class="badge bg-yellow"><?php echo $action['text']; ?></a>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="10">Data tidak ditemukan</td>
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
$('.sidebar-menu').find('#menu-keuangan').addClass('active');
$('.sidebar-menu').find('#menu-biaya').addClass('active');
</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=keuangan/iklanperiodik&token=<?php echo $token; ?>';

	var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

  var filter_status = $('select[name=\'filter_jenis\']').val();

	if (filter_status > 0) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

  var filter_pameran_id = $('select[name=\'filter_pameran_id\']').val();

	if (filter_pameran_id > 0) {
		url += '&filter_pameran_id=' + encodeURIComponent(filter_pameran_id);
	}

  var filter_biaya = $('select[name=\'filter_biaya\']').val();

	if (filter_biaya > 0) {
		url += '&filter_biaya=' + encodeURIComponent(filter_biaya);
	}


	location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $(".lokasi-pameran").select2({
    ajax: {
    url: 'index.php?route=catalog/vendorlokal/autocomplete&token=<?php echo $token; ?>',
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
