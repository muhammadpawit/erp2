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
            <h3 class="box-title">Aset</h3>
            <div class="button pull-right">
									<a href="<?php echo $excel; ?>" target="_blank"><button type="button" class="btn btn-warning">Export to Excel</button></a>
                  <a href="<?php echo $insert; ?>"><button type="button" class="btn btn-primary">Tambah</button></a>
                  <a href="<?php echo $penyesuaian; ?>"><button type="button" class="btn btn-success">Penyesuaian Nilai</button></a>
                  <a onclick="$('#form').submit();" ><button type="button" class="btn btn-danger">Hapus</button></a>
								</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                  <?php echo $error_warning; ?>
                </div>
                <?php
                }
                ?>
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
                        <th colspan="2">Filter Tanggal</th>
                        <th>Nama Aset</th>
                        <th>Jumlah</th>
                        <th>Kelompok Aset</th>
                        <th>Jenis Aktiva</th>
                        <th>Status</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td><input type="text" class="form-control" placeholder="Tanggal Awal" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12"  /></td>
                      <td><input type="text" class="form-control" placeholder="Tanggal Akhir" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12"  /></td>

                      <td><input type="text" class="form-control" name="filter_name" value="<?php echo $filter_name; ?>" />

                      <td>
                        <select class="select-ads" style="width:200px" name="filter_kelompok_aset" >
                          <option value="*">Semua Kelompok Aset</option>
                          <?php
                          foreach($asets as $u){
                          ?>
                            <option value="<?php echo $u['kelompok_aset_id']?>" <?php echo $u['kelompok_aset_id'] == $filter_kelompok_aset?'selected':''; ?>><?php echo $u['jenis_aset'] == 1?'Bukan Bangunan':'Bangunan'; ?> <?php echo $u['name']; ?></option>
                          <?php
                          }
                          ?>
                        </select>
                      </td>
                    <td>
                          <select class="form-control" name="filter_jenis_aktiva">
                            <option value="*">Semua Jenis Aktiva</option>
                          <?php
                          foreach($aktivas as $c){
                          ?>
                            <option value="<?php echo $c['no_akun']; ?>" <?php echo $c['no_akun']==$jenis_aktiva?'selected':''; ?>><?php echo $c['nama']; ?></option>
                          <?php
                          }
                          ?>
                          </select>
                      </td>


                    <td><select name="filter_status" class="form-control">
                              <option value="*">Semua Status</option>
                              <option value="1" <?php echo $filter_status == 1?'selected':''; ?>>Tersedia</option>
                              <option value="2" <?php echo $filter_status == 2?'selected':''; ?>>Tidak Tersedia</option>
                              <option value="3" <?php echo $filter_status == 3?'selected':''; ?>>Hilang</option>
                              <option value="4" <?php echo $filter_status == 4?'selected':''; ?>>Dijual</option>

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
                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
                        <th>Kode</th>
                        <th  width="200px"><?php if ($sort == 'name') { ?>
                          <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>">Nama Aset</a>
                          <?php } else { ?>
                          <a href="<?php echo $sort_name; ?>">Nama Aset</a>
                          <?php } ?>
                        </th>
                        <th>Jumlah</th>
                        <th class="left">Jenis Aktiva</th>
          				      <th class="left"><?php if ($sort == 'kelompok_aset') { ?>
                          <a href="<?php echo $sort_kelompok_aset; ?>" class="<?php echo strtolower($order); ?>">Kelompok Aset</a>
                          <?php } else { ?>
                          <a href="<?php echo $sort_kelompok_aset; ?>">Kelompok Aset</a>
                          <?php } ?></th>
                        <th class="right"><?php if ($sort == 'tglpembelian') { ?>
                          <a href="<?php echo $sort_tglpembelian; ?>" class="<?php echo strtolower($order); ?>">Tanggal Perolehan</a>
                          <?php } else { ?>
                          <a href="<?php echo $sort_tglpembelian; ?>">Tanggal Perolehan</a>
                          <?php } ?></th>
                          <th class="right"><?php if ($sort == 'hargabeli') { ?>
                            <a href="<?php echo $sort_hargabeli; ?>" class="<?php echo strtolower($order); ?>">Harga Beli</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_hargabeli; ?>">Harga Beli</a>
                            <?php } ?></th>
                          <th class="right"><?php if ($sort == 'nilaibuku') { ?>
                            <a href="<?php echo $sort_nilaibuku; ?>" class="<?php echo strtolower($order); ?>">Nilai Buku</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_nilaibuku; ?>">Nilai Buku</a>
                            <?php } ?></th>
                          <th class="right">Penyusutan Tahunan</th>
                          <th class="right">Penyusutan Bulanan</th>
                          <th class="right">Akumulasi Penyusutan</th>
                        <th class="left">Status</th>
                        <th class="right"></th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php if ($tasets) { ?>
                      <?php foreach ($tasets as $product) { ?>
                      <tr>
                        <td style="text-align: center;">
                    		<?php
                    		if($product['status'] == 1 | $product['status'] == 3){
                    		?>
                    		<?php if ($product['selected']) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $product['aset_id']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $product['aset_id']; ?>" />
                                    <?php }}

                    				//echo $product['stok'];
                    				?>
                    		</td>
                          <td class="left"><?php echo $product['kode']; ?></td>
                          <td class="left"><?php echo $product['name']; ?></td>
                          <td class="left"><?php echo $product['jumlah']; ?></td>
                          <td class="left"><?php echo $product['nama_aktiva']; ?></td>
                          <td class="left"><?php echo $product['jenis'] == 1?'Bukan Bangunan':'Bangunan'; ?> <?php echo $product['kelompok']; ?></td>
                          <td class="left"><?php echo $product['tglpembelian']; ?></td>
                          <td class="left"><?php echo $product['hargabeli']; ?></td>
                          <td class="left"><?php echo $product['nilaibuku']; ?>
                          </td>
                          <td class="left"><?php echo $product['penyusutan']; ?>
                          </td>
                          <td class="left"><?php echo $product['penyusutanbulanan']; ?>
                          </td>
                          <td class="left"><?php echo $product['akumulasipenyusutan']; ?>
                          </td>

                        <td class="left"><?php echo $product['status']; ?></td>
                        <td class="right"><?php foreach ($product['action'] as $action) { ?>
                           <a href="<?php echo $action['href']; ?>" class="label label-primary"><?php echo $action['text']; ?></a><br>
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
                </form>
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
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});

	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>

<script>
$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-aset').addClass('active');
$(function(){
  $(".select-ads").select2({


      theme:"bootstrap"
    });
})
</script>
<script type="text/javascript">
function filter() {
	url = 'index.php?route=catalog/aset&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
  var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

  var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

  var filter_jenis_aktiva = $('select[name=\'filter_jenis_aktiva\']').val();

  	if (filter_jenis_aktiva != '*') {
  		url += '&filter_jenis_aktiva=' + encodeURIComponent(filter_jenis_aktiva);
  	}

  var filter_kelompok_aset = $('select[name=\'filter_kelompok_aset\']').val();

  	if (filter_kelompok_aset != '*') {
  		url += '&filter_kelompok_aset=' + encodeURIComponent(filter_kelompok_aset);
  	}


	location = url;
}
</script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>

<?php echo $footer; ?>
