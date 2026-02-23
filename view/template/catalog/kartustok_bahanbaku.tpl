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
            <h3 class="box-title">Kartu Stok</h3>
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
                        <th>Type</th>

                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>

                      <td><input type="text" id="tanggal" name="tanggal" class="form-control" value="<?php echo $tanggal;?>"></td>
                      <td><select name="type" class="form-control">
                              <option value="*">Tampil Semua</urutkan>
                              <option value="1" <?php echo $type == 1?'selected':'';?>>Pembelian</option>
                              <option value="2" <?php echo $type == 2?'selected':'';?>>Penjualan</option>
                              <option value="3" <?php echo $type == 3?'selected':'';?>>Stok Opname</option>
                              <option value="4" <?php echo $type == 4?'selected':'';?>>Set Stok Awal</option>
                              <option value="7" <?php echo $type == 7?'selected':'';?>>Produksi</option>
                                <option value="8" <?php echo $type == 8?'selected':'';?>>Penggembosan</option>


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
                        <th class="left">Tanggal</th>
                        <th class="left">Waktu Awal</th>
                        <th class="left">Waktu Akhir</th>
          				      <th class="left">Type</th>
                        <th class="left">Referensi</th>
                        <th class="left">Keterangan</th>
                        <th class="right">Level Awal</th>
                        <th class="left">Level Akhir</th>
                        <th class="left">Isi <br>(Kg)</th>
                        <th class="left">Pemakaian LOX</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($kartustoks) { ?>
                      <?php foreach ($kartustoks as $product) { ?>
                      <tr>
                        <td class="left"><?php echo $product['tanggal'];
                          ?></td>
                        <td class="left"><?php echo $product['waktuawal']; ?></td>
                        <td class="left"><?php echo $product['waktuakhir']; ?></td>
          			          <td class="left"><?php echo $product['typename']; ?></td>
                          <td class="left"><?php echo $product['ref']; ?></td>
                          <td class="left"><?php echo $product['ket']; ?></td>
                          <td class="left"><?php echo $product['levelawal']; ?></td>
                          <td class="left"><?php echo $product['levelakhir']; ?></td>
                          <td class="left"><?php echo $product['isi'] > 0?$product['isi']:''; ?></td>
                          <td> <?php
                            if($product['type'] == 7 | $product['type'] == 8){
                              echo abs($product['levelakhir']-$product['levelawal']);
                            }
                             ?></td>
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
$('.sidebar-menu').find('#menu-persediaan-bahanbaku').addClass('active');
$('.sidebar-menu').find('#menu-daftar-bahanbaku').addClass('active');
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

  var type = $('select[name=\'type\']').val();

	if (type != '*') {
		url += '&type=' + type;
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
