<?php echo $header; ?>
<div id="content" class="content-wrapper">
  <section class="content-header">
    <h1>

    </h1>

  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Tunjangan Pegawai</h3>
            <div class="button pull-right">
									<a onclick="$('#form').submit();" class="button"><button type="button" class="btn btn-primary">Simpan</button></a>
                  <a href="<?php echo $cancel; ?>"><button type="button" class="btn btn-warning">Kembali</button></a>
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
                <?php if (isset($success)) { ?>
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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                  <table class="table table-stripped">
                    <tr>
                      <td><span class="required">*</span>  Nama Tunjangan</td>
                      <td><input class="form-control" type="text" name="name" size="100" value="<?php echo isset($name) ? $name : ''; ?>" required />

                      </td>
                    </tr>
                    <tr>
                      <td>Satuan</td>
                      <td>
                          <select class="form-control" name="satuan">
                              <option value="1" <?php echo isset($satuan) ?($satuan == 1?'selected':'') : ''; ?>>Harian</option>
                              <option value="2" <?php echo isset($satuan) ?($satuan == 2?'selected':'') : ''; ?>>Bulanan</option>
                          </select>
                      </td>
                    </tr>
                    <tr>
                      <td>Status</td>
                      <td>
                          <select class="form-control" name="status">
                              <option value="1" <?php echo isset($status) ?($status == 1?'selected':'') : ''; ?>>Aktif</option>
                              <option value="2" <?php echo isset($status) ?($status == 2?'selected':'') : ''; ?>>Tidak Aktif</option>
                          </select>
                      </td>
                    </tr>
                    </table>
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
$('.sidebar-menu').find('#menu-kepegawaian').addClass('active');
$('.sidebar-menu').find('#menu-spg-toko').addClass('active');

</script>
<?php echo $footer; ?>
