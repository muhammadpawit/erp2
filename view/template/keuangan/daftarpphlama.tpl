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
            <h3 class="box-title">Daftar PPh</h3>
            <div class="button pull-right">

                  </div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table table-stripped">
                    <thead>
                      <tr>
                        <th>Filter Tanggal Awal</th>
                        <th>Filter Tanggal Akhir</th>
                        <th>Akun</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <td><input type="text" class="form-control" id="date-start" name="filter_date_start" value="<?php echo $filter_date_start; ?>"></td>
                      <td><input type="text" class="form-control" id="date-end" name="filter_date_end" value="<?php echo $filter_date_end; ?>"></td>

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
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th class="text-center">Debet</th>
                        <th class="text-center">Kredit</th>

                      </tr>


                    </thead>
                    <tbody>
                      <tr>
                        <td></td>
                        <td><b>Total</b></td>
                        <td><b><?php echo $this->currency->format($totaldebet); ?></b></td>
                        <td><b><?php echo $this->currency->format($totalkredit); ?></b></td>

                      </tr>

                      <?php
                      if ($orders) { ?>
                      <?php foreach ($orders as $product) { ?>

                      <?php
                      foreach($product['detail'] as $d){
                      ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>

                        <td><?php echo $d['ref_akun'].' '.$d['keterangan'].'<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small>'.$product['keterangan'].'</small>'; ?></td>
                        <td><?php echo $this->currency->format($d['debet']); ?></td>
                        <td><?php echo $this->currency->format($d['kredit']); ?></td>

                      </tr>
                      <?php
                      }
                      ?>
                      <?php } ?>

                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="6">Data tidak ditemukan</td>
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
$('.sidebar-menu').find('#menu-pajak').addClass('active');
$('.sidebar-menu').find('#menu-daftar-pph').addClass('active');



</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=keuangan/daftarpph&token=<?php echo $token; ?>';

	var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
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
