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
            <h3 class="box-title">Tambah COA</h3>
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
                      <td><span class="required">*</span> Nama COA</td>
                      <td><input class="form-control" type="text" name="name" size="100" value="<?php echo $name; ?>" />
                        <?php if ($error_name) { ?>
                        <span class="help-block"><?php echo $error_name; ?></span>
                        <?php } ?></td>
                    </tr>
                    <tr>
                      <td><span class="required">*</span> Kode Rekening</td>
                      <td><input class="form-control" type="text" name="kode_rek" size="100" value="<?php echo $kode_rek; ?>" />
                        <?php if ($error_rek) { ?>
                        <span class="help-block"><?php echo $error_rek; ?></span>
                        <?php } ?></td>
                    </tr>

                    <tr>
                      <td>Parent</td>
                      <td><select class="form-control" name="parent_id">
                          <option value="0">Tidak Ada</option>
                          <?php foreach ($categories as $category) { ?>
                          <?php if ($category['category_id'] == $parent_id) { ?>
                          <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>


                    <tr>
                      <td>Urutan Tampil</td>
                      <td><input class="form-control" type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
                    </tr>
                    <tr>
                      <td>Type Akun</td>
                      <td><select class="form-control" name="type">
                          <option value="1" <?php echo $type == 1?'selected':''; ?>>Aktiva</option>
                          <option value="2" <?php echo $type == 2?'selected':''; ?>>Hutang</option>
                          <option value="3" <?php echo $type == 3?'selected':''; ?>>Modal</option>
                          <option value="4" <?php echo $type == 4?'selected':''; ?>>Pendapatan</option>
                          <option value="5" <?php echo $type == 5?'selected':''; ?>>Harga Pokok Penjualan</option>
                          <option value="6" <?php echo $type == 6?'selected':''; ?>>Beban</option>
                          <option value="7" <?php echo $type == 7?'selected':''; ?>>Pendapatan Lain-lain</option>
                          <option value="8" <?php echo $type == 8?'selected':''; ?>>Beban Lain-Lain</option>
                          <option value="8" <?php echo $type == 9?'selected':''; ?>>Pendapatan dan Biaya Luar Biasa</option>
                        </select></td>
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
$('.sidebar-menu').find('#menu-buku-besar').addClass('active');
$('.sidebar-menu').find('#menu-pencatatan-tagihan').addClass('active');
</script>

<?php echo $footer; ?>
