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
            <h3 class="box-title">Permintaan Pembelian</h3>
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
                        <th>Jenis Pembelian</th>
                        <th>Jenis Barang</th>
                        <th>Divisi Asal</th>
                        <th>Status</th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><input type="text" class="form-control date" name="filter_tanggal" value=""></td>
                      <td><input type="text" class="form-control" name="filter_no_surat" value="<?php echo $filter_no_surat; ?>"></td>
                      <td>
                          <select class="form-control" name="filter_jenis_pembelian">
                            <option value="*" >Semua Jenis Pembelian</option>
                            <option value="1" <?php echo $filter_jenis_pembelian == 1?'selected':''; ?>>Tunai</option>
                            <option value="2" <?php echo $filter_jenis_pembelian == 2?'selected':''; ?>>Kredit</option>
                            <option value="3" <?php echo $filter_jenis_pembelian == 3?'selected':''; ?>>Import</option>
                          </status>
                      </td>
                      <td>
                          <select class="form-control" name="filter_jenis_barang">
                            <option value="*" >Semua Jenis Barang</option>
                          <option value="1" <?php echo $filter_jenis_barang == 1?'selected':''; ?>>Bahan Baku</option>
                            <option value="2" <?php echo $filter_jenis_barang == 2?'selected':''; ?>>Produk Dagang</option>
                            <option value="3" <?php echo $filter_jenis_barang == 3?'selected':''; ?>>ATK</option>
                            <option value="4" <?php echo $filter_jenis_barang == 4?'selected':''; ?>>Aset</option>
                            <option value="5" <?php echo $filter_jenis_barang == 5?'selected':''; ?>>Tabung MP</option>
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
                      <td>
                          <select class="form-control" name="filter_status">
                            <option value="*" >Semua Status</option>
                            <option value="3" <?php echo $filter_status == 3?'selected':''; ?>>Ditolak/Dibatalkan</option>
                            <option value="1" <?php echo $filter_status == 1?'selected':''; ?>>Disimpan</option>
                            <option value="2" <?php echo $filter_status == 2?'selected':''; ?>>Disetujui (Belum Dibuat PO)</option>
                            <option value="4" <?php echo $filter_status == 4?'selected':''; ?>>Sudah Dibuat PO</option>

                          </status>
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
                        <th>Tujuan Pembelian</th>
                        <th>Jenis Pembelian</th>
                        <th>Jenis Barang</th>
                        <th>Divisi Asal</th>
                        <th>Status</th>
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
                        <td><?php echo $product['tujuan_pembelian']; ?></td>
                        <td>
                          <?php
                            if($product['jenis_pembelian'] == 1){
                              echo 'Tunai';
                            }
                            if($product['jenis_pembelian'] == 2){
                              echo 'Kredit';
                            }
                            if($product['jenis_pembelian'] == 3){
                              echo 'Import';
                            }
                          ?>
                        </td>
                        <td>
                          <?php
                            if($product['jenis_barang'] == 1){
                              echo 'Bahan Baku';
                            }
                            if($product['jenis_barang'] == 2){
                              echo 'Produk Dagang';
                            }
                            if($product['jenis_barang'] == 3){
                              echo 'ATK';
                            }
                            if($product['jenis_barang'] == 4){
                              echo 'Aset';
                            }
                            if($product['jenis_barang'] == 5){
                              echo 'Tabung MP';
                            }

                          ?>
                        </td>
                        <td><?php echo $product['name']; ?></td>
                        <td>
                          <?php
                            if($product['status'] == 3){
                              echo 'Ditolak/Dibatalkan';
                            }
                            if($product['status'] == 1){
                              echo 'Disimpan';
                            }
                            if($product['status'] == 2){
                              echo 'Disetujui (Belum Dibuat PO)';
                            }

                            if($product['status'] == 4){
                              echo 'Disetujui (Sudah Dibuat PO)';
                            }

                          ?>
                        </td>
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
$('.sidebar-menu').find('#menu-pembelian').addClass('active');
$('.sidebar-menu').find('#menu-pembelian-produk').addClass('active');

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
	url = 'index.php?route=pembelian/permintaanpembelian&token=<?php echo $token; ?>';

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

  var filter_jenis_barang = $('select[name=\'filter_jenis_barang\']').val();

	if (filter_jenis_barang != '*') {
		url += '&filter_jenis_barang=' + encodeURIComponent(filter_jenis_barang);
	}

  var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
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
