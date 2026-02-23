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
            <h3 class="box-title">Saldo Awal Chart Of Account (COA)</h3>
            <div class="button pull-right">
									<a href="<?php echo $insert; ?>"><button type="button" class="btn btn-primary">Tambah</button></a>
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
                  <?php echo $warning; ?>
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

                <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                        <th class="left">Tahun</th>
                        <th class="left">Kode Rekening</th>
                        <th class="left">Nama COA</th>
                        <th class="left">Type</th>
                        <th class="right">Debet</th>
                        <th class="right">Kredit</th>
                        <th class="right"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td></td>
                        <td>
                            <select class="form-control" name="filter_tahun">
                                <option value="*">Tampil Semua</option>
                                <?php
                                for($i=2018;$i<=date('Y');$i++){
                                ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                        
                        <td><input name="filter_kode_rek" type="text" placeholder="Filter Kode Rekening" class="form-control" value="<?php echo $filter_kode_rek; ?>" ></td>
                        <td><input name="filter_name" type="text" placeholder="Filter Nama Akun" class="form-control" value="<?php echo $filter_name; ?>" ></td>
                        <td>
                            <select class="form-control" name="filter_type">
                              <option value="*">Semua Jenis Akun</option>
                              <option value="1" <?php echo $filter_type == 1?'selected':''; ?>>Aset</option>
                              <option value="2" <?php echo $filter_type == 2?'selected':''; ?>>Hutang</option>
                              <option value="3" <?php echo $filter_type == 3?'selected':''; ?>>Modal</option>
                              <option value="4" <?php echo $filter_type == 4?'selected':''; ?>>Pendapatan</option>
                              <option value="5" <?php echo $filter_type == 5?'selected':''; ?>>Harga Pokok Penjualan</option>
                              <option value="6" <?php echo $filter_type == 6?'selected':''; ?>>Beban</option>
                              <option value="7" <?php echo $filter_type == 7?'selected':''; ?>>Pendapatan Lain-lain</option>
                              <option value="8" <?php echo $filter_type == 8?'selected':''; ?>>Beban Lain-Lain</option>
                              <option value="9" <?php echo $filter_type == 9?'selected':''; ?>>Pendapatan dan Biaya Luar Biasa</option>

                            </select>
                        </td>
                        <td></td>
                        <td></td>
                        <td><a onclick="filter();" class="btn btn-success">Filter</a></td>
                      </tr>
                      <?php if ($saldos) { ?>
                      <?php foreach ($saldos as $category) { ?>
                      <tr>
                        <td style="text-align: center;"><?php if ($category['selected']) { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $category['id']; ?>" checked="checked" />
                          <?php } else { ?>
                          <input type="checkbox" name="selected[]" value="<?php echo $category['id']; ?>" />
                          <?php } ?></td>
                          <td class="left"><?php echo $category['tahun']; ?></td>
                          <td class="left"><?php echo $category['kode_rek']; ?></td>
                        <td class="left"><?php if($category['parent_id'] == 0){
                          ?>
                          <b><?php echo $category['name']; ?></b>
                          <?php
                        }else{
                        ?>
                        <?php echo $category['name']; ?>
                        <?php
                        }
                        ?></td>
                        <td class="left"><?php echo $category['type']; ?></td>
                        <td class="right"><?php echo $category['debet']; ?></td>
                        <td class="right"><?php echo $category['kredit']; ?></td>
                        <td class="right"><?php foreach ($category['action'] as $action) { ?>
                          [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      <?php } else { ?>
                      <tr>
                        <td class="center" colspan="4">Data COA tidak ditemukan</td>
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
<script>
$('.sidebar-menu').find('#menu-keuangan').addClass('active');


</script>
<script>
function filter(){
  url = 'index.php?route=keuangan/saldoawalcoa&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();
	if (filter_name) {
		url += '&filter_name=' + filter_name;
	}

  var filter_kode_rek = $('input[name=\'filter_kode_rek\']').val();
	if (filter_kode_rek) {
		url += '&filter_kode_rek=' + filter_kode_rek;
	}

  var filter_type = $('select[name=\'filter_type\']').val();
	if (filter_type != '*') {
		url += '&filter_type=' + filter_type;
	}
    var filter_tahun = $('select[name=\'filter_tahun\']').val();
	if (filter_tahun != '*') {
		url += '&filter_tahun=' + filter_tahun;
	}
  location=url
}
</script>
<?php echo $footer; ?>
