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
              <a onclick="$('#form').submit();" class="button"><button type="button" class="btn btn-primary">Simpan</button></a>
              <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-warning">Kembali</button></a>
						</div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">

                <?php
				$res_success = !empty($success) ? $success : '';
				if ($res_success) { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <?php echo $res_success; ?>
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
                  <table class="table">
                    <tr>
                      <td><span class="required">*</span> Nama Menu</td>
                      <td><input class="form-control" type="text" name="nama" size="100" value="<?php echo isset($nama) ? $nama : ''; ?>" />
                        <?php if (isset($error_nama)) { ?>
                        <span class="error"><?php echo $error_nama; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> URL</td>
                      <td><input class="form-control" type="text" name="url" size="100" value="<?php echo isset($url) ? $url : ''; ?>" />
                        <?php if (isset($error_url)) { ?>
                        <span class="error"><?php echo $error_url; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Group</td>
                      <td>
                        <select class="form-control" name="grouping">
                          <option value="Master Data" <?php echo isset($grouping) ? ($grouping == 'Master Data'?'selected':''): ''; ?>>Master Data</option>
                          <option value="Persediaan" <?php echo isset($grouping) ? ($grouping == 'Persediaan'?'selected':''): ''; ?>>Persediaan</option>
                          <option value="Customer" <?php echo isset($grouping) ? ($grouping == 'Customer'?'selected':''): ''; ?>>Customer</option>
                          <option value="Pembelian" <?php echo isset($grouping) ? ($grouping == 'Pembelian'?'selected':''): ''; ?>>Pembelian</option>
                          <option value="Penjualan" <?php echo isset($grouping) ? ($grouping == 'Penjualan'?'selected':''): ''; ?>>Penjualan</option>
                          <option value="Produksi" <?php echo isset($grouping) ? ($grouping == 'Produksi'?'selected':''): ''; ?>>Produksi</option>
                          <option value="Akuntansi" <?php echo isset($grouping) ? ($grouping == 'Akuntansi'?'selected':''): ''; ?>>Akuntansi</option>
                          <option value="Kepegawaian" <?php echo isset($grouping) ? ($grouping == 'Kepegawaian'?'selected':''): ''; ?>>Kepegawaian</option>
                          <option value="Keuangan" <?php echo isset($grouping) ? ($grouping == 'Keuangan'?'selected':''): ''; ?>>Keuangan</option>
                          <option value="Laporan" <?php echo isset($grouping) ? ($grouping == 'Laporan'?'selected':''): ''; ?>>Laporan</option>
                          <option value="Pajak" <?php echo isset($grouping) ? ($grouping == 'Pajak'?'selected':''): ''; ?>>Pajak</option>
                          <option value="Pengaturan" <?php echo isset($grouping) ? ($grouping == 'Pengaturan'?'selected':''): ''; ?>>Pengaturan</option>
                        </select>
                        <!--input class="form-control" type="text" name="grouping" size="100" value="<?php echo isset($grouping) ? $grouping : ''; ?>" /-->
                        <?php if (isset($error_grouping)) { ?>
                        <span class="error"><?php echo $error_grouping; ?></span>
                        <?php } ?></td>
                    </tr>

                    <tr>
                      <td>Urutan</td>
                      <td><input class="form-control" type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
                    </tr>
                  </table>
                </form>
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
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<?php echo $footer; ?>
