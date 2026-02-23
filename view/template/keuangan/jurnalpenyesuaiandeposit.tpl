<?php echo $header; ?>

<div class="content-wrapper" >
  <section class="content-header">
    <h1>

    </h1>

  </section>
  <section class="content" id="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Pengaturan Jurnal Penyesuaian Deposit</h3>
            <div class="button pull-right">
                    <a onclick="$('#form').submit();" class="btn btn-info">Simpan</a>

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

                <?php if ($error_warning) { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Alert!</h4>
                  <?php echo $error_warning; ?>
                </div>
                <?php
                }
                ?>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <div id="tab-general">
                    <table class="table table-bordered">
                      <tr>
                        <td>Nilai Nominal Tersedia Lebih Besar dari Tersimpan</td>
                        <td><select class="form-control" name="config_kelebihan">
                          <option value="0">Belum Diatur</option>
                            <?php foreach ($categories as $category) { ?>
                            <?php if ($category['category_id'] == $config_kelebihan) { ?>
                            <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo  $category['kode_rek'].' '.$category['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $category['category_id']; ?>"><?php echo  $category['kode_rek'].' '.$category['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select></td>
                      </tr>

                      <tr>
                        <td>Nilai Nominal Tersedia Lebih Kecil dari Tersimpan</td>
                        <td><select class="form-control" name="config_kekurangan">
                          <option value="0">Belum Diatur</option>
                            <?php foreach ($categories as $category) { ?>
                            <?php if ($category['category_id'] == $config_kekurangan) { ?>
                            <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo  $category['kode_rek'].' '.$category['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $category['category_id']; ?>"><?php echo  $category['kode_rek'].' '.$category['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select></td>
                      </tr>

                     

                    </table>
                  </div>

                </form>
              </div>
            </div>

          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">

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
/*$('.sidebar-menu').find('#menu-persediaan').addClass('active');
$('.sidebar-menu').find('#menu-persediaan-produkdagang').addClass('active');
$('.sidebar-menu').find('#menu-stok-opname').addClass('active');
*/
//--></script>

<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>

<?php echo $footer; ?>
