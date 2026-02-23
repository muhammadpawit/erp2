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
            <h3 class="box-title">Kartu Aset <?php echo $aset['name']; ?></h3>
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
                        <th></th>
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
                        <th class="left">Waktu</th>
          				      <th class="left">Harga Beli</th>
                        <th class="left">Penyusutan</th>
                        <th class="left">Akumulasi Penyusutan</th>
                        <th class="right">Nilai Buku</th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($kartustoks) { ?>
                      <?php foreach ($kartustoks as $product) { ?>
                      <tr>
                        <td class="left"><?php echo $product['tanggal'];
                          ?></td>
                        <td class="left"><?php echo $product['waktu']; ?></td>
          			          <td class="left"><?php echo $product['hargabeli']; ?></td>
                          <td class="left"><?php echo $product['penyusutan']; ?></td>
                          <td class="left"><?php echo $product['akumulasipenyusutan']; ?></td>
                          <td class="left"><?php echo $product['nilaibuku']; ?></td>

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
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-aset').addClass('active');

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
