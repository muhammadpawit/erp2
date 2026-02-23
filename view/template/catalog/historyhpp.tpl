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
            <h3 class="box-title">History HPP <?php echo $desc['name']; ?> Gudang <?php echo $gudang['nama']; ?></h3>
            <div class="button pull-right">
								<a href="<?php echo $cancel; ?>" ><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table table-stripped">
                    <thead>
                      <tr>
                        <th>Tanggal</th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>

                      <td><input type="text" id="tanggal" name="tanggal" class="form-control" value="<?php echo $tanggal;?>"></td>


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
                        <th class="left">Tanggal</th>

                        <th class="left">HPP</th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($hpps) { ?>
                      <?php foreach ($hpps as $product) { ?>
                      <tr>
                        <td class="left"><?php echo $product['tanggal'];
                          ?></td>
                        <td class="left"><?php echo $product['net_cost']; ?></td>

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
$('.sidebar-menu').find('#menu-produk-gudang').addClass('active');
$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
})
</script>
<script type="text/javascript"><!--
function filter() {
	url = "<?php echo htmlspecialchars_decode($url); ?>";
  //url=decodeURIComponent(urls);


	var tanggal= $('input[name=\'tanggal\']').val();

	if (tanggal) {
		url += '&tanggal=' + encodeURIComponent(tanggal);
	}


	location = url;
}
//--></script>
<script>
$(function(){
  $('#tanggal').datepicker({dateFormat: 'yy-mm-dd'});
})
</script>
<?php echo $footer; ?>
