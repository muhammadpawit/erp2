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
            <h3 class="box-title">Kartu Stok Produk <b><?php echo $namaproduk?></b></h3>
            <div class="button pull-right">
                <?php if($canceldata>0){?>
                  <a href="<?php echo $cetakexcel; ?>" target="_blank"><button type="button" class="btn btn-success">Export to excel</button></a>
                <?php } ?>
								<a href="<?php echo $cancel; ?>" ><button type="button" class="btn btn-danger">Kembali</button></a>
								</div>
          </div>
          <div class="box-body">

            <div class="row">
              <div class="col-md-12">
                <table class="table table-stripped">
                    <thead>
                      <tr>
                        <th>Tanggal Awal</th>
                        <th>Tanggal Akhir</th>
                        <th>Type</th>

                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>

                      <td><input type="text" id="tanggal" name="tanggal" placeholder="tgl awal" class="form-control" value="<?php echo $tanggal;?>">&nbsp;</td>
                      <td><input type="text" id="tanggal2" name="tanggal2" placeholder="tgl akhir" class="form-control" value="<?php echo $tanggal2;?>"></td>
                      <td><select name="type" class="form-control">
                              <option value="*">Tampil Semua</urutkan>
                              <option value="1" <?php echo $type == 1?'selected':'';?>>Pembelian</option>
                              <option value="2" <?php echo $type == 2?'selected':'';?>>Penjualan</option>
                              <option value="3" <?php echo $type == 3?'selected':'';?>>Stok Opname</option>
                              <option value="4" <?php echo $type == 4?'selected':'';?>>Set Stok Awal</option>
                              <option value="5" <?php echo $type == 5?'selected':'';?>>Produk Cacat</option>
                              <option value="6" <?php echo $type == 6?'selected':'';?>>Produk Hilang</option>

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
                        <th class="left">No</th>
                        <th class="left">Tanggal</th>
                        <th class="left">Waktu</th>
          				      <th class="left">Type</th>
                        <th class="left">Referensi</th>
                        <th class="left">No. Dokumen</th>
                        <th class="left">Keterangan</th>
                        <th class="right">Stok Awal</th>
                        <th class="left">Stok Masuk</th>
                        <th class="left">Stok Keluar</th>
                        <th class="left">Stok Akhir</th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($kartustoks) { ?>
                      <?php foreach ($kartustoks as $product) { ?>
                      <tr>
                        <td class="left"><?php echo $product['kartustok_id'];
                          ?></td>
                        <td class="left"><?php echo $product['tanggal'];
                          ?></td>
                        <td class="left"><?php echo $product['waktu']; ?></td>
          			          <td class="left"><?php echo $product['type']; ?></td>
                          <td class="left"><?php echo $product['invoice']; ?> <?php echo ($product['typ']==2)?$product['customer']:''; ?></td>
                          <td class="left"><a href="<?php echo $product['urlref']; ?>" target="_blank"><?php echo $product['no_dokumen']; ?></a></td>
                          <td class="left"><?php echo $product['ket']; ?></td>
                          <td class="left"><?php echo $product['quantityawal']; ?></td>
                          <td class="left"><?php echo $product['stokmasuk']; ?></td>
                          <td class="left"><?php echo $product['stokkeluar']; ?></td>
                          <td class="left"><?php echo $product['saldo']; ?></td>


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

var tanggal2= $('input[name=\'tanggal2\']').val();

  if (tanggal2) {
    url += '&tanggal2=' + encodeURIComponent(tanggal2);
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
  $('#tanggal2').datepicker({dateFormat: 'yy-mm-dd'});
})
</script>
<?php echo $footer; ?>
