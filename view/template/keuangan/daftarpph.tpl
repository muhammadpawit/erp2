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
                        <th>Jenis Pph</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                    <td><input type="text" class="form-control" id="date-start" name="filter_date_start" value="<?php echo $filter_date_start; ?>"></td>
                      <td><input type="text" class="form-control" id="date-end" name="filter_date_end" value="<?php echo $filter_date_end; ?>"></td>

                      <td>
                        <select name="filter_jenis" class="form-control">
                          <option value="*">Pilih</option>
                          <?php //foreach($coas as $ref) { ?>
                            <!--<option value="<?php //echo $ref['kode_rek'] ?>"><?php //echo $ref['name'] ?></option>-->
                          <?php //} ?>
							<option value="2501" <?php echo ($filter_jenis==2501)?'selected':''?>>Hutang pajak-PPh 21</option>
                            <option value="2502" <?php echo ($filter_jenis==2502)?'selected':''?>>Hutang pajak-PPh 23</option>
                            <option value="2503" <?php echo ($filter_jenis==2503)?'selected':''?>>Hutang pajak-PPh 25</option>
                            <option value="2504" <?php echo ($filter_jenis==2504)?'selected':''?>>Hutang pajak-PPh 29</option>
                            <option value="1551" <?php echo ($filter_jenis==1551)?'selected':''?>>Pajak dibayar dimuka-PPh 21</option>
                            <option value="1552" <?php echo ($filter_jenis==1552)?'selected':''?>>Pajak dibayar dimuka-PPh 22</option>
                            <option value="1553" <?php echo ($filter_jenis==1553)?'selected':''?>>Pajak dibayar dimuka-PPh 23</option>
                            <option value="1554" <?php echo ($filter_jenis==1554)?'selected':''?>>Pajak dibayar dimuka-PPh 25</option>
							              <!--
                            <option value="2506" <?php echo ($filter_jenis==2506)?'selected':''?>>Hutang pajak-PPh Ps 4 ay 2 atas Sewa</option>
                            -->
							              <option value="2507" <?php echo ($filter_jenis==2507)?'selected':''?>>Hutang pajak-PPh Final ps.17 (2c) dividen</option>
                        </select>
                      </td>
                      <td><a onclick="filter();" class="btn btn-info">Filter</a></td>
                    </tr>
                  </tbody>
                  </table>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
						<th></th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th class="text-center">Debet</th>
                        <th class="text-center">Kredit</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td></td>
						            <td></td>
                        <td><b>Total : <?php echo $transaksi ?></b></td>
                        <td><b><?php echo $this->currency->format($tb); ?></b></td>
                        <td><b><?php echo $this->currency->format($tk); ?></b></td>
                      </tr>
                      <?php
                      if ($orders) { ?>
                      <?php foreach ($orders as $product) { ?>

                      <tr>
                      		<td><?php echo $no++ ?></td>
                      		<td><?php echo $product['tanggal'] ?></td>
                      		<td><b><?php echo $product['akun'] ?></b><br><small><?php echo $product['keterangan'] ?></small></td>
                      		<td><?php echo $this->currency->format($product['debet']); ?></td>
                      		<td><?php echo $this->currency->format($product['kredit']); ?></td>
                      	</tr>
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

  var filter_jenis = $('select[name=\'filter_jenis\']').val();
  if (filter_jenis == '*') {
	//swal("Oops!", "Silahkan Pilih PPh");
	// swal({
	//   title: "Oops!",
	//   text: "Silahkan Pilih PPh!",
	//   icon: "error",
	//   button: "OK",
	// });
	//$('select[name=\'filter_jenis\']').focus();
	//return false;
    /*var r = confirm("Apakah akan filter semua PPh ?");
	if (r == true) {
	  location = url;
	} else {
	  alert("Jika tidak silahkan pilih jenis PPh");
	  $('select[name=\'filter_jenis\']').focus();
	  return false;
	}*/
  }
  if (filter_jenis != '*') {
    url += '&filter_jenis=' + filter_jenis;
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
