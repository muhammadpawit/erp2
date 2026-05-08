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
            <h3 class="box-title">Daftar Harga Terendah</h3>
            <div class="button pull-right">
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
                        <th>Nama Barang</th>
                        <th>Tanggal</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td>
                        <input type="text" name="filter_nama" value="<?php echo $filter_nama; ?>" placeholder="Nama Barang" class="form-control" />
                      </td>
                      <td>
                        <select style="width:100%;" name="filter_tanggal" class="form-control lokasi-pameran">

                        </select>
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
                        <th style="text-align:center !important;" rowspan="2" align="center">NO</th>
                        <th style="text-align:center !important;" rowspan="2" align="center">Tanggal Berlaku</th>
                        <th style="text-align:center !important;" rowspan="2" align="center">Kode Barang</th>
                        <th style="text-align:center !important;" rowspan="2" align="center">Nama Barang</th>
                        <th style="text-align:center !important;" colspan="<?php echo count($gudangs); ?>" align="center">Harga Terendah</th>
                    </tr>
                    <tr>
                        <?php foreach ($gudangs as $g_name) { ?>
                        <th style="text-align:center !important;" align="center"><?php echo $g_name; ?></th>
                        <?php } ?>
                    </tr>
                    </thead>
                    <tbody>

                      <?php if (isset($permintaans)) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['no']?></td>
                        <td><?php echo $product['tanggal']?></td>
                        <td><?php echo $product['kode']?></td>
                        <td><?php echo $product['nama']?></td>
                        <?php foreach ($gudangs as $g_id => $g_name) { ?>
                        <td><?php echo $product['prices'][$g_id]?></td>
                        <?php } ?>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="<?php echo (4 + count($gudangs)); ?>">Data tidak ditemukan</td>
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
$('.sidebar-menu').find('#menu-laporan').addClass('active');
$('.sidebar-menu').find('#menu-laporan-hargaterendah').addClass('active');
//$('.sidebar-menu').find('#penerimaan-dana-hutang-lain').addClass('active');
</script>
<script type="text/javascript">
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
<script type="text/javascript">
function filter() {
	url = 'index.php?route=laporan/hargaterendah&token=<?php echo $token; ?>';

	

  var filter_tanggal = $('select[name=\'filter_tanggal\']').val();

	if (filter_tanggal) {
		url += '&filter_tanggal=' + encodeURIComponent(filter_tanggal);
	}

  var filter_nama = $('input[name=\'filter_nama\']').val();

	if (filter_nama) {
		url += '&filter_nama=' + encodeURIComponent(filter_nama);
	}

	location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $(".lokasi-pameran").select2({
    ajax: {
    url:"index.php?route=laporan/hargaterendah/autocomplete&token=<?php echo $token; ?>",
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
  })
  $(".bank").select2({
    ajax: {
    url:"index.php?route=keuangan/bank/autocomplete&token=<?php echo $this->request->get['token']; ?>",
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term,
        c:1

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
  })
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
