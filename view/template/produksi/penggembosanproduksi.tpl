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
            <h3 class="box-title">Penggembosan Produksi</h3>
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
                        <th>Nomor Surat</th>
                        <th>Jenis Produksi</th>
                        <th>Divisi Asal</th>
                        <th>Status</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><input type="text" class="date form-control" readonly name="filter_tanggal" value="<?php echo $filter_tanggal; ?>"></td>
                      <td><input type="text" class="form-control" name="filter_no_surat" value="<?php echo $filter_no_surat; ?>"></td>
                      <td>
                          <select class="form-control" name="filter_jenis_pembelian">
                            <option value="*" >Semua Jenis Produksi</option>
                            <option value="1" <?php echo $filter_jenis_pembelian == 1?'selected':''; ?>>MR</option>
                            <option value="2" <?php echo $filter_jenis_pembelian == 2?'selected':''; ?>>Stok</option>
                            <option value="3" <?php echo $filter_jenis_pembelian == 3?'selected':''; ?>>MP</option>
                          </status>
                      </td>
                    <td>
                        <select style="width:200px" name="filter_divisi" class="select-ads">
                          <option value="*">Semua Divisi Asal</option>
                          <?php
                          foreach($divisis as $g){
                          ?>
                            <option value="<?php echo $g['id'] ?>" <?php echo $filter_divisi == $g['id']?'selected':'';?>><?php echo $g['name'] ?></option>
                          <?php
                          }
                          ?>
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
                        <th>Tanggal</th>
                        <th>Nomor Surat</th>
                        <th>Gudang</th>
                        <th>Keterangan</th>
                        <th>Jenis Produksi</th>
                        <th>Divisi Asal</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($permintaans) { ?>
                      <?php foreach ($permintaans as $product) { ?>
                      <tr>
                        <td><?php echo $product['tanggal']; ?></td>
                        <td><?php echo $product['no_surat']; ?></td>
                        <td><?php echo $product['gudang']; ?></td>
                        <td><?php echo $product['keterangan']; ?></td>
                        <td>
                          <?php
                            if($product['jenis_produksi'] == 1){
                              echo 'MR';
                            }
                            if($product['jenis_produksi'] == 2){
                              echo 'Stok';
                            }
                            if($product['jenis_produksi'] == 3){
                              echo 'MP';
                            }
                          ?>
                        </td>
                        <td><?php echo $product['name']; ?></td>

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
$('.sidebar-menu').find('#menu-produksi').addClass('active');

$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
})
</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=produksi/penggembosanproduksi&token=<?php echo $token; ?>';

  var filter_tanggal = $('input[name=\'filter_tanggal\']').val();

	if (filter_tanggal) {
		url += '&filter_tanggal=' + encodeURIComponent(filter_tanggal);
	}

  var filter_no_surat = $('input[name=\'filter_no_surat\']').val();

	if (filter_no_surat) {
		url += '&filter_no_surat=' + encodeURIComponent(filter_no_surat);
	}

	var filter_divisi = $('select[name=\'filter_divisi\']').val();

	if (filter_divisi != '*') {
		url += '&filter_divisi=' + encodeURIComponent(filter_divisi);
	}
  var filter_jenis_pembelian = $('select[name=\'filter_jenis_pembelian\']').val();

	if (filter_jenis_pembelian != '*') {
		url += '&filter_jenis_pembelian=' + encodeURIComponent(filter_jenis_pembelian);
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
