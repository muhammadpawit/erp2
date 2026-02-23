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
            <h3 class="box-title">Menu</h3>
            <div class="button pull-right">
                    <a onclick="location = '<?php echo $insert; ?>'" class="btn btn-info">Tambah</a>
                    <a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="btn btn-danger">Hapus</a>

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
                <form action="" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-bordered">

                      <thead>
                        <tr>
                          <th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                          <th class="left"><?php if ($sort == 'id.name') { ?>
                            <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>">Nama Menu</a>
                            <?php } else { ?>
                            <a href="<?php echo $sort_title; ?>">Nama Menu</a>
                            <?php } ?></th>

                          <th class="right">Grup</th>
                          <th class="right">URL</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td></td>

                          <td><input type="text" name="filter_name" class="form-control" value=""></td>
                          <td>
                            <select class="form-control" name="filter_group">
                              <option value="*">Semua Grup Menu</option>
                              <option value="Master Data">Master Data</option>
                              <option value="Persediaan">Persediaan</option>
                              <option value="Customer">Customer</option>
                              <option value="Pembelian">Pembelian</option>
                              <option value="Penjualan">Penjualan</option>
                              <option value="Produksi">Produksi</option>
                              <option value="Keuangan">Keuangan</option>
                              <option value="Akuntansi">Akuntansi</option>
                              <option value="Kepegawaian">Kepegawaian</option>

                              <option value="Laporan">Laporan</option>
                              <option value="Pajak">Pajak</option>
                              <option value="Pengaturan">Pengaturan</option>



                            </select>
                            </td>
                          <td>
                          </td>
                          <td><a onclick="filter();" class="btn btn-info">Filter</a></td>
                        </tr>

                        <?php if ($informations) { ?>
                        <?php foreach ($informations as $information) { ?>
                        <tr>
                          <td style="text-align: center;"><?php if ($information['selected']) { ?>
                            <input type="checkbox" name="selected[]" value="<?php echo $information['menu_id']; ?>" checked="checked" />
                            <?php } else { ?>
                            <input type="checkbox" name="selected[]" value="<?php echo $information['menu_id']; ?>" />
                            <?php } ?></td>
                          <td class="left"><?php echo $information['nama']; ?></td>
                        <td class="left"><?php echo $information['grouping']; ?></td>
                          <td class="right"><?php echo $information['url']; ?></td>
                          <td class="right"><?php foreach ($information['action'] as $action) { ?>
                            <a href="<?php echo $action['href']; ?>" class="badge bg-yellow"><?php echo $action['text']; ?></a>
                            <?php } ?></td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                        <tr>
                          <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
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
$('.sidebar-menu').find('#menu-system').addClass('active');
$('.sidebar-menu').find('#menu-menu').addClass('active');

//--></script>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=website/menu&token=<?php echo $this->request->get['token']; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

  var filter_group = $('select[name=\'filter_group\']').val();

	if (filter_group != '*') {
		url += '&filter_group=' + encodeURIComponent(filter_group);
	}


	location = url;
}
//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<?php echo $footer; ?>
